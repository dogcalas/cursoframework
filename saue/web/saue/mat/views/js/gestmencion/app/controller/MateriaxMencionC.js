Ext.define('GestMateriaxMencion.controller.MateriaxMencionC', {
    extend: 'Ext.app.Controller',

    views: [
        'GestMateriaxMencion.view.materiaxmencion.Grid',
        'GestMateriaxMencion.view.materiaxmencion.Toolbar',
        'GestMateriaxMencion.view.materia.Grid',
        'GestMateriaxMencion.view.materia.Toolbar',
        'GestMateriaxMencion.view.mencion.Combo'
    ],
    stores: [
        'GestMateriaxMencion.store.MateriasxMencion',
        'GestMateriaxMencion.store.Materias',
        'GestMateriaxMencion.store.Menciones'
    ],
    models: [

    ],

    refs: [
        {ref: 'materiaxmencion_tbar', selector: 'materiaxmencion_tbar'},
        {ref: 'materiaxmencion_grid', selector: 'materiaxmencion_grid'},
        {ref: 'materiaxmencion_materia_grid', selector: 'materiaxmencion_materia_grid'},
        {ref: 'mencion_combo', selector: 'mencion_combo'}
    ],

    init: function () {
        var me = this;

        me.control({

            'mencion_combo': {
                select: me.cargarMaterias
            },
            'materiaxmencion_grid dataview': {
                beforedrop: me.setearValores
            },
            'materiaxmencion_tbar button[action=eliminar]': {
                click: me.eliminarMatxMencion
            },
            'materiaxmencion_grid': {
                selectionchange: me.manejarBotones
            }
        });

        me.getGestMateriaxMencionStoreMateriasStore().on(
            {
                beforeload: {fn: me.setearExtraParams, scope: this}
            }
        );

        me.getGestMateriaxMencionStoreMateriasxMencionStore().on(
            {
                beforeload: {fn: me.setearExtraParams, scope: this}
            }
        );
    },

    cargarMaterias: function (combo) {
        var me = this,
            materia_store = me.getGestMateriaxMencionStoreMateriasStore(),
            materiaxmencion_store = me.getGestMateriaxMencionStoreMateriasxMencionStore();

        materia_store.load();
        materiaxmencion_store.load();
    },

    manejarBotones: function (store, selected) {
        var me = this,
            tbar = me.getMateriaxmencion_tbar();
        tbar.down('button[action=eliminar]').setDisabled(selected.length === 0);
    },

    eliminarMatxMencion: function (button) {
        var me = this,
            materia_mencion_grid = me.getMateriaxmencion_grid(),
            record = materia_mencion_grid.getSelectionModel().getLastSelected(),
            store = materia_mencion_grid.getStore();

        mostrarMensaje(
            2,
            perfil.etiquetas.lbMsgConfEliminar + " '" + record.get('descripcion') + "'",
            function (btn, text) {
                if (btn == 'ok') {
                    store.remove(record);
                    store.sync({
                        callback: function () {
                            store.reload();
                            me.getMateriaxmencion_materia_grid().getStore().reload();
                        }
                    });
                }
            }
        )
    },

    obtenerExtraParams: function () {
        var me = this,
            mencion_combo = me.getMencion_combo(),
            extra_params = {};

        //Obtenindo valor del combo de mención en caso de que halla cambiado
        if (mencion_combo.isDirty())
            extra_params.idmencion = mencion_combo.getValue();

        return extra_params
    },

    setearExtraParams: function (store) {
        var me = this,
            extra_params = me.obtenerExtraParams();

        store.getProxy().extraParams = extra_params;
    },

    setearValores: function (node, data, overModel, dropPosition, dropHandlers) {
        var me = this,
            mencion_combo = me.getMencion_combo();

        if (mencion_combo.validate() && me.validarCantMaterias(mencion_combo)) {
            var materiaxmencion_grid = me.getMateriaxmencion_grid(),
                materiaxmencion_materia_grid = me.getMateriaxmencion_materia_grid(),
                materia_x_mencion;

            for (var i = 0; i < data.records.length; i++) {
                materia_x_mencion = Ext.create('GestMateriaxMencion.model.MateriaxMencion', {
                    idmencion: mencion_combo.getValue(),
                    idmateria: data.records[i].get('idmateria'),
                    codmateria: data.records[i].get('codmateria'),
                    descripcion: data.records[i].get('descripcion'),
                    estado: data.records[i].get('estado')
                });

                data.records[i] = materia_x_mencion;
            }

            dropHandlers.processDrop();

            materiaxmencion_grid.getStore().sync(
                {
                    callback: function () {
                        materiaxmencion_materia_grid.getStore().reload();
                        materiaxmencion_grid.getStore().reload();
                    }
                }
            );
        } else
            return false;

    },

    validarCantMaterias: function (combo) {
        var me = this;
        if (combo.displayTplData) {
            console.log( me.getMateriaxmencion_grid().getStore().count());
            var cant = me.getMateriaxmencion_grid().getStore().count();
            if (combo.displayTplData[0].cant_materias <= cant){
                Ext.MessageBox.show({
                    //title: 'Imposible',
                    msg: 'Ha excedido la cantidad de materias permitidas en esta mención',
                    buttons: Ext.MessageBox.OK,
                    icon: Ext.MessageBox.INFO
                });
                return false;
            }
        }
        return true;
    }
});