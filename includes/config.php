<?php
/**
 * Configuration centrale de la refonte AS amU.
 * Modifiez ici les contenus : navigation, sections, bureaux, permanences,
 * adhésion HelloAsso, calendrier, coachs et partenaires.
 */
declare(strict_types=1);

$site = [
    'name' => 'AS amU',
    'full_name' => 'Association Sportive Aix-Marseille Université',
    'tagline' => 'Sport universitaire amU',
    'site_url' => 'https://www.as-amu.fr',
    'social_image' => 'https://www.as-amu.fr/assets/img/logo-asamu-v2.png',
    'helloasso_url' => 'https://www.helloasso.com/associations/as-amu',
    'mysportu_url' => 'https://mysportu.com/',
    'instagram_url' => 'https://www.instagram.com/as__amu/',
    'email' => 'contact@as-amu.fr',
    'competition_email' => 'competition@as-amu.fr',
    'treasury_email' => 'tresorerie@as-amu.fr',
    'communication_email' => 'communication@as-amu.fr',
    'phone' => '06 23 92 89 14',
    'address' => 'Bât. Hexagone, 163 avenue de Luminy, 13009 Marseille',
    'legal_status' => 'Association déclarée régie par la loi du 1er juillet 1901',
    'rna_number' => 'W133017720',
    'siren_number' => '751 147 406',
    'siret_number' => '751 147 406 00019',
    'privacy_email' => 'contact@as-amu.fr',
    'host_name' => 'E-Frogg',
    'host_company' => 'E-Frogg SARL · SIREN 452 136 567',
    'host_address' => 'Promenade de la Rambla, ZAC du Plan de la Mer, 83270 Saint-Cyr-sur-Mer',
    'host_phone' => '04 42 32 90 23',
    'membership_price' => '10 €',
    'license_price' => '0 €',
    'season' => '2026/2027',
    'home_stats' => [
        ['number' => '12', 'label' => 'sections universitaires'],
        ['number' => '2 658', 'label' => 'adhérent·es', 'note' => 'Chiffres 2025-2026'],
        ['number' => '2 959', 'label' => 'licencié·es AS amU', 'note' => 'Chiffres 2025-2026'],
        ['number' => '2026/2027', 'label' => 'saison en cours'],
    ],
    'president_name' => 'Remy Casanova',
    'president_title' => 'Président de l’AS amU',
    'president_photo' => 'assets/img/president.jpg',
    'president_word' => '
<p>
    L’AS amU, à travers ses sections, ses coachs, son personnel et ses encadrants passionnés,
    s’engage aux côtés d’Aix-Marseille Université pour offrir à chaque étudiante et étudiant
    une expérience sportive riche, accessible et fédératrice.
</p>

<p>
    Qu’il s’agisse d’entraînements, de compétitions ou d’activités de loisir, nous plaçons
    le sport au cœur de l’épanouissement personnel et collectif. Notre ambition est simple :
    permettre à chacun de pratiquer, progresser, représenter son université et vivre pleinement
    les valeurs du sport universitaire.
</p>

<p>
    Ces valeurs fortes nous guident au quotidien :
</p>

<ul class="president-values">
    <li><strong>Partage</strong> — Tisser des liens au sein d’une communauté dynamique.</li>
    <li><strong>Engagement</strong> — Donner le meilleur de soi, sur et en dehors du terrain.</li>
    <li><strong>Inclusion</strong> — Faire du sport un espace ouvert à toutes et tous.</li>
    <li><strong>Durabilité</strong> — Encourager une pratique sportive respectueuse de l’environnement.</li>
</ul>
',
];

$nav = [
    ['label' => 'Accueil', 'url' => './'],
    [
        'label' => 'L’association',
        'url' => 'association',
        'children' => [
            ['label' => 'Organisation de l’association', 'url' => 'association'],
            ['label' => 'Statuts – règlement intérieur et charte', 'url' => 'statuts'],
            ['label' => 'Documents', 'url' => 'documents'],
            ['label' => 'Les commissions', 'url' => 'commissions'],
            ['label' => 'Comment adhérer ?', 'url' => 'adhesion'],
        ],
    ],
    [
        'label' => 'Les sections',
        'url' => 'sections',
        'children' => [
            ['label' => 'Toutes les sections', 'url' => 'sections'],
            ['label' => "À quelle section j'appartiens", 'url' => 'sections-appartenance'],
            ['label' => 'Carte des campus et sections', 'url' => 'campus'],
            ['label' => 'Disciplines sportives', 'url' => 'sections#sports'],
        ],
    ],
    [
        'label' => 'Compétitions',
        'url' => 'competitions',
        'children' => [
            ['label' => 'Préparer une compétition', 'url' => 'competitions'],
            ['label' => 'Calendrier des compétitions', 'url' => 'calendrier'],
            ['label' => 'Palmarès', 'url' => 'palmares'],
            ['label' => 'Coachs ' . $site['season'], 'url' => 'coachs'],
            ['label' => 'Documents compétition', 'url' => 'competitions#documents'],
        ],
    ],
    ['label' => 'Photothèque', 'url' => 'phototheque'],
    ['label' => 'Contact', 'url' => 'contact'],
];

/**
 * Uniformise les deux encarts éditoriaux affichés sur chaque fiche section.
 * Les textes restent propres à chaque section, sans dupliquer la structure.
 */
function section_blocks(string $competition, string $events): array
{
    return [
        [
            'title' => 'Compétitions',
            'kicker' => 'Représenter sa section',
            'paragraphs' => [$competition],
        ],
        [
            'title' => 'Vie de section',
            'kicker' => 'Événements et animations',
            'paragraphs' => [$events],
        ],
    ];
}

