Ext.define('GestConv.view.MateriasConvList', {
    extend: 'Ext.grid.Panel',

    alias: 'widget.convlist',
    id:'convlist',
    store: 'GestConv.store.MateriasConva',

    selModel: Ext.create('Ext.selection.RowModel', {
        id: 'idSelectionConvlist',
        mode: 'SINGLE'
    }),
    columns: [
        { dataIndex: 'idmateriaconva', hidden: true},
        { dataIndex: 'idmateria', hidden: true},
        { header: 'Materia convalidación', dataIndex: 'descripcion',flex: 4},
        {
            xtype: 'booleancolumn', 
            trueText: '<b>×<b>',
            falseText: '-', 
            header: 'Principal', 
            dataIndex: 'principal', 
            flex: 1,
            align:'center'
        }
    ],
    viewConfig:{
            getRowClass: function(record, rowIndex, rowParams, store){
                if (record.data.principal == true)
                    return 'FilaRoja';
            }
        },

    padding:  '10 5 0 0',
    bbar : Ext.create('GestConv.view.ToolBarConva'),
    initComponent: function () {
        var me = this;

        me.callParent(arguments);
    }
});