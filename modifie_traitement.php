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

$desti = $conn->query("SELECT tr.id_traitement, tr.date_traitement, tr.id_utilisateur, tr.id_dossier 
                        FROM traitements AS tr 
                        JOIN utilisateurs AS user ON tr.id_utilisateur = user.id_utilisateur 
                        JOIN dossiers AS dos ON tr.id_dossier = dos.id_dossier")->fetchAll(PDO::FETCH_ASSOC);

if (isset($_GET['mod'])) {
    $modifie = intval($_GET['mod']);
    $modificationEnCours = true;

    $modification = $conn->prepare("SELECT * FROM traitements WHERE id_traitement = :id_traitement");
    $modification->bindParam(':id_traitement', $modifie, PDO::PARAM_INT);
    $modification->execute();
    $moddesti = $modification->fetch(PDO::FETCH_ASSOC);

    if (!$moddesti) {
        echo "<script>alert('Traitement introuvable.');</script>";
        exit();
    }

    if (isset($_POST['valider'])) {
        $recupedate = $_POST['date_traitement'];
        $recuperuser = $_POST['utilisateur'];
        $recupedossi = $_POST['dossier'];

        $modfie = $conn->prepare("UPDATE traitements SET date_traitement = :date_traitement, id_utilisateur = :id_utilisateur, id_dossier = :id_dossier WHERE id_traitement = :id_traitement");
        $modfie->bindParam(':date_traitement', $recupedate);
        $modfie->bindParam(':id_utilisateur', $recuperuser);
        $modfie->bindParam(':id_dossier', $recupedossi);
        $modfie->bindParam(':id_traitement', $modifie, PDO::PARAM_INT);

        if ($modfie->execute()) {
            echo "<script>alert('Modification réussie');</script>";
            header('Location:traitement.php');
            exit();
        } else {
            echo "<script>alert('Erreur de modification');</script>";
        }
    }
} else {
    echo "<script>alert('Aucune ID de traitement spécifiée.');</script>";
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Traitement</title>
    <style>
        body {
            background-color: #f4f4f9;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 50vh;
        }

        fieldset {
            border: 2px solid #3498db;
            border-radius: 8px;
            padding: 2em;
            width: 400px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
            background-color: white;
        }

        legend {
            font-size: 1.5em;
            color: #3498db;
            font-weight: bold;
        }

        label {
            display: block;
            margin: 10px 0 5px;
            font-weight: bold;
        }

        input[type="date"], select {
            width: 100%;
            padding: 10px;
            margin: 5px 0 15px;
            border: 1px solid #3498db;
            border-radius: 4px;
            font-size: 1em;
        }

        button[type="submit"] {
            padding: 12px 20px;
            background-color: #3498db;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1.1em;
            transition: background-color 0.3s;
            display: block;
            margin: 0 auto;
        }

        button[type="submit"]:hover {
            background-color: #2980b9;
        }

        p {
            color: #2980b9;
            text-align: center;
        }

        @media (max-width: 600px) {
            fieldset {
                width: 90%;
            }
        }
    </style>
</head>
<body>
    <fieldset>
        <center><p>Traitement</p></center>
        <form action="" method="POST">
            <div>
                <label for="date">Date de traitement :</label>
                <input type="date" name="date_traitement" value="<?php echo htmlspecialchars($moddesti['date_traitement']); ?>" required max="<?php echo date('Y-m-d'); ?>">
            </div>
            <div class="utilisateur">
                <label>Utilisateur :</label>
                <select name="utilisateur" required>
                    <option value="" disabled>Sélectionnez un utilisateur</option>
                    <?php
                    try {
                        $affichagerole = $conn->query("SELECT * FROM utilisateurs");
                        while ($datarecup = $affichagerole->fetch()) {
                            // Préselectionner l'utilisateur actuel
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
            <div class="dossier">
                <label>Dossier :</label>
                <select name="dossier" required>
                    <option value="" disabled>Sélectionnez un dossier</option>
                    <?php
                    try {
                        $affichagerole = $conn->query("SELECT * FROM dossiers");
                        while ($datarecup = $affichagerole->fetch()) {
                            // Préselectionner le dossier actuel
                            $selected = ($datarecup['id_dossier'] == $moddesti['id_dossier']) ? 'selected' : '';
                            echo "<option value='" . htmlspecialchars($datarecup['id_dossier'], ENT_QUOTES, 'UTF-8') . "' $selected>" . htmlspecialchars($datarecup['id_dossier'], ENT_QUOTES, 'UTF-8') . "</option>";
                        }
                        $affichagerole->closeCursor();
                    } catch (PDOException $e) {
                        echo "<option value=''>Erreur de chargement des dossiers</option>";
                    }
                    ?>
                </select>
            </div>
            <center><button type="submit" name="valider">Traiter</button></center>
        </form>
    </fieldset>
</body>
</html>