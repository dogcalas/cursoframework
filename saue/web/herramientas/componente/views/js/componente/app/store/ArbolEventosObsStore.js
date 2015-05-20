Ext.define('GestComponentes.store.ArbolEventosObsStore', {
    extend: 'Ext.data.TreeStore',
    requires: 'GestComponentes.model.ArbolEventosObsModel',
    model: 'GestComponentes.model.ArbolEventosObsModel',
    root: {text: 'Componentes',
        expanded: true
    }


});
