Ext.define('GestCoRequisitos.controller.GestCoRequisitos', {
    extend: 'Ext.app.Controller',

    views: [
        'GestCoRequisitos.view.corequisito.CoRequisitoList',
        'GestCoRequisitos.view.corequisito.CoRequisitoListToolBar',
        'GestCoRequisitos.view.corequisito.MateriaCoRequisitoList',
        'GestCoRequisitos.view.corequisito.CoRequisitoEdit',
        'GestCoRequisitos.view.corequisito.PagingToolBar',
        'GestCoRequisitos.view.materia.Grid',
        'GestCoRequisitos.view.materia.PagingToolBar',
        'GestCoRequisitos.view.materia.ToolBar'
    ],
    stores: [
        'GestCoRequisitos.store.CoRequisitos',
        'GestCoRequisitos.store.MateriasParaCoRequisitos',
        'GestCoRequisitos.store.TipoMaterias'
    ],
    models: [
        'GestCoRequisitos.model.CoRequisito',
        'GestCoRequisitos.model.MateriasParaCoRequisito',
        'GestCoRequisitos.model.Materia',

        'GestMaterias.model.TipoMateria'
    ],

    refs: [
        {ref: 'list', selector: 'corequisitolist'},
        {ref: 'edit', selector: 'corequisitoedit'},
        {ref: 'tbar', selector: 'corequisitolisttbar'},
        {ref: 'listm', selector: 'viewport > corequisitos_materia_grid'},
        {ref: 'listmp', selector: 'corequisitoedit > materiacorequisitolist'}
    ],

    init: function () {
        var me = this;

        me.control({
            'corequisitolist': {
                selectionchange: me.manejarBotones
            },
            'viewport > corequisitos_materia_grid': {
                select: me.cargarCoRequisitos,
                selectionchange: me.setearTitulo,
                deselect: me.desactivarToolBar
            },
            'corequisitolisttbar button[action=adicionar]': {
                click: me.adicionarCoRequisito
            },
            'corequisitolisttbar button[action=eliminar]': {
                click: me.eliminarCoRequisito
            }, /*
             'corequisitolisttbar button[action=buscar]': {
             click: me.buscarCoRequisito
             },*/
            'corequisitoedit button[action=aceptar]': {
                click: me.guardarCoRequisito
            },
            'corequisitoedit button[action=aplicar]': {
                click: me.guardarCoRequisito
            }
        });

        me.getGestCoRequisitosStoreMateriasParaCoRequisitosStore().on(
            {
                beforeload: {fn: me.setearExtraParams, scope: this}
            }
        );

        me.getGestCoRequisitosStoreCoRequisitosStore().on(
            {
                beforeload: {fn: me.setearExtraParams, scope: this}
            }
        );
    },

    setearExtraParams: function (store) {
        var me = this,
            idmateria = me.getListm().getSelectionModel().getLastSelected().get('idmateria');

        store.getProxy().extraParams = {idmateria: idmateria};
    },

    manejarBotones: function (store, selected) {
        var me = this,
            tbar = me.getTbar();
        tbar.down('button[action=eliminar]').setDisabled(selected.length === 0);
    },

    setearTitulo: function (store, selected) {
        var me = this,
            titulo = perfil.etiquetas.lbTtlCoLista;

        if (selected.length === 0) {
            titulo += "'X'";
            me.getList().getStore().removeAll();
        } else {
            titulo += "'" + selected[0].get('descripcion') + "'";
        }

        me.getList().setTitle(titulo);
    },

    desactivarToolBar: function () {
        var me = this;
        me.getTbar().disable();
    },

    cargarCoRequisitos: function (sm, materia) {
        var me = this;

        //me.getList().setTitle(perfil.etiquetas.lbTtlCoLista + "'"+sm.getLastSelected().get('descripcion')+"'");
        me.getList().getStore().load();
        this.getTbar().enable();
    },

    adicionarCoRequisito: function (button) {
        Ext.widget('corequisitoedit').setTitle(perfil.etiquetas.lbTtlCoAdicionar);
        var me = this,
            listamp = me.getListmp();
        /*listam = me.getListm();
         idmateria = listam.getSelectionModel().getLastSelected().get('idmateria');*/
        listamp.getStore().load();
    },

    /*modificarCoRequisito: function (button) {
     var view = Ext.widget('corequisitoedit'),
     record = this.getList().getSelectionModel().getLastSelected();

     view.setTitle(perfil.etiquetas.lbTtlModificar);

     //view.down('form').loadRecord(record);
     },*/

    eliminarCoRequisito: function (button) {
        var record = this.getList().getSelectionModel().getSelection(),
            store = this.getList().getStore(),
            mensaje = perfil.etiquetas.lbMsgConfEliminarM;

        if (record.length == 1)
            mensaje = perfil.etiquetas.lbMsgConfEliminar + " '" + record[0].get('descripcion') + "'?";

        mostrarMensaje(
            2,
            mensaje,
            function (btn, text) {
                if (btn == 'ok') {
                    store.remove(record);
                    store.sync({
                        success: function (batch) {
                            if (batch.total > 1)
                                mostrarMensaje(1, perfil.etiquetas.lbMsgInfEliminarM);
                            else
                                mostrarMensaje(1, perfil.etiquetas.lbMsgInfEliminar);
                            store.reload();
                        }
                    });
                }
            }
        )
    },

    /*buscarCoRequisito: function (button) {
     alert('Buscar');
     },*/

    guardarCoRequisito: function (button) {
        var win = button.up('window'),
            records = win.down('grid').getSelectionModel().getSelection(),
            me = this,
            idmateria = me.getListm().getSelectionModel().getLastSelected().get('idmateria');

        if (records.length > 0) {
            var corequisito;
            for (var i = 0; i < records.length; i++) {
                corequisito = Ext.create('GestCoRequisitos.model.CoRequisito', {
                    idco_requisito: null,
                    idmateria: idmateria,
                    idmateriaco: records[i].get('idmateriaco'),
                    codmateria: records[i].get('codmateria'),
                    descripcion: records[i].get('descripcion')
                });
                me.getList().getStore().add(corequisito);
            }

            me.getList().getStore().sync({
                success: function (batch) {
                    if (batch.total > 1)
                        mostrarMensaje(1, perfil.etiquetas.lbMsgInfAdicionarM);
                    else
                        mostrarMensaje(1, perfil.etiquetas.lbMsgInfAdicionar);
                    if (button.action === 'aplicar') {
                        win.down('grid').getStore().reload();
                    }
                    me.getList().getStore().reload();
                }
            });

            if (button.action === 'aceptar')
                win.close();
        }
    }
});
