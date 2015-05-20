DoctrineGenerator.UI.winRelations = Ext.extend(Ext.Window, {
    title: 'Relaciones',
    width: 255,
    height: 200,
    modal: true,
    closable: false,
    initComponent: function() {
        this.cbForeignTable = new Ext.form.ComboBox ({
            fieldLabel: 'Clase foranea',
            anchor: '100%',
            store: this.stClasses,
            mode : 'local',
            displayField: 'clas',
            valueField: 'table',
            triggerAction : 'all'
        })

        this.stForeignField = new Ext.data.Store ({
            url: '../mapper/loadfields',
            reader: new Ext.data.JsonReader({
                root: 'fields'
            }, ['name', 'length', 'sequence', 'pk', 'type'])
        })

        this.stType = new Ext.data.Store ({
            url: '../mapper/loadtypes',
            reader: new Ext.data.JsonReader({
                root: 'types'
            }, ['clas', 'type'])
        })
        
        this.cbType = new Ext.form.ComboBox ( {
                fieldLabel: 'Tipo',
                anchor: '100%',
                store: this.stType,
                displayField: 'type',
                valueField: 'clas',
                mode: 'remote',
                triggerAction: 'all'
        })

        this.cbForeignField = new Ext.form.ComboBox ({
            fieldLabel: 'Campo foraneo',
            anchor: '100%',
            store: this.stForeignField,
            displayField: 'name',
            valueField: 'name',
            mode : 'local',
            triggerAction : 'all'
        })

        this.cbLocalField = new Ext.form.ComboBox ({
            fieldLabel: 'Campo local',
            anchor: '100%',
            displayField: 'name',
            valueField: 'name',
            store: this.stLocalField,
            mode : 'local',
            triggerAction : 'all'
        })

        this.cbIntermediateTable = new Ext.form.ComboBox ({
            fieldLabel: 'Clase puente',
            anchor: '100%',
            store: this.stClasses,
            displayField: 'clas',
            valueField: 'table',
            mode : 'local',
            triggerAction : 'all'
        })

        this.frmRelation = new Ext.form.FormPanel ({
            height: 145,
            frame: true,
            scope: this,
            items: [
                this.cbType,
                this.cbForeignTable,
                this.cbForeignField,
                this.cbLocalField,
                this.cbIntermediateTable
            ]
        })
        
        this.items = [
            this.frmRelation
        ];
        
        this.btnCancel = new Ext.Button ({
        	text: 'Cancelar'
        })
        
        this.btnOk = new Ext.Button ({
        	text: 'Aceptar'
        })
        
        this.buttons = [this.btnCancel, this.btnOk]
        
        DoctrineGenerator.UI.winRelations.superclass.initComponent.call(this);
    }
});
