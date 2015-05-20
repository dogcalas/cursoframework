Ext.define('GestMateriaxMencion.view.materia.Toolbar', {
    extend: 'Ext.toolbar.Toolbar',
    alias: 'widget.materiaxmencion_materia_tbar',

    initComponent: function () {
        var me = this;

        me.items = [
            {
                xtype: 'searchfield',
                store: 'GestMateriaxMencion.store.Materias',
                emptyText: 'Filtrar por materia',//Etiqueta
                width: 250,
                padding: '0 0 0 5',
                labelWidth: 40,
                filterPropertysNames: ['codmateria', 'descripcion']
            }
        ];

        me.callParent(arguments);
    }
});