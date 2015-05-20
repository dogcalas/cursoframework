Ext.define('App.view.campus.CampusList', {
    extend: 'Ext.grid.Panel',
    alias: 'widget.campuslist',
    id: 'gdcampus',

    store: 'App.store.stCampus',


    initComponent: function () {
        var me = this;

        me.selModel = Ext.create('Ext.selection.RowModel', {
            id: 'idSelectionCampusGrid',
            mode: 'MULTI'
        });

        me.bbar = Ext.create('Ext.toolbar.Paging', {
            displayInfo: true,
            store: 'App.store.stCampus'
        });

        me.viewConfig = {
            getRowClass: function(record){
                if (record.get('estado') === false)
                    return 'FilaRoja';
            }
        };


        me.columns = [
            { dataIndex: 'idcampus', hidden: true},
            { dataIndex: 'estado',  hidden: true },
            { header: perfil.etiquetas.lbHdrDescripcion, dataIndex: 'descripcion', flex: 1},
            { header: 'Abreviatura', dataIndex: 'abrev', flex: 1}
        ];

        me.callParent(arguments);
    }
});
