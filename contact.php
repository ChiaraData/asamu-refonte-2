<?php
$pageTitle = 'Contact';
$pageDescription = 'Écrire directement à l’AS amU pour une question d’adhésion, de licence, de section ou de compétition.';
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
    <article class="contact-card">
        <p class="eyebrow">Contact par e-mail</p>
        <h2>Écris-nous directement</h2>
        <p>Pour toute demande générale concernant l’adhésion, la licence ou l’association, utilise l’adresse officielle de l’AS amU.</p>
        <p><a class="btn btn-primary" href="mailto:<?= e($site['email']) ?>?subject=Demande%20depuis%20le%20site%20AS%20amU">Envoyer un e-mail à l’AS amU</a></p>
        <p class="form-privacy-note">Le site ne collecte aucune donnée de contact. Le bouton ouvre simplement ton application de messagerie. <a href="confidentialite">En savoir plus sur tes données et tes droits</a>.</p>
    </article>

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
