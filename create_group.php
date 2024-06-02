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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $pseudo = $_SESSION['pseudo'];
    $groupName = $_POST['group_name'];
    $numberOfUsers = $_POST['number_of_users'];

    $userIdQuery = "SELECT ID_Utilisateur FROM Utilisateur WHERE Pseudo='$pseudo'";
    $userIdResult = $conn->query($userIdQuery);
    $userIdRow = $userIdResult->fetch_assoc();
    $userId = $userIdRow['ID_Utilisateur'];

    // Vérification des pseudos des utilisateurs à ajouter
    $validUsers = true;
    $usersToAdd = [];
    for ($i = 1; $i <= $numberOfUsers; $i++) {
        $userKey = 'user_' . $i;
        if (isset($_POST[$userKey])) {
            $userPseudo = $_POST[$userKey];
            $userCheckQuery = "SELECT ID_Utilisateur FROM Utilisateur WHERE Pseudo='$userPseudo'";
            $userCheckResult = $conn->query($userCheckQuery);
            if ($userCheckResult->num_rows > 0) {
                $userCheckRow = $userCheckResult->fetch_assoc();
                $usersToAdd[] = $userCheckRow['ID_Utilisateur'];
            } else {
                $validUsers = false;
                break;
            }
        }
    }

    if ($validUsers) {
        // Création du groupe
        $createGroupQuery = "INSERT INTO Groupe (Nom_Groupe) VALUES ('$groupName')";
        if ($conn->query($createGroupQuery) === TRUE) {
            $groupId = $conn->insert_id;

            // Ajout du créateur du groupe dans Groupe_Utilisateur
            $conn->query("INSERT INTO Groupe_Utilisateur (ID_Groupe, ID_Utilisateur) VALUES ($groupId, $userId)");

            // Ajout des autres utilisateurs dans Groupe_Utilisateur
            foreach ($usersToAdd as $otherUserId) {
                $conn->query("INSERT INTO Groupe_Utilisateur (ID_Groupe, ID_Utilisateur) VALUES ($groupId, $otherUserId)");
            }

            header("Location: messagerie.php");
        } else {
            echo "Erreur lors de la création du groupe : " . $conn->error;
        }
    } else {
        // Afficher un message d'erreur si au moins un des pseudos est incorrect
        echo "<script>alert('Au moins un pseudo renseigné est invalide. Veuillez vérifier et réessayer.');</script>";
    }
}

$conn->close();
?>