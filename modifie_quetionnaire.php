<?php
include "connexion.php";

$id_modifier = null;
$donnees = [];

if (isset($_GET['edit'])) {
    $id_modifier = $_GET['edit'];
    $stmt = $conn->prepare("SELECT * FROM questionnairedemande WHERE id= ?");
    $stmt->execute([$id_modifier]);
    $donnees = $stmt->fetch(PDO::FETCH_ASSOC);
}

if (isset($_POST['modifier'])) {
    $id = $_POST['id_questionnaire'];
    $date_naissance = $_POST['date_naissance'];
    $province = $_POST['province'];
    $school = $_POST['school'];
    $father = $_POST['father'];
    $mother = $_POST['mother'];

    $update = $conn->prepare("UPDATE questionnairedemande SET date_naissance=?, province=?, school=?, father=?, mother=? WHERE id=?");
    if ($update->execute([$date_naissance, $province, $school, $father, $mother, $id])) {
        echo "<script>alert('Modification réussie'); window.location.href='modifie_quetionnaire.php';</script>";
    } else {
        echo "<script>alert('Erreur lors de la modification');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier Questionnaire</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #e9ecef;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 700px;
            background-color: white;
            margin: auto;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 25px;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #007BFF;
            color: white;
        }
        form input, form button {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
        }
        .edit-btn {
            background-color: orange;
            border: none;
            padding: 5px 10px;
            color: white;
            border-radius: 4px;
            cursor: pointer;
        }
        .edit-btn:hover {
            background-color: darkorange;
        }
    </style>
</head>
<body>
    <div class="container">

        <?php if ($id_modifier): ?>
            <form method="POST">
                <input type="hidden" name="id" value="<?php echo $donnees['id']; ?>">
                <label>Date de naissance :</label>
                <input type="date" name="date_naissance" value="<?php echo $donnees['date_naissance']; ?>" required>
                
                <label>Province :</label>
                <input type="text" name="province" value="<?php echo $donnees['province']; ?>" required>
                
                <label>École primaire :</label>
                <input type="text" name="school" value="<?php echo $donnees['school']; ?>" required>
                
                <label>Nom du père :</label>
                <input type="text" name="father" value="<?php echo $donnees['father']; ?>" required>
                
                <label>Nom de la mère :</label>
                <input type="text" name="mother" value="<?php echo $donnees['mother']; ?>" required>

                <button type="submit" name="modifier">Enregistrer les modifications</button>
            </form>
        <?php endif; ?>

        <p>Voulez_vous Ajouter des questionnaire demandes ?<a href="questionnaire.php">Ajouter</a></p>

        <h3>Liste des questionnaires demandes enregistrées</h3>
        <table>
            <tr>
                <th>Utilisateur</th>
                <th>Date Naissance</th>
                <th>Province</th>
                <th>École</th>
                <th>Père</th>
                <th>Mère</th>
                <th>Action</th>
            </tr>
            <?php
            $stmt = $conn->query("SELECT q.*, u.username FROM questionnairedemande q JOIN utilisateurs u ON q.id_utilisateur = u.id_utilisateur");
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr>
                        <td>" . htmlspecialchars($row['username']) . "</td>
                        <td>" . htmlspecialchars($row['date_naissance']) . "</td>
                        <td>" . htmlspecialchars($row['province']) . "</td>
                        <td>" . htmlspecialchars($row['school']) . "</td>
                        <td>" . htmlspecialchars($row['father']) . "</td>
                        <td>" . htmlspecialchars($row['mother']) . "</td>
                        <td><a href='?edit=" . $row['id'] . "' class='edit-btn'>Modifier</a></td>
                      </tr>";
            }
            ?>
        </table>
    </div>
</body>
</html>
