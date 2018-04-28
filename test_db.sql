CREATE DATABASE gestion_prescription CHARACTER SET 'UTF8';

USE gestion_prescription;

CREATE TABLE type_personnel (
    type_personnel_id INT UNSIGNED AUTO_INCREMENT,
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
    UNIQUE KEY personnel_uni_mail (personnel_mail),
    PRIMARY KEY (personnel_id)
)
ENGINE=InnoDB DEFAULT CHARSET=utf8; 


CREATE TABLE patient (
    patient_id INT UNSIGNED AUTO_INCREMENT,
    patient_num_secu INT UNSIGNED NOT NULL,
    patient_prenom VARCHAR(60) NOT NULL,
    patient_nom VARCHAR(60) NOT NULL,
    patient_mail VARCHAR(100) DEFAULT NULL,
    patient_sexe VARCHAR(10) DEFAULT NULL,
    patient_ethnie VARCHAR(60) DEFAULT NULL,
    patient_date_naissance DATE DEFAULT NULL,
    patient_num_tel VARCHAR(10) DEFAULT NULL,
    patient_pere_id INT UNSIGNED DEFAULT NULL,
    patient_mere_id INT UNSIGNED DEFAULT NULL,
    patient_prescripteur_id INT UNSIGNED DEFAULT NULL,
    patient_clinicien_id INT UNSIGNED DEFAULT NULL,
    UNIQUE KEY patient_uni_mail (patient_mail),
    PRIMARY KEY (patient_id)
)
ENGINE=InnoDB;         


CREATE TABLE panel_gene (
    panel_gene_id INT UNSIGNED AUTO_INCREMENT,
    panel_gene_nom VARCHAR(60) NOT NULL,
    PRIMARY KEY (panel_gene_id)
)
ENGINE=InnoDB;

CREATE TABLE gene (
    gene_id INT UNSIGNED AUTO_INCREMENT,
    gene_nom VARCHAR(60) NOT NULL,
    gene_chromosome CHAR(2) DEFAULT NULL,
    PRIMARY KEY (gene_id)
)
ENGINE=InnoDB; 

CREATE TABLE assoc_panel_gene(
    assoc_gene_id INT UNSIGNED NOT NULL,
    assoc_panel_id INT UNSIGNED NOT NULL,
    CONSTRAINT pk_assoc PRIMARY KEY (assoc_gene_id,assoc_panel_id)
)
ENGINE=InnoDB;    

CREATE TABLE examen (
    examen_id INT UNSIGNED AUTO_INCREMENT,
    examen_nom VARCHAR(60) NOT NULL,
    examen_date DATE DEFAULT NULL,
    examen_patient_id INT UNSIGNED NOT NULL,
    examen_panel_gene_id INT UNSIGNED NOT NULL,
    PRIMARY KEY (examen_id)
)
ENGINE=InnoDB;          


CREATE TABLE rapport (
    rapport_id INT UNSIGNED AUTO_INCREMENT,
    rapport_nom VARCHAR(60) NOT NULL,
    rapport_date DATE DEFAULT NULL,
    rapport_pathologie VARCHAR(60) NOT NULL,
    rapport_examen_id INT UNSIGNED NOT NULL,
    rapport_prescripteur_id INT UNSIGNED NOT NULL,
    PRIMARY KEY (rapport_id)
)
ENGINE=InnoDB;   

ALTER TABLE personnel ADD CONSTRAINT fk_type_personnel_id          
        FOREIGN KEY (personnel_type_personnel_id)           
        REFERENCES TYPE_PERSONNEL (type_personnel_id);

ALTER TABLE patient ADD CONSTRAINT fk_pere_id
        FOREIGN KEY (patient_pere_id) 
        REFERENCES patient(patient_id);
ALTER TABLE patient ADD CONSTRAINT fk_mere_id 
        FOREIGN KEY (patient_mere_id) 
        REFERENCES patient(patient_id);
ALTER TABLE patient ADD CONSTRAINT fk_prescripteur_id
        FOREIGN KEY (patient_prescripteur_id) 
        REFERENCES personnel(personnel_id);
ALTER TABLE patient ADD CONSTRAINT fk_clinicien_id
        FOREIGN KEY (patient_clinicien_id) 
        REFERENCES personnel(personnel_id);

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

ALTER TABLE rapport ADD CONSTRAINT fk_examen_id
        FOREIGN KEY (rapport_examen_id) 
        REFERENCES examen(examen_id);

ALTER TABLE rapport ADD CONSTRAINT fk_rapport_prescripteur_id
        FOREIGN KEY (rapport_prescripteur_id) 
        REFERENCES personnel(personnel_id);