<?php

session_start();
$_SESSION['connecté'] = true; // Cela indique que l'utilisateur est connecté
$_SESSION['roles'] = 'secretaire_executif,secretaire,secretaire_interim'; // Rôles de l'utilisateur

include "connexion.php";

if (isset($_POST['valider'])) {
    $recuperuser = $_POST['NOM'];
    $recupepass = $_POST['pswd']; 

    $stmt = $conn->prepare("SELECT * FROM utilisateurs WHERE username = :username");
    $stmt->bindParam(':username', $recuperuser);
    $stmt->execute();
    $utilisateur = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($utilisateur && password_verify($recupepass, $utilisateur['password'])) {
        $_SESSION['user_id'] = $utilisateur['id_utilisateur'];
        $_SESSION['username'] = $utilisateur['username'];
        $_SESSION['role'] = $utilisateur['role'];

        switch ($utilisateur['role']) {
            case "secretaire_executif":
                header('Location: secretaire_executif.php');
                break;
            case "secretaire":
                header('Location: secretaire.php');
                break;
            case "secretaire_interim":
                header('Location: secretaire_interim.php');
                break;
            default:
                echo "role n'existe pas.";
                break;
        }
        exit();
    } else {
        $erreur_connexion = "Nom d'utilisateur ou mot de passe incorrect.";
    }
}
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link rel="stylesheet" href="style/authentification.css">
    <style>

        body {
            font-family: Arial, sans-serif;
            background-color:black;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 80vh;
            margin: 0;
            min-height: 90vh;
        }
        .container {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            width: 500px;
           
            text-align: center;
        }
        img {
            max-width: 90%;
            height: auto;
            margin-bottom: 10px;
            background:green;
        }
        h1 {
            font-size: 24px;
            margin-bottom: 20px;
            color:blue;
        }
        label {
            display: block;
            margin: 10px 0 5px;
        }
        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 5px 0 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button {
            background-color:rgb(4, 77, 4);
            color: white;
            padding: 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
        }
        button:hover {
            background-color: #d66527;
        }
        p {
            margin-top: 10px;
            color:rgb(183, 16, 58);
            size: 15px;
        }
        .a{
            color:blue;
        }
    </style>
</head>
<body>
    <div class="container">
        <img src="images/photo.png" alt="">
        <h1>Authentification</h1>
        <form action="" method="POST">
            <div class="username">
                <label>Nom d'utilisateur :</label>
                <input type="text" name="NOM" placeholder="Tapez votre nom" required pattern="[A-Za-zÀ-ÿ\s]{2,20}"/>    
            </div>
            <div class="password">
                <label>Mot de passe :</label>
                <input type="password" name="pswd" placeholder="Tapez votre mot de passe" required>
            </div>
            <div>
                <button type="submit" name="valider">Se connecter</button>
            </div>
        </form>
        <!-- <p>Vous n'avez pas de compte ? <a href="utilisateur.php">Inscription</a></p> -->
        <p>vous avez oublie le mot de passe? <a href="selectquestion.php">mot de passe oublie</a></p>
    </div>
</body>
</html>