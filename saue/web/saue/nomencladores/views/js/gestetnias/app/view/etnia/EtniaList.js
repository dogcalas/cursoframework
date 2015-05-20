Ext.define('GestEtnias.view.etnia.EtniaList', {
    extend: 'Ext.grid.Panel',
    alias: 'widget.etnialist',

    store: 'GestEtnias.store.Etnia',

    selModel: Ext.create('Ext.selection.RowModel', {
        id: 'idSelectionEtniaGrid',
        mode: 'MULTI'
    }),

    initComponent: function () {
        var me = this;

        me.bbar = Ext.create('Ext.toolbar.Paging', {
            displayInfo: true,
            store: 'GestEtnias.store.Etnia'
        });

        me.viewConfig = {
            getRowClass: function(record){
                if (record.get('estado') === false)
                    return 'FilaRoja';
            }
        };

        me.columns = [
            { dataIndex: 'idetnia', hidden: true},
            { dataIndex: 'estado',  hidden: true },
            { header: perfil.etiquetas.lbHdrDescripcion, dataIndex: 'etnia', flex: 1}
        ];

        me.callParent(arguments);
    }
});