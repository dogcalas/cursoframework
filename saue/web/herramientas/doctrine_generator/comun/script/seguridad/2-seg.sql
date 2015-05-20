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
	
INSERT INTO mod_datosmaestros.conf_version ( versionscript,  versionscriptinicial,  esquema,  esquemarelacion,  tiposcript,  classcript,  script) 
VALUES ('V5.0.0', 'V5.0.0','mod_seguridad',null,'C','E','I');	
	
	CREATE SCHEMA mod_seguridad;
------------------------------------------------------------------------------------------------
--Estructura del Esquema Seguridad---------------------------------------------------------------
------------------------------------------------------------------------------------------------
	


	
------------------------------------------------------------------------------------------------
--Esquema Datos Maestros------------------------------------------------------------------------
------------------------------------------------------------------------------------------------	
	--Insercción de datos para la generación de secuencias
	SET search_path = mod_datosmaestros, pg_catalog;	
		
	INSERT INTO nom_secuenciasgeneral VALUES ('sec_nomtema_seq', 6, 1, 6, 'mod_seguridad');
	INSERT INTO nom_secuenciasgeneral VALUES ('sec_nomdesktop_seq', 6, 1, 6, 'mod_seguridad');
	INSERT INTO nom_secuenciasgeneral VALUES ('sec_nomidioma_seq', 6, 1, 6, 'mod_seguridad');
	INSERT INTO nom_secuenciasgeneral VALUES ('sec_nomexpresiones_seq', 6, 1, 6, 'mod_seguridad');
	INSERT INTO nom_secuenciasgeneral VALUES ('sec_nomcampo_seq', 10, 1, 10, 'mod_seguridad');
	INSERT INTO nom_secuenciasgeneral VALUES ('sec_nomfila_seq', 10, 1, 10, 'mod_seguridad');
	INSERT INTO nom_secuenciasgeneral VALUES ('sec_nomvalor_seq', 10, 1, 10, 'mod_seguridad');
	INSERT INTO nom_secuenciasgeneral VALUES ('sec_segusuario_seq', 10, 1, 10, 'mod_seguridad');
	INSERT INTO nom_secuenciasgeneral VALUES ('sec_segcertificado_seq', 6, 1, 6, 'mod_seguridad');
	INSERT INTO nom_secuenciasgeneral VALUES ('sec_segclaveacceso_seq', 6, 1, 6, 'mod_seguridad');
	INSERT INTO nom_secuenciasgeneral VALUES ('sec_datesquema_seq', 6, 1, 6, 'mod_seguridad');
	INSERT INTO nom_secuenciasgeneral VALUES ('sec_datbd_seq', 6, 1, 6, 'mod_seguridad');
	INSERT INTO nom_secuenciasgeneral VALUES ('sec_datgestor_seq', 6, 1, 6, 'mod_seguridad');
	INSERT INTO nom_secuenciasgeneral VALUES ('sec_datservidor_seq', 6, 1, 6, 'mod_seguridad');
	INSERT INTO nom_secuenciasgeneral VALUES ('sec_segrol_seq', 10, 1, 10, 'mod_seguridad');
	INSERT INTO nom_secuenciasgeneral VALUES ('sec_datservicio_seq', 10, 1, 10, 'mod_seguridad');
	INSERT INTO nom_secuenciasgeneral VALUES ('sec_datfunciones_seq', 10, 1, 10, 'mod_seguridad');
	INSERT INTO nom_secuenciasgeneral VALUES ('sec_datparametros_seq', 10, 1, 10, 'mod_seguridad');
	INSERT INTO nom_secuenciasgeneral VALUES ('sec_segrestricclaveacceso_seq', 2, 1, 2, 'mod_seguridad');
	INSERT INTO nom_secuenciasgeneral VALUES ('sec_segrolesbd_seq', 10, 1, 10, 'mod_seguridad');
	
------------------------------------------------------------------------------------------------
--Estructura del Esquema Seguridad---------------------------------------------------------
------------------------------------------------------------------------------------------------
	SET search_path = mod_seguridad, pg_catalog;
	
	
-------------------------------------------------------------------------------
----Creación de secuencias
--------------------------------------------------------------------------------
	SET search_path = mod_seguridad, pg_catalog;
	
	CREATE SEQUENCE "mod_seguridad"."sec_datsistema_seq"
    INCREMENT 1  MINVALUE 1
    MAXVALUE 19999999999  START 1
    CACHE 1;
	
	CREATE SEQUENCE "mod_seguridad"."sec_datfuncionalidad_seq"
    INCREMENT 1  MINVALUE 1
    MAXVALUE 19999999999  START 1
    CACHE 1;
	
	CREATE SEQUENCE "mod_seguridad"."sec_dataccion_seq"
    INCREMENT 1  MINVALUE 1
    MAXVALUE 19999999999999  START 1
    CACHE 1;
-------------------------------------------------------------------------------
----Creación de tablas
--------------------------------------------------------------------------------
	SET search_path = mod_seguridad, pg_catalog;
	
	
CREATE TABLE dat_accion (
    idaccion numeric(19,0) DEFAULT nextval('mod_seguridad.sec_dataccion_seq'::regclass) NOT NULL,
    icono character varying(250),
    denominacion text NOT NULL,
    descripcion character varying(250),
    abreviatura text NOT NULL,
    idfuncionalidad numeric(19,0) NOT NULL,
	iddominio numeric(19,0)
);

CREATE TABLE dat_bd (
    idbd numeric(19,0) DEFAULT nextval('mod_seguridad.sec_datbd_seq'::regclass) NOT NULL,
    descripcion character varying(250),
    denominacion character varying(250) NOT NULL
);

