<?php 
include "connexion.php";

// Récupère les commentaires existants
$desti = $conn->query("
    SELECT com.id_commentaire, com.date_commentaire, com.commentaire, com.id_utilisateur, com.id_dossier 
    FROM commentaires AS com 
    JOIN utilisateurs AS user ON com.id_utilisateur = user.id_utilisateur 
    JOIN dossiers AS dos ON comt.id_dossier = dos.id_dossier
")->fetchAll(PDO::FETCH_ASSOC);

if (isset($_GET['mod'])) {
    $modifie = intval($_GET['mod']);
    $modificationEnCours = true;

    // Récupère le commentaire à modifier
    $modification = $conn->prepare("SELECT * FROM commentaires WHERE id_commentaire = :id_commentaire");
    $modification->bindParam(':id_commentaire', $modifie, PDO::PARAM_INT);
    $modification->execute();
    $moddesti = $modification->fetch(PDO::FETCH_ASSOC);

    if ($moddesti) { 
        if (isset($_POST['valider'])) {
            // Récupère les données du formulaire
            $recuperadate = $_POST['date_commentaire'];
            $recupecoment = $_POST['commentaire'];
            $recupeuser = $_POST['utilisateur'];
            $recuperadoss = intval($_POST['dossier']); // Assure-toi que c'est un entier

            // Vérifie si l'id_dossier existe dans la table dossiers
            $checkDossier = $conn->prepare("SELECT COUNT(*) FROM dossiers WHERE id_dossier = :id_dossier");
            $checkDossier->bindParam(':id_dossier', $recuperadoss, PDO::PARAM_INT);
            $checkDossier->execute();
            $dossierExists = $checkDossier->fetchColumn();

            if ($dossierExists) {
                // Prépare la requête de mise à jour
                $modfie = $conn->prepare("UPDATE commentaires SET date_commentaire = :date_commentaire, commentaire = :commentaire, id_utilisateur = :id_utilisateur, id_dossier = :id_dossier WHERE id_commentaire = :id_commentaire");

                $modfie->bindParam(':date_commentaire', $recuperadate);
                $modfie->bindParam(':commentaire', $recupecoment);
                $modfie->bindParam(':id_utilisateur', $recupeuser);
                $modfie->bindParam(':id_dossier', $recuperadoss);
                $modfie->bindParam(':id_commentaire', $modifie, PDO::PARAM_INT);

                // Exécute la mise à jour
                if ($modfie->execute()) {
                    echo "<script>alert('Modification réussie');</script>";
                    header('Location: comment.php');
                    exit();
                } else {
                    echo "<script>alert('Erreur de modification');</script>";
                }
            } else {
                echo "<script>alert('Le dossier sélectionné n'existe pas.');</script>";
            }
        }
    } else {
        echo "<script>alert('Commentaire introuvable.');</script>";
    }
} else {
    echo "<script>alert('Aucune ID de commentaire spécifiée.');</script>";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Commentaire</title>
    <link rel="stylesheet" href="style/comernt.css">
</head>
<body>
    <center><h1>Commentaire sur Dossier</h1></center>
    <center>
    <form method="POST" action="">
        <div>
            <label>Date Commentaire :</label>
            <input type="date" name="date_commentaire" value="<?php echo htmlspecialchars($moddesti['date_commentaire']); ?>" required max="<?php echo date('Y-m-d'); ?>"><br>
            <label>Commentaire :</label>
            <input type="text" name="commentaire" value="<?php echo htmlspecialchars($moddesti['commentaire']); ?>" required><br>
        </div>
        <div class="utilisateur">
            <label>Utilisateur :</label>
            <select name="utilisateur" required>
                <option value="" disabled>Sélectionnez un utilisateur</option>
                <?php
                try {
                    $affichagerole = $conn->query("SELECT id_utilisateur, username FROM utilisateurs");
                    while ($datarecup = $affichagerole->fetch()) {
                        $selected = ($datarecup['id_utilisateur'] == $moddesti['id_utilisateur']) ? 'selected' : '';
                        echo "<option value='" . htmlspecialchars($datarecup['id_utilisateur'], ENT_QUOTES, 'UTF-8') . "' $selected>" . htmlspecialchars($datarecup['username'], ENT_QUOTES, 'UTF-8') . "</option>";
                    }
                    $affichagerole->closeCursor();
                } catch (PDOException $e) {
                    echo "<option value=''>Erreur de chargement des utilisateurs</option>";
                }
                ?>
            </select>
        </div>
        <div>
            <label>Dossier :</label>
            <select name="dossier" required>
                <option value="" disabled>Sélectionnez un dossier</option>
                <?php
                try {
                    $affichagerole = $conn->query("SELECT id_dossier, nom FROM dossiers");
                    while ($datarecup = $affichagerole->fetch()) {
                        $selected = ($datarecup['id_dossier'] == $moddesti['id_dossier']) ? 'selected' : '';
                        echo "<option value='" . htmlspecialchars($datarecup['id_dossier'], ENT_QUOTES, 'UTF-8') . "' $selected>" . htmlspecialchars($datarecup['nom'], ENT_QUOTES, 'UTF-8') . "</option>";
                    }
                    $affichagerole->closeCursor();
                } catch (PDOException $e) {
                    echo "<option value=''>Erreur de chargement des dossiers</option>";
                }
                ?>
            </select>
        </div>
        </center>
        <center><button type="submit" name="valider">Modifier</button></center>
    </form>
</body>
</html>
