Ext.define('GestConv.view.ToolBarConva', {
    extend: 'Ext.toolbar.Toolbar',
    alias: 'widget.toolbar_conva',
    id: 'toolcon',
    items : [
            {
            text:"Adicionar",
            id:'addMatUniversidad',
            icon: perfil.dirImg + 'adicionar.png',
            iconCls: 'btn',
            disabled: true
        },
        {
            text:"Eliminar",
            id:'delMatUniversidad',
            icon: perfil.dirImg + 'eliminar.png',
            iconCls: 'btn',
            disabled: true
        }, '->',
        {
            text:"Principal",
            id:'prinMatUniversidad',
            icon: perfil.dirImg + 'validaryaceptar.png',
            iconCls: 'btn',
            disabled: true
        }
        ],
    initComponent: function () {

        this.callParent(arguments);
    }
});