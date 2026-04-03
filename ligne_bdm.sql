-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:3306
-- Généré le : ven. 03 avr. 2026 à 17:10
-- Version du serveur : 10.11.16-MariaDB-cll-lve
-- Version de PHP : 8.4.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `gdamali_bdm_db`
--

-- --------------------------------------------------------

--
-- Structure de la table `agences`
--

CREATE TABLE `agences` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nom` varchar(255) NOT NULL,
  `adresse` varchar(255) DEFAULT NULL,
  `chef_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `agences`
--

INSERT INTO `agences` (`id`, `nom`, `adresse`, `chef_id`, `created_at`, `updated_at`) VALUES
(2, 'Niamana', NULL, NULL, '2026-03-30 10:17:27', '2026-03-30 10:48:47'),
(3, 'PME/PMI', NULL, NULL, '2026-03-30 10:17:27', '2026-03-30 10:48:47'),
(4, 'Centre d\'appel', NULL, NULL, '2026-03-30 10:17:27', '2026-03-30 10:48:47'),
(5, 'Sotuba', NULL, NULL, '2026-03-30 10:17:27', '2026-03-30 10:48:47'),
(6, 'Sogoniko', NULL, NULL, '2026-03-30 10:17:27', '2026-03-30 10:48:47'),
(7, 'Korofina', NULL, NULL, '2026-03-30 10:17:27', '2026-03-30 10:48:47'),
(8, 'Baco Djicoroni', NULL, NULL, '2026-03-30 10:17:27', '2026-03-30 10:48:47'),
(9, 'Dibida', NULL, NULL, '2026-03-30 10:17:27', '2026-03-30 10:48:47'),
(10, 'AP 2', NULL, NULL, '2026-03-30 10:17:27', '2026-03-30 10:48:47'),
(11, 'N\'Golonina', NULL, NULL, '2026-03-30 10:17:27', '2026-03-30 10:48:47'),
(12, 'Kalaban coura', NULL, NULL, '2026-03-30 10:17:27', '2026-03-30 10:48:47'),
(13, 'Maison du Hadj', NULL, NULL, '2026-03-30 10:17:27', '2026-03-30 10:48:47'),
(15, 'Yirimadio', NULL, NULL, '2026-03-30 10:17:27', '2026-03-30 10:48:47'),
(16, 'Futura', NULL, NULL, '2026-03-30 10:17:27', '2026-03-30 10:48:47'),
(17, 'Djicoroni para', NULL, NULL, '2026-03-30 10:17:27', '2026-03-30 10:48:47'),
(18, 'Dramane DIAKITE', NULL, NULL, '2026-03-30 10:17:27', '2026-03-30 10:48:47'),
(19, 'Kabala', NULL, NULL, '2026-03-30 10:17:27', '2026-03-30 10:48:47'),
(20, 'Kati', NULL, NULL, '2026-03-30 10:17:27', '2026-03-30 10:48:47'),
(21, 'AP 1', NULL, NULL, '2026-03-30 10:17:27', '2026-03-30 10:48:47'),
(22, 'Ségou 2', NULL, NULL, '2026-03-30 10:17:27', '2026-03-30 10:48:47'),
(23, 'Ségou 1', NULL, NULL, '2026-03-30 10:17:27', '2026-03-30 10:48:47'),
(24, 'San', NULL, NULL, '2026-03-30 10:17:27', '2026-03-30 10:48:47'),
(25, 'Mopti', NULL, NULL, '2026-03-30 10:17:27', '2026-03-30 10:48:47'),
(26, 'Koulikoro', NULL, NULL, '2026-03-30 10:17:27', '2026-03-30 10:48:47'),
(27, 'Dioila', NULL, NULL, '2026-03-30 10:17:27', '2026-03-30 10:48:47'),
(28, 'Sikasso', NULL, NULL, '2026-03-30 10:17:27', '2026-03-30 10:48:47'),
(29, 'Tombouctou', NULL, NULL, '2026-03-30 10:17:27', '2026-03-30 10:48:47'),
(30, 'Kita', NULL, NULL, '2026-03-30 10:17:27', '2026-03-30 10:48:47'),
(31, 'Kayes 1', NULL, NULL, '2026-03-30 10:17:27', '2026-03-30 10:48:47');

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
('gda-money-cache-blaise|127.0.0.1', 'i:1;', 1774614342),
('gda-money-cache-blaise|127.0.0.1:timer', 'i:1774614342;', 1774614342),
('gda-money-cache-cissehamadoun23@gmail.com|127.0.0.1', 'i:1;', 1774457264),
('gda-money-cache-cissehamadoun23@gmail.com|127.0.0.1:timer', 'i:1774457264;', 1774457264),
('gda-money-cache-commercial@bdm.com|127.0.0.1', 'i:2;', 1774450957),
('gda-money-cache-commercial@bdm.com|127.0.0.1:timer', 'i:1774450957;', 1774450957),
('laravel-cache-cissehamadoun23@gmail.com|127.0.0.1', 'i:1;', 1774368743),
('laravel-cache-cissehamadoun23@gmail.com|127.0.0.1:timer', 'i:1774368743;', 1774368743);

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
(5, 'Campagne Avril 2026', '2026-03-31', '2026-04-30', 25000, NULL, 0, 1, 5000, 3000, 2000, 1, 1, 5000, 2000, 3000, 'Yaya H DIALLO', 'Bamako', NULL, '2026-03-31 09:12:49', 1, 'en_cours', 1, '2026-03-31 09:12:49', '2026-03-31 09:12:49');

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
(47, 5, 27, '2026-03-31 09:12:49', '2026-03-31 09:12:49'),
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
(59, 5, 40, '2026-03-31 11:35:19', '2026-03-31 11:35:19');

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
(25, 5, 8, 'Article 4', 'En contrepartie des prestations fournies, la Prestataire percevra de GDA un émolument forfaitaire de 50 000 FCFA TTC pour la durée totale de la mission.\r\no	Forfait Communication de : 2 000 Francs CFA\r\no	Forfait Deplacement de : 3 000 Francs CFA\r\no	Une prime de performance hebdomadaire de 25 000 FCFA sera attribuée au meilleur vendeur de la semaine, sur la base des rapports et résultats transmis.\r\n\r\nLe paiement interviendra en une seule fois à la fin de la campagne, après validation du rapport final et contrôle des résultats.', '2026-03-31 10:08:35', '2026-03-31 10:08:35');

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
(169, 3, 'Mohamed hady', 'Sow', '74000467', NULL, NULL, 'vendue', NULL, 23, '2026-04-03 14:44:31', '2026-04-03 14:44:31');

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
(47, 5, 27, 'accepte', '2026-03-31 09:57:52', '2026-03-31 09:12:49', '2026-03-31 09:57:52'),
(48, 5, 28, 'accepte', '2026-04-02 09:12:55', '2026-03-31 09:12:49', '2026-04-02 09:12:55'),
(49, 5, 29, 'accepte', '2026-03-31 11:25:45', '2026-03-31 09:12:49', '2026-03-31 11:25:45'),
(50, 5, 30, 'accepte', '2026-03-31 13:33:08', '2026-03-31 09:12:49', '2026-03-31 13:33:08'),
(51, 5, 31, 'accepte', '2026-03-31 16:29:32', '2026-03-31 09:12:49', '2026-03-31 16:29:32'),
(52, 5, 32, 'en_attente', NULL, '2026-03-31 09:12:49', '2026-03-31 09:12:49'),
(53, 5, 33, 'accepte', '2026-03-31 12:45:17', '2026-03-31 09:12:49', '2026-03-31 12:45:17'),
(54, 5, 34, 'accepte', '2026-03-31 11:11:36', '2026-03-31 09:12:49', '2026-03-31 11:11:36'),
(55, 5, 35, 'en_attente', NULL, '2026-03-31 09:12:49', '2026-03-31 09:12:49'),
(56, 5, 36, 'en_attente', NULL, '2026-03-31 09:12:49', '2026-03-31 09:12:49'),
(57, 5, 37, 'en_attente', NULL, '2026-03-31 09:12:49', '2026-03-31 09:12:49'),
(58, 5, 38, 'accepte', '2026-03-31 20:14:46', '2026-03-31 09:12:49', '2026-03-31 20:14:46'),
(59, 5, 40, 'accepte', '2026-04-02 06:33:38', '2026-03-31 11:35:19', '2026-04-02 06:33:38');

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
(13, '2025_03_23_100000_add_prenom_and_nullable_email_to_users', 2),
(14, '2025_03_23_110000_enhance_campagnes_table', 3),
(15, '2025_03_24_000000_create_types_cartes_and_migrate', 4),
(16, '2025_03_24_120000_drop_libelle_ordre_from_types_cartes', 5),
(17, '2026_02_10_000001_add_remise_aide_campagne_and_users_actif', 6),
(18, '2026_03_25_000000_add_remise_types_cartes_to_campagnes', 7),
(19, '2026_03_27_120000_add_campagne_id_to_ventes_table', 8),
(20, '2026_03_30_120000_campagne_prime_meilleur_vendeur_only', 9),
(21, '2026_03_31_100000_users_role_direction_replace_chef', 10),
(22, '2026_03_31_110000_clear_agences_chef_id', 11),
(23, '2026_03_31_200000_contrats_prestation_aides_versements', 12),
(24, '2026_03_31_210000_campagne_contrat_articles', 13);

