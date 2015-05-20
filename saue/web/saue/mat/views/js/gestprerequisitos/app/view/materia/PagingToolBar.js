Ext.define('GestPreRequisitos.view.materia.PagingToolBar', {
    extend: 'Ext.toolbar.Paging',
    alias: 'widget.prerequisitomaterialistpbar',

    displayInfo: true,

    initComponent: function(){
        var me = this;

        me.store = Ext.data.StoreManager.lookup('idStorePreRequisitoMateria');

        me.callParent(arguments);
    }
})