<?php
require 'config/db.php';
session_start();

if (!isset($_SESSION['utilisateur_id']) || $_SESSION['utilisateur_role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

$resultat = mysqli_query($connexion, "SELECT id, identifiant, email, role, date_inscription FROM utilisateurs ORDER BY date_inscription DESC");
$nombreUtilisateurs = mysqli_num_rows($resultat);
?>
<!doctype html>
<html lang="fr">
<head>
<meta charset="utf-8">
<title>Utilisateurs - Administration</title>
<link rel="stylesheet" href="css/style.css?v=5">
</head>
<body class="page-admin">
<header class="topbar">
    <span class="brand">Dictionnaire de citations - Administration</span>
    <nav>
        <span>Connecte : <?= htmlspecialchars($_SESSION['utilisateur_identifiant']) ?></span>
        <a href="admin.php">Citations</a>
        <a href="index.php">Voir le site</a>
        <a href="logout.php">Deconnexion</a>
    </nav>
</header>

<main class="conteneur-admin">
    <div class="admin-actions">
        <h1>Gestion des utilisateurs (<?= $nombreUtilisateurs ?>)</h1>
    </div>

    <table class="table-citations">
        <thead>
            <tr>
                <th>Identifiant</th>
                <th>Email</th>
                <th>Role</th>
                <th>Inscrit le</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($utilisateur = mysqli_fetch_assoc($resultat)): ?>
                <tr>
                    <td><?= htmlspecialchars($utilisateur['identifiant']) ?></td>
                    <td><?= htmlspecialchars($utilisateur['email']) ?></td>
                    <td><span class="badge badge-<?= htmlspecialchars($utilisateur['role']) ?>"><?= htmlspecialchars($utilisateur['role']) ?></span></td>
                    <td><?= htmlspecialchars(date('d/m/Y H:i', strtotime($utilisateur['date_inscription']))) ?></td>
                    <td class="actions">
                        <a href="admin_utilisateur_modifier.php?id=<?= (int) $utilisateur['id'] ?>">Modifier</a>
                        <?php if ($utilisateur['role'] !== 'admin'): ?>
                            <a href="admin_utilisateur_promouvoir.php?id=<?= (int) $utilisateur['id'] ?>">Promouvoir admin</a>
                        <?php endif; ?>
                        <a href="admin_utilisateur_supprimer.php?id=<?= (int) $utilisateur['id'] ?>" class="lien-danger">Supprimer</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</main>
</body>
</html>
