<?php
require 'config/db.php';
session_start();

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

$resultat = mysqli_query($connexion, "SELECT id, auteur, texte, date_ajout FROM citations ORDER BY date_ajout DESC");
$nombreCitations = mysqli_num_rows($resultat);
?>
<!doctype html>
<html lang="fr">
<head>
<meta charset="utf-8">
<title>Administration - Dictionnaire de citations</title>
<link rel="stylesheet" href="css/style.css?v=2">
</head>
<body class="page-admin">
<header class="topbar">
    <span class="brand">Dictionnaire de citations - Administration</span>
    <nav>
        <span>Connecte : <?= htmlspecialchars($_SESSION['admin_identifiant']) ?></span>
        <a href="index.php">Voir le site</a>
        <a href="logout.php">Deconnexion</a>
    </nav>
</header>

<main class="conteneur-admin">
    <div class="admin-actions">
        <h1>Gestion des citations (<?= $nombreCitations ?>)</h1>
        <a class="bouton" href="ajouter.php">+ Ajouter une citation</a>
    </div>

    <?php if ($nombreCitations === 0): ?>
        <p>Aucune citation enregistree pour le moment.</p>
    <?php else: ?>
        <table class="table-citations">
            <thead>
                <tr>
                    <th>Auteur</th>
                    <th>Citation</th>
                    <th>Ajoutee le</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($citation = mysqli_fetch_assoc($resultat)): ?>
                    <tr>
                        <td><?= htmlspecialchars($citation['auteur']) ?></td>
                        <td><?= htmlspecialchars($citation['texte']) ?></td>
                        <td><?= htmlspecialchars(date('d/m/Y H:i', strtotime($citation['date_ajout']))) ?></td>
                        <td class="actions">
                            <a href="admin_modifier.php?id=<?= (int) $citation['id'] ?>">Modifier</a>
                            <a href="admin_supprimer.php?id=<?= (int) $citation['id'] ?>" class="lien-danger">Supprimer</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php endif; ?>
</main>
</body>
</html>
