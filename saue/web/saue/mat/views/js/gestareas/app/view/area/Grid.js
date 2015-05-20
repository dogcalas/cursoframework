Ext.define('GestAreas.view.area.Grid', {
    extend: 'Ext.grid.Panel',
    alias: 'widget.area_grid',

    store: Ext.create('GestAreas.store.Areas'),

    initComponent: function () {
        var me = this;

        me.selModel = Ext.create('Ext.selection.CheckboxModel', {
            itemId: 'idSelectionAreaGrid'
        });

        me.tbar = Ext.create('GestAreas.view.area.ToolBar');

        me.bbar = Ext.create('GestAreas.view.area.PagingToolBar');

        me.viewConfig = {
            getRowClass: function (record) {
                if (record.get('estado') === false)
                    return 'FilaRoja';
            }
        };

        me.columns = [
            {
                dataIndex: 'idarea',
                hidden: true,
                hideable: false
            },
            {
                header: perfil.etiquetas.lbHdrDescripcion,
                dataIndex: 'descripcion_area',
                flex: 1
            },
            {
                header: perfil.etiquetas.lbHdrAreaGen,
                dataIndex: 'descripcion_area_general',
                width: 150
            },
            {
                dataIndex: 'estado',
                hidden: true,
                text: perfil.etiquetas.lbHdrEstado,
                xtype: 'booleancolumn'
            }
        ];

        me.callParent(arguments);
    }
});