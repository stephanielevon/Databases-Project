SET NAMES 'utf8';

/* Création de la base */
CREATE DATABASE gestion_prescription; 

USE gestion_prescription; /* Changement de base de données dans MySQL */

/* Création des tables */
CREATE TABLE type_personnel ( 
    type_personnel_id INT UNSIGNED AUTO_INCREMENT, /* Clef primaire auto-incrémentée */
    type_personnel_nom VARCHAR(60) NOT NULL,
    type_personnel_rang INT UNSIGNED NOT NULL,
    PRIMARY KEY (type_personnel_id)
)
ENGINE=InnoDB;   

CREATE TABLE personnel (
    personnel_id INT UNSIGNED AUTO_INCREMENT,
    personnel_prenom VARCHAR(60) NOT NULL,
    personnel_nom VARCHAR(60) NOT NULL,
    personnel_mail VARCHAR(100) DEFAULT NULL,
    personnel_password CHAR(40) CHARACTER SET ASCII NOT NULL,
    personnel_type_personnel_id INT UNSIGNED NOT NULL,
    personnel_actif BOOLEAN DEFAULT '0' NOT NULL,
    UNIQUE KEY personnel_uni_mail (personnel_mail),
    PRIMARY KEY (personnel_id)
)
ENGINE=InnoDB DEFAULT CHARSET=utf8;  


CREATE TABLE patient (
    patient_id INT UNSIGNED AUTO_INCREMENT,
    patient_num_secu BIGINT UNSIGNED NOT NULL,
    patient_prenom VARCHAR(60) NOT NULL,
    patient_nom VARCHAR(60) NOT NULL,
    patient_mail VARCHAR(100) DEFAULT NULL,
    patient_sexe CHAR(1) DEFAULT NULL,
    patient_date_naissance DATE DEFAULT NULL,
    patient_num_tel VARCHAR(10) DEFAULT NULL,
    patient_pere_id INT UNSIGNED DEFAULT NULL,
    patient_mere_id INT UNSIGNED DEFAULT NULL,
    patient_actif BOOLEAN DEFAULT '0' NOT NULL,
    UNIQUE KEY patient_uni_mail (patient_mail),
    PRIMARY KEY (patient_id)
)
ENGINE=InnoDB;   

CREATE INDEX nom_prenom_patient /* Permet une recherche rapide des patients par leur nom et prénom */
ON patient(patient_nom,patient_prenom);

CREATE TABLE panel_gene (
    panel_gene_id INT UNSIGNED AUTO_INCREMENT,
    panel_gene_nom VARCHAR(30) NOT NULL,
    UNIQUE KEY panel_gene_uni_nom (panel_gene_nom),
    PRIMARY KEY (panel_gene_id)
)
ENGINE=InnoDB; 


CREATE TABLE gene (
    gene_id INT UNSIGNED AUTO_INCREMENT,
    gene_nom VARCHAR(10) NOT NULL,
    gene_chromosome CHAR(2) DEFAULT NULL,
    gene_actif BOOLEAN DEFAULT '0' NOT NULL,
    UNIQUE KEY nom_gene (gene_nom),
    PRIMARY KEY (gene_id)
)
ENGINE=InnoDB;   


CREATE TABLE assoc_panel_gene( /* Création dune table d'association avec pour particularité deux clefs primaires */
    assoc_gene_id INT UNSIGNED NOT NULL,
    assoc_panel_id INT UNSIGNED NOT NULL,
    CONSTRAINT pk_assoc PRIMARY KEY (assoc_gene_id,assoc_panel_id)
)
ENGINE=InnoDB;    




CREATE TABLE examen (
    examen_id INT UNSIGNED AUTO_INCREMENT,
    examen_nom VARCHAR(60) NOT NULL,
    examen_date DATE DEFAULT NULL,
    examen_pathologie VARCHAR(60) NOT NULL,
    examen_commentaires TEXT,
    examen_patient_id INT UNSIGNED NOT NULL,
    examen_panel_gene_id INT UNSIGNED NOT NULL,
    examen_personnel_id INT UNSIGNED NOT NULL,
    PRIMARY KEY (examen_id)
)
ENGINE=InnoDB; 

/* Création des clefs étrangères */ 

ALTER TABLE personnel ADD CONSTRAINT fk_type_personnel_id          
        FOREIGN KEY (personnel_type_personnel_id)           
        REFERENCES type_personnel(type_personnel_id);  

ALTER TABLE patient ADD CONSTRAINT fk_pere_id
        FOREIGN KEY (patient_pere_id) 
        REFERENCES patient(patient_id);
ALTER TABLE patient ADD CONSTRAINT fk_mere_id 
        FOREIGN KEY (patient_mere_id) 
        REFERENCES patient(patient_id);

