<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quizzeo - Plateforme Quiz Professionnelle</title>
    <style>
        *{margin:0;padding:0;box-sizing:border-box;}
        body{font-family:'Segoe UI',Tahoma,sans-serif;background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);min-height:100vh;display:flex;align-items:center;justify-content:center;padding:20px;color:#333;}
        
        .container{
            background:#ffffff;           /* blanc pur comme le fond du logo */
            border-radius:30px;
            max-width:900px;
            width:100%;
            padding:60px;
            text-align:center;
            box-shadow:0 30px 60px rgba(0,0,0,0.15);
            animation:slideUp 1s ease;
        }
        @keyframes slideUp{from{transform:translateY(80px);opacity:0;}to{transform:translateY(0);opacity:1;}}

        .logo img{
            height:120px;
            border-radius:0;              /* carré */
            box-shadow:none;              /* pas d'ombre pour coller au fond blanc */
        }

        h1{background:linear-gradient(45deg,#667eea,#764ba2);-webkit-background-clip:text;-webkit-text-fill-color:transparent;font-size:3.5em;font-weight:800;margin-bottom:20px;}
        .sous-titre{font-size:1.4em;color:#666;margin-bottom:40px;line-height:1.6;}
        .objectifs{margin:50px 0;text-align:left;max-width:700px;margin-left:auto;margin-right:auto;}
        .objectif{padding:25px;background:rgba(255,255,255,0.7);border-radius:20px;margin:20px 0;box-shadow:0 10px 30px rgba(0,0,0,0.1);transition:all 0.3s ease;}
        .objectif:hover{transform:translateY(-5px);box-shadow:0 20px 40px rgba(0,0,0,0.15);}
        .objectif h3{font-size:1.3em;color:#333;margin-bottom:10px;}
        
        /* BOUTONS - COULEURS LOGO */
        .btn-container{display:flex;gap:30px;justify-content:center;flex-wrap:wrap;margin:50px 0;}
        .btn{padding:20px 40px;color:white;border:none;border-radius:25px;font-size:1.3em;font-weight:700;text-decoration:none;text-transform:uppercase;letter-spacing:1px;transition:all 0.3s ease;box-shadow:0 10px 30px rgba(0,0,0,0.2);}

        /* ROUGE comme le logo Quizzeo */
        .btn-contact{
            background:linear-gradient(45deg,#d13c3c,#a82e2e);
            box-shadow:0 10px 30px rgba(209,60,60,0.4);
        }
        .btn-contact:hover{
            transform:translateY(-5px);
            box-shadow:0 20px 40px rgba(209,60,60,0.5);
        }

        /* JAUNE/ORANGE comme le logo Quizzeo */
        .btn-login{
            background:linear-gradient(45deg,#f28c28,#d9771e);
            box-shadow:0 10px 30px rgba(242,140,40,0.4);
        }
        .btn-login:hover{
            transform:translateY(-5px);
            box-shadow:0 20px 40px rgba(242,140,40,0.5);
        }

        .features{display:grid;grid-template-columns:repeat(auto-fit,minmax(250px,1fr));gap:30px;margin:50px 0;}
        .feature{background:linear-gradient(45deg,rgba(255,255,255,0.9),rgba(255,255,255,0.7));padding:30px;border-radius:20px;box-shadow:0 15px 35px rgba(0,0,0,0.1);}
        .feature-icon{font-size:3em;margin-bottom:15px;}
        footer{padding:30px;background:rgba(0,0,0,0.1);border-radius:20px;margin-top:40px;color:#666;}
        
        @media (max-width:768px){.container{padding:40px;}h1{font-size:2.5em;}.btn-container{flex-direction:column;align-items:center;}}
    </style>
</head>
<body>
    <div class="container">
        <!-- LOGO -->
        <div class="logo">
            <img src="Capture d’écran 2025-12-01 102913.png" alt="Logo Quizzeo">
        </div>

        <!-- TITRE -->
        <h1>🎓 Bienvenue sur Quizzeo</h1>
        <p class="sous-titre">La plateforme n°1 pour créer et partager des quiz professionnels</p>

        <!-- OBJECTIFS -->
        <div class="objectifs">
            <div class="objectif">
                <h3>🏫 Pour les Écoles</h3>
                <p>Créez des QCM notés pour vos élèves avec correction automatique et suivi des résultats.</p>
            </div>
            <div class="objectif">
                <h3>🏢 Pour les Entreprises</h3>
                <p>Questionnaires de satisfaction clients, formations internes et évaluations RH.</p>
            </div>
            <div class="objectif">
                <h3>👤 Pour les Utilisateurs</h3>
                <p>Accédez facilement aux quiz et obtenez vos scores instantanément.</p>
            </div>
        </div>

        <!-- BOUTONS D'ACTION - COULEURS LOGO -->
        <div class="btn-container">
            <a href="contact.php" class="btn btn-contact">📧 Nous Contacter</a>
            <a href="src/login.php" class="btn btn-login">👤 Se Connecter / Créer Compte</a>
        </div>

        <!-- FOOTER -->
        <footer>
            <p>&copy; 2025 Quizzeo - Plateforme d'évaluation moderne </p>
        </footer>
    </div>
</body>
</html>
