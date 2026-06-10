<?php
session_start(); 

if (!isset($_SESSION['connecté'])) {
    header('Location: index.php');
    exit();
}

$roles = explode(',', $_SESSION['roles']); 
if (!in_array('secretaire_executif', $roles) && !in_array('secretaire', $roles) && !in_array('secretaire_interim', $roles)) {
    exit("Accès refusé.");
}

include "connexion.php";

// Vérifie que le paramètre "fichier" est présent
if (!isset($_GET['fichier']) || empty($_GET['fichier'])) {
    exit("Fichier non spécifié.");
}

$fichier = $_GET['fichier'];

// Recherche dans la base pour récupérer le nom du dossier correspondant à ce fichier
$stmt = $conn->prepare("SELECT nom FROM dossiers_transferes WHERE fichier = ?");
$stmt->execute([$fichier]);
$dossier = $stmt->fetch();

if (!$dossier) {
    exit("Dossier associé non trouvé pour ce fichier.");
}

$nomDossier = $dossier['nom'];

// Nettoyage du nom pour éviter les attaques par traversée de répertoires
$nomDossier = basename($nomDossier);
$fichier = basename($fichier);

// Construction du chemin vers le fichier
$chemin = "uploads/" . $nomDossier . "/" . $fichier;

if (!file_exists($chemin)) {
    exit("Le fichier n'existe pas.");
}

// Téléchargement ou affichage
$mimeType = mime_content_type($chemin);
header('Content-Type: ' . $mimeType);
header('Content-Disposition: inline; filename="' . $fichier . '"');
header('Content-Length: ' . filesize($chemin));
readfile($chemin);
exit;
?>
