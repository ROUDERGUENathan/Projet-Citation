<?php
require 'config/db.php';
session_start();

if (!isset($_SESSION['utilisateur_id'])) {
    header('Location: login.php');
    exit;
}

$erreur = '';
$succes = false;
$auteur = '';
$texte = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $auteur = trim($_POST['auteur']);
    $texte = trim($_POST['texte']);

    if ($auteur === '' || $texte === '') {
        $erreur = "Merci de renseigner l'auteur et la citation.";
    } else {
        $auteurSecurise = mysqli_real_escape_string($connexion, $auteur);
        $texteSecurise = mysqli_real_escape_string($connexion, $texte);

        $requete = "INSERT INTO citations (auteur, texte) VALUES ('$auteurSecurise', '$texteSecurise')";
        mysqli_query($connexion, $requete);

        $succes = true;
        $auteur = '';
        $texte = '';
    }
}
?>
<!doctype html>
<html lang="fr">
<head>
<meta charset="utf-8">
<title>Deposer une citation - Dictionnaire de citations</title>
<link rel="stylesheet" href="css/style.css?v=5">
</head>
<body class="page-form">
<main class="carte">
    <h1>Ajout Citation</h1>

    <?php if ($succes): ?>
        <p class="alerte alerte-succes">Merci ! Votre citation a ete ajoutee au dictionnaire.</p>
    <?php endif; ?>

    <?php if ($erreur !== ''): ?>
        <p class="alerte alerte-erreur"><?= htmlspecialchars($erreur) ?></p>
    <?php endif; ?>

    <form method="post" novalidate>
        <label for="auteur">Auteur</label>
        <input type="text" id="auteur" name="auteur" value="<?= htmlspecialchars($auteur) ?>" required>

        <label for="texte">Citation</label>
        <textarea id="texte" name="texte" rows="6" placeholder="Ecrivez une citation ici !" required><?= htmlspecialchars($texte) ?></textarea>

        <button type="submit">&check; Poster</button>
    </form>

    <p class="lien-retour"><a href="index.php">&larr; Retour a l'accueil</a></p>
</main>
</body>
</html>
