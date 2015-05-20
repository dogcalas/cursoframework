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
VALUES ('V5.0.0', 'V5.0.0','mod_traza',null,'C','P','I');
----------------------------------------------------------------------------
--Permisos del rol trazasigis sobre el esquema mod_traza
----------------------------------------------------------------------------
----------------------------------------
--Permisos sobre el esquema
----------------------------------------
	SET search_path = mod_traza, pg_catalog;

GRANT USAGE ON SCHEMA mod_traza TO trazasigis;
----------------------------------------
-- Permisos sobre secuencias
----------------------------------------
	SET search_path = mod_traza, pg_catalog;

GRANT SELECT, USAGE ON mod_traza.his_traza_idtraza_seq TO trazasigis;
GRANT SELECT, USAGE ON mod_traza.sec_categoriatraza_seq TO trazasigis;
GRANT SELECT, USAGE ON mod_traza.sec_tipotraza_seq TO trazasigis;

----------------------------------------
--Permisos sobre tablas
----------------------------------------
	SET search_path = mod_traza, pg_catalog;

GRANT SELECT, UPDATE, INSERT, DELETE ON TABLE mod_traza.his_accion TO trazasigis;
GRANT SELECT, UPDATE, INSERT, DELETE ON TABLE mod_traza.his_autentication TO trazasigis;
GRANT SELECT, UPDATE, INSERT, DELETE ON TABLE mod_traza.his_cierresesion TO trazasigis;
GRANT SELECT, UPDATE, INSERT, DELETE ON TABLE mod_traza.his_dato TO trazasigis;
GRANT SELECT, UPDATE, INSERT, DELETE ON TABLE mod_traza.his_excepcion TO trazasigis;
GRANT SELECT, UPDATE, INSERT, DELETE ON TABLE mod_traza.his_ioc TO trazasigis;
GRANT SELECT, UPDATE, INSERT, DELETE ON TABLE mod_traza.his_iocexception TO trazasigis;
GRANT SELECT, UPDATE, INSERT, DELETE ON TABLE mod_traza.his_rendimiento TO trazasigis;
GRANT SELECT, UPDATE, INSERT, DELETE ON TABLE mod_traza.his_traza TO trazasigis;
GRANT SELECT, UPDATE, INSERT, DELETE ON TABLE mod_traza.his_url TO trazasigis;
GRANT SELECT, UPDATE, INSERT, DELETE ON TABLE mod_traza.nom_categoriatraza TO trazasigis;
GRANT SELECT, UPDATE, INSERT, DELETE ON TABLE mod_traza.nom_tipotraza TO trazasigis;

----------------------------------------
--Permisos sobre funciones
----------------------------------------
	SET search_path = mod_traza, pg_catalog;

----------------------------------------------------------------------------
--Permisos del rol erp sobre el esquema mod_traza
----------------------------------------------------------------------------
----------------------------------------
--Permisos sobre el esquema
----------------------------------------
	SET search_path = mod_traza, pg_catalog;

ALTER SCHEMA "mod_traza" OWNER TO trazasigis;
----------------------------------------
-- Permisos sobre secuencias
----------------------------------------
	SET search_path = mod_traza, pg_catalog;

ALTER TABLE mod_traza.his_traza_idtraza_seq OWNER TO trazasigis;
ALTER TABLE mod_traza.sec_categoriatraza_seq OWNER TO trazasigis;
ALTER TABLE mod_traza.sec_tipotraza_seq OWNER TO trazasigis;

----------------------------------------
-- Permisos sobre tablas
----------------------------------------
	SET search_path = mod_traza, pg_catalog;
	

ALTER TABLE "mod_traza"."his_accion"
  OWNER TO trazasigis;
ALTER TABLE "mod_traza"."his_autentication"
  OWNER TO trazasigis;
ALTER TABLE "mod_traza"."his_cierresesion"
  OWNER TO trazasigis;
ALTER TABLE "mod_traza"."his_dato"
  OWNER TO trazasigis;
ALTER TABLE "mod_traza"."his_excepcion"
  OWNER TO trazasigis;
ALTER TABLE "mod_traza"."his_ioc"
  OWNER TO trazasigis;
ALTER TABLE "mod_traza"."his_iocexception"
  OWNER TO trazasigis;
ALTER TABLE "mod_traza"."his_rendimiento"
  OWNER TO trazasigis;
ALTER TABLE "mod_traza"."his_traza"
  OWNER TO trazasigis;
ALTER TABLE "mod_traza"."his_url"
  OWNER TO trazasigis;
ALTER TABLE "mod_traza"."nom_categoriatraza"
  OWNER TO trazasigis;
ALTER TABLE "mod_traza"."nom_tipotraza"
  OWNER TO trazasigis;	
----------------------------------------
-- Permisos sobre funciones
----------------------------------------
	SET search_path = mod_traza, pg_catalog;

----------------------------------------
--fin de la transaccion
COMMIT;