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
VALUES ('V5.0.0', 'V5.0.0','mod_nomencladores',null,'C','P','I');
----------------------------------------------------------------------------
--Permisos del rol nomenclador sobre el esquema mod_nomencladores
----------------------------------------------------------------------------
----------------------------------------
--Permisos sobre el esquema
----------------------------------------
	SET search_path = mod_nomencladores, pg_catalog;

GRANT USAGE ON SCHEMA mod_nomencladores TO nomencladorsigis;
----------------------------------------
-- Permisos sobre secuencias
----------------------------------------
	SET search_path = mod_nomencladores, pg_catalog;



GRANT ALL ON TABLE mod_nomencladores.sec_iddpa TO nomencladorsigis;
GRANT ALL ON TABLE mod_nomencladores.sec_idespecialidad TO nomencladorsigis;
GRANT ALL ON TABLE mod_nomencladores.sec_idpais TO nomencladorsigis;
GRANT ALL ON TABLE mod_nomencladores.sec_idtipodpa TO nomencladorsigis;

----------------------------------------
--Permisos sobre tablas
----------------------------------------
	SET search_path = mod_nomencladores, pg_catalog;

GRANT ALL ON TABLE mod_nomencladores.nom_dpa TO nomencladorsigis;
GRANT ALL ON TABLE mod_nomencladores.nom_especialidad TO nomencladorsigis;
GRANT ALL ON TABLE mod_nomencladores.nom_pais TO nomencladorsigis;
GRANT ALL ON TABLE mod_nomencladores.nom_tipodpa TO nomencladorsigis;

----------------------------------------
--Permisos sobre funciones
----------------------------------------
	SET search_path = mod_nomencladores, pg_catalog;

GRANT EXECUTE ON FUNCTION mod_nomencladores.f_reordenar_nom_dpa(numeric, numeric) TO nomencladorsigis;
GRANT EXECUTE ON FUNCTION mod_nomencladores.f_reordenar_nom_especialidad(numeric, numeric) TO nomencladorsigis;

GRANT EXECUTE ON FUNCTION mod_nomencladores.ft_actualizacion_arbol_dpa() TO nomencladorsigis;
GRANT EXECUTE ON FUNCTION mod_nomencladores.ft_actualizacion_arbol_especialidad() TO nomencladorsigis;
GRANT EXECUTE ON FUNCTION mod_nomencladores.ft_eliminar_nodo_dpa() TO nomencladorsigis;
GRANT EXECUTE ON FUNCTION mod_nomencladores.ft_eliminar_nodo_especialidad() TO nomencladorsigis;
GRANT EXECUTE ON FUNCTION mod_nomencladores.ft_insertar_nodo_dpa() TO nomencladorsigis;
GRANT EXECUTE ON FUNCTION mod_nomencladores.ft_insertar_nodo_especialidad() TO nomencladorsigis;
GRANT EXECUTE ON FUNCTION mod_nomencladores.ft_modificar_nodo_dpa() TO nomencladorsigis;
GRANT EXECUTE ON FUNCTION mod_nomencladores.ft_modificar_nodo_especialidad() TO nomencladorsigis;

----------------------------------------------------------------------------
--Permisos del rol nomenclador sobre el esquema mod_nomencladores
----------------------------------------------------------------------------
----------------------------------------
--Permisos sobre el esquema
----------------------------------------
	SET search_path = mod_nomencladores, pg_catalog;

ALTER SCHEMA "mod_nomencladores" OWNER TO nomencladorsigis;
----------------------------------------
-- Permisos sobre secuencias
----------------------------------------
	SET search_path = mod_nomencladores, pg_catalog;

ALTER TABLE mod_nomencladores.sec_iddpa OWNER TO nomencladorsigis;
ALTER TABLE mod_nomencladores.sec_idespecialidad OWNER TO nomencladorsigis;
ALTER TABLE mod_nomencladores.sec_idpais OWNER TO nomencladorsigis;
ALTER TABLE mod_nomencladores.sec_idtipodpa OWNER TO nomencladorsigis;
----------------------------------------

ALTER TABLE "mod_nomencladores"."nom_dpa"
  OWNER TO "nomencladorsigis";
ALTER TABLE "mod_nomencladores"."nom_especialidad"
  OWNER TO "nomencladorsigis";
ALTER TABLE "mod_nomencladores"."nom_pais"
  OWNER TO "nomencladorsigis";
ALTER TABLE "mod_nomencladores"."nom_tipodpa"
  OWNER TO "nomencladorsigis";
----------------------------------------  
ALTER FUNCTION "mod_nomencladores"."f_reordenar_nom_dpa"(idnodo numeric, ordenizqnodo numeric)
  OWNER TO "nomencladorsigis";
ALTER FUNCTION "mod_nomencladores"."f_reordenar_nom_especialidad"(idnodo numeric, ordenizqnodo numeric)
  OWNER TO "nomencladorsigis";
ALTER FUNCTION "mod_nomencladores"."ft_actualizacion_arbol_dpa"()
  OWNER TO "nomencladorsigis";
ALTER FUNCTION "mod_nomencladores"."ft_actualizacion_arbol_especialidad"()
  OWNER TO "nomencladorsigis";
ALTER FUNCTION "mod_nomencladores"."ft_eliminar_nodo_dpa"()
  OWNER TO "nomencladorsigis";
ALTER FUNCTION "mod_nomencladores"."ft_eliminar_nodo_especialidad"()
  OWNER TO "nomencladorsigis";
ALTER FUNCTION "mod_nomencladores"."ft_insertar_nodo_dpa"()
  OWNER TO "nomencladorsigis";
ALTER FUNCTION "mod_nomencladores"."ft_insertar_nodo_especialidad"()
  OWNER TO "nomencladorsigis";
ALTER FUNCTION "mod_nomencladores"."ft_modificar_nodo_dpa"()
  OWNER TO "nomencladorsigis";
ALTER FUNCTION "mod_nomencladores"."ft_modificar_nodo_especialidad"()
  OWNER TO "nomencladorsigis";
--fin de la transaccion
COMMIT;