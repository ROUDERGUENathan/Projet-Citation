<?php
require 'config/db.php';
session_start();

if (!isset($_SESSION['utilisateur_id']) || $_SESSION['utilisateur_role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

$id = (int) $_GET['id'];

$resultat = mysqli_query($connexion, "SELECT id, auteur, texte FROM citations WHERE id = $id");
$citation = mysqli_fetch_assoc($resultat);

if (!$citation) {
    header('Location: admin.php');
    exit;
}

$erreur = '';
$auteur = $citation['auteur'];
$texte = $citation['texte'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $auteur = trim($_POST['auteur']);
    $texte = trim($_POST['texte']);

    if ($auteur === '' || $texte === '') {
        $erreur = "Merci de renseigner l'auteur et la citation.";
    } else {
        $auteurSecurise = mysqli_real_escape_string($connexion, $auteur);
        $texteSecurise = mysqli_real_escape_string($connexion, $texte);

        $requete = "UPDATE citations SET auteur = '$auteurSecurise', texte = '$texteSecurise' WHERE id = $id";
        mysqli_query($connexion, $requete);

        header('Location: admin.php');
        exit;
    }
}
?>
<!doctype html>
<html lang="fr">
<head>
<meta charset="utf-8">
<title>Modifier une citation - Administration</title>
<link rel="stylesheet" href="css/style.css?v=5">
</head>
<body class="page-form">
<main class="carte">
    <h1>Modifier la citation</h1>

    <?php if ($erreur !== ''): ?>
        <p class="alerte alerte-erreur"><?= htmlspecialchars($erreur) ?></p>
    <?php endif; ?>

    <form method="post" novalidate>
        <label for="auteur">Auteur</label>
        <input type="text" id="auteur" name="auteur" value="<?= htmlspecialchars($auteur) ?>" required>

        <label for="texte">Citation</label>
        <textarea id="texte" name="texte" rows="6" required><?= htmlspecialchars($texte) ?></textarea>

        <button type="submit">Enregistrer</button>
    </form>

    <p class="lien-retour"><a href="admin.php">&larr; Retour a l'administration</a></p>
</main>
</body>
</html>
