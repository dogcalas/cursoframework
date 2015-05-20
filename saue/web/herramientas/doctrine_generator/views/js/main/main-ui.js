DoctrineGenerator.UI.Main = Ext.extend(Ext.Viewport, {
    layout: 'border',
    initComponent: function() {
        this.winCreateProject = new DoctrineGenerator.winCreateProject()
        this.winOpenProject = new DoctrineGenerator.winOpenProject()
        this.winMapper = new DoctrineGenerator.winMapper()

        this.btnNewProject = new Ext.menu.Item({
            text: 'Nuevo'
        })

        this.btnOpenProject = new Ext.menu.Item({
            text: 'Abrir'
        })

        this.btnSaveProject = new Ext.menu.Item({
            text: 'Guardar'
        })
        this.btnQGen = new Ext.menu.Item({
            text: 'Consultas'
        })
    

        this.items = [
            {
                xtype: 'panel',
                title: 'Generador de ficheros de Doctrine',
                region: 'center',
                frame: true,
                html: '<center><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><img src = "../../views/img/main.gif"/></center>',
                tbar: [
                    {
                        xtype: 'button',
                        text: 'Proyecto',
                        menu: {
                            xtype: 'menu',
                            items: [
                                this.btnNewProject,
                                this.btnOpenProject,
                                this.btnQGen,
                                this.btnSaveProject,
                            ]
                        }
                    }
                ]
            }
        ];
        DoctrineGenerator.UI.Main.superclass.initComponent.call(this);
    }
});