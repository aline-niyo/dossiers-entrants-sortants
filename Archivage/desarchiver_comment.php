<?php
session_start();
if (!isset($_SESSION['connecté'])) {
    header('Location: ../index.php');
    exit();
}

include "../connexion.php";

if (isset($_GET['mod'])) {
    $id = intval($_GET['mod']);

    // Récupérer le commentaire archivé
    $stmt = $conn->prepare("SELECT * FROM commentaires_archive WHERE id_commentaire = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $commentaire = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($commentaire) {
        // Réinsérer dans la table principale sans spécifier l'id_commentaire (laisser MySQL auto-incrémenter)
        $insert = $conn->prepare("
            INSERT INTO commentaires (date_commentaire, commentaire, id_utilisateur, id_dossier)
            VALUES (:date_commentaire, :commentaire, :id_utilisateur, :id_dossier)
        ");
        $insert->execute([
            ':date_commentaire' => $commentaire['date_commentaire'],
            ':commentaire' => $commentaire['commentaire'],
            ':id_utilisateur' => $commentaire['id_utilisateur'],
            ':id_dossier' => $commentaire['id_dossier']
        ]);

        // Supprimer le commentaire de la table d'archives
        $delete = $conn->prepare("DELETE FROM commentaires_archive WHERE id_commentaire = :id");
        $delete->bindParam(':id', $id, PDO::PARAM_INT);
        $delete->execute();

        echo "<script>alert('Commentaire désarchivé avec succès.'); window.location.href='../comment.php';</script>";
    } else {
        echo "<script>alert('Commentaire archivé introuvable.'); window.location.href='affichage_comment.php';</script>";
    }
} else {
    echo "<script>alert('ID du commentaire manquant.'); window.location.href='affichage_comment.php';</script>";
}
?>
