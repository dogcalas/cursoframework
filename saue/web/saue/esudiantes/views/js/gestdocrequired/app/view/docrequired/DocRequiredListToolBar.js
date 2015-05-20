Ext.define('GestDocRequired.view.docrequired.DocRequiredListToolBar', {
    extend: 'Ext.toolbar.Toolbar',
    alias: 'widget.docrequiredlisttbar',

    initComponent: function () {

        this.items = [
            {
                id: 'idBtnAddDocRequired',
                text: perfil.etiquetas.lbBtnAdicionar,
                icon: perfil.dirImg + 'adicionar.png',
                iconCls: 'btn',
                action: 'adicionar'
            },
            {
                id: 'idBtnUpdDocRequired',
                text: perfil.etiquetas.lbBtnModificar,
                icon: perfil.dirImg + 'modificar.png',
                iconCls: 'btn',
                action: 'modificar',
                disabled: true
            },
            {
                id: 'idBtnDelDocRequired',
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