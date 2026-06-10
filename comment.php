<?php
session_start();
if (!isset($_SESSION['connecté'])) {
    header('Location: index.php');
    exit();
}
$roles = explode(',', $_SESSION['roles']);
if (!in_array('secretaire_executif', $roles) && !in_array('secretaire', $roles) && !in_array('secretaire_interim', $roles)) {
    
}

include "connexion.php";

if (isset($_POST['valider'])) {
    $recuperadate = $_POST['date_commentaire'];
    $recupecoment = $_POST['commentaire'];
    $reuser = $_POST['utilisateur'];
    $recuperadoss = $_POST['dossier'];

    $insertdata = $conn->prepare("
        INSERT INTO commentaires (date_commentaire, commentaire, id_utilisateur, id_dossier)
        VALUES (:date_commentaire, :commentaire, :utilisateur, :dossier)
    ");

    $insertdata->bindParam(':date_commentaire', $recuperadate);
    $insertdata->bindParam(':commentaire', $recupecoment);
    $insertdata->bindParam(':utilisateur', $reuser);
    $insertdata->bindParam(':dossier', $recuperadoss);

    if ($insertdata->execute()) {
        echo "<script>alert('Commentaire ajouté avec succès');</script>";
        echo "<script>window.location.href='comment.php';</script>";
        exit();
    } else {
        echo "<script>alert('Pas de commentaire ajouté');</script>";
    }
}
if (isset($_GET["sup"])) {
    try {
        $commentId = intval($_GET['sup']);
        $suppression = $conn->prepare("DELETE FROM commentaires WHERE id_commentaire = :id_commentaire");
        $suppression->bindParam(':id_commentaire', $commentId, PDO::PARAM_INT);
        $suppression->execute();
        echo "<script>alert('Commentaire supprimé avec succès.'); window.location.href='affichage_comment.php';</script>";
        exit();
    } catch (PDOException $e) {
        echo '<div class="message error">Erreur lors de la suppression : ' . htmlspecialchars($e->getMessage()) . '</div>';
    }
}


$affichageUtilisateur = $conn->query("
    SELECT 
        com.id_commentaire, 
        com.date_commentaire, 
        com.commentaire, 
        com.id_dossier, -- <= AJOUTE CETTE LIGNE
        dos.nom AS dossier, 
        user.username AS utilisateur 
    FROM 
        commentaires AS com 
    LEFT JOIN 
        utilisateurs AS user ON com.id_utilisateur = user.id_utilisateur 
    LEFT JOIN 
        dossiers AS dos ON com.id_dossier = dos.id_dossier
    ORDER BY com.date_commentaire DESC, com.id_commentaire DESC
");

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Commentaire</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f4f6f9;
            margin: 0;
            padding: 0;
        }

        h1,p,a {
            margin-top: 20px;
            color: #333;
            text-align: center;
        }

        form {
            background: #fff;
            padding: 20px;
            margin: 20px auto;
            max-width: 600px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        form label {
            display: block;
            margin-bottom: 6px;
            font-weight: bold;
        }

        form input[type="text"],
        form input[type="date"],
        form select {
            width: 100%;
            padding: 10px;
            margin-bottom: 16px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 14px;
        }

        form button {
            background: #007bff;
            color: #fff;
            border: none;
            padding: 10px 24px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
            transition: background 0.3s ease;
        }

        form button:hover {
            background: #0056b3;
        }

        .container {
            background: #fff;
            padding: 20px;
            margin: 30px auto;
            max-width: 95%;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        .container p a {
            color: #007bff;
            text-decoration: none;
        }

        .container p a:hover {
            text-decoration: underline;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table thead {
            background: #007bff;
            color: white;
        }

        table th,
        table td {
            padding: 12px 15px;
            border: 1px solid #ddd;
            text-align: center;
        }

        table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        table i.fa-folder {
            margin-right: 6px;
        }

        /* Boutons d'action uniformes - tous bleus */
        .action-btn {
            display: inline-block;
            padding: 8px 12px;
            margin: 2px;
            border-radius: 6px;
            font-size: 14px;
            font-weight: bold;
            text-decoration: none;
            color: #fff;
            background-color: #007bff;
            transition: background-color 0.3s ease;
        }

        .action-btn:hover {
            background-color: #0056b3;
        }

        /* Responsive */
        @media (max-width: 768px) {
            form, .container {
                width: 95%;
            }

            table, thead, tbody, th, td, tr {
                display: block;
            }

            table thead {
                display: none;
            }

            table tr {
                margin-bottom: 15px;
                background: #fff;
                border-radius: 8px;
                box-shadow: 0 2px 6px rgba(0,0,0,0.1);
            }

            table td {
                text-align: right;
                padding-left: 50%;
                position: relative;
            }

            table td::before {
                content: attr(data-label);
                position: absolute;
                left: 10px;
                width: 45%;
                padding-left: 10px;
                font-weight: bold;
                text-align: left;
            }
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

<center><h1>Commentaire sur Dossier</h1></center>

<form method="POST" action="">
    <div>
        <label>Date Commentaire :</label>
        <input type="date" name="date_commentaire" required value="<?php echo date('Y-m-d'); ?>" max="<?php echo date('Y-m-d'); ?>" min="<?php echo date('Y-m-d'); ?>"><br>

        <label>Commentaire :</label>
        <input type="text" name="commentaire" required><br>
    </div>

    <div class="utilisateur">
        <label>Utilisateur :</label>
        <select name="utilisateur" required>
            <option value="" disabled selected>Sélectionnez un utilisateur</option>
            <?php
            try {
                $users = $conn->query("SELECT id_utilisateur, username FROM utilisateurs WHERE role = 'secretaire_executif' OR role= 'secretaire_interim'");
                while ($user = $users->fetch(PDO::FETCH_ASSOC)) {
                    echo "<option value='" . htmlspecialchars($user['id_utilisateur']) . "'>" . htmlspecialchars($user['username']) . "</option>";
                }
                $users->closeCursor();
            } catch (PDOException $e) {
                echo "<option value=''>Erreur de chargement</option>";
            }
            ?>
        </select>
    </div>

    <div class="dossier">
        <label>Dossier :</label>
        <select name="dossier" required>
            <option value="" disabled selected>Sélectionnez un dossier</option>
            <?php
            try {
                $dossiers = $conn->query("SELECT id_dossier, nom FROM dossiers");
                while ($dossier = $dossiers->fetch(PDO::FETCH_ASSOC)) {
                    echo "<option value='" . htmlspecialchars($dossier['id_dossier']) . "'>" . htmlspecialchars($dossier['nom']) . "</option>";
                }
                $dossiers->closeCursor();
            } catch (PDOException $e) {
                echo "<option value=''>Erreur de chargement</option>";
            }
            ?>
        </select>
    </div>

    <center><button type="submit" name="valider">Commenter</button></center>
</form>

<div class="container">
    <h1>Liste des Commentaires</h1>
    <p>Voulez-vous voir des commentaires archivés ? <a href="Archivage/affichage_comment.php">Visiter</a></p>
    <table>
        <thead>
            <tr>
                <th>Date Commentaire</th>
                <th>Commentaire</th>
                <th>Dossier</th>
                <th>Utilisateur</th>
                <th colspan="2">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($commentaire = $affichageUtilisateur->fetch(PDO::FETCH_ASSOC)) { ?>
                <tr>
                    <td><?= htmlspecialchars($commentaire["date_commentaire"]); ?></td>
                    <td><?= htmlspecialchars($commentaire["commentaire"]); ?></td>
                    <td><i class="fa-solid fa-folder" style="color: #f0ad4e;"></i><?= htmlspecialchars($commentaire["dossier"]); ?></td>
                    <td><?= htmlspecialchars($commentaire["utilisateur"]); ?></td>
                    <td>
                        <a href="modifie_comment.php?mod=<?= $commentaire['id_commentaire']; ?>" class="action-btn edit">
                            <i class="fa fa-pen-to-square"></i> Modifier
                        </a>
                    </td>
                    <td>
                        <a href="Archivage/archivage_comment.php?mod=<?= $commentaire['id_commentaire']; ?>" class="action-btn archive">
                            <i class="fa fa-box-archive"></i> Archiver
                        </a>
                    </td>
                    <td>
                        <a href="comment_fichiers.php?dossier_id=<?= $commentaire['id_dossier']; ?>" class="action-btn view">
                            <i class="fa fa-eye"></i> Voir
                        </a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<script>
function confirmDelete(commentId) {
    if (confirm("Êtes-vous sûr de vouloir supprimer ce commentaire ?")) {
        window.location.href = "affichage_comment.php?sup=" + commentId;
    }
}
</script>

</body>
</html>