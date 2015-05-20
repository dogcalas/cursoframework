Ext.define('GestPensums.view.pensum.PensumEdit', {
    extend: 'Ext.window.Window',
    alias: 'widget.pensumedit',

    layout: 'fit',
    modal: true,
    resizable: false,
    autoShow: true,
    width: 300,

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
                        name: 'idpensum'
                    },
                    {
                        xtype: 'pensum_facultad_combo'
                    },
                    {
                        xtype: 'pensum_carrera_combo',
                        fieldLabel: perfil.etiquetas.lbHdrDescripcionCarrera
                    },
                    {
                        xtype: 'textfield',
                        fieldLabel: perfil.etiquetas.lbHdrDescripcion,
                        name: 'descripcion_pensum',
                        allowBlank: false,
                        labelAlign: 'top',
                        blankText: 'Campo requerido'
                    },
                    {
                        id: 'idestado_pensum',
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

        me.callParent(arguments);
    }
})