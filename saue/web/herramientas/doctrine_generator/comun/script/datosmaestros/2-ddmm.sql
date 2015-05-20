--------------------V5.0.0---------------------
/*
--CENTRO DE SOLUCIONES DE GESTIÓN		----
--Subdirección de tecnología			----
--										----
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
	

	
	CREATE SCHEMA mod_datosmaestros;
------------------------------------------------------------------------------------------------
--Estructura del Esquema Datos Maestros---------------------------------------------------------
------------------------------------------------------------------------------------------------
	SET search_path = mod_datosmaestros, pg_catalog;
	
	--Tabla nom servidor
	CREATE TABLE nom_servidor 
	(
		idservidor numeric(19,0)
	) WITHOUT OIDS;
	
	--Tabla nom secuencias 
	CREATE TABLE nom_secuenciasgeneral 
	(
		nombresecuencia character varying,
		valor_inicial numeric(19,0),
		valor_incrementar numeric(10,0),
		valor_final numeric(19,0),
		nombreesquema character varying(50)
	) WITHOUT OIDS;
	
	
	--Llave primaria de nom_secuencia
	ALTER TABLE ONLY nom_secuenciasgeneral
    ADD CONSTRAINT nom_secuencias_nombresecuenciageneral_key PRIMARY KEY (nombresecuencia, nombreesquema);
	
	--Llave primaria de nom_servidor
	ALTER TABLE ONLY nom_servidor
    ADD CONSTRAINT nom_servidor_idservidor_key PRIMARY KEY (idservidor);
	
	--Datos del servidor  
	--Aqui se le pone el id del servidor es decir en numero de despliegue
	INSERT INTO mod_datosmaestros.nom_servidor(idservidor) VALUES (?);	
	
	
	--Función para la creación de las secuencias generales	
	CREATE OR REPLACE FUNCTION ft_creacionsecuenciasact () RETURNS trigger AS
		$body$
			DECLARE

			v_servidor numeric;

			BEGIN

				select ser.idservidor into v_servidor
					from mod_datosmaestros.nom_servidor ser;
					
					EXECUTE 'CREATE SEQUENCE ' || NEW.nombreesquema || '.' || NEW.nombresecuencia ||
						 ' INCREMENT ' || NEW.valor_incrementar || ' MINVALUE ' || 
						  rpad(v_servidor::VARCHAR,(length(v_servidor::VARCHAR) + NEW.valor_inicial)::INTEGER, '0') 
						  || ' MAXVALUE ' || rpad(v_servidor::VARCHAR,(length(v_servidor::VARCHAR) + NEW.valor_final)::INTEGER, '9') 
						  || ' START ' || rpad(v_servidor::VARCHAR,(length(v_servidor::VARCHAR) + NEW.valor_inicial)::INTEGER, '0') || ';';
				 
				RETURN new;    
			 
			END;
		$body$
	LANGUAGE 'plpgsql';
	
	--Trigger para la creación de las secuencias generales	
	CREATE TRIGGER t_creacionsecuenciasact AFTER INSERT 
	ON nom_secuenciasgeneral FOR EACH ROW 
	EXECUTE PROCEDURE ft_creacionsecuenciasact();
	
	--Insercción de datos para la generación de secuencias generales
	SET search_path = mod_datosmaestros, pg_catalog;	
	
	INSERT INTO nom_secuenciasgeneral VALUES ('sec_version_seq', 14, 1, 14, 'mod_datosmaestros');	
	


-------------------------------------------------------------------------------
----Creación de secuecnias
--------------------------------------------------------------------------------
	SET search_path = mod_datosmaestros, pg_catalog;
	
-------------------------------------------------------------------------------
----Creación de tablas
--------------------------------------------------------------------------------
	SET search_path = mod_datosmaestros, pg_catalog;
	
	--Tabla para llevar la version de la base de datos
	CREATE TABLE conf_version
	(
	  "idversion" NUMERIC(19,0) DEFAULT nextval('mod_datosmaestros.sec_version_seq'::regclass) NOT NULL, 
	  "fecha" DATE DEFAULT now(), 
	  "versionscript" VARCHAR(255) NOT NULL, 
	  "versionscriptinicial" VARCHAR(255) NOT NULL,
	  "esquema" VARCHAR(100) NOT NULL, 
	  "esquemarelacion" VARCHAR(255),
	  "tiposcript" VARCHAR(100) NOT NULL,
	  "classcript" VARCHAR(100) NOT NULL,
	  "script" VARCHAR(100) NOT NULL,
	  CONSTRAINT "conf_version_pkey" PRIMARY KEY("idversion")
	);	
	
-------------------------------------------------------------------------------
----Creación de llaves primarias
--------------------------------------------------------------------------------
	SET search_path = mod_datosmaestros, pg_catalog;

-------------------------------------------------------------------------------
----Creación de triggers
--------------------------------------------------------------------------------	
	SET search_path = mod_datosmaestros, pg_catalog;


---------------------------------------------------------------------------
--versionado
INSERT INTO mod_datosmaestros.conf_version ( versionscript,  versionscriptinicial,  esquema,  esquemarelacion,  tiposcript,  classcript,  script) 
VALUES ('V5.0.0', 'V5.0.0','mod_datosmaestros',null,'C','E','I');
-----------------------------------------------------------------------------

--termina la tranzaccion--
COMMIT; 