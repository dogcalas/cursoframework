Ext.define('App.controller.TDisc', {
    extend: 'Ext.app.Controller',
    refs: [
        {ref: 'list', selector: 'tdisclist'},
        {ref: 'tdisclisttbar', selector: 'tdisclisttbar'}
    ],

    init: function () {
        var me = this;
        this.control({

            'tdisclist': {
               // render: this.crearSearhField,
                selectionchange: me.activarBotones
            },
            'tdisclisttbar button[action=adicionar]': {
                click: me.adicionarTDisc
            },
            'tdisclisttbar button[action=modificar]': {
                click: me.modificarTDisc
            },
            'tdisclisttbar button[action=eliminar]': {
                click: me.eliminarTDisc
            },
            'tdiscedit button[action=aceptar]': {
                click: me.guardarTDisc
            },
            'tdiscedit button[action=aplicar]': {
                click: me.guardarTDisc
            }
        });
    },

    activarBotones: function (store, selected) {
        var me = this,
            tbar = me.getTdisclisttbar();
        tbar.down('button[action=modificar]').setDisabled(selected.length === 0);
        tbar.down('button[action=modificar]').setDisabled(selected.length > 1);
        tbar.down('button[action=eliminar]').setDisabled(selected.length === 0);
    },

     adicionarTDisc: function (button) {
        var view = Ext.widget('tdiscedit');
        view.setTitle(perfil.etiquetas.lbTtlAdicionar);
    },

    modificarTDisc: function (button) {
        var view = Ext.widget('tdiscedit'),
            record = this.getList().getSelectionModel().getLastSelected();

        view.setTitle(perfil.etiquetas.lbTtlModificar);
        view.down('button[action=aplicar]').hide();

        view.down('form').loadRecord(record);
    },

    eliminarTDisc: function (button) {
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
                }
            }
        )
            },

    guardarTDisc: function (button) {
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
                Ext.getCmp('gdtdisc').getStore().add(values);
            }

            Ext.getCmp('gdtdisc').getStore().sync({
                success: function (batch) {
                    if (batch.operations[0].action == "create")
                        Ext.getCmp('gdtdisc').getStore().reload();
                },
                failure: function () {
                    Ext.getCmp('gddisc').getStore().reload();
                }
            });

            if (button.action === 'aceptar')
                win.close();
            else
            form.getForm().reset();
        }
    }
});
