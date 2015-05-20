var idalumno = 0,
    idperiodo = 0,
    idmateria = 0,
    cantcolumns,
    idcurso = 0,
    observaciones = "",
    permiso = 0;

Ext.define('GestFaltas.controller.Faltas', {
    extend: 'Ext.app.Controller',

    views: [
        'GestFaltas.view.faltas.FaltaList',
        'GestFaltas.view.faltas.DesgloseList',
        //'GestFaltas.view.faltas.AlumnoList',
        //'GestFaltas.view.faltas.WindowSearch',
        'GestNotas.view.nota.AlumnoList',
        'GestNotas.view.nota.WindowSearch',
        'GestNotas.view.nota.CursoList',
        //'GestFaltas.view.faltas.CursoList',
        'GestFaltas.view.faltas.FaltaListToolBar',
        'GestNotas.view.nota.reportesWinF',
        'GestNotas.view.nota.observWin'
    ],
    stores: [
        'GestFaltas.store.Faltas',
        'GestFaltas.store.FaltasDias',
        //'GestFaltas.store.Alumnos',
        //'GestFaltas.store.Cursos',
        'GestFaltas.store.Periodos',
        'GestFaltas.store.Annos',
        'GestNotas.store.Alumnos',
        'GestNotas.store.Cursos'
    ],
    models: [
        'GestFaltas.model.Faltas',
        'GestFaltas.model.FaltasDias',
        //'GestFaltas.model.Alumnos',
        //'GestFaltas.model.Cursos',
        //'GestFaltas.model.Periodos',
        //'GestFaltas.model.Annos',
        'GestNotas.model.Alumnos',
        'GestNotas.model.Periodos',
        'GestNotas.model.Cursos',
        'GestNotas.model.Annos'
    ],
    refs: [
        {ref: 'list', selector: 'faltalist'},
        {ref: 'alist', selector: 'alumnolist'},
        {ref: 'clist', selector: 'cursolist'},
        {ref: 'dlist', selector: 'desgloselist'},
        {ref: 'faltalisttbar', selector: 'faltalisttbar'},
        {ref: 'windowsearch', selector: 'windowsearch'}
    ],

    init: function () {
        this.control({
            'faltalisttbar button[action=buscar]': {
                click: this.buscar
            },
            'observwin button[action=aceptar]': {
                click: this.regFalta
            },
            'observwin button[action=cancelar]': {
                click: function () {
                    this.getList().getStore().load();
                }
            },
            'faltalisttbar button[action=imprimir]': {
                click: this.imprimir
            },
            'faltalisttbar button[action=historial]': {
                click: this.collaps
            },
            'faltalisttbar combobox[id=periodoList]': {
                afterrender: this.comboEvents,
                change: function (combo, newvalue) {
                    if (Ext.getCmp('radio').getValue().rb == 'alumnolist') {
                        type = 'alumno';
                        this.idt = idalumno;
                    } else {
                        type = 'curso';
                        this.idt = idmateria;
                    }
                    if (newvalue)
                        idperiodo = combo.getValue();
                    else
                        idperiodo = 0;

                    this.reconfigureGridFaltas(type);
                    this.getList().getStore().load();
                    this.getDlist().getStore().load({params: {type: 0, idt: 0, idpd: 0, idmateria: 0}});
                }
            },
            'faltalisttbar combobox[id=anno]': {
                change: this.updateStore,
                afterrender: this.updateStore
            },
            'windowsearch button[action=aceptar]': {
                click: this.cargarFaltas
            },
            'cursolist': {
                afterrender: this.loadStoreCursos,
                celldblclick: function () {
                    var button = this.getWindowsearch().down('button[action=aceptar]');
                    this.cargarFaltas(button);
                }
            },
            'alumnolist': {
                celldblclick: function () {
                    var button = this.getWindowsearch().down('button[action=aceptar]');
                    this.cargarFaltas(button);
                }
            },
            'faltalist': {
                afterrender: this.saveChangesEvent,
                itemclick: function (g, record) {
                    if (permiso == 1) {
                        idmateria = record.raw.idmateria;
                        idalumno = record.raw.idalumno;
                        Ext.getCmp('desgloselist').getStore().on('beforeload', function (store) {
                            store.getProxy().extraParams = {
                                type: type,
                                idt: idalumno,
                                idpd: Ext.getCmp('periodoList').getValue(),
                                idmateria: idmateria
                            };
                        });
                        Ext.getCmp('desgloselist').getStore().load();
                    } else {
                        mostrarMensaje(3, 'Usted no tiene permiso para modificar faltas.');
                    }
                }
            }
        });
    },
    imprimir: function (button) {
        if (Ext.getCmp('radio').getValue().rb == 'alumnolist') {
            Ext.widget('reportes_win',
                {
                    url: '../gestestudiantes/reportes?idreporte=14&idalumno=' + idalumno + '&idperiodo=' + idperiodo,
                    title: 'FALTAS POR ALUMNO'
                }).show();
        } else {
            Ext.widget('reportes_win',
                {
                    url: '../gestestudiantes/reportes?idreporte=15&idalumno=' + idcurso + '&idperiodo=' + idperiodo,
                    title: 'FALTAS POR CURSO'
                }).show();
        }
    },
    updateStore: function (combo, nv) {
        Ext.getCmp('periodoList').getStore().load({params: {anno: Ext.getCmp('anno').getValue()}});
    },

    loadStoreCursos: function (grid, eOp) {
        grid.getStore().clearFilter(true);
        grid.getStore().addFilter({'property': 'idperiododocente', 'value': Ext.getCmp('periodoList').getValue()});
    },
    buscar: function (button) {
        if (Ext.getCmp('periodoList').getValue()) {
            if (Ext.getCmp('radio').getValue().rb == 'alumnolist') {
                var view = Ext.widget('windowsearch', {who: 'alumnolist', height: 400});
            } else {
                var view = Ext.widget('windowsearch', {who: 'cursolist', width: 600, height: 400});
            }
        } else {
            mostrarMensaje(1, perfil.etiquetas.lbMsgSelCombo);
        }
    },
    collaps: function () {
        Ext.getCmp('faltalist').setHeight(300);
        Ext.getCmp('desgloselist').show();
        Ext.getCmp('desgloselist').toggleCollapse();
        Ext.getCmp('desgloselist').getStore().sync();
    },
    mostrarError: function (type) {
        mostrarMensaje(1, perfil.etiquetas.lbMsgError + " " + type + ".")
    },
    saveChangesEvent: function (grid, eOp) {
        var me = this,
            win;
        grid.getPlugin('cellplugin').on('edit', function (editor, e) {
            if (e.originalValue != e.value)
                win = Ext.widget('observwin');
        });

        grid.getStore().on('beforeload', function (store) {
            store.getProxy().extraParams = {
                type: me.typo,
                idt: me.idt,
                idpd: idperiodo,
                par_curs: me.paralelo
            };
        });
    },
    regFalta: function (button, e) {
        var win = button.up('window'),
            form = win.down('form'),
            me = this;
        me.getList().editingPlugin.context.record.data.observaciones = form.down('textarea[name=observacion]').getValue();

        if (form.getForm().isValid()) {
            me.getList().getStore().sync();
            if (me.typo == 'alumno')
                me.idt = idalumno;
            else
                me.idt = idmateria;

            me.getDlist().getStore().load({
                params: {
                    type: me.typo,
                    idt: idalumno,
                    idpd: idperiodo,
                    idmateria: idmateria
                }
            });
            me.getList().getStore().load();
            win.close();
        } else {
            mostrarMensaje(3, 'Debe introducir una observación sobre el cambio de falta.');
        }
    },
    comboEvents: function (combo, eOp) {
        combo.getStore().on('beforeload', function (store) {
            store.getProxy().extraParams = {anno: Ext.getCmp('anno').getValue()};
            idperiodo = 0;
        });
        combo.getStore().on('load', function (store) {
            if (store.count() > 0) {
                combo.select(store.getAt(0).data.idperiododocente);
                idperiodo = combo.getValue();
            }
        });
    },
    cargarFaltas: function (button) {
        var win = button.up('window'),
            me = this;

        if (button.list == 'alumnolist') {
            record = me.getAlist().getSelectionModel().getSelection()[0];

            if (record) {
                me.typo = 'alumno';
                me.idt = record.data.idalumno;
                idalumno = record.data.idalumno;
                me.reconfigureGridFaltas(me.typo);
                me.getList().getStore().load();
                Ext.getCmp('idstudentinfo').setTitle('DATOS DEL ALUMNO');
                Ext.getCmp('studentCodigo').setFieldLabel('Código');
                Ext.getCmp('studentNombre').setFieldLabel('Nombre');
                Ext.getCmp('studentFacultad').setFieldLabel('Facultad');
                Ext.getCmp('studentCodigo').setValue("<b>" + record.data.codigo + "</b>");
                Ext.getCmp('studentNombre').setValue("<b>" + record.data.nombre + " " + record.data.apellidos + "</b>");
                Ext.getCmp('studentFacultad').setValue("<b>" + record.data.facultad + "</b>");
                win.close();
            } else {
                me.mostrarError(perfil.etiquetas.lbMsgEst);
            }
        } else {
            record = me.getClist().getSelectionModel().getSelection()[0];

            if (record) {
                me.typo = 'curso';
                me.idt = record.data.idmateria;
                idmateria = record.data.idmateria;
                idcurso = record.raw.idcurso;
                me.paralelo = record.data.par_curs;
                me.reconfigureGridFaltas(me.typo);
                me.getList().getStore().load();
                Ext.getCmp('idstudentinfo').setTitle('DATOS DEL CURSO');
                Ext.getCmp('studentCodigo').setFieldLabel('Materia');
                Ext.getCmp('studentCodigo').setValue("<b>" + record.data.codmateria + "-" + record.data.materia_descripcion + "</b>");
                Ext.getCmp('studentNombre').setFieldLabel('Profesor');
                Ext.getCmp('studentNombre').setValue("<b>" + record.data.profesor + "</b>");
                Ext.getCmp('studentFacultad').setFieldLabel('Horario');
                Ext.getCmp('studentFacultad').setValue("<b>" + record.data.horario + "</b>");
                win.close();
            } else {
                me.mostrarError(perfil.etiquetas.lbMsgCur);
            }
        }
        Ext.getCmp('idBtnImprimir').enable();
    },
    reconfigureGridFaltas: function (type) {
        var me = this,
            responseData,
            camposGridDinamico;
        Ext.Ajax.request({
            url: 'configridFaltas',
            method: 'POST',
            params: {idperiodo: idperiodo, type: type},
            callback: function (options, success, response) {
                me.getList().columns = [];
                responseData = Ext.decode(response.responseText);
                camposGridDinamico = responseData.grid.campos;
                cantcolumns = camposGridDinamico;
                if (type == 'alumno') {
                    me.getList().columns.push({dataIndex: 'idalumno', hidden: true, hideable: false});
                    me.getList().columns.push({dataIndex: 'idmateria', hidden: true, hideable: false});
                    me.getList().columns.push({dataIndex: 'idperiododocente', hidden: true, hideable: false});
                    me.getList().columns.push({dataIndex: 'observaciones', hidden: true, hideable: false});
                    me.getList().columns.push({header: 'Código', dataIndex: 'codmateria', flex: 1});
                    me.getList().columns.push({header: 'Materia', dataIndex: 'materia', flex: 3});
                    me.getList().columns.push({header: 'Facultad', dataIndex: 'facultad', flex: 1});
                }
                else {
                    me.getList().columns.push({dataIndex: 'idmateria', hidden: true, hideable: false});
                    me.getList().columns.push({dataIndex: 'observaciones', hidden: true, hideable: false});
                    me.getList().columns.push({header: 'Código', dataIndex: 'codigo', flex: 1});
                    me.getList().columns.push({header: 'Nombre', dataIndex: 'nombre', flex: 1});
                    me.getList().columns.push({header: 'Apellidos', dataIndex: 'apellidos', flex: 1});
                    me.getList().columns.push({header: 'Facultad', dataIndex: 'facultad', flex: 1});
                }

                permiso = responseData.grid.permiso;

                for (var i = 1; i <= camposGridDinamico; i++) {
                    me.getList().columns.push(responseData.grid.columns[i]);
                }

                me.getList().columns.push({header: 'Total', renderer: me.total, flex: 1 / 2});

                me.getList().reconfigure(me.getList().getStore(), me.getList().columns);
            }
        });
    },
    total: function (value, md, record) {
        var suma = 0;
        for (i = 1; i <= cantcolumns; i++) {
            suma += record.get('sem' + i);
        }
        return suma;
    }
});
