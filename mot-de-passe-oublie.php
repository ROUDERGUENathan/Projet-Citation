<?php
require 'config/db.php';
require 'config/email.php';

$message = '';
$erreur = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $emailSecurise = mysqli_real_escape_string($connexion, $email);

    $resultat = mysqli_query($connexion, "SELECT id, identifiant FROM utilisateurs WHERE email = '$emailSecurise'");
    $utilisateur = mysqli_fetch_assoc($resultat);

    if ($utilisateur) {
        $token = bin2hex(random_bytes(32));
        $expiration = date('Y-m-d H:i:s', time() + 3600);

        mysqli_query($connexion, "DELETE FROM reinitialisations WHERE utilisateur_id = " . (int) $utilisateur['id']);

        $requete = "INSERT INTO reinitialisations (utilisateur_id, token, date_expiration) VALUES (" . (int) $utilisateur['id'] . ", '$token', '$expiration')";
        mysqli_query($connexion, $requete);

        $lien = "https://projet-citationnr.gt.tc/reinitialiser.php?token=$token";
        $contenu = "<p>Bonjour " . htmlspecialchars($utilisateur['identifiant']) . ",</p>"
            . "<p>Cliquez sur ce lien pour choisir un nouveau mot de passe (valable 1 heure) :</p>"
            . "<p><a href=\"$lien\">$lien</a></p>";

        envoyerEmail($email, 'Reinitialisation de votre mot de passe', $contenu);
    }

    $message = 'Si un compte existe avec cet email, un lien de reinitialisation vient de lui etre envoye.';
}
?>
<!doctype html>
<html lang="fr">
<head>
<meta charset="utf-8">
<title>Mot de passe oublie - Dictionnaire de citations</title>
<link rel="stylesheet" href="css/style.css?v=3">
</head>
<body class="page-form">
<main class="carte">
    <h1>Mot de passe oublie</h1>

    <?php if ($message !== ''): ?>
        <p class="alerte alerte-succes"><?= htmlspecialchars($message) ?></p>
    <?php else: ?>
        <p>Entrez l'email associe a votre compte, vous recevrez un lien pour choisir un nouveau mot de passe.</p>
        <form method="post" novalidate>
            <label for="email">Email</label>
            <input type="email" id="email" name="email" required autofocus>

            <button type="submit">Envoyer le lien</button>
        </form>
    <?php endif; ?>

    <p class="lien-retour"><a href="login.php">&larr; Retour a la connexion</a></p>
</main>
</body>
</html>
