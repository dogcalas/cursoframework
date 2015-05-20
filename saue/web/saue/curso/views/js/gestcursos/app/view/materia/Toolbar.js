Ext.define('GestCursos.view.materia.Toolbar', {
    extend: 'Ext.toolbar.Toolbar',
    alias: 'widget.curso_materia_toolbar',

    initComponent: function () {
        var me = this;

        me.items = [
            {
                xtype: 'searchfield',
                store: 'GestCursos.store.Materias',
                emptyText: 'Filtrar por código o descripción',
                width: 250,
                padding: '0 0 0 5',
                filterPropertysNames: ['codmateria', 'descripcion']
            }
        ];

        this.callParent(arguments);
    }
});