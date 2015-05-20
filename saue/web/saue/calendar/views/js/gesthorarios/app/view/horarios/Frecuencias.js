Ext.define('GestHorarios.view.horarios.Frecuencias', {
    extend: 'Ext.window.Window',
    alias: 'widget.frecuencias',
    layout: 'fit',
    modal: true,
    resizable: false,
    autoShow: true,
    width: 380,
    height: 380,

    initComponent: function () {
        this.items = [
            {
                xtype: 'form',
                fieldDefaults: {
                    msgTarget: 'side',
                    labelAlign:'top',
                    anchor:'100%'
                },
                defaults: {
                    padding: '5'
                },
                items: [
                    {
                        xtype: 'hidden',
                        name: 'idhorario'
                    },
                    {
                        xtype: 'hidden',
                        name: 'idhorario_detallado'
                    },
                    {
                        xtype: 'horariosdetalist'
                    }
                ]
            }
        ];

        this.buttons = [
            {
                icon: perfil.dirImg + 'aceptar.png',
                iconCls: 'btn',
                text: perfil.etiquetas.lbBtnAceptar,
                action: 'aceptar',
                scope: this,
                handler: this.close
            }
        ];

        this.callParent(arguments);
    }
})
