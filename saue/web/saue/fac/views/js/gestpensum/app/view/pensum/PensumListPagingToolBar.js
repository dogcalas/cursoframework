Ext.define('GestPensums.view.pensum.PensumListPagingToolBar', {
    extend: 'Ext.toolbar.Paging',
    alias: 'widget.pensumlistpbar',

    displayInfo: true,

    initComponent: function(){
        var me = this;

        me.store = 'GestPensums.store.Pensums';

        me.callParent(arguments);
    }
})