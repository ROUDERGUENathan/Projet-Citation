<?php
// Connexion au serveur MySQL et selection de la base de donnees.
// Adaptez ces 4 valeurs a votre environnement (local ou hebergement).
$serveur = 'localhost';
$utilisateur = 'root';
$motDePasse = '';
$nomBase = 'dictionnaire_citations';

$connexion = mysqli_connect($serveur, $utilisateur, $motDePasse, $nomBase);

if (!$connexion) {
    die('Erreur de connexion a la base de donnees : ' . mysqli_connect_error());
}

mysqli_set_charset($connexion, 'utf8mb4');
