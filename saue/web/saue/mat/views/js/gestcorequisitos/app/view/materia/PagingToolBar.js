Ext.define('GestCoRequisitos.view.materia.PagingToolBar', {
    extend: 'Ext.toolbar.Paging',
    alias: 'widget.corequisitomaterialistpbar',

    displayInfo: true,

    initComponent: function(){
        var me = this;

        me.store = Ext.data.StoreManager.lookup('idStoreCoRequisitoMateria');

        me.callParent(arguments);
    }
})