Ext.define('GestEtnias.controller.Etnia', {
    extend: 'Ext.app.Controller',

    views: [
        'GestEtnias.view.etnia.EtniaList',
        'GestEtnias.view.etnia.EtniaEdit',
        'GestEtnias.view.etnia.EtniaListToolBar'
    ],
    stores: [
        'GestEtnias.store.Etnia'
    ],
    models: [
        'GestEtnias.model.Etnia'
    ],
    refs: [
        {ref: 'list', selector: 'etnialist'},
        {ref: 'etnialisttbar', selector: 'etnialisttbar'}
    ],

    init: function () {
        var me = this;
        this.control({

            'etnialist': {
                render: this.crearSearhField,
                selectionchange: me.activarBotones
            },
            'etnialisttbar button[action=adicionar]': {
                click: me.adicionarEtnia
            },
            'etnialisttbar button[action=modificar]': {
                click: me.modificarEtnia
            },
            'etnialisttbar button[action=eliminar]': {
                click: me.eliminarEtnia
            },
            'etniaedit button[action=aceptar]': {
                click: me.guardarEtnia
            },
            'etniaedit button[action=aplicar]': {
                click: me.guardarEtnia
            }
        });
    },

    activarBotones: function (store, selected) {
        var me = this,
            tbar = me.getEtnialisttbar();

        if(selected.length == 1){
            tbar.down('button[action=modificar]').enable();
            tbar.down('button[action=eliminar]').enable();
        }else if(selected.length > 1){
            tbar.down('button[action=modificar]').disable();
            tbar.down('button[action=eliminar]').enable();
        }else{
            tbar.down('button[action=modificar]').disable();
            tbar.down('button[action=eliminar]').disable();
        }
    },

    crearSearhField: function (grid) {
        grid.down('etnialisttbar').add([
            '->',
            {
                xtype: 'searchfield',
                store: grid.getStore(),
                width: 400,
                fieldLabel: '<b>' + perfil.etiquetas.lbBtnBuscar + '</b>',
                labelWidth: 40,
                filterPropertysNames: ['etnia']
            }
        ])
    },

    adicionarEtnia: function (button) {
        var view = Ext.widget('etniaedit');
        view.setTitle(perfil.etiquetas.lbTtlAdicionar);
    },

    modificarEtnia: function (button) {
        var view = Ext.widget('etniaedit'),
            record = this.getList().getSelectionModel().getLastSelected();

        view.setTitle(perfil.etiquetas.lbTtlModificar);
        view.down('button[action=aplicar]').hide();

        view.down('form').loadRecord(record);
    },

    eliminarEtnia: function (button) {
        //var record = this.getList().getSelectionModel().getLastSelected(),
            var store = this.getList().getStore(),
                selModel = this.getList().getSelectionModel();

        var ids = new Array();
        var et = new Array();
        for (var i = 0; i < selModel.getCount(); i++) {
            ids.push(selModel.getSelection()[i].data.idetnia);
            et.push(selModel.getSelection()[i]);
        }

        mostrarMensaje(
            2,
            perfil.etiquetas.lbMsgConfEliminar,
            function (btn, text) {
                if (btn == 'ok') {
                    for (var j = 0; j < et.length; j++) {
                        store.remove(et[j]);
                    }
                    store.sync();
                    store.load();
                }
            }
        )
    },

    guardarEtnia: function (button) {
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
                this.getGestEtniasStoreEtniaStore().add(values);
            }

            me.getGestEtniasStoreEtniaStore().sync({
                success: function (batch) {
                    if (batch.operations[0].action == "create")
                        me.getGestEtniasStoreEtniaStore().reload();
                },
                failure: function () {
                    me.getGestEtniasStoreEtniaStore().reload();
                }
            });

            if (button.action === 'aceptar')
                win.close();
            else
                form.getForm().reset();
        }
    },

    sincronizarStore: function (grid, store) {
        store.sync({
            //scope: this,
            success: function (batch) {
                if (batch.operations[0].action == "create") {
                    grid.down('etnialistpbar').moveLast();
                } else if (batch.operations[0].action == "destroy") {
                    if(store.count() > 0)
                        grid.down('etnialistpbar').doRefresh();
                    else
                        grid.down('etnialistpbar').movePrevious();
                }
            },
            failure: function () {
                grid.down('etnialistpbar').doRefresh();
            }
        });
    }
});