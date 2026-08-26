<?php
require_once __DIR__ . '/includes/config.php';
$pageTitle = 'Coachs ' . $site['season'];
$pageDescription = 'Coachs sportifs AS amU pour la saison ' . $site['season'] . ' : disciplines encadrées, sports sans coach et contacts.';
require_once __DIR__ . '/includes/header.php';
?>
<section class="page-hero compact coach-hero">
    <div class="container">
        <p class="eyebrow">Compétitions · Saison <?= e($site['season']) ?></p>
        <h1>Les coachs sportifs <?= e($site['season']) ?>.</h1>
        <p class="lead"><?= e($coachInfo['intro']) ?></p>
        <div class="hero-actions">
            <a class="btn btn-primary" href="calendrier.php">Voir le calendrier</a>
            <a class="btn btn-secondary" href="#contacts-coachs">Voir les coordonnées des coachs</a>
        </div>
    </div>
</section>

<section class="section container">
    <div class="notice warning-notice">
        <strong>Important :</strong> <?= e($coachInfo['warning']) ?>
    </div>
</section>

<section class="section container cards two coach-rules">
    <article class="card">
        <span class="badge badge-salmon">Sport sans coach</span>
        <h2>Prendre contact avec l’AS amU</h2>
        <p>Si le sport n’a pas de coach référent, l’étudiant·e doit contacter l’AS amU avant de s’engager en compétition. L’association valide la participation et organise le suivi administratif si nécessaire.</p>
        <div class="tag-cloud">
            <?php foreach ($coachInfo['sports_without_coach'] as $sport): ?>
                <span><?= e($sport) ?></span>
            <?php endforeach; ?>
        </div>
    </article>
    <article class="card">
        <span class="badge badge-green">Sport avec coach</span>
        <h2>Contacter directement le ou la coach</h2>
        <p>Pour un sport encadré, l’étudiant·e contacte directement le ou la coach de la discipline pour connaître les modalités de pratique, les critères de sélection et les prochaines échéances.</p>
        <div class="tag-cloud">
            <?php foreach ($coachInfo['sports_with_coach'] as $sport): ?>
                <span><?= e($sport) ?></span>
            <?php endforeach; ?>
        </div>
    </article>
</section>

<section id="contacts-coachs" class="section section-muted">
    <div class="container">
        <div class="section-heading inline-heading">
            <div>
                <p class="eyebrow">Contacts</p>
                <h2>Contact des coachs</h2>
            </div>
            <a class="text-link" href="competitions.php#documents">Documents compétition →</a>
        </div>

        <div class="coach-grid">
            <?php foreach ($coaches as $coach): ?>
                <article class="coach-card">
                    <h3><?= e($coach['sport']) ?></h3>
                    <ul>
                        <?php foreach ($coach['contacts'] as $contact): ?>
                            <li>
                                <strong><?= e($contact['name']) ?></strong>
                                <?php if (!empty($contact['note'])): ?>
                                    <span><?= e($contact['note']) ?></span>
                                <?php endif; ?>
                                <a href="mailto:<?= e($contact['email']) ?>"><?= e($contact['email']) ?></a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php if ($coachStats): ?>
<section class="section container">
    <div class="cards three">
        <?php foreach ($coachStats as $stat): ?>
            <article class="card stat-soft">
                <span><?= e($stat['label']) ?></span>
                <strong><?= e($stat['number']) ?></strong>
                <p><?= e($stat['description']) ?></p>
            </article>
        <?php endforeach; ?>
    </div>
</section>
<?php endif; ?>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