CREATE TABLE dat_entidad_seg_usuario_seg_rol (
    idusuario numeric(19,0) NOT NULL,
    identidad numeric(19,0) NOT NULL,
    idrol numeric(19,0) NOT NULL
	
);

CREATE TABLE dat_esquema (
    idesquema numeric(19,0) DEFAULT nextval('mod_seguridad.sec_datesquema_seq'::regclass) NOT NULL,
    descripcion character varying(250),
    denominacion character varying(50) NOT NULL
);

CREATE TABLE dat_funcionalidad (
    idfuncionalidad numeric(19,0) DEFAULT nextval('mod_seguridad.sec_datfuncionalidad_seq'::regclass) NOT NULL,
    index integer DEFAULT 0,
    referencia character varying(255),
    denominacion text NOT NULL,
    descripcion character varying(255),
    icono character varying(30),
    idsistema numeric(19,0) NOT NULL,
	iddominio numeric(19,0)
);

CREATE TABLE dat_funciones (
    idfunciones numeric(19,0) DEFAULT nextval('mod_seguridad.sec_datfunciones_seq'::regclass) NOT NULL,
    denominacion character varying(250) NOT NULL,
    descripcion character varying(250),
    idservicio numeric(19,0) NOT NULL
);

CREATE TABLE dat_gestor (
    idgestor numeric(19,0) DEFAULT nextval('mod_seguridad.sec_datgestor_seq'::regclass) NOT NULL,
    gestor character varying(50) NOT NULL,
    puerto integer NOT NULL,
    descripcion character varying(250)
);

CREATE TABLE dat_gestor_dat_servidorbd (
    idservidor numeric(19,0) NOT NULL,
    idgestor numeric(19,0) NOT NULL
);

CREATE TABLE dat_parametros (
    idparametros numeric(19,0) DEFAULT nextval('mod_seguridad.sec_datparametros_seq'::regclass) NOT NULL,
    tipoparametro character varying(250) NOT NULL,
    valordefecto character varying(250),
    descripcion character varying(250),
    denominacion character varying(250) NOT NULL,
    puedesernull boolean NOT NULL,
    idfunciones numeric(19,0) NOT NULL
);

CREATE TABLE dat_serautenticacion (
    idservidor numeric(19,0) NOT NULL
);

CREATE TABLE dat_servicio (
    idservicio numeric(19,0) DEFAULT nextval('mod_seguridad.sec_datservicio_seq'::regclass) NOT NULL,
    denominacion text NOT NULL,
    descripcion character varying(255),
    wsdl character varying(255) NOT NULL,
    idsistema numeric(19,0) NOT NULL
);

CREATE TABLE dat_servicio_dat_sistema (
    idsistema numeric(19,0) NOT NULL,
    idservicio numeric(19,0) NOT NULL,
	externa varchar(20)
);

CREATE TABLE dat_servidor (
    idservidor numeric(19,0) DEFAULT nextval('mod_seguridad.sec_datservidor_seq'::regclass) NOT NULL,
    tiposervidor character varying(250) NOT NULL,
    denominacion character varying(50) NOT NULL,
    descripcion character varying(250),
    ip character varying(50) NOT NULL
);

CREATE TABLE dat_servidorbd (
    idservidor numeric(19,0) NOT NULL
);

CREATE TABLE dat_sistema (
    idsistema numeric(19,0) DEFAULT nextval('mod_seguridad.sec_datsistema_seq'::regclass) NOT NULL,
    idpadre numeric(19,0) NOT NULL,
    denominacion character varying(255) NOT NULL,
    icono character varying(30),
    abreviatura character varying(255),
    descripcion character varying(255),
	externa varchar(20),
	lft numeric(19,0),
	rgt numeric(19,0),
	iddominio numeric(19,0)
);

CREATE TABLE dat_sistema_seg_rol (
    idrol numeric(19,0) NOT NULL,
    idsistema numeric(19,0) NOT NULL
    --identidad numeric(19,0) NOT NULL
);

CREATE TABLE dat_sistema_seg_rol_dat_funcionalidad (
    idfuncionalidad numeric(19,0) NOT NULL,
    idrol numeric(19,0) NOT NULL,
    idsistema numeric(19,0) NOT NULL
   -- identidad numeric(19,0) NOT NULL
);

CREATE TABLE dat_sistema_seg_rol_dat_funcionalidad_dat_accion (
    idaccion numeric(19,0) NOT NULL,
    idfuncionalidad numeric(19,0) NOT NULL,
    idrol numeric(19,0) NOT NULL,
    idsistema numeric(19,0) NOT NULL
    --identidad numeric(19,0) NOT NULL
);

CREATE TABLE seg_usuario_dat_serautenticacion (
    idservidor numeric(19,0) NOT NULL,
    idusuario numeric(19,0) NOT NULL
);

CREATE TABLE dat_sistema_dat_servidores (
    idbd numeric(19,0) NOT NULL,
    idsistema numeric(19,0) NOT NULL,
    idesquema numeric(19,0) NOT NULL,
    idservidor numeric(19,0) NOT NULL,
    idgestor numeric(19,0) NOT NULL,
	idrolesbd numeric(19,0) NOT NULL
);

CREATE TABLE nom_campo (
    idcampo numeric(19,0) DEFAULT nextval('mod_seguridad.sec_nomcampo_seq'::regclass) NOT NULL,
    tipo character varying(250) NOT NULL,
    nombre character varying(250) NOT NULL,
    nombreamostrar character varying(250),
    longitud integer,
    visible boolean,
    descripcion character varying(250),
    tipocampo character varying(250) NOT NULL,
    idexpresiones numeric(18,0) NOT NULL
);

