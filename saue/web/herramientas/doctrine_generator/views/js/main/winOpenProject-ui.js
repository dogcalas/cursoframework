DoctrineGenerator.UI.winOpenProject = Ext.extend(Ext.Window, {
    title: 'Abrir',
    width: 235,
    height: 105,
    modal: true,
    closable: false,
    initComponent: function() {
        this.btnOpen = new Ext.Button ({
            text: 'Abrir'
        })

        this.btnCancel = new Ext.Button ({
            text: 'Cancelar'
        })
        
        this.buttons = [this.btnCancel, this.btnOpen]
    	
    	this.items = [
            {
                xtype: 'form',
                title: '',
                scope: this,
                fileUpload: true,
                id: 'fpUpload',
                frame: true,
                items: [
                    {
                        xtype: 'fileuploadfield',
                        fieldLabel: 'Archivo',
                        buttonText: '...',
                        anchor: '100%',
                        id: 'name'
                    }
                ]
            }
        ];
        DoctrineGenerator.UI.winOpenProject.superclass.initComponent.call(this);
    }
});
