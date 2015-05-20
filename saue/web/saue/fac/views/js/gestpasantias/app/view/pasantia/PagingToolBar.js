Ext.define('GestPasantias.view.pasantia.PagingToolBar', {
    extend: 'Ext.toolbar.Paging',
    alias: 'widget.pasantia_paging',

    displayInfo: true,

    initComponent: function(){
        var me = this;

        me.store = 'GestPasantias.store.Pasantias';

        me.callParent(arguments);
    }
})