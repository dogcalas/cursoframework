Ext.define('GestPeriodos.view.periodos.Activ_ActivasList', {
    extend: 'Ext.grid.Panel',
    alias: 'widget.activ_activaslist',
    name: 'grid_activas',
    store: 'GestPeriodos.store.Activ_Activas',
    disabled: true,
    plugins: [
        Ext.create('Ext.grid.plugin.CellEditing', {
            clicksToEdit: 1,
            pluginId: 'cellplugin'
        })
    ],
    selType: 'cellmodel',
    initComponent: function () {
        var me = this;

        me.selModel = Ext.create('Ext.selection.CheckboxModel');

        me.features = [{
            ftype: 'grouping',
            hideGroupedHeader: true
        }];

        me.bbar = Ext.create('Ext.toolbar.Paging', {
            displayInfo: true,
            store: 'GestPeriodos.store.Activ_Activas'
        });

        me.columns = [
            {dataIndex: 'idperiodoactivo', hidden: true, hideable: false},
            {dataIndex: 'idperiododocente', hidden: true, hideable: false},
            {dataIndex: 'idrol', hidden: true, hideable: false},
            {dataIndex: 'idfuncionalidad', hidden: true, hideable: false},
            {dataIndex: 'idaccion', hidden: true, hideable: false},
            {dataIndex: 'idsistema', hidden: true, hideable: false},
            {header: 'Rol', dataIndex: 'rol', flex: 3 / 2},
            {header: 'Funcionalidad', dataIndex: 'funcionalidad', hidden: true, flex: 3 / 2},
            {header: 'Acción', dataIndex: 'accion', flex: 3 / 2},
            {
                header: 'Fecha inicio', dataIndex: 'fecha_ini', flex: 1, editor: {
                xtype: 'datefield',
                format: 'j/n/Y H:i:s'
            },
                renderer: Ext.util.Format.dateRenderer('d/m/Y')
            },
            {
                header: 'Fecha fin', dataIndex: 'fecha_fin', flex: 1, editor: {
                xtype: 'datefield',
                format: 'j/n/Y H:i:s'
            },
                renderer: Ext.util.Format.dateRenderer('d/m/Y')
            }
        ];

        me.tbar = [
            {
                xtype: 'button',
                id: 'idBtnAddActivActiva',
                text: perfil.etiquetas.lbBtnAdicionar,
                icon: perfil.dirImg + 'adicionar.png',
                iconCls: 'btn',
                //hidden: true,
                action: 'adicionar'
            },
            {
                xtype: 'button',
                id: 'idBtnDelActivActiva',
                text: perfil.etiquetas.lbBtnEliminar,
                icon: perfil.dirImg + 'eliminar.png',
                iconCls: 'btn',
                //hidden: true,
                action: 'eliminar',
                disabled: true
            }
        ];
        me.callParent(arguments);
    }
});
