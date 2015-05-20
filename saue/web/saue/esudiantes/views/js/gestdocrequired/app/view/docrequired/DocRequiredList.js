Ext.define('GestDocRequired.view.docrequired.DocRequiredList', {
    extend: 'Ext.grid.Panel',
    alias: 'widget.docrequiredlist',

    store: 'GestDocRequired.store.DocRequired',

    initComponent: function () {
        var me = this;

        me.selModel = Ext.create('Ext.selection.RowModel', {
            id: 'idSelectionDocRequiredGrid',
            mode: 'MULTI'
        });

        me.bbar = Ext.create('Ext.toolbar.Paging', {
            displayInfo: true,
            store: 'GestDocRequired.store.DocRequired'
        });

        me.viewConfig = {
            getRowClass: function(record){
                if (record.get('estado') === false)
                    return 'FilaRoja';
            }
        };

        me.columns = [
            { dataIndex: 'iddocumentorequerido', hidden: true},
            { dataIndex: 'estado',  hidden: true },
            { header: perfil.etiquetas.lbHdrDescripcion, dataIndex: 'descripcion', flex: 1}
        ];

        me.callParent(arguments);
    }
});