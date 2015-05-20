Ext.define('GestCarreras.view.carrera.CarreraEdit', {
    extend: 'Ext.window.Window',
    alias: 'widget.carreraedit',

    //title: perfil.etiquetas.lbTtlEditar,
    layout: 'fit',
    modal: true,
    resizable: false,
    autoShow: true,
    width: 300,

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
                        name: 'idcarrera'
                    },
                    {
                        xtype: 'carrera_facultad_combo',
                        allowBlank: false
                    },
                    {
                        xtype: 'textfield',
                        fieldLabel: 'Carrera',
                        name: 'descripcion_carrera',
                        allowBlank: false,
                        labelAlign: 'top',
                        blankText: 'Campo requerido'
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
                id: 'idBtnAplicar',
                icon: perfil.dirImg + 'aplicar.png',
                iconCls: 'btn',
                text: perfil.etiquetas.lbBtnAplicar,
                action: 'aplicar'
            },
            {
                id: 'idBtnAceptar',
                icon: perfil.dirImg + 'aceptar.png',
                iconCls: 'btn',
                text: perfil.etiquetas.lbBtnAceptar,
                action: 'aceptar'
            }
        ];

        this.callParent(arguments);
    }
})