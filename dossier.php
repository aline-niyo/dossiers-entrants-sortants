<?php
session_start(); // Démarrer la session

// Redirection si l'utilisateur n'est pas connecté
if (!isset($_SESSION['connecté'])) {
    header('Location: index.php');
    exit();
}

// Vérification des rôles
$roles = explode(',', $_SESSION['roles']);
if (!array_intersect(['secretaire_executif', 'secretaire', 'secretaire_interim'], $roles)) {
    // Redirection ou gestion des permissions ici
}

include "connexion.php";

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['valider'])) {
    $nom = htmlspecialchars(trim($_POST['nom']));
    $numero = htmlspecialchars(trim($_POST['numero_reference']));
    $date_envoi = $_POST['date_envoi'];
    $date_reception = $_POST['date_reception'];
    $destinateur = intval($_POST['destinateur']);
    $objet = htmlspecialchars(trim($_POST['objet']));
    $user = intval($_POST['utilisateur']);
 

    // Vérification de l'unicité du numéro de référence
    $verif = $conn->prepare("SELECT COUNT(*) FROM dossiers WHERE numero_reference = ?");
    $verif->execute([$numero]);
    if ($verif->fetchColumn() > 0) {
        echo "<script>alert('Ce numéro de référence existe déjà.');</script>";
    } else {
        $destinateurVerif = $conn->prepare("SELECT COUNT(*) FROM destinateurs WHERE id_destinateur = ?");
        $destinateurVerif->execute([$destinateur]);

        if ($destinateurVerif->fetchColumn() == 0) {
            echo "<script>alert('Le destinateur sélectionné n\\'existe pas.');</script>";
        } else {
            $insert = $conn->prepare("
                INSERT INTO dossiers (nom, numero_reference, date_envoi, date_reception, id_destinateur, objet, id_utilisateur) 
                VALUES (:nom, :numero_reference, :date_envoi, :date_reception, :destinateur, :objet, :utilisateur)
            ");
            $insert->bindParam(':nom', $nom);
            $insert->bindParam(':numero_reference', $numero);
            $insert->bindParam(':date_envoi', $date_envoi);
            $insert->bindParam(':date_reception', $date_reception);
            $insert->bindParam(':destinateur', $destinateur);
            $insert->bindParam(':objet', $objet);
            $insert->bindParam(':utilisateur', $user);
          

            if ($insert->execute()) {
                echo "<script>alert('Dossier enregistré avec succès !');</script>";
                header("Location: " . $_SERVER['PHP_SELF']);
                exit();
            } else {
                echo "<script>alert('Erreur lors de l\\'enregistrement du dossier.');</script>";
            }
        }
    }
}

// Recherche
$searchTerm = '';
if (isset($_GET['search'])) {
    $searchTerm = htmlspecialchars($_GET['search']);
    $affichageUtilisateur = $conn->prepare("
        SELECT d.id_dossier, d.nom, d.numero_reference, d.date_envoi, d.date_reception, de.nom_destinateur, d.objet
        FROM dossiers d
        LEFT JOIN destinateurs de ON d.id_destinateur = de.id_destinateur
        WHERE d.nom LIKE :searchTerm OR d.numero_reference LIKE :searchTerm OR de.nom_destinateur LIKE :searchTerm
    ");
    $affichageUtilisateur->execute([':searchTerm' => '%' . $searchTerm . '%']);
} else {
    $affichageUtilisateur = $conn->query("
        SELECT d.id_dossier, d.nom, d.numero_reference, d.date_envoi, d.date_reception, de.nom_destinateur, d.objet, u.username
        FROM dossiers d
        LEFT JOIN destinateurs de ON d.id_destinateur = de.id_destinateur
        LEFT JOIN utilisateurs u ON d.id_utilisateur = u.id_utilisateur
        ORDER BY d.date_reception DESC, d.id_dossier DESC
    ");
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Dossiers reçus</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/dossier.css">
    <!-- Font Awesome pour les icônes -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

<div class="container">
    <form method="POST" enctype="multipart/form-data">
        <fieldset>
            <legend>Dossiers reçus</legend>

            <label for="utilisateur">Utilisateur :</label>
            <select name="utilisateur" required>
                <option value="" disabled selected>Sélectionnez un utilisateur</option>
                <?php
                include "connexion.php";
                try {
                    $affichagerole = $conn->query("SELECT * FROM utilisateurs WHERE role = 'secretaire'");
                    while ($datarecup = $affichagerole->fetch()) {
                        echo "<option value='" . htmlspecialchars($datarecup['id_utilisateur'], ENT_QUOTES, 'UTF-8') . "'>" . htmlspecialchars($datarecup['username'], ENT_QUOTES, 'UTF-8') . "</option>";
                    }
                    $affichagerole->closeCursor();
                } catch (PDOException $e) {
                    echo "<option value=''>Erreur de chargement des utilisateurs</option>";
                }
                ?>
            </select>

            <label for="nom">Nom du dossier :</label>
            <input type="text" name="nom" required placeholder="Taper le nom du dossier">

            <label for="numero_reference">Numéro de référence :</label>
            <input type="text" name="numero_reference" required placeholder="Entrez le numéro de référence">

            <label for="date_envoi">Date d'envoi :</label>
            <input type="date" name="date_envoi" required max="<?php echo date('Y-m-d'); ?>">

            <label for="date_reception">Date de réception :</label>
            <input type="date" name="date_reception" required value="<?php echo date('Y-m-d'); ?>" max="<?php echo date('Y-m-d'); ?>" min="<?php echo date('Y-m-d'); ?>">

            <label for="destinateur">Nom de l'Expediteur :</label>
            <select name="destinateur" required>
                <option value="" disabled selected>Sélectionnez un destinateur</option>
                <?php
                try {
                    $stmt = $conn->query("SELECT id_destinateur, nom_destinateur FROM destinateurs");
                    while ($row = $stmt->fetch()) {
                        echo "<option value='" . htmlspecialchars($row['id_destinateur'], ENT_QUOTES, 'UTF-8') . "'>" . htmlspecialchars($row['nom_destinateur'], ENT_QUOTES, 'UTF-8') . "</option>";
                    }
                    $stmt->closeCursor();
                } catch (PDOException $e) {
                    echo "<option value=''>Erreur de chargement</option>";
                }
                ?>
            </select>

            <label for="objet">Objet :</label>
            <input type="text" name="objet" required placeholder="Entrez l'objet du dossier">


            <button type="submit" name="valider">Enregistrer</button>
        </fieldset>
    </form>
</div>

<div class="container">
    <h1>Liste des Dossiers Enregistrés</h1>
    <form action="" method="GET" class="search-container">
        <input type="text" name="search" placeholder="Rechercher un dossier..." value="<?php echo htmlspecialchars($searchTerm); ?>">
        <button type="submit"><i class="fa fa-search"></i>Chercher</button>
    </form>
    <center><p>Voulez-vous voir les dossiers archivés ? <a href="Archivage/affichage_dossier.php">Visiter</a></p></center>

    <table>
        <thead>
            <tr>
                <th>Nom du dossier</th>
                <th>Numéro de Référence</th>
                <th>Date d'Envoi</th>
                <th>Date de Réception</th>
                <th>Nom de l'Expéditeur</th>
                <th>Objet</th>
                <th>Utilisateur Ajouté</th>
                <th colspan="4">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $count = 0;
            while ($utilisateur = $affichageUtilisateur->fetch(PDO::FETCH_ASSOC)) { 
                $count++;
            ?>
            <tr>
                <td><i class="fa-solid fa-folder" style="color: #f0ad4e;"></i> <?php echo htmlspecialchars($utilisateur["nom"]); ?></td>
                <td><?php echo htmlspecialchars($utilisateur["numero_reference"]); ?></td>
                <td><?php echo htmlspecialchars($utilisateur["date_envoi"]); ?></td>
                <td><?php echo htmlspecialchars($utilisateur["date_reception"]); ?></td>
                <td><?php echo htmlspecialchars($utilisateur["nom_destinateur"] ?? 'Inconnu'); ?></td>
                <td><?php echo htmlspecialchars($utilisateur["objet"]); ?></td>
                <td><?php echo htmlspecialchars($utilisateur["username"]); ?></td>
                <td>
                    <option value="selected">
                        <!-- <td><a href="ouvrir_dos.php?id=<?php// echo $utilisateur['id_dossier']; ?>"><i class="fa fa-comment-dots"></i> Commentaires</a></td> -->
                        <td><a href="modifie_dossier.php?mod=<?php echo $utilisateur["id_dossier"]; ?>"><i class="fa fa-edit"></i> Modifier</a></td>
                        <td><a href="Archivage/archivage_dossier.php?archiver=<?php echo $utilisateur['id_dossier']; ?>"><i class="fa fa-archive"></i> Archiver</a></td>
                        <td><a href="trans_dossier.php?trans=<?php echo $utilisateur['id_dossier']; ?>"><i class="fa fa-share"></i> Transférer</a></td>
                        <!-- <td><a href="fir.php?nom=<?php //echo urlencode($utilisateur['nom']); ?>"><i class="fa fa-eye"></i> Voir</a></td> -->
                    </option>
                </td>

            </tr>
            <?php } ?>
            <?php if ($count === 0): ?>
                <tr><td colspan="10">Aucun dossier trouvé.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<script>
function confirmDelete(dossierId) {
    const confirmation = confirm("Êtes-vous sûr de vouloir supprimer ce dossier ?");
    if (confirmation) {
        window.location.href = "dossier.php?sup=" + dossierId;
    }
}
</script>
</body>
</html>



