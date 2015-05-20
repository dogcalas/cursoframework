Ext.define('GestDiscapacidad.view.discapacidad.DiscapacidadList', {
    extend: 'Ext.grid.Panel',
    alias: 'widget.discapacidadlist',

    store: 'GestDiscapacidad.store.Discapacidad',

    selModel: Ext.create('Ext.selection.RowModel', {
        id: 'idSelectionDiscapacidadGrid',
        mode: 'MULTI'
    }),

    initComponent: function () {
        var me = this;

        me.bbar = Ext.create('Ext.toolbar.Paging', {
            displayInfo: true,
            store: 'GestDiscapacidad.store.Discapacidad'
        });

        me.viewConfig = {
            getRowClass: function(record){
                if (record.get('estado') === false)
                    return 'FilaRoja';
            }
        };

        me.columns = [
            { dataIndex: 'iddiscapacidad', hidden: true},
            { dataIndex: 'estado',  hidden: true },
            { header: perfil.etiquetas.lbHdrDescripcion, dataIndex: 'discapacidad', flex: 1}
        ];

        me.callParent(arguments);
    }
});