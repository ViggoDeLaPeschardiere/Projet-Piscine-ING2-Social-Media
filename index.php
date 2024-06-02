<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion ECE In</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            flex-direction: column;
            align-items: center;
            height: 100vh;
        }
        .container {
            background: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
        }
        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 5px;
            background: #28a745;
            color: #fff;
            font-size: 16px;
        }
        button:hover {
            background: #218838;
        }
        .error {
            color: red;
            margin-bottom: 10px;
        }
        .header {
            background: #fff;
            padding: 10px;
            width: 100%;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
    </style>
</head>
<body>
<header>
        <?php require('header.php'); ?>
    </header>
    <div class="header">
        <div>Bienvenue, 
            <?php 
            session_start(); 
            if (isset($_SESSION['pseudo'])) {
                echo htmlspecialchars($_SESSION['pseudo']); 
            } else {
                echo "Invité";
            }
            ?>
        </div>
        <div>
            <?php if (isset($_SESSION['pseudo'])): ?>
                <a href="logout.php">Se déconnecter</a>
            <?php endif; ?>
        </div>
    </div>
    <div class="container">
        <h2>Connexion</h2>
        <form id="loginForm" action="login.php" method="POST">
            <input type="text" name="pseudo" placeholder="Pseudo" required>
            <input type="password" name="mot_de_passe" placeholder="MotDePasse" required>
            <button type="submit">Se connecter</button>
        </form>
        <h2>Créer un compte</h2>
        <form id="registerForm" action="register.php" method="POST">
            <input type="text" name="pseudo" placeholder="Pseudo" required>
            <input type="password" name="mot_de_passe" placeholder="MotDePasse" required>
            <button type="submit">Créer un compte</button>
        </form>
        <div class="error" id="errorMessage"></div>

        <a href="messagerie.php" style="position: fixed; bottom: 10px; left: 10px;">Accéder à la messagerie</a>

    </div>
</body>
</html>