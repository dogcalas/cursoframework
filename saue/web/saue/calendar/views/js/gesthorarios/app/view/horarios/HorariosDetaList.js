Ext.define('GestHorarios.view.horarios.HorariosDetaList', {
    extend: 'Ext.grid.Panel',
    alias: 'widget.horariosdetalist',
    height: 320,
    store: 'GestHorarios.store.HorariosDeta',

    initComponent: function () {
        var me = this;

        me.selModel = Ext.create('Ext.selection.RowModel', {
            id: 'idSelectionHorariosDetaListGrid',
            mode: 'MULTI'
        });

        /*me.viewConfig = {
            getRowClass: function (record) {
                if (record.get('estado') === false)
                    return 'FilaRoja';
            }
        };*/

        me.columns = [
            {dataIndex: 'idhorario_detallado', hidden: true, hideable: false},
            {dataIndex: 'idhorario', hidden: true, hideable: false},
            {dataIndex: 'iddias', hidden: true, hideable: false},
            {header: 'Día semana', dataIndex: 'descripcion', flex: 1},
            {header: 'Hora inicio', dataIndex: 'hora_inicio', flex: 1},
            {header: 'Hora fin', dataIndex: 'hora_fin', flex: 1},
            {dataIndex: 'estado', hidden: true}
        ];

        me.tbar = Ext.define('GestHorarios.view.horarios.HorariosDetaListToolBar', {
            extend: 'Ext.toolbar.Toolbar',
            alias: 'widget.horariosdetalisttbar',

            initComponent: function () {

                this.items = [
                    {
                        xtype: 'timefield',
                        fieldLabel: 'Hora inicio',
                        name: 'hora_inicio',
                        id: 'hora_inicio',
                        minValue: '08:00',
                        maxValue: '23:00',
                        format: 'H:i',
                        width: 90,
                        allowBlank: false,
                        listeners:{
                            'select':function(field,value){
                                Ext.getCmp('hora_fin').setMinValue(value);
                            }
                        }
                    },
                    {
                        xtype: 'timefield',
                        fieldLabel: 'Hora fin',
                        name: 'hora_fin',
                        id: 'hora_fin',
                        minValue: '08:00',
                        maxValue: '23:00',
                        format: 'H:i',
                        width: 90,
                        padding: '0 0 0 10',
                        allowBlank: false
                    },
                    {
                        xtype: 'horarios_combo_dias'
                    },
                    '->',
                    {
                        id: 'idBtnAddHorarioDeta',
                        //text: perfil.etiquetas.lbBtnAdicionar,
                        icon: perfil.dirImg + 'adicionar.png',
                        iconCls: 'btn',
                        padding: '10 0 0 0',
                        action: 'adicionarHD'
                    },
                    {
                        id: 'idBtnDelHorarioDeta',
                        //text: perfil.etiquetas.lbBtnEliminar,
                        icon: perfil.dirImg + 'eliminar.png',
                        iconCls: 'btn',
                        padding: '10 0 0 0',
                        action: 'eliminarHD',
                        disabled: true
                    }
                ]
                ;

                this.callParent(arguments);
            }
        });

        me.callParent(arguments);
    }
})
;