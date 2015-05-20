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
VALUES ('V5.0.0', 'V5.0.0','mod_recuperaciones','mod_estructuracomp','C','P','I');
----------------------------------------------------------------------------
--Permisos del rol reporte sobre el esquema mod_estructuracomp
----------------------------------------------------------------------------
----------------------------------------
--Permisos sobre el esquema
----------------------------------------
	SET search_path = mod_estructuracomp, pg_catalog;

GRANT USAGE
  ON SCHEMA "mod_estructuracomp" TO reportesigis;

----------------------------------------
-- Permisos sobre secuencias
----------------------------------------
	SET search_path = mod_estructuracomp, pg_catalog;

GRANT SELECT, USAGE ON TABLE mod_estructuracomp.sec_cargomiliat_seq TO reportesigis;
GRANT SELECT, USAGE ON TABLE mod_estructuracomp.sec_datcargo_seq TO reportesigis;
GRANT SELECT, USAGE ON TABLE mod_estructuracomp.sec_datcargocivil_seq TO reportesigis;
GRANT SELECT, USAGE ON TABLE mod_estructuracomp.sec_datdocoficial_seq TO reportesigis;
GRANT SELECT, USAGE ON TABLE mod_estructuracomp.sec_datespecifcargo_seq TO reportesigis;
GRANT SELECT, USAGE ON TABLE mod_estructuracomp.sec_datmodcargo_seq TO reportesigis;
GRANT SELECT, USAGE ON TABLE mod_estructuracomp.sec_datmodulos_seq TO reportesigis;
GRANT SELECT, USAGE ON TABLE mod_estructuracomp.sec_datpuesto_seq TO reportesigis;
GRANT SELECT, USAGE ON TABLE mod_estructuracomp.sec_dattecnica_seq TO reportesigis;
GRANT SELECT, USAGE ON TABLE mod_estructuracomp.sec_nomagrupacion_seq TO reportesigis;
GRANT SELECT, USAGE ON TABLE mod_estructuracomp.sec_nomcalificadorcargo_seq TO reportesigis;
GRANT SELECT, USAGE ON TABLE mod_estructuracomp.sec_nomcampoestruc_seq TO reportesigis;
GRANT SELECT, USAGE ON TABLE mod_estructuracomp.sec_nomcargocivil_seq TO reportesigis;
GRANT SELECT, USAGE ON TABLE mod_estructuracomp.sec_nomcargomilitar_seq TO reportesigis;
GRANT SELECT, USAGE ON TABLE mod_estructuracomp.sec_nomcategcivil_seq TO reportesigis;
GRANT SELECT, USAGE ON TABLE mod_estructuracomp.sec_nomcategocup_seq TO reportesigis;
GRANT SELECT, USAGE ON TABLE mod_estructuracomp.sec_nomclasificacioncargo_seq TO reportesigis;
GRANT SELECT, USAGE ON TABLE mod_estructuracomp.sec_nomdominio_seq TO reportesigis;
GRANT SELECT, USAGE ON TABLE mod_estructuracomp.sec_nomescalasalarial_seq TO reportesigis;
GRANT SELECT, USAGE ON TABLE mod_estructuracomp.sec_nomfilaestruc_seq TO reportesigis;
GRANT SELECT, USAGE ON TABLE mod_estructuracomp.sec_nomgradomilit_seq TO reportesigis;
GRANT SELECT, USAGE ON TABLE mod_estructuracomp.sec_nomgrupocomple_seq TO reportesigis;
GRANT SELECT, USAGE ON TABLE mod_estructuracomp.sec_nomgsubcateg_seq TO reportesigis;
GRANT SELECT, USAGE ON TABLE mod_estructuracomp.sec_nommodulo_seq TO reportesigis;
GRANT SELECT, USAGE ON TABLE mod_estructuracomp.sec_nomnivelestr_seq TO reportesigis;
GRANT SELECT, USAGE ON TABLE mod_estructuracomp.sec_nomnivelutilizacion_seq TO reportesigis;
GRANT SELECT, USAGE ON TABLE mod_estructuracomp.sec_nomnomencladoreavestruc_seq TO reportesigis;
GRANT SELECT, USAGE ON TABLE mod_estructuracomp.sec_nomorgano_seq TO reportesigis;
GRANT SELECT, USAGE ON TABLE mod_estructuracomp.sec_nomprefijo_seq TO reportesigis;
GRANT SELECT, USAGE ON TABLE mod_estructuracomp.sec_nomprepmilitar_seq TO reportesigis;
GRANT SELECT, USAGE ON TABLE mod_estructuracomp.sec_nomsalario_seq TO reportesigis;
GRANT SELECT, USAGE ON TABLE mod_estructuracomp.sec_nomtecnica_seq TO reportesigis;
GRANT SELECT, USAGE ON TABLE mod_estructuracomp.sec_nomtipocalificador_seq TO reportesigis;
GRANT SELECT, USAGE ON TABLE mod_estructuracomp.sec_nomtipocifra_seq TO reportesigis;
GRANT SELECT, USAGE ON TABLE mod_estructuracomp.sec_nomtipodoc_seq TO reportesigis;
GRANT SELECT, USAGE ON TABLE mod_estructuracomp.sec_nomvalor_defecto_seq TO reportesigis;
GRANT SELECT, USAGE ON TABLE mod_estructuracomp.sec_nomvalorestruc_seq TO reportesigis;

