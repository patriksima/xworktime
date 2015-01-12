SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';

DROP SCHEMA IF EXISTS `xworktime` ;
CREATE SCHEMA IF NOT EXISTS `xworktime` DEFAULT CHARACTER SET utf8 COLLATE utf8_czech_ci ;
USE `xworktime` ;

-- -----------------------------------------------------
-- Table `xworktime`.`xwt_dodavatel_typ`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `xworktime`.`xwt_dodavatel_typ` ;

CREATE  TABLE IF NOT EXISTS `xworktime`.`xwt_dodavatel_typ` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `nazev` VARCHAR(255) CHARACTER SET 'utf8' COLLATE 'utf8_czech_ci' NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_czech_ci;


-- -----------------------------------------------------
-- Table `xworktime`.`xwt_dodavatel_stabilita`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `xworktime`.`xwt_dodavatel_stabilita` ;

CREATE  TABLE IF NOT EXISTS `xworktime`.`xwt_dodavatel_stabilita` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `nazev` VARCHAR(255) CHARACTER SET 'utf8' COLLATE 'utf8_czech_ci' NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_czech_ci;


-- -----------------------------------------------------
-- Table `xworktime`.`xwt_dodavatel`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `xworktime`.`xwt_dodavatel` ;

CREATE  TABLE IF NOT EXISTS `xworktime`.`xwt_dodavatel` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `id_typ` INT(10) UNSIGNED NOT NULL ,
  `id_stabilita` INT(10) UNSIGNED NOT NULL ,
  `nazev` VARCHAR(255) CHARACTER SET 'utf8' COLLATE 'utf8_czech_ci' NOT NULL ,
  `ulice` VARCHAR(255) CHARACTER SET 'utf8' COLLATE 'utf8_czech_ci' NOT NULL ,
  `mesto` VARCHAR(255) CHARACTER SET 'utf8' COLLATE 'utf8_czech_ci' NOT NULL ,
  `stat` VARCHAR(255) CHARACTER SET 'utf8' COLLATE 'utf8_czech_ci' NOT NULL ,
  `psc` VARCHAR(255) CHARACTER SET 'utf8' COLLATE 'utf8_czech_ci' NOT NULL ,
  `ic` VARCHAR(255) CHARACTER SET 'utf8' COLLATE 'utf8_czech_ci' NOT NULL ,
  `dic` VARCHAR(255) CHARACTER SET 'utf8' COLLATE 'utf8_czech_ci' NOT NULL ,
  `tel` VARCHAR(255) CHARACTER SET 'utf8' COLLATE 'utf8_czech_ci' NOT NULL ,
  `fax` VARCHAR(255) CHARACTER SET 'utf8' COLLATE 'utf8_czech_ci' NOT NULL ,
  `email` VARCHAR(255) CHARACTER SET 'utf8' COLLATE 'utf8_czech_ci' NOT NULL ,
  `icq` VARCHAR(255) CHARACTER SET 'utf8' COLLATE 'utf8_czech_ci' NOT NULL ,
  `jabber` VARCHAR(255) CHARACTER SET 'utf8' COLLATE 'utf8_czech_ci' NOT NULL ,
  `msn` VARCHAR(255) CHARACTER SET 'utf8' COLLATE 'utf8_czech_ci' NOT NULL ,
  `www` VARCHAR(255) CHARACTER SET 'utf8' COLLATE 'utf8_czech_ci' NOT NULL ,
  `poznamka` TEXT CHARACTER SET 'utf8' COLLATE 'utf8_czech_ci' NOT NULL ,
  `fond` INT(10) UNSIGNED NOT NULL ,
  `sazba` DECIMAL(10,2) UNSIGNED NOT NULL ,
  `rychlost` DECIMAL(4,2) UNSIGNED NOT NULL DEFAULT '1.00' ,
  `nepocitat` TINYINT(1)  NOT NULL DEFAULT 0 ,
  PRIMARY KEY (`id`) ,
  CONSTRAINT `fk_dodavatel_dodavatel_typ`
    FOREIGN KEY (`id_typ` )
    REFERENCES `xworktime`.`xwt_dodavatel_typ` (`id` )
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_dodavatel_dodavatel_stabilita`
    FOREIGN KEY (`id_stabilita` )
    REFERENCES `xworktime`.`xwt_dodavatel_stabilita` (`id` )
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_czech_ci;

CREATE INDEX `fk_dodavatel_dodavatel_stabilita` ON `xworktime`.`xwt_dodavatel` (`id_stabilita` ASC) ;

CREATE INDEX `fk_dodavatel_dodavatel_typ` ON `xworktime`.`xwt_dodavatel` (`id_typ` ASC) ;


-- -----------------------------------------------------
-- Table `xworktime`.`xwt_zadavatel`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `xworktime`.`xwt_zadavatel` ;

CREATE  TABLE IF NOT EXISTS `xworktime`.`xwt_zadavatel` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `nazev` VARCHAR(255) CHARACTER SET 'utf8' COLLATE 'utf8_czech_ci' NOT NULL ,
  `sazba` DECIMAL(10,2) UNSIGNED NOT NULL ,
  `koeficient` DECIMAL(4,2) UNSIGNED NOT NULL DEFAULT '1.00' ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_czech_ci;


-- -----------------------------------------------------
-- Table `xworktime`.`xwt_ukol`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `xworktime`.`xwt_ukol` ;

CREATE  TABLE IF NOT EXISTS `xworktime`.`xwt_ukol` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `id_zadavatel` INT(10) UNSIGNED NOT NULL ,
  `id_dodavatel` INT(10) UNSIGNED NOT NULL ,
  `nazev` VARCHAR(255) CHARACTER SET 'utf8' COLLATE 'utf8_czech_ci' NOT NULL COMMENT 'nazev ukolu' ,
  `popis` TEXT CHARACTER SET 'utf8' COLLATE 'utf8_czech_ci' NOT NULL COMMENT 'popis ukolu' ,
  `klic` VARCHAR(255) CHARACTER SET 'utf8' COLLATE 'utf8_czech_ci' NOT NULL COMMENT 'klicove slovo' ,
  `zadano` DATE NOT NULL ,
  `termin` DATE NOT NULL COMMENT 'termin ukolu' ,
  `splneno` DATE NOT NULL COMMENT 'datum splneni' ,
  `status` ENUM('novy','prijaty','zamitnuty','dokonceny') CHARACTER SET 'utf8' COLLATE 'utf8_czech_ci' NOT NULL DEFAULT 'novy' ,
  PRIMARY KEY (`id`) ,
  CONSTRAINT `fk_ukol_zadavatel`
    FOREIGN KEY (`id_zadavatel` )
    REFERENCES `xworktime`.`xwt_zadavatel` (`id` )
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_ukol_dodavatel`
    FOREIGN KEY (`id_dodavatel` )
    REFERENCES `xworktime`.`xwt_dodavatel` (`id` )
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_czech_ci;

