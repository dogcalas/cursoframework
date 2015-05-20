Ext.define('App.controller.TBeca', {
    extend: 'Ext.app.Controller',
    refs: [
        {ref: 'list', selector: 'tbecalist'},
        {ref: 'tbecalisttbar', selector: 'tbecalisttbar'}
    ],

    init: function () {
        var me = this;
        this.control({

            'tbecalist': {
               // render: this.crearSearhField,
                selectionchange: me.activarBotones
            },
            'tbecalisttbar button[action=adicionar]': {
                click: me.adicionarTBeca
            },
            'tbecalisttbar button[action=modificar]': {
                click: me.modificarTBeca
            },
            'tbecalisttbar button[action=eliminar]': {
                click: me.eliminarTBeca
            },
            'tbecaedit button[action=aceptar]': {
                click: me.guardarTBeca
            },
            'tbecaedit button[action=aplicar]': {
                click: me.guardarTBeca
            }
        });
    },

    activarBotones: function (store, selected) {
        var me = this,
            tbar = me.getTbecalisttbar();
        tbar.down('button[action=modificar]').setDisabled(selected.length === 0);
        tbar.down('button[action=modificar]').setDisabled(selected.length > 1);
        tbar.down('button[action=eliminar]').setDisabled(selected.length === 0);
    },

     adicionarTBeca: function (button) {
        var view = Ext.widget('tbecaedit');
        view.setTitle(perfil.etiquetas.lbTtlAdicionar);
    },

    modificarTBeca: function (button) {
        var view = Ext.widget('tbecaedit'),
            record = this.getList().getSelectionModel().getLastSelected();

        view.setTitle(perfil.etiquetas.lbTtlModificar);
        view.down('button[action=aplicar]').hide();

        view.down('form').loadRecord(record);
    },

    eliminarTBeca: function (button) {
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

    guardarTBeca: function (button) {
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
                Ext.getCmp('gdtbeca').getStore().add(values);
            }

            Ext.getCmp('gdtbeca').getStore().sync({
                success: function (batch) {
                    if (batch.operations[0].action == "create")
                        Ext.getCmp('gdtbeca').getStore().reload();
                },
                failure: function () {
                    Ext.getCmp('gdtbeca').getStore().reload();
                }
            });

            if (button.action === 'aceptar')
                win.close();
            else
                form.getForm().reset();
        }
    }
});
