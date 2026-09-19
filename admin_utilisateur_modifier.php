<?php
require 'config/db.php';
session_start();

if (!isset($_SESSION['utilisateur_id']) || $_SESSION['utilisateur_role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

$id = (int) $_GET['id'];

$resultat = mysqli_query($connexion, "SELECT id, identifiant, email FROM utilisateurs WHERE id = $id");
$utilisateur = mysqli_fetch_assoc($resultat);

if (!$utilisateur) {
    header('Location: admin_utilisateurs.php');
    exit;
}

$erreur = '';
$identifiant = $utilisateur['identifiant'];
$email = $utilisateur['email'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $identifiant = trim($_POST['identifiant']);
    $email = trim($_POST['email']);

    if ($identifiant === '' || $email === '') {
        $erreur = 'Merci de renseigner l\'identifiant et l\'email.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erreur = 'Merci de saisir une adresse email valide.';
    } else {
        $identifiantSecurise = mysqli_real_escape_string($connexion, $identifiant);
        $emailSecurise = mysqli_real_escape_string($connexion, $email);

        $requete = "UPDATE utilisateurs SET identifiant = '$identifiantSecurise', email = '$emailSecurise' WHERE id = $id";
        mysqli_query($connexion, $requete);

        header('Location: admin_utilisateurs.php');
        exit;
    }
}
?>
<!doctype html>
<html lang="fr">
<head>
<meta charset="utf-8">
<title>Modifier un utilisateur - Administration</title>
<link rel="stylesheet" href="css/style.css?v=3">
</head>
<body class="page-form">
<main class="carte">
    <h1>Modifier l'utilisateur</h1>

    <?php if ($erreur !== ''): ?>
        <p class="alerte alerte-erreur"><?= htmlspecialchars($erreur) ?></p>
    <?php endif; ?>

    <form method="post" novalidate>
        <label for="identifiant">Identifiant</label>
        <input type="text" id="identifiant" name="identifiant" value="<?= htmlspecialchars($identifiant) ?>" required>

        <label for="email">Email</label>
        <input type="email" id="email" name="email" value="<?= htmlspecialchars($email) ?>" required>

        <button type="submit">Enregistrer</button>
    </form>

    <p class="lien-retour"><a href="admin_utilisateurs.php">&larr; Retour aux utilisateurs</a></p>
</main>
</body>
</html>
