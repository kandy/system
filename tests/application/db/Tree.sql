CREATE TABLE Tree (
	id INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
	parentId INTEGER UNSIGNED NULL,
	lft INTEGER UNSIGNED NOT NULL,
	rgt  INTEGER UNSIGNED NOT NULL,
	level  INTEGER UNSIGNED NOT NULL,
	PRIMARY KEY(id)
)
TYPE=InnoDB;

INSERT INTO Tree 
VALUES
	(1, NULL, 1, 2, 0);
	
