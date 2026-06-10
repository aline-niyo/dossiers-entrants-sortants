<?php
include "../connexion.php"; 


if (isset($_GET["sup"])) {
    try {
        $id_utilisateur = intval($_GET["sup"]); 
        $suppression = $conn->prepare("DELETE FROM utilisateurs WHERE id_utilisateur = ?");
        $suppression->execute([$id_utilisateur]);
        echo "<script>alert('Utilisateur supprimé avec succès !');</script>";
    } catch (PDOException $e) {
        echo '<div class="message error">Erreur lors de la suppression : ' . htmlspecialchars($e->getMessage()) . '</div>';
    }
}

$searchTerm = '';
if (isset($_GET['search'])) {
    $searchTerm = htmlspecialchars($_GET['search']);
    $affichageUtilisateur = $conn->prepare("SELECT id_utilisateur, nom, prenom, email, telephone, username, role 
        FROM utilisateurs 
        WHERE nom LIKE :searchTerm OR prenom LIKE :searchTerm");
    $affichageUtilisateur->execute([':searchTerm' => '%' . $searchTerm . '%']);
} else {
    $affichageUtilisateur = $conn->query("SELECT id_utilisateur, nom, prenom, email, telephone, username, role FROM utilisateurs");
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Utilisateurs</title>
    <!-- <link rel="stylesheet" href="style.css"> -->
    
    <style>
              body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 900px;
            margin: auto;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }

        .search-container {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }

        .search-container input {
            padding: 10px;
            width: 300px;
            border: 1px solid #007BFF;
            border-radius: 5px;
        }

        .search-container button {
            padding: 10px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-left: 5px;
            transition: background-color 0.3s ease;
        }

        .search-container button:hover {
            background-color: #0056b3;
        }

        table {
            width: 30%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #dee2e6;
        }

        th {
            background-color: #007BFF;
            color: white;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        .btn-supprimer,
        .btn-modifier {
            padding: 5px 10px;
            border-radius: 5px;
            text-decoration: none;
            color: white;
        }

        .btn-supprimer {
            background-color:rgb(43, 14, 147);
        }

        .btn-supprimer:hover {
            background-color:rgb(35, 140, 21);
        }

        .btn-modifier {
            background-color:rgb(57, 7, 255);
        }

        .btn-modifier:hover {
            background-color: #e0a800;
        }

        .plus-button {
            display: inline-block;
            width: 40px;
            height: 40px;
            line-height: 40px;
            text-align: center;
            background-color: #28a745;
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
<div class="container">
    <h1>Liste des Utilisateurs</h1>
    <form action="" method="GET" class="search-container">
        <input type="text" name="search" placeholder="Rechercher un utilisateur..." value="<?php echo htmlspecialchars($searchTerm); ?>">
        <button type="submit">Chercher</button>
    </form>
    

    <table>
        <thead>
            <tr>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Email</th>
                <th>Téléphone</th>
                <th>Nom d'utilisateur</th>
                <!-- <th>Mot de passe</th> -->
                <th>Rôle</th>
                <!-- <th>Actions</th> -->
                <!-- <th><a href="utilisateur.php" class="plus-button">+</a></th> -->
            </tr>
        </thead>
        <tbody>
            <?php while ($utilisateur = $affichageUtilisateur->fetch(PDO::FETCH_ASSOC)) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($utilisateur["nom"]); ?></td>
                    <td><?php echo htmlspecialchars($utilisateur["prenom"]); ?></td>
                    <td><?php echo htmlspecialchars($utilisateur["email"]); ?></td>
                    <td><?php echo htmlspecialchars($utilisateur["telephone"]); ?></td>
                    <td><?php echo htmlspecialchars($utilisateur["username"]); ?></td>
                    <!-- <td><?php //echo htmlspecialchars($utilisateur["password"]); ?></td> -->
                    <td><?php echo htmlspecialchars($utilisateur["role"]); ?></td>
                    <!-- <td>
                        <a href="affichage_utilisateur.php?sup=<?php echo $utilisateur["id_utilisateur"]; ?>" class="btn-supprimer">Supprimer</a><br>
                        <a href="modifie_utilisateur.php?mod=<?php echo $utilisateur["id_utilisateur"]; ?>" class="btn-modifier">Modifier</a>
                    
                    </td> -->
        
                </tr>
            <?php } ?>
             <a href="secretaire_executif.php" class="btn">Retour</a>
        </tbody>
    </table>
</div>
</body>
</html>
