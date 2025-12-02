<?php
require_once 'auth.php'; // inclut la connexion et fonctions

// Si l'admin existe déjà, stop
$existing = find_user_by_email('admin@quizzeo.local');
if ($existing) {
    die("Le compte admin existe déjà.");
}

$email = 'admin@quizzeo.local';
$password = 'Admin123!'; // mot de passe admin sécuritaire
$role = 'admin';
$firstname = 'Admin';
$lastname = 'Quizzeo';

$result = create_user($email, $password, $role, $firstname, $lastname);

if ($result === true) {
    echo "Compte admin créé avec succès.";
} else {
    echo "Erreur : $result";
}
