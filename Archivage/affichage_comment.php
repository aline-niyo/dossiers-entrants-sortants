<?php
session_start();
if (!isset($_SESSION['connecté'])) {
    header('Location: ../index.php');
    exit();
}

include "../connexion.php";

// Récupérer les commentaires archivés
$commentairesArchives = $conn->query("
    SELECT 
        arc.id_commentaire, 
        arc.date_commentaire, 
        arc.commentaire, 
        dos.nom AS dossier, 
        user.username AS utilisateur 
    FROM 
        commentaires_archive AS arc
    JOIN 
        utilisateurs AS user ON arc.id_utilisateur = user.id_utilisateur 
    JOIN 
        dossiers AS dos ON arc.id_dossier = dos.id_dossier
    ORDER BY arc.date_commentaire DESC, arc.id_commentaire DESC
");
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Commentaires Archivés</title>
    <link rel="stylesheet" href="../style/comernt.css">
</head>
<body>

<center><h1>Commentaires Archivés</h1></center>

<div class="container">
    
    <table>
        <thead>
            <tr>
                <th>Date Commentaire</th>
                <th>Commentaire</th>
                <th>Dossier</th>
                <th>Utilisateur</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($commentaire = $commentairesArchives->fetch(PDO::FETCH_ASSOC)) { ?>
                <tr>
                    <td><?= htmlspecialchars($commentaire["date_commentaire"]); ?></td>
                    <td><?= htmlspecialchars($commentaire["commentaire"]); ?></td>
                    <td><?= htmlspecialchars($commentaire["dossier"]); ?></td>
                    <td><?= htmlspecialchars($commentaire["utilisateur"]); ?></td>
                    <td><a href="desarchiver_comment.php?mod=<?= $commentaire['id_commentaire']; ?>" class="action-btn edit">Désarchiver</a>
                    </td>
                </tr>

            <?php } ?>
        </tbody>
    </table>
</div>

</body>
</html>