CREATE TABLE nom_desktop (
    iddesktop numeric(19,0) DEFAULT nextval('mod_seguridad.sec_nomdesktop_seq'::regclass) NOT NULL,
    denominacion character varying(250) NOT NULL,
    abreviatura character varying(50) NOT NULL,
    descripcion character varying(250)
);

CREATE TABLE nom_expresiones (
    idexpresiones numeric(18,0) DEFAULT nextval('mod_seguridad.sec_nomexpresiones_seq'::regclass) NOT NULL,
    denominacion character varying(250) NOT NULL,
    expresion character varying(250) NOT NULL,
    descripcion character varying(250)
);

CREATE TABLE nom_fila (
    idfila numeric(19,0) DEFAULT nextval('mod_seguridad.sec_nomfila_seq'::regclass) NOT NULL,
    idusuario numeric(19,0) NOT NULL
);

CREATE TABLE nom_idioma (
    ididioma numeric(19,0) DEFAULT nextval('mod_seguridad.sec_nomidioma_seq'::regclass) NOT NULL,
    denominacion character varying(250) NOT NULL,
    abreviatura character varying(50)
);

CREATE TABLE nom_tema (
    idtema numeric(19,0) DEFAULT nextval('mod_seguridad.sec_nomtema_seq'::regclass) NOT NULL,
    denominacion character varying(250) NOT NULL,
    abreviatura character varying(50) NOT NULL,
    descripcion character varying(250)
);

CREATE TABLE nom_valor (
    idvalor numeric(19,0) DEFAULT nextval('mod_seguridad.sec_nomvalor_seq'::regclass) NOT NULL,
    idfila numeric(19,0),
    idcampo numeric(19,0),
    valor character varying(250)
);

CREATE TABLE seg_certificado (
    idcertificado numeric(19,0) DEFAULT nextval('mod_seguridad.sec_segcertificado_seq'::regclass) NOT NULL,
    mac character varying(255) NOT NULL,
    valor character varying(255) NOT NULL,
    idusuario numeric(19,0) NOT NULL
);

CREATE TABLE seg_claveacceso (
    pkidclaveacceso numeric(19,0) DEFAULT nextval('mod_seguridad.sec_segclaveacceso_seq'::regclass) NOT NULL,
    valor boolean NOT NULL,
    fechainicio time without time zone NOT NULL,
    fechafin time without time zone NOT NULL,
    clave character varying(255) NOT NULL,
    idusuario numeric(19,0) NOT NULL
);

CREATE TABLE seg_restricclaveacceso (
    idrestricclaveacceso numeric(19,0) DEFAULT nextval('mod_seguridad.sec_segrestricclaveacceso_seq'::regclass) NOT NULL,
    diascaducidad integer NOT NULL,
    numerica boolean,
    alfabetica boolean,
    signos boolean,
    minimocaracteres integer NOT NULL
);

CREATE TABLE seg_rol (
    idrol numeric(19,0) DEFAULT nextval('mod_seguridad.sec_segrol_seq'::regclass) NOT NULL,
    denominacion text NOT NULL,
    descripcion character varying(255),
    abreviatura text
);

CREATE TABLE seg_usuario (
    idusuario numeric(19,0) DEFAULT nextval('mod_seguridad.sec_segusuario_seq'::regclass) NOT NULL,
    idcargo numeric(19,0) DEFAULT 0,
    idarea numeric(19,0) DEFAULT 0,
    identidad numeric(19,0) DEFAULT 0,
    nombreusuario text NOT NULL,
    contrasenna character varying(255) NOT NULL,
    contrasenabd character varying(50) NOT NULL,
    ip character varying(255),
    iddominio numeric(18,0) NOT NULL,
    iddesktop numeric(19,0) NOT NULL,
    idtema numeric(19,0) NOT NULL,
    ididioma numeric(19,0) NOT NULL,
	estado character(1) DEFAULT 0,
	perfilxml text,
	accesodirecto text,
	caduca date,
	activo numeric(1,0) DEFAULT 1
);

CREATE TABLE "mod_seguridad"."dat_accion_dat_reporte" (
  "idreporte" NUMERIC(10,0) NOT NULL, 
  "idaccion" NUMERIC(19,0) NOT NULL, 
  "denominacion" VARCHAR(255)
);


CREATE TABLE mod_seguridad.seg_usuario_nom_dominio
(
  idusuario numeric(19) NOT NULL,
  iddominio numeric(18) NOT NULL 
);

CREATE TABLE mod_seguridad.seg_compartimentacionusuario
(
  idusuario numeric(19) NOT NULL,
  iddominio numeric(18) NOT NULL  
);

CREATE TABLE mod_seguridad.seg_rol_nom_dominio
(
  idrol numeric(19) NOT NULL,
  iddominio numeric(18) NOT NULL
 );
 
 CREATE TABLE mod_seguridad.seg_compartimentacionroles
(
  idrol numeric(19) NOT NULL,
  iddominio numeric(18) NOT NULL
);

CREATE TABLE mod_seguridad.dat_sistema_compartimentacion
(
  idsistema numeric(19) NOT NULL,
  iddominio numeric(18) NOT NULL
);

CREATE TABLE mod_seguridad.seg_rolesbd
(
  idrolesbd numeric(10) NOT NULL DEFAULT nextval('mod_seguridad.sec_segrolesbd_seq'::regclass),
  idservidor numeric(19) NOT NULL,
  idgestor numeric(19) NOT NULL,
  nombrerol character varying NOT NULL,
  passw character varying NOT NULL,
  CONSTRAINT idrolesbd PRIMARY KEY (idrolesbd)
);

