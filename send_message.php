<?php
session_start();
if (!isset($_SESSION['pseudo'])) {
    header("Location: index.php");
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "twitterlike";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$data = json_decode(file_get_contents('php://input'), true);
$groupId = $data['group_id'];
$messageContent = $data['message'];

$pseudo = $_SESSION['pseudo'];
$userIdQuery = "SELECT ID_Utilisateur FROM Utilisateur WHERE Pseudo='$pseudo'";
$userIdResult = $conn->query($userIdQuery);
$userIdRow = $userIdResult->fetch_assoc();
$userId = $userIdRow['ID_Utilisateur'];

$sendMessageQuery = "INSERT INTO Message (ID_Groupe, ID_Utilisateur, Contenu) VALUES ($groupId, $userId, '$messageContent')";
$conn->query($sendMessageQuery);

$conn->close();
?>