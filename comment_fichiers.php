<?php
include "connexion.php";

function getFileIcon($filename) {
    $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    return match ($ext) {
        'pdf' => 'fa-file-pdf text-red-600',
        'doc', 'docx' => 'fa-file-word text-blue-600',
        'xls', 'xlsx' => 'fa-file-excel text-green-600',
        'ppt', 'pptx' => 'fa-file-powerpoint text-orange-500',
        'jpg', 'jpeg', 'png', 'gif', 'bmp' => 'fa-file-image text-purple-500',
        'zip', 'rar' => 'fa-file-zipper text-yellow-600',
        'txt' => 'fa-file-lines text-gray-600',
        default => 'fa-file text-black'
    };
}

if (isset($_GET['dossier_id'])) {
    $dossierId = intval($_GET['dossier_id']);
    
    $dossierQuery = $conn->prepare("SELECT nom FROM dossiers WHERE id_dossier = :id_dossier");
    $dossierQuery->bindParam(':id_dossier', $dossierId, PDO::PARAM_INT);
    $dossierQuery->execute();
    $dossier = $dossierQuery->fetch(PDO::FETCH_ASSOC);

    if ($dossier) {
        $dossierNom = $dossier['nom'];
        $dossierPath = "uploads/" . $dossierNom;
        $files = (is_dir($dossierPath)) ? array_diff(scandir($dossierPath), ['.', '..']) : [];
    } else {
        echo "<p class='message error'>Dossier introuvable.</p>";
        exit();
    }
} else {
    echo "<p class='message error'>Aucun dossier spécifié.</p>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fichiers du Dossier</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f1f1f1;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 480px;
            margin: 0 auto;
            padding: 20px;
            background: white;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            border-radius: 20px;
            margin-top: 30px;
        }

        h1 {
            font-size: 22px;
            color: #333;
            text-align: center;
        }

        ul {
            list-style-type: none;
            padding: 0;
        }

        li {
            background: #e9ecef;
            margin: 10px 0;
            padding: 12px 16px;
            border-radius: 12px;
            font-size: 16px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .file-icon {
            font-size: 20px;
            width: 25px;
            text-align: center;
        }

        a.file-link {
            text-decoration: none;
            color: #007bff;
            font-weight: 500;
            flex: 1;
        }

        a.file-link:hover {
            text-decoration: underline;
        }

        .back-button {
            display: inline-block;
            margin-top: 20px;
            text-align: center;
            background: #007bff;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 30px;
            width: 100%;
            box-sizing: border-box;
        }

        .back-button:hover {
            background: #0056b3;
        }

        .message.error {
            color: red;
            text-align: center;
            padding: 20px;
        }

        @media (max-width: 480px) {
            h1 {
                font-size: 20px;
            }

            li {
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1><i class="fa-solid fa-folder"></i> <?= htmlspecialchars($dossierNom); ?></h1>

        <?php if (empty($files)): ?>
            <p style="text-align:center;">Aucun fichier trouvé dans ce dossier.</p>
        <?php else: ?>
            <ul>
                <?php foreach ($files as $file): ?>
                    <li>
                        <i class="fa-solid <?= getFileIcon($file); ?> file-icon"></i>
                        <a class="file-link" href="<?= $dossierPath . '/' . rawurlencode($file); ?>" target="_blank">
                            <?= htmlspecialchars($file); ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

        <a class="back-button" href="comment.php"><i class="fa-solid fa-arrow-left"></i> Retour</a>
    </div>
</body>
</html>
