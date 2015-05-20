Ext.define('estudianteGrid', {
    extend: 'Ext.grid.GridPanel',
    alias: 'widget.alumno_grid',
    id: 'alumno_grid',
    initComponent: function () {
        var idalumno = 0;
        var id_alumnodatos = 0;
        var reporte = 0;

        //        Modo de selección del grid de estudiantes
        var sm = new Ext.selection.RowModel({
            mode: 'MULTI'
        });

//        BOTONES

        var btnAdicionarEstudiante = Ext.create('Ext.Button', {
            id: 'btnAgrEstudiante',
            text: perfil.etiquetas.lbBtnAdicionar,
            icon: perfil.dirImg + 'adicionar.png',
            iconCls: 'btn',
            hidden: true,
            handler: function () {
                new estudianteForm({
                    store: stGpEstudiante,
                    mod:false
                }).show();
                if (sm && sm.count() > 1) {
                    btnModificarEstudiante.disable();
                    btnEliminarEstudiante.disable();
                }
            }
        });

        var btnModificarEstudiante = Ext.create('Ext.Button', {
            id: 'btnModEstudiante',
            text: perfil.etiquetas.lbBtnModificar,
            disabled: true,
            hidden: true,
            icon: perfil.dirImg + 'modificar.png',
            iconCls: 'btn',
            handler: function () {
                new estudianteForm({
                    store: stGpEstudiante,
                    alumno: sm.getSelection()[0],
                    idalumno: idalumno,
                    idalumnodatos: id_alumnodatos,
                    mod:true
                }).show();

                if (sm && sm.count() > 1) {
                    btnModificarEstudiante.disable();
                    btnEliminarEstudiante.disable();
                }
            }
        });

        var btnEliminarEstudiante = Ext.create('Ext.Button', {
            id: 'btnEliEstudiante',
            text: perfil.etiquetas.lbBtnEliminar,
            disabled: true,
            hidden: true,
            icon: perfil.dirImg + 'eliminar.png',
            iconCls: 'btn',
            handler: function () {
                EliminarEstudiante();
            }
        });

        var btnReportesEstudiantes = Ext.create('Ext.Button', {
            id: 'btnReportesEstudiantes',
            text: perfil.etiquetas.lbBtnReportes,
            disabled: true,
            hidden: true,
            icon: perfil.dirImg + 'imprimir.png',
            iconCls: 'btn',
            handler: function () {
                if (pnl1.collapsed)
                    pnl1.expand();
                else
                    pnl1.collapse();
            }
        });
        var btnEnviarMail = Ext.create('Ext.Button', {
            id: 'btnEnviarMail',
            text: perfil.etiquetas.lbBtnMail,
            disabled: true,
            hidden: false,
            icon: perfil.dirImg + 'comentar.png',
            iconCls: 'btn',
            handler: function () {
                new sendMailWin({
                    sm: sm
                }).show()
            }
        });
        var btnChangePassw = Ext.create('Ext.Button', {
            id: 'btnChangePassw',
            text: perfil.etiquetas.lbBtnChangePassw,
            disabled: true,
            hidden: false,
            icon: perfil.dirImg + 'cambiarcontrasena.png',
            iconCls: 'btn',
            handler: function () {
                new changePassw({
                    sm: sm
                }).show()
            }
        });
//        CARGAR ACCIONES PERMITIDAS
        UCID.portal.cargarAcciones(window.parent.idFuncionalidad);

//        GESTIÓN DE ESTUDIANTES

//        Model para el store de estudiantes
        Ext.define('EstudianteModel', {
            extend: 'Ext.data.Model',
            fields: ['alumno', 'id_alumnodatos', 'idusuario', 'idusuarioasig', 'idestadocivil', 'idprovincia', 'idtipoalumno', 'idsectorciudad',
                'iduniversidad', 'idcanton', 'idcolegio', 'idpensumenfasismateriatipo', 'idpensum', 'idenfasis', 'idprovinciauniv',
                'idprovinciacoleg', 'idcarrera', 'idestructura', 'idperiododocente', 'pensum', 'nombre', 'apellidos', 'fecha', 'estadocivil',
                'sexo', 'provincia', 'tipoalumno', 'sectorciudad', 'universidad', 'canton', 'colegio', 'codigo', 'fec_nacimiento', 'domicilio',
                'telefono', 'celular', 'annoc', 'periodo', 'fec_grad', 'lugar_nacimiento', 'nacionalidad', 'pais', 'religion', 'e_mail', 'e_mail2',
                'cedula', 'pasaporte', 'enfasis', 'provcoleg', 'provuniv', 'tipobeca', 'idtipobeca', 'cant_hijos', 'estado', 'carrera', 'facultad',
                'empresa_trab', 'cargo_empresa', 'ciudad_trabajo', 'direccion_trab', 'telefono_trab', 'nombre_padre', 'apellidos_padre',
                'direccion_padre', 'telefono_padre', 'profesion_padre', 'cargo_padre', 'empresa_padre', 'nombre_madre', 'apellidos_madre',
                'direccion_madre', 'telefono_madre', 'profesion_madre', 'cargo_madre', 'empresa_madre', 'anno', 'iddiscapacidad',
                'discapacidad', 'idtipodiscapacidad', 'tipodiscapacidad', 'idgradodiscapacidad', 'gradodiscapacidad', 'idetnia', 'etnia',
                'idpais', 'nombrepais', 'codigopais', 'siglas']
        });

//        Store para el grid de estudiantes
        var stGpEstudiante = Ext.create('Ext.data.ArrayStore', {
            model: 'EstudianteModel',
            autoLoad: true,
            storeId: 'idStoreAlumnos',
            pageSize: 24,
            remoteFilter: true,
            proxy: {
                type: 'ajax',
                api: {
                    read: 'cargarAlumnosFull'
                },
                actionMethods: { //Esta Linea es necesaria para el metodo de llamada POST o GET
                    read: 'POST'
                },
                reader: {
                    root: 'datos',
                    totalProperty: 'cantidad',
                    successProperty: 'success',
                    messageProperty: 'mensaje'
                }
            }
        });

        sm.on('selectionchange', function (sel, selectedRecord) {
            if (selectedRecord) {
                if (selectedRecord.length > 0)
                if (selectedRecord[0].get('idtipoalumno') != 1000009) {
                    if (selectedRecord.length === 1) {
                        btnModificarEstudiante.enable();
                        btnReportesEstudiantes.enable();
                        btnEnviarMail.enable();
                        btnChangePassw.enable();
                        btnEliminarEstudiante.enable();
                    } else if (selectedRecord.length > 1) {
                        btnModificarEstudiante.disable();
                        btnReportesEstudiantes.disable();
                        btnEnviarMail.enable();
                        btnChangePassw.disable();
                        btnEliminarEstudiante.enable();
                    } else {
                        btnModificarEstudiante.disable();
                        btnReportesEstudiantes.disable();
                        btnEliminarEstudiante.disable();
                    }
                } else {
                    btnModificarEstudiante.disable();
                    //btnReportesEstudiantes.disable();
                    btnEliminarEstudiante.disable();
                    //btnEnviarMail.disable();
                    if (selectedRecord.length > 1) {
                        btnReportesEstudiantes.disable();
                    }
                }

                idalumno = sm.getLastSelected().data.alumno;
                id_alumnodatos = sm.getLastSelected().data.id_alumnodatos;
            }
        });

//      Grid de estudiantes
        var GpEstudiante = new Ext.grid.GridPanel({
            store: stGpEstudiante,
            stateful: true,
            stateId: 'stateGrid',
            frame: true,
            region: 'east',
            selModel: sm,
            columns: [
                {
                    text: 'alumno',
                    flex: 1,
                    dataIndex: 'alumno',
                    hidden: true,
                    hideable: false
                },
                {
                    text: 'id_alumnodatos',
                    flex: 1,
                    dataIndex: 'id_alumnodatos',
                    hidden: true,
                    hideable: false
                },
                {
                    text: 'idusuario',
                    flex: 1,
                    dataIndex: 'idusuario',
                    hidden: true,
                    hideable: false
                },
                {
                    text: 'idestadocivil',
                    flex: 1,
                    dataIndex: 'idestadocivil',
                    hidden: true,
                    hideable: false
                },
                {
                    text: 'idprovincia',
                    flex: 1,
                    dataIndex: 'idprovincia',
                    hidden: true,
                    hideable: false
                },
                {
                    text: 'idtipoalumno',
                    flex: 1,
                    dataIndex: 'idtipoalumno',
                    hidden: true,
                    hideable: true
                },
                {
                    text: 'idsectorciudad',
                    flex: 1,
                    dataIndex: 'idsectorciudad',
                    hidden: true,
                    hideable: false
                },
                {
                    text: 'idperiododocente',
                    flex: 1,
                    dataIndex: 'idperiododocente',
                    hidden: true,
                    hideable: false
                },
                {
                    text: 'iduniversidad',
                    flex: 1,
                    dataIndex: 'iduniversidad',
                    hidden: true,
                    hideable: false
                },
                {
                    text: 'idcanton',
                    flex: 1,
                    dataIndex: 'idcanton',
                    hidden: true,
                    hideable: false
                },
                {
                    text: 'idcolegio',
                    flex: 1,
                    dataIndex: 'idcolegio',
                    hidden: true,
                    hideable: false
                },
                {
                    text: 'idpensumenfasismateriatipo',
                    flex: 1,
                    dataIndex: 'idpensumenfasismateriatipo',
                    hidden: true,
                    hideable: false
                },
                {
                    text: 'idpensum',
                    flex: 1,
                    dataIndex: 'idpensum',
                    hidden: true,
                    hideable: false
                },
                {
                    text: 'pensum',
                    flex: 1,
                    dataIndex: 'pensum',
                    hidden: true
                },
                {
                    text: 'idusuarioasig',
                    flex: 1,
                    dataIndex: 'idusuarioasig',
                    hidden: true,
                    hideable: false
                },
                {
                    text: 'idEnfasis',
                    flex: 1,
                    dataIndex: 'idenfasis',
                    hidden: true,
                    hideable: false
                },
                {
                    text: 'idcarrera',
                    flex: 1,
                    dataIndex: 'idcarrera',
                    hidden: true,
                    hideable: false
                },
                {
                    text: 'idestructura',
                    flex: 1,
                    dataIndex: 'idestructura',
                    hidden: true,
                    hideable: false
                },
                {
                    text: 'idetnia',
                    flex: 1,
                    dataIndex: 'idetnia',
                    hidden: true,
                    hideable: false
                },
                {
                    text: 'iddiscapacidad',
                    flex: 1,
                    dataIndex: 'iddiscapacidad',
                    hidden: true,
                    hideable: false
                },
                {
                    text: 'idtipodiscapacidad',
                    flex: 1,
                    dataIndex: 'idtipodiscapacidad',
                    hidden: true,
                    hideable: false
                },
                {
                    text: 'idgradodiscapacidad',
                    flex: 1,
                    dataIndex: 'idgradodiscapacidad',
                    hidden: true,
                    hideable: false
                },
                {
                    text: 'idtipobeca',
                    flex: 1,
                    dataIndex: 'idtipobeca',
                    hidden: true,
                    hideable: false
                },
                {
                    text: perfil.etiquetas.lbHdrGpEApellidos,
                    flex: 1.5,
//                    width: 150,
                    dataIndex: 'apellidos'
                },
                {
                    text: perfil.etiquetas.lbHdrGpENombre,
                    flex: 1.5,
//                    width: 150,
                    dataIndex: 'nombre'
                },
                {
                    text: perfil.etiquetas.lbHdrGpECodigo,
                    //flex: 1,
                    width: 100,
                    dataIndex: 'codigo'
                },
                {
                    text: perfil.etiquetas.lbHdrGpETipoAl,
                    flex: 1.5,
//                    width: 200,
                    dataIndex: 'tipoalumno'
                },
                {
                    text: perfil.etiquetas.lbHdrGpETipoBeca,
                    flex: 1,
                    dataIndex: 'tipobeca',
                    hidden: true
                },
                {
                    text: perfil.etiquetas.lbHdrGpEGenero,
                    //flex: 1,
                    width: 70,
                    dataIndex: 'sexo',
                    hidden: true
                },
                {
                    text: 'Fecha',
                    flex: 1,
                    dataIndex: 'fecha',
                    hidden: true
                },
                {
                    text: perfil.etiquetas.lbHdrGpEEstadoCivil,
                    flex: 1,
                    dataIndex: 'estadocivil',
                    hidden: true
                },
                {
                    text: perfil.etiquetas.lbHdrGpEProvincia,
                    flex: 1,
                    dataIndex: 'provincia',
                    hidden: true
                },
                {
                    text: 'Sector ciudad',
                    flex: 1,
                    dataIndex: 'sectorciudad',
                    hidden: true
                },
                {
                    text: 'Período',
                    flex: 1,
                    dataIndex: 'periodo',
                    hidden: true
                },
                {
                    text: 'Fecha graduación',
                    flex: 1,
                    dataIndex: 'fec_grad',
                    hidden: true
                },
                {
                    text: 'Año',
                    flex: 1,
                    dataIndex: 'annoc',
                    hidden: true
                },
                {
                    text: 'Universidad',
                    flex: 1,
                    dataIndex: 'universidad',
                    hidden: true
                },
                {
                    text: 'Colegio',
                    flex: 1,
                    dataIndex: 'colegio',
                    hidden: true
                },
                {
                    text: 'Prov. colegio',
                    flex: 1,
                    dataIndex: 'provcoleg',
                    hidden: true
                },
                {
                    text: 'Prov. universidad',
                    flex: 1,
                    dataIndex: 'provuniv',
                    hidden: true
                },
                {
                    text: perfil.etiquetas.lbHdrGpEFecNac,
                    //flex: 1,
                    width: 100,
                    dataIndex: 'fec_nacimiento',
                    hidden: true,
                    type: 'date'
                },
                {
                    text: 'Domicilio',
                    flex: 1,
                    dataIndex: 'domicilio',
                    hidden: true
                },
                {
                    text: 'Teléfono',
                    flex: 1,
                    dataIndex: 'telefono',
                    hidden: true
                },
                {
                    text: 'Celular',
                    flex: 1,
                    dataIndex: 'celular',
                    hidden: true
                },
                {
                    text: 'Lugar nacimiento',
                    flex: 1,
                    dataIndex: 'lugar_nacimiento',
                    hidden: true
                },
                {
                    text: 'Nacionalidad',
                    flex: 1,
                    dataIndex: 'nacionalidad',
                    hidden: true
                },
                {
                    text: 'País',
                    flex: 1,
                    dataIndex: 'pais',
                    hidden: true
                },
                {
                    text: 'Religión',
                    flex: 1,
                    dataIndex: 'religion',
                    hidden: true
                },
                {
                    text: 'Correo',
                    flex: 1,
                    dataIndex: 'e_mail',
                    hidden: true
                },
                {
                    text: 'Correo (alternativo)',
                    flex: 1,
                    dataIndex: 'e_mail2',
                    hidden: true
                },
                {
                    text: perfil.etiquetas.lbHdrGpECedula,
                    flex: 1,
                    dataIndex: 'cedula',
                    hidden: true
                },
                {
                    text: perfil.etiquetas.lbHdrGpEPasaporte,
                    flex: 1,
                    dataIndex: 'pasaporte',
                    hidden: true
                },
                {
                    text: perfil.etiquetas.lbHdrGpEstFac,
                    flex: 1.5,
                    dataIndex: 'facultad',
                    hidden: false
                },
                {
                    text: 'Carrera',
                    flex: 1.5,
                    dataIndex: 'carrera',
                    hidden: false
                },
                {
                    text: perfil.etiquetas.lbHdrGpEstEnfasis,
                    flex: 1.5,
                    dataIndex: 'enfasis'
                },
                {
                    text: 'Estado',
//                    flex: 1,
                    width: 55,
                    dataIndex: 'estado',
                    hidden: true
                },
                {
                    text: 'Empresa',
                    flex: 1,
                    dataIndex: 'empresa_trab',
                    hidden: true
                },
                {
                    text: 'Cargo',
                    flex: 1,
                    dataIndex: 'cargo_empresa',
                    hidden: true
                },
                {
                    text: 'Ciudad',
                    flex: 1,
                    dataIndex: 'ciudad_trabajo',
                    hidden: true
                },
                {
                    text: 'Direccion',
                    flex: 1,
                    dataIndex: 'direccion_trab',
                    hidden: true
                },
                {
                    text: 'Teléfono',
                    flex: 1,
                    dataIndex: 'telefono_trab',
                    hidden: true
                },
                {
                    text: 'Nombre padre',
                    flex: 1,
                    dataIndex: 'nombre_padre',
                    hidden: true
                },
                {
                    text: 'Apellidos padre',
                    flex: 1,
                    dataIndex: 'apellidos_padre',
                    hidden: true
                },
                {
                    text: 'Dirección padre',
                    flex: 1,
                    dataIndex: 'direccion_padre',
                    hidden: true
                },
                {
                    text: 'Teléfono padre',
                    flex: 1,
                    dataIndex: 'telefono_padre',
                    hidden: true
                },
                {
                    text: 'Profesión padre',
                    flex: 1,
                    dataIndex: 'profesion_padre',
                    hidden: true
                },
                {
                    text: 'Cargo padre',
                    flex: 1,
                    dataIndex: 'cargo_padre',
                    hidden: true
                },
                {
                    text: 'Empresa padre',
                    flex: 1,
                    dataIndex: 'empresa_padre',
                    hidden: true
                },
                {
                    text: 'Nombre madre',
                    flex: 1,
                    dataIndex: 'nombre_madre',
                    hidden: true
                },
                {
                    text: 'Apellidos madre',
                    flex: 1,
                    dataIndex: 'apellidos_madre',
                    hidden: true
                },
                {
                    text: 'Dirección madre',
                    flex: 1,
                    dataIndex: 'direccion_madre',
                    hidden: true
                },
                {
                    text: 'Teléfono madre',
                    flex: 1,
                    dataIndex: 'telefono_madre',
                    hidden: true
                },
                {
                    text: 'Profesión madre',
                    flex: 1,
                    dataIndex: 'profesion_madre',
                    hidden: true
                },
                {
                    text: 'Cargo madre',
                    flex: 1,
                    dataIndex: 'cargo_madre',
                    hidden: true
                },
                {
                    text: 'Empresa madre',
                    flex: 1,
                    dataIndex: 'empresa_madre',
                    hidden: true
                },
                {
                    text: 'Hijos',
                    flex: 1,
                    dataIndex: 'cant_hijos',
                    hidden: true
                },
                {
                    text: 'anno periodo',
                    flex: 1,
                    dataIndex: 'anno',
                    hidden: true
                },
                {
                    text: 'etnia',
                    flex: 1,
                    dataIndex: 'etnia',
                    hidden: true
                },
                {
                    text: 'discapacidad',
                    flex: 1,
                    dataIndex: 'discapacidad',
                    hidden: true
                },
                {
                    text: 'tipodiscapacidad',
                    flex: 1,
                    dataIndex: 'tipodiscapacidad',
                    hidden: true
                },
                {
                    text: 'gradodiscapacidad',
                    flex: 1,
                    dataIndex: 'gradodiscapacidad',
                    hidden: true
                },
                {
                    text: 'idpais',
                    flex: 1,
                    dataIndex: 'idpais',
                    hidden: true
                },
                {
                    text: 'nombrepais',
                    flex: 1,
                    dataIndex: 'idpais',
                    hidden: true
                }
            ],
            tbar: [btnAdicionarEstudiante, btnModificarEstudiante, btnEliminarEstudiante, btnReportesEstudiantes, btnEnviarMail, btnChangePassw,
                '->',
                {
                    xtype: 'searchfield',
                    store: stGpEstudiante,
                    width: 400,
                    fieldLabel: '<b>' + perfil.etiquetas.lbBtnBuscar + '</b>',
                    labelWidth: 40,
                    filterPropertysNames: ['nombre', 'apellidos', 'codigo', 'tipoalumno', 'cedpas']
                }],
            bbar: new Ext.PagingToolbar({
                id: 'ptbaux99',
                store: stGpEstudiante,
                displayInfo: true,
                displayMsg: perfil.etiquetas.lbMsgbbarI,
                emptyMsg: perfil.etiquetas.lbMsgbbarII
            }),
            viewConfig: {
                getRowClass: function (record, rowIndex, rowParams, store) {
                    if (record.data.estado == false)
                        return 'FilaRoja';
                    if (record.data.idtipoalumno == 1000009)
                        return 'FilaGris';
                }
            }
        });

//      Panel para el grid de estudiante
        var pnlEstudiante = Ext.create('Ext.Panel', {
            id: 'pnlEstudiante',
            frame: true,
            layout: 'fit',
            region: 'center',
            width: 500,
            items: GpEstudiante
        });

        //        Panel para los reportes
        var pnlReportesEstudiante = Ext.create('Ext.Panel', {
            id: 'pnlReportesEstudiante',
            frame: true,
            region: 'east',
            items: [
                {
                    xtype: 'button',
                    text: '<b>' + 'Certificado IECE' + '</b>',
                    width: '100%',
                    height: 25,
                    renderTo: Ext.getBody(),
                    handler: function () {
                        if (idalumno != 0) {
                            new reportesWin({
                                url: 'reportes?idreporte=1&idalumno=' + idalumno,
                                title: 'Certificado IECE',
                                panel: pnl1
                            }).show();
                        } else
                            mostrarMensaje(3, 'Debe seleccionar un alumno para ver el reporte.');
                    }
                },
                {
                    xtype: 'button',
                    text: '<b>' + 'Cuadro de promedios' + '</b>',
                    width: '100%',
                    height: 25,
                    renderTo: Ext.getBody(),
                    handler: function () {
                        if (idalumno != 0) {
                            new reportesWin({
                                url: 'reportes?idreporte=2&idalumno=' + idalumno,
                                title: 'Cuadro de promedios',
                                panel: pnl1
                            }).show();
                        } else
                            mostrarMensaje(3, 'Debe seleccionar un alumno para ver el reporte.');
                    }
                },
                {
                    xtype: 'button',
                    text: '<b>' + 'Récord académico' + '</b>',
                    width: '100%',
                    height: 25,
                    renderTo: Ext.getBody(),
                    target: '_blank',
                    handler: function () {
                        if (idalumno != 0) {
                            new reportesWin({
                                url: 'reportes?idreporte=3&idalumno=' + idalumno,
                                title: 'Récord académico',
                                panel: pnl1
                            }).show();
                        } else {
                            mostrarMensaje(3, 'Debe seleccionar un alumno para ver el reporte.');
                        }
                    }
                },
                {
                    xtype: 'button',
                    text: '<b>' + 'Récord académico Inglés' + '</b>',
                    width: '100%',
                    height: 25,
                    renderTo: Ext.getBody(),
                    target: '_blank',
                    handler: function () {
                        if (idalumno != 0) {
                            new reportesWin({
                                url: 'reportes?idreporte=4&idalumno=' + idalumno,
                                title: 'Récord académico Inglés',
                                panel: pnl1
                            }).show();
                        } else {
                            mostrarMensaje(3, 'Debe seleccionar un alumno para ver el reporte.');
                        }
                    }
                },
                {
                    xtype: 'button',
                    text: '<b>' + 'Notas y faltas' + '</b>',
                    width: '100%',
                    height: 25,
                    renderTo: Ext.getBody(),
                    handler: function () {
                        if (idalumno != 0) {
                            new reportesWin({
                                url: 'reportes?idreporte=5&idalumno=' + idalumno,
                                title: 'Notas y faltas',
                                panel: pnl1
                            }).show();
                        } else
                            mostrarMensaje(3, 'Debe seleccionar un alumno para ver el reporte.');
                    }
                },
                {
                    xtype: 'button',
                    text: '<b>' + 'Consejería académica' + '</b>',
                    width: '100%',
                    height: 25,
                    renderTo: Ext.getBody(),
                    handler: function () {
                        if (idalumno != 0) {
                            new reportesWin({
                                url: 'reportes?idreporte=6&idalumno=' + idalumno,
                                title: 'Consejería académica',
                                panel: pnl1
                            }).show();
                        } else
                            mostrarMensaje(3, 'Debe seleccionar un alumno para ver el reporte.');
                    }
                },
                {
                    xtype: 'button',
                    text: '<b>' + 'Materias convalidadas' + '</b>',
                    width: '100%',
                    height: 25,
                    renderTo: Ext.getBody(),
                    handler: function () {
                        if (idalumno != 0) {
                            new reportesWin({
                                url: 'reportes?idreporte=8&idalumno=' + idalumno,
                                title: 'Materias convalidadas',
                                panel: pnl1
                            }).show();
                        } else
                            mostrarMensaje(3, 'Debe seleccionar un alumno para ver el reporte.');
                    }
                },
                {
                    xtype: 'button',
                    height: 25,
                    text: '<b>' + 'Materias homologadas' + '</b>',
                    width: '100%',
                    renderTo: Ext.getBody(),
                    target: '_blank',
                    handler: function () {
                        if (idalumno != 0) {
                            new reportesWin({
                                url: 'reportes?idreporte=9&idalumno=' + idalumno,
                                title: 'Materias homologadas',
                                panel: pnl1
                            }).show();
                        } else {
                            mostrarMensaje(3, 'Debe seleccionar un alumno para ver el reporte.');
                        }
                    }
                },
                {
                    xtype: 'button',
                    text: '<b>' + 'Estado de cuenta' + '</b>',
                    width: '100%',
                    height: 25,
                    renderTo: Ext.getBody(),
                    handler: function () {
                        if (idalumno != 0) {
                            new reportesWin({
                                url: 'reportes?idreporte=12&idalumno=' + idalumno,
                                title: 'Estado de Cuenta',
                                panel: pnl1
                            }).show();
                        } else
                            mostrarMensaje(3, 'Debe seleccionar un alumno para ver el reporte.');
                    }
                }
            ]
        });

//        Panel para componentes
        var pnl1 = Ext.create('Ext.Panel', {
            title: '<b>' + 'REPORTES' + '</b>',
            id: 'reportes_Est',
            frame: true,
            layout: 'fit',
            region: 'east',
            collapsed: true,
            collapsible: true,
            width: 200,
            items: [pnlReportesEstudiante]
        });

//        Panel general y viewport
        var general = Ext.create('Ext.panel.Panel', {layout: 'border', items: [pnlEstudiante, pnl1]});
        var vpGestSistema = Ext.create('Ext.Viewport', {layout: 'fit', items: general});

        this.callParent();

//        FUNCIONES

//        Función para eliminar uno o varios estudiantes.
        function EliminarEstudiante() {
            mostrarMensaje(2, '¿Desea eliminar este alumno?', eliminar);
            var delMask = new Ext.LoadMask(Ext.getBody(), {
                msg: 'Eliminando alumno...'
            });

            function eliminar(btnPresionado) {
                if (btnPresionado === 'ok') {
                    var ids = new Array();
                    for (var i = 0; i < sm.getCount(); i++) {
                        ids.push(sm.getSelection()[i].raw.alumno);
                    }
                    delMask.show();
                    Ext.Ajax.request({
                        url: 'eliminarEstudiante',
                        method: 'POST',
                        params: {
                            idalumnos: Ext.JSON.encode(ids)
                        },
                        callback: function (options, success, response) {
                            responseData = Ext.decode(response.responseText);
                            delMask.disable();
                            delMask.hide();
                            if (responseData.codMsg === 1) {
                                sm.clearSelections();
                                stGpEstudiante.load();
                                btnModificarEstudiante.disable();
                                btnEliminarEstudiante.disable();
                            }
                        }
                    });
                }
            }
        };
    }
});
