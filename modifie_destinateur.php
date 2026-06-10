<?php
include "connexion.php";

$desti = $conn->query("SELECT id_destinateur, date,nom_destinateur,adresse,telephone FROM destinateurs")->fetchAll(PDO::FETCH_ASSOC);

if (isset($_GET['mod'])) {
    $modifie = intval($_GET['mod']);
    $modificationEnCours = true;

    // Récupération de l'enregistrement à modifier
    $modification = $conn->prepare("SELECT * FROM destinateurs WHERE id_destinateur = :id_destinateur");
    $modification->bindParam(':id_destinateur', $modifie, PDO::PARAM_INT);
    $modification->execute();
    $moddesti = $modification->fetch(PDO::FETCH_ASSOC);

    if (!$moddesti) {
        echo "<script>alert('Destinateur non trouvé.');</script>";
        exit;
    }

    if (isset($_POST['Modifier'])) {
        $nom = $_POST['date'];
        $recupedesti = $_POST['nom_destinateur'];
        $addre = $_POST['adresse'];
        $tel = $_POST['telephone'];

        // Vérification si le numéro existe déjà dans un autre enregistrement
        $checkTel = $conn->prepare("SELECT COUNT(*) FROM destinateurs WHERE telephone = :tel AND id_destinateur != :id");
        $checkTel->bindParam(':tel', $tel);
        $checkTel->bindParam(':id', $modifie, PDO::PARAM_INT);
        $checkTel->execute();
        $telExists = $checkTel->fetchColumn();

        if ($telExists > 0) {
            echo "<script>alert('Ce numéro de téléphone est déjà utilisé par un autre destinateur.');</script>";
        } else {
            $modfie = $conn->prepare("
                UPDATE destinateurs 
                SET date = :date, nom_destinateur = :nom_destinateur, adresse = :adresse, telephone = :telephone 
                WHERE id_destinateur = :id_destinateur
            ");
            $modfie->bindParam(':date', $nom);
            $modfie->bindParam(':nom_destinateur', $recupedesti);
            $modfie->bindParam(':adresse', $addre);
            $modfie->bindParam(':telephone', $tel);
            $modfie->bindParam(':id_destinateur', $modifie, PDO::PARAM_INT);

            if ($modfie->execute()) {
                echo "<script>alert('Modification réussie');</script>";
                header('Location: destinateur.php');
                exit();
            } else {
                echo "<script>alert('Erreur de modification');</script>";
            }
        }
    }
} else {
    echo "<script>alert('Aucune ID de destinataire spécifiée.');</script>";
    exit();
}
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier le Destinateur</title>
    <link rel="stylesheet" href="style/desti.css">
</head>
<body>
    <form method="POST" action="">
        <h1>Modifier le Destinateur</h1>
        
        <label for="date_acceuil">Date d'Accueil :</label>
        <input type="date" name="date" placeholder="date d'ajout" varequired value="<?php echo date('Y-m-d'); ?>" max="<?php echo date('Y-m-d'); ?>">

        <label for="nom_destinateur">Nom Destinateur :</label>
        <input type="text" id="nom_destinateur" name="nom_destinateur" 
               placeholder="Nom du destinataire" 
               value="<?php echo htmlspecialchars($moddesti['nom_destinateur']); ?>" required>

        <label for="adresse">Adresse :</label>
        <input type="text" id="adresse" name="adresse" 
               placeholder="Votre adresse" 
               value="<?php echo htmlspecialchars($moddesti['adresse']); ?>" required>

        <label for="telephone">Téléphone :</label>
        <input type="text" id="telephone" name="telephone" 
               placeholder="Votre téléphone"
               pattern="[0-9]{8,15}" 
               title="Le numéro doit contenir entre 8 et 15 chiffres." 
               value="<?php echo htmlspecialchars($moddesti['telephone']); ?>" required>

        <button type="submit" name="Modifier">Modifier</button>
    </form>
</body>
</html>
