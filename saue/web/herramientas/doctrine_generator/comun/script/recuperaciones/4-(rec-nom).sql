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
VALUES ('V5.0.0', 'V5.0.0','mod_recuperaciones','mod_nomencladores','C','P','I');
----------------------------------------------------------------------------
--Permisos del rol reporte sobre el esquema mod_nomencladores
----------------------------------------------------------------------------
----------------------------------------
--Permisos sobre el esquema
----------------------------------------
	SET search_path = mod_nomencladores, pg_catalog;

GRANT USAGE ON SCHEMA "mod_nomencladores" TO reportesigis;

----------------------------------------
-- Permisos sobre secuencias
----------------------------------------
	SET search_path = mod_nomencladores, pg_catalog;

GRANT SELECT, USAGE ON TABLE mod_nomencladores.sec_iddpa TO reportesigis;
GRANT SELECT, USAGE ON TABLE mod_nomencladores.sec_idespecialidad TO reportesigis;
GRANT SELECT, USAGE ON TABLE mod_nomencladores.sec_idpais TO reportesigis;
GRANT SELECT, USAGE ON TABLE mod_nomencladores.sec_idtipodpa TO reportesigis;
----------------------------------------
--Permisos sobre tablas
----------------------------------------
	SET search_path = mod_nomencladores, pg_catalog;

GRANT SELECT ON TABLE mod_nomencladores.nom_dpa TO reportesigis;
GRANT SELECT ON TABLE mod_nomencladores.nom_especialidad TO reportesigis;
GRANT SELECT ON TABLE mod_nomencladores.nom_pais TO reportesigis;
GRANT SELECT ON TABLE mod_nomencladores.nom_tipodpa TO reportesigis;


----------------------------------------
--Permisos sobre funciones
----------------------------------------
	SET search_path = mod_nomencladores, pg_catalog;


GRANT EXECUTE ON FUNCTION mod_nomencladores.f_reordenar_nom_dpa(numeric, numeric) TO reportesigis;
GRANT EXECUTE ON FUNCTION mod_nomencladores.f_reordenar_nom_especialidad(numeric, numeric) TO reportesigis;

GRANT EXECUTE ON FUNCTION mod_nomencladores.ft_actualizacion_arbol_dpa() TO reportesigis;
GRANT EXECUTE ON FUNCTION mod_nomencladores.ft_actualizacion_arbol_especialidad() TO reportesigis;
GRANT EXECUTE ON FUNCTION mod_nomencladores.ft_eliminar_nodo_dpa() TO reportesigis;
GRANT EXECUTE ON FUNCTION mod_nomencladores.ft_eliminar_nodo_especialidad() TO reportesigis;
GRANT EXECUTE ON FUNCTION mod_nomencladores.ft_insertar_nodo_dpa() TO reportesigis;
GRANT EXECUTE ON FUNCTION mod_nomencladores.ft_insertar_nodo_especialidad() TO reportesigis;
GRANT EXECUTE ON FUNCTION mod_nomencladores.ft_modificar_nodo_dpa() TO reportesigis;
GRANT EXECUTE ON FUNCTION mod_nomencladores.ft_modificar_nodo_especialidad() TO reportesigis;
----------------------------------------
--fin de la transaccion
COMMIT;