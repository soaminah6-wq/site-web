<?php
// 1. Connexion à la base
$dsn = "mysql:host=localhost;dbname=quizzeo;charset=utf8mb4";
$user = "root";
$pass = "";

try {
    $pdo = new PDO($dsn, $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

$message_ok = "";
$message_erreur = "";

// 2. Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom    = trim($_POST['nom'] ?? '');
    $email  = trim($_POST['email'] ?? '');
    $sujet  = trim($_POST['sujet'] ?? '');
    $msg    = trim($_POST['message'] ?? '');

    if ($nom === '' || $email === '' || $sujet === '' || $msg === '') {
        $message_erreur = "Tous les champs sont obligatoires.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message_erreur = "Adresse email invalide.";
    } else {
        $sql = "INSERT INTO contact (nom, email, sujet, message)
                VALUES (:nom, :email, :sujet, :message)";
        $stmt = $pdo->prepare($sql);
        $ok = $stmt->execute([
            ':nom'     => $nom,
            ':email'   => $email,
            ':sujet'   => $sujet,
            ':message' => $msg,
        ]);

        if ($ok) {
            $message_ok = "Votre message a bien été envoyé.";
        } else {
            $message_erreur = "Une erreur est survenue lors de l'envoi.";
        }
    }
}
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Contact - Quizzeo</title>
</head>
<body>
<h1>Contactez-nous</h1>

<?php if ($message_ok): ?>
    <p style="color:green;"><?= htmlspecialchars($message_ok) ?></p>
<?php endif; ?>

<?php if ($message_erreur): ?>
    <p style="color:red;"><?= htmlspecialchars($message_erreur) ?></p>
<?php endif; ?>

<form method="post" action="">
    <label>Nom :</label><br>
    <input type="text" name="nom" required><br><br>

    <label>Email :</label><br>
    <input type="email" name="email" required><br><br>

    <label>Sujet :</label><br>
    <input type="text" name="sujet" required><br><br>

    <label>Message :</label><br>
    <textarea name="message" rows="5" required></textarea><br><br>

    <button type="submit">Envoyer</button>
</form>

</body>
</html>
