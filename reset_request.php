<?php
if (isset($_POST['changer'])) {
    include "connexion.php";
    $user = $_POST['username'];
    $new_password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);

    $stmt = $conn->prepare("UPDATE utilisateurs SET password = ? WHERE username = ?");
    $stmt->execute([$new_password, $user]);

    echo "<script>alert('Mot de passe modifié avec succès pour $user');</script>";
    header('location: index.php');
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Changer le mot de passe</title>
    <style>
        body {
            background-color: #f4f6f9;
            font-family: 'Segoe UI', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 50vh;
            margin: 0;
        }

        .form-container {
            background-color: #fff;
            padding: 30px 40px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }

        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 25px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #555;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 6px;
            margin-bottom: 20px;
            transition: border 0.3s ease;
        }

        input:focus {
            border-color: #0066cc;
            outline: none;
        }

        button {
            width: 100%;
            background-color: #007bff;
            color: white;
            padding: 12px;
            font-size: 16px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        button:hover {
            background-color: #0056b3;
        }

        .message {
            text-align: center;
            margin-top: 15px;
            color: #333;
        }
    </style>
</head>
<body>

<div class="form-container">
    <h2>Changer le mot de passe</h2>
    <form method="POST">
        <label for="username">Nom d'utilisateur :</label>
        <input type="text" name="username" id="username" required>

        <label for="new_password">Nouveau mot de passe :</label>
        <input type="password" name="new_password" id="new_password" required>

        <button type="submit" name="changer">Changer le mot de passe</button>
    </form>
</div>

</body>
</html>
