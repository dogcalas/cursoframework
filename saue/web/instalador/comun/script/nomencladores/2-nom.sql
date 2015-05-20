-------------------V6.0.0---------------------
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
	

INSERT INTO mod_datosmaestros.conf_version ( versionscript,  versionscriptinicial,  esquema,  esquemarelacion,  tiposcript,  classcript,  script) 
VALUES ('V6.0.0', 'V6.0.0','mod_nomencladores',null,'C','E','I');	
	
	
	CREATE SCHEMA mod_nomencladores;	
------------------------------------------------------------------------------------------------
--Estructura del Esquema Nomencladores----------------------------------------------------------
------------------------------------------------------------------------------------------------	
	


------------------------------------------------------------------------------------------------
--Esquema datosmaestros-------------------------------------------------------------------------
------------------------------------------------------------------------------------------------	
	SET search_path = mod_nomencladores, pg_catalog;
	
	--Insercción de datos para la generación de secuencias generales
	SET search_path = mod_datosmaestros, pg_catalog;	
	INSERT INTO nom_secuenciasgeneral VALUES ('sec_iddpa', 10, 1, 10, 'mod_nomencladores');	
	INSERT INTO nom_secuenciasgeneral VALUES ('sec_idespecialidad', 10, 1, 10, 'mod_nomencladores');	
	INSERT INTO nom_secuenciasgeneral VALUES ('sec_idpais', 10, 1, 10, 'mod_nomencladores');	
	INSERT INTO nom_secuenciasgeneral VALUES ('sec_idtipodpa', 10, 1, 10, 'mod_nomencladores');	
	
-------------------------------------------------------------------------------
----Creación de secuencias
--------------------------------------------------------------------------------
	SET search_path = mod_nomencladores, pg_catalog;

		
-------------------------------------------------------------------------------
----Creación de tablas
--------------------------------------------------------------------------------
	SET search_path = mod_nomencladores, pg_catalog;
	

	CREATE TABLE nom_dpa 
	(
		iddpa numeric(19,0) DEFAULT nextval('mod_nomencladores.sec_iddpa'::regclass) NOT NULL,
		idpadre numeric(19,0),
		ordenizq numeric(19,0),
		ordender numeric(19,0),
		denominacion character varying(100),
		abreviatura character varying(20),
		idtipodpa numeric(19,0),
		codigo character varying(50),
		idpais numeric(19,0)
	);

	CREATE TABLE nom_especialidad 
	(
		idespecialidad numeric(19,0) DEFAULT nextval('mod_nomencladores.sec_idespecialidad'::regclass) NOT NULL,
		idpadre numeric(19,0),
		ordenizq numeric(19,0),
		ordender numeric(19,0),
		abrevespecialidad character varying(20),
		denespecialidad character varying(60),
		codespecialidad character varying(50),
		nivel numeric(10,0),
		fechaini character varying(20),
		fechafin character varying(20)
	);

	CREATE TABLE nom_pais 
	(
		idpais numeric(19,0) DEFAULT nextval('mod_nomencladores.sec_idpais'::regclass) NOT NULL,
		nombrepais character varying(50),
		codigopais character varying(3),
		siglas character varying(10),
        nacionalidad character varying(25)
	);




	CREATE TABLE nom_tipodpa 
	(
		idtipodpa numeric(19,0) DEFAULT nextval('mod_nomencladores.sec_idtipodpa'::regclass) NOT NULL,
		denominacion character varying(255),
		orden numeric(10,0),
		fechaini character varying(20),
		fechafin character varying(20)
	);

-------------------------------------------------------------------------------
----Creación de llaves primarias
--------------------------------------------------------------------------------
	SET search_path = mod_nomencladores, pg_catalog;


	ALTER TABLE ONLY nom_dpa
		ADD CONSTRAINT nom_dpa_pkey PRIMARY KEY (iddpa);


	ALTER TABLE ONLY nom_especialidad
		ADD CONSTRAINT nom_especialidad_pkey PRIMARY KEY (idespecialidad);

	ALTER TABLE ONLY nom_pais
		ADD CONSTRAINT nom_pais_pkey PRIMARY KEY (idpais);


	ALTER TABLE ONLY nom_tipodpa
		ADD CONSTRAINT nom_tipodpa_pkey PRIMARY KEY (idtipodpa);

	
