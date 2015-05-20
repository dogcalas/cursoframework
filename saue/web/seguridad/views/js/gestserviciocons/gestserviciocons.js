var perfil = window.parent.UCID.portal.perfil;

perfil.etiquetas = Object();
UCID.portal.cargarEtiquetas('gestserviciocons', cargarInterfaz);

// 3. Inicializo el singlenton QuickTips
Ext.QuickTips.init();


////------------ Declarar variables ------------////
var sistema, nodeFlag;
var idsistema;
var winIns;
var auxIns = false;
var auxDel = false;
var auxIns2 = false;
var auxDel2 = false;
var auxIns3 = true;
var auxDel3 = true;

function cargarInterfaz() {
    btnAdicionar = new Ext.Button({disabled: true, id: 'btnAgrServCons', hidden: true, icon: perfil.dirImg + 'adicionar.png', iconCls: 'btn', text: perfil.etiquetas.lbBtnAdicionar, handler: function() {
            winIns.show();
        }});
    btnEliminar = new Ext.Button({disabled: true, id: 'btnEliServCons', hidden: true, icon: perfil.dirImg + 'eliminar.png', iconCls: 'btn', text: perfil.etiquetas.lbBtnEliminar, handler: function() {
            eliminarServicio();
        }});
    /*btnAyuda = new Ext.Button({id:'btnAyuServCons', hidden:true, icon:perfil.dirImg+'ayuda.png', iconCls:'btn', text:perfil.etiquetas.lbBtnAyuda });*/
    UCID.portal.cargarAcciones(window.parent.idFuncionalidad);
    ////------------ Arbol de sistemas ------------////
    Ext.define('consume', {
        extend: 'Ext.data.Model',
        fields: ['id', 'abreviatura', 'descripcion', 'icono', 'leaf', 'text']
    });

    var sttreeconsume = Ext.create('Ext.data.TreeStore', {
        autoLoad: false,
        model: 'consume',
        idProperty: 'id',
        proxy: {
            type: 'ajax',
            url: 'cargarsistemas',
            actionMethods: { //Esta Linea es necesaria para el metodo de llamada POST o GET

                read: 'POST'
            },
            reader: {
                type: 'json'
            }
        }
    });
    arbolSistema = new Ext.tree.TreePanel({
        title: perfil.etiquetas.lbTitArbolSistemas,
        collapsible: true,
        autoScroll: true,
        region: 'west',
        split: true,
        width: '30%',
        margins: '2 0 2 2',
        store: sttreeconsume,
        root: {
            text: perfil.etiquetas.lbTitRootNodeArbolSubsist,
            expandable: false,
            expanded: true,
            id: '0'
        }
    });

    ////------------ Evento para habilitar botones ------------////
    arbolSistema.on('itemclick', function(anode, e) {
        sistema = anode.node.data.id;
        idsistema = anode.node.data.id;
        btnEliminar.disable();
        btnAdicionar.disable();
        storeGrid.removeAll();
        if (anode.node.data.leaf==="")
        {
            grid.enable();
            storeGrid.removeAll();
            storeGrid.load({params: {start: 0, limit: 15, idsistema: anode.node.data.id}});
            storeConsSrv.removeAll();
            nodeFlag = anode.node.data.id;
            storeConsSrv.load({params: {idsistema: anode.node.data.id}});
            btnAdicionar.enable();
            auxIns = true;
            auxDel = true;
        }
        else
        {
            auxDel = false;
            auxIns = false;
        }
        if (anode.node.data.id == 0)
            grid.disable();
    }, this);

    ////------------ Store del Grid ------------////
       var storeGrid = new Ext.data.Store({
        fields:
                [
                    {name: 'denominacion', mapping: 'denominacion'},
                    {name: 'subsistema', mapping: 'subsistema'},
                    {name: 'proceso', mapping: 'proceso'},
                    {name: 'protocolo', mapping: 'protocolo'}
                ],
        listeners: {'beforeload': function(thisstore, objeto) {
                objeto.params.idsistema = sistema
            }},
        proxy: {
            type: 'ajax',
            url: 'cargarserviciocons',
            actionMethods: { //Esta Linea es necesaria para el metodo de llamada POST o GET

                read: 'POST'
            },
            reader: {
                totalProperty: "cantidad_filas",
                root: "datos",
                id: "ide"
            }
        }
    });
     ////------------ Modo seleccion del Grid ------------////
    var sm = Ext.create('Ext.selection.RowModel', {
        mode: 'SINGLE'
    });

    sm.on('beforeselect', function(smodel, rowIndex, keepExisting, record) {
        btnEliminar.enable();
    }, this);
    ////----------- Grid ------------////
    var grid = new Ext.grid.GridPanel({
        title: perfil.etiquetas.lbTitServiciosConsume,
        region: 'center',
        frame: true,
        disabled: true,
        autoExpandColumn: 'expandir',
        margins: '2 2 2 -4',
        store: storeGrid,
        selModel: sm,
        columns: [
            {header: perfil.etiquetas.lbDenominacion, width: 150, dataIndex: 'denominacion'},
            {header: perfil.etiquetas.lbSubsistema, width: 150, dataIndex: 'subsistema'},
            {header: perfil.etiquetas.lbProtocolo, width: 150, dataIndex: 'protocolo'},
            {header: perfil.etiquetas.lbProceso, width: 200, dataIndex: 'proceso', flex:1}
        ],
        loadMask: {store: storeGrid},
        bbar: new Ext.PagingToolbar({
            pageSize: 15,
            id: 'ptbaux',
            store: storeGrid,
            displayInfo: true,
            displayMsg: perfil.etiquetas.lbMsgPaginado,
            emptyMsg: perfil.etiquetas.lbMsgEmpty
        })
    });
    ////------------ Trabajo con el PagingToolbar ------------////
    Ext.getCmp('ptbaux').on('change', function() {
        sm.select();
    }, this);

    ////------------ Panel con los componentes ------------////
    var panel = new Ext.Panel({
//			title:perfil.etiquetas.lbTitGestServCons,
        layout: 'border',
        //renderTo: 'panel',
        items: [grid, arbolSistema],
        tbar: [btnAdicionar, btnEliminar/*,btnAyuda*/],
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
                        winIns.show();
                }
            }])
    });
    ////---------- Eventos para hotkeys ----------////
    btnAdicionar.on('show', function() {
        auxIns2 = true;
    }, this)
    btnEliminar.on('show', function() {
        auxDel2 = true;
    }, this)
    storeGrid.on('load', function() {
        if (storeGrid.getCount() != 0)
            auxDel = true;
        else
            auxDel = false;
    }, this)
    ////------------ Viewport ------------////
    var vpGestSistema = new Ext.Viewport({
        layout: 'fit',
        items: panel
    });
    var storeConsSrv = new Ext.data.Store({
        fields:
                [
                    {name: 'idproceso', mapping: 'idproceso'},
                    {name: 'denominacion', mapping: 'denominacion'}
                ],
        listeners: {'beforeload': function(thisstore, objeto) {
                objeto.params.idsistema = idsistema
            }},
        proxy: {
            type: 'ajax',
            url: 'cargarprocesos',
            actionMethods: { //Esta Linea es necesaria para el metodo de llamada POST o GET

                read: 'POST'
            },
            reader: {
               totalProperty: "cantidad_filas",
                root: "datos"
            }
        }
    });

    var protocolostore = new Ext.data.Store({
        fields:
               [
            {name: 'idprotocolo', mapping: 'idprotocolo'},
            {name: 'denominacion', mapping: 'denominacion'}
        ],
        proxy: {
            type: 'ajax',
            url: 'cargarprotocolos',
            actionMethods: { //Esta Linea es necesaria para el metodo de llamada POST o GET

                read: 'POST'
            },
            reader: {
                totalProperty: "cantidad_filas",
                root: "datos"
            }
        }
    });

    protocolostore.load();
      Ext.define('IOC', {
        extend: 'Ext.data.Model',
        fields: ['id', 'abreviatura', 'descripcion', 'icono', 'leaf', 'text']
    });

    var sttreeIOC = Ext.create('Ext.data.TreeStore', {
        autoLoad: false,
        model: 'IOC',
        idProperty: 'id',
        proxy: {
            type: 'ajax',
            url: 'cargarIOC',
            actionMethods: { //Esta Linea es necesaria para el metodo de llamada POST o GET

                read: 'POST'
            },
            reader: {
                type: 'json'
            }
        }
    });
    
    arbolIOC = new Ext.tree.TreePanel({
        title: 'Servicios',
        collapsible: true,
        autoScroll: true,
        region: 'west',
        split: true,
        width: '30%',
        margins: '2 0 2 2',
        store:sttreeIOC,
        root:{
            text: "IOC",
            expandable: false,
            expanded: true,
            id: '0'
        }
    });

    formConsServ = new Ext.FormPanel({
//					labelAlign: 'top',
        region: 'center',
        frame: true,
        bodyStyle: 'padding:5px 5px 0',
        items: [{
                layout: 'column',
                items: [{
                        columnWidth: 1,
                        layout: 'form',
                        items: [new Ext.form.ComboBox({
                                id: 'comboproceso',
                                emptyText: 'seleccione proceso',
                                editable: false,
                                fieldLabel: 'Proceso',
                                store: storeConsSrv,
                                valueField: 'denominacion',
                                displayField: 'denominacion',
                                hiddenName: 'idproceso',
                                forceSelection: true,
                                typeAhead: true,
                                mode: 'local',
                                allowBlank: false,
                                triggerAction: 'all',
                                selectOnFocus: true,
                                anchor: '100%'
                            })]
                    },
                ]
            }, {
                layout: 'column',
                items: [{
                        columnWidth: 1,
                        layout: 'form',
                        items: [new Ext.form.ComboBox({
                                id: 'comboprotocolo',
                                emptyText: 'seleccione protocolo',
                                editable: false,
                                fieldLabel: 'Protocolo',
                                store: protocolostore,
                                valueField: 'denominacion',
                                displayField: 'denominacion',
                                hiddenName: 'idprotocolo',
                                forceSelection: true,
                                typeAhead: true,
                                mode: 'local',
                                allowBlank: false,
                                triggerAction: 'all',
                                selectOnFocus: true,
                                anchor: '100%'
                            })]
                    },
                ]
            }]
    });
    winIns = new Ext.Window({
        modal: true,
        closeAction: 'hide',
        layout: 'border',
        title: perfil.etiquetas.lbTitAdicionarServCons,
        width: 800,
        height: 500,
        items: [arbolIOC, formConsServ],
        buttons: [
            {
                icon: perfil.dirImg + 'cancelar.png',
                iconCls: 'btn',
                text: perfil.etiquetas.lbBtnCancelar,
                handler: function() {
                    winIns.hide();
                }
            },
            {
                icon: perfil.dirImg + 'aceptar.png',
                iconCls: 'btn',
                text: perfil.etiquetas.lbBtnAceptar,
                handler: function() {
                    adicionarServicio();
                }
            }]
    });
    winIns.on('show', function() {
        auxIns3 = false;
        auxDel3 = false;
    }, this);

    winIns.on('hide', function() {
        auxIns3 = true;
        auxDel3 = true;
//                                                                formConsServ.getForm().reset();
    }, this);
    ////------------ Cargar ventanas ------------////







    ////------------ Funcion Adicionar ------------////
    function adicionarServicio(apl) {
        nodo = arbolIOC.getSelectionModel().selNode;
        if (formConsServ.getForm().isValid() && nodo.leaf) {
            servicio = nodo.text;
            subsistema = nodo.parentNode.text;
            proceso = Ext.getCmp('comboproceso').getValue();
            protocolo = Ext.getCmp('comboprotocolo').getValue();
            Ext.Ajax.request({
                url: 'insertarserviciocons',
                method: 'POST',
                params: {
                    idsistema: idsistema,
                    servicio: servicio,
                    subsistema: subsistema,
                    proceso: proceso,
                    protocolo: protocolo},
                callback: function() {
                    arbolIOC.collapseAll();
                    formConsServ.getForm().reset();
                    winIns.hide();
                    arbolIOC.getSelectionModel().clearSelections();
                    arbolIOC.collapseAll();
                    storeGrid.reload();
                    sm.clearSelections();
                    //btnModificar.disable();
                    btnEliminar.disable();

                }
            });

        }
    }

    ////------------ Funcion eliminar ------------////
    function eliminarServicio() {
        mostrarMensaje(2, perfil.etiquetas.lbMsgDeseaEliminar, elimina);
        function elimina(btnPresionado) {
            if (btnPresionado == 'ok')
            {
                Ext.Ajax.request({
                    url: 'eliminarserviciocons',
                    method: 'POST',
                    params: {idsistema: idsistema, servicio: sm.getSelected().data.denominacion},
                    callback: function(options, success, response) {
                        responseData = Ext.decode(response.responseText);
                        if (responseData.codMsg == 1)
                        {
                            mostrarMensaje(responseData.codMsg, responseData.mensaje);
                            storeGrid.reload();
                            storeConsSrv.load({params: {idsistema: nodeFlag}});
                            sm.clearSelections();
                            btnEliminar.disable();
                            auxIns = false;
                            auxDel = false;
                        }
                        if (responseData.codMsg == 3)
                            mostrarMensaje(responseData.codMsg, responseData.mensaje);
                    }
                });
            }
        }
    }

}