<?php
require_once 'auth.php';
require_login();

$user = $_SESSION['user'];
if (!in_array($user['role'], ['ecole', 'entreprise'])) {
    die('Accès réservé aux écoles et entreprises');
}

global $pdo;

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = trim($_POST['titre'] ?? '');
    $description = trim($_POST['description'] ?? '');

    if ($titre === '') {
        $errors[] = "Le titre est obligatoire.";
    }
    if (empty($errors)) {
        $stmt = $pdo->prepare("INSERT INTO quiz (titre, description, id_createur, statut) VALUES (?, ?, ?, 'en_cours')");
        if ($stmt->execute([$titre, $description, $user['id']])) {
            $success = true;
        } else {
            $errors[] = "Erreur lors de la création du quiz.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><title>Créer un quiz</title></head>
<body>
<h1>Créer un nouveau quiz</h1>

<?php if ($success): ?>
    <p>Quiz créé avec succès. <a href="quiz_list.php">Voir mes quiz</a></p>
<?php else: ?>
    <?php foreach ($errors as $error): ?>
        <p style="color:red;"><?=htmlspecialchars($error)?></p>
    <?php endforeach; ?>

    <form method="post">
        Titre:<br>
        <input type="text" name="titre" value="<?=htmlspecialchars($_POST['titre'] ?? '')?>" required><br><br>

        Description:<br>
        <textarea name="description"><?=htmlspecialchars($_POST['description'] ?? '')?></textarea><br><br>

        <button type="submit">Créer</button>
    </form>
<?php endif; ?>

<p><a href="dashboard.php">Retour au dashboard</a></p>
</body>
</html>
