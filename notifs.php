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
        <div class="notifs">
            <h2>Notifications</h2>
            <?php
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }
            require('database.php'); // Connexion à la base de données principale

            // Récupération des notifications
            $query = "
                SELECT n.ID_Notification, n.ID_Utilisateur, n.Type, n.Contenu, n.Date_Notification, u.Pseudo
                FROM notification n
                JOIN utilisateur u ON n.ID_Utilisateur = u.ID_Utilisateur
                ORDER BY n.Date_Notification DESC
            ";
            $stmt = $database->prepare($query);
            $stmt->execute();
            $notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Récupération des amis et amis des amis
            $currentUserId = $_SESSION['connection']['ID_Utilisateur'];

            // Amis directs
            $query = "
                SELECT c.ID_Utilisateur_2
                FROM connexion c 
                WHERE c.ID_Utilisateur_1 = :currentUserId
            ";
            $stmt = $database->prepare($query);
            $stmt->bindParam(':currentUserId', $currentUserId, PDO::PARAM_INT);
            $stmt->execute();
            $directFriends = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);

            // Amis des amis
            $query = "
                SELECT DISTINCT c2.ID_Utilisateur_2
                FROM connexion c1
                JOIN connexion c2 ON c1.ID_Utilisateur_2 = c2.ID_Utilisateur_1
                WHERE c1.ID_Utilisateur_1 = :currentUserId AND c2.ID_Utilisateur_2 != :currentUserId
            ";
            $stmt = $database->prepare($query);
            $stmt->bindParam(':currentUserId', $currentUserId, PDO::PARAM_INT);
            $stmt->execute();
            $friendsOfFriends = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);

            // Affichage des notifications
            foreach ($notifications as $notif) {
                $senderId = $notif['ID_Utilisateur'];
                $senderPseudo = htmlspecialchars($notif['Pseudo'], ENT_QUOTES, 'UTF-8');
                $notifType = htmlspecialchars($notif['Type'], ENT_QUOTES, 'UTF-8');
                $notifContent = htmlspecialchars($notif['Contenu'], ENT_QUOTES, 'UTF-8');
                $notifDate = htmlspecialchars($notif['Date_Notification'], ENT_QUOTES, 'UTF-8');

                echo '<div class="notif">';
                if (in_array($senderId, $directFriends)) {
                    echo '<p>Votre contact ' . $senderPseudo . ' vous a envoyé une notif de type <strong>' . $notifType . '</strong>:</p>';
                } elseif (in_array($senderId, $friendsOfFriends)) {
                    echo '<p>Un contact de votre contact, ' . $senderPseudo . ', vous a envoyé une notif de type <strong>' . $notifType . '</strong>:</p>';
                } else {
                    echo '<p>Vous avez reçu une notification de type <strong>' . $notifType . '</strong>:</p>';
                }
                echo '<p>' . $notifContent . '</p>';
                echo '<p>À <strong>' . $notifDate . '</strong></p>';
                echo '</div>';
            }
            ?>
        </div>
    </div>
</main>
<footer></footer>
</body>
</html>
