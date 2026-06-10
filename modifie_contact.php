<?php
include "connexion.php";

$desti = $conn->query("SELECT id_contact, name, email, subject, message FROM contacts")->fetchAll(PDO::FETCH_ASSOC);

$modificationEnCours = false;
$moddesti = null;

if (isset($_GET['mod'])) {
    $modifie = intval($_GET['mod']);
    $modificationEnCours = true;

    $modification = $conn->prepare("SELECT * FROM contacts WHERE id_contact = :id_contact");
    $modification->bindParam(':id_contact', $modifie, PDO::PARAM_INT);
    $modification->execute();
    $moddesti = $modification->fetch(PDO::FETCH_ASSOC);

    if (isset($_POST['Modifier'])) {
        $recupedesti = $_POST['name'];
        $recuname = $_POST['email'];
        $recupesub = $_POST['subject'];
        $recupmessa = $_POST['message']; 

        $modfie = $conn->prepare("UPDATE contacts SET name = :name, email = :email, subject = :subject, message = :message WHERE id_contact = :id_contact");
        $modfie->bindParam(':name', $recupedesti);
        $modfie->bindParam(':email', $recuname);
        $modfie->bindParam(':subject', $recupesub);
        $modfie->bindParam(':message', $recupmessa);
        $modfie->bindParam(':id_contact', $modifie, PDO::PARAM_INT); 

        if ($modfie->execute()) {
            echo "<script>alert('Modification réussie');</script>";
            header('Location: affichage_contact.php');
            exit();
        } else {
            echo "<script>alert('Erreur de modification');</script>";
        }
    }
} else {
    echo "<script>alert('Aucune ID de contact spécifiée.');</script>";
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contactez-nous</title>
    <style>
        /* Basic styles commented out for clarity */
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
            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($moddesti['name']); ?>" required>
        </div>
        <div class="form-group">
            <label for="email">Email :</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($moddesti['email']); ?>" required>
        </div>
        <div class="form-group">
            <label for="subject">Sujet :</label>
            <input type="text" id="subject" name="subject" value="<?php echo htmlspecialchars($moddesti['subject']); ?>">
        </div>
        <div class="form-group">
            <label for="message">Commentaire ou Message :</label>
            <textarea id="message" name="message" rows="4" required><?php echo htmlspecialchars($moddesti['message']); ?></textarea>
        </div>
        <button type="submit" name="Modifier">Modifier</button>
    </form>

    <div class="social-media">
        <p>Suivez-nous sur les différents réseaux sociaux :</p>
        <a href="https://www.facebook.com/profile.php?id=100067322347665">Facebook</a>
        <a href="https://x.com/seticburundi?t=99Cyw7Wc-d1OEHl95grdOw&s=09">Twitter</a>
        <a href="https://youtube.com/@seticburundi_officiel?si=KK-c21V8AxJqTQ6w">YouTube</a>
    </div>
</div>

</body>
</html>