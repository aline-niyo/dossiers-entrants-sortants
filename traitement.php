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

// Traitement du formulaire
if (isset($_POST['valider'])) {
    $recupedate   = $_POST['date_traitement'];
    $recuperuser  = $_POST['utilisateur'];
    $recupedossi  = $_POST['dossier'];

    // Insertion des données dans la base
    $insertdata = $conn->prepare("INSERT INTO traitements(date_traitement, id_utilisateur, id_dossier) VALUES(:date_traitement, :utilisateur, :dossier)");
    $insertdata->bindParam(':date_traitement', $recupedate);
    $insertdata->bindParam(':utilisateur',   $recuperuser);
    $insertdata->bindParam(':dossier',       $recupedossi);

    if ($insertdata->execute()) {
        echo "<script>alert('Traitement réussi');</script>";
        echo "<script>window.location.href='traitement.php';</script>";
        exit();
    } else {
        echo "<script>alert('Échec du traitement');</script>";
        exit();
    }
}

// Affichage des traitements
$affiche = $conn->query("
    SELECT tr.date_traitement, user.username, dos.nom, tr.id_traitement 
    FROM traitements AS tr
    JOIN utilisateurs AS user ON tr.id_utilisateur = user.id_utilisateur
    JOIN dossiers AS dos ON tr.id_dossier = dos.id_dossier
    ORDER BY tr.date_traitement DESC, tr.id_traitement DESC
");

// Suppression d'un traitement
if (isset($_GET['sup'])) {
    try {
        $modefi = intval($_GET['sup']);
        $suppression = $conn->prepare("DELETE FROM traitements WHERE id_traitement = :id_traitement");
        $suppression->execute([':id_traitement' => $modefi]);
        echo "<script>alert('Traitement supprimé avec succès');</script>";
        echo "<script>window.location.href='traitement.php';</script>";
        exit();
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
    <title>Traitement</title>
    <style>
        /* Style de base */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 10px;
            color: #333;
        }

        /* Formulaire de traitement */
        fieldset {
            background: #fff;
            padding: 20px;
            margin: 0 auto 20px;
            max-width: 800px;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.05);
        }

        legend {
            font-size: 1.4em;
            font-weight: bold;
            color:hsl(210, 87.80%, 38.60%);
            padding: 0 10px;
            text-align: center;
        }

        form {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }

        form label {
            flex: 1 1 100%;
            font-weight: 600;
        }

        form input[type="date"],
        form select,
        form button {
            flex: 1 1 100%;
            padding: 14px;
            font-size: 1em;
            border: 1px solid #ccc;
            border-radius: 6px;
            box-sizing: border-box;
        }

        form button {
            background-color: #27ae60;
            color: #fff;
            border: none;
            cursor: pointer;
            transition: background 0.3s;
        }

        form button:hover {
            background-color: #219150;
        }

        h1 {
            text-align: center;
            margin: 20px 0;
            font-size: 1.6em;
            color: blue;
        }

        label{
            color: blue;
        }

        /* Tableau des traitements */
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 1em;
        }

        th, td {
            padding: 16px;
            border: 1px solid #ddd;
            text-align: left;
        }

        th {
            background-color: #ecf0f1;
            font-weight: 600;
            color: blue;
        }

        tr:nth-child(even) {
            background-color: #fafafa;
        }

        a {
            text-decoration: none;
            font-weight: 500;
        }

        a:hover {
            text-decoration: underline;
        }

        /* Mode responsive */
        @media (max-width: 768px) {
            table, thead, tbody, th, td, tr {
                display: block;
            }
            thead {
                display: none;
            }
            tr {
                margin-bottom: 20px;
                border: 1px solid #ddd;
                border-radius: 6px;
                padding: 10px;
                background: #fff;
            }
            td {
                position: relative;
                padding-left: 50%;
                text-align: right;
                border: none;
                border-bottom: 1px solid #eee;
            }
            td::before {
                content: attr(data-label);
                position: absolute;
                left: 16px;
                top: 16px;
                font-weight: 600;
                text-align: left;
            }
            td:nth-of-type(1)::before { content: "Date"; }
            td:nth-of-type(2)::before { content: "Traiteur"; }
            td:nth-of-type(3)::before { content: "Dossier"; }
            td:nth-of-type(4)::before { content: "Actions"; }
        }
    </style>
</head>
<body>

    <fieldset>
        <legend>Traitement</legend>
        <form action="" method="POST">
            <label for="date">Date de traitement :</label>
            <input type="date" name="date_traitement" required value="<?php echo date('Y-m-d'); ?>" max="<?php echo date('Y-m-d'); ?>" min="<?php echo date('Y-m-d'); ?>">

            <label for="utilisateur">Utilisateur :</label>
            <select name="utilisateur" required>
                <option value="" disabled selected>Sélectionnez un utilisateur</option>
                <?php
                try {
                    $affichagerole = $conn->query("SELECT * FROM utilisateurs");
                    while ($datarecup = $affichagerole->fetch()) {
                        echo "<option value='" . htmlspecialchars($datarecup['id_utilisateur'], ENT_QUOTES, 'UTF-8') . "'>" . htmlspecialchars($datarecup['username'], ENT_QUOTES, 'UTF-8') . "</option>";
                    }
                    $affichagerole->closeCursor();
                } catch (PDOException $e) {
                    echo "<option value=''>Erreur de chargement des utilisateurs</option>";
                }
                ?>
            </select>

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

            <button type="submit" name="valider">Traiter</button>
        </form>
    </fieldset>

    <h1>Affichage des Dossiers Traités</h1>
    <table>
        <thead>
            <tr>
                <th>Date de Traitement</th>
                <th>Traiteur</th>
                <th>Dossier Traité</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php while ($destinateur = $affiche->fetch(PDO::FETCH_ASSOC)) { ?>
            <tr>
                <td><?php echo htmlspecialchars($destinateur["date_traitement"]); ?></td>
                <td><?php echo htmlspecialchars($destinateur['username']); ?></td>
                <td><?php echo htmlspecialchars($destinateur["nom"]); ?></td>
                <td>
                    <!-- Lien pour modifier -->
                    <a href="modifie_traitement.php?mod=<?php echo $destinateur["id_traitement"]; ?>">Modifier</a> |
                    <!-- Lien pour voir -->
                    <a href="trater_fichier.php?traiter=<?php echo $destinateur["id_traitement"]; ?>">Voir</a>
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>

    <script>
    function confirmDelete(traitementId) {
        const confirmation = confirm("Êtes-vous sûr de vouloir supprimer ce traitement ?");
        if (confirmation) {
            window.location.href = "traitement.php?sup=" + traitementId;
        }
    }
    </script>

</body>
</html>
