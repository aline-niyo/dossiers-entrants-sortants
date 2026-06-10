<?php
include "connexion.php";
include "menu.php";

// Récupération des données
$affichageUtilisateur = $conn->query("
    SELECT val.id_validation, val.date_validation, user.username, dos.numero_reference, res.date_reponse 
    FROM validation as val 
    JOIN utilisateurs as user ON val.id_utilisateur = user.id_utilisateur 
    JOIN dossiers as dos ON val.id_dossier = dos.id_dossier 
    JOIN reponse as res ON val.id_reponse = res.id_reponse
");

// Suppression d'une validation
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
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Affichage des validations</title>
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
    <h1>Fichiers des dossiers validés</h1>
    <form action="" method="GET">
        <table>
            <thead>
                <tr>
                    <th>Date de validation</th>
                    <th>Utilisateur</th>
                    <th>Dossier</th>
                    <th>Réponse</th>
                    <th colspan="2">Actions</th>
                    <th><a href="validation.php" class="add-button">+</a></th>
                </tr>
            </thead>
            <tbody>
            <?php while ($destinateur = $affichageUtilisateur->fetch(PDO::FETCH_ASSOC)) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($destinateur["date_validation"]); ?></td>
                    <td><?php echo htmlspecialchars($destinateur['username']); ?></td>
                    <td><?php echo htmlspecialchars($destinateur["numero_reference"]); ?></td>
                    <td><?php echo htmlspecialchars($destinateur['date_reponse']); ?></td>
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