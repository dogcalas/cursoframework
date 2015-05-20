Ext.define('GestTipoEstudiante.view.tipoestudiante.TipoEstudianteList', {
    extend: 'Ext.grid.Panel',
    alias: 'widget.tipoestudiantelist',

    store: 'GestTipoEstudiante.store.TipoEstudiante',

    initComponent: function () {
        var me = this;

        me.selModel = Ext.create('Ext.selection.RowModel', {
            id: 'idSelectionTipoEstudianteGrid',
            mode: 'MULTI'
        });

        me.bbar = Ext.create('Ext.toolbar.Paging', {
            displayInfo: true,
            store: 'GestTipoEstudiante.store.TipoEstudiante'
        });

        me.viewConfig = {
            getRowClass: function(record){
                if (record.get('estado') === false)
                    return 'FilaRoja';
            }
        };

        me.columns = [
            { dataIndex: 'idtipoalumno', hidden: true},
            { dataIndex: 'estado',  hidden: true },
            { header: perfil.etiquetas.lbHdrDescripcion, dataIndex: 'descripcion', flex: 1}
        ];

        me.callParent(arguments);
    }
});