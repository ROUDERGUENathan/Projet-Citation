# Dictionnaire de citations

Site PHP/MySQL (aucun JavaScript : uniquement PHP cote serveur + CSS) ou chaque
visiteur peut deposer une citation (visible par tous) et un administrateur gere
l'ensemble du contenu (CRUD).

Le site est en ligne ici : https://projet-citationnr.gt.tc

## Fonctionnalites

- Page d'accueil : une citation + un fond degrade s'affichent, tires au hasard
  dans la base a chaque chargement. La page se recharge automatiquement toutes
  les 15 secondes (`<meta http-equiv="refresh" content="15">`) pour simuler un
  carrousel, sans une seule ligne de JavaScript.
- Formulaire de connexion administrateur integre directement sur la page d'accueil.
- `ajouter.php` : formulaire public pour deposer une citation (visiteurs).
- `login.php` / `logout.php` : connexion / deconnexion administrateur.
- `admin.php` : liste des citations avec liens Modifier / Supprimer (reserve a l'admin).
- `admin_modifier.php` : modifier une citation (admin).
- `admin_supprimer.php` : page de confirmation puis suppression (admin) - pas de
  `confirm()` JavaScript, tout se fait via une page de confirmation cote serveur.
- `setup_admin.php` : cree le premier compte administrateur (a supprimer du
  serveur une fois utilise - deja fait sur le site en ligne).

## Technologies

- **PHP** en procedural avec l'extension **mysqli** (pas de PDO, pas de
  framework) : connexion, requetes et fermeture explicites, comme vu en cours.
- **MySQL** hebergement **InfinityFree**.
- **CSS pur** pour la mise en forme et le carrousel (aucun JavaScript, conforme
  a la contrainte du projet).
- Mots de passe stockes avec `password_hash()` / verifies avec `password_verify()`.
- Valeurs utilisateur echappees avec `mysqli_real_escape_string()` (protection
  contre les injections SQL) et affichees avec `htmlspecialchars()` (protection
  contre les failles XSS).

## Structure

```
config/db.php           Connexion mysqli a la base de donnees
sql/schema.sql           Tables citations + administrateurs
index.php                 Accueil + carrousel (une citation, rechargee toutes les 15s)
ajouter.php                Depot d'une citation (public)
login.php / logout.php    Connexion / deconnexion admin
admin.php                  Liste + gestion des citations (admin)
admin_modifier.php         Modifier une citation (admin)
admin_supprimer.php        Confirmer puis supprimer une citation (admin)
setup_admin.php            Creation du 1er compte admin (a supprimer apres usage)
css/style.css               Mise en forme + carrousel en CSS pur
```

## Deploiement (InfinityFree)

1. Creer un compte et un hebergement gratuit sur https://infinityfree.net.
2. Creer une base MySQL dans "MySQL Databases" et importer `sql/schema.sql`
   (uniquement les `CREATE TABLE`/`INSERT`, sans les lignes `CREATE DATABASE`)
   via phpMyAdmin.
3. Envoyer tous les fichiers du projet dans `htdocs/` via le File Manager
   InfinityFree.
4. Completer `config/db.php` avec l'hote/utilisateur/nom de base fournis par
   InfinityFree, et le mot de passe du compte (directement en ligne, jamais
   dans ce depot).
5. Ouvrir `setup_admin.php` pour creer le compte administrateur, puis le
   supprimer du serveur.
