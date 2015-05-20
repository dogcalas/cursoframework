Ext.define('App.controller.GDisc', {
    extend: 'Ext.app.Controller',
    refs: [
        {ref: 'list', selector: 'gdisclist'},
        {ref: 'gdisclisttbar', selector: 'gdisclisttbar'}
    ],

    init: function () {
        var me = this;
        this.control({

            'gdisclist': {
               // render: this.crearSearhField,
                selectionchange: me.activarBotones
            },
            'gdisclisttbar button[action=adicionar]': {
                click: me.adicionarGDisc
            },
            'gdisclisttbar button[action=modificar]': {
                click: me.modificarGDisc
            },
            'gdisclisttbar button[action=eliminar]': {
                click: me.eliminarGDisc
            },
            'gdiscedit button[action=aceptar]': {
                click: me.guardarGDisc
            },
            'gdiscedit button[action=aplicar]': {
                click: me.guardarGDisc
            }
        });
    },

    activarBotones: function (store, selected) {
        var me = this,
            tbar = me.getGdisclisttbar();
        tbar.down('button[action=modificar]').setDisabled(selected.length === 0);
        tbar.down('button[action=modificar]').setDisabled(selected.length > 1);
        tbar.down('button[action=eliminar]').setDisabled(selected.length === 0);
    },

     adicionarGDisc: function (button) {
        var view = Ext.widget('gdiscedit');
        view.setTitle(perfil.etiquetas.lbTtlAdicionar);
    },

    modificarGDisc: function (button) {
        var view = Ext.widget('gdiscedit'),
            record = this.getList().getSelectionModel().getLastSelected();

        view.setTitle(perfil.etiquetas.lbTtlModificar);
        view.down('button[action=aplicar]').hide();

        view.down('form').loadRecord(record);
    },

    eliminarGDisc: function (button) {
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

    guardarGDisc: function (button) {
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
                Ext.getCmp('gdgdisc').getStore().add(values);
            }

            Ext.getCmp('gdgdisc').getStore().sync({
                success: function (batch) {
                    if (batch.operations[0].action == "create")
                        Ext.getCmp('gdgdisc').getStore().reload();
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
