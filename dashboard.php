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
        .container{background:#ffffff;border-radius:30px;box-shadow:0 30px 60px rgba(0,0,0,0.15);max-width:1200px;margin:0 auto;padding:80px 60px;text-align:center;animation:slideUp 0.8s ease;}
        @keyframes slideUp{from{transform:translateY(80px);opacity:0;}to{transform:translateY(0);opacity:1;}}
        .logo-section{margin-bottom:30px;}
        .logo-section img{height:120px;border-radius:0;box-shadow:none;margin-bottom:20px;}
        h1{background:linear-gradient(45deg,#667eea,#764ba2);-webkit-background-clip:text;-webkit-text-fill-color:transparent;font-size:2.8em;font-weight:800;margin-bottom:20px;}
        .role-badge{padding:12px 25px;background:linear-gradient(45deg,#d13c3c,#a82e2e);color:white;border-radius:30px;display:inline-block;font-weight:700;font-size:1.1em;margin-bottom:20px;}
        .welcome{text-align:center;color:#666;font-size:1.3em;margin-bottom:40px;}
        .admin-actions{display:flex;justify-content:center;gap:25px;flex-wrap:wrap;margin:60px 0;}
        .btn-quiz{display:inline-block;background:linear-gradient(45deg,#f28c28,#d9771e);color:white;padding:20px 40px;border:none;border-radius:25px;font-size:1.2em;font-weight:700;text-decoration:none;transition:all 0.3s ease;box-shadow:0 10px 30px rgba(242,140,40,0.4);text-transform:uppercase;}
        .btn-quiz:hover{transform:translateY(-5px);box-shadow:0 20px 40px rgba(242,140,40,0.5);}
        .tabs{display:flex;justify-content:center;gap:15px;margin:40px 0;flex-wrap:wrap;}
        .tab-btn{padding:15px 30px;background:#e1e5e9;color:#666;border:none;border-radius:25px;font-size:1.1em;font-weight:600;cursor:pointer;transition:all 0.3s ease;}
        .tab-btn.active{background:linear-gradient(45deg,#667eea,#764ba2);color:white;transform:translateY(-2px);}
        .quiz-section{display:none;}
        .quiz-section.active{display:block;}
        .quiz-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(350px,1fr));gap:25px;}
        .quiz-card{background:rgba(248,249,250,0.8);border-radius:20px;padding:30px;box-shadow:0 15px 35px rgba(0,0,0,0.1);transition:all 0.3s ease;}
        .quiz-card:hover{transform:translateY(-8px);box-shadow:0 25px 50px rgba(0,0,0,0.15);}
        .quiz-title{font-size:1.6em;font-weight:800;color:#333;margin-bottom:12px;}
        .quiz-desc{color:#666;line-height:1.5;margin-bottom:15px;}
        .quiz-meta{color:#888;font-size:0.95em;margin-bottom:20px;}
        .no-quiz{text-align:center;padding:80px;color:#888;}
        .no-quiz h3{font-size:2em;margin-bottom:20px;}
        .quick-launch{background:linear-gradient(45deg,#667eea,#764ba2);color:white;padding:30px;border-radius:25px;margin:40px 0;text-align:center;box-shadow:0 15px 35px rgba(102,126,234,0.3);}
        .quick-launch h3{font-size:1.8em;margin-bottom:15px;}
        .quick-launch .btn-quiz{background:linear-gradient(45deg,#d13c3c,#a82e2e);box-shadow:0 10px 25px rgba(209,60,60,0.4);margin-top:15px;display:inline-block;padding:15px 35px;}
        .logout{text-align:center;margin-top:40px;}
        .logout a{color:#d13c3c;font-weight:800;font-size:1.2em;text-decoration:none;}
        @media (max-width:768px){.container{padding:60px 40px;}.admin-actions{flex-direction:column;align-items:center;}}
    </style>
</head>
<body>
    <div class="container">
        <!-- LOGO -->
        <div class="logo-section">
            <img src="../Capture d’écran 2025-12-01 102913.png" alt="Logo Quizzeo">
        </div>
        
        <h1>🎓 Quizzeo Dashboard</h1>
        <div class="role-badge"><?= ucfirst($user['role']) ?></div>
        <p class="welcome">Bienvenue <?= htmlspecialchars($user['nom'] ?? $user['email'] ?? 'Utilisateur') ?> !</p>

        <?php if (in_array($user['role'], ['admin', 'ecole', 'entreprise'])): ?>
            <!-- ADMIN / ÉCOLE / ENTREPRISE -->
            <div class="admin-actions">
                <a href="quiz_create.php" class="btn-quiz">➕ Créer nouveau quiz</a>
                <a href="quiz_list.php" class="btn-quiz"> Mes quiz</a>
                <?php if ($user['role'] === 'admin'): ?>
                    <a href="admin_users.php" class="btn-quiz"> Gestion utilisateurs</a>
                    <a href="admin_contact.php" class="btn-quiz"> Messages contact</a>
                <?php endif; ?>
            </div>

        <?php else: ?>
            <!-- UTILISATEUR SIMPLE -->
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
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="no-quiz">
                        <h3>Aucun quiz entreprise disponible</h3>
                        <p>Revenez bientôt pour de nouveaux questionnaires !</p>
                    </div>
                <?php endif; ?>
            </div>

            <!-- BOUTON DEMARRER QUIZ EN BAS -->
            <?php
            // Récupère un quiz école ou entreprise selon l'onglet actif
            $stmt_quick = $pdo->prepare("
                SELECT q.id, q.titre, u.role as createur_role, u.nom as createur
                FROM quiz q JOIN utilisateurs u ON q.id_createur = u.id 
                WHERE (u.role = 'ecole' OR u.role = 'entreprise') 
                AND q.statut IN ('lancé', 'terminé') 
                ORDER BY q.date_creation DESC LIMIT 1
            ");
            $stmt_quick->execute();
            $quiz_quick = $stmt_quick->fetch();
            ?>
            
            <?php if ($quiz_quick): ?>
            <div class="quick-launch">
                <h3>Quiz disponible !</h3>
                <p><strong><?= htmlspecialchars($quiz_quick['titre']) ?></strong> par <?= htmlspecialchars($quiz_quick['createur']) ?></p>
                <a href="quiz_take.php?id=<?= $quiz_quick['id'] ?>" class="btn-quiz"> Démarrer le quiz</a>
            </div>
            <?php endif; ?>
        <?php endif; ?>

        <div class="logout">
            <a href="logout.php"> Se déconnecter</a>
        </div>
    </div>

    <script>
        function showTab(type) {
            document.querySelectorAll('.quiz-section').forEach(s => s.classList.remove('active'));
            document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
            document.getElementById(type + '-tab').classList.add('active');
            document.getElementById(type + '-quiz').classList.add('active');
        }
    </script>
</body>
</html>
