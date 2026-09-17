<?php
require 'config/db.php';
session_start();

if (isset($_SESSION['admin_id'])) {
    header('Location: admin.php');
    exit;
}

$erreur = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $identifiant = trim($_POST['identifiant']);
    $motDePasseSaisi = $_POST['mot_de_passe'];

    // On echappe la valeur avant de la mettre dans la requete SQL
    // (protection contre les injections SQL).
    $identifiantSecurise = mysqli_real_escape_string($connexion, $identifiant);

    $requete = "SELECT id, identifiant, mot_de_passe FROM administrateurs WHERE identifiant = '$identifiantSecurise'";
    $resultat = mysqli_query($connexion, $requete);
    $admin = mysqli_fetch_assoc($resultat);

    // password_verify() compare le mot de passe saisi avec le hash
    // enregistre en base (cree par password_hash() dans setup_admin.php).
    if ($admin && password_verify($motDePasseSaisi, $admin['mot_de_passe'])) {
        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['admin_identifiant'] = $admin['identifiant'];
        header('Location: admin.php');
        exit;
    }

    $erreur = 'Identifiant ou mot de passe incorrect.';
}
?>
<!doctype html>
<html lang="fr">
<head>
<meta charset="utf-8">
<title>Connexion administrateur - Dictionnaire de citations</title>
<link rel="stylesheet" href="css/style.css">
</head>
<body class="page-form">
<main class="carte">
    <h1>Espace administrateur</h1>
    <p>Veuillez entrer l'utilisateur et le mot de passe pour acceder au domaine administrateur :</p>

    <?php if ($erreur !== ''): ?>
        <p class="alerte alerte-erreur"><?= htmlspecialchars($erreur) ?></p>
    <?php endif; ?>

    <form method="post" novalidate>
        <label for="identifiant">Utilisateur</label>
        <input type="text" id="identifiant" name="identifiant" required autofocus>

        <label for="mot_de_passe">Mot de passe</label>
        <input type="password" id="mot_de_passe" name="mot_de_passe" required>

        <button type="submit">Valider</button>
    </form>

    <p class="lien-retour"><a href="index.php">&larr; Retour a l'accueil</a></p>
</main>
</body>
</html>
