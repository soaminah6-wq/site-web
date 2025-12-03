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
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mes Quiz - Quizzeo</title>
    <style>
        *{margin:0;padding:0;box-sizing:border-box;}
        body{font-family:'Segoe UI',Tahoma,sans-serif;background:linear-gradient(135deg,#667eea,#764ba2);min-height:100vh;padding:20px;color:#333;}
        .container{background:#ffffff;border-radius:30px;box-shadow:0 30px 60px rgba(0,0,0,0.15);max-width:1000px;margin:0 auto;padding:60px;text-align:center;}
        .logo-section{margin-bottom:30px;}
        .logo-section img{height:100px;border-radius:0;margin-bottom:20px;}
        h1{background:linear-gradient(45deg,#667eea,#764ba2);-webkit-background-clip:text;-webkit-text-fill-color:transparent;font-size:2.5em;font-weight:800;margin-bottom:20px;}
        .btn-create{background:linear-gradient(45deg,#d13c3c,#a82e2e);color:white;padding:15px 35px;border:none;border-radius:25px;font-size:1.2em;font-weight:700;text-decoration:none;display:inline-block;margin:30px 0;transition:all 0.3s ease;box-shadow:0 10px 25px rgba(209,60,60,0.4);}
        .btn-create:hover{transform:translateY(-3px);box-shadow:0 15px 35px rgba(209,60,60,0.5);}
        .table-container{max-height:600px;overflow:auto;margin:40px 0;border-radius:20px;box-shadow:0 15px 40px rgba(0,0,0,0.1);}
        table{width:100%;border-collapse:collapse;background:white;border-radius:15px;overflow:hidden;}
        th{padding:20px 15px;background:linear-gradient(45deg,#f28c28,#d9771e);color:white;font-weight:700;text-transform:uppercase;letter-spacing:1px;font-size:1em;}
        td{padding:18px 15px;border-bottom:1px solid #f0f0f0;text-align:left;vertical-align:middle;}
        tr:hover{background:rgba(102,126,234,0.05);}
        tr:last-child td{border-bottom:none;}
        .status-badge{padding:6px 14px;border-radius:20px;font-size:0.85em;font-weight:600;}
        .status-en_cours{background:#e3f2fd;color:#1976d2;}
        .status-lance{background:#e8f5e8;color:#2e7d32;}
        .status-termine{background:#fff3e0;color:#f57c00;}
        .no-quiz{padding:80px;color:#888;}
        .no-quiz h3{font-size:2em;margin-bottom:15px;color:#666;}
        .back-link{color:#667eea;font-weight:700;font-size:1.1em;text-decoration:none;display:inline-block;margin-top:30px;}
        .back-link:hover{text-decoration:underline;}
        @media(max-width:768px){.container{padding:40px 30px;}th,td{padding:12px 10px;font-size:0.9em;}}
    </style>
</head>
<body>
    <div class="container">
        <div class="logo-section">
            <img src="../Capture d’écran 2025-12-01 102913.png" alt="Logo Quizzeo">
        </div>
        
        <h1> Mes Quiz</h1>

        <a href="quiz_create.php" class="btn-create">➕ Créer un nouveau quiz</a>

        <?php if (empty($quizzes)): ?>
            <div class="no-quiz">
                <h3>Aucun quiz créé</h3>
                <p>Créez votre premier questionnaire dès maintenant !</p>
            </div>
        <?php else: ?>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Titre</th>
                            <th>Description</th>
                            <th>Statut</th>
                            <th>Date création</th>
                          
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($quizzes as $quiz): ?>
                            <tr>
                                <td style="font-weight:600;font-size:1.05em;"><?= htmlspecialchars($quiz['titre']) ?></td>
                                <td style="color:#666;"><?= htmlspecialchars(substr($quiz['description'], 0, 60)) ?><?= strlen($quiz['description']) > 60 ? '...' : '' ?></td>
                                <td><span class="status-badge status-<?= $quiz['statut'] ?>"><?= ucfirst($quiz['statut']) ?></span></td>
                                <td style="color:#888;"><?= date('d/m/Y', strtotime($quiz['date_creation'])) ?></td>
                               
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>

        <a href="dashboard.php" class="back-link">Retour Dashboard</a>
    </div>
</body>
</html>
