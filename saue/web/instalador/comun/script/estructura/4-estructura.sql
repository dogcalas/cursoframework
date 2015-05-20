-------------------V6.0.0---------------------
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
VALUES ('V6.0.0', 'V6.0.0','mod_estructuracomp',null,'C','P','I');
----------------------------------------------------------------------------
--Permisos del rol estructrua sobre el esquema mod_estructuracomp
ALTER SCHEMA mod_estructuracomp OWNER TO sauxe;
----------------------------------------------------------------------------
----------------------------------------
--Permisos sobre el esquema
----------------------------------------
	SET search_path = mod_estructuracomp, pg_catalog;

GRANT USAGE ON SCHEMA mod_estructuracomp TO sauxe;

--- Funciones

GRANT EXECUTE ON FUNCTION mod_estructuracomp."Nemury_ContarNietosxEAVxidOrgPadre"(numeric, numeric, numeric) TO sauxe;
GRANT EXECUTE ON FUNCTION mod_estructuracomp.cant_entida_unidades_agrupacion(character varying, character varying) TO sauxe;
GRANT EXECUTE ON FUNCTION mod_estructuracomp."f_ getHijosEstructura"(numeric) TO sauxe;
GRANT EXECUTE ON FUNCTION mod_estructuracomp.f_adicinardom(bigint, bigint) TO sauxe;
GRANT EXECUTE ON FUNCTION mod_estructuracomp.f_buscaridcampo(character varying, numeric) TO sauxe;
GRANT EXECUTE ON FUNCTION mod_estructuracomp.f_cargosporestructura(character varying) TO sauxe;
GRANT EXECUTE ON FUNCTION mod_estructuracomp.f_contarcargosporgupoescala(character varying, character varying, character varying, character varying) TO sauxe;
GRANT EXECUTE ON FUNCTION mod_estructuracomp."f_existeEstructuraop"(numeric) TO sauxe;
GRANT EXECUTE ON FUNCTION mod_estructuracomp."f_getEstructurasInternas"(numeric, boolean) TO sauxe;
GRANT EXECUTE ON FUNCTION mod_estructuracomp."f_getHijosInterna"(numeric) TO sauxe;
GRANT EXECUTE ON FUNCTION mod_estructuracomp.f_getdatosestructura(numeric) TO sauxe;
GRANT EXECUTE ON FUNCTION mod_estructuracomp."f_listadoEstructuras"(numeric, numeric) TO sauxe;
GRANT EXECUTE ON FUNCTION mod_estructuracomp."f_listadoEstructurasInternas"(numeric, numeric) TO sauxe;
GRANT EXECUTE ON FUNCTION mod_estructuracomp.f_rep_areas(character varying, character varying) TO sauxe;
GRANT EXECUTE ON FUNCTION mod_estructuracomp.f_rep_areascategorias(character varying, character varying) TO sauxe;
GRANT EXECUTE ON FUNCTION mod_estructuracomp.f_rep_areascategorias1(numeric, character varying) TO sauxe;
GRANT EXECUTE ON FUNCTION mod_estructuracomp.f_rep_buscaridcalaificador(character varying) TO sauxe;
GRANT EXECUTE ON FUNCTION mod_estructuracomp.f_rep_buscaridcategocup(character varying) TO sauxe;
GRANT EXECUTE ON FUNCTION mod_estructuracomp.f_rep_buscarideav(character varying) TO sauxe;
GRANT EXECUTE ON FUNCTION mod_estructuracomp.f_rep_buscaridestructura(character varying) TO sauxe;
GRANT EXECUTE ON FUNCTION mod_estructuracomp.f_rep_buscaridestructuraop(character varying) TO sauxe;
GRANT EXECUTE ON FUNCTION mod_estructuracomp.f_rep_calificador_cargos(character varying) TO sauxe;
GRANT EXECUTE ON FUNCTION mod_estructuracomp.f_rep_cargos_areas_categoria(character varying, character varying) TO sauxe;
GRANT EXECUTE ON FUNCTION mod_estructuracomp.f_rep_componentesestructurainterna(character varying) TO sauxe;
GRANT EXECUTE ON FUNCTION mod_estructuracomp.f_rep_contarcargosporcategoriaocupacional(character varying, character varying) TO sauxe;
GRANT EXECUTE ON FUNCTION mod_estructuracomp.f_rep_contarhijospororgano(numeric, numeric) TO sauxe;
GRANT EXECUTE ON FUNCTION mod_estructuracomp.f_rep_entidades(numeric, character varying, numeric) TO sauxe;
GRANT EXECUTE ON FUNCTION mod_estructuracomp.f_rep_grupoescalacategocupacional(character varying, character varying) TO sauxe;
GRANT EXECUTE ON FUNCTION mod_estructuracomp.f_rep_nemurycontarhijosxeav1(numeric, numeric) TO sauxe;
GRANT EXECUTE ON FUNCTION mod_estructuracomp.f_rep_plantilla_cargos(character varying, character varying) TO sauxe;
GRANT EXECUTE ON FUNCTION mod_estructuracomp.f_rep_plantilla_cargos(numeric) TO sauxe;
GRANT EXECUTE ON FUNCTION mod_estructuracomp.f_rep_relacion_localizacion_unidades_nivelest(character varying, character varying) TO sauxe;
GRANT EXECUTE ON FUNCTION mod_estructuracomp.f_rep_relacion_localizunidadesporentidad(character varying, character varying) TO sauxe;
GRANT EXECUTE ON FUNCTION mod_estructuracomp.f_rep_relacion_nivel1porclasif1() TO sauxe;
GRANT EXECUTE ON FUNCTION mod_estructuracomp.f_rep_relacion_registro_entidades_agrupacion(character varying, character varying) TO sauxe;
GRANT EXECUTE ON FUNCTION mod_estructuracomp.f_rep_relacionagruppornivel11(character varying) TO sauxe;
GRANT EXECUTE ON FUNCTION mod_estructuracomp.f_rep_relacionentidadesporagrupaciones1(character varying, character varying) TO sauxe;
GRANT EXECUTE ON FUNCTION mod_estructuracomp.f_rep_resumen_agrupaciones_nivel_segun_clasificacion(character varying) TO sauxe;
GRANT EXECUTE ON FUNCTION mod_estructuracomp.f_rep_resumen_categoria_entidades_agrupaciones(character varying, character varying) TO sauxe;
GRANT EXECUTE ON FUNCTION mod_estructuracomp.f_rep_resumentidporagrupaciporclasificac(character varying, character varying) TO sauxe;
GRANT EXECUTE ON FUNCTION mod_estructuracomp.f_totalgrupoescala(character varying, character varying) TO sauxe;
GRANT EXECUTE ON FUNCTION mod_estructuracomp.posicional(text) TO sauxe;
GRANT EXECUTE ON FUNCTION mod_estructuracomp.posicionalmitad(numeric) TO sauxe;
GRANT EXECUTE ON FUNCTION mod_estructuracomp.reordenar_dat_estructura(bigint, bigint) TO sauxe;
GRANT EXECUTE ON FUNCTION mod_estructuracomp.reordenar_dat_estructuraop(bigint, bigint) TO sauxe;
GRANT EXECUTE ON FUNCTION mod_estructuracomp.reordenar_nom_dpa(bigint, bigint) TO sauxe;
GRANT EXECUTE ON FUNCTION mod_estructuracomp.rep_contarhijosconvalorencampo(numeric, numeric, numeric, character varying) TO sauxe;
GRANT EXECUTE ON FUNCTION mod_estructuracomp.rep_contarhijosconvalorencampo(numeric, numeric, character varying) TO sauxe;
GRANT EXECUTE ON FUNCTION mod_estructuracomp.rep_contarxclasf(numeric, numeric, numeric) TO sauxe;

