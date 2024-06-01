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
session_start(); 

$connection = isset($_SESSION['connection']) ? $_SESSION['connection'] : ['state' => false, 'id' => 0];
$messageerreur = isset($_SESSION['messageerreur']) ? $_SESSION['messageerreur'] : '.';
$x = isset($_SESSION['x']) ? $_SESSION['x'] : false;

try {
    $database = new PDO('mysql:host=localhost;dbname=twitterlike', 'root', 'root');
} catch (PDOException $e) {
    die('Site indisponible');
}

if ($connection['state'] == false) {
    $requete = $database->prepare("SELECT ID_Utilisateur, Pseudo, Email, mdp FROM Utilisateur");
    $requete->execute();
    $users = $requete->fetchAll(PDO::FETCH_ASSOC);
    $_SESSION['users'] = $users;

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['form'] == 'ajoutercompte') {
        if ($_POST['pseudo'] != '' || $_POST['email'] != '') {
            $nouveluser = [
                'Pseudo' => $_POST['pseudo'],
                'Email' => $_POST['email'],
                'mdp' => $_POST['password'],
            ];

            foreach ($users as $user) {
                if ($user['Pseudo'] == $nouveluser['Pseudo']) {
                    $_SESSION['messageerreur'] = 'Pseudo déjà utilisé';
                    header('Location: compte.php');
                    exit();
                } else if ($user['Email'] == $nouveluser['Email']) {
                    $_SESSION['messageerreur'] = 'Email déjà utilisé';
                    header('Location: compte.php');
                    exit();
                }
            }

            $requete = $database->prepare("INSERT INTO Utilisateur (Pseudo, Email, mdp) VALUES (:Pseudo, :Email, :mdp)");
            if ($requete->execute($nouveluser)) {
                $newUserId = $database->lastInsertId();
                $connection['state'] = true;
                $connection['id'] = $newUserId;
                $_SESSION['connection'] = $connection;
                header('Location: compte.php');
                exit();
            } else {
                echo '<div>Erreur lors de l\'inscription</div>';
            }
        } else {
            echo 'Formulaire incomplet';
        }
    }
}

$x = $_SESSION['x'];
if ($connection['state'] == true && $x == false) {
    $_SESSION['messageerreur'] = '';
    $requete = $database->prepare("SELECT ID_Utilisateur, Pseudo, Email FROM Utilisateur");
    $requete->execute();
    $users = $requete->fetchAll(PDO::FETCH_ASSOC);
    $_SESSION['x'] = true;
    header("Location: compte.php");
    exit;
}
?>
</body>
</html>
