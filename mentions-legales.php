<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/config.php';

$pageTitle = 'Mentions légales';
$pageDescription = 'Mentions légales du site de l’Association Sportive Aix-Marseille Université.';
$hostIsConfigured = trim((string) ($site['host_name'] ?? '')) !== ''
    && trim((string) ($site['host_address'] ?? '')) !== '';

require_once __DIR__ . '/includes/header.php';
?>
<section class="page-hero compact legal-hero">
    <div class="container">
        <p class="eyebrow">Informations légales</p>
        <h1>Mentions légales.</h1>
        <p class="lead">Informations relatives à l’édition, à la publication et à l’hébergement du site as-amu.fr.</p>
    </div>
</section>

<section class="section container legal-content">
    <article class="legal-card">
        <p class="eyebrow">Éditeur</p>
        <h2><?= e($site['full_name']) ?></h2>
        <dl class="legal-details">
            <div><dt>Statut</dt><dd><?= e((string) ($site['legal_status'] ?? 'Association déclarée')) ?></dd></div>
            <div><dt>Siège social</dt><dd><?= e($site['address']) ?></dd></div>
            <div><dt>RNA</dt><dd><?= e((string) ($site['rna_number'] ?? '')) ?></dd></div>
            <div><dt>SIREN</dt><dd><?= e((string) ($site['siren_number'] ?? '')) ?></dd></div>
            <div><dt>SIRET</dt><dd><?= e((string) ($site['siret_number'] ?? '')) ?></dd></div>
            <div><dt>Contact</dt><dd><a href="mailto:<?= e($site['email']) ?>"><?= e($site['email']) ?></a> · <?= e($site['phone']) ?></dd></div>
        </dl>
    </article>

    <article class="legal-card">
        <p class="eyebrow">Publication</p>
        <h2>Direction de la publication</h2>
        <p>Le directeur de la publication est <strong><?= e((string) ($site['president_name'] ?? '')) ?></strong>, <?= e((string) ($site['president_title'] ?? 'Président de l’AS amU')) ?>.</p>
    </article>

    <article class="legal-card">
        <p class="eyebrow">Hébergement</p>
        <h2>Prestataire technique</h2>
        <?php if ($hostIsConfigured): ?>
            <div class="legal-host-card">
                <div class="legal-host-heading">
                    <span aria-hidden="true">EF</span>
                    <div>
                        <strong><?= e((string) $site['host_name']) ?></strong>
                        <small>Hébergement et infrastructure du site</small>
                    </div>
                </div>
                <dl class="legal-details legal-host-details">
                    <?php if (!empty($site['host_company'])): ?><div><dt>Raison sociale</dt><dd><?= e((string) $site['host_company']) ?></dd></div><?php endif; ?>
                    <div><dt>Adresse</dt><dd><?= e((string) $site['host_address']) ?></dd></div>
                    <?php if (!empty($site['host_phone'])): ?><div><dt>Téléphone</dt><dd><a href="tel:<?= e(preg_replace('/\s+/', '', (string) $site['host_phone'])) ?>"><?= e((string) $site['host_phone']) ?></a></dd></div><?php endif; ?>
                    <div><dt>Site internet</dt><dd><a href="https://www.e-frogg.com/" target="_blank" rel="noopener">www.e-frogg.com <span aria-hidden="true">↗</span></a></dd></div>
                </dl>
            </div>
        <?php else: ?>
            <div class="legal-warning">
                <strong>Information à compléter avant la publication définitive</strong>
                <p>Le nom, la raison sociale, l’adresse et le téléphone de l’hébergeur doivent être renseignés dans l’administration du site, rubrique « Informations du site ».</p>
            </div>
        <?php endif; ?>
    </article>

    <article class="legal-card">
        <p class="eyebrow">Contenus</p>
        <h2>Propriété intellectuelle</h2>
        <p>Les textes, photographies, illustrations, logos, documents et autres contenus présents sur ce site appartiennent à l’AS amU ou sont utilisés avec l’autorisation de leurs titulaires. Toute reproduction ou réutilisation substantielle nécessite une autorisation préalable, sauf exception prévue par la loi.</p>
        <p>Les marques, logos et contenus appartenant aux partenaires restent la propriété de leurs titulaires respectifs.</p>
    </article>

    <article class="legal-card">
        <p class="eyebrow">Données personnelles</p>
        <h2>Protection de la vie privée</h2>
        <p>Les modalités de traitement des données personnelles, les durées de conservation et les droits des personnes sont détaillés dans la <a href="confidentialite">politique de confidentialité</a>.</p>
    </article>

    <p class="legal-updated">Dernière mise à jour : 4 août 2026.</p>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
