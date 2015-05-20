Ext.define('CredxArea.view.credxarea.Edit', {
    extend: 'Ext.window.Window',
    alias: 'widget.credxarea_edit',

    layout: 'fit',
    modal: true,
    resizable: false,
    autoShow: true,
    width: 400,

    initComponent: function () {
        this.items = [
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
                        name: 'idareacredito'
                    },
                    {
                        xtype: 'enfasis_facultad_combo',
                        allowBlank: false
                    },
                    {
                        xtype: 'credxarea_carrera_combo',
                        allowBlank: false,
                        disabled: true
                    },
                    {
                        xtype: 'credxarea_enfasi_combo',
                        allowBlank: false,
                        disabled: true
                    },
                    {
                        xtype: 'credxarea_pensum_combo',
                        allowBlank: false,
                        disabled: true
                    },
                    {
                        xtype: 'credxarea_area_general_combo',
                        allowBlank: false
                    },
                    {
                        xtype: 'credxarea_area_combo',
                        allowBlank: false,
                        disabled: true
                    },
                    {
                        xtype: 'numberfield',
                        fieldLabel: perfil.etiquetas.lbHdrCreditos,
                        name: 'creditos',
                        allowBlank: false,
                        labelAlign: 'top',
                        blankText: 'Campo requerido',
                        minValue: 1,
                        value: 1
                    },
                    {
                        id: 'idestado_carrera',
                        xtype: 'checkbox',
                        fieldLabel: perfil.etiquetas.lbHdrEstado,
                        checked: true,
                        inputValue: true,
                        uncheckedValue: false,
                        name: 'estado'
                    }
                ]
            }
        ];

        this.buttons = [
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

        this.callParent(arguments);
    }
})