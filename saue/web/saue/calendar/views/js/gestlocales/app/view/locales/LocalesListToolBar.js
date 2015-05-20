Ext.define('GestLocales.view.locales.LocalesListToolBar', {
    extend: 'Ext.toolbar.Toolbar',
    alias: 'widget.locallisttbar',

    initComponent: function () {

        this.items = [
            {
                id: 'idBtnAddLocal',
                text: perfil.etiquetas.lbBtnAdicionar,
                icon: perfil.dirImg + 'adicionar.png',
                iconCls: 'btn',
                action: 'adicionar'
            },
            {
                id: 'idBtnUpdLocal',
                text: perfil.etiquetas.lbBtnModificar,
                icon: perfil.dirImg + 'modificar.png',
                iconCls: 'btn',
                action: 'modificar',
                disabled: true
            },
            {
                id: 'idBtnDelLocal',
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
