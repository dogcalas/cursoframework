Ext.define('GestPreRequisitos.view.prerequisito.PreRequisitoListToolBar', {
    extend: 'Ext.toolbar.Toolbar',
    alias: 'widget.prerequisitolisttbar',

    disabled: true,

    initComponent: function () {
        UCID.portal.cargarAcciones(window.parent.idFuncionalidad);
        this.items = [
            {
                id: 'idBtnAddPreRequisito',
                text: perfil.etiquetas.lbBtnAdicionar,
                icon: perfil.dirImg + 'adicionar.png',
                iconCls: 'btn',
                hidden: true,
                action: 'adicionar'
            },
            {
                id: 'idBtnDelPreRequisito',
                disabled: true,
                hidden: true,
                text: perfil.etiquetas.lbBtnEliminar,
                icon: perfil.dirImg + 'eliminar.png',
                iconCls: 'btn',
                action: 'eliminar'
            },'-',
            {
                xtype: 'searchfield',
                store: 'GestPreRequisitos.store.PreRequisitos',//Ext.data.StoreManager.lookup('idStorePreRequisito'),
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