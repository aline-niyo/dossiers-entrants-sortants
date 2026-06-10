<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contactez-nous</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: auto;
            background: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
        }
        input[type="text"],
        input[type="email"],
        textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
        }
        button:hover {
            background-color: #218838;
        }
        .social-media {
            text-align: center;
            margin-top: 20px;
        }
        .social-media a {
            margin: 0 10px;
            text-decoration: none;
            color: #007bff;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Contactez-nous</h1>
    <p>Envoyer-nous un Email : <a href="mailto:info@setic.gov.bi">info@setic.gov.bi</a></p>

    <form method="POST" action="">
        <div class="form-group">
            <label for="name">Nom :</label>
            <input type="text" id="name" name="name" required  pattern="[A-Za-z\s]*">
        </div>
        <div class="form-group">
            <label for="email">Email :</label>
            <input type="email" id="email" name="email" required>
        </div>
        <div class="form-group">
            <label for="subject">Sujet :</label>
            <input type="text" id="subject" name="subject" require pattern="[A-Za-z\s]*">
        </div>
        <div class="form-group">
            <label for="message">Commentaire ou Message :</label>
            <textarea id="message" name="message" rows="4" required  pattern="[A-Za-z0-9\s]*"></textarea>
        </div>
        <button type="submit" name="valider">Envoyer</button>
    </form>

    <div class="social-media">
        <p>Suivez-nous sur les différents réseaux sociaux :</p>
        <a href="https://www.facebook.com/profile.php?id=100067322347665">Facebook</a>
        <a href="https://x.com/seticburundi?t=99Cyw7Wc-d1OEHl95grdOw&s=09">Twitter</a>
        <a href="https://youtube.com/@seticburundi_officiel?si=KK-c21V8AxJqTQ6w">YouTube</a>
    </div>
</div>

<?php
include "connexion.php";

if (isset($_POST['valider'])) {
    $recupernom = $_POST['name'];
    $recupemail = $_POST['email'];
    $recupesujet = $_POST['subject'];
    $recupemessage = $_POST['message'];

    // Préparation de la requête
    $insertdata = $conn->prepare("INSERT INTO contacts (name, email, subject, message) VALUES (:name, :email, :subject, :message)");
    
    // Lier les paramètres
    $insertdata->bindParam(':name', $recupernom);
    $insertdata->bindParam(':email', $recupemail);
    $insertdata->bindParam(':subject', $recupesujet);
    $insertdata->bindParam(':message', $recupemessage);

    // Exécution de la requête
    if ($insertdata->execute()) {
        echo "<script>alert('Message envoyé avec succès.');</script>";
        header('location:affichage_contact.php');
    } else {
        echo "<script>alert('Message non envoyé, répétez encore s\'il vous plaît.');</script>";
    }
}
?>

</body>
</html>