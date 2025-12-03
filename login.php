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

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Quizzeo</title>
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
            background: #ffffff;           /* BLANC PUR */
            border-radius: 30px;
            box-shadow: 0 30px 60px rgba(0,0,0,0.15);
            max-width: 650px;             /* Optimisé login */
            width: 100%;
            padding: 80px 60px;           /* Grand espace logo */
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
            height: 120px;                /* Même que accueil */
            border-radius: 0;             /* CARRÉ */
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
        .login-form {
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
        input[type="email"], input[type="password"] {
            padding: 18px;
            border: 2px solid #e1e5e9;
            border-radius: 15px;
            font-size: 1em;
            background: #f8f9fa;
            transition: all 0.3s ease;
            width: 100%;
        }
        input[type="email"]:focus, input[type="password"]:focus {
            outline: none;
            border-color: #667eea;
            background: white;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
            transform: translateY(-2px);
        }
        .btn-login {
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
        .btn-login:hover {
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
        .register-link {
            margin-top: 30px;
            color: #666;
            font-size: 1em;
        }
        .register-link a {
            color: #f28c28;
            text-decoration: none;
            font-weight: 700;
        }
        .register-link a:hover {
            text-decoration: underline;
        }
        .footer {
            margin-top: 40px;
            color: #888;
            font-size: 0.9em;
        }
        @media (max-width: 480px) {
            .main-container { padding: 60px 40px; }
            h1 { font-size: 2em; }
        }
    </style>
</head>
<body>
    <div class="main-container">
        <!-- LOGO -->
        <div class="logo-section">
            <img src="../Capture d’écran 2025-12-01 102913.png" alt="Logo Quizzeo">
            <h1>Se Connecter</h1>
            <p class="sous-titre">Accédez à votre espace Quizzeo en quelques secondes</p>
        </div>

        <!-- ERREURS -->
        <?php foreach($errors as $e): ?>
            <div class="error"><?= htmlspecialchars($e) ?></div>
        <?php endforeach; ?>

        <!-- FORMULAIRE -->
        <form method="post" class="login-form">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" placeholder="votre@email.com" required autofocus>

            <label for="password">Mot de passe</label>
            <input type="password" id="password" name="password" placeholder="Votre mot de passe" required>

            <button type="submit" class="btn-login"> Se connecter</button>
        </form>

        <!-- LIEN INSCRIPTION -->
        <div class="register-link">
            <p>Pas encore de compte ? <a href="register.php">Créer un compte</a></p>
        </div>

        <!-- FOOTER -->
        <div class="footer">
            <p> Quizzeo - Plateforme d'évaluation moderne</p>
        </div>
    </div>
</body>
</html>
