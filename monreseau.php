<?php
// Configuration de la base de données
$host = 'localhost';
$dbname = 'twitterlike';
$username = 'root';
$password = 'root';

// Connexion à la base de données
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

// Vérifier si l'utilisateur est connecté
session_start();
if (!isset($_SESSION['ID_Utilisateur'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['ID_Utilisateur'];

// Récupérer les informations de l'utilisateur
$stmt = $pdo->prepare("SELECT Pseudo, Photo_de_profil, Description FROM Utilisateur WHERE ID_Utilisateur = :id");
$stmt->bindParam(':id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$utilisateur = $stmt->fetch(PDO::FETCH_ASSOC);

// Vérifier si l'utilisateur existe
if (!$utilisateur) {
    die("Utilisateur non trouvé.");
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Réseau</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>Profil de <?php echo htmlspecialchars($utilisateur['Pseudo']); ?></h1>
        </header>
        <div class="profile">
            <img src="<?php echo htmlspecialchars($utilisateur['Photo_de_profil']); ?>" alt="Photo de profil" class="profile-photo">
            <p><?php echo nl2br(htmlspecialchars($utilisateur['Description'])); ?></p>
        </div>
        <footer>
            &copy; 2024 Votre Entreprise. Tous droits réservés.
        </footer>
    </div>
</body>
</html>
