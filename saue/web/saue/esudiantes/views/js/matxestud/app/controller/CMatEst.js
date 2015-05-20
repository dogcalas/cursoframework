var idperiodo = 0,
    idalumno = 0;
Ext.define('MatEst.controller.CMatEst', {
    extend: 'Ext.app.Controller',

    views: [
        'MatEst.view.matx.MatxEstLis',
        'MatEst.view.matx.MateriaxEstToolBar',
        'MatEst.view.matx.RegistroMatxEst',
        'GestNotas.view.nota.AlumnoList',
        'GestNotas.view.nota.WindowSearch',
        'MatEst.view.matx.StudentInfo',
        'GestNotas.view.nota.reportesWinF'
    ],

    stores: [
        'MatEst.store.Cursos',
        'MatEst.store.Horarios',
        'MatEst.store.Registro',
        'GestNotas.store.Cursos',
        'GestNotas.store.Alumnos',
        'MatEst.store.Fac',
        'MatEst.store.Periodos',
        'MatEst.store.Annos'
    ],

    models: [
        'GestNotas.model.Alumnos',
        'MatEst.model.Cursos',
        'MatEst.model.Horario',
        'MatEst.model.Registro',
        'GestNotas.model.Periodos',
        'GestNotas.model.Cursos',
        'GestNotas.model.Annos',
        'MatEst.model.Fac'
    ],
    refs: [
        {ref: 'list', selector: 'matxestlis'},//cursos disponibles
        {ref: 'alist', selector: 'alumnolist'},
        {ref: 'rmlist', selector: 'regmatxest'},//cursos registrados
        {ref: 'tblist', selector: 'tbmateriaxest'},
        {ref: 'anno_combo', selector: 'combo[id=anno]'},
        {ref: 'windowsearch', selector: 'windowsearch'}
    ],

    init: function () {
        this.control({
            'regmatxest button[action=buscar]': {
                click: this.buscar
            },
            'regmatxest button[action=imprimir]': {
                click: this.imprimir
            },
            'tbmateriaxest combobox[id=periodo]': {
                select: function (combo) {
                    this.cargarCursos(combo);
                    this.cargarHorarios(combo);
                    this.loadRegistro(combo);
                    idperiodo = combo.getValue();
                },
                change: function (combo) {
                    this.cargarCursos(combo);
                    this.cargarHorarios(combo);
                    idperiodo = combo.getValue();
                }
            },
            'tbmateriaxest combobox[id=anno]': {
                select: this.updateStore,
                change: this.updateStore
            },
            'tbmateriaxest combobox[id=idfacfiltro]': {
                select: this.cargarCursos,
                change: this.cargarCursos
            },
            'windowsearch button[action=aceptar]': {
                click: this.cargarCursos
            },
            'alumnolist': {
                celldblclick: function () {
                    var button = this.getWindowsearch().down('button[action=aceptar]');
                    this.cargarCursos(button);
                }
            },
            'regmatxest button[action=registrar]': {
                click: this.registroclick
            },
            'matxestlis': {
                select: this.enableRegistro,
                drop: this.registroclick,
                beforedrop: this.registroclick,
                deselect: this.desableRegistro,
                afterrender: function () {
                    this.buscar();
                }
            },
            'regmatxest ': {
                select: this.enableeliminar,
                beforedrop: this.registroclick,
                drop: this.registroclick,
                deselect: this.desableeliminar
            },
            'regmatxest button[action=eliminar]': {
                click: this.eliminar
            }
        });
    },

    imprimir: function (button) {
        Ext.widget('reportes_win',
            {
                url: '../gestestudiantes/reportes?idreporte=18&idalumno=' + idalumno + '&idperiodo=' + idperiodo,
                title: 'REGISTRO DE ALUMNO'
            }).show();
    },
    cargarHorarios: function (combo, eOp) {
        Ext.getCmp('idhorariofiltro').getStore().load({params: {idperiodo: Ext.getCmp('periodo').getValue()}});
    },
    enableRegistro: function () {
        var pag = Ext.getCmp('reg').enable();
    },
    desableRegistro: function () {
        var pag = Ext.getCmp('reg').setDisabled(true);
    },
    enableeliminar: function () {
        var pag = Ext.getCmp('delboton').setDisabled(false);
    },
    desableeliminar: function () {
        var pag = Ext.getCmp('delboton').setDisabled(true);
    },
    registroclick: function () {
        var me = this;
        var record = me.getList().getSelectionModel().getSelection()[0].data;

        mostrarMensaje(
            2,
            perfil.etiquetas.lbMsgRegistrar,
            function (btn, text) {
                if (btn == 'ok') {
                    me.registrar(record.idcurso, record.idprofesor, record.cupo, record.idhorario, record.idaula, record.idmateria, me.idenfasis, Ext.getCmp('periodo').getValue(), me.idt, me.idpensum);
                    me.getRmlist().getStore().load({
                        params: {
                            idalumno: me.idt,
                            periodo: Ext.getCmp('periodo').getValue()
                        }
                    });
                    me.getList().getStore().load({
                        params: {
                            idalumno: me.idt,
                            periodo: Ext.getCmp('periodo').getValue()
                        }
                    });
                }
            });
    },

    registrar: function (idcurso, idprofesor, cupo, idhorario, idaula, idmateria, idenfasis, idperiododocente, idalumno, idpensum) {
        var me = this;
        Ext.Ajax.request({
            url: 'saveRegistro',
            method: 'POST',
            params: {
                idcurso: idcurso,
                idprofesor: idprofesor,
                cupo: cupo,
                idhorario: idhorario,
                idaula: idaula,
                idmateria: idmateria,
                idenfasis: idenfasis,
                idperiododocente: idperiododocente,
                idalumno: idalumno,
                idpensum: idpensum
            },
            callback: function (options, success, response) {

                if (response.responseText === 'pregunta') {
                    mostrarMensaje(2,
                        perfil.etiquetas.lbMsgRegistrarCorrequisito,

                        function (btn, text) {
                            if (btn == 'ok') {
                                me.aceptar(idcurso, idprofesor, cupo, idhorario, idaula, idmateria, idenfasis, idperiododocente, idalumno, idpensum);
                                me.desableRegistro();
                            }
                        }
                    )
                }
                else {
                    me.getRmlist().getStore().load({
                        params: {
                            idalumno: me.idt,
                            periodo: Ext.getCmp('periodo').getValue()
                        }
                    })
                    me.getList().getStore().load();
                    responseData = Ext.decode(response.responseText);
                    me.desableRegistro();
                }
            }
        });
    },

    aceptar: function (idcurso, idprofesor, cupo, idhorario, idaula, idmateria, idenfasis, idperiododocente, idalumno, idpensum) {
        var me = this;
        Ext.Ajax.request({
            url: 'save',
            method: 'POST',
            params: {
                idcurso: idcurso,
                idprofesor: idprofesor,
                cupo: cupo,
                idhorario: idhorario,
                idaula: idaula,
                idmateria: idmateria,
                idenfasis: idenfasis,
                idperiododocente: idperiododocente,
                idalumno: idalumno,
                idpensum: idpensum
            },
            callback: function (options, success, response) {
                me.getRmlist().getStore().load({params: {idalumno: me.idt, periodo: Ext.getCmp('periodo').getValue()}})
                me.getList().getStore().load();
                me.enableregistro();
                responseData = Ext.decode(response.responseText);
            }
        });
    },

    buscar: function (button) {
        var view = Ext.widget('windowsearch', {who: 'alumnolist', height: 400});
    },
    eliminar: function () {
        var me = this,
            record = me.getRmlist().getSelectionModel().getSelection()[0].data;
        //console.log(record);
        mostrarMensaje(
            2,
            perfil.etiquetas.lbMsgEliminar,
            function (btn, text) {
                if (btn == 'ok') {
                    me.eliminarRegistro(record.idmateria, me.idt, record.idcurso);
                    me.desableeliminar();
                }
            }
        )
    },

    eliminarRegistro: function (record, idt, idcurso) {
        var me = this;
        Ext.Ajax.request({
            url: 'delRegistro',
            method: 'POST',
            params: {idmateria: record, idt: idt, idcurso: idcurso, idperiododocente: Ext.getCmp('periodo').getValue()},
            callback: function (options, success, response) {

                if (response.responseText === 'correquisitos') {
                    mostrarMensaje(2,
                        perfil.etiquetas.lbMsgEliminarCorrequisito,
                        function (btn, text) {
                            if (btn == 'ok') {
                                me.eliminarCo(record, me.idt);
                                me.desableRegistro();
                            }
                        }
                    )
                }
                else if (response) {
                    responseData = Ext.decode(response.responseText);
                    me.getRmlist().getStore().removeAll();
                    me.getRmlist().getStore().load({
                        params: {
                            idalumno: me.idt,
                            periodo: Ext.getCmp('periodo').getValue()
                        }
                    });
                    me.getList().getStore().load();
                }
            }
        });
    },

    eliminarCo: function (idmateria, idalumno) {
        var me = this;
        Ext.Ajax.request({
            url: 'eliminarCorrequisitos',
            method: 'POST',
            params: {idmateria: idmateria, idalumno: idalumno},
            callback: function (options, success, response) {
                me.getRmlist().getStore().load();
                me.getList().getStore().load();
                response = Ext.decode(response.responseText);
            }
        });
    },

    enable: function () {
        Ext.getCmp('paginator').enable();
    },

    enableregistro: function () {
        Ext.getCmp('paginador').enable();
        Ext.getCmp('idhorariofiltro').enable();
        Ext.getCmp('anno').enable();
        Ext.getCmp('idfacfiltro').enable();
        Ext.getCmp('periodo').enable();
    },
    updateStore: function (combo, nv) {
        Ext.getCmp('periodo').getStore().load({params: {anno: Ext.getCmp('anno').getValue()}});

        Ext.getCmp('periodo').getStore().on('load', function (store) {
            if (store.count() > 0) {
                Ext.getCmp('periodo').select(store.getAt(0).data.idperiododocente);
            }
        });
    },

    mostrarError: function (type) {
        mostrarMensaje(1, perfil.etiquetas.lbMsgError + " " + type + ".")
    },

    comboEvents: function (combo, eOp) {
        combo.getStore().on('beforeload', function (store) {
            store.getProxy().extraParams = {anno: Ext.getCmp('anno').getValue()};
        });
    },

    cargarCursos: function (button) {
        var win = button.up('window'),
            me = this;
        if (win) {
            record = me.getAlist().getSelectionModel().getSelection()[0];

            if (record) {
                me.getList().reconfigure(me.getList().getStore(), [
                        {dataIndex: 'idalumno', hidden: true, hideable: false},
                        {dataIndex: 'idcurso', hidden: true},
                        {dataIndex: 'idaula', hidden: true},
                        {dataIndex: 'idhorario', hidden: true},
                        {dataIndex: 'idprofesor', hidden: true},
                        {dataIndex: 'idmateria', hidden: true},
                        {header: 'Aula', dataIndex: 'aula', flex: 1},
                        {header: 'Materia', dataIndex: 'materia', flex: 1},
                        {header: 'Profesor', dataIndex: 'profesor', flex: 1},
                        {header: 'Horario', dataIndex: 'horario', flex: 1},
                        //{header: 'LV', dataIndex: 'lv', flex: 1},
                        {header: 'Cupo', dataIndex: 'cupo', flex: 1 / 2},
                        {header: 'Par', dataIndex: 'par_curs', flex: 1 / 2}
                    ]
                );
                me.typo = 'alumno';
                me.idt = record.data.idalumno;
                me.idenfasis = record.data.idenfasis;
                me.idfacultad = record.data.idfacultad;
                me.idpensum = record.data.idpensum;
                me.getList().getStore().clearFilter(true);
                idalumno = record.data.idalumno;

                me.getList().getStore().getProxy().extraParams = {
                    idt: me.idt,
                    idenfasis: me.idenfasis,
                    idfacultad: record.data.idfacultad,
                    idcarrera: record.data.idcarrera,
                    idperiododocente: Ext.getCmp('periodo').getValue(),
                    val: Ext.getCmp('idfacfiltro').getValue(),
                    idpensum: record.data.idpensum
                };

                me.getList().getStore().load();

                me.getRmlist().getStore().getProxy().extraParams = {
                    idalumno: me.idt,
                    periodo: Ext.getCmp('periodo').getValue()
                };
                me.loadRegistro();
                if (me.idt) {
                    me.enableregistro();
                }

                Ext.getCmp('studentCodigo').setFieldLabel('Código');
                Ext.getCmp('studentCodigo').setFieldLabel('Cédula');
                Ext.getCmp('studentNombre').setFieldLabel('Nombre');
                Ext.getCmp('studentFacultad').setFieldLabel('Facultad');
                Ext.getCmp('studentCarrera').setFieldLabel('Carrera');
                Ext.getCmp('studentItinerario').setFieldLabel('Itinerario');

                Ext.getCmp('studentCodigo').setValue("<b>" + record.data.codigo + "</b>");
                Ext.getCmp('studentNombre').setValue("<b>" + record.data.nombre + " " + record.data.apellidos + "</b>");
                Ext.getCmp('studentFacultad').setValue("<b>" + record.data.facultad + "</b>");
                Ext.getCmp('studentCarrera').setValue("<b>" + record.data.carrera + "</b>");
                Ext.getCmp('studentItinerario').setValue("<b>" + record.data.enfasis + "</b>");
                Ext.getCmp('idalumno').setValue(record.data.idalumno);
                Ext.getCmp('idhorariofiltro').setDisabled(false);
                me.getAlist().getStore().removeAll();
                me.getAlist().getSelectionModel().deselect();

                me.enable();
                win.close();
            } else {
                me.mostrarError(perfil.etiquetas.lbMsgEst);
            }
        }
        else {
            if (me.idt) {
                me.getList().getStore().getProxy().extraParams = {
                    idt: me.idt,
                    idenfasis: me.idenfasis,
                    idfacultad: record.data.idfacultad,
                    idcarrera: record.data.idcarrera,
                    idperiododocente: Ext.getCmp('periodo').getValue(),
                    val: Ext.getCmp('idfacfiltro').getValue(),
                    idpensum: record.data.idpensum
                };
                me.getList().getStore().load();
            }
        }
    },

    loadRegistro: function () {
        var me = this;
        me.getRmlist().getStore().load({params: {idalumno: me.idt, periodo: Ext.getCmp('periodo').getValue()}});
    }
});