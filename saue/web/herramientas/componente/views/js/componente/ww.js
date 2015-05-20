var perfil = window.parent.UCID.portal.perfil;
perfil.etiquetas = Object();
UCID.portal.cargarEtiquetas('componente', cargarInterfaz);
//
tipos = /(^([a-zA-ZáéíóúñÑ])+([a-zA-ZáéíóúñÑ\d\.\-\@\#\_\(\)\s]*))$/;
var winIns, winMod, winSer, winAddSer, winDep,
        soloNumeros = /^[0-9]+$/;
////------------ Inicializo el singlenton QuickTips ------------////
Ext.QuickTips.init();

function cargarInterfaz() {
    //botones de la interfaz componentes
    btnAdicionar = Ext.create('Ext.Button', {// esta es la declaracion de los botones
        disabled: false, // permite activar los botones
        id: 'btnAgrFunc', // es el identificador para cada boton es diferente
        icon: perfil.dirImg + 'adicionar.png', // este es la imagen que aparece en el boton
        iconCls: 'btn',
        text: 'Adicionar', // este es nombre del boton 
        handler: function() { // es el clic de boton
            winForm('Ins');
        }
    });
    
    btnModificar = Ext.create('Ext.Button', {
        disabled: true,
        id: 'btnModFunc',
        icon: perfil.dirImg + 'modificar.png',
        iconCls: 'btn',
        text: 'Modificar',
        handler: function() {
            winForm('Mod');
        }
    });
    
    btnEliminar = Ext.create('Ext.Button', {
        disabled: true,
        id: 'btnEliFunc',
        icon: perfil.dirImg + 'eliminar.png',
        iconCls: 'btn',
        text: 'Eliminar',
        handler: function() {
            //eliminarlocal();
        }
    });
    btnServicio = Ext.create('Ext.Button', {
        disabled: false,
        id: 'btnServicio',
        icon: perfil.dirImg + 'adicionar.png',
        iconCls: 'btn',
        text: 'Servicio',
        handler: function() {
            winService('Ser');
        }
    });
    btnDependencia = Ext.create('Ext.Button', {
        disabled: false,
        id: 'btnDependencia',
        icon: perfil.dirImg + 'adicionar.png',
        iconCls: 'btn',
        text: 'Dependencia',
        handler: function() {
            winDependency('Dep');
        }
    });
    btnEventosObservados = Ext.create('Ext.Button', {
        disabled: false,
        id: 'btnObservados',
        icon: perfil.dirImg + 'adicionar.png',
        iconCls: 'btn',
        text: 'Eventos Observados',
        handler: function() {
            eliminarlocal();
        }
    });
    btnEventosGenerados = Ext.create('Ext.Button', {
        disabled: false,
        id: 'btnGenerados',
        icon: perfil.dirImg + 'adicionar.png',
        iconCls: 'btn',
        text: 'Eventos Generados',
        handler: function() {
            eliminarlocal();
        }
    });
    //Botones de servicio
    btnAdicionarServicio = Ext.create('Ext.Button', {
        disabled: false,
        id: 'btnAService',
        icon: perfil.dirImg + 'adicionar.png',
        iconCls: 'btn',
        text: 'Adicionar',
        handler: function() {
            winAdicionarService('AddSer');
        }
    });
    btnModificarServicio = Ext.create('Ext.Button', {
        disabled: false,
        id: 'btnMService',
        icon: perfil.dirImg + 'modificar.png',
        iconCls: 'btn',
        text: 'Modificar',
        handler: function() {
            eliminarlocal();
        }
    });
    btnEliminarServicio = Ext.create('Ext.Button', {
        disabled: false,
        id: 'btnEService',
        icon: perfil.dirImg + 'eliminar.png',
        iconCls: 'btn',
        text: 'Eliminar',
        handler: function() {
            eliminarlocal();
        }
    });
    //botones de la interfaz dependencia
    btnAdicionarDep = Ext.create('Ext.Button', {
        disabled: false,
        id: 'btnADep',
        icon: perfil.dirImg + 'adicionar.png',
        iconCls: 'btn',
        text: 'Adicionar',
        handler: function() {
            //winAdicionarService('AddSer');
        }
    });
    btnModificarDep = Ext.create('Ext.Button', {
        disabled: false,
        id: 'btnMDep',
        icon: perfil.dirImg + 'modificar.png',
        iconCls: 'btn',
        text: 'Modificar',
        handler: function() {
            eliminarlocal();
        }
    });
    btnEliminarDep = Ext.create('Ext.Button', {
        disabled: false,
        id: 'btnEDep',
        icon: perfil.dirImg + 'eliminar.png',
        iconCls: 'btn',
        text: 'Eliminar',
        handler: function() {
            eliminarlocal();
        }
    });
    btnBuscarDep = Ext.create('Ext.Button', {
        disabled: false,
        id: 'btnBusDep',
        icon: perfil.dirImg + 'buscar.png',
        iconCls: 'btn',
        text: 'Buscar',
        handler: function() {
            //buscarfuncionalidad(funcionalidad.getValue());
        }
    });
    btnAddDepComp = Ext.create('Ext.Button', {
        disabled: false,
        id: 'btnADepComp',
        icon: perfil.dirImg + 'adicionar.png',
        iconCls: 'btn',
        text: 'Adicionar',
        handler: function() {
            //winAdicionarService('AddSer');
        }
    });
    btnModDepComp = Ext.create('Ext.Button', {
        disabled: false,
        id: 'btnMDepComp',
        icon: perfil.dirImg + 'modificar.png',
        iconCls: 'btn',
        text: 'Modificar',
        handler: function() {
            eliminarlocal();
        }
    });
    btnDelDepComp = Ext.create('Ext.Button', {
        disabled: false,
        id: 'btnEDepComp',
        icon: perfil.dirImg + 'eliminar.png',
        iconCls: 'btn',
        text: 'Eliminar',
        handler: function() {
            eliminarlocal();
        }
    });
    btnBuscarDepComp = Ext.create('Ext.Button', {
        disabled: false,
        id: 'btnBusDepComp',
        icon: perfil.dirImg + 'buscar.png',
        iconCls: 'btn',
        text: 'Buscar',
        handler: function() {
            //buscarfuncionalidad(funcionalidad.getValue());
        }
    });
    
    
    //botones de la interfaz operacion de servicios
    btnAdicionarOperacion = Ext.create('Ext.Button', {
        disabled: false,
        id: 'btnAOperacion',
        icon: perfil.dirImg + 'adicionar.png',
        iconCls: 'btn',
        text: 'Adicionar',
        handler: function() {
            eliminarlocal();
        }
    });
    btnModificarOperacion = Ext.create('Ext.Button', {
        disabled: false,
        id: 'btnMOperacion',
        icon: perfil.dirImg + 'modificar.png',
        iconCls: 'btn',
        text: 'Modificar',
        handler: function() {
            eliminarlocal();
        }
    });
    btnEliminarOperacion = Ext.create('Ext.Button', {
        disabled: false,
        id: 'btnEOperacion',
        icon: perfil.dirImg + 'eliminar.png',
        iconCls: 'btn',
        text: 'Eliminar ',
        handler: function() {
            eliminarlocal();
        }
    });
    //store del arbol
    var store = Ext.create('Ext.data.TreeStore', {// esta es la declaracion del store del arbol
       // fields:        [ {name: 'id', mapping: 'id'} ],
        proxy: {
            type: 'ajax',
            url: 'cargar',
            actionMethods: { //Esta Linea es necesaria para el metodo de llamada POST o GET

                read: 'POST'
            },
            reader: {
                //totalProperty: "cantidad_filas",
                //root: "datos",
                  id: "id"
            }
        }
    });
    //arbol
    var arbollocal = Ext.create('Ext.tree.Panel', {// aqui es donde se declara el arbol
        title: 'Componentes', // muestra el nombre del titulo del panel
        collapsible: true, // esto es para desplegar el panel <<>>
        region: 'west', // aqui es donde va a estar ubicado el arbol en este caso oeste 
        width: 300, // este es el ancho del arbol
        height: 150, // este es el largo o la altura del arbol
        store: store, // aqui es donde se define el store del arbol
        rootVisible: false, // este elemento es para mostrar el nodo padre
                //renderTo: Ext.getBody()
        tbar: [btnAdicionar, btnModificar, btnEliminar]
    });
    //store del grid componentes
    var stcomponente = Ext.create('Ext.data.TreeStore', {
        fields:
                [
                    {name: 'idcomponente', mapping: 'idcomponente'},
                    {name: 'identificador', mapping: 'identificador'},
                    {name: 'nombre', mapping: 'nombre'},
                    {name: 'version', mapping: 'version'},
                    {name: 'ubicacion', mapping: 'ubicacion'},
                ],
        proxy: {
            type: 'ajax',
            url: 'componente.js',
            actionMethods: { //Esta Linea es necesaria para el metodo de llamada POST o GET

                read: 'POST'
            },
            reader: {
                totalProperty: "cantidad_filas",
                root: "datos",
                id: "idfuncionalidad"
            }
        }
    });
    //modo de seleccion del grid
    smcomponente = Ext.create('Ext.selection.RowModel', {
        mode: 'SINGLE'
    });          // de esta forma se declara el modo de seleccion del grid
    //eventos 
    smcomponente.on('beforeselect', function(smodel, rowIndex, keepExisting, record) {
        btnModificar.enable(); // permite activar los botones modificar y eliminar cuando se selecciona un elemento del grid
        btnEliminar.enable();
    }, this);
    //grid componentes
    var gridcomp = Ext.create('Ext.grid.Panel', {
        frame: true,
        title: 'Propiedades',
        region: 'center',
        iconCls: 'icon-grid',
        autoExpandColumn: 'expandir',
        store: stcomponente,
        selModel: smcomponente,
        tbar:[btnServicio, btnDependencia, btnEventosObservados, btnEventosGenerados],
        columns: [
            {hidden: true, hideable: false, dataIndex: 'idcomponente'},
            {header: 'identificador', width: 100, dataIndex: 'identificador', flex: 1},
            {header: 'nombre', width: 150, dataIndex: 'nombre'},
            {header: 'version', width: 150, dataIndex: 'version'},
            {header: 'ubicacion', width: 100, dataIndex: 'ubicacion'}

        ],
        loadMask: {store: stcomponente},
        bbar: Ext.create('Ext.toolbar.Paging', {
            pageSize: 15,
            id: 'ptbaux',
            store: stcomponente,
            displayInfo: true,
            displayMsg: perfil.etiquetas.lbMsgbbarI,
            emptyMsg: perfil.etiquetas.lbMsgbbarII
        })
    });

////------------ Trabajo con el PagingToolbar ------------////
    Ext.getCmp('ptbaux').on('change', function() {
        smcomponente.select();
    }, this)

    ////------------ Formulario del componente ------------////
    var formuser = Ext.create('Ext.form.Panel', {// aqui es donde se declara los elementos que van dentro de la venta Windows
        labelAlign: 'top',
        frame: true,
        bodyStyle: 'padding:5px 5px 0',
        items: [{
                layout: 'column',
                items: [{
                        columnWidth: .7,
                        layout: 'form',
                        items: [
                            {
                                xtype: 'textfield', // este es un textfield
                                fieldLabel: 'identificador', // el nombre del textfield
                                id: 'identificador', // el id del textfiel
                                allowBlank: false,
                                blankText: perfil.etiquetas.lbMsgBlankTextDenom,
                                regex: soloNumeros, // aqui es donde se pone la validaciones
                                regexText: perfil.etiquetas.lbMsgExpRegDenom, // aqui es donde se pone el comentario de la validacion
                                anchor: '95%'
                            },
                            {
                                xtype: 'textfield', // este es un textfield
                                fieldLabel: 'nombre', // el nombre del textfield
                                id: 'nombre', // el id del textfiel
                                allowBlank: false,
                                blankText: perfil.etiquetas.lbMsgBlankTextDenom,
                                regex: soloNumeros, // aqui es donde se pone la validaciones
                                regexText: perfil.etiquetas.lbMsgExpRegDenom, // aqui es donde se pone el comentario de la validacion
                                anchor: '95%'
                            },
                            {
                                xtype: 'textfield', // este es un textfield
                                fieldLabel: 'version', // el nombre del textfield
                                id: 'version', // el id del textfiel
                                allowBlank: false,
                                blankText: perfil.etiquetas.lbMsgBlankTextDenom,
                                regex: soloNumeros, // aqui es donde se pone la validaciones
                                regexText: perfil.etiquetas.lbMsgExpRegDenom, // aqui es donde se pone el comentario de la validacion
                                anchor: '95%'
                            },
                            {
                                xtype: 'textfield', // este es un textfield
                                fieldLabel: 'ubicacion', // el nombre del textfield
                                id: 'ubicacion', // el id del textfiel
                                allowBlank: false,
                                blankText: perfil.etiquetas.lbMsgBlankTextDenom,
                                regex: soloNumeros, // aqui es donde se pone la validaciones
                                regexText: perfil.etiquetas.lbMsgExpRegDenom, // aqui es donde se pone el comentario de la validacion
                                anchor: '95%'
                            }
                        ]
                    }]
            }]
    });
    //funcion del boton adicionar
    function winForm(opcion) { // Aqui es donde se declara la funcion del boton Adicionar
        switch (opcion) {
            case 'Ins':
                {
                    if (!winIns)
                    {
                        winIns = Ext.create('Ext.window.Window', {// aqui es donde se declara la ventana de Windows
                            modal: false, // la ventana se convierte en modal, es decir lo que esta por debajo de ellas no se ve, sobresaltando solamente la ventana
                            closeAction: 'hide', //Acción de ocultar ventana cuando la cerramos 
                            layout: 'fit', //Ayuda a que el formulario que insertaremos quede ajustado a perfeccion de la ventana
                            title: 'Adicionar componente',
                            border: true, //Elimina el borde del interior de la ventana
                            width: 400,
                            height: 280,
                            minimizable: true, //muestra los botones de minimizar la ventana
                            maximizable: true, //muestra los botones de maximizar la ventana
                            x: 100, //especifica la posicion de la ventana
                            y: 100, //especifica el top de la ventana
                            buttons: [// asi es como se declaran los botones que van dentro de la ventana Windows
                                {
                                    icon: perfil.dirImg + 'cancelar.png',
                                    iconCls: 'btn',
                                    text: 'Cancelar',
                                    handler: function() {
                                        winIns.hide();
                                    }
                                },
                                {
                                    icon: perfil.dirImg + 'aceptar.png',
                                    iconCls: 'btn',
                                    text: 'Aceptar',
                                    handler: function() {
                                        adicionarLocal();
                                    }
                                }]
                        });
                    }
                    formuser.getForm().reset();
                    winIns.add(formuser); // incluye el formulario dentro de la ventana Windows
                    winIns.doLayout();
                    winIns.show(); // permite mostrar la ventana
                }
                break;
        }
    }
   
    //store del grid servicio
    var stservice =  Ext.create('Ext.data.Store',{
        fields:
                [
                    {name: 'idservicio', mapping: 'idservicio'},
                    {name: 'identificador', mapping: 'identificador'},
                    {name: 'interfaz', mapping: 'interfaz'},
                    {name: 'implementacion', mapping: 'implementacion'}
                ],
        proxy: {
            type: 'ajax',
            url: 'cargarservicio',
            actionMethods: { //Esta Linea es necesaria para el metodo de llamada POST o GET

                read: 'POST'
            },
            reader: {
                totalProperty: "cantidad_filas",
                root: "datos",
                id: "idfuncionalidad"
            }
        }
    });
    //modo de seleccion del grid servicio
    smservice = Ext.create('Ext.selection.RowModel', {
        mode: 'SINGLE'
    });          // de esta forma se declara el modo de seleccion del grid
    //eventos de servicios
    smservice.on('beforeselect', function(smodel, rowIndex, keepExisting, record) {
        btnModificarServicio.enable(); // permite activar los botones modificar y eliminar cuando se selecciona un elemento del grid
        btnEliminarServicio.enable();
    }, this);
    //grid servicios
    var gridservice = Ext.create('Ext.grid.Panel', {
        frame: true,
        region: 'west',
        iconCls: 'icon-grid',
        autoExpandColumn: 'expandir',
        store: stservice,
        selModel: smservice,
        columns: [
            {hidden: true, hideable: false, dataIndex: 'idservicio'},
            {header: 'identificador', width: 100, dataIndex: 'identificador', flex: 1},
            {header: 'interfaz', width: 150, dataIndex: 'interfaz'},
            {header: 'implementacion', width: 150, dataIndex: 'implementacion'}

        ],
        loadMask: {store: stservice},
        bbar: Ext.create('Ext.toolbar.Paging', {
            pageSize: 15,
            id: 'ptbaser',
            store: stservice,
            displayInfo: true,
            displayMsg: perfil.etiquetas.lbMsgbbarI,
            emptyMsg: perfil.etiquetas.lbMsgbbarII
        })
    });
    ////------------ Trabajo con el PagingToolbar ------------////
    Ext.getCmp('ptbaser').on('change', function() {
        smservice.select();
    }, this);
    
    //todo lo referente con el grid de depdendencias
     var stdependency =  Ext.create('Ext.data.Store', {
        fields:
                [
                    {name: 'iddependency', mapping: 'iddependency'},
                    {name: 'identificador', mapping: 'identificador'},
                    {name: 'interfaz', mapping: 'interfaz'},
                    {name: 'implementacion', mapping: 'implementacion'}
                ],
        proxy: {
            type: 'ajax',
            url: 'cargardepdencia',
            actionMethods: { //Esta Linea es necesaria para el metodo de llamada POST o GET

                read: 'POST'
            },
            reader: {
                totalProperty: "cantidad_filas",
                root: "datos",
                id: "idfuncionalidad"
            }
        }
    });
    //modo de seleccion del grid depdencia
    smdependency = Ext.create('Ext.selection.RowModel', {
        mode: 'SINGLE'
    });          // de esta forma se declara el modo de seleccion del grid
    //eventos de dependencia
    smdependency.on('beforeselect', function(smodel, rowIndex, keepExisting, record) {
        //btnModDep.enable(); // permite activar los botones modificar y eliminar cuando se selecciona un elemento del grid
        //btnDelDepConp.enable();
    }, this);
    //grid depdencia
    var griddependency = Ext.create('Ext.grid.Panel', {
        frame: true,
        region: 'west',
        iconCls: 'icon-grid',
        autoExpandColumn: 'expandir',
        store: stdependency,
        selModel: smdependency,
        columns: [
            {hidden: true, hideable: false, dataIndex: 'iddependencia'},
            {header: 'identificador', width: 100, dataIndex: 'identificador', flex: 1},
            {header: 'interfaz', width: 150, dataIndex: 'interfaz'},
            {header: 'implementacion', width: 150, dataIndex: 'implementacion'}

        ],
        loadMask: {store: stdependency},
        bbar: Ext.create('Ext.toolbar.Paging',{
            pageSize: 15,
            id: 'ptbaser',
            store: stdependency,
            displayInfo: true,
            displayMsg: perfil.etiquetas.lbMsgbbarI,
            emptyMsg: perfil.etiquetas.lbMsgbbarII
        })
    });
    ////------------ Trabajo con el PagingToolbar ------------////
    Ext.getCmp('ptbaser').on('change', function() {
        smdependency.select();
    }, this);

    //store de del grid operacion
    var stoperacion =  Ext.create('Ext.data.Store', {
        fields:
                [
                    {name: 'idoperacion', mapping: 'idoperacion'},
                    {name: 'identificador', mapping: 'identificador'},
                    {name: 'interfaz', mapping: 'interfaz'},
                    {name: 'implementacion', mapping: 'implementacion'}
                ],
        proxy: {
            type: 'ajax',
            url: 'cargarservicio',
            actionMethods: { //Esta Linea es necesaria para el metodo de llamada POST o GET

                read: 'POST'
            },
            reader: {
                totalProperty: "cantidad_filas",
                root: "datos",
                id: "idfuncionalidad"
            }
        }
    });
    //modo de selecion de operacion
    smoperacion = Ext.create('Ext.selection.RowModel', {
        mode: 'SINGLE'
    });          // de esta forma se declara el modo de seleccion del grid
    //eventos de operacion
    smoperacion.on('beforeselect', function(smodel, rowIndex, keepExisting, record) {
        btnModificarOperacion.enable(); // permite activar los botones modificar y eliminar cuando se selecciona un elemento del grid
        btnEliminarOperacion.enable();
    }, this);
    //grid de operacion
    var gridoperacion = Ext.create('Ext.grid.Panel', {
        frame: true,
        region: 'center',
        iconCls: 'icon-grid',
        autoExpandColumn: 'expandir',
        store: stoperacion,
        selModel: smoperacion,
        columns: [
            {hidden: true, hideable: false, dataIndex: 'idservicio'},
            {header: 'identificador', width: 100, dataIndex: 'identificador', flex: 1},
            {header: 'interfaz', width: 150, dataIndex: 'interfaz'},
            {header: 'implementacion', width: 150, dataIndex: 'implementacion'}

        ],
        loadMask: {store: stoperacion},
        bbar: Ext.create('Ext.toolbar.Paging', {
            pageSize: 15,
            id: 'ptbopr',
            store: stoperacion,
            displayInfo: true,
            displayMsg: perfil.etiquetas.lbMsgbbarI,
            emptyMsg: perfil.etiquetas.lbMsgbbarII
        })
    });
////------------ Trabajo con el PagingToolbar ------------////
    Ext.getCmp('ptbopr').on('change', function() {
        smoperacion.select();
    }, this);
    
    //store del grid depdendencias que contiene todas las dependencias
    var stdependency1 = Ext.create('Ext.data.Store', {
        fields:
                [
                    {name: 'iddependencia', mapping: 'iddependencia'},
                    {name: 'identificador', mapping: 'identificador'},
                    {name: 'interfaz', mapping: 'interfaz'},
                    {name: 'implementacion', mapping: 'implementacion'}
                ],
        proxy: {
            type: 'ajax',
            url: 'cargardependencia',
            actionMethods: { //Esta Linea es necesaria para el metodo de llamada POST o GET

                read: 'POST'
            },
            reader: {
                totalProperty: "cantidad_filas",
                root: "datos",
                id: "idfuncionalidad"
            }
        }
    });
    //modo de seleccion del grid dependencia1
    smdependency1 = Ext.create('Ext.selection.RowModel', {
        mode: 'SINGLE'
    });          // de esta forma se declara el modo de seleccion del grid
    //eventos de dependencias1
    smdependency1.on('beforeselect', function(smodel, rowIndex, keepExisting, record) {
        //btnModificarServicio.enable(); // permite activar los botones modificar y eliminar cuando se selecciona un elemento del grid
        //btnEliminarServicio.enable();
    }, this);
    //grid dependency1
    var griddependency1 = Ext.create('Ext.grid.Panel', {
        frame: true,
        region: 'west',
        iconCls: 'icon-grid',
        autoExpandColumn: 'expandir',
        store: stdependency1,
        selModel: smdependency1,
        columns: [
            {hidden: true, hideable: false, dataIndex: 'iddependencia'},
            {header: 'identificador', width: 100, dataIndex: 'identificador', flex: 1},
            {header: 'interfaz', width: 150, dataIndex: 'interfaz'},
            {header: 'implementacion', width: 150, dataIndex: 'implementacion'}

        ],
        loadMask: {store: stdependency1},
        bbar: Ext.create('Ext.toolbar.Paging', {
            pageSize: 15,
            id: 'ptbaser',
            store: stdependency1,
            displayInfo: true,
            displayMsg: perfil.etiquetas.lbMsgbbarI,
            emptyMsg: perfil.etiquetas.lbMsgbbarII
        })
    });
    ////------------ Trabajo con el PagingToolbar ------------////
    Ext.getCmp('ptbaser').on('change', function() {
        smdepdendency1.select();
    }, this);
    
    //panel oeste de servicios que contiene el grid de operacion
       var poeste = Ext.create('Ext.panel.Panel',{
        title: 'Oeste',
        collapsible: true,
        region: 'west',
        width: 500,
        items: [gridoperacion],
        tbar: [btnAdicionarServicio, btnModificarServicio, btnEliminarServicio] // permite incluir dentro del panel los botones


    });
    //panel central de servicios que contines grid servicios
    var pcentral = Ext.create('Ext.panel.Panel',{
        title: 'Central',
        collapsible: true,
        region: 'center',
        items: [gridservice],
        tbar: [btnAdicionarOperacion, btnModificarOperacion, btnEliminarOperacion] // permite incluir dentro del panel los botones
    });
    //panel de servicios que contiene todos los elementos de servicios.
    var panelService = Ext.create('Ext.panel.Panel',{// esta es la declaracion del panel central
        layout: 'border', //  permite dividir un panel o contenedor en regiones
        title: 'Gestionar Servicio', // este es el titulo del panel
        items: [poeste, pcentral] // permite incluir dentro del panel los componentes grid y arbol

    });
    //panel oeste de dependencia
    var poested = Ext.create('Ext.panel.Panel', {
        title: 'Todas las Dependencias',
        collapsible: true,
        region: 'west',
        width: 500,
        tbar: [btnAdicionarDep, btnModificarDep, btnEliminarDep, btnBuscarDep],
           items:[griddependency1]
                // permite incluir dentro del panel los botones
    });
      //panel central de dependencia
    var pcentrald = Ext.create('Ext.panel.Panel',{
        title: 'Dependencias del componente',
        collapsible: true,
        region: 'center',
        items:[griddependency],
        tbar:[btnAddDepComp, btnModDepComp, btnDelDepComp, btnBuscarDepComp] // permite incluir dentro del panel los botones
    });
    //panel de dependencia que contiene todo
    var panelDependency = Ext.create('Ext.panel.Panel',{// esta es la declaracion del panel central
        layout: 'border', //  permite dividir un panel o contenedor en regiones
        //title: 'Gestionar Dependencia', // este es el titulo del panel
        
        items: [poested, pcentrald] // permite incluir dentro del panel los componentes grid y arbol

    });
    
    
    //panel con todos los componentes
    var panel = Ext.create('Ext.panel.Panel',{// esta es la declaracion del panel central
        layout: 'border', //  permite dividir un panel o contenedor en regiones
        title: perfil.etiquetas.lbTitVentana, // este es el titulo del panel
        items: [arbollocal, gridcomp], // permite incluir dentro del panel los componentes grid y arbol
        //tbar: [] // permite incluir dentro del panel los botones
    });
    //contenedor
    ////------------ Viewport ------------////
    var vpGestFuncionalidad = Ext.create('Ext.container.Viewport', {// renderiza automáticamente en document.body, y que sólo puede existir uno por aplicación, toma el tamano del navegador
        layout: 'fit', //permite dividir un panel o contenedor en regiones
        items: panel // incluye el panel central
    });
    //levanta la interfaz de servicios
    function winService(opcion) { // Aqui es donde se declara la funcion del boton Adicionar
        switch (opcion) {
            case 'Ser':
                {
                    if (!winSer)
                    {
                        winSer = Ext.create('Ext.window.Window', {// aqui es donde se declara la ventana de Windows
                            modal: false, // la ventana se convierte en modal, es decir lo que esta por debajo de ellas no se ve, sobresaltando solamente la ventana
                            closeAction: 'hide', //Acción de ocultar ventana cuando la cerramos 
                            layout: 'fit', //Ayuda a que el formulario que insertaremos quede ajustado a perfeccion de la ventana
                            title: 'Servicio',
                            border: true, //Elimina el borde del interior de la ventana
                            width: '80%',
                            height: '80%',
                            minimizable: true, //muestra los botones de minimizar la ventana
                            maximizable: true, //muestra los botones de maximizar la ventana
                            x: 100, //especifica la posicion de la ventana
                            y: 100, //especifica el top de la ventana
                            buttons: [// asi es como se declaran los botones que van dentro de la ventana Windows
                                {
                                    icon: perfil.dirImg + 'cancelar.png',
                                    iconCls: 'btn',
                                    text: 'Cancelar',
                                    handler: function() {
                                        winSer.hide();
                                    }
                                },
                                {
                                    icon: perfil.dirImg + 'aceptar.png',
                                    iconCls: 'btn',
                                    text: 'Aceptar',
                                    handler: function() {
                                        adicionarLocal();
                                    }
                                }]
                        });
                    }
                    //formservice.getForm().reset();
                    winSer.add(panelService); // incluye el formulario dentro de la ventana Windows
                    winSer.doLayout();
                    winSer.show(); // permite mostrar la ventana
                }
                break;
        }
    }
    //levanta la interfaz de dependencias
    function winDependency(opcion) { // Aqui es donde se declara la funcion del boton Adicionar
        switch (opcion) {
            case 'Dep':
                {
                    if (!winDep)
                    {
                        winDep = Ext.create('Ext.window.Window',{// aqui es donde se declara la ventana de Windows
                            modal: false, // la ventana se convierte en modal, es decir lo que esta por debajo de ellas no se ve, sobresaltando solamente la ventana
                            closeAction: 'hide', //Acción de ocultar ventana cuando la cerramos 
                            layout: 'fit', //Ayuda a que el formulario que insertaremos quede ajustado a perfeccion de la ventana
                            title: 'Gestionar Dependencia',
                            border: true, //Elimina el borde del interior de la ventana
                            width: '80%',
                            height: '80%',
                            minimizable: true, //muestra los botones de minimizar la ventana
                            maximizable: true, //muestra los botones de maximizar la ventana
                            x: 100, //especifica la posicion de la ventana
                            y: 100, //especifica el top de la ventana
                            buttons: [// asi es como se declaran los botones que van dentro de la ventana Windows
                                {
                                    icon: perfil.dirImg + 'cancelar.png',
                                    iconCls: 'btn',
                                    text: 'Cancelar',
                                    handler: function() {
                                        winDep.hide();
                                    }
                                },
                                {
                                    icon: perfil.dirImg + 'aceptar.png',
                                    iconCls: 'btn',
                                    text: 'Aceptar',
                                    handler: function() {
                                        adicionarLocal();
                                    }
                                }]
                        });
                    }
                    //formservice.getForm().reset();
                    winDep.add(panelDependency); // incluye el formulario dentro de la ventana Windows
                    winDep.doLayout();
                    winDep.show(); // permite mostrar la ventana
                }
                break;
        }
    }

    ////------------ Formulario del servicio ------------////
    var formservice = Ext.create('Ext.form.Panel', {// aqui es donde se declara los elementos que van dentro de la venta Windows
        labelAlign: 'top',
        frame: true,
        bodyStyle: 'padding:5px 5px 0',
        items: [{
                layout: 'column',
                items: [{
                        columnWidth: .7,
                        layout: 'form',
                        items: [
                            {
                                xtype: 'textfield', // este es un textfield
                                fieldLabel: 'identificador', // el nombre del textfield
                                id: 'identificador', // el id del textfiel
                                allowBlank: false,
                                blankText: perfil.etiquetas.lbMsgBlankTextDenom,
                                regex: soloNumeros, // aqui es donde se pone la validaciones
                                regexText: perfil.etiquetas.lbMsgExpRegDenom, // aqui es donde se pone el comentario de la validacion
                                anchor: '95%'
                            },
                            {
                                xtype: 'textfield', // este es un textfield
                                fieldLabel: 'interfaz', // el nombre del textfield
                                id: 'interfaz', // el id del textfiel
                                allowBlank: false,
                                blankText: perfil.etiquetas.lbMsgBlankTextDenom,
                                regex: soloNumeros, // aqui es donde se pone la validaciones
                                regexText: perfil.etiquetas.lbMsgExpRegDenom, // aqui es donde se pone el comentario de la validacion
                                anchor: '95%'
                            },
                            {
                                xtype: 'textfield', // este es un textfield
                                fieldLabel: 'implementacion', // el nombre del textfield
                                id: 'implementacion', // el id del textfiel
                                allowBlank: false,
                                blankText: perfil.etiquetas.lbMsgBlankTextDenom,
                                regex: soloNumeros, // aqui es donde se pone la validaciones
                                regexText: perfil.etiquetas.lbMsgExpRegDenom, // aqui es donde se pone el comentario de la validacion
                                anchor: '95%'
                            }
                        ]
                    }]
            }]
    });
    //Adiciona un servicio en la interfaz servicio
    function winAdicionarService(opcion) { // Aqui es donde se declara la funcion del boton Adicionar
        switch (opcion) {
            case 'AddSer':
                {
                    if (!winAddSer)
                    {
                        winAddSer = Ext.create('Ext.window.Window', {// aqui es donde se declara la ventana de Windows
                            modal: false, // la ventana se convierte en modal, es decir lo que esta por debajo de ellas no se ve, sobresaltando solamente la ventana
                            closeAction: 'hide', //Acción de ocultar ventana cuando la cerramos 
                            layout: 'fit', //Ayuda a que el formulario que insertaremos quede ajustado a perfeccion de la ventana
                            title: 'Adicionar Servicio',
                            border: true, //Elimina el borde del interior de la ventana
                            width: 400,
                            height: 280,
                            minimizable: true, //muestra los botones de minimizar la ventana
                            maximizable: true, //muestra los botones de maximizar la ventana
                            x: 100, //especifica la posicion de la ventana
                            y: 100, //especifica el top de la ventana
                            buttons: [// asi es como se declaran los botones que van dentro de la ventana Windows
                                {
                                    icon: perfil.dirImg + 'cancelar.png',
                                    iconCls: 'btn',
                                    text: 'Cancelar',
                                    handler: function() {
                                        winAddSer.hide();
                                    }
                                },
                                {
                                    icon: perfil.dirImg + 'aceptar.png',
                                    iconCls: 'btn',
                                    text: 'Aceptar',
                                    handler: function() {
                                        adicionarLocal();
                                    }
                                }]
                        });
                    }
                    formservice.getForm().reset();
                    winAddSer.add(formservice); // incluye el formulario dentro de la ventana Windows
                    winAddSer.doLayout();
                    winAddSer.show(); // permite mostrar la ventana
                }
                break;
        }
    }

}

