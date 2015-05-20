Ext.define('GestPeriodos.store.Roles', {
    extend: 'Ext.data.Store',
    fields: [
        {
            name: 'idrol',
            mapping: 'idrol'
        },
        {
            name: 'denominacion',
            mapping: 'denominacion'
        }
    ],
    autoLoad: true,
    proxy: {
        type: 'ajax',
        api: {
            read: 'cargarRoles'
        },
        actionMethods: {//Esta Linea es necesaria para el metodo de llamada POST o GET
            read: 'POST'
        },
        reader: {
            totalProperty: "cantidad_filas",
            root: "datos"
        }
    }

});