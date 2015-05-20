var perfil = window.parent.UCID.portal.perfil;
perfil.etiquetas = Object();

UCID.portal.cargarEtiquetas('gestdatparametros', cargarInterfaz);

////------------ Inicializo el singlenton QuickTips ------------////
Ext.QuickTips.init();
/*****  Temporal para cargar los combox  *****/
var dataTipoData = [
    ['boolean', 'boolean'],
    ['date', 'date'],
    ['int', 'int'],
    ['time', 'time'],
    ['varchar', 'varchar']
];
var storeDataTipoData = new Ext.data.SimpleStore({
    fields: ['data', 'iddata'],
    data: dataTipoData

});
/*****  EndOF Temporal  *****/

////------------ Declarar variables ------------////
var winIns, winMod, winCamb, tipos, referencia, parametros, funcionalidad;
var auxIns = false;
var auxMod = false;
var auxDel = false;
var auxIns2 = false;
var auxMod2 = false;
var auxDel2 = false;
var auxIns3 = true;
var auxMod3 = true;
var auxDel3 = true;
////------------ Area de Validaciones ------------////

referencia = /(^([a-zA-Z])+([a-zA-Z\d\_]*\.{0,1})|([a-zA-Z])+([a-zA-Z\d\_]*\.{0,1})(\/){0,1}([a-zA-Z])+([a-zA-Z\d\.\-\@\#\_]*))+$/
tipos = /(^([a-zA-Z��������])+([a-zA-Z��������\d\.\-\@\#\_]*))$/;
function cargarInterfaz()
{

    ////------------ Botones ------------////
    btnAdicionar = new Ext.Button({disabled: true, id: 'btnAgrParametros', hidden: true, icon: perfil.dirImg + 'adicionar.png', iconCls: 'btn', text: perfil.etiquetas.lbBtnAdicionar, handler: function() {
            winForm('Ins');
        }});
    btnModificar = new Ext.Button({disabled: true, id: 'btnModParametros', hidden: true, icon: perfil.dirImg + 'modificar.png', iconCls: 'btn', text: perfil.etiquetas.lbBtnModificar, handler: function() {
            winForm('Mod');
        }});
    btnEliminar = new Ext.Button({disabled: true, id: 'btnEliParametros', hidden: true, icon: perfil.dirImg + 'eliminar.png', iconCls: 'btn', text: perfil.etiquetas.lbBtnEliminar, handler: function() {
            eliminarParametro();
        }});
    /*btnAyuda = new Ext.Button({id:'btnAyuParametros', hidden:true, icon:perfil.dirImg+'ayuda.png', iconCls:'btn', text:'<b>'+perfil.etiquetas.lbBtnAyuda+'</b>' });*/
    UCID.portal.cargarAcciones(window.parent.idFuncionalidad);
    ////------------ Arbol Sistemas ------------////
    Ext.define('param', {
        extend: 'Ext.data.Model',
        fields: ['id', 'abreviatura', 'descripcion', 'icono', 'leaf', 'text']
    });

    var sttreeparam = Ext.create('Ext.data.TreeStore', {
        model: 'param',
        idProperty: 'id',
        proxy: {
            type: 'ajax',
            url: 'cargarservicios',
            actionMethods: { //Esta Linea es necesaria para el metodo de llamada POST o GET

                read: 'POST'
            },
            reader: {
                type: 'json'
            }
        }
    }); 
    
    arbolFunc = new Ext.tree.TreePanel({
        title: perfil.etiquetas.lbTitServReg,
        collapsible: true,
        autoScroll: true,
        region: 'west',
        split: true,
        width: '30%',
        store:sttreeparam,
        root: {
            text: perfil.etiquetas.lbRootNodeArbolServ,
            expandable: false,
            //expanded: true,
            id: '0'
        }
    });


    ////------------ Evento para habilitar botones ------------////
    arbolFunc.on('itemclick', function(anode, e) {
        btnModificar.disable();
        btnEliminar.disable();
        btnAdicionar.disable();
        if (anode.node.data.leaf==="")
        {
            gpParametros.enable();
            funcionalidad = anode.node.data.id;
            parametros = anode.node.data.id;
            stGpParametros.removeAll();
            stGpParametros.load({params: {start: 0, limit: 15, idfunciones: anode.node.data.id}});
            btnAdicionar.enable();
            auxIns = true;
        }
        else
        {
            auxIns = false;
            auxMod = false;
            auxDel = false;
        }
        if (anode.node.data.id === 0)
        {
            stGpParametros.removeAll();
            gpParametros.disable();
        }
    }, this);

    ////------------ Store del Grid de Funciones ------------////
    var stGpParametros = new Ext.data.Store({
        fields:
                [
                    {name: 'idfunciones', mapping: 'idfunciones'},
                    {name: 'idparametros', mapping: 'idparametros'},
                    {name: 'denominacion', mapping: 'denominacion'},
                    {name: 'puedesernull', mapping: 'puedesernull'},
                    {name: 'tipoparametro', mapping: 'tipoparametro'},
                    {name: 'valordefecto', mapping: 'valordefecto'},
                    {name: 'descripcion', mapping: 'descripcion'}


                ],
         listeners: {'beforeload': function(thisstore, objeto) {
                objeto.params.idfuncionalidad = parametros;
                objeto.params.idfunciones = funcionalidad;
            }},
        proxy: {
            type: 'ajax',
            url: 'cargarparametros',
            actionMethods: { //Esta Linea es necesaria para el metodo de llamada POST o GET

                read: 'POST'
            },
            reader: {
                totalProperty: 'cantidad_filas',
                root: 'datos',
                id: 'idparametros'
            }
        }
    });
    ////------------ Establesco modo de seleccion de grid (single) ------------////
    sm = Ext.create('Ext.selection.RowModel', {
        mode: 'SINGLE'
    }); 
    sm.on('beforeselect', function(smodel, rowIndex, keepExisting, record) {
        btnModificar.enable();
        btnEliminar.enable();
    }, this);
    ////------------ Defino el grid de Parametros ------------////
    var gpParametros = new Ext.grid.GridPanel({
        frame: true,
        title: perfil.etiquetas.lbTitlbTitParamReg,
        region: 'center',
        disabled: true,
        autoExpandColumn: 'expandir',
        store: stGpParametros,
        selModel: sm,
        columns: [
            {hidden: true, hideable: false, dataIndex: 'idparametros'},
            {hidden: true, hideable: false, dataIndex: 'idfunciones'},
            {header: perfil.etiquetas.lbCampoDenom, width: 100, dataIndex: 'denominacion'},
            {header: perfil.etiquetas.lbCampoTipoParam, width: 150, dataIndex: 'tipoparametro', flex:1},
            {header: perfil.etiquetas.lbCampoValorDefecto, width: 100, dataIndex: 'valordefecto'},
            {header: perfil.etiquetas.lbCampoEsNull, width: 100, dataIndex: 'puedesernull'}
        ],
        loadMask: {store: stGpParametros},
        bbar: new Ext.PagingToolbar({
            pageSize: 15,
            id: 'ptbaux',
            store: stGpParametros,
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
        layout: 'border',
        title: perfil.etiquetas.lbTitGestParam,
        items: [gpParametros, arbolFunc],
        tbar: [btnAdicionar, btnModificar, btnEliminar/*,btnAyuda*/],
        keys: new Ext.KeyMap(document, [{
                key: Ext.EventObject.DELETE,
                fn: function() {
                    if (auxDel && auxDel2 && auxDel3)
                        eliminarParametro();
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
    ////---------- Eventos para hotkeys ----------////
    btnAdicionar.on('show', function() {
        auxIns2 = true;
    }, this)
    btnEliminar.on('show', function() {
        auxDel2 = true;
    }, this)
    btnModificar.on('show', function() {
        auxMod2 = true;
    }, this)
    stGpParametros.on('load', function() {
        if (stGpParametros.getCount() != 0)
        {
            auxMod = true;
            auxDel = true;
        }
        else
        {
            auxMod = false;
            auxDel = false;
        }
    }, this)
    ////------------ Formulario ------------////
    var regParametros = new Ext.FormPanel({
        labelAlign: 'top',
        frame: true,
        bodyStyle: 'padding:5px 5px 0',
        items: [{
                layout: 'column',
                items: [
                    {
                        columnWidth: .5,
                        layout: 'form',
                        items: [
                            {
                                xtype: 'textfield',
                                fieldLabel: perfil.etiquetas.lbCampoDenom,
                                id: 'denominacion',
                                allowBlank: false,
                                blankText: perfil.etiquetas.lbMsgBlankText,
                                maxLength: 30,
                                regex: tipos,
                                regexText: perfil.etiquetas.lbMsgExpRegDenom,
                                tabIndex: 1,
                                anchor: '95%'
                            },
                            {
                                xtype: 'combo',
                                fieldLabel: perfil.etiquetas.lbCampoTipoParam,
                                id: 'tipoparametro',
                                displayField: 'iddata',
                                valueField: 'data',
                                store: dataTipoData,
                                triggerAction: 'all',
                                typeAhead: true,
                                allowBlank: false,
                                blankText: perfil.etiquetas.lbMsgBlankText,
                                mode: 'local',
                                editable: false,
                                emptyText: perfil.etiquetas.lbMsgEmptyText,
                                tabIndex: 3,
                                anchor: '95%'

                            }
                        ]
                    },
                    {
                        columnWidth: .5,
                        layout: 'form',
                        items: [
                            {
                                xtype: 'textfield',
                                fieldLabel: perfil.etiquetas.lbCampoValorDefecto,
                                id: 'valordefecto',
                                name: 'valordefecto',
                                allowBlank: true,
                                blankText: perfil.etiquetas.lbMsgBlankText,
                                regexText: perfil.etiquetas.lbMsgExpRegValDef,
                                //maskRe:/^^[0-9]+$/,
                                //regex:/^^[0-9]+$/,
                                tabIndex: 2,
                                anchor: '95%'
                            }
                        ]

                    },
                    {
                        columnWidth: .5,
                        layout: 'form',
                        style: 'margin-left:10px;margin-top:18px',
                        items: [
                            {
                                boxLabel: perfil.etiquetas.lbCampoEsNull,
                                xtype: 'checkbox',
                                id: 'puedesernull',
                                hideLabel: true,
                                name: 'puedesernull',
                                tabIndex: 4,
                                anchor: '95%'
                            }
                        ]
                    },
                    {
                        columnWidth: 1,
                        layout: 'form',
                        items: [
                            {
                                xtype: 'textarea',
                                fieldLabel: 'Descripci&oacute;n',
                                id: 'descripcion',
                                tabIndex: 5,
                                anchor: '100%'
                            }
                        ]
                    }
                ]
            }]
    });
    ////------------ Cargar la ventana ------------////
    function winForm(opcion) {
        switch (opcion) {
            case 'Ins':
                {
                    if (!winIns)
                    {
                        winIns = new Ext.Window({modal: true, closeAction: 'hide', layout: 'fit',
                            title: perfil.etiquetas.lbTitlbTitAdicionarParam, width: 500, height: 300,
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
                                        adicionarParametro('apl');
                                    }
                                },
                                {
                                    icon: perfil.dirImg + 'aceptar.png',
                                    iconCls: 'btn',
                                    text: perfil.etiquetas.lbBtnAceptar,
                                    handler: function() {
                                        adicionarParametro();
                                    }
                                }]
                        });
                        winIns.on('show', function() {
                            auxIns3 = false;
                            auxMod3 = false;
                            auxDel3 = false;
                        }, this)
                        winIns.on('hide', function() {
                            auxIns3 = true;
                            auxMod3 = true;
                            auxDel3 = true;
                        }, this)
                    }
                    regParametros.getForm().reset();
                    winIns.add(regParametros);
                    winIns.doLayout();
                    winIns.show();
                }
                break;
            case 'Mod':
                {
                    if (!winMod)
                    {
                        winMod = new Ext.Window({modal: true, closeAction: 'hide', layout: 'fit',
                            title: perfil.etiquetas.lbTitModificarParam, width: 500, height: 300,
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
                                        modificarParametro();
                                    }
                                }]
                        });
                        winMod.on('show', function() {
                            auxIns3 = false;
                            auxMod3 = false;
                            auxDel3 = false;
                        }, this)
                        winMod.on('hide', function() {
                            auxIns3 = true;
                            auxMod3 = true;
                            auxDel3 = true;
                        }, this)
                    }
                    regParametros.getForm().reset();
                    winMod.add(regParametros);
                    winMod.doLayout();
                    regParametros.getForm().loadRecord(sm.getSelected());
                    winMod.show();
                }
                break;
        }
    }

    ////------------ Viewport ------------////
    var vpGestFuncionalidad = new Ext.Viewport({
        layout: 'fit',
        items: panel
    })



    ////------------ Adicionar Funcionalidades ------------////
    function adicionarParametro(apl) {
        if (regParametros.getForm().isValid())
        {
            regParametros.getForm().submit({
                url: 'insertarparametro',
                waitMsg: perfil.etiquetas.lbMsgEsperaRegParam,
                params: {idfunciones: arbolFunc.getSelectionModel().getSelectedNode().attributes.id},
                failure: function(form, action) {
                    if (action.result.codMsg != 3)
                    {
                        mostrarMensaje(action.result.codMsg, action.result.mensaje);
                        regParametros.getForm().reset();
                        if (!apl)
                            winIns.hide();
                        stGpParametros.reload();
                        sm.clearSelections();
                        btnModificar.disable();
                        btnEliminar.disable();
                    }
                    if (action.result.codMsg == 3)
                        mostrarMensaje(action.result.codMsg, action.result.mensaje);
                }
            });
        }
        else
            mostrarMensaje(3, perfil.etiquetas.lbMsgErrorEnCamops);
    }

    ////------------ Modififcar Funcionalidad ------------////
    function modificarParametro() {
        if (regParametros.getForm().isValid()) {
            regParametros.getForm().submit({
                url: 'modificarparametro',
                waitMsg: perfil.etiquetas.lbMsgEsperaModParam,
                params: {idparametros: sm.getSelected().data.idparametros, idfunciones: sm.getSelected().data.idfunciones},
                failure: function(form, action) {
                    if (action.result.codMsg != 3) {
                        mostrarMensaje(action.result.codMsg, action.result.mensaje);
                        stGpParametros.reload();
                        winMod.hide();
                    }
                    if (action.result.codMsg == 3)
                        mostrarMensaje(action.result.codMsg, action.result.mensaje);

                }
            });
        }
        else
            mostrarMensaje(3, perfil.etiquetas.lbMsgErrorEnCamops);
    }

    ////------------ Eliminar Funcionalidad ------------////
    function eliminarParametro() {
        mostrarMensaje(2, perfil.etiquetas.lbMsgDeseaEliminar, elimina);
        function elimina(btnPresionado) {
            if (btnPresionado == 'ok')
            {
                Ext.Ajax.request({
                    url: 'eliminarparametro',
                    method: 'POST',
                    params: {idparametros: sm.getSelected().data.idparametros},
                    callback: function(options, success, response) {
                        responseData = Ext.decode(response.responseText);
                        if (responseData.codMsg == 1)
                        {
                            mostrarMensaje(responseData.codMsg, responseData.mensaje);
                            stGpParametros.reload();
                            sm.clearSelections();
                            btnModificar.disable();
                            btnEliminar.disable();
                        }
                        if (responseData.codMsg == 3)
                            mostrarMensaje(responseData.codMsg, responseData.mensaje);
                    }
                });
            }
        }
    }

    ////------------ Buscar Funcionalidad ------------////
    function buscarfuncionalidad(funcionalidad) {
        stGpParametros.load({params: {denominacion: funcionalidad, idsistema: arbolFunc.getSelectionModel().getSelectedNode().attributes.id, start: 0, limit: 20}});
    }
}