CREATE TABLE mod_seguridad.dat_funcionalidad_compartimentacion
(
  idfuncionalidad numeric(19) NOT NULL,
  iddominio numeric(18) NOT NULL
);

CREATE TABLE mod_seguridad.dat_accion_compartimentacion
(
  idaccion numeric(19) NOT NULL,
  iddominio numeric(18) NOT NULL
);
-------------------------------------------------------------------------------
----Creación de llaves primarias
--------------------------------------------------------------------------------
	SET search_path = mod_seguridad, pg_catalog;
	
ALTER TABLE ONLY dat_accion_compartimentacion
    ADD CONSTRAINT pk_dat_accion_nom_dominio PRIMARY KEY (idaccion, iddominio);
	
ALTER TABLE ONLY dat_funcionalidad_compartimentacion
    ADD CONSTRAINT pk_dat_funcionalidad_nom_dominio PRIMARY KEY (idfuncionalidad, iddominio);
	
ALTER TABLE ONLY dat_sistema_compartimentacion
    ADD CONSTRAINT pk_dat_sistema_nom_dominio PRIMARY KEY (idsistema, iddominio);
	
ALTER TABLE ONLY seg_compartimentacionroles
    ADD CONSTRAINT pk_seg_compartimentacionroles PRIMARY KEY (idrol, iddominio);
	
ALTER TABLE ONLY seg_rol_nom_dominio
    ADD CONSTRAINT pk_seg_rol_nom_dominio PRIMARY KEY (idrol, iddominio);	
	
ALTER TABLE ONLY seg_compartimentacionusuario
    ADD	CONSTRAINT pk_seg_compartimentacionusuario PRIMARY KEY (idusuario, iddominio);
	
ALTER TABLE ONLY seg_usuario_nom_dominio
    ADD CONSTRAINT pk_seg_usuario_nom_dominio PRIMARY KEY (idusuario, iddominio);
		
ALTER TABLE ONLY dat_servicio
    ADD CONSTRAINT "PK12" PRIMARY KEY (idservicio);

ALTER TABLE ONLY dat_servicio_dat_sistema
    ADD CONSTRAINT "PK13" PRIMARY KEY (idsistema, idservicio);

ALTER TABLE ONLY dat_funcionalidad
    ADD CONSTRAINT "PK14" PRIMARY KEY (idfuncionalidad);

ALTER TABLE ONLY dat_accion
    ADD CONSTRAINT "PK15" PRIMARY KEY (idaccion);

ALTER TABLE ONLY seg_rol
    ADD CONSTRAINT "PK16" PRIMARY KEY (idrol);

ALTER TABLE ONLY seg_usuario
    ADD CONSTRAINT "PK18" PRIMARY KEY (idusuario);

ALTER TABLE ONLY seg_claveacceso
    ADD CONSTRAINT "PK23" PRIMARY KEY (pkidclaveacceso);

ALTER TABLE ONLY seg_certificado
    ADD CONSTRAINT "PK24" PRIMARY KEY (idcertificado);

ALTER TABLE ONLY dat_servidor
    ADD CONSTRAINT "PK26" PRIMARY KEY (idservidor);

ALTER TABLE ONLY dat_servidorbd
    ADD CONSTRAINT "PK27" PRIMARY KEY (idservidor);

ALTER TABLE ONLY dat_bd
    ADD CONSTRAINT "PK28" PRIMARY KEY (idbd);

ALTER TABLE ONLY dat_esquema
    ADD CONSTRAINT "PK29" PRIMARY KEY (idesquema);

ALTER TABLE ONLY dat_gestor
    ADD CONSTRAINT "PK30" PRIMARY KEY (idgestor);

ALTER TABLE ONLY dat_gestor_dat_servidorbd
    ADD CONSTRAINT "PK31" PRIMARY KEY (idservidor, idgestor);

ALTER TABLE ONLY dat_serautenticacion
    ADD CONSTRAINT "PK33" PRIMARY KEY (idservidor);

ALTER table mod_seguridad.seg_usuario_dat_serautenticacion
	ADD CONSTRAINT "PK35" PRIMARY KEY("idservidor", "idusuario"); 

ALTER TABLE ONLY nom_campo
    ADD CONSTRAINT "PK38" PRIMARY KEY (idcampo);

ALTER TABLE ONLY nom_fila
    ADD CONSTRAINT "PK45" PRIMARY KEY (idfila);

ALTER TABLE ONLY dat_sistema_dat_servidores
    ADD CONSTRAINT "PK47" PRIMARY KEY (idbd, idsistema, idesquema, idservidor, idgestor, idrolesbd);

ALTER TABLE ONLY nom_expresiones
    ADD CONSTRAINT "PK53" PRIMARY KEY (idexpresiones);

ALTER TABLE ONLY nom_tema
    ADD CONSTRAINT "PK60" PRIMARY KEY (idtema);

ALTER TABLE ONLY nom_idioma
    ADD CONSTRAINT "PK61" PRIMARY KEY (ididioma);

ALTER TABLE ONLY nom_desktop
    ADD CONSTRAINT "PK62" PRIMARY KEY (iddesktop);

ALTER TABLE ONLY dat_parametros
    ADD CONSTRAINT "PK63" PRIMARY KEY (idparametros);

ALTER TABLE ONLY dat_funciones
    ADD CONSTRAINT "PK64" PRIMARY KEY (idfunciones);

ALTER TABLE ONLY nom_valor
    ADD CONSTRAINT "PK65" PRIMARY KEY (idvalor);

