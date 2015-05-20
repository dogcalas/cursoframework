----------------V6.0.0---------------------
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
--Las reglas son aplicables a todo este documento.----
*/																							
------------------------------------------------------------------------------------------------

--comienza la transaccion--
BEGIN;
-- Propiedades de la BD       
	SET client_encoding = 'UTF8';
	SET standard_conforming_strings = off;
	SET check_function_bodies = false;
	SET client_min_messages = warning;
	SET escape_string_warning = off;

INSERT INTO mod_datosmaestros.conf_version ( versionscript,  versionscriptinicial,  esquema,  esquemarelacion,  tiposcript,  classcript,  script) 
VALUES ('V6.0.0', 'V6.0.0','mod_seguridad',null,'C','P','I');
----------------------------------------------------------------------------
--Permisos del rol seguridad sobre el esquema mod_seguridad
ALTER SCHEMA mod_seguridad OWNER TO sauxe;
----------------------------------------------------------------------------
----------------------------------------
--Permisos sobre el esquema
----------------------------------------
	SET search_path = mod_seguridad, pg_catalog;

GRANT USAGE ON SCHEMA mod_seguridad TO sauxe;
GRANT USAGE ON SCHEMA mod_seguridad TO loginuser;

--- Secuencias

GRANT ALL ON TABLE mod_seguridad.sec_dataccion_seq TO sauxe;
GRANT ALL ON TABLE mod_seguridad.sec_datbd_seq TO sauxe;
GRANT ALL ON TABLE mod_seguridad.sec_datesquema_seq TO sauxe;
GRANT ALL ON TABLE mod_seguridad.sec_datfuncionalidad_seq TO sauxe;
GRANT ALL ON TABLE mod_seguridad.sec_datfunciones_seq TO sauxe;
GRANT ALL ON TABLE mod_seguridad.sec_datgestor_seq TO sauxe;
GRANT ALL ON TABLE mod_seguridad.sec_datparametros_seq TO sauxe;
GRANT ALL ON TABLE mod_seguridad.sec_datservicio_seq TO sauxe;
GRANT ALL ON TABLE mod_seguridad.sec_datservidor_seq TO sauxe;
GRANT ALL ON TABLE mod_seguridad.sec_datsistema_seq TO sauxe;
GRANT ALL ON TABLE mod_seguridad.sec_nomcampo_seq TO sauxe;
GRANT ALL ON TABLE mod_seguridad.sec_nomdesktop_seq TO sauxe;
GRANT ALL ON TABLE mod_seguridad.sec_nomexpresiones_seq TO sauxe;
GRANT ALL ON TABLE mod_seguridad.sec_nomfila_seq TO sauxe;
GRANT ALL ON TABLE mod_seguridad.sec_nomidioma_seq TO sauxe;
GRANT ALL ON TABLE mod_seguridad.sec_nomtema_seq TO sauxe;
GRANT ALL ON TABLE mod_seguridad.sec_nomvalor_seq TO sauxe;
GRANT ALL ON TABLE mod_seguridad.sec_segcertificado_seq TO sauxe;
GRANT ALL ON TABLE mod_seguridad.sec_segclaveacceso_seq TO sauxe;
GRANT ALL ON TABLE mod_seguridad.sec_segrestricclaveacceso_seq TO sauxe;
GRANT ALL ON TABLE mod_seguridad.sec_segrol_seq TO sauxe;
GRANT ALL ON TABLE mod_seguridad.sec_segrolesbd_seq TO sauxe;
GRANT ALL ON TABLE mod_seguridad.sec_segusuario_seq TO sauxe;
--------Permisos Agregados Por Jose Eduardo
GRANT ALL ON TABLE mod_seguridad.sec_datobjetobd_seq TO sauxe;
GRANT SELECT, USAGE ON TABLE mod_seguridad.sec_datobjetobd_seq TO sauxe;

GRANT ALL ON TABLE mod_seguridad.sec_datservicioioc_seq TO sauxe;
GRANT SELECT, USAGE ON TABLE mod_seguridad.sec_datservicioioc_seq TO sauxe;

