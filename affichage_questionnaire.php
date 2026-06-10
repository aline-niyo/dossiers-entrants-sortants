<?php
include "connexion.php";
try {
    $affichage = $conn->query("
        SELECT q.id, u.username, q.date_naissance, q.province, q.school, q.father, q.mother
        FROM questionnairedemande q
        JOIN utilisateurs u ON q.id_utilisateur = u.id_utilisateur
        ORDER BY q.id DESC
    ");

    if ($affichage->rowCount() > 0) {
        echo "<div style='margin-top: 40px; background-color: white; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1);'>";
        echo "<h2 style='color: #007BFF; text-align: center;'>Liste des questionnaires enregistrés</h2>";
        echo "<table border='1' cellpadding='10' cellspacing='0' style='width:100%; border-collapse: collapse;'>";
        echo "<tr style='background-color: #007BFF; color: white;'>
                <th>Utilisateur</th>
                <th>Date de naissance</th>
                <th>Province</th>
                <th>École primaire</th>
                <th>Nom du père</th>
                <th>Nom de la mère</th>
                <th>Action</th>
              </tr>";

        while ($row = $affichage->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr>
                    <td>" . htmlspecialchars($row['username']) . "</td>
                    <td>" . htmlspecialchars($row['date_naissance']) . "</td>
                    <td>" . htmlspecialchars($row['province']) . "</td>
                    <td>" . htmlspecialchars($row['school']) . "</td>
                    <td>" . htmlspecialchars($row['father']) . "</td>
                    <td>" . htmlspecialchars($row['mother']) . "</td>
                    <td>
                        <a href='modifie_quetionnaire.php?id=" . $row['id'] . "' style='background-color: orange; color: white; padding: 5px 10px; border-radius: 4px; text-decoration: none;'>Modifier</a>
                    </td>
                  </tr>";
        }

        echo "</table></div>";
    } else {
        echo "<p style='text-align:center; margin-top:30px;'>Aucun questionnaire enregistré pour le moment.</p>";
    }
} catch (PDOException $e) {
    echo "<p style='color:red;'>Erreur d'affichage : " . $e->getMessage() . "</p>";
}
?>
