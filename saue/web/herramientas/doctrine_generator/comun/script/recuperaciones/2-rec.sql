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
VALUES ('V5.0.0', 'V5.0.0','mod_recuperaciones',null,'C','E','I');


	CREATE SCHEMA mod_recuperaciones;
------------------------------------------------------------------------------------------------
--Estructura del Esquema Recuperaciones---------------------------------------------------------
------------------------------------------------------------------------------------------------
	SET search_path = mod_recuperaciones, pg_catalog;
	
	
-------------------------------------------------------------------------------
----Creación de secuencias
--------------------------------------------------------------------------------
	SET search_path = mod_recuperaciones, pg_catalog;
	
CREATE SEQUENCE ncss_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;

CREATE SEQUENCE nformat_seq
    START WITH 4
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;

CREATE SEQUENCE noptionrw_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;

CREATE SEQUENCE nrol_seq
    START WITH 4
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;

CREATE SEQUENCE nscheduletype_seq
    START WITH 6
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;

CREATE SEQUENCE nsubscriptiontype_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;

CREATE SEQUENCE ntask_seq
    START WITH 11
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;

CREATE SEQUENCE tbcategory_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;

CREATE SEQUENCE tbconcurrency_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;

CREATE SEQUENCE tbdatasource_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;

CREATE SEQUENCE tbmodel_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;

CREATE SEQUENCE tbreport_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;

CREATE SEQUENCE tbschedule_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;

CREATE SEQUENCE tbsqlerror_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;

CREATE SEQUENCE tbsqlfunction_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;

CREATE SEQUENCE tbsubscription_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;

CREATE SEQUENCE tbtemplate_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;

CREATE SEQUENCE tbuser_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;

CREATE SEQUENCE tbversion_seq
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;

CREATE SEQUENCE ntargetdb_seq
    START WITH 3
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;

-------------------------------------------------------------------------------
----Creación de tablas
--------------------------------------------------------------------------------ç
	SET search_path = mod_recuperaciones, pg_catalog;
	

CREATE TABLE ncss (
    idcss integer DEFAULT nextval('ncss_seq'::regclass) NOT NULL,
    css text NOT NULL,
    cssname character varying(50) NOT NULL
);

CREATE TABLE nformat (
    idformat integer DEFAULT nextval('nformat_seq'::regclass) NOT NULL,
    format character varying(20) NOT NULL
);

CREATE TABLE noptionrw (
    idoptionrw integer DEFAULT nextval('noptionrw_seq'::regclass) NOT NULL,
    optionrw text NOT NULL
);

CREATE TABLE nrol (
    idrol integer DEFAULT nextval('nrol_seq'::regclass) NOT NULL,
    rol character varying(50) NOT NULL,
    description text NOT NULL
);

CREATE TABLE nscheduletype (
    idscheduletype integer DEFAULT nextval('nscheduletype_seq'::regclass) NOT NULL,
    scheduletype character varying(50) NOT NULL
);

CREATE TABLE nsubscriptiontype (
    idsubscriptiontype integer DEFAULT nextval('nsubscriptiontype_seq'::regclass) NOT NULL,
    subscriptiontype character varying(100) NOT NULL
);

CREATE TABLE ntargetdb (
    idtargetdb integer DEFAULT nextval('ntargetdb_seq'::regclass) NOT NULL,
    targetdb character varying(50) NOT NULL
);

CREATE TABLE ntask (
    idtask integer DEFAULT nextval('ntask_seq'::regclass) NOT NULL,
    task character varying(250) NOT NULL,
    description text NOT NULL
);

CREATE TABLE tbcategory (
    idcategory integer DEFAULT nextval('tbcategory_seq'::regclass) NOT NULL,
    description text NOT NULL,
    container integer NOT NULL,
    category character varying(50) NOT NULL
);

CREATE TABLE tbcategoryreport (
    idcategory integer NOT NULL,
    idreport integer NOT NULL
);

CREATE TABLE tbconcurrency (
    idconcurrency integer DEFAULT nextval('tbconcurrency_seq'::regclass) NOT NULL,
    idschedule integer NOT NULL
);

