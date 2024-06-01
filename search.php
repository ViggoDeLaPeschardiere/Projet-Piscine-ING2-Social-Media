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
        <form action="search.php" method="POST">
            <input type="hidden" name="form" value="search">
            <label for="search">Recherche</label>
            <input type="text" name="search" id="search">
            <button type="submit">Rechercher un tweet</button>
        </form>
        <div>
            <?php
                require('databasetweet.php');
                // Initialise $search à une chaîne vide par défaut
                $search = '';
                
                // Vérifie si la clé 'search' existe dans $_POST, puis affecte sa valeur à $search
                if (isset($_POST['search'])) {
                    $search = $_POST['search'];
                }

                // Parcours tous les tweets
                foreach ($tweets as $tweet) {
                    // Vérifie si la chaîne de recherche existe dans le contenu du tweet
                    if (!empty($search) && strpos($tweet['contenu'], $search) !== false) {
                        foreach ($users as $user) {
                            if($user['id'] == $tweet['userid']){
                                echo '
                                    <section><div>
                                        <h2>' . $user['pseudo'] . '</h2>
                                    </div>';
                            }
                        }
                        echo '<div class="tweet">' . $tweet['contenu'] . '</div></section>';
                        
                    }
                }
            ?>
        </div>
    </main>
</body>
</html>