----------------------------------------
--Permisos sobre tablas
----------------------------------------
	SET search_path = mod_estructuracomp, pg_catalog;

GRANT SELECT ON TABLE mod_estructuracomp.dat_agrupacionest TO reportesigis;
GRANT SELECT ON TABLE mod_estructuracomp.dat_agrupacionestop TO reportesigis;
GRANT SELECT ON TABLE mod_estructuracomp.dat_cargo TO reportesigis;
GRANT SELECT ON TABLE mod_estructuracomp.dat_cargocivil TO reportesigis;
GRANT SELECT ON TABLE mod_estructuracomp.dat_cargomtar TO reportesigis;
GRANT SELECT ON TABLE mod_estructuracomp.dat_docoficial TO reportesigis;
GRANT SELECT ON TABLE mod_estructuracomp.dat_estructura TO reportesigis;
GRANT SELECT ON TABLE mod_estructuracomp.dat_estructuraop TO reportesigis;
GRANT SELECT ON TABLE mod_estructuracomp.dat_modcargo TO reportesigis;
GRANT SELECT ON TABLE mod_estructuracomp.dat_modulos TO reportesigis;
GRANT SELECT ON TABLE mod_estructuracomp.dat_puesto TO reportesigis;
GRANT SELECT ON TABLE mod_estructuracomp.dat_tecnica TO reportesigis;
GRANT SELECT ON TABLE mod_estructuracomp.nom_agrupacion TO reportesigis;
GRANT SELECT ON TABLE mod_estructuracomp.nom_aristaeav TO reportesigis;
GRANT SELECT ON TABLE mod_estructuracomp.nom_calificador_cargo TO reportesigis;
GRANT SELECT ON TABLE mod_estructuracomp.nom_campoestruc TO reportesigis;
GRANT SELECT ON TABLE mod_estructuracomp.nom_cargocivil TO reportesigis;
GRANT SELECT ON TABLE mod_estructuracomp.nom_cargomilitar TO reportesigis;
GRANT SELECT ON TABLE mod_estructuracomp.nom_categcivil TO reportesigis;
GRANT SELECT ON TABLE mod_estructuracomp.nom_categocup TO reportesigis;
GRANT SELECT ON TABLE mod_estructuracomp.nom_clasificacion_cargo TO reportesigis;
GRANT SELECT ON TABLE mod_estructuracomp.nom_dominio TO reportesigis;
GRANT SELECT ON TABLE mod_estructuracomp.nom_escalasalarial TO reportesigis;
GRANT SELECT ON TABLE mod_estructuracomp.nom_filaestruc TO reportesigis;
GRANT SELECT ON TABLE mod_estructuracomp.nom_gradomilit TO reportesigis;
GRANT SELECT ON TABLE mod_estructuracomp.nom_grupocomple TO reportesigis;
GRANT SELECT ON TABLE mod_estructuracomp.nom_gsubcateg TO reportesigis;
GRANT SELECT ON TABLE mod_estructuracomp.nom_modulo TO reportesigis;
GRANT SELECT ON TABLE mod_estructuracomp.nom_nivel_utilizacion TO reportesigis;
GRANT SELECT ON TABLE mod_estructuracomp.nom_nivelestr TO reportesigis;
GRANT SELECT ON TABLE mod_estructuracomp.nom_nomencladoreavestruc TO reportesigis;
GRANT SELECT ON TABLE mod_estructuracomp.nom_organo TO reportesigis;
GRANT SELECT ON TABLE mod_estructuracomp.nom_prefijo TO reportesigis;
GRANT SELECT ON TABLE mod_estructuracomp.nom_prepmilitar TO reportesigis;
GRANT SELECT ON TABLE mod_estructuracomp.nom_salario TO reportesigis;
GRANT SELECT ON TABLE mod_estructuracomp.nom_tecnica TO reportesigis;
GRANT SELECT ON TABLE mod_estructuracomp.nom_tipo_calificador TO reportesigis;
GRANT SELECT ON TABLE mod_estructuracomp.nom_tipocifra TO reportesigis;
GRANT SELECT ON TABLE mod_estructuracomp.nom_tipodoc TO reportesigis;
GRANT SELECT ON TABLE mod_estructuracomp.nom_valor_defecto TO reportesigis;
GRANT SELECT ON TABLE mod_estructuracomp.nom_valorestruc TO reportesigis;