CREATE TABLE tbdatasource (
    iddatasource integer DEFAULT nextval('tbdatasource_seq'::regclass) NOT NULL,
    name character varying(250) NOT NULL,
    db character varying(50) NOT NULL,
    port integer NOT NULL,
    host character varying(50) NOT NULL,
    password character varying(50) NOT NULL,
    username character varying(50) NOT NULL,
    idtargetdb integer NOT NULL
);

CREATE TABLE tbmodel (
    idmodel integer DEFAULT nextval('tbmodel_seq'::regclass) NOT NULL,
    xmlmodel text NOT NULL,
    name character varying(250) NOT NULL,
    iddatasource integer NOT NULL
);

CREATE TABLE tbreport (
    idreport integer DEFAULT nextval('tbreport_seq'::regclass) NOT NULL,
    template text NOT NULL,
    modifiable boolean NOT NULL,
    query text NOT NULL,
    excecutiontime timestamp without time zone,
    parentid integer NOT NULL,
    xmlreport text NOT NULL,
    title character varying(250) NOT NULL,
    image bytea,
    created_on timestamp without time zone NOT NULL,
    updated_on timestamp without time zone NOT NULL,
    modifiedbyid integer NOT NULL,
    createbyid integer NOT NULL
);

CREATE TABLE tbreportdatasource (
    idreport integer NOT NULL,
    iddatasource integer NOT NULL
);

CREATE TABLE tbreportmodel (
    idreport integer NOT NULL,
    idmodel integer NOT NULL
);

CREATE TABLE tbrolcategory (
    idcategory integer NOT NULL,
    idrol integer NOT NULL
);

CREATE TABLE tbrolreport (
    idrol integer NOT NULL,
    idreport integer NOT NULL
);

CREATE TABLE tbroltask (
    idrol integer NOT NULL,
    idtask integer NOT NULL
);

CREATE TABLE tbschedule (
    idschedule integer DEFAULT nextval('tbschedule_seq'::regclass) NOT NULL,
    lastruntime character varying(20) NOT NULL,
    weeksinterval integer,
    nextruntime character varying(20) NOT NULL,
    dayofweek integer,
    month integer,
    monthly integer,
    minutesinterval integer,
    enddate character varying(20) NOT NULL,
    idscheduletype integer NOT NULL,
    idsubscription integer NOT NULL
);

CREATE TABLE tbsqlerror (
    idsqlerror integer DEFAULT nextval('tbsqlerror_seq'::regclass) NOT NULL,
    error text NOT NULL,
    description text NOT NULL,
    solution text NOT NULL
);

CREATE TABLE tbsqlfunction (
    idsqlfunction integer DEFAULT nextval('tbsqlfunction_seq'::regclass) NOT NULL,
    idtargetdb integer NOT NULL,
    function character varying(50) NOT NULL,
    description text NOT NULL
);

CREATE TABLE tbsubscription (
    idsubscription integer DEFAULT nextval('tbsubscription_seq'::regclass) NOT NULL,
    xmlmail text,
    username character varying(250),
    pass character varying(250),
    path text,
    subscriptionname character varying(250) NOT NULL,
    idreport integer NOT NULL,
    iduser integer NOT NULL,
    idformat integer NOT NULL,
    idsubscriptiontype integer NOT NULL,
    idoptionrw integer
);

CREATE TABLE tbtemplate (
    idtemplate integer DEFAULT nextval('tbtemplate_seq'::regclass) NOT NULL,
    xmltemplate text NOT NULL,
    name character varying(250) NOT NULL,
    createid integer NOT NULL,
    modifiedid integer NOT NULL,
    updated_on timestamp without time zone NOT NULL,
    created_on timestamp without time zone NOT NULL
);

CREATE TABLE tbtemplatecategory (
    idtemplate integer NOT NULL,
    idcategory integer NOT NULL
);

CREATE TABLE tbtemplatecss (
    idtemplate integer NOT NULL,
    idcss integer NOT NULL
);

