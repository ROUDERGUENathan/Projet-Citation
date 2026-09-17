<?php
// Connexion au serveur MySQL et selection de la base de donnees
// (hebergement InfinityFree).
// Le mot de passe n'est jamais enregistre ici ni sur GitHub : il est
// renseigne uniquement en ligne, directement sur le serveur, via
// l'editeur du File Manager InfinityFree.
$serveur = 'sql308.infinityfree.com';
$utilisateur = 'if0_42941135';
$motDePasse = '';
$nomBase = 'if0_42941135_citations';

$connexion = mysqli_connect($serveur, $utilisateur, $motDePasse, $nomBase);

if (!$connexion) {
    die('Erreur de connexion a la base de donnees : ' . mysqli_connect_error());
}

mysqli_set_charset($connexion, 'utf8mb4');
