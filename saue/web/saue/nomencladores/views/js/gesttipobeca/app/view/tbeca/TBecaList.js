Ext.define('App.view.tbeca.TBecaList', {
    extend: 'Ext.grid.Panel',
    alias: 'widget.tbecalist',
    id: 'gdtbeca',

    store: 'App.store.stTBeca',


    initComponent: function () {
        var me = this;

        me.selModel = Ext.create('Ext.selection.RowModel', {
            id: 'idSelectionTBecaGrid',
            mode: 'MULTI'
        });



        me.bbar = Ext.create('Ext.toolbar.Paging', {
            displayInfo: true,
            store: 'App.store.stTBeca'
        });

        me.viewConfig = {
            getRowClass: function(record){
                if (record.get('estado') === false)
                    return 'FilaRoja';
            }
        };


        me.columns = [
            { dataIndex: 'idtipobeca', hidden: true},
            { dataIndex: 'estado',  hidden: true },
            {header: perfil.etiquetas.lbHdrDescripcion, dataIndex: 'descripcion', flex: 1},
            {header: perfil.etiquetas.lbHdrDescuento, dataIndex: 'descuento', flex: 1}
        ];

        me.callParent(arguments);
    }
});
