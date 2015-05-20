Ext.define('GestLocales.controller.Locales', {
    extend: 'Ext.app.Controller',

    views: [
        'GestLocales.view.locales.LocalesList',
        'GestLocales.view.locales.LocalesEdit',
        'GestLocales.view.locales.LocalesListToolBar',
        'GestLocales.view.tipolocales.Combo',
        'GestLocales.view.ubicaciones.Combo'
    ],
    stores: [
        'GestLocales.store.Locales',
        'GestLocales.store.TipoLocales',
        'GestLocales.store.Ubicaciones'
    ],
    models: [
        'GestLocales.model.Locales',
        'GestLocales.model.TipoLocales',
        'GestLocales.model.Ubicaciones'
    ],
    refs: [
        {ref: 'list', selector: 'locallist'},
        {ref: 'locallisttbar', selector: 'locallisttbar'}
    ],

    init: function () {
        var me = this;
        this.control({

            'locallist': {
                render: this.crearSearhField,
                selectionchange: me.activarBotones
            },
            'locallisttbar button[action=adicionar]': {
                click: me.adicionarLocal
            },
            'locallisttbar button[action=modificar]': {
                click: me.modificarLocal
            },
            'locallisttbar button[action=eliminar]': {
                click: me.eliminarLocal
            },
            'localedit button[action=aceptar]': {
                click: me.guardarLocal
            },
            'localedit button[action=aplicar]': {
                click: me.guardarLocal
            }
        });
    },

    activarBotones: function (store, selected) {
        var me = this,
            tbar = me.getLocallisttbar();

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
        grid.down('locallisttbar').add([
            '->',
            {
                xtype: 'searchfield',
                store: grid.getStore(),
                //anchor: '100%',
                width: 400,
                fieldLabel: '<b>' + perfil.etiquetas.lbBtnBuscar + '</b>',
                labelWidth: 40,
                filterPropertysNames: ['descripcion', 'local', 'campus']
            }
        ])
    },

    adicionarLocal: function (button) {
        var view = Ext.widget('localedit');
        view.setTitle(perfil.etiquetas.lbTitVentanaTitI);
    },

    modificarLocal: function (button) {
        var view = Ext.widget('localedit'),
            record = this.getList().getSelectionModel().getLastSelected();

        view.setTitle(perfil.etiquetas.lbTitVentanaTitII);
        view.down('button[action=aplicar]').hide();

        view.down('form').loadRecord(record);
    },

    eliminarLocal: function (button) {
        //var record = this.getList().getSelectionModel().getLastSelected(),
            var store = this.getList().getStore(),
                selModel = this.getList().getSelectionModel();

        var ids = new Array();
        var loc = new Array();
        for (var i = 0; i < selModel.getCount(); i++) {
            //ids.push(selModel.getSelection()[i].data.idtipo_aula);
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

    guardarLocal: function (button) {
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
                me.getGestLocalesStoreLocalesStore().add(values);
            }

            me.sincronizarStore(me.getList(), me.getList().getStore());
            me.getList().getStore().load();

            if (button.action === 'aceptar'){
                win.close();
            }
            else
                form.getForm().reset();
        }
    },

    sincronizarStore: function (grid, store) {
        store.sync({
            //scope: this,
            success: function (batch) {
                if (batch.operations[0].action == "create") {
                    grid.down('locallistpbar').moveLast();
                } else if (batch.operations[0].action == "destroy") {
                    if(store.count() > 0)
                        grid.down('locallistpbar').doRefresh();
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
