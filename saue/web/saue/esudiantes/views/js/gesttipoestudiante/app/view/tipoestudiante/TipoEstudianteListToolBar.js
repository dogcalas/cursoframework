Ext.define('GestTipoEstudiante.view.tipoestudiante.TipoEstudianteListToolBar', {
    extend: 'Ext.toolbar.Toolbar',
    alias: 'widget.tipoestudiantelisttbar',

    initComponent: function () {

        this.items = [
            {
                id: 'idBtnAddTipoEstudiante',
                text: perfil.etiquetas.lbBtnAdicionar,
                icon: perfil.dirImg + 'adicionar.png',
                iconCls: 'btn',
                action: 'adicionar'
            },
            {
                id: 'idBtnUpdTipoEstudiante',
                text: perfil.etiquetas.lbBtnModificar,
                icon: perfil.dirImg + 'modificar.png',
                iconCls: 'btn',
                action: 'modificar',
                disabled: true
            },
            {
                id: 'idBtnDelTipoEstudiante',
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