--- Secuencias

GRANT ALL ON TABLE mod_estructuracomp.sec_cargomiliat_seq TO sauxe;
GRANT ALL ON TABLE mod_estructuracomp.sec_datcargo_seq TO sauxe;
GRANT ALL ON TABLE mod_estructuracomp.sec_datcargocivil_seq TO sauxe;
GRANT ALL ON TABLE mod_estructuracomp.sec_datdocoficial_seq TO sauxe;
GRANT ALL ON TABLE mod_estructuracomp.sec_datespecifcargo_seq TO sauxe;
GRANT ALL ON TABLE mod_estructuracomp.sec_datmodcargo_seq TO sauxe;
GRANT ALL ON TABLE mod_estructuracomp.sec_datmodulos_seq TO sauxe;
GRANT ALL ON TABLE mod_estructuracomp.sec_datpuesto_seq TO sauxe;
GRANT ALL ON TABLE mod_estructuracomp.sec_dattecnica_seq TO sauxe;
GRANT ALL ON TABLE mod_estructuracomp.sec_nomagrupacion_seq TO sauxe;
GRANT ALL ON TABLE mod_estructuracomp.sec_nomcalificadorcargo_seq TO sauxe;
GRANT ALL ON TABLE mod_estructuracomp.sec_nomcampoestruc_seq TO sauxe;
GRANT ALL ON TABLE mod_estructuracomp.sec_nomcargocivil_seq TO sauxe;
GRANT ALL ON TABLE mod_estructuracomp.sec_nomcargomilitar_seq TO sauxe;
GRANT ALL ON TABLE mod_estructuracomp.sec_nomcategcivil_seq TO sauxe;
GRANT ALL ON TABLE mod_estructuracomp.sec_nomcategocup_seq TO sauxe;
GRANT ALL ON TABLE mod_estructuracomp.sec_nomclasificacioncargo_seq TO sauxe;
GRANT ALL ON TABLE mod_estructuracomp.sec_nomdominio_seq TO sauxe;
GRANT ALL ON TABLE mod_estructuracomp.sec_nomescalasalarial_seq TO sauxe;
GRANT ALL ON TABLE mod_estructuracomp.sec_nomfilaestruc_seq TO sauxe;
GRANT ALL ON TABLE mod_estructuracomp.sec_nomgradomilit_seq TO sauxe;
GRANT ALL ON TABLE mod_estructuracomp.sec_nomgrupocomple_seq TO sauxe;
GRANT ALL ON TABLE mod_estructuracomp.sec_nomgsubcateg_seq TO sauxe;
GRANT ALL ON TABLE mod_estructuracomp.sec_nommodulo_seq TO sauxe;
GRANT ALL ON TABLE mod_estructuracomp.sec_nomnivelestr_seq TO sauxe;
GRANT ALL ON TABLE mod_estructuracomp.sec_nomnivelutilizacion_seq TO sauxe;
GRANT ALL ON TABLE mod_estructuracomp.sec_nomnomencladoreavestruc_seq TO sauxe;
GRANT ALL ON TABLE mod_estructuracomp.sec_nomorgano_seq TO sauxe;
GRANT ALL ON TABLE mod_estructuracomp.sec_nomprefijo_seq TO sauxe;
GRANT ALL ON TABLE mod_estructuracomp.sec_nomprepmilitar_seq TO sauxe;
GRANT ALL ON TABLE mod_estructuracomp.sec_nomsalario_seq TO sauxe;
GRANT ALL ON TABLE mod_estructuracomp.sec_nomtecnica_seq TO sauxe;
GRANT ALL ON TABLE mod_estructuracomp.sec_nomtipocalificador_seq TO sauxe;
GRANT ALL ON TABLE mod_estructuracomp.sec_nomtipocifra_seq TO sauxe;
GRANT ALL ON TABLE mod_estructuracomp.sec_nomtipodoc_seq TO sauxe;
GRANT ALL ON TABLE mod_estructuracomp.sec_nomvalor_defecto_seq TO sauxe;
GRANT ALL ON TABLE mod_estructuracomp.sec_nomvalorestruc_seq TO sauxe;

