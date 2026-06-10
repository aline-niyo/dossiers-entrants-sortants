<?php
session_start();
if (!isset($_SESSION['connecté'])) {
    header('Location: ../index.php');
    exit();
}

if (!isset($_GET['dossier']) || !isset($_GET['fichier'])) {
    echo "Paramètres manquants.";
    exit;
}

// Sécurisation du dossier et du fichier
$dossier = basename($_GET['dossier']);
$fichier = basename($_GET['fichier']);

// Vérification pour éviter d'ouvrir des fichiers PHP
if (pathinfo($fichier, PATHINFO_EXTENSION) === 'php') {
    echo "Les fichiers PHP ne sont pas autorisés.";
    exit;
}

// Création du chemin complet vers le fichier
$chemin = __DIR__ . "/uploads/$dossier/$fichier";

// Vérification de l'existence du fichier
if (!file_exists($chemin)) {
    echo "Fichier introuvable.";
    exit;
}

// Détection du type MIME
$mime = mime_content_type($chemin);

// Vérification pour empêcher les fichiers PHP d'être servis
if ($mime === 'text/x-php') {
    echo "Les fichiers PHP ne sont pas autorisés.";
    exit;
}

// Traitement selon le type MIME
switch ($mime) {
    case 'application/pdf':
        // Si c'est un PDF
        header("Content-Type: $mime");
        header("Content-Disposition: inline; filename=\"$fichier\"");
        header("Content-Length: " . filesize($chemin));
        readfile($chemin);
        break;

    case 'image/jpeg':
    case 'image/png':
    case 'image/gif':
        // Si c'est une image
        header("Content-Type: $mime");
        header("Content-Disposition: inline; filename=\"$fichier\"");
        header("Content-Length: " . filesize($chemin));
        readfile($chemin);
        break;

    case 'text/plain':
        // Si c'est un fichier texte
        header("Content-Type: $mime");
        header("Content-Disposition: inline; filename=\"$fichier\"");
        header("Content-Length: " . filesize($chemin));
        readfile($chemin);
        break;

    case 'application/msword':
    case 'application/vnd.openxmlformats-officedocument.wordprocessingml.document':
        // Si c'est un fichier Word
        header("Content-Type: $mime");
        header("Content-Disposition: inline; filename=\"$fichier\"");
        header("Content-Length: " . filesize($chemin));
        readfile($chemin);
        break;

    case 'application/vnd.ms-excel':
    case 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet':
        // Si c'est un fichier Excel
        header("Content-Type: $mime");
        header("Content-Disposition: inline; filename=\"$fichier\"");
        header("Content-Length: " . filesize($chemin));
        readfile($chemin);
        break;

    case 'application/vnd.ms-powerpoint':
    case 'application/vnd.openxmlformats-officedocument.presentationml.presentation':
        // Si c'est un fichier PowerPoint
        header("Content-Type: $mime");
        header("Content-Disposition: inline; filename=\"$fichier\"");
        header("Content-Length: " . filesize($chemin));
        readfile($chemin);
        break;

    case 'application/zip':
    case 'application/x-rar-compressed':
    case 'application/x-tar':
        // Si c'est une archive ZIP, RAR ou TAR, il sera téléchargé car ne peut pas s'ouvrir inline
        header("Content-Type: $mime");
        header("Content-Disposition: attachment; filename=\"$fichier\"");
        header("Content-Length: " . filesize($chemin));
        readfile($chemin);
        break;

    case 'audio/mpeg':
    case 'audio/ogg':
    case 'audio/wav':
        // Si c'est un fichier audio, il peut être lu directement dans le navigateur
        header("Content-Type: $mime");
        header("Content-Disposition: inline; filename=\"$fichier\"");
        header("Content-Length: " . filesize($chemin));
        readfile($chemin);
        break;

    case 'video/mp4':
    case 'video/webm':
    case 'video/ogg':
        // Si c'est un fichier vidéo, il peut être lu directement dans le navigateur
        header("Content-Type: $mime");
        header("Content-Disposition: inline; filename=\"$fichier\"");
        header("Content-Length: " . filesize($chemin));
        readfile($chemin);
        break;

    default:
        // Si le type MIME n'est pas reconnu
        echo "Le type de fichier '$mime' n'est pas pris en charge.";
        exit;
}
exit;
