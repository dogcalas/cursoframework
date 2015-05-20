Ext.define('GestComponentes.store.ComboTDServStore', {
    extend: 'Ext.data.Store',
    fields: ['TD', 'name'],
    data: [
        {"TD": "void", "name": "void"},
        {"TD": "string", "name": "string"},
        {"TD": "integer", "name": "int"},
        {"TD": "arreglo", "name": "[]"},
        {"TD": "float", "name": "float"},
        {"TD": "object", "name": "object"},
        {"TD": "bool", "name": "bool"}

    ]


});
