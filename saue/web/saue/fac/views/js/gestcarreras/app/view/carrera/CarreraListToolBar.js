Ext.define('GestCarreras.view.carrera.CarreraListToolBar', {
    extend: 'Ext.toolbar.Toolbar',
    alias: 'widget.carreralisttbar',

    initComponent: function () {
        UCID.portal.cargarAcciones(window.parent.idFuncionalidad);
        var me = this;

        me.items = [
            {
                id: 'idBtnAddCarrera',
                text: perfil.etiquetas.lbBtnAdicionar,
                icon: perfil.dirImg + 'adicionar.png',
                iconCls: 'btn',
                hidden: true,
                action: 'adicionar'
            },
            {
                id: 'idBtnUpdCarrera',
                text: perfil.etiquetas.lbBtnModificar,
                icon: perfil.dirImg + 'modificar.png',
                disabled: true,
                hidden: true,
                iconCls: 'btn',
                action: 'modificar'
            },
            {
                id: 'idBtnDelCarrera',
                text: perfil.etiquetas.lbBtnEliminar,
                icon: perfil.dirImg + 'eliminar.png',
                disabled: true,
                hidden: true,
                iconCls: 'btn',
                action: 'eliminar'
            },'->',
            {
                xtype: 'searchcombofield',
                store: Ext.data.StoreManager.lookup('idStoreFacultades'),
                name: 'idfacultad',
                valueField: 'idfacultad',
                displayField: 'denominacion',
                storeToFilter: Ext.data.StoreManager.lookup('idStoreCarreras'),
                emptyText: 'Filtrar por facultad',
                width: 250,
                padding: '0 0 0 5',
                labelWidth: 40
            },
            {
                xtype: 'searchfield',
                store: Ext.data.StoreManager.lookup('idStoreCarreras'),
                emptyText: 'Filtrar por carrera',
                width: 250,
                padding: '0 0 0 5',
                labelWidth: 40,
                filterPropertysNames: ['descripcion']
            }
        ];

        me.callParent(arguments);
    }
});