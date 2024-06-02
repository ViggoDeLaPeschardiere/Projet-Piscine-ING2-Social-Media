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
            require('database.php'); // Connexion à la base de données principale
            require('databasetweet.php'); // Connexion à la base de données des tweets

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
                
                // Affichage d'un message de bienvenue pour l'utilisateur connecté
                foreach ($users as $user) {
                    if ($user['ID_Utilisateur'] == $_SESSION['connection']['ID_Utilisateur']) {
                        echo '<h2> Bienvenue :' . $user['Pseudo'] . '</h2>';
                    }
                } 
                
                // Formulaire pour se déconnecter
                echo '
                <form action="deco.php" method="POST" value="supprimer">
                    <input type="hidden" name="form">
                    <button type="submit">Se déconnecter</button>
                </form>
                ';
                
                // Affichage des tweets de l'utilisateur connecté
                echo '<h2> Vos tweets :</h2>';
                if (!empty($tweets)) {
                    foreach ($tweets as $tweet) {
                        // Vérification que le tweet appartient à l'utilisateur connecté
                        if ($tweet['ID_Utilisateur'] == $_SESSION['connection']['ID_Utilisateur']) {
                            // Affichage du titre du tweet
                            echo '<div class="tweet">' . $tweet['Titre'] . '</div>';
                            // Affichage de l'image associée au tweet, si elle existe
                            if ($tweet['URL']) {
                                echo '<img src="' . $tweet['URL'] . '" alt="Image associée au tweet">';
                            }
                            // Affichage du contenu du tweet
                            echo '<div class="tweet">' . $tweet['Contenu'] . '</div>';
                        }
                    }
                } else {
                    // Message si aucun tweet n'est disponible
                    echo '<p>Aucun tweet disponible</p>';
                }
            }
        ?>
    </main>
    <footer></footer>
</body>
</html>
