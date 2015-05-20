Ext.define('GestComponentes.view.FormAddDep', {
    extend: 'Ext.form.Panel',
    alias: 'widget.formadddep',
    frame: true,
    width: 320,
    height: 340,
    region: 'east',
    initComponent: function() {
        this.items = [{xtype: 'panel', title: perfil.etiquetas.lbTitDep,
                layout: 'form',
                border: 0,
                margin: '5 5 5 5',
                items: [{xtype: 'textfield',
                        labelAlign: 'top',
                        name: 'nombredep',
                        fieldLabel: perfil.etiquetas.lbTitIdent,
                        allowBlank: false,
                        itemId: 'nombredep',
                        enableKeyEvents: true,
                        regex: /^([a-zA-ZáéíóúñüÑ]+[a-zA-ZáéíóúñüÑ\d ]*)+$/,
                        regexText: perfil.etiquetas.lbFLDatInc,
                        blankText:perfil.etiquetas.lbMsgBlank,
                    }
                    ,
                    {xtype: 'checkbox',
                        frame: true,
                        name: 'opcional',
                        labelAlign: 'top',
                        fieldLabel: perfil.etiquetas.lbTitOpc,
                    }, {xtype: 'textfield',
                        labelAlign: 'top',
                        frame: true,
                        name: 'use',
                       readOnly:true,
                        fieldLabel: perfil.etiquetas.lbTitUso,
                        itemId: 'usedep'}, {
                        xtype: 'textfield',
                        labelAlign: 'top',
                        frame: true,
                        name: 'interface',
                        readOnly:true,
                        fieldLabel: perfil.etiquetas.lbTitInterfaz,
                        width: 290,
                        margin: '8 0 0 0',
                        itemId: 'interfacedep'
                    },
                ]
            }
        ];
        this.callParent();
    },
});
