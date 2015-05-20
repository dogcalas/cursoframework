Ext.define('GestCursos.view.mail.Mail', {
    extend: 'Ext.window.Window',
    alias: 'widget.mail_send',

    layout: 'fit',
    modal: true,
    resizable: false,
    autoShow: true,
    width: 500,

    initComponent: function () {

        this.items = [
            {
                xtype: 'form',
                fieldDefaults: {
                    msgTarget: 'side',
                    anchor: '100%',
                    labelWidth: 60
                },
                defaults: {
                    padding: '5'
                },
                defaultType: 'combo',
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
                        },
                        action: 'adjunto'


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
            }
        ];

        this.buttons = [
            {
                minWidth: 80,
                icon: perfil.dirImg + 'comentar.png',
                iconCls: 'btn',
                text: 'Enviar',
                action: 'send'

            },
            {
                minWidth: 80,
                icon: perfil.dirImg + 'cancelar.png',
                text: 'Cancelar',
                scope: this,
                handler: this.close
            }
        ];

        this.callParent(arguments);
    }
})
