Ext.define('GestPasantias.view.pasantia.Edit', {
    extend: 'Ext.window.Window',
    alias: 'widget.pasantia_edit',

    //title: perfil.etiquetas.lbTtlEditar,
    layout: 'fit',
    modal: true,
    resizable: false,
    autoShow: true,
    width: 400,

    initComponent: function () {
        var me = this;

        me.items = [
            {
                xtype: 'form',
                fieldDefaults: {
                    msgTarget: 'side',
                    anchor: '100%'
                },
                defaults: {
                    padding: '5'
                },
                items: [
                    {
                        xtype: 'hidden',
                        name: 'idpasantia'
                    },
                    {
                        xtype: 'enfasis_facultad_combo'//'pasantias_facultad_combo'
                    },
                    {
                        xtype: 'pasantias_carrera_combo'
                    },
                    {
                        xtype: 'pasantias_enfasi_combo'
                    },
                    {
                        xtype: 'tipo_pasantias_combo'
                    },
                    {
                        xtype: 'numberfield',
                        fieldLabel: perfil.etiquetas.lbHdrHoras,
                        name: 'horas',
                        labelAlign: 'top',
                        allowBlank: false,
                        minValue: 1,
                        value: 1
                    },
                    //{
                    //    xtype: 'textfield',
                    //    fieldLabel: perfil.etiquetas.lbHdrEmpresa,
                    //    name: 'empresa',
                    //    labelAlign: 'top',
                    //    allowBlank: true
                    //},
                    {
                        xtype: 'checkbox',
                        fieldLabel: perfil.etiquetas.lbHdrEstado,
                        checked: true,
                        name: 'estado',
                        inputValue: true,
                        uncheckedValue: false
                    }
                ]
            }
        ];

        me.buttons = [
            {
                icon: perfil.dirImg + 'cancelar.png',
                iconCls: 'btn',
                text: perfil.etiquetas.lbBtnCancelar,
                action: 'cancelar',
                scope: this,
                handler: this.close
            },
            {
                icon: perfil.dirImg + 'aplicar.png',
                iconCls: 'btn',
                text: perfil.etiquetas.lbBtnAplicar,
                action: 'aplicar'
            },
            {
                icon: perfil.dirImg + 'aceptar.png',
                iconCls: 'btn',
                text: perfil.etiquetas.lbBtnAceptar,
                action: 'aceptar'
            }
        ];

        me.callParent(arguments);
    }
})