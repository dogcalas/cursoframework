var schemas = true, smconexion;
selectconexion = Ext.extend(Ext.Window, {
    title: 'Seleccionar conexión',
    width: 360,
    height: 300,
    closable: false,
    layout: 'border',
    closeAction: 'hide',
    modal: true,
    initComponent: function() {


        smconexion = Ext.create('Ext.selection.CheckboxModel', {
            mode: 'SINGLE',
            showHeaderCheckbox: false
        });

        smconexion.on('selectionchange', function() {
            
            if (smconexion.getSelection().length > 0)
                btnCreateProject.enable();
            else
                btnCreateProject.disable();

        })

        txtFilterTconexion = new Ext.form.TextField({
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
        });

        txtFilterTconexion.on('keyup', function(tf) {
            stconexion.clearFilter(true);
            if (tf.getValue()) {
                stconexion.filter('name', tf.getValue());
            }
        }, this);

        this.btnCancel.setHandler(function() {
            this.hide();
        }, this);

        btnCreateProject.setHandler(function() {
            getschemas(smconexion.getSelection());
        }, this);
        stconexion = new Ext.data.Store({
            fields: [
                {
                    name: 'id',
                    type: 'string'
                },
                {
                    name: 'name',
                    type: 'string'
                },
                {
                    name: 'user',
                    type: 'string'
                },
                {
                    name: 'host',
                    type: 'string'
                },
                {
                    name: 'db',
                    type: 'string'
                },
                {
                    name: 'port',
                    type: 'string'
                },
                {
                    name: 'pass',
                    type: 'string'
                }
            ],
            proxy: {
                type: 'ajax',
                url: 'getconexions',
                actionMethods: {//Esta Linea es necesaria para el metodo de llamada POST o GET                           
                    read: 'POST'
                },
                reader: {
                    totalProperty: "cantidad_filas",
                    root: "datos",
                    id: "id"
                }
            },
            autoLoad: true,
            listeners: {
        'load': function(a, b) {
            stconexion.clearFilter(true);
            stconexion.filter('name', '');
            txtFilterTconexion.setValue('');
        }
    }
        });

        stconexion.on('load', function() {
            if (actionproceso == "modificar") {
                // stconexion.clearFilter(true);
                Ext.Ajax.request({
                    url: 'getselecconexion',
                    method: 'POST',
                    callback: function(options, success, response) {
                        responseData = Ext.decode(response.responseText);
                        if (responseData.idconexion != -1)
                        {
                            smconexion.select(responseData.idconexion);
                        }

                    }

                })

            }

        })

        this.buttons = [this.btnCancel, btnCreateProject]

        this.items = [
            {
                xtype: 'grid',
                sortableColumns: false,
                columnResize: false,
                title: '',
                selModel: smconexion,
                store: Ext.data.StoreManager.lookup(stconexion),
                tbar: ['->', 'Filtro: ', txtFilterTconexion],
                columns: [
                    {text: 'Nombre', width: 170, dataIndex: 'name'},
                    {text: 'Usuario', width: 170, dataIndex: 'user'}
                ],
                height: 350,
                width: 350
            }



        ];

        selectconexion.superclass.initComponent.call(this);


    }

});






function getschemas(conexion) {

    Ext.Ajax.request({
        url: 'crearconexion',
        method: 'POST',
        params: {
            id: conexion[0].data.id,
            host: conexion[0].data.host,
            port: conexion[0].data.port,
            user: conexion[0].data.user,
            pass: conexion[0].data.pass,
            bd: conexion[0].data.db
        }
    });

    if (schemas)
        this.selectschemas = new selectschemas();
    else
        stschemas.reload();

    schemas = false;
    this.selectconexion.hide();
    this.selectschemas.show();

}


