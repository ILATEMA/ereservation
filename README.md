# e-réservation — Version MVC finale

## Installation

1. Place le dossier entier sous `C:\wamp64\www\ereservation\` (ou équivalent)
2. Importe `ereservation_full.sql`
3. Ouvre **`config/env.php`** — LE seul fichier à modifier pour ton hébergement (DB, SMTP, FedaPay)
4. Configure Apache pour que le **DocumentRoot pointe vers `public/`** (recommandé), ou utilise le dossier tel quel avec le `.htaccess` déjà inclus dans `public/`

### Option simple (sans toucher à la config Apache)

Accède directement à `http://localhost/ereservation/public/` — toutes les routes fonctionnent avec ce préfixe automatiquement détecté.

### Option propre (Virtual Host)

Configure un Virtual Host avec `DocumentRoot` pointant vers `.../ereservation/public`, donne-lui un nom (ex: `e-reservation.local`), et les URLs seront propres : `http://e-reservation.local/resultats`, etc.

## Structure

```
ereservation/
├── config/env.php        ← SEUL fichier à modifier (DB, SMTP, FedaPay, chemins)
├── routes.php              ← TOUTES les routes du site, un seul endroit à regarder
├── public/
│   ├── index.php            ← point d'entrée unique
│   ├── .htaccess
│   └── uploads/              ← documents compagnies (protégé, pas d'exécution PHP)
├── app/
│   ├── Core/                 ← Router, Database, Session, Mailer, FedaPay...
│   ├── Models/                ← toute la logique métier et les requêtes SQL
│   ├── Controllers/            ← reçoit la requête, appelle un Model, choisit une Vue
│   └── Views/                  ← templates HTML (aucune logique métier)
└── ereservation_full.sql
```

## Routes principales (voir routes.php pour la liste complète)

| URL | Rôle |
|---|---|
| `/` | Accueil |
| `/resultats` | Recherche de voyages |
| `/siege?voyage=X` | Sélection de siège |
| `/paiement?voyage=X` | Paiement |
| `/billet?commande=X` | Billet final |
| `/rejoindre` | Inscription compagnie |
| `/compagnie/connexion` | Connexion compagnie |
| `/compagnie` | Tableau de bord compagnie |
| `/admin/connexion` | Connexion admin |
| `/admin` | Tableau de bord admin |
| `/admin/compagnies/{id}` | Détail + validation compagnie |

## Mode démo FedaPay

Tant que `config('fedapay.cle_secrete')` est vide dans `config/env.php`, les paiements sont automatiquement simulés (toujours réussis). Renseigne une vraie clé pour activer le vrai paiement — aucun code à changer.

## Ce qui a été testé réellement (vraie base MariaDB, vrais appels HTTP)

- Parcours voyageur complet (recherche → siège → paiement démo → billet)
- Concurrence réelle sur réservation multi-sièges (deux commandes simultanées avec sièges qui se chevauchent → une seule réussit intégralement)
- Connexion + tableau de bord admin
- Connexion + tableau de bord compagnie + création de voyage
- Détail compagnie (validation/rejet)

## Ce qui n'a pas encore été migré dans cette passe

Les actions "Passagers" avancées, l'export CSV, et certains détails cosmétiques mineurs des anciennes pages peuvent nécessiter une vérification manuelle — signale-moi tout comportement inattendu.
