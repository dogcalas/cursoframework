Ext.define('RegMaterias.store.MateriasEst', {
    extend: 'Ext.data.Store',
    model: 'RegMaterias.model.MateriasEst',
    autoLoad: false,
    storeId: 'idStoreMaterias',
    pageSize: 25,
    /*listener agregado, cuando se carga el store, limpia el focus del filtro tipoaprobado LISTO*/
    listeners: {
        load: function () {

            if (!Ext.getCmp('idtipoaprob').getRawValue()) {
                Ext.getCmp('idtipoaprob').reset();
            }
            ;
            if (!Ext.getCmp('periodoList').getRawValue()) {
                Ext.getCmp('periodoList').reset();
            }
            ;


        }
    },
    proxy: {
        type: 'ajax',
        api: {
            read: 'cargarMateriasXAlumno'
            //update: 'modificarIdiomas',
        },
        actionMethods: { //Esta Linea es necesaria para el metodo de llamada POST o GET
            read: 'POST',
            update: 'POST'
        },
        reader: {
            root: 'datos',
            totalProperty: 'cantidad',
            successProperty: 'success',
            messageProperty: 'mensaje'
        }
    }
});