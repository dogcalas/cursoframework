Ext.define('RegMaterias.view.materia.Toolbar', {
    extend: 'Ext.toolbar.Toolbar',
    alias: 'widget.materia_toolbar',

    initComponent: function () {
        var me = this;

        me.items = [
            {
                xtype: 'searchfield',
                store: 'GestCursos.store.Materias',
                emptyText: 'Filtrar materias',
                width: 250,
                padding: '0 0 0 5',
                filterPropertysNames: ['descripcion']
            }
        ];

        this.callParent(arguments);
    }
});