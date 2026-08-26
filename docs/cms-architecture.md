# Architecture du CMS AS amU

## Ce qui fonctionne dès maintenant

Le site reste un site PHP simple, compatible XAMPP et hébergement mutualisé. Le contenu est conservé dans des fichiers JSON protégés de l’accès public, afin de ne pas interrompre le travail déjà réalisé.

L’administration apporte désormais :

- un tableau de bord avec raccourcis et historique des modifications ;
- des comptes individuels avec quatre niveaux de droits ;
- une médiathèque d’images et de PDF avec dossiers, légendes, texte alternatif et cadrage d’aperçu ;
- un éditeur visuel avec aperçu en direct pour les textes éditoriaux ;
- une API interne d’envoi de média, prête à être utilisée par de futurs modules ;
- une écriture atomique des contenus pour éviter toute sauvegarde partielle.

Les fichiers JSON restent une solution adaptée au fonctionnement local. Lors de la mise en ligne par l’agence, la base MySQL peut être mise en place à partir du schéma `database/schema.mysql.sql`, sans changer les pages publiques : il suffit de remplacer progressivement les fonctions de lecture/écriture du dépôt de contenu.

## Architecture recommandée pour la version hébergée

```text
Navigateur
    │ HTTPS
    ▼
Site public PHP  ─────────────► services de lecture de contenu
    │                                      │
    │                                      ▼
Administration PHP ── API interne ──► MySQL / MariaDB
    │                                      │
    ├─ Sessions sécurisées                  ├─ contenus et rôles
    ├─ CSRF + contrôle des droits            ├─ journal d’activité
    └─ Médiathèque                           └─ références vers les fichiers
             │
             ▼
        stockage public contrôlé
        (images WebP, PDF) ou stockage objet
```

À terme, l’agence peut conserver PHP sans framework ou introduire Laravel/Symfony. Le point important est de garder une couche de services unique entre l’administration et le site public : les pages ne doivent jamais exécuter directement des requêtes SQL ou manipuler les fichiers de contenu.

## Rôles proposés

| Rôle | Accès |
|---|---|
| Propriétaire | Tous les contenus, les réglages et la gestion des utilisateurs. |
| Administrateur·rice | Toutes les mises à jour du site et les synchronisations, sans gestion des accès. |
| Rédacteur·rice | Médias et contenus éditoriaux assignés. |
| Référent·e de section | Uniquement les fiches de section qui lui sont attribuées. |
| Consultation | Tableau de bord uniquement. |

Chaque membre doit disposer de son propre compte : aucun mot de passe partagé. La désactivation d’un compte conserve son historique sans supprimer les contenus publiés.

## Règles de sécurité à conserver

1. Forcer HTTPS sur l’hébergement et activer `Secure`, `HttpOnly` et `SameSite` pour les cookies de session.
2. Utiliser des mots de passe uniques d’au moins 12 caractères, stockés avec Argon2id lorsque disponible.
3. Garder les jetons CSRF sur tous les formulaires et les API d’administration.
4. Vérifier le type réel des fichiers envoyés ; interdire SVG et fichiers exécutables ; ne jamais utiliser le nom de fichier fourni par le navigateur comme nom de stockage.
5. Stocker les secrets Google Sheets, clés API et sauvegardes en dehors de la racine publique ou dans les variables d’environnement de l’hébergement.
6. Mettre en place une limitation de tentatives de connexion et une sauvegarde quotidienne de la base/du dossier `storage` lors du passage en production.
7. Conserver le journal d’activité et prévoir une politique de suppression des données personnelles.

## Plan de développement vers MySQL

1. Créer une base MySQL en UTF-8 (`utf8mb4`) et importer `database/schema.mysql.sql`.
2. Ajouter les variables de connexion dans un fichier non public, par exemple `config/local.php`, jamais dans Git ou dans `storage` public.
3. Créer un service `ContentRepository` PDO avec requêtes préparées. Migrer d’abord les utilisateurs, puis la médiathèque, ensuite les sections, calendrier, photothèque et palmarès.
4. Mettre en place les sauvegardes automatiques et un environnement de préproduction avant chaque changement important.
5. Faire évoluer l’API interne vers des réponses JSON versionnées (`/admin/api/v1/...`) si une application mobile ou un autre outil doit lire les données.
6. Connecter Google Sheets via un compte de service ou Apps Script protégé : le tableur devient une source contrôlée pour le palmarès, et la base conserve la dernière synchronisation ainsi que les éventuelles erreurs.

## Utilisation quotidienne

1. Se connecter avec un compte individuel.
2. Mettre d’abord l’image ou le PDF dans **Médiathèque** ; le classer dans un dossier et remplir le texte alternatif.
3. Ajouter la photo à la **Photothèque**, ou l’associer à l’événement concerné.
4. Modifier les textes dans l’éditeur visuel, vérifier l’aperçu puis enregistrer.
5. Ouvrir **Voir le site** dans un nouvel onglet pour contrôler le résultat publié.