ALTER TABLE ONLY seg_restricclaveacceso
    ADD CONSTRAINT "PK7" PRIMARY KEY (idrestricclaveacceso);
/*
ALTER TABLE ONLY dat_sistema_dat_entidad
    ADD CONSTRAINT "PK71" PRIMARY KEY (idsistema, identidad);
*/
/*
ALTER TABLE ONLY dat_sistema_dat_entidad_seg_rol
    ADD CONSTRAINT "PK72" PRIMARY KEY (idrol, idsistema, identidad);
*/

ALTER table mod_seguridad.dat_sistema_seg_rol
	ADD CONSTRAINT "PK72" PRIMARY KEY("idrol", "idsistema"); 

ALTER TABLE ONLY dat_entidad_seg_usuario_seg_rol
    ADD CONSTRAINT "PK74" PRIMARY KEY (idusuario, identidad, idrol);
/*
ALTER TABLE ONLY dat_sistema_dat_entidad_seg_rol_dat_funcionalidad_dat_accion
    ADD CONSTRAINT "PK75" PRIMARY KEY (idaccion, idfuncionalidad, idrol, idsistema, identidad);
*/

ALTER table mod_seguridad.dat_sistema_seg_rol_dat_funcionalidad_dat_accion
	ADD CONSTRAINT "PK75" PRIMARY KEY("idaccion", "idfuncionalidad", "idrol", "idsistema"); 
/*
ALTER TABLE ONLY dat_sistema_dat_entidad_seg_rol_dat_funcionalidad
    ADD CONSTRAINT "PK76" PRIMARY KEY (idfuncionalidad, idrol, idsistema, identidad);
*/

ALTER table mod_seguridad.dat_sistema_seg_rol_dat_funcionalidad
	ADD CONSTRAINT "PK76" PRIMARY KEY("idfuncionalidad", "idrol", "idsistema");

ALTER TABLE ONLY dat_sistema
    ADD CONSTRAINT "PK9" PRIMARY KEY (idsistema);
	
ALTER TABLE ONLY dat_accion_dat_reporte
	ADD CONSTRAINT "PK77" PRIMARY KEY("idreporte", "idaccion");
-------------------------------------------------------------------------------
----Creación de otras restricciones de datos
--------------------------------------------------------------------------------
	SET search_path = mod_seguridad, pg_catalog;
	
	
-------------------------------------------------------------------------------
----Creación de indices
--------------------------------------------------------------------------------
	SET search_path = mod_seguridad, pg_catalog;
	
	
-------------------------------------------------------------------------------
----Creación de tipos de datos
--------------------------------------------------------------------------------
	SET search_path = mod_seguridad, pg_catalog;
	
	
-------------------------------------------------------------------------------
----Creación de llaves foraneas
--------------------------------------------------------------------------------
	SET search_path = mod_seguridad, pg_catalog;
	
ALTER TABLE ONLY dat_accion_compartimentacion
    ADD CONSTRAINT fk_dat_accion_idaccion FOREIGN KEY (idaccion)
      REFERENCES mod_seguridad.dat_accion (idaccion) MATCH SIMPLE
      ON UPDATE CASCADE ON DELETE CASCADE;
	
ALTER TABLE ONLY dat_funcionalidad_compartimentacion
    ADD CONSTRAINT fk_dat_funcionalidad_idfuncionalidad FOREIGN KEY (idfuncionalidad)
      REFERENCES mod_seguridad.dat_funcionalidad (idfuncionalidad) MATCH SIMPLE
      ON UPDATE CASCADE ON DELETE CASCADE;
	
ALTER TABLE ONLY dat_sistema_compartimentacion
    ADD CONSTRAINT fk_dat_sistema_idsistema FOREIGN KEY (idsistema)
      REFERENCES mod_seguridad.dat_sistema (idsistema) MATCH SIMPLE
      ON UPDATE CASCADE ON DELETE CASCADE;
	
ALTER TABLE ONLY seg_rol_nom_dominio
    ADD CONSTRAINT fk_seg_rol_idrol FOREIGN KEY (idrol)
      REFERENCES mod_seguridad.seg_rol (idrol) MATCH SIMPLE
      ON UPDATE CASCADE ON DELETE CASCADE;
	
ALTER TABLE ONLY seg_compartimentacionroles
    ADD CONSTRAINT fk_seg_compartimentacionroles FOREIGN KEY (idrol)
      REFERENCES mod_seguridad.seg_rol (idrol) MATCH SIMPLE
      ON UPDATE CASCADE ON DELETE CASCADE;
	
ALTER TABLE ONLY seg_usuario_nom_dominio
    ADD CONSTRAINT fk_seg_usuario_idusuario FOREIGN KEY (idusuario)
      REFERENCES mod_seguridad.seg_usuario (idusuario) MATCH SIMPLE
      ON UPDATE CASCADE ON DELETE CASCADE;
	
ALTER TABLE ONLY seg_compartimentacionusuario
    ADD CONSTRAINT fk_seg_compartimentacionusuario FOREIGN KEY (idusuario)
      REFERENCES mod_seguridad.seg_usuario (idusuario) MATCH SIMPLE
      ON UPDATE CASCADE ON DELETE CASCADE;
	
ALTER TABLE ONLY dat_sistema_seg_rol_dat_funcionalidad_dat_accion
    ADD CONSTRAINT "Refdat_accion213" FOREIGN KEY (idaccion) REFERENCES dat_accion(idaccion) ON UPDATE CASCADE ON DELETE CASCADE;