----------------------------------------
--Permisos sobre funciones
----------------------------------------
	SET search_path = mod_estructuracomp, pg_catalog;

GRANT EXECUTE ON FUNCTION mod_estructuracomp."Nemury_ContarNietosxEAVxidOrgPadre"(numeric, numeric, numeric) TO reportesigis;
GRANT EXECUTE ON FUNCTION mod_estructuracomp.cant_entida_unidades_agrupacion(character varying, character varying) TO reportesigis;
GRANT EXECUTE ON FUNCTION mod_estructuracomp."f_ getHijosEstructura"(numeric) TO reportesigis;
GRANT EXECUTE ON FUNCTION mod_estructuracomp.f_adicinardom(bigint, bigint) TO reportesigis;
GRANT EXECUTE ON FUNCTION mod_estructuracomp.f_buscaridcampo(character varying, numeric) TO reportesigis;
GRANT EXECUTE ON FUNCTION mod_estructuracomp.f_cargosporestructura(character varying) TO reportesigis;
GRANT EXECUTE ON FUNCTION mod_estructuracomp.f_contarcargosporgupoescala(character varying, character varying, character varying, character varying) TO reportesigis;
GRANT EXECUTE ON FUNCTION mod_estructuracomp."f_existeEstructuraop"(numeric) TO reportesigis;
GRANT EXECUTE ON FUNCTION mod_estructuracomp."f_getEstructurasInternas"(numeric, boolean) TO reportesigis;
GRANT EXECUTE ON FUNCTION mod_estructuracomp."f_getHijosInterna"(numeric) TO reportesigis;
GRANT EXECUTE ON FUNCTION mod_estructuracomp.f_getdatosestructura(numeric) TO reportesigis;
GRANT EXECUTE ON FUNCTION mod_estructuracomp."f_listadoEstructuras"(numeric, numeric) TO reportesigis;
GRANT EXECUTE ON FUNCTION mod_estructuracomp."f_listadoEstructurasInternas"(numeric, numeric) TO reportesigis;
GRANT EXECUTE ON FUNCTION mod_estructuracomp.f_rep_areas(character varying, character varying) TO reportesigis;
GRANT EXECUTE ON FUNCTION mod_estructuracomp.f_rep_areascategorias(character varying, character varying) TO reportesigis;
GRANT EXECUTE ON FUNCTION mod_estructuracomp.f_rep_areascategorias1(numeric, character varying) TO reportesigis;
GRANT EXECUTE ON FUNCTION mod_estructuracomp.f_rep_buscaridcalaificador(character varying) TO reportesigis;
GRANT EXECUTE ON FUNCTION mod_estructuracomp.f_rep_buscaridcategocup(character varying) TO reportesigis;
GRANT EXECUTE ON FUNCTION mod_estructuracomp.f_rep_buscarideav(character varying) TO reportesigis;
GRANT EXECUTE ON FUNCTION mod_estructuracomp.f_rep_buscaridestructura(character varying) TO reportesigis;
GRANT EXECUTE ON FUNCTION mod_estructuracomp.f_rep_buscaridestructuraop(character varying) TO reportesigis;
GRANT EXECUTE ON FUNCTION mod_estructuracomp.f_rep_calificador_cargos(character varying) TO reportesigis;
GRANT EXECUTE ON FUNCTION mod_estructuracomp.f_rep_cargos_areas_categoria(character varying, character varying) TO reportesigis;
GRANT EXECUTE ON FUNCTION mod_estructuracomp.f_rep_componentesestructurainterna(character varying) TO reportesigis;
GRANT EXECUTE ON FUNCTION mod_estructuracomp.f_rep_contarcargosporcategoriaocupacional(character varying, character varying) TO reportesigis;
GRANT EXECUTE ON FUNCTION mod_estructuracomp.f_rep_contarhijospororgano(numeric, numeric) TO reportesigis;
GRANT EXECUTE ON FUNCTION mod_estructuracomp.f_rep_entidades(numeric, character varying, numeric) TO reportesigis;
GRANT EXECUTE ON FUNCTION mod_estructuracomp.f_rep_grupoescalacategocupacional(character varying, character varying) TO reportesigis;
GRANT EXECUTE ON FUNCTION mod_estructuracomp.f_rep_nemurycontarhijosxeav1(numeric, numeric) TO reportesigis;
GRANT EXECUTE ON FUNCTION mod_estructuracomp.f_rep_plantilla_cargos(character varying, character varying) TO reportesigis;
GRANT EXECUTE ON FUNCTION mod_estructuracomp.f_rep_relacion_localizacion_unidades_nivelest(character varying, character varying) TO reportesigis;
GRANT EXECUTE ON FUNCTION mod_estructuracomp.f_rep_relacion_localizunidadesporentidad(character varying, character varying) TO reportesigis;
GRANT EXECUTE ON FUNCTION mod_estructuracomp.f_rep_relacion_nivel1porclasif1() TO reportesigis;
GRANT EXECUTE ON FUNCTION mod_estructuracomp.f_rep_relacion_registro_entidades_agrupacion(character varying, character varying) TO reportesigis;
GRANT EXECUTE ON FUNCTION mod_estructuracomp.f_rep_relacionagruppornivel11(character varying) TO reportesigis;
GRANT EXECUTE ON FUNCTION mod_estructuracomp.f_rep_relacionentidadesporagrupaciones1(character varying, character varying) TO reportesigis;
GRANT EXECUTE ON FUNCTION mod_estructuracomp.f_rep_resumen_agrupaciones_nivel_segun_clasificacion(character varying) TO reportesigis;
GRANT EXECUTE ON FUNCTION mod_estructuracomp.f_rep_resumen_categoria_entidades_agrupaciones(character varying, character varying) TO reportesigis;
GRANT EXECUTE ON FUNCTION mod_estructuracomp.f_rep_resumentidporagrupaciporclasificac(character varying, character varying) TO reportesigis;
GRANT EXECUTE ON FUNCTION mod_estructuracomp.f_totalgrupoescala(character varying, character varying) TO reportesigis;
GRANT EXECUTE ON FUNCTION mod_estructuracomp.posicional(text) TO reportesigis;
GRANT EXECUTE ON FUNCTION mod_estructuracomp.posicionalmitad(numeric) TO reportesigis;
GRANT EXECUTE ON FUNCTION mod_estructuracomp.reordenar_dat_estructura(bigint, bigint) TO reportesigis;
GRANT EXECUTE ON FUNCTION mod_estructuracomp.reordenar_dat_estructuraop(bigint, bigint) TO reportesigis;
GRANT EXECUTE ON FUNCTION mod_estructuracomp.reordenar_nom_dpa(bigint, bigint) TO reportesigis;
GRANT EXECUTE ON FUNCTION mod_estructuracomp.rep_contarhijosconvalorencampo(numeric, numeric, numeric, character varying) TO reportesigis;
GRANT EXECUTE ON FUNCTION mod_estructuracomp.rep_contarhijosconvalorencampo(numeric, numeric, character varying) TO reportesigis;
GRANT EXECUTE ON FUNCTION mod_estructuracomp.rep_contarxclasf(numeric, numeric, numeric) TO reportesigis;

