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
VALUES ('V5.0.0', 'V5.0.0','mod_estructuracomp',null,'C','P','I');
----------------------------------------------------------------------------
--Permisos del rol estructrua sobre el esquema mod_estructuracomp
----------------------------------------------------------------------------
----------------------------------------
--Permisos sobre el esquema
----------------------------------------
	SET search_path = mod_estructuracomp, pg_catalog;

GRANT USAGE ON SCHEMA mod_estructuracomp TO estructurasigis;
----------------------------------------
-- Permisos sobre secuencias
----------------------------------------
	SET search_path = mod_estructuracomp, pg_catalog;

GRANT SELECT, USAGE ON TABLE mod_estructuracomp.sec_cargomiliat_seq TO estructurasigis;
GRANT SELECT, USAGE ON TABLE mod_estructuracomp.sec_datcargo_seq TO estructurasigis;
GRANT SELECT, USAGE ON TABLE mod_estructuracomp.sec_datcargocivil_seq TO estructurasigis;
GRANT SELECT, USAGE ON TABLE mod_estructuracomp.sec_datdocoficial_seq TO estructurasigis;
GRANT SELECT, USAGE ON TABLE mod_estructuracomp.sec_datespecifcargo_seq TO estructurasigis;
GRANT SELECT, USAGE ON TABLE mod_estructuracomp.sec_datmodcargo_seq TO estructurasigis;
GRANT SELECT, USAGE ON TABLE mod_estructuracomp.sec_datmodulos_seq TO estructurasigis;
GRANT SELECT, USAGE ON TABLE mod_estructuracomp.sec_datpuesto_seq TO estructurasigis;
GRANT SELECT, USAGE ON TABLE mod_estructuracomp.sec_dattecnica_seq TO estructurasigis;
GRANT SELECT, USAGE ON TABLE mod_estructuracomp.sec_nomagrupacion_seq TO estructurasigis;
GRANT SELECT, USAGE ON TABLE mod_estructuracomp.sec_nomcalificadorcargo_seq TO estructurasigis;
GRANT SELECT, USAGE ON TABLE mod_estructuracomp.sec_nomcampoestruc_seq TO estructurasigis;
GRANT SELECT, USAGE ON TABLE mod_estructuracomp.sec_nomcargocivil_seq TO estructurasigis;
GRANT SELECT, USAGE ON TABLE mod_estructuracomp.sec_nomcargomilitar_seq TO estructurasigis;
GRANT SELECT, USAGE ON TABLE mod_estructuracomp.sec_nomcategcivil_seq TO estructurasigis;
GRANT SELECT, USAGE ON TABLE mod_estructuracomp.sec_nomcategocup_seq TO estructurasigis;
GRANT SELECT, USAGE ON TABLE mod_estructuracomp.sec_nomclasificacioncargo_seq TO estructurasigis;
GRANT SELECT, USAGE ON TABLE mod_estructuracomp.sec_nomdominio_seq TO estructurasigis;
GRANT SELECT, USAGE ON TABLE mod_estructuracomp.sec_nomescalasalarial_seq TO estructurasigis;
GRANT SELECT, USAGE ON TABLE mod_estructuracomp.sec_nomfilaestruc_seq TO estructurasigis;
GRANT SELECT, USAGE ON TABLE mod_estructuracomp.sec_nomgradomilit_seq TO estructurasigis;
GRANT SELECT, USAGE ON TABLE mod_estructuracomp.sec_nomgrupocomple_seq TO estructurasigis;
GRANT SELECT, USAGE ON TABLE mod_estructuracomp.sec_nomgsubcateg_seq TO estructurasigis;
GRANT SELECT, USAGE ON TABLE mod_estructuracomp.sec_nommodulo_seq TO estructurasigis;
GRANT SELECT, USAGE ON TABLE mod_estructuracomp.sec_nomnivelestr_seq TO estructurasigis;
GRANT SELECT, USAGE ON TABLE mod_estructuracomp.sec_nomnivelutilizacion_seq TO estructurasigis;
GRANT SELECT, USAGE ON TABLE mod_estructuracomp.sec_nomnomencladoreavestruc_seq TO estructurasigis;
GRANT SELECT, USAGE ON TABLE mod_estructuracomp.sec_nomorgano_seq TO estructurasigis;
GRANT SELECT, USAGE ON TABLE mod_estructuracomp.sec_nomprefijo_seq TO estructurasigis;
GRANT SELECT, USAGE ON TABLE mod_estructuracomp.sec_nomprepmilitar_seq TO estructurasigis;
GRANT SELECT, USAGE ON TABLE mod_estructuracomp.sec_nomsalario_seq TO estructurasigis;
GRANT SELECT, USAGE ON TABLE mod_estructuracomp.sec_nomtecnica_seq TO estructurasigis;
GRANT SELECT, USAGE ON TABLE mod_estructuracomp.sec_nomtipocalificador_seq TO estructurasigis;
GRANT SELECT, USAGE ON TABLE mod_estructuracomp.sec_nomtipocifra_seq TO estructurasigis;
GRANT SELECT, USAGE ON TABLE mod_estructuracomp.sec_nomtipodoc_seq TO estructurasigis;
GRANT SELECT, USAGE ON TABLE mod_estructuracomp.sec_nomvalor_defecto_seq TO estructurasigis;
GRANT SELECT, USAGE ON TABLE mod_estructuracomp.sec_nomvalorestruc_seq TO estructurasigis;


----------------------------------------
--Permisos sobre tablas
----------------------------------------
	SET search_path = mod_estructuracomp, pg_catalog;

