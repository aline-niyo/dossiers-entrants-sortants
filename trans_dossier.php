<?php
session_start(); 

if (!isset($_SESSION['connecté'])) {
    header('Location: index.php');
    exit();
}

$roles = explode(',', $_SESSION['roles']); 
if (!in_array('secretaire_executif', $roles) && !in_array('secretaire', $roles) && !in_array('secretaire_interim', $roles)) {
    // accès restreint
}

include "connexion.php";

// TRANSFERT DE DOSSIER
if (isset($_GET['trans'])) {
    $id = intval($_GET['trans']);
    $stmt = $conn->prepare("SELECT * FROM dossiers WHERE id_dossier = ?");
    $stmt->execute([$id]);
    $dossier = $stmt->fetch();

    if ($dossier) {
        $id_destinateur = $dossier['id_destinateur'];

        // Vérifie si le dossier a déjà été transféré
        $check = $conn->prepare("SELECT COUNT(*) FROM dossiers_transferes WHERE numero_reference = ? AND id_destinateur = ?");
        $check->execute([$dossier['numero_reference'], $id_destinateur]);
        $dejaTransfere = $check->fetchColumn();

        if ($dejaTransfere == 0) {
            $dateTransfert = date("Y-m-d H:i:s");
$insert = $conn->prepare("
    INSERT INTO dossiers_transferes 
    (nom, numero_reference, date_envoi, date_reception, id_destinateur, objet, id_utilisateur, date_transfere)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?)
");
$insert->execute([
    $dossier['nom'], 
    $dossier['numero_reference'], 
    $dossier['date_envoi'],
    $dossier['date_reception'], 
    $dossier['id_destinateur'], 
    $dossier['objet'],
    $dossier['id_utilisateur'], 
    $dateTransfert
]);


        } else {
            echo "<script>alert('Ce dossier a déjà été transféré vers ce composant.');</script>";
        }
    }
}

// AFFICHAGE DÉTAIL SI "VOIR" EST CLIQUÉ
$dossierDetail = null;
if (isset($_GET['voir'])) {
    $voirId = intval($_GET['voir']);
    $stmt = $conn->prepare("
        SELECT dt.*, de.nom_destinateur, u.username AS nom_utilisateur
        FROM dossiers_transferes dt
        LEFT JOIN destinateurs de ON dt.id_destinateur = de.id_destinateur
        LEFT JOIN utilisateurs u ON dt.id_utilisateur = u.id_utilisateur
        WHERE dt.id_dossier = ?
    ");
    $stmt->execute([$voirId]);
    $dossierDetail = $stmt->fetch();
}

// LISTE DES DOSSIERS TRANSFÉRÉS
$transferts = $conn->query("
    SELECT id_dossier, nom, date_transfere, fichier 
    FROM dossiers_transferes 
    ORDER BY date_transfere DESC
");
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Dossiers transférés</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <style>
        body { font-family: 'Segoe UI', sans-serif; background-color: #f2f4f8; margin: 0; padding: 20px; }
        h1, h2 { text-align: center; color: #333; margin-bottom: 20px; }
        .table-container, .details-container { max-width: 800px; margin: auto; }
        table { width: 100%; border-collapse: collapse; background-color: white; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        th, td { padding: 12px 15px; text-align: left; }
        th { background-color: #007BFF; color: white; font-size: 14px; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        td a.button { background-color: #28a745; color: white; padding: 6px 12px; border-radius: 5px; text-decoration: none; }
        td a.button:hover { background-color: #218838; }
        .details { background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); margin-bottom: 30px; }
        .details p { margin: 10px 0; }
        .details strong { color: #555; }
        .back { display: block; text-align: center; margin: 20px auto; background: #007BFF; color: white; padding: 10px 20px; border-radius: 5px; width: 150px; text-decoration: none; }
    </style>

</head>
<body>

<h1>Dossiers transférés</h1>

<?php if ($dossierDetail): ?>
<div class="details-container">
    <div class="details">
        <h2>Détail du dossier</h2>
        <p><strong>Nom :</strong> <?= htmlspecialchars($dossierDetail['nom']) ?></p>
        <p><strong>Référence :</strong> <?= htmlspecialchars($dossierDetail['numero_reference']) ?></p>
        <p><strong>Date d'envoi :</strong> <?= htmlspecialchars($dossierDetail['date_envoi']) ?></p>
        <p><strong>Date de réception :</strong> <?= htmlspecialchars($dossierDetail['date_reception']) ?></p>
        <p><strong>Objet :</strong> <?= htmlspecialchars($dossierDetail['objet']) ?></p>
        <p><strong>Destinateur :</strong> <?= htmlspecialchars($dossierDetail['nom_destinateur']) ?></p>
        <p><strong>Utilisateur Ajouté :</strong> <?= htmlspecialchars($dossierDetail["nom_utilisateur"]); ?></p>
        <p><strong>Date de transfert :</strong> <?= htmlspecialchars($dossierDetail['date_transfere']) ?></p>
        <?php if (!empty($dossierDetail['fichier'])): ?>
            <!-- <p><strong>Fichier :</strong> 
                <a class="button" href="ouvrir_fichier.php?fichier=<?= urlencode($dossierDetail['fichier']) ?>">Ouvrir</a>
            </p> -->
        <?php endif; ?>
        <a class="back" href="trans_dossier.php">Retour</a>
    </div>
</div>
<?php endif; ?>

<div class="table-container">
    <table>
        <thead>
            <tr>
                <th>Nom du dossier</th>
                <th>Date de transfert</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $transferts->fetch(PDO::FETCH_ASSOC)): ?>
            <tr>
                <td>
                    <img src="https://img.icons8.com/color/20/000000/folder-invoices.png" alt="Dossier">
                    <?= htmlspecialchars($row['nom']) ?>
                </td>
                <td><?= htmlspecialchars($row['date_transfere']) ?></td>
                <td>
                    <a class="button" href="?voir=<?= $row['id_dossier'] ?>">Details</a>
                    <a class="button" href="fir.php?nom=<?= urlencode($row['nom']); ?>">Voir</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

</body>
</html>