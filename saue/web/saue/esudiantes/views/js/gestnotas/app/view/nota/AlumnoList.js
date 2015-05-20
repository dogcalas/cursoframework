Ext.define('GestNotas.view.nota.AlumnoList', {
    extend: 'Ext.grid.Panel',

    alias: 'widget.alumnolist',
    id: 'alumnolist',
    store: 'GestNotas.store.Alumnos',
    columns: [
        { dataIndex: 'idalumno', hidden: true, hideable: false},
        { dataIndex: 'anno', hidden: true, hideable: false},
        { header: 'Código', dataIndex: 'codigo'},
        { header: 'Apellidos', dataIndex: 'apellidos', flex: 1 },
        { header: 'Nombre', dataIndex: 'nombre', flex: 1},
        { header: 'Cédula', dataIndex: 'cedula'},
        { hidden: true, dataIndex: 'idpensum'}
    ],

    initComponent: function () {
        var me = this;
        me.bbar = Ext.create('Ext.toolbar.Paging', {
            displayInfo: true,
            store: me.store
        });

        me.callParent(arguments);
    }
});