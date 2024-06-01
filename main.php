<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Document</title>
</head>
<body>
<img src="Blaz.jpg" alt="Une belle photo">
    <main>
    <header>
        <?php require('header.php'); ?>
    </header>
    <div class="main-content">
        <div class="extra-left-column">
            <h2>INFOS ECE</h2>
            <div>
            <?php
                require('databaseinfosece.php');
                foreach ($_SESSION['infosece'] as $info) {
                    echo '<div class="info">';
                    echo '<h3>' . htmlspecialchars($info['titre'], ENT_QUOTES, 'UTF-8') . '</h3>';
                    echo '<p>' . htmlspecialchars($info['contenu'], ENT_QUOTES, 'UTF-8') . '</p>';
                    echo '</div>';
                }
            ?>
            </div>
        </div>
        <div class="left-column">
            <form action="databasetweet.php" method="POST">
                <input type="hidden" name="form" value="publication">
                <label for="publication">Poster un tweet</label>
                <input type="text" name="publication" id="publication">
                <button type="submit">Tweeter</button>
            </form>
            <div>
            <?php
            require('databasetweet.php');
            foreach ($tweets as $tweet) {
                foreach ($users as $user) {
                    if($user['ID_Utilisateur'] == $tweet['ID_Utilisateur']){
                        echo '
                            <section><div>
                                <h2>' . $user['Pseudo'] . '</h2>
                            </div>';
                    }
                }
                echo '<div class="tweet">'. $tweet['Contenu']. '</div></section>';
            }
            ?>
            </div>

        </div>
        <div class="right-column">
            <h2>Actualites</h2>
            <div>
                <?php
                require('databaseactualites.php');
                foreach ($_SESSION['actualites'] as $actualite) {
                    echo '<div class="actualite">';
                    echo '<h3>' . htmlspecialchars($actualite['titre'], ENT_QUOTES, 'UTF-8') . '</h3>';
                    echo '<p>' . htmlspecialchars($actualite['contenu'], ENT_QUOTES, 'UTF-8') . '</p>';
                    echo '</div>';
                }
                ?>
            </div>
        </div>
    </div>
    </main>
    <footer></footer>
</body>
</html>