ALTER TABLE assoc_panel_gene ADD CONSTRAINT fk_assoc_gene_id FOREIGN KEY (assoc_gene_id)
        REFERENCES gene(gene_id);
ALTER TABLE assoc_panel_gene ADD CONSTRAINT fk_assoc_panel_id FOREIGN KEY (assoc_panel_id)
        REFERENCES panel_gene(panel_gene_id);


ALTER TABLE examen ADD CONSTRAINT fk_patient_id
        FOREIGN KEY (examen_patient_id) 
        REFERENCES patient(patient_id);
ALTER TABLE examen ADD CONSTRAINT fk_panel_gene_id
        FOREIGN KEY (examen_panel_gene_id) 
        REFERENCES panel_gene(panel_gene_id);  
ALTER TABLE examen ADD CONSTRAINT fk_examen_personnel_id
        FOREIGN KEY (examen_personnel_id) 
        REFERENCES personnel(personnel_id);

/* Insertion de données */ 
/* Création de deux types personnel : celui de l'admin et celui des prescripteurs */
INSERT INTO `gestion_prescription`.`type_personnel` (`type_personnel_id`, `type_personnel_nom`, `type_personnel_rang`) VALUES (NULL, 'Administrateur', '1'),
    (NULL, 'Prescripteur', '2');

/* Création de l'admin : George Michael */
INSERT INTO `gestion_prescription`.`personnel` (`personnel_id`, `personnel_prenom`, `personnel_nom`, `personnel_mail`, `personnel_password`, `personnel_type_personnel_id`) VALUES (NULL, 'George', 'Michael', 'george.michael@cc.fr', '5ed25af7b1ed23fb00122e13d7f74c4d8262acd8', '1');
/* Création de trois prescripteurs */
INSERT INTO `gestion_prescription`.`personnel` (`personnel_id`, `personnel_prenom`, `personnel_nom`, `personnel_mail`, `personnel_password`, `personnel_type_personnel_id`) VALUES (NULL, 'Gregory', 'House', 'dr.house@cc.fr', 'ba203059615a933ae7a3638e1adde76aa7290398', '2'), 
(NULL, 'Cristina', 'Yang', 'cristina.yang@cc.fr', 'b5539108cc78f3f7fac087a88cad130c6ee6842d', '2'), 
(NULL, 'Derek', 'Shepherd', 'dr.mamour@cc.fr', 'f0e045bf8c5b9ebac14df3986aa6d426660b6d19', '2');

