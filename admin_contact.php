<?php
require_once 'auth.php';
require_login();
if ($_SESSION['user']['role'] !== 'admin') die('Accès admin seulement');

global $pdo;
$stmt = $pdo->query("SELECT * FROM contact ORDER BY date_envoi DESC");
$messages = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head><title>Messages Contact</title></head>
<body>
<h1> Messages Contact (<?= count($messages) ?>)</h1>
<?php foreach ($messages as $msg): ?>
    <div style="border:1px solid #ccc; padding:20px; margin:10px;">
        <h3><?= htmlspecialchars($msg['sujet']) ?></h3>
        <p><strong><?= htmlspecialchars($msg['nom']) ?></strong> - <?= htmlspecialchars($msg['role']) ?></p>
        <p><?= htmlspecialchars($msg['email']) ?></p>
        <p><?= nl2br(htmlspecialchars($msg['message'])) ?></p>
        <small><?= $msg['date_envoi'] ?></small>
    </div>
<?php endforeach; ?>
</body>
</html>
