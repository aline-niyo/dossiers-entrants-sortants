<?php
include "connexion.php";
include "menu.php";

if (isset($_GET["sup"])) {
    try {
        $contact = intval($_GET["sup"]); // Sécuriser l'identifiant avec intval
        $suppression = $conn->prepare("DELETE FROM contacts WHERE id_contact = :id_contact");
        $suppression->execute([':id_contact' => $contact]);
        echo "<script>alert('Contact supprimé avec succès !');</script>";
    } catch (PDOException $e) {
        echo '<div class="message error">Erreur lors de la suppression : ' . htmlspecialchars($e->getMessage()) . '</div>';
    }
}

$searchTerm = '';
if (isset($_GET['search'])) {
    $searchTerm = htmlspecialchars($_GET['search']);
    $contact = $conn->prepare("SELECT * FROM contacts WHERE name LIKE :searchTerm OR email LIKE :searchTerm");
    $contact->execute([':searchTerm' => '%' . $searchTerm . '%']);
} else {
    $contact = $conn->query("SELECT * FROM contacts");
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Contacts</title>
    <link rel="stylesheet" href="style.css"> <!-- Lien vers le fichier CSS -->
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 90%;
            max-width: 800px;
            margin: 40px auto;
            padding: 20px;
            background: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #343a40;
            margin-bottom: 20px;
        }

        .search-container {
            text-align: center;
            margin: 20px 0;
        }

        .search-container input {
            padding: 10px;
            width: 200px;
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
            width: 100%;
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
            color: white;
            border-radius: 5px;
            text-decoration: none;
        }

        .btn-supprimer {
            background-color: #dc3545;
        }

        .btn-supprimer:hover {
            background-color: #c82333;
        }

        .btn-modifier {
            background-color: #ffc107;
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
    <h1>Liste des Contacts :</h1>
    <form action="" method="GET" class="search-container">
        <input type="text" name="search" placeholder="Rechercher un contact..." value="<?php echo htmlspecialchars($searchTerm); ?>">
        <button type="submit">Chercher</button>
    </form>
    <table>
        <thead>
            <tr>
                <th>Nom</th>
                <th>Email</th>
                <th>Sujet</th>
                <th>Message</th>
                <th colspan="2">Actions</th>
                <th><a href="contact.php" class="plus-button">+</a></th> 
            </tr>
        </thead>
        <tbody>
            <?php while ($destinateur = $contact->fetch(PDO::FETCH_ASSOC)) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($destinateur["name"]); ?></td>
                    <td><?php echo htmlspecialchars($destinateur["email"]); ?></td>
                    <td><?php echo htmlspecialchars($destinateur["subject"]); ?></td>
                    <td><?php echo htmlspecialchars($destinateur["message"]); ?></td>
                    <td>
                        <a href="#" onclick="confirmDelete(<?php echo $destinateur["id_contact"]; ?>)" class="btn-supprimer">Supprimer</a>
                    </td>
                    <td>
                        <a href="modifie_contact.php?mod=<?php echo $destinateur["id_contact"]; ?>" class="btn-modifier">Modifier</a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<script>
function confirmDelete(contactId) {
    const confirmation = confirm("Êtes-vous sûr de vouloir supprimer ce contact ?");
    if (confirmation) {
        window.location.href = "affichage_contact.php?sup=" + contactId;
    }
}
</script>
</body>
</html>