# Refonte AS amU — site V2 et CMS

Site PHP/HTML/CSS sans framework, conçu pour être testé avec XAMPP puis publié sur un hébergement PHP classique.

## Application mobile Flutter

Une application iOS/Android complète et navigable est disponible dans [`mobile/`](mobile/README.md). Elle comprend les parcours étudiant, équipe, compétition, événement, carte QR, documents, gamification et administration, ainsi qu'une architecture cible Firebase/PostgreSQL.

## Fonctionnalités du site

- Barre de navigation refaite : plus compacte, plus claire, menus déroulants propres.
- Accueil enrichi avec le mot du/de la président·e.
- Adhésion via HelloAsso, sans portail AS amU.
- Onglet Calendrier des compétitions.
- Page Coachs 2025/2026.
- Une page dédiée par section avec adresse, bureau, permanences, nombre d’adhérent·es, nombre de licencié·es, blocs éditoriaux “Compétitions” et “Événementiel”, événements propres à la section.
- Bloc “Nos partenaires précieux” en bas de page.

## Structure

```txt
asamu-refonte/
├── index.php
├── adhesion.php
├── association.php
├── calendrier.php
├── coachs.php
├── commissions.php
├── competitions.php
├── contact.php
├── documents.php
├── section.php
├── sections.php
├── statuts.php
├── includes/
│   ├── config.php
│   ├── header.php
│   └── footer.php
└── assets/
    ├── css/style.css
    └── js/main.js
```

## Mettre le contenu à jour, sans code

L’espace d’administration est disponible ici :

```txt
/admin/login.php
```

Il permet de modifier ou d’ajouter :

- les fiches sections : horaires, contacts, référent·es, chiffres, textes et événements ;
- le calendrier des compétitions et ses affiches ;
- les coachs et leurs coordonnées ;
- les photos, titres et légendes de la photothèque ;
- les podiums du palmarès ;
- la synchronisation automatique du palmarès vers Google Sheets ;
- les coordonnées de l’AS amU, les chiffres de l’accueil et l’organigramme du bureau ;
- les disciplines, étapes d’adhésion, documents, commissions, partenaires et la boussole du sport.

L’administration inclut également :

- un tableau de bord avec les raccourcis et l’historique des mises à jour ;
- des comptes individuels avec rôles (propriétaire, administration, rédaction, référent·e de section et consultation) ;
- une médiathèque pour classer les images et PDF avant de les publier ;
- un éditeur visuel avec aperçu en direct pour les textes éditoriaux ;
- un module Actualités, accessible aux rédacteur·rices ;
- une API interne d’envoi de médias : `admin/api/media-upload.php`.

Les modifications sont enregistrées dans `storage/content.json`. Ce fichier est protégé de l’accès public et ne doit pas être supprimé. Les comptes d’administration, le journal des modifications et la médiathèque sont eux aussi stockés dans `storage/` pendant la phase locale.

Le compte historique utilise l’identifiant `admin`. Dès la première connexion, crée des comptes individuels depuis **Utilisateurs et rôles** et change le mot de passe initial.

La cible de migration MySQL et le plan de déploiement pour l’agence sont documentés dans [docs/cms-architecture.md](docs/cms-architecture.md). Le schéma est disponible dans [database/schema.mysql.sql](database/schema.mysql.sql).

## Tester avec XAMPP

Place le dossier dans `htdocs`, puis démarre Apache.

Sur Mac :

```txt
/Applications/XAMPP/htdocs/asamu-refonte
```

Sur Windows :

```txt
C:\xampp\htdocs\asamu-refonte
```

Puis ouvre :

```txt
http://localhost/asamu-refonte
```

## Mettre en ligne

Sur l’hébergement, envoie le contenu du dossier dans `public_html` ou `www`.

Le fichier `index.php` doit être directement à la racine publique :

```txt
public_html/index.php
public_html/assets/
public_html/includes/
public_html/admin/
public_html/storage/
```

Le dossier `storage/` doit pouvoir être écrit par PHP (en général permission 755 ou 775 selon l’hébergeur). Après la première connexion à `/admin/login.php`, change immédiatement le mot de passe dans « Changer le mot de passe ».

## Google Sheets (facultatif)

Dans l’administration, ouvre « Google Sheets » puis suis les étapes indiquées. Le script à copier une seule fois dans le tableur est fourni dans :

```txt
google-sheets/Code.gs
```

Une fois l’URL Apps Script `/exec` et le code secret renseignés, chaque enregistrement du palmarès actualise automatiquement l’onglet `Palmarès` du Google Sheet.