$sections = [
    [
        'slug' => 'aix-allsh',
        'name' => 'Aix ALLSH',
        'component' => 'Faculté des Arts, Lettres, Langues et Sciences humaines',
        'city' => 'Aix-en-Provence',
        'campus' => 'Aix',
        'address' => 'Bureau des sports, salle 010, bâtiment Egger, site Schumann, 29 avenue Robert Schuman, 13100 Aix-en-Provence',
        'email' => 'contact.aix.allsh@as-amu.fr',
        'office_hours' => 'Du lundi au jeudi de 12h à 14h',
        'adherents_count' => '425',
        'licensees_count' => '290',
        'activity_stats' => [
            ['number' => '15', 'label' => 'équipes engagées'],
        ],
        'bureau' => [
            ['role' => 'Présidence', 'name' => 'Kévin Lavalou'],
            ['role' => 'Trésorerie', 'name' => 'Conties Sylvain'],
            ['role' => 'Secrétariat', 'name' => 'Parat Odile'],
            ['role' => 'Membres étudiant·es', 'name' => 'Maelys Birou · Antonin Broudard · Mayssam Jebali'],
        ],
        'content_blocks' => [
            [
                'title' => 'Compétitions',
                'kicker' => 'ALLSH en compétition',
                'paragraphs' => [
                    'En 2025-2026, la section ALLSH engage 15 équipes : basket-ball féminin et masculin, handball féminin, volley-ball féminin et masculin, football masculin et football à 8 féminin, ainsi que rugby à 10 et rugby à 7 dans les catégories féminines et masculines.',
                    'La section propose des compétitions départementales, régionales et interrégionales, des championnats de France selon les qualifications, ainsi que des formations d’arbitrage en volley-ball.',
                ],
            ],
            [
                'title' => 'Événementiel',
                'kicker' => 'Animations, tournois, stages',
                'paragraphs' => [
                    'Des tournois de raquette et de sports collectifs, des activités forme et bien-être, ainsi que des sorties nature sont proposés au fil de l’année.',
                    'La section organise notamment une sortie kayak en mai. Les étudiant·es danseur·ses participent aussi aux spectacles de fin d’année.',
                ],
            ],
        ],
        'events' => [
            ['title' => 'Tournois de section', 'date' => 'Chaque semestre', 'description' => 'Tournois de raquette et de sports collectifs en soirée.'],
            ['title' => 'Sortie kayak', 'date' => 'Mai', 'description' => 'Sortie ou stage de découverte en pleine nature.'],
        ],
        'notes' => 'Pour une inscription ou un renseignement, rendez-vous au bureau des sports, salle 010 du bâtiment Egger.',
    ],
    [
        'slug' => 'aix-droit',
        'name' => 'Aix Droit',
        'component' => 'Faculté de Droit et de Science Politique',
        'city' => 'Aix-en-Provence',
        'campus' => 'Aix',
        'address' => 'Bureau des sports, sous-sol du bâtiment Pouillon, 3 avenue Robert Schuman, 13100 Aix-en-Provence',
        'email' => 'contact.aix.droit@as-amu.fr',
        'office_hours' => 'Du lundi au vendredi, de 12h à 14h',
        'adherents_count' => '355',
        'licensees_count' => '293',
        'activity_stats' => [
            ['number' => '14', 'label' => 'équipes engagées'],
        ],
        'bureau' => [
            ['role' => 'Présidence', 'name' => 'Bojidar Barbitch'],
            ['role' => 'Trésorerie', 'name' => 'Éric Valls'],
            ['role' => 'Secrétariat', 'name' => 'Pierre Canourgues'],
            ['role' => 'Membres étudiant·es', 'name' => 'Lucile Savatier · Cyrianne Verger · Romane Larue'],
        ],
        'content_blocks' => section_blocks('En 2025-2026, Aix Droit engage 14 équipes : basket-ball féminin et masculin, handball féminin et masculin, volley-ball féminin, football féminin et masculin, rugby à 10 masculin et rugby à 7 masculin.', 'En partenariat avec les sections aixoises : Nuit de la forme, nuit des sports collectifs, spectacles de danse et tournois de raquette.'),
        'events' => [['title' => 'Animations inter-sections', 'date' => 'Toute l’année', 'description' => 'Nuits sportives, spectacles de danse et tournois avec les autres sections d’Aix.']],
        'notes' => 'Le bureau des sports se trouve au sous-sol du bâtiment Pouillon, au fond du couloir à droite.',
    ],
    [
        'slug' => 'aix-feg',
        'name' => 'Aix FEG',
        'component' => 'Faculté d’Économie et de Gestion',
        'city' => 'Aix-en-Provence',
        'campus' => 'Aix',
        'address' => 'La Pauliane, 424 chemin du Viaduc, 13080 Aix-en-Provence',
        'map_query' => 'La Pauliane - Aix-Marseille Université, 424 chemin du Viaduc, 13080 Aix-en-Provence',
        'email' => 'contact.aix.feg@as-amu.fr',
        'office_hours' => 'Du lundi au vendredi, de 12h à 14h',
        'adherents_count' => '182',
        'licensees_count' => '188',
        'activity_stats' => [
            ['number' => '10', 'label' => 'équipes engagées'],
        ],
        'bureau' => [
            ['role' => 'Présidence', 'name' => 'Gilles Signoret'],
            ['role' => 'Trésorerie', 'name' => 'Amaury Borel'],
            ['role' => 'Secrétariat', 'name' => 'Chiara Masi'],
        ],
        'content_blocks' => section_blocks('En 2025-2026, Aix FEG engage 10 équipes : basket-ball féminin et masculin, handball masculin, volley-ball féminin et masculin, football masculin et football à 8 féminin.', 'Avec les autres sections, la FEG participe à la Nuit de la forme, à la Nuit des sports collectifs, aux spectacles de danse et aux tournois de raquette.'),
        'events' => [['title' => 'Animations inter-sections', 'date' => 'Toute l’année', 'description' => 'Rendez-vous sportifs organisés avec les autres sections aixoises.']],
        'notes' => 'Le bureau des sports est situé au premier étage de la faculté, sur le site Ferry.',
    ],
    [
        'slug' => 'aix-iae',
        'name' => 'Aix IAE',
        'component' => 'Institut d’Administration des Entreprises',
        'city' => 'Aix-en-Provence',
        'campus' => 'Aix',
        'address' => 'Chemin de la Quille, 13540 Aix-en-Provence',
        'email' => 'contact.aix-iae@as-amu.fr',
        'office_hours' => 'Lundi de 14h à 17h · mercredi de 10h à 12h',
        'adherents_count' => '44',
        'licensees_count' => '45',
        'activity_stats' => [
            ['number' => '2', 'label' => 'équipes engagées'],
        ],
        'bureau' => [
            ['role' => 'Présidence', 'name' => 'Valentine Bouly de Lesdain'],
        ],
        'content_blocks' => section_blocks('En 2025-2026, la section Aix IAE engage 2 équipes : une équipe de basket-ball masculin et une équipe de volley-ball masculin.', 'Des animations et tournois de section ou inter-sections, autour du sport, de la forme et du bien-être, rythment l’année.'),
        'events' => [['title' => 'Animations et tournois', 'date' => 'Toute l’année', 'description' => 'Événements organisés par la section et avec les autres sections.']],
        'notes' => 'Suivez les réseaux de la section IAE pour les créneaux d’entraînement et les inscriptions.',
    ],
    [
        'slug' => 'aix-impgt',
        'name' => 'Aix IMPGT',
        'component' => 'Institut de Management Public et Gouvernance Territoriale',
        'city' => 'Aix-en-Provence',
        'campus' => 'Aix',
        'address' => '21 rue Gaston-de-Saporta, 13100 Aix-en-Provence',
        'email' => 'contact.aix.impgt@as-amu.fr',
        'office_hours' => 'Du lundi au vendredi, de 9h à 12h et de 13h30 à 16h30',
        'adherents_count' => '60',
        'licensees_count' => '48',
        'activity_stats' => [
            ['number' => '2', 'label' => 'équipes engagées'],
        ],
        'bureau' => [
            ['role' => 'Présidence', 'name' => 'Sandrine Fournier'],
            ['role' => 'Trésorerie', 'name' => 'Marco Sardi'],
        ],
        'content_blocks' => section_blocks('En 2025-2026, l’IMPGT engage 2 équipes de football masculin. Elles affrontent les autres facultés en matchs amicaux ou en compétition.', 'La section met en avant les étudiant·es qui représentent l’institut dans les compétitions AS amU.'),
        'events' => [['title' => 'Football et basket-ball', 'date' => 'Selon calendrier', 'description' => 'Rencontres amicales et compétitions contre les autres facultés.']],
        'notes' => 'Contactez la section si vous souhaitez ouvrir une discipline ou proposer un événement sportif.',
    ],
    [
        'slug' => 'aix-iut',
        'name' => 'Aix IUT',
        'component' => 'Institut Universitaire de Technologie',
        'city' => 'Aix-en-Provence',
        'campus' => 'Aix',
        'address' => 'Institut universitaire de technologie, 413 avenue Gaston-Berger, 13100 Aix-en-Provence',
        'email' => 'contact.aix.iut@as-amu.fr',
        'office_hours' => 'Lundi et jeudi, de 9h30 à 12h30',
        'adherents_count' => '139',
        'licensees_count' => '134',
        'activity_stats' => [
            ['number' => '7', 'label' => 'équipes engagées'],
        ],
        'bureau' => [
            ['role' => 'Présidence', 'name' => 'Philippe Thibaut'],
            ['role' => 'Trésorerie', 'name' => 'Pascal Barnier'],
            ['role' => 'Membres étudiant·es', 'name' => 'Chloé Pacchioni · Lola Sarthou'],
        ],
        'content_blocks' => section_blocks('En 2025-2026, Aix IUT engage 7 équipes : basket-ball masculin, handball masculin, deux équipes de volley-ball masculin, deux équipes de football masculin et rugby à 10 masculin.', 'Le tournoi de rentrée rassemble les étudiant·es autour du football, volley-ball et de la pétanque. La section a aussi porté un challenge laser run et participé à la Coupe de France des IUT.'),
        'events' => [['title' => 'Tournoi de rentrée', 'date' => 'Rentrée universitaire', 'description' => 'Football, volley-ball et pétanque au CSU et au stade Ruocco.']],
        'notes' => 'Le bureau des sports est situé à côté de la cafétéria ; le secrétariat de chaque département peut également renseigner les étudiant·es.',
    ],
    [
        'slug' => 'aix-sciences',
        'name' => 'Aix Sciences',
        'component' => 'Faculté des Sciences',
        'city' => 'Aix-en-Provence',
        'campus' => 'Aix',
        'address' => 'Faculté des Sciences, site Montperrin, 6 avenue du Pigonnet, 13090 Aix-en-Provence',
        'email' => 'aix.sciences@as-amu.fr',
        'office_hours' => 'Du lundi au vendredi, de 12h à 13h',
        'adherents_count' => '76',
        'licensees_count' => '69',
        'activity_stats' => [
            ['number' => '4', 'label' => 'équipes engagées'],
        ],
        'bureau' => [
            ['role' => 'Présidence', 'name' => 'Caroline Rumeau'],
            ['role' => 'Membre étudiant', 'name' => 'Jawad Saidi'],
        ],
        'content_blocks' => section_blocks('En 2025-2026, Aix Sciences engage 4 équipes : basket-ball masculin, volley-ball féminin et masculin, ainsi que football masculin.', 'Des animations sportives inter-sections sont proposées aux adhérent·es et licencié·es du site.'),
        'events' => [['title' => 'Animations inter-sections', 'date' => 'Toute l’année', 'description' => 'Activités sportives ouvertes aux adhérent·es et licencié·es du site.']],
        'notes' => 'Le bureau des sports se situe dans le hall de la faculté de Montperrin.',
    ],
    [
        'slug' => 'marseille-centre',
        'name' => 'Marseille Centre',
        'component' => 'Campus Marseille Centre',
        'city' => 'Marseille',
        'campus' => 'Marseille',
        'address' => 'Bureau des sports, étage du gymnase Jassaud, campus Saint-Charles, 3 place Victor-Hugo, 13003 Marseille',
        'email' => 'contact.marseille.centre@as-amu.fr',
        'office_hours' => 'Du lundi au jeudi, de 8h30 à 16h · vendredi, de 8h30 à 12h30',
        'adherents_count' => '146',
        'licensees_count' => '139',
        'activity_stats' => [
            ['number' => '6', 'label' => 'équipes engagées'],
            ['number' => '14', 'label' => 'animations organisées'],
            ['number' => '813', 'label' => 'étudiant·es touché·es'],
        ],
        'bureau' => [
            ['role' => 'Présidence', 'name' => 'Manon Bezault'],
            ['role' => 'Trésorerie', 'name' => 'Linda Vigiola'],
            ['role' => 'Secrétariat', 'name' => 'Isabelle Chol · Bernard Camps'],
            ['role' => 'Coachs et encadrant·es', 'name' => 'Laura Delmas · Maxime Berrier'],
        ],
        'content_blocks' => section_blocks('En 2025-2026, Marseille Centre engage 6 équipes : basket-ball féminin et masculin, futsal masculin, handball féminin et masculin, ainsi qu’une équipe de volley-ball masculin.', 'La section a proposé 14 animations réparties sur les deux semestres : soirées sports collectifs, HIIT/CrossFit, Nuit de la forme, raquettes, judo, natation et Semaine Sérénité. Elles ont touché 813 étudiant·es.'),
        'events' => [
            ['title' => 'Soirée sports collectifs', 'date' => 'Semestre 1 · 2025-2026', 'description' => '23 étudiant·es touché·es.'],
            ['title' => 'Soirée HIIT / CrossFit', 'date' => 'Semestre 1 · 2025-2026', 'description' => '71 étudiant·es touché·es.'],
            ['title' => 'Soirée Nuit de la forme', 'date' => 'Semestre 1 · 2025-2026', 'description' => '69 étudiant·es touché·es.'],
            ['title' => 'Soirée raquettes', 'date' => 'Semestre 1 · 2025-2026', 'description' => '17 étudiant·es touché·es.'],
            ['title' => 'Nuit du judo', 'date' => 'Semestre 1 · 2025-2026', 'description' => '25 étudiant·es touché·es.'],
            ['title' => 'Soirée natation', 'date' => 'Semestre 1 · 2025-2026', 'description' => '25 étudiant·es touché·es.'],
            ['title' => 'Semaine Sérénité', 'date' => 'Semestre 1 · 2025-2026', 'description' => '232 étudiant·es touché·es.'],
            ['title' => 'Soirée sports collectifs', 'date' => 'Semestre 2 · 2025-2026', 'description' => '24 étudiant·es touché·es.'],
            ['title' => 'Soirée HIIT / CrossFit', 'date' => 'Semestre 2 · 2025-2026', 'description' => '48 étudiant·es touché·es.'],
            ['title' => 'Soirée Nuit de la forme', 'date' => 'Semestre 2 · 2025-2026', 'description' => '55 étudiant·es touché·es.'],
            ['title' => 'Soirée raquettes', 'date' => 'Semestre 2 · 2025-2026', 'description' => '22 étudiant·es touché·es.'],
            ['title' => 'Nuit du judo', 'date' => 'Semestre 2 · 2025-2026', 'description' => '25 étudiant·es touché·es.'],
            ['title' => 'Soirée natation', 'date' => 'Semestre 2 · 2025-2026', 'description' => '25 étudiant·es touché·es.'],
            ['title' => 'Semaine Sérénité', 'date' => 'Semestre 2 · 2025-2026', 'description' => '152 étudiant·es touché·es.'],
        ],
        'notes' => 'L’équipe enseignante et le secrétariat accueillent les étudiant·es au bureau des sports du gymnase Jassaud.',
    ],
    [
        'slug' => 'marseille-etoile',
        'name' => 'Marseille Étoile',
        'component' => 'Faculté des Sciences',
        'city' => 'Marseille',
        'campus' => 'Marseille',
        'address' => 'Faculté des Sciences, site Saint-Jérôme, avenue Escadrille-Normandie-Niemen, 13013 Marseille',
        'email' => 'contact.marseille.etoile@as-amu.fr',
        'office_hours' => 'Lundi de 14h à 17h · mercredi de 10h à 12h',
        'adherents_count' => '311',
        'licensees_count' => '294',
        'activity_stats' => [
            ['number' => '14', 'label' => 'équipes engagées'],
            ['number' => '13', 'label' => 'rendez-vous organisés'],
            ['number' => '516', 'label' => 'étudiant·es touché·es'],
        ],
        'bureau' => [
            ['role' => 'Présidence', 'name' => 'Didier Omiros'],
            ['role' => 'Trésorerie', 'name' => 'Céline Capelle'],
            ['role' => 'Secrétariat', 'name' => 'Gina Ferrero'],
            ['role' => 'Coachs et encadrant·es', 'name' => 'Grégory Capelle · Thomas Poletti'],
        ],
        'content_blocks' => section_blocks('En 2025-2026, Marseille Étoile engage 14 équipes : deux équipes de basket-ball, futsal et football masculins, une équipe de handball masculin, cinq équipes de volley-ball masculin, ainsi qu’une équipe féminine de basket-ball et de handball.', 'Treize rendez-vous ont animé la saison : accueil des étudiant·es, tournois, animations de sports de raquette, natation et volley-ball, sorties nature et spectacle de danse. Ils ont touché 516 étudiant·es.'),
        'events' => [
            ['title' => 'Village sport IUT', 'date' => '4 & 11 septembre 2026', 'description' => '207 étudiant·es touché·es : 80 en volley-ball, 9 en tennis, 70 en basket-ball et 48 en pétanque.'],
            ['title' => 'Journées d’accueil IUT CG & INSPE', 'date' => '8 & 19 septembre 2025', 'description' => '105 étudiant·es touché·es : 40 à l’IUT CG et 65 à l’INSPE.'],
            ['title' => 'Festival Danse d’Aix', 'date' => '17 septembre 2025', 'description' => '15 étudiant·es touché·es.'],
            ['title' => 'Tournoi de padel', 'date' => 'Semestre 1 · 2025-2026', 'description' => '13 étudiant·es touché·es.'],
            ['title' => 'Tournoi découverte de pickleball', 'date' => 'Semestre 1 · 2025-2026', 'description' => '4 étudiant·es touché·es.'],
            ['title' => 'Tournoi de badminton', 'date' => 'Semestre 1 · 2025-2026', 'description' => '20 étudiant·es touché·es.'],
            ['title' => 'Animation natation', 'date' => 'Semestre 1 · 2025-2026', 'description' => '10 étudiant·es touché·es.'],
            ['title' => 'Animation volley-ball', 'date' => 'Semestre 1 · 2025-2026', 'description' => '48 étudiant·es touché·es.'],
            ['title' => 'Animation tennis & padel', 'date' => '7 mai 2026', 'description' => '16 étudiant·es touché·es.'],
            ['title' => 'Animation paddle board PMT', 'date' => '23 mai 2026', 'description' => '10 étudiant·es touché·es.'],
            ['title' => 'Randonnée VTT', 'date' => '9 & 30 mai 2026', 'description' => '10 étudiant·es touché·es.'],
            ['title' => 'Sorties trail expert · 20 km', 'date' => '12 & 19 mai 2026', 'description' => '13 étudiant·es touché·es.'],
            ['title' => 'Spectacle de danse', 'date' => '29 mai 2026', 'description' => '45 étudiant·es touché·es · qualifié·es CFU.'],
        ],
        'notes' => 'Les horaires indiqués sont ceux de la permanence de la section au site Saint-Jérôme.',
    ],
    [
        'slug' => 'marseille-fss',
        'name' => 'Marseille FSS',
        'component' => 'Faculté des Sciences du Sport',
        'city' => 'Marseille',
        'campus' => 'Marseille',
        'address' => 'Cité universitaire de Luminy, bâtiment C, 13009 Marseille',
        'email' => 'contact.marseille.fss@as-amu.fr',
        'office_hours' => 'Non communiqué',
        'adherents_count' => '592',
        'licensees_count' => '718',
        'activity_stats' => [
            ['number' => '33', 'label' => 'équipes engagées'],
        ],
        'bureau' => [
            ['role' => 'Présidence', 'name' => 'Christophe Bourdin'],
            ['role' => 'Trésorerie', 'name' => 'Rémy Casanova'],
            ['role' => 'Secrétariat', 'name' => 'Anne Pujol'],
            ['role' => 'Membres étudiant·es', 'name' => 'Lalie Carlier · Carla Aillaud · Enzo Tanzy'],
        ],
        'content_blocks' => section_blocks('En 2025-2026, la FSS engage 33 équipes : basket-ball, futsal, handball, volley-ball, football et rugby, dans les catégories féminines et masculines. La section aligne notamment 8 équipes de volley-ball masculin, 7 équipes de football masculin, 5 équipes de handball masculin et 4 équipes de basket-ball masculin.', 'Des animations de section et inter-sections — tournois, activités forme et bien-être — dynamisent la vie du campus.'),
        'events' => [['title' => 'Animations sportives', 'date' => 'Toute l’année', 'description' => 'Tournois et activités forme et bien-être sur le campus.']],
        'notes' => 'Les horaires de permanence ne sont pas publiés ; contactez la section avant de vous déplacer.',
    ],
    [
        'slug' => 'marseille-luminy',
        'name' => 'Marseille Luminy',
        'component' => 'Faculté des Sciences de Luminy',
        'city' => 'Marseille',
        'campus' => 'Marseille',
        'address' => 'Faculté des Sciences de Luminy, 163 avenue de Luminy, 13009 Marseille',
        'email' => 'contact.marseille.luminy@as-amu.fr',
        'office_hours' => 'Lundi de 14h à 17h · mercredi de 10h à 12h',
        'adherents_count' => '163',
        'licensees_count' => '171',
        'activity_stats' => [
            ['number' => '7', 'label' => 'équipes engagées'],
        ],
        'bureau' => [
            ['role' => 'Présidence', 'name' => 'Éric Hanania'],
            ['role' => 'Trésorerie', 'name' => 'Pierre Rutgé'],
            ['role' => 'Secrétariat', 'name' => 'Solène Vilfroy'],
            ['role' => 'Membre étudiante', 'name' => 'Solène Bernard-Cervera'],
            ['role' => 'Coachs et encadrant·es', 'name' => 'Nathalie Dray · Michaël Chiarazzo · François Zmyslony · Olivier Sarda'],
        ],
        'content_blocks' => section_blocks('En 2025-2026, la section Marseille Luminy engage 7 équipes : basket-ball féminin et masculin, handball féminin et masculin, volley-ball féminin et masculin, ainsi qu’une équipe féminine de football à 8.', 'Des tournois et animations autour du sport et du bien-être sont proposés. Un stage de ski est envisagé pour permettre la découverte ou le perfectionnement en montagne.'),
        'events' => [['title' => 'Stage de ski', 'date' => 'Selon programmation', 'description' => 'Projet de séjour à la montagne pour découvrir ou progresser en ski.']],
        'notes' => 'Contactez la section pour les créneaux d’entraînement, les équipes et les modalités de participation.',
    ],
    [
        'slug' => 'marseille-sante-timone',
        'name' => 'Marseille Santé Timone',
        'component' => 'Faculté des Sciences Médicales et Paramédicales',
        'city' => 'Marseille',
        'campus' => 'Marseille',
        'address' => 'Faculté des Sciences Médicales et Paramédicales, 27 boulevard Jean-Moulin, 13385 Marseille',
        'email' => 'contact.marseille.timone@as-amu.fr',
        'office_hours' => 'Du lundi au vendredi, de 9h à 13h',
        'adherents_count' => '241',
        'licensees_count' => '251',
        'activity_stats' => [
            ['number' => '10', 'label' => 'équipes engagées'],
            ['number' => '4', 'label' => 'animations organisées'],
            ['number' => '410', 'label' => 'étudiant·es touché·es'],
        ],
        'bureau' => [
            ['role' => 'Présidence', 'name' => 'Cyprien Caracena'],
            ['role' => 'Trésorerie', 'name' => 'Catherine Roger'],
            ['role' => 'Secrétariat', 'name' => 'Gilles Gravier'],
            ['role' => 'Membres étudiant·es', 'name' => 'Emma Valbert · Hugo Di Giorgio'],
            ['role' => 'Coachs et encadrant·es', 'name' => 'Cyprien Caracena (handball) · Christine Cordinier (volley-ball) · Pierre Gadilhe (rugby) · Catherine Roger (danse)'],
        ],
        'content_blocks' => section_blocks('En 2025-2026, la section Santé Timone engage 10 équipes : deux équipes de basket-ball masculin, ainsi que des équipes de futsal, handball, volley-ball, football et rugby à 10 masculins ; une équipe de basket-ball, handball et volley-ball féminins.', 'La section organise quatre animations sur l’année universitaire : les tournois de badminton et de volley-ball des semestres 1 et 2, une soirée salsa au semestre 2 et des animations danse sur les deux semestres. Elles ont réuni 410 étudiant·es.'),
        'events' => [
            ['title' => 'Tournois de badminton', 'date' => 'Semestres 1 & 2 · 2025-2026', 'description' => 'Deux rendez-vous de badminton ayant touché 120 étudiant·es.'],
            ['title' => 'Tournois de volley-ball', 'date' => 'Semestres 1 & 2 · 2025-2026', 'description' => 'Deux tournois de volley-ball ayant touché 160 étudiant·es.'],
            ['title' => 'Soirée salsa', 'date' => 'Semestre 2 · 2025-2026', 'description' => 'Une soirée salsa ayant réuni 80 étudiant·es.'],
            ['title' => 'Animations danse', 'date' => 'Semestres 1 & 2 · 2025-2026', 'description' => 'Des animations danse avec Nathalie, ayant touché 50 étudiant·es.'],
        ],
        'notes' => 'La permanence accueille les étudiant·es pour les informations et inscriptions.',
    ],
];

