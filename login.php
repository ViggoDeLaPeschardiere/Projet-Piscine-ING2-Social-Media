<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "twitterlike";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $pseudo = $_POST['pseudo'];
    $mot_de_passe = $_POST['mot_de_passe'];

    $sql = "SELECT * FROM utilisateur WHERE Pseudo='$pseudo' AND MotDePasse='$mot_de_passe'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $_SESSION['pseudo'] = $pseudo;
        header("Location: index.php");
    } else {
        echo "<script>
                document.getElementById('errorMessage').innerText = 'Pseudo ou mot de passe invalide.';
                </script>";
    }
}

$conn->close();
?>