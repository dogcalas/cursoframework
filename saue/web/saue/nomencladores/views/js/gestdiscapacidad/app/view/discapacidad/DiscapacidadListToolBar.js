Ext.define('GestDiscapacidad.view.discapacidad.DiscapacidadListToolBar', {
    extend: 'Ext.toolbar.Toolbar',
    alias: 'widget.discapacidadlisttbar',

    initComponent: function () {
        UCID.portal.cargarAcciones(window.parent.idFuncionalidad);

        this.items = [
            {
                id: 'idBtnAddDiscapacidad',
                text: perfil.etiquetas.lbBtnAdicionar,
                icon: perfil.dirImg + 'adicionar.png',
                iconCls: 'btn',
                hidden: true,
                action: 'adicionar'
            },
            {
                id: 'idBtnUpdDiscapacidad',
                text: perfil.etiquetas.lbBtnModificar,
                icon: perfil.dirImg + 'modificar.png',
                iconCls: 'btn',
                hidden: true,
                action: 'modificar',
                disabled: true
            },
            {
                id: 'idBtnDelDiscapacidad',
                text: perfil.etiquetas.lbBtnEliminar,
                icon: perfil.dirImg + 'eliminar.png',
                iconCls: 'btn',
                hidden: true,
                action: 'eliminar',
                disabled: true
            }
        ];

        this.callParent(arguments);
    }
});