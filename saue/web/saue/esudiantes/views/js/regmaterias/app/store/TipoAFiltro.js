Ext.define('RegMaterias.store.TipoAFiltro', {
    extend: 'Ext.data.Store',
    model: 'RegMaterias.model.TipoAFiltro',
    autoLoad: true,
    storeId: 'idStoreTipoAprobado',

    proxy: {
        type: 'ajax',
        api: {
            read: 'cargarTipoFiltro'
        },
        actionMethods: {
            read: 'POST'
        },
        reader: {
            root: 'datos'
        }
    }
});