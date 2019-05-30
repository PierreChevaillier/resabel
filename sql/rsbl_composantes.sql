SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;

--
-- Resabel
-- creation: 12-mai-2019 pchevaillier@gmail.com

--
-- Structure de la table `rsbl_composantes`
--
DROP TABLE IF EXISTS `rsbl_composantes`;

CREATE TABLE `rsbl_composantes` (
  `code` TINYINT(4) UNSIGNED NOT NULL,
  `genre` varchar(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'F',
  `nom_court` varchar(12) COLLATE utf8_unicode_ci NOT NULL,
  `nom` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `courriel_contact` varchar(50) COLLATE utf8_unicode_ci,
  `liste_diffusion` varchar(50) COLLATE utf8_unicode_ci
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `rsbl_composantes` (`code`, `genre`, `nom_court`, `nom`, `courriel_contact`, `liste_diffusion`) VALUES
  (1, 'M', 'Club', 'Club', 'aviron.plougonvelin@gmail.com', ''),
  (2, 'N', 'Resabel', 'Système de gestion Resabel', 'pchevaillier@gmail.com', ''),
  (3, 'M', 'CA', 'Conseil d\'Administration', '', ''),
  (4, 'M', 'Bureau','Bureau', 'aviron.plougonvelin@gmail.com', ''),
  (5, 'F', 'Informatique','Commission Numérique', '', ''),
  (6, 'F', 'Formation', 'Commission formation et sécurité', '', ''),
  (7, 'F', 'Compet', 'Commission compétition', 'amp.competition@gmail.com', ''),
  (8, 'F', 'Entretien', 'Commission entretien', '', ''),
  (9, 'F', 'Festivites', 'Commission festivités', 'Amp.festivites@gmail.com', ''),
  (10, 'F', 'Rando', 'Commission randonnées', 'amp.randos@gmail.com', ''),
  (11, 'F', 'Permanence', 'Equipe des responsables des permanences', '', ''),
  (12, 'F', 'Jeunes', 'Commission jeunes', '', '');

--
-- Index pour la table `rsbl_composantes`
--

ALTER TABLE `rsbl_composantes`
  ADD PRIMARY KEY (`code`);

--
-- AUTO_INCREMENT pour la table `rsbl_club`
--
ALTER TABLE `rsbl_composantes`
  MODIFY `code` tinyint(4) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

COMMIT;
