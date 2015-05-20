Ext.define('GestPeriodos.view.periodos.Activ_ActivasEdit', {
    extend: 'Ext.window.Window',
    alias: 'widget.activ_activasedit',
    layout: 'fit',
    modal: true,
    resizable: true,
    autoShow: true,
    width: 500,
    height: 400,

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
                        columnWidth: .5,
                        items: [
                            {
                                xtype: 'periodotree',
                                layout: 'fit'
                            }
                        ]
                    },
                    {
                        columnWidth: .5,
                        items: [
                            {
                                xtype: 'accionlist',
                                layout: 'fit',
                                height: 390,
                                width: 250
                            }
                        ]
                    }
                ]
            }
        ];
        this.callParent(arguments);
    }
});
