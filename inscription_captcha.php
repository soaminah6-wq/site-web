<?php

// public/register.php

require_once __DIR__.'/../src/auth.php';

// générer captcha si non présent

if (!isset($_SESSION['captcha'])) {

    $a = rand(1,9);

    $b = rand(1,9);

    $_SESSION['captcha'] = ['a'=>$a,'b'=>$b,'ans'=>$a+$b];

}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = $_POST['email'] ?? '';

    $pass = $_POST['password'] ?? '';

    $role = $_POST['role'] ?? 'user';

    $name = $_POST['name'] ?? '';

    $captcha = intval($_POST['captcha'] ?? 0);

    if ($captcha !== $_SESSION['captcha']['ans']) {

        $errors[] = "Captcha incorrect.";

    }

    if (empty($errors)) {

        if (register_user($email, $pass, $role, $name)) {

            header("Location: login.php?registered=1");

            exit();

        } else {

            $errors[] = "Erreur lors de l'inscription.";

        }

    }

}

?>
<!-- HTML form ici : email, password, role (select), name, captcha display -->
 