<?php
// include "connexion.php";
// include "menu.php";

// Récupération des traitements avec des jointures pour obtenir des informations supplémentaires
$affiche = $conn->query("
    SELECT tr.date_traitement, user.username, dos.numero_reference, tr.id_traitement 
    FROM traitements as tr 
    JOIN utilisateurs as user ON tr.id_utilisateur = user.id_utilisateur 
    JOIN dossiers as dos ON tr.id_dossier = dos.id_dossier
");

if (isset($_GET['sup'])) {
    try {
        $modefi = intval($_GET['sup']);
        $suppression = $conn->prepare("DELETE FROM traitements WHERE id_traitement = :id_traitement");
        $suppression->execute([':id_traitement' => $modefi]);
        echo "<script>alert('Traitement supprimé avec succès');</script>";
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
    <title>Affichage des Dossiers Traités</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f4f4f4;
        }
        h1 {
            color: blue;
        }
        table {
            width: 60%;
            border-collapse: collapse;
            margin: 20px auto; /* Centrer la table */
            background: white;
            text-align: center;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }
        th {
            background-color: #4CAF50;
            color: white;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        a {
            display: inline-block; 
            padding: 8px 12px;
            margin: 5px; 
            background-color: #3498db; 
            color: white;
            text-decoration: none; 
            border-radius: 4px; 
            transition: background-color 0.3s, transform 0.2s;
        }
        a:hover {
            background-color: blue; 
            transform: scale(1.05); 
        } 
        a:active {
            transform: scale(0.95); 
        }
        .message.error {
            color: red;
            margin-top: 20px;
        }
        .plus-button {
            display: inline-block;
            width: 40px;
            height: 40px;
            line-height: 40px;
            text-align: center;
            background-color: #28a745; /* vert */
            color: white;
            font-size: 24px;
            font-weight: bold;
            border-radius: 50%;
            text-decoration: none;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transition: background-color 0.3s ease, transform 0.2s ease;
        }
        .plus-button:hover {
            background-color: #218838;
            transform: scale(1.1);
        }
    </style>
</head>
<body>
    <center><h1>Affichage des Dossiers Traités</h1></center>
    <table>
        <thead>
            <tr>
                <th>Date de Traitement</th>
                <th>Traiteur</th>
                <th>Dossier Traité</th>
                <th colspan="2">Actions</th>
                <th><a href="traitement.php" class="plus-button">+</a></th>
            </tr>
        </thead>
        <tbody>
        <?php while ($destinateur = $affiche->fetch(PDO::FETCH_ASSOC)) { ?>
            <tr>
                <td><?php echo htmlspecialchars($destinateur["date_traitement"]); ?></td>
                <td><?php echo htmlspecialchars($destinateur['username']); ?></td>
                <td><?php echo htmlspecialchars($destinateur["numero_reference"]); ?></td>
                <td>
                    <a href="#" onclick="confirmDelete(<?php echo $destinateur["id_traitement"]; ?>)">Supprimer</a>
                    <a href="modifie_traitement.php?mod=<?php echo $destinateur["id_traitement"]; ?>">Modifier</a>
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>

    <script>
    function confirmDelete(traitementId) {
        const confirmation = confirm("Êtes-vous sûr de vouloir supprimer ce traitement ?");
        if (confirmation) {
            window.location.href = "affichage_traitement.php?sup=" + traitementId;
        }
    }
    </script>
</body>
</html>