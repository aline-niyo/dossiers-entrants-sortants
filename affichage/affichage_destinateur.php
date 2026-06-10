<?php

// include "connexion.php";
session_start(); 
if (!isset($_SESSION['connecté'])) {
    header('Location: index.php');
    exit();
}
$roles = explode(',', $_SESSION['roles']); 
if (!in_array('secretaire_executif,secretaire,secretaire_interim', $roles)) {
}


if (isset($_POST['valider'])) {
    $nom = $_POST['date'];
    $recupenom = $_POST['nom_destinateur'];
    $addre = $_POST['adresse'];
    $tel = $_POST['telephone'];

    // Vérifier si le numéro existe déjà
    $check = $conn->prepare("SELECT COUNT(*) FROM destinateurs WHERE telephone = :telephone");
    $check->bindParam(':telephone', $tel);
    $check->execute();
    $count = $check->fetchColumn();

    if ($count > 0) {
        echo "<script>alert('Ce numéro de téléphone est déjà utilisé !');</script>";
    } else {
        $insertdata = $conn->prepare("
            INSERT INTO destinateurs(date,nom_destinateur, adresse, telephone)
            VALUES (:date, :nom_destinateur, :adresse, :telephone)
        ");
        $insertdata->bindParam(':date',$nom);
        $insertdata->bindParam(':nom_destinateur', $recupenom);
        $insertdata->bindParam(':adresse', $addre);
        $insertdata->bindParam(':telephone', $tel);

        if ($insertdata->execute()) {
            echo "<script>alert('Destinateur ajouté avec succès');</script>";
            header('Location: destinateur.php');
            exit();
        } else {
            echo "<script>alert('Erreur lors de l'enregistrement du destinateur');</script>";
        }
    }
}
$searchTerm = '';
if (isset($_GET['search'])) {
    $searchTerm = htmlspecialchars($_GET['search']);
    $affichageUtilisateur = $conn->prepare("
        SELECT id_destinateur, date, nom_destinateur, adresse, telephone 
        FROM destinateurs 
        WHERE nom_destinateur LIKE :searchTerm
    ");
    $affichageUtilisateur->execute([':searchTerm' => '%' . $searchTerm . '%']);
} else {
    $affichageUtilisateur = $conn->query("
    SELECT id_destinateur, date, nom_destinateur, adresse, telephone 
    FROM destinateurs
    ORDER BY id_destinateur DESC");

}

// Suppression d'un destinataire
if (isset($_GET["sup"])) {
    try {
        $id_destinateur = intval($_GET['sup']);
        $suppression = $conn->prepare("
            DELETE FROM destinateurs 
            WHERE id_destinateur = :id_destinateur
        ");
        $suppression->execute([':id_destinateur' => $id_destinateur]);
        echo "<script>alert('Destinataire supprimé avec succès !');</script>";
    } catch (PDOException $e) {
        echo '<div class="message error">Erreur lors de la suppression : ' . htmlspecialchars($e->getMessage()) . '</div>';
    }
}
?>