CREATE INDEX `fk_ukol_dodavatel` ON `xworktime`.`xwt_ukol` (`id_dodavatel` ASC) ;

CREATE INDEX `fk_ukol_zadavatel` ON `xworktime`.`xwt_ukol` (`id_zadavatel` ASC) ;


-- -----------------------------------------------------
-- Table `xworktime`.`xwt_podklady`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `xworktime`.`xwt_podklady` ;

CREATE  TABLE IF NOT EXISTS `xworktime`.`xwt_podklady` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `id_ukol` INT(10) UNSIGNED NOT NULL ,
  `hodinyd` DECIMAL(10,2) UNSIGNED NOT NULL COMMENT 'pocet hodin' ,
  `hodinyz` DECIMAL(10,2) UNSIGNED NOT NULL ,
  `sazbad` DECIMAL(10,2) UNSIGNED NOT NULL COMMENT 'dodavatelska sazba' ,
  `sazbaz` DECIMAL(10,2) UNSIGNED NOT NULL COMMENT 'nase sazba zadavateli' ,
  `fakturad` TINYINT(3) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'faktura od dodavatele' ,
  `fakturaz` TINYINT(3) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'faktura zadavateli' ,
  `zaplacend` TINYINT(3) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'zaplaceno dodavateli' ,
  `zaplacenz` TINYINT(3) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'zadavatel zaplatil' ,
  PRIMARY KEY (`id`) ,
  CONSTRAINT `fk_podklady_ukol`
    FOREIGN KEY (`id_ukol` )
    REFERENCES `xworktime`.`xwt_ukol` (`id` )
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_czech_ci;

