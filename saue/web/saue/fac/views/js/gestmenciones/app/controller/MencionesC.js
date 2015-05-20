Ext.define('GestMenciones.controller.MencionesC', {
    extend: 'Ext.app.Controller',

    views: [
        'GestMenciones.view.mencion.Edit',
        'GestMenciones.view.mencion.Grid',
        'GestMenciones.view.mencion.PagingToolBar',
        'GestMenciones.view.mencion.ToolBar',
        'GestPensums.view.facultad.Combo'
    ],
    stores: [
        'GestMenciones.store.Menciones',
        'GestMenciones.store.Facultades'
    ],
    models: [
    ],

    refs: [
        {ref: 'list', selector: 'mencion_grid'},
        {ref: 'tbar', selector: 'mencion_toolbar'}
    ],

    init: function () {
        var me = this;

        this.control({

            'mencion_grid': {
                selectionchange: me.manejarBotonos
            },
            'mencion_toolbar button[action=adicionar]': {
                click: me.adicionarMencion
            },
            'mencion_toolbar button[action=modificar]': {
                click: me.modificarMencion
            },
            'mencion_toolbar button[action=eliminar]': {
                click: me.eliminarMencion
            },
            'mencion_edit button[action=aceptar]': {
                click: me.guardarMencion
            },
            'mencion_edit button[action=aplicar]': {
                click: me.guardarMencion
            }
        });
    },

    manejarBotonos: function (store, selected) {
        var me = this,
            tbar = me.getTbar();
        tbar.down('button[action=modificar]').setDisabled(selected.length !== 1);
        tbar.down('button[action=eliminar]').setDisabled(selected.length === 0);
    },

    adicionarMencion: function (button) {
        var view = Ext.widget('mencion_edit');
        view.setTitle(perfil.etiquetas.lbTtlAdicionar);
    },

    modificarMencion: function (button) {
        var view = Ext.widget('mencion_edit'),
            record = this.getList().getSelectionModel().getLastSelected();

        view.setTitle(perfil.etiquetas.lbTtlModificar);
        view.down('button[action=aplicar]').hide();

        view.down('form').loadRecord(record);
    },

    eliminarMencion: function (button) {
        var me = this,
            record = me.getList().getSelectionModel().getSelection(),
            store = me.getList().getStore(),
            mensaje = perfil.etiquetas.lbMsgConfEliminarM;

        if (record.length == 1)
            mensaje = perfil.etiquetas.lbMsgConfEliminar;

        mostrarMensaje(
            2,
            mensaje,
            function (btn, text) {
                if (btn == 'ok') {
                    store.remove(record);
                    me.sincronizarStore(me.getList(), store);
                }
            }
        )
    },

    guardarMencion: function (button) {
        var win = button.up('window'),
            form = win.down('form'),
            me = this;

        if (form.getForm().isValid()) {
            var record = form.getRecord(),
                values = form.getValues();

            //modificando
            if (record) {
                values.denominacion = form.down('pensum_facultad_combo').getRawValue();
                record.set(values);
            }
            //adicionando
            else {
                me.getList().getStore().add(values);
            }

            me.sincronizarStore(me.getList(), me.getList().getStore());

            if (button.action === 'aceptar')
                win.close();
            else if (button.action === 'aplicar')
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

                    grid.down('mencion_paging').moveLast();
                    //grid.down('enfasilisttbar').updateInfo();
                } else if (batch.operations[0].action == "update") {
                    var response = Ext.decode(batch.operations[0].response.responseText);
                    if (response.codMsg == 3)
                        grid.down('mencion_paging').doRefresh();
                } else if (batch.operations[0].action == "destroy") {
                    if (store.count() > 0)
                        grid.down('mencion_paging').doRefresh();//me.getList().down('enfasilisttbar').doRefresh();
                    else
                        grid.down('mencion_paging').movePrevious();
                }
            },
            failure: function () {
                grid.down('mencion_paging').doRefresh();
            }
        });
    }
});