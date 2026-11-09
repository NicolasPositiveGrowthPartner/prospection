# Prospection PGP — Application de recherche SIRENE (INSEE) + qualification double grille

Application statique + relais PHP pour prospecter des entreprises via l'API Sirene de l'INSEE,
puis les qualifier selon deux grilles de critères (PGP « impact positif » et Standard).

## Fichiers à déployer sur le serveur (même dossier)

| Fichier | Rôle |
|---|---|
| `prospection.html` | Étape 1 : recherche multicritères via l'API INSEE + export CSV |
| `api-relais.php` | Relais PHP qui appelle l'API INSEE (la clé ne transite pas par le navigateur) |
| `analyse.html` | Étape 2 : visualisation double grille (PGP / Standard) à partir du CSV exporté |

## Configuration obligatoire (clé API INSEE)

1. Créer un compte et une clé sur https://portail-api.insee.fr
   (Mes applications → créer une application → souscrire à « API Sirene » plan Public → Clés et jetons d'accès → Générer/Montrer les clés → copier la **clé d'intégration**).
2. **Révoquer toute ancienne clé publiée** (ex. committée dans un dépôt public).
3. Sur le serveur, définir la variable d'environnement `INSEE_API_KEY` :
   - via le `.htaccess` du dossier : `SetEnv INSEE_API_KEY ta-cle-ici`
   - ou via « Variables d'environnement » du panneau d'hébergement (cPanel o2switch).
4. Ne JAMAIS écrire la clé en dur dans un fichier versionné (ni la partager en clair).

## Utilisation

- Ouvrir `prospection.html` → onglet « 1. Recherche » → définir les critères → « Rechercher » → « Export CSV ».
- Ouvrir `analyse.html` → glisser le CSV exporté → scores PGP et Standard automatiques
  (proximité AURA, budget présumé) + saisie manuelle des autres critères par entreprise.
- Les saisies de qualification sont stockées dans le navigateur (localStorage).

## Limites de l'API Sirene

30 requêtes/minute (plan Public). Le relais renvoie une erreur explicite si la clé manque (500)
ou est invalide (401).
