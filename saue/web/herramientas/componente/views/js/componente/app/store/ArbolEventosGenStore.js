Ext.define('GestComponentes.store.ArbolEventosGenStore', {
    extend: 'Ext.data.TreeStore',
    requires: 'GestComponentes.model.ArbolEventosGenModel',
    model: 'GestComponentes.model.ArbolEventosGenModel',
    root: {text: 'Componentes',
        expanded: true
    }


});