GRANT ALL ON TABLE mod_seguridad.sec_objetobd TO sauxe;
GRANT SELECT, USAGE ON TABLE mod_seguridad.sec_objetobd TO sauxe;

GRANT ALL ON TABLE mod_seguridad.sec_segrolesbd_seq TO sauxe;
GRANT SELECT, USAGE ON TABLE mod_seguridad.sec_segrolesbd_seq TO sauxe;

GRANT USAGE ON TABLE mod_seguridad.sec_segcertificado_seq TO loginuser;
--------Fin de Permisos Agregados por Jose Eduardo

--- Tablas

GRANT ALL ON TABLE mod_seguridad.dat_accion TO sauxe;
GRANT ALL ON TABLE mod_seguridad.dat_accion_compartimentacion TO sauxe;
GRANT ALL ON TABLE mod_seguridad.dat_accion_dat_reporte TO sauxe;
GRANT ALL ON TABLE mod_seguridad.dat_bd TO sauxe;
GRANT ALL ON TABLE mod_seguridad.dat_entidad_seg_usuario_seg_rol TO sauxe;
GRANT ALL ON TABLE mod_seguridad.dat_esquema TO sauxe;
GRANT ALL ON TABLE mod_seguridad.dat_funcionalidad TO sauxe;
GRANT ALL ON TABLE mod_seguridad.dat_funcionalidad_compartimentacion TO sauxe;
GRANT ALL ON TABLE mod_seguridad.dat_funciones TO sauxe;
GRANT ALL ON TABLE mod_seguridad.dat_gestor TO sauxe;
GRANT ALL ON TABLE mod_seguridad.dat_gestor_dat_servidorbd TO sauxe;
GRANT ALL ON TABLE mod_seguridad.dat_parametros TO sauxe;
GRANT ALL ON TABLE mod_seguridad.dat_serautenticacion TO sauxe;
GRANT ALL ON TABLE mod_seguridad.dat_serautenticacion TO loginuser;
GRANT ALL ON TABLE mod_seguridad.dat_servicio TO sauxe;
GRANT ALL ON TABLE mod_seguridad.dat_servicio_dat_sistema TO sauxe;
GRANT ALL ON TABLE mod_seguridad.dat_servidor TO sauxe;
GRANT ALL ON TABLE mod_seguridad.dat_servidorbd TO sauxe;
GRANT ALL ON TABLE mod_seguridad.dat_sistema TO sauxe;
GRANT ALL ON TABLE mod_seguridad.dat_sistema_compartimentacion TO sauxe;
GRANT ALL ON TABLE mod_seguridad.dat_sistema_dat_servidores TO sauxe;
GRANT ALL ON TABLE mod_seguridad.dat_sistema_seg_rol TO sauxe;
GRANT ALL ON TABLE mod_seguridad.dat_sistema_seg_rol_dat_funcionalidad TO sauxe;
GRANT ALL ON TABLE mod_seguridad.dat_sistema_seg_rol_dat_funcionalidad_dat_accion TO sauxe;
GRANT ALL ON TABLE mod_seguridad.nom_campo TO sauxe;
GRANT ALL ON TABLE mod_seguridad.nom_desktop TO sauxe;
GRANT ALL ON TABLE mod_seguridad.nom_expresiones TO sauxe;
GRANT ALL ON TABLE mod_seguridad.nom_fila TO sauxe;
GRANT ALL ON TABLE mod_seguridad.nom_idioma TO sauxe;
GRANT ALL ON TABLE mod_seguridad.nom_tema TO sauxe;
GRANT ALL ON TABLE mod_seguridad.nom_valor TO sauxe;
GRANT ALL ON TABLE mod_seguridad.seg_certificado TO sauxe;
GRANT ALL ON TABLE mod_seguridad.seg_claveacceso TO sauxe;
GRANT ALL ON TABLE mod_seguridad.seg_compartimentacionroles TO sauxe;
GRANT ALL ON TABLE mod_seguridad.seg_compartimentacionusuario TO sauxe;
GRANT ALL ON TABLE mod_seguridad.seg_restricclaveacceso TO sauxe;
GRANT ALL ON TABLE mod_seguridad.seg_rol TO sauxe;
GRANT ALL ON TABLE mod_seguridad.seg_rol_nom_dominio TO sauxe;
GRANT ALL ON TABLE mod_seguridad.seg_rolesbd TO sauxe;
GRANT ALL ON TABLE mod_seguridad.seg_usuario TO sauxe;
GRANT ALL ON TABLE mod_seguridad.seg_usuario_dat_serautenticacion TO sauxe;
GRANT ALL ON TABLE mod_seguridad.seg_usuario_nom_dominio TO sauxe;
GRANT ALL ON TABLE mod_seguridad.seg_usuario TO loginuser;
GRANT ALL ON TABLE mod_seguridad.dat_funcionalidades_restringidas_usuario_entidad_rol TO sauxe;