GRANT EXECUTE ON FUNCTION mod_estructuracomp.chequear() TO reportesigis;
GRANT EXECUTE ON FUNCTION mod_estructuracomp.ft_actualizacion_arbol() TO reportesigis;
GRANT EXECUTE ON FUNCTION mod_estructuracomp.ft_actualizacion_arbolop() TO reportesigis;
GRANT EXECUTE ON FUNCTION mod_estructuracomp.ft_chequear() TO reportesigis;
GRANT EXECUTE ON FUNCTION mod_estructuracomp.ft_eliminar_nodo() TO reportesigis;
GRANT EXECUTE ON FUNCTION mod_estructuracomp.ft_eliminar_nodo_dominio() TO reportesigis;
GRANT EXECUTE ON FUNCTION mod_estructuracomp.ft_eliminar_nodo_fila() TO reportesigis;
GRANT EXECUTE ON FUNCTION mod_estructuracomp.ft_eliminar_nodoop() TO reportesigis;
GRANT EXECUTE ON FUNCTION mod_estructuracomp.ft_insertar_fila() TO reportesigis;
GRANT EXECUTE ON FUNCTION mod_estructuracomp.ft_insertar_nodo() TO reportesigis;
GRANT EXECUTE ON FUNCTION mod_estructuracomp.ft_insertar_nodo_dominio() TO reportesigis;
GRANT EXECUTE ON FUNCTION mod_estructuracomp.ft_insertar_nodo_fila() TO reportesigis;
GRANT EXECUTE ON FUNCTION mod_estructuracomp.ft_insertar_nodoop() TO reportesigis;
GRANT EXECUTE ON FUNCTION mod_estructuracomp.ft_modificar_nodo() TO reportesigis;
GRANT EXECUTE ON FUNCTION mod_estructuracomp.ft_modificar_nodoop() TO reportesigis;

----------------------------------------
--fin de la transaccion
COMMIT;