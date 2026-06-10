<?php
session_start();
if (!isset($_SESSION['connecté'])) {
    header('Location: ../index.php');
    exit();
}

include "../connexion.php";

if (isset($_GET['id'])) {
    $id_reponse = intval($_GET['id']);

    // Récupérer la réponse à désarchiver
    $reponseQuery = $conn->prepare("SELECT * FROM reponse_archive WHERE id_reponse = :id_reponse");
    $reponseQuery->bindParam(':id_reponse', $id_reponse);
    $reponseQuery->execute();
    $reponse = $reponseQuery->fetch(PDO::FETCH_ASSOC);

    if ($reponse) {
        // Insérer dans la table reponse
        $insertQuery = $conn->prepare("INSERT INTO reponse (date_reponse, reponse, id_utilisateur, id_destinateur, id_dossier) 
                                        VALUES (:date_reponse, :reponse, :id_utilisateur, :id_destinateur, :id_dossier)");
        $insertQuery->bindParam(':date_reponse', $reponse['date_reponse']);
        $insertQuery->bindParam(':reponse', $reponse['reponse']);
        $insertQuery->bindParam(':id_utilisateur', $reponse['id_utilisateur']);
        $insertQuery->bindParam(':id_destinateur', $reponse['id_destinateur']);
        $insertQuery->bindParam(':id_dossier', $reponse['id_dossier']);

        // Supprimer de la table archive_reponse
        $deleteQuery = $conn->prepare("DELETE FROM reponse_archive WHERE id_reponse = :id_reponse");
        $deleteQuery->bindParam(':id_reponse', $id_reponse);

        try {
            $conn->beginTransaction();
            $insertQuery->execute();
            $deleteQuery->execute();
            $conn->commit();
            echo "<script>alert('Réponse désarchivée avec succès !'); window.location.href='../reponse.php';</script>";
        } catch (PDOException $e) {
            $conn->rollBack();
            echo "<script>alert('Erreur lors de la désarchivage : " . htmlspecialchars($e->getMessage()) . "'); window.location.href='affichage_archive.php';</script>";
        }
    } else {
        echo "<script>alert('Réponse non trouvée.'); window.location.href='affichage_archive.php';</script>";
    }
} else {
    echo "<script>alert('Aucun ID de réponse fourni.'); window.location.href='affichage_archive.php';</script>";
}
?>