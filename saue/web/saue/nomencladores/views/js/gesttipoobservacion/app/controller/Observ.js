Ext.define('App.controller.Observ', {
    extend: 'Ext.app.Controller',
    refs: [
        {ref: 'list', selector: 'observlist'},
        {ref: 'observlisttbar', selector: 'observlisttbar'}
    ],

    init: function () {
        var me = this;
        this.control({

            'observlist': {
               // render: this.crearSearhField,
                selectionchange: me.activarBotones
            },
            'observlisttbar button[action=adicionar]': {
                click: me.adicionarTObserv
            },
            'observlisttbar button[action=modificar]': {
                click: me.modificarTObserv
            },
            'observlisttbar button[action=eliminar]': {
                click: me.eliminarTObserv
            },
            'observedit button[action=aceptar]': {
                click: me.guardarTObserv
            },
            'observedit button[action=aplicar]': {
                click: me.guardarTObserv
            }
        });
    },

    activarBotones: function (store, selected) {
        var me = this,
            tbar = me.getObservlisttbar();
        tbar.down('button[action=modificar]').setDisabled(selected.length === 0);
        tbar.down('button[action=modificar]').setDisabled(selected.length > 1);
        tbar.down('button[action=eliminar]').setDisabled(selected.length === 0);
    },

     adicionarTObserv: function (button) {
        var view = Ext.widget('observedit');
        view.setTitle(perfil.etiquetas.lbTtlAdicionar);
    },

    modificarTObserv: function (button) {
        var view = Ext.widget('observedit'),
            record = this.getList().getSelectionModel().getLastSelected();

        view.setTitle(perfil.etiquetas.lbTtlModificar);

        view.down('button[action=aplicar]').hide();
        view.down('form').loadRecord(record);
    },

    eliminarTObserv: function (button) {
        var record = this.getList().getSelectionModel().getLastSelected(),
            store = this.getList().getStore(),
            selModel = this.getList().getSelectionModel();

            var ids = new Array();
            for (var i = 0; i < selModel.getCount(); i++) {
               ids.push(selModel.getSelection()[i]);

            }
        mostrarMensaje(
            2,
            perfil.etiquetas.lbMsgConfEliminar,
            function (btn, text) {
                if (btn == 'ok') {
                    for (var j = 0; j < ids.length; j++) {
                        store.remove(ids[j]);
                    }
                    store.sync();
                    store.load();
                }
            }
        )
            },

    guardarTObserv: function (button) {
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
            //insertando
            else {
                Ext.getCmp('gdobserv').getStore().add(values);
            }

            Ext.getCmp('gdobserv').getStore().sync({
                success: function (batch) {
                    if (batch.operations[0].action == "create")
                        Ext.getCmp('gdobserv').getStore().reload();
                },
                failure: function () {
                    Ext.getCmp('gdobserv').getStore().reload();
                }
            });

            if (button.action === 'aceptar')
                win.close();
            else
                form.getForm().reset();
        }
    }
});
