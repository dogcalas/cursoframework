-----------V7.0.0---------------------
/*
--CENTRO DE SOLUCIONES DE GESTIÓN		----
--Departamento de tecnología			----
--2014						----
--SCRIPT de INSTALACION de ESTRUCTURA	----
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
VALUES ('V7.0.0', 'V7.0.0','mod_generacionscript',null,'C','E','I');


	CREATE SCHEMA mod_generacionscript;
------------------------------------------------------------------------------------------------
--Estructura del Esquema de la herramienta para la generación de scripts------------------------
------------------------------------------------------------------------------------------------

	
-------------------------------------------------------------------------------
----Creación de secuencias
--------------------------------------------------------------------------------
	SET search_path = mod_generacionscript, pg_catalog;
		
CREATE SEQUENCE mod_generacionscript.dat_script_id_script_seq
  INCREMENT BY 1
  MINVALUE 1
  MAXVALUE 99999999
  START WITH 1
  CACHE 1;
	
CREATE SEQUENCE mod_generacionscript.nom_tiposcript_id_tiposcript_seq
  INCREMENT BY 1
  MINVALUE 1
  MAXVALUE 99999999
  START WITH 1
  CACHE 1;
-------------------------------------------------------------------------------
----Creación de tablas
-------------------------------------------------------------------------------
SET search_path = mod_generacionscript, pg_catalog;
	
CREATE TABLE mod_generacionscript.dat_script
(
  id_script numeric(19,0) NOT NULL DEFAULT nextval('mod_generacionscript.dat_script_id_script_seq'::regclass),
  nombre_paquete character varying(50),
  nombre_sistema character varying(50),
  version_sistema character varying(6),
  version_script character varying(6),
  id_tiposcript integer,
  usuario character varying(60),
  fecha date,
  ip_host character varying(15),
  definicionsql text,
  nombre_script character varying(20)
);

CREATE TABLE mod_generacionscript.nom_tiposscript
(
  id_tiposcript integer NOT NULL DEFAULT nextval('mod_generacionscript.nom_tiposcript_id_tiposcript_seq'::regclass),
  nombre character varying(50),
  descripcion character varying(100)
);
-------------------------------------------------------------------------------
----Creación de llaves primarias
--------------------------------------------------------------------------------
	SET search_path = mod_generacionscript, pg_catalog;
	
ALTER TABLE mod_generacionscript.dat_script
  ADD CONSTRAINT id_script PRIMARY KEY(id_script );

ALTER TABLE mod_generacionscript.nom_tiposscript
  ADD CONSTRAINT id_tiposcript PRIMARY KEY(id_tiposcript );

-------------------------------------------------------------------------------
----Creación de indices
--------------------------------------------------------------------------------
	SET search_path = mod_generacionscript, pg_catalog;
	
	CREATE INDEX fki_id_tiposcript ON mod_generacionscript.dat_script USING btree (id_tiposcript );
	
-------------------------------------------------------------------------------
----Creación de llaves foraneas
--------------------------------------------------------------------------------
	SET search_path = mod_generacionscript, pg_catalog;
	
ALTER TABLE mod_generacionscript.dat_script
  ADD CONSTRAINT id_tiposcript FOREIGN KEY (id_tiposcript)
      REFERENCES mod_generacionscript.nom_tiposscript (id_tiposcript) MATCH SIMPLE
      ON UPDATE CASCADE ON DELETE CASCADE;


--fin de la tranzaccion--
COMMIT;
