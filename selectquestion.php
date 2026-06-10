<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Questionnaire</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #e9ecef;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            width: 400px;
        }
        h1 {
            text-align: center;
            color: #007BFF;
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin: 15px 0 5px;
            font-weight: bold;
        }
        input[type="date"],
        input[type="text"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ced4da;
            border-radius: 5px;
            margin-bottom: 15px;
        }
        button {
            width: 100%;
            padding: 10px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Vérification du Mot de Passe Oublié</h1>
        <form action="" method="POST">
            <div class="form-group">
                <label>Utilisateur :</label>
                <select name="utilisateur" required>
                    <option value="" disabled selected>Sélectionnez un utilisateur</option>
                    <?php
                    include "connexion.php";
                    try {
                        $affichagerole = $conn->query("SELECT id_utilisateur, username FROM utilisateurs");
                        while ($datarecup = $affichagerole->fetch()) {
                            echo "<option value='" . htmlspecialchars($datarecup['id_utilisateur'], ENT_QUOTES, 'UTF-8') . "'>" . htmlspecialchars($datarecup['username'], ENT_QUOTES, 'UTF-8') . "</option>";
                        }
                        $affichagerole->closeCursor();
                    } catch (PDOException $e) {
                        echo "<option value=''>Erreur de chargement des utilisateurs</option>";
                    }
                    ?>
                </select>
            </div>
            <label for="date_naissance">1. Quelle est votre date de naissance ?</label>
            <input type="date" name="date_naissance" required max="<?php echo date('Y-m-d'); ?>">

            <label for="province">2. Dans quelle province êtes-vous né(e) ?</label>
            <input type="text" name="province" placeholder="Votre province" required>

            <label for="school">3. Quel est le nom de votre école primaire ?</label>
            <input type="text" name="school" placeholder="Votre école primaire" required>

            <label for="father">4. Quel est le nom de votre père ?</label>
            <input type="text" name="father" placeholder="Votre père" required>

            <label for="mother">5. Quel est le nom de votre mère ?</label>
            <input type="text" name="mother" placeholder="Votre mère" required>

            <button type="submit" name="valider">Enregistrer</button>
        </form>
    </div>
</body>
</html>

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
if (isset($_POST['valider'])) {
    $recupuser = $_POST['utilisateur'];
    $recupedate = $_POST['date_naissance'];
    $recupepro = $_POST['province'];
    $recupscho = $_POST['school'];
    $recufat = $_POST['father'];
    $recumot = $_POST['mother'];

    
    $requete = $conn->prepare("SELECT * FROM questionnairedemande 
        WHERE id_utilisateur = :utilisateur 
        AND date_naissance = :date_naissance 
        AND province = :province 
        AND school = :school 
        AND father = :father 
        AND mother = :mother");

    $requete->execute([
        ':utilisateur' => $recupuser,
        ':date_naissance' => $recupedate,
        ':province' => $recupepro,
        ':school' => $recupscho,
        ':father' => $recufat,
        ':mother' => $recumot
    ]);

    if ($requete->rowCount() > 0) {
       
        header('Location: reset_request.php');
        exit(); 
    } else {
        echo "<script>alert('Pas des informations correspondent sur cette utilisateur ou utilisateur n'existe pas.');</script>";
    }
}
?>
