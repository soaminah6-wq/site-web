<?php

$message_ok = '';
$message_erreur = '';


try {
    $pdo = new PDO('mysql:host=localhost;dbname=quizzeo;charset=utf8mb4', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $role = $_POST['role'] ?? '';
    $sujet = trim($_POST['sujet'] ?? '');
    $message = trim($_POST['message'] ?? '');

    $roles_valides = ['admin', 'ecole', 'entreprise', 'user'];

    if ($nom === '' || $email === '' || $sujet === '' || $message === '' || !in_array($role, $roles_valides)) {
        $message_erreur = "Tous les champs sont obligatoires et le rôle doit être valide.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message_erreur = "Adresse email invalide.";
    } else {
        $sql = "INSERT INTO contact (nom, email, role, sujet, message, date_envoi) 
                VALUES (:nom, :email, :role, :sujet, :message, NOW())";
        $stmt = $pdo->prepare($sql);
        $ok = $stmt->execute([
            ':nom' => $nom,
            ':email' => $email,
            ':role' => $role,
            ':sujet' => $sujet,
            ':message' => $message,
        ]);
        if ($ok) {
            $message_ok = "Votre message a bien été envoyé.";
        } else {
            $message_erreur = "Une erreur s'est produite lors de l'envoi.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8" />
<title>Contact - Quizzeo</title>
<style>
body {
    font-family: Arial, sans-serif;
    background-color: #fdfdfd;
    color: #333;
    margin: 0;
    padding: 0;
}

header {
    background-color: #6e3b9e; /* violet */
    color: white;
    text-align: center;
    padding: 20px;
}

header img {
    height: 60px;
    margin-bottom: 10px;
}

header h1 {
    margin: 0;
    font-size: 2rem;
}

.main-container {
    max-width: 600px;
    margin: 40px auto;
    padding: 20px;
    background-color: white;
    border: 1px solid #ddd;
    border-radius: 10px;
}

form {
    display: flex;
    flex-direction: column;
}

label {
    margin-top: 15px;
    font-weight: bold;
    color: #6e3b9e; /* violet */
}

input[type="text"],
input[type="email"],
select,
textarea {
    padding: 10px;
    margin-top: 5px;
    border: 1px solid #f28c28; /* orange */
    border-radius: 5px;
    font-size: 1rem;
    background-color: #fff;
}

textarea {
    resize: vertical;
    min-height: 100px;
}

button {
    margin-top: 20px;
    padding: 12px;
    background-color: #d13c3c; /* rouge */
    color: white;
    border: none;
    border-radius: 5px;
    font-size: 1rem;
    cursor: pointer;
}

button:hover {
    background-color: #a82e2e;
}

.message-ok, .message-erreur {
    padding: 15px;
    margin-bottom: 20px;
    border-radius: 5px;
    font-weight: bold;
    text-align: center;
}

.message-ok {
    background-color: #d4edda;
    color: #f28c28;
    border: 1px solid #c3e6cb;
}

.message-erreur {
    background-color: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}*

</style>

<header>
    <img src="Capture d’écran 2025-12-01 102913.png" alt="Logo Quizzeo" />
    <h1>Formulaire de Contact</h1>
</header>

<div class="main-container">
    <!-- Message de succès ou erreur -->
    <?php if ($message_ok): ?>
        <p class="message-ok"><?= htmlspecialchars($message_ok) ?></p>
    <?php endif; ?>
    <?php if ($message_erreur): ?>
        <p class="message-erreur"><?= htmlspecialchars($message_erreur) ?></p>
    <?php endif; ?>

    <!-- Formulaire -->
    <form method="post" action="">
        <label for="nom">Nom</label>
        <input type="text" id="nom" name="nom" required />

        <label for="email">Email</label>
        <input type="email" id="email" name="email" required />

        <label for="role">Rôle</label>
        <select id="role" name="role" required>
            <option value="">-- Choisissez un rôle --</option>
            <option value="admin">Administrateur</option>
            <option value="ecole">École</option>
            <option value="entreprise">Entreprise</option>
            <option value="user">Utilisateur</option>
        </select>

        <label for="sujet">Sujet</label>
        <input type="text" id="sujet" name="sujet" required />

        <label for="message">Message</label>
        <textarea id="message" name="message" required></textarea>

        <button type="submit">Envoyer</button>
    </form>
</div>

</body>
</html>