--- Tablas

GRANT ALL ON TABLE mod_estructuracomp.dat_agrupacionest TO sauxe;
GRANT ALL ON TABLE mod_estructuracomp.dat_agrupacionestop TO sauxe;
GRANT ALL ON TABLE mod_estructuracomp.dat_cargo TO sauxe;
GRANT ALL ON TABLE mod_estructuracomp.dat_cargocivil TO sauxe;
GRANT ALL ON TABLE mod_estructuracomp.dat_cargomtar TO sauxe;
GRANT ALL ON TABLE mod_estructuracomp.dat_docoficial TO sauxe;
GRANT ALL ON TABLE mod_estructuracomp.dat_estructura TO sauxe;
GRANT ALL ON TABLE mod_estructuracomp.dat_estructuraop TO sauxe;
GRANT ALL ON TABLE mod_estructuracomp.dat_modcargo TO sauxe;
GRANT ALL ON TABLE mod_estructuracomp.dat_modulos TO sauxe;
GRANT ALL ON TABLE mod_estructuracomp.dat_puesto TO sauxe;
GRANT ALL ON TABLE mod_estructuracomp.dat_subordinacion TO sauxe;
GRANT ALL ON TABLE mod_estructuracomp.dat_tecnica TO sauxe;
GRANT ALL ON TABLE mod_estructuracomp.nom_agrupacion TO sauxe;
GRANT ALL ON TABLE mod_estructuracomp.nom_aristaeav TO sauxe;
GRANT ALL ON TABLE mod_estructuracomp.nom_calificador_cargo TO sauxe;
GRANT ALL ON TABLE mod_estructuracomp.nom_campoestruc TO sauxe;
GRANT ALL ON TABLE mod_estructuracomp.nom_cargocivil TO sauxe;
GRANT ALL ON TABLE mod_estructuracomp.nom_cargomilitar TO sauxe;
GRANT ALL ON TABLE mod_estructuracomp.nom_categcivil TO sauxe;
GRANT ALL ON TABLE mod_estructuracomp.nom_categocup TO sauxe;
GRANT ALL ON TABLE mod_estructuracomp.nom_clasificacion_cargo TO sauxe;
GRANT ALL ON TABLE mod_estructuracomp.nom_dominio TO sauxe;
GRANT ALL ON TABLE mod_estructuracomp.nom_escalasalarial TO sauxe;
GRANT ALL ON TABLE mod_estructuracomp.nom_filaestruc TO sauxe;
GRANT ALL ON TABLE mod_estructuracomp.nom_gradomilit TO sauxe;
GRANT ALL ON TABLE mod_estructuracomp.nom_grupocomple TO sauxe;
GRANT ALL ON TABLE mod_estructuracomp.nom_gsubcateg TO sauxe;
GRANT ALL ON TABLE mod_estructuracomp.nom_modulo TO sauxe;
GRANT ALL ON TABLE mod_estructuracomp.nom_nivel_utilizacion TO sauxe;
GRANT ALL ON TABLE mod_estructuracomp.nom_nivelestr TO sauxe;
GRANT ALL ON TABLE mod_estructuracomp.nom_nomencladoreavestruc TO sauxe;
GRANT ALL ON TABLE mod_estructuracomp.nom_organo TO sauxe;
GRANT ALL ON TABLE mod_estructuracomp.nom_prefijo TO sauxe;
GRANT ALL ON TABLE mod_estructuracomp.nom_prepmilitar TO sauxe;
GRANT ALL ON TABLE mod_estructuracomp.nom_salario TO sauxe;
GRANT ALL ON TABLE mod_estructuracomp.nom_subordinacion TO sauxe;
GRANT ALL ON TABLE mod_estructuracomp.nom_tecnica TO sauxe;
GRANT ALL ON TABLE mod_estructuracomp.nom_tipo_calificador TO sauxe;
GRANT ALL ON TABLE mod_estructuracomp.nom_tipocifra TO sauxe;
GRANT ALL ON TABLE mod_estructuracomp.nom_tipodoc TO sauxe;
GRANT ALL ON TABLE mod_estructuracomp.nom_valor_defecto TO sauxe;
GRANT ALL ON TABLE mod_estructuracomp.nom_valorestruc TO sauxe;