ALTER table mod_seguridad.dat_sistema_seg_rol_dat_funcionalidad_dat_accion
	ADD CONSTRAINT "Refdat_sistema_seg_rol_dat_funcionalidad216" FOREIGN KEY ("idfuncionalidad", "idrol", "idsistema")
    REFERENCES "mod_seguridad"."dat_sistema_seg_rol_dat_funcionalidad"("idfuncionalidad", "idrol", "idsistema")
    ON DELETE CASCADE
    ON UPDATE CASCADE;	
	
ALTER TABLE ONLY dat_sistema_dat_servidores
    ADD CONSTRAINT "Refdat_bd144" FOREIGN KEY (idbd)
      REFERENCES mod_seguridad.dat_bd (idbd) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE NO ACTION;
/*
ALTER TABLE ONLY dat_servidor_dat_gestor_dat_bd
    ADD CONSTRAINT "Refdat_bd154" FOREIGN KEY (idbd) REFERENCES dat_bd(idbd) ON UPDATE CASCADE ON DELETE CASCADE;
*/
ALTER TABLE ONLY dat_sistema_dat_servidores
    ADD CONSTRAINT "Refdat_esquema156" FOREIGN KEY (idesquema)
      REFERENCES mod_seguridad.dat_esquema (idesquema) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE NO ACTION;
/*
ALTER TABLE ONLY dat_servidor_dat_gestor_dat_bd_dat_esquema
    ADD CONSTRAINT "Refdat_esquema158" FOREIGN KEY (idesquema) REFERENCES dat_esquema(idesquema) ON UPDATE CASCADE ON DELETE CASCADE;
*/
ALTER TABLE ONLY dat_sistema_seg_rol_dat_funcionalidad
    ADD CONSTRAINT "Refdat_funcionalidad214" FOREIGN KEY (idfuncionalidad) REFERENCES dat_funcionalidad(idfuncionalidad) ON UPDATE CASCADE ON DELETE CASCADE;

ALTER TABLE mod_seguridad.dat_sistema_seg_rol_dat_funcionalidad
	ADD CONSTRAINT "Refdat_sistema_seg_rol215" FOREIGN KEY ("idrol", "idsistema")
    REFERENCES "mod_seguridad"."dat_sistema_seg_rol"("idrol", "idsistema")
    ON DELETE CASCADE
    ON UPDATE CASCADE;	

ALTER TABLE ONLY dat_accion
    ADD CONSTRAINT "Refdat_funcionalidad45" FOREIGN KEY (idfuncionalidad) REFERENCES dat_funcionalidad(idfuncionalidad) ON UPDATE CASCADE ON DELETE CASCADE;

ALTER TABLE ONLY dat_parametros
    ADD CONSTRAINT "Refdat_funciones190" FOREIGN KEY (idfunciones) REFERENCES dat_funciones(idfunciones) ON UPDATE CASCADE ON DELETE CASCADE;

ALTER TABLE ONLY dat_sistema_dat_servidores
    ADD CONSTRAINT "Refdat_gestor160" FOREIGN KEY (idgestor)
      REFERENCES mod_seguridad.dat_gestor (idgestor) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE NO ACTION;

ALTER TABLE ONLY dat_gestor_dat_servidorbd
    ADD CONSTRAINT "Refdat_gestor68" FOREIGN KEY (idgestor) REFERENCES dat_gestor(idgestor) ON UPDATE CASCADE ON DELETE CASCADE;
/*
ALTER TABLE ONLY dat_servidor_dat_gestor_dat_bd
    ADD CONSTRAINT "Refdat_gestor_dat_servidorbd161" FOREIGN KEY (idservidor, idgestor) REFERENCES dat_gestor_dat_servidorbd(idservidor, idgestor) ON UPDATE CASCADE ON DELETE CASCADE;
*/
ALTER TABLE ONLY seg_usuario_dat_serautenticacion
    ADD CONSTRAINT "Refdat_serautenticacion64" FOREIGN KEY (idservidor) REFERENCES dat_serautenticacion(idservidor) ON UPDATE CASCADE ON DELETE CASCADE;

ALTER table mod_seguridad.seg_usuario_dat_serautenticacion
	ADD CONSTRAINT "Refseg_usuario218" FOREIGN KEY ("idusuario")
    REFERENCES "mod_seguridad"."seg_usuario"("idusuario")
    ON DELETE CASCADE
    ON UPDATE CASCADE; 	
	
ALTER TABLE ONLY dat_funciones
    ADD CONSTRAINT "Refdat_servicio189" FOREIGN KEY (idservicio) REFERENCES dat_servicio(idservicio) ON UPDATE CASCADE ON DELETE CASCADE;

ALTER TABLE ONLY dat_servicio_dat_sistema
    ADD CONSTRAINT "Refdat_servicio44" FOREIGN KEY (idservicio) REFERENCES dat_servicio(idservicio) ON DELETE CASCADE;

ALTER TABLE ONLY dat_sistema_dat_servidores
    ADD CONSTRAINT "Refdat_servidor159" FOREIGN KEY (idservidor)
      REFERENCES mod_seguridad.dat_servidor (idservidor) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE NO ACTION;

ALTER TABLE ONLY dat_servidorbd
    ADD CONSTRAINT "Refdat_servidor33" FOREIGN KEY (idservidor) REFERENCES dat_servidor(idservidor) ON UPDATE CASCADE ON DELETE CASCADE;

ALTER TABLE ONLY dat_serautenticacion
    ADD CONSTRAINT "Refdat_servidor40" FOREIGN KEY (idservidor) REFERENCES dat_servidor(idservidor) ON UPDATE CASCADE ON DELETE CASCADE;
