 Ext.define('GestConv.view.AddMatConv', {
    
    extend: 'Ext.window.Window',
    id:'addmatconv',
    closeAction:'hide',
    alias: 'widget.addmatconv',

                        title: 'Adicionar materia de convalidación',
                        width: 350,
                        height: 150,
                        constrain: true,
                        layout: 'fit',
                        items:[ Ext.create('Ext.form.Panel', {
                                frame: true,
                                bodyStyle: 'padding:5px 5px 0',
                                width: 350,
                                id:'formAddMateria',
                                fieldDefaults: {
                                    msgTarget: 'side',
                                    labelWidth: 80
                                },
                                defaults: {
                                    anchor: '100%'
                                },
                                items: [
                                    {
                                        xtype: 'textfield',
                                        fieldLabel: 'Descripci&oacute;n:',
                                        name: 'descripcion',
                                        anchor: '100%',
                                        labelAlign: 'top',
                                        allowBlank: false
                                    }, {
                                        xtype: 'checkbox',
                                        fieldLabel: 'Activado:',
                                        name: 'estado',
                                        hidden:true,
                                        checked: true
                                    },
                                    {
                                        xtype: 'hidden',
                                        name: 'idmateriaconvalidada'
                                    }]
                            })
                            ],
                        buttons: [{
                                text: 'Cancelar',
                                icon: perfil.dirImg + 'cancelar.png',
                                action: 'cancelmatconv'
                            }, {
                                text: 'Aplicar',
                                icon: perfil.dirImg + 'aplicar.png',
                                action: 'applymatconv',
                                id:'apply'
                            }, {
                                text: 'Aceptar',
                                icon: perfil.dirImg + 'aceptar.png',
                                action: 'addmatconv',
                                id:'add'
                            }
                        ],
                        initComponent: function () {    
                        var me = this;    
                            me.callParent(arguments);
                    }

 });

