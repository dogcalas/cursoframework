Ext.define('GestLocales.view.locales.LocalListPagingToolBar', {
    extend: 'Ext.toolbar.Paging',
    alias: 'widget.locallistpbar',

    displayInfo: true,

    initComponent: function(){
        var me = this;

        me.store = 'GestLocales.store.Locales';

        me.callParent(arguments);
    }
})