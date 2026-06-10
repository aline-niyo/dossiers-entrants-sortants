<?php
if (!isset($_GET['dossier'])) {
    echo "Aucun dossier spécifié.";
    exit();
}

$dossier = basename($_GET['dossier']); // Sécurise le nom du dossier
$chemin = "uploads/" . $dossier;

if (!is_dir($chemin)) {
    echo "Dossier introuvable.";
    exit();
}

$fichiers = scandir($chemin);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Contenu du dossier "<?php echo htmlspecialchars($dossier); ?>"</title>
    <style>
        /* Général */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f4f6f9;
            margin: 0;
            padding: 0;
        }

        .container {
            background: #fff;
            padding: 20px;
            margin: 30px auto;
            max-width: 90%;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        h1 {
            color: #333;
            font-size: 24px;
            text-align: center;
            margin-bottom: 20px;
        }

        .file-list {
            list-style-type: none;
            padding: 0;
        }

        .file-list li {
            background-color: #f9f9f9;
            margin: 10px 0;
            padding: 10px;
            border-radius: 6px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }

        .file-list a {
            text-decoration: none;
            color: #007bff;
            font-size: 16px;
            transition: color 0.3s ease;
        }

        .file-list a:hover {
            color: #0056b3;
        }

        /* Bouton retour */
        .action-btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border-radius: 6px;
            font-size: 16px;
            text-decoration: none;
            margin-top: 20px;
            text-align: center;
            transition: background-color 0.3s ease;
        }

        .action-btn.retour {
            background-color: #28a745;
        }

        .action-btn.retour:hover {
            background-color: #1e7e34;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Fichiers dans le dossier : <?php echo htmlspecialchars($dossier); ?></h1>
        <ul class="file-list">
            <?php
            foreach ($fichiers as $fichier) {
                if ($fichier !== '.' && $fichier !== '..') {
                    echo '<li><a href="' . $chemin . '/' . urlencode($fichier) . '" target="_blank">' . htmlspecialchars($fichier) . '</a></li>';
                }
            }
            ?>
        </ul>
        <a href="secretaire_executif.php" class="action-btn retour">Retour</a>
    </div>
</body>
</html>
