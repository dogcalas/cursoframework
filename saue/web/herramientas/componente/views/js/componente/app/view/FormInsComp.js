Ext.define('GestComponentes.view.FormInsComp', {
    extend: 'Ext.form.Panel',
    alias: 'widget.forminscomp',
    labelAlign: 'top',
    bodyStyle: 'padding:5px 5px 0',
    initComponent: function() {
        this.items = [{
                layout: 'column',
                items: [{
                        columnWidth: 1,
                        layout: 'form',
                        border: 0,
                        margin: '5 5 5 5',
                        items: [{
                                xtype: 'textfield',
                                labelAlign: 'top',
                                fieldLabel: perfil.etiquetas.lbTitUbicacion,
                                regex: /^\/{1}([a-zA-ZáéíóúñüÑ\d\.\/\-\_ ])+([a-zA-ZáéíóúñüÑ\d\-\_ ])\.scl$/,
                                regexText: perfil.etiquetas.lbFLDatInc,
                                itemId: 'DirComp',
                                maxLength: 400,
                                allowBlank: false,
                                name: 'direccion',
                                readOnly:true,
                                blankText: perfil.etiquetas.lbMsgBlank,
                                anchor: '100%'
                            }, {
                                xtype: 'label',
                                labelStyle: 'font-size:15px;',
                                itemCls: 'required',
                                itemId: 'label',
                                maxLength: 400,
                                anchor: '100%',
                                text: perfil.etiquetas.lbTitLabel

                            }
                        ]
                    }]
            }];
        this.callParent();
    },
});
