Ext.define('GestComponentes.view.FormAddEventO', {
    extend: 'Ext.form.Panel',
    alias: 'widget.formaddevento',
    frame: true,
    width: 320,
    height: 340,  region: 'east',
    initComponent: function() {
        this.items = [{xtype: 'panel', title: perfil.etiquetas.lbTitFormAddEventO, frame: true,
                layout: 'form',
                border: 0,
                margin: '5 5 5 5',
                items: [{xtype: 'textfield',
                        labelAlign: 'top',
                        frame: true,
                        name: 'nombrevento',
                        fieldLabel: perfil.etiquetas.lbTitEvento,
                        allowBlank: false,
                        itemId: 'nombrevento',
                        blankText:perfil.etiquetas.lbMsgBlank,
                        regex: /^([a-zA-ZáéíóúñüÑ]+[a-zA-ZáéíóúñüÑ\d ]*)+$/,
                        regexText: perfil.etiquetas.lbFLDatInc,
                    }
          ,
                    {xtype: 'textfield',
                        frame: true,
                        labelAlign: 'top',
                        fieldLabel: perfil.etiquetas.lbTitImplement,
                        allowBlank: true,
                        width: '100%',
                        itemId: 'implobs',
                         readOnly:true,
                        disabled: false,
                        name: 'impl'
                    }

                ]
            }
        ];
        this.callParent();
    },
});
