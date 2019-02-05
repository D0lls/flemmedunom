-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Client :  127.0.0.1
-- Généré le :  Mar 05 Février 2019 à 13:53
-- Version du serveur :  5.7.14
-- Version de PHP :  5.6.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `infoiut`
--

-- --------------------------------------------------------

--
-- Structure de la table `attente`
--

CREATE TABLE `attente` (
  `id_attente` int(11) NOT NULL,
  `contenu` text NOT NULL,
  `date` date NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `information`
--

CREATE TABLE `informations` (
  `id_info` int(11) NOT NULL,
  `contenu` text NOT NULL,
  `auteur` text NOT NULL,
  `date` date NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `role`
--

CREATE TABLE `role` (
  `id_role` int(11) NOT NULL,
  `nom_role` varchar(50) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

CREATE TABLE `utilisateur` (
  `id_user` int(11) NOT NULL,
  `nom` varchar(50) NOT NULL,
  `prenom` varchar(50) NOT NULL,
  `role` varchar(50) NOT NULL,
  `mdp` varchar(50) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Index pour les tables exportées
--

--
-- Index pour la table `attente`
--
ALTER TABLE `attente`
  ADD PRIMARY KEY (`id_attente`);

--
-- Index pour la table `information`
--
ALTER TABLE `informations`
  ADD PRIMARY KEY (`id_info`);

--
-- Index pour la table `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`id_role`);

--
-- Index pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `attente`
--
ALTER TABLE `attente`
  MODIFY `id_attente` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `information`
--
ALTER TABLE `informations`
  MODIFY `id_info` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `role`
--
ALTER TABLE `role`
  MODIFY `id_role` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
