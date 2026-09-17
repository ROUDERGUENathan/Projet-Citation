<?php
require 'config/db.php';

$resultat = mysqli_query($connexion, "SELECT COUNT(*) AS total FROM administrateurs");
$ligne = mysqli_fetch_assoc($resultat);
$dejaConfigure = $ligne['total'] > 0;

$message = '';
$erreur = '';

if (!$dejaConfigure && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $identifiant = trim($_POST['identifiant']);
    $motDePasse = $_POST['mot_de_passe'];

    if ($identifiant === '' || strlen($motDePasse) < 6) {
        $erreur = 'Identifiant requis et mot de passe de 6 caracteres minimum.';
    } else {
        $hash = password_hash($motDePasse, PASSWORD_DEFAULT);
        $identifiantSecurise = mysqli_real_escape_string($connexion, $identifiant);

        $requete = "INSERT INTO administrateurs (identifiant, mot_de_passe) VALUES ('$identifiantSecurise', '$hash')";
        mysqli_query($connexion, $requete);

        $dejaConfigure = true;
        $message = 'Compte administrateur cree avec succes. Supprimez maintenant ce fichier (setup_admin.php) puis connectez-vous depuis login.php.';
    }
}
?>
<!doctype html>
<html lang="fr">
<head>
<meta charset="utf-8">
<title>Configuration initiale - Administrateur</title>
<link rel="stylesheet" href="css/style.css?v=3">
</head>
<body class="page-form">
<main class="carte">
    <h1>Creation du compte administrateur</h1>

    <?php if ($dejaConfigure): ?>
        <p class="alerte alerte-succes">
            <?= $message !== '' ? htmlspecialchars($message) : 'Un compte administrateur existe deja. Rendez-vous sur login.php pour vous connecter. Pensez a supprimer ce fichier.' ?>
        </p>
        <p><a href="login.php">Aller a la page de connexion</a></p>
    <?php else: ?>
        <?php if ($erreur !== ''): ?>
            <p class="alerte alerte-erreur"><?= htmlspecialchars($erreur) ?></p>
        <?php endif; ?>
        <form method="post" novalidate>
            <label for="identifiant">Identifiant</label>
            <input type="text" id="identifiant" name="identifiant" required>

            <label for="mot_de_passe">Mot de passe (6 caracteres minimum)</label>
            <input type="password" id="mot_de_passe" name="mot_de_passe" minlength="6" required>

            <button type="submit">Creer le compte administrateur</button>
        </form>
    <?php endif; ?>
</main>
</body>
</html>
