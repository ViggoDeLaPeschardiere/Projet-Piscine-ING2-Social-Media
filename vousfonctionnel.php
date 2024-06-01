<?php
session_start();
require('database.php');

// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION['connection']['state']) || $_SESSION['connection']['state'] == false) {
    header('Location: login.php');
    exit();
}

// Récupère les informations de l'utilisateur connecté
$ID_Utilisateur = $_SESSION['connection']['id']; // Utilise 'id' au lieu de 'ID_Utilisateur' si c'est le champ correct dans votre base de données
$sql = "SELECT * FROM Utilisateur WHERE ID_Utilisateur = :ID_Utilisateur";
$stmt = $database->prepare($sql);
$stmt->bindParam(':ID_Utilisateur', $ID_Utilisateur, PDO::PARAM_INT);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Gérer l'ajout ou la mise à jour des informations de l'utilisateur
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_info'])) {
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $photo_de_profil = $_POST['photo_de_profil'];
    $image_de_fond = $_POST['image_de_fond'];
    $description = $_POST['description'];
    $competences = $_POST['competences'];
    $humeur = $_POST['humeur'];

    $sql = "UPDATE Utilisateur SET Nom = :nom, Prénom = :prenom, Photo_de_profil = :photo_de_profil, Image_de_fond = :image_de_fond, Description = :description, Compétences = :competences, Humeur = :humeur WHERE ID_Utilisateur = :ID_Utilisateur";
    $stmt = $database->prepare($sql);
    $stmt->bindParam(':nom', $nom, PDO::PARAM_STR);
    $stmt->bindParam(':prenom', $prenom, PDO::PARAM_STR);
    $stmt->bindParam(':photo_de_profil', $photo_de_profil, PDO::PARAM_STR);
    $stmt->bindParam(':image_de_fond', $image_de_fond, PDO::PARAM_STR);
    $stmt->bindParam(':description', $description, PDO::PARAM_STR);
    $stmt->bindParam(':competences', $competences, PDO::PARAM_STR);
    $stmt->bindParam(':humeur', $humeur, PDO::PARAM_STR);
    $stmt->bindParam(':ID_Utilisateur', $ID_Utilisateur, PDO::PARAM_INT);
    $stmt->execute();

    header('Location: vous.php');
    exit();
}

// Gérer l'ajout de formation
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_formation'])) {
    $titre_formation = $_POST['titre_formation'];
    $etablissement = $_POST['etablissement'];
    $date_debut_formation = $_POST['date_debut_formation'];
    $date_fin_formation = $_POST['date_fin_formation'];
    $description_formation = $_POST['description_formation'];

    $sql = "INSERT INTO Formation (ID_Utilisateur, Titre, Etablissement, Date_Début, Date_Fin, Description) VALUES (:ID_Utilisateur, :titre, :etablissement, :date_debut, :date_fin, :description)";
    $stmt = $database->prepare($sql);
    $stmt->bindParam(':ID_Utilisateur', $ID_Utilisateur, PDO::PARAM_INT);
    $stmt->bindParam(':titre', $titre_formation, PDO::PARAM_STR);
    $stmt->bindParam(':etablissement', $etablissement, PDO::PARAM_STR);
    $stmt->bindParam(':date_debut', $date_debut_formation, PDO::PARAM_STR);
    $stmt->bindParam(':date_fin', $date_fin_formation, PDO::PARAM_STR);
    $stmt->bindParam(':description', $description_formation, PDO::PARAM_STR);
    $stmt->execute();

    header('Location: vous.php');
    exit();
}

