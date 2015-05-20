Ext.define('GestPreRequisitos.controller.GestPreRequisitos', {
    extend: 'Ext.app.Controller',

    views: [
        'GestPreRequisitos.view.prerequisito.PreRequisitoList',
        'GestPreRequisitos.view.prerequisito.PreRequisitoListToolBar',
        'GestPreRequisitos.view.prerequisito.MateriaPreRequisitoList',
        'GestPreRequisitos.view.prerequisito.PreRequisitoEdit',
        'GestPreRequisitos.view.prerequisito.PagingToolBar',
        'GestPreRequisitos.view.materia.Grid',
        'GestPreRequisitos.view.materia.PagingToolBar',
        'GestPreRequisitos.view.materia.ToolBar'
    ],
    stores: [
        'GestPreRequisitos.store.PreRequisitos',
        'GestPreRequisitos.store.MateriasParaPreRequisitos',
        'GestPreRequisitos.store.TipoMaterias'
    ],
    models: [
        'GestPreRequisitos.model.PreRequisito',
        'GestPreRequisitos.model.MateriasParaPreRequisito',
        'GestPreRequisitos.model.Materia',

        'GestMaterias.model.TipoMateria'
    ],

    refs: [
        {ref: 'list', selector: 'prerequisitolist'},
        {ref: 'edit', selector: 'prerequisitoedit'},
        {ref: 'tbar', selector: 'prerequisitolisttbar'},
        {ref: 'listm', selector: 'viewport > prerequisitos_materia_grid'},
        {ref: 'listmp', selector: 'prerequisitoedit > materiaprerequisitolist'}
    ],

    init: function () {
        var me = this;

        me.control({
            'prerequisitolist': {
                selectionchange: me.manejarBotones
            },
            'viewport > prerequisitos_materia_grid': {
                select: me.cargarPreRequisitos,
                selectionchange: me.setearTitulo,
                deselect: me.desactivarToolBar
            },
            'prerequisitolisttbar button[action=adicionar]': {
                click: me.adicionarPreRequisito
            },
            'prerequisitolisttbar button[action=eliminar]': {
                click: me.eliminarPreRequisito
            }, /*
             'prerequisitolisttbar button[action=buscar]': {
             click: me.buscarPreRequisito
             },*/
            'prerequisitoedit button[action=aceptar]': {
                click: me.guardarPreRequisito
            },
            'prerequisitoedit button[action=aplicar]': {
                click: me.guardarPreRequisito
            }
        });

        me.getGestPreRequisitosStoreMateriasParaPreRequisitosStore().on(
            {
                beforeload: {fn: me.setearExtraParams, scope: this}
            }
        );

        me.getGestPreRequisitosStorePreRequisitosStore().on(
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
            titulo = perfil.etiquetas.lbTtlPreLista;

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

    cargarPreRequisitos: function (sm, materia) {
        var me = this;

        //me.getList().setTitle(perfil.etiquetas.lbTtlPreLista + "'"+sm.getLastSelected().get('descripcion')+"'");
        me.getList().getStore().load();
        this.getTbar().enable();
    },

    adicionarPreRequisito: function (button) {
        Ext.widget('prerequisitoedit').setTitle(perfil.etiquetas.lbTtlPreAdicionar);
        var me = this,
            listamp = me.getListmp();
        /*listam = me.getListm();
         idmateria = listam.getSelectionModel().getLastSelected().get('idmateria');*/
        listamp.getStore().load(

        );
    },

    /*modificarPreRequisito: function (button) {
     var view = Ext.widget('prerequisitoedit'),
     record = this.getList().getSelectionModel().getLastSelected();

     view.setTitle(perfil.etiquetas.lbTtlModificar);

     //view.down('form').loadRecord(record);
     },*/

    eliminarPreRequisito: function (button) {
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

    /*buscarPreRequisito: function (button) {
     alert('Buscar');
     },*/

    guardarPreRequisito: function (button) {
        var win = button.up('window'),
            records = win.down('grid').getSelectionModel().getSelection(),
            me = this,
            idmateria = me.getListm().getSelectionModel().getLastSelected().get('idmateria');

        if (records.length > 0) {
            var prerequisito;
            for (var i = 0; i < records.length; i++) {
                prerequisito = Ext.create('GestPreRequisitos.model.PreRequisito', {
                    idpre_requisito: null,
                    idmateria: idmateria,
                    idmateriapre: records[i].get('idmateriapre'),
                    codmateria: records[i].get('codmateria'),
                    descripcion: records[i].get('descripcion')
                });
                me.getList().getStore().add(prerequisito);
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
