Ext.define('GestComponentes.view.TabPanelGeneral', {
    extend: 'Ext.tab.Panel',
    alias: 'widget.tabpanelgeneral',
    region: 'center',
    itemId: 'tabgeneral',
    disabled: true,
    initComponent: function() {
        this.items = [{xtype: 'gridpanelcomp'},
        ];
        
        this.callParent();
    }




});
