<?php
session_start();
if (!isset($_SESSION['connecté'])) {
    header('Location: ../index.php');
    exit();
}
$roles = explode(',', $_SESSION['roles']);
if (!in_array('secretaire_executif', $roles) && !in_array('secretaire', $roles) && !in_array('secretaire_interim', $roles)) {
    
}

include "../connexion.php"; 


if (isset($_GET["sup"])) {
    try {
        $dossier = intval($_GET["sup"]); 
        $suppression = $conn->prepare("DELETE FROM dossiers WHERE id_dossier = ?");
        $suppression->execute([$dossier]);
        echo "<script>alert('Dossier supprimé avec succès !');</script>";
    } catch (PDOException $e) {
        echo '<div class="message error">Erreur lors de la suppression : ' . htmlspecialchars($e->getMessage()) . '</div>';
    }
}

$searchTerm = '';
if (isset($_GET['search'])) {
    $searchTerm = htmlspecialchars($_GET['search']);
    $affichageUtilisateur = $conn->prepare("
        SELECT d.id_dossier, d.nom, d.numero_reference, d.date_envoi, d.date_reception, de.nom_destinateur, d.objet 
        FROM dossiers d
        JOIN destinateurs de ON d.id_destinateur = de.id_destinateur
        WHERE d.numero_reference LIKE :searchTerm OR de.nom_destinateur LIKE :searchTerm
    ");
    $affichageUtilisateur->execute([':searchTerm' => '%' . $searchTerm . '%']);
} else {
    $affichageUtilisateur = $conn->query("
        SELECT d.id_dossier, d.nom, d.numero_reference, d.date_envoi, d.date_reception, de.nom_destinateur, d.objet 
        FROM dossiers d
        JOIN destinateurs de ON d.id_destinateur = de.id_destinateur
    ");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<div class="container">
    <h1>Liste des Dossiers Enregistrés</h1>
    <form action="" method="GET" class="search-container">
        <input type="text" name="search" placeholder="Rechercher un dossier..." value="<?php echo htmlspecialchars($searchTerm); ?>">
        <button type="submit"><i class="fa fa-search"></i> Chercher</button>
    </form>
    <center><p>Voulez-vous voir les dossiers archivés ? <a href="Archivage/affichage_dossier.php">Visiter</a></p></center>

    <table>
        <thead>
            <tr>
                <th>Nom du dossier</th>
                <th>Numéro de Référence</th>
                <th>Date d'Envoi</th>
                <th>Date de Réception</th>
                <th>Nom de l'Expéditeur</th>
                <th>Objet</th>
                <th colspan="4">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $count = 0;
            while ($utilisateur = $affichageUtilisateur->fetch(PDO::FETCH_ASSOC)) { 
                $count++;
            ?>
            <tr>
                <td><i class="fa-solid fa-folder" style="color: #f0ad4e;"></i> <?php echo htmlspecialchars($utilisateur["nom"]); ?></td>
                <td><?php echo htmlspecialchars($utilisateur["numero_reference"]); ?></td>
                <td><?php echo htmlspecialchars($utilisateur["date_envoi"]); ?></td>
                <td><?php echo htmlspecialchars($utilisateur["date_reception"]); ?></td>
                <td><?php echo htmlspecialchars($utilisateur["nom_destinateur"] ?? 'Inconnu'); ?></td>
                <td><?php echo htmlspecialchars($utilisateur["objet"]); ?></td>
                
                <td>
                    <option value="selected">
                        <td><a href="ouvrir_dos.php?id=<?php echo $utilisateur['id_dossier']; ?>"><i class="fa fa-comment-dots"></i> Commentaires</a></td>
                        <td><a href="modifie_dossier.php?mod=<?php echo $utilisateur["id_dossier"]; ?>"><i class="fa fa-edit"></i> Modifier</a></td>
                        <td><a href="Archivage/affichage_dossier.php?archiver=<?php echo $utilisateur['id_dossier']; ?>"><i class="fa fa-archive"></i> Archiver</a></td>
                        <td><a href="trans_dossier.php?trans=<?php echo $utilisateur['id_dossier']; ?>"><i class="fa fa-share"></i> Transférer</a></td>
                    </option>
                </td>

            </tr>
            <?php } ?>
            <?php if ($count === 0): ?>
                <tr><td colspan="10">Aucun dossier trouvé.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<script>
function confirmDelete(dossierId) {
    const confirmation = confirm("Êtes-vous sûr de vouloir supprimer ce dossier ?");
    if (confirmation) {
        window.location.href = "dossier.php?sup=" + dossierId;
    }
}
</script>
    
</body>
</html>
