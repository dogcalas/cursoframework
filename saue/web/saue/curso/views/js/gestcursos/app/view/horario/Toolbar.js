Ext.define('GestCursos.view.horario.Toolbar', {
    extend: 'Ext.toolbar.Toolbar',
    alias: 'widget.curso_horario_toolbar',

    initComponent: function () {
        var me = this;

        me.items = [
            {
                xtype: 'searchfield',
                id: 'horario_tb',
                store: 'GestCursos.store.Horarios',
                emptyText: 'Filtrar por descripción',
                width: 250,
                padding: '0 0 0 5',
                filterPropertysNames: ['descripcion']
            }
        ];

        this.callParent(arguments);
    }
});