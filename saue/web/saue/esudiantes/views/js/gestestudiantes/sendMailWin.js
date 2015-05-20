Ext.define('sendMailWin', {
    extend: 'Ext.Window',
    alias: 'widget.send_mail_win',
    id: 'send_mail_win',
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

        var e_mails = "";
        e_mails = sm.getSelection()[0].raw.e_mail;

        for (var i = 1; i < sm.getCount(); i++) {
            e_mails += ";";
            e_mails += sm.getSelection()[i].raw.e_mail;
        }
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
                    fieldLabel: perfil.etiquetas.lbtxtfldPara,
                    name: 'toPersons',
                    id: 'toPersons',
                    anchor: '95%',
                    allowBlank: false,
                    tabIndex: 1
                },
                {
                    xtype: 'textfield',
                    fieldLabel: perfil.etiquetas.lbtxtfldAsunto,
                    name: 'Asunto',
                    id: 'asunto',
                    anchor: '95%',
                    tabIndex: 1
                },
                {
                    xtype: 'filefield',
                    buttonOnly: true,
                    hideLabel: true,
                    name: 'ad',
                    id: 'ad',
                    buttonText: 'Adjuntar archivo',
                    buttonConfig: {
                        icon: perfil.dirImg + 'subir.png',
                        iconCls: 'btn'
                    }

                },
                {
                    xtype: 'htmleditor',
                    name: 'msg',
                    id: 'msg',
                    anchor: '95%',
                    height: 300,
                    allowBlank: false,
                    maskRe: /[A-Z a-z]/,
                    tabIndex: 1
                }
            ]
        })
        Ext.getCmp("ad").onChange = function (fv, v) {
            adjuntarArchivo(fv, v);
        }

        Ext.apply(this, {
            title: 'Redactar mensaje',
            maximizable: false,
            scroll: false,
            modal: true,
            width: 700,
            height: 455,
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
                            text: 'Enviar',
                            handler: function () {
                                sendMail()
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
        Ext.getCmp("toPersons").setValue(e_mails);

        function sendMail() {
            var sendMask = new Ext.LoadMask(Ext.getBody(), {
                msg: 'Enviando mensaje...'
            });
            sendMask.show();
            Ext.Ajax.request({
                url: 'sendMailTo',
                params: {
                    emails: e_mails,
                    asunto: Ext.getCmp("asunto").getValue(),
                    text: Ext.getCmp("msg").getValue(),
                    nombre: nombre

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
        }

        function adjuntarArchivo(fb, v) {
            var addMask = new Ext.LoadMask(Ext.getBody(), {
                msg: 'Adjuntando archivo...'
            });
            nombre = Ext.getCmp("ad").getValue()
            addMask.show();
            formSendMail.getForm().submit({
                url: 'upload',
                failure: function (form, action) {
                    if (action.result.codMsg === 1) {
                      //  mostrarMensaje(1, perfil.etiquetas.lbMsgFunModificarMsg);
                    }
                }
            });
            addMask.disable();
            addMask.hide();
        }

        me.callParent(arguments);
    }

//title: 'Vista previa',


})
;
