-- MySQL Script generated by MySQL Workbench
-- Вт 05 дек 2017 13:56:56
-- Model: New Model    Version: 1.0
-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';


-- -----------------------------------------------------
-- Table `categories`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `categories` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `goods`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `goods` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `category_id` INT NULL,
  PRIMARY KEY (`id`),
  INDEX `category_fk_idx` (`category_id` ASC),
  CONSTRAINT `category_fk`
    FOREIGN KEY (`category_id`)
    REFERENCES `categories` (`id`)
    ON DELETE SET NULL
    ON UPDATE SET NULL)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `options`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `options` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `categories_options`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `categories_options` (
  `category_id` INT NULL,
  `option_id` INT NULL,
  UNIQUE INDEX `cat_opt` (`category_id` ASC, `option_id` ASC),
  INDEX `option_fk_idx` (`option_id` ASC),
  CONSTRAINT `category_fk`
    FOREIGN KEY (`category_id`)
    REFERENCES `categories` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `option_fk`
    FOREIGN KEY (`option_id`)
    REFERENCES `options` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `goods_options`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `goods_options` (
  `goods_id` INT NULL,
  `option_id` INT NULL,
  `value` VARCHAR(255) NULL,
  UNIQUE INDEX `goods_opts` (`goods_id` ASC, `option_id` ASC),
  INDEX `options_fk_idx` (`option_id` ASC),
  CONSTRAINT `goods_fk`
    FOREIGN KEY (`goods_id`)
    REFERENCES `goods` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `options_fk`
    FOREIGN KEY (`option_id`)
    REFERENCES `options` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `tags`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `tags` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `cnt` INT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;