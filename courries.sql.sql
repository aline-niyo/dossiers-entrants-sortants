-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 06, 2026 at 10:55 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `gestion_courrier`
--

-- --------------------------------------------------------

--
-- Table structure for table `archivage_dossiers`
--

CREATE TABLE `archivage_dossiers` (
  `id_dossier` int(11) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `numero_reference` varchar(255) NOT NULL,
  `date_envoi` date NOT NULL,
  `date_reception` date NOT NULL,
  `id_destinateur` int(11) DEFAULT NULL,
  `objet` text DEFAULT NULL,
  `id_utilisateur` int(11) DEFAULT NULL,
  `archived_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `archivage_dossiers`
--

INSERT INTO `archivage_dossiers` (`id_dossier`, `nom`, `numero_reference`, `date_envoi`, `date_reception`, `id_destinateur`, `objet`, `id_utilisateur`, `archived_at`) VALUES
(12, 'Roi-khaled', 'khaled:2025', '2025-04-26', '2025-04-28', 20, 'rapport sur leur application', 26, '2025-04-28 13:49:00'),
(13, 'heillo', '3', '2025-04-08', '2025-04-25', 13, 'rapport sur le dossiere', NULL, '2025-04-28 13:49:14'),
(23, 'heartboy', 'CDS:800/202', '2025-04-21', '2025-04-22', 18, 'demande de  fichier ', NULL, '2025-04-28 14:37:17'),
(26, 'jhh', '2', '2025-04-27', '2025-04-10', 20, 'demande de bulletin', NULL, '2025-04-29 08:25:32'),
(27, 'heartboy', 'CDS:800/202', '2025-04-21', '2025-04-22', 18, 'demande de  fichier ', NULL, '2025-05-01 05:47:50'),
(30, 'Cuniculture', 'CUNIPRO/2021/PR:14', '2025-05-07', '2025-05-09', 20, 'projet de cuniculture', 26, '2025-05-12 06:19:09');

-- --------------------------------------------------------

--
-- Table structure for table `commentaires`
--

CREATE TABLE `commentaires` (
  `id_commentaire` int(11) NOT NULL,
  `date_commentaire` date NOT NULL,
  `commentaire` text NOT NULL,
  `id_dossier` int(11) NOT NULL,
  `id_utilisateur` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `commentaires`
--

INSERT INTO `commentaires` (`id_commentaire`, `date_commentaire`, `commentaire`, `id_dossier`, `id_utilisateur`) VALUES
(18, '2025-04-18', 'traiter ce dossier', 18, 26),
(19, '2025-05-01', 'accord sur cette demande passe a l\'expert pour faire le traitement', 33, 24),
(21, '2026-02-06', 'oui d\'accord pour ce demande', 35, 24),
(22, '2025-05-01', 'accordd de stage', 33, 24);

-- --------------------------------------------------------

--
-- Table structure for table `commentaires_archive`
--

CREATE TABLE `commentaires_archive` (
  `id_commentaire` int(11) NOT NULL,
  `date_commentaire` date NOT NULL,
  `commentaire` text NOT NULL,
  `id_utilisateur` int(11) NOT NULL,
  `id_dossier` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `commentaires_archive`
--

INSERT INTO `commentaires_archive` (`id_commentaire`, `date_commentaire`, `commentaire`, `id_utilisateur`, `id_dossier`) VALUES
(14, '2025-05-01', 'suspension sur cette demande', 21, 32),
(20, '2025-05-09', 'refusee', 27, 23);

-- --------------------------------------------------------

--
-- Table structure for table `contacts`
--

CREATE TABLE `contacts` (
  `id_contact` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contacts`
--

INSERT INTO `contacts` (`id_contact`, `name`, `email`, `subject`, `message`, `created_at`) VALUES
(2, 'Nsengimana Elie', 'nsengimanaelie150@gmail.com', 'demande d\'un stage professionnel', 'bonjpour monsieur', '2025-04-07 08:16:13'),
(3, 'NIKUZE Eliane', 'elianne@gmail.com', 'demande d\'un stage professionnel', 'bonjour', '2025-04-10 06:34:37'),
(5, 'Ciza david', 'david@gmail.com', 'demande d\'emploi', 'hello frere', '2025-04-10 06:44:26'),
(6, 'keza belyse', 'belyse@gmail.com', 'demande d\'un stage', 'bon apres midi', '2025-04-14 09:34:22'),
(7, 'keza belyse', 'b@gmail.com', 'demande d\'un stage', 'bon apres midi', '2025-04-14 10:00:14'),
(10, 'Niyomwungere ', 'Aline@gmail.com', 'STAGE', 'c\'est le stage professionnel ', '2026-03-25 10:26:10'),
(11, 'Niyomwungere ', 'Aline@gmail.com', 'STAGE', 'c\'est le stage professionnel ', '2026-03-25 10:45:21'),
(12, 'Niyomwungere ', 'Aline@gmail.com', 'Stage', 'c\'est le stage professionnel ', '2026-03-25 10:50:17');

-- --------------------------------------------------------

--
-- Table structure for table `destinateurs`
--

CREATE TABLE `destinateurs` (
  `id_destinateur` int(11) NOT NULL,
  `nom_destinateur` varchar(255) NOT NULL,
  `adresse` varchar(25) DEFAULT NULL,
  `telephone` int(11) DEFAULT NULL,
  `date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `destinateurs`
--

INSERT INTO `destinateurs` (`id_destinateur`, `nom_destinateur`, `adresse`, `telephone`, `date`) VALUES
(13, 'BUTOYI  Alexis', 'ngagara', 76321445, '2025-04-23'),
(14, 'Keza belyse', 'Kinama', 76389613, '2025-04-24'),
(15, 'CIZA David', 'gasenyi , taba', 62186991, '2025-04-25'),
(16, 'CIZA Davido', 'gasenyi , gihosha', 62186992, '2025-04-26'),
(17, 'CIZA prisca', 'gasenyi , gihosha', 62186993, '2025-04-27'),
(18, 'mambo chirake ', 'bwiza,avenue 4', 65142535, '2026-03-20');

-- --------------------------------------------------------

--
-- Table structure for table `dossiers`
--

CREATE TABLE `dossiers` (
  `id_dossier` int(11) NOT NULL,
  `nom` varchar(50) NOT NULL,
  `numero_reference` varchar(50) DEFAULT NULL,
  `date_envoi` date NOT NULL,
  `date_reception` date NOT NULL,
  `objet` varchar(255) NOT NULL,
  `id_destinateur` int(11) DEFAULT NULL,
  `id_utilisateur` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `dossiers`
--

INSERT INTO `dossiers` (`id_dossier`, `nom`, `numero_reference`, `date_envoi`, `date_reception`, `objet`, `id_destinateur`, `id_utilisateur`) VALUES
(1, 'cvnz', '1', '2025-04-02', '2025-04-07', 'demande de stagiaire', 17, NULL),
(2, 'jhh', '2', '2025-04-27', '2025-04-10', 'demande de bulletin', NULL, NULL),
(13, 'lkjh', 'RNP:800/202', '2025-04-18', '2025-04-19', 'demande de  fichier de cotation', NULL, NULL),
(18, 'hekiod', 'ULT:2025/45', '2025-04-19', '2025-04-21', 'demande de  fichier de cotationne', NULL, NULL),
(23, 'hdf', '7', '2025-04-17', '2025-04-24', 'demande du ticket d\'entree', NULL, NULL),
(32, 'votre demande de stade', 'BBN: 1025/5', '2025-04-24', '2025-04-25', 'demande de stage', 14, 25),
(33, 'ceni burundi', 'ceni/202565', '2025-04-16', '2025-04-28', 'bonjour', 15, 23),
(34, 'votre demande de stade', 'BBN: 1025/5', '2025-04-24', '2025-04-25', 'demande de stage', 14, 25),
(35, 'demande d un service', 'SETIC/2002:', '2025-05-04', '2025-05-05', 'demande de stage professionnelle', NULL, 23),
(36, 'oree du golf', 'SDF/142536:', '2025-05-02', '2025-05-05', 'demande de  fichier de cotation', 15, 25),
(37, 'gakiza', 'RNP:800/202', '2025-05-03', '2025-05-05', 'demande de stage professionnelle', 13, 25),
(38, 'Agriculture', 'Roi_khaled/2025', '2025-05-05', '2025-05-06', 'demande de stage', NULL, 23),
(40, 'ngabire', 'BBN: 1025/568/202512', '2025-05-10', '2025-05-12', 'demande de stage professionnelle', NULL, 23),
(41, 'invitation', '4555', '2025-05-12', '2025-05-12', 'invitation a un atelier', NULL, 23),
(42, 'golf', 'golf2025', '2025-05-11', '2025-05-12', 'demande', NULL, 25),
(43, 'good', 'ghjd/2025', '2025-05-11', '2025-05-12', 'ok bonjour', NULL, 26),
(44, 'Morning', 'fdfg123', '2025-05-10', '2025-05-12', 'goodr morning', 17, 23),
(45, 'Setico', 'SETIC/2002:4571', '2025-05-10', '2025-05-12', 'demande de stage de', 13, 23);

-- --------------------------------------------------------

--
-- Table structure for table `dossiers_transferes`
--

CREATE TABLE `dossiers_transferes` (
  `id_dossier` int(11) NOT NULL,
  `nom` varchar(255) DEFAULT NULL,
  `numero_reference` varchar(255) DEFAULT NULL,
  `date_envoi` date DEFAULT NULL,
  `date_reception` date DEFAULT NULL,
  `id_destinateur` int(11) DEFAULT NULL,
  `objet` text DEFAULT NULL,
  `id_utilisateur` int(11) DEFAULT NULL,
  `date_transfere` datetime DEFAULT NULL,
  `fichier` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `dossiers_transferes`
--

INSERT INTO `dossiers_transferes` (`id_dossier`, `nom`, `numero_reference`, `date_envoi`, `date_reception`, `id_destinateur`, `objet`, `id_utilisateur`, `date_transfere`, `fichier`) VALUES
(6, 'demande d un service', 'SETIC/2002:', '2025-05-04', '2025-05-05', 9, 'demande de stage professionnelle', 23, '2025-05-05 09:01:46', 'stagairese.docx'),
(7, 'ceni burundi', 'ceni/202565', '2025-04-16', '2025-04-28', 15, 'bonjour', 23, '2025-05-05 09:23:08', 'conges.docx'),
(8, 'votre demande de stade', 'BBN: 1025/5', '2025-04-24', '2025-04-25', 14, 'demande de stage', 25, '2025-05-05 10:22:15', 'sawa sawa user.txt'),
(9, 'oree du golf', 'SDF/142536:', '2025-05-02', '2025-05-05', 15, 'demande de  fichier de cotation', 25, '2025-05-05 11:39:36', 'HK.pdf'),
(10, 'gakiza', 'RNP:800/202', '2025-05-03', '2025-05-05', 13, 'demande de stage professionnelle', 25, '2025-05-05 11:57:12', 'Classeur1.xlsx'),
(12, 'Agriculture', 'Roi_khaled/2025', '2025-05-05', '2025-05-06', 20, 'demande de stage', 23, '2025-05-06 08:50:02', 'EPREUVE_Kirundi_2021.pdf'),
(13, 'Cuniculture', 'CUNIPRO/2021/PR:14', '2025-05-07', '2025-05-09', 20, 'projet de cuniculture', 26, '2025-05-09 16:43:21', NULL),
(14, 'invitation', '4555', '2025-05-12', '2025-05-12', 21, 'invitation a un atelier', 23, '2025-05-12 09:38:39', NULL),
(15, 'ngabire', 'BBN: 1025/568/202512', '2025-05-10', '2025-05-12', 10, 'demande de stage professionnelle', 23, '2025-05-12 09:41:33', NULL),
(16, 'golf', 'golf2025', '2025-05-11', '2025-05-12', 21, 'demande', 25, '2025-05-12 12:00:12', NULL),
(17, 'good', 'ghjd/2025', '2025-05-11', '2025-05-12', 20, 'ok bonjour', 26, '2025-05-12 12:00:58', NULL),
(18, 'Morning', 'fdfg123', '2025-05-10', '2025-05-12', 17, 'goodr morning', 23, '2025-05-12 12:56:19', NULL),
(19, 'Setico', 'SETIC/2002:4571', '2025-05-10', '2025-05-12', 13, 'demande de stage de', 23, '2025-05-12 13:00:16', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `fichiers_dossiers`
--

CREATE TABLE `fichiers_dossiers` (
  `id_fichier` int(11) NOT NULL,
  `date_ajout` date NOT NULL,
  `id_dossier` int(11) NOT NULL,
  `nom_fichier` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `fichiers_dossiers`
--

INSERT INTO `fichiers_dossiers` (`id_fichier`, `date_ajout`, `id_dossier`, `nom_fichier`) VALUES
(1, '2025-05-06', 38, 'EPREUVE_Kirundi_2021.pdf'),
(2, '2025-05-06', 38, 'stagairese.docx'),
(3, '2025-05-06', 36, 'stages pr.pdf'),
(4, '2025-05-06', 36, 'stages pr.txt'),
(7, '2025-05-06', 35, 'sawa sawa user.txt'),
(8, '2025-05-01', 36, 'stagairese.docx'),
(9, '2025-05-07', 32, 'stages pr.pdf'),
(10, '2025-05-08', 2, 'EPREUVE_Kirundi_2021.pdf'),
(11, '2025-05-08', 13, 'conges.docx'),
(12, '2025-05-09', 23, 'Nom.pdf'),
(13, '2025-05-10', 33, 'HK.pdf'),
(14, '2025-05-10', 33, 'stagairese.docx'),
(16, '2025-05-10', 33, 'essai.txt'),
(17, '2025-05-12', 37, 'kaki.jpg'),
(18, '2025-05-12', 37, 'Classeur1.xlsx'),
(19, '2025-05-12', 40, 'Factures_79645.pdf'),
(20, '2025-05-12', 40, 'stagairese.docx'),
(21, '2025-05-12', 41, 'sql server.docx'),
(22, '2025-05-12', 35, 'Hello.pptx'),
(23, '2025-05-12', 41, '1.docx'),
(24, '2025-05-12', 42, 'Base de données1.accdb'),
(25, '2025-05-12', 42, '1.docx'),
(26, '2025-05-12', 43, 'import sqlite3.docx'),
(27, '2025-05-12', 45, 'stages pr.pdf'),
(28, '2025-05-12', 45, 'EPREUVE_Kirundi_2021.pdf');

-- --------------------------------------------------------

--
-- Table structure for table `questionnairedemande`
--

CREATE TABLE `questionnairedemande` (
  `id` int(11) NOT NULL,
  `id_utilisateur` int(11) NOT NULL,
  `date_naissance` date NOT NULL,
  `province` varchar(100) NOT NULL,
  `school` varchar(100) NOT NULL,
  `father` varchar(100) NOT NULL,
  `mother` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `questionnairedemande`
--

INSERT INTO `questionnairedemande` (`id`, `id_utilisateur`, `date_naissance`, `province`, `school`, `father`, `mother`, `created_at`) VALUES
(1, 21, '2002-12-08', 'muyinga', 'masaka', 'MAJAMBERE Evariste', 'MIBURO Therese', '2025-04-22 07:43:03'),
(3, 23, '1987-05-12', 'bujumbura', 'ecofo gasenyi', 'CIZA David', 'BUTOYI  Clementine', '2025-04-22 07:47:52'),
(4, 27, '2001-12-12', 'gitega', 'mirango I', 'CIZA David', 'Nikiza edissa', '2025-05-10 08:33:18');

-- --------------------------------------------------------

--
-- Table structure for table `reponse`
--

CREATE TABLE `reponse` (
  `id_reponse` int(11) NOT NULL,
  `date_reponse` date NOT NULL,
  `id_utilisateur` int(11) NOT NULL,
  `id_destinateur` int(11) NOT NULL,
  `id_dossier` int(11) NOT NULL,
  `reponse` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reponse`
--

INSERT INTO `reponse` (`id_reponse`, `date_reponse`, `id_utilisateur`, `id_destinateur`, `id_dossier`, `reponse`) VALUES
(28, '2025-04-20', 23, 13, 34, 'suspendre l\'accueil des nouveaux stagiaires au bbn'),
(37, '2026-02-06', 25, 17, 42, 'kaki');

-- --------------------------------------------------------

--
-- Table structure for table `reponse_archive`
--

CREATE TABLE `reponse_archive` (
  `id_reponse` int(11) NOT NULL,
  `date_reponse` date NOT NULL,
  `reponse` text NOT NULL,
  `id_utilisateur` int(11) DEFAULT NULL,
  `id_destinateur` int(11) DEFAULT NULL,
  `id_dossier` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reponse_archive`
--

INSERT INTO `reponse_archive` (`id_reponse`, `date_reponse`, `reponse`, `id_utilisateur`, `id_destinateur`, `id_dossier`) VALUES
(1, '2025-04-20', 'suspendre l\'accueil des nouveaux stagiaires au bbn', 23, 13, 13),
(4, '2025-05-08', 'Nom.pdf', 23, 11, 38),
(5, '2025-05-06', 'stagairese.docx', 25, 20, 38);

-- --------------------------------------------------------

--
-- Table structure for table `traitements`
--

CREATE TABLE `traitements` (
  `id_traitement` int(11) NOT NULL,
  `date_traitement` date NOT NULL,
  `id_utilisateur` int(11) NOT NULL,
  `id_dossier` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `traitements`
--

INSERT INTO `traitements` (`id_traitement`, `date_traitement`, `id_utilisateur`, `id_dossier`) VALUES
(10, '2025-04-22', 24, 13),
(20, '2025-05-08', 21, 35),
(21, '2025-05-10', 21, 33),
(22, '2026-02-06', 25, 32),
(23, '2026-03-18', 26, 37);

-- --------------------------------------------------------

--
-- Table structure for table `utilisateurs`
--

CREATE TABLE `utilisateurs` (
  `id_utilisateur` int(11) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `prenom` varchar(255) NOT NULL,
  `telephone` varchar(20) NOT NULL,
  `email` varchar(255) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `utilisateurs`
--

INSERT INTO `utilisateurs` (`id_utilisateur`, `nom`, `prenom`, `telephone`, `email`, `username`, `password`, `role`, `created_at`) VALUES
(21, 'MIBURO', 'Therese', '79368525', 'therese@gmail.conm', 'ThereseBurundi', '$2y$10$FCSzImWs9QFPgFSyJVuoA.7rgWW0kuhfGCQbDaUXCZFkAsoAGQyqK', 'secretaire_executif', '2025-04-22 05:03:14'),
(23, 'Niyonyishu', 'Caritas', '62354789', 'caritas@gmail.conm', 'CaritasBurundi', '$2y$10$qeS.lZFbgtiPu/l3A.xu6OEmEiX7MYgUrwQHZgQg/oL8b/VtFPmva', 'secretaire', '2025-04-22 06:04:34'),
(24, 'Butoyi', 'Michel', '76286313', 'michel@gmail.conm', 'MichelBurundi', '$2y$10$7z4cHLo46kmLtvYD4axGvegNG2SWgEC3/VmSwGABrc2AGbu7yZqdG', 'secretaire_interim', '2025-04-22 06:05:46'),
(25, 'Citegetse', 'Emelyne', '63254558', 'emelyne@gmail.conm', 'EmelyneBurundi', '$2y$10$kBQFUHah.f12JNkGJy0tuermNuaIFLsb2T8vJm3qqC1aatM7boTlW', 'secretaire', '2025-04-24 08:37:47'),
(26, 'MINANI', 'Bosco', '76253623', 'bosco@gmail.conm', 'BoscoBurundi', '$2y$10$yr3rqlOTaXejGP2N9X95Sehqd8apuvqESq/XzmWPUWFBOK9SnotGq', 'secretaire', '2025-04-28 05:37:12'),
(27, 'NSENGIMANA', 'Elie', '62186990', 'elie@gmail.conm', 'Elieburundi', '$2y$10$NcnT5B1glNv6MCm.NR5ErekQfl79JUVnIEmL6p2ek/JVEfTCyhg1i', 'secretaire_executif', '2025-05-08 08:10:26'),
(28, 'NIYOMWUNGERE', 'Aline', '62573421', 'alineniyomwungere@gmail.com', 'AlineBurundi', '$2y$10$pR/F6rFsB37gTK9qQxFiZe14LzFpf/EC.veqBh4xJDcwO8A4GjSAm', 'secretaire_executif', '2026-02-03 17:35:58'),
(29, 'HARERIMANA ', 'Bell', '22435678', 'Bell@gmail.com', 'BellaBurundi', '$2y$10$M/zoq.YwgdbqEn29Bq6rlOH0duTQYaDbDfuoAZH1qPWs4/96glW4C', 'secretaire', '2026-02-08 18:19:05'),
(30, 'KEZA', 'Muco', '67908432', 'kez@gmail.com', 'KezaBurundi', '$2y$10$ND/9zc41SVk4YO.0j5AVmOPPvAVIx6khpXviNdr2NxVQseV5Lub7m', 'secretaire', '2026-03-25 11:00:30');

--
-- Triggers `utilisateurs`
--
DELIMITER $$
CREATE TRIGGER `protect_default_user` BEFORE DELETE ON `utilisateurs` FOR EACH ROW BEGIN
    IF OLD.id_utilisateur = 21 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'L''utilisateur par défaut ne peut pas être supprimé';
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `protected_default_user` BEFORE DELETE ON `utilisateurs` FOR EACH ROW BEGIN
    IF OLD.id_utilisateur = 23 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'L''utilisateur par défaut ne peut pas être supprimé';
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `validation`
--

CREATE TABLE `validation` (
  `id_validation` int(11) NOT NULL,
  `date_validation` date NOT NULL,
  `id_utilisateur` int(11) NOT NULL,
  `id_dossier` int(11) NOT NULL,
  `id_reponse` int(11) NOT NULL,
  `sign` longblob DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `validation`
--

INSERT INTO `validation` (`id_validation`, `date_validation`, `id_utilisateur`, `id_dossier`, `id_reponse`, `sign`) VALUES
(18, '2025-05-03', 21, 33, 28, 0xffd8ffe000104a46494600010100000100010000ffdb0084000a0a0a0a0a0a0b0c0c0b0f100e100f16141313141622181a181a182233202520202520332d372c292c372d5140383840515e4f4a4f5e716565718f888fbbbbfb010a0a0a0a0a0a0b0c0c0b0f100e100f16141313141622181a181a182233202520202520332d372c292c372d5140383840515e4f4a4f5e716565718f888fbbbbfbffc20011080092043803012200021101031101ffc4002e0000030101010000000000000000000000000203010405010101010100000000000000000000000000010203ffda000c03010002100310000002ea3082744aa01199ba25393ab457478c5dc8c9d39293b93352e0668041374aa0119b9a00523a38006804e88e19b86806cab228000684dd070037035754c7460dc0d0c1293a186e1a6023a38006e008e8e0000023a38000013a4e80018008e8e002001374a1800004e93a2802002cdd28819a19aa49e3d3a69a6588e8368000623a0f9a1800baae002b880f3d42c20393d26e4373a5e4d9ba210dcb45d4e86934b4c5c8a080f3d996d407101c401e2e388141006930e20504079ea558421f6614989561087101d71699a7a3f2ba6a7588b9bb4e5b8f8a436a063c5e9c4228200d27a61087134c78bd388438803c1e9c421c45a67e5ba389914105ca41e9c421c401e2f6393228262948bd509aa5a125d37b792f142665498b551087101970a7148d305579ba69814025c4a4ea80400071f672ea5df9fa4c3725e5b7376ea6019ad9b868013a4ca00000004e93a0000008e8e0002b72d9d2bb850094002749d50080005643754ae5ebe4edd42555cb9faa36a00c830129375559d7528619252545004005479d0000c39eca53925a7a1bcdb17e7189f5737518066801374a5669900023ce866e6a0ad334e77d173a1eb21d1c91d2c196042acab95634cb0d09e8d598db08361271a94621c0949d129f7080009d30e4ede1eedc304cde5eee3ebd4159735c000027499400000022749d2800dcd09ba3866e09c558f5cfa48e9cf5409c50cd09ef36a7610bc004a49e365579576b75f1c8f4485f0474a2e1b90009374cae6ccede91a6b0cd5efe6ea322dca771a61374a2e06998211d876ee36861cef26db6c94ca16c91639ba49d2745c020cd515d4b1960d49bd6831b99a004e25b5299158bf2e75690bb6432ef2c756f3740af30a669199aa1a69800e20ae829527a393d1f1021794b73bb9e9cb974da7a32092d8e6db3a3664b44c42db20a6cc28200f17a71087103767b5496f3a6e3f2f47a929ee0f2cbd2d1211b712adbc748e8c48435a09b74c6d08aa5b90efc8be6ebcb4710871328e63373a93372e7e96da9521532ca653ba00f1757c41116b3ae7e95ad0f39e53b64f44ede50e8e6789d16e6628f2dc9c9cce8940d369421f664526216c5c2993853bc23a9d7091a74ef150ec9f1d0eee4db65cdd5cfd152bce71dc43716c8aa584c284c2a699b8aeb432399a118013e6e95dcaf26bd749a62e4e9c953efe1eed433658b1e885f539c1b4e934e74009d27400000177349f2751b8b2ec9959d13363d325b2b0e9e63ab9f168ebe6a14e3eae32b2ad74ac29b83819a66a98eac60019b872e9d5b86860062ad26e0609b8023ce8a00823e2f174e1b894a6e599b92abab2060265274cbcf4a4ad8366e92b810649352b0dda4cebd38d7b65585d7283ebd20968e1ede5e8d4a371f466cfa254161589d3aaf188f31c080018cd94cdca579d0008c009ba3d79be8f1f5f495c0e77395e9a91ebe56ab40e8cb92cd2ae4f4a695d22b62937e5ab5674837034013734e7bf3f5ea0699a4e932800006252754c08813eadc6474cdd60800047437734000c6539fab9ba75030cd0011d1c000cdc11d5cc340cd5319590dc1456506cd000ce3ed9d994e4cd3b378c8ec9c12bb4e523a8e50ea482576e72c8e8b79d6aec5e5c8b2266a6abed19af1b1b644d3d0c5e0e8bb473972229d28215226500d992d040ca45c6101c401a784b45e93b79abb8636cd6cb843880f8b8334987101e142878b8e211410344296e98536643a0957c421f665525a8584c8e7ede1ebdc741336c4c1f67a3a6294d407101d08256b3d1c415f154769ea393d1d7155da5a3ecc4a2aaad367a8e4f55d042bb30a13d1d3512c20393d1e7a8545c28203cf50ae2cc3a214ad108a120a881ad262558d34ae2ae4ed2d1c9851310b098509850365c342749d0c02000595b9b52daec46f84a4e9328601b81b802b2386e068023a50000005006334000479d50080009d274e6a490ebe7e8d09d272d0080c0d9ba0e6686e010bc2cb6e6c0002b22eb2ea1a00ac86b2b1801a8c8ae0200293a4e9f73606cd426f3aa0100008f3aa0100013a4ea90bf397d085336b4568dc011d1e8020564180000c4741c00301809400470acd08c00ce60dcebd0cd0009014020028009d003400011c0d000014034006032405400d03261548823540d982be041a01301c00d032416540940104056d0400040180000102d702370026154021b0109855008340540a7d08300d9853c02ba30325d0a874050065370a008d9853810002a853e046807fffc40002ffda000c030100020003000000213b80ba8b25ae3c88880aa91cf04894116f382beb0e9ae182d0cdbe73ef3c78cf2a03c18d873f7df8923b2459417e32ff00471ec0c12f0c470e2f33621680433ef827ba4b0ea623acb0c0592a4a6f36190dba732c8ccbc7d7c8ccbe44f8ac75c1cf67fbe90c23c32bcf821d903f2b401000003ef3ceb8959eb671e08673807d0ef9ad0eecfe7a276d17f4fe99093c6a94ef5218fdf6b0f56c7d4f7b8b865a962d0804141042bef10abcf689bef61493d20a58dab0fd6308376baea69ca4e76ecfbee648c608a4d49579c94ef6ff0043248871eae1883b06bae094cbe00f695e7f56f7d0fe6694da021b988bff0089dee23f6bbe341b497fde75167cd187b5e3fcb14dcb4322e914b0924ea95b003ef3c0be9b4df724c1ac256aa2fbeabfb823db28348d6faccfc4f148f9188c81d816bc6952665bcf284702e88c1c3695dffbcf3eb002a348208281acbcab6d96fa2e6eaba082eb21bfbba886cdcbc5cf2d3ceb0e76d57928f51e71b00cbcf360bb9aedaac9273cf825068ac6a04a6262328330d17ef0921054225311e30b1cec1253d410ff004778fd4f88eb74784de30bbeaa7cc6c8cb087a831200048a0a42bee33f7c88e69ebaf0d3dafcc3aff293f90aa3373ce58e7547cddfdbc730c72f70cf7a2722fa10bc7a2720fa0f1e7008be8be8a0f1f7c0fbe09e8a30a38830c37ff8020bc79ff430fd8df7607fd063f7f7430dd7c3ffc40002ffda000c0301000200030000001036d8dbb30cbed217de0be26f74187cf53edb2fcf9432c12ad8e7b03821bedb62be4be5bb7d3bff000171b37dbb3336d06dadf5723f9edb42099020cd8ad92c9a575ca423e98b7f2188f28130482c8e288292d9638efb04e7ba2cb637f1b815fda36433f9a7ccc1de30f39f797c71c76e055f694ef146fe700cd2052620dec5f5d4a696af50e9c5f691ee1063c9ba1bf1ea57c27e135b796659418d51fb4d238ee7114fe79ded751a52f52680f57b29e6e7d0915e4d955053d906930cd672058f1465b038f3f18640ea0b75967d9c9243fc5ee37a5fca235e4037170b3a753ca3a8db237326a94afe84f9c9601c078ab8cfd27cf8ca10dd625606337a13085c41de18e3be105028382ad1ff00620eb4b9a7e88af1ba826bc922400661522a41ea1508cb21e97f2b9c22425333684b8c751fe4d6712d53c30d6cf94849e21ca79ebf1ed240202051c37858c5ae52a1ace2ed1c890c1792e79828f894cb384d6132d9bf98b2418680bc39eb0e338f90ae71fc089c4cd3b4bb64ac602facf382a472f5c47032703a988b72e004969f4bff00144d9b1575149961b4d17167167f1cd962f79859c65cd208388814dd00723ab460c998a37fed3af877a7508502ca29549765459072e6de559459f9c77c4d0df0ff000dd83d07ff004181df5d8df0c175f86385dfc0f207bcf1df1d7a1f7ff7df20f2073d76106387df61f41f01f7d05f87df41ffc400321100020102050205030402020300000000010200031110122131513241041322617142528114203391627223a1538292ffda0008010201013f008bdfe3164b223f668fb9c28d3f31c03b0d4c765a94cd85b2ed8af7f8fd8dbe27a462bb362bbe23711b738b6f8b6e3e316edf189d97e313b2e27a5713b0c4ec3e316df01b88de9a43fc989c177c477c7b61611409612c3994c0a941d2fd27308c05f7961cca202d2acfec1669977961ccb0e62817de584b0e658730817de58732c258586b2c39961cc502cdacb0e65873142df7961ccb0e6002e3d508173aca2a111ea9e2cbf265813bca8851c86d258732c398c05f7961ccb7b88c36d46c25bdc4b7b88cbb6a36997dc4cbee232eda8da65f710212401b98f4dd6c08b69329994c643a7c4c8d329e232b4c8dc408dc465398e905373b29329d00083537eca278804d4b2ae80584cadc18aadae86653c1995b8332b5b63329e0cb1e0c21b4d0cb1e305df1f0ce16a80766d0cac992a15e306f4785a63bbb169f4fe715dff636f8fd38514f32a2af261003381b0c537c46e21d58fccae72d3a54c719a2100df8da576cd53e0018b6f329545016ecfafe306dff000316dff1825367bdbb45f0ace459d361de1a36240a887f3151a92b39df612b9f4525e16ffde2db8f818b6e70504b002794aa6f51b5fb46f1bc4640569a85946e5cb9fa41261249262296beb603730aa842ca5a5ccb9849b4b9973189bef2e79382f50c468419e27d592a7665805c813c51f5aa7daa041d2715dff6363f4e1e1a92d35bb0f5b4eef154b1b004984106c652a4edead947731d0a1dc11c8c29aae619ee01d8c1e16efa5443731fc3bd5aaec41551a0d2f1d3cba856f7b18fd4df3885cd502f24095aa0a04e5ea22c3e252a05c867d8f69e2d867c800016787a2ac41a9b1e91cc22c636f82ab36c098f9a922051ae8c61bdef1547e9d19ba43126553772659aab28ef1e9850086cc09223751c06f082ce4017379e4aa7f2bdbfc46a60ac01b5350a3fee5ce1d146dddb53f1050a845c2c1e6787a77b58b18f55aa0d62532eac40b911e954a601616bccac56e01200d701bc3be168a3d4215d77997de65f78aa1fc295beaa6f28266aabec6f2a90f51dafb9300195b58297fc66a16d2f612c398a351accb2c39961cc6039ed2c39961ccb0cbbca281ea2f0353f894c17a849bd829fecc28899867074ed0914502836661726055ad5b7363a98ee1dc052428d1447a211492fa820116894c5475507731a935570ca0e45d01b7129119ddefd2a65225e93d35739ae088caaad6cd182e63af7965e6003994a984cd589d40f488328a81eab5f5b912ad70ede9365bca9e417672e4dcdf2da33e660d9ad6dadda3b173766178c0663ea12c3ee111897552fa1234da56aa7cc60328edef32173d421349e9f941d4016209950506555ce14a9e37945692b90d516c4102c799529be6b1160368c353a88b4d9cd96c4c4a08a47995003c08d50ea299551edbccbfe422aebb8997dc44a0ecc0769fa7a8d52e56cba7f422d155ce49d4f7e279245354460c35bdf883c3a531a119bde27974ac454466bdcdcda5608f94e8c6ddda32bbd365f48b1b800c345f81fdc08d7854f2265f7180dc46ea38d07c8e9c1d0c14fc9f3db8161f9c2921a8d94779e2ac82922ec16f154b3003b99e234ac146c02886f4dcaed4d52e7def8b76f8c7e9fcca2e69d12c00b93606d29d76ea7627d62777b47f2aad9cd4ca6c0116e2502b99d41b164201329d26a6e1aa6801fc995fd66e1d726e359e155479950eca2dfdcf35516e1812e3403b4072d2a87ee60301b88dd47011aa3a51b5f76b0f818b751c5ba8e03711de83b1660e1bbdb631aa69954655e20dc46ea3f382b312ab736bc34950dea9b70a378d5cdb2a0c8bed17a860b4a9900b5651157c22dfd6cda7c4fd422ff001d303fecc5aee69bb127810d56c86c4fb9bc352a3d150cc4e668e1d2b0001256d2b598233a32df83795292a9508c4dfb11a8954ddec361a4a4af9f35bd3dc98d6cc6db41fb047ea38fd23e6562cfe12931e75c1479484fd6c3fa11d1ab2526417b2e52043ff08b03eb3bfb4cf46a90ce583697b0de56a82ba8556ca54f49ef0822514572d9af655be91ed7d36c7e9fccade84a69fe380d9be315df027cbf08a3bbb1313a8424ed82ee3e636e701b89e23460bc0c046ea38b751c06e236e701b887738516095149178d4a9b9bad7173d9b433f4b50ec50fc345f0b581d87f627e92b7da3fb13f4957fc7ffa10785aa2f729b7dc20f0a7bd441f98fe1c0454f3505b53733c84035ac9117c3aa80d554917b182b78755b79c49e6d0d5a2011e754fc08a7c3adea0ce6c7bc15e803a7871f932a78847dd0fc6686a53ff00c43fb303ad8ffc6b33afd8b330fb04ca658c606f2d32ccb7036de5c16340916c800f989969b12e0122125d8b161782e36696f7100171ac61a9d6587311b266d770442069acb0e658732c32efde3bf987331d65873005b36b3d3ccb2f3142df79e9e4cf119479697e941102e61acb2f2659793142e61ac396e75965e4ca48acea2e779508676373bcb2f2600b71a98c16e75965e4c016e353182dceb2cbcc50b71ac216e75965e6285cc35965fba59798816fd52cbccb0fba2db5f5769a7dd2c3ee800b37aa2a8660334ad6351bd52c32f54ca96ebd7e25873081a7aa1f27ca5198e6800bef085fba5979802d8faa5979965e4cbc06376f8c688cce83fc84a8e7ce661f74a8f9dd9ad6b98b888dd4713b0c7e9fce2366c537fc18a2ec072678937acfeda44ea18af50f987738523624c3b9c06e21dce0bb8877382f50877382750c577c57bfc62366947af37da0984de7d389ed82ee277c0749fdaddbe31f0dfc89fec23f5b7c9c177fd8dd4713b0c474fe711b1c537fc194bf953fd84aff00cd53fd8c4ea18af50f98773826dffb0877380dc7cc3b9c17a8629d421c13a8629bfe315eff0018af4b4a5b55ff004c3e98ff00c74ff381ed82ee311d38ff00ffc40026110100020103040202030100000000000001001102102131122030514041617142505262ffda0008010301013f00ec1dd355a2553fd91cbe676c8d5e43e33bb5a5df9ecf1591cbd4c78f3646d0dcd39c9f2ad10f01cae815d9f7deb51caa5fe18b7b4397c0cb5e218df31f5a3077f2e3eb4c3cb9370d5420de971cbf10c8020d9d8c0b8e531993ebb76574fe5a71707b6ef8257bd7965936c9815177208cbf13b6445da1b1a5ef5adf7ad136ad399c102a0dc7620d47e88808f6e4db53eb6863373681b400d53681b4e26f7737b58dd42b4596bc12bdf639012ca8bc4bdd65ac6dfa6173632259e14b25df4e8b31dd59c4c78f03bb1342cdaa27116cda13278205bfa9f67751d5e1a495db77c431f7beb6fa9797a9d3ed9d2584a94193fa9b38c2f7a60f370e26495e336c9d3986cb39949c40a745aedc795efe72fd780f36459053ea7513a89d78cea27513abf0c1dd6a5bea3d4bc4e9cbd4e97d11ea5ada38bfea18d7dca7dcafccafccaeff00fa9c938eee7b42bbf1fbef61f15871da5f5791e181b40af84cc78ef7e365c7c2cb887c378663c7cd793f7a9cbda47b3fffc4003410000102040307040202010403000000000100020311122021313210132241515261044262712381143330244353a191b1d1ffda0008010100013f02dbfee1b08c133a22a1e9b623e80854fe29f34d33170d66f66573f2bdba9d73fdbf773b2299a45ccf77ddc359bbde3eae3a9b73b36dcfe5f773fdbf773b536e1a9d69539432a168163b2bbdd6bb242df7fead88ea220d90f2b5fc7143539a1ad0028730e75deffd5ece773f2bdba9d73f97ddcec8a66917339fddc35baef7feaef78fab9da9b73b536e76a6dc758fab9b9bad764545d2d6f5406163b95c3336bb95c758b6389b67d1423362673fbb6071442e4fd285def17b39fddcfcaf6ea75cef6fddcec8a6e91b49de450de436b39fddc353aef7feaef7feae3ac5c75b6e3adb77bcdcce7f76c588d68f2984c58830cad3a85cdb4ea1b69f2a9f2a9f28b7882a7caa7caa1509cc982a0665b34d6e6a9f2553e551e4a8a2963b15e9dbc253db8668370ccaa3c954f92a9f2553e4aa7c954f10c551e4aa7c954f92a9f2553e4aa7c94d6e78aa3c954792a8f2553e4aa7c94f6e19aa7c954792a8f25503caa0752a81d4a6b7138aa3c954792a8f2551e4aa3c94e6e23154f92a8f9154fc8aa7e45527b8a734cb520dc351549ee2a24dadd454187999aa4f7144100f1a82eaf9c952eee527772a5ddca4eee5277726874dd8a93fb949fdca4fee527f72e2ea10aaa2b8fa85c7e171f85c7e171f85c752e3f0b8fc2e3f0b8fa0537f40a6eaf2537f4537f453776a9bbb53a25226426c60e7e4aa3daaa3daaa3da5547b4aabe2555c792afe2557f12abf8955fc4aafc141fc47055f82abf0557e0aafc14620e8531d864557e0ade0e8518cd19a318bf06845ad6899c4a80ecc9e6b78d5bc6ade3556d558a956deaab6f555b7aaadbd517b659a6b9b2cd54deaaa6f55537aa98eaa62acd4c75531d6c766db8fe38a9ba9d67a83c2028424c09fa4a190b8ea6dedcddf773f2b5ce0c1329a4384d3753ae7fb7eee76929ba46c8c6647fd268a5a06c7e2431411c66e6e6efbd8e70689a6567889fd6d1a9d70d66ef79fab48deba5c828babff00170d66e6e6efbb5d914cd2139ed666b7912269121d53604ccdc66800dc97a8c80ea9a2968164da84a67648290e8a41380920d0a91d1523a2a5bd1523a26b44caa5bd150de963fdbf777a86e13500cec8dc5140413b229ba45cecdb7b7375cfcad8e74b533404dd4eb9f9b7eee7e929ba42729551c0e4ddb2c4950723733ddf7b1fc711acfd945ed6e6567b1ba9d70d4eb0e484571c98bf34dd201174574f8b24047ee0a71c66d0518d2066d20a80380795aa34ba5cdd4eb99ced88e0d6ade3df8307ed0841bc4fc54aac796d9d71beac24c474b922c6018a8395aeb9973f97ddcf13690bd39e222c87c5189d8724cd22e7e6dfbbdba9d73f4d8f7d026ab2f249e88649ba9d73fdbf773dcd91134320a79b97a6f73bad90b4dcce7f68a61d4e1a89c16eeb78e7d4ac93df4f9283a23df20719ed7c60c32cced6e6eb9efa43ba92b7728548cd0d9ea3faff006a7bb605046a273d8d6c9d39e7633ddf7743d3b5ce6b732b7af7994308c1e6f3328095911d4b49505b2c7aed719029909c78aacd08723c58a665b019ed7662d3926e573f2bff00ae36d7993495e986676b34dcfe5f778d4eb9fa572dae787127b516c833ca1804dd4ed8e88c6e04a0663639e1831537c4225862818add788b2a1394f15161348279aa63e1277e9447c50da5cd50e331ac9645035cdcf7fe940aa9c72e48e4a1e81743cbf6a31930a68f6373e69a29124f898d2dd48e1e5fff00a5e99b813e76447d03ca7b28a1c7ae2863b19eefbb4c560f7041c1f1a7c820f076fa9d23ed33f21ace5c9339fdec3c94471183732a92c6d45d8ec8791fbb5d914cd2139ed66656f2244d03f69be9fbccd0006413f97ddb1789ed621a93e2118344ca11bbc48a8918112914c952248e49b923926440c9d58231fa09a08ea4d88d7190b1d92195cfd37fa8191509d5306cf50781411260daccae7e5fbbc6a75cfd276c5752c29acc1adeb8951318ec1d36022a7288fa5a9907dcfc4a83a7f689909a634c5754ec910052075465b4a60a9f527e92998e2a2f14563545634b5d86325021b0b0196c39266917332519dbc786353181824a33e8679299f1c5dccaddd2c74b392f4c7f1fed3e235bf698c33add9a7343848a642730e0fc3a6c673fbb3753cde53836109864d41dec8968189cd084e3a9e7f48300d91897cdade59a82670c27be5200f35c90c539c1b1f1e884e219fb76334db11c034e283e23f060c3aa6c06e6ec4d8ecdb61c04d41e22e7a71954502d86d994d7b5d126e5ea245a1358618041c39a7c568089310d2d464c62792e6e9c10ddb4b4872df4f4b494e111d324c940028b1f64ddd14dcb893aa92154971ae35c6b8d71288d270501c665ab8baa8b373c3549c066a4eeaa4eea9b3eaa2b9cd19a84c70666a4eeaa4eeab1ee4e0eeab1ee58f7293bb949ddca4eee5277720d351c5527b9527b9527b9527b9527b8a734cb341a7b8aa7e4544137b5b32853892f45d38931338a0c8a73749361c89c5535c4ccc82a3c942181cca8a26e6b01421c866539b223154560b9c4cb9284ce01895bb1d4a8e296e671526c268993345c1f8367e50609734d6d519d9e0b763a942a84e2245318e38ba63c22cc3329ade1189547c8aa3e4551f22a8f91547c8a74c3400712a0b054ec7259e924adc55a8929b0837225527b8a10278874936035bccaa4f71549ee2a9777aa5ddc980cb3527772a5ddca4eee5277728d56edd8a800eec48a93fb949fdc9ce7ce90665321b993e2cd6ee3327411229b066c3e56e224a55e0bf36424027c17ea9cca646ab9807ca2f2722136b9725c7e17e4f09d14b3392aa344c8482dc1189c50ac720bf2785f93c2e3e8171f846ba82e3f0a6fe81564674a8b17865308476b1b24f8f50c94e213553341c493c00a735e0e9423c50248d6530c66e002dd47ccaff512c9a8b5f58e06cd55147fb6107c4c7f1a8711cd716d2b78eec5bc3daab3d117198c14dddaa6fed537f6d8ed253748b9f9b54414440e53989a6714626c6fb93ddbc88072b20f13dce4fd2bfb5d2e49cddd16526df79b9fa4a192895cb85358624dd52101833c5112840fcb60d4f504f13c73d954f24cc63bb67a8d2980c4007b46c9a799c6c726a6f73b53b24d6f1bbc2e4a07bddd4d8ec8a66916945b36d5394941842999b61e9b9995ae13690a0c5ddf03918b0c7b954f8ba448754d87465b0e499a45861b0e6022035a64137209f11accd55122e9c0264068c4e2763f21f76988c1ee09d1d95755be8aed0d5bb8aed4f5b86732e29b0d8e892192218cc033150e18cc84e82c682665406c9a86310a8a3870083802384cd6f07341cd76476338a213b1aa30910f08198da750bce453348b9fcbed466cd8a1c4fc67c2f4c3026c88e9540665426ca2ed8aea5abd36929fa4af4dee43f2c49f216fbff573f494320a31930a86241a13b4954550804d1201375393e0d4660c8adc93ade4a0d004827b5cc7d6dfdadf8e4d28b5f10b6bcba20d8b0f06f1350de3b3e14781a5426d7fb29ec8862603e9514b13cc987e9431260b1da4a6e9169c93ff00a80ea809095b0f4dccd369418d7344c210618f6d872299a45b13415bd73b86184c80337e26c7fb7ef6bdb149e1760b70f76a7afe33799420b039523638c812a00c0bbaec664a3695a59fa500cea4e7b5b995123349184d06c373674c96ed93e04ee1628424dd81c1a3129d10c4e1684d6d2d036fbbfc10f4dd1392cc27b68716a8624c1b4992862b885c861ea3612004d6ef0d67f4a1bb76e2d727bd94e686a90c014d68680058e88d6e6534cdd3f173f4943209fc51437a26f5b1ba9d73b5376c73287f6a00c3644d28b43a53b5fa4a6e9169e68713e18e82773348b4a669169c8a66916bb494dc85bea67c3daa1d14f0daecdb70d676c6384baa68900114dc946c247a14e7d720324f0c0661e849dffd28b6165521b919c49adf416e45448ed74a4bf93d1a8c68a5368f74ca111adc9856f7e056f0f6155bbb102fa8f02a9dd8aa776a9bbb6d8795cfc9051ffb1423360dae35ba909a2974bc28cd21c1e16fdb2f280745c5d83763e1b5f9afe3b42ddb5cc010dec3f2135d3da1a1d19d35ef1f573f4943209b8c475add4eb9da9bb7d462e604d1268d9132b9fa4a6e42d39150312e369c93348b4e499a45aec8a66916bf4943216c400891ea8fa5e6c72a7d4b79cd6f238cd8bf90fec5fc93d88c7991c0bf93f05fc83d8b7eff00f8d6fe27fc6b7f13fe35bd8b33c0b791cfb17fa9f2a51dcee730bfd5aa7d5754217a8eeffb5b9f51debf8b13aafe19ee5fc3f92fe289e6bf8b0d18109a32506137392a5bd110249b95a333fe0673fbb9fa4a19268de457a80697169d8f8849a5aa143a0795effd6ca1bd2c3914dc85a04a31fa5ef173b22864142865a4936b7375ced4dda78bd401d36c4cae7e928642d79931ca08943169c8a6e9169c8a6e916bb494dd22d7e9285afe5f771d41485a353b6c1c4b9db0e450cadf76c8cec82616cb0d8ec90cad6f3b2af0abf0aaf0aaf09aecf0551e8aa3daaa3daaa776a9bba27174b253706a801d225450e6b8390a88527785c7e17154b8d71f55c7d571f55c5d510e9669a0cb352777293bb949ddca93dca93dca93566a47b9527b9527b8aa4f72a4f714e699668370d4553f22a9f9154fc8aa7e4553f229adc4e2a8f2551e4aa3c954792a8f2516f10540ea5501426ce2bd5015013d8005405405bb0a80b76139800418150d5bb6aa1aa80a3340007541824150150150150139824830482a02a02a02a0273048a0c12540ea551e4aa3c9547929ccc3328330ccaa3c9547c8aa3e4551f229edc332a8f2551f22a8f91547c8aa3e4539b88c553f22a93dc5527b8aa4f71549ee2a93566a93dca97772a5ddca97772a4f72683338aa5ddca207069e2509a6818aa5ddc9cd74b520d74b527432e122e4d865a352a5ddca93dca93566a93dc9d05ce76a421cb9aa4f7145a659a0df2551f22a8f9154792a8f25359e5503a95bb0b762d6ea75cec8a79fc2a089430a2b6a6150346d3ac5c5334dc75b6e7645372173753ae3adbb7d28c1c76c4c87ddd1349432b5f8bcf808642d76928642d76928642d7e928656c4cbf773b36fdddef1f5737376c8dc8752865b1d91432b7dd6bb242d6e5fe01acdc72513fa82609346c94b69d62f665b46d3a9b73b229b90b9ba9d73b5b763f063be97a71f8f6bf97ddd134ddc9e50cad7e928642d7e928656c4d373f97ddced4dbbdffab99cfef61c6281d36bb243245c059eeb5d95a5332ff07bee3927ff005b7ed0c85a75b6f8795ced4db9da4a6e91b79ed6ea7dc75b7646feb2a0ff005b76bfdbf7744d377fb48642d7e928642d7e928656bf4dd13dbf773b536ef7feae673fbd8dfed76d7648289adb67b8daeb4e49b959ffc40029100002010206020203010101010000000000011121311020516171a14191b1f03081e1f1c140d1ffda0008010100013f21c574649190f091b1963078a2538b67574b3dee735ae567eee6bb31d2ceaecc74d667987cecdd8cde1ccf06654afc6ac6396d846a6c9f30ad9555f2df2c5f8a633e0995fa3e4cbb6909e21262ad76bafcb59cb35ae56458f7735d9ae91d3cdf2b37c4fc93ccbe5e6b5df35aeff912b76f97a43385e4448b6c230f16f9abca78b7cdd0c92275423f6a16e03c1d136205785a4e67d5f947cc856cddfcd7e63a42fa717249931f9d921907c7ccafe1f92e8bcdd479ba4f32af166b1b76118bca99d048505975e5196bcb5e2731cc73105675398e621ab21ab1284bb17261544ba31ffb07bd80914ec2dba5dcbb950a6cc0db66d79956d3ee67dccd628c87f421fd087f43ec647fa095297b231ff421fd08ff00437bd8ff0060ff00405a5ca847fa11fe847fa1f533eb64755727fdc960d9e054ed4c7357061524eb3b0e89778c39ccda88649486e7d1bd3e947de8fad1cd27d88fb11b4f46c3d11fc825fa923f80e639e28556d31d13a44e91c4493fd014af24ac6c7d9b5f67d4cfb98ca6d0951c07ab37f8f54c26e53a2c0fd28fa51f423eb42a5911fe647f991fe443f98829e810bfe023fc8ff00f365fa2a4a352a63745253a5b4393d1b86e1bc4b29c36dcd99b32428202836a6d4d81b627b5636c6db276b3355f0d8decc9cc5e1fe80f9af9df963e715b23cda1253c8dedcd7663aa74309c2d606d92c39d55f022dc428fc14f6fe0486efa63f1b374d665d6cab4cabd7911422e9a2cdd3cd1ca21fa8a380592dfa2a3926144921069f3d85e80b08286f216510b43611b436113344405446c8db1b436c598ae6d0d864bb3122e921be3270312124748ea66a79b3f7335f15b24db8ea2c709dbfc81d33a035215d8a11fe38a8bf77612b6f96a5fcf83e8082d7a13489ab3c3bbf8bb43325e513772fac9542c3f8b11665fd0ba8cc63698b4207721adb54f3559bb5b7cae92c567909b64c54194782c7f4f21855c25f0bd21a2d34cbe3c8ad96c7ce6b731c1445b2c646db9fc125ee3f340ec66bc2b2c54d6f468f60bf658e0ede649798a2498b1d61c13ec95046e6756277c1d9894e6b798684d8d94a617ff0042742751a49125620516821c20b551e3156802ae1dfcd4af8089a6e1a6c48496d83c4350d784925b8d5e41d70657a252b25cd94e899631aa403f6418d8bd052424349e412426ba4e326d847088651bdd22428dc09ad3fc01e18b79be516677159e3c1c517e0eccb5ff008f6bc2b3188d2929c8c47cbc8b02c5b1a37a084356c268c378825839b35099ae2d6b79e05a68121795a8a312749175e821133bd10d6b70b9c67977912fd6839c5c5fa2d04ad08ae1b426ed2732ae814bce70c2d37d87de1ba83244d3a3c2ec83707fa41204b74d8d4bd637e55f84587cfc2e11bb50dd3013a26761968e23a02ce82c215a854cb1c4b101e2ca688e5943ec90f03ad2fd09458af92e0a414b1420d510ce01336834a91bd10d325325f2c66bc2b2cf5c4a98430d59cbb1b9cb2499c96b80b37c6ce1597184d3cba6031b0a18295fc952577637e75212d242dcde063e221ca901a1538c1924db275bc54eb9438d0da7763da35207015b9288b99fd3fbb1d5356a47d7f2cb43f0234ab67e92095d6a88a3ca69374d0879f84b419d543dbb96167362c93566c3e8136a902a4f6d41639f7844aa561307c28f4478075b13a18d3956448ed08bba85b2d4762d659f126042a182550928b63dcc8e98de0494f2f074c752c7b725c8f14d3a9326f00a359916194f23c46fc0a50f0f24964cd68789c16782b21148bf9c962e456c7646ca274a1e7a5068ac7e87e87e87e873454de55046491a457691285e0b06e12b5ad46bbbd8507e75c6b8e62226e7529550a56c9926f0d633746e8dd1bac1146ac8482610ebbcb1528de157090783ba275149ca50a5a9b74fa989617b0940adea2d497b8556abb89226751231d4b57c1fe8085252345cbba2e915739884ad9a8bab8245c7fd01257a9a432a26848acf989e813b9f433e967d2cfa18e862ec54b0849ef4e7721d3b8781a436c10c2887102e4c57e0aa2935c6bb703d492d6d47af37787fbd08bb1d0b1d3e846c3d1427c361b453f426ff00ca0720dad6c82e74095a197f16312ab3c502272f829604e19784f445bef0382d1012433a04e81249d21ac16c710c47764fd9be3a3161148c62d4254d1f14150a67882a286c591f435d5724dfaec4ce93e58a1098777a092f899e50c930e0de88f29751036decda7b369ef2750e866ed0e5166254258db89e4687c88b6c6250a30744548cb8439ee91338aba8b22e85967016382e1d2d474f655816ca4b721209ca264a4d79122568a8abbf1834216bd7b76284a10fd84ccc0936bc5d06cd584bea2f8093907a474f2d8c8f5995d47d276db124b27c8f37cef2cb1e508951534624b0b4d6dbfe0a6ab9c2e7197565a652b28748b83f44e85dd3d8b3c3a8cb7104d1a9a07e0ab5349e1a474a5791f4295a845684b1614ed22678ab2bba506b68a49f6a6a51948e4b60f0e074c3cb91be28ea453d7f10eb67de7127aaa884b7e0366abe4d7a07213babe2b7eac4a9ab3ac35ccea7caafce1d03763a10cab296744bc6b03127748ed9fbac8ab361224422274a76135db2c95aca50cd03c2a4f6c831a292539fe4780110da0d23f3e593610d793a87572d2dc0dcbc0b5e8c8cb395d9e6ac644db1b7fd5454c5fd5983a5231f7c0904a31bf1253087708bccb2231e048124bc1b088a92ec34aaf05edd59d017b9259920e896a84064921b2339d469382b8fcd47625a1e053bb3681637fd73bb32c66b3922280cf08d91427080db259b782ab7c1e9b7418cb31416b824d4d869664aca30de2a095259e6740eb1b6aa62df511325b0eee584bc23b38cf7e8473db0f910b3432f4ce8e5b7816081647679a58f34e866dd53ad9591a3914d4c65efe6e8ac6975883342c7c16c9a454a87869eed9473d5216d3496a548a9d16e79104804524d4d4b20b6144954fd4208f549e0b6ec947c8df7b3eb67d8f2dc5be6f9cb0ad23c212e6310ee4440088e62e5fd760cd220f1a8924a10928aea349b96c71645285ab46859afef188b242505333a07589bbab842b64ecfe1748914c3e515965e99d2cbd63ace5b9c667738cc3a47472f48eb655944a62b5072787ed9f93f9920b9dc8d625413ea1f89b0db8c22925d4ff2475d8ea82fdba1b139a06d14d7ec265d4d8086cdb9bcc686e454bad6d241e24c50450ffc7f204202645aa330733a8b855ae41411b98e4ea0feacb388a30fa309c9d63a47e8132f6ff23c3e5599cba99245b8f439a572bfa8a7872f58ea65e81d0cdac5f8b408bf5b36110b4c9dc20708e54f0eb1686d0a317671822af96310798c2e16b2ddcb2731c87313d63a6b5cdc9b8c88575c4ab8509111a5528d4751d2a2539896071cc1c911a046811a0469fa268c5e4a93746e0685d7e4df1b8c03718048d63a01209e424945950fa990fec43fb11fe847fa09489753fd8379fb16ed611cfece7f620353716ffb39fd9bafd9cfece5f64d54d06d2bdb539bd9b1d9cdece7f628d32df079f5b539fd9cfece7f673fb25ee7985b539fd9cfecdf7ecdf7ecb89fb226aeda9fec11fe843fa10fec2a7e0d5941f31f5b25fd0fa59f43229cd5f2268ffb1f432581641e77564f2955d4e0d76b9bf3726e4dc9bb2ed9b918f31389d4d4dc922222a240080273726e8d54dd0a0ec478ef847988d82904b013a3fe82ab50ff00b06e3f66ebf7f8c74889b8c03001db59f18b7a9e49c2c658cdf233758e96338bfb737ccc1ba4951f978f418ce45632f087d6cbd03ad97aa75336b196cf0c57e1879877b0a92c121163ad65ff008cd5991fe1ba1996be06a1b903db04890966ab17679346955cbfc33a474b37773741e0f28452d5e36f166ba2b2cb1cc65ae3224740eb65e8963254b9fa15965b783158f67f2576171e637cb03249bbe4f3e32bf61646a32cfe0f3e31d31bf83f591e73d58b2ef383fc135c3a2757218ec770797ac78c0f5b24f2cf3878197f942b0b176783a03c9d73a19a58e32fccb077ff00d614bdf2d5845658acb5ab9164b85bc9ffc400271001000201040202010501010000000000010011211031415161718191a120b1d1e1f1c1f0ffda0008010100013f10d311d07665466f0bb920b18a28211f5b308ac58de3162e7bbb103e6000161db768d3a0fe8c3c86731610d1d99f95d1ea54c12a6118d3e660b84a98c3e63a61d8d33f42144dbf427e5cc7d3a1a65d379dbd1cc3de8cc9af5b634da797bb4bd0212e9785a33895a6dfb6cc75a62a5de9cee44a89e23b634657d861a7bd4fcf3368e617529a9588c7c404bd6e2a6e86777aabe673292ff436a76086812b56593d04b9ccc12ca8eb36f8828f1b7e9c07cc5dfe80bf940de6cda2cce9c58d0b1631616f7be604306524ad1230a354955edab3708ce19f9dd181a8b945b5d54bd39622d6967d4d1b81a6ee60e1e10219b869cd43f7e161e20cbef9d2c44fd1db4369c4cbd7152aa1c46a56ec45f49af1035cc3657ca634c6bb4f6973337d36e667e416119983a674b930970da5d1a2b2ad94bc4742aa35a03c4b952a5c6806e8136ba804a250953737301310944768c49dcc68ca8eebe440a22b2c964f9941ef772c398a772cee52778830deb9cd1050e2d14de3a6e6f2be322b5dac26e66c81ee8c11d1a26f0965f39793f412f0ca06ddd362634f997e670c28471a0c398b28a1b5617ce832c84afc087e8bcd4fcfca8bc7463b833bd9bb2ba944c055be9f24ee5928730da79fd9a399d6973739c4bb8f170b9b659bca6be25972efd4b74ca554559e3458afde4b8efa5699874daad12a3028e89f73119c68c57c06017004ad13653611e6bdb4e37845d151e85d1ad762c05872cdb4df4147a17465a77bbfcf2cff003c5eec9777fbe27fb664ff00bc3fb38b44b5cc2250e7172c5160c30590f1dfdcb5ff00da6e8bb3314a0bc18a23cea59e4a3b9ff9b37603e65ffb25bfba5bfba1b35634c157fd23fec95e7ec81fee9581b0901d045a30cb66cba635788298805dc962983882fdf48a3fb24f0bf28d1ff7c415ab86b79637f9d1d1ce36cd62454df74cb923287f752fc7d9a3ee7ee9323102d3b43d582e88edc48bd88a9bcbbcad04e7b6507f14289116c2536f33b0dcff00150ef3d90edfd2791f49589fb304182516aee3fe1cf06084048e9953b3ab8c56884febb2bfaecc79fab16e17f56377dcfc218adb3be58fcb3fda65bfcc88b8765808f14cdb20edc1ebc32a8a1bc44e0a107fca47458efd0f36f34556658fe297931df4954a91967ba305eda118042237e0468bdc566ccae2c4a77fb52898fcbdd0fa3f2a45adb8b081a1c89eff00ba797f4cf2fe99ff00b8c78c0259c676ea62628cd70c1025ed8ff6b3fd79fedc3fbf831b047fa116fe79925b320c68fe50d39998dca37c8fb9f524996278d2b06f3237297171f28cd2704ba324bd6a2cbc61a9331662a328eb473a5e8b3f4980f52f4b97914968f46f33ea9fb418b2f45e3425db33620d4b74c3dd9f8a8b1185b89eb9854bb17362c8c21476dd209441ff004bc47692d54da34422a0c8062db4ceda2bf8d311a34344bb382d6f4768edba097e21aa72c8f610f582506c4a86944a5dd1944aea54c465cf1898594131286412d07c34794de06560a0516dd83f7874a100106a906e6c11a80e8883825186ff006461812e78d3fca967f04ff3b4e41eaba8aff14ff1a59fc7374c1ea2f66308b6975684c520db432c778ca4f77198b68063174553c0fcc13360086fded6be35e253db90ad095311acfebd019b926c6fb12b4519547ba04a958cfc46999571999dd8a8ce63b1399f909f8a8e67ec94d3ee19ac1ef56f0a83c027bcb074e662ea3830ce4cc92b1a19be463d7e08ebd808cb895e739d2e186a5b1db790d562142d0b444460f71782a016d1a5b908a0cab644b063650f44c5cb16aae5dbba5454a37c9f12e7ce8c563c868ba5c599771c6a0aac5bd293cb30420a71950f60f392e1e1467f9660da30aa2dedeb6460fbb9cb1a700dd612d2b7975a12f4cc3d8830d798b860cddad77bd1873f421544677af9d154667ff08e8938da593b2b058999f945f54e6307500b1b71a1fa30f4bf4d4c293a9f8d36d28a94316dd989634060364ad3bd09f8a998cb8cbc3129bb40f98c17551ebabb4fc643de0fe90459a2c401602153f118055db37de62569b54def7317360b62ec941c01bc2710bc8c7a20b600a08743ac521d80d91c47108291c83b4c43d92a0cfa68d31ab7b77c4cf9b947f00952fd6e44090c9dc293c95e8a4a793288e8ed0df794c4ad2a3b1d0c19bb566574051c296d48a50e31c10280030100a4b859182c34d51ee2eee8a66665cb5852d477a8b228abe36215400942e1cda2f4b964e1b4bd1963f130d0de711c54dcb870f59b0f5fa2a2475802dfc32ec18b3c70e3b03620fa257e465dc77d2f45bf80c327e9a9fb5d4d333bb187a0d16c8f36e1d23b7dfe6dc41f1b197f492e1802fb31f315b24e630f58e5f53e7880dc2f6e68dc80006ce35488031077f3af70d9136457f501bd4b914b737617875321f412e4ac9e4a9f929b884631c458089d9829f3377de87723e789ed9457713248daefd8c8c77e5b6f0461819613be61566deee77a82790b1255436fe7fa0c3684b016f03ff1329862933a7fb50463382c1044ab03f8e66c7dc28807c4c1b2247eeb9cae7a9733a992769a2c2ab462fce0a9aea43581bb05b7fb187a3e6f102001b01135dc2608cb9889b6062001805153bf622b10389bc2d99dd0d56b4648adf894b58c42b37c3185a16d26194a0a3bdca34259000bb41324f8408e231d4480788e84de307c68c77eafd0d12bcca53e2585739232acb932795e5a348e65c3ea5b203623a13e659f0c16025cbce944c4d945c74c1a2148fcd84ccad3a1ed805215678cd9d0e8448b463e23d271184dad5bae108adbc28d9e05cbf30b21304b510194071982274a9cc54000b877d54fa2ec440cc1c9ac83d20e6c324aa045cdc1ab5e58a1393d331f54d88e26346117ff00599b2baee0011fb2c566a480c3b621c5b823573292d61c00987be2332b3326fd465a0dc042b7e4c6e1a5ee58d2e883988a8fc8a08cf9673bd4042173049f86126fa77da63ee3682374b1dcff007028a89c7621362c41b82c9771e160d7038bd62587adbefe6c40b131f22ba5b98c586c4581cc5e8402a323f96f686400068b320f78b33bc058aa605c7dc74106fa8ec42555df961c14d884def8312e3044a00ab30d5ee238095521bf3564ba36c5a5441f84a8824b9249cb76aca25d8830f51d3fd0807f3694cc039547a06c97d26fa48769f69ace356a643da62717bec98fcdd44fcd33f70e66a8da25787d45ba169d4ae54aa3ce65184e12a2ecce2487f8e3fe180b557d0972001c54a1a201ba106debe021fe2877fe89872fea59b7d040958c15ee7f8da3e2992f341aaf0cd3118cc45b7fbe6fd8677688a8572d5a20f830aa2d47c5fcb236b0968e65edb82f911315770b5c43bb57768a06403148e1baa56c84238b50de5296ca2da04d49584036af69cac7432b215e20282f08b11a998558c4a14ed7a097e721b60efb42e6d1998ce2844f116370632342274383b82fb5e1aa881406ed5a962d4566d7df986d906f2da2f726f0c7337cade67d6424224e99e463fa922043eb23e8798401c91495f54e8faa79cfc103dff04576e6f230c036ed71e333d3098a86f5b3db1e10216f94012ae51c5a3883b8dec853d8f742570b0abdea17549179594bddc802df0628bf5ac5dc7a7f29749c5abcc581a9b6f2b0f7602800c062367fdd983feecff76371fbec6836998ba7fd980fe561564f9ac5585dab7c31d438185ddf68a9c4d94c590d6126a56d9a5906e880acc215955d56610017ce700ecbaba3c6481c81d85032bdf4110dc8a8cb655b63dc84d4b4a21531cf6c7e9538cef2d350000cb973f251e4ae1add4b97152ce07dc7e63319173bff00ec3458b89525daf1d7e090006c144b63b17ab96db858e7f144270c16cee4f49b13bda78d7d70753faa355f0958a25b6874976b765bc1179c12e61bb4a84e419f70545681ed8d32b1a6c774d89641d43476dd6602c496e401b000411433d20eb476fb3333990b6641286d85e0b963a1e21edb940f89b389fbe8eabef0d5cf18fbd1d88844dde196b2f8ed70bb5082e2180001b069411ba66425b1ae23b5cdf7b68c0d12104c29080209aa5aaff4dc29217ca633711376ebdde8e96b650ea5c4fc91504805f62627e107e7dc1958da3eec7cec585000144da7700c112a63198d4dbf3ee2b545704c0aa6c88d244bc0c437cf04b2b728838879896f676c602b4169840fbcadcae3d090f0a3e330acc371dfee02587c2043eb18ed73b31823b4363add420335410fb0b80443a844e8c0b10ea54a616ba6086c3ca2be4c69b68e983e2406d70a6eb8d50d90e12b465bb550218d792194cca78a8827282e0880b8145ab13fdaccea24ab89d4c0ce4425688e251cce37509f02558e9405cbf93615f7e5dda528f4ef123b00b32f4a10b0b71cc73d08d101043a973bb6255aeb211d1df7907b8ea7e02824fc6630a5edb12eed44d85bd6b865862eaf5c532c67bd279a53fb98463d4d5a797308de23305f29f848c2312a23d94a4db8c10b6012b4689b1987b18c769b68820f0f6c59bcced376e2fa1815897721242fb3fba0300022e952f9cb1ebd6db8b15682a54bf6a94aeb5b6f2400a0025c2228ecc131bc6500f54b8550d73159b8160d54bed96f4087de0cbc8cb61ca335312cf85c965a38815679d80593c6ec67bce7921d2ac4da1e7adb43822de6d2918432d51e5ea242133256c93346c1a2c5f87f40c6a6cbc4cbc4a423bcdcd727ea4844612151c0fa6016717a1848280b993a861007d7e48d6254b81089cd132c18b865a2f68298cea10af50e0c1cf7a5972e5101d0cb3273066e62d9b7e861f4231adb92d2eebde08d03b8140e02660af5a675bbc40580f447f6a5c5959e58621b36a5f9de0cdf80706a8d47dc2825e8bc14e5fe0cb9cc656665e250886172098976ccca8c9c3aab97a72ba60924c40661951b8b41f4e9c6988fecc0fc48cf8812a53596f509b691f3f33886b441eec2350d28993741a5c706e4c8620a012d30a9e672469291167022fcb912be70262be2ac97370500a71684aa0878214a25a1a95e0dd8467d015152bba2843811e22edbe897ed4cbf6f958af908b4d8f843b3f4876be9ae23b24c7a8e118e0d4ddba0cc8caf3477054da14fb21140cc7c68bb629ac0004d8f0fd25f68ba48fc15673834141b10b6d361b91ed4058af28c870ca58774e481c83b052463b6d088419094c00b00434b23389407ca7e063dccc8000ada635fc6cde3de983398132f63a313e6cc64728699fc5381d462e6626ee6630fe1f42662d137be73e825f0459965d10bdd8c3da973d1ad634c17ca5fe38f6c22c2a2c9e73091bd7898458fa117f413102c8c3ecc387c930801e9ff00094f2be9ce677f31e9665bd79fd8e2e2eb53bfea63c0be98f7d6d59694b0bed1bbd03312f917695d04b7214ec80518751b24fbc5bf7ec14cc8b95030c0900dd627701cb15721602e8812813d1018955d40623688744a2511c4ba8f7a0475a8cb09785368caba9528097714cd788da2c83e8a8cb738f64ba26787b5c1b96ed4323dc2129214bbfd40035abee40d262d988ed14d5952bf2e091c218d2ed23ebdd3323acde5d686a54b6a32ebe12ede899cd904658cbc6f15959ccd98b2e638a7e0b9b1a21272cae8de5cbda0bf73e60197728828bc261e61ee0f163115e58c96cfed99999b297a592ee108bca1a5729a1a30f623c3d430c3a3b460af7953d0fd2f9e65ea254c681705d79ac0c6952894e88eac21fe140384a251310f1f08c315782117eb250cc68ed3f1e00b50896cdcb9729a41de4de828206883d73f0232d988c62a3bc2ebff00b9a31cd823712d3a5b33cefb9d0bee787f704121ce63ef5cc2322f995b45bc7643fe22e39aab7634e630cde17552a9ff0038ff004b3fc59fe54ee91c54dba89ae856311ff2cb777f44f37ea7f884cc390f04c82518a640977f19a79a53912f2b8174cbad024c7ff79fe8cbff006cb7f6cbd3fbb3b9e367787366735cdc4689dc9f9d06dbbe7254d91fb117334fb47c96d37f33cff747fde8925aa655c307cfdd1ff6a097fba9e7fba3feb462c1ecdc4a78b94cd93ec81728f1fd933ff2448fc8bb42c9e2e53ff653ff00653e37b4ff00dd4128dc2f758f65b236447b7ee9e4fba5457e543fb941578ce544f2e1729fe8a1c7175e9a467399b909c660da1985d158da4b44b6680c450132a23fbb83e7ee87f631ecb30677213d975e88618049b9b1c8a96ff091feac957f113fcb234e3e823c0246943763fd612c298e88eb4ac2bb987fe04ba59f0930222ba236a387ab67f91a3380ed6f186cf607ccdc5bee318cd561cd17cc7fb79fecc69bafcc05488172a61a88fe4422bfd14a251b4a251d4a05e4d1d2f4a5cf698872081e66660ac859076f924c12c9f232b8b06525917d51c6e12cd2cee7e2411c69709f968beb436b963abe623e8cb989764b971bf0c2f3087d0622864e5ea2122c2dc68b1b59be261e921a6399be65bbb2bdb107a13b854b971d7b72ff001b4db21369c47f766dbc271067533a135e9230c4b8c565dc0d88c129d329aeaa366998b1e2e9fe8735571887469df5032382b45f54fc5d0c68c6ff00457307301175dacde7cb1d4251a54007d8952a70449503e54b3b449e22310daa5490744ddd307f09a3376989f8936fdba570402159beda5627efe5466fa8fbd33f5a3b6f2b4c4dcc9007a330cdf1a56f3899141310d86a94a7edba5131f34b98d6d0217cce660be48aaee8d2a8b7e09b12b10b3c458bec20af5a2103a8ce4660fe53f010205cac4a98c9f82686fa5a62af323f33f18fd2e8fb913370474783ca152d8591d16cce3f432987b458e99c58eb84ebf04146381065cbb83b5d25cb97a6d3b111445971733eaa2d45273a71a7ed6338d0ece9f83a57fe3750d4cfcc74e5d1c1a6c7d33f39a6e21b10d3f2d87119c420d27f09a7508dda1f8d9b09c308730dc9f90fd0fddfad3f6308306efb8ecd1ff91dcdaf44e5ee3c7b61b4fc6885fd3fbcfc14dc470be88711db4cfc04e61b3a773f653f0d19d4e7e75a363d13727109c93f6b0d886f38d399f96c7723a75398ef93686bdcfdf4667e99c6bbf0b4ff0085afe01fa19f8f9b09c68cfc575e467fffd9);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `archivage_dossiers`
--
ALTER TABLE `archivage_dossiers`
  ADD PRIMARY KEY (`id_dossier`);

--
-- Indexes for table `commentaires`
--
ALTER TABLE `commentaires`
  ADD PRIMARY KEY (`id_commentaire`),
  ADD KEY `id_dossier` (`id_dossier`);

--
-- Indexes for table `commentaires_archive`
--
ALTER TABLE `commentaires_archive`
  ADD PRIMARY KEY (`id_commentaire`);

--
-- Indexes for table `contacts`
--
ALTER TABLE `contacts`
  ADD PRIMARY KEY (`id_contact`);

--
-- Indexes for table `destinateurs`
--
ALTER TABLE `destinateurs`
  ADD PRIMARY KEY (`id_destinateur`),
  ADD UNIQUE KEY `telephone` (`telephone`);

--
-- Indexes for table `dossiers`
--
ALTER TABLE `dossiers`
  ADD PRIMARY KEY (`id_dossier`),
  ADD KEY `fk_destinateur` (`id_destinateur`),
  ADD KEY `id_utilisateur` (`id_utilisateur`);

--
-- Indexes for table `dossiers_transferes`
--
ALTER TABLE `dossiers_transferes`
  ADD PRIMARY KEY (`id_dossier`);

--
-- Indexes for table `fichiers_dossiers`
--
ALTER TABLE `fichiers_dossiers`
  ADD PRIMARY KEY (`id_fichier`),
  ADD KEY `id_dossier` (`id_dossier`);

--
-- Indexes for table `questionnairedemande`
--
ALTER TABLE `questionnairedemande`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_utilisateur` (`id_utilisateur`);

--
-- Indexes for table `reponse`
--
ALTER TABLE `reponse`
  ADD PRIMARY KEY (`id_reponse`),
  ADD KEY `id_utilisateur` (`id_utilisateur`),
  ADD KEY `id_destinateur` (`id_destinateur`),
  ADD KEY `id_dossier` (`id_dossier`);

--
-- Indexes for table `reponse_archive`
--
ALTER TABLE `reponse_archive`
  ADD PRIMARY KEY (`id_reponse`);

--
-- Indexes for table `traitements`
--
ALTER TABLE `traitements`
  ADD PRIMARY KEY (`id_traitement`),
  ADD KEY `id_utilisateur` (`id_utilisateur`),
  ADD KEY `id_dossier` (`id_dossier`);

--
-- Indexes for table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  ADD PRIMARY KEY (`id_utilisateur`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `telephone` (`telephone`),
  ADD UNIQUE KEY `telephone_2` (`telephone`,`email`,`username`);

--
-- Indexes for table `validation`
--
ALTER TABLE `validation`
  ADD PRIMARY KEY (`id_validation`),
  ADD KEY `id_utilisateur` (`id_utilisateur`),
  ADD KEY `id_dossier` (`id_dossier`),
  ADD KEY `id_reponse` (`id_reponse`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `archivage_dossiers`
--
ALTER TABLE `archivage_dossiers`
  MODIFY `id_dossier` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `commentaires`
--
ALTER TABLE `commentaires`
  MODIFY `id_commentaire` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `contacts`
--
ALTER TABLE `contacts`
  MODIFY `id_contact` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `destinateurs`
--
ALTER TABLE `destinateurs`
  MODIFY `id_destinateur` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `dossiers`
--
ALTER TABLE `dossiers`
  MODIFY `id_dossier` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `dossiers_transferes`
--
ALTER TABLE `dossiers_transferes`
  MODIFY `id_dossier` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `fichiers_dossiers`
--
ALTER TABLE `fichiers_dossiers`
  MODIFY `id_fichier` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `questionnairedemande`
--
ALTER TABLE `questionnairedemande`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `reponse`
--
ALTER TABLE `reponse`
  MODIFY `id_reponse` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `reponse_archive`
--
ALTER TABLE `reponse_archive`
  MODIFY `id_reponse` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `traitements`
--
ALTER TABLE `traitements`
  MODIFY `id_traitement` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  MODIFY `id_utilisateur` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `validation`
--
ALTER TABLE `validation`
  MODIFY `id_validation` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `commentaires`
--
ALTER TABLE `commentaires`
  ADD CONSTRAINT `commentaires_ibfk_1` FOREIGN KEY (`id_dossier`) REFERENCES `dossiers` (`id_dossier`) ON DELETE CASCADE;

--
-- Constraints for table `dossiers`
--
ALTER TABLE `dossiers`
  ADD CONSTRAINT `dossiers_ibfk_1` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateurs` (`id_utilisateur`),
  ADD CONSTRAINT `fk_destinateur` FOREIGN KEY (`id_destinateur`) REFERENCES `destinateurs` (`id_destinateur`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `fichiers_dossiers`
--
ALTER TABLE `fichiers_dossiers`
  ADD CONSTRAINT `fichiers_dossiers_ibfk_1` FOREIGN KEY (`id_dossier`) REFERENCES `dossiers` (`id_dossier`);

--
-- Constraints for table `questionnairedemande`
--
ALTER TABLE `questionnairedemande`
  ADD CONSTRAINT `questionnairedemande_ibfk_1` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateurs` (`id_utilisateur`);

--
-- Constraints for table `reponse`
--
ALTER TABLE `reponse`
  ADD CONSTRAINT `reponse_ibfk_1` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateurs` (`id_utilisateur`) ON DELETE CASCADE,
  ADD CONSTRAINT `reponse_ibfk_2` FOREIGN KEY (`id_destinateur`) REFERENCES `destinateurs` (`id_destinateur`) ON DELETE CASCADE,
  ADD CONSTRAINT `reponse_ibfk_3` FOREIGN KEY (`id_dossier`) REFERENCES `dossiers` (`id_dossier`) ON DELETE CASCADE;

--
-- Constraints for table `traitements`
--
ALTER TABLE `traitements`
  ADD CONSTRAINT `traitements_ibfk_1` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateurs` (`id_utilisateur`),
  ADD CONSTRAINT `traitements_ibfk_2` FOREIGN KEY (`id_dossier`) REFERENCES `dossiers` (`id_dossier`);

--
-- Constraints for table `validation`
--
ALTER TABLE `validation`
  ADD CONSTRAINT `validation_ibfk_1` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateurs` (`id_utilisateur`) ON DELETE CASCADE,
  ADD CONSTRAINT `validation_ibfk_2` FOREIGN KEY (`id_dossier`) REFERENCES `dossiers` (`id_dossier`) ON DELETE CASCADE,
  ADD CONSTRAINT `validation_ibfk_3` FOREIGN KEY (`id_reponse`) REFERENCES `reponse` (`id_reponse`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
