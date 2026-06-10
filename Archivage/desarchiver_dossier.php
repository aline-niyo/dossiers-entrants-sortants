<?php
session_start();
// Sécurité
if (!isset($_SESSION['connecté'])) {
    header('Location: ../index.php');
    exit();
}
$roles = explode(',', $_SESSION['roles']);
if (!array_intersect(['secretaire_executif','secretaire','secretaire_interim'], $roles)) {
    header('HTTP/1.1 403 Forbidden');
    echo "Accès refusé";
    exit();
}
require_once __DIR__ . '/../connexion.php';

if (isset($_GET['desarchiver'])) {
    $id = intval($_GET['desarchiver']);

    // Récupérer l’archive
    $stmt = $conn->prepare("SELECT * FROM archivage_dossiers WHERE id_dossier = ?");
    $stmt->execute([$id]);
    $d = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($d) {
        // Réinsérer dans dossiers
        $ins = $conn->prepare("
            INSERT INTO dossiers
              (nom, numero_reference, date_envoi, date_reception,
               id_destinateur, objet, id_utilisateur)
            VALUES
              (:nom, :numeref, :de, :dr, :idd, :obj, :iu)
        ");
        $ins->execute([
            ':nom'     => $d['nom'],
            ':numeref' => $d['numero_reference'],
            ':de'      => $d['date_envoi'],
            ':dr'      => $d['date_reception'],
            ':idd'     => $d['id_destinateur'],
            ':obj'     => $d['objet'],
            ':iu'      => $d['id_utilisateur'],
        ]);

        // Supprimer de l’archive
        $del = $conn->prepare("DELETE FROM archivage_dossiers WHERE id_dossier = ?");
        $del->execute([$id]);

        echo "<script>
                alert('Dossier désarchivé avec succès !');
                window.location.href = '../dossier.php';
              </script>";
        exit();
    } else {
        echo "<script>
                alert('Archive introuvable.');
                window.location.href = 'affichage_dossier.php';
              </script>";
        exit();
    }
} else {
    header('Location: affichage_dossier.php');
    exit();
}
