<?php
require_once 'auth.php';
require_login();

if ($_SESSION['user']['role'] !== 'admin') {
    die("Accès réservé aux administrateurs");
}

global $pdo;
$user_id = $_GET['id'] ?? 0;

if (!$user_id) {
    header("Location: admin_users.php");
    exit;
}

// Récupérer l'utilisateur
$stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

if (!$user) {
    die("Utilisateur introuvable");
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>✏️ Modifier utilisateur - Quizzeo</title>
    <style>
        /* Colle le CSS ci-dessus ici */
    </style>
</head>
<body>
    <div class="container">
        <h1>✏️ Modifier <?= htmlspecialchars(($user['prenom'] ?? '') . ' ' . ($user['nom'] ?? '')) ?></h1>
        
        <form method="post" action="admin_user_edit.php?id=<?= $user_id ?>">
            <div class="form-group">
                <label for="email">📧 Email</label>
                <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
            </div>
            
            <div class="form-group">
                <label for="prenom">👤 Prénom</label>
                <input type="text" id="prenom" name="prenom" value="<?= htmlspecialchars($user['prenom'] ?? '') ?>">
            </div>
            
            <div class="form-group">
                <label for="nom">👥 Nom</label>
                <input type="text" id="nom" name="nom" value="<?= htmlspecialchars($user['nom'] ?? '') ?>">
            </div>
            
            <div class="form-group">
                <label for="role">🎭 Rôle</label>
                <select id="role" name="role">
                    <option value="admin" <?= $user['role']=='admin' ? 'selected' : '' ?>>👑 Admin</option>
                    <option value="ecole" <?= $user['role']=='ecole' ? 'selected' : '' ?>>🏫 École</option>
                    <option value="entreprise" <?= $user['role']=='entreprise' ? 'selected' : '' ?>>🏢 Entreprise</option>
                    <option value="utilisateur" <?= $user['role']=='utilisateur' ? 'selected' : '' ?>>👤 Utilisateur</option>
                </select>
            </div>
            
            <div class="buttons">
                <button type="submit" class="btn btn-save">💾 Enregistrer les modifications</button>
                <a href="admin_users.php" class="btn btn-cancel">❌ Annuler</a>
            </div>
        </form>
    </div>
</body>
</html>
