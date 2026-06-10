<?php 
session_start(); 


if (!isset($_SESSION['connecté'])) {
    header('Location: index.php');
    exit();
}


$roles = explode(',', $_SESSION['roles']); 
if (!in_array('secretaire_executif,secretaire,secretaire_interim', $roles)) {
   
}
include "connexion.php";

if (isset($_POST['valider'])) {
    $recupedat = $_POST['date_reponse'];
    $recupose = $_POST['reponse'];
    $recupedata = $_POST['utilisateur'];
    $recupedataa = $_POST['destinateur'];
    $recupedataaa = $_POST['dossier'];

    // Vérifie si une réponse existe déjà pour ce trio utilisateur + destinateur + dossier
    $checkResponse = $conn->prepare("SELECT COUNT(*) FROM reponse WHERE id_utilisateur = :utilisateur AND id_destinateur = :destinateur AND id_dossier = :dossier");
    $checkResponse->bindParam(':utilisateur', $recupedata);
    $checkResponse->bindParam(':destinateur', $recupedataa);
    $checkResponse->bindParam(':dossier', $recupedataaa);
    $checkResponse->execute();
    $responseCount = $checkResponse->fetchColumn();

    if ($responseCount > 0) {
        echo "<script>alert('Vous avez déjà répondu à ce destinateur dans ce dossier.');</script>";
    } else {
        // Aucune réponse existante, insertion autorisée
        $insertdata = $conn->prepare("INSERT INTO reponse(date_reponse, reponse, id_utilisateur, id_destinateur, id_dossier) VALUES(:date_reponse, :reponse, :utilisateur, :destinateur, :dossier)");

        $insertdata->bindParam(':date_reponse', $recupedat);
        $insertdata->bindParam(':reponse', $recupose);
        $insertdata->bindParam(':utilisateur', $recupedata);
        $insertdata->bindParam(':destinateur', $recupedataa);
        $insertdata->bindParam(':dossier', $recupedataaa);

        try {
            if ($insertdata->execute()) {
                echo "<script>alert('Réponse ajoutée avec succès');</script>";
                header('location:reponse.php');
                exit(); 
            } else {
                echo "<script>alert('Aucune réponse ajoutée');</script>";
            }
        } catch (PDOException $e) {
            echo "<script>alert('Erreur: " . $e->getMessage() . "');</script>";
        }
    }
}

