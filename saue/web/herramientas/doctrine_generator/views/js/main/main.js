var qgenera=false;
DoctrineGenerator.Main = Ext.extend (DoctrineGenerator.UI.Main, {
    initComponent: function () {
        DoctrineGenerator.Main.superclass.initComponent.call (this)

        this.btnNewProject.setHandler (function () {
              qgenera=false;
            this.winCreateProject.show ();
        }, this);

        this.btnSaveProject.setHandler (function () {
           window.open ('download');
        }, this)

        this.btnOpenProject.setHandler (function () {
            this.winOpenProject.show ()
        }, this)
        
        this.btnQGen.setHandler (function () {
            qgenera=true;
            this.winCreateProject.show ();
              //this.winQGenerator.show ()
        }, this)
    
    }
})

new DoctrineGenerator.Main ();