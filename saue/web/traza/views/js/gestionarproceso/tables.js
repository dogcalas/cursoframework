var sttables, boolprocessdefinition = true, instancia;
;
selecttables = Ext.extend(Ext.Window, {
    title: 'Seleccionar tablas',
    width: 350,
    height: 345,
    columnResize: true,
    closable: false,
    layout: 'border',
    closeAction: 'hide',
    modal: true,
    initComponent: function() {

        /*this.smTablest = new Ext.grid.CheckboxSelectionModel({
         width: 25,
         scope: this
         });*/

        var smTablest = Ext.create('Ext.selection.CheckboxModel', {
            mode: 'SIMPLE'
        });

        smTablest.on('selectionchange', function() {
            if (smTablest.getSelection().length > 0)
                btnCreateProject.enable();
            else
                btnCreateProject.disable();
        })


        txtFilterTtable = new Ext.form.TextField({
            enableKeyEvents: true
        })

        var btnCreateProject = new Ext.Button({
            icon: perfil.dirImg + 'aplicar.png',
            text: 'Siguiente',
            disabled: true

        });


        this.btnCancel = new Ext.Button({
            icon: perfil.dirImg + 'cancelar.png',
            text: 'Cancelar'
        })

        txtFilterTtable.on('keyup', function(tf) {
            sttables.clearFilter(true);
            if (tf.getValue()) {
                sttables.filter('name', tf.getValue());
            }
        }, this)

        this.btnCancel.setHandler(function() {
            this.hide();
        }, this);

        btnCreateProject.setHandler(function() {
            createproceso(smTablest.getSelection());
        }, this);
        sttables = new Ext.data.Store({
            fields: [
                {
                    name: 'name',
                    type: 'string'
                }
            ],
            proxy: {
                type: 'ajax',
                url: 'gettables',
                actionMethods: {//Esta Linea es necesaria para el metodo de llamada POST o GET                           
                    read: 'POST'
                },
                reader: {
                    totalProperty: "cantidad_filas",
                    root: "datos",
                    id: "name"
                }
            },
            autoLoad: true,
            listeners: {
                'load': function(a, b) {
                    sttables.clearFilter(true);
                    sttables.filter('name', '');
                    txtFilterTtable.setValue('');
                }
            }
        });


        sttables.on('load', function() {
            if (actionproceso == "modificar") {
                Ext.Ajax.request({
                    url: 'getselectables',
                    method: 'POST',
                    callback: function(options, success, response) {
                        responseData = Ext.decode(response.responseText);
                        if (responseData.indextables != '-1') {
                            var indextables = responseData.indextables.split(" ");
                            var indice;
                            for (var i = 0; i < indextables.length; i++) {
                                indice = parseInt(indextables[i]);
                                smTablest.select(indice, true);
                            }

                        }
                    }

                })

            }

        })

        this.buttons = [this.btnCancel, btnCreateProject]

        this.items = [
            {
                xtype: 'grid',
                autoScroll: true,
                columnResize: true,
                sortableColumns: false,
                selModel: smTablest,
                store: Ext.data.StoreManager.lookup(sttables),
                tbar: ['->', 'Filtro: ', txtFilterTtable],
                columns: [
                    {text: 'Nombre', width: 302, dataIndex: 'name'}
                ],
                height: 302
            }



        ];


        selecttables.superclass.initComponent.call(this);

    }

});

function createproceso(iselectedtables) {

    var selectedtables;

    for (i = 0; i < iselectedtables.length; i++) {
        if (i == 0)
            selectedtables = iselectedtables[i].data.name;
        else
            selectedtables = selectedtables + "," + iselectedtables[i].data.name;
    }

    Ext.Ajax.request({
        url: 'selectedtables',
        method: 'POST',
        params: {
            selectedtables: selectedtables
        }
    })


    if (boolprocessdefinition)
        this.processdefinition = new processdefinition();
    else
        sttablesprodefinition.reload();
    boolprocessdefinition = false;
    //setnameproceso();
    this.selecttables.hide();
    this.processdefinition.show();
    if (actionproceso == "modificar") {
        Ext.getCmp('formulario').loadRecord(sm.getLastSelected());
        instancia = sm.getLastSelected().data.instancia;
        modificarprocesoname = Ext.getCmp('name').getValue();
    }


}



