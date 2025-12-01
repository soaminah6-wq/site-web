<?php
session_start();

define('USERS_FILE', __DIR__ . '/../data/users.json');

// Charge les utilisateurs depuis le fichier JSON
function load_users() {
    if (!file_exists(USERS_FILE)) return [];
    $json = file_get_contents(USERS_FILE);
    $users = json_decode($json, true);
    return is_array($users) ? $users : [];
}

// Sauvegarde les utilisateurs dans le fichier JSON
function save_users($users) {
    $fp = fopen(USERS_FILE, 'c+');
    if (!$fp) throw new Exception("Impossible d'ouvrir le fichier users.json");
    if (flock($fp, LOCK_EX)) {
        ftruncate($fp, 0);
        rewind($fp);
        fwrite($fp, json_encode($users, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        fflush($fp);
        flock($fp, LOCK_UN);
    }
    fclose($fp);
}

// Cherche un utilisateur par email
function find_user_by_email($email) {
    $users = load_users();
    foreach ($users as $user) {
        if (strcasecmp($user['email'], $email) === 0) return $user;
    }
    return null;
}

// Crée un nouvel utilisateur
function create_user($email, $password, $role, $firstname = '', $lastname = '') {
    if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) return "Email invalide.";
    if (strlen($password) < 6) return "Le mot de passe doit contenir au moins 6 caractères.";
    if (find_user_by_email($email)) return "Un compte avec cet email existe déjà.";

    $users = load_users();
    $users[] = [
        'id' => uniqid('u', true),
        'email' => $email,
        'password' => password_hash($password, PASSWORD_DEFAULT),
        'role' => $role,
        'firstname' => $firstname,
        'lastname' => $lastname,
        'active' => true
    ];
    save_users($users);
    return true;
}

// Connexion utilisateur
function login_user($email, $password) {
    $user = find_user_by_email($email);
    if (!$user) return "Utilisateur non trouvé.";
    if (isset($user['active']) && !$user['active']) return "Compte désactivé.";
    if (!password_verify($password, $user['password'])) return "Mot de passe incorrect.";

    $_SESSION['user'] = [
        'id' => $user['id'],
        'email' => $user['email'],
        'role' => $user['role'],
        'firstname' => $user['firstname'],
        'lastname' => $user['lastname']
    ];
    return true;
}

// Vérifie si utilisateur est connecté
function require_login() {
    if (empty($_SESSION['user'])) {
        header('Location: login.php');
        exit;
    }
}

// Crée un admin par défaut s'il n'existe pas
function ensure_admin_exists() {
    $users = load_users();
    foreach ($users as $user) {
        if ($user['role'] === 'admin') return;
    }
    create_user('admin@quizzeo.local', 'Admin123!', 'admin', 'Admin', 'Quizzeo');
}
