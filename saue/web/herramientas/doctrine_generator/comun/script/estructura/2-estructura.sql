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
VALUES ('V5.0.0', 'V5.0.0','mod_estructuracomp',null,'C','E','I');



	CREATE SCHEMA mod_estructuracomp;
------------------------------------------------------------------------------------------------
--Estructura del Esquema Etructura y Composición------------------------------------------------
------------------------------------------------------------------------------------------------
	
------------------------------------------------------------------------------------------------
--Esquema Datos Maestros------------------------------------------------------------------------
------------------------------------------------------------------------------------------------	
	--Insercción de datos para la generación de secuencias
	SET search_path = mod_datosmaestros, pg_catalog;	
	
	INSERT INTO nom_secuenciasgeneral VALUES ('sec_datcargo_seq', 5, 1, 5, 'mod_estructuracomp');
	INSERT INTO nom_secuenciasgeneral VALUES ('sec_datcargocivil_seq', 3, 1, 3, 'mod_estructuracomp');
	INSERT INTO nom_secuenciasgeneral VALUES ('sec_cargomiliat_seq', 3, 1, 3, 'mod_estructuracomp');
	INSERT INTO nom_secuenciasgeneral VALUES ('sec_datdocoficial_seq', 4, 1, 4, 'mod_estructuracomp');
	INSERT INTO nom_secuenciasgeneral VALUES ('sec_datespecifcargo_seq', 3, 1, 3, 'mod_estructuracomp');
	INSERT INTO nom_secuenciasgeneral VALUES ('sec_datmodcargo_seq', 3, 1, 3, 'mod_estructuracomp');
	INSERT INTO nom_secuenciasgeneral VALUES ('sec_datmodulos_seq', 3, 1, 3, 'mod_estructuracomp');
	INSERT INTO nom_secuenciasgeneral VALUES ('sec_nomescalasalarial_seq', 3, 1, 3, 'mod_estructuracomp');
	INSERT INTO nom_secuenciasgeneral VALUES ('sec_dattecnica_seq', 5, 1, 5, 'mod_estructuracomp');
	INSERT INTO nom_secuenciasgeneral VALUES ('sec_nomagrupacion_seq', 5, 1, 5, 'mod_estructuracomp');
	INSERT INTO nom_secuenciasgeneral VALUES ('sec_nomcargocivil_seq', 5, 1, 5, 'mod_estructuracomp');
	INSERT INTO nom_secuenciasgeneral VALUES ('sec_nomcargomilitar_seq', 5, 1, 5, 'mod_estructuracomp');
	INSERT INTO nom_secuenciasgeneral VALUES ('sec_nomcategcivil_seq', 3, 1, 3, 'mod_estructuracomp');
	INSERT INTO nom_secuenciasgeneral VALUES ('sec_nomcategocup_seq', 3, 1, 3, 'mod_estructuracomp');
	INSERT INTO nom_secuenciasgeneral VALUES ('sec_nomgrupocomple_seq', 3, 1, 3, 'mod_estructuracomp');
	INSERT INTO nom_secuenciasgeneral VALUES ('sec_nomcampoestruc_seq', 5, 1, 5, 'mod_estructuracomp');
	INSERT INTO nom_secuenciasgeneral VALUES ('sec_nomfilaestruc_seq', 8, 1, 8, 'mod_estructuracomp');
	INSERT INTO nom_secuenciasgeneral VALUES ('sec_nomgradomilit_seq', 4, 1, 4, 'mod_estructuracomp');
	INSERT INTO nom_secuenciasgeneral VALUES ('sec_nomgsubcateg_seq', 3, 1, 3, 'mod_estructuracomp');
	INSERT INTO nom_secuenciasgeneral VALUES ('sec_nommodulo_seq', 3, 1, 3, 'mod_estructuracomp');
	INSERT INTO nom_secuenciasgeneral VALUES ('sec_nomnivelestr_seq', 3, 1, 3, 'mod_estructuracomp');
	INSERT INTO nom_secuenciasgeneral VALUES ('sec_nomnomencladoreavestruc_seq', 3, 1, 3, 'mod_estructuracomp');
	INSERT INTO nom_secuenciasgeneral VALUES ('sec_nomsalario_seq', 3, 1, 3, 'mod_estructuracomp');
	INSERT INTO nom_secuenciasgeneral VALUES ('sec_nomorgano_seq', 3, 1, 3, 'mod_estructuracomp');
	INSERT INTO nom_secuenciasgeneral VALUES ('sec_nomtipocalificador_seq', 3, 1, 3, 'mod_estructuracomp');
	INSERT INTO nom_secuenciasgeneral VALUES ('sec_nomprefijo_seq', 3, 1, 3, 'mod_estructuracomp');
	INSERT INTO nom_secuenciasgeneral VALUES ('sec_nomprepmilitar_seq', 3, 1, 3, 'mod_estructuracomp');
	INSERT INTO nom_secuenciasgeneral VALUES ('sec_nomtecnica_seq', 3, 1, 3, 'mod_estructuracomp');
	INSERT INTO nom_secuenciasgeneral VALUES ('sec_nomtipocifra_seq', 3, 1, 3, 'mod_estructuracomp');
	INSERT INTO nom_secuenciasgeneral VALUES ('sec_nomtipodoc_seq', 3, 1, 3, 'mod_estructuracomp');
	INSERT INTO nom_secuenciasgeneral VALUES ('sec_nomvalor_defecto_seq', 3, 1, 3, 'mod_estructuracomp');
	INSERT INTO nom_secuenciasgeneral VALUES ('sec_nomvalorestruc_seq', 3, 1, 3, 'mod_estructuracomp');
	INSERT INTO nom_secuenciasgeneral VALUES ('sec_nomcalificadorcargo_seq', 3, 1, 3, 'mod_estructuracomp');
	INSERT INTO nom_secuenciasgeneral VALUES ('sec_nomclasificacioncargo_seq', 3, 1, 3, 'mod_estructuracomp');
	INSERT INTO nom_secuenciasgeneral VALUES ('sec_nomnivelutilizacion_seq', 3, 1, 3, 'mod_estructuracomp');
	INSERT INTO nom_secuenciasgeneral VALUES ('sec_datpuesto_seq', 10, 1, 10, 'mod_estructuracomp');
	INSERT INTO nom_secuenciasgeneral VALUES ('sec_nomdominio_seq', 6, 1, 6, 'mod_estructuracomp');




	
-------------------------------------------------------------------------------
----Creación de secuencias
--------------------------------------------------------------------------------
	SET search_path = mod_estructuracomp, pg_catalog;	
	
-------------------------------------------------------------------------------
----Creación de tablas
--------------------------------------------------------------------------------ç

	SET search_path = mod_estructuracomp, pg_catalog;	

	CREATE TABLE dat_agrupacionest 
	(
		idestructura numeric(19,0) NOT NULL,
		idagrupacion numeric(19,0) NOT NULL
	);

	CREATE TABLE dat_agrupacionestop 
	(
		idestructuraop numeric(19,0) NOT NULL,
		idagrupacion numeric(19,0) NOT NULL
	);

	CREATE TABLE dat_cargo 
	(
		idcargo numeric(19,0) DEFAULT nextval('mod_estructuracomp.sec_datcargo_seq'::regclass) NOT NULL,
		idestructuraop numeric(19,0) NOT NULL,
		idespecialidad numeric(19,0) NOT NULL,
		ctp numeric(5,0),
		ctg numeric(5,0),
		idtipocifra numeric(19,0) NOT NULL,
		idprefijo numeric(19,0) NOT NULL,
		orden numeric(10,0),
		estado numeric(10,0) DEFAULT 1 NOT NULL,
		fechaini date,
		fechafin date,
		salario numeric(10,0),
		--idtecnica numeric(19,0),
		idgrupocomplejidad numeric(19,0),
		idmodulo numeric(19,0)
	);

	CREATE TABLE dat_cargocivil 
	(
		idcargocivil numeric(19,0) DEFAULT nextval('mod_estructuracomp.sec_datcargocivil_seq'::regclass) NOT NULL,
		idcargo numeric(19,0) NOT NULL,
		idcargociv numeric(19,0) NOT NULL,
		idcategcivil numeric(19,0),
		idgrupocomple numeric(19,0),
		idescalasalarial numeric(19,0),
		idsalario numeric(19,0),
		idclasificacion numeric(19,0),
		modificable numeric(1,0)
	);

	CREATE TABLE dat_cargomtar 
	(
		idcargomilitar numeric(19,0) DEFAULT nextval('mod_estructuracomp.sec_cargomiliat_seq'::regclass) NOT NULL,
		idgradomilit numeric(19,0) NOT NULL,
		salario numeric(10,0),
		escadmando numeric(1,0),
		idcargo numeric(19,0) NOT NULL,
		idnomcargomilitar numeric(19,0)
	);

	CREATE TABLE dat_docoficial 
	(
		iddocoficial numeric(19,0) DEFAULT nextval('mod_estructuracomp.sec_datdocoficial_seq'::regclass) NOT NULL,
		denominacion character varying(255),
		texto character varying(500),
		fechadoc date,
		fechavigor date,
		nodoc numeric(10,0),
		idtipodoc numeric(10,0),
		estado numeric(1,0)
	);

	CREATE TABLE dat_estructura 
	(
		idestructura numeric(19,0) NOT NULL,
		idprefijo numeric(10,0) NOT NULL,
		fechaini date,
		fechafin date,
		lft numeric(5,0),
		rgt numeric(5,0),
		denominacion character varying(255),
		abreviatura character varying(100),
		idnomeav numeric(10,0),
		idpadre numeric(10,0),
		idorgano numeric(10,0),
		version numeric(6,0) DEFAULT 0,
		idnivelestr numeric(19,0),
		idespecialidad numeric(19,0),
		iddpa numeric(19,0),
		codigo character varying(20)
	);

	CREATE TABLE dat_estructuraop 
	(
		idestructuraop numeric(19,0) NOT NULL,
		idpadre numeric(10,0),
		idprefijo numeric(10,0) NOT NULL,
		fechaini date,
		fechafin date,
		lft numeric(5,0),
		rgt numeric(5,0),
		denominacion character varying(255),
		abreviatura character varying(100),
		idnomeav numeric(10,0),
		idestructura numeric(10,0) NOT NULL,
		idorgano numeric(19,0),
		version numeric(6,0) DEFAULT 0,
		idespecialidad numeric(19,0),
		idnivelestr numeric(19,0),
		iddpa numeric(19,0),
		codigo character varying(20)
	);

	CREATE TABLE dat_modcargo 
	(
		idmodulo numeric(10,0) NOT NULL,
		id_cargo numeric(10,0),
		idmodcargo numeric(19,0) DEFAULT nextval('mod_estructuracomp.sec_datmodcargo_seq'::regclass) NOT NULL
	);

	CREATE TABLE dat_modulos 
	(
		idmodulo numeric(10,0) DEFAULT nextval('mod_estructuracomp.sec_datmodulos_seq'::regclass) NOT NULL,
		idtecnica numeric(10,0) NOT NULL,
		ctp numeric(5,0),
		ctg numeric(5,0)
	);

	CREATE TABLE dat_puesto 
	(
		idpuesto numeric(10,0) DEFAULT nextval('mod_estructuracomp.sec_datpuesto_seq'::regclass) NOT NULL,
		idcargo numeric(10,0),
		denominacion character varying(255),
		abreviatura character varying(100),
		habilidades character varying(255),
		condiciones character varying(255),
		acciones character varying(255),
		riesgos character varying(255)
	);

	CREATE TABLE dat_tecnica 
	(
		idestructura numeric(19,0) NOT NULL,
		ctp numeric(10,0),
		ctg numeric(8,0),
		idtecnica numeric(19,0) DEFAULT nextval('mod_estructuracomp.sec_dattecnica_seq'::regclass) NOT NULL,
		idnomtecnica numeric(19,0)
	);

	CREATE TABLE nom_agrupacion 
	(
		idagrupacion numeric(19,0) DEFAULT nextval('mod_estructuracomp.sec_nomagrupacion_seq'::regclass) NOT NULL,
		denagrupacion character varying(255),
		abrevagrupacion character varying(20),
		orden numeric(10,0) NOT NULL,
		fechaini date,
		fechafin date
	);

	CREATE TABLE nom_aristaeav 
	(
		idorigen numeric(8,0) NOT NULL,
		iddestino numeric(8,0) NOT NULL
	);

	CREATE TABLE nom_calificador_cargo 
	(
		idcalificador numeric(19,0) DEFAULT nextval('mod_estructuracomp.sec_nomcalificadorcargo_seq'::regclass) NOT NULL,
		denominacion character varying(255),
		abreviatura character varying(100),
		fechaini date,
		fechafin date,
		orden numeric(5,0),
		idtipocalificador numeric(10,0),
		codigo character varying(25),
		idcategocup numeric(19,0)
	);

	CREATE TABLE nom_campoestruc 
	(
		idcampo numeric(19,0) DEFAULT nextval('mod_estructuracomp.sec_nomcampoestruc_seq'::regclass) NOT NULL,
		idnomeav numeric(19,0),
		nombre character varying(30) NOT NULL,
		tipo character varying(255) NOT NULL,
		longitud numeric(10,0) NOT NULL,
		nombre_mostrar character varying(255) NOT NULL,
		regex character varying(255),
		descripcion character varying(255),
		tipocampo character varying(100),
		--nom_nomencladoreavestrucidnomeav numeric(10,0),
		visible numeric(1,0)
	);

	CREATE TABLE nom_cargocivil 
	(
		idcargociv numeric(10,0) DEFAULT nextval('mod_estructuracomp.sec_nomcargocivil_seq'::regclass) NOT NULL,
		dencargociv character varying(255),
		abrevcargociv character varying(100),
		orden numeric(10,0),
		fechaini date,
		fechafin date,
		idcategocup numeric(10,0),
		codigo character varying(25),
		descripcion text,
		requisitos text,
		idcalificador numeric(19,0),
		idgrupocomplejidad numeric(19,0),
		idnivelutilizacion numeric(19,0),
		idespecialidad numeric(19,0),
		idcategcivil numeric(19,0)
	);

	CREATE TABLE nom_cargomilitar 
	(
		idcargomilitar numeric(10,0) DEFAULT nextval('mod_estructuracomp.sec_nomcargomilitar_seq'::regclass) NOT NULL,
		dencargomilitar character varying(255),
		abrevcargomilitar character varying(100),
		idespecialidad numeric(10,0) NOT NULL,
		orden numeric(10,0),
		idprepmilitar numeric(10,0),
		fechaini date,
		fechafin date,
		idcalificador numeric(19,0),
		idtipocifra numeric(10,0),
		idgradomilit numeric(10,0)
	);

	CREATE TABLE nom_categcivil 
	(
		idcategcivil numeric(10,0) DEFAULT nextval('mod_estructuracomp.sec_nomcategcivil_seq'::regclass) NOT NULL,
		dencategcivil character varying(255),
		abrevcategcivil character varying(100),
		orden numeric(5,0),
		fechaini date,
		fechafin date,
		essueldo numeric(1,0)
	);

	CREATE TABLE nom_categocup 
	(
		idcategocup numeric(10,0) DEFAULT nextval('mod_estructuracomp.sec_nomcategocup_seq'::regclass) NOT NULL,
		dencategocup character varying(255),
		orden numeric(10,0),
		fechaini date,
		fechafin date,
		abreviatura character varying(100)
	);

	CREATE TABLE nom_clasificacion_cargo 
	(
		idclasificacion numeric(19,0) DEFAULT nextval('mod_estructuracomp.sec_nomclasificacioncargo_seq'::regclass) NOT NULL,
		denominacion character varying(255),
		orden numeric(5,0),
		fechaini date,
		fechafin date
	);

	CREATE TABLE nom_dominio 
	(
		iddominio numeric(8,0) DEFAULT nextval('mod_estructuracomp.sec_nomdominio_seq'::regclass) NOT NULL,
		denominacion character varying(255) NOT NULL,
		dominiostring character varying(255),
		descripcion character varying(250),
		seguridad integer DEFAULT 0,
		idpadre numeric(8,0),
		lft numeric(19,0),
		rgt numeric(19,0),
		dominio bit(1)[]
	);

	CREATE TABLE nom_escalasalarial 
	(
		idescalasalarial numeric(19,0) DEFAULT nextval('mod_estructuracomp.sec_nomescalasalarial_seq'::regclass) NOT NULL,
		denominacion character varying(255),
		abreviatura character varying(100),
		fechaini date,
		fechafin date,
		orden numeric(10,0)
	);

	CREATE TABLE nom_filaestruc 
	(
		idfila numeric(19,0) DEFAULT nextval('mod_estructuracomp.sec_nomfilaestruc_seq'::regclass) NOT NULL,
		idnomeav numeric(10,0),
		dominio bit(83000000),
		idpadre numeric(19,0),
		lft numeric(19,0),
		rgt numeric(19,0),
		dominiofila bit(1)[]
	);

	CREATE TABLE nom_gradomilit 
	(
		idgradomilit numeric(10,0) DEFAULT nextval('mod_estructuracomp.sec_nomgradomilit_seq'::regclass) NOT NULL,
		dengradomilit character varying(255),
		abrevgradomilit character varying(100),
		homologoterr numeric(5,0),
		anterior numeric(5,0),
		sucesor numeric(5,0),
		orden numeric(10,0),
		fechaini date,
		fechafin date,
		idgsubcateg numeric(10,0),
		esmarina numeric(1,0)
	);

	CREATE TABLE nom_grupocomple 
	(
		idgrupocomplejidad numeric(19,0) DEFAULT nextval('mod_estructuracomp.sec_nomgrupocomple_seq'::regclass) NOT NULL,
		denominacion character varying(255),
		abreviatura character varying(100),
		idescalasalarial numeric(19,0),
		salarioescala numeric(10,0),
		fechaini date,
		fechafin date,
		orden numeric(10,0)
	);

	CREATE TABLE nom_gsubcateg 
	(
		idgsubcateg numeric(19,0) DEFAULT nextval('mod_estructuracomp.sec_nomgsubcateg_seq'::regclass) NOT NULL,
		densubcateg character varying(255),
		orden numeric(10,0),
		fechaini date,
		fechafin date
	);

	CREATE TABLE nom_modulo 
	(
		idmodulo numeric(10,0) DEFAULT nextval('mod_estructuracomp.sec_nommodulo_seq'::regclass) NOT NULL,
		denmodulo character varying(255),
		fechaini date,
		fechafin date,
		orden numeric(10,0) DEFAULT 0,
		abreviatura character varying(100),
		idtecnica numeric(10,0)
	);

	CREATE TABLE nom_nivel_utilizacion 
	(
		idnivelutilizacion numeric(19,0) DEFAULT nextval('mod_estructuracomp.sec_nomnivelutilizacion_seq'::regclass) NOT NULL,
		denominacion character varying(255),
		abreviatura character varying(100),
		orden numeric(10,0),
		fechaini date,
		fechafin date
	);

	CREATE TABLE nom_nivelestr 
	(
		idnivelestr numeric(19,0) DEFAULT nextval('mod_estructuracomp.sec_nomnivelestr_seq'::regclass) NOT NULL,
		abrevnivelestr character varying(100),
		dennivelestr character varying(255),
		orden numeric(10,0),
		fechaini date,
		fechafin date
	);

	CREATE TABLE nom_nomencladoreavestruc 
	(
		idnomeav numeric(19,0) DEFAULT nextval('mod_estructuracomp.sec_nomnomencladoreavestruc_seq'::regclass) NOT NULL,
		nombre character varying(30),
		fechaini date,
		fechafin date,
		root bit(1),
		concepto numeric(1,0),
		externa numeric(2,0)
	);

	CREATE TABLE nom_organo 
	(
		idorgano numeric(19,0) DEFAULT nextval('mod_estructuracomp.sec_nomorgano_seq'::regclass) NOT NULL,
		idnivelestr numeric(19,0) NOT NULL,
		idespecialidad numeric(19,0) NOT NULL,
		denorgano character varying(255),
		abrevorgano character varying(100),
		orden numeric(10,0) NOT NULL,
		fechaini date,
		fechafin date,
		idnomeav numeric(19,0)
	);

	CREATE TABLE nom_prefijo 
	(
		idprefijo numeric(10,0) DEFAULT nextval('mod_estructuracomp.sec_nomprefijo_seq'::regclass) NOT NULL,
		prefijo character varying(10),
		desclugar character varying(30),
		fechaini date,
		fechafin date,
		orden numeric(10,0)
	);

	CREATE TABLE nom_prepmilitar 
	(
		idprepmilitar numeric(10,0) DEFAULT nextval('mod_estructuracomp.sec_nomprepmilitar_seq'::regclass) NOT NULL,
		denprepmilitar character varying(255),
		abrevprepmilitar character varying(100),
		orden numeric(5,0) DEFAULT 0,
		fechaini date,
		fechafin date
	);

	CREATE TABLE nom_salario 
	(
		idsalario numeric(19,0) DEFAULT nextval('mod_estructuracomp.sec_nomsalario_seq'::regclass) NOT NULL,
		idgrupocomplejidad numeric(19,0),
		idescalasalarial numeric(19,0),
		fechaini date,
		fechafin date,
		salario numeric(6,2),
		tarifa numeric(10,5),
		orden numeric(10,0)
	);

	CREATE TABLE nom_tecnica 
	(
		idtecnica numeric(10,0) DEFAULT nextval('mod_estructuracomp.sec_nomtecnica_seq'::regclass) NOT NULL,
		codtecnica character varying(20),
		dentecnica character varying(255),
		abrevtecnica character varying(100),
		orden numeric(10,0) DEFAULT 0,
		fechaini date,
		fechafin date,
		vaplantilla numeric(10,0)
	);

	CREATE TABLE nom_tipo_calificador 
	(
		idtipocalificador numeric(19,0) DEFAULT nextval('mod_estructuracomp.sec_nomtipocalificador_seq'::regclass) NOT NULL,
		orden numeric(10,0),
		fechaini date,
		fechafin date,
		denominacion character varying(255),
		abreviatura character varying(100)
	);

	CREATE TABLE nom_tipocifra 
	(
		idtipocifra numeric(10,0) DEFAULT nextval('mod_estructuracomp.sec_nomtipocifra_seq'::regclass) NOT NULL,
		dentipocifra character varying(255),
		esdescargable boolean NOT NULL,
		fechaini date,
		fechafin date,
		escifracargo boolean,
		orden numeric(10,0)
	);

	CREATE TABLE nom_tipodoc 
	(
		iddocoficial numeric(19,0) DEFAULT nextval('mod_estructuracomp.sec_nomtipodoc_seq'::regclass) NOT NULL,
		denominacion character varying(255),
		fechaini date,
		fechafin date
	);

	CREATE TABLE nom_valor_defecto 
	(
		idvalordefecto numeric(10,0) DEFAULT nextval('mod_estructuracomp.sec_nomvalor_defecto_seq'::regclass) NOT NULL,
		valorid character varying(255),
		valor character varying(255),
		idcampo numeric(10,0),
		version numeric(6,0) DEFAULT 0
	);

	CREATE TABLE nom_valorestruc 
	(
		idfila numeric(10,0) NOT NULL,
		idcampo numeric(10,0) NOT NULL,
		valor character varying(255)
	);

	CREATE TABLE mod_estructuracomp.nom_subordinacion
	(
	  idnomsubordinacion numeric(9) NOT NULL,
	  denominacion character varying(255),
	  abreviatura character varying(60),
	  color character varying(9),
	  CONSTRAINT pk_nom_subordinacion PRIMARY KEY (idnomsubordinacion)
	);
	
	CREATE TABLE mod_estructuracomp.dat_subordinacion
	(
	  idpadre numeric(19) NOT NULL,
	  idhijo numeric(19) NOT NULL,
	  idnomsubordinacion numeric(19),
	  CONSTRAINT pk_dat_subordinacion PRIMARY KEY (idpadre, idhijo)
	);

