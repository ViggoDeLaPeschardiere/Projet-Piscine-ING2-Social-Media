<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Document</title>
</head>
<body>
<img src="Blaz.jpg" alt="Une belle photo">
<main>
<header>
            <?php require('header.php'); ?>
</header>
    <div class="main-content">
        <div class="emplois">
            <h2>Emplois</h2>
            <?php
            require('databaseemplois.php');
            foreach ($_SESSION['emplois'] as $emploi) {
                echo '<div class="emploi">';
                echo '<h3>' . htmlspecialchars($emploi['Titre'], ENT_QUOTES, 'UTF-8') . '</h3>';
                echo '<p>' . htmlspecialchars($emploi['Description'], ENT_QUOTES, 'UTF-8') . '</p>';
                echo '<p>' . htmlspecialchars($emploi['Entreprise'], ENT_QUOTES, 'UTF-8') . '</p>';
                echo '<p>' . htmlspecialchars($emploi['Lieu'], ENT_QUOTES, 'UTF-8') . '</p>';
                echo '</div>';
            }
            ?>
        </div>
    </div>
</main>
<footer></footer>
</body>
</html>
