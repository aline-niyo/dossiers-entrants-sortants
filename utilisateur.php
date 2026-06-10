<?php
session_start(); 
if (!isset($_SESSION['connecté'])) {
    header('Location: index.php');
    exit();
}
$roles = explode(',', $_SESSION['roles']);
if (!in_array('secretaire_executif,secretaire,secretaire_interim', $roles)) {
   
}

include "connexion.php";

if (isset($_POST['valider'])) {
    $nom = htmlspecialchars($_POST['nom']);
    $prenom = htmlspecialchars($_POST['prenom']);
    $tel = htmlspecialchars($_POST['telephone']);
    $email = htmlspecialchars($_POST['Email']);
    $username = htmlspecialchars($_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];

    // Vérifier si l'email, le username ou le téléphone existent déjà
    $checkExistence = $conn->prepare("
        SELECT COUNT(*) FROM utilisateurs 
        WHERE email = ? OR username = ? OR telephone = ?
    ");
    $checkExistence->execute([$email, $username, $tel]);
    $exists = $checkExistence->fetchColumn();

    if ($exists > 0) {
        echo "<script>alert('Erreur : Cet email, ce téléphone ou ce nom d’utilisateur est déjà utilisé.');</script>";
    } else {
        $insertdata = $conn->prepare("
            INSERT INTO utilisateurs (nom, prenom, telephone, email, username, password, role) 
            VALUES (:nom, :prenom, :telephone, :email, :username, :password, :role)
        ");

        $insertdata->bindParam(':nom', $nom);
        $insertdata->bindParam(':prenom', $prenom);
        $insertdata->bindParam(':telephone', $tel);
        $insertdata->bindParam(':email', $email);
        $insertdata->bindParam(':username', $username);
        $insertdata->bindParam(':password', $password);
        $insertdata->bindParam(':role', $role);

        if ($insertdata->execute()) {
            echo "<script>alert('Utilisateur enregistré avec succès !');</script>";
        } else {
            $errorInfo = $insertdata->errorInfo();
            echo "<script>alert('Erreur lors de l\'enregistrement: " . $errorInfo[2] . "');</script>";
        }
    }
}

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
        WHERE nom LIKE :searchTerm OR prenom LIKE :searchTerm ORDER BY id_utilisateur DESC");
    $affichageUtilisateur->execute([':searchTerm' => '%' . $searchTerm . '%']);
} else {
    $affichageUtilisateur = $conn->query("SELECT id_utilisateur, nom, prenom, email, telephone, username, role FROM utilisateurs ORDER BY id_utilisateur DESC");
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Utilisateurs</title>
    <link rel="stylesheet" href="style/utilisateur.css">
</head>
<body>

    <!-- Formulaire d'ajout -->
    <div class="form-container">
        <form method="POST">
            <legend>Ajouter un utilisateur</legend>

            <label for="nom">Nom</label>
            <input type="text" name="nom" required pattern="[A-Za-z\s]+">

            <label for="prenom">Prénom</label>
            <input type="text" name="prenom" required pattern="[A-Za-z\s]+">

            <label for="telephone">Téléphone</label>
            <input type="text" name="telephone" required pattern="[0-9]+">

            <label for="Email">Email</label>
            <input type="email" name="Email" required>

            <label for="username">Nom d'utilisateur</label>
            <input type="text" name="username" required pattern="[A-Za-z0-9_]+">

            <label for="password">Mot de passe</label>
            <input type="password" name="password" required>

            <label for="role">Rôle</label>
            <select name="role" required>
                <option value="secretaire_executif">Secrétaire exécutif</option>
                <option value="secretaire">Secrétaire</option>
                <option value="secretaire_interimn">Secrétaire intérim</option>
            </select>

            <button type="submit" name="valider">Enregistrer</button>
        </form>
    </div>

    <
    <div class="table-container">
        <h1>Liste des utilisateurs</h1>

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
                    <th>Rôle</th>
                    <th>Actions</th>
                    <th><a href="utilisateur.php" class="plus-button">+</a></th>
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
                        <td><?php echo htmlspecialchars($utilisateur["role"]); ?></td>
                        <td>
                            <a href="utilisateur.php?sup=<?php echo $utilisateur["id_utilisateur"]; ?>" class="btn-supprimer">Supprimer</a><br>
                            <a href="modifie_utilisateur.php?mod=<?php echo $utilisateur["id_utilisateur"]; ?>" class="btn-modifier">Modifier</a>
                        </td>
                    </tr>
                <?php } ?>
                <a href="secretaire_executif.php" class="btn">Retour</a>
            </tbody>
        </table>
    </div>

</body>
</html>
