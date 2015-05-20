Ext.define('App.view.tdisc.TDiscListToolBar', {
    extend: 'Ext.toolbar.Toolbar',
    alias: 'widget.tdisclisttbar',

    initComponent: function () {
        UCID.portal.cargarAcciones(window.parent.idFuncionalidad);

        this.items = [
            {
                id: 'idBtnAddTDisc',
                text: perfil.etiquetas.lbBtnAdicionar,
                icon: perfil.dirImg + 'adicionar.png',
                iconCls: 'btn',
                hidden: true,
                action: 'adicionar'
            },
            {
                id: 'idBtnUpdTDisc',
                text: perfil.etiquetas.lbBtnModificar,
                icon: perfil.dirImg + 'modificar.png',
                iconCls: 'btn',
                hidden: true,
                action: 'modificar',
                disabled: true
            },
            {
                id: 'idBtnDelTDisc',
                text: perfil.etiquetas.lbBtnEliminar,
                icon: perfil.dirImg + 'eliminar.png',
                iconCls: 'btn',
                hidden: true,
                action: 'eliminar',
                disabled: true
            }, '->',
            {
                xtype: 'searchfield',
                store: 'App.store.stTDisc',
                fieldLabel: '<b>' + perfil.etiquetas.lbBtnBuscar + '</b>',
                width: 400,
                padding: '0 0 0 5',
                labelWidth: 40,
                filterPropertysNames: ['descripcion']
            }
        ];

        this.callParent(arguments);
    }
});