CREATE TABLE tbuser (
    iduser integer DEFAULT nextval('tbuser_seq'::regclass) NOT NULL,
    username character varying(50) NOT NULL,
    password text NOT NULL
);

CREATE TABLE tbusercategory (
    iduser integer NOT NULL,
    idcategory integer NOT NULL
);

CREATE TABLE tbuserreport (
    idreport integer NOT NULL,
    iduser integer NOT NULL
);

CREATE TABLE tbuserrol (
    iduser integer NOT NULL,
    idrol integer NOT NULL
);

CREATE TABLE tbversion (
    idversion integer DEFAULT nextval('tbversion_seq'::regclass) NOT NULL,
    versionfield text NOT NULL,
    versiondate date NOT NULL
);


-------------------------------------------------------------------------------
----Creación de llaves primarias
--------------------------------------------------------------------------------
	SET search_path = mod_recuperaciones, pg_catalog;
	

ALTER TABLE ONLY tbversion
    ADD CONSTRAINT pk_idversion PRIMARY KEY (idversion);

ALTER TABLE ONLY ncss
    ADD CONSTRAINT pk_ncss PRIMARY KEY (idcss);

ALTER TABLE ONLY nformat
    ADD CONSTRAINT pk_nformat PRIMARY KEY (idformat);

ALTER TABLE ONLY noptionrw
    ADD CONSTRAINT pk_noptionrw PRIMARY KEY (idoptionrw);

ALTER TABLE ONLY nrol
    ADD CONSTRAINT pk_nrol PRIMARY KEY (idrol);

ALTER TABLE ONLY nscheduletype
    ADD CONSTRAINT pk_nscheduletype PRIMARY KEY (idscheduletype);

ALTER TABLE ONLY nsubscriptiontype
    ADD CONSTRAINT pk_nsubscriptiontype PRIMARY KEY (idsubscriptiontype);

ALTER TABLE ONLY ntargetdb
    ADD CONSTRAINT pk_ntargetdb PRIMARY KEY (idtargetdb);

ALTER TABLE ONLY ntask
    ADD CONSTRAINT pk_ntask PRIMARY KEY (idtask);

ALTER TABLE ONLY tbcategory
    ADD CONSTRAINT pk_tbcategory PRIMARY KEY (idcategory);

ALTER TABLE ONLY tbcategoryreport
    ADD CONSTRAINT pk_tbcategoryreport PRIMARY KEY (idcategory, idreport);

ALTER TABLE ONLY tbconcurrency
    ADD CONSTRAINT pk_tbconcurrency PRIMARY KEY (idconcurrency);

ALTER TABLE ONLY tbdatasource
    ADD CONSTRAINT pk_tbdatasource PRIMARY KEY (iddatasource);

ALTER TABLE ONLY tbmodel
    ADD CONSTRAINT pk_tbmodel PRIMARY KEY (idmodel);

ALTER TABLE ONLY tbreport
    ADD CONSTRAINT pk_tbreport PRIMARY KEY (idreport);

ALTER TABLE ONLY tbreportdatasource
    ADD CONSTRAINT pk_tbreportdatasource PRIMARY KEY (idreport, iddatasource);

ALTER TABLE ONLY tbrolcategory
    ADD CONSTRAINT pk_tbrolcategory PRIMARY KEY (idcategory, idrol);

ALTER TABLE ONLY tbrolreport
    ADD CONSTRAINT pk_tbrolreport PRIMARY KEY (idrol, idreport);

ALTER TABLE ONLY tbroltask
    ADD CONSTRAINT pk_tbroltask PRIMARY KEY (idrol, idtask);

ALTER TABLE ONLY tbschedule
    ADD CONSTRAINT pk_tbschedule PRIMARY KEY (idschedule);

ALTER TABLE ONLY tbsqlerror
    ADD CONSTRAINT pk_tbsqlerror PRIMARY KEY (idsqlerror);

ALTER TABLE ONLY tbsqlfunction
    ADD CONSTRAINT pk_tbsqlfunction PRIMARY KEY (idsqlfunction);

