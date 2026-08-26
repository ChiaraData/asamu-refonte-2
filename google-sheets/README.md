# Synchroniser le Google Sheet de résultats avec le site

Le **Google Sheet reste le document de travail**. Le site ne lit que les podiums de la feuille `NATIONAL` : sport, championnat, nom, prénom, catégorie et résultat. Les colonnes de budget, de transport, de contact et d’inscription sont ignorées. Les résultats reconnus sont les places `1er`, `2e`, `3e`, les médailles or/argent/bronze et les mentions champion·ne / vice-champion·ne.

## Configuration, une seule fois

1. Dans l’administration AS amU, ouvre **Google Sheets**, active la synchronisation puis génère un code secret.
2. Copie l’**adresse de synchronisation du site** affichée sur cette page.
3. Dans le Google Sheet existant, ouvre **Extensions → Apps Script** puis colle le contenu de `Code.gs`.
4. Dans **Paramètres du projet → Propriétés du script**, ajoute :
   - `SITE_SYNC_URL` : l’adresse copiée dans l’administration ;
   - `SHARED_SECRET` : le code secret généré dans l’administration ;
   - `DEFAULT_SEASON` : par exemple `2025/2026`.
5. Dans Apps Script, exécute une fois la fonction `installAutomaticSync` et accepte les autorisations Google.
6. Exécute ensuite `syncNow` une première fois pour vérifier que les podiums apparaissent sur la page Palmarès du site.

Après cela, chaque modification de la feuille `NATIONAL` actualise le palmarès du site automatiquement. Un menu **AS amU** est aussi ajouté au Google Sheet pour lancer une synchronisation manuelle si nécessaire.

> Pendant les essais sur XAMPP, Google ne peut pas joindre `localhost`. La synchronisation automatique fonctionnera lorsque le site aura une URL publique HTTPS (hébergement de l’agence ou tunnel public temporaire).