-------------------------------------------------------------------------------
----Creación de llaves primarias
--------------------------------------------------------------------------------
	SET search_path = mod_estructuracomp, pg_catalog;	
	
	ALTER TABLE ONLY dat_cargo
		ADD CONSTRAINT dat_cargo_pkey PRIMARY KEY (idcargo);

	ALTER TABLE ONLY dat_cargomtar
		ADD CONSTRAINT dat_cargomtar_pkey PRIMARY KEY (idcargomilitar, idcargo);

	ALTER TABLE ONLY dat_docoficial
		ADD CONSTRAINT dat_docoficial_pkey PRIMARY KEY (iddocoficial);
	
	
	ALTER TABLE ONLY dat_estructura
		ADD CONSTRAINT dat_estructura_pkey PRIMARY KEY (idestructura);


	ALTER TABLE ONLY dat_estructuraop
		ADD CONSTRAINT dat_estructuraop_pkey PRIMARY KEY (idestructuraop);

	ALTER TABLE ONLY dat_modulos
		ADD CONSTRAINT dat_modulos_idx PRIMARY KEY (idmodulo, idtecnica);

	ALTER TABLE ONLY dat_tecnica
		ADD CONSTRAINT dat_tecnica_pkey PRIMARY KEY (idtecnica);

	ALTER TABLE ONLY dat_modcargo
		ADD CONSTRAINT idmodcargo PRIMARY KEY (idmodcargo);

	ALTER TABLE ONLY nom_agrupacion
		ADD CONSTRAINT nom_agrupacion_pkey PRIMARY KEY (idagrupacion);

	ALTER TABLE ONLY nom_cargocivil
		ADD CONSTRAINT nom_cargocivil_pkey PRIMARY KEY (idcargociv);

	ALTER TABLE ONLY nom_cargomilitar
		ADD CONSTRAINT nom_cargomilitar_pkey PRIMARY KEY (idcargomilitar);

	ALTER TABLE ONLY nom_categcivil
		ADD CONSTRAINT nom_categcivil_pkey PRIMARY KEY (idcategcivil);

	ALTER TABLE ONLY nom_categocup
		ADD CONSTRAINT nom_categocup_pkey PRIMARY KEY (idcategocup);

	ALTER TABLE ONLY nom_tipodoc
		ADD CONSTRAINT nom_docoficiales_pkey PRIMARY KEY (iddocoficial);

	ALTER TABLE ONLY nom_gradomilit
		ADD CONSTRAINT nom_gradomilit_pkey PRIMARY KEY (idgradomilit);

	ALTER TABLE ONLY nom_gsubcateg
		ADD CONSTRAINT nom_gsubcateg_pkey PRIMARY KEY (idgsubcateg);

	ALTER TABLE ONLY nom_modulo
		ADD CONSTRAINT nom_modulo_pkey PRIMARY KEY (idmodulo);

	ALTER TABLE ONLY nom_nivelestr
		ADD CONSTRAINT nom_nivelestr_pkey PRIMARY KEY (idnivelestr);

	ALTER TABLE ONLY nom_prefijo
		ADD CONSTRAINT nom_prefijo_pkey PRIMARY KEY (idprefijo);

	ALTER TABLE ONLY nom_prepmilitar
		ADD CONSTRAINT nom_prepmilitar_pkey PRIMARY KEY (idprepmilitar);

	ALTER TABLE ONLY nom_tecnica
		ADD CONSTRAINT nom_tecnica_pkey PRIMARY KEY (idtecnica);

	ALTER TABLE ONLY nom_tipocifra
		ADD CONSTRAINT nom_tipocifra_pkey PRIMARY KEY (idtipocifra);

	ALTER TABLE ONLY nom_valorestruc
		ADD CONSTRAINT nom_valorestruc_pkey PRIMARY KEY (idfila, idcampo);

	ALTER TABLE ONLY dat_agrupacionest
		ADD CONSTRAINT pk_agrupacionest PRIMARY KEY (idestructura, idagrupacion);

	ALTER TABLE ONLY dat_agrupacionestop
		ADD CONSTRAINT pk_dat_agrupacionestop PRIMARY KEY (idestructuraop, idagrupacion);

	ALTER TABLE ONLY dat_cargocivil
		ADD CONSTRAINT pk_dat_cargocivil PRIMARY KEY (idcargocivil, idcargo);

	ALTER TABLE ONLY dat_puesto
		ADD CONSTRAINT pk_dat_puesto PRIMARY KEY (idpuesto);

	ALTER TABLE ONLY nom_dominio
		ADD CONSTRAINT pk_dominio PRIMARY KEY (iddominio);

	ALTER TABLE ONLY nom_campoestruc
		ADD CONSTRAINT pk_idcampo PRIMARY KEY (idcampo);

	ALTER TABLE ONLY nom_clasificacion_cargo
		ADD CONSTRAINT pk_idclasificacion PRIMARY KEY (idclasificacion);

	ALTER TABLE ONLY nom_filaestruc
		ADD CONSTRAINT pk_idfila PRIMARY KEY (idfila);

	ALTER TABLE ONLY nom_nomencladoreavestruc
		ADD CONSTRAINT pk_idnomeav PRIMARY KEY (idnomeav);

	ALTER TABLE ONLY nom_aristaeav
		ADD CONSTRAINT pk_nom_arista PRIMARY KEY (idorigen, iddestino);

	ALTER TABLE ONLY nom_calificador_cargo
		ADD CONSTRAINT pk_nom_calificador_cargo PRIMARY KEY (idcalificador);

	ALTER TABLE ONLY nom_escalasalarial
		ADD CONSTRAINT pk_nom_escalasalarial PRIMARY KEY (idescalasalarial);

	ALTER TABLE ONLY nom_grupocomple
		ADD CONSTRAINT pk_nom_grupocomp PRIMARY KEY (idgrupocomplejidad);

	ALTER TABLE ONLY nom_nivel_utilizacion
		ADD CONSTRAINT pk_nom_nivel_utilizacion PRIMARY KEY (idnivelutilizacion);

	ALTER TABLE ONLY nom_organo
		ADD CONSTRAINT pk_nom_organo PRIMARY KEY (idorgano);

	ALTER TABLE ONLY nom_salario
		ADD CONSTRAINT pk_nom_salario PRIMARY KEY (idsalario);

	ALTER TABLE ONLY nom_tipo_calificador
		ADD CONSTRAINT pk_nom_tipocalificador PRIMARY KEY (idtipocalificador);

	ALTER TABLE ONLY nom_valor_defecto
		ADD CONSTRAINT pk_nom_valor_defecto PRIMARY KEY (idvalordefecto);
	
-------------------------------------------------------------------------------
----Creación de otras restricciones de datos
--------------------------------------------------------------------------------
	SET search_path = mod_estructuracomp, pg_catalog;	

	ALTER TABLE ONLY dat_estructura
		ADD CONSTRAINT dat_estructura_codigo_key UNIQUE (codigo);
		
-------------------------------------------------------------------------------
----Creación de indices
--------------------------------------------------------------------------------
	SET search_path = mod_estructuracomp, pg_catalog;	
	
	CREATE INDEX fk1i_nom_calificador_cargo ON nom_cargomilitar USING btree (idcalificador);
	CREATE INDEX fki1_nom_escalasalarial ON nom_salario USING btree (idescalasalarial);
	CREATE INDEX fki1_nom_grupocomple ON dat_cargocivil USING btree (idgrupocomple);
	CREATE INDEX fki3_nom_escalasalarial ON dat_cargocivil USING btree (idescalasalarial);
	CREATE INDEX fki_anterior ON nom_gradomilit USING btree (anterior);
	CREATE INDEX fki_dat_cargo1 ON dat_modcargo USING btree (id_cargo);
	CREATE INDEX fki_dat_estoppadre ON dat_estructuraop USING btree (idpadre);
	CREATE INDEX fki_nom_calificador_cargo ON nom_cargocivil USING btree (idcalificador);
	CREATE INDEX fki_nom_categcivil ON nom_cargocivil USING btree (idcategcivil);
	CREATE INDEX fki_nom_categocup ON nom_calificador_cargo USING btree (idcategocup);
	CREATE INDEX fki_nom_clasificacion_cargo ON dat_cargocivil USING btree (idclasificacion);
	CREATE INDEX fki_nom_grupo_comp ON nom_cargocivil USING btree (idgrupocomplejidad);
	CREATE INDEX fki_nom_grupocomple ON nom_salario USING btree (idgrupocomplejidad);
	CREATE INDEX fki_nom_nivel_utilizacion ON nom_cargocivil USING btree (idnivelutilizacion);
	CREATE INDEX fki_nom_organo ON dat_estructuraop USING btree (idorgano);
	CREATE INDEX fki_nom_prefijo ON dat_estructuraop USING btree (idprefijo);
	CREATE INDEX fki_nom_salario ON dat_cargocivil USING btree (idsalario);
	CREATE INDEX fki_nom_tipo_calificador ON nom_calificador_cargo USING btree (idtipocalificador);
	CREATE INDEX fki_nom_tipodoc ON dat_docoficial USING btree (idtipodoc);
	CREATE INDEX fki_sucesor ON nom_gradomilit USING btree (sucesor);
	CREATE INDEX dom ON mod_estructuracomp.nom_dominio USING gin (dominio);

-------------------------------------------------------------------------------
----Creación de tipos de datos
--------------------------------------------------------------------------------
	SET search_path = mod_estructuracomp, pg_catalog;	
		
CREATE TYPE "mod_estructuracomp"."cant_entida_unidades_agrupacion" AS (
  "nombre_nivel" VARCHAR(255),
  "abrev_nivel" VARCHAR(100),
  "codigo_one_nivel" VARCHAR(255),
  "nombre_agrupacion" VARCHAR(255),
  "abrev_agrupacion" VARCHAR(100),
  "organo" VARCHAR(255),
  "categoria" VARCHAR(255),
  "total_entidades" NUMERIC(19,0),
  "total_unidades" NUMERIC(19,0)
);

	CREATE TYPE td_agruppornivel1 AS
	   (
			denominacion varchar(255),
			idestructura numeric(19),
			denominacionestruct varchar(255),
			abreviaturaestruct varchar(100),
			denorgano varchar(255)
		);

	CREATE TYPE td_areas AS
	   (
			denominacion_cargod varchar(255),
			categ_ocupacional varchar(255),
			grupocomple varchar(255),
			escala_salarial varchar(255),
			salario numeric(19,2),
			idestructura numeric(19),
			idestructuraop numeric(19)
		);

	CREATE TYPE "mod_estructuracomp"."td_calificador_cargo" AS (
  "codigo" VARCHAR(25),
  "denominacion_cargo" VARCHAR(255),
  "categoria_ocupacional" VARCHAR(50),
  "grupo_complejidad" VARCHAR(255),
  "tareas_o_funciones" TEXT,
  "nivel_utilizacion" VARCHAR(255),
  "requisitos" TEXT
);

	CREATE TYPE "mod_estructuracomp"."td_cargos_areas_categoria" AS (
  "denominacion" VARCHAR(255),
  "total" NUMERIC(19,0),
  "administrativo" NUMERIC(19,0),
  "servicios" NUMERIC(19,0),
  "tecnicos" NUMERIC(19,0),
  "operarios" NUMERIC(19,0),
  "dirigentes" NUMERIC(19,0),
  "entidad" VARCHAR(255),
  "padre" VARCHAR(255),
  "codigo" VARCHAR(20)
);

	CREATE TYPE td_entidades AS
	   (
			codigo varchar(255),
			denominacion_area varchar(255),
			categoria_ocupacional varchar(255),
			cantidad numeric(19)
		);

	CREATE TYPE td_entidadesporagrupaciones AS
	   (
			denomestructura varchar(255),
			idestructura numeric(19),
			denominacion varchar(255),
			denominacionetruct varchar(255),
			abreviaturaestruct varchar(100),
			denorgano varchar(255),
			categoria varchar(255),
			"ActEconomica" varchar(255)
		);
		
	CREATE TYPE "mod_estructuracomp"."td_grupoescalacategocupacional1" AS (
  "entidad" VARCHAR(255),
  "grupocomplejidad" VARCHAR(25),
  "administrativo" NUMERIC(19,0),
  "servicios" NUMERIC(19,0),
  "tecnicos" NUMERIC(19,0),
  "operarios" NUMERIC(19,0),
  "dirigentes" NUMERIC(19,0),
  "total" NUMERIC(19,0),
  "codigo" VARCHAR(20)
);

	CREATE TYPE td_nivel1porclasif AS
	   (
			denorgano varchar(255),
			abreviaturaestruct varchar(100),
			denominacionestruct varchar(255),
			numhijos numeric(19),
			numhijose numeric(19)
		);

	CREATE TYPE "mod_estructuracomp"."td_plantilla_cargos" AS (
  "denominacion_area" VARCHAR(255),
  "denominacion_cargo" VARCHAR(255),
  "cantidad_cargos" NUMERIC(19,0),
  "abreviatura_grupo_escala" VARCHAR(100),
  "abreviatura_categoria_ocupacional" VARCHAR(100),
  "especialidad" VARCHAR(255),
  "entidad" VARCHAR(255),
  "codigo" VARCHAR(20)
);

	CREATE TYPE td_rep_componentesestructurainterna AS
	   (
			estructura varchar(255),
			areas varchar(255),
			totalcargos numeric(19),
			total numeric(19)
		);

	CREATE TYPE "mod_estructuracomp"."td_rep_nivel1porclasif" AS (
  "clasificacion" VARCHAR(255),
  "Nombre" VARCHAR(255),
  "Abrev" VARCHAR(255),
  "TotalAgrupac" NUMERIC(19,0),
  "TotalEntidades" NUMERIC(19,0),
  "TotalUnidades" NUMERIC(19,0)
);
		


	CREATE TYPE "mod_estructuracomp"."td_rep_relacion_localizacion_unidades_nivelest" AS (
  "NombreUnidad" VARCHAR(255),
  "Abrev" VARCHAR(255),
  "Domicilio" VARCHAR(255),
  "Localidad" VARCHAR(255),
  "Provincia" VARCHAR(255),
  "Municipio" VARCHAR(255),
  "TlefonoPizarra" VARCHAR(255),
  "TelefonoDireccion" VARCHAR(255),
  "Fax" VARCHAR(255),
  "email" VARCHAR(255),
  "Codigo" VARCHAR(20),
  "NombreNivel" VARCHAR(255)
);

