Ext.ns('Normalizar.UI');
Normalizar.UI.winProgress = Ext.extend(Ext.Window, {
    height: 52,
    width: 400,
    closable: false,
    title: perfil.etiquetas.lbMsgEntrenando,
    modal: true,
    initComponent: function() {
    	this.pb = new Ext.ProgressBar ({
            id: 'pb',
            value: 0
        })

        this.items = [
            {
                xtype: 'form',
                title: '',
                items: [
                    this.pb
                ]
            }
        ];
        Normalizar.UI.winProgress.superclass.initComponent.call(this);
    }
});