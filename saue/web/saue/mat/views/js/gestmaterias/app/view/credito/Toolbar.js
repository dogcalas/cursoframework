Ext.define('GestMaterias.view.credito.Toolbar', {
    extend: 'Ext.toolbar.Toolbar',
    alias: 'widget.credito_toolbar',

    initComponent: function () {

        this.items = [
            {
                text: perfil.etiquetas.lbBtnAdicionar,
                icon: perfil.dirImg + 'adicionar.png',
                iconCls: 'btn',
                action: 'adicionar',
                id:'btnAddMCredito'
            },
            {
                text: perfil.etiquetas.lbBtnModificar,
                icon: perfil.dirImg + 'modificar.png',
                iconCls: 'btn',
                action: 'modificar',
                id:'btnUpdMCredito'
                disabled: true
            },
            {
                text: perfil.etiquetas.lbBtnEliminar,
                icon: perfil.dirImg + 'eliminar.png',
                iconCls: 'btn',
                action: 'eliminar',
                id:'btnDelMCredito'
                disabled: true
            }
        ];

        this.callParent(arguments);
    }
});
