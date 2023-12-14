CREATE TABLE utilisateurs
(
    id               INT PRIMARY KEY AUTO_INCREMENT,
    email         VARCHAR(255)                    NOT NULL,
    password         VARCHAR(255)                    NOT NULL,
    type_utilisateur ENUM ('abonne', 'gestionnaire') NOT NULL,
    abonne_id        INT,
    FOREIGN KEY (abonne_id) REFERENCES abonne (id)
);
