-----------------V5.0.0---------------------
/*
--CENTRO DE SOLUCIONES DE GESTIÓN		----
--Subdirección de tecnología			----
--										----
--SCRIPT de INSTALACION de DATOS INICIALES	----
--										----
*/
----------------------------------------
------------------------------------------------------------------------------------------------
/*
--Reglas de confidencialidad																----
--Clasificación: Clasificado.																----
--Forma de distribución: Script SQL.														----
--Este documento contiene información propietaria del CENTRO DE SOLUCIONES DE GESTIÓN 		----
--y es emitido confidencialmente para un propósito específico. 								----
--El que recibe el documento asume la custodia y control, comprometiéndose a no reproducir, ----
--divulgar, difundir o de cualquier manera hacer de conocimiento público su contenido, 		----
--excepto para cumplir el propósito para el cual se ha generado.							----
--Las reglas son aplicables a todo este documento.											----
*/																							
------------------------------------------------------------------------------------------------

--comienza la tranzaccion--
begin;
-- Propiedades de la BD       
	SET client_encoding = 'UTF8';
	SET standard_conforming_strings = off;
	SET check_function_bodies = false;
	SET client_min_messages = warning;
	SET escape_string_warning = off;

INSERT INTO mod_datosmaestros.conf_version ( versionscript,  versionscriptinicial,  esquema,  esquemarelacion,  tiposcript,  classcript,  script) 
VALUES ('V5.0.0', 'V5.0.0','mod_nomencladores',null,'I','D','I');
---------------------------------------------------------------------------------------------------------------------------
--Datos del esquema mod_nomencladores
---------------------------------------------------------------------------------------------------------------------------	

	SET search_path = mod_nomencladores, pg_catalog;