-------------------------------------------------------------------------------
----Creación de otras restricciones de datos
--------------------------------------------------------------------------------
	SET search_path = mod_nomencladores, pg_catalog;		
		

-------------------------------------------------------------------------------
----Creación de indices
--------------------------------------------------------------------------------
	SET search_path = mod_nomencladores, pg_catalog;
	
-------------------------------------------------------------------------------
----Creación de tipos de datos
--------------------------------------------------------------------------------
	SET search_path = mod_nomencladores, pg_catalog;
	
-------------------------------------------------------------------------------
----Creación de llaves foraneas
--------------------------------------------------------------------------------
	SET search_path = mod_nomencladores, pg_catalog;
	

ALTER TABLE ONLY nom_dpa
    ADD CONSTRAINT nom_dpa_fk FOREIGN KEY (idtipodpa) REFERENCES nom_tipodpa(idtipodpa);


ALTER TABLE ONLY nom_dpa
    ADD CONSTRAINT nom_dpa_fk1 FOREIGN KEY (idpais) REFERENCES nom_pais(idpais);


ALTER TABLE ONLY nom_dpa
    ADD CONSTRAINT nom_dpa_iddpa FOREIGN KEY (idpadre) REFERENCES nom_dpa(iddpa) ON UPDATE CASCADE ON DELETE CASCADE;


ALTER TABLE ONLY nom_especialidad
    ADD CONSTRAINT nom_especialidad_idespecialidad FOREIGN KEY (idpadre) REFERENCES nom_especialidad(idespecialidad) ON UPDATE CASCADE ON DELETE CASCADE;

-------------------------------------------------------------------------------
----Creación de funciones
--------------------------------------------------------------------------------
	SET search_path = mod_nomencladores, pg_catalog;


CREATE FUNCTION f_reordenar_nom_dpa(idnodo numeric, ordenizqnodo numeric) RETURNS numeric
    AS $_$
DECLARE
       ultimoordender BIGINT;
       canthijos INTEGER;
       esprimero INTEGER;
       hijo RECORD;
BEGIN
     SET search_path = mod_nomencladores;
     canthijos := count(iddpa) FROM mod_nomencladores.nom_dpa WHERE idpadre = $1 AND idpadre <> iddpa;
     IF canthijos > 0 THEN
        ultimoordender := $2 + 1;
     ELSE
         esprimero := 1;
         FOR hijo IN SELECT iddpa, ordenizq FROM mod_nomencladores.nom_dpa WHERE iddpa = $1 LOOP
             IF esprimero = 1 THEN
                hijo.ordenizq := $2 + 1;
                esprimero := 0;
             ELSE
                 hijo.ordenizq := ultimoordender + 1;
             END IF;
             ultimoordender := f_reordenar_nom_dpa(hijo.iddpa, hijo.ordenizq);
             UPDATE mod_nomencladores.nom_dpa SET ordenizq = hijo.ordenizq, ordender = ultimoordender WHERE iddpa = hijo.iddpa;
         END LOOP;
     END IF;
     RETURN ultimoordender;
END;
$_$
    LANGUAGE plpgsql;

CREATE FUNCTION f_reordenar_nom_especialidad(idnodo numeric, ordenizqnodo numeric) RETURNS numeric
    AS $_$
DECLARE
       ultimoordender BIGINT;
       canthijos INTEGER;
       esprimero INTEGER;
       hijo RECORD;
BEGIN
     SET search_path = mod_nomencladores;
     canthijos := count(idespecialidad) FROM mod_nomencladores.nom_especialidad WHERE idpadre = $1 AND idpadre <> idespecialidad;
     IF canthijos > 0 THEN
        ultimoordender := $2 + 1;
     ELSE
         esprimero := 1;
         FOR hijo IN SELECT idespecialidad, ordenizq FROM mod_nomencladores.nom_especialidad WHERE idespecialidad = $1 LOOP
             IF esprimero = 1 THEN
                hijo.ordenizq := $2 + 1;
                esprimero := 0;
             ELSE
                 hijo.ordenizq := ultimoordender + 1;
             END IF;
             ultimoordender := f_reordenar_nom_especialidad(hijo.idespecialidad, hijo.ordenizq);
             UPDATE mod_nomencladores.nom_especialidad SET ordenizq = hijo.ordenizq, ordender = ultimoordender WHERE idespecialidad = hijo.idespecialidad;
         END LOOP;
     END IF;
     RETURN ultimoordender;