--------Permisos Agregados Por Jose Eduardo
GRANT ALL ON TABLE mod_seguridad.dat_accion_dat_objetobd TO sauxe;
GRANT ALL ON TABLE mod_seguridad.dat_accion_dat_servicioioc TO sauxe;
GRANT ALL ON TABLE mod_seguridad.dat_reglas TO sauxe;
GRANT ALL ON TABLE mod_seguridad.seg_rol_asignacion TO sauxe;
GRANT ALL ON TABLE mod_seguridad.dat_objetobd TO sauxe;
GRANT ALL ON TABLE mod_seguridad.dat_servicio_objetobd TO sauxe;
GRANT ALL ON TABLE mod_seguridad.dat_servicioioc TO sauxe;
GRANT ALL ON TABLE mod_seguridad.nom_objetospermisos TO sauxe;
GRANT ALL ON TABLE mod_seguridad.seg_rol_dat_servidor_dat_gestor TO sauxe;
GRANT ALL ON TABLE mod_seguridad.seg_usuario_dat_servidor_dat_gestor TO sauxe;

GRANT SELECT ON TABLE mod_seguridad.dat_entidad_seg_usuario_seg_rol TO loginuser; 
GRANT SELECT ON TABLE mod_seguridad.dat_funcionalidad TO loginuser; 
GRANT SELECT ON TABLE mod_seguridad.dat_servidor TO loginuser; 
GRANT SELECT ON TABLE mod_seguridad.dat_sistema TO loginuser; 
GRANT SELECT ON TABLE mod_seguridad.dat_sistema_seg_rol TO loginuser; 
GRANT SELECT ON TABLE mod_seguridad.dat_sistema_seg_rol_dat_funcionalidad TO loginuser; 
GRANT SELECT ON TABLE mod_seguridad.nom_campo TO loginuser; 
GRANT SELECT ON TABLE mod_seguridad.nom_desktop TO loginuser; 
GRANT SELECT ON TABLE mod_seguridad.nom_fila TO loginuser; 
GRANT SELECT ON TABLE mod_seguridad.nom_idioma TO loginuser; 
GRANT SELECT ON TABLE mod_seguridad.nom_tema TO loginuser; 
GRANT SELECT ON TABLE mod_seguridad.nom_valor TO loginuser; 
GRANT SELECT, INSERT, DELETE, UPDATE ON TABLE mod_seguridad.seg_certificado TO loginuser; 
GRANT SELECT ON TABLE mod_seguridad.seg_claveacceso TO loginuser; 
GRANT SELECT ON TABLE mod_seguridad.seg_restricclaveacceso TO loginuser; 
GRANT SELECT ON TABLE mod_seguridad.seg_rol TO loginuser; 
GRANT SELECT ON TABLE mod_seguridad.seg_usuario TO loginuser; 
GRANT SELECT ON TABLE mod_seguridad.seg_usuario_dat_serautenticacion TO loginuser; 
--------Fin de Permisos Agregados por Jose Eduardo

--- Funciones disparadoras

GRANT EXECUTE ON FUNCTION mod_seguridad.ft_actualizacion_idsistema() TO sauxe;
GRANT EXECUTE ON FUNCTION mod_seguridad.ft_eliminar_dat_sistema() TO sauxe;
GRANT EXECUTE ON FUNCTION mod_seguridad.ft_insertar_dat_sistema() TO sauxe;

--- Dueño funciones

