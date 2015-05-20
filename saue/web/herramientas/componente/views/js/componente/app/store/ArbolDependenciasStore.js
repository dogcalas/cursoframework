Ext.define('GestComponentes.store.ArbolDependenciasStore', {
    extend: 'Ext.data.TreeStore',
    requires: 'GestComponentes.model.ArbolDependenciasModel',
    model: 'GestComponentes.model.ArbolDependenciasModel',
    root: {text: 'Componentes',
        expanded: true
    }


});
