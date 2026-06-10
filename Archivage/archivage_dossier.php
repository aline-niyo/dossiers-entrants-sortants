<?php
session_start();

// 1) Sécurité : redirection si non connecté
if (!isset($_SESSION['connecté'])) {
    header('Location: ../index.php');
    exit();
}

// 2) Vérification des rôles autorisés
$roles = explode(',', $_SESSION['roles']);
if (!array_intersect(['secretaire_executif','secretaire','secretaire_interim'], $roles)) {
    header('HTTP/1.1 403 Forbidden');
    echo "Accès refusé";
    exit();
}

// 3) Inclure la connexion (remontée d’un niveau dans l’arborescence)
require_once __DIR__ . '/../connexion.php';

if (isset($_GET['archiver'])) {
    $id_dossier = intval($_GET['archiver']);

    // 4) Récupérer le dossier à archiver
    $stmt = $conn->prepare("SELECT * FROM dossiers WHERE id_dossier = ?");
    $stmt->execute([$id_dossier]);
    $dossier = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($dossier) {
        // 5) Insérer dans la table d’archivage
        $ins = $conn->prepare("
            INSERT INTO archivage_dossiers
              (nom, numero_reference, date_envoi, date_reception,
               id_destinateur, objet, id_utilisateur, archived_at)
            VALUES
              (:nom, :numeref, :date_envoi, :date_reception,
               :iddest, :objet, :iduser, NOW())
        ");
        $ins->execute([
            ':nom'            => $dossier['nom'],
            ':numeref'        => $dossier['numero_reference'],
            ':date_envoi'     => $dossier['date_envoi'],
            ':date_reception' => $dossier['date_reception'],
            ':iddest'         => $dossier['id_destinateur'],
            ':objet'          => $dossier['objet'],
            ':iduser'         => $dossier['id_utilisateur'],
        ]);

        // 6) Puis supprimer de la table principale
        $del = $conn->prepare("DELETE FROM dossiers WHERE id_dossier = ?");
        $del->execute([$id_dossier]);

        // 7) Retour à la liste avec message
        echo "<script>
                alert('Dossier archivé avec succès !');
                window.location.href = affichage/affichage_dossier.php';
              </script>";
        exit();
    } else {
        // Dossier introuvable
        echo "<script>
                alert('Dossier introuvable, impossible d\\'archiver.');
                window.location.href = Archivage/affichage_dossier.php';
              </script>";
        exit();
    }
} else {
    // appel direct sans paramètre
   // header('Location: /affichage_dossier.php');
    exit();
}
?>