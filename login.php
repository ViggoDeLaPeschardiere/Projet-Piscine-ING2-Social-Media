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
<?php
require('database.php');
$messageerreur = '';
echo '<form action="login.php" method="POST" class="form">
    <input type="hidden" name="form" value="login">
    <div>'. $messageerreur .'</div>
    <label for="Pseudo">Pseudo</label>
    <input type="text" name="Pseudo" id="Pseudo">
    <label for="MotDePasse">Mot de passe</label>
    <input type="password" name="MotDePasse" id="MotDePasse">
    <button type="submit">Envoyer</button>';

if($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['form'] =='login') {
    if(!empty($_POST['Pseudo']) && !empty($_POST['MotDePasse'])){
        $loginuser = [
            'Pseudo' => $_POST['Pseudo'],
            'MotDePasse' => $_POST['MotDePasse'],
        ];

        foreach($users as $user){
            if($user['Pseudo'] == $loginuser['Pseudo']){
                if($user['MotDePasse'] == $loginuser['MotDePasse']){
                    $connection = [
                        'ID_Utilisateur' => $user['ID_Utilisateur'],
                        'state' => true,
                    ];
                    $_SESSION['connection'] = $connection;
                    header('Location: compte.php');
                    exit();
                }
                else{
                    $messageerreur = 'Mot de passe incorrect';
                }
            }
        }
    }
}
?>
</body>
</html>