$affichageUtilisateur = $conn->query("SELECT res.id_reponse, res.date_reponse, res.reponse, user.username, dest.nom_destinateur, dos.nom 
    FROM reponse as res 
    JOIN utilisateurs as user ON res.id_utilisateur = user.id_utilisateur
    JOIN destinateurs as dest ON res.id_destinateur = dest.id_destinateur
    JOIN dossiers as dos ON res.id_dossier = dos.id_dossier
    ORDER BY res.date_reponse DESC, res.id_reponse DESC");

if (isset($_GET["sup"])) {  
    try {
        $comment = intval($_GET['sup']);
        $suppression = $conn->prepare("DELETE FROM reponse WHERE id_reponse = :id_reponse");
        $suppression->execute([':id_reponse' => $comment]);
        echo "<script>alert('Réponse supprimée avec succès !');</script>";
    } catch (PDOException $e) {
        echo '<div class="message error">Erreur lors de la suppression : ' . htmlspecialchars($e->getMessage()) . '</div>';
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réponse</title>
    <link rel="stylesheet" href="style/reponse.css">

</head>
<body>
 
    <fieldset>
        <form action="" method="POST">
            <h1>Ajout de la reponse</h1>
            <div>
                <label for="date_reponse">Date de réponse :</label>
                <input type="date" name="date_reponse" required value="<?php echo date('Y-m-d'); ?>" max="<?php echo date('Y-m-d'); ?>" min="<?php echo date('Y-m-d'); ?>">
            </div>
            <div> 
                <label for="reponse">réponse :</label>
                <input type="file" name="reponse" required>
            </div>
            <div>
                <label for="utilisateur">Utilisateur :</label>
                <select name="utilisateur" required>
                    <option value="" disabled selected>Sélectionnez un utilisateur</option>
                    <?php
                    include "connexion.php";
                    try {
                        $affichagerole = $conn->query("SELECT id_utilisateur, username FROM utilisateurs WHERE role = 'secretaire'");
                        while ($datarecup = $affichagerole->fetch()) {
                            echo "<option value='" . htmlspecialchars($datarecup['id_utilisateur'], ENT_QUOTES, 'UTF-8') . "'>" . htmlspecialchars($datarecup['username'], ENT_QUOTES, 'UTF-8') . "</option>";
                        }
                        $affichagerole->closeCursor();
                    } catch (PDOException $e) {
                        echo "<option value=''>Erreur de chargement des utilisateurs</option>";
                    }
                    ?>
                </select>
            </div>
            <div>
                <label for="destinateur">Destinateur :</label>
                <select name="destinateur" required>
                    <option value="" disabled selected>Sélectionnez un destinateur :</option>
                    <?php
                    try {
                        $affichagerole = $conn->query("SELECT id_destinateur, nom_destinateur FROM destinateurs");
                        while ($datarecup = $affichagerole->fetch()) {
                            echo "<option value='" . htmlspecialchars($datarecup['id_destinateur'], ENT_QUOTES, 'UTF-8') . "'>" . htmlspecialchars($datarecup['nom_destinateur'], ENT_QUOTES, 'UTF-8') . "</option>";
                        }
                        $affichagerole->closeCursor();
                    } catch (PDOException $e) {
                        echo "<option value=''>Erreur de chargement des destinateurs</option>";
                    }
                    ?>
                </select>
            </div>
            <div>
                <label for="dossier">Dossier :</label>
                <select name="dossier" required>
                    <option value="" disabled selected>Sélectionnez un dossier</option>
                    <?php
                    try {
                        $affichagerole = $conn->query("SELECT id_dossier, nom FROM dossiers");
                        while ($datarecup = $affichagerole->fetch()) {
                            echo "<option value='" . htmlspecialchars($datarecup['id_dossier'], ENT_QUOTES, 'UTF-8') . "'>" . htmlspecialchars($datarecup['nom'], ENT_QUOTES, 'UTF-8') . "</option>";
                        }
                        $affichagerole->closeCursor();
                    } catch (PDOException $e) {
                        echo "<option value=''>Erreur de chargement des dossiers</option>";
                    }
                    ?>
                </select>
            </div>
            <button type="submit" name="valider">Répondre</button>
        </form>
    </fieldset>
    <p>Voulez_vous voir les reponses Archives? <a href="Archivage/affichage_reponse.php">Visiter</a></p>

    <center><h1>Affichage des réponses</h1></center>
    <form action="" method="GET">
        <table>
            <thead>
                <tr>
                    <th>Date de réponse</th>
                    <th>Reponse</th>
                    <th>Utilisateur</th>
                    <th>Destinateur</th>
                    <th>Dossier</th>
                    <th colspan="2">Actions</th>
                    <!-- <th><a href="reponse.php" class="add-button">+</a></th> -->
                </tr>
            </thead>
            <tbody>
                <?php while ($utilisateur = $affichageUtilisateur->fetch(PDO::FETCH_ASSOC)) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($utilisateur["date_reponse"]); ?></td>
                        <td><?php echo htmlspecialchars($utilisateur["reponse"]); ?></td>
                        <td><?php echo htmlspecialchars($utilisateur["username"]); ?></td>
                        <td><?php echo htmlspecialchars($utilisateur["nom_destinateur"]); ?></td>
                        <td><?php echo htmlspecialchars($utilisateur["nom"]); ?></td>
                        <td>
                            <a href="Archivage/archivage_reponse.php?id=<?php echo $utilisateur['id_reponse']; ?>" class="action-btn archive">Archiver</a>
                        </td>
                        <td>
                            <a href="modifie_reponse.php?mod=<?php echo $utilisateur["id_reponse"]; ?>" class="action-btn edit">Modifier</a>
                        </td>
                        <td>
                            <a href="reponse_fichier.php?dossier=<?php echo urlencode($utilisateur['nom']); ?>" class="action-btn view">Voir</a>
                        </td>

                    
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </form>

    <script>
    function confirmDelete(reponseId) {
        const confirmation = confirm("Êtes-vous sûr de vouloir Archiver cette réponse ?");
        if (confirmation) {
            window.location.href = "affichage_reponse.php?sup=" + reponseId;
        }
    }
    </script>
</body>
</html>

