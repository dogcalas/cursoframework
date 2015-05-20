Ext.define('GestTiposMateria.view.tipomateria.TiposMateriaListToolBar', {
    extend: 'Ext.toolbar.Toolbar',
    alias: 'widget.tiposmaterialisttbar',

    initComponent: function () {
        UCID.portal.cargarAcciones(window.parent.idFuncionalidad);

        this.items = [
            {
                id: 'idBtnAddTipoMateria',
                text: perfil.etiquetas.lbBtnAdicionar,
                icon: perfil.dirImg + 'adicionar.png',
                iconCls: 'btn',
                hidden: true,
                action: 'adicionar'
            },
            {
                id: 'idBtnUpdTipoMateria',
                text: perfil.etiquetas.lbBtnModificar,
                icon: perfil.dirImg + 'modificar.png',
                iconCls: 'btn',
                hidden: true,
                action: 'modificar',
                disabled: true
            },
            {
                id: 'idBtnDelTipoMateria',
                text: perfil.etiquetas.lbBtnEliminar,
                icon: perfil.dirImg + 'eliminar.png',
                iconCls: 'btn',
                hidden: true,
                action: 'eliminar',
                disabled: true
            },
            '->',
            {
                xtype: 'searchfield',
                store: 'GestTiposMateria.store.TiposMaterias',//Ext.data.StoreManager.lookup('idStorePreRequisito'),
                emptyText: 'Filtrar por descripción',
                width: 400,
                padding: '0 0 0 5',
                labelWidth: 40,
                filterPropertysNames: ['descripcion']
            }
        ];

        this.callParent(arguments);
    }
});