CREATE TYPE "mod_estructuracomp"."td_rep_relacion_localizunidadesporentidad" AS (
  "NombreEmpresa" VARCHAR(255),
  "AbrevEmpresa" VARCHAR(255),
  "NombreUnidad" VARCHAR(255),
  "Abrev" VARCHAR(255),
  "Domicilio" VARCHAR(255),
  "Localidad" VARCHAR(255),
  "Provincia" VARCHAR(255),
  "Municipio" VARCHAR(255),
  "TlefonoPizarra" VARCHAR(255),
  "TelefonoDireccion" VARCHAR(255),
  "Fax" VARCHAR(255),
  "email" VARCHAR(255),
  "Codigo" VARCHAR(20),
  "NombreNivel" VARCHAR(255)
);

CREATE TYPE "mod_estructuracomp"."td_rep_relacion_registro_entidad_agrupacion1" AS (
  "NombreAgrupacion" VARCHAR(255),
  "AbrevAgrupacion" VARCHAR(255),
  "NombreEntidad" VARCHAR(255),
  "AbrevEntidad" VARCHAR(255),
  "CodigoOne" VARCHAR(255),
  "CodigoNAE" VARCHAR(255),
  "RegistroMercantil" VARCHAR(255),
  "RegisroComercial" VARCHAR(255),
  "RegistroONAT" VARCHAR(255),
  "Codigo" VARCHAR(20),
  "NombreNivel" VARCHAR(255)
);

	CREATE TYPE td_rep_relacionagruppornivel1 AS
	   (
			"NombreNivel" varchar(255),
			"abrevNivel" varchar(255),
			"NombreAgrupacion" varchar(255),
			"Clasificacion" varchar(255),
			"Categoria" varchar(255),
			"CantidadEntidades" numeric(19)
		);

	CREATE TYPE "mod_estructuracomp"."td_rep_relacionentidadesporagrupaciones1" AS (
  "NombreAgrupacion" VARCHAR(255),
  "TipoEstructura" VARCHAR(255),
  "NombreEntidad" VARCHAR(255),
  "AbrevEntidad" VARCHAR(255),
  "Codigo" VARCHAR(255),
  "Clasificacion" VARCHAR(255),
  "Categoria" VARCHAR(255),
  "ActivEconomica" VARCHAR(255),
  "CantidadUnidades" NUMERIC(19,0),
  "CodigoNae" VARCHAR(255),
  "Perfeccionamiento" VARCHAR(255),
  "CodigoNivel1" VARCHAR(20),
  "NombreNivel1" VARCHAR(255)
);

	CREATE TYPE td_rep_resumen_agrupaciones_nivel AS
	   (
			"Clasificacion" varchar(255),
			"CantAgrupaciones" numeric(19),
			"CantEntidades" numeric(19),
			"CantUnidades" numeric(19)
		);

	CREATE TYPE "mod_estructuracomp"."td_rep_resumen_categoria_entidades_agrupaciones" AS (
  "NombreAgrupacion" VARCHAR(255),
  "Abrev" VARCHAR(255),
  "TotalEntidades" NUMERIC(19,0),
  "EntidadesCategoriaI" NUMERIC(19,0),
  "EntidadesCategoriaII" NUMERIC(19,0),
  "EntidadesCategoriaIIII" NUMERIC(19,0),
  "EntidadesCategoriaIV" NUMERIC(19,0),
  "Codigo" VARCHAR(20),
  "NombreNivel" VARCHAR(255)
);



	CREATE TYPE "mod_estructuracomp"."td_rep_resumentidporagrupaciporclasificac" AS (
  "Clasificacion" VARCHAR(255),
  "NombreAgrupacion" VARCHAR(255),
  "AbrevAgrupacion" VARCHAR(255),
  "TotalEntidades" NUMERIC(19,0),
  "TotalEntidadesEstatales" NUMERIC(19,0),
  "TotalEntidadesMixtas" NUMERIC(19,0),
  "TotalEntidadesCAEI" NUMERIC(19,0),
  "TotalEntidadesActEmpresarial" NUMERIC(19,0),
  "TotalEntidadesActPresupuestada" NUMERIC(19,0),
  "Codigo" VARCHAR(20),
  "NombreNivel" VARCHAR(255)
);

	CREATE TYPE td_unidades AS
		(
		   idestructura numeric(19),
			denomestructura varchar(255),
			abrevestructura varchar(255),
			tipo varchar(255),
			domicilio varchar(255),
			localidad varchar(255),
			municipio varchar(255),
			provincia varchar(255),
			telefonopizarra varchar(255),
			estensiondireccion varchar(255),
			fax varchar(255),
			email varchar(255)
		);

	CREATE TYPE td_unidadesporentidad AS
	   (
			denominacionestruc varchar(255),
			idestructura numeric(19),
			denominacion varchar(255),
			denominacionestruct varchar(255),
			abreviaturaestruct varchar(255),
			domincilio varchar(255),
			municipio varchar(255),
			provincia varchar(255),
			telefono numeric(19),
			fax varchar(255),
			email varchar(255)
		);




-------------------------------------------------------------------------------
----Creación de llaves foraneas
--------------------------------------------------------------------------------
	SET search_path = mod_estructuracomp, pg_catalog;	
	
ALTER TABLE mod_estructuracomp.dat_subordinacion
  ADD CONSTRAINT fk_hijo_fila FOREIGN KEY (idhijo)
      REFERENCES mod_estructuracomp.nom_filaestruc (idfila) MATCH SIMPLE
      ON UPDATE CASCADE ON DELETE CASCADE;

ALTER TABLE mod_estructuracomp.dat_subordinacion
  ADD CONSTRAINT fk_nopm_subordinacion FOREIGN KEY (idnomsubordinacion)
      REFERENCES mod_estructuracomp.nom_subordinacion (idnomsubordinacion) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE NO ACTION;

ALTER TABLE mod_estructuracomp.dat_subordinacion
  ADD CONSTRAINT fk_padre_fila FOREIGN KEY (idpadre)
      REFERENCES mod_estructuracomp.nom_filaestruc (idfila) MATCH SIMPLE
      ON UPDATE CASCADE ON DELETE CASCADE;	  


ALTER TABLE ONLY dat_tecnica
    ADD CONSTRAINT dat_tecnica_fk FOREIGN KEY (idnomtecnica) REFERENCES nom_tecnica(idtecnica);


--
-- TOC entry 3597 (class 2606 OID 1540369)
-- Dependencies: 3498 2886 2886
-- Name: fk_anterior; Type: FK CONSTRAINT; Schema: mod_estructuracomp; Owner: -
--

ALTER TABLE ONLY nom_gradomilit
    ADD CONSTRAINT fk_anterior FOREIGN KEY (anterior) REFERENCES nom_gradomilit(idgradomilit);


--
-- TOC entry 3575 (class 2606 OID 1540374)
-- Dependencies: 3430 2863 2872
-- Name: fk_cargo; Type: FK CONSTRAINT; Schema: mod_estructuracomp; Owner: -
--

ALTER TABLE ONLY dat_puesto
    ADD CONSTRAINT fk_cargo FOREIGN KEY (idcargo) REFERENCES dat_cargo(idcargo) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 3572 (class 2606 OID 1540379)
-- Dependencies: 3430 2863 2870
-- Name: fk_dat_cargo; Type: FK CONSTRAINT; Schema: mod_estructuracomp; Owner: -
--

ALTER TABLE ONLY dat_modcargo
    ADD CONSTRAINT fk_dat_cargo FOREIGN KEY (id_cargo) REFERENCES dat_cargo(idcargo);


ALTER TABLE ONLY dat_cargomtar
    ADD CONSTRAINT fk_dat_cargomilit FOREIGN KEY (idcargo) REFERENCES dat_cargo(idcargo) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 3544 (class 2606 OID 1540394)
-- Dependencies: 3430 2863 2864
-- Name: fk_dat_cargomilit; Type: FK CONSTRAINT; Schema: mod_estructuracomp; Owner: -
--

ALTER TABLE ONLY dat_cargocivil
    ADD CONSTRAINT fk_dat_cargomilit FOREIGN KEY (idcargo) REFERENCES dat_cargo(idcargo) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 3545 (class 2606 OID 1540399)
-- Dependencies: 3484 2880 2864
-- Name: fk_dat_categ_cargocivil; Type: FK CONSTRAINT; Schema: mod_estructuracomp; Owner: -
--

ALTER TABLE ONLY dat_cargocivil
    ADD CONSTRAINT fk_dat_categ_cargocivil FOREIGN KEY (idcategcivil) REFERENCES nom_categcivil(idcategcivil);


--
-- TOC entry 3563 (class 2606 OID 1540404)
-- Dependencies: 3451 2869 2869
-- Name: fk_dat_estoppadre; Type: FK CONSTRAINT; Schema: mod_estructuracomp; Owner: -
--

ALTER TABLE ONLY dat_estructuraop
    ADD CONSTRAINT fk_dat_estoppadre FOREIGN KEY (idpadre) REFERENCES dat_estructuraop(idestructuraop) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 3576 (class 2606 OID 1540409)
-- Dependencies: 3451 2869 2873
-- Name: fk_dat_estructura; Type: FK CONSTRAINT; Schema: mod_estructuracomp; Owner: -
--

ALTER TABLE ONLY dat_tecnica
    ADD CONSTRAINT fk_dat_estructura FOREIGN KEY (idestructura) REFERENCES dat_estructuraop(idestructuraop) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 3535 (class 2606 OID 1540414)
-- Dependencies: 3447 2868 2861
-- Name: fk_dat_estructura_agrup; Type: FK CONSTRAINT; Schema: mod_estructuracomp; Owner: -
--

ALTER TABLE ONLY dat_agrupacionest
    ADD CONSTRAINT fk_dat_estructura_agrup FOREIGN KEY (idestructura) REFERENCES dat_estructura(idestructura);


--
-- TOC entry 3537 (class 2606 OID 1540419)
-- Dependencies: 3451 2869 2862
-- Name: fk_dat_estructuraop; Type: FK CONSTRAINT; Schema: mod_estructuracomp; Owner: -
--

ALTER TABLE ONLY dat_agrupacionestop
    ADD CONSTRAINT fk_dat_estructuraop FOREIGN KEY (idestructuraop) REFERENCES dat_estructuraop(idestructuraop);


--
-- TOC entry 3574 (class 2606 OID 1540424)
-- Dependencies: 3522 2897 2871
-- Name: fk_dat_modulos; Type: FK CONSTRAINT; Schema: mod_estructuracomp; Owner: -
--

ALTER TABLE ONLY dat_modulos
    ADD CONSTRAINT fk_dat_modulos FOREIGN KEY (idtecnica) REFERENCES nom_tecnica(idtecnica);


--
-- TOC entry 3564 (class 2606 OID 1540429)
-- Dependencies: 3447 2868 2869
-- Name: fk_estructura; Type: FK CONSTRAINT; Schema: mod_estructuracomp; Owner: -
--

ALTER TABLE ONLY dat_estructuraop
    ADD CONSTRAINT fk_estructura FOREIGN KEY (idestructura) REFERENCES dat_estructura(idestructura) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 3556 (class 2606 OID 1540434)
-- Dependencies: 3447 2868 2868
-- Name: fk_estructura_padre; Type: FK CONSTRAINT; Schema: mod_estructuracomp; Owner: -
--

ALTER TABLE ONLY dat_estructura
    ADD CONSTRAINT fk_estructura_padre FOREIGN KEY (idpadre) REFERENCES dat_estructura(idestructura) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 3538 (class 2606 OID 1540439)
-- Dependencies: 3451 2869 2863
-- Name: fk_estructuraop; Type: FK CONSTRAINT; Schema: mod_estructuracomp; Owner: -
--

ALTER TABLE ONLY dat_cargo
    ADD CONSTRAINT fk_estructuraop FOREIGN KEY (idestructuraop) REFERENCES dat_estructuraop(idestructuraop) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 3536 (class 2606 OID 1540444)
-- Dependencies: 3465 2874 2862
-- Name: fk_nom_agrupacion; Type: FK CONSTRAINT; Schema: mod_estructuracomp; Owner: -
--

ALTER TABLE ONLY dat_agrupacionestop
    ADD CONSTRAINT fk_nom_agrupacion FOREIGN KEY (idagrupacion) REFERENCES nom_agrupacion(idagrupacion);


--
-- TOC entry 3534 (class 2606 OID 1540449)
-- Dependencies: 3465 2874 2861
-- Name: fk_nom_agrupaciones; Type: FK CONSTRAINT; Schema: mod_estructuracomp; Owner: -
--

ALTER TABLE ONLY dat_agrupacionest
    ADD CONSTRAINT fk_nom_agrupaciones FOREIGN KEY (idagrupacion) REFERENCES nom_agrupacion(idagrupacion);


--
-- TOC entry 3588 (class 2606 OID 1540454)
-- Dependencies: 3471 2876 2878
-- Name: fk_nom_calificador_cargocivil; Type: FK CONSTRAINT; Schema: mod_estructuracomp; Owner: -
--

ALTER TABLE ONLY nom_cargocivil
    ADD CONSTRAINT fk_nom_calificador_cargocivil FOREIGN KEY (idcalificador) REFERENCES nom_calificador_cargo(idcalificador);


--
-- TOC entry 3593 (class 2606 OID 1540459)
-- Dependencies: 3471 2876 2879
-- Name: fk_nom_calificador_cargomilitar; Type: FK CONSTRAINT; Schema: mod_estructuracomp; Owner: -
--

ALTER TABLE ONLY nom_cargomilitar
    ADD CONSTRAINT fk_nom_calificador_cargomilitar FOREIGN KEY (idcalificador) REFERENCES nom_calificador_cargo(idcalificador);


--
-- TOC entry 3605 (class 2606 OID 1540464)
-- Dependencies: 3473 2877 2901
-- Name: fk_nom_campo; Type: FK CONSTRAINT; Schema: mod_estructuracomp; Owner: -
--

ALTER TABLE ONLY nom_valor_defecto
    ADD CONSTRAINT fk_nom_campo FOREIGN KEY (idcampo) REFERENCES nom_campoestruc(idcampo) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 3607 (class 2606 OID 1540469)
-- Dependencies: 3473 2877 2902
-- Name: fk_nom_campo; Type: FK CONSTRAINT; Schema: mod_estructuracomp; Owner: -
--

ALTER TABLE ONLY nom_valorestruc
    ADD CONSTRAINT fk_nom_campo FOREIGN KEY (idcampo) REFERENCES nom_campoestruc(idcampo) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 3546 (class 2606 OID 1540474)
-- Dependencies: 3479 2878 2864
-- Name: fk_nom_cargocivil; Type: FK CONSTRAINT; Schema: mod_estructuracomp; Owner: -
--

ALTER TABLE ONLY dat_cargocivil
    ADD CONSTRAINT fk_nom_cargocivil FOREIGN KEY (idcargociv) REFERENCES nom_cargocivil(idcargociv);


--
-- TOC entry 3552 (class 2606 OID 1540479)
-- Dependencies: 3482 2879 2865
-- Name: fk_nom_cargomtar; Type: FK CONSTRAINT; Schema: mod_estructuracomp; Owner: -
--

ALTER TABLE ONLY dat_cargomtar
    ADD CONSTRAINT fk_nom_cargomtar FOREIGN KEY (idnomcargomilitar) REFERENCES nom_cargomilitar(idcargomilitar);


--
-- TOC entry 3587 (class 2606 OID 1540484)
-- Dependencies: 3484 2880 2878
-- Name: fk_nom_categcivil; Type: FK CONSTRAINT; Schema: mod_estructuracomp; Owner: -
--

ALTER TABLE ONLY nom_cargocivil
    ADD CONSTRAINT fk_nom_categcivil FOREIGN KEY (idcategcivil) REFERENCES nom_categcivil(idcategcivil);


--
-- TOC entry 3586 (class 2606 OID 1540489)
-- Dependencies: 3486 2881 2878
-- Name: fk_nom_categocup; Type: FK CONSTRAINT; Schema: mod_estructuracomp; Owner: -
--

ALTER TABLE ONLY nom_cargocivil
    ADD CONSTRAINT fk_nom_categocup FOREIGN KEY (idcategocup) REFERENCES nom_categocup(idcategocup);


--
-- TOC entry 3581 (class 2606 OID 1540494)
-- Dependencies: 3486 2881 2876
-- Name: fk_nom_categocup; Type: FK CONSTRAINT; Schema: mod_estructuracomp; Owner: -
--

ALTER TABLE ONLY nom_calificador_cargo
    ADD CONSTRAINT fk_nom_categocup FOREIGN KEY (idcategocup) REFERENCES nom_categocup(idcategocup);