/*
ALTER TABLE ONLY dat_servidor_dat_gestor_dat_bd_dat_esquema
    ADD CONSTRAINT "Refdat_servidor_dat_gestor_dat_bd162" FOREIGN KEY (idbd, idservidor, idgestor) REFERENCES dat_servidor_dat_gestor_dat_bd(idbd, idservidor, idgestor) ON UPDATE CASCADE ON DELETE CASCADE;
*/
ALTER TABLE ONLY dat_gestor_dat_servidorbd
    ADD CONSTRAINT "Refdat_servidorbd67" FOREIGN KEY (idservidor) REFERENCES dat_servidorbd(idservidor) ON UPDATE CASCADE ON DELETE CASCADE;

ALTER TABLE ONLY dat_funcionalidad
    ADD CONSTRAINT "Refdat_sistema12" FOREIGN KEY (idsistema) REFERENCES dat_sistema(idsistema) ON UPDATE CASCADE ON DELETE CASCADE;

ALTER TABLE ONLY dat_sistema_dat_servidores
    ADD CONSTRAINT "Refdat_sistema145" FOREIGN KEY (idsistema)
      REFERENCES mod_seguridad.dat_sistema (idsistema) MATCH SIMPLE
      ON UPDATE CASCADE ON DELETE CASCADE;

ALTER TABLE ONLY dat_sistema_dat_servidores
    ADD CONSTRAINT "Refseg_rolesbd" FOREIGN KEY (idrolesbd)
      REFERENCES mod_seguridad.seg_rolesbd (idrolesbd) MATCH SIMPLE
      ON UPDATE CASCADE ON DELETE CASCADE;	  
	  /*
ALTER TABLE ONLY dat_sistema_dat_entidad
    ADD CONSTRAINT "Refdat_sistema203" FOREIGN KEY (idsistema) REFERENCES dat_sistema(idsistema) ON UPDATE CASCADE ON DELETE CASCADE;
*/
ALTER TABLE ONLY dat_servicio_dat_sistema
    ADD CONSTRAINT "Refdat_sistema43" FOREIGN KEY (idsistema) REFERENCES dat_sistema(idsistema) ON UPDATE CASCADE ON DELETE CASCADE;

ALTER TABLE ONLY dat_servicio
    ADD CONSTRAINT "Refdat_sistema6" FOREIGN KEY (idsistema) REFERENCES dat_sistema(idsistema) ON UPDATE CASCADE ON DELETE CASCADE;
/*
ALTER TABLE ONLY dat_sistema_dat_serautenticacion
    ADD CONSTRAINT "Refdat_sistema65" FOREIGN KEY (idsistema) REFERENCES dat_sistema(idsistema) ON UPDATE CASCADE ON DELETE CASCADE;
*/
	/*
ALTER TABLE ONLY dat_sistema_dat_entidad_seg_rol
    ADD CONSTRAINT "Refdat_sistema_dat_entidad206" FOREIGN KEY (idsistema, identidad) REFERENCES dat_sistema_dat_entidad(idsistema, identidad) ON UPDATE CASCADE ON DELETE CASCADE;

ALTER TABLE ONLY dat_sistema_dat_entidad_seg_rol_dat_funcionalidad
    ADD CONSTRAINT "Refdat_sistema_dat_entidad_seg_rol215" FOREIGN KEY (idrol, idsistema, identidad) REFERENCES dat_sistema_dat_entidad_seg_rol(idrol, idsistema, identidad) ON UPDATE CASCADE ON DELETE CASCADE;

ALTER TABLE ONLY dat_sistema_dat_entidad_seg_rol_dat_funcionalidad_dat_accion
    ADD CONSTRAINT "Refdat_sistema_dat_entidad_seg_rol_dat_funcionalidad216" FOREIGN KEY (idfuncionalidad, idrol, idsistema, identidad) REFERENCES dat_sistema_dat_entidad_seg_rol_dat_funcionalidad(idfuncionalidad, idrol, idsistema, identidad) ON UPDATE CASCADE ON DELETE CASCADE;
*/
ALTER TABLE ONLY nom_valor
    ADD CONSTRAINT "Refnom_campo194" FOREIGN KEY (idcampo) REFERENCES nom_campo(idcampo) ON UPDATE CASCADE ON DELETE CASCADE;

ALTER TABLE ONLY seg_usuario
    ADD CONSTRAINT "Refnom_desktop185" FOREIGN KEY (iddesktop) REFERENCES nom_desktop(iddesktop) ON UPDATE CASCADE ON DELETE CASCADE;

ALTER TABLE ONLY nom_campo
    ADD CONSTRAINT "Refnom_expresiones163" FOREIGN KEY (idexpresiones) REFERENCES nom_expresiones(idexpresiones) ON UPDATE CASCADE ON DELETE CASCADE;

ALTER TABLE ONLY nom_valor
    ADD CONSTRAINT "Refnom_fila195" FOREIGN KEY (idfila) REFERENCES nom_fila(idfila) ON UPDATE CASCADE ON DELETE CASCADE;

ALTER TABLE ONLY seg_usuario
    ADD CONSTRAINT "Refnom_idioma187" FOREIGN KEY (ididioma) REFERENCES nom_idioma(ididioma) ON UPDATE CASCADE ON DELETE CASCADE;

ALTER TABLE ONLY seg_usuario
    ADD CONSTRAINT "Refnom_tema186" FOREIGN KEY (idtema) REFERENCES nom_tema(idtema) ON UPDATE CASCADE ON DELETE CASCADE;

ALTER TABLE ONLY dat_sistema_seg_rol
    ADD CONSTRAINT "Refseg_rol205" FOREIGN KEY (idrol) REFERENCES seg_rol(idrol) ON UPDATE CASCADE ON DELETE CASCADE;

