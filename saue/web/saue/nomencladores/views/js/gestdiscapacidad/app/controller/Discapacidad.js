Ext.define('GestDiscapacidad.controller.Discapacidad', {
    extend: 'Ext.app.Controller',

    views: [
        'GestDiscapacidad.view.discapacidad.DiscapacidadList',
        'GestDiscapacidad.view.discapacidad.DiscapacidadEdit',
        'GestDiscapacidad.view.discapacidad.DiscapacidadListToolBar'
    ],
    stores: [
        'GestDiscapacidad.store.Discapacidad'
    ],
    models: [
        'GestDiscapacidad.model.Discapacidad'
    ],
    refs: [
        {ref: 'list', selector: 'discapacidadlist'},
        {ref: 'discapacidadlisttbar', selector: 'discapacidadlisttbar'}
    ],

    init: function () {
        var me = this;
        this.control({

            'discapacidadlist': {
                render: this.crearSearhField,
                selectionchange: me.activarBotones
            },
            'discapacidadlisttbar button[action=adicionar]': {
                click: me.adicionarDiscapacidad
            },
            'discapacidadlisttbar button[action=modificar]': {
                click: me.modificarDiscapacidad
            },
            'discapacidadlisttbar button[action=eliminar]': {
                click: me.eliminarDiscapacidad
            },
            'discapacidadedit button[action=aceptar]': {
                click: me.guardarDiscapacidad
            },
            'discapacidadedit button[action=aplicar]': {
                click: me.guardarDiscapacidad
            }
        });
    },

    activarBotones: function (store, selected) {
        var me = this,
            tbar = me.getDiscapacidadlisttbar();

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
        grid.down('discapacidadlisttbar').add([
            '->',
            {
                xtype: 'searchfield',
                store: grid.getStore(),
                width: 400,
                fieldLabel: '<b>' + perfil.etiquetas.lbBtnBuscar + '</b>',
                labelWidth: 40,
                filterPropertysNames: ['discapacidad']
            }
        ])
    },

    adicionarDiscapacidad: function (button) {
        var view = Ext.widget('discapacidadedit');
        view.setTitle(perfil.etiquetas.lbTtlAdicionar);
    },

    modificarDiscapacidad: function (button) {
        var view = Ext.widget('discapacidadedit'),
            record = this.getList().getSelectionModel().getLastSelected();

        view.setTitle(perfil.etiquetas.lbTtlModificar);
        view.down('button[action=aplicar]').hide();

        view.down('form').loadRecord(record);
    },

    eliminarDiscapacidad: function (button) {
        //var record = this.getList().getSelectionModel().getLastSelected(),
            var store = this.getList().getStore(),
                selModel = this.getList().getSelectionModel();

        var ids = new Array();
        var disc = new Array();
        for (var i = 0; i < selModel.getCount(); i++) {
            ids.push(selModel.getSelection()[i].data.iddiscapacidad);
            disc.push(selModel.getSelection()[i]);
        }

        mostrarMensaje(
            2,
            perfil.etiquetas.lbMsgConfEliminar,
            function (btn, text) {
                if (btn == 'ok') {
                    for (var j = 0; j < disc.length; j++) {
                        store.remove(disc[j]);
                    }
                    store.sync();
                }
            }
        )
    },

    guardarDiscapacidad: function (button) {
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
                this.getGestDiscapacidadStoreDiscapacidadStore().add(values);
            }

            me.getGestDiscapacidadStoreDiscapacidadStore().sync({
                success: function (batch) {
                    if (batch.operations[0].action == "create")
                        me.getGestDiscapacidadStoreDiscapacidadStore().reload();
                },
                failure: function () {
                    me.getGestDiscapacidadStoreDiscapacidadStore().reload();
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
                    grid.down('discapacidadlistpbar').moveLast();
                } else if (batch.operations[0].action == "destroy") {
                    if(store.count() > 0)
                        grid.down('discapacidadlistpbar').doRefresh();
                    else
                        grid.down('discapacidadlistpbar').movePrevious();
                }
            },
            failure: function () {
                grid.down('discapacidadlistpbar').doRefresh();
            }
        });
    }
});