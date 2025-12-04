<?php
require_once 'auth.php';
require_login();

if ($_SESSION['user']['role'] !== 'admin') {
    die("Accès réservé aux administrateurs");
}

global $pdo;

// Gestion de l'activation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_id'])) {
    $user_id = intval($_POST['user_id']);
    // Récupérer l'état actuel
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
<head><meta charset="utf-8"><title>Gestion des utilisateurs</title></head>
<body>
<h1>Gestion des utilisateurs</h1>
<table border="1" cellpadding="10">
<tr><th>ID</th><th>Email</th><th>Prénom</th><th>Nom</th><th>Rôle</th><th>Actif</th><th>Action</th></tr>
<?php foreach ($users as $u): ?>
<tr>
  <td><?= htmlspecialchars($u['id']) ?></td>
  <td><?= htmlspecialchars($u['email']) ?></td>
  <td><?= htmlspecialchars($u['prenom']) ?></td>
  <td><?= htmlspecialchars($u['nom']) ?></td>
  <td><?= htmlspecialchars($u['role']) ?></td>
  <td><?= $u['actif'] ? 'Oui' : 'Non' ?></td>
  <td>
    <form method="post" style="margin:0;">
      <input type="hidden" name="user_id" value="<?= $u['id'] ?>" />
      <button type="submit"><?= $u['actif'] ? 'Désactiver' : 'Activer' ?></button>
    </form>
  </td>
</tr>
<?php endforeach; ?>
</table>

<p><a href="dashboard.php">Retour au dashboard</a></p>
</body>
</html>

