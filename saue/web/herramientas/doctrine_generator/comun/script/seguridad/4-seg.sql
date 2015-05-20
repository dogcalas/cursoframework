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
VALUES ('V5.0.0', 'V5.0.0','mod_seguridad',null,'C','P','I');
----------------------------------------------------------------------------
--Permisos del rol seguridad sobre el esquema mod_seguridad
----------------------------------------------------------------------------
----------------------------------------
--Permisos sobre el esquema
----------------------------------------
	SET search_path = mod_seguridad, pg_catalog;

GRANT USAGE ON SCHEMA mod_seguridad TO seguridadsigis;
----------------------------------------
-- Permisos sobre secuencias
----------------------------------------
	SET search_path = mod_seguridad, pg_catalog;

GRANT SELECT, USAGE ON TABLE mod_seguridad.sec_dataccion_seq TO seguridadsigis;
GRANT SELECT, USAGE ON TABLE mod_seguridad.sec_datbd_seq TO seguridadsigis;
GRANT SELECT, USAGE ON TABLE mod_seguridad.sec_datesquema_seq TO seguridadsigis;
GRANT SELECT, USAGE ON TABLE mod_seguridad.sec_datfuncionalidad_seq TO seguridadsigis;
GRANT SELECT, USAGE ON TABLE mod_seguridad.sec_datfunciones_seq TO seguridadsigis;
GRANT SELECT, USAGE ON TABLE mod_seguridad.sec_datgestor_seq TO seguridadsigis;
GRANT SELECT, USAGE ON TABLE mod_seguridad.sec_datparametros_seq TO seguridadsigis;
GRANT SELECT, USAGE ON TABLE mod_seguridad.sec_datservicio_seq TO seguridadsigis;
GRANT SELECT, USAGE ON TABLE mod_seguridad.sec_datservidor_seq TO seguridadsigis;
GRANT SELECT, USAGE ON TABLE mod_seguridad.sec_datsistema_seq TO seguridadsigis;
GRANT SELECT, USAGE ON TABLE mod_seguridad.sec_nomcampo_seq TO seguridadsigis;
GRANT SELECT, USAGE ON TABLE mod_seguridad.sec_nomdesktop_seq TO seguridadsigis;
GRANT SELECT, USAGE ON TABLE mod_seguridad.sec_nomexpresiones_seq TO seguridadsigis;
GRANT SELECT, USAGE ON TABLE mod_seguridad.sec_nomfila_seq TO seguridadsigis;
GRANT SELECT, USAGE ON TABLE mod_seguridad.sec_nomidioma_seq TO seguridadsigis;
GRANT SELECT, USAGE ON TABLE mod_seguridad.sec_nomtema_seq TO seguridadsigis;
GRANT SELECT, USAGE ON TABLE mod_seguridad.sec_nomvalor_seq TO seguridadsigis;
GRANT SELECT, USAGE ON TABLE mod_seguridad.sec_segcertificado_seq TO seguridadsigis;
GRANT SELECT, USAGE ON TABLE mod_seguridad.sec_segclaveacceso_seq TO seguridadsigis;
GRANT SELECT, USAGE ON TABLE mod_seguridad.sec_segrestricclaveacceso_seq TO seguridadsigis;
GRANT SELECT, USAGE ON TABLE mod_seguridad.sec_segrol_seq TO seguridadsigis;
GRANT SELECT, USAGE ON TABLE mod_seguridad.sec_segrolesbd_seq TO seguridadsigis;
GRANT SELECT, USAGE ON TABLE mod_seguridad.sec_segusuario_seq TO seguridadsigis;


----------------------------------------
--Permisos sobre tablas
----------------------------------------
	SET search_path = mod_seguridad, pg_catalog;


