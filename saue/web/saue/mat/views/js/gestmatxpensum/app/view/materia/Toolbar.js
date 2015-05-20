Ext.define('GestMatxPensum.view.materia.Toolbar', {
    extend: 'Ext.toolbar.Toolbar',
    alias: 'widget.matxpensum_materia_tbar',

    initComponent: function () {
        var me = this;

        me.items = [
            {
                xtype: 'searchfield',
                store: 'GestMatxPensum.store.Materias',
                emptyText: 'Filtrar por código o descripción',
                width: 250,
                padding: '0 0 0 5',
                labelWidth: 40,
                filterPropertysNames: ['codmateria', 'descripcion']
            }
        ];

        me.callParent(arguments);
    }
});