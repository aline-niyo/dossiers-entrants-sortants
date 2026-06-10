<?php
session_start();
if (!isset($_SESSION['connecté'])) {
    header('Location: ../index.php');
    exit();
}

include "../connexion.php";

if (isset($_POST['valider'])) {
    $date_ajout = $_POST['date'];
    $id_dossier = intval($_POST['dossier']);

    if (isset($_FILES['fichier']) && $_FILES['fichier']['error'] == 0) {
        $fichier_nom = basename($_FILES['fichier']['name']);
        $fichier_tmp = $_FILES['fichier']['tmp_name'];

        // Récupérer le nom du dossier sélectionné
        $req_nom = $conn->prepare("SELECT nom FROM dossiers WHERE id_dossier = ?");
        $req_nom->execute([$id_dossier]);
        $nom_dossier = $req_nom->fetchColumn();

        if ($nom_dossier) {
            // Nettoyer le nom du dossier pour l'utiliser dans un chemin de fichier
            $nom_dossier_nettoye = preg_replace('/[^a-zA-Z0-9_-]/', '_', $nom_dossier);
            $dossier_upload = "../uploads/" . $nom_dossier_nettoye;

            // Créer le dossier s'il n'existe pas
            if (!is_dir($dossier_upload)) {
                mkdir($dossier_upload, 0777, true);
            }

            $chemin_fichier = $dossier_upload . "/" . $fichier_nom;

            // Vérifier si le fichier existe déjà pour ce dossier
            $check = $conn->prepare("SELECT COUNT(*) FROM fichiers_dossiers WHERE nom_fichier = ? AND id_dossier = ?");
            $check->execute([$fichier_nom, $id_dossier]);
            $existe = $check->fetchColumn();

            if ($existe == 0) {
                if (move_uploaded_file($fichier_tmp, $chemin_fichier)) {
                    $stmt = $conn->prepare("INSERT INTO fichiers_dossiers (date_ajout, id_dossier, nom_fichier) VALUES (?, ?, ?)");
                    $stmt->execute([$date_ajout, $id_dossier, $fichier_nom]);
                    echo "<script>alert('Fichier ajouté avec succès.');</script>";
                    header('location: ../affichage_dossier_fichier.php');
                } else {
                    echo "<script>alert('Erreur lors du déplacement du fichier.');</script>";
                }
            } else {
                echo "<script>alert('Ce fichier existe déjà pour ce dossier.');</script>";
                header('location: ../affichage_dossier_fichier.php');
            }
        } else {
            echo "<script>alert('Dossier introuvable.');</script>";
        }
    } else {
        echo "<script>alert('Aucun fichier valide reçu.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajout de Fichier</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        h1 {
            color: blue;
            text-align: center;
            margin: 2%;
        }
        form {
            background: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            margin: auto;
        }
        div {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: blue;
        }
        input[type="date"],
        input[type="file"],
        select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            color: chocolate;
        }
        button {
            background-color: #5cb85c;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
        }
        button:hover {
            background-color: #4cae4c;
        }
    </style>
</head>
<body>
    <h1>Ajout des fichiers dans un dossier</h1>
    <form action="" method="POST" enctype="multipart/form-data">
        <div>
            <label for="date">Date d'Ajout :</label>
            <input type="date" name="date" required value="<?php echo date('Y-m-d'); ?>" max="<?php echo date('Y-m-d'); ?>">
        </div>
        <div>
            <label for="dossier">Dossier Existant :</label>
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
        <div>
            <label for="files">Ajout du fichier :</label>
            <input type="file" name="fichier" required>
        </div>
        <button type="submit" name="valider">Envoyer</button>
    </form>
</body>
</html>
