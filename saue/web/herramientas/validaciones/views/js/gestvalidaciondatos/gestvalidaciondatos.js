var perfil = window.parent.UCID.portal.perfil;
UCID.portal.cargarEtiquetas('gestvalidaciondatos', function(){
    cargarInterfaz();
});

////------------ Inicializo el singlenton QuickTips ------------////
Ext.QuickTips.init();

var btnAdicionar,btnModificar,btnEliminar,btnAyuda;
var btnBuscar;
var arbolSistema,padreSistema,arbolmetodos,padremetodos,sm,stGpValidaciones,panel_datos,gpValidaciones,fpValidaciones,panel,vpGestValid;
var fpAddValidaciones;
var winIns,winMod;
var auxtfnom = true, auxcbTipo = true, auxcbTipoE = true, auxcbNullE = true,uri=0,urip=0;
var auxfsNotN = true;

var auxfsNotNcol = true, auxfsNotNexp;
var path;

function cargarInterfaz(){

    btnAdicionar = new Ext.Button({
        disabled:true, 
        id:'btnAgrSist', 
        hidden:false, 
        icon:perfil.dirImg+'adicionar.png', 
        iconCls:'btn', 
        text:'Adicionar', 
        handler:function(){
            winForm('Ins');
        }
    });
    btnModificar = new Ext.Button({
        disabled:true, 
        id:'btnModSist', 
        hidden:false, 
        icon:perfil.dirImg+'modificar.png', 
        iconCls:'btn', 
        text:'Modificar', 
        handler:function(){
            winForm('Mod');
        }
    });
    btnEliminar = new Ext.Button({
        disabled:true, 
        id:'btnEliSist', 
        hidden:false, 
        icon:perfil.dirImg+'eliminar.png', 
        iconCls:'btn', 
        text:'Eliminar',
        handler:function(){
            eliminarValidacion();
        }
    });
    //btnAyuda = new Ext.Button({id:'btnAyuComp', hidden:false, icon:perfil.dirImg+'ayuda.png', iconCls:'btn', text:'Ayuda'});
    btnBuscar = new Ext.Button({
        disabled:true,
        icon:perfil.dirImg+'buscar.png',
        iconCls:'btn',
        text:'Buscar'/*perfil.etiquetas.lbBtnBuscar*/, 
        handler:function(){
            buscarvalidacion(validacion.getValue());
        }
    })
    //UCID.portal.cargarAcciones(window.parent.idFuncionalidad);


    ////------------ Arbol de sistemas ------------////
    arbolSistema = new Ext.tree.TreePanel({
        title:'Subsistemas registrados',
        collapsible:true,
        autoScroll:true,
        //root:padreSistema,
        region:'west',
        split:true,
        width:'25%',
        loader: new Ext.tree.TreeLoader({
            dataUrl:'cargarsistema',
            listeners:{
                'beforeload':function(atreeloader, anode){ 
                    atreeloader.baseParams = {};  
                    if(anode.attributes.id != 0)
                    {									   
                        atreeloader.baseParams.id = anode.attributes.id;
                        atreeloader.baseParams.text = anode.attributes.text;
                        atreeloader.baseParams.leaf = anode.attributes.leaf;
                        atreeloader.baseParams.path = anode.attributes.path;
                        atreeloader.baseParams.component = anode.attributes.component;	                                      
                    }                                  	                                               									
                }
            }
        })
    });

    ////------------ Crear nodo padre del arbol ------------////
    padreSistema = new Ext.tree.AsyncTreeNode({
        text: 'Subsistemas',
        animate:false,
        //layoutConfig:{animate:false   },
        draggable:false,
        expandable:false,
        expanded:true,
        id:'0'
    });

    ////------------ Crear lista de hijos ------------////
    arbolSistema.setRootNode(padreSistema);

    ////------------ Evento para habilitar botones ------------////
    arbolSistema.on('click', function (node, e){
        sistemaseleccionado = node.id;
        urip= node.attributes.path;
        sistemas =  node;
        bandera =0;
        if(node.id!=0){
            idsistema = node.id;
            nodoSeleccionado = node;
            //btnModificar.enable();
            //btnEliminar.enable();
            btnAdicionar.enable();
            btnModificar.disable();
            btnEliminar.disable();
            gpValidaciones.enable();
            stGpValidaciones.load({
                params:{
                    idsistema:sistemaseleccionado,
                    start:0,
                    limit:10,
                    path:urip
                }
            });
            stTipo.load({
                params:{
                    idsistema:sistemaseleccionado,
                    path:urip
                }
            });
            stTipoE.load({
                params:{
                    idsistema:sistemaseleccionado,
                    path:urip
                }
            });
            stNullE.load({
                params:{
                    idsistema:sistemaseleccionado,
                    path:urip
                }
            });
            btnBuscar.enable();
        }
        else {
            //btnAdicionar.enable();
            btnModificar.disable();
            btnEliminar.disable();
        }
    }, this);

    ////------------ Arbol de controladores ------------////
    arbolmetodos = new Ext.tree.TreePanel({
        title:'M&eacute;todos a validar',
        collapsible:true,
        autoScroll:true,
        frame:true,
        region:'west',
        split:true,
        width:'60%',
        height:'100%',
        loader: new Ext.tree.TreeLoader({
            dataUrl:'cargarmetodos',
            listeners:{
                'beforeload':function(atreeloader, anode) {
                    if(anode.attributes.id) {
                        atreeloader.baseParams.nodo = arbolSistema.getSelectionModel().getSelectedNode().attributes.id;
                        atreeloader.baseParams.type = anode.attributes.type;
                        atreeloader.baseParams.path = arbolSistema.getSelectionModel().getSelectedNode().attributes.path;
                    }
                },
                load:function(){
                    if(auxiliar != false)
                    {
                        var clas = sm.getSelected().get('controlador');
                        var node = arbolmetodos.getRootNode().findChild('text',clas);
                    }
                    else
                    {
                        return;
                    }
                    if(node != undefined)
                    {
                        node.expand();
                        var metodo = sm.getSelected().get('accion');
                        var n = node.findChild('text',metodo);
                        if(n != undefined)
                        {
                            n.select();
                        }
                    }
                }
            }
        })

    });

    ////------------ Crear nodo padre del arbol ------------////
    padremetodos = new Ext.tree.AsyncTreeNode({
        text: 'Controladores',
        animate:false,
        draggable:false,
        expandable:false,
        expanded:true,
        id:'0',
        type:'folder'
    });

    arbolmetodos.setRootNode(padremetodos);

    ////------------ Evento para habilitar botones ------------////
    arbolmetodos.on('click', function (node, e){
        sistemaseleccionado = node.id;
        uri=node.attributes.path;
        sistemas =  node;
        bandera =0;
        if(node.leaf){
            idsistema = node.id;
            nodoSeleccionado=node;
            tfnom.enable();
            cbTipo.enable();
            cbTipoE.enable();
            fsNotN.enable();
            cbNullE.enable();
        }
        else
        {
            tfnom.disable();
            cbTipo.disable();
            cbTipoE.disable();
            fsNotN.disable();
            cbNullE.disable();
        }
    }, this);

    ////------------ Store del Grid de Funcionalidades ------------////
    stGpValidaciones =  new Ext.data.GroupingStore({
        proxy: new Ext.data.HttpProxy({
            url: 'cargarvalidaciones'
        }),
        listeners:{
            'beforeload':function(thisstore, objeto){
                objeto.params.idsistema = sistemaseleccionado,
                objeto.params.path = urip
            }
        },
        reader:new Ext.data.JsonReader({
            totalProperty: "cantidad_filas",
            root: "datos",
            id: "idvalidacion"
        },
        [
        {
            name:'controlador_accion',
            mapping:'controlador_accion'
        },
        {
            name:'controlador',
            mapping:'controlador'
        },
        {
            name:'accion',
            mapping:'accion'
        },
        {
            name:'nombre',
            mapping:'nombre'
        },
        {
            name:'nombre_tipo',
            mapping:'nombre_tipo'
        },
        {
            name:'not_null',
            mapping:'not_null'
        },
        {
            name:'null_error',
            mapping:'null_error'
        },
        {
            name:'codigo',
            mapping:'codigo'
        },
        ]),
        sortInfo:{
            field: 'nombre', 
            direction: "ASC"
        },
        groupField:'controlador_accion'
    });

    ////------------ Establesco modo de seleccion de grid (single) ------------////
    sm = new Ext.grid.RowSelectionModel({
        singleSelect:true
    });
    sm.on('rowselect', function (smodel, rowIndex, keepExisting, record){
        btnAdicionar.disable();
        btnModificar.enable();
        btnEliminar.enable();
        auxDel = true;
        auxMod = true;
        auxBus = true;
    }, this);

    ////------------ Defino el grid de Funcionalidades ------------////
    gpValidaciones = new Ext.grid.GridPanel({
        frame:true,
        region:'center',
        height:'100%',
        id:'gpVal',
        width:'100%',
        iconCls:'icon-grid',
        disabled:true,
        autoExpandColumn:'expandir',
        store:stGpValidaciones,
        sm:sm,
        columns: [
        {
            hidden: true, 
            header:'Controlador/Acci&oacute;n', 
            sortable:true, 
            width:300, 
            dataIndex: 'controlador_accion'
        },

        {
            hidden: true, 
            header:'Controlador', 
            sortable:true, 
            width:300, 
            dataIndex: 'controlador'
        },

        {
            hidden: true, 
            header:'Acci&oacute;n', 
            sortable:true, 
            width:300, 
            dataIndex: 'accion'
        },

        {
            id: 'expandir',
            header: 'Nombre', 
            sortable:true, 
            width:300, 
            dataIndex: 'nombre'
        },

        {
            header: 'Tipo', 
            sortable:true, 
            width:300, 
            dataIndex: 'nombre_tipo'
        },

        {
            header: 'Not_null', 
            sortable:true, 
            width:300, 
            dataIndex: 'not_null'
        },

        {
            header: 'Null_error', 
            sortable:true, 
            width:200, 
            dataIndex: 'null_error'
        },

        {
            header: 'Tipo_error', 
            sortable:true, 
            width:300, 
            dataIndex: 'codigo'
        },
        ],
        view: new Ext.grid.GroupingView({
            forceFit:true,
            groupTextTpl: '{text} ({[values.rs.length]} {[values.rs.length > 1 ? "Controlador/Acci&oacute;n" : "Controlador/Acci&oacute;n"]})'
        }),
        loadMask:{
            store:stGpValidaciones
        },
        tbar:[
        new Ext.Toolbar.TextItem({
            text:'Nombre de la validaci&oacute;n: '/*perfil.etiquetas.lbBuscarFunc*/
        }),
        validacion = new Ext.form.TextField({
            name: 'nombrevalidacion',
            //allowBlank:false,
            width:150,
            id: 'nombrevalidacion',
            regex:/^([a-zA-Z_\xf3\xf1\xe1\xe9\xed\xfa\xd1\xc1\xc9\xcd\xd3\xda\xfc\s]+ ?[a-zA-Z_\s]*)+$/,
            maskRe:/^([a-zA-Z_\xf3\xf1\xe1\xe9\xed\xfa\xd1\xc1\xc9\xcd\xd3\xda\xfc\s]+ ?[a-zA-Z_\s]*)+$/
        }),
        new Ext.menu.Separator(),
        btnBuscar
        ]
    });
		

    ////------------------ Store del combobox de Tipo de error -----------------////
    var stTipoE =  new Ext.data.Store({
        url: 'cargarcombotipoerror',
        //autoLoad:true,
        reader:new Ext.data.JsonReader({
            root:'datos'
        },
        [
        {
            name: 'nombre'
        },

        {
            name:'codigo'
        }
        ])
    });

    ////------------------ Store del combobox de Tipo -----------------////
    var stTipo =  new Ext.data.Store({
        url: 'cargarcombotipo',
        // autoLoad:true,
			   
        reader:new Ext.data.JsonReader({
            root:'datos'
        },
        [
        {
            name:'nombre_tipo'
        }
        ])
    });

    ////------------------ Store del combobox de Null error -----------------////
    var stNullE =  new Ext.data.Store({
        url: 'cargarcombonullerror',
        //autoLoad:true,
        reader:new Ext.data.JsonReader({
            root:'datos'
        },
        [
        {
            name: 'nombre'
        },

        {
            name:'codigo'
        }
        ])
    });

    var tfnom = new Ext.form.TextField({
        name: 'nombre',
        fieldLabel: 'Nombre',
        anchor: '100%',
        allowBlank:false,
        regex:/^([a-zA-Z_\xf3\xf1\xe1\xe9\xed\xfa\xd1\xc1\xc9\xcd\xd3\xda\xfc\s]+ ?[a-zA-Z_\s]*)+$/,
        maskRe:/^([a-zA-Z_\xf3\xf1\xe1\xe9\xed\xfa\xd1\xc1\xc9\xcd\xd3\xda\xfc\s]+ ?[a-zA-Z_\s]*)+$/,
        maxLength:255,
        disabled:true
    });

    ////------------------ Combobox de Tipo -----------------////
    var cbTipo = new Ext.form.ComboBox({
        fieldLabel:'Tipo',
        allowBlank:false,
        displayField:'nombre_tipo',
        hiddenName:'nombre_tipo',
        valueName:'nombre_tipo',
        store:stTipo,
        mode:'local',
        anchor:'100%',
        emptyText:'Seleccione..',
        triggerAction:'all',
        editable:false,
        selectOnFocus:true,
        disabled:true
    })

    ////------------------ Combobox de Tipo de error -----------------////
    var cbTipoE = new Ext.form.ComboBox({
        fieldLabel:'Tipo de error',
        allowBlank:false,
        displayField:'codigo',
        hiddenName:'codigo',
        valueName:'codigo',
        store:stTipoE,
        mode:'local',
        anchor:'100%',
        emptyText:'Seleccione...',
        triggerAction:'all',
        editable:false,
        selectOnFocus:true,
        disabled:true
    })

    ////------------------ Combobox de Null error -----------------////
    var cbNullE = new Ext.form.ComboBox({
        fieldLabel:'Null_error',
        allowBlank:false,
        displayField:'codigo',
        hiddenName:'null_error',
        valueField:'codigo',
        store:stNullE,
        mode:'local',
        anchor:'100%',
        emptyText:'Seleccione...',
        triggerAction:'all',
        editable:false,
        selectOnFocus:true
    })

    var fsNotN = new Ext.form.FieldSet({
        //xtype:'fieldset',
        id:'not_null',
        checkboxToggle:true,
        title:'Not_null',
        autoHeight:true,
        defaultType:'combobox',
        collapsed:false,
        disabled:true,
        items:[cbNullE]
    })

    fpAddValidaciones = new Ext.FormPanel({
        labelAlign: 'top',
        frame:true,
        title:'Datos de la validaci&oacute;n',
        width:'30%',
        region:'center',
        bodyStyle:'padding:5px 5px 0',
        items: [{
            layout:'column',
            items:[{
                columnWidth:1,
                layout:'form',
                items:[tfnom,cbTipo,cbTipoE,fsNotN]
            }]
        }]
    });

    var paneladd = new Ext.Panel({
        layout:'border',
        border:false,
        items:[arbolmetodos,fpAddValidaciones]
    });


    panel = new Ext.Panel({
        layout:'border',
        items:[arbolSistema,gpValidaciones],
        tbar:[btnAdicionar,btnModificar,btnEliminar]
    });

    vpGestValid = new Ext.Viewport({
        layout:'fit',
        items:panel
    });

    function winForm(opcion){
        switch(opcion){
            case 'Ins':{
                if(!winIns)
                {
                    winIns = new Ext.Window({
                        modal: true,
                        closeAction:'hide',
                        layout:'fit',
                        title:'Adicionar validaci&oacute;n',
                        width:900,
                        height:500,
                        listeners:{
                            beforeshow:function(){
                                //tfdenom.enable();
                                arbolmetodos.enable();
                                fpAddValidaciones.getForm().reset();
                                arbolmetodos.getRootNode().reload();
                                tfnom.disable();
                                cbTipo.disable();
                                cbTipoE.disable();
                                cbNullE.disable();
                            //arbolmetodosval.getRootNode().reload();
                            }
                        },
                        buttons:[
                        {
                            icon:perfil.dirImg+'cancelar.png',
                            iconCls:'btn',
                            text:'Cancelar',
                            handler:function(){
                                winIns.hide();
                                fpAddValidaciones.getForm().reset();
                                arbolmetodos.getRootNode().reload()
                            }
                        },
                        {
                            icon:perfil.dirImg+'aplicar.png',
                            iconCls:'btn',
                            text:'Aplicar',
                            handler:function(){
                                adicionarValidacion('apl');
                            }
                        },
                        {
                            icon:perfil.dirImg+'aceptar.png',
                            iconCls:'btn',
                            text:'Aceptar',
                            handler:function(){
                                adicionarValidacion();
                            }
                        }]
                    });
                    winIns.on('show',function(){
                        auxiliar = false;
                    },this);
                }
                //arbolmetodos.getRootNode().reload();
                fpAddValidaciones.getForm().reset();
                winIns.add(paneladd);
                winIns.doLayout();
                winIns.show();
                                        
            }
            break;
            case 'Mod':{
                if(!winMod)
                {
                    winMod= new Ext.Window({
                        modal: true,
                        closeAction:'hide',
                        layout:'fit',
                        title:'Modificar validaci&oacute;n'/*perfil.etiquetas.lbTitModificarFun*/,
                        width:900,
                        height:500,
                        listeners:{
                            beforeshow:function(){
                                //tfdenom.enable();
                                arbolmetodos.enable();
                                fpAddValidaciones.getForm().reset();
                                arbolmetodos.getRootNode().reload();
                                 btnAdicionar.enable();
                                  btnModificar.disable();
									btnEliminar.disable();
                                                                 
                            //Ext.getCmp(fsNotN.getId()).collapsed = false;
                            //arbolmetodosval.getRootNode().reload();
                            }
                        },
                        buttons:[
                        {
                            icon:perfil.dirImg+'cancelar.png',
                            iconCls:'btn',
                            text:'Cancelar',
                            handler:function(){
                                winMod.hide();
                                fpAddValidaciones.getForm().reset();
                                arbolmetodos.getRootNode().reload()
                            }
                        },
                        {
                            icon:perfil.dirImg+'aceptar.png',
                            iconCls:'btn',
                            text:'Aceptar',
                            handler:function(){
                                modificarValidacion();
                            }
                        }]
                    });
                    winMod.on('show',function(){
                        auxiliar = true;
                    },this);
                }
                winMod.add(paneladd);
                winMod.doLayout();
                winMod.show();
                tfnom.disable();
                cbTipo.enable();
                cbTipoE.enable();
                cbNullE.enable();
                fsNotN.enable();
                fpAddValidaciones.getForm().loadRecord(sm.getSelected());
                arbolmetodos.disable();
                //alert(sm.getSelected().get('not_null'));
                //alert(sm.getSelected().data.not_null);
                if (sm.getSelected().data.not_null == 'true') {
                    //alert('putaaaaaa');
                    fsNotN.expand(false);
                }
                else
                    fsNotN.collapse(false);
                                        
                tfnom.on('change', function(){
                    auxtfnom = false;
                });
                cbTipo.on('change', function(){
                    auxcbTipo = false;
                });
                cbTipoE.on('change', function(){
                    auxcbTipoE = false;
                });
                cbNullE.on('change', function(){
                    auxcbNullE = false;
                });
                fsNotN.on('collapse', function(){
                    auxfsNotNcol = false;
                });
            /*fsNotN.on('beforecollapse', function(){
                                            auxfsNotNcol = false;
                                        });
                                        fsNotN.on('beforeexpand', function(){
                                            auxfsNotNcol = true;
                                            //auxfsNotNexp = false;
                                        });*/
            }
        }
    }

    function adicionarValidacion(apl){
        tfnom.setValue(limpiarEspacios(tfnom.getValue()));
        if (Ext.getCmp(fsNotN.getId()).collapsed == true)
        {
            cbNullE.allowBlank = true;
            if ((arbolmetodos.getSelectionModel().getSelectedNode() != null) && (fpAddValidaciones.getForm().isValid()) && (cbTipo.getValue() != '')/* && (Ext.getCmp(fsNotN.getId()).collapsed == true)*/)
            {
                auxfsNotN = true;
                var idnodopadre = arbolmetodos.getSelectionModel().getSelectedNode().parentNode.attributes.id;
                var node = arbolmetodos.getSelectionModel().getSelectedNode();
                var nombrehijo = node.text;
                cbNullE.reset();
                var not_null = false;

                fpAddValidaciones.getForm().submit({
                    url:'adicionarvalidacion',
                    waitMsg:perfil.etiquetas.lbMsgEsperaRegFun,
                    params:{
                        idsistema:arbolSistema.getSelectionModel().getSelectedNode().attributes.id, 
                        nombreclase: idnodopadre, 
                        nombremetodo:nombrehijo, 
                        not_null:not_null,
                        path:urip
                    },
                    failure: function(form, action){
                        if(action.result.codMsg != 3)
                        {
                            mostrarMensaje(action.result.codMsg,action.result.mensaje);
                            fpAddValidaciones.getForm().reset();
                            if(!apl)
                                winIns.hide();
                            fpAddValidaciones.getForm().reset();
                            sistemaseleccionado = arbolSistema.getSelectionModel().getSelectedNode().attributes.id;
                            stGpValidaciones.reload();
                            sm.clearSelections();
                            btnModificar.disable();
                            btnEliminar.disable();

                        }
                        if(action.result.codMsg == 3) mostrarMensaje(action.result.codMsg,action.result.mensaje);
                    }
                });
            }
            else
                mostrarMensaje(3,'No se pueden dejar campos vacios.');
        }
        else
        {
            cbNullE.allowBlank = false;
            if ((arbolmetodos.getSelectionModel().getSelectedNode() != null) && (fpAddValidaciones.getForm().isValid()) && (cbTipo.getValue() != '')/* && (Ext.getCmp(fsNotN.getId()).collapsed == true)*/)
            {
                idnodopadre = arbolmetodos.getSelectionModel().getSelectedNode().parentNode.attributes.id;
                node = arbolmetodos.getSelectionModel().getSelectedNode();
                nombrehijo = node.text;
                not_null = true;

                fpAddValidaciones.getForm().submit({
                    url:'adicionarvalidacion',
                    waitMsg:perfil.etiquetas.lbMsgEsperaRegFun,
                    params:{
                        idsistema:arbolSistema.getSelectionModel().getSelectedNode().attributes.id, 
                        nombreclase: idnodopadre, 
                        nombremetodo:nombrehijo, 
                        not_null:not_null,
                        path:urip
                    },
                    failure: function(form, action){
                        if(action.result.codMsg != 3)
                        {
                            mostrarMensaje(action.result.codMsg,action.result.mensaje);
                            fpAddValidaciones.getForm().reset();
                            if(!apl)
                                winIns.hide();
                            fpAddValidaciones.getForm().reset();
                            sistemaseleccionado = arbolSistema.getSelectionModel().getSelectedNode().attributes.id;
                            stGpValidaciones.reload();
                            sm.clearSelections();
                            btnModificar.disable();
                            btnEliminar.disable();
                        }
                        if(action.result.codMsg == 3) mostrarMensaje(action.result.codMsg,action.result.mensaje);
                    }
                });
            }
            else
                mostrarMensaje(3,'No se pueden dejar campos vacios.');
        }			
    }

    ////------------ Modififcar Funcionalidad ------------////
    function modificarValidacion(){
        tfnom.setValue(limpiarEspacios(tfnom.getValue()));
        if (Ext.getCmp(fsNotN.getId()).collapsed == true)
        {
            cbNullE.allowBlank = true;
            if (auxtfnom == false || auxcbNullE == false || auxcbTipo == false || auxcbTipoE == false || auxfsNotNcol == false) 
            {
                auxtfnom = true;
                auxcbTipo = true;
                auxcbTipoE = true;
                auxcbNullE = true;
                auxfsNotNcol = true;
                //if ((arbolmetodos.getSelectionModel().getSelectedNode() != null) && (fpAddValidaciones.getForm().isValid()))
                //{
                    var padresel = sm.getSelected().get('controlador');
                    var hijosel = sm.getSelected().get('accion');
                    var nombval = sm.getSelected().get('nombre');
                    cbNullE.reset();
                    var not_null = false;

                    fpAddValidaciones.getForm().submit({
                        url:'modificarvalidacion',
                        waitMsg:perfil.etiquetas.lbMsgEsperaModFun,
                        params:{
                            idsistema:arbolSistema.getSelectionModel().getSelectedNode().attributes.id, 
                            padresel:padresel, 
                            hijosel:hijosel, 
                            not_null:not_null, 
                            nombre:nombval,
                            path:urip
                        },
                        failure: function(form, action){
                            if(action.result.codMsg != 3){
                                mostrarMensaje(action.result.codMsg,action.result.mensaje);
                                stGpValidaciones.reload();
                                winMod.hide();
                            }
                            if(action.result.codMsg == 3) mostrarMensaje(action.result.codMsg,action.result.mensaje);
                        }
                    });
                //}
                //else
                   // mostrarMensaje(3,'No se pueden dejar campos vacios.');
            }
            else
                winMod.hide();
        }
        else
        {
            cbNullE.allowBlank = false;
            if (auxtfnom == false || auxcbNullE == false || auxcbTipo == false || auxcbTipoE == false || auxfsNotNcol == false)
            {
                auxtfnom = true;
                auxcbTipo = true;
                auxcbTipoE = true;
                auxcbNullE = true;
                auxfsNotNcol = true;
                //auxfsNotNexp = true;
                //if ((arbolmetodos.getSelectionModel().getSelectedNode() != null) && (fpAddValidaciones.getForm().isValid()) && (cbTipo.getValue() != ''))
                //{
                    var padreselec = sm.getSelected().get('controlador');
                    var hijoselec = sm.getSelected().get('accion');
                    var nombvald = sm.getSelected().get('nombre');
                    var not_nullt = true;

                    fpAddValidaciones.getForm().submit({
                        url:'modificarvalidacion',
                        waitMsg:perfil.etiquetas.lbMsgEsperaModFun,
                        params:{
                            idsistema:arbolSistema.getSelectionModel().getSelectedNode().attributes.id, 
                            padresel:padreselec, 
                            hijosel:hijoselec, 
                            not_null:not_nullt, 
                            nombre:nombvald,
                            path:urip
                        },
                        failure: function(form, action){
                            if(action.result.codMsg != 3){
                                mostrarMensaje(action.result.codMsg,action.result.mensaje);
                                stGpValidaciones.reload();
                                winMod.hide();
                            }
                            if(action.result.codMsg == 3) mostrarMensaje(action.result.codMsg,action.result.mensaje);

                        }
                    });
                //}
                //else
                    //mostrarMensaje(3,'No se pueden dejar campos vacios.');
            }
            else
                winMod.hide();
                                
        }
    }

    ////-----------Funcion Eliminar Validaci�n------------////
    function eliminarValidacion(){
        var idsistema = arbolSistema.getSelectionModel().getSelectedNode().attributes.id;
        var padresel = sm.getSelected().get('controlador');
        var hijosel = sm.getSelected().get('accion');
        var nombval = sm.getSelected().get('nombre');
        path=urip;
        /*var claseval = sm.getSelected().get('clase');
		var metodoval = sm.getSelected().get('metodo');*/
        mostrarMensaje(2,'&iquest;Est&aacute; seguro que desea eliminar la validaci&oacute;n?',elimina);
        function elimina(btnPresionado){
            if (btnPresionado == 'ok')
            {
                Ext.Ajax.request({
                    url: 'eliminarvalidacion',
                    waitMsg:'Espere por favor...',
                    params:{
                        idsistema:idsistema, 
                        padresel:padresel, 
                        hijosel:hijosel, 
                        nombreval:nombval,
                        path:urip/*, claseval:claseval, metodoval:metodoval*/
                    },
                    callback: function (options,success,response){
                        btnAdicionar.enable();
                          btnModificar.disable();
            btnEliminar.disable();
                        mostrarMensaje(1,"La validaci&oacute;n fue eliminada correctamente.");
                        //Ext.Msg.alert("Informaci&oacute;n","La validaci&oacute;n fue eliminada correctamente.");
                        stGpValidaciones.reload();
                    }
                });
            }
        }
    }

    ////------------ Buscar Funcionalidad ------------////
    function buscarvalidacion(valid){
//        validacion.setValue(limpiarEspacios(validacion.getValue()));
        var val = valid;
        //var idsistema = arbolSistema.getSelectionModel().getSelectedNode().attributes.id;
        validacion.reset();
        stGpValidaciones.removeAll();
        stGpValidaciones.load({
            params:{
                idsistema:sistemas.attributes.id, 
                validacion:val, 
                limit:20, 
                start:0
            }
        });
    }

    function limpiarEspacios(s){
        var before = s;
        s = s.replace(/^\s+|\s+$/gi,''); //sacar espacios blanco principio y final
        s = s.replace(/\s+/gi,'_'); //sacar espacios repetidos dejando solo uno
        return s;
    }
}
