var incrementalcondicones = 1, cambios = false;

Ext.define('condiciones', {
    extend: 'Ext.data.Model',
    fields: [
        // the 'name' below matches the tag name to read, except 'availDate'
        // which is mapped to the tag 'availability'
        {
            name: 'columna',
            type: 'string'
        }, {
            name: 'comparador',
            type: 'string'
        }, {
            name: 'valorocolumna',
            type: 'string'
        }, {
            name: 'operador',
            type: 'string'
        }]
});

var stcondiciones = Ext.create('Ext.data.Store', {
    // storeId : 'condiciones',
    model: 'condiciones',
    // fields : [ 'colunna', 'comparador', 'valorocolunna' ],
    proxy: {
        type: 'ajax',
        url: 'getcondiciones',
        actionMethods: {// Esta Linea es necesaria para el metodo de llamada
            // POST o GET
            read: 'POST'

        },
        reader: {
            totalProperty: "cantidad_filas",
            root: "datos",
            id: "columna"
        }
    }
});

var cellEditing = Ext.create('Ext.grid.plugin.CellEditing', {
    clicksToEdit: 1

});

var condiciones = Ext.create('Ext.grid.Panel', {
    width: '100%',
    region: 'center',
    sortableColumns: false,
    autoExpandColumn: 'expandir',
    store: stcondiciones,
    columns: [
        {
            
            header: 'Operador',
            dataIndex: 'operador',
            width: 65,
            hideable: false
        },
        {
            header: 'Seleccione columna',
            dataIndex: 'columna',
            width: '100%',
            editor: new Ext.form.field.ComboBox({
                id: 'columna1',
                typeAhead: true,
                selectOnTab: true,
                store: stalltable,
                displayField: 'name',
                valueField: 'name',
                forceSelection: true,
                editable: false,
                listeners: {
                    'blur': function(a, b, c) {
                        if (a.isDirty())
                            cambios = a.isDirty();
                    },
                    'focus': function(a, b, c) {
                        a.resetOriginalValue();
                    }
                }
            }),
            flex: 3

        },
        {
            header: 'Signo de comparación',
            dataIndex: 'comparador',
            width: '30%',
            editor: new Ext.form.field.ComboBox({
                typeAhead: true,
                selectOnTab: true,
                store: [['>', '>'], ['<', '<'], ['=', '='],
                    ['<=', '<='], ['>=', '>='], ['!=', '!=']],
                forceSelection: true,
                listeners: {
                    'blur': function(a, b, c) {
                        if (a.isDirty())
                            cambios = a.isDirty();
                    },
                    'focus': function(a, b, c) {
                        a.resetOriginalValue();
                    }
                }

            }),
            flex: 1

        }, {
            header: 'Seleccione columna o inserte valor entre \' \'',
            dataIndex: 'valorocolumna',
            width: '100%',
            editor: new Ext.form.field.ComboBox({
                typeAhead: true,
                width: '35%',
                selectOnTab: true,
                store: stalltable,
                displayField: 'name',
                valueField: 'name',
                listeners: {
                    'blur': function(a, b, c) {
                        if (a.isDirty())
                            cambios = a.isDirty();
                    },
                    'focus': function(a, b, c) {
                        a.resetOriginalValue();
                    }
                }
            }),
            flex: 3
        }, {
            xtype: 'actioncolumn',
            width: 30,
            sortable: false,
            items: [{
                    icon: '../../views/images/delete.gif',
                    tooltip: 'Eliminar Condicion ',
                    handler: function(grid, rowIndex, colIndex) {
                        if (rowIndex == 0) {
                                Ext.MessageBox.show({
                                    title: 'Condición',
                                    msg: "La primera condición no se puede eliminar.",
                                    icon: Ext.MessageBox.ERROR,
                                    buttons: Ext.MessageBox.OK
                                });
                        }else{

                            cambios = true;
                            stcondiciones.removeAt(rowIndex);
                            message("Condición", "Condición eliminada.");
                        }

                    }
                }]
        }],
    tbar: [{
            text: 'AND',
            icon: '../../views/images/annadir.png',
            width: 60,
            handler: function() {
                // Create a model instance
                var r = Ext.create('condiciones', {
                    columna: '',
                    comparador: '',
                    valorocolumna: '',
                    operador: 'AND'
                });
                var count = stcondiciones.getCount();
                stcondiciones.insert(count, r);
                cellEditing.startEditByPosition({
                    row: count,
                    column: 0
                });
            }
        }, {
            text: 'OR',
            icon: '../../views/images/annadir.png',
            width: 60,
            handler: function() {
                // Create a model instance
                var r = Ext.create('condiciones', {
                    columna: '',
                    comparador: '',
                    valorocolumna: '',
                    operador: 'OR'
                });
                var count = stcondiciones.getCount();
                stcondiciones.insert(count, r);
                cellEditing.startEditByPosition({
                    row: count,
                    column: 0
                });
            }
        }],
    plugins: [cellEditing]
});

var panelabajo = Ext.create('Ext.form.Panel', {
    title: 'Condiciones de evento',
    region: 'south',
    split: true,
    height: '45%',
    collapsible: true,
    collapsed: false,
    layout: 'border'
});
panelabajo.add(condiciones);
