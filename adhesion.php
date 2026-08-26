<?php
$pageTitle = 'Adhésion';
$pageDescription = 'Procédure d’adhésion AS amU via HelloAsso et demande de licence FFSU saison 2025/2026.';
require_once __DIR__ . '/includes/header.php';
?>
<section class="page-hero compact">
    <div class="container">
        <p class="eyebrow">Adhésion <?= e($site['season']) ?></p>
        <h1>Adhérer à l’AS amU via HelloAsso.</h1>
        <p class="lead">L’adhésion ne passe plus par le portail AS amU. Le paiement se fait sur HelloAsso, puis la licence FFSU se complète sur MySportU pour les compétitions.</p>
        <div class="hero-actions">
            <a class="btn btn-primary" href="<?= e($site['helloasso_url']) ?>" target="_blank" rel="noopener">Ouvrir HelloAsso</a>
            <a class="btn btn-secondary" href="sections-appartenance.php">Je ne connais pas ma section</a>
        </div>
    </div>
</section>

<section class="section container">
    <div class="pricing-strip">
        <div>
            <span>Adhésion AS amU</span>
            <strong><?= e($site['membership_price']) ?></strong>
        </div>
        <div>
            <span>Licence FFSU</span>
            <strong><?= e($site['license_price']) ?></strong>
        </div>
        <p>Conserve ton reçu HelloAsso. Il sert de preuve d’adhésion si ta section ou l’AS amU te le demande.</p>
    </div>
</section>

<section class="section container">
    <div class="section-heading">
        <p class="eyebrow">Mode d’emploi</p>
        <h2>Les étapes à suivre</h2>
    </div>
    <ol class="timeline">
        <?php foreach ($adhesionSteps as $index => $step): ?>
            <li>
                <span><?= $index + 1 ?></span>
                <div>
                    <h3><?= e($step['title']) ?></h3>
                    <p><?= e($step['text']) ?></p>
                </div>
            </li>
        <?php endforeach; ?>
    </ol>
</section>

<section class="section section-muted" id="licence">
    <div class="container faq-grid">
        <article class="faq-card">
            <h2>Licence FFSU</h2>
            <details open>
                <summary>La licence est-elle obligatoire ?</summary>
                <p>Oui, elle est obligatoire pour participer aux compétitions universitaires FFSU.</p>
            </details>
            <details>
                <summary>Où faire la demande de licence ?</summary>
                <p>Après ton adhésion HelloAsso, complète ton dossier sur <a href="<?= e($site['mysportu_url']) ?>" target="_blank" rel="noopener">MySportU</a>.</p>
            </details>
            <details>
                <summary>Quelle section dois-je choisir ?</summary>
                <p>Choisis la section correspondant à ta composante AMU : faculté, école ou institut d’inscription.</p>
            </details>
            <details>
                <summary>J’ai un problème de dossier, qui contacter ?</summary>
                <p>Contacte d’abord ta section depuis sa page dédiée. Si le blocage persiste, écris à <a href="mailto:<?= e($site['email']) ?>"><?= e($site['email']) ?></a>.</p>
            </details>
        </article>
        <aside class="highlight-panel">
            <h3>Avant de commencer</h3>
            <ul class="check-list">
                <li>Identifier ta section AMU.</li>
                <li>Préparer ton adresse étudiante.</li>
                <li>Garder le reçu HelloAsso.</li>
                <li>Préparer un certificat médical si demandé.</li>
            </ul>
        </aside>
    </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
