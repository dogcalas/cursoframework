Ext.define('GestPeriodos.view.periodos.PeriodoActivoEdit', {
    extend: 'Ext.window.Window',
    alias: 'widget.periodoactivoedit',
    id: 'periodoactivoedit',
    layout: 'fit',
    modal: true,
    resizable: false,
    autoShow: true,
    width: 520,
    height: 400,

    initComponent: function () {
        this.items = [
            {
                xtype: 'accionlist',
                layout: 'fit',
                height: 300,
                width: 250
            }
        ];

        this.buttons = [
            {
                icon: perfil.dirImg + 'cancelar.png',
                iconCls: 'btn',
                text: perfil.etiquetas.lbBtnCancelar,
                action: 'cancelar',
                scope: this
            },
            {
                id: 'idBtnAceptar',
                icon: perfil.dirImg + 'aceptar.png',
                iconCls: 'btn',
                text: perfil.etiquetas.lbBtnAceptar,
                action: 'aceptar'
            }
        ];

        this.tbar = [
            {
                xtype: 'periodo_roles_combo'
            },
            {
                xtype: 'datefield',
                id: 'fecha_ini',
                fieldLabel: 'Fecha inicio',
                editable: false,
                allowBlank: false,
                padding: '0 0 0 8',
                format: 'Y-m-d',
                labelAlign: 'top',
                width: 140,
                disabled: true,
                listeners: {
                    'change': function (field, value) {
                        if (value) {
                            Ext.getCmp('fecha_fin').setMinValue(value);
                            Ext.getCmp('fecha_fin').enable();
                        }
                    }
                }
            },
            {
                xtype: 'datefield',
                id: 'fecha_fin',
                fieldLabel: 'Fecha fin',
                format: 'Y-m-d',
                padding: '0 0 0 8',
                editable: false,
                width: 140,
                disabled: true,
                labelAlign: 'top',
                allowBlank: false
            }
        ];

        this.callParent(arguments);
    }
});