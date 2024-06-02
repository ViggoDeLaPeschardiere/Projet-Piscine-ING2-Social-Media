<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

$conn = mysqli_connect('localhost', 'root', '', 'twitterlike');

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION['connection']['state']) || $_SESSION['connection']['state'] == false) {
    header('Location: logine.php');
    exit();
}

// Récupère les informations de l'utilisateur connecté
$ID_Utilisateur = $_SESSION['connection']['ID_Utilisateur'];
$sql = "SELECT * FROM utilisateur WHERE ID_Utilisateur = '$ID_Utilisateur'";
$result = mysqli_query($conn, $sql);

if ($result && mysqli_num_rows($result) > 0) {
    $user = mysqli_fetch_assoc($result);
} else {
    // Gérer le cas où l'utilisateur n'est pas trouvé
    // Peut-être rediriger l'utilisateur vers une page d'erreur
    echo "Erreur: Utilisateur non trouvé.";
    exit();
}

// Gérer l'ajout ou la mise à jour des informations de l'utilisateur
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_info'])) {
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $photo_de_profil = $_POST['photo_de_profil'];
    $image_de_fond = $_POST['image_de_fond'];
    $description = $_POST['description'];
    $competences = $_POST['competences'];
    $humeur = $_POST['humeur'];

    $sql = "UPDATE utilisateur SET 
            Nom = '$nom', 
            Prenom = '$prenom', 
            Photo_de_profil = '$photo_de_profil', 
            Image_de_fond = '$image_de_fond', 
            Description = '$description', 
            Competences = '$competences', 
            Humeur = '$humeur' 
            WHERE ID_Utilisateur = '$ID_Utilisateur'";

    if (mysqli_query($conn, $sql)) {
        header('Location: vous.php');
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
}

// Gérer l'ajout de projet

// Gérer l'ajout de projet
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_projet'])) {
    $id_Projet = $_POST['id_projet'];
    $titre = $_POST['titre_projet'];
    $description = $_POST['description_projet'];
    $date_debut = $_POST['date_debut_projet'];
    $date_fin = $_POST['date_fin_projet'];
    $lieu = $_POST['lieu'];
    $type_projet = $_POST['type_projet'];

    $sql = "INSERT INTO Projet (ID_Projet, ID_Utilisateur, Titre, Description, Date_Debut, Date_Fin, Lieu, Type) 
            VALUES ('$id_Projet', '$ID_Utilisateur', '$titre', '$description', '$date_debut', '$date_fin', '$lieu', '$type_projet')";

    if (mysqli_query($conn, $sql)) {
        header('Location: vous.php');
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
}



