Ext.define('GestPeriodos.view.periodos.PeriodosListToolBar', {
    extend: 'Ext.toolbar.Toolbar',
    alias: 'widget.periodolisttbar',

    initComponent: function () {
        //        CARGAR ACCIONES PERMITIDAS
        UCID.portal.cargarAcciones(window.parent.idFuncionalidad);

        this.items = [
            {
                id: 'idBtnAddPeriodo',
                text: perfil.etiquetas.lbBtnAdicionar,
                icon: perfil.dirImg + 'adicionar.png',
                iconCls: 'btn',
                hidden: true,
                action: 'adicionar'
            },
            {
                id: 'idBtnUpdPeriodo',
                text: perfil.etiquetas.lbBtnModificar,
                icon: perfil.dirImg + 'modificar.png',
                iconCls: 'btn',
                hidden: true,
                action: 'modificar',
                disabled: true
            },
            {
                id: 'idBtnDelPeriodo',
                text: perfil.etiquetas.lbBtnEliminar,
                icon: perfil.dirImg + 'eliminar.png',
                iconCls: 'btn',
                hidden: true,
                action: 'eliminar',
                disabled: true
            },
            {
                id: 'idBtnActivarPeriodo',
                text: perfil.etiquetas.lbBtnActivar,
                icon: perfil.dirImg + 'activar.png',
                iconCls: 'btn',
                action: 'activar',
                hidden: true,
                disabled: true
            }
        ];

        this.callParent(arguments);
    }
});
