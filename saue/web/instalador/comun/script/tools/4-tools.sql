--------V7.0.0---------------------
/*
--CENTRO DE SOLUCIONES DE GESTIÓN		----
--Departamento de tecnología			----
--2014										----
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
BEGIN;
-- Propiedades de la BD       
	SET client_encoding = 'UTF8';
	SET standard_conforming_strings = off;
	SET check_function_bodies = false;
	SET client_min_messages = warning;
	SET escape_string_warning = off;

INSERT INTO mod_datosmaestros.conf_version ( versionscript,  versionscriptinicial,  esquema,  esquemarelacion,  tiposcript,  classcript,  script) 
VALUES ('V7.0.0', 'V7.0.0','mod_generacionscript',null,'C','P','I');
----------------------------------------------------------------------------
--Permisos del rol arquitectura sobre el esquema mod_generacionscript
ALTER SCHEMA mod_generacionscript OWNER TO sauxe;
----------------------------------------------------------------------------
----------------------------------------
--Permisos sobre el esquema
----------------------------------------
	SET search_path = mod_generacionscript, pg_catalog;

GRANT USAGE ON SCHEMA mod_generacionscript TO sauxe;

--- Secuencias

GRANT ALL ON TABLE mod_generacionscript.dat_script_id_script_seq TO sauxe;
GRANT ALL ON TABLE mod_generacionscript.nom_tiposcript_id_tiposcript_seq TO sauxe;

--- Tablas

GRANT ALL ON TABLE mod_generacionscript.dat_script TO sauxe;
GRANT ALL ON TABLE mod_generacionscript.nom_tiposscript TO sauxe;

--- Dueño tablas y secuencias

ALTER TABLE mod_generacionscript.nom_tiposscript OWNER TO sauxe;
ALTER TABLE mod_generacionscript.dat_script OWNER TO sauxe;
ALTER TABLE mod_generacionscript.nom_tiposcript_id_tiposcript_seq OWNER TO sauxe;
ALTER TABLE mod_generacionscript.dat_script_id_script_seq OWNER TO sauxe;

COMMIT;
