CREATE TABLE utilisateur (
    id_utilisateur INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    telephone VARCHAR(20),
    adresse_postale TEXT,
    mot_de_passe_hash VARCHAR(255) NOT NULL,
    role VARCHAR(20) NOT NULL DEFAULT 'client',
    compte_actif BOOLEAN NOT NULL DEFAULT TRUE
);




CREATE TABLE menu (
    id_menu INT AUTO_INCREMENT PRIMARY KEY,
    titre VARCHAR(150) NOT NULL,
    description TEXT,
    theme VARCHAR(100),
    regime VARCHAR(100),
    nb_personnes_min INT NOT NULL,
    prix_minimum DECIMAL(10,2) NOT NULL,
    stock_disponible INT NOT NULL DEFAULT 0,
    conditions TEXT
);




CREATE TABLE commande (
    id_commande INT AUTO_INCREMENT PRIMARY KEY,
    id_utilisateur INT NOT NULL,
    id_menu INT NOT NULL,
    date_commande DATETIME NOT NULL,
    date_prestation DATE NOT NULL,
    date_livraison DATE NOT NULL,
    heure_livraison TIME NOT NULL,
    adresse_livraison TEXT NOT NULL,
    ville_livraison VARCHAR(100) NOT NULL,
    nombre_personnes INT NOT NULL,
    prix_menu_commande DECIMAL(10,2) NOT NULL,
    montant_reduction DECIMAL(10,2) DEFAULT 0,
    distance_livraison_km DECIMAL(8,2) DEFAULT 0,
    frais_livraison DECIMAL(10,2) DEFAULT 0,
    prix_total DECIMAL(10,2) NOT NULL,
    statut VARCHAR(50) NOT NULL,
    date_modification DATETIME,


    CONSTRAINT fk_commande_utilisateur
        FOREIGN KEY (id_utilisateur)
        REFERENCES utilisateur(id_utilisateur),


    CONSTRAINT fk_commande_menu
        FOREIGN KEY (id_menu)
        REFERENCES menu(id_menu)
);




CREATE TABLE plat (
    id_plat INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(150) NOT NULL,
    description TEXT,
    categorie VARCHAR(50) NOT NULL
);




CREATE TABLE menu_plat (
    id_menu INT NOT NULL,
    id_plat INT NOT NULL,


    PRIMARY KEY (id_menu, id_plat),


    CONSTRAINT fk_menu_plat_menu
        FOREIGN KEY (id_menu)
        REFERENCES menu(id_menu),


    CONSTRAINT fk_menu_plat_plat
        FOREIGN KEY (id_plat)
        REFERENCES plat(id_plat)
);




CREATE TABLE allergene (
    id_allergene INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL UNIQUE,
    description TEXT
);




CREATE TABLE plat_allergene (
    id_plat INT NOT NULL,
    id_allergene INT NOT NULL,


    PRIMARY KEY (id_plat, id_allergene),


    CONSTRAINT fk_plat_allergene_plat
        FOREIGN KEY (id_plat)
        REFERENCES plat(id_plat),


    CONSTRAINT fk_plat_allergene_allergene
        FOREIGN KEY (id_allergene)
        REFERENCES allergene(id_allergene)
);




CREATE TABLE historique_statut (
    id_historique INT AUTO_INCREMENT PRIMARY KEY,
    id_commande INT NOT NULL,
    statut VARCHAR(50) NOT NULL,
    date_changement DATE NOT NULL,
    heure_changement TIME NOT NULL,


    CONSTRAINT fk_historique_commande
        FOREIGN KEY (id_commande)
        REFERENCES commande(id_commande)
);




CREATE TABLE avis (
    id_avis INT AUTO_INCREMENT PRIMARY KEY,
    id_commande INT NOT NULL UNIQUE,
    note INT NOT NULL,
    commentaire TEXT,
    date_avis DATE NOT NULL,
    statut_validation VARCHAR(30) NOT NULL DEFAULT 'en_attente',


    CONSTRAINT fk_avis_commande
        FOREIGN KEY (id_commande)
        REFERENCES commande(id_commande),


    CONSTRAINT chk_avis_note
        CHECK (note BETWEEN 1 AND 5)
);




CREATE TABLE image_menu (
    id_image INT AUTO_INCREMENT PRIMARY KEY,
    id_menu INT NOT NULL,
    url_image VARCHAR(255) NOT NULL,
    texte_alternatif VARCHAR(255),
    ordre_affichage INT NOT NULL DEFAULT 1,


    CONSTRAINT fk_image_menu_menu
        FOREIGN KEY (id_menu)
        REFERENCES menu(id_menu)
);




CREATE TABLE horaire (
    id_horaire INT AUTO_INCREMENT PRIMARY KEY,
    jour_semaine VARCHAR(20) NOT NULL,
    heure_ouverture TIME,
    heure_fermeture TIME,
    est_ferme BOOLEAN NOT NULL DEFAULT FALSE
);




CREATE TABLE annulation_commande (
    id_annulation INT AUTO_INCREMENT PRIMARY KEY,
    id_commande INT NOT NULL UNIQUE,
    id_utilisateur INT NOT NULL,
    date_annulation DATETIME NOT NULL,
    motif_annulation TEXT,
    mode_contact VARCHAR(50),
    date_contact DATETIME,


    CONSTRAINT fk_annulation_commande
        FOREIGN KEY (id_commande)
        REFERENCES commande(id_commande),


    CONSTRAINT fk_annulation_utilisateur
        FOREIGN KEY (id_utilisateur)
        REFERENCES utilisateur(id_utilisateur)
);




CREATE TABLE pret_materiel (
    id_pret INT AUTO_INCREMENT PRIMARY KEY,
    id_commande INT NOT NULL,
    description_materiel TEXT NOT NULL,
    date_pret DATE NOT NULL,
    date_limite_retour DATE NOT NULL,
    date_retour_effectif DATE,
    statut_restitution VARCHAR(50) NOT NULL DEFAULT 'en_attente',
    frais_penalite DECIMAL(10,2) NOT NULL DEFAULT 0,


    CONSTRAINT fk_pret_materiel_commande
        FOREIGN KEY (id_commande)
        REFERENCES commande(id_commande)
);




CREATE TABLE modification_commande (
    id_modification INT AUTO_INCREMENT PRIMARY KEY,
    id_commande INT NOT NULL,
    id_utilisateur INT NOT NULL,
    date_modification DATETIME NOT NULL,
    description_modification TEXT NOT NULL,
    motif_modification TEXT,
    mode_contact VARCHAR(50),
    date_contact DATETIME,


    CONSTRAINT fk_modification_commande
        FOREIGN KEY (id_commande)
        REFERENCES commande(id_commande),


    CONSTRAINT fk_modification_utilisateur
        FOREIGN KEY (id_utilisateur)
        REFERENCES utilisateur(id_utilisateur)
);