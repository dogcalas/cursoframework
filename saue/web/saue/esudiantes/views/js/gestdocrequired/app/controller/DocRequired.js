Ext.define('GestDocRequired.controller.DocRequired', {
    extend: 'Ext.app.Controller',

    views: [
        'GestDocRequired.view.docrequired.DocRequiredList',
        'GestDocRequired.view.docrequired.DocRequiredEdit',
        'GestDocRequired.view.docrequired.DocRequiredListToolBar'
    ],
    stores: [
        'GestDocRequired.store.DocRequired'
    ],
    models: [
        'GestDocRequired.model.DocRequired'
    ],
    refs: [
        {ref: 'list', selector: 'docrequiredlist'},
        {ref: 'docrequiredlisttbar', selector: 'docrequiredlisttbar'}
    ],

    init: function () {
        var me = this;
        this.control({

            'docrequiredlist': {
                render: this.crearSearhField,
                selectionchange: me.activarBotones
            },
            'docrequiredlisttbar button[action=adicionar]': {
                click: me.adicionarDocRequired
            },
            'docrequiredlisttbar button[action=modificar]': {
                click: me.modificarDocRequired
            },
            'docrequiredlisttbar button[action=eliminar]': {
                click: me.eliminarDocRequired
            },
            'docrequirededit button[action=aceptar]': {
                click: me.guardarDocRequired
            },
            'docrequirededit button[action=aplicar]': {
                click: me.guardarDocRequired
            }
        });
    },

    activarBotones: function (store, selected) {
        var me = this,
            tbar = me.getDocrequiredlisttbar();
        tbar.down('button[action=modificar]').setDisabled(selected.length === 0);
        tbar.down('button[action=eliminar]').setDisabled(selected.length === 0);
    },

    crearSearhField: function (grid) {
        grid.down('docrequiredlisttbar').add([
            '->',
            {
                xtype: 'searchfield',
                store: grid.getStore(),
                width: 400,
                fieldLabel: '<b>' + perfil.etiquetas.lbBtnBuscar + '</b>',
                labelWidth: 40,
                filterPropertysNames: ['descripcion']
            }
        ])
    },

    adicionarDocRequired: function (button) {
        var view = Ext.widget('docrequirededit');
        view.setTitle(perfil.etiquetas.lbTtlAdicionar);
    },

    modificarDocRequired: function (button) {
        var view = Ext.widget('docrequirededit'),
            record = this.getList().getSelectionModel().getLastSelected();

        view.setTitle(perfil.etiquetas.lbTtlModificar);
        view.down('button[action=aplicar]').hide();
        view.down('form').loadRecord(record);
    },

    eliminarDocRequired: function (button) {
//        var record = this.getList().getSelectionModel().getLastSelected(),
        var store = this.getList().getStore(),
            selModel = this.getList().getSelectionModel();

        var ids = new Array();
        var doc = new Array();
        for (var i = 0; i < selModel.getCount(); i++) {
            ids.push(selModel.getSelection()[i].data.iddocumentorequerido);
            doc.push(selModel.getSelection()[i]);
        }

        mostrarMensaje(
            2,
            perfil.etiquetas.lbMsgConfEliminar,
            function (btn, text) {
                if (btn == 'ok') {
                    for (var j = 0; j < doc.length; j++) {
                        store.remove(doc[j]);
                    }
                    store.sync();
                    store.load();
                }
            }
        )
    },

    guardarDocRequired: function (button) {
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
                this.getGestDocRequiredStoreDocRequiredStore().add(values);
            }

            me.getGestDocRequiredStoreDocRequiredStore().sync({
                success: function (batch) {
                    if (batch.operations[0].action == "create")
                        me.getGestDocRequiredStoreDocRequiredStore().reload();
                },
                failure: function () {
                    me.getGestDocRequiredStoreDocRequiredStore().reload();
                }
            });

            if (button.action === 'aceptar')
                win.close();
            else
                form.getForm().reset();
        }
    }
});