$sports = [
    'Sports aquatiques' => ['Natation', 'Natation synchronisée', 'Water-polo'],
    'Athlétisme & endurance' => ['Indoor', 'Estival', '10 km', 'Épreuves combinées', 'Trail', 'Triathlon', 'Swim and run', 'Bike and run', 'Cross-country'],
    'Sports de combat' => ['Judo', 'Jujitsu', 'Karaté', 'Boxe', 'Kick-boxing', 'Muay-thaï', 'Lutte', 'Sambo', 'Savate française', 'Taekwondo'],
    'Sports de raquettes' => ['Tennis', 'Badminton', 'Tennis de table', 'Padel', 'Pelote basque'],
    'Sports collectifs petits terrains' => ['Basket 3×3', 'Futsal', 'Handball', 'Volley 4×4', 'Beach-volley', 'Hockey', 'Ultimate', 'Water-polo'],
    'Activités nautiques' => ['Aviron de mer', 'Aviron de rivière', 'Aviron indoor', 'Canoë-kayak', 'Voile', 'Stand-up paddle', 'Surf'],
    'Sports d’armes' => ['Escrime', 'Tir à l’arc', 'Tir'],
    'Gym & danse' => ['Danse', 'Gym artistique', 'Gym rythmique', 'Trampoline', 'Teamgym'],
    'Sports collectifs grands terrains' => ['Football 11/8', 'Football américain', 'Rugby 7/13/15'],
    'Plein air' => ['Escalade', 'Équitation', 'Golf', 'Ski nordique', 'Ski alpin'],
    'Force' => ['Haltérophilie', 'Musculation', 'Force athlétique'],
    'Cyclisme & mécanique' => ['Cyclisme route', 'VTT', 'Bike and run', 'Karting'],
];

$adhesionSteps = [
    ['title' => 'Choisir sa section', 'text' => 'La section dépend de ta composante AMU, pas du sport. Utilise la page Sections pour trouver la bonne section avant de payer.'],
    ['title' => 'Payer l’adhésion sur HelloAsso', 'text' => 'L’adhésion se fait maintenant via HelloAsso. Le lien officiel est centralisé dans le fichier includes/config.php.'],
    ['title' => 'Recevoir la confirmation', 'text' => 'Conserve le mail de confirmation HelloAsso. Il pourra être demandé par ta section ou par l’AS amU.'],
    ['title' => 'Créer ou compléter son compte MySportU', 'text' => 'Pour la compétition universitaire, complète ensuite ta demande de licence FFSU sur MySportU.'],
    ['title' => 'Attendre la validation', 'text' => 'La section et l’AS amU vérifient ton dossier. Tu seras informé·e lorsque la licence est validée.'],
];

$competitionDocs = [
    ['label' => 'Formulaire compétition', 'url' => 'https://forms.gle/fFL3JK7qVr1NQccs7'],
    ['label' => 'Budget prévisionnel AS amU', 'url' => 'https://www.as-amu.fr/wp-content/uploads/2026/05/Fiche-budget-previsionnel-AS-amU-COMPETITION-VF.xlsx'],
    ['label' => 'Note de frais / remboursement', 'url' => 'https://www.as-amu.fr/wp-content/uploads/2025/09/Fiche-remboursement-AS-AMU-vierge.xlsx'],
    ['label' => 'Demande minibus', 'url' => 'https://forms.gle/7RopYfzP93ctFjFg6'],
    ['label' => 'Validation véhicule personnel', 'url' => 'https://forms.gle/qoVGoEmQA1bpqv6Q9'],
];

$associationDocuments = [
    ['label' => 'Statuts de l’AS amU', 'type' => 'Gouvernance', 'description' => 'Document officiel qui précise l’objet de l’association, son fonctionnement et ses instances.', 'url' => 'assets/documents/as_amu_statuts_signes_ages_14_mai.pdf'],
    ['label' => 'Règlement intérieur', 'type' => 'Gouvernance', 'description' => 'Règles de fonctionnement interne, droits et devoirs des adhérent·es et des sections.', 'url' => 'assets/documents/as_amu_projet_ri_vdef_signes.pdf'],
    ['label' => 'Charte des valeurs', 'type' => 'Engagement', 'description' => 'Valeurs, principes éthiques et recommandations applicables aux activités de l’AS amU.', 'url' => 'assets/documents/as_amu_charte_des_valeurs.pdf'],
];

$commissions = [
    ['name' => 'Commission sport', 'mission' => 'Suit les demandes de participation, les calendriers, les engagements et les priorités sportives.', 'members' => 'Équipe sportive AS amU', 'contact' => $site['competition_email']],
    ['name' => 'communication', 'mission' => 'Coordonne les actualités, les réseaux sociaux, les visuels, les résultats et la valorisation des sections.', 'members' => 'Chiara Masi', 'contact' => $site['communication_email']],
    ['name' => 'finances', 'mission' => 'Accompagne les budgets, remboursements, achats, justificatifs et suivis financiers des projets.', 'members' => 'Amaury Borel et Céline Capelle', 'contact' => $site['treasury_email']],
];

$associationStats = [
    ['number' => '2012', 'label' => 'création de l’AS amU'],
    ['number' => '12', 'label' => 'sections universitaires'],
    ['number' => '2 658', 'label' => 'adhérent·es'],
    ['number' => '2 959', 'label' => 'licencié·es AS amU'],
];

