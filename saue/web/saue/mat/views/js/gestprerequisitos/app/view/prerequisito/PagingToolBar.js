Ext.define('GestPreRequisitos.view.prerequisito.PagingToolBar', {
    extend: 'Ext.toolbar.Paging',
    alias: 'widget.prerequisitolistpbar',

    displayInfo: true,

    initComponent: function(){
        var me = this;

        //me.store = Ext.data.StoreManager.lookup('idStorePreRequisito');

        me.callParent(arguments);
    }
})