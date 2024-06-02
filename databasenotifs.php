<?php 
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

try {
    $database = new PDO('mysql:host=localhost;dbname=twitterlike', 'root', '');
    $database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die('Site indisponible: ' . $e->getMessage());
}

try {
    // Récupération des emplois
    $requete = $database->prepare("SELECT * FROM notification ORDER BY ID_Notification DESC");
    $requete->execute();
    $notifs = $requete->fetchAll(PDO::FETCH_ASSOC);
    $_SESSION['notifs'] = $notifs;
} catch (PDOException $e) { 
    echo 'Erreur SQL: ' . $e->getMessage();
}
?>