$associationBoard = [
    'year' => '2026',
    'contact' => 'bureau@as-amu.fr',
    'president' => [
        'name' => 'Rémy Casanova',
        'role' => 'Président',
        'initials' => 'RC',
    ],
    'poles' => [
        [
            'name' => 'Gouvernance & finances',
            'accent' => 'yellow',
            'members' => [
                ['name' => 'Céline Capelle', 'role' => 'Trésorière', 'initials' => 'CC'],
                ['name' => 'Anne Pujol', 'role' => 'Secrétaire générale · AG et CA', 'initials' => 'AP'],
                ['name' => 'Amaury Borel', 'role' => 'Trésorier adjoint', 'initials' => 'AB'],
                ['name' => 'Pierre Rutgé', 'role' => 'Secrétaire adjoint · partenariats et commissions', 'initials' => 'PR'],
            ],
        ],
        [
            'name' => 'Sport & moyens',
            'accent' => 'blue',
            'members' => [
                ['name' => 'Gilles Signoret', 'role' => 'Vice-président sport', 'initials' => 'GS'],
                ['name' => 'Didier Omiros', 'role' => 'Vice-président moyens', 'initials' => 'DO'],
            ],
        ],
        [
            'name' => 'Développement & communication',
            'accent' => 'green',
            'members' => [
                ['name' => 'Lou Charlot', 'role' => 'Chargée de développement et de coordination', 'initials' => 'LC'],
                ['name' => 'Andréa Sorel', 'role' => 'Chargée de développement et de coordination', 'initials' => 'AS'],
                ['name' => 'Chiara Masi', 'role' => 'Secrétaire adjointe · communication', 'initials' => 'CM'],
            ],
        ],
    ],
];

/**
 * Calendrier des compétitions.
 *
 * Ajoutez uniquement les rendez-vous validés par l'AS amU. Les champs
 * start_date et end_date sont au format AAAA-MM-JJ ; end_date est facultatif
 * pour une compétition d'une seule journée.
 *
 * Exemple :
 * [
 *     'id' => 'cfdu-football-2027',
 *     'start_date' => '2027-05-18',
 *     'end_date' => '2027-05-20',
 *     'title' => 'CFDU Football',
 *     'sport' => 'Football',
 *     'level' => 'National',
 *     'status' => 'Inscriptions ouvertes',
 *     'place' => 'Ville à confirmer',
 *     'section' => 'Sélection AS amU',
 *     'description' => 'Informations pratiques et modalités de sélection.',
 *     'registration_deadline' => '2027-04-30',
 *     'url' => 'https://…', // facultatif
 * ],
 */
$competitionCalendar = [
    [
        'id' => 'cfu-trail-2027',
        'start_date' => '2027-06-04',
        'title' => 'Championnat de France universitaire de trail',
        'sport' => 'Trail',
        'level' => 'National',
        'status' => 'À venir',
        'place' => 'La Réunion',
        'section' => 'Étudiant·es AS amU sélectionné·es',
        'description' => 'Championnat de France universitaire de trail, organisé le vendredi 4 juin 2027 à La Réunion.',
        'image' => 'assets/img/calendar/cfu-trail-reunion-2027.jpg',
        'image_alt' => 'Affiche officielle du Championnat de France universitaire de trail 2027 à La Réunion',
    ],
];

// Ajoutez ici les chiffres validés par l’association lorsqu’ils sont disponibles.
$coachStats = [];

$coachInfo = [
    'intro' => 'Bienvenue sur la page dédiée aux coachs sportifs. Retrouvez ici les disciplines encadrées, les contacts des coachs et les indications pour les sports sans coach identifié.',
    'warning' => 'Pour garantir le bon déroulement du remboursement et du suivi compétition, l’étudiant·e doit respecter les étapes de validation avant tout engagement de frais.',
    'sports_without_coach' => ['Biathlon', 'Bridge', 'Bike and Run', 'Cyclisme', 'Course d’Orientation', 'Canoë-Kayak', 'VTT', 'Échecs', 'Équitation', 'Escrime', 'Football Américain', 'Force athlétique', 'Futsal', 'Golf', 'Haltérophilie', 'Hockey', 'Karting', 'Ski', 'Squash', 'Ultimate', 'Voile', 'Water-Polo'],
    'sports_with_coach' => ['Athlétisme', 'Aviron', 'Badminton', 'Basket 3×3', 'Basket 5×5', 'Beach Handball', 'Beach Volley', 'Boxes', 'Cross Country', 'Danse', 'Escalade', 'Football', 'Handball', 'Judo', 'Natation', 'Padel', 'Rugby', 'Taekwondo', 'Tennis', 'Tir à l’arc', 'Trail', 'Triathlon', 'Volley-ball'],
];

$coaches = [
    ['sport' => 'Athlétisme', 'contacts' => [['name' => 'MALVEZIN Erwin', 'email' => 'erwin.malvezin@gmail.com']]],
    ['sport' => 'Aviron', 'contacts' => [['name' => 'HAMDAN Marc', 'email' => 'marc.hamdan@gmail.com']]],
    ['sport' => 'Badminton', 'contacts' => [['name' => 'CHIARAZZO Michaël', 'email' => 'michael.chiarazzo@univ-amu.fr']]],
    ['sport' => 'Basket-ball', 'contacts' => [
        ['name' => 'BOUSGARBIES Vincent', 'email' => 'vincent.bousgarbies@univ-amu.fr', 'note' => 'Féminin'],
        ['name' => 'BARBITCH Bojidar', 'email' => 'b.barbitch@univ-amu.fr', 'note' => 'Masculin'],
        ['name' => 'CALLEWAERT Jules', 'email' => 'julescallewaert1@gmail.com'],
        ['name' => 'GUEROULT Anaïs', 'email' => 'anais.gueroult@etu.univ-amu.fr'],
    ]],
    ['sport' => 'Beach handball', 'contacts' => [['name' => 'CARACENA Cyprien', 'email' => 'cyprien.CARACENA@univ-amu.fr']]],
    ['sport' => 'Beach volley', 'contacts' => [['name' => 'THIBAUT Philippe', 'email' => 'philippe.thibaut@univ-amu.fr']]],
    ['sport' => 'BMX', 'contacts' => [['name' => 'THIVOLLE Yannick', 'email' => 'yannick.thivolle@hotmail.fr']]],
    ['sport' => 'Boxes', 'contacts' => [
        ['name' => 'LAVALOU Kevin', 'email' => 'kevin.LAVALOU@univ-amu.fr'],
        ['name' => 'STIOUI Matthieu', 'email' => 'matthieu.stioui@univ-amu.fr'],
    ]],
    ['sport' => 'Cross country', 'contacts' => [['name' => 'BARNIER Pascal', 'email' => 'pascal.barnier@univ-amu.fr']]],
    ['sport' => 'Danse', 'contacts' => [
        ['name' => 'CAPELLE Céline', 'email' => 'celine.CAPELLE@univ-amu.fr', 'note' => 'Danse contemporaine'],
        ['name' => 'DRAY Nathalie', 'email' => 'nathalie.dray@univ-amu.fr'],
        ['name' => 'MOUCHNINO Laurence', 'email' => 'laurence.mouchnino@univ-amu.fr'],
        ['name' => 'ROGER Catherine', 'email' => 'catherine.ROGER@univ-amu.fr'],
        ['name' => 'PHILIP Julie', 'email' => 'julie.philip@univ-amu.fr', 'note' => 'Hip hop'],
    ]],
    ['sport' => 'Escalade', 'contacts' => [['name' => 'VALLS Éric', 'email' => 'eric.VALLS@univ-amu.fr']]],
    ['sport' => 'Football', 'contacts' => [
        ['name' => 'BOURDIN Christophe', 'email' => 'christophe.bourdin@univ-amu.fr', 'note' => 'Féminin'],
        ['name' => 'DAMACHE Yamna', 'email' => 'yamna.damache@hotmail.fr', 'note' => 'Féminin'],
        ['name' => 'BOREL Amaury', 'email' => 'amaury.BOREL@univ-amu.fr', 'note' => 'Masculin N2'],
        ['name' => 'FERRER Romain', 'email' => 'romain.FERRER@univ-amu.fr', 'note' => 'Masculin N2'],
        ['name' => 'MAUREL Philippe', 'email' => 'philippe.maurel@univ-amu.fr', 'note' => 'CFDU'],
        ['name' => 'SIGNORET Gilles', 'email' => 'gilles.signoret@univ-amu.fr', 'note' => 'CFDU'],
    ]],
    ['sport' => 'Handball', 'contacts' => [
        ['name' => 'CAPELLE Grégory', 'email' => 'gregory.capelle@univ-amu.fr', 'note' => 'Féminin'],
        ['name' => 'CARACENA Cyprien', 'email' => 'cyprien.CARACENA@univ-amu.fr', 'note' => 'Masculin'],
    ]],
    ['sport' => 'Judo', 'contacts' => [
        ['name' => 'BONIFAY Jean-Claude', 'email' => 'jean-claude.bonifay@univ-amu.fr'],
        ['name' => 'DELMAS Laura', 'email' => 'laura.DELMAS@univ-amu.fr'],
        ['name' => 'GIALLURACHIS Damien', 'email' => 'damien.giallurachis@univ-amu.fr'],
        ['name' => 'RUTGÉ Pierre', 'email' => 'pierre.RUTGE@univ-amu.fr'],
    ]],
    ['sport' => 'Natation', 'contacts' => [
        ['name' => 'CORRADI Michaël', 'email' => 'michael.CORRADI@univ-amu.fr'],
        ['name' => 'LINGELBACH Jérémy', 'email' => 'jlingelbach@marseille.fr'],
    ]],
    ['sport' => 'Padel', 'contacts' => [['name' => 'BARNIER Pascal', 'email' => 'pascal.barnier@univ-amu.fr']]],
    ['sport' => 'Rugby', 'contacts' => [
        ['name' => 'CONTIES Sylvain', 'email' => 'sylvain.CONTIES@univ-amu.fr', 'note' => 'Féminin'],
        ['name' => 'CANOURGUES Pierre', 'email' => 'pierre.CANOURGUES@univ-amu.fr', 'note' => 'Masculin'],
    ]],
    ['sport' => 'Taekwondo', 'contacts' => [['name' => 'CONTENSUZAS Benoit', 'email' => 'benoit.CONTENSUZAS@univ-amu.fr']]],
    ['sport' => 'Tennis', 'contacts' => [
        ['name' => 'OMIROS Didier', 'email' => 'didier.omiros@univ-amu.fr', 'note' => 'Féminin'],
        ['name' => 'POLETTI Thomas', 'email' => 'thomas.POLETTI@univ-amu.fr', 'note' => 'Masculin'],
    ]],
    ['sport' => 'Tir à l’arc', 'contacts' => [['name' => 'BOREL Amaury', 'email' => 'amaury.BOREL@univ-amu.fr']]],
    ['sport' => 'Trail', 'contacts' => [['name' => 'ROSIER Fabien', 'email' => 'fabien.rosier@univ-amu.fr']]],
    ['sport' => 'Triathlon', 'contacts' => [['name' => 'CORRADI Michaël', 'email' => 'michael.CORRADI@univ-amu.fr']]],
    ['sport' => 'Volley-ball', 'contacts' => [
        ['name' => 'THIBAUT Philippe', 'email' => 'philippe.thibaut@univ-amu.fr', 'note' => 'Féminin 6×6'],
        ['name' => 'HANANIA Éric', 'email' => 'eric.HANANIA@univ-amu.fr', 'note' => 'Masculin 6×6 et 4×4'],
    ]],
];

$partners = [
    [
        'name' => 'Aix-Marseille Université',
        'url' => 'https://www.univ-amu.fr/',
        'description' => 'Université partenaire de l’association.',
        'logo' => 'assets/img/partners/amu.webp',
    ],
    [
        'name' => 'FFSU',
        'url' => 'https://sport-u.com/',
        'description' => 'Fédération du sport universitaire.',
        'logo' => 'assets/img/partners/ffsu.webp',
    ],
    [
        'name' => 'SUAPS AMU',
        'url' => 'https://sport-suaps.univ-amu.fr/fr/',
        'description' => 'Service universitaire des activités physiques et sportives.',
        'logo' => 'assets/img/partners/suaps.webp',
    ],
    [
        'name' => 'CROUS Aix-Marseille Avignon',
        'url' => 'https://www.crous-aix-marseille.fr',
        'description' => 'Partenaire de la vie étudiante.',
        'logo' => 'assets/img/partners/crous.webp',
    ],
    [
        'name' => 'Rent A Car',
        'url' => 'https://www.rentacar.fr/',
        'description' => 'Partenaire mobilité et location de véhicules.',
        'logo' => 'assets/img/partners/rent-a-car.webp',
    ],
    [
        'name' => 'Sport Pro Group',
        'url' => 'https://www.sportprogroup.fr/',
        'description' => 'Nouveau partenaire équipement sportif.',
        'logo' => 'assets/img/partners/sport-pro-group.png',
    ],
];

