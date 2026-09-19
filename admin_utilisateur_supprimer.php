<?php
require 'config/db.php';
session_start();

if (!isset($_SESSION['utilisateur_id']) || $_SESSION['utilisateur_role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

$id = (int) ($_POST['id'] ?? $_GET['id']);

$resultat = mysqli_query($connexion, "SELECT id, identifiant, email FROM utilisateurs WHERE id = $id");
$utilisateur = mysqli_fetch_assoc($resultat);

if (!$utilisateur) {
    header('Location: admin_utilisateurs.php');
    exit;
}

if ($id === $_SESSION['utilisateur_id']) {
    header('Location: admin_utilisateurs.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirmer'])) {
    mysqli_query($connexion, "DELETE FROM utilisateurs WHERE id = $id");
    header('Location: admin_utilisateurs.php');
    exit;
}
?>
<!doctype html>
<html lang="fr">
<head>
<meta charset="utf-8">
<title>Supprimer un utilisateur - Administration</title>
<link rel="stylesheet" href="css/style.css?v=4">
</head>
<body class="page-form">
<main class="carte">
    <h1>Supprimer cet utilisateur ?</h1>

    <p><?= htmlspecialchars($utilisateur['identifiant']) ?> (<?= htmlspecialchars($utilisateur['email']) ?>)</p>

    <form method="post">
        <input type="hidden" name="id" value="<?= (int) $utilisateur['id'] ?>">
        <button type="submit" name="confirmer" value="1">Confirmer la suppression</button>
    </form>

    <p class="lien-retour"><a href="admin_utilisateurs.php">Annuler</a></p>
</main>
</body>
</html>
