<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<img src="Blaz.jpg" alt="Une belle photo">
<header>
    <?php require('header.php'); ?>
</header>
<main>
    <?php
    require('database.php');

    // Vérifiez si une session est déjà active avant de démarrer une nouvelle session
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    // Vérifiez si l'utilisateur est connecté en tant qu'administrateur
    if ($_SESSION['connection']['state'] == true && $_SESSION['connection']['ID_Utilisateur'] == 500) {
        // Vérifiez si une action de suppression a été demandée
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] == 'delete') {
            $userID = $_POST['ID_Utilisateur'];

            // Préparez et exécutez la requête de suppression
            $stmt = $database->prepare("DELETE FROM utilisateur WHERE ID_Utilisateur = :ID_Utilisateur");
            $stmt->bindParam(':ID_Utilisateur', $userID, PDO::PARAM_INT);
            if ($stmt->execute()) {
                // Redirigez pour éviter une nouvelle soumission du formulaire après actualisation de la page
                header('Location: admin.php');
                exit();
            } else {
                echo "Erreur lors de la suppression de l'utilisateur.";
            }
        }

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
                <p>Bienvenue sur le profil administrateur, vous pouvez maintenant supprimer les auteurs commme vous le voulez :</p>
                <p> 
                </p>
            </div>
        </div>
        ';

        echo '<h2> Liste des utilisateurs :</h2>';
        if (!empty($users)) {
            echo '<ul>';
            foreach ($users as $user) {
                echo '<li>
                        <span>' . $user['Pseudo'] . ' (' . $user['Email'] . ')</span>
                        <form action="admin.php" method="POST" style="display:inline;">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="ID_Utilisateur" value="' . $user['ID_Utilisateur'] . '">
                            <button type="submit">Supprimer</button>
                        </form>
                      </li>';
            }
            echo '</ul>';
        } else {
            echo '<p>Aucun utilisateur trouvé</p>';
        }

       
    } else {
        // Rediriger vers logine.php si l'utilisateur n'est pas connecté en tant qu'administrateur
        header('Location: logine.php');
        exit();
    }
    ?>
</main>
<footer>
    &copy; 2024 Votre Entreprise. Tous droits réservés.
</footer>
</body>
</html>
