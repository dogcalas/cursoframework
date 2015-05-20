--------------------V5.0.0---------------------
/*
--CENTRO DE SOLUCIONES DE GESTIÓN		----
--Subdirección de tecnología			----
--										----
--SCRIPT de INSTALACION de PERMISOS 	----
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

--comienza la transaccion--
begin;
-- Propiedades de la BD       
	SET client_encoding = 'UTF8';
	SET standard_conforming_strings = off;
	SET check_function_bodies = false;
	SET client_min_messages = warning;
	SET escape_string_warning = off;

INSERT INTO mod_datosmaestros.conf_version ( versionscript,  versionscriptinicial,  esquema,  esquemarelacion,  tiposcript,  classcript,  script) 
VALUES ('V5.0.0', 'V5.0.0','mod_recuperaciones','mod_traza','C','P','I');
----------------------------------------------------------------------------
--Permisos del rol reporte sobre el esquema mod_traza
----------------------------------------------------------------------------
----------------------------------------
--Permisos sobre el esquema
----------------------------------------
	SET search_path = mod_traza, pg_catalog;

GRANT USAGE ON SCHEMA mod_traza TO sauxe; 
----------------------------------------
-- Permisos sobre secuencias
----------------------------------------
	SET search_path = mod_traza, pg_catalog;

GRANT SELECT, USAGE
  ON "mod_traza"."his_traza_idtraza_seq" TO sauxe;
GRANT SELECT, USAGE
  ON "mod_traza"."sec_tipotraza_seq" TO sauxe;
----------------------------------------
--Permisos sobre tablas
----------------------------------------
	SET search_path = mod_traza, pg_catalog;

GRANT SELECT ON TABLE mod_traza.his_accion TO sauxe;
GRANT SELECT ON TABLE mod_traza.his_dato TO sauxe;
GRANT SELECT ON TABLE mod_traza.his_excepcion TO sauxe;
GRANT SELECT ON TABLE mod_traza.his_ioc TO sauxe;
GRANT SELECT ON TABLE mod_traza.his_iocexception TO sauxe;
GRANT SELECT ON TABLE mod_traza.his_rendimiento TO sauxe;
GRANT SELECT ON TABLE mod_traza.his_traza TO sauxe;
GRANT SELECT ON TABLE mod_traza.nom_tipotraza TO sauxe;

----------------------------------------
--Permisos sobre funciones
----------------------------------------
	SET search_path = mod_traza, pg_catalog;



----------------------------------------
--fin de la transaccion
COMMIT;
