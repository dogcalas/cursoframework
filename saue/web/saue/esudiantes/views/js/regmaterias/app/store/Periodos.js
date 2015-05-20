Ext.define('RegMaterias.store.Periodos', {
    extend: 'Ext.data.Store',
    model: 'RegMaterias.model.Periodos',
    autoLoad: false,
    storeId: 'idStorePeriodosMat',
    proxy: {
        type: 'ajax',
        api: {
            read: 'cargarPeriodos'
        },
        actionMethods: { //Esta Linea es necesaria para el metodo de llamada POST o GET
            read: 'POST'
			//update: 'POST'
        },
        reader: {
            root: 'datos',
            totalProperty: 'cantidad',
            successProperty: 'success',
            messageProperty: 'mensaje'
        }
    }
});