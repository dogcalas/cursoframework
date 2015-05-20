Ext.define('GestTipoPeriodo.view.tipoperiodo.TipoPeriodoListToolBar', {
    extend: 'Ext.toolbar.Toolbar',
    alias: 'widget.tipoperiodolisttbar',

    initComponent: function () {
        //        CARGAR ACCIONES PERMITIDAS
        UCID.portal.cargarAcciones(window.parent.idFuncionalidad);

        this.items = [
            {
                id: 'idBtnAddTipoPeriodo',
                text: perfil.etiquetas.lbBtnAdicionar,
                icon: perfil.dirImg + 'adicionar.png',
                iconCls: 'btn',
                hidden: true,
                action: 'adicionar'
            },
            {
                id: 'idBtnUpdTipoPeriodo',
                text: perfil.etiquetas.lbBtnModificar,
                icon: perfil.dirImg + 'modificar.png',
                iconCls: 'btn',
                hidden: true,
                action: 'modificar',
                disabled: true
            },
            {
                id: 'idBtnDelTipoPeriodo',
                text: perfil.etiquetas.lbBtnEliminar,
                icon: perfil.dirImg + 'eliminar.png',
                iconCls: 'btn',
                action: 'eliminar',
                hidden: true,
                disabled: true
            }
        ];

        this.callParent(arguments);
    }
});