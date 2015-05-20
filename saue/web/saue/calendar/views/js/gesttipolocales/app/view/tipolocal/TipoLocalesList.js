Ext.define('GestTipoLocales.view.tipolocal.TipoLocalesList', {
    extend: 'Ext.grid.Panel',
    alias: 'widget.tipolocallist',

    store: 'GestTipoLocales.store.TipoLocales',

    initComponent: function () {
        var me = this;

        me.selModel = Ext.create('Ext.selection.RowModel', {
            id: 'idSelectionTipoLocalesGrid',
            mode: 'MULTI'
        });

        me.bbar = Ext.create('Ext.toolbar.Paging', {
            displayInfo: true,
            store: 'GestTipoLocales.store.TipoLocales'
        });

        me.viewConfig = {
            getRowClass: function(record){
                if (record.get('estado') === false)
                    return 'FilaRoja';
            }
        };

        me.columns = [
            { dataIndex: 'idtipo_aula', hidden: true},
            { dataIndex: 'estado',  hidden: true },
            { header: perfil.etiquetas.lbHdrDescripcion, dataIndex: 'descripcion', flex: 1}
        ];

        me.callParent(arguments);
    }
});