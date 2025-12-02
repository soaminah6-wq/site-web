<?php
require_once 'auth.php';
require_login();

$user = $_SESSION['user'];
if (!in_array($user['role'], ['admin', 'Ecole', 'ecole', 'Entreprise', 'entreprise'])) {

    die('Accès interdit.');
}

global $pdo;

$id_quiz = intval($_GET['id_quiz'] ?? 0);
if ($id_quiz <= 0) {
    die("ID quiz invalide");
}

// Suppression
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    $stmt = $pdo->prepare("DELETE FROM questions WHERE id = ? AND id_quiz = ?");
    $stmt->execute([$delete_id, $id_quiz]);
    header("Location: questions_manage.php?id_quiz=$id_quiz");
    exit;
}

$errors = [];
$success = false;

// Modification
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_id'])) {
    $edit_id = intval($_POST['edit_id']);
    $question = trim($_POST['question']);
    $reponse_1 = trim($_POST['reponse_1']);
    $reponse_2 = trim($_POST['reponse_2']);
    $reponse_3 = trim($_POST['reponse_3']);
    $reponse_4 = trim($_POST['reponse_4']);
    $bonne_reponse = intval($_POST['bonne_reponse']);
    $points = intval($_POST['points']);

    if ($question === '' || $reponse_1 === '' || $reponse_2 === '') {
        $errors[] = "Les champs question, réponse 1 et réponse 2 sont obligatoires.";
    }
    if ($bonne_reponse < 1 || $bonne_reponse > 4) {
        $errors[] = "La bonne réponse doit être entre 1 et 4.";
    }

    if (empty($errors)) {
        $update = $pdo->prepare("UPDATE questions SET question=?, reponse_1=?, reponse_2=?, reponse_3=?, reponse_4=?, bonne_reponse=?, points=? WHERE id=? AND id_quiz=?");
        $update->execute([$question, $reponse_1, $reponse_2, $reponse_3, $reponse_4, $bonne_reponse, $points, $edit_id, $id_quiz]);
        $success = true;
    }
}

