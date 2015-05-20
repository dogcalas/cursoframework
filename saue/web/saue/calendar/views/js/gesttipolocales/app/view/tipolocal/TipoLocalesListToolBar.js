Ext.define('GestTipoLocales.view.tipolocal.TipoLocalesListToolBar', {
    extend: 'Ext.toolbar.Toolbar',
    alias: 'widget.tipolocallisttbar',

    initComponent: function () {
        UCID.portal.cargarAcciones(window.parent.idFuncionalidad);
        this.items = [
            {
                id: 'idBtnAddTipoLocal',
                hidden: true,
                text: perfil.etiquetas.lbBtnAdicionar,
                icon: perfil.dirImg + 'adicionar.png',
                iconCls: 'btn',
                action: 'adicionar'
            },
            {
                id: 'idBtnUpdTipoLocal',
                hidden: true,
                text: perfil.etiquetas.lbBtnModificar,
                icon: perfil.dirImg + 'modificar.png',
                iconCls: 'btn',
                action: 'modificar',
                disabled: true
            },
            {
                id: 'idBtnDelTipoLocal',
                hidden: true,
                text: perfil.etiquetas.lbBtnEliminar,
                icon: perfil.dirImg + 'eliminar.png',
                iconCls: 'btn',
                action: 'eliminar',
                disabled: true
            }
        ];

        this.callParent(arguments);
    }
});