CREATE INDEX `fk_podklady_ukol` ON `xworktime`.`xwt_podklady` (`id_ukol` ASC) ;


-- -----------------------------------------------------
-- Table `xworktime`.`xwt_udalosti`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `xworktime`.`xwt_udalosti` ;

CREATE  TABLE IF NOT EXISTS `xworktime`.`xwt_udalosti` (
  `id` INT(10) UNSIGNED NOT NULL ,
  `datum` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP ,
  `tabulka` VARCHAR(255) CHARACTER SET 'utf8' COLLATE 'utf8_czech_ci' NOT NULL ,
  `akce` ENUM('update','insert','delete') CHARACTER SET 'utf8' COLLATE 'utf8_czech_ci' NOT NULL )
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_czech_ci;

CREATE INDEX `ovx_udalosti_datum_Idx` ON `xworktime`.`xwt_udalosti` (`datum` ASC) ;


-- -----------------------------------------------------
-- Table `xworktime`.`xwt_uzivatele`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `xworktime`.`xwt_uzivatele` ;

CREATE  TABLE IF NOT EXISTS `xworktime`.`xwt_uzivatele` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `id_dodavatel` INT(10) UNSIGNED NULL DEFAULT NULL ,
  `id_zadavatel` INT(10) UNSIGNED NULL DEFAULT NULL ,
  `prist_jmeno` VARCHAR(255) CHARACTER SET 'utf8' COLLATE 'utf8_czech_ci' NOT NULL ,
  `prist_heslo` VARCHAR(255) CHARACTER SET 'utf8' COLLATE 'utf8_czech_ci' NOT NULL ,
  `email` VARCHAR(255) CHARACTER SET 'utf8' COLLATE 'utf8_czech_ci' NOT NULL ,
  `prava` TINYINT(3) UNSIGNED NOT NULL DEFAULT '0' ,
  PRIMARY KEY (`id`) ,
  CONSTRAINT `fk_uzivatele_zadavatel`
    FOREIGN KEY (`id_zadavatel` )
    REFERENCES `xworktime`.`xwt_zadavatel` (`id` )
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_uzivatele_realizator`
    FOREIGN KEY (`id_dodavatel` )
    REFERENCES `xworktime`.`xwt_dodavatel` (`id` )
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_czech_ci
COMMENT = 'uzivatel muze byt samostatny nebo ma vazbu na dodavatele';

CREATE INDEX `fk_uzivatele_realizator` ON `xworktime`.`xwt_uzivatele` (`id_dodavatel` ASC) ;

CREATE INDEX `fk_uzivatele_zadavatel` ON `xworktime`.`xwt_uzivatele` (`id_zadavatel` ASC) ;


-- -----------------------------------------------------
-- Table `xworktime`.`xwt_zadavatel_sazba`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `xworktime`.`xwt_zadavatel_sazba` ;

CREATE  TABLE IF NOT EXISTS `xworktime`.`xwt_zadavatel_sazba` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `id_zadavatel` INT(10) UNSIGNED NOT NULL ,
  `nazev` VARCHAR(255) CHARACTER SET 'utf8' COLLATE 'utf8_czech_ci' NOT NULL ,
  `sazba` DECIMAL(10,2) UNSIGNED NOT NULL ,
  PRIMARY KEY (`id`) ,
  CONSTRAINT `fk_ovx_zadavatel_sazba_ovx_zadavatel`
    FOREIGN KEY (`id_zadavatel` )
    REFERENCES `xworktime`.`xwt_zadavatel` (`id` )
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_czech_ci;

CREATE INDEX `fk_ovx_zadavatel_sazba_ovx_zadavatel` ON `xworktime`.`xwt_zadavatel_sazba` (`id_zadavatel` ASC) ;


-- -----------------------------------------------------
-- Placeholder table for view `xworktime`.`xwt_dodavatel_dluh`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `xworktime`.`xwt_dodavatel_dluh` (`id` INT, `id_typ` INT, `id_stabilita` INT, `nazev` INT, `ulice` INT, `mesto` INT, `stat` INT, `psc` INT, `ic` INT, `dic` INT, `tel` INT, `fax` INT, `email` INT, `icq` INT, `jabber` INT, `msn` INT, `www` INT, `poznamka` INT, `fond` INT, `sazba` INT, `rychlost` INT, `dluh` INT);

-- -----------------------------------------------------
-- Placeholder table for view `xworktime`.`xwt_dodavatel_nefakturovano`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `xworktime`.`xwt_dodavatel_nefakturovano` (`id` INT, `id_typ` INT, `id_stabilita` INT, `nazev` INT, `ulice` INT, `mesto` INT, `stat` INT, `psc` INT, `ic` INT, `dic` INT, `tel` INT, `fax` INT, `email` INT, `icq` INT, `jabber` INT, `msn` INT, `www` INT, `poznamka` INT, `fond` INT, `sazba` INT, `rychlost` INT, `nefakturovano` INT);

-- -----------------------------------------------------
-- Placeholder table for view `xworktime`.`xwt_zadavatel_dluh`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `xworktime`.`xwt_zadavatel_dluh` (`id` INT, `nazev` INT, `sazba` INT, `koeficient` INT, `dluh` INT);

-- -----------------------------------------------------
-- Placeholder table for view `xworktime`.`xwt_zadavatel_naklady`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `xworktime`.`xwt_zadavatel_naklady` (`id` INT, `nazev` INT, `sazba` INT, `koeficient` INT, `naklady` INT);

-- -----------------------------------------------------
-- Placeholder table for view `xworktime`.`xwt_zadavatel_nefakturovano`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `xworktime`.`xwt_zadavatel_nefakturovano` (`id` INT, `nazev` INT, `sazba` INT, `koeficient` INT, `nefakturovano` INT);

-- -----------------------------------------------------
-- Placeholder table for view `xworktime`.`xwt_zadavatel_zaplaceno`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `xworktime`.`xwt_zadavatel_zaplaceno` (`id` INT, `nazev` INT, `sazba` INT, `koeficient` INT, `zaplaceno` INT);

-- -----------------------------------------------------
-- Placeholder table for view `xworktime`.`xwt_zadavatel_zisk`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `xworktime`.`xwt_zadavatel_zisk` (`id` INT, `nazev` INT, `sazba` INT, `koeficient` INT, `zisk` INT);

-- -----------------------------------------------------
-- View `xworktime`.`xwt_dodavatel_dluh`
-- -----------------------------------------------------
DROP VIEW IF EXISTS `xworktime`.`xwt_dodavatel_dluh` ;
DROP TABLE IF EXISTS `xworktime`.`xwt_dodavatel_dluh`;
USE `xworktime`;
CREATE  OR REPLACE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `xworktime`.`xwt_dodavatel_dluh` AS select `d`.`id` AS `id`,`d`.`id_typ` AS `id_typ`,`d`.`id_stabilita` AS `id_stabilita`,`d`.`nazev` AS `nazev`,`d`.`ulice` AS `ulice`,`d`.`mesto` AS `mesto`,`d`.`stat` AS `stat`,`d`.`psc` AS `psc`,`d`.`ic` AS `ic`,`d`.`dic` AS `dic`,`d`.`tel` AS `tel`,`d`.`fax` AS `fax`,`d`.`email` AS `email`,`d`.`icq` AS `icq`,`d`.`jabber` AS `jabber`,`d`.`msn` AS `msn`,`d`.`www` AS `www`,`d`.`poznamka` AS `poznamka`,`d`.`fond` AS `fond`,`d`.`sazba` AS `sazba`,`d`.`rychlost` AS `rychlost`,sum((`p`.`hodinyd` * `p`.`sazbad`)) AS `dluh` from ((`xworktime`.`xwt_dodavatel` `d` left join `xworktime`.`xwt_ukol` `u` on((`d`.`id` = `u`.`id_dodavatel`))) left join `xworktime`.`xwt_podklady` `p` on((`u`.`id` = `p`.`id_ukol`))) where ((`u`.`status` = 'dokonceny') and (`p`.`zaplacend` = 0)) group by `u`.`id_dodavatel`;

-- -----------------------------------------------------
-- View `xworktime`.`xwt_dodavatel_nefakturovano`
-- -----------------------------------------------------
DROP VIEW IF EXISTS `xworktime`.`xwt_dodavatel_nefakturovano` ;
DROP TABLE IF EXISTS `xworktime`.`xwt_dodavatel_nefakturovano`;
USE `xworktime`;
CREATE  OR REPLACE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `xworktime`.`xwt_dodavatel_nefakturovano` AS select `d`.`id` AS `id`,`d`.`id_typ` AS `id_typ`,`d`.`id_stabilita` AS `id_stabilita`,`d`.`nazev` AS `nazev`,`d`.`ulice` AS `ulice`,`d`.`mesto` AS `mesto`,`d`.`stat` AS `stat`,`d`.`psc` AS `psc`,`d`.`ic` AS `ic`,`d`.`dic` AS `dic`,`d`.`tel` AS `tel`,`d`.`fax` AS `fax`,`d`.`email` AS `email`,`d`.`icq` AS `icq`,`d`.`jabber` AS `jabber`,`d`.`msn` AS `msn`,`d`.`www` AS `www`,`d`.`poznamka` AS `poznamka`,`d`.`fond` AS `fond`,`d`.`sazba` AS `sazba`,`d`.`rychlost` AS `rychlost`,sum((`p`.`hodinyd` * `p`.`sazbad`)) AS `nefakturovano` from ((`xworktime`.`xwt_dodavatel` `d` left join `xworktime`.`xwt_ukol` `u` on((`d`.`id` = `u`.`id_dodavatel`))) left join `xworktime`.`xwt_podklady` `p` on((`u`.`id` = `p`.`id_ukol`))) where ((`u`.`status` = 'dokonceny') and (`p`.`fakturad` = 0) and (`p`.`zaplacend` = 0)) group by `u`.`id_dodavatel`;

-- -----------------------------------------------------
-- View `xworktime`.`xwt_zadavatel_dluh`
-- -----------------------------------------------------
DROP VIEW IF EXISTS `xworktime`.`xwt_zadavatel_dluh` ;
DROP TABLE IF EXISTS `xworktime`.`xwt_zadavatel_dluh`;
USE `xworktime`;
CREATE  OR REPLACE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `xworktime`.`xwt_zadavatel_dluh` AS select `z`.`id` AS `id`,`z`.`nazev` AS `nazev`,`z`.`sazba` AS `sazba`,`z`.`koeficient` AS `koeficient`,sum((`p`.`hodinyz` * `p`.`sazbaz`)) AS `dluh` from ((`xworktime`.`xwt_zadavatel` `z` left join `xworktime`.`xwt_ukol` `u` on((`z`.`id` = `u`.`id_zadavatel`))) left join `xworktime`.`xwt_podklady` `p` on((`u`.`id` = `p`.`id_ukol`))) where ((`u`.`status` = 'dokonceny') and (`p`.`fakturaz` = 1) and (`p`.`zaplacenz` = 0)) group by `u`.`id_zadavatel`;

-- -----------------------------------------------------
-- View `xworktime`.`xwt_zadavatel_naklady`
-- -----------------------------------------------------
DROP VIEW IF EXISTS `xworktime`.`xwt_zadavatel_naklady` ;
DROP TABLE IF EXISTS `xworktime`.`xwt_zadavatel_naklady`;
USE `xworktime`;
CREATE  OR REPLACE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `xworktime`.`xwt_zadavatel_naklady` AS select `z`.`id` AS `id`,`z`.`nazev` AS `nazev`,`z`.`sazba` AS `sazba`,`z`.`koeficient` AS `koeficient`,sum((`p`.`hodinyd` * `p`.`sazbad`)) AS `naklady` from ((`xworktime`.`xwt_zadavatel` `z` left join `xworktime`.`xwt_ukol` `u` on((`z`.`id` = `u`.`id_zadavatel`))) left join `xworktime`.`xwt_podklady` `p` on((`u`.`id` = `p`.`id_ukol`))) where ((`u`.`status` = 'dokonceny') and (`p`.`fakturaz` = 1)) group by `u`.`id_zadavatel`;

-- -----------------------------------------------------
-- View `xworktime`.`xwt_zadavatel_nefakturovano`
-- -----------------------------------------------------
DROP VIEW IF EXISTS `xworktime`.`xwt_zadavatel_nefakturovano` ;
DROP TABLE IF EXISTS `xworktime`.`xwt_zadavatel_nefakturovano`;
USE `xworktime`;
CREATE  OR REPLACE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `xworktime`.`xwt_zadavatel_nefakturovano` AS select `z`.`id` AS `id`,`z`.`nazev` AS `nazev`,`z`.`sazba` AS `sazba`,`z`.`koeficient` AS `koeficient`,sum((`p`.`hodinyz` * `p`.`sazbaz`)) AS `nefakturovano` from ((`xworktime`.`xwt_zadavatel` `z` left join `xworktime`.`xwt_ukol` `u` on((`z`.`id` = `u`.`id_zadavatel`))) left join `xworktime`.`xwt_podklady` `p` on((`u`.`id` = `p`.`id_ukol`))) where ((`u`.`status` = 'dokonceny') and (`p`.`fakturaz` = 0) and (`p`.`zaplacenz` = 0)) group by `u`.`id_zadavatel`;

-- -----------------------------------------------------
-- View `xworktime`.`xwt_zadavatel_zaplaceno`
-- -----------------------------------------------------
DROP VIEW IF EXISTS `xworktime`.`xwt_zadavatel_zaplaceno` ;
DROP TABLE IF EXISTS `xworktime`.`xwt_zadavatel_zaplaceno`;
USE `xworktime`;
CREATE  OR REPLACE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `xworktime`.`xwt_zadavatel_zaplaceno` AS select `z`.`id` AS `id`,`z`.`nazev` AS `nazev`,`z`.`sazba` AS `sazba`,`z`.`koeficient` AS `koeficient`,sum((`p`.`hodinyz` * `p`.`sazbaz`)) AS `zaplaceno` from ((`xworktime`.`xwt_zadavatel` `z` left join `xworktime`.`xwt_ukol` `u` on((`z`.`id` = `u`.`id_zadavatel`))) left join `xworktime`.`xwt_podklady` `p` on((`u`.`id` = `p`.`id_ukol`))) where ((`u`.`status` = 'dokonceny') and (`p`.`fakturaz` = 1) and (`p`.`zaplacenz` = 1)) group by `u`.`id_zadavatel`;

-- -----------------------------------------------------
-- View `xworktime`.`xwt_zadavatel_zisk`
-- -----------------------------------------------------
DROP VIEW IF EXISTS `xworktime`.`xwt_zadavatel_zisk` ;
DROP TABLE IF EXISTS `xworktime`.`xwt_zadavatel_zisk`;
USE `xworktime`;
CREATE  OR REPLACE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `xworktime`.`xwt_zadavatel_zisk` AS select `z`.`id` AS `id`,`z`.`nazev` AS `nazev`,`z`.`sazba` AS `sazba`,`z`.`koeficient` AS `koeficient`,sum(((`p`.`hodinyz` * `p`.`sazbaz`) - (`p`.`hodinyd` * `p`.`sazbad`))) AS `zisk` from ((`xworktime`.`xwt_zadavatel` `z` left join `xworktime`.`xwt_ukol` `u` on((`z`.`id` = `u`.`id_zadavatel`))) left join `xworktime`.`xwt_podklady` `p` on((`u`.`id` = `p`.`id_ukol`))) where ((`u`.`status` = 'dokonceny') and (`p`.`fakturaz` = 1) and (`p`.`zaplacenz` = 1)) group by `u`.`id_zadavatel`;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