GRANT SELECT, UPDATE, INSERT, DELETE ON TABLE mod_estructuracomp.dat_agrupacionest TO estructurasigis;
GRANT SELECT, UPDATE, INSERT, DELETE ON TABLE mod_estructuracomp.dat_agrupacionestop TO estructurasigis;
GRANT SELECT, UPDATE, INSERT, DELETE ON TABLE mod_estructuracomp.dat_cargo TO estructurasigis;
GRANT SELECT, UPDATE, INSERT, DELETE ON TABLE mod_estructuracomp.dat_cargocivil TO estructurasigis;
GRANT SELECT, UPDATE, INSERT, DELETE ON TABLE mod_estructuracomp.dat_cargomtar TO estructurasigis;
GRANT SELECT, UPDATE, INSERT, DELETE ON TABLE mod_estructuracomp.dat_docoficial TO estructurasigis;
GRANT SELECT, UPDATE, INSERT, DELETE ON TABLE mod_estructuracomp.dat_estructura TO estructurasigis;
GRANT SELECT, UPDATE, INSERT, DELETE ON TABLE mod_estructuracomp.dat_estructuraop TO estructurasigis;
GRANT SELECT, UPDATE, INSERT, DELETE ON TABLE mod_estructuracomp.dat_modcargo TO estructurasigis;
GRANT SELECT, UPDATE, INSERT, DELETE ON TABLE mod_estructuracomp.dat_modulos TO estructurasigis;
GRANT SELECT, UPDATE, INSERT, DELETE ON TABLE mod_estructuracomp.dat_puesto TO estructurasigis;
GRANT SELECT, UPDATE, INSERT, DELETE ON TABLE mod_estructuracomp.dat_tecnica TO estructurasigis;
GRANT SELECT, UPDATE, INSERT, DELETE ON TABLE mod_estructuracomp.nom_agrupacion TO estructurasigis;
GRANT SELECT, UPDATE, INSERT, DELETE ON TABLE mod_estructuracomp.nom_aristaeav TO estructurasigis;
GRANT SELECT, UPDATE, INSERT, DELETE ON TABLE mod_estructuracomp.nom_calificador_cargo TO estructurasigis;
GRANT SELECT, UPDATE, INSERT, DELETE ON TABLE mod_estructuracomp.nom_campoestruc TO estructurasigis;
GRANT SELECT, UPDATE, INSERT, DELETE ON TABLE mod_estructuracomp.nom_cargocivil TO estructurasigis;
GRANT SELECT, UPDATE, INSERT, DELETE ON TABLE mod_estructuracomp.nom_cargomilitar TO estructurasigis;
GRANT SELECT, UPDATE, INSERT, DELETE ON TABLE mod_estructuracomp.nom_categcivil TO estructurasigis;
GRANT SELECT, UPDATE, INSERT, DELETE ON TABLE mod_estructuracomp.nom_categocup TO estructurasigis;
GRANT SELECT, UPDATE, INSERT, DELETE ON TABLE mod_estructuracomp.nom_clasificacion_cargo TO estructurasigis;
GRANT SELECT, UPDATE, INSERT, DELETE ON TABLE mod_estructuracomp.nom_dominio TO estructurasigis;
GRANT SELECT, UPDATE, INSERT, DELETE ON TABLE mod_estructuracomp.nom_escalasalarial TO estructurasigis;
GRANT SELECT, UPDATE, INSERT, DELETE ON TABLE mod_estructuracomp.nom_filaestruc TO estructurasigis;
GRANT SELECT, UPDATE, INSERT, DELETE ON TABLE mod_estructuracomp.nom_gradomilit TO estructurasigis;
GRANT SELECT, UPDATE, INSERT, DELETE ON TABLE mod_estructuracomp.nom_grupocomple TO estructurasigis;
GRANT SELECT, UPDATE, INSERT, DELETE ON TABLE mod_estructuracomp.nom_gsubcateg TO estructurasigis;
GRANT SELECT, UPDATE, INSERT, DELETE ON TABLE mod_estructuracomp.nom_modulo TO estructurasigis;
GRANT SELECT, UPDATE, INSERT, DELETE ON TABLE mod_estructuracomp.nom_nivel_utilizacion TO estructurasigis;
GRANT SELECT, UPDATE, INSERT, DELETE ON TABLE mod_estructuracomp.nom_nivelestr TO estructurasigis;
GRANT SELECT, UPDATE, INSERT, DELETE ON TABLE mod_estructuracomp.nom_nomencladoreavestruc TO estructurasigis;
GRANT SELECT, UPDATE, INSERT, DELETE ON TABLE mod_estructuracomp.nom_organo TO estructurasigis;
GRANT SELECT, UPDATE, INSERT, DELETE ON TABLE mod_estructuracomp.nom_prefijo TO estructurasigis;
GRANT SELECT, UPDATE, INSERT, DELETE ON TABLE mod_estructuracomp.nom_prepmilitar TO estructurasigis;
GRANT SELECT, UPDATE, INSERT, DELETE ON TABLE mod_estructuracomp.nom_salario TO estructurasigis;
GRANT SELECT, UPDATE, INSERT, DELETE ON TABLE mod_estructuracomp.nom_tecnica TO estructurasigis;
GRANT SELECT, UPDATE, INSERT, DELETE ON TABLE mod_estructuracomp.nom_tipo_calificador TO estructurasigis;
GRANT SELECT, UPDATE, INSERT, DELETE ON TABLE mod_estructuracomp.nom_tipocifra TO estructurasigis;
GRANT SELECT, UPDATE, INSERT, DELETE ON TABLE mod_estructuracomp.nom_tipodoc TO estructurasigis;
GRANT SELECT, UPDATE, INSERT, DELETE ON TABLE mod_estructuracomp.nom_valor_defecto TO estructurasigis;
GRANT SELECT, UPDATE, INSERT, DELETE ON TABLE mod_estructuracomp.nom_valorestruc TO estructurasigis;