ALTER TABLE ONLY tbsubscription
    ADD CONSTRAINT pk_tbsubscription PRIMARY KEY (idsubscription);

ALTER TABLE ONLY tbtemplate
    ADD CONSTRAINT pk_tbtemplate PRIMARY KEY (idtemplate);

ALTER TABLE ONLY tbtemplatecategory
    ADD CONSTRAINT pk_tbtemplatecategory PRIMARY KEY (idtemplate, idcategory);

ALTER TABLE ONLY tbtemplatecss
    ADD CONSTRAINT pk_tbtemplatecss PRIMARY KEY (idtemplate, idcss);

ALTER TABLE ONLY tbuser
    ADD CONSTRAINT pk_tbuser PRIMARY KEY (iduser);

ALTER TABLE ONLY tbusercategory
    ADD CONSTRAINT pk_tbusercategory PRIMARY KEY (iduser, idcategory);

ALTER TABLE ONLY tbuserreport
    ADD CONSTRAINT pk_tbuserreport PRIMARY KEY (idreport, iduser);

ALTER TABLE ONLY tbuserrol
    ADD CONSTRAINT pk_tbuserrol PRIMARY KEY (iduser, idrol);


ALTER TABLE ONLY tbreportmodel
    ADD CONSTRAINT pkidreport PRIMARY KEY (idreport, idmodel);

-------------------------------------------------------------------------------
----Creación de otras restricciones de datos
--------------------------------------------------------------------------------
	SET search_path = mod_recuperaciones, pg_catalog;
	
ALTER TABLE ONLY tbdatasource
    ADD CONSTRAINT dsname UNIQUE (name);

ALTER TABLE ONLY tbmodel
    ADD CONSTRAINT modelname UNIQUE (name);

ALTER TABLE ONLY tbtemplate
    ADD CONSTRAINT templatename UNIQUE (name);

ALTER TABLE ONLY tbreport
    ADD CONSTRAINT title UNIQUE (title);	
-------------------------------------------------------------------------------
----Creación de indices
--------------------------------------------------------------------------------
	SET search_path = mod_recuperaciones, pg_catalog;
	

-------------------------------------------------------------------------------
----Creación de tipos de datos
--------------------------------------------------------------------------------
	SET search_path = mod_recuperaciones, pg_catalog;
	

-------------------------------------------------------------------------------
----Creación de llaves foraneas
--------------------------------------------------------------------------------
	SET search_path = mod_recuperaciones, pg_catalog;
	
	
ALTER TABLE ONLY tbreportmodel
    ADD CONSTRAINT modelreport FOREIGN KEY (idmodel) REFERENCES tbmodel(idmodel) ON DELETE CASCADE;

ALTER TABLE ONLY tbreportmodel
    ADD CONSTRAINT reportmodel FOREIGN KEY (idreport) REFERENCES tbreport(idreport) ON DELETE CASCADE;

ALTER TABLE ONLY tbcategoryreport
    ADD CONSTRAINT tbcategoryreporttbcategory FOREIGN KEY (idcategory) REFERENCES tbcategory(idcategory) ON UPDATE CASCADE ON DELETE CASCADE;

ALTER TABLE ONLY tbcategoryreport
    ADD CONSTRAINT tbcategoryreporttbreport FOREIGN KEY (idreport) REFERENCES tbreport(idreport) ON UPDATE CASCADE ON DELETE CASCADE;

ALTER TABLE ONLY tbcategory
    ADD CONSTRAINT tbcategorytbcategory FOREIGN KEY (container) REFERENCES tbcategory(idcategory) ON UPDATE CASCADE ON DELETE CASCADE;

ALTER TABLE ONLY tbconcurrency
    ADD CONSTRAINT tbconcurrencytbschedule FOREIGN KEY (idschedule) REFERENCES tbschedule(idschedule) ON UPDATE CASCADE ON DELETE CASCADE;

ALTER TABLE ONLY tbdatasource
    ADD CONSTRAINT tbdatasourcentargetdb FOREIGN KEY (idtargetdb) REFERENCES ntargetdb(idtargetdb) ON UPDATE CASCADE ON DELETE CASCADE;

