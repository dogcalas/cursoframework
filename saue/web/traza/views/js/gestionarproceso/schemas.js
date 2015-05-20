var stschemas, tables = true;
selectschemas = Ext.extend(Ext.Window, {
    title: 'Seleccionar esquema',
    width: 290,
    height: 280,
    closable: false,
    layout: 'border',
    closeAction: 'hide',
    modal: true,
    initComponent: function() {

        /*this.smschemas = new Ext.grid.CheckboxSelectionModel({
         width: 25,
         scope: this
         });*/

        var smschemas = Ext.create('Ext.selection.CheckboxModel', {
            mode: 'SIMPLE'
        });

        smschemas.on('selectionchange', function() {
            if (smschemas.getSelection().length > 0)
                btnCreateProject.enable();
            else
                btnCreateProject.disable();
        })


        txtFilterTschemas = new Ext.form.TextField({
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

        txtFilterTschemas.on('keyup', function(tf) {
            stschemas.clearFilter(true);
            if (tf.getValue()) {
                stschemas.filter('name', tf.getValue());

            }

        }, this)

        this.btnCancel.setHandler(function() {
            this.hide();
        }, this);

        btnCreateProject.setHandler(function() {
            gettables(smschemas.getSelection());
        }, this);
        stschemas = new Ext.data.Store({
            fields: [
                {
                    name: 'name',
                    type: 'string'
                }
            ],
            proxy: {
                type: 'ajax',
                url: 'getschemas',
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
                    stschemas.clearFilter(true);
                    stschemas.filter('name', '');
                    txtFilterTschemas.setValue('');
                }
            }
        });

        stschemas.on('load', function() {
            if (actionproceso == "modificar" && stschemas.getTotalCount() > 0) {

                Ext.Ajax.request({
                    url: 'getselecschemas',
                    method: 'POST',
                    callback: function(options, success, response) {
                        responseData = Ext.decode(response.responseText);
                        if (responseData.indexschemas != '-1') {
                            var indexschemas = responseData.indexschemas.split(" ");
                            var indice;
                            for (var i = 0; i < indexschemas.length; i++) {
                                indice = parseInt(indexschemas[i]);
                                smschemas.select(indice, true);
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
                columnResize: false,
                sortableColumns: false,
                selModel: smschemas,
                store: Ext.data.StoreManager.lookup(stschemas),
                tbar: ['->', 'Filtro: ', txtFilterTschemas],
                columns: [
                    {text: 'Nombre', width: 250, dataIndex: 'name'}
                ],
                height: 250
            }



        ];


        selectschemas.superclass.initComponent.call(this);

    }

});

function gettables(selectedschemas) {

    var schemas;

    for (i = 0; i < selectedschemas.length; i++) {
        if (i == 0)
            schemas = selectedschemas[i].data.name;
        else
            schemas = schemas + "," + selectedschemas[i].data.name;
    }
    Ext.Ajax.request({
        url: 'crearschemas',
        method: 'POST',
        params: {
            schemas: schemas
        }
    });


    if (tables)
        this.selecttables = new selecttables();
    else
        sttables.reload();

    tables = false;
    //setnameproceso();
    this.selectschemas.hide();
    this.selecttables.show();
}



