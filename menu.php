<?php 
session_start(); // Démarrer la session

// Redirection si l'utilisateur n'est pas connecté
if (!isset($_SESSION['connecté'])) {
    header('Location: index.php');
    exit();
}

// Si nécessaire, vous pouvez également vérifier les rôles ici
$roles = explode(',', $_SESSION['roles']); // Convertir la chaîne de rôles en tableau
if (!in_array('secretaire_executif,secretaire,secretaire_interim', $roles)) {
    // Redirection ou gestion des permissions
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .nav {
            display: flex;
            flex-wrap: wrap;
            background-color: #333;
            padding: 10px;
        }
        .nav a {
            color: white;
            padding: 14px 20px;
            text-decoration: none;
            text-align: center;
            transition: background-color 0.3s;
        }
        .nav a:hover {
            background-color: #575757;
        }
        @media (max-width: 600px) {
            .nav {
                flex-direction: column;
            }
            .nav a {
                text-align: left;
                padding: 10px;
            }
        }
    </style>
</head>
<body>
    <nav class="nav">
        <a href="home.php">Accueil</a>
        <a href="affichage_destinateur.php">Expediteur</a>
        <a href="affichage_dossier.php">Documents</a>
        <a href="affichage_traitement.php">Traitement</a>
        <a href="affichage_comment.php">Commentaires</a>
        <a href="affichage_reponse.php">Réponses</a>
        <a href="affichage_validation.php">Validation</a>
        <a href="affichage_contact.php">Message</a>
        <a href="affichage_utilisateur.php">Utilisateurs</a>
        <a href="affichage_destinateur.php">Expediteurs</a>
        <a href=""></a>
        <a href=""></a>
        <a href=""></a>   
        <a href="deconnect.php">Deconnexion</a>
    </nav>
</body>
</html>