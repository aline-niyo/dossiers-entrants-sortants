
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/destin.css">  
</head>
<body>
    <div class="container">
        <form method="POST" action="">
            <h1>Ajout du Destinateur</h1>

            <label for="date_acceuil">Date d'Accueil :</label>
            <input type="date" name="date" placeholder="date d'ajout" varequired value="<?php echo date('Y-m-d'); ?>" max="<?php echo date('Y-m-d'); ?>">

            <label for="nom_destinateur">Nom Destinateur :</label>
            <input type="text" id="nom_destinateur" name="nom_destinateur" placeholder="Nom du destinataire" required pattern="[A-Za-z\s]*">
            
            <label for="adresse_desti">Adresse :</label>
            <input type="text" name="adresse" placeholder="Votre adresse" required>
            
            <label for="numero_tele">Numéro de téléphone :</label>
            <input type="number" name="telephone" placeholder="Votre téléphone" required pattern="[0-9]+">
            
            <button type="submit" name="valider">Enregistrer</button>
        </form>
    </div>


  <!--affichages des destinateurs-->
  <div class="container">
    <h1>Liste des Destinataires</h1>

    <form action="" method="GET" class="search-container">
        <input type="text" name="search" placeholder="Rechercher un destinataire..." value="<?php echo htmlspecialchars($searchTerm); ?>">
        <button type="submit">Chercher</button>
    </form>

    <table>
        <thead>
            <tr>
                <th>Date d'Accueil</th>
                <th>Nom Destinateur</th>
                <th>Adresse</th>
                <th>Téléphone</th>
                <th colspan="2">Actions</th>
                <!-- <th><a href="destinateur.php" class="plus-button">+</a></th>  -->
            </tr>
        </thead>
        <tbody>
            <?php while ($destinateur = $affichageUtilisateur->fetch(PDO::FETCH_ASSOC)) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($destinateur["date"]); ?></td>
                    <td><?php echo htmlspecialchars($destinateur["nom_destinateur"]); ?></td>
                    <td><?php echo htmlspecialchars($destinateur["adresse"]); ?></td>
                    <td><?php echo htmlspecialchars($destinateur["telephone"]); ?></td>
                    <td>
                        <a href="#" onclick="confirmDelete(<?php echo $destinateur['id_destinateur']; ?>)" class="btn-supprimer">Supprimer</a>
                    </td>
                    <td>
                        <a href="modifie_destinateur.php?mod=<?php echo $destinateur["id_destinateur"]; ?>" class="btn-modifier">Modifier</a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<script>
function confirmDelete(destinateurId) {
    const confirmation = confirm("Êtes-vous sûr de vouloir supprimer ce destinataire ?");
    if (confirmation) {
        window.location.href = "destinateur.php?sup=" + destinateurId;
    }
}
</script>
</body>
</html>