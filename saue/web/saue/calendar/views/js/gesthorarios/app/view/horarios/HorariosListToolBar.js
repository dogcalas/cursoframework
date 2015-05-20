Ext.define('GestHorarios.view.horarios.HorariosListToolBar', {
    extend: 'Ext.toolbar.Toolbar',
    alias: 'widget.horarioslisttbar',

    initComponent: function () {
        UCID.portal.cargarAcciones(window.parent.idFuncionalidad);

        this.items = [
            {
                id: 'idBtnAddHorario',
                text: perfil.etiquetas.lbBtnAdicionar,
                icon: perfil.dirImg + 'adicionar.png',
                hidde: true,
                iconCls: 'btn',
                action: 'adicionar'
            },
            {
                id: 'idBtnUpdHorario',
                text: perfil.etiquetas.lbBtnModificar,
                icon: perfil.dirImg + 'modificar.png',
                iconCls: 'btn',
                hidde: true,
                action: 'modificar',
                disabled: true
            },
            {
                id: 'idBtnDelHorario',
                text: perfil.etiquetas.lbBtnEliminar,
                icon: perfil.dirImg + 'eliminar.png',
                iconCls: 'btn',
                hidde: true,
                action: 'eliminar',
                disabled: true
            },
            {
                id: 'idBtnFrecHorario',
                text: 'Frecuencias',
                icon: perfil.dirImg + 'flujo.png',
                iconCls: 'btn',
                hidde: true,
                action: 'frecuencia',
                disabled: true
            }
        ];

        this.callParent(arguments);
    }
});