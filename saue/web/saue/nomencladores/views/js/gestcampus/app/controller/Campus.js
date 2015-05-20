Ext.define('App.controller.Campus', {
    extend: 'Ext.app.Controller',
    refs: [
        {ref: 'list', selector: 'campuslist'},
        {ref: 'campuslisttbar', selector: 'campuslisttbar'}
    ],

    init: function () {
        var me = this;
        this.control({

            'campuslist': {
               // render: this.crearSearhField,
                selectionchange: me.activarBotones
            },
            'campuslisttbar button[action=adicionar]': {
                click: me.adicionarCampus
            },
            'campuslisttbar button[action=modificar]': {
                click: me.modificarCampus
            },
            'campuslisttbar button[action=eliminar]': {
                click: me.eliminarCampus
            },
            'campusedit button[action=aceptar]': {
                click: me.guardarCampus
            },
            'campusedit button[action=aplicar]': {
                click: me.guardarCampus
            }
        });
    },

    activarBotones: function (store, selected) {
        var me = this,
            tbar = me.getCampuslisttbar();
        tbar.down('button[action=modificar]').setDisabled(selected.length === 0);
        tbar.down('button[action=modificar]').setDisabled(selected.length > 1);
        tbar.down('button[action=eliminar]').setDisabled(selected.length === 0);
    },

     adicionarCampus: function (button) {
        var view = Ext.widget('campusedit');
        view.setTitle(perfil.etiquetas.lbTtlAdicionar);
    },

    modificarCampus: function (button) {
        var view = Ext.widget('campusedit'),
            record = this.getList().getSelectionModel().getLastSelected();

        view.setTitle(perfil.etiquetas.lbTtlModificar);
        view.down('button[action=aplicar]').hide();

        view.down('form').loadRecord(record);
    },

    eliminarCampus: function (button) {
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

    guardarCampus: function (button) {
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
                Ext.getCmp('gdcampus').getStore().add(values);
            }

            Ext.getCmp('gdcampus').getStore().sync({
                success: function (batch) {
                    if (batch.operations[0].action == "create")
                        Ext.getCmp('gdcampus').getStore().reload();
                },
                failure: function () {
                    Ext.getCmp('gdcampus').getStore().reload();
                }
            });

            if (button.action === 'aceptar')
                win.close();
            else
                form.getForm().reset();
        }
    }
});
