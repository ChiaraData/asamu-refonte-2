<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/config.php';

$pageTitle = 'Politique de confidentialité';
$pageDescription = 'Politique de confidentialité et protection des données personnelles sur le site AS amU.';
$privacyEmail = trim((string) ($site['privacy_email'] ?? '')) ?: $site['email'];

require_once __DIR__ . '/includes/header.php';
?>
<section class="page-hero compact legal-hero">
    <div class="container">
        <p class="eyebrow">Vie privée</p>
        <h1>Politique de confidentialité.</h1>
        <p class="lead">Cette page explique quelles données sont utilisées sur le site, pourquoi elles le sont et comment exercer vos droits.</p>
    </div>
</section>

<section class="section container legal-content">
    <article class="legal-card">
        <p class="eyebrow">Responsable du traitement</p>
        <h2><?= e($site['full_name']) ?></h2>
        <p>L’AS amU, située <?= e($site['address']) ?>, est responsable des traitements réalisés directement au moyen de ce site.</p>
        <p>Pour toute question relative aux données personnelles : <a href="mailto:<?= e($privacyEmail) ?>"><?= e($privacyEmail) ?></a>.</p>
    </article>

    <article class="legal-card">
        <p class="eyebrow">Formulaire de contact</p>
        <h2>Répondre à vos demandes</h2>
        <p>Lorsque vous utilisez le formulaire de contact, le site traite votre nom, votre adresse e-mail, le sujet choisi et le contenu de votre message.</p>
        <dl class="legal-details">
            <div><dt>Finalité</dt><dd>Recevoir, orienter et répondre à votre demande.</dd></div>
            <div><dt>Base légale</dt><dd>Intérêt légitime de l’association à répondre aux sollicitations qui lui sont adressées.</dd></div>
            <div><dt>Caractère obligatoire</dt><dd>Ces informations sont nécessaires pour traiter la demande. Sans elles, l’AS amU ne pourra pas répondre.</dd></div>
            <div><dt>Destinataires</dt><dd>Personnes habilitées de l’AS amU et, selon le sujet, de la section sportive concernée.</dd></div>
            <div><dt>Durée</dt><dd>Durée nécessaire au traitement de la demande, puis au maximum 12 mois après le dernier échange, sauf obligation légale ou contentieux.</dd></div>
        </dl>
        <p>Le formulaire ne conserve actuellement pas les messages dans une base de données du site. Lorsque l’envoi électronique est activé sur l’hébergement, le message est transmis à la boîte de contact de l’association.</p>
    </article>

    <article class="legal-card">
        <p class="eyebrow">Carte des campus</p>
        <h2>Google Maps chargé uniquement sur demande</h2>
        <p>La carte Google Maps n’est pas chargée lors de l’ouverture de la page. Elle apparaît uniquement lorsque vous sélectionnez le bouton « Afficher la carte Google Maps ».</p>
        <p>Cette action établit une connexion avec Google, qui peut alors recevoir des informations techniques telles que l’adresse IP, le navigateur utilisé et la carte consultée. Google traite ces données selon sa propre <a href="https://policies.google.com/privacy?hl=fr" target="_blank" rel="noopener">politique de confidentialité <span aria-hidden="true">↗</span></a>.</p>
        <p>Vous pouvez utiliser les coordonnées et adresses affichées sur le site sans charger la carte.</p>
    </article>

    <article class="legal-card">
        <p class="eyebrow">Cookies et journaux techniques</p>
        <h2>Pas de suivi publicitaire</h2>
        <p>Le site public n’utilise actuellement aucun outil de publicité personnalisée ni de mesure d’audience. Il ne dépose donc pas de cookie nécessitant une bannière de consentement.</p>
        <p>L’espace d’administration restreint utilise un cookie de session strictement nécessaire à l’authentification et à la sécurité. L’hébergeur peut également conserver temporairement des journaux techniques pour assurer la sécurité et le bon fonctionnement du service.</p>
    </article>

    <article class="legal-card">
        <p class="eyebrow">Services externes</p>
        <h2>Liens vers d’autres plateformes</h2>
        <p>Le site contient des liens vers HelloAsso, MySportU, Instagram et les sites de partenaires. Tant que vous ne suivez pas ces liens, ces plateformes ne sont pas chargées par le site AS amU. Après votre départ du site, leurs propres politiques de confidentialité s’appliquent.</p>
    </article>

    <article class="legal-card">
        <p class="eyebrow">Vos droits</p>
        <h2>Accès, rectification et effacement</h2>
        <p>Selon la situation, vous pouvez demander l’accès à vos données, leur rectification, leur effacement ou la limitation du traitement, et vous opposer à leur utilisation.</p>
        <p>Pour exercer ces droits, écrivez à <a href="mailto:<?= e($privacyEmail) ?>"><?= e($privacyEmail) ?></a> en précisant votre demande. Une réponse vous sera apportée dans les délais prévus par la réglementation.</p>
        <p>Si vous estimez, après avoir contacté l’association, que vos droits ne sont pas respectés, vous pouvez adresser une réclamation à la <a href="https://www.cnil.fr/fr/plaintes" target="_blank" rel="noopener">CNIL <span aria-hidden="true">↗</span></a>.</p>
    </article>

    <article class="legal-card">
        <p class="eyebrow">Sécurité et évolution</p>
        <h2>Protection des informations</h2>
        <p>L’AS amU limite l’accès aux informations aux personnes qui en ont besoin et met en œuvre des mesures techniques et organisationnelles adaptées. Cette politique sera mise à jour si les formulaires, services externes, cookies ou finalités du site évoluent.</p>
    </article>

    <p class="legal-updated">Dernière mise à jour : 4 août 2026.</p>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
