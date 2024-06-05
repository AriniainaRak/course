-- Active: 1717405712043@@127.0.0.1@5432@course
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
insert into equipes(name, username, password) values ('A', 'Arinirina', 12345);
insert into equipes(name, username, password) values ('B', 'Ariniaina', 12345);
insert into equipes(name, username, password) values ('D', 'Arimalala', 12345);
insert into equipes(name, username, password) values ('E', 'Ambinintsoa', 12345);
insert into equipes(name, username, password) values ('C', 'Arison', 12345);

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
    dossard_number INT NOT NULL,
    idgender int,
    birth_date DATE NOT NULL,
    idequipe INT,
    FOREIGN KEY (idequipe) REFERENCES equipes(id),
    FOREIGN KEY (idgender) REFERENCES genres(id)
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
    rang INT NOT NULL,
    datedepart date,
    heure_depart time
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

create table genres(
    id serial PRIMARY KEY NOT NULL,
    name varchar(255)
);

-- Table pour les résultats
CREATE TABLE results (
    id SERIAL PRIMARY KEY,
    idetape INT,
    idcoureur INT,
    heure_arrive timestamp,
    FOREIGN KEY (idetape) REFERENCES etapes(id),
    FOREIGN KEY (idcoureur) REFERENCES coureurs(id)
);

-- Table pour les pénalités (si nécessaire)
CREATE TABLE penalities (
    id SERIAL PRIMARY KEY,
    idetape INT,
    idequipe int,
    penalty TIME,
    FOREIGN KEY (idetape) REFERENCES etapes(id),
    FOREIGN KEY (idequipe) REFERENCES equipes(id)
);

create table classements(
    id serial primary key not null,
    idcoureur int,
    classement int,
    idetape int,
    foreign key (idcoureur) coureurs(id),
    foreign key (idetape) etapes(id)
);

drop view detail_resultat;
create or replace view detail_resultat as
select
e.*,
r.heure_arrive,
c.name as name_coureur,c.dossard_number,c.idequipe,eq.name as name_equipe
from results r
join etapes e on e.id = r.idetape
join coureurs c on r.idcoureur = c.id
join equipes eq on c.idequipe = eq.id;

SELECT
    id,
    name_coureur,
    name as etape,
    name_equipe,
    longueur,
    datedepart,
    heure_depart,
    heure_arrive,
    dossard_number,
    heure_arrive - (datedepart + heure_depart::time) AS temps_ecoule
FROM
    detail_resultat
order by temps_ecoule;


SELECT
    id,
    name,
    longueur,
    coureurs_per_equipe,
    rang,
    datedepart,
    heure_depart,
    heure_arrive,
    name_coureur,
    dossard_number,
    heure_arrive - (datedepart + heure_depart::time) AS temps_ecoule,
    DENSE_RANK() OVER (ORDER BY heure_arrive - (datedepart + heure_depart::time)) AS rang_coureur
FROM
    detail_resultat
ORDER BY
    temps_ecoule;



drop view  classement;
create or replace view classement as
SELECT
    id,
    ROW_NUMBER() OVER (PARTITION BY id ORDER BY heure_arrive - (datedepart + heure_depart::time)) AS rang,
    name_coureur,
    name_equipe,
    dossard_number,
    name as etape,
    datedepart,
    heure_depart,
    heure_arrive,
    heure_arrive - (datedepart + heure_depart::time) AS temps_ecoule,
    DENSE_RANK() OVER (ORDER BY heure_arrive - (datedepart + heure_depart::time)) AS rang_coureur
FROM
    detail_resultat
ORDER BY
    id, rang;

create or replace view classement_point as
SELECT
    c.id,
    c.rang,
    c.name_coureur,
    c.name_equipe,
    c.dossard_number,
    c.etape,
    c.datedepart,
    c.heure_depart,
    c.heure_arrive,
    c.temps_ecoule,
    COALESCE(p.points, 0) AS points
FROM
    classement c
LEFT JOIN
    points p ON c.rang = p.classement
ORDER BY
    c.id, c.rang;

-- classement generale par equipe
create or replace view classement_equipe as
SELECT
    equipe_nom,
    SUM(points) AS total_points
FROM
    classement_generale
GROUP BY
    equipe_nom
ORDER BY
    total_points DESC;

-- classement par equipe homme
create or replace view homme_rang as
SELECT
    equipe_nom,
    SUM(points) AS total_points
FROM
    classement_homme
GROUP BY
    equipe_nom
ORDER BY
    total_points DESC;

-- classement par equipe femme
create or replace view femme_rang as
SELECT
    equipe_nom,
    SUM(points) AS total_points
FROM
    classement_femme
GROUP BY
    equipe_nom
ORDER BY
    total_points DESC;

