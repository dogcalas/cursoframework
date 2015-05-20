Ext.define('GestLocales.view.locales.LocalListToolBar', {
    extend: 'Ext.toolbar.Toolbar',
    alias: 'widget.locallisttbar',

    initComponent: function () {
        UCID.portal.cargarAcciones(window.parent.idFuncionalidad);
        var me = this;

        me.items = [
            {
                id: 'idBtnAddLocal',
                text: perfil.etiquetas.lbBtnAdicionar,
                icon: perfil.dirImg + 'adicionar.png',
                iconCls: 'btn',
                hidden: true,
                action: 'adicionar'
            },
            {
                id: 'idBtnUpdLocal',
                disabled: true,
                hidden: true,
                text: perfil.etiquetas.lbBtnModificar,
                icon: perfil.dirImg + 'modificar.png',
                iconCls: 'btn',
                action: 'modificar'
            },
            {
                id: 'idBtnDelLocal',
                disabled: true,
                hidden: true,
                text: perfil.etiquetas.lbBtnEliminar,
                icon: perfil.dirImg + 'eliminar.png',
                iconCls: 'btn',
                action: 'eliminar'
            },
            '->',
            {
                xtype: 'searchfield',
                store: 'GestLocales.store.Locales',
                width: 400,
                fieldLabel: '<b>' + perfil.etiquetas.lbBtnBuscar + '</b>',
                labelWidth: 40,
                filterPropertysNames: ['descripcion']
            }
        ];

        me.callParent(arguments);
    }
});