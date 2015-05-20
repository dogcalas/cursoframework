Ext.define('GestComponentes.view.WinInsComp', {
    extend: 'Ext.Window',
    alias: 'widget.wininscomp',
    closeAction: 'destroy',
    layout: 'fit',
    resizable: false,
    title: perfil.etiquetas.lbTitRegComponent,
    width: 350,
    height: 160,
    initComponent: function() {

        this.buttons = [{xtype: 'button',
                itemId: 'btncancAddComp',
                icon: perfil.dirImg + 'cancelar.png',
                iconCls: 'btn',
                text: perfil.etiquetas.lbBtnCancelar

            },
//            {
//                xtype: 'button',
//                itemId: 'btnapliAddComp',
//                icon: perfil.dirImg + 'aplicar.png',
//                iconCls: 'btn',
//                text: perfil.etiquetas.lbBtnAplicar,
//            },
            {xtype: 'button',
                itemId: 'btnacepAddComp',
                icon: perfil.dirImg + 'aceptar.png',
                iconCls: 'btn',
                text: perfil.etiquetas.lbBtnAceptar,
            }];

        this.callParent();
    }
});
