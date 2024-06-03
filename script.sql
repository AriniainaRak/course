-- Active: 1716373515129@@127.0.0.1@5432@course
-- Table pour les admins
CREATE TABLE admins (
    id  SERIAL PRIMARY KEY,
    username VARCHAR(255) NOT NULL UNIQUE,
    pswd VARCHAR(255) NOT NULL
);

insert into admins (username, pswd) values ('admin', '1234');

-- Table pour les équipes
CREATE TABLE equipes (
    id SERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL UNIQUE,
    username VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);
insert into equipes(name, username, password) values ('Ankoay', 'Arinirina', 12345);
insert into equipes(name, username, password) values ('Vorona', 'Ariniaina', 12345);

-- Table pour les catégories
CREATE TABLE categories (
    id SERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL UNIQUE
);

insert into categories(name) values ('homme');
insert into categories(name) values ('femme');
insert into categories(name) values ('senior');
insert into categories(name) values ('junior');


-- Table pour les coureurs
CREATE TABLE coureurs (
    id SERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    dossard_number INT NOT NULL UNIQUE,
    gender varchar(255) NOT NULL,
    birth_date DATE NOT NULL,
    idequipe INT,
    FOREIGN KEY (idequipe) REFERENCES equipes(id)
);
insert into coureurs(name, dossard_number, gender, birth_date, idequipe) values ('Cristina', 13, 'F', '13-11-01', 1 );
insert into coureurs(name, dossard_number, gender, birth_date, idequipe) values ('Mioty', 07, 'F', '13-07-01', 2 );

CREATE TABLE etape_coureurs (
    idetape INTEGER NOT NULL,
    idcoureur INTEGER NOT NULL,
    PRIMARY KEY (idetape, idcoureur),
    FOREIGN KEY (idetape) REFERENCES etapes(id),
    FOREIGN KEY (idcoureur) REFERENCES coureurs(id)
);

-- Table pour associer les coureurs aux catégories
CREATE TABLE coureur_categories (
    idcoureur INT,
    idcategory INT,
    PRIMARY KEY (idcoureur, idcategory),
    FOREIGN KEY (idcoureur) REFERENCES coureurs(id),
    FOREIGN KEY (idcategory) REFERENCES categories(id)
);

-- Table pour les étapes
CREATE TABLE etapes (
    id SERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    longueur DECIMAL(5, 2) NOT NULL,
    coureurs_per_equipe INT NOT NULL,
    rang INT NOT NULL
);
insert into etapes(name, longueur, coureurs_per_equipe, rang) values ('Anosy-67Ha', 10, 2, 1);

-- Table pour les affectations des coureurs aux étapes
CREATE TABLE etape_assignments (
    id SERIAL PRIMARY KEY,
    idetape INT,
    idcoureur INT,
    -- idequipe INT,
    -- start_time TIME,
    -- end_time TIME,
    FOREIGN KEY (idetape) REFERENCES etapes(id),
    FOREIGN KEY (idcoureur) REFERENCES coureurs(id)
    -- FOREIGN KEY (idequipe) REFERENCES equipes(id)
);

create table chronos(
    id serial primary key not null,
    idetape INT,
    idcoureur INT,
    heure_depart TIME,
    heure_arrive TIME,
    FOREIGN KEY (idetape) REFERENCES etapes(id),
    FOREIGN KEY (idcoureur) REFERENCES coureurs(id)
);

create table points (
    id serial primary key not null,
    classement int,
    points int
);

create table genre(
    id serial PRIMARY KEY NOT NULL,
    name varchar(255)
);

-- Table pour les résultats
CREATE TABLE results (
    id SERIAL PRIMARY KEY,
    idetape_assignment INT,
    points INT,
    penalty_time TIME,
    FOREIGN KEY (idetape_assignment) REFERENCES etape_assignments(id)
);

-- Table pour les pénalités (si nécessaire)
CREATE TABLE penalties (
    id SERIAL PRIMARY KEY,
    idetape_assignment INT,
    penalty_description VARCHAR(255),
    penalty_time TIME,
    FOREIGN KEY (idetape_assignment) REFERENCES etape_assignments(id)
);

























-- -- Active: 1716373515129@@127.0.0.1@5432@course
-- create table admins (
--     id serial primary key not null,
--     username varchar(255),
--     pswd varchar (255)
-- );

-- create table equipes (
--     id serial primary key not null,
--     username varchar(255),
--     pwd varchar(100)
-- );

-- create table finitions (
--     id serial primary key not null,
--     finition VARCHAR(255),
--     -- prix decimal,
--     porcentage decimal
-- );

-- create table maisons (
--     id serial primary key not null,
--     type_maison varchar(255),
--     duree int,
--     description varchar (255),
--     surface int,
-- );

