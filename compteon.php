<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <header>
        <?php require('header.php') ?>
    </header>
    <main>
        <?php 
            require('database.php');
            if($connection['state'] == false){
                echo '<form action="database.php" method="POST">
                <input type="hidden" name="form" value="ajoutercompte">
                <label for="Pseudo">Pseudo</label>
                <input type="text" name="Pseudo" id="Pseudo">
                <label for="Email">Email</label>
                <input type="text" name="Email" id="Email">
                <label for="MotDePasse">Mot de passe</label>
                <input type="password" name="MotDePasse" id="MotDePasse">
                <button type="submit">Envoyer</button>';
            }
            else if($connection['state'] == true){
                echo '
                    <div>
                        <h2>' . $users[$connection['ID_Utilisateur']]['Pseudo'] . '</h2>
                    </div>
                    <div>
                        <!-- nom du compte -->
                    </div>
                    <div>
                        <!-- nom du compte -->
                    </div>
                ';
            }
        ?>
    </main>
    <footer></footer>
</body>
</html>