/**
 * Photothèque : ajoutez vos fichiers dans assets/img/gallery/, puis créez une
 * entrée ici. Chaque photo peut avoir une catégorie, un titre et une légende.
 *
 * Exemple :
 * [
 *     'image' => 'assets/img/gallery/basket-cfu-2025-2026.jpg',
 *     'alt' => 'Équipe de basket AS amU lors du CFU 2025/2026',
 *     'category' => 'Basket',
 *     'title' => 'Équipe basket 2025/2026',
 *     'description' => 'Fini 1er au CFU.',
 * ],
 */
$photoGallery = [
    [
        'image' => 'assets/img/gallery/football-cfdu-valenciennes.jpg',
        'alt' => 'Équipe de football AS amU championne de France universitaire au CFDU de Valenciennes',
        'category' => 'Football',
        'title' => 'Champions de France universitaires — CFDU 2025/2026',
        'description' => 'L’équipe de football de l’AS amU championne de France universitaire lors du Championnat de France universitaire (CFDU) 2025/2026, du 9 au 11 juin à Valenciennes. Entraîneurs : Gilles Signoret et Philippe Maurel.',
    ],
    [
        'image' => 'assets/img/gallery/handball-fisu-pessac.jpg',
        'alt' => 'Équipe de France universitaire de handball championne du monde aux FISU de Pessac',
        'category' => 'Handball',
        'title' => 'Champions du monde universitaires — FISU 2025/2026',
        'description' => 'Champions du monde universitaires aux FISU 2025/2026 de Pessac, du 20 au 27 juin. Grégory Capelle, coach de l’AS amU et entraîneur officiel de l’équipe de France universitaire, avec Mathilde Plais, Louane Garcia et Robin Moliex.',
    ],
];

/**
 * Extraction brute des résultats nationaux importés depuis le tableau Excel.
 * Elle est conservée comme source de contrôle ; elle n’est pas affichée.
 *
 * Exemple :
 * [
 *     'last_name' => 'DUPONT',
 *     'first_name' => 'Camille',
 *     'result' => 'Or',
 *     'place' => 1,
 * ],
 */
$nationalResultsRaw = [
    ['last_name' => 'BOTTA', 'first_name' => 'BAPTISTE', 'result' => '2e / 11', 'place' => 2],
    ['last_name' => 'KARA', 'first_name' => 'YETER', 'result' => '4e / 11', 'place' => 4],
    ['last_name' => 'KARA', 'first_name' => 'SEVIN', 'result' => '5e / 12', 'place' => 5],
    ['last_name' => 'PAPPALARDO', 'first_name' => 'MANON', 'result' => '2e / 12', 'place' => 2],
    ['last_name' => 'VACHIER', 'first_name' => 'GAETAN', 'result' => '4e / 6', 'place' => 4],
    ['last_name' => 'BONNARD', 'first_name' => 'Kazya', 'result' => '4e / 6', 'place' => 4],
    ['last_name' => 'CAIAZZO', 'first_name' => 'Lola', 'result' => '3e / 6', 'place' => 3],
    ['last_name' => 'HOLLANDER', 'first_name' => 'Jade', 'result' => '3e / 10', 'place' => 3],
    ['last_name' => 'VIERNE', 'first_name' => 'Léa', 'result' => '5e / 22', 'place' => 5],
    ['last_name' => 'BAZOT', 'first_name' => 'Florent', 'result' => 'Or', 'place' => 1],
    ['last_name' => 'BOUYAHIAOUI', 'first_name' => 'Nisrine', 'result' => 'Or', 'place' => 1],
    ['last_name' => 'DOUCE', 'first_name' => 'Axel', 'result' => 'Bronze', 'place' => 3],
    ['last_name' => 'HABBOUB', 'first_name' => 'Brahim', 'result' => 'Bronze', 'place' => 3],
    ['last_name' => 'HENNET', 'first_name' => 'Axel', 'result' => 'Bronze', 'place' => 3],
    ['last_name' => 'KENZOU', 'first_name' => 'Mohamed Amine', 'result' => 'Bronze', 'place' => 3],
    ['last_name' => 'PREMONT', 'first_name' => 'Louanne', 'result' => 'Or', 'place' => 1],
    ['last_name' => 'TELLIEZ-MORENI', 'first_name' => 'Manolo', 'result' => 'Or', 'place' => 1],
    ['last_name' => 'VAUQUELIN', 'first_name' => 'Marie Lou', 'result' => 'Or', 'place' => 1],
    ['last_name' => 'SALLE', 'first_name' => 'Gaia', 'result' => 'Or', 'place' => 1],
    ['last_name' => 'FOTI', 'first_name' => 'Gabriel', 'result' => 'Bronze', 'place' => 3],
    ['last_name' => 'CASTEL', 'first_name' => 'Thomas', 'result' => '5e', 'place' => 5],
    ['last_name' => 'CARINI', 'first_name' => 'Alois', 'result' => 'Argent', 'place' => 2],
    ['last_name' => 'HIBON', 'first_name' => 'Bertrand', 'result' => 'Argent', 'place' => 2],
    ['last_name' => 'BOUFELLAH', 'first_name' => 'Ghylas', 'result' => 'Argent', 'place' => 2],
    ['last_name' => 'LUNETTA FLEURY', 'first_name' => 'Laurens', 'result' => '5e', 'place' => 5],
    ['last_name' => 'DEMICHEV', 'first_name' => 'Stepan', 'result' => 'Or', 'place' => 1],
    ['last_name' => 'SAADALLAH', 'first_name' => 'SEPHIRA', 'result' => '5e', 'place' => 5],
    ['last_name' => 'GAY', 'first_name' => 'QUENTIN', 'result' => '5e / 8', 'place' => 5],
    ['last_name' => 'ISAMBARD', 'first_name' => 'Titouan', 'result' => '1er / 16', 'place' => 1],
    ['last_name' => 'ROMIEU', 'first_name' => 'Antonin', 'result' => '2e / 16', 'place' => 2],
    ['last_name' => 'SID', 'first_name' => 'Alicia', 'result' => 'Argent', 'place' => 2],
    ['last_name' => 'MAZENOD', 'first_name' => 'Ines', 'result' => '5e', 'place' => 5],
    ['last_name' => 'COLIN', 'first_name' => 'Zachary', 'result' => 'Argent', 'place' => 2],
    ['last_name' => 'SALAH', 'first_name' => 'Amine', 'result' => 'Argent', 'place' => 2],
    ['last_name' => 'BRISSONNEAU', 'first_name' => 'FELIX', 'result' => '5e', 'place' => 5],
    ['last_name' => 'GRIMALDI', 'first_name' => 'LAURIS', 'result' => '5e', 'place' => 5],
    ['last_name' => 'MOUTAMA', 'first_name' => 'FREDERIC', 'result' => 'Argent', 'place' => 2],
    ['last_name' => 'COENEN', 'first_name' => 'Alexandre', 'result' => 'Argent', 'place' => 2],
    ['last_name' => 'MEDERBEL', 'first_name' => 'Salim', 'result' => '4e', 'place' => 4],
    ['last_name' => 'DELISLE', 'first_name' => 'Riowen', 'result' => '4e', 'place' => 4],
    ['last_name' => 'PREMONT', 'first_name' => 'Louanne', 'result' => 'Argent', 'place' => 2],
    ['last_name' => 'DANNEELS', 'first_name' => 'Salome', 'result' => 'Argent', 'place' => 2],
    ['last_name' => 'VAUQUELIN', 'first_name' => 'Marie-Lou', 'result' => 'Or', 'place' => 1],
    ['last_name' => 'DE BARBARIN', 'first_name' => 'Oscar', 'result' => '5e', 'place' => 5],
    ['last_name' => 'COSTA', 'first_name' => 'CECILIA', 'result' => '5e', 'place' => 5],
    ['last_name' => 'DUDON', 'first_name' => 'NOHAN', 'result' => '3e', 'place' => 3],
    ['last_name' => 'BELASRI', 'first_name' => 'BOUAZA', 'result' => '3e', 'place' => 3],
    ['last_name' => 'DIASSINOUSMENDIL', 'first_name' => 'JADE', 'result' => '1er / 6', 'place' => 1],
    ['last_name' => 'HANSALI', 'first_name' => 'JOUMENE', 'result' => '3e', 'place' => 3],
    ['last_name' => 'Bounin', 'first_name' => 'Carole', 'result' => 'Or', 'place' => 1],
    ['last_name' => 'DESSE', 'first_name' => 'Iléana', 'result' => 'Bronze', 'place' => 3],
    ['last_name' => 'PLANQUART', 'first_name' => 'Lily', 'result' => '4e / 7 · 3e / 5', 'place' => 3],
    ['last_name' => 'CHARDON', 'first_name' => 'Etienne', 'result' => '4e / 8', 'place' => 4],
    ['last_name' => 'SEGOND', 'first_name' => 'Oriane', 'result' => '3e / 6', 'place' => 3],
    ['last_name' => 'BOMMARITO', 'first_name' => 'Baptiste', 'result' => 'Bronze', 'place' => 3],
    ['last_name' => 'DETTORI', 'first_name' => 'Alessandro', 'result' => 'Or', 'place' => 1],
    ['last_name' => 'ELOUARDI', 'first_name' => 'Yasmine', 'result' => 'Or', 'place' => 1],
    ['last_name' => 'HERNANDEZ', 'first_name' => 'Alexis', 'result' => 'Or', 'place' => 1],
    ['last_name' => 'JOUET-PASTRE', 'first_name' => 'Louis', 'result' => 'Or', 'place' => 1],
    ['last_name' => 'PREMONT', 'first_name' => 'Louanne', 'result' => 'Or', 'place' => 1],
    ['last_name' => 'GROSS', 'first_name' => 'Emmy', 'result' => 'Bronze', 'place' => 3],
    ['last_name' => 'TELLIEZ-MORENI', 'first_name' => 'Manolo', 'result' => '5e', 'place' => 5],
    ['last_name' => 'COLLARD', 'first_name' => 'Thibaut', 'result' => '3e / 69 · 4e / 69', 'place' => 3],
    ['last_name' => 'MICHAUD', 'first_name' => 'Arthur', 'result' => '2e / 18', 'place' => 2],
    ['last_name' => 'BROCHARD--URBAN', 'first_name' => 'Rébecca', 'result' => '4e / 8', 'place' => 4],
    ['last_name' => 'AUGE', 'first_name' => 'Léonie', 'result' => 'Argent', 'place' => 2],
    ['last_name' => 'BOUVAREL', 'first_name' => 'Camille', 'result' => 'Argent', 'place' => 2],
    ['last_name' => 'BOYER-DURAND', 'first_name' => 'Anaelle', 'result' => 'Argent', 'place' => 2],
    ['last_name' => 'CARVALHO', 'first_name' => 'Alizée', 'result' => 'Argent', 'place' => 2],
    ['last_name' => 'CHERNAI', 'first_name' => 'Camille', 'result' => 'Argent', 'place' => 2],
    ['last_name' => 'COUDRAY', 'first_name' => 'Manon', 'result' => 'Bronze · Argent', 'place' => 2],
    ['last_name' => 'COUSSON', 'first_name' => 'Bertille', 'result' => 'Or · Bronze · Argent', 'place' => 1],
    ['last_name' => 'GUZAITE', 'first_name' => 'Ema', 'result' => 'Bronze · Argent', 'place' => 2],
    ['last_name' => 'LALOUZE', 'first_name' => 'Adeline', 'result' => 'Argent', 'place' => 2],
    ['last_name' => 'MARTIAL', 'first_name' => 'Noah', 'result' => 'Argent', 'place' => 2],
    ['last_name' => 'PEREZ', 'first_name' => 'Ilyana', 'result' => 'Bronze · Argent', 'place' => 2],
    ['last_name' => 'RIBARDIERE', 'first_name' => 'Anna', 'result' => 'Argent', 'place' => 2],
    ['last_name' => 'VIGUIER', 'first_name' => 'Alix', 'result' => 'Argent · Bronze · Or', 'place' => 1],
    ['last_name' => 'JEBALI', 'first_name' => 'Mayssam', 'result' => 'Or', 'place' => 1],
    ['last_name' => 'MARCHETTI', 'first_name' => 'Bruna', 'result' => 'Bronze', 'place' => 3],
    ['last_name' => 'EL BIGA', 'first_name' => 'Saloua Laurina', 'result' => 'Argent', 'place' => 2],
    ['last_name' => 'LE VAN', 'first_name' => 'Jade', 'result' => '4e', 'place' => 4],
];