----Permisos Agregados por Jose
GRANT SELECT ON TABLE mod_estructuracomp.dat_cargo TO loginuser;
GRANT SELECT ON TABLE mod_estructuracomp.dat_cargomtar TO loginuser;
GRANT SELECT ON TABLE mod_estructuracomp.dat_estructura TO loginuser;
GRANT SELECT ON TABLE mod_estructuracomp.dat_estructuraop TO loginuser;
GRANT SELECT ON TABLE mod_estructuracomp.nom_cargomilitar TO loginuser;
GRANT SELECT ON TABLE mod_estructuracomp.nom_dominio TO loginuser;
GRANT SELECT ON TABLE mod_estructuracomp.nom_filaestruc TO loginuser;
GRANT SELECT ON TABLE mod_estructuracomp.nom_nivelestr TO loginuser;
GRANT SELECT ON TABLE mod_estructuracomp.nom_nomencladoreavestruc TO loginuser;
GRANT SELECT ON TABLE mod_estructuracomp.nom_organo TO loginuser;

--- Funciones disparadoras

GRANT EXECUTE ON FUNCTION mod_estructuracomp.chequear() TO sauxe;
GRANT EXECUTE ON FUNCTION mod_estructuracomp.ft_actualizacion_arbol() TO sauxe;
GRANT EXECUTE ON FUNCTION mod_estructuracomp.ft_actualizacion_arbolop() TO sauxe;
GRANT EXECUTE ON FUNCTION mod_estructuracomp.ft_actualizarsubordinacion() TO sauxe;
GRANT EXECUTE ON FUNCTION mod_estructuracomp.ft_chequear() TO sauxe;
GRANT EXECUTE ON FUNCTION mod_estructuracomp.ft_eliminar_nodo() TO sauxe;
GRANT EXECUTE ON FUNCTION mod_estructuracomp.ft_eliminar_nodo_dominio() TO sauxe;
GRANT EXECUTE ON FUNCTION mod_estructuracomp.ft_eliminar_nodo_fila() TO sauxe;
GRANT EXECUTE ON FUNCTION mod_estructuracomp.ft_eliminar_nodoop() TO sauxe;
GRANT EXECUTE ON FUNCTION mod_estructuracomp.ft_insertar_fila() TO sauxe;
GRANT EXECUTE ON FUNCTION mod_estructuracomp.ft_insertar_nodo() TO sauxe;
GRANT EXECUTE ON FUNCTION mod_estructuracomp.ft_insertar_nodo_dominio() TO sauxe;
GRANT EXECUTE ON FUNCTION mod_estructuracomp.ft_insertar_nodo_fila() TO sauxe;
GRANT EXECUTE ON FUNCTION mod_estructuracomp.ft_insertar_nodoop() TO sauxe;
GRANT EXECUTE ON FUNCTION mod_estructuracomp.ft_modificar_nodo() TO sauxe;
GRANT EXECUTE ON FUNCTION mod_estructuracomp.ft_modificar_nodoop() TO sauxe;

--- Dueño tipos de datos

