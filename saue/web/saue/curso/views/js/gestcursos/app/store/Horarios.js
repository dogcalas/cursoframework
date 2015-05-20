Ext.define('GestCursos.store.Horarios', {
    extend: 'Ext.data.Store',
    model: 'GestCursos.model.Horario',

    storeId: 'idCursosStoreHorarios',
    //autoLoad: true,
    pageSize: 25,
    proxy: {
        type: 'rest',
        url: 'cargarHorarios',
        actionMethods: {
            read: 'POST'
        },
        reader: {
            root: 'datos',
            totalProperty: 'cantidad',
            successProperty: 'success'
        }
    }
});