--
-- TOC entry 3547 (class 2606 OID 1540499)
-- Dependencies: 3488 2882 2864
-- Name: fk_nom_clasificacion_cargo; Type: FK CONSTRAINT; Schema: mod_estructuracomp; Owner: -
--

ALTER TABLE ONLY dat_cargocivil
    ADD CONSTRAINT fk_nom_clasificacion_cargo FOREIGN KEY (idclasificacion) REFERENCES nom_clasificacion_cargo(idclasificacion);


ALTER TABLE ONLY nom_organo
    ADD CONSTRAINT fk_nom_eav FOREIGN KEY (idnomeav) REFERENCES nom_nomencladoreavestruc(idnomeav) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 3598 (class 2606 OID 1540529)
-- Dependencies: 3492 2884 2887
-- Name: fk_nom_escalasalarial; Type: FK CONSTRAINT; Schema: mod_estructuracomp; Owner: -
--

ALTER TABLE ONLY nom_grupocomple
    ADD CONSTRAINT fk_nom_escalasalarial FOREIGN KEY (idescalasalarial) REFERENCES nom_escalasalarial(idescalasalarial);


--
-- TOC entry 3548 (class 2606 OID 1540534)
-- Dependencies: 3492 2884 2864
-- Name: fk_nom_escalasalarial; Type: FK CONSTRAINT; Schema: mod_estructuracomp; Owner: -
--

ALTER TABLE ONLY dat_cargocivil
    ADD CONSTRAINT fk_nom_escalasalarial FOREIGN KEY (idescalasalarial) REFERENCES nom_escalasalarial(idescalasalarial);


--
-- TOC entry 3604 (class 2606 OID 1540539)
-- Dependencies: 3492 2884 2896
-- Name: fk_nom_escalasalarial; Type: FK CONSTRAINT; Schema: mod_estructuracomp; Owner: -
--

ALTER TABLE ONLY nom_salario
    ADD CONSTRAINT fk_nom_escalasalarial FOREIGN KEY (idescalasalarial) REFERENCES nom_escalasalarial(idescalasalarial);

	
ALTER TABLE ONLY nom_filaestruc
    ADD CONSTRAINT fk_nom_estructuraeav FOREIGN KEY (idnomeav) REFERENCES nom_nomencladoreavestruc(idnomeav) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 3606 (class 2606 OID 1540569)
-- Dependencies: 3494 2885 2902
-- Name: fk_nom_fila; Type: FK CONSTRAINT; Schema: mod_estructuracomp; Owner: -
--

ALTER TABLE ONLY nom_valorestruc
    ADD CONSTRAINT fk_nom_fila FOREIGN KEY (idfila) REFERENCES nom_filaestruc(idfila) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 3551 (class 2606 OID 1540574)
-- Dependencies: 3498 2886 2865
-- Name: fk_nom_gradomilitdat_cargomiliatar; Type: FK CONSTRAINT; Schema: mod_estructuracomp; Owner: -
--

ALTER TABLE ONLY dat_cargomtar
    ADD CONSTRAINT fk_nom_gradomilitdat_cargomiliatar FOREIGN KEY (idgradomilit) REFERENCES nom_gradomilit(idgradomilit);


--
-- TOC entry 3591 (class 2606 OID 1540579)
-- Dependencies: 3498 2886 2879
-- Name: fk_nom_gradomilitnom_gradomiliatar; Type: FK CONSTRAINT; Schema: mod_estructuracomp; Owner: -
--

ALTER TABLE ONLY nom_cargomilitar
    ADD CONSTRAINT fk_nom_gradomilitnom_gradomiliatar FOREIGN KEY (idgradomilit) REFERENCES nom_gradomilit(idgradomilit);


--
-- TOC entry 3549 (class 2606 OID 1540584)
-- Dependencies: 3500 2887 2864
-- Name: fk_nom_grupocomple; Type: FK CONSTRAINT; Schema: mod_estructuracomp; Owner: -
--

ALTER TABLE ONLY dat_cargocivil
    ADD CONSTRAINT fk_nom_grupocomple FOREIGN KEY (idgrupocomple) REFERENCES nom_grupocomple(idgrupocomplejidad);


--
-- TOC entry 3584 (class 2606 OID 1540589)
-- Dependencies: 3500 2887 2878
-- Name: fk_nom_grupocomple; Type: FK CONSTRAINT; Schema: mod_estructuracomp; Owner: -
--

ALTER TABLE ONLY nom_cargocivil
    ADD CONSTRAINT fk_nom_grupocomple FOREIGN KEY (idgrupocomplejidad) REFERENCES nom_grupocomple(idgrupocomplejidad);


--
-- TOC entry 3603 (class 2606 OID 1540594)
-- Dependencies: 3500 2887 2896
-- Name: fk_nom_grupocomple; Type: FK CONSTRAINT; Schema: mod_estructuracomp; Owner: -
--

ALTER TABLE ONLY nom_salario
    ADD CONSTRAINT fk_nom_grupocomple FOREIGN KEY (idgrupocomplejidad) REFERENCES nom_grupocomple(idgrupocomplejidad);


--
-- TOC entry 3540 (class 2606 OID 1540599)
-- Dependencies: 3500 2887 2863
-- Name: fk_nom_grupocomplejidad; Type: FK CONSTRAINT; Schema: mod_estructuracomp; Owner: -
--

ALTER TABLE ONLY dat_cargo
    ADD CONSTRAINT fk_nom_grupocomplejidad FOREIGN KEY (idgrupocomplejidad) REFERENCES nom_grupocomple(idgrupocomplejidad);


--
-- TOC entry 3596 (class 2606 OID 1540604)
-- Dependencies: 3502 2888 2886
-- Name: fk_nom_gsubcateg; Type: FK CONSTRAINT; Schema: mod_estructuracomp; Owner: -
--

ALTER TABLE ONLY nom_gradomilit
    ADD CONSTRAINT fk_nom_gsubcateg FOREIGN KEY (idgsubcateg) REFERENCES nom_gsubcateg(idgsubcateg);


--
-- TOC entry 3571 (class 2606 OID 1540609)
-- Dependencies: 3504 2889 2870
-- Name: fk_nom_modulo; Type: FK CONSTRAINT; Schema: mod_estructuracomp; Owner: -
--

ALTER TABLE ONLY dat_modcargo
    ADD CONSTRAINT fk_nom_modulo FOREIGN KEY (idmodulo) REFERENCES nom_modulo(idmodulo);


--
-- TOC entry 3573 (class 2606 OID 1540614)
-- Dependencies: 3504 2889 2871
-- Name: fk_nom_modulo; Type: FK CONSTRAINT; Schema: mod_estructuracomp; Owner: -
--

ALTER TABLE ONLY dat_modulos
    ADD CONSTRAINT fk_nom_modulo FOREIGN KEY (idmodulo) REFERENCES nom_modulo(idmodulo);


--
-- TOC entry 3541 (class 2606 OID 1540619)
-- Dependencies: 3504 2889 2863
-- Name: fk_nom_modulocargo; Type: FK CONSTRAINT; Schema: mod_estructuracomp; Owner: -
--

ALTER TABLE ONLY dat_cargo
    ADD CONSTRAINT fk_nom_modulocargo FOREIGN KEY (idmodulo) REFERENCES nom_modulo(idmodulo);


--
-- TOC entry 3583 (class 2606 OID 1540624)
-- Dependencies: 3506 2890 2878
-- Name: fk_nom_nivel_utilizacion; Type: FK CONSTRAINT; Schema: mod_estructuracomp; Owner: -
--

ALTER TABLE ONLY nom_cargocivil
    ADD CONSTRAINT fk_nom_nivel_utilizacion FOREIGN KEY (idnivelutilizacion) REFERENCES nom_nivel_utilizacion(idnivelutilizacion);


--
-- TOC entry 3559 (class 2606 OID 1540629)
-- Dependencies: 3508 2891 2868
-- Name: fk_nom_nivelestr; Type: FK CONSTRAINT; Schema: mod_estructuracomp; Owner: -
--

ALTER TABLE ONLY dat_estructura
    ADD CONSTRAINT fk_nom_nivelestr FOREIGN KEY (idnivelestr) REFERENCES nom_nivelestr(idnivelestr) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 3567 (class 2606 OID 1540634)
-- Dependencies: 3508 2891 2869
-- Name: fk_nom_nivelestr; Type: FK CONSTRAINT; Schema: mod_estructuracomp; Owner: -
--

ALTER TABLE ONLY dat_estructuraop
    ADD CONSTRAINT fk_nom_nivelestr FOREIGN KEY (idnivelestr) REFERENCES nom_nivelestr(idnivelestr) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 3579 (class 2606 OID 1540639)
-- Dependencies: 3510 2892 2875
-- Name: fk_nom_nomencladoreav_destino; Type: FK CONSTRAINT; Schema: mod_estructuracomp; Owner: -
--

ALTER TABLE ONLY nom_aristaeav
    ADD CONSTRAINT fk_nom_nomencladoreav_destino FOREIGN KEY (iddestino) REFERENCES nom_nomencladoreavestruc(idnomeav) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 3578 (class 2606 OID 1540644)
-- Dependencies: 3510 2892 2875
-- Name: fk_nom_nomencladoreav_origen; Type: FK CONSTRAINT; Schema: mod_estructuracomp; Owner: -
--

ALTER TABLE ONLY nom_aristaeav
    ADD CONSTRAINT fk_nom_nomencladoreav_origen FOREIGN KEY (idorigen) REFERENCES nom_nomencladoreavestruc(idnomeav) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 3568 (class 2606 OID 1540649)
-- Dependencies: 3512 2893 2869
-- Name: fk_nom_organo; Type: FK CONSTRAINT; Schema: mod_estructuracomp; Owner: -
--

ALTER TABLE ONLY dat_estructuraop
    ADD CONSTRAINT fk_nom_organo FOREIGN KEY (idorgano) REFERENCES nom_organo(idorgano);


--
-- TOC entry 3560 (class 2606 OID 1540654)
-- Dependencies: 3512 2893 2868
-- Name: fk_nom_organo1; Type: FK CONSTRAINT; Schema: mod_estructuracomp; Owner: -
--

ALTER TABLE ONLY dat_estructura
    ADD CONSTRAINT fk_nom_organo1 FOREIGN KEY (idorgano) REFERENCES nom_organo(idorgano);


--
-- TOC entry 3600 (class 2606 OID 1540659)
-- Dependencies: 3508 2891 2893
-- Name: fk_nom_organo_nom_nivelestr; Type: FK CONSTRAINT; Schema: mod_estructuracomp; Owner: -
--

ALTER TABLE ONLY nom_organo
    ADD CONSTRAINT fk_nom_organo_nom_nivelestr FOREIGN KEY (idnivelestr) REFERENCES nom_nivelestr(idnivelestr);


--
-- TOC entry 3569 (class 2606 OID 1540664)
-- Dependencies: 3514 2894 2869
-- Name: fk_nom_prefijo; Type: FK CONSTRAINT; Schema: mod_estructuracomp; Owner: -
--

ALTER TABLE ONLY dat_estructuraop
    ADD CONSTRAINT fk_nom_prefijo FOREIGN KEY (idprefijo) REFERENCES nom_prefijo(idprefijo);


--
-- TOC entry 3561 (class 2606 OID 1540669)
-- Dependencies: 3514 2894 2868
-- Name: fk_nom_prefijo0; Type: FK CONSTRAINT; Schema: mod_estructuracomp; Owner: -
--

ALTER TABLE ONLY dat_estructura
    ADD CONSTRAINT fk_nom_prefijo0 FOREIGN KEY (idprefijo) REFERENCES nom_prefijo(idprefijo);


--
-- TOC entry 3542 (class 2606 OID 1540674)
-- Dependencies: 3514 2894 2863
-- Name: fk_nom_prefijo_cargo; Type: FK CONSTRAINT; Schema: mod_estructuracomp; Owner: -
--

ALTER TABLE ONLY dat_cargo
    ADD CONSTRAINT fk_nom_prefijo_cargo FOREIGN KEY (idprefijo) REFERENCES nom_prefijo(idprefijo);


--
-- TOC entry 3590 (class 2606 OID 1540679)
-- Dependencies: 3516 2895 2879
-- Name: fk_nom_prep_cargomilitar; Type: FK CONSTRAINT; Schema: mod_estructuracomp; Owner: -
--

ALTER TABLE ONLY nom_cargomilitar
    ADD CONSTRAINT fk_nom_prep_cargomilitar FOREIGN KEY (idprepmilitar) REFERENCES nom_prepmilitar(idprepmilitar);


--
-- TOC entry 3550 (class 2606 OID 1540684)
-- Dependencies: 3520 2896 2864
-- Name: fk_nom_salario; Type: FK CONSTRAINT; Schema: mod_estructuracomp; Owner: -
--

ALTER TABLE ONLY dat_cargocivil
    ADD CONSTRAINT fk_nom_salario FOREIGN KEY (idsalario) REFERENCES nom_salario(idsalario);


--
-- TOC entry 3599 (class 2606 OID 1540689)
-- Dependencies: 3522 2897 2889
-- Name: fk_nom_tecnicanom_modulo; Type: FK CONSTRAINT; Schema: mod_estructuracomp; Owner: -
--

ALTER TABLE ONLY nom_modulo
    ADD CONSTRAINT fk_nom_tecnicanom_modulo FOREIGN KEY (idtecnica) REFERENCES nom_tecnica(idtecnica);


--
-- TOC entry 3580 (class 2606 OID 1540694)
-- Dependencies: 3524 2898 2876
-- Name: fk_nom_tipo_calificador; Type: FK CONSTRAINT; Schema: mod_estructuracomp; Owner: -
--

ALTER TABLE ONLY nom_calificador_cargo
    ADD CONSTRAINT fk_nom_tipo_calificador FOREIGN KEY (idtipocalificador) REFERENCES nom_tipo_calificador(idtipocalificador);


--
-- TOC entry 3543 (class 2606 OID 1540699)
-- Dependencies: 3526 2899 2863
-- Name: fk_nom_tipocifracargo; Type: FK CONSTRAINT; Schema: mod_estructuracomp; Owner: -
--

ALTER TABLE ONLY dat_cargo
    ADD CONSTRAINT fk_nom_tipocifracargo FOREIGN KEY (idtipocifra) REFERENCES nom_tipocifra(idtipocifra);


--
-- TOC entry 3589 (class 2606 OID 1540704)
-- Dependencies: 3526 2899 2879
-- Name: fk_nom_tipocifracargomiliatar; Type: FK CONSTRAINT; Schema: mod_estructuracomp; Owner: -
--

ALTER TABLE ONLY nom_cargomilitar
    ADD CONSTRAINT fk_nom_tipocifracargomiliatar FOREIGN KEY (idtipocifra) REFERENCES nom_tipocifra(idtipocifra);


--
-- TOC entry 3554 (class 2606 OID 1540709)
-- Dependencies: 3528 2900 2866
-- Name: fk_nom_tipodoc; Type: FK CONSTRAINT; Schema: mod_estructuracomp; Owner: -
--

ALTER TABLE ONLY dat_docoficial
    ADD CONSTRAINT fk_nom_tipodoc FOREIGN KEY (idtipodoc) REFERENCES nom_tipodoc(iddocoficial);


--
-- TOC entry 3562 (class 2606 OID 1540714)
-- Dependencies: 3494 2885 2868
-- Name: fk_nomfila; Type: FK CONSTRAINT; Schema: mod_estructuracomp; Owner: -
--

ALTER TABLE ONLY dat_estructura
    ADD CONSTRAINT fk_nomfila FOREIGN KEY (idestructura) REFERENCES nom_filaestruc(idfila) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 3570 (class 2606 OID 1540719)
-- Dependencies: 3494 2885 2869
-- Name: fk_nomfila; Type: FK CONSTRAINT; Schema: mod_estructuracomp; Owner: -
--

ALTER TABLE ONLY dat_estructuraop
    ADD CONSTRAINT fk_nomfila FOREIGN KEY (idestructuraop) REFERENCES nom_filaestruc(idfila) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 3595 (class 2606 OID 1540724)
-- Dependencies: 3498 2886 2886
-- Name: fk_sucesor; Type: FK CONSTRAINT; Schema: mod_estructuracomp; Owner: -
--

ALTER TABLE ONLY nom_gradomilit
    ADD CONSTRAINT fk_sucesor FOREIGN KEY (sucesor) REFERENCES nom_gradomilit(idgradomilit);


--
-- TOC entry 3582 (class 2606 OID 1540729)
-- Dependencies: 3510 2892 2877
-- Name: fknom_campoe39926; Type: FK CONSTRAINT; Schema: mod_estructuracomp; Owner: -
--

ALTER TABLE ONLY nom_campoestruc
    ADD CONSTRAINT fknom_campoe39926 FOREIGN KEY (idnomeav) REFERENCES nom_nomencladoreavestruc(idnomeav) ON UPDATE CASCADE ON DELETE CASCADE;


-------------------------------------------------------------------------------
----Creación de funciones
--------------------------------------------------------------------------------
	SET search_path = mod_estructuracomp, pg_catalog;

	CREATE OR REPLACE FUNCTION mod_estructuracomp."Nemury_ContarNietosxEAVxidOrgPadre"("IdPadre" numeric, "IdOrgano" numeric, "Eav" numeric)
  RETURNS numeric AS
$BODY$
/* New function body */
DECLARE
Result numeric;
BEGIN

Result :=( SELECT count(h1.*)
from
     mod_estructuracomp.dat_estructura p,
     mod_estructuracomp.dat_estructura h,
     mod_estructuracomp.dat_estructura h1
where
      p.idestructura=$1
     and h.lft > p.lft   AND h.lft < p.rgt
     and h.idorgano =$2
     and h1.lft > h.lft   AND h1.lft < h.rgt
     and h1.idnomeav=$3
     
);


return  Result;
END
$BODY$
  LANGUAGE 'plpgsql' ;

CREATE OR REPLACE FUNCTION mod_estructuracomp.cant_entida_unidades_agrupacion(nombre_nivel character varying, codigo_nivel character varying)
  RETURNS SETOF mod_estructuracomp.cant_entida_unidades_agrupacion AS
$BODY$
/* Funcion para darle solucion al reporte Cantidad de
Entidades y Unidades por Agrupaciones de un Nivel 1 */

