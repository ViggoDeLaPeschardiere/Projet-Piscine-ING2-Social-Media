<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'database.php'; // Connexion à la base de données

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['ID_Utilisateur'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['ID_Utilisateur'];

// Récupérer les informations de l'utilisateur
$query = $database->prepare("SELECT * FROM Utilisateur WHERE ID_Utilisateur = ?");
$query->execute([$user_id]);
$user = $query->fetch(PDO::FETCH_ASSOC);

$query = $database->prepare("SELECT * FROM Formation WHERE ID_Utilisateur = ?");
$query->execute([$user_id]);
$formations = $query->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['update_user'])) {
        // Mettre à jour les informations de l'utilisateur
        $email = $_POST['email'];
        $pseudo = $_POST['pseudo'];
        $nom = $_POST['nom'];
        $prenom = $_POST['prenom'];
        $description = $_POST['description'];
        $competences = $_POST['competences'];
        $humeur = $_POST['humeur'];
        $filePath = $user['Photo_de_profil']; // Conserver l'ancien chemin si aucune nouvelle photo n'est uploadée

        if (isset($_FILES['photo_profil']) && $_FILES['photo_profil']['error'] === UPLOAD_ERR_OK) {
            $allowed = ['jpg', 'jpeg', 'png', 'gif'];
            $file = $_FILES['photo_profil'];
            $fileExt = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            
            if (in_array($fileExt, $allowed) && $file['size'] <= 2097152) { // 2MB limit
                $uploadDir = 'uploads/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }
                $filePath = $uploadDir . uniqid('', true) . '.' . $fileExt;
                if (move_uploaded_file($file['tmp_name'], $filePath)) {
                    echo "Fichier téléchargé avec succès.";
                } else {
                    echo "Erreur : le fichier n'a pas pu être téléchargé.";
                }
            } else {
                echo 'Erreur : fichier non autorisé ou trop volumineux.';
            }
        }

        $query = $database->prepare("UPDATE Utilisateur SET Email = ?, Pseudo = ?, Nom = ?, Prénom = ?, Description = ?, Compétences = ?, Humeur = ?, Photo_de_profil = ? WHERE ID_Utilisateur = ?");
        if ($query->execute([$email, $pseudo, $nom, $prenom, $description, $competences, $humeur, $filePath, $user_id])) {
            echo "Profil mis à jour avec succès.";
        } else {
            echo 'Erreur lors de la mise à jour du profil.';
        }
    } elseif (isset($_POST['add_formation'])) {
        // Ajouter une nouvelle formation
        $titre = $_POST['titre'];
        $etablissement = $_POST['etablissement'];
        $date_debut = $_POST['date_debut'];
        $date_fin = $_POST['date_fin'];
        $description_formation = $_POST['description_formation'];

        $query = $database->prepare("INSERT INTO Formation (ID_Utilisateur, Titre, Etablissement, Date_Début, Date_Fin, Description) VALUES (?, ?, ?, ?, ?, ?)");
        $query->execute([$user_id, $titre, $etablissement, $date_debut, $date_fin, $description_formation]);
        
        header("Location: vous.php");
        exit();
    } elseif (isset($_POST['update_formation'])) {
        // Mettre à jour une formation existante
        $id_formation = $_POST['id_formation'];
        $titre = $_POST['titre'];
        $etablissement = $_POST['etablissement'];
        $date_debut = $_POST['date_debut'];
        $date_fin = $_POST['date_fin'];
        $description_formation = $_POST['description_formation'];

        $query = $database->prepare("UPDATE Formation SET Titre = ?, Etablissement = ?, Date_Début = ?, Date_Fin = ?, Description = ? WHERE ID_Formation = ? AND ID_Utilisateur = ?");
        $query->execute([$titre, $etablissement, $date_debut, $date_fin, $description_formation, $id_formation, $user_id]);
        
        header("Location: vous.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Votre Profil</title>
</head>
<body>
    <h1>Votre Profil</h1>
    <form method="POST" action="vous.php" enctype="multipart/form-data">
        <input type="hidden" name="update_user" value="1">
        <label for="email">Email :</label>
        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['Email']); ?>" required><br>

        <label for="pseudo">Pseudo :</label>
        <input type="text" id="pseudo" name="pseudo" value="<?php echo htmlspecialchars($user['Pseudo']); ?>" required><br>

        <label for="nom">Nom :</label>
        <input type="text" id="nom" name="nom" value="<?php echo htmlspecialchars($user['Nom']); ?>" required><br>

        <label for="prenom">Prénom :</label>
        <input type="text" id="prenom" name="prenom" value="<?php echo htmlspecialchars($user['Prénom']); ?>" required><br>

        <label for="description">Description :</label>
        <textarea id="description" name="description"><?php echo htmlspecialchars($user['Description']); ?></textarea><br>

        <label for="competences">Compétences :</label>
        <textarea id="competences" name="competences"><?php echo htmlspecialchars($user['Compétences']); ?></textarea><br>

        <label for="humeur">Humeur :</label>
        <input type="text" id="humeur" name="humeur" value="<?php echo htmlspecialchars($user['Humeur']); ?>"><br>

        <label for="photo_profil">Photo de profil :</label>
        <input type="file" id="photo_profil" name="photo_profil"><br>

        <input type="submit" value="Mettre à jour">
    </form>

    <h2>Ajouter une formation</h2>
    <form method="POST" action="vous.php">
        <input type="hidden" name="add_formation" value="1">
        <label for="titre">Titre :</label>
        <input type="text" id="titre" name="titre" required><br>

        <label for="etablissement">Etablissement :</label>
        <input type="text" id="etablissement" name="etablissement" required><br>

        <label for="date_debut">Date de début :</label>
        <input type="date" id="date_debut" name="date_debut" required><br>

        <label for="date_fin">Date de fin :</label>
        <input type="date" id="date_fin" name="date_fin" required><br>

        <label for="description_formation">Description :</label>
        <textarea id="description_formation" name="description_formation"></textarea><br>

        <input type="submit" value="Ajouter">
    </form>

    <h2>Vos formations</h2>
    <ul>
        <?php foreach ($formations as $formation): ?>
            <li>
                <strong><?php echo htmlspecialchars($formation['Titre']); ?></strong><br>
                <?php echo htmlspecialchars($formation['Etablissement']); ?><br>
                Du <?php echo htmlspecialchars($formation['Date_Début']); ?> au <?php echo htmlspecialchars($formation['Date_Fin']); ?><br>
                <?php echo htmlspecialchars($formation['Description']); ?><br>
                <form method="POST" action="vous.php">
                    <input type="hidden" name="update_formation" value="1">
                    <input type="hidden" name="id_formation" value="<?php echo htmlspecialchars($formation['ID_Formation']); ?>">
                    <label for="titre">Titre :</label>
                    <input type="text" id="titre" name="titre" value="<?php echo htmlspecialchars($formation['Titre']); ?>" required><br>
                    
                    <label for="etablissement">Etablissement :</label>
                    <input type="text" id="etablissement" name="etablissement" value="<?php echo htmlspecialchars($formation['Etablissement']); ?>" required><br>
                    
                    <label for="date_debut">Date de début :</label>
                    <input type="date" id="date_debut" name="date_debut" value="<?php echo htmlspecialchars($formation['Date_Début']); ?>" required><br>
                    
                    <label for="date_fin">Date de fin :</label>
                    <input type="date" id="date_fin" name="date_fin" value="<?php echo htmlspecialchars($formation['Date_Fin']); ?>" required><br>
                    
                    <label for="description_formation">Description :</label>
                    <textarea id="description_formation" name="description_formation"><?php echo htmlspecialchars($formation['Description']); ?></textarea><br>
                    
                    <input type="submit" value="Mettre à jour">
                </form>
            </li>
        <?php endforeach; ?>
    </ul>
</body>
</html>
