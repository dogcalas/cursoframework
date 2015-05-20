Ext.define('GestMenciones.view.mencion.PagingToolBar', {
    extend: 'Ext.toolbar.Paging',
    alias: 'widget.mencion_paging',

    displayInfo: true,

    initComponent: function(){
        var me = this;

        me.store = 'GestMenciones.store.Menciones';

        me.callParent(arguments);
    }
})