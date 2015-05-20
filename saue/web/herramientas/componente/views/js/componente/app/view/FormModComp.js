Ext.define('GestComponentes.view.FormModComp', {
    extend: 'Ext.form.Panel',
    alias: 'widget.formmodcomp',
    trackResetOnLoad: true,
    labelAlign: 'top',
    bodyStyle: 'padding:5px 5px 0',
    initComponent: function() {
        this.items = [{
                layout: 'column',
                items: [{
                        columnWidth: .5,
                        layout: 'form',
                        border: 0,
                        margin: '5 5 5 5',
                        items: [{
                                xtype: 'textfield',
                                labelAlign: 'top',
                                fieldLabel: perfil.etiquetas.lbTitId,
                                itemId: 'id',
                                name: 'id',
                                readOnly: true,
                                anchor: '95%'
                            }]
                    },
                    {
                        columnWidth: .5,
                        layout: 'form',
                        border: 0,
                        margin: '5 5 5 5',
                        items: [{
                                xtype: 'textfield',
                                fieldLabel: perfil.etiquetas.lbTitNombre,
                                labelAlign: 'top',
                                itemId: 'nombre',
                                name: 'nombre',
                                maxLength: 100,
                                allowBlank: false,
                                blankText: perfil.etiquetas.lbMsgBlank,
                                regex: /^([a-zA-ZáéíóúñüÑ]+[a-zA-ZáéíóúñüÑ\d ]*)+$/,
                                regexText: perfil.etiquetas.lbFLDatInc,
                                anchor: '95%'
                            }]
                    },
                    {
                        columnWidth: .5,
                        layout: 'form',
                        border: 0,
                        margin: '5 5 5 5',
                        items: [{
                                xtype: 'textfield',
                                labelAlign: 'top',
                                fieldLabel: perfil.etiquetas.lbTitEstado,
                                readOnly: true,
                                itemId: 'estado',
                                name: 'estado',
                                anchor: '95%'
                            }]
                    },
                    {
                        columnWidth: .5,
                        layout: 'form',
                        border: 0,
                        margin: '5 5 5 5',
                        items: [{
                                xtype: 'textfield',
                                labelAlign: 'top',
                                fieldLabel: perfil.etiquetas.lbTitVersion,
                                itemId: 'version',
                                name: 'version',
                                maxLength: 100,
                                allowBlank: true,
                                blankText: perfil.etiquetas.lbMsgBlank,
                                regex: /^([0-9\d\.]*)+$/,
                                regexText: perfil.etiquetas.lbMsgRegexVersion,
                                anchor: '100%'
                            }]
                    },
                    {
                        columnWidth: 1,
                        layout: 'form',
                        border: 0,
                        margin: '5 5 5 5',
                        items: [{
                                xtype: 'textfield',
                                fieldLabel: perfil.etiquetas.lbTitUbicacion,
                                labelAlign: 'top',
                                itemId: 'direccion',
                                name: 'direccion',
                                maxLength: 500,
                                allowBlank: true,
                                blankText: perfil.etiquetas.lbMsgBlank,
                                anchor: '100%'
                            }, {xtype: 'label',
                                text: perfil.etiquetas.lbTitLabelI}]
                    }
                ]
            },
        ];
        this.callParent();
    },
});
