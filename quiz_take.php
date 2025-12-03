<?php
require_once 'auth.php';
// PAS require_login() → accessible à tous

global $pdo;
$id_quiz = $_GET['id'] ?? 0;
$quiz = null;
$questions = [];

if ($id_quiz) {
    $stmt = $pdo->prepare("SELECT * FROM quiz WHERE id = ? AND statut IN ('en_cours', 'lancé')");
    $stmt->execute([$id_quiz]);
    $quiz = $stmt->fetch();

    if ($quiz) {
        $stmt = $pdo->prepare("SELECT * FROM questions WHERE id_quiz = ? ORDER BY id");
        $stmt->execute([$id_quiz]);
        $questions = $stmt->fetchAll();
    }
}

if (!$quiz || empty($questions)) {
    die('Quiz introuvable ou pas encore prêt');
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($quiz['titre']) ?> - Quizzeo</title>
    <style>
        /* Styles conservés */
        body{font-family:'Segoe UI',sans-serif;background:linear-gradient(135deg,#667eea,#764ba2);min-height:100vh;padding:20px;}
        .container{background:#fff;max-width:800px;margin:auto;border-radius:25px;padding:50px;box-shadow:0 25px 50px rgba(0,0,0,0.15);}
        h1{font-size:2.5em;color:#667eea;text-align:center;margin-bottom:30px;}
        .question{margin:30px 0;padding:25px;background:#f8f9fa;border-radius:15px;}
        .question h3{font-size:1.4em;margin-bottom:20px;color:#333;}
        .reponses label{display:block;margin-bottom:12px;cursor:pointer;}
        input[type="checkbox"]{display:none;}
        .reponse-btn{
            padding:15px;
            border:2px solid #e1e5e9;
            border-radius:12px;
            font-size:1.1em;
            transition:all 0.3s;
            display:inline-block;
            width:100%;
            box-sizing:border-box;
        }
        .reponse-btn:hover{background:#667eea;color:white;}
        input[type="checkbox"]:checked + .reponse-btn{
            background:#667eea;
            color:white;
            border-color:#667eea;
        }
        .btn-submit{
            background:linear-gradient(45deg,#d13c3c,#a82e2e);
            color:white;
            padding:20px 50px;
            border:none;
            border-radius:25px;
            font-size:1.2em;
            font-weight:700;
            cursor:pointer;
            width:100%;
            margin-top:30px;
        }
    </style>
</head>
<body>
<div class="container">
    <h1><?= htmlspecialchars($quiz['titre']) ?></h1>
    <p style="text-align:center;color:#666;font-size:1.1em;"><?= htmlspecialchars($quiz['description']) ?></p>

    <form method="post" action="quiz_result.php">
        <input type="hidden" name="id_quiz" value="<?= $id_quiz ?>">
        <?php foreach($questions as $index => $q): ?>
            <div class="question">
                <h3><?= $index + 1 ?>. <?= htmlspecialchars($q['question']) ?></h3>
                <div class="reponses">
                    <label>
                        <input type="checkbox" name="reponse[<?= $q['id'] ?>][]" value="0">
                        <span class="reponse-btn"><?= htmlspecialchars($q['reponse_1']) ?></span>
                    </label>
                    <label>
                        <input type="checkbox" name="reponse[<?= $q['id'] ?>][]" value="1">
                        <span class="reponse-btn"><?= htmlspecialchars($q['reponse_2']) ?></span>
                    </label>
                    <label>
                        <input type="checkbox" name="reponse[<?= $q['id'] ?>][]" value="2">
                        <span class="reponse-btn"><?= htmlspecialchars($q['reponse_3']) ?></span>
                    </label>
                    <label>
                        <input type="checkbox" name="reponse[<?= $q['id'] ?>][]" value="3">
                        <span class="reponse-btn"><?= htmlspecialchars($q['reponse_4']) ?></span>
                    </label>
                </div>
            </div>
        <?php endforeach; ?>
        <button type="submit" class="btn-submit">Soumettre mes réponses</button>
    </form>
</div>
</body>
</html>
