<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mot de Passe Oublié</title>
    <style>
        body {
            background-color: green;
            margin: 0;
            padding: 20px;
            height: 50vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            color: white;
            font-family: Arial, sans-serif;
        }
        h1 {
            color: white;
            margin-bottom: 20px;
        }
        label {
            color: white;
            text-align: left;
            display: block;
            margin-bottom: 5px;
        }
        input[type="text"] {
            padding: 10px;
            border: none;
            border-radius: 4px;
            width: 100%;
            max-width: 300px;
            margin-bottom: 20px;
        }
        button {
            padding: 10px 20px;
            border: none;
            background-color: blue;
            color: white;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover {
            background-color: darkblue;
        }
        .retour_index {
            margin-top: 20px;
            text-align: center;
        }
        p{
            color:blue;
        }
        .a.Se connecter{
            color:blue;
        }
    </style>
</head>
<body>
    <div class="container">
        <form action="" method="POST">
            <h1>Mot de passe oublié</h1> 
            <div>
                <label for="username">Nom d'utilisateur :</label>
                <input type="text" name="username" placeholder="Votre nom d'utilisateur" required>
            </div>
            <button type="submit" name="valider">Envoyer</button>
        </form>

        <!-- <?php
        // if (isset($_POST['valider'])) {
        //     include "connexion.php";
        //     $user = $_POST['username'];

        //     $stmt = $conn->prepare("SELECT password FROM utilisateurs WHERE username = ?");
        //     $stmt->execute([$user]);
        //     $result = $stmt->fetch();

        //     if ($result) {
        //         echo "<script>alert('Votre mot de passe pour $user est : " . addslashes($result['password']) . "');</script>";
        //     } else {
        //         echo "<p style='color: red; margin-top: 20px;'>Nom d'utilisateur introuvable.</p>";
        //     }
        // }
        ?> -->
    </div>
    <div class="retour_index">
        <p>Vous avez vu votre mot de passe ? Maintenant vous pouvez vous connecter ? <a href="index.php" style="color: lightblue;">Se connecter</a></p>
    </div>
</body>
</html>

<?php
if (isset($_POST['valider'])) {
    include "connexion.php";
    $user = $_POST['username'];
    
    // Préparation de la requête SQL pour récupérer le mot de passe haché
    $stmt = $conn->prepare("SELECT password FROM utilisateurs WHERE username = ?");
    $stmt->execute([$user]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    // Vérification si l'utilisateur existe et si un mot de passe est retourné
    if ($result && isset($result['password'])) {
        // Affichage du mot de passe haché
        echo "<script>alert('Votre mot de passe pour $user est : " . addslashes($result['password']) . "');</script>";
    } else {
        echo "<p style='color: red; margin-top: 20px;'>Nom d'utilisateur introuvable.</p>";
    }
}
?>

