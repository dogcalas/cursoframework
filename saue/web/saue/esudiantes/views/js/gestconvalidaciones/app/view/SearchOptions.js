Ext.define('GestConv.view.SearchOptions', {
    extend: 'Ext.container.Container',
    alias: 'widget.searchoptions',
    layout: 'hbox',
    items: [
        {
            xtype: 'combobox',
            id: 'anno',
            fieldLabel: 'Año',
            queryMode: 'local',
            valueField: 'anno',
            displayField: 'anno',
            width: '20%',
            editable: false,
            labelAlign: 'top',
            store: 'GestConv.store.Annos',
            allowBlank: false,
            margin: '5 0 5 5'
        },
        {
            xtype: 'combobox',
            id: 'periodo',
            width: '80%',
            editable: false,
            fieldLabel: 'Período',
            queryMode: 'local',
            allowBlank: false,
            forceSelection: true,
            labelAlign: 'top',
            store: 'GestConv.store.Periodos',
            valueField: 'idperiododocente',
            displayField: 'descripcion',
            margin: '5 2 5 20'
        }
    ],

    initComponent: function () {
        var me = this;

        me.callParent(arguments);
    }
});