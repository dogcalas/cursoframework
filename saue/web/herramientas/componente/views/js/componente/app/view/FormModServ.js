Ext.define('GestComponentes.view.FormModServ', {
    extend: 'Ext.form.Panel',
    alias: 'widget.formmodserv',
    frame: true,
    width: 260,
    height: 80,
    initComponent: function() {
        this.items = [{xtype: 'panel', title: perfil.etiquetas.lbTitServicio, frame: true,
                layout: 'column',
                items: [{xtype: 'textfield',
                        width: 230,
                        frame: true,
                        name: 'nombreservm',
                        fieldLabel: perfil.etiquetas.lbTitNombre,
                        blankText: perfil.etiquetas.lbMsgBlank,
                        allowBlank: false,
                        itemId: 'nombreservm',
                        enableKeyEvents: true,
                        regex: /^([a-zA-ZáéíóúñüÑ]+[a-zA-ZáéíóúñüÑ\d ]*)+$/,
                        regexText: perfil.etiquetas.lbFLDatInc,
                    }
                ]
            }
        ];
        this.callParent();
    },
});
