<?php
session_start();
if (!isset($_SESSION['connecté'])) {
    header('Location: ../index.php');
    exit();
}
$roles = explode(',', $_SESSION['roles']);
if (!array_intersect(['secretaire_executif','secretaire','secretaire_interim'], $roles)) {
    header('HTTP/1.1 403 Forbidden');
    echo "Accès refusé";
    exit();
}

require_once __DIR__ . '/../connexion.php';

// Suppression si un ID est passé en paramètre
if (isset($_GET['supprimer'])) {
    $id_dossier = intval($_GET['supprimer']);

    $stmt_delete = $conn->prepare("DELETE FROM archivage_dossiers WHERE id_dossier = ?");
    if ($stmt_delete->execute([$id_dossier])) {
        echo "<script>alert('Dossier archivé supprimé avec succès.'); window.location.href='affichage_dossier.php';</script>";
        exit();
    } else {
        echo "<script>alert('Erreur lors de la suppression.'); window.location.href='affichage_dossier.php';</script>";
        exit();
    }
}

// Requête principale d'affichage
$stmt = $conn->query("
    SELECT 
      a.id_dossier,
      a.nom,
      a.numero_reference,
      a.date_envoi,
      a.date_reception,
      de.nom_destinateur,
      a.objet,
      u.username AS archive_par,
      a.archived_at
    FROM archivage_dossiers a
    LEFT JOIN destinateurs de ON a.id_destinateur = de.id_destinateur
    LEFT JOIN utilisateurs u  ON a.id_utilisateur = u.id_utilisateur
    ORDER BY a.archived_at DESC
");
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Archives des dossiers</title>
  <link rel="stylesheet" href="../style/dossier.css">
  <script>
    function confirmerSuppression(id) {
      if (confirm('Êtes-vous sûr de vouloir supprimer définitivement ce dossier archivé ?')) {
        window.location.href = 'affichage_dossier.php?supprimer=' + id;
      }
    }
  </script>
</head>
<body>

  <div class="container">
    <h1>Archives des Dossiers</h1>
    <table>
      <thead>
        <tr>
          <th>Nom du dossier</th>
          <th>Réf.</th>
          <th>Date d'envoi</th>
          <th>Date de réception</th>
          <th>Expéditeur</th>
          <th>Objet</th>
          <th>Archivé par</th>
          <th>Date d'archivage</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if($stmt->rowCount()): ?>
          <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
            <tr>
              <td data-label="Nom"><?php echo htmlspecialchars($row['nom']); ?></td>
              <td data-label="Réf"><?php echo htmlspecialchars($row['numero_reference']); ?></td>
              <td data-label="Envoi"><?php echo htmlspecialchars($row['date_envoi']); ?></td>
              <td data-label="Réception"><?php echo htmlspecialchars($row['date_reception']); ?></td>
              <td data-label="Expéditeur"><?php echo htmlspecialchars($row['nom_destinateur'] ?? 'Inconnu'); ?></td>
              <td data-label="Objet"><?php echo htmlspecialchars($row['objet']); ?></td>
              <td data-label="Archivé par"><?php echo htmlspecialchars($row['archive_par']); ?></td>
              <td data-label="Archivage"><?php echo htmlspecialchars($row['archived_at']); ?></td>
              <td data-label="Actions">
                <a href="desarchiver_dossier.php?desarchiver=<?php echo $row['id_dossier']; ?>">Désarchiver</a> |
                <a href="#" onclick="confirmerSuppression(<?php echo $row['id_dossier']; ?>)">Supprimer</a>
              </td>
            </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr><td colspan="9">Aucun dossier archivé.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>

    <p><a href="../dossier.php" class="plus-button">‹ Retour aux dossiers actifs</a></p>
  </div>

</body>
</html>
