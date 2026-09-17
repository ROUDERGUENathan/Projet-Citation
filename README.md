# Dictionnaire de citations

Site PHP/MySQL (aucun JavaScript : uniquement PHP cote serveur + CSS) ou chaque
visiteur peut creer un compte, deposer une citation (visible par tous), et un
administrateur gere l'ensemble du contenu et des utilisateurs.

Le site est en ligne ici : https://projet-citationnr.gt.tc

## Fonctionnalites

- Page d'accueil : une citation + un fond degrade s'affichent, tires au hasard
  dans la base a chaque chargement. La page se recharge automatiquement toutes
  les 15 secondes (`<meta http-equiv="refresh" content="15">`) pour simuler un
  carrousel, sans une seule ligne de JavaScript.
- Formulaire de connexion integre directement sur la page d'accueil.
- `ajouter.php` : formulaire public pour deposer une citation (visiteurs).
- `inscription.php` : creation d'un compte utilisateur.
- `login.php` / `logout.php` : connexion / deconnexion.
- `mot-de-passe-oublie.php` / `reinitialiser.php` : reinitialisation du mot de
  passe par email (lien a duree limitee envoye via l'API Brevo).
- `admin.php` : liste des citations avec liens Modifier / Supprimer (reserve a l'admin).
- `admin_modifier.php` : modifier une citation (admin).
- `admin_supprimer.php` : page de confirmation puis suppression (admin) - pas de
  `confirm()` JavaScript, tout se fait via une page de confirmation cote serveur.
- `admin_utilisateurs.php` : liste des comptes utilisateurs (admin).
- `admin_utilisateur_modifier.php` : modifier l'identifiant/email d'un utilisateur (admin).
- `admin_utilisateur_supprimer.php` : supprimer un compte utilisateur (admin).
- `admin_utilisateur_promouvoir.php` : donner les droits administrateur a un utilisateur (admin).

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
- Emails de reinitialisation envoyes via l'API REST de **Brevo** (cURL, HTTPS) -
  InfinityFree bloque le SMTP sortant classique et la fonction `mail()`.

## Structure

```
config/db.php                     Connexion mysqli a la base de donnees
config/email.php                   Envoi d'email via l'API Brevo
sql/schema.sql                      Tables citations, utilisateurs, reinitialisations
index.php                            Accueil + carrousel (rechargee toutes les 15s)
ajouter.php                          Depot d'une citation (public)
inscription.php                       Creation de compte
login.php / logout.php               Connexion / deconnexion
mot-de-passe-oublie.php               Demande de reinitialisation (envoi d'email)
reinitialiser.php                      Choix du nouveau mot de passe (lien recu par email)
admin.php                              Liste + gestion des citations (admin)
admin_modifier.php                     Modifier une citation (admin)
admin_supprimer.php                    Confirmer puis supprimer une citation (admin)
admin_utilisateurs.php                 Liste des utilisateurs (admin)
admin_utilisateur_modifier.php          Modifier un utilisateur (admin)
admin_utilisateur_supprimer.php         Confirmer puis supprimer un utilisateur (admin)
admin_utilisateur_promouvoir.php        Promouvoir un utilisateur en admin
css/style.css                           Mise en forme + carrousel en CSS pur
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
5. Creer un compte gratuit sur https://www.brevo.com, verifier une adresse
   d'expediteur, generer une cle API et la coller dans `config/email.php`
   (directement en ligne, jamais dans ce depot).
6. Creer un compte via `inscription.php`, puis se faire promouvoir administrateur
   directement en base de donnees (premier compte uniquement).
