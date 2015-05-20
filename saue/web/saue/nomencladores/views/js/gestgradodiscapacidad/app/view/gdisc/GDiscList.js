Ext.define('App.view.gdisc.GDiscList', {
    extend: 'Ext.grid.Panel',
    alias: 'widget.gdisclist',
    id: 'gdgdisc',

    store: 'App.store.stGDisc',


    initComponent: function () {
        var me = this;

        me.selModel = Ext.create('Ext.selection.RowModel', {
            id: 'idSelectionGDiscGrid',
            mode: 'MULTI'
        });



        me.bbar = Ext.create('Ext.toolbar.Paging', {
            displayInfo: true,
            store: 'App.store.stGDisc'
        });

        me.viewConfig = {
            getRowClass: function(record){
                if (record.get('estado') === false)
                    return 'FilaRoja';
            }
        };


        me.columns = [
            { dataIndex: 'idgradodiscapacidad', hidden: true},
            { dataIndex: 'estado',  hidden: true },
            { header: perfil.etiquetas.lbHdrDescripcion, dataIndex: 'descripcion', flex: 1}
        ];

        me.callParent(arguments);
    }
});