GRANT SELECT, UPDATE, INSERT, DELETE ON TABLE mod_seguridad.dat_accion TO seguridadsigis;
GRANT SELECT, UPDATE, INSERT, DELETE ON TABLE mod_seguridad.dat_accion_compartimentacion TO seguridadsigis;
GRANT SELECT, UPDATE, INSERT, DELETE ON TABLE mod_seguridad.dat_accion_dat_reporte TO seguridadsigis;
GRANT SELECT, UPDATE, INSERT, DELETE ON TABLE mod_seguridad.dat_bd TO seguridadsigis;
GRANT SELECT, UPDATE, INSERT, DELETE ON TABLE mod_seguridad.dat_entidad_seg_usuario_seg_rol TO seguridadsigis;
GRANT SELECT, UPDATE, INSERT, DELETE ON TABLE mod_seguridad.dat_esquema TO seguridadsigis;
GRANT SELECT, UPDATE, INSERT, DELETE ON TABLE mod_seguridad.dat_funcionalidad TO seguridadsigis;
GRANT SELECT, UPDATE, INSERT, DELETE ON TABLE mod_seguridad.dat_funcionalidad_compartimentacion TO seguridadsigis;
GRANT SELECT, UPDATE, INSERT, DELETE ON TABLE mod_seguridad.dat_funciones TO seguridadsigis;
GRANT SELECT, UPDATE, INSERT, DELETE ON TABLE mod_seguridad.dat_gestor TO seguridadsigis;
GRANT SELECT, UPDATE, INSERT, DELETE ON TABLE mod_seguridad.dat_gestor_dat_servidorbd TO seguridadsigis;
GRANT SELECT, UPDATE, INSERT, DELETE ON TABLE mod_seguridad.dat_parametros TO seguridadsigis;
GRANT SELECT, UPDATE, INSERT, DELETE ON TABLE mod_seguridad.dat_serautenticacion TO seguridadsigis;
GRANT SELECT, UPDATE, INSERT, DELETE ON TABLE mod_seguridad.dat_servicio TO seguridadsigis;
GRANT SELECT, UPDATE, INSERT, DELETE ON TABLE mod_seguridad.dat_servicio_dat_sistema TO seguridadsigis;
GRANT SELECT, UPDATE, INSERT, DELETE ON TABLE mod_seguridad.dat_servidor TO seguridadsigis;
GRANT SELECT, UPDATE, INSERT, DELETE ON TABLE mod_seguridad.dat_servidorbd TO seguridadsigis;
GRANT SELECT, UPDATE, INSERT, DELETE ON TABLE mod_seguridad.dat_sistema TO seguridadsigis;
GRANT SELECT, UPDATE, INSERT, DELETE ON TABLE mod_seguridad.dat_sistema_compartimentacion TO seguridadsigis;
GRANT SELECT, UPDATE, INSERT, DELETE ON TABLE mod_seguridad.dat_sistema_dat_servidores TO seguridadsigis;
GRANT SELECT, UPDATE, INSERT, DELETE ON TABLE mod_seguridad.dat_sistema_seg_rol TO seguridadsigis;
GRANT SELECT, UPDATE, INSERT, DELETE ON TABLE mod_seguridad.dat_sistema_seg_rol_dat_funcionalidad TO seguridadsigis;
GRANT SELECT, UPDATE, INSERT, DELETE ON TABLE mod_seguridad.dat_sistema_seg_rol_dat_funcionalidad_dat_accion TO seguridadsigis;
GRANT SELECT, UPDATE, INSERT, DELETE ON TABLE mod_seguridad.nom_campo TO seguridadsigis;
GRANT SELECT, UPDATE, INSERT, DELETE ON TABLE mod_seguridad.nom_desktop TO seguridadsigis;
GRANT SELECT, UPDATE, INSERT, DELETE ON TABLE mod_seguridad.nom_expresiones TO seguridadsigis;
GRANT SELECT, UPDATE, INSERT, DELETE ON TABLE mod_seguridad.nom_fila TO seguridadsigis;
GRANT SELECT, UPDATE, INSERT, DELETE ON TABLE mod_seguridad.nom_idioma TO seguridadsigis;
GRANT SELECT, UPDATE, INSERT, DELETE ON TABLE mod_seguridad.nom_tema TO seguridadsigis;
GRANT SELECT, UPDATE, INSERT, DELETE ON TABLE mod_seguridad.nom_valor TO seguridadsigis;
GRANT SELECT, UPDATE, INSERT, DELETE ON TABLE mod_seguridad.seg_certificado TO seguridadsigis;
GRANT SELECT, UPDATE, INSERT, DELETE ON TABLE mod_seguridad.seg_claveacceso TO seguridadsigis;
GRANT SELECT, UPDATE, INSERT, DELETE ON TABLE mod_seguridad.seg_compartimentacionroles TO seguridadsigis;
GRANT SELECT, UPDATE, INSERT, DELETE ON TABLE mod_seguridad.seg_compartimentacionusuario TO seguridadsigis;
GRANT SELECT, UPDATE, INSERT, DELETE ON TABLE mod_seguridad.seg_restricclaveacceso TO seguridadsigis;
GRANT SELECT, UPDATE, INSERT, DELETE ON TABLE mod_seguridad.seg_rol TO seguridadsigis;
GRANT SELECT, UPDATE, INSERT, DELETE ON TABLE mod_seguridad.seg_rol_nom_dominio TO seguridadsigis;
GRANT SELECT, UPDATE, INSERT, DELETE ON TABLE mod_seguridad.seg_rolesbd TO seguridadsigis;
GRANT SELECT, UPDATE, INSERT, DELETE ON TABLE mod_seguridad.seg_usuario TO seguridadsigis;
GRANT SELECT, UPDATE, INSERT, DELETE ON TABLE mod_seguridad.seg_usuario_dat_serautenticacion TO seguridadsigis;
GRANT SELECT, UPDATE, INSERT, DELETE ON TABLE mod_seguridad.seg_usuario_nom_dominio TO seguridadsigis;

