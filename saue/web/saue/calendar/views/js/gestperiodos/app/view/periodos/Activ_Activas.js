Ext.define('GestPeriodos.view.periodos.Activ_Activas', {
    extend: 'Ext.window.Window',
    alias: 'widget.activ_activas',
    layout: 'fit',
    modal: true,
    resizable: false,
    autoShow: true,
    width: 700,
    height: 500,

    initComponent: function () {
        this.items = [
            {
                xtype: 'form',
                layout: 'column',
                fieldDefaults: {
                    msgTarget: 'side',
                    anchor: '100%'
                },
                items: [
                    {
                        columnWidth: .30,
                        items: [
                            {
                                xtype: 'periodotree',
                                layout: 'fit'
                            }
                        ]
                    },
                    {
                        columnWidth: .70,
                        items: [
                            {
                                xtype: 'activ_activaslist',
                                layout: 'fit',
                                height: 465
                            }
                        ]
                    }
                ]
            }
        ];
        this.callParent(arguments);
    }
});
