var validate = false, salva, idaction = 1, position, tip, contadoratribtos = 0, presskey = false;
var actions = Ext.create('Ext.data.Store', {
    fields: ['action'],
    data: [{
            "action": "insert"
        }, {
            "action": "update"
        }, {
            "action": "delete"
        }, {
            "action": "no action"
        }
        // ...
    ]
});
var panelcentro = Ext.create('Ext.form.Panel', {
    title: 'Atributos de evento',
    region: 'center',
    animCollapse: true,
    autoExpandColumn: 'expandir',
    layout: 'border'
});
Ext.define('atributosmodel', {
    extend: 'Ext.data.Model',
    fields: [
        // the 'name' below matches the tag name to read, except 'availDate'
        // which is mapped to the tag 'availability'
        {
            name: 'id',
            type: 'string'
        }, {
            name: 'name',
            type: 'string'
        }, {
            name: 'tablev',
            type: 'string'
        }, {
            name: 'action',
            type: 'string'
        }]
});
var statributes = Ext.create('Ext.data.Store', {
    //fields: ['id', 'name', 'tablev', 'action'],
    model: 'atributosmodel',
    proxy: {
        type: 'ajax',
        url: 'getcorrelacion',
        actionMethods: {// Esta Linea es necesaria para el metodo de llamada
            // POST o GET
            read: 'POST'

        },
        reader: {
            totalProperty: "cantidad_filas",
            root: "datos",
            id: 'id'
        }
    },
    listeners: {
        'load': function(a, b) {
            stpropiedades.reload();

        }
    }

});

var stalltable = Ext.create('Ext.data.Store', {
    fields: ['name'],
    proxy: {
        type: 'ajax',
        url: 'getalltable',
        actionMethods: {// Esta Linea es necesaria para el metodo de llamada
            // POST o GET
            read: 'POST'

        },
        reader: {
            totalProperty: "cantidad_filas",
            root: "datos",
            id: "name"
        }
    }
});
var cellEditing = Ext.create('Ext.grid.plugin.CellEditing', {
    clicksToEdit: 1
});

var tip = Ext.create('Ext.tip.ToolTip', {
    target: 'combobox-1010',
    dismissDelay: 1000,
    html: 'Vacio por defecto'
});

var atributos = Ext.create('Ext.grid.Panel', {
    width: '100%',
    flex: 1,
    region: 'center',
    sortableColumns: false,
    autoExpandColumn: 'expandir',
    store: statributes,
    columns: [{
            flex: 2,
            text: 'Atributos',
            width: '35%',
            dataIndex: 'name',
            hideable: false,
            style: {
                width: '88%',
                marginBottom: '10px'
            },
        }, {
            
            header: 'Columnas',
            flex: 3,
            renderer: function(value, metaData, record, rowIdx, colIdx, store, view) {
                contadoratribtos++;
                if (contadoratribtos > 7)
                    if (presskey) {
                        record.set('action', 'no action');
                        presskey = false;
                    }
                return value;
            },
            dataIndex: 'tablev',
            width: '100%',
            editor: new Ext.form.field.ComboBox({
                typeAhead: true,
                enableKeyEvents: true,
                triggerAction: 'all',
                selectOnTab: true,
                store: stalltable,
                displayField: 'name',
                valueField: 'name',
                listeners: {
                    'change': function(a, b, c) {
                        if (a.isDirty())
                            cambios = a.isDirty();
                    },
                    'focus': function(a, b, c) {
                        var pocision = atributos.getSelectionModel().getCurrentPosition().row;

                        if (pocision > 0 && pocision <= 6) {
                            position = a.getPosition();
                            position[0] = position[0] + 10;
                            position[1] = position[1] - 29;
                            tip.showAt(position);
                        }
                        a.resetOriginalValue();
                    },
                    'keyup': function(a, b, c) {
                        presskey = true;
                    },
                    'select': function(a, b, c) {
                        presskey = false;
                    }
                }

            })

        }, {
            header: 'Acción',
            flex: 1,
            dataIndex: 'action',
            width: '100%',
            editor: new Ext.form.field.ComboBox({
                typeAhead: true,
                enableKeyEvents: true,
                id: 'actions',
                width: '35%',
                store: actions,
                displayField: 'action',
                valueField: 'action',
                forceSelection: true,
                allowBlank: false,
                blankText: 'Este campo es obligatorio.',
                listeners: {
                    'blur': function(a, b, c) {
                        if (a.isDirty())
                            cambios = a.isDirty();
                    },
                    'focus': function(a, b, c) {
                        a.resetOriginalValue();
                    }
                }
            })

        }],
    
    plugins: [cellEditing]
});
