Ext.define('GestCarreras.view.carrera.CarreraListPagingToolBar', {
    extend: 'Ext.toolbar.Paging',
    alias: 'widget.carreralistpbar',

    displayInfo: true,

    initComponent: function(){
        var me = this;

        me.store = Ext.data.StoreManager.lookup('idStoreCarreras');

        me.callParent(arguments);
    }
})