Ext.define('GestHorarios.view.horarios.HorariosList', {
    extend: 'Ext.grid.Panel',
    alias: 'widget.horarioslist',

    store: 'GestHorarios.store.Horarios',

    initComponent: function () {
        var me = this;

        me.selModel = Ext.create('Ext.selection.RowModel', {
            id: 'idSelectionHorariosGrid',
            mode: 'MULTI'
        });

        me.bbar = Ext.create('Ext.toolbar.Paging', {
            displayInfo: true,
            store: 'GestHorarios.store.Horarios'
        });

       /* me.features = [{
            ftype:'grouping',
            hideGroupedHeader: true
        }];*/

        me.viewConfig = {
            getRowClass: function(record){
                if (record.get('estado') === false)
                    return 'FilaRoja';
            }
        };

        me.columns = [
            { header: 'Código horario', dataIndex: 'idhorario', width: 100, hidden: true},
            { dataIndex: 'estado',  hidden: true },
            { header: perfil.etiquetas.lbHdrDescripcion, dataIndex: 'descripcion', flex:.7},
            { header:'Frecuencia', dataIndex: 'frecuencia', flex: 1}
        ];

        me.callParent(arguments);
    }
});