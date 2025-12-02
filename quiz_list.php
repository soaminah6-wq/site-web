<?php
require_once 'auth.php';
require_login();

$user = $_SESSION['user'];
if (!in_array($user['role'], ['ecole', 'entreprise'])) {
    die('Accès réservé aux écoles et entreprises');
}

global $pdo;

$stmt = $pdo->prepare("SELECT * FROM quiz WHERE id_createur = ? ORDER BY date_creation DESC");
$stmt->execute([$user['id']]);
$quizzes = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><title>Mes Quiz</title></head>
<body>
<h1>Mes Quiz</h1>
<a href="quiz_create.php">Créer un nouveau quiz</a>
<?php if(empty($quizzes)): ?>
    <p>Aucun quiz créé.</p>
<?php else: ?>
    <table border="1" cellpadding="10">
        <tr><th>Titre</th><th>Statut</th><th>Date de création</th></tr>
        <?php foreach($quizzes as $quiz): ?>
            <tr>
                <td><?=htmlspecialchars($quiz['titre'])?></td>
                <td><?=htmlspecialchars($quiz['statut'])?></td>
                <td><?=htmlspecialchars($quiz['date_creation'])?></td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php endif; ?>
<p><a href="dashboard.php">Retour au dashboard</a></p>
</body>
</html>