declare
result record;
id numeric;
EavNivel numeric;
EavAgrup numeric;
EavEntidad numeric;
EavUnidad numeric;
CampoCategoria numeric;

BEGIN

id         =   mod_estructuracomp."f_rep_buscaridestructura"($1);
EavNivel   =   mod_estructuracomp."f_rep_buscarideav"('Nivel 1');
EavAgrup   =   mod_estructuracomp."f_rep_buscarideav"('Agrupación');
EavEntidad =   mod_estructuracomp."f_rep_buscarideav"('Entidad');
EavUnidad  =   mod_estructuracomp."f_rep_buscarideav"('Unidad');
CampoCategoria= mod_estructuracomp."f_buscaridcampo"('Categoria',EavAgrup);


FOR result IN
select 
        p.denominacion,
        p.abreviatura,
        p.codigo,
        h.denominacion,
        h.abreviatura,
        o.denorgano,
        v.valor,
        mod_estructuracomp."f_rep_nemurycontarhijosxeav1"(h.idestructura,EavEntidad),
        mod_estructuracomp."f_rep_nemurycontarhijosxeav1"(h.idestructura,EavUnidad)
from
         mod_estructuracomp.dat_estructura p
INNER join
         mod_estructuracomp.dat_estructura h
ON
         (h.lft>p.lft and h.lft<p.rgt)
         and ((p.idestructura=id) or (p.codigo=$2))
         and (h.idnomeav=EavAgrup)
INNER join
         mod_estructuracomp.nom_organo o
on
         h.idorgano=o.idorgano
inner join
         mod_estructuracomp.nom_filaestruc f
on
         f.idfila=h.idestructura
LEFT join
         mod_estructuracomp.nom_valorestruc v
on
         (v.idfila=f.idfila) and
         (v.idcampo= CampoCategoria)

/*where
     
     and h.idorgano=o.idorgano
     and h.lft>p.lft and h.lft<p.rgt
     and p.idestructura=id
*/
group by o.denorgano,
         p.abreviatura,
         h.abreviatura,
         p.denominacion,
         p.codigo,
         h.denominacion,
         h.idestructura,
         v.valor


loop
return next result;
end loop;
END
$BODY$
LANGUAGE 'plpgsql' VOLATILE CALLED ON NULL INPUT SECURITY INVOKER;



CREATE OR REPLACE FUNCTION mod_estructuracomp."f_ getHijosEstructura"(idpadre numeric)
  RETURNS SETOF record AS
$BODY$
/* New function body */

/* New function body */
DECLARE

estructura record;

BEGIN

for estructura in
select  e.idestructura, e.idorgano, e.idprefijo, e.denominacion,
e.abreviatura, e.fechaini, e.fechafin, e.lft, e.rgt
from  mod_estructuracomp.dat_estructura e
where e.idpadre = idpadre AND e.idestructura != e.idpadre loop

return next estructura;

end loop;

END
$BODY$
  LANGUAGE 'plpgsql' ;




CREATE OR REPLACE FUNCTION mod_estructuracomp.f_adicinardom(pos bigint, bin bigint)
  RETURNS numeric AS
$BODY$
/* New function body *//* New function body */

DECLARE

binario varchar := convertir($2);
tambin integer := (select length(binario));
val varchar[];
j integer := 1;
k integer := 0;
i integer := 1;
z integer := tambin;
cad varchar;
num numeric := 0;


BEGIN

if exitedominio($1,$2) = TRUE THEN

    RAISE EXCEPTION 'Ya existe ese domino';

ELSE


  WHILE tambin >= 1 LOOP

   if j = $1 THEN
       val[z] := 1 ;
       j := j + 1;
       tambin := tambin - 1;
       z := z - 1;

   else

   val[z] := (select substr( binario , z , i  )) ;
   j := j + 1;
   tambin := tambin - 1;
   z := z - 1;

   end if;

  END LOOP;

END if;

cad := (SELECT array_to_string(val,''));

num := (SELECT converdecimal(cad));

return num;

END
$BODY$
  LANGUAGE 'plpgsql' ;




CREATE OR REPLACE FUNCTION mod_estructuracomp.f_buscaridcampo(nombre character varying, "idEAV" numeric)
  RETURNS numeric AS
$BODY$
/* New function body */

DECLARE
buscar numeric;
BEGIN
buscar := (SELECT idcampo FROM mod_estructuracomp.nom_campoestruc
       WHERE mod_estructuracomp.nom_campoestruc.idnomeav = $2
             AND mod_estructuracomp.nom_campoestruc.nombre = $1);
        IF buscar IS NULL THEN
              RETURN 0;
        ELSE
              RETURN buscar;
        END IF;
END
$BODY$
  LANGUAGE 'plpgsql' ;

CREATE OR REPLACE FUNCTION mod_estructuracomp.f_cargosporestructura(denominacion_estructura character varying)
  RETURNS numeric AS
$BODY$
DECLARE

res numeric;

BEGIN

res := (SELECT
  count(c.idcargo)

FROM
 mod_estructuracomp.dat_estructura e
 INNER JOIN  mod_estructuracomp.dat_estructuraop op ON (e.idestructura = op.idestructura)
 INNER JOIN mod_estructuracomp.dat_cargo c ON (op.idestructuraop = c.idestructuraop)

WHERE
    e.denominacion = $1
);
return res;

END
$BODY$
  LANGUAGE 'plpgsql';



CREATE OR REPLACE FUNCTION mod_estructuracomp.f_contarcargosporgupoescala(grupoescala character varying, categocupacional character varying, denominacion_estructura character varying, codigo character varying)
  RETURNS numeric AS
$BODY$
DECLARE

num numeric;

BEGIN

num := (SELECT
 count(c.idcargo)
  
FROM
 mod_estructuracomp.dat_estructura e
 INNER JOIN mod_estructuracomp.dat_estructuraop op ON (e.idestructura = op.idestructura)
 INNER JOIN mod_estructuracomp.dat_cargo c ON (op.idestructuraop = c.idestructuraop)
 INNER JOIN mod_estructuracomp.dat_cargocivil civ ON (c.idcargo = civ.idcargo)
 INNER JOIN mod_estructuracomp.nom_grupocomple es ON (civ.idgrupocomple = es.idgrupocomplejidad)
 INNER JOIN mod_estructuracomp.nom_cargocivil nciv ON (civ.idcargociv = nciv.idcargociv)
 INNER JOIN mod_estructuracomp.nom_categocup cat ON (nciv.idcategocup = cat.idcategocup)
WHERE
   es.denominacion = $1 and cat.dencategocup = $2 and e.denominacion = $3
  );

  return num;
 END
$BODY$
  LANGUAGE 'plpgsql';



CREATE OR REPLACE FUNCTION mod_estructuracomp."f_existeEstructuraop"(id_estructuraop numeric)
  RETURNS boolean AS
$BODY$
DECLARE
buscar mod_estructuracomp.dat_estructuraop.idestructuraop%type;
BEGIN
SELECT INTO buscar id_estructuraop FROM mod_estructuracomp.dat_estructuraop
       WHERE mod_estructuracomp.dat_estructuraop.idestructuraop = id_estructuraop;
       IF found THEN
        RETURN TRUE;
  ELSE
       RETURN FALSE;
        END IF;
END
$BODY$
  LANGUAGE 'plpgsql';




CREATE OR REPLACE FUNCTION mod_estructuracomp."f_getEstructurasInternas"(idestructura numeric, raiz boolean)
  RETURNS SETOF record AS
$BODY$
declare

internas record;
internasraiz record;

begin

if raiz is false THEN

for internas in
select p.denominacion, p.abreviatura, p.fechaini,
p.fechafin, p.idorgano
from mod_estructuracomp.dat_estructura e
inner join mod_estructuracomp.dat_estructuraop p
on e.idestructura = p.idestructura
where e.idestructura = idestructura loop

return next internas;

end loop;
else

for internasraiz in
select p.denominacion, p.abreviatura, p.fechaini, p.fechafin,
p.idorgano
from mod_estructuracomp.dat_estructura e
inner join mod_estructuracomp.dat_estructuraop p
on e.idestructura = p.idestructura
where e.idestructura = idestructura and p.idestructuraop = p.idpadre loop

return next internasraiz;

end loop;
end if;

END
$BODY$
  LANGUAGE 'plpgsql';

CREATE OR REPLACE FUNCTION mod_estructuracomp."f_getHijosInterna"(idpadre numeric)
  RETURNS SETOF record AS
$BODY$
/* New function body */

DECLARE

internasop record;

BEGIN

for internasop in
select  p.idestructuraop, p.idorgano, p.idprefijo, p.denominacion,
p.abreviatura, p.fechaini, p.fechafin, p.lft, p.rgt
from  mod_estructuracomp.dat_estructuraop p
where p.idpadre = idpadre AND p.idestructuraop != p.idpadre loop

return next internasop;

end loop;

END
$BODY$
  LANGUAGE 'plpgsql' ;



-- Function: mod_estructuracomp.f_getdatosestructura(numeric)

-- DROP FUNCTION mod_estructuracomp.f_getdatosestructura(numeric);

CREATE OR REPLACE FUNCTION mod_estructuracomp.f_getdatosestructura(estructura numeric)
  RETURNS SETOF record AS
$BODY$
declare
--padre mod_estructuracomp.dat_estructura.idpadre%type;
valor record;
valor2 record;

begin


if estructura is null then

for valor in select  t.idestructura ,t.denominacion, t.abreviatura, t.fechafin, t.fechafin,
  t.idorgano, t.idprefijo, t.idpadre 
  from mod_estructuracomp.dat_estructura t
  where t.idestructura = t.idpadre loop
   return next valor;
   end loop;
else

for valor2 in select  r.idestructura ,r.denominacion, r.abreviatura, r.fechafin, r.fechafin,
  r.idorgano, r.idprefijo, r.idpadre 
  from mod_estructuracomp.dat_estructura r
  where r.idpadre = estructura loop
  
 return next valor2;
end loop;

end if;

end
$BODY$
  LANGUAGE 'plpgsql';



-- Function: mod_estructuracomp."f_listadoEstructuras"(numeric, numeric)

-- DROP FUNCTION mod_estructuracomp."f_listadoEstructuras"(numeric, numeric);

CREATE OR REPLACE FUNCTION mod_estructuracomp."f_listadoEstructuras"(comienzo numeric, fin numeric)
  RETURNS SETOF record AS
$BODY$
/* New function body */

DECLARE

listado record;

BEGIN

for listado in
select e.idestructura
from mod_estructuracomp.dat_estructura e
where e.idestructura  > comienzo AND e.idestructura < fin
order by e.idestructura
loop

return next listado;

end loop;

END
$BODY$
  LANGUAGE 'plpgsql';



-- Function: mod_estructuracomp."f_listadoEstructurasInternas"(numeric, numeric)

-- DROP FUNCTION mod_estructuracomp."f_listadoEstructurasInternas"(numeric, numeric);

CREATE OR REPLACE FUNCTION mod_estructuracomp."f_listadoEstructurasInternas"(comienzo numeric, fin numeric)
  RETURNS SETOF record AS
$BODY$
/* New function body */

DECLARE

listadoop record;


BEGIN

for listadoop in
select p.idestructuraop
from mod_estructuracomp.dat_estructuraop p
where p.idestructuraop  > comienzo AND p.idestructuraop < fin
order by p.idestructuraop
loop

return next listadoop;

end loop;

END
$BODY$
  LANGUAGE 'plpgsql' ;



-- Function: mod_estructuracomp.f_rep_areas(character varying, character varying)

-- DROP FUNCTION mod_estructuracomp.f_rep_areas(character varying, character varying);

CREATE OR REPLACE FUNCTION mod_estructuracomp.f_rep_areas(denominacion_estructura character varying, denominacion_estructuraop character varying)
  RETURNS SETOF mod_estructuracomp.td_areas AS
$BODY$
/* New function body */

DECLARE

result record;

BEGIN

FOR result IN

SELECT

nciv.dencargociv,
ncat.abreviatura,
grucople.denominacion,
esc.abreviatura,
sal.salario,
e.idestructura,
o.idestructuraop

FROM

mod_estructuracomp.dat_estructura e
INNER JOIN mod_estructuracomp.dat_estructuraop o ON (e.idestructura = o.idestructura)
INNER JOIN mod_estructuracomp.dat_cargo c ON (o.idestructuraop = c.idestructuraop)
INNER JOIN mod_estructuracomp.dat_cargocivil civ ON (c.idcargo = civ.idcargo)
INNER JOIN mod_estructuracomp.nom_cargocivil nciv ON (civ.idcargociv = nciv.idcargociv)
INNER JOIN mod_estructuracomp.nom_categocup ncat ON (nciv.idcategocup = ncat.idcategocup)
INNER JOIN mod_estructuracomp.nom_grupocomple grucople ON (civ.idgrupocomple = grucople.idgrupocomplejidad)
INNER JOIN mod_estructuracomp.nom_escalasalarial esc ON (civ.idescalasalarial = esc.idescalasalarial)
INNER JOIN mod_estructuracomp.nom_salario sal ON (civ.idsalario = sal.idsalario)


WHERE  e.idestructura = mod_estructuracomp."f_rep_buscaridestructura"($1)
       AND o.idestructuraop =  mod_estructuracomp."f_rep_buscaridestructura"($2)

GROUP BY     nciv.dencargociv,
             ncat.abreviatura,
             grucople.denominacion,
             esc.abreviatura,
             sal.salario,
             e.idestructura,
             o.idestructuraop

ORDER BY    nciv.dencargociv asc

loop
return next result;
end loop;
END
$BODY$
  LANGUAGE 'plpgsql';



-- Function: mod_estructuracomp.f_rep_areascategorias(character varying, character varying)

-- DROP FUNCTION mod_estructuracomp.f_rep_areascategorias(character varying, character varying);

CREATE OR REPLACE FUNCTION mod_estructuracomp.f_rep_areascategorias(denominacion_area character varying, denominacion_categoria character varying)
  RETURNS numeric AS
$BODY$
DECLARE

suma numeric := 0;

BEGIN

suma := (SELECT  count(nciv.idcargociv)

FROM
 mod_estructuracomp.dat_cargo c
 --INNER JOIN mod_estructuracomp.dat_estructuraop op ON (c.idestructuraop = op.idestructuraop)
 INNER JOIN mod_estructuracomp.dat_cargocivil civ ON (  civ.idcargo=c.idcargo)
 INNER JOIN mod_estructuracomp.nom_cargocivil nciv ON (  nciv.idcargociv=civ.idcargociv)
 INNER JOIN mod_estructuracomp.nom_categocup cat ON (  cat.idcategocup=nciv.idcategocup)

WHERE
           c.idestructuraop = $1
           AND cat.idcategocup =mod_estructuracomp.f_rep_buscaridcategocup($2)
           
 );
           return suma;

END
$BODY$
  LANGUAGE 'plpgsql';



-- Function: mod_estructuracomp.f_rep_areascategorias1(numeric, character varying)

-- DROP FUNCTION mod_estructuracomp.f_rep_areascategorias1(numeric, character varying);

CREATE OR REPLACE FUNCTION mod_estructuracomp.f_rep_areascategorias1(id_area numeric, denominacion_categoria character varying)
  RETURNS numeric AS
$BODY$
DECLARE

suma numeric := 0;

BEGIN

suma := (SELECT  count(nciv.idcargociv)

FROM
 mod_estructuracomp.dat_cargo c
 
 INNER JOIN mod_estructuracomp.dat_cargocivil civ ON (  civ.idcargo=c.idcargo)
 INNER JOIN mod_estructuracomp.nom_cargocivil nciv ON (  nciv.idcargociv=civ.idcargociv)
 INNER JOIN mod_estructuracomp.nom_categocup cat ON (  cat.idcategocup=nciv.idcategocup)

WHERE
           c.idestructuraop = $1
           AND cat.idcategocup =mod_estructuracomp.f_rep_buscaridcategocup($2)
           
 );
           return suma;

END
$BODY$
  LANGUAGE 'plpgsql';



-- Function: mod_estructuracomp.f_rep_buscaridcalaificador(character varying)

-- DROP FUNCTION mod_estructuracomp.f_rep_buscaridcalaificador(character varying);

CREATE OR REPLACE FUNCTION mod_estructuracomp.f_rep_buscaridcalaificador(denominacion_calificador character varying)
  RETURNS numeric AS
$BODY$
/* New function body */


DECLARE
buscar numeric;
BEGIN
buscar := (SELECT idcalificador FROM mod_estructuracomp.nom_calificador_cargo
       WHERE mod_estructuracomp.nom_calificador_cargo.denominacion = $1);
        IF buscar IS NULL THEN
              RETURN 0;
        ELSE
              RETURN buscar;
        END IF;
END
$BODY$
  LANGUAGE 'plpgsql' ;



-- Function: mod_estructuracomp.f_rep_buscaridcategocup(character varying)

-- DROP FUNCTION mod_estructuracomp.f_rep_buscaridcategocup(character varying);

CREATE OR REPLACE FUNCTION mod_estructuracomp.f_rep_buscaridcategocup(denominacion_categocup character varying)
  RETURNS numeric AS
$BODY$
/* New function body */


DECLARE
buscar numeric;
BEGIN
buscar := (SELECT idcategocup FROM mod_estructuracomp.nom_categocup
       WHERE mod_estructuracomp.nom_categocup.dencategocup = $1);
        IF buscar IS NULL THEN
              RETURN 0;
        ELSE
              RETURN buscar;
        END IF;
END
$BODY$
  LANGUAGE 'plpgsql' ;


-- Function: mod_estructuracomp.f_rep_buscarideav(character varying)

-- DROP FUNCTION mod_estructuracomp.f_rep_buscarideav(character varying);

CREATE OR REPLACE FUNCTION mod_estructuracomp.f_rep_buscarideav(nombre character varying)
  RETURNS numeric AS
$BODY$
/* New function body */

DECLARE
buscar numeric;
BEGIN
buscar := (SELECT idnomeav FROM mod_estructuracomp.nom_nomencladoreavestruc
       WHERE mod_estructuracomp.nom_nomencladoreavestruc.nombre = $1);
        IF buscar IS NULL THEN
              RETURN 0;
        ELSE
              RETURN buscar;
        END IF;
END
$BODY$
  LANGUAGE 'plpgsql' ;



-- Function: mod_estructuracomp.f_rep_buscaridestructura(character varying)

-- DROP FUNCTION mod_estructuracomp.f_rep_buscaridestructura(character varying);