----------------------------------------
--Permisos sobre funciones
----------------------------------------
	SET search_path = mod_estructuracomp, pg_catalog;

GRANT EXECUTE ON FUNCTION mod_estructuracomp."Nemury_ContarNietosxEAVxidOrgPadre"(numeric, numeric, numeric) TO estructurasigis;
GRANT EXECUTE ON FUNCTION mod_estructuracomp.cant_entida_unidades_agrupacion(character varying, character varying) TO estructurasigis;
GRANT EXECUTE ON FUNCTION mod_estructuracomp."f_ getHijosEstructura"(numeric) TO estructurasigis;
GRANT EXECUTE ON FUNCTION mod_estructuracomp.f_adicinardom(bigint, bigint) TO estructurasigis;
GRANT EXECUTE ON FUNCTION mod_estructuracomp.f_buscaridcampo(character varying, numeric) TO estructurasigis;

GRANT EXECUTE ON FUNCTION mod_estructuracomp.f_cargosporestructura(character varying) TO estructurasigis;
GRANT EXECUTE ON FUNCTION mod_estructuracomp.f_contarcargosporgupoescala(character varying, character varying, character varying, character varying) TO estructurasigis;
GRANT EXECUTE ON FUNCTION mod_estructuracomp."f_existeEstructuraop"(numeric) TO estructurasigis;
GRANT EXECUTE ON FUNCTION mod_estructuracomp."f_getEstructurasInternas"(numeric, boolean) TO estructurasigis;
GRANT EXECUTE ON FUNCTION mod_estructuracomp."f_getHijosInterna"(numeric) TO estructurasigis;
GRANT EXECUTE ON FUNCTION mod_estructuracomp.f_getdatosestructura(numeric) TO estructurasigis;
GRANT EXECUTE ON FUNCTION mod_estructuracomp."f_listadoEstructuras"(numeric, numeric) TO estructurasigis;
GRANT EXECUTE ON FUNCTION mod_estructuracomp."f_listadoEstructurasInternas"(numeric, numeric) TO estructurasigis;
GRANT EXECUTE ON FUNCTION mod_estructuracomp.f_rep_areas(character varying, character varying) TO estructurasigis;
GRANT EXECUTE ON FUNCTION mod_estructuracomp.f_rep_areascategorias(character varying, character varying) TO estructurasigis;
GRANT EXECUTE ON FUNCTION mod_estructuracomp.f_rep_areascategorias1(numeric, character varying) TO estructurasigis;
GRANT EXECUTE ON FUNCTION mod_estructuracomp.f_rep_buscaridcalaificador(character varying) TO estructurasigis;
GRANT EXECUTE ON FUNCTION mod_estructuracomp.f_rep_buscaridcategocup(character varying) TO estructurasigis;
GRANT EXECUTE ON FUNCTION mod_estructuracomp.f_rep_buscarideav(character varying) TO estructurasigis;
GRANT EXECUTE ON FUNCTION mod_estructuracomp.f_rep_buscaridestructura(character varying) TO estructurasigis;
GRANT EXECUTE ON FUNCTION mod_estructuracomp.f_rep_buscaridestructuraop(character varying) TO estructurasigis;
GRANT EXECUTE ON FUNCTION mod_estructuracomp.f_rep_calificador_cargos(character varying) TO estructurasigis;
GRANT EXECUTE ON FUNCTION mod_estructuracomp.f_rep_cargos_areas_categoria(character varying, character varying) TO estructurasigis;
GRANT EXECUTE ON FUNCTION mod_estructuracomp.f_rep_componentesestructurainterna(character varying) TO estructurasigis;
GRANT EXECUTE ON FUNCTION mod_estructuracomp.f_rep_contarcargosporcategoriaocupacional(character varying, character varying) TO estructurasigis;
GRANT EXECUTE ON FUNCTION mod_estructuracomp.f_rep_contarhijospororgano(numeric, numeric) TO estructurasigis;
GRANT EXECUTE ON FUNCTION mod_estructuracomp.f_rep_entidades(numeric, character varying, numeric) TO estructurasigis;
GRANT EXECUTE ON FUNCTION mod_estructuracomp.f_rep_grupoescalacategocupacional(character varying, character varying) TO estructurasigis;
GRANT EXECUTE ON FUNCTION mod_estructuracomp.f_rep_nemurycontarhijosxeav1(numeric, numeric) TO estructurasigis;
GRANT EXECUTE ON FUNCTION mod_estructuracomp.f_rep_plantilla_cargos(character varying, character varying) TO estructurasigis;
GRANT EXECUTE ON FUNCTION mod_estructuracomp.f_rep_relacion_localizacion_unidades_nivelest(character varying, character varying) TO estructurasigis;
GRANT EXECUTE ON FUNCTION mod_estructuracomp.f_rep_relacion_localizunidadesporentidad(character varying, character varying) TO estructurasigis;
GRANT EXECUTE ON FUNCTION mod_estructuracomp.f_rep_relacion_nivel1porclasif1() TO estructurasigis;
GRANT EXECUTE ON FUNCTION mod_estructuracomp.f_rep_relacion_registro_entidades_agrupacion(character varying, character varying) TO estructurasigis;
GRANT EXECUTE ON FUNCTION mod_estructuracomp.f_rep_relacionagruppornivel11(character varying) TO estructurasigis;
GRANT EXECUTE ON FUNCTION mod_estructuracomp.f_rep_relacionentidadesporagrupaciones1(character varying, character varying) TO estructurasigis;
GRANT EXECUTE ON FUNCTION mod_estructuracomp.f_rep_resumen_agrupaciones_nivel_segun_clasificacion(character varying) TO estructurasigis;
GRANT EXECUTE ON FUNCTION mod_estructuracomp.f_rep_resumen_categoria_entidades_agrupaciones(character varying, character varying) TO estructurasigis;
GRANT EXECUTE ON FUNCTION mod_estructuracomp.f_rep_resumentidporagrupaciporclasificac(character varying, character varying) TO estructurasigis;
GRANT EXECUTE ON FUNCTION mod_estructuracomp.f_totalgrupoescala(character varying, character varying) TO estructurasigis;
GRANT EXECUTE ON FUNCTION mod_estructuracomp.posicional(text) TO estructurasigis;
GRANT EXECUTE ON FUNCTION mod_estructuracomp.posicionalmitad(numeric) TO estructurasigis;
GRANT EXECUTE ON FUNCTION mod_estructuracomp.reordenar_dat_estructura(bigint, bigint) TO estructurasigis;
GRANT EXECUTE ON FUNCTION mod_estructuracomp.reordenar_dat_estructuraop(bigint, bigint) TO estructurasigis;
GRANT EXECUTE ON FUNCTION mod_estructuracomp.reordenar_nom_dpa(bigint, bigint) TO estructurasigis;
GRANT EXECUTE ON FUNCTION mod_estructuracomp.rep_contarhijosconvalorencampo(numeric, numeric, numeric, character varying) TO estructurasigis;
GRANT EXECUTE ON FUNCTION mod_estructuracomp.rep_contarhijosconvalorencampo(numeric, numeric, character varying) TO estructurasigis;
GRANT EXECUTE ON FUNCTION mod_estructuracomp.rep_contarxclasf(numeric, numeric, numeric) TO estructurasigis;

