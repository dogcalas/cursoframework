Ext.define('App.view.tdisc.TDiscList', {
    extend: 'Ext.grid.Panel',
    alias: 'widget.tdisclist',
    id: 'gdtdisc',

    store: 'App.store.stTDisc',


    initComponent: function () {
        var me = this;

        me.selModel = Ext.create('Ext.selection.RowModel', {
            id: 'idSelectionTDiscGrid',
            mode: 'MULTI'
        });



        me.bbar = Ext.create('Ext.toolbar.Paging', {
            displayInfo: true,
            store: 'App.store.stTDisc'
        });

        me.viewConfig = {
            getRowClass: function(record){
                if (record.get('estado') === false)
                    return 'FilaRoja';
            }
        };


        me.columns = [
            { dataIndex: 'idtipodiscapacidad', hidden: true},
            { dataIndex: 'estado',  hidden: true },
            { header: perfil.etiquetas.lbHdrDescripcion, dataIndex: 'descripcion', flex: 1}
        ];

        me.callParent(arguments);
    }
});
