<?php
require 'config/db.php';
session_start();

if (isset($_SESSION['utilisateur_id'])) {
    header('Location: index.php');
    exit;
}

$erreur = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $identifiant = trim($_POST['identifiant']);
    $motDePasseSaisi = $_POST['mot_de_passe'];

    $identifiantSecurise = mysqli_real_escape_string($connexion, $identifiant);

    $requete = "SELECT id, identifiant, mot_de_passe, role FROM utilisateurs WHERE identifiant = '$identifiantSecurise'";
    $resultat = mysqli_query($connexion, $requete);
    $utilisateur = mysqli_fetch_assoc($resultat);

    if ($utilisateur && password_verify($motDePasseSaisi, $utilisateur['mot_de_passe'])) {
        $_SESSION['utilisateur_id'] = $utilisateur['id'];
        $_SESSION['utilisateur_identifiant'] = $utilisateur['identifiant'];
        $_SESSION['utilisateur_role'] = $utilisateur['role'];
        header('Location: index.php');
        exit;
    }

    $erreur = 'Identifiant ou mot de passe incorrect.';
}
?>
<!doctype html>
<html lang="fr">
<head>
<meta charset="utf-8">
<title>Connexion - Dictionnaire de citations</title>
<link rel="stylesheet" href="css/style.css?v=5">
</head>
<body class="page-form">
<main class="carte">
    <h1>Connexion</h1>

    <?php if ($erreur !== ''): ?>
        <p class="alerte alerte-erreur"><?= htmlspecialchars($erreur) ?></p>
    <?php endif; ?>

    <form method="post" novalidate>
        <label for="identifiant">Identifiant</label>
        <input type="text" id="identifiant" name="identifiant" required autofocus>

        <label for="mot_de_passe">Mot de passe</label>
        <input type="password" id="mot_de_passe" name="mot_de_passe" required>

        <button type="submit">Valider</button>
    </form>

    <p class="lien-retour"><a href="mot-de-passe-oublie.php">Mot de passe oublie ?</a></p>
    <p class="lien-retour"><a href="inscription.php">Pas encore de compte ? En creer un</a></p>
    <p class="lien-retour"><a href="index.php">&larr; Retour a l'accueil</a></p>
</main>
</body>
</html>
