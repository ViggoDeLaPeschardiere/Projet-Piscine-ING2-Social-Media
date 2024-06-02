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

    $sql_check = "SELECT * FROM utilisateur WHERE Pseudo='$pseudo'";
    $result_check = $conn->query($sql_check);

    if ($result_check->num_rows > 0) {
        echo "<script>
                document.getElementById('errorMessage').innerText = 'Pseudo déjà utilisé.';
                </script>";
    } else {
        $sql = "INSERT INTO utilisateur (Pseudo, MotDePasse) VALUES ('$pseudo', '$mot_de_passe')";
        if ($conn->query($sql) === TRUE) {
            $_SESSION['pseudo'] = $pseudo;
            header("Location: index.php");
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}

$conn->close();
?>