END;
$_$
    LANGUAGE plpgsql;

CREATE FUNCTION ft_actualizacion_arbol_especialidad() RETURNS trigger
    AS $$
BEGIN
if (new.idpadre is null ) then
    new.idpadre = new.idespecialidad;
end if;
RETURN new;
END;
$$
    LANGUAGE plpgsql;

CREATE FUNCTION ft_eliminar_nodo_dpa() RETURNS trigger
    AS $$
DECLARE
       ancho BIGINT;
BEGIN
	SET search_path =mod_nomencladores;
	ancho := OLD.ordender - OLD.ordenizq + 1;
	UPDATE mod_nomencladores.nom_dpa SET ordender = ordender - 2 WHERE ordender > OLD.ordender - ancho;
	UPDATE mod_nomencladores.nom_dpa SET ordenizq = ordenizq - 2 WHERE ordenizq > OLD.ordender - ancho;
	RETURN OLD;
	EXCEPTION WHEN foreign_key_violation THEN
         RETURN OLD;
END;
$$
    LANGUAGE plpgsql;

CREATE FUNCTION ft_eliminar_nodo_especialidad() RETURNS trigger
    AS $$
DECLARE
       ancho BIGINT;
BEGIN
	SET search_path =mod_nomencladores;
	ancho := OLD.ordender - OLD.ordenizq + 1;
	UPDATE mod_nomencladores.nom_especialidad SET ordender = ordender - 2 WHERE ordender > OLD.ordender - ancho;
	UPDATE mod_nomencladores.nom_especialidad SET ordenizq = ordenizq - 2 WHERE ordenizq > OLD.ordender - ancho;
	RETURN OLD;
	EXCEPTION WHEN foreign_key_violation THEN
         RETURN OLD;
END;
$$
    LANGUAGE plpgsql;

CREATE FUNCTION ft_insertar_nodo_dpa() RETURNS trigger
    AS $$
DECLARE
        derecha bigint;
BEGIN
	SET search_path = mod_nomencladores;
	IF NEW.iddpa != NEW.idpadre THEN
       derecha := ordender FROM mod_nomencladores.nom_dpa WHERE iddpa = NEW.idpadre;
       UPDATE mod_nomencladores.nom_dpa SET ordender = ordender + 2 WHERE ordender >= derecha;
       UPDATE mod_nomencladores.nom_dpa SET ordenizq = ordenizq + 2 WHERE ordenizq > derecha;
       NEW.ordenizq := derecha;
       NEW.ordender := derecha + 1;
    ELSE
        derecha :=  MAX(ordender) FROM mod_nomencladores.nom_dpa WHERE iddpa = idpadre;
        IF derecha IS NOT NULL THEN
           NEW.ordenizq := derecha + 1;
           NEW.ordender := derecha + 2;
        ELSE
            NEW.ordenizq := 1;
            NEW.ordender := 2;
        END IF;
    END IF;
	RETURN NEW;
END;
$$
    LANGUAGE plpgsql;






CREATE FUNCTION ft_insertar_nodo_especialidad() RETURNS trigger
    AS $$
DECLARE
        derecha bigint;
BEGIN
	SET search_path = mod_nomencladores;
	IF NEW.idespecialidad != NEW.idpadre THEN
       derecha := ordender FROM mod_nomencladores.nom_especialidad WHERE idespecialidad = NEW.idpadre;
       UPDATE mod_nomencladores.nom_especialidad SET ordender = ordender + 2 WHERE ordender >= derecha;
       UPDATE mod_nomencladores.nom_especialidad SET ordenizq = ordenizq + 2 WHERE ordenizq > derecha;
       NEW.ordenizq := derecha;
       NEW.ordender := derecha + 1;
    ELSE
        derecha :=  MAX(ordender) FROM mod_nomencladores.nom_especialidad WHERE idespecialidad = idpadre;
        IF derecha IS NOT NULL THEN
           NEW.ordenizq := derecha + 1;
           NEW.ordender := derecha + 2;
        ELSE
            NEW.ordenizq := 1;
            NEW.ordender := 2;
        END IF;
    END IF;
	RETURN NEW;
END;
$$
    LANGUAGE plpgsql;


