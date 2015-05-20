Ext.define('GestTipoPrograma.view.tipoprograma.TipoProgramaList', {
    extend: 'Ext.grid.Panel',
    alias: 'widget.tipoprogramalist',

    store: 'GestTipoPrograma.store.TipoPrograma',

    initComponent: function () {
        var me = this;

        me.selModel = Ext.create('Ext.selection.RowModel', {
            id: 'idSelectionTipoProgramaGrid',
            mode: 'MULTI'
        });

        me.bbar = Ext.create('Ext.toolbar.Paging', {
            displayInfo: true,
            store: 'GestTipoPrograma.store.TipoPrograma'
        });

        me.viewConfig = {
            getRowClass: function(record){
                if (record.get('estado') === false)
                    return 'FilaRoja';
            }
        };

        me.columns = [
            { dataIndex: 'idtipoprograma', hidden: true},
            { dataIndex: 'estado',  hidden: true },
            { header: perfil.etiquetas.lbHdrDescripcion, dataIndex: 'descripcion', flex: 1}
        ];

        me.callParent(arguments);
    }
});