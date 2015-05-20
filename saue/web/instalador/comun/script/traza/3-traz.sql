--------V7.0.0-----------------------
/*
--CENTRO DE SOLUCIONES DE GESTIÓN		----
--Departamento de tecnología			----
--2014									----
--SCRIPT de INSTALACION de DATOS INICIALES   	----
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
VALUES ('V7.0.0', 'V7.0.0','mod_traza',null,'I','D','I');
---------------------------------------------------------------------------------------------------------------------------
--Datos del esquema mod_traza
---------------------------------------------------------------------------------------------------------------------------	
	SET search_path = mod_traza, pg_catalog;


----------------------------------------
INSERT INTO nom_tipotraza (idtipotraza, tipotraza) VALUES (1, 'Acción');
INSERT INTO nom_tipotraza (idtipotraza, tipotraza) VALUES (2, 'Excepción');
INSERT INTO nom_tipotraza (idtipotraza, tipotraza) VALUES (3, 'Rendimiento');
INSERT INTO nom_tipotraza (idtipotraza, tipotraza) VALUES (4, 'Integración');
INSERT INTO nom_tipotraza (idtipotraza, tipotraza) VALUES (5, 'Excepción de Integración');
INSERT INTO nom_tipotraza (idtipotraza, tipotraza) VALUES (6, 'Datos');
INSERT INTO nom_tipotraza (idtipotraza, tipotraza) VALUES (7, 'Proceso');


INSERT INTO nom_operacion (idoperacion, denominacion) VALUES (1, 'Insertar');
INSERT INTO nom_operacion (idoperacion, denominacion) VALUES (2, 'Modificar');
INSERT INTO nom_operacion (idoperacion, denominacion) VALUES (3, 'Eliminar');

----------------------------------------
-- registro de funcionalidades y permisos en mod_seguridad --
SET search_path = mod_seguridad, pg_catalog;

INSERT INTO dat_sistema (idsistema, idpadre, denominacion, icono, abreviatura, descripcion, externa, lft, rgt, iddominio) VALUES (1001, 1001, 'Traza', 'traza', 'trz', '', '', 15, 16, 1);

INSERT INTO dat_funcionalidad (idfuncionalidad, index, referencia, denominacion, descripcion, icono, idsistema, iddominio) VALUES (10001, 0, 'traza/index.php/gestionartraza/gestionartraza', 'Reportes de traza', '', 'falta', 1001, 1);
INSERT INTO dat_funcionalidad (idfuncionalidad, index, referencia, denominacion, descripcion, icono, idsistema, iddominio) VALUES (10002, 0, 'traza/index.php/configurar/configurar', 'Activar trazas', '', 'falta', 1001, 1);

INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio)VALUES (126, 'icon', 'cargarcombotipo', '', 'cargarcombotipo', 10001, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio)VALUES (127, 'icon', 'confform', '', 'confform', 10001, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio)VALUES (128, 'icon', 'confgrid', '', 'confgrid', 10001, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio)VALUES (129, 'icon', 'cargargrid', '', 'cargargrid', 10001, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio)VALUES (130, 'icon', 'cargar', '', 'cargar', 10002, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio)VALUES (131, 'icon', 'limpiar', '', 'limpiar', 10002, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio)VALUES (132, 'icon', 'cambiar', '', 'cambiar', 10002, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio)VALUES (133, 'icon', 'exportarxml', '', 'exportarxml', 10001, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio)VALUES (166, 'icon', 'cargarestados', '', 'cargarestados', 10001, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio)VALUES (167, 'icon', 'cargarcomboestado', '', 'cargarcomboestado', 10001, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio)VALUES (168, 'icon', 'activartrazas', '', 'activartrazas', 10001, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio)VALUES (169, 'icon', 'mostrardatos', '', 'mostrardatos', 10001, 1);

INSERT INTO dat_sistema_seg_rol (idrol, idsistema) VALUES (10000000001,1001);

INSERT INTO dat_sistema_seg_rol_dat_funcionalidad (idfuncionalidad, idrol, idsistema) VALUES (10001, 10000000001, 1001);
INSERT INTO dat_sistema_seg_rol_dat_funcionalidad (idfuncionalidad, idrol, idsistema) VALUES (10002, 10000000001, 1001);

INSERT INTO dat_sistema_seg_rol_dat_funcionalidad_dat_accion (idaccion, idfuncionalidad, idrol, idsistema) VALUES (126, 10001, 10000000001, 1001);
INSERT INTO dat_sistema_seg_rol_dat_funcionalidad_dat_accion (idaccion, idfuncionalidad, idrol, idsistema) VALUES (127, 10001, 10000000001, 1001);
INSERT INTO dat_sistema_seg_rol_dat_funcionalidad_dat_accion (idaccion, idfuncionalidad, idrol, idsistema) VALUES (128, 10001, 10000000001, 1001);
INSERT INTO dat_sistema_seg_rol_dat_funcionalidad_dat_accion (idaccion, idfuncionalidad, idrol, idsistema) VALUES (129, 10001, 10000000001, 1001);
INSERT INTO dat_sistema_seg_rol_dat_funcionalidad_dat_accion (idaccion, idfuncionalidad, idrol, idsistema) VALUES (133, 10001, 10000000001, 1001);
INSERT INTO dat_sistema_seg_rol_dat_funcionalidad_dat_accion (idaccion, idfuncionalidad, idrol, idsistema) VALUES (166, 10001, 10000000001, 1001);
INSERT INTO dat_sistema_seg_rol_dat_funcionalidad_dat_accion (idaccion, idfuncionalidad, idrol, idsistema) VALUES (167, 10001, 10000000001, 1001);
INSERT INTO dat_sistema_seg_rol_dat_funcionalidad_dat_accion (idaccion, idfuncionalidad, idrol, idsistema) VALUES (168, 10001, 10000000001, 1001);
INSERT INTO dat_sistema_seg_rol_dat_funcionalidad_dat_accion (idaccion, idfuncionalidad, idrol, idsistema) VALUES (169, 10001, 10000000001, 1001);
INSERT INTO dat_sistema_seg_rol_dat_funcionalidad_dat_accion (idaccion, idfuncionalidad, idrol, idsistema) VALUES (130, 10002, 10000000001, 1001);
INSERT INTO dat_sistema_seg_rol_dat_funcionalidad_dat_accion (idaccion, idfuncionalidad, idrol, idsistema) VALUES (131, 10002, 10000000001, 1001);
INSERT INTO dat_sistema_seg_rol_dat_funcionalidad_dat_accion (idaccion, idfuncionalidad, idrol, idsistema) VALUES (132, 10002, 10000000001, 1001);

INSERT INTO dat_sistema_compartimentacion (idsistema, iddominio) VALUES (1001, 1);

INSERT INTO dat_funcionalidad_compartimentacion (idfuncionalidad, iddominio) VALUES (10001, 1);
INSERT INTO dat_funcionalidad_compartimentacion (idfuncionalidad, iddominio) VALUES (10002, 1);

INSERT INTO dat_accion_compartimentacion(idaccion, iddominio) VALUES (126, 1);
INSERT INTO dat_accion_compartimentacion(idaccion, iddominio) VALUES (127, 1);
INSERT INTO dat_accion_compartimentacion(idaccion, iddominio) VALUES (128, 1);
INSERT INTO dat_accion_compartimentacion(idaccion, iddominio) VALUES (129, 1);
INSERT INTO dat_accion_compartimentacion(idaccion, iddominio) VALUES (130, 1);
INSERT INTO dat_accion_compartimentacion(idaccion, iddominio) VALUES (131, 1);
INSERT INTO dat_accion_compartimentacion(idaccion, iddominio) VALUES (132, 1);
INSERT INTO dat_accion_compartimentacion(idaccion, iddominio) VALUES (133, 1);
INSERT INTO dat_accion_compartimentacion(idaccion, iddominio) VALUES (166, 1);
INSERT INTO dat_accion_compartimentacion(idaccion, iddominio) VALUES (167, 1);
INSERT INTO dat_accion_compartimentacion(idaccion, iddominio) VALUES (168, 1);
INSERT INTO dat_accion_compartimentacion(idaccion, iddominio) VALUES (169, 1);




--fin de la transaccion
COMMIT;
