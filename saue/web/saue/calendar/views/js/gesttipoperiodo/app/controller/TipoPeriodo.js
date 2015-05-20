Ext.define('GestTipoPeriodo.controller.TipoPeriodo', {
    extend: 'Ext.app.Controller',
    refs: [
        {ref: 'list', selector: 'tipoperiodolist'},
        {ref: 'tipoperiodolisttbar', selector: 'tipoperiodolisttbar'}
    ],

    init: function () {
        var me = this;
        this.control({

            'tipoperiodolist': {
                render: this.crearSearhField,
                selectionchange: me.activarBotones
            },
            'tipoperiodolisttbar button[action=adicionar]': {
                click: me.adicionarTipoPeriodo
            },
            'tipoperiodolisttbar button[action=modificar]': {
                click: me.modificarTipoPeriodo
            },
            'tipoperiodolisttbar button[action=eliminar]': {
                click: me.eliminarTipoPeriodo
            },
            'tipoperiodoedit button[action=aceptar]': {
                click: me.guardarTipoPeriodo
            },
            'tipoperiodoedit button[action=aplicar]': {
                click: me.guardarTipoPeriodo
            }
        });
    },

    activarBotones: function (store, selected) {
        var me = this,
            tbar = me.getTipoperiodolisttbar();

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
        grid.down('tipoperiodolisttbar').add([
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

    adicionarTipoPeriodo: function (button) {
        var view = Ext.widget('tipoperiodoedit');
        view.setTitle(perfil.etiquetas.lbTtlAdicionar);
    },

    modificarTipoPeriodo: function (button) {
        var view = Ext.widget('tipoperiodoedit'),
            record = this.getList().getSelectionModel().getLastSelected();

        view.setTitle(perfil.etiquetas.lbTtlModificar);
        view.down('button[action=aplicar]').hide();

        view.down('form').loadRecord(record);
    },

    eliminarTipoPeriodo: function (button) {
        //var record = this.getList().getSelectionModel().getLastSelected(),
            var store = this.getList().getStore(),
                selModel = this.getList().getSelectionModel();

        var ids = new Array();
        var per = new Array();
        for (var i = 0; i < selModel.getCount(); i++) {
            ids.push(selModel.getSelection()[i].data.idtipo_periododocente);
            per.push(selModel.getSelection()[i]);
        }

        mostrarMensaje(
            2,
            perfil.etiquetas.lbMsgConfEliminar,
            function (btn, text) {
                if (btn == 'ok') {
                    for (var j = 0; j < per.length; j++) {
                        store.remove(per[j]);
                    }
                    store.sync();
                }
            }
        )
    },

    guardarTipoPeriodo: function (button) {
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
                Ext.getCmp('gdtipoperiodo').getStore().add(values);
            }

            Ext.getCmp('gdtipoperiodo').getStore().sync({
                success: function (batch) {
                    if (batch.operations[0].action == "create")
                        Ext.getCmp('gdtipoperiodo').getStore().reload();
                },
                failure: function () {
                    Ext.getCmp('gdtipoperiodo').getStore().reload();
                }
            });

            if (button.action === 'aceptar')
                win.close();
            else
                form.getForm().reset();
        }
    }
});
