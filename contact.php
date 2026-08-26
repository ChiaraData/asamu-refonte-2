<?php
$pageTitle = 'Contact';
$pageDescription = 'Contacter l’AS amU pour une question d’adhésion, licence, section ou compétition.';
require_once __DIR__ . '/includes/config.php';

$errors = [];
$success = false;
$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$subject = trim($_POST['subject'] ?? '');
$message = trim($_POST['message'] ?? '');
$honeypot = trim($_POST['website'] ?? '');

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    if ($honeypot !== '') {
        $errors[] = 'Envoi refusé.';
    }
    if ($name === '') {
        $errors[] = 'Indique ton nom.';
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Indique une adresse e-mail valide.';
    }
    if ($subject === '') {
        $errors[] = 'Choisis un sujet.';
    }
    if (mb_strlen($message) < 10) {
        $errors[] = 'Le message doit contenir au moins 10 caractères.';
    }

    if (!$errors) {
        $to = $site['email'];
        $mailSubject = '[Site AS amU] ' . $subject;
        $body = "Nom : {$name}\nEmail : {$email}\nSujet : {$subject}\n\nMessage :\n{$message}";
        $headers = "From: noreply@as-amu.fr\r\nReply-To: {$email}\r\nContent-Type: text/plain; charset=UTF-8";

        // Décommentez cette ligne sur un hébergement PHP configuré pour envoyer des e-mails.
        // $success = mail($to, $mailSubject, $body, $headers);
        $success = true;

        if ($success) {
            $name = $email = $subject = $message = '';
        } else {
            $errors[] = 'Le message n’a pas pu être envoyé. Réessaie ou écris directement à contact@as-amu.fr.';
        }
    }
}

require_once __DIR__ . '/includes/header.php';
?>
<section class="page-hero compact">
    <div class="container">
        <p class="eyebrow">Contact</p>
        <h1>Une question ? Écris à l’AS amU.</h1>
        <p class="lead">Adhésion HelloAsso, licence, section, calendrier, compétition : envoie ta demande au bon endroit.</p>
    </div>
</section>

<section class="section container contact-grid">
    <form class="contact-form" method="post" action="contact.php" novalidate>
        <h2>Envoyer un message</h2>

        <?php if ($success): ?>
            <div class="form-alert success">Message prêt à être envoyé. Active la fonction <code>mail()</code> sur ton hébergement pour l’envoi réel.</div>
        <?php endif; ?>

        <?php if ($errors): ?>
            <div class="form-alert error">
                <strong>À corriger :</strong>
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?= e($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <label for="name">Nom</label>
        <input id="name" name="name" type="text" value="<?= e($name) ?>" autocomplete="name" required>

        <label for="email">Adresse e-mail</label>
        <input id="email" name="email" type="email" value="<?= e($email) ?>" autocomplete="email" required>

        <label for="subject">Sujet</label>
        <select id="subject" name="subject" required>
            <option value="">Choisir un sujet</option>
            <?php foreach (['Adhésion HelloAsso', 'Licence FFSU', 'Section sportive', 'Calendrier compétition', 'Coachs', 'Compétition', 'Partenariat', 'Autre'] as $option): ?>
                <option value="<?= e($option) ?>" <?= $subject === $option ? 'selected' : '' ?>><?= e($option) ?></option>
            <?php endforeach; ?>
        </select>

        <label for="message">Message</label>
        <textarea id="message" name="message" rows="6" required><?= e($message) ?></textarea>

        <label class="hp" for="website">Site web</label>
        <input class="hp" id="website" name="website" type="text" tabindex="-1" autocomplete="off">

        <button class="btn btn-primary" type="submit">Envoyer</button>
        <p class="form-privacy-note">Tous les champs visibles sont nécessaires pour répondre à ta demande. Les informations sont utilisées uniquement par l’AS amU et, si besoin, par la section concernée. <a href="confidentialite.php">En savoir plus sur tes données et tes droits</a>.</p>
    </form>

    <aside class="contact-card">
        <h2>Coordonnées</h2>
        <p><strong><?= e($site['full_name']) ?></strong></p>
        <p><?= e($site['address']) ?></p>
        <p><a href="tel:+33623928914"><?= e($site['phone']) ?></a><br><a href="mailto:<?= e($site['email']) ?>"><?= e($site['email']) ?></a></p>
        <hr>
        <h3>Contacts directs</h3>
        <p>Compétitions : <a href="mailto:<?= e($site['competition_email']) ?>"><?= e($site['competition_email']) ?></a></p>
        <p>Communication : <a href="mailto:<?= e($site['communication_email']) ?>"><?= e($site['communication_email']) ?></a></p>
        <p>Remboursements : <a href="mailto:<?= e($site['treasury_email']) ?>"><?= e($site['treasury_email']) ?></a></p>
    </aside>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
