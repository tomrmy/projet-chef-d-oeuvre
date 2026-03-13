-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : ven. 13 mars 2026 à 11:09
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `gestion_badges`
--

-- --------------------------------------------------------

--
-- Structure de la table `badges_bloques`
--

CREATE TABLE `badges_bloques` (
  `id` int(11) NOT NULL,
  `numero_badge` varchar(50) NOT NULL,
  `date_blocage` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `badges_inconnus`
--

CREATE TABLE `badges_inconnus` (
  `id` int(11) NOT NULL,
  `numero_badge` varchar(50) NOT NULL,
  `date_scan` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `logs_generaux`
--

CREATE TABLE `logs_generaux` (
  `id` int(11) NOT NULL,
  `numero_badge` varchar(50) NOT NULL,
  `statut` varchar(50) NOT NULL,
  `date_scan` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `logs_generaux`
--

INSERT INTO `logs_generaux` (`id`, `numero_badge`, `statut`, `date_scan`) VALUES
(1, '1C00318D9131', 'Autorisé', '2026-03-13 10:50:51'),
(2, '02008AE9A5C4', 'Inconnu', '2026-03-13 10:51:06'),
(3, '02008AE9A5C4', 'Autorisé', '2026-03-13 10:51:48'),
(4, '1C00318D9131', 'Autorisé', '2026-03-13 10:58:02'),
(5, '1C0032026448', 'Autorisé', '2026-03-13 10:58:12'),
(6, '1C0032026448', 'Autorisé', '2026-03-13 10:58:14'),
(7, '1C0032026448', 'Autorisé', '2026-03-13 10:58:41');

-- --------------------------------------------------------

--
-- Structure de la table `passages_badge`
--

CREATE TABLE `passages_badge` (
  `id` int(11) NOT NULL,
  `numero_badge` varchar(50) NOT NULL,
  `date_passage` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `passages_badge`
--

INSERT INTO `passages_badge` (`id`, `numero_badge`, `date_passage`) VALUES
(1, '1C00318D9131', '2026-03-13 09:39:53'),
(2, '1C00318D9131', '2026-03-13 09:40:32'),
(3, '1C00318D9131', '2026-03-13 09:47:35'),
(4, '1C00318D9131', '2026-03-13 10:21:13'),
(5, '1C0032026448', '2026-03-13 10:21:19'),
(6, '1C0032026448', '2026-03-13 10:21:21'),
(7, '1C00318D9131', '2026-03-13 10:50:51'),
(8, '02008AE9A5C4', '2026-03-13 10:51:47'),
(9, '1C00318D9131', '2026-03-13 10:58:02'),
(10, '1C0032026448', '2026-03-13 10:58:11'),
(11, '1C0032026448', '2026-03-13 10:58:14'),
(12, '1C0032026448', '2026-03-13 10:58:41');

-- --------------------------------------------------------

--
-- Structure de la table `utilisateurs`
--

CREATE TABLE `utilisateurs` (
  `id` int(11) NOT NULL,
  `nom` varchar(50) NOT NULL,
  `prenom` varchar(50) NOT NULL,
  `classe` varchar(20) NOT NULL,
  `numero_badge` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `utilisateurs`
--

INSERT INTO `utilisateurs` (`id`, `nom`, `prenom`, `classe`, `numero_badge`) VALUES
(2, 'Burey', 'Peter', 'TP3', '1C00318D9131'),
(3, 'Roumy', 'Tom', 'TP3', '1C0032026448'),
(4, 'Dieng', 'Marley', '3eme', '02008AE9A5C4');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `badges_bloques`
--
ALTER TABLE `badges_bloques`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `numero_badge` (`numero_badge`);

--
-- Index pour la table `badges_inconnus`
--
ALTER TABLE `badges_inconnus`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `numero_badge` (`numero_badge`);

--
-- Index pour la table `logs_generaux`
--
ALTER TABLE `logs_generaux`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `passages_badge`
--
ALTER TABLE `passages_badge`
  ADD PRIMARY KEY (`id`),
  ADD KEY `numero_badge` (`numero_badge`);

--
-- Index pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `numero_badge` (`numero_badge`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `badges_bloques`
--
ALTER TABLE `badges_bloques`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `badges_inconnus`
--
ALTER TABLE `badges_inconnus`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `logs_generaux`
--
ALTER TABLE `logs_generaux`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pour la table `passages_badge`
--
ALTER TABLE `passages_badge`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `passages_badge`
--
ALTER TABLE `passages_badge`
  ADD CONSTRAINT `passages_badge_ibfk_1` FOREIGN KEY (`numero_badge`) REFERENCES `utilisateurs` (`numero_badge`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
