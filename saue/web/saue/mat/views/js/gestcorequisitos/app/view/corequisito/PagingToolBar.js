Ext.define('GestCoRequisitos.view.corequisito.PagingToolBar', {
    extend: 'Ext.toolbar.Paging',
    alias: 'widget.corequisitolistpbar',

    displayInfo: true,

    initComponent: function(){
        var me = this;

        //me.store = Ext.data.StoreManager.lookup('idStoreCoRequisito');

        me.callParent(arguments);
    }
})