----------------------------------------
--Permisos sobre funciones
----------------------------------------
	SET search_path = mod_seguridad, pg_catalog;

GRANT EXECUTE ON FUNCTION mod_seguridad.ft_actualizacion_idsistema() TO seguridadsigis;
GRANT EXECUTE ON FUNCTION mod_seguridad.ft_eliminar_dat_sistema() TO seguridadsigis;
GRANT EXECUTE ON FUNCTION mod_seguridad.ft_insertar_dat_sistema() TO seguridadsigis;

----------------------------------------------------------------------------
--Permisos del rol sigis sobre el esquema mod_seguridad
----------------------------------------------------------------------------
----------------------------------------
--Permisos sobre el esquema
----------------------------------------
	SET search_path = mod_seguridad, pg_catalog;

ALTER SCHEMA "mod_seguridad" OWNER TO seguridadsigis;

----------------------------------------
--Permisos sobre secuencias
----------------------------------------
	SET search_path = mod_seguridad, pg_catalog;
	
ALTER TABLE "mod_seguridad"."sec_dataccion_seq"
  OWNER TO seguridadsigis;
ALTER TABLE "mod_seguridad"."sec_datbd_seq"
  OWNER TO seguridadsigis;
ALTER TABLE "mod_seguridad"."sec_datesquema_seq"
  OWNER TO seguridadsigis;
ALTER TABLE "mod_seguridad"."sec_datfuncionalidad_seq"
  OWNER TO seguridadsigis;
ALTER TABLE "mod_seguridad"."sec_datfunciones_seq"
  OWNER TO seguridadsigis;
ALTER TABLE "mod_seguridad"."sec_datgestor_seq"
  OWNER TO seguridadsigis;
ALTER TABLE "mod_seguridad"."sec_datparametros_seq"
  OWNER TO seguridadsigis;
ALTER TABLE "mod_seguridad"."sec_datservicio_seq"
  OWNER TO seguridadsigis;
ALTER TABLE "mod_seguridad"."sec_datservidor_seq"
  OWNER TO seguridadsigis;
ALTER TABLE "mod_seguridad"."sec_datsistema_seq"
  OWNER TO seguridadsigis;
ALTER TABLE "mod_seguridad"."sec_nomcampo_seq"
  OWNER TO seguridadsigis;
ALTER TABLE "mod_seguridad"."sec_nomdesktop_seq"
  OWNER TO seguridadsigis;
ALTER TABLE "mod_seguridad"."sec_nomexpresiones_seq"
  OWNER TO seguridadsigis;
ALTER TABLE "mod_seguridad"."sec_nomfila_seq"
  OWNER TO seguridadsigis;
ALTER TABLE "mod_seguridad"."sec_nomidioma_seq"
  OWNER TO seguridadsigis;
ALTER TABLE "mod_seguridad"."sec_nomtema_seq"
  OWNER TO seguridadsigis;
ALTER TABLE "mod_seguridad"."sec_nomvalor_seq"
  OWNER TO seguridadsigis;
ALTER TABLE "mod_seguridad"."sec_segcertificado_seq"
  OWNER TO seguridadsigis;
ALTER TABLE "mod_seguridad"."sec_segclaveacceso_seq"
  OWNER TO seguridadsigis;
ALTER TABLE "mod_seguridad"."sec_segrestricclaveacceso_seq"
  OWNER TO seguridadsigis;
ALTER TABLE "mod_seguridad"."sec_segrol_seq"
  OWNER TO seguridadsigis;
ALTER TABLE "mod_seguridad"."sec_segrolesbd_seq"
  OWNER TO seguridadsigis;
ALTER TABLE "mod_seguridad"."sec_segusuario_seq"
  OWNER TO seguridadsigis;
----------------------------------------
--Permisos sobre tablas
----------------------------------------
	SET search_path = mod_seguridad, pg_catalog;


ALTER TABLE "mod_seguridad"."dat_accion"
  OWNER TO seguridadsigis;
ALTER TABLE "mod_seguridad"."dat_accion_compartimentacion"
  OWNER TO seguridadsigis;
ALTER TABLE "mod_seguridad"."dat_accion_dat_reporte"
  OWNER TO seguridadsigis;
ALTER TABLE "mod_seguridad"."dat_bd"
  OWNER TO seguridadsigis;
ALTER TABLE "mod_seguridad"."dat_entidad_seg_usuario_seg_rol"
  OWNER TO seguridadsigis;
ALTER TABLE "mod_seguridad"."dat_esquema"
  OWNER TO seguridadsigis;
ALTER TABLE "mod_seguridad"."dat_funcionalidad"
  OWNER TO seguridadsigis;
ALTER TABLE "mod_seguridad"."dat_funcionalidad_compartimentacion"
  OWNER TO seguridadsigis;
ALTER TABLE "mod_seguridad"."dat_funciones"
  OWNER TO seguridadsigis;
ALTER TABLE "mod_seguridad"."dat_gestor"
  OWNER TO seguridadsigis;
ALTER TABLE "mod_seguridad"."dat_gestor_dat_servidorbd"
  OWNER TO seguridadsigis;
ALTER TABLE "mod_seguridad"."dat_parametros"
  OWNER TO seguridadsigis;
ALTER TABLE "mod_seguridad"."dat_serautenticacion"
  OWNER TO seguridadsigis;
ALTER TABLE "mod_seguridad"."dat_servicio"
  OWNER TO seguridadsigis;
ALTER TABLE "mod_seguridad"."dat_servicio_dat_sistema"
  OWNER TO seguridadsigis;
ALTER TABLE "mod_seguridad"."dat_servidor"
  OWNER TO seguridadsigis;
ALTER TABLE "mod_seguridad"."dat_servidorbd"
  OWNER TO seguridadsigis;
ALTER TABLE "mod_seguridad"."dat_sistema"
  OWNER TO seguridadsigis;
ALTER TABLE "mod_seguridad"."dat_sistema_compartimentacion"
  OWNER TO seguridadsigis;
ALTER TABLE "mod_seguridad"."dat_sistema_dat_servidores"
  OWNER TO seguridadsigis;
ALTER TABLE "mod_seguridad"."dat_sistema_seg_rol"
  OWNER TO seguridadsigis;
ALTER TABLE "mod_seguridad"."dat_sistema_seg_rol_dat_funcionalidad"
  OWNER TO seguridadsigis;
ALTER TABLE "mod_seguridad"."dat_sistema_seg_rol_dat_funcionalidad_dat_accion"
  OWNER TO seguridadsigis;
ALTER TABLE "mod_seguridad"."nom_campo"
  OWNER TO seguridadsigis;
ALTER TABLE "mod_seguridad"."nom_desktop"
  OWNER TO seguridadsigis;
ALTER TABLE "mod_seguridad"."nom_expresiones"
  OWNER TO seguridadsigis;
ALTER TABLE "mod_seguridad"."nom_fila"
  OWNER TO seguridadsigis;
ALTER TABLE "mod_seguridad"."nom_idioma"
  OWNER TO seguridadsigis;
ALTER TABLE "mod_seguridad"."nom_tema"
  OWNER TO seguridadsigis;
ALTER TABLE "mod_seguridad"."nom_valor"
  OWNER TO seguridadsigis;
ALTER TABLE "mod_seguridad"."seg_certificado"
  OWNER TO seguridadsigis;
ALTER TABLE "mod_seguridad"."seg_claveacceso"
  OWNER TO seguridadsigis;
ALTER TABLE "mod_seguridad"."seg_compartimentacionroles"
  OWNER TO seguridadsigis;
ALTER TABLE "mod_seguridad"."seg_compartimentacionusuario"
  OWNER TO seguridadsigis;
ALTER TABLE "mod_seguridad"."seg_restricclaveacceso"
  OWNER TO seguridadsigis;
ALTER TABLE "mod_seguridad"."seg_rol"
  OWNER TO seguridadsigis;
ALTER TABLE "mod_seguridad"."seg_rol_nom_dominio"
  OWNER TO seguridadsigis;
ALTER TABLE "mod_seguridad"."seg_rolesbd"
  OWNER TO seguridadsigis;
ALTER TABLE "mod_seguridad"."seg_usuario"
  OWNER TO seguridadsigis;
ALTER TABLE "mod_seguridad"."seg_usuario_dat_serautenticacion"
  OWNER TO seguridadsigis;
ALTER TABLE "mod_seguridad"."seg_usuario_nom_dominio"
  OWNER TO seguridadsigis;
----------------------------------------
--Permisos sobre funciones
----------------------------------------
	SET search_path = mod_seguridad, pg_catalog;
	
ALTER FUNCTION "mod_seguridad"."ft_actualizacion_idsistema"()
  OWNER TO seguridadsigis;
ALTER FUNCTION "mod_seguridad"."ft_eliminar_dat_sistema"()
  OWNER TO seguridadsigis;
ALTER FUNCTION "mod_seguridad"."ft_insertar_dat_sistema"()
  OWNER TO seguridadsigis;
----------------------------------------
--fin de la transaccion
COMMIT;