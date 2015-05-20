Ext.define('GestCarreras.controller.CarrerasC', {
    extend: 'Ext.app.Controller',

    views: [
        'GestCarreras.view.carrera.CarreraList',
        'GestCarreras.view.carrera.CarreraEdit',
        'GestCarreras.view.carrera.CarreraListToolBar',
        'GestCarreras.view.carrera.CarreraListPagingToolBar',
        'GestCarreras.view.facultad.Combo'
    ],
    stores: [
        'GestCarreras.store.Carreras'
        //'GestCarreras.store.Facultades'
    ],
    models: [
        //'GestCarreras.model.Carrera',
        //'GestCarreras.model.Facultad'
    ],

    refs: [
        {ref: 'list', selector: 'carreralist'},
        {ref: 'tbar', selector: 'carreralisttbar'}
    ],

    init: function () {
        var me = this;

        me.control({

            'carreralist': {
                selectionchange: me.activarBotones
            },
            'carreralisttbar button[action=adicionar]': {
                click: me.adicionarCarrera
            },
            'carreralisttbar button[action=modificar]': {
                click: me.modificarCarrera
            },
            'carreralisttbar button[action=eliminar]': {
                click: me.eliminarCarrera
            },
            'carreraedit button[action=aceptar]': {
                click: me.guardarCarrera
            },
            'carreraedit button[action=aplicar]': {
                click: me.guardarCarrera
            }
        });
    },

    recargarGrid: function (combo) {
        var me = this,
            store = me.getList().getStore();

        me.setearExtraParams(store)
        store.reload();
    },

    activarBotones: function (store, selected) {
        var me = this,
            tbar = me.getTbar();
        tbar.down('button[action=modificar]').setDisabled(selected.length !== 1);
        tbar.down('button[action=eliminar]').setDisabled(selected.length === 0);
    },

    adicionarCarrera: function (button) {
        var view = Ext.widget('carreraedit');
        view.setTitle(perfil.etiquetas.lbTtlAdicionar);
    },

    modificarCarrera: function (button) {
        var view = Ext.widget('carreraedit'),
            record = this.getList().getSelectionModel().getLastSelected();

        view.setTitle(perfil.etiquetas.lbTtlModificar);
        view.down('button[action=aplicar]').hide();

        view.down('form').loadRecord(record);
    },

    eliminarCarrera: function (button) {
        var me = this,
            record = me.getList().getSelectionModel().getSelection(),
            store = me.getList().getStore(),
            mensaje = perfil.etiquetas.lbMsgConfEliminarM;

        if (record.length == 1)
            mensaje = perfil.etiquetas.lbMsgConfEliminar;

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

    guardarCarrera: function (button) {
        var win = button.up('window'),
            form = win.down('form'),
            me = this;

        if (form.getForm().isValid()) {
            var record = form.getRecord(),
                values = form.getValues();

            //modificando
            if (record) {
                record.set(values);
            }
            //adicionando
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

                    grid.down('carreralistpbar').moveLast();
                    //grid.down('carreralistpbar').updateInfo();
                } else if (batch.operations[0].action == "update") {
                    var response = Ext.decode(batch.operations[0].response.responseText);
                    if (response.codMsg == 3)
                        grid.down('carreralistpbar').doRefresh();
                } else if (batch.operations[0].action == "destroy") {
                    if (store.count() > 0)
                        grid.down('carreralistpbar').doRefresh();//me.getList().down('carreralistpbar').doRefresh();
                    else
                        grid.down('carreralistpbar').movePrevious();
                }
            },
            failure: function () {
                grid.down('carreralistpbar').doRefresh();
            }
        });
    }
});