ALTER TABLE ONLY tbmodel
    ADD CONSTRAINT tbmodeltbdatasource FOREIGN KEY (iddatasource) REFERENCES tbdatasource(iddatasource) ON UPDATE CASCADE ON DELETE CASCADE;

ALTER TABLE ONLY tbreportdatasource
    ADD CONSTRAINT tbreportdatasourcetbdatasource FOREIGN KEY (iddatasource) REFERENCES tbdatasource(iddatasource) ON UPDATE CASCADE ON DELETE CASCADE;

ALTER TABLE ONLY tbreportdatasource
    ADD CONSTRAINT tbreportdatasourcetbreport FOREIGN KEY (idreport) REFERENCES tbreport(idreport) ON UPDATE CASCADE ON DELETE CASCADE;

ALTER TABLE ONLY tbreport
    ADD CONSTRAINT tbreporttbreport FOREIGN KEY (parentid) REFERENCES tbreport(idreport) ON UPDATE CASCADE ON DELETE CASCADE;

ALTER TABLE ONLY tbreport
    ADD CONSTRAINT tbreporttbus6 FOREIGN KEY (createbyid) REFERENCES tbuser(iduser) ON UPDATE CASCADE ON DELETE CASCADE;

ALTER TABLE ONLY tbreport
    ADD CONSTRAINT tbreporttbuser FOREIGN KEY (modifiedbyid) REFERENCES tbuser(iduser) ON UPDATE CASCADE ON DELETE CASCADE;

ALTER TABLE ONLY tbrolcategory
    ADD CONSTRAINT tbrolcategorynrol FOREIGN KEY (idrol) REFERENCES nrol(idrol) ON UPDATE CASCADE ON DELETE CASCADE;

ALTER TABLE ONLY tbrolcategory
    ADD CONSTRAINT tbrolcategorytbcategory FOREIGN KEY (idcategory) REFERENCES tbcategory(idcategory) ON UPDATE CASCADE ON DELETE CASCADE;

ALTER TABLE ONLY tbrolreport
    ADD CONSTRAINT tbrolreportnrol FOREIGN KEY (idrol) REFERENCES nrol(idrol) ON UPDATE CASCADE ON DELETE CASCADE;

ALTER TABLE ONLY tbrolreport
    ADD CONSTRAINT tbrolreporttbreport FOREIGN KEY (idreport) REFERENCES tbreport(idreport) ON UPDATE CASCADE ON DELETE CASCADE;

ALTER TABLE ONLY tbroltask
    ADD CONSTRAINT tbroltasknrol FOREIGN KEY (idrol) REFERENCES nrol(idrol) ON UPDATE CASCADE ON DELETE CASCADE;

ALTER TABLE ONLY tbroltask
    ADD CONSTRAINT tbroltaskntask FOREIGN KEY (idtask) REFERENCES ntask(idtask) ON UPDATE CASCADE ON DELETE CASCADE;

ALTER TABLE ONLY tbschedule
    ADD CONSTRAINT tbschedulenscheduletype FOREIGN KEY (idscheduletype) REFERENCES nscheduletype(idscheduletype) ON UPDATE CASCADE ON DELETE CASCADE;

ALTER TABLE ONLY tbschedule
    ADD CONSTRAINT tbscheduletbsubscription FOREIGN KEY (idsubscription) REFERENCES tbsubscription(idsubscription) ON UPDATE CASCADE ON DELETE CASCADE;

ALTER TABLE ONLY tbsqlfunction
    ADD CONSTRAINT tbsqlfunctionntargetdb FOREIGN KEY (idtargetdb) REFERENCES ntargetdb(idtargetdb) ON UPDATE CASCADE ON DELETE CASCADE;

ALTER TABLE ONLY tbsubscription
    ADD CONSTRAINT tbsubscriptionnformat FOREIGN KEY (idformat) REFERENCES nformat(idformat) ON UPDATE CASCADE ON DELETE CASCADE;

