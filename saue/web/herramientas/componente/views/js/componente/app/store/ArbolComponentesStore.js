Ext.define('GestComponentes.store.ArbolComponentesStore', {
    extend: 'Ext.data.TreeStore',
    requires: 'GestComponentes.model.ArbolComponentesModel',
    model: 'GestComponentes.model.ArbolComponentesModel',
    root: {text: 'Componentes',
        expanded: true}


});