CREATE OR REPLACE FUNCTION mod_estructuracomp.f_rep_buscaridestructura(denominacion character varying)
  RETURNS numeric AS
$BODY$
/* New function body */

DECLARE
buscar numeric;
BEGIN
buscar := (SELECT idestructura FROM mod_estructuracomp.dat_estructura
       WHERE mod_estructuracomp.dat_estructura.denominacion = $1);
        IF buscar IS NULL THEN
              RETURN 0;
        ELSE
              RETURN buscar;
        END IF;
END
$BODY$
  LANGUAGE 'plpgsql' ;



-- Function: mod_estructuracomp.f_rep_buscaridestructuraop(character varying)

-- DROP FUNCTION mod_estructuracomp.f_rep_buscaridestructuraop(character varying);

CREATE OR REPLACE FUNCTION mod_estructuracomp.f_rep_buscaridestructuraop(denominacion character varying)
  RETURNS numeric AS
$BODY$
/* New function body */

DECLARE
buscar numeric;
BEGIN
buscar := (SELECT idestructuraop FROM mod_estructuracomp.dat_estructuraop
       WHERE mod_estructuracomp.dat_estructuraop.denominacion = $1);
        IF buscar IS NULL THEN
              RETURN 0;
        ELSE
              RETURN buscar;
        END IF;
END
$BODY$
  LANGUAGE 'plpgsql' ;

CREATE OR REPLACE FUNCTION mod_estructuracomp.f_rep_calificador_cargos(denominacion_calificador character varying)
  RETURNS SETOF mod_estructuracomp.td_calificador_cargo AS
$BODY$
/* Se le da solucion al reporte Detalles del calificador de cargos
revisado por Nemury el barriga e implementado por Rafa el tete*/

DECLARE

cargo record;

BEGIN

for cargo in
SELECT
  mod_estructuracomp.nom_cargocivil.codigo,
  mod_estructuracomp.nom_cargocivil.dencargociv,
  mod_estructuracomp.nom_categocup.dencategocup,
  mod_estructuracomp.nom_grupocomple.denominacion,
  mod_estructuracomp.nom_cargocivil.descripcion,
  mod_estructuracomp.nom_nivel_utilizacion.denominacion,
  mod_estructuracomp.nom_cargocivil.requisitos

FROM
 mod_estructuracomp.nom_grupocomple
 INNER JOIN mod_estructuracomp.nom_cargocivil ON (mod_estructuracomp.nom_grupocomple.idgrupocomplejidad=mod_estructuracomp.nom_cargocivil.idgrupocomplejidad)
 INNER JOIN mod_estructuracomp.nom_categocup ON (mod_estructuracomp.nom_cargocivil.idcategocup=mod_estructuracomp.nom_categocup.idcategocup)
 INNER JOIN mod_estructuracomp.nom_nivel_utilizacion ON (mod_estructuracomp.nom_cargocivil.idnivelutilizacion=mod_estructuracomp.nom_nivel_utilizacion.idnivelutilizacion)
 INNER JOIN mod_estructuracomp.nom_calificador_cargo ON (mod_estructuracomp.nom_cargocivil.idcalificador=mod_estructuracomp.nom_calificador_cargo.idcalificador)
WHERE
   mod_estructuracomp.nom_cargocivil.idcalificador = mod_estructuracomp."f_rep_buscaridcalaificador"($1)

 loop

return next cargo;

end loop;

END
$BODY$
  LANGUAGE 'plpgsql';



-- Function: mod_estructuracomp.f_rep_cargos_areas_categoria(character varying)

-- DROP FUNCTION mod_estructuracomp.f_rep_cargos_areas_categoria(character varying);

CREATE OR REPLACE FUNCTION mod_estructuracomp.f_rep_cargos_areas_categoria(denominacion_estructura character varying, codigo_estructura character varying)
  RETURNS SETOF mod_estructuracomp.td_cargos_areas_categoria AS
$BODY$
/* Eliminado */


declare

var record;

begin

for var IN
SELECT
  e.denominacion,
  op.denominacion,
  p.denominacion,
  count(c.idcargo)::numeric as "total",
  mod_estructuracomp.f_rep_areascategorias1(op.idestructuraop,'Administrativo') as "administrativo",
  mod_estructuracomp.f_rep_areascategorias1(op.idestructuraop,'Servicios') as "servicios",
  mod_estructuracomp.f_rep_areascategorias1(op.idestructuraop,'Tecnicos') as "tecnicos",
  mod_estructuracomp.f_rep_areascategorias1(op.idestructuraop,'Operarios') as "operarios",
  mod_estructuracomp.f_rep_areascategorias1(op.idestructuraop,'Dirigentes') as "dirigentes"

FROM
 mod_estructuracomp.dat_estructura e
 INNER JOIN mod_estructuracomp.dat_estructuraop op ON (e.idestructura = op.idestructura)
 inner join mod_estructuracomp.dat_estructuraop p on (p.idestructuraop=op.idpadre)
 INNER JOIN mod_estructuracomp.dat_cargo c ON (op.idestructuraop = c.idestructuraop)

WHERE e.idestructura = mod_estructuracomp."f_rep_buscaridestructura"($1)

GROUP BY
 e.denominacion,
 op.denominacion,
 op.idestructuraop,
 p.denominacion

ORDER BY
  op.denominacion
  

loop
return next var;
end loop;
end
$BODY$
  LANGUAGE 'plpgsql' ;



-- Function: mod_estructuracomp.f_rep_componentesestructurainterna(character varying)

-- DROP FUNCTION mod_estructuracomp.f_rep_componentesestructurainterna(character varying);

CREATE OR REPLACE FUNCTION mod_estructuracomp.f_rep_componentesestructurainterna(denominacion_estructura character varying)
  RETURNS SETOF mod_estructuracomp.td_rep_componentesestructurainterna AS
$BODY$
/* New function body */

DECLARE

result record;

BEGIN
FOR result IN
SELECT
  e.denominacion,
  op.denominacion,
  count(c.idcargo)::numeric,
  mod_estructuracomp."f_cargosporestructura"($1)
FROM
 mod_estructuracomp.dat_estructura e
 INNER JOIN mod_estructuracomp.dat_estructuraop op ON (e.idestructura = op.idestructura)
 INNER JOIN mod_estructuracomp.dat_cargo c ON (op.idestructuraop = c.idestructuraop)

WHERE
    e.denominacion = $1
GROUP BY
   e.denominacion,
   op.denominacion

loop
return next result;
end loop;
END
$BODY$
  LANGUAGE 'plpgsql';



-- Function: mod_estructuracomp.f_rep_contarcargosporcategoriaocupacional(character varying, character varying)

-- DROP FUNCTION mod_estructuracomp.f_rep_contarcargosporcategoriaocupacional(character varying, character varying);

CREATE OR REPLACE FUNCTION mod_estructuracomp.f_rep_contarcargosporcategoriaocupacional(denominacion_categoriaocupacional character varying, denominacion_estructuraop character varying)
  RETURNS numeric AS
$BODY$
/* New function body */

DECLARE
num numeric;
begin
num :=(select count(cargo.*)
from
     mod_estructuracomp.nom_categocup c,
     mod_estructuracomp.dat_estructuraop o
     INNER JOIN  mod_estructuracomp.dat_cargo cargo ON (o.idestructuraop = cargo.idestructuraop)
     INNER JOIN  mod_estructuracomp.dat_cargocivil datcarciv ON (cargo.idcargo = datcarciv.idcargo)
     INNER JOIN  mod_estructuracomp.nom_cargocivil carciv ON (datcarciv.idcargociv = carciv.idcargociv)

where
     o.idestructuraop = mod_estructuracomp."f_rep_buscaridestructuraop"($2) and carciv.idcategocup = mod_estructuracomp.f_rep_buscaridcategocup($1)
   
);
return num;
end
$BODY$
  LANGUAGE 'plpgsql';



-- Function: mod_estructuracomp.f_rep_contarhijospororgano(numeric, numeric)

-- DROP FUNCTION mod_estructuracomp.f_rep_contarhijospororgano(numeric, numeric);

CREATE OR REPLACE FUNCTION mod_estructuracomp.f_rep_contarhijospororgano("IdPadre" numeric, "idOrgano" numeric)
  RETURNS numeric AS
$BODY$
/* New function body */
DECLARE
num numeric;
begin
num :=(select count(h.*)
from mod_estructuracomp.dat_estructura p,
     mod_estructuracomp.dat_estructura h

where
     h.lft > p.lft  AND h.lft < p.rgt
     and p.idestructura=$1
     and h.idorgano=$2
);
return num;
end
$BODY$
  LANGUAGE 'plpgsql' ;


-- Function: mod_estructuracomp.f_rep_entidades(numeric, character varying, numeric)

-- DROP FUNCTION mod_estructuracomp.f_rep_entidades(numeric, character varying, numeric);

CREATE OR REPLACE FUNCTION mod_estructuracomp.f_rep_entidades(idestructura numeric, nombre character varying, "idEAV" numeric)
  RETURNS SETOF mod_estructuracomp.td_entidades AS
$BODY$
/* New function body */

DECLARE

result record;

BEGIN
FOR result IN
SELECT
val.valor,
o.denominacion,
ncat.abreviatura,
count(ncat.idcategocup)
FROM

mod_estructuracomp.dat_estructura e ,
mod_estructuracomp.dat_estructuraop o,
mod_estructuracomp.dat_cargo c,
mod_estructuracomp.dat_cargocivil civ,
mod_estructuracomp.nom_cargocivil nciv,
mod_estructuracomp.nom_categocup ncat,
mod_estructuracomp.nom_valorestruc val

WHERE  e.idestructura = $1
       AND val.idcampo = mod_estructuracomp."f_buscaridcampo"($2,$3)
       AND e.idestructura = o.idestructura
       AND o.idestructuraop = c.idestructuraop
       AND c.idcargo = civ.idcargo
       AND civ.idcargociv = nciv.idcargociv
       AND nciv.idcategocup = ncat.idcategocup

       GROUP BY ncat.abreviatura,
                o.denominacion,
                val.valor

       ORDER BY o.denominacion asc
       
loop
return next result;
end loop;
END
$BODY$
  LANGUAGE 'plpgsql';



-- Function: mod_estructuracomp.f_rep_grupoescalacategocupacional(character varying, character varying, character varying)

-- DROP FUNCTION mod_estructuracomp.f_rep_grupoescalacategocupacional(character varying, character varying, character varying);

CREATE OR REPLACE FUNCTION mod_estructuracomp.f_rep_grupoescalacategocupacional(denominacion_estructura character varying, codigo_estructura character varying)
  RETURNS SETOF mod_estructuracomp.td_grupoescalacategocupacional1 AS
$BODY$
/* New function body */


declare

var record;
denom varchar;
codigo varchar;

begin

denom := (SELECT e.denominacion from mod_estructuracomp.dat_estructura e where(e.codigo = $2 or e.denominacion = $1));
codigo := (SELECT e.codigo from mod_estructuracomp.dat_estructura e where(e.codigo = $2 or e.denominacion = $1));

FOR var IN
SELECT denom,
   g.denominacion,
   mod_estructuracomp."f_contarcargosporgupoescala"(g.denominacion,'Administrativo',$1,$2) as "administrativo",
   mod_estructuracomp."f_contarcargosporgupoescala"(g.denominacion,'Servicios',$1,$2) as "servicios",
   mod_estructuracomp."f_contarcargosporgupoescala"(g.denominacion,'Tecnicos',$1,$2) as "tecnicos",
   mod_estructuracomp."f_contarcargosporgupoescala"(g.denominacion,'Operarios',$1,$2) as "operarios",
   mod_estructuracomp."f_contarcargosporgupoescala"(g.denominacion,'Dirigentes',$1,$2) as "dirigentes",
   mod_estructuracomp.f_totalgrupoescala(denom,g.denominacion),
    codigo

FROM
  mod_estructuracomp.nom_grupocomple g 

 where 
 mod_estructuracomp.f_totalgrupoescala(denom,g.denominacion)>0
order BY
   -- g.abreviatura ,
    g.orden
loop
return next var;
end loop;
end
$BODY$
  LANGUAGE 'plpgsql';


-- Function: mod_estructuracomp.f_rep_nemurycontarhijosxeav1(numeric, numeric)

-- DROP FUNCTION mod_estructuracomp.f_rep_nemurycontarhijosxeav1(numeric, numeric);

CREATE OR REPLACE FUNCTION mod_estructuracomp.f_rep_nemurycontarhijosxeav1("IdPadre" numeric, "Eav" numeric)
  RETURNS numeric AS
$BODY$
/* New function body */
DECLARE
num numeric;
begin
num :=(select count(h.*)
from mod_estructuracomp.dat_estructura p,
     mod_estructuracomp.dat_estructura h

where
     ( h.lft > p.lft or h.lft = p.lft) AND h.rgt < p.rgt
     and p.idestructura=$1
     and h.idnomeav=$2
);




return num;
end
$BODY$
  LANGUAGE 'plpgsql' ;



-- Function: mod_estructuracomp.f_rep_plantilla_cargos(character varying)

-- DROP FUNCTION mod_estructuracomp.f_rep_plantilla_cargos(character varying);

CREATE OR REPLACE FUNCTION mod_estructuracomp.f_rep_plantilla_cargos(denominacion_estructura character varying, codigo_estructura character varying)
  RETURNS SETOF mod_estructuracomp.td_plantilla_cargos AS
$BODY$
DECLARE

plantilla record;

BEGIN

for plantilla in
SELECT
  op.denominacion,
  nciv.dencargociv,
  SUM(civ.idcargo) AS cantidad_cargos,
  es.abreviatura,
  categ.abreviatura,
  esp.abrevespecialidad
FROM
 mod_estructuracomp.dat_estructura e
 INNER JOIN mod_estructuracomp.dat_estructuraop op ON (e.idestructura = op.idestructura)
 INNER JOIN mod_estructuracomp.dat_cargo c ON (op.idestructuraop = c.idestructuraop)
 INNER JOIN mod_estructuracomp.dat_cargocivil civ ON (c.idcargo = civ.idcargo)
 INNER JOIN mod_estructuracomp.nom_cargocivil nciv ON (civ.idcargociv = nciv.idcargociv)
 INNER JOIN mod_estructuracomp.nom_especialidad esp ON (e.idespecialidad = esp.idespecialidad)
 INNER JOIN mod_estructuracomp.nom_categocup categ ON (nciv.idcategocup = categ.idcategocup)
 INNER JOIN mod_estructuracomp.nom_escalasalarial es ON (civ.idescalasalarial = es.idescalasalarial)
WHERE
  e.idestructura = mod_estructuracomp."f_rep_buscaridestructura"($1)
GROUP BY
  op.denominacion,
  nciv.dencargociv,
  categ.abreviatura,
  es.abreviatura,
  esp.abrevespecialidad

 loop

return next plantilla;

end loop;

END
$BODY$
  LANGUAGE 'plpgsql' ;




CREATE OR REPLACE FUNCTION mod_estructuracomp.f_rep_relacion_nivel1porclasif1()
  RETURNS SETOF mod_estructuracomp.td_rep_nivel1porclasif AS
$BODY$
/* solucion al reporte
Relacion de niveles 1 segun su clasificacion, Firmado
*/

declare
result record;
id numeric;
EavNivel numeric;
EavAgrup numeric;
EavEntidad numeric;
EavUnidad numeric;

BEGIN

EavNivel   =mod_estructuracomp."f_rep_buscarideav"('Nivel 1');
EavAgrup   =mod_estructuracomp."f_rep_buscarideav"('Agrupación');
EavEntidad =mod_estructuracomp."f_rep_buscarideav"('Entidad');
EavUnidad  =mod_estructuracomp."f_rep_buscarideav"('Unidad');

FOR result IN
select o.denorgano,p.abreviatura,p.denominacion,
       mod_estructuracomp."f_rep_nemurycontarhijosxeav1"(p.idestructura,EavAgrup),
       mod_estructuracomp."f_rep_nemurycontarhijosxeav1"(p.idestructura,EavEntidad),
       mod_estructuracomp."f_rep_nemurycontarhijosxeav1"(p.idestructura,EavUnidad)

from
       mod_estructuracomp.dat_estructura p
       LEFT JOIN
       mod_estructuracomp.nom_organo o
       on
       p.idorgano=o.idorgano

where
     o.idnomeav=EavNivel




order by
      o.denorgano


loop
return next result;
end loop;
END
$BODY$
  LANGUAGE 'plpgsql' ;



-- Function: mod_estructuracomp.f_rep_relacion_registro_entidades_agrupacion(character varying, character varying)

-- DROP FUNCTION mod_estructuracomp.f_rep_relacion_registro_entidades_agrupacion(character varying, character varying);

CREATE OR REPLACE FUNCTION mod_estructuracomp.f_rep_relacion_registro_entidades_agrupacion(nombrenivel character varying, codigonivel character varying)
  RETURNS SETOF mod_estructuracomp.td_rep_relacion_registro_entidad_agrupacion1 AS
$BODY$
/* Solucion al reporte Relacion de registros
de las entidades por agrupacion,Firmado , adiciionar codigo NAE */

declare
Result record;
id numeric;
EavNivel numeric;
EavAgrup numeric;
EavEntidad numeric;
EavUnidad numeric;

CampoCodigoOne numeric;
CampoRegisMercantil numeric;
CampoRegisComercial numeric;
CampoRegisONAT numeric;
CampoCodNAE numeric;


BEGIN
id         =mod_estructuracomp."f_rep_buscaridestructura"($1);
EavNivel   =mod_estructuracomp."f_rep_buscarideav"('Nivel 1');
EavAgrup   =mod_estructuracomp."f_rep_buscarideav"('Agrupación');
EavEntidad =mod_estructuracomp."f_rep_buscarideav"('Entidad');
EavUnidad  =mod_estructuracomp."f_rep_buscarideav"('Unidad');


CampoCodigoOne=mod_estructuracomp."f_buscaridcampo"('codigoone',EavEntidad);
CampoRegisMercantil=mod_estructuracomp."f_buscaridcampo"('registromercantil',EavEntidad);
CampoRegisComercial=mod_estructuracomp."f_buscaridcampo"('registrocomercial',EavEntidad);
CampoRegisONAT=mod_estructuracomp."f_buscaridcampo"('registroonat',EavEntidad);
CampoCodNAE=mod_estructuracomp."f_buscaridcampo"('codigonae',EavEntidad);


