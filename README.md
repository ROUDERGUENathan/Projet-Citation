# Dictionnaire de citations

Site PHP/MySQL (aucun JavaScript : uniquement PHP cote serveur + CSS) ou chaque
visiteur peut deposer une citation (visible par tous) et un administrateur gere
l'ensemble du contenu (CRUD).

## Fonctionnalites

- Page d'accueil avec carrousel **en CSS pur** : citation + fond change automatiquement
  toutes les 15 secondes (animation CSS `@keyframes`, sans JavaScript).
- Formulaire de connexion administrateur integre directement sur la page d'accueil.
- `ajouter.php` : formulaire public pour deposer une citation.
- `login.php` : connexion administrateur.
- `admin.php` : liste des citations avec liens Modifier / Supprimer (reserve a l'admin).
- `admin_modifier.php` : modifier une citation (admin).
- `admin_supprimer.php` : page de confirmation puis suppression (admin) - aucune boite
  de dialogue JavaScript, tout se fait via une page de confirmation cote serveur.

## Installation (XAMPP / WAMP / MAMP)

1. Placez ce dossier dans votre serveur local (ex. `C:\xampp\htdocs\dictionnaire-citations`).
2. Demarrez Apache et MySQL.
3. Importez `sql/schema.sql` (via phpMyAdmin, ou `mysql -u root -p < sql/schema.sql`).
   Cela cree la base `dictionnaire_citations` et la table `citations` (avec 3 citations d'exemple).
4. Verifiez les identifiants dans `config/db.php` (par defaut : `root` / mot de passe vide).
5. Ouvrez `http://localhost/dictionnaire-citations/setup_admin.php` pour creer le
   premier compte administrateur (identifiant + mot de passe).
6. **Supprimez `setup_admin.php` une fois le compte cree** (il ne sert qu'a l'initialisation).
7. Ouvrez `http://localhost/dictionnaire-citations/index.php`.

## Structure

```
config/db.php          Connexion PDO a MySQL
includes/auth.php       Gestion de session et controle d'acces admin
sql/schema.sql          Tables citations + administrateurs
index.php                Accueil + carrousel
ajouter.php              Depot d'une citation (public)
login.php / logout.php  Connexion / deconnexion admin
admin.php                Liste + gestion des citations (admin)
admin_modifier.php       Modifier une citation (admin)
admin_supprimer.php      Supprimer une citation (admin)
setup_admin.php          Creation du 1er compte admin (a supprimer apres usage)
css/style.css            Mise en forme + carrousel en CSS pur (@keyframes)
```

## Securite

- Requetes SQL preparees (PDO) contre les injections SQL.
- Mots de passe hashes avec `password_hash` / verifies avec `password_verify`.
- Sortie echappee avec `htmlspecialchars` contre les failles XSS.
- Pages admin protegees par `requireAdmin()` (redirection vers `login.php` si non connecte).

## Pistes d'amelioration (hors perimetre initial)

- Ajout d'un jeton CSRF sur les formulaires.
- Upload d'image personnalisee par citation (actuellement, les fonds sont des degrades CSS).
- Pagination de la liste admin si le nombre de citations devient important.