-- classement par equipe junior
create or replace view junior_rang as
SELECT
    equipe_nom,
    SUM(points) AS total_points
FROM
    classement_junior
GROUP BY
    equipe_nom
ORDER BY
    total_points DESC;











CREATE OR REPLACE VIEW course_detail AS
WITH rang_coureur AS (
    SELECT
        r.id AS id_resultat,
        e.id AS idetape,
        e.name AS etape,
        e.datedepart,
        e.heure_depart AS heure_de_depart,
        e.longueur AS longueur_km,
        r.heure_arrive,
        -- r.chrono,
        c.id AS idcoureur,
        c.name AS coureur_nom,
        c.idgender AS id_category,
        g.name AS category_nom,
        eq.id AS id_equipe,
        eq.name AS equipe_nom,
        (r.heure_arrive - (e.datedepart + e.heure_depart::time)) AS temps_parcours,
        DENSE_RANK() OVER (PARTITION BY e.id ORDER BY (r.heure_arrive - (e.datedepart + e.heure_depart::time))) AS rang
    FROM
        results r
        JOIN etapes e ON r.idetape = e.id
        JOIN coureurs c ON r.idcoureur = c.id
        JOIN genres g ON c.idgender = g.id
        JOIN equipes eq ON c.idequipe = eq.id
)
SELECT * FROM rang_coureur;


create or replace VIEW classement_generale as
SELECT
    rc.id_resultat,
    rc.idetape,
    rc.etape,
    rc.datedepart,
    rc.heure_de_depart,
    rc.longueur_km,
    rc.heure_arrive,
    -- rc.chrono,
    rc.idcoureur,
    rc.coureur_nom,
    rc.id_category,
    rc.category_nom,
    rc.id_equipe,
    rc.equipe_nom,
    rc.temps_parcours,
    rc.rang,
    COALESCE(p.points, 0) AS points  -- Vérifiez que "points" est le bon nom de colonne
FROM course_detail rc
LEFT JOIN points p ON p.classement = rc.rang
ORDER BY rc.idetape, rc.rang;



CREATE OR REPLACE VIEW course_detail_homme AS
    SELECT
        r.id AS id_resultat,
        e.id AS id_etape,
        e.name AS nom_etape,
        e.datedepart,
        e.heure_depart AS heure_de_depart,
        e.longueur AS longueur_km,
        r.heure_arrive,
        -- r.chrono,
        c.id AS id_coureur,
        c.name AS coureur_nom,
        c.idgender AS id_category,
        g.name AS category_nom,  -- Correction: Utilisation de la colonne 'genre' de la table 'genres'
        eq.id AS id_equipe,
        eq.name AS equipe_nom,
        (r.heure_arrive - (e.datedepart + e.heure_depart)) AS temps_parcours,
        DENSE_RANK() OVER (PARTITION BY e.id ORDER BY (r.heure_arrive - (e.datedepart + e.heure_depart))) AS rang
        -- COALESCE(p.points, 0) AS points  -- Vérifiez que "points" est le bon nom de colonne
    FROM
        results r
        JOIN etapes e ON r.idetape = e.id
        JOIN coureurs c ON r.idcoureur = c.id
        JOIN genres g ON c.idgender = g.id
        JOIN equipes eq ON c.idequipe = eq.id
        -- LEFT JOIN points p ON p.classement = rc.rang
    WHERE
        g.name = 'M';

drop view classement_homme;
create or replace view classement_homme as
SELECT
    rc.id_resultat,
    rc.id_etape,
    rc.nom_etape,
    rc.datedepart,
    rc.heure_de_depart,
    rc.longueur_km,
    rc.heure_arrive,
    -- rc.chrono,
    rc.id_coureur,
    rc.coureur_nom,
    rc.id_category,
    rc.category_nom,
    rc.id_equipe,
    rc.equipe_nom,
    rc.temps_parcours,
    rc.rang,
    COALESCE(p.points, 0) AS points  -- Vérifiez que "points" est le bon nom de colonne
FROM course_detail_homme rc
LEFT JOIN points p ON p.classement = rc.rang
ORDER BY rc.id_etape, rc.rang;

SELECT
    rc.id_resultat,
    rc.id_etape,
    rc.nom_etape,
    rc.datedepart,
    rc.heure_de_depart,
    rc.longueur_km,
    rc.arrivee,
    rc.chrono,
    rc.id_coureur,
    rc.coureur_nom,
    rc.id_category,
    rc.category_nom,
    rc.id_equipe,
    rc.equipe_nom,
    rc.temps_parcours,
    rc.rang,
    COALESCE(p.point, 0) AS points
FROM
    rang_coureur rc
LEFT JOIN
    points p ON p.classement = rc.rang;