/* Insertion de gènes */
INSERT INTO `gestion_prescription`.`gene` (`gene_id`, `gene_nom`, `gene_chromosome`)
VALUES (NULL, 'DHX33', '17'),
(NULL, 'DPM1', '20'),
(NULL, 'UPF1', '19'),
(NULL, 'ACSM3', '16'),
(NULL, 'CFLAR', '2'),
(NULL, 'RBM6', '3'),
(NULL, 'LASP1', '17'),
(NULL, 'PDK4', '7'),
(NULL, 'CCDC109B', '4'),
(NULL, 'KLHL13', 'X'),
(NULL, 'SLC25A5', 'X'),
(NULL, 'HCCS', 'X'),
(NULL, 'ARF5', '7'),
(NULL, 'TNMD', 'X'),
(NULL, 'CYP26B1', '2'),
(NULL, 'STPG1', '1'),
(NULL, 'GCLC', '6'),
(NULL, 'CROT', '7'),
(NULL, 'MAD1L1', '7'),
(NULL, 'AC004381.6', '16'),
(NULL, 'POLR2J', '7'),
(NULL, 'PRSS22', '16'),
(NULL, 'CYP51A1', '7'),
(NULL, 'ALS2', '2'),
(NULL, 'ABCB5', '7'),
(NULL, 'CALCR', '7'),
(NULL, 'BZRAP1', '17'),
(NULL, 'NIPAL3', '1'),
(NULL, 'CFTR', '7'),
(NULL, 'NDUFAF7', '2'),
(NULL, 'ABCB4', '7'),
(NULL, 'HSPB6', '19'),
(NULL, 'HOXA11', '7'),
(NULL, 'NFYA', '6'),
(NULL, 'TFPI', '2'),
(NULL, 'FKBP4', '12'),
(NULL, 'GCFC2', '2'),
(NULL, 'CDC27', '17'),
(NULL, 'M6PR', '12'),
(NULL, 'AK2', '1'),
(NULL, 'NDUFAB1', '16'),
(NULL, 'ICA1', '7'),
(NULL, 'BAD', '11'),
(NULL, 'SARM1', '17'),
(NULL, 'AOC1', '7'),
(NULL, 'MSL3', 'X'),
(NULL, 'MTMR7', '8'),
(NULL, 'LIG3', '17'),
(NULL, 'CD38', '4'),
(NULL, 'FUCA2', '6'),
(NULL, 'ARHGAP33', '19'),
(NULL, 'WNT16', '7'),
(NULL, 'CREBBP', '16'),
(NULL, 'HS3ST1', '4'),
(NULL, 'COPZ2', '17'),
(NULL, 'SNX11', '17'),
(NULL, 'C1orf112', '1'),
(NULL, 'ST7', '7'),
(NULL, 'DBNDD1', '16'),
(NULL, 'ENPP4', '6'),
(NULL, 'PON1', '7'),
(NULL, 'SLC22A16', '6'),
(NULL, 'TMEM176A', '7'),
(NULL, 'RECQL', '12'),
(NULL, 'CFH', '1'),
(NULL, 'CD99', 'X'),
(NULL, 'LAS1L', 'X'),
(NULL, 'RBM5', '3'),
(NULL, 'HECW1', '7'),
(NULL, 'SLC4A1', '17'),
(NULL, 'CASP10', '2'),
(NULL, 'KMT2E', '7'),
(NULL, 'CIAPIN1', '16'),
(NULL, 'RAD52', '12'),
(NULL, 'MEOX1', '17'),
(NULL, 'DVL2', '17'),
(NULL, 'RHBDD2', '7'),
(NULL, 'PRKAR2B', '7'),
(NULL, 'MPO', '17'),
(NULL, 'ARX', 'X'),
(NULL, 'FGR', '1'),
(NULL, 'POLDIP2', '17'),
(NULL, 'FAM214B', '9'),
(NULL, 'LAP3', '4'),
(NULL, 'THSD7A', '7'),
(NULL, 'KDM1A', '1'),
(NULL, 'RPAP3', '12'),
(NULL, 'SEMA3F', '3'),
(NULL, 'KRIT1', '7'),
(NULL, 'WDR54', '2'),
(NULL, 'PLXND1', '3'),
(NULL, 'ZMYND10', '3'),
(NULL, 'SKAP2', '7'),
(NULL, 'SLC7A2', '8'),
(NULL, 'CCDC132', '7'),
(NULL, 'SLC25A13', '7'),
(NULL, 'TSPAN6', 'X'),
(NULL, 'SCYL3', '1'),
(NULL, 'ANKIB1', '7'),
(NULL, 'CAMKK1', '17');


