-------------------V5.0.0---------------------
/*
--CENTRO DE SOLUCIONES DE GESTIÓN		----
--Subdirección de tecnología			----
--										----
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
VALUES ('V5.0.0', 'V5.0.0','mod_seguridad',null,'I','D','I');
---------------------------------------------------------------------------------------------------------------------------
--Datos del esquema mod_Seguridad
----------------------------------------------------------------------------------------------------------------------------------	
	SET search_path = mod_seguridad, pg_catalog;	
---------------------------------------------------------------------	
INSERT INTO seg_rol VALUES (10000000001, 'instalacion', 'Rol de instalacion', 'INS');
----------------------------------------------------------------------
INSERT INTO nom_desktop (iddesktop, denominacion, abreviatura, descripcion) VALUES (100000001, 'Sitio web con menú vertical', 'standardarbol', 'Portal que muestra el menú en forma de árbol.');
INSERT INTO nom_desktop (iddesktop, denominacion, abreviatura, descripcion) VALUES (100000002, 'Escritorio de funcionalidades', 'desktopaction', 'Portal en forma de escritorio que muestra el menú hasta el nivel de funcionalidades.');
----------------------------------------------------------------------
INSERT INTO nom_idioma (ididioma, denominacion, abreviatura) VALUES (100000001, 'Español', 'es');
----------------------------------------------------------------------
INSERT INTO nom_tema (idtema, denominacion, abreviatura, descripcion) VALUES (100000001, 'Estándar', 'default', 'default');
----------------------------------------------------------------------
INSERT INTO seg_usuario (idusuario, idcargo, idarea, identidad, nombreusuario, contrasenna, contrasenabd, ip, iddominio, iddesktop, idtema, ididioma, estado, perfilxml, accesodirecto) VALUES (1000000001, 0, 0, 100000001, 'instalacion', 'cb58ceb169fa69a98b7d60a820b0b37c', 'cb58ceb169fa69a98b7d60a820b0b37c', '0.0.0.0/0', 1, 100000002, 100000001, 100000001, '0', '', '');
----------------------------------------------------------------------
INSERT INTO dat_entidad_seg_usuario_seg_rol (idusuario, identidad, idrol) VALUES (1000000001, 100000001, 10000000001);
----------------------------------------------------------------------
INSERT INTO nom_expresiones VALUES (1, 'letras', '/^([a-zA-Z]+ ?[a-zA-Z]*)+$/', '');
----------------------------------------------------------------------
INSERT INTO seg_restricclaveacceso VALUES (100, 20, true, true, false, 4);
----------------------------------------------------------------------

INSERT INTO dat_sistema (idsistema, idpadre, denominacion, icono, abreviatura, descripcion, externa, lft, rgt, iddominio) VALUES (3, 2, 'Configurar nomencladores', '', NULL, '', '', 2, 3, 1);
INSERT INTO dat_sistema (idsistema, idpadre, denominacion, icono, abreviatura, descripcion, externa, lft, rgt, iddominio) VALUES (4, 2, 'Configurar servidores', '', NULL, '', '', 4, 5, 1);
INSERT INTO dat_sistema (idsistema, idpadre, denominacion, icono, abreviatura, descripcion, externa, lft, rgt, iddominio) VALUES (5, 2, 'Configurar sistemas', '', NULL, '', '', 6, 7, 1);
INSERT INTO dat_sistema (idsistema, idpadre, denominacion, icono, abreviatura, descripcion, externa, lft, rgt, iddominio) VALUES (2, 2, 'Seguridad', 'seguridad', NULL, '', '', 1, 13, 1);
INSERT INTO dat_sistema (idsistema, idpadre, denominacion, icono, abreviatura, descripcion, externa, lft, rgt, iddominio) VALUES (6, 2, 'Configurar usuarios', '', NULL, '', '', 8, 9, 1);
INSERT INTO dat_sistema (idsistema, idpadre, denominacion, icono, abreviatura, descripcion, externa, lft, rgt, iddominio) VALUES (7, 2, 'Compartimentación', '', NULL, '', '', 10, 11, 1);
INSERT INTO dat_sistema (idsistema, idpadre, denominacion, icono, abreviatura, descripcion, externa, lft, rgt, iddominio) VALUES (101, 101, 'Estructura y composición', 'estructura', NULL, '', '', 14, 15, 1);
INSERT INTO dat_sistema (idsistema, idpadre, denominacion, icono, abreviatura, descripcion, externa, lft, rgt, iddominio) VALUES (1001, 1001, 'Traza', 'traza', NULL, '', '', 16, 17, 1);

----------------------------------------------------------------------
INSERT INTO dat_funcionalidad (idfuncionalidad, index, referencia, denominacion, descripcion, icono, idsistema, iddominio) VALUES (2, 0, 'seguridad/index.php/gestnomdominio/gestnomdominio', 'Dominio', '', 'falta', 3, 1);
INSERT INTO dat_funcionalidad (idfuncionalidad, index, referencia, denominacion, descripcion, icono, idsistema, iddominio) VALUES (3, 0, 'seguridad/index.php/gestnomgestor/gestnomgestor', 'Gestores de bases de datos', '', 'falta', 3, 1);
INSERT INTO dat_funcionalidad (idfuncionalidad, index, referencia, denominacion, descripcion, icono, idsistema, iddominio) VALUES (4, 0, 'seguridad/index.php/gestnomidioma/gestnomidioma', 'Idiomas', '', 'falta', 3, 1);
INSERT INTO dat_funcionalidad (idfuncionalidad, index, referencia, denominacion, descripcion, icono, idsistema, iddominio) VALUES (5, 0, 'seguridad/index.php/gestexpresiones/gestexpresiones', 'Expresiones', '', 'falta', 3, 1);
INSERT INTO dat_funcionalidad (idfuncionalidad, index, referencia, denominacion, descripcion, icono, idsistema, iddominio) VALUES (6, 0, 'seguridad/index.php/gestnomtema/gestnomtema', 'Temas', '', 'falta', 3, 1);
INSERT INTO dat_funcionalidad (idfuncionalidad, index, referencia, denominacion, descripcion, icono, idsistema, iddominio) VALUES (7, 0, 'seguridad/index.php/gestnomdesktop/gestnomdesktop', 'Escritorios', '', 'falta', 3, 1);
INSERT INTO dat_funcionalidad (idfuncionalidad, index, referencia, denominacion, descripcion, icono, idsistema, iddominio) VALUES (8, 0, 'seguridad/index.php/configcont/configcont', 'Claves', '', 'falta', 3, 1);
INSERT INTO dat_funcionalidad (idfuncionalidad, index, referencia, denominacion, descripcion, icono, idsistema, iddominio) VALUES (9, 0, 'seguridad/index.php/gestservidor/gestservidor', 'Servidores', '', 'falta', 4, 1);
INSERT INTO dat_funcionalidad (idfuncionalidad, index, referencia, denominacion, descripcion, icono, idsistema, iddominio) VALUES (10, 0, 'seguridad/index.php/gestgestor/gestgestor', 'Gestores de bases de datos', '', 'falta', 4, 1);
INSERT INTO dat_funcionalidad (idfuncionalidad, index, referencia, denominacion, descripcion, icono, idsistema, iddominio) VALUES (11, 0, 'seguridad/index.php/gestsistema/gestsistema', 'Sistemas', '', 'falta', 5, 1);
INSERT INTO dat_funcionalidad (idfuncionalidad, index, referencia, denominacion, descripcion, icono, idsistema, iddominio) VALUES (12, 0, 'seguridad/index.php/gestfuncionalidad/gestfuncionalidad', 'Funcionalidades', '', 'falta', 5, 1);
INSERT INTO dat_funcionalidad (idfuncionalidad, index, referencia, denominacion, descripcion, icono, idsistema, iddominio) VALUES (13, 0, 'seguridad/index.php/gestaccion/gestaccion', 'Acciones', '', 'falta', 5, 1);
INSERT INTO dat_funcionalidad (idfuncionalidad, index, referencia, denominacion, descripcion, icono, idsistema, iddominio) VALUES (14, 0, 'seguridad/index.php/gestservpresta/gestservpresta', 'Servicios que presta', '', 'falta', 5, 1);
INSERT INTO dat_funcionalidad (idfuncionalidad, index, referencia, denominacion, descripcion, icono, idsistema, iddominio) VALUES (15, 0, 'seguridad/index.php/gestserviciocons/gestserviciocons', 'Servicios que consume', '', 'falta', 5, 1);
INSERT INTO dat_funcionalidad (idfuncionalidad, index, referencia, denominacion, descripcion, icono, idsistema, iddominio) VALUES (16, 0, 'seguridad/index.php/gestdatfunciones/gestdatfunciones', 'Funciones', '', 'falta', 5, 1);
INSERT INTO dat_funcionalidad (idfuncionalidad, index, referencia, denominacion, descripcion, icono, idsistema, iddominio) VALUES (17, 0, 'seguridad/index.php/gestdatparametros/gestdatparametros', 'Parámetros', '', 'falta', 5, 1);
INSERT INTO dat_funcionalidad (idfuncionalidad, index, referencia, denominacion, descripcion, icono, idsistema, iddominio) VALUES (18, 0, 'seguridad/index.php/gestaccrep/gestaccrep', 'Acciones y Reportes', 'Acciones y Reportes ', '', 5, 1);
INSERT INTO dat_funcionalidad (idfuncionalidad, index, referencia, denominacion, descripcion, icono, idsistema, iddominio) VALUES (19, 0, 'seguridad/index.php/gestrol/gestrol', 'Roles', '', 'falta', 6, 1);
INSERT INTO dat_funcionalidad (idfuncionalidad, index, referencia, denominacion, descripcion, icono, idsistema, iddominio) VALUES (20, 0, 'seguridad/index.php/gestusuario/gestusuario', 'Usuarios', '', 'falta', 6, 1);
INSERT INTO dat_funcionalidad (idfuncionalidad, index, referencia, denominacion, descripcion, icono, idsistema, iddominio) VALUES (21, 0, 'seguridad/index.php/datosusuario/datosusuario', 'Campos del perfil de usuario', '', 'falta', 6, 1);
INSERT INTO dat_funcionalidad (idfuncionalidad, index, referencia, denominacion, descripcion, icono, idsistema, iddominio) VALUES (22, 0, 'seguridad/index.php/gestperfil/gestperfil', 'Perfil de usuario', '', 'falta', 6, 1);

INSERT INTO dat_funcionalidad (idfuncionalidad, index, referencia, denominacion, descripcion, icono, idsistema, iddominio) VALUES (23, 0, 'seguridad/index.php/gestcompartimentacionsistema/gestcompartimentacionsistema', 'Compartimentar sistemas', '', 'falta', 7, 1);
INSERT INTO dat_funcionalidad (idfuncionalidad, index, referencia, denominacion, descripcion, icono, idsistema, iddominio) VALUES (24, 0, 'seguridad/index.php/gestusuariodominio/gestusuariodominio', 'Compartimentar dominio', '', 'falta', 7, 1);
INSERT INTO dat_funcionalidad (idfuncionalidad, index, referencia, denominacion, descripcion, icono, idsistema, iddominio) VALUES (25, 0, 'seguridad/index.php/gestroldominio/gestroldominio', 'Compartimentar rol', '', 'falta', 7, 1);

INSERT INTO dat_funcionalidad (idfuncionalidad, index, referencia, denominacion, descripcion, icono, idsistema, iddominio) VALUES (1001, 0, '/metadatos/index.php/eav/gestionareav', 'Definir nivel estructural', '', 'falta', 101, 1);
INSERT INTO dat_funcionalidad (idfuncionalidad, index, referencia, denominacion, descripcion, icono, idsistema, iddominio) VALUES (1002, 0, '/metadatos/index.php/estructura/gestionarestructura', 'Gestionar estructuras', '', 'falta', 101, 1);
INSERT INTO dat_funcionalidad (idfuncionalidad, index, referencia, denominacion, descripcion, icono, idsistema, iddominio) VALUES (1003, 0, '/metadatos/index.php/nomenclador/gestionarnomenclador', 'Gestionar nomencladores', '', 'falta', 101, 1);
INSERT INTO dat_funcionalidad (idfuncionalidad, index, referencia, denominacion, descripcion, icono, idsistema, iddominio) VALUES (1004, 0, '/metadatos/index.php/estructurasb/gestionarestructura', 'Subordinaciones', '', '', 101, 1);
INSERT INTO dat_funcionalidad (idfuncionalidad, index, referencia, denominacion, descripcion, icono, idsistema, iddominio) VALUES (1005, 0, '/metadatos/index.php/reportes/gestionarreportes', 'Recuperaciones', '', 'falta', 101, 1);
INSERT INTO dat_funcionalidad (idfuncionalidad, index, referencia, denominacion, descripcion, icono, idsistema, iddominio) VALUES (10001, 0, 'traza/index.php/gestionartraza/gestionartraza', 'Gestionar traza', '', 'falta', 1001, 1);

----------------------------------------------------------------------
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio) VALUES (2, 'icon', 'Adicionar dominio', 'Adicionar dominio', 'btnAgr', 2, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio) VALUES (3, 'icon', 'Modificar dominio', 'Modificar dominio', 'btnMod', 2, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio) VALUES (4, 'icon', 'Eliminar dominio', 'Eliminar dominio', 'btnEli', 2, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio) VALUES (5, 'icon', 'Ayuda', 'Ayuda', 'btnAyu', 2, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio) VALUES (6, 'icon', 'adicionar dominio2', 'wefw', 'dafva', 2, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio) VALUES (7, 'icon', 'nuevo', 'ersfg', 'nu', 2, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio) VALUES (8, 'icon', 'Adicionar gestor', 'Adicionar gestor', 'btnAgrGest', 3, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio) VALUES (9, 'icon', 'Modificar gestor', 'Modificar gestor', 'btnModGest', 3, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio) VALUES (10, 'icon', 'Eliminar gestor', 'Eliminar gestor', 'btnEliGest', 3, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio) VALUES (11, 'icon', 'Ayuda de gestor', 'Ayuda de gestor', 'btnAyuGest', 3, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio) VALUES (12, 'icon', 'Adicionar idioma', 'Adicionar idioma', 'btnAgrIdioma', 4, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio) VALUES (13, 'icon', 'Modificar idioma', 'Modificar idioma', 'btnModIdioma', 4, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio) VALUES (14, 'icon', 'Eliminar idioma', 'Eliminar idioma', 'btnEliIdioma', 4, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio) VALUES (15, 'icon', 'Ayuda de idioma', 'Ayuda de idioma', 'btnAyuIdioma', 4, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio) VALUES (16, 'icon', 'Adicionar expreciones', 'Adicionar expreciones', 'btnAgrExpre', 5, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio) VALUES (17, 'icon', 'Modificar expreciones', 'Modificar expreciones', 'btnModExpre', 5, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio) VALUES (18, 'icon', 'Eliminar expreciones', 'Eliminar expreciones', 'btnEliExpre', 5, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio) VALUES (19, 'icon', 'Ayuda de expreciones', 'Ayuda de expreciones', 'btnAyuExpre', 5, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio) VALUES (20, 'icon', 'Adicionar tema', 'Adicionar tema', 'btnAgrTema', 6, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio) VALUES (21, 'icon', 'Modificar tema', 'Modificar tema', 'btnModTema', 6, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio) VALUES (22, 'icon', 'Eliminar tema', 'Eliminar tema', 'btnEliTema', 6, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio) VALUES (23, 'icon', 'Ayuda de tema', 'Ayuda de tema', 'btnAyuTema', 6, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio) VALUES (24, 'icon', 'Adicionar escritorio', 'Adicionar escritorio', 'btnAgrDesktop', 7, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio) VALUES (25, 'icon', 'Modificar escritorio', 'Modificar escritorio', 'btnModDesktop', 7, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio) VALUES (26, 'icon', 'Eliminar escritorio', 'Eliminar escritorio', 'btnEliDesktop', 7, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio) VALUES (27, 'icon', 'Ayuda de escritorios', 'Ayuda de escritorios', 'btnAyuDesktop', 7, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio) VALUES (28, 'icon', 'Adicionar claves', 'Adicionar claves', 'btnAgrClaves', 8, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio) VALUES (29, 'icon', 'Modificar claves', 'Modificar claves', 'btnModClaves', 8, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio) VALUES (30, 'icon', 'Ayuda de claves', 'Ayuda de claves', 'btnAyuClaves', 8, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio) VALUES (31, 'icon', 'Adicionar servidor', 'Adicionar servidor', 'btnAgrServ', 9, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio) VALUES (32, 'icon', 'Modificar servidor', 'Modificar servidor', 'btnModServ', 9, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio) VALUES (33, 'icon', 'Eliminar servidor', 'Eliminar servidor', 'btnEliServ', 9, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio) VALUES (34, 'icon', 'Ayuda de servidor', 'Ayuda de servidor', 'btnAyuServ', 9, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio) VALUES (35, 'icon', 'Adicionar gestor de BD', 'Adicionar gestor de BD', 'btnAgrgestorBd', 10, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio) VALUES (36, 'icon', 'Eliminar  gestor de BD', 'Eliminar  gestor de BD', 'btnEligestorBd', 10, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio) VALUES (37, 'icon', 'Ayuda de gestor de BD', 'Ayuda de gestor de BD', 'btnAyugestorBd', 10, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio) VALUES (38, 'icon', 'Adicionar sistema', 'Adicionar sistema', 'btnAgrSist', 11, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio) VALUES (39, 'icon', 'Modificar sistema', 'Modificar sistema', 'btnModSist', 11, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio) VALUES (40, 'icon', 'Eliminar sistema', 'Eliminar sistema', 'btnEliSist', 11, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio) VALUES (41, 'icon', 'Exportar sistema', 'Exportar sistema', 'btnExpSist', 11, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio) VALUES (42, 'icon', 'Importar sistema', 'Importar sistema', 'btnImpSist', 11, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio) VALUES (43, 'icon', 'Ayuda de sistema', 'Ayuda de sistema', 'btnAyuSist', 11, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio) VALUES (44, 'icon', 'Modificar funcionalidad', 'Modificar funcionalidad', 'btnModFunc', 12, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio) VALUES (45, 'icon', 'Eliminar funcionalidad', 'Eliminar funcionalidad', 'btnEliFunc', 12, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio) VALUES (46, 'icon', 'Ayuda de funcionalidad', 'Ayuda de funcionalidad', 'btnAyuFunc', 12, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio) VALUES (47, 'icon', 'Adicionar funcionalidad', 'Adicionar funcionalidad', 'btnAgrFunc', 12, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio) VALUES (48, 'icon', 'Adicionar acción', 'Adicionar acción', 'btnAgrAccion', 13, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio) VALUES (49, 'icon', 'Modificar acción', 'Modificar acción', 'btnModAccion', 13, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio) VALUES (50, 'icon', 'Eliminar acción', 'Eliminar acción', 'btnEliAccion', 13, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio) VALUES (51, 'icon', 'Ayuda de acción', 'Ayuda de acción', 'btnAyuAccion', 13, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio) VALUES (52, 'icon', 'Adicionar servicios que presta', 'Adicionar servicios que presta', 'btnAgrServPres', 14, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio) VALUES (53, 'icon', 'Modificar servicios que presta', 'Modificar servicios que presta', 'btnModServPres', 14, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio) VALUES (54, 'icon', 'Eliminar sericios que presta', 'Eliminar sericios que presta', 'btnEliServPres', 14, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio) VALUES (55, 'icon', 'Ayuda de servicios que presta', 'Ayuda de servicios que presta', 'btnAyuServPres', 14, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio) VALUES (56, 'icon', 'Adicionar serv. que consume', 'Adicionar serv. que consume', 'btnAgrServCons', 15, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio) VALUES (57, 'icon', 'Eliminar serv. que consume', 'Eliminar serv. que consume', 'btnEliServCons', 15, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio) VALUES (58, 'icon', 'Ayuda de serv. que consume', 'Ayuda de serv. que consume', 'btnAyuServCons', 15, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio) VALUES (59, 'icon', 'Adicionar funciones', 'Adicionar funciones', 'btnAgrFunciones', 16, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio) VALUES (60, 'icon', 'Modificar funciones', 'Modificar funciones', 'btnModFunciones', 16, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio) VALUES (61, 'icon', 'Eliminar funciones', 'Eliminar funciones', 'btnEliFunciones', 16, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio) VALUES (62, 'icon', 'Ayuda de funciones', 'Ayuda de funciones', 'btnAyuFunciones', 16, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio) VALUES (63, 'icon', 'Adicionar parametros', 'Adicionar parametros', 'btnAgrParametros', 17, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio) VALUES (64, 'icon', 'Modificar parametros', 'Modificar parametros', 'btnModParametros', 17, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio) VALUES (65, 'icon', 'Eliminar parametros', 'Eliminar parametros', 'btnEliParametros', 17, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio) VALUES (66, 'icon', 'Ayuda de parametros', 'Ayuda de parametros', 'btnAyuParametros', 17, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio) VALUES (67, 'icon', 'Adicionar rol', 'Adicionar rol', 'btnAgrRol', 19, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio) VALUES (68, 'icon', 'Modificar rol', 'Modificar rol', 'btnModRol', 19, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio) VALUES (69, 'icon', 'Eliminar rol', 'Eliminar rol', 'btnEliRol', 19, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio) VALUES (70, 'icon', 'Regular acciones del rol', 'Regular acciones del rol', 'btnRestRol', 19, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio) VALUES (71, 'icon', 'Ayuda de rol', 'Ayuda de rol', 'btnAyuRol', 19, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio) VALUES (72, 'icon', 'Adicionar usuario', 'Adicionar usuario', 'btnAgrUser', 20, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio) VALUES (73, 'icon', 'Modificar usuario', 'Modificar usuario', 'btnModUser', 20, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio) VALUES (74, 'icon', 'Eliminar usuario', 'Eliminar usuario', 'btnEliUser', 20, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio) VALUES (75, 'icon', 'Adicionar roles de usuario', 'Adicionar roles de usuario', 'btnAgrUserRol', 20, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio) VALUES (76, 'icon', 'Cambiar contraseña de usuario', 'Cambiar contraseña de usuario', 'btnContUser', 20, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio) VALUES (77, 'icon', 'Ayuda de usuario', 'Ayuda de usuario', 'btnAyuUser', 20, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio) VALUES (78, 'icon', 'Adicionar campos del perfil', 'Adicionar campos del perfil', 'btnAgrCampPerf', 21, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio) VALUES (79, 'icon', 'Modificar campos del perfil', 'Modificar campos del perfil', 'btnModCampPerf', 21, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio) VALUES (80, 'icon', 'Eliminar campos del perfil', 'Eliminar campos del perfil', 'btnEliCampPerf', 21, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio) VALUES (81, 'icon', 'Ayuda de campos del perfil', 'Ayuda de campos del perfil', 'btnAyuCampPerf', 21, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio) VALUES (82, 'icon', 'Adicionar perfil', 'Adicionar perfil', 'btnAddPerfil', 22, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio) VALUES (83, 'icon', 'Modificar perfil', 'Modificar perfil', 'btnModPerfil', 22, 1);
INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio) VALUES (84, 'icon', 'Ayuda del perfil', 'Ayuda del perfil', 'btnHelpPerfil', 22, 1);
INSERT INTO dat_accion(idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio) VALUES (85, 'icon', 'Activar usuarios', 'btnActivarUser', 'btnActivarUser', 20, 1);
INSERT INTO dat_accion(idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio) VALUES (86, 'icon', 'Desctivar usuarios', 'btnDesctivarUser', 'btnDesctivarUser', 20, 1);

INSERT INTO dat_accion (idaccion, icono, denominacion, descripcion, abreviatura, idfuncionalidad, iddominio) VALUES (1001, 'icon', 'Mostrar reportes', 'Muestra los reportes de Estructura y Composición', 'mreporte', 1005, 1);

INSERT INTO "mod_seguridad"."dat_sistema_seg_rol" ("idrol", "idsistema")
VALUES (10000000001, 2);

INSERT INTO "mod_seguridad"."dat_sistema_seg_rol" ("idrol", "idsistema")
VALUES (10000000001, 3);

INSERT INTO "mod_seguridad"."dat_sistema_seg_rol" ("idrol", "idsistema")
VALUES (10000000001, 6);

INSERT INTO "mod_seguridad"."dat_sistema_seg_rol" ("idrol", "idsistema")
VALUES (10000000001, 7);

INSERT INTO "mod_seguridad"."dat_sistema_seg_rol" ("idrol", "idsistema")
VALUES (10000000001,101);
INSERT INTO "mod_seguridad"."dat_sistema_seg_rol" ("idrol", "idsistema")
VALUES (10000000001,1001);
----------------------------------------------------------------------

INSERT INTO "mod_seguridad"."dat_sistema_seg_rol_dat_funcionalidad" ("idfuncionalidad", "idrol", "idsistema")
VALUES (2, 10000000001, 3);

INSERT INTO "mod_seguridad"."dat_sistema_seg_rol_dat_funcionalidad" ("idfuncionalidad", "idrol", "idsistema")
VALUES (8, 10000000001, 3);

INSERT INTO "mod_seguridad"."dat_sistema_seg_rol_dat_funcionalidad" ("idfuncionalidad", "idrol", "idsistema")
VALUES (19, 10000000001, 6);

INSERT INTO "mod_seguridad"."dat_sistema_seg_rol_dat_funcionalidad" ("idfuncionalidad", "idrol", "idsistema")
VALUES (20, 10000000001, 6);

INSERT INTO "mod_seguridad"."dat_sistema_seg_rol_dat_funcionalidad" ("idfuncionalidad", "idrol", "idsistema")
VALUES (23, 10000000001, 7);

INSERT INTO "mod_seguridad"."dat_sistema_seg_rol_dat_funcionalidad" ("idfuncionalidad", "idrol", "idsistema")
VALUES (24, 10000000001, 7);

INSERT INTO "mod_seguridad"."dat_sistema_seg_rol_dat_funcionalidad" ("idfuncionalidad", "idrol", "idsistema")
VALUES (25, 10000000001, 7);

INSERT INTO "mod_seguridad"."dat_sistema_seg_rol_dat_funcionalidad" ("idfuncionalidad", "idrol", "idsistema")
VALUES (1001, 10000000001, 101);

INSERT INTO "mod_seguridad"."dat_sistema_seg_rol_dat_funcionalidad" ("idfuncionalidad", "idrol", "idsistema")
VALUES (1002, 10000000001, 101);

INSERT INTO "mod_seguridad"."dat_sistema_seg_rol_dat_funcionalidad" ("idfuncionalidad", "idrol", "idsistema")
VALUES (1003, 10000000001, 101);

INSERT INTO "mod_seguridad"."dat_sistema_seg_rol_dat_funcionalidad" ("idfuncionalidad", "idrol", "idsistema")
VALUES (1004, 10000000001, 101);

INSERT INTO "mod_seguridad"."dat_sistema_seg_rol_dat_funcionalidad" ("idfuncionalidad", "idrol", "idsistema")
VALUES (1005, 10000000001, 101);

INSERT INTO "mod_seguridad"."dat_sistema_seg_rol_dat_funcionalidad" ("idfuncionalidad", "idrol", "idsistema")
VALUES (10001, 10000000001, 1001);
----------------------------------------------------------------------

INSERT INTO "mod_seguridad"."dat_sistema_seg_rol_dat_funcionalidad_dat_accion" ("idaccion", "idfuncionalidad", "idrol", "idsistema")
VALUES (5, 2, 10000000001, 3);

INSERT INTO "mod_seguridad"."dat_sistema_seg_rol_dat_funcionalidad_dat_accion" ("idaccion", "idfuncionalidad", "idrol", "idsistema")
VALUES (4, 2, 10000000001, 3);

INSERT INTO "mod_seguridad"."dat_sistema_seg_rol_dat_funcionalidad_dat_accion" ("idaccion", "idfuncionalidad", "idrol", "idsistema")
VALUES (3, 2, 10000000001, 3);

INSERT INTO "mod_seguridad"."dat_sistema_seg_rol_dat_funcionalidad_dat_accion" ("idaccion", "idfuncionalidad", "idrol", "idsistema")
VALUES (2, 2, 10000000001, 3);

INSERT INTO "mod_seguridad"."dat_sistema_seg_rol_dat_funcionalidad_dat_accion" ("idaccion", "idfuncionalidad", "idrol", "idsistema")
VALUES (30, 8, 10000000001, 3);

INSERT INTO "mod_seguridad"."dat_sistema_seg_rol_dat_funcionalidad_dat_accion" ("idaccion", "idfuncionalidad", "idrol", "idsistema")
VALUES (29, 8, 10000000001, 3);

INSERT INTO "mod_seguridad"."dat_sistema_seg_rol_dat_funcionalidad_dat_accion" ("idaccion", "idfuncionalidad", "idrol", "idsistema")
VALUES (28, 8, 10000000001, 3);

INSERT INTO "mod_seguridad"."dat_sistema_seg_rol_dat_funcionalidad_dat_accion" ("idaccion", "idfuncionalidad", "idrol", "idsistema")
VALUES (71, 19, 10000000001, 6);

INSERT INTO "mod_seguridad"."dat_sistema_seg_rol_dat_funcionalidad_dat_accion" ("idaccion", "idfuncionalidad", "idrol", "idsistema")
VALUES (70, 19, 10000000001, 6);

INSERT INTO "mod_seguridad"."dat_sistema_seg_rol_dat_funcionalidad_dat_accion" ("idaccion", "idfuncionalidad", "idrol", "idsistema")
VALUES (69, 19, 10000000001, 6);

INSERT INTO "mod_seguridad"."dat_sistema_seg_rol_dat_funcionalidad_dat_accion" ("idaccion", "idfuncionalidad", "idrol", "idsistema")
VALUES (68, 19, 10000000001, 6);

INSERT INTO "mod_seguridad"."dat_sistema_seg_rol_dat_funcionalidad_dat_accion" ("idaccion", "idfuncionalidad", "idrol", "idsistema")
VALUES (67, 19, 10000000001, 6);

INSERT INTO "mod_seguridad"."dat_sistema_seg_rol_dat_funcionalidad_dat_accion" ("idaccion", "idfuncionalidad", "idrol", "idsistema")
VALUES (77, 20, 10000000001, 6);

INSERT INTO "mod_seguridad"."dat_sistema_seg_rol_dat_funcionalidad_dat_accion" ("idaccion", "idfuncionalidad", "idrol", "idsistema")
VALUES (76, 20, 10000000001, 6);

INSERT INTO "mod_seguridad"."dat_sistema_seg_rol_dat_funcionalidad_dat_accion" ("idaccion", "idfuncionalidad", "idrol", "idsistema")
VALUES (75, 20, 10000000001, 6);

INSERT INTO "mod_seguridad"."dat_sistema_seg_rol_dat_funcionalidad_dat_accion" ("idaccion", "idfuncionalidad", "idrol", "idsistema")
VALUES (74, 20, 10000000001, 6);

INSERT INTO "mod_seguridad"."dat_sistema_seg_rol_dat_funcionalidad_dat_accion" ("idaccion", "idfuncionalidad", "idrol", "idsistema")
VALUES (73, 20, 10000000001, 6);

INSERT INTO "mod_seguridad"."dat_sistema_seg_rol_dat_funcionalidad_dat_accion" ("idaccion", "idfuncionalidad", "idrol", "idsistema")
VALUES (72, 20, 10000000001, 6);

INSERT INTO "mod_seguridad"."dat_sistema_seg_rol_dat_funcionalidad_dat_accion" ("idaccion", "idfuncionalidad", "idrol", "idsistema")
VALUES (85, 20, 10000000001, 6);

INSERT INTO "mod_seguridad"."dat_sistema_seg_rol_dat_funcionalidad_dat_accion" ("idaccion", "idfuncionalidad", "idrol", "idsistema")
VALUES (86, 20, 10000000001, 6);


----------------------------------------
INSERT INTO dat_sistema_compartimentacion (idsistema, iddominio) VALUES (2, 1);
INSERT INTO dat_sistema_compartimentacion (idsistema, iddominio) VALUES (3, 1);
INSERT INTO dat_sistema_compartimentacion (idsistema, iddominio) VALUES (4, 1);
INSERT INTO dat_sistema_compartimentacion (idsistema, iddominio) VALUES (5, 1);
INSERT INTO dat_sistema_compartimentacion (idsistema, iddominio) VALUES (6, 1);
INSERT INTO dat_sistema_compartimentacion (idsistema, iddominio) VALUES (7, 1);
INSERT INTO dat_sistema_compartimentacion (idsistema, iddominio) VALUES (101, 1);
INSERT INTO dat_sistema_compartimentacion (idsistema, iddominio) VALUES (1001, 1);
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
INSERT INTO dat_funcionalidad_compartimentacion (idfuncionalidad, iddominio) VALUES (10, 1);
INSERT INTO dat_funcionalidad_compartimentacion (idfuncionalidad, iddominio) VALUES (11, 1);
INSERT INTO dat_funcionalidad_compartimentacion (idfuncionalidad, iddominio) VALUES (12, 1);
INSERT INTO dat_funcionalidad_compartimentacion (idfuncionalidad, iddominio) VALUES (13, 1);
INSERT INTO dat_funcionalidad_compartimentacion (idfuncionalidad, iddominio) VALUES (14, 1);
INSERT INTO dat_funcionalidad_compartimentacion (idfuncionalidad, iddominio) VALUES (15, 1);
INSERT INTO dat_funcionalidad_compartimentacion (idfuncionalidad, iddominio) VALUES (16, 1);
INSERT INTO dat_funcionalidad_compartimentacion (idfuncionalidad, iddominio) VALUES (17, 1);
INSERT INTO dat_funcionalidad_compartimentacion (idfuncionalidad, iddominio) VALUES (18, 1);
INSERT INTO dat_funcionalidad_compartimentacion (idfuncionalidad, iddominio) VALUES (19, 1);
INSERT INTO dat_funcionalidad_compartimentacion (idfuncionalidad, iddominio) VALUES (20, 1);
INSERT INTO dat_funcionalidad_compartimentacion (idfuncionalidad, iddominio) VALUES (21, 1);
INSERT INTO dat_funcionalidad_compartimentacion (idfuncionalidad, iddominio) VALUES (22, 1);
INSERT INTO dat_funcionalidad_compartimentacion (idfuncionalidad, iddominio) VALUES (23, 1);
INSERT INTO dat_funcionalidad_compartimentacion (idfuncionalidad, iddominio) VALUES (24, 1);
INSERT INTO dat_funcionalidad_compartimentacion (idfuncionalidad, iddominio) VALUES (25, 1);
INSERT INTO dat_funcionalidad_compartimentacion (idfuncionalidad, iddominio) VALUES (1001, 1);
INSERT INTO dat_funcionalidad_compartimentacion (idfuncionalidad, iddominio) VALUES (1002, 1);
INSERT INTO dat_funcionalidad_compartimentacion (idfuncionalidad, iddominio) VALUES (1003, 1);
INSERT INTO dat_funcionalidad_compartimentacion (idfuncionalidad, iddominio) VALUES (1004, 1);
INSERT INTO dat_funcionalidad_compartimentacion (idfuncionalidad, iddominio) VALUES (1005, 1);
INSERT INTO dat_funcionalidad_compartimentacion (idfuncionalidad, iddominio) VALUES (10001, 1);


----------------------------------------------------------------------------------------------------
INSERT INTO dat_accion_compartimentacion (idaccion, iddominio) VALUES (2, 1);
INSERT INTO dat_accion_compartimentacion (idaccion, iddominio) VALUES (3, 1);
INSERT INTO dat_accion_compartimentacion (idaccion, iddominio) VALUES (4, 1);
INSERT INTO dat_accion_compartimentacion (idaccion, iddominio) VALUES (5, 1);
INSERT INTO dat_accion_compartimentacion (idaccion, iddominio) VALUES (6, 1);
INSERT INTO dat_accion_compartimentacion (idaccion, iddominio) VALUES (7, 1);
INSERT INTO dat_accion_compartimentacion (idaccion, iddominio) VALUES (8, 1);
INSERT INTO dat_accion_compartimentacion (idaccion, iddominio) VALUES (9, 1);
INSERT INTO dat_accion_compartimentacion (idaccion, iddominio) VALUES (10, 1);
INSERT INTO dat_accion_compartimentacion (idaccion, iddominio) VALUES (11, 1);
INSERT INTO dat_accion_compartimentacion (idaccion, iddominio) VALUES (12, 1);
INSERT INTO dat_accion_compartimentacion (idaccion, iddominio) VALUES (13, 1);
INSERT INTO dat_accion_compartimentacion (idaccion, iddominio) VALUES (14, 1);
INSERT INTO dat_accion_compartimentacion (idaccion, iddominio) VALUES (15, 1);
INSERT INTO dat_accion_compartimentacion (idaccion, iddominio) VALUES (16, 1);
INSERT INTO dat_accion_compartimentacion (idaccion, iddominio) VALUES (17, 1);
INSERT INTO dat_accion_compartimentacion (idaccion, iddominio) VALUES (18, 1);
INSERT INTO dat_accion_compartimentacion (idaccion, iddominio) VALUES (19, 1);
INSERT INTO dat_accion_compartimentacion (idaccion, iddominio) VALUES (20, 1);
INSERT INTO dat_accion_compartimentacion (idaccion, iddominio) VALUES (21, 1);
INSERT INTO dat_accion_compartimentacion (idaccion, iddominio) VALUES (22, 1);
INSERT INTO dat_accion_compartimentacion (idaccion, iddominio) VALUES (23, 1);
INSERT INTO dat_accion_compartimentacion (idaccion, iddominio) VALUES (24, 1);
INSERT INTO dat_accion_compartimentacion (idaccion, iddominio) VALUES (25, 1);
INSERT INTO dat_accion_compartimentacion (idaccion, iddominio) VALUES (26, 1);
INSERT INTO dat_accion_compartimentacion (idaccion, iddominio) VALUES (27, 1);
INSERT INTO dat_accion_compartimentacion (idaccion, iddominio) VALUES (28, 1);
INSERT INTO dat_accion_compartimentacion (idaccion, iddominio) VALUES (29, 1);
INSERT INTO dat_accion_compartimentacion (idaccion, iddominio) VALUES (30, 1);
INSERT INTO dat_accion_compartimentacion (idaccion, iddominio) VALUES (31, 1);
INSERT INTO dat_accion_compartimentacion (idaccion, iddominio) VALUES (32, 1);
INSERT INTO dat_accion_compartimentacion (idaccion, iddominio) VALUES (33, 1);
INSERT INTO dat_accion_compartimentacion (idaccion, iddominio) VALUES (34, 1);
INSERT INTO dat_accion_compartimentacion (idaccion, iddominio) VALUES (35, 1);
INSERT INTO dat_accion_compartimentacion (idaccion, iddominio) VALUES (36, 1);
INSERT INTO dat_accion_compartimentacion (idaccion, iddominio) VALUES (37, 1);
INSERT INTO dat_accion_compartimentacion (idaccion, iddominio) VALUES (38, 1);
INSERT INTO dat_accion_compartimentacion (idaccion, iddominio) VALUES (39, 1);
INSERT INTO dat_accion_compartimentacion (idaccion, iddominio) VALUES (40, 1);
INSERT INTO dat_accion_compartimentacion (idaccion, iddominio) VALUES (41, 1);
INSERT INTO dat_accion_compartimentacion (idaccion, iddominio) VALUES (42, 1);
INSERT INTO dat_accion_compartimentacion (idaccion, iddominio) VALUES (43, 1);
INSERT INTO dat_accion_compartimentacion (idaccion, iddominio) VALUES (44, 1);
INSERT INTO dat_accion_compartimentacion (idaccion, iddominio) VALUES (45, 1);
INSERT INTO dat_accion_compartimentacion (idaccion, iddominio) VALUES (46, 1);
INSERT INTO dat_accion_compartimentacion (idaccion, iddominio) VALUES (47, 1);
INSERT INTO dat_accion_compartimentacion (idaccion, iddominio) VALUES (48, 1);
INSERT INTO dat_accion_compartimentacion (idaccion, iddominio) VALUES (49, 1);
INSERT INTO dat_accion_compartimentacion (idaccion, iddominio) VALUES (50, 1);
INSERT INTO dat_accion_compartimentacion (idaccion, iddominio) VALUES (51, 1);
INSERT INTO dat_accion_compartimentacion (idaccion, iddominio) VALUES (52, 1);
INSERT INTO dat_accion_compartimentacion (idaccion, iddominio) VALUES (53, 1);
INSERT INTO dat_accion_compartimentacion (idaccion, iddominio) VALUES (54, 1);
INSERT INTO dat_accion_compartimentacion (idaccion, iddominio) VALUES (55, 1);
INSERT INTO dat_accion_compartimentacion (idaccion, iddominio) VALUES (56, 1);
INSERT INTO dat_accion_compartimentacion (idaccion, iddominio) VALUES (57, 1);
INSERT INTO dat_accion_compartimentacion (idaccion, iddominio) VALUES (58, 1);
INSERT INTO dat_accion_compartimentacion (idaccion, iddominio) VALUES (59, 1);
INSERT INTO dat_accion_compartimentacion (idaccion, iddominio) VALUES (60, 1);
INSERT INTO dat_accion_compartimentacion (idaccion, iddominio) VALUES (61, 1);
INSERT INTO dat_accion_compartimentacion (idaccion, iddominio) VALUES (62, 1);
INSERT INTO dat_accion_compartimentacion (idaccion, iddominio) VALUES (63, 1);
INSERT INTO dat_accion_compartimentacion (idaccion, iddominio) VALUES (64, 1);
INSERT INTO dat_accion_compartimentacion (idaccion, iddominio) VALUES (65, 1);
INSERT INTO dat_accion_compartimentacion (idaccion, iddominio) VALUES (66, 1);
INSERT INTO dat_accion_compartimentacion (idaccion, iddominio) VALUES (67, 1);
INSERT INTO dat_accion_compartimentacion (idaccion, iddominio) VALUES (68, 1);
INSERT INTO dat_accion_compartimentacion (idaccion, iddominio) VALUES (69, 1);
INSERT INTO dat_accion_compartimentacion (idaccion, iddominio) VALUES (70, 1);
INSERT INTO dat_accion_compartimentacion (idaccion, iddominio) VALUES (71, 1);
INSERT INTO dat_accion_compartimentacion (idaccion, iddominio) VALUES (72, 1);
INSERT INTO dat_accion_compartimentacion (idaccion, iddominio) VALUES (73, 1);
INSERT INTO dat_accion_compartimentacion (idaccion, iddominio) VALUES (74, 1);
INSERT INTO dat_accion_compartimentacion (idaccion, iddominio) VALUES (75, 1);
INSERT INTO dat_accion_compartimentacion (idaccion, iddominio) VALUES (76, 1);
INSERT INTO dat_accion_compartimentacion (idaccion, iddominio) VALUES (77, 1);
INSERT INTO dat_accion_compartimentacion (idaccion, iddominio) VALUES (78, 1);
INSERT INTO dat_accion_compartimentacion (idaccion, iddominio) VALUES (79, 1);
INSERT INTO dat_accion_compartimentacion (idaccion, iddominio) VALUES (80, 1);
INSERT INTO dat_accion_compartimentacion (idaccion, iddominio) VALUES (81, 1);
INSERT INTO dat_accion_compartimentacion (idaccion, iddominio) VALUES (82, 1);
INSERT INTO dat_accion_compartimentacion (idaccion, iddominio) VALUES (83, 1);
INSERT INTO dat_accion_compartimentacion (idaccion, iddominio) VALUES (84, 1);
INSERT INTO dat_accion_compartimentacion (idaccion, iddominio) VALUES (85, 1);
INSERT INTO dat_accion_compartimentacion (idaccion, iddominio) VALUES (86, 1);
INSERT INTO dat_accion_compartimentacion (idaccion, iddominio) VALUES (1001, 1);
-----------------------------------------------------------------------------------
INSERT INTO seg_compartimentacionroles (idrol, iddominio) VALUES (10000000001, 1);
-----------------------------------------------------------------------------------
INSERT INTO seg_compartimentacionusuario (idusuario, iddominio) VALUES (1000000001, 1);
-----------------------------------------------------------------------------------
INSERT INTO mod_seguridad.seg_rol_nom_dominio(idrol, iddominio) VALUES (10000000001, 1);

--fin de la transaccion
COMMIT;
