<?php
session_start();
if (!isset($_SESSION['connecté'])) {
    header('Location: index.php');
    exit();
}
$roles = explode(',', $_SESSION['roles']);
if (!array_intersect(['secretaire_executif', 'secretaire', 'secretaire_interim'], $roles)) {
    // Gérer l'accès non autorisé ici
}
include "connexion.php";

$desti = $conn->query("SELECT id_dossier, nom, numero_reference, date_envoi, date_reception, id_destinateur, objet, id_utilisateur, fichier FROM dossiers")->fetchAll(PDO::FETCH_ASSOC);

$modificationEnCours = false;
$moddesti = null;

if (isset($_GET['mod'])) {
    $modifie = intval($_GET['mod']);
    $modificationEnCours = true;

    $modification = $conn->prepare("SELECT * FROM dossiers WHERE id_dossier = :id_dossier");
    $modification->bindParam(':id_dossier', $modifie, PDO::PARAM_INT);
    $modification->execute();
    $moddesti = $modification->fetch(PDO::FETCH_ASSOC);

    if (isset($_POST['valider'])) {
        $nom = $_POST['nom'];
        $recupernum2 = $_POST['numero_reference'];
        $recupedata1 = $_POST['date_envoi'];
        $recupedata2 = $_POST['date_reception'];
        $recupenomexpe = $_POST['destinateur'];  // Correct ici
        $recupobjet = $_POST['objet'];
        $fichier = $_FILES['fichier']['name'] ?? $moddesti['fichier']; // gérer upload fichier si besoin
        $recuser = $_POST['utilisateur'];  // Correction ici : récupérer 'utilisateur'

        // Vérification du doublon de numero_reference, en excluant le dossier actuel
        $checkReference = $conn->prepare("SELECT COUNT(*) FROM dossiers WHERE numero_reference = :numero_reference AND id_dossier != :id_dossier");
        $checkReference->bindParam(':numero_reference', $recupernum2);
        $checkReference->bindParam(':id_dossier', $modifie, PDO::PARAM_INT);
        $checkReference->execute();
        $referenceExists = $checkReference->fetchColumn();

        if ($referenceExists > 0) {
            echo "<script>alert('Erreur: Ce numéro de référence est déjà utilisé.');</script>";
        } else {
            // Mise à jour du dossier
            $modfie = $conn->prepare("
                UPDATE dossiers SET 
                    nom = :nom,
                    numero_reference = :numero_reference, 
                    date_envoi = :date_envoi, 
                    date_reception = :date_reception, 
                    id_destinateur = :id_destinateur, 
                    objet = :objet,
                    id_utilisateur = :utilisateur,
                    fichier = :fichier 
                WHERE id_dossier = :id_dossier
            ");
            $modfie->bindParam(':nom', $nom);
            $modfie->bindParam(':numero_reference', $recupernum2);
            $modfie->bindParam(':date_envoi', $recupedata1);
            $modfie->bindParam(':date_reception', $recupedata2);
            $modfie->bindParam(':id_destinateur', $recupenomexpe);
            $modfie->bindParam(':objet', $recupobjet);
            $modfie->bindParam(':utilisateur', $recuser);
            $modfie->bindParam(':fichier', $fichier);
            $modfie->bindParam(':id_dossier', $modifie, PDO::PARAM_INT);

            if ($modfie->execute()) {
                echo "<script>alert('Modification réussie');</script>";
                header('Location: dossier.php');
                exit();
            } else {
                echo "<script>alert('Erreur de modification');</script>";
            }
        }
    }
} else {
    echo "<script>alert('Aucune ID du dossier spécifiée.');</script>";
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Dossiers reçus</title>
    <style>
                body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 600px;
            margin: auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        fieldset {
            border: none;
        }
        legend {
            font-size: 1.5em;
            margin-bottom: 10px;
            color: blue;
        }
        label {
            display: block;
            margin: 10px 0 5px;
            text-align: left;
            color:blue;
        }
        input[type="text"],
        input[type="date"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            margin-bottom: 15px;
        }
        button {
            padding: 10px 20px;
            border: none;
            background-color: #007BFF;
            color: white;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1em;
        }
        button:hover {
            background-color: #0056b3;
        }
        @media (max-width: 600px) {
            button {
                width: 100%;
            }
        }
    </style>
</head>
<body>
<div class="container">
    <form action="" method="POST" enctype="multipart/form-data">
        <fieldset>
            <legend>Dossiers reçus :</legend>
            <center>
                <label for="nom_S">Nom du dossier :</label>
                <input type="text" name="nom" placeholder="taper le nom du dossier" required value="<?php echo htmlspecialchars($moddesti['nom'] ?? '', ENT_QUOTES); ?>" />

                <label for="utilisateur">Utilisateur :</label>
                <select name="utilisateur" required>
                    <option value="" disabled <?php echo empty($moddesti['id_utilisateur']) ? 'selected' : ''; ?>>Sélectionnez un utilisateur</option>
                    <?php
                    try {
                        $affichagerole = $conn->query("SELECT * FROM utilisateurs WHERE role = 'secretaire'");
                        while ($datarecup = $affichagerole->fetch()) {
                            $selected = ($datarecup['id_utilisateur'] == ($moddesti['id_utilisateur'] ?? '')) ? 'selected' : '';
                            echo "<option value='" . htmlspecialchars($datarecup['id_utilisateur'], ENT_QUOTES) . "' $selected>" . htmlspecialchars($datarecup['username'], ENT_QUOTES) . "</option>";
                        }
                        $affichagerole->closeCursor();
                    } catch (PDOException $e) {
                        echo "<option value=''>Erreur de chargement des utilisateurs</option>";
                    }
                    ?>
                </select>

                <label for="numero_reference">Numéro de référence :</label>
                <input type="text" name="numero_reference" placeholder="Entrez votre numéro de référence" value="<?php echo htmlspecialchars($moddesti['numero_reference'] ?? '', ENT_QUOTES); ?>" required />

                <label for="date_envoi">Date d'envoi :</label>
                <input type="date" name="date_envoi" value="<?php echo htmlspecialchars($moddesti['date_envoi'] ?? '', ENT_QUOTES); ?>" required max="<?php echo date('Y-m-d'); ?>" />

                <label for="date_reception">Date de réception :</label>
                <input type="date" name="date_reception" value="<?php echo htmlspecialchars($moddesti['date_reception'] ?? '', ENT_QUOTES); ?>" required max="<?php echo date('Y-m-d'); ?>" />

                <label for="destinateur">Expéditeur :</label>
                <select name="destinateur" required>
                    <option value="" disabled <?php echo empty($moddesti['id_destinateur']) ? 'selected' : ''; ?>>Sélectionnez un destinateur</option>
                    <?php
                    try {
                        $stmt = $conn->query("SELECT id_destinateur, nom_destinateur FROM destinateurs");
                        while ($row = $stmt->fetch()) {
                            $selected = ($row['id_destinateur'] == ($moddesti['id_destinateur'] ?? '')) ? 'selected' : '';
                            echo "<option value='" . htmlspecialchars($row['id_destinateur'], ENT_QUOTES) . "' $selected>" . htmlspecialchars($row['nom_destinateur'], ENT_QUOTES) . "</option>";
                        }
                        $stmt->closeCursor();
                    } catch (PDOException $e) {
                        echo "<option value=''>Erreur de chargement</option>";
                    }
                    ?>
                </select>

                <label for="objet">Objet :</label>
                <input type="text" name="objet" placeholder="Entrez l'objet du dossier" value="<?php echo htmlspecialchars($moddesti['objet'] ?? '', ENT_QUOTES); ?>" required />

                <label for="fichier_dos">Fichier du dossier :</label>
                <input type="file" name="fichier" />

                <button type="submit" name="valider">Enregistrer</button>
            </center>
        </fieldset>
    </form>
</div>
</body>
</html>
