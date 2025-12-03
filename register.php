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
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - Quizzeo</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .main-container {
            background: #ffffff;
            border-radius: 30px;
            box-shadow: 0 30px 60px rgba(0,0,0,0.15);
            max-width: 650px;
            width: 100%;
            padding: 80px 60px;
            animation: slideUp 0.8s ease;
            text-align: center;
        }
        @keyframes slideUp {
            from { transform: translateY(80px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        .logo-section {
            margin-bottom: 40px;
        }
        .logo-section img {
            height: 120px;
            border-radius: 0;
            box-shadow: none;
            margin-bottom: 25px;
        }
        .logo-section h1 {
            background: linear-gradient(45deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            font-size: 2.5em;
            font-weight: 800;
            margin-bottom: 10px;
        }
        .sous-titre {
            color: #666;
            font-size: 1.1em;
            margin-bottom: 30px;
            line-height: 1.5;
        }
        .register-form {
            display: flex;
            flex-direction: column;
            gap: 25px;
        }
        label {
            font-weight: 600;
            color: #333;
            font-size: 1em;
            text-align: left;
        }
        input, select {
            padding: 18px;
            border: 2px solid #e1e5e9;
            border-radius: 15px;
            font-size: 1em;
            background: #f8f9fa;
            transition: all 0.3s ease;
            width: 100%;
        }
        input:focus, select:focus {
            outline: none;
            border-color: #667eea;
            background: white;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
            transform: translateY(-2px);
        }
        .captcha-row {
            display: flex;
            gap: 15px;
            align-items: center;
            flex-wrap: wrap;
        }
        .captcha-question {
            background: #f8f9fa;
            padding: 18px;
            border-radius: 15px;
            font-size: 1.2em;
            font-weight: 700;
            color: #333;
            min-width: 150px;
        }
        .btn-register {
            padding: 22px;
            background: linear-gradient(45deg, #f28c28, #d9771e);  /* ORANGE LOGO */
            color: white;
            border: none;
            border-radius: 25px;
            font-size: 1.3em;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
            box-shadow: 0 10px 30px rgba(242, 140, 40, 0.4);
            margin-top: 10px;
        }
        .btn-register:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(242, 140, 40, 0.5);
        }
        .error {
            background: linear-gradient(45deg, #f8d7da, #f5c6cb);
            color: #721c24;
            border: 2px solid #f5c6cb;
            padding: 15px;
            border-radius: 15px;
            margin-bottom: 25px;
            font-weight: 600;
            animation: shake 0.5s ease;
        }
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }
        .success {
            background: linear-gradient(45deg, #d4edda, #c3e6cb);
            color: #155724;
            border: 2px solid #c3e6cb;
            padding: 20px;
            border-radius: 20px;
            margin-bottom: 25px;
            font-weight: 600;
        }
        .login-link {
            margin-top: 30px;
            color: #666;
            font-size: 1em;
        }
        .login-link a {
            color: #f28c28;
            text-decoration: none;
            font-weight: 700;
        }
        .footer {
            margin-top: 40px;
            color: #888;
            font-size: 0.9em;
        }
        @media (max-width: 480px) {
            .main-container { padding: 60px 40px; }
            h1 { font-size: 2em; }
            .captcha-row { flex-direction: column; align-items: stretch; }
        }
    </style>
</head>
<body>
    <div class="main-container">
        <!-- LOGO -->
        <div class="logo-section">
            <img src="../Capture d’écran 2025-12-01 102913.png" alt="Logo Quizzeo">
            <h1>Créer un Compte</h1>
            <p class="sous-titre">Rejoignez Quizzeo en 1 minute et commencez à créer vos quiz !</p>
        </div>

        <?php if ($success): ?>
            <div class="success">
                ✅ Compte créé avec succès ! 
                <a href="login.php" style="color:#155724;font-weight:700;">Se connecter maintenant →</a>
            </div>
        <?php else: ?>
            <!-- ERREURS -->
            <?php foreach ($errors as $error): ?>
                <div class="error"><?= htmlspecialchars($error) ?></div>
            <?php endforeach; ?>

            <!-- FORMULAIRE INSCRIPTION -->
            <form method="post" class="register-form">
                <label for="firstname"> Prénom</label>
                <input type="text" id="firstname" name="firstname" value="<?= htmlspecialchars($_POST['firstname'] ?? '') ?>" required>

                <label for="lastname"> Nom</label>
                <input type="text" id="lastname" name="lastname" value="<?= htmlspecialchars($_POST['lastname'] ?? '') ?>" required>

                <label for="email"> Email</label>
                <input type="email" id="email" name="email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" placeholder="votre@email.com" required autofocus>

                <label for="password"> Mot de passe</label>
                <input type="password" id="password" name="password" placeholder="Minimum 6 caractères" required>

                <label for="role"> Votre rôle</label>
                <select id="role" name="role" required>
                    <option value="user" <?= (($_POST['role'] ?? '') === 'user') ? 'selected' : '' ?>>👤 Utilisateur</option>
                    <option value="ecole" <?= (($_POST['role'] ?? '') === 'ecole') ? 'selected' : '' ?>>🏫 École</option>
                    <option value="entreprise" <?= (($_POST['role'] ?? '') === 'entreprise') ? 'selected' : '' ?>>🏢 Entreprise</option>
                    <option value="admin" <?= (($_POST['role'] ?? '') === 'admin') ? 'selected' : '' ?>>👑 Administrateur</option>
                </select>

                <label>CAPTCHA : Combien font <?= $_SESSION['captcha'][0] ?> + <?= $_SESSION['captcha'][1] ?> ?</label>
                <div class="captcha-row">
                    <div class="captcha-question"><?= $_SESSION['captcha'][0] ?> + <?= $_SESSION['captcha'][1] ?> =</div>
                    <input type="number" name="captcha" placeholder="?" min="2" max="18" required style="flex:1;">
                </div>

                <button type="submit" class="btn-register"> S'inscrire</button>
            </form>
        <?php endif; ?>

        <!-- LIEN CONNEXION -->
        <div class="login-link">
            <p>Déjà un compte ? <a href="login.php">Se connecter</a></p>
        </div>

        <!-- FOOTER -->
        <div class="footer">
            <p> Quizzeo - Plateforme d'évaluation moderne</p>
        </div>
    </div>
</body>
</html>
