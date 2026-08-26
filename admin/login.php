<?php
declare(strict_types=1);

require_once __DIR__ . '/_bootstrap.php';

if (admin_is_logged_in()) {
    admin_redirect('index.php');
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!admin_verify_csrf()) {
        $error = 'La page a expiré. Recharge-la puis réessaie.';
    } elseif (admin_login((string) ($_POST['username'] ?? ''), (string) ($_POST['password'] ?? ''))) {
        admin_redirect('index.php');
    } else {
        $error = 'Mot de passe incorrect.';
    }
}
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>Connexion · Administration AS amU</title>
    <link rel="stylesheet" href="assets/admin.css">
</head>
<body class="admin-login-body">
<main class="admin-login-card">
    <a class="admin-login-brand" href="../index.php"><img src="../assets/img/logo-asamu-v2.png" alt="AS amU"><span>Administration</span></a>
    <p class="admin-kicker">Espace privé</p>
    <h1>Mettre le site à jour.</h1>
    <p>Connecte-toi pour modifier les fiches sections, la photothèque, les podiums et les informations pratiques.</p>
    <?php if ($error): ?><div class="admin-alert error" role="alert"><?= e($error) ?></div><?php endif; ?>
    <form method="post" class="admin-login-form">
        <?= admin_form_token() ?>
        <label for="username">Identifiant</label>
        <input type="text" id="username" name="username" autocomplete="username" required autofocus placeholder="admin">
        <label for="password">Mot de passe</label>
        <input type="password" id="password" name="password" autocomplete="current-password" required>
        <button class="admin-button primary full" type="submit">Se connecter</button>
    </form>
    <a href="../index.php">← Retour au site</a>
</main>
</body>
</html>
