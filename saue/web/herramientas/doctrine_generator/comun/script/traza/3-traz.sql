----------------V5.0.0---------------------
/*
--CENTRO DE SOLUCIONES DE GESTIÓN		----
--Subdirección de tecnología			----
--										----
--SCRIPT de INSTALACION de DATOS INICIALES   	----
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
VALUES ('V5.0.0', 'V5.0.0','mod_traza',null,'I','D','I');
---------------------------------------------------------------------------------------------------------------------------
--Datos del esquema mod_traza
---------------------------------------------------------------------------------------------------------------------------	
	SET search_path = mod_traza, pg_catalog;


----------------------------------------
INSERT INTO nom_categoriatraza (idcategoriatraza, denominacion) VALUES (0, 'Todas');
INSERT INTO nom_categoriatraza (idcategoriatraza, denominacion) VALUES (1, 'Estructura y Composición');
INSERT INTO nom_categoriatraza (idcategoriatraza, denominacion) VALUES (2, 'Configuración');
INSERT INTO nom_categoriatraza (idcategoriatraza, denominacion) VALUES (3, 'Multimoneda');
INSERT INTO nom_categoriatraza (idcategoriatraza, denominacion) VALUES (4, 'Contabilidad');
INSERT INTO nom_categoriatraza (idcategoriatraza, denominacion) VALUES (5, 'Costo y Procesos');
INSERT INTO nom_categoriatraza (idcategoriatraza, denominacion) VALUES (6, 'Cobros y Pagos');
INSERT INTO nom_categoriatraza (idcategoriatraza, denominacion) VALUES (7, 'Capital Humano');
INSERT INTO nom_categoriatraza (idcategoriatraza, denominacion) VALUES (10, 'Inventario');
INSERT INTO nom_categoriatraza (idcategoriatraza, denominacion) VALUES (13, 'Facturación');
INSERT INTO nom_categoriatraza (idcategoriatraza, denominacion) VALUES (12, 'Banco');
INSERT INTO nom_categoriatraza (idcategoriatraza, denominacion) VALUES (14, 'Caja');
INSERT INTO nom_categoriatraza (idcategoriatraza, denominacion) VALUES (96, 'Portal');
INSERT INTO nom_categoriatraza (idcategoriatraza, denominacion) VALUES (97, 'Seguridad');
INSERT INTO nom_categoriatraza (idcategoriatraza, denominacion) VALUES (98, 'Traza');
INSERT INTO nom_categoriatraza (idcategoriatraza, denominacion) VALUES (99, 'Arquitectura');


----------------------------------------
INSERT INTO nom_tipotraza (idtipotraza, tipotraza) VALUES (1, 'Acción');
INSERT INTO nom_tipotraza (idtipotraza, tipotraza) VALUES (3, 'Excepción');
INSERT INTO nom_tipotraza (idtipotraza, tipotraza) VALUES (7, 'Rendimiento');
INSERT INTO nom_tipotraza (idtipotraza, tipotraza) VALUES (6, 'Integración');
INSERT INTO nom_tipotraza (idtipotraza, tipotraza) VALUES (8, 'Excepción de Integración');
INSERT INTO nom_tipotraza (idtipotraza, tipotraza) VALUES (9, 'Autenticación');
INSERT INTO nom_tipotraza (idtipotraza, tipotraza) VALUES (10, 'Cierre sesión');
----------------------------------------
--fin de la transaccion
COMMIT;