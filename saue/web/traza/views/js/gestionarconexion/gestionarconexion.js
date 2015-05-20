var perfil = window.parent.UCID.portal.perfil;
perfil.etiquetas = Object();
UCID.portal.cargarEtiquetas('gestionarconexion', cargarInterfaz);

// //------------ Inicializo el singlenton QuickTips ------------////
Ext.QuickTips.init();

// //------------ Declarar Variables ------------////
var conexion = true, interface, actionconexion,thisconexion; 
var stconexion;
var sm, host, db, port;

// //------------ Area de Validaciones ------------////
var texto, num, dir;
texto = /^([a-zA-ZáéíóúñüÑ]+[a-zA-ZáéíóúñüÑ\d\.\-\_ ]*)+$/;
num = /^([a-z\d\.\-\_]*)+$/;
function cargarInterfaz() {
Ext.onReady(function(){
    // //------------ Botones ------------////

	ConfigProceso = new Ext.Button({
        disabled: false,
        id: 'CProceso',
        icon: perfil.dirImg + 'adicionar.png',
        iconCls: 'btn',
        text: perfil.etiquetas.ConfigProceso,
        handler: function() {
            Adicionar_conexion();
        }

    });


    ModifProceso = new Ext.Button({
        disabled: true,
        id: 'ModifProceso',
        icon: perfil.dirImg + 'modificar.png',
        iconCls: 'btn',
        text: perfil.etiquetas.ModifProceso,
        handler: function() {
            Modificar_conexion();
        }

    });
    ElimProceso = new Ext.Button({
        disabled: true,
        id: 'ElimProceso',
        icon: perfil.dirImg + 'eliminar.png',
        iconCls: 'btn',
        text: perfil.etiquetas.ElimProceso,
        handler: function() {
            eliminar_conexion();
        }
    });




    // //------------ Store del Grid de Temas ------------////

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
        }
    });


    // //------------ Establesco modo de seleccion de grid (single)

    sm = Ext.create('Ext.selection.RowModel', {
        mode: 'SINGLE',
        allowDeselect: false,
         enableKeyNav: false
    });




    stconexion.on('load', function() {
        if (stconexion.getCount() != 0)
        {
            ModifProceso.enable();
            ElimProceso.enable();
            sm.select(0);
        }
        else
        {
            ModifProceso.disable();
            ElimProceso.disable();
        }
    }, this)




    // //------------ Defino el grid de Temas ------------////

    var GModulo = Ext.create('Ext.grid.Panel', {
        frame: true,
        region: 'center',
        autoExpandColumn: 'expandir',
        store: stconexion,
        selModel: sm,
        columns: [
            {
                text: 'Nombre de la  conexión',
                dataIndex: 'name',
                flex: 1
            },
            {
                text: 'Base de datos',
                dataIndex: 'db',
                flex: 1

            },
            {
                text: 'Usuario',
                dataIndex: 'user',
                flex: 1
            },
            {
                text: 'Host',
                dataIndex: 'host',
                flex: 1
            }
            ,
            {
                text: 'Puerto',
                dataIndex: 'port'

            }

        ],
        loadMask: {
            store: stconexion
        },
        renderTo: Ext.getBody(),
        bbar: new Ext.toolbar.Paging({
                store: stconexion,
                displayInfo: true,
                displayMsg:" Resultados {0} - {1} de {2}",
                emptyMsg: "Ning&uacute;n resultado para mostrar."
            })
    });





    // //------------ Renderiar el arbol ------------////
    var panel = new Ext.Panel({
        layout: 'border',
        title: "Gestionar conexión",
        renderTo: 'panel',
        items: [GModulo],
        tbar: [ConfigProceso, ModifProceso, ElimProceso]

    });




    // //------------ ViewPort ------------////
    var vpTema = new Ext.Viewport({
        disable: true,
        layout: 'fit',
        items: [panel]
    })
    stconexion.load({
        params: {
            start: 0,
            limit: 15
        }
    })
            ;

    function Configurar_proceso() {
        if (crearproceso)
            this.UIwinCreateProject = new UIwinCreateProject();
        else {
            this.UIwinCreateProject.stTable.reload();
        }
        crearproceso = false;
        this.UIwinCreateProject.show();




    }
    function Adicionar_conexion() {
        actionconexion="adicionada";
        if (conexion)
            this.UIconexion1 = new UIconexion1();
        conexion = false;
        interface = "add";
        this.UIconexion1.setTitle("Adicionar conexión");
        btnAplicar.show();
        host = true;
        port = true;
        db = true;
        this.UIconexion1.show();
        //Resettext();
    }

    function Modificar_conexion() {
        actionconexion="modificada";
        if (conexion)
            this.UIconexion1 = new UIconexion1();
        conexion = false;
        interface = "mod";
        this.UIconexion1.setTitle("Modificar conexión");
        btnAplicar.hide();
        Ext.getCmp('pass').reset();
        this.UIconexion1.show();
        winconexion1.loadRecord(sm.getLastSelected());
        thisconexion=Ext.getCmp('name').getValue();

      

    }
    function eliminar_conexion() {
	 mostrarMensaje(2, "Está seguro que desea eliminar la conexión", elimina);

        function elimina(btnPresionado) {
            if (btnPresionado == 'ok')
            {
                Ext.Ajax.request({
                    url: 'delconexion',
                    method: 'POST',
                    params: {
                        id: sm.getLastSelected().data.id
                    },
                    callback: function(options, success, response) {
                        responseData = Ext.decode(response.responseText);
                        if (responseData.conexion == 1)
                        {
                            stconexion.reload();
                            Ext.MessageBox.show({
                                title: 'Eliminar conexión',
                                msg: 'Conexión eliminada satisfactoriamente.',
                                icon: Ext.MessageBox.INFO,
                                buttons: Ext.MessageBox.OK
                            })
                        }
                        else{
                            Ext.MessageBox.show({
                                title: 'Eliminar conexión',
                                msg: 'La conexión está siendo usada por un proceso.',
                                icon: Ext.MessageBox.ERROR,
                                buttons: Ext.MessageBox.OK
                            })
                        }

                    }

                })
            }
        }

    }

    function Resettext() {
        Ext.getCmp('pass').reset();
        Ext.getCmp('user').reset();
        Ext.getCmp('pass').reset();
        Ext.getCmp('host').reset();
        Ext.getCmp('name').reset();
        Ext.getCmp('db').reset();
        Ext.getCmp('port').reset();
    }



});
}



