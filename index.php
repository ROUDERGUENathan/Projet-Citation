<?php
require 'config/db.php';
session_start();

$resultat = mysqli_query($connexion, "SELECT auteur, texte FROM citations ORDER BY RAND() LIMIT 1");
$citation = mysqli_fetch_assoc($resultat);

if (!$citation) {
    $citation = [
        'auteur' => '',
        'texte' => 'Aucune citation pour le moment. Soyez le premier a en deposer une !',
    ];
}

$fonds = ['bg-0', 'bg-1', 'bg-2', 'bg-3', 'bg-4', 'bg-5'];
$fond = $fonds[array_rand($fonds)];
?>
<!doctype html>
<html lang="fr">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="refresh" content="15">
<title>Dictionnaire de citations</title>
<link rel="stylesheet" href="css/style.css?v=3">
</head>
<body>
<main class="carousel <?= $fond ?>">
    <div class="silhouette" aria-hidden="true"></div>

    <p class="bienvenue">Bienvenue</p>

    <div class="coin-connexion">
        <?php if (isset($_SESSION['utilisateur_id'])): ?>
            <p>Connecte : <?= htmlspecialchars($_SESSION['utilisateur_identifiant']) ?></p>
            <?php if ($_SESSION['utilisateur_role'] === 'admin'): ?>
                <a class="lien-connexion" href="admin.php">Espace administrateur</a>
            <?php endif; ?>
            <a class="lien-connexion" href="logout.php">Deconnexion</a>
        <?php else: ?>
            <form class="connexion-box" method="post" action="login.php">
                <label for="identifiant">Identifiant :</label>
                <input type="text" id="identifiant" name="identifiant" required>
                <label for="mot_de_passe">Mot de passe :</label>
                <input type="password" id="mot_de_passe" name="mot_de_passe" required>
                <button type="submit">Valider</button>
            </form>
            <p class="lien-retour"><a href="inscription.php">Creer un compte</a> &middot; <a href="mot-de-passe-oublie.php">Mot de passe oublie</a></p>
        <?php endif; ?>
    </div>

    <blockquote>&laquo; <?= htmlspecialchars($citation['texte']) ?> &raquo;</blockquote>
    <?php if ($citation['auteur'] !== ''): ?>
        <p class="auteur"><?= htmlspecialchars($citation['auteur']) ?></p>
    <?php endif; ?>

    <a class="lien-ajouter" href="ajouter.php">+ Deposer une citation</a>
</main>
</body>
</html>
