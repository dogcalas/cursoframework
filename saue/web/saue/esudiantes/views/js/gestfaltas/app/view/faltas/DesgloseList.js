Ext.define('GestFaltas.view.faltas.DesgloseList', {
    extend: 'Ext.grid.Panel',
    alias: 'widget.desgloselist',
    id: 'desgloselist',

    store: 'GestFaltas.store.FaltasDias',

    height:300,
    hideCollapseTool:true,
    collapsed: true,
    collapsible: true,
    selType: 'cellmodel',

    plugins: [
        Ext.create('Ext.grid.plugin.CellEditing', {
            clicksToEdit: 1,
            pluginId: 'cellplugin'
        })
    ],

    columns: [
        { dataIndex: 'id_falta_log', hidden: true, hideable: false},
        { dataIndex: 'idalumno', hidden: true, hideable: false},
        { dataIndex: 'idpd', hidden: true, hideable: false},
        { dataIndex: 'idmateria', hidden: true, hideable: false},
        { header: 'Facultad', dataIndex: 'facultad', flex: 1},
        { header: 'Semana', dataIndex: 'semanas', width: 50},
        { header: 'Día', dataIndex: 'diasemana', width: 50},
        { header: 'Fecha', dataIndex: 'fecha'},
        { header: 'Usuario', dataIndex: 'usuario', flex: 1},
        { header: 'Cód. Materia', dataIndex: 'codmateria'},
        { header: 'Materia', dataIndex: 'materia', flex: 2},
        { header: 'Falta inicial', dataIndex: 'falta_ini', width: 70},
        { header: 'Falta final', dataIndex: 'val_falta', width: 70},
        { header: 'Observaciones', dataIndex: 'observaciones', flex: 3}
    ],
    initComponent: function () {
        var me = this;

        me.bbar = Ext.create('Ext.toolbar.Paging', {
            displayInfo: true,
            store: me.store
        });

        me.callParent(arguments);
    }

});