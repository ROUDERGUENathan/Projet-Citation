<?php
require 'config/db.php';
session_start();

if (!isset($_SESSION['utilisateur_id']) || $_SESSION['utilisateur_role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

$id = (int) ($_POST['id'] ?? $_GET['id']);

$resultat = mysqli_query($connexion, "SELECT id, identifiant, role FROM utilisateurs WHERE id = $id");
$utilisateur = mysqli_fetch_assoc($resultat);

if (!$utilisateur || $utilisateur['role'] === 'admin') {
    header('Location: admin_utilisateurs.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirmer'])) {
    mysqli_query($connexion, "UPDATE utilisateurs SET role = 'admin' WHERE id = $id");
    header('Location: admin_utilisateurs.php');
    exit;
}
?>
<!doctype html>
<html lang="fr">
<head>
<meta charset="utf-8">
<title>Promouvoir un utilisateur - Administration</title>
<link rel="stylesheet" href="css/style.css?v=5">
</head>
<body class="page-form">
<main class="carte">
    <h1>Donner les droits administrateur ?</h1>

    <p><?= htmlspecialchars($utilisateur['identifiant']) ?> deviendra administrateur du site.</p>

    <form method="post">
        <input type="hidden" name="id" value="<?= (int) $utilisateur['id'] ?>">
        <button type="submit" name="confirmer" value="1">Confirmer la promotion</button>
    </form>

    <p class="lien-retour"><a href="admin_utilisateurs.php">Annuler</a></p>
</main>
</body>
</html>
