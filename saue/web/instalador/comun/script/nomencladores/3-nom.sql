------------------V6.0.0---------------------
/*
--CENTRO DE SOLUCIONES DE GESTIÓN		----
--Subdirección de tecnología (Actualizado por Rene R. Bauta)			----
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
VALUES ('V6.0.0', 'V6.0.0','mod_nomencladores',null,'I','D','I');
---------------------------------------------------------------------------------------------------------------------------
--Datos del esquema mod_nomencladores
---------------------------------------------------------------------------------------------------------------------------	

	SET search_path = mod_nomencladores, pg_catalog;

----------------------------------------
INSERT INTO mod_nomencladores.nom_especialidad (idespecialidad, idpadre, abrevespecialidad, denespecialidad, codespecialidad, nivel, fechaini, fechafin) VALUES (1, 1,'Ng', 'Ninguna', '00', 1, '01/01/2009', NULL);
INSERT INTO mod_nomencladores.nom_especialidad (idespecialidad, idpadre, abrevespecialidad, denespecialidad, codespecialidad, nivel, fechaini, fechafin) VALUES (2, 2, NULL, 'Ingenieria', '50', NULL, NULL, NULL);
INSERT INTO mod_nomencladores.nom_especialidad (idespecialidad, idpadre, abrevespecialidad, denespecialidad, codespecialidad, nivel, fechaini, fechafin) VALUES (3, 3, NULL, 'Intendencia', '33', NULL, NULL, NULL);
INSERT INTO mod_nomencladores.nom_especialidad (idespecialidad, idpadre, abrevespecialidad, denespecialidad, codespecialidad, nivel, fechaini, fechafin) VALUES (4, 4, NULL, 'Marina de Guerra', '23', NULL, NULL, NULL);
INSERT INTO mod_nomencladores.nom_especialidad (idespecialidad, idpadre, abrevespecialidad, denespecialidad, codespecialidad, nivel, fechaini, fechafin) VALUES (5, 5, NULL, 'Construcción y Alojamiento', '86', NULL, NULL, NULL);
INSERT INTO mod_nomencladores.nom_especialidad (idespecialidad, idpadre, abrevespecialidad, denespecialidad, codespecialidad, nivel, fechaini, fechafin) VALUES (6, 6, NULL, 'Tanque y transporte ', '74', NULL, NULL, NULL);
INSERT INTO mod_nomencladores.nom_especialidad (idespecialidad, idpadre, abrevespecialidad, denespecialidad, codespecialidad, nivel, fechaini, fechafin) VALUES (7, 7, NULL, 'Servicios Médicos', '37', NULL, NULL, NULL);
INSERT INTO mod_nomencladores.nom_especialidad (idespecialidad, idpadre, abrevespecialidad, denespecialidad, codespecialidad, nivel, fechaini, fechafin) VALUES (8, 8, NULL, 'Armamento', '69', NULL, NULL, NULL);
INSERT INTO mod_nomencladores.nom_especialidad (idespecialidad, idpadre, abrevespecialidad, denespecialidad, codespecialidad, nivel, fechaini, fechafin) VALUES (9, 9, NULL, 'Preparacion Combativa', '59', NULL, NULL, NULL);
INSERT INTO mod_nomencladores.nom_especialidad (idespecialidad, idpadre, abrevespecialidad, denespecialidad, codespecialidad, nivel, fechaini, fechafin) VALUES (10, 10, NULL, 'Direccion Politica', '61', NULL, NULL, NULL);
INSERT INTO mod_nomencladores.nom_especialidad (idespecialidad, idpadre, abrevespecialidad, denespecialidad, codespecialidad, nivel, fechaini, fechafin) VALUES (11, 11, NULL, 'Daafar', '54', NULL, NULL, NULL);
INSERT INTO mod_nomencladores.nom_especialidad (idespecialidad, idpadre, abrevespecialidad, denespecialidad, codespecialidad, nivel, fechaini, fechafin) VALUES (12, 12, NULL, 'Dirección de Combustible', '6', NULL, NULL, NULL);
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
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (1, 1, 1, 24, 'PINAR DEL RIO', 'PRI', 2, '21', 1);	
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (2, 1, 2, 3, 'SANDINO', NULL, 3, '2101', 1);
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (3, 1, 4, 5, 'MANTUA', NULL, 3, '2102', 1);
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (4, 1, 6, 7, 'MINAS DE MATAHAMBRE', NULL, 3, '2103', 1);
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (5, 1, 8, 9, 'VIÑALES', NULL, 3, '2104', 1);
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (6, 1, 10, 11, 'LA PALMA', NULL, 3, '2105', 1);
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (7, 1, 12, 13, 'LOS PALACIOS', NULL, 3, '2106', 1);
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (8, 1, 14, 15, 'CONSOLACION DE SUR', NULL, 3, '2107', 1);
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (9, 1, 16, 17, 'PINAR DEL RIO', NULL, 3, '2108', 1);
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (10, 1, 18, 19, 'SAN LUIS', NULL, 3, '2109', 1);
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (11, 1, 20, 21, 'SAN JUAN Y MARTINEZ', NULL, 3, '2110', 1);
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (12, 1, 22, 23, 'GUANE', NULL, 3, '2111', 1);
-----------------
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (21, 21, 1, 24, 'ARTEMISA', 'ART', 2, '22', 1);	
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (22, 21, 2, 3, 'BAHIA HONDA', NULL, 3, '2201', 1);
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (23, 21, 4, 5, 'MARIEL', NULL, 3, '2202', 1);
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (24, 21, 6, 7, 'GUANAJAY', NULL, 3, '2203', 1);
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (25, 21, 8, 9, 'CAIMITO', NULL, 3, '2204', 1);
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (26, 21, 10, 11, 'BAUTA', NULL, 3, '2205', 1);
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (27, 21, 12, 13, 'SAN ANTONIO DE LOS BAÑOS', NULL, 3, '2206', 1);
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (28, 21, 14, 15, 'GÜIRA DE MELENA', NULL, 3, '2207', 1);
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (29, 21, 16, 17, 'ALQUIZAR', NULL, 3, '2208', 1);
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (30, 21, 18, 19, 'ARTEMISA', NULL, 3, '2209', 1);
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (31, 21, 20, 21, 'CANDELARIA', NULL, 3, '2210', 1);
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (32, 21, 22, 23, 'SAN CRISTOBAL', NULL, 3, '2211', 1);
-------------------
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (41, 41, 1, 32, 'LA HABANA', 'HAB', 2, '23', 1);	
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (42, 41, 2, 3, 'PLAYA', NULL, 3, '2301', 1);
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (43, 41, 4, 5, 'PLAZA DE LA REVOLUCION', NULL, 3, '2302', 1);
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (44, 41, 6, 7, 'CENTRO HABANA', NULL, 3, '2303', 1);
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (45, 41, 8, 9, 'LA HABANA VIEJA', NULL, 3, '2304', 1);
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (46, 41, 10, 11, 'REGLA', NULL, 3, '2305', 1);
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (47, 41, 12, 13, 'LA HABANA DEL ESTE', NULL, 3, '2306', 1);
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (48, 41, 14, 15, 'GUANABACOA', NULL, 3, '2307', 1);
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (49, 41, 16, 17, 'SAN MIGUEL DEL PADRON', NULL, 3, '2308', 1);
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (50, 41, 18, 19, 'DIEZ DE OCTUBRE', NULL, 3, '2309', 1);
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (51, 41, 20, 21, 'CERRO', NULL, 3, '2310', 1);
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (52, 41, 22, 23, 'MARIANAO', NULL, 3, '2311', 1);
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (53, 41, 24, 25, 'LA LISA', NULL, 3, '2312', 1);
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (54, 41, 26, 27, 'BOYEROS', NULL, 3, '2313', 1);
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (55, 41, 28, 29, 'ARROYO NARANJO', NULL, 3, '2314', 1);
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (56, 41, 30, 31, 'COTORRO', NULL, 3, '2315', 1);
-----------------
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (71, 71, 1, 24, 'MAYABEQUE', 'MAY', 2, '24', 1);
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (72, 71, 2, 3, 'BEJUCAL', NULL, 3, '2401', 1);
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (73, 71, 4, 5, 'SAN JOSE DE LAS LAJAS', NULL, 3, '2402', 1);
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (74, 71, 6, 7, 'JARUCO', NULL, 3, '2403', 1);
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (75, 71, 8, 9, 'SANTA CRUZ DEL NORTE', NULL, 3, '2404', 1);
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (76, 71, 10, 11, 'MADRUGA', NULL, 3, '2405', 1);
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (77, 71, 12, 13, 'NUEVA PAZ', NULL, 3, '2406', 1);
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (78, 71, 14, 15, 'SAN NICOLAS', NULL, 3, '2407', 1);
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (79, 71, 16, 17, 'GÜINES', NULL, 3, '2408', 1);
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (80, 71, 18, 19, 'MELENA DEL SUR', NULL, 3, '2409', 1);
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (81, 71, 20, 21, 'BATABANO', NULL, 3, '22810', 1);
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (82, 71, 22, 23, 'QUIVICAN', NULL, 3, '2411', 1);
----------------------
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (91, 91, 1, 28, 'MATANZAS', 'MTZ', 2, '25', 1);	
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (92, 91, 2, 3, 'MATANZAS', NULL, 3, '2501', 1);
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (93, 91, 4, 5, 'CARDENAS', NULL, 3, '2502', 1);
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (94, 91, 6, 7, 'MARTI', NULL, 3, '2503', 1);
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (95, 91, 8, 9, 'COLON', NULL, 3, '2504', 1);
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (96, 91, 10, 11, 'PERICO', NULL, 3, '2505', 1);
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (97, 91, 12, 13, 'JOVELLANOS', NULL, 3, '2506', 1);
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (98, 91, 14, 15, 'PEDRO BETANCOURT', NULL, 3, '2507', 1);
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (99, 91, 16, 17, 'LIMONAR', NULL, 3, '2508', 1);
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (100, 91, 18, 19, 'UNION DE REYES', NULL, 3, '2509', 1);
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (101, 91, 20, 21, 'CIENAGA DE ZAPATA', NULL, 3, '2510', 1);
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (102, 91, 22, 23, 'JAGÜEY GRANDE', NULL, 3, '2511', 1);
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (103, 91, 24, 25, 'CALIMETE', NULL, 3, '2512', 1);
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (104, 91, 26, 27, 'LOS ARABOS', NULL, 3, '2513', 1);
----------------------
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (111, 111, 1, 28, 'VILLA CLARA', 'VCL', 2, '26', 1);	
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (112, 111, 2, 3, 'CORRALILLO', NULL, 3, '2601', 1);
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (113, 111, 4, 5, 'QUEMADO DE GÜINES', NULL, 3, '2602', 1);
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (114, 111, 6, 7, 'SAGUA LA GRANDE', NULL, 3, '2603', 1);
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (115, 111, 8, 9, 'ENCRICIJADA', NULL, 3, '2604', 1);
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (116, 111, 10, 11, 'CAMAJUANI', NULL, 3, '2605', 1);
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (117, 111, 12, 13, 'CAIBARIEN', NULL, 3, '2606', 1);
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (118, 111, 14, 15, 'REMEDIOS', NULL, 3, '2607', 1);
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (119, 111, 16, 17, 'PLACETAS', NULL, 3, '2608', 1);
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (120, 111, 18, 19, 'SANTA CLARA', NULL, 3, '2609', 1);
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (121, 111, 20, 21, 'CIFUENTES', NULL, 3, '2610', 1);
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (122, 111, 22, 23, 'SANTO DOMINGO', NULL, 3, '2611', 1);
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (123, 111, 24, 25, 'RANCHUELO', NULL, 3, '2612', 1);
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (124, 111, 26, 27, 'MANICARAGUA', NULL, 3, '2613', 1);
----------------------
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (131, 131, 1, 18, 'CIENFUEGOS', 'CFG', 2, '27', 1);	
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (132, 131, 2, 3, 'AGUADA DE PASAJEROS', NULL, 3, '2701', 1);
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (133, 131, 4, 5, 'RODAS', NULL, 3, '2702', 1);
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (134, 131, 6, 7, 'PALMIRA', NULL, 3, '2703', 1);
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (135, 131, 8, 9, 'LAJAS', NULL, 3, '2704', 1);
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (136, 131, 10, 11, 'CRUCES', NULL, 3, '2705', 1);
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (137, 131, 12, 13, 'CUMANAYAGUA', NULL, 3, '2706', 1);
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (138, 131, 14, 15, 'CIENFUEGOS', NULL, 3, '2707', 1);
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (139, 131, 16, 17, 'ABREUS', NULL, 3, '2708', 1);
-------------
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (151, 151, 1, 18, 'SANTI SPIRITUS', 'SSP', 2, '28', 1);	
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (152, 151, 2, 3, 'YAGUAJAY', NULL, 3, '2801', 1);
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (153, 151, 4, 5, 'JATIBONICO', NULL, 3, '2802', 1);
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (154, 151, 6, 7, 'TAGUASCO', NULL, 3, '2803', 1);
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (155, 151, 8, 9, 'CABAIGUAN', NULL, 3, '2804', 1);
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (156, 151, 10, 11, 'FOMENTO', NULL, 3, '2805', 1);
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (157, 151, 12, 13, 'TRINIDAD', NULL, 3, '2806', 1);
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (158, 151, 14, 15, 'SANTI SPIRITUS', NULL, 3, '2807', 1);
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (159, 151, 16, 17, 'LA SIERPE', NULL, 3, '2808', 1);
-----------------
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (171, 171, 1, 22, 'CIEGO DE AVILA', 'CAV', 2, '29', 1);	
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (172, 171, 2, 3, 'CHAMBAS', NULL, 3, '2901', 1);
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (173, 171, 4, 5, 'MORON', NULL, 3, '2902', 1);
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (174, 171, 6, 7, 'BOLIVIA', NULL, 3, '2903', 1);
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (175, 171, 8, 9, 'PRIMERO DE ENERO', NULL, 3, '2904', 1);
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (176, 171, 10, 11, 'CIRO REDONDO', NULL, 3, '2905', 1);
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (177, 171, 12, 13, 'FLORENCIA', NULL, 3, '2906', 1);
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (178, 171, 14, 15, 'MAJAGUA', NULL, 3, '2907', 1);
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (179, 171, 16, 17, 'CIEGO DE AVILA', NULL, 3, '2908', 1);
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (180, 171, 18, 19, 'VENEZUELA', NULL, 3, '2909', 1);
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (181, 171, 20, 21, 'BARAGUA', NULL, 3, '2910', 1);
-----------------
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (191, 191, 1, 28, 'CAMAGÜEY', 'CMG', 2, '30', 1);	
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (192, 191, 2, 3, 'CARLOS MANUEL DE CESPEDES', NULL, 3, '3001', 1);
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (193, 191, 4, 5, 'ESMERALDA', NULL, 3, '3002', 1);
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (194, 191, 6, 7, 'SIERRA DE CUBITAS', NULL, 3, '3003', 1);
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (195, 191, 8, 9, 'MINAS', NULL, 3, '3004', 1);
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (196, 191, 10, 11, 'NUEVITAS', NULL, 3, '3005', 1);
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (197, 191, 12, 13, 'GUAIMARO', NULL, 3, '3006', 1);
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (198, 191, 14, 15, 'SIBANICU', NULL, 3, '3007', 1);
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (199, 191, 16, 17, 'CAMAGÜEY', NULL, 3, '3008', 1);
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (200, 191, 18, 19, 'FLORIDA', NULL, 3, '3009', 1);
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (201, 191, 20, 21, 'VERTIENTES', NULL, 3, '3011', 1);
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (202, 191, 22, 23, 'JIMAGUAYU', NULL, 3, '3010', 1);
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (203, 191, 24, 25, 'NAJASA', NULL, 3, '3012', 1);
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (204, 191, 26, 27, 'SANTA CRUZ DEL SUR', NULL, 3, '3013', 1);
-----------------
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (211, 211, 1, 18, 'LAS TUNAS', 'LTU', 2, '31', 1);	
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (212, 211, 2, 3, 'MANATI', NULL, 3, '3101', 1);
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (213, 211, 4, 5, 'PUERTO PADRE', NULL, 3, '3102', 1);
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (214, 211, 6, 7, 'JESUS MENENDEZ', NULL, 3, '3103', 1);
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (215, 211, 8, 9, 'MAJIBACOA', NULL, 3, '3104', 1);
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (216, 211, 10, 11, 'LAS TUNAS', NULL, 3, '3105', 1);
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (217, 211, 12, 13, 'JOBABO', NULL, 3, '3106', 1);
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (218, 211, 14, 15, 'COLOMBIA', NULL, 3, '3107', 1);
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (219, 211, 16, 17, 'AMANCIO', NULL, 3, '3108', 1);
-----------------
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (231, 231, 1, 30, 'HOLGIN', 'HOL', 2, '32', 1);	
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (232, 231, 2, 3, 'GIBARA', NULL, 3, '3201', 1);
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (233, 231, 4, 5, 'RAFEL FREYRE', NULL, 3, '3202', 1);
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (234, 231, 6, 7, 'BANES', NULL, 3, '3203', 1);
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (235, 231, 8, 9, 'ANTILLA', NULL, 3, '3204', 1);
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (236, 231, 10, 11, 'BAGUANOS', NULL, 3, '3205', 1);
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (237, 231, 12, 13, 'HOLGUIN', NULL, 3, '3206', 1);
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (238, 231, 14, 15, 'CALIXTO GARCIA', NULL, 3, '3207', 1);
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (239, 231, 16, 17, 'CACOCUN', NULL, 3, '3208', 1);
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (240, 231, 18, 19, 'URBANO NORIS', NULL, 3, '3209', 1);
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (241, 231, 20, 21, 'CUETO', NULL, 3, '3210', 1);
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (242, 231, 22, 23, 'MAYARI', NULL, 3, '3211', 1);
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (243, 231, 24, 25, 'FRANK PAIS', NULL, 3, '3212', 1);
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (244, 231, 26, 27, 'SAGUA DE TANAMO', NULL, 3, '3213', 1);
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (245, 231, 28, 29, 'MOA', NULL, 3, '3214', 1);
-----------------
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (251, 251, 1, 28, 'GRANMA', 'GRA', 2, '33', 1);	
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (252, 251, 2, 3, 'RIO CAUTO', NULL, 3, '3301', 1);
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (253, 251, 4, 5, 'CAUTO CRISTO', NULL, 3, '3302', 1);
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (254, 251, 6, 7, 'JIGUANI', NULL, 3, '3303', 1);
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (255, 251, 8, 9, 'BAYAMO', NULL, 3, '3304', 1);
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (256, 251, 10, 11, 'YARA', NULL, 3, '3305', 1);
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (257, 251, 12, 13, 'MANZANILLO', NULL, 3, '3306', 1);
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (258, 251, 14, 15, 'CAMPECHUELA', NULL, 3, '3307', 1);
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (259, 251, 16, 17, 'MEDIA LUNA', NULL, 3, '3308', 1);
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (260, 251, 18, 19, 'NIQUERO', NULL, 3, '3309', 1);
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (261, 251, 20, 21, 'PILON', NULL, 3, '3310', 1);
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (262, 251, 22, 23, 'BARTOLOME MASO', NULL, 3, '3311', 1);
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (263, 251, 24, 25, 'BUEY ARRIBA', NULL, 3, '3312', 1);
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (264, 251, 26, 27, 'GUISA', NULL, 3, '3313', 1);
-----------------
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (271, 271, 1, 20, 'SANTIAGO DE CUBA', 'SCU', 2, '34', 1);	
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (272, 271, 2, 3, 'CONTRAMAESTRE', NULL, 3, '3401', 1);
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (273, 271, 4, 5, 'MELLA', NULL, 3, '3402', 1);
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (274, 271, 6, 7, 'SAN LUIS', NULL, 3, '3403', 1);
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (275, 271, 8, 9, 'SEGUNDO FRENTE', NULL, 3, '3404', 1);
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (276, 271, 10, 11, 'SONGO - LA MAYA', NULL, 3, '3405', 1);
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (277, 271, 12, 13, 'SANTIAGO DE CUBA', NULL, 3, '3406', 1);
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (278, 271, 14, 15, 'PALMA SORIANO', NULL, 3, '3407', 1);
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (279, 271, 16, 17, 'TERCER FRENTE', NULL, 3, '3408', 1);
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (280, 271, 18, 19, 'GUAMA', NULL, 3, '3409', 1);
-----------------
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (291, 291, 1, 22, 'GUANTANAMO', 'GTM', 2, '35', 1);	
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (292, 291, 2, 3, 'EL SALVADOR', NULL, 3, '3501', 1);
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (293, 291, 4, 5, 'MANUEL TAMES', NULL, 3, '3508', 1);
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (294, 291, 6, 7, 'YATERAS', NULL, 3, '3503', 1);
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (295, 291, 8, 9, 'BARACOA', NULL, 3, '3504', 1);
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (296, 291, 10, 11, 'MAISI', NULL, 3, '3505', 1);
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (297, 291, 12, 13, 'IMIAS', NULL, 3, '3506', 1);
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (298, 291, 14, 15, 'SAN ANTONIO DEL SUR', NULL, 3, '3507', 1);
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (299, 291, 16, 17, 'CAIMANERA', NULL, 3, '3509', 1);
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (300, 291, 18, 19, 'GUANTANAMO', NULL, 3, '3502', 1);
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (301, 291, 20, 21, 'NICETO PEREZ', NULL, 3, '3510', 1);
-----------------
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (311, 311, 1, 4, 'ISLA DE LA JUVENTUD', 'IJV', 2, '40', 1);	
INSERT INTO nom_dpa (iddpa, idpadre, ordenizq, ordender, denominacion, abreviatura, idtipodpa, codigo, idpais) VALUES (312, 311, 2, 3, 'ISLA DE LA JUVENTUD', 'IJV', 3, '4001', 1);	
-----------------
----------------------------------------
--fin de la transaccion
COMMIT;
