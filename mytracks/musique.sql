-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : mar. 16 mai 2023 à 14:49
-- Version du serveur : 8.0.31
-- Version de PHP : 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `musique`
--

-- --------------------------------------------------------

--
-- Structure de la table `artist`
--

DROP TABLE IF EXISTS `artist`;
CREATE TABLE IF NOT EXISTS `artist` (
  `id_artist` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id_artist`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `artist`
--

INSERT INTO `artist` (`id_artist`, `nom`) VALUES
(1, 'Alpha'),
(2, 'Martine'),
(3, 'Sefyu'),
(4, 'Hamoud');

-- --------------------------------------------------------

--
-- Structure de la table `track`
--

DROP TABLE IF EXISTS `track`;
CREATE TABLE IF NOT EXISTS `track` (
  `id_track` int NOT NULL AUTO_INCREMENT,
  `titre_track` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `id_artist` int NOT NULL,
  PRIMARY KEY (`id_track`),
  UNIQUE KEY `id_artist` (`id_artist`)
) ENGINE=InnoDB AUTO_INCREMENT=110 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `track`
--

INSERT INTO `track` (`id_track`, `titre_track`, `id_artist`) VALUES
(1, 'mnsDevWeb1', 3),
(13, 'Developpeurr', 2),
(26, 'La vie est troy', 4),
(51, 'roroevervevrveer', 1);

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `track`
--
ALTER TABLE `track`
  ADD CONSTRAINT `track_ibfk_1` FOREIGN KEY (`id_artist`) REFERENCES `artist` (`id_artist`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
