Ext.define('GestAreas.view.area.PagingToolBar', {
    extend: 'Ext.toolbar.Paging',
    alias: 'widget.arealistpbar',

    displayInfo: true,

    initComponent: function(){
        var me = this;

        me.store = Ext.data.StoreManager.lookup('idStoreAreas');

        me.callParent(arguments);
    }
})