CREATE OR REPLACE VIEW course_detail_femme AS
    SELECT
        r.id AS id_resultat,
        e.id AS id_etape,
        e.name AS nom_etape,
        e.datedepart,
        e.heure_depart AS heure_de_depart,
        e.longueur AS longueur_km,
        r.heure_arrive,
        -- r.chrono,
        c.id AS id_coureur,
        c.name AS coureur_nom,
        c.idgender AS id_category,
        g.name AS category_nom,  -- Correction: Utilisation de la colonne 'genre' de la table 'genres'
        eq.id AS id_equipe,
        eq.name AS equipe_nom,
        (r.heure_arrive - (e.datedepart + e.heure_depart)) AS temps_parcours,
        DENSE_RANK() OVER (PARTITION BY e.id ORDER BY (r.heure_arrive - (e.datedepart + e.heure_depart))) AS rang
        -- COALESCE(p.points, 0) AS points  -- Vérifiez que "points" est le bon nom de colonne
    FROM
        results r
        JOIN etapes e ON r.idetape = e.id
        JOIN coureurs c ON r.idcoureur = c.id
        JOIN genres g ON c.idgender = g.id
        JOIN equipes eq ON c.idequipe = eq.id
        -- LEFT JOIN points p ON p.classement = rc.rang
    WHERE
        g.name = 'F';

create or replace view classement_femme as
SELECT
    rc.id_resultat,
    rc.id_etape,
    rc.nom_etape,
    rc.datedepart,
    rc.heure_de_depart,
    rc.longueur_km,
    rc.heure_arrive,
    -- rc.chrono,
    rc.id_coureur,
    rc.coureur_nom,
    rc.id_category,
    rc.category_nom,
    rc.id_equipe,
    rc.equipe_nom,
    rc.temps_parcours,
    rc.rang,
    COALESCE(p.points, 0) AS points  -- Vérifiez que "points" est le bon nom de colonne
FROM course_detail_femme rc
LEFT JOIN points p ON p.classement = rc.rang
ORDER BY rc.id_etape, rc.rang;


SELECT
    rc.id_resultat,
    rc.id_etape,
    rc.nom_etape,
    rc.datedepart,
    rc.heure_de_depart,
    rc.longueur_km,
    rc.arrivee,
    rc.chrono,
    rc.id_coureur,
    rc.coureur_nom,
    rc.id_category,
    rc.category_nom,
    rc.id_equipe,
    rc.equipe_nom,
    rc.temps_parcours,
    rc.rang,
    COALESCE(p.point, 0) AS points
FROM
    course_detail rc
LEFT JOIN
    points p ON p.classement = rc.rang;


CREATE OR REPLACE VIEW course_detail_junior AS
    SELECT
        r.id AS id_resultat,
        e.id AS id_etape,
        e.name AS nom_etape,
        e.datedepart,
        e.heure_depart AS heure_de_depart,
        e.longueur AS longueur_km,
        r.heure_arrive,
        c.id AS id_coureur,
        c.name AS coureur_nom,
        c.birth_date,
        c.idgender AS id_category,
        g.name AS category_nom,  -- Correction: Utilisation de la colonne 'nom' de la table 'categories'
        eq.id AS id_equipe,
        eq.name AS equipe_nom,
        (r.heure_arrive - (e.datedepart + e.heure_depart)) AS temps_parcours,
        DENSE_RANK() OVER (PARTITION BY e.id ORDER BY (r.heure_arrive - (e.datedepart + e.heure_depart))) AS rang
    FROM
        results r
        JOIN etapes e ON r.idetape = e.id
        JOIN coureurs c ON r.idcoureur = c.id
        JOIN categories g ON c.idgender = g.id  -- Correction: Utilisation de la table 'categories'
        JOIN equipes eq ON c.idequipe = eq.id
    WHERE
        age(e.datedepart, c.birth_date) <= INTERVAL '18 years';  -- Correction: Utilisation de la colonne 'nom' de la table 'categories'
CREATE OR REPLACE VIEW classement_junior as
SELECT
    rc.id_resultat,
    rc.id_etape,
    rc.nom_etape,
    rc.datedepart,
    rc.heure_de_depart,
    rc.longueur_km,
    rc.heure_arrive,
    -- rc.chrono,
    rc.id_coureur,
    rc.coureur_nom,
    rc.id_category,
    rc.category_nom,
    rc.id_equipe,
    rc.equipe_nom,
    rc.temps_parcours,
    rc.rang,
    COALESCE(p.points, 0) AS points  -- Vérifiez que "points" est le bon nom de colonne
FROM course_detail_junior rc
LEFT JOIN points p ON p.classement = rc.rang
ORDER BY rc.id_etape, rc.rang;

















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
