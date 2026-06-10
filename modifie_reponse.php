<?php
include "connexion.php";

// Correction de la requête SQL
$desti = $conn->query("SELECT res.id_reponse, res.date_reponse, res.reponse, res.id_utilisateur, res.id_destinateur, res.id_dossier 
    FROM reponse AS res 
    JOIN utilisateurs AS user ON res.id_utilisateur = user.id_utilisateur 
    JOIN destinateurs AS des ON res.id_destinateur = des.id_destinateur 
    JOIN dossiers AS dos ON res.id_dossier = dos.id_dossier")->fetchAll(PDO::FETCH_ASSOC);

if (isset($_GET['mod'])) {
    $modifie = intval($_GET['mod']);
    $modificationEnCours = true;

    // Préparation de la requête pour récupérer la réponse à modifier
    $modification = $conn->prepare("SELECT * FROM reponse WHERE id_reponse = :id_reponse");
    $modification->bindParam(':id_reponse', $modifie, PDO::PARAM_INT);
    $modification->execute();
    $moddesti = $modification->fetch(PDO::FETCH_ASSOC);

    if ($moddesti) { 
        if (isset($_POST['valider'])) {
            $recupedat = $_POST['date_reponse'];
            $recupose = $_POST['reponse'];
            $recupedata = $_POST['utilisateur'];
            $recupedataa = $_POST['destinateur'];
            $recupedataaa = $_POST['dossier'];

            // Préparation de la mise à jour
            $modfie = $conn->prepare("UPDATE reponse SET date_reponse = :date_reponse, reponse = :reponse, id_utilisateur = :id_utilisateur, id_destinateur = :destinateur, id_dossier = :id_dossier WHERE id_reponse = :id_reponse");

            $modfie->bindParam(':date_reponse', $recupedat);
            $modfie->bindParam(':reponse', $recupose);
            $modfie->bindParam(':id_utilisateur', $recupedata);
            $modfie->bindParam(':destinateur', $recupedataa);
            $modfie->bindParam(':id_dossier', $recupedataaa);
            $modfie->bindParam(':id_reponse', $modifie, PDO::PARAM_INT);

            // Exécution de la mise à jour
            if ($modfie->execute()) {
                echo "<script>alert('Modification réussie');</script>";
                header('Location: reponse.php');
                exit();
            } else {
                echo "<script>alert('Erreur de modification');</script>";
            }
        }
    } else {
        echo "<script>alert('Commentaire introuvable.');</script>";
    }
} else {
    echo "<script>alert('Aucune ID de reponse spécifiée.');</script>";
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réponse</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: rgb(5, 45, 45);
            margin: 0;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        fieldset {
            background: white;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(7, 110, 52, 0.76);
            width: 400px;
            margin: 2px;
        }
        legend {
            font-size: 1.5em;
            color: #333;
        }
        div {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            color: #555;
        }
        input[type="date"],
        select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 1em;
        }
        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1em;
            transition: background-color 0.3s;
            width: 100%;
        }
        button:hover {
            background-color: #45a049;
        }
        .error {
            color: red;
            margin-top: 10px;
        }
    </style>
</head>
<body>
 
    <fieldset>
        
        <form action="" method="POST">
            <div>
                <label for="date_reponse">Date de réponse :</label>
                <input type="date" name="date_reponse" value="<?php echo htmlspecialchars($moddesti['date_reponse']); ?>" required max="<?php echo date('Y-m-d'); ?>">
            </div>
            <div>
                <label for="reponse">réponse :</label>
                <input type="text" name="reponse" value="<?php echo htmlspecialchars($moddesti['reponse']); ?>" required>
            </div>
            <div>
                <label for="utilisateur">Utilisateur :</label>
                <select name="utilisateur" required>
                    <option value="" disabled selected>Sélectionnez un utilisateur</option>
                    <?php
                    include "connexion.php";
                    try {
                        $affichagerole = $conn->query("SELECT * FROM utilisateurs");
                        while ($datarecup = $affichagerole->fetch()) {
                            echo "<option value='" . htmlspecialchars($datarecup['id_utilisateur'], ENT_QUOTES, 'UTF-8') . "'" . ($datarecup['id_utilisateur'] == $moddesti['id_utilisateur'] ? ' selected' : '') . ">" . htmlspecialchars($datarecup['username'], ENT_QUOTES, 'UTF-8') . "</option>";
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
                        $affichagerole = $conn->query("SELECT * FROM destinateurs");
                        while ($datarecup = $affichagerole->fetch()) {
                            echo "<option value='" . htmlspecialchars($datarecup['id_destinateur'], ENT_QUOTES, 'UTF-8') . "'" . ($datarecup['id_destinateur'] == $moddesti['id_destinateur'] ? ' selected' : '') . ">" . htmlspecialchars($datarecup['nom_destinateur'], ENT_QUOTES, 'UTF-8') . "</option>";
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
                        $affichagerole = $conn->query("SELECT * FROM dossiers");
                        while ($datarecup = $affichagerole->fetch()) {
                            echo "<option value='" . htmlspecialchars($datarecup['id_dossier'], ENT_QUOTES, 'UTF-8') . "'" . ($datarecup['id_dossier'] == $moddesti['id_dossier'] ? ' selected' : '') . ">" . htmlspecialchars($datarecup['id_dossier'], ENT_QUOTES, 'UTF-8') . "</option>";
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
</body>
</html>