/**
 * Palmarès affiché : ajoutez ici un tableau par podium national (or, argent
 * ou bronze).
 * Le sport est affiché sur chaque carte. Pour une discipline collective,
 * utilisez seulement « team => true » : l’équipe AS amU sera affichée à la
 * place des noms des athlètes.
 *
 * Exemple :
 * [
 *     'season' => '2025/2026',
 *     'sport' => 'Basket',
 *     'competition' => 'CFDU',
 *     'last_name' => 'DUPONT',
 *     'first_name' => 'Camille',
 *     'result' => 'Or',
 *     'place' => 1,
 * ],
 *
 * Pour un sport collectif, remplacez les noms par « team => true ».
 * Exemple : Football, CFDU, Équipe AS amU, Champion de France.
 */
$palmares = [
    ['sport' => 'Taekwondo', 'last_name' => 'BOTTA', 'first_name' => 'BAPTISTE', 'result' => '2e / 11', 'place' => 2],
    ['sport' => 'Taekwondo', 'last_name' => 'PAPPALARDO', 'first_name' => 'MANON', 'result' => '2e / 12', 'place' => 2],
    ['sport' => 'Athlétisme Indoor', 'last_name' => 'CAIAZZO', 'first_name' => 'Lola', 'result' => '3e / 6', 'place' => 3],
    ['sport' => 'Athlétisme Indoor', 'last_name' => 'HOLLANDER', 'first_name' => 'Jade', 'result' => '3e / 10', 'place' => 3],
    ['sport' => 'Kick Boxing', 'last_name' => 'BAZOT', 'first_name' => 'Florent', 'result' => 'Or', 'place' => 1],
    ['sport' => 'Kick Boxing', 'last_name' => 'BOUYAHIAOUI', 'first_name' => 'Nisrine', 'result' => 'Or', 'place' => 1],
    ['sport' => 'Kick Boxing', 'last_name' => 'DOUCE', 'first_name' => 'Axel', 'result' => 'Bronze', 'place' => 3],
    ['sport' => 'Kick Boxing', 'last_name' => 'HABBOUB', 'first_name' => 'Brahim', 'result' => 'Bronze', 'place' => 3],
    ['sport' => 'Kick Boxing', 'last_name' => 'HENNET', 'first_name' => 'Axel', 'result' => 'Bronze', 'place' => 3],
    ['sport' => 'Kick Boxing', 'last_name' => 'KENZOU', 'first_name' => 'Mohamed Amine', 'result' => 'Bronze', 'place' => 3],
    ['sport' => 'Kick Boxing', 'last_name' => 'PREMONT', 'first_name' => 'Louanne', 'result' => 'Or', 'place' => 1],
    ['sport' => 'Kick Boxing', 'last_name' => 'TELLIEZ-MORENI', 'first_name' => 'Manolo', 'result' => 'Or', 'place' => 1],
    ['sport' => 'Kick Boxing', 'last_name' => 'VAUQUELIN', 'first_name' => 'Marie Lou', 'result' => 'Or', 'place' => 1],
    ['sport' => 'Judo kyus', 'last_name' => 'SALLE', 'first_name' => 'Gaia', 'result' => 'Or', 'place' => 1],
    ['sport' => 'Judo kyus', 'last_name' => 'FOTI', 'first_name' => 'Gabriel', 'result' => 'Bronze', 'place' => 3],
    ['sport' => 'Judo kyus', 'last_name' => 'CARINI', 'first_name' => 'Alois', 'result' => 'Argent', 'place' => 2],
    ['sport' => 'Judo kyus', 'last_name' => 'HIBON', 'first_name' => 'Bertrand', 'result' => 'Argent', 'place' => 2],
    ['sport' => 'Judo kyus', 'last_name' => 'BOUFELLAH', 'first_name' => 'Ghylas', 'result' => 'Argent', 'place' => 2],
    ['sport' => 'Judo kyus', 'last_name' => 'DEMICHEV', 'first_name' => 'Stepan', 'result' => 'Or', 'place' => 1],
    ['sport' => 'Squash', 'last_name' => 'ISAMBARD', 'first_name' => 'Titouan', 'result' => '1er / 16', 'place' => 1],
    ['sport' => 'Squash', 'last_name' => 'ROMIEU', 'first_name' => 'Antonin', 'result' => '2e / 16', 'place' => 2],
    ['sport' => 'Gymnastique Rythmique', 'last_name' => 'SID', 'first_name' => 'Alicia', 'result' => 'Argent', 'place' => 2],
    ['sport' => 'Judo 1d', 'last_name' => 'COLIN', 'first_name' => 'Zachary', 'result' => 'Argent', 'place' => 2],
    ['sport' => 'Judo 1d', 'last_name' => 'SALAH', 'first_name' => 'Amine', 'result' => 'Argent', 'place' => 2],
    ['sport' => 'Boxe Combat individuel', 'last_name' => 'MOUTAMA', 'first_name' => 'FREDERIC', 'result' => 'Argent', 'place' => 2],
    ['sport' => 'Boxe Combat individuel', 'last_name' => 'COENEN', 'first_name' => 'Alexandre', 'result' => 'Argent', 'place' => 2],
    ['sport' => 'Boxe Assaut', 'last_name' => 'PREMONT', 'first_name' => 'Louanne', 'result' => 'Argent', 'place' => 2],
    ['sport' => 'Boxe Assaut', 'last_name' => 'DANNEELS', 'first_name' => 'Salome', 'result' => 'Argent', 'place' => 2],
    ['sport' => 'Boxe Assaut', 'last_name' => 'VAUQUELIN', 'first_name' => 'Marie-Lou', 'result' => 'Or', 'place' => 1],
    ['sport' => 'Karaté', 'last_name' => 'DUDON', 'first_name' => 'NOHAN', 'result' => '3e', 'place' => 3],
    ['sport' => 'Karaté', 'last_name' => 'BELASRI', 'first_name' => 'BOUAZA', 'result' => '3e', 'place' => 3],
    ['sport' => 'Karaté', 'last_name' => 'DIASSINOUSMENDIL', 'first_name' => 'JADE', 'result' => '1er / 6', 'place' => 1],
    ['sport' => 'Karaté', 'last_name' => 'HANSALI', 'first_name' => 'JOUMENE', 'result' => '3e', 'place' => 3],
    ['season' => '2025/2026', 'sport' => 'Cheerleading', 'competition' => 'CFU', 'team' => true, 'team_name' => 'Équipe AS amU ALLSH (Eagles)', 'result' => 'Or', 'place' => 1],
    ['season' => '2025/2026', 'sport' => 'Cheerleading', 'competition' => 'CFU', 'team' => true, 'team_name' => 'Équipe AS amU Droit Aix (Lexis)', 'result' => 'Bronze', 'place' => 3],
    ['sport' => 'Cyclisme VTT', 'last_name' => 'PLANQUART', 'first_name' => 'Lily', 'result' => '3e / 5', 'place' => 3],
    ['sport' => 'Haltérophilie', 'last_name' => 'SEGOND', 'first_name' => 'Oriane', 'result' => '3e / 6', 'place' => 3],
    ['sport' => 'Badminton en équipe N2', 'team' => true, 'result' => 'Bronze', 'place' => 3],
    ['sport' => 'Boxe Française', 'team' => true, 'result' => 'Or', 'place' => 1],
    ['sport' => 'Boxe Française', 'last_name' => 'GROSS', 'first_name' => 'Emmy', 'result' => 'Bronze', 'place' => 3],
    ['sport' => 'Escalade', 'last_name' => 'COLLARD', 'first_name' => 'Thibaut', 'result' => '3e / 69', 'place' => 3],
    ['sport' => 'Tir sportif', 'last_name' => 'MICHAUD', 'first_name' => 'Arthur', 'result' => '2e / 18', 'place' => 2],
    ['sport' => 'Natation individuel et équipe', 'team' => true, 'result' => 'Or · Argent · Bronze', 'place' => 1],
    ['sport' => 'Pétanque', 'team' => true, 'result' => 'Or · Argent · Bronze', 'place' => 1],
    ['season' => '2025/2026', 'sport' => 'Football', 'competition' => 'CFDU', 'team' => true, 'result' => 'Champion de France', 'place' => 1],

    // Saison 2024/2025 — source : feuille « Médaillés amU ».
    ['season' => '2024/2025', 'sport' => 'Kick boxing', 'competition' => 'CFU', 'last_name' => 'AYALA', 'first_name' => 'Océane', 'result' => 'Or', 'place' => 1],
    ['season' => '2024/2025', 'sport' => 'Kick boxing', 'competition' => 'CFU', 'last_name' => 'BABAGBETO', 'first_name' => 'Napua', 'result' => 'Or', 'place' => 1],
    ['season' => '2024/2025', 'sport' => 'Kick boxing', 'competition' => 'CFU', 'last_name' => 'DETTORI', 'first_name' => 'Alessandro', 'result' => 'Or', 'place' => 1],
    ['season' => '2024/2025', 'sport' => 'Kick boxing', 'competition' => 'CFU', 'last_name' => 'ASSAEL', 'first_name' => 'Jade', 'result' => 'Bronze', 'place' => 3],
    ['season' => '2024/2025', 'sport' => 'Kick boxing', 'competition' => 'CFU', 'last_name' => 'BROUSSE', 'first_name' => 'Sarah', 'result' => 'Or', 'place' => 1],
    ['season' => '2024/2025', 'sport' => 'Kick boxing', 'competition' => 'CFU', 'last_name' => 'JOUET-PASTRE', 'first_name' => 'Louis', 'result' => 'Or', 'place' => 1],
    ['season' => '2024/2025', 'sport' => 'Kick boxing', 'competition' => 'CFU', 'last_name' => 'TELLIEZ-MORENI', 'first_name' => 'Manolo', 'result' => 'Bronze', 'place' => 3],
    ['season' => '2024/2025', 'sport' => 'Kick boxing', 'competition' => 'CFU', 'last_name' => 'MICHEL', 'first_name' => 'Evan', 'result' => 'Bronze', 'place' => 3],
    ['season' => '2024/2025', 'sport' => 'Kick boxing', 'competition' => 'CFU', 'last_name' => 'KENZOU', 'first_name' => 'Mohamed-Amine', 'result' => 'Or', 'place' => 1],
    ['season' => '2024/2025', 'sport' => 'Kick boxing', 'competition' => 'CFU', 'last_name' => 'OUDJETT', 'first_name' => 'Ouais', 'result' => 'Bronze', 'place' => 3],
    ['season' => '2024/2025', 'sport' => 'Kick boxing', 'competition' => 'CFU', 'last_name' => 'VAUQUELIN', 'first_name' => 'Marie Lou', 'result' => 'Or', 'place' => 1],

    ['season' => '2024/2025', 'sport' => 'Judo', 'competition' => 'CFU 2e division', 'last_name' => 'HDIDI', 'first_name' => 'Safia', 'result' => 'Bronze', 'place' => 3],
    ['season' => '2024/2025', 'sport' => 'Judo', 'competition' => 'CFU 2e division', 'last_name' => 'BERTHE', 'first_name' => 'Marie', 'result' => 'Argent', 'place' => 2],
    ['season' => '2024/2025', 'sport' => 'Judo', 'competition' => 'CFU 2e division', 'last_name' => 'LECLERCQ', 'first_name' => 'Ambre', 'result' => 'Bronze', 'place' => 3],
    ['season' => '2024/2025', 'sport' => 'Judo', 'competition' => 'CFU 2e division', 'last_name' => 'DI MARINO', 'first_name' => 'Chiara', 'result' => 'Bronze', 'place' => 3],
    ['season' => '2024/2025', 'sport' => 'Judo', 'competition' => 'CFU 2e division', 'last_name' => 'MAZENOD', 'first_name' => 'Inès', 'result' => 'Or', 'place' => 1],
    ['season' => '2024/2025', 'sport' => 'Judo', 'competition' => 'CFU 2e division', 'last_name' => 'ASCHIERI', 'first_name' => 'Guenièvre', 'result' => 'Bronze', 'place' => 3],
    ['season' => '2024/2025', 'sport' => 'Judo kyus', 'competition' => 'CFU — Kyus filles', 'last_name' => 'PECORINI', 'first_name' => 'Téa', 'result' => 'Or', 'place' => 1],
    ['season' => '2024/2025', 'sport' => 'Judo kyus', 'competition' => 'CFU — Kyus filles', 'last_name' => 'SAADALLAH', 'first_name' => 'Séphira', 'result' => 'Argent', 'place' => 2],
    ['season' => '2024/2025', 'sport' => 'Judo kyus', 'competition' => 'CFU — Kyus garçons', 'last_name' => 'BOUFELLAH', 'first_name' => 'Ghylas', 'result' => 'Bronze', 'place' => 3],
    ['season' => '2024/2025', 'sport' => 'Judo kyus', 'competition' => 'CFU — Kyus garçons', 'last_name' => 'FIORI', 'first_name' => 'Andréa', 'result' => 'Bronze', 'place' => 3],
    ['season' => '2024/2025', 'sport' => 'Judo kyus', 'competition' => 'CFU — Kyus garçons', 'last_name' => 'KULE KYAKAKALA', 'first_name' => 'Saturnin', 'result' => 'Bronze', 'place' => 3],
    ['season' => '2024/2025', 'sport' => 'Judo kyus', 'competition' => 'CFU — Kyus garçons', 'last_name' => 'HIBON', 'first_name' => 'Bertrand', 'result' => 'Bronze', 'place' => 3],

    ['season' => '2024/2025', 'sport' => 'Athlétisme estival', 'competition' => 'CFU', 'last_name' => 'BOUIJOUX', 'first_name' => 'Aucéane', 'result' => 'Or', 'place' => 1],
    ['season' => '2024/2025', 'sport' => 'Athlétisme indoor', 'competition' => 'CFU', 'last_name' => 'BOUIJOUX', 'first_name' => 'Aucéane', 'result' => 'Or', 'place' => 1],
    ['season' => '2024/2025', 'sport' => 'Taekwondo', 'competition' => 'CFU', 'last_name' => 'AGBOGBE-SALANON', 'first_name' => 'Oswin', 'result' => 'Bronze', 'place' => 3],
    ['season' => '2024/2025', 'sport' => 'Taekwondo', 'competition' => 'CFU', 'last_name' => 'PAPPALARDO', 'first_name' => 'Manon', 'result' => 'Argent', 'place' => 2],

    ['season' => '2024/2025', 'sport' => 'Boxe anglaise', 'competition' => 'CFU', 'last_name' => 'BERLIN', 'first_name' => 'Espenelle', 'result' => 'Or', 'place' => 1],
    ['season' => '2024/2025', 'sport' => 'Boxe anglaise', 'competition' => 'CFU', 'last_name' => 'BONNEFOND', 'first_name' => 'Clément', 'result' => 'Or', 'place' => 1],
    ['season' => '2024/2025', 'sport' => 'Boxe anglaise', 'competition' => 'CFU', 'last_name' => 'BOUYAHIAOUI', 'first_name' => 'Nisrine', 'result' => 'Bronze', 'place' => 3],
    ['season' => '2024/2025', 'sport' => 'Boxe anglaise', 'competition' => 'CFU', 'last_name' => 'MEDJ', 'first_name' => 'Chirine', 'result' => 'Bronze', 'place' => 3],
    ['season' => '2024/2025', 'sport' => 'Boxe anglaise', 'competition' => 'CFU', 'last_name' => 'MOUTAMA', 'first_name' => 'Frédéric', 'result' => 'Or', 'place' => 1],
    ['season' => '2024/2025', 'sport' => 'Boxe anglaise', 'competition' => 'CFU', 'last_name' => 'ROUISSI', 'first_name' => 'Mehdi', 'result' => 'Bronze', 'place' => 3],
    ['season' => '2024/2025', 'sport' => 'Boxe anglaise', 'competition' => 'CFU', 'last_name' => 'VAUQUELIN', 'first_name' => 'Marie Lou', 'result' => 'Argent', 'place' => 2],
    ['season' => '2024/2025', 'sport' => 'Boxe française', 'competition' => 'CFU', 'team' => true, 'result' => 'Or', 'place' => 1],
    ['season' => '2024/2025', 'sport' => 'Badminton', 'competition' => 'CFU — Double mixte', 'team' => true, 'result' => 'Bronze', 'place' => 3],
    ['season' => '2024/2025', 'sport' => 'Aviron indoor', 'competition' => 'CFU — Relais mixte', 'team' => true, 'result' => 'Argent', 'place' => 2],
    ['season' => '2024/2025', 'sport' => 'Pancrace', 'competition' => 'CFU N2', 'last_name' => 'PRINCE', 'first_name' => 'Nessa', 'result' => 'Argent', 'place' => 2],
    ['season' => '2024/2025', 'sport' => 'Squash', 'competition' => 'CDF', 'last_name' => 'ISAMBARD', 'first_name' => 'Titouan', 'result' => 'Or', 'place' => 1],
    ['season' => '2024/2025', 'sport' => 'Gymnastique rythmique et sportive', 'competition' => 'CFU', 'last_name' => 'ILGART', 'first_name' => 'Iseult', 'result' => 'Or', 'place' => 1],

    ['season' => '2024/2025', 'sport' => 'Natation', 'competition' => 'CFU', 'last_name' => 'COUSSON', 'first_name' => 'Bertille', 'result' => 'Or — 100 m dos', 'place' => 1],
    ['season' => '2024/2025', 'sport' => 'Natation', 'competition' => 'CFU', 'last_name' => 'COUSSON', 'first_name' => 'Bertille', 'result' => 'Or — 100 m 4 nages', 'place' => 1],
    ['season' => '2024/2025', 'sport' => 'Natation', 'competition' => 'CFU', 'last_name' => 'COUSSON', 'first_name' => 'Bertille', 'result' => 'Or — Combiné', 'place' => 1],
    ['season' => '2024/2025', 'sport' => 'Natation', 'competition' => 'CFU', 'last_name' => 'COUSSON', 'first_name' => 'Bertille', 'result' => 'Bronze — 50 m dos', 'place' => 3],
    ['season' => '2024/2025', 'sport' => 'Trampoline', 'competition' => 'CFU — Individuel', 'last_name' => 'PIRYT', 'first_name' => 'Loris', 'result' => 'Or', 'place' => 1],
    ['season' => '2024/2025', 'sport' => 'Trampoline', 'competition' => 'CFU — Individuel', 'last_name' => 'CHASSANG', 'first_name' => 'Diane', 'result' => 'Or', 'place' => 1],
    ['season' => '2024/2025', 'sport' => 'Trampoline', 'competition' => 'CFU — Équipe', 'team' => true, 'result' => 'Argent', 'place' => 2],

    ['season' => '2024/2025', 'sport' => 'Judo 1re division', 'competition' => 'CFU 1re division', 'last_name' => 'GRIMONT', 'first_name' => 'Eloïse', 'result' => 'Bronze', 'place' => 3],
    ['season' => '2024/2025', 'sport' => 'Judo 1re division', 'competition' => 'CFU 1re division', 'last_name' => 'LEDIEU', 'first_name' => 'Théo', 'result' => 'Argent', 'place' => 2],
    ['season' => '2024/2025', 'sport' => 'Judo 1re division', 'competition' => 'CFU 1re division', 'last_name' => 'SALAH', 'first_name' => 'Amine', 'result' => 'Bronze', 'place' => 3],
    ['season' => '2024/2025', 'sport' => 'Judo 1re division', 'competition' => 'CFU 1re division', 'last_name' => 'MANFRUELLI', 'first_name' => 'Néo', 'result' => 'Bronze', 'place' => 3],
    ['season' => '2024/2025', 'sport' => 'Breaking hip-hop', 'competition' => 'CFU — B-girl', 'last_name' => 'GARIN', 'first_name' => 'Enola', 'result' => 'Argent', 'place' => 2],
    ['season' => '2024/2025', 'sport' => 'Breaking hip-hop', 'competition' => 'CFU — B-boy', 'last_name' => 'KANTOUCAR', 'first_name' => 'Jean Aël', 'result' => 'Argent', 'place' => 2],
    ['season' => '2024/2025', 'sport' => 'Breaking hip-hop', 'competition' => 'CFU — Showcase', 'team' => true, 'result' => 'Argent', 'place' => 2],

    ['season' => '2024/2025', 'sport' => 'Basket 5x5 féminin', 'competition' => 'CFU N2', 'team' => true, 'result' => 'Argent', 'place' => 2],
    ['season' => '2024/2025', 'sport' => 'Volley-ball féminin', 'competition' => 'CFU N1', 'team' => true, 'result' => 'Or', 'place' => 1],
    ['season' => '2024/2025', 'sport' => 'Handball masculin', 'competition' => 'CFU N2', 'team' => true, 'result' => 'Argent', 'place' => 2],
    ['season' => '2024/2025', 'sport' => 'Football', 'competition' => 'CFDU', 'team' => true, 'result' => 'Argent', 'place' => 2],
    ['season' => '2024/2025', 'sport' => 'Escalade', 'competition' => 'CFU — Combiné', 'last_name' => 'COLLARD', 'first_name' => 'Thibaut', 'result' => 'Bronze', 'place' => 3],
    ['season' => '2024/2025', 'sport' => 'Escalade', 'competition' => 'CFU — Combiné équipe', 'team' => true, 'result' => 'Bronze', 'place' => 3],
    ['season' => '2024/2025', 'sport' => 'Beach volley', 'competition' => 'CFU', 'team' => true, 'result' => 'Or', 'place' => 1],
    ['season' => '2024/2025', 'sport' => 'Judo', 'competition' => 'EUSA Pologne', 'last_name' => 'MANFRUELI', 'first_name' => 'Néo', 'result' => 'Bronze', 'place' => 3],
    ['season' => '2024/2025', 'sport' => 'Kick boxing', 'competition' => 'EUSA Pologne', 'last_name' => 'BROUSSE', 'first_name' => 'Sarah', 'result' => 'Argent', 'place' => 2],

    // Saison 2023/2024 — source : « Résultats AS.AMU saison 23-24 ».
    // Uniquement les podiums CFU, EUSA et Championnat du monde.
    ['season' => '2023/2024', 'sport' => 'Kick boxing', 'competition' => 'CFU', 'last_name' => 'ORIS', 'first_name' => 'Nolan', 'result' => 'Or — Kick light -83 kg', 'place' => 1],
    ['season' => '2023/2024', 'sport' => 'Kick boxing', 'competition' => 'CFU', 'last_name' => 'LARRERA DE MOREL', 'first_name' => 'Maximilien', 'result' => 'Or — Kick pré-combat -65 kg', 'place' => 1],
    ['season' => '2023/2024', 'sport' => 'Kick boxing', 'competition' => 'CFU', 'last_name' => 'DETTORI', 'first_name' => 'Alessandro', 'result' => 'Or — K-1 -67 kg', 'place' => 1],
    ['season' => '2023/2024', 'sport' => 'Kick boxing', 'competition' => 'CFU', 'last_name' => 'ASSAEL', 'first_name' => 'Jade', 'result' => 'Bronze — Kick light -55 kg', 'place' => 3],
    ['season' => '2023/2024', 'sport' => 'Kick boxing', 'competition' => 'CFU', 'last_name' => 'BROUSSE', 'first_name' => 'Sarah', 'result' => 'Or — Kick light -60 kg', 'place' => 1],
    ['season' => '2023/2024', 'sport' => 'Kick boxing', 'competition' => 'CFU', 'last_name' => 'BERLIN', 'first_name' => 'Espenelle', 'result' => 'Or — Kick light +70 kg', 'place' => 1],
    ['season' => '2023/2024', 'sport' => 'Kick boxing', 'competition' => 'CFU', 'last_name' => 'TELLIEZ-MORENI', 'first_name' => 'Manolo', 'result' => 'Or — Kick pré-combat -59 kg', 'place' => 1],
    ['season' => '2023/2024', 'sport' => 'Kick boxing', 'competition' => 'CFU', 'last_name' => 'SAMMUT', 'first_name' => 'Séréna', 'result' => 'Or — Kick pré-combat -50 kg', 'place' => 1],
    ['season' => '2023/2024', 'sport' => 'Kick boxing', 'competition' => 'CFU', 'last_name' => 'KENZOU', 'first_name' => 'Mohamed-Amine', 'result' => 'Or — Kick pré-combat -59 kg', 'place' => 1],
    ['season' => '2023/2024', 'sport' => 'Kick boxing', 'competition' => 'CFU', 'last_name' => "M'RAIHI", 'first_name' => 'Mael', 'result' => 'Bronze — Kick pré-combat -77 kg', 'place' => 3],

    ['season' => '2023/2024', 'sport' => 'Judo', 'competition' => 'CFU 2e division', 'last_name' => 'HDIDI', 'first_name' => 'Safia', 'result' => 'Argent — -48 kg', 'place' => 2],
    ['season' => '2023/2024', 'sport' => 'Judo', 'competition' => 'CFU 2e division', 'last_name' => 'HDIDI', 'first_name' => 'Safia', 'result' => 'Bronze — -52 kg', 'place' => 3],
    ['season' => '2023/2024', 'sport' => 'Judo', 'competition' => 'CFU 2e division', 'last_name' => 'HDIDI', 'first_name' => 'Safia', 'result' => 'Bronze — -52 kg', 'place' => 3],
    ['season' => '2023/2024', 'sport' => 'Judo', 'competition' => 'CFU 2e division', 'last_name' => 'HDIDI', 'first_name' => 'Safia', 'result' => 'Bronze — -60 kg', 'place' => 3],
    ['season' => '2023/2024', 'sport' => 'Judo kyus', 'competition' => 'CFU — Kyus filles', 'last_name' => 'HDIDI', 'first_name' => 'Safia', 'result' => 'Or — -48 kg bleu/marron', 'place' => 1],
    ['season' => '2023/2024', 'sport' => 'Judo kyus', 'competition' => 'CFU — Kyus filles', 'last_name' => 'HDIDI', 'first_name' => 'Safia', 'result' => 'Bronze — -63 kg bleu/marron', 'place' => 3],
    ['season' => '2023/2024', 'sport' => 'Judo kyus', 'competition' => 'CFU — Kyus garçons', 'last_name' => 'HDIDI', 'first_name' => 'Safia', 'result' => 'Argent — -60 kg orange/verte', 'place' => 2],
    ['season' => '2023/2024', 'sport' => 'Judo kyus', 'competition' => 'CFU — Kyus garçons', 'last_name' => 'HDIDI', 'first_name' => 'Safia', 'result' => 'Or — -66 kg orange/verte', 'place' => 1],
    ['season' => '2023/2024', 'sport' => 'Judo kyus', 'competition' => 'CFU — Kyus garçons', 'last_name' => 'HDIDI', 'first_name' => 'Safia', 'result' => 'Or — -66 kg bleu/marron', 'place' => 1],
    ['season' => '2023/2024', 'sport' => 'Judo kyus', 'competition' => 'CFU — Kyus garçons', 'last_name' => 'HDIDI', 'first_name' => 'Safia', 'result' => 'Bronze — -73 kg orange/verte', 'place' => 3],
    ['season' => '2023/2024', 'sport' => 'Judo kyus', 'competition' => 'CFU — Kyus garçons', 'last_name' => 'HDIDI', 'first_name' => 'Safia', 'result' => 'Bronze — -81 kg bleu/marron', 'place' => 3],
    ['season' => '2023/2024', 'sport' => 'Judo kyus', 'competition' => 'CFU — Kyus garçons', 'last_name' => 'HDIDI', 'first_name' => 'Safia', 'result' => 'Or — -81 kg orange/verte', 'place' => 1],
    ['season' => '2023/2024', 'sport' => 'Judo kyus', 'competition' => 'CFU — Kyus garçons', 'last_name' => 'HDIDI', 'first_name' => 'Safia', 'result' => 'Argent — -90 kg orange/verte', 'place' => 2],
    ['season' => '2023/2024', 'sport' => 'Judo kyus', 'competition' => 'CFU — Kyus garçons', 'last_name' => 'HDIDI', 'first_name' => 'Safia', 'result' => 'Bronze — -100 kg bleu/marron', 'place' => 3],

    ['season' => '2023/2024', 'sport' => 'Athlétisme indoor', 'competition' => 'CFU', 'last_name' => 'AUQUIER', 'first_name' => 'Carla', 'result' => 'Bronze — 400 m', 'place' => 3],
    ['season' => '2023/2024', 'sport' => 'Athlétisme indoor', 'competition' => 'CFU', 'last_name' => 'TISSANDIER', 'first_name' => 'Cassandre', 'result' => 'Bronze — saut en longueur', 'place' => 3],

    ['season' => '2023/2024', 'sport' => 'Taekwondo', 'competition' => 'CFU', 'last_name' => 'PAYE', 'first_name' => 'Mame Fatou', 'result' => 'Bronze — +73 kg', 'place' => 3],
    ['season' => '2023/2024', 'sport' => 'Taekwondo', 'competition' => 'CFU', 'last_name' => 'PAYE', 'first_name' => 'Mame Fatou', 'result' => 'Bronze — -53 kg', 'place' => 3],
    ['season' => '2023/2024', 'sport' => 'Taekwondo', 'competition' => 'CFU', 'last_name' => 'PAYE', 'first_name' => 'Mame Fatou', 'result' => 'Bronze — -57 kg', 'place' => 3],
    ['season' => '2023/2024', 'sport' => 'Taekwondo', 'competition' => 'CFU', 'last_name' => 'PAYE', 'first_name' => 'Mame Fatou', 'result' => 'Bronze — -57 kg', 'place' => 3],
    ['season' => '2023/2024', 'sport' => 'Taekwondo', 'competition' => 'CFU', 'last_name' => 'PAYE', 'first_name' => 'Mame Fatou', 'result' => 'Argent — -74 kg', 'place' => 2],
    ['season' => '2023/2024', 'sport' => 'Taekwondo', 'competition' => 'CFU', 'last_name' => 'PAYE', 'first_name' => 'Mame Fatou', 'result' => 'Bronze — -68 kg', 'place' => 3],

    ['season' => '2023/2024', 'sport' => 'Boxe française', 'competition' => 'CFU', 'last_name' => 'BERLIN', 'first_name' => 'Espenelle', 'result' => 'Or — assaut individuel +70 kg', 'place' => 1],
    ['season' => '2023/2024', 'sport' => 'Boxe française', 'competition' => 'CFU', 'last_name' => 'SAMMUT', 'first_name' => 'Serena', 'result' => 'Bronze — assaut individuel -50 kg', 'place' => 3],
    ['season' => '2023/2024', 'sport' => 'Boxe française', 'competition' => 'CFU — Par équipe', 'team' => true, 'result' => 'Or', 'place' => 1],

    ['season' => '2023/2024', 'sport' => 'Karaté', 'competition' => 'CFU', 'last_name' => 'MEHENNI', 'first_name' => 'Saïd', 'result' => 'Or — -60 kg', 'place' => 1],
    ['season' => '2023/2024', 'sport' => 'Karaté', 'competition' => 'CFU', 'last_name' => 'TELLIEZ-MORENI', 'first_name' => 'Manolo', 'result' => 'Bronze — Kata', 'place' => 3],
    ['season' => '2023/2024', 'sport' => 'Trail', 'competition' => 'CFU — Par équipe scratch', 'team' => true, 'result' => 'Bronze', 'place' => 3],

    ['season' => '2023/2024', 'sport' => 'Judo 1re division', 'competition' => 'CFU 1re division', 'last_name' => 'MACHUT', 'first_name' => 'Léa', 'result' => 'Or — +78 kg', 'place' => 1],
    ['season' => '2023/2024', 'sport' => 'Judo 1re division', 'competition' => 'CFU 1re division', 'last_name' => 'LANG', 'first_name' => 'Bintibe', 'result' => 'Argent — +78 kg', 'place' => 2],
    ['season' => '2023/2024', 'sport' => 'Athlétisme estival', 'competition' => 'CFU', 'last_name' => 'LACAZETTE', 'first_name' => 'Laura', 'result' => 'Argent — marteau 49,87 m', 'place' => 2],

    ['season' => '2023/2024', 'sport' => 'Basket 5x5 masculin', 'competition' => 'CFU N2', 'team' => true, 'result' => 'Bronze', 'place' => 3],
    ['season' => '2023/2024', 'sport' => 'Volley-ball féminin', 'competition' => 'CFU N2', 'team' => true, 'result' => 'Or', 'place' => 1],
    ['season' => '2023/2024', 'sport' => 'Volley-ball masculin', 'competition' => 'CFU N2', 'team' => true, 'result' => 'Or', 'place' => 1],
    ['season' => '2023/2024', 'sport' => 'Football', 'competition' => 'CFDU', 'team' => true, 'result' => 'Champion de France', 'place' => 1],
    ['season' => '2023/2024', 'sport' => 'Tennis', 'competition' => 'CFU N2', 'team' => true, 'result' => 'Or', 'place' => 1],
    ['season' => '2023/2024', 'sport' => 'Wing foil', 'competition' => 'CFU', 'last_name' => 'HERMITANT', 'first_name' => 'Marin', 'result' => 'Or', 'place' => 1],

    ['season' => '2023/2024', 'sport' => 'Judo 1re division', 'competition' => 'EUSA — Hongrie', 'last_name' => 'MACHUT', 'first_name' => 'Léa', 'result' => 'Argent — +78 kg', 'place' => 2],
    ['season' => '2023/2024', 'sport' => 'Kick boxing', 'competition' => 'EUSA — Hongrie', 'last_name' => 'DETTORI', 'first_name' => 'Alessandro', 'result' => 'Bronze — K-1 -67 kg', 'place' => 3],
    ['season' => '2023/2024', 'sport' => 'Voile', 'competition' => 'Championnat du monde — Italie', 'team' => true, 'result' => 'Or', 'place' => 1],
];

