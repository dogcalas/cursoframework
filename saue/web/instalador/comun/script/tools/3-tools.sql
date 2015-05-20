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
VALUES ('V7.0.0', 'V7.0.0','mod_generacionscript',null,'I','D','I');
---------------------------------------------------------------------------------------------------------------------------
--Datos del esquema mod_generacionscript
---------------------------------------------------------------------------------------------------------------------------	
	SET search_path = mod_generacionscript, pg_catalog;

INSERT INTO nom_tiposscript(id_tiposcript, nombre, descripcion) VALUES (1, 'Rol', 'Script donde se crean los roles.');
INSERT INTO nom_tiposscript(id_tiposcript, nombre, descripcion) VALUES (2, 'Estructura', 'Script donde se crea la estructura del esquema.');
INSERT INTO nom_tiposscript(id_tiposcript, nombre, descripcion) VALUES (3, 'Datos', 'Script donde se carga los datos iniciales.');
INSERT INTO nom_tiposscript(id_tiposcript, nombre, descripcion) VALUES (4, 'Permisos', 'Script donde se asignan los permisos a los roles antes creados.');

----------------------------------------
-- registro de funcionalidades y permisos en mod_seguridad --
SET search_path = mod_seguridad, pg_catalog;

INSERT INTO dat_sistema (idsistema, idpadre, denominacion, icono, abreviatura, descripcion, externa, lft, rgt, iddominio) VALUES (10001, 10001, 'Herramientas', 'herramientas', 'herr', '', '', 17, 24, 1);
INSERT INTO dat_sistema (idsistema, idpadre, denominacion, icono, abreviatura, descripcion, externa, lft, rgt, iddominio) VALUES (10002, 10001, 'Validaciones', '', 'valid', '', '', 18, 19, 1);
INSERT INTO dat_sistema (idsistema, idpadre, denominacion, icono, abreviatura, descripcion, externa, lft, rgt, iddominio) VALUES (10004, 10001, 'Plataforma de integración', '', 'pint', '', '', 20, 21, 1);
INSERT INTO dat_sistema (idsistema, idpadre, denominacion, icono, abreviatura, descripcion, externa, lft, rgt, iddominio) VALUES (10005, 10001, 'Generador de scripts', '', 'genscripts', '', '', 22, 23, 1);

INSERT INTO dat_funcionalidad (idfuncionalidad, index, referencia, denominacion, descripcion, icono, idsistema, iddominio) VALUES (100001, 0, '/herramientas/doctrine_generator/index.php/main/main', 'Mapeador de Doctrine', 'Herramienta para el mapeo de tablas de bases de datos a clases del negocio utilizando Doctrine', 'falta', 10001, 1);
INSERT INTO dat_funcionalidad (idfuncionalidad, index, referencia, denominacion, descripcion, icono, idsistema, iddominio) VALUES (100002, 0, '/herramientas/validaciones/index.php/gestvalidaciondatos/gestvalidaciondatos', 'Gestionar validaciones de datos', '', 'falta', 10002, 1);
INSERT INTO dat_funcionalidad (idfuncionalidad, index, referencia, denominacion, descripcion, icono, idsistema, iddominio) VALUES (100003, 0, '/herramientas/validaciones/index.php/gestvalidacionnegocio/gestvalidacionnegocio', 'Gestionar validaciones de negocio', '', 'falta', 10002, 1);
INSERT INTO dat_funcionalidad (idfuncionalidad, index, referencia, denominacion, descripcion, icono, idsistema, iddominio) VALUES (100004, 0, '/herramientas/wsb/index.php/gestdatatype/gestdatatype', 'Gestionar tipos de datos', '', 'falta', 10004, 1);
INSERT INTO dat_funcionalidad (idfuncionalidad, index, referencia, denominacion, descripcion, icono, idsistema, iddominio) VALUES (100005, 0, '/herramientas/wsb/index.php/gestwebservice/gestwebservice', 'Gestionar servicios web', '', 'falta', 10004, 1);
INSERT INTO dat_funcionalidad (idfuncionalidad, index, referencia, denominacion, descripcion, icono, idsistema, iddominio) VALUES (100006, 0, '/herramientas/excepciones/index.php/gestexcepcion/gestexcepcion', 'Gestionar excepciones', '', 'falta', 10001, 1);
INSERT INTO dat_funcionalidad (idfuncionalidad, index, referencia, denominacion, descripcion, icono, idsistema, iddominio) VALUES (100007, 0, '/herramientas/ioc/index.php/gestservprest/gestservprest', 'Gestionar IoC', '', 'falta', 10001, 1);
INSERT INTO dat_funcionalidad (idfuncionalidad, index, referencia, denominacion, descripcion, icono, idsistema, iddominio) VALUES (100008, 0, '/herramientas/component/index.php/component/component','Gestionar componentes','','',10001,1);
INSERT INTO dat_funcionalidad (idfuncionalidad, index, referencia, denominacion, descripcion, icono, idsistema, iddominio) VALUES (100009, 0, '/herramientas/gdm/index.php/gestionarcarpetas/gestionarcarpetas','Generador de módulos','','',10001,1);
INSERT INTO dat_funcionalidad (idfuncionalidad, index, referencia, denominacion, descripcion, icono, idsistema, iddominio) VALUES (100010, 0, '/herramientas/genscripts/index.php/gestGenScripts/gestGenScripts','Generador de scripts','','',10005,1);
INSERT INTO dat_funcionalidad (idfuncionalidad, index, referencia, denominacion, descripcion, icono, idsistema, iddominio) VALUES (100011, 0, '/herramientas/genscripts/index.php/gestCatScript/gestCatScript','Catálogo de scripts','','',10005,1);


INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio)VALUES (1200001,'icon','listarBundles','','listarBundles',100008,1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio)VALUES (1200002,'','insertarbundle','','btnAdicionar',100008,1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio)VALUES (1200003,'','modificarbundle','','btnModificar',100008,1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio)VALUES (1200004,'','eliminarBundle','','btnEliminar',100008,1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio)VALUES (1100213, 'icon', 'main', 'Principal', 'main', 100001, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio)VALUES (1100214, 'icon', 'loaddbms', 'Cargar bases de datos', 'dbms', 100001, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio)VALUES (1100215, 'icon', 'loadversion', 'Cargar versiones', 'version', 100001, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio)VALUES (1100216, 'icon', 'addtable', 'Adicionar Tabla', 'table', 100001, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio)VALUES (1100217, 'icon','addrelations', 'Adicionar relaciones', 'relations', 100001, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio)VALUES (1100218, 'icon','revertrelation', 'Deshacer relación', 'relation', 100001, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio)VALUES (1100219, 'icon','createproject', 'Crear proyecto', 'project', 100001, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio)VALUES (1100220, 'icon','open', 'Abrir proyecto', 'open', 100001, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio)VALUES (1100221, 'icon','download', 'Descargar', 'down', 100001, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio)VALUES (1100222, 'icon','connect', 'Conectar', 'connect', 100001, 1);

INSERT INTO dat_sistema_seg_rol (idrol, idsistema) VALUES (10000000001,10001);
INSERT INTO dat_sistema_seg_rol (idrol, idsistema) VALUES (10000000001,10002);
INSERT INTO dat_sistema_seg_rol (idrol, idsistema) VALUES (10000000001,10004);

INSERT INTO dat_sistema_seg_rol_dat_funcionalidad (idfuncionalidad, idrol, idsistema) VALUES (100001, 10000000001, 10001);
INSERT INTO dat_sistema_seg_rol_dat_funcionalidad (idfuncionalidad, idrol, idsistema) VALUES (100006, 10000000001, 10001);
INSERT INTO dat_sistema_seg_rol_dat_funcionalidad (idfuncionalidad, idrol, idsistema) VALUES (100007, 10000000001, 10001);
INSERT INTO dat_sistema_seg_rol_dat_funcionalidad (idfuncionalidad, idrol, idsistema) VALUES (100008, 10000000001, 10001);
INSERT INTO dat_sistema_seg_rol_dat_funcionalidad (idfuncionalidad, idrol, idsistema) VALUES (100009, 10000000001, 10001);
INSERT INTO dat_sistema_seg_rol_dat_funcionalidad (idfuncionalidad, idrol, idsistema) VALUES (100002, 10000000001, 10002);
INSERT INTO dat_sistema_seg_rol_dat_funcionalidad (idfuncionalidad, idrol, idsistema) VALUES (100003, 10000000001, 10002);
INSERT INTO dat_sistema_seg_rol_dat_funcionalidad (idfuncionalidad, idrol, idsistema) VALUES (100004, 10000000001, 10004);
INSERT INTO dat_sistema_seg_rol_dat_funcionalidad (idfuncionalidad, idrol, idsistema) VALUES (100005, 10000000001, 10004);

