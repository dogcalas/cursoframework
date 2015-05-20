Ext.define('GestAreas.controller.Areas', {
    extend: 'Ext.app.Controller',

    views: [
        'GestAreas.view.area.Grid',
        'GestAreas.view.area.Edit',
        'GestAreas.view.area.ToolBar',
        'GestAreas.view.area.PagingToolBar',
        'GestAreas.view.area_general.Combo'
    ],
    stores: [
        'GestAreas.store.Areas',
        'GestAreas.store.AreasGenerales'
    ],
    models: [
        'GestAreas.model.Area',
        'GestAreas.model.AreaGeneral'
    ],

    refs: [
        {ref: 'list', selector: 'area_grid'},
        {ref: 'arealisttbar', selector: 'arealisttbar'}
    ],

    init: function () {
        var me = this;

        me.control({
            'area_grid': {
                selectionchange: me.manejarBotones
            },
            'arealisttbar button[action=adicionar]': {
                click: me.adicionarArea
            },
            'arealisttbar button[action=modificar]': {
                click: me.modificarArea
            },
            'arealisttbar button[action=eliminar]': {
                click: me.eliminarArea
            },
            'area_edit button[action=aceptar]': {
                click: me.guardarArea
            },
            'area_edit button[action=aplicar]': {
                click: me.guardarArea
            }
        });
    },

    manejarBotones: function (sm, selections) {
        var me = this,
            tbar = me.getArealisttbar();

        tbar.down('button[action=modificar]').setDisabled(selections.length !== 1);
        tbar.down('button[action=eliminar]').setDisabled(selections.length === 0);
    },

    adicionarArea: function (button) {
        var view = Ext.widget('area_edit');
        view.setTitle(perfil.etiquetas.lbTtlAdicionar);
    },

    modificarArea: function (button) {
        var view = Ext.widget('area_edit'),
            record = this.getList().getSelectionModel().getLastSelected();

        view.setTitle(perfil.etiquetas.lbTtlModificar);
        view.down('button[action=aplicar]').hide();

        view.down('form').loadRecord(record);
    },

    eliminarArea: function (button) {
        var me = this,
            record = me.getList().getSelectionModel().getSelection(),
            store = me.getList().getStore(),
            mensaje = perfil.etiquetas.lbMsgConfEliminarM;

        if (record.length == 1)
            mensaje = perfil.etiquetas.lbMsgConfEliminar + " '" + record[0].get('descripcion_area') + "'?";

        mostrarMensaje(
            2,
            mensaje,//perfil.etiquetas.lbMsgConfEliminar + " '" + record.get('descripcion_area') + "'",
            function (btn, text) {
                if (btn == 'ok') {
                    store.remove(record);
                    me.sincronizarStore(me.getList(), store);
                }
            }
        )
    },

    guardarArea: function (button) {
        var win = button.up('window'),
            form = win.down('form'),
            me = this,
            store = me.getList().getStore();

        if (form.getForm().isValid()) {
            var record = form.getRecord(),
                values = form.getValues();

            //modificando
            if (record) {
                record.set(values);
                record.set('descripcion_area_general', form.down('area_general_combo').getRawValue());
            }
            //insertando
            else {
                store.add(values);
            }

            if (button.action === 'aceptar')
                win.close();
            else if (button.action === 'aplicar')
                form.getForm().reset();

            me.sincronizarStore(me.getList(), store);
        }
    },

    sincronizarStore: function (grid, store) {
        store.sync({
            //scope: this,
            success: function (batch) {
                if (batch.operations[0].action == "create") {
                    //var idcarrera = Ext.decode(batch.operations[0].response.responseText).idcarrera;
                    //store.last().set('idcarrera', idcarrera);

                    grid.down('arealistpbar').moveLast();
                    //grid.down('carreralistpbar').updateInfo();
                } else if (batch.operations[0].action == "destroy") {
                    if (store.count() > 0)
                        grid.down('arealistpbar').doRefresh();//me.getList().down('carreralistpbar').doRefresh();
                    else
                        grid.down('arealistpbar').movePrevious();
                }
            },
            failure: function () {
                grid.down('arealistpbar').doRefresh();
            }
        });
    }
});