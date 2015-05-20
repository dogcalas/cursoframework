Ext.define('GestConv.controller.Convalidaciones', {
    extend: 'Ext.app.Controller',
    idmateria: 0,
    win1: Ext.create('GestConv.view.AddMatConv'),
    views: [
        'GestConv.view.StudentInfo',
        'GestConv.view.SearchOptions',
        'GestConv.view.MateriaList',
        'GestConv.view.EnfasisFilter',
        'GestConv.view.ToolBarConva',
        'GestConv.view.MateriasConvList',
        'GestNotas.view.nota.WindowSearch',
        'GestNotas.view.nota.AlumnoList',
        'GestConv.view.AddMatConv'

    ],
    refs: [
        {ref: 'list', selector: 'materialist'},
        {ref: 'alist', selector: 'alumnolist'},
        {ref: 'clist', selector: 'convlist'},
        {ref: 'windowsearch', selector: 'windowsearch'}
    ],
    stores: ['GestConv.store.Materias', 'GestNotas.store.Alumnos', 'GestConv.store.Annos', 'GestConv.store.Periodos', 'GestConv.store.MateriasConva'],
    models: ['GestConv.model.Materia', 'GestNotas.model.Alumnos', 'GestConv.model.MateriasConva', 'GestConv.model.Annos', 'GestConv.model.Periodos'],
    init: function () {
        this.control({
            'studentinfo button[action=buscar]': {
                click: this.buscar
            },
            'windowsearch button[action=aceptar]': {
                click: this.cargarNotas
            },
            'searchoptions combobox[id=periodo]': {
                afterrender: this.comboEvents
            },
            'searchoptions combobox[id=anno]': {
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
                select: this.cargarMaterias,
                afterrender: this.selectUni
            },
            'materialist': {
                afterrender: this.setExtraParams,
                itemclick: this.manageAdd
            },
            'materialist button[action=convalidar]': {
                click: this.convalidarMaterias
            },
            'addmatconv  button[action=addmatconv]': {
                click: this.addMateria
            },
            'addmatconv  button[action=applymatconv]': {
                click: this.addMateria
            },
            'addmatconv  button[action=cancelmatconv]': {
                click: this.closeWindow
            },
            'convlist': {
                afterrender: this.setExtraConvParams,
                selectionchange: this.manageBut,
                itemdblclick: this.changeConva
            },
            'toolbar_conva button[id=addMatUniversidad]': {
                click: this.showWindow
            },
            'toolbar_conva button[id=delMatUniversidad]': {
                click: this.showWindowDelete
            },
            'toolbar_conva button[id=prinMatUniversidad]': {
                click: this.prinMat
            },
            'alumnolist': {
                celldblclick: function () {
                    var button = this.getWindowsearch().down('button[action=aceptar]');
                    this.cargarNotas(button);
                }
            }
        });
    },
    convalidarMaterias: function () {
        if (!this.sm || this.sm.getSelection().length === 0) {
            mostrarMensaje(3, "Debe seleccionar al menos una materia a homologar.")
        } else if (!Ext.getCmp('periodo').getValue()) {
            mostrarMensaje(3, "Debe seleccionar el período donde se homologa.")
        } else if (!Ext.getCmp('materialist').getSelectionModel().getLastSelected().data.nota) {
            mostrarMensaje(3, "Debe introducir la nota con la que se homologa.")
        } else {
            Ext.MessageBox.show({
                title: 'Convalidar',
                msg: '¿Desea homologar las (' + this.sm.getSelection().length + ') materias seleccionadas?',
                buttons: Ext.MessageBox.YESNO,
                icon: Ext.MessageBox.QUESTION,
                scope: this,
                fn: function (buttonId) {
                    if (buttonId === "yes") {
                        valid = true;
                        for (var i = this.sm.getSelection().length - 1; i >= 0; i--) {
                            if (this.sm.getSelection()[i].data.idmateriaconva == "") {
                                valid = false;
                                mostrarMensaje(1, "La materia " + this.sm.getSelection()[i].data.codigo + " est&aacute; seleccionada y no tiene ingreso de convalidaci&oacute;n.")
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
    prinMat: function (but) {
        Ext.Ajax.request({
            url: 'principalMateriaConvalidacion',
            method: 'POST',
            params: {
                "iduniversidad": Ext.getCmp('universidadCombo').getValue(),
                "idmateria": this.idmateria,
                "idmateriaconva": Ext.getCmp('convlist').getSelectionModel().getLastSelected().data.idmateriaconva
            },
            callback: function (options, success, response) {
                responseData = Ext.decode(response.responseText);
                if (responseData.codMsg === 1) {
                    Ext.getCmp('convlist').getStore().reload();
                    Ext.getCmp('convlist').getSelectionModel().deselect();
                }
            }
        });
    },
    showWindowDelete: function () {
        Ext.MessageBox.show({
            title: 'Eliminar',
            msg: '¿Desea eliminar la materia?',
            buttons: Ext.MessageBox.YESNO,
            icon: Ext.MessageBox.QUESTION,
            fn: function (buttonId) {
                if (buttonId === "yes") {
                    Ext.Ajax.request({
                        url: 'eliminarMateriaConvalidacion',
                        method: 'POST',
                        params: {idmateriaconva: Ext.getCmp('convlist').getSelectionModel().getLastSelected().data.idmateriaconva},
                        callback: function (options, success, response) {
                            responseData = Ext.decode(response.responseText);
                            if (responseData.codMsg === 1) {
                                Ext.getCmp('convlist').getStore().reload();
                                Ext.getCmp('convlist').getSelectionModel().deselect();
                            }
                        }
                    });
                }
            }
        });
    },
    closeWindow: function () {
        Ext.getCmp('addmatconv').close();
    },
    showWindow: function () {
        this.win1.show();
    },
    addMateria: function (but) {
        Ext.getCmp('formAddMateria').getForm().submit({
            url: 'insertarMateriaConvalidacion',
            waitMsg: 'Registrando la materia.',
            params: {
                "iduniversidad": Ext.getCmp('universidadCombo').getValue(),
                "idmateria": this.idmateria
            },
            failure: function (form, action) {
                if (action.result.codMsg === 1) {
                    Ext.getCmp('formAddMateria').getForm().reset();
                    Ext.getCmp('convlist').getStore().reload();
                    if (but.getId() == "add") {
                        Ext.getCmp('addmatconv').close();
                    }
                }
            }
        });
    },
    manageAdd: function (me, record) {
        if (me.getSelectionModel().getLastSelected().data.idtipoaprobado != 1000013) {
        if (me.getSelectionModel().getSelection().length >= 1) {
            this.sm = me.getSelectionModel();
            Ext.getCmp('convlist').getStore().load({
                params: {
                    "iduniversidad": Ext.getCmp('universidadCombo').getValue(),
                    "idmateria": this.idmateria = me.getSelectionModel().getLastSelected().data.idmateria
                }
            });
            Ext.getCmp('addMatUniversidad').enable();
        } else {
            Ext.getCmp('addMatUniversidad').disable();
            Ext.getCmp('prinMatUniversidad').disable();
            Ext.getCmp('delMatUniversidad').disable();
        }
        } else {
            me.deselect(me.getSelectionModel().getLastSelected());
        }

    },
    changeConva: function (me, record, item, index, e, eOpts) {
        this.sm.getLastSelected().beginEdit();
        this.sm.getLastSelected().data.idmateriaconva = record.data.idmateriaconva;
        this.sm.getLastSelected().data.materiaprincipal = record.data.descripcion;
        this.sm.getLastSelected().endEdit();
    },
    manageBut: function (sel, selectedRecords) {

        if (selectedRecords.length === 1) {
            Ext.getCmp('prinMatUniversidad').enable();
            Ext.getCmp('delMatUniversidad').enable();
        } else {
            Ext.getCmp('prinMatUniversidad').disable();
            Ext.getCmp('delMatUniversidad').disable();
        }

    },
    selectUni: function (combo) {
        combo.getStore().on("load", function (store) {
            if (store.count() > 0) {
                combo.select(store.getAt(0).data.iduniversidad);
            }
        });
    },
    setExtraConvParams: function (grid) {
        grid.getStore().on('beforeload', function (store) {
            store.getProxy().extraParams = {
                "iduniversidad": Ext.getCmp('universidadCombo').getValue(),
                'idmateria': this.idmateria
            }
        });
    },
    setExtraParams: function (grid) {
        grid.getStore().on('beforeload', function (store) {
            store.getProxy().extraParams = {
                "idenfasis": Ext.getCmp('idMatxPensumEnfasisCombo').getValue(),
                "idpensum": Ext.getCmp('idpensum').getValue(),
                "iduniversidad": Ext.getCmp('universidadCombo').getValue(),
                "idperiodo": Ext.getCmp('periodo').getValue(),
                "idalumno": Ext.getCmp('idalumno').getValue()
            }
        });
        this.buscar();
    },
    cargarMaterias: function (combo) {
        if (Ext.getCmp('idpensum').isValid() && !Ext.getCmp('idpensum').isDisabled()
            && Ext.getCmp('idMatxPensumEnfasisCombo').isValid() && !Ext.getCmp('idMatxPensumEnfasisCombo').isDisabled()
            && Ext.getCmp('universidadCombo').isValid()) {
            Ext.getCmp('materialist').getStore().load();
            Ext.getCmp('addMatUniversidad').disable();
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
            carrera_combo = Ext.getCmp('idMatxPensumCarrerasCombo');
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
                                Ext.Ajax.request({
                                    url: 'cargarUniversidadEstudiante',
                                    method: 'POST',
                                    params: {
                                        "idalumno": record.data.idalumno
                                    },
                                    callback: function (options, success, response) {
                                        responseData = Ext.decode(response.responseText);
                                        if (responseData.datos) {
                                            Ext.getCmp('universidadCombo').select(responseData.datos.iduniversidad);
                                        }
                                        win.setLoading("false");
                                        me.getAlist().getSelectionModel().deselectAll();
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
                    }
            });
        } else {
            me.mostrarError(perfil.etiquetas.lbMsgEst);
        }
    }
});