/* Insertion de patients */
INSERT INTO `gestion_prescription`.`patient` (`patient_id`, `patient_num_secu`, `patient_prenom`, `patient_nom`, `patient_mail`, `patient_sexe`, `patient_date_naissance`, `patient_num_tel`, `patient_pere_id`, `patient_mere_id`)
VALUES (NULL, '372542127966880', 'Leo', 'Giles', 'leo.giles@cc.fr', 'H', '1960-06-20', '0772964051', NULL, NULL),
(NULL,'458268732763829','Georgia','Richardson','georgia.richardson@cc.fr','F','1955-09-09','0772964051',NULL,NULL),
(NULL,'893529274035245','Janna','Sampson','janna.sampson@cc.fr','F','1987-01-26','0786477306',NULL,NULL),
(NULL,'719790315255522','Gareth','Pitts','gareth.pitts@cc.fr','H','1981-12-03','0613702578',NULL,NULL),
(NULL,'839999704621732','Madison','Galloway','madison.galloway@cc.fr','F','1988-03-28','0830188914',NULL,NULL),
(NULL,'381287971977144','Justin','Powers','justin.powers@cc.fr','H','1957-05-19','0746191789',NULL,NULL),
(NULL,'179723885841667','Grace','Bowers','grace.bowers@cc.fr','F','1969-01-31','0438077027',NULL,NULL),
(NULL,'910394249018281','Ignacia','Henry','ignacia.henry@cc.fr','F','1989-02-26','0407291039',NULL,NULL),
(NULL,'215397845953702','Kenneth','Mckenzie','kenneth.mckenzie@cc.fr','F','2012-06-07','0799916749',NULL,NULL),
(NULL,'386237485334277','Christian','Watson','christian.watson@cc.fr','H','1959-05-05','0602613645',NULL,NULL),
(NULL,'375898923724889','Ulla','Torres','ulla.torres@cc.fr','F','1970-08-11','0144847210',NULL,NULL),
(NULL,'293753291014581','Mia','Jacobs','mia.jacobs@cc.fr','F','2005-10-28','0214475100',NULL,NULL),
(NULL,'343134348746389','Channing','Marsh','channing.marsh@cc.fr','F','2000-06-20','0951940825',NULL,NULL),
(NULL,'638697972055524','Althea','Best','althea.best@cc.fr','F','1982-03-22','0764741765',NULL,NULL),
(NULL,'582508681342005','Ebony','Ortega','ebony.ortega@cc.fr','F','1946-09-10','0209455023',NULL,NULL),
(NULL,'902086384594446','Felix','Becker','felix.becker@cc.fr','H','1981-12-26','0339079079',NULL,NULL),
(NULL,'303165260236710','Guy','Flynn','guy.flynn@cc.fr','H','2005-08-31','0783249241',NULL,NULL),
(NULL,'683669000398367','Sean','Donovan','sean.donovan@cc.fr','H','1986-11-23','0997975598',NULL,NULL),
(NULL,'293784618377685','Hector','Sampson','hector.sampson@cc.fr','H','1985-01-26','0238506015',NULL,NULL),
(NULL,'790227639023214','Anna','Giles','anna.giles@cc.fr','F','1967-01-13','0459951824',NULL,NULL),
(NULL,'491160103119908','John','Richardson','john.richardson@cc.fr','H','1947-03-25','0220867353',NULL,NULL),
(NULL,'260590809863060','Elisa','Pitts','elisa.pitts@cc.fr','F','1984-07-06','0494560812',NULL,NULL),
(NULL,'887728589586913','George','Torres','george.torres@cc.fr','H','1964-06-02','0219527916',NULL,NULL),
(NULL,'293784618377685','Richard','Sampson','richard.sampson@cc.fr','H','2014-01-13',NULL,'19','3'),
(NULL,'293784618377685','Jenna','Sampson','jenna.sampson@cc.fr','F','2007-05-11',NULL,'19','3'),
(NULL,'984132010489704','Isabelle','Giles','isabelle.giles@cc.fr','F','1983-01-13','0538534932','1','20'),
(NULL,'585501230321824','Allan','Richardson','allan.richardson@cc.fr','H','1972-03-25','0119335695','21','2'),
(NULL,'260590809863060','Vincent','Pitts','vincent.pitts@cc.fr','H','2002-05-01',NULL,'4','22'),
(NULL,'302256620023399','Simon','Torres','simon.torres@cc.fr','H','1984-06-14','0219527916','23','11');


/* Création de 5 panels de gènes */
INSERT INTO `gestion_prescription`.`panel_gene` (`panel_gene_id`, `panel_gene_nom`) VALUES (NULL, 'cancer_sein');
INSERT INTO `gestion_prescription`.`assoc_panel_gene` (`assoc_gene_id`, `assoc_panel_id`) 
VALUES ('1', '1'),
('2', '1'),
('3', '1');

INSERT INTO `gestion_prescription`.`panel_gene` (`panel_gene_id`, `panel_gene_nom`) VALUES (NULL, 'polykystose_renale');
INSERT INTO `gestion_prescription`.`assoc_panel_gene` (`assoc_gene_id`, `assoc_panel_id`) 
VALUES ('4', '2'),
('5', '2'),
('6', '2');

INSERT INTO `gestion_prescription`.`panel_gene` (`panel_gene_id`, `panel_gene_nom`) VALUES (NULL, 'intelectual_disorder');
INSERT INTO `gestion_prescription`.`assoc_panel_gene` (`assoc_gene_id`, `assoc_panel_id`) 
VALUES ('7', '3'),
('8', '3'),
('9', '3'),
('10', '3'),
('11', '3'),
('12', '3'),
('13', '3'),
('14', '3'),
('15', '3');

INSERT INTO `gestion_prescription`.`panel_gene` (`panel_gene_id`, `panel_gene_nom`) VALUES (NULL, 'mucoviscidose');
INSERT INTO `gestion_prescription`.`assoc_panel_gene` (`assoc_gene_id`, `assoc_panel_id`) 
VALUES ('16', '4'),
('17', '4'),
('18', '4'),
('19', '4'),
('20', '4');

INSERT INTO `gestion_prescription`.`panel_gene` (`panel_gene_id`, `panel_gene_nom`) VALUES (NULL, 'maladie_crohn');
INSERT INTO `gestion_prescription`.`assoc_panel_gene` (`assoc_gene_id`, `assoc_panel_id`) 
VALUES ('21', '5'),
('22', '5'),
('23', '5'),
('24', '5'),
('25', '5'); 



/* Création d'un examen */
INSERT INTO `gestion_prescription`.`examen` (`examen_id`, `examen_nom`, `examen_date`, `examen_pathologie`, `examen_patient_id`, `examen_panel_gene_id`,`examen_personnel_id`)
VALUES (NULL, 'house_watson', '2015-06-12', 'maladie_crohn', '10', '5', '1');