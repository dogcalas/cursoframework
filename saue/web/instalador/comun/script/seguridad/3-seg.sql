--------V7.0.0-----------------------
/*
--CENTRO DE SOLUCIONES DE GESTIÓN		----
--Departamento de tecnología			----
--2014										----
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
VALUES ('V7.0.0', 'V7.0.0','mod_seguridad',null,'I','D','I');
---------------------------------------------------------------------------------------------------------------------------
--Datos del esquema mod_Seguridad
----------------------------------------------------------------------------------------------------------------------------------
	SET search_path = mod_seguridad, pg_catalog;
---------------------------------------------------------------------
INSERT INTO seg_rol VALUES (10000000001, 'instalacion', 'Rol de instalación', 'INS');
----------------------------------------------------------------------
INSERT INTO nom_desktop (iddesktop, denominacion, abreviatura, descripcion) VALUES (100000001, 'Árbol vertical', 'standardarbol', 'Portal que muestra el menú en forma de árbol.');
INSERT INTO nom_desktop (iddesktop, denominacion, abreviatura, descripcion) VALUES (100000002, 'Estilo windows', 'desktopaction', 'Portal en forma de escritorio que muestra el menú hasta el nivel de funcionalidades.');
----------------------------------------------------------------------
INSERT INTO nom_idioma (ididioma, denominacion, abreviatura) VALUES (100000001, 'Español', 'es');
INSERT INTO nom_idioma (ididioma, denominacion, abreviatura) VALUES (100000002, 'Inglés', 'en');
----------------------------------------------------------------------
INSERT INTO nom_tema (idtema, denominacion, abreviatura, descripcion) VALUES (100000001, 'personal4', 'personal4', 'personal4');
----------------------------------------------------------------------
INSERT INTO seg_usuario (idusuario, idcargo, idarea, identidad, nombreusuario, contrasenna, contrasenabd, ip, iddominio, iddesktop, idtema, ididioma, estado, perfilxml, accesodirecto) VALUES (1000000001, 0, 0, 100000001, 'instalacion', 'cb58ceb169fa69a98b7d60a820b0b37c', 'cb58ceb169fa69a98b7d60a820b0b37c', '0.0.0.0/0', 1, 100000002, 100000001, 100000001, '0', '', '');
----------------------------------------------------------------------
INSERT INTO dat_entidad_seg_usuario_seg_rol (idusuario, identidad, idrol) VALUES (1000000001, 100000001, 10000000001);
----------------------------------------------------------------------
INSERT INTO nom_expresiones VALUES (1, 'letras', '/^([a-zA-Z]+ ?[a-zA-Z]*)+$/', '');
----------------------------------------------------------------------
INSERT INTO seg_restricclaveacceso VALUES (100, 20, true, true, false, 8, 24);
----------------------------------------------------------------------
INSERT INTO dat_sistema (idsistema, idpadre, denominacion, icono, abreviatura, descripcion, externa, lft, rgt, iddominio) VALUES (2, 2, 'Seguridad', 'seguridad', 'seg', '', '', 1, 12, 1);
INSERT INTO dat_sistema (idsistema, idpadre, denominacion, icono, abreviatura, descripcion, externa, lft, rgt, iddominio) VALUES (7, 2, 'Compartimentación', '', NULL, '', '', 2, 3, 1);
INSERT INTO dat_sistema (idsistema, idpadre, denominacion, icono, abreviatura, descripcion, externa, lft, rgt, iddominio) VALUES (3, 2, 'Configurar nomencladores', '', NULL, '', '', 4, 5, 1);
INSERT INTO dat_sistema (idsistema, idpadre, denominacion, icono, abreviatura, descripcion, externa, lft, rgt, iddominio) VALUES (4, 2, 'Configurar servidores', '', NULL, '', '', 6, 7, 1);
INSERT INTO dat_sistema (idsistema, idpadre, denominacion, icono, abreviatura, descripcion, externa, lft, rgt, iddominio) VALUES (5, 2, 'Configurar sistemas', '', NULL, '', '', 8, 9, 1);
INSERT INTO dat_sistema (idsistema, idpadre, denominacion, icono, abreviatura, descripcion, externa, lft, rgt, iddominio) VALUES (6, 2, 'Configurar usuarios', '', NULL, '', '', 10,11, 1);

----------------------------------------------------------------------
INSERT INTO dat_funcionalidad (idfuncionalidad, index, referencia, denominacion, descripcion, icono, idsistema, iddominio) VALUES (2, 0, 'seguridad/index.php/gestnomdominio/gestnomdominio', 'Dominio', '', 'falta', 3, 1);
INSERT INTO dat_funcionalidad (idfuncionalidad, index, referencia, denominacion, descripcion, icono, idsistema, iddominio) VALUES (3, 0, 'seguridad/index.php/gestnomgestor/gestnomgestor', 'Gestores de base de datos', '', 'falta', 3, 1);
INSERT INTO dat_funcionalidad (idfuncionalidad, index, referencia, denominacion, descripcion, icono, idsistema, iddominio) VALUES (4, 0, 'seguridad/index.php/gestnomidioma/gestnomidioma', 'Idiomas', '', 'falta', 3, 1);
INSERT INTO dat_funcionalidad (idfuncionalidad, index, referencia, denominacion, descripcion, icono, idsistema, iddominio) VALUES (5, 0, 'seguridad/index.php/gestexpresiones/gestexpresiones', 'Expresiones', '', 'falta', 3, 1);
INSERT INTO dat_funcionalidad (idfuncionalidad, index, referencia, denominacion, descripcion, icono, idsistema, iddominio) VALUES (6, 0, 'seguridad/index.php/gestnomtema/gestnomtema', 'Temas', '', 'falta', 3, 1);
INSERT INTO dat_funcionalidad (idfuncionalidad, index, referencia, denominacion, descripcion, icono, idsistema, iddominio) VALUES (7, 0, 'seguridad/index.php/gestnomdesktop/gestnomdesktop', 'Escritorios', '', 'falta', 3, 1);
INSERT INTO dat_funcionalidad (idfuncionalidad, index, referencia, denominacion, descripcion, icono, idsistema, iddominio) VALUES (8, 0, 'seguridad/index.php/configcont/configcont', 'Claves', '', 'falta', 3, 1);
INSERT INTO dat_funcionalidad (idfuncionalidad, index, referencia, denominacion, descripcion, icono, idsistema, iddominio) VALUES (9, 0, 'seguridad/index.php/gestservidor/gestservidor', 'Servidores', '', 'falta', 4, 1);
--INSERT INTO dat_funcionalidad (idfuncionalidad, index, referencia, denominacion, descripcion, icono, idsistema, iddominio) VALUES (10, 0, 'seguridad/index.php/gestgestor/gestgestor', 'Gestores de base de datos', '', 'falta', 4, 1);
--INSERT INTO dat_funcionalidad (idfuncionalidad, index, referencia, denominacion, descripcion, icono, idsistema, iddominio) VALUES (26,0,  'seguridad/index.php/gestrolesbd/gestrolesbd','Gestionar roles de base de datos','','',4,1);
INSERT INTO dat_funcionalidad (idfuncionalidad, index, referencia, denominacion, descripcion, icono, idsistema, iddominio) VALUES (11, 0, 'seguridad/index.php/gestsistema/gestsistema', 'Sistemas', '', 'falta', 5, 1);
INSERT INTO dat_funcionalidad (idfuncionalidad, index, referencia, denominacion, descripcion, icono, idsistema, iddominio) VALUES (12, 0, 'seguridad/index.php/gestfuncionalidad/gestfuncionalidad', 'Funcionalidades', '', 'falta', 5, 1);
INSERT INTO dat_funcionalidad (idfuncionalidad, index, referencia, denominacion, descripcion, icono, idsistema, iddominio) VALUES (13, 0, 'seguridad/index.php/gestaccion/gestaccion', 'Acciones', '', 'falta', 5, 1);
--INSERT INTO dat_funcionalidad (idfuncionalidad, index, referencia, denominacion, descripcion, icono, idsistema, iddominio) VALUES (14, 0, 'seguridad/index.php/gestservpresta/gestservpresta', 'Servicios que presta', '', 'falta', 5, 1);
--INSERT INTO dat_funcionalidad (idfuncionalidad, index, referencia, denominacion, descripcion, icono, idsistema, iddominio) VALUES (15, 0, 'seguridad/index.php/gestserviciocons/gestserviciocons', 'Servicios que consume', '', 'falta', 5, 1);
--INSERT INTO dat_funcionalidad (idfuncionalidad, index, referencia, denominacion, descripcion, icono, idsistema, iddominio) VALUES (16, 0, 'seguridad/index.php/gestdatfunciones/gestdatfunciones', 'Funciones', '', 'falta', 5, 1);
--INSERT INTO dat_funcionalidad (idfuncionalidad, index, referencia, denominacion, descripcion, icono, idsistema, iddominio) VALUES (17, 0, 'seguridad/index.php/gestdatparametros/gestdatparametros', 'Parámetros', '', 'falta', 5, 1);
--INSERT INTO dat_funcionalidad (idfuncionalidad, index, referencia, denominacion, descripcion, icono, idsistema, iddominio) VALUES (18, 0, 'seguridad/index.php/gestaccrep/gestaccrep', 'Acciones y reportes', 'Acciones y Reportes ', '', 5, 1);
INSERT INTO dat_funcionalidad (idfuncionalidad, index, referencia, denominacion, descripcion, icono, idsistema, iddominio) VALUES (19, 0, 'seguridad/index.php/gestrol/gestrol', 'Roles', '', 'falta', 6, 1);
INSERT INTO dat_funcionalidad (idfuncionalidad, index, referencia, denominacion, descripcion, icono, idsistema, iddominio) VALUES (20, 0, 'seguridad/index.php/gestusuario/gestusuario', 'Usuarios', '', 'falta', 6, 1);
INSERT INTO dat_funcionalidad (idfuncionalidad, index, referencia, denominacion, descripcion, icono, idsistema, iddominio) VALUES (21, 0, 'seguridad/index.php/datosusuario/datosusuario', 'Campos del perfil de usuario', '', 'falta', 6, 1);
INSERT INTO dat_funcionalidad (idfuncionalidad, index, referencia, denominacion, descripcion, icono, idsistema, iddominio) VALUES (22, 0, 'seguridad/index.php/gestperfil/gestperfil', 'Perfil de usuario', '', 'falta', 6, 1);
INSERT INTO dat_funcionalidad (idfuncionalidad, index, referencia, denominacion, descripcion, icono, idsistema, iddominio) VALUES (23, 0, 'seguridad/index.php/gestcompartimentacionsistema/gestcompartimentacionsistema', 'Compartimentar sistemas', '', 'falta', 7, 1);
INSERT INTO dat_funcionalidad (idfuncionalidad, index, referencia, denominacion, descripcion, icono, idsistema, iddominio) VALUES (24, 0, 'seguridad/index.php/gestusuariodominio/gestusuariodominio', 'Compartimentar dominio', '', 'falta', 7, 1);
INSERT INTO dat_funcionalidad (idfuncionalidad, index, referencia, denominacion, descripcion, icono, idsistema, iddominio) VALUES (25, 0, 'seguridad/index.php/gestroldominio/gestroldominio', 'Compartimentar rol', '', 'falta', 7, 1);
--INSERT INTO dat_funcionalidad (idfuncionalidad, index, referencia, denominacion, descripcion, icono, idsistema, iddominio) VALUES (120002,0,'seguridad/index.php/gestnomobjeto/gestnomobjeto','Objetos de base de datos','','falta',3,1);
--INSERT INTO dat_funcionalidad (idfuncionalidad, index, referencia, denominacion, descripcion, icono, idsistema, iddominio) VALUES (120003,0,'seguridad/index.php/gestnomconexion/gestnomconexion','Gestionar tipo de conexión','Nomenclador de Objetos de Base de Datos','falta',3,1);
--INSERT INTO dat_funcionalidad (idfuncionalidad, index, referencia, denominacion, descripcion, icono, idsistema, iddominio) VALUES (120004,0,'seguridad/index.php/gestnomrelacion/gestnomrelacion','Relación entre servicios y objetos de base de datos','','falta',3,1);
--INSERT INTO dat_funcionalidad (idfuncionalidad, index, referencia, denominacion, descripcion, icono, idsistema, iddominio) VALUES (120005,0,'seguridad/index.php/gestcompartimentacionrecursos/gestcompartimentacionrecursos','Compartimentar información','','falta',7,1);

----------------------------------------------------------------------

INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio)VALUES (2, NULL, 'insertarnomdominio', 'Adicionar dominio', 'btnAgr', 2, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio)VALUES (3, NULL, 'modificarnomdominio', 'Modificar dominio', 'btnMod', 2, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio)VALUES (4, NULL, 'eliminarnomdominio', 'Eliminar dominio', 'btnEli', 2, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio)VALUES (123, 'icon', 'cargardominios', '', 'griddominios', 2, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio)VALUES (179, 'icon', 'cargardominios', '', 'cargardominios', 2, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio)VALUES (180, 'icon', 'modificarclave', '', 'modificarclave', 2, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio)VALUES (181, 'icon', 'cargarestructuras', '', 'cargarestructuras', 2, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio)VALUES (8, NULL, 'insertargestor', 'Adicionar gestor', 'btnAgrGest', 3, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio)VALUES (9, NULL, 'modificaromgestor', 'Modificar gestor', 'btnModGest', 3, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio)VALUES (10, NULL, 'eliminargestor', 'Eliminar gestor', 'btnEliGest', 3, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio)VALUES (124, 'icon', 'cargarnomgestores', '', 'cargarnomgestores', 3, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio)VALUES (12, NULL, 'insertarnomidioma', 'Adicionar idioma', 'btnAgrIdioma', 4, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio)VALUES (13, NULL, 'modificarnomidioma', 'Modificar idioma', 'btnModIdioma', 4, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio)VALUES (14, NULL, 'eliminarnomidioma', 'Eliminar idioma', 'btnEliIdioma', 4, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio)VALUES (125, 'icon', 'cargarnomidioma', '', 'cargarnomidioma', 4, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio)VALUES (19, NULL, 'cargarexpresiones', '', 'cargarexpresiones', 5, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio)VALUES (16, NULL, 'insertarexpresion', 'Adicionar expreciones', 'btnAgrExpre', 5, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio)VALUES (17, NULL, 'modificarexpresion', 'Modificar expreciones', 'btnModExpre', 5, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio)VALUES (18, NULL, 'eliminarexpresion', 'Eliminar expreciones', 'btnEliExpre', 5, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio)VALUES (23, NULL, 'cargarnomtema', '', 'cargarnomtema', 6, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio)VALUES (20, NULL, 'insertartema', 'Adicionar tema', 'btnAgrTema', 6, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio)VALUES (21, NULL, 'modificartema', 'Modificar tema', 'btnModTema', 6, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio)VALUES (22, NULL, 'eliminarnomtema', 'Eliminar tema', 'btnEliTema', 6, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio)VALUES (27, NULL, 'cargarnomdesktop', '', 'cargarnomdesktop', 7, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio)VALUES (24, NULL, 'insertarnomdesktop', 'Adicionar escritorio', 'btnAgrDesktop', 7, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio)VALUES (25, NULL, 'modificarnomdesktop', 'Modificar escritorio', 'btnModDesktop', 7, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio)VALUES (26, NULL, 'eliminarnomdesktop', 'Eliminar escritorio', 'btnEliDesktop', 7, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio)VALUES (30, NULL, 'cargarclaves', '', 'cargarclaves', 8, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio)VALUES (28, NULL, 'insertarclaveA', 'Adicionar claves', 'btnAgrClaves', 8, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio)VALUES (29, NULL, 'modificarclaven', 'Modificar claves', 'btnModClaves', 8, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio)VALUES (31, NULL, 'insertarserv', 'Adicionar servidor', 'btnAgrServ', 9, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio)VALUES (32, NULL, 'modificarserv', 'Modificar servidor', 'btnModServ', 9, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio)VALUES (33, NULL, 'comprobarservidor', 'Eliminar servidor', 'btnEliServ', 9, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio)VALUES (112, 'icon', 'cargarservidores', 'Carga el grid de servidores', 'gridservidores', 9, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio)VALUES (39, NULL, 'modificarsistema', 'Modificar sistema', 'btnModSist', 11, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio)VALUES (40, NULL, 'eliminarsistema', 'Eliminar sistema', 'btnEliSist', 11, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio)VALUES (41, NULL, 'exportarsistema', 'Exportar sistema', 'btnExpSist', 11, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio)VALUES (42, NULL, 'importarXML', 'Importar sistema', 'btnImpSist', 11, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio)VALUES (38, NULL, 'insertarsistema', 'Adicionar sistema', 'btnAgrSist', 11, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio)VALUES (101, 'icon', 'cargarsistema', 'carga el grid de los sistemas', 'gridsistema', 11, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio)VALUES (102, 'icon', 'cargarservidores', 'carga el arbol de servidores, gestores, bases de datos y esquemas', 'treeservidores', 11, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio)VALUES (47, NULL, 'insertarfuncionalidad', 'Adicionar funcionalidad', 'btnAgrFunc', 12, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio)VALUES (44, NULL, 'modificarfuncionalidad', 'Modificar funcionalidad', 'btnModFunc', 12, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio)VALUES (45, NULL, 'eliminarfuncionalidad', 'Eliminar funcionalidad', 'btnEliFunc', 12, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio)VALUES (103, 'icon', 'cargarsistema', 'Carga el grid sistemas registrados', 'gridsistema', 12, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio)VALUES (104, 'icon', 'cargarfuncionalidades', 'Carga el grid de funcionalidades', 'gridfuncionalidades', 12, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio)VALUES (48, NULL, 'insertaraccion', 'Adicionar acción', 'btnAgrAccion', 13, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio)VALUES (49, NULL, 'modificaraccion', 'Modificar acción', 'btnModAccion', 13, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio)VALUES (50, NULL, 'elos/gestvalidaciondatosiminaraccion', 'Eliminar acción', 'btnEliAccion', 13, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio)VALUES (1100223, 'icon','buscarAccionesControlador', 'buscar acciones asociadas al controlador', 'acciones', 13, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio)VALUES (1200011,'icon','getcriterioSeleccion','getcriterioSeleccion','gcriSel',13,1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio)VALUES (1200012,'icon','configridObjetos','configridObjetos','congobj',13,1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio)VALUES (1200013,'icon','cargargridObjetos','cargargridObjetos','cggibj',13,1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio)VALUES (1200014,'icon','modificarPermisos','modificarPermisos','modp',13,1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio)VALUES (1200015,'icon','mostrarMensaje','mostrarMensaje','mMsg',13,1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio)VALUES (105, 'icon', 'cargarsistfunc', 'Carga el grid de sistemas registrados', 'gridsistemas', 13, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio)VALUES (106, 'icon', 'cargargridacciones', 'Carga el grid de acciones', 'gridacciones', 13, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio)VALUES (67, NULL, 'insertarrol', 'Adicionar rol', 'btnAgrRol', 19, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio)VALUES (68, NULL, 'modificarrol', 'Modificar rol', 'btnModRol', 19, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio)VALUES (69, NULL, 'eliminarrol', 'Eliminar rol', 'btnEliRol', 19, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio)VALUES (70, NULL, 'cargarsistemafunc', 'Regular acciones del rol', 'btnRestRol', 19, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio)VALUES (115, 'icon', 'cargarsistemafuncionalidades', '', 'treefuncionalidades', 19, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio)VALUES (116, 'icon', 'cargaraccionesquetiene', '', 'accquetiene', 19, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio)VALUES (117, 'icon', 'cargaraccionesquenotiene', '', 'accquenotiene', 19, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio)VALUES (170, 'icon', 'cargarrol', '', 'cargarrol', 19, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio)VALUES (171, 'icon', 'adicionaraccion', '', 'adicionaraccion', 19, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio)VALUES (172, 'icon', 'eliminaraccion', '', 'eliminaraccion', 19, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio)VALUES (72, NULL, 'insertarusuario', 'Adicionar usuario', 'btnAgrUser', 20, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio)VALUES (73, NULL, 'modificarusuario', 'Modificar usuario', 'btnModUser', 20, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio)VALUES (74, NULL, 'eliminarusuario', 'Eliminar usuario', 'btnEliUser', 20, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio)VALUES (75, NULL, 'asignarroles', 'Adicionar roles de usuario', 'btnAgrUserRol', 20, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio)VALUES (76, NULL, 'cambiarpassword', 'Cambiar contraseña de usuario', 'btnContUser', 20, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio)VALUES (85, NULL, 'ActivarUsuario', 'btnActivarUser', 'btnActivarUser', 20, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio)VALUES (86, NULL, 'DesactivarUsuario', 'btnDesctivarUser', 'btnDesctivarUser', 20, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio)VALUES (118, 'icon', 'cargarusuario', '', 'griduser', 20, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio)VALUES (119, 'icon', 'cargarroles', '', 'treeroles', 20, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio)VALUES (120, 'icon', 'cargarentidades', '', 'treeentidades', 20, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio)VALUES (173, 'icon', 'cargarcombodominio', '', 'cargarcombodominio', 20, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio)VALUES (174, 'icon', 'cargarComboDominioBuscar', '', 'cargarComboDominioBuscar', 20, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio)VALUES (175, 'icon', 'cargarcombodesktop', '', 'cargarcombodesktop', 20, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio)VALUES (176, 'icon', 'cargarcomboidioma', '', 'cargarcomboidioma', 20, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio)VALUES (177, 'icon', 'cargarcombotema', '', 'cargarcombotema', 20, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio)VALUES (178, 'icon', 'cargarcomboservidoresaut', '', 'cargarcomboservidoresaut', 20, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio)VALUES (182, NULL, 'cargarestructura', '', 'cargarestructura', 20, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio)VALUES (183, NULL, 'cargarareas', '', 'cargarareas', 20, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio)VALUES (184, NULL, 'cargarcargos', '', 'cargarcargos', 20, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio)VALUES (1100224, 'icon', 'cargarUsuariosConectados', 'Carga los usuarios conectados', 'cargarUsuariosConectados', 20, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio)VALUES (1100225, 'icon', 'cerrarSession', 'Cierra la sesión de un usuarios', 'btnCerrarSes', 20, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio)VALUES (78, NULL, 'insertarexpresion', 'Adicionar campos del perfil', 'btnAgrCampPerf', 21, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio)VALUES (79, NULL, 'modificarexpresion', 'Modificar campos del perfil', 'btnModCampPerf', 21, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio)VALUES (80, NULL, 'eliminarexpresion', 'Eliminar campos del perfil', 'btnEliCampPerf', 21, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio)VALUES (121, 'icon', 'cargarexpresiones', '', 'gridexpresiones', 21, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio)VALUES (82, NULL, 'insertarperfil', 'Adicionar perfil', 'btnAddPerfil', 22, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio)VALUES (83, NULL, 'modificarperfil', 'Modificar perfil', 'btnModPerfil', 22, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio)VALUES (122, 'icon', 'cargarcampos', '', 'gridcampos', 22, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio)VALUES (185, NULL, 'cargarArbolDominios', '', 'cargarArbolDominios', 23, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio)VALUES (186, NULL, 'cargarSistFuncAcc', '', 'cargarSistFuncAcc', 23, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio)VALUES (187, NULL, 'insertarCompartimentacionSistFuncAcc', '', 'insertarCompartimentacionSistFuncAcc', 23, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio)VALUES (188, NULL, 'insertarUsuariosDominios', '', 'insertarUsuariosDominios', 24, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio)VALUES (189, NULL, 'cargarArbolDominios', '', 'cargarArbolDominios', 24, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio)VALUES (190, NULL, 'cargarusuariodominio', '', 'cargarusuariodominio', 24, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio)VALUES (191, NULL, 'insertarRolesDominios', '', 'insertarRolesDominios', 25, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio)VALUES (192, NULL, 'cargarArbolDominios', '', 'cargarArbolDominios', 25, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio)VALUES (193, NULL, 'cargarRolDominio', '', 'cargarRolDominio', 25, 1);


--INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio)VALUES (35, NULL, 'insertargestorservidor', 'Adicionar gestor de BD', 'btnAgrgestorBd', 10, 1);
--INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio)VALUES (36, NULL, 'comprobargestores', 'Eliminar  gestor de BD', 'btnEligestorBd', 10, 1);
--INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio)VALUES (110, 'icon', 'cargargestores', 'Carga el grid de gestores', 'gridgestores', 10, 1);
--INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio)VALUES (111, 'icon', 'cargarservidores', 'Carga el grid de servidores', 'gridservidores', 10, 1);
--INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio)VALUES (88, NULL, 'modificarRolBaseDato', '', 'btnModBd', 26, 1);
--INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio)VALUES (89, NULL, 'eliminarRolesDB', '', 'btnEliBd', 26, 1);
--INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio)VALUES (87, NULL, 'insertarRolBaseDato', '', 'btnAgrBd', 26, 1);
--INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio)VALUES (107, 'icon', 'cargarsistfunc', 'Carga el arbol de sistemas', 'gridsistemas', 18, 1);
--INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio)VALUES (108, 'icon', 'cargargridacciones', 'Carga el grid de acciones', 'gridacciones', 18, 1);
--INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio)VALUES (109, 'icon', 'reportesasociadosarep', 'Carga el grid de reportes', 'gridreportes', 18, 1);
--INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio)VALUES (113, 'icon', 'cargarservidores', '', 'gridservidores', 26, 1);
--INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio)VALUES (114, 'icon', 'cargarRolesBD', '', 'gridroles', 26, 1);
--INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio)VALUES (1200005,'icon','gestnomobjeto','gestnomobjeto','gno',120002,1);
--INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio)VALUES (1200006,'icon','insertarnomobjeto','gestnomobjeto','ino',120002,1);
--INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio)VALUES (1200007,'icon','modificaromobjeto','gestnomobjeto','mno',120002,1);
--INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio)VALUES (1200008,'icon','eliminarobjeto','gestnomobjeto','eo',120002,1);
--INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio)VALUES (1200009,'icon','cargarnomobjetos','gestnomobjeto','cno',120002,1);
--INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio)VALUES (1200010,'icon','getObjetosBd','gestnomobjeto','gObB',120002,1);
--INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio)VALUES (1200016,'icon','gestnomconexion','gestnomconexion','gnc',120003,1);
--INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio)VALUES (1200017,'icon','CargarGridTipoConexiones','CargarGridTipoConexiones','cgtc',120003,1);
--INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio)VALUES (1200018,'icon','cargarCombo','cargarCombo','cc',120003,1);
--INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio)VALUES (1200020,'icon','InsertarConexion','InsertarConexion','insC',120003,1);
--INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio)VALUES (1200021,'icon','EliminarConexion','EliminarConexion','elimC',120003,1);
--INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio)VALUES (1200022,'icon','ModificarTipoConexion','ModificarTipoConexion','modTC',120003,1);
--INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio)VALUES (1200023,'icon','Gestnomrelacion','Gestnomrelacion','nr',120004,1);
--INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio)VALUES (1200024,'icon','cargarServicios','cargarServicios','cs',120004,1);
--INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio)VALUES (1200025,'icon','getcriterioSeleccion','getcriterioSeleccion','gcs',120004,1);
--INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio)VALUES (1200026,'icon','configridObjetos','configridObjetos','cgo',120004,1);
--INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio)VALUES (1200027,'icon','cargargridObjetos','cargargridObjetos','cggo',120004,1);
--INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio)VALUES (1200028,'icon','modificarPermisos','modificarPermisos','mp',120004,1);

-------------------------------------------------------

INSERT INTO dat_sistema_seg_rol (idrol, idsistema) VALUES (10000000001, 2);
INSERT INTO dat_sistema_seg_rol (idrol, idsistema) VALUES (10000000001, 3);
INSERT INTO dat_sistema_seg_rol (idrol, idsistema) VALUES (10000000001, 4);
INSERT INTO dat_sistema_seg_rol (idrol, idsistema) VALUES (10000000001, 5);
INSERT INTO dat_sistema_seg_rol (idrol, idsistema) VALUES (10000000001, 6);
INSERT INTO dat_sistema_seg_rol (idrol, idsistema) VALUES (10000000001, 7);

----------------------------------------------------------------------

INSERT INTO dat_sistema_seg_rol_dat_funcionalidad (idfuncionalidad, idrol, idsistema) VALUES (2, 10000000001, 3);
INSERT INTO dat_sistema_seg_rol_dat_funcionalidad (idfuncionalidad, idrol, idsistema) VALUES (3, 10000000001, 3);
INSERT INTO dat_sistema_seg_rol_dat_funcionalidad (idfuncionalidad, idrol, idsistema) VALUES (4, 10000000001, 3);
INSERT INTO dat_sistema_seg_rol_dat_funcionalidad (idfuncionalidad, idrol, idsistema) VALUES (5, 10000000001, 3);
INSERT INTO dat_sistema_seg_rol_dat_funcionalidad (idfuncionalidad, idrol, idsistema) VALUES (6, 10000000001, 3);
INSERT INTO dat_sistema_seg_rol_dat_funcionalidad (idfuncionalidad, idrol, idsistema) VALUES (7, 10000000001, 3);
INSERT INTO dat_sistema_seg_rol_dat_funcionalidad (idfuncionalidad, idrol, idsistema) VALUES (8, 10000000001, 3);
INSERT INTO dat_sistema_seg_rol_dat_funcionalidad (idfuncionalidad, idrol, idsistema) VALUES (9, 10000000001, 4);
--INSERT INTO dat_sistema_seg_rol_dat_funcionalidad (idfuncionalidad, idrol, idsistema) VALUES (10, 10000000001, 4);
INSERT INTO dat_sistema_seg_rol_dat_funcionalidad (idfuncionalidad, idrol, idsistema) VALUES (11, 10000000001, 5);
INSERT INTO dat_sistema_seg_rol_dat_funcionalidad (idfuncionalidad, idrol, idsistema) VALUES (12, 10000000001, 5);
INSERT INTO dat_sistema_seg_rol_dat_funcionalidad (idfuncionalidad, idrol, idsistema) VALUES (13, 10000000001, 5);
INSERT INTO dat_sistema_seg_rol_dat_funcionalidad (idfuncionalidad, idrol, idsistema) VALUES (19, 10000000001, 6);
INSERT INTO dat_sistema_seg_rol_dat_funcionalidad (idfuncionalidad, idrol, idsistema) VALUES (20, 10000000001, 6);
INSERT INTO dat_sistema_seg_rol_dat_funcionalidad (idfuncionalidad, idrol, idsistema) VALUES (21, 10000000001, 6);
INSERT INTO dat_sistema_seg_rol_dat_funcionalidad (idfuncionalidad, idrol, idsistema) VALUES (22, 10000000001, 6);
INSERT INTO dat_sistema_seg_rol_dat_funcionalidad (idfuncionalidad, idrol, idsistema) VALUES (23, 10000000001, 7);
INSERT INTO dat_sistema_seg_rol_dat_funcionalidad (idfuncionalidad, idrol, idsistema) VALUES (24, 10000000001, 7);
INSERT INTO dat_sistema_seg_rol_dat_funcionalidad (idfuncionalidad, idrol, idsistema) VALUES (25, 10000000001, 7);
--INSERT INTO dat_sistema_seg_rol_dat_funcionalidad (idfuncionalidad, idrol, idsistema) VALUES (26, 10000000001, 4);

----------------------------------------------------------------------

INSERT INTO dat_sistema_seg_rol_dat_funcionalidad_dat_accion (idaccion, idfuncionalidad, idrol, idsistema) VALUES (4, 2, 10000000001, 3);
INSERT INTO dat_sistema_seg_rol_dat_funcionalidad_dat_accion (idaccion, idfuncionalidad, idrol, idsistema) VALUES (3, 2, 10000000001, 3);
INSERT INTO dat_sistema_seg_rol_dat_funcionalidad_dat_accion (idaccion, idfuncionalidad, idrol, idsistema) VALUES (2, 2, 10000000001, 3);
INSERT INTO dat_sistema_seg_rol_dat_funcionalidad_dat_accion (idaccion, idfuncionalidad, idrol, idsistema) VALUES (179, 2, 10000000001, 3);
INSERT INTO dat_sistema_seg_rol_dat_funcionalidad_dat_accion (idaccion, idfuncionalidad, idrol, idsistema) VALUES (181, 2, 10000000001, 3);
INSERT INTO dat_sistema_seg_rol_dat_funcionalidad_dat_accion (idaccion, idfuncionalidad, idrol, idsistema) VALUES (8, 3, 10000000001, 3);
INSERT INTO dat_sistema_seg_rol_dat_funcionalidad_dat_accion (idaccion, idfuncionalidad, idrol, idsistema) VALUES (9, 3, 10000000001, 3);
INSERT INTO dat_sistema_seg_rol_dat_funcionalidad_dat_accion (idaccion, idfuncionalidad, idrol, idsistema) VALUES (10, 3, 10000000001, 3);
INSERT INTO dat_sistema_seg_rol_dat_funcionalidad_dat_accion (idaccion, idfuncionalidad, idrol, idsistema) VALUES (124, 3, 10000000001, 3);
INSERT INTO dat_sistema_seg_rol_dat_funcionalidad_dat_accion (idaccion, idfuncionalidad, idrol, idsistema) VALUES (12, 4, 10000000001, 3);
INSERT INTO dat_sistema_seg_rol_dat_funcionalidad_dat_accion (idaccion, idfuncionalidad, idrol, idsistema) VALUES (13, 4, 10000000001, 3);
INSERT INTO dat_sistema_seg_rol_dat_funcionalidad_dat_accion (idaccion, idfuncionalidad, idrol, idsistema) VALUES (14, 4, 10000000001, 3);
INSERT INTO dat_sistema_seg_rol_dat_funcionalidad_dat_accion (idaccion, idfuncionalidad, idrol, idsistema) VALUES (125, 4, 10000000001, 3);
INSERT INTO dat_sistema_seg_rol_dat_funcionalidad_dat_accion (idaccion, idfuncionalidad, idrol, idsistema) VALUES (16, 5, 10000000001, 3);
INSERT INTO dat_sistema_seg_rol_dat_funcionalidad_dat_accion (idaccion, idfuncionalidad, idrol, idsistema) VALUES (17, 5, 10000000001, 3);
INSERT INTO dat_sistema_seg_rol_dat_funcionalidad_dat_accion (idaccion, idfuncionalidad, idrol, idsistema) VALUES (18, 5, 10000000001, 3);
INSERT INTO dat_sistema_seg_rol_dat_funcionalidad_dat_accion (idaccion, idfuncionalidad, idrol, idsistema) VALUES (20, 6, 10000000001, 3);
INSERT INTO dat_sistema_seg_rol_dat_funcionalidad_dat_accion (idaccion, idfuncionalidad, idrol, idsistema) VALUES (21, 6, 10000000001, 3);
INSERT INTO dat_sistema_seg_rol_dat_funcionalidad_dat_accion (idaccion, idfuncionalidad, idrol, idsistema) VALUES (22, 6, 10000000001, 3);
INSERT INTO dat_sistema_seg_rol_dat_funcionalidad_dat_accion (idaccion, idfuncionalidad, idrol, idsistema) VALUES (23, 6, 10000000001, 3);
INSERT INTO dat_sistema_seg_rol_dat_funcionalidad_dat_accion (idaccion, idfuncionalidad, idrol, idsistema) VALUES (24, 7, 10000000001, 3);
INSERT INTO dat_sistema_seg_rol_dat_funcionalidad_dat_accion (idaccion, idfuncionalidad, idrol, idsistema) VALUES (25, 7, 10000000001, 3);
INSERT INTO dat_sistema_seg_rol_dat_funcionalidad_dat_accion (idaccion, idfuncionalidad, idrol, idsistema) VALUES (26, 7, 10000000001, 3);
INSERT INTO dat_sistema_seg_rol_dat_funcionalidad_dat_accion (idaccion, idfuncionalidad, idrol, idsistema) VALUES (27, 7, 10000000001, 3);
INSERT INTO dat_sistema_seg_rol_dat_funcionalidad_dat_accion (idaccion, idfuncionalidad, idrol, idsistema) VALUES (30, 8, 10000000001, 3);
INSERT INTO dat_sistema_seg_rol_dat_funcionalidad_dat_accion (idaccion, idfuncionalidad, idrol, idsistema) VALUES (29, 8, 10000000001, 3);
INSERT INTO dat_sistema_seg_rol_dat_funcionalidad_dat_accion (idaccion, idfuncionalidad, idrol, idsistema) VALUES (28, 8, 10000000001, 3);
INSERT INTO dat_sistema_seg_rol_dat_funcionalidad_dat_accion (idaccion, idfuncionalidad, idrol, idsistema) VALUES (180, 8, 10000000001, 3);
INSERT INTO dat_sistema_seg_rol_dat_funcionalidad_dat_accion (idaccion, idfuncionalidad, idrol, idsistema) VALUES (31, 9, 10000000001, 4);
INSERT INTO dat_sistema_seg_rol_dat_funcionalidad_dat_accion (idaccion, idfuncionalidad, idrol, idsistema) VALUES (32, 9, 10000000001, 4);
INSERT INTO dat_sistema_seg_rol_dat_funcionalidad_dat_accion (idaccion, idfuncionalidad, idrol, idsistema) VALUES (33, 9, 10000000001, 4);
INSERT INTO dat_sistema_seg_rol_dat_funcionalidad_dat_accion (idaccion, idfuncionalidad, idrol, idsistema) VALUES (112, 9, 10000000001, 4);
--INSERT INTO dat_sistema_seg_rol_dat_funcionalidad_dat_accion (idaccion, idfuncionalidad, idrol, idsistema) VALUES (35, 10, 10000000001, 4);
--INSERT INTO dat_sistema_seg_rol_dat_funcionalidad_dat_accion (idaccion, idfuncionalidad, idrol, idsistema) VALUES (36, 10, 10000000001, 4);
--INSERT INTO dat_sistema_seg_rol_dat_funcionalidad_dat_accion (idaccion, idfuncionalidad, idrol, idsistema) VALUES (110, 10, 10000000001, 4);
--INSERT INTO dat_sistema_seg_rol_dat_funcionalidad_dat_accion (idaccion, idfuncionalidad, idrol, idsistema) VALUES (111, 10, 10000000001, 4);
INSERT INTO dat_sistema_seg_rol_dat_funcionalidad_dat_accion (idaccion, idfuncionalidad, idrol, idsistema) VALUES (38, 11, 10000000001, 5);
INSERT INTO dat_sistema_seg_rol_dat_funcionalidad_dat_accion (idaccion, idfuncionalidad, idrol, idsistema) VALUES (39, 11, 10000000001, 5);
INSERT INTO dat_sistema_seg_rol_dat_funcionalidad_dat_accion (idaccion, idfuncionalidad, idrol, idsistema) VALUES (40, 11, 10000000001, 5);
INSERT INTO dat_sistema_seg_rol_dat_funcionalidad_dat_accion (idaccion, idfuncionalidad, idrol, idsistema) VALUES (41, 11, 10000000001, 5);
INSERT INTO dat_sistema_seg_rol_dat_funcionalidad_dat_accion (idaccion, idfuncionalidad, idrol, idsistema) VALUES (42, 11, 10000000001, 5);
INSERT INTO dat_sistema_seg_rol_dat_funcionalidad_dat_accion (idaccion, idfuncionalidad, idrol, idsistema) VALUES (101, 11, 10000000001, 5);
INSERT INTO dat_sistema_seg_rol_dat_funcionalidad_dat_accion (idaccion, idfuncionalidad, idrol, idsistema) VALUES (102, 11, 10000000001, 5);
INSERT INTO dat_sistema_seg_rol_dat_funcionalidad_dat_accion (idaccion, idfuncionalidad, idrol, idsistema) VALUES (44, 12, 10000000001, 5);
INSERT INTO dat_sistema_seg_rol_dat_funcionalidad_dat_accion (idaccion, idfuncionalidad, idrol, idsistema) VALUES (45, 12, 10000000001, 5);
INSERT INTO dat_sistema_seg_rol_dat_funcionalidad_dat_accion (idaccion, idfuncionalidad, idrol, idsistema) VALUES (47, 12, 10000000001, 5);
INSERT INTO dat_sistema_seg_rol_dat_funcionalidad_dat_accion (idaccion, idfuncionalidad, idrol, idsistema) VALUES (103, 12, 10000000001, 5);
INSERT INTO dat_sistema_seg_rol_dat_funcionalidad_dat_accion (idaccion, idfuncionalidad, idrol, idsistema) VALUES (104, 12, 10000000001, 5);
INSERT INTO dat_sistema_seg_rol_dat_funcionalidad_dat_accion (idaccion, idfuncionalidad, idrol, idsistema) VALUES (48, 13, 10000000001, 5);
INSERT INTO dat_sistema_seg_rol_dat_funcionalidad_dat_accion (idaccion, idfuncionalidad, idrol, idsistema) VALUES (49, 13, 10000000001, 5);
INSERT INTO dat_sistema_seg_rol_dat_funcionalidad_dat_accion (idaccion, idfuncionalidad, idrol, idsistema) VALUES (50, 13, 10000000001, 5);
INSERT INTO dat_sistema_seg_rol_dat_funcionalidad_dat_accion (idaccion, idfuncionalidad, idrol, idsistema) VALUES (105, 13, 10000000001, 5);
INSERT INTO dat_sistema_seg_rol_dat_funcionalidad_dat_accion (idaccion, idfuncionalidad, idrol, idsistema) VALUES (106, 13, 10000000001, 5);
INSERT INTO dat_sistema_seg_rol_dat_funcionalidad_dat_accion (idaccion, idfuncionalidad, idrol, idsistema) VALUES (1100223,13, 10000000001, 5);
INSERT INTO dat_sistema_seg_rol_dat_funcionalidad_dat_accion (idaccion, idfuncionalidad, idrol, idsistema) VALUES (70, 19, 10000000001, 6);
INSERT INTO dat_sistema_seg_rol_dat_funcionalidad_dat_accion (idaccion, idfuncionalidad, idrol, idsistema) VALUES (69, 19, 10000000001, 6);
INSERT INTO dat_sistema_seg_rol_dat_funcionalidad_dat_accion (idaccion, idfuncionalidad, idrol, idsistema) VALUES (68, 19, 10000000001, 6);
INSERT INTO dat_sistema_seg_rol_dat_funcionalidad_dat_accion (idaccion, idfuncionalidad, idrol, idsistema) VALUES (67, 19, 10000000001, 6); 
INSERT INTO dat_sistema_seg_rol_dat_funcionalidad_dat_accion (idaccion, idfuncionalidad, idrol, idsistema) VALUES (115, 19, 10000000001, 6); 
INSERT INTO dat_sistema_seg_rol_dat_funcionalidad_dat_accion (idaccion, idfuncionalidad, idrol, idsistema) VALUES (116, 19, 10000000001, 6); 
INSERT INTO dat_sistema_seg_rol_dat_funcionalidad_dat_accion (idaccion, idfuncionalidad, idrol, idsistema) VALUES (117, 19, 10000000001, 6); 
INSERT INTO dat_sistema_seg_rol_dat_funcionalidad_dat_accion (idaccion, idfuncionalidad, idrol, idsistema) VALUES (170, 19, 10000000001, 6);
INSERT INTO dat_sistema_seg_rol_dat_funcionalidad_dat_accion (idaccion, idfuncionalidad, idrol, idsistema) VALUES (171, 19, 10000000001, 6);
INSERT INTO dat_sistema_seg_rol_dat_funcionalidad_dat_accion (idaccion, idfuncionalidad, idrol, idsistema) VALUES (172, 19, 10000000001, 6); 
INSERT INTO dat_sistema_seg_rol_dat_funcionalidad_dat_accion (idaccion, idfuncionalidad, idrol, idsistema) VALUES (173, 20, 10000000001, 6);
INSERT INTO dat_sistema_seg_rol_dat_funcionalidad_dat_accion (idaccion, idfuncionalidad, idrol, idsistema) VALUES (174, 20, 10000000001, 6);
INSERT INTO dat_sistema_seg_rol_dat_funcionalidad_dat_accion (idaccion, idfuncionalidad, idrol, idsistema) VALUES (175, 20, 10000000001, 6);
INSERT INTO dat_sistema_seg_rol_dat_funcionalidad_dat_accion (idaccion, idfuncionalidad, idrol, idsistema) VALUES (176, 20, 10000000001, 6);
INSERT INTO dat_sistema_seg_rol_dat_funcionalidad_dat_accion (idaccion, idfuncionalidad, idrol, idsistema) VALUES (177, 20, 10000000001, 6);
INSERT INTO dat_sistema_seg_rol_dat_funcionalidad_dat_accion (idaccion, idfuncionalidad, idrol, idsistema) VALUES (178, 20, 10000000001, 6);
INSERT INTO dat_sistema_seg_rol_dat_funcionalidad_dat_accion (idaccion, idfuncionalidad, idrol, idsistema) VALUES (76, 20, 10000000001, 6);
INSERT INTO dat_sistema_seg_rol_dat_funcionalidad_dat_accion (idaccion, idfuncionalidad, idrol, idsistema) VALUES (75, 20, 10000000001, 6);
INSERT INTO dat_sistema_seg_rol_dat_funcionalidad_dat_accion (idaccion, idfuncionalidad, idrol, idsistema) VALUES (74, 20, 10000000001, 6);
INSERT INTO dat_sistema_seg_rol_dat_funcionalidad_dat_accion (idaccion, idfuncionalidad, idrol, idsistema) VALUES (73, 20, 10000000001, 6);
INSERT INTO dat_sistema_seg_rol_dat_funcionalidad_dat_accion (idaccion, idfuncionalidad, idrol, idsistema) VALUES (72, 20, 10000000001, 6);
INSERT INTO dat_sistema_seg_rol_dat_funcionalidad_dat_accion (idaccion, idfuncionalidad, idrol, idsistema) VALUES (85, 20, 10000000001, 6);
INSERT INTO dat_sistema_seg_rol_dat_funcionalidad_dat_accion (idaccion, idfuncionalidad, idrol, idsistema) VALUES (86, 20, 10000000001, 6);
INSERT INTO dat_sistema_seg_rol_dat_funcionalidad_dat_accion (idaccion, idfuncionalidad, idrol, idsistema) VALUES (118, 20, 10000000001, 6);
INSERT INTO dat_sistema_seg_rol_dat_funcionalidad_dat_accion (idaccion, idfuncionalidad, idrol, idsistema) VALUES (119, 20, 10000000001, 6);
INSERT INTO dat_sistema_seg_rol_dat_funcionalidad_dat_accion (idaccion, idfuncionalidad, idrol, idsistema) VALUES (120, 20, 10000000001, 6);
INSERT INTO dat_sistema_seg_rol_dat_funcionalidad_dat_accion (idaccion, idfuncionalidad, idrol, idsistema) VALUES (182, 20, 10000000001, 6);
INSERT INTO dat_sistema_seg_rol_dat_funcionalidad_dat_accion (idaccion, idfuncionalidad, idrol, idsistema) VALUES (183, 20, 10000000001, 6);
INSERT INTO dat_sistema_seg_rol_dat_funcionalidad_dat_accion (idaccion, idfuncionalidad, idrol, idsistema) VALUES (184, 20, 10000000001, 6);
INSERT INTO dat_sistema_seg_rol_dat_funcionalidad_dat_accion (idaccion, idfuncionalidad, idrol, idsistema) VALUES (1100224, 20, 10000000001, 6);
INSERT INTO dat_sistema_seg_rol_dat_funcionalidad_dat_accion (idaccion, idfuncionalidad, idrol, idsistema) VALUES (1100225, 20, 10000000001, 6);
INSERT INTO dat_sistema_seg_rol_dat_funcionalidad_dat_accion (idaccion, idfuncionalidad, idrol, idsistema) VALUES (78, 21, 10000000001, 6);
INSERT INTO dat_sistema_seg_rol_dat_funcionalidad_dat_accion (idaccion, idfuncionalidad, idrol, idsistema) VALUES (79, 21, 10000000001, 6);
INSERT INTO dat_sistema_seg_rol_dat_funcionalidad_dat_accion (idaccion, idfuncionalidad, idrol, idsistema) VALUES (80, 21, 10000000001, 6);
INSERT INTO dat_sistema_seg_rol_dat_funcionalidad_dat_accion (idaccion, idfuncionalidad, idrol, idsistema) VALUES (121, 21, 10000000001, 6);
INSERT INTO dat_sistema_seg_rol_dat_funcionalidad_dat_accion (idaccion, idfuncionalidad, idrol, idsistema) VALUES (82, 22, 10000000001, 6);
INSERT INTO dat_sistema_seg_rol_dat_funcionalidad_dat_accion (idaccion, idfuncionalidad, idrol, idsistema) VALUES (83, 22, 10000000001, 6);
INSERT INTO dat_sistema_seg_rol_dat_funcionalidad_dat_accion (idaccion, idfuncionalidad, idrol, idsistema) VALUES (122, 22, 10000000001, 6);
INSERT INTO dat_sistema_seg_rol_dat_funcionalidad_dat_accion (idaccion, idfuncionalidad, idrol, idsistema) VALUES (185, 23, 10000000001, 7);
INSERT INTO dat_sistema_seg_rol_dat_funcionalidad_dat_accion (idaccion, idfuncionalidad, idrol, idsistema) VALUES (186, 23, 10000000001, 7);
INSERT INTO dat_sistema_seg_rol_dat_funcionalidad_dat_accion (idaccion, idfuncionalidad, idrol, idsistema) VALUES (187, 23, 10000000001, 7);
INSERT INTO dat_sistema_seg_rol_dat_funcionalidad_dat_accion (idaccion, idfuncionalidad, idrol, idsistema) VALUES (188, 24, 10000000001, 7);
INSERT INTO dat_sistema_seg_rol_dat_funcionalidad_dat_accion (idaccion, idfuncionalidad, idrol, idsistema) VALUES (189, 24, 10000000001, 7);
INSERT INTO dat_sistema_seg_rol_dat_funcionalidad_dat_accion (idaccion, idfuncionalidad, idrol, idsistema) VALUES (190, 24, 10000000001, 7);
INSERT INTO dat_sistema_seg_rol_dat_funcionalidad_dat_accion (idaccion, idfuncionalidad, idrol, idsistema) VALUES (191, 25, 10000000001, 7);
INSERT INTO dat_sistema_seg_rol_dat_funcionalidad_dat_accion (idaccion, idfuncionalidad, idrol, idsistema) VALUES (192, 25, 10000000001, 7);
INSERT INTO dat_sistema_seg_rol_dat_funcionalidad_dat_accion (idaccion, idfuncionalidad, idrol, idsistema) VALUES (193, 25, 10000000001, 7);
--INSERT INTO dat_sistema_seg_rol_dat_funcionalidad_dat_accion (idaccion, idfuncionalidad, idrol, idsistema) VALUES (88, 26, 10000000001, 4);
--INSERT INTO dat_sistema_seg_rol_dat_funcionalidad_dat_accion (idaccion, idfuncionalidad, idrol, idsistema) VALUES (89, 26, 10000000001, 4);
--INSERT INTO dat_sistema_seg_rol_dat_funcionalidad_dat_accion (idaccion, idfuncionalidad, idrol, idsistema) VALUES (87, 26, 10000000001, 4);
--INSERT INTO dat_sistema_seg_rol_dat_funcionalidad_dat_accion (idaccion, idfuncionalidad, idrol, idsistema) VALUES (113, 26, 10000000001, 4);
--INSERT INTO dat_sistema_seg_rol_dat_funcionalidad_dat_accion (idaccion, idfuncionalidad, idrol, idsistema) VALUES (114, 26, 10000000001, 4);

---------------------------------------------------

INSERT INTO dat_sistema_compartimentacion (idsistema, iddominio) VALUES (2, 1);
INSERT INTO dat_sistema_compartimentacion (idsistema, iddominio) VALUES (3, 1);
INSERT INTO dat_sistema_compartimentacion (idsistema, iddominio) VALUES (4, 1);
INSERT INTO dat_sistema_compartimentacion (idsistema, iddominio) VALUES (5, 1);
INSERT INTO dat_sistema_compartimentacion (idsistema, iddominio) VALUES (6, 1);
INSERT INTO dat_sistema_compartimentacion (idsistema, iddominio) VALUES (7, 1);

----------------------------------------------------------------------------------------------------
--funcionalidad compartimentacion

INSERT INTO dat_funcionalidad_compartimentacion (idfuncionalidad, iddominio) VALUES (2, 1);
INSERT INTO dat_funcionalidad_compartimentacion (idfuncionalidad, iddominio) VALUES (3, 1);
INSERT INTO dat_funcionalidad_compartimentacion (idfuncionalidad, iddominio) VALUES (4, 1);
INSERT INTO dat_funcionalidad_compartimentacion (idfuncionalidad, iddominio) VALUES (5, 1);
INSERT INTO dat_funcionalidad_compartimentacion (idfuncionalidad, iddominio) VALUES (6, 1);
INSERT INTO dat_funcionalidad_compartimentacion (idfuncionalidad, iddominio) VALUES (7, 1);
INSERT INTO dat_funcionalidad_compartimentacion (idfuncionalidad, iddominio) VALUES (8, 1);
INSERT INTO dat_funcionalidad_compartimentacion (idfuncionalidad, iddominio) VALUES (9, 1);
--INSERT INTO dat_funcionalidad_compartimentacion (idfuncionalidad, iddominio) VALUES (10, 1);
INSERT INTO dat_funcionalidad_compartimentacion (idfuncionalidad, iddominio) VALUES (11, 1);
INSERT INTO dat_funcionalidad_compartimentacion (idfuncionalidad, iddominio) VALUES (12, 1);
INSERT INTO dat_funcionalidad_compartimentacion (idfuncionalidad, iddominio) VALUES (13, 1);
--INSERT INTO dat_funcionalidad_compartimentacion (idfuncionalidad, iddominio) VALUES (14, 1);
--INSERT INTO dat_funcionalidad_compartimentacion (idfuncionalidad, iddominio) VALUES (15, 1);
--INSERT INTO dat_funcionalidad_compartimentacion (idfuncionalidad, iddominio) VALUES (16, 1);
--INSERT INTO dat_funcionalidad_compartimentacion (idfuncionalidad, iddominio) VALUES (17, 1);
--INSERT INTO dat_funcionalidad_compartimentacion (idfuncionalidad, iddominio) VALUES (18, 1);
INSERT INTO dat_funcionalidad_compartimentacion (idfuncionalidad, iddominio) VALUES (19, 1);
INSERT INTO dat_funcionalidad_compartimentacion (idfuncionalidad, iddominio) VALUES (20, 1);
INSERT INTO dat_funcionalidad_compartimentacion (idfuncionalidad, iddominio) VALUES (21, 1);
INSERT INTO dat_funcionalidad_compartimentacion (idfuncionalidad, iddominio) VALUES (22, 1);
INSERT INTO dat_funcionalidad_compartimentacion (idfuncionalidad, iddominio) VALUES (23, 1);
INSERT INTO dat_funcionalidad_compartimentacion (idfuncionalidad, iddominio) VALUES (24, 1);
INSERT INTO dat_funcionalidad_compartimentacion (idfuncionalidad, iddominio) VALUES (25, 1);
--INSERT INTO dat_funcionalidad_compartimentacion (idfuncionalidad, iddominio) VALUES (26, 1);
--INSERT INTO dat_funcionalidad_compartimentacion (idfuncionalidad, iddominio) VALUES (120002, 1);
--INSERT INTO dat_funcionalidad_compartimentacion (idfuncionalidad, iddominio) VALUES (120003, 1);
--INSERT INTO dat_funcionalidad_compartimentacion (idfuncionalidad, iddominio) VALUES (120004, 1);

----------------------------------------------------------------------------------------------------

INSERT INTO dat_accion_compartimentacion(idaccion, iddominio) VALUES (2, 1);
INSERT INTO dat_accion_compartimentacion(idaccion, iddominio) VALUES (3, 1);
INSERT INTO dat_accion_compartimentacion(idaccion, iddominio) VALUES (4, 1);
INSERT INTO dat_accion_compartimentacion(idaccion, iddominio) VALUES (8, 1);
INSERT INTO dat_accion_compartimentacion(idaccion, iddominio) VALUES (9, 1);
INSERT INTO dat_accion_compartimentacion(idaccion, iddominio) VALUES (10, 1);
INSERT INTO dat_accion_compartimentacion(idaccion, iddominio) VALUES (12, 1);
INSERT INTO dat_accion_compartimentacion(idaccion, iddominio) VALUES (13, 1);
INSERT INTO dat_accion_compartimentacion(idaccion, iddominio) VALUES (14, 1);
INSERT INTO dat_accion_compartimentacion(idaccion, iddominio) VALUES (16, 1);
INSERT INTO dat_accion_compartimentacion(idaccion, iddominio) VALUES (17, 1);
INSERT INTO dat_accion_compartimentacion(idaccion, iddominio) VALUES (18, 1);
INSERT INTO dat_accion_compartimentacion(idaccion, iddominio) VALUES (19, 1);
INSERT INTO dat_accion_compartimentacion(idaccion, iddominio) VALUES (20, 1);
INSERT INTO dat_accion_compartimentacion(idaccion, iddominio) VALUES (21, 1);
INSERT INTO dat_accion_compartimentacion(idaccion, iddominio) VALUES (22, 1);
INSERT INTO dat_accion_compartimentacion(idaccion, iddominio) VALUES (23, 1);
INSERT INTO dat_accion_compartimentacion(idaccion, iddominio) VALUES (24, 1);
INSERT INTO dat_accion_compartimentacion(idaccion, iddominio) VALUES (25, 1);
INSERT INTO dat_accion_compartimentacion(idaccion, iddominio) VALUES (26, 1);
INSERT INTO dat_accion_compartimentacion(idaccion, iddominio) VALUES (27, 1);
INSERT INTO dat_accion_compartimentacion(idaccion, iddominio) VALUES (28, 1);
INSERT INTO dat_accion_compartimentacion(idaccion, iddominio) VALUES (29, 1);
INSERT INTO dat_accion_compartimentacion(idaccion, iddominio) VALUES (30, 1);
INSERT INTO dat_accion_compartimentacion(idaccion, iddominio) VALUES (31, 1);
INSERT INTO dat_accion_compartimentacion(idaccion, iddominio) VALUES (32, 1);
INSERT INTO dat_accion_compartimentacion(idaccion, iddominio) VALUES (33, 1);
--INSERT INTO dat_accion_compartimentacion(idaccion, iddominio) VALUES (35, 1);
--INSERT INTO dat_accion_compartimentacion(idaccion, iddominio) VALUES (36, 1);
INSERT INTO dat_accion_compartimentacion(idaccion, iddominio) VALUES (38, 1);
INSERT INTO dat_accion_compartimentacion(idaccion, iddominio) VALUES (39, 1);
INSERT INTO dat_accion_compartimentacion(idaccion, iddominio) VALUES (40, 1);
INSERT INTO dat_accion_compartimentacion(idaccion, iddominio) VALUES (41, 1);
INSERT INTO dat_accion_compartimentacion(idaccion, iddominio) VALUES (42, 1);
INSERT INTO dat_accion_compartimentacion(idaccion, iddominio) VALUES (44, 1);
INSERT INTO dat_accion_compartimentacion(idaccion, iddominio) VALUES (45, 1);
INSERT INTO dat_accion_compartimentacion(idaccion, iddominio) VALUES (47, 1);
INSERT INTO dat_accion_compartimentacion(idaccion, iddominio) VALUES (48, 1);
INSERT INTO dat_accion_compartimentacion(idaccion, iddominio) VALUES (49, 1);
INSERT INTO dat_accion_compartimentacion(idaccion, iddominio) VALUES (50, 1);
INSERT INTO dat_accion_compartimentacion(idaccion, iddominio) VALUES (67, 1);
INSERT INTO dat_accion_compartimentacion(idaccion, iddominio) VALUES (68, 1);
INSERT INTO dat_accion_compartimentacion(idaccion, iddominio) VALUES (69, 1);
INSERT INTO dat_accion_compartimentacion(idaccion, iddominio) VALUES (70, 1);
INSERT INTO dat_accion_compartimentacion(idaccion, iddominio) VALUES (72, 1);
INSERT INTO dat_accion_compartimentacion(idaccion, iddominio) VALUES (73, 1);
INSERT INTO dat_accion_compartimentacion(idaccion, iddominio) VALUES (74, 1);
INSERT INTO dat_accion_compartimentacion(idaccion, iddominio) VALUES (75, 1);
INSERT INTO dat_accion_compartimentacion(idaccion, iddominio) VALUES (76, 1);
INSERT INTO dat_accion_compartimentacion(idaccion, iddominio) VALUES (78, 1);
INSERT INTO dat_accion_compartimentacion(idaccion, iddominio) VALUES (79, 1);
INSERT INTO dat_accion_compartimentacion(idaccion, iddominio) VALUES (80, 1);
INSERT INTO dat_accion_compartimentacion(idaccion, iddominio) VALUES (82, 1);
INSERT INTO dat_accion_compartimentacion(idaccion, iddominio) VALUES (83, 1);
INSERT INTO dat_accion_compartimentacion(idaccion, iddominio) VALUES (85, 1);
INSERT INTO dat_accion_compartimentacion(idaccion, iddominio) VALUES (86, 1);
--INSERT INTO dat_accion_compartimentacion(idaccion, iddominio) VALUES (87, 1);
--INSERT INTO dat_accion_compartimentacion(idaccion, iddominio) VALUES (88, 1);
--INSERT INTO dat_accion_compartimentacion(idaccion, iddominio) VALUES (89, 1);
INSERT INTO dat_accion_compartimentacion(idaccion, iddominio) VALUES (101, 1);
INSERT INTO dat_accion_compartimentacion(idaccion, iddominio) VALUES (102, 1);
INSERT INTO dat_accion_compartimentacion(idaccion, iddominio) VALUES (103, 1);
INSERT INTO dat_accion_compartimentacion(idaccion, iddominio) VALUES (104, 1);
INSERT INTO dat_accion_compartimentacion(idaccion, iddominio) VALUES (105, 1);
INSERT INTO dat_accion_compartimentacion(idaccion, iddominio) VALUES (106, 1);
--INSERT INTO dat_accion_compartimentacion(idaccion, iddominio) VALUES (107, 1);
--INSERT INTO dat_accion_compartimentacion(idaccion, iddominio) VALUES (108, 1);
--INSERT INTO dat_accion_compartimentacion(idaccion, iddominio) VALUES (109, 1);
--INSERT INTO dat_accion_compartimentacion(idaccion, iddominio) VALUES (110, 1);
--INSERT INTO dat_accion_compartimentacion(idaccion, iddominio) VALUES (111, 1);
INSERT INTO dat_accion_compartimentacion(idaccion, iddominio) VALUES (112, 1);
--INSERT INTO dat_accion_compartimentacion(idaccion, iddominio) VALUES (113, 1);
--INSERT INTO dat_accion_compartimentacion(idaccion, iddominio) VALUES (114, 1);
INSERT INTO dat_accion_compartimentacion(idaccion, iddominio) VALUES (115, 1);
INSERT INTO dat_accion_compartimentacion(idaccion, iddominio) VALUES (116, 1);
INSERT INTO dat_accion_compartimentacion(idaccion, iddominio) VALUES (117, 1);
INSERT INTO dat_accion_compartimentacion(idaccion, iddominio) VALUES (118, 1);
INSERT INTO dat_accion_compartimentacion(idaccion, iddominio) VALUES (119, 1);
INSERT INTO dat_accion_compartimentacion(idaccion, iddominio) VALUES (120, 1);
INSERT INTO dat_accion_compartimentacion(idaccion, iddominio) VALUES (121, 1);
INSERT INTO dat_accion_compartimentacion(idaccion, iddominio) VALUES (122, 1);
INSERT INTO dat_accion_compartimentacion(idaccion, iddominio) VALUES (123, 1);
INSERT INTO dat_accion_compartimentacion(idaccion, iddominio) VALUES (124, 1);
INSERT INTO dat_accion_compartimentacion(idaccion, iddominio) VALUES (125, 1);
INSERT INTO dat_accion_compartimentacion(idaccion, iddominio) VALUES (170, 1);
INSERT INTO dat_accion_compartimentacion(idaccion, iddominio) VALUES (171, 1);
INSERT INTO dat_accion_compartimentacion(idaccion, iddominio) VALUES (172, 1);
INSERT INTO dat_accion_compartimentacion(idaccion, iddominio) VALUES (173, 1);
INSERT INTO dat_accion_compartimentacion(idaccion, iddominio) VALUES (174, 1);
INSERT INTO dat_accion_compartimentacion(idaccion, iddominio) VALUES (175, 1);
INSERT INTO dat_accion_compartimentacion(idaccion, iddominio) VALUES (176, 1);
INSERT INTO dat_accion_compartimentacion(idaccion, iddominio) VALUES (177, 1);
INSERT INTO dat_accion_compartimentacion(idaccion, iddominio) VALUES (178, 1);
INSERT INTO dat_accion_compartimentacion(idaccion, iddominio) VALUES (179, 1);
INSERT INTO dat_accion_compartimentacion(idaccion, iddominio) VALUES (180, 1);
INSERT INTO dat_accion_compartimentacion(idaccion, iddominio) VALUES (181, 1);
INSERT INTO dat_accion_compartimentacion(idaccion, iddominio) VALUES (182, 1);
INSERT INTO dat_accion_compartimentacion(idaccion, iddominio) VALUES (183, 1);
INSERT INTO dat_accion_compartimentacion(idaccion, iddominio) VALUES (184, 1);
INSERT INTO dat_accion_compartimentacion(idaccion, iddominio) VALUES (185, 1);
INSERT INTO dat_accion_compartimentacion(idaccion, iddominio) VALUES (186, 1);
INSERT INTO dat_accion_compartimentacion(idaccion, iddominio) VALUES (187, 1);
INSERT INTO dat_accion_compartimentacion(idaccion, iddominio) VALUES (188, 1);
INSERT INTO dat_accion_compartimentacion(idaccion, iddominio) VALUES (189, 1);
INSERT INTO dat_accion_compartimentacion(idaccion, iddominio) VALUES (190, 1);
INSERT INTO dat_accion_compartimentacion(idaccion, iddominio) VALUES (191, 1);
INSERT INTO dat_accion_compartimentacion(idaccion, iddominio) VALUES (192, 1);
INSERT INTO dat_accion_compartimentacion(idaccion, iddominio) VALUES (193, 1);

-----------------------------------------------------------------------------------

INSERT INTO seg_compartimentacionroles (idrol, iddominio) VALUES (10000000001, 1);

-----------------------------------------------------------------------------------

INSERT INTO seg_compartimentacionusuario (idusuario, iddominio) VALUES (1000000001, 1);

-----------------------------------------------------------------------------------

INSERT INTO seg_rol_nom_dominio(idrol, iddominio) VALUES (10000000001, 1);

INSERT INTO seg_claveacceso (valor, clave, idusuario,fechainicio,fechafin) VALUES (true, 'cb58ceb169fa69a98b7d60a820b0b37c', 1000000001,'31/12/2012','31/12/2050');

INSERT INTO nom_tipoconex(idconexion, seleccion, denominacion, descripcion, tipo)VALUES (1,TRUE, 'sistema', 'sistema', 1);

COMMIT;
