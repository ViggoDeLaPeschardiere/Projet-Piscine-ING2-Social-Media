<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <?php require('header.php') ?>
    </header>
    <main>
        <?php 
            
        require('database.php');
        require('databasetweet.php');
        
        if ($_SESSION['connection']['state'] == false) {
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
            <a href="login.php"> <button type="button">Je possède deja un compte</button></a>';
        } else if ($_SESSION['connection']['state'] == true) {
            $users = isset($_SESSION['users']) ? $_SESSION['users'] : [];
            $tweets = isset($_SESSION['tweets']) ? $_SESSION['tweets'] : [];
            
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
            
            echo '<h2> Vos tweets :</h2>';
            if (!empty($tweets)) {
                foreach ($tweets as $tweet) {
                    if ($tweet['ID_Utilisateur'] == $_SESSION['connection']['ID_Utilisateur']) {
                        foreach ($users as $user) {
                            if ($user['ID_Utilisateur'] == $tweet['ID_Utilisateur']) {
                                echo '
                                <section><div>
                                    <h2>' . $user['Pseudo'] . '</h2>
                                </div>';
                            }
                        }
                        echo '<div class="tweet">' . $tweet['Contenu'] . '</div></section>';
                    }
                }
            } else {
                echo '<p>Aucun tweet disponible</p>';
            }
        }
        ?>

        </form>
    </main>
    <footer></footer>
</body>
</html>
