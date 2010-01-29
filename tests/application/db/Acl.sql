-- -----------------------------------------------------
-- Table `AclRole`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `AclRole` (
  `name` VARCHAR(64) NOT NULL ,
  `parent` VARCHAR(64) NULL ,
  PRIMARY KEY (`name`) ,
  INDEX `fk_AclRole_1` (`parent` ASC))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `AclResource`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `AclResource` (
  `name` VARCHAR(64) NOT NULL ,
  `parent` VARCHAR(64) NULL ,
  PRIMARY KEY (`name`) ,
  INDEX `fk_AclRole_1` (`parent` ASC))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `AclRule`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `AclRule` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `role` VARCHAR(64) NOT NULL ,
  `resource` VARCHAR(64) NOT NULL ,
  `privileges` VARCHAR(256) NULL,
  `access` TINYINT(1) NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_AclRule_AclRole1` (`role` ASC) ,
  INDEX `fk_AclRule_AclResource1` (`resource` ASC))
ENGINE = InnoDB;

