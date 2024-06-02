<?php
session_start();
if (!isset($_SESSION['pseudo'])) {
    header("Location: index.php");
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "twitterlike";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$pseudo = $_SESSION['pseudo'];
$userIdQuery = "SELECT ID_Utilisateur FROM utilisateur WHERE Pseudo='$pseudo'";
$userIdResult = $conn->query($userIdQuery);
$userIdRow = $userIdResult->fetch_assoc();
$userId = $userIdRow['ID_Utilisateur'];

// Récupérer les groupes de l'utilisateur
$groupsQuery = "
    SELECT g.ID_Groupe, g.Nom_Groupe 
    FROM Groupe g 
    JOIN Groupe_Utilisateur gu ON g.ID_Groupe = gu.ID_Groupe 
    WHERE gu.ID_Utilisateur = $userId";
$groupsResult = $conn->query($groupsQuery);

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messagerie ECE In</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            height: 100vh;
        }
        .sidebar {
            width: 30%;
            background: #fff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            overflow-y: auto;
        }
        .chat {
            width: 70%;
            background: #fff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
        }
        .message {
            background: #e9e9e9;
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
        }
        .header {
            background: #fff;
            padding: 10px;
            width: 100%;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .group {
            padding: 10px;
            margin: 10px 0;
            background: #e9e9e9;
            border-radius: 5px;
            cursor: pointer;
        }
        .group:hover {
            background: #dcdcdc;
        }
        .group-form {
            margin-bottom: 20px;
        }
        .group-form input, .group-form button {
            width: calc(100% - 22px);
            padding: 10px;
            margin: 5px 0;
        }
        .chat-messages {
            flex-grow: 1;
            overflow-y: auto;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
<header>
        <?php require('header.php'); ?>
    </header>
    <div class="sidebar">
        <div class="header">
            <div>Bienvenue, <?php echo htmlspecialchars($pseudo); ?></div>
            <div>
                <a href="logout.php">Se déconnecter</a>
                <a href="index.php">Retour</a>
            </div>
        </div>
        <div class="group-form">
            <h3>Créer un groupe</h3>
            <form id="createGroupForm" action="create_group.php" method="POST">
                <input type="text" name="group_name" placeholder="Nom du groupe" required>
                <label for="number_of_users">Nombre d'utilisateurs (2-4):</label>
                <select id="number_of_users" name="number_of_users" required>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                </select>
                <div id="additional_users">
                    <!-- Les champs pour les utilisateurs supplémentaires seront ajoutés ici -->
                    <input type="text" name="user_2" placeholder="Pseudo utilisateur 2">
                </div>
                <button type="submit">Créer</button>
            </form>
        </div>
        <div class="groups">
            <h3>Vos groupes</h3>
            <?php
            if ($groupsResult->num_rows > 0) {
                while($groupRow = $groupsResult->fetch_assoc()) {
                    echo "<div class='group' data-group-id='" . $groupRow['ID_Groupe'] . "'>" . htmlspecialchars($groupRow['Nom_Groupe']) . "</div>";
                }
            } else {
                echo "Aucun groupe.";
            }
            ?>
        </div>
    </div>
    <div class="chat">
        <div class="chat-messages" id="chatMessages">
            <!-- Messages seront chargés ici -->
        </div>
        <form id="messageForm" style="display: none;">
            <textarea name="message" id="messageInput" placeholder="Tapez votre message ici..." required></textarea>
            <button type="submit">Envoyer</button>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.group').forEach(function(groupElement) {
                groupElement.addEventListener('click', function() {
                    const groupId = this.getAttribute('data-group-id');
                    fetchMessages(groupId);
                    document.getElementById('messageForm').style.display = 'block';
                    document.getElementById('messageForm').setAttribute('data-group-id', groupId);
                });
            });

            document.getElementById('messageForm').addEventListener('submit', function(event) {
                event.preventDefault();
                const groupId = this.getAttribute('data-group-id');
                const message = document.getElementById('messageInput').value;
                sendMessage(groupId, message);
            });

            document.getElementById('number_of_users').addEventListener('change', function() {
                const numberOfUsers = this.value;
                const additionalUsersContainer = document.getElementById('additional_users');
                additionalUsersContainer.innerHTML = '';
                for (let i = 1; i < numberOfUsers; i++) {
                    const input = document.createElement('input');
                    input.type = 'text';
                    input.name = `user_${i}`;
                    input.placeholder = `Pseudo utilisateur ${i}`;
                    input.required = true;
                    additionalUsersContainer.appendChild(input);
                }
            });

            function fetchMessages(groupId) {
                fetch(`fetch_messages.php?group_id=${groupId}`)
                    .then(response => response.json())
                    .then(data => {
                        const chatMessages = document.getElementById('chatMessages');
                        chatMessages.innerHTML = '';
                        data.forEach(msg => {
                            const msgDiv = document.createElement('div');
                            msgDiv.className = 'message';
                            msgDiv.innerHTML = `<strong>${msg.Pseudo}:</strong> ${msg.Contenu} <br> <small>${msg.Date_Message}</small>`;
                            chatMessages.appendChild(msgDiv);
                        });
                    });
            }

            function sendMessage(groupId, message) {
                fetch('send_message.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        group_id: groupId,
                        message: message
                    })
                }).then(() => {
                    document.getElementById('messageInput').value = '';
                    fetchMessages(groupId);
                });
            }
        });
    </script>
</body>
</html>