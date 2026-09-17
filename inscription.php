<?php
require 'config/db.php';
session_start();

if (isset($_SESSION['utilisateur_id'])) {
    header('Location: index.php');
    exit;
}

$erreur = '';
$identifiant = '';
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $identifiant = trim($_POST['identifiant']);
    $email = trim($_POST['email']);
    $motDePasse = $_POST['mot_de_passe'];
    $motDePasseConfirmation = $_POST['mot_de_passe_confirmation'];

    if ($identifiant === '' || $email === '' || $motDePasse === '') {
        $erreur = 'Merci de remplir tous les champs.';
    } elseif (strlen($motDePasse) < 6) {
        $erreur = 'Le mot de passe doit contenir au moins 6 caracteres.';
    } elseif ($motDePasse !== $motDePasseConfirmation) {
        $erreur = 'Les deux mots de passe ne correspondent pas.';
    } else {
        $identifiantSecurise = mysqli_real_escape_string($connexion, $identifiant);
        $emailSecurise = mysqli_real_escape_string($connexion, $email);

        $resultat = mysqli_query($connexion, "SELECT id FROM utilisateurs WHERE identifiant = '$identifiantSecurise' OR email = '$emailSecurise'");

        if (mysqli_fetch_assoc($resultat)) {
            $erreur = 'Cet identifiant ou cet email est deja utilise.';
        } else {
            $hash = password_hash($motDePasse, PASSWORD_DEFAULT);
            $requete = "INSERT INTO utilisateurs (identifiant, email, mot_de_passe) VALUES ('$identifiantSecurise', '$emailSecurise', '$hash')";
            mysqli_query($connexion, $requete);

            $_SESSION['utilisateur_id'] = mysqli_insert_id($connexion);
            $_SESSION['utilisateur_identifiant'] = $identifiant;
            $_SESSION['utilisateur_role'] = 'visiteur';
            header('Location: index.php');
            exit;
        }
    }
}
?>
<!doctype html>
<html lang="fr">
<head>
<meta charset="utf-8">
<title>Creer un compte - Dictionnaire de citations</title>
<link rel="stylesheet" href="css/style.css?v=3">
</head>
<body class="page-form">
<main class="carte">
    <h1>Creer un compte</h1>

    <?php if ($erreur !== ''): ?>
        <p class="alerte alerte-erreur"><?= htmlspecialchars($erreur) ?></p>
    <?php endif; ?>

    <form method="post" novalidate>
        <label for="identifiant">Identifiant</label>
        <input type="text" id="identifiant" name="identifiant" value="<?= htmlspecialchars($identifiant) ?>" required>

        <label for="email">Email</label>
        <input type="email" id="email" name="email" value="<?= htmlspecialchars($email) ?>" required>

        <label for="mot_de_passe">Mot de passe (6 caracteres minimum)</label>
        <input type="password" id="mot_de_passe" name="mot_de_passe" minlength="6" required>

        <label for="mot_de_passe_confirmation">Confirmer le mot de passe</label>
        <input type="password" id="mot_de_passe_confirmation" name="mot_de_passe_confirmation" minlength="6" required>

        <button type="submit">Creer mon compte</button>
    </form>

    <p class="lien-retour"><a href="login.php">Deja un compte ? Se connecter</a></p>
    <p class="lien-retour"><a href="index.php">&larr; Retour a l'accueil</a></p>
</main>
</body>
</html>
