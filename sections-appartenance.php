<?php
$pageTitle = 'À quelle section AS amU j’appartiens ?';
$pageDescription = 'Identifie ta section AS amU selon ta formation, ton campus ou ta composante avant d’adhérer.';
require_once __DIR__ . '/includes/header.php';

$sectionMemberships = [
    [
        'name' => 'Aix IMPGT',
        'city' => 'Aix-en-Provence',
        'email' => 'contact.aix.impgt@as-amu.fr',
        'formations' => [
            'Licence Administration Publique',
            'Master Management public',
            'Master Management public Prépa IMPGT+ / Préparation aux concours de la fonction publique',
            'Master Management des établissements sanitaires et sociaux',
            'Master Management et droit des organisations et des manifestations culturelles',
            'Master 2 Management des administrations publiques',
            'Master Droit et management des collectivités territoriales',
            'Master Développement durable et gouvernance territoriale de projets en Méditerranée et à l’international',
            'Master Management qualité et gestion des risques sociétaux',
            'Master Sécurité et management des territoires',
            'Master Marketing et communication publique',
            'Master Attractivité et nouveau marketing territorial',
            'Master Recherche, études et conseil en sciences de gestion',
            'DESU – Entrepreneuriat et management des musiques actuelles',
            'DESU – Entrepreneuriat et management des festivals',
        ],
    ],
    [
        'name' => 'Aix IUT',
        'city' => 'Aix-en-Provence',
        'email' => 'contact.aix.iut@as-amu.fr',
        'formations' => [
            'Carrières sociales',
            'Information-Communication',
            'Gestion des Entreprises et des Administrations — GEA',
            'Techniques de Commercialisation — TC',
            'Management de la logistique et des transports',
            'Génie électrique',
            'Génie mécanique',
            'Sécurité des biens et des personnes',
            'Informatique',
            'Formations dispensées à Digne-les-Bains',
        ],
    ],
    [
        'name' => 'Marseille Étoile',
        'city' => 'Marseille',
        'email' => 'contact.marseille.etoile@as-amu.fr',
        'formations' => [
            'Métiers de la santé : technologies',
            'Réseaux informatiques et télécommunications',
            'Optique professionnelle',
            'Chimie',
            'Génie civil',
            'Génie chimique',
            'Métiers de la transition et de l’efficacité énergétique',
            'Mesures physiques',
            'Polytech : Génie civil, Génie industriel et informatique, Écologie industrielle, Mécanique et énergétique, Microélectronique et télécommunications, Systèmes numériques',
            'INSPE',
            'Licence Chimie',
            'Licence MPCI',
            'Licence Mécanique',
            'Licence Physique',
            'Licence Physique-Chimie',
            'Licence SVT',
            'Licence Sciences pour l’ingénieur',
            'Licence Sciences sanitaires et sociales',
            'Licence Pro Qualité, hygiène, sécurité, santé et environnement',
            'Licence Pro Métiers de l’instrumentation, de la mesure et du contrôle qualité',
            'Master Électronique, énergie électrique, automatique',
            'Master Génie des procédés et des bio-procédés',
            'Master Génie mécanique',
            'Master Informatique',
            'Master Information et Médiation Scientifique et Technique',
            'Master Instrumentation, mesure, métrologie',
            'Master Mécanique',
            'Master Nanosciences et nanotechnologies',
            'Master Physique fondamentale et applications',
            'Master Sciences des technologies de l’agriculture, de l’alimentation et de l’environnement',
            'Master Traitement du signal et des images',
            'Master Qualité, hygiène, sécurité',
            'Formations dispensées à Salon',
        ],
    ],
    [
        'name' => 'Aix ALLSH',
        'city' => 'Aix-en-Provence',
        'email' => 'contact.aix.allsh@as-amu.fr',
        'formations' => [
            'LEA — Langues étrangères appliquées',
            'LLCER — Langues, littératures et civilisations étrangères et régionales',
            'Lettres',
            'Sciences du langage',
            'Création littéraire',
            'Études européennes et internationales',
            'Humanités',
            'Langues et sociétés',
            'Traduction et interprétation',
            'Géographie et aménagement',
            'Histoire',
            'Histoire de l’art et archéologie',
            'Philosophie',
            'Psychologie',
            'Sciences de l’éducation',
            'Sciences de l’homme, anthropologie, ethnologie',
            'Sociologie',
            'Intervention sociale : accompagnement de publics spécifiques',
            'Anthropologie',
            'Archéologie, sciences pour l’archéologie',
            'Épistémologie, histoire des sciences et des techniques',
            'Français langue étrangère — FLE',
            'Géographie, aménagement, environnement et développement',
            'Histoire de l’art',
            'Sciences cognitives',
            'INSPE Aix',
        ],
    ],
    [
        'name' => 'Aix IAE',
        'city' => 'Aix-en-Provence',
        'email' => 'contact.aix-iae@as-amu.fr',
        'formations' => [
            'Toutes les formations de l’IAE Aix-Marseille Graduate School of Management',
        ],
    ],
    [
        'name' => 'Marseille FSS',
        'city' => 'Marseille',
        'email' => 'contact.marseille.fss@as-amu.fr',
        'formations' => [
            'STAPS',
            'Formations dispensées à Gap',
        ],
    ],
    [
        'name' => 'Marseille Santé Timone',
        'city' => 'Marseille',
        'email' => 'contact.marseille.timone@as-amu.fr',
        'formations' => [
            'Biologie – Santé',
            'Santé',
            'Santé publique',
            'Neurosciences',
            'Humanités médicales',
            'PASS — Parcours Accès Spécifique Santé',
            'Sciences infirmières',
            'École de journalisme et de communication d’Aix-Marseille',
        ],
    ],
    [
        'name' => 'Aix Droit',
        'city' => 'Aix-en-Provence',
        'email' => 'contact.aix.droit@as-amu.fr',
        'formations' => [
            'Droit Aix',
            'Droit et science politique Aix',
            'Droit / Histoire de l’art',
            'Droit / Économie-gestion',
            'Droit / Lettres',
            'Activités juridiques : assistant juridique',
            'Activités juridiques : mandataire judiciaire à la protection des majeurs',
            'Activités juridiques : métiers du droit de l’immobilier',
            'Logistique et transports internationaux',
            'Métiers du notariat',
            'Administration et liquidation d’entreprises en difficulté',
            'Droit des affaires',
            'Droit bancaire et financier',
            'Droit de l’environnement',
            'Droit fiscal',
            'Droit de l’immobilier',
            'Droit international et européen',
            'Droit du numérique',
            'Droit notarial',
            'Droit pénal et sciences criminelles',
            'Droit public',
            'Droit privé',
            'Droit de la santé',
            'Droit social',
            'Histoire du droit et des institutions',
            'Justice, procès et procédures',
            'Urbanisme et aménagement',
            'Formations dispensées à Arles',
        ],
    ],
    [
        'name' => 'Aix Sciences',
        'city' => 'Aix-en-Provence',
        'email' => 'contact.aix.sciences@as-amu.fr',
        'formations' => [
            'Licence Chimie',
            'Licence Informatique',
            'Licence Mathématiques',
            'Licence Physique',
            'Licence Sciences de la Vie',
            'Licence SVT',
            'Licence Sciences pour l’Ingénieur',
        ],
    ],
    [
        'name' => 'Marseille Luminy',
        'city' => 'Marseille',
        'email' => 'contact.marseille.luminy@as-amu.fr',
        'formations' => [
            'Licence Chimie',
            'Polytech : Génie biologique, Génie biomédical, Informatique, Matériaux',
            'Institut Pythéas',
            'Licence Informatique',
            'Licence Mathématiques',
            'Licence Mathématiques-Informatique',
            'Licence Physique',
            'Licence Sciences de la Vie',
            'Licence SVT',
            'Licence Pro Métiers du décisionnel et de la statistique',
            'Master Biologie intégrative et physiologie',
            'Master Biologie structurale, génomique',
            'Master Immunologie',
            'Master Informatique',
            'Master Mathématiques appliquées, statistiques',
            'Master Microbiologie',
            'Master Nanosciences et nanotechnologies',
            'Master Réseaux et télécommunications',
            'Master Sciences des technologies de l’agriculture, de l’alimentation et de l’environnement',
            'Master Traitement du signal et des images',
            'Formations dispensées à La Ciotat',
        ],
    ],
    [
        'name' => 'Aix FEG',
        'city' => 'Aix-en-Provence',
        'email' => 'contact.aix.feg@as-amu.fr',
        'formations' => [
            'Administration économique et sociale — AES',
            'Économie et gestion',
            'Gestion',
            'Informatique',
            'Mathématiques et Informatique Appliquées aux Sciences Humaines et Sociales — MIASHS',
            'Gestion des structures sanitaires et sociales — Responsable de structures enfance et petite enfance',
            'Métiers du commerce international',
            'Économétrie et statistiques',
            'Management et gestion des organisations',
            'Économie',
            'Économie de l’entreprise et des marchés',
            'Économie du droit',
            'Finance',
            'Gestion de production, logistique, achats',
            'Gestion des ressources humaines',
            'Management et commerce international',
            'Management de l’innovation',
            'Méthodes informatiques appliquées à la gestion des entreprises — MIAGE',
            'Réseaux et télécommunications',
        ],
    ],
    [
        'name' => 'Marseille Centre',
        'city' => 'Marseille',
        'email' => 'contact.marseille.centre@as-amu.fr',
        'formations' => [
            'Arts du spectacle',
            'Arts plastiques',
            'Musicologie',
            'Acoustique et musicologie',
            'Arts',
            'Cinéma et audiovisuel',
            'Médiation culturelle et artistique',
            'Assurance, banque, finance : chargé de clientèle',
            'Gestion et comptabilité : responsable de portefeuille clients en cabinet',
            'Entrepreneuriat',
            'Comptabilité – Contrôle – Audit',
            'Mode',
            'Licence Informatique',
            'Licence Mathématiques',
            'Licence Physique',
            'Licence Sciences de la Vie',
            'Licence SVT',
            'Licence Sciences et Humanités',
            'Licence Pro Aménagement paysager : conception, gestion, entretien',
            'Licence Pro Productions végétales',
            'Master Acoustique et musicologie',
            'Master Chimie',
            'Master Épistémologie, histoire et philosophie des sciences',
            'Master Intervention et développement social',
            'Master Mathématiques appliquées, statistiques',
            'Master Neurosciences',
            'Master Physique fondamentale et applications',
            'Master Sciences cognitives',
            'Licence AES',
            'Licence Droit Marseille',
            'Master Droit bancaire et financier',
            'Master Management Culture et Territoires — MCT',
            'Master Management de l’Innovation et du Digital — MIND',
            'Master Logistique, organisation et commerce international — LOCI',
            'Master Finance Comptabilité Audit — FCA',
            'Aix-Marseille School of Economics — AMSE',
        ],
    ],
];
?>