ALTER TABLE ONLY tbsubscription
    ADD CONSTRAINT tbsubscriptionnoptionrw FOREIGN KEY (idoptionrw) REFERENCES noptionrw(idoptionrw) ON UPDATE CASCADE ON DELETE CASCADE;

ALTER TABLE ONLY tbsubscription
    ADD CONSTRAINT tbsubscriptionnsubscriptiontyp FOREIGN KEY (idsubscriptiontype) REFERENCES nsubscriptiontype(idsubscriptiontype) ON UPDATE CASCADE ON DELETE CASCADE;

ALTER TABLE ONLY tbsubscription
    ADD CONSTRAINT tbsubscriptiontbreport FOREIGN KEY (idreport) REFERENCES tbreport(idreport) ON UPDATE CASCADE ON DELETE CASCADE;

ALTER TABLE ONLY tbsubscription
    ADD CONSTRAINT tbsubscriptiontbuser FOREIGN KEY (iduser) REFERENCES tbuser(iduser) ON UPDATE CASCADE ON DELETE CASCADE;

ALTER TABLE ONLY tbtemplatecategory
    ADD CONSTRAINT tbtemplatecategorytbcategory FOREIGN KEY (idcategory) REFERENCES tbcategory(idcategory) ON UPDATE CASCADE ON DELETE CASCADE;

ALTER TABLE ONLY tbtemplatecategory
    ADD CONSTRAINT tbtemplatecategorytbtemplate FOREIGN KEY (idtemplate) REFERENCES tbtemplate(idtemplate) ON UPDATE CASCADE ON DELETE CASCADE;

ALTER TABLE ONLY tbtemplatecss
    ADD CONSTRAINT tbtemplatecssncss FOREIGN KEY (idcss) REFERENCES ncss(idcss) ON UPDATE CASCADE ON DELETE CASCADE;

ALTER TABLE ONLY tbtemplatecss
    ADD CONSTRAINT tbtemplatecsstbtemplate FOREIGN KEY (idtemplate) REFERENCES tbtemplate(idtemplate) ON UPDATE CASCADE ON DELETE CASCADE;

ALTER TABLE ONLY tbtemplate
    ADD CONSTRAINT tbtemplatetbu17 FOREIGN KEY (modifiedid) REFERENCES tbuser(iduser) ON UPDATE CASCADE ON DELETE CASCADE;

ALTER TABLE ONLY tbtemplate
    ADD CONSTRAINT tbtemplatetbuser FOREIGN KEY (createid) REFERENCES tbuser(iduser) ON UPDATE CASCADE ON DELETE CASCADE;

ALTER TABLE ONLY tbusercategory
    ADD CONSTRAINT tbusercategorytbcategory FOREIGN KEY (idcategory) REFERENCES tbcategory(idcategory) ON UPDATE CASCADE ON DELETE CASCADE;

ALTER TABLE ONLY tbusercategory
    ADD CONSTRAINT tbusercategorytbuser FOREIGN KEY (iduser) REFERENCES tbuser(iduser) ON UPDATE CASCADE ON DELETE CASCADE;

ALTER TABLE ONLY tbuserreport
    ADD CONSTRAINT tbuserreporttbreport FOREIGN KEY (idreport) REFERENCES tbreport(idreport) ON UPDATE CASCADE ON DELETE CASCADE;

ALTER TABLE ONLY tbuserreport
    ADD CONSTRAINT tbuserreporttbuser FOREIGN KEY (iduser) REFERENCES tbuser(iduser) ON UPDATE CASCADE ON DELETE CASCADE;

ALTER TABLE ONLY tbuserrol
    ADD CONSTRAINT tbuserrolnrol FOREIGN KEY (idrol) REFERENCES nrol(idrol) ON UPDATE CASCADE ON DELETE CASCADE;

ALTER TABLE ONLY tbuserrol
    ADD CONSTRAINT tbuserroltbuser FOREIGN KEY (iduser) REFERENCES tbuser(iduser) ON UPDATE CASCADE ON DELETE CASCADE;



	
--fin de la tranzaccion--
COMMIT;                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                  