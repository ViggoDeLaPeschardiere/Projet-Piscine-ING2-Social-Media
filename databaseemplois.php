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
    $requete = $database->prepare("
        SELECT * FROM Emploi ORDER BY ID_Emploi DESC
    ");
    $requete->execute();
    $emplois = $requete->fetchAll(PDO::FETCH_ASSOC);
    $_SESSION['emplois'] = $emplois;
} catch (PDOException $e) {
    echo 'Erreur SQL: ' . $e->getMessage();
}
?>
