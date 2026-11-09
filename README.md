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

- Ouvrir `prospection.html` → onglet « **1. Recherche** » → définir les critères
  (dénomination, NAF, **régions** et départements, codes postaux, commune, dates, effectifs,
  catégorie juridique, ESS, actifs/sièges) → « Rechercher » → « Export CSV ».
- Onglet « **2. Qualification** » : cliquer « Qualifier ces résultats » pour charger la recherche
  courante, choisir la grille (PGP impact positif / Standard), puis cliquer sur une entreprise pour
  renseigner CA, résultat net, export, dirigeant, levée, croissance, impact, exclusions.
  Liens rapides par entreprise : Pappers, Annuaire-entreprises (dirigeants et procédures, gratuit),
  recherches Google « export » / « levée de fonds », LinkedIn.
- `analyse.html` sert uniquement à **réanalyser un CSV hors ligne** (glisser-déposer) :
  scores automatiques des deux grilles + mêmes saisies manuelles.
- Les saisies de qualification sont stockées dans le navigateur (localStorage).

> Les données SIRENE couvrent identité, adresse, NAF, effectifs, forme juridique.
> Le CA, le résultat net et les levées de fonds ne figurent PAS dans SIRENE :
> ils se lisent sur Pappers / presse spécialisée (liens fournis par entreprise).

## Limites de l'API Sirene

30 requêtes/minute (plan Public). Le relais renvoie une erreur explicite si la clé manque (500)
ou est invalide (401).