ALTER TYPE mod_estructuracomp.cant_entida_unidades_agrupacion OWNER TO sauxe;
ALTER TYPE mod_estructuracomp.td_agruppornivel1 OWNER TO sauxe;
ALTER TYPE mod_estructuracomp.td_areas OWNER TO sauxe;
ALTER TYPE mod_estructuracomp.td_calificador_cargo OWNER TO sauxe;
ALTER TYPE mod_estructuracomp.td_cargos_areas_categoria OWNER TO sauxe;
ALTER TYPE mod_estructuracomp.td_entidades OWNER TO sauxe;
ALTER TYPE mod_estructuracomp.td_entidadesporagrupaciones OWNER TO sauxe;
ALTER TYPE mod_estructuracomp.td_grupoescalacategocupacional1 OWNER TO sauxe;
ALTER TYPE mod_estructuracomp.td_nivel1porclasif OWNER TO sauxe;
ALTER TYPE mod_estructuracomp.td_plantilla_cargos OWNER TO sauxe;
ALTER TYPE mod_estructuracomp.td_rep_componentesestructurainterna OWNER TO sauxe;
ALTER TYPE mod_estructuracomp.td_rep_nivel1porclasif OWNER TO sauxe;
ALTER TYPE mod_estructuracomp.td_rep_relacion_localizacion_unidades_nivelest OWNER TO sauxe;
ALTER TYPE mod_estructuracomp.td_rep_relacion_localizunidadesporentidad OWNER TO sauxe;
ALTER TYPE mod_estructuracomp.td_rep_relacion_registro_entidad_agrupacion1 OWNER TO sauxe;
ALTER TYPE mod_estructuracomp.td_rep_relacionagruppornivel1 OWNER TO sauxe;
ALTER TYPE mod_estructuracomp.td_rep_relacionentidadesporagrupaciones1 OWNER TO sauxe;
ALTER TYPE mod_estructuracomp.td_rep_resumen_agrupaciones_nivel OWNER TO sauxe;
ALTER TYPE mod_estructuracomp.td_rep_resumen_categoria_entidades_agrupaciones OWNER TO sauxe;
ALTER TYPE mod_estructuracomp.td_rep_resumentidporagrupaciporclasificac OWNER TO sauxe;
ALTER TYPE mod_estructuracomp.td_unidades OWNER TO sauxe;
ALTER TYPE mod_estructuracomp.td_unidadesporentidad OWNER TO sauxe;

--- Dueño funciones

