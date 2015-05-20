Ext.define('GestComponentes.view.FormAddServ', {
    extend: 'Ext.form.Panel',
    alias: 'widget.formaddserv',
    frame: true,
    width: 260,
    height: 80,
    initComponent: function() {
        this.items = [{xtype: 'panel', title: perfil.etiquetas.lbTitServicio, frame: true,
                layout: 'column',
                items: [{xtype: 'textfield',
                        width: 230,
                        frame: true,
                        name: 'nombreserv',
                        fieldLabel: perfil.etiquetas.lbTitNombre,
                        allowBlank: false,
                        itemId: 'nombreserv',
                        enableKeyEvents: true,
                        blankText:perfil.etiquetas.lbMsgBlank,
                        regex: /^([a-zA-ZáéíóúñüÑ]+[a-zA-ZáéíóúñüÑ\d ]*)+$/,
                        regexText: perfil.etiquetas.lbFLDatInc,
                    }
                ]
            }
        ];
        this.callParent();
    },
});
