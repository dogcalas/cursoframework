-------------------V6.0.0---------------------
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
VALUES ('V6.0.0', 'V6.0.0','mod_datosmaestros',null,'C','P','I');
----------------------------------------------------------------------------
--Permisos del rol configuracion sobre el esquema mod_datosmaestros
----------------------------------------------------------------------------

----------------------------------------
--Permisos sobre el esquema
----------------------------------------
	SET search_path = mod_datosmaestros, pg_catalog;

GRANT USAGE ON SCHEMA mod_datosmaestros TO sauxe;
----------------------------------------
-- Permisos sobre secuencias
----------------------------------------
	SET search_path = mod_datosmaestros, pg_catalog;

GRANT ALL ON TABLE mod_datosmaestros.sec_version_seq TO sauxe;

----------------------------------------
--Permisos sobre tablas
----------------------------------------
	SET search_path = mod_datosmaestros, pg_catalog;

GRANT ALL ON TABLE mod_datosmaestros.conf_version TO sauxe;
GRANT ALL ON TABLE mod_datosmaestros.nom_secuenciasgeneral TO sauxe;
GRANT ALL ON TABLE mod_datosmaestros.nom_servidor TO sauxe;
GRANT ALL ON TABLE mod_datosmaestros.conf_entidades TO sauxe;

---------------------------------------rene-
--Permisos sobre funciones
----------------------------------------
	SET search_path = mod_datosmaestros, pg_catalog;

GRANT EXECUTE ON FUNCTION mod_datosmaestros.ft_creacionsecuenciasact() TO sauxe;
GRANT EXECUTE ON FUNCTION mod_datosmaestros.ft_actualizarantidades() TO sauxe;

----------------------------------------------------------------------------
--Permisos del rol configuracion sobre el esquema mod_datosmaestros
----------------------------------------------------------------------------
----------------------------------------
--Permisos sobre el esquema
----------------------------------------
	SET search_path = mod_datosmaestros, pg_catalog;

ALTER SCHEMA mod_datosmaestros OWNER TO sauxe;
----------------------------------------
ALTER TABLE mod_datosmaestros.sec_version_seq OWNER TO sauxe;
ALTER TABLE mod_datosmaestros.sec_idconfiguracion_seq OWNER TO sauxe;
----------------------------------------

ALTER TABLE mod_datosmaestros.conf_entidades
  OWNER TO sauxe;

ALTER TABLE mod_datosmaestros.conf_version
  OWNER TO sauxe;

ALTER TABLE mod_datosmaestros.nom_secuenciasgeneral
  OWNER TO sauxe;
  
ALTER TABLE mod_datosmaestros.nom_servidor
  OWNER TO sauxe;
----------------------------------------

ALTER FUNCTION mod_datosmaestros.ft_creacionsecuenciasact()
  OWNER TO sauxe;
  ALTER FUNCTION mod_datosmaestros.ft_actualizarantidades()
  OWNER TO sauxe;

----------------------------------------
--fin de la transaccion
COMMIT;
