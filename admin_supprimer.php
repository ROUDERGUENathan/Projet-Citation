<?php
require 'config/db.php';
session_start();

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

$id = (int) ($_POST['id'] ?? $_GET['id']);

$resultat = mysqli_query($connexion, "SELECT id, auteur, texte FROM citations WHERE id = $id");
$citation = mysqli_fetch_assoc($resultat);

if (!$citation) {
    header('Location: admin.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirmer'])) {
    mysqli_query($connexion, "DELETE FROM citations WHERE id = $id");
    header('Location: admin.php');
    exit;
}
?>
<!doctype html>
<html lang="fr">
<head>
<meta charset="utf-8">
<title>Supprimer une citation - Administration</title>
<link rel="stylesheet" href="css/style.css?v=2">
</head>
<body class="page-form">
<main class="carte">
    <h1>Supprimer cette citation ?</h1>

    <blockquote>&laquo; <?= htmlspecialchars($citation['texte']) ?> &raquo;</blockquote>
    <p class="auteur"><?= htmlspecialchars($citation['auteur']) ?></p>

    <form method="post">
        <input type="hidden" name="id" value="<?= (int) $citation['id'] ?>">
        <button type="submit" name="confirmer" value="1">Confirmer la suppression</button>
    </form>

    <p class="lien-retour"><a href="admin.php">Annuler</a></p>
</main>
</body>
</html>
