Ext.define('GestCoRequisitos.view.corequisito.CoRequisitoEdit', {
    extend: 'Ext.window.Window',
    alias: 'widget.corequisitoedit',

    layout: 'fit',
    modal: true,
    resizable: false,
    autoShow: true,
    width: 600,
    height: 400,

    initComponent: function () {

        this.items = {
            xtype: 'materiacorequisitolist'
        };

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
                id: 'idBtnAplicar',
                icon: perfil.dirImg + 'aplicar.png',
                iconCls: 'btn',
                text: perfil.etiquetas.lbBtnAplicar,
                action: 'aplicar'
            },
            {
                id: 'idBtnAceptar',
                icon: perfil.dirImg + 'aceptar.png',
                iconCls: 'btn',
                text: perfil.etiquetas.lbBtnAceptar,
                action: 'aceptar'
            }
        ];

        this.callParent(arguments);
    }
})