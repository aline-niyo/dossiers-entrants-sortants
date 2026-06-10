<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/open.css">
    <title>Détails du Dossier</title>
    
</head>
<body>
    <div class="container">
    <?php
session_start();
include "connexion.php";

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['connecté'])) {
    header('Location: index.php');
    exit();
}

// Vérification des rôles
$roles = explode(',', $_SESSION['roles']);
if (!in_array('secretaire', $roles) && !in_array('secretaire_executif', $roles) && !in_array('secretaire_interim', $roles)) {
    // Accès interdit
    exit();
}

if (isset($_GET['id'])) {
    $id_dossier = intval($_GET['id']);

    // Récupérer les détails du dossier
    $stmt = $conn->prepare("SELECT * FROM dossiers d LEFT JOIN destinateurs de ON d.id_destinateur = de.id_destinateur WHERE d.id_dossier = :id_dossier");
    $stmt->bindParam(':id_dossier', $id_dossier, PDO::PARAM_INT);
    $stmt->execute();
    $dossier = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($dossier) {
        // Affichage des détails
        echo "<h1>Détails du Dossier : " . htmlspecialchars($dossier['nom'], ENT_QUOTES, 'UTF-8') . "</h1>";
        echo "<p><strong>Numéro de Référence:</strong> " . htmlspecialchars($dossier['numero_reference'], ENT_QUOTES, 'UTF-8') . "</p>";
        echo "<p><strong>Date d'Envoi:</strong> " . htmlspecialchars($dossier['date_envoi'], ENT_QUOTES, 'UTF-8') . "</p>";
        echo "<p><strong>Date de Réception:</strong> " . htmlspecialchars($dossier['date_reception'], ENT_QUOTES, 'UTF-8') . "</p>";
        echo "<p><strong>Destinateur:</strong> " . htmlspecialchars($dossier['nom_destinateur'], ENT_QUOTES, 'UTF-8') . "</p>";
        echo "<p><strong>Objet:</strong> " . htmlspecialchars($dossier['objet'], ENT_QUOTES, 'UTF-8') . "</p>";
    } else {
        echo "<p>Dossier introuvable.</p>";
    }
} else {
    echo "<p>ID de dossier manquant.</p>";
}
?>

<a href="dossier.php">Retour à la liste des dossiers</a>

        
    </div>
</body>
</html>
