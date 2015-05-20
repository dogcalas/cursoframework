Ext.define('GestMaterias.view.credito.Grid', {
    extend: 'Ext.grid.Panel',
    alias: 'widget.creditos_grid',

    store: 'GestMaterias.store.Creditos',

    initComponent: function () {
        var me = this;

        me.sm = Ext.create('Ext.selection.RowModel', {
            id: 'idSelectionMateriaPensumGrid',
            mode: 'SINGLE'
        });

        me.plugins = [Ext.create('Ext.grid.plugin.CellEditing', {
            clicksToEdit: 1
        })];

            me.viewConfig = {
                getRowClass: function (record) {
                    if (record.get('estado') === false)
                        return 'FilaRoja';
                }
            };

        me.columns = [
            { dataIndex: 'idmateriacredito', hidden: true, hideable: false },
            { dataIndex: 'idmateria', hidden: true, hideable: false },
            { dataIndex: 'idpensum', hidden: true, hideable: false },
            { header: 'Pensum', dataIndex: 'descripcion', flex: 1},//Etiqueta
            {
                header: 'Créditos',
                dataIndex: 'creditos',
                field: {
                    xtype: 'numberfield',
                    allowBlank: false,
                    blankText: 'Campo requerido',
                    minValue: 1,
                    allowDecimals: false
                }
            }//Etiqueta
        ];

        me.callParent(arguments);
    }
});