ALTER FUNCTION mod_seguridad.ft_actualizacion_idsistema() OWNER TO sauxe;
ALTER FUNCTION mod_seguridad.ft_eliminar_dat_sistema() OWNER TO sauxe;
ALTER FUNCTION mod_seguridad.ft_insertar_dat_sistema() OWNER TO sauxe;

--- Dueño tablas y secuencias

ALTER TABLE mod_seguridad.sec_dataccion_seq OWNER TO sauxe;
ALTER TABLE mod_seguridad.dat_accion OWNER TO sauxe;
ALTER TABLE mod_seguridad.dat_accion_compartimentacion OWNER TO sauxe;
ALTER TABLE mod_seguridad.dat_accion_dat_reporte OWNER TO sauxe;
ALTER TABLE mod_seguridad.sec_datbd_seq OWNER TO sauxe;
ALTER TABLE mod_seguridad.dat_bd OWNER TO sauxe;
ALTER TABLE mod_seguridad.dat_entidad_seg_usuario_seg_rol OWNER TO sauxe;
ALTER TABLE mod_seguridad.sec_datesquema_seq OWNER TO sauxe;
ALTER TABLE mod_seguridad.dat_esquema OWNER TO sauxe;
ALTER TABLE mod_seguridad.sec_datfuncionalidad_seq OWNER TO sauxe;
ALTER TABLE mod_seguridad.dat_funcionalidad OWNER TO sauxe;
ALTER TABLE mod_seguridad.dat_funcionalidad_compartimentacion OWNER TO sauxe;
ALTER TABLE mod_seguridad.sec_datfunciones_seq OWNER TO sauxe;
ALTER TABLE mod_seguridad.dat_funciones OWNER TO sauxe;
ALTER TABLE mod_seguridad.sec_datgestor_seq OWNER TO sauxe;
ALTER TABLE mod_seguridad.dat_gestor OWNER TO sauxe;
ALTER TABLE mod_seguridad.dat_gestor_dat_servidorbd OWNER TO sauxe;
ALTER TABLE mod_seguridad.sec_datparametros_seq OWNER TO sauxe;
ALTER TABLE mod_seguridad.dat_parametros OWNER TO sauxe;
ALTER TABLE mod_seguridad.dat_serautenticacion OWNER TO sauxe;
ALTER TABLE mod_seguridad.sec_datservicio_seq OWNER TO sauxe;
ALTER TABLE mod_seguridad.dat_servicio OWNER TO sauxe;
ALTER TABLE mod_seguridad.dat_servicio_dat_sistema OWNER TO sauxe;
ALTER TABLE mod_seguridad.sec_datservidor_seq OWNER TO sauxe;
ALTER TABLE mod_seguridad.dat_servidor OWNER TO sauxe;
ALTER TABLE mod_seguridad.dat_servidorbd OWNER TO sauxe;
ALTER TABLE mod_seguridad.sec_datsistema_seq OWNER TO sauxe;
ALTER TABLE mod_seguridad.dat_sistema OWNER TO sauxe;
ALTER TABLE mod_seguridad.dat_sistema_compartimentacion OWNER TO sauxe;
ALTER TABLE mod_seguridad.dat_sistema_dat_servidores OWNER TO sauxe;
ALTER TABLE mod_seguridad.dat_sistema_seg_rol OWNER TO sauxe;
ALTER TABLE mod_seguridad.dat_sistema_seg_rol_dat_funcionalidad OWNER TO sauxe;
ALTER TABLE mod_seguridad.dat_sistema_seg_rol_dat_funcionalidad_dat_accion OWNER TO sauxe;
ALTER TABLE mod_seguridad.sec_nomcampo_seq OWNER TO sauxe;
ALTER TABLE mod_seguridad.nom_campo OWNER TO sauxe;
ALTER TABLE mod_seguridad.sec_nomdesktop_seq OWNER TO sauxe;
ALTER TABLE mod_seguridad.nom_desktop OWNER TO sauxe;
ALTER TABLE mod_seguridad.sec_nomexpresiones_seq OWNER TO sauxe;
ALTER TABLE mod_seguridad.nom_expresiones OWNER TO sauxe;
ALTER TABLE mod_seguridad.sec_nomfila_seq OWNER TO sauxe;
ALTER TABLE mod_seguridad.nom_fila OWNER TO sauxe;
ALTER TABLE mod_seguridad.sec_nomidioma_seq OWNER TO sauxe;
ALTER TABLE mod_seguridad.nom_idioma OWNER TO sauxe;
ALTER TABLE mod_seguridad.sec_nomtema_seq OWNER TO sauxe;
ALTER TABLE mod_seguridad.nom_tema OWNER TO sauxe;
ALTER TABLE mod_seguridad.sec_nomvalor_seq OWNER TO sauxe;
ALTER TABLE mod_seguridad.nom_valor OWNER TO sauxe;
ALTER TABLE mod_seguridad.sec_segcertificado_seq OWNER TO sauxe;
ALTER TABLE mod_seguridad.sec_segclaveacceso_seq OWNER TO sauxe;
ALTER TABLE mod_seguridad.sec_segrestricclaveacceso_seq OWNER TO sauxe;
ALTER TABLE mod_seguridad.sec_segrol_seq OWNER TO sauxe;
ALTER TABLE mod_seguridad.sec_segrolesbd_seq OWNER TO sauxe;
ALTER TABLE mod_seguridad.sec_segusuario_seq OWNER TO sauxe;
ALTER TABLE mod_seguridad.seg_certificado OWNER TO sauxe;
ALTER TABLE mod_seguridad.seg_claveacceso OWNER TO sauxe;
ALTER TABLE mod_seguridad.seg_compartimentacionroles OWNER TO sauxe;
ALTER TABLE mod_seguridad.seg_compartimentacionusuario OWNER TO sauxe;
ALTER TABLE mod_seguridad.seg_restricclaveacceso OWNER TO sauxe;
ALTER TABLE mod_seguridad.seg_rol OWNER TO sauxe;
ALTER TABLE mod_seguridad.seg_rol_nom_dominio OWNER TO sauxe;
ALTER TABLE mod_seguridad.seg_rolesbd OWNER TO sauxe;
ALTER TABLE mod_seguridad.seg_usuario OWNER TO sauxe;
ALTER TABLE mod_seguridad.seg_usuario_dat_serautenticacion OWNER TO sauxe;
ALTER TABLE mod_seguridad.seg_usuario_nom_dominio OWNER TO sauxe;
ALTER TABLE mod_seguridad.dat_funcionalidades_restringidas_usuario_entidad_rol OWNER TO sauxe;

