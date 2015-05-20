Ext.define('RegMaterias.view.materia.MateDetaList', {
    extend: 'Ext.grid.Panel',

    alias: 'widget.matedeta',
    id: 'matedeta',
    store: 'GestNotas.store.Alumnos',

    selModel: Ext.create('Ext.selection.RowModel', {
        id: 'idSelectionAlummnoGrid',
        mode: 'SIMPLE'
    }),
   
    columns: [
        { dataIndex: 'idmateria', hidden: true, hideable: false},
        { header: 'Materia', dataIndex: 'descripcion'}
        
    ],

    initComponent: function () {
        var me = this;
        me.bbar = Ext.create('Ext.toolbar.Paging', {
            displayInfo: true,
            store: me.store
        });

        me.callParent(arguments);
    }

});