-- Dictionnaire de citations - schema de la base de donnees
-- A importer via phpMyAdmin ou : mysql -u root -p < sql/schema.sql

CREATE DATABASE IF NOT EXISTS dictionnaire_citations
    CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE dictionnaire_citations;

CREATE TABLE IF NOT EXISTS citations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    auteur VARCHAR(255) NOT NULL,
    texte TEXT NOT NULL,
    date_ajout DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS administrateurs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    identifiant VARCHAR(100) NOT NULL UNIQUE,
    mot_de_passe VARCHAR(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Quelques citations de depart (facultatif)
INSERT INTO citations (auteur, texte) VALUES
    ('Antoine de Saint-Exupery', 'La vie est un mystere qu''il faut vivre, et non un probleme a resoudre.'),
    ('Victor Hugo', 'La musique exprime ce qui ne peut etre dit et sur quoi il est impossible de rester silencieux.'),
    ('Albert Camus', 'Dans le milieu de l''hiver, j''apprenais enfin qu''il y avait en moi un ete invincible.');

-- Aucun administrateur n'est cree ici : utilisez setup_admin.php une seule fois
-- apres avoir importe ce fichier, pour creer le premier compte administrateur.