-- --------------------------------------------------------

--
-- Structure de la table `mouvements_stock`
--

CREATE TABLE `mouvements_stock` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `type_carte_id` bigint(20) UNSIGNED NOT NULL,
  `agence_id` bigint(20) UNSIGNED NOT NULL,
  `quantite` int(11) NOT NULL,
  `type_mouvement` enum('vente','entree','ajustement') NOT NULL,
  `vente_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `mouvements_stock`
--

INSERT INTO `mouvements_stock` (`id`, `type_carte_id`, `agence_id`, `quantite`, `type_mouvement`, `vente_id`, `created_at`, `updated_at`) VALUES
(1, 2, 2, -50, 'ajustement', NULL, '2026-03-30 15:41:15', '2026-03-30 15:41:15');

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
('0Q92ktwf4SieyG875sAvadudRGbIRcCXRKU247Ks', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiODExMmgzV3JaUjBUVzJBNmRuN213Y0dTZU9TMlRuZkszWkMxRVhkeSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fX0=', 1774950246),
('5ONtd4PbH0J9pbE769OFaddvwsoUXJyykl3b9Jfr', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoib1lCRGY3TlRGakN2VTdQakNoVEhwN2pqSDVRd3g2YkcyQ1pZZmppRCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzg6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9zaXRlLndlYm1hbmlmZXN0IjtzOjU6InJvdXRlIjtzOjEyOiJwd2EubWFuaWZlc3QiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1774950236),
('8HYuzyMB8gLuFMlwryIhz63TGNzk852ckZVfc3NU', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiT3dQN3FjU28yZjdVZ25kdkdQQ3lkT2M2STAyM0dzdWtieVUwSTNFeiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzg6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9zaXRlLndlYm1hbmlmZXN0IjtzOjU6InJvdXRlIjtzOjEyOiJwd2EubWFuaWZlc3QiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1774950143),
('8IQFoN9vmHqAPm6pb69B7ZV8MZ9GLhi7tOSSQEqU', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoibklHd2RidVpzZE5mbkJaWkJSdWE4OEl0R0JwT01rbkh4UG1WR0JCUyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzg6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9zaXRlLndlYm1hbmlmZXN0IjtzOjU6InJvdXRlIjtzOjEyOiJwd2EubWFuaWZlc3QiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1774950075),
('8zk1kPwa1RUvQX0PNY8GpYtxaT6UL3ZZHVanHJvV', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiU1NNVmNzT2JvZGRjYkw3QzQwOGxYcWtGM09yV0lrR2JiRHVIUWFWMyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzg6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9zaXRlLndlYm1hbmlmZXN0IjtzOjU6InJvdXRlIjtzOjEyOiJwd2EubWFuaWZlc3QiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1774949695),
('AWCb1G0yxU8QJs202kxJYpWqI2mFYmSn3FeiqYHP', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiQWt2am41SjAxTEZvd0pScjNlVzl1TmRqTE11bmJGb2pCT29KUThMcSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzg6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9zaXRlLndlYm1hbmlmZXN0IjtzOjU6InJvdXRlIjtzOjEyOiJwd2EubWFuaWZlc3QiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1774950196),
('B611Sqdk2t5oRr3leNA0vyx7RYdSnRzV0tqJRKw2', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiM1Q3NktUSE9YSkRrZ004VmV3RjBCbFA3ODFzbUw3T01aYTN1bWNCNyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzg6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9zaXRlLndlYm1hbmlmZXN0IjtzOjU6InJvdXRlIjtzOjEyOiJwd2EubWFuaWZlc3QiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1774949703),
('cQUPfjKd4KuXX1jPcZW4Wqs16EnuhAIx6pRN5f3I', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiTnRzZ0lxb2tXYlIxSjRmcmpyWXB0dVQ0SG1PbnhobE5yaFI3QXlwNSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzg6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9zaXRlLndlYm1hbmlmZXN0IjtzOjU6InJvdXRlIjtzOjEyOiJwd2EubWFuaWZlc3QiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1774949758),
('DfNCsTF7RG9EDTzGMCdrSFEN0Z4OLPNqTsP6kgfo', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiSHlHUVZJS1FrcXJRQzFsWmVBcFlXNVBhVlg5b3NIeDhsOVZXVlRSOSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzg6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9zaXRlLndlYm1hbmlmZXN0IjtzOjU6InJvdXRlIjtzOjEyOiJwd2EubWFuaWZlc3QiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1774949716),
('dnlMtRu4krQj2Sm032EopqbPzVCnF9gWPPEvNEAr', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoieDV5ckd3WDI4TjAwZTF0NThINTlXaXZma2l0YTBiaUlqQ2FiOU9zRyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzg6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9zaXRlLndlYm1hbmlmZXN0IjtzOjU6InJvdXRlIjtzOjEyOiJwd2EubWFuaWZlc3QiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1774949815),
('FjRfVen78ZRt9tIoCUI67yVRqyje2huRdTz6Krfq', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiNWRLZ2ZjQkRqM2tpMzgybUNrT3c0eXI3bzlDNlZqWE5KalZpbTJWeiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzg6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9zaXRlLndlYm1hbmlmZXN0IjtzOjU6InJvdXRlIjtzOjEyOiJwd2EubWFuaWZlc3QiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1774949297),
('fYn5Kopq3IsmDmwneuh5YHlifPtvZU58UzeHU35E', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoidGRuc085Um5ybzBsWWFHb0ZmQkxEaFlzWERHZlNNSTFhMzdpVzRFZCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzg6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9zaXRlLndlYm1hbmlmZXN0IjtzOjU6InJvdXRlIjtzOjEyOiJwd2EubWFuaWZlc3QiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1774950193),
('hPg7yuu7adDvoxCcjneJsSPaj7hsvFh4sQPHa2A6', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiZ1BuUmlSYkgzQlcwaE1WRjRtR2NwRmVSWGVvbnZsam50OWd4RWs2VCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzg6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9zaXRlLndlYm1hbmlmZXN0IjtzOjU6InJvdXRlIjtzOjEyOiJwd2EubWFuaWZlc3QiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1774950065),
('JyQZosZuxJd5vfVyjzYGeLnnumFpqOGOkUoXTyhc', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiamRQQW5ncmVLdEZUaVlhcThYUk5PWlgwQ2tXQzIwTnJpcjlvaFU1NiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzg6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9zaXRlLndlYm1hbmlmZXN0IjtzOjU6InJvdXRlIjtzOjEyOiJwd2EubWFuaWZlc3QiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1774949807),
('KeG1LUpqLJqeLjI1wmOpcT2FlWDSYbVMo7YaVklA', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiaThTTkxuMGw0cUVrQU5ZRkZlREJHQkVGZ2ZiWk1MNklqVjJjZXQ5VCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzg6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9zaXRlLndlYm1hbmlmZXN0IjtzOjU6InJvdXRlIjtzOjEyOiJwd2EubWFuaWZlc3QiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1774949283),
('NfnZpNIw3qCJhwtjwth3A6VMVGCItd792eRfXZ1D', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiTmpMUFpLNTR2ZTVuNmRBc0xMMFhTaFNRYW1nR0lpaGFzWFNKRmtUUyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzg6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9zaXRlLndlYm1hbmlmZXN0IjtzOjU6InJvdXRlIjtzOjEyOiJwd2EubWFuaWZlc3QiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1774950080),
('NukPd7m1kKOdj74Ro8k2Hv92TX5N0OA805WzoX6Z', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiUmdKYzc3UFFXMnZXaUdEMmlvcEh0NWlLeUpqYlJqZWNjMW5rR3dqRyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzg6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9zaXRlLndlYm1hbmlmZXN0IjtzOjU6InJvdXRlIjtzOjEyOiJwd2EubWFuaWZlc3QiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1774949722),
('oiehhziDx8faLsmYbe18V1iO643vv3i5jQswZS7s', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoieDdRVGRMaTkxOHIzWGtjMkhid2pmUXRrR0E0T0lPMWp0UWxsNW9DaCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzg6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9zaXRlLndlYm1hbmlmZXN0IjtzOjU6InJvdXRlIjtzOjEyOiJwd2EubWFuaWZlc3QiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1774949227),
('qjcBfVknT3Jw0Z873jcNQOvzjlZnwVsiYjhK213F', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoid0E3N1dTMmZBY1UwelhVSDJDa1JkQnRZSm92d1V2cE5KaU5iOXA4VyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzg6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9zaXRlLndlYm1hbmlmZXN0IjtzOjU6InJvdXRlIjtzOjEyOiJwd2EubWFuaWZlc3QiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1774949791),
('skPRFKcnB2NH5wv9D0c1668DZXsUSxyUrDAJ9PDl', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoic0lGMlgxSlJEdThCaU5aeVhOSWNvVW5VbDNod2N5Njk1Q1BzdnpCOCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzg6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9zaXRlLndlYm1hbmlmZXN0IjtzOjU6InJvdXRlIjtzOjEyOiJwd2EubWFuaWZlc3QiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1774949761),
('TVBQgBX4NGEokj6DorwB1sf5XHH5ObowokhdIvIN', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiMWN2SGN0NWE1MmVkT2ZFNGxWTENQTlpTQzI0bHZtQlRuU0ZibDBRZiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzg6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9zaXRlLndlYm1hbmlmZXN0IjtzOjU6InJvdXRlIjtzOjEyOiJwd2EubWFuaWZlc3QiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1774950141),
('U1183nqYcGbqlanWEYvvW5HrvJCbQ12mSWUhWmQJ', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoicjN4ZVhaT05Jc3NONWNRZlBYUmxzM1dFVHBORjVxcGZ2ZFhlWkhxMiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzg6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9zaXRlLndlYm1hbmlmZXN0IjtzOjU6InJvdXRlIjtzOjEyOiJwd2EubWFuaWZlc3QiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1774949799),
('V4R1a6KZXa34RR5U9c2d7ayqNGvxLglO4cnoOLPZ', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiTmNxazl0UkhHZ0puNjlBcG5VTWZ5WVh5TFZJbDExdDlPQ3JSWHBzYSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzg6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9zaXRlLndlYm1hbmlmZXN0IjtzOjU6InJvdXRlIjtzOjEyOiJwd2EubWFuaWZlc3QiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1774950247),
('VJmTEF99Hw4DU8W7DZnFQAGctTTRDqpJ7CcsNqUy', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiWUxwSTlDYmlVWGY1cE9kRkNFdzdXQ2dDSjd0OW5SUDhOaExKa2t2TSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzg6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9zaXRlLndlYm1hbmlmZXN0IjtzOjU6InJvdXRlIjtzOjEyOiJwd2EubWFuaWZlc3QiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1774949291),
('wL4tQyRqQygE80kVp8Bcb39H3GT9uWsZRpH2sI8C', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiNEhsN2IyaWdhZlZWbXFmN05SMloyNDMyWEhVTGJJUzZxU0xDeERRNCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzg6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9zaXRlLndlYm1hbmlmZXN0IjtzOjU6InJvdXRlIjtzOjEyOiJwd2EubWFuaWZlc3QiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1774949726),
('WMslvkELMD2QYuAbXZWWxlUn7KpRivrQsEsnqpQG', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiQzRZVjFHSEV2RjY4WWdnbzRIN21aOXVXVnExaGFQNGIyY0dzbGIwTSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzg6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9zaXRlLndlYm1hbmlmZXN0IjtzOjU6InJvdXRlIjtzOjEyOiJwd2EubWFuaWZlc3QiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1774950188),
('wsWlE7mS8bTlkV2esHuSPsV19ZG5YeGpyk79lYug', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiS0c4R2hzZFF3SWNxc2Z6U0hmRXpUUEk2ODF5NzNtVXVHUDBYM3AwdiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzg6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9zaXRlLndlYm1hbmlmZXN0IjtzOjU6InJvdXRlIjtzOjEyOiJwd2EubWFuaWZlc3QiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1774950133),
('x96FXiVtWK33PaMZu6rQRUIgLNYHPcdP1LO9VCR4', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiTmtEamZvOWtMZGEycVhjWUEwQUJJT0UxOExSSXB3OW5ZY09KbURrcCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzg6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9zaXRlLndlYm1hbmlmZXN0IjtzOjU6InJvdXRlIjtzOjEyOiJwd2EubWFuaWZlc3QiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1774949810),
('Z1KabSg3zCIt3tnnmEda3BeohvtW6IQYZEUaskJU', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiYkdyOEhtc1lZTVQzenF1OVFFVUZLanF6QXZTV1FyZjlkMWJ2ZjFWUSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzg6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9zaXRlLndlYm1hbmlmZXN0IjtzOjU6InJvdXRlIjtzOjEyOiJwd2EubWFuaWZlc3QiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1774950059);

-- --------------------------------------------------------

--
-- Structure de la table `stocks`
--

CREATE TABLE `stocks` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `type_carte_id` bigint(20) UNSIGNED NOT NULL,
  `quantite` int(11) NOT NULL DEFAULT 0,
  `agence_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `stocks`
