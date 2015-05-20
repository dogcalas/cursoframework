Ext.define('GestLocales.view.locales.LocalList', {
    extend: 'Ext.grid.Panel',
    alias: 'widget.locallist',

    store: 'GestLocales.store.Locales',
    selModel: Ext.create('Ext.selection.RowModel', {
        id: 'idSelectionLocalGrid',
        mode: 'MULTI'
    }),

    initComponent: function () {
        var me = this;

        me.bbar = Ext.create('GestLocales.view.locales.LocalListPagingToolBar');

        me.viewConfig = {
            getRowClass: function(record){
                if (record.get('estado') === false)
                    return 'FilaRoja';
            }
        };

        me.tbar =  Ext.widget('locallisttbar')

        me.columns = [
            { dataIndex: 'idaula', hidden: true, hideable: false},
            { dataIndex: 'idtipo_aula', hidden: true, hideable: false},
            { dataIndex: 'idubicacion', hidden: true, hideable: false},
            { dataIndex: 'estado', hidden: true, hideable: false},
            { header: perfil.etiquetas.lbHdrDescripcion, dataIndex: 'aula', flex: 1},
            { header: perfil.etiquetas.lbHdrTipoLocal, dataIndex: 'tipolocal', flex: 1},
            { header: perfil.etiquetas.lbHdrUbicacion, dataIndex: 'descripcion', flex: 1},
            { header: perfil.etiquetas.lbHdrCapacidad, dataIndex: 'capacidad', flex: 1}
        ];

        me.callParent(arguments);
    }
});