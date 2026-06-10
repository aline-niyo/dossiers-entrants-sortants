<?php
session_start();

if (!isset($_SESSION['connecté'])) {
    header('Location: index.php');
    exit();
}

$roles = explode(',', $_SESSION['roles']);
if (!array_intersect(['secretaire_executif', 'secretaire', 'secretaire_interim'], $roles)) {
    // Gérer les permissions
}

include "connexion.php";

$searchTerm = '';
if (isset($_GET['search'])) {
    $searchTerm = htmlspecialchars($_GET['search']);
    $affichageUtilisateur = $conn->prepare("
        SELECT d.id_dossier, d.nom, d.numero_reference, d.date_envoi, d.date_reception, de.nom_destinateur, d.objet 
        FROM dossiers d
        LEFT JOIN destinateurs de ON d.id_destinateur = de.id_destinateur
        WHERE d.nom LIKE :searchTerm OR d.numero_reference LIKE :searchTerm OR de.nom_destinateur LIKE :searchTerm
    ");
    $affichageUtilisateur->execute([':searchTerm' => '%' . $searchTerm . '%']);
} else {
    $affichageUtilisateur = $conn->query("
        SELECT d.id_dossier, d.nom, d.numero_reference, d.date_envoi, d.date_reception, de.nom_destinateur, d.objet, u.username
        FROM dossiers d
        LEFT JOIN destinateurs de ON d.id_destinateur = de.id_destinateur
        LEFT JOIN utilisateurs u ON d.id_utilisateur = u.id_utilisateur
        ORDER BY d.date_reception DESC, d.id_dossier DESC
    ");
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Dossiers</title>
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
            padding: 1rem;
            max-width: 1000px;
            margin: auto;
            background: #fff;
            box-shadow: 0 0 10px rgba(0,0,0,0.05);
            border-radius: 10px;
        }

        h1 {
            text-align: center;
            color: #007bff;
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }

        .search-container {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            justify-content: center;
            margin-bottom: 1rem;
        }

        .search-container input[type="text"] {
            padding: 0.5rem;
            flex: 1;
            min-width: 200px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }

        .search-container button {
            padding: 0.5rem 1rem;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }

        .search-container button:hover {
            background-color: #0056b3;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.9rem;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 0.6rem;
            text-align: left;
        }

        th {
            background-color: #007bff;
            color: #fff;
        }

        td i {
            margin-right: 6px;
        }

        a {
            color: #007bff;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        .actions a {
            display: inline-block;
            margin: 0.2rem 0;
            font-size: 0.9rem;
        }

        @media (max-width: 768px) {
            table thead {
                display: none;
            }

            table, table tbody, table tr, table td {
                display: block;
                width: 100%;
            }

            table tr {
                margin-bottom: 1rem;
                border: 1px solid #ccc;
                border-radius: 8px;
                padding: 0.5rem;
                background: #fafafa;
            }

            table td {
                text-align: right;
                padding-left: 50%;
                position: relative;
            }

            table td::before {
                content: attr(data-label);
                position: absolute;
                left: 0;
                width: 45%;
                padding-left: 1rem;
                font-weight: bold;
                text-align: left;
            }

            .search-container {
                flex-direction: column;
                align-items: stretch;
            }
        }
    </style>
</head>
<body>
<div class="container">
    <h1><i class="fa fa-folder"></i> Liste des Dossiers Enregistrés</h1>

    <form action="" method="GET" class="search-container">
        <input type="text" name="search" placeholder="Rechercher un dossier..." value="<?php echo htmlspecialchars($searchTerm); ?>">
        <button type="submit"><i class="fa fa-search"></i> Chercher</button>
    </form>

    <center><p>Voulez-vous voir les dossiers archivés ? <a href="Archivage/affichage_dossier.php">Visiter</a></p></center>
    <center><p>Voulez_vous Ajouter un dossier ? <a href="dossier.php">Ajouter</a></p></center>

    <table>
        <thead>
            <tr>
                <th>Nom du dossier</th>
                <th>Référence</th>
                <th>Envoi</th>
                <th>Réception</th>
                <th>Expéditeur</th>
                <th>Objet</th>
                <th>Ajouté par</th>
                <th colspan="2">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $count = 0;
            while ($utilisateur = $affichageUtilisateur->fetch(PDO::FETCH_ASSOC)) { 
                $count++;
            ?>
            <tr>
                <td data-label="Nom"><i class="fa-solid fa-folder" style="color:hsl(200, 93.20%, 23.10%);"></i> <?php echo htmlspecialchars($utilisateur["nom"]); ?></td>
                <td data-label="Référence"><?php echo htmlspecialchars($utilisateur["numero_reference"]); ?></td>
                <td data-label="Envoi"><?php echo htmlspecialchars($utilisateur["date_envoi"]); ?></td>
                <td data-label="Réception"><?php echo htmlspecialchars($utilisateur["date_reception"]); ?></td>
                <td data-label="Expéditeur"><?php echo htmlspecialchars($utilisateur["nom_destinateur"] ?? 'Inconnu'); ?></td>
                <td data-label="Objet"><?php echo htmlspecialchars($utilisateur["objet"]); ?></td>
                <td data-label="Utilisateur"><?php echo htmlspecialchars($utilisateur["username"]); ?></td>
                <td data-label="Actions" class="actions">
                    <a href="Archivage/affichage_dossier.php?archiver=<?php echo $utilisateur['id_dossier']; ?>"><i class="fa fa-archive"></i> Archiver</a><br>
                    <a href="fir.php?nom=<?php echo urlencode($utilisateur['nom']); ?>"><i class="fa fa-eye"></i> Voir</a>
                </td>
            </tr>
            <?php } ?>
            <?php if ($count === 0): ?>
                <tr><td colspan="9" style="text-align:center;">Aucun dossier trouvé.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<script>
function confirmDelete(dossierId) {
    if (confirm("Êtes-vous sûr de vouloir supprimer ce dossier ?")) {
        window.location.href = "dossier.php?sup=" + dossierId;
    }
}
</script>
</body>
</html>
