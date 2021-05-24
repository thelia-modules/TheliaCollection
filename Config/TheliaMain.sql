
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

-- ---------------------------------------------------------------------
-- thelia_collection
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `thelia_collection`;

CREATE TABLE `thelia_collection`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `code` VARCHAR(255),
    `visible` TINYINT,
    `position` INTEGER,
    `item_type` VARCHAR(255),
    `item_id` INTEGER,
    PRIMARY KEY (`id`),
    INDEX `thelia_collection_i_066c87` (`item_type`, `item_id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- thelia_collection_item
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `thelia_collection_item`;

CREATE TABLE `thelia_collection_item`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `thelia_collection_id` INTEGER,
    `item_type` VARCHAR(255),
    `item_id` INTEGER,
    `position` INTEGER,
    PRIMARY KEY (`id`),
    INDEX `thelia_collection_item_i_066c87` (`item_type`, `item_id`),
    INDEX `fi_thelia_collection_thelia_item_collection_id` (`thelia_collection_id`),
    CONSTRAINT `fk_thelia_collection_thelia_item_collection_id`
        FOREIGN KEY (`thelia_collection_id`)
        REFERENCES `thelia_collection` (`id`)
        ON UPDATE RESTRICT
        ON DELETE CASCADE
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- thelia_collection_i18n
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `thelia_collection_i18n`;

CREATE TABLE `thelia_collection_i18n`
(
    `id` INTEGER NOT NULL,
    `locale` VARCHAR(5) DEFAULT 'en_US' NOT NULL,
    `title` VARCHAR(255),
    PRIMARY KEY (`id`,`locale`),
    CONSTRAINT `thelia_collection_i18n_fk_4787f2`
        FOREIGN KEY (`id`)
        REFERENCES `thelia_collection` (`id`)
        ON DELETE CASCADE
) ENGINE=InnoDB;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