-- create table travauxs (
--     id serial primary key not null,
--     type_travaux varchar(255),
--     code varchar(255),
--     unite varchar(255),
--     prix_unitaire decimal,
--     quantite decimal,
--     id_maison int,
--     foreign key (id_maison) references maisons(id)
-- );

-- -- mikikikika

-- -- create table devis (
-- --     id serial primary key not null,
-- --     idtype_travaux int,
-- --     designation varchar(255),
-- --     unite varchar (255),
-- --     quantite decimal(10, 2),
-- --     prix_unitaire decimal(10, 2)
-- -- );

-- create table devis (
--     id serial primary key not null,
-- 	idclient int,
--     ref_devis varchar(255),
--     idtype_maison int,
--     idtype_finition int,
--     date_devis date default now(),
--     date_debut date,
--     date_fin date,
--     lieu varchar(255),
--     foreign key (idtype_maison) references maisons(id),
--     foreign key (idtype_finition) references finitions(id),
--     foreign key (idclient) references clients(id)
-- );

-- create table paiements(
--     id serial primary key not null,
--     ref_paiement varchar(255),
--     iddevis int,
--     date_paie date,
--     montant decimal,
--     foreign key (iddevis) references devis(id)
-- );

-- -- create table devis_clients(
-- --     id serial primary key not null,
-- --     nom_client varchar(255),
-- --     idtype_maison int,
-- --     idtype_finition int,
-- --     date_debut date,
-- -- );

-- -- Creation de vues
-- create or replace view travaux_maisons as
-- select m.id,m.type_maison,m.description,m.surface,t.id as idtravaux,t.type_travaux,t.code,t.unite,t.prix_unitaire,t.quantite
-- from maisons m
-- join travauxs t on t.id_maison=m.id;

-- create or replace view prix_devis as
-- SELECT
--     ID,type_maison,
--     SUM(PRIX_UNITAIRE * QUANTITE) AS TOTAL
-- FROM
--     travaux_maisons
-- GROUP BY
--     ID,type_maison;

-- drop view detail_devis;
-- create or replace view detail_devis as
-- select d.id as id_devis,c.id as idclient,c.nom,m.type_maison,f.finition,d.date_devis,d.date_debut,d.date_fin,d.lieu,pd.total as prix
-- from devis d
-- join clients c on c.id = d.idclient
-- join maisons m on m.id = d.idtype_maison
-- join finitions f on f.id = d.idtype_finition
-- join prix_devis pd on pd.id = d.idtype_maison;

-- drop view detail_paiement;
-- create or replace view detail_paiement as
-- SELECT D.ID AS ID_DEVIS,C.NOM,M.TYPE_MAISON,F.FINITION,D.DATE_DEVIS,D.DATE_DEBUT,D.DATE_FIN,D.LIEU,PD.TOTAL AS PRIX,p.DATE_PAIE,P.MONTANT
-- FROM DEVIS D
-- JOIN CLIENTS C ON C.ID = D.IDCLIENT
-- JOIN MAISONS M ON M.ID = D.IDTYPE_MAISON
-- JOIN FINITIONS F ON F.ID = D.IDTYPE_FINITION
-- JOIN PRIX_DEVIS PD ON PD.ID = D.IDTYPE_MAISON
-- join paiements p on p.iddevis = d.id;

-- -- Nombre de Devis annuel
-- create or replace view nombre_devis as
-- SELECT
--     TO_CHAR(M,
--     'MM') AS MOIS,
--     COUNT(D.ID) AS NOMBRE_DE_DEVIS
-- FROM
--     ( SELECT GENERATE_SERIES('2024-01-01'::DATE,'2024-12-01'::DATE,'1 month') AS M ) AS MONTHS
--     LEFT JOIN DEVIS D
--     ON TO_CHAR(D.DATE_DEVIS,'MM') = TO_CHAR(MONTHS.M,'MM')
-- GROUP BY
--     MOIS
-- ORDER BY
--     MOIS;

-- -- Total de prix de devis par mois
-- create or replace view prix_devis_mensuel as
-- SELECT
--     TO_CHAR(M,'MM') AS MOIS,
--     COALESCE(SUM(DP.MONTANT),0) AS Prix_devis
-- FROM
--     ( SELECT GENERATE_SERIES('2024-01-01'::DATE,'2024-12-01'::DATE,'1 month') AS M)
--     AS MONTHS
--     LEFT JOIN DETAIL_PAIEMENT DP
--     ON TO_CHAR(DP.DATE_DEVIS,'MM') = TO_CHAR(MONTHS.M,'MM')
-- GROUP BY
--     MOIS
-- ORDER BY
--     MOIS;

-- -- Total de prix de devis
-- create or replace view prix_devis_total as
-- select sum(montant) as prix_total
-- from detail_paiement;