--

INSERT INTO `stocks` (`id`, `type_carte_id`, `quantite`, `agence_id`, `created_at`, `updated_at`) VALUES
(2, 2, 0, 2, '2026-03-30 14:37:12', '2026-03-30 15:43:36'),
(3, 4, 0, 2, '2026-03-30 14:37:12', '2026-03-30 15:43:36'),
(4, 3, 0, 2, '2026-03-30 14:37:12', '2026-03-30 15:43:36'),
(5, 2, 0, 3, '2026-03-30 14:37:12', '2026-03-30 15:43:36'),
(6, 4, 0, 3, '2026-03-30 14:37:12', '2026-03-30 15:43:36'),
(7, 3, 0, 3, '2026-03-30 14:37:12', '2026-03-30 15:43:36'),
(8, 2, 0, 4, '2026-03-30 14:37:12', '2026-03-30 15:43:36'),
(9, 4, 0, 4, '2026-03-30 14:37:12', '2026-03-30 15:43:36'),
(10, 3, 0, 4, '2026-03-30 14:37:12', '2026-03-30 15:43:36'),
(11, 2, 0, 5, '2026-03-30 14:37:12', '2026-03-30 15:43:36'),
(12, 4, 0, 5, '2026-03-30 14:37:12', '2026-03-30 15:43:36'),
(13, 3, 0, 5, '2026-03-30 14:37:12', '2026-03-30 15:43:36'),
(14, 2, 0, 6, '2026-03-30 14:37:12', '2026-03-30 15:43:36'),
(15, 4, 0, 6, '2026-03-30 14:37:12', '2026-03-30 15:43:36'),
(16, 3, 0, 6, '2026-03-30 14:37:12', '2026-03-30 15:43:36'),
(17, 2, 0, 7, '2026-03-30 14:37:12', '2026-03-30 15:43:36'),
(18, 4, 0, 7, '2026-03-30 14:37:12', '2026-03-30 15:43:36'),
(19, 3, 0, 7, '2026-03-30 14:37:12', '2026-03-30 15:43:36'),
(20, 2, 0, 8, '2026-03-30 14:37:12', '2026-03-30 15:43:36'),
(21, 4, 0, 8, '2026-03-30 14:37:12', '2026-03-30 15:43:36'),
(22, 3, 0, 8, '2026-03-30 14:37:12', '2026-03-30 15:43:36'),
(23, 2, 0, 9, '2026-03-30 14:37:12', '2026-03-30 15:43:36'),
(24, 4, 0, 9, '2026-03-30 14:37:12', '2026-03-30 15:43:36'),
(25, 3, 0, 9, '2026-03-30 14:37:12', '2026-03-30 15:43:36'),
(26, 2, 0, 10, '2026-03-30 14:37:12', '2026-03-30 15:43:36'),
(27, 4, 0, 10, '2026-03-30 14:37:12', '2026-03-30 15:43:36'),
(28, 3, 0, 10, '2026-03-30 14:37:12', '2026-03-30 15:43:36'),
(29, 2, 0, 11, '2026-03-30 14:37:12', '2026-03-30 15:43:36'),
(30, 4, 0, 11, '2026-03-30 14:37:12', '2026-03-30 15:43:36'),
(31, 3, 0, 11, '2026-03-30 14:37:12', '2026-03-30 15:43:36'),
(32, 2, 0, 12, '2026-03-30 14:37:12', '2026-03-30 15:43:36'),
(33, 4, 0, 12, '2026-03-30 14:37:12', '2026-03-30 15:43:36'),
(34, 3, 0, 12, '2026-03-30 14:37:12', '2026-03-30 15:43:36'),
(35, 2, 0, 13, '2026-03-30 14:37:12', '2026-03-30 15:43:36'),
(36, 4, 0, 13, '2026-03-30 14:37:12', '2026-03-30 15:43:36'),
(37, 3, 0, 13, '2026-03-30 14:37:12', '2026-03-30 15:43:36'),
(41, 2, 0, 15, '2026-03-30 14:37:12', '2026-03-30 15:43:36'),
(42, 4, 0, 15, '2026-03-30 14:37:12', '2026-03-30 15:43:36'),
(43, 3, 0, 15, '2026-03-30 14:37:12', '2026-03-30 15:43:36'),
(44, 2, 0, 16, '2026-03-30 14:37:12', '2026-03-30 15:43:36'),
(45, 4, 0, 16, '2026-03-30 14:37:12', '2026-03-30 15:43:36'),
(46, 3, 0, 16, '2026-03-30 14:37:12', '2026-03-30 15:43:36'),
(47, 2, 0, 17, '2026-03-30 14:37:12', '2026-03-30 15:43:36'),
(48, 4, 0, 17, '2026-03-30 14:37:12', '2026-03-30 15:43:36'),
(49, 3, 0, 17, '2026-03-30 14:37:12', '2026-03-30 15:43:36'),
(50, 2, 0, 18, '2026-03-30 14:37:12', '2026-03-30 15:43:36'),
(51, 4, 0, 18, '2026-03-30 14:37:12', '2026-03-30 15:43:36'),
(52, 3, 0, 18, '2026-03-30 14:37:12', '2026-03-30 15:43:36'),
(53, 2, 0, 19, '2026-03-30 14:37:12', '2026-03-30 15:43:36'),
(54, 4, 0, 19, '2026-03-30 14:37:12', '2026-03-30 15:43:36'),
(55, 3, 0, 19, '2026-03-30 14:37:12', '2026-03-30 15:43:36'),
(56, 2, 0, 21, '2026-03-30 14:37:12', '2026-03-30 15:43:36'),
(57, 4, 0, 21, '2026-03-30 14:37:12', '2026-03-30 15:43:36'),
(58, 3, 0, 21, '2026-03-30 14:37:12', '2026-03-30 15:43:36'),
(59, 2, 0, 22, '2026-03-30 14:37:12', '2026-03-30 15:43:36'),
(60, 4, 0, 22, '2026-03-30 14:37:12', '2026-03-30 15:43:36'),
(61, 3, 0, 22, '2026-03-30 14:37:12', '2026-03-30 15:43:36'),
(62, 2, 0, 23, '2026-03-30 14:37:12', '2026-03-30 15:43:36'),
(63, 4, 0, 23, '2026-03-30 14:37:12', '2026-03-30 15:43:36'),
(64, 3, 0, 23, '2026-03-30 14:37:12', '2026-03-30 15:43:36'),
(65, 2, 0, 24, '2026-03-30 14:37:12', '2026-03-30 15:43:36'),
(66, 4, 0, 24, '2026-03-30 14:37:12', '2026-03-30 15:43:36'),
(67, 3, 0, 24, '2026-03-30 14:37:12', '2026-03-30 15:43:36'),
(68, 2, 0, 25, '2026-03-30 14:37:12', '2026-03-30 15:43:36'),
(69, 4, 0, 25, '2026-03-30 14:37:12', '2026-03-30 15:43:36'),
(70, 3, 0, 25, '2026-03-30 14:37:12', '2026-03-30 15:43:36'),
(71, 2, 0, 26, '2026-03-30 14:37:12', '2026-03-30 15:43:36'),
(72, 4, 0, 26, '2026-03-30 14:37:12', '2026-03-30 15:43:36'),
(73, 3, 0, 26, '2026-03-30 14:37:12', '2026-03-30 15:43:36'),
(74, 2, 0, 27, '2026-03-30 14:37:12', '2026-03-30 15:43:36'),
(75, 4, 0, 27, '2026-03-30 14:37:12', '2026-03-30 15:43:36'),
(76, 3, 0, 27, '2026-03-30 14:37:12', '2026-03-30 15:43:36'),
(77, 2, 0, 28, '2026-03-30 14:37:12', '2026-03-30 15:43:36'),
(78, 4, 0, 28, '2026-03-30 14:37:12', '2026-03-30 15:43:36'),
(79, 3, 0, 28, '2026-03-30 14:37:12', '2026-03-30 15:43:36'),
(80, 2, 0, 29, '2026-03-30 14:37:12', '2026-03-30 15:43:36'),
(81, 4, 0, 29, '2026-03-30 14:37:12', '2026-03-30 15:43:36'),
(82, 3, 0, 29, '2026-03-30 14:37:12', '2026-03-30 15:43:36'),
(83, 2, 0, 30, '2026-03-30 14:37:12', '2026-03-30 15:43:36'),
(84, 4, 0, 30, '2026-03-30 14:37:12', '2026-03-30 15:43:36'),
(85, 3, 0, 30, '2026-03-30 14:37:12', '2026-03-30 15:43:36'),
(86, 2, 0, 31, '2026-03-30 14:37:12', '2026-03-30 15:43:36'),
(87, 4, 0, 31, '2026-03-30 14:37:12', '2026-03-30 15:43:36'),
(88, 3, 0, 31, '2026-03-30 14:37:12', '2026-03-30 15:43:36');

