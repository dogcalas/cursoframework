Ext.define('RegMaterias.view.materia.WindowSearch', {
    extend: 'Ext.window.Window',
    alias: 'widget.windowsearchmateria',

    //title: perfil.etiquetas.lbTtlEditar,
    layout: 'fit',
    modal: true,
    resizable: false,
    autoShow: true,
    width:600,
    height:200,
    initComponent: function () {
        var me = this;
        
            me.title = "Buscar materia:";
            me.items = [
                {
                    xtype: 'form',
                    layout: 'fit',
                    tbar:[ {
                    xtype: 'searchfield',
                    store: Ext.widget(me.who).getStore(),
                    width: 400,
                    fieldLabel: perfil.etiquetas.lbBtnBuscar,
                    labelWidth: 40,
                    padding:'5',
                    filterPropertysNames: ["nombre", "apellidos"]
                }],
                    items: [
                        Ext.widget(me.who)
                    ]
                }
            ];
         
        me.buttons = [
            {
                icon: perfil.dirImg + 'cancelar.png',
                iconCls: 'btn',
                text: perfil.etiquetas.lbBtnCancelar,
                action: 'cancelar',
                scope: this,
                handler: this.close
            },
            {
                id: 'idBtnAceptar',
                icon: perfil.dirImg + 'aceptar.png',
                iconCls: 'btn',
                text: perfil.etiquetas.lbBtnAceptar,
                list:me.who,
                action: 'aceptar'
            }
        ];

        me.callParent(arguments);
    }
})