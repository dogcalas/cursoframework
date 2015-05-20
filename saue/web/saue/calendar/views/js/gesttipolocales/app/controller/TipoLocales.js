Ext.define('GestTipoLocales.controller.TipoLocales', {
    extend: 'Ext.app.Controller',

    views: [
        'GestTipoLocales.view.tipolocal.TipoLocalesList',
        'GestTipoLocales.view.tipolocal.TipoLocalesEdit',
        'GestTipoLocales.view.tipolocal.TipoLocalesListToolBar'
    ],
    stores: [
        'GestTipoLocales.store.TipoLocales'
    ],
    models: [
        'GestTipoLocales.model.TipoLocales'
    ],
    refs: [
        {ref: 'list', selector: 'tipolocallist'},
        {ref: 'tipolocallisttbar', selector: 'tipolocallisttbar'}
    ],

    init: function () {
        var me = this;
        this.control({

            'tipolocallist': {
                render: this.crearSearhField,
                selectionchange: me.activarBotones
            },
            'tipolocallisttbar button[action=adicionar]': {
                click: me.adicionarTipoLocal
            },
            'tipolocallisttbar button[action=modificar]': {
                click: me.modificarTipoLocal
            },
            'tipolocallisttbar button[action=eliminar]': {
                click: me.eliminarTipoLocal
            },
            'tipolocaledit button[action=aceptar]': {
                click: me.guardarTipoLocal
            },
            'tipolocaledit button[action=aplicar]': {
                click: me.guardarTipoLocal
            }
        });
    },

    activarBotones: function (store, selected) {
        var me = this,
            tbar = me.getTipolocallisttbar();

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
        grid.down('tipolocallisttbar').add([
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

    adicionarTipoLocal: function (button) {
        var view = Ext.widget('tipolocaledit');
        view.setTitle(perfil.etiquetas.lbTtlAdicionar);
    },

    modificarTipoLocal: function (button) {
        var view = Ext.widget('tipolocaledit'),
            record = this.getList().getSelectionModel().getLastSelected();

        view.setTitle(perfil.etiquetas.lbTtlModificar);
        view.down('button[action=aplicar]').hide();

        view.down('form').loadRecord(record);
    },

    eliminarTipoLocal: function (button) {
        //var record = this.getList().getSelectionModel().getLastSelected(),
            var store = this.getList().getStore(),
                selModel = this.getList().getSelectionModel();

        var ids = new Array();
        var loc = new Array();
        for (var i = 0; i < selModel.getCount(); i++) {
            ids.push(selModel.getSelection()[i].data.idtipo_aula);
            loc.push(selModel.getSelection()[i]);
        }

        mostrarMensaje(
            2,
            perfil.etiquetas.lbMsgConfEliminar,
            function (btn, text) {
                if (btn == 'ok') {
                    for (var j = 0; j < loc.length; j++) {
                        store.remove(loc[j]);
                    }
                    store.sync();
                }
            }
        )
    },

    guardarTipoLocal: function (button) {
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
                this.getGestTipoLocalesStoreTipoLocalesStore().add(values);
            }

            me.getGestTipoLocalesStoreTipoLocalesStore().sync({
                success: function (batch) {
                    if (batch.operations[0].action == "create")
                        me.getGestTipoLocalesStoreTipoLocalesStore().reload();
                },
                failure: function () {
                    me.getGestTipoLocalesStoreTipoLocalesStore().reload();
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
                    //var idcarrera = Ext.decode(batch.operations[0].response.responseText).idcarrera;
                    //store.last().set('idcarrera', idcarrera);

                    grid.down('locallistpbar').moveLast();
                    //grid.down('enfasilisttbar').updateInfo();
                } else if (batch.operations[0].action == "destroy") {
                    if(store.count() > 0)
                        grid.down('locallistpbar').doRefresh();//me.getList().down('enfasilisttbar').doRefresh();
                    else
                        grid.down('locallistpbar').movePrevious();
                }
            },
            failure: function () {
                grid.down('locallistpbar').doRefresh();
            }
        });
    }
});