function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

function current_page(): string
{
    return clean_public_route(basename($_SERVER['SCRIPT_NAME'] ?? 'index.php'));
}

function clean_public_route(string $path): string
{
    $path = (string) preg_replace('/\.php$/i', '', $path);
    return $path === 'index' ? '' : $path;
}

function nav_path(string $url): string
{
    $path = parse_url($url, PHP_URL_PATH) ?: $url;
    return clean_public_route(basename($path));
}

function is_url_active(string $url): bool
{
    $path = nav_path($url);
    $current = current_page();

    if ($current === $path) {
        return true;
    }

    if ($path === 'sections' && $current === 'section') {
        return true;
    }

    return false;
}

function is_nav_active(array $item): bool
{
    if (isset($item['url']) && is_url_active((string) $item['url'])) {
        return true;
    }

    foreach (($item['children'] ?? []) as $child) {
        if (isset($child['url']) && is_url_active((string) $child['url'])) {
            return true;
        }
    }

    return false;
}

function active_class(string $file): string
{
    return is_url_active($file) ? ' class="active" aria-current="page"' : '';
}

function section_url(array $section): string
{
    return 'section.php?slug=' . urlencode((string) $section['slug']);
}

function find_section_by_slug(array $sections, string $slug): ?array
{
    foreach ($sections as $section) {
        if (($section['slug'] ?? '') === $slug) {
            return $section;
        }
    }

    return null;
}

