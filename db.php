<?php
// connexion
$host = 'localhost';
$dbname = 'quizzeo';  // Nom de la base
$user = 'root';       // Utilisateur MySQL
$pass = '';           // Mot de passe MySQL (vide si pas de mot de passe)

try {
    // objet PDO pour la connexion (creation)
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);

    // Configurer PDO pour qu'il lance des exceptions en cas d'erreur
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    // En cas d'erreur et afficher un message et arrêter le script
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}

