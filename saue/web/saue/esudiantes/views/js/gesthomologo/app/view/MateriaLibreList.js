Ext.define('GestHom.view.MateriaLibreList', {
    extend: 'Ext.grid.Panel',

    alias: 'widget.materialibrelist',
    id: 'materialibrelist',
    store: 'GestHom.store.MateriasLibre',
    title: "Materias del pensum anterior",
    selModel: selModel = Ext.create('Ext.selection.RowModel', {
        id: 'idSelectionMGrid',
        mode: 'SINGLE'
    }),
    columns: [
        {dataIndex: 'idmateria', hidden: true},
        {header: 'Código', dataIndex: 'codigo', width: 45},
        {header: 'Materia', dataIndex: 'descripcion', flex: 1},
        {header: 'Período', dataIndex: 'periodo'},
        {header: 'Paralelo', dataIndex: 'paralelo', width: 50},
        {header: 'Nota', dataIndex: 'nota', width: 35}
    ],
    columnLines: true,

    initComponent: function () {
        var me = this;
        me.bbar = [
            Ext.create('Ext.toolbar.Paging', {
                displayInfo: true,
                id: 'librepaginator',
                width: '100%',
                disabled: true,
                store: me.store
            })
        ];
        me.tbar = [{
            xtype: 'searchfield',
            store: me.store,
            width: '100%',
            fieldLabel: perfil.etiquetas.lbBtnBuscar,
            labelWidth: 40,
            padding: '5',
            filterPropertysNames: ["codmateria", "descripcion"]
        }];

        me.callParent(arguments);
    }
});