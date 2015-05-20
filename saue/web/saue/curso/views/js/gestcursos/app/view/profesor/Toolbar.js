Ext.define('GestCursos.view.profesor.Toolbar', {
    extend: 'Ext.toolbar.Toolbar',
    alias: 'widget.curso_profesor_toolbar',

    initComponent: function () {
        var me = this;

        me.items = [
            {
                xtype: 'searchfield',
                store: 'GestCursos.store.Profesores',
                emptyText: 'Filtrar por nombre(s) o apellidos',
                width: 250,
                padding: '0 0 0 5',
                filterPropertysNames: ['nombre', 'apellidos']
            }
        ];

        this.callParent(arguments);
    }
});