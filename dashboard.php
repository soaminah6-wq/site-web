<?php
require_once 'auth.php';
require_login();

$user = $_SESSION['user'];
global $pdo;
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Dashboard - Quizzeo</title>
    <style>
        *{margin:0;padding:0;box-sizing:border-box;}
        body{font-family:'Segoe UI',sans-serif;background:linear-gradient(135deg,#667eea,#764ba2);min-height:100vh;padding:20px;color:#333;}
        .container{background:rgba(255,255,255,0.95);backdrop-filter:blur(10px);border-radius:20px;max-width:1200px;margin:0 auto;padding:40px;box-shadow:0 25px 50px rgba(0,0,0,0.15);}
        h1{background:linear-gradient(45deg,#667eea,#764ba2);-webkit-background-clip:text;-webkit-text-fill-color:transparent;font-size:2.8em;text-align:center;margin-bottom:20px;}
        .role-badge{padding:12px 25px;background:linear-gradient(45deg,#4CAF50,#45a049);color:white;border-radius:30px;display:inline-block;font-weight:700;font-size:1.1em;margin-bottom:20px;}
        .welcome{text-align:center;color:#666;font-size:1.3em;margin-bottom:40px;}
        .tabs{display:flex;justify-content:center;gap:15px;margin:40px 0;flex-wrap:wrap;}
        .tab-btn{padding:15px 30px;background:#e1e5e9;color:#666;border:none;border-radius:25px;font-size:1.1em;font-weight:600;cursor:pointer;transition:all 0.3s ease;}
        .tab-btn.active{background:linear-gradient(45deg,#4CAF50,#45a049);color:white;transform:translateY(-2px);}
        .quiz-section{display:none;}
        .quiz-section.active{display:block;}
        .quiz-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(350px,1fr));gap:25px;}
        .quiz-card{background:white;border-radius:20px;padding:30px;box-shadow:0 15px 35px rgba(0,0,0,0.1);transition:all 0.3s ease;}
        .quiz-card:hover{transform:translateY(-8px);box-shadow:0 25px 50px rgba(0,0,0,0.15);}
        .quiz-title{font-size:1.6em;font-weight:800;color:#333;margin-bottom:12px;}
        .quiz-desc{color:#666;line-height:1.5;margin-bottom:15px;}
        .quiz-meta{color:#888;font-size:0.95em;margin-bottom:20px;}
        .btn-quiz{display:inline-block;background:linear-gradient(45deg,#667eea,#764ba2);color:white;padding:15px 30px;border:none;border-radius:12px;font-size:1.1em;font-weight:700;text-decoration:none;transition:all 0.3s ease;}
        .btn-quiz:hover{transform:translateY(-3px);box-shadow:0 15px 35px rgba(102,126,234,0.4);}
        .no-quiz{text-align:center;padding:80px;color:#888;}
        .no-quiz h3{font-size:2em;margin-bottom:20px;}
        .admin-actions{display:flex;justify-content:center;gap:20px;flex-wrap:wrap;margin:40px 0;}
        .logout{text-align:center;margin-top:40px;}
        .logout a{color:#ff5722;font-weight:800;font-size:1.1em;text-decoration:none;}
    </style>
</head>
<body>
    <div class="container">
        <h1>🎓 Quizzeo Dashboard</h1>
        <div class="role-badge"><?= ucfirst($user['role']) ?></div>
        <p class="welcome">Bienvenue <?= htmlspecialchars($user['nom'] ?? $user['email'] ?? 'Utilisateur') ?> !</p>

        <?php if (in_array($user['role'], ['admin', 'ecole', 'entreprise'])): ?>
            <!-- ADMIN / ÉCOLE / ENTREPRISE -->
            <div class="admin-actions">
                <a href="quiz_create.php" class="btn-quiz">➕ Créer nouveau quiz</a>
                <a href="quiz_list.php" class="btn-quiz">📋 Mes quiz</a>
                <?php if ($user['role'] === 'admin'): ?>
                    <a href="admin_users.php" class="btn-quiz">👥 Gestion utilisateurs</a>
                    <a href="admin_contact.php" class="btn-quiz">📧 Messages contact</a>
                <?php endif; ?>
            </div>

        <?php else: ?>
            <!-- UTILISATEUR SIMPLE : ONGLETS ÉCOLE/ENTREPRISE -->
            <div class="tabs">
                <button class="tab-btn active" onclick="showTab('ecole')" id="ecole-tab">🏫 Quiz Écoles</button>
                <button class="tab-btn" onclick="showTab('entreprise')" id="entreprise-tab">🏢 Quiz Entreprises</button>
            </div>

            <!-- QUIZ ÉCOLES -->
            <div id="ecole-quiz" class="quiz-section active">
                <?php
                $stmt = $pdo->prepare("SELECT q.*, u.nom as createur FROM quiz q JOIN utilisateurs u ON q.id_createur = u.id WHERE u.role = 'ecole' AND q.statut IN ('lancé', 'terminé') ORDER BY q.titre");
                $stmt->execute();
                $ecole_quizzes = $stmt->fetchAll();
                ?>
                <?php if ($ecole_quizzes): ?>
                    <div class="quiz-grid">
                        <?php foreach ($ecole_quizzes as $quiz): ?>
                            <div class="quiz-card">
                                <div class="quiz-title"><?= htmlspecialchars($quiz['titre']) ?></div>
                                <div class="quiz-desc"><?= htmlspecialchars($quiz['description']) ?></div>
                                <div class="quiz-meta">
                                    <strong>🏫 <?= htmlspecialchars($quiz['createur']) ?></strong> • 
                                    <?= $quiz['statut'] === 'lancé' ? '🟢 Ouvert' : '🔴 Terminé' ?>
                                </div>
                                <a href="quiz_take.php?id_quiz=<?= $quiz['id'] ?>" class="btn-quiz">🚀 Passer le quiz</a>
                                <?php if ($quiz['code_partage']): ?>
                                    <p style="font-size:0.9em;color:#888;margin-top:10px;">
                                        📎 de>http://localhost/quizzeo/quiz_direct.php?code=<?= htmlspecialchars($quiz['code_partage']) ?></code>
                                    </p>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="no-quiz">
                        <h3>Aucun quiz école disponible</h3>
                        <p>Revenez bientôt pour de nouveaux questionnaires pédagogiques !</p>
                    </div>
                <?php endif; ?>
            </div>

            <!-- QUIZ ENTREPRISES -->
            <div id="entreprise-quiz" class="quiz-section">
                <?php
                $stmt = $pdo->prepare("SELECT q.*, u.nom as createur FROM quiz q JOIN utilisateurs u ON q.id_createur = u.id WHERE u.role = 'entreprise' AND q.statut IN ('lancé', 'terminé') ORDER BY q.titre");
                $stmt->execute();
                $entreprise_quizzes = $stmt->fetchAll();
                ?>
                <?php if ($entreprise_quizzes): ?>
                    <div class="quiz-grid">
                        <?php foreach ($entreprise_quizzes as $quiz): ?>
                            <div class="quiz-card">
                                <div class="quiz-title"><?= htmlspecialchars($quiz['titre']) ?></div>
                                <div class="quiz-desc"><?= htmlspecialchars($quiz['description']) ?></div>
                                <div class="quiz-meta">
                                    <strong>🏢 <?= htmlspecialchars($quiz['createur']) ?></strong> • 
                                    <?= $quiz['statut'] === 'lancé' ? '🟢 Ouvert' : '🔴 Terminé' ?>
                                </div>
                                <a href="quiz_take.php?id_quiz=<?= $quiz['id'] ?>" class="btn-quiz">🚀 Passer le quiz</a>
                                <?php if ($quiz['code_partage']): ?>
                                    <p style="font-size:0.9em;color:#888;margin-top:10px;">
                                        📎 de>http://localhost/quizzeo/quiz_direct.php?code=<?= htmlspecialchars($quiz['code_partage']) ?></code>
                                    </p>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="no-quiz">
                        <h3>Aucun quiz entreprise disponible</h3>
                        <p>Revenez bientôt pour de nouveaux questionnaires de satisfaction !</p>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <div class="logout">
            <a href="logout.php">🚪 Se déconnecter</a>
        </div>
    </div>

    <script>
        function showTab(type) {
            // Cacher toutes les sections
            document.querySelectorAll('.quiz-section').forEach(s => s.classList.remove('active'));
            // Désactiver tous les onglets
            document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
            // Activer onglet cliqué
            document.getElementById(type + '-tab').classList.add('active');
            // Montrer section
            document.getElementById(type + '-quiz').classList.add('active');
        }
    </script>
</body>
</html>