FOR Result IN
SELECT

       h.denominacion,
       h.abreviatura,
       h1.denominacion,
       h1.abreviatura,
       h1.codigo,
       v4.valor,
       v1.valor,
       v2.valor,
       v3.valor
       --h.denominacion,
       --h.abreviatura,
       --h.denominacion,
       --h.denominacion


FROM

     mod_estructuracomp.dat_estructura p
inner join
     mod_estructuracomp.dat_estructura h
ON
     ((p.idestructura=id or p.codigo=$2)
       AND h.lft>p.lft and h.lft<p.rgt
       and h.idnomeav=2)
inner join
       mod_estructuracomp.dat_estructura h1
on
      (  h1.lft>h.lft and h1.lft<h.rgt
       and h1.idnomeav=EavEntidad)
inner join
     mod_estructuracomp.nom_filaestruc f
on
     (f.idfila=h1.idestructura)
LEFT join
     mod_estructuracomp.nom_valorestruc v1
on
     (v1.idfila=f.idfila
     and v1.idcampo=CampoRegisMercantil)
LEFT join
     mod_estructuracomp.nom_valorestruc v2
ON
     (v2.idcampo=CampoRegisComercial
       and v2.idfila=f.idfila)
left join
     mod_estructuracomp.nom_valorestruc v3
on
     (v3.idfila=f.idfila
       and v3.idcampo=CampoRegisONAT)
left join
     mod_estructuracomp.nom_valorestruc v4
on
     (v4.idfila=f.idfila
       and v4.idcampo=CampoCodNAE)
/*
where
       p.idestructura=id
       AND h.lft>p.lft and h.lft<p.rgt
       and h.idnomeav=2
       AND h1.lft>h.lft and h1.lft<h.rgt
       and h1.idnomeav=EavEntidad
       and f.idfila=h1.idestructura
       and v1.idfila=f.idfila
       and v1.idcampo=CampoRegisMercantil
       and v2.idcampo=CampoRegisComercial
       and v2.idfila=f.idfila
        and v3.idfila=f.idfila
       and v3.idcampo=CampoRegisONAT
       and v4.idfila=f.idfila
       and v4.idcampo=CampoCodNAE


*/

group by
         h.denominacion,
         h.abreviatura,
         h1.denominacion,
         h1.abreviatura,
         h1.codigo,
         v4.valor,
         v1.valor,
         v2.valor,
         v3.valor
order by
        h.denominacion



loop
return next Result;
end loop;
END
$BODY$
  LANGUAGE 'plpgsql';



-- Function: mod_estructuracomp.f_rep_relacionagruppornivel11(character varying)

-- DROP FUNCTION mod_estructuracomp.f_rep_relacionagruppornivel11(character varying);

CREATE OR REPLACE FUNCTION mod_estructuracomp.f_rep_relacionagruppornivel11("NombreNivel" character varying)
  RETURNS SETOF mod_estructuracomp.td_rep_relacionagruppornivel1 AS
$BODY$
/* Funcion para darle solucion alReporteRelacion
de Agrupaciones por Nivel 1*/

DECLARE
result record;
id numeric;
EavNivel numeric;
EavAgrup numeric;
EavEntidad numeric;
idcampCategoria numeric;


BEGIN

--id         =mod_estructuracomp."f_rep_buscaridestructura"($1);
EavNivel   =mod_estructuracomp."f_rep_buscarideav"('Nivel 1');
EavAgrup   =mod_estructuracomp."f_rep_buscarideav"('Agrupacion');
EavEntidad =mod_estructuracomp."f_rep_buscarideav"('Entidad');
idcampCategoria=mod_estructuracomp."f_buscaridcampo"('Categoria',EavAgrup);


FOR result IN
SELECT  p.denominacion,
        p.abreviatura,
        h.denominacion,
        o.denorgano,
        v.valor,
        mod_estructuracomp."f_rep_nemurycontarhijosxeav1"(h.idestructura,EavEntidad)

FROM
    
    mod_estructuracomp.dat_estructura p
    INNER JOIN mod_estructuracomp.dat_estructura h ON ( p.idnomeav=EavNivel AND h.lft>p.lft AND h.lft<p.rgt AND h.idnomeav=EavAgrup )
    INNER JOIN mod_estructuracomp.nom_organo o ON ( h.idorgano=o.idorgano )
    INNER JOIN mod_estructuracomp.nom_filaestruc f ON ( f.idfila =h.idestructura )
    LEFT JOIN mod_estructuracomp.nom_valorestruc v ON ( v.idfila=f.idfila  AND v.idcampo=idcampCategoria )

/*WHERE
     p.idnomeav=EavNivel
    and h.lft>p.lft and h.lft<p.rgt
    and h.idnomeav=EavAgrup
    and h.idorgano=o.idorgano
    and f.idfila =h.idestructura
    and v.idfila=f.idfila
    and v.idcampo=idcampCategoria*/

group by p.denominacion,
         p.abreviatura,
         p.idestructura,
         h.denominacion,
         o.denorgano,
         h.idestructura,
          v.valor
order by
      p.denominacion

loop
return next result;
end loop;
END
$BODY$
  LANGUAGE 'plpgsql';



-- Function: mod_estructuracomp.f_rep_relacionentidadesporagrupaciones1(character varying)

-- DROP FUNCTION mod_estructuracomp.f_rep_relacionentidadesporagrupaciones1(character varying);

CREATE OR REPLACE FUNCTION mod_estructuracomp.f_rep_relacionentidadesporagrupaciones1("NombreNivel" character varying, "Codigo" character varying)
  RETURNS SETOF mod_estructuracomp.td_rep_relacionentidadesporagrupaciones1 AS
$BODY$
/* Funcion para darle solucion al reporte
Relacion de entidades por agrupacion, ponerle el codigo NAE
 y si aplica perfeccionamiento*/

declare
result record;
id numeric;
EavNivel numeric;
EavAgrup numeric;
EavEntidad numeric;
EavUnidad numeric;

CampoCategoria numeric;
CampoActEconomica numeric;
CampoPagoAdic numeric;
CampoPerfeccionamiento numeric;
CampoCodigoNae numeric;



BEGIN

id         =mod_estructuracomp."f_rep_buscaridestructura"($1);
EavNivel   =mod_estructuracomp."f_rep_buscarideav"('Nivel 1');
EavAgrup   =mod_estructuracomp."f_rep_buscarideav"('Agrupación');
EavEntidad =mod_estructuracomp."f_rep_buscarideav"('Entidad');
EavUnidad  =mod_estructuracomp."f_rep_buscarideav"('Unidad');
CampoCategoria=mod_estructuracomp."f_buscaridcampo"('Categoria',EavEntidad);
CampoActEconomica=mod_estructuracomp."f_buscaridcampo"('actividadeconomica',EavEntidad);
CampoPerfeccionamiento=mod_estructuracomp."f_buscaridcampo"('perfeccionamiento',EavEntidad);
CampoCodigoNae=mod_estructuracomp."f_buscaridcampo"('codigonae',EavEntidad);

FOR result IN

SELECT  h.denominacion,
        h.abreviatura,
        h1.denominacion,
        h1.abreviatura,
        h1.codigo,
        o.denorgano,
        v.valor,
        v1.valor,
        mod_estructuracomp."f_rep_nemurycontarhijosxeav1"(h1.idestructura,EavUnidad),
        v2.valor,
        v3.valor
        

FROM
    mod_estructuracomp.dat_estructura p
inner join
    mod_estructuracomp.dat_estructura h
on
    ( p.idestructura=id
     and h.lft>p.lft and h.lft<p.rgt and h.idnomeav=EavAgrup)
inner join
    mod_estructuracomp.dat_estructura h1
on
    (h1.lft>h.lft and h1.lft<h.rgt
    and h1.idnomeav=EavEntidad)
inner join
      mod_estructuracomp.nom_organo o
on
      (o.idorgano=h1.idorgano)
inner join
    mod_estructuracomp.nom_filaestruc f
on
    (f.idfila =h1.idestructura)
left join
    mod_estructuracomp.nom_valorestruc v
on
    ( v.idfila=f.idfila
    and v.idcampo=CampoCategoria)
left join
    mod_estructuracomp.nom_valorestruc v1
on
    (v1.idfila=f.idfila
    and v1.idcampo=CampoActEconomica)
left join
    mod_estructuracomp.nom_valorestruc v2
on
    (v2.idfila=f.idfila
    and v2.idcampo=CampoPerfeccionamiento)
left join
    mod_estructuracomp.nom_valorestruc v3
on
    (v3.idfila=f.idfila
    and v3.idcampo=CampoCodigoNae)

 /*
where
     p.idestructura=id
     and h.lft>p.lft and h.lft<p.rgt
     and h1.lft>h.lft and h1.lft<h.rgt
    and h.idnomeav=2
    and h1.idnomeav=EavEntidad
    and o.idorgano=h1.idorgano
    and f.idfila =h1.idestructura
    and v.idfila=f.idfila
    and v.idcampo=68
    and v1.idfila=f.idfila
    and v1.idcampo=74
   and v2.idfila=f.idfila
    and v2.idcampo=CampoPerfeccionamiento
    and v3.idfila=f.idfila
    and v3.idcampo=CampoCodigoNae
    */
group by

          h.denominacion,
          h.abreviatura,
          h1.idestructura,
          h1.denominacion,
          h1.abreviatura,
          h1.codigo,
          o.denorgano,
          v.valor,
          v1.valor,
          v2.valor,
          v3.valor
order by
        h.denominacion

loop
return next result;
end loop;
END
$BODY$
  LANGUAGE 'plpgsql' ;




CREATE OR REPLACE FUNCTION mod_estructuracomp.f_rep_resumen_agrupaciones_nivel_segun_clasificacion(nombrenivel1 character varying)
  RETURNS SETOF mod_estructuracomp.td_rep_resumen_agrupaciones_nivel AS
$BODY$
/*Eliminado, Funcion para darle solucion al reporte
Resumen de agrupaciones por nivel 1 segun su clasificacion */

declare
result record;
id numeric;
EavNivel numeric;
EavAgrup numeric;
EavEntidad numeric;
EavUnidad numeric;

BEGIN
id         =mod_estructuracomp."f_rep_buscaridestructura"($1);
EavNivel   =mod_estructuracomp."f_rep_buscarideav"('Nivel 1');
EavAgrup   =mod_estructuracomp."f_rep_buscarideav"('Agrupacion');
EavEntidad =mod_estructuracomp."f_rep_buscarideav"('Entidad');
EavUnidad  =mod_estructuracomp."f_rep_buscarideav"('Unidad');

FOR result IN
SELECT  o.denorgano,
        mod_estructuracomp.rep_contarxclasf(id,EavAgrup,o.idorgano),
        mod_estructuracomp."Nemury_ContarNietosxEAVxidOrgPadre"(id,o.idorgano,EavEntidad),
        mod_estructuracomp."Nemury_ContarNietosxEAVxidOrgPadre"(id,o.idorgano,EavUnidad)

FROM
      mod_estructuracomp.nom_organo o

where
      o.idnomeav =EavAgrup
     

group by o.denorgano,
         o.idorgano
order by
      o.denorgano
loop
return next result;
end loop;
END
$BODY$
  LANGUAGE 'plpgsql' ;



CREATE OR REPLACE FUNCTION mod_estructuracomp.f_rep_resumen_categoria_entidades_agrupaciones(nombrenivel character varying, codigo character varying)
  RETURNS SETOF mod_estructuracomp.td_rep_resumen_categoria_entidades_agrupaciones AS
$BODY$
/* funcion para darle solucion al reporte
Resumen de categoria de las entidades por agrupaciones
valores :138,3,57,2, todo fino */


declare
result record;
id numeric;
EavNivel numeric;
EavAgrup numeric;
EavEntidad numeric;
CampoCategoria numeric;


BEGIN

id         =mod_estructuracomp."f_rep_buscaridestructura"($1);
EavNivel   =mod_estructuracomp."f_rep_buscarideav"('Nivel 1');
EavAgrup   =mod_estructuracomp."f_rep_buscarideav"('Agrupación');
EavEntidad =mod_estructuracomp."f_rep_buscarideav"('Entidad');
CampoCategoria=mod_estructuracomp."f_buscaridcampo"('categoria',EavEntidad);

FOR result IN
select  h.abreviatura,h.denominacion,
        mod_estructuracomp."f_rep_nemurycontarhijosxeav1"(h.idestructura,EavEntidad ),
        mod_estructuracomp."rep_contarhijosconvalorencampo"(h.idestructura,CampoCategoria,'I'),
        mod_estructuracomp."rep_contarhijosconvalorencampo"(h.idestructura,CampoCategoria,'II'),
        mod_estructuracomp."rep_contarhijosconvalorencampo"(h.idestructura,CampoCategoria,'III'),
        mod_estructuracomp."rep_contarhijosconvalorencampo"(h.idestructura,CampoCategoria,'IV')

from
         mod_estructuracomp.dat_estructura p
inner join

         mod_estructuracomp.dat_estructura h
on
     (p.idestructura=id
     and h.lft>p.lft and h.lft<p.rgt
     and h.idnomeav=EavAgrup)


group by
         h.abreviatura,
         h.denominacion,
         h.idestructura
order by
        h.denominacion
loop
return next result;
end loop;
END
$BODY$
  LANGUAGE 'plpgsql';




CREATE OR REPLACE FUNCTION mod_estructuracomp.f_rep_resumentidporagrupaciporclasificac(nombrenivel1 character varying, codigo character varying)
  RETURNS SETOF mod_estructuracomp.td_rep_resumentidporagrupaciporclasificac AS
$BODY$
/* Funcion para darle solucion al reporte Resumen
de entidades por agrupacion segun su clasificacion,
 */

declare
result record;
id numeric;
EavNivel numeric;
EavAgrup numeric;
EavEntidad numeric;
EavUnidad numeric;
IdCamActEcono numeric;

BEGIN

id         =mod_estructuracomp."f_rep_buscaridestructura"($1);
EavNivel   =mod_estructuracomp."f_rep_buscarideav"('Nivel 1');
EavAgrup   =mod_estructuracomp."f_rep_buscarideav"('Agrupación');
EavEntidad =mod_estructuracomp."f_rep_buscarideav"('Entidad');
EavUnidad  =mod_estructuracomp."f_rep_buscarideav"('Unidad');
IdCamActEcono=mod_estructuracomp."f_buscaridcampo"('actividadeconomica',EavEntidad);


FOR result IN
select  o.denorgano,
        h.abreviatura,
        h.denominacion,
        mod_estructuracomp."f_rep_nemurycontarhijosxeav1"(h.idestructura,EavEntidad),
        mod_estructuracomp."f_rep_contarhijospororgano"(h.idestructura,8080000014),
        mod_estructuracomp."f_rep_contarhijospororgano"(h.idestructura,8080000013),
        mod_estructuracomp."f_rep_contarhijospororgano"(h.idestructura,8080000015),
        mod_estructuracomp."rep_contarhijosconvalorencampo"(h.idestructura,IdCamActEcono,'Empresarial'),
        mod_estructuracomp."rep_contarhijosconvalorencampo"(h.idestructura,IdCamActEcono,'Presupuestada')

from
         mod_estructuracomp.dat_estructura p,
         mod_estructuracomp.dat_estructura h,
         mod_estructuracomp.nom_organo o

where
     o.idnomeav=EavAgrup
     and h.idorgano=o.idorgano
     and h.lft>p.lft and h.lft<p.rgt
     and p.idestructura=id

group by o.denorgano,
         p.abreviatura,
         h.abreviatura,
         p.denominacion,
         h.denominacion,
         h.idestructura
order by
        o.denorgano

loop
return next result;
end loop;
END
$BODY$
  LANGUAGE 'plpgsql' ;




CREATE OR REPLACE FUNCTION mod_estructuracomp.f_totalgrupoescala(denominacion_estructura character varying, grupocomple character varying)
  RETURNS numeric AS
$BODY$
/* New function body */


declare

var numeric;

BEGIN
var := (SELECT
  count(c.idcargo)

FROM
 mod_estructuracomp.dat_estructura e
 INNER JOIN mod_estructuracomp.dat_estructuraop op ON (e.idestructura = op.idestructura)
 INNER JOIN mod_estructuracomp.dat_cargo c ON (op.idestructuraop = c.idestructuraop)
 INNER JOIN mod_estructuracomp.dat_cargocivil civ ON (c.idcargo = civ.idcargo)
 INNER JOIN mod_estructuracomp.nom_grupocomple g ON (civ.idgrupocomple = g.idgrupocomplejidad)
 INNER JOIN mod_estructuracomp.nom_cargocivil nciv ON (civ.idcargociv = nciv.idcargociv)
 INNER JOIN mod_estructuracomp.nom_categocup cat ON (nciv.idcategocup = cat.idcategocup)
WHERE
 );

return  var;

end
$BODY$
  LANGUAGE 'plpgsql' ;




CREATE OR REPLACE FUNCTION mod_estructuracomp.posicional(num text)
  RETURNS character varying AS
$BODY$
/* New function body */
DECLARE
	pot bit:= 0 ;
BEGIN
	pot := (SELECT power( 10 , num - 1 ));
	RETURN pot;

END
$BODY$
  LANGUAGE 'plpgsql';




CREATE OR REPLACE FUNCTION mod_estructuracomp.posicionalmitad(num numeric)
  RETURNS character varying AS
$BODY$
/* New function body */

DECLARE

pot numeric:= 0 ;

BEGIN
pot := (SELECT (power( 2 , (num - 1) ))/2);

RETURN pot;
END
$BODY$
  LANGUAGE 'plpgsql';




CREATE OR REPLACE FUNCTION mod_estructuracomp.reordenar_dat_estructura(iddat_estructuranodo bigint, lftnodo bigint)
  RETURNS bigint AS
$BODY$
DECLARE
       ultimorgt BIGINT;
       canthijos INTEGER;
       esprimero INTEGER;
       hijo RECORD;
