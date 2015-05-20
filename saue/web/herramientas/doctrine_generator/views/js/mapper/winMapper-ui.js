DoctrineGenerator.UI.winMapper = Ext.extend(Ext.Window, {
    title: 'Mapeo',
    width: 500,
    id: 'winMapper',
    height: 325,
    closable: false,
    modal: true,
    checkRenderer: function(pValue) {
        if (pValue == 1)
            return "<center><img src = '../../views/img/checked.gif'/> </center>";
        else
            return "<center><img src = '../../views/img/unchecked.gif'/></center>";
    },
    initComponent: function() {
        this.winQGenerator = new DoctrineGenerator.winQGenerator()
        this.stFields = new Ext.data.Store({
            reader: new Ext.data.JsonReader({
                root: 'fields'
            }, ['name', 'length', 'sequence', 'pk', 'type'])
        })
        

        this.stRelations = new Ext.data.Store({
            reader: new Ext.data.JsonReader({
                root: 'relations'
            }, ['type', 'ft', 'ff', 'lf', 'it'])
        })

        this.stClasses = new Ext.data.Store({
            id: 'stClasses',
            url: '../mapper/load_classes',
            reader: new Ext.data.JsonReader({
                root: 'data'
            }, ['clas', 'table'])
        })


    

        this.cbClasses = new Ext.form.ComboBox({
            store: this.stClasses,
            fieldLabel: 'Clase',
            displayField: 'clas',
            valueField: 'table',
            triggerAction: 'all',
            width: 206
        })

        this.btnMap = new Ext.Button({
            text: 'Mapear'
        })

        this.pRelations = new DoctrineGenerator.pRelations({
            stClasses: this.stClasses,
            stFields: this.stFields,
            stRelations: this.stRelations
        })
     
        this.chkns = new Ext.form.Checkbox({
            fieldLabel: 'Editar Namespace',
            anchor: '90%'
        })


        this.btnCancel = new Ext.Button({
            text: 'Cancelar'
        })
        this.btnQGenO = new Ext.Button({
            text: 'Generador de Consultas'

        })

  
      
       
        /*
         * botones de la ventana general
         */
        this.buttons = [this.btnCancel, this.btnQGenO, this.btnMap]

       
        this.items = [
            {
                xtype: 'panel',
                title: '',
                layout: 'form',
                scope: this,
                frame: true,
                height: 400,
                width: 1000,
                items: [
                    this.cbClasses,
                    {
                        xtype: 'tabpanel',
                        activeTab: 1,
                        scope: this,
                        id: 'tp',
                        title: 'Relaciones',
                        height: 262,
                        items: [
                            {
                                xtype: 'grid',
                                title: 'Campos',
                                store: this.stFields,
                                id: 'mygryd',
                                height: 234,
                                columns: [
                                    {
                                        dataIndex: 'name',
                                        header: 'Campo',
                                        sortable: true,
                                        width: 150
                                    },
                                    {
                                        dataIndex: 'type',
                                        header: 'Tipo',
                                        sortable: true,
                                        width: 100
                                    },
                                    {
                                        dataIndex: 'pk',
                                        header: 'Llave primaria',
                                        sortable: true,
                                        renderer: this.checkRenderer,
                                        width: 100
                                    },
                                    {
                                        dataIndex: 'sequence',
                                        header: 'Secuencia',
                                        sortable: true,
                                        width: 100
                                    }
                                ]
                            },
                            this.pRelations
                        ]
                    },
                ]
            }
        ];
        ////-----Valorde los metodos-----///////////


        this.stgetall2 = ' \npublic function getAll($_em){\n\n\
$result=$_em->createQuery("SELECT t FROM {$namespace} t;")\n\
            ->getResult();\n\
\n\
return $result;\n\
} \n\ ';

        this.stfind2 = ' \npublic function find($id,$_em){\n\n\
$result=$_em->createQuery("SELECT t FROM {$namespace} t WHERE \n\
              t.{$idname}=?1;")\n\
            ->setParameter(1,$id)\n\
            ->getResult();\n\
\n\
return $result;\n\
} \n\ ';

        this.stgetllave2 = ' \npublic function getLlave($_em){\n\n\
$result=$_em->createQuery("SELECT t.{$idname} FROM {$namespace} t WHERE \n\
              t.{$idname}=?1;")\n\
            ->getResult();\n\
\n\
return $result;\n\
}   \n\ ';
        DoctrineGenerator.UI.winMapper.superclass.initComponent.call(this);
    },
    showQgenerator: function() {
        this.w = new Ext.Window({
            layout: 'fit',
            frame: true,
            height: 500,
            width: 900,
            minWidth: 900,
            maxWidth: 1000,
            maximizable: true,
            minimizable: true,
            title: 'Generador de Consultas',
            items: [this.panelgeneral]
        });
        this.w.show();
    }

});
