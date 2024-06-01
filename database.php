<?php
session_start();

$connection = isset($_SESSION['connection']) ? $_SESSION['connection'] : ['state' => false, 'ID_Utilisateur' => 0];
$messageerreur = isset($_SESSION['messageerreur'])? $_SESSION['messageerreur'] : '.';
$x = isset($_SESSION['x'])? $_SESSION['x'] : false;

try{
    $database = new PDO('mysql:host=localhost;dbname=twitterlike' , 'root' , '');
}    
catch(PDOException $e){
    die('Site indisponible');
}


if($connection['state'] == false){
    $requete = $database->prepare("SELECT ID_Utilisateur,Pseudo,Email,MotDePasse FROM utilisateur ");
    $requete->execute();

    $users = $requete->fetchAll(PDO::FETCH_ASSOC);
    $_SESSION['users'] = $users;

    if($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['form'] =='ajoutercompte') {
        if($_POST['Pseudo'] != '' || $_POST['Email'] != ''){
            $nouveluser = [
                'Pseudo' => $_POST['Pseudo'],
                'Email' => $_POST['Email'],
                'MotDePasse' => $_POST['MotDePasse'],
            ];

            foreach($users as $user){
                if($user['Pseudo'] == $nouveluser['Pseudo']){
                    $_SESSION['messageerreur'] = 'Pseudo déjà utilisé';
                    header('Location: compte.php');
                    exit();
                }
                else if($user['Email'] == $nouveluser['Email']){
                    $_SESSION['messageerreur'] = 'Email déjà utilisé';
                    header('Location: compte.php');
                    exit();
                }
            }

            $requete = $database->prepare("INSERT INTO utilisateur (Pseudo, Email, MotDePasse) VALUES (:Pseudo, :Email, :MotDePasse)");
            if($requete->execute($nouveluser)) {
                $newUserId = $database->lastInsertId();
                $connection['state'] = true;
                $connection['ID_Utilisateur'] = $newUserId;
                $_SESSION['connection'] = $connection;
                $_SESSION['x'] = true;
                header("Location: compte.php");
                exit();
            }
            else {
                echo '<div>Erreur</div>';
            }
        }
        else {
            echo 'Formulaire incomplet';
        }
    }
}

$x = $_SESSION['x'];
if ($connection['state'] == true && $x == false) {
    $_SESSION['messageerreur'] = '';
    $requete = $database->prepare("SELECT ID_Utilisateur,Pseudo,Email FROM utilisateur ");
    $requete->execute();

    $users = $requete->fetchAll(PDO::FETCH_ASSOC);
    $_SESSION['x'] = true;
    header("Location: compte.php");
    exit();
}
?>
