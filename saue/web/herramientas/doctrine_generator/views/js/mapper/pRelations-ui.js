DoctrineGenerator.UI.pRelations = Ext.extend(Ext.Panel, {
    title: 'Relaciones',
    width: 453,
    height: 258,
    scope: this,
    layout: 'border',
    initComponent: function() {
        this.btnAddRelation = new Ext.Button ({
            icon: '../../views/img/adicionar.png',
            iconCls:'btn'
        })
                
        this.btnRemRelation = new Ext.Button ({
            icon: '../../views/img/eliminar.png',
            iconCls:'btn'
        })

        this.stRelations = new Ext.data.Store ({
            reader: new Ext.data.JsonReader ({
                root: 'relations'
            }, ['type', 'ft', 'lf', 'ff', 'it'])
        })

        this.chkInherits = new Ext.form.Checkbox ({
            fieldLabel: 'Hereda',
            anchor: '90%'
        })

        this.cbForeignTable = new Ext.form.ComboBox ({
            store: this.stClasses,
            mode : 'local',
            triggerAction : 'all',
            valueField: 'clas',
            displayField: 'clas'
        })

        this.cbLocalField = new Ext.form.ComboBox ({
            store: this.stFields,
            valueField: 'name',
            displayField: 'name',
            mode : 'local',
            triggerAction : 'all'
        })

        this.stForeignField = new Ext.data.Store ({
            url: '../mapper/load_fields',
            reader: new Ext.data.JsonReader({
                root: 'fields'
            }, ['name', 'length', 'sequence', 'pk', 'type'])
        })

        this.cbClasses = new Ext.form.ComboBox ({
            store: this.stClasses,
            fieldLabel: 'Clase padre',
            mode: 'local',
            displayField: 'clas',
            valueField: 'table',
            anchor: '95%',
            disabled: true,
            triggerAction: 'all'
        })
        
        this.winRelations = new DoctrineGenerator.winRelations ({
        	stClasses: this.stClasses,
        	stLocalField: this.stFields,
                scope: this
        })

        this.egRelations = new Ext.grid.EditorGridPanel ({
            title: '',
            store: this.stRelations,
            tbar: [this.btnAddRelation, this.btnRemRelation],
            region: 'center',
            sm: new Ext.grid.RowSelectionModel ({
                singleSelect: true
            }),
            columns: [
                {
                    dataIndex: 'type',
                    header: 'Tipo',
                    sortable: true,
                    width: 95
                },
                {
                    dataIndex: 'ft',
                    header: 'Tabla foranea',
                    editor: this.cbForeignTable,
                    sortable: true,
                    width: 100
                },
                {
                    dataIndex: 'ff',
                    header: 'Columna foranea',
                    sortable: true,
                    width: 90
                },
                {
                    dataIndex: 'lf',
                    header: 'Columna local',
                    sortable: true,
                    width: 80
                },
                {
                    header: 'Tabla puente',
                    sortable: true,
                    width: 100,
                    dataIndex: 'it'
                }
            ]
        })

        this.items = [
            {
                xtype: 'panel',
                title: '',
                region: 'north',
                height: 40,
                layout: 'column',
                defaults: {
                    columnWidth: .5,
                    frame: true
                },
                items: [
                    {
                        xtype: 'form',
                        title: '',
                        height: 50,
                        items: [
                            this.chkInherits
                        ]
                    },
                    {
                        xtype: 'form',
                        title: '',
                        height: 50,
                        width: 241,
                        items: [
                            this.cbClasses
                        ]
                    }
                ]
            },
            this.egRelations
        ];
        DoctrineGenerator.UI.pRelations.superclass.initComponent.call(this);
    }
});
