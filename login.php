<?php
require_once 'auth.php';

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $res = login_user($email, $password);
    if ($res === true) {
        header('Location: dashboard.php');
        exit;
    } else $errors[] = $res;
}
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Connexion</title></head>
<body>
<h2>Connexion</h2>
<?php foreach($errors as $e) echo "<p style='color:red;'>$e</p>"; ?>
<form method="post">
  Email: <input name="email" required><br>
  Mot de passe: <input type="password" name="password" required><br>
  <button type="submit">Se connecter</button>
</form>
<p><a href="register.php">Créer un compte</a></p>
</body>
</html>