ALTER FUNCTION mod_estructuracomp."Nemury_ContarNietosxEAVxidOrgPadre"("IdPadre" numeric, "IdOrgano" numeric, "Eav" numeric) OWNER TO sauxe;
ALTER FUNCTION mod_estructuracomp.cant_entida_unidades_agrupacion(nombre_nivel character varying, codigo_nivel character varying) OWNER TO sauxe;
ALTER FUNCTION mod_estructuracomp.chequear() OWNER TO sauxe;
ALTER FUNCTION mod_estructuracomp."f_ getHijosEstructura"(idpadre numeric) OWNER TO sauxe;
ALTER FUNCTION mod_estructuracomp.f_adicinardom(pos bigint, bin bigint) OWNER TO sauxe;
ALTER FUNCTION mod_estructuracomp.f_buscaridcampo(nombre character varying, "idEAV" numeric) OWNER TO sauxe;
ALTER FUNCTION mod_estructuracomp.f_cargosporestructura(denominacion_estructura character varying) OWNER TO sauxe;
ALTER FUNCTION mod_estructuracomp.f_contarcargosporgupoescala(grupoescala character varying, categocupacional character varying, denominacion_estructura character varying, codigo character varying) OWNER TO sauxe;
ALTER FUNCTION mod_estructuracomp."f_existeEstructuraop"(id_estructuraop numeric) OWNER TO sauxe;
ALTER FUNCTION mod_estructuracomp."f_getEstructurasInternas"(idestructura numeric, raiz boolean) OWNER TO sauxe;
ALTER FUNCTION mod_estructuracomp."f_getHijosInterna"(idpadre numeric) OWNER TO sauxe;
ALTER FUNCTION mod_estructuracomp.f_getdatosestructura(estructura numeric) OWNER TO sauxe;
ALTER FUNCTION mod_estructuracomp."f_listadoEstructuras"(comienzo numeric, fin numeric) OWNER TO sauxe;
ALTER FUNCTION mod_estructuracomp."f_listadoEstructurasInternas"(comienzo numeric, fin numeric) OWNER TO sauxe;
ALTER FUNCTION mod_estructuracomp.f_rep_areas(denominacion_estructura character varying, denominacion_estructuraop character varying) OWNER TO sauxe;
ALTER FUNCTION mod_estructuracomp.f_rep_areascategorias(denominacion_area character varying, denominacion_categoria character varying) OWNER TO sauxe;
ALTER FUNCTION mod_estructuracomp.f_rep_areascategorias1(id_area numeric, denominacion_categoria character varying) OWNER TO sauxe;
ALTER FUNCTION mod_estructuracomp.f_rep_buscaridcalaificador(denominacion_calificador character varying) OWNER TO sauxe;
ALTER FUNCTION mod_estructuracomp.f_rep_buscaridcategocup(denominacion_categocup character varying) OWNER TO sauxe;
ALTER FUNCTION mod_estructuracomp.f_rep_buscarideav(nombre character varying) OWNER TO sauxe;
ALTER FUNCTION mod_estructuracomp.f_rep_buscaridestructura(denominacion character varying) OWNER TO sauxe;
ALTER FUNCTION mod_estructuracomp.f_rep_buscaridestructuraop(denominacion character varying) OWNER TO sauxe;
ALTER FUNCTION mod_estructuracomp.f_rep_calificador_cargos(denominacion_calificador character varying) OWNER TO sauxe;
ALTER FUNCTION mod_estructuracomp.f_rep_cargos_areas_categoria(denominacion_estructura character varying, codigo_estructura character varying) OWNER TO sauxe;
ALTER FUNCTION mod_estructuracomp.f_rep_componentesestructurainterna(denominacion_estructura character varying) OWNER TO sauxe;
ALTER FUNCTION mod_estructuracomp.f_rep_contarcargosporcategoriaocupacional(denominacion_categoriaocupacional character varying, denominacion_estructuraop character varying) OWNER TO sauxe;
ALTER FUNCTION mod_estructuracomp.f_rep_contarhijospororgano("IdPadre" numeric, "idOrgano" numeric) OWNER TO sauxe;
ALTER FUNCTION mod_estructuracomp.f_rep_entidades(idestructura numeric, nombre character varying, "idEAV" numeric) OWNER TO sauxe;
ALTER FUNCTION mod_estructuracomp.f_rep_grupoescalacategocupacional(denominacion_estructura character varying, codigo_estructura character varying) OWNER TO sauxe;
ALTER FUNCTION mod_estructuracomp.f_rep_nemurycontarhijosxeav1("IdPadre" numeric, "Eav" numeric) OWNER TO sauxe;
ALTER FUNCTION mod_estructuracomp.f_rep_plantilla_cargos(idestructura numeric) OWNER TO sauxe;
ALTER FUNCTION mod_estructuracomp.f_rep_plantilla_cargos(denominacion_estructura character varying, codigo_estructura character varying) OWNER TO sauxe;
ALTER FUNCTION mod_estructuracomp.f_rep_relacion_localizacion_unidades_nivelest("NombrePadre" character varying, "Codigo" character varying) OWNER TO sauxe;
ALTER FUNCTION mod_estructuracomp.f_rep_relacion_localizunidadesporentidad("NombreNivel" character varying, "Codigo" character varying) OWNER TO sauxe;
ALTER FUNCTION mod_estructuracomp.f_rep_relacion_nivel1porclasif1() OWNER TO sauxe;
ALTER FUNCTION mod_estructuracomp.f_rep_relacion_registro_entidades_agrupacion(nombrenivel character varying, codigonivel character varying) OWNER TO sauxe;
ALTER FUNCTION mod_estructuracomp.f_rep_relacionagruppornivel11("NombreNivel" character varying) OWNER TO sauxe;
ALTER FUNCTION mod_estructuracomp.f_rep_relacionentidadesporagrupaciones1("NombreNivel" character varying, "Codigo" character varying) OWNER TO sauxe;
ALTER FUNCTION mod_estructuracomp.f_rep_resumen_agrupaciones_nivel_segun_clasificacion(nombrenivel1 character varying) OWNER TO sauxe;
ALTER FUNCTION mod_estructuracomp.f_rep_resumen_categoria_entidades_agrupaciones(nombrenivel character varying, codigo character varying) OWNER TO sauxe;
ALTER FUNCTION mod_estructuracomp.f_rep_resumentidporagrupaciporclasificac(nombrenivel1 character varying, codigo character varying) OWNER TO sauxe;
ALTER FUNCTION mod_estructuracomp.f_totalgrupoescala(denominacion_estructura character varying, grupocomple character varying) OWNER TO sauxe;
ALTER FUNCTION mod_estructuracomp.ft_actualizacion_arbol() OWNER TO sauxe;
ALTER FUNCTION mod_estructuracomp.ft_actualizacion_arbolop() OWNER TO sauxe;
ALTER FUNCTION mod_estructuracomp.ft_actualizarsubordinacion() OWNER TO sauxe;
ALTER FUNCTION mod_estructuracomp.ft_chequear() OWNER TO sauxe;
ALTER FUNCTION mod_estructuracomp.ft_eliminar_nodo() OWNER TO sauxe;
ALTER FUNCTION mod_estructuracomp.ft_eliminar_nodo_dominio() OWNER TO sauxe;
ALTER FUNCTION mod_estructuracomp.ft_eliminar_nodo_fila() OWNER TO sauxe;
ALTER FUNCTION mod_estructuracomp.ft_eliminar_nodoop() OWNER TO sauxe;
ALTER FUNCTION mod_estructuracomp.ft_insertar_fila() OWNER TO sauxe;
ALTER FUNCTION mod_estructuracomp.ft_insertar_nodo() OWNER TO sauxe;
ALTER FUNCTION mod_estructuracomp.ft_insertar_nodo_dominio() OWNER TO sauxe;
ALTER FUNCTION mod_estructuracomp.ft_insertar_nodo_fila() OWNER TO sauxe;
ALTER FUNCTION mod_estructuracomp.ft_insertar_nodoop() OWNER TO sauxe;
ALTER FUNCTION mod_estructuracomp.ft_modificar_nodo() OWNER TO sauxe;
ALTER FUNCTION mod_estructuracomp.ft_modificar_nodoop() OWNER TO sauxe;
ALTER FUNCTION mod_estructuracomp.posicional(num text) OWNER TO sauxe;
ALTER FUNCTION mod_estructuracomp.posicionalmitad(num numeric) OWNER TO sauxe;
ALTER FUNCTION mod_estructuracomp.reordenar_dat_estructura(iddat_estructuranodo bigint, lftnodo bigint) OWNER TO sauxe;
ALTER FUNCTION mod_estructuracomp.reordenar_dat_estructuraop(iddat_estructuranodo bigint, lftnodo bigint) OWNER TO sauxe;
ALTER FUNCTION mod_estructuracomp.reordenar_nom_dpa(iddat_estructuranodo bigint, lftnodo bigint) OWNER TO sauxe;
ALTER FUNCTION mod_estructuracomp.rep_contarhijosconvalorencampo("Idpadre" numeric, idcampo numeric, eav_hijo numeric, valor character varying) OWNER TO sauxe;
ALTER FUNCTION mod_estructuracomp.rep_contarhijosconvalorencampo("IdPadre" numeric, "idCampo" numeric, "Valor" character varying) OWNER TO sauxe;
ALTER FUNCTION mod_estructuracomp.rep_contarxclasf(id numeric, idnomeav numeric, idor numeric) OWNER TO sauxe;