----------------------------------------
INSERT INTO nom_especialidad (idespecialidad, idpadre, ordenizq, ordender, abrevespecialidad, denespecialidad, codespecialidad, orden, fechaini, fechafin) VALUES (1, 1, 1, 2, 'Ng', 'Ninguna', '00', 1, '01/01/2009', NULL);
----------------------------------------
INSERT INTO nom_pais (idpais, nombrepais, codigopais, siglas) VALUES (1, 'Cuba', '192', 'CUB');
INSERT INTO nom_pais (idpais, nombrepais, codigopais, siglas) VALUES (2, 'Tuvalu', '798', 'TUV');
INSERT INTO nom_pais (idpais, nombrepais, codigopais, siglas) VALUES (3, 'Turkmenistán', '795', 'TKN');
INSERT INTO nom_pais (idpais, nombrepais, codigopais, siglas) VALUES (4, 'Suecia', '752', 'SWE');
INSERT INTO nom_pais (idpais, nombrepais, codigopais, siglas) VALUES (5, 'Turquía', '792', 'TUR');
INSERT INTO nom_pais (idpais, nombrepais, codigopais, siglas) VALUES (6, 'Angola', '024', 'AGO');
INSERT INTO nom_pais (idpais, nombrepais, codigopais, siglas) VALUES (7, 'Anguilla', '041', 'AIA');
INSERT INTO nom_pais (idpais, nombrepais, codigopais, siglas) VALUES (8, 'Albania', '008', 'ALB');
INSERT INTO nom_pais (idpais, nombrepais, codigopais, siglas) VALUES (9, 'Andorra', '020', 'AND');
INSERT INTO nom_pais (idpais, nombrepais, codigopais, siglas) VALUES (10, 'Antillas Holandesas', '530', 'ANT');
INSERT INTO nom_pais (idpais, nombrepais, codigopais, siglas) VALUES (11, 'Austria', '040', 'ARG');
INSERT INTO nom_pais (idpais, nombrepais, codigopais, siglas) VALUES (13, 'Armenia', '051', 'ARM');
INSERT INTO nom_pais (idpais, nombrepais, codigopais, siglas) VALUES (14, 'Samoa Estadounidense', '016', 'ASM');
INSERT INTO nom_pais (idpais, nombrepais, codigopais, siglas) VALUES (15, 'Antartida', '333', 'ATA');
INSERT INTO nom_pais (idpais, nombrepais, codigopais, siglas) VALUES (16, 'Ucrania', '804', 'UKR');
INSERT INTO nom_pais (idpais, nombrepais, codigopais, siglas) VALUES (17, 'Antigua y Barbuda', '028', 'ATG');
INSERT INTO nom_pais (idpais, nombrepais, codigopais, siglas) VALUES (18, 'Australia', '053', 'AUS');
INSERT INTO nom_pais (idpais, nombrepais, codigopais, siglas) VALUES (20, 'Azerbaiyan', '031', 'AZE');
INSERT INTO nom_pais (idpais, nombrepais, codigopais, siglas) VALUES (21, 'Burundi', '108', 'BDI');
INSERT INTO nom_pais (idpais, nombrepais, codigopais, siglas) VALUES (22, 'Suriname', '740', 'SUR');
INSERT INTO nom_pais (idpais, nombrepais, codigopais, siglas) VALUES (23, 'Benin', '204', 'BEN');
INSERT INTO nom_pais (idpais, nombrepais, codigopais, siglas) VALUES (24, 'Burkina Faso', '854', 'BFA');
INSERT INTO nom_pais (idpais, nombrepais, codigopais, siglas) VALUES (25, 'Bangladesh', '050', 'BGD');
INSERT INTO nom_pais (idpais, nombrepais, codigopais, siglas) VALUES (26, 'Bulgaria', '100', 'BGR');
INSERT INTO nom_pais (idpais, nombrepais, codigopais, siglas) VALUES (27, 'Bahrein', '048', 'BHR');
INSERT INTO nom_pais (idpais, nombrepais, codigopais, siglas) VALUES (28, 'Bahamas', '077', 'BHS');
INSERT INTO nom_pais (idpais, nombrepais, codigopais, siglas) VALUES (29, 'Bosnia y Herzegovina', '029', 'BIH');
INSERT INTO nom_pais (idpais, nombrepais, codigopais, siglas) VALUES (30, 'Belarus', '112', 'BLR');
INSERT INTO nom_pais (idpais, nombrepais, codigopais, siglas) VALUES (31, 'Belice', '084', 'BLZ');
INSERT INTO nom_pais (idpais, nombrepais, codigopais, siglas) VALUES (32, 'Bermudas', '060', 'BMU');
INSERT INTO nom_pais (idpais, nombrepais, codigopais, siglas) VALUES (33, 'Bolivia', '068', 'BOL');
INSERT INTO nom_pais (idpais, nombrepais, codigopais, siglas) VALUES (34, 'Brasil', '076', 'BRA');
INSERT INTO nom_pais (idpais, nombrepais, codigopais, siglas) VALUES (35, 'Barbados', '052', 'BRB');
INSERT INTO nom_pais (idpais, nombrepais, codigopais, siglas) VALUES (36, 'Brunei Darussalam', '096', 'BRN');
INSERT INTO nom_pais (idpais, nombrepais, codigopais, siglas) VALUES (37, 'Bhutan', '064', 'BTN');
INSERT INTO nom_pais (idpais, nombrepais, codigopais, siglas) VALUES (38, 'Uganda', '800', 'UGA');
INSERT INTO nom_pais (idpais, nombrepais, codigopais, siglas) VALUES (39, 'Botswana', '072', 'BWA');
INSERT INTO nom_pais (idpais, nombrepais, codigopais, siglas) VALUES (40, 'Republica Centroafricana', '640', 'CAF');
INSERT INTO nom_pais (idpais, nombrepais, codigopais, siglas) VALUES (41, 'Canada', '149', 'CAN');
INSERT INTO nom_pais (idpais, nombrepais, codigopais, siglas) VALUES (42, 'Islas Cocos (Keeling)', '165', 'CCK');
INSERT INTO nom_pais (idpais, nombrepais, codigopais, siglas) VALUES (43, 'Suiza', '756', 'CHE');
INSERT INTO nom_pais (idpais, nombrepais, codigopais, siglas) VALUES (44, 'Chile', '152', 'CHL');
INSERT INTO nom_pais (idpais, nombrepais, codigopais, siglas) VALUES (45, 'Republica Popular de China', '156', 'CHN');
INSERT INTO nom_pais (idpais, nombrepais, codigopais, siglas) VALUES (46, 'Costa de Marfil', '384', 'CIV');
INSERT INTO nom_pais (idpais, nombrepais, codigopais, siglas) VALUES (47, 'Swazilandia', '748', 'SWZ');
INSERT INTO nom_pais (idpais, nombrepais, codigopais, siglas) VALUES (49, 'Congo', '178', 'COG');
INSERT INTO nom_pais (idpais, nombrepais, codigopais, siglas) VALUES (50, 'Islas Cook', '184', 'COK');
INSERT INTO nom_pais (idpais, nombrepais, codigopais, siglas) VALUES (51, 'Colombia', '170', 'COL');
INSERT INTO nom_pais (idpais, nombrepais, codigopais, siglas) VALUES (52, 'Comoras', '173', 'COM');
INSERT INTO nom_pais (idpais, nombrepais, codigopais, siglas) VALUES (53, 'Cabo Verde', '132', 'CPV');
INSERT INTO nom_pais (idpais, nombrepais, codigopais, siglas) VALUES (54, 'Costa Rica', '188', 'CRI');
INSERT INTO nom_pais (idpais, nombrepais, codigopais, siglas) VALUES (55, 'Isla Christmas', '???', 'CXR');
INSERT INTO nom_pais (idpais, nombrepais, codigopais, siglas) VALUES (56, 'Mayotte', '175', 'MYT');
INSERT INTO nom_pais (idpais, nombrepais, codigopais, siglas) VALUES (57, 'Chipre', '196', 'CYP');
INSERT INTO nom_pais (idpais, nombrepais, codigopais, siglas) VALUES (58, 'Republica Checa', '644', 'CZE');
INSERT INTO nom_pais (idpais, nombrepais, codigopais, siglas) VALUES (59, 'Alemania', '276', 'DEU');
INSERT INTO nom_pais (idpais, nombrepais, codigopais, siglas) VALUES (60, 'Djibouti', '262', 'DJI');
INSERT INTO nom_pais (idpais, nombrepais, codigopais, siglas) VALUES (61, 'Dominica', '212', 'DMA');
INSERT INTO nom_pais (idpais, nombrepais, codigopais, siglas) VALUES (62, 'Dinamarca', '208', 'DNK');
INSERT INTO nom_pais (idpais, nombrepais, codigopais, siglas) VALUES (63, 'Republica Dominicana', '647', 'DOM');
INSERT INTO nom_pais (idpais, nombrepais, codigopais, siglas) VALUES (64, 'Argelia', '012', 'DZA');
INSERT INTO nom_pais (idpais, nombrepais, codigopais, siglas) VALUES (66, 'Uruguay', '858', 'URY');
INSERT INTO nom_pais (idpais, nombrepais, codigopais, siglas) VALUES (68, 'Sahara Occidental', '732', 'ESH');
INSERT INTO nom_pais (idpais, nombrepais, codigopais, siglas) VALUES (69, 'Estonia', '233', 'EST');
INSERT INTO nom_pais (idpais, nombrepais, codigopais, siglas) VALUES (71, 'Finlandia', '246', 'FIN');
INSERT INTO nom_pais (idpais, nombrepais, codigopais, siglas) VALUES (72, 'Fiji', '242', 'FJI');
INSERT INTO nom_pais (idpais, nombrepais, codigopais, siglas) VALUES (73, 'Islas Malvinas y Dependencias', '238', 'FLK');
INSERT INTO nom_pais (idpais, nombrepais, codigopais, siglas) VALUES (74, 'Islas Feroe', '234', 'FRO');
INSERT INTO nom_pais (idpais, nombrepais, codigopais, siglas) VALUES (75, 'Estados  Federados de Micronesia', '583', 'FSM');
INSERT INTO nom_pais (idpais, nombrepais, codigopais, siglas) VALUES (76, 'Francia', '250', 'FRA');
INSERT INTO nom_pais (idpais, nombrepais, codigopais, siglas) VALUES (77, 'Tailandia', '764', 'THA');
INSERT INTO nom_pais (idpais, nombrepais, codigopais, siglas) VALUES (78, 'Reino Unido', '826', 'GBR');
INSERT INTO nom_pais (idpais, nombrepais, codigopais, siglas) VALUES (79, 'Georgia', '268', 'GEO');
INSERT INTO nom_pais (idpais, nombrepais, codigopais, siglas) VALUES (80, 'Ghana', '288', 'GHA');
INSERT INTO nom_pais (idpais, nombrepais, codigopais, siglas) VALUES (81, 'Gibraltar', '292', 'GIB');
INSERT INTO nom_pais (idpais, nombrepais, codigopais, siglas) VALUES (82, 'Guinea', '324', 'GIN');
INSERT INTO nom_pais (idpais, nombrepais, codigopais, siglas) VALUES (83, 'Guadeloupe', '309', 'GLP');
INSERT INTO nom_pais (idpais, nombrepais, codigopais, siglas) VALUES (84, 'Gambia', '270', 'GMB');
INSERT INTO nom_pais (idpais, nombrepais, codigopais, siglas) VALUES (85, 'Guinea-Bissau', '624', 'GNB');
INSERT INTO nom_pais (idpais, nombrepais, codigopais, siglas) VALUES (86, 'Guinea Ecuatorial', '226', 'GNQ');
INSERT INTO nom_pais (idpais, nombrepais, codigopais, siglas) VALUES (87, 'Grecia', '300', 'GRC');
INSERT INTO nom_pais (idpais, nombrepais, codigopais, siglas) VALUES (88, 'Granada', '308', 'GRD');
INSERT INTO nom_pais (idpais, nombrepais, codigopais, siglas) VALUES (89, 'Groenlandia', '304', 'GRL');
INSERT INTO nom_pais (idpais, nombrepais, codigopais, siglas) VALUES (90, 'Guatemala', '320', 'GTM');
INSERT INTO nom_pais (idpais, nombrepais, codigopais, siglas) VALUES (91, 'Guayana Francesa', '254', 'GUF');
INSERT INTO nom_pais (idpais, nombrepais, codigopais, siglas) VALUES (92, 'Guam', '316', 'GUM');
INSERT INTO nom_pais (idpais, nombrepais, codigopais, siglas) VALUES (93, 'Guyana', '328', 'GUY');
INSERT INTO nom_pais (idpais, nombrepais, codigopais, siglas) VALUES (94, 'Hong Kong', '344', 'HKG');
INSERT INTO nom_pais (idpais, nombrepais, codigopais, siglas) VALUES (95, 'Islas Heard y Mcdonald', '999', 'HMD');
INSERT INTO nom_pais (idpais, nombrepais, codigopais, siglas) VALUES (96, 'Honduras', '340', 'HND');
INSERT INTO nom_pais (idpais, nombrepais, codigopais, siglas) VALUES (97, 'Croacia', '191', 'HRV');
INSERT INTO nom_pais (idpais, nombrepais, codigopais, siglas) VALUES (98, 'Uzbekistán', '860', 'UZB');
INSERT INTO nom_pais (idpais, nombrepais, codigopais, siglas) VALUES (99, 'Vanuatu', '548', 'VUT');
INSERT INTO nom_pais (idpais, nombrepais, codigopais, siglas) VALUES (100, 'Indonesia', '360', 'IDN');
INSERT INTO nom_pais (idpais, nombrepais, codigopais, siglas) VALUES (101, 'India', '356', 'IND');
INSERT INTO nom_pais (idpais, nombrepais, codigopais, siglas) VALUES (102, 'Territorio Britanico del Oceano Indico', '787', 'IOT');
INSERT INTO nom_pais (idpais, nombrepais, codigopais, siglas) VALUES (103, 'Republica de Irlanda (Eire)', '372', 'IRL');
INSERT INTO nom_pais (idpais, nombrepais, codigopais, siglas) VALUES (104, 'Tayikistán', '762', 'TJK');
INSERT INTO nom_pais (idpais, nombrepais, codigopais, siglas) VALUES (105, 'Iraq', '368', 'IRQ');
INSERT INTO nom_pais (idpais, nombrepais, codigopais, siglas) VALUES (106, 'Islandia', '352', 'ISL');
----------------------------------------
INSERT INTO nom_tipodpa (idtipodpa, denominacion, orden, fechaini, fechafin) VALUES (2, 'Provincia', 1, '2009-01-01', NULL);
INSERT INTO nom_tipodpa (idtipodpa, denominacion, orden, fechaini, fechafin) VALUES (3, 'Municipio', 1, '2009-01-01', NULL);
----------------------------------------
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (1, 1, 1, 32, 'CIUDAD DE LA HABANA', 'CH', 2, '03', 1);	
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (2, 1, 2, 3, 'PLAYA', NULL, 3, '0301', 1);
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (3, 1, 4, 5, 'PLAZA DE LA REVOLUCION', NULL, 3, '0302', 1);
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (4, 1, 6, 7, 'CENTRO HABANA', NULL, 3, '0303', 1);
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (5, 1, 8, 9, 'LA HABANA VIEJA', NULL, 3, '0304', 1);
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (6, 1, 10, 11, 'REGLA', NULL, 3, '0305', 1);
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (7, 1, 12, 13, 'LA HABANA DEL ESTE', NULL, 3, '0306', 1);
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (8, 1, 14, 15, 'GUANABACOA', NULL, 3, '0307', 1);
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (9, 1, 16, 17, 'SAN MIGUEL DEL PADRON', NULL, 3, '0308', 1);
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (10, 1, 18, 19, 'DIEZ DE OCTUBRE', NULL, 3, '0309', 1);
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (11, 1, 20, 21, 'CERRO', NULL, 3, '0310', 1);
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (12, 1, 22, 23, 'MARIANAO', NULL, 3, '0311', 1);
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (13, 1, 24, 25, 'LA LISA', NULL, 3, '0312', 1);
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (14, 1, 26, 27, 'BOYEROS', NULL, 3, '0313', 1);
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (15, 1, 28, 29, 'ARROYO NARANJO', NULL, 3, '0314', 1);
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (16, 1, 30, 31, 'COTORRO', NULL, 3, '0315', 1);
----------------------------------------

--fin de la transaccion
COMMIT;