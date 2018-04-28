
SET NAMES 'utf8';

CREATE DATABASE clinique;
USE clinique;

CREATE TABLE Examen (
    examen_id INT UNSIGNED AUTO_INCREMENT,
    examen_nom VARCHAR(60) NOT NULL,
    examen_date DATE DEFAULT NULL,
    examen_pathologie VARCHAR(60) NOT NULL,
    examen_commentaires TEXT,
    examen_patient_id INT UNSIGNED NOT NULL,
    examen_panel_gene_id INT UNSIGNED NOT NULL,
    examen_type_personnel_id INT UNSIGNED NOT NULL,
    PRIMARY KEY (examen_id)
)
ENGINE=InnoDB; 


CREATE TABLE Patient (
    patient_id INT UNSIGNED AUTO_INCREMENT,
    patient_num_secu INT UNSIGNED NOT NULL,
    patient_nom VARCHAR(60) NOT NULL,
    patient_prenom VARCHAR(60) NOT NULL,
    patient_sexe VARCHAR(10) NOT NULL,
    patient_ethnie VARCHAR(60) DEFAULT NULL,
    patient_date_naissance DATE NOT NULL,
    patient_mail VARCHAR(100) DEFAULT NULL,
    patient_num_tel VARCHAR(10) DEFAULT NULL,
    patient_pere_id INT UNSIGNED DEFAULT NULL,
    patient_mere_id INT UNSIGNED DEFAULT NULL,
    PRIMARY KEY (patient_id)
)
ENGINE=InnoDB;   


CREATE TABLE Personnel (
    personnel_id INT UNSIGNED AUTO_INCREMENT,
    personnel_prenom VARCHAR(60) NOT NULL,
    personnel_nom VARCHAR(60) NOT NULL,
    personnel_mail VARCHAR(100) NOT NULL,
    personnel_password CHAR(40) CHARACTER SET ASCII NOT NULL,
    personnel_type_personnel_id INT UNSIGNED NOT NULL,
    PRIMARY KEY (personnel_id)
)
ENGINE=InnoDB; 

CREATE TABLE Type_personnel (
    type_personnel_id INT UNSIGNED AUTO_INCREMENT,
    type_personnel_nom VARCHAR(60) NOT NULL,
    type_personnel_rang INT UNSIGNED NOT NULL,
    PRIMARY KEY (type_personnel_id)
)
ENGINE=InnoDB; 


CREATE TABLE Gene (
    gene_id INT UNSIGNED AUTO_INCREMENT,
    gene_nom VARCHAR(60) NOT NULL,
    gene_chromosome CHAR(2) DEFAULT NULL,
    PRIMARY KEY (gene_id)
)
ENGINE=InnoDB;

CREATE TABLE Panel (
    panel_id INT UNSIGNED AUTO_INCREMENT,
    panel_nom VARCHAR(60) NOT NULL,
    PRIMARY KEY (panel_id)
)
ENGINE=InnoDB;

CREATE TABLE Assoc_panel_gene(
    assoc_gene_id INT UNSIGNED NOT NULL,
    assoc_panel_id INT UNSIGNED NOT NULL,
    PRIMARY KEY (assoc_gene_id, assoc_panel_id)
)
ENGINE=InnoDB;  


ALTER TABLE Patient ADD CONSTRAINT fk_pere_id FOREIGN KEY (patient_pere_id) REFERENCES Patient(patient_id);
ALTER TABLE Patient ADD CONSTRAINT fk_mere_id FOREIGN KEY (patient_mere_id) REFERENCES Patient(patient_id);


       
ALTER TABLE Personnel 
    ADD CONSTRAINT fk_type_personnel_id          
    FOREIGN KEY (personnel_type_personnel_id)           
    REFERENCES Type_personnel(type_personnel_id);  
  


ALTER TABLE Assoc_panel_gene ADD CONSTRAINT fk_assoc_gene_id FOREIGN KEY (assoc_gene_id) REFERENCES Gene(gene_id);
ALTER TABLE Assoc_panel_gene ADD CONSTRAINT fk_assoc_panel_id FOREIGN KEY (assoc_panel_id) REFERENCES Panel(panel_id);



ALTER TABLE Examen ADD CONSTRAINT fk_patient_id FOREIGN KEY (examen_patient_id) REFERENCES Patient(patient_id);
ALTER TABLE Examen ADD CONSTRAINT fk_panel_gene_id FOREIGN KEY (examen_panel_gene_id) REFERENCES Panel(panel_id);
ALTER TABLE Examen ADD CONSTRAINT fk_examen_type_personnel_id FOREIGN KEY (examen_type_personnel_id) REFERENCES Personnel(personnel_id);


CREATE UNIQUE INDEX patient_unique_email
ON Patient(patient_mail);

CREATE UNIQUE INDEX personnel_unique_mail
ON Personnel(personnel_mail);

CREATE INDEX nom_personnel
ON Personnel(personnel_nom);

CREATE INDEX nom_gene
ON Gene(gene_nom);


