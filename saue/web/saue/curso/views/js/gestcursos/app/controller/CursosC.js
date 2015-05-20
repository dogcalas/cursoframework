var idcurso = 0,
    idperiodo, nombre;
Ext.define('GestCursos.controller.CursosC', {
    extend: 'Ext.app.Controller',

    stores: [
        'GestCursos.store.Cursos',
        'GestCursos.store.Annos',
        'GestCursos.store.Periodos',
        'GestCursos.store.Horarios',
        'GestCursos.store.Profesores',
        'GestCursos.store.Alumnos',
        'GestCursos.store.Aulas',
        'GestCursos.store.Facultades',
        'GestCursos.store.Materias',
        'GestCursos.store.AnnoE',
        'GestCursos.store.PeriodosEdit',
        'GestCursos.store.MateriaTb'
    ],

    models: [
        'GestCursos.model.Curso',
        'GestCursos.model.AnnoE',
        'GestCursos.model.PeriodosEdit',
        'GestCursos.model.MateriaTb'
    ],

    views: [
        'GestCursos.view.curso.Grid',
        'GestCursos.view.curso.Toolbar',
        'GestCursos.view.curso.Edit',
        'GestCursos.view.curso.reportesWinC',

        'GestCursos.view.profesor.Grid',
        'GestCursos.view.profesor.Search',
        'GestCursos.view.profesor.Toolbar',

        'GestCursos.view.alumno.Grid',
        'GestCursos.view.alumno.Search',
        'GestCursos.view.alumno.Toolbar',

        'GestCursos.view.periodo.Search',
        'GestCursos.view.periodo.Grid',
        'GestCursos.view.periodo.Toolbar',

        'GestCursos.view.horario.Search',
        'GestCursos.view.horario.Grid',
        'GestCursos.view.horario.Toolbar',

        'GestCursos.view.materia.Search',
        'GestCursos.view.materia.Grid',
        'GestCursos.view.materia.Toolbar',

        'GestCursos.view.mail.Mail'

    ],

    refs: [
        {ref: 'curso_toolbar', selector: 'curso_toolbar'},
        {ref: 'mail_send', selector: 'mail_send'},
        {ref: 'curso_edit', selector: 'curso_edit'},
        {ref: 'curso_grid', selector: 'curso_grid'},
        {ref: 'curso_periodo_search', selector: 'curso_periodo_search'},
        {ref: 'curso_horario_search', selector: 'curso_horario_search'},
        {ref: 'curso_materia_search', selector: 'curso_materia_search'},
        {ref: 'curso_profesor_search', selector: 'curso_profesor_search'},
        {ref: 'anno_combo', selector: 'combo[name=anno]'},
        {ref: 'periodo_combo_search', selector: 'searchcombofield[name=idperiododocentetb]'}
    ],

    requieres: [
        'GestCursos.view.curso.Toolbar'
    ],

    init: function () {
        var me = this;

        me.control({
            'curso_grid': {
                selectionchange: me.manejarBotones,
                select: function (grid, record) {
                    idcurso = record.raw.idcurso
                },
                afterrender: me.seleccionarAnnoDefecto
            },
            'curso_grid button[action=imprimirasistencia]': {
                click: me.imprimir
            },
            'curso_alumno_toolbar button[action=printtoolbar]': {
                click: me.imprimirlsitado
            },
            'curso_toolbar button[action=adicionar]': {
                click: me.adicionarCurso
            },
            'curso_toolbar button[action=modificar]': {
                click: me.modificarCurso
            },
            'curso_toolbar button[action=eliminar]': {
                click: me.eliminarCurso
            },
            'curso_toolbar button[action=mostrar_alumnos]': {
                click: me.mostrarAlumnos
            },
            'curso_toolbar combo[name=anno]': {
                select: function (combo) {
                    me.cargarPeriodos(combo, 'toolbar');
                }
            },
            'curso_toolbar searchcombofield[name=idperiododocentetb]': {
                change: function (combo, nuevoValor) {

                    var curso_toolbar = me.getCurso_toolbar(),
                        horario = curso_toolbar.down('combo[name=idhorario]');

                    curso_toolbar.down('button[action=adicionar]').setDisabled(nuevoValor == null);
                    curso_toolbar.down('combo[name=idhorario]').setDisabled(nuevoValor == null);
                    curso_toolbar.down('searchfield[name=idmatsearch]').setDisabled(nuevoValor == null);

                    me.getCurso_grid().getStore().clearFilter(true);
                    me.getCurso_grid().getStore().addFilter({'property': 'idperiododocente', 'value': nuevoValor});

                    horario.getStore().load({params: {idperiodo: nuevoValor}});
                }
            },
            'curso_profesor_search button[action=aceptar]': {
                click: me.asociarProfesor
            },
            'curso_profesor_grid': {
                celldblclick: function () {
                    var button = me.getCurso_profesor_search().down('button[action=aceptar]');
                    me.asociarProfesor(button);
                }
            },
            'curso_materia_search button[action=aceptar]': {
                click: me.asociarMateria
            },
            'curso_alumno_toolbar button[action=sendmail]': {
                click: me.sendMail
            },
            'mail_send button[action=send]': {
                click: me.sendMailTo
            },
            'mail_send filefield[name=ad]': {
                change: function (m, fld, value) {
                    var addMask = new Ext.LoadMask(Ext.getBody(), {
                        msg: 'Adjuntando archivo...'
                    });
                    nombre = Ext.getCmp("ad").getValue()
                    addMask.show();
                    m.up('form').submit({
                        url: 'upload',
                        failure: function (form, action) {
                            if (action.result.codMsg === 1) {
                                //  mostrarMensaje(1, perfil.etiquetas.lbMsgFunModificarMsg);
                            }
                        }
                    });
                    addMask.disable();
                    addMask.hide();

                }
            },
            'curso_materia_grid': {
                celldblclick: function () {
                    var button = me.getCurso_materia_search().down('button[action=aceptar]');
                    me.asociarMateria(button);
                }
            },
            'curso_horario_search button[action=aceptar]': {
                click: me.asociarHorario
            },
            'curso_edit combo[name=idaula]': {
                select: function (combo, records ) {
                    me.getCurso_edit().down('triggerfield[name=profesor_nombre_completo]').enable();
                    me.getCurso_edit().down('textfield[name=cupo]').setValue(records[0].data.capacidad);
                }
            },
            'curso_edit combo[name=periodo_descripcion_edit]': {
                select: function () {
                    me.getCurso_edit().down('triggerfield[name=horario_descripcion]').setValue('');
                    me.getGestCursosStoreHorariosStore().load({params: {idperiodo: Ext.getCmp('periodo_descripcion_edit').getValue()}});
                    idperiodo = Ext.getCmp('periodo_descripcion_edit').getValue();
                },
                change: function () {
                    me.getGestCursosStoreHorariosStore().load({params: {idperiodo: Ext.getCmp('periodo_descripcion_edit').getValue()}});
                    idperiodo = Ext.getCmp('periodo_descripcion_edit').getValue();
                }
            },
            'curso_horario_grid': {
                celldblclick: function () {
                    var button = me.getCurso_horario_search().down('button[action=aceptar]');
                    me.asociarHorario(button);
                }
            },
            'curso_edit button[action=aceptar]': {
                click: me.guardarCurso
            },
            'curso_edit button[action=aplicar]': {
                click: me.guardarCurso
            },
            'curso_edit triggerfield[name=horario_descripcion]': {
                focus: function (triggerfield) {
                    triggerfield.onTrigger1Click();
                    window.down('curso_horario_grid').getStore().load({params: {idperiodo: idperiodo}});
                }
            },
            'curso_edit triggerfield[name=materia_descripcion]': {
                focus: function (triggerfield) {
                    triggerfield.onTrigger1Click();
                },
                change: function () {
                    me.getCurso_edit().down('combo[name=idfacultad]').enable();
                }
            },
            'curso_edit triggerfield[name=profesor_nombre_completo]': {
                focus: function (triggerfield) {
                    triggerfield.onTrigger1Click();
                },
                change: function () {
                    me.getCurso_edit().down('triggerfield[name=materia_descripcion]').enable();
                }
            },
            'curso_edit combobox[name=anno_edit]': {
                //afterrender: me.updateStore,
                change: me.updateStore
            },
            'curso_edit combobox[name=periodo_descripcion_edit]': {
                //afterrender: me.habilitarHorario,
                change: me.habilitarHorario
            },
            'curso_edit combobox[name=idfacultad]': {
                select: function () {
                    me.getCurso_edit().down('textfield[name=cupo]').enable();
                    me.getCurso_edit().down('textfield[name=par_curs]').enable();
                },
                change: function () {
                    me.getCurso_edit().down('textfield[name=cupo]').enable();
                    me.getCurso_edit().down('textfield[name=par_curs]').enable();
                }
            }
        });
    },

    imprimirlsitado: function (button) {
        Ext.widget('reportes_winC',
            {
                url: '../../../esudiantes/index.php/gestestudiantes/reportes?idreporte=16&idalumno=' + idcurso + '&idperiodo=' + Ext.getCmp('idperiododocentetb').getValue(),
                title: 'ALUMNOS POR CURSO'
            }).show();
    },
    imprimir: function (button) {
        Ext.widget('reportes_winC',
            {
                url: '../../../esudiantes/index.php/gestestudiantes/reportes?idreporte=17&idalumno=' + idcurso + '&idperiodo=' + Ext.getCmp('idperiododocentetb').getValue(),
                title: 'CONTROL DE ASISTENCIA DE ESTUDIANTES'
            }).show();
    },
    habilitarHorario: function () {
        var horario = this.getCurso_edit().down('triggerfield[name=horario_descripcion]');
        horario.setDisabled(false);
    },

    updateStore: function () {
        Ext.getCmp('periodo_descripcion_edit').clearValue();
        Ext.getCmp('periodo_descripcion_edit').getStore().removeAll();
        Ext.getCmp('periodo_descripcion_edit').getStore().load({params: {anno: Ext.getCmp('anno_edit').getValue()}});
    },
    onLaunch: function () {
        var me = this;

        me.getGestCursosStoreAnnosStore().on({
            scope: me,
            load: me.seleccionarAnnoDefecto
        });

        me.getGestCursosStorePeriodosStore().on({
            load: function () {
                var combo = me.getPeriodo_combo_search();
                me.seleccionarPrimerElemento(combo);
            }
        });
    },

    seleccionarAnnoDefecto: function () {
        var me = this;
        Ext.defer(function () {
            var anno_combo = me.getAnno_combo(),
                hoy = new Date(),
                anno = hoy.getFullYear().toString();

            anno_combo.select(anno);
            me.cargarPeriodos(anno_combo, 'toolbar');
        }, 200, me)
    },
    sendMail: function () {
        var view = Ext.widget('mail_send');
        view.setTitle("Enviar correo");
        var mails = "";
        var store = Ext.widget('curso_alumno_grid').getStore();
        for (var i = 0; i < store.getCount(); i++) {
            mails += store[i].e_mail;
            if (i > 0)
                mails += store[i].e_mail + ";";
        }

        Ext.getCmp('toPersons').setRawValue(mails);
    },

    seleccionarPrimerElemento: function (combo) {
        Ext.defer(function () {
            var store = combo.getStore();
            if (store.getCount() > 0) {
                combo.select(store.first());
            }
        }, 100, this)
    },

    cargarPeriodos: function (combo_anno, desde) {
        var me = this,
            periodo, periodos_store,
            curso_toolbar = me.getCurso_toolbar();

        if (desde === "toolbar") {
            periodo = me.getCurso_toolbar().down('combo[name=idperiododocentetb]');

        }
        periodo.enable();
        periodo.reset();
        periodos_store = periodo.getStore();

        periodos_store.load({
            params: {anno: combo_anno.getValue()}
        });

        periodo.getStore().on('load', function (periodos_store) {
            if (periodos_store.count() > 0) {
                periodo.select(periodos_store.getAt(0).data.idperiododocente);
            }
        });

        //periodo.onTrigger1Click('false');

        curso_toolbar.down('button[action=adicionar]').disable();
    },

    manejarBotones: function (store, selected) {
        var me = this,
            curso_toolbar = me.getCurso_toolbar(),
            curso_grid = me.getCurso_grid();
        curso_toolbar.down('button[action=modificar]').setDisabled(selected.length !== 1);
        curso_toolbar.down('button[action=eliminar]').setDisabled(selected.length === 0);
        curso_toolbar.down('button[action=mostrar_alumnos]').setDisabled(selected.length !== 1);
        curso_grid.down('button[action=imprimirasistencia]').setDisabled(selected.length !== 1);
        //idcurso = selected.idcurso;
    },

    adicionarCurso: function () {
        var window = Ext.widget('curso_edit'),
            formulario = window.down('form'),
            me = this,
            curso_toolbar = me.getCurso_toolbar();

        if (curso_toolbar.down('combo[name=idperiododocentetb]').getValue() != null) {
            formulario.down('combo[name=anno_edit]').setValue(curso_toolbar.down('combo[name=anno]').getValue());
            formulario.down('combo[name=anno_edit]').setDisabled(false);

            formulario.down('combo[name=periodo_descripcion_edit]').clearValue();
            formulario.down('combo[name=periodo_descripcion_edit]').getStore().removeAll();
            formulario.down('combo[name=periodo_descripcion_edit]').setDisabled(false);

            me.getGestCursosStoreHorariosStore().removeAll();
            me.getGestCursosStoreHorariosStore().getProxy().extraParams = {
                idperiodo: idperiodo
            };
            me.getGestCursosStoreHorariosStore().load();
        }
        window.setTitle(perfil.etiquetas.lbTtlAdicionar);
    },

    modificarCurso: function () {
        var window = Ext.widget('curso_edit'),
            formulario = window.down('form'),
            me = this,
            record = me.getCurso_grid().getSelectionModel().getLastSelected(),
            curso_toolbar = me.getCurso_toolbar();

        window.setTitle(perfil.etiquetas.lbTtlModificar);
        window.down('button[action=aplicar]').hide();

        formulario.down('combo[name=anno_edit]').enable();
        formulario.down('combo[name=periodo_descripcion_edit]').enable();
        formulario.down('combo[name=idaula]').enable();
        formulario.down('combo[name=idfacultad]').enable();
        formulario.down('triggerfield[name=profesor_nombre_completo]').enable();
        formulario.down('triggerfield[name=materia_descripcion]').enable();
        formulario.down('combo[name=anno_edit]').setValue(curso_toolbar.down('combo[name=anno]').getValue());

        me.getGestCursosStoreAulasStore().getProxy().extraParams = {};
        me.getGestCursosStoreAulasStore().load();
        me.getGestCursosStoreProfesoresStore().getProxy().extraParams = {
            idhorario: record.get('idhorario')
        };

        formulario.loadRecord(record);
    },

    eliminarCurso: function () {
        var me = this,
            record = me.getCurso_grid().getSelectionModel().getSelection(),
            store = me.getCurso_grid().getStore();

        mostrarMensaje(
            2,
            perfil.etiquetas.lbMsgConfEliminar,
            function (btn, text) {
                if (btn == 'ok') {
                    store.remove(record);
                    store.sync();
                    store.load();
                }
            }
        )
    },

    mostrarAlumnos: function () {
        var window = Ext.widget('curso_alumno_search'),
            alumno_grid = window.down('curso_alumno_grid'),
            alumno_toolbar = window.down('curso_alumno_toolbar'),
            me = this,
            curso_toolbar = me.getCurso_toolbar(),
            record = me.getCurso_grid().getSelectionModel().getLastSelected();

        record.set('anno', curso_toolbar.down('combo[name=anno]').getValue());
        alumno_toolbar.down('form').loadRecord(record);
        alumno_toolbar.down('form').down('displayfield[name=anno]').setValue(curso_toolbar.down('combo[name=anno]').getValue());
        alumno_toolbar.down('form').down('displayfield[name=periodo]').setValue(curso_toolbar.down('combo[name=idperiododocentetb]').getRawValue());
        idcurso = record.get('idcurso');
        alumno_grid.getStore().getProxy().extraParams = {
            idcurso: record.get('idcurso')
        };
        alumno_grid.getStore().load();
    },

    asociarProfesor: function (button) {
        var window = button.up('curso_profesor_search'),
            curso_profesor_grid = window.down('curso_profesor_grid'),
            record = curso_profesor_grid.getSelectionModel().getLastSelected(),
            me = this,
            curso_edit = me.getCurso_edit();

        curso_edit.down('triggerfield[name=profesor_nombre_completo]').setValue(record.get('nombre') + ' ' + record.get('apellidos'));
        curso_edit.down('hidden[name=idprofesor]').setValue(record.get('idprofesor'));

        if (button.action === 'aceptar')
            window.close();
    },

    asociarMateria: function (button) {
        var window = button.up('curso_materia_search'),
            curso_materia_grid = window.down('curso_materia_grid'),
            record = curso_materia_grid.getSelectionModel().getLastSelected(),
            me = this,
            curso_edit = me.getCurso_edit();

        curso_edit.down('triggerfield[name=materia_descripcion]').setValue(record.get('descripcion'));
        curso_edit.down('hidden[name=idmateria]').setValue(record.get('idmateria'));

        if (button.action === 'aceptar')
            window.close();
    },

    asociarPeriodo: function (button) {
        var window = button.up('curso_periodo_search'),
            curso_materia_grid = window.down('curso_periodo_grid'),
            record = curso_materia_grid.getSelectionModel().getLastSelected(),
            me = this,
            curso_edit = me.getCurso_edit();

        curso_edit.down('hidden[name=idperiododocente]').setValue(record.get('idperiododocente'));
        me.getGestCursosStoreHorariosStore().getProxy().extraParams = {

            idperiodo: record.get('idperiododocente')
        };
        curso_edit.down('triggerfield[name=horario_descripcion]').enable();

        if (button.action === 'aceptar')
            window.close();
    },

    asociarHorario: function (button) {
        var window = button.up('curso_horario_search'),
            curso_materia_grid = window.down('curso_horario_grid'),
            record = curso_materia_grid.getSelectionModel().getLastSelected(),
            me = this,
            curso_edit = me.getCurso_edit();

        curso_edit.down('triggerfield[name=horario_descripcion]').setValue(record.get('horario_descripcion'));
        curso_edit.down('hidden[name=idhorario]').setValue(record.get('idhorario'));

        me.getGestCursosStoreAulasStore().getProxy().extraParams = {
            idhorario: record.get('idhorario')
        };
        me.getGestCursosStoreAulasStore().load();

        me.getGestCursosStoreProfesoresStore().getProxy().extraParams = {
            idhorario: record.get('idhorario')
        };
        curso_edit.down('combo[name=idaula]').enable();

        if (button.action === 'aceptar')
            window.close();
    },

    guardarCurso: function (button) {
        var win = button.up('window'),
            form = win.down('form'),
            me = this,
            curso_edit = me.getCurso_edit();

        if (form.getForm().isValid()) {
            var record = form.getRecord(),
                values = form.getValues();

            //modificando
            if (record) {
                values.aula_descripcion = curso_edit.down('combo[name=idaula]').getRawValue();
                values.materia_descripcion = curso_edit.down('triggerfield[name=materia_descripcion]').getRawValue();
                values.horario_descripcion = curso_edit.down('triggerfield[name=horario_descripcion]').getRawValue();
                record.set(values);
            }
            //adicionando
            else {
                values.idperiododocente = curso_edit.down('triggerfield[name=periodo_descripcion_edit]').getValue();
                me.getCurso_grid().getStore().add(values);
            }

            me.sincronizarStore(me.getCurso_grid(), me.getCurso_grid().getStore());

            if (button.action === 'aceptar')
                win.close();
            else if (button.action === 'aplicar')
                form.getForm().reset();
        }
    },

    sincronizarStore: function (grid, store) {
        store.sync({
            success: function (batch) {
                if (batch.operations[0].action == "create") {

                    grid.down('pagingtoolbar').moveLast();
                } else if (batch.operations[0].action == "destroy") {
                    if (store.count() > 0)
                        grid.down('pagingtoolbar').doRefresh();
                    else
                        grid.down('pagingtoolbar').movePrevious();
                }
            },
            failure: function () {
                grid.down('pagingtoolbar').doRefresh();
            }
        });
    },

    sendMailTo: function (button) {
        var window = button.up('mail_send');
        var sendMask = new Ext.LoadMask(Ext.getBody(), {
            msg: 'Enviando mensaje...'
        });
        sendMask.show();
        Ext.Ajax.request({
            url: 'sendMailTo',
            params: {
                emails: Ext.getCmp("toPersons").getValue(),
                asunto: Ext.getCmp("asunto").getValue(),
                text: Ext.getCmp("msg").getValue(),
                nombre: nombre

            },
            callback: function (options, success, response) {
                responseData = Ext.decode(response.responseText);
                sendMask.disable();
                sendMask.hide();
                if (responseData.codMsg === 1) {
                    window.close();
                }
            }
        });
    }

});