<style>
    .section-finder-hero {
        padding-bottom: 48px;
    }

    .section-finder-hero .hero-copy {
        max-width: 980px;
    }

    .section-finder-hero h1 {
        max-width: 980px;
    }

    .section-helper {
        margin-top: 28px;
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 16px;
    }

    .section-helper .card {
        padding: 22px;
    }

    .finder-tools {
        margin: 28px 0;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 18px;
        flex-wrap: wrap;
    }

    .finder-search {
        flex: 1;
        min-width: 260px;
        display: block;
    }

    .finder-search span {
        display: block;
        margin-bottom: 8px;
        font-weight: 800;
    }

    .finder-search input {
        width: 100%;
        border: 1px solid rgba(15, 23, 42, 0.14);
        border-radius: 999px;
        padding: 15px 20px;
        font: inherit;
        background: #fff;
        box-shadow: 0 16px 40px rgba(15, 23, 42, 0.06);
    }

    .membership-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(330px, 1fr));
        gap: 20px;
    }

    .membership-card {
        display: flex;
        flex-direction: column;
        gap: 18px;
        padding: 26px;
    }

    .membership-card.is-search-hidden {
        display: none !important;
    }

    .membership-card-header {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .membership-card h2 {
        margin: 0;
        font-size: clamp(1.5rem, 2vw, 2.1rem);
    }

    .membership-card h3 {
        margin: 0;
        font-size: 1rem;
    }

    .formation-list {
        margin: 0;
        padding-left: 18px;
        display: grid;
        gap: 8px;
        color: var(--text-muted, #4b5563);
    }

    .empty-message {
        margin-top: 26px;
        padding: 22px;
        border-radius: 24px;
        background: #fff;
        border: 1px solid rgba(15, 23, 42, 0.1);
        font-weight: 800;
    }

    @media (max-width: 900px) {
        .section-helper {
            grid-template-columns: 1fr;
        }

        .membership-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<section class="hero section-finder-hero">
    <div class="palette-strip" aria-hidden="true">
        <span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span>
    </div>

    <div class="container hero-grid">
        <div class="hero-copy">
            <p class="eyebrow">Sections AS amU</p>
            <h1>À quelle section AS amU j’appartiens&nbsp;?</h1>
            <p class="lead">
                Avant d’adhérer, identifie la section correspondant à ta formation ou à ta composante.
                C’est ce qui permet de t’inscrire correctement, de recevoir les bonnes informations
                et d’être rattaché·e à la bonne section.
            </p>

            <div class="hero-actions">
                <a class="btn btn-primary" href="#trouver-section">Trouver ma section</a>
                <a class="btn btn-secondary" href="adhesion">Voir l’adhésion</a>
            </div>
        </div>

        <aside class="hero-card" aria-label="Information importante">
            <span class="badge badge-yellow">Important</span>
            <h2>Tu peux pratiquer avec une autre section</h2>
            <p>
                Ton rattachement administratif ne t’empêche pas de participer à des entraînements
                ou compétitions avec une autre section si c’est plus pratique pour toi.
            </p>
            <a class="text-link" href="contact">Besoin d’aide&nbsp;? Contacte l’AS amU →</a>
        </aside>
    </div>
</section>

<section class="section container">
    <div class="section-helper">
        <article class="card">
            <span class="icon">1</span>
            <h3>Repère ta formation</h3>
            <p>Cherche ton diplôme, ta composante, ton campus ou un mot-clé de ta formation.</p>
        </article>

        <article class="card">
            <span class="icon">2</span>
            <h3>Note ta section</h3>
            <p>Chaque bloc indique la section AS amU à laquelle ta formation est rattachée.</p>
        </article>

        <article class="card">
            <span class="icon">3</span>
            <h3>Adhère en ligne</h3>
            <p>Une fois ta section identifiée, tu peux poursuivre ton adhésion via HelloAsso.</p>
        </article>
    </div>
</section>

<section class="section container" id="trouver-section">
    <div class="section-heading inline-heading">
        <div>
            <p class="eyebrow">Recherche rapide</p>
            <h2>Trouve ta section</h2>
        </div>
        <a class="text-link" href="contact">Je ne trouve pas ma formation →</a>
    </div>

    <div class="finder-tools">
        <label class="finder-search" for="section-search">
            <span>Recherche par formation, campus ou section</span>
            <input
                type="search"
                id="section-search"
                placeholder="Exemple : STAPS, Droit, Luminy, IUT, Psychologie..."
                autocomplete="off"
            >
        </label>

        <a class="btn btn-primary" href="adhesion">Adhérer</a>
    </div>

    <div class="membership-grid" id="membership-grid">
        <?php foreach ($sectionMemberships as $section): ?>
            <?php
                $keywords = $section['name'] . ' ' . $section['city'] . ' ' . $section['email'] . ' ' . implode(' ', $section['formations']);
            ?>
            <article class="card membership-card" data-section-card data-keywords="<?= e($keywords) ?>">
                <div class="membership-card-header">
                    <p class="eyebrow"><?= e($section['city']) ?></p>
                    <h2><?= e($section['name']) ?></h2>
                    <a class="text-link" href="mailto:<?= e($section['email']) ?>">
                        <?= e($section['email']) ?>
                    </a>
                </div>

                <div>
                    <h3>Formations concernées</h3>
                    <ul class="formation-list">
                        <?php foreach ($section['formations'] as $formation): ?>
                            <li><?= e($formation) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </article>
        <?php endforeach; ?>
    </div>

    <p class="empty-message" id="no-section-result" hidden>
        Aucun résultat pour cette recherche. Essaie avec un autre mot-clé ou contacte directement l’AS amU.
    </p>
</section>

<section class="section section-muted">
    <div class="container split">
        <div>
            <p class="eyebrow">À retenir</p>
            <h2>Ta section dépend de ta formation, pas uniquement de ton sport</h2>
            <p>
                L’AS amU est organisée en sections rattachées aux composantes et aux campus.
                Cette organisation facilite les inscriptions, les contacts, les permanences
                et la représentation des étudiant·es.
            </p>
            <p>
                En revanche, ton choix de sport peut parfois t’amener à t’entraîner avec une autre section,
                selon les lieux d’entraînement, les équipes disponibles ou les compétitions.
            </p>
        </div>

        <div class="highlight-panel">
            <h3>Encore un doute&nbsp;?</h3>
            <p>
                Si ta formation n’apparaît pas dans la liste, contacte l’AS amU.
                Indique ton diplôme, ton campus et le sport que tu souhaites pratiquer.
            </p>
            <div class="card-actions">
                <a class="btn btn-secondary" href="contact">Contacter l’AS amU</a>
                <a class="btn btn-secondary" href="sections">Voir les sections</a>
            </div>
        </div>
    </div>
</section>

<script>
    (() => {
        const searchInput = document.querySelector('#section-search');
        const cards = [...document.querySelectorAll('[data-section-card]')];
        const emptyMessage = document.querySelector('#no-section-result');

        if (!searchInput || !cards.length) return;

        const normalize = (value) => String(value || '')
            .normalize('NFD')
            .replace(/[\u0300-\u036f]/g, '')
            .toLocaleLowerCase('fr-FR')
            .trim();

        const updateResults = () => {
            const query = normalize(searchInput.value);
            let visibleCards = 0;

            cards.forEach((card) => {
                const keywords = normalize(card.dataset.keywords);
                const isVisible = query === '' || keywords.includes(query);

                card.classList.toggle('is-search-hidden', !isVisible);
                card.setAttribute('aria-hidden', String(!isVisible));

                if (isVisible) visibleCards += 1;
            });

            if (emptyMessage) emptyMessage.hidden = visibleCards !== 0;
        };

        searchInput.addEventListener('input', updateResults);
        searchInput.addEventListener('search', updateResults);
        updateResults();
    })();
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