GRANT EXECUTE ON FUNCTION mod_estructuracomp.chequear() TO estructurasigis;
GRANT EXECUTE ON FUNCTION mod_estructuracomp.ft_actualizacion_arbol() TO estructurasigis;
GRANT EXECUTE ON FUNCTION mod_estructuracomp.ft_actualizacion_arbolop() TO estructurasigis;
GRANT EXECUTE ON FUNCTION mod_estructuracomp.ft_chequear() TO estructurasigis;
GRANT EXECUTE ON FUNCTION mod_estructuracomp.ft_eliminar_nodo() TO estructurasigis;
GRANT EXECUTE ON FUNCTION mod_estructuracomp.ft_eliminar_nodo_dominio() TO estructurasigis;
GRANT EXECUTE ON FUNCTION mod_estructuracomp.ft_eliminar_nodo_fila() TO estructurasigis;
GRANT EXECUTE ON FUNCTION mod_estructuracomp.ft_eliminar_nodoop() TO estructurasigis;
GRANT EXECUTE ON FUNCTION mod_estructuracomp.ft_insertar_fila() TO estructurasigis;
GRANT EXECUTE ON FUNCTION mod_estructuracomp.ft_insertar_nodo() TO estructurasigis;
GRANT EXECUTE ON FUNCTION mod_estructuracomp.ft_insertar_nodo_dominio() TO estructurasigis;
GRANT EXECUTE ON FUNCTION mod_estructuracomp.ft_insertar_nodo_fila() TO estructurasigis;
GRANT EXECUTE ON FUNCTION mod_estructuracomp.ft_insertar_nodoop() TO estructurasigis;
GRANT EXECUTE ON FUNCTION mod_estructuracomp.ft_modificar_nodo() TO estructurasigis;
GRANT EXECUTE ON FUNCTION mod_estructuracomp.ft_modificar_nodoop() TO estructurasigis;

GRANT EXECUTE ON FUNCTION mod_estructuracomp.ft_insertar_fila () TO estructurasigis;
----------------------------------------------------------------------------
--Permisos del rol sigis sobre el esquema mod_estructuracomp
----------------------------------------------------------------------------
----------------------------------------
--Permisos sobre el esquema
----------------------------------------
	SET search_path = mod_estructuracomp, pg_catalog;

ALTER SCHEMA "mod_estructuracomp" OWNER TO estructurasigis;
----------------------------------------
-- Permisos sobre secuencias
----------------------------------------
	SET search_path = mod_estructuracomp, pg_catalog;

ALTER TABLE "mod_estructuracomp"."sec_cargomiliat_seq"
  OWNER TO estructurasigis;
ALTER TABLE "mod_estructuracomp"."sec_datcargo_seq"
  OWNER TO estructurasigis;
ALTER TABLE "mod_estructuracomp"."sec_datcargocivil_seq"
  OWNER TO estructurasigis;
ALTER TABLE "mod_estructuracomp"."sec_datdocoficial_seq"
  OWNER TO estructurasigis;
ALTER TABLE "mod_estructuracomp"."sec_datespecifcargo_seq"
  OWNER TO estructurasigis;
ALTER TABLE "mod_estructuracomp"."sec_datmodcargo_seq"
  OWNER TO estructurasigis;
