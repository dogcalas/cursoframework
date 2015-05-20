Ext.define('CredxArea.view.credxarea.Grid', {
    extend: 'Ext.grid.Panel',
    alias: 'widget.credxarea_grid',

    store: 'CredxArea.store.CredsxArea',

    initComponent: function () {
        var me = this;

        me.selModel = Ext.create('Ext.selection.CheckboxModel');

        me.bbar = Ext.create('Ext.toolbar.Paging', {
            displayInfo: true,
            store: me.store
        });

        me.tbar = Ext.create('CredxArea.view.credxarea.Toolbar');

        me.viewConfig = {
            getRowClass: function (record) {
                if (record.get('estado') === false)
                    return 'FilaRoja';
            }
        };

        me.columns = [
            { dataIndex: 'idareacredito', hidden: true, hideable: false},
            { dataIndex: 'idenfasis', hidden: true, hideable: false},
            { dataIndex: 'idpensum', hidden: true, hideable: false},
            {dataIndex: 'idcarrera', hidden: true, hideable: false},
            {dataIndex: 'idfacultad', hidden: true, hideable: false},
            { dataIndex: 'idareageneral', hidden: true, hideable: false},
            { dataIndex: 'idarea', hidden: true, hideable: false},

            { text: perfil.etiquetas.lbHdrDescripcion, dataIndex: 'descripcion', flex: 1},
            { text: perfil.etiquetas.lbHdrCreditos, dataIndex: 'creditos'},
            { text: perfil.etiquetas.lbHdrEstado, dataIndex: 'estado', hidden: true }
        ];

        me.callParent(arguments);
    }
});