<?php
session_start();
if (!isset($_SESSION['connecté'])) {
    header('Location: index.php');
    exit();
}

include "../connexion.php";

$affichageArchive = $conn->query("
    SELECT r.*, u.username AS utilisateur, d.nom AS dossier, dest.username AS destinateur
    FROM reponse_archive r
    LEFT JOIN utilisateurs u ON r.id_utilisateur = u.id_utilisateur
    LEFT JOIN utilisateurs dest ON r.id_destinateur = dest.id_utilisateur
    LEFT JOIN dossiers d ON r.id_dossier = d.id_dossier
    ORDER BY r.date_reponse DESC
");
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réponses Archivées</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f2f2f2;
            margin: 0;
            padding: 10px;
        }

        h1 {
            text-align: center;
            color: #333;
        }

        .container {
            max-width: 1000px;
            margin: auto;
            background: #fff;
            padding: 15px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th, td {
            padding: 12px 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #4CAF50;
            color: white;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        @media screen and (max-width: 600px) {
            table, thead, tbody, th, td, tr {
                display: block;
            }

            thead {
                display: none;
            }

            tr {
                margin-bottom: 10px;
                border: 1px solid #ccc;
                border-radius: 10px;
                padding: 10px;
                background: #fff;
            }

            td {
                position: relative;
                padding-left: 50%;
                text-align: right;
            }

            td::before {
                content: attr(data-label);
                position: absolute;
                left: 10px;
                top: 10px;
                font-weight: bold;
                text-align: left;
                white-space: nowrap;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <p><a href="../reponse.php">Retour</a></p>
    <h1>Réponses Archivées</h1>
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Réponse</th>
                <th>Utilisateur</th>
                <th>Destinateur</th>
                <th>Dossier</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
    <?php while ($archive = $affichageArchive->fetch(PDO::FETCH_ASSOC)) { ?>
        <tr>
            <td data-label="Date"><?= htmlspecialchars($archive["date_reponse"]); ?></td>
            <td data-label="Réponse"><?= htmlspecialchars($archive["reponse"]); ?></td>
            <td data-label="Utilisateur"><?= htmlspecialchars($archive["utilisateur"]); ?></td>
            <td data-label="Destinateur"><?= htmlspecialchars($archive["destinateur"]); ?></td>
            <td data-label="Dossier"><?= htmlspecialchars($archive["dossier"]); ?></td>
            <td>
                <a href="desarchiver_reponse.php?id=<?= $archive['id_reponse']; ?>" class="action-btn">Désarchiver</a>
            </td>
        </tr>
    <?php } ?>
</tbody>
    </table>
</div>

</body>
</html>
