// 1.
var perfil = window.parent.UCID.portal.perfil;
perfil.etiquetas = Object();
UCID.portal.cargarEtiquetas('gestnomdominio', cargarInterfaz);
// 3. Inicializo el singlenton QuickTips
Ext.QuickTips.init();


//4 declarar variables
var winIns, winMod, regServ, modificar = 0, arregloDeschequeados = [], arrayPadresEliminar = [], NodosInicialesChekeados = [], arrayTiene = [], ultimonodomarcado = 0, valor = 0, bandera = 0, iguales = 0, validarExterna = 0;
var auxDelete = true;
var auxIns = false;
var auxMod = false;
var auxDel = false;
var auxIns2 = true;
var auxMod2 = true;
var auxDel2 = true;
var auxBus2 = true;
var auxMod1 = false;
var auxDel1 = false;
var denominacionMod, descripcionMod, arrayN, arrayNMod;
////------------ Area de Validaciones ------------////
var tipos, aBandera = false;
tipos = /(^([a-zA-ZáéíóúñÑ])+([a-zA-ZáéíóúñÑ\d\_\s]*))$/;

function cargarInterfaz()
{

    //botones
    btnAdicionar = new Ext.Button({
        id: 'btnAgr',
        hidden: true,
        icon: perfil.dirImg + 'adicionar.png',
        iconCls: 'btn',
        text: '<b>' + perfil.etiquetas.lbBtnAdicionar + '</b>',
        handler: function() {
            winForm('Ins');
        }
    });
    btnModificar = new Ext.Button({
        disabled: true,
        id: 'btnMod',
        hidden: true,
        icon: perfil.dirImg + 'modificar.png',
        iconCls: 'btn',
        text: '<b>' + perfil.etiquetas.lbBtnModificar + '</b>',
        handler: function() {
            winForm('Mod');
        }
    });
    btnEliminar = new Ext.Button({
        disabled: true,
        id: 'btnEli',
        hidden: true,
        icon: perfil.dirImg + 'eliminar.png',
        iconCls: 'btn',
        text: '<b>' + perfil.etiquetas.lbBtnEliminar + '</b>',
        handler: function() {
            eliminardominio();
        }
    });
    btnAyuda = new Ext.Button({
        id: 'btnAyu',
        hidden: true,
        icon: perfil.dirImg + 'ayuda.png',
        iconCls: 'btn',
        text: '<b>' + perfil.etiquetas.lbBtnAyuda + '</b>'
    });
    UCID.portal.cargarAcciones(window.parent.idFuncionalidad);
    //Store del Grid 
    stGpDominio = new Ext.data.Store({
        fields:
                [{
                        name: 'iddominio'
                    }, {
                        name: 'denominacion'
                    }, {
                        name: 'descripcion'
                    }, {
                        name: 'seguridad'
                    }
                ],
        proxy: {
            type: 'ajax',
            url: 'cargardominios',
            actionMethods: { //Esta Linea es necesaria para el metodo de llamada POST o GET

                read: 'POST'
            },
            reader: {
                totalProperty: "cant",
                root: "datos"
            }
        }

    });
    // Establesco modo de seleccion de grid (single)
    sm = Ext.create('Ext.selection.RowModel', {
        mode: 'SINGLE'
    });

    sm.on('beforeselect', function(smodel, record, eOpts) {
        btnEliminar.disable();
        btnModificar.enable();
        if (!record.data.seguridad == 1)
            btnEliminar.enable();
    }, this);

    //                        if(!record.data.seguridad == 1)
    //						    btnEliminar.enable();


    // Defino el grid de estudiantes
    var dendominio;
    var GpDominio = new Ext.grid.GridPanel({
        header: false,
        frame: true,
        region: 'center',
        iconCls: 'icon-grid',
        paginate:true,
        autoExpandColumn: 'expandir',
        store: stGpDominio,
        selModel: sm,
        columns: [
            {
                hidden: true,
                hideable: false,
                dataIndex: 'iddominio'
            },
            {
                hidden: true,
                hideable: false,
                dataIndex: 'seguridad'
            },
            {
                flex: 1,
                header: perfil.etiquetas.lbTitDenominacion,
                dataIndex: 'denominacion'
            },
            {
                header: perfil.etiquetas.lbTitDescripcion,
                width: 300,
                dataIndex: 'descripcion'
            }

        ],
        loadMask: {
            store: stGpDominio
        },
        tbar: [
            //new Ext.Toolbar.TextItem({text:perfil.etiquetas.lbTitDenBuscarDominio}),
            dendominio = new Ext.form.TextField({
                fieldLabel: perfil.etiquetas.lbTitDenBuscarDominio,
                labelWidth : 80,
                regex: /(^([a-zA-Z_])+([a-zA-Z0-9_]*))$/,
                regexText: perfil.etiquetas.lbMsgIntrodujovaloresnopermitidos,
                maskRe: /[a-zA-Z0-9_]/i,
                anchor: '95%',
                id: 'dominio'
            }),
            new Ext.menu.Separator(),
            new Ext.Button({
                id: 'ddominio',
                icon: perfil.dirImg + 'buscar.png',
                iconCls: 'btn',
                text: perfil.etiquetas.lbBtnBuscarDominio,
                handler: function() {
                    buscarnombredominio(dendominio.getValue())
                }
            })
        ],
        bbar: new Ext.PagingToolbar({
            pageSize: 15,
            id: 'ptbaux',
            store: stGpDominio,
            displayInfo: true,
            displayMsg: perfil.etiquetas.lbMsgbbarI,
            emptyMsg: perfil.etiquetas.lbMsgbbarII
        })
    });

    ////------------ Trabajo con el PagingToolbar ------------////
    Ext.getCmp('ptbaux').on('change', function() {
        sm.select();
    }, this);
    // renderiar el arbol
    var panel = new Ext.Panel({
        layout: 'border',
        title: perfil.etiquetas.lbTitPanelTit,
        //renderTo:'panel',
        items: [GpDominio],
        tbar: [btnAdicionar, btnModificar, btnEliminar/*,btnAyuda*/],
        keys: new Ext.KeyMap(document, [{
                key: Ext.EventObject.DELETE,
                fn: function() {
                    if (auxDelete && auxDel && auxDel1 && auxDel2)
                        eliminardominio();
                }
            },
            {
                key: "i",
                alt: true,
                fn: function() {
                    if (auxIns && auxIns2)
                        winForm('Ins');
                }
            },
            {
                key: "b",
                alt: true,
                fn: function() {
                    if (auxBus2)
                        buscarnombredominio(Ext.getCmp('dominio').getValue());
                }
            },
            {
                key: "m",
                alt: true,
                fn: function() {
                    if (auxMod && auxMod1 && auxMod2)
                        winForm('Mod');
                }
            }])
    });

    ////------------ Eventos para hotkeys ------------////
    btnAdicionar.on('show', function() {
        auxIns = true;
    }, this);
    btnEliminar.on('show', function() {
        auxDel = true;
    }, this);
    btnModificar.on('show', function() {
        auxMod = true;
    }, this);
    Ext.getCmp('dominio').on('focus', function() {
        auxDelete = false;
    }, this);
    Ext.getCmp('dominio').on('blur', function() {
        auxDelete = true;
    }, this);
    stGpDominio.on('load', function() {
        if (stGpDominio.getCount() != 0)
        {
            auxMod1 = true;
            auxDel1 = true;
        }
        else
        {
            auxMod1 = false;
            auxDel1 = false;
        }
    }, this);
    ////---------- Viewport ----------////
    var vpServidorAut = new Ext.Viewport({
        layout: 'fit',
        items: panel
    });

    stGpDominio.load({
        params: {
            start: 0,
            limit: 10
        }
    });

    //Formulario
    regDominio = new Ext.FormPanel({
        labelAlign: 'top',
        frame: true,
        region: 'west',
        width: 200,
        bodyStyle: 'padding:5px 5px 0',
        items: [{
                layout: 'column',
                border: false,
                items: [{
                        columnWidth: 1,
                        layout: 'form',
                        margin: '5 5 5 5',
                        border: false,
                        items: [{
                                // style: 'background-color:#eee',
                                xtype: 'textfield',
                                labelAlign: 'top',
                                fieldLabel: perfil.etiquetas.lbFLDenominacion,
                                id: 'denominacion',
                                maxLength: 40,
                                name: 'denominacion',
                                allowBlank: false,
                                blankText: perfil.etiquetas.lbMsgBlank,
                                regex: tipos,
                                anchor: '100%'
                            }]
                    }, {
                        columnWidth: 1,
                        layout: 'form',
                        margin: '5 5 5 5',
                        border: false,
                        items: [{
                                xtype: 'textarea',
                                labelAlign: 'top',
                                // style: 'background-color:#eee',
                                fieldLabel: perfil.etiquetas.lbFLDescripcion,
                                id: 'descripcion',
                                height: 200,
                                name: 'descripcion',
                                anchor: '100%'
                            }]
                    }]
            }]
    });

    ////------------ Arbol para cargar las estructuras del pais ------------//// 
    ////------------ Arbol de sistemas ------------////
    Ext.define('dominio', {
        extend: 'Ext.data.Model',
        fields: ['id', 'accion', 'text', 'leaf', 'iddominio', 'idestructuraop']
    });

    var store = Ext.create('Ext.data.TreeStore', {
        model: 'dominio',
        idProperty: 'id',
        proxy: {
            type: 'ajax',
            url: 'cargarestructuras',
            actionMethods: { //Esta Linea es necesaria para el metodo de llamada POST o GET

                read: 'POST'
            },
            reader: {
                type: 'json'

            }
        }
    });


    arbolEntidades = new Ext.tree.TreePanel({
        //title:'Sistemas registrados ',
        autoScroll: true,
        region: 'center',
        split: true,
        width: '37%',
        store: store,
        rootVisible: true,
        root: {
            expanded: false,
            text: perfil.etiquetas.lbArbolEstructura,
            id: '0'
        },
        listeners: {
            itemclick: function(node, rec, item, index, e) {

            },
            beforeload: function(atreeloader, anode, g) {

                if (modificar > 0)
                {
                    anode.params.accion = 'modificar';
                    if (sm.getLastSelected().data.seguridad)
                        anode.params.idseguridad = sm.getLastSelected().data.iddominio;
                    anode.params.iddominio = sm.getLastSelected().data.iddominio;
                    if (anode.node.data.idestructuraop)
                        anode.params.idarea = anode.node.data.idestructuraop;
                    if (anode.node.data.checked && anode.node.data.id != 0)
                        anode.params.tcheck = 'marcado';
                    else if (!anode.node.data.checked && anode.node.data.id != 0)
                        anode.params.tcheck = 'desmarcado';
                }
                else
                {
                    anode.params.accion = 'insertar';
                    if (stGpDominio.getCount() > 0)
                        anode.params.forma = 1;
                    if (anode.node.data.checked && anode.node.data.id != 0)
                        anode.params.tcheck = 'marcado';
                    else if (!anode.node.data.checked && anode.node.data.id != 0)
                        anode.params.tcheck = 'desmarcado';
                }
                //anode.params.idrol=sm.getLastSelected().data.idrol
            },
            load: function(atreeloader, anode)

            {
                var id;
                id = anode.data.id;
                var esta1 = estaEnDeschequeados(arrayPadresEliminar, id);

                if (esta1 != -1)
                {
                    eliminarEnDeschequeados(arrayPadresEliminar, esta1);
                    if (arbolEntidades.getChecked("id", anode).length == 0)
                    {
                        hijosNodo = anode.childNodes;
                        for (i = 0; i < hijosNodo.length; i++)
                        {
                            if (!hijosNodo[i].isLeaf())
                            {
                                id = anode.data.id;
                                adicionarEnDeschequeados(arrayPadresEliminar, id);
                            }
                        }
                    }
                }
                //NodosInicialesChekeados = [];
                //NodosInicialesChekeados = arbolEntidades.getChecked("id", anode);
            }
        }

    });
    store.load();

    /*
     arbolEntidades = new Ext.tree.TreePanel({
     //title:perfil.etiquetas.lbArbolEstructura,
     autoScroll:true,
     region:'center',
     split:true,
     width:'37%',
     loader:new Ext.ComponentLoader({
     dataUrl:'cargarestructuras',
     listeners:{'beforeload':function(atreeloader, anode)
     { 
     atreeloader.baseParams = {};
     if(modificar > 0)
     {     
     atreeloader.baseParams.accion = 'modificar';
     if(sm.getSelected().data.seguridad)
     atreeloader.baseParams.idseguridad = sm.getSelected().data.iddominio ;
     atreeloader.baseParams.iddominio = sm.getSelected().data.iddominio;
     if(anode.attributes.idestructuraop)
     atreeloader.baseParams.idarea = anode.attributes.idestructuraop;  
     if(anode.attributes.checked && anode.attributes.id != 0)
     atreeloader.baseParams.tcheck = 'marcado';
     else if(!anode.attributes.checked && anode.attributes.id != 0)
     atreeloader.baseParams.tcheck = 'desmarcado';
     }
     else
     {   
     atreeloader.baseParams.accion = 'insertar';
     if(stGpDominio.getCount() > 0)
     atreeloader.baseParams.forma = 1; 
     if(anode.attributes.checked && anode.attributes.id != 0)
     atreeloader.baseParams.tcheck = 'marcado';
     else if(!anode.attributes.checked && anode.attributes.id != 0)
     atreeloader.baseParams.tcheck = 'desmarcado';                                       
     }
     
     },'load':function(atreeloader, anode)
     {
     var id;        
     id = anode.attributes.id;
     var esta1 = estaEnDeschequeados(arrayPadresEliminar, id);
     
     if(esta1 != -1)
     {   
     eliminarEnDeschequeados(arrayPadresEliminar, esta1);
     if(arbolEntidades.getChecked("id", anode).length == 0)
     {
     hijosNodo = anode.childNodes;
     for(i=0; i<hijosNodo.length;i++)
     {
     if(!hijosNodo[i].isLeaf())
     {
     id = anode.data.id;
     adicionarEnDeschequeados(arrayPadresEliminar, id);
     }
     }
     }   
     }
     //NodosInicialesChekeados = [];
     //NodosInicialesChekeados = arbolEntidades.getChecked("id", anode);
     }
     }               
     }) 
     });
     
     arbolEntidades.on('checkchange', function (node, e)
     {
     
     var esta = estaEnDeschequeados(arregloDeschequeados, node.attributes.id);
     
     var id;
     id = node.attributes.id; 
     var esta1 = estaEnDeschequeados(arrayPadresEliminar, id);
     
     if(node.attributes.checked)
     {
     /*if(node.attributes.id != 0)
     {
     marcarArriba(node);
     }   
     marcarHijos(node, true);
     
     if(esta != -1)
     eliminarEnDeschequeados(arregloDeschequeados, esta);
     if(!node.isLeaf() && esta1 != -1)
     eliminarEnDeschequeados(arrayPadresEliminar, esta1);
     }
     else
     {
     if(node.attributes.id == 0 && !aBandera)
     {
     //marcarHijos(node, false);
     }
     else if(node.attributes.id != 0 && !aBandera)
     {
     //desmarcarArriba(node);
     //marcarHijos(node, false);
     }
     aBandera = false;
     adicionarEnDeschequeados(arregloDeschequeados, node.attributes.id);
     if(!node.isLeaf() && node.childNodes.length == 0)
     adicionarEnDeschequeados(arrayPadresEliminar, id); 
     }
     }, this);
     */
    ////------------ Crear nodo padre del arbol ------------////



    ////---------- panel adicionar dominio----------////
    var panelAdicionar = new Ext.Panel({
        layout: 'border',
        items: [regDominio, arbolEntidades]
    });


    //Cargar la ventana
    function winForm(opcion) {
        switch (opcion) {
            case 'Ins':
                {
                    limpiarArreglos();
                    if (!winIns) {

                        winIns = new Ext.Window({
                            modal: true,
                            closeAction: 'hide',
                            layout: 'fit',
                            resizable: false,
                            title: perfil.etiquetas.lbTitVentanaTitI,
                            width: 650,
                            height: 400,
                            buttons: [
                                {
                                    icon: perfil.dirImg + 'cancelar.png',
                                    iconCls: 'btn',
                                    text: perfil.etiquetas.lbBtnCancelar,
                                    handler: function() {
                                        winIns.hide();
                                    }

                                }, {
                                    icon: perfil.dirImg + 'aplicar.png',
                                    iconCls: 'btn',
                                    text: perfil.etiquetas.lbBtnAplicar,
                                    handler: function() {
                                        adicionardominio('apl');
                                    }

                                }, {
                                    icon: perfil.dirImg + 'aceptar.png',
                                    iconCls: 'btn',
                                    text: perfil.etiquetas.lbBtnAceptar,
                                    handler: function() {
                                        adicionardominio();
                                    }
                                }]

                        });
                        winIns.on('show', function() {
                            auxIns2 = false;
                            auxMod2 = false;
                            auxDel2 = false;
                            auxBus2 = false;
                        }, this)
                        winIns.on('hide', function() {
                            auxIns2 = true;
                            auxMod2 = true;
                            auxDel2 = true;
                            auxBus2 = true;
                        }, this)
                    }
                    modificar = 0;
                    regDominio.getForm().reset();
                    winIns.add(panelAdicionar);
                    winIns.doLayout();
                    winIns.show();
                    store.load();
                    arbolEntidades.expandPath(arbolEntidades.getRootNode().getPath());
                }
                break;

            case 'Mod':
                {
                    limpiarArreglos();
                    if (!winMod) {
                        winMod = new Ext.Window({
                            modal: true,
                            closeAction: 'hide',
                            layout: 'fit',
                            resizable: false,
                            title: perfil.etiquetas.lbTitVentanaTitII,
                            width: 650,
                            height: 400,
                            buttons: [
                                {
                                    icon: perfil.dirImg + 'cancelar.png',
                                    iconCls: 'btn',
                                    text: perfil.etiquetas.lbBtnCancelar,
                                    handler: function() {
                                        winMod.hide();
                                    }
                                }, {
                                    icon: perfil.dirImg + 'aceptar.png',
                                    iconCls: 'btn',
                                    text: perfil.etiquetas.lbBtnAceptar,
                                    handler: function() {
                                        modificardominio();
                                    }
                                }]

                        });
                        winMod.on('show', function() {
                            auxIns2 = false;
                            auxMod2 = false;
                            auxDel2 = false;
                            auxBus2 = false;
                        }, this)
                        winMod.on('hide', function() {
                            auxIns2 = true;
                            auxMod2 = true;
                            auxDel2 = true;
                            auxBus2 = true;
                        }, this)
                    }
                    modificar = 1;
                    winMod.add(panelAdicionar);
                    winMod.doLayout();
                    winMod.show();
                    regDominio.getForm().loadRecord(sm.getLastSelected());
                    arbolEntidades.getStore().load();
                    arbolEntidades.expandPath(arbolEntidades.getRootNode().getPath());
                    denominacionMod = Ext.getCmp('denominacion').getValue();
                    descripcionMod = Ext.getCmp('descripcion').getValue();
                }
                break;

        }
    }


    // EndOF Cargar
    function adicionardominio(apl) {
        if (regDominio.getForm().isValid()) {
            var arrayNodos = arbolEntidades.getChecked();
            var arrayEnt = new Array();
            var arrayPadres = new Array();
            var arrayarbol = new Array();
            var ins = 'ins';
            if (arrayNodos == 0)
                mostrarMensaje(3, perfil.etiquetas.lbMsgSelecEntidad);
            else
            {
                for (var i = 0; i < arrayNodos.length; i++)
                {
                    arrayEnt.push(arrayNodos[i].data.id);
                    if (!arrayNodos[i].isLeaf() && arrayNodos[i].childNodes.length == 0)
                    {
                        arrayPadres.push(arrayNodos[i].data.id);
                    }
                }
                regDominio.getForm().submit({
                    url: 'insertarnomdominio',
                    params: {
                        arrayEntidades: Ext.encode(arrayEnt),
                        arrayPadres: Ext.encode(arrayPadres)
                    },
                    waitMsg: perfil.etiquetas.lbMsgFunAdicionarMsg,
                    failure: function(form, action)
                    {
                        if (action.result.codMsg != 3)
                        {                           
                            regDominio.getForm().reset();
                            limpiarArreglos();
                            if (!apl)
                                winIns.hide();
                            arrayNodos = arbolEntidades.getChecked();
                            for (var i = 0; i < arrayNodos.length; i++)
                            {
                                cambiarEstadoCheck(arrayNodos[i], false);
                            }
                            stGpDominio.load();
                            sm.clearSelections();
                            btnModificar.disable();
                            btnEliminar.disable();
                        }
                    }
                });

            }
        }
        else
            mostrarMensaje(3, perfil.etiquetas.lbMsgErrorEnCamops);
    }

    //////////Modififcar campo///////////////
    function modificardominio()
    {
        if (regDominio.getForm().isValid())
        {
            arrayNodos = arbolEntidades.getChecked();
            var arrayEnt = new Array();
            var arrayPadres = new Array();
            var arrayarbol = new Array();
            buscarNodosTiene(arbolEntidades.getRootNode());
            var ins = 'mod';
            if (arrayNodos.length > 0)
            {


                for (var i = 0; i < arrayNodos.length; i++)
                {
                    bandera = 0;
                    iguales = 0;
                    arrayEnt.push(arrayNodos[i].data.id);
                    if (!arrayNodos[i].isLeaf() && arrayNodos[i].childNodes.length == 0)
                    {
                        arrayPadres.push(arrayNodos[i].data.id);

                    }
                    if (arrayNodos[i].data.id != 0)
                        arrayarbol.push(armarcadena(arrayNodos[i], ins));
                }
            }
            var dMod = denominacionMod != Ext.getCmp('denominacion').getValue();
            var desMod = descripcionMod != Ext.getCmp('descripcion').getValue();
            arrayNMod = arbolEntidades.getChecked();
            var cambio = false;
            if (arrayN.length == arrayNMod.length) {
                for (i = 0; i < arrayN.length; i++) {
                    if (arrayN[i] != arrayNMod[i])
                        cambio = true;

                }
            }
            else
                cambio = true;
            if (dMod || desMod || cambio) {
                regDominio.getForm().submit
                        ({
                            url: 'modificarnomdominio',
                            waitMsg: perfil.etiquetas.lbMsgFunModificarMsg,
                            timeout: 1800000,
                            params: {
                                iddominio: sm.getLastSelected().data.iddominio,
                                arrayEntidades: Ext.encode(arrayEnt),
                                arrayEntidadesEliminar: Ext.encode(arregloDeschequeados),
                                seguridad: sm.getLastSelected().data.seguridad,
                                arrayPadres: Ext.encode(arrayPadres),
                                arrayarbol: Ext.encode(arrayarbol),
                                arrayPadresEliminar: Ext.encode(arrayPadresEliminar),
                                arrayTiene: Ext.encode(arrayTiene)
                            },
                            failure: function(form, action)
                            {
                                limpiarArreglos();
                                if (action.result.codMsg != 3)
                                    //mostrarMensaje(action.result.codMsg, perfil.etiquetas.lbMsgModDom);
                                stGpDominio.load();
                                winMod.hide();


                                if (action.result.codMsg == 3)
                                    mostrarMensaje(action.result.codMsg, perfil.etiquetas.lbMsgModDomError);
                            }

                        });
            }
            else
                mostrarMensaje(3, perfil.etiquetas.NoModify);
            //arbolEntidades.getRootNode().reload();
        }
        else
            mostrarMensaje(3, perfil.etiquetas.lbMsgErrorEnCamops);
    }
       arbolEntidades.on('load', function(node, e) {
        arrayN = arbolEntidades.getChecked();


    }, this);

    function buscarNodosTiene(nodo)
    {
        nodo.eachChild(function(anodehijo)
        {
            esta = estaEnDeschequeados(arregloDeschequeados, anodehijo.data.id);
            if (esta == -1 && !anodehijo.data.checked && !anodehijo.isLeaf() && anodehijo.data.tiene && anodehijo.childNodes.length == 0)
                arrayTiene.push(anodehijo.data.id);
            if (anodehijo.childNodes.length > 0)
                buscarNodosTiene(anodehijo);

        }, this);
    }


    ///////Eliminar  campo////////////////////////
    function eliminardominio() {
        mostrarMensaje(2, perfil.etiquetas.lbMsgFunEliminarMsg, elimina);
        function elimina(btnPresionado) {
            if (btnPresionado == 'ok') {
                Ext.Ajax.request({
                    url: 'eliminarnomdominio',
                    method: 'POST',
                    params: {
                        iddominio: sm.getLastSelected().data.iddominio
                    },
                    callback: function(options, success, response)
                    {
                        responseData = Ext.decode(response.responseText);
                        
                        if (responseData.codMsg == 1)
                        {                            
                            stGpDominio.load();
                            sm.clearSelections();
                            btnModificar.disable();
                            btnEliminar.disable();
                        }else{
                            if (responseData.codMsg == 3)
                            mostrarMensaje(responseData.codMsg, perfil.etiquetas.lbMsgDeleDomError2);   
                        }
                    }
                });
            }
        }
    }

    ////------------ Auxiliar para marcar y desmarcar nodos ------------////
    function cambiarEstadoCheck(anodehijo, check)
    {
        if (anodehijo.data.checked != check)
        {
            //anodehijo.getUI().toggleCheck(check);
            anodehijo.data.checked = check;
            banderaClick = false;
            anodehijo.fireEvent('checkchange', anodehijo, check);
        }
    }

    function quitarArregloPadresEliminar()
    {
        for (j = 0; j < arrayPadresEliminar.length; j++)
        {
            esta = estaEnDeschequeados(NodosInicialesChekeados, arrayPadresEliminar[j]);

            if (esta == -1)
            {

                eliminarEnDeschequeados(arrayPadresEliminar, j);
            }
        }
    }

    function estaEnDeschequeados(arreglonodos, idnodo)
    {
        var cantidad = arreglonodos.length;
        for (p = 0; p < cantidad; p++)
        {
            if (arreglonodos[p] == idnodo)
                return p;
        }
        return -1;
    }

    function eliminarEnDeschequeados(arreglo, pos)
    {
        arreglo.splice(pos, 1);
    }

    function adicionarEnDeschequeados(arreglo, id)
    {
        if (estaEnDeschequeados(arreglo, id) == -1)
            arreglo.push(id);

    }

    function limpiarArreglos()
    {
        arregloDeschequeados = [];
        arrayPadresEliminar = [];
        arrayTiene = [];
    }


    function marcarHijos(nodo, check)
    {
        nodo.eachChild(function(anodehijo)
        {
            if (anodehijo.attributes.checked != check)
                cambiarEstadoCheck(anodehijo, check);
            if (anodehijo.childNodes.length > 0)
                marcarHijos(anodehijo, check);
        }, this);
    }

    function estadoTodosHijos(nodo, opcion)
    {
        resultado = true;
        nodo.eachChild(function(anodehijo)
        {
            if (anodehijo.attributes.checked != opcion)
                resultado = false;
        }, this);
        return resultado;
    }

    function marcarArriba(nodo)
    {
        if (nodo.data.id != 0)
        {
            if (estadoTodosHijos(nodo.parentNode, true))
                cambiarEstadoCheck(nodo.parentNode, true);
            marcarArriba(nodo.parentNode);
        }
    }

    function desmarcarArriba(nodo)
    {
        aBandera = true;
        if (nodo.data.id != 0)
        {
            cambiarEstadoCheck(nodo.parentNode, false);
            if (nodo.parentNode.data.id >= 0)
            {
                desmarcarArriba(nodo.parentNode);
            }
        }
    }

    function armarcadena(nodo, ins)
    {
        if (!bandera)
        {
            buscarultimonodomarcado(nodo);
            bandera = 1;
        }
        var cadena = '';
        if (nodo.parentNode)
        {
            if (valor == nodo.data.id)
            {
                iguales = valor;
            }
            if (nodo.data.tipo == 'externa')
            {
                if (ins == 'ins')
                    validarExterna = 1;
                if (!iguales)
                {
                    cad = armarcadena(nodo.parentNode, ins);
                    if (cad != '')
                        cadena += cad + "-" + nodo.data.id + '_e';
                    else
                        cadena += nodo.data.id + '_e';
                }
                else
                    cadena += nodo.data.id + '_e';
            }
            else
            {
                if (!iguales)
                {
                    cad = armarcadena(nodo.parentNode, ins);
                    if (cad != '')
                        cadena += cad + "-" + nodo.data.id + '_i';
                    else
                        cadena += nodo.data.id + '_i';
                }
                else
                    cadena += nodo.data.id + '_i';
            }
            return cadena;
        }
        return cadena;
    }

    function buscarultimonodomarcado(nodo)
    {
        if (nodo.data.checked)
        {
            valor = nodo.data.id;
            if (nodo.data.id != 0 && nodo.parentNode)
            {
                buscarultimonodomarcado(nodo.parentNode);
            }
            else
                return;
        }
        else
        {
            if (nodo.parentNode)
                buscarultimonodomarcado(nodo.parentNode);
            else
                return;
        }
    }

    function buscarnombredominio(dendominio) {
        // stGpDominio.baseParams = {};
        //stGpDominio.baseParams.dendominio = dendominio;
        stGpDominio.load({
            params: {
                dendominio: dendominio,
                start: 0,
                limit: 10
            }
        });

    }
}
