<?php
$pageTitle = 'Organisation de l’association';
$pageDescription = 'Organisation de l’AS amU : rôle de l’association, sections, bureau, commissions et documents.';
require_once __DIR__ . '/includes/header.php';

// Le bureau est volontairement présenté comme un collectif : chaque personne
// apparaît au même niveau, quel que soit son rôle ou son pôle de contribution.
$boardMembers = [[
    'name' => $associationBoard['president']['name'],
    'role' => $associationBoard['president']['role'],
    'initials' => $associationBoard['president']['initials'],
    'accent' => 'blue',
]];
foreach ($associationBoard['poles'] as $pole) {
    foreach ($pole['members'] as $member) {
        $boardMembers[] = [
            'name' => $member['name'],
            'role' => $member['role'],
            'initials' => $member['initials'],
            'accent' => $pole['accent'],
        ];
    }
}
?>
<section class="page-hero compact">
    <div class="container">
        <p class="eyebrow">L’association</p>
        <h1>Organisation de l’association.</h1>
        <p class="lead">Une page claire pour comprendre qui fait quoi : association centrale, sections, bureau, commissions, documents et adhésion.</p>
        <div class="hero-actions">
            <a class="btn btn-primary" href="adhesion.php">Comment adhérer ?</a>
            <a class="btn btn-secondary" href="documents.php">Voir les documents</a>
        </div>
    </div>
</section>

<section class="section container association-layout">
    <div>
        <article class="card anchor-block" id="role">
            <span class="badge badge-yellow">Rôle</span>
            <h2>À quoi sert l’AS amU ?</h2>
            <p>L’AS amU organise, accompagne et valorise la pratique sportive universitaire à Aix-Marseille Université. Elle fait le lien entre les étudiant·es, les sections, les coachs, les compétitions FFSU et les démarches associatives.</p>
            <p>Cette organisation permet de garder un fonctionnement commun tout en laissant chaque section gérer ses permanences, ses contacts et ses informations de terrain.</p>
        </article>

        <div class="stat-grid" aria-label="Chiffres clés AS amU">
            <?php foreach ($associationStats as $stat): ?>
                <div class="stat-card">
                    <strong><?= e($stat['number']) ?></strong>
                    <span><?= e($stat['label']) ?></span>
                </div>
            <?php endforeach; ?>
        </div>

        <article class="card anchor-block" id="fonctionnement" style="margin-top:18px">
            <span class="badge badge-blue">Fonctionnement</span>
            <h2>Association centrale + sections</h2>
            <p>L’association centrale coordonne les grandes démarches : adhésion, documents, compétitions, communication, finances et accompagnement des sections.</p>
            <p>Les sections sont les points d’entrée étudiants. Chaque section dispose d’une fiche dédiée avec son adresse, son bureau, ses permanences, son mail et ses informations pratiques.</p>
            <div class="card-actions">
                <a href="sections.php">Voir toutes les sections</a>
                <a href="commissions.php">Voir les commissions</a>
            </div>
        </article>
    </div>

    <aside class="association-sidebar card">
        <h2>Menu association</h2>
        <ul class="quick-menu">
            <li><a href="association.php">Organisation <span>→</span></a></li>
            <li><a href="#bureau">Bureau AS amU <span>→</span></a></li>
            <li><a href="statuts.php">Statuts & charte <span>→</span></a></li>
            <li><a href="documents.php">Documents <span>→</span></a></li>
            <li><a href="commissions.php">Commissions <span>→</span></a></li>
            <li><a href="adhesion.php">Comment adhérer ? <span>→</span></a></li>
        </ul>
    </aside>
</section>

<section class="section association-board-section" id="bureau">
    <div class="container">
        <div class="section-heading association-board-heading">
            <div>
                <p class="eyebrow">Bureau AS amU · <?= e($associationBoard['year']) ?></p>
                <h2>Le collectif qui fait vivre l’AS amU.</h2>
            </div>
            <p>Des rôles complémentaires, une même énergie au service des sections, des étudiant·es et du sport universitaire.</p>
        </div>

        <div class="board-collective">
            <div class="board-collective-intro">
                <img src="assets/img/logo-asamu-v2.png" alt="Logo AS amU">
                <div>
                    <span>Ensemble, sur tous les terrains</span>
                    <p>Chaque membre contribue aux projets, aux sections et à la vie sportive de l’association.</p>
                </div>
            </div>

            <div class="board-people-grid" aria-label="Les membres du bureau AS amU">
                <?php foreach ($boardMembers as $index => $member): ?>
                    <article class="board-person board-person-<?= e($member['accent']) ?> board-person-<?= $index + 1 ?>">
                        <span class="board-person-orbit" aria-hidden="true"></span>
                        <span class="org-avatar" aria-hidden="true"><?= e($member['initials']) ?></span>
                        <div>
                            <strong><?= e($member['name']) ?></strong>
                            <span><?= e($member['role']) ?></span>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>

            <a class="board-collective-contact" href="mailto:<?= e($associationBoard['contact']) ?>">
                <span>Une question, une idée, un projet ?</span>
                <strong><?= e($associationBoard['contact']) ?> →</strong>
            </a>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
