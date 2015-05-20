Ext.define('GestPeriodos.view.periodos.PeriodosList', {
    extend: 'Ext.grid.Panel',
    alias: 'widget.periodolist',

    store: 'GestPeriodos.store.Periodos',

    initComponent: function () {
        var me = this;

        me.selModel = Ext.create('Ext.selection.RowModel', {
            id: 'idSelectionPeriodosGrid',
            mode: 'MULTI'
        });

        me.bbar = Ext.create('Ext.toolbar.Paging', {
            displayInfo: true,
            store: 'GestPeriodos.store.Periodos'
        });

        me.viewConfig = {
            getRowClass: function(record){
                if (record.get('estado') === false)
                    return 'FilaRoja';
            }
        };

        me.columns = [
            { dataIndex: 'idperiododocente', hidden: true, hideable: false},
            { dataIndex: 'idtipo_periododocente', hidden: true, hideable: false},
            { dataIndex: 'idcampus', hidden: true, hideable: false},
            { header:'Abrev.', dataIndex: 'abrev', hidden: true, hideable: false},
            {header: 'Anno', dataIndex: 'anno', hidden: true, hideable: false},
            { header:'Estado', dataIndex: 'estado',  hidden: true },
            { header: 'Código período', dataIndex: 'codperiodo', flex: 1/2},
            { header: 'Descripción', dataIndex: 'descripcion', flex: 2},
            { header: 'Tipo período', dataIndex: 'tipoperiodo', width: 100},
            { header: 'Fecha inicio', dataIndex: 'fecha_ini', flex: 1/2},
            { header: 'Fecha fin', dataIndex: 'fecha_fin', flex: 1/2},
            { header: 'Duración (sem)', dataIndex: 'duracion', width: 100, hidden: false},
            { header: 'Cant. eval.', dataIndex: 'cant_notas', width: 100, hidden: false}
        ];

        me.callParent(arguments);
    }
});