CREATE FUNCTION ft_modificar_nodo_dpa() RETURNS trigger
    AS $$
DECLARE
	raiz RECORD;
	esprimero INTEGER;
	ultimoordender BIGINT;
BEGIN
	IF OLD.idpadre != NEW.idpadre THEN
		SET search_path = mod_nomencladores;
		esprimero := 1;
		FOR raiz IN SELECT iddpa, ordenizq FROM mod_nomencladores.nom_dpa WHERE iddpa = idpadre LOOP
		    IF esprimero = 1 THEN
                raiz.ordenizq := 1;
                esprimero := 0;
             ELSE
                 raiz.ordenizq := ultimoordender + 1;
             END IF;
             ultimoordender := f_reordenar_nom_dpa(raiz.iddpa, raiz.ordenizq);
             UPDATE mod_nomencladores.nom_dpa SET ordenizq = raiz.ordenizq, ordender = ultimoordender WHERE iddpa = raiz.iddpa;
		END LOOP;
	END IF;
	RETURN NEW;
END;
$$
    LANGUAGE plpgsql;

CREATE FUNCTION ft_modificar_nodo_especialidad() RETURNS trigger
    AS $$
DECLARE
	raiz RECORD;
	esprimero INTEGER;
	ultimoordender BIGINT;
BEGIN
	IF OLD.idpadre != NEW.idpadre THEN
		SET search_path = mod_nomencladores;
		esprimero := 1;
		FOR raiz IN SELECT idespecialidad, ordenizq FROM mod_nomencladores.nom_especialidad WHERE idespecialidad = idpadre LOOP
		    IF esprimero = 1 THEN
                raiz.ordenizq := 1;
                esprimero := 0;
             ELSE
                 raiz.ordenizq := ultimoordender + 1;
             END IF;
             ultimoordender := f_reordenar_nom_especialidad(raiz.idespecialidad, raiz.ordenizq);
             UPDATE mod_nomencladores.nom_especialidad SET ordenizq = raiz.ordenizq, ordender = ultimoordender WHERE idespecialidad = raiz.idespecialidad;
		END LOOP;
	END IF;
	RETURN NEW;
END;
$$
    LANGUAGE plpgsql;



CREATE FUNCTION ft_actualizacion_arbol_dpa() RETURNS trigger
    AS $$
BEGIN

if (new.idpadre is null ) then
    new.idpadre = new.iddpa;
end if;
RETURN new;
END;
$$
    LANGUAGE plpgsql;

-------------------------------------------------------------------------------
----Creación de triggers
--------------------------------------------------------------------------------
	SET search_path = mod_nomencladores, pg_catalog;



CREATE TRIGGER t_actualizarpadredpa
    BEFORE INSERT ON nom_dpa
    FOR EACH ROW
    EXECUTE PROCEDURE ft_actualizacion_arbol_dpa();


CREATE TRIGGER t_actualizarpadreespecialidad
    BEFORE INSERT ON nom_especialidad
    FOR EACH ROW
    EXECUTE PROCEDURE ft_actualizacion_arbol_especialidad();


CREATE TRIGGER t_eliminar_dpa
    AFTER DELETE ON nom_dpa
    FOR EACH ROW
    EXECUTE PROCEDURE ft_eliminar_nodo_dpa();


CREATE TRIGGER t_eliminar_especialidad
    AFTER DELETE ON nom_especialidad
    FOR EACH ROW
    EXECUTE PROCEDURE ft_eliminar_nodo_especialidad();



CREATE TRIGGER t_insertar_dpa
    BEFORE INSERT ON nom_dpa
    FOR EACH ROW
    EXECUTE PROCEDURE ft_insertar_nodo_dpa();


CREATE TRIGGER t_insertar_especialidad
    BEFORE INSERT ON nom_especialidad
    FOR EACH ROW
    EXECUTE PROCEDURE ft_insertar_nodo_especialidad();


CREATE TRIGGER t_modificar_dpa
    AFTER UPDATE ON nom_dpa
    FOR EACH ROW
    EXECUTE PROCEDURE ft_modificar_nodo_dpa();


CREATE TRIGGER t_modificar_especialidad
    AFTER UPDATE ON nom_especialidad
    FOR EACH ROW
    EXECUTE PROCEDURE ft_modificar_nodo_especialidad();



---------------------------------------------------------

--termina la tranzaccion--
COMMIT; 
