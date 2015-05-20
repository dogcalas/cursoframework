Ext.define('GestCoRequisitos.view.corequisito.CoRequisitoListToolBar', {
    extend: 'Ext.toolbar.Toolbar',
    alias: 'widget.corequisitolisttbar',

    disabled: true,

    initComponent: function () {
        UCID.portal.cargarAcciones(window.parent.idFuncionalidad);
        this.items = [
            {
                id: 'idBtnAddCoRequisito',
                text: perfil.etiquetas.lbBtnAdicionar,
                icon: perfil.dirImg + 'adicionar.png',
                iconCls: 'btn',
                hidden: true,
                action: 'adicionar'
            },
            {
                id: 'idBtnDelCoRequisito',
                disabled: true,
                hidden: true,
                text: perfil.etiquetas.lbBtnEliminar,
                icon: perfil.dirImg + 'eliminar.png',
                iconCls: 'btn',
                action: 'eliminar'
            },'-',
            {
                xtype: 'searchfield',
                store: 'GestCoRequisitos.store.CoRequisitos',//Ext.data.StoreManager.lookup('idStoreCoRequisito'),
                emptyText: 'Filtrar por código o descripción',
                width: 250,
                padding: '0 0 0 5',
                labelWidth: 40,
                filterPropertysNames: ['codmateria', 'descripcion']
            }
        ];

        this.callParent(arguments);
    }
});