// Gérer l'ajout de formation
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_formation'])) {
    $id_formation = $_POST['id_formation'];
    $titre = $_POST['titre_formation'];
    $etablissement = $_POST['etablissement'];
    $date_debut = $_POST['date_debut_formation'];
    $date_fin = $_POST['date_fin_formation'];
    $description = $_POST['description_formation'];

    $sql = "INSERT INTO Formation (ID_Formation, ID_Utilisateur, Titre, Etablissement, Date_Debut, Date_Fin, Description) 
            VALUES ('$id_formation', '$ID_Utilisateur', '$titre', '$etablissement', '$date_debut', '$date_fin', '$description')";

    if (mysqli_query($conn, $sql)) {
        header('Location: vous.php');
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["generate_cv"])) {
    // Récupérez les informations nécessaires depuis $_POST ou $_SESSION
    $nom = htmlspecialchars($user['Nom']);
    $prenom = htmlspecialchars($user['Prenom']);
    $competences = htmlspecialchars($user['Competences']);
    $description = htmlspecialchars($user['Description']);

    // Récupérez les informations sur les formations de la base de données
    $sql = "SELECT * FROM Formation WHERE ID_Utilisateur = '$ID_Utilisateur'";
    $result = mysqli_query($conn, $sql);
    $formations = [];
    if ($result && mysqli_num_rows($result) > 0) {
        while ($formation = mysqli_fetch_assoc($result)) {
            $formations[] = $formation;
        }
    }

    // Récupérez les informations sur les projets de la base de données
    $sql = "SELECT * FROM Projet WHERE ID_Utilisateur = '$ID_Utilisateur'";
    $result = mysqli_query($conn, $sql);
    $projets = [];
    if ($result && mysqli_num_rows($result) > 0) {
        while ($projet = mysqli_fetch_assoc($result)) {
            $projets[] = $projet;
        }
    }

    // Générez le contenu du CV au format souhaité
    $cv_content = "<h1>CV de $prenom $nom</h1>";
    $cv_content .= "<h2>Competences :</h2>";
    $cv_content .= "<p>$competences</p>";
    $cv_content .= "<h2>Description :</h2>";
    $cv_content .= "<p>$description</p>";

    if (!empty($formations)) {
        $cv_content .= "<h2>Formations :</h2>";
        foreach ($formations as $formation) {
            $cv_content .= "<p><strong>Titre :</strong> " . htmlspecialchars($formation['Titre']) . "</p>";
            $cv_content .= "<p><strong>Etablissement :</strong> " . htmlspecialchars($formation['Etablissement']) . "</p>";
            $cv_content .= "<p><strong>Date Debut :</strong> " . htmlspecialchars($formation['Date_Debut']) . "</p>";
            $cv_content .= "<p><strong>Date Fin :</strong> " . htmlspecialchars($formation['Date_Fin']) . "</p>";
            $cv_content .= "<p><strong>Description :</strong> " . htmlspecialchars($formation['Description']) . "</p>";
        }
    }

    if (!empty($projets)) {
        $cv_content .= "<h2>Projets :</h2>";
        foreach ($projets as $projet) {
            $cv_content .= "<p><strong>Titre :</strong> " . htmlspecialchars($projet['Titre']) . "</p>";
            $cv_content .= "<p><strong>Description :</strong> " . htmlspecialchars($projet['Description']) . "</p>";
            $cv_content .= "<p><strong>Date Debut :</strong> " . htmlspecialchars($projet['Date_Debut']) . "</p>";
            $cv_content .= "<p><strong>Date Fin :</strong> " . htmlspecialchars($projet['Date_Fin']) . "</p>";
            $cv_content .= "<p><strong>Lieu :</strong> " . htmlspecialchars($projet['Lieu']) . "</p>";
            $cv_content .= "<p><strong>Type :</strong> " . htmlspecialchars($projet['Type']) . "</p>";
        }
    }

    echo $cv_content;
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

            <label for="prenom">Prenom</label>
            <input type="text" name="prenom" id="prenom" value="<?php echo htmlspecialchars($user['Prenom']); ?>">

            <label for="photo_de_profil">Photo de profil</label>
            <input type="text" name="photo_de_profil" id="photo_de_profil" value="<?php echo htmlspecialchars($user['Photo_de_profil']); ?>">

            <label for="image_de_fond">Image de fond</label>
            <input type="text" name="image_de_fond" id="image_de_fond" value="<?php echo htmlspecialchars($user['Image_de_fond']); ?>">

            <label for="description">Description</label>
            <textarea name="description" id="description"><?php echo htmlspecialchars($user['Description']); ?></textarea>

            <label for="competences">Competences</label>
            <textarea name="competences" id="competences"><?php echo htmlspecialchars($user['Competences']); ?></textarea>

            <label for="humeur">Humeur</label>
            <input type="text" name="humeur" id="humeur" value="<?php echo htmlspecialchars($user['Humeur']); ?>">

            <button type="submit" name="update_info">Mettre à jour</button>
        </form>

        <h2>Ajouter Formation</h2>
        <form action="vous.php" method="POST">
            <label for="id_formation">ID Formation</label>
            <input type="text" name="id_formation" id="id_formation">

            <label for="titre_formation">Titre</label>
            <input type="text" name="titre_formation" id="titre_formation">

            <label for="etablissement">Etablissement</label>
            <input type="text" name="etablissement" id="etablissement">

            <label for="date_debut_formation">Date Debut</label>
            <input type="date" name="date_debut_formation" id="date_debut_formation">

            <label for="date_fin_formation">Date Fin</label>
            <input type="date" name="date_fin_formation" id="date_fin_formation">

            <label for="description_formation">Description</label>
            <textarea name="description_formation" id="description_formation"></textarea>

            <button type="submit" name="add_formation">Ajouter Formation</button>
        </form>

        <h2>Ajouter Projet</h2>
        <form action="vous.php" method="POST">
            <label for="id_projet">ID Projet</label>
            <input type="text" name="id_projet" id="id_projet">

            <label for="titre_projet">Titre</label>
            <input type="text" name="titre_projet" id="titre_projet">

            <label for="description_projet">Description</label>
            <textarea name="description_projet" id="description_projet"></textarea>

            <label for="date_debut_projet">Date Debut</label>
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

        <h2>Vos Informations</h2>
        <p><strong>Nom :</strong> <?php echo htmlspecialchars($user['Nom']); ?></p>
        <p><strong>Prenom :</strong> <?php echo htmlspecialchars($user['Prenom']); ?></p>
        <p><strong>Photo de profil :</strong> <img src="<?php echo htmlspecialchars($user['Photo_de_profil']); ?>" alt="Photo de profil"></p>
        <p><strong>Image de fond :</strong> <img src="<?php echo htmlspecialchars($user['Image_de_fond']); ?>" alt="Image de fond"></p>
        <p><strong>Description :</strong> <?php echo htmlspecialchars($user['Description']); ?></p>
        <p><strong>Competences :</strong> <?php echo htmlspecialchars($user['Competences']); ?></p>
        <p><strong>Humeur :</strong> <?php echo htmlspecialchars($user['Humeur']); ?></p>
        <h2>Vos Formations</h2>
       <?php
       $sql = "SELECT * FROM Formation WHERE ID_Utilisateur = '$ID_Utilisateur'";
       $result = mysqli_query($conn, $sql);

       if (mysqli_num_rows($result) > 0) {
           while ($formation = mysqli_fetch_assoc($result)) {
               echo '<div>';
               echo '<p><strong>Titre :</strong> ' . htmlspecialchars($formation['Titre']) . '</p>';
               echo '<p><strong>Etablissement :</strong> ' . htmlspecialchars($formation['Etablissement']) . '</p>';
               echo '<p><strong>Date Debut :</strong> ' . htmlspecialchars($formation['Date_Debut']) . '</p>';
               echo '<p><strong>Date Fin :</strong> ' . htmlspecialchars($formation['Date_Fin']) . '</p>';
               echo '<p><strong>Description :</strong> ' . htmlspecialchars($formation['Description']) . '</p>';
               echo '</div>';
           }
       } else {
           echo "Aucune formation trouvée.";
       }
       ?>

       <h2>Vos Projets</h2>
       <?php
       $sql = "SELECT * FROM Projet WHERE ID_Utilisateur = '$ID_Utilisateur'";
       $result = mysqli_query($conn, $sql);

       if (mysqli_num_rows($result) > 0) {
           while ($projet = mysqli_fetch_assoc($result)) {
               echo '<div>';
               echo '<p><strong>Titre :</strong> ' . htmlspecialchars($projet['Titre']) . '</p>';
               echo '<p><strong>Description :</strong> ' . htmlspecialchars($projet['Description']) . '</p>';
               echo '<p><strong>Date Debut :</strong> ' . htmlspecialchars($projet['Date_Debut']) . '</p>';
               echo '<p><strong>Date Fin :</strong> ' . htmlspecialchars($projet['Date_Fin']) . '</p>';
               echo '<p><strong>Lieu :</strong> ' . htmlspecialchars($projet['Lieu']) . '</p>';
               echo '<p><strong>Type :</strong> ' . htmlspecialchars($projet['Type']) . '</p>';
               echo '</div>';
           }
       } else {
           echo "Aucun projet trouvé.";
       }
       ?>
       <form action="vous.php" method="POST">
           <button type="submit" name="generate_cv">Générer CV</button>
       </form>
   </main>
   <footer></footer>
</body>
</html>

