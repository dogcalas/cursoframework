Ext.define('GestCarreras.view.carrera.CarreraList', {
    extend: 'Ext.grid.Panel',
    alias: 'widget.carreralist',

    store: Ext.create('GestCarreras.store.Carreras'),

    initComponent: function () {
        var me = this;

        me.selModel = Ext.create('Ext.selection.CheckboxModel', {
            id: 'idSelectionCarreraGrid'
        });

        me.bbar = Ext.create('GestCarreras.view.carrera.CarreraListPagingToolBar');

        me.tbar = Ext.create('GestCarreras.view.carrera.CarreraListToolBar');

        me.viewConfig = {
            getRowClass: function (record) {
                if (record.get('estado') === false)
                    return 'FilaRoja';
            }
        };

        me.columns = [
            { dataIndex: 'idcarrera', hidden: true, hideable: false},
            { dataIndex: 'estado', hidden: true, hideable: false },
            { dataIndex: 'idfacultad', hidden: true, hideable: false },
            { header: perfil.etiquetas.lbHdrDescripcion, dataIndex: 'descripcion_carrera', flex: 2},
            { header: perfil.etiquetas.lbHdrFacultad, dataIndex: 'facultad', flex: 1}
        ];

        me.callParent(arguments);
    }
});