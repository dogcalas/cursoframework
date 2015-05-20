Ext.define('GestEnfasis.controller.Enfasis', {
    extend: 'Ext.app.Controller',

    views: [
        'GestEnfasis.view.enfasi.EnfasiList',
        'GestEnfasis.view.enfasi.EnfasiEdit',
        'GestEnfasis.view.enfasi.EnfasiListToolBar',
        'GestEnfasis.view.enfasi.EnfasiListPagingToolBar',
        'GestEnfasis.view.facultad.Combo',
        'GestEnfasis.view.carrera.Combo'

    ],
    stores: [
        'GestEnfasis.store.Enfasis',
        'GestEnfasis.store.Carreras',
        'GestEnfasis.store.Facultades'
    ],

    models: [
        'GestEnfasis.model.Enfasi',
        'GestEnfasis.model.Carrera'
    ],

    refs: [
        {ref: 'list', selector: 'enfasilist'},
        {ref: 'tbar', selector: 'enfasilisttbar'},
        {ref: 'enfasis_carrera_combo', selector: 'enfasis_carrera_combo'}
    ],

    init: function () {
        var me = this;

        me.control({

            'enfasilist': {
                selectionchange: me.activarBotones
            },
            'enfasis_facultad_combo': {
                select: function (combo) {
                    me.cargarCarreras(combo.getValue());
                }
            },
            'enfasilisttbar button[action=adicionar]': {
                click: me.adicionarEnfasi
            },
            'enfasilisttbar button[action=modificar]': {
                click: me.modificarEnfasi
            },
            'enfasilisttbar button[action=eliminar]': {
                click: me.eliminarEnfasi
            },
            'enfasiedit button[action=aceptar]': {
                click: me.guardarEnfasi
            },
            'enfasiedit button[action=aplicar]': {
                click: me.guardarEnfasi
            }
        });
    },

    cargarCarreras: function (idfacultad) {
        var me = this,
            enfasis_carrera_combo = me.getEnfasis_carrera_combo();

        enfasis_carrera_combo.reset();
        enfasis_carrera_combo.getStore().load(
            {
                params: {idfacultad: idfacultad}
            }
        )
    },

    activarBotones: function (store, selected) {
        var me = this,
            tbar = me.getTbar();
        tbar.down('button[action=modificar]').setDisabled(selected.length !== 1);
        tbar.down('button[action=eliminar]').setDisabled(selected.length === 0);
    },

    adicionarEnfasi: function (button) {
        var view = Ext.widget('enfasiedit');
        view.setTitle(perfil.etiquetas.lbTtlAdicionar);
    },

    modificarEnfasi: function (button) {
        var me = this,
            view = Ext.widget('enfasiedit'),
            record = this.getList().getSelectionModel().getLastSelected();

        view.setTitle(perfil.etiquetas.lbTtlModificar);
        view.down('button[action=aplicar]').hide();

        me.cargarCarreras(record.get('idfacultad'));
        view.down('form').loadRecord(record);
    },

    eliminarEnfasi: function (button) {
        var me = this,
            record = me.getList().getSelectionModel().getSelection(),
            store = me.getList().getStore(),
            mensaje = perfil.etiquetas.lbMsgConfEliminarM;

        if (record.length == 1)
            mensaje = perfil.etiquetas.lbMsgConfEliminar + " '" + record[0].get('descripcion_enfasi') + "'?";

        mostrarMensaje(
            2,
            mensaje,
            function (btn, text) {
                if (btn == 'ok') {
                    store.remove(record);
                    me.sincronizarStore(me.getList(), store);
                }
            }
        )
    },

    guardarEnfasi: function (button) {
        var win = button.up('window'),
            form = win.down('form'),
            me = this;

        if (form.getForm().isValid()) {
            var record = form.getRecord(),
                values = form.getValues(),
                combo = form.down('#idEnfasisCarrerasCombo');

            values.descripcion_carrera = combo.getRawValue();

            //modificando
            if (record) {
                record.set(values);
            }
            //insertando
            else {
                me.getList().getStore().add(values);
            }

            if (button.action === 'aceptar')
                win.close();
            else if (button.action === 'aplicar')
                form.getForm().reset();

            me.sincronizarStore(me.getList(), me.getList().getStore());
        }
    },

    sincronizarStore: function (grid, store) {
        store.sync({
            //scope: this,
            success: function (batch) {
                if (batch.operations[0].action == "create") {
                    //var idcarrera = Ext.decode(batch.operations[0].response.responseText).idcarrera;
                    //store.last().set('idcarrera', idcarrera);

                    grid.down('enfasilistpbar').moveLast();
                    //grid.down('enfasilisttbar').updateInfo();
                } else if (batch.operations[0].action == "destroy") {
                    if (store.count() > 0)
                        grid.down('enfasilistpbar').doRefresh();//me.getList().down('enfasilisttbar').doRefresh();
                    else
                        grid.down('enfasilistpbar').movePrevious();
                }
            },
            failure: function () {
                grid.down('enfasilistpbar').doRefresh();
            }
        });
    }
});