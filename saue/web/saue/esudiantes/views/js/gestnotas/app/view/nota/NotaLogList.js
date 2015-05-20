Ext.define('GestNotas.view.nota.NotaLogList', {
    extend: 'Ext.grid.Panel',

    alias: 'widget.notaloglist',
    id: 'notaloglist',
    //title: perfil.etiquetas.lbTtlLista,

    store: 'GestNotas.store.NotaLog',
    colllapsible: true,
    hideCollapseTool:true,
    collapsed: true,
    selType: 'cellmodel',
    height: 300,
    plugins: [
        Ext.create('Ext.grid.plugin.CellEditing', {
            clicksToEdit: 1,
            pluginId: 'cellplugin'
        })
    ],
    columns: [{ dataIndex: 'id_nota_log', hidden: true, hideable: false},
        { dataIndex: 'idalumno', hidden: true, hideable: false},
        { dataIndex: 'idpd', hidden: true, hideable: false},
        { dataIndex: 'idmateria', hidden: true, hideable: false},
        { header: 'Facultad', dataIndex: 'facultad', flex: 1},
        //{ header: 'Nota', dataIndex: 'semana', flex: 1},
        { header: 'Día', dataIndex: 'diasemana', flex: 1},
        { header: 'Fecha', dataIndex: 'fecha', flex: 1},
        { header: 'Usuario', dataIndex: 'usuario', flex: 1},
        { header: 'Paralelo', dataIndex: 'par_curso'},
        { header: 'No. Nota', dataIndex: 'n_nota'},
        { header: 'Nota Inicial', dataIndex: 'nota_ini'},
        { header: 'Nota Final', dataIndex: 'val_nota'},
        { header: 'Observaciones', dataIndex: 'observaciones', flex: 3}
    ],
    
    initComponent: function () {
        var me = this;

       // me.selModel.on("selectionchange", me.manejarBotones, me);

        me.bbar = Ext.create('Ext.toolbar.Paging', {
            displayInfo: true,
            store: me.store
        });


        me.callParent(arguments);
    }
});