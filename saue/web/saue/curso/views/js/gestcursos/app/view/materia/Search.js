Ext.define('GestCursos.view.materia.Search', {
    extend: 'Ext.window.Window',
    alias: 'widget.curso_materia_search',

    layout: 'fit',
    modal: true,
    resizable: false,
    autoShow: true,
    width: 600,
    height: 400,

    initComponent: function () {
        this.items = Ext.create('GestCursos.view.materia.Grid');

        this.buttons = [
            {
                icon: perfil.dirImg + 'cancelar.png',
                iconCls: 'btn',
                text: perfil.etiquetas.lbBtnCancelar,
                action: 'cancelar',
                scope: this,
                handler: this.close
            },
            {
                icon: perfil.dirImg + 'aceptar.png',
                iconCls: 'btn',
                text: perfil.etiquetas.lbBtnAceptar,
                action: 'aceptar'
            }
        ];

        this.callParent(arguments);
    }
})