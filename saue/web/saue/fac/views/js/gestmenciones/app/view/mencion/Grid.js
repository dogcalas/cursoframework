Ext.define('GestMenciones.view.mencion.Grid', {
    extend: 'Ext.grid.Panel',
    alias: 'widget.mencion_grid',

    store: 'GestMenciones.store.Menciones',

    initComponent: function () {
        var me = this;

        me.selModel = Ext.create('Ext.selection.CheckboxModel');

        me.bbar = Ext.create('GestMenciones.view.mencion.PagingToolBar');

        me.viewConfig = {
            getRowClass: function (record) {
                if (record.get('estado') === false)
                    return 'FilaRoja';
            }
        };

        me.tbar = Ext.widget('mencion_toolbar');

        me.columns = [
            { dataIndex: 'idmencion', hidden: true, hideable: false},
            { dataIndex: 'idfacultad', hidden: true, hideable: false},
            { dataIndex: 'cant_materias', hidden: true, hideable: false},
            { dataIndex: 'estado', hidden: true, hideable: false},
            { header: perfil.etiquetas.lbHdrDescripcion, dataIndex: 'descripcion', flex: 1},
            { header: perfil.etiquetas.lbHdrFacultad, dataIndex: 'denominacion', flex: 1}
        ];

        me.callParent(arguments);
    }
});