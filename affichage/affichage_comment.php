<?php
include "connexion.php";
include "menu.php";

// Fetch comments with associated user and dossier information
$affichageUtilisateur = $conn->query("
    SELECT 
        com.id_commentaire, 
        com.date_commentaire, 
        com.commentaire, 
        dos.numero_reference AS dossier, 
        user.username AS utilisateur 
    FROM 
        commentaires AS com 
    JOIN 
        utilisateurs AS user ON com.id_utilisateur = user.id_utilisateur 
    JOIN 
        dossiers AS dos ON com.id_dossier = dos.id_dossier
");

// Handle deletion of comments
if (isset($_GET["sup"])) {
    try {
        $commentId = intval($_GET['sup']); // Ensure it's an integer
        $suppression = $conn->prepare("DELETE FROM commentaires WHERE id_commentaire = :id_commentaire");
        $suppression->bindParam(':id_commentaire', $commentId, PDO::PARAM_INT);
        $suppression->execute();
        echo "<script>alert('Commentaire supprimé avec succès.');</script>";
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
    <title>Liste des Commentaires</title>
    <style>
        /* Reset & Base */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            background-color: #f4f6f8;
            color: #333;
        }

        .container {
            max-width: 1000px;
            margin: 40px auto;
            padding: 20px;
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        /* Title */
        h1 {
            text-align: center;
            margin-bottom: 30px;
            font-size: 2rem;
            color: #2c3e50;
        }

        /* Add button */
        .add-button-wrapper {
            text-align: right;
            margin-bottom: 15px;
        }

        .plus-button {
            display: inline-block;
            width: 45px;
            height: 45px;
            line-height: 45px;
            background-color: #3498db;
            color: #fff;
            font-size: 28px;
            font-weight: bold;
            border-radius: 50%;
            text-align: center;
            text-decoration: none;
            transition: background-color 0.3s ease, transform 0.2s ease;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
        }

        .plus-button:hover {
            background-color: #2980b9;
            transform: scale(1.1);
        }

        /* Table Styles */
        table {
            width: 100%;
            border-collapse: collapse;
            border-spacing: 0;
        }

        th, td {
            padding: 12px 15px;
            text-align: left;
        }

        thead {
            background-color: #2c3e50;
            color: #fff;
        }

        tbody tr:nth-child(odd) {
            background-color: #f9f9f9;
        }

        tbody tr:hover {
            background-color: #e8f4ff;
        }

        /* Action buttons */
        .action-btn {
            padding: 6px 12px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 500;
            transition: background 0.3s ease;
        }

        .action-btn.delete {
            background-color: #e74c3c;
            color: #fff;
        }

        .action-btn.delete:hover {
            background-color: #c0392b;
        }

        .action-btn.edit {
            background-color: #f39c12;
            color: #fff;
        }

        .action-btn.edit:hover {
            background-color: #d68910;
        }

        /* Responsive */
        @media (max-width: 768px) {
            table, thead, tbody, th, td, tr {
                display: block;
            }

            thead tr {
                display: none;
            }

            tbody tr {
                margin-bottom: 15px;
                background-color: #fff;
                box-shadow: 0 2px 8px rgba(0,0,0,0.05);
                border-radius: 8px;
                padding: 10px;
            }

            td {
                position: relative;
                padding-left: 50%;
            }

            td::before {
                content: attr(data-label);
                position: absolute;
                left: 15px;
                font-weight: bold;
                color: #555;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Liste des Commentaires</h1>
        <table>
            <thead>
                <tr>
                    <th>Date Commentaire</th>
                    <th>Commentaire</th>
                    <th>Dossier (Numéro de Référence)</th>
                    <th>Utilisateur (Nom d'utilisateur)</th>
                    <th colspan="2">Actions</th>
                    <th><a href="comment.php" class="plus-button">+</a></th> 
                </tr>
            </thead>
            <tbody>
                <?php while ($destinateur = $affichageUtilisateur->fetch(PDO::FETCH_ASSOC)) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($destinateur["date_commentaire"]); ?></td>
                        <td><?php echo htmlspecialchars($destinateur["commentaire"]); ?></td>
                        <td><?php echo htmlspecialchars($destinateur["dossier"]); ?></td>
                        <td><?php echo htmlspecialchars($destinateur["utilisateur"]); ?></td>
                        <td>
                            <a href="#" onclick="confirmDelete(<?php echo $destinateur['id_commentaire']; ?>)" class="action-btn delete">Supprimer</a>
                        </td>
                        <td>
                            <a href="modifie_comment.php?mod=<?php echo $destinateur["id_commentaire"]; ?>" class="action-btn edit">Modifier</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <script>
    function confirmDelete(commentId) {
        const confirmation = confirm("Êtes-vous sûr de vouloir supprimer ce commentaire ?");
        if (confirmation) {
            window.location.href = "affichage_comment.php?sup=" + commentId;
        }
    }
    </script>
</body>
</html>