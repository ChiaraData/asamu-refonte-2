<?php
$pageTitle = 'Accueil';
$pageDescription = 'AS amU : adhésion HelloAsso, licence FFSU, sections sportives et calendrier des compétitions universitaires à Aix-Marseille Université.';
require_once __DIR__ . '/includes/header.php';
?>
<section class="hero">
    <div class="palette-strip" aria-hidden="true">
        <span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span>
    </div>
    <div class="container hero-grid">
        <div class="hero-copy">
            <p class="eyebrow">Saison <?= e($site['season']) ?></p>
            <h1>Association sportive Aix-Marseille Université</h1>
            <p class="lead">Trouve ta section, adhère avec HelloAsso, prépare ta licence FFSU et suis le calendrier des compétitions en quelques clics.</p>
            <div class="hero-actions">
                <a class="btn btn-primary" href="adhesion">Adhérer via HelloAsso</a>
                <a class="btn btn-secondary" href="sections">Trouver ma section</a>
                <a class="btn btn-secondary" href="boussole">La boussole du sport à AMU</a>
                <a class="btn btn-secondary" href="sections-appartenance">Je ne sais pas à quelle section j'appartiens</a>
            </div>
            <ul class="trust-list" aria-label="Chiffres clés">
                <?php foreach (($site['home_stats'] ?? []) as $stat): ?>
                    <li>
                        <strong><?= e((string) ($stat['number'] ?? '')) ?></strong>
                        <span><?= e((string) ($stat['label'] ?? '')) ?></span>
                        <?php if (!empty($stat['note'])): ?><small><?= e((string) $stat['note']) ?></small><?php endif; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
        <aside class="hero-card" aria-label="Informations importantes">
            <span class="badge badge-yellow">Nouveau</span>
            <h2>Adhésion sur HelloAsso</h2>
            <p>Il n’y a plus de portail AS amU pour l’adhésion. Le paiement passe par HelloAsso, puis la licence FFSU se complète sur MySportU si tu fais de la compétition.</p>
            <a href="<?= e($site['helloasso_url']) ?>" class="text-link" target="_blank" rel="noopener">Ouvrir HelloAsso →</a>
        </aside>
    </div>
</section>

<section class="section container president-section">
    <div class="president-card">
        <figure class="president-photo">
            <img 
                src="<?= e($site['president_photo']) ?>" 
                alt="Photo de <?= e($site['president_name']) ?>"
            >
        </figure>

        <div>
            <p class="eyebrow">Le mot du président</p>
            <h2>Bienvenue à l’AS amU.</h2>
            <blockquote>
                <?= rich_text_render((string) $site['president_word']) ?>
            </blockquote>
            <p class="signature">
                <strong><?= e($site['president_name']) ?></strong>
                <span><?= e($site['president_title']) ?></span>
            </p>
        </div>
    </div>
</section>

<section class="section container">
    <div class="section-heading">
        <p class="eyebrow">Parcours étudiant</p>
        <h2>Les 4 actions à comprendre</h2>
    </div>
    <div class="cards four">
        <article class="card action-card color-blue">
            <span class="icon">1</span>
            <h3>Je choisis ma section</h3>
            <p>La section dépend de ta composante amU, pas seulement de ton sport.</p>
            <a href="sections">Voir les sections</a>
        </article>
        <article class="card action-card color-orange">
            <span class="icon">2</span>
            <h3>J’adhère</h3>
            <p>Le paiement de l’adhésion se fait directement sur HelloAsso.</p>
            <a href="adhesion">Voir la procédure</a>
        </article>
        <article class="card action-card color-green">
            <span class="icon">3</span>
            <h3>Je demande ma licence</h3>
            <p>La licence FFSU reste nécessaire pour participer aux compétitions universitaires.</p>
            <a href="adhesion#licence">Licence FFSU</a>
        </article>
        <article class="card action-card color-purple">
            <span class="icon">4</span>
            <h3>Je consulte le calendrier</h3>
            <p>Les compétitions à venir sont centralisées dans un onglet dédié.</p>
            <a href="calendrier">Voir le calendrier</a>
        </article>
    </div>
</section>

<section class="section section-muted">
    <div class="container split">
        <div>
            <p class="eyebrow">L’association</p>
            <h2>Association Sportive Aix-Marseille Université</h2>

            <p>
                L’AS amU, créée en 2012 avec la naissance de l’Université d’Aix-Marseille,
                est un acteur incontournable de la vie sportive universitaire. Forte de l’héritage
                des associations précédentes, elle regroupe aujourd’hui 12 sections sportives réparties
                sur les différents campus universitaires, au plus près des étudiants et étudiantes.
            </p>

            <p>
                Avec <strong>2&nbsp;658 adhérentes et adhérents et 2&nbsp;959 licenciés et licenciées AS amU</strong>, l’AS amU offre un espace unique
                pour apprendre, s’épanouir et se dépasser à travers le sport.
            </p>

            <h3>Un engagement sportif reconnu</h3>

            <p>
                Sous l’égide de la Fédération Française du Sport Universitaire (FFSU) et de la Ligue
                Régionale de Sports Universitaires (Ligue Sud), nos licenciés et licenciées participent
                chaque année à des compétitions locales, régionales, nationales, et même européennes.
                Grâce à leur détermination, nos équipes défendent fièrement les couleurs de
                l’Université d’Aix-Marseille.
            </p>

            <div class="mini-stats">
                <article>
                    <strong>64</strong>
                    <span>podiums nationaux</span>
                </article>

                <article>
                    <strong>3</strong>
                    <span>podiums internationaux</span>
                </article>
            </div>

            <h3>Nos valeurs : « Apprendre, Participer, Gagner »</h3>

            <div class="values-list">
                <p>
                    <strong>Apprendre</strong>
                    Développez vos compétences sportives, découvrez le plaisir du collectif
                    et épanouissez-vous dans votre discipline.
                </p>

                <p>
                    <strong>Participer</strong>
                    Rejoignez l’une des 12 sections sportives pour des activités variées
                    et participez aux compétitions locales ou nationales.
                </p>

                <p>
                    <strong>Gagner</strong>
                    Dépassez vos limites, vivez des moments inoubliables et cultivez votre confiance en vous.
                </p>
            </div>

            <h3>Rejoignez l’aventure !</h3>

            <p>
                Envie de faire partie d’une équipe qui allie performance et convivialité ?
                Que vous soyez débutant ou confirmé, il y a une place pour vous à l’AS amU.
                Découvrez nos sections sportives et les opportunités qui vous attendent !
            </p>
        </div>
        <div class="highlight-panel">
            <h3>Besoin d’aide rapidement ?</h3>
            <p>Commence par identifier ta section. Si tu as déjà payé ton adhésion HelloAsso, garde ton mail de confirmation à portée de main.</p>
            <div class="card-actions">
                <a class="btn btn-secondary" href="association">Voir l’association</a>
                <a class="btn btn-secondary" href="contact">Contacter l’AS amU</a>
            </div>
        </div>
    </div>
</section>

<section class="section container">
    <div class="section-heading inline-heading">
        <div>
            <p class="eyebrow">Les 12 sections</p>
            <h2>Découvrir les sections et les sports</h2>
        </div>
        <a class="text-link" href="sections#section-list">Découvrir les sections et les sports →</a>
    </div>
    <div class="mini-section-grid">
        <?php foreach ($sections as $section): ?>
            <a class="mini-section" href="<?= e(section_url($section)) ?>">
                <strong><?= e($section['name']) ?></strong>
                <span><?= e($section['city']) ?></span>
            </a>
        <?php endforeach; ?>
    </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
