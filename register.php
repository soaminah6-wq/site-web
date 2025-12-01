<?php
require_once 'auth.php';
ensure_admin_exists();

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $role = $_POST['role'] ?? 'user';
    $firstname = $_POST['firstname'] ?? '';
    $lastname = $_POST['lastname'] ?? '';
    $captcha_answer = intval($_POST['captcha'] ?? 0);
    $expected = $_SESSION['captcha'][0] + $_SESSION['captcha'][1];

    if ($captcha_answer !== $expected) $errors[] = "Captcha incorrect.";
    if (!in_array($role, ['admin','ecole','entreprise','user'])) $errors[] = "Rôle invalide.";

    if (empty($errors)) {
        $res = create_user($email, $password, $role, $firstname, $lastname);
        if ($res === true) {
            $success = true;
        } else $errors[] = $res;
    }
    $_SESSION['captcha'] = [rand(1,9), rand(1,9)];
} else {
    $_SESSION['captcha'] = [rand(1,9), rand(1,9)];
}
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Inscription</title></head>
<body>
<h2>Inscription</h2>

<?php if ($success): ?>
  <p>Compte créé. <a href="login.php">Se connecter</a></p>
<?php else: ?>
  <?php foreach($errors as $e) echo "<p style='color:red;'>$e</p>"; ?>
  <form method="post">
    Prénom: <input name="firstname"><br>
    Nom: <input name="lastname"><br>
    Email: <input name="email" required><br>
    Mot de passe: <input type="password" name="password" required><br>
    Rôle:
    <select name="role">
      <option value="user">Utilisateur</option>
      <option value="ecole">École</option>
      <option value="entreprise">Entreprise</option>
    </select><br>
    Captcha : combien font <?= $_SESSION['captcha'][0] ?> + <?= $_SESSION['captcha'][1] ?> ?
    <input name="captcha" required><br>
    <button type="submit">S'inscrire</button>
  </form>
<?php endif; ?>
</body>
</html>
