<?php
require_once 'src/db.php'; // Utilise ta connexion existante

$message_ok = '';
$message_erreur = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $role = $_POST['role'] ?? '';
    $sujet = trim($_POST['sujet'] ?? '');
    $message = trim($_POST['message'] ?? '');

    $roles_valides = ['admin', 'ecole', 'entreprise', 'user'];

    if ($nom === '' || $email === '' || $sujet === '' || $message === '' || !in_array($role, $roles_valides)) {
        $message_erreur = "Tous les champs sont obligatoires et le rôle doit être valide.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message_erreur = "Adresse email invalide.";
    } else {
        $sql = "INSERT INTO contact (nom, email, role, sujet, message, date_envoi) 
                VALUES (:nom, :email, :role, :sujet, :message, NOW())";
        $stmt = $pdo->prepare($sql);
        $ok = $stmt->execute([
            ':nom' => $nom,
            ':email' => $email,
            ':role' => $role,
            ':sujet' => $sujet,
            ':message' => $message,
        ]);
        if ($ok) {
            $message_ok = "✅ Votre message a été envoyé avec succès !";
        } else {
            $message_erreur = "❌ Erreur lors de l'envoi.";
        }
    }
}
if ($message_ok) {
    // Redirige vers page de succès après 2 secondes
    echo '<script>setTimeout(function(){ 
        window.location.href = "src/dashboard.php"; 
    }, 2000);</script>';
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8" />
<title>Contact - Quizzeo</title>
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
    background: rgba(255,255,255,0.95);
    backdrop-filter: blur(10px);
    border-radius: 20px;
    box-shadow: 0 20px 40px rgba(0,0,0,0.1);
    max-width: 500px;
    width: 100%;
    padding: 40px;
    animation: slideUp 0.6s ease;
}
@keyframes slideUp {
    from { transform: translateY(50px); opacity: 0; }
    to { transform: translateY(0); opacity: 1; }
}
.logo-section {
    text-align: center;
    margin-bottom: 30px;
}
.logo-section img {
    height: 80px;
    border-radius: 50%;
    box-shadow: 0 10px 30px rgba(0,0,0,0.2);
    margin-bottom: 15px;
}
.logo-section h1 {
    background: linear-gradient(45deg, #667eea, #764ba2);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    font-size: 2.2em;
    font-weight: 700;
}
form {
    display: flex;
    flex-direction: column;
    gap: 20px;
}
label {
    font-weight: 600;
    color: #333;
    font-size: 0.95em;
}
input, select, textarea {
    padding: 15px;
    border: 2px solid #e1e5e9;
    border-radius: 12px;
    font-size: 1em;
    transition: all 0.3s ease;
    background: #f8f9fa;
}
input:focus, select:focus, textarea:focus {
    outline: none;
    border-color: #667eea;
    background: white;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    transform: translateY(-2px);
}
textarea {
    resize: vertical;
    min-height: 120px;
}
.btn-submit {
    padding: 18px;
    background: linear-gradient(45deg, #667eea, #764ba2);
    color: white;
    border: none;
    border-radius: 12px;
    font-size: 1.1em;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    text-transform: uppercase;
    letter-spacing: 1px;
}
.btn-submit:hover {
    transform: translateY(-3px);
    box-shadow: 0 15px 35px rgba(102, 126, 234, 0.4);
}
.message {
    padding: 15px;
    border-radius: 12px;
    margin-bottom: 20px;
    font-weight: 600;
    text-align: center;
    animation: fadeIn 0.5s ease;
}
@keyframes fadeIn {
    from { opacity: 0; transform: scale(0.95); }
    to { opacity: 1; transform: scale(1); }
}
.message-ok {
    background: linear-gradient(45deg, #d4edda, #c3e6cb);
    color: #155724;
    border: 2px solid #c3e6cb;
}
.message-erreur {
    background: linear-gradient(45deg, #f8d7da, #f5c6cb);
    color: #721c24;
    border: 2px solid #f5c6cb;
}
.footer {
    text-align: center;
    margin-top: 25px;
    color: #666;
    font-size: 0.9em;
}
@media (max-width: 480px) {
    .main-container { padding: 25px; margin: 10px; }
}
</style>
</head>
<body>
<div class="main-container">
    <div class="logo-section">
        <img src="Capture d’écran 2025-12-01 102913.png" alt="Logo Quizzeo" />
        <h1>Nous Contacter</h1>
    </div>

    <?php if ($message_ok): ?>
        <div class="message message-ok"><?= htmlspecialchars($message_ok) ?></div>
    <?php endif; ?>
    <?php if ($message_erreur): ?>
        <div class="message message-erreur"><?= htmlspecialchars($message_erreur) ?></div>
    <?php endif; ?>

    <form method="post" action="">
        <label for="nom">👤 Nom complet</label>
        <input type="text" id="nom" name="nom" value="<?= htmlspecialchars($_POST['nom'] ?? '') ?>" required>

        <label for="email">📧 Email</label>
        <input type="email" id="email" name="email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>

        <label for="role">🎭 Votre rôle</label>
        <select id="role" name="role" required>
            <option value="">Choisissez...</option>
            <option value="admin" <?= ($_POST['role'] ?? '') === 'admin' ? 'selected' : '' ?>>👑 Administrateur</option>
            <option value="ecole" <?= ($_POST['role'] ?? '') === 'ecole' ? 'selected' : '' ?>>🏫 École</option>
            <option value="entreprise" <?= ($_POST['role'] ?? '') === 'entreprise' ? 'selected' : '' ?>>🏢 Entreprise</option>
            <option value="user" <?= ($_POST['role'] ?? '') === 'user' ? 'selected' : '' ?>>👤 Utilisateur</option>
        </select>

        <label for="sujet">📝 Sujet</label>
        <input type="text" id="sujet" name="sujet" value="<?= htmlspecialchars($_POST['sujet'] ?? '') ?>" required>

        <label for="message">💬 Message</label>
        <textarea id="message" name="message" placeholder="Dites-nous en plus..." required><?= htmlspecialchars($_POST['message'] ?? '') ?></textarea>

        <button type="submit" class="btn-submit">🚀 Envoyer le message</button>
    </form>

    <div class="footer">
        <p>📱 Quizzeo - Plateforme d'évaluation moderne</p>
    </div>
</div>
</body>
</html>
