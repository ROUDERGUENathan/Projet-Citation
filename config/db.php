<?php
$serveur = 'sql308.infinityfree.com';
$utilisateur = 'if0_42941135';
$motDePasse = '';
$nomBase = 'if0_42941135_citations';

$connexion = mysqli_connect($serveur, $utilisateur, $motDePasse, $nomBase);

if (!$connexion) {
    die('Erreur de connexion a la base de donnees : ' . mysqli_connect_error());
}

mysqli_set_charset($connexion, 'utf8mb4');