ALTER TABLE "mod_estructuracomp"."sec_datmodulos_seq"
  OWNER TO estructurasigis;
ALTER TABLE "mod_estructuracomp"."sec_datpuesto_seq"
  OWNER TO estructurasigis;
ALTER TABLE "mod_estructuracomp"."sec_dattecnica_seq"
  OWNER TO estructurasigis;
ALTER TABLE "mod_estructuracomp"."sec_nomagrupacion_seq"
  OWNER TO estructurasigis;
ALTER TABLE "mod_estructuracomp"."sec_nomcalificadorcargo_seq"
  OWNER TO estructurasigis;
ALTER TABLE "mod_estructuracomp"."sec_nomcampoestruc_seq"
  OWNER TO estructurasigis;
ALTER TABLE "mod_estructuracomp"."sec_nomcargocivil_seq"
  OWNER TO estructurasigis;
ALTER TABLE "mod_estructuracomp"."sec_nomcargomilitar_seq"
  OWNER TO estructurasigis;
ALTER TABLE "mod_estructuracomp"."sec_nomcategcivil_seq"
  OWNER TO estructurasigis;
ALTER TABLE "mod_estructuracomp"."sec_nomcategocup_seq"
  OWNER TO estructurasigis;
ALTER TABLE "mod_estructuracomp"."sec_nomclasificacioncargo_seq"
  OWNER TO estructurasigis;
ALTER TABLE "mod_estructuracomp"."sec_nomdominio_seq"
  OWNER TO estructurasigis;
ALTER TABLE "mod_estructuracomp"."sec_nomescalasalarial_seq"
  OWNER TO estructurasigis;
ALTER TABLE "mod_estructuracomp"."sec_nomfilaestruc_seq"
  OWNER TO estructurasigis;
ALTER TABLE "mod_estructuracomp"."sec_nomgradomilit_seq"
  OWNER TO estructurasigis;
ALTER TABLE "mod_estructuracomp"."sec_nomgrupocomple_seq"
  OWNER TO estructurasigis;
ALTER TABLE "mod_estructuracomp"."sec_nomgsubcateg_seq"
  OWNER TO estructurasigis;
ALTER TABLE "mod_estructuracomp"."sec_nommodulo_seq"
  OWNER TO estructurasigis;
ALTER TABLE "mod_estructuracomp"."sec_nomnivelestr_seq"
  OWNER TO estructurasigis;
ALTER TABLE "mod_estructuracomp"."sec_nomnivelutilizacion_seq"
  OWNER TO estructurasigis;
ALTER TABLE "mod_estructuracomp"."sec_nomnomencladoreavestruc_seq"
  OWNER TO estructurasigis;
ALTER TABLE "mod_estructuracomp"."sec_nomorgano_seq"
  OWNER TO estructurasigis;
ALTER TABLE "mod_estructuracomp"."sec_nomprefijo_seq"
  OWNER TO estructurasigis;
ALTER TABLE "mod_estructuracomp"."sec_nomprepmilitar_seq"
  OWNER TO estructurasigis;
ALTER TABLE "mod_estructuracomp"."sec_nomsalario_seq"
  OWNER TO estructurasigis;
ALTER TABLE "mod_estructuracomp"."sec_nomtecnica_seq"
  OWNER TO estructurasigis;
ALTER TABLE "mod_estructuracomp"."sec_nomtipocalificador_seq"
  OWNER TO estructurasigis;
ALTER TABLE "mod_estructuracomp"."sec_nomtipocifra_seq"
  OWNER TO estructurasigis;
ALTER TABLE "mod_estructuracomp"."sec_nomtipodoc_seq"
  OWNER TO estructurasigis;
ALTER TABLE "mod_estructuracomp"."sec_nomvalor_defecto_seq"
  OWNER TO estructurasigis;
ALTER TABLE "mod_estructuracomp"."sec_nomvalorestruc_seq"
  OWNER TO estructurasigis;

----------------------------------------
-- Permisos sobre tablas
----------------------------------------
	SET search_path = mod_estructuracomp, pg_catalog;
	
ALTER TABLE "mod_estructuracomp"."dat_agrupacionest"
  OWNER TO estructurasigis;
ALTER TABLE "mod_estructuracomp"."dat_agrupacionestop"
  OWNER TO estructurasigis;
ALTER TABLE "mod_estructuracomp"."dat_cargo"
  OWNER TO estructurasigis;
ALTER TABLE "mod_estructuracomp"."dat_cargocivil"
  OWNER TO estructurasigis;
ALTER TABLE "mod_estructuracomp"."dat_cargomtar"
  OWNER TO estructurasigis;
ALTER TABLE "mod_estructuracomp"."dat_docoficial"
  OWNER TO estructurasigis;
ALTER TABLE "mod_estructuracomp"."dat_estructura"
  OWNER TO estructurasigis;
ALTER TABLE "mod_estructuracomp"."dat_estructuraop"
  OWNER TO estructurasigis;
ALTER TABLE "mod_estructuracomp"."dat_modcargo"
  OWNER TO estructurasigis;
ALTER TABLE "mod_estructuracomp"."dat_modulos"
  OWNER TO estructurasigis;
ALTER TABLE "mod_estructuracomp"."dat_puesto"
  OWNER TO estructurasigis;
ALTER TABLE "mod_estructuracomp"."dat_tecnica"
  OWNER TO estructurasigis;
ALTER TABLE "mod_estructuracomp"."nom_agrupacion"
  OWNER TO estructurasigis;