-- --------------------------------------------------------

--
-- Structure de la table `types_cartes`
--

CREATE TABLE `types_cartes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(50) NOT NULL,
  `prix` bigint(20) UNSIGNED NOT NULL DEFAULT 0,
  `actif` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `types_cartes`
--

INSERT INTO `types_cartes` (`id`, `code`, `prix`, `actif`, `created_at`, `updated_at`) VALUES
(2, 'ADAN', 10000, 1, '2026-03-30 10:32:01', '2026-03-30 10:32:01'),
(3, 'LAAFIA', 15000, 1, '2026-03-30 10:32:32', '2026-03-30 10:32:32'),
(4, 'ELITE', 50000, 1, '2026-03-30 10:32:54', '2026-03-30 10:32:54'),
(6, 'GIM_UEMOA', 0, 1, '2026-04-01 11:38:19', '2026-04-01 11:38:19'),
(7, 'CAURIS_CLASSIQUE', 0, 1, '2026-04-01 11:38:50', '2026-04-01 11:38:50'),
(8, 'CAURIS_EPARGNE', 0, 1, '2026-04-01 11:39:20', '2026-04-01 11:39:20'),
(9, 'VISA_OPTMUM', 0, 1, '2026-04-01 11:39:44', '2026-04-01 11:39:44'),
(10, 'CUARIS_MANSA', 0, 1, '2026-04-01 11:40:14', '2026-04-01 11:40:14'),
(11, 'VISA_GOLD', 0, 1, '2026-04-01 11:40:36', '2026-04-01 11:40:36');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `prenom` varchar(100) DEFAULT NULL,
  `telephone` varchar(20) DEFAULT NULL,
  `role` enum('admin','commercial','direction') NOT NULL DEFAULT 'commercial',
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
(1, 'Sylla', NULL, NULL, 'admin', NULL, 1, NULL, NULL, NULL, NULL, '$2y$12$YiS76t9BDw/zFkK6HYShVu0his8C9ALMm.j./DDdtH63cETULU5dy', NULL, '2026-03-27 09:59:32', '2026-03-27 09:59:32'),
(2, 'Dante', NULL, NULL, 'admin', NULL, 1, NULL, NULL, NULL, NULL, '$2y$12$8qsGql.9kVC4pSs8EGtSNuZXcLYlAmySGH7880ItYTD0uifV4YXiK', NULL, '2026-03-27 09:59:33', '2026-03-27 09:59:33'),
(3, 'Koita', NULL, NULL, 'admin', NULL, 1, NULL, NULL, NULL, NULL, '$2y$12$4QRTUMjDKWSljti4dD42d.eLApbNUEf6bhmTpQjcsIzsW.l93yvPy', NULL, '2026-03-27 09:59:33', '2026-03-27 09:59:33'),
(4, 'Sacko', NULL, NULL, 'admin', NULL, 1, NULL, NULL, NULL, NULL, '$2y$12$aw83Qx9GfGUnemobyRmRQ.j065Kw9PJNO1As11XZQ9JmKiUPt8ByG', NULL, '2026-03-27 09:59:33', '2026-03-27 09:59:33'),
(5, 'Cisse', NULL, NULL, 'admin', NULL, 1, NULL, NULL, NULL, NULL, '$2y$12$OYAqF9T9cIUBYq.PcNsncumRQALbYHtx60bQJKBgBA2HdS6mahZJe', NULL, '2026-03-27 09:59:34', '2026-03-27 09:59:34'),
(6, 'Yaya', NULL, NULL, 'admin', NULL, 1, NULL, NULL, NULL, NULL, '$2y$12$p62ee/iwjYCquB0q8YQEE.pQRPGRatQE7ChofMf6F/E7PlkuUlLKy', NULL, '2026-03-27 09:59:34', '2026-03-27 09:59:34'),
(10, 'THERA', 'Mariam', '74082712', 'commercial', 2, 1, NULL, NULL, NULL, NULL, '$2y$12$KkRi0H8lXlat6F4jsLSEkuVL2PD8X3JUxIndQQ47ZmO1CliKC0.Lu', 'lkriJcVImx6q2VnWZBzY4vYmxCKkmlWusryDCU4ngVAHv8Rj6JJ2EvlLcasI', '2026-03-30 14:37:06', '2026-04-03 15:09:58'),
(11, 'NIAMBELE', 'Aissata N', '66904040', 'commercial', 3, 1, NULL, NULL, NULL, NULL, '$2y$12$H5xgOXlqkQ23Poh53doyT.X3Kuw.h5tToEpZVogAbAjUq0WX8xshW', NULL, '2026-03-30 14:37:06', '2026-04-03 15:09:58'),
(13, 'DIAKITE', 'Nagnouma TOURE', '79053641', 'commercial', 5, 1, NULL, NULL, NULL, NULL, '$2y$12$CcXSjx6PXRoTEAajj8cJbOr0n95TDCuXva1dA18U738AVXPJLsZ7a', NULL, '2026-03-30 14:37:06', '2026-04-03 15:09:58'),
(14, 'MAIGA', 'Adiaratou A', '90889198', 'commercial', 6, 1, NULL, NULL, NULL, NULL, '$2y$12$zRkxXaX.SF2BY01fnFKUmuZzK7BUTNeVygYCDTm.Uz8gp1Jv0dn7W', NULL, '2026-03-30 14:37:07', '2026-04-03 15:09:58'),
(15, 'DRAME', 'Sadio', '92096399', 'commercial', 7, 1, NULL, NULL, NULL, NULL, '$2y$12$aBiBuP5luYfJ0qi1FZv8w.s.Pe1lN4IqIa7sC9OLKZJAF.wh9os7m', NULL, '2026-03-30 14:37:07', '2026-04-03 15:09:58'),
(16, 'DIALLO', 'Ami Colley', '76040083', 'commercial', 8, 1, NULL, NULL, NULL, NULL, '$2y$12$NHOutBw1y/gNbmN5eHZ.tO3cdbZQGVhmMZC36qSj3ldQ7UhpowAme', NULL, '2026-03-30 14:37:07', '2026-04-03 15:09:58'),
(17, 'SANGARE', 'Fatimata', '78754962', 'commercial', 9, 1, NULL, NULL, NULL, NULL, '$2y$12$N9x9xK5Mar36WMevO9ZMSOLKWBFcCUyW/ngaJJN9bqfdAaaEtIe1S', NULL, '2026-03-30 14:37:07', '2026-04-03 15:09:58'),
(18, 'CAMARA', 'Ali Badara', '73907530', 'commercial', 10, 1, NULL, NULL, NULL, NULL, '$2y$12$6QH03891N7Zk/547UqDlhut.7ck9MOp1RlOKlKZM2PZW2RMOqyj3G', NULL, '2026-03-30 14:37:08', '2026-04-03 15:09:58'),
(19, 'TOURE', 'Mary N', '69098738', 'commercial', 11, 1, NULL, NULL, NULL, NULL, '$2y$12$Tps0bGTq0TQOGEaloXA12uOsRyACQDv.xyrsxF.fWbkJinpeCZIci', 'm49TfGhPVsM6E94kwjBNN5huL242hJ7scq5EiZJutEZHNloyH9c5x5WHYlOo', '2026-03-30 14:37:08', '2026-04-03 15:09:58'),
(20, 'SERITA', 'Massitan', '79018138', 'commercial', 12, 1, NULL, NULL, NULL, NULL, '$2y$12$JCnWhVTrUNvWhDT1SYtrXeUoA2mNvKA0TrgyV68I0.akx3eJfATYW', NULL, '2026-03-30 14:37:08', '2026-04-03 15:09:58'),
(21, 'FOFANA', 'Kadiatou', '76612042', 'commercial', 13, 1, NULL, NULL, NULL, NULL, '$2y$12$DQspxt4ZcIpqLsqhFXx8xOh.UBBTpSindQQyga2JlcH8PVJgP/Hw6', NULL, '2026-03-30 14:37:08', '2026-04-03 15:09:58'),
(22, 'KANOUTE', 'Nènè', '74353690', 'commercial', 4, 1, NULL, NULL, NULL, NULL, '$2y$12$pyV4.hwDP6SAfzvYppX6iO7LgIA8CdLfZNhmkH/t0yrKBfvfyjIH2', 'kkGoJ6fwMH7cjlgypqF7Wy7ejS1l5xJLCetyOFwvO99EqVE6w93dl4iRlWPt', '2026-03-30 14:37:08', '2026-04-03 15:09:58'),
(23, 'COULIBALY', 'Aminata', '71766277', 'commercial', 15, 1, NULL, NULL, NULL, NULL, '$2y$12$5JoHema14eT4tJkNbo6OzO6w13tZMKdMT63byRHuiapOb0JK0OzfW', NULL, '2026-03-30 14:37:09', '2026-04-03 15:09:58'),
(24, 'SANGARE', 'Binta', '71616201', 'commercial', 16, 1, NULL, NULL, NULL, NULL, '$2y$12$OLwPWEg1OgrbCoHDKnkl.uBQMiJeaRfP9zdrn2SaGwI4zovIrOjC2', NULL, '2026-03-30 14:37:09', '2026-04-03 15:09:58'),
(25, 'TOGORA', 'Lassina', '83140127', 'commercial', 17, 1, NULL, NULL, NULL, NULL, '$2y$12$QpL7y89mgkImFobMx9V15.5jA.gDBL3uVbl9Gvpcd7pI16TJIZJaK', NULL, '2026-03-30 14:37:09', '2026-04-03 15:09:58'),
(26, 'DABITAO', 'Oumou', '64924953', 'commercial', 18, 1, NULL, NULL, NULL, NULL, '$2y$12$xWeG4KQJkFJ4kv9VQcthW.GpjQLQ63OED0CsqFxOX9LivmY2FN.8C', NULL, '2026-03-30 14:37:09', '2026-04-03 15:09:58'),
(27, 'TRAORE', 'Adama', '70277320', 'commercial', 19, 1, NULL, NULL, NULL, NULL, '$2y$12$yY/Z9yl.SY62bF3y1y2riOckDNTdlaMG43BcDbHNHjktMZ6tXpo7i', 'BIGKo2BX1yxOUvhJEJvGo8HNaEZMkN4PB3cZq7Tse17VXS5KL8px6Ns1drcp', '2026-03-30 14:37:10', '2026-04-03 15:09:58'),
(28, 'Amadou', 'Houneijata', '76326633', 'commercial', 21, 1, NULL, NULL, NULL, NULL, '$2y$12$EVYwNaFesuQSktIrQMbxgORTlBew5uN2V7DP/krrM7lOFdOzMjq46', NULL, '2026-03-30 14:37:10', '2026-04-03 15:09:58'),
(29, 'THIAM', 'Mohamed Aly', '70442854', 'commercial', 22, 1, NULL, NULL, NULL, NULL, '$2y$12$c1RbiO3P81udk36bw9bEReD4W/yiLJDGB9.U9GSp9ZTbBLqWuyJnG', NULL, '2026-03-30 14:37:10', '2026-04-03 15:09:58'),
(30, 'TOURE', 'Harerata', '89501249', 'commercial', 23, 1, NULL, NULL, NULL, NULL, '$2y$12$pHaDeUCRezZ6K1Y7x2FMNeGCnn5k.YLQkSV6hSuOjdEgWsemNW.ty', NULL, '2026-03-30 14:37:10', '2026-04-03 15:09:58'),
(31, 'Touré', 'Hawa', '79771505', 'commercial', 24, 1, NULL, NULL, NULL, NULL, '$2y$12$ijhrWX1twGuDBEb6x4xg8OmameTsok6K5MHVFJltYtzwk0oSxf4f2', NULL, '2026-03-30 14:37:11', '2026-04-03 15:09:58'),
(32, 'NIANGALE', 'Fatoumata', '93244009', 'commercial', 25, 1, NULL, NULL, NULL, NULL, '$2y$12$uCrnZu7Hz7WGj2J7N0CSke7mIOWBvayCMsl0iw/IjuksF0hOoyOby', NULL, '2026-03-30 14:37:11', '2026-04-03 15:09:58'),
(33, 'SANOGO', 'Fatoumata', '92330460', 'commercial', 26, 1, NULL, NULL, NULL, NULL, '$2y$12$tz6eHkwFQmLCux7CiTf.4e4Az2k6AHL3qA4k3ORdIUrX0DjyGgnQy', 'xD0d3I0prw2qmp5f9Y6au5HYciS2j4xoHUZDUs0MLbFpBIjN76AFThZD3YsC', '2026-03-30 14:37:11', '2026-04-03 15:09:58'),
(34, 'SIDIBE', 'Kadidiatou', '92021391', 'commercial', 27, 1, NULL, NULL, NULL, NULL, '$2y$12$QPJHI5mt7p89ZRqrQHb7H.pbyAQ87VSSBpxPyI04YpAs.xcNfRAmy', NULL, '2026-03-30 14:37:11', '2026-04-03 15:09:58'),
(35, 'DEMBELE', 'Karidiata', '60625221', 'commercial', 28, 1, NULL, NULL, NULL, NULL, '$2y$12$CvwilsJsWqiI/rGR4HmPUeqCbS59PTjFvGbnB8RWoM58dq7oFXYt2', NULL, '2026-03-30 14:37:11', '2026-04-03 15:09:58'),
(36, 'TRAORE', 'Mariam Bagna', '94888495', 'commercial', 29, 1, NULL, NULL, NULL, NULL, '$2y$12$fFwCfLTkeJ18HrVwB1mWHOOROkbs9ClMedEDEetAQeoSoyv1KjDW2', NULL, '2026-03-30 14:37:12', '2026-04-03 15:09:58'),
(37, 'HAIDARA', 'Awa', '76277641', 'commercial', 30, 1, NULL, NULL, NULL, NULL, '$2y$12$69NM81Swn4neNsUeforF6OIZiNhba5cT8w68XdYjK6OSzDkIiVpkq', NULL, '2026-03-30 14:37:12', '2026-04-03 15:09:58'),
(38, 'SISSOKO', 'Djeneba', '69418521', 'commercial', 31, 1, NULL, NULL, NULL, NULL, '$2y$12$S/dcNBd6kjeYZXS/L.4MJumFR1xMzvqInOLF9/oNFIImXvMrVu75a', NULL, '2026-03-30 14:37:12', '2026-04-03 15:09:58'),
(39, 'Direction', 'Générale', '22300000999', 'direction', NULL, 1, NULL, NULL, 'direction@bdm.local', NULL, '$2y$12$zowXWA7fiat5CMlDrMHxru.K.ZSfgcK/T1VpJITG1fAYkKgbH1to.', NULL, '2026-03-30 15:43:37', '2026-03-30 15:43:37'),
(40, 'KANSAYE', 'Diahara', '78522819', 'commercial', 4, 1, NULL, NULL, NULL, NULL, '$2y$12$Ad4pMeFB9c.4ijHbyi1ozuJjBOLiei1/cbJP7d7483Q8kFGzfG74S', 'dTduCAR2CYBWrnreWO28BhiIPovzQGM1Gc0NHEjvx5WgjDTXwcnzz7FacyHf', '2026-03-31 11:24:57', '2026-04-03 15:09:58');

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
  `montant` decimal(12,0) DEFAULT NULL,
  `statut_activation` enum('vendue','activée','en_erreur') NOT NULL DEFAULT 'vendue',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `ventes`
--

INSERT INTO `ventes` (`id`, `type_carte_id`, `client_id`, `user_id`, `agence_id`, `campagne_id`, `montant`, `statut_activation`, `created_at`, `updated_at`) VALUES
(5, 3, 5, 11, 3, 5, 15000, 'vendue', '2026-03-31 11:32:49', '2026-03-31 11:32:49'),
(6, 2, 6, 10, 2, 5, 10000, 'vendue', '2026-03-31 12:15:54', '2026-03-31 12:15:54'),
(7, 2, 7, 10, 2, 5, 10000, 'vendue', '2026-03-31 12:24:41', '2026-03-31 12:24:41'),
(8, 2, 8, 10, 2, 5, 10000, 'vendue', '2026-03-31 13:14:26', '2026-03-31 13:14:26'),
(9, 2, 9, 10, 2, 5, 10000, 'vendue', '2026-03-31 13:22:47', '2026-03-31 13:22:47'),
(10, 2, 10, 33, 26, 5, 10000, 'vendue', '2026-03-31 13:38:29', '2026-03-31 13:38:29'),
(11, 2, 11, 33, 26, 5, 10000, 'vendue', '2026-03-31 13:39:58', '2026-03-31 13:39:58'),
(12, 3, 12, 33, 26, 5, 15000, 'vendue', '2026-03-31 13:41:21', '2026-03-31 13:41:21'),
(13, 3, 13, 33, 26, 5, 15000, 'vendue', '2026-03-31 13:52:00', '2026-03-31 13:52:00'),
(14, 2, 14, 30, 23, 5, 10000, 'vendue', '2026-03-31 13:52:39', '2026-03-31 13:52:39'),
(15, 2, 15, 33, 26, 5, 10000, 'vendue', '2026-03-31 13:53:27', '2026-03-31 13:53:27'),
(16, 4, 16, 33, 26, 5, 50000, 'vendue', '2026-03-31 13:55:11', '2026-03-31 13:55:11'),
(17, 2, 17, 13, 5, 5, 10000, 'vendue', '2026-03-31 14:20:28', '2026-03-31 14:20:28'),
(18, 2, 18, 10, 2, 5, 10000, 'vendue', '2026-03-31 14:33:41', '2026-03-31 14:33:41'),
(20, 2, 20, 30, 23, 5, 10000, 'vendue', '2026-03-31 14:52:24', '2026-03-31 14:52:24'),
(21, 2, 21, 30, 23, 5, 10000, 'vendue', '2026-03-31 14:53:43', '2026-03-31 14:53:43'),
(22, 3, 22, 30, 23, 5, 15000, 'vendue', '2026-03-31 14:54:38', '2026-03-31 14:54:38'),
(23, 2, 23, 33, 26, 5, 10000, 'vendue', '2026-03-31 15:02:30', '2026-03-31 15:02:30'),
(24, 3, 24, 26, 18, 5, 15000, 'vendue', '2026-03-31 15:25:13', '2026-03-31 15:25:13'),
(25, 2, 25, 18, 10, 5, 10000, 'vendue', '2026-03-31 15:34:33', '2026-03-31 15:34:33'),
(26, 3, 26, 26, 18, 5, 15000, 'vendue', '2026-03-31 15:38:41', '2026-03-31 15:38:41'),
(27, 2, 27, 31, 24, 5, 10000, 'vendue', '2026-03-31 16:26:10', '2026-03-31 16:26:10'),
(28, 4, 28, 17, 9, 5, 50000, 'vendue', '2026-03-31 16:56:04', '2026-03-31 16:56:04'),
(29, 2, 29, 17, 9, 5, 10000, 'vendue', '2026-03-31 16:57:29', '2026-03-31 16:57:29'),
(30, 2, 30, 17, 9, 5, 10000, 'vendue', '2026-03-31 16:58:24', '2026-03-31 16:58:24'),
(31, 4, 31, 23, 15, 5, 50000, 'vendue', '2026-03-31 17:08:22', '2026-03-31 17:08:22'),
(32, 2, 32, 23, 15, 5, 10000, 'vendue', '2026-03-31 17:09:49', '2026-03-31 17:09:49'),
(33, 3, 33, 20, 12, 5, 15000, 'vendue', '2026-04-01 08:27:46', '2026-04-01 08:27:46'),
(34, 3, 34, 16, 8, 5, 15000, 'vendue', '2026-04-01 08:46:21', '2026-04-01 08:46:21'),
(35, 4, 35, 14, 6, 5, 50000, 'vendue', '2026-04-01 11:49:17', '2026-04-01 11:49:17'),
(36, 2, 36, 16, 8, 5, 10000, 'vendue', '2026-04-01 11:54:36', '2026-04-01 11:54:36'),
(37, 8, 37, 27, 19, 5, 0, 'vendue', '2026-04-01 11:54:56', '2026-04-01 11:54:56'),
(38, 8, 38, 10, 2, 5, 0, 'vendue', '2026-04-01 12:13:33', '2026-04-01 12:13:33'),
(39, 2, 39, 10, 2, 5, 10000, 'vendue', '2026-04-01 12:15:39', '2026-04-01 12:15:39'),
(40, 8, 40, 10, 2, 5, 0, 'vendue', '2026-04-01 12:19:21', '2026-04-01 12:19:21'),
(41, 8, 41, 14, 6, 5, 0, 'vendue', '2026-04-01 12:41:29', '2026-04-01 12:41:29'),
(42, 2, 42, 16, 8, 5, 10000, 'vendue', '2026-04-01 13:14:44', '2026-04-01 13:14:44'),
(43, 2, 43, 16, 8, 5, 10000, 'vendue', '2026-04-01 13:15:46', '2026-04-01 13:15:46'),
(44, 2, 44, 34, 27, 5, 10000, 'vendue', '2026-04-01 13:21:13', '2026-04-01 13:21:13'),
(45, 3, 45, 20, 12, 5, 15000, 'vendue', '2026-04-01 13:22:52', '2026-04-01 13:22:52'),
(46, 3, 46, 20, 12, 5, 15000, 'vendue', '2026-04-01 13:26:08', '2026-04-01 13:26:08'),
(47, 2, 47, 19, 11, 5, 10000, 'vendue', '2026-04-01 13:43:37', '2026-04-01 13:43:37'),
(48, 2, 48, 16, 8, 5, 10000, 'vendue', '2026-04-01 14:41:06', '2026-04-01 14:41:06'),
(49, 8, 49, 27, 19, 5, 0, 'vendue', '2026-04-01 14:59:52', '2026-04-01 14:59:52'),
(50, 3, 50, 14, 6, 5, 15000, 'vendue', '2026-04-01 15:02:05', '2026-04-01 15:02:05'),
(51, 2, 51, 33, 26, 5, 10000, 'vendue', '2026-04-01 15:07:08', '2026-04-01 15:07:08'),
(52, 2, 52, 33, 26, 5, 10000, 'vendue', '2026-04-01 15:09:52', '2026-04-01 15:09:52'),
(53, 3, 53, 20, 12, 5, 15000, 'vendue', '2026-04-01 15:12:29', '2026-04-01 15:12:29'),
(54, 2, 54, 33, 26, 5, 10000, 'vendue', '2026-04-01 15:13:15', '2026-04-01 15:13:15'),
(55, 2, 55, 30, 23, 5, 10000, 'vendue', '2026-04-01 15:14:03', '2026-04-01 15:14:03'),
(56, 3, 56, 30, 23, 5, 15000, 'vendue', '2026-04-01 15:15:41', '2026-04-01 15:15:41'),
(57, 3, 57, 33, 26, 5, 15000, 'vendue', '2026-04-01 15:15:56', '2026-04-01 15:15:56'),
(58, 2, 58, 33, 26, 5, 10000, 'vendue', '2026-04-01 15:18:39', '2026-04-01 15:18:39'),
(59, 2, 59, 33, 26, 5, 10000, 'vendue', '2026-04-01 15:20:25', '2026-04-01 15:20:25'),
(60, 6, 60, 29, 22, 5, 0, 'vendue', '2026-04-01 15:22:44', '2026-04-01 15:22:44'),
(61, 2, 61, 33, 26, 5, 10000, 'vendue', '2026-04-01 15:25:59', '2026-04-01 15:25:59'),
(62, 6, 62, 29, 22, 5, 0, 'vendue', '2026-04-01 15:28:21', '2026-04-01 15:28:21'),
(63, 7, 63, 29, 22, 5, 0, 'vendue', '2026-04-01 15:48:57', '2026-04-01 15:48:57'),
(64, 7, 64, 29, 22, 5, 0, 'vendue', '2026-04-01 15:50:43', '2026-04-01 15:50:43'),
(65, 2, 65, 23, 15, 5, 10000, 'vendue', '2026-04-01 16:23:35', '2026-04-01 16:23:35'),
(66, 8, 66, 18, 10, 5, 0, 'vendue', '2026-04-01 16:39:40', '2026-04-01 16:39:40'),
(67, 8, 67, 18, 10, 5, 0, 'vendue', '2026-04-01 16:41:05', '2026-04-01 16:41:05'),
(68, 7, 68, 18, 10, 5, 0, 'vendue', '2026-04-01 16:43:12', '2026-04-01 16:43:12'),
(69, 7, 69, 18, 10, 5, 0, 'vendue', '2026-04-01 16:43:57', '2026-04-01 16:43:57'),
(70, 7, 70, 18, 10, 5, 0, 'vendue', '2026-04-01 16:45:21', '2026-04-01 16:45:21'),
(71, 7, 71, 18, 10, 5, 0, 'vendue', '2026-04-01 16:47:04', '2026-04-01 16:47:04'),
(72, 2, 72, 26, 18, 5, 10000, 'vendue', '2026-04-01 16:48:39', '2026-04-01 16:48:39'),
(73, 3, 73, 26, 18, 5, 15000, 'vendue', '2026-04-01 16:51:19', '2026-04-01 16:51:19'),
(74, 9, 74, 18, 10, 5, 0, 'vendue', '2026-04-01 16:51:32', '2026-04-01 16:51:32'),
(75, 7, 75, 18, 10, 5, 0, 'vendue', '2026-04-01 16:52:56', '2026-04-01 16:52:56'),
(76, 11, 76, 18, 10, 5, 0, 'vendue', '2026-04-01 16:54:59', '2026-04-01 16:54:59'),
(77, 8, 77, 11, 3, 5, 0, 'vendue', '2026-04-01 18:14:14', '2026-04-01 18:14:14'),
(78, 6, 78, 11, 3, 5, 0, 'vendue', '2026-04-02 07:04:19', '2026-04-02 07:04:19'),
(79, 7, 79, 11, 3, 5, 0, 'vendue', '2026-04-02 07:06:43', '2026-04-02 07:06:43'),
(81, 8, 81, 16, 8, 5, 0, 'vendue', '2026-04-02 07:22:39', '2026-04-02 07:22:39'),
(82, 2, 82, 13, 5, 5, 10000, 'vendue', '2026-04-02 07:25:50', '2026-04-02 07:25:50'),
(83, 8, 83, 16, 8, 5, 0, 'vendue', '2026-04-02 07:45:09', '2026-04-02 07:45:09'),
(84, 9, 84, 11, 3, 5, 0, 'vendue', '2026-04-02 07:53:06', '2026-04-02 07:53:06'),
(85, 10, 85, 11, 3, 5, 0, 'vendue', '2026-04-02 08:14:35', '2026-04-02 08:14:35'),
(86, 10, 86, 11, 3, 5, 0, 'vendue', '2026-04-02 08:29:12', '2026-04-02 08:29:12'),
(87, 8, 87, 11, 3, 5, 0, 'vendue', '2026-04-02 08:48:48', '2026-04-02 08:48:48'),
(88, 3, 88, 31, 24, 5, 15000, 'vendue', '2026-04-02 10:34:08', '2026-04-02 10:34:08'),
(89, 7, 89, 11, 3, 5, 0, 'vendue', '2026-04-02 11:05:26', '2026-04-02 11:05:26'),
(90, 9, 90, 11, 3, 5, 0, 'vendue', '2026-04-02 11:25:49', '2026-04-02 11:25:49'),
(91, 3, 91, 31, 24, 5, 15000, 'vendue', '2026-04-02 11:31:38', '2026-04-02 11:31:38'),
(92, 8, 92, 31, 24, 5, 0, 'vendue', '2026-04-02 11:55:37', '2026-04-02 11:55:37'),
(93, 8, 93, 31, 24, 5, 0, 'vendue', '2026-04-02 12:03:10', '2026-04-02 12:03:10'),
(94, 7, 94, 31, 24, 5, 0, 'vendue', '2026-04-02 12:11:17', '2026-04-02 12:11:17'),
(95, 3, 95, 27, 19, 5, 15000, 'vendue', '2026-04-02 12:50:15', '2026-04-02 12:50:15'),
(96, 2, 96, 10, 2, 5, 10000, 'vendue', '2026-04-02 13:47:20', '2026-04-02 13:47:20'),
(97, 3, 97, 10, 2, 5, 15000, 'vendue', '2026-04-02 13:48:52', '2026-04-02 13:48:52'),
(98, 2, 98, 10, 2, 5, 10000, 'vendue', '2026-04-02 13:49:54', '2026-04-02 13:49:54'),
(99, 7, 99, 34, 27, 5, 0, 'vendue', '2026-04-02 13:59:57', '2026-04-02 13:59:57'),
(100, 6, 100, 34, 27, 5, 0, 'vendue', '2026-04-02 14:01:40', '2026-04-02 14:01:40'),
(101, 6, 101, 34, 27, 5, 0, 'vendue', '2026-04-02 14:03:13', '2026-04-02 14:03:13'),
(102, 6, 102, 34, 27, 5, 0, 'vendue', '2026-04-02 14:04:15', '2026-04-02 14:04:15'),
(103, 8, 103, 34, 27, 5, 0, 'vendue', '2026-04-02 14:05:41', '2026-04-02 14:05:41'),
(104, 3, 104, 33, 26, 5, 15000, 'vendue', '2026-04-02 14:16:14', '2026-04-02 14:16:14'),
(105, 2, 105, 33, 26, 5, 10000, 'vendue', '2026-04-02 14:17:44', '2026-04-02 14:17:44'),
(106, 2, 106, 33, 26, 5, 10000, 'vendue', '2026-04-02 14:18:31', '2026-04-02 14:18:31'),
(107, 3, 107, 20, 12, 5, 15000, 'vendue', '2026-04-02 14:39:18', '2026-04-02 14:39:18'),
(108, 2, 108, 19, 11, 5, 10000, 'vendue', '2026-04-02 15:04:52', '2026-04-02 15:04:52'),
(109, 4, 109, 19, 11, 5, 50000, 'vendue', '2026-04-02 15:06:19', '2026-04-02 15:06:19'),
(110, 7, 110, 19, 11, 5, 0, 'vendue', '2026-04-02 15:07:16', '2026-04-02 15:07:16'),
(111, 7, 111, 20, 12, 5, 0, 'vendue', '2026-04-02 15:09:37', '2026-04-02 15:09:37'),
(112, 9, 112, 18, 10, 5, 0, 'vendue', '2026-04-02 15:38:28', '2026-04-02 15:38:28'),
(113, 7, 113, 18, 10, 5, 0, 'vendue', '2026-04-02 15:39:22', '2026-04-02 15:39:22'),
(114, 2, 114, 18, 10, 5, 10000, 'vendue', '2026-04-02 15:40:28', '2026-04-02 15:40:28'),
(115, 2, 115, 18, 10, 5, 10000, 'vendue', '2026-04-02 15:41:34', '2026-04-02 15:41:34'),
(116, 4, 116, 18, 10, 5, 50000, 'vendue', '2026-04-02 15:42:50', '2026-04-02 15:42:50'),
(117, 2, 117, 23, 15, 5, 10000, 'vendue', '2026-04-02 16:34:30', '2026-04-02 16:34:30'),
(118, 2, 118, 23, 15, 5, 10000, 'vendue', '2026-04-02 16:35:28', '2026-04-02 16:35:28'),
(119, 2, 119, 23, 15, 5, 10000, 'vendue', '2026-04-02 16:36:29', '2026-04-02 16:36:29'),
(120, 2, 120, 23, 15, 5, 10000, 'vendue', '2026-04-02 16:37:34', '2026-04-02 16:37:34'),
(121, 2, 121, 23, 15, 5, 10000, 'vendue', '2026-04-02 16:38:28', '2026-04-02 16:38:28'),
(122, 4, 122, 23, 15, 5, 50000, 'vendue', '2026-04-02 16:39:43', '2026-04-02 16:39:43'),
(123, 6, 123, 38, 31, 5, 0, 'vendue', '2026-04-02 18:31:49', '2026-04-02 18:31:49'),
(124, 6, 124, 38, 31, 5, 0, 'vendue', '2026-04-02 18:33:33', '2026-04-02 18:33:33'),
(125, 6, 125, 38, 31, 5, 0, 'vendue', '2026-04-02 18:35:23', '2026-04-02 18:35:23'),
(126, 2, 126, 25, 17, 5, 10000, 'vendue', '2026-04-02 18:35:50', '2026-04-02 18:35:50'),
(127, 2, 127, 38, 31, 5, 10000, 'vendue', '2026-04-02 18:37:05', '2026-04-02 18:37:05'),
(128, 8, 128, 38, 31, 5, 0, 'vendue', '2026-04-02 18:38:40', '2026-04-02 18:38:40'),
(129, 8, 129, 38, 31, 5, 0, 'vendue', '2026-04-02 18:40:09', '2026-04-02 18:40:09'),
(130, 7, 130, 38, 31, 5, 0, 'vendue', '2026-04-02 18:41:38', '2026-04-02 18:41:38'),
(131, 3, 131, 25, 17, 5, 15000, 'vendue', '2026-04-02 18:45:43', '2026-04-02 18:45:43'),
(132, 7, 132, 25, 17, 5, 0, 'vendue', '2026-04-02 19:03:00', '2026-04-02 19:03:00'),
(133, 8, 133, 25, 17, 5, 0, 'vendue', '2026-04-02 19:05:39', '2026-04-02 19:05:39'),
(134, 8, 134, 25, 17, 5, 0, 'vendue', '2026-04-02 19:07:29', '2026-04-02 19:07:29'),
(135, 8, 135, 25, 17, 5, 0, 'vendue', '2026-04-02 19:17:05', '2026-04-02 19:17:05'),
(136, 8, 136, 25, 17, 5, 0, 'vendue', '2026-04-02 19:19:03', '2026-04-02 19:19:03'),
(137, 8, 137, 25, 17, 5, 0, 'vendue', '2026-04-02 19:34:22', '2026-04-02 19:34:22'),
(138, 9, 138, 11, 3, 5, 0, 'vendue', '2026-04-03 08:13:49', '2026-04-03 08:13:49'),
(139, 11, 139, 11, 3, 5, 0, 'vendue', '2026-04-03 08:22:44', '2026-04-03 08:22:44'),
(140, 2, 140, 15, 7, 5, 10000, 'vendue', '2026-04-03 10:14:49', '2026-04-03 10:14:49'),
(141, 2, 141, 15, 7, 5, 10000, 'vendue', '2026-04-03 10:15:55', '2026-04-03 10:15:55'),
(142, 3, 142, 15, 7, 5, 15000, 'vendue', '2026-04-03 10:17:04', '2026-04-03 10:17:04'),
(143, 2, 143, 15, 7, 5, 10000, 'vendue', '2026-04-03 10:19:11', '2026-04-03 10:19:11'),
(144, 2, 144, 15, 7, 5, 10000, 'vendue', '2026-04-03 10:21:03', '2026-04-03 10:21:03'),
(145, 2, 145, 15, 7, 5, 10000, 'vendue', '2026-04-03 10:22:36', '2026-04-03 10:22:36'),
(146, 3, 146, 26, 18, 5, 15000, 'vendue', '2026-04-03 10:22:46', '2026-04-03 10:22:46'),
(147, 2, 147, 15, 7, 5, 10000, 'vendue', '2026-04-03 10:23:48', '2026-04-03 10:23:48'),
(148, 3, 148, 26, 18, 5, 15000, 'vendue', '2026-04-03 10:24:17', '2026-04-03 10:24:17'),
(149, 2, 149, 15, 7, 5, 10000, 'vendue', '2026-04-03 10:25:12', '2026-04-03 10:25:12'),
(150, 2, 150, 15, 7, 5, 10000, 'vendue', '2026-04-03 10:26:25', '2026-04-03 10:26:25'),
(151, 2, 151, 15, 7, 5, 10000, 'vendue', '2026-04-03 10:27:49', '2026-04-03 10:27:49'),
(152, 9, 152, 11, 3, 5, 0, 'vendue', '2026-04-03 10:30:00', '2026-04-03 10:30:00'),
(153, 2, 153, 15, 7, 5, 10000, 'vendue', '2026-04-03 10:30:15', '2026-04-03 10:30:15'),
(154, 9, 154, 16, 8, 5, 0, 'vendue', '2026-04-03 10:31:19', '2026-04-03 10:31:19'),
(155, 2, 155, 15, 7, 5, 10000, 'vendue', '2026-04-03 10:31:42', '2026-04-03 10:31:42'),
(156, 3, 156, 20, 12, 5, 15000, 'vendue', '2026-04-03 10:47:07', '2026-04-03 10:47:07'),
(157, 4, 157, 27, 19, 5, 50000, 'vendue', '2026-04-03 10:58:19', '2026-04-03 10:58:19'),
(158, 3, 158, 14, 6, 5, 15000, 'vendue', '2026-04-03 11:00:29', '2026-04-03 11:00:29'),
(159, 2, 159, 10, 2, 5, 10000, 'vendue', '2026-04-03 11:17:49', '2026-04-03 11:17:49'),
(160, 2, 160, 10, 2, 5, 10000, 'vendue', '2026-04-03 11:21:36', '2026-04-03 11:21:36'),
(161, 2, 161, 33, 26, 5, 10000, 'vendue', '2026-04-03 11:50:21', '2026-04-03 11:50:21'),
(162, 2, 162, 33, 26, 5, 10000, 'vendue', '2026-04-03 11:52:33', '2026-04-03 11:52:33'),
(163, 2, 163, 33, 26, 5, 10000, 'vendue', '2026-04-03 12:11:57', '2026-04-03 12:11:57'),
(164, 2, 164, 30, 23, 5, 10000, 'vendue', '2026-04-03 12:54:25', '2026-04-03 12:54:25'),
(165, 2, 165, 30, 23, 5, 10000, 'vendue', '2026-04-03 12:55:36', '2026-04-03 12:55:36'),
(166, 7, 166, 29, 22, 5, 0, 'vendue', '2026-04-03 14:27:22', '2026-04-03 14:27:22'),
(167, 2, 167, 23, 15, 5, 10000, 'vendue', '2026-04-03 14:35:47', '2026-04-03 14:35:47'),
(168, 3, 168, 29, 22, 5, 15000, 'vendue', '2026-04-03 14:41:06', '2026-04-03 14:41:06'),
(169, 3, 169, 23, 15, 5, 15000, 'vendue', '2026-04-03 14:44:31', '2026-04-03 14:44:31');

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
-- Index pour la table `mouvements_stock`
--
ALTER TABLE `mouvements_stock`
  ADD PRIMARY KEY (`id`),
  ADD KEY `mouvements_stock_agence_id_foreign` (`agence_id`),
  ADD KEY `mouvements_stock_vente_id_foreign` (`vente_id`),
  ADD KEY `mouvements_stock_type_carte_id_foreign` (`type_carte_id`);

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
-- Index pour la table `stocks`
--
ALTER TABLE `stocks`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `stocks_type_carte_id_agence_id_unique` (`type_carte_id`,`agence_id`),
  ADD KEY `stocks_agence_id_foreign` (`agence_id`);

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT pour la table `campagnes`
--
ALTER TABLE `campagnes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `campagne_actions`
--
ALTER TABLE `campagne_actions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `campagne_agence`
--
ALTER TABLE `campagne_agence`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- AUTO_INCREMENT pour la table `campagne_contrat_articles`
--
ALTER TABLE `campagne_contrat_articles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT pour la table `campagne_remise_type_carte`
--
ALTER TABLE `campagne_remise_type_carte`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `clients`
--
ALTER TABLE `clients`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=170;

