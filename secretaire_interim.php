<?php
session_start(); 

if (!isset($_SESSION['connecté'])) {
    header('Location: index.php');
    exit();
}
$roles = explode(',', $_SESSION['roles']); 
if (!in_array('secretaire_executif,secretaire,secretaire_interim', $roles)) {
    
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            display: flex;
            flex-direction: column;
            min-height: 100vh; /* Assure que le body prend toute la hauteur */
        }
        .header {
            background-color: white;
            color: white;
            padding: 20px;
            text-align: center;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .search-container {
            margin: 20px auto;
            text-align: center;
        }
        .search-container input[type="text"] {
            padding: 10px;
            width: 300px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .search-container button {
            padding: 10px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-left: 5px;
        }
        .search-container button:hover {
            background-color: #218838;
        }
        .nav {
            display: flex;
            justify-content: flex-end;
            background-color: #343a40;
            padding: 10px;
        }
        .nav a {
            color: white;
            text-decoration: none;
            margin-left: 20px;
            padding: 10px;
            transition: background-color 0.3s;
        }
        .nav a:hover {
            background-color: #495057;
            border-radius: 5px;
        }
        .Capture {
            text-align: center;
            margin: 20px 0;
        }
        .Capture img {
            max-width: 100%;
            height: auto;
            width: 300px;
        }
        footer {
            background-color: rgb(185, 88, 31);
            color: white;
            text-align: center;
            padding: 10px;
            width: 100%;
            box-sizing: border-box; 
            margin-top: auto; 
        }
        .social-media a {
            color: white;
            margin: 0 10px;
            text-decoration: none;
            height: 100%;
        }
        .social-media a:hover {
            text-decoration: underline;
            height: 100%;
        }
        h2 { 
            color: black;
        }
        h1 {
            color: blue;
        }
    </style>
</head>
<body>
    <header class="header">
        <!-- <h1>Plateforme de Suivi des Dossiers Entrants et Sortants au SETIC</h1> -->
    </header>
    
    <div class="search-container">
        <form action="search.php" method="GET">
            <input type="text" name="query" placeholder="Rechercher..." required>
            <button type="submit">Rechercher</button>
        </form>
    </div>

    <nav class="nav">
        <a href="#">Accueil</a>
        <a href="affichage_dossier_fichier.php">Dossier</a>
        <a href="trans_dossier.php">documents</a>
        <a href="comment.php">Commentaires</a>
        <a href="traitement.php">traitement</a>
        <a href="reponse.php">Reponse</a>
        <a href="register.php">Validation</a>
        <a href="deconnect.php">Deconnexion</a>
    </nav>

    <div class="Capture">
        <img src="images/Capture.PNG" alt="Capture d'écran">
    </div>

    <h1 style="text-align: center;">Le Secrétaire Exécutif des Technologies de l’Information et de la Communication (SETIC)</h1>
    
    <footer>
        <div class="social-media">
            <h2>Suivez-nous sur les différents réseaux sociaux :</h2>
            <a href="https://www.facebook.com/profile.php?id=100067322347665">Facebook</a>
            <a href="https://x.com/seticburundi?t=99Cyw7Wc-d1OEHl95grdOw&s=09">Twitter</a>
            <a href="https://youtube.com/@seticburundi_officiel?si=KK-c21V8AxJqTQ6w">YouTube</a>
        </div>
    </footer>
</body>
</html>