ALTER TABLE "mod_estructuracomp"."nom_aristaeav"
  OWNER TO estructurasigis;
ALTER TABLE "mod_estructuracomp"."nom_calificador_cargo"
  OWNER TO estructurasigis;
ALTER TABLE "mod_estructuracomp"."nom_campoestruc"
  OWNER TO estructurasigis;
ALTER TABLE "mod_estructuracomp"."nom_cargocivil"
  OWNER TO estructurasigis;
ALTER TABLE "mod_estructuracomp"."nom_cargomilitar"
  OWNER TO estructurasigis;
ALTER TABLE "mod_estructuracomp"."nom_categcivil"
  OWNER TO estructurasigis;
ALTER TABLE "mod_estructuracomp"."nom_categocup"
  OWNER TO estructurasigis;
ALTER TABLE "mod_estructuracomp"."nom_clasificacion_cargo"
  OWNER TO estructurasigis;
ALTER TABLE "mod_estructuracomp"."nom_dominio"
  OWNER TO estructurasigis;
ALTER TABLE "mod_estructuracomp"."nom_escalasalarial"
  OWNER TO estructurasigis;
ALTER TABLE "mod_estructuracomp"."nom_filaestruc"
  OWNER TO estructurasigis;
ALTER TABLE "mod_estructuracomp"."nom_gradomilit"
  OWNER TO estructurasigis;
ALTER TABLE "mod_estructuracomp"."nom_grupocomple"
  OWNER TO estructurasigis;
ALTER TABLE "mod_estructuracomp"."nom_gsubcateg"
  OWNER TO estructurasigis;
ALTER TABLE "mod_estructuracomp"."nom_modulo"
  OWNER TO estructurasigis;
ALTER TABLE "mod_estructuracomp"."nom_nivel_utilizacion"
  OWNER TO estructurasigis;
ALTER TABLE "mod_estructuracomp"."nom_nivelestr"
  OWNER TO estructurasigis;
ALTER TABLE "mod_estructuracomp"."nom_nomencladoreavestruc"
  OWNER TO estructurasigis;
ALTER TABLE "mod_estructuracomp"."nom_organo"
  OWNER TO estructurasigis;
ALTER TABLE "mod_estructuracomp"."nom_prefijo"
  OWNER TO estructurasigis;
ALTER TABLE "mod_estructuracomp"."nom_prepmilitar"
  OWNER TO estructurasigis;
ALTER TABLE "mod_estructuracomp"."nom_salario"
  OWNER TO estructurasigis;
ALTER TABLE "mod_estructuracomp"."nom_tecnica"
  OWNER TO estructurasigis;
ALTER TABLE "mod_estructuracomp"."nom_tipo_calificador"
  OWNER TO estructurasigis;
ALTER TABLE "mod_estructuracomp"."nom_tipocifra"
  OWNER TO estructurasigis;
ALTER TABLE "mod_estructuracomp"."nom_tipodoc"
  OWNER TO estructurasigis;
ALTER TABLE "mod_estructuracomp"."nom_valor_defecto"
  OWNER TO estructurasigis;
ALTER TABLE "mod_estructuracomp"."nom_valorestruc"
  OWNER TO estructurasigis;
	
ALTER TABLE mod_estructuracomp.dat_subordinacion 
OWNER TO estructurasigis;

ALTER TABLE mod_estructuracomp.nom_subordinacion 
OWNER TO estructurasigis;
----------------------------------------
-- Permisos sobre funciones
----------------------------------------
	SET search_path = mod_estructuracomp, pg_catalog;
	
ALTER FUNCTION "mod_estructuracomp"."Nemury_ContarNietosxEAVxidOrgPadre"(IdPadre numeric, IdOrgano numeric, Eav numeric)
  OWNER TO estructurasigis;
ALTER FUNCTION "mod_estructuracomp"."cant_entida_unidades_agrupacion"(nombre_nivel varchar, codigo_nivel varchar)
  OWNER TO estructurasigis;
ALTER FUNCTION "mod_estructuracomp"."chequear"()
  OWNER TO estructurasigis;
ALTER FUNCTION "mod_estructuracomp"."f_ getHijosEstructura"(idpadre numeric)
  OWNER TO estructurasigis;
ALTER FUNCTION "mod_estructuracomp"."f_adicinardom"(pos bigint, bin bigint)
  OWNER TO estructurasigis;
ALTER FUNCTION "mod_estructuracomp"."f_buscaridcampo"(nombre varchar, idEAV numeric)
  OWNER TO estructurasigis;
ALTER FUNCTION "mod_estructuracomp"."f_cargosporestructura"(denominacion_estructura varchar)
  OWNER TO estructurasigis;
ALTER FUNCTION "mod_estructuracomp"."f_contarcargosporgupoescala"(grupoescala varchar, categocupacional varchar, denominacion_estructura varchar, codigo varchar)
  OWNER TO estructurasigis;
ALTER FUNCTION "mod_estructuracomp"."f_existeEstructuraop"(id_estructuraop numeric)
  OWNER TO estructurasigis;
ALTER FUNCTION "mod_estructuracomp"."f_getEstructurasInternas"(idestructura numeric, raiz boolean)
  OWNER TO estructurasigis;
ALTER FUNCTION "mod_estructuracomp"."f_getHijosInterna"(idpadre numeric)
  OWNER TO estructurasigis;
ALTER FUNCTION "mod_estructuracomp"."f_getdatosestructura"(estructura numeric)
  OWNER TO estructurasigis;