ALTER table mod_seguridad.dat_sistema_seg_rol
	ADD CONSTRAINT "Refdat_sistema_dat_entidad206" FOREIGN KEY ("idsistema")
    REFERENCES "mod_seguridad"."dat_sistema"("idsistema")
    ON DELETE CASCADE
    ON UPDATE CASCADE; 	
	
ALTER TABLE ONLY dat_entidad_seg_usuario_seg_rol
    ADD CONSTRAINT "Refseg_rol208" FOREIGN KEY (idrol) REFERENCES seg_rol(idrol) ON UPDATE CASCADE ON DELETE CASCADE;

ALTER TABLE ONLY nom_fila
    ADD CONSTRAINT "Refseg_usuario141" FOREIGN KEY (idusuario) REFERENCES seg_usuario(idusuario) ON UPDATE CASCADE ON DELETE CASCADE;

ALTER TABLE ONLY dat_entidad_seg_usuario_seg_rol
    ADD CONSTRAINT "Refseg_usuario207" FOREIGN KEY (idusuario) REFERENCES seg_usuario(idusuario) ON UPDATE CASCADE ON DELETE CASCADE;

ALTER TABLE ONLY seg_certificado
    ADD CONSTRAINT "Refseg_usuario62" FOREIGN KEY (idusuario) REFERENCES seg_usuario(idusuario) ON UPDATE CASCADE ON DELETE CASCADE;

ALTER TABLE ONLY seg_claveacceso
    ADD CONSTRAINT "Refseg_usuario63" FOREIGN KEY (idusuario) REFERENCES seg_usuario(idusuario) ON UPDATE CASCADE ON DELETE CASCADE;

ALTER TABLE ONLY dat_accion_dat_reporte
 ADD CONSTRAINT "Refdat_accion219" FOREIGN KEY ("idaccion") REFERENCES "mod_seguridad"."dat_accion"("idaccion") ON DELETE CASCADE ON UPDATE CASCADE NOT DEFERRABLE ;
-------------------------------------------------------------------------------
----Creación de funciones
--------------------------------------------------------------------------------
	SET search_path = mod_seguridad, pg_catalog;
	

CREATE OR REPLACE FUNCTION "mod_seguridad"."ft_actualizacion_idsistema" () RETURNS trigger AS
$body$
BEGIN
if (new.idpadre is null ) then
    new.idpadre = new.idsistema;
end if;
RETURN new;
END;
$body$
LANGUAGE 'plpgsql' VOLATILE CALLED ON NULL INPUT SECURITY INVOKER;


CREATE OR REPLACE FUNCTION mod_seguridad.ft_eliminar_dat_sistema()
  RETURNS trigger AS
$BODY$
DECLARE
       ancho BIGINT;
BEGIN
     SET search_path = mod_seguridad;
     ancho := OLD.rgt - OLD.lft + 1;
     UPDATE dat_sistema SET rgt = rgt - ancho WHERE rgt > OLD.rgt;
     UPDATE dat_sistema SET lft = lft - ancho WHERE lft > OLD.rgt;
     RETURN OLD;
     EXCEPTION WHEN foreign_key_violation THEN
         RETURN OLD;
END;
$BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
  
  
CREATE OR REPLACE FUNCTION mod_seguridad.ft_insertar_dat_sistema()
  RETURNS trigger AS
$BODY$
DECLARE
        derecha bigint;
BEGIN
	SET search_path = mod_seguridad;
	IF NEW.idsistema != NEW.idpadre THEN
       derecha :=  rgt FROM dat_sistema WHERE idsistema = NEW.idpadre;
       UPDATE dat_sistema SET rgt = rgt + 2 WHERE rgt >= derecha;
       UPDATE dat_sistema SET lft = lft + 2 WHERE lft > derecha;
       NEW.lft := derecha;
       NEW.rgt := derecha + 1;
    ELSE
        derecha :=  MAX(rgt) FROM dat_sistema WHERE idsistema = idpadre;
        IF NOT nullvalue(derecha) THEN
           NEW.lft := derecha + 1;
           NEW.rgt := derecha + 2;
        ELSE
            NEW.lft := 1;
            NEW.rgt := 2;
        END IF;
    END IF;
	RETURN NEW;
END;
$BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
-------------------------------------------------------------------------------
----Creación de triggers
--------------------------------------------------------------------------------
	SET search_path = mod_seguridad, pg_catalog;

CREATE TRIGGER t_insertar_dat_sistema
  BEFORE INSERT
  ON mod_seguridad.dat_sistema
  FOR EACH ROW
  EXECUTE PROCEDURE mod_seguridad.ft_insertar_dat_sistema();	
	
CREATE TRIGGER t_eliminar_dat_sistema
  AFTER DELETE
  ON mod_seguridad.dat_sistema
  FOR EACH ROW
  EXECUTE PROCEDURE mod_seguridad.ft_eliminar_dat_sistema();
	
CREATE TRIGGER "t_actualiza_idsistemapadre" BEFORE INSERT 
ON "mod_seguridad"."dat_sistema" FOR EACH ROW 
EXECUTE PROCEDURE "mod_seguridad"."ft_actualizacion_idsistema"();

-------------------------------------------------------------------------------
----actualizaciones de secuencias
--------------------------------------------------------------------------------
	SET search_path = mod_seguridad, pg_catalog;

SELECT setval('mod_seguridad.sec_datsistema_seq', 88, true);
SELECT setval('mod_seguridad.sec_datfuncionalidad_seq', 202, true);
SELECT setval('mod_seguridad.sec_dataccion_seq', 602, true);



	
--fin de la tranzaccion--
COMMIT;                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            