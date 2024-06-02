<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <!-- Meta tag pour un design réactif -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <!-- Lien vers le fichier CSS externe -->
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <!-- Affichage d'une image avec une balise alt pour le texte alternatif -->
    <img src="Blaz.jpg" alt="Une belle photo">
    <header>
        <!-- Inclusion du fichier header.php pour le contenu de l'en-tête -->
        <?php require('header.php') ?>
    </header>
    <main>
        <!-- Inclusion des fichiers nécessaires pour l'interaction avec la base de données -->
        <?php 

            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }
            require('database.php'); // Connexion à la base de données principale

            // Vérification de l'état de connexion de l'utilisateur
            if ($_SESSION['connection']['state'] == false) {
                // Affichage du formulaire de création de compte si l'utilisateur n'est pas connecté
                echo '<form action="database.php" method="POST">
                <input type="hidden" name="form" value="ajoutercompte">
                <div>' . $messageerreur . '</div>
                <label for="Pseudo">Pseudo</label>
                <input type="text" name="Pseudo" id="Pseudo">
                <label for="Email">Email</label>
                <input type="text" name="Email" id="Email">
                <label for="MotDePasse">MotDePasse</label>
                <input type="password" name="MotDePasse" id="MotDePasse">
                <button type="submit">Envoyer</button>
                <a href="logine.php"> <button type="button">Je possède déjà un compte</button></a>';
            
            // Si l'utilisateur est connecté
            } else if ($_SESSION['connection']['state'] == true) {
                // Récupération des utilisateurs et des tweets depuis la session
                $users = isset($_SESSION['users']) ? $_SESSION['users'] : [];
                $tweets = isset($_SESSION['tweets']) ? $_SESSION['tweets'] : [];
                                
                // Recharger les utilisateurs depuis la base de données
                $stmt = $database->query("SELECT * FROM utilisateur");
                $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

                echo '<div>';
                foreach ($users as $user) {
                    if ($user['ID_Utilisateur'] == $_SESSION['connection']['ID_Utilisateur']) {
                        echo '<h2> Bienvenue :' . $user['Pseudo'] . '</h2>';
                    }
                }

                echo '
                <form action="deco.php" method="POST" value="supprimer">
                    <input type="hidden" name="form">
                    <button type="submit">Se déconnecter</button>
                </form>
                ';

                // Récupérer les informations de l'utilisateur
                $stmt = $database->prepare("SELECT Pseudo, Photo_de_profil, Description FROM utilisateur WHERE ID_Utilisateur = :id");
                $stmt->bindParam(':id', $_SESSION['connection']['ID_Utilisateur'], PDO::PARAM_INT);
                $stmt->execute();
                $utilisateur = $stmt->fetch(PDO::FETCH_ASSOC);

                // Vérifier si l'utilisateur existe
                if (!$utilisateur) {
                    die("Utilisateur non trouvé.");
                }

                echo '
                <div class="container">
                    <div class="profile">
                        <img src="' . htmlspecialchars($utilisateur['Photo_de_profil']) . '" alt="Photo de profil" class="profile-photo">
                        <p>' . nl2br(htmlspecialchars($utilisateur['Description'])) . '</p>
                        <p> Vous etes désormais dans votre réseau, vous trouverez ci dessous la liste de vos amis : </p>
                        <p> 
                        </p>
                    </div>
                </div>
                ';
                // Récupération des informations de connexion
                $currentUserId = $_SESSION['connection']['ID_Utilisateur'];

                // Utiliser la connexion à la base de données définie dans database.php
                require('database.php'); // Connexion à la base de données principale
                
                // Récupérer les connexions directes
                $query = "
                    SELECT c.ID_Utilisateur_2, u1.Pseudo as Pseudo1, u2.Pseudo as Pseudo2 
                    FROM connexion c 
                    JOIN utilisateur u1 ON c.ID_Utilisateur_1 = u1.ID_Utilisateur 
                    JOIN utilisateur u2 ON c.ID_Utilisateur_2 = u2.ID_Utilisateur 
                    WHERE c.ID_Utilisateur_1 = :currentUserId
                ";
                $stmt = $database->prepare($query);
                $stmt->bindParam(':currentUserId', $currentUserId, PDO::PARAM_INT);
                if ($stmt->execute()) {
                    $connections = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    if (count($connections) > 0) {
                        foreach ($connections as $connection) {
                            echo $connection['Pseudo1'] . ' est ami avec ' . $connection['Pseudo2'] . '<br>';
                        }
                    } else {
                        echo "Aucune connexion trouvée pour cet utilisateur.<br>";
                    }
                } else {
                    echo "Erreur lors de l'exécution de la requête : " . $stmt->errorInfo()[2];
                }

                // Récupérer les amis des amis
                $query = "
                    SELECT u2.Pseudo as Ami, u3.Pseudo as AmiDeAmi
                    FROM connexion c1
                    JOIN connexion c2 ON c1.ID_Utilisateur_2 = c2.ID_Utilisateur_1
                    JOIN utilisateur u1 ON c1.ID_Utilisateur_1 = u1.ID_Utilisateur
                    JOIN utilisateur u2 ON c1.ID_Utilisateur_2 = u2.ID_Utilisateur
                    JOIN utilisateur u3 ON c2.ID_Utilisateur_2 = u3.ID_Utilisateur
                    WHERE c1.ID_Utilisateur_1 = :currentUserId AND c2.ID_Utilisateur_2 != :currentUserId
                ";
                $stmt = $database->prepare($query);
                $stmt->bindParam(':currentUserId', $currentUserId, PDO::PARAM_INT);
                if ($stmt->execute()) {
                    $friendsOfFriends = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    if (count($friendsOfFriends) > 0) {
                        foreach ($friendsOfFriends as $friendOfFriend) {
                            echo 'Votre ami ' . $friendOfFriend['Ami'] . ' est ami avec ' . $friendOfFriend['AmiDeAmi'] . '<br>';
                        }                        
                    } else {
                        echo "Aucun ami d'ami trouvé pour cet utilisateur.";
                    }
                } else {
                    echo "Erreur lors de l'exécution de la requête : " . $stmt->errorInfo()[2];
                }
            }
        ?>
    </main>
</body>
</html>
