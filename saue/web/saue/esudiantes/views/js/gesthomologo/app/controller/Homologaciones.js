Ext.define('GestHom.controller.Homologaciones', {
    extend: 'Ext.app.Controller',
    idmateria: 0,
    views: [
        'GestConv.view.StudentInfo',
        'GestHom.view.SearchOptions',
        'GestHom.view.MateriaList',
        'GestHom.view.MateriaLibreList',
        'GestConv.view.EnfasisFilter',
        'GestNotas.view.nota.WindowSearch',
        'GestNotas.view.nota.AlumnoList'
    ],
    refs: [
        {ref: 'alist', selector: 'alumnolist'},
        {ref: 'mllist', selector: 'materialibrelist'},
        {ref: 'windowsearch', selector: 'windowsearch'}
    ],
    stores: ['GestHom.store.Materias', 'GestNotas.store.Alumnos', 'GestConv.store.Annos', 'GestHom.store.MateriasLibre', 'GestConv.store.Periodos'],
    models: ['GestHom.model.Materia', 'GestNotas.model.Alumnos', 'GestConv.model.Annos', 'GestHom.model.MateriasLibre', 'GestConv.model.Periodos'],
    init: function () {
        this.control({
            'studentinfo button[action=buscar]': {
                click: this.buscar
            },
            'windowsearch button[action=aceptar]': {
                click: this.cargarNotas
            },
            'searchhomoptions combobox[id=periodo]': {
                afterrender: this.comboEvents
            },
            'searchhomoptions combobox[id=anno]': {
                change: this.updateStore,
                afterrender: this.updateStore
            },
            'enfasisfilter combobox[id=facultad_combo]': {
                select: this.cargarCarreras
            },
            'enfasisfilter combobox[id=idMatxPensumCarrerasCombo]': {
                select: this.cargarEnfasisPensum
            },
            'enfasisfilter combobox[id=idMatxPensumEnfasisCombo]': {
                select: this.cargarMaterias
            },
            'enfasisfilter combobox[id=idpensum]': {
                select: this.cargarMaterias
            },
            'enfasisfilter combobox[id=universidadCombo]': {
                afterrender: this.hideUni
            },
            'materialist': {
                afterrender: this.setExtraParams,
                itemclick: this.manageAdd
            },
            'materialist button[action=homologar]': {
                click: this.homologarMaterias
            },
            'materialibrelist': {
                afterrender: this.setExtraParams,
                itemdblclick: this.changeHomo
            },
            'alumnolist': {
                celldblclick: function () {
                    var button = this.getWindowsearch().down('button[action=aceptar]');
                    this.cargarNotas(button);
                }
            }
        });
    },
    manageAdd: function (me, record) {
        if (me.getSelectionModel().getLastSelected().data.idtipoaprobado != 1000023) {
            if (me.getSelectionModel().getSelection().length >= 1) {
                this.sm = me.getSelectionModel();
            }
        } else {
            me.deselect(me.getSelectionModel().getLastSelected());
        }
    },
    homologarMaterias: function () {
        if (!this.sm || this.sm.getSelection().length === 0) {
            mostrarMensaje(1, "Debe seleccionar al menos una materia a homologar.")
        } else if (!Ext.getCmp('periodo').getValue()) {
            mostrarMensaje(1, "Debe seleccionar el periodo donde se homologa.")
        } else {
            Ext.MessageBox.show({
                title: 'Homologar',
                msg: '¿Desea homologar las (' + this.sm.getSelection().length + ') materias seleccionadas?',
                buttons: Ext.MessageBox.YESNO,
                icon: Ext.MessageBox.QUESTION,
                scope: this,
                fn: function (buttonId) {
                    if (buttonId === "yes") {
                        valid = true;
                        for (var i = this.sm.getSelection().length - 1; i >= 0; i--) {
                            if (this.sm.getSelection()[i].data.idmateriahomo == "") {
                                valid = false;
                                mostrarMensaje(1, "La materia " + this.sm.getSelection()[i].data.codigo + " est&aacute; seleccionada y no tiene ingreso de homologaci&oacute;n.")
                                break;
                            }
                        }
                        if (valid) {
                            for (var i = this.sm.getSelection().length - 1; i >= 0; i--) {
                                this.sm.getSelection()[i].setDirty();
                            }
                            this.sm.getStore().sync({
                                scope: this.sm.getStore(),
                                success: function () {
                                    this.reload();
                                }
                            });
                        }

                    }
                }
            });
        }
    },
    //aqui se llama cuando selececcione la materia a homologar
    changeHomo: function (me, record, item, index, e, eOpts) {
        this.sm.getLastSelected().beginEdit();
        this.sm.getLastSelected().data.idmateriahomo = record.data.idmateria;
        this.sm.getLastSelected().data.materiahomo = record.data.descripcion;
        this.sm.getLastSelected().data.codmateriahomo = record.data.codigo;
        this.sm.getLastSelected().data.nota = record.data.nota;
        this.sm.getLastSelected().data.paralelo = record.data.paralelo;
        this.sm.getLastSelected().endEdit();
    },
    hideUni: function (combo) {
        combo.hide();
    },
    setExtraParams: function (grid) {
        grid.getStore().on('beforeload', function (store) {
            store.getProxy().extraParams = {
                "idenfasis": Ext.getCmp('idMatxPensumEnfasisCombo').getValue(),
                "idpensum": Ext.getCmp('idpensum').getValue(),
                "idperiodo": Ext.getCmp('periodo').getValue(),
                "idalumno": Ext.getCmp('idalumno').getValue()
            };
        });
        if(grid.getId() == 'materialist') this.buscar();
    },
    cargarMaterias: function (combo) {
        if (Ext.getCmp('idpensum').isValid() && !Ext.getCmp('idpensum').isDisabled()
            && Ext.getCmp('idMatxPensumEnfasisCombo').isValid() && !Ext.getCmp('idMatxPensumEnfasisCombo').isDisabled()) {
            Ext.getCmp('materialist').getStore().load();
            Ext.getCmp('paginator').enable();
        }
    },
    cargarEnfasisPensum: function (carrera_combo) {
        var me = this,
            pensum_combo = Ext.getCmp('idpensum'),
            enfasi_combo = Ext.getCmp('idMatxPensumEnfasisCombo');

        enfasi_combo.reset();
        enfasi_combo.getStore().reload(
            {
                params: {idcarrera: carrera_combo.getValue()},
                callback: function (records, operation, success) {
                    enfasi_combo.select(enfasi_combo.getStore().first());
                    pensum_combo.reset();
                    pensum_combo.getStore().reload(
                        {
                            params: {idcarrera: carrera_combo.getValue()},
                            callback: function (records, operation, success) {
                                pensum_combo.select(pensum_combo.getStore().first());
                                me.cargarMaterias();
                            }
                        }
                    );
                }
            }
        );

    },
    cargarCarreras: function (facultad_combo) {
        var me = this,
            carrera_combo = Ext.getCmp('idMatxPensumCarrerasCombo'),
            pensum_combo = Ext.getCmp('idpensum'),
            enfasi_combo = Ext.getCmp('idMatxPensumEnfasisCombo');

        enfasi_combo.reset();
        pensum_combo.reset();
        carrera_combo.getStore().load(
            {
                params: {idfacultad: facultad_combo.getValue()},
                callback: function (records, operation, success) {
                    carrera_combo.select(carrera_combo.getStore().first());
                    enfasi_combo.getStore().load(
                        {
                            params: {idcarrera: carrera_combo.getValue()},
                            callback: function (records, operation, success) {
                                enfasi_combo.select(enfasi_combo.getStore().first());
                                pensum_combo.select(pensum_combo.getStore().first());
                                Ext.getCmp('materialist').getStore().load();
                            }
                        }
                    );
                }
            }
        );
    },
    updateStore: function (combo, nv) {
        Ext.getCmp('periodo').getStore().load({params: {anno: Ext.getCmp('anno').getValue()}});
    },
    buscar: function (button) {

        var view = Ext.widget('windowsearch', {who: 'alumnolist', height: 400});
    },
    comboEvents: function (combo, eOp) {
        combo.getStore().on('beforeload', function (store) {
            store.getProxy().extraParams = {anno: Ext.getCmp('anno').getValue()};
        });
        combo.getStore().on('load', function (store) {
            if (store.count() > 0)
                combo.select(store.getAt(0).data.idperiododocente);
        });
    },
    mostrarError: function (type) {
        mostrarMensaje(1, perfil.etiquetas.lbMsgError + " " + type + ".")
    },
    cargarNotas: function (button) {
        var win = button.up('window');
        var me = this;
        record = me.getAlist().getSelectionModel().getSelection()[0];
        if (record) {
            win.setLoading("Cargando");
            me.idusuario = record.data.idusuario;
            Ext.getCmp('studentCodigo').setValue("<b>" + record.data.codigo + "</b>");
            Ext.getCmp('studentNombre').setValue("<b>" + record.data.nombre + " " + record.data.apellidos + "</b>");
            Ext.getCmp('studentFacultad').setValue("<b>" + record.data.facultad + "</b>");
            Ext.getCmp('idalumno').setValue(record.data.idalumno);

            Ext.getCmp('materialist').getStore().removeAll();

            var facultad_combo = Ext.getCmp('facultad_combo'),
                carrera_combo = Ext.getCmp('idMatxPensumCarrerasCombo'),
                enfasi_combo = Ext.getCmp('idMatxPensumEnfasisCombo'),
                pensum_combo = Ext.getCmp('idpensum');
            facultad_combo.removeListener("load");
            carrera_combo.removeListener("load");

            facultad_combo.getStore().load();
            me.facListener = facultad_combo.getStore().on({
                destroyable: true,
                'load': function (obj) {
                    carrera_combo.getStore().load(
                        {
                            params: {idfacultad: record.data.idfacultad}
                        }
                    );
                }
            });

            me.carListener = carrera_combo.getStore().on({
                destroyable: true,
                'load': function () {
                    enfasi_combo.getStore().load(
                        {
                            params: {idcarrera: record.data.idcarrera}
                        }
                    );
                }
            });

            me.enfListener = enfasi_combo.getStore().on({
                destroyable: true,
                'load': function () {
                    facultad_combo.enable();
                    enfasi_combo.enable();
                    carrera_combo.enable();
                    facultad_combo.select(Ext.create('GestMatxPensum.model.Facultad', {
                        "idfacultad": record.data.idfacultad,
                        "denominacion": record.data.facultad
                    }));
                    carrera_combo.select(Ext.create('GestMatxPensum.model.Carrera', {
                        "idcarrera": record.data.idcarrera,
                        "descripcion": record.data.carrera
                    }));
                    enfasi_combo.select(Ext.create('GestMatxPensum.model.Enfasi', {
                        "idenfasis": record.data.idenfasis,
                        "descripcion": record.data.enfasis
                    }));
                    pensum_combo.reset();
                    pensum_combo.getStore().load(
                        {
                            params: {idcarrera: record.data.idcarrera}
                        }
                    );
                    me.penListener = pensum_combo.getStore().on({
                        destroyable: true,
                        'load': function () {
                            pensum_combo.enable();
                            pensum_combo.select(Ext.create('GestMatxPensum.model.Pensum', {
                                "idpensum": record.data.idpensum,
                                "descripcion": record.data.pensum
                            }));
                            win.setLoading("false");
                            me.getAlist().getSelectionModel().deselectAll();
                            Ext.getCmp('librepaginator').enable();
                            Ext.getCmp('materialibrelist').getStore().load();
//                            Ext.getCmp('paginator').disable();
                            Ext.getCmp('materialist').getStore().load();
                            Ext.getCmp('paginator').enable();
                            me.carListener.destroy();
                            me.facListener.destroy();
                            me.enfListener.destroy();
                            me.penListener.destroy();
                            win.close();
                        }
                    });
                }
            });
        } else {
            me.mostrarError(perfil.etiquetas.lbMsgEst);
        }
    }
});