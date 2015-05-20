------------------V6.0.0---------------------
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
VALUES ('V6.0.0', 'V6.0.0','mod_nomencladores',null,'C','P','I');
----------------------------------------------------------------------------
--Permisos del rol nomenclador sobre el esquema mod_nomencladores
ALTER SCHEMA mod_nomencladores OWNER TO sauxe;
----------------------------------------------------------------------------
----------------------------------------
--Permisos sobre el esquema
----------------------------------------
	SET search_path = mod_nomencladores, pg_catalog;

GRANT USAGE ON SCHEMA mod_nomencladores TO sauxe;

--- Funciones

GRANT EXECUTE ON FUNCTION mod_nomencladores.f_reordenar_nom_dpa(numeric, numeric) TO sauxe;
GRANT EXECUTE ON FUNCTION mod_nomencladores.f_reordenar_nom_especialidad(numeric, numeric) TO sauxe;
ALTER FUNCTION mod_nomencladores.f_reordenar_nom_dpa(numeric, numeric)
  OWNER TO sauxe;
ALTER FUNCTION mod_nomencladores.f_reordenar_nom_especialidad(numeric, numeric)
  OWNER TO sauxe;
  
GRANT ALL ON TABLE mod_nomencladores.sec_iddpa TO sauxe;
GRANT ALL ON TABLE mod_nomencladores.sec_idespecialidad TO sauxe;
GRANT ALL ON TABLE mod_nomencladores.sec_idpais TO sauxe;
GRANT ALL ON TABLE mod_nomencladores.sec_idtipodpa TO sauxe;

--- Tabla
GRANT ALL ON TABLE mod_nomencladores.nom_dpa TO sauxe;
GRANT ALL ON TABLE mod_nomencladores.nom_especialidad TO sauxe;
GRANT ALL ON TABLE mod_nomencladores.nom_pais TO sauxe;
GRANT ALL ON TABLE mod_nomencladores.nom_tipodpa TO sauxe;

--- Funciones disparadora

GRANT EXECUTE ON FUNCTION mod_nomencladores.ft_actualizacion_arbol_dpa() TO sauxe;
GRANT EXECUTE ON FUNCTION mod_nomencladores.ft_actualizacion_arbol_especialidad() TO sauxe;
GRANT EXECUTE ON FUNCTION mod_nomencladores.ft_eliminar_nodo_dpa() TO sauxe;
GRANT EXECUTE ON FUNCTION mod_nomencladores.ft_eliminar_nodo_especialidad() TO sauxe;
GRANT EXECUTE ON FUNCTION mod_nomencladores.ft_insertar_nodo_dpa() TO sauxe;
GRANT EXECUTE ON FUNCTION mod_nomencladores.ft_insertar_nodo_especialidad() TO sauxe;
GRANT EXECUTE ON FUNCTION mod_nomencladores.ft_modificar_nodo_dpa() TO sauxe;
GRANT EXECUTE ON FUNCTION mod_nomencladores.ft_modificar_nodo_especialidad() TO sauxe;

ALTER FUNCTION mod_nomencladores.ft_actualizacion_arbol_dpa()
  OWNER TO sauxe;
ALTER FUNCTION mod_nomencladores.ft_actualizacion_arbol_especialidad()
  OWNER TO sauxe;
ALTER FUNCTION mod_nomencladores.ft_eliminar_nodo_dpa()
  OWNER TO sauxe;
ALTER FUNCTION mod_nomencladores.ft_eliminar_nodo_especialidad()
  OWNER TO sauxe;
ALTER FUNCTION mod_nomencladores.ft_insertar_nodo_dpa()
  OWNER TO sauxe;
ALTER FUNCTION mod_nomencladores.ft_insertar_nodo_especialidad()
  OWNER TO sauxe;
ALTER FUNCTION mod_nomencladores.ft_modificar_nodo_dpa()
  OWNER TO sauxe;
ALTER FUNCTION mod_nomencladores.ft_modificar_nodo_especialidad()
  OWNER TO sauxe;

ALTER TABLE mod_nomencladores.nom_tipodpa OWNER TO sauxe;
ALTER TABLE mod_nomencladores.nom_dpa OWNER TO sauxe;
ALTER TABLE mod_nomencladores.nom_especialidad OWNER TO sauxe;
ALTER TABLE mod_nomencladores.nom_pais OWNER TO sauxe;
  
ALTER TABLE mod_nomencladores.sec_iddpa OWNER TO sauxe;
ALTER TABLE mod_nomencladores.sec_idespecialidad OWNER TO sauxe;
ALTER TABLE mod_nomencladores.sec_idpais OWNER TO sauxe;
ALTER TABLE mod_nomencladores.sec_idtipodpa OWNER TO sauxe;


COMMIT;
