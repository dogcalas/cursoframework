Ext.define('GestMaterias.view.materia.PagingToolBar', {
    extend: 'Ext.toolbar.Paging',
    alias: 'widget.materialistpbar',

    displayInfo: true,

    initComponent: function(){
        var me = this;

        me.store = Ext.data.StoreManager.lookup('idStoreMaterias');

        me.callParent(arguments);
    }
})