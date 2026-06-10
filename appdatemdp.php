<?php
$token = $_GET['token'];
?>

<form method="POST" action="update_password.php">
    
    <input type="hidden" name="token" value="<?php echo $token; ?>">

    <input type="password" name="password" placeholder="Nouveau mot de passe" required>

    <button type="submit">Modifier</button>

</form>
