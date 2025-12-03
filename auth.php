<?php
session_start();
require_once 'db.php'; // Mettre  PDO 

function find_user_by_email($email) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE email = ?");
    $stmt->execute([$email]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function create_user($email, $password, $role, $firstname = '', $lastname = '') {
    global $pdo;
    
    if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) return "Email invalide.";
    if (strlen($password) < 6) return "Mot de passe trop court.";
    if (find_user_by_email($email)) return "Un compte avec cet email existe déjà.";

    $hash = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO utilisateurs (email, mot_de_passe, role, prenom, nom, actif) VALUES (?, ?, ?, ?, ?, 1)");
    $stmt->execute([$email, $hash, $role, $firstname, $lastname]);
    return true;
}

function load_users() {
    global $pdo;
    $stmt = $pdo->query("SELECT * FROM utilisateurs");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function login_user($email, $password) {
    $user = find_user_by_email($email);
    if (!$user) return "Utilisateur non trouvé.";
    if (!$user['actif']) return "Compte désactivé.";
    if (!password_verify($password, $user['mot_de_passe'])) return "Mot de passe incorrect.";

    $_SESSION['user'] = [
        'id' => $user['id'],
        'email' => $user['email'],
        'role' => $user['role'],
        'firstname' => $user['prenom'],
        'lastname' => $user['nom']
    ];
    return true;
}

function require_login() {
    if (empty($_SESSION['user'])) {
        header('Location: login.php');
        exit;
    }
}

function ensure_admin_exists() {
    global $pdo;
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM utilisateurs WHERE role = 'admin'");
    $stmt->execute();
    if ($stmt->fetchColumn() == 0) {
        create_user('admin@quizzeo.local', 'Admin123!', 'admin', 'Admin', 'Quizzeo');
    }
}

