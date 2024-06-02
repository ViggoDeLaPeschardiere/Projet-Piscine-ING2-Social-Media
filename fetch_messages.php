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

$groupId = $_GET['group_id'];

$messagesQuery = "
    SELECT m.Contenu, m.Date_Message, u.Pseudo 
    FROM Message m 
    JOIN Utilisateur u ON m.ID_Utilisateur = u.ID_Utilisateur 
    WHERE m.ID_Groupe = $groupId
    ORDER BY m.Date_Message ASC";
$messagesResult = $conn->query($messagesQuery);

$messages = [];
if ($messagesResult->num_rows > 0) {
    while($row = $messagesResult->fetch_assoc()) {
        $messages[] = $row;
    }
}

echo json_encode($messages);

$conn->close();
?>