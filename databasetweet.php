<?php 
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

try{
    $database = new PDO('mysql:host=localhost;dbname=twitterlike' , 'root' , '');
}    
catch(PDOException $e){
    die('Site indisponible');
}

$requete = $database->prepare("SELECT ID_Publication,Contenu,ID_Utilisateur FROM publication");
$requete->execute();

$tweets = $requete->fetchAll(PDO::FETCH_ASSOC);
$_SESSION['tweets'] = $tweets;

$requete = $database->prepare("SELECT ID_Utilisateur,Pseudo,Email FROM utilisateur ");
$requete->execute();

$users = $requete->fetchAll(PDO::FETCH_ASSOC);
$_SESSION['users'] = $users;

if(isset($_POST['form']) && $_POST['form'] =='publication'){
    if($_POST['publication'] != ''){
        $tweet = [
            'Contenu' => $_POST['publication'],
            'ID_Utilisateur' => $_SESSION['connection']['ID_Utilisateur']
        ];
        $requete = $database->prepare("INSERT INTO publication (Contenu, ID_Utilisateur) VALUES (:Contenu, :ID_Utilisateur)");
        $requete->execute($tweet);
        header('Location: main.php');
        exit();
    }
}
?>
