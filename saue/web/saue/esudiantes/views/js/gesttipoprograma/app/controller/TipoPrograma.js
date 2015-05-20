Ext.define('GestTipoPrograma.controller.TipoPrograma', {
    extend: 'Ext.app.Controller',

    views: [
        'GestTipoPrograma.view.tipoprograma.TipoProgramaList',
        'GestTipoPrograma.view.tipoprograma.TipoProgramaEdit',
        'GestTipoPrograma.view.tipoprograma.TipoProgramaListToolBar'
    ],
    stores: [
        'GestTipoPrograma.store.TipoPrograma'
    ],
    models: [
        'GestTipoPrograma.model.TipoPrograma'
    ],
    refs: [
        {ref: 'list', selector: 'tipoprogramalist'},
        {ref: 'tipoprogramalisttbar', selector: 'tipoprogramalisttbar'}
    ],

    init: function () {
        var me = this;
        this.control({

            'tipoprogramalist': {
                render: this.crearSearhField,
                selectionchange: me.activarBotones
            },
            'tipoprogramalisttbar button[action=adicionar]': {
                click: me.adicionarTipoPrograma
            },
            'tipoprogramalisttbar button[action=modificar]': {
                click: me.modificarTipoPrograma
            },
            'tipoprogramalisttbar button[action=eliminar]': {
                click: me.eliminarTipoPrograma
            },
            'tipoprogramaedit button[action=aceptar]': {
                click: me.guardarTipoPrograma
            },
            'tipoprogramaedit button[action=aplicar]': {
                click: me.guardarTipoPrograma
            }
        });
    },

    activarBotones: function (store, selected) {
        var me = this,
            tbar = me.getTipoprogramalisttbar();

        if(selected.length==1){
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
        grid.down('tipoprogramalisttbar').add([
            '->',
            {
                xtype: 'searchfield',
                store: grid.getStore(),
                //anchor: '100%',
                width: 400,
                fieldLabel: '<b>' + perfil.etiquetas.lbBtnBuscar + '</b>',
                labelWidth: 40,
                filterPropertysNames: ['descripcion']
            }
        ])
    },

    adicionarTipoPrograma: function (button) {
        var view = Ext.widget('tipoprogramaedit');
        view.setTitle(perfil.etiquetas.lbTtlAdicionar);
    },

    modificarTipoPrograma: function (button) {
        var view = Ext.widget('tipoprogramaedit'),
            record = this.getList().getSelectionModel().getLastSelected();

        view.setTitle(perfil.etiquetas.lbTtlModificar);
        view.down('button[action=aplicar]').hide();
        view.down('form').loadRecord(record);
    },

    eliminarTipoPrograma: function (button) {
//        var record = this.getList().getSelectionModel().getLastSelected(),
            var store = this.getList().getStore(),
                selModel = this.getList().getSelectionModel();

        var ids = new Array();
        var est = new Array();
        for (var i = 0; i < selModel.getCount(); i++) {
            ids.push(selModel.getSelection()[i].data.idtipoalumno);
            est.push(selModel.getSelection()[i]);
        }

        mostrarMensaje(
            2,
            perfil.etiquetas.lbMsgConfEliminar,
            function (btn, text) {
                if (btn == 'ok') {
                    for (var j = 0; j < est.length; j++) {
                        store.remove(est[j]);
                    }
                    store.sync();
                    store.load();
                }
            }
        )
    },

    guardarTipoPrograma: function (button) {
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
                this.getGestTipoProgramaStoreTipoProgramaStore().add(values);
            }

            me.getGestTipoProgramaStoreTipoProgramaStore().sync({
                success: function (batch) {
                    if (batch.operations[0].action == "create")
                        me.getGestTipoProgramaStoreTipoProgramaStore().reload();
                },
                failure: function () {
                    me.getGestTipoProgramaStoreTipoProgramaStore().reload();
                }
            });

            if (button.action === 'aceptar')
                win.close();
            else
                form.getForm().reset();
        }
    }
});