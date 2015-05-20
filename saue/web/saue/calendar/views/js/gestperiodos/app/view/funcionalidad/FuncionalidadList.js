Ext.define('GestPeriodos.view.funcionalidad.FuncionalidadList', {
    extend: 'Ext.grid.Panel',
    alias: 'widget.funcionalidadlist',
    title: 'Funcionalidades',
    height: 395,
    width: 300,
    frame: true,
    region: 'west',
    iconCls: 'icon-grid',
    layout: 'fit',

    store: 'GestPeriodos.store.Funcionalidad',

    initComponent: function () {
        var me = this;

        me.selModel = Ext.create('Ext.selection.RowModel', {
            id: 'idSelectionFuncionalidadGrid',
            mode: 'MULTI'
        });

        me.columns = [
            { dataIndex: 'idfuncionalidad', hidden: true, hideable: false},
            { header: 'Descripción', dataIndex: 'den', flex: 1}
        ];

        me.callParent(arguments);
    }
});


