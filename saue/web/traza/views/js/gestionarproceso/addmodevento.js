var addmodevent = new Ext.grid.GridPanel({
    frame: true,
    region: 'center',
    autoExpandColumn: 'expandir',
    columns: [
        {
            id: 'Neventos',
            header: "nombre de eventos",
            width: 110
        },
        {
            id: 'edescripcion',
            header: "Descripción",
            width: 100
        }

    ]
});

winaddmodevent = Ext.extend(Ext.Window, {
    modal: true,
    closeAction: 'hide',
    layout: 'fit',
    resizable: false,
    title: "Crear evento del proceso ",
    width: 390,
    height: 180,
    initComponent: function() {


        this.AddEvento = new Ext.Button({
            text: 'Agregar evento'
        })


        this.AddEvento.setHandler(function() {
            crearvevento();
        }, this)


        this.btnCreateProject = new Ext.Button({
            text: 'Siguiente',
            disabled: true

        })


        this.btnCancel = new Ext.Button({
            text: 'Cancelar'
        })



        this.btnCancel.setHandler(function() {
            this.hide();
        }, this)

        this.btnCreateProject.setHandler(function() {

        }, this)

        this.buttons = [this.btnCancel, this.btnCreateProject];

        items: [addmodevent]
        buttons: [
                {
                    icon: perfil.dirImg + 'cancelar.png',
                    iconCls: 'btn',
                    text: perfil.etiquetas.lbBtnCancelar,
                    handler: function() {
                        vevento.hide();
                        Ext.getCmp('newevento').setValue("");
                        Ext.getCmp('descipcion').setValue("");
                    }
                },
        {
            icon: perfil.dirImg + 'aplicar.png',
            iconCls: 'btn',
            text: perfil.etiquetas.lbBtnAplicar,
            handler: function() {
                Ext.getCmp('newevento').setValue("");
                Ext.getCmp('descipcion').reset();
                adicionarBundle('apl');
            }
        },
        {
            icon: perfil.dirImg + 'aceptar.png',
            iconCls: 'btn',
            text: perfil.etiquetas.lbBtnAceptar,
            handler: function() {
                Ext.getCmp('newevento').setValue("");
                Ext.getCmp('descipcion').setValue("");
                adicionarBundle();
            }
        }]




        winaddmodevent.superclass.initComponent.call(this);

    }

});

function crearvevento() {
    if (estaevento) {
        vevento = new Ext.Window({
            modal: true,
            closeAction: 'hide',
            layout: 'fit',
            resizable: false,
            title: "Crear evento del proceso " + Ext.getCmp('Nproceso').getValue(),
            width: 390,
            height: 180,
            items: [
                {
                    xtype: 'form',
                    title: '',
                    frame: true,
                    region: 'center',
                    items: [
                        {
                            xtype: 'textfield',
                            fieldLabel: 'Crear evento',
                            value: 'newevento',
                            width: 240,
                            id: 'newevento',
                            allowBlank: false,
                            blankText: 'Debe ingresar el nombre del evento.'
                        },
                        {
                            xtype: 'textarea',
                            fieldLabel: 'Descipcion',
                            value: 'Descripción',
                            width: 240,
                            allowBlank: false,
                            blankText: 'Debe ingresar la descripción.',
                            id: 'descipcion'
                        }
                    ]

                }],
            buttons: [
                {
                    icon: perfil.dirImg + 'cancelar.png',
                    iconCls: 'btn',
                    text: perfil.etiquetas.lbBtnCancelar,
                    handler: function() {
                        vevento.hide();
                        Ext.getCmp('newevento').setValue("");
                        Ext.getCmp('descipcion').setValue("");
                    }
                },
                {
                    icon: perfil.dirImg + 'aplicar.png',
                    iconCls: 'btn',
                    text: perfil.etiquetas.lbBtnAplicar,
                    handler: function() {
                        Ext.getCmp('newevento').setValue("");
                        Ext.getCmp('descipcion').reset();
                        adicionarBundle('apl');
                    }
                },
                {
                    icon: perfil.dirImg + 'aceptar.png',
                    iconCls: 'btn',
                    text: perfil.etiquetas.lbBtnAceptar,
                    handler: function() {
                        Ext.getCmp('newevento').setValue("");
                        Ext.getCmp('descipcion').setValue("");
                        adicionarBundle();
                    }
                }]





        });
        //this.UIwinCreateTree.hide();

    }
    estaevento = false;
    vevento.show();
}



