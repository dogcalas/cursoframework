Ext.define('GestLocales.view.locales.LocalesList', {
    extend: 'Ext.grid.Panel',
    alias: 'widget.locallist',

    store: 'GestLocales.store.Locales',

    initComponent: function () {
        var me = this;

        me.selModel = Ext.create('Ext.selection.RowModel', {
            id: 'idSelectionLocalesGrid',
            mode: 'MULTI'
        });

        me.bbar = Ext.create('Ext.toolbar.Paging', {
            displayInfo: true,
            store: 'GestLocales.store.Locales'
        });

        me.viewConfig = {
            getRowClass: function(record){
                if (record.get('estado') === false)
                    return 'FilaRoja';
            }
        };

        me.columns = [
            { dataIndex: 'idaula', hidden: true},
            { dataIndex: 'idtipo_aula', hidden: true},
            { dataIndex: 'idcampus', hidden: true},
            { dataIndex: 'estado',  hidden: true },
            { header: perfil.etiquetas.lbHdrDescripcion, dataIndex: 'aula', flex: 1},
            { header: perfil.etiquetas.lbHdrTipoLocal, dataIndex: 'local', flex: 1},
            { header: perfil.etiquetas.lbHdrUbicacion, dataIndex: 'abrev', flex: 1},
            { header: perfil.etiquetas.lbHdrCapacidad, dataIndex: 'capacidad', flex: 1}
        ];

        me.callParent(arguments);
    }
});