// Gérer l'ajout de projet
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_projet'])) {
    $titre_projet = $_POST['titre_projet'];
    $description_projet = $_POST['description_projet'];
    $date_debut_projet = $_POST['date_debut_projet'];
    $date_fin_projet = $_POST['date_fin_projet'];
    $lieu = $_POST['lieu'];
    $type_projet = $_POST['type_projet'];

    $sql = "INSERT INTO Projet (ID_Utilisateur, Titre, Description, Date_Début, Date_Fin, Lieu, Type) VALUES (:ID_Utilisateur, :titre, :description, :date_debut, :date_fin, :lieu, :type)";
    $stmt = $database->prepare($sql);
    $stmt->bindParam(':ID_Utilisateur', $ID_Utilisateur, PDO::PARAM_INT);
    $stmt->bindParam(':titre', $titre_projet, PDO::PARAM_STR);
    $stmt->bindParam(':description', $description_projet, PDO::PARAM_STR);
    $stmt->bindParam(':date_debut', $date_debut_projet, PDO::PARAM_STR);
    $stmt->bindParam(':date_fin', $date_fin_projet, PDO::PARAM_STR);
    $stmt->bindParam(':lieu', $lieu, PDO::PARAM_STR);
    $stmt->bindParam(':type', $type_projet, PDO::PARAM_STR);
    $stmt->execute();

    header('Location: vous.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Votre Profil</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <?php require('header.php'); ?>
    </header>
    <main>
        <h1>Complétez votre profil</h1>

        <form action="vous.php" method="POST">
            <h2>Informations Personnelles</h2>
            <label for="nom">Nom</label>
            <input type="text" name="nom" id="nom" value="<?php echo htmlspecialchars($user['Nom']); ?>">

            <label for="prenom">Prénom</label>
            <input type="text" name="prenom" id="prenom" value="<?php echo htmlspecialchars($user['Prénom']); ?>">

            <label for="photo_de_profil">Photo de profil</label>
            <input type="text" name="photo_de_profil" id="photo_de_profil" value="<?php echo htmlspecialchars($user['Photo_de_profil']); ?>">

            <label for="image_de_fond">Image de fond</label>
            <input type="text" name="image_de_fond" id="image_de_fond" value="<?php echo htmlspecialchars($user['Image_de_fond']); ?>">

            <label for="description">Description</label>
            <textarea name="description" id="description"><?php echo htmlspecialchars($user['Description']); ?></textarea>
            <label for="competences">Compétences</label>
        <textarea name="competences" id="competences"><?php echo htmlspecialchars($user['Compétences']); ?></textarea>

        <label for="humeur">Humeur</label>
        <input type="text" name="humeur" id="humeur" value="<?php echo htmlspecialchars($user['Humeur']); ?>">

        <button type="submit" name="update_info">Mettre à jour</button>
    </form>

    <h2>Ajouter Formation</h2>
    <form action="vous.php" method="POST">
        <label for="titre_formation">Titre</label>
        <input type="text" name="titre_formation" id="titre_formation">

        <label for="etablissement">Etablissement</label>
        <input type="text" name="etablissement" id="etablissement">

        <label for="date_debut_formation">Date Début</label>
        <input type="date" name="date_debut_formation" id="date_debut_formation">

        <label for="date_fin_formation">Date Fin</label>
        <input type="date" name="date_fin_formation" id="date_fin_formation">

        <label for="description_formation">Description</label>
        <textarea name="description_formation" id="description_formation"></textarea>

        <button type="submit" name="add_formation">Ajouter Formation</button>
    </form>

    <h2>Ajouter Projet</h2>
    <form action="vous.php" method="POST">
        <label for="titre_projet">Titre</label>
        <input type="text" name="titre_projet" id="titre_projet">

        <label for="description_projet">Description</label>
        <textarea name="description_projet" id="description_projet"></textarea>

        <label for="date_debut_projet">Date Début</label>
        <input type="date" name="date_debut_projet" id="date_debut_projet">

        <label for="date_fin_projet">Date Fin</label>
        <input type="date" name="date_fin_projet" id="date_fin_projet">

        <label for="lieu">Lieu</label>
        <input type="text" name="lieu" id="lieu">

        <label for="type_projet">Type</label>
        <select name="type_projet" id="type_projet">
            <option value="Ecole">Ecole</option>
            <option value="Entreprise">Entreprise</option>
            <option value="Erasmus">Erasmus</option>
            <option value="Autre">Autre</option>
        </select>

        <button type="submit" name="add_projet">Ajouter Projet</button>
    </form>
</main>
<footer></footer>
