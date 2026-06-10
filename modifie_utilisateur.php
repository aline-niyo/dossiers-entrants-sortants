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


if (isset($_GET['mod'])) {
    $modifie = intval($_GET['mod']);

    $stmt = $conn->prepare("SELECT * FROM utilisateurs WHERE id_utilisateur = :id_utilisateur");
    $stmt->bindParam(':id_utilisateur', $modifie, PDO::PARAM_INT);
    $stmt->execute();
    $modfiee = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($modfiee) {
        if (isset($_POST['Modifier'])) {
            $nom = $_POST['nom'];
            $prenom = $_POST['prenom'];
            $email = $_POST['email'];
            $telephone = $_POST['telephone'];
            $username = $_POST['username'];
            $password = $_POST['password'];
            $role = $_POST['role'];

            // Gérer mot de passe : garder ancien si vide
            if (!empty($password)) {
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            } else {
                $hashedPassword = $modfiee['password'];
            }

            $update = $conn->prepare("UPDATE utilisateurs SET 
                nom = :nom, 
                prenom = :prenom, 
                email = :email, 
                telephone = :telephone, 
                username = :username, 
                password = :password, 
                role = :role 
                WHERE id_utilisateur = :id_utilisateur");

            $update->bindParam(':nom', $nom);
            $update->bindParam(':prenom', $prenom);
            $update->bindParam(':email', $email);
            $update->bindParam(':telephone', $telephone);
            $update->bindParam(':username', $username);
            $update->bindParam(':password', $hashedPassword);
            $update->bindParam(':role', $role);
            $update->bindParam(':id_utilisateur', $modifie, PDO::PARAM_INT);

            if ($update->execute()) {
                echo "<script>alert('Modification réussie'); window.location.href='utilisateur.php';</script>";
                exit();
            } else {
                echo "<script>alert('Erreur lors de la modification');</script>";
            }
        }
    } else {
        echo "<script>alert('Utilisateur non trouvé.');</script>";
        exit();
    }
} else {
    echo "<script>alert('ID de l\'utilisateur non spécifié.');</script>";
    exit();
}
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier Utilisateur</title>
    <link rel="stylesheet" href="style/modutili.css">
</head>
<body>
    <form method="POST" action="">
        <h1>Modifier un utilisateur</h1>

        <label>ID Utilisateur :</label>
        <input type="text" value="<?= htmlspecialchars($modfiee['id_utilisateur']) ?>" readonly><br><br>

        <label>Nom :</label>
        <input type="text" name="nom" value="<?= htmlspecialchars($modfiee['nom']) ?>" required><br><br>

        <label>Prénom :</label>
        <input type="text" name="prenom" value="<?= htmlspecialchars($modfiee['prenom']) ?>" required><br><br>

        <label>Email :</label>
        <input type="email" name="email" value="<?= htmlspecialchars($modfiee['email']) ?>" required><br><br>

        <label>Téléphone :</label>
        <input type="number" name="telephone" value="<?= htmlspecialchars($modfiee['telephone']) ?>" required><br><br>

        <label>Nom d'utilisateur :</label>
        <input type="text" name="username" value="<?= htmlspecialchars($modfiee['username']) ?>" required><br><br>

        <label>Mot de passe :</label>
        <input type="password" name="password" placeholder="Nouveau mot de passe (laisser vide pour conserver l'ancien)"><br>

        <label>Rôle :</label>
        <select name="role" required>
            <option value="">-- Choisissez un rôle --</option>
            <option value="secretaire_executif" <?= $modfiee['role'] ?>>secretaire_executif</option>
            <option value="secretaire" <?= $modfiee['role']?>>secretaire</option>
            <option value="secretaire_interim" <?= $modfiee['role'] ?>>secretaire_interim</option>
        </select><br>

        <button type="submit" name="Modifier">Modifier</button>
    </form>
</body>
</html>