INSERT INTO dat_sistema_seg_rol_dat_funcionalidad_dat_accion (idaccion, idfuncionalidad, idrol, idsistema) VALUES (1100213, 100001, 10000000001, 10001);
INSERT INTO dat_sistema_seg_rol_dat_funcionalidad_dat_accion (idaccion, idfuncionalidad, idrol, idsistema) VALUES (1100214, 100001, 10000000001, 10001);
INSERT INTO dat_sistema_seg_rol_dat_funcionalidad_dat_accion (idaccion, idfuncionalidad, idrol, idsistema) VALUES (1100215, 100001, 10000000001, 10001);
INSERT INTO dat_sistema_seg_rol_dat_funcionalidad_dat_accion (idaccion, idfuncionalidad, idrol, idsistema) VALUES (1100216, 100001, 10000000001, 10001);
INSERT INTO dat_sistema_seg_rol_dat_funcionalidad_dat_accion (idaccion, idfuncionalidad, idrol, idsistema) VALUES (1100217, 100001, 10000000001, 10001);
INSERT INTO dat_sistema_seg_rol_dat_funcionalidad_dat_accion (idaccion, idfuncionalidad, idrol, idsistema) VALUES (1100218, 100001, 10000000001, 10001);
INSERT INTO dat_sistema_seg_rol_dat_funcionalidad_dat_accion (idaccion, idfuncionalidad, idrol, idsistema) VALUES (1100219, 100001, 10000000001, 10001);
INSERT INTO dat_sistema_seg_rol_dat_funcionalidad_dat_accion (idaccion, idfuncionalidad, idrol, idsistema) VALUES (1100220, 100001, 10000000001, 10001);
INSERT INTO dat_sistema_seg_rol_dat_funcionalidad_dat_accion (idaccion, idfuncionalidad, idrol, idsistema) VALUES (1100221, 100001, 10000000001, 10001);
INSERT INTO dat_sistema_seg_rol_dat_funcionalidad_dat_accion (idaccion, idfuncionalidad, idrol, idsistema) VALUES (1100222, 100001, 10000000001, 10001);
INSERT INTO dat_sistema_seg_rol_dat_funcionalidad_dat_accion (idaccion, idfuncionalidad, idrol, idsistema) VALUES (1200001, 100008, 10000000001, 10001);
INSERT INTO dat_sistema_seg_rol_dat_funcionalidad_dat_accion (idaccion, idfuncionalidad, idrol, idsistema) VALUES (1200002, 100008, 10000000001, 10001);
INSERT INTO dat_sistema_seg_rol_dat_funcionalidad_dat_accion (idaccion, idfuncionalidad, idrol, idsistema) VALUES (1200003, 100008, 10000000001, 10001);
INSERT INTO dat_sistema_seg_rol_dat_funcionalidad_dat_accion (idaccion, idfuncionalidad, idrol, idsistema) VALUES (1200004, 100008, 10000000001, 10001);

INSERT INTO dat_sistema_compartimentacion (idsistema, iddominio) VALUES (10001, 1);
INSERT INTO dat_sistema_compartimentacion (idsistema, iddominio) VALUES (10002, 1);
INSERT INTO dat_sistema_compartimentacion (idsistema, iddominio) VALUES (10004, 1);

INSERT INTO dat_funcionalidad_compartimentacion (idfuncionalidad, iddominio) VALUES (100001, 1);
INSERT INTO dat_funcionalidad_compartimentacion (idfuncionalidad, iddominio) VALUES (100002, 1);
INSERT INTO dat_funcionalidad_compartimentacion (idfuncionalidad, iddominio) VALUES (100003, 1);
INSERT INTO dat_funcionalidad_compartimentacion (idfuncionalidad, iddominio) VALUES (100004, 1);
INSERT INTO dat_funcionalidad_compartimentacion (idfuncionalidad, iddominio) VALUES (100005, 1);
INSERT INTO dat_funcionalidad_compartimentacion (idfuncionalidad, iddominio) VALUES (100006, 1);
INSERT INTO dat_funcionalidad_compartimentacion (idfuncionalidad, iddominio) VALUES (100007, 1);
INSERT INTO dat_funcionalidad_compartimentacion (idfuncionalidad, iddominio) VALUES (100008, 1);
INSERT INTO dat_funcionalidad_compartimentacion (idfuncionalidad, iddominio) VALUES (100009, 1);

INSERT INTO dat_accion_compartimentacion(idaccion, iddominio) VALUES (1100213, 1);
INSERT INTO dat_accion_compartimentacion(idaccion, iddominio) VALUES (1100214, 1);
INSERT INTO dat_accion_compartimentacion(idaccion, iddominio) VALUES (1100215, 1);
INSERT INTO dat_accion_compartimentacion(idaccion, iddominio) VALUES (1100216, 1);
INSERT INTO dat_accion_compartimentacion(idaccion, iddominio) VALUES (1100217, 1);
INSERT INTO dat_accion_compartimentacion(idaccion, iddominio) VALUES (1100218, 1);
INSERT INTO dat_accion_compartimentacion(idaccion, iddominio) VALUES (1100219, 1);
INSERT INTO dat_accion_compartimentacion(idaccion, iddominio) VALUES (1100220, 1);
INSERT INTO dat_accion_compartimentacion(idaccion, iddominio) VALUES (1100221, 1);
INSERT INTO dat_accion_compartimentacion(idaccion, iddominio) VALUES (1100222, 1);
INSERT INTO dat_accion_compartimentacion(idaccion, iddominio) VALUES (1100223, 1);
INSERT INTO dat_accion_compartimentacion(idaccion, iddominio) VALUES (1200001, 1);
INSERT INTO dat_accion_compartimentacion(idaccion, iddominio) VALUES (1200002, 1);
INSERT INTO dat_accion_compartimentacion(idaccion, iddominio) VALUES (1200003, 1);
INSERT INTO dat_accion_compartimentacion(idaccion, iddominio) VALUES (1200004, 1);


--fin de la transaccion
COMMIT;
