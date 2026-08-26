<?php
/**
 * Contenu repris du fichier original « boussole-sport-amu_1.html ».
 * Chaque catégorie s'ouvre dans la même page et affiche tous ses choix.
 */

$sportCompassActors = [
    'SUAPS' => [
        'name' => 'SUAPS',
        'description' => 'Le sport pour tous : loisir, bonus, santé, animations.',
    ],
    'AS_AMU' => [
        'name' => 'AS amU',
        'description' => 'La compétition universitaire : sections, entraînements, matchs.',
    ],
    'FFSU' => [
        'name' => 'FFSU — Ligue Sud',
        'description' => "L'instance des championnats universitaires (licence offerte par AMU).",
    ],
    'STAPS' => [
        'name' => 'STAPS / FSS',
        'description' => 'Les formations diplômantes du sport (licence, master, licence pro).',
    ],
];

$sportCompass = [
    [
        'id' => 'loisir',
        'emoji' => '🏃',
        'title' => 'Sport loisir',
        'subtitle' => 'Pratiquer sans pression, à mon rythme',
        'theme' => 'green',
        'options' => [
            [
                'actor' => 'SUAPS',
                'label' => 'Je veux pratiquer une ou deux activités par semaine, sans compétition',
                'title' => 'SUAPS — Pack Sport',
                'description' => "Jusqu'à 2 activités par semaine, sur n'importe quel campus, encadrées par des enseignants.",
                'info' => 'Gratuit pour les étudiant·es',
                'url' => 'https://sport-suaps.univ-amu.fr/sport/list',
            ],
            [
                'actor' => 'SUAPS',
                'label' => 'Je veux voir toutes les activités possibles',
                'title' => 'SUAPS — Catalogue des activités',
                'description' => 'Plus de 80 activités sportives à explorer.',
                'url' => 'https://sport-suaps.univ-amu.fr/discipline/listSport',
            ],
            [
                'actor' => 'SUAPS',
                'label' => 'Je veux pratiquer plus, ou une activité spécifique (à tarif préférentiel)',
                'title' => 'SUAPS — Pack Sport +',
                'description' => 'Des activités supplémentaires à tarifs réduits.',
                'info' => "Nécessite d'avoir d'abord un Pack Sport",
                'url' => 'https://sport-suaps.univ-amu.fr/pack/info',
            ],
        ],
    ],
    [
        'id' => 'bonus',
        'emoji' => '🎁',
        'title' => 'Les bonus étudiants',
        'subtitle' => 'Valoriser le sport dans mes études',
        'theme' => 'yellow',
        'options' => [
            [
                'actor' => 'SUAPS',
                'label' => 'Je veux gagner des points bonus sur ma moyenne grâce au sport',
                'title' => 'SUAPS — Bonus sport',
                'description' => "Jusqu'à +0,5 pt sur la moyenne semestrielle (plafond tous bonus confondus). Réservé L1 → M1.",
                'info' => "Double inscription obligatoire : au SUAPS, puis inscription pédagogique via l'ENT",
                'url' => 'https://sport-suaps.univ-amu.fr/bonus',
            ],
            [
                'actor' => 'SUAPS',
                'label' => "Je veux m'engager (arbitrage, tutorat) et le valoriser",
                'title' => 'AMU — Bonus engagement étudiant',
                'description' => "L'arbitrage (non rémunéré) au sein du SUAPS/AS amU et le tutorat peuvent être bonifiés.",
                'info' => 'Panorama de tous les bonus AMU',
                'url' => 'https://www.univ-amu.fr/fr/public/bonus-1',
            ],
            [
                'actor' => 'SUAPS',
                'label' => 'Je veux des réductions / bons plans sport',
                'title' => 'SUAPS — Avantages partenaires',
                'description' => 'Avantages étudiants : réductions matériel/textile, plongée, secourisme…',
                'url' => 'https://sport-suaps.univ-amu.fr/',
            ],
        ],
    ],
    [
        'id' => 'formation',
        'emoji' => '🎓',
        'title' => 'La formation dans le sport',
        'subtitle' => 'Faire des études dans le domaine sportif',
        'theme' => 'purple',
        'options' => [
            [
                'actor' => 'STAPS',
                'label' => 'Je veux faire des études dans le sport (licence)',
                'title' => 'FSS — Licence STAPS',
                'description' => 'Portail L1 commun, puis 4 mentions : Activité Physique Adaptée & Santé, Éducation & Motricité, Ergonomie du Sport & Performance Motrice, Management du Sport.',
                'url' => 'https://fss.univ-amu.fr/fr/formations/licence-staps',
            ],
            [
                'actor' => 'STAPS',
                'label' => 'Je veux voir les masters et débouchés',
                'title' => 'FSS — Diplômes nationaux',
                'description' => 'Masters (MEEF/enseignement, APAS, Management, EOPS, Ingénierie…) et licences pro.',
                'url' => 'https://fss.univ-amu.fr/fr/formations/diplomes-nationaux',
            ],
            [
                'actor' => 'STAPS',
                'label' => 'Je veux découvrir la faculté',
                'title' => 'Faculté des Sciences du Sport',
                'description' => 'Trois sites : Gap, Luminy-Marseille et Aubagne.',
                'url' => 'https://fss.univ-amu.fr/',
            ],
        ],
    ],
    [
        'id' => 'festif',
        'emoji' => '🎉',
        'title' => 'Sport festif',
        'subtitle' => "Vivre l'ambiance et les événements",
        'theme' => 'salmon',
        'options' => [
            [
                'actor' => 'SUAPS',
                'label' => 'Je veux participer à des événements/animations sportives sur mon campus',
                'title' => 'SUAPS — Animations & événements',
                'description' => 'Plus de 100 événements par an, souvent gratuits : challenges, animations de campus.',
                'url' => 'https://sport-suaps.univ-amu.fr/sport/events',
            ],
            [
                'actor' => 'AS_AMU',
                'label' => "Je veux l'ambiance d'une équipe, la vie de section",
                'title' => 'AS amU — Vie de section',
                'description' => '12 sections, une communauté et des temps forts autour de la compétition.',
                'url' => 'https://www.as-amu.fr/',
            ],
        ],
    ],
    [
        'id' => 'competition',
        'emoji' => '🏆',
        'title' => 'Sport compétition',
        'subtitle' => 'Jouer des matchs et des championnats',
        'theme' => 'orange',
        'options' => [
            [
                'actor' => 'AS_AMU',
                'label' => 'Je veux faire de la compétition universitaire (matchs, championnats)',
                'title' => 'AS amU + licence FFSU',
                'description' => 'Adhésion à une section, licence FFSU (Ligue Sud) offerte par AMU. Des compétitions locales à européennes.',
                'info' => 'Adhésion 10 € à une section',
                'url' => 'https://www.as-amu.fr/',
            ],
            [
                'actor' => 'AS_AMU',
                'label' => 'Je suis sportif·ve de haut niveau et je veux concilier sport et études',
                'title' => 'AMU — Sportifs de Haut Niveau (SHN-amU)',
                'description' => 'Statut avec aménagements pédagogiques, suivi individualisé et double projet.',
                'url' => 'https://www.univ-amu.fr/fr/public/sportifs-de-haut-niveau',
            ],
        ],
    ],
    [
        'id' => 'sante',
        'emoji' => '💚',
        'title' => 'Sport santé',
        'subtitle' => 'Reprendre en douceur, prendre soin de moi',
        'theme' => 'blue',
        'options' => [
            [
                'actor' => 'SUAPS',
                'label' => 'Je veux reprendre le sport en douceur (peu/pas sportif·ve, sédentaire)',
                'title' => "SUAPS — Mouv'amU",
                'description' => 'Dispositif anti-sédentarité : créneaux santé & bien-être, reprise progressive et bienveillante en petit groupe.',
                'url' => 'https://www.univ-amu.fr/fr/public/dispositif-sport-et-handicap',
            ],
            [
                'actor' => 'SUAPS',
                'label' => "J'ai un handicap ou une situation de santé qui nécessite un accompagnement",
                'title' => "Mouv'amU — Activité Physique Adaptée (APA)",
                'description' => 'Créneaux encadrés par des professionnels APA, après un bilan personnalisé. Créneaux inclusifs.',
                'url' => 'https://www.univ-amu.fr/fr/public/dispositif-sport-et-handicap',
            ],
            [
                'actor' => 'SUAPS',
                'label' => "Je veux gérer mon stress / mon bien-être (période d'examens)",
                'title' => 'SUAPS — Bien-être',
                'description' => 'Ateliers de gestion du stress : yoga, relaxation…',
                'url' => 'https://sport-suaps.univ-amu.fr/',
            ],
        ],
    ],
];

require_once __DIR__ . '/content-store.php';
$sportCompassActors = content_store_collection('compass_actors', $sportCompassActors);
$sportCompass = content_store_collection('compass_categories', $sportCompass);
