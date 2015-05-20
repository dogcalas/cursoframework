Ext.define('GestTipoPeriodo.view.tipoperiodo.TipoPeriodoList', {
    extend: 'Ext.grid.Panel',
    alias: 'widget.tipoperiodolist',
    id: 'gdtipoperiodo',

    store: 'GestTipoPeriodo.store.TipoPeriodo',

    initComponent: function () {
        var me = this;

        me.selModel = Ext.create('Ext.selection.RowModel', {
            id: 'idSelectionTipoPeriodoGrid',
            mode: 'MULTI'
        });

        me.bbar = Ext.create('Ext.toolbar.Paging', {
            displayInfo: true,
            store: 'GestTipoPeriodo.store.TipoPeriodo'
        });

        me.viewConfig = {
            getRowClass: function(record){
                if (record.get('estado') === false)
                    return 'FilaRoja';
            }
        };

        me.columns = [
            { dataIndex: 'idtipo_periododocente', hidden: true, hideable: false},
            { header:'Estado', dataIndex: 'estado',  hidden: true },
            { header: perfil.etiquetas.lbHdrDescripcion, dataIndex: 'tipoperiodo', flex: 1},
            { header: 'Duración (semanas)', dataIndex: 'duracion', flex: 1}
        ];

        me.callParent(arguments);
    }
});
