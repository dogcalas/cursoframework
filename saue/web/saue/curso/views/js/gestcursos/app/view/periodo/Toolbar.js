Ext.define('GestCursos.view.periodo.Toolbar', {
    extend: 'Ext.toolbar.Toolbar',
    alias: 'widget.curso_periodo_toolbar',

    initComponent: function () {
        var me = this;

        me.items = [
            {
                xtype: 'searchfield',
                store: 'GestCursos.store.Periodos',
                emptyText: 'Filtrar por descripcion',
                width: 250,
                padding: '0 0 0 5',
                filterPropertysNames: ['descripcion']
            }
        ];

        this.callParent(arguments);
    }
});