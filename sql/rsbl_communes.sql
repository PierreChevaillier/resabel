-- phpMyAdmin SQL Dump
-- version OVH
-- https://www.phpmyadmin.net/
--
-- Hôte : avironplsi183.mysql.db
-- Généré le : dim. 06 mars 2022 à 18:55
-- Version du serveur : 5.6.50-log
-- Version de PHP : 7.4.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `avironplsi183` (serveur)
-- Base de données : `resabel` (local dev.)
--

-- --------------------------------------------------------

--
-- Structure de la table `rsbl_communes`
--

DROP TABLE IF EXISTS `rsbl_communes`;
CREATE TABLE `rsbl_communes` (
  `code` int(5) NOT NULL,
  `code_postal` int(5) NOT NULL,
  `nom` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `acces` varchar(1) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `rsbl_communes`
--

INSERT INTO `rsbl_communes` (`code`, `code_postal`, `nom`, `acces`) VALUES
(29001, 29560, 'Argol', 'N'),
(29002, 29300, 'Arzano', 'N'),
(29003, 29770, 'Audierne', 'N'),
(29004, 29380, 'Bannalec', 'N'),
(29005, 29300, 'Baye', 'N'),
(29006, 29950, 'Bénodet', 'N'),
(29007, 29690, 'Berrien', 'N'),
(29008, 29790, 'Beuzec-Cap-Sizun', 'N'),
(29010, 29400, 'Bodilis', 'N'),
(29011, 29820, 'Bohars', 'O'),
(29012, 29640, 'Bolazec', 'N'),
(29013, 29690, 'Botmeur', 'N'),
(29014, 29650, 'Botsorhel', 'N'),
(29015, 29860, 'Bourg-Blanc', 'O'),
(29016, 29190, 'Brasparts', 'N'),
(29017, 29810, 'Brélès', 'N'),
(29018, 29690, 'Brennilis', 'N'),
(29019, 29200, 'Brest', 'O'),
(29020, 29510, 'Briec', 'N'),
(29021, 29890, 'Brignogan-Plages', 'N'),
(29022, 29570, 'Camaret-sur-Mer', 'N'),
(29023, 29660, 'Carantec', 'N'),
(29024, 29270, 'Carhaix-Plouguer', 'N'),
(29025, 29150, 'Cast', 'N'),
(29026, 29150, 'Châteaulin', 'N'),
(29027, 29520, 'Châteauneuf-du-Faou', 'N'),
(29028, 29770, 'Cléden-Cap-Sizun', 'N'),
(29029, 29270, 'Cléden-Poher', 'N'),
(29030, 29233, 'Cléder', 'N'),
(29031, 29360, 'Clohars-Carnoët', 'N'),
(29032, 29950, 'Clohars-Fouesnant', 'N'),
(29033, 29190, 'Le Cloître-Pleyben', 'N'),
(29034, 29410, 'Le Cloître-Saint-Thégonnec', 'N'),
(29035, 29870, 'Coat-Méal', 'N'),
(29036, 29530, 'Collorec', 'N'),
(29037, 29120, 'Combrit', 'N'),
(29038, 29450, 'Commana', 'N'),
(29039, 29900, 'Concarneau', 'N'),
(29040, 29217, 'Le Conquet', 'O'),
(29041, 29370, 'Coray', 'N'),
(29042, 29160, 'Crozon', 'N'),
(29043, 29460, 'Daoulas', 'N'),
(29044, 29150, 'Dinéault', 'N'),
(29045, 29460, 'Dirinon', 'N'),
(29046, 29100, 'Douarnenez', 'N'),
(29047, 29860, 'Le Drennec', 'N'),
(29048, 29510, 'Edern', 'N'),
(29049, 29370, 'Elliant', 'N'),
(29051, 29500, 'Ergué-Gabéric', 'N'),
(29052, 29770, 'Esquibien', 'N'),
(29053, 29590, 'Le Faou', 'N'),
(29054, 29690, 'La Feuillée', 'N'),
(29055, 29260, 'Le Folgoët', 'N'),
(29056, 29800, 'La Forest-Landerneau', 'N'),
(29057, 29940, 'La Forêt-Fouesnant', 'N'),
(29058, 29170, 'Fouesnant', 'N'),
(29059, 29610, 'Garlan', 'N'),
(29060, 29950, 'Gouesnac\'h', 'N'),
(29061, 29850, 'Gouesnou', 'O'),
(29062, 29190, 'Gouézec', 'N'),
(29063, 29770, 'Goulien', 'N'),
(29064, 29890, 'Goulven', 'N'),
(29065, 29710, 'Gourlizon', 'N'),
(29066, 29180, 'Guengat', 'N'),
(29067, 29650, 'Guerlesquin', 'N'),
(29068, 29410, 'Guiclan', 'N'),
(29069, 29820, 'Guilers', 'O'),
(29070, 29710, 'Guiler-sur-Goyen', 'N'),
(29071, 29300, 'Guilligomarc\'h', 'N'),
(29072, 29730, 'Guilvinec', 'N'),
(29073, 29620, 'Guimaëc', 'N'),
(29074, 29400, 'Guimiliau', 'N'),
(29075, 29490, 'Guipavas', 'O'),
(29076, 29290, 'Guipronvel', 'O'),
(29077, 29880, 'Guissény', 'N'),
(29078, 29460, 'Hanvec', 'N'),
(29079, 29670, 'Henvic', 'N'),
(29080, 29460, 'L\'Hôpital-Camfrout', 'N'),
(29081, 29690, 'Huelgoat', 'N'),
(29082, 29253, 'Île-de-Batz', 'N'),
(29083, 29990, 'Île-de-Sein', 'N'),
(29084, 29259, 'Île-Molène', 'N'),
(29085, 29980, 'Île-Tudy', 'N'),
(29086, 29460, 'Irvillac', 'N'),
(29087, 29100, 'Le Juch', 'N'),
(29089, 29270, 'Kergloff', 'N'),
(29090, 29100, 'Kerlaz', 'N'),
(29091, 29890, 'Kerlouan', 'N'),
(29093, 29260, 'Kernilis', 'N'),
(29094, 29260, 'Kernouës', 'N'),
(29095, 29860, 'Kersaint-Plabennec', 'N'),
(29097, 29400, 'Lampaul-Guimiliau', 'N'),
(29098, 29810, 'Lampaul-Plouarzel', 'O'),
(29099, 29830, 'Lampaul-Ploudalmézeau', 'O'),
(29100, 29260, 'Lanarvily', 'N'),
(29101, 29870, 'Landéda', 'O'),
(29102, 29530, 'Landeleau', 'N'),
(29103, 29800, 'Landerneau', 'N'),
(29104, 29560, 'Landévennec', 'N'),
(29105, 29400, 'Landivisiau', 'N'),
(29106, 29510, 'Landrévarzec', 'N'),
(29107, 29510, 'Landudal', 'N'),
(29108, 29710, 'Landudec', 'N'),
(29109, 29840, 'Landunvez', 'N'),
(29110, 29510, 'Langolen', 'N'),
(29111, 29430, 'Lanhouarneau', 'N'),
(29112, 29840, 'Lanildut', 'O'),
(29113, 29620, 'Lanmeur', 'N'),
(29114, 29640, 'Lannéanou', 'N'),
(29115, 29190, 'Lannédern', 'N'),
(29116, 29400, 'Lanneuffret', 'N'),
(29117, 29870, 'Lannilis', 'O'),
(29119, 29290, 'Lanrivoaré', 'N'),
(29120, 29160, 'Lanvéoc', 'N'),
(29122, 29520, 'Laz', 'N'),
(29123, 29190, 'Lennon', 'N'),
(29124, 29260, 'Lesneven', 'O'),
(29125, 29390, 'Leuhan', 'N'),
(29126, 29260, 'Loc-Brévalaire', 'N'),
(29127, 29410, 'Loc-Eguiner-Saint-Thégonnec', 'N'),
(29128, 29400, 'Loc-Éguiner', 'N'),
(29129, 29690, 'Locmaria-Berrien', 'N'),
(29130, 29280, 'Locmaria-Plouzané', 'O'),
(29131, 29400, 'Locmélar', 'N'),
(29132, 29670, 'Locquénolé', 'N'),
(29133, 29241, 'Locquirec', 'N'),
(29134, 29180, 'Locronan', 'N'),
(29135, 29750, 'Loctudy', 'N'),
(29136, 29310, 'Locunolé', 'N'),
(29137, 29460, 'Logonna-Daoulas', 'N'),
(29139, 29590, 'Lopérec', 'N'),
(29140, 29470, 'Loperhet', 'N'),
(29141, 29530, 'Loqueffret', 'N'),
(29142, 29190, 'Lothey', 'N'),
(29143, 29790, 'Mahalon', 'N'),
(29144, 29800, 'La Martyre', 'N'),
(29145, 29790, 'Confort-Meilars', 'N'),
(29146, 29140, 'Melgven', 'N'),
(29147, 29300, 'Mellac', 'N'),
(29148, 29420, 'Mespaul', 'N'),
(29149, 29290, 'Milizac', 'O'),
(29150, 29350, 'Moëlan-sur-Mer', 'N'),
(29151, 29600, 'Morlaix', 'O'),
(29152, 29270, 'Motreff', 'N'),
(29153, 29920, 'Névez', 'N'),
(29155, 29242, 'Ouessant', 'N'),
(29156, 29800, 'Pencran', 'N'),
(29158, 29760, 'Penmarc\'h', 'N'),
(29159, 29710, 'Peumerit', 'N'),
(29160, 29860, 'Plabennec', 'O'),
(29161, 29170, 'Pleuven', 'N'),
(29162, 29190, 'Pleyben', 'N'),
(29163, 29410, 'Pleyber-Christ', 'N'),
(29165, 29740, 'Plobannalec-Lesconil', 'N'),
(29166, 29550, 'Ploéven', 'N'),
(29167, 29710, 'Plogastel-Saint-Germain', 'N'),
(29168, 29770, 'Plogoff', 'N'),
(29169, 29180, 'Plogonnec', 'N'),
(29170, 29700, 'Plomelin', 'N'),
(29171, 29120, 'Plomeur', 'N'),
(29172, 29550, 'Plomodiern', 'N'),
(29173, 29710, 'Plonéis', 'N'),
(29174, 29720, 'Plonéour-Lanvern', 'N'),
(29175, 29530, 'Plonévez-du-Faou', 'N'),
(29176, 29550, 'Plonévez-Porzay', 'N'),
(29177, 29810, 'Plouarzel', 'O'),
(29178, 29830, 'Ploudalmézeau', 'N'),
(29179, 29260, 'Ploudaniel', 'N'),
(29180, 29800, 'Ploudiry', 'N'),
(29181, 29800, 'Plouédern', 'N'),
(29182, 29620, 'Plouégat-Guérand', 'N'),
(29183, 29650, 'Plouégat-Moysan', 'N'),
(29184, 29420, 'Plouénan', 'N'),
(29185, 29430, 'Plouescat', 'N'),
(29186, 29252, 'Plouezoc\'h', 'N'),
(29187, 29440, 'Plougar', 'N'),
(29188, 29630, 'Plougasnou', 'N'),
(29189, 29470, 'Plougastel-Daoulas', 'N'),
(29190, 29217, 'Plougonvelin', 'O'),
(29191, 29640, 'Plougonven', 'N'),
(29192, 29250, 'Plougoulm', 'N'),
(29193, 29400, 'Plougourvest', 'N'),
(29195, 29880, 'Plouguerneau', 'O'),
(29196, 29830, 'Plouguin', 'N'),
(29197, 29780, 'Plouhinec', 'N'),
(29198, 29260, 'Plouider', 'N'),
(29199, 29610, 'Plouigneau', 'N'),
(29201, 29810, 'Ploumoguer', 'O'),
(29202, 29410, 'Plounéour-Ménez', 'N'),
(29203, 29890, 'Plounéour-Trez', 'N'),
(29204, 29400, 'Plounéventer', 'N'),
(29205, 29270, 'Plounévézel', 'N'),
(29206, 29430, 'Plounévez-Lochrist', 'N'),
(29207, 29600, 'Plourin-lès-Morlaix', 'N'),
(29208, 29830, 'Plourin', 'N'),
(29209, 29860, 'Plouvien', 'N'),
(29210, 29420, 'Plouvorn', 'N'),
(29211, 29690, 'Plouyé', 'N'),
(29212, 29280, 'Plouzané', 'O'),
(29213, 29440, 'Plouzévédé', 'N'),
(29214, 29720, 'Plovan', 'N'),
(29215, 29710, 'Plozévet', 'N'),
(29216, 29700, 'Pluguffan', 'N'),
(29217, 29930, 'Pont-Aven', 'N'),
(29218, 29790, 'Pont-Croix', 'N'),
(29219, 29650, 'Le Ponthou', 'N'),
(29220, 29120, 'Pont-l\'Abbé', 'N'),
(29221, 29840, 'Porspoder', 'O'),
(29222, 29150, 'Port-Launay', 'N'),
(29224, 29100, 'Pouldergat', 'N'),
(29225, 29710, 'Pouldreuzic', 'N'),
(29226, 29100, 'Poullan-sur-Mer', 'N'),
(29227, 29246, 'Poullaouen', 'N'),
(29228, 29770, 'Primelin', 'N'),
(29229, 29180, 'Quéménéven', 'N'),
(29230, 29310, 'Querrien', 'N'),
(29232, 29000, 'Quimper', 'N'),
(29233, 29300, 'Quimperlé', 'N'),
(29234, 29300, 'Rédené', 'N'),
(29235, 29480, 'Le Relecq-Kerhuon', 'O'),
(29236, 29340, 'Riec-sur-Bélon', 'O'),
(29237, 29800, 'La Roche-Maurice', 'N'),
(29238, 29570, 'Roscanvel', 'N'),
(29239, 29680, 'Roscoff', 'N'),
(29240, 29590, 'Rosnoën', 'N'),
(29241, 29140, 'Rosporden', 'N'),
(29243, 29150, 'Saint-Coulitz', 'N'),
(29244, 29440, 'Saint-Derrien', 'N'),
(29245, 29800, 'Saint-Divy', 'N'),
(29246, 29460, 'Saint-Éloy', 'N'),
(29247, 29170, 'Saint-Évarzec', 'N'),
(29248, 29260, 'Saint-Frégant', 'N'),
(29249, 29520, 'Saint-Goazec', 'N'),
(29250, 29270, 'Saint-Hernin', 'N'),
(29251, 29630, 'Saint-Jean-du-Doigt', 'N'),
(29252, 29120, 'Saint-Jean-Trolimon', 'N'),
(29254, 29600, 'Saint-Martin-des-Champs', 'N'),
(29255, 29260, 'Saint-Méen', 'N'),
(29256, 29550, 'Saint-Nic', 'N'),
(29257, 29830, 'Saint-Pabu', 'N'),
(29259, 29250, 'Saint-Pol-de-Léon', 'N'),
(29260, 29290, 'Saint-Renan', 'O'),
(29261, 29190, 'Saint-Rivoal', 'N'),
(29262, 29400, 'Saint-Sauveur', 'N'),
(29263, 29590, 'Saint-Ségal', 'N'),
(29264, 29400, 'Saint-Servais', 'N'),
(29265, 29600, 'Sainte-Sève', 'N'),
(29266, 29410, 'Saint-Thégonnec', 'N'),
(29267, 29520, 'Saint-Thois', 'N'),
(29268, 29800, 'Saint-Thonan', 'N'),
(29269, 29380, 'Saint-Thurien', 'N'),
(29270, 29800, 'Saint-Urbain', 'N'),
(29271, 29440, 'Saint-Vougay', 'N'),
(29272, 29140, 'Saint-Yvi', 'N'),
(29273, 29250, 'Santec', 'N'),
(29274, 29390, 'Scaër', 'N'),
(29275, 29640, 'Scrignac', 'N'),
(29276, 29250, 'Sibiril', 'N'),
(29277, 29450, 'Sizun', 'N'),
(29278, 29540, 'Spézet', 'N'),
(29279, 29670, 'Taulé', 'N'),
(29280, 29560, 'Telgruc-sur-Mer', 'N'),
(29281, 29140, 'Tourc\'h', 'N'),
(29282, 29217, 'Trébabu', 'O'),
(29284, 29730, 'Treffiagat', 'N'),
(29285, 29440, 'Tréflaouénan', 'N'),
(29286, 29800, 'Tréflévénez', 'N'),
(29287, 29430, 'Tréflez', 'N'),
(29288, 29260, 'Trégarantec', 'N'),
(29289, 29560, 'Trégarvan', 'N'),
(29290, 29870, 'Tréglonou', 'N'),
(29291, 29970, 'Trégourez', 'N'),
(29292, 29720, 'Tréguennec', 'N'),
(29293, 29910, 'Trégunc', 'N'),
(29294, 29450, 'Le Tréhou', 'N'),
(29295, 29800, 'Trémaouézan', 'N'),
(29296, 29120, 'Tréméoc', 'N'),
(29297, 29300, 'Tréméven', 'N'),
(29298, 29720, 'Tréogat', 'N'),
(29299, 29290, 'Tréouergat', 'N'),
(29300, 29380, 'Le Trévoux', 'N'),
(29301, 29440, 'Trézilidé', 'N'),
(29302, 29590, 'Pont-de-Buis-lès-Quimerch', 'N'),
(44162, 44800, 'Saint-Herblin', 'O');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `rsbl_communes`
--
ALTER TABLE `rsbl_communes`
  ADD PRIMARY KEY (`code`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
