Ext.define('PracWin.controller.Practicas', {
    extend: 'Ext.app.Controller',
    refs: [
        {ref: 'alist', selector: 'alumnolist'},
        {ref: 'windowsearch', selector: 'windowsearch'},
        {ref: 'anno_combo', selector: 'combo[name=anno_practica]'},
        {ref: 'periodo_combo_search', selector: 'searchcombofield[name=periodo_practica]'},
        {ref: 'editpractica', selector: 'editpractica'},
        {ref: 'searchempresa', selector: 'searchempresa'}
    ],
    init: function () {
        this.control({
            'studentinfo button[action=buscar]': {
                click: this.buscar
            },
            'windowsearch button[action=aceptar]': {
                click: this.searchStudents
            },
            "practicalist button[action=adicionar]": {
                click: this.onAdicionar
            },
            "practicalist gridpanel[id=gridPracticas]": {
                selectionchange: this.updateButtons,
                afterrender: this.setExtraParams
            },
            "practicalist button[action=modificar]": {
                click: this.onModificar
            },
            "practicalist button[action=eliminar]": {
                click: this.onEliminar
            },
            "practicalist button[action=aprobar]": {
                click: this.onAprobar
            },
            'editpractica combo[name=periodo_practica]': {
                afterrender: this.comboEvents
            },
            'editpractica combobox[name=anno_practica]': {
                change: this.updateStore,
                select: this.updateStore,
                afterrender: this.seleccionarAnnoDefecto
            },
            'editpractica button[action=aceptar]': {
                click: this.addPractica
            },
            'editpractica button[action=aplicar]': {
                click: this.addPractica
            },
            'editpractica triggerfield[id=selectempresa]': {
                searchclick: this.showSearchEmpresa,
                focus: this.showSearchEmpresa
            },
            'editpractica combobox[id=tipopractica]': {
                change: this.changeTipoPractica
            },
            'searchempresa button[action=aceptar]': {
                click: this.loadEmpresa
            },
            'searchempresa': {
                celldblclick: function () {
                    var button = this.getSearchempresa().down('button[action=aceptar]');
                    this.loadEmpresa(button);
                }
            },
            'alumnolist': {
                celldblclick: function () {
                    var button = this.getWindowsearch().down('button[action=aceptar]');
                    this.searchStudents(button);
                }
            }

        });
    },
    seleccionarAnnoDefecto: function () {
        var me = this;
        Ext.defer(function () {
            var anno_combo = me.getAnno_combo(),
                hoy = new Date(),
                anno = hoy.getFullYear().toString();
            if (anno_combo.getValue() == null)
                anno_combo.select(anno);
        }, 200, me)
    },

    buscar: function (button) {
        var searchalumno = Ext.widget('windowsearch', {who: 'alumnolist', height: 400});
    },
    onModificar: function (button) {
        var mod = Ext.widget('editpractica'),
            record = Ext.getCmp('gridPracticas').getSelectionModel().getLastSelected();

        mod.setTitle(perfil.etiquetas.lbTtlModificar);
        mod.down('form').loadRecord(record);
        mod.show();
    },
    onEliminar: function (button) {
        var me = this,
            grid = Ext.getCmp('gridPracticas'),
            record = grid.getSelectionModel().getLastSelected(),
            store = grid.getStore();

        mostrarMensaje(
            2,
            perfil.etiquetas.lbMsgConfEliminar,
            function (btn, text) {
                if (btn == 'ok') {
                    store.remove(record);
                    me.sincronizarStore(grid, store);
                }
            }
        )
    },
    onAprobar: function (button) {
        var me = this,
            idpractica = Ext.getCmp('gridPracticas').getSelectionModel().getLastSelected().data.idpractica,
            estado = Ext.getCmp('gridPracticas').getSelectionModel().getLastSelected().data.estado;
        if (estado)
            mostrarMensaje(1, perfil.etiquetas.lbMsgAprobado);
        else
            mostrarMensaje(
                2,
                perfil.etiquetas.lbMsgConfAprobar,
                function (btn, text) {
                    if (btn == 'ok') {
                        Ext.Ajax.request({
                            url: 'aprobarPractica',
                            method: 'POST',
                            params: {'idpractica': idpractica},
                            callback: function (options, success, response) {
                                responseData = Ext.decode(response.responseText);
                                if (responseData.codMsg === 1) {
                                    Ext.getCmp('gridPracticas').getStore().reload();
                                }
                            }
                        });
                    }
                }
            )
    },
    setExtraParams: function (grid) {
        grid.getStore().on('beforeload', function (store) {
            store.getProxy().extraParams = {
                idenfasis: Ext.getCmp('idenfasis').getValue(),
                idalumno: Ext.getCmp('idalumno').getValue()
            };
        });
        grid.getStore().on('load', function (store) {
            var stSuma = Ext.data.StoreManager.lookup('stSuma'),
                stPractica = Ext.data.StoreManager.lookup('stPracticas');
            stSuma.each(function (record) {
                stPractica.filter('idtipopractica', record.data.idtipopractica);
                record.beginEdit();
                record.data.horas = stPractica.sum('horas');
                record.endEdit();
                stPractica.clearFilter();
            })
        });
        this.buscar();
    },
    changeTipoPractica: function (me) {
        practica = Ext.getCmp("tipopractica").getRawValue();
        Ext.getCmp("pasantia").setValue(practica);
    },
    loadEmpresa: function (button) {
        empresa = Ext.getCmp("listaEmpresa").getSelectionModel().getLastSelected().data.descripcion;
        idempresa = Ext.getCmp("listaEmpresa").getSelectionModel().getLastSelected().data.idempresa;
        Ext.getCmp('idempresa').setValue(idempresa);
        Ext.getCmp("selectempresa").setValue(empresa);
        win = button.up('window');
        win.close();
    },
    showSearchEmpresa: function (me) {
        var empresas = Ext.widget('searchempresa');
        empresas.show();
    },
    addPractica: function (button, e, options) {
        var me = this,
            win = button.up('window'),
            form = win.down('form');

        if (form.getForm().isValid()) {
            var record = form.getRecord(),
                values = form.getValues();

            //modificando
            if (record) {
                record.set(values);
            }
            //insertando
            else {
                Ext.getCmp('gridPracticas').getStore().add(values);
            }
            me.sincronizarStore(Ext.getCmp('gridPracticas'), Ext.getCmp('gridPracticas').getStore());
            // Ext.getCmp('gridPracticas').getStore().reload();

            if (button.action === 'aceptar')
                win.close();
            else if (win.title == perfil.etiquetas.lbTitleAdd)
                form.getForm().reset();
        }

    },
    updateButtons: function (sm, selections) {
        Ext.getCmp('eliminarBtn').setDisabled(selections.length === 0);
        Ext.getCmp('modificarBtn').setDisabled(selections.length === 0);
        Ext.getCmp('aprobarBtn').setDisabled(selections.length === 0);
    },
    onAdicionar: function (button, e, options) {
        var view = Ext.widget('editpractica');
        view.setTitle(perfil.etiquetas.lbTitleAdd);
        view.show();
    },
    sincronizarStore: function (grid, store) {
        store.sync({
            success: function (batch) {
                grid.getDockedComponent('paginator').doRefresh();
            },
            failure: function () {
                grid.getDockedComponent('paginator').doRefresh();
            }
        });
    },
    updateStore: function (combo, nv) {
        Ext.getCmp('periodo_practica').getStore().load({params: {anno: this.getAnno_combo().getValue()}});
        Ext.getCmp('periodo_practica').getStore().on('load', function (store) {
            if (store.count() > 0) {
                if (Ext.getCmp('periodo_practica').getValue() == null)
                    Ext.getCmp('periodo_practica').select(Ext.getCmp('periodo_practica').getStore().first());
            }
        });
    },
    comboEvents: function (combo, eOp) {
        Ext.getCmp('periodo_practica').getStore().on('load', function (store) {
            if (store.count() > 0)
                if (Ext.getCmp('periodo_practica').getValue() == null)
                    Ext.getCmp('periodo_practica').select(Ext.getCmp('periodo_practica').getStore().first());
        });
    },
    searchStudents: function (button) {
        var win = button.up('window');
        var me = this;
        record = me.getAlist().getSelectionModel().getSelection()[0];
        if (record) {
            win.setLoading("Cargando");
            me.idusuario = record.data.idusuario;
            Ext.getCmp('studentCodigo').setValue("<b>" + record.data.codigo + "</b>");
            Ext.getCmp('studentNombre').setValue("<b>" + record.data.nombre + " " + record.data.apellidos + "</b>");
            Ext.getCmp('studentFacultad').setValue("<b>" + record.data.facultad + "</b>");
            Ext.getCmp('studentCarrera').setValue("<b>" + record.data.carrera + "</b>");
            Ext.getCmp('studentItinerario').setValue("<b>" + record.data.enfasis + "</b>");
            Ext.getCmp('idenfasis').setValue(record.data.idenfasis);
            Ext.getCmp('idalumno').setValue(record.data.idalumno);

            me.getAlist().getSelectionModel().deselectAll();
            Ext.getCmp('gridPracticas').getStore().load();
            Ext.getCmp('practicastool').enable();
            Ext.getCmp('paginator').enable();
            win.setLoading(false);
            win.close();
        } else {
            me.mostrarError(perfil.etiquetas.lbMsgEst);
        }
    }
});