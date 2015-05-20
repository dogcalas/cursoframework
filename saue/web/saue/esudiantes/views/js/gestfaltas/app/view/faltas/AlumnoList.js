Ext.define('GestFaltas.view.faltas.AlumnoList', {
    extend: 'Ext.grid.Panel',

    alias: 'widget.alumnolistf',
    id: 'alumnolistf',
    idalumno: 0,
    store: 'GestFaltas.store.Alumnos',
   
    columns: [
        { dataIndex: 'idalumno', hidden: true, hideable: false},
        { header: 'Código', dataIndex: 'codigo'},
        { header: 'Nombre', dataIndex: 'nombre', flex: 1},
        { header: 'Apellidos', dataIndex: 'apellidos', flex: 1 }
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