// Les éventuelles modifications réalisées depuis /admin sont chargées après
// les contenus d'origine afin de rester visibles sur toutes les pages du site.
require_once __DIR__ . '/content-store.php';
content_store_apply($site, $sections, $photoGallery, $palmares, $associationStats);

foreach ($nav as &$navItem) {
    foreach (($navItem['children'] ?? []) as &$navChild) {
        if (str_starts_with((string) ($navChild['label'] ?? ''), 'Coachs ')) {
            $navChild['label'] = 'Coachs ' . $site['season'];
        }
    }
    unset($navChild);
}
unset($navItem);

// Collections complémentaires gérées depuis l’administration.
$sports = content_store_collection('sports', $sports);
$adhesionSteps = content_store_collection('adhesion_steps', $adhesionSteps);
$competitionDocs = content_store_collection('competition_documents', $competitionDocs);
$associationDocuments = content_store_collection('association_documents', $associationDocuments);
$commissions = content_store_collection('commissions', $commissions);
$associationBoard = content_store_collection('association_board', $associationBoard);
$competitionCalendar = content_store_collection('competition_calendar', $competitionCalendar);
$newsPosts = content_store_collection('news', []);
$coachStats = content_store_collection('coach_stats', $coachStats);
$coachInfo = content_store_collection('coach_info', $coachInfo);
$coaches = content_store_collection('coaches', $coaches);
$partners = content_store_collection('partners', $partners);
