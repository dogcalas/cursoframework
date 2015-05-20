Ext.define('changePassw', {
    extend: 'Ext.Window',
    alias: 'widget.change_passw_win',
    id: 'change_passw_win',
    idalumno: null,
    idreporte: null,
    overflowY: 'scroll',
    backgroundColor: '#FFFFFF',

    /*    height: 210,
     width: 300,*/
    // maximized: true,
    layout: {
        type: 'fit'
    },
    initComponent: function () {
        var nombre = "";
        var me = this;
        Ext.QuickTips.init();
        sm = this.sm

        var id = sm.getSelection()[0].raw.idalumno;

        Ext.define('Ext.ux.form.MultiFile', {
            extend: 'Ext.form.field.File',

            /**
             * Override to add a "multiple" attribute.
             */
            createFileInput: function () {
                this.callParent(arguments);

                this.fileInputEl.set({
                    multiple: 'multiple'
                });
            }
        });
//        Formulario para enviar correos
        var formSendMail = Ext.create('Ext.form.Panel', {
            frame: true,
            items: [
                {
                    xtype: 'textfield',
                    fieldLabel: perfil.etiquetas.lbtxtfldContra,
                    name: 'toPersons',
                    id: 'pasword',
                    labelAlign: 'top',
                    inputType: 'password',
                    anchor: '95%',
                    allowBlank: false,
                    allowOnlyWhitespace: false,
                    tabIndex: 1
                },
                {
                    xtype: 'textfield',
                    fieldLabel: perfil.etiquetas.lbtxtfldContra1,
                    labelAlign: 'top',
                    name: 'Asunto',
                    inputType: 'password',
                    allowBlank: false,
                    allowOnlyWhitespace: false,
                    id: 'pasword1',
                    anchor: '95%',
                    tabIndex: 1
                }

            ]
        })

        Ext.apply(this, {
            title: 'Cambiar contrase&ntilde;a',
            maximizable: false,
            modal: true,
            width: 320,
            height: 180,
            items: formSendMail,
            dockedItems: [
                {
                    xtype: 'toolbar',
                    dock: 'bottom',
                    ui: 'footer',
                    layout: {
                        pack: 'center'
                    },
                    items: [
                        {
                            minWidth: 80,
                            icon: perfil.dirImg + 'comentar.png',
                            text: 'Cambiar',
                            handler: function () {
                                changePassw()
                            }

                        },
                        {
                            minWidth: 80,
                            icon: perfil.dirImg + 'cancelar.png',
                            text: 'Cancelar',
                            handler: function () {
                                me.close();
                            }

                        }
                    ]
                }
            ]
        });
        //    Ext.getCmp("toPersons").setValue(e_mails);

        function changePassw() {
            if (Ext.getCmp("pasword").getValue() != "" && Ext.getCmp("pasword").getValue() != "")
                if (Ext.getCmp("pasword").getValue() == Ext.getCmp("pasword1").getValue()) {
                    var sendMask = new Ext.LoadMask(Ext.getBody(), {
                        msg: 'Cambiando contrase&ntilde;a...'
                    });
                    sendMask.show();
                    Ext.Ajax.request({
                        url: 'changePassw',
                        params: {
                            password: Ext.getCmp("pasword").getValue(),
                            idusuario: sm.getSelection()[0].raw.idusuarioasig
                        },
                        callback: function (options, success, response) {
                            responseData = Ext.decode(response.responseText);
                            sendMask.disable();
                            sendMask.hide();
                            if (responseData.codMsg === 1) {
                                me.close();
                            }
                        }
                    });
                } else {
                    mostrarMensaje(3, 'Las contrse&ntilde;as no coinciden')
                }
        }


        me.callParent(arguments);
    }

//title: 'Vista previa',


})
;
