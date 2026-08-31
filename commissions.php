<?php
$pageTitle = 'Les commissions';
$pageDescription = 'Les commissions AS amU : sport, communication, finances et vie associative.';
require_once __DIR__ . '/includes/header.php';
?>
<section class="page-hero compact">
    <div class="container">
        <p class="eyebrow">L’association</p>
        <h1>Les commissions.</h1>
        <p class="lead">Les commissions permettent de répartir le travail : sport, finances, communication, vie associative et suivi des projets.</p>
        <div class="hero-actions">
            <a class="btn btn-primary" href="contact">Contacter l’AS amU</a>
            <a class="btn btn-secondary" href="association">Organisation</a>
        </div>
    </div>
</section>

<section class="section container">
    <div class="section-heading">
        <p class="eyebrow">Fonctionnement interne</p>
        <h2>Une commission = une mission claire.</h2>
    </div>

    <div class="commission-grid">
        <?php foreach ($commissions as $commission): ?>
            <article class="card commission-card">
                <h3><?= e($commission['name']) ?></h3>
                <p><?= e($commission['mission']) ?></p>
                <ul class="commission-meta">
                    <li><strong>Membres :</strong> <?= e($commission['members']) ?></li>
                    <li><strong>Contact :</strong> <a href="mailto:<?= e($commission['contact']) ?>"><?= e($commission['contact']) ?></a></li>
                </ul>
            </article>
        <?php endforeach; ?>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
