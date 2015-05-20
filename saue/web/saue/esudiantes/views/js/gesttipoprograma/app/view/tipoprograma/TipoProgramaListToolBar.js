Ext.define('GestTipoPrograma.view.tipoprograma.TipoProgramaListToolBar', {
    extend: 'Ext.toolbar.Toolbar',
    alias: 'widget.tipoprogramalisttbar',

    initComponent: function () {

        this.items = [
            {
                id: 'idBtnAddTipoPrograma',
                text: perfil.etiquetas.lbBtnAdicionar,
                icon: perfil.dirImg + 'adicionar.png',
                iconCls: 'btn',
                action: 'adicionar'
            },
            {
                id: 'idBtnUpdTipoPrograma',
                text: perfil.etiquetas.lbBtnModificar,
                icon: perfil.dirImg + 'modificar.png',
                iconCls: 'btn',
                action: 'modificar',
                disabled: true
            },
            {
                id: 'idBtnDelTipoPrograma',
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