<?php
require 'config/db.php';

$token = $_GET['token'] ?? $_POST['token'] ?? '';
$tokenSecurise = mysqli_real_escape_string($connexion, $token);

$resultat = mysqli_query($connexion, "SELECT utilisateur_id, date_expiration FROM reinitialisations WHERE token = '$tokenSecurise'");
$reinitialisation = mysqli_fetch_assoc($resultat);

$erreur = '';
$succes = false;
$lienValide = $reinitialisation && strtotime($reinitialisation['date_expiration']) > time();

if (!$lienValide) {
    $erreur = 'Ce lien de reinitialisation est invalide ou a expire.';
}

if ($lienValide && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $motDePasse = $_POST['mot_de_passe'];
    $motDePasseConfirmation = $_POST['mot_de_passe_confirmation'];

    if (strlen($motDePasse) < 6) {
        $erreur = 'Le mot de passe doit contenir au moins 6 caracteres.';
    } elseif ($motDePasse !== $motDePasseConfirmation) {
        $erreur = 'Les deux mots de passe ne correspondent pas.';
    } else {
        $hash = password_hash($motDePasse, PASSWORD_DEFAULT);
        $idUtilisateur = (int) $reinitialisation['utilisateur_id'];

        mysqli_query($connexion, "UPDATE utilisateurs SET mot_de_passe = '$hash' WHERE id = $idUtilisateur");
        mysqli_query($connexion, "DELETE FROM reinitialisations WHERE utilisateur_id = $idUtilisateur");

        $succes = true;
    }
}
?>
<!doctype html>
<html lang="fr">
<head>
<meta charset="utf-8">
<title>Nouveau mot de passe - Dictionnaire de citations</title>
<link rel="stylesheet" href="css/style.css?v=4">
</head>
<body class="page-form">
<main class="carte">
    <h1>Nouveau mot de passe</h1>

    <?php if ($succes): ?>
        <p class="alerte alerte-succes">Votre mot de passe a ete modifie avec succes.</p>
        <p class="lien-retour"><a href="login.php">Se connecter</a></p>
    <?php else: ?>
        <?php if ($erreur !== ''): ?>
            <p class="alerte alerte-erreur"><?= htmlspecialchars($erreur) ?></p>
        <?php endif; ?>

        <?php if ($lienValide): ?>
            <form method="post" novalidate>
                <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">

                <label for="mot_de_passe">Nouveau mot de passe (6 caracteres minimum)</label>
                <input type="password" id="mot_de_passe" name="mot_de_passe" minlength="6" required autofocus>

                <label for="mot_de_passe_confirmation">Confirmer le mot de passe</label>
                <input type="password" id="mot_de_passe_confirmation" name="mot_de_passe_confirmation" minlength="6" required>

                <button type="submit">Valider</button>
            </form>
        <?php else: ?>
            <p class="lien-retour"><a href="mot-de-passe-oublie.php">Demander un nouveau lien</a></p>
        <?php endif; ?>
    <?php endif; ?>
</main>
</body>
</html>
