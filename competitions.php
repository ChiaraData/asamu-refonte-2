<?php
$pageTitle = 'Compétitions';
$pageDescription = 'Préparer un départ en compétition universitaire avec l’AS amU : validation, budget, transport, calendrier et remboursement.';
require_once __DIR__ . '/includes/header.php';
?>
<section class="page-hero compact">
    <div class="container">
        <p class="eyebrow">Compétitions universitaires</p>
        <h1>Préparer son départ sans oublier d’étape.</h1>
        <p class="lead">Avant toute dépense, la participation et le budget doivent être validés par l’AS amU. Le calendrier est maintenant disponible dans un onglet dédié.</p>
        <div class="hero-actions">
            <a class="btn btn-primary" href="calendrier">Voir le calendrier</a>
            <a class="btn btn-secondary" href="coachs">Voir les coachs</a>
        </div>
    </div>
</section>

<section class="section container">
    <div class="cards two">
        <article class="card color-green-soft">
            <h2>Sport avec coach AS amU</h2>
            <p>La sélection est faite par le coach. Il ou elle coordonne ensuite les démarches administratives, les engagements et la logistique.</p><a class="text-link" href="coachs">Voir les coachs →</a>
        </article>
        <article class="card warning-card">
            <h2>Sport sans coach AS amU</h2>
            <p>L’étudiant·e fait une demande via le formulaire compétition. La commission sport valide ensuite le départ avant toute dépense.</p><a class="text-link" href="coachs">Voir les sports sans coach →</a>
        </article>
    </div>
</section>

<section class="section section-muted">
    <div class="container">
        <div class="section-heading">
            <p class="eyebrow">Process</p>
            <h2>Les étapes obligatoires</h2>
        </div>
        <div class="process-grid">
            <article><span>1</span><h3>Vérifier le calendrier</h3><p>Regarde les compétitions à venir, les deadlines et les niveaux concernés.</p></article>
            <article><span>2</span><h3>Budget prévisionnel</h3><p>Transport, hébergement, restauration, liste des sélectionné·es et tailles pour les dotations.</p></article>
            <article><span>3</span><h3>Validation préalable</h3><p>Envoyer le budget à <a href="mailto:<?= e($site['competition_email']) ?>"><?= e($site['competition_email']) ?></a> avant toute dépense.</p></article>
            <article><span>4</span><h3>Organisation</h3><p>Privilégier la carte ou le virement AS amU. Garder tous les justificatifs valables.</p></article>
            <article><span>5</span><h3>Remboursement</h3><p>Envoyer le dossier complet à <a href="mailto:<?= e($site['treasury_email']) ?>"><?= e($site['treasury_email']) ?></a>.</p></article>
            <article><span>6</span><h3>Résultats</h3><p>Transmettre résultats et photos pendant ou après la compétition.</p></article>
        </div>
    </div>
</section>

<section class="section container" id="documents">
    <div class="section-heading inline-heading">
        <div>
            <p class="eyebrow">Documents</p>
            <h2>Liens utiles</h2>
        </div>
    </div>
    <div class="doc-list">
        <?php foreach ($competitionDocs as $doc): ?>
            <a href="<?= e($doc['url']) ?>" target="_blank" rel="noopener"><?= e($doc['label']) ?><span>↗</span></a>
        <?php endforeach; ?>
    </div>
</section>

<section class="section container">
    <div class="notice">
        <strong>Important :</strong> aucun remboursement n’est possible sans validation préalable ni dossier complet. Les justificatifs doivent être lisibles et valables.
    </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