--------Due??os Agregados Por Jose Eduardo(Secuencias)
ALTER TABLE mod_seguridad.sec_datobjetobd_seq OWNER TO sauxe;
ALTER TABLE mod_seguridad.sec_datservicioioc_seq OWNER TO sauxe;
ALTER TABLE mod_seguridad.sec_objetobd OWNER TO sauxe;
ALTER TABLE mod_seguridad.sec_segrolesbd_seq OWNER TO sauxe;
--------Due??os Agregados Por Jose Eduardo(Tablas)
ALTER TABLE mod_seguridad.dat_accion_dat_objetobd OWNER TO sauxe;

ALTER TABLE mod_seguridad.dat_accion_dat_servicioioc OWNER TO sauxe;

ALTER TABLE mod_seguridad.dat_reglas OWNER TO sauxe;

ALTER TABLE mod_seguridad.seg_rol_asignacion OWNER TO sauxe;

ALTER TABLE mod_seguridad.dat_objetobd OWNER TO sauxe;

ALTER TABLE mod_seguridad.dat_servicio_objetobd OWNER TO sauxe;

ALTER TABLE mod_seguridad.dat_servicioioc OWNER TO sauxe;

ALTER TABLE mod_seguridad.nom_objetospermisos OWNER TO sauxe;

ALTER TABLE mod_seguridad.nom_tipoconex OWNER TO sauxe;

ALTER TABLE mod_seguridad.seg_rol_dat_servidor_dat_gestor OWNER TO sauxe;

ALTER TABLE mod_seguridad.seg_usuario_dat_servidor_dat_gestor OWNER TO sauxe;
--------Fin Due??os Agregados Por Jose Eduardo

COMMIT;
