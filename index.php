<?php
session_start();
if (isset($_SESSION['user'])) {
    header('Location: src/dashboard.php');
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Quizzeo - Plateforme Quiz</title>
    <style>
        *{margin:0;padding:0;box-sizing:border-box;}
        body{font-family:'Segoe UI',sans-serif;background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);min-height:100vh;display:flex;align-items:center;justify-content:center;padding:20px;}
        .container{background:rgba(255,255,255,0.95);backdrop-filter:blur(10px);border-radius:25px;box-shadow:0 25px 50px rgba(0,0,0,0.15);max-width:450px;width:100%;padding:50px;animation:slideUp 0.8s ease;}
        @keyframes slideUp{from{transform:translateY(60px);opacity:0;}to{transform:translateY(0);opacity:1;}}
        .logo{text-align:center;margin-bottom:40px;}
        .logo img{height:90px;border-radius:50%;box-shadow:0 15px 35px rgba(0,0,0,0.2);}
        .logo h1{background:linear-gradient(45deg,#667eea,#764ba2);-webkit-background-clip:text;-webkit-text-fill-color:transparent;font-size:2.5em;font-weight:800;margin-bottom:10px;}
        .tagline{color:#666;font-size:1.1em;text-align:center;margin-bottom:40px;}
        form{display:flex;flex-direction:column;gap:20px;}
        input,select{padding:18px;border:2px solid #e1e5e9;border-radius:15px;font-size:1.1em;transition:all 0.3s ease;background:#f8f9fa;}
        input:focus,select:focus{outline:none;border-color:#667eea;background:white;box-shadow:0 0 0 4px rgba(102,126,234,0.1);transform:translateY(-2px);}
        select{background-image:url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6,9 12,15 18,9'%3e%3c/polyline%3e%3c/svg%3e");background-repeat:no-repeat;background-position:right 15px center;background-size:20px;}
        .btn-start{padding:20px;background:linear-gradient(45deg,#667eea,#764ba2);color:white;border:none;border-radius:15px;font-size:1.3em;font-weight:700;cursor:pointer;transition:all 0.3s ease;text-transform:uppercase;letter-spacing:1px;}
        .btn-start:hover{transform:translateY(-4px);box-shadow:0 20px 40px rgba(102,126,234,0.4);}
        .login-link{text-align:center;margin-top:30px;color:#666;font-size:0.95em;}
        .login-link a{color:#667eea;text-decoration:none;font-weight:600;}
    </style>
</head>
<body>
    <div class="container">
        <div class="logo">
            <img src="Capture d’écran 2025-12-01 102913.png" alt="Quizzeo">
            <h1>QUIZZEO</h1>
        </div>
        <p class="tagline">Plateforme d'évaluation moderne pour écoles et entreprises</p>
        
        <form method="POST" action="src/register.php">
            <input type="text" name="nom" placeholder="👤 Votre nom complet" required>
            <input type="email" name="email" placeholder="📧 Votre email" required>
            <select name="role" required>
                <option value=""> 🎭 Choisissez votre rôle</option>
                <option value="user"> 👤 Utilisateur</option>
                <option value="ecole"> 🏫 École</option>
                <option value="entreprise">🏢 Entreprise</option>
            </select>
            <button type="submit" class="btn-start"> 🚀 COMMENCER</button>
        </form>
        
        <div class="login-link">
            <a href="src/login.php"> 👋 Déjà inscrit ? Se connecter</a>
        </div>
    </div>
</body>
</html>