ALTER FUNCTION "mod_estructuracomp"."f_listadoEstructuras"(comienzo numeric, fin numeric)
  OWNER TO estructurasigis;
ALTER FUNCTION "mod_estructuracomp"."f_listadoEstructurasInternas"(comienzo numeric, fin numeric)
  OWNER TO estructurasigis;
ALTER FUNCTION "mod_estructuracomp"."f_rep_areas"(denominacion_estructura varchar, denominacion_estructuraop varchar)
  OWNER TO estructurasigis;
ALTER FUNCTION "mod_estructuracomp"."f_rep_areascategorias"(denominacion_area varchar, denominacion_categoria varchar)
  OWNER TO estructurasigis;
ALTER FUNCTION "mod_estructuracomp"."f_rep_areascategorias1"(id_area numeric, denominacion_categoria varchar)
  OWNER TO estructurasigis;
ALTER FUNCTION "mod_estructuracomp"."f_rep_buscaridcalaificador"(denominacion_calificador varchar)
  OWNER TO estructurasigis;
ALTER FUNCTION "mod_estructuracomp"."f_rep_buscaridcategocup"(denominacion_categocup varchar)
  OWNER TO estructurasigis;
ALTER FUNCTION "mod_estructuracomp"."f_rep_buscarideav"(nombre varchar)
  OWNER TO estructurasigis;
ALTER FUNCTION "mod_estructuracomp"."f_rep_buscaridestructura"(denominacion varchar)
  OWNER TO estructurasigis;
ALTER FUNCTION "mod_estructuracomp"."f_rep_buscaridestructuraop"(denominacion varchar)
  OWNER TO estructurasigis;
ALTER FUNCTION "mod_estructuracomp"."f_rep_calificador_cargos"(denominacion_calificador varchar)
  OWNER TO estructurasigis;
ALTER FUNCTION "mod_estructuracomp"."f_rep_cargos_areas_categoria"(denominacion_estructura varchar, codigo_estructura varchar)
  OWNER TO estructurasigis;
ALTER FUNCTION "mod_estructuracomp"."f_rep_componentesestructurainterna"(denominacion_estructura varchar)
  OWNER TO estructurasigis;
ALTER FUNCTION "mod_estructuracomp"."f_rep_contarcargosporcategoriaocupacional"(denominacion_categoriaocupacional varchar, denominacion_estructuraop varchar)
  OWNER TO estructurasigis;
ALTER FUNCTION "mod_estructuracomp"."f_rep_contarhijospororgano"(IdPadre numeric, idOrgano numeric)
  OWNER TO estructurasigis;
ALTER FUNCTION "mod_estructuracomp"."f_rep_entidades"(idestructura numeric, nombre varchar, idEAV numeric)
  OWNER TO estructurasigis;
ALTER FUNCTION "mod_estructuracomp"."f_rep_grupoescalacategocupacional"(denominacion_estructura varchar, codigo_estructura varchar)
  OWNER TO estructurasigis;
ALTER FUNCTION "mod_estructuracomp"."f_rep_nemurycontarhijosxeav1"(IdPadre numeric, Eav numeric)
  OWNER TO estructurasigis;
ALTER FUNCTION "mod_estructuracomp"."f_rep_plantilla_cargos"(denominacion_estructura varchar, codigo_estructura varchar)
  OWNER TO estructurasigis;
ALTER FUNCTION "mod_estructuracomp"."f_rep_relacion_localizacion_unidades_nivelest"(NombrePadre varchar, Codigo varchar)
  OWNER TO estructurasigis;
ALTER FUNCTION "mod_estructuracomp"."f_rep_relacion_localizunidadesporentidad"(NombreNivel varchar, Codigo varchar)
  OWNER TO estructurasigis;
ALTER FUNCTION "mod_estructuracomp"."f_rep_relacion_nivel1porclasif1"()
  OWNER TO estructurasigis;
ALTER FUNCTION "mod_estructuracomp"."f_rep_relacion_registro_entidades_agrupacion"(nombrenivel varchar, codigonivel varchar)
  OWNER TO estructurasigis;
ALTER FUNCTION "mod_estructuracomp"."f_rep_relacionagruppornivel11"(NombreNivel varchar)
  OWNER TO estructurasigis;
ALTER FUNCTION "mod_estructuracomp"."f_rep_relacionentidadesporagrupaciones1"(NombreNivel varchar, Codigo varchar)
  OWNER TO estructurasigis;
ALTER FUNCTION "mod_estructuracomp"."f_rep_resumen_agrupaciones_nivel_segun_clasificacion"(nombrenivel1 varchar)
  OWNER TO estructurasigis;
ALTER FUNCTION "mod_estructuracomp"."f_rep_resumen_categoria_entidades_agrupaciones"(nombrenivel varchar, codigo varchar)
  OWNER TO estructurasigis;
ALTER FUNCTION "mod_estructuracomp"."f_rep_resumentidporagrupaciporclasificac"(nombrenivel1 varchar, codigo varchar)
  OWNER TO estructurasigis;
ALTER FUNCTION "mod_estructuracomp"."f_totalgrupoescala"(denominacion_estructura varchar, grupocomple varchar)
  OWNER TO estructurasigis;
ALTER FUNCTION "mod_estructuracomp"."ft_actualizacion_arbol"()
  OWNER TO estructurasigis;
ALTER FUNCTION "mod_estructuracomp"."ft_actualizacion_arbolop"()
  OWNER TO estructurasigis;
ALTER FUNCTION "mod_estructuracomp"."ft_chequear"()
  OWNER TO estructurasigis;
