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
    // Récupération des informations de la table "infosece"
    $requete = $database->prepare("SELECT ID, titre, contenu FROM infosece ORDER BY ID DESC");
    $requete->execute();
    $infosece = $requete->fetchAll(PDO::FETCH_ASSOC);
    $_SESSION['infosece'] = $infosece;
} catch (PDOException $e) {
    echo 'Erreur SQL: ' . $e->getMessage();
}
?>
