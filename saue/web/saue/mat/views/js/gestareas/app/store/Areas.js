Ext.define('GestAreas.store.Areas', {
    extend: 'Ext.data.Store',
    model: 'GestAreas.model.Area',
    //alias: 'widget.store_areas',

    storeId: 'idStoreAreas',
    autoLoad: true,
    pageSize: 24,
    proxy: {
        type: 'ajax',
        api: {
            read: 'cargarAreas',
            create: 'insertarAreas',
            update: 'modificarAreas',
            destroy: 'eliminarAreas'
        },
        actionMethods: { //Esta Linea es necesaria para el metodo de llamada POST o GET
            read: 'POST',
            update: 'POST'
        },
        reader: {
            root: 'datos',
            totalProperty: 'cantidad',
            idProperty: 'idarea',
            successProperty: 'success',
            messageProperty: 'mensaje'
        }
    }
});