--- Dueño tablas y secuencias

ALTER TABLE mod_estructuracomp.dat_agrupacionest OWNER TO sauxe;
ALTER TABLE mod_estructuracomp.dat_agrupacionestop OWNER TO sauxe;
ALTER TABLE mod_estructuracomp.sec_datcargo_seq OWNER TO sauxe;
ALTER TABLE mod_estructuracomp.dat_cargo OWNER TO sauxe;
ALTER TABLE mod_estructuracomp.sec_datcargocivil_seq OWNER TO sauxe;
ALTER TABLE mod_estructuracomp.dat_cargocivil OWNER TO sauxe;
ALTER TABLE mod_estructuracomp.sec_cargomiliat_seq OWNER TO sauxe;
ALTER TABLE mod_estructuracomp.dat_cargomtar OWNER TO sauxe;
ALTER TABLE mod_estructuracomp.sec_datdocoficial_seq OWNER TO sauxe;
ALTER TABLE mod_estructuracomp.dat_docoficial OWNER TO sauxe;
ALTER TABLE mod_estructuracomp.dat_estructura OWNER TO sauxe;
ALTER TABLE mod_estructuracomp.dat_estructuraop OWNER TO sauxe;
ALTER TABLE mod_estructuracomp.sec_datmodcargo_seq OWNER TO sauxe;
ALTER TABLE mod_estructuracomp.dat_modcargo OWNER TO sauxe;
ALTER TABLE mod_estructuracomp.sec_datmodulos_seq OWNER TO sauxe;
ALTER TABLE mod_estructuracomp.dat_modulos OWNER TO sauxe;
ALTER TABLE mod_estructuracomp.sec_datpuesto_seq OWNER TO sauxe;
ALTER TABLE mod_estructuracomp.dat_puesto OWNER TO sauxe;
ALTER TABLE mod_estructuracomp.dat_subordinacion OWNER TO sauxe;
ALTER TABLE mod_estructuracomp.sec_dattecnica_seq OWNER TO sauxe;
ALTER TABLE mod_estructuracomp.dat_tecnica OWNER TO sauxe;
ALTER TABLE mod_estructuracomp.sec_nomagrupacion_seq OWNER TO sauxe;
ALTER TABLE mod_estructuracomp.nom_agrupacion OWNER TO sauxe;
ALTER TABLE mod_estructuracomp.nom_aristaeav OWNER TO sauxe;
ALTER TABLE mod_estructuracomp.sec_nomcalificadorcargo_seq OWNER TO sauxe;
ALTER TABLE mod_estructuracomp.nom_calificador_cargo OWNER TO sauxe;
ALTER TABLE mod_estructuracomp.sec_nomcampoestruc_seq OWNER TO sauxe;
ALTER TABLE mod_estructuracomp.nom_campoestruc OWNER TO sauxe;
ALTER TABLE mod_estructuracomp.sec_nomcargocivil_seq OWNER TO sauxe;
ALTER TABLE mod_estructuracomp.nom_cargocivil OWNER TO sauxe;
ALTER TABLE mod_estructuracomp.sec_nomcargomilitar_seq OWNER TO sauxe;
ALTER TABLE mod_estructuracomp.nom_cargomilitar OWNER TO sauxe;
ALTER TABLE mod_estructuracomp.sec_nomcategcivil_seq OWNER TO sauxe;
ALTER TABLE mod_estructuracomp.nom_categcivil OWNER TO sauxe;
ALTER TABLE mod_estructuracomp.sec_nomcategocup_seq OWNER TO sauxe;
ALTER TABLE mod_estructuracomp.nom_categocup OWNER TO sauxe;
ALTER TABLE mod_estructuracomp.sec_nomclasificacioncargo_seq OWNER TO sauxe;
ALTER TABLE mod_estructuracomp.nom_clasificacion_cargo OWNER TO sauxe;
ALTER TABLE mod_estructuracomp.sec_nomdominio_seq OWNER TO sauxe;
ALTER TABLE mod_estructuracomp.nom_dominio OWNER TO sauxe;
ALTER TABLE mod_estructuracomp.sec_nomescalasalarial_seq OWNER TO sauxe;
ALTER TABLE mod_estructuracomp.nom_escalasalarial OWNER TO sauxe;
ALTER TABLE mod_estructuracomp.sec_nomfilaestruc_seq OWNER TO sauxe;
ALTER TABLE mod_estructuracomp.nom_filaestruc OWNER TO sauxe;
ALTER TABLE mod_estructuracomp.sec_nomgradomilit_seq OWNER TO sauxe;
ALTER TABLE mod_estructuracomp.nom_gradomilit OWNER TO sauxe;
ALTER TABLE mod_estructuracomp.sec_nomgrupocomple_seq OWNER TO sauxe;
ALTER TABLE mod_estructuracomp.nom_grupocomple OWNER TO sauxe;
ALTER TABLE mod_estructuracomp.sec_nomgsubcateg_seq OWNER TO sauxe;
ALTER TABLE mod_estructuracomp.nom_gsubcateg OWNER TO sauxe;
ALTER TABLE mod_estructuracomp.sec_nommodulo_seq OWNER TO sauxe;
ALTER TABLE mod_estructuracomp.nom_modulo OWNER TO sauxe;
ALTER TABLE mod_estructuracomp.sec_nomnivelutilizacion_seq OWNER TO sauxe;
ALTER TABLE mod_estructuracomp.nom_nivel_utilizacion OWNER TO sauxe;
ALTER TABLE mod_estructuracomp.sec_nomnivelestr_seq OWNER TO sauxe;
ALTER TABLE mod_estructuracomp.nom_nivelestr OWNER TO sauxe;
ALTER TABLE mod_estructuracomp.sec_nomnomencladoreavestruc_seq OWNER TO sauxe;
ALTER TABLE mod_estructuracomp.nom_nomencladoreavestruc OWNER TO sauxe;
ALTER TABLE mod_estructuracomp.sec_nomorgano_seq OWNER TO sauxe;
ALTER TABLE mod_estructuracomp.nom_organo OWNER TO sauxe;
ALTER TABLE mod_estructuracomp.sec_nomprefijo_seq OWNER TO sauxe;
ALTER TABLE mod_estructuracomp.nom_prefijo OWNER TO sauxe;
ALTER TABLE mod_estructuracomp.sec_nomprepmilitar_seq OWNER TO sauxe;
ALTER TABLE mod_estructuracomp.nom_prepmilitar OWNER TO sauxe;
ALTER TABLE mod_estructuracomp.sec_nomsalario_seq OWNER TO sauxe;
ALTER TABLE mod_estructuracomp.nom_salario OWNER TO sauxe;
ALTER TABLE mod_estructuracomp.nom_subordinacion OWNER TO sauxe;
ALTER TABLE mod_estructuracomp.sec_nomtecnica_seq OWNER TO sauxe;
ALTER TABLE mod_estructuracomp.nom_tecnica OWNER TO sauxe;
ALTER TABLE mod_estructuracomp.sec_nomtipocalificador_seq OWNER TO sauxe;
ALTER TABLE mod_estructuracomp.nom_tipo_calificador OWNER TO sauxe;
ALTER TABLE mod_estructuracomp.sec_nomtipocifra_seq OWNER TO sauxe;
ALTER TABLE mod_estructuracomp.nom_tipocifra OWNER TO sauxe;
ALTER TABLE mod_estructuracomp.sec_nomtipodoc_seq OWNER TO sauxe;
ALTER TABLE mod_estructuracomp.nom_tipodoc OWNER TO sauxe;
ALTER TABLE mod_estructuracomp.sec_nomvalor_defecto_seq OWNER TO sauxe;
ALTER TABLE mod_estructuracomp.nom_valor_defecto OWNER TO sauxe;
ALTER TABLE mod_estructuracomp.nom_valorestruc OWNER TO sauxe;
ALTER TABLE mod_estructuracomp.sec_datespecifcargo_seq OWNER TO sauxe;
ALTER TABLE mod_estructuracomp.sec_nomvalorestruc_seq OWNER TO sauxe;

COMMIT;
