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
    $questions = $_POST['questions'] ?? [];

    if ($titre === '') {
        $errors[] = "Le titre est obligatoire.";
    }

    if (empty($errors)) {
        // 1. Créer le quiz
        $stmt = $pdo->prepare("INSERT INTO quiz (titre, description, id_createur, statut) VALUES (?, ?, ?, 'en_cours')");
        if ($stmt->execute([$titre, $description, $user['id']])) {
            $quiz_id = $pdo->lastInsertId();
            
            // 2. Ajouter les questions avec TA structure
            foreach ($questions as $index => $q_data) {
                $question_texte = trim($q_data['texte'] ?? '');
                $rep1 = trim($q_data['reponses'][0] ?? '');
                $rep2 = trim($q_data['reponses'][1] ?? '');
                $rep3 = trim($q_data['reponses'][2] ?? '');
                $rep4 = trim($q_data['reponses'][3] ?? '');
                $bonne_rep = intval($q_data['bonne_reponse'] ?? 0);
                
                if ($question_texte && ($rep1 || $rep2 || $rep3 || $rep4)) {
                    // INSERT avec TA structure exacte
                    $stmt_q = $pdo->prepare("
                        INSERT INTO questions (id_quiz, question, reponse_1, reponse_2, reponse_3, reponse_4, bonne_reponse, points) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, 1)
                    ");
                    $stmt_q->execute([
                        $quiz_id, 
                        $question_texte, 
                        $rep1, $rep2, $rep3, $rep4, 
                        $bonne_rep
                    ]);
                }
            }
            $success = true;
        } else {
            $errors[] = "Erreur création quiz.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Créer quiz - Quizzeo</title>
    <style>
        /* Style Quizzeo complet (comme avant) */
        *{margin:0;padding:0;box-sizing:border-box;}
        body{font-family:'Segoe UI',Tahoma,sans-serif;background:linear-gradient(135deg,#667eea,#764ba2);min-height:100vh;padding:20px;display:flex;align-items:center;justify-content:center;}
        .container{background:#ffffff;border-radius:30px;box-shadow:0 30px 60px rgba(0,0,0,0.15);max-width:900px;width:100%;padding:60px;animation:slideUp 0.8s ease;text-align:center;}
        @keyframes slideUp{from{transform:translateY(80px);opacity:0;}to{transform:translateY(0);opacity:1;}}
        h1{font-size:2.5em;background:linear-gradient(45deg,#667eea,#764ba2);-webkit-background-clip:text;-webkit-text-fill-color:transparent;margin-bottom:30px;}
        input,textarea,select{width:100%;padding:15px;border:2px solid #e1e5e9;border-radius:15px;font-size:1em;margin:10px 0;background:#f8f9fa;transition:all 0.3s;}
        input:focus,textarea:focus,select:focus{outline:none;border-color:#667eea;background:white;box-shadow:0 0 0 4px rgba(102,126,234,0.1);}
        textarea{min-height:100px;resize:vertical;}
        .question-block{background:rgba(248,249,250,0.8);border-radius:20px;padding:25px;margin:20px 0;border-left:4px solid #f28c28;}
        .btn-add-question{background:linear-gradient(45deg,#f28c28,#d9771e);color:white;padding:12px 25px;border:none;border-radius:20px;font-weight:700;cursor:pointer;margin:15px 0;}
        .btn-submit{padding:20px 50px;background:linear-gradient(45deg,#d13c3c,#a82e2e);color:white;border:none;border-radius:25px;font-size:1.3em;font-weight:700;cursor:pointer;width:100%;text-transform:uppercase;}
        .message-success,.error{padding:20px;border-radius:15px;margin:20px 0;font-weight:600;text-align:center;}
        .message-success{background:#d4edda;color:#155724;border:2px solid #c3e6cb;}
        .error{background:#f8d7da;color:#721c24;border:2px solid #f5c6cb;}
    </style>
</head>
<body>
    <div class="container">
        <h1>🎓 Créer un quiz</h1>

        <?php if ($success): ?>
            <div class="message-success">
                ✅ Quiz "<strong><?= htmlspecialchars($titre) ?></strong>" créé avec succès !<br>
                <a href="quiz_list.php" style="color:#155724;">Voir mes quiz →</a>
            </div>
        <?php else: ?>
            <?php foreach ($errors as $error): ?>
                <div class="error"><?= htmlspecialchars($error) ?></div>
            <?php endforeach; ?>

            <form method="post">
                <input type="text" name="titre" placeholder="Titre du quiz *" value="<?= htmlspecialchars($_POST['titre'] ?? '') ?>" required>
                <textarea name="description" placeholder="Description"><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea>

                <h2 style="margin-top:40px;"> Questions</h2>
                <div id="questions-container">
                    <div class="question-block" data-index="0">
                        <input type="text" name="questions[0][texte]" placeholder="Question 1 ?" required>
                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;margin:15px 0;">
                            <input type="text" name="questions[0][reponses][0]" placeholder="Réponse 1">
                            <input type="text" name="questions[0][reponses][1]" placeholder="Réponse 2">
                            <input type="text" name="questions[0][reponses][2]" placeholder="Réponse 3">
                            <input type="text" name="questions[0][reponses][3]" placeholder="Réponse 4">
                        </div>
                        <select name="questions[0][bonne_reponse]" required>
                            <option value="">Bonne réponse :</option>
                            <option value="0">Réponse 1 ✓</option>
                            <option value="1">Réponse 2 ✓</option>
                            <option value="2">Réponse 3 ✓</option>
                            <option value="3">Réponse 4 ✓</option>
                        </select>
                    </div>
                </div>

                <button type="button" class="btn-add-question" onclick="addQuestion()">➕ Ajouter question</button>
                <button type="submit" class="btn-submit">Créer quiz complet</button>
            </form>
        <?php endif; ?>

        <a href="dashboard.php" style="color:#667eea;font-weight:700;margin-top:30px;display:inline-block;">← Dashboard</a>
    </div>

    <script>
        let qIndex = 1;
        function addQuestion() {
            const container = document.getElementById('questions-container');
            const div = document.createElement('div');
            div.className = 'question-block';
            div.dataset.index = qIndex;
            div.innerHTML = `
                <input type="text" name="questions[${qIndex}][texte]" placeholder="Question ${qIndex + 1} ?" required>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;margin:15px 0;">
                    <input type="text" name="questions[${qIndex}][reponses][0]" placeholder="Réponse 1">
                    <input type="text" name="questions[${qIndex}][reponses][1]" placeholder="Réponse 2">
                    <input type="text" name="questions[${qIndex}][reponses][2]" placeholder="Réponse 3">
                    <input type="text" name="questions[${qIndex}][reponses][3]" placeholder="Réponse 4">
                </div>
                <select name="questions[${qIndex}][bonne_reponse]" required>
                    <option value="">Bonne réponse :</option>
                    <option value="0">Réponse 1 ✓</option>
                    <option value="1">Réponse 2 ✓</option>
                    <option value="2">Réponse 3 ✓</option>
                    <option value="3">Réponse 4 ✓</option>
                </select>
            `;
            container.appendChild(div);
            qIndex++;
        }
    </script>
</body>
</html>
