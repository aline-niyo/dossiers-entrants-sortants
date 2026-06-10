<?php
session_start(); 

if (!isset($_SESSION['connecté'])) {
    header('Location: index.php');
    exit();
}

$roles = explode(',', $_SESSION['roles']); 
if (!in_array('secretaire_executif', $roles) && !in_array('secretaire', $roles) && !in_array('secretaire_interim', $roles)) {
    // Handle unauthorized access here
}
include "connexion.php";
$desti = $conn->query("SELECT * FROM validation as val 
JOIN utilisateurs as user ON val.id_utilisateur = user.id_utilisateur 
JOIN dossiers as dos ON val.id_dossier = dos.id_dossier 
JOIN reponse as res ON val.id_reponse = res.id_reponse")->fetchAll(PDO::FETCH_ASSOC);

if (isset($_GET['mod'])) {
    $modifie = intval($_GET['mod']);
    $modificationEnCours = true;

    $modification = $conn->prepare("SELECT * FROM validation WHERE id_validation = :id_validation");
    $modification->bindParam(':id_validation', $modifie, PDO::PARAM_INT);
    $modification->execute();
    $moddesti = $modification->fetch(PDO::FETCH_ASSOC);

    if ($moddesti) { 
        if (isset($_POST['valider'])) {
            $reupedate = $_POST['date_validation'];
            $recupuser = $_POST['utilisateur'];
            $recupedosse = $_POST['dossier'];
            $recuperepo = $_POST['reponse'];

            // Gestion de la signature
            if (isset($_FILES['sign']) && $_FILES['sign']['error'] == 0) {
                $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
                if (in_array($_FILES['sign']['type'], $allowedTypes)) {
                    $photo = file_get_contents($_FILES['sign']['tmp_name']);
                } else {
                    echo "<script>alert('Type de fichier non autorisé.');</script>";
                    exit();
                }
            } else {
                echo "<script>alert('Erreur lors du téléchargement de la signature.');</script>";
                exit();
            }

            // Préparation de la mise à jour
            $update = $conn->prepare("UPDATE validation SET date_validation = :date_validation, id_utilisateur = :id_utilisateur, id_dossier = :id_dossier, id_reponse = :id_reponse, sign = :sign WHERE id_validation = :id_validation");

            $update->bindParam(':date_validation', $reupedate);
            $update->bindParam(':id_utilisateur', $recupuser);
            $update->bindParam(':id_dossier', $recupedosse);
            $update->bindParam(':id_reponse', $recuperepo);
            $update->bindParam(':sign', $photo);
            $update->bindParam(':id_validation', $modifie, PDO::PARAM_INT);

            if ($update->execute()) {
                echo "<script>alert('Modification réussie');</script>";
                header('Location: register.php');
                exit();
            } else {
                $errorInfo = $update->errorInfo();
                echo "<script>alert('Erreur de modification: " . htmlspecialchars($errorInfo[2]) . "');</script>";
            }
        }
    } else {
        echo "<script>alert('Validation introuvable.');</script>";
    }
} else {
    echo "<script>alert('Aucune ID de validation spécifiée.');</script>";
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page de Validation</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f7f7f7;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .validation-container {
            background-color: #fff;
            border-radius: 12px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
            padding: 30px;
            width: 500px;
            max-width: 90%;
            text-align: center;
        }

        h1 {
            color: #3498db;
            margin-bottom: 20px;
            font-size: 28px;
        }

        .form-group {
            margin-bottom: 25px;
            text-align: left;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #555;
            font-weight: 600;
            font-size: 16px;
        }

        input[type="date"],
        select {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 16px;
            color: #333;
            background-color: #f9f9f9;
            transition: border-color 0.3s ease;
        }

        input[type="date"]:focus,
        select:focus {
            border-color: #3498db;
            outline: none;
            box-shadow: 0 2px 4px rgba(0, 123, 255, 0.1);
        }

        select option {
            background-color: #fff;
            color: #333;
        }

        button[type="submit"] {
            background-color: #3498db;
            color: #fff;
            padding: 14px 24px;
            border: none;
            border-radius: 6px;
            font-size: 18px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button[type="submit"]:hover {
            background-color: #2980b9;
        }
    </style>
</head>
<body>
    <div class="validation-container">
        <h1>Page de Validation</h1>
        <form action="" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="date">Date de Validation :</label>
                <input type="date" name="date_validation" value="<?php echo htmlspecialchars($moddesti['date_validation']); ?>" required max="<?php echo date('Y-m-d'); ?>">
            </div>
            <div class="form-group">
                <label>Utilisateur :</label>
                <select name="utilisateur" required>
                    <option value="" disabled selected>Sélectionnez un utilisateur</option>
                    <?php
                    try {
                        $affichagerole = $conn->query("SELECT id_utilisateur, username FROM utilisateurs");
                        while ($datarecup = $affichagerole->fetch()) {
                            echo "<option value='" . htmlspecialchars($datarecup['id_utilisateur'], ENT_QUOTES, 'UTF-8') . "'" . ($datarecup['id_utilisateur'] == $moddesti['id_utilisateur'] ? ' selected' : '') . ">" . htmlspecialchars($datarecup['username'], ENT_QUOTES, 'UTF-8') . "</option>";
                        }
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
                        $affichagerole = $conn->query("SELECT * FROM reponse");
                        while ($datarecup = $affichagerole->fetch()) {
                            echo "<option value='" . htmlspecialchars($datarecup['id_reponse'], ENT_QUOTES, 'UTF-8') . "'" . ($datarecup['id_reponse'] == $moddesti['id_reponse'] ? ' selected' : '') . ">" . htmlspecialchars($datarecup['id_reponse'], ENT_QUOTES, 'UTF-8') . "</option>";
                        }
                    } catch (PDOException $e) {
                        echo "<option value=''>Erreur de chargement des réponses</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="sign">Signature :</label>
                <input type="file" name="sign" accept="image/*" required>
            </div>
            <button type="submit" name="valider">Valider</button>
        </form>
    </div>
</body>
</html>