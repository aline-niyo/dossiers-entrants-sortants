<?php
if (!isset($_GET['nom'])) {
    echo "Nom du dossier non spécifié.";
    exit();
}

$nomBrut = $_GET['nom']; // Nom tel qu'entré dans l'URL
$nomNormalise = str_replace(' ', '_', $nomBrut); // Convertit les espaces en underscores
$nomDossier = basename($nomNormalise); // Sécurise le nom du dossier

$chemin = "uploads/" . $nomDossier;

if (!is_dir($chemin)) {
    echo "Aucun dossier trouvé pour : " . htmlspecialchars($nomBrut);
    exit();
}

$fichiers = scandir($chemin);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Fichiers - <?php echo htmlspecialchars($nomBrut); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Google Fonts + Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
            background: #f4f6f9;
            color: #333;
        }

        .container {
            max-width: 600px;
            margin: 1.5rem auto;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            padding: 1.5rem;
        }

        h2 {
            text-align: center;
            color: #007bff;
            margin-bottom: 1rem;
        }

        ul {
            list-style-type: none;
            padding: 0;
        }

        li {
            margin-bottom: 0.75rem;
            background-color: #eef3f9;
            border-radius: 8px;
            padding: 0.75rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        li a {
            text-decoration: none;
            color: #333;
            font-weight: bold;
            flex-grow: 1;
        }

        li i {
            color: #007bff;
        }

        .back-btn {
            display: block;
            text-align: center;
            margin-top: 1.5rem;
            padding: 0.75rem;
            background: #007bff;
            color: #fff;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
            transition: background 0.3s ease;
        }

        .back-btn:hover {
            background: #0056b3;
        }

        @media (max-width: 600px) {
            .container {
                margin: 1rem;
                padding: 1rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h2><i class="fa fa-folder-open"></i> <?php echo htmlspecialchars($nomBrut); ?></h2>
        <ul>
            <?php foreach ($fichiers as $fichier): ?>
                <?php if (!in_array($fichier, ['.', '..'])): ?>
                    <li>
                        <i class="fa fa-file"></i>
                        <a href="<?php echo $chemin . '/' . urlencode($fichier); ?>" target="_blank">
                            <?php echo htmlspecialchars($fichier); ?>
                        </a>
                    </li>
                <?php endif; ?>
            <?php endforeach; ?>
        </ul>
        <a class="back-btn" href="secretaire_executif.php"> Retour</a>
    </div>
</body>
</html>
