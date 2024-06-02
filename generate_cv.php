<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require('database.php');
require('tcpdf.php'); // Assurez-vous que le chemin est correct

// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION['connection']['state']) || $_SESSION['connection']['state'] == false) {
    header('Location: login.php');
    exit();
}

// Récupère les informations de l'utilisateur connecté
$ID_Utilisateur = $_SESSION['connection']['id'];
$sql = "SELECT * FROM Utilisateur WHERE ID_Utilisateur = :ID_Utilisateur";
$stmt = $database->prepare($sql);
$stmt->bindParam(':ID_Utilisateur', $ID_Utilisateur, PDO::PARAM_INT);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Récupère les formations de l'utilisateur
$sql_formations = "SELECT * FROM Formation WHERE ID_Utilisateur = :ID_Utilisateur";
$stmt_formations = $database->prepare($sql_formations);
$stmt_formations->bindParam(':ID_Utilisateur', $ID_Utilisateur, PDO::PARAM_INT);
stmt_formations->execute();
$formations = $stmt_formations->fetchAll(PDO::FETCH_ASSOC);

// Récupère les projets de l'utilisateur
$sql_projets = "SELECT * FROM Projet WHERE ID_Utilisateur = :ID_Utilisateur";
$stmt_projets = $database->prepare($sql_projets);
$stmt_projets->bindParam(':ID_Utilisateur', $ID_Utilisateur, PDO::PARAM_INT);
stmt_projets->execute();
$projets = $stmt_projets->fetchAll(PDO::FETCH_ASSOC);

// Crée un nouveau document PDF
$pdf = new TCPDF();
$pdf->AddPage();

// Définit le titre
$pdf->SetFont('helvetica', 'B', 20);
$pdf->Cell(0, 10, 'CV de ' . htmlspecialchars($user['Nom']) . ' ' . htmlspecialchars($user['Prénom']), 0, 1, 'C');

// Ajoute la description de l'utilisateur
$pdf->SetFont('helvetica', '', 12);
$pdf->MultiCell(0, 10, 'Description: ' . htmlspecialchars($user['Description']), 0, 1);

// Ajoute les formations
$pdf->SetFont('helvetica', 'B', 16);
$pdf->Cell(0, 10, 'Formations', 0, 1);
$pdf->SetFont('helvetica', '', 12);
foreach ($formations as $formation) {
    $pdf->MultiCell(0, 10, htmlspecialchars($formation['Titre']) . ' à ' . htmlspecialchars($formation['Etablissement']) . ' (' . htmlspecialchars($formation['Date_Début']) . ' - ' . htmlspecialchars($formation['Date_Fin']) . '): ' . htmlspecialchars($formation['Description']), 0, 1);
}

// Ajoute les projets
$pdf->SetFont('helvetica', 'B', 16);
$pdf->Cell(0, 10, 'Projets', 0, 1);
$pdf->SetFont('helvetica', '', 12);
foreach ($projets as $projet) {
    $pdf->MultiCell(0, 10, htmlspecialchars($projet['Titre']) . ' à ' . htmlspecialchars($projet['Lieu']) . ' (' . htmlspecialchars($projet['Date_Début']) . ' - ' . htmlspecialchars($projet['Date_Fin']) . '): ' . htmlspecialchars($projet['Description']), 0, 1);
}
// Chemin où enregistrer le fichier PDF
$filePath = 'pdf/cv_' . $user['Nom'] . '_' . $user['Prénom'] . '.pdf';

// Enregistre le fichier PDF sur le serveur
$pdf->Output($filePath, 'F');

echo 'Le CV a été généré et enregistré à cet emplacement : ' . $filePath;


?>
