Ext.define('GestPeriodos.view.accion.AccionList', {
    extend: 'Ext.grid.Panel',
    alias: 'widget.accionlist',
    id: 'accionlist',
    title: 'Acciones',
    disabled: true,
    /*height: 395,
    width: 300,*/
    frame: true,
    region: 'west',
    iconCls: 'icon-grid',
    store: 'GestPeriodos.store.Accion',

    initComponent: function () {
        var me = this;

        me.selModel = Ext.create('Ext.selection.CheckboxModel');

        me.columns = [
            { dataIndex: 'm__idaccion', hidden: true, hideable: true},
            { dataIndex: 'm__idfuncionalidad', hidden: true, hideable: true},
            {dataIndex: 'idrol', hidden: true, hideable: true},
            //{ xtype : 'checkcolumn', dataIndex: 'checked', width: 30},
            { header: 'Descripción', dataIndex: 'm__denominacion', flex: 1}
        ];

        me.viewConfig = {
            getRowClass: function (record, rowIndex, rowParams, store) {
                if (record.data.checked == true)
                    return 'FilaVerde';
            }
        };

        me.callParent(arguments);
    }
});