--
-- AUTO_INCREMENT pour la table `contrat_prestation_reponses`
--
ALTER TABLE `contrat_prestation_reponses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

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
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT pour la table `mouvements_stock`
--
ALTER TABLE `mouvements_stock`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

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
-- AUTO_INCREMENT pour la table `stocks`
--
ALTER TABLE `stocks`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=89;

--
-- AUTO_INCREMENT pour la table `types_cartes`
--
ALTER TABLE `types_cartes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT pour la table `ventes`
--
ALTER TABLE `ventes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=170;

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
-- Contraintes pour la table `contrat_prestation_reponses`
--
ALTER TABLE `contrat_prestation_reponses`
  ADD CONSTRAINT `contrat_prestation_reponses_campagne_id_foreign` FOREIGN KEY (`campagne_id`) REFERENCES `campagnes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `contrat_prestation_reponses_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `mouvements_stock`
--
ALTER TABLE `mouvements_stock`
  ADD CONSTRAINT `mouvements_stock_agence_id_foreign` FOREIGN KEY (`agence_id`) REFERENCES `agences` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `mouvements_stock_type_carte_id_foreign` FOREIGN KEY (`type_carte_id`) REFERENCES `types_cartes` (`id`),
  ADD CONSTRAINT `mouvements_stock_vente_id_foreign` FOREIGN KEY (`vente_id`) REFERENCES `ventes` (`id`) ON DELETE SET NULL;

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
-- Contraintes pour la table `stocks`
--
ALTER TABLE `stocks`
  ADD CONSTRAINT `stocks_agence_id_foreign` FOREIGN KEY (`agence_id`) REFERENCES `agences` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `stocks_type_carte_id_foreign` FOREIGN KEY (`type_carte_id`) REFERENCES `types_cartes` (`id`);

--
-- Contraintes pour la table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_agence_id_foreign` FOREIGN KEY (`agence_id`) REFERENCES `agences` (`id`) ON DELETE SET NULL;

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
