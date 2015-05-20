Ext.define('GestConv.view.EnfasisFilter', {
    extend: 'Ext.container.Container',

    alias: 'widget.enfasisfilter',
    defaultType: 'combobox',
    layout: 'fit',
    padding: '5 0 0 0',
    defaults: { // defaults are applied to items, not the container
        padding: '0 5 10 0'
    },
    items: [
        {
            fieldLabel: 'Facultad',//Etiqueta
            editable: false,
            disabled: true,
            store: Ext.create('GestMatxPensum.store.Facultades'),
            queryMode: 'local',
            name: 'idfacultad',
            id: 'facultad_combo',
            displayField: 'denominacion',
            labelAlign: 'top',
            valueField: 'idfacultad',
            allowBlank: false
        },
        {
            fieldLabel: 'Carrera',//Etiqueta
            editable: false,
            disabled: true,
            store: Ext.create('GestMatxPensum.store.Carreras'),
            queryMode: 'local',
            id: 'idMatxPensumCarrerasCombo',
            labelAlign: 'top',
            name: 'idcarrera',
            displayField: 'descripcion',
            valueField: 'idcarrera',
            allowBlank: false
        },
        {
            fieldLabel: 'Itinerario',//Etiqueta
            editable: false,
            disabled: true,
            store: Ext.create('GestMatxPensum.store.Enfasis'),
            queryMode: 'local',
            name: 'idenfasis',
            id: 'idMatxPensumEnfasisCombo',
            labelAlign: 'top',
            displayField: 'descripcion',
            valueField: 'idenfasis',
            allowBlank: false
        },
        {
            fieldLabel: 'Pensum',//Etiqueta
            editable: false,
            disabled: true,
            store: Ext.create('GestMatxPensum.store.Pensums'),
            queryMode: 'local',
            name: 'idpensum',
            id: 'idpensum',
            labelAlign: 'top',
            displayField: 'descripcion',
            valueField: 'idpensum',
            allowBlank: false
        },
        {
            fieldLabel: 'Universidad',
            id: 'universidadCombo',
            margin: '5 5 0 0',
            labelAlign: 'top',
            labelStyle: 'padding: 0 0 5 0',
            editable: false,
            store: Ext.create('GestConv.store.Universidades'),
            queryMode: 'local',
            name: 'iduniversidad',
            displayField: 'descripcion',
            valueField: 'iduniversidad',
            allowBlank: false
        }
    ]
});