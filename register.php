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
    $reupedate = $_POST['date_validation'];
    $recupuser = $_POST['utilisateur'];
    $recupedosse = $_POST['dossier'];
    $recuperepo = $_POST['reponse'];
    $photo = null;

    if (isset($_FILES['sign']) && $_FILES['sign']['error'] === UPLOAD_ERR_OK) {
        $photo = file_get_contents($_FILES['sign']['tmp_name']);
    }

    $insertdata = $conn->prepare("INSERT INTO validation (date_validation, sign, id_utilisateur, id_dossier, id_reponse) VALUES (:date_validation, :sign, :utilisateur, :dossier, :reponse)");

    $insertdata->bindParam(':date_validation', $reupedate);
    $insertdata->bindParam(':sign', $photo, PDO::PARAM_LOB);
    $insertdata->bindParam(':utilisateur', $recupuser);
    $insertdata->bindParam(':dossier', $recupedosse);  
    $insertdata->bindParam(':reponse', $recuperepo);

    try {
        if ($insertdata->execute()) {
            echo "<script>alert('Validation réussie'); window.location.href='register.php';</script>";
        } else {
            echo "<script>alert('Validation non réussie');</script>";
        }
    } catch (PDOException $e) {
        echo "<script>alert('Erreur lors de la validation : " . htmlspecialchars($e->getMessage()) . "');</script>";
    }
}

$affichageUtilisateur = $conn->query("
    SELECT val.id_validation, val.date_validation, user.username, dos.nom, res.reponse, val.sign 
    FROM validation as val 
    JOIN utilisateurs as user ON val.id_utilisateur = user.id_utilisateur 
    JOIN dossiers as dos ON val.id_dossier = dos.id_dossier 
    JOIN reponse as res ON val.id_reponse = res.id_reponse
    ORDER BY val.date_validation DESC, val.id_validation DESC");

if (isset($_GET["sup"])) {
    try {
        $comment = intval($_GET['sup']); // Assurez-vous que c'est un entier
        $suppression = $conn->prepare("DELETE FROM validation WHERE id_validation = :id_validation");
        $suppression->bindParam(':id_validation', $comment, PDO::PARAM_INT);
        $suppression->execute();
        echo "<script>alert('Validation supprimée avec succès !');</script>";
    } catch (PDOException $e) {
        echo '<div class="message error">Erreur lors de la suppression : ' . htmlspecialchars($e->getMessage()) . '</div>';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page de Validation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
        }
        .validation-container {
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            max-width: 600px;
            margin: auto;
        }
        h1 {
            color: #3498db;
            text-align: center;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            color: #333;
        }
        input[type="date"],
        select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button[type="submit"] {
            background-color: #3498db;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
        }
        button[type="submit"]:hover {
            background-color: #2980b9;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 30px;
            background: white;
            border-radius: 10px;
            overflow: hidden;
        }
        th, td {
            padding: 12px 15px;
            border: 1px solid #ddd;
            text-align: center;
        }
        th {
            background-color: #3498db;
            color: white;
        }
        .action-btn {
            text-decoration: none;
            padding: 6px 12px;
            border-radius: 5px;
            font-size: 14px;
        }
        .delete {
            background-color: #e74c3c;
            color: white;
        }
        .edit {
            background-color: #f39c12;
            color: white;
        }
        .action-btn:hover {
            opacity: 0.85;
        }
    </style>
</head>
<body>
    <div class="validation-container">
        <h1>Page de Validation</h1>
        <form action="" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="date">Date de Validation :</label>
                <input type="date" name="date_validation" required value="<?php echo date('Y-m-d'); ?>" max="<?php echo date('Y-m-d'); ?>" min="<?php echo date('Y-m-d'); ?>">
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
            <div class="form-group">
                <label>Dossier :</label>
                <select name="dossier" required>
                    <option value="" disabled selected>Sélectionnez un dossier</option>
                    <?php
                    try {
                        $affichagerole = $conn->query("SELECT id_dossier, nom FROM dossiers");
                        while ($datarecup = $affichagerole->fetch()) {
                            echo "<option value='" . htmlspecialchars($datarecup['id_dossier'], ENT_QUOTES, 'UTF-8') . "'" . ($datarecup['id_dossier'] == $moddesti['id_dossier'] ? ' selected' : '') . ">" . htmlspecialchars($datarecup['nom'], ENT_QUOTES, 'UTF-8') . "</option>";
                        }
                    } catch (PDOException $e) {
                        echo "<option value=''>Erreur de chargement des dossiers</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="form-group">
                <label>Réponse :</label>
                <select name="reponse" required>
                    <option value="" disabled selected>Sélectionnez une réponse</option>
                    <?php
                    try {
                        $affichagerole = $conn->query("SELECT id_reponse, reponse FROM reponse");
                        while ($datarecup = $affichagerole->fetch(PDO::FETCH_ASSOC)) {
                            echo "<option value='" . htmlspecialchars($datarecup['id_reponse'], ENT_QUOTES, 'UTF-8') . "'>" . htmlspecialchars($datarecup['reponse'], ENT_QUOTES, 'UTF-8') . "</option>";
                        }
                        $affichagerole->closeCursor();
                    } catch (PDOException $e) {
                        echo "<option value=''>Erreur de chargement des réponses</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="vue_signa">Signature :</label>
                <input type="file" name="sign" accept="image/*" required>
            </div>
            <button type="submit" name="valider">Valider</button>
        </form>
    </div>

    <h1>Fichiers des dossiers validés</h1>
    <form action="" method="GET">
        <table>
            <thead>
                <tr>
                    <th>Date de validation</th>
                    <th>Utilisateur</th>
                    <th>Dossier</th>
                    <th>Réponse</th>
                    <th>Signature</th>
                    <th colspan="2">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($destinateur = $affichageUtilisateur->fetch(PDO::FETCH_ASSOC)) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($destinateur["date_validation"]); ?></td>
                        <td><?php echo htmlspecialchars($destinateur['username']); ?></td>
                        <td><?php echo htmlspecialchars($destinateur["nom"]); ?></td>
                        <td><?php echo htmlspecialchars($destinateur['reponse']); ?></td>
                        <td>
                            <?php if (!empty($destinateur['sign'])): ?>
                                <img src="data:image/png;base64,<?= base64_encode($destinateur['sign']) ?>" alt="Signature" style="width: 100px; height: auto;">
                            <?php else: ?>
                                Pas de signature
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="#" onclick="confirmDelete(<?php echo $destinateur['id_validation']; ?>)" class="action-btn delete">Supprimer</a>
                        </td>
                        <td>
                            <a href="modifie_validation.php?mod=<?php echo $destinateur["id_validation"]; ?>" class="action-btn edit">Modifier</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </form>

    <script>
    function confirmDelete(validationId) {
        const confirmation = confirm("Êtes-vous sûr de vouloir supprimer cette validation ?");
        if (confirmation) {
            window.location.href = "affichage_validation.php?sup=" + validationId;
        }
    }
    </script>
</body>
</html>