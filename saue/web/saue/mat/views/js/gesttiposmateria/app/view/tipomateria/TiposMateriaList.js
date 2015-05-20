Ext.define('GestTiposMateria.view.tipomateria.TiposMateriaList', {
    extend: 'Ext.grid.Panel',
    alias: 'widget.tiposmaterialist',

    store: 'GestTiposMateria.store.TiposMaterias',

    initComponent: function () {
        var me = this;

        me.selModel = Ext.create('Ext.selection.CheckboxModel', {
            id: 'idSelectionTipoMateriaGrid'
        });

        me.bbar = Ext.create('Ext.toolbar.Paging', {
            displayInfo: true,
            store: 'GestTiposMateria.store.TiposMaterias'
        });

        me.viewConfig = {
            getRowClass: function (record) {
                if (record.get('estado') === false)
                    return 'FilaRoja';
            }
        };

        me.columns = [
            { dataIndex: 'idtipomateria', hidden: true},
            { dataIndex: 'estado', hidden: true },
            { header: perfil.etiquetas.lbHdrDescripcion, dataIndex: 'descripcion_tipo_materia', flex: 1}
        ];

        me.callParent(arguments);
    }
});