Ext.define('GestConv.view.MateriaList', {
    extend: 'Ext.grid.Panel',

    alias: 'widget.materialist',
    id: 'materialist',
    store: 'GestConv.store.Materias',
    selType: 'cellmodel',

    plugins: [
        Ext.create('Ext.grid.plugin.CellEditing', {
            clicksToEdit: 1,
            pluginId: 'cellplugin'
        })
    ],
    columns: [
        {dataIndex: 'idalumno', hidden: true, hideable: false},
        {dataIndex: 'idpd', hidden: true},
        {dataIndex: 'idmateriaconva', hidden: true},
        {dataIndex: 'iduniversidad', hidden: true},
        {dataIndex: 'idmateria', hidden: true},
        {header: 'Código', dataIndex: 'codigo', width: 65},
        {header: 'Materia', dataIndex: 'descripcion', flex: 1},
        {header: 'Materia convalidación', dataIndex: 'materiaprincipal', flex: 1},
        {header: 'Nota', dataIndex: 'nota', width: 50, editor: 'numberfield'}
    ],
    columnLines: true,

    selType: 'checkboxmodel',
    viewConfig: {
        getRowClass: function (record, rowIndex, rowParams, store) {
            if (record.data.idtipoaprobado == 1000013)
                return 'FilaVerde';
        }
    },
    initComponent: function () {
        var me = this;

        // me.selModel.on("selectionchange", me.manejarBotones, me);
        me.bbar = [Ext.create('Ext.toolbar.Paging', {
            displayInfo: true,
            id: "paginator",
            disabled: true,
            store: me.store
        }),
            "->",
            {
                xtype: 'button',
                text: 'Homologar',
                id:'convalidarMaterias',
                action: 'convalidar',
                icon: perfil.dirImg + 'avanzada.png',
                iconCls: 'btn'
            }
        ];
        me.callParent(arguments);
    }
});