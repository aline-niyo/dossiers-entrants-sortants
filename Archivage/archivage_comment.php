<?php
session_start();
if (!isset($_SESSION['connecté'])) {
    header('Location: ../index.php');
    exit();
}

include "../connexion.php";

if (isset($_GET['mod'])) {
    $id = intval($_GET['mod']);

    $stmt = $conn->prepare("SELECT * FROM commentaires WHERE id_commentaire = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $commentaire = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($commentaire) {
        // Archiver le commentaire
        $insert = $conn->prepare("
            INSERT INTO commentaires_archive (id_commentaire, date_commentaire, commentaire, id_utilisateur, id_dossier)
            VALUES (:id_commentaire, :date_commentaire, :commentaire, :id_utilisateur, :id_dossier)
        ");
        $insert->execute([
            ':id_commentaire' => $commentaire['id_commentaire'],
            ':date_commentaire' => $commentaire['date_commentaire'],
            ':commentaire' => $commentaire['commentaire'],
            ':id_utilisateur' => $commentaire['id_utilisateur'],
            ':id_dossier' => $commentaire['id_dossier']
        ]);

        $delete = $conn->prepare("DELETE FROM commentaires WHERE id_commentaire = :id");
        $delete->bindParam(':id', $id, PDO::PARAM_INT);
        $delete->execute();

        echo "<script>alert('Commentaire archivé avec succès.'); window.location.href='./affichage_comment.php';</script>";
    } else {
        echo "<script>alert('Commentaire introuvable.'); window.location.href='../comment.php';</script>";
    }
} else {
    echo "<script>alert('ID de commentaire manquant.'); window.location.href='../comment.php';</script>";
}
?>
