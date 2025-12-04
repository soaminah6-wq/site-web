<?php
require_once 'auth.php';
require_login();

if ($_SESSION['user']['role'] !== 'admin') {
    die("Accès réservé aux administrateurs");
}

global $pdo;

// Gestion activation/désactivation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_id'])) {
    $user_id = intval($_POST['user_id']);
    $stmt = $pdo->prepare("SELECT actif FROM utilisateurs WHERE id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch();
    if ($user) {
        $new_status = $user['actif'] ? 0 : 1;
        $update = $pdo->prepare("UPDATE utilisateurs SET actif = ? WHERE id = ?");
        $update->execute([$new_status, $user_id]);
    }
    header("Location: admin_users.php");
    exit;
}

// Récupérer tous les utilisateurs
$stmt = $pdo->query("SELECT id, email, prenom, nom, role, actif FROM utilisateurs ORDER BY id");
$users = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Gestion des utilisateurs</title>
    <style>
        body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background: #f9fafb;
    margin: 20px;
    color: #333;
}

h1 {
    text-align: center;
    color: #2c3e50;
    margin-bottom: 30px;
    font-weight: 700;
    font-size: 2.5em;
}

table {
    width: 100%;
    border-collapse: collapse;
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    background: white;
    border-radius: 10px;
    overflow: hidden;
}

th, td {
    padding: 15px 20px;
    text-align: left;
}

th {
    background-color: #34495e;
    color: white;
    font-weight: 700;
}

tr:nth-child(even) {
    background-color: #f2f4f7;
}

tr:hover {
    background-color: #d6e6f5;
    transition: background-color 0.3s ease;
}

.btn-edit, .btn-toggle {
    border: none;
    padding: 8px 15px;
    border-radius: 6px;
    font-size: 0.9em;
    cursor: pointer;
    transition: all 0.3s ease;
    color: white;
    text-decoration: none;
    display: inline-block;
    text-align: center;
}

.btn-edit {
    background-color: #3498db;
}

.btn-edit:hover {
    background-color: #2980b9;
}

.btn-toggle {
    background-color: #e74c3c;
}

.btn-toggle:hover {
    background-color: #c0392b;
}

.btn-toggle.active {
    background-color: #27ae60;
}

.btn-toggle.active:hover {
    background-color: #229954;
}

form {
    display: inline;
}

a {
    color: #3498db;
    text-decoration: none;
}

a:hover {
    text-decoration: underline;
}

p {
    text-align: center;
    margin-top: 40px;
}

p a {
    background-color: #34495e;
    color: white;
    padding: 12px 30px;
    border-radius: 30px;
    font-weight: 600;
    transition: background-color 0.3s ease;
}

p a:hover {
    background-color: #2c3e50;
}

    </style>
</head>
<body>
<h1>👥 Gestion des utilisateurs</h1>
<table>
<tr>
    <th>ID</th><th>Email</th><th>Prénom</th><th>Nom</th><th>Rôle</th><th>Actif</th><th>Actions</th>
</tr>
<?php foreach ($users as $u): ?>
<tr>
    <td><?= htmlspecialchars($u['id']) ?></td>
    <td><?= htmlspecialchars($u['email']) ?></td>
    <td><?= htmlspecialchars($u['prenom']) ?></td>
    <td><?= htmlspecialchars($u['nom']) ?></td>
    <td><?= ucfirst(htmlspecialchars($u['role'])) ?></td>
    <td><?= $u['actif'] ? '<span style="color:green">✅ Oui</span>' : '<span style="color:red">❌ Non</span>' ?></td>
    <td>
        <!-- BOUTON MODIFIER ✏️ -->
        <a href="admin_user_edit.php?id=<?= $u['id'] ?>" class="btn-edit" title="Modifier">
            ✏️ Modifier
        </a>
        <!-- BOUTON ACTIVER/DÉSACTIVER -->
        <form method="post" style="display:inline; margin:0;">
            <input type="hidden" name="user_id" value="<?= $u['id'] ?>" />
            <button type="submit" class="btn-toggle" onclick="return confirm('<?= $u['actif'] ? 'Désactiver' : 'Activer' ?> cet utilisateur ?')">
                <?= $u['actif'] ? '❌ Désactiver' : '✅ Activer' ?>
            </button>
        </form>
    </td>
</tr>
<?php endforeach; ?>
</table>

<p><a href="dashboard.php" style="display:inline-block; margin-top:20px; padding:10px 20px; background:#007cba; color:white; text-decoration:none; border-radius:5px;">🏠 Retour Dashboard</a></p>
</body>
</html>