ALTER FUNCTION "mod_estructuracomp"."ft_eliminar_nodo"()
  OWNER TO estructurasigis;
ALTER FUNCTION "mod_estructuracomp"."ft_eliminar_nodo_dominio"()
  OWNER TO estructurasigis;
ALTER FUNCTION "mod_estructuracomp"."ft_eliminar_nodo_fila"()
  OWNER TO estructurasigis;
ALTER FUNCTION "mod_estructuracomp"."ft_eliminar_nodoop"()
  OWNER TO estructurasigis;
ALTER FUNCTION "mod_estructuracomp"."ft_insertar_fila"()
  OWNER TO estructurasigis;
ALTER FUNCTION "mod_estructuracomp"."ft_insertar_nodo"()
  OWNER TO estructurasigis;
ALTER FUNCTION "mod_estructuracomp"."ft_insertar_nodo_dominio"()
  OWNER TO estructurasigis;
ALTER FUNCTION "mod_estructuracomp"."ft_insertar_nodo_fila"()
  OWNER TO estructurasigis;
ALTER FUNCTION "mod_estructuracomp"."ft_insertar_nodoop"()
  OWNER TO estructurasigis;
ALTER FUNCTION "mod_estructuracomp"."ft_modificar_nodo"()
  OWNER TO estructurasigis;
ALTER FUNCTION "mod_estructuracomp"."ft_modificar_nodoop"()
  OWNER TO estructurasigis;
ALTER FUNCTION "mod_estructuracomp"."posicional"(num text)
  OWNER TO estructurasigis;
ALTER FUNCTION "mod_estructuracomp"."posicionalmitad"(num numeric)
  OWNER TO estructurasigis;
ALTER FUNCTION "mod_estructuracomp"."reordenar_dat_estructura"(iddat_estructuranodo bigint, lftnodo bigint)
  OWNER TO estructurasigis;
ALTER FUNCTION "mod_estructuracomp"."reordenar_dat_estructuraop"(iddat_estructuranodo bigint, lftnodo bigint)
  OWNER TO estructurasigis;
ALTER FUNCTION "mod_estructuracomp"."reordenar_nom_dpa"(iddat_estructuranodo bigint, lftnodo bigint)
  OWNER TO estructurasigis;
ALTER FUNCTION "mod_estructuracomp"."rep_contarhijosconvalorencampo"(IdPadre numeric, idCampo numeric, Valor varchar)
  OWNER TO estructurasigis;
ALTER FUNCTION "mod_estructuracomp"."rep_contarhijosconvalorencampo"(Idpadre numeric, idcampo numeric, eav_hijo numeric, valor varchar)
  OWNER TO estructurasigis;
ALTER FUNCTION "mod_estructuracomp"."rep_contarxclasf"(id numeric, idnomeav numeric, idor numeric)
  OWNER TO estructurasigis;	
ALTER FUNCTION mod_estructuracomp.ft_insertar_fila () 
OWNER TO estructurasigis;
----------------------------------------
-- Permisos sobre tipos de datos
----------------------------------------
	SET search_path = mod_estructuracomp, pg_catalog;

ALTER TYPE mod_estructuracomp.cant_entida_unidades_agrupacion OWNER TO sigis;
ALTER TYPE mod_estructuracomp.td_agruppornivel1 OWNER TO sigis;
ALTER TYPE mod_estructuracomp.td_areas OWNER TO sigis;
ALTER TYPE mod_estructuracomp.td_calificador_cargo OWNER TO sigis;
ALTER TYPE mod_estructuracomp.td_cargos_areas_categoria OWNER TO sigis;
ALTER TYPE mod_estructuracomp.td_entidades OWNER TO sigis;
ALTER TYPE mod_estructuracomp.td_entidadesporagrupaciones OWNER TO sigis;
ALTER TYPE mod_estructuracomp.td_grupoescalacategocupacional1 OWNER TO sigis;
ALTER TYPE mod_estructuracomp.td_nivel1porclasif OWNER TO sigis;
ALTER TYPE mod_estructuracomp.td_plantilla_cargos OWNER TO sigis;
ALTER TYPE mod_estructuracomp.td_rep_componentesestructurainterna OWNER TO sigis;
ALTER TYPE mod_estructuracomp.td_rep_nivel1porclasif OWNER TO sigis;
ALTER TYPE mod_estructuracomp.td_rep_relacion_localizacion_unidades_nivelest OWNER TO sigis;
ALTER TYPE mod_estructuracomp.td_rep_relacion_localizunidadesporentidad OWNER TO sigis;
ALTER TYPE mod_estructuracomp.td_rep_relacion_registro_entidad_agrupacion1 OWNER TO sigis;
ALTER TYPE mod_estructuracomp.td_rep_relacionagruppornivel1 OWNER TO sigis;
ALTER TYPE mod_estructuracomp.td_rep_relacionentidadesporagrupaciones1 OWNER TO sigis;
ALTER TYPE mod_estructuracomp.td_rep_resumen_agrupaciones_nivel OWNER TO sigis;
ALTER TYPE mod_estructuracomp.td_rep_resumen_categoria_entidades_agrupaciones OWNER TO sigis;
ALTER TYPE mod_estructuracomp.td_rep_resumentidporagrupaciporclasificac OWNER TO sigis;
ALTER TYPE mod_estructuracomp.td_unidades OWNER TO sigis;
ALTER TYPE mod_estructuracomp.td_unidadesporentidad OWNER TO sigis;
--fin de la transaccion
COMMIT;