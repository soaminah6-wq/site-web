<?php
require_once 'auth.php';
require_login();

echo "<h1>Bienvenue " . htmlspecialchars($_SESSION['user']['firstname']) . "</h1>";

switch ($_SESSION['user']['role']) {
    case 'admin':
        echo "<p>Vous êtes administrateur. Voici vos options :</p>";
        // Met ici le code ou liens vers la gestion admin
        break;
    case 'ecole':
        echo "<p>Bienvenue à votre tableau de bord Ecole. Liste des quiz ...</p>";
        // Code spécifique école
        break;
    case 'entreprise':
        echo "<p>Bienvenue à votre tableau entreprise. Liste des quiz ...</p>";
        // Code spécifique entreprise
        break;
    case 'user':
        echo "<p>Bienvenue dans votre espace utilisateur. Accédez aux quiz.</p>";
        // Code utilisateur simple
        break;
    default:
        echo "<p>Rôle inconnu.</p>";
}
?>
<p><a href='logout.php'>Se déconnecter</a></p>
