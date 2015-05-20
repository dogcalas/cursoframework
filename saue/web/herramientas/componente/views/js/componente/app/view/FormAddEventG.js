Ext.define('GestComponentes.view.FormAddEventG', {
    extend: 'Ext.form.Panel',
    alias: 'widget.formaddeventg',
    frame: true,
    width: 320,
    height: 340,  region: 'east',
    initComponent: function() {
        this.items = [{xtype: 'panel', title: perfil.etiquetas.lbTitFormAddEventG, frame: true,
                layout: 'form',
                border: 0,
                margin: '5 5 5 5',
                items: [{xtype: 'textfield',
                        labelAlign: 'top',
                        name: 'nombreventg',
                        fieldLabel: perfil.etiquetas.lbTitIdent,
                        allowBlank: false,
                        itemId: 'nombreventg',
                        blankText:perfil.etiquetas.lbMsgBlank,
                        regex: /^([a-zA-ZáéíóúñüÑ]+[a-zA-ZáéíóúñüÑ\d ]*)+$/,
                        regexText: perfil.etiquetas.lbFLDatInc,
                    }
                    ,
                    {xtype: 'checkbox',
                        labelAlign: 'top',
                        name: 'clase',
                        itemId: 'clase',
                        fieldLabel: perfil.etiquetas.lbTitChBclase,
                    },
                    {xtype: 'textfield',
                        labelAlign: 'top',
                        fieldLabel: perfil.etiquetas.lbTitDir,
                        allowBlank: true,
                        itemId: 'nombreclase',
                        regex: /^([a-zA-ZáéíóúñüÑ\d\.\/\-\_ ])+([a-zA-ZáéíóúñüÑ\d\-\_ ])\.php$/ ,
                        regexText: perfil.etiquetas.lbMsgEvenErrorDir,
                        disabled: true,
                        name: 'nombreclase'
                    }

                ]
            }
        ];
        this.callParent();
    },
});