BEGIN
     SET search_path = mod_estructuracomp;
     canthijos := count(idestructura) FROM dat_estructura WHERE idpadre = $1 AND idpadre <> idestructura;
     IF canthijos = 0 THEN
        ultimorgt := $2;
     ELSE
         esprimero := 1;
         FOR hijo IN SELECT idestructura, lft FROM dat_estructura WHERE idpadre = $1 AND idpadre <> idestructura LOOP
             IF esprimero = 1 THEN
                hijo.lft := $2 + 1;
                esprimero := 0;
             ELSE
                 hijo.lft := ultimorgt + 1;
             END IF;
             ultimorgt := reordenar_dat_estructura(hijo.idestructura, hijo.lft);
             UPDATE dat_estructura SET lft = hijo.lft, rgt = ultimorgt WHERE idestructura = hijo.idestructura;
         END LOOP;
     END IF;
     RETURN ultimorgt + 1;
END;
$BODY$
  LANGUAGE 'plpgsql';




CREATE OR REPLACE FUNCTION mod_estructuracomp.reordenar_dat_estructuraop(iddat_estructuranodo bigint, lftnodo bigint)
  RETURNS bigint AS
$BODY$
DECLARE
       ultimorgt BIGINT;
       canthijos INTEGER;
       esprimero INTEGER;
       hijo RECORD;
BEGIN
     SET search_path = mod_estructuracomp;
     canthijos := count(idestructuraop) FROM dat_estructuraop WHERE idpadre = $1 AND idpadre <> idestructuraop;
     IF canthijos = 0 THEN
        ultimorgt := $2;
     ELSE
         esprimero := 1;
         FOR hijo IN SELECT idestructuraop, lft FROM dat_estructuraop WHERE idpadre = $1 AND idpadre <> idestructuraop LOOP
             IF esprimero = 1 THEN
                hijo.lft := $2 + 1;
                esprimero := 0;
             ELSE
                 hijo.lft := ultimorgt + 1;
             END IF;
             ultimorgt := reordenar_dat_estructuraop(hijo.idestructuraop, hijo.lft);
             UPDATE dat_estructuraop SET lft = hijo.lft, rgt = ultimorgt WHERE idestructuraop = hijo.idestructuraop;
         END LOOP;
     END IF;
     RETURN ultimorgt + 1;
END;
$BODY$
  LANGUAGE 'plpgsql' ;




CREATE OR REPLACE FUNCTION mod_estructuracomp.reordenar_nom_dpa(iddat_estructuranodo bigint, lftnodo bigint)
  RETURNS bigint AS
$BODY$
DECLARE
       ultimorgt BIGINT;
       canthijos INTEGER;
       esprimero INTEGER;
       hijo RECORD;
BEGIN
     SET search_path = mod_estructuracomp;
     canthijos := count(iddpa) FROM nom_dpa WHERE idpadredpa = $1 AND idpadredpa <> iddpa;
     IF canthijos = 0 THEN
        ultimorgt := $2;
     ELSE
         esprimero := 1;
         FOR hijo IN SELECT iddpa, lft FROM nom_dpa WHERE idpadredpa = $1 AND idpadredpa <> iddpa LOOP
             IF esprimero = 1 THEN
                hijo.lft := $2 + 1;
                esprimero := 0;
             ELSE
                 hijo.lft := ultimorgt + 1;
             END IF;
             ultimorgt := reordenar_nom_dpa(hijo.iddpa, hijo.lft);
             UPDATE nom_dpa SET lft = hijo.lft, rgt = ultimorgt WHERE iddpa = hijo.iddpa;
         END LOOP;
     END IF;
     RETURN ultimorgt + 1;
END;
$BODY$
  LANGUAGE 'plpgsql' ;



CREATE OR REPLACE FUNCTION mod_estructuracomp.rep_contarhijosconvalorencampo("IdPadre" numeric, "idCampo" numeric, "Valor" character varying)
  RETURNS numeric AS
$BODY$
/* Cuenta de los hijos que tiene , la cantidad que tiene
en un campo determinado un valor especifico*/
DECLARE
num numeric;
begin
num :=(select count(h.*)
from mod_estructuracomp.dat_estructura p,
     mod_estructuracomp.dat_estructura h,
     mod_estructuracomp.nom_filaestruc f,
     mod_estructuracomp.nom_valorestruc v

where
     h.lft > p.lft  AND h.lft < p.rgt
     and p.idestructura=$1
     and f.idfila=h.idestructura
     and v.idfila=f.idfila
     and v.idcampo=$2
     and v.valor=$3
);
return num;
end
$BODY$
  LANGUAGE 'plpgsql';




CREATE OR REPLACE FUNCTION mod_estructuracomp.rep_contarhijosconvalorencampo("Idpadre" numeric, idcampo numeric, eav_hijo numeric, valor character varying)
  RETURNS numeric AS
$BODY$
/* New function body */
/* Cuenta de los hijos que tiene , la cantidad que tiene
en un campo determinado un valor especifico*/
DECLARE
num numeric;
begin
num :=(select count(h.*)
from mod_estructuracomp.dat_estructura p,
     mod_estructuracomp.dat_estructura h,
     mod_estructuracomp.nom_filaestruc f,
     mod_estructuracomp.nom_valorestruc v

where
     p.idestructura=$1
     and h.lft > p.lft  AND h.lft < p.rgt
     and h.idnomeav=$3
     and f.idfila=h.idestructura
     and v.idfila=f.idfila
     and v.idcampo=$2
     and v.valor=$4
);
return num;
end
$BODY$
  LANGUAGE 'plpgsql' ;



CREATE OR REPLACE FUNCTION mod_estructuracomp.rep_contarxclasf(id numeric, idnomeav numeric, idor numeric)
  RETURNS numeric AS
$BODY$
/* Dado un id , cuenta la cantidad de hijos que tiene
    de un tipo y de una clasificacon */
DECLARE
num numeric;
begin
num :=(select count(l.*) from mod_estructuracomp.dat_estructura m,mod_estructuracomp.dat_estructura l
								where
								  l.lft > m.lft  AND l.lft < m.rgt
									and l.idnomeav = $2 and m.idestructura=$1
                                    and l.idorgano =$3 );

return num;
end
$BODY$
  LANGUAGE 'plpgsql' ;


CREATE FUNCTION chequear() RETURNS trigger
    AS $$
BEGIN
RETURN NEW;
END;
$$
    LANGUAGE plpgsql IMMUTABLE;



CREATE FUNCTION ft_actualizacion_arbol() RETURNS trigger
    AS $$
BEGIN
if (new.idpadre is null ) then
    new.idpadre = new.idestructura;
end if;
RETURN new;
END;
$$
    LANGUAGE plpgsql;




CREATE FUNCTION ft_actualizacion_arbolop() RETURNS trigger
    AS $$
BEGIN
if (new.idpadre is null ) then
    new.idpadre = new.idestructuraop;
end if;
RETURN new;
END;
$$
    LANGUAGE plpgsql IMMUTABLE;




CREATE FUNCTION ft_chequear() RETURNS trigger
    AS $$
BEGIN
IF(OLD.version = NEW.version)THEN
  NEW.version = NEW.version + 1;
  ELSE
   RAISE EXCEPTION 'CONCURRENCE RESTRICTION';
END IF;
RETURN NEW;

END;
$$
    LANGUAGE plpgsql;




CREATE FUNCTION ft_eliminar_nodo() RETURNS trigger
    AS $$
DECLARE
       ancho BIGINT;
BEGIN
     SET search_path = mod_estructuracomp;
     ancho := OLD.rgt - OLD.lft + 1;
     UPDATE dat_estructura SET rgt = rgt - ancho WHERE rgt > OLD.rgt;
     UPDATE dat_estructura SET lft = lft - ancho WHERE lft > OLD.rgt;
     RETURN OLD;
     EXCEPTION WHEN foreign_key_violation THEN
         RETURN OLD;
END;
$$
    LANGUAGE plpgsql;




CREATE FUNCTION ft_eliminar_nodoop() RETURNS trigger
    AS $$
DECLARE
       ancho BIGINT;
BEGIN
     SET search_path = mod_estructuracomp;
     ancho := OLD.rgt - OLD.lft + 1;
     UPDATE dat_estructuraop SET rgt = rgt - ancho WHERE rgt > OLD.rgt;
     UPDATE dat_estructuraop SET lft = lft - ancho WHERE lft > OLD.rgt;
     RETURN OLD;
     EXCEPTION WHEN foreign_key_violation THEN
         RETURN OLD;
END;
$$
    LANGUAGE plpgsql;




CREATE FUNCTION ft_insertar_fila() RETURNS trigger
    AS $$
BEGIN
SET search_path = mod_estructuracomp;

RETURN NEW;
END;
$$
    LANGUAGE plpgsql IMMUTABLE;




CREATE FUNCTION ft_insertar_nodo() RETURNS trigger
    AS $$
DECLARE
        derecha bigint;
BEGIN
	SET search_path = mod_estructuracomp;
	IF NEW.idestructura != NEW.idpadre THEN
       derecha :=  rgt FROM dat_estructura WHERE idestructura = NEW.idpadre;
       UPDATE dat_estructura SET rgt = rgt + 2 WHERE rgt >= derecha;
       UPDATE dat_estructura SET lft = lft + 2 WHERE lft > derecha;
       NEW.lft := derecha;
       NEW.rgt := derecha + 1;
    ELSE
        derecha :=  MAX(rgt) FROM dat_estructura WHERE idestructura = idpadre;
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
$$
    LANGUAGE plpgsql;




CREATE FUNCTION ft_insertar_nodoop() RETURNS trigger
    AS $$
DECLARE
        derecha bigint;
BEGIN
	SET search_path = mod_estructuracomp;
	IF NEW.idestructuraop != NEW.idpadre THEN
       derecha :=  rgt FROM dat_estructuraop WHERE idestructuraop = NEW.idpadre;
       UPDATE dat_estructuraop SET rgt = rgt + 2 WHERE rgt >= derecha;
       UPDATE dat_estructuraop SET lft = lft + 2 WHERE lft > derecha;
       NEW.lft := derecha;
       NEW.rgt := derecha + 1;
    ELSE
        derecha :=  MAX(rgt) FROM dat_estructuraop WHERE idestructuraop = idpadre;
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
$$
    LANGUAGE plpgsql;




CREATE FUNCTION ft_modificar_nodo() RETURNS trigger
    AS $$
DECLARE
	raiz RECORD;
	esprimero INTEGER;
	ultimorgt BIGINT;
BEGIN
	IF OLD.idpadre != NEW.idpadre THEN
		SET search_path = mod_estructuracomp;
		esprimero := 1;
		FOR raiz IN SELECT idestructura, lft FROM dat_estructura WHERE idestructura = idpadre ORDER BY lft LOOP
			IF esprimero = 1 THEN
				raiz.lft := 1;
				esprimero := 0;
			ELSE
				raiz.lft := ultimorgt + 1;
			END IF;
			ultimorgt := reordenar_dat_estructura(raiz.idestructura, raiz.lft);
			UPDATE dat_estructura SET lft = raiz.lft, rgt = ultimorgt WHERE idestructura = raiz.idestructura;
		END LOOP;
	END IF;
	RETURN NEW;
END;
$$
    LANGUAGE plpgsql;




CREATE FUNCTION ft_modificar_nodoop() RETURNS trigger
    AS $$
DECLARE
	raiz RECORD;
	esprimero INTEGER;
	ultimorgt BIGINT;
BEGIN
	IF OLD.idpadre != NEW.idpadre THEN
		SET search_path = mod_estructuracomp;
		esprimero := 1;
		FOR raiz IN SELECT idestructuraop, lft FROM dat_estructuraop WHERE idestructuraop = idpadre ORDER BY lft LOOP
			IF esprimero = 1 THEN
				raiz.lft := 1;
				esprimero := 0;
			ELSE
				raiz.lft := ultimorgt + 1;
			END IF;
			ultimorgt := reordenar_dat_estructuraop(raiz.idestructuraop, raiz.lft);
			UPDATE dat_estructuraop SET lft = raiz.lft, rgt = ultimorgt WHERE idestructuraop = raiz.idestructuraop;
		END LOOP;
	END IF;
	RETURN NEW;
END;
$$
    LANGUAGE plpgsql;
	
	
	
	CREATE OR REPLACE FUNCTION mod_estructuracomp.ft_eliminar_nodo_fila()
  RETURNS trigger AS
$BODY$
DECLARE
       ancho BIGINT;
BEGIN
     SET search_path = mod_estructuracomp;
     ancho := OLD.rgt - OLD.lft + 1;
     UPDATE nom_filaestruc SET rgt = rgt - ancho WHERE rgt > OLD.rgt;
     UPDATE nom_filaestruc SET lft = lft - ancho WHERE lft > OLD.rgt;
     RETURN OLD;
     EXCEPTION WHEN foreign_key_violation THEN
         RETURN OLD;
END;
$BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
  
  
  
  CREATE OR REPLACE FUNCTION mod_estructuracomp.ft_insertar_nodo_fila()
  RETURNS trigger AS
$BODY$
DECLARE
        derecha bigint;
BEGIN
	SET search_path = mod_estructuracomp;
	IF NEW.idfila != NEW.idpadre THEN
       derecha :=  rgt FROM nom_filaestruc WHERE idfila = NEW.idpadre;
       UPDATE nom_filaestruc SET rgt = rgt + 2 WHERE rgt >= derecha;
       UPDATE nom_filaestruc SET lft = lft + 2 WHERE lft > derecha;
       NEW.lft := derecha;
       NEW.rgt := derecha + 1;
    ELSE
        derecha :=  MAX(rgt) FROM nom_filaestruc WHERE idfila = idpadre;
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
  
  
  CREATE OR REPLACE FUNCTION mod_estructuracomp.ft_insertar_nodo_dominio()
  RETURNS trigger AS
$BODY$
DECLARE
        derecha bigint;
BEGIN
        SET search_path = mod_estructuracomp;
        IF NEW.iddominio != NEW.idpadre THEN
       derecha :=  rgt FROM nom_dominio WHERE iddominio = NEW.idpadre;
       UPDATE nom_dominio SET rgt = rgt + 2 WHERE rgt >= derecha;
       UPDATE nom_dominio SET lft = lft + 2 WHERE lft > derecha;
       NEW.lft := derecha;
       NEW.rgt := derecha + 1;
    ELSE
        derecha :=  MAX(rgt) FROM nom_dominio WHERE iddominio = idpadre;
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
  
  
  CREATE OR REPLACE FUNCTION mod_estructuracomp.ft_eliminar_nodo_dominio()
  RETURNS trigger AS
$BODY$
DECLARE
       ancho BIGINT;
BEGIN
     SET search_path = mod_estructuracomp;
     ancho := OLD.rgt - OLD.lft + 1;
     UPDATE nom_dominio SET rgt = rgt - ancho WHERE rgt > OLD.rgt;
     UPDATE nom_dominio SET lft = lft - ancho WHERE lft > OLD.rgt;
     RETURN OLD;
     EXCEPTION WHEN foreign_key_violation THEN
         RETURN OLD;
END;
$BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
  
  
CREATE OR REPLACE FUNCTION "mod_estructuracomp"."ft_insertar_fila" ()
RETURNS trigger AS
$body$
BEGIN
	IF (NEW.idpadre is null) THEN
		NEW.idpadre = NEW.idfila;
	END IF;
	RETURN NEW;
END;
$body$
LANGUAGE 'plpgsql'
IMMUTABLE
CALLED ON NULL INPUT
SECURITY INVOKER
COST 100;



-------------------------------------------------------------------------------
----Creación de triggers
--------------------------------------------------------------------------------	
	SET search_path = mod_estructuracomp, pg_catalog;
	
CREATE TRIGGER "t_insertar_nodo_fila" BEFORE INSERT 
ON "mod_estructuracomp"."nom_filaestruc" FOR EACH ROW 
EXECUTE PROCEDURE "mod_estructuracomp"."ft_insertar_fila"();
	

CREATE TRIGGER t_eliminar
  AFTER DELETE
  ON mod_estructuracomp.nom_dominio
  FOR EACH ROW
  EXECUTE PROCEDURE mod_estructuracomp.ft_eliminar_nodo_dominio();
  
CREATE TRIGGER t_insertar_dominio
  BEFORE INSERT
  ON mod_estructuracomp.nom_dominio
  FOR EACH ROW
  EXECUTE PROCEDURE mod_estructuracomp.ft_insertar_nodo_dominio();
	
	
	
	
CREATE TRIGGER t_eliminar
  AFTER DELETE
  ON mod_estructuracomp.nom_filaestruc
  FOR EACH ROW
  EXECUTE PROCEDURE mod_estructuracomp.ft_eliminar_nodo_fila();


CREATE TRIGGER t_insertar_fila
  BEFORE INSERT
  ON mod_estructuracomp.nom_filaestruc
  FOR EACH ROW
  EXECUTE PROCEDURE mod_estructuracomp.ft_insertar_nodo_fila();  
	
CREATE TRIGGER t_actualizararbol
    BEFORE INSERT ON dat_estructura
    FOR EACH ROW
    EXECUTE PROCEDURE ft_actualizacion_arbol();

CREATE TRIGGER t_actualizararbolop
    BEFORE INSERT ON dat_estructuraop
    FOR EACH ROW
    EXECUTE PROCEDURE ft_actualizacion_arbolop();

CREATE TRIGGER t_dat_estructura
    BEFORE UPDATE ON dat_estructura
    FOR EACH ROW
    EXECUTE PROCEDURE ft_chequear();




CREATE TRIGGER t_dat_estructuraop
    BEFORE UPDATE ON dat_estructuraop
    FOR EACH ROW
    EXECUTE PROCEDURE ft_chequear();

CREATE TRIGGER t_eliminar
    AFTER DELETE ON dat_estructura
    FOR EACH ROW
    EXECUTE PROCEDURE ft_eliminar_nodo();




CREATE TRIGGER t_eliminar
    AFTER DELETE ON dat_estructuraop
    FOR EACH ROW
    EXECUTE PROCEDURE ft_eliminar_nodoop();

CREATE TRIGGER t_insertar
    BEFORE INSERT ON dat_estructura
    FOR EACH ROW
    EXECUTE PROCEDURE ft_insertar_nodo();




CREATE TRIGGER t_insertar
    BEFORE INSERT ON dat_estructuraop
    FOR EACH ROW
    EXECUTE PROCEDURE ft_insertar_nodoop();




CREATE TRIGGER t_modificar
    AFTER UPDATE ON dat_estructuraop
    FOR EACH ROW
    EXECUTE PROCEDURE ft_modificar_nodoop();


--termina la tranzaccion--
COMMIT; 

