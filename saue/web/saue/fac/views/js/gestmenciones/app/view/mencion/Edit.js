Ext.define('GestMenciones.view.mencion.Edit', {
    extend: 'Ext.window.Window',
    alias: 'widget.mencion_edit',

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
                        name: 'idmencion'
                    },
                    {
                        xtype: 'pensum_facultad_combo'
                    },
                    {
                        xtype: 'textareafield',
                        fieldLabel: perfil.etiquetas.lbHdrDescripcion,
                        name: 'descripcion',
                        labelAlign: 'top',
                        allowBlank: false,
                        blankText: 'Campo requerido'
                    },
                    {
                        xtype: 'numberfield',
                        fieldLabel: 'Cant. materias',//perfil.etiquetas.lbHdrDescripcion,
                        name: 'cant_materias',
                        allowBlank: false,
                        labelAlign: 'top',
                        blankText: 'Campo requerido',
                        minValue: 1,
                        value: 1
                    },
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