// Ajout
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['edit_id'])) {
    $question = trim($_POST['question']);
    $reponse_1 = trim($_POST['reponse_1']);
    $reponse_2 = trim($_POST['reponse_2']);
    $reponse_3 = trim($_POST['reponse_3']);
    $reponse_4 = trim($_POST['reponse_4']);
    $bonne_reponse = intval($_POST['bonne_reponse']);
    $points = intval($_POST['points']);

    if ($question === '' || $reponse_1 === '' || $reponse_2 === '') {
        $errors[] = "Les champs question, réponse 1 et réponse 2 sont obligatoires.";
    }
    if ($bonne_reponse < 1 || $bonne_reponse > 4) {
        $errors[] = "La bonne réponse doit être entre 1 et 4.";
    }

    if (empty($errors)) {
        $insert = $pdo->prepare("INSERT INTO questions (id_quiz, question, reponse_1, reponse_2, reponse_3, reponse_4, bonne_reponse, points)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $insert->execute([$id_quiz, $question, $reponse_1, $reponse_2, $reponse_3, $reponse_4, $bonne_reponse, $points]);
        $success = true;
    }
}

// Liste des questions
$stmt = $pdo->prepare("SELECT * FROM questions WHERE id_quiz = ? ORDER BY id");
$stmt->execute([$id_quiz]);
$questions = $stmt->fetchAll();

$edit_question = null;
if (isset($_GET['edit_id'])) {
    $edit_id = intval($_GET['edit_id']);
    $stmtEdit = $pdo->prepare("SELECT * FROM questions WHERE id = ? AND id_quiz = ?");
    $stmtEdit->execute([$edit_id, $id_quiz]);
    $edit_question = $stmtEdit->fetch();
}
?>

<!DOCTYPE html>
<html>
<head><meta charset="utf-8"><title>Gestion des questions</title></head>
<body>
<h1>Gestion des questions - Quiz #<?= $id_quiz ?></h1>

<?php if ($success): ?>
    <p style="color:green;">Opération réussie.</p>
<?php endif; ?>

<?php foreach ($errors as $error): ?>
    <p style="color:red;"><?= htmlspecialchars($error) ?></p>
<?php endforeach; ?>

<?php if ($edit_question): ?>
    <h2>Modifier une question</h2>
    <form method="post">
        <input type="hidden" name="edit_id" value="<?= $edit_question['id'] ?>">
        <label>Question :</label><br>
        <textarea name="question" required><?= htmlspecialchars($edit_question['question']) ?></textarea><br><br>
        <label>Réponse 1 :</label><br>
        <input type="text" name="reponse_1" value="<?= htmlspecialchars($edit_question['reponse_1']) ?>" required><br><br>
        <label>Réponse 2 :</label><br>
        <input type="text" name="reponse_2" value="<?= htmlspecialchars($edit_question['reponse_2']) ?>" required><br><br>
        <label>Réponse 3 :</label><br>
        <input type="text" name="reponse_3" value="<?= htmlspecialchars($edit_question['reponse_3']) ?>"><br><br>
        <label>Réponse 4 :</label><br>
        <input type="text" name="reponse_4" value="<?= htmlspecialchars($edit_question['reponse_4']) ?>"><br><br>
        <label>Bonne réponse (1 à 4) :</label><br>
        <input type="number" name="bonne_reponse" min="1" max="4" value="<?= htmlspecialchars($edit_question['bonne_reponse']) ?>" required><br><br>
        <label>Points :</label><br>
        <input type="number" name="points" min="1" value="<?= htmlspecialchars($edit_question['points']) ?>" required><br><br>
        <button type="submit">Modifier</button>
    </form>
    <p><a href="questions_manage.php?id_quiz=<?= $id_quiz ?>">Annuler</a></p>

<?php else: ?>

    <h2>Ajouter une nouvelle question</h2>
    <form method="post">
        <label>Question :</label><br>
        <textarea name="question" required><?= htmlspecialchars($_POST['question'] ?? '') ?></textarea><br><br>
        <label>Réponse 1 :</label><br>
        <input type="text" name="reponse_1" value="<?= htmlspecialchars($_POST['reponse_1'] ?? '') ?>" required><br><br>
        <label>Réponse 2 :</label><br>
        <input type="text" name="reponse_2" value="<?= htmlspecialchars($_POST['reponse_2'] ?? '') ?>" required><br><br>
        <label>Réponse 3 :</label><br>
        <input type="text" name="reponse_3" value="<?= htmlspecialchars($_POST['reponse_3'] ?? '') ?>"><br><br>
        <label>Réponse 4 :</label><br>
        <input type="text" name="reponse_4" value="<?= htmlspecialchars($_POST['reponse_4'] ?? '') ?>"><br><br>
        <label>Bonne réponse (1 à 4) :</label><br>
        <input type="number" name="bonne_reponse" min="1" max="4" value="<?= htmlspecialchars($_POST['bonne_reponse'] ?? '1') ?>" required><br><br>
        <label>Points :</label><br>
        <input type="number" name="points" min="1" value="<?= htmlspecialchars($_POST['points'] ?? '1') ?>" required><br><br>
        <button type="submit">Ajouter</button>
    </form>

<?php endif; ?>

<h2>Liste des questions existantes</h2>
<ul>
    <?php foreach ($questions as $q): ?>
        <li>
            <strong><?= htmlspecialchars($q['question']) ?></strong>
            <a href="questions_manage.php?id_quiz=<?= $id_quiz ?>&edit_id=<?= $q['id'] ?>">Modifier</a>
            <a href="questions_manage.php?id_quiz=<?= $id_quiz ?>&delete_id=<?= $q['id'] ?>" onclick="return confirm('Confirmer la suppression ?')">Supprimer</a>
        </li>
    <?php endforeach; ?>
</ul>

<p><a href="quiz_list.php">Retour à la liste des quiz</a></p>
</body>
</html>
