Ext.define('MatEst.view.matx.MateriaxEstToolBar', {
    extend: 'Ext.toolbar.Toolbar',
    alias: 'widget.tbmateriaxest',
    store: 'MatEst.store.Periodos',
    width: '100%',
    border: 1,
    initComponent: function () {
        var me = this;

        this.items = [
            {
                xtype: 'fieldset',
                border: 0,
                items: [
                    {
                        xtype: 'combobox',
                        id: 'anno',
                        fieldLabel: 'Año',
                        labelAlign: 'top',
                        queryMode: 'local',
                        valueField: 'anno',
                        displayField: 'anno',
                        editable: false,
                        labelWidth: 55,
                        store: 'MatEst.store.Annos',
                        width: 90,
                        disabled: true
                    },
                    {
                        xtype: 'combobox',
                        store: 'MatEst.store.Fac',
                        labelAlign: 'top',
                        emptyText: 'Fac./Car./Itin.',//Etiqueta
                        name: 'idfacfiltro',
                        id: 'idfacfiltro',
                        fieldLabel: 'Fac/Car/Iti',
                        //autoload: true,
                        //autoSelect: true,
                        valueField: 'valor',
                        displayField: 'accion',
                        forceSelection: true,
                        queryMode: 'local',
                        editable: false,
                        width: 90,
                        labelWidth: 55,
                        disabled: true
                    }
                ]
            },
            {
                xtype: 'fieldset',
                border: 0,
                items: [
                    {
                        xtype: 'combobox',
                        id: 'periodo',
                        forceSelection: true,
                        autoSelect: true,
                        labelAlign: 'top',
                        emptyText: '--seleccione--',
                        fieldLabel: 'Período',
                        queryMode: 'local',
                        valueField: 'idperiododocente',
                        displayField: 'descripcion',
                        allowBlank: false,
                        editable: false,
                        labelWidth: 55,
                        store: me.store,
                        width: 350,
                        disabled: true
                    },
                    {
                        xtype: 'searchcombofield',
                        store: 'MatEst.store.Horarios',
                        emptyText: 'Filtrar por horario',//Etiqueta
                        name: 'idhorariofiltro',
                        fieldLabel: 'Horarios',
                        id: 'idhorariofiltro',
                        labelAlign: 'top',
                        valueField: 'idhorario',
                        displayField: 'horario_descripcion',
                        storeToFilter: 'MatEst.store.Cursos',
                        filterPropertysNames: ['idhorario'],
                        queryMode: 'local',
                        editable: false,
                        labelWidth: 55,
                        width: 350,
                        disabled: true
                    }
                ]
            }
        ];

        this.callParent(arguments);
    }
});