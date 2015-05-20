Ext.define('GestEnfasis.view.enfasi.EnfasiListPagingToolBar', {
    extend: 'Ext.toolbar.Paging',
    alias: 'widget.enfasilistpbar',

    displayInfo: true,

    initComponent: function(){
        var me = this;

        me.store = Ext.data.StoreManager.lookup('idStoreEnfasis');

        me.callParent(arguments);
    }
})