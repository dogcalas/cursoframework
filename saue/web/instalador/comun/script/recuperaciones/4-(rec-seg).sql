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
VALUES ('V5.0.0', 'V5.0.0','mod_recuperaciones','mod_seguridad','C','P','I');
----------------------------------------------------------------------------
--Permisos del rol reporte sobre el esquema mod_seguridad
----------------------------------------------------------------------------
----------------------------------------
--Permisos sobre el esquema
----------------------------------------
	SET search_path = mod_seguridad, pg_catalog;

GRANT USAGE ON SCHEMA mod_seguridad TO sauxe;
----------------------------------------
-- Permisos sobre secuencias
----------------------------------------
	SET search_path = mod_seguridad, pg_catalog;


GRANT SELECT, USAGE ON TABLE mod_seguridad.sec_dataccion_seq TO sauxe;
GRANT SELECT, USAGE ON TABLE mod_seguridad.sec_datbd_seq TO sauxe;
GRANT SELECT, USAGE ON TABLE mod_seguridad.sec_datesquema_seq TO sauxe;
GRANT SELECT, USAGE ON TABLE mod_seguridad.sec_datfuncionalidad_seq TO sauxe;
GRANT SELECT, USAGE ON TABLE mod_seguridad.sec_datfunciones_seq TO sauxe;
GRANT SELECT, USAGE ON TABLE mod_seguridad.sec_datgestor_seq TO sauxe;
GRANT SELECT, USAGE ON TABLE mod_seguridad.sec_datparametros_seq TO sauxe;
GRANT SELECT, USAGE ON TABLE mod_seguridad.sec_datservicio_seq TO sauxe;
GRANT SELECT, USAGE ON TABLE mod_seguridad.sec_datservidor_seq TO sauxe;
GRANT SELECT, USAGE ON TABLE mod_seguridad.sec_datsistema_seq TO sauxe;
GRANT SELECT, USAGE ON TABLE mod_seguridad.sec_nomcampo_seq TO sauxe;
GRANT SELECT, USAGE ON TABLE mod_seguridad.sec_nomdesktop_seq TO sauxe;
GRANT SELECT, USAGE ON TABLE mod_seguridad.sec_nomexpresiones_seq TO sauxe;
GRANT SELECT, USAGE ON TABLE mod_seguridad.sec_nomfila_seq TO sauxe;
GRANT SELECT, USAGE ON TABLE mod_seguridad.sec_nomidioma_seq TO sauxe;
GRANT SELECT, USAGE ON TABLE mod_seguridad.sec_nomtema_seq TO sauxe;
GRANT SELECT, USAGE ON TABLE mod_seguridad.sec_nomvalor_seq TO sauxe;
GRANT SELECT, USAGE ON TABLE mod_seguridad.sec_segcertificado_seq TO sauxe;
GRANT SELECT, USAGE ON TABLE mod_seguridad.sec_segclaveacceso_seq TO sauxe;
GRANT SELECT, USAGE ON TABLE mod_seguridad.sec_segrestricclaveacceso_seq TO sauxe;
GRANT SELECT, USAGE ON TABLE mod_seguridad.sec_segrol_seq TO sauxe;
GRANT SELECT, USAGE ON TABLE mod_seguridad.sec_segrolesbd_seq TO sauxe;
GRANT SELECT, USAGE ON TABLE mod_seguridad.sec_segusuario_seq TO sauxe;

----------------------------------------
--Permisos sobre tablas
----------------------------------------
	SET search_path = mod_seguridad, pg_catalog;

GRANT SELECT ON TABLE mod_seguridad.dat_accion TO sauxe;
GRANT SELECT ON TABLE mod_seguridad.dat_accion_compartimentacion TO sauxe;
GRANT SELECT ON TABLE mod_seguridad.dat_accion_dat_reporte TO sauxe;
GRANT SELECT ON TABLE mod_seguridad.dat_bd TO sauxe;
GRANT SELECT ON TABLE mod_seguridad.dat_entidad_seg_usuario_seg_rol TO sauxe;
GRANT SELECT ON TABLE mod_seguridad.dat_esquema TO sauxe;
GRANT SELECT ON TABLE mod_seguridad.dat_funcionalidad TO sauxe;
GRANT SELECT ON TABLE mod_seguridad.dat_funcionalidad_compartimentacion TO sauxe;
GRANT SELECT ON TABLE mod_seguridad.dat_funciones TO sauxe;
GRANT SELECT ON TABLE mod_seguridad.dat_gestor TO sauxe;
GRANT SELECT ON TABLE mod_seguridad.dat_gestor_dat_servidorbd TO sauxe;
GRANT SELECT ON TABLE mod_seguridad.dat_parametros TO sauxe;
GRANT SELECT ON TABLE mod_seguridad.dat_serautenticacion TO sauxe;
GRANT SELECT ON TABLE mod_seguridad.dat_servicio TO sauxe;
GRANT SELECT ON TABLE mod_seguridad.dat_servicio_dat_sistema TO sauxe;
GRANT SELECT ON TABLE mod_seguridad.dat_servidor TO sauxe;
GRANT SELECT ON TABLE mod_seguridad.dat_servidorbd TO sauxe;
GRANT SELECT ON TABLE mod_seguridad.dat_sistema TO sauxe;
GRANT SELECT ON TABLE mod_seguridad.dat_sistema_compartimentacion TO sauxe;
GRANT SELECT ON TABLE mod_seguridad.dat_sistema_dat_servidores TO sauxe;
GRANT SELECT ON TABLE mod_seguridad.dat_sistema_seg_rol TO sauxe;
GRANT SELECT ON TABLE mod_seguridad.dat_sistema_seg_rol_dat_funcionalidad TO sauxe;
GRANT SELECT ON TABLE mod_seguridad.dat_sistema_seg_rol_dat_funcionalidad_dat_accion TO sauxe;
GRANT SELECT ON TABLE mod_seguridad.nom_campo TO sauxe;
GRANT SELECT ON TABLE mod_seguridad.nom_desktop TO sauxe;
GRANT SELECT ON TABLE mod_seguridad.nom_expresiones TO sauxe;
GRANT SELECT ON TABLE mod_seguridad.nom_fila TO sauxe;
GRANT SELECT ON TABLE mod_seguridad.nom_idioma TO sauxe;
GRANT SELECT ON TABLE mod_seguridad.nom_tema TO sauxe;
GRANT SELECT ON TABLE mod_seguridad.nom_valor TO sauxe;
GRANT SELECT ON TABLE mod_seguridad.seg_certificado TO sauxe;
GRANT SELECT ON TABLE mod_seguridad.seg_claveacceso TO sauxe;
GRANT SELECT ON TABLE mod_seguridad.seg_compartimentacionroles TO sauxe;
GRANT SELECT ON TABLE mod_seguridad.seg_compartimentacionusuario TO sauxe;
GRANT SELECT ON TABLE mod_seguridad.seg_restricclaveacceso TO sauxe;
GRANT SELECT ON TABLE mod_seguridad.seg_rol TO sauxe;
GRANT SELECT ON TABLE mod_seguridad.seg_rol_nom_dominio TO sauxe;
GRANT SELECT ON TABLE mod_seguridad.seg_rolesbd TO sauxe;
GRANT SELECT ON TABLE mod_seguridad.seg_usuario TO sauxe;
GRANT SELECT ON TABLE mod_seguridad.seg_usuario_dat_serautenticacion TO sauxe;
GRANT SELECT ON TABLE mod_seguridad.seg_usuario_nom_dominio TO sauxe;

----------------------------------------
--Permisos sobre funciones
----------------------------------------
	SET search_path = mod_seguridad, pg_catalog;

GRANT EXECUTE ON FUNCTION mod_seguridad.ft_actualizacion_idsistema() TO sauxe;
GRANT EXECUTE ON FUNCTION mod_seguridad.ft_eliminar_dat_sistema() TO sauxe;
GRANT EXECUTE ON FUNCTION mod_seguridad.ft_insertar_dat_sistema() TO sauxe;

----------------------------------------
--fin de la transaccion
COMMIT;