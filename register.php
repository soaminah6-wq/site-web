<?php
require_once 'auth.php';

$errors = [];
$success = false;

// Génération d'un captcha simple
if (empty($_SESSION['captcha'])) {
    $_SESSION['captcha'] = [rand(1, 9), rand(1, 9)];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $firstname = $_POST['firstname'] ?? '';
    $lastname = $_POST['lastname'] ?? '';
    $role = $_POST['role'] ?? 'user';
    $captcha_answer = intval($_POST['captcha'] ?? 0);
    $captcha_expected = $_SESSION['captcha'][0] + $_SESSION['captcha'][1];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Email invalide.";
    }
    if (strlen($password) < 6) {
        $errors[] = "Le mot de passe doit faire au moins 6 caractères.";
    }
    if (!in_array($role, ['user', 'ecole', 'entreprise', 'admin'])) {
        $errors[] = "Rôle invalide.";
    }
    if ($captcha_answer !== $captcha_expected) {
        $errors[] = "Captcha incorrect.";
    }

    if (empty($errors)) {
        $res = create_user($email, $password, $role, $firstname, $lastname);
        if ($res === true) {
            $success = true;
        } else {
            $errors[] = $res;
        }
    }

    // Regénérer captcha après tentative
    $_SESSION['captcha'] = [rand(1, 9), rand(1, 9)];
}

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Inscription Quizzeo</title>
</head>
<body>
    <h2>Inscription</h2>
    <?php if ($success): ?>
        <p>Compte créé avec succès. <a href="login.php">Se connecter</a></p>
    <?php else: ?>
        <?php foreach ($errors as $error) {
            echo "<p style='color:red;'>$error</p>";
        } ?>
        <form method="post">
            Prénom:<br>
            <input type="text" name="firstname" value="<?=htmlspecialchars($_POST['firstname'] ?? '')?>" /><br><br>

            Nom:<br>
            <input type="text" name="lastname" value="<?=htmlspecialchars($_POST['lastname'] ?? '')?>" /><br><br>

            Email:<br>
            <input type="email" name="email" value="<?=htmlspecialchars($_POST['email'] ?? '')?>" required /><br><br>

            Mot de passe:<br>
            <input type="password" name="password" required /><br><br>

            Rôle:<br>
            <select name="role" required>
                <option value="user" <?= (($_POST['role'] ?? '')==='user') ? 'selected' : '' ?>>Utilisateur</option>
                <option value="ecole" <?= (($_POST['role'] ?? '')==='ecole') ? 'selected' : '' ?>>École</option>
                <option value="entreprise" <?= (($_POST['role'] ?? '')==='entreprise') ? 'selected' : '' ?>>Entreprise</option>
                <option value="admin" <?= (($_POST['role'] ?? '')==='admin') ? 'selected' : '' ?>>Administrateur</option>
            </select><br><br>

            Captcha: Combien font <?= $_SESSION['captcha'][0] ?> + <?= $_SESSION['captcha'][1] ?> ?<br>
            <input type="number" name="captcha" required /><br><br>

            <button type="submit">S'inscrire</button>
        </form>
    <?php endif; ?>
</body>
</html>

