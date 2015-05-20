--------V6.0.0---------------------
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
BEGIN;
-- Propiedades de la BD       
	SET client_encoding = 'UTF8';
	SET standard_conforming_strings = off;
	SET check_function_bodies = false;
	SET client_min_messages = warning;
	SET escape_string_warning = off;

INSERT INTO mod_datosmaestros.conf_version ( versionscript,  versionscriptinicial,  esquema,  esquemarelacion,  tiposcript,  classcript,  script) 
VALUES ('V6.0.0', 'V6.0.0','mod_traza',null,'C','P','I');
----------------------------------------------------------------------------
--Permisos del rol arquitectura sobre el esquema mod_traza
ALTER SCHEMA mod_traza OWNER TO sauxe;
----------------------------------------------------------------------------
----------------------------------------
--Permisos sobre el esquema
----------------------------------------
	SET search_path = mod_traza, pg_catalog;

GRANT USAGE ON SCHEMA mod_traza TO sauxe;

--- Secuencias

GRANT ALL ON TABLE mod_traza.his_traza_idtraza_seq TO sauxe;
GRANT ALL ON TABLE mod_traza.sec_nomoperacion_seq TO sauxe;
GRANT ALL ON TABLE mod_traza.sec_tipotraza_seq TO sauxe;

--- Tablas

GRANT ALL ON TABLE mod_traza.his_accion TO sauxe;
GRANT ALL ON TABLE mod_traza.his_dato TO sauxe;
GRANT ALL ON TABLE mod_traza.his_excepcion TO sauxe;
GRANT ALL ON TABLE mod_traza.his_ioc TO sauxe;
GRANT ALL ON TABLE mod_traza.his_iocexception TO sauxe;
GRANT ALL ON TABLE mod_traza.his_rendimiento TO sauxe;
GRANT ALL ON TABLE mod_traza.his_traza TO sauxe;
GRANT ALL ON TABLE mod_traza.nom_operacion TO sauxe;
GRANT ALL ON TABLE mod_traza.nom_tipotraza TO sauxe;

--- Dueño tablas y secuencias

ALTER TABLE mod_traza.his_accion OWNER TO sauxe;
ALTER TABLE mod_traza.his_dato OWNER TO sauxe;
ALTER TABLE mod_traza.his_excepcion OWNER TO sauxe;
ALTER TABLE mod_traza.his_ioc OWNER TO sauxe;
ALTER TABLE mod_traza.his_iocexception OWNER TO sauxe;
ALTER TABLE mod_traza.his_rendimiento OWNER TO sauxe;
ALTER TABLE mod_traza.his_traza_idtraza_seq OWNER TO sauxe;
ALTER TABLE mod_traza.his_traza OWNER TO sauxe;
ALTER TABLE mod_traza.sec_nomoperacion_seq OWNER TO sauxe;
ALTER TABLE mod_traza.nom_operacion OWNER TO sauxe;
ALTER TABLE mod_traza.sec_tipotraza_seq OWNER TO sauxe;
ALTER TABLE mod_traza.nom_tipotraza OWNER TO sauxe;

COMMIT;
