<?php
session_start();

if (!isset($_SESSION['connecté'])) {
    header('Location: ../index.php');
    exit();
}

include "../connexion.php"; // Adjust the path as needed

if (isset($_GET['id'])) {
    $id_reponse = intval($_GET['id']);
    
    // Move the response to an archive table or update its status
    $archiveQuery = $conn->prepare("INSERT INTO reponse_archive (date_reponse, reponse, id_utilisateur, id_destinateur, id_dossier) 
                                     SELECT date_reponse, reponse, id_utilisateur, id_destinateur, id_dossier 
                                     FROM reponse WHERE id_reponse = :id_reponse");
    $archiveQuery->bindParam(':id_reponse', $id_reponse);
    
    $deleteQuery = $conn->prepare("DELETE FROM reponse WHERE id_reponse = :id_reponse");
    $deleteQuery->bindParam(':id_reponse', $id_reponse);

    try {
        $conn->beginTransaction();
        $archiveQuery->execute();
        $deleteQuery->execute();
        $conn->commit();
        
        echo "<script>alert('Réponse archivée avec succès !'); window.location.href='./affichage_reponse.php';</script>";

    } catch (PDOException $e) {
        $conn->rollBack();
        echo "<script>alert('Erreur lors de l\'archivage : " . htmlspecialchars($e->getMessage()) . "'); window.location.href='../reponse.php';</script>";
    }
} else {
    echo "<script>alert('Aucun ID de réponse fourni.'); window.location.href='../reponse.php';</script>";
}
?>