var perfil = window.parent.UCID.portal.perfil;
UCID.portal.cargarEtiquetas('gestrol', function() {
    cargarInterfaz();
});

////------------ Inicializo el singlenton QuickTips ------------////
Ext.QuickTips.init();

////------------ Declarar Variables ------------////
var winIns, winMod, winRegular, denrol, idsistema, arraysistemas = [], padre = 0, arbolSistema, arraytiene = [];
var modificar = false;
var auxDelete = true;
var auxIns = false;
var auxMod = false;
var auxDel = false;
var auxReg = false;
var auxMod1 = false;
var auxDel1 = false;
var auxReg1 = false;
var auxIns2 = true;
var auxMod2 = true;
var auxDel2 = true;
var auxReg2 = true;
var auxBus2 = true;
var auxAcept = false;
var auxApl = false;
var arregloDeschequeados = [], arregloChequeados = [];
var auxCanc = false;
letrasnumeros = /(^([a-zA-ZáéíóúñÑ])+([a-zA-ZáéíóúñÑ\d\.\_\s]*))$/;
function cargarInterfaz() {

    ////------------ Botones ------------////
    btnAdicionar = new Ext.Button({
        id: 'btnAgrRol',
        hidden: true,
        icon: perfil.dirImg + 'adicionar.png',
        iconCls: 'btn',
        text: perfil.etiquetas.lbBtnAdicionar,
        handler: function() {
            winForm('Ins');
        }
    });
    btnModificar = new Ext.Button({
        id: 'btnModRol',
        disabled: true,
        hidden: true,
        icon: perfil.dirImg + 'modificar.png',
        iconCls: 'btn',
        text: perfil.etiquetas.lbBtnModificar,
        handler: function() {
            winForm('Mod');
        }
    });
    btnEliminar = new Ext.Button({
        id: 'btnEliRol',
        disabled: true,
        hidden: true,
        icon: perfil.dirImg + 'eliminar.png',
        iconCls: 'btn',
        text: perfil.etiquetas.lbBtnEliminar,
        handler: function() {
            eliminarRol();
        }
    });
    btnRegular = new Ext.Button({
        disabled: true,
        id: 'btnRestRol',
        hidden: true,
        icon: perfil.dirImg + 'restringir.png',
        iconCls: 'btn',
        text: perfil.etiquetas.lbBtnRegularAcciones,
        handler: function() {
            winForm('Reg');
        }
    });
    btnSiguiente = new Ext.Button({
        disabled: true,
        id: 'btnsiguiente',
        margin: '130 15 15 10',
        icon: perfil.dirImg + 'siguiente.png',
        iconCls: 'btn',
        handler: function() {
            eliminaraccion();
        }
    });
    btnAnterior = new Ext.Button({
        disabled: true,
        id: 'btnAnterior',
        margin: '20 15 0 10',
        icon: perfil.dirImg + 'anterior.png',
        iconCls: 'btn',
        handler: function() {
            adicionaraccion();
        }
    });
    btnAyuda = new Ext.Button({
        id: 'btnAyuRol',
        hidden: true,
        icon: perfil.dirImg + 'ayuda.png',
        iconCls: 'btn',
        text: perfil.etiquetas.lbBtnAyuda
    });
    UCID.portal.cargarAcciones(window.parent.idFuncionalidad);
////------------ store del arbol de sistemas ------------////
    Ext.define('Menu', {
        extend: 'Ext.data.Model',
        fields: ['id', 'idsistema', 'text', 'leaf', 'tiene', 'idfuncionalidad']
    });
    var store = Ext.create('Ext.data.TreeStore', {
        model: 'Menu',
        idProperty: 'id',
        proxy: {
            type: 'ajax',
            url: 'cargarsistemafuncionalidades',
            actionMethods: {//Esta Linea es necesaria para el metodo de llamada POST o GET                  
                read: 'POST'
            },
            reader: {
                type: 'json'
            }
        }
    });

////------------Arbol de sistemas ------------////       
    arbolSistema = new Ext.tree.TreePanel({
        title: perfil.etiquetas.lbTitSistemasregistrados,
        autoScroll: true,
        region: 'center',
        split: true,
        width: '37%',
        store: store,
        rootVisible: true,
        root: {
            expanded: true,
            text: perfil.etiquetas.lbTitMsgSubsistemas,
            id: '0'
        },
        listeners: {
            itemclick: function(node, rec, item, index, e) {
            },
            beforeload: function(atreeloader, anode, g) {

                if (modificar) {
                    anode.params.idsistema = anode.node.data.idsistema;
                    anode.params.idrol = sm.getLastSelected().data.idrol;
                    //console.debug(anode.node.data);
                    if (anode.node.data.tiene)
                        arraytiene.push(anode.node.data.idsistema);
                } else {
                    anode.params.id = anode.node.data.id;
                    anode.params.idsistema = anode.node.data.idsistema;

                }
                //anode.params.idrol=sm.getLastSelected().data.idrol
            }
        }

    });
////------------- Store del Grid de Roles -------------- ////
    stGpRol = new Ext.data.Store({
        fields:
                [{
                        name: 'idrol',
                        mapping: 'idrol'
                    },
                    {
                        name: 'descripcion',
                        mapping: 'descripcion'
                    },
                    {
                        name: 'abreviatura',
                        mapping: 'abreviatura'
                    },
                    {
                        name: 'denominacion',
                        mapping: 'denominacion'
                    },
                    {
                        name: 'identidad',
                        mapping: 'identidad'
                    }
                ],
        proxy: {
            type: 'ajax',
            url: 'cargarrol',
            actionMethods: { //Esta Linea es necesaria para el metodo de llamada POST o GET

                read: 'POST'
            },
            reader: {
                totalProperty: "cantidad_filas",
                root: "datos",
                id: "idrol"
            }
        }
    });
////------------ Modo de seleccion de grid (single) ---------////
    sm = Ext.create('Ext.selection.RowModel', {mode: 'SINGLE', allowDeselect: true
    });
    sm.on('beforeselect', function(smodel, record, keepExisting, rowIndex) {
        // alert('beforeselect');
        if (record.data.idrol == 10000000001)
            btnEliminar.disable();
        else
            btnEliminar.enable();
        btnModificar.enable();
        btnRegular.enable();
    }, this);

    sm.on('select', function(smodel, rowIndex, keepExisting, record) {
        // alert('rowselect');
        if (sm.getCount() != 1) {
            btnModificar.disable();
            btnRegular.disable();
        } else {
            btnModificar.enable();
            btnRegular.enable();
        }
    }, this);

    sm.on('deselect', function(smodel, rowIndex, keepExisting, record) {
        if (sm.getCount() != 1) {
            btnModificar.disable();
            btnRegular.disable();
        } else {
            btnModificar.enable();
            btnRegular.enable();
        }
    }, this);

////---------- Defino el grid de roles ----------////
    var gpRol = new Ext.grid.GridPanel({
        frame: true,
        region: 'center',
        iconCls: 'icon-grid',
        header: false,
        autoExpandColumn: 'expandir3',
        store: stGpRol,
        paginate:true,
        selModel: sm,
        columns: [
            {
                hidden: true,
                hideable: false,
                dataIndex: 'idrol'
            },
            {
                id: 'expandir3',
                header: perfil.etiquetas.lbTitMsgDenominacion,
                width: 200,
                dataIndex: 'denominacion',
                flex: 1
            },
            {
                header: perfil.etiquetas.lbTitMsgAbreviatura,
                width: 200,
                dataIndex: 'abreviatura'
            },
            {
                header: perfil.etiquetas.lbTitMsgDescripcion,
                width: 200,
                dataIndex: 'descripcion'
            },
            {
                hidden: true,
                hideable: false,
                dataIndex: 'identidad'
            }
        ],
        loadMask: {
            store: stGpRol
        },
        tbar:
                [
                    new Ext.Toolbar.TextItem({text: perfil.etiquetas.lbTitDenBuscar}),
                    denrol = new Ext.form.TextField({
                        width: 80,
                        id: 'denrol',
                        regex: /(^([a-z_])+([a-z0-9_]*))$/,
                        maskRe: /[a-z0-9_]/i
                    }),
                    new Ext.menu.Separator(),
                    new Ext.Button({
                        icon: perfil.dirImg + 'buscar.png',
                        iconCls: 'btn',
                        text: perfil.etiquetas.lbBtnBuscar,
                        handler: function() {
                            buscarnombrerol(denrol.getValue())
                        }
                    })
                ],
        bbar: new Ext.PagingToolbar({
            pageSize: 15,
            id: 'ptbaux',
            store: stGpRol,
            displayInfo: true,
            displayMsg: perfil.etiquetas.lbTitMsgResultados,
            emptyMsg: perfil.etiquetas.lbTitMsgNingunresultadoparamostrar
        })
    });
////------------ Trabajo con el PagingToolbar ------------////
    Ext.getCmp('ptbaux').on('change', function() {
        sm.select();
    }, this)
////---------- Renderiar el arbol ----------////
    var panel = new Ext.Panel({
        layout: 'border',
        title: perfil.etiquetas.lbTitMsgGestionarRoles,
        items: [gpRol],
        tbar: [btnAdicionar, btnModificar, btnRegular, btnEliminar, /*btnAyuda*/],
        keys: new Ext.KeyMap(document, [{
                key: Ext.EventObject.DELETE,
                fn: function() {
                    if (auxDelete && auxDel && auxDel1 && auxDel2)
                        eliminarRol();
                }

            },
            {
                key: Ext.EventObject.ENTER,
                fn: function() {
                    if (auxAcept)
                        adicionarRol();
                }
            }, /*
             {
             key:"p",
             ctrl:true,
             fn: function(){
             if(auxApl)
             adicionarRol('apl')}
             },*/
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
                        buscarnombrerol(denrol.getValue());
                }
            },
            {
                key: "u",
                alt: true,
                fn: function() {
                    if (auxReg && auxReg1 && auxReg2)
                        winForm('Reg');
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
    stGpRol.load({
        params: {
            start: 0,
            limit: 15
        }
    });
////---------- Eventos para hotkeys ----------////
    btnAdicionar.on('show', function() {
        auxIns = true;
    }, this)
    btnEliminar.on('show', function() {
        auxDel = true;
    }, this)
    btnModificar.on('show', function() {
        auxMod = true;
    }, this)
    btnRegular.on('show', function() {
        auxReg = true;
    }, this)
    Ext.getCmp('denrol').on('focus', function() {
        auxDelete = false;
    }, this)
    Ext.getCmp('denrol').on('blur', function() {
        auxDelete = true;
    }, this)
    stGpRol.on('load', function() {
        if (stGpRol.getCount() != 0)
        {
            auxMod1 = true;
            auxDel1 = true;
            auxReg1 = true;
        }
        else
        {
            auxMod1 = false;
            auxDel1 = false;
            auxReg1 = false;
        }
    }, this)

////---------- Viewport ----------////
    var vpGestRol = new Ext.Viewport({
        layout: 'fit',
        items: panel
    })

////---------- Formulario ----------////
    var regrol = new Ext.FormPanel({
        labelAlign: 'top',
        collapsible: true,
        width: 200,
        region: 'west',
        frame: true,
        bodyStyle: 'padding:5px 5px 0',
        items: [{
                layout: 'column',
                items: [{
                        columnWidth: 1,
                        layout: 'form',
                        margin: '5 5 5 5',
                        border: 0,
                        items: [{
                                xtype: 'textfield',
                                fieldLabel: perfil.etiquetas.lbTitMsgDenominacion,
                                name: 'denominacion',
                                id: 'denominacion',
                                maxLength: 40,
                                labelAlign: 'top',
                                anchor: '100%',
                                allowBlank: false,
                                blankText: perfil.etiquetas.lbMsgEstecampoesrequerido,
                                regex: /(^([a-zA-ZáéíóúñÑ])+([a-zA-ZáéíóúñÑ\d\.\-\@\#\_\s]*))$/
                            },
                            {
                                xtype: 'textfield',
                                fieldLabel: perfil.etiquetas.lbTitMsgAbreviatura,
                                name: 'abreviatura',
                                id: 'abreviatura',
                                maxLength: 40,
                                anchor: '100%',
                                labelAlign: 'top',
                                allowBlank: false,
                                blankText: perfil.etiquetas.lbMsgEstecampoesrequerido,
                                regex: /(^([a-zA-ZáéíóúñÑ])+([a-zA-ZáéíóúñÑ\d\.\-\@\#\_\s]*))$/,
                                regexText: perfil.etiquetas.lbMsgIntrodujovaloresnopermitidos
                            },
                            {
                                xtype: 'textarea',
                                fieldLabel: perfil.etiquetas.lbTitMsgDescripcion,
                                name: 'descripcion',
                                id: 'descripcion',
                                height: 180,
                                labelAlign: 'top',
                                anchor: '100%'
                            }]
                    }]
            }]
    });

/////////////////////////////////////mi mierdita
////------------ Store del Grid de acciones que tiene un usuario ------------////
    var storeTieneAccion = new Ext.data.Store({
        fields: [{
                name: 'idaccion',
                mapping: 'idaccion'
            },
            {
                name: 'denominacion',
                mapping: 'denominacion'
            },
            {
                name: 'idfuncionalidad',
                mapping: 'idfuncionalidad'
            }
        ],
        listeners: {
            beforeload: function(thisstore, objeto, b) {
                objeto.params.idsistema = idi,
                        objeto.params.idrol = idr,
                        objeto.params.idfuncionalidad = idu
            }
        },
        proxy: {
            type: 'ajax',
            url: 'cargaraccionesquetiene',
            actionMethods: {//Esta Linea es necesaria para el metodo de llamada POST o GET
                read: 'POST'
            },
            reader: {
                totalProperty: "cantidad_filas",
                root: "datos",
                id: "ide"
            }
        }
    });
////------------- Modo de seleccion del Grid de acciones que tiene un usuario Ext.create('Ext.selection.RowModel',{mode:'SINGLE', allowDeselect:true});-------------////
    var smtieneaccion = Ext.create('Ext.selection.RowModel', {
        mode: 'MULTI',
        allowDeselect: true
    });
    smtieneaccion.on('beforeselect', function(smodel, rowIndex, keepExisting, record) {
        btnAnterior.disable();
        btnSiguiente.enable();
    }, this);

////------------- Creando el grid de acciones que tiene un usuario -------------////
    var gridtieneaccion = new Ext.grid.GridPanel({
        title: perfil.etiquetas.lbTitAccionesautorizadas,
        frame: true,
        iconCls: 'icon-grid',
        //autoExpandColumn:'expandir',
        margins: '2 2 2 -4',
        store: storeTieneAccion,
        height: 315,
        selModel: smtieneaccion,
        columns: [
            {
                header: perfil.etiquetas.lbDenominacion,
                width: 200,
                dataIndex: 'denominacion'
            },
            {
                hidden: true,
                hideable: false,
                dataIndex: 'idaccion'
            },
            {
                hidden: true,
                hideable: false,
                dataIndex: 'idfuncionalidad'
            }
        ],
        loadMask: {
            store: storeTieneAccion
        }
    });
////------------ Trabajo con el PagingToolbar del grid acciones autorizadas ------------////
//Ext.getCmp('ptbaux2').on('change',function(){
//	smtieneaccion.selectFirstRow();
//},this)
////------------ Store del grid de acciones que no tiene un usuario ------------////
    var storeNoTieneAccion = new Ext.data.Store({
        fields:
                [{
                        name: 'idaccion',
                        mapping: 'idaccion'
                    },
                    {
                        name: 'idfuncionalidad',
                        mapping: 'idfuncionalidad'
                    },
                    {
                        name: 'denominacion',
                        mapping: 'denominacion'
                    }],
        listeners: {
            'beforeload': function(thisstore, objeto, b) {
                objeto.params.idsistema = idi,
                        objeto.params.idrol = idr,
                        objeto.params.idfuncionalidad = idu
            }
        },
        proxy: {
            type: 'ajax',
            url: 'cargaraccionesquenotiene',
            actionMethods: {//Esta Linea es necesaria para el metodo de llamada POST o GET
                read: 'POST'
            },
            reader: {
                totalProperty: "cantidad_filas",
                root: "datos",
                id: "ide"
            }
        }
    });
////------------ Modo de seleccion  del grid de acciones que no tiene un usuario ------------////
    var smnotieneaccion = Ext.create('Ext.selection.RowModel', {
        mode: 'MULTI',
        allowDeselect: true
    });
    smnotieneaccion.on('beforeselect', function(smodel, rowIndex, keepExisting, record) {
        btnAnterior.enable();
        btnSiguiente.disable();
    }, this);

////------------ Creando el grid de acciones que no tiene un usuario ------------////
    var gridnotieneaccion = new Ext.grid.GridPanel({
        title: perfil.etiquetas.lbAccionesnoautorizadas,
        height: 315,
        frame: true,
        iconCls: 'icon-grid',
        autoExpandColumn: 'expandir',
        margins: '2 2 2 -4',
        store: storeNoTieneAccion,
        selModel: smnotieneaccion,
        columns: [
            {
                header: perfil.etiquetas.lbDenominacion,
                width: 200,
                dataIndex: 'denominacion',
                id: 'expandir'
            },
            {
                hidden: true,
                hideable: false,
                dataIndex: 'idaccion'
            },
            {
                hidden: true,
                hideable: false,
                dataIndex: 'idfuncionalidad'
            }
        ],
        loadMask: {
            store: storeNoTieneAccion
        }

    });

////------------ Arbol de sistema y roles para seleccionar acciones ------------////
    var storeAccion = Ext.create('Ext.data.TreeStore', {
        model: 'Menu',
        idProperty: 'id',
        proxy: {
            type: 'ajax',
            url: 'cargarsistemafunc',
            actionMethods: { //Esta Linea es necesaria para el metodo de llamada POST o GET

                read: 'POST'
            },
            reader: {
                type: 'json'

            }
        }
    });

    arbolRestAccion = new Ext.tree.TreePanel({
        title: perfil.etiquetas.lbTitMsgSistemasyroles,
        border: false,
        autoScroll: true,
        region: 'west',
        store: storeAccion,
        rootVisible: true,
        root: {
            expanded: true,
            text: perfil.etiquetas.lbTitMsgSubsistemas,
            id: '0',
            data: []
        },
        width: 200,
        margins: '2 2 2 2',
        listeners: {
            itemclick: function(node1, node, item, index, e) {
                /*
                 console.log(node);
                 console.log(rec);
                 console.log(item);
                 console.log(index);
                 console.log(e);
                 var nd = console.log(rec);
                 */
                if (node.isLeaf())
                {
                    idi = node.data.idsistema;
                    idr = sm.getLastSelected().data.idrol;
                    idu = node.data.idfuncionalidad;
                    storeNoTieneAccion.load({
                        params: {
                            start: 0,
                            limit: 15
                        }
                    });
                    storeTieneAccion.load({
                        params: {
                            start: 0,
                            limit: 15
                        }
                    });
                }
// Ext.Msg.alert(rec.get('text','internalid'));

// console.debug(arbolSistema.getChecked());
            },
            beforeload: function(atreeloader, anode, g) {


                //anode.params={};
                if (sm.getLastSelected())
                {
                    anode.params.idrol = sm.getLastSelected().data.idrol;
                    anode.params.idsistema = anode.node.data.idsistema;
                }


//anode.params.idrol=sm.getLastSelected().data.idrol
            }
        }
    });
    /*,
     loader: new Ext.ComponentLoader({
     listeners:{'beforeload':function(atreeloader, anode)                        
     { 
     atreeloader.baseParams = {};
     if(sm.getSelected())
     { 
     arbolRestAccion.getLoader().baseParams = {idrol:sm.getSelected().data.idrol,idsistema:anode.attributes.idsistema}
     }
     }
     },
     url:'cargarsistemafunc'
     })*/


//    arbolRestAccion.on('itemclick', function (node, e)
//{
//    if (node.isLeaf())
//    {
//        idi=node.data.idsistema;
//        idr=sm.getLastSelected().data.idrol;
//        idu=node.data.idfuncionalidad;
//        storeNoTieneAccion.load({
//            params:{
//                start:0,
//                limit:15
//            }
//        });
//    storeTieneAccion.load({
//        params:{
//            start:0,
//            limit:15
//        }
//    });
//}
//}, this);

////------------ Panel para las acciones ------------////
    var regularAcciones = new Ext.FormPanel({
        labelAlign: 'top',
        frame: true,
        region: 'center',
        height: 370,
        items: [{
                layout: 'column',
                items: [{
                        columnWidth: .45,
                        layout: 'form',
                        items: [gridtieneaccion]
                    },
                    {
                        columnWidth: .10,
                        layout: 'vbox',
                        border: 0,
                        margin: '5 5 5 5',
                        items: [btnSiguiente, btnAnterior],
                        anchor: '100%'
                    },
                    {
                        columnWidth: .45,
                        layout: 'form',
                        items: [gridnotieneaccion]
                    }]
            }]
    });

    panelRestAccion = new Ext.Panel({
        layout: 'border',
        items: [regularAcciones, arbolRestAccion]
    });

////---------- panel ----------////
    var panelAdicionar = new Ext.Panel({
        layout: 'border',
        items: [regrol, arbolSistema]
    });

////---------- Cargar ventanas ----------////
    function winForm(opcion) {
        switch (opcion) {
            case 'Ins':
                {
                    if (!winIns)
                    {
                        winIns = new Ext.Window({
                            modal: true,
                            closeAction: 'hide',
                            layout: 'fit',
                            title: perfil.etiquetas.lbTitAdicionarRol,
                            width: 550,
                            height: 400,
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
                                    icon: perfil.dirImg + 'aplicar.png',
                                    iconCls: 'btn',
                                    text: perfil.etiquetas.lbBtnAplicar,
                                    handler: function() {
                                        adicionarRol('apl');
                                    }
                                },
                                {
                                    icon: perfil.dirImg + 'aceptar.png',
                                    iconCls: 'btn',
                                    text: perfil.etiquetas.lbBtnAceptar,
                                    handler: function() {
                                        adicionarRol();
                                    }
                                }
                            ]
                        });
                        winIns.on('show', function() {
                            auxIns2 = false;
                            auxMod2 = false;
                            auxDel2 = false;
                            auxReg2 = false;
                            auxBus2 = false;
                            auxAcept = true;
                            auxApl = true;
                        }, this)
                        winIns.on('hide', function() {
                            auxIns2 = true;
                            auxMod2 = true;
                            auxDel2 = true;
                            auxReg2 = true;
                            auxBus2 = true;
                            auxAcept = false;
                            auxApl = false;
                        }, this)
                    }
                    arraytiene = [];
                    arraysistemas = [];
                    modificar = false;
                    // arbolSistema.getRootNode().load(); 
                    store.load();
                    regrol.getForm().reset();
                    winIns.add(panelAdicionar);
                    winIns.doLayout();
                    winIns.show();

                }
                break;
            case 'Mod':
                {
                    if (!winMod)
                    {
                        winMod = new Ext.Window({
                            modal: true,
                            closeAction: 'hide',
                            layout: 'fit',
                            title: perfil.etiquetas.lbTitModificarRol,
                            width: 550,
                            height: 400,
                            buttons: [
                                {
                                    icon: perfil.dirImg + 'cancelar.png',
                                    iconCls: 'btn',
                                    text: perfil.etiquetas.lbBtnCancelar,
                                    handler: function() {
                                        winMod.hide();
                                    }
                                },
                                {
                                    icon: perfil.dirImg + 'aceptar.png',
                                    iconCls: 'btn',
                                    text: perfil.etiquetas.lbBtnAceptar,
                                    handler: function() {
                                        modificarRol();
                                    }
                                }
                            ]
                        });
                        winMod.on('show', function() {
                            auxIns2 = false;
                            auxMod2 = false;
                            auxDel2 = false;
                            auxReg2 = false;
                            auxBus2 = false;
                            auxAcept = true;
                            auxApl = true;
                        }, this)
                        winMod.on('hide', function() {
                            auxIns2 = true;
                            auxMod2 = true;
                            auxDel2 = true;
                            auxReg2 = true;
                            auxBus2 = true;
                            auxAcept = false;
                            auxApl = false;
                        }, this)
                    }
                    arraytiene = [];
                    arraysistemas = [];
                    modificar = true;
                    store.load();
                    regrol.getForm().reset();
                    winMod.add(panelAdicionar);
                    winMod.doLayout();
                    winMod.show();
                    regrol.getForm().loadRecord(sm.getLastSelected());
                    denominacionMod = Ext.getCmp('denominacion').getValue();
                    abreviaturaMod = Ext.getCmp('abreviatura').getValue();
                    descripcionMod = Ext.getCmp('descripcion').getValue();
                }
                break;
            case 'Reg':
                {
                    if (!winRegular)
                    {
                        winRegular = new Ext.Window({
                            modal: true,
                            closeAction: 'hide',
                            layout: 'fit',
                            title: perfil.etiquetas.lbBtnRegularacciones,
                            width: 750,
                            height: 400,
                            buttons: [
                                {
                                    icon: perfil.dirImg + 'cancelar.png',
                                    iconCls: 'btn',
                                    text: perfil.etiquetas.lbBtnCancelar,
                                    handler: function() {
                                        winRegular.hide();
                                    }
                                }]
                        });
                        winRegular.on('show', function() {
                            auxIns2 = false;
                            auxMod2 = false;
                            auxDel2 = false;
                            auxReg2 = false;
                            auxBus2 = false;
                        }, this)
                        winRegular.on('hide', function() {
                            auxIns2 = true;
                            auxMod2 = true;
                            auxDel2 = true;
                            auxReg2 = true;
                            auxBus2 = true;
                        }, this)
                    }
                    winRegular.add(panelRestAccion);
                    winRegular.doLayout();
                    winRegular.show();
                    storeTieneAccion.removeAll();
                    storeNoTieneAccion.removeAll();
                    storeAccion.load();
                    arbolRestAccion.expandPath(arbolRestAccion.getRootNode().getPath());
                }
                break;
        }
    }

////---------- Adicionar Rol ----------////    
    function adicionarRol(apl)
    {
        if (regrol.getForm().isValid())
        {
            var resultado = new Array();
            var arrPadres = new Array();
            var arrayNodos = arbolSistema.getChecked();
            if (arrayNodos.length > 0)
            {
                for (var i = 0; i < arrayNodos.length; i++)
                {
                    if (!existesistema(arrPadres, arrayNodos[i].parentNode.data.idsistema))
                    {
                        var result = new Array();
                        arrPadres.push(arrayNodos[i].parentNode.data.idsistema);
                        result[0] = arrayNodos[i].parentNode.data.idsistema;
                        result[1] = dameFuncionalidades(arrayNodos[i].parentNode.data.idsistema, arrayNodos);
                        resultado.push(result);
                        damesistemas(arrayNodos[i]);
                    }
                }

                regrol.getForm().submit({
                    url: 'insertarrol',
                    params: {
                        arraysistfun: Ext.encode(resultado),
                        arraysist: Ext.encode(arraysistemas)
                    },
                    failure: function(form, action)
                    {
                        if (action.result.codMsg != 3)
                        {
                            if (!apl)
                                winIns.hide();
                            stGpRol.load();
                            arraytiene = [];
                            arraysistemas = [];
                            regrol.getForm().reset();
                            btnModificar.disable();
                            btnEliminar.disable();
                        }

                    }
                });
            }
            else
                mostrarMensaje(3, perfil.etiquetas.withOutFunctions);
        }
        else
            mostrarMensaje(3, perfil.etiquetas.lbMsgErrorEnCamops);
        arregloChequeados = [];
        arregloDeschequeados = [];
    }
////---------- Eliminar Rol ----------////
    function eliminarRol()
    {
        var arrRolesElim = sm.getSelection();
        var arrayRolesElim = [];
        for (var i = 0; i < arrRolesElim.length; i++)
            arrayRolesElim.push(arrRolesElim[i].data.idrol);

        mostrarMensaje(2, perfil.etiquetas.lbMsgEliminar, elimina);
        // Ext.MessageBox.alert('Titulo',perfil.etiquetas.lbMsgEliminar);
        function elimina(btnPresionado) {
            if (btnPresionado == 'ok')
            {
                Ext.Ajax.request({
                    url: 'eliminarrol',
                    method: 'POST',
                    params: {
                        arrayRolesElim: Ext.encode(arrayRolesElim)
                    },
                    callback: function(options, success, response)
                    {
                        responseData = Ext.decode(response.responseText);
                        if (responseData.codMsg == 1)
                        {

                            stGpRol.load();
                            sm.clearSelections();
                            btnModificar.disable();
                            btnEliminar.disable();

                        }

                    }
                });
            }
        }
    }

////---------- Modificar Rol ----------////
    function modificarRol(apl)
    {
        if (regrol.getForm().isValid())
        {
            var dMod = denominacionMod != Ext.getCmp('denominacion').getValue();
            var aMod = abreviaturaMod != Ext.getCmp('abreviatura').getValue();
            var desMod = descripcionMod != Ext.getCmp('descripcion').getValue();
            var arrPadres = new Array();
            var arrayNodos = arbolSistema.getChecked();
            if (arrayNodos.length > 0)
            {
                for (var i = 0; i < arrayNodos.length; i++)
                {
                    if (!existesistema(arrPadres, arrayNodos[i].parentNode.data.idsistema))
                    {
                        arrPadres.push(arrayNodos[i].parentNode.data.idsistema);
                        damesistemas(arrayNodos[i]);
                    }
                }
            }
            var cambio = false;
            if(arregloChequeados || arregloDeschequeados)
            if (arregloChequeados.length != 0 || arregloDeschequeados.length != 0)
                cambio = true;
            if (dMod || aMod || desMod || cambio) {
                regrol.getForm().submit(
                        {
                            url: 'modificarrol',
                            waitMsg: perfil.etiquetas.lbModifying,
                            params: {
                                arraysistfun: Ext.encode(arregloChequeados),
                                arraysist: Ext.encode(arraysistemas),
                                arrayeliminar: Ext.encode(arregloDeschequeados),
                                idrol: sm.getLastSelected().data.idrol
                            },
                            failure: function(form, action)
                            {
                                if (action.result.codMsg != 3)
                                {
                                    if (!apl) {
                                        winMod.hide();
                                    }

                                    resultado = {};
                                    stGpRol.load();
                                    arraytiene = [];
                                    arraysistemas = [];
                                    btnModificar.disable();
                                    btnEliminar.disable();
                                }
                            }
                        });
            }
            else
                mostrarMensaje(3, perfil.etiquetas.NoModify);
        }
        else
            mostrarMensaje(3, perfil.etiquetas.lbMsgErrorEnCamops);
        arregloChequeados = [];
        arregloDeschequeados = [];
    }

    arbolSistema.on('checkchange', function(node, e) {
        if (node.data.checked) {
            var estaenDeschequeados = estaEnArreglo(arregloDeschequeados,
                    node.parentNode.data.idsistema, node.data.idfuncionalidad);
            if (estaenDeschequeados != -1)
                eliminarDeArreglo(arregloDeschequeados, estaenDeschequeados);

            adicionarEnArreglo(arregloChequeados,
                    node.parentNode.data.idsistema, node.data.idfuncionalidad);
        }
        else {
           
            var estaenChequeados = estaEnArreglo(arregloChequeados,
                    node.parentNode.data.idsistema, node.data.idfuncionalidad);

            if (estaenChequeados != -1)
                eliminarDeArreglo(arregloChequeados, estaenChequeados);

            adicionarEnArreglo(arregloDeschequeados,
                    node.parentNode.data.idsistema, node.data.idfuncionalidad);
        }

    }, this);
    
     function estaEnArreglo(arreglonodos, idsistema, idfuncionalidad) {
        if(arreglonodos)
        for (var i = 0; i < arreglonodos.length; i++) {
            if (arreglonodos[i][0] == idsistema) {
                for (var j = 0; j < arreglonodos[i][1].length; j++) {
                    if (arreglonodos[i][1][j] == idfuncionalidad) {
                        return i + "-" + j;
                    }
                }
            }
        }
        return -1;
    }
    
    
    function eliminarDeArreglo(arreglo, pos) {
        var posminus = pos.indexOf('-');
        var i = pos.substring(0, posminus);
        var j = pos.substring(posminus + 1, pos.length);
        arreglo[i][1].splice(j, 1);
        if (arreglo[i].length == 2 && arreglo[i][1].length == 0) {
            arreglo.splice(i, 1);
        }
    }
    
    function adicionarEnArreglo(arreglo, idsistema, idfuncionalidad) {
        var add = false;
        if (estaEnArreglo(arreglo, idsistema, idfuncionalidad) == -1) {
            if(arreglo)
            for (var i = 0; i < arreglo.length; i++) {
                if (arreglo[i][0] == idsistema) {
                    arreglo[i][1].push(idfuncionalidad);
                    add = true;
                }
            }
            if (!add) {
                arreglo.push([idsistema]);
                arreglo[arreglo.length - 1].push([idfuncionalidad]);
            }
        }
    }



    function damesistemas(nodo)
    {
        if (nodo.parentNode && nodo.parentNode.data.idsistema && !existesistema(arraysistemas, nodo.parentNode.data.idsistema))
            arraysistemas.push(nodo.parentNode.data.idsistema);
        if (nodo.parentNode)
            damesistemas(nodo.parentNode);
        return 0;
    }
   
  

    function dameFuncionalidades(idPadre, array)
    {
        var resultado = new Array();
        for (var i = 0; i < array.length; i++)
        {
            if (array[i].parentNode.data.idsistema == idPadre)
                resultado.push(array[i].data.idfuncionalidad);
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

////------------ Adicionar Acciones ------------////
    function adicionaraccion()
    {
        var arrAccs = smnotieneaccion.getSelection();
        var arrAcc = [];
        for (var i = 0; i < arrAccs.length; i++)
            arrAcc.push(arrAccs[i].data.idaccion);

        if (regularAcciones.getForm().isValid())
        {
            regularAcciones.getForm().submit({
                url: 'adicionaraccion',
                waitMsg: perfil.etiquetas.lbAddingAction,
                params: {
                    idsistema: idi,
                    idfuncionalidad: idu,
                    idrol: idr,
                    idaccion: Ext.encode(arrAcc)
                },
                failure: function(form, action)
                {
                    if (action.result.tiene == 1)
                    {
                        storeNoTieneAccion.load({
                            params: {
                                start: 0,
                                limit: 15
                            }
                        });
                        storeTieneAccion.load({
                            params: {
                                start: 0,
                                limit: 15
                            }
                        });
                    }
                }
            });
        }
        else
            mostrarMensaje(3, perfil.etiquetas.lbMsgErrorEnCamops);
    }

////------------ Eliminar Acciones ------------////
    function eliminaraccion()
    {
        var arrAccs = smtieneaccion.getSelection();
        var arrAcc = [];
        for (var i = 0; i < arrAccs.length; i++)
            arrAcc.push(arrAccs[i].data.idaccion);

        if (regularAcciones.getForm().isValid())
        {
            regularAcciones.getForm().submit({
                url: 'eliminaraccion',
                waitMsg: perfil.etiquetas.lbDeleAction,
                params: {
                    idsistema: idi,
                    idfuncionalidad: idu,
                    idrol: idr,
                    idaccion: Ext.encode(arrAcc)
                },
                failure: function(form, action)
                {
                    if (action.result.tiene == 1)
                    {
                        storeNoTieneAccion.load({
                            params: {
                                start: 0,
                                limit: 15
                            }
                        });
                        storeTieneAccion.load({
                            params: {
                                start: 0,
                                limit: 15
                            }
                        });
                    }
                }
            });
        }
    }

////------------ Para marcar un solo nodo ------------////
    function marcauno(node, idnode)
    {
        var arraynodos = node.parentNode.childNodes;
        if (node.data.checked)
        {
            for (var i = 0; i < arraynodos.length; i++)
            {
                if (arraynodos[i].id != idnode)
                    cambiarEstadoCheck(arraynodos[i], false);
                else
                    cambiarEstadoCheck(arraynodos[i], true);
            }
        }
    }

////------------ Auxiliar para marcar y desmarcar nodos ------------////
    function cambiarEstadoCheck(anodehijo, check)
    {
        if (anodehijo.data.checked != check)
        {
            anodehijo.getUI().toggleCheck(check);
            anodehijo.data.checked = check;
            banderaClick = false;
            anodehijo.fireEvent('checkchange', anodehijo, check);
        }
    }

////------------ obtener sistemas a eliminar ------------//// 
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

////------------ buscar roles por denominacion ------------////
    function buscarnombrerol(denrol)
    {
        stGpRol.load({
            params: {
                denrol: denrol,
                start: 0,
                limit: 15
            }
        });
    }
    function IsInstalacionSelected() {
        var arrRolesElim = sm.getSelections();
        for (var i = 0; i < arrRolesElim.length; i++) {
            if (arrRolesElim[i].data.idrol == 10000000001) {
                return true;
            }
        }
        return false;
    }
}


