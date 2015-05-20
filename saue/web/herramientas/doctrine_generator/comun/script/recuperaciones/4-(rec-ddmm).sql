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
VALUES ('V5.0.0', 'V5.0.0','mod_recuperaciones','mod_datosmaestros','C','P','I');
----------------------------------------------------------------------------
--Permisos del rol reporte sobre el esquema mod_datosmaestros
----------------------------------------------------------------------------
----------------------------------------
--Permisos sobre el esquema
----------------------------------------
	SET search_path = mod_datosmaestros, pg_catalog;

GRANT USAGE
  ON SCHEMA "mod_datosmaestros" TO reportesigis;

----------------------------------------
-- Permisos sobre secuencias
----------------------------------------
	SET search_path = mod_datosmaestros, pg_catalog;


GRANT SELECT, USAGE ON TABLE mod_datosmaestros.sec_idconfiguracion_seq TO reportesigis;
GRANT SELECT, USAGE ON TABLE mod_datosmaestros.sec_version_seq TO reportesigis;
----------------------------------------
--Permisos sobre tablas
----------------------------------------
	SET search_path = mod_datosmaestros, pg_catalog;



GRANT SELECT ON TABLE mod_datosmaestros.conf_entidades TO reportesigis;
GRANT SELECT ON TABLE mod_datosmaestros.conf_version TO reportesigis;
GRANT SELECT ON TABLE mod_datosmaestros.nom_secuenciasgeneral TO reportesigis;
GRANT SELECT ON TABLE mod_datosmaestros.nom_servidor TO reportesigis;


----------------------------------------
--Permisos sobre funciones
----------------------------------------
	SET search_path = mod_datosmaestros, pg_catalog;


GRANT EXECUTE ON FUNCTION mod_datosmaestros.ft_actualizarantidades() TO reportesigis;
GRANT EXECUTE ON FUNCTION mod_datosmaestros.ft_creacionsecuenciasact() TO reportesigis;

----------------------------------------
--fin de la transaccion
COMMIT;