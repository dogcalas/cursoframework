Ext.define('GestTiposMateria.controller.TiposMaterias', {
    extend: 'Ext.app.Controller',

    views: [
        'GestTiposMateria.view.tipomateria.TiposMateriaList',
        'GestTiposMateria.view.tipomateria.TiposMateriaEdit',
        'GestTiposMateria.view.tipomateria.TiposMateriaListToolBar'
    ],
    stores: [
        'GestTiposMateria.store.TiposMaterias'
    ],
    models: [
        'GestTiposMateria.model.TipoMateria'
    ],
    refs: [
        {ref: 'list', selector: 'tiposmaterialist'},
        {ref: 'tiposmaterialisttbar', selector: 'tiposmaterialisttbar'}
    ],

    init: function () {
        var me = this;
        this.control({

            'tiposmaterialist': {
                selectionchange: me.activarBotones
            },
            'tiposmaterialisttbar button[action=adicionar]': {
                click: me.adicionarTipoMateria
            },
            'tiposmaterialisttbar button[action=modificar]': {
                click: me.modificarTipoMateria
            },
            'tiposmaterialisttbar button[action=eliminar]': {
                click: me.eliminarTipoMateria
            },
            'tiposmateriaedit button[action=aceptar]': {
                click: me.guardarTipoMateria
            },
            'tiposmateriaedit button[action=aplicar]': {
                click: me.guardarTipoMateria
            }
        });
    },

    activarBotones: function (store, selected) {
        var me = this,
            tbar = me.getTiposmaterialisttbar();
        tbar.down('button[action=modificar]').setDisabled(selected.length !== 1);
        tbar.down('button[action=eliminar]').setDisabled(selected.length === 0);
    },

    adicionarTipoMateria: function (button) {
        var view = Ext.widget('tiposmateriaedit');
        view.setTitle(perfil.etiquetas.lbTtlAdicionar);
    },

    modificarTipoMateria: function (button) {
        var view = Ext.widget('tiposmateriaedit'),
            record = this.getList().getSelectionModel().getLastSelected();

        view.setTitle(perfil.etiquetas.lbTtlModificar);
        view.down('button[action=aplicar]').hide();

        view.down('form').loadRecord(record);
    },

    eliminarTipoMateria: function (button) {
        var record = this.getList().getSelectionModel().getSelection(),
            store = this.getList().getStore(),
            mensaje = perfil.etiquetas.lbMsgConfEliminarM;

        if (record.length == 1)
            mensaje = perfil.etiquetas.lbMsgConfEliminar + " '" + record[0].get('descripcion_tipo_materia') + "'?";

        mostrarMensaje(
            2,
            mensaje,
            function (btn, text) {
                if (btn == 'ok') {
                    store.remove(record);
                    store.sync();
                }
            }
        )
    },

    guardarTipoMateria: function (button) {
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
                this.getGestTiposMateriaStoreTiposMateriasStore().add(values);
            }

            me.getGestTiposMateriaStoreTiposMateriasStore().sync({
                success: function (batch) {
                    if (batch.operations[0].action == "create")
                        me.getGestTiposMateriaStoreTiposMateriasStore().reload();
                },
                failure: function () {
                    me.getGestTiposMateriaStoreTiposMateriasStore().reload();
                }
            });

            if (button.action === 'aceptar')
                win.close();
            else if (button.action === 'aplicar')
                form.getForm().reset();
        }
    }
});