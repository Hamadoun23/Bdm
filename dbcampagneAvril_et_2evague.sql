-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : lun. 15 juin 2026 à 18:53
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
-- Base de données : `bdm`
--

-- --------------------------------------------------------

--
-- Structure de la table `agences`
--

CREATE TABLE `agences` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `ordre` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `nom` varchar(255) NOT NULL,
  `adresse` varchar(255) DEFAULT NULL,
  `chef_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `agences`
--

INSERT INTO `agences` (`id`, `ordre`, `nom`, `adresse`, `chef_id`, `created_at`, `updated_at`) VALUES
(2, 2, 'Niamana', NULL, NULL, '2026-03-30 10:17:27', '2026-04-10 13:12:03'),
(3, 3, 'PME/PMI', NULL, NULL, '2026-03-30 10:17:27', '2026-04-10 13:12:03'),
(4, 4, 'Centre d\'appel', NULL, NULL, '2026-03-30 10:17:27', '2026-04-10 13:12:03'),
(5, 5, 'Sotuba', NULL, NULL, '2026-03-30 10:17:27', '2026-04-10 13:12:03'),
(6, 6, 'Sogoniko', NULL, NULL, '2026-03-30 10:17:27', '2026-04-10 13:12:03'),
(7, 7, 'Korofina', NULL, NULL, '2026-03-30 10:17:27', '2026-04-10 13:12:03'),
(8, 8, 'Baco Djicoroni', NULL, NULL, '2026-03-30 10:17:27', '2026-04-10 13:12:03'),
(9, 9, 'Dibida', NULL, NULL, '2026-03-30 10:17:27', '2026-04-10 13:12:03'),
(10, 10, 'AP 2', NULL, NULL, '2026-03-30 10:17:27', '2026-04-10 13:12:03'),
(11, 11, 'N\'Golonina', NULL, NULL, '2026-03-30 10:17:27', '2026-04-10 13:12:03'),
(12, 12, 'Kalaban coura', NULL, NULL, '2026-03-30 10:17:27', '2026-04-10 13:12:03'),
(13, 13, 'Maison du Hadj', NULL, NULL, '2026-03-30 10:17:27', '2026-04-10 13:12:03'),
(15, 15, 'Yirimadio', NULL, NULL, '2026-03-30 10:17:27', '2026-04-10 13:12:03'),
(16, 16, 'Futura', NULL, NULL, '2026-03-30 10:17:27', '2026-04-10 13:12:03'),
(17, 17, 'Djicoroni para', NULL, NULL, '2026-03-30 10:17:27', '2026-04-10 13:12:03'),
(18, 18, 'Dramane DIAKITE', NULL, NULL, '2026-03-30 10:17:27', '2026-04-10 13:12:03'),
(19, 19, 'Kabala', NULL, NULL, '2026-03-30 10:17:27', '2026-04-10 13:12:03'),
(21, 21, 'AP 1', NULL, NULL, '2026-03-30 10:17:27', '2026-04-10 13:12:03'),
(22, 22, 'Ségou 2', NULL, NULL, '2026-03-30 10:17:27', '2026-04-10 13:12:03'),
(23, 23, 'Ségou 1', NULL, NULL, '2026-03-30 10:17:27', '2026-04-10 13:12:03'),
(24, 24, 'San', NULL, NULL, '2026-03-30 10:17:27', '2026-04-10 13:12:03'),
(25, 25, 'Mopti', NULL, NULL, '2026-03-30 10:17:27', '2026-04-10 13:12:03'),
(26, 26, 'Koulikoro', NULL, NULL, '2026-03-30 10:17:27', '2026-04-10 13:12:03'),
(27, 27, 'Dioila', NULL, NULL, '2026-03-30 10:17:27', '2026-04-10 13:12:03'),
(28, 28, 'Sikasso', NULL, NULL, '2026-03-30 10:17:27', '2026-04-10 13:12:03'),
(29, 29, 'Tombouctou', NULL, NULL, '2026-03-30 10:17:27', '2026-04-10 13:12:03'),
(30, 30, 'Kita', NULL, NULL, '2026-03-30 10:17:27', '2026-04-10 13:12:03'),
(31, 31, 'Kayes 1', NULL, NULL, '2026-03-30 10:17:27', '2026-04-10 13:12:03'),
(32, 32, 'SEMA GESCO', NULL, NULL, '2026-04-10 09:46:18', '2026-04-10 13:12:03'),
(33, 33, 'MISSIRA', NULL, NULL, '2026-04-10 09:46:18', '2026-04-10 13:12:03'),
(34, 34, 'QUINZAMBOUGOU', NULL, NULL, '2026-04-10 09:46:18', '2026-04-10 13:12:03'),
(35, 35, 'SEBENIKORO', NULL, NULL, '2026-04-10 09:46:19', '2026-04-10 13:12:03'),
(36, 36, 'HAMDALLAYE', NULL, NULL, '2026-04-10 09:46:19', '2026-04-10 13:12:03'),
(37, 37, 'LAFIABOUGOU', NULL, NULL, '2026-04-10 09:46:19', '2026-04-10 13:12:03'),
(38, 38, 'TOROKOROBOUGOU', NULL, NULL, '2026-04-10 09:46:20', '2026-04-10 13:12:03'),
(39, 39, 'MAGNAMBOUGOU', NULL, NULL, '2026-04-10 09:46:20', '2026-04-10 13:12:03'),
(40, 40, 'AZAR', NULL, NULL, '2026-04-10 09:46:20', '2026-04-10 13:12:03'),
(42, 42, 'KATI', NULL, NULL, '2026-04-10 09:46:21', '2026-04-10 13:12:03'),
(43, 43, 'Senou', NULL, NULL, '2026-04-10 13:23:48', '2026-04-10 13:23:48'),
(44, 44, 'BS', NULL, NULL, '2026-04-13 10:59:50', '2026-04-13 10:59:50'),
(46, 46, 'kwame nkrumah', NULL, NULL, '2026-04-15 08:50:29', '2026-04-15 08:50:29'),
(47, 47, 'BOULKASSOUMBOUGOU', NULL, NULL, '2026-06-15 16:33:24', '2026-06-15 16:33:24'),
(48, 48, 'AZAR CENTER', NULL, NULL, '2026-06-15 16:33:24', '2026-06-15 16:33:24'),
(49, 49, 'AP2', NULL, NULL, '2026-06-15 16:33:25', '2026-06-15 16:33:25'),
(50, 50, 'DJICORONI-PARA', NULL, NULL, '2026-06-15 16:33:25', '2026-06-15 16:33:25'),
(51, 51, 'DD', NULL, NULL, '2026-06-15 16:33:26', '2026-06-15 16:33:26'),
(52, 52, 'PME/ PMI', NULL, NULL, '2026-06-15 16:33:26', '2026-06-15 16:33:26'),
(53, 53, 'TOROKORO', NULL, NULL, '2026-06-15 16:33:27', '2026-06-15 16:33:27'),
(54, 54, 'MORIBABOUGOU', NULL, NULL, '2026-06-15 16:33:28', '2026-06-15 16:33:28'),
(55, 55, 'BANCONI RAZEL', NULL, NULL, '2026-06-15 16:33:28', '2026-06-15 16:33:28'),
(56, 56, 'SIKASSO 1', NULL, NULL, '2026-06-15 16:33:29', '2026-06-15 16:33:29');

-- --------------------------------------------------------

--
-- Structure de la table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('campagne-bdm-cache-admin|127.0.0.1', 'i:1;', 1781540895),
('campagne-bdm-cache-admin|127.0.0.1:timer', 'i:1781540895;', 1781540895);

-- --------------------------------------------------------

--
-- Structure de la table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `campagnes`
--

CREATE TABLE `campagnes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nom` varchar(255) NOT NULL,
  `date_debut` date NOT NULL,
  `date_fin` date NOT NULL,
  `prime_meilleur_vendeur` decimal(12,0) NOT NULL DEFAULT 25000,
  `remise_pourcentage` decimal(5,2) DEFAULT NULL,
  `remise_tous_types_cartes` tinyint(1) NOT NULL DEFAULT 1,
  `aide_hebdo_active` tinyint(1) NOT NULL DEFAULT 0,
  `aide_hebdo_montant` int(10) UNSIGNED NOT NULL DEFAULT 5000,
  `aide_hebdo_carburant` int(10) UNSIGNED NOT NULL DEFAULT 3000,
  `aide_hebdo_credit_tel` int(10) UNSIGNED NOT NULL DEFAULT 2000,
  `aide_hebdo_tous_commerciaux` tinyint(1) NOT NULL DEFAULT 1,
  `contrat_tous_commerciaux` tinyint(1) NOT NULL DEFAULT 1,
  `contrat_emolument_forfait` int(10) UNSIGNED NOT NULL DEFAULT 50000,
  `contrat_forfait_communication` int(10) UNSIGNED NOT NULL DEFAULT 2000,
  `contrat_forfait_deplacement` int(10) UNSIGNED NOT NULL DEFAULT 3000,
  `contrat_representant_nom` varchar(191) NOT NULL DEFAULT 'Yaya H DIALLO',
  `contrat_lieu_signature` varchar(191) NOT NULL DEFAULT 'Bamako',
  `contrat_clause_libre` text DEFAULT NULL,
  `contrat_publie_at` timestamp NULL DEFAULT NULL,
  `actif` tinyint(1) NOT NULL DEFAULT 1,
  `statut` varchar(20) NOT NULL DEFAULT 'programmee',
  `toutes_agences` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `campagnes`
--

INSERT INTO `campagnes` (`id`, `nom`, `date_debut`, `date_fin`, `prime_meilleur_vendeur`, `remise_pourcentage`, `remise_tous_types_cartes`, `aide_hebdo_active`, `aide_hebdo_montant`, `aide_hebdo_carburant`, `aide_hebdo_credit_tel`, `aide_hebdo_tous_commerciaux`, `contrat_tous_commerciaux`, `contrat_emolument_forfait`, `contrat_forfait_communication`, `contrat_forfait_deplacement`, `contrat_representant_nom`, `contrat_lieu_signature`, `contrat_clause_libre`, `contrat_publie_at`, `actif`, `statut`, `toutes_agences`, `created_at`, `updated_at`) VALUES
(5, 'Campagne Avril 2026', '2026-03-31', '2026-04-30', 25000, NULL, 0, 1, 5000, 3000, 2000, 1, 1, 5000, 2000, 3000, 'Yaya H DIALLO', 'Bamako', NULL, '2026-03-31 09:12:49', 0, 'terminee', 0, '2026-03-31 09:12:49', '2026-06-15 16:50:51'),
(6, 'Avril 2è vague', '2026-04-09', '2026-05-08', 25000, NULL, 1, 0, 5000, 3000, 2000, 0, 0, 50000, 2000, 3000, 'Yaya H DIALLO', 'Bamako', NULL, '2026-04-10 09:46:21', 0, 'terminee', 0, '2026-04-10 09:46:21', '2026-06-15 16:50:51'),
(8, 'Campagne Juin 2026', '2026-06-15', '2026-06-17', 25000, NULL, 0, 0, 5000, 3000, 2000, 0, 0, 50000, 2000, 3000, 'Yaya H DIALLO', 'Bamako', NULL, '2026-06-15 16:33:30', 1, 'en_cours', 0, '2026-06-15 16:33:30', '2026-06-15 16:50:51');

-- --------------------------------------------------------

--
-- Structure de la table `campagne_actions`
--

CREATE TABLE `campagne_actions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `campagne_id` bigint(20) UNSIGNED NOT NULL,
  `action` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `donnees_avant` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`donnees_avant`)),
  `donnees_apres` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`donnees_apres`)),
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `campagne_agence`
--

CREATE TABLE `campagne_agence` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `campagne_id` bigint(20) UNSIGNED NOT NULL,
  `agence_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `campagne_agence`
--

INSERT INTO `campagne_agence` (`id`, `campagne_id`, `agence_id`) VALUES
(13, 5, 2),
(14, 5, 3),
(15, 5, 4),
(16, 5, 5),
(17, 5, 6),
(18, 5, 7),
(19, 5, 8),
(20, 5, 9),
(21, 5, 10),
(22, 5, 11),
(23, 5, 12),
(24, 5, 13),
(25, 5, 15),
(26, 5, 16),
(27, 5, 17),
(28, 5, 18),
(30, 5, 21),
(31, 5, 22),
(32, 5, 23),
(33, 5, 24),
(34, 5, 25),
(35, 5, 26),
(36, 5, 27),
(37, 5, 28),
(38, 5, 29),
(39, 5, 30),
(40, 5, 31),
(44, 5, 46),
(12, 6, 19),
(1, 6, 32),
(3, 6, 34),
(4, 6, 35),
(5, 6, 36),
(6, 6, 37),
(7, 6, 38),
(8, 6, 39),
(43, 6, 40),
(11, 6, 42),
(41, 6, 43),
(42, 6, 44),
(58, 8, 2),
(64, 8, 5),
(67, 8, 6),
(47, 8, 7),
(66, 8, 8),
(50, 8, 9),
(45, 8, 15),
(56, 8, 16),
(61, 8, 21),
(69, 8, 22),
(74, 8, 24),
(72, 8, 26),
(70, 8, 31),
(51, 8, 32),
(52, 8, 33),
(60, 8, 34),
(54, 8, 35),
(46, 8, 36),
(63, 8, 37),
(73, 8, 42),
(48, 8, 47),
(49, 8, 48),
(53, 8, 49),
(55, 8, 50),
(57, 8, 51),
(59, 8, 52),
(62, 8, 53),
(65, 8, 54),
(68, 8, 55),
(71, 8, 56);

-- --------------------------------------------------------

--
-- Structure de la table `campagne_aide_beneficiaire`
--

CREATE TABLE `campagne_aide_beneficiaire` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `campagne_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `campagne_aide_versements`
--

CREATE TABLE `campagne_aide_versements` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `campagne_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `semaine_debut` date NOT NULL,
  `montant_carburant` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `montant_credit_tel` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `enregistre_par` bigint(20) UNSIGNED DEFAULT NULL,
  `accuse_at` timestamp NULL DEFAULT NULL,
  `accuse_commentaire` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `campagne_commercial_contrat`
--

CREATE TABLE `campagne_commercial_contrat` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `campagne_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `campagne_commercial_contrat`
--

INSERT INTO `campagne_commercial_contrat` (`id`, `campagne_id`, `user_id`, `created_at`, `updated_at`) VALUES
(30, 5, 10, '2026-03-31 09:12:49', '2026-03-31 09:12:49'),
(31, 5, 11, '2026-03-31 09:12:49', '2026-03-31 09:12:49'),
(33, 5, 13, '2026-03-31 09:12:49', '2026-03-31 09:12:49'),
(34, 5, 14, '2026-03-31 09:12:49', '2026-03-31 09:12:49'),
(35, 5, 15, '2026-03-31 09:12:49', '2026-03-31 09:12:49'),
(36, 5, 16, '2026-03-31 09:12:49', '2026-03-31 09:12:49'),
(37, 5, 17, '2026-03-31 09:12:49', '2026-03-31 09:12:49'),
(38, 5, 18, '2026-03-31 09:12:49', '2026-03-31 09:12:49'),
(39, 5, 19, '2026-03-31 09:12:49', '2026-03-31 09:12:49'),
(40, 5, 20, '2026-03-31 09:12:49', '2026-03-31 09:12:49'),
(41, 5, 21, '2026-03-31 09:12:49', '2026-03-31 09:12:49'),
(42, 5, 22, '2026-03-31 09:12:49', '2026-03-31 09:12:49'),
(43, 5, 23, '2026-03-31 09:12:49', '2026-03-31 09:12:49'),
(44, 5, 24, '2026-03-31 09:12:49', '2026-03-31 09:12:49'),
(45, 5, 25, '2026-03-31 09:12:49', '2026-03-31 09:12:49'),
(46, 5, 26, '2026-03-31 09:12:49', '2026-03-31 09:12:49'),
(48, 5, 28, '2026-03-31 09:12:49', '2026-03-31 09:12:49'),
(49, 5, 29, '2026-03-31 09:12:49', '2026-03-31 09:12:49'),
(50, 5, 30, '2026-03-31 09:12:49', '2026-03-31 09:12:49'),
(51, 5, 31, '2026-03-31 09:12:49', '2026-03-31 09:12:49'),
(52, 5, 32, '2026-03-31 09:12:49', '2026-03-31 09:12:49'),
(53, 5, 33, '2026-03-31 09:12:49', '2026-03-31 09:12:49'),
(54, 5, 34, '2026-03-31 09:12:49', '2026-03-31 09:12:49'),
(55, 5, 35, '2026-03-31 09:12:49', '2026-03-31 09:12:49'),
(56, 5, 36, '2026-03-31 09:12:49', '2026-03-31 09:12:49'),
(57, 5, 37, '2026-03-31 09:12:49', '2026-03-31 09:12:49'),
(58, 5, 38, '2026-03-31 09:12:49', '2026-03-31 09:12:49'),
(59, 5, 40, '2026-03-31 11:35:19', '2026-03-31 11:35:19'),
(60, 6, 41, '2026-04-10 09:46:21', '2026-04-10 09:46:21'),
(62, 6, 43, '2026-04-10 09:46:21', '2026-04-10 09:46:21'),
(63, 6, 44, '2026-04-10 09:46:21', '2026-04-10 09:46:21'),
(64, 6, 45, '2026-04-10 09:46:21', '2026-04-10 09:46:21'),
(65, 6, 46, '2026-04-10 09:46:21', '2026-04-10 09:46:21'),
(66, 6, 47, '2026-04-10 09:46:21', '2026-04-10 09:46:21'),
(67, 6, 48, '2026-04-10 09:46:21', '2026-04-10 09:46:21'),
(69, 6, 50, '2026-04-10 09:46:21', '2026-04-10 09:46:21'),
(70, 6, 51, '2026-04-10 09:46:21', '2026-04-10 09:46:21'),
(72, 6, 52, '2026-04-14 10:31:23', '2026-04-14 10:31:23'),
(73, 6, 54, '2026-04-14 10:31:23', '2026-04-14 10:31:23'),
(74, 6, 55, '2026-04-14 10:31:23', '2026-04-14 10:31:23'),
(76, 8, 10, '2026-06-15 16:33:30', '2026-06-15 16:33:30'),
(77, 8, 11, '2026-06-15 16:33:30', '2026-06-15 16:33:30'),
(78, 8, 40, '2026-06-15 16:33:30', '2026-06-15 16:33:30'),
(79, 8, 13, '2026-06-15 16:33:30', '2026-06-15 16:33:30'),
(80, 8, 14, '2026-06-15 16:33:30', '2026-06-15 16:33:30'),
(81, 8, 57, '2026-06-15 16:33:30', '2026-06-15 16:33:30'),
(82, 8, 58, '2026-06-15 16:33:30', '2026-06-15 16:33:30'),
(83, 8, 17, '2026-06-15 16:33:30', '2026-06-15 16:33:30'),
(84, 8, 18, '2026-06-15 16:33:30', '2026-06-15 16:33:30'),
(85, 8, 19, '2026-06-15 16:33:30', '2026-06-15 16:33:30'),
(86, 8, 59, '2026-06-15 16:33:30', '2026-06-15 16:33:30'),
(87, 8, 21, '2026-06-15 16:33:30', '2026-06-15 16:33:30'),
(88, 8, 60, '2026-06-15 16:33:30', '2026-06-15 16:33:30'),
(89, 8, 23, '2026-06-15 16:33:30', '2026-06-15 16:33:30'),
(90, 8, 55, '2026-06-15 16:33:30', '2026-06-15 16:33:30'),
(91, 8, 25, '2026-06-15 16:33:30', '2026-06-15 16:33:30'),
(92, 8, 22, '2026-06-15 16:33:30', '2026-06-15 16:33:30'),
(93, 8, 27, '2026-06-15 16:33:30', '2026-06-15 16:33:30'),
(94, 8, 45, '2026-06-15 16:33:30', '2026-06-15 16:33:30'),
(95, 8, 61, '2026-06-15 16:33:30', '2026-06-15 16:33:30'),
(96, 8, 62, '2026-06-15 16:33:30', '2026-06-15 16:33:30'),
(97, 8, 63, '2026-06-15 16:33:30', '2026-06-15 16:33:30'),
(98, 8, 47, '2026-06-15 16:33:30', '2026-06-15 16:33:30'),
(99, 8, 64, '2026-06-15 16:33:30', '2026-06-15 16:33:30'),
(100, 8, 29, '2026-06-15 16:33:30', '2026-06-15 16:33:30'),
(101, 8, 38, '2026-06-15 16:33:30', '2026-06-15 16:33:30'),
(102, 8, 35, '2026-06-15 16:33:30', '2026-06-15 16:33:30'),
(103, 8, 33, '2026-06-15 16:33:30', '2026-06-15 16:33:30'),
(104, 8, 51, '2026-06-15 16:33:30', '2026-06-15 16:33:30'),
(105, 8, 65, '2026-06-15 16:33:30', '2026-06-15 16:33:30');

-- --------------------------------------------------------

--
-- Structure de la table `campagne_contrat_articles`
--

CREATE TABLE `campagne_contrat_articles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `campagne_id` bigint(20) UNSIGNED NOT NULL,
  `sort_order` smallint(5) UNSIGNED NOT NULL DEFAULT 0,
  `titre` varchar(255) NOT NULL,
  `contenu` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `campagne_contrat_articles`
--

INSERT INTO `campagne_contrat_articles` (`id`, `campagne_id`, `sort_order`, `titre`, `contenu`, `created_at`, `updated_at`) VALUES
(17, 5, 0, 'Article 1 : Objet du contrat', 'Le présent contrat a pour objet de définir les conditions dans lesquelles la Prestataire s’engage à assurer, pour le compte de GDA, la commercialisation des cartes bancaires BDM SA dans le cadre d’une campagne pilotée par GDA en partenariat avec la Banque de Développement du Mali (BDM SA).', '2026-03-31 09:12:49', '2026-03-31 09:12:49'),
(18, 5, 1, 'Article 2 : Durée de la mission', 'La mission du Prestataire est conclue pour une durée déterminée correspondant à la période de la campagne telle qu’enregistrée dans l’application (dates de début et de fin), sauf résiliation anticipée dans les conditions prévues au présent contrat.', '2026-03-31 09:12:49', '2026-03-31 09:12:49'),
(19, 5, 2, 'Article 3 : Conditions d’exécution', 'La Prestataire s’engage notamment à :\n\n- Participer activement à la campagne de commercialisation des cartes BDM SA ;\n- Atteindre les objectifs de vente qui lui seront fixés en début de mission ;\n- Être disponible pendant les heures d’ouverture de la banque dans sa zone d’affectation ;\n- Transmettre chaque lundi au plus tard à 12h un rapport hebdomadaire d’activité ;\n- Intégrer et rester actif(ve) dans le groupe WhatsApp de coordination mis en place par GDA ;\n- Respecter l’éthique commerciale, l’image de marque de GDA et les consignes de la BDM SA.', '2026-03-31 09:12:49', '2026-03-31 09:12:49'),
(20, 5, 3, 'Article 5 : Matériel fourni', 'Un forfait téléphonique hebdomadaire peut être financé par GDA, pour permettre la transmission des rapports et la coordination des actions, selon les versements enregistrés dans l’application.\n\nLa Prestataire recevra de la BDM SA, pour les besoins de la campagne : un tee-shirt et une casquette de campagne, un argumentaire commercial et les outils nécessaires à la prospection.', '2026-03-31 09:12:49', '2026-03-31 09:12:49'),
(21, 5, 4, 'Article 6 : Statut du prestataire', 'La Prestataire intervient en toute indépendance, en tant que prestataire de services non salarié. Il n’existe entre les parties aucun lien de subordination, ni de relation de travail salarié.', '2026-03-31 09:12:49', '2026-03-31 09:12:49'),
(22, 5, 5, 'Article 7 : Résiliation', 'Le présent contrat pourra être résilié de plein droit par GDA, sans indemnité, en cas de : non-respect des obligations contractuelles ; résultats commerciaux manifestement insuffisants sans justification ; attitude contraire à l’éthique ou aux règles de la campagne. En cas de résiliation anticipée pour faute du Prestataire, aucun paiement ne sera exigible.', '2026-03-31 09:12:49', '2026-03-31 09:12:49'),
(23, 5, 6, 'Article 8 : Confidentialité', 'La Prestataire s’engage à garder confidentielles toutes les informations commerciales, stratégiques ou personnelles auxquelles il pourrait avoir accès dans le cadre de sa mission.', '2026-03-31 09:12:49', '2026-03-31 09:12:49'),
(24, 5, 7, 'Article 9 : Engagement de présence et reporting', 'La Prestataire s’engage à respecter les horaires de présence définis, à tenir un discours conforme aux éléments fournis, et à remonter toute difficulté rencontrée à GDA dans les plus brefs délais.', '2026-03-31 09:12:49', '2026-03-31 09:12:49'),
(25, 5, 8, 'Article 4', 'En contrepartie des prestations fournies, la Prestataire percevra de GDA un émolument forfaitaire de 50 000 FCFA TTC pour la durée totale de la mission.\r\no	Forfait Communication de : 2 000 Francs CFA\r\no	Forfait Deplacement de : 3 000 Francs CFA\r\no	Une prime de performance hebdomadaire de 25 000 FCFA sera attribuée au meilleur vendeur de la semaine, sur la base des rapports et résultats transmis.\r\n\r\nLe paiement interviendra en une seule fois à la fin de la campagne, après validation du rapport final et contrôle des résultats.', '2026-03-31 10:08:35', '2026-03-31 10:08:35'),
(26, 6, 0, 'Article 1 : Objet du contrat', 'Le présent contrat a pour objet de définir les conditions dans lesquelles la Prestataire s’engage à assurer, pour le compte de GDA, la commercialisation des cartes bancaires BDM SA dans le cadre d’une campagne pilotée par GDA en partenariat avec la Banque de Développement du Mali (BDM SA).', '2026-04-10 09:46:21', '2026-04-10 09:46:21'),
(27, 6, 1, 'Article 2 : Durée de la mission', 'La mission du Prestataire est conclue pour une durée déterminée correspondant à la période de la campagne telle qu’enregistrée dans l’application (dates de début et de fin), sauf résiliation anticipée dans les conditions prévues au présent contrat.', '2026-04-10 09:46:21', '2026-04-10 09:46:21'),
(28, 6, 2, 'Article 3 : Conditions d’exécution', 'La Prestataire s’engage notamment à :\n\n- Participer activement à la campagne de commercialisation des cartes BDM SA ;\n- Atteindre les objectifs de vente qui lui seront fixés en début de mission ;\n- Être disponible pendant les heures d’ouverture de la banque dans sa zone d’affectation ;\n- Transmettre chaque lundi au plus tard à 12h un rapport hebdomadaire d’activité ;\n- Intégrer et rester actif(ve) dans le groupe WhatsApp de coordination mis en place par GDA ;\n- Respecter l’éthique commerciale, l’image de marque de GDA et les consignes de la BDM SA.', '2026-04-10 09:46:21', '2026-04-10 09:46:21'),
(29, 6, 3, 'Article 5 : Matériel fourni', 'Un forfait téléphonique hebdomadaire peut être financé par GDA, pour permettre la transmission des rapports et la coordination des actions, selon les versements enregistrés dans l’application.\n\nLa Prestataire recevra de la BDM SA, pour les besoins de la campagne : un tee-shirt et une casquette de campagne, un argumentaire commercial et les outils nécessaires à la prospection.', '2026-04-10 09:46:21', '2026-04-10 09:46:21'),
(30, 6, 4, 'Article 6 : Statut du prestataire', 'La Prestataire intervient en toute indépendance, en tant que prestataire de services non salarié. Il n’existe entre les parties aucun lien de subordination, ni de relation de travail salarié.', '2026-04-10 09:46:21', '2026-04-10 09:46:21'),
(31, 6, 5, 'Article 7 : Résiliation', 'Le présent contrat pourra être résilié de plein droit par GDA, sans indemnité, en cas de : non-respect des obligations contractuelles ; résultats commerciaux manifestement insuffisants sans justification ; attitude contraire à l’éthique ou aux règles de la campagne. En cas de résiliation anticipée pour faute du Prestataire, aucun paiement ne sera exigible.', '2026-04-10 09:46:21', '2026-04-10 09:46:21'),
(32, 6, 6, 'Article 8 : Confidentialité', 'La Prestataire s’engage à garder confidentielles toutes les informations commerciales, stratégiques ou personnelles auxquelles il pourrait avoir accès dans le cadre de sa mission.', '2026-04-10 09:46:21', '2026-04-10 09:46:21'),
(33, 6, 7, 'Article 9 : Engagement de présence et reporting', 'La Prestataire s’engage à respecter les horaires de présence définis, à tenir un discours conforme aux éléments fournis, et à remonter toute difficulté rencontrée à GDA dans les plus brefs délais.', '2026-04-10 09:46:21', '2026-04-10 09:46:21'),
(42, 8, 0, 'Article 1 : Objet du contrat', 'Le présent contrat a pour objet de définir les conditions dans lesquelles la Prestataire s’engage à assurer, pour le compte de GDA, la commercialisation des cartes bancaires BDM SA dans le cadre d’une campagne pilotée par GDA en partenariat avec la Banque de Développement du Mali (BDM SA).', '2026-06-15 16:33:30', '2026-06-15 16:33:30'),
(43, 8, 1, 'Article 2 : Durée de la mission', 'La mission du Prestataire est conclue pour une durée déterminée correspondant à la période de la campagne telle qu’enregistrée dans l’application (dates de début et de fin), sauf résiliation anticipée dans les conditions prévues au présent contrat.', '2026-06-15 16:33:30', '2026-06-15 16:33:30'),
(44, 8, 2, 'Article 3 : Conditions d’exécution', 'La Prestataire s’engage notamment à :\n\n- Participer activement à la campagne de commercialisation des cartes BDM SA ;\n- Atteindre les objectifs de vente qui lui seront fixés en début de mission ;\n- Être disponible pendant les heures d’ouverture de la banque dans sa zone d’affectation ;\n- Transmettre chaque lundi au plus tard à 12h un rapport hebdomadaire d’activité ;\n- Intégrer et rester actif(ve) dans le groupe WhatsApp de coordination mis en place par GDA ;\n- Respecter l’éthique commerciale, l’image de marque de GDA et les consignes de la BDM SA.', '2026-06-15 16:33:30', '2026-06-15 16:33:30'),
(45, 8, 3, 'Article 5 : Matériel fourni', 'Un forfait téléphonique hebdomadaire peut être financé par GDA, pour permettre la transmission des rapports et la coordination des actions, selon les versements enregistrés dans l’application.\n\nLa Prestataire recevra de la BDM SA, pour les besoins de la campagne : un tee-shirt et une casquette de campagne, un argumentaire commercial et les outils nécessaires à la prospection.', '2026-06-15 16:33:30', '2026-06-15 16:33:30'),
(46, 8, 4, 'Article 6 : Statut du prestataire', 'La Prestataire intervient en toute indépendance, en tant que prestataire de services non salarié. Il n’existe entre les parties aucun lien de subordination, ni de relation de travail salarié.', '2026-06-15 16:33:30', '2026-06-15 16:33:30'),
(47, 8, 5, 'Article 7 : Résiliation', 'Le présent contrat pourra être résilié de plein droit par GDA, sans indemnité, en cas de : non-respect des obligations contractuelles ; résultats commerciaux manifestement insuffisants sans justification ; attitude contraire à l’éthique ou aux règles de la campagne. En cas de résiliation anticipée pour faute du Prestataire, aucun paiement ne sera exigible.', '2026-06-15 16:33:30', '2026-06-15 16:33:30'),
(48, 8, 6, 'Article 8 : Confidentialité', 'La Prestataire s’engage à garder confidentielles toutes les informations commerciales, stratégiques ou personnelles auxquelles il pourrait avoir accès dans le cadre de sa mission.', '2026-06-15 16:33:30', '2026-06-15 16:33:30'),
(49, 8, 7, 'Article 9 : Engagement de présence et reporting', 'La Prestataire s’engage à respecter les horaires de présence définis, à tenir un discours conforme aux éléments fournis, et à remonter toute difficulté rencontrée à GDA dans les plus brefs délais.', '2026-06-15 16:33:30', '2026-06-15 16:33:30');

-- --------------------------------------------------------

--
-- Structure de la table `campagne_remise_type_carte`
--

CREATE TABLE `campagne_remise_type_carte` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `campagne_id` bigint(20) UNSIGNED NOT NULL,
  `type_carte_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `clients`
--

CREATE TABLE `clients` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `type_carte_id` bigint(20) UNSIGNED NOT NULL,
  `prenom` varchar(255) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `telephone` varchar(20) DEFAULT NULL,
  `ville` varchar(100) DEFAULT NULL,
  `quartier` varchar(100) DEFAULT NULL,
  `statut_carte` enum('vendue','activée','en_erreur') NOT NULL DEFAULT 'vendue',
  `carte_identite` varchar(255) DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `clients`
--

INSERT INTO `clients` (`id`, `type_carte_id`, `prenom`, `nom`, `telephone`, `ville`, `quartier`, `statut_carte`, `carte_identite`, `user_id`, `created_at`, `updated_at`) VALUES
(5, 3, 'Yacouba', 'Doumbia', '76760752', 'Bamako', 'ACI', 'vendue', 'cartes-identite/yUe17pwtRLU4I0ZFvAlBVhMaw8mJpFIhzsudHoP5.jpg', 11, '2026-03-31 11:32:49', '2026-03-31 11:32:49'),
(6, 2, 'IBrahima', 'Doumbia', '77473798', 'Bamako', 'Attbougou près des Eaux et forêt', 'vendue', NULL, 10, '2026-03-31 12:15:54', '2026-03-31 12:15:54'),
(7, 2, 'Tahirou', 'Ouattara', '77508101', 'Bamako', 'Magnambougou', 'vendue', NULL, 10, '2026-03-31 12:24:41', '2026-03-31 12:24:41'),
(8, 2, 'Bakary', 'TRAORÉ', '76224108', 'Bamako', 'Mali', 'vendue', NULL, 10, '2026-03-31 13:14:26', '2026-03-31 13:14:26'),
(9, 2, 'Bareima', 'Tiocary', '70412414', 'Bamako', 'Diatoula', 'vendue', NULL, 10, '2026-03-31 13:22:47', '2026-03-31 13:22:47'),
(10, 2, 'Sidi', 'Diarra', '74052203', 'Koulikoro', 'Koulikoro ba', 'vendue', 'cartes-identite/QFzLzGDrnh4oVGksrnPnD5WfQT4RJXrYvLsKK8OA.pdf', 33, '2026-03-31 13:38:29', '2026-03-31 13:38:29'),
(11, 2, 'Adama', 'Macalou', '79699858', 'Koulikoro', 'Koulikoro Gare', 'vendue', 'cartes-identite/a2DcmXB6F7taD4SO0vWZOfUiXfTBz4qcDJpR61Gy.pdf', 33, '2026-03-31 13:39:58', '2026-03-31 13:39:58'),
(12, 3, 'Amedjika', 'Komi', '70711375', 'Koulikoro', 'Koulikoro souban', 'vendue', 'cartes-identite/t6omUGnGgcHVpCxlKbQ9qcj60q5sBBLBuFhi5753.pdf', 33, '2026-03-31 13:41:21', '2026-03-31 13:41:21'),
(13, 3, 'Tidiani', 'Dembélé', '76066641', 'Koulikoro', 'Koulikoro ba', 'vendue', 'cartes-identite/BrT5eDgfxxOmlKdQ4ybFylReZqvi9ejRH1qxoJH9.pdf', 33, '2026-03-31 13:52:00', '2026-03-31 13:52:00'),
(14, 2, 'Aminata', 'Coulibaly', '76382984', 'Ségou', 'Pelengana sud', 'vendue', NULL, 30, '2026-03-31 13:52:39', '2026-03-31 13:52:39'),
(15, 2, 'Mamary', 'Coulibaly', '85001738', 'Koulikoro', 'Attbougou', 'vendue', 'cartes-identite/wy78rDnQGCTnxjQEYyKKy8sAFIA77wLS19aT6ZSh.pdf', 33, '2026-03-31 13:53:27', '2026-03-31 13:53:27'),
(16, 4, 'Boubacar', 'Guindo', '66176601', 'Koulikoro', 'Koulikoro plateau II', 'vendue', 'cartes-identite/GDZiqk8XPHVU6GLxN2CfVFEwIiFlHLuMpGTbz8gk.pdf', 33, '2026-03-31 13:55:11', '2026-03-31 13:55:11'),
(17, 2, 'Mohamed', 'Fomba', '75189448', NULL, NULL, 'vendue', NULL, 13, '2026-03-31 14:20:28', '2026-03-31 14:20:28'),
(18, 2, 'Alassane Ibrahim', 'Aldjoumah', '76684536', 'Bamako', 'Niamakoro cité', 'vendue', NULL, 10, '2026-03-31 14:33:41', '2026-03-31 14:33:41'),
(20, 2, 'Alpha', 'Koureissi', '72177134', 'Segou', 'Cite comatex', 'vendue', NULL, 30, '2026-03-31 14:52:24', '2026-03-31 14:52:24'),
(21, 2, 'Ibrahim', 'Maïga', '76359403', 'Ségou', 'Bagadadji', 'vendue', NULL, 30, '2026-03-31 14:53:43', '2026-03-31 14:53:43'),
(22, 3, 'Mamadou', 'Dia', '77938480', 'Segou', 'Darsalam', 'vendue', NULL, 30, '2026-03-31 14:54:38', '2026-03-31 14:54:38'),
(23, 2, 'Baba', 'Sidibé', '66282231', 'Bamako', 'Bamako Koulouba', 'vendue', 'cartes-identite/rDjHZxfROSobGI2yodbIrJju5qNeqpxzNc07wB8v.pdf', 33, '2026-03-31 15:02:30', '2026-03-31 15:02:30'),
(24, 3, 'Oumar', 'Sy', '70349055', 'Bamako', 'Badalabougou commune 5', 'vendue', 'cartes-identite/NtDJGB9XAXrNxgEmwlJpRn29JXX1dUIWJ7vtoFl2.pdf', 26, '2026-03-31 15:25:13', '2026-03-31 15:25:13'),
(25, 2, 'Fatoumata', 'Sidibe', '70376621', 'Bamako', 'Sebenicoro', 'vendue', NULL, 18, '2026-03-31 15:34:33', '2026-03-31 15:34:33'),
(26, 3, 'Fatoumata', 'Diallo', '76423072', 'Bamako', 'Badialan 3', 'vendue', NULL, 26, '2026-03-31 15:38:41', '2026-03-31 15:38:41'),
(27, 2, 'Oumarou', 'Bah', '74052438', 'San', 'Santoro', 'vendue', NULL, 31, '2026-03-31 16:26:10', '2026-03-31 16:26:10'),
(28, 4, 'Fatimata', 'Sanogo', '76560355', 'Bko', 'Moribabougou', 'vendue', NULL, 17, '2026-03-31 16:56:04', '2026-03-31 16:56:04'),
(29, 2, 'Makan', 'Soumounou', '76497776', 'Bko', 'Torokorobougou', 'vendue', NULL, 17, '2026-03-31 16:57:29', '2026-03-31 16:57:29'),
(30, 2, 'Oumarou', 'Kanté', '75751464', 'Bko', 'Lafiabougou', 'vendue', NULL, 17, '2026-03-31 16:58:24', '2026-03-31 16:58:24'),
(31, 4, 'Doumbia Aminata', 'Dabo', '89896789', NULL, NULL, 'vendue', NULL, 23, '2026-03-31 17:08:22', '2026-03-31 17:08:22'),
(32, 2, 'Ibrahim', 'Bah', '93428101', NULL, NULL, 'vendue', NULL, 23, '2026-03-31 17:09:49', '2026-03-31 17:09:49'),
(33, 3, 'Oumar', 'TRAORÉ', '78789466', 'Bamako', NULL, 'vendue', 'cartes-identite/CwgQI2hobZhh6bPkeM57acQVtptSWA8ZcRE2X2Si.jpg', 20, '2026-04-01 08:27:46', '2026-04-01 08:27:46'),
(34, 3, 'Aoua', 'Koné', '76269306', 'Bamako', 'Baco Djicoroni', 'vendue', NULL, 16, '2026-04-01 08:46:21', '2026-04-01 08:46:21'),
(35, 4, 'Fatoumata', 'Traoré', '72303009', NULL, 'Magnabougou faso kanou', 'vendue', NULL, 14, '2026-04-01 11:49:17', '2026-04-01 11:49:17'),
(36, 2, 'Toumani', 'Diakité', '94154206', 'Bamako', 'Baco Djicoroni', 'vendue', NULL, 16, '2026-04-01 11:54:36', '2026-04-01 11:54:36'),
(37, 8, 'Mahamet', 'Konate', '76044387', 'Bamako', 'Kalaban Coro plateau', 'vendue', NULL, 27, '2026-04-01 11:54:56', '2026-04-01 11:54:56'),
(38, 8, 'Sianwa', 'Sanogo', '90415805', 'Bamako', 'Tieguena', 'vendue', NULL, 10, '2026-04-01 12:13:33', '2026-04-01 12:13:33'),
(39, 2, 'Komakan', 'Fofana', '83286979', 'Bamako', 'Marseille', 'vendue', NULL, 10, '2026-04-01 12:15:39', '2026-04-01 12:15:39'),
(40, 8, 'Aïcha', 'Sidibé', '74599887', 'Bamako', 'N’tabacoro', 'vendue', NULL, 10, '2026-04-01 12:19:21', '2026-04-01 12:19:21'),
(41, 8, 'Youssouf', 'SYLLA', NULL, NULL, 'Daoudabougou', 'vendue', NULL, 14, '2026-04-01 12:41:29', '2026-04-01 12:41:29'),
(42, 2, 'Abdoulaye dit  harouna', 'Keita', '75889599', 'Bamako', 'Golf', 'vendue', NULL, 16, '2026-04-01 13:14:44', '2026-04-01 14:42:45'),
(43, 2, 'Idissa gueDiouna', 'Cissouma', '94640233', 'Bamako', 'Baco Djicoroni', 'vendue', NULL, 16, '2026-04-01 13:15:46', '2026-04-01 13:15:46'),
(44, 2, 'KessayAime', 'Konate', '76520872', 'Dioïla', 'Tripanobougou', 'vendue', NULL, 34, '2026-04-01 13:21:13', '2026-04-02 14:15:34'),
(45, 3, 'Mamadou', 'SOGODOGO', '78134334', 'Bamako', NULL, 'vendue', 'cartes-identite/J52KNUFs3v3lb0vR69UOIidgoCdtlrhbjSVDl68a.jpg', 20, '2026-04-01 13:22:52', '2026-04-01 13:22:52'),
(46, 3, 'Drissa', 'TRAORÉ', NULL, 'Bamako', NULL, 'vendue', NULL, 20, '2026-04-01 13:26:08', '2026-04-01 13:26:08'),
(47, 2, 'Zoumana', 'Konaté', '76220898', 'Bamako', 'Niaréla', 'vendue', NULL, 19, '2026-04-01 13:43:37', '2026-04-01 13:43:37'),
(48, 2, 'Fatoumata', 'Sylla', '71747208', 'Bamako', 'Golf', 'vendue', NULL, 16, '2026-04-01 14:41:06', '2026-04-01 14:42:23'),
(49, 8, 'Tiebile', 'Tirera', '73497646', 'Bamako', 'Kabala', 'vendue', NULL, 27, '2026-04-01 14:59:52', '2026-04-01 15:00:48'),
(50, 3, 'Kassoum', 'Sanogo', '76415429', NULL, 'Daoudabougou', 'vendue', NULL, 14, '2026-04-01 15:02:05', '2026-04-01 15:02:05'),
(51, 2, 'Seydou', 'Bocoum', '94084218', 'Koulikoro', 'Koulikoro', 'vendue', 'cartes-identite/bhh4NUNJ47amWsDyd6vQ8berXvHiYjcBTLFRAb8z.pdf', 33, '2026-04-01 15:07:08', '2026-04-01 15:07:08'),
(52, 2, 'Habibatou Oumar', 'Dia', '76177394', 'Koulikoro', 'Koulikoro', 'vendue', 'cartes-identite/GmLh6GJRCnRDaZeBSBJDLfRAI7MKxoujeSx5GNe1.pdf', 33, '2026-04-01 15:09:52', '2026-04-01 15:09:52'),
(53, 3, 'Aminata', 'SY', '73777664', 'Bamako', NULL, 'vendue', 'cartes-identite/En50ORRmhzz0XYS3FOY6apb3L4EA68QglBq1IivI.jpg', 20, '2026-04-01 15:12:29', '2026-04-01 15:12:29'),
(54, 2, 'Senou', 'Keïta', '75450109', 'Koulikoro', 'Koulikoro', 'vendue', 'cartes-identite/Z7cWkoXkxEH6rKlXyNo4bXM9MS7KaeloEITcSNYZ.pdf', 33, '2026-04-01 15:13:15', '2026-04-01 15:13:15'),
(55, 2, 'Balakissa kissima', 'Traore', '75182737', 'Ségou', 'Medine', 'vendue', NULL, 30, '2026-04-01 15:14:03', '2026-04-01 15:14:03'),
(56, 3, 'Modibo', 'Lah', '75223747', 'Ségou', 'Sido konin-coura', 'vendue', NULL, 30, '2026-04-01 15:15:41', '2026-04-01 15:15:41'),
(57, 3, 'Mahamadou', 'Traoré', '71384207', 'Koulikoro', 'Koulikoro', 'vendue', 'cartes-identite/eXN5ItAYfbZTSMNGhSXWh3gVuidlUQ9lSyq6iHwq.pdf', 33, '2026-04-01 15:15:56', '2026-04-01 15:15:56'),
(58, 2, 'Gisèle', 'Coulibaly', '77060683', 'Bamako', 'Bamako', 'vendue', 'cartes-identite/LrDCXlo7W1Yhd8JDMqWviTah3Wdyz1ciFtY9pbJu.pdf', 33, '2026-04-01 15:18:39', '2026-04-01 15:18:39'),
(59, 2, 'Sidiki', 'Dembélé', '76591255', 'Koulikoro', 'Koulikoro', 'vendue', 'cartes-identite/jxO4C3Sjyf0bhqwtbzRIeSSibb6SSUQrJqrxZh4H.pdf', 33, '2026-04-01 15:20:25', '2026-04-01 15:20:25'),
(60, 6, 'Abibatou Y', 'Sidibe', '64201598', 'Segou', 'Sido sonikoura', 'vendue', 'cartes-identite/ByeDqHgn3YjiCQfjbz6DTJA8xxEzqgIuUf4YgXnC.pdf', 29, '2026-04-01 15:22:44', '2026-04-01 15:22:44'),
(61, 2, 'AGAICHATOU', 'TOURE', '94245556', 'Koulikoro', 'Koulikoro', 'vendue', 'cartes-identite/d415LJb5N6rAznm9kZHJhSeNuzNInTCUE6kWv9Bp.pdf', 33, '2026-04-01 15:25:59', '2026-04-01 15:25:59'),
(62, 6, 'Nouhoum', 'Lah', '79407278', 'Segou', 'Lafiabougou', 'vendue', 'cartes-identite/toPmCVXshZDF5BndnhdC5Gi7Wl0PYSBmNFHCqNB1.pdf', 29, '2026-04-01 15:28:21', '2026-04-01 15:28:21'),
(63, 7, 'Ibrahim', 'Haidara', '77204644', 'Segou', 'Markala kirango', 'vendue', 'cartes-identite/OKTQ517WzoUu5hAJUOzfaGj4ej2DZfN7uG6r0V8s.pdf', 29, '2026-04-01 15:48:57', '2026-04-01 15:48:57'),
(64, 7, 'Fousseni', 'Ouattara', '91965553', 'Sikasso', 'Wayerma 2', 'vendue', 'cartes-identite/KBpn9frr5M1gruZT31KPwnHHbLEKchiqdTegkJbP.pdf', 29, '2026-04-01 15:50:43', '2026-04-01 15:50:43'),
(65, 2, 'Abdoulaye', 'Haïdara', '76352385', NULL, NULL, 'vendue', NULL, 23, '2026-04-01 16:23:35', '2026-04-01 16:23:35'),
(66, 8, 'M\'pamana', 'Kouma', NULL, NULL, NULL, 'vendue', NULL, 18, '2026-04-01 16:39:40', '2026-04-01 16:39:40'),
(67, 8, 'Sidi', 'Sissoko', NULL, NULL, NULL, 'vendue', NULL, 18, '2026-04-01 16:41:05', '2026-04-01 16:41:05'),
(68, 7, 'Dakouo', 'Sangare', NULL, 'Bamako', 'Quartier mali', 'vendue', NULL, 18, '2026-04-01 16:43:12', '2026-04-01 16:43:12'),
(69, 7, 'Sidi', 'Sissoko', NULL, 'Bamako', 'Lafiabougou', 'vendue', NULL, 18, '2026-04-01 16:43:57', '2026-04-01 16:43:57'),
(70, 7, 'Adama', 'Keita', NULL, 'Bamako', 'Médina coura', 'vendue', NULL, 18, '2026-04-01 16:45:21', '2026-04-01 16:45:21'),
(71, 7, 'Mamadou', 'Soumounou', NULL, 'Bamako', 'Hamdallaye', 'vendue', NULL, 18, '2026-04-01 16:47:04', '2026-04-01 16:47:04'),
(72, 2, 'Kaou', 'Demba', '76192515', 'Bamako', 'Badialan 2', 'vendue', 'cartes-identite/t4mHx4vHegs22vhrQtH3SOBG1h0r5bBdEJTvUZxY.pdf', 26, '2026-04-01 16:48:39', '2026-04-01 16:48:39'),
(73, 3, 'Bakary', 'Sacko', '90559665', 'Bamako', 'Banankabougou', 'vendue', NULL, 26, '2026-04-01 16:51:19', '2026-04-01 16:51:19'),
(74, 9, 'Oumar moriba', 'Diallo', NULL, 'Bamako', 'Sebenicoro', 'vendue', NULL, 18, '2026-04-01 16:51:32', '2026-04-01 16:51:32'),
(75, 7, 'Dakouo', 'Sangare', NULL, 'Bamako', 'Quartier mali', 'vendue', NULL, 18, '2026-04-01 16:52:56', '2026-04-01 16:52:56'),
(76, 11, 'Samba', 'Diallo', NULL, 'Bamako', 'Aci 2000', 'vendue', NULL, 18, '2026-04-01 16:54:59', '2026-04-01 16:54:59'),
(77, 8, 'Sekou', 'Diane', '90824317', 'Bamako', 'Aci', 'vendue', 'cartes-identite/vJqBYPwE2R0UAceysRe85FePrA4Z5VgBkOPj9U24.jpg', 11, '2026-04-01 18:14:13', '2026-04-01 18:14:13'),
(78, 6, 'Cheick Oumar', 'Touré', '73427030', 'Bamako', 'Faladie', 'vendue', 'cartes-identite/OI8eAUUekdpPgoqXwVPrWJpxJa0jfknkuiJlm7mV.jpg', 11, '2026-04-02 07:04:19', '2026-04-02 07:04:19'),
(79, 7, 'Bocar', 'Dembele', '76353410', 'Bamako', 'Nimakoro', 'vendue', 'cartes-identite/Bg7y0vQSOZfne4NTbJYWHDFpH0zPEjT1JMOzLDYS.jpg', 11, '2026-04-02 07:06:43', '2026-04-02 07:06:43'),
(81, 8, 'Camara', 'Oumar', '66714675', 'Bamako', 'Golf', 'vendue', NULL, 16, '2026-04-02 07:22:39', '2026-04-02 07:22:39'),
(82, 2, 'Aboubacar', 'TRAORE', NULL, NULL, NULL, 'vendue', NULL, 13, '2026-04-02 07:25:50', '2026-04-02 07:25:50'),
(83, 8, 'Veronique', 'Goïta', '79230111', 'Bamako', 'Balaban coro kouloubleni', 'vendue', NULL, 16, '2026-04-02 07:45:09', '2026-04-02 07:45:09'),
(84, 9, 'Ouattara', 'Diam', '74586111', 'Bamako', 'Lafiabougou', 'vendue', 'cartes-identite/4HB4gHDzkRQmzcsLTny7nfRU99E8S57ld0evEzwF.jpg', 11, '2026-04-02 07:53:06', '2026-04-02 07:53:06'),
(85, 10, 'Abdoulaye', 'Camara', '76289142', 'Bamako', 'Hamdallaye', 'vendue', 'cartes-identite/fyTpAhkzKp3UDqhgqoQptmNu0ifkDwJL5iCilfJk.jpg', 11, '2026-04-02 08:14:35', '2026-04-02 08:14:35'),
(86, 10, 'Ibrahima', 'Kaba', '79428451', 'Bamako', 'Djicoroni', 'vendue', 'cartes-identite/0Yhp9OvtnS1RcgnAIDk5bOl3ESjn4InXQEKsUB1p.jpg', 11, '2026-04-02 08:29:12', '2026-04-02 08:29:12'),
(87, 8, 'Abdoulaye', 'Diallo', '73379390', 'Bamako', 'Djicoroni', 'vendue', 'cartes-identite/SRq1P6wSDhrk4zphjCn5DZ57Ge7mSCSsi1vHI54y.jpg', 11, '2026-04-02 08:48:48', '2026-04-02 08:48:48'),
(88, 3, 'Konimba', 'Diarra', '75152629', 'San', 'Santoro', 'vendue', 'cartes-identite/WJoRlGWs3Jb93dfOHhxMxoacElexBsqm9msOY9kE.jpg', 31, '2026-04-02 10:34:08', '2026-04-02 10:34:08'),
(89, 7, 'Sirafoune', 'Simpara', '76302221', 'Bamako', 'Kalanba', 'vendue', 'cartes-identite/QHoygtLvV2tNEpFXzzcI47nkPmhi0jlf7pW42D7E.jpg', 11, '2026-04-02 11:05:26', '2026-04-02 11:05:26'),
(90, 9, 'Foussenou', 'Sacko', '72727543', 'Bamako', 'Aci 200', 'vendue', 'cartes-identite/sPUo59UJllXVb3ouqxVmibkdbREdxdLzeibcPQVS.jpg', 11, '2026-04-02 11:25:49', '2026-04-02 11:25:49'),
(91, 3, 'Siaka', 'Daou', '76 67 47 47', 'Koutiala', NULL, 'vendue', 'cartes-identite/xGecVMZbuWCnbEy5vUpPlou9tJW2dQBFu4JCCc7k.jpg', 31, '2026-04-02 11:31:38', '2026-04-02 11:31:38'),
(92, 8, 'Madou', 'Pléa', '75621611', 'San', 'Médine', 'vendue', 'cartes-identite/xsMrG59rDGNCe2OxGfWGQIIuCMwj5sh6UgExMxBh.pdf', 31, '2026-04-02 11:55:37', '2026-04-02 11:55:37'),
(93, 8, 'Seydou', 'TRAORE', '73160854', 'San', 'Lafiabougou', 'vendue', 'cartes-identite/Bcg76mE4Im6d2eXnduQbyUBhfeaT3QAndvC5sqHW.pdf', 31, '2026-04-02 12:03:10', '2026-04-02 12:03:10'),
(94, 7, 'Bayo', 'Seydou', '64696931', 'San', 'Médine', 'vendue', 'cartes-identite/ozUWxbuXKDNyfQR8twhoKT8qdRRgzFeCwEdAvtaZ.pdf', 31, '2026-04-02 12:11:17', '2026-04-02 12:11:17'),
(95, 3, 'Abdrahamane', 'Kamissoko', NULL, 'Bamako', 'Aci 2000', 'vendue', NULL, 27, '2026-04-02 12:50:15', '2026-04-02 12:50:15'),
(96, 2, 'Diakaridia', 'Traoré', '76223739', 'Bamako', 'Niamana', 'vendue', NULL, 10, '2026-04-02 13:47:20', '2026-04-02 13:47:20'),
(97, 3, 'Diakaridia', 'Traore', '76223739', 'Bamako', 'Niamana', 'vendue', NULL, 10, '2026-04-02 13:48:52', '2026-04-02 13:48:52'),
(98, 2, 'Aboubacar Sidiki', 'Kouyate', '77526900', 'Bamako', 'Niamana', 'vendue', NULL, 10, '2026-04-02 13:49:54', '2026-04-02 13:49:54'),
(99, 7, 'Bina', 'Tangara', '74169926', 'Dioïla', 'Tripanobougou', 'vendue', NULL, 34, '2026-04-02 13:59:57', '2026-04-02 13:59:57'),
(100, 6, 'Gnédiè', 'Coulibaly', '74130390', 'Dioïla', NULL, 'vendue', NULL, 34, '2026-04-02 14:01:40', '2026-04-02 14:01:40'),
(101, 6, 'Seydou', 'Koné', '79073590', 'Dioïla', NULL, 'vendue', NULL, 34, '2026-04-02 14:03:13', '2026-04-02 14:03:13'),
(102, 6, 'Koumaré', 'Daouda', '92622324', 'Dioïla', 'Socoura', 'vendue', NULL, 34, '2026-04-02 14:04:15', '2026-04-02 14:04:15'),
(103, 8, 'N’djicoura', 'Diarra', '79436192', 'Dioïla', NULL, 'vendue', NULL, 34, '2026-04-02 14:05:41', '2026-04-02 14:05:41'),
(104, 3, 'Aly', 'Djiguiba', '75163744', 'Koulikoro', 'Koulikoro', 'vendue', 'cartes-identite/nNmwI0N7OA5WCGDQG7E93QwTQkfsEl0rvPgoMDJ9.pdf', 33, '2026-04-02 14:16:14', '2026-04-02 14:16:14'),
(105, 2, 'Marietou', 'Bathily', '76230322', 'Koulikoro', 'Koulikoro', 'vendue', 'cartes-identite/WwVx08M3IJx9v9xybhzEMkIEnuaMzU2usU8cnaFa.pdf', 33, '2026-04-02 14:17:44', '2026-04-02 14:17:44'),
(106, 2, 'Mohamed', 'Diarra', '79049954', 'Koulikoro', 'Koulikoro', 'vendue', 'cartes-identite/HfS78YjGKRFO2V1DRGLtMrkWfScljunMQKIZSpuo.pdf', 33, '2026-04-02 14:18:31', '2026-04-02 14:18:31'),
(107, 3, 'Adjaratou', 'SANOGO', '79253754', 'Bamako', NULL, 'vendue', 'cartes-identite/W13uVJjuwo2R6gFMlfAbZTS8m6a0x0Dyxx7zbWVi.jpg', 20, '2026-04-02 14:39:18', '2026-04-02 14:39:18'),
(108, 2, 'Abdoulaye', 'Simpara', '74040856', 'Bamako', 'Bozola', 'vendue', NULL, 19, '2026-04-02 15:04:52', '2026-04-02 15:04:52'),
(109, 4, 'Mamadou dit Bagname', 'Doucoure', '77772177', 'Bamako', 'Cite du Niger', 'vendue', NULL, 19, '2026-04-02 15:06:19', '2026-04-02 15:06:19'),
(110, 7, 'Amadou', 'Niaré', '79483931', 'Bamako', 'Sébénikoro', 'vendue', NULL, 19, '2026-04-02 15:07:16', '2026-04-02 15:07:16'),
(111, 7, 'Moussa', 'DIATAKO', '76809920', 'Bamako', NULL, 'vendue', NULL, 20, '2026-04-02 15:09:37', '2026-04-02 15:09:37'),
(112, 9, 'Ibrahima', 'Toure', '99997077', 'Bamako', 'Bagadadji', 'vendue', NULL, 18, '2026-04-02 15:38:28', '2026-04-02 15:38:28'),
(113, 7, 'Samou', 'Traore', '76264492', 'Bamako', 'Mali bougou', 'vendue', NULL, 18, '2026-04-02 15:39:22', '2026-04-02 15:39:22'),
(114, 2, 'Lydie', 'Diakite', '93139457', 'Bamako', 'Kalaban coro adeken', 'vendue', NULL, 18, '2026-04-02 15:40:28', '2026-04-02 15:40:28'),
(115, 2, 'Mamadou', 'Kebe', '73829978', 'Bamako', 'Boulkassoumbougou', 'vendue', NULL, 18, '2026-04-02 15:41:34', '2026-04-02 15:41:34'),
(116, 4, 'Boubacar', 'Fofana', '76379528', 'Bamako', 'Aci 2000', 'vendue', NULL, 18, '2026-04-02 15:42:50', '2026-04-02 15:42:50'),
(117, 2, 'Daouda', 'Doumbia', '78046185', NULL, NULL, 'vendue', NULL, 23, '2026-04-02 16:34:30', '2026-04-02 16:34:30'),
(118, 2, 'Sidiki', 'Yirango', '67779663', NULL, NULL, 'vendue', NULL, 23, '2026-04-02 16:35:28', '2026-04-02 16:35:28'),
(119, 2, 'Nyele', 'Diarra', '79559200', NULL, NULL, 'vendue', NULL, 23, '2026-04-02 16:36:29', '2026-04-02 16:36:29'),
(120, 2, 'Bakary', 'Dembele', '74815395', NULL, NULL, 'vendue', NULL, 23, '2026-04-02 16:37:34', '2026-04-02 16:37:34'),
(121, 2, 'Aminata', 'Sogodogo', '75556546', NULL, NULL, 'vendue', NULL, 23, '2026-04-02 16:38:28', '2026-04-02 16:38:28'),
(122, 4, 'Mohamed', 'Ousmane', '74699921', NULL, NULL, 'vendue', NULL, 23, '2026-04-02 16:39:43', '2026-04-02 16:39:43'),
(123, 6, 'Dembélé', 'Mariam Lassana', '78428639', 'Kayes', 'Kamankole', 'vendue', NULL, 38, '2026-04-02 18:31:49', '2026-04-02 18:31:49'),
(124, 6, 'Konaté', 'Mamadou', '75329873', 'Kayes', 'Plateau', 'vendue', NULL, 38, '2026-04-02 18:33:33', '2026-04-02 18:33:33'),
(125, 6, 'Traoré', 'Mariam', '94931605', 'Sadiola', 'Sadiola', 'vendue', NULL, 38, '2026-04-02 18:35:23', '2026-04-02 18:35:23'),
(126, 2, 'Aichata', 'N\'DIAYE', NULL, 'Bamako', 'Djicoroni-para', 'vendue', 'cartes-identite/almcRPmmiq3eBshF6KjRvOpMaXor1u5bjxxweRjr.jpg', 25, '2026-04-02 18:35:50', '2026-04-02 18:35:50'),
(127, 2, 'Alfousseyni', 'Camara', '76387933', 'Kita', 'ATT bougou', 'vendue', NULL, 38, '2026-04-02 18:37:05', '2026-04-02 18:37:05'),
(128, 8, 'Diakité', 'Yamadou', '83510847', 'Diamou', 'Bangassi', 'vendue', NULL, 38, '2026-04-02 18:38:40', '2026-04-02 18:38:40'),
(129, 8, 'Bah', 'Zeinabou', '74586509', 'Kayes', 'Lafiabougou sud', 'vendue', NULL, 38, '2026-04-02 18:40:09', '2026-04-02 18:40:09'),
(130, 7, 'Diallo', 'Kally Amadou', '79150831', 'Kayes', 'Lafiabougou sud', 'vendue', NULL, 38, '2026-04-02 18:41:38', '2026-04-02 18:41:38'),
(131, 3, 'Ousmane', 'OUOLOGUEM', NULL, 'Bamako', 'Djicoroni-para', 'vendue', 'cartes-identite/rEHlY3cxTSB8yQBCccU0I06LBsWU78P0NsCowIo6.jpg', 25, '2026-04-02 18:45:43', '2026-04-02 18:45:43'),
(132, 7, 'Kadidiatou Issa', 'TRAORE', '77889387', 'Bamako', 'Djicoroni-para S/c feu Époux', 'vendue', 'cartes-identite/7fvMwGnKj4vCH0d5LD1uFU7ymFIiiTqRJgO5UHeQ.jpg', 25, '2026-04-02 19:03:00', '2026-04-02 19:03:00'),
(133, 8, 'Mamadou', 'DEMBELE', '76199716', 'Bamako', 'Kanadjiguila marché côté-Est', 'vendue', 'cartes-identite/oMzB4OoCVehaWYETGnzBWV48qgAImCTpN3hCegxA.jpg', 25, '2026-04-02 19:05:39', '2026-04-02 19:05:39'),
(134, 8, 'Mariam', 'KEITA', NULL, 'Bamako', 'Djicoroni-para', 'vendue', 'cartes-identite/Ig0Cx4F69QNEO62xGYuqR9M5HcJLr7CdzDwlMBgX.jpg', 25, '2026-04-02 19:07:29', '2026-04-02 19:07:29'),
(135, 8, 'Daouda', 'SAMAKE', '77819407', 'Bamako', 'Djicoroni-para', 'vendue', 'cartes-identite/7CeaoVky7GBCAtBAlr0dTzMdKFhFRY6AN3ZwOf6o.jpg', 25, '2026-04-02 19:17:05', '2026-04-02 19:17:05'),
(136, 8, 'Ladji Daman', 'BAGAYOKO', '76388478', 'Bamako', 'Djicoroni-para Usine carré', 'vendue', 'cartes-identite/oK8ZbSv9InNLdvgivQkm1jEJ94HgzLh5FLsDCKNu.jpg', 25, '2026-04-02 19:19:03', '2026-04-02 19:19:03'),
(137, 8, 'Diakaridia', 'FOMBA', '90773393', 'Bamako', 'Senou plateau près de zawiya', 'vendue', 'cartes-identite/N4y9vlJwVGzgjVNyrjE1iRT3kEdX85RKPdb9bJVE.jpg', 25, '2026-04-02 19:34:22', '2026-04-02 19:34:22'),
(138, 9, 'Arouna', 'Sidibé', '72869999', 'Kati', 'Gongon', 'vendue', 'cartes-identite/aSYyroY8iBk4WZhmwRBq9gjdKhPr0nTmppVv9dTQ.jpg', 11, '2026-04-03 08:13:49', '2026-04-03 08:13:49'),
(139, 11, 'Issa', 'Tounkara', '76048438', 'Bamako', 'Torokorobougou', 'vendue', 'cartes-identite/7aFiZ34v291dTQC2H3qXTbFCcl32naap5i32Ly6t.jpg', 11, '2026-04-03 08:22:43', '2026-04-03 08:22:43'),
(140, 2, 'Mahamet', 'Doucour', '76444010', 'Bamako', 'Razel', 'vendue', NULL, 15, '2026-04-03 10:14:49', '2026-04-03 10:14:49'),
(141, 2, 'Diaby', 'Doucoure', '83360535', 'Bamako', 'Razel', 'vendue', NULL, 15, '2026-04-03 10:15:55', '2026-04-03 10:15:55'),
(142, 3, 'Mady', 'Kante', '70129129', 'Bamako', 'Doumazana', 'vendue', NULL, 15, '2026-04-03 10:17:04', '2026-04-03 10:17:04'),
(143, 2, 'Korotimi', 'Yarro', '73082048', 'Bamako', 'Hippodrome', 'vendue', NULL, 15, '2026-04-03 10:19:11', '2026-04-03 10:19:11'),
(144, 2, 'Jeanne dar', 'Coulibaly', '79116777', 'Bamako', 'Sangarebougou', 'vendue', NULL, 15, '2026-04-03 10:21:03', '2026-04-03 10:21:03'),
(145, 2, 'Fall oumar', 'Gueye', '76086360', 'Bamako', 'Djelibougou', 'vendue', NULL, 15, '2026-04-03 10:22:36', '2026-04-03 10:22:36'),
(146, 3, 'Ismaïla', 'Diawara', '76414631', 'Bamako', 'N’tomikorobougou', 'vendue', NULL, 26, '2026-04-03 10:22:46', '2026-04-03 10:22:46'),
(147, 2, 'Hawa', 'Kouyate', '66782170', 'Bamako', 'Fombabougou', 'vendue', NULL, 15, '2026-04-03 10:23:48', '2026-04-03 10:23:48'),
(148, 3, 'Oumar b', 'Keita', '79152975', 'Banako', 'Niamana', 'vendue', NULL, 26, '2026-04-03 10:24:17', '2026-04-03 10:24:17'),
(149, 2, 'Moussa', 'Keita', '96876446', 'Bamako', 'Korofina', 'vendue', NULL, 15, '2026-04-03 10:25:12', '2026-04-03 10:25:12'),
(150, 2, 'Seydou', 'Kone', '75932648', 'Bamako', 'Korofina', 'vendue', NULL, 15, '2026-04-03 10:26:25', '2026-04-03 10:26:25'),
(151, 2, 'Habibatou', 'Coulibaly', '67926441', 'Bamako', 'Korofina', 'vendue', NULL, 15, '2026-04-03 10:27:49', '2026-04-03 10:27:49'),
(152, 9, 'Kouma', 'Sory', '73434546', 'Bamako', 'Niarela', 'vendue', 'cartes-identite/SqXB95Unfc0gRTQIPnwm8Dt8hTOETWtm3TvOUEn1.jpg', 11, '2026-04-03 10:30:00', '2026-04-03 10:30:00'),
(153, 2, 'Gonflez dela serna', 'Marie piedad', '72829730', 'Bamako', 'Korofina', 'vendue', NULL, 15, '2026-04-03 10:30:15', '2026-04-03 10:30:15'),
(154, 9, 'Rokia', 'Koné', '71480263', 'Bamako', 'Kalaban coro plateau', 'vendue', NULL, 16, '2026-04-03 10:31:19', '2026-04-03 10:31:19'),
(155, 2, 'Gonflez dela serna', 'Marie piedad', '72829730', 'Bamako', 'Korofina', 'vendue', NULL, 15, '2026-04-03 10:31:42', '2026-04-03 10:31:42'),
(156, 3, 'Massa', 'DIAKITE', '72658740', 'Bamako', NULL, 'vendue', NULL, 20, '2026-04-03 10:47:07', '2026-04-03 10:47:07'),
(157, 4, 'Ousmane', 'Diallo', NULL, 'Bamako', 'Lafiabougou', 'vendue', NULL, 27, '2026-04-03 10:58:19', '2026-04-03 10:58:19'),
(158, 3, 'Mohamed ould', 'Sadack', '75505370', NULL, 'Faladié', 'vendue', NULL, 14, '2026-04-03 11:00:29', '2026-04-03 11:00:29'),
(159, 2, 'Kawele', 'Togola', '76320931', 'Bamako', 'Yirimadio', 'vendue', NULL, 10, '2026-04-03 11:17:49', '2026-04-03 11:17:49'),
(160, 2, 'Boubacar', 'Konate', '90373682', 'Bamako', NULL, 'vendue', NULL, 10, '2026-04-03 11:21:36', '2026-04-03 11:21:36'),
(161, 2, 'Siriki', 'Touré', '91022975', 'Koulikoro', 'Koulikoro', 'vendue', 'cartes-identite/EUOdsUsMMXlEGc390noZiVspii8qQNXIdY3Yt7jU.pdf', 33, '2026-04-03 11:50:21', '2026-04-03 11:50:21'),
(162, 2, 'Saidou', 'Keïta', '70060689', 'Koulikoro', 'Koulikoro', 'vendue', 'cartes-identite/MrqjkcIBW0luOVtQqYNhs55TP1HEluNp6MR0EqVE.pdf', 33, '2026-04-03 11:52:33', '2026-04-03 11:52:33'),
(163, 2, 'Kalifa', 'Tembely', '74900151', 'Koulikoro', 'Koulikoro', 'vendue', 'cartes-identite/vJNEDEw2fZGaE6Ah8nd1F3ez04R0XX8o3tHp5fkQ.pdf', 33, '2026-04-03 12:11:57', '2026-04-03 12:11:57'),
(164, 2, 'Alassane', 'Sidibe', '79125902', 'Ségou', 'Camp militaire', 'vendue', NULL, 30, '2026-04-03 12:54:25', '2026-04-03 12:54:25'),
(165, 2, 'Amadou diadié', 'Maidona', '69075050', 'Ségou', 'Hamdallaye', 'vendue', NULL, 30, '2026-04-03 12:55:36', '2026-04-03 12:55:36'),
(166, 7, 'Tahirou', 'Drago', '76609586', 'Segou', 'Sido sonicoura', 'vendue', 'cartes-identite/QTpyk3gYIKcz8FMsOOTKrbMX6tIHvaO40Cexlfsc.pdf', 29, '2026-04-03 14:27:22', '2026-04-03 14:27:22'),
(167, 2, 'Mohamed', 'Sylla', '71714684', NULL, NULL, 'vendue', NULL, 23, '2026-04-03 14:35:47', '2026-04-03 14:35:47'),
(168, 3, 'Abdoulaye', 'Dembele', '76588005', 'Segou', 'Pelengana Nord', 'vendue', 'cartes-identite/2M03bhv8pA5CuRWDAkaByNGTvjKjrBtL5z4DPabR.pdf', 29, '2026-04-03 14:41:06', '2026-04-03 14:42:19'),
(169, 3, 'Mohamed hady', 'Sow', '74000467', NULL, NULL, 'vendue', NULL, 23, '2026-04-03 14:44:31', '2026-04-03 14:44:31'),
(170, 2, 'Lamine', 'Doucoura', '76973030', 'Bamako', 'Zone industrielle', 'vendue', NULL, 19, '2026-04-03 19:38:05', '2026-04-03 19:38:05'),
(171, 4, 'Cheick Oumar', 'Diarra', '79424232', 'Bamako', 'Sebenikoro', 'vendue', NULL, 26, '2026-04-04 07:17:40', '2026-04-04 07:17:40'),
(172, 3, 'Moussa', 'Camara', '76286847', 'Bamako', 'Sotuba', 'vendue', NULL, 26, '2026-04-04 07:19:27', '2026-04-04 07:19:27'),
(173, 3, 'Abdoulaye', 'SY', '76365656', 'Bamako', NULL, 'vendue', 'cartes-identite/uHZhdEvmcfuZiTeBdrwUqzVQnUmG2QnOavnVSPiB.jpg', 20, '2026-04-04 10:33:30', '2026-04-04 10:33:30'),
(174, 10, 'Sara', 'KONTE', NULL, 'Bamako', NULL, 'vendue', 'cartes-identite/2hKrePtNYhkuUbO10PFEc5ZWpnQi3u8HCFdOJQJD.jpg', 20, '2026-04-04 10:34:59', '2026-04-04 10:34:59'),
(175, 2, 'Youssouf', 'Niangadou', '77777070', 'Bamako', 'Niamana', 'vendue', NULL, 10, '2026-04-04 11:06:44', '2026-04-04 11:06:44'),
(176, 9, 'Coulibaly', 'Seydou', '73174333', 'Kayes', 'KayesNdi', 'vendue', NULL, 38, '2026-04-04 11:07:10', '2026-04-04 11:07:10'),
(177, 7, 'Abdoulaye', 'Coulibaly', '70385283', 'Bamako', 'Hamdallaye', 'vendue', NULL, 10, '2026-04-04 11:07:57', '2026-04-04 11:07:57'),
(178, 7, 'Traoré', 'Issa', '74168358', 'Kayes', 'Khasso', 'vendue', NULL, 38, '2026-04-04 11:09:39', '2026-04-04 11:09:39'),
(179, 4, 'Coulibaly', 'Seydou', '73174333', 'Kayes', 'Soutoucoulé', 'vendue', NULL, 38, '2026-04-04 11:18:04', '2026-04-04 11:18:04'),
(180, 3, 'Dibaga', 'Sekou', '93352224', 'Kayes', 'Dialané', 'vendue', NULL, 38, '2026-04-04 11:20:02', '2026-04-04 11:20:02'),
(181, 7, 'Thera', 'Yoro', '78558303', 'Kayes', 'KayesNdi', 'vendue', NULL, 38, '2026-04-04 11:35:44', '2026-04-04 11:35:44'),
(182, 4, 'Moussa', 'Keita', NULL, 'Bamako', 'Para djicoronie', 'vendue', NULL, 27, '2026-04-04 11:40:06', '2026-04-04 11:40:06'),
(183, 7, 'Mary madou', 'Dembele', '77617896', 'Bamako', 'Sebenikoro', 'vendue', NULL, 19, '2026-04-04 11:44:12', '2026-04-04 11:44:12'),
(184, 2, 'Niankate', 'Dangoule', '79139376', 'Bamako', 'Torokorobouhou', 'vendue', NULL, 17, '2026-04-04 12:51:56', '2026-04-04 12:51:56'),
(185, 2, 'Fatimata', 'Sangaré', '78754962', 'Bamako', 'Baco djicroni', 'vendue', NULL, 17, '2026-04-04 12:52:27', '2026-04-04 12:52:27'),
(186, 8, 'Fatoumata', 'TRAORE', '73965511', 'Bamako', 'Djicoroni-para gningnin carré', 'vendue', 'cartes-identite/n9LS9MNJ5UFtMM3Rov8NjFMJo3HX7zcPGevlAsnI.jpg', 25, '2026-04-04 13:43:36', '2026-04-04 13:43:36'),
(187, 3, 'Aboubacar sidiki', 'DOUMBIA', '98741309', 'Bamako', 'Djicoroni-para près du marché', 'vendue', 'cartes-identite/DZtoYMTT8mBlNhS8gjKvAr0fJIMFwTy4v7cqjSLk.jpg', 25, '2026-04-04 13:45:32', '2026-04-04 13:45:32'),
(188, 3, 'Kalilou', 'KOITA', '70544268', 'Bamako', 'Djicoroni-para près de la maternité', 'vendue', 'cartes-identite/roDdmsIAnU5Lk495HfmwJNCsBdbwtIKHCCmCkMmq.jpg', 25, '2026-04-04 13:47:05', '2026-04-04 13:47:05'),
(189, 3, 'Mamourou', 'Traoré', '74242743', 'Segou', 'Pelengana Nord', 'vendue', 'cartes-identite/13aDjXbYYy7yuFDAzf0yzVkLf5v8Rdnsrbmo5KqP.pdf', 29, '2026-04-04 13:57:48', '2026-04-04 13:57:48'),
(190, 3, 'Dramane', 'Donogo', '71773220', NULL, NULL, 'vendue', NULL, 23, '2026-04-04 14:08:48', '2026-04-04 14:08:48'),
(191, 3, 'Mahamadou', 'Guindo', '76352165', NULL, NULL, 'vendue', NULL, 23, '2026-04-04 14:09:28', '2026-04-04 14:09:28'),
(192, 3, 'Soumare', 'Tiemoko', '73508032', NULL, NULL, 'vendue', NULL, 23, '2026-04-04 14:11:02', '2026-04-04 14:11:02'),
(193, 3, 'Ibrahim', 'Diarra', '76375937', NULL, NULL, 'vendue', NULL, 23, '2026-04-04 14:12:08', '2026-04-04 14:12:08'),
(194, 3, 'Almchtar', 'Ould Oumar', '79291412', 'Segou', 'Medine', 'vendue', 'cartes-identite/vsOYFrXudsGLMuQtAzDYZUo1BtkW4mlvt2rziogA.jpg', 29, '2026-04-04 14:35:27', '2026-04-04 14:35:27'),
(195, 7, 'Aly', 'Traoré', '70610469', 'Kayes', 'KayesNdi', 'vendue', NULL, 38, '2026-04-04 14:41:05', '2026-04-04 14:41:05'),
(197, 7, 'Keremekamba', 'Keita', '93462637', 'Bougouni', 'Bougoudani', 'vendue', 'cartes-identite/MF36EHzitfTfLPJwhErzbHHL0IY1aaKi8CRju06j.pdf', 31, '2026-04-04 21:11:24', '2026-04-04 21:11:24'),
(198, 7, 'Jean Baptiste', 'Kamate', '65937881', 'San', 'lafiabougou', 'vendue', 'cartes-identite/dD2HRle4bjINzNxfHQIQIoqqqEFWTJOpnG1dTrN4.pdf', 31, '2026-04-04 21:22:25', '2026-04-04 21:22:25'),
(199, 7, 'Dialla', 'Camara', '94663660', 'San', 'Médine', 'vendue', 'cartes-identite/sE4OZpckITuVZp1qLI9Q4O4AoXAxJtJFSLfkS3nH.pdf', 31, '2026-04-04 21:35:00', '2026-04-04 21:35:00'),
(200, 7, 'Niagalé', 'Coulibaly', '729 65 176', 'San', 'Lafiabougou', 'vendue', 'cartes-identite/c4UIQj0CQBF4AhnmdgWFAZiUWHFllNlZZEyZ4aUS.pdf', 31, '2026-04-04 21:43:26', '2026-04-04 21:43:26'),
(201, 8, 'Sia Claude', 'Touré', '53992835', 'Bamako', 'Djicoroni para', 'vendue', 'cartes-identite/aAC7Mhc2EgMYUIrhY5Ei161yYu8BOopIrlo6NMRn.jpg', 11, '2026-04-07 08:02:13', '2026-04-07 08:02:13'),
(202, 9, 'Boukary', 'Fane', '76493182', 'Bamako', 'Sebenikoro', 'vendue', 'cartes-identite/ej7gH7NI7ELIRyXWkoxI4uvD2nU4K3LrHSAvZaHd.jpg', 11, '2026-04-07 08:50:51', '2026-04-07 08:50:51'),
(203, 9, 'Amidou', 'Diakite', '73514499', 'Bamako', 'Lafiabougou', 'vendue', 'cartes-identite/L9DGMfVh4EvfNsjq4BgiKBgqSAM4JBxFqgAeJbNa.jpg', 11, '2026-04-07 08:59:45', '2026-04-07 08:59:45'),
(204, 2, 'Boubacar', 'Touré', '65242777', 'Segou', 'Pelengana sud', 'vendue', NULL, 30, '2026-04-07 09:28:06', '2026-04-07 09:28:06'),
(205, 6, 'Karim', 'Diakite', '75741185', 'Bamako', 'Quarantiguibougou', 'vendue', 'cartes-identite/dWRoYEukOGYv5w1SCcUkB9VOitJdV9QVoprry1v2.jpg', 11, '2026-04-07 09:32:18', '2026-04-07 09:32:18'),
(206, 8, 'Mamadou', 'Diarra', '84678481', 'Sikasso', 'Wayerma 2', 'vendue', NULL, 35, '2026-04-07 09:39:10', '2026-04-07 09:39:10'),
(207, 8, 'Seydou', 'Coulibaly', '74205907', 'Sikasso', 'Mancourani 1', 'vendue', NULL, 35, '2026-04-07 09:41:04', '2026-04-07 09:41:04'),
(208, 6, 'Boubacar', 'Mariko', '74779953', 'Sikasso', 'Sanso', 'vendue', NULL, 35, '2026-04-07 09:42:46', '2026-04-07 09:42:46'),
(209, 2, 'Mamadou baiba', 'Sacko', '70440045', 'Bamako', 'Daoudabougou', 'vendue', NULL, 11, '2026-04-07 10:45:55', '2026-04-07 10:45:55'),
(210, 6, 'Soungalo', 'Diakité', '76984975', 'Sikasso', 'N\'tomikorobougou', 'vendue', NULL, 35, '2026-04-07 10:51:20', '2026-04-07 10:51:20'),
(211, 6, 'Mah Adiara', 'Bengaly', '76534896', 'Sikasso', 'Wayerma 2', 'vendue', NULL, 35, '2026-04-07 11:10:21', '2026-04-07 11:10:21'),
(212, 2, 'Adama', 'Coulibaly', '832667396', 'San', 'Médine', 'vendue', 'cartes-identite/XgX98cuUdCR0z2gwRB1buFIa7Z04Sky47sJP3h0a.pdf', 31, '2026-04-07 12:00:09', '2026-04-07 12:00:09'),
(213, 3, 'Boubacar', 'Dembele', '71272305', 'San', 'Médine', 'vendue', 'cartes-identite/f6bZWUS2g5Vv5Rs0vuw8iAtPeEi8J3QlshriFQCn.pdf', 31, '2026-04-07 12:04:43', '2026-04-07 12:04:43'),
(214, 4, 'Ousmane', 'TRAORE', '71728469', NULL, NULL, 'vendue', NULL, 13, '2026-04-07 16:38:42', '2026-04-07 16:38:42'),
(216, 3, 'Daouda', 'Kone', '77832840', NULL, NULL, 'vendue', NULL, 23, '2026-04-07 17:17:55', '2026-04-07 17:17:55'),
(217, 2, 'Nouhoum', 'Maiga', '99036660', 'Ségou', 'Sebougou', 'vendue', NULL, 30, '2026-04-07 17:24:27', '2026-04-07 17:24:27'),
(218, 6, 'Mamadou Bassirou', 'Coulibaly', '76636063', 'Segou', 'Pelengana', 'vendue', 'cartes-identite/gsi3mUPIrppvVc4Ig3G0SAQlA5yJpCtJgd9pconp.jpg', 29, '2026-04-07 17:52:40', '2026-04-07 17:53:49'),
(219, 6, 'Mamourou', 'Traoré', '76328511', 'Segou', 'Pelengana Nord', 'vendue', 'cartes-identite/mIGj6cphCcwb0Z8TJxzgG0D4Xfq3SUxYRNWAk2GZ.jpg', 29, '2026-04-07 18:04:16', '2026-04-07 18:04:16'),
(220, 2, 'Ousmane', 'Konta', '71 75 46 06', 'San', 'Farakolo', 'vendue', 'cartes-identite/9fiaqQdJkZW0oZRzJnGpKnWFJYJMcUMZ2u05WJch.pdf', 31, '2026-04-07 18:17:59', '2026-04-07 18:17:59'),
(221, 6, 'Mamadou', 'Traore', '77173007', 'Segou', 'Darsalam', 'vendue', 'cartes-identite/Hx8MqeOlcdzU2Xfy9HqGneNxFZa2qJVVRPaACmgP.jpg', 29, '2026-04-07 18:19:03', '2026-04-07 18:19:03'),
(222, 6, 'Moussa', 'Coulibaly', '73050858', 'Segou', 'Pelengana Nord', 'vendue', 'cartes-identite/eHWCyWwqiXhGcL0dbh6COFhc18aOV5LQPCx6GaSK.jpg', 29, '2026-04-07 18:20:11', '2026-04-07 18:20:11'),
(223, 6, 'Bakary', 'Traoré', '77798270', 'Segou', 'Pelengana Nord', 'vendue', 'cartes-identite/nDenK3qfDw8RMNIMJeudffVlPoQ8fWyGiLNiuV8d.jpg', 29, '2026-04-07 18:21:13', '2026-04-07 18:21:13'),
(224, 6, 'Siaka', 'Traoré', '93563436', 'Segou', 'Pelengana', 'vendue', 'cartes-identite/3q4WcyL8XAJ3ALiBffjEUGIHvGOpD9D91nKVJQHA.jpg', 29, '2026-04-07 18:23:34', '2026-04-07 18:23:34'),
(225, 6, 'Bafing', 'Coulibaly', '73473423', 'Segou', 'Pelengana Nord', 'vendue', 'cartes-identite/wCzgFimVfFEixD6Zumm03P8AfuGIBBDSNj4B4ZhD.jpg', 29, '2026-04-07 18:25:31', '2026-04-07 18:25:31'),
(226, 8, 'Moussa', 'Sissoko', '74036513', 'Kayes', 'Diamou', 'vendue', NULL, 38, '2026-04-07 18:29:18', '2026-04-07 18:29:18'),
(227, 8, 'Yacouba', 'Touré', '91210388', 'Kayes', 'Kounda', 'vendue', NULL, 38, '2026-04-07 18:31:01', '2026-04-07 18:31:01'),
(228, 3, 'Salou', 'N\'diaye', '76462065', 'Kayes', 'Kayes N\'di', 'vendue', NULL, 38, '2026-04-07 18:33:26', '2026-04-07 18:33:26'),
(229, 6, 'Diabé', 'Diarra', '76013456', 'Kayes', 'Khasso', 'vendue', NULL, 38, '2026-04-07 18:35:51', '2026-04-07 18:35:51'),
(230, 7, 'Nafe', 'DIAWARA', '78224491', 'Bamako', 'Dravela bolibana', 'vendue', 'cartes-identite/MneELdzp6hY8KMqYQMgz3B3PCkgbFKhWnid1AyUg.jpg', 25, '2026-04-07 19:57:50', '2026-04-07 19:57:50'),
(231, 3, 'Mohamed', 'DOUMBIA', '79383557', 'Bamako', 'Sebenikoro près du marché', 'vendue', 'cartes-identite/vfNs3HtXACv66miYxWcTit0KFflgKXvjqM6gv6cf.jpg', 25, '2026-04-07 19:59:16', '2026-04-07 19:59:16'),
(232, 7, 'Mamadou', 'Fomba', '78925562', 'Dioïla', NULL, 'vendue', NULL, 34, '2026-04-07 20:27:44', '2026-04-07 20:27:44'),
(233, 8, 'Issouf', 'Dembele', '71198597', 'Dioïla', NULL, 'vendue', NULL, 34, '2026-04-07 20:28:53', '2026-04-07 20:28:53'),
(234, 9, 'Ely', 'COULIBALY', '76161017', 'Bamako', 'Kalaban coura', 'vendue', 'cartes-identite/26SS6jJ9hbJwHDdmz0v1nuSVbBrtBCdk1wgJg9rZ.jpg', 20, '2026-04-07 21:39:31', '2026-04-07 21:51:33'),
(235, 8, 'Mamadou', 'BAH', '71363390', 'Bamako', 'Kalaban coura', 'vendue', 'cartes-identite/C9GGjgSMtycWeJ8JPsw3jg574XcQNkQhWmweAXSc.jpg', 20, '2026-04-07 21:41:56', '2026-04-07 21:41:56'),
(236, 7, 'Mamadou', 'KAMARA', '90053137', 'Bamako', 'Kalaban coura', 'vendue', 'cartes-identite/s1wu4yJ8cfqdAtXnnoFpJhtf8UP7nRZEvIX1ekK9.jpg', 20, '2026-04-07 21:44:44', '2026-04-07 21:51:13'),
(237, 8, 'Zié Mohamed', 'SANOGO', '78503571', 'Bamako', 'Kalaban coura', 'vendue', 'cartes-identite/CGjWMt8IIpuFpNVntZ66o85h2VGdh4KNDVSfVerF.jpg', 20, '2026-04-07 21:47:23', '2026-04-07 21:47:23'),
(238, 8, 'Gaoussou', 'SANGARE', '75847528', 'Bamako', 'Kalaban coura', 'vendue', NULL, 20, '2026-04-07 21:48:55', '2026-04-07 21:48:55'),
(239, 9, 'Salif', 'TRAORÉ', '66607777', 'Bamako', 'Kalaban coura', 'vendue', NULL, 20, '2026-04-07 21:50:45', '2026-04-07 21:50:45'),
(240, 8, 'Kassoum', 'CISSÉ', '74036914', 'Bamako', 'Kalaban coura', 'vendue', 'cartes-identite/5vg3j8MiiKzw975K54k8DMMH2mm6qOahdD22c2kx.jpg', 20, '2026-04-07 21:53:23', '2026-04-07 21:53:23'),
(241, 10, 'Fousseini', 'SOGODOGO', '63905952', 'Bamako', 'Kalaban coura', 'vendue', 'cartes-identite/2NzuLmOu78vJvT2eNxEZuVcJTv8EvSvuxCvYkrI4.jpg', 20, '2026-04-07 21:55:22', '2026-04-07 21:55:22'),
(242, 9, 'Fatoumata', 'SIDIBÉ', '76468800', 'Bamako', 'Kalaban coura', 'vendue', 'cartes-identite/JHbplKA2nxBeqteYRxMWNnjcjYpsAQbKASMJo5Un.jpg', 20, '2026-04-07 21:57:38', '2026-04-07 21:57:38'),
(243, 4, 'Alou', 'Niangado', '75101515', 'Bamako', 'Faso Kanu', 'vendue', NULL, 11, '2026-04-08 06:17:51', '2026-04-08 06:17:51'),
(244, 2, 'Ismail', 'Maiga', '70090860', 'Bamako', 'Aci', 'vendue', NULL, 11, '2026-04-08 06:19:24', '2026-04-08 06:19:24'),
(245, 2, 'Yaya', 'Doumbia', '76809267', 'Koulikoro', 'Koulikoro Souban', 'vendue', 'cartes-identite/cLfsyhgW46Dq1WolqNxOeQZIqLsxCtCsfBbGFdiA.pdf', 33, '2026-04-08 07:13:42', '2026-04-08 07:13:42'),
(246, 8, 'Kibily Dimba', 'Traore', '73101846', 'Koulikoro', 'Koulikoro', 'vendue', 'cartes-identite/La5kuMO7tUkQbncgd2Hqx4IvKybcje3U8e5Z5SMQ.pdf', 33, '2026-04-08 07:16:42', '2026-04-08 07:16:42'),
(247, 2, 'Tiemoko', 'Fofana', '79439030', 'Koulikoro', 'Koulikoro', 'vendue', 'cartes-identite/JUj1ndNVp4l7dpdIqWn3ubVeBptLFdR6DByzrkFy.pdf', 33, '2026-04-08 07:18:09', '2026-04-08 07:18:09'),
(248, 6, 'Oumar', 'Haïdara', '74407263', 'Koulikoro', 'Koulikoro', 'vendue', 'cartes-identite/zPzFmZwzVemoMmqMXAwSFlTe7TJLJKHP7886jeJR.pdf', 33, '2026-04-08 07:19:33', '2026-04-08 07:19:33'),
(249, 7, 'Hawaou', 'Koromakan', '75241624', 'Koulikoro', 'Koulikoro', 'vendue', 'cartes-identite/O9ngZRXhWa0PSbCOKUrr6rwPPbOxli8b4DSGlVXN.pdf', 33, '2026-04-08 07:20:54', '2026-04-08 07:20:54'),
(250, 6, 'Brahim', 'Doumbia', '92590937', 'Koulikoro', 'Koulikoro', 'vendue', 'cartes-identite/6g5vEMcB2pjIQyh68ezCuL7AQJqVIeYFAW1ARtdN.pdf', 33, '2026-04-08 07:21:51', '2026-04-08 07:21:51'),
(251, 2, 'Harouna', 'Diallo', '76734300', 'Bamako', 'Senou', 'vendue', NULL, 15, '2026-04-08 07:33:30', '2026-04-08 07:33:30'),
(252, 2, 'Souleymane', 'Camara', '76845959', 'Bamako', 'Badialan', 'vendue', NULL, 15, '2026-04-08 07:35:18', '2026-04-08 07:35:18'),
(253, 2, 'Baissembe', 'Dolo', '76391004', 'Bamako', 'Moribabougou', 'vendue', NULL, 15, '2026-04-08 07:36:31', '2026-04-08 07:36:31'),
(254, 4, 'Seydou', 'Kante', '76465470', 'Bamako', 'Korofina', 'vendue', NULL, 15, '2026-04-08 07:38:45', '2026-04-08 07:38:45'),
(255, 4, 'Seydou', 'Kante', '76465470', 'Bamako', 'Korofina', 'vendue', NULL, 15, '2026-04-08 07:39:42', '2026-04-08 07:39:42'),
(256, 7, 'Fatoumata', 'Diakité', '76300493', 'Bamako', 'Kalaban coro', 'vendue', NULL, 16, '2026-04-08 07:51:09', '2026-04-08 07:51:09'),
(257, 8, 'Aïssata', 'KANTE', '77319325', 'Bamako', 'Kalaban coura', 'vendue', 'cartes-identite/IKjDrMn0hmS5WEd9d07Y8AyVIRRZAM5EDeYPudNW.jpg', 20, '2026-04-08 09:09:15', '2026-04-08 09:09:15'),
(258, 4, 'yahaya', 'Soukouma', '78778397', 'Bamako', 'Golf', 'vendue', NULL, 16, '2026-04-08 09:34:42', '2026-04-08 09:34:42'),
(259, 3, 'Yahaya', 'Soukouma', '78778397', 'Bamako', 'Golf', 'vendue', NULL, 16, '2026-04-08 09:35:25', '2026-04-08 09:35:25'),
(260, 4, 'Makan', 'Konate', '84474510', NULL, 'Magnabougou', 'vendue', NULL, 14, '2026-04-08 09:39:18', '2026-04-08 09:39:18'),
(261, 2, 'Souleymane', 'Sylla', '77097574', 'Bamako', 'Aci', 'vendue', NULL, 11, '2026-04-08 10:00:44', '2026-04-08 10:00:44'),
(262, 9, 'Abibatou', 'DIARRA', '74004092', 'Bamako', 'Kalaban coura', 'vendue', 'cartes-identite/GsrFySnchI2WL29ObosU2Yl5QbFKjDcTmPtuw8zr.jpg', 20, '2026-04-08 10:24:34', '2026-04-08 10:24:34'),
(263, 2, 'Himd Rahma', 'Ahmed salem', NULL, 'Tombouctou', NULL, 'vendue', NULL, 36, '2026-04-08 10:42:55', '2026-04-08 10:42:55'),
(264, 2, 'Abdel Magid', 'Ben', NULL, NULL, NULL, 'vendue', NULL, 36, '2026-04-08 10:44:15', '2026-04-08 10:44:15'),
(265, 2, 'Mohamed chabane', 'Djické', NULL, NULL, NULL, 'vendue', NULL, 36, '2026-04-08 10:44:59', '2026-04-08 10:44:59'),
(266, 3, 'Elhadji ould', 'Hadrahma', NULL, NULL, NULL, 'vendue', NULL, 36, '2026-04-08 10:45:53', '2026-04-08 10:45:53'),
(267, 2, 'Karim', 'Samake', '66737641', 'Bamako', 'Niamana', 'vendue', NULL, 10, '2026-04-08 11:04:25', '2026-04-08 11:04:25'),
(268, 2, 'Abdoulaye Amadou', 'Maïga', '78703002', 'Bamako', 'N’tabacoro', 'vendue', NULL, 10, '2026-04-08 11:05:43', '2026-04-08 11:05:43'),
(269, 7, 'Seny', 'Simpara', '76026429', 'Bamako', 'Niamana fouga', 'vendue', NULL, 10, '2026-04-08 11:07:38', '2026-04-08 11:07:38'),
(270, 7, 'Karembe', 'Douro', '73245196', NULL, NULL, 'vendue', NULL, 36, '2026-04-08 11:19:03', '2026-04-08 11:19:03'),
(271, 4, 'Mamadou', 'Diaby', NULL, 'Bamako', 'Baco djicoronie Aci', 'vendue', NULL, 27, '2026-04-08 11:21:04', '2026-04-08 11:21:04'),
(272, 7, 'Harouna', 'Diakité', NULL, 'Tombouctou', NULL, 'vendue', NULL, 36, '2026-04-08 11:21:22', '2026-04-08 11:21:22'),
(273, 9, 'Ely', 'Dembele', '82237655', NULL, NULL, 'vendue', NULL, 36, '2026-04-08 11:22:50', '2026-04-08 11:22:50'),
(274, 9, 'Ely', 'Dembele', '82237655', NULL, NULL, 'vendue', NULL, 36, '2026-04-08 11:23:40', '2026-04-08 11:23:40'),
(275, 7, 'Makan', 'SISSOKO', '77885735', 'Bamako', 'Kalaban coura', 'vendue', NULL, 20, '2026-04-08 11:33:22', '2026-04-08 11:33:22'),
(276, 6, 'Aliou', 'Diallo', '73498435', 'Kayes', 'Doussoukané', 'vendue', NULL, 38, '2026-04-08 12:31:55', '2026-04-08 12:31:55'),
(277, 3, 'Fadima', 'Thiam', NULL, 'Bamako', 'Djicoronie para', 'vendue', NULL, 27, '2026-04-08 12:49:28', '2026-04-08 12:49:28'),
(278, 2, 'Ali Bandara', 'Diawara', '79412348', 'Bamako', 'Magnambougou r266 p588', 'vendue', NULL, 26, '2026-04-08 13:20:13', '2026-04-08 13:21:17'),
(279, 2, 'Daouda', 'Diarra', '65992579', 'Bamako', 'Sotuba', 'vendue', NULL, 26, '2026-04-08 13:21:46', '2026-04-08 13:22:57'),
(280, 4, 'Amadou', 'Traoré', '66752361', 'Bamako', 'Sirakoro', 'vendue', NULL, 26, '2026-04-08 13:23:15', '2026-04-08 13:23:52'),
(281, 2, 'Aïchata', 'Samake', '78 10 86 14', 'Bamako', 'Kalaban Coura', 'vendue', NULL, 26, '2026-04-08 13:24:29', '2026-04-08 13:25:07'),
(282, 2, 'Korotoumou', 'Sissoko', '73656644', 'Bamako', 'Lafiabougou', 'vendue', NULL, 11, '2026-04-08 13:28:27', '2026-04-08 13:28:27'),
(283, 2, 'Abdel', 'Tounkara', '71444464', 'Bamako', 'Hamdallaye', 'vendue', NULL, 11, '2026-04-08 14:21:17', '2026-04-08 14:21:17'),
(284, 3, 'Sambou', 'Dembele', '76277641', 'Kita', 'Doumbacoura', 'vendue', 'cartes-identite/XLlTiiq4q6XUhjPOnFwCv3FjyHiiNOZShLatIIqY.jpg', 37, '2026-04-08 14:21:40', '2026-04-08 14:21:40'),
(285, 3, 'Sissoko', 'Koumba', '76888037', 'Bamako', 'Golf', 'vendue', NULL, 16, '2026-04-08 14:26:06', '2026-04-08 14:26:06'),
(286, 7, 'Fousseyni', 'Coulibaly', '67093669', 'Segou', 'Dougabougou', 'vendue', 'cartes-identite/xzMRfowrkht6J1QaPFo8TwLxeecqaWGGsqa5Gum5.jpg', 29, '2026-04-08 14:34:00', '2026-04-08 14:34:00'),
(287, 4, 'Aboudrahamane', 'Dembele', '76379619', 'Bamako', 'Madina coura', 'vendue', NULL, 26, '2026-04-08 14:42:15', '2026-04-08 15:14:25'),
(288, 6, 'Salia', 'Diarra', '70801281', 'Bamako', 'N’tabacoro', 'vendue', NULL, 10, '2026-04-08 15:07:01', '2026-04-08 15:07:01'),
(289, 6, 'Souleymane', 'Saye', '79872001', 'Koulikoro', 'Koulikoro', 'vendue', 'cartes-identite/Q2RiZsAQ210pE4STmGoa3sBGn4aopb9T5h29FRG8.pdf', 33, '2026-04-08 15:19:11', '2026-04-08 15:19:11'),
(290, 7, 'Mamou', 'Bamia', '94537889', 'Bamako', 'Bamako', 'vendue', 'cartes-identite/FC1GjkQzLntqj5tqSg52lIacT1EyZKP95SHBAi3z.pdf', 33, '2026-04-08 15:20:11', '2026-04-08 15:20:11'),
(291, 6, 'Mamadou', 'Keïta', '77415692', 'Koulikoro', 'Koulikoro', 'vendue', 'cartes-identite/nnyGhVlBTMavbSoN71MVp2TufEvBhXb1SLozzPRR.pdf', 33, '2026-04-08 15:21:06', '2026-04-08 15:21:06'),
(292, 6, 'Zantigui', 'Traoré', '77711411', 'Koulikoro', 'Koulikoro', 'vendue', 'cartes-identite/h5ySzqDs8Uz3QBjyNkPNYC3QrA0e0O1kLApB80lV.pdf', 33, '2026-04-08 15:22:02', '2026-04-08 15:22:02'),
(293, 8, 'Halimatou', 'Traore', '90625207', 'Bamako', 'Quinzandougou', 'vendue', NULL, 19, '2026-04-08 15:40:00', '2026-04-08 15:40:00'),
(294, 6, 'Habibatou', 'Coulibaly', '66532360', 'Bamako', 'Medina coura', 'vendue', NULL, 19, '2026-04-08 15:41:11', '2026-04-08 15:41:11'),
(295, 7, 'Fissina', 'Diarra', '76978272', 'Dioïla', NULL, 'vendue', NULL, 34, '2026-04-08 15:56:14', '2026-04-08 15:56:14'),
(296, 3, 'Fatimata', 'Kane', '90769025', 'Bamako', 'Médina coura', 'vendue', NULL, 18, '2026-04-08 17:08:12', '2026-04-08 17:08:12'),
(297, 2, 'Ramata', 'Hanne', '71763808', 'Bamako', 'Kati', 'vendue', NULL, 18, '2026-04-08 17:08:54', '2026-04-08 17:08:54'),
(298, 8, 'Aminata', 'Traore', '79083656', 'Bamako', 'Sebenicoro', 'vendue', NULL, 18, '2026-04-08 17:09:44', '2026-04-08 17:09:44'),
(299, 2, 'Rokia', 'Coulibaly', '75011595', 'Bamako', 'Quartier mali', 'vendue', NULL, 18, '2026-04-08 17:10:48', '2026-04-08 17:10:48'),
(300, 2, 'Mohamed moustapha', 'Mariko', '70050287', 'Bamako', 'Magnambougou', 'vendue', NULL, 18, '2026-04-08 17:11:36', '2026-04-08 17:11:36'),
(301, 4, 'Sira', 'Keita', '83990062', 'Bamako', 'Hippodrome', 'vendue', NULL, 18, '2026-04-08 17:12:23', '2026-04-08 17:12:23'),
(302, 7, 'Moussa', 'DIARRA', '66964603', 'Bamako', 'Djicoroni-para', 'vendue', 'cartes-identite/LRD3iuWzR5J3NfcfOk0PXendVCiDH7Nj2369cF7n.jpg', 25, '2026-04-08 21:25:10', '2026-04-08 21:25:10'),
(303, 8, 'Bakary', 'BAGAYOKO', '73247158', 'Bamako', 'Senou hèrèmakono', 'vendue', 'cartes-identite/SWdTRDuGOxsKgW5WpIZsgVO03tAdynCK6A7AGslF.jpg', 25, '2026-04-08 21:27:09', '2026-04-08 21:27:09'),
(304, 3, 'Saibou', 'SANOGO', '76159789', 'Bamako', 'Badalabougou Sema 2', 'vendue', 'cartes-identite/VVHa1AmEgu5yl3Xh3Ix0lzLChpnDFqM6A1k5RPfk.jpg', 25, '2026-04-08 21:28:47', '2026-04-08 21:28:47'),
(305, 6, 'Toumany', 'Diallo', '78913738', 'Bamako', 'Sebenikoro', 'vendue', NULL, 11, '2026-04-09 08:49:59', '2026-04-09 08:49:59'),
(306, 7, 'Alassane', 'Toure', '74906503', 'Bamako', 'Niamakoro', 'vendue', NULL, 11, '2026-04-09 08:51:15', '2026-04-09 08:51:15'),
(307, 6, 'Karim', 'Keita', '94235301', 'Bamako', 'Djicoroni', 'vendue', NULL, 11, '2026-04-09 09:00:32', '2026-04-09 09:00:32'),
(308, 2, 'Sory', 'Sidibé', '66203316', NULL, 'Krofina-Nord', 'vendue', NULL, 14, '2026-04-09 09:41:46', '2026-04-09 09:41:46'),
(309, 11, 'Cabinet Mariam', 'Malle', '76127522', 'Bamako', 'Aci', 'vendue', NULL, 11, '2026-04-09 10:10:48', '2026-04-09 10:10:48'),
(310, 7, 'Touré service', 'Et business', '75906503', 'Bamako', 'Cite unicef', 'vendue', NULL, 11, '2026-04-09 10:12:09', '2026-04-09 10:12:09'),
(311, 2, 'Diawoye', 'KONARE', '73328973', 'Bamako', 'Kalaban Coura', 'vendue', 'cartes-identite/YMbgDglrPL69B4IRpUx5o7p1OHAGQROJVegiXI3j.jpg', 20, '2026-04-09 10:24:48', '2026-04-09 10:24:48'),
(312, 8, 'Aicha', 'Lah', '73818502', 'Bamako', 'Sirakoro sité BMS', 'vendue', NULL, 16, '2026-04-09 11:34:16', '2026-04-09 11:34:16'),
(313, 4, 'Ousmane', 'Kanté', '77226688', NULL, 'Gnamakôrô', 'vendue', NULL, 14, '2026-04-09 11:56:22', '2026-04-09 11:56:22'),
(315, 2, 'Abraham', 'Traoré', '72361516', NULL, 'Point-G', 'vendue', NULL, 14, '2026-04-09 12:51:16', '2026-04-09 12:51:16'),
(316, 4, 'Cheick Oumar', 'Diarra', '73134343', 'Bamako', 'Sebenikoro', 'vendue', NULL, 26, '2026-04-09 13:00:07', '2026-04-09 13:31:29'),
(317, 2, 'Seydou', 'Kané', '77296515', 'Koulikoro', 'Koulikoro', 'vendue', 'cartes-identite/mhBHp3mI5OvnExrvnfBFEBI9DgxkmYwkdxEi0qCG.pdf', 33, '2026-04-09 13:18:50', '2026-04-09 13:18:50'),
(318, 6, 'Diarrah', 'Diarra', '70614093', 'Koulikoro', 'Koulikoro plateau II', 'vendue', 'cartes-identite/zoI9Hr2rqfb4yQyOXZbocDBHacR6KPoWt0ugcnRw.pdf', 33, '2026-04-09 13:19:49', '2026-04-09 13:19:49'),
(319, 2, 'Abdoulaye', 'Traoré', '91406409', 'Bamako', 'Bamako', 'vendue', 'cartes-identite/DRVlu3uDjWUdBQSbqKhrQSOZwYXjHIomh7M3J9Hd.pdf', 33, '2026-04-09 13:20:53', '2026-04-09 13:20:53'),
(320, 2, 'Aboubacar Sidiki', 'Sanogo', '83645419', 'Koulikoro', 'Koulikoro bâ', 'vendue', 'cartes-identite/umPiwwY6gls9JaFE8mmEpmw7t50VeBCRNnchV56T.pdf', 33, '2026-04-09 13:25:55', '2026-04-09 13:25:55'),
(321, 2, 'Aïssata', 'Kané', '91075741', 'Koulikoro', 'Koulikoro', 'vendue', 'cartes-identite/ehBwef2AELSuCTAEzsy6y6o7jAWGqAQFzVM5Z9ks.pdf', 33, '2026-04-09 13:28:02', '2026-04-09 13:28:02'),
(322, 7, 'Dibilirou', 'Traoré', '83632408', 'Sikasso', 'Mamassoni', 'vendue', NULL, 35, '2026-04-09 13:28:37', '2026-04-09 13:28:37'),
(323, 2, 'Ramata', 'Sanogo', '77153251', 'Sikasso', 'Sikasso', 'vendue', 'cartes-identite/6nb0TojSSLJqs8tNbsiVFOIYwXklxR04v6udZg06.pdf', 33, '2026-04-09 13:29:44', '2026-04-09 13:29:44'),
(324, 2, 'Salia', 'Sidibé', '91209676', 'Koulikoro', 'Koulikoro Souban', 'vendue', 'cartes-identite/jf7LX8Z9ZNUGvMfi0c6KsmYvGSVsCv3hoz3sNAO6.pdf', 33, '2026-04-09 13:31:29', '2026-04-09 13:31:29'),
(325, 11, 'Aïssa', 'Touré', '72222929', 'Bamako', 'Niamana', 'vendue', NULL, 10, '2026-04-09 13:36:26', '2026-04-09 13:36:26'),
(326, 8, 'Touoto toutou', 'CAMARA', '90755699', 'Bamako', 'Kalaban COURA', 'vendue', 'cartes-identite/T9y2xGTiOLnDDEVE7DfKnWy8yRSE6c2yObKi5dVz.jpg', 20, '2026-04-09 14:03:33', '2026-04-09 14:03:33'),
(327, 2, 'Moussa', 'Coulibaly', '77205140', 'Bamako', 'Djelibougou', 'vendue', NULL, 15, '2026-04-09 14:07:10', '2026-04-09 14:07:10'),
(328, 2, 'Ibelou', 'Djiguiba', '75383415', 'Bamako', 'Sotuba', 'vendue', NULL, 15, '2026-04-09 14:08:53', '2026-04-09 14:08:53'),
(329, 2, 'Salimata', 'Coulibaly', '77280036', 'Bamako', 'Razel', 'vendue', NULL, 15, '2026-04-09 14:10:30', '2026-04-09 14:10:30'),
(330, 2, 'Salimata', 'Coulibaly', '77280036', 'Bamako', 'Razel', 'vendue', NULL, 15, '2026-04-09 14:12:28', '2026-04-09 14:12:28'),
(331, 6, 'Tahirou', 'Kone', '76184874', 'Segou', 'Pelengana Nord', 'vendue', 'cartes-identite/jtaZnPsQcIJyop25Tl4Kz9rP78nUUEXrdpfqNorm.jpg', 29, '2026-04-09 14:29:59', '2026-04-09 14:29:59'),
(332, 6, 'Mariam moussa', 'Maïga', '78347363', NULL, 'Kalaban coura', 'vendue', NULL, 14, '2026-04-09 14:44:41', '2026-04-09 14:44:41'),
(333, 8, 'Saliya', 'Koné', '69678108', NULL, 'Faladié', 'vendue', NULL, 14, '2026-04-09 14:45:38', '2026-04-09 14:45:38'),
(334, 8, 'Bouna', 'Diaoune', '75654096', NULL, 'Hamdalaye Aci', 'vendue', NULL, 14, '2026-04-09 14:48:02', '2026-04-09 14:48:02'),
(335, 8, 'Kaleb', 'Kadio', '76698069', NULL, 'Kalaban Coro', 'vendue', NULL, 14, '2026-04-09 14:49:29', '2026-04-09 14:49:29'),
(336, 9, 'Mody', 'Kanoute', '+393385295', 'Italie', NULL, 'vendue', NULL, 14, '2026-04-09 14:52:28', '2026-04-09 14:52:28'),
(337, 8, 'Oumar', 'Malick', '60299633', NULL, 'Kalaban coura', 'vendue', NULL, 14, '2026-04-09 14:53:19', '2026-04-09 14:53:19'),
(338, 7, 'Maurice', 'Bagayoko', NULL, NULL, NULL, 'vendue', NULL, 14, '2026-04-09 14:55:35', '2026-04-09 14:55:35');
INSERT INTO `clients` (`id`, `type_carte_id`, `prenom`, `nom`, `telephone`, `ville`, `quartier`, `statut_carte`, `carte_identite`, `user_id`, `created_at`, `updated_at`) VALUES
(339, 8, 'Abdoulaye', 'Guinou', '76567967', NULL, 'Attbougou 1008lgt', 'vendue', NULL, 14, '2026-04-09 14:56:36', '2026-04-09 14:56:36'),
(340, 8, 'Tassine', 'Keita', '76171424', NULL, 'Niamana', 'vendue', NULL, 14, '2026-04-09 14:57:28', '2026-04-09 14:57:28'),
(341, 7, 'Djibril', 'Koné', '62011980', NULL, 'Niamana', 'vendue', NULL, 14, '2026-04-09 14:58:44', '2026-04-09 14:58:44'),
(342, 7, 'Aminata', 'Konaté', '71496523', NULL, 'Yirimadio', 'vendue', NULL, 14, '2026-04-09 14:59:55', '2026-04-09 14:59:55'),
(343, 6, 'Seckou', 'Kanté', '74948618', NULL, 'Niamana diallobougou', 'vendue', NULL, 14, '2026-04-09 15:01:05', '2026-04-09 15:01:05'),
(344, 2, 'Aissata', 'Sissoko', '72723032', 'Bamako', 'Missabougou', 'vendue', NULL, 15, '2026-04-09 15:07:50', '2026-04-09 15:07:50'),
(345, 7, 'Moussa', 'DOUMBIA', '72489429', 'Bamako', 'Djicoroni-para près de l\'école publique Dountèmè 1', 'vendue', 'cartes-identite/2sR1h7Wpaq53e20ukPi2I7SOOLKAPizuZ6MchWjp.jpg', 25, '2026-04-09 19:37:34', '2026-04-09 19:37:34'),
(346, 8, 'Kola', 'BOCOUM', '74106525', 'Bamako', 'Yirimadio yorodjanbougou près de la maison briques rouges', 'vendue', 'cartes-identite/MvCn9D4WJxGzFuo043PIgZVsn1KYnztxiAy93AMJ.jpg', 25, '2026-04-09 19:39:27', '2026-04-09 19:39:27'),
(347, 8, 'Mahamadou', 'SIDIBE', '77734633', 'Bamako', 'Banangabougou près du lycée Ibrahima Ly', 'vendue', 'cartes-identite/lm3nIckcP2OwuVjMg9VOBQxcaJaKGxwc6ddbcIoK.jpg', 25, '2026-04-09 19:41:37', '2026-04-09 19:41:37'),
(348, 7, 'Kadidia', 'Sidibe', '79384523', 'Bko', 'Magnabougou projé', 'vendue', NULL, 17, '2026-04-09 23:14:43', '2026-04-09 23:14:43'),
(349, 8, 'Oumar', 'Camara', '66714675', 'Bamako', 'Bakodjicoroni golf', 'vendue', NULL, 18, '2026-04-10 07:28:35', '2026-04-10 07:28:35'),
(350, 7, 'Noël', 'Sissoko', '78311347', 'Bamako', 'Lafiabougou', 'vendue', NULL, 18, '2026-04-10 07:29:44', '2026-04-10 07:29:44'),
(351, 8, 'Rokia', 'Koné', '73422560', 'Kayes', 'Bencounda', 'vendue', NULL, 38, '2026-04-10 07:32:54', '2026-04-10 07:32:54'),
(352, 9, 'Aichata', 'Niangadou', '79094848', 'Sotuba', 'Bamako', 'vendue', NULL, 11, '2026-04-10 07:43:26', '2026-04-10 07:43:26'),
(353, 8, 'Mori julien', 'Kone', '79016991', 'Bamako', 'Baco djicoroni', 'vendue', NULL, 11, '2026-04-10 07:49:14', '2026-04-10 07:49:14'),
(354, 9, 'Mamadou', 'Siby', '79402737', 'Bamako', 'Kalaban coro tiébanie', 'vendue', NULL, 16, '2026-04-10 07:50:14', '2026-04-10 07:50:14'),
(355, 7, 'Idriss yahya', 'Ben', '79794438', 'Bamako', 'Bacodjicoroni ACI', 'vendue', NULL, 11, '2026-04-10 08:12:32', '2026-04-10 08:12:32'),
(356, 8, 'Sadio', 'Coulibaly', '94481288', 'Bamako', 'Kalabanbougou', 'vendue', NULL, 44, '2026-04-10 09:10:04', '2026-04-10 09:10:04'),
(357, 2, 'Assetou B Diatigui', 'Diarra', '91477743', 'Bamako', NULL, 'vendue', NULL, 47, '2026-04-10 09:45:39', '2026-04-10 09:45:39'),
(358, 2, 'Ibrahima Fadimata', 'Walet', NULL, 'Bamako', NULL, 'vendue', NULL, 41, '2026-04-10 10:52:13', '2026-04-10 10:52:13'),
(359, 2, 'Toumani', 'Diallo', '76300866', 'Bamako', 'Lafiabougou', 'vendue', 'cartes-identite/brDRsQPXrJNo0u0nz0Z4dLF26yWgc9XMvK2F3xMw.pdf', 46, '2026-04-10 10:53:38', '2026-04-10 10:53:38'),
(360, 8, 'Djeneba', 'Tangara', '79049898', 'Bamako', 'Hamdallaye ACI', 'vendue', 'cartes-identite/0auynSXahq3TiuGygGKPC3g3A7oIJ5ajYe2S1hQu.jpg', 46, '2026-04-10 10:55:48', '2026-04-10 10:55:48'),
(363, 7, 'Idrissa', 'Goro', '71954941', 'Koulikoro', 'Koulikoro', 'vendue', 'cartes-identite/bGQanDCry0EfcftQrwTNbF2Lnc6Q30SZIQ2u0YAv.pdf', 33, '2026-04-10 13:08:47', '2026-04-10 13:08:47'),
(364, 2, 'Mamadou', 'Traoré', '60003939', 'Koulikoro', 'Koulikoro Souban', 'vendue', 'cartes-identite/52EgqkMg9S7W79SpEpi0NTWh3El4y23cAlmahAuR.pdf', 33, '2026-04-10 13:10:36', '2026-04-10 13:10:36'),
(366, 8, 'Abdoul Wahab', 'Samaké', '99808080', 'Koulikoro', 'Koulikoro ba', 'vendue', 'cartes-identite/eYjQRs0owPDJJ0VxlCtlNDqZ09KCOaNkbPHPcdjI.pdf', 33, '2026-04-10 13:11:58', '2026-04-10 13:11:58'),
(367, 3, 'Aliou', 'Dembélé', '91030618', 'Koulikoro', 'Koulikoro Socourani', 'vendue', 'cartes-identite/5Hk2eaT4wJCLkQER0h1k0FcyMXeWlr5MC7CiJziU.pdf', 33, '2026-04-10 13:12:54', '2026-04-10 13:12:54'),
(368, 4, 'Sory Ibrahim', 'Tekete', '76082550', 'Bamako', 'Lafiabougou', 'vendue', 'cartes-identite/aCJDmWwywLzowRZZguLQQvtu11RnySTCErJmEtNj.jpg', 46, '2026-04-10 13:16:19', '2026-04-10 13:16:19'),
(369, 11, 'Sory Ibrahim', 'Tekete', '76082550', 'Bamako', 'Lafiabougou', 'vendue', 'cartes-identite/HRAQt4PgGxCxhZRlV6TrV9ruQwTsokFqZVUq2cQi.jpg', 46, '2026-04-10 13:18:06', '2026-04-10 13:18:06'),
(370, 8, 'Mahamadou', 'Bagayoko', '83419440', 'Bamako', 'Lafiabougou', 'vendue', 'cartes-identite/6GnwWo7qx2pBp3th6Ncg0pVeZZd2OgMlqsfYbDbi.jpg', 46, '2026-04-10 13:20:46', '2026-04-10 13:20:46'),
(371, 2, 'Mahamadou', 'Bagayoko', '83419440', 'Bamako', 'Lafiabougou', 'vendue', 'cartes-identite/RhYZNsQvqm3ot6onYSl8yWeytJGfxvOmEOI6Sqmy.jpg', 46, '2026-04-10 13:22:26', '2026-04-10 13:22:26'),
(372, 4, 'Boureima', 'Toure', '76074855', 'Bamako', 'Lafiabougou', 'vendue', 'cartes-identite/aHgOZYiUOyKgzkwYN8ZfAsIrteaYPKIjDRJxvFM7.jpg', 46, '2026-04-10 13:24:28', '2026-04-10 13:24:28'),
(373, 2, 'Boureima', 'Toure', '76074855', 'Bamako', 'Lafiabougou', 'vendue', 'cartes-identite/Xs6wcVu0Yigz4ghR1k8bU2rI4ebhNUlwb5hlnAgD.jpg', 46, '2026-04-10 13:25:20', '2026-04-10 13:25:20'),
(374, 3, 'Alioun badara', 'Diarra', '74535383', NULL, NULL, 'vendue', NULL, 23, '2026-04-10 13:29:39', '2026-04-10 13:29:39'),
(375, 6, 'Mamadou', 'Sanogo', '76375897', 'Bamako', 'Lafiabougou', 'vendue', 'cartes-identite/kvYRkoesikbVmffG59qioxcmlFj2yOeWVKJSkZiT.jpg', 46, '2026-04-10 13:39:20', '2026-04-10 13:39:20'),
(376, 7, 'Mamadou', 'Sanogo', '76375897', 'Bamako', 'Lafiabougou', 'vendue', 'cartes-identite/gc4oRUkVKlBBy3hlisCLQgKslcDXDEzUt7sbDyBC.jpg', 46, '2026-04-10 13:40:23', '2026-04-10 13:40:23'),
(377, 2, 'Mamadou', 'Sanogo', '76375897', 'Bamako', 'Lafiabougou', 'vendue', 'cartes-identite/1h6UnzU29bihAfY4eU61caiTEl6HXxPmkfGViNpa.jpg', 46, '2026-04-10 13:42:06', '2026-04-10 13:42:06'),
(378, 2, 'Mamadou', 'Sidibé', '92206344', 'Bamako', 'Lafiabougou', 'vendue', 'cartes-identite/0Pg7qyIDIRrIdcdqEMq2pnVfGdtdljd7mXK0f6xS.jpg', 46, '2026-04-10 13:43:01', '2026-04-10 13:43:01'),
(379, 9, 'Lassina', 'Kouyaté', '79027877', 'Bamako', 'Lafiabougou', 'vendue', 'cartes-identite/VoB2QUfcXYTuVnre0EFEVwBKv8wJWo67HuIJx1x7.jpg', 46, '2026-04-10 13:49:49', '2026-04-10 13:49:49'),
(380, 8, 'Awa', 'Diawara', '90570690', 'Bamako', 'Lafiabougou', 'vendue', 'cartes-identite/OFMg0gM3cxiOiIQeUgmmZssuxnvi8M6kqqn1t34x.jpg', 46, '2026-04-10 13:52:21', '2026-04-10 13:52:21'),
(381, 9, 'Massaba', 'Keïta', '79222392', 'Bamako', 'Hamdallaye ACI 2000', 'vendue', 'cartes-identite/fXm7BscAAlkW0RZhDZmJqQayPe14W6Mvjsje0nol.jpg', 46, '2026-04-10 14:53:52', '2026-04-10 14:55:03'),
(382, 3, 'Rokia', 'Niamba', '76300250', 'Bamako', NULL, 'vendue', NULL, 41, '2026-04-10 15:02:08', '2026-04-10 17:43:31'),
(383, 4, 'Mamadou Djibril', 'Diawara', NULL, 'Bamako', NULL, 'vendue', NULL, 41, '2026-04-10 15:03:15', '2026-04-10 15:03:15'),
(384, 12, 'Acence de Voyage lahidou', 'Voyage et autres commerces', '76215656', 'Bamako', 'Badalabougou', 'vendue', NULL, 11, '2026-04-10 15:51:20', '2026-04-10 15:51:20'),
(385, 12, 'Intelligence Africaine', 'de sahel', '82408404', 'Bamako', 'Aci 2000', 'vendue', NULL, 11, '2026-04-10 15:54:11', '2026-04-10 15:54:11'),
(386, 6, 'Youssouf', 'Koné', '78588034', 'Dioïla', 'Tripanobougou', 'vendue', NULL, 34, '2026-04-10 16:26:00', '2026-04-10 16:26:00'),
(387, 3, 'Fatoumata', 'Coulibaly', NULL, 'Bamako', 'Kalaban coura Aci', 'vendue', NULL, 41, '2026-04-10 17:42:43', '2026-04-10 17:42:43'),
(388, 6, 'Chiaka', 'Dembele', '76042880', 'Segou', 'Sido sonincoura', 'vendue', 'cartes-identite/vEsQT1e0NgnMFZtnKB85tpUWvOAnTzHa8FTou4Vj.jpg', 29, '2026-04-10 17:58:39', '2026-04-10 17:58:39'),
(389, 4, 'Gaoussou', 'SIMPARA', '66725496#76372064', NULL, NULL, 'vendue', NULL, 13, '2026-04-10 19:31:30', '2026-04-10 19:31:30'),
(390, 7, 'Aïchata', 'Abdou', '75354671', 'Bamako', 'Kalaban Coura', 'vendue', NULL, 20, '2026-04-10 19:33:45', '2026-04-10 19:34:10'),
(391, 8, 'Lalaïcha', 'MAGASSA 1', '73044690', 'Bamako', 'Kalaban Coura', 'vendue', 'cartes-identite/E7GUNoXwBJ6W8nI8T5n2p6Cs7hNCyMp2G5VjsNSk.jpg', 20, '2026-04-10 19:36:51', '2026-04-10 19:36:51'),
(392, 8, 'Lalaïcha', 'MAGASSA 2', '73044690', 'Bamako', 'Niamakoro Courani', 'vendue', 'cartes-identite/NGxDXnmcFUfOOuqwWicIUFBcqswucOS2ni3zQfOg.jpg', 20, '2026-04-10 19:38:27', '2026-04-10 19:38:27'),
(393, 2, 'Brahima', 'Diarra', '85473638', 'Kayes', 'Yelimané', 'vendue', NULL, 38, '2026-04-10 19:43:46', '2026-04-10 19:43:46'),
(394, 2, 'Abdoul Kader', 'KONARE', '76313340', 'Bamako', NULL, 'vendue', 'cartes-identite/xeNGj9ZZYx1Y4F8ehRq0H8bfGjx6wzAkWQDBJ4PO.jpg', 20, '2026-04-10 19:51:22', '2026-04-10 19:51:22'),
(395, 2, 'Cheickna hamala', 'Sow', '78055518', 'Bamako', NULL, 'vendue', 'cartes-identite/WTsEkXHL4vqqcMbMbFmvZMiITM5gEopQ2gN07xGF.jpg', 20, '2026-04-10 19:54:29', '2026-04-10 19:54:29'),
(396, 8, 'Boubacar', 'Kodio', '78797999', 'Bamako', 'Banconi plateu', 'vendue', NULL, 19, '2026-04-10 20:51:57', '2026-04-10 20:51:57'),
(397, 7, 'Adama', 'Diallo', '60059019', 'Bamako', 'Attbougou 1008', 'vendue', NULL, 19, '2026-04-10 20:53:17', '2026-04-10 20:53:17'),
(398, 2, 'Moussa', 'SISSOKO', '92591344', 'Bamako', 'Sirakoro dounfing', 'vendue', NULL, 45, '2026-04-11 08:01:55', '2026-04-11 08:01:55'),
(399, 2, 'Sadio', 'DIARRA', '91221172', 'Bamako', 'Koulouba', 'vendue', NULL, 45, '2026-04-11 08:02:46', '2026-04-11 08:02:46'),
(400, 2, 'Abdramane', 'DESSOH', '83438356', 'Bamako', 'Hamdallaye', 'vendue', NULL, 45, '2026-04-11 08:16:56', '2026-04-11 08:16:56'),
(401, 4, 'Abdoul kader', 'DOUCOURE', '75468888', 'Bamako', 'Boulkassoumbougou', 'vendue', NULL, 45, '2026-04-11 08:22:55', '2026-04-11 08:22:55'),
(402, 2, 'Ibrahim SEGA', 'SISSOKO', '94645746', 'Bamako', 'Torokorobougou', 'vendue', NULL, 45, '2026-04-11 08:25:25', '2026-04-11 08:25:25'),
(403, 7, 'Boubacar sidiki', 'SANGARÉ', '82523637', 'Bamako', 'Baco djicoroni', 'vendue', NULL, 45, '2026-04-11 08:26:23', '2026-04-11 08:26:23'),
(404, 7, 'Drissa', 'KONE', '74589679', 'Bamako', 'Niamana ATT Bougou', 'vendue', NULL, 45, '2026-04-11 08:39:06', '2026-04-11 08:39:06'),
(405, 2, 'Amidou', 'Sanogo', '72345418', 'Koulikoro', 'Koulikoro ba', 'vendue', 'cartes-identite/gKmVL7GM7dhGOHlXGTtiuZja19HiTzmvDUPHES75.pdf', 33, '2026-04-11 08:54:56', '2026-04-11 08:54:56'),
(406, 2, 'Fodie Amara', 'Diaby', '74497060', 'Bamako', 'Kabala', 'vendue', 'cartes-identite/YDKcu4zEINGpsAt7q7GwpItiQlb9vWoyyI1NRogk.jpg', 52, '2026-04-11 08:56:47', '2026-04-11 08:56:47'),
(407, 7, 'SZ', 'Diarra services', '77898998', 'Bamako', 'Kabala', 'vendue', 'cartes-identite/D8faRAbJyJBON0QwNyq62RIs7h7KUZzGsS9B2YDj.jpg', 52, '2026-04-11 08:58:59', '2026-04-11 08:58:59'),
(408, 7, 'Abdoul wahab', 'Ouedrago', '64001733', 'Bamako', 'Kabala', 'vendue', 'cartes-identite/yPqNepLrntbRHDdkVwhf6KliGk8P6WukM3RErSAi.jpg', 52, '2026-04-11 09:00:06', '2026-04-11 09:00:06'),
(409, 8, 'Ali', 'Traoré', '76965400', 'Bamako', 'Kabala', 'vendue', 'cartes-identite/K2x4D6CG8i2hTqu2D05t2Txlk3B4hPP9R4cE8LDv.jpg', 52, '2026-04-11 09:00:53', '2026-04-11 09:00:53'),
(410, 4, 'Kossi Sodjine', 'Agamakou', '72912967', 'Bamako', 'Sirakoro', 'vendue', NULL, 41, '2026-04-11 09:10:12', '2026-04-11 09:10:12'),
(411, 4, 'Drissa', 'Bathily', '93200595', 'Bamako', 'Kati', 'vendue', NULL, 26, '2026-04-11 09:33:10', '2026-04-11 09:33:10'),
(412, 6, 'Alassane', 'CAMARA', '72779452', NULL, NULL, 'vendue', NULL, 13, '2026-04-11 09:43:24', '2026-04-11 09:43:24'),
(413, 8, 'Timbile', 'Diabira', '96172010', 'Bamako', 'Hamdallaye ACI 2000', 'vendue', 'cartes-identite/oUiW6zJKKnjFEiNW1nSZ9dRwNtXQbn68KWVKXHNn.jpg', 46, '2026-04-11 09:55:13', '2026-04-11 09:55:13'),
(414, 6, 'Mahamadou', 'Sangaré', '79434389', 'Kati', 'Fouga', 'vendue', 'cartes-identite/t4YeazokaZGV8D6AGzDuB1tkxNuM18x5q1qA63PD.jpg', 51, '2026-04-11 09:57:19', '2026-04-11 09:57:19'),
(415, 2, 'Ayouba', 'Sanogo', '79865341', 'Bamako', 'Daoudabougou', 'vendue', NULL, 41, '2026-04-11 10:09:36', '2026-04-11 10:09:36'),
(416, 4, 'Idrissa', 'Sissako', '76836969', 'Bamako', 'Lafiabougou', 'vendue', 'cartes-identite/AJAkd1VBvnXi8bTRvGbauk73NsVYzTaNkaNc1sqE.jpg', 46, '2026-04-11 11:10:28', '2026-04-11 11:10:28'),
(417, 2, 'Mohamed', 'DOUMBIA', '72654618', 'Bamako', 'Tièbani', 'vendue', NULL, 45, '2026-04-11 11:25:03', '2026-04-11 11:25:03'),
(418, 2, 'Sory Ibrahima', 'Cissé', '73398553', 'Bamako', 'Lafiabougou', 'vendue', 'cartes-identite/BbexT6iHnmUJ2Cj0hfTzuA5cyPXOkRPhidM6ig9h.jpg', 46, '2026-04-11 11:54:19', '2026-04-11 11:54:19'),
(419, 2, 'Idrissa', 'Sanou', '74 84 04 90', 'Bamako', 'Sébénikoro cela 2', 'vendue', NULL, 44, '2026-04-11 12:07:19', '2026-04-11 12:07:19'),
(420, 6, 'Mamadou', 'Traore', '78430993', 'Segou', 'Markala', 'vendue', 'cartes-identite/iEYYkfJ2rtpggkZNWIZPZt2v3VuFtmJYNvis84QS.jpg', 29, '2026-04-11 12:54:33', '2026-04-11 12:54:33'),
(421, 3, 'Aminata', 'Daou', '72196486', 'Tominian', 'TOMINIAN', 'vendue', 'cartes-identite/bLwt2ysmlZI9gCzwjZ0TqAFc8gzASYmtIhYIobvW.pdf', 31, '2026-04-11 14:24:34', '2026-04-11 14:24:34'),
(422, 3, 'Tirera', 'Youssouf', '77079707', 'Bko', 'Daoudabougou', 'vendue', NULL, 17, '2026-04-12 21:58:20', '2026-04-12 21:58:20'),
(423, 6, 'Diakite', 'Kalidou', '76088441', 'Bko', 'Moribabougou', 'vendue', NULL, 17, '2026-04-12 22:00:57', '2026-04-12 22:00:57'),
(424, 7, 'Traoré', 'Mory', '70900374', 'Bko', 'L’origine nord', 'vendue', NULL, 17, '2026-04-12 22:02:42', '2026-04-12 22:02:42'),
(425, 9, 'Fousseyni', 'Traoré', '60694620', 'Bamako', 'Sébénikoro', 'vendue', NULL, 44, '2026-04-13 07:23:07', '2026-04-13 07:23:07'),
(426, 8, 'Kader', 'Dembélé', '76337526', 'Bamako', 'Kabala', 'vendue', NULL, 16, '2026-04-13 07:23:10', '2026-04-13 07:23:10'),
(427, 7, 'Malado', 'DICKO', '66865037', 'Bamako', 'Hamdallaye R26 P194', 'vendue', NULL, 45, '2026-04-13 08:56:35', '2026-04-13 08:56:35'),
(428, 7, 'Wahabou', 'DEMBELE', '94049117', 'Bamako', 'Niomirambougou près Mosquée', 'vendue', NULL, 45, '2026-04-13 08:59:00', '2026-04-13 08:59:00'),
(429, 7, 'Maïmouna', 'THIAM', '66865036', 'Bamako', 'Hamdallaye R26 P194', 'vendue', NULL, 45, '2026-04-13 08:59:43', '2026-04-13 08:59:43'),
(430, 2, 'Ibrahima', 'Diawara', '77801725', 'Bamako', 'Korofina', 'vendue', NULL, 15, '2026-04-13 09:17:15', '2026-04-13 09:17:15'),
(431, 8, 'Mariam', 'Traoré', '94895653', 'Bamako', 'Lafiabougou', 'vendue', 'cartes-identite/h8Pl9gikIsu0uum8hmzvG4s7nYsL2MjBb2tuPukc.jpg', 46, '2026-04-13 09:54:11', '2026-04-13 09:54:11'),
(432, 2, 'Mahamadou', 'Soukouna', '98625083', 'Bamako', 'Sabalibougou', 'vendue', NULL, 47, '2026-04-13 10:02:41', '2026-04-13 10:02:41'),
(433, 7, 'Soumaila', 'Mariko', '73576326', 'Bamako', 'Wolofobougou', 'vendue', 'cartes-identite/BdZOmfodU48yrc0hig14z2yZFum96U5SiJeoyrL9.jpg', 46, '2026-04-13 10:05:06', '2026-04-13 10:05:06'),
(434, 7, 'Azanatou', 'Fofana', '78007408', 'Bamako', 'Lassa', 'vendue', 'cartes-identite/ODWK8pd7HF22QTHTJdsjKqIv3wDePpa3nvbaRsUR.jpg', 46, '2026-04-13 10:06:27', '2026-04-13 10:06:27'),
(435, 9, 'Boubacar', 'Keïta', '66733744', 'Bamako', 'Kalaban coura ACI', 'vendue', 'cartes-identite/1LlthefD40uLqCdSBRX3FfxLf4WVOFTZ5JtDudpm.jpg', 46, '2026-04-13 10:15:31', '2026-04-13 10:15:31'),
(436, 7, 'Kalilou', 'Coulibaly', '79287247', 'Bamako', 'Lafiabougou', 'vendue', 'cartes-identite/tNctGmeQkqKKxX5q6i2IvngjH1G4dm9WDEzXY2nT.jpg', 46, '2026-04-13 10:18:05', '2026-04-13 10:18:05'),
(437, 9, 'Bakoré', 'Konté', '74906025', 'Kayes', 'Liberté', 'vendue', NULL, 38, '2026-04-13 11:55:00', '2026-04-13 11:55:00'),
(438, 8, 'Aliou', 'DIARRA', '79126520', 'Bamako', 'Missala', 'vendue', NULL, 20, '2026-04-13 11:55:48', '2026-04-13 11:55:48'),
(439, 8, 'Mariam', 'KANTE', '77452625', 'Bamako', 'Kalaban ACI', 'vendue', 'cartes-identite/12TyF4J0jtMTsTSxyH54UQRJ3KoKi5VLCtzhwgIw.jpg', 20, '2026-04-13 11:57:25', '2026-04-13 11:57:25'),
(440, 6, 'Soungo', 'Coulibaly', '73592886', 'Kayes', 'Niaka Niaka', 'vendue', NULL, 38, '2026-04-13 11:57:38', '2026-04-13 11:57:38'),
(441, 6, 'Demba', 'Sissoko', '90992438', 'Kayes', 'Kayes Ndi ATT bougou', 'vendue', NULL, 38, '2026-04-13 12:01:05', '2026-04-13 12:01:05'),
(442, 2, 'Gaoussou', 'Haidara', '71212266', 'Bamako', 'Sotuba', 'vendue', NULL, 43, '2026-04-13 12:02:35', '2026-04-13 12:02:35'),
(443, 6, 'Pape Moustaphe', 'N\'diaye', '92163155', 'Kayes', 'Kayes Ndi Madinacoura', 'vendue', NULL, 38, '2026-04-13 12:03:23', '2026-04-13 12:03:23'),
(444, 7, 'Doudou Deme', 'Fall', '82010793', 'Kayes', 'Plateau', 'vendue', NULL, 38, '2026-04-13 12:09:23', '2026-04-13 12:09:23'),
(445, 2, 'Cheick Ahmed', 'Bah', '66482442', 'Bamako', 'Missabougou', 'vendue', NULL, 43, '2026-04-13 12:19:10', '2026-04-13 12:19:10'),
(446, 3, 'Amadou', 'Coulibaly', '76394377', 'Bamako', 'Doumanzana', 'vendue', NULL, 43, '2026-04-13 12:30:36', '2026-04-13 12:30:36'),
(447, 3, 'Ibrahima', 'Goumane', '70452219', 'Bamako', 'Niarela', 'vendue', NULL, 43, '2026-04-13 12:44:49', '2026-04-13 12:44:49'),
(448, 6, 'Bekaye', 'Kané', '79027010', 'Koulikoro', 'Koulikoro kayo', 'vendue', 'cartes-identite/IHA0hdyc79JGlRk97mgte7TxvsDia2Wj5tftmGpe.pdf', 33, '2026-04-13 12:45:10', '2026-04-13 12:45:10'),
(449, 6, 'Sékou', 'Traoré', '74964354', 'Bamako', 'Bamako', 'vendue', 'cartes-identite/XxykdPny3GiSH0JQF5XS4AhMji1GSOAcFmPANi9G.pdf', 33, '2026-04-13 12:46:05', '2026-04-13 12:46:05'),
(450, 3, 'Aboubacar', 'Traoré', '72080725', 'Koulikoro', 'Koulikoro', 'vendue', 'cartes-identite/8NdGP7LzWwbESJiyD9sBWifGGIuTWnXdeSVemdSe.pdf', 33, '2026-04-13 12:46:54', '2026-04-13 12:46:54'),
(451, 2, 'Dialla', 'Fofana', '66265395', 'Bamako', 'Doumanzana', 'vendue', NULL, 43, '2026-04-13 12:52:14', '2026-04-13 12:52:14'),
(452, 2, 'Oumou', 'Traore', '92122908', 'Bamako', 'Torokorobougou', 'vendue', NULL, 41, '2026-04-13 13:00:58', '2026-04-13 13:00:58'),
(453, 2, 'Moussa', 'Konate', '71317786', 'Bamako', 'Kalaban coura Aci', 'vendue', NULL, 41, '2026-04-13 13:01:53', '2026-04-13 13:01:53'),
(454, 2, 'Mamadou', 'Konate', '83180482', 'Bamako', 'Kabala', 'vendue', NULL, 43, '2026-04-13 13:07:02', '2026-04-13 13:07:02'),
(455, 2, 'Aboubacar', 'Konate', '89743958', 'Bamako', 'Guarantiguibougou', 'vendue', NULL, 43, '2026-04-13 13:14:30', '2026-04-13 13:14:30'),
(456, 7, 'cheick Oumar', 'Ba', '75751258', 'Bamako', 'Kalabancoro Rue 609 Porte 345', 'vendue', NULL, 44, '2026-04-13 13:14:54', '2026-04-13 13:14:54'),
(457, 2, 'Harouna', 'KOITA', '70418841', 'Bamako', 'Badialan 1 R469 P31', 'vendue', NULL, 45, '2026-04-13 13:20:59', '2026-04-13 13:20:59'),
(458, 2, 'Fatoumata', 'TRAORÉ', '91846693', 'Bamako', 'Hamdallaye aci Bocoum', 'vendue', NULL, 45, '2026-04-13 13:21:56', '2026-04-13 13:21:56'),
(459, 11, 'Groupe', 'Privilège', '79253550', 'Bamako', 'Kabala', 'vendue', 'cartes-identite/h3GvdmKkxxsDW0YgMZm4xNC04WIgouTxqtWcfRUe.jpg', 52, '2026-04-13 14:33:55', '2026-04-13 14:33:55'),
(460, 8, 'Mahamadou', 'HAÏDARA', '72144349', 'Bamako', 'Kalaban', 'vendue', 'cartes-identite/SqoXqQMMNMvwxUr2tJwryw0JFqM0k65HIeqAv5U1.jpg', 20, '2026-04-13 14:34:49', '2026-04-13 14:34:49'),
(461, 11, 'Mohamed', 'Maiga', '76171688', 'Segou', 'Bagadadji', 'vendue', 'cartes-identite/zsdAZC3Ebuy4KX6lAtrtOsYg7rsfCc938TzDtZKf.jpg', 29, '2026-04-13 15:02:02', '2026-04-13 15:02:02'),
(462, 2, 'Ibrahim werner', 'Traore', '77771426', 'Bamako', 'Kalaban Coro', 'vendue', NULL, 47, '2026-04-13 15:20:02', '2026-04-13 15:20:02'),
(463, 2, 'Souleymane', 'Diarra', '77228825', 'Bamako', 'Kabala', 'vendue', 'cartes-identite/tT6XxyOkW8ApgrbAsQDRvsCCz2yqL17KkAr1OkJc.jpg', 52, '2026-04-13 15:37:20', '2026-04-13 15:37:20'),
(464, 8, 'Fassande', 'Dansoko', '63683343', 'Bamako', 'Niamana', 'vendue', NULL, 19, '2026-04-13 15:38:36', '2026-04-13 15:38:36'),
(465, 7, 'Daiyi', 'Macalou', '76381674', 'Bamako', 'Kabala', 'vendue', 'cartes-identite/yrbPDG48ugGKi2MTCbE6ASnWKkmBKb0yeDhdTF3E.jpg', 52, '2026-04-13 15:40:39', '2026-04-13 15:40:39'),
(466, 9, 'Abdramane', 'Keita', '79827546', 'Bamako', 'Sebenikoro', 'vendue', NULL, 11, '2026-04-13 16:37:44', '2026-04-13 16:37:44'),
(467, 7, 'Sitan', 'Diarra', '83401288', 'Bamako', 'Sebenikoro', 'vendue', NULL, 11, '2026-04-13 16:39:15', '2026-04-13 16:39:15'),
(468, 10, 'Amadou', 'Toure', '76880586', 'Bamako', 'Dialobougou', 'vendue', NULL, 11, '2026-04-13 16:41:09', '2026-04-13 16:41:09'),
(469, 4, 'Mariam Ousmane', 'Diallo', '79293705', 'Bamako', 'Aci', 'vendue', NULL, 11, '2026-04-13 16:42:20', '2026-04-13 16:42:20'),
(470, 4, 'Mariam Ousmane', 'Diallo', '79293705', 'Bamako', 'Aci', 'vendue', NULL, 11, '2026-04-13 16:43:43', '2026-04-13 16:43:43'),
(471, 6, 'Mamadou', 'Thiam', '76302912', 'Bamako', 'Lafiabougou', 'vendue', NULL, 11, '2026-04-13 16:45:07', '2026-04-13 16:45:07'),
(472, 7, 'Coulibaly', 'Theodore sibiri', '92289221', 'Bamako', 'Taliko', 'vendue', NULL, 11, '2026-04-13 16:47:18', '2026-04-13 16:47:18'),
(473, 9, 'Rokiya', 'Dembele', '70406364', 'Bamako', 'Yirimadjo', 'vendue', NULL, 11, '2026-04-13 16:48:54', '2026-04-13 16:48:54'),
(474, 11, 'Dionge', 'Bombite', '76305708', 'Bamako', 'Taliko', 'vendue', NULL, 11, '2026-04-13 16:52:32', '2026-04-13 16:52:32'),
(475, 12, 'Amadou', 'Bassoum', '76-21-56-56', 'Bamako', 'Badalabougou', 'vendue', NULL, 11, '2026-04-13 16:54:24', '2026-04-13 16:54:24'),
(476, 12, 'MOhamed Falion', 'N\'Dlaye', '82-40-84-04', 'Bamako', 'Aci', 'vendue', NULL, 11, '2026-04-13 16:55:51', '2026-04-13 16:55:51'),
(477, 8, 'M’Bafinda', 'FOFANA', '76264015', 'Bamako', 'N’tomokorobougou R502 P92', 'vendue', NULL, 45, '2026-04-13 17:22:31', '2026-04-13 17:22:31'),
(478, 6, 'Oumar', 'DIAKITE', '76923113', 'Bamako', 'Doumazana R367 P11408', 'vendue', NULL, 45, '2026-04-13 17:23:30', '2026-04-13 17:23:30'),
(479, 6, 'Abdoulaye Roger', 'OURA', '79512183', 'Bamako', 'N’tomikorobougou R507 P206', 'vendue', NULL, 45, '2026-04-13 17:24:42', '2026-04-13 17:24:42'),
(480, 3, 'Cheick Oumar', 'DIARRA', '76133077', 'Bamako', 'Sirakoro Dounfing', 'vendue', NULL, 45, '2026-04-13 17:25:50', '2026-04-13 17:25:50'),
(481, 3, 'Yahaya', 'Bagayoko', '76723305', 'Bamako', 'Korofina', 'vendue', NULL, 15, '2026-04-13 17:40:04', '2026-04-13 17:40:04'),
(482, 3, 'N’daou', 'Amadou', '91446991', NULL, NULL, 'vendue', NULL, 23, '2026-04-13 17:40:42', '2026-04-13 17:40:42'),
(483, 4, 'Mamadou', 'Sidibe', '75054345', NULL, NULL, 'vendue', NULL, 23, '2026-04-13 17:41:34', '2026-04-13 17:41:34'),
(484, 7, 'Yoro', 'Niare', '75959231', 'Bamako', 'Hamdallaye', 'vendue', NULL, 18, '2026-04-14 07:29:39', '2026-04-14 07:29:39'),
(485, 4, 'Boulaye', 'Kebe', '76443574', 'Bamako', 'Quinzabougou', 'vendue', NULL, 18, '2026-04-14 07:30:39', '2026-04-14 07:30:39'),
(486, 3, 'Siaka', 'Keita', '75319068', 'Kati', 'Kati', 'vendue', NULL, 18, '2026-04-14 07:31:22', '2026-04-14 07:31:22'),
(487, 2, 'Sinaly', 'Konaté', '76443500', 'Bamako', 'Quinzambougou', 'vendue', NULL, 43, '2026-04-14 08:22:26', '2026-04-14 08:22:26'),
(488, 2, 'Housseini', 'Keita', '79433103', 'Bamako', 'Korofina sud', 'vendue', NULL, 43, '2026-04-14 09:01:06', '2026-04-14 09:01:06'),
(489, 4, 'Marcel kaba', 'Diarra', '74001490', 'Kati', 'Malibougou', 'vendue', NULL, 43, '2026-04-14 10:15:34', '2026-04-14 10:15:34'),
(490, 7, 'Baba Ibrahim', 'Cissé', '76825184', 'Tombouctou', 'Abaradjou', 'vendue', NULL, 36, '2026-04-14 10:15:35', '2026-04-14 10:15:35'),
(491, 3, 'Moriba', 'Sangaré', '93936402', 'Bamako', 'Dialakorodji', 'vendue', NULL, 43, '2026-04-14 10:16:28', '2026-04-14 10:16:28'),
(492, 9, 'Fily', 'Konaté', '82492910', 'Bamako', 'Tiébani près du fleuve', 'vendue', NULL, 16, '2026-04-14 10:31:35', '2026-04-14 10:31:35'),
(493, 8, 'Daouda', 'Diarra', '73765771', 'Bamako', 'Kalaban coro dougoucoro', 'vendue', NULL, 16, '2026-04-14 10:33:18', '2026-04-14 10:33:18'),
(494, 7, 'Samba', 'N\'DJIM', '78735318', NULL, NULL, 'vendue', NULL, 13, '2026-04-14 11:27:50', '2026-04-14 11:27:50'),
(495, 4, 'Karim', 'Diarra', '83333232', 'Bamako', 'Kati', 'vendue', NULL, 26, '2026-04-14 11:32:27', '2026-04-14 11:32:27'),
(496, 8, 'Aly', 'Coulibaly', '78638283', 'Bamako', 'Sébénikoro secteur 1', 'vendue', NULL, 44, '2026-04-14 11:41:23', '2026-04-14 11:41:23'),
(497, 9, 'Ousmane', 'Diabaté', '89726042', 'Bamako', 'Lafiabougou', 'vendue', 'cartes-identite/NVFb6s2xmsyRNgsFsPUdCo48sJEpUUjiDCfdxRxp.jpg', 46, '2026-04-14 11:45:45', '2026-04-14 11:45:45'),
(498, 9, 'Hamady', 'Traoré', '85008833', 'Bamako', 'Lafiabougou', 'vendue', 'cartes-identite/HvP7AXViN9x6FOFvfiICfH1jPcNdceaEXZRvOHcC.jpg', 46, '2026-04-14 11:46:50', '2026-04-14 11:46:50'),
(499, 3, 'Issa', 'Wadiou', '68667707', 'Bamako', 'Lafiabougou', 'vendue', 'cartes-identite/y5dZnfhbfUzRgpCkwwsi1IxxtSXZooK7JVvztsiM.jpg', 46, '2026-04-14 11:47:53', '2026-04-14 11:47:53'),
(500, 8, 'Moussa', 'Dougoune', '76115864', 'Bamako', 'Lassa', 'vendue', 'cartes-identite/1zcbg7KFECTNvHDBmvwa0gbYWEc2b030fMnpxlGj.jpg', 46, '2026-04-14 11:49:28', '2026-04-14 11:49:28'),
(501, 9, 'Drissa', 'Sissako', '76836969', 'Bamako', 'Lafiabougou', 'vendue', 'cartes-identite/uQRk3C5zRtyp1DzUgvrv9P15EAMrbMrbLJ3THJAw.jpg', 46, '2026-04-14 11:51:16', '2026-04-14 11:51:16'),
(502, 8, 'Moussa', 'KONE', '94905653', 'Bamako', 'Kalaban', 'vendue', NULL, 20, '2026-04-14 11:54:19', '2026-04-14 11:54:19'),
(503, 8, 'Noufou', 'Traoré', '79 02 74 78', 'Bamako', 'Baguinéda', 'vendue', 'cartes-identite/25NLmDzbP5mknCErB6hWIuxmSgw4KgaYAmYtSJHV.pdf', 33, '2026-04-14 11:58:35', '2026-04-14 11:58:35'),
(504, 8, 'Lassine', 'Doumbia', '76179276', 'Koulikoro', 'Koulikoro', 'vendue', 'cartes-identite/ocLn74As55z8WAyqjK1gyjtUCN8u3k3G4NzXUR6M.pdf', 33, '2026-04-14 12:00:20', '2026-04-14 12:00:20'),
(505, 2, 'Salomon', 'Mounkoro', '72531431', 'Koulikoro', 'Koulikoro', 'vendue', 'cartes-identite/aHENuxDQQsekOPSH3GFEiXwsmOFDHPc4Tfjar0cF.pdf', 33, '2026-04-14 12:01:59', '2026-04-14 12:01:59'),
(506, 2, 'Fatimata Bintou', 'BERTHE', '74660058', NULL, NULL, 'vendue', NULL, 13, '2026-04-14 12:07:25', '2026-04-14 12:07:25'),
(507, 7, 'Sidy', 'TRAORÉ', '79525497', 'Bamako', 'Niamakoro Diallobougou', 'vendue', NULL, 20, '2026-04-14 12:39:01', '2026-04-14 12:39:01'),
(508, 2, 'Sory Ibrahim', 'Touré', '63595405', 'Bamako', 'Niarela', 'vendue', NULL, 43, '2026-04-14 13:50:23', '2026-04-14 13:50:23'),
(509, 4, 'Soya', 'N’diaye', NULL, NULL, NULL, 'vendue', NULL, 21, '2026-04-14 14:06:10', '2026-04-14 14:06:10'),
(510, 4, 'Boubacar yassary', 'Sanogo', '75143019', NULL, NULL, 'vendue', NULL, 21, '2026-04-14 14:10:15', '2026-04-14 14:10:15'),
(511, 6, 'Mohamed Aly', 'Ould Ibrahim', '92782394', 'Sikasso', 'Wayerma 2', 'vendue', NULL, 35, '2026-04-14 14:10:54', '2026-04-14 14:10:54'),
(512, 2, 'Ibrahim', 'Doumbia', '78488027', 'Bamako', 'Doumazana', 'vendue', NULL, 15, '2026-04-14 14:10:58', '2026-04-14 14:10:58'),
(513, 2, 'Abdoulaye', 'Coulibaly', '93785504', 'Bamako', 'Banconi', 'vendue', NULL, 15, '2026-04-14 14:12:08', '2026-04-14 14:12:08'),
(514, 2, 'Diarra', 'Sekou mahamad', '74746163', 'Bamako', 'Razel', 'vendue', NULL, 15, '2026-04-14 14:14:03', '2026-04-14 14:14:03'),
(515, 2, 'Amadou', 'Traoré', '79084732', 'Bamako', 'Yirimadio', 'vendue', NULL, 43, '2026-04-14 14:28:27', '2026-04-14 14:28:27'),
(516, 11, 'Bayahaya', 'DOUMBIA', '66754106', 'Bamako', 'Hamdallaye R29 P472', 'vendue', NULL, 45, '2026-04-14 14:55:59', '2026-04-14 14:55:59'),
(517, 9, 'Brehima', 'Sarambounou', '72334908', 'Kayes', 'Koussané', 'vendue', NULL, 38, '2026-04-14 15:29:36', '2026-04-14 15:29:36'),
(518, 2, 'Idrissa', 'Keita', '75698222', 'Kayes', 'Lafiabougou sud', 'vendue', NULL, 38, '2026-04-14 15:32:03', '2026-04-14 15:32:03'),
(519, 13, 'Pharmacie', 'Guidio Almamy', '76028296', 'Segou', 'ATT Bougou', 'vendue', 'cartes-identite/qSlBDHp6uHsJgSoLtM6sp0fyg9bkfJdrF1nk4u1T.jpg', 29, '2026-04-14 15:52:20', '2026-04-14 15:52:20'),
(520, 6, 'Assitan', 'Diarra', '74028189', 'Segou', 'Pelengana sud', 'vendue', 'cartes-identite/HvP7x2FlVKMdVtGFSo7AbpSCEzCc5e4N68ZlmSaL.jpg', 29, '2026-04-14 15:54:19', '2026-04-14 15:54:19'),
(521, 2, 'Salamata dite salleye', 'Seydou', '91- 07-01-25', 'Bamako', 'Lafiabougou', 'vendue', NULL, 11, '2026-04-14 17:17:55', '2026-04-14 17:17:55'),
(522, 7, 'Nabilaye makan', 'Keita', '78-10-32-32', 'Bamako', 'Sirakoro', 'vendue', NULL, 11, '2026-04-14 17:19:25', '2026-04-14 17:19:25'),
(523, 7, 'Papa konimba', 'Coulibaly', '83689356', 'Bamako', 'Lafiabougou', 'vendue', NULL, 11, '2026-04-14 17:21:54', '2026-04-14 17:21:54'),
(524, 7, 'Cheick oumar tidiane', 'Gako', '73-20.54-98', 'Bamako', 'Magnabougou', 'vendue', NULL, 11, '2026-04-14 17:24:28', '2026-04-14 17:24:28'),
(525, 3, 'Oumar', 'Bocoum', '75555520', 'Bamako', 'Kabala', 'vendue', 'cartes-identite/vZnl4BNFirAWBbwfXJ9Yyo6aUxh9mxEDQzrphCCk.jpg', 52, '2026-04-14 17:56:26', '2026-04-14 17:56:26'),
(526, 8, 'Mahamadou', 'Gassama', '93196581', 'Bamako', 'Kabala', 'vendue', 'cartes-identite/jWczrWvzWScW51VXKGz0RO2NKrNRFs000qxkNyRk.jpg', 52, '2026-04-14 17:58:27', '2026-04-14 17:58:27'),
(527, 2, 'Makan', 'Diop', '79017057', 'Bamako', 'Niamana', 'vendue', NULL, 10, '2026-04-14 18:07:13', '2026-04-14 18:07:13'),
(528, 2, 'Fatoumata', 'Diaby', '76604814', 'Bamako', 'Mali', 'vendue', NULL, 10, '2026-04-14 18:08:00', '2026-04-14 18:08:00'),
(529, 8, 'Mohamed A T', 'Kante', '84169611', 'Bamako', 'N’tabacoro', 'vendue', NULL, 10, '2026-04-14 18:09:21', '2026-04-14 18:09:21'),
(530, 6, 'Omorou', 'Almahadi', '79412915', 'Bamako', 'N’tabacoro', 'vendue', NULL, 10, '2026-04-14 18:10:29', '2026-04-14 18:10:29'),
(531, 7, 'Brehima', 'Bouare', '75618500', 'Bamako', 'Mali', 'vendue', NULL, 10, '2026-04-14 18:11:21', '2026-04-14 18:11:21'),
(532, 9, 'Mahamane', 'Haidara', '98480288', 'Bamako', 'Sangarebougou marseille', 'vendue', NULL, 19, '2026-04-14 18:18:17', '2026-04-14 18:18:17'),
(533, 3, 'Mamadou', 'Kone', '73005434', 'Bamako', 'Attbougou', 'vendue', NULL, 10, '2026-04-14 18:54:52', '2026-04-14 18:54:52'),
(534, 8, 'Drissa', 'Kone', '69605027', 'San', 'Santoro', 'vendue', 'cartes-identite/vQu1ZfMi2EqWX1BTDlvPL2TNozmfpPwcQmDuFmTk.pdf', 31, '2026-04-14 22:08:15', '2026-04-14 22:08:15'),
(535, 8, 'Dani', 'Dembele', '62565706', 'San', 'Parana', 'vendue', 'cartes-identite/9KMBtLtbry9BDmyBX5emb7gBzbnyXuttZzbKkuGv.pdf', 31, '2026-04-14 22:13:09', '2026-04-14 22:13:09'),
(536, 7, 'Barnabas', 'Daou', '74137387', 'San', 'Hamdalaye', 'vendue', 'cartes-identite/D3GAgAwbpp90rqawnRIEzY4QQG6h5ItbUSctyMKV.pdf', 31, '2026-04-14 22:15:30', '2026-04-14 22:15:30'),
(537, 9, 'Toumkara Awa', 'dite Tenin', '73075106', 'San', 'Médine', 'vendue', 'cartes-identite/vkWfEJPpv88taTLJN1PIF2niuUmU7OqA4l6lHjcA.pdf', 31, '2026-04-14 22:18:58', '2026-04-14 22:18:58'),
(538, 6, 'Chiaka', 'SIDIBE', '71805908', 'Bamako', 'Yirimadio près de l\' ancienne poste', 'vendue', 'cartes-identite/qR41um1MprdY0dVSAqs0FOts9o9qtHRwx7mkp1n2.jpg', 25, '2026-04-14 22:34:03', '2026-04-14 22:43:09'),
(539, 7, 'Sadio', 'YAITO', '75644930', 'Bamako', 'Djicoroni-para', 'vendue', 'cartes-identite/GF8VnNZvf14RNzQbVAMACKkInfFVevvSDmXACjzn.jpg', 25, '2026-04-14 22:36:07', '2026-04-14 22:36:07'),
(540, 7, 'Nankama', 'KEITA', '75013899', 'Bamako Hamdallaye ACI2000 près de la pharmacie Bocar SIDIBE', NULL, 'vendue', 'cartes-identite/fdaBpZsnsZ2Dw1sC9DgNduqfJ65Wq4DbbQhN1eJw.jpg', 25, '2026-04-14 22:38:11', '2026-04-14 22:38:11'),
(541, 8, 'Oumou', 'DENA', '78635200', 'Bamako', 'Quinzambougou', 'vendue', 'cartes-identite/otf6peLDhAAd5R2TZlB24hy8RS2SCnsPpq05JjWo.jpg', 25, '2026-04-14 22:39:53', '2026-04-14 22:39:53'),
(542, 7, 'Arouna', 'CAMARA', '76761642', 'Bamako', 'Djicoroni-para près de la mairie', 'vendue', 'cartes-identite/KYOsC5Twf7WM2Ex63w4WicJan4WBRuNhUgocQ7Rz.jpg', 25, '2026-04-14 22:41:27', '2026-04-14 22:41:27'),
(543, 9, 'Fatoumata', 'Traore', '97989468', 'Bamako', NULL, 'vendue', NULL, 55, '2026-04-15 07:51:21', '2026-04-15 07:51:21'),
(544, 7, 'Maïmouna', 'Keïta', '76656548', 'Bamako', NULL, 'vendue', NULL, 55, '2026-04-15 07:53:26', '2026-04-15 07:53:26'),
(545, 3, 'Massila', 'Sackone', '72361227', 'Bamako', NULL, 'vendue', NULL, 55, '2026-04-15 07:57:21', '2026-04-15 07:57:21'),
(546, 3, 'Jean paul', 'Sangaré', '76015536', 'Bamako', NULL, 'vendue', NULL, 55, '2026-04-15 08:00:59', '2026-04-15 08:00:59'),
(547, 9, 'Laye', 'Diakité', '73075615', 'Bamako', 'Faladje', 'vendue', NULL, 55, '2026-04-15 08:03:27', '2026-04-15 08:03:27'),
(548, 3, 'Kalilou', 'Touré', '99482537', 'Bamako', 'Kabala', 'vendue', 'cartes-identite/mKgdZJ8sE7FZjNTtMb0fQRKwJVUDqJmUXJr1NKNY.jpg', 52, '2026-04-15 08:37:51', '2026-04-15 08:37:51'),
(549, 8, 'Hama', 'Maïga', '66733831', 'Bamako', 'Kabala', 'vendue', NULL, 52, '2026-04-15 08:39:49', '2026-04-15 08:39:49'),
(550, 2, 'Abdourazakou', 'Bâ', '75134466', 'Bamako', 'N\'tabaco attbougou', 'vendue', NULL, 50, '2026-04-15 08:47:10', '2026-04-15 08:47:10'),
(551, 2, 'Allaye', 'Tembely', '70756000', 'Bamako', 'Faladie', 'vendue', NULL, 50, '2026-04-15 08:51:47', '2026-04-15 08:51:47'),
(552, 7, 'Mamadou', 'Lam', '73308689', 'Bamako', 'Lafiabougou', 'vendue', 'cartes-identite/UqdewhkAc16zAOqfdUXUYITHYnsVeOhPJ1IDfLK4.jpg', 46, '2026-04-15 09:07:19', '2026-04-15 09:07:19'),
(553, 7, 'Alpha', 'Kansaye', '71678278', 'Bamako', 'Hamdallaye', 'vendue', NULL, 18, '2026-04-15 09:10:57', '2026-04-15 09:10:57'),
(554, 4, 'Nouhoum', 'Coulibaly', '69240688', 'Bamako', 'Hamdallaye', 'vendue', NULL, 18, '2026-04-15 09:11:41', '2026-04-15 09:11:41'),
(555, 13, 'Djibril', 'Camara', '76202955', 'Bamako', 'Ouinzindougou', 'vendue', NULL, 44, '2026-04-15 09:16:37', '2026-04-15 09:16:37'),
(556, 4, 'Aly', 'Bocoum', '66715047', NULL, NULL, 'vendue', NULL, 14, '2026-04-15 09:32:52', '2026-04-15 09:32:52'),
(557, 2, 'Amadou', 'Konare', '64824949', NULL, NULL, 'vendue', NULL, 21, '2026-04-15 09:48:43', '2026-04-15 09:48:43'),
(558, 4, 'Oumar', 'Bathily', '77774141', NULL, NULL, 'vendue', NULL, 21, '2026-04-15 09:51:05', '2026-04-15 09:51:05'),
(559, 3, 'Oumar', 'Bathily', '77774141', NULL, NULL, 'vendue', NULL, 21, '2026-04-15 09:51:32', '2026-04-15 09:51:32'),
(560, 4, 'Odile Patricia Virginie Philippe', 'Diane deberay', '94949696', NULL, NULL, 'vendue', NULL, 21, '2026-04-15 09:55:35', '2026-04-15 09:55:35'),
(561, 2, 'Andiouro', 'Doumbo', '76339071', 'Bamako', 'Hippodrome', 'vendue', NULL, 43, '2026-04-15 10:04:03', '2026-04-15 10:04:03'),
(562, 2, 'Mamadou Djibril', 'Diallo', '79021292', 'Bamako', 'Niarela', 'vendue', NULL, 43, '2026-04-15 10:04:51', '2026-04-15 10:04:51'),
(563, 3, 'Mohamed', 'Haïdara', '79207687', NULL, 'Magnabougou', 'vendue', NULL, 14, '2026-04-15 10:23:06', '2026-04-15 10:23:06'),
(564, 2, 'Aba', 'Diarra', '93562137', 'Bamako', 'Sabalibougou', 'vendue', NULL, 47, '2026-04-15 10:23:35', '2026-04-15 10:23:35'),
(565, 2, 'Issa', 'Sotbar', '76498291', 'Bamako', 'Golf', 'vendue', NULL, 50, '2026-04-15 10:53:08', '2026-04-15 10:53:08'),
(566, 8, 'Fousseny', 'Konaté', '77481958', 'Bamako', 'Sebenicoro', 'vendue', 'cartes-identite/LslTGQGcXkIvYzoL9F7jFmHuhjlzeOOEBcDX1A5t.jpg', 46, '2026-04-15 10:53:32', '2026-04-15 10:53:32'),
(567, 8, 'Amidou', 'Sidibé', '72785296', 'Bamako', 'Lafiabougou', 'vendue', 'cartes-identite/QEch8Eev5FJP54LAiJPGn1KWrsOZ9C8G1xQQrVXz.jpg', 46, '2026-04-15 11:07:15', '2026-04-15 11:07:15'),
(568, 2, 'Abdoul AZIZ', 'COULIBALY', '83631305', 'Bamako', 'Sangarebougou', 'vendue', NULL, 45, '2026-04-15 11:34:57', '2026-04-15 11:34:57'),
(569, 2, 'Gaoussou', 'Coulibaly', '74193518', 'Bamako', 'Sotuba bougouba', 'vendue', NULL, 41, '2026-04-15 11:36:25', '2026-04-15 11:36:25'),
(570, 2, 'Abdrahamane', 'Arfagala', '79459948', 'Bamako', 'Kabala', 'vendue', 'cartes-identite/HewdrAedBVADsxrjz0zcZG8NS3MVByctlxGoFX9Y.jpg', 52, '2026-04-15 11:39:36', '2026-04-15 11:39:36'),
(571, 9, 'Anzoumana', 'TOURÉ', '93852815', 'Kalaban coura', 'Bamako', 'vendue', NULL, 20, '2026-04-15 11:58:40', '2026-04-15 11:58:40'),
(572, 2, 'Cheickna', 'Ouattara', '70355565', 'Bamako', 'Koulouba', 'vendue', NULL, 43, '2026-04-15 11:59:32', '2026-04-15 11:59:32'),
(573, 2, 'Lamine bassirou', 'Sall', '66799900', 'Bamako', 'Quinzambougou', 'vendue', NULL, 43, '2026-04-15 12:15:13', '2026-04-15 12:15:13'),
(574, 2, 'Moussa salif', 'Cisse', '76609560', 'Kita', 'Kolibougou', 'vendue', NULL, 37, '2026-04-15 12:27:00', '2026-04-15 12:27:00'),
(575, 6, 'Sekou sallah', 'Diebkile', '75796044', 'Kita', 'Kofoulabe', 'vendue', NULL, 37, '2026-04-15 12:30:41', '2026-04-15 12:30:41'),
(576, 2, 'Ibrahim wazir', 'Fofana', '77777710', 'Bamako', 'Bagadadji', 'vendue', NULL, 43, '2026-04-15 12:39:34', '2026-04-15 12:39:34'),
(577, 3, 'Modibo', 'Camara', '78301046', 'Kita', 'Kolibougou', 'vendue', NULL, 37, '2026-04-15 12:43:57', '2026-04-15 12:43:57'),
(578, 3, 'Aïssata ousmane', 'Djire', '79086493', 'Bamako', 'Bacon djikoroni Aci', 'vendue', NULL, 55, '2026-04-15 13:07:32', '2026-04-15 13:07:32'),
(579, 3, 'Ibrahima', 'Diallo', '75110320', 'Bamako', 'Kalaban coura Aci', 'vendue', NULL, 26, '2026-04-15 13:07:45', '2026-04-15 13:07:45'),
(580, 3, 'Seydou', 'Bathily', '76417095', 'Bamako', NULL, 'vendue', NULL, 55, '2026-04-15 13:09:05', '2026-04-15 13:09:05'),
(581, 4, 'Amadou ousmane', 'Toure', '70406161', 'Bamako', 'Kalaban Coro adken', 'vendue', NULL, 26, '2026-04-15 13:09:08', '2026-04-15 13:09:08'),
(582, 3, 'Mahamadou', 'Sissoko', '78244224', 'Bamako', NULL, 'vendue', NULL, 55, '2026-04-15 13:10:17', '2026-04-15 13:10:17'),
(583, 3, 'Bakaye', 'Goro', '77783531', 'Koulikoro', 'Koulikoro Gare', 'vendue', 'cartes-identite/7RIB5nMVefS2nTQWjvfstO328V4RruYiScVzw7uB.pdf', 33, '2026-04-15 13:34:34', '2026-04-15 13:34:34'),
(584, 8, 'Seriba', 'Diallo', '79167382', 'Koulikoro', 'Koulikoro Souban', 'vendue', 'cartes-identite/Xybv8meQ9sbChTGuHJZM0P6jW2j1dKhfEj7jnBt2.pdf', 33, '2026-04-15 13:36:53', '2026-04-15 13:36:53'),
(585, 7, 'Souleymane', 'Samaké', '70254014', 'Koulikoro', 'Koulikoro', 'vendue', 'cartes-identite/ex8T6i6xKsoBwyKdrmQcWNaFxbY0Fe6SToDDhoAT.pdf', 33, '2026-04-15 13:38:41', '2026-04-15 13:38:41'),
(586, 3, 'Ousmane thierno', 'Ball', '76244434', 'Bamako', 'Koulouba', 'vendue', NULL, 15, '2026-04-15 13:41:03', '2026-04-15 13:41:03'),
(587, 8, 'Balla', 'Sangare', '79442492', 'Bamako', 'Senou', 'vendue', NULL, 50, '2026-04-15 13:49:54', '2026-04-15 13:49:54'),
(588, 8, 'Mamadou', 'Traoré', '76250515', 'Koulikoro', 'Koulikoro', 'vendue', 'cartes-identite/b3eDLWDEN1kPGfi1hDiihCme80oCMM3u33DN6yr3.pdf', 33, '2026-04-15 13:51:22', '2026-04-15 13:51:22'),
(589, 3, 'Fousseiny', 'Djigue', '76743939', 'Bamako', 'Lafiabougou', 'vendue', 'cartes-identite/cy66mmCoFO98M6Ro1YZ1pJ1u3CrMAeIvcA3NtaI6.jpg', 46, '2026-04-15 13:58:25', '2026-04-15 13:58:25'),
(590, 3, 'Mamodesen Marie Josette', 'Ketty', '76036869', 'Bamako', 'Niarela', 'vendue', NULL, 43, '2026-04-15 14:09:15', '2026-04-15 14:09:15'),
(591, 8, 'Nounou', 'ABDOULKADER', '71370721', 'Bamako', 'Kalaban', 'vendue', 'cartes-identite/RiFNxC68On1pzIM653ssI671elGhHU2NOqYHNxW2.jpg', 20, '2026-04-15 14:09:34', '2026-04-15 14:09:34'),
(592, 2, 'Mamodesen Marie Josette', 'Ketty', '76036869', 'Bamako', 'Niarela', 'vendue', NULL, 43, '2026-04-15 14:10:03', '2026-04-15 14:10:03'),
(593, 4, 'Mamodesen Marie Josette', 'Ketty', '76036869', 'Bamako', 'Niarela', 'vendue', NULL, 43, '2026-04-15 14:10:48', '2026-04-15 14:10:48'),
(594, 3, 'Ibrahim', 'Sacko', '77897344', 'Kati', 'Kôkô plateau', 'vendue', NULL, 51, '2026-04-15 14:17:50', '2026-04-15 14:17:50'),
(595, 8, 'Goundo', 'Cissoko', '76739711', 'Bamako', 'Baco djicoroni', 'vendue', NULL, 16, '2026-04-15 14:39:36', '2026-04-15 14:39:36'),
(596, 8, 'Brehima', 'Macalou', '+393282305387', 'Bamako', 'Kalaban coro mairie', 'vendue', NULL, 16, '2026-04-15 14:42:02', '2026-04-15 14:42:02'),
(597, 2, 'Fatoumata', 'Djire', '94464610', 'Bamako', 'Banconi', 'vendue', NULL, 43, '2026-04-15 14:43:06', '2026-04-15 14:43:06'),
(598, 2, 'Boufoune', 'Traoré', '76396218', 'Bamako', 'Sogoniko', 'vendue', 'cartes-identite/Ed4YsNzRPPXPw0kOppb34Dfl6gVHblKzDROXBBY6.jpg', 46, '2026-04-15 14:50:49', '2026-04-15 14:50:49'),
(599, 3, 'Mamadou', 'Diallo', '76341512', 'Bamako', 'Yirimadio', 'vendue', NULL, 26, '2026-04-15 15:02:59', '2026-04-15 15:02:59'),
(600, 2, 'Samba', 'Diabaté', '76157236', 'Bamako', 'Bacodjicoroni', 'vendue', 'cartes-identite/dsN02cHSeasCYOurJah46dm0z15LDLoCDvTUBbhC.jpg', 46, '2026-04-15 15:03:38', '2026-04-15 15:03:38'),
(601, 9, 'Sedou', 'Bathily', '76417095', 'Bamako', NULL, 'vendue', NULL, 55, '2026-04-15 15:17:07', '2026-04-15 15:17:07'),
(602, 3, 'Kalilou Allhaye', 'Maïga', '70065365', 'Bamako', NULL, 'vendue', NULL, 55, '2026-04-15 15:19:05', '2026-04-15 15:19:05'),
(603, 2, 'Mamadou', 'Traoré', '66994853', 'Kayes', 'Lafiabougou', 'vendue', NULL, 38, '2026-04-15 15:40:09', '2026-04-15 15:40:09'),
(604, 2, 'Mamadou', 'Traoré', '65809171', 'Kayes', 'Lafiabougou', 'vendue', NULL, 38, '2026-04-15 15:41:01', '2026-04-15 15:41:01'),
(605, 4, 'Boubacar', 'Niangado', '76962525', 'Bamako', 'Badalabougou', 'vendue', NULL, 26, '2026-04-15 15:47:25', '2026-04-15 15:47:25'),
(606, 6, 'SaHelienne de tiens detention', 'Et d’intermédiaire', '75 - 13-43-16', 'Bamako', 'Hamdallaye ACi', 'vendue', NULL, 11, '2026-04-15 17:54:55', '2026-04-15 17:54:55'),
(607, 2, 'Kadidia', 'Keita', '79_96-19-95', 'Bamako', 'Hamdallaye', 'vendue', NULL, 11, '2026-04-15 17:55:57', '2026-04-15 17:55:57'),
(608, 11, 'Aminata', 'Ouattara', '7623 01 01', 'Bamako', 'Bakodjicoroni ACI', 'vendue', NULL, 11, '2026-04-15 17:59:05', '2026-04-15 17:59:05'),
(610, 8, 'Adama', 'DIAKITE', '76412017', 'Bamako', 'Djicoroni-para', 'vendue', 'cartes-identite/1Eboi0xHKGWqvQ1YFHkbHaDIuV2oJTWuN7fUiTP7.jpg', 25, '2026-04-15 22:18:09', '2026-04-15 22:18:09'),
(611, 8, 'Mamadou', 'KEITA', NULL, 'Bamako', 'Djicoroni-para', 'vendue', 'cartes-identite/hWvMwMauGVORjmAqnGUhF2x7W9zz83hgrajI2ZQZ.jpg', 25, '2026-04-15 22:20:06', '2026-04-15 22:20:06'),
(612, 8, 'NanaMouroukerou', 'KANE', '77048734', 'Bamako', 'Ouezzindougou', 'vendue', 'cartes-identite/WowM3ixv1DwcqVnJgp7k7AB0Pj4CFyhY8Uqr7JRC.jpg', 25, '2026-04-15 22:22:18', '2026-04-15 22:22:18'),
(613, 7, 'Lassine', 'SANOGO', '76267943', 'Bamako', 'Baco-djicoroni Rue 607', 'vendue', 'cartes-identite/hDmSxnUvjDuauM1VDeCzRUFtspa7qJvWy5c05EAU.jpg', 25, '2026-04-15 22:24:00', '2026-04-15 22:24:00'),
(614, 2, 'Assan', 'Saounera', '71693799', 'Bamako', 'Lafiabougou', 'vendue', 'cartes-identite/cnGuotqCBFASFALUgiPgmVYSRCCYzRE6cTRyGEL5.jpg', 46, '2026-04-16 07:11:22', '2026-04-16 07:11:22'),
(615, 2, 'Mahamadou', 'Coumare', '75275724', 'Bamako', NULL, 'vendue', NULL, 54, '2026-04-16 07:40:36', '2026-04-16 07:40:36'),
(616, 2, 'Daouda', 'Keita', '94514769', 'Bamako', 'Bacodjicoroni aci', 'vendue', NULL, 54, '2026-04-16 07:41:56', '2026-04-16 07:41:56'),
(617, 6, 'Hassanatou', 'Coulibaly', '76898550', 'Bamako', 'Banakoro', 'vendue', NULL, 50, '2026-04-16 07:41:56', '2026-04-16 07:41:56'),
(618, 6, 'Nouhoum', 'Fomba', '74600334', 'Bamako', 'Senou', 'vendue', NULL, 50, '2026-04-16 07:42:46', '2026-04-16 07:42:46'),
(619, 2, 'Mahamadou', 'MAIGA', '66655606', 'Kalaban', 'Bamako', 'vendue', NULL, 20, '2026-04-16 07:49:51', '2026-04-16 07:49:51'),
(620, 9, 'Toure', 'Soumaré', '71139638', 'Bamako', 'Lafiabougou', 'vendue', 'cartes-identite/xISZdDKSmYWZBJysqPO9itsBDwVuiYYlY2QlA3g6.jpg', 46, '2026-04-16 08:10:18', '2026-04-16 08:10:18'),
(621, 7, 'Aly', 'Traoré', '70610469', 'Kayes', 'KayesNdi', 'vendue', NULL, 38, '2026-04-16 08:42:11', '2026-04-16 08:42:11'),
(622, 2, 'Ibrahima', 'SANOGO', '76613152', 'Bamako', 'Badialan 2 R477 P59', 'vendue', NULL, 45, '2026-04-16 08:50:00', '2026-04-16 08:50:00'),
(623, 7, 'Oumou', 'Sacko', '78454479', 'Bamako', 'Lafiabougou bougoudani', 'vendue', 'cartes-identite/5IOOCkYpSUefKcBAK4rRFYITcZXeZnD6qOnCb3yj.jpg', 46, '2026-04-16 09:03:03', '2026-04-16 09:03:03'),
(624, 3, 'Zoumana', 'Traore', '74001575', 'Bamako', NULL, 'vendue', NULL, 55, '2026-04-16 09:18:31', '2026-04-16 09:18:31'),
(625, 2, 'Brehima', 'Gakou', '78804096', 'Bamako', 'Bougouba', 'vendue', NULL, 43, '2026-04-16 09:22:58', '2026-04-16 09:22:58'),
(626, 9, 'Daouda', 'Gakou', '76466271', 'Bamako', 'Sanakoroba', 'vendue', NULL, 50, '2026-04-16 09:24:12', '2026-04-16 09:24:12'),
(627, 3, 'Siaka', 'Diarra', '72040798', 'Bamako', NULL, 'vendue', NULL, 55, '2026-04-16 10:01:32', '2026-04-16 10:01:32'),
(628, 6, 'Issa', 'Traore', '90527796', 'Bamako', 'Sanakoroba', 'vendue', NULL, 50, '2026-04-16 10:10:14', '2026-04-16 10:10:14'),
(629, 8, 'Mamoudou', 'Kante', '72460259', 'Bamako', 'Senou', 'vendue', NULL, 50, '2026-04-16 10:15:56', '2026-04-16 10:15:56'),
(630, 2, 'Mamadou lamine', 'Sissoko', '76371147', 'Bamako', 'Kalaban coura', 'vendue', 'cartes-identite/nS0fjbDRqjq38ZV4EcX6CFAcUx6w90XaqZ59Zxmh.jpg', 46, '2026-04-16 10:34:33', '2026-04-16 10:34:33'),
(631, 6, 'Yacouba', 'Sote', '71077727', 'Bamako', 'Senou', 'vendue', NULL, 50, '2026-04-16 10:39:16', '2026-04-16 10:39:16'),
(632, 2, 'Simon', 'Diarra', '71637360', 'Bamako', 'Hippodrome', 'vendue', NULL, 43, '2026-04-16 10:40:54', '2026-04-16 10:40:54'),
(633, 6, 'Soumaïla', 'Traoré', '70030563', 'Kati', 'Kati camp', 'vendue', 'cartes-identite/hw9NJY2FqgxiB4uO2EzsjxSqNKXFZnbQvgpPyJUJ.jpg', 51, '2026-04-16 10:57:23', '2026-04-16 10:57:23'),
(634, 6, 'Fode', 'Sidibé', '90627268', 'Kati', 'Kati camp', 'vendue', 'cartes-identite/cMJxXKVtHGGN46I1kSosXslAp1faA7j1pykp31g6.jpg', 51, '2026-04-16 10:59:24', '2026-04-16 10:59:24'),
(635, 6, 'Fousseyni', 'Nanakasse', '75443425', 'Bamako', 'Banakoro', 'vendue', NULL, 50, '2026-04-16 11:00:15', '2026-04-16 11:00:15'),
(636, 2, 'Hamadou', 'BOCOUM', '77654623', 'Kalaban', 'Bamako', 'vendue', NULL, 20, '2026-04-16 11:18:34', '2026-04-16 11:18:34'),
(637, 2, 'Souleymane', 'Diallo', '76363878', 'Bamako', 'Badalabougou', 'vendue', NULL, 41, '2026-04-16 11:31:54', '2026-04-16 11:31:54'),
(638, 2, 'Issa', 'TRAORÉ', '95195018', 'Bamako', 'Ntomikorobougou R658 P11', 'vendue', NULL, 45, '2026-04-16 11:33:04', '2026-04-16 11:33:04'),
(639, 8, 'Djoumé', 'Sidibé', '73318767', 'Koulikoro', 'Koulikoro', 'vendue', 'cartes-identite/d42pdGEmfnwqRLBt04mKL3v0qCxMfUij83P2c5xy.pdf', 33, '2026-04-16 11:50:10', '2026-04-16 11:50:10'),
(640, 2, 'Mohamed Ali', 'Kane', '73710384', 'Bamako', 'Darsalam', 'vendue', NULL, 41, '2026-04-16 11:50:36', '2026-04-16 11:50:36'),
(641, 8, 'Anassa', 'Haïdara', '93673601', 'Koulikoro', 'Koulikoro', 'vendue', 'cartes-identite/Ky7TTJh3Lbe9ty5aEPqc6kMuFtTuPQEzxk853qRz.pdf', 33, '2026-04-16 11:51:18', '2026-04-16 11:51:18'),
(642, 6, 'Samba', 'Yambolba', '79760604', 'Koulikoro', 'Koulikoro', 'vendue', 'cartes-identite/WLD9BUjfecxGQqLdBUWMZ0I8DQ0MNWclx95LjrUl.pdf', 33, '2026-04-16 11:52:09', '2026-04-16 11:52:09'),
(643, 3, 'Salif', 'Coulibaly', '76747171', 'Koulikoro', 'Koulikoro', 'vendue', 'cartes-identite/gg7abw1o0SJSA3XzMSw5e0jGMzvbLsx5EE9d7vqF.pdf', 33, '2026-04-16 11:53:01', '2026-04-16 11:53:01'),
(644, 9, 'Sidaly', 'Ben Minnih', '77520618', 'Bamako', 'Baco Djicoroni golf', 'vendue', NULL, 16, '2026-04-16 11:57:43', '2026-04-16 11:57:43'),
(645, 11, 'Bouare', 'Moussa', '78461073', 'Bamako', 'Lafiabougou', 'vendue', 'cartes-identite/EFHc2aKm7skahSvc7XOdeYVNxwUhuncdMi72WWPg.jpg', 46, '2026-04-16 12:00:18', '2026-04-16 12:00:18'),
(646, 8, 'Fadimata', 'Agaly', '93498744', 'Bamako', 'Hamdallaye ACI 2000', 'vendue', 'cartes-identite/6vIdxaxSP423Log2mQD2IuusCx96ZOGFZJS4IkE2.jpg', 46, '2026-04-16 12:20:05', '2026-04-16 12:20:05'),
(647, 2, 'Drissa', 'BENGALY', '73110188', 'Kalaban', 'Bamako', 'vendue', 'cartes-identite/bCdYghGTVuu4LWh1ScAPx1dmLvsW8ChfonhqDH7z.jpg', 20, '2026-04-16 12:25:51', '2026-04-16 12:25:51'),
(648, 6, 'Abdrahamane', 'Soungara', '83083427', 'Koulikoro', 'Koulikoro', 'vendue', 'cartes-identite/g09yNz1xLpoNeMRjpfL1P0C1nMXaDWWdCv9TeBXY.pdf', 33, '2026-04-16 12:31:50', '2026-04-16 12:31:50'),
(649, 8, 'Issa', 'Goïta', '72053068', 'Kati', 'Kati fouga', 'vendue', 'cartes-identite/BdE9VAdpeVzSeEzdlb9w4VJZtvAFhEr470D4HJn7.jpg', 51, '2026-04-16 12:43:16', '2026-04-16 12:43:16'),
(650, 2, 'Nana kadidia', 'Sall', '76426717', 'Bamako', 'Lafiabougou', 'vendue', 'cartes-identite/kBZZwU9mQqHLCt8szWz6rAjGen9maWoWVqudN9l2.jpg', 46, '2026-04-16 13:12:18', '2026-04-16 13:12:18'),
(651, 7, 'Oumar', 'Coulibaly', '78471303', 'Bamako', 'Quartier Mali', 'vendue', 'cartes-identite/puomvlSBDQ9RvGSKo2xK6EyCsFNvPFnFTN0jBKvo.jpg', 46, '2026-04-16 13:13:53', '2026-04-16 13:13:53'),
(652, 4, 'Drissa', 'KEITA', '73136575', NULL, NULL, 'vendue', NULL, 13, '2026-04-16 13:54:42', '2026-04-16 13:54:42'),
(654, 3, 'Nana kadidia', 'DIBO', '74061111', NULL, NULL, 'vendue', NULL, 13, '2026-04-16 13:55:24', '2026-04-16 13:55:24'),
(655, 11, 'Drissa', 'KEITA', '73136575', NULL, NULL, 'vendue', NULL, 13, '2026-04-16 13:57:42', '2026-04-16 13:57:42'),
(657, 2, 'Minamady', 'Siby', '76291040', 'Bamako', 'Banconi plateau', 'vendue', NULL, 43, '2026-04-16 14:04:50', '2026-04-16 14:04:50'),
(658, 3, 'Mohamed Mahn', 'Diarra', '79832002', 'Bamako', 'Sotuba', 'vendue', NULL, 43, '2026-04-16 14:06:07', '2026-04-16 14:06:07'),
(659, 8, 'Ibrahim', 'Sacko', '77897344', 'Kati', 'Kôkô plateau', 'vendue', NULL, 51, '2026-04-16 14:08:05', '2026-04-16 14:08:05'),
(660, 4, 'Riyad', 'Dahrouge', '77902020', 'Bamako', 'Quinzambougou', 'vendue', NULL, 43, '2026-04-16 14:11:59', '2026-04-16 14:11:59'),
(661, 3, 'Mamadou', 'Siby', '79402737', 'Bamako', 'Kalaban coro tiébanie', 'vendue', NULL, 16, '2026-04-16 14:13:12', '2026-04-16 14:13:12'),
(662, 7, 'Fatimata', 'DABO', '92941536', 'Bamako', 'Ntomikorobougou R654 P182', 'vendue', NULL, 45, '2026-04-16 14:22:43', '2026-04-16 14:22:43'),
(663, 2, 'Lamba', 'SAMABALY', '75655018', 'Bamako', 'Hamdallaye', 'vendue', NULL, 45, '2026-04-16 14:23:25', '2026-04-16 14:23:25'),
(664, 2, 'Ibrahim Mamadou', 'Kanouté', '73673441', 'Koulikoro', 'Koulikoro', 'vendue', 'cartes-identite/N6NdwP8yG6zsMzj6vwsLPJP28LXvSdB4l9LH1HxH.pdf', 33, '2026-04-16 14:33:56', '2026-04-16 14:33:56'),
(665, 2, 'Abdel karim', 'Tounkara', '76444464', 'Bamako', 'Hamdallaye', 'vendue', NULL, 11, '2026-04-16 15:25:16', '2026-04-16 15:25:16'),
(666, 9, 'Kadi', 'Soumara', '79293440', 'Bamako', 'Lafiabougou', 'vendue', NULL, 11, '2026-04-16 15:26:29', '2026-04-16 15:26:29'),
(667, 13, 'Souleymane', 'Kone', '78-09-59-66', 'Bamako', 'Yirimadjo', 'vendue', NULL, 11, '2026-04-16 15:28:16', '2026-04-16 15:28:16'),
(668, 8, 'Bassiraba', 'Doumbia', '78789774', 'Bamako', 'Sogoniko', 'vendue', NULL, 11, '2026-04-16 15:29:28', '2026-04-16 15:29:28'),
(669, 2, 'Fatoumat', 'Samassekou', '95817743', 'Bamako', 'Sirakoro neketana', 'vendue', NULL, 26, '2026-04-16 15:29:53', '2026-04-16 15:29:53'),
(670, 2, 'Yacouba', 'Sangaré', '94156018', NULL, NULL, 'vendue', NULL, 23, '2026-04-16 16:58:12', '2026-04-16 16:58:12');
INSERT INTO `clients` (`id`, `type_carte_id`, `prenom`, `nom`, `telephone`, `ville`, `quartier`, `statut_carte`, `carte_identite`, `user_id`, `created_at`, `updated_at`) VALUES
(671, 2, 'Mariam', 'N’gom', '70654426', NULL, NULL, 'vendue', NULL, 23, '2026-04-16 16:59:00', '2026-04-16 16:59:00'),
(672, 2, 'Aïssata', 'Goïta', '73633634', NULL, NULL, 'vendue', NULL, 23, '2026-04-16 16:59:37', '2026-04-16 16:59:37'),
(673, 3, 'Mahamadou', 'Doucoure', '76730606', 'Bamako', 'Sotuba Aci', 'vendue', NULL, 19, '2026-04-16 20:06:59', '2026-04-16 20:06:59'),
(674, 3, 'Aminata', 'Koné', '72737393', 'Bamako', 'Niarela', 'vendue', NULL, 19, '2026-04-16 20:08:03', '2026-04-16 20:08:03'),
(675, 3, 'Aboubacar Diamou', 'Cissé', '79445776', 'Bamako', 'Niarela', 'vendue', NULL, 19, '2026-04-16 20:09:05', '2026-04-16 20:09:05'),
(676, 9, 'Mahamadou lamine', 'Sylla', '76729511', 'Bamako', 'Niamana', 'vendue', NULL, 10, '2026-04-16 20:15:51', '2026-04-16 20:15:51'),
(677, 2, 'Karfala', 'Sow', '76436428', NULL, NULL, 'vendue', NULL, 21, '2026-04-16 21:03:17', '2026-04-16 21:03:17'),
(678, 2, 'Abdoulaye', 'Drame', '75660878', NULL, NULL, 'vendue', NULL, 21, '2026-04-16 21:04:06', '2026-04-16 21:04:06'),
(679, 3, 'Aboubacar', 'Dembele', '76114570', NULL, NULL, 'vendue', NULL, 21, '2026-04-16 21:04:57', '2026-04-16 21:04:57'),
(680, 2, 'Fatoumata', 'Yarissy', '66760568', NULL, NULL, 'vendue', NULL, 21, '2026-04-16 21:05:42', '2026-04-16 21:05:42'),
(681, 2, 'Salifou', 'Dembele', '66746859', NULL, NULL, 'vendue', NULL, 21, '2026-04-16 21:06:20', '2026-04-16 21:06:20'),
(682, 2, 'Check abdoul', 'Kharre', '76382701', NULL, NULL, 'vendue', NULL, 21, '2026-04-16 21:07:42', '2026-04-16 21:07:42'),
(683, 2, 'Fatoumata tognes', 'Magasouba', '76476822', NULL, NULL, 'vendue', NULL, 21, '2026-04-16 21:10:17', '2026-04-16 21:10:17'),
(684, 4, 'Souleymane satigui', 'Sidibe', '72727273', NULL, NULL, 'vendue', NULL, 21, '2026-04-16 21:11:24', '2026-04-16 21:11:24'),
(685, 2, 'Aoua', 'Traore', '62015757', NULL, NULL, 'vendue', NULL, 21, '2026-04-16 21:12:09', '2026-04-16 21:12:09'),
(686, 2, 'Fanta', 'Sidibe', NULL, NULL, NULL, 'vendue', NULL, 21, '2026-04-16 21:12:29', '2026-04-16 21:12:29'),
(687, 2, 'Coumba Béatrice', 'Diallo', '94401945', NULL, NULL, 'vendue', NULL, 21, '2026-04-16 21:19:04', '2026-04-16 21:19:04'),
(688, 3, 'Aissata', 'Bathily', '70707270', NULL, NULL, 'vendue', NULL, 21, '2026-04-16 21:20:57', '2026-04-16 21:20:57'),
(689, 2, 'Salif', 'Cisse', '75474796', NULL, NULL, 'vendue', NULL, 21, '2026-04-16 21:21:57', '2026-04-16 21:21:57'),
(690, 2, 'Salimata', 'Keita', '71128316', NULL, NULL, 'vendue', NULL, 21, '2026-04-16 21:22:43', '2026-04-16 21:22:43'),
(691, 2, 'Ousmane', 'Niangadou', '79292959', NULL, NULL, 'vendue', NULL, 21, '2026-04-16 21:52:59', '2026-04-16 21:52:59'),
(692, 4, 'Niangadou', 'Mamoudou', '76331943', NULL, NULL, 'vendue', NULL, 21, '2026-04-16 21:54:30', '2026-04-16 21:54:30'),
(693, 4, 'Fatoumata', 'Konate', '66739051', NULL, NULL, 'vendue', NULL, 21, '2026-04-16 21:55:34', '2026-04-16 21:55:34'),
(694, 4, 'Samassekou', 'Ibrahim', '70001539', NULL, NULL, 'vendue', NULL, 21, '2026-04-16 21:56:45', '2026-04-16 21:56:45'),
(695, 4, 'Noumansana ep famata', 'Famata', '74746804', NULL, NULL, 'vendue', NULL, 21, '2026-04-16 21:58:28', '2026-04-16 21:58:28'),
(696, 4, 'Brehima', 'Diarra', '76436672', NULL, NULL, 'vendue', NULL, 21, '2026-04-16 22:00:41', '2026-04-16 22:00:41'),
(697, 3, 'Abdoulaye', 'Traore', '75546456', NULL, NULL, 'vendue', NULL, 21, '2026-04-16 22:03:57', '2026-04-16 22:03:57'),
(698, 2, 'Ibrahima', 'Guimayara', '63448480', NULL, NULL, 'vendue', NULL, 21, '2026-04-16 22:04:57', '2026-04-16 22:04:57'),
(699, 2, 'Ibrahima', 'Toure', '76365707', NULL, NULL, 'vendue', NULL, 21, '2026-04-16 22:06:09', '2026-04-16 22:06:09'),
(702, 4, 'Kadidia', 'TOURE', '74766692', 'Bamako', NULL, 'vendue', 'cartes-identite/QlinISUQbYoe1Mqxq7GypEndzgpyLGr0XlvtyBZU.jpg', 25, '2026-04-16 22:37:00', '2026-04-16 22:37:00'),
(703, 4, 'Ousmane', 'SIDIBE', '79290619', 'Bamako', NULL, 'vendue', 'cartes-identite/EfEewfjPpwPeZE3459eU7F89z3fJzZiKxypkkFgQ.jpg', 25, '2026-04-16 22:38:00', '2026-04-16 22:38:00'),
(704, 7, 'Mountaga', 'COULIBALY', '78938315', 'Bamako', 'Sebenikoro derrière le cimetière', 'vendue', 'cartes-identite/r677JArN2I7RcuiZH5DvLbraXOLApB0bDHagUn91.jpg', 25, '2026-04-16 22:43:12', '2026-04-16 22:43:12'),
(705, 3, 'Mahamadou', 'Sidibe', '76599015', NULL, NULL, 'vendue', NULL, 21, '2026-04-17 07:51:20', '2026-04-17 07:51:20'),
(706, 4, 'Oumar', 'Maiga', '76300001', NULL, NULL, 'vendue', NULL, 21, '2026-04-17 07:54:17', '2026-04-17 07:54:17'),
(707, 2, 'Ousmane bilal', 'Maiga', '76300001', NULL, NULL, 'vendue', NULL, 21, '2026-04-17 07:55:10', '2026-04-17 07:55:10'),
(708, 2, 'Beme', 'Berthe', '76456530', NULL, NULL, 'vendue', NULL, 21, '2026-04-17 07:56:33', '2026-04-17 07:56:33'),
(709, 2, 'Abdramane', 'Diakite', '77822509', NULL, NULL, 'vendue', NULL, 21, '2026-04-17 07:58:10', '2026-04-17 07:58:10'),
(710, 4, 'Sacko bjeneba', 'Mavie', '74820038', NULL, NULL, 'vendue', NULL, 21, '2026-04-17 07:59:34', '2026-04-17 07:59:34'),
(711, 2, 'Fousseyni', 'Djigue', '74444732', NULL, NULL, 'vendue', NULL, 21, '2026-04-17 08:01:20', '2026-04-17 08:01:20'),
(712, 3, 'Cheichna namalla', 'Doucoure', '76600676', NULL, NULL, 'vendue', NULL, 21, '2026-04-17 08:03:10', '2026-04-17 08:03:10'),
(713, 2, 'Saouty', 'Traore', '76419295', NULL, NULL, 'vendue', NULL, 21, '2026-04-17 08:04:01', '2026-04-17 08:04:01'),
(714, 2, 'Cheickne', 'Kamissoko', '76016850', NULL, NULL, 'vendue', NULL, 21, '2026-04-17 08:05:32', '2026-04-17 08:05:32'),
(715, 3, 'Youssef', 'Charbel', '66744745', 'Bamako', 'Quinzambougou', 'vendue', NULL, 43, '2026-04-17 08:10:11', '2026-04-17 08:10:11'),
(716, 2, 'Aminata', 'Fané', '62379366', 'Bamako', 'Doumanzana', 'vendue', NULL, 43, '2026-04-17 08:20:07', '2026-04-17 08:20:07'),
(717, 8, 'Hamidou', 'Doumbia', '78840330', 'Dioïla', 'Tripanobougou', 'vendue', NULL, 34, '2026-04-17 08:32:28', '2026-04-17 08:32:28'),
(718, 3, 'Affo Samba', 'Sow', '79268277', 'Dioïla', 'Socoura nord', 'vendue', NULL, 34, '2026-04-17 08:34:35', '2026-04-17 08:34:35'),
(719, 2, 'Sofia Awa', 'Moucoro', '90519517', 'Bamako', 'Gnamana', 'vendue', NULL, 34, '2026-04-17 08:36:06', '2026-04-17 08:36:06'),
(720, 3, 'Boubacar', 'Dabo', '75596273', 'Bamako', 'Kalaban coro plateau', 'vendue', NULL, 16, '2026-04-17 09:00:15', '2026-04-17 09:00:15'),
(721, 4, 'Youssouf', 'Sidibe', '66724006**77896305', NULL, NULL, 'vendue', NULL, 21, '2026-04-17 09:03:18', '2026-04-17 09:03:18'),
(722, 7, 'Agaichatou', 'Abdoulaye', '93279358', 'Bamako', 'Abaradjou', 'vendue', NULL, 45, '2026-04-17 09:49:16', '2026-04-17 09:49:16'),
(724, 2, 'Oumar', 'Thera', '70251147', 'Bamako', 'Lafiabougou', 'vendue', 'cartes-identite/dPtuqgGir8puC5QIwyj3UFaf1xAAQLPxwI7F5YF7.jpg', 46, '2026-04-17 10:04:50', '2026-04-17 10:04:50'),
(725, 6, 'Bakary', 'Diabate', '75277896', 'Bamako', 'Senou', 'vendue', NULL, 50, '2026-04-17 10:08:13', '2026-04-17 10:08:13'),
(726, 2, 'Youssouf', 'Maïga', '98436391', 'Bamako', NULL, 'vendue', NULL, 55, '2026-04-17 10:20:02', '2026-04-17 10:20:02'),
(727, 2, 'Seydou', 'Traoré', '73228275', 'Bamako', NULL, 'vendue', NULL, 55, '2026-04-17 10:21:43', '2026-04-17 10:21:43'),
(728, 2, 'Ibrahima Hamadoun', 'Diancoumba', '79100347', 'Kati', 'Kati farada', 'vendue', NULL, 51, '2026-04-17 10:22:34', '2026-04-17 10:22:34'),
(729, 2, 'Mariam', 'Kone', '77771003', 'Bamako', NULL, 'vendue', NULL, 41, '2026-04-17 10:23:12', '2026-04-17 10:23:12'),
(730, 2, 'Amadou', 'Camara', '73285170', 'Kati', 'Kati sananfara', 'vendue', NULL, 51, '2026-04-17 10:24:30', '2026-04-17 10:24:30'),
(731, 4, 'Aminata', 'Coulibaly', '75673440', 'Bamako', 'Hamdallaye Aci 2000', 'vendue', NULL, 41, '2026-04-17 10:24:40', '2026-04-17 10:24:40'),
(732, 4, 'Idrissa', 'Doucara', '78984546', 'Bamako', 'Sirakoro Meguétana', 'vendue', NULL, 51, '2026-04-17 10:28:47', '2026-04-17 10:28:47'),
(733, 2, 'Edmond Alex', 'Dembele', '76282628', 'Bamako', 'badalabougou', 'vendue', NULL, 54, '2026-04-17 10:43:49', '2026-04-17 10:43:49'),
(734, 4, 'Madina', 'DEME', '66731931', 'Bamako', 'Faladie Sema', 'vendue', NULL, 45, '2026-04-17 10:44:27', '2026-04-17 10:44:27'),
(735, 7, 'Aw', 'Amadou', '67858009', 'Koulikoro', 'Koulikoro', 'vendue', 'cartes-identite/pDKpa815Tq6JpvkCOCL70szYLbdkVyLmf5D72WWK.pdf', 33, '2026-04-17 10:59:33', '2026-04-17 10:59:33'),
(736, 4, 'Saye', 'Baïle', '79198794', 'Koulikoro', 'Koulikoro', 'vendue', 'cartes-identite/m6uE4Ur3xClZcqQLcfLimEkMclE1Vi7LtOOLT5MD.pdf', 33, '2026-04-17 11:00:53', '2026-04-17 11:01:46'),
(737, 2, 'Seydou', 'Traoré', '73228275', 'Bamako', NULL, 'vendue', NULL, 55, '2026-04-17 11:03:02', '2026-04-17 11:03:02'),
(738, 6, 'Yaya', 'Coulibaly', '92697168', 'Bamako', 'Nada dji près de l’hôpital', 'vendue', NULL, 44, '2026-04-17 11:05:13', '2026-04-17 11:05:13'),
(739, 4, 'Jacque', 'Ba', '76378524', 'Bamako', NULL, 'vendue', NULL, 55, '2026-04-17 11:05:23', '2026-04-17 11:05:23'),
(741, 2, 'Issa', 'Traore', '73088833', 'Bamako', 'Kalabacoura', 'vendue', NULL, 15, '2026-04-17 11:27:16', '2026-04-17 11:27:16'),
(742, 7, 'Sata', 'Keita', '634141 89', 'Bamako', 'Sebenikoro', 'vendue', NULL, 11, '2026-04-17 11:27:34', '2026-04-17 11:27:34'),
(743, 2, 'Aboubacar', 'Tembely', '92096399', 'Bamako', 'Niamana', 'vendue', NULL, 15, '2026-04-17 11:28:44', '2026-04-17 11:28:44'),
(744, 9, 'Kofi mawusi', 'Koudji', '77770868', 'Bamako', 'Sotuba ACI', 'vendue', NULL, 11, '2026-04-17 11:31:03', '2026-04-17 11:31:03'),
(745, 8, 'Issa Alassane', 'Touré', '76150915', 'Bamako', 'Hamdallaye', 'vendue', NULL, 11, '2026-04-17 11:35:38', '2026-04-17 11:35:38'),
(746, 2, 'Ousmane', 'Diallo', '92 61 96 34', 'Bamako', 'Médina coura', 'vendue', NULL, 18, '2026-04-17 12:09:39', '2026-04-17 12:09:39'),
(747, 2, 'Kemoko', 'Kangama', '70149760', 'Bamako', 'Kabala', 'vendue', 'cartes-identite/xlmXZn0uw9wr1DQ3MFoVDWdB0CaBZZhmxxztlinK.jpg', 52, '2026-04-17 13:47:32', '2026-04-17 13:47:32'),
(748, 2, 'Mamadou', 'Sow', '66727324', NULL, NULL, 'vendue', NULL, 21, '2026-04-17 16:23:52', '2026-04-17 16:23:52'),
(749, 3, 'Oualy sekou', 'Traore', '76299597', NULL, NULL, 'vendue', NULL, 21, '2026-04-17 16:25:16', '2026-04-17 16:25:16'),
(750, 2, 'Fanta', 'Kida', '84482355', NULL, NULL, 'vendue', NULL, 21, '2026-04-17 16:27:12', '2026-04-17 16:27:12'),
(751, 4, 'Diarafa', 'Maiga', '66723828', NULL, NULL, 'vendue', NULL, 21, '2026-04-17 16:29:34', '2026-04-17 16:29:34'),
(752, 2, 'Sanou', 'Sarr', NULL, NULL, NULL, 'vendue', NULL, 21, '2026-04-17 16:30:35', '2026-04-17 16:30:35'),
(753, 4, 'Safiatou', 'Maiga', '73913310', NULL, NULL, 'vendue', NULL, 21, '2026-04-17 16:31:47', '2026-04-17 16:31:47'),
(754, 3, 'Oumou', 'Kamia', '76949418', 'Bko', NULL, 'vendue', NULL, 10, '2026-04-17 17:13:41', '2026-04-17 17:13:41'),
(755, 8, 'Mahamadou', 'Sangare', '79383407', 'Bamako', 'N’tabacoro', 'vendue', NULL, 10, '2026-04-17 17:14:44', '2026-04-17 17:14:44'),
(756, 9, 'Bassaro', 'Tambadou', '786969', 'Bamako', 'Niamana', 'vendue', NULL, 10, '2026-04-17 17:16:59', '2026-04-17 17:17:16'),
(757, 8, 'Fatoumata', 'KAMISSOKI', '66670508', 'Bamako', 'Djicoroni-para gnègnè carré', 'vendue', 'cartes-identite/M2yGkvvJ5MQqB3KSBzaKudwatVRMQv1bm2bGU1Mj.jpg', 25, '2026-04-17 22:40:38', '2026-04-17 22:40:38'),
(758, 8, 'Ibrahim Sambou', 'BAGAGAH', '65089394', 'Bamako', 'Djicoroni-para Camp para', 'vendue', 'cartes-identite/pAxqFrbUHKaeXdStJH54bs4RARhFtS0xihaulfcb.jpg', 25, '2026-04-17 22:43:07', '2026-04-17 22:43:07'),
(760, 6, 'Djeneba Aichatou', 'Kone', '94444155', 'Bamako', 'Senou', 'vendue', NULL, 50, '2026-04-18 08:56:34', '2026-04-18 08:56:34'),
(761, 6, 'Almamy', 'Gana', '79235034', 'Bamako', 'Senou', 'vendue', NULL, 50, '2026-04-18 09:07:17', '2026-04-18 09:07:17'),
(762, 2, 'Gaoussou', 'FOFANA', '77881873', NULL, NULL, 'vendue', NULL, 13, '2026-04-18 09:30:57', '2026-04-18 09:30:57'),
(763, 2, 'Mariam', 'SAMAKE', '77662766', NULL, NULL, 'vendue', NULL, 13, '2026-04-18 09:31:49', '2026-04-18 09:31:49'),
(765, 3, 'Yacouba', 'Niangadou', '78736683', 'Bamako', NULL, 'vendue', NULL, 43, '2026-04-18 09:59:38', '2026-04-18 09:59:38'),
(766, 6, 'Bourama', 'Samake', '76949868', 'Bamako', 'Senou', 'vendue', NULL, 50, '2026-04-18 10:05:12', '2026-04-18 10:05:12'),
(767, 6, 'Bourama', 'Samake', '76949868', 'Bamako', 'Senou', 'vendue', NULL, 50, '2026-04-18 10:06:39', '2026-04-18 10:06:39'),
(768, 8, 'Abdoulaye', 'Diassana', '73442509', 'Bamako', 'Lafiabougou Taliko', 'vendue', 'cartes-identite/GZ5Hm17omYhffZW7faRmvaDLBhfEq5A6HvnUA8gy.jpg', 46, '2026-04-18 10:09:53', '2026-04-18 10:09:53'),
(769, 8, 'Djoume', 'Sidibé', '83589933', 'Bamako', 'Lafiabougou', 'vendue', 'cartes-identite/wrQFwj7OhMHZ7umXalbeR2Cm6jlXdIfMASYC7AwG.jpg', 46, '2026-04-18 10:12:05', '2026-04-18 10:12:05'),
(770, 9, 'Kaniba', 'Doumbia', '78499045', 'Bamako', 'Baco Djicoroni golf', 'vendue', NULL, 16, '2026-04-18 10:48:15', '2026-04-18 10:48:15'),
(771, 2, 'Kalidou', 'Bamba', '77673746', 'Bamako', 'Daoudabougou', 'vendue', NULL, 41, '2026-04-18 11:00:41', '2026-04-18 11:00:41'),
(772, 3, 'Paulin', 'Traoré', '77281907', 'Bamako', 'Kabala', 'vendue', NULL, 52, '2026-04-18 12:12:43', '2026-04-18 12:12:43'),
(773, 3, 'Mohamed', 'Guindo', '76720494', NULL, NULL, 'vendue', NULL, 23, '2026-04-18 14:09:21', '2026-04-18 14:09:21'),
(774, 6, 'Abdoulaye', 'Coulibaly', '79480094', 'Bamako', 'Yirimadio zerny', 'vendue', NULL, 19, '2026-04-18 14:14:40', '2026-04-18 14:14:40'),
(775, 9, 'Dimitri destaing', 'Demanou azebaze', '77163951', 'Bamako', 'Baco Djicoroni', 'vendue', NULL, 16, '2026-04-18 16:43:54', '2026-04-18 16:43:54'),
(776, 7, 'Abdoulaye', 'Konare', '77808555', 'Kayes', 'Lafiabougou sud', 'vendue', NULL, 38, '2026-04-18 17:31:20', '2026-04-18 17:31:20'),
(777, 2, 'Fatoumata', 'Niangaly', '93244009', 'Mopti', 'Sévaré Million kin', 'vendue', NULL, 32, '2026-04-18 19:57:37', '2026-04-18 19:57:37'),
(778, 7, 'Fatoumata', 'Niangaly', '93244009', 'Mopti', 'Séparé Million kin', 'vendue', NULL, 32, '2026-04-18 19:59:26', '2026-04-18 19:59:26'),
(779, 2, 'Sidy', 'Haïdara', '76736298', NULL, NULL, 'vendue', NULL, 21, '2026-04-19 12:15:06', '2026-04-19 12:15:06'),
(780, 2, 'Aissatou', 'Coulibaly', '77087571', NULL, NULL, 'vendue', NULL, 21, '2026-04-19 12:16:02', '2026-04-19 12:16:02'),
(781, 2, 'Oumar', 'Diarra', NULL, NULL, NULL, 'vendue', NULL, 21, '2026-04-19 12:16:30', '2026-04-19 12:16:30'),
(782, 2, 'Maimouna', 'Keita', '76642732', NULL, NULL, 'vendue', NULL, 21, '2026-04-19 12:17:52', '2026-04-19 12:17:52'),
(783, 4, 'Djibro', 'Gognimbou', '79356063', NULL, NULL, 'vendue', NULL, 21, '2026-04-19 12:18:59', '2026-04-19 12:18:59'),
(784, 2, 'Mamadou', 'Sangare', '76388895', NULL, NULL, 'vendue', NULL, 21, '2026-04-19 12:20:05', '2026-04-19 12:20:05'),
(785, 3, 'Bouramasire', 'Simpara', '78787778', NULL, NULL, 'vendue', NULL, 21, '2026-04-19 12:20:54', '2026-04-19 12:20:54'),
(786, 4, 'Ibrahima', 'Karagnara', '76448754', NULL, NULL, 'vendue', NULL, 21, '2026-04-19 12:21:43', '2026-04-19 12:21:43'),
(787, 2, 'Moctar', 'Tolo', '71710733', NULL, NULL, 'vendue', NULL, 21, '2026-04-19 12:22:22', '2026-04-19 12:22:22'),
(788, 3, 'Salif', 'Doumbia', '90961315', NULL, NULL, 'vendue', NULL, 21, '2026-04-19 12:23:23', '2026-04-19 12:23:23'),
(789, 2, 'Mody', 'Sacko', '76605302', NULL, NULL, 'vendue', NULL, 21, '2026-04-19 12:24:13', '2026-04-19 12:24:13'),
(790, 3, 'Tierno  Sidy Mohamed', 'Coulibaly', '76736748', NULL, NULL, 'vendue', NULL, 21, '2026-04-19 12:26:01', '2026-04-19 12:26:01'),
(791, 2, 'Hanini', 'Sacko', '76075371', NULL, NULL, 'vendue', NULL, 21, '2026-04-19 12:27:11', '2026-04-19 12:27:11'),
(792, 2, 'Mariam', 'Timbo', '76378831', NULL, NULL, 'vendue', NULL, 21, '2026-04-19 12:28:02', '2026-04-19 12:28:02'),
(793, 2, 'Fanta Mohamed', 'Traore', '76641212', NULL, NULL, 'vendue', NULL, 21, '2026-04-19 12:29:20', '2026-04-19 12:29:20'),
(794, 2, 'Aminata', 'Taboure', '64408989', NULL, NULL, 'vendue', NULL, 21, '2026-04-19 12:30:21', '2026-04-19 12:30:21'),
(796, 2, 'N’Fally', 'Sylla', '70535759', NULL, NULL, 'vendue', NULL, 54, '2026-04-20 07:36:42', '2026-04-20 07:36:42'),
(797, 7, 'Aminata', 'Traoré', '60-56-44-26', 'Bamako', 'Lafiabougou', 'vendue', NULL, 11, '2026-04-20 08:02:46', '2026-04-20 08:02:46'),
(798, 6, 'Cheick Fanta mady', 'Traoré', '75 - 13-43-16', 'Bamako', 'Hamdallaye', 'vendue', NULL, 11, '2026-04-20 08:05:16', '2026-04-20 08:05:16'),
(799, 8, 'Talla', 'Diouf', '93030035', 'Bamako', 'Lafiabougou', 'vendue', 'cartes-identite/K4bE8KSBai6UVgVOD3hSbE0X0Z6XvH7RAsEjBSeU.jpg', 46, '2026-04-20 08:06:46', '2026-04-20 08:06:46'),
(800, 2, 'Fanta Mohamed', 'TRAORÉ', '76641212', 'Bamako', 'Sangarebougou', 'vendue', NULL, 45, '2026-04-20 08:24:24', '2026-04-20 08:24:24'),
(801, 2, 'Hanini', 'SACKO', '76075371', 'Bamako', 'Ntomikorobougou', 'vendue', NULL, 45, '2026-04-20 08:30:51', '2026-04-20 08:41:43'),
(802, 2, 'Mariam', 'TIMBO', '76378831', 'Bamako', 'Niamana', 'vendue', NULL, 45, '2026-04-20 09:07:00', '2026-04-20 09:07:00'),
(803, 3, 'Thierno Sidy Mohamed', 'COULIBALY', '76736748', 'Bamako', 'Moribabougou droit', 'vendue', NULL, 45, '2026-04-20 09:08:12', '2026-04-20 09:08:12'),
(804, 2, 'Youssouf', 'DEMBELE', '70923646', 'Bamako', 'Kati', 'vendue', NULL, 45, '2026-04-20 09:09:29', '2026-04-20 09:09:29'),
(805, 9, 'Nouhan', 'Sidibé', '70276478', 'Bamako', 'Baco djicoroni', 'vendue', NULL, 16, '2026-04-20 09:13:14', '2026-04-20 09:13:14'),
(806, 9, 'Modibo', 'Diagouraga', '78373934', 'Bamako', 'Lafiabougou', 'vendue', 'cartes-identite/5x47eBiOAMBb9vLspcfUfibeZUzYJ4agiWy2krAk.jpg', 46, '2026-04-20 09:56:02', '2026-04-20 09:56:02'),
(807, 8, 'Waly', 'Diawara', '61- 61-36 - 89', 'Bamako', 'Quarantibougou', 'vendue', NULL, 11, '2026-04-20 10:57:08', '2026-04-20 10:57:08'),
(808, 7, 'N\'Deye', 'Kanoute', '78431819', 'Bamako', 'Lafiabougou', 'vendue', 'cartes-identite/HH1jlaIciqTX3NJpg0Rby5d9Vn3foX0eyGQdnI86.jpg', 46, '2026-04-20 11:04:14', '2026-04-20 11:04:14'),
(809, 8, 'Safiatou', 'Doumbia', '70674540', 'Bamako', 'Lafiabougou Terminus', 'vendue', 'cartes-identite/zYTlYzJeyX4H0nO7UYaO5EJSXpJgGuQEjQch29lI.jpg', 46, '2026-04-20 11:18:18', '2026-04-20 11:18:18'),
(810, 7, 'Djeneba', 'Goita', '73276190', 'San', 'Kimparana', 'vendue', 'cartes-identite/TsJNVnaEWOPs8tIzYRw680fhSVLxqzQ1zOjU4k91.pdf', 31, '2026-04-20 12:00:07', '2026-04-20 12:00:07'),
(811, 7, 'Allias', 'Goro', '76566363', 'San', 'Lafiabougou', 'vendue', 'cartes-identite/VpAEHMDv5tZEQFkzsMbgZaGvYxwXaJAkw8p8F42j.pdf', 31, '2026-04-20 12:01:49', '2026-04-20 12:01:49'),
(812, 7, 'Oumou', 'Bakary Coulibaly', '79414597', 'San', 'Kayantona', 'vendue', 'cartes-identite/2wbKYDtHHE8Vfkjcn1FwzA1KyQDQY2OIWvMP79Vj.pdf', 31, '2026-04-20 12:05:31', '2026-04-20 12:05:31'),
(813, 7, 'Cheick Tidiani', 'Keïta', '96541090', 'Bamako', 'Niamana Tabacoro', 'vendue', 'cartes-identite/MGjfo57CmEigBBSo58ELiSM5jAU3ayIpMCxphrCB.jpg', 46, '2026-04-20 12:20:06', '2026-04-20 12:20:06'),
(814, 2, 'Fatoumata', 'Kanté', '72744585', 'Bamako', 'Lafiabougou', 'vendue', 'cartes-identite/vsmIKF6gWL5OieGEXsfixHitrW961B2poo8PbGx1.jpg', 46, '2026-04-20 13:12:44', '2026-04-20 13:12:44'),
(815, 2, 'Kadiatou', 'Camara', '76238494', 'Bamako', 'Boulkassoubougou', 'vendue', NULL, 15, '2026-04-20 13:16:56', '2026-04-20 13:16:56'),
(816, 2, 'Kadiatou', 'Camara', '76238494', 'Bamako', 'Boulkassoubougou', 'vendue', NULL, 15, '2026-04-20 13:17:28', '2026-04-20 13:17:28'),
(817, 13, 'Emmanuel chidera', 'Madu', '70673355', 'Bamako', 'Sébénikoro près de la mosquée 2', 'vendue', NULL, 44, '2026-04-20 13:32:27', '2026-04-20 13:32:27'),
(818, 9, 'Youssouf', 'Dramé', '90292051', 'Bamako', 'Baco djicoroni aci', 'vendue', NULL, 16, '2026-04-20 13:43:25', '2026-04-20 13:43:25'),
(819, 2, 'Mariam Mody', 'DIA', '74147214', 'Bamako', 'Tomikorobougou', 'vendue', NULL, 45, '2026-04-20 13:50:15', '2026-04-20 13:50:15'),
(820, 9, 'Moussa', 'Kouyate', '75249082', 'Bamako', 'Senou', 'vendue', NULL, 50, '2026-04-20 13:50:33', '2026-04-20 13:50:33'),
(821, 6, 'Moussa', 'Kone', '75750904', 'Bamako', 'Senou', 'vendue', NULL, 50, '2026-04-20 13:51:08', '2026-04-20 13:51:08'),
(822, 6, 'Abdoul Aziz', 'Coulibaly', '90808304', 'Bamako', 'Senou', 'vendue', NULL, 50, '2026-04-20 13:51:54', '2026-04-20 13:51:54'),
(823, 9, 'Coumba', 'Kamissoko', '70819455', 'Bamako', 'Baco djicoroni aci', 'vendue', NULL, 16, '2026-04-20 14:04:26', '2026-04-20 14:04:26'),
(824, 2, 'Modibo', 'Diagouraga', '78373934', 'Bamako', 'Lafiabougou', 'vendue', 'cartes-identite/8JukVpy7X4Gwq4OjP91bGX3D3QnZrgBHOb6E1eRW.jpg', 46, '2026-04-20 14:06:40', '2026-04-20 14:06:40'),
(825, 6, 'Mamoudou', 'Sidibé', '75556354', NULL, 'Garantibougou', 'vendue', NULL, 14, '2026-04-20 14:10:36', '2026-04-20 14:10:36'),
(826, 9, 'Aminata', 'SANANKOUA', '76114680', 'Kalaban coura', 'Bamako', 'vendue', NULL, 20, '2026-04-20 14:12:38', '2026-04-20 14:12:38'),
(827, 7, 'Teninkoura', 'Diarra', '79085380', 'Kati', 'Kati coura', 'vendue', NULL, 51, '2026-04-20 14:33:03', '2026-04-20 14:33:03'),
(828, 2, 'Salimata', 'Maiga', '83346430', 'Bamako', 'Titibougou', 'vendue', NULL, 15, '2026-04-20 14:49:49', '2026-04-20 14:49:49'),
(829, 2, 'Naffissetou', 'Keita', '76739189', 'Bamako', 'Senou', 'vendue', NULL, 50, '2026-04-20 14:50:35', '2026-04-20 14:50:35'),
(830, 2, 'Salimata', 'Maiga', '83346430', 'Bamako', 'Titibougou', 'vendue', NULL, 15, '2026-04-20 14:51:40', '2026-04-20 14:51:40'),
(831, 2, 'Fousseny', 'Togola', '71788091', 'Bamako', 'Kalaban coura Aci', 'vendue', NULL, 41, '2026-04-20 15:09:12', '2026-04-20 15:09:12'),
(832, 2, 'Aicha hama', 'Cissé', '73825231', 'Bamako', 'Kabala', 'vendue', 'cartes-identite/71zI50Z5zjNU9GGUytsTx5l5dP402T6sKqanqyyn.jpg', 52, '2026-04-20 16:43:45', '2026-04-20 16:43:45'),
(833, 8, 'Moussa', 'Sidibé', '90141607', 'Bamako', 'Kabala', 'vendue', 'cartes-identite/MuXUJnubD97cc7ZoUxS4WtWKqrGuriIZFsL2MlzF.jpg', 52, '2026-04-20 16:45:11', '2026-04-20 16:45:11'),
(834, 2, 'Fanta djene', 'Camara', '98910052', 'Bamako', 'Niamana', 'vendue', NULL, 34, '2026-04-20 17:08:45', '2026-04-20 17:08:45'),
(835, 8, 'Diakaridia', 'Fomba', '70826188', 'Dioïla', NULL, 'vendue', NULL, 34, '2026-04-20 17:09:39', '2026-04-20 17:09:39'),
(836, 3, 'Oumou', 'Traore', '76142858', NULL, NULL, 'vendue', NULL, 21, '2026-04-20 17:38:01', '2026-04-20 17:38:01'),
(837, 2, 'Mamadou', 'Diarra', '76285978', NULL, NULL, 'vendue', NULL, 21, '2026-04-20 17:55:51', '2026-04-20 17:55:51'),
(838, 2, 'Yaya', 'Traore', '89604444', 'Bamako', 'Koulouba', 'vendue', NULL, 18, '2026-04-21 07:13:20', '2026-04-21 07:13:20'),
(839, 3, 'Kadidiatou', 'Traore', '76081416', 'Bamako', 'Sebenicoro', 'vendue', NULL, 18, '2026-04-21 07:14:14', '2026-04-21 07:14:14'),
(840, 9, 'Djiguiya', 'Coulibaly', '76322159', 'Bamako', 'Sotuba', 'vendue', NULL, 18, '2026-04-21 07:15:19', '2026-04-21 07:15:19'),
(841, 6, 'Lea mariam', 'Dembele', NULL, 'Bamako', 'Kalaban coura', 'vendue', NULL, 18, '2026-04-21 07:16:18', '2026-04-21 07:16:18'),
(842, 2, 'Balla', 'Djenepo', '75168286', 'Mopti', 'Komoguel', 'vendue', NULL, 32, '2026-04-21 08:41:09', '2026-04-21 08:41:09'),
(843, 2, 'Balla', 'Djenepo', '75168286', 'Mopti', 'Komoguel', 'vendue', NULL, 32, '2026-04-21 08:41:34', '2026-04-21 08:41:34');

-- --------------------------------------------------------

--
-- Structure de la table `commercial_agence_transferts`
--

CREATE TABLE `commercial_agence_transferts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `commercial_user_id` bigint(20) UNSIGNED NOT NULL,
  `admin_user_id` bigint(20) UNSIGNED NOT NULL,
  `nouvelle_agence_id` bigint(20) UNSIGNED NOT NULL,
  `snapshots` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`snapshots`)),
  `profil_agence_avant` bigint(20) UNSIGNED DEFAULT NULL,
  `profil_agence_apres` bigint(20) UNSIGNED DEFAULT NULL,
  `note` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `contrat_prestation_reponses`
--

CREATE TABLE `contrat_prestation_reponses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `campagne_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `statut` varchar(32) NOT NULL DEFAULT 'en_attente',
  `repondu_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `contrat_prestation_reponses`
--

INSERT INTO `contrat_prestation_reponses` (`id`, `campagne_id`, `user_id`, `statut`, `repondu_at`, `created_at`, `updated_at`) VALUES
(30, 5, 10, 'accepte', '2026-03-31 09:17:01', '2026-03-31 09:12:49', '2026-03-31 09:17:01'),
(31, 5, 11, 'accepte', '2026-04-01 19:00:56', '2026-03-31 09:12:49', '2026-04-01 19:00:56'),
(33, 5, 13, 'accepte', '2026-03-31 14:22:59', '2026-03-31 09:12:49', '2026-03-31 14:22:59'),
(34, 5, 14, 'accepte', '2026-04-01 18:23:53', '2026-03-31 09:12:49', '2026-04-01 18:23:53'),
(35, 5, 15, 'en_attente', NULL, '2026-03-31 09:12:49', '2026-03-31 09:12:49'),
(36, 5, 16, 'en_attente', NULL, '2026-03-31 09:12:49', '2026-03-31 09:12:49'),
(37, 5, 17, 'accepte', '2026-03-31 17:19:21', '2026-03-31 09:12:49', '2026-03-31 17:19:21'),
(38, 5, 18, 'accepte', '2026-03-31 10:04:22', '2026-03-31 09:12:49', '2026-03-31 10:04:22'),
(39, 5, 19, 'accepte', '2026-03-31 09:58:40', '2026-03-31 09:12:49', '2026-03-31 09:58:40'),
(40, 5, 20, 'accepte', '2026-04-01 19:46:06', '2026-03-31 09:12:49', '2026-04-01 19:46:06'),
(41, 5, 21, 'en_attente', NULL, '2026-03-31 09:12:49', '2026-03-31 09:12:49'),
(42, 5, 22, 'accepte', '2026-04-01 14:16:05', '2026-03-31 09:12:49', '2026-04-01 14:16:05'),
(43, 5, 23, 'accepte', '2026-04-01 19:44:45', '2026-03-31 09:12:49', '2026-04-01 19:44:45'),
(44, 5, 24, 'en_attente', NULL, '2026-03-31 09:12:49', '2026-03-31 09:12:49'),
(45, 5, 25, 'accepte', '2026-03-31 13:05:01', '2026-03-31 09:12:49', '2026-03-31 13:05:01'),
(46, 5, 26, 'accepte', '2026-03-31 10:17:14', '2026-03-31 09:12:49', '2026-03-31 10:17:14'),
(48, 5, 28, 'accepte', '2026-04-02 09:12:55', '2026-03-31 09:12:49', '2026-04-02 09:12:55'),
(49, 5, 29, 'accepte', '2026-03-31 11:25:45', '2026-03-31 09:12:49', '2026-03-31 11:25:45'),
(50, 5, 30, 'accepte', '2026-03-31 13:33:08', '2026-03-31 09:12:49', '2026-03-31 13:33:08'),
(51, 5, 31, 'accepte', '2026-03-31 16:29:32', '2026-03-31 09:12:49', '2026-03-31 16:29:32'),
(52, 5, 32, 'en_attente', NULL, '2026-03-31 09:12:49', '2026-03-31 09:12:49'),
(53, 5, 33, 'accepte', '2026-03-31 12:45:17', '2026-03-31 09:12:49', '2026-03-31 12:45:17'),
(54, 5, 34, 'accepte', '2026-03-31 11:11:36', '2026-03-31 09:12:49', '2026-03-31 11:11:36'),
(55, 5, 35, 'accepte', '2026-04-04 20:27:05', '2026-03-31 09:12:49', '2026-04-04 20:27:05'),
(56, 5, 36, 'accepte', '2026-04-04 21:35:43', '2026-03-31 09:12:49', '2026-04-04 21:35:43'),
(57, 5, 37, 'en_attente', NULL, '2026-03-31 09:12:49', '2026-03-31 09:12:49'),
(58, 5, 38, 'accepte', '2026-03-31 20:14:46', '2026-03-31 09:12:49', '2026-03-31 20:14:46'),
(59, 5, 40, 'accepte', '2026-04-02 06:33:38', '2026-03-31 11:35:19', '2026-04-02 06:33:38'),
(60, 6, 41, 'en_attente', NULL, '2026-04-10 09:46:21', '2026-04-10 09:46:21'),
(62, 6, 43, 'accepte', '2026-04-10 09:05:10', '2026-04-10 09:46:21', '2026-04-10 09:05:10'),
(63, 6, 44, 'accepte', '2026-04-10 10:39:02', '2026-04-10 09:46:21', '2026-04-10 10:39:02'),
(64, 6, 45, 'en_attente', NULL, '2026-04-10 09:46:21', '2026-04-10 09:46:21'),
(65, 6, 46, 'accepte', '2026-04-14 14:04:02', '2026-04-10 09:46:21', '2026-04-14 14:04:02'),
(66, 6, 47, 'accepte', '2026-04-10 09:43:08', '2026-04-10 09:46:21', '2026-04-10 09:43:08'),
(67, 6, 48, 'accepte', '2026-04-10 14:34:37', '2026-04-10 09:46:21', '2026-04-10 14:34:37'),
(69, 6, 50, 'accepte', '2026-04-15 08:41:46', '2026-04-10 09:46:21', '2026-04-15 08:41:46'),
(70, 6, 51, 'accepte', '2026-04-10 16:16:07', '2026-04-10 09:46:21', '2026-04-10 16:16:07'),
(72, 6, 52, 'en_attente', NULL, '2026-04-14 10:31:23', '2026-04-14 10:31:23'),
(73, 6, 54, 'en_attente', NULL, '2026-04-14 10:31:23', '2026-04-14 10:31:23'),
(74, 6, 55, 'en_attente', NULL, '2026-04-14 10:31:23', '2026-04-14 10:31:23'),
(76, 8, 10, 'en_attente', NULL, '2026-06-15 16:33:30', '2026-06-15 16:33:30'),
(77, 8, 11, 'en_attente', NULL, '2026-06-15 16:33:30', '2026-06-15 16:33:30'),
(78, 8, 40, 'en_attente', NULL, '2026-06-15 16:33:30', '2026-06-15 16:33:30'),
(79, 8, 13, 'en_attente', NULL, '2026-06-15 16:33:30', '2026-06-15 16:33:30'),
(80, 8, 14, 'en_attente', NULL, '2026-06-15 16:33:30', '2026-06-15 16:33:30'),
(81, 8, 57, 'en_attente', NULL, '2026-06-15 16:33:30', '2026-06-15 16:33:30'),
(82, 8, 58, 'en_attente', NULL, '2026-06-15 16:33:30', '2026-06-15 16:33:30'),
(83, 8, 17, 'en_attente', NULL, '2026-06-15 16:33:30', '2026-06-15 16:33:30'),
(84, 8, 18, 'en_attente', NULL, '2026-06-15 16:33:30', '2026-06-15 16:33:30'),
(85, 8, 19, 'en_attente', NULL, '2026-06-15 16:33:30', '2026-06-15 16:33:30'),
(86, 8, 59, 'en_attente', NULL, '2026-06-15 16:33:30', '2026-06-15 16:33:30'),
(87, 8, 21, 'en_attente', NULL, '2026-06-15 16:33:30', '2026-06-15 16:33:30'),
(88, 8, 60, 'en_attente', NULL, '2026-06-15 16:33:30', '2026-06-15 16:33:30'),
(89, 8, 23, 'en_attente', NULL, '2026-06-15 16:33:30', '2026-06-15 16:33:30'),
(90, 8, 55, 'en_attente', NULL, '2026-06-15 16:33:30', '2026-06-15 16:33:30'),
(91, 8, 25, 'en_attente', NULL, '2026-06-15 16:33:30', '2026-06-15 16:33:30'),
(92, 8, 22, 'en_attente', NULL, '2026-06-15 16:33:30', '2026-06-15 16:33:30'),
(93, 8, 27, 'en_attente', NULL, '2026-06-15 16:33:30', '2026-06-15 16:33:30'),
(94, 8, 45, 'en_attente', NULL, '2026-06-15 16:33:30', '2026-06-15 16:33:30'),
(95, 8, 61, 'en_attente', NULL, '2026-06-15 16:33:30', '2026-06-15 16:33:30'),
(96, 8, 62, 'en_attente', NULL, '2026-06-15 16:33:30', '2026-06-15 16:33:30'),
(97, 8, 63, 'en_attente', NULL, '2026-06-15 16:33:30', '2026-06-15 16:33:30'),
(98, 8, 47, 'en_attente', NULL, '2026-06-15 16:33:30', '2026-06-15 16:33:30'),
(99, 8, 64, 'en_attente', NULL, '2026-06-15 16:33:30', '2026-06-15 16:33:30'),
(100, 8, 29, 'en_attente', NULL, '2026-06-15 16:33:30', '2026-06-15 16:33:30'),
(101, 8, 38, 'en_attente', NULL, '2026-06-15 16:33:30', '2026-06-15 16:33:30'),
(102, 8, 35, 'en_attente', NULL, '2026-06-15 16:33:30', '2026-06-15 16:33:30'),
(103, 8, 33, 'en_attente', NULL, '2026-06-15 16:33:30', '2026-06-15 16:33:30'),
(104, 8, 51, 'en_attente', NULL, '2026-06-15 16:33:30', '2026-06-15 16:33:30'),
(105, 8, 65, 'en_attente', NULL, '2026-06-15 16:33:30', '2026-06-15 16:33:30');

-- --------------------------------------------------------

--
-- Structure de la table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_03_23_000001_create_agences_table', 1),
(5, '2025_03_23_000002_add_bdm_columns_to_users_table', 1),
(6, '2025_03_23_000003_create_clients_table', 1),
(7, '2025_03_23_000004_create_stocks_table', 1),
(8, '2025_03_23_000005_create_ventes_table', 1),
(9, '2025_03_23_000006_create_mouvements_stock_table', 1),
(10, '2025_03_23_000007_create_reclamations_table', 1),
(11, '2025_03_23_000008_create_primes_table', 1),
(12, '2025_03_23_000009_create_campagnes_table', 1),
(13, '2025_03_23_100000_add_prenom_and_nullable_email_to_users', 1),
(14, '2025_03_23_110000_enhance_campagnes_table', 1),
(15, '2025_03_24_000000_create_types_cartes_and_migrate', 1),
(16, '2025_03_24_120000_drop_libelle_ordre_from_types_cartes', 1),
(17, '2026_02_10_000001_add_remise_aide_campagne_and_users_actif', 1),
(18, '2026_03_25_000000_add_remise_types_cartes_to_campagnes', 1),
(19, '2026_03_27_120000_add_campagne_id_to_ventes_table', 1),
(20, '2026_03_30_120000_campagne_prime_meilleur_vendeur_only', 1),
(21, '2026_03_31_100000_users_role_direction_replace_chef', 1),
(22, '2026_03_31_110000_clear_agences_chef_id', 1),
(23, '2026_03_31_200000_add_commercial_telephonique_and_logs', 1),
(24, '2026_03_31_200000_contrats_prestation_aides_versements', 1),
(25, '2026_03_31_210000_campagne_contrat_articles', 1),
(26, '2026_04_03_100000_add_cartes_proposees_to_telephonique_rapports', 1),
(27, '2026_04_04_100000_add_campagne_id_to_telephonique_rapports', 2),
(28, '2026_03_30_120000_add_ordre_to_agences_and_fix_campagne_avril_2026', 3),
(29, '2026_04_01_000000_remove_prix_and_montant_ventes', 4),
(30, '2026_04_02_100000_reassign_agence_boulkassoulbougu_to_senou', 5),
(31, '2026_04_02_110000_merge_duplicate_youssouf_traore_kabala', 5),
(32, '2026_04_01_120000_create_commercial_agence_transferts_table', 6),
(33, '2026_04_30_000000_drop_stocks_and_mouvements_stock_tables', 7);

-- --------------------------------------------------------

--
-- Structure de la table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `primes`
--

CREATE TABLE `primes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `periode` varchar(7) NOT NULL,
  `montant` decimal(12,0) NOT NULL,
  `rang` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `reclamations`
--

CREATE TABLE `reclamations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `client_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `type` enum('activation','mot_de_passe','rechargement') NOT NULL,
  `statut` enum('ouvert','en_cours','resolu') NOT NULL DEFAULT 'ouvert',
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('12pIv4WbDbztIW5tCypdmNPj7kPWYZhdrbeUthfy', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36 Edg/149.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiQUJudE9kZ1JyVkJBbDkyVTZLMHdhaFlVeUp4SmhtaHg1aVN5WGZyQSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzg6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9zaXRlLndlYm1hbmlmZXN0IjtzOjU6InJvdXRlIjtzOjEyOiJwd2EubWFuaWZlc3QiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1781541493),
('1qr2e3wAVEoEIJseK24weHyyYKTU0wN2lXaVlhtv', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36 Edg/149.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoibFRNcThmb0doaVgwUXlPQ3ZYMEZZOGEyeXlZMURJRFZ2SlVQYURzMCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzg6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9zaXRlLndlYm1hbmlmZXN0IjtzOjU6InJvdXRlIjtzOjEyOiJwd2EubWFuaWZlc3QiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1781540948),
('1ul2jfodPAXIK456InuGcd7yBBuFvjVsZsggBIjH', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36 Edg/149.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiN2ZIbHJtb2theE1nUnlzN0hLVFRLTG1KMEk4ZzFmek5EQzVkcU82USI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzg6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9zaXRlLndlYm1hbmlmZXN0IjtzOjU6InJvdXRlIjtzOjEyOiJwd2EubWFuaWZlc3QiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1781541764),
('2rj1670Y6fHYCAIALFX0UR6n49OAdQjBq3eXDDBX', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36 Edg/149.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiRHFXcld2RkRvSHdMNXBGeDN2WUd4Rk45WVJ5ZlVpMHJlTmRxZ0VoZiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzg6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9zaXRlLndlYm1hbmlmZXN0IjtzOjU6InJvdXRlIjtzOjEyOiJwd2EubWFuaWZlc3QiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1781541759),
('30OLJpE9Gnd8hf7sguP0b4N7JWd5yowKKL1OXmdo', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36 Edg/149.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiaENXNkVkUnRsRDluWVNJMDg0cWhlNDM0bTFnZTVxb0s2SU5VWkNtTSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzg6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9zaXRlLndlYm1hbmlmZXN0IjtzOjU6InJvdXRlIjtzOjEyOiJwd2EubWFuaWZlc3QiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1781538781),
('4I7pFQ0VNNjL9dofmKRUrxtYG5FtDVnI6Qlrrgrd', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36 Edg/149.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiVjdRWTdzWmpORW5OVHAwckMwZExmdDBJNXBCYktreFZ1Z3h6Y2UxNSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzg6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9zaXRlLndlYm1hbmlmZXN0IjtzOjU6InJvdXRlIjtzOjEyOiJwd2EubWFuaWZlc3QiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1781542117),
('4XeOrFiGR4erhmMrmWP4LAl0cKY4jTy5K9mWq0m8', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36 Edg/149.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiOXBMNG9Ba3p0Mkh1R1RYWEUyMkRQQzhHZVBTaG5FSldYVWY1VVVGeCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzg6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9zaXRlLndlYm1hbmlmZXN0IjtzOjU6InJvdXRlIjtzOjEyOiJwd2EubWFuaWZlc3QiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1781542215),
('67pNu2qIHPG6ssBUY3osmZ9sPZThcBZGWGQOsT5a', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36 Edg/149.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiV254ajBDSlRlUWJHcTd2ZjZJcVhZTHF4RVdVVDMxZWhYYXdZSjVlZiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzg6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9zaXRlLndlYm1hbmlmZXN0IjtzOjU6InJvdXRlIjtzOjEyOiJwd2EubWFuaWZlc3QiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1781542120),
('7TTlBDlXw5lKh8ItHvfHPOQHtIBGiZf49WhGK7tu', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36 Edg/149.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiQVpMMG52Tnl4SjJXbTJhWUwwdWk0b21FeDAxYUVLYkVXWnEzVTRZSSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzg6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9zaXRlLndlYm1hbmlmZXN0IjtzOjU6InJvdXRlIjtzOjEyOiJwd2EubWFuaWZlc3QiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1781542247),
('ANnrlVha1JoumnZ7r5SPRYSIGgKuNp85ilNlkVMN', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36 Edg/149.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoid0luTlZuR1J3NUZ6Z05rb3lsbG1aZ3VHV3JUNTR2N0Rva0NnM2FOdyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzg6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9zaXRlLndlYm1hbmlmZXN0IjtzOjU6InJvdXRlIjtzOjEyOiJwd2EubWFuaWZlc3QiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1781541779),
('APkrHklcHysnesuZDrqyjakGwqESO8bNukjfguVa', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36 Edg/149.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoibnkwWlU3SlJSZDhvTnBjY2g3YnpKMkY2c2lNWnNjRVVXZ3M5VmVBNyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzg6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9zaXRlLndlYm1hbmlmZXN0IjtzOjU6InJvdXRlIjtzOjEyOiJwd2EubWFuaWZlc3QiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1781542193),
('bdOXKsnguZ18Jpb6W1fEhe82abBNqmZGuOXFHXWA', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36 Edg/149.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiTDlmektzTWh0enFEd2dyaWVUNzVIbTd5eUVDZlFsQWl2SHlnczZ4MiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzg6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9zaXRlLndlYm1hbmlmZXN0IjtzOjU6InJvdXRlIjtzOjEyOiJwd2EubWFuaWZlc3QiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1781541367),
('DT0YCUe0CI9nRNjq9AdBsEgxEo238l6Ss6SJB0Nr', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36 Edg/149.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoidENqbkpBejB5V1FYYzlEc2FIZ0trWTR0VmpaTVJBWE9PYXlETVVwdCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzg6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9zaXRlLndlYm1hbmlmZXN0IjtzOjU6InJvdXRlIjtzOjEyOiJwd2EubWFuaWZlc3QiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1781542242),
('EbqGKgLGdfMOccQPlABSyTYZojoFjCd6O8SrYtkf', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36 Edg/149.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoieDlCSnlxSUg0VGRXMjBvTWxhYXh5SEtWOUxzYklUcFB2TlN2a2JyZyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzg6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9zaXRlLndlYm1hbmlmZXN0IjtzOjU6InJvdXRlIjtzOjEyOiJwd2EubWFuaWZlc3QiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1781541755),
('FTRXupO4pRYuV40C0LiYKTWcjtsD8mybDnEqsy2L', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36 Edg/149.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoicU5TOTFmOWs5YmdPbXBXd3h1bHJ4bmROWkRLaTNjSGZQdzNaYlR6eSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzg6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9zaXRlLndlYm1hbmlmZXN0IjtzOjU6InJvdXRlIjtzOjEyOiJwd2EubWFuaWZlc3QiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1781542133),
('GkPcJserRhc8i8nk4vxRyHXI99Px7DNkyn7YhSHw', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36 Edg/149.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiVEY3eWp1Q1F3cEw2NzlMWG1abHpBdDh4RXNrUFJxNkpOZGl2b1JOSiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fX0=', 1781542254),
('guKSL0IejnRM1f3NoAfJo0qd9CCcMzJ3RbuLHJvV', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36 Edg/149.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiQWNhUUIwU0tDdWgzeXhaSWVwUVVUWUQ5SlUxWkZnSXNzNlB6R2hGNyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzg6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9zaXRlLndlYm1hbmlmZXN0IjtzOjU6InJvdXRlIjtzOjEyOiJwd2EubWFuaWZlc3QiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1781542126),
('HKQOzUUpRsoR2Gs5TYyFFxruTxTg1Xeyo5gMVEDx', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36 Edg/149.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoia2FBZFJiM0VmbkJTRXNtdFgyWnlFTW5OR1d6MEMyMWVqckVMdUFhVyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzg6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9zaXRlLndlYm1hbmlmZXN0IjtzOjU6InJvdXRlIjtzOjEyOiJwd2EubWFuaWZlc3QiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1781542129),
('IhtnsXE7bFDIP0cXMmPD1QwNV7SLUj2YE2P6v5rn', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36 Edg/149.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiRmZkRWF3ckxSdzNsejB0eTRvRHg2UXZsMzQwZGZyRXkwOXRxTVJERyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzg6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9zaXRlLndlYm1hbmlmZXN0IjtzOjU6InJvdXRlIjtzOjEyOiJwd2EubWFuaWZlc3QiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1781541508),
('IZq0MxZJ59lTMQiyqhkRrjdZBWY6Gc0FC5Hnl6h2', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36 Edg/149.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiTDdhOHIzZ3VmU2xFV0s3aVRqcTFLWUxVWExHbVJ6QWFuZ1pSWkpPQyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzg6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9zaXRlLndlYm1hbmlmZXN0IjtzOjU6InJvdXRlIjtzOjEyOiJwd2EubWFuaWZlc3QiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1781541757),
('jfgxrbCbhO2sXZmS8uPhnyUDNHTOCBts64Mp5qDB', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36 Edg/149.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiMnpvblpVclhrWktNWGxIMVJiSE9RYmF0ZWI3NnF0cFFlMWFadUF6aSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzg6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9zaXRlLndlYm1hbmlmZXN0IjtzOjU6InJvdXRlIjtzOjEyOiJwd2EubWFuaWZlc3QiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1781542219),
('jxYnxw4KhxA8Tv3HEcRAcEDFpdBcwtaUCbN1R2nR', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36 Edg/149.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiTEFrV3hmemdDU3VMOG56dmYyeDh1b0hXOXVPczdNcktNbWNMNzVjNyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzg6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9zaXRlLndlYm1hbmlmZXN0IjtzOjU6InJvdXRlIjtzOjEyOiJwd2EubWFuaWZlc3QiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1781542213),
('kbcu0JyWM8RJ9FzgzjhWsYP13dDEGh3xrpkTufDT', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36 Edg/149.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiSW1qQnp2SE9wYkZrMVQwOFd2djRqNHhzbW5laERKWjJPUlNxbVlLbiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzg6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9zaXRlLndlYm1hbmlmZXN0IjtzOjU6InJvdXRlIjtzOjEyOiJwd2EubWFuaWZlc3QiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1781542203),
('KsAfy8K8laqyl2v5qrmzhA34v9oZjgvC67MePYuL', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36 Edg/149.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiNTJnTzlzZnBra2dKOElYN0VZUlVkOTJNZGxGNG94d1lVY3diakducSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzg6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9zaXRlLndlYm1hbmlmZXN0IjtzOjU6InJvdXRlIjtzOjEyOiJwd2EubWFuaWZlc3QiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1781541523),
('l3E6SZrtmEhxpTwNQRiGnGBLNtB6TggcRN2WPF0C', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36 Edg/149.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiSTVYNDFBUGg0RHRqYTltVFAxaTJJZXFFN242SDNKNWE5a0VVSTFYVyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzg6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9zaXRlLndlYm1hbmlmZXN0IjtzOjU6InJvdXRlIjtzOjEyOiJwd2EubWFuaWZlc3QiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1781540830),
('lbojkno6aqRZFQRFp9lGxE5ksp5r4MJoYHxX7XgM', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36 Edg/149.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiT0huRko3UERrOXpoMHp0UjE1UjJwUlQzMGMweHNRdmtRdkgybDJqQSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzg6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9zaXRlLndlYm1hbmlmZXN0IjtzOjU6InJvdXRlIjtzOjEyOiJwd2EubWFuaWZlc3QiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1781541768),
('LEjE8vlO75QDhspsMesyvFPh5zkWQbOmhpS4L7T6', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36 Edg/149.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiaE1ad29vdmtLcjZyNmRDQWVDcDQ0SDFNM3pBclNmMDBsTVdkUlNBcSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzg6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9zaXRlLndlYm1hbmlmZXN0IjtzOjU6InJvdXRlIjtzOjEyOiJwd2EubWFuaWZlc3QiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1781542179),
('MjauoaPGmNkOvjpsILaCFnj2EAzSoFlecJQORZxO', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36 Edg/149.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiY2M5RDBmR1B2Ymw2T3RSOU5qTmdQdGd3VzZBbWRrVWFqRmh6ZkdmTSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzg6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9zaXRlLndlYm1hbmlmZXN0IjtzOjU6InJvdXRlIjtzOjEyOiJwd2EubWFuaWZlc3QiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1781542189),
('nlWr7vNAn39el4CPi4fcOL0XRAKYCE3XXeNe0paQ', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36 Edg/149.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiTGppVk1ndExFc0E0eUI0ck9LZzA5ZkdtTE9qSENjZkZiODBSd0R0SSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzg6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9zaXRlLndlYm1hbmlmZXN0IjtzOjU6InJvdXRlIjtzOjEyOiJwd2EubWFuaWZlc3QiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1781542122),
('P2mrk4IZMmTzdx4jSs7oUdWmDY7rD4p6Bbna7aPO', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36 Edg/149.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiWDdCbEpTb09jN2xmSXJmeDdMaVl0MGE3U1JObmVvNGtLUXA0WUJFdiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzg6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9zaXRlLndlYm1hbmlmZXN0IjtzOjU6InJvdXRlIjtzOjEyOiJwd2EubWFuaWZlc3QiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1781540939),
('Ps3in0tRQFrDbkcam3GSMFF7ls0tqLn5Ui2aI5Wl', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36 Edg/149.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoieEJnV3pGSXBuOGFlczNsbnNrckJtbUwyMm5mN2wxVjZheXptOUJXQyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzg6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9zaXRlLndlYm1hbmlmZXN0IjtzOjU6InJvdXRlIjtzOjEyOiJwd2EubWFuaWZlc3QiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1781541771),
('qXpnU9rmltaWzHobne8Kqh76dq59azGYgqZXD2IS', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36 Edg/149.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoicGtjU3N5UGVWeUZMTEZDdXVGYW9hQzFQUmhEaTM5aHlBUVFiT2d2UCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzg6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9zaXRlLndlYm1hbmlmZXN0IjtzOjU6InJvdXRlIjtzOjEyOiJwd2EubWFuaWZlc3QiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1781542254),
('r6DrgjoMCYfieLzBxhHXxuYhYJsBUJAZooXDjKge', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36 Edg/149.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiWlpkd1F3QzI3dHl6S2pqMXlLbUlNTGswOTZSSkRkU1BqMGdJZGpaaCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzg6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9zaXRlLndlYm1hbmlmZXN0IjtzOjU6InJvdXRlIjtzOjEyOiJwd2EubWFuaWZlc3QiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1781541516),
('rSKGkF3zxVoHzhLAoKBYcDaVSM7cHzaMWCqvUsi1', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36 Edg/149.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiZ2JaWlY0dDRkSVBCcXhuc2J3Um1meGl1WVRQQUYzUWZad1E1aUcwMyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzg6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9zaXRlLndlYm1hbmlmZXN0IjtzOjU6InJvdXRlIjtzOjEyOiJwd2EubWFuaWZlc3QiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1781540935),
('rUD23FCxk4eDTsNtHNy2VTXhLSae4hQaQfe1ojfM', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36 Edg/149.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiTmk4SmdQSGhldGFTamY1cHFpTHJBUzdyQXhWQ0dpNW5YZTVETUhzTCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzg6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9zaXRlLndlYm1hbmlmZXN0IjtzOjU6InJvdXRlIjtzOjEyOiJwd2EubWFuaWZlc3QiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1781541363),
('SEz1yTUQw0cHXXSUAhhUU0oBE6pcAk3E2jOR99Km', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36 Edg/149.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoib044ZERZeXhzR2luenh4cElmbXlZZ2FzSFM5SkptSWxxSUlLNDJPViI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzg6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9zaXRlLndlYm1hbmlmZXN0IjtzOjU6InJvdXRlIjtzOjEyOiJwd2EubWFuaWZlc3QiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1781541773),
('vbUF42IOMFxbfjb4nxnF5NH6noHDnat9gX4ATpi4', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36 Edg/149.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiTkE4OGI2Vk93blZwWGpMOVh0dThIYUFHamVHNzdIeWpLVjh5UmNXWiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzg6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9zaXRlLndlYm1hbmlmZXN0IjtzOjU6InJvdXRlIjtzOjEyOiJwd2EubWFuaWZlc3QiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1781542114),
('wr2vfuXIWX1MfbSTpnHQroWfxUvPojxHxtHUQs13', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36 Edg/149.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiM2NINEsyVWdnS2JQNGUyWWZQWWtuS0tONTY1QnNTRUxmZDR3czhZQiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzg6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9zaXRlLndlYm1hbmlmZXN0IjtzOjU6InJvdXRlIjtzOjEyOiJwd2EubWFuaWZlc3QiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1781542185),
('XaazcnMSXkOwkOI5v509bMfULJW4NO1DlMWmLSo3', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36 Edg/149.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiOUg1bTEwRWNpbjgyTWNkVGRzWGcxZVI4bTlQRU9lWG5obkFkWndERCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzg6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9zaXRlLndlYm1hbmlmZXN0IjtzOjU6InJvdXRlIjtzOjEyOiJwd2EubWFuaWZlc3QiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1781540836),
('xQcX5qmpoArnSyIdZGkE9Pmc9GHIj3V43XhVUdtV', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36 Edg/149.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiU0pqMjJVYmZOTHVCV0VtS3FiUTRESGIzQ1E2Qkd5ZHBnNDZnSWNRbyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzg6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9zaXRlLndlYm1hbmlmZXN0IjtzOjU6InJvdXRlIjtzOjEyOiJwd2EubWFuaWZlc3QiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1781542191),
('y0kJmKbpn4RaJ7yqkCWGrWHOvKu9bfXc98WWDxAT', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36 Edg/149.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiMWJ1bzJpOWlOQU5OUDd0Y2xMaHBTdVJ6SzFCSGhHaERpTDJoaWw5TiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzg6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9zaXRlLndlYm1hbmlmZXN0IjtzOjU6InJvdXRlIjtzOjEyOiJwd2EubWFuaWZlc3QiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1781541766),
('YRFt3hdBn7nMSATZT09wY1flyHwX0Opcqo2RjoRv', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36 Edg/149.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiRGNYUlBscjNsWGJWYVJRdmpTMkMwdVNDc0FWSFhaZVo1cU1RYm1yRSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzg6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9zaXRlLndlYm1hbmlmZXN0IjtzOjU6InJvdXRlIjtzOjEyOiJwd2EubWFuaWZlc3QiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1781542251),
('z1oHysaU666BSgj1hf7UOxJPPEysaM0TJKpho3Uw', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36 Edg/149.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiY0M2Zll4TVFEVDM3RWFuemhmWEZsb0pWZHhvMm9sQ3RrWVVQaWRjWSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzg6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9zaXRlLndlYm1hbmlmZXN0IjtzOjU6InJvdXRlIjtzOjEyOiJwd2EubWFuaWZlc3QiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1781542135);

-- --------------------------------------------------------

--
-- Structure de la table `telephonique_rapports`
--

CREATE TABLE `telephonique_rapports` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `campagne_id` bigint(20) UNSIGNED DEFAULT NULL,
  `date_rapport` date NOT NULL,
  `appels_emis` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `appels_joignables` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `appels_non_joignables` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `taux_joignabilite` decimal(6,2) DEFAULT NULL,
  `clients_interesses_nombre` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `clients_interesses_pct` decimal(6,2) DEFAULT NULL,
  `clients_deja_servis_nombre` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `clients_deja_servis_pct` decimal(6,2) DEFAULT NULL,
  `cartes_proposees` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`cartes_proposees`)),
  `propose_visa` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `propose_gim` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `propose_cauris` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `propose_prepayee` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `nj_repondeur` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `nj_numero_errone` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `nj_hors_reseau` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `nj_autres_nombre` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `nj_autres_precision` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `telephonique_rapports`
--

INSERT INTO `telephonique_rapports` (`id`, `user_id`, `campagne_id`, `date_rapport`, `appels_emis`, `appels_joignables`, `appels_non_joignables`, `taux_joignabilite`, `clients_interesses_nombre`, `clients_interesses_pct`, `clients_deja_servis_nombre`, `clients_deja_servis_pct`, `cartes_proposees`, `propose_visa`, `propose_gim`, `propose_cauris`, `propose_prepayee`, `nj_repondeur`, `nj_numero_errone`, `nj_hors_reseau`, `nj_autres_nombre`, `nj_autres_precision`, `created_at`, `updated_at`) VALUES
(1, 22, NULL, '2026-04-03', 31, 16, 15, 51.61, 8, NULL, 0, NULL, '{\"2\":16,\"7\":0,\"8\":16,\"10\":0,\"4\":16,\"6\":0,\"3\":16,\"11\":0,\"9\":0}', 0, 0, 0, 0, 5, 1, 0, 0, NULL, '2026-04-04 10:46:59', '2026-04-04 10:46:59'),
(2, 40, NULL, '2026-03-31', 73, 28, 45, 38.36, 8, NULL, 0, NULL, '{\"2\":28,\"7\":0,\"8\":28,\"10\":0,\"4\":28,\"6\":0,\"3\":28,\"11\":0,\"9\":0}', 0, 0, 0, 0, 10, 2, 5, 20, 'Pas intéressé : 2 , possibilité de vente :18', '2026-04-04 14:20:40', '2026-04-04 14:20:40'),
(3, 40, NULL, '2026-04-01', 59, 31, 28, 52.54, 10, NULL, 0, NULL, '{\"2\":31,\"7\":0,\"8\":31,\"10\":0,\"4\":31,\"6\":0,\"3\":31,\"11\":0,\"9\":0}', 0, 0, 0, 0, 5, 1, 0, 21, 'Pvente:17 , pas intéressé :3, réflexion :1', '2026-04-04 14:22:34', '2026-04-04 14:25:45'),
(4, 40, NULL, '2026-04-02', 81, 57, 24, 70.37, 7, NULL, 3, NULL, '{\"2\":57,\"7\":0,\"8\":57,\"10\":0,\"4\":57,\"6\":0,\"3\":57,\"11\":0,\"9\":0}', 0, 0, 0, 0, 14, 0, 7, 3, 'Pas intéressé :3', '2026-04-04 14:27:52', '2026-04-04 14:31:06'),
(5, 40, NULL, '2026-04-03', 41, 24, 17, 58.54, 18, NULL, 3, NULL, '{\"2\":24,\"7\":0,\"8\":24,\"10\":0,\"4\":24,\"6\":0,\"3\":24,\"11\":0,\"9\":0}', 0, 0, 0, 0, 9, 0, 5, 3, 'Pas intéressé :2 , réflexion :1', '2026-04-04 14:34:01', '2026-04-04 14:34:01'),
(6, 40, 5, '2026-04-07', 100, 55, 45, 55.00, 27, NULL, 1, NULL, '{\"2\":21,\"7\":0,\"8\":6,\"10\":0,\"4\":21,\"6\":0,\"3\":21,\"11\":0,\"9\":0}', 0, 0, 0, 0, 35, 1, 8, 1, 'Pas intéressé : 5', '2026-04-07 20:00:13', '2026-04-07 20:00:13'),
(7, 22, 5, '2026-04-07', 100, 64, 36, 64.00, 20, NULL, 10, NULL, '{\"2\":64,\"7\":0,\"8\":64,\"10\":0,\"4\":64,\"6\":0,\"3\":64,\"11\":0,\"9\":0}', 0, 0, 0, 0, 14, 1, 2, 1, '4 réflexion 1 demande encours 1 personnel', '2026-04-07 21:19:29', '2026-04-07 21:19:29'),
(8, 40, 5, '2026-04-08', 100, 63, 37, 63.00, 13, NULL, 22, NULL, '{\"2\":41,\"7\":41,\"8\":2,\"10\":41,\"4\":41,\"6\":41,\"3\":41,\"11\":41,\"9\":41}', 0, 0, 0, 0, 7, 2, 0, 2, 'Possiblite de vente:19, pas intéressé :8', '2026-04-08 19:35:24', '2026-04-08 19:35:24'),
(9, 22, 5, '2026-04-08', 100, 49, 51, 49.00, 4, NULL, 15, NULL, '{\"2\":49,\"7\":49,\"8\":0,\"10\":49,\"4\":49,\"6\":49,\"3\":49,\"11\":49,\"9\":49}', 0, 0, 0, 0, 18, 0, 0, 0, NULL, '2026-04-09 07:07:40', '2026-04-09 07:07:40'),
(10, 22, 5, '2026-04-09', 62, 37, 25, 59.68, 8, NULL, 8, NULL, '{\"2\":37,\"7\":37,\"8\":0,\"10\":37,\"4\":37,\"6\":37,\"3\":37,\"11\":37,\"9\":37}', 0, 0, 0, 0, 9, 0, 0, 1, 'Réflexion 3, 7 pas intéresser 10 possibles Et on a eu des clients qui ont déjà des cartes prépayées mais ils se plaignent parce que souvent les recharges prennent du temps et même pendant son adhésion quil a mis du temps avant que sa carte ne soit activée', '2026-04-09 15:15:50', '2026-04-09 15:15:50'),
(11, 40, 5, '2026-04-09', 64, 34, 30, 53.13, 7, NULL, 14, NULL, '{\"2\":34,\"7\":10,\"8\":0,\"10\":10,\"4\":34,\"6\":10,\"3\":34,\"11\":10,\"9\":10}', 0, 0, 0, 0, 26, 0, 4, 0, NULL, '2026-04-09 19:11:07', '2026-04-09 19:11:07'),
(12, 40, 5, '2026-04-10', 88, 41, 47, 46.59, 17, NULL, 10, NULL, '{\"2\":41,\"7\":31,\"8\":0,\"10\":31,\"12\":0,\"4\":41,\"6\":31,\"3\":41,\"11\":31,\"9\":31}', 0, 0, 0, 0, 40, 1, 5, 1, 'Pas intéressé :4, réflexion :1, possibilité de v: 15', '2026-04-11 10:48:39', '2026-04-11 10:48:39'),
(13, 22, 5, '2026-04-13', 108, 68, 40, 62.96, 19, NULL, 16, NULL, '{\"2\":68,\"7\":68,\"8\":0,\"10\":68,\"12\":0,\"4\":68,\"6\":68,\"3\":68,\"11\":68,\"9\":68}', 0, 0, 0, 0, 14, 0, 2, 0, NULL, '2026-04-13 15:23:23', '2026-04-13 15:23:23'),
(14, 40, 5, '2026-04-13', 110, 62, 48, 56.36, 14, NULL, 28, NULL, '{\"2\":34,\"7\":34,\"8\":0,\"10\":34,\"12\":0,\"4\":34,\"6\":34,\"3\":34,\"11\":34,\"9\":34}', 0, 0, 0, 0, 38, 1, 6, 2, 'Possiblite v: 18, pas intéressé : 6', '2026-04-13 15:23:41', '2026-04-13 15:23:41'),
(15, 22, 5, '2026-04-14', 125, 77, 48, 61.60, 19, NULL, 27, NULL, '{\"2\":77,\"7\":77,\"8\":0,\"10\":77,\"12\":0,\"4\":77,\"6\":77,\"3\":77,\"13\":77,\"11\":77,\"9\":77}', 0, 0, 0, 0, 14, 0, 2, 0, NULL, '2026-04-14 15:23:43', '2026-04-14 15:23:43'),
(16, 40, 5, '2026-04-14', 125, 78, 47, 62.40, 18, NULL, 44, NULL, '{\"2\":28,\"7\":28,\"8\":0,\"10\":28,\"12\":0,\"4\":28,\"6\":28,\"3\":28,\"13\":28,\"11\":28,\"9\":28}', 0, 0, 0, 0, 38, 0, 6, 3, 'Pv: 3, pas int: 6, num étranger :1, il y’a des clients de l’agence dibida résidence à kita de plaignent de ne pas avoir leur carte après plusieurs demandes mais toujours pas de suite', '2026-04-14 15:25:56', '2026-04-14 15:25:56'),
(17, 22, 5, '2026-04-15', 130, 83, 47, 63.85, 25, NULL, 23, NULL, '{\"2\":83,\"7\":83,\"8\":0,\"10\":83,\"12\":0,\"4\":83,\"6\":83,\"3\":83,\"13\":83,\"11\":83,\"9\":83}', 0, 0, 0, 0, 15, 0, 1, 0, NULL, '2026-04-15 15:18:46', '2026-04-15 15:18:46'),
(18, 40, 5, '2026-04-15', 130, 66, 64, 50.77, 14, NULL, 28, NULL, '{\"2\":42,\"7\":42,\"8\":0,\"10\":42,\"12\":0,\"4\":42,\"6\":42,\"3\":42,\"13\":42,\"11\":42,\"9\":42}', 0, 0, 0, 0, 56, 0, 8, 0, NULL, '2026-04-15 15:24:12', '2026-04-15 15:24:12'),
(19, 40, 5, '2026-04-16', 140, 79, 61, 56.43, 25, NULL, 10, NULL, '{\"2\":51,\"7\":51,\"8\":0,\"10\":51,\"12\":0,\"4\":51,\"6\":51,\"3\":51,\"13\":51,\"11\":51,\"9\":51}', 0, 0, 0, 0, 50, 0, 11, 0, NULL, '2026-04-16 15:18:11', '2026-04-16 15:18:11'),
(20, 22, 5, '2026-04-16', 140, 90, 50, 64.29, 21, NULL, 20, NULL, '{\"2\":90,\"7\":90,\"8\":0,\"10\":90,\"12\":0,\"4\":90,\"6\":90,\"3\":90,\"13\":90,\"11\":90,\"9\":90}', 0, 0, 0, 0, 11, 0, 4, 0, NULL, '2026-04-16 15:24:35', '2026-04-16 15:24:35'),
(21, 22, 5, '2026-04-17', 85, 48, 37, 56.47, 7, NULL, 13, NULL, '{\"2\":48,\"7\":48,\"8\":0,\"10\":48,\"12\":0,\"4\":48,\"6\":48,\"3\":48,\"13\":48,\"11\":48,\"9\":48}', 0, 0, 0, 0, 14, 1, 1, 0, NULL, '2026-04-17 11:46:16', '2026-04-17 11:46:16'),
(22, 40, 5, '2026-04-17', 85, 48, 37, 56.47, 28, NULL, 4, NULL, '{\"2\":37,\"7\":37,\"8\":0,\"10\":37,\"12\":0,\"4\":37,\"6\":37,\"3\":37,\"13\":37,\"11\":37,\"9\":37}', 0, 0, 0, 0, 33, 0, 4, 0, NULL, '2026-04-17 11:59:44', '2026-04-17 11:59:44'),
(23, 22, 5, '2026-04-20', 110, 63, 47, 57.27, 6, NULL, 25, NULL, '{\"2\":63,\"7\":63,\"8\":0,\"10\":63,\"12\":0,\"4\":63,\"6\":63,\"3\":63,\"13\":63,\"11\":63,\"9\":63}', 0, 0, 0, 0, 6, 0, 0, 0, NULL, '2026-04-20 18:56:41', '2026-04-20 18:56:41'),
(24, 40, 5, '2026-04-20', 110, 66, 44, 60.00, 36, NULL, 5, NULL, '{\"2\":57,\"7\":57,\"8\":0,\"10\":57,\"12\":0,\"4\":57,\"6\":57,\"3\":57,\"13\":57,\"11\":57,\"9\":57}', 0, 0, 0, 0, 38, 0, 6, 0, NULL, '2026-04-20 22:05:26', '2026-04-20 22:05:26');

-- --------------------------------------------------------

--
-- Structure de la table `types_cartes`
--

CREATE TABLE `types_cartes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(50) NOT NULL,
  `actif` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `types_cartes`
--

INSERT INTO `types_cartes` (`id`, `code`, `actif`, `created_at`, `updated_at`) VALUES
(2, 'ADAN', 1, '2026-03-30 10:32:01', '2026-03-30 10:32:01'),
(3, 'LAAFIA', 1, '2026-03-30 10:32:32', '2026-03-30 10:32:32'),
(4, 'ELITE', 1, '2026-03-30 10:32:54', '2026-03-30 10:32:54'),
(6, 'GIM_UEMOA', 1, '2026-04-01 11:38:19', '2026-04-01 11:38:19'),
(7, 'CAURIS_CLASSIQUE', 1, '2026-04-01 11:38:50', '2026-04-01 11:38:50'),
(8, 'CAURIS_EPARGNE', 1, '2026-04-01 11:39:20', '2026-04-01 11:39:20'),
(9, 'VISA_OPTMUM', 1, '2026-04-01 11:39:44', '2026-04-01 11:39:44'),
(10, 'CUARIS_MANSA', 1, '2026-04-01 11:40:14', '2026-04-01 11:40:14'),
(11, 'VISA_GOLD', 1, '2026-04-01 11:40:36', '2026-04-01 11:40:36'),
(12, 'DUNIA_PLUS', 1, '2026-04-10 13:35:39', '2026-04-10 13:35:39'),
(13, 'VISA_CLASSIQUE', 1, '2026-04-14 12:44:42', '2026-04-14 12:44:42');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `prenom` varchar(100) DEFAULT NULL,
  `telephone` varchar(20) DEFAULT NULL,
  `role` enum('admin','commercial','commercial_telephonique','direction') NOT NULL DEFAULT 'commercial',
  `agence_id` bigint(20) UNSIGNED DEFAULT NULL,
  `actif` tinyint(1) NOT NULL DEFAULT 1,
  `adresse_contrat` text DEFAULT NULL,
  `piece_identite_ref` varchar(191) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `name`, `prenom`, `telephone`, `role`, `agence_id`, `actif`, `adresse_contrat`, `piece_identite_ref`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Sylla', NULL, NULL, 'admin', NULL, 1, NULL, NULL, NULL, NULL, '$2y$12$YiS76t9BDw/zFkK6HYShVu0his8C9ALMm.j./DDdtH63cETULU5dy', 'QLIX7jSt9iXvb4GYIAT4nn4EUmoEWfErCocdAgG8VxOcs2bDphyBpygc1hDi', '2026-03-27 09:59:32', '2026-03-27 09:59:32'),
(2, 'Dante', NULL, NULL, 'admin', NULL, 1, NULL, NULL, NULL, NULL, '$2y$12$8qsGql.9kVC4pSs8EGtSNuZXcLYlAmySGH7880ItYTD0uifV4YXiK', NULL, '2026-03-27 09:59:33', '2026-03-27 09:59:33'),
(3, 'Koita', NULL, NULL, 'admin', NULL, 1, NULL, NULL, NULL, NULL, '$2y$12$4QRTUMjDKWSljti4dD42d.eLApbNUEf6bhmTpQjcsIzsW.l93yvPy', NULL, '2026-03-27 09:59:33', '2026-03-27 09:59:33'),
(4, 'Sacko', NULL, NULL, 'admin', NULL, 1, NULL, NULL, NULL, NULL, '$2y$12$aw83Qx9GfGUnemobyRmRQ.j065Kw9PJNO1As11XZQ9JmKiUPt8ByG', NULL, '2026-03-27 09:59:33', '2026-03-27 09:59:33'),
(5, 'Cisse', NULL, NULL, 'admin', NULL, 1, NULL, NULL, NULL, NULL, '$2y$12$OYAqF9T9cIUBYq.PcNsncumRQALbYHtx60bQJKBgBA2HdS6mahZJe', NULL, '2026-03-27 09:59:34', '2026-03-27 09:59:34'),
(6, 'Yaya', NULL, NULL, 'admin', NULL, 1, NULL, NULL, NULL, NULL, '$2y$12$p62ee/iwjYCquB0q8YQEE.pQRPGRatQE7ChofMf6F/E7PlkuUlLKy', NULL, '2026-03-27 09:59:34', '2026-03-27 09:59:34'),
(10, 'THERA', 'Mariam', '74082712', 'commercial', 15, 1, NULL, NULL, 'juin2026.74082712@import.gda', NULL, '$2y$12$LPghzUe0o7x38ZjMGMeqwO3FgKplNs7SOle/6o.BtA2NHRMUC90yK', 'lkriJcVImx6q2VnWZBzY4vYmxCKkmlWusryDCU4ngVAHv8Rj6JJ2EvlLcasI', '2026-03-30 14:37:06', '2026-06-15 16:50:51'),
(11, 'NIAMBLE', 'Aissata N', '66904040', 'commercial', 36, 1, NULL, NULL, 'juin2026.66904040@import.gda', NULL, '$2y$12$v93UXmTBro2tKfpvpKRex.L3ds8M4jxpJ3Q844Oi3oylAeh0vR2C.', NULL, '2026-03-30 14:37:06', '2026-06-15 16:50:51'),
(13, 'DIAKITE', 'Nagnouma TOURE', '79053641', 'commercial', 47, 1, NULL, NULL, 'juin2026.79053641@import.gda', NULL, '$2y$12$d1rbwzkkE/V0rtgjDx3ndOyqAVzhPrGJh/mz7uEyP9JjBfydADxpW', NULL, '2026-03-30 14:37:06', '2026-06-15 16:50:51'),
(14, 'MAIGA', 'Adiaratou A', '90889198', 'commercial', 48, 1, NULL, NULL, 'juin2026.90889198@import.gda', NULL, '$2y$12$nRt18idcqHrduYPFYIYNJ.kXhd6UfxS.CQGN5T8RhTrFbc.CvvWAK', NULL, '2026-03-30 14:37:07', '2026-06-15 16:50:51'),
(15, 'DRAME', 'Sadio', '92096399', 'commercial', 7, 0, NULL, NULL, NULL, NULL, '$2y$12$aBiBuP5luYfJ0qi1FZv8w.s.Pe1lN4IqIa7sC9OLKZJAF.wh9os7m', NULL, '2026-03-30 14:37:07', '2026-06-15 16:50:51'),
(16, 'DIALLO', 'Ami Colley', '76040083', 'commercial', 8, 0, NULL, NULL, NULL, NULL, '$2y$12$NHOutBw1y/gNbmN5eHZ.tO3cdbZQGVhmMZC36qSj3ldQ7UhpowAme', NULL, '2026-03-30 14:37:07', '2026-06-15 16:50:51'),
(17, 'SANGARE', 'Fatimata', '78754962', 'commercial', 33, 1, NULL, NULL, 'juin2026.78754962@import.gda', NULL, '$2y$12$/LCaWxt54ByDK/LBYZ7ZoOE8TjfuiwVepA1bBiF/6vKOK202UZDFu', 'qEQC1F6uK05KA9wGe7wUPmsWX01HXrwr8Q3k4qHO3Q7MjiyyotygSEi9vbYB', '2026-03-30 14:37:07', '2026-06-15 16:50:51'),
(18, 'CAMARA', 'Ali Badara', '73907530', 'commercial', 49, 1, NULL, NULL, 'juin2026.73907530@import.gda', NULL, '$2y$12$ihzdAl7mTPiF8lFKytI7deWbO46MTfXFuUQCQOdeXsrY2t1V2BHUW', NULL, '2026-03-30 14:37:08', '2026-06-15 16:50:51'),
(19, 'TOURE', 'Mary N', '69098738', 'commercial', 35, 1, NULL, NULL, 'juin2026.69098738@import.gda', NULL, '$2y$12$sX6DUjKFunJNYJikoqWq2uRuy6S58pZhjqRPaVzGTh0CWhxPvRPVa', 'm49TfGhPVsM6E94kwjBNN5huL242hJ7scq5EiZJutEZHNloyH9c5x5WHYlOo', '2026-03-30 14:37:08', '2026-06-15 16:50:51'),
(20, 'SERITA', 'Massitan', '79018138', 'commercial', 12, 0, NULL, NULL, NULL, NULL, '$2y$12$JCnWhVTrUNvWhDT1SYtrXeUoA2mNvKA0TrgyV68I0.akx3eJfATYW', NULL, '2026-03-30 14:37:08', '2026-06-15 16:50:51'),
(21, 'FOFANA', 'Kadiatou', '76612042', 'commercial', 16, 1, NULL, NULL, 'juin2026.76612042@import.gda', NULL, '$2y$12$YUyMTISPQ8BzvdiUlKeFJuFkHabvIabefPLE1CQMrVo9AB/lKhpm.', 'mEwQKmQVfIkrkzN5dHGfKFXe0T5OQY6cDAstC5WNfZppSjR6Mm6HMVzy5EzT', '2026-03-30 14:37:08', '2026-06-15 16:50:51'),
(22, 'KANOUTE', 'Nènè', '74353690', 'commercial_telephonique', 21, 1, NULL, NULL, 'juin2026.74353690@import.gda', NULL, '$2y$12$/zCupozEYCzbdYzdeoHUZ.temOPgZSz.lUlXW4DhB1vjU3bd86iH2', 'kkGoJ6fwMH7cjlgypqF7Wy7ejS1l5xJLCetyOFwvO99EqVE6w93dl4iRlWPt', '2026-03-30 14:37:08', '2026-06-15 16:50:51'),
(23, 'COULIBALY', 'Aminata', '71766277', 'commercial', 2, 1, NULL, NULL, 'juin2026.71766277@import.gda', NULL, '$2y$12$HjUpZgV.yGHEEDLLnRVR5OVEusRTy4m7A41AYpQC7v7J6DHnat2Ce', NULL, '2026-03-30 14:37:09', '2026-06-15 16:50:51'),
(24, 'SANGARE', 'Binta', '71616201', 'commercial', 16, 0, NULL, NULL, NULL, NULL, '$2y$12$OLwPWEg1OgrbCoHDKnkl.uBQMiJeaRfP9zdrn2SaGwI4zovIrOjC2', NULL, '2026-03-30 14:37:09', '2026-06-15 16:50:51'),
(25, 'TOGOLA', 'Lassina', '83140127', 'commercial', 34, 1, NULL, NULL, 'juin2026.83140127@import.gda', NULL, '$2y$12$f/VvOGcRgsEKHoc.Cv3pcenUGpI7v3AyoAZyHI8PVwC/Xy8jPpfEC', NULL, '2026-03-30 14:37:09', '2026-06-15 16:50:51'),
(26, 'DABITAO', 'Oumou', '64924953', 'commercial', 18, 0, NULL, NULL, NULL, NULL, '$2y$12$xWeG4KQJkFJ4kv9VQcthW.GpjQLQ63OED0CsqFxOX9LivmY2FN.8C', NULL, '2026-03-30 14:37:09', '2026-06-15 16:50:51'),
(27, 'TRAORE', 'Adama', '70277320', 'commercial', 53, 1, NULL, NULL, 'juin2026.70277320@import.gda', NULL, '$2y$12$nUc/3AyAFwjHz3HNWS7XUeVMNSxff1de6jlCSvtPwRH41TIP3TQb.', 'BIGKo2BX1yxOUvhJEJvGo8HNaEZMkN4PB3cZq7Tse17VXS5KL8px6Ns1drcp', '2026-03-30 14:37:10', '2026-06-15 16:50:51'),
(28, 'Amadou', 'Houneijata', '76326633', 'commercial', 21, 0, NULL, NULL, NULL, NULL, '$2y$12$EVYwNaFesuQSktIrQMbxgORTlBew5uN2V7DP/krrM7lOFdOzMjq46', NULL, '2026-03-30 14:37:10', '2026-06-15 16:50:51'),
(29, 'THIAM', 'Mohamed Aly', '70442854', 'commercial', 22, 1, NULL, NULL, 'juin2026.70442854@import.gda', NULL, '$2y$12$lYtFHmfm8UZt8eQccTW22ea3o3eIOUTBNaz7yayYVVgmO8/B33UZ.', NULL, '2026-03-30 14:37:10', '2026-06-15 16:50:51'),
(30, 'TOURE', 'Harerata', '89501249', 'commercial', 23, 0, NULL, NULL, NULL, NULL, '$2y$12$pHaDeUCRezZ6K1Y7x2FMNeGCnn5k.YLQkSV6hSuOjdEgWsemNW.ty', NULL, '2026-03-30 14:37:10', '2026-06-15 16:50:51'),
(31, 'Touré', 'Hawa', '79771505', 'commercial', 24, 0, NULL, NULL, NULL, NULL, '$2y$12$ijhrWX1twGuDBEb6x4xg8OmameTsok6K5MHVFJltYtzwk0oSxf4f2', NULL, '2026-03-30 14:37:11', '2026-06-15 16:50:51'),
(32, 'NIANGALE', 'Fatoumata', '93244009', 'commercial', 25, 0, NULL, NULL, NULL, NULL, '$2y$12$uCrnZu7Hz7WGj2J7N0CSke7mIOWBvayCMsl0iw/IjuksF0hOoyOby', NULL, '2026-03-30 14:37:11', '2026-06-15 16:50:51'),
(33, 'SANOGO', 'Fatoumata', '92330460', 'commercial', 26, 1, NULL, NULL, 'juin2026.92330460@import.gda', NULL, '$2y$12$c5JXMF1Fllqa.N8yapU1DeoIHVc.v5h8EjBAm7ZbEMHXWui.yhPb.', 'evFFNn9J8ASt37j9rcBLYaeAIeZhqubURXDDjfBHZWhNDspGjITWgt8oimHy', '2026-03-30 14:37:11', '2026-06-15 16:50:51'),
(34, 'SIDIBE', 'Kadidiatou', '92021391', 'commercial', 27, 0, NULL, NULL, NULL, NULL, '$2y$12$QPJHI5mt7p89ZRqrQHb7H.pbyAQ87VSSBpxPyI04YpAs.xcNfRAmy', 'euOpOhgD5q9osnXJio9ZdInu7nQNOT38bSptWbyyMURUIuDAJwHqJksaXhmh', '2026-03-30 14:37:11', '2026-06-15 16:50:51'),
(35, 'DEMBELE', 'Karidiata', '60625221', 'commercial', 56, 1, NULL, NULL, 'juin2026.60625221@import.gda', NULL, '$2y$12$nOgDvy5we9FP4Xrpb3IudexHR4Z0QdaZXloiCOW7dr1w8EOyNUNZS', 'Q0AZkcFZO9J3PQzXKLmVCHxpnSSyy4ChyXwF7crHY3wnuwXCMfstsuLBjINV', '2026-03-30 14:37:11', '2026-06-15 16:50:51'),
(36, 'TRAORE', 'Mariam Bagna', '94888495', 'commercial', 29, 0, NULL, NULL, NULL, NULL, '$2y$12$fFwCfLTkeJ18HrVwB1mWHOOROkbs9ClMedEDEetAQeoSoyv1KjDW2', 'gxK299wWIbYwMXF1r7w8WYGQ77an0W0sidgBr6R85GgQry4gGeMGpNXc5c1t', '2026-03-30 14:37:12', '2026-06-15 16:50:51'),
(37, 'HAIDARA', 'Awa', '76277641', 'commercial', 30, 0, NULL, NULL, NULL, NULL, '$2y$12$69NM81Swn4neNsUeforF6OIZiNhba5cT8w68XdYjK6OSzDkIiVpkq', 'SG9jIKlHW1UrC6rgfPbfICe6a7P3BddfYu8dY3MZPQaEFkXSHM11AJp1jn2S', '2026-03-30 14:37:12', '2026-06-15 16:50:51'),
(38, 'SISSOKO', 'Djeneba', '69418521', 'commercial', 31, 1, NULL, NULL, 'juin2026.69418521@import.gda', NULL, '$2y$12$aWfYbHjYJbZ0IM/gOqc2i.4PBzktJCoRBwtq0sPSRJV2Jx2qYZ2K.', 'JqHc4JEuNQ0TVGqORfQROdMM0DD6Trkc86BT5xMsi4oGleOyjCXvinCPpHJ2', '2026-03-30 14:37:12', '2026-06-15 16:50:51'),
(39, 'Direction', 'Générale', '22300000999', 'direction', NULL, 1, NULL, NULL, 'direction@bdm.local', NULL, '$2y$12$zowXWA7fiat5CMlDrMHxru.K.ZSfgcK/T1VpJITG1fAYkKgbH1to.', NULL, '2026-03-30 15:43:37', '2026-03-30 15:43:37'),
(40, 'KANSAYE', 'Diahara', '78522819', 'commercial_telephonique', 7, 1, NULL, NULL, 'juin2026.78522819@import.gda', NULL, '$2y$12$LoU9HXU0eUXZOdZKcfs7l.u1FER/DrAUtw/zOxkTpRzoPsdV8jUYO', 'AYypS6yYpKaci42S2K2ZBCqdqrvTpQxMZKgCgE2jWuKUd5W7UVq2MuDSWBQl', '2026-03-31 11:24:57', '2026-06-15 16:50:51'),
(41, 'KONE', 'Modibo', '83840345', 'commercial', 32, 0, NULL, NULL, 'avril2.vague.83840345@import.gda', NULL, '$2y$12$yBqojrD.LimX21LuW0eI3ubS.wrwl9Vmu083.VDw6aaPg7xahEnTy', 'UqROuDhrUcUjiUd3JhcpSZtgM0bDau5wW7dIouRl3XMgI6oCKt9VhIzrRXnH', '2026-04-10 09:46:18', '2026-06-15 16:50:51'),
(43, 'DIARRA', 'Soumaila', '91105337', 'commercial', 34, 0, NULL, NULL, NULL, NULL, '$2y$12$WqYPNzXr090tet9ocdSoUeNHCAqM3zfcP6JS.Iehj7eZRdOe1SgJO', NULL, '2026-04-10 09:46:19', '2026-06-15 16:50:51'),
(44, 'TOUNKARA', 'Mamadou', '70122814', 'commercial', 35, 0, NULL, NULL, 'avril2.vague.70122814@import.gda', NULL, '$2y$12$3O9230IderMlPJRu.WlDFOqAbC2AaF.5ZmBJJO2BT1aq4.Zs3ekmO', 'Na5XYiuodXzS6Npln9wPdj5Zv2VuKBLfjV5oqvIcqSyEROYhUzPE7wVdy2a7', '2026-04-10 09:46:19', '2026-06-15 16:50:51'),
(45, 'SIDIBE', 'Djelika KEITA', '72715555', 'commercial', 37, 1, NULL, NULL, 'juin2026.72715555@import.gda', NULL, '$2y$12$iY2ukJ6Xl03w02Vi5yfjruCnfnJTXwA6Q1BJWS96yJ3AsMe2OkVeS', 'Qeh7yeTdnAWTK3F6eRGzfvckG9Rk2AX65CZiYOGR7Z0Q2w3t2t0hyH6JczLm', '2026-04-10 09:46:19', '2026-06-15 16:50:51'),
(46, 'DIARRA', 'Assetou YALCOYE', '90983335', 'commercial', 37, 0, NULL, NULL, 'avril2.vague.90983335@import.gda', NULL, '$2y$12$BoAM9Co.Wqmwe9w1oyECvuVF/PMIre4gXRrc7.9xtqIzKRYus0G3m', '7HfHNDjRkW3uiEdShlBOCUJPBUfVrvjL95CIL8A1sXshDRH5cgviiSqf6kka', '2026-04-10 09:46:20', '2026-06-15 16:50:51'),
(47, 'COULIBALY', 'Mamadou', '76411856', 'commercial', 6, 1, NULL, NULL, 'juin2026.76411856@import.gda', NULL, '$2y$12$4di2unR0aRsl8JPaJ83nVOTHfJZHCHbPNqGBCLsvkigj7sWCTF1ZG', 'eLz3GHBUhd2FLIb4iopDoSHQloeHRIbSN2Naok7oZVa4uxPlOXZ4euJfihJ2', '2026-04-10 09:46:20', '2026-06-15 16:50:51'),
(48, 'MACALOU', 'Adama', '71690729', 'commercial', 39, 0, NULL, NULL, 'avril2.vague.71690729@import.gda', NULL, '$2y$12$XPnHs7X9EK9UJCUhAyuC7OocPpvEvZ0DVoooYo.CQO5oxfUiRQ8Pi', 'hoCnWL8E96VvmgOdazqYXMfklMVmNA2y1dvVaG67xrQtJ4I0JhINS5nFipS6', '2026-04-10 09:46:20', '2026-06-15 16:50:51'),
(50, 'TOURE', 'Imran', '92574790', 'commercial', 43, 0, NULL, NULL, NULL, NULL, '$2y$12$I4N525c00NBiiRwLBf0WB.ZpfiF2EEqmLej8xF1F3dbkdKKU.P8U6', 'buaN5P6jEopiolNbC84OWAHdZJ9XyOerassNUMWm1ujAr9O7zCuf7kRoXQLR', '2026-04-10 09:46:21', '2026-06-15 16:50:51'),
(51, 'BATHILY', 'Maimounata', '65893863', 'commercial', 42, 1, NULL, NULL, 'juin2026.65893863@import.gda', NULL, '$2y$12$6MQ7fScf7LfOBwcuIBkX/OqeqyhiCYcKgdKdtlAArugZlhrn4a.xq', NULL, '2026-04-10 09:46:21', '2026-06-15 16:50:51'),
(52, 'TRAORE', 'Youssouf', '63032329', 'commercial', 19, 0, NULL, NULL, NULL, NULL, '$2y$12$Mac8X5Tln11R/Fr7TJZ3Me6jfRPdpzn3fd.V1b3/58/wIWy.m.MHq', NULL, '2026-04-10 12:18:24', '2026-06-15 16:50:51'),
(54, 'DEMBELE', 'Mama', '74279847', 'commercial', 44, 0, NULL, NULL, NULL, NULL, '$2y$12$Qt/VV9MM9/2hsL25lX7YKu8ztBb7WjUCcBi6qR9dsvvPi6IxH5Tye', NULL, '2026-04-14 10:31:22', '2026-06-15 16:50:51'),
(55, 'COULIBALY', 'Awa', '79790604', 'commercial', 52, 1, NULL, NULL, 'juin2026.79790604@import.gda', NULL, '$2y$12$1/R/VisYAbweBXd2zqjuNe3gih7l5N3P.FoUfNKvrxM0NHE8SyDla', NULL, '2026-04-14 10:31:23', '2026-06-15 16:50:51'),
(56, 'Dymo', 'Labs', '83757033', 'commercial', 16, 1, NULL, NULL, NULL, NULL, '$2y$12$b8sXEGCU68uYTc5WbsFry.sS9rqPRndjWccEs0Nl.hNd34wnLK0b2', NULL, '2026-06-11 10:57:13', '2026-06-15 16:28:58'),
(57, 'TANGARA', 'AMINATA', '71700505', 'commercial', 9, 1, NULL, NULL, 'juin2026.71700505@import.gda', NULL, '$2y$12$7kc0RjoCBRIJOXXHooj7mOVOh1ag2GA2MMwoaKMga5X88DyvH0g2K', NULL, '2026-06-15 16:33:24', '2026-06-15 16:50:51'),
(58, 'MAIGA', 'Fatoumata', '76636578', 'commercial', 32, 1, NULL, NULL, 'juin2026.76636578@import.gda', NULL, '$2y$12$S/oNGkl7ldMYPWrCuWcIYu5/6Fr0Y3C1vQZ2BZLzWorAwi8if7L9S', NULL, '2026-06-15 16:33:25', '2026-06-15 16:50:51'),
(59, 'KONATE', 'Maimouna', '70179839', 'commercial', 50, 1, NULL, NULL, 'juin2026.70179839@import.gda', NULL, '$2y$12$Mtxb9IMu2lW1c.UN4zLWyu/vLsvu.hUy9BMFmNimME5OxmTwchumq', NULL, '2026-06-15 16:33:26', '2026-06-15 16:50:51'),
(60, 'SAGONO', 'FATOUMATA', '71010050', 'commercial', 51, 1, NULL, NULL, 'juin2026.71010050@import.gda', NULL, '$2y$12$d0Yq6udh6ek5h/UP9udvPuLvG7KJNxZKf0w6QtDWQedShT5dWpzgC', NULL, '2026-06-15 16:33:26', '2026-06-15 16:50:51'),
(61, 'DIARRE', 'Assetou Yalcoye', '66986621', 'commercial', 5, 1, NULL, NULL, 'juin2026.66986621@import.gda', NULL, '$2y$12$pw7N7eXEcA.KmMe0e/1VjuIE2mnIzbEzs8/lNv0BGgOpRDvmQJ5Qm', NULL, '2026-06-15 16:33:28', '2026-06-15 16:50:51'),
(62, 'DEMBELE', 'Salimata', '72789105', 'commercial', 54, 1, NULL, NULL, 'juin2026.72789105@import.gda', NULL, '$2y$12$N4QDKC6jJ6SunzoKSm9s5.HHUtpcWc3wVnJDNszumgAZjETbpFy0y', NULL, '2026-06-15 16:33:28', '2026-06-15 16:50:51'),
(63, 'THIAM', 'Fatoumata', '92274352', 'commercial', 8, 1, NULL, NULL, 'juin2026.92274352@import.gda', NULL, '$2y$12$oHIpeIMhZBZ.KCHKDfQ37u7J.5197o.kf9PN6/1ySbeh81IV72nny', NULL, '2026-06-15 16:33:28', '2026-06-15 16:50:51'),
(64, 'GAKOU', 'Oumar', '79787541', 'commercial', 55, 1, NULL, NULL, 'juin2026.79787541@import.gda', NULL, '$2y$12$uhC9VaFcdHKC6whC3zLKvO3EiU3AWRDKT5H0kWVddR5tnzorXC.62', NULL, '2026-06-15 16:33:29', '2026-06-15 16:50:51'),
(65, 'KAMATE', 'Sitan', '90464123', 'commercial', 24, 1, NULL, NULL, 'juin2026.90464123@import.gda', NULL, '$2y$12$NjOK2CMq0bq4hK9HVB/8gOkn/vw3HQUvZyP68ardCviLCcL5vQfuC', NULL, '2026-06-15 16:33:30', '2026-06-15 16:50:51');

-- --------------------------------------------------------

--
-- Structure de la table `user_login_logs`
--

CREATE TABLE `user_login_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `logged_in_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` varchar(512) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `user_login_logs`
--

INSERT INTO `user_login_logs` (`id`, `user_id`, `logged_in_at`, `ip_address`, `user_agent`, `created_at`, `updated_at`) VALUES
(1, 5, '2026-04-03 16:14:57', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-03 16:14:57', '2026-04-03 16:14:57'),
(2, 40, '2026-04-03 16:15:29', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-03 16:15:29', '2026-04-03 16:15:29'),
(3, 5, '2026-04-03 16:17:59', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-03 16:17:59', '2026-04-03 16:17:59'),
(4, 5, '2026-04-03 15:30:02', '153.67.69.96', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-03 15:30:02', '2026-04-03 15:30:02'),
(5, 26, '2026-04-03 15:30:25', '217.64.98.152', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', '2026-04-03 15:30:25', '2026-04-03 15:30:25'),
(6, 40, '2026-04-03 15:37:47', '153.67.69.96', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-03 15:37:47', '2026-04-03 15:37:47'),
(7, 25, '2026-04-03 22:01:55', '41.73.98.142', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/29.0 Chrome/136.0.0.0 Mobile Safari/537.36', '2026-04-03 22:01:55', '2026-04-03 22:01:55'),
(8, 26, '2026-04-04 06:20:11', '217.64.107.175', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', '2026-04-04 06:20:11', '2026-04-04 06:20:11'),
(9, 1, '2026-04-04 08:00:58', '41.73.104.5', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', '2026-04-04 08:00:58', '2026-04-04 08:00:58'),
(10, 16, '2026-04-04 08:13:25', '41.73.105.32', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/29.0 Chrome/136.0.0.0 Mobile Safari/537.36', '2026-04-04 08:13:25', '2026-04-04 08:13:25'),
(11, 5, '2026-04-04 09:20:38', '153.67.69.244', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', '2026-04-04 09:20:38', '2026-04-04 09:20:38'),
(12, 31, '2026-04-04 09:51:01', '41.73.107.246', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_6 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.0 Mobile/15E148 Safari/604.1', '2026-04-04 09:51:01', '2026-04-04 09:51:01'),
(13, 20, '2026-04-04 10:31:41', '41.73.104.152', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', '2026-04-04 10:31:41', '2026-04-04 10:31:41'),
(14, 10, '2026-04-04 10:58:22', '41.221.187.17', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.4 Mobile/15E148 Safari/604.1', '2026-04-04 10:58:22', '2026-04-04 10:58:22'),
(15, 38, '2026-04-04 11:05:14', '41.73.109.181', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Mobile Safari/537.36', '2026-04-04 11:05:14', '2026-04-04 11:05:14'),
(16, 17, '2026-04-04 11:28:42', '41.73.110.89', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Mobile/15E148 Safari/604.1', '2026-04-04 11:28:42', '2026-04-04 11:28:42'),
(17, 1, '2026-04-04 11:42:41', '41.73.104.5', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', '2026-04-04 11:42:41', '2026-04-04 11:42:41'),
(18, 17, '2026-04-04 12:50:52', '41.73.123.64', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Mobile/15E148 Safari/604.1', '2026-04-04 12:50:52', '2026-04-04 12:50:52'),
(19, 25, '2026-04-04 13:41:02', '41.73.98.142', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/29.0 Chrome/136.0.0.0 Mobile Safari/537.36', '2026-04-04 13:41:02', '2026-04-04 13:41:02'),
(20, 29, '2026-04-04 13:53:34', '41.221.187.47', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Mobile/15E148 Safari/604.1', '2026-04-04 13:53:34', '2026-04-04 13:53:34'),
(21, 23, '2026-04-04 14:07:15', '41.73.98.131', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_5 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.5 Mobile/15E148 Safari/604.1', '2026-04-04 14:07:15', '2026-04-04 14:07:15'),
(22, 20, '2026-04-04 14:07:53', '41.73.98.97', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', '2026-04-04 14:07:53', '2026-04-04 14:07:53'),
(23, 40, '2026-04-04 14:13:09', '217.64.103.79', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.7 Mobile/15E148 Safari/604.1', '2026-04-04 14:13:09', '2026-04-04 14:13:09'),
(24, 29, '2026-04-04 14:22:00', '41.221.187.47', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Mobile/15E148 Safari/604.1', '2026-04-04 14:22:00', '2026-04-04 14:22:00'),
(25, 36, '2026-04-04 14:25:20', '41.73.110.51', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Mobile Safari/537.36', '2026-04-04 14:25:20', '2026-04-04 14:25:20'),
(26, 35, '2026-04-04 15:46:02', '153.67.69.244', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', '2026-04-04 15:46:02', '2026-04-04 15:46:02'),
(27, 35, '2026-04-04 15:51:09', '217.64.100.246', 'Mozilla/5.0 (Linux; U; Android 15; fr-fr; SM-A145F Build/AP3A.240905.015.A2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/111.0.5563.116 Mobile Safari/537.36 PHX/20.7', '2026-04-04 15:51:10', '2026-04-04 15:51:10'),
(28, 1, '2026-04-04 19:24:35', '41.73.104.205', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', '2026-04-04 19:24:35', '2026-04-04 19:24:35'),
(29, 31, '2026-04-04 21:02:25', '41.221.189.65', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_6 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.0 Mobile/15E148 Safari/604.1', '2026-04-04 21:02:25', '2026-04-04 21:02:25'),
(30, 36, '2026-04-04 21:31:37', '41.73.107.20', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Mobile Safari/537.36', '2026-04-04 21:31:37', '2026-04-04 21:31:37'),
(31, 25, '2026-04-05 09:24:12', '41.73.119.250', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/29.0 Chrome/136.0.0.0 Mobile Safari/537.36', '2026-04-05 09:24:12', '2026-04-05 09:24:12'),
(32, 25, '2026-04-05 19:15:57', '41.73.119.250', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/29.0 Chrome/136.0.0.0 Mobile Safari/537.36', '2026-04-05 19:15:57', '2026-04-05 19:15:57'),
(33, 10, '2026-04-05 21:52:48', '41.73.123.240', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.4 Mobile/15E148 Safari/604.1', '2026-04-05 21:52:48', '2026-04-05 21:52:48'),
(34, 1, '2026-04-06 00:22:16', '41.73.104.205', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', '2026-04-06 00:22:16', '2026-04-06 00:22:16'),
(35, 34, '2026-04-06 12:25:16', '41.221.187.243', 'Mozilla/5.0 (iPhone; CPU iPhone OS 15_2 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/15.2 Mobile/15E148 Safari/604.1', '2026-04-06 12:25:16', '2026-04-06 12:25:16'),
(36, 25, '2026-04-06 18:06:07', '154.118.186.132', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/29.0 Chrome/136.0.0.0 Mobile Safari/537.36', '2026-04-06 18:06:07', '2026-04-06 18:06:07'),
(37, 4, '2026-04-06 23:50:35', '41.73.98.118', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10.15; rv:149.0) Gecko/20100101 Firefox/149.0', '2026-04-06 23:50:35', '2026-04-06 23:50:35'),
(38, 4, '2026-04-07 04:54:00', '41.73.98.118', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10.15; rv:149.0) Gecko/20100101 Firefox/149.0', '2026-04-07 04:54:00', '2026-04-07 04:54:00'),
(39, 11, '2026-04-07 08:00:08', '217.64.103.102', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', '2026-04-07 08:00:08', '2026-04-07 08:00:08'),
(40, 10, '2026-04-07 08:19:14', '41.221.187.17', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.4 Mobile/15E148 Safari/604.1', '2026-04-07 08:19:14', '2026-04-07 08:19:14'),
(41, 31, '2026-04-07 08:21:24', '41.221.181.144', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_6 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.0 Mobile/15E148 Safari/604.1', '2026-04-07 08:21:24', '2026-04-07 08:21:24'),
(42, 14, '2026-04-07 08:30:19', '41.221.189.43', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_4_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.4 Mobile/15E148 Safari/604.1', '2026-04-07 08:30:19', '2026-04-07 08:30:19'),
(43, 4, '2026-04-07 08:31:43', '74.244.87.78', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10.15; rv:149.0) Gecko/20100101 Firefox/149.0', '2026-04-07 08:31:43', '2026-04-07 08:31:43'),
(44, 21, '2026-04-07 08:39:21', '41.221.189.168', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/29.0 Chrome/136.0.0.0 Mobile Safari/537.36', '2026-04-07 08:39:21', '2026-04-07 08:39:21'),
(45, 40, '2026-04-07 08:43:52', '74.244.87.78', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', '2026-04-07 08:43:52', '2026-04-07 08:43:52'),
(46, 30, '2026-04-07 09:26:45', '217.64.109.106', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_2 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.2 Mobile/15E148 Safari/604.1', '2026-04-07 09:26:45', '2026-04-07 09:26:45'),
(47, 4, '2026-04-07 10:04:36', '41.73.104.100', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10.15; rv:149.0) Gecko/20100101 Firefox/149.0', '2026-04-07 10:04:36', '2026-04-07 10:04:36'),
(48, 31, '2026-04-07 11:57:57', '41.221.189.249', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_6 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.0 Mobile/15E148 Safari/604.1', '2026-04-07 11:57:57', '2026-04-07 11:57:57'),
(49, 5, '2026-04-07 12:15:05', '74.244.87.78', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-07 12:15:05', '2026-04-07 12:15:05'),
(50, 5, '2026-04-07 13:26:10', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-07 13:26:10', '2026-04-07 13:26:10'),
(51, 5, '2026-04-07 13:34:37', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-07 13:34:37', '2026-04-07 13:34:37'),
(52, 5, '2026-04-07 14:09:21', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-07 14:09:21', '2026-04-07 14:09:21'),
(53, 5, '2026-04-07 14:57:18', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-07 14:57:18', '2026-04-07 14:57:18'),
(54, 5, '2026-04-07 15:38:23', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-07 15:38:23', '2026-04-07 15:38:23'),
(55, 5, '2026-04-07 15:22:41', '74.244.87.78', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-07 15:22:41', '2026-04-07 15:22:41'),
(56, 11, '2026-04-07 15:24:14', '74.244.87.78', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-07 15:24:14', '2026-04-07 15:24:14'),
(57, 40, '2026-04-07 15:26:10', '74.244.87.78', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-07 15:26:10', '2026-04-07 15:26:10'),
(58, 13, '2026-04-07 15:26:33', '74.244.87.78', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-07 15:26:33', '2026-04-07 15:26:33'),
(59, 11, '2026-04-07 15:47:41', '74.244.87.78', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-07 15:47:41', '2026-04-07 15:47:41'),
(60, 1, '2026-04-07 16:09:08', '41.73.98.197', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', '2026-04-07 16:09:08', '2026-04-07 16:09:08'),
(61, 36, '2026-04-07 16:13:37', '162.19.155.225', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Mobile Safari/537.36', '2026-04-07 16:13:37', '2026-04-07 16:13:37'),
(62, 13, '2026-04-07 16:37:30', '41.73.104.155', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/29.0 Chrome/136.0.0.0 Mobile Safari/537.36', '2026-04-07 16:37:30', '2026-04-07 16:37:30'),
(63, 31, '2026-04-07 16:58:30', '41.221.181.249', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_6 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.0 Mobile/15E148 Safari/604.1', '2026-04-07 16:58:30', '2026-04-07 16:58:30'),
(64, 29, '2026-04-07 17:00:02', '217.64.100.252', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Mobile/15E148 Safari/604.1', '2026-04-07 17:00:02', '2026-04-07 17:00:02'),
(65, 23, '2026-04-07 17:17:00', '41.73.98.164', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.7.5 Mobile/15E148 Safari/604.1', '2026-04-07 17:17:00', '2026-04-07 17:17:00'),
(66, 30, '2026-04-07 17:22:58', '217.64.109.106', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_2 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.2 Mobile/15E148 Safari/604.1', '2026-04-07 17:22:58', '2026-04-07 17:22:58'),
(67, 5, '2026-04-07 18:20:13', '153.67.68.45', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', '2026-04-07 18:20:13', '2026-04-07 18:20:13'),
(68, 38, '2026-04-07 18:27:44', '74.244.87.187', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Mobile Safari/537.36', '2026-04-07 18:27:44', '2026-04-07 18:27:44'),
(69, 29, '2026-04-07 18:35:03', '41.73.104.40', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Mobile/15E148 Safari/604.1', '2026-04-07 18:35:03', '2026-04-07 18:35:03'),
(70, 36, '2026-04-07 19:29:44', '162.19.155.225', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Mobile Safari/537.36', '2026-04-07 19:29:44', '2026-04-07 19:29:44'),
(71, 25, '2026-04-07 19:49:04', '197.155.176.208', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/29.0 Chrome/136.0.0.0 Mobile Safari/537.36', '2026-04-07 19:49:04', '2026-04-07 19:49:04'),
(72, 40, '2026-04-07 19:54:57', '217.64.103.79', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.7 Mobile/15E148 Safari/604.1', '2026-04-07 19:54:57', '2026-04-07 19:54:57'),
(73, 20, '2026-04-07 21:33:01', '41.221.187.131', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', '2026-04-07 21:33:01', '2026-04-07 21:33:01'),
(74, 31, '2026-04-08 05:02:57', '41.73.110.115', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_6 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.0 Mobile/15E148 Safari/604.1', '2026-04-08 05:02:57', '2026-04-08 05:02:57'),
(75, 11, '2026-04-08 06:16:14', '217.64.103.111', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', '2026-04-08 06:16:14', '2026-04-08 06:16:14'),
(76, 15, '2026-04-08 07:31:46', '217.64.98.157', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/28.0 Chrome/130.0.0.0 Mobile Safari/537.36', '2026-04-08 07:31:46', '2026-04-08 07:31:46'),
(77, 16, '2026-04-08 07:50:08', '41.73.109.250', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/29.0 Chrome/136.0.0.0 Mobile Safari/537.36', '2026-04-08 07:50:08', '2026-04-08 07:50:08'),
(78, 37, '2026-04-08 09:04:54', '217.64.100.228', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Mobile Safari/537.36', '2026-04-08 09:04:54', '2026-04-08 09:04:54'),
(79, 20, '2026-04-08 09:07:19', '41.73.104.216', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', '2026-04-08 09:07:19', '2026-04-08 09:07:19'),
(80, 11, '2026-04-08 09:08:52', '217.64.100.251', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', '2026-04-08 09:08:52', '2026-04-08 09:08:52'),
(81, 4, '2026-04-08 09:23:24', '153.67.68.173', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10.15; rv:149.0) Gecko/20100101 Firefox/149.0', '2026-04-08 09:23:24', '2026-04-08 09:23:24'),
(82, 14, '2026-04-08 09:38:35', '41.73.110.200', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_4_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.4 Mobile/15E148 Safari/604.1', '2026-04-08 09:38:35', '2026-04-08 09:38:35'),
(83, 36, '2026-04-08 10:25:12', '41.221.181.190', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Mobile Safari/537.36', '2026-04-08 10:25:12', '2026-04-08 10:25:12'),
(84, 10, '2026-04-08 11:02:38', '41.73.104.248', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.4 Mobile/15E148 Safari/604.1', '2026-04-08 11:02:38', '2026-04-08 11:02:38'),
(85, 5, '2026-04-08 11:41:32', '153.67.68.173', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-08 11:41:32', '2026-04-08 11:41:32'),
(86, 38, '2026-04-08 12:28:40', '41.73.109.94', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Mobile Safari/537.36', '2026-04-08 12:28:40', '2026-04-08 12:28:40'),
(87, 4, '2026-04-08 12:45:00', '153.67.68.173', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10.15; rv:149.0) Gecko/20100101 Firefox/149.0', '2026-04-08 12:45:00', '2026-04-08 12:45:00'),
(88, 5, '2026-04-08 12:53:59', '153.67.68.173', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-08 12:53:59', '2026-04-08 12:53:59'),
(89, 26, '2026-04-08 13:17:07', '217.64.103.78', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.4 Mobile/15E148 Safari/604.1', '2026-04-08 13:17:07', '2026-04-08 13:17:07'),
(90, 20, '2026-04-08 13:43:04', '41.73.104.216', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', '2026-04-08 13:43:04', '2026-04-08 13:43:04'),
(91, 29, '2026-04-08 14:16:58', '41.221.187.154', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Mobile/15E148 Safari/604.1', '2026-04-08 14:16:58', '2026-04-08 14:16:58'),
(92, 5, '2026-04-08 14:17:20', '153.67.68.173', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-08 14:17:20', '2026-04-08 14:17:20'),
(93, 16, '2026-04-08 14:25:10', '41.73.109.250', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/29.0 Chrome/136.0.0.0 Mobile Safari/537.36', '2026-04-08 14:25:10', '2026-04-08 14:25:10'),
(94, 21, '2026-04-08 14:34:46', '153.67.68.173', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-08 14:34:46', '2026-04-08 14:34:46'),
(95, 5, '2026-04-08 14:43:21', '153.67.68.173', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-08 14:43:21', '2026-04-08 14:43:21'),
(96, 5, '2026-04-08 14:55:15', '153.67.68.173', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-08 14:55:15', '2026-04-08 14:55:15'),
(97, 10, '2026-04-08 15:05:12', '41.73.104.248', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.4 Mobile/15E148 Safari/604.1', '2026-04-08 15:05:12', '2026-04-08 15:05:12'),
(98, 18, '2026-04-08 17:06:36', '102.222.204.39', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/29.0 Chrome/136.0.0.0 Mobile Safari/537.36', '2026-04-08 17:06:36', '2026-04-08 17:06:36'),
(99, 26, '2026-04-08 17:22:04', '217.64.103.76', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.4 Mobile/15E148 Safari/604.1', '2026-04-08 17:22:04', '2026-04-08 17:22:04'),
(100, 40, '2026-04-08 19:30:35', '217.64.100.203', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.7 Mobile/15E148 Safari/604.1', '2026-04-08 19:30:35', '2026-04-08 19:30:35'),
(101, 25, '2026-04-08 19:34:17', '197.155.176.97', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/29.0 Chrome/136.0.0.0 Mobile Safari/537.36', '2026-04-08 19:34:17', '2026-04-08 19:34:17'),
(102, 36, '2026-04-08 22:18:32', '41.221.181.190', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Mobile Safari/537.36', '2026-04-08 22:18:32', '2026-04-08 22:18:32'),
(103, 36, '2026-04-08 22:19:31', '41.221.181.190', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Mobile Safari/537.36', '2026-04-08 22:19:31', '2026-04-08 22:19:31'),
(104, 11, '2026-04-08 22:26:59', '41.73.123.219', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', '2026-04-08 22:26:59', '2026-04-08 22:26:59'),
(105, 11, '2026-04-09 07:17:08', '217.64.103.91', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', '2026-04-09 07:17:08', '2026-04-09 07:17:08'),
(106, 4, '2026-04-09 08:31:01', '153.67.68.173', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10.15; rv:149.0) Gecko/20100101 Firefox/149.0', '2026-04-09 08:31:01', '2026-04-09 08:31:01'),
(107, 31, '2026-04-09 08:33:32', '41.221.181.36', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_6 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.0 Mobile/15E148 Safari/604.1', '2026-04-09 08:33:32', '2026-04-09 08:33:32'),
(108, 30, '2026-04-09 09:31:20', '217.64.109.106', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_2 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.2 Mobile/15E148 Safari/604.1', '2026-04-09 09:31:20', '2026-04-09 09:31:20'),
(109, 14, '2026-04-09 09:40:56', '41.221.189.204', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_4_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.4 Mobile/15E148 Safari/604.1', '2026-04-09 09:40:56', '2026-04-09 09:40:56'),
(110, 1, '2026-04-09 09:57:16', '41.73.98.251', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', '2026-04-09 09:57:16', '2026-04-09 09:57:16'),
(111, 38, '2026-04-09 09:59:12', '41.73.109.91', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Mobile Safari/537.36', '2026-04-09 09:59:12', '2026-04-09 09:59:12'),
(112, 20, '2026-04-09 10:23:05', '41.73.104.216', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', '2026-04-09 10:23:05', '2026-04-09 10:23:05'),
(113, 5, '2026-04-09 10:29:18', '153.67.68.173', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-09 10:29:18', '2026-04-09 10:29:18'),
(114, 5, '2026-04-09 10:34:20', '153.67.68.173', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-09 10:34:20', '2026-04-09 10:34:20'),
(115, 16, '2026-04-09 11:30:51', '41.73.105.73', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/29.0 Chrome/136.0.0.0 Mobile Safari/537.36', '2026-04-09 11:30:51', '2026-04-09 11:30:51'),
(116, 14, '2026-04-09 11:53:09', '41.221.181.211', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_4_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.4 Mobile/15E148 Safari/604.1', '2026-04-09 11:53:09', '2026-04-09 11:53:09'),
(117, 10, '2026-04-09 12:17:53', '41.73.104.248', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.4 Mobile/15E148 Safari/604.1', '2026-04-09 12:17:53', '2026-04-09 12:17:53'),
(118, 1, '2026-04-09 12:38:03', '41.73.98.251', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', '2026-04-09 12:38:03', '2026-04-09 12:38:03'),
(119, 26, '2026-04-09 12:59:53', '217.64.98.154', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.4 Mobile/15E148 Safari/604.1', '2026-04-09 12:59:53', '2026-04-09 12:59:53'),
(120, 4, '2026-04-09 13:39:52', '153.67.68.173', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10.15; rv:149.0) Gecko/20100101 Firefox/149.0', '2026-04-09 13:39:52', '2026-04-09 13:39:52'),
(121, 20, '2026-04-09 14:02:12', '41.73.104.216', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', '2026-04-09 14:02:12', '2026-04-09 14:02:12'),
(122, 15, '2026-04-09 14:06:15', '41.221.181.37', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/28.0 Chrome/130.0.0.0 Mobile Safari/537.36', '2026-04-09 14:06:15', '2026-04-09 14:06:15'),
(123, 29, '2026-04-09 14:27:29', '217.64.103.79', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Mobile/15E148 Safari/604.1', '2026-04-09 14:27:29', '2026-04-09 14:27:29'),
(124, 25, '2026-04-09 16:22:39', '154.118.186.9', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/29.0 Chrome/136.0.0.0 Mobile Safari/537.36', '2026-04-09 16:22:39', '2026-04-09 16:22:39'),
(125, 14, '2026-04-09 17:30:17', '41.73.107.219', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_4_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.4 Mobile/15E148 Safari/604.1', '2026-04-09 17:30:17', '2026-04-09 17:30:17'),
(126, 25, '2026-04-09 19:24:10', '154.118.186.118', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/29.0 Chrome/136.0.0.0 Mobile Safari/537.36', '2026-04-09 19:24:10', '2026-04-09 19:24:10'),
(127, 14, '2026-04-09 20:02:33', '41.73.109.111', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_4_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.4 Mobile/15E148 Safari/604.1', '2026-04-09 20:02:33', '2026-04-09 20:02:33'),
(128, 17, '2026-04-09 23:11:25', '41.73.123.64', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.4 Mobile/15E148 Safari/604.1', '2026-04-09 23:11:25', '2026-04-09 23:11:25'),
(129, 17, '2026-04-09 23:13:22', '41.73.123.64', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.4 Mobile/15E148 Safari/604.1', '2026-04-09 23:13:22', '2026-04-09 23:13:22'),
(130, 14, '2026-04-10 05:54:10', '41.221.189.59', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_4_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.4 Mobile/15E148 Safari/604.1', '2026-04-10 05:54:10', '2026-04-10 05:54:10'),
(131, 18, '2026-04-10 07:24:55', '217.64.102.162', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/29.0 Chrome/136.0.0.0 Mobile Safari/537.36', '2026-04-10 07:24:55', '2026-04-10 07:24:55'),
(132, 18, '2026-04-10 07:26:05', '217.64.102.162', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/29.0 Chrome/136.0.0.0 Mobile Safari/537.36', '2026-04-10 07:26:05', '2026-04-10 07:26:05'),
(133, 18, '2026-04-10 07:26:50', '217.64.102.162', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/29.0 Chrome/136.0.0.0 Mobile Safari/537.36', '2026-04-10 07:26:50', '2026-04-10 07:26:50'),
(134, 18, '2026-04-10 07:26:50', '217.64.102.162', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/29.0 Chrome/136.0.0.0 Mobile Safari/537.36', '2026-04-10 07:26:50', '2026-04-10 07:26:50'),
(135, 38, '2026-04-10 07:28:24', '41.73.107.247', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Mobile Safari/537.36', '2026-04-10 07:28:24', '2026-04-10 07:28:24'),
(136, 10, '2026-04-10 07:31:20', '41.73.98.130', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.4 Mobile/15E148 Safari/604.1', '2026-04-10 07:31:20', '2026-04-10 07:31:20'),
(137, 11, '2026-04-10 07:42:41', '217.64.100.213', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', '2026-04-10 07:42:41', '2026-04-10 07:42:41'),
(138, 16, '2026-04-10 07:49:24', '41.221.189.41', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/29.0 Chrome/136.0.0.0 Mobile Safari/537.36', '2026-04-10 07:49:24', '2026-04-10 07:49:24'),
(139, 30, '2026-04-10 08:01:46', '217.64.109.106', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_2 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.2 Mobile/15E148 Safari/604.1', '2026-04-10 08:01:46', '2026-04-10 08:01:46'),
(140, 26, '2026-04-10 08:19:58', '217.64.103.85', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.4 Mobile/15E148 Safari/604.1', '2026-04-10 08:19:58', '2026-04-10 08:19:58'),
(141, 5, '2026-04-10 08:32:03', '153.67.68.46', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-10 08:32:03', '2026-04-10 08:32:03'),
(142, 5, '2026-04-10 09:41:23', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-10 09:41:23', '2026-04-10 09:41:23'),
(143, 5, '2026-04-10 09:51:06', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-10 09:51:06', '2026-04-10 09:51:06'),
(145, 5, '2026-04-10 09:52:12', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-10 09:52:12', '2026-04-10 09:52:12'),
(146, 43, '2026-04-10 09:02:57', '41.221.181.21', 'Mozilla/5.0 (iPhone; CPU iPhone OS 16_7_15 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.6.2 Mobile/15E148 Safari/604.1', '2026-04-10 09:02:57', '2026-04-10 09:02:57'),
(147, 50, '2026-04-10 09:03:43', '41.221.187.85', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Mobile Safari/537.36', '2026-04-10 09:03:43', '2026-04-10 09:03:43'),
(148, 44, '2026-04-10 09:07:40', '41.221.187.86', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.4 Mobile/15E148 Safari/604.1', '2026-04-10 09:07:40', '2026-04-10 09:07:40'),
(149, 20, '2026-04-10 09:32:11', '41.73.104.216', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', '2026-04-10 09:32:11', '2026-04-10 09:32:11'),
(150, 45, '2026-04-10 09:36:28', '41.73.107.84', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', '2026-04-10 09:36:28', '2026-04-10 09:36:28'),
(151, 47, '2026-04-10 09:42:47', '41.221.187.196', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.4 Mobile/15E148 Safari/604.1', '2026-04-10 09:42:47', '2026-04-10 09:42:47'),
(152, 5, '2026-04-10 09:50:05', '153.67.68.46', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-10 09:50:05', '2026-04-10 09:50:05'),
(153, 38, '2026-04-10 10:14:15', '41.73.107.247', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Mobile Safari/537.36', '2026-04-10 10:14:15', '2026-04-10 10:14:15'),
(154, 41, '2026-04-10 10:26:59', '41.73.109.156', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', '2026-04-10 10:26:59', '2026-04-10 10:26:59'),
(155, 26, '2026-04-10 10:32:41', '217.64.98.143', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.4 Mobile/15E148 Safari/604.1', '2026-04-10 10:32:41', '2026-04-10 10:32:41'),
(156, 46, '2026-04-10 10:41:27', '41.73.109.251', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Mobile Safari/537.36', '2026-04-10 10:41:27', '2026-04-10 10:41:27'),
(157, 41, '2026-04-10 10:46:15', '41.73.105.22', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', '2026-04-10 10:46:15', '2026-04-10 10:46:15'),
(158, 41, '2026-04-10 10:46:15', '41.73.105.22', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', '2026-04-10 10:46:15', '2026-04-10 10:46:15'),
(159, 5, '2026-04-10 10:51:43', '153.67.68.46', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-10 10:51:43', '2026-04-10 10:51:43'),
(160, 5, '2026-04-10 10:57:16', '153.67.68.46', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-10 10:57:16', '2026-04-10 10:57:16'),
(161, 11, '2026-04-10 11:03:02', '153.67.68.46', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', '2026-04-10 11:03:02', '2026-04-10 11:03:02'),
(162, 5, '2026-04-10 11:03:18', '153.67.68.46', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-10 11:03:18', '2026-04-10 11:03:18'),
(163, 11, '2026-04-10 11:03:46', '153.67.68.46', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-10 11:03:46', '2026-04-10 11:03:46'),
(164, 52, '2026-04-10 12:23:02', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-10 12:23:02', '2026-04-10 12:23:02'),
(165, 33, '2026-04-10 12:51:31', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-10 12:51:31', '2026-04-10 12:51:31'),
(166, 5, '2026-04-10 12:55:58', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-10 12:55:58', '2026-04-10 12:55:58'),
(167, 5, '2026-04-10 13:57:34', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-10 13:57:34', '2026-04-10 13:57:34'),
(168, 11, '2026-04-10 13:58:08', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-10 13:58:08', '2026-04-10 13:58:08'),
(169, 26, '2026-04-10 14:02:37', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-10 14:02:37', '2026-04-10 14:02:37'),
(170, 5, '2026-04-10 13:10:01', '153.67.68.46', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', '2026-04-10 13:10:01', '2026-04-10 13:10:01'),
(171, 11, '2026-04-10 13:10:33', '153.67.68.46', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-10 13:10:33', '2026-04-10 13:10:33'),
(172, 5, '2026-04-10 13:16:02', '153.67.68.46', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-10 13:16:02', '2026-04-10 13:16:02'),
(173, 43, '2026-04-10 13:17:19', '41.73.105.224', 'Mozilla/5.0 (iPhone; CPU iPhone OS 16_7_15 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.6.2 Mobile/15E148 Safari/604.1', '2026-04-10 13:17:19', '2026-04-10 13:17:19'),
(174, 11, '2026-04-10 13:21:57', '153.67.68.46', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-10 13:21:57', '2026-04-10 13:21:57'),
(175, 5, '2026-04-10 13:22:21', '153.67.68.46', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-10 13:22:21', '2026-04-10 13:22:21'),
(176, 23, '2026-04-10 13:29:05', '41.73.123.192', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.7.5 Mobile/15E148 Safari/604.1', '2026-04-10 13:29:05', '2026-04-10 13:29:05'),
(177, 5, '2026-04-10 13:32:24', '153.67.68.46', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-10 13:32:24', '2026-04-10 13:32:24'),
(178, 41, '2026-04-10 13:36:34', '153.67.68.46', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-10 13:36:34', '2026-04-10 13:36:34'),
(179, 5, '2026-04-10 13:39:04', '153.67.68.46', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-10 13:39:04', '2026-04-10 13:39:04'),
(180, 5, '2026-04-10 13:48:01', '153.67.68.46', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-10 13:48:01', '2026-04-10 13:48:01'),
(181, 52, '2026-04-10 13:48:59', '153.67.68.46', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', '2026-04-10 13:48:59', '2026-04-10 13:48:59'),
(182, 52, '2026-04-10 13:55:46', '41.221.187.186', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', '2026-04-10 13:55:46', '2026-04-10 13:55:46'),
(183, 5, '2026-04-10 14:27:15', '153.67.68.46', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-10 14:27:15', '2026-04-10 14:27:15'),
(184, 48, '2026-04-10 14:28:52', '153.67.68.46', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', '2026-04-10 14:28:52', '2026-04-10 14:28:52'),
(185, 5, '2026-04-10 14:29:44', '153.67.68.46', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', '2026-04-10 14:29:44', '2026-04-10 14:29:44'),
(186, 48, '2026-04-10 14:30:28', '153.67.68.46', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', '2026-04-10 14:30:28', '2026-04-10 14:30:28'),
(187, 48, '2026-04-10 14:31:52', '41.73.110.63', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Mobile Safari/537.36', '2026-04-10 14:31:52', '2026-04-10 14:31:52'),
(188, 41, '2026-04-10 14:52:15', '41.73.109.15', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', '2026-04-10 14:52:15', '2026-04-10 14:52:15'),
(189, 41, '2026-04-10 15:00:58', '41.73.107.178', 'Mozilla/5.0 (iPhone; CPU iPhone OS 26_3_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/147.0.7727.47 Mobile/15E148 Safari/604.1', '2026-04-10 15:00:58', '2026-04-10 15:00:58'),
(190, 25, '2026-04-10 15:08:17', '154.118.186.131', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/29.0 Chrome/136.0.0.0 Mobile Safari/537.36', '2026-04-10 15:08:17', '2026-04-10 15:08:17'),
(191, 33, '2026-04-10 15:37:07', '41.73.105.217', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.7 Mobile/15E148 Safari/604.1', '2026-04-10 15:37:07', '2026-04-10 15:37:07'),
(192, 11, '2026-04-10 15:49:11', '41.73.98.105', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', '2026-04-10 15:49:11', '2026-04-10 15:49:11'),
(193, 51, '2026-04-10 16:10:03', '217.64.104.30', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/26.0 Chrome/122.0.0.0 Mobile Safari/537.36', '2026-04-10 16:10:03', '2026-04-10 16:10:03'),
(194, 5, '2026-04-10 16:18:31', '41.73.98.236', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', '2026-04-10 16:18:31', '2026-04-10 16:18:31'),
(195, 1, '2026-04-10 16:35:25', '153.67.68.46', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', '2026-04-10 16:35:25', '2026-04-10 16:35:25'),
(196, 43, '2026-04-10 16:38:53', '41.73.107.109', 'Mozilla/5.0 (iPhone; CPU iPhone OS 16_7_15 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.6.2 Mobile/15E148 Safari/604.1', '2026-04-10 16:38:53', '2026-04-10 16:38:53'),
(197, 29, '2026-04-10 17:55:21', '41.221.187.117', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Mobile/15E148 Safari/604.1', '2026-04-10 17:55:21', '2026-04-10 17:55:21'),
(198, 11, '2026-04-10 18:07:29', '41.73.98.105', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', '2026-04-10 18:07:29', '2026-04-10 18:07:29'),
(199, 25, '2026-04-10 18:53:09', '154.118.186.131', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/29.0 Chrome/136.0.0.0 Mobile Safari/537.36', '2026-04-10 18:53:09', '2026-04-10 18:53:09'),
(200, 20, '2026-04-10 19:29:21', '41.73.104.124', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', '2026-04-10 19:29:21', '2026-04-10 19:29:21'),
(201, 13, '2026-04-10 19:29:46', '41.73.104.155', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/29.0 Chrome/136.0.0.0 Mobile Safari/537.36', '2026-04-10 19:29:46', '2026-04-10 19:29:46'),
(202, 38, '2026-04-10 19:42:20', '153.67.68.246', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Mobile Safari/537.36', '2026-04-10 19:42:20', '2026-04-10 19:42:20'),
(203, 43, '2026-04-10 19:54:51', '41.73.107.184', 'Mozilla/5.0 (iPhone; CPU iPhone OS 16_7_15 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.6.2 Mobile/15E148 Safari/604.1', '2026-04-10 19:54:51', '2026-04-10 19:54:51'),
(204, 52, '2026-04-10 20:48:25', '41.73.104.41', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', '2026-04-10 20:48:25', '2026-04-10 20:48:25'),
(205, 25, '2026-04-10 21:01:22', '154.118.186.131', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/29.0 Chrome/136.0.0.0 Mobile Safari/537.36', '2026-04-10 21:01:22', '2026-04-10 21:01:22'),
(206, 5, '2026-04-10 21:43:51', '41.221.187.97', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', '2026-04-10 21:43:51', '2026-04-10 21:43:51'),
(207, 11, '2026-04-10 21:46:23', '41.221.187.22', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', '2026-04-10 21:46:23', '2026-04-10 21:46:23'),
(208, 45, '2026-04-11 07:59:15', '41.73.109.252', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', '2026-04-11 07:59:15', '2026-04-11 07:59:15'),
(209, 14, '2026-04-11 08:21:00', '41.221.181.131', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_4_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.4 Mobile/15E148 Safari/604.1', '2026-04-11 08:21:00', '2026-04-11 08:21:00'),
(210, 50, '2026-04-11 08:22:26', '41.221.187.111', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Mobile Safari/537.36', '2026-04-11 08:22:26', '2026-04-11 08:22:26'),
(211, 52, '2026-04-11 08:49:15', '41.73.104.128', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', '2026-04-11 08:49:15', '2026-04-11 08:49:15'),
(212, 11, '2026-04-11 09:16:55', '41.73.104.53', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', '2026-04-11 09:16:55', '2026-04-11 09:16:55'),
(213, 43, '2026-04-11 09:24:33', '41.73.109.205', 'Mozilla/5.0 (iPhone; CPU iPhone OS 16_7_15 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.6.2 Mobile/15E148 Safari/604.1', '2026-04-11 09:24:33', '2026-04-11 09:24:33'),
(214, 10, '2026-04-11 09:25:32', '41.221.187.1', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.4 Mobile/15E148 Safari/604.1', '2026-04-11 09:25:32', '2026-04-11 09:25:32'),
(215, 26, '2026-04-11 09:32:03', '217.64.103.77', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.4 Mobile/15E148 Safari/604.1', '2026-04-11 09:32:03', '2026-04-11 09:32:03'),
(216, 13, '2026-04-11 09:42:13', '41.73.104.155', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/29.0 Chrome/136.0.0.0 Mobile Safari/537.36', '2026-04-11 09:42:13', '2026-04-11 09:42:13'),
(217, 46, '2026-04-11 09:49:08', '41.73.109.251', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Mobile Safari/537.36', '2026-04-11 09:49:08', '2026-04-11 09:49:08'),
(218, 51, '2026-04-11 09:54:27', '217.64.98.135', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/26.0 Chrome/122.0.0.0 Mobile Safari/537.36', '2026-04-11 09:54:27', '2026-04-11 09:54:27'),
(219, 20, '2026-04-11 10:10:48', '41.73.104.124', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', '2026-04-11 10:10:48', '2026-04-11 10:10:48'),
(220, 52, '2026-04-11 11:20:36', '41.73.104.153', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', '2026-04-11 11:20:36', '2026-04-11 11:20:36'),
(221, 45, '2026-04-11 11:24:29', '41.73.109.252', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', '2026-04-11 11:24:29', '2026-04-11 11:24:29');
INSERT INTO `user_login_logs` (`id`, `user_id`, `logged_in_at`, `ip_address`, `user_agent`, `created_at`, `updated_at`) VALUES
(222, 43, '2026-04-11 11:39:49', '217.64.110.181', 'Mozilla/5.0 (iPhone; CPU iPhone OS 16_7_15 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.6.2 Mobile/15E148 Safari/604.1', '2026-04-11 11:39:49', '2026-04-11 11:39:49'),
(223, 1, '2026-04-11 12:17:30', '41.73.123.136', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', '2026-04-11 12:17:30', '2026-04-11 12:17:30'),
(224, 29, '2026-04-11 12:50:27', '217.64.100.197', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Mobile/15E148 Safari/604.1', '2026-04-11 12:50:27', '2026-04-11 12:50:27'),
(225, 31, '2026-04-11 14:19:17', '41.73.110.208', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_6 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.0 Mobile/15E148 Safari/604.1', '2026-04-11 14:19:17', '2026-04-11 14:19:17'),
(226, 1, '2026-04-11 14:29:35', '41.221.187.186', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', '2026-04-11 14:29:35', '2026-04-11 14:29:35'),
(227, 11, '2026-04-11 16:46:08', '41.73.123.105', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', '2026-04-11 16:46:08', '2026-04-11 16:46:08'),
(228, 45, '2026-04-11 18:02:39', '41.73.98.10', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', '2026-04-11 18:02:39', '2026-04-11 18:02:39'),
(229, 46, '2026-04-11 19:12:38', '217.64.100.231', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Mobile Safari/537.36', '2026-04-11 19:12:38', '2026-04-11 19:12:38'),
(230, 1, '2026-04-11 22:12:56', '41.221.187.186', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', '2026-04-11 22:12:56', '2026-04-11 22:12:56'),
(231, 26, '2026-04-12 07:41:07', '217.64.110.184', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.4 Mobile/15E148 Safari/604.1', '2026-04-12 07:41:07', '2026-04-12 07:41:07'),
(232, 25, '2026-04-12 16:48:27', '154.118.186.120', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/29.0 Chrome/136.0.0.0 Mobile Safari/537.36', '2026-04-12 16:48:27', '2026-04-12 16:48:27'),
(233, 11, '2026-04-12 18:17:43', '41.73.98.113', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', '2026-04-12 18:17:43', '2026-04-12 18:17:43'),
(234, 17, '2026-04-12 21:54:53', '41.73.109.49', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.4 Mobile/15E148 Safari/604.1', '2026-04-12 21:54:53', '2026-04-12 21:54:53'),
(235, 20, '2026-04-13 07:15:49', '41.73.104.124', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', '2026-04-13 07:15:49', '2026-04-13 07:15:49'),
(236, 16, '2026-04-13 07:22:16', '41.73.109.138', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/29.0 Chrome/136.0.0.0 Mobile Safari/537.36', '2026-04-13 07:22:16', '2026-04-13 07:22:16'),
(237, 48, '2026-04-13 07:41:21', '41.221.181.45', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Mobile Safari/537.36', '2026-04-13 07:41:21', '2026-04-13 07:41:21'),
(238, 43, '2026-04-13 07:51:49', '41.221.189.69', 'Mozilla/5.0 (iPhone; CPU iPhone OS 16_7_15 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.6.2 Mobile/15E148 Safari/604.1', '2026-04-13 07:51:49', '2026-04-13 07:51:49'),
(239, 5, '2026-04-13 08:44:52', '153.67.68.46', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-13 08:44:52', '2026-04-13 08:44:52'),
(240, 45, '2026-04-13 08:55:47', '41.73.109.65', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', '2026-04-13 08:55:47', '2026-04-13 08:55:47'),
(241, 4, '2026-04-13 09:05:58', '153.67.68.46', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10.15; rv:149.0) Gecko/20100101 Firefox/149.0', '2026-04-13 09:05:58', '2026-04-13 09:05:58'),
(242, 11, '2026-04-13 09:09:43', '217.64.100.249', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', '2026-04-13 09:09:43', '2026-04-13 09:09:43'),
(243, 3, '2026-04-13 09:15:29', '41.221.189.136', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/29.0 Chrome/136.0.0.0 Mobile Safari/537.36', '2026-04-13 09:15:29', '2026-04-13 09:15:29'),
(244, 15, '2026-04-13 09:16:02', '41.221.189.224', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/28.0 Chrome/130.0.0.0 Mobile Safari/537.36', '2026-04-13 09:16:02', '2026-04-13 09:16:02'),
(245, 46, '2026-04-13 09:21:31', '217.64.103.95', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Mobile Safari/537.36', '2026-04-13 09:21:31', '2026-04-13 09:21:31'),
(246, 3, '2026-04-13 10:03:03', '153.67.68.46', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', '2026-04-13 10:03:03', '2026-04-13 10:03:03'),
(247, 14, '2026-04-13 10:18:09', '41.73.107.127', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_4_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.4 Mobile/15E148 Safari/604.1', '2026-04-13 10:18:09', '2026-04-13 10:18:09'),
(248, 30, '2026-04-13 10:19:57', '217.64.100.242', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_2 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.2 Mobile/15E148 Safari/604.1', '2026-04-13 10:19:57', '2026-04-13 10:19:57'),
(249, 26, '2026-04-13 10:35:22', '217.64.103.68', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.4 Mobile/15E148 Safari/604.1', '2026-04-13 10:35:22', '2026-04-13 10:35:22'),
(250, 5, '2026-04-13 10:57:34', '153.67.68.46', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-13 10:57:34', '2026-04-13 10:57:34'),
(251, 1, '2026-04-13 11:41:13', '153.67.68.46', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.4 Mobile/15E148 Safari/604.1', '2026-04-13 11:41:13', '2026-04-13 11:41:13'),
(252, 20, '2026-04-13 11:53:50', '41.73.104.124', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', '2026-04-13 11:53:50', '2026-04-13 11:53:50'),
(253, 4, '2026-04-13 12:05:28', '153.67.68.46', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10.15; rv:149.0) Gecko/20100101 Firefox/149.0', '2026-04-13 12:05:28', '2026-04-13 12:05:28'),
(254, 10, '2026-04-13 12:13:14', '41.221.187.1', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.4 Mobile/15E148 Safari/604.1', '2026-04-13 12:13:14', '2026-04-13 12:13:14'),
(255, 11, '2026-04-13 13:44:51', '41.73.98.85', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', '2026-04-13 13:44:51', '2026-04-13 13:44:51'),
(256, 20, '2026-04-13 14:00:38', '41.73.104.223', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', '2026-04-13 14:00:38', '2026-04-13 14:00:38'),
(257, 52, '2026-04-13 14:17:43', '41.221.187.31', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', '2026-04-13 14:17:43', '2026-04-13 14:17:43'),
(258, 11, '2026-04-13 14:45:58', '217.64.103.95', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', '2026-04-13 14:45:58', '2026-04-13 14:45:58'),
(259, 4, '2026-04-13 14:55:27', '41.221.187.201', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10.15; rv:149.0) Gecko/20100101 Firefox/149.0', '2026-04-13 14:55:27', '2026-04-13 14:55:27'),
(260, 29, '2026-04-13 15:00:03', '41.73.104.249', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Mobile/15E148 Safari/604.1', '2026-04-13 15:00:03', '2026-04-13 15:00:03'),
(261, 11, '2026-04-13 15:29:23', '41.73.98.85', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', '2026-04-13 15:29:23', '2026-04-13 15:29:23'),
(262, 11, '2026-04-13 15:32:12', '41.73.98.85', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', '2026-04-13 15:32:12', '2026-04-13 15:32:12'),
(263, 29, '2026-04-13 16:27:08', '41.73.104.249', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Mobile/15E148 Safari/604.1', '2026-04-13 16:27:08', '2026-04-13 16:27:08'),
(264, 15, '2026-04-13 17:38:11', '41.221.189.224', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/28.0 Chrome/130.0.0.0 Mobile Safari/537.36', '2026-04-13 17:38:11', '2026-04-13 17:38:11'),
(265, 23, '2026-04-13 17:39:55', '41.73.123.192', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.7.5 Mobile/15E148 Safari/604.1', '2026-04-13 17:39:55', '2026-04-13 17:39:55'),
(266, 52, '2026-04-13 19:18:52', '41.221.187.82', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', '2026-04-13 19:18:52', '2026-04-13 19:18:52'),
(267, 43, '2026-04-13 22:31:15', '217.64.110.181', 'Mozilla/5.0 (iPhone; CPU iPhone OS 16_7_15 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.6.2 Mobile/15E148 Safari/604.1', '2026-04-13 22:31:15', '2026-04-13 22:31:15'),
(268, 31, '2026-04-14 06:20:41', '41.73.109.64', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_6 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.0 Mobile/15E148 Safari/604.1', '2026-04-14 06:20:41', '2026-04-14 06:20:41'),
(269, 18, '2026-04-14 07:28:13', '217.64.102.162', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/29.0 Chrome/136.0.0.0 Mobile Safari/537.36', '2026-04-14 07:28:13', '2026-04-14 07:28:13'),
(270, 43, '2026-04-14 07:39:16', '41.73.110.240', 'Mozilla/5.0 (iPhone; CPU iPhone OS 16_7_15 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.6.2 Mobile/15E148 Safari/604.1', '2026-04-14 07:39:16', '2026-04-14 07:39:16'),
(271, 5, '2026-04-14 09:16:54', '41.73.98.85', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-14 09:16:54', '2026-04-14 09:16:54'),
(272, 5, '2026-04-14 10:25:49', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-14 10:25:49', '2026-04-14 10:25:49'),
(273, 54, '2026-04-14 10:34:40', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-14 10:34:40', '2026-04-14 10:34:40'),
(274, 5, '2026-04-14 10:35:59', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-14 10:35:59', '2026-04-14 10:35:59'),
(275, 54, '2026-04-14 10:00:17', '41.73.98.85', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-14 10:00:17', '2026-04-14 10:00:17'),
(276, 55, '2026-04-14 10:01:14', '41.73.98.85', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-14 10:01:14', '2026-04-14 10:01:14'),
(277, 16, '2026-04-14 10:04:12', '41.221.189.189', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/29.0 Chrome/136.0.0.0 Mobile Safari/537.36', '2026-04-14 10:04:12', '2026-04-14 10:04:12'),
(278, 46, '2026-04-14 10:18:09', '217.64.100.206', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Mobile Safari/537.36', '2026-04-14 10:18:09', '2026-04-14 10:18:09'),
(279, 11, '2026-04-14 10:25:14', '197.155.148.178', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', '2026-04-14 10:25:14', '2026-04-14 10:25:14'),
(280, 1, '2026-04-14 11:03:39', '153.67.68.208', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/128.0.0.0 Safari/537.36', '2026-04-14 11:03:39', '2026-04-14 11:03:39'),
(281, 5, '2026-04-14 11:05:15', '41.73.98.85', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-14 11:05:15', '2026-04-14 11:05:15'),
(282, 54, '2026-04-14 11:07:40', '153.67.68.208', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', '2026-04-14 11:07:40', '2026-04-14 11:07:40'),
(283, 13, '2026-04-14 11:25:38', '41.73.104.155', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/29.0 Chrome/136.0.0.0 Mobile Safari/537.36', '2026-04-14 11:25:38', '2026-04-14 11:25:38'),
(284, 54, '2026-04-14 11:31:13', '41.73.110.136', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Mobile/15E148 Safari/604.1', '2026-04-14 11:31:13', '2026-04-14 11:31:13'),
(285, 26, '2026-04-14 11:31:38', '217.64.103.80', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.4 Mobile/15E148 Safari/604.1', '2026-04-14 11:31:38', '2026-04-14 11:31:38'),
(286, 11, '2026-04-14 11:31:39', '197.155.148.178', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', '2026-04-14 11:31:39', '2026-04-14 11:31:39'),
(287, 52, '2026-04-14 11:36:25', '41.221.187.26', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', '2026-04-14 11:36:25', '2026-04-14 11:36:25'),
(288, 11, '2026-04-14 11:41:41', '41.73.98.85', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-14 11:41:41', '2026-04-14 11:41:41'),
(289, 20, '2026-04-14 11:51:36', '41.73.104.223', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', '2026-04-14 11:51:36', '2026-04-14 11:51:36'),
(290, 43, '2026-04-14 12:16:36', '41.221.181.187', 'Mozilla/5.0 (iPhone; CPU iPhone OS 16_7_15 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.6.2 Mobile/15E148 Safari/604.1', '2026-04-14 12:16:36', '2026-04-14 12:16:36'),
(291, 5, '2026-04-14 12:44:18', '41.73.98.85', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-14 12:44:18', '2026-04-14 12:44:18'),
(292, 21, '2026-04-14 14:02:44', '41.73.109.244', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/29.0 Chrome/136.0.0.0 Mobile Safari/537.36', '2026-04-14 14:02:44', '2026-04-14 14:02:44'),
(293, 15, '2026-04-14 14:03:45', '217.64.100.237', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/28.0 Chrome/130.0.0.0 Mobile Safari/537.36', '2026-04-14 14:03:45', '2026-04-14 14:03:45'),
(294, 11, '2026-04-14 14:12:25', '217.64.100.226', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', '2026-04-14 14:12:25', '2026-04-14 14:12:25'),
(295, 29, '2026-04-14 15:48:55', '41.73.104.249', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Mobile/15E148 Safari/604.1', '2026-04-14 15:48:55', '2026-04-14 15:48:55'),
(296, 31, '2026-04-14 16:48:51', '217.64.98.134', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_6 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.0 Mobile/15E148 Safari/604.1', '2026-04-14 16:48:51', '2026-04-14 16:48:51'),
(297, 11, '2026-04-14 17:16:37', '41.73.123.123', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', '2026-04-14 17:16:37', '2026-04-14 17:16:37'),
(298, 52, '2026-04-14 17:53:58', '41.221.187.26', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', '2026-04-14 17:53:58', '2026-04-14 17:53:58'),
(299, 10, '2026-04-14 18:05:52', '41.73.123.75', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.4 Mobile/15E148 Safari/604.1', '2026-04-14 18:05:52', '2026-04-14 18:05:52'),
(300, 13, '2026-04-14 18:44:23', '41.73.104.155', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/29.0 Chrome/136.0.0.0 Mobile Safari/537.36', '2026-04-14 18:44:23', '2026-04-14 18:44:23'),
(301, 32, '2026-04-14 19:22:05', '41.221.189.56', 'Mozilla/5.0 (Linux; Android 14; SM-A135F Build/UP1A.231005.007; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/146.0.7680.177 Mobile Safari/537.36', '2026-04-14 19:22:05', '2026-04-14 19:22:05'),
(302, 43, '2026-04-14 20:00:51', '217.64.110.181', 'Mozilla/5.0 (iPhone; CPU iPhone OS 16_7_15 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.6.2 Mobile/15E148 Safari/604.1', '2026-04-14 20:00:51', '2026-04-14 20:00:51'),
(303, 51, '2026-04-14 20:21:11', '217.64.103.79', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/26.0 Chrome/122.0.0.0 Mobile Safari/537.36', '2026-04-14 20:21:11', '2026-04-14 20:21:11'),
(304, 31, '2026-04-14 22:01:29', '41.73.107.28', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_6 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.0 Mobile/15E148 Safari/604.1', '2026-04-14 22:01:29', '2026-04-14 22:01:29'),
(305, 25, '2026-04-14 22:31:17', '154.118.186.41', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/29.0 Chrome/136.0.0.0 Mobile Safari/537.36', '2026-04-14 22:31:17', '2026-04-14 22:31:17'),
(306, 54, '2026-04-15 06:51:51', '41.73.110.83', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Mobile/15E148 Safari/604.1', '2026-04-15 06:51:51', '2026-04-15 06:51:51'),
(307, 46, '2026-04-15 07:19:42', '217.64.98.144', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Mobile Safari/537.36', '2026-04-15 07:19:42', '2026-04-15 07:19:42'),
(308, 43, '2026-04-15 07:28:07', '41.73.105.123', 'Mozilla/5.0 (iPhone; CPU iPhone OS 16_7_15 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.6.2 Mobile/15E148 Safari/604.1', '2026-04-15 07:28:07', '2026-04-15 07:28:07'),
(309, 55, '2026-04-15 07:48:22', '41.73.105.225', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/28.0 Chrome/130.0.0.0 Mobile Safari/537.36', '2026-04-15 07:48:22', '2026-04-15 07:48:22'),
(310, 5, '2026-04-15 08:14:11', '153.67.68.158', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', '2026-04-15 08:14:11', '2026-04-15 08:14:11'),
(311, 11, '2026-04-15 08:27:48', '217.64.100.247', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', '2026-04-15 08:27:48', '2026-04-15 08:27:48'),
(312, 52, '2026-04-15 08:36:10', '41.221.187.89', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', '2026-04-15 08:36:10', '2026-04-15 08:36:10'),
(313, 18, '2026-04-15 09:09:58', '41.221.185.138', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/29.0 Chrome/136.0.0.0 Mobile Safari/537.36', '2026-04-15 09:09:58', '2026-04-15 09:09:58'),
(314, 14, '2026-04-15 09:32:18', '41.221.189.224', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_4_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.4 Mobile/15E148 Safari/604.1', '2026-04-15 09:32:18', '2026-04-15 09:32:18'),
(315, 21, '2026-04-15 09:47:27', '41.73.109.244', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/29.0 Chrome/136.0.0.0 Mobile Safari/537.36', '2026-04-15 09:47:27', '2026-04-15 09:47:27'),
(316, 54, '2026-04-15 10:36:24', '41.221.181.251', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Mobile/15E148 Safari/604.1', '2026-04-15 10:36:24', '2026-04-15 10:36:24'),
(317, 11, '2026-04-15 10:40:42', '197.155.148.178', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', '2026-04-15 10:40:42', '2026-04-15 10:40:42'),
(318, 52, '2026-04-15 11:37:58', '41.221.187.156', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', '2026-04-15 11:37:58', '2026-04-15 11:37:58'),
(319, 20, '2026-04-15 11:55:57', '41.73.104.223', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', '2026-04-15 11:55:57', '2026-04-15 11:55:57'),
(320, 55, '2026-04-15 13:05:44', '41.73.105.225', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/28.0 Chrome/130.0.0.0 Mobile Safari/537.36', '2026-04-15 13:05:44', '2026-04-15 13:05:44'),
(321, 26, '2026-04-15 13:06:08', '217.64.98.156', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.4 Mobile/15E148 Safari/604.1', '2026-04-15 13:06:08', '2026-04-15 13:06:08'),
(322, 15, '2026-04-15 13:13:10', '217.64.103.76', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/28.0 Chrome/130.0.0.0 Mobile Safari/537.36', '2026-04-15 13:13:10', '2026-04-15 13:13:10'),
(323, 46, '2026-04-15 13:52:36', '217.64.98.159', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Mobile Safari/537.36', '2026-04-15 13:52:36', '2026-04-15 13:52:36'),
(324, 51, '2026-04-15 14:16:51', '217.64.103.87', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/26.0 Chrome/122.0.0.0 Mobile Safari/537.36', '2026-04-15 14:16:51', '2026-04-15 14:16:51'),
(325, 16, '2026-04-15 14:26:26', '41.73.110.65', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/29.0 Chrome/136.0.0.0 Mobile Safari/537.36', '2026-04-15 14:26:26', '2026-04-15 14:26:26'),
(326, 55, '2026-04-15 15:15:40', '41.73.105.225', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/28.0 Chrome/130.0.0.0 Mobile Safari/537.36', '2026-04-15 15:15:40', '2026-04-15 15:15:40'),
(327, 11, '2026-04-15 17:13:42', '217.64.104.61', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', '2026-04-15 17:13:42', '2026-04-15 17:13:42'),
(328, 46, '2026-04-15 17:54:19', '217.64.100.231', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Mobile Safari/537.36', '2026-04-15 17:54:19', '2026-04-15 17:54:19'),
(329, 26, '2026-04-15 18:53:40', '217.64.98.151', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.4 Mobile/15E148 Safari/604.1', '2026-04-15 18:53:40', '2026-04-15 18:53:40'),
(330, 52, '2026-04-15 19:20:18', '41.221.187.238', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', '2026-04-15 19:20:18', '2026-04-15 19:20:18'),
(331, 43, '2026-04-15 21:01:29', '217.64.110.181', 'Mozilla/5.0 (iPhone; CPU iPhone OS 16_7_15 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.6.2 Mobile/15E148 Safari/604.1', '2026-04-15 21:01:29', '2026-04-15 21:01:29'),
(332, 25, '2026-04-15 22:13:37', '197.155.155.128', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/29.0 Chrome/136.0.0.0 Mobile Safari/537.36', '2026-04-15 22:13:37', '2026-04-15 22:13:37'),
(333, 43, '2026-04-16 07:18:01', '41.73.105.57', 'Mozilla/5.0 (iPhone; CPU iPhone OS 16_7_15 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.6.2 Mobile/15E148 Safari/604.1', '2026-04-16 07:18:01', '2026-04-16 07:18:01'),
(334, 54, '2026-04-16 07:37:04', '41.221.181.61', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Mobile/15E148 Safari/604.1', '2026-04-16 07:37:04', '2026-04-16 07:37:04'),
(335, 20, '2026-04-16 07:48:23', '41.73.104.223', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', '2026-04-16 07:48:23', '2026-04-16 07:48:23'),
(336, 1, '2026-04-16 08:44:51', '41.73.104.66', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.4 Mobile/15E148 Safari/604.1', '2026-04-16 08:44:51', '2026-04-16 08:44:51'),
(337, 51, '2026-04-16 09:10:39', '217.64.98.135', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/26.0 Chrome/122.0.0.0 Mobile Safari/537.36', '2026-04-16 09:10:39', '2026-04-16 09:10:39'),
(338, 55, '2026-04-16 09:17:40', '41.73.105.225', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/28.0 Chrome/130.0.0.0 Mobile Safari/537.36', '2026-04-16 09:17:40', '2026-04-16 09:17:40'),
(339, 3, '2026-04-16 09:22:36', '41.73.98.102', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', '2026-04-16 09:22:36', '2026-04-16 09:22:36'),
(340, 11, '2026-04-16 09:57:59', '197.155.148.178', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', '2026-04-16 09:57:59', '2026-04-16 09:57:59'),
(341, 5, '2026-04-16 10:01:46', '74.244.87.56', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', '2026-04-16 10:01:46', '2026-04-16 10:01:46'),
(342, 14, '2026-04-16 10:56:35', '41.221.189.212', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_4_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.4 Mobile/15E148 Safari/604.1', '2026-04-16 10:56:35', '2026-04-16 10:56:35'),
(343, 54, '2026-04-16 11:15:17', '41.221.181.226', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Mobile/15E148 Safari/604.1', '2026-04-16 11:15:17', '2026-04-16 11:15:17'),
(344, 20, '2026-04-16 11:17:23', '41.73.104.223', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', '2026-04-16 11:17:23', '2026-04-16 11:17:23'),
(345, 16, '2026-04-16 11:56:23', '41.73.110.65', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/29.0 Chrome/136.0.0.0 Mobile Safari/537.36', '2026-04-16 11:56:23', '2026-04-16 11:56:23'),
(346, 52, '2026-04-16 12:27:29', '41.73.104.140', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', '2026-04-16 12:27:29', '2026-04-16 12:27:29'),
(347, 11, '2026-04-16 13:19:47', '217.64.100.251', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', '2026-04-16 13:19:47', '2026-04-16 13:19:47'),
(348, 13, '2026-04-16 13:53:30', '41.73.104.155', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/29.0 Chrome/136.0.0.0 Mobile Safari/537.36', '2026-04-16 13:53:30', '2026-04-16 13:53:30'),
(349, 16, '2026-04-16 14:11:09', '41.73.110.65', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/29.0 Chrome/136.0.0.0 Mobile Safari/537.36', '2026-04-16 14:11:09', '2026-04-16 14:11:09'),
(350, 5, '2026-04-16 14:19:17', '74.244.87.56', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', '2026-04-16 14:19:17', '2026-04-16 14:19:17'),
(351, 1, '2026-04-16 14:20:06', '74.244.87.56', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.4 Mobile/15E148 Safari/604.1', '2026-04-16 14:20:06', '2026-04-16 14:20:06'),
(352, 5, '2026-04-16 14:51:07', '74.244.87.56', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', '2026-04-16 14:51:07', '2026-04-16 14:51:07'),
(353, 54, '2026-04-16 14:56:42', '41.221.189.194', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Mobile/15E148 Safari/604.1', '2026-04-16 14:56:42', '2026-04-16 14:56:42'),
(354, 14, '2026-04-16 15:02:41', '41.73.110.94', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_4_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.4 Mobile/15E148 Safari/604.1', '2026-04-16 15:02:41', '2026-04-16 15:02:41'),
(355, 26, '2026-04-16 15:28:18', '217.64.103.96', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.4 Mobile/15E148 Safari/604.1', '2026-04-16 15:28:18', '2026-04-16 15:28:18'),
(356, 4, '2026-04-16 15:28:51', '74.244.87.56', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10.15; rv:149.0) Gecko/20100101 Firefox/149.0', '2026-04-16 15:28:51', '2026-04-16 15:28:51'),
(357, 5, '2026-04-16 15:34:49', '74.244.87.56', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', '2026-04-16 15:34:49', '2026-04-16 15:34:49'),
(358, 5, '2026-04-16 15:35:55', '74.244.87.56', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', '2026-04-16 15:35:55', '2026-04-16 15:35:55'),
(359, 31, '2026-04-16 15:49:10', '41.73.107.239', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_6 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.0 Mobile/15E148 Safari/604.1', '2026-04-16 15:49:10', '2026-04-16 15:49:10'),
(360, 23, '2026-04-16 16:57:32', '41.73.123.192', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.7.5 Mobile/15E148 Safari/604.1', '2026-04-16 16:57:32', '2026-04-16 16:57:32'),
(361, 25, '2026-04-16 17:00:47', '197.155.187.24', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/29.0 Chrome/136.0.0.0 Mobile Safari/537.36', '2026-04-16 17:00:47', '2026-04-16 17:00:47'),
(362, 31, '2026-04-16 18:37:44', '41.73.105.8', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_6 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.0 Mobile/15E148 Safari/604.1', '2026-04-16 18:37:44', '2026-04-16 18:37:44'),
(363, 25, '2026-04-16 20:13:10', '197.155.187.24', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/29.0 Chrome/136.0.0.0 Mobile Safari/537.36', '2026-04-16 20:13:10', '2026-04-16 20:13:10'),
(364, 10, '2026-04-16 20:14:43', '41.73.123.75', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.4 Mobile/15E148 Safari/604.1', '2026-04-16 20:14:43', '2026-04-16 20:14:43'),
(365, 21, '2026-04-16 21:02:06', '41.73.109.17', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/29.0 Chrome/136.0.0.0 Mobile Safari/537.36', '2026-04-16 21:02:06', '2026-04-16 21:02:06'),
(366, 21, '2026-04-17 07:50:10', '41.73.109.17', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/29.0 Chrome/136.0.0.0 Mobile Safari/537.36', '2026-04-17 07:50:10', '2026-04-17 07:50:10'),
(367, 54, '2026-04-17 08:01:10', '41.221.181.197', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Mobile/15E148 Safari/604.1', '2026-04-17 08:01:10', '2026-04-17 08:01:10'),
(368, 43, '2026-04-17 08:09:38', '41.73.107.19', 'Mozilla/5.0 (iPhone; CPU iPhone OS 16_7_15 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.6.2 Mobile/15E148 Safari/604.1', '2026-04-17 08:09:38', '2026-04-17 08:09:38'),
(369, 16, '2026-04-17 08:59:32', '41.73.110.65', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/29.0 Chrome/136.0.0.0 Mobile Safari/537.36', '2026-04-17 08:59:32', '2026-04-17 08:59:32'),
(370, 11, '2026-04-17 09:22:09', '217.64.100.238', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', '2026-04-17 09:22:09', '2026-04-17 09:22:09'),
(371, 14, '2026-04-17 09:46:27', '41.73.110.105', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_4_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.4 Mobile/15E148 Safari/604.1', '2026-04-17 09:46:27', '2026-04-17 09:46:27'),
(372, 55, '2026-04-17 10:18:49', '41.73.109.149', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/28.0 Chrome/130.0.0.0 Mobile Safari/537.36', '2026-04-17 10:18:49', '2026-04-17 10:18:49'),
(373, 51, '2026-04-17 10:20:55', '217.64.103.83', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/26.0 Chrome/122.0.0.0 Mobile Safari/537.36', '2026-04-17 10:20:55', '2026-04-17 10:20:55'),
(374, 20, '2026-04-17 10:32:17', '41.73.104.223', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', '2026-04-17 10:32:17', '2026-04-17 10:32:17'),
(375, 54, '2026-04-17 10:42:49', '41.221.189.187', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Mobile/15E148 Safari/604.1', '2026-04-17 10:42:49', '2026-04-17 10:42:49'),
(376, 11, '2026-04-17 11:24:58', '217.64.100.205', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', '2026-04-17 11:24:58', '2026-04-17 11:24:58'),
(377, 15, '2026-04-17 11:26:11', '41.73.110.41', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/28.0 Chrome/130.0.0.0 Mobile Safari/537.36', '2026-04-17 11:26:11', '2026-04-17 11:26:11'),
(378, 3, '2026-04-17 11:45:16', '41.73.98.102', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', '2026-04-17 11:45:16', '2026-04-17 11:45:16'),
(379, 18, '2026-04-17 12:08:21', '41.221.185.138', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/29.0 Chrome/136.0.0.0 Mobile Safari/537.36', '2026-04-17 12:08:21', '2026-04-17 12:08:21'),
(380, 52, '2026-04-17 13:45:20', '41.221.187.173', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', '2026-04-17 13:45:20', '2026-04-17 13:45:20'),
(381, 21, '2026-04-17 16:22:45', '41.73.109.17', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/29.0 Chrome/136.0.0.0 Mobile Safari/537.36', '2026-04-17 16:22:45', '2026-04-17 16:22:45'),
(382, 43, '2026-04-17 16:47:07', '217.64.110.181', 'Mozilla/5.0 (iPhone; CPU iPhone OS 16_7_15 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.6.2 Mobile/15E148 Safari/604.1', '2026-04-17 16:47:07', '2026-04-17 16:47:07'),
(383, 10, '2026-04-17 17:12:20', '41.73.98.227', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.4 Mobile/15E148 Safari/604.1', '2026-04-17 17:12:20', '2026-04-17 17:12:20'),
(384, 25, '2026-04-17 19:42:40', '197.155.176.205', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/29.0 Chrome/136.0.0.0 Mobile Safari/537.36', '2026-04-17 19:42:40', '2026-04-17 19:42:40'),
(385, 54, '2026-04-17 20:36:08', '197.155.176.153', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Mobile/15E148 Safari/604.1', '2026-04-17 20:36:08', '2026-04-17 20:36:08'),
(386, 25, '2026-04-17 22:30:55', '197.155.176.205', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/29.0 Chrome/136.0.0.0 Mobile Safari/537.36', '2026-04-17 22:30:55', '2026-04-17 22:30:55'),
(387, 51, '2026-04-18 08:50:06', '217.64.103.101', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/26.0 Chrome/122.0.0.0 Mobile Safari/537.36', '2026-04-18 08:50:06', '2026-04-18 08:50:06'),
(388, 13, '2026-04-18 09:29:27', '41.73.104.155', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/29.0 Chrome/136.0.0.0 Mobile Safari/537.36', '2026-04-18 09:29:27', '2026-04-18 09:29:27'),
(389, 43, '2026-04-18 09:45:33', '41.73.107.56', 'Mozilla/5.0 (iPhone; CPU iPhone OS 16_7_15 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.6.2 Mobile/15E148 Safari/604.1', '2026-04-18 09:45:33', '2026-04-18 09:45:33'),
(390, 1, '2026-04-18 10:21:05', '41.73.104.66', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.4 Mobile/15E148 Safari/604.1', '2026-04-18 10:21:05', '2026-04-18 10:21:05'),
(391, 16, '2026-04-18 10:47:13', '41.73.110.65', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/29.0 Chrome/136.0.0.0 Mobile Safari/537.36', '2026-04-18 10:47:13', '2026-04-18 10:47:13'),
(392, 52, '2026-04-18 12:05:45', '41.73.104.236', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', '2026-04-18 12:05:45', '2026-04-18 12:05:45'),
(393, 38, '2026-04-18 14:02:33', '217.64.98.152', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Mobile Safari/537.36', '2026-04-18 14:02:33', '2026-04-18 14:02:33'),
(394, 23, '2026-04-18 14:08:36', '41.73.123.236', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.7.5 Mobile/15E148 Safari/604.1', '2026-04-18 14:08:36', '2026-04-18 14:08:36'),
(395, 43, '2026-04-18 14:14:21', '217.64.110.181', 'Mozilla/5.0 (iPhone; CPU iPhone OS 16_7_15 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.6.2 Mobile/15E148 Safari/604.1', '2026-04-18 14:14:21', '2026-04-18 14:14:21'),
(396, 25, '2026-04-18 14:21:35', '197.155.176.58', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/29.0 Chrome/136.0.0.0 Mobile Safari/537.36', '2026-04-18 14:21:35', '2026-04-18 14:21:35'),
(397, 11, '2026-04-18 15:23:40', '217.64.100.255', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', '2026-04-18 15:23:40', '2026-04-18 15:23:40'),
(398, 14, '2026-04-18 15:36:19', '41.73.109.219', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_4_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.4 Mobile/15E148 Safari/604.1', '2026-04-18 15:36:19', '2026-04-18 15:36:19'),
(399, 16, '2026-04-18 16:40:45', '41.73.110.65', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/29.0 Chrome/136.0.0.0 Mobile Safari/537.36', '2026-04-18 16:40:45', '2026-04-18 16:40:45'),
(400, 20, '2026-04-18 17:47:26', '41.73.123.235', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', '2026-04-18 17:47:26', '2026-04-18 17:47:26'),
(401, 32, '2026-04-18 19:54:05', '41.73.105.13', 'Mozilla/5.0 (Linux; Android 14; SM-A135F Build/UP1A.231005.007; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/146.0.7680.177 Mobile Safari/537.36', '2026-04-18 19:54:05', '2026-04-18 19:54:05'),
(402, 17, '2026-04-18 21:29:22', '154.118.150.27', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.4 Mobile/15E148 Safari/604.1', '2026-04-18 21:29:22', '2026-04-18 21:29:22'),
(403, 21, '2026-04-19 01:38:18', '41.73.109.17', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/29.0 Chrome/136.0.0.0 Mobile Safari/537.36', '2026-04-19 01:38:18', '2026-04-19 01:38:18'),
(404, 3, '2026-04-19 07:52:19', '154.118.186.163', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.5 Safari/605.1.15', '2026-04-19 07:52:19', '2026-04-19 07:52:19'),
(405, 3, '2026-04-19 09:09:40', '154.118.186.163', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', '2026-04-19 09:09:40', '2026-04-19 09:09:40'),
(406, 11, '2026-04-19 10:53:28', '41.73.123.139', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', '2026-04-19 10:53:28', '2026-04-19 10:53:28'),
(407, 3, '2026-04-19 11:12:07', '154.118.186.163', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/29.0 Chrome/136.0.0.0 Mobile Safari/537.36', '2026-04-19 11:12:07', '2026-04-19 11:12:07'),
(408, 21, '2026-04-19 12:13:31', '41.73.109.17', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/29.0 Chrome/136.0.0.0 Mobile Safari/537.36', '2026-04-19 12:13:31', '2026-04-19 12:13:31'),
(409, 52, '2026-04-19 13:59:36', '41.73.104.149', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', '2026-04-19 13:59:36', '2026-04-19 13:59:36'),
(410, 31, '2026-04-20 04:47:19', '41.73.104.249', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_6 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.0 Mobile/15E148 Safari/604.1', '2026-04-20 04:47:19', '2026-04-20 04:47:19'),
(411, 54, '2026-04-20 07:33:58', '41.221.187.176', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Mobile/15E148 Safari/604.1', '2026-04-20 07:33:58', '2026-04-20 07:33:58'),
(412, 11, '2026-04-20 07:59:57', '197.155.148.178', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', '2026-04-20 07:59:57', '2026-04-20 07:59:57'),
(413, 3, '2026-04-20 08:49:30', '41.73.123.84', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-20 08:49:30', '2026-04-20 08:49:30'),
(414, 16, '2026-04-20 09:12:12', '41.73.105.86', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/29.0 Chrome/136.0.0.0 Mobile Safari/537.36', '2026-04-20 09:12:12', '2026-04-20 09:12:12'),
(415, 3, '2026-04-20 09:53:38', '41.73.123.84', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-20 09:53:38', '2026-04-20 09:53:38'),
(416, 54, '2026-04-20 10:01:20', '41.73.104.127', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Mobile/15E148 Safari/604.1', '2026-04-20 10:01:20', '2026-04-20 10:01:20'),
(417, 5, '2026-04-20 10:03:32', '153.67.68.127', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', '2026-04-20 10:03:32', '2026-04-20 10:03:32'),
(418, 4, '2026-04-20 10:05:47', '153.67.68.127', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10.15; rv:149.0) Gecko/20100101 Firefox/149.0', '2026-04-20 10:05:47', '2026-04-20 10:05:47'),
(419, 14, '2026-04-20 10:06:12', '41.221.187.178', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_4_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.4 Mobile/15E148 Safari/604.1', '2026-04-20 10:06:12', '2026-04-20 10:06:12'),
(420, 3, '2026-04-20 10:18:27', '41.73.123.84', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', '2026-04-20 10:18:27', '2026-04-20 10:18:27'),
(421, 3, '2026-04-20 10:18:42', '41.73.123.84', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', '2026-04-20 10:18:42', '2026-04-20 10:18:42'),
(422, 51, '2026-04-20 10:31:04', '217.64.98.149', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/26.0 Chrome/122.0.0.0 Mobile Safari/537.36', '2026-04-20 10:31:04', '2026-04-20 10:31:04'),
(423, 31, '2026-04-20 11:01:53', '217.64.103.76', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_6 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.0 Mobile/15E148 Safari/604.1', '2026-04-20 11:01:53', '2026-04-20 11:01:53'),
(424, 15, '2026-04-20 13:15:16', '217.64.103.103', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/28.0 Chrome/130.0.0.0 Mobile Safari/537.36', '2026-04-20 13:15:16', '2026-04-20 13:15:16'),
(425, 3, '2026-04-20 13:31:17', '41.73.123.84', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-20 13:31:17', '2026-04-20 13:31:17'),
(426, 16, '2026-04-20 13:42:20', '41.73.105.86', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/29.0 Chrome/136.0.0.0 Mobile Safari/537.36', '2026-04-20 13:42:20', '2026-04-20 13:42:20'),
(427, 11, '2026-04-20 14:04:48', '217.64.100.243', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', '2026-04-20 14:04:48', '2026-04-20 14:04:48'),
(428, 14, '2026-04-20 14:09:23', '41.221.187.178', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_4_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.4 Mobile/15E148 Safari/604.1', '2026-04-20 14:09:23', '2026-04-20 14:09:23'),
(429, 20, '2026-04-20 14:09:38', '41.73.104.223', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', '2026-04-20 14:09:38', '2026-04-20 14:09:38'),
(430, 51, '2026-04-20 14:31:48', '217.64.98.149', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/26.0 Chrome/122.0.0.0 Mobile Safari/537.36', '2026-04-20 14:31:48', '2026-04-20 14:31:48'),
(431, 6, '2026-04-20 15:03:21', '153.67.68.127', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-20 15:03:21', '2026-04-20 15:03:21'),
(432, 5, '2026-04-20 15:06:35', '153.67.68.127', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', '2026-04-20 15:06:35', '2026-04-20 15:06:35'),
(433, 11, '2026-04-20 16:09:02', '217.64.103.101', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', '2026-04-20 16:09:02', '2026-04-20 16:09:02'),
(434, 52, '2026-04-20 16:42:13', '41.73.104.145', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', '2026-04-20 16:42:13', '2026-04-20 16:42:13'),
(435, 51, '2026-04-20 18:17:19', '217.64.98.149', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/26.0 Chrome/122.0.0.0 Mobile Safari/537.36', '2026-04-20 18:17:19', '2026-04-20 18:17:19'),
(436, 11, '2026-04-20 18:40:58', '41.73.123.113', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', '2026-04-20 18:40:58', '2026-04-20 18:40:58'),
(437, 43, '2026-04-20 19:05:36', '41.73.104.238', 'Mozilla/5.0 (iPhone; CPU iPhone OS 16_7_15 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.6.2 Mobile/15E148 Safari/604.1', '2026-04-20 19:05:36', '2026-04-20 19:05:36'),
(438, 18, '2026-04-21 07:11:35', '217.64.102.162', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/29.0 Chrome/136.0.0.0 Mobile Safari/537.36', '2026-04-21 07:11:35', '2026-04-21 07:11:35');
INSERT INTO `user_login_logs` (`id`, `user_id`, `logged_in_at`, `ip_address`, `user_agent`, `created_at`, `updated_at`) VALUES
(439, 18, '2026-04-21 07:12:06', '217.64.102.162', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/29.0 Chrome/136.0.0.0 Mobile Safari/537.36', '2026-04-21 07:12:06', '2026-04-21 07:12:06'),
(440, 18, '2026-04-21 07:12:33', '217.64.102.162', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/29.0 Chrome/136.0.0.0 Mobile Safari/537.36', '2026-04-21 07:12:33', '2026-04-21 07:12:33'),
(441, 11, '2026-04-21 07:25:19', '197.155.148.178', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', '2026-04-21 07:25:19', '2026-04-21 07:25:19'),
(442, 55, '2026-04-21 07:57:17', '41.73.105.148', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/28.0 Chrome/130.0.0.0 Mobile Safari/537.36', '2026-04-21 07:57:17', '2026-04-21 07:57:17'),
(443, 5, '2026-04-21 08:32:59', '153.67.68.127', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', '2026-04-21 08:32:59', '2026-04-21 08:32:59'),
(444, 32, '2026-04-21 08:35:40', '217.64.97.115', 'Mozilla/5.0 (Linux; Android 14; SM-A135F Build/UP1A.231005.007; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/146.0.7680.177 Mobile Safari/537.36', '2026-04-21 08:35:40', '2026-04-21 08:35:40'),
(445, 5, '2026-04-21 09:47:54', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', '2026-04-21 09:47:54', '2026-04-21 09:47:54'),
(446, 5, '2026-06-11 10:53:59', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36 Edg/149.0.0.0', '2026-06-11 10:53:59', '2026-06-11 10:53:59'),
(447, 56, '2026-06-11 10:58:10', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36 Edg/149.0.0.0', '2026-06-11 10:58:10', '2026-06-11 10:58:10'),
(448, 5, '2026-06-11 11:00:35', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36 Edg/149.0.0.0', '2026-06-11 11:00:35', '2026-06-11 11:00:35'),
(449, 56, '2026-06-11 11:22:55', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36 Edg/149.0.0.0', '2026-06-11 11:22:55', '2026-06-11 11:22:55'),
(450, 56, '2026-06-11 15:17:53', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36 Edg/149.0.0.0', '2026-06-11 15:17:53', '2026-06-11 15:17:53'),
(451, 56, '2026-06-12 11:29:31', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36 Edg/149.0.0.0', '2026-06-12 11:29:31', '2026-06-12 11:29:31'),
(452, 56, '2026-06-12 15:41:07', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36 Edg/149.0.0.0', '2026-06-12 15:41:07', '2026-06-12 15:41:07'),
(453, 5, '2026-06-15 16:28:54', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36 Edg/149.0.0.0', '2026-06-15 16:28:54', '2026-06-15 16:28:54'),
(454, 11, '2026-06-15 16:38:27', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36 Edg/149.0.0.0', '2026-06-15 16:38:27', '2026-06-15 16:38:27'),
(455, 5, '2026-06-15 16:49:38', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36 Edg/149.0.0.0', '2026-06-15 16:49:38', '2026-06-15 16:49:38');

-- --------------------------------------------------------

--
-- Structure de la table `ventes`
--

CREATE TABLE `ventes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `type_carte_id` bigint(20) UNSIGNED NOT NULL,
  `client_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `agence_id` bigint(20) UNSIGNED NOT NULL,
  `campagne_id` bigint(20) UNSIGNED DEFAULT NULL,
  `statut_activation` enum('vendue','activée','en_erreur') NOT NULL DEFAULT 'vendue',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `ventes`
--

INSERT INTO `ventes` (`id`, `type_carte_id`, `client_id`, `user_id`, `agence_id`, `campagne_id`, `statut_activation`, `created_at`, `updated_at`) VALUES
(5, 3, 5, 11, 3, 5, 'vendue', '2026-03-31 11:32:49', '2026-03-31 11:32:49'),
(6, 2, 6, 10, 2, 5, 'vendue', '2026-03-31 12:15:54', '2026-03-31 12:15:54'),
(7, 2, 7, 10, 2, 5, 'vendue', '2026-03-31 12:24:41', '2026-03-31 12:24:41'),
(8, 2, 8, 10, 2, 5, 'vendue', '2026-03-31 13:14:26', '2026-03-31 13:14:26'),
(9, 2, 9, 10, 2, 5, 'vendue', '2026-03-31 13:22:47', '2026-03-31 13:22:47'),
(10, 2, 10, 33, 26, 5, 'vendue', '2026-03-31 13:38:29', '2026-03-31 13:38:29'),
(11, 2, 11, 33, 26, 5, 'vendue', '2026-03-31 13:39:58', '2026-03-31 13:39:58'),
(12, 3, 12, 33, 26, 5, 'vendue', '2026-03-31 13:41:21', '2026-03-31 13:41:21'),
(13, 3, 13, 33, 26, 5, 'vendue', '2026-03-31 13:52:00', '2026-03-31 13:52:00'),
(14, 2, 14, 30, 23, 5, 'vendue', '2026-03-31 13:52:39', '2026-03-31 13:52:39'),
(15, 2, 15, 33, 26, 5, 'vendue', '2026-03-31 13:53:27', '2026-03-31 13:53:27'),
(16, 4, 16, 33, 26, 5, 'vendue', '2026-03-31 13:55:11', '2026-03-31 13:55:11'),
(17, 2, 17, 13, 5, 5, 'vendue', '2026-03-31 14:20:28', '2026-03-31 14:20:28'),
(18, 2, 18, 10, 2, 5, 'vendue', '2026-03-31 14:33:41', '2026-03-31 14:33:41'),
(20, 2, 20, 30, 23, 5, 'vendue', '2026-03-31 14:52:24', '2026-03-31 14:52:24'),
(21, 2, 21, 30, 23, 5, 'vendue', '2026-03-31 14:53:43', '2026-03-31 14:53:43'),
(22, 3, 22, 30, 23, 5, 'vendue', '2026-03-31 14:54:38', '2026-03-31 14:54:38'),
(23, 2, 23, 33, 26, 5, 'vendue', '2026-03-31 15:02:30', '2026-03-31 15:02:30'),
(24, 3, 24, 26, 18, 5, 'vendue', '2026-03-31 15:25:13', '2026-03-31 15:25:13'),
(25, 2, 25, 18, 10, 5, 'vendue', '2026-03-31 15:34:33', '2026-03-31 15:34:33'),
(26, 3, 26, 26, 18, 5, 'vendue', '2026-03-31 15:38:41', '2026-03-31 15:38:41'),
(27, 2, 27, 31, 24, 5, 'vendue', '2026-03-31 16:26:10', '2026-03-31 16:26:10'),
(28, 4, 28, 17, 9, 5, 'vendue', '2026-03-31 16:56:04', '2026-03-31 16:56:04'),
(29, 2, 29, 17, 9, 5, 'vendue', '2026-03-31 16:57:29', '2026-03-31 16:57:29'),
(30, 2, 30, 17, 9, 5, 'vendue', '2026-03-31 16:58:24', '2026-03-31 16:58:24'),
(31, 4, 31, 23, 15, 5, 'vendue', '2026-03-31 17:08:22', '2026-03-31 17:08:22'),
(32, 2, 32, 23, 15, 5, 'vendue', '2026-03-31 17:09:49', '2026-03-31 17:09:49'),
(33, 3, 33, 20, 12, 5, 'vendue', '2026-04-01 08:27:46', '2026-04-01 08:27:46'),
(34, 3, 34, 16, 8, 5, 'vendue', '2026-04-01 08:46:21', '2026-04-01 08:46:21'),
(35, 4, 35, 14, 6, 5, 'vendue', '2026-04-01 11:49:17', '2026-04-01 11:49:17'),
(36, 2, 36, 16, 8, 5, 'vendue', '2026-04-01 11:54:36', '2026-04-01 11:54:36'),
(37, 8, 37, 27, 19, 5, 'vendue', '2026-04-01 11:54:56', '2026-04-01 11:54:56'),
(38, 8, 38, 10, 2, 5, 'vendue', '2026-04-01 12:13:33', '2026-04-01 12:13:33'),
(39, 2, 39, 10, 2, 5, 'vendue', '2026-04-01 12:15:39', '2026-04-01 12:15:39'),
(40, 8, 40, 10, 2, 5, 'vendue', '2026-04-01 12:19:21', '2026-04-01 12:19:21'),
(41, 8, 41, 14, 6, 5, 'vendue', '2026-04-01 12:41:29', '2026-04-01 12:41:29'),
(42, 2, 42, 16, 8, 5, 'vendue', '2026-04-01 13:14:44', '2026-04-01 13:14:44'),
(43, 2, 43, 16, 8, 5, 'vendue', '2026-04-01 13:15:46', '2026-04-01 13:15:46'),
(44, 2, 44, 34, 27, 5, 'vendue', '2026-04-01 13:21:13', '2026-04-01 13:21:13'),
(45, 3, 45, 20, 12, 5, 'vendue', '2026-04-01 13:22:52', '2026-04-01 13:22:52'),
(46, 3, 46, 20, 12, 5, 'vendue', '2026-04-01 13:26:08', '2026-04-01 13:26:08'),
(47, 2, 47, 19, 11, 5, 'vendue', '2026-04-01 13:43:37', '2026-04-01 13:43:37'),
(48, 2, 48, 16, 8, 5, 'vendue', '2026-04-01 14:41:06', '2026-04-01 14:41:06'),
(49, 8, 49, 27, 19, 5, 'vendue', '2026-04-01 14:59:52', '2026-04-01 14:59:52'),
(50, 3, 50, 14, 6, 5, 'vendue', '2026-04-01 15:02:05', '2026-04-01 15:02:05'),
(51, 2, 51, 33, 26, 5, 'vendue', '2026-04-01 15:07:08', '2026-04-01 15:07:08'),
(52, 2, 52, 33, 26, 5, 'vendue', '2026-04-01 15:09:52', '2026-04-01 15:09:52'),
(53, 3, 53, 20, 12, 5, 'vendue', '2026-04-01 15:12:29', '2026-04-01 15:12:29'),
(54, 2, 54, 33, 26, 5, 'vendue', '2026-04-01 15:13:15', '2026-04-01 15:13:15'),
(55, 2, 55, 30, 23, 5, 'vendue', '2026-04-01 15:14:03', '2026-04-01 15:14:03'),
(56, 3, 56, 30, 23, 5, 'vendue', '2026-04-01 15:15:41', '2026-04-01 15:15:41'),
(57, 3, 57, 33, 26, 5, 'vendue', '2026-04-01 15:15:56', '2026-04-01 15:15:56'),
(58, 2, 58, 33, 26, 5, 'vendue', '2026-04-01 15:18:39', '2026-04-01 15:18:39'),
(59, 2, 59, 33, 26, 5, 'vendue', '2026-04-01 15:20:25', '2026-04-01 15:20:25'),
(60, 6, 60, 29, 22, 5, 'vendue', '2026-04-01 15:22:44', '2026-04-01 15:22:44'),
(61, 2, 61, 33, 26, 5, 'vendue', '2026-04-01 15:25:59', '2026-04-01 15:25:59'),
(62, 6, 62, 29, 22, 5, 'vendue', '2026-04-01 15:28:21', '2026-04-01 15:28:21'),
(63, 7, 63, 29, 22, 5, 'vendue', '2026-04-01 15:48:57', '2026-04-01 15:48:57'),
(64, 7, 64, 29, 22, 5, 'vendue', '2026-04-01 15:50:43', '2026-04-01 15:50:43'),
(65, 2, 65, 23, 15, 5, 'vendue', '2026-04-01 16:23:35', '2026-04-01 16:23:35'),
(66, 8, 66, 18, 10, 5, 'vendue', '2026-04-01 16:39:40', '2026-04-01 16:39:40'),
(67, 8, 67, 18, 10, 5, 'vendue', '2026-04-01 16:41:05', '2026-04-01 16:41:05'),
(68, 7, 68, 18, 10, 5, 'vendue', '2026-04-01 16:43:12', '2026-04-01 16:43:12'),
(69, 7, 69, 18, 10, 5, 'vendue', '2026-04-01 16:43:57', '2026-04-01 16:43:57'),
(70, 7, 70, 18, 10, 5, 'vendue', '2026-04-01 16:45:21', '2026-04-01 16:45:21'),
(71, 7, 71, 18, 10, 5, 'vendue', '2026-04-01 16:47:04', '2026-04-01 16:47:04'),
(72, 2, 72, 26, 18, 5, 'vendue', '2026-04-01 16:48:39', '2026-04-01 16:48:39'),
(73, 3, 73, 26, 18, 5, 'vendue', '2026-04-01 16:51:19', '2026-04-01 16:51:19'),
(74, 9, 74, 18, 10, 5, 'vendue', '2026-04-01 16:51:32', '2026-04-01 16:51:32'),
(75, 7, 75, 18, 10, 5, 'vendue', '2026-04-01 16:52:56', '2026-04-01 16:52:56'),
(76, 11, 76, 18, 10, 5, 'vendue', '2026-04-01 16:54:59', '2026-04-01 16:54:59'),
(77, 8, 77, 11, 3, 5, 'vendue', '2026-04-01 18:14:14', '2026-04-01 18:14:14'),
(78, 6, 78, 11, 3, 5, 'vendue', '2026-04-02 07:04:19', '2026-04-02 07:04:19'),
(79, 7, 79, 11, 3, 5, 'vendue', '2026-04-02 07:06:43', '2026-04-02 07:06:43'),
(81, 8, 81, 16, 8, 5, 'vendue', '2026-04-02 07:22:39', '2026-04-02 07:22:39'),
(82, 2, 82, 13, 5, 5, 'vendue', '2026-04-02 07:25:50', '2026-04-02 07:25:50'),
(83, 8, 83, 16, 8, 5, 'vendue', '2026-04-02 07:45:09', '2026-04-02 07:45:09'),
(84, 9, 84, 11, 3, 5, 'vendue', '2026-04-02 07:53:06', '2026-04-02 07:53:06'),
(85, 10, 85, 11, 3, 5, 'vendue', '2026-04-02 08:14:35', '2026-04-02 08:14:35'),
(86, 10, 86, 11, 3, 5, 'vendue', '2026-04-02 08:29:12', '2026-04-02 08:29:12'),
(87, 8, 87, 11, 3, 5, 'vendue', '2026-04-02 08:48:48', '2026-04-02 08:48:48'),
(88, 3, 88, 31, 24, 5, 'vendue', '2026-04-02 10:34:08', '2026-04-02 10:34:08'),
(89, 7, 89, 11, 3, 5, 'vendue', '2026-04-02 11:05:26', '2026-04-02 11:05:26'),
(90, 9, 90, 11, 3, 5, 'vendue', '2026-04-02 11:25:49', '2026-04-02 11:25:49'),
(91, 3, 91, 31, 24, 5, 'vendue', '2026-04-02 11:31:38', '2026-04-02 11:31:38'),
(92, 8, 92, 31, 24, 5, 'vendue', '2026-04-02 11:55:37', '2026-04-02 11:55:37'),
(93, 8, 93, 31, 24, 5, 'vendue', '2026-04-02 12:03:10', '2026-04-02 12:03:10'),
(94, 7, 94, 31, 24, 5, 'vendue', '2026-04-02 12:11:17', '2026-04-02 12:11:17'),
(95, 3, 95, 27, 19, 5, 'vendue', '2026-04-02 12:50:15', '2026-04-02 12:50:15'),
(96, 2, 96, 10, 2, 5, 'vendue', '2026-04-02 13:47:20', '2026-04-02 13:47:20'),
(97, 3, 97, 10, 2, 5, 'vendue', '2026-04-02 13:48:52', '2026-04-02 13:48:52'),
(98, 2, 98, 10, 2, 5, 'vendue', '2026-04-02 13:49:54', '2026-04-02 13:49:54'),
(99, 7, 99, 34, 27, 5, 'vendue', '2026-04-02 13:59:57', '2026-04-02 13:59:57'),
(100, 6, 100, 34, 27, 5, 'vendue', '2026-04-02 14:01:40', '2026-04-02 14:01:40'),
(101, 6, 101, 34, 27, 5, 'vendue', '2026-04-02 14:03:13', '2026-04-02 14:03:13'),
(102, 6, 102, 34, 27, 5, 'vendue', '2026-04-02 14:04:15', '2026-04-02 14:04:15'),
(103, 8, 103, 34, 27, 5, 'vendue', '2026-04-02 14:05:41', '2026-04-02 14:05:41'),
(104, 3, 104, 33, 26, 5, 'vendue', '2026-04-02 14:16:14', '2026-04-02 14:16:14'),
(105, 2, 105, 33, 26, 5, 'vendue', '2026-04-02 14:17:44', '2026-04-02 14:17:44'),
(106, 2, 106, 33, 26, 5, 'vendue', '2026-04-02 14:18:31', '2026-04-02 14:18:31'),
(107, 3, 107, 20, 12, 5, 'vendue', '2026-04-02 14:39:18', '2026-04-02 14:39:18'),
(108, 2, 108, 19, 11, 5, 'vendue', '2026-04-02 15:04:52', '2026-04-02 15:04:52'),
(109, 4, 109, 19, 11, 5, 'vendue', '2026-04-02 15:06:19', '2026-04-02 15:06:19'),
(110, 7, 110, 19, 11, 5, 'vendue', '2026-04-02 15:07:16', '2026-04-02 15:07:16'),
(111, 7, 111, 20, 12, 5, 'vendue', '2026-04-02 15:09:37', '2026-04-02 15:09:37'),
(112, 9, 112, 18, 10, 5, 'vendue', '2026-04-02 15:38:28', '2026-04-02 15:38:28'),
(113, 7, 113, 18, 10, 5, 'vendue', '2026-04-02 15:39:22', '2026-04-02 15:39:22'),
(114, 2, 114, 18, 10, 5, 'vendue', '2026-04-02 15:40:28', '2026-04-02 15:40:28'),
(115, 2, 115, 18, 10, 5, 'vendue', '2026-04-02 15:41:34', '2026-04-02 15:41:34'),
(116, 4, 116, 18, 10, 5, 'vendue', '2026-04-02 15:42:50', '2026-04-02 15:42:50'),
(117, 2, 117, 23, 15, 5, 'vendue', '2026-04-02 16:34:30', '2026-04-02 16:34:30'),
(118, 2, 118, 23, 15, 5, 'vendue', '2026-04-02 16:35:28', '2026-04-02 16:35:28'),
(119, 2, 119, 23, 15, 5, 'vendue', '2026-04-02 16:36:29', '2026-04-02 16:36:29'),
(120, 2, 120, 23, 15, 5, 'vendue', '2026-04-02 16:37:34', '2026-04-02 16:37:34'),
(121, 2, 121, 23, 15, 5, 'vendue', '2026-04-02 16:38:28', '2026-04-02 16:38:28'),
(122, 4, 122, 23, 15, 5, 'vendue', '2026-04-02 16:39:43', '2026-04-02 16:39:43'),
(123, 6, 123, 38, 31, 5, 'vendue', '2026-04-02 18:31:49', '2026-04-02 18:31:49'),
(124, 6, 124, 38, 31, 5, 'vendue', '2026-04-02 18:33:33', '2026-04-02 18:33:33'),
(125, 6, 125, 38, 31, 5, 'vendue', '2026-04-02 18:35:23', '2026-04-02 18:35:23'),
(126, 2, 126, 25, 17, 5, 'vendue', '2026-04-02 18:35:50', '2026-04-02 18:35:50'),
(127, 2, 127, 38, 31, 5, 'vendue', '2026-04-02 18:37:05', '2026-04-02 18:37:05'),
(128, 8, 128, 38, 31, 5, 'vendue', '2026-04-02 18:38:40', '2026-04-02 18:38:40'),
(129, 8, 129, 38, 31, 5, 'vendue', '2026-04-02 18:40:09', '2026-04-02 18:40:09'),
(130, 7, 130, 38, 31, 5, 'vendue', '2026-04-02 18:41:38', '2026-04-02 18:41:38'),
(131, 3, 131, 25, 17, 5, 'vendue', '2026-04-02 18:45:43', '2026-04-02 18:45:43'),
(132, 7, 132, 25, 17, 5, 'vendue', '2026-04-02 19:03:00', '2026-04-02 19:03:00'),
(133, 8, 133, 25, 17, 5, 'vendue', '2026-04-02 19:05:39', '2026-04-02 19:05:39'),
(134, 8, 134, 25, 17, 5, 'vendue', '2026-04-02 19:07:29', '2026-04-02 19:07:29'),
(135, 8, 135, 25, 17, 5, 'vendue', '2026-04-02 19:17:05', '2026-04-02 19:17:05'),
(136, 8, 136, 25, 17, 5, 'vendue', '2026-04-02 19:19:03', '2026-04-02 19:19:03'),
(137, 8, 137, 25, 17, 5, 'vendue', '2026-04-02 19:34:22', '2026-04-02 19:34:22'),
(138, 9, 138, 11, 3, 5, 'vendue', '2026-04-03 08:13:49', '2026-04-03 08:13:49'),
(139, 11, 139, 11, 3, 5, 'vendue', '2026-04-03 08:22:44', '2026-04-03 08:22:44'),
(140, 2, 140, 15, 7, 5, 'vendue', '2026-04-03 10:14:49', '2026-04-03 10:14:49'),
(141, 2, 141, 15, 7, 5, 'vendue', '2026-04-03 10:15:55', '2026-04-03 10:15:55'),
(142, 3, 142, 15, 7, 5, 'vendue', '2026-04-03 10:17:04', '2026-04-03 10:17:04'),
(143, 2, 143, 15, 7, 5, 'vendue', '2026-04-03 10:19:11', '2026-04-03 10:19:11'),
(144, 2, 144, 15, 7, 5, 'vendue', '2026-04-03 10:21:03', '2026-04-03 10:21:03'),
(145, 2, 145, 15, 7, 5, 'vendue', '2026-04-03 10:22:36', '2026-04-03 10:22:36'),
(146, 3, 146, 26, 18, 5, 'vendue', '2026-04-03 10:22:46', '2026-04-03 10:22:46'),
(147, 2, 147, 15, 7, 5, 'vendue', '2026-04-03 10:23:48', '2026-04-03 10:23:48'),
(148, 3, 148, 26, 18, 5, 'vendue', '2026-04-03 10:24:17', '2026-04-03 10:24:17'),
(149, 2, 149, 15, 7, 5, 'vendue', '2026-04-03 10:25:12', '2026-04-03 10:25:12'),
(150, 2, 150, 15, 7, 5, 'vendue', '2026-04-03 10:26:25', '2026-04-03 10:26:25'),
(151, 2, 151, 15, 7, 5, 'vendue', '2026-04-03 10:27:49', '2026-04-03 10:27:49'),
(152, 9, 152, 11, 3, 5, 'vendue', '2026-04-03 10:30:00', '2026-04-03 10:30:00'),
(153, 2, 153, 15, 7, 5, 'vendue', '2026-04-03 10:30:15', '2026-04-03 10:30:15'),
(154, 9, 154, 16, 8, 5, 'vendue', '2026-04-03 10:31:19', '2026-04-03 10:31:19'),
(155, 2, 155, 15, 7, 5, 'vendue', '2026-04-03 10:31:42', '2026-04-03 10:31:42'),
(156, 3, 156, 20, 12, 5, 'vendue', '2026-04-03 10:47:07', '2026-04-03 10:47:07'),
(157, 4, 157, 27, 19, 5, 'vendue', '2026-04-03 10:58:19', '2026-04-03 10:58:19'),
(158, 3, 158, 14, 6, 5, 'vendue', '2026-04-03 11:00:29', '2026-04-03 11:00:29'),
(159, 2, 159, 10, 2, 5, 'vendue', '2026-04-03 11:17:49', '2026-04-03 11:17:49'),
(160, 2, 160, 10, 2, 5, 'vendue', '2026-04-03 11:21:36', '2026-04-03 11:21:36'),
(161, 2, 161, 33, 26, 5, 'vendue', '2026-04-03 11:50:21', '2026-04-03 11:50:21'),
(162, 2, 162, 33, 26, 5, 'vendue', '2026-04-03 11:52:33', '2026-04-03 11:52:33'),
(163, 2, 163, 33, 26, 5, 'vendue', '2026-04-03 12:11:57', '2026-04-03 12:11:57'),
(164, 2, 164, 30, 23, 5, 'vendue', '2026-04-03 12:54:25', '2026-04-03 12:54:25'),
(165, 2, 165, 30, 23, 5, 'vendue', '2026-04-03 12:55:36', '2026-04-03 12:55:36'),
(166, 7, 166, 29, 22, 5, 'vendue', '2026-04-03 14:27:22', '2026-04-03 14:27:22'),
(167, 2, 167, 23, 15, 5, 'vendue', '2026-04-03 14:35:47', '2026-04-03 14:35:47'),
(168, 3, 168, 29, 22, 5, 'vendue', '2026-04-03 14:41:06', '2026-04-03 14:41:06'),
(169, 3, 169, 23, 15, 5, 'vendue', '2026-04-03 14:44:31', '2026-04-03 14:44:31'),
(170, 2, 170, 19, 11, 5, 'vendue', '2026-04-03 19:38:05', '2026-04-03 19:38:05'),
(171, 4, 171, 26, 18, 5, 'vendue', '2026-04-04 07:17:40', '2026-04-04 07:17:40'),
(172, 3, 172, 26, 18, 5, 'vendue', '2026-04-04 07:19:27', '2026-04-04 07:19:27'),
(173, 3, 173, 20, 12, 5, 'vendue', '2026-04-04 10:33:30', '2026-04-04 10:33:30'),
(174, 10, 174, 20, 12, 5, 'vendue', '2026-04-04 10:34:59', '2026-04-04 10:34:59'),
(175, 2, 175, 10, 2, 5, 'vendue', '2026-04-04 11:06:44', '2026-04-04 11:06:44'),
(176, 9, 176, 38, 31, 5, 'vendue', '2026-04-04 11:07:10', '2026-04-04 11:07:10'),
(177, 7, 177, 10, 2, 5, 'vendue', '2026-04-04 11:07:57', '2026-04-04 11:07:57'),
(178, 7, 178, 38, 31, 5, 'vendue', '2026-04-04 11:09:39', '2026-04-04 11:09:39'),
(179, 4, 179, 38, 31, 5, 'vendue', '2026-04-04 11:18:04', '2026-04-04 11:18:04'),
(180, 3, 180, 38, 31, 5, 'vendue', '2026-04-04 11:20:02', '2026-04-04 11:20:02'),
(181, 7, 181, 38, 31, 5, 'vendue', '2026-04-04 11:35:44', '2026-04-04 11:35:44'),
(182, 4, 182, 27, 19, 5, 'vendue', '2026-04-04 11:40:06', '2026-04-04 11:40:06'),
(183, 7, 183, 19, 11, 5, 'vendue', '2026-04-04 11:44:12', '2026-04-04 11:44:12'),
(184, 2, 184, 17, 9, 5, 'vendue', '2026-04-04 12:51:56', '2026-04-04 12:51:56'),
(185, 2, 185, 17, 9, 5, 'vendue', '2026-04-04 12:52:27', '2026-04-04 12:52:27'),
(186, 8, 186, 25, 17, 5, 'vendue', '2026-04-04 13:43:36', '2026-04-04 13:43:36'),
(187, 3, 187, 25, 17, 5, 'vendue', '2026-04-04 13:45:32', '2026-04-04 13:45:32'),
(188, 3, 188, 25, 17, 5, 'vendue', '2026-04-04 13:47:05', '2026-04-04 13:47:05'),
(189, 3, 189, 29, 22, 5, 'vendue', '2026-04-04 13:57:48', '2026-04-04 13:57:48'),
(190, 3, 190, 23, 15, 5, 'vendue', '2026-04-04 14:08:48', '2026-04-04 14:08:48'),
(191, 3, 191, 23, 15, 5, 'vendue', '2026-04-04 14:09:28', '2026-04-04 14:09:28'),
(192, 3, 192, 23, 15, 5, 'vendue', '2026-04-04 14:11:02', '2026-04-04 14:11:02'),
(193, 3, 193, 23, 15, 5, 'vendue', '2026-04-04 14:12:08', '2026-04-04 14:12:08'),
(194, 3, 194, 29, 22, 5, 'vendue', '2026-04-04 14:35:27', '2026-04-04 14:35:27'),
(195, 7, 195, 38, 31, 5, 'vendue', '2026-04-04 14:41:05', '2026-04-04 14:41:05'),
(197, 7, 197, 31, 24, 5, 'vendue', '2026-04-04 21:11:24', '2026-04-04 21:11:24'),
(198, 7, 198, 31, 24, 5, 'vendue', '2026-04-04 21:22:25', '2026-04-04 21:22:25'),
(199, 7, 199, 31, 24, 5, 'vendue', '2026-04-04 21:35:00', '2026-04-04 21:35:00'),
(200, 7, 200, 31, 24, 5, 'vendue', '2026-04-04 21:43:26', '2026-04-04 21:43:26'),
(201, 8, 201, 11, 3, 5, 'vendue', '2026-04-07 08:02:13', '2026-04-07 08:02:13'),
(202, 9, 202, 11, 3, 5, 'vendue', '2026-04-07 08:50:51', '2026-04-07 08:50:51'),
(203, 9, 203, 11, 3, 5, 'vendue', '2026-04-07 08:59:45', '2026-04-07 08:59:45'),
(204, 2, 204, 30, 23, 5, 'vendue', '2026-04-07 09:28:06', '2026-04-07 09:28:06'),
(205, 6, 205, 11, 3, 5, 'vendue', '2026-04-07 09:32:18', '2026-04-07 09:32:18'),
(206, 8, 206, 35, 28, 5, 'vendue', '2026-04-07 09:39:10', '2026-04-07 09:39:10'),
(207, 8, 207, 35, 28, 5, 'vendue', '2026-04-07 09:41:04', '2026-04-07 09:41:04'),
(208, 6, 208, 35, 28, 5, 'vendue', '2026-04-07 09:42:46', '2026-04-07 09:42:46'),
(209, 2, 209, 11, 3, 5, 'vendue', '2026-04-07 10:45:55', '2026-04-07 10:45:55'),
(210, 6, 210, 35, 28, 5, 'vendue', '2026-04-07 10:51:20', '2026-04-07 10:51:20'),
(211, 6, 211, 35, 28, 5, 'vendue', '2026-04-07 11:10:21', '2026-04-07 11:10:21'),
(212, 2, 212, 31, 24, 5, 'vendue', '2026-04-07 12:00:09', '2026-04-07 12:00:09'),
(213, 3, 213, 31, 24, 5, 'vendue', '2026-04-07 12:04:43', '2026-04-07 12:04:43'),
(214, 4, 214, 13, 5, 5, 'vendue', '2026-04-07 16:38:42', '2026-04-07 16:38:42'),
(216, 3, 216, 23, 15, 5, 'vendue', '2026-04-07 17:17:55', '2026-04-07 17:17:55'),
(217, 2, 217, 30, 23, 5, 'vendue', '2026-04-07 17:24:27', '2026-04-07 17:24:27'),
(218, 6, 218, 29, 22, 5, 'vendue', '2026-04-07 17:52:40', '2026-04-07 17:52:40'),
(219, 6, 219, 29, 22, 5, 'vendue', '2026-04-07 18:04:16', '2026-04-07 18:04:16'),
(220, 2, 220, 31, 24, 5, 'vendue', '2026-04-07 18:17:59', '2026-04-07 18:17:59'),
(221, 6, 221, 29, 22, 5, 'vendue', '2026-04-07 18:19:03', '2026-04-07 18:19:03'),
(222, 6, 222, 29, 22, 5, 'vendue', '2026-04-07 18:20:11', '2026-04-07 18:20:11'),
(223, 6, 223, 29, 22, 5, 'vendue', '2026-04-07 18:21:13', '2026-04-07 18:21:13'),
(224, 6, 224, 29, 22, 5, 'vendue', '2026-04-07 18:23:34', '2026-04-07 18:23:34'),
(225, 6, 225, 29, 22, 5, 'vendue', '2026-04-07 18:25:31', '2026-04-07 18:25:31'),
(226, 8, 226, 38, 31, 5, 'vendue', '2026-04-07 18:29:18', '2026-04-07 18:29:18'),
(227, 8, 227, 38, 31, 5, 'vendue', '2026-04-07 18:31:01', '2026-04-07 18:31:01'),
(228, 3, 228, 38, 31, 5, 'vendue', '2026-04-07 18:33:26', '2026-04-07 18:33:26'),
(229, 6, 229, 38, 31, 5, 'vendue', '2026-04-07 18:35:51', '2026-04-07 18:35:51'),
(230, 7, 230, 25, 17, 5, 'vendue', '2026-04-07 19:57:50', '2026-04-07 19:57:50'),
(231, 3, 231, 25, 17, 5, 'vendue', '2026-04-07 19:59:16', '2026-04-07 19:59:16'),
(232, 7, 232, 34, 27, 5, 'vendue', '2026-04-07 20:27:44', '2026-04-07 20:27:44'),
(233, 8, 233, 34, 27, 5, 'vendue', '2026-04-07 20:28:53', '2026-04-07 20:28:53'),
(234, 9, 234, 20, 12, 5, 'vendue', '2026-04-07 21:39:31', '2026-04-07 21:39:31'),
(235, 8, 235, 20, 12, 5, 'vendue', '2026-04-07 21:41:56', '2026-04-07 21:41:56'),
(236, 7, 236, 20, 12, 5, 'vendue', '2026-04-07 21:44:44', '2026-04-07 21:44:44'),
(237, 8, 237, 20, 12, 5, 'vendue', '2026-04-07 21:47:23', '2026-04-07 21:47:23'),
(238, 8, 238, 20, 12, 5, 'vendue', '2026-04-07 21:48:55', '2026-04-07 21:48:55'),
(239, 9, 239, 20, 12, 5, 'vendue', '2026-04-07 21:50:45', '2026-04-07 21:50:45'),
(240, 8, 240, 20, 12, 5, 'vendue', '2026-04-07 21:53:23', '2026-04-07 21:53:23'),
(241, 10, 241, 20, 12, 5, 'vendue', '2026-04-07 21:55:22', '2026-04-07 21:55:22'),
(242, 9, 242, 20, 12, 5, 'vendue', '2026-04-07 21:57:38', '2026-04-07 21:57:38'),
(243, 4, 243, 11, 3, 5, 'vendue', '2026-04-08 06:17:51', '2026-04-08 06:17:51'),
(244, 2, 244, 11, 3, 5, 'vendue', '2026-04-08 06:19:24', '2026-04-08 06:19:24'),
(245, 2, 245, 33, 26, 5, 'vendue', '2026-04-08 07:13:42', '2026-04-08 07:13:42'),
(246, 8, 246, 33, 26, 5, 'vendue', '2026-04-08 07:16:42', '2026-04-08 07:16:42'),
(247, 2, 247, 33, 26, 5, 'vendue', '2026-04-08 07:18:09', '2026-04-08 07:18:09'),
(248, 6, 248, 33, 26, 5, 'vendue', '2026-04-08 07:19:33', '2026-04-08 07:19:33'),
(249, 7, 249, 33, 26, 5, 'vendue', '2026-04-08 07:20:54', '2026-04-08 07:20:54'),
(250, 6, 250, 33, 26, 5, 'vendue', '2026-04-08 07:21:51', '2026-04-08 07:21:51'),
(251, 2, 251, 15, 7, 5, 'vendue', '2026-04-08 07:33:30', '2026-04-08 07:33:30'),
(252, 2, 252, 15, 7, 5, 'vendue', '2026-04-08 07:35:18', '2026-04-08 07:35:18'),
(253, 2, 253, 15, 7, 5, 'vendue', '2026-04-08 07:36:31', '2026-04-08 07:36:31'),
(254, 4, 254, 15, 7, 5, 'vendue', '2026-04-08 07:38:45', '2026-04-08 07:38:45'),
(255, 4, 255, 15, 7, 5, 'vendue', '2026-04-08 07:39:42', '2026-04-08 07:39:42'),
(256, 7, 256, 16, 8, 5, 'vendue', '2026-04-08 07:51:09', '2026-04-08 07:51:09'),
(257, 8, 257, 20, 12, 5, 'vendue', '2026-04-08 09:09:15', '2026-04-08 09:09:15'),
(258, 4, 258, 16, 8, 5, 'vendue', '2026-04-08 09:34:42', '2026-04-08 09:34:42'),
(259, 3, 259, 16, 8, 5, 'vendue', '2026-04-08 09:35:25', '2026-04-08 09:35:25'),
(260, 4, 260, 14, 6, 5, 'vendue', '2026-04-08 09:39:18', '2026-04-08 09:39:18'),
(261, 2, 261, 11, 3, 5, 'vendue', '2026-04-08 10:00:44', '2026-04-08 10:00:44'),
(262, 9, 262, 20, 12, 5, 'vendue', '2026-04-08 10:24:34', '2026-04-08 10:24:34'),
(263, 2, 263, 36, 29, 5, 'vendue', '2026-04-08 10:42:55', '2026-04-08 10:42:55'),
(264, 2, 264, 36, 29, 5, 'vendue', '2026-04-08 10:44:15', '2026-04-08 10:44:15'),
(265, 2, 265, 36, 29, 5, 'vendue', '2026-04-08 10:44:59', '2026-04-08 10:44:59'),
(266, 3, 266, 36, 29, 5, 'vendue', '2026-04-08 10:45:53', '2026-04-08 10:45:53'),
(267, 2, 267, 10, 2, 5, 'vendue', '2026-04-08 11:04:25', '2026-04-08 11:04:25'),
(268, 2, 268, 10, 2, 5, 'vendue', '2026-04-08 11:05:43', '2026-04-08 11:05:43'),
(269, 7, 269, 10, 2, 5, 'vendue', '2026-04-08 11:07:38', '2026-04-08 11:07:38'),
(270, 7, 270, 36, 29, 5, 'vendue', '2026-04-08 11:19:03', '2026-04-08 11:19:03'),
(271, 4, 271, 27, 19, 5, 'vendue', '2026-04-08 11:21:04', '2026-04-08 11:21:04'),
(272, 7, 272, 36, 29, 5, 'vendue', '2026-04-08 11:21:22', '2026-04-08 11:21:22'),
(273, 9, 273, 36, 29, 5, 'vendue', '2026-04-08 11:22:50', '2026-04-08 11:22:50'),
(274, 9, 274, 36, 29, 5, 'vendue', '2026-04-08 11:23:40', '2026-04-08 11:23:40'),
(275, 7, 275, 20, 12, 5, 'vendue', '2026-04-08 11:33:22', '2026-04-08 11:33:22'),
(276, 6, 276, 38, 31, 5, 'vendue', '2026-04-08 12:31:55', '2026-04-08 12:31:55'),
(277, 3, 277, 27, 19, 5, 'vendue', '2026-04-08 12:49:28', '2026-04-08 12:49:28'),
(278, 2, 278, 26, 18, 5, 'vendue', '2026-04-08 13:20:13', '2026-04-08 13:20:13'),
(279, 2, 279, 26, 18, 5, 'vendue', '2026-04-08 13:21:46', '2026-04-08 13:21:46'),
(280, 4, 280, 26, 18, 5, 'vendue', '2026-04-08 13:23:15', '2026-04-08 13:23:15'),
(281, 2, 281, 26, 18, 5, 'vendue', '2026-04-08 13:24:29', '2026-04-08 13:24:29'),
(282, 2, 282, 11, 3, 5, 'vendue', '2026-04-08 13:28:27', '2026-04-08 13:28:27'),
(283, 2, 283, 11, 3, 5, 'vendue', '2026-04-08 14:21:17', '2026-04-08 14:21:17'),
(284, 3, 284, 37, 30, 5, 'vendue', '2026-04-08 14:21:40', '2026-04-08 14:21:40'),
(285, 3, 285, 16, 8, 5, 'vendue', '2026-04-08 14:26:06', '2026-04-08 14:26:06'),
(286, 7, 286, 29, 22, 5, 'vendue', '2026-04-08 14:34:00', '2026-04-08 14:34:00'),
(287, 4, 287, 26, 18, 5, 'vendue', '2026-04-08 14:42:15', '2026-04-08 14:42:15'),
(288, 6, 288, 10, 2, 5, 'vendue', '2026-04-08 15:07:01', '2026-04-08 15:07:01'),
(289, 6, 289, 33, 26, 5, 'vendue', '2026-04-08 15:19:11', '2026-04-08 15:19:11'),
(290, 7, 290, 33, 26, 5, 'vendue', '2026-04-08 15:20:11', '2026-04-08 15:20:11'),
(291, 6, 291, 33, 26, 5, 'vendue', '2026-04-08 15:21:06', '2026-04-08 15:21:06'),
(292, 6, 292, 33, 26, 5, 'vendue', '2026-04-08 15:22:02', '2026-04-08 15:22:02'),
(293, 8, 293, 19, 11, 5, 'vendue', '2026-04-08 15:40:00', '2026-04-08 15:40:00'),
(294, 6, 294, 19, 11, 5, 'vendue', '2026-04-08 15:41:11', '2026-04-08 15:41:11'),
(295, 7, 295, 34, 27, 5, 'vendue', '2026-04-08 15:56:14', '2026-04-08 15:56:14'),
(296, 3, 296, 18, 10, 5, 'vendue', '2026-04-08 17:08:12', '2026-04-08 17:08:12'),
(297, 2, 297, 18, 10, 5, 'vendue', '2026-04-08 17:08:54', '2026-04-08 17:08:54'),
(298, 8, 298, 18, 10, 5, 'vendue', '2026-04-08 17:09:44', '2026-04-08 17:09:44'),
(299, 2, 299, 18, 10, 5, 'vendue', '2026-04-08 17:10:48', '2026-04-08 17:10:48'),
(300, 2, 300, 18, 10, 5, 'vendue', '2026-04-08 17:11:36', '2026-04-08 17:11:36'),
(301, 4, 301, 18, 10, 5, 'vendue', '2026-04-08 17:12:23', '2026-04-08 17:12:23'),
(302, 7, 302, 25, 17, 5, 'vendue', '2026-04-08 21:25:10', '2026-04-08 21:25:10'),
(303, 8, 303, 25, 17, 5, 'vendue', '2026-04-08 21:27:09', '2026-04-08 21:27:09'),
(304, 3, 304, 25, 17, 5, 'vendue', '2026-04-08 21:28:47', '2026-04-08 21:28:47'),
(305, 6, 305, 11, 3, 5, 'vendue', '2026-04-09 08:50:00', '2026-04-09 08:50:00'),
(306, 7, 306, 11, 3, 5, 'vendue', '2026-04-09 08:51:15', '2026-04-09 08:51:15'),
(307, 6, 307, 11, 3, 5, 'vendue', '2026-04-09 09:00:32', '2026-04-09 09:00:32'),
(308, 2, 308, 14, 6, 5, 'vendue', '2026-04-09 09:41:46', '2026-04-09 09:41:46'),
(309, 11, 309, 11, 3, 5, 'vendue', '2026-04-09 10:10:48', '2026-04-09 10:10:48'),
(310, 7, 310, 11, 3, 5, 'vendue', '2026-04-09 10:12:09', '2026-04-09 10:12:09'),
(311, 2, 311, 20, 12, 5, 'vendue', '2026-04-09 10:24:48', '2026-04-09 10:24:48'),
(312, 8, 312, 16, 8, 5, 'vendue', '2026-04-09 11:34:16', '2026-04-09 11:34:16'),
(313, 4, 313, 14, 6, 5, 'vendue', '2026-04-09 11:56:22', '2026-04-09 11:56:22'),
(315, 2, 315, 14, 6, 5, 'vendue', '2026-04-09 12:51:16', '2026-04-09 12:51:16'),
(316, 4, 316, 26, 18, 5, 'vendue', '2026-04-09 13:00:07', '2026-04-09 13:00:07'),
(317, 2, 317, 33, 26, 5, 'vendue', '2026-04-09 13:18:50', '2026-04-09 13:18:50'),
(318, 6, 318, 33, 26, 5, 'vendue', '2026-04-09 13:19:49', '2026-04-09 13:19:49'),
(319, 2, 319, 33, 26, 5, 'vendue', '2026-04-09 13:20:53', '2026-04-09 13:20:53'),
(320, 2, 320, 33, 26, 5, 'vendue', '2026-04-09 13:25:55', '2026-04-09 13:25:55'),
(321, 2, 321, 33, 26, 5, 'vendue', '2026-04-09 13:28:02', '2026-04-09 13:28:02'),
(322, 7, 322, 35, 28, 5, 'vendue', '2026-04-09 13:28:37', '2026-04-09 13:28:37'),
(323, 2, 323, 33, 26, 5, 'vendue', '2026-04-09 13:29:44', '2026-04-09 13:29:44'),
(324, 2, 324, 33, 26, 5, 'vendue', '2026-04-09 13:31:29', '2026-04-09 13:31:29'),
(325, 11, 325, 10, 2, 5, 'vendue', '2026-04-09 13:36:26', '2026-04-09 13:36:26'),
(326, 8, 326, 20, 12, 5, 'vendue', '2026-04-09 14:03:33', '2026-04-09 14:03:33'),
(327, 2, 327, 15, 7, 5, 'vendue', '2026-04-09 14:07:10', '2026-04-09 14:07:10'),
(328, 2, 328, 15, 7, 5, 'vendue', '2026-04-09 14:08:53', '2026-04-09 14:08:53'),
(329, 2, 329, 15, 7, 5, 'vendue', '2026-04-09 14:10:30', '2026-04-09 14:10:30'),
(330, 2, 330, 15, 7, 5, 'vendue', '2026-04-09 14:12:28', '2026-04-09 14:12:28'),
(331, 6, 331, 29, 22, 5, 'vendue', '2026-04-09 14:29:59', '2026-04-09 14:29:59'),
(332, 6, 332, 14, 6, 5, 'vendue', '2026-04-09 14:44:41', '2026-04-09 14:44:41'),
(333, 8, 333, 14, 6, 5, 'vendue', '2026-04-09 14:45:38', '2026-04-09 14:45:38'),
(334, 8, 334, 14, 6, 5, 'vendue', '2026-04-09 14:48:02', '2026-04-09 14:48:02'),
(335, 8, 335, 14, 6, 5, 'vendue', '2026-04-09 14:49:29', '2026-04-09 14:49:29'),
(336, 9, 336, 14, 6, 5, 'vendue', '2026-04-09 14:52:28', '2026-04-09 14:52:28'),
(337, 8, 337, 14, 6, 5, 'vendue', '2026-04-09 14:53:19', '2026-04-09 14:53:19'),
(338, 7, 338, 14, 6, 5, 'vendue', '2026-04-09 14:55:35', '2026-04-09 14:55:35'),
(339, 8, 339, 14, 6, 5, 'vendue', '2026-04-09 14:56:36', '2026-04-09 14:56:36'),
(340, 8, 340, 14, 6, 5, 'vendue', '2026-04-09 14:57:28', '2026-04-09 14:57:28'),
(341, 7, 341, 14, 6, 5, 'vendue', '2026-04-09 14:58:44', '2026-04-09 14:58:44'),
(342, 7, 342, 14, 6, 5, 'vendue', '2026-04-09 14:59:55', '2026-04-09 14:59:55'),
(343, 6, 343, 14, 6, 5, 'vendue', '2026-04-09 15:01:05', '2026-04-09 15:01:05'),
(344, 2, 344, 15, 7, 5, 'vendue', '2026-04-09 15:07:50', '2026-04-09 15:07:50'),
(345, 7, 345, 25, 17, 5, 'vendue', '2026-04-09 19:37:34', '2026-04-09 19:37:34'),
(346, 8, 346, 25, 17, 5, 'vendue', '2026-04-09 19:39:27', '2026-04-09 19:39:27'),
(347, 8, 347, 25, 17, 5, 'vendue', '2026-04-09 19:41:37', '2026-04-09 19:41:37'),
(348, 7, 348, 17, 9, 5, 'vendue', '2026-04-09 23:14:43', '2026-04-09 23:14:43'),
(349, 8, 349, 18, 10, 5, 'vendue', '2026-04-10 07:28:35', '2026-04-10 07:28:35'),
(350, 7, 350, 18, 10, 5, 'vendue', '2026-04-10 07:29:44', '2026-04-10 07:29:44'),
(351, 8, 351, 38, 31, 5, 'vendue', '2026-04-10 07:32:54', '2026-04-10 07:32:54'),
(352, 9, 352, 11, 3, 5, 'vendue', '2026-04-10 07:43:26', '2026-04-10 07:43:26'),
(353, 8, 353, 11, 3, 5, 'vendue', '2026-04-10 07:49:14', '2026-04-10 07:49:14'),
(354, 9, 354, 16, 8, 5, 'vendue', '2026-04-10 07:50:14', '2026-04-10 07:50:14'),
(355, 7, 355, 11, 3, 5, 'vendue', '2026-04-10 08:12:32', '2026-04-10 08:12:32'),
(356, 8, 356, 44, 35, 6, 'vendue', '2026-04-10 09:10:04', '2026-04-10 09:10:04'),
(357, 2, 357, 47, 38, 6, 'vendue', '2026-04-10 09:45:39', '2026-04-10 09:45:39'),
(358, 2, 358, 41, 32, 6, 'vendue', '2026-04-10 10:52:13', '2026-04-10 10:52:13'),
(359, 2, 359, 46, 37, 6, 'vendue', '2026-04-10 10:53:38', '2026-04-10 10:53:38'),
(360, 8, 360, 46, 37, 6, 'vendue', '2026-04-10 10:55:48', '2026-04-10 10:55:48'),
(363, 7, 363, 33, 26, 5, 'vendue', '2026-04-10 13:08:47', '2026-04-10 13:08:47'),
(364, 2, 364, 33, 26, 5, 'vendue', '2026-04-10 13:10:36', '2026-04-10 13:10:36'),
(366, 8, 366, 33, 26, 5, 'vendue', '2026-04-10 13:11:58', '2026-04-10 13:11:58'),
(367, 3, 367, 33, 26, 5, 'vendue', '2026-04-10 13:12:54', '2026-04-10 13:12:54'),
(368, 4, 368, 46, 37, 6, 'vendue', '2026-04-10 13:16:19', '2026-04-10 13:16:19'),
(369, 11, 369, 46, 37, 6, 'vendue', '2026-04-10 13:18:06', '2026-04-10 13:18:06'),
(370, 8, 370, 46, 37, 6, 'vendue', '2026-04-10 13:20:46', '2026-04-10 13:20:46'),
(371, 2, 371, 46, 37, 6, 'vendue', '2026-04-10 13:22:26', '2026-04-10 13:22:26'),
(372, 4, 372, 46, 37, 6, 'vendue', '2026-04-10 13:24:28', '2026-04-10 13:24:28'),
(373, 2, 373, 46, 37, 6, 'vendue', '2026-04-10 13:25:20', '2026-04-10 13:25:20'),
(374, 3, 374, 23, 15, 5, 'vendue', '2026-04-10 13:29:39', '2026-04-10 13:29:39'),
(375, 6, 375, 46, 37, 6, 'vendue', '2026-04-10 13:39:20', '2026-04-10 13:39:20'),
(376, 7, 376, 46, 37, 6, 'vendue', '2026-04-10 13:40:23', '2026-04-10 13:40:23'),
(377, 2, 377, 46, 37, 6, 'vendue', '2026-04-10 13:42:06', '2026-04-10 13:42:06'),
(378, 2, 378, 46, 37, 6, 'vendue', '2026-04-10 13:43:01', '2026-04-10 13:43:01'),
(379, 9, 379, 46, 37, 6, 'vendue', '2026-04-10 13:49:49', '2026-04-10 13:49:49'),
(380, 8, 380, 46, 37, 6, 'vendue', '2026-04-10 13:52:21', '2026-04-10 13:52:21'),
(381, 9, 381, 46, 37, 6, 'vendue', '2026-04-10 14:53:52', '2026-04-10 14:53:52'),
(382, 3, 382, 41, 32, 6, 'vendue', '2026-04-10 15:02:08', '2026-04-10 15:02:08'),
(383, 4, 383, 41, 32, 6, 'vendue', '2026-04-10 15:03:15', '2026-04-10 15:03:15'),
(384, 12, 384, 11, 3, 5, 'vendue', '2026-04-10 15:51:20', '2026-04-10 15:51:20'),
(385, 12, 385, 11, 3, 5, 'vendue', '2026-04-10 15:54:11', '2026-04-10 15:54:11'),
(386, 6, 386, 34, 27, 5, 'vendue', '2026-04-10 16:26:00', '2026-04-10 16:26:00'),
(387, 3, 387, 41, 32, 6, 'vendue', '2026-04-10 17:42:43', '2026-04-10 17:42:43'),
(388, 6, 388, 29, 22, 5, 'vendue', '2026-04-10 17:58:39', '2026-04-10 17:58:39'),
(389, 4, 389, 13, 5, 5, 'vendue', '2026-04-10 19:31:30', '2026-04-10 19:31:30'),
(390, 7, 390, 20, 12, 5, 'vendue', '2026-04-10 19:33:45', '2026-04-10 19:33:45'),
(391, 8, 391, 20, 12, 5, 'vendue', '2026-04-10 19:36:51', '2026-04-10 19:36:51'),
(392, 8, 392, 20, 12, 5, 'vendue', '2026-04-10 19:38:27', '2026-04-10 19:38:27'),
(393, 2, 393, 38, 31, 5, 'vendue', '2026-04-10 19:43:46', '2026-04-10 19:43:46'),
(394, 2, 394, 20, 12, 5, 'vendue', '2026-04-10 19:51:22', '2026-04-10 19:51:22'),
(395, 2, 395, 20, 12, 5, 'vendue', '2026-04-10 19:54:29', '2026-04-10 19:54:29'),
(396, 8, 396, 19, 11, 5, 'vendue', '2026-04-10 20:51:57', '2026-04-10 20:51:57'),
(397, 7, 397, 19, 11, 5, 'vendue', '2026-04-10 20:53:17', '2026-04-10 20:53:17'),
(398, 2, 398, 45, 36, 6, 'vendue', '2026-04-11 08:01:55', '2026-04-11 08:01:55'),
(399, 2, 399, 45, 36, 6, 'vendue', '2026-04-11 08:02:46', '2026-04-11 08:02:46'),
(400, 2, 400, 45, 36, 6, 'vendue', '2026-04-11 08:16:56', '2026-04-11 08:16:56'),
(401, 4, 401, 45, 36, 6, 'vendue', '2026-04-11 08:22:55', '2026-04-11 08:22:55'),
(402, 2, 402, 45, 36, 6, 'vendue', '2026-04-11 08:25:25', '2026-04-11 08:25:25'),
(403, 7, 403, 45, 36, 6, 'vendue', '2026-04-11 08:26:23', '2026-04-11 08:26:23'),
(404, 7, 404, 45, 36, 6, 'vendue', '2026-04-11 08:39:06', '2026-04-11 08:39:06'),
(405, 2, 405, 33, 26, 5, 'vendue', '2026-04-11 08:54:56', '2026-04-11 08:54:56'),
(406, 2, 406, 52, 19, 6, 'vendue', '2026-04-11 08:56:47', '2026-04-11 08:56:47'),
(407, 7, 407, 52, 19, 6, 'vendue', '2026-04-11 08:58:59', '2026-04-11 08:58:59'),
(408, 7, 408, 52, 19, 6, 'vendue', '2026-04-11 09:00:06', '2026-04-11 09:00:06'),
(409, 8, 409, 52, 19, 6, 'vendue', '2026-04-11 09:00:53', '2026-04-11 09:00:53'),
(410, 4, 410, 41, 32, 6, 'vendue', '2026-04-11 09:10:12', '2026-04-11 09:10:12'),
(411, 4, 411, 26, 18, 5, 'vendue', '2026-04-11 09:33:10', '2026-04-11 09:33:10'),
(412, 6, 412, 13, 5, 5, 'vendue', '2026-04-11 09:43:24', '2026-04-11 09:43:24'),
(413, 8, 413, 46, 37, 6, 'vendue', '2026-04-11 09:55:13', '2026-04-11 09:55:13'),
(414, 6, 414, 51, 42, 6, 'vendue', '2026-04-11 09:57:19', '2026-04-11 09:57:19'),
(415, 2, 415, 41, 32, 6, 'vendue', '2026-04-11 10:09:36', '2026-04-11 10:09:36'),
(416, 4, 416, 46, 37, 6, 'vendue', '2026-04-11 11:10:28', '2026-04-11 11:10:28'),
(417, 2, 417, 45, 36, 6, 'vendue', '2026-04-11 11:25:03', '2026-04-11 11:25:03'),
(418, 2, 418, 46, 37, 6, 'vendue', '2026-04-11 11:54:19', '2026-04-11 11:54:19'),
(419, 2, 419, 44, 35, 6, 'vendue', '2026-04-11 12:07:19', '2026-04-11 12:07:19'),
(420, 6, 420, 29, 22, 5, 'vendue', '2026-04-11 12:54:33', '2026-04-11 12:54:33'),
(421, 3, 421, 31, 24, 5, 'vendue', '2026-04-11 14:24:34', '2026-04-11 14:24:34'),
(422, 3, 422, 17, 9, 5, 'vendue', '2026-04-12 21:58:20', '2026-04-12 21:58:20'),
(423, 6, 423, 17, 9, 5, 'vendue', '2026-04-12 22:00:57', '2026-04-12 22:00:57'),
(424, 7, 424, 17, 9, 5, 'vendue', '2026-04-12 22:02:42', '2026-04-12 22:02:42'),
(425, 9, 425, 44, 35, 6, 'vendue', '2026-04-13 07:23:07', '2026-04-13 07:23:07'),
(426, 8, 426, 16, 8, 5, 'vendue', '2026-04-13 07:23:10', '2026-04-13 07:23:10'),
(427, 7, 427, 45, 36, 6, 'vendue', '2026-04-13 08:56:35', '2026-04-13 08:56:35'),
(428, 7, 428, 45, 36, 6, 'vendue', '2026-04-13 08:59:00', '2026-04-13 08:59:00'),
(429, 7, 429, 45, 36, 6, 'vendue', '2026-04-13 08:59:43', '2026-04-13 08:59:43'),
(430, 2, 430, 15, 7, 5, 'vendue', '2026-04-13 09:17:15', '2026-04-13 09:17:15'),
(431, 8, 431, 46, 37, 6, 'vendue', '2026-04-13 09:54:11', '2026-04-13 09:54:11'),
(432, 2, 432, 47, 38, 6, 'vendue', '2026-04-13 10:02:41', '2026-04-13 10:02:41'),
(433, 7, 433, 46, 37, 6, 'vendue', '2026-04-13 10:05:06', '2026-04-13 10:05:06'),
(434, 7, 434, 46, 37, 6, 'vendue', '2026-04-13 10:06:27', '2026-04-13 10:06:27'),
(435, 9, 435, 46, 37, 6, 'vendue', '2026-04-13 10:15:31', '2026-04-13 10:15:31'),
(436, 7, 436, 46, 37, 6, 'vendue', '2026-04-13 10:18:05', '2026-04-13 10:18:05'),
(437, 9, 437, 38, 31, 5, 'vendue', '2026-04-13 11:55:00', '2026-04-13 11:55:00'),
(438, 8, 438, 20, 12, 5, 'vendue', '2026-04-13 11:55:48', '2026-04-13 11:55:48'),
(439, 8, 439, 20, 12, 5, 'vendue', '2026-04-13 11:57:25', '2026-04-13 11:57:25'),
(440, 6, 440, 38, 31, 5, 'vendue', '2026-04-13 11:57:38', '2026-04-13 11:57:38'),
(441, 6, 441, 38, 31, 5, 'vendue', '2026-04-13 12:01:05', '2026-04-13 12:01:05'),
(442, 2, 442, 43, 34, 6, 'vendue', '2026-04-13 12:02:35', '2026-04-13 12:02:35'),
(443, 6, 443, 38, 31, 5, 'vendue', '2026-04-13 12:03:23', '2026-04-13 12:03:23'),
(444, 7, 444, 38, 31, 5, 'vendue', '2026-04-13 12:09:23', '2026-04-13 12:09:23'),
(445, 2, 445, 43, 34, 6, 'vendue', '2026-04-13 12:19:10', '2026-04-13 12:19:10'),
(446, 3, 446, 43, 34, 6, 'vendue', '2026-04-13 12:30:36', '2026-04-13 12:30:36'),
(447, 3, 447, 43, 34, 6, 'vendue', '2026-04-13 12:44:49', '2026-04-13 12:44:49'),
(448, 6, 448, 33, 26, 5, 'vendue', '2026-04-13 12:45:10', '2026-04-13 12:45:10'),
(449, 6, 449, 33, 26, 5, 'vendue', '2026-04-13 12:46:05', '2026-04-13 12:46:05'),
(450, 3, 450, 33, 26, 5, 'vendue', '2026-04-13 12:46:54', '2026-04-13 12:46:54'),
(451, 2, 451, 43, 34, 6, 'vendue', '2026-04-13 12:52:14', '2026-04-13 12:52:14'),
(452, 2, 452, 41, 32, 6, 'vendue', '2026-04-13 13:00:58', '2026-04-13 13:00:58'),
(453, 2, 453, 41, 32, 6, 'vendue', '2026-04-13 13:01:53', '2026-04-13 13:01:53'),
(454, 2, 454, 43, 34, 6, 'vendue', '2026-04-13 13:07:02', '2026-04-13 13:07:02'),
(455, 2, 455, 43, 34, 6, 'vendue', '2026-04-13 13:14:30', '2026-04-13 13:14:30'),
(456, 7, 456, 44, 35, 6, 'vendue', '2026-04-13 13:14:54', '2026-04-13 13:14:54'),
(457, 2, 457, 45, 36, 6, 'vendue', '2026-04-13 13:20:59', '2026-04-13 13:20:59'),
(458, 2, 458, 45, 36, 6, 'vendue', '2026-04-13 13:21:56', '2026-04-13 13:21:56'),
(459, 11, 459, 52, 19, 6, 'vendue', '2026-04-13 14:33:55', '2026-04-13 14:33:55'),
(460, 8, 460, 20, 12, 5, 'vendue', '2026-04-13 14:34:49', '2026-04-13 14:34:49'),
(461, 11, 461, 29, 22, 5, 'vendue', '2026-04-13 15:02:02', '2026-04-13 15:02:02'),
(462, 2, 462, 47, 38, 6, 'vendue', '2026-04-13 15:20:02', '2026-04-13 15:20:02'),
(463, 2, 463, 52, 19, 6, 'vendue', '2026-04-13 15:37:20', '2026-04-13 15:37:20'),
(464, 8, 464, 19, 11, 5, 'vendue', '2026-04-13 15:38:36', '2026-04-13 15:38:36'),
(465, 7, 465, 52, 19, 6, 'vendue', '2026-04-13 15:40:39', '2026-04-13 15:40:39'),
(466, 9, 466, 11, 3, 5, 'vendue', '2026-04-13 16:37:44', '2026-04-13 16:37:44'),
(467, 7, 467, 11, 3, 5, 'vendue', '2026-04-13 16:39:15', '2026-04-13 16:39:15'),
(468, 10, 468, 11, 3, 5, 'vendue', '2026-04-13 16:41:09', '2026-04-13 16:41:09'),
(469, 4, 469, 11, 3, 5, 'vendue', '2026-04-13 16:42:20', '2026-04-13 16:42:20'),
(470, 4, 470, 11, 3, 5, 'vendue', '2026-04-13 16:43:43', '2026-04-13 16:43:43'),
(471, 6, 471, 11, 3, 5, 'vendue', '2026-04-13 16:45:07', '2026-04-13 16:45:07'),
(472, 7, 472, 11, 3, 5, 'vendue', '2026-04-13 16:47:18', '2026-04-13 16:47:18'),
(473, 9, 473, 11, 3, 5, 'vendue', '2026-04-13 16:48:54', '2026-04-13 16:48:54'),
(474, 11, 474, 11, 3, 5, 'vendue', '2026-04-13 16:52:32', '2026-04-13 16:52:32'),
(475, 12, 475, 11, 3, 5, 'vendue', '2026-04-13 16:54:24', '2026-04-13 16:54:24'),
(476, 12, 476, 11, 3, 5, 'vendue', '2026-04-13 16:55:51', '2026-04-13 16:55:51'),
(477, 8, 477, 45, 36, 6, 'vendue', '2026-04-13 17:22:31', '2026-04-13 17:22:31'),
(478, 6, 478, 45, 36, 6, 'vendue', '2026-04-13 17:23:30', '2026-04-13 17:23:30'),
(479, 6, 479, 45, 36, 6, 'vendue', '2026-04-13 17:24:42', '2026-04-13 17:24:42'),
(480, 3, 480, 45, 36, 6, 'vendue', '2026-04-13 17:25:50', '2026-04-13 17:25:50'),
(481, 3, 481, 15, 7, 5, 'vendue', '2026-04-13 17:40:04', '2026-04-13 17:40:04'),
(482, 3, 482, 23, 15, 5, 'vendue', '2026-04-13 17:40:42', '2026-04-13 17:40:42'),
(483, 4, 483, 23, 15, 5, 'vendue', '2026-04-13 17:41:34', '2026-04-13 17:41:34'),
(484, 7, 484, 18, 10, 5, 'vendue', '2026-04-14 07:29:39', '2026-04-14 07:29:39'),
(485, 4, 485, 18, 10, 5, 'vendue', '2026-04-14 07:30:39', '2026-04-14 07:30:39'),
(486, 3, 486, 18, 10, 5, 'vendue', '2026-04-14 07:31:22', '2026-04-14 07:31:22'),
(487, 2, 487, 43, 34, 6, 'vendue', '2026-04-14 08:22:26', '2026-04-14 08:22:26'),
(488, 2, 488, 43, 34, 6, 'vendue', '2026-04-14 09:01:06', '2026-04-14 09:01:06'),
(489, 4, 489, 43, 34, 6, 'vendue', '2026-04-14 10:15:34', '2026-04-14 10:15:34'),
(490, 7, 490, 36, 29, 5, 'vendue', '2026-04-14 10:15:35', '2026-04-14 10:15:35'),
(491, 3, 491, 43, 34, 6, 'vendue', '2026-04-14 10:16:28', '2026-04-14 10:16:28'),
(492, 9, 492, 16, 8, 5, 'vendue', '2026-04-14 10:31:35', '2026-04-14 10:31:35'),
(493, 8, 493, 16, 8, 5, 'vendue', '2026-04-14 10:33:18', '2026-04-14 10:33:18'),
(494, 7, 494, 13, 5, 5, 'vendue', '2026-04-14 11:27:50', '2026-04-14 11:27:50'),
(495, 4, 495, 26, 18, 5, 'vendue', '2026-04-14 11:32:27', '2026-04-14 11:32:27'),
(496, 8, 496, 44, 35, 6, 'vendue', '2026-04-14 11:41:23', '2026-04-14 11:41:23'),
(497, 9, 497, 46, 37, 6, 'vendue', '2026-04-14 11:45:45', '2026-04-14 11:45:45'),
(498, 9, 498, 46, 37, 6, 'vendue', '2026-04-14 11:46:50', '2026-04-14 11:46:50'),
(499, 3, 499, 46, 37, 6, 'vendue', '2026-04-14 11:47:53', '2026-04-14 11:47:53'),
(500, 8, 500, 46, 37, 6, 'vendue', '2026-04-14 11:49:28', '2026-04-14 11:49:28'),
(501, 9, 501, 46, 37, 6, 'vendue', '2026-04-14 11:51:16', '2026-04-14 11:51:16'),
(502, 8, 502, 20, 12, 5, 'vendue', '2026-04-14 11:54:19', '2026-04-14 11:54:19'),
(503, 8, 503, 33, 26, 5, 'vendue', '2026-04-14 11:58:35', '2026-04-14 11:58:35'),
(504, 8, 504, 33, 26, 5, 'vendue', '2026-04-14 12:00:20', '2026-04-14 12:00:20'),
(505, 2, 505, 33, 26, 5, 'vendue', '2026-04-14 12:01:59', '2026-04-14 12:01:59'),
(506, 2, 506, 13, 5, 5, 'vendue', '2026-04-14 12:07:25', '2026-04-14 12:07:25'),
(507, 7, 507, 20, 12, 5, 'vendue', '2026-04-14 12:39:01', '2026-04-14 12:39:01'),
(508, 2, 508, 43, 34, 6, 'vendue', '2026-04-14 13:50:23', '2026-04-14 13:50:23'),
(509, 4, 509, 21, 13, 5, 'vendue', '2026-04-14 14:06:10', '2026-04-14 14:06:10'),
(510, 4, 510, 21, 13, 5, 'vendue', '2026-04-14 14:10:15', '2026-04-14 14:10:15'),
(511, 6, 511, 35, 28, 5, 'vendue', '2026-04-14 14:10:54', '2026-04-14 14:10:54'),
(512, 2, 512, 15, 7, 5, 'vendue', '2026-04-14 14:10:58', '2026-04-14 14:10:58'),
(513, 2, 513, 15, 7, 5, 'vendue', '2026-04-14 14:12:08', '2026-04-14 14:12:08'),
(514, 2, 514, 15, 7, 5, 'vendue', '2026-04-14 14:14:03', '2026-04-14 14:14:03'),
(515, 2, 515, 43, 34, 6, 'vendue', '2026-04-14 14:28:27', '2026-04-14 14:28:27'),
(516, 11, 516, 45, 36, 6, 'vendue', '2026-04-14 14:55:59', '2026-04-14 14:55:59'),
(517, 9, 517, 38, 31, 5, 'vendue', '2026-04-14 15:29:36', '2026-04-14 15:29:36'),
(518, 2, 518, 38, 31, 5, 'vendue', '2026-04-14 15:32:03', '2026-04-14 15:32:03'),
(519, 13, 519, 29, 22, 5, 'vendue', '2026-04-14 15:52:20', '2026-04-14 15:52:20'),
(520, 6, 520, 29, 22, 5, 'vendue', '2026-04-14 15:54:19', '2026-04-14 15:54:19'),
(521, 2, 521, 11, 3, 5, 'vendue', '2026-04-14 17:17:55', '2026-04-14 17:17:55'),
(522, 7, 522, 11, 3, 5, 'vendue', '2026-04-14 17:19:25', '2026-04-14 17:19:25'),
(523, 7, 523, 11, 3, 5, 'vendue', '2026-04-14 17:21:54', '2026-04-14 17:21:54'),
(524, 7, 524, 11, 3, 5, 'vendue', '2026-04-14 17:24:28', '2026-04-14 17:24:28'),
(525, 3, 525, 52, 19, 6, 'vendue', '2026-04-14 17:56:26', '2026-04-14 17:56:26'),
(526, 8, 526, 52, 19, 6, 'vendue', '2026-04-14 17:58:27', '2026-04-14 17:58:27'),
(527, 2, 527, 10, 2, 5, 'vendue', '2026-04-14 18:07:13', '2026-04-14 18:07:13'),
(528, 2, 528, 10, 2, 5, 'vendue', '2026-04-14 18:08:00', '2026-04-14 18:08:00'),
(529, 8, 529, 10, 2, 5, 'vendue', '2026-04-14 18:09:21', '2026-04-14 18:09:21'),
(530, 6, 530, 10, 2, 5, 'vendue', '2026-04-14 18:10:29', '2026-04-14 18:10:29'),
(531, 7, 531, 10, 2, 5, 'vendue', '2026-04-14 18:11:21', '2026-04-14 18:11:21'),
(532, 9, 532, 19, 11, 5, 'vendue', '2026-04-14 18:18:17', '2026-04-14 18:18:17'),
(533, 3, 533, 10, 2, 5, 'vendue', '2026-04-14 18:54:52', '2026-04-14 18:54:52'),
(534, 8, 534, 31, 24, 5, 'vendue', '2026-04-14 22:08:15', '2026-04-14 22:08:15'),
(535, 8, 535, 31, 24, 5, 'vendue', '2026-04-14 22:13:09', '2026-04-14 22:13:09'),
(536, 7, 536, 31, 24, 5, 'vendue', '2026-04-14 22:15:30', '2026-04-14 22:15:30'),
(537, 9, 537, 31, 24, 5, 'vendue', '2026-04-14 22:18:58', '2026-04-14 22:18:58'),
(538, 6, 538, 25, 17, 5, 'vendue', '2026-04-14 22:34:03', '2026-04-14 22:34:03'),
(539, 7, 539, 25, 17, 5, 'vendue', '2026-04-14 22:36:07', '2026-04-14 22:36:07'),
(540, 7, 540, 25, 17, 5, 'vendue', '2026-04-14 22:38:11', '2026-04-14 22:38:11'),
(541, 8, 541, 25, 17, 5, 'vendue', '2026-04-14 22:39:53', '2026-04-14 22:39:53'),
(542, 7, 542, 25, 17, 5, 'vendue', '2026-04-14 22:41:27', '2026-04-14 22:41:27'),
(543, 9, 543, 55, 40, 6, 'vendue', '2026-04-15 07:51:21', '2026-04-15 07:51:21'),
(544, 7, 544, 55, 40, 6, 'vendue', '2026-04-15 07:53:26', '2026-04-15 07:53:26'),
(545, 3, 545, 55, 40, 6, 'vendue', '2026-04-15 07:57:21', '2026-04-15 07:57:21'),
(546, 3, 546, 55, 40, 6, 'vendue', '2026-04-15 08:00:59', '2026-04-15 08:00:59'),
(547, 9, 547, 55, 40, 6, 'vendue', '2026-04-15 08:03:27', '2026-04-15 08:03:27'),
(548, 3, 548, 52, 19, 6, 'vendue', '2026-04-15 08:37:51', '2026-04-15 08:37:51'),
(549, 8, 549, 52, 19, 6, 'vendue', '2026-04-15 08:39:49', '2026-04-15 08:39:49'),
(550, 2, 550, 50, 43, 6, 'vendue', '2026-04-15 08:47:10', '2026-04-21 10:04:50'),
(551, 2, 551, 50, 43, 6, 'vendue', '2026-04-15 08:51:47', '2026-04-21 10:04:50'),
(552, 7, 552, 46, 37, 6, 'vendue', '2026-04-15 09:07:19', '2026-04-15 09:07:19'),
(553, 7, 553, 18, 10, 5, 'vendue', '2026-04-15 09:10:57', '2026-04-15 09:10:57'),
(554, 4, 554, 18, 10, 5, 'vendue', '2026-04-15 09:11:41', '2026-04-15 09:11:41'),
(555, 13, 555, 44, 35, 6, 'vendue', '2026-04-15 09:16:37', '2026-04-15 09:16:37'),
(556, 4, 556, 14, 6, 5, 'vendue', '2026-04-15 09:32:52', '2026-04-15 09:32:52'),
(557, 2, 557, 21, 13, 5, 'vendue', '2026-04-15 09:48:43', '2026-04-15 09:48:43'),
(558, 4, 558, 21, 13, 5, 'vendue', '2026-04-15 09:51:05', '2026-04-15 09:51:05'),
(559, 3, 559, 21, 13, 5, 'vendue', '2026-04-15 09:51:32', '2026-04-15 09:51:32'),
(560, 4, 560, 21, 13, 5, 'vendue', '2026-04-15 09:55:35', '2026-04-15 09:55:35'),
(561, 2, 561, 43, 34, 6, 'vendue', '2026-04-15 10:04:03', '2026-04-15 10:04:03'),
(562, 2, 562, 43, 34, 6, 'vendue', '2026-04-15 10:04:51', '2026-04-15 10:04:51'),
(563, 3, 563, 14, 6, 5, 'vendue', '2026-04-15 10:23:06', '2026-04-15 10:23:06'),
(564, 2, 564, 47, 38, 6, 'vendue', '2026-04-15 10:23:35', '2026-04-15 10:23:35'),
(565, 2, 565, 50, 43, 6, 'vendue', '2026-04-15 10:53:08', '2026-04-21 10:04:50'),
(566, 8, 566, 46, 37, 6, 'vendue', '2026-04-15 10:53:32', '2026-04-15 10:53:32'),
(567, 8, 567, 46, 37, 6, 'vendue', '2026-04-15 11:07:15', '2026-04-15 11:07:15'),
(568, 2, 568, 45, 36, 6, 'vendue', '2026-04-15 11:34:57', '2026-04-15 11:34:57'),
(569, 2, 569, 41, 32, 6, 'vendue', '2026-04-15 11:36:25', '2026-04-15 11:36:25'),
(570, 2, 570, 52, 19, 6, 'vendue', '2026-04-15 11:39:36', '2026-04-15 11:39:36'),
(571, 9, 571, 20, 12, 5, 'vendue', '2026-04-15 11:58:40', '2026-04-15 11:58:40'),
(572, 2, 572, 43, 34, 6, 'vendue', '2026-04-15 11:59:32', '2026-04-15 11:59:32'),
(573, 2, 573, 43, 34, 6, 'vendue', '2026-04-15 12:15:13', '2026-04-15 12:15:13'),
(574, 2, 574, 37, 30, 5, 'vendue', '2026-04-15 12:27:00', '2026-04-15 12:27:00'),
(575, 6, 575, 37, 30, 5, 'vendue', '2026-04-15 12:30:41', '2026-04-15 12:30:41'),
(576, 2, 576, 43, 34, 6, 'vendue', '2026-04-15 12:39:34', '2026-04-15 12:39:34'),
(577, 3, 577, 37, 30, 5, 'vendue', '2026-04-15 12:43:57', '2026-04-15 12:43:57'),
(578, 3, 578, 55, 40, 6, 'vendue', '2026-04-15 13:07:32', '2026-04-15 13:07:32'),
(579, 3, 579, 26, 18, 5, 'vendue', '2026-04-15 13:07:45', '2026-04-15 13:07:45'),
(580, 3, 580, 55, 40, 6, 'vendue', '2026-04-15 13:09:05', '2026-04-15 13:09:05'),
(581, 4, 581, 26, 18, 5, 'vendue', '2026-04-15 13:09:08', '2026-04-15 13:09:08'),
(582, 3, 582, 55, 40, 6, 'vendue', '2026-04-15 13:10:17', '2026-04-15 13:10:17'),
(583, 3, 583, 33, 26, 5, 'vendue', '2026-04-15 13:34:34', '2026-04-15 13:34:34'),
(584, 8, 584, 33, 26, 5, 'vendue', '2026-04-15 13:36:53', '2026-04-15 13:36:53'),
(585, 7, 585, 33, 26, 5, 'vendue', '2026-04-15 13:38:41', '2026-04-15 13:38:41'),
(586, 3, 586, 15, 7, 5, 'vendue', '2026-04-15 13:41:03', '2026-04-15 13:41:03'),
(587, 8, 587, 50, 43, 6, 'vendue', '2026-04-15 13:49:54', '2026-04-21 10:04:50'),
(588, 8, 588, 33, 26, 5, 'vendue', '2026-04-15 13:51:22', '2026-04-15 13:51:22'),
(589, 3, 589, 46, 37, 6, 'vendue', '2026-04-15 13:58:25', '2026-04-15 13:58:25'),
(590, 3, 590, 43, 34, 6, 'vendue', '2026-04-15 14:09:15', '2026-04-15 14:09:15'),
(591, 8, 591, 20, 12, 5, 'vendue', '2026-04-15 14:09:34', '2026-04-15 14:09:34'),
(592, 2, 592, 43, 34, 6, 'vendue', '2026-04-15 14:10:03', '2026-04-15 14:10:03'),
(593, 4, 593, 43, 34, 6, 'vendue', '2026-04-15 14:10:48', '2026-04-15 14:10:48'),
(594, 3, 594, 51, 42, 6, 'vendue', '2026-04-15 14:17:50', '2026-04-15 14:17:50'),
(595, 8, 595, 16, 8, 5, 'vendue', '2026-04-15 14:39:36', '2026-04-15 14:39:36'),
(596, 8, 596, 16, 8, 5, 'vendue', '2026-04-15 14:42:02', '2026-04-15 14:42:02'),
(597, 2, 597, 43, 34, 6, 'vendue', '2026-04-15 14:43:06', '2026-04-15 14:43:06'),
(598, 2, 598, 46, 37, 6, 'vendue', '2026-04-15 14:50:49', '2026-04-15 14:50:49'),
(599, 3, 599, 26, 18, 5, 'vendue', '2026-04-15 15:02:59', '2026-04-15 15:02:59'),
(600, 2, 600, 46, 37, 6, 'vendue', '2026-04-15 15:03:38', '2026-04-15 15:03:38'),
(601, 9, 601, 55, 40, 6, 'vendue', '2026-04-15 15:17:07', '2026-04-15 15:17:07'),
(602, 3, 602, 55, 40, 6, 'vendue', '2026-04-15 15:19:05', '2026-04-15 15:19:05'),
(603, 2, 603, 38, 31, 5, 'vendue', '2026-04-15 15:40:09', '2026-04-15 15:40:09'),
(604, 2, 604, 38, 31, 5, 'vendue', '2026-04-15 15:41:01', '2026-04-15 15:41:01'),
(605, 4, 605, 26, 18, 5, 'vendue', '2026-04-15 15:47:25', '2026-04-15 15:47:25'),
(606, 6, 606, 11, 3, 5, 'vendue', '2026-04-15 17:54:55', '2026-04-15 17:54:55'),
(607, 2, 607, 11, 3, 5, 'vendue', '2026-04-15 17:55:57', '2026-04-15 17:55:57'),
(608, 11, 608, 11, 3, 5, 'vendue', '2026-04-15 17:59:05', '2026-04-15 17:59:05'),
(610, 8, 610, 25, 17, 5, 'vendue', '2026-04-15 22:18:09', '2026-04-15 22:18:09'),
(611, 8, 611, 25, 17, 5, 'vendue', '2026-04-15 22:20:06', '2026-04-15 22:20:06'),
(612, 8, 612, 25, 17, 5, 'vendue', '2026-04-15 22:22:18', '2026-04-15 22:22:18'),
(613, 7, 613, 25, 17, 5, 'vendue', '2026-04-15 22:24:00', '2026-04-15 22:24:00'),
(614, 2, 614, 46, 37, 6, 'vendue', '2026-04-16 07:11:22', '2026-04-16 07:11:22'),
(615, 2, 615, 54, 44, 6, 'vendue', '2026-04-16 07:40:36', '2026-04-16 07:40:36'),
(616, 2, 616, 54, 44, 6, 'vendue', '2026-04-16 07:41:56', '2026-04-16 07:41:56'),
(617, 6, 617, 50, 43, 6, 'vendue', '2026-04-16 07:41:56', '2026-04-21 10:04:50'),
(618, 6, 618, 50, 43, 6, 'vendue', '2026-04-16 07:42:46', '2026-04-21 10:04:50'),
(619, 2, 619, 20, 12, 5, 'vendue', '2026-04-16 07:49:51', '2026-04-16 07:49:51'),
(620, 9, 620, 46, 37, 6, 'vendue', '2026-04-16 08:10:18', '2026-04-16 08:10:18'),
(621, 7, 621, 38, 31, 5, 'vendue', '2026-04-16 08:42:11', '2026-04-16 08:42:11'),
(622, 2, 622, 45, 36, 6, 'vendue', '2026-04-16 08:50:00', '2026-04-16 08:50:00'),
(623, 7, 623, 46, 37, 6, 'vendue', '2026-04-16 09:03:03', '2026-04-16 09:03:03'),
(624, 3, 624, 55, 40, 6, 'vendue', '2026-04-16 09:18:31', '2026-04-16 09:18:31'),
(625, 2, 625, 43, 34, 6, 'vendue', '2026-04-16 09:22:58', '2026-04-16 09:22:58'),
(626, 9, 626, 50, 43, 6, 'vendue', '2026-04-16 09:24:12', '2026-04-21 10:04:50'),
(627, 3, 627, 55, 40, 6, 'vendue', '2026-04-16 10:01:32', '2026-04-16 10:01:32'),
(628, 6, 628, 50, 43, 6, 'vendue', '2026-04-16 10:10:14', '2026-04-21 10:04:50'),
(629, 8, 629, 50, 43, 6, 'vendue', '2026-04-16 10:15:56', '2026-04-21 10:04:50'),
(630, 2, 630, 46, 37, 6, 'vendue', '2026-04-16 10:34:33', '2026-04-16 10:34:33'),
(631, 6, 631, 50, 43, 6, 'vendue', '2026-04-16 10:39:16', '2026-04-21 10:04:50'),
(632, 2, 632, 43, 34, 6, 'vendue', '2026-04-16 10:40:54', '2026-04-16 10:40:54'),
(633, 6, 633, 51, 42, 6, 'vendue', '2026-04-16 10:57:23', '2026-04-16 10:57:23'),
(634, 6, 634, 51, 42, 6, 'vendue', '2026-04-16 10:59:24', '2026-04-16 10:59:24'),
(635, 6, 635, 50, 43, 6, 'vendue', '2026-04-16 11:00:15', '2026-04-21 10:04:50'),
(636, 2, 636, 20, 12, 5, 'vendue', '2026-04-16 11:18:34', '2026-04-16 11:18:34'),
(637, 2, 637, 41, 32, 6, 'vendue', '2026-04-16 11:31:54', '2026-04-16 11:31:54'),
(638, 2, 638, 45, 36, 6, 'vendue', '2026-04-16 11:33:04', '2026-04-16 11:33:04'),
(639, 8, 639, 33, 26, 5, 'vendue', '2026-04-16 11:50:10', '2026-04-16 11:50:10'),
(640, 2, 640, 41, 32, 6, 'vendue', '2026-04-16 11:50:36', '2026-04-16 11:50:36');
INSERT INTO `ventes` (`id`, `type_carte_id`, `client_id`, `user_id`, `agence_id`, `campagne_id`, `statut_activation`, `created_at`, `updated_at`) VALUES
(641, 8, 641, 33, 26, 5, 'vendue', '2026-04-16 11:51:18', '2026-04-16 11:51:18'),
(642, 6, 642, 33, 26, 5, 'vendue', '2026-04-16 11:52:09', '2026-04-16 11:52:09'),
(643, 3, 643, 33, 26, 5, 'vendue', '2026-04-16 11:53:01', '2026-04-16 11:53:01'),
(644, 9, 644, 16, 8, 5, 'vendue', '2026-04-16 11:57:43', '2026-04-16 11:57:43'),
(645, 11, 645, 46, 37, 6, 'vendue', '2026-04-16 12:00:18', '2026-04-16 12:00:18'),
(646, 8, 646, 46, 37, 6, 'vendue', '2026-04-16 12:20:05', '2026-04-16 12:20:05'),
(647, 2, 647, 20, 12, 5, 'vendue', '2026-04-16 12:25:51', '2026-04-16 12:25:51'),
(648, 6, 648, 33, 26, 5, 'vendue', '2026-04-16 12:31:50', '2026-04-16 12:31:50'),
(649, 8, 649, 51, 42, 6, 'vendue', '2026-04-16 12:43:16', '2026-04-16 12:43:16'),
(650, 2, 650, 46, 37, 6, 'vendue', '2026-04-16 13:12:18', '2026-04-16 13:12:18'),
(651, 7, 651, 46, 37, 6, 'vendue', '2026-04-16 13:13:53', '2026-04-16 13:13:53'),
(652, 4, 652, 13, 5, 5, 'vendue', '2026-04-16 13:54:42', '2026-04-16 13:54:42'),
(654, 3, 654, 13, 5, 5, 'vendue', '2026-04-16 13:55:24', '2026-04-16 13:55:24'),
(655, 11, 655, 13, 5, 5, 'vendue', '2026-04-16 13:57:42', '2026-04-16 13:57:42'),
(657, 2, 657, 43, 34, 6, 'vendue', '2026-04-16 14:04:50', '2026-04-16 14:04:50'),
(658, 3, 658, 43, 34, 6, 'vendue', '2026-04-16 14:06:07', '2026-04-16 14:06:07'),
(659, 8, 659, 51, 42, 6, 'vendue', '2026-04-16 14:08:05', '2026-04-16 14:08:05'),
(660, 4, 660, 43, 34, 6, 'vendue', '2026-04-16 14:11:59', '2026-04-16 14:11:59'),
(661, 3, 661, 16, 8, 5, 'vendue', '2026-04-16 14:13:12', '2026-04-16 14:13:12'),
(662, 7, 662, 45, 36, 6, 'vendue', '2026-04-16 14:22:43', '2026-04-16 14:22:43'),
(663, 2, 663, 45, 36, 6, 'vendue', '2026-04-16 14:23:25', '2026-04-16 14:23:25'),
(664, 2, 664, 33, 26, 5, 'vendue', '2026-04-16 14:33:56', '2026-04-16 14:33:56'),
(665, 2, 665, 11, 3, 5, 'vendue', '2026-04-16 15:25:16', '2026-04-16 15:25:16'),
(666, 9, 666, 11, 3, 5, 'vendue', '2026-04-16 15:26:29', '2026-04-16 15:26:29'),
(667, 13, 667, 11, 3, 5, 'vendue', '2026-04-16 15:28:16', '2026-04-16 15:28:16'),
(668, 8, 668, 11, 3, 5, 'vendue', '2026-04-16 15:29:28', '2026-04-16 15:29:28'),
(669, 2, 669, 26, 18, 5, 'vendue', '2026-04-16 15:29:53', '2026-04-16 15:29:53'),
(670, 2, 670, 23, 15, 5, 'vendue', '2026-04-16 16:58:12', '2026-04-16 16:58:12'),
(671, 2, 671, 23, 15, 5, 'vendue', '2026-04-16 16:59:00', '2026-04-16 16:59:00'),
(672, 2, 672, 23, 15, 5, 'vendue', '2026-04-16 16:59:37', '2026-04-16 16:59:37'),
(673, 3, 673, 19, 11, 5, 'vendue', '2026-04-16 20:06:59', '2026-04-16 20:06:59'),
(674, 3, 674, 19, 11, 5, 'vendue', '2026-04-16 20:08:03', '2026-04-16 20:08:03'),
(675, 3, 675, 19, 11, 5, 'vendue', '2026-04-16 20:09:05', '2026-04-16 20:09:05'),
(676, 9, 676, 10, 2, 5, 'vendue', '2026-04-16 20:15:51', '2026-04-16 20:15:51'),
(677, 2, 677, 21, 13, 5, 'vendue', '2026-04-16 21:03:17', '2026-04-16 21:03:17'),
(678, 2, 678, 21, 13, 5, 'vendue', '2026-04-16 21:04:06', '2026-04-16 21:04:06'),
(679, 3, 679, 21, 13, 5, 'vendue', '2026-04-16 21:04:57', '2026-04-16 21:04:57'),
(680, 2, 680, 21, 13, 5, 'vendue', '2026-04-16 21:05:42', '2026-04-16 21:05:42'),
(681, 2, 681, 21, 13, 5, 'vendue', '2026-04-16 21:06:20', '2026-04-16 21:06:20'),
(682, 2, 682, 21, 13, 5, 'vendue', '2026-04-16 21:07:43', '2026-04-16 21:07:43'),
(683, 2, 683, 21, 13, 5, 'vendue', '2026-04-16 21:10:17', '2026-04-16 21:10:17'),
(684, 4, 684, 21, 13, 5, 'vendue', '2026-04-16 21:11:24', '2026-04-16 21:11:24'),
(685, 2, 685, 21, 13, 5, 'vendue', '2026-04-16 21:12:09', '2026-04-16 21:12:09'),
(686, 2, 686, 21, 13, 5, 'vendue', '2026-04-16 21:12:29', '2026-04-16 21:12:29'),
(687, 2, 687, 21, 13, 5, 'vendue', '2026-04-16 21:19:04', '2026-04-16 21:19:04'),
(688, 3, 688, 21, 13, 5, 'vendue', '2026-04-16 21:20:57', '2026-04-16 21:20:57'),
(689, 2, 689, 21, 13, 5, 'vendue', '2026-04-16 21:21:57', '2026-04-16 21:21:57'),
(690, 2, 690, 21, 13, 5, 'vendue', '2026-04-16 21:22:43', '2026-04-16 21:22:43'),
(691, 2, 691, 21, 13, 5, 'vendue', '2026-04-16 21:52:59', '2026-04-16 21:52:59'),
(692, 4, 692, 21, 13, 5, 'vendue', '2026-04-16 21:54:30', '2026-04-16 21:54:30'),
(693, 4, 693, 21, 13, 5, 'vendue', '2026-04-16 21:55:34', '2026-04-16 21:55:34'),
(694, 4, 694, 21, 13, 5, 'vendue', '2026-04-16 21:56:45', '2026-04-16 21:56:45'),
(695, 4, 695, 21, 13, 5, 'vendue', '2026-04-16 21:58:28', '2026-04-16 21:58:28'),
(696, 4, 696, 21, 13, 5, 'vendue', '2026-04-16 22:00:41', '2026-04-16 22:00:41'),
(697, 3, 697, 21, 13, 5, 'vendue', '2026-04-16 22:03:57', '2026-04-16 22:03:57'),
(698, 2, 698, 21, 13, 5, 'vendue', '2026-04-16 22:04:57', '2026-04-16 22:04:57'),
(699, 2, 699, 21, 13, 5, 'vendue', '2026-04-16 22:06:09', '2026-04-16 22:06:09'),
(702, 4, 702, 25, 17, 5, 'vendue', '2026-04-16 22:37:00', '2026-04-16 22:37:00'),
(703, 4, 703, 25, 17, 5, 'vendue', '2026-04-16 22:38:00', '2026-04-16 22:38:00'),
(704, 7, 704, 25, 17, 5, 'vendue', '2026-04-16 22:43:12', '2026-04-16 22:43:12'),
(705, 3, 705, 21, 13, 5, 'vendue', '2026-04-17 07:51:20', '2026-04-17 07:51:20'),
(706, 4, 706, 21, 13, 5, 'vendue', '2026-04-17 07:54:17', '2026-04-17 07:54:17'),
(707, 2, 707, 21, 13, 5, 'vendue', '2026-04-17 07:55:10', '2026-04-17 07:55:10'),
(708, 2, 708, 21, 13, 5, 'vendue', '2026-04-17 07:56:33', '2026-04-17 07:56:33'),
(709, 2, 709, 21, 13, 5, 'vendue', '2026-04-17 07:58:10', '2026-04-17 07:58:10'),
(710, 4, 710, 21, 13, 5, 'vendue', '2026-04-17 07:59:34', '2026-04-17 07:59:34'),
(711, 2, 711, 21, 13, 5, 'vendue', '2026-04-17 08:01:20', '2026-04-17 08:01:20'),
(712, 3, 712, 21, 13, 5, 'vendue', '2026-04-17 08:03:10', '2026-04-17 08:03:10'),
(713, 2, 713, 21, 13, 5, 'vendue', '2026-04-17 08:04:01', '2026-04-17 08:04:01'),
(714, 2, 714, 21, 13, 5, 'vendue', '2026-04-17 08:05:32', '2026-04-17 08:05:32'),
(715, 3, 715, 43, 34, 6, 'vendue', '2026-04-17 08:10:11', '2026-04-17 08:10:11'),
(716, 2, 716, 43, 34, 6, 'vendue', '2026-04-17 08:20:07', '2026-04-17 08:20:07'),
(717, 8, 717, 34, 27, 5, 'vendue', '2026-04-17 08:32:28', '2026-04-17 08:32:28'),
(718, 3, 718, 34, 27, 5, 'vendue', '2026-04-17 08:34:35', '2026-04-17 08:34:35'),
(719, 2, 719, 34, 27, 5, 'vendue', '2026-04-17 08:36:06', '2026-04-17 08:36:06'),
(720, 3, 720, 16, 8, 5, 'vendue', '2026-04-17 09:00:15', '2026-04-17 09:00:15'),
(721, 4, 721, 21, 13, 5, 'vendue', '2026-04-17 09:03:18', '2026-04-17 09:03:18'),
(722, 7, 722, 45, 36, 6, 'vendue', '2026-04-17 09:49:16', '2026-04-17 09:49:16'),
(724, 2, 724, 46, 37, 6, 'vendue', '2026-04-17 10:04:50', '2026-04-17 10:04:50'),
(725, 6, 725, 50, 43, 6, 'vendue', '2026-04-17 10:08:13', '2026-04-21 10:04:50'),
(726, 2, 726, 55, 40, 6, 'vendue', '2026-04-17 10:20:02', '2026-04-17 10:20:02'),
(727, 2, 727, 55, 40, 6, 'vendue', '2026-04-17 10:21:43', '2026-04-17 10:21:43'),
(728, 2, 728, 51, 42, 6, 'vendue', '2026-04-17 10:22:34', '2026-04-17 10:22:34'),
(729, 2, 729, 41, 32, 6, 'vendue', '2026-04-17 10:23:12', '2026-04-17 10:23:12'),
(730, 2, 730, 51, 42, 6, 'vendue', '2026-04-17 10:24:30', '2026-04-17 10:24:30'),
(731, 4, 731, 41, 32, 6, 'vendue', '2026-04-17 10:24:40', '2026-04-17 10:24:40'),
(732, 4, 732, 51, 42, 6, 'vendue', '2026-04-17 10:28:47', '2026-04-17 10:28:47'),
(733, 2, 733, 54, 44, 6, 'vendue', '2026-04-17 10:43:49', '2026-04-17 10:43:49'),
(734, 4, 734, 45, 36, 6, 'vendue', '2026-04-17 10:44:27', '2026-04-17 10:44:27'),
(735, 7, 735, 33, 26, 5, 'vendue', '2026-04-17 10:59:33', '2026-04-17 10:59:33'),
(736, 4, 736, 33, 26, 5, 'vendue', '2026-04-17 11:00:53', '2026-04-17 11:00:53'),
(737, 2, 737, 55, 40, 6, 'vendue', '2026-04-17 11:03:02', '2026-04-17 11:03:02'),
(738, 6, 738, 44, 35, 6, 'vendue', '2026-04-17 11:05:13', '2026-04-17 11:05:13'),
(739, 4, 739, 55, 40, 6, 'vendue', '2026-04-17 11:05:23', '2026-04-17 11:05:23'),
(741, 2, 741, 15, 7, 5, 'vendue', '2026-04-17 11:27:16', '2026-04-17 11:27:16'),
(742, 7, 742, 11, 3, 5, 'vendue', '2026-04-17 11:27:34', '2026-04-17 11:27:34'),
(743, 2, 743, 15, 7, 5, 'vendue', '2026-04-17 11:28:44', '2026-04-17 11:28:44'),
(744, 9, 744, 11, 3, 5, 'vendue', '2026-04-17 11:31:03', '2026-04-17 11:31:03'),
(745, 8, 745, 11, 3, 5, 'vendue', '2026-04-17 11:35:38', '2026-04-17 11:35:38'),
(746, 2, 746, 18, 10, 5, 'vendue', '2026-04-17 12:09:39', '2026-04-17 12:09:39'),
(747, 2, 747, 52, 19, 6, 'vendue', '2026-04-17 13:47:32', '2026-04-17 13:47:32'),
(748, 2, 748, 21, 13, 5, 'vendue', '2026-04-17 16:23:52', '2026-04-17 16:23:52'),
(749, 3, 749, 21, 13, 5, 'vendue', '2026-04-17 16:25:16', '2026-04-17 16:25:16'),
(750, 2, 750, 21, 13, 5, 'vendue', '2026-04-17 16:27:12', '2026-04-17 16:27:12'),
(751, 4, 751, 21, 13, 5, 'vendue', '2026-04-17 16:29:34', '2026-04-17 16:29:34'),
(752, 2, 752, 21, 13, 5, 'vendue', '2026-04-17 16:30:35', '2026-04-17 16:30:35'),
(753, 4, 753, 21, 13, 5, 'vendue', '2026-04-17 16:31:47', '2026-04-17 16:31:47'),
(754, 3, 754, 10, 2, 5, 'vendue', '2026-04-17 17:13:41', '2026-04-17 17:13:41'),
(755, 8, 755, 10, 2, 5, 'vendue', '2026-04-17 17:14:44', '2026-04-17 17:14:44'),
(756, 9, 756, 10, 2, 5, 'vendue', '2026-04-17 17:16:59', '2026-04-17 17:16:59'),
(757, 8, 757, 25, 17, 5, 'vendue', '2026-04-17 22:40:38', '2026-04-17 22:40:38'),
(758, 8, 758, 25, 17, 5, 'vendue', '2026-04-17 22:43:07', '2026-04-17 22:43:07'),
(760, 6, 760, 50, 43, 6, 'vendue', '2026-04-18 08:56:34', '2026-04-21 10:04:50'),
(761, 6, 761, 50, 43, 6, 'vendue', '2026-04-18 09:07:17', '2026-04-21 10:04:50'),
(762, 2, 762, 13, 5, 5, 'vendue', '2026-04-18 09:30:57', '2026-04-18 09:30:57'),
(763, 2, 763, 13, 5, 5, 'vendue', '2026-04-18 09:31:49', '2026-04-18 09:31:49'),
(765, 3, 765, 43, 34, 6, 'vendue', '2026-04-18 09:59:38', '2026-04-18 09:59:38'),
(766, 6, 766, 50, 43, 6, 'vendue', '2026-04-18 10:05:12', '2026-04-21 10:04:50'),
(767, 6, 767, 50, 43, 6, 'vendue', '2026-04-18 10:06:39', '2026-04-21 10:04:50'),
(768, 8, 768, 46, 37, 6, 'vendue', '2026-04-18 10:09:53', '2026-04-18 10:09:53'),
(769, 8, 769, 46, 37, 6, 'vendue', '2026-04-18 10:12:05', '2026-04-18 10:12:05'),
(770, 9, 770, 16, 8, 5, 'vendue', '2026-04-18 10:48:15', '2026-04-18 10:48:15'),
(771, 2, 771, 41, 32, 6, 'vendue', '2026-04-18 11:00:41', '2026-04-18 11:00:41'),
(772, 3, 772, 52, 19, 6, 'vendue', '2026-04-18 12:12:43', '2026-04-18 12:12:43'),
(773, 3, 773, 23, 15, 5, 'vendue', '2026-04-18 14:09:21', '2026-04-18 14:09:21'),
(774, 6, 774, 19, 11, 5, 'vendue', '2026-04-18 14:14:40', '2026-04-18 14:14:40'),
(775, 9, 775, 16, 8, 5, 'vendue', '2026-04-18 16:43:54', '2026-04-18 16:43:54'),
(776, 7, 776, 38, 31, 5, 'vendue', '2026-04-18 17:31:20', '2026-04-18 17:31:20'),
(777, 2, 777, 32, 25, 5, 'vendue', '2026-04-18 19:57:37', '2026-04-18 19:57:37'),
(778, 7, 778, 32, 25, 5, 'vendue', '2026-04-18 19:59:26', '2026-04-18 19:59:26'),
(779, 2, 779, 21, 13, 5, 'vendue', '2026-04-19 12:15:06', '2026-04-19 12:15:06'),
(780, 2, 780, 21, 13, 5, 'vendue', '2026-04-19 12:16:02', '2026-04-19 12:16:02'),
(781, 2, 781, 21, 13, 5, 'vendue', '2026-04-19 12:16:30', '2026-04-19 12:16:30'),
(782, 2, 782, 21, 13, 5, 'vendue', '2026-04-19 12:17:52', '2026-04-19 12:17:52'),
(783, 4, 783, 21, 13, 5, 'vendue', '2026-04-19 12:18:59', '2026-04-19 12:18:59'),
(784, 2, 784, 21, 13, 5, 'vendue', '2026-04-19 12:20:05', '2026-04-19 12:20:05'),
(785, 3, 785, 21, 13, 5, 'vendue', '2026-04-19 12:20:54', '2026-04-19 12:20:54'),
(786, 4, 786, 21, 13, 5, 'vendue', '2026-04-19 12:21:43', '2026-04-19 12:21:43'),
(787, 2, 787, 21, 13, 5, 'vendue', '2026-04-19 12:22:22', '2026-04-19 12:22:22'),
(788, 3, 788, 21, 13, 5, 'vendue', '2026-04-19 12:23:23', '2026-04-19 12:23:23'),
(789, 2, 789, 21, 13, 5, 'vendue', '2026-04-19 12:24:13', '2026-04-19 12:24:13'),
(790, 3, 790, 21, 13, 5, 'vendue', '2026-04-19 12:26:01', '2026-04-19 12:26:01'),
(791, 2, 791, 21, 13, 5, 'vendue', '2026-04-19 12:27:11', '2026-04-19 12:27:11'),
(792, 2, 792, 21, 13, 5, 'vendue', '2026-04-19 12:28:02', '2026-04-19 12:28:02'),
(793, 2, 793, 21, 13, 5, 'vendue', '2026-04-19 12:29:20', '2026-04-19 12:29:20'),
(794, 2, 794, 21, 13, 5, 'vendue', '2026-04-19 12:30:21', '2026-04-19 12:30:21'),
(796, 2, 796, 54, 44, 6, 'vendue', '2026-04-20 07:36:42', '2026-04-20 07:36:42'),
(797, 7, 797, 11, 3, 5, 'vendue', '2026-04-20 08:02:46', '2026-04-20 08:02:46'),
(798, 6, 798, 11, 3, 5, 'vendue', '2026-04-20 08:05:16', '2026-04-20 08:05:16'),
(799, 8, 799, 46, 37, 6, 'vendue', '2026-04-20 08:06:46', '2026-04-20 08:06:46'),
(800, 2, 800, 45, 36, 6, 'vendue', '2026-04-20 08:24:24', '2026-04-20 08:24:24'),
(801, 2, 801, 45, 36, 6, 'vendue', '2026-04-20 08:30:51', '2026-04-20 08:30:51'),
(802, 2, 802, 45, 36, 6, 'vendue', '2026-04-20 09:07:00', '2026-04-20 09:07:00'),
(803, 3, 803, 45, 36, 6, 'vendue', '2026-04-20 09:08:12', '2026-04-20 09:08:12'),
(804, 2, 804, 45, 36, 6, 'vendue', '2026-04-20 09:09:29', '2026-04-20 09:09:29'),
(805, 9, 805, 16, 8, 5, 'vendue', '2026-04-20 09:13:14', '2026-04-20 09:13:14'),
(806, 9, 806, 46, 37, 6, 'vendue', '2026-04-20 09:56:02', '2026-04-20 09:56:02'),
(807, 8, 807, 11, 3, 5, 'vendue', '2026-04-20 10:57:08', '2026-04-20 10:57:08'),
(808, 7, 808, 46, 37, 6, 'vendue', '2026-04-20 11:04:14', '2026-04-20 11:04:14'),
(809, 8, 809, 46, 37, 6, 'vendue', '2026-04-20 11:18:18', '2026-04-20 11:18:18'),
(810, 7, 810, 31, 24, 5, 'vendue', '2026-04-20 12:00:07', '2026-04-20 12:00:07'),
(811, 7, 811, 31, 24, 5, 'vendue', '2026-04-20 12:01:49', '2026-04-20 12:01:49'),
(812, 7, 812, 31, 24, 5, 'vendue', '2026-04-20 12:05:31', '2026-04-20 12:05:31'),
(813, 7, 813, 46, 37, 6, 'vendue', '2026-04-20 12:20:06', '2026-04-20 12:20:06'),
(814, 2, 814, 46, 37, 6, 'vendue', '2026-04-20 13:12:44', '2026-04-20 13:12:44'),
(815, 2, 815, 15, 7, 5, 'vendue', '2026-04-20 13:16:56', '2026-04-20 13:16:56'),
(816, 2, 816, 15, 7, 5, 'vendue', '2026-04-20 13:17:28', '2026-04-20 13:17:28'),
(817, 13, 817, 44, 35, 6, 'vendue', '2026-04-20 13:32:27', '2026-04-20 13:32:27'),
(818, 9, 818, 16, 8, 5, 'vendue', '2026-04-20 13:43:25', '2026-04-20 13:43:25'),
(819, 2, 819, 45, 36, 6, 'vendue', '2026-04-20 13:50:15', '2026-04-20 13:50:15'),
(820, 9, 820, 50, 43, 6, 'vendue', '2026-04-20 13:50:33', '2026-04-21 10:04:50'),
(821, 6, 821, 50, 43, 6, 'vendue', '2026-04-20 13:51:08', '2026-04-21 10:04:50'),
(822, 6, 822, 50, 43, 6, 'vendue', '2026-04-20 13:51:54', '2026-04-21 10:04:50'),
(823, 9, 823, 16, 8, 5, 'vendue', '2026-04-20 14:04:26', '2026-04-20 14:04:26'),
(824, 2, 824, 46, 37, 6, 'vendue', '2026-04-20 14:06:40', '2026-04-20 14:06:40'),
(825, 6, 825, 14, 6, 5, 'vendue', '2026-04-20 14:10:36', '2026-04-20 14:10:36'),
(826, 9, 826, 20, 12, 5, 'vendue', '2026-04-20 14:12:38', '2026-04-20 14:12:38'),
(827, 7, 827, 51, 42, 6, 'vendue', '2026-04-20 14:33:03', '2026-04-20 14:33:03'),
(828, 2, 828, 15, 7, 5, 'vendue', '2026-04-20 14:49:49', '2026-04-20 14:49:49'),
(829, 2, 829, 50, 43, 6, 'vendue', '2026-04-20 14:50:35', '2026-04-21 10:04:50'),
(830, 2, 830, 15, 7, 5, 'vendue', '2026-04-20 14:51:40', '2026-04-20 14:51:40'),
(831, 2, 831, 41, 32, 6, 'vendue', '2026-04-20 15:09:12', '2026-04-20 15:09:12'),
(832, 2, 832, 52, 19, 6, 'vendue', '2026-04-20 16:43:45', '2026-04-20 16:43:45'),
(833, 8, 833, 52, 19, 6, 'vendue', '2026-04-20 16:45:11', '2026-04-20 16:45:11'),
(834, 2, 834, 34, 27, 5, 'vendue', '2026-04-20 17:08:45', '2026-04-20 17:08:45'),
(835, 8, 835, 34, 27, 5, 'vendue', '2026-04-20 17:09:39', '2026-04-20 17:09:39'),
(836, 3, 836, 21, 13, 5, 'vendue', '2026-04-20 17:38:01', '2026-04-20 17:38:01'),
(837, 2, 837, 21, 13, 5, 'vendue', '2026-04-20 17:55:51', '2026-04-20 17:55:51'),
(838, 2, 838, 18, 10, 5, 'vendue', '2026-04-21 07:13:20', '2026-04-21 07:13:20'),
(839, 3, 839, 18, 10, 5, 'vendue', '2026-04-21 07:14:14', '2026-04-21 07:14:14'),
(840, 9, 840, 18, 10, 5, 'vendue', '2026-04-21 07:15:19', '2026-04-21 07:15:19'),
(841, 6, 841, 18, 10, 5, 'vendue', '2026-04-21 07:16:18', '2026-04-21 07:16:18'),
(842, 2, 842, 32, 25, 5, 'vendue', '2026-04-21 08:41:09', '2026-04-21 08:41:09'),
(843, 2, 843, 32, 25, 5, 'vendue', '2026-04-21 08:41:34', '2026-04-21 08:41:34');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `agences`
--
ALTER TABLE `agences`
  ADD PRIMARY KEY (`id`),
  ADD KEY `agences_chef_id_foreign` (`chef_id`);

--
-- Index pour la table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Index pour la table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Index pour la table `campagnes`
--
ALTER TABLE `campagnes`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `campagne_actions`
--
ALTER TABLE `campagne_actions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `campagne_actions_campagne_id_foreign` (`campagne_id`),
  ADD KEY `campagne_actions_user_id_foreign` (`user_id`);

--
-- Index pour la table `campagne_agence`
--
ALTER TABLE `campagne_agence`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `campagne_agence_campagne_id_agence_id_unique` (`campagne_id`,`agence_id`),
  ADD KEY `campagne_agence_agence_id_foreign` (`agence_id`);

--
-- Index pour la table `campagne_aide_beneficiaire`
--
ALTER TABLE `campagne_aide_beneficiaire`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `campagne_aide_beneficiaire_campagne_id_user_id_unique` (`campagne_id`,`user_id`),
  ADD KEY `campagne_aide_beneficiaire_user_id_foreign` (`user_id`);

--
-- Index pour la table `campagne_aide_versements`
--
ALTER TABLE `campagne_aide_versements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `campagne_aide_versements_user_id_foreign` (`user_id`),
  ADD KEY `campagne_aide_versements_enregistre_par_foreign` (`enregistre_par`),
  ADD KEY `campagne_aide_versements_campagne_id_user_id_semaine_debut_index` (`campagne_id`,`user_id`,`semaine_debut`);

--
-- Index pour la table `campagne_commercial_contrat`
--
ALTER TABLE `campagne_commercial_contrat`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `campagne_commercial_contrat_campagne_id_user_id_unique` (`campagne_id`,`user_id`),
  ADD KEY `campagne_commercial_contrat_user_id_foreign` (`user_id`);

--
-- Index pour la table `campagne_contrat_articles`
--
ALTER TABLE `campagne_contrat_articles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `campagne_contrat_articles_campagne_id_foreign` (`campagne_id`);

--
-- Index pour la table `campagne_remise_type_carte`
--
ALTER TABLE `campagne_remise_type_carte`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `campagne_remise_type_carte_campagne_id_type_carte_id_unique` (`campagne_id`,`type_carte_id`),
  ADD KEY `campagne_remise_type_carte_type_carte_id_foreign` (`type_carte_id`);

--
-- Index pour la table `clients`
--
ALTER TABLE `clients`
  ADD PRIMARY KEY (`id`),
  ADD KEY `clients_user_id_foreign` (`user_id`),
  ADD KEY `clients_type_carte_id_foreign` (`type_carte_id`);

--
-- Index pour la table `commercial_agence_transferts`
--
ALTER TABLE `commercial_agence_transferts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `commercial_agence_transferts_commercial_user_id_foreign` (`commercial_user_id`),
  ADD KEY `commercial_agence_transferts_admin_user_id_foreign` (`admin_user_id`),
  ADD KEY `commercial_agence_transferts_nouvelle_agence_id_foreign` (`nouvelle_agence_id`),
  ADD KEY `commercial_agence_transferts_profil_agence_avant_foreign` (`profil_agence_avant`),
  ADD KEY `commercial_agence_transferts_profil_agence_apres_foreign` (`profil_agence_apres`);

--
-- Index pour la table `contrat_prestation_reponses`
--
ALTER TABLE `contrat_prestation_reponses`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `contrat_prestation_reponses_campagne_id_user_id_unique` (`campagne_id`,`user_id`),
  ADD KEY `contrat_prestation_reponses_user_id_foreign` (`user_id`);

--
-- Index pour la table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Index pour la table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Index pour la table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Index pour la table `primes`
--
ALTER TABLE `primes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `primes_user_id_foreign` (`user_id`);

--
-- Index pour la table `reclamations`
--
ALTER TABLE `reclamations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `reclamations_client_id_foreign` (`client_id`),
  ADD KEY `reclamations_user_id_foreign` (`user_id`);

--
-- Index pour la table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Index pour la table `telephonique_rapports`
--
ALTER TABLE `telephonique_rapports`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `telephonique_rapports_user_id_date_rapport_unique` (`user_id`,`date_rapport`),
  ADD KEY `telephonique_rapports_campagne_id_date_rapport_index` (`campagne_id`,`date_rapport`);

--
-- Index pour la table `types_cartes`
--
ALTER TABLE `types_cartes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `types_cartes_code_unique` (`code`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `users_agence_id_foreign` (`agence_id`);

--
-- Index pour la table `user_login_logs`
--
ALTER TABLE `user_login_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_login_logs_user_id_logged_in_at_index` (`user_id`,`logged_in_at`);

--
-- Index pour la table `ventes`
--
ALTER TABLE `ventes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ventes_client_id_foreign` (`client_id`),
  ADD KEY `ventes_user_id_foreign` (`user_id`),
  ADD KEY `ventes_agence_id_foreign` (`agence_id`),
  ADD KEY `ventes_type_carte_id_foreign` (`type_carte_id`),
  ADD KEY `ventes_campagne_id_foreign` (`campagne_id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `agences`
--
ALTER TABLE `agences`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT pour la table `campagnes`
--
ALTER TABLE `campagnes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pour la table `campagne_actions`
--
ALTER TABLE `campagne_actions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `campagne_agence`
--
ALTER TABLE `campagne_agence`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=75;

--
-- AUTO_INCREMENT pour la table `campagne_aide_beneficiaire`
--
ALTER TABLE `campagne_aide_beneficiaire`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `campagne_aide_versements`
--
ALTER TABLE `campagne_aide_versements`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `campagne_commercial_contrat`
--
ALTER TABLE `campagne_commercial_contrat`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=106;

--
-- AUTO_INCREMENT pour la table `campagne_contrat_articles`
--
ALTER TABLE `campagne_contrat_articles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT pour la table `campagne_remise_type_carte`
--
ALTER TABLE `campagne_remise_type_carte`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `clients`
--
ALTER TABLE `clients`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=844;

--
-- AUTO_INCREMENT pour la table `commercial_agence_transferts`
--
ALTER TABLE `commercial_agence_transferts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `contrat_prestation_reponses`
--
ALTER TABLE `contrat_prestation_reponses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=106;

--
-- AUTO_INCREMENT pour la table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT pour la table `primes`
--
ALTER TABLE `primes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `reclamations`
--
ALTER TABLE `reclamations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `telephonique_rapports`
--
ALTER TABLE `telephonique_rapports`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT pour la table `types_cartes`
--
ALTER TABLE `types_cartes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;

--
-- AUTO_INCREMENT pour la table `user_login_logs`
--
ALTER TABLE `user_login_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=456;

--
-- AUTO_INCREMENT pour la table `ventes`
--
ALTER TABLE `ventes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=844;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `agences`
--
ALTER TABLE `agences`
  ADD CONSTRAINT `agences_chef_id_foreign` FOREIGN KEY (`chef_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Contraintes pour la table `campagne_actions`
--
ALTER TABLE `campagne_actions`
  ADD CONSTRAINT `campagne_actions_campagne_id_foreign` FOREIGN KEY (`campagne_id`) REFERENCES `campagnes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `campagne_actions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Contraintes pour la table `campagne_agence`
--
ALTER TABLE `campagne_agence`
  ADD CONSTRAINT `campagne_agence_agence_id_foreign` FOREIGN KEY (`agence_id`) REFERENCES `agences` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `campagne_agence_campagne_id_foreign` FOREIGN KEY (`campagne_id`) REFERENCES `campagnes` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `campagne_aide_beneficiaire`
--
ALTER TABLE `campagne_aide_beneficiaire`
  ADD CONSTRAINT `campagne_aide_beneficiaire_campagne_id_foreign` FOREIGN KEY (`campagne_id`) REFERENCES `campagnes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `campagne_aide_beneficiaire_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `campagne_aide_versements`
--
ALTER TABLE `campagne_aide_versements`
  ADD CONSTRAINT `campagne_aide_versements_campagne_id_foreign` FOREIGN KEY (`campagne_id`) REFERENCES `campagnes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `campagne_aide_versements_enregistre_par_foreign` FOREIGN KEY (`enregistre_par`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `campagne_aide_versements_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `campagne_commercial_contrat`
--
ALTER TABLE `campagne_commercial_contrat`
  ADD CONSTRAINT `campagne_commercial_contrat_campagne_id_foreign` FOREIGN KEY (`campagne_id`) REFERENCES `campagnes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `campagne_commercial_contrat_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `campagne_contrat_articles`
--
ALTER TABLE `campagne_contrat_articles`
  ADD CONSTRAINT `campagne_contrat_articles_campagne_id_foreign` FOREIGN KEY (`campagne_id`) REFERENCES `campagnes` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `campagne_remise_type_carte`
--
ALTER TABLE `campagne_remise_type_carte`
  ADD CONSTRAINT `campagne_remise_type_carte_campagne_id_foreign` FOREIGN KEY (`campagne_id`) REFERENCES `campagnes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `campagne_remise_type_carte_type_carte_id_foreign` FOREIGN KEY (`type_carte_id`) REFERENCES `types_cartes` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `clients`
--
ALTER TABLE `clients`
  ADD CONSTRAINT `clients_type_carte_id_foreign` FOREIGN KEY (`type_carte_id`) REFERENCES `types_cartes` (`id`),
  ADD CONSTRAINT `clients_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `commercial_agence_transferts`
--
ALTER TABLE `commercial_agence_transferts`
  ADD CONSTRAINT `commercial_agence_transferts_admin_user_id_foreign` FOREIGN KEY (`admin_user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `commercial_agence_transferts_commercial_user_id_foreign` FOREIGN KEY (`commercial_user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `commercial_agence_transferts_nouvelle_agence_id_foreign` FOREIGN KEY (`nouvelle_agence_id`) REFERENCES `agences` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `commercial_agence_transferts_profil_agence_apres_foreign` FOREIGN KEY (`profil_agence_apres`) REFERENCES `agences` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `commercial_agence_transferts_profil_agence_avant_foreign` FOREIGN KEY (`profil_agence_avant`) REFERENCES `agences` (`id`) ON DELETE SET NULL;

--
-- Contraintes pour la table `contrat_prestation_reponses`
--
ALTER TABLE `contrat_prestation_reponses`
  ADD CONSTRAINT `contrat_prestation_reponses_campagne_id_foreign` FOREIGN KEY (`campagne_id`) REFERENCES `campagnes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `contrat_prestation_reponses_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `primes`
--
ALTER TABLE `primes`
  ADD CONSTRAINT `primes_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `reclamations`
--
ALTER TABLE `reclamations`
  ADD CONSTRAINT `reclamations_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reclamations_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `telephonique_rapports`
--
ALTER TABLE `telephonique_rapports`
  ADD CONSTRAINT `telephonique_rapports_campagne_id_foreign` FOREIGN KEY (`campagne_id`) REFERENCES `campagnes` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `telephonique_rapports_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_agence_id_foreign` FOREIGN KEY (`agence_id`) REFERENCES `agences` (`id`) ON DELETE SET NULL;

--
-- Contraintes pour la table `user_login_logs`
--
ALTER TABLE `user_login_logs`
  ADD CONSTRAINT `user_login_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `ventes`
--
ALTER TABLE `ventes`
  ADD CONSTRAINT `ventes_agence_id_foreign` FOREIGN KEY (`agence_id`) REFERENCES `agences` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `ventes_campagne_id_foreign` FOREIGN KEY (`campagne_id`) REFERENCES `campagnes` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `ventes_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `ventes_type_carte_id_foreign` FOREIGN KEY (`type_carte_id`) REFERENCES `types_cartes` (`id`),
  ADD CONSTRAINT `ventes_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
