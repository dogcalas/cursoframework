// 1.
var perfil = window.parent.UCID.portal.perfil;
perfil.etiquetas = Object();
// 2.
UCID.portal.cargarEtiquetas('gestservpresta', cargarInterfaz);
// 3. Inicializo el singlenton QuickTips
Ext.QuickTips.init();

////----------4 declarar variables ----------////
var winIns, winMod, idsistema;
var auxIns = false;
var auxMod = false;
var auxDel = false;
var auxIns3 = true;
var auxMod3 = true;
var auxDel3 = true;
var auxIns2 = false;
var auxMod2 = false;
var auxDel2 = false;
var idnodopadre = 0;
var flag = false;
var abreviaturanodo;
var nodoprueba;
var myMask;
function cargarInterfaz() {

    ////----------Botones ----------////
    //btnAdicionar = new Ext.Button({id:'btnAgrServPres', hidden:true, disabled:true,icon:perfil.dirImg+'adicionar.png', iconCls:'btn', text:perfil.etiquetas.lbBtnAdicionar, handler:function(){winForm('Ins');}});
    btnAdicionar1 = new Ext.Button({id: 'btnAgrServPres1', icon: perfil.dirImg + 'adicionar.png', iconCls: 'btn', text: perfil.etiquetas.lbBtnAdicionar, handler: function() {
            winForm('Ins');
        }});
    btnModificar = new Ext.Button({id: 'btnModServPres', hidden: true, disabled: true, icon: perfil.dirImg + 'modificar.png', iconCls: 'btn', text: perfil.etiquetas.lbBtnModificar, handler: function() {
            winForm('Mod');
        }});
    //btnEliminar = new Ext.Button({id:'btnEliServPres', hidden:true, disabled:true, icon:perfil.dirImg+'eliminar.png', iconCls:'btn', text:perfil.etiquetas.lbBtnEliminar, handler:function(){eliminarServicio();}});
    UCID.portal.cargarAcciones(window.parent.idFuncionalidad);
    //btnAdicionar.disable();
    ////----------Arbol funcionalides ----------////
    Ext.define('server', {
        extend: 'Ext.data.Model',
        fields: ['id', 'abreviatura', 'descripcion', 'icono', 'leaf', 'text']
    });

    var sttreeserver = Ext.create('Ext.data.TreeStore', {
        autoLoad: false,
        model: 'server',
        idProperty: 'id',
        proxy: {
            type: 'ajax',
            url: 'cargarsistemashojas',
            actionMethods: { //Esta Linea es necesaria para el metodo de llamada POST o GET

                read: 'POST'
            },
            reader: {
                type: 'json'
            }
        }
    });

    arbolServ = new Ext.tree.TreePanel({
        title: perfil.etiquetas.lbTitSistemasReg,
        collapsible: true,
        autoScroll: true,
        id: 'arbolexterno',
        region: 'west',
        split: true,
        width: '28%',
        store: sttreeserver,
        root: {
            text: perfil.etiquetas.lbRootNodeSitem,
            expandable: false,
            expanded: true,
            id: '0'
        }
    });
    ////---------- Evento para habilitar botones ----------////
    arbolServ.on('itemclick', function(anode, e) {
        btnModificar.disable();
        if (anode.node.data.leaf==="") {
            flag = true;
            if (cbProcesos.getValue() != '')
            {
                idnodopadre = anode.node.data.id;
                gpServicio.enable();
                stGpServicio.removeAll();
                idsistema = anode.node.data.id;
                nodoprueba = anode;
                stGpServicio.load({params: {start: 0, limit: 15}});
                auxIns = true;
                tpPrincipal.enable();
                idnodopadre = anode.node.data.id;
                abreviaturanodo = anode.node.data.abreviatura;
               // arbolServInt.getLoader().baseParams.idnodoselected = anode.node.data.id;
               // arbolServInt.getLoader().baseParams.abreviatura = anode.node.data.abreviatura;
               // arbolServInt.getLoader().baseParams.idproceso = cbProcesos.getValue();
               // arbolServInt.getLoader().load(arbolServInt.getRootNode());

                auxMod = false;
                auxDel = false;

            }
            idnodopadre = anode.node.data.id;
            abreviaturanodo = anode.node.data.abreviatura;

        }
        else
        {
            auxDel = false;
            auxIns = false;
            auxMod = false;
            flag = false;
            tpPrincipal.disable();
        }
        if (anode.node.data.id == 0)
        {
            gpServicio.disable();
        }

    }, this);

    ////---------- Store del Grid de Funcionalidades  ----------////
    var stGpServicio = new Ext.data.Store({
        fields:
                [
                    {name: 'idservicio', mapping: 'idservicio'},
                    {name: 'denominacion', mapping: 'denominacion'},
                    {name: 'descripcion', mapping: 'descripcion'},
                    {name: 'wsdl', mapping: 'wsdl'}
                ],
        listeners: {'beforeload': function(thisstore, objeto) {
                objeto.params.idsistema = idsistema
            }},
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

    /////---------- store de internos ----------/////
    var stGpServicioIn = new Ext.data.Store({
        fields:
                [
                    {name: 'idservicio', mapping: 'idservicio'},
                    {name: 'denominacion', mapping: 'denominacion'},
                    {name: 'descripcion', mapping: 'descripcion'},
                    {name: 'ws dl', mapping: 'wsdl'}
                ],
        listeners: {'beforeload': function(thisstore, objeto) {
                objeto.params.idsistema = idsistema
            }},
        proxy: {
            type: 'ajax',
            url: 'cargarservicio',
            actionMethods: { //Esta Linea es necesaria para el metodo de llamada POST o GET

                read: 'POST'
            },
            reader: {
                totalProperty: "cantidad_filas",
                root: "datos",
                id: "idfuncionalidadIn"
            }
        }
    });
    ////---------- Establesco modo de seleccion de grid (single) ----------////
    sm = Ext.create('Ext.selection.RowModel', {
        mode: 'SINGLE'
    });

    sm.on('beforeselect', function(smodel, rowIndex, keepExisting, record)
    {


    }, this);

    sm.on('select ', function(smodel, rowIndex, record)
    {
        btnModificar.enable();
    }, this);
    ////------------ Store del combobox de procesos ------------////	
     var stProcesos = new Ext.data.Store({
        autoLoad: true,
        fields:
                [
            {name: 'idproceso', mapping: 'idproceso'},
            {name: 'proceso', mapping: 'denominacion'}
        ],
        proxy: {
            type: 'ajax',
            url: 'listarprocesos',
            actionMethods: { //Esta Linea es necesaria para el metodo de llamada POST o GET

                read: 'POST'
            },
            reader: {
                id: 'idproceso',
                root: 'datos'
            }
        }
    });
    ////---------- Combo de procesos ----------////
    var cbProcesos = new Ext.form.ComboBox({
        emptyText: 'Seleccione...',
        editable: false,
        fieldLabel: 'Procesos',
        id: 'cbprocesos',
        store: stProcesos,
        valueField: 'idproceso',
        displayField: 'proceso',
        hiddenName: 'idproceso',
        forceSelection: true,
        typeAhead: true,
        mode: 'local',
        allowBlank: false,
        triggerAction: 'all',
        selectOnFocus: true,
        anchor: '100%'
    });

    cbProcesos.on('select', function() {
        btnModificar.disable();
        if (flag)
        {

            if (tpPrincipal.disabled)
                tpPrincipal.enable();
            arbolServInt.getLoader().baseParams.idnodoselected = arbolServ.getSelectionModel().getSelectedNode().attributes.id;
            arbolServInt.getLoader().baseParams.abreviatura = arbolServ.getSelectionModel().getSelectedNode().attributes.abreviatura;
            arbolServInt.getLoader().baseParams.idproceso = cbProcesos.getValue();
            arbolServInt.getLoader().load(arbolServInt.getRootNode());
        }
    }, this);

    ////---------- Defino el grid de Funcionalidades ----------////
    var gpServicio = new Ext.grid.GridPanel({
        title: 'Externos',
        frame: true,
        region: 'center',
        autoExpandColumn: 'expandir',
        store: stGpServicio,
        height: 497,
        margins: '5 5 5 5',
        selModel: sm,
        columns: [
            {hidden: true, hideable: false, dataIndex: 'idservicio'},
            {header: perfil.etiquetas.lbCampDenom, width: 150, dataIndex: 'denominacion'},
            {header: perfil.etiquetas.lbCampWSDL, width: 150, dataIndex: 'wsdl'},
            {id: 'expandir', header: perfil.etiquetas.lbCampDescrip, width: 200, dataIndex: 'descripcion'}
        ],
        loadMask: {store: stGpServicio},
        bbar: new Ext.PagingToolbar({
            pageSize: 15,
            id: 'ptbaux',
            store: stGpServicio,
            displayInfo: true,
            displayMsg: perfil.etiquetas.lbMsgPaginado,
            emptyMsg: perfil.etiquetas.lbMsgEmpty
        })
    });
    ////------------ Trabajo con el PagingToolbar ------------////
    Ext.getCmp('ptbaux').on('change', function() {
        sm.selectFirstRow();
    }, this);
    ////---------- arbol para internos----------////
    
    Ext.define('inter', {
        extend: 'Ext.data.Model',
        fields: ['id', 'abreviatura', 'descripcion', 'icono', 'leaf', 'text']
    });

    var sttreefunciones = Ext.create('Ext.data.TreeStore', {
        autoLoad: false,
        model: 'inter',
        idProperty: 'id',
        proxy: {
            type: 'ajax',
            url: 'cargarSUB',
            actionMethods: { //Esta Linea es necesaria para el metodo de llamada POST o GET

                read: 'POST'
            },
            reader: {
                type: 'json'
            }
        }
    });   
    arbolServInt = new Ext.tree.TreePanel({
        title: perfil.etiquetas.lbRootNodeIOC,
        id: 'arbolServInt',
        autoScroll: true,
        height: 500,
        width: 200,
        region: 'center',
        split: true,
        root: {
            text: 'ioc',
            expandable: false,
            expanded: true,
            id: '0'
        },
        store:sttreefunciones
    });


//    arbolServInt.getLoader().on("beforeload", function(treeLoader, node) {
//        this.baseParams.idnodoselected = idnodopadre;
//        this.baseParams.abreviatura = abreviaturanodo;
//        this.baseParams.idproceso = cbProcesos.getValue();
//    }, arbolServInt.getLoader());

    arbolServInt.on('itemclick', function(anode, e) {
        if (anode.node.data.leaf==="") {
            btnModificar.enable();
        }
        else
        {
            btnModificar.disable();
        }


    }, this);

    /*arbolServInt.on('load', function (node, e){
     myMask = new Ext.LoadMask(Ext.getBody(), {msg:"Please wait..."});
     myMask.show();
     }, this);*/


    ////---------- tabpanel ----------////
    var tpPrincipal = new Ext.TabPanel({
        activeTab: 0,
        disabled: true,
        margins: '5 5 5 5',
        region: 'center',
        frame: true,
        idNode: 0,
        defaultType: 'panel',
        items: [{
                xtype: 'panel',
                title: 'Externo',
                id: 'tab0',
                items: [gpServicio]
            },
            {
                xtype: 'panel',
                title: 'Interno',
                id: 'tab1',
                layout: 'border',
                items: [arbolServInt],
                listeners: {activate: function() {
                        var oldid = arbolServInt.getLoader().baseParams.idnodoselected;
                        var newId = arbolServ.getSelectionModel().getSelectedNode().attributes.id;
                        if (newId != oldid)
                        {
                            arbolServInt.getLoader().baseParams.idnodoselected = arbolServ.getSelectionModel().getSelectedNode().attributes.id;
                            arbolServInt.getLoader().baseParams.abreviatura = arbolServ.getSelectionModel().getSelectedNode().attributes.abreviatura;
                            arbolServInt.getLoader().baseParams.idproceso = cbProcesos.getValue();
                            arbolServInt.getLoader().load(arbolServInt.getRootNode());
                        }
                    }
                }

            }]
    });
    var panel2 = new Ext.Panel({
        region: 'center',
        layout: 'border',
        tbar: [cbProcesos],
        items: [arbolServ, {
                xtype: 'panel',
                region: 'center',
                title: 'Interno',
                id: 'tab1',
                layout: 'border',
                items: [arbolServInt],
                listeners: {activate: function() {
                        var oldid = arbolServInt.getLoader().baseParams.idnodoselected;
                        var newId = arbolServ.getSelectionModel().getSelectedNode().attributes.id;
                        if (newId != oldid)
                        {
                            arbolServInt.getLoader().baseParams.idnodoselected = arbolServ.getSelectionModel().getSelectedNode().attributes.id;
                            arbolServInt.getLoader().baseParams.abreviatura = arbolServ.getSelectionModel().getSelectedNode().attributes.abreviatura;
                            arbolServInt.getLoader().baseParams.idproceso = cbProcesos.getValue();
                            arbolServInt.getLoader().load(arbolServInt.getRootNode());
                        }
                    }
                }

            }]
    })
    var panel = new Ext.Panel({
        title: perfil.etiquetas.lbTitGestServPresta,
        layout: 'border',
        items: [panel2],
        tbar: [/*btnAdicionar*/btnModificar/*btnEliminar*/],
        keys: new Ext.KeyMap(document, [{
                key: Ext.EventObject.DELETE,
                fn: function() {
                    if (auxDel && auxDel2 && auxDel3)
                        eliminarServicio();
                }
            },
            {
                key: "i",
                alt: true,
                fn: function() {
                    if (auxIns && auxIns2 && auxIns3)
                        winForm('Ins');
                }
            },
            {
                key: "m",
                alt: true,
                fn: function() {
                    if (auxMod && auxMod2 && auxMod3)
                        winForm('Mod');
                }
            }])
    });

    Ext.getCmp('tab1').on('activate', function() {
        auxMod = false;
        auxDel = false;
        //btnAdicionar.disable();
        btnModificar.disable();
        //btnEliminar.disable();
    }, this);

    /*Ext.getCmp('tab0').on('activate',function(){
     if(stGpServicio.getCount() != 0)
     {
     auxMod = true;
     auxDel = true;
     //btnAdicionar.enable();
     btnModificar.enable();
     btnEliminar.enable();
     }
     else
     {
     auxMod = false;
     auxDel = false;
     //btnAdicionar.enable();
     btnModificar.disable();
     btnEliminar.disable();
     }
     },this);*/

    ////---------- Eventos para hotkeys ----------////
    /*btnAdicionar.on('show',function(){
     auxIns2 = true;
     },this);*/
    /*btnEliminar.on('show',function(){
     auxDel2 = true;
     },this);*/
    btnModificar.on('show', function() {
        auxMod2 = true;
    }, this);

    /*stGpServicio.on('load',function(){
     if(stGpServicio.getCount() != 0)
     {
     auxMod = true;
     auxDel = true;
     }
     else
     {
     auxMod = false;
     auxDel = false;
     }
     },this);*/
    ////---------- Viewport ----------////
    var vpGestSistema = new Ext.Viewport({
        layout: 'fit',
        items: panel
    });
    ////---------- Formulario de registrar servicios que presta ----------////
    var regServPresta = new Ext.FormPanel({
        labelAlign: 'top',
        frame: true,
        bodyStyle: 'padding:5px 5px 0',
        items: [{
                layout: 'column',
                items: [{
                        columnWidth: .5,
                        layout: 'form',
                        items: [{
                                xtype: 'textfield',
                                fieldLabel: perfil.etiquetas.lbDenominacion,
                                id: 'denominacion',
                                maxLength: 50,
                                anchor: '95%',
                                allowBlank: false,
                                blankText: perfil.etiquetas.lbMsgBlankTextDenom,
                                regex: /(^([a-zA-Z�������])+([a-zA-Z��������\d\.\-\@\#\_\s]*))$/,
                                regexText: perfil.etiquetas.lbMsgExpRegDenom
                            }]
                    }, {
                        columnWidth: 1,
                        layout: 'form',
                        items: [{
                                xtype: 'textfield',
                                fieldLabel: perfil.etiquetas.lbWSDL,
                                id: 'wsdl',
                                anchor: '100%',
                                allowBlank: false,
                                blankText: perfil.etiquetas.lbMsgBlankTextDenom,
                                regex: /^^([hH][tT]{2}[pP]:\/\/){1}([a-zA-Z��������\d\-\@\#\_]*\.{0,1}[a-zA-Z��������\d\-\@\#\_\s]+)+([a-zA-Z��������\d\-\@\#\_]*\.{0,1}[a-zA-Z��������\d\-\@\#\_\s]+)*([a-zA-Z��������\d\-\@\#\_]*\/{0,1}[a-zA-Z��������\d\-\@\#\_\s]+)*(\.[wW][sS][dD][lL])$/,
                                regexText: perfil.etiquetas.lbMsgExpRegDenom
                            }]
                    }, {
                        columnWidth: 1,
                        layout: 'form',
                        items: [{
                                xtype: 'textarea',
                                fieldLabel: perfil.etiquetas.lbDescripcion,
                                id: 'descripcion',
                                anchor: '100%'
                            }]
                    }]
            }]
    });





    /*var modiServPresta = new Ext.FormPanel({
     labelAlign:'top',
     frame:true,
     bodyStyle:'padding:3px 3px 0',
     items: [{
     
     layout:'column',
     items:[{
     columnWidth:1,
     layout: 'form',
     items: [{
     xtype:'textfield',
     fieldLabel: perfil.etiquetas.lbWSDL,
     id:'wsdl',
     anchor:'100%',
     allowBlank: false,
     blankText: perfil.etiquetas.lbMsgBlankTextDenom,
     regex:/^^([hH][tT]{2}[pP]:\/\/){1}([a-zA-Z��������\d\-\@\#\_]*\.{0,1}[a-zA-Z��������\d\-\@\#\_\s]+)+([a-zA-Z��������\d\-\@\#\_]*\.{0,1}[a-zA-Z��������\d\-\@\#\_\s]+)*([a-zA-Z��������\d\-\@\#\_]*\/{0,1}[a-zA-Z��������\d\-\@\#\_\s]+)*(\.[wW][sS][dD][lL])$/,
     regexText:perfil.etiquetas.lbMsgExpRegDenom
     }]
     }]
     }]
     });*/





    var modiServPresta = new Ext.FormPanel({
        labelAlign: 'top',
        frame: true,
        bodyStyle: 'padding:5px 5px 0',
        items: [{
                layout: 'column',
                items: [{
                        columnWidth: .5,
                        layout: 'form',
                        items: [{
                                xtype: 'textfield',
                                fieldLabel: perfil.etiquetas.lbWSDL,
                                id: 'wsdl',
                                allowBlank: false,
                                // maxLength:40,    
                                blankText: perfil.etiquetas.lbMsgBlankTextDenom,
                                regex: /^^([hH][tT]{2}[pP]:\/\/){1}([a-zA-Z��������\d\-\@\#\_]*\.{0,1}[a-zA-Z��������\d\-\@\#\_\s]+)+([a-zA-Z��������\d\-\@\#\_]*\.{0,1}[a-zA-Z��������\d\-\@\#\_\s]+)*([a-zA-Z��������\d\-\@\#\_]*\/{0,1}[a-zA-Z��������\d\-\@\#\_\s]+)*(\.[wW][sS][dD][lL])$/,
                                regexText: perfil.etiquetas.lbMsgExpRegDenom,
                                anchor: '95%'
                            }]
                    }]
            }]
    });




    ////---------- Cargar ventanas ----------////
    function winForm(opcion) {
        switch (opcion) {
            case 'Ins':
                {

                    if (!winIns) {
                        winIns = new Ext.Window({modal: true, closeAction: 'hide', layout: 'fit',
                            title: perfil.etiquetas.lbTitAdicServPrest, width: 200, height: 270,
                            buttons: [
                                {
                                    icon: perfil.dirImg + 'cancelar.png',
                                    iconCls: 'btn',
                                    text: perfil.etiquetas.lbBtnCancelar,
                                    handler: function() {
                                        regServPresta.getForm().reset();
                                        winIns.hide();
                                    }
                                }, {
                                    icon: perfil.dirImg + 'aplicar.png',
                                    iconCls: 'btn',
                                    text: perfil.etiquetas.lbBtnAplicar,
                                    handler: function() {
                                        adicionarServicio('apl');
                                    }
                                }, {
                                    icon: perfil.dirImg + 'aceptar.png',
                                    iconCls: 'btn',
                                    text: perfil.etiquetas.lbBtnAceptar,
                                    handler: function() {
                                        adicionarServicio();
                                    }
                                }]
                        });
                    }

                }
                break;
            case 'Mod':
                {
                    if (!winMod)
                    {
                        winMod = new Ext.Window({modal: true, closeAction: 'hide', layout: 'fit',
                            resizable: false,
                            title: perfil.etiquetas.lbTitVentanaTitII, width: 350, height: 220,
                            buttons: [
                                {
                                    icon: perfil.dirImg + 'cancelar.png',
                                    iconCls: 'btn',
                                    text: perfil.etiquetas.lbBtnCancelar,
                                    handler: function() {
                                        regServPresta.getForm().reset();
                                        Ext.getCmp('wsdl').reset();
                                        winMod.hide();
                                    }
                                },
                                {
                                    icon: perfil.dirImg + 'aceptar.png',
                                    iconCls: 'btn',
                                    text: perfil.etiquetas.lbBtnAceptar,
                                    handler: function() {
                                        modificarServicio();
                                        arbolServInt.getLoader().baseParams.idnodoselected = arbolServ.getSelectionModel().getSelectedNode().attributes.id;
                                        arbolServInt.getLoader().baseParams.abreviatura = arbolServ.getSelectionModel().getSelectedNode().attributes.abreviatura;
                                        arbolServInt.getLoader().baseParams.idproceso = cbProcesos.getValue();
                                        arbolServInt.getLoader().load(arbolServInt.getRootNode());
                                    }
                                }]
                        });
                        winMod.on('show', function() {
                            auxIns2 = false;
                            auxMod2 = false;
                            auxDel2 = false;
                        }, this)
                        winMod.on('hide', function() {
                            auxIns2 = true;
                            auxMod2 = true;
                            auxDel2 = true;
                        }, this)
                    }
                    winMod.add(modiServPresta);
                    winMod.doLayout();
                    winMod.show();
                    //modiServPresta.getForm().loadRecord(arbolServ.getSelectionModel().getSelectedNode().attributes.wsdl);	
                    if (arbolServInt.getSelectionModel().getSelectedNode().attributes.wsdl)
                        Ext.getCmp('wsdl').setValue(arbolServInt.getSelectionModel().getSelectedNode().attributes.wsdl);
                    //console.info(arbolServInt.getSelectionModel().getSelectedNode().attributes);
                }
                break;
        }
    }

    ////---------- Adicionar un servicio qu presta ----------////
    function adicionarServicio(apl) {

        if (regServPresta.getForm().isValid()) {

            regServPresta.getForm().submit({
                url: 'insertarservicio',
                waitMsg: perfil.etiquetas.lbMsgEsperaRegServ,
                params: {idsistema: arbolServ.getSelectionModel().getSelectedNode().attributes.id, idprocess: cbProcesos.getValue(), tabactivo: tpPrincipal.getActiveTab().id}, //arregloservicios:Ext.encode(resultado)},				
                failure: function(form, action) {
                    if (action.result.codMsg != 3) {
                        mostrarMensaje(action.result.codMsg, perfil.etiquetas.lbMsgAddServ);
                        regServPresta.getForm().reset();
                        if (!apl)
                            winIns.hide();
                        stGpServicio.reload();
                        sm.clearSelections();
                        btnModificar.disable();
                        //btnEliminar.disable();
                    }
                    if (action.result.codMsg == 3)
                        mostrarMensaje(action.result.codMsg, action.result.mensaje);
                }
            });

        }
        else
            mostrarMensaje(3, perfil.etiquetas.lbMsgErrorEnCamops);
    }
    var myMask = new Ext.LoadMask(Ext.getBody(), {msg: "Gestionando Servicios...."});

    function adicionarServicioInt() {
        var resultado = new Array();
        var arrayNodos = arbolServInt.getChecked();
        if (arrayNodos.length > 0)
        {
            for (var i = 0; i < arrayNodos.length; i++)
            {
                var result = new Array();
                result[0] = arrayNodos[i].attributes.id;
                result[1] = arrayNodos[i].attributes.text;
                resultado.push(result);
            }
        }
        myMask.show();
        Ext.Ajax.request({
            url: 'insertarservicioInt',
            method: 'POST',
            params: {arraycheck: Ext.encode(resultado), idsistema: arbolServ.getSelectionModel().getSelectedNode().attributes.id, idprocess: cbProcesos.getValue(), tabactivo: tpPrincipal.getActiveTab().id}, //arrayRolesElim:Ext.encode(arrayRolesElim)
            callback: function(options, success, response)
            {
                responseData = Ext.decode(response.responseText);
                if (responseData.codMsg == 1)
                {
                    arbolServInt.getLoader().baseParams.idnodoselected = arbolServ.getSelectionModel().getSelectedNode().attributes.id;
                    arbolServInt.getLoader().baseParams.abreviatura = arbolServ.getSelectionModel().getSelectedNode().attributes.abreviatura;
                    arbolServInt.getLoader().baseParams.idproceso = cbProcesos.getValue();
                    arbolServInt.getLoader().load(arbolServInt.getRootNode());


                }
                myMask.hide();
                if (responseData.codMsg == 3)
                    mostrarMensaje(responseData.codMsg, responseData.mensaje);
            }
        });
        /*}
         else
         mostrarMensaje(3,'Debe seleccionar al menos un servicio');	*/
    }
    ////---------- Modificar un servicio qu presta ----------////
    function modificarServicio() {
        var idmodiaux = "-1";
        var forma = modiServPresta;
        var nodoselected = "-1";
        var nodoselectedparent = "-1";
        nodoselected = arbolServInt.getSelectionModel().getSelectedNode().attributes.text;
        nodoselectedparent = arbolServInt.getSelectionModel().getSelectedNode().parentNode.attributes.text;
        /*if(tpPrincipal.getActiveTab().id == 'tab1')
         {
         nodoselected = arbolServInt.getSelectionModel().getSelectedNode().attributes.text;
         nodoselectedparent = arbolServInt.getSelectionModel().getSelectedNode().parentNode.attributes.text;
         }*/
        /*if(tpPrincipal.getActiveTab().id == 'tab0')
         {
         idmodiaux = sm.getSelected().data.idservicio;
         forma = regServPresta;
         }*/
        if (forma.getForm().isValid()) {
            forma.getForm().submit({
                url: 'modificarservicio',
                waitMsg: perfil.etiquetas.lbMsgEsperaModServ,
                params: {idservicio: idmodiaux, idsistema: arbolServ.getSelectionModel().getSelectedNode().attributes.id,
                    nodoseleccionado: nodoselected, padrenodoseleccionado: nodoselectedparent},
                failure: function(form, action) {
                    if (action.result.codMsg != 3) {
                        winMod.hide();
                        forma.getForm().reset();
                        /*if(tpPrincipal.getActiveTab().id == 'tab0')
                         {											
                         stGpServicio.reload();
                         sm.clearSelections();
                         }*/
                        btnModificar.disable();
                        //btnEliminar.disable();
                        mostrarMensaje(action.result.codMsg, perfil.etiquetas.lbMsgModServ);
                    }
                    if (action.result.codMsg == 3)
                        mostrarMensaje(action.result.codMsg, action.result.mensaje);

                }
            });
        }
        else
            mostrarMensaje(3, perfil.etiquetas.lbMsgErrorEnCamops);
    }
    ////---------- Eliminar un servicio qu presta----------////
    function eliminarServicio() {
        mostrarMensaje(2, perfil.etiquetas.lbMsgDeseaEliminar, elimina);
        function elimina(btnPresionado) {
            if (btnPresionado == 'ok') {
                Ext.Ajax.request({
                    url: 'eliminarservicio',
                    method: 'POST',
                    params: {idservicio: sm.getSelected().data.idservicio},
                    callback: function(options, success, response) {
                        responseData = Ext.decode(response.responseText);
                        if (responseData.codMsg == 1) {
                            mostrarMensaje(responseData.codMsg, perfil.etiquetas.lbMsgDelServ);
                            stGpServicio.reload();
                            sm.clearSelections();
                            btnModificar.disable();
                            //btnEliminar.disable();
                        }
                        if (responseData.codMsg == 3)
                            mostrarMensaje(responseData.codMsg, responseData.mensaje);
                    }
                });
            }
        }
    }


    function damesistemas(nodo)
    {
        if (nodo.parentNode && nodo.parentNode.attributes.idsistema && !existesistema(arraysistemas, nodo.parentNode.attributes.idsistema))
            arraysistemas.push(nodo.parentNode.attributes.idsistema);
        if (nodo.parentNode)
            damesistemas(nodo.parentNode);
        return 0;
    }

    function dameFuncionalidades(idPadre, array)
    {
        var resultado = new Array();
        for (var i = 0; i < array.length; i++)
        {
            if (array[i].parentNode.attributes.idsistema == idPadre)
                resultado.push(array[i].attributes.idfuncionalidad);
        }
        return resultado;
    }

    function existesistema(arraysistemas, sistema)
    {
        for (var f = 0; f < arraysistemas.length; f++)
        {
            if (arraysistemas[f] == sistema)
                return true;
        }
        return false;
    }

    function sistemaEliminar()
    {
        arrayeliminar = [];
        for (i = 0; i < arraytiene.length; i++)
        {
            bandera = false;
            for (j = 0; j < arraysistemas.length; j++)
            {
                if (arraytiene[i] == arraysistemas[j])
                {
                    bandera = true;
                    break;
                }
            }
            if (!bandera)
                arrayeliminar.push(arraytiene[i]);
        }
        return arrayeliminar;
    }



}
