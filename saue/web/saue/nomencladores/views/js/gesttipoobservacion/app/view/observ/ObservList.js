Ext.define('App.view.observ.ObservList', {
    extend: 'Ext.grid.Panel',
    alias: 'widget.observlist',
    id: 'gdobserv',

    store: 'App.store.stObserv',


    initComponent: function () {
        var me = this;

        me.selModel = Ext.create('Ext.selection.RowModel', {
            id: 'idSelectionObservGrid',
            mode: 'MULTI'
        });



        me.bbar = Ext.create('Ext.toolbar.Paging', {
            displayInfo: true,
            store: 'App.store.stObserv'
        });

        me.viewConfig = {
            getRowClass: function(record){
                if (record.get('estado') === false)
                    return 'FilaRoja';
            }
        };


        me.columns = [
            { dataIndex: 'idtipoobservacion', hidden: true},
            { dataIndex: 'estado',  hidden: true },
            { header: perfil.etiquetas.lbHdrDescripcion, dataIndex: 'descripcion', flex: 1}

        ];

        me.callParent(arguments);
    }
});
