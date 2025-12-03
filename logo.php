<?php ?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Logo Quizzéo</title>

<style>
    body {
        background: linear-gradient(135deg, pink, #ffb6c1);
        text-align: center;
        padding-top: 100px;
        font-family: Arial, sans-serif;
    }

    .logo {
        font-size: 120px;
        font-weight: bold;
        letter-spacing: 3px;
    }

    /* Base */
    .logo span {
        font-weight: bold;
        color: transparent;
        background-size: 8px 8px;
        background-repeat: repeat;
        -webkit-background-clip: text;
    }

    /* Q violet avec points */
    .Q {
        background-image:
            radial-gradient(white 12%, transparent 13%),
            linear-gradient(#8c7bff, #8c7bff);
    }

    /* Lettres rouges */
    .U, .I, .Z1, .Z2, .E {
        background-image:
            radial-gradient(white 12%, transparent 13%),
            linear-gradient(#ff6b6b, #ff6b6b);
    }

    /* O jaune */
    .O {
        background-image:
            radial-gradient(white 12%, transparent 13%),
            linear-gradient(#ffcc5c, #ffcc5c);
    }
</style>

<div class="logo">
    <span class="Q">Q</span>
    <span class="U">U</span>
    <span class="I">I</span>
    <span class="Z1">Z</span>
    <span class="Z2">Z</span>
    <span class="E">E</span>
    <span class="O">O</span>
</div>

