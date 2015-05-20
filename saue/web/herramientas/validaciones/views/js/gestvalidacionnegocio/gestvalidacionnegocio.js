var perfil = window.parent.UCID.portal.perfil;		
UCID.portal.cargarEtiquetas('gestvalidacionnegocio', function(){
    cargarInterfaz();
});
		
////------------ Inicializo el singlenton QuickTips ------------////
Ext.QuickTips.init();



var btnAdicionar,btnModificar,btnEliminar,btnAyuda;
var btnBuscar;
var arbolSistema,padreSistema,arbolmetodos,padremetodos,sm,stGpValidaciones,panel_datos,gpValidaciones,fpValidaciones,panel,vpGestValid;
var fpAddValidaciones, uri=0;
var winIns,winMod;
var auxiliar;
var auxcbError = true, auxdescrip = true, auxarbmetval = true;

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
        sistemas =  node;
        uri=node.attributes.path;
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
                    start:0,
                    limit:10
                }
            });
            stError.load({
                params:{
                    idsistema:sistemaseleccionado,
                    path:uri
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
        width:'35%',
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
                    //alert(auxiliar);
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
                        //  alert(metodo);
                        //  alert(clas);
                        if(n != undefined)
                        {
                            n.select();
                        // storeg.load({params:{metodow:metodo,clasew:clas}});
                        //alert(metodow);
                        //  alert(clasew);

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
        //layoutConfig:{animate:false   },
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
        sistemas =  node;
        bandera =0;
        if(node.leaf){
            idsistema = node.id;
            nodoSeleccionado=node;					
            arbolmetodosval.enable();                
        }
        else
            arbolmetodosval.disable();  
    }, this);
	
    ////------------ Arbol de validaciones ------------////
    arbolmetodosval = new Ext.tree.TreePanel({
        title:'M&eacute;todos de validaci&oacute;n',
        collapsible:true,
        autoScroll:true,
        frame:true,
        region:'center',
        split:true,
        disabled:true,
        width:'30%',
        height:'100%',
        loader: new Ext.tree.TreeLoader({
            dataUrl:'cargararbolvalidaciones',
            listeners:{
                'beforeload':function(atreeloader, anode) {
                    if(anode.attributes.id) {
						
                        atreeloader.baseParams.nodo = arbolSistema.getSelectionModel().getSelectedNode().attributes.id;
                        atreeloader.baseParams.type = anode.attributes.type;
                        atreeloader.baseParams.path = arbolSistema.getSelectionModel().getSelectedNode().attributes.path;	
                    }
                },
                load:function(){
                    //alert(auxiliar);
                    if(auxiliar != false)
                    {
                        var clas = sm.getSelected().get('clase');
                        var node = arbolmetodosval.getRootNode().findChild('text',clas);
                    }
                    else
                    {
                        return;
                    }
                    if(node != undefined)
                    {
                        node.expand();
                        var metodo = sm.getSelected().get('metodo');
                        var n = node.findChild('text',metodo);
                        //  alert(metodo);
                        //  alert(clas);
                        if(n != undefined)
                        {
                            n.select();
                        // storeg.load({params:{metodow:metodo,clasew:clas}});
                        //alert(metodow);
                        //  alert(clasew);

                        }
                    }
                }
            }
        })
		
    });
	
    ////------------ Crear nodo padre del arbol ------------////
    padremetodosval = new Ext.tree.AsyncTreeNode({
        text: 'Validaciones',
        animate:false,
        //layoutConfig:{animate:false   },
        draggable:false,
        expandable:false,
        expanded:true,
        id:'0',
        type:'folder'
    });
	
    arbolmetodosval.setRootNode(padremetodosval);
	
    ////------------ Evento para habilitar botones ------------////
    arbolmetodosval.on('click', function (node, e){
        sistemaseleccionado = node.id;
        sistemas =  node;
        bandera =0;
        if(node.leaf){
            idsistema = node.id;
            nodoSeleccionado=node;					
            fieldset_datos.enable();                
        }
        else
        {
            fieldset_datos.disable();
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
                objeto.params.path = uri
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
            name:'denominacion',
            mapping:'denominacion'
        },
        {
            name:'clase',
            mapping:'clase'
        },
        {
            name:'metodo',
            mapping:'metodo'
        },
        {
            name:'codigo',
            mapping:'codigo'
        },
        {
            name:'descripcion',
            mapping:'descripcion'
        },
        ]),
        sortInfo:{
            field: 'denominacion', 
            direction: "ASC"
        },
        groupField:'controlador_accion'
    });
			
    ////------------ Establesco modo de seleccion de grid (single) ------------////
    sm = new Ext.grid.RowSelectionModel({
        singleSelect:true
    });
    sm.on('beforerowselect', function (smodel, rowIndex, keepExisting, record){
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
            header: 'Nombre', 
            sortable:true, 
            width:300, 
            dataIndex: 'denominacion'
        },

        {
            header: 'Clase', 
            sortable:true, 
            width:300, 
            dataIndex: 'clase'
        },

        {
            header: 'M&eacute;todo', 
            sortable:true, 
            width:300, 
            dataIndex: 'metodo'
        },

        {
            header: 'Error', 
            sortable:true, 
            width:200, 
            dataIndex: 'codigo'
        },

        {
            id:'expandir', 
            header: 'Descripci&oacute;n', 
            sortable:true, 
            width:300, 
            dataIndex: 'descripcion'
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
            text:'Nombre de la Validaci&oacute;n: '/*perfil.etiquetas.lbBuscarFunc*/
        }),
        validacion = new Ext.form.TextField({
            name: 'nombrevalidacion',
            //allowBlank:false,
            width:150,
            id: 'nombrevalidacion',
            regex:/([a-zA-Z_\xf3\xf1\xe1\xe9\xed\xfa\xd1\xc1\xc9\xcd\xd3\xda\xfc\s]+ ?[a-zA-Z\s]*)+$/,
            maskRe:/([a-zA-Z_\xf3\xf1\xe1\xe9\xed\xfa\xd1\xc1\xc9\xcd\xd3\xda\xfc\s]+ ?[a-zA-Z\s]*)+$/
        }),
        new Ext.menu.Separator(),			
        btnBuscar]
    });	

    var stError = new Ext.data.Store({
        url:'cargarcomboerror',
        reader:new Ext.data.JsonReader({
            root:'datos'
        },
        [
        {
            name:'nombre'
        },

        {
            name:'codigo'
        }
        ])
    })

    var tfdenom = new Ext.form.TextField({
        name: 'denominacion',
        fieldLabel: 'Nombre',
        enableKeyEvents:true,
        anchor: '97%',
        allowBlank:false,
        regex:/^([a-zA-Z_\xf3\xf1\xe1\xe9\xed\xfa\xd1\xc1\xc9\xcd\xd3\xda\xfc\s]+ ?[a-zA-Z_\s]*)+$/,
        maskRe:/^([a-zA-Z_\xf3\xf1\xe1\xe9\xed\xfa\xd1\xc1\xc9\xcd\xd3\xda\xfc\s]+ ?[a-zA-Z_\s]*)+$/,
        maxLength:255
    });

    var cbError = new Ext.form.ComboBox({
        fieldLabel:'Error',
        allowBlank:false,
        displayField:'codigo',
        hiddenName:'codigo',
        valueName:'codigo',
        store:stError,
        mode:'local',
        anchor:'100%',
        emptyText:'Seleccione...',
        triggerAction:'all',
        forceSelection:true,
        editable:false,
        typeAhead:true,
        selectOnFocus:true
    })

    var descrip = new Ext.form.TextArea({
        fieldLabel:'Descripci&oacute;n',
        name: 'descripcion',
        anchor:'97%',
        region:'south'
    })

    var fieldset_datos = new Ext.form.FieldSet({
        title: 'Introduzca los datos',
        width:'92%',
        region:'center',
        disabled:true,
        autoHeight: true,
        items:[tfdenom,cbError,descrip]
    })
	
    fpAddValidaciones = new Ext.FormPanel({
        labelAlign: 'top',
        frame:true,
        title:'Datos de la validaci&oacute;n',
        width:'35%',
        region:'east',
        bodyStyle:'padding:5px 5px 0',
        items: [{
            layout:'column',
            items:[{
                columnWidth:1,
                layout:'form',
                items:[fieldset_datos]
            }]
        }]
    });

    var paneladd = new Ext.Panel({
        layout:'border',
        border:false,
        items:[arbolmetodos,arbolmetodosval,fpAddValidaciones]
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
                                tfdenom.enable();
                                arbolmetodos.enable();
                                fpAddValidaciones.getForm().reset();
                                arbolmetodos.getRootNode().reload();
                                arbolmetodosval.getRootNode().reload();
                                fieldset_datos.disable();
                                arbolmetodosval.disable();
                                
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
                                arbolmetodos.getRootNode().reload();
                                arbolmetodosval.getRootNode().reload()
                                
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
                                fpAddValidaciones.getForm().reset();
                                arbolmetodos.getRootNode().reload();
                                arbolmetodosval.getRootNode().reload();
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
                                arbolmetodos.getRootNode().reload();
                                arbolmetodosval.getRootNode().reload()
                                
                            }
                        },
                        {	
                            icon:perfil.dirImg+'aceptar.png',
                            iconCls:'btn',
                            text:'Aceptar',
                            handler:function(){
                                modificarValidacion();
                                btnAdicionar.enable();
                                  btnModificar.disable();
                                btnEliminar.disable();
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
                fpAddValidaciones.getForm().loadRecord(sm.getSelected());
                fieldset_datos.enable();
                arbolmetodos.disable();
                arbolmetodosval.enable();
                tfdenom.disable();
                cbError.on('change', function(){
                    auxcbError = false;
                });
                descrip.on('change', function(){
                    auxdescrip = false;
                });
                arbolmetodosval.getSelectionModel().on('change', function(){
                    auxarbmetval = false;
                });
            }
            break;
        }
    }
	
    function adicionarValidacion(apl){
        tfdenom.setValue(limpiarEspacios(tfdenom.getValue()));
        //alert(keyp);
        if (fpAddValidaciones.getForm().isValid())
        {
			
            //var ids = arbolmetodos.getSelectionModel().getSelectedNode().id;	
            //console.info(arbolFunc.getSelectionModel().getSelectedNode());]
            var idnodopadre = arbolmetodos.getSelectionModel().getSelectedNode().parentNode.attributes.id;
            var node = arbolmetodos.getSelectionModel().getSelectedNode();
            var nombrehijo = node.text;
            //var arraytext = [];
				
            var idnodopadreval = arbolmetodosval.getSelectionModel().getSelectedNode().parentNode.attributes.id;
            var nodeval = arbolmetodosval.getSelectionModel().getSelectedNode();
            var nombrehijoval = nodeval.text;
            fpAddValidaciones.getForm().submit({
                url:'adicionarvalidacion',
                waitMsg:perfil.etiquetas.lbMsgEsperaRegFun,
                params:{
                    idsistema:arbolSistema.getSelectionModel().getSelectedNode().attributes.id, 
                    nombreclase: idnodopadre, 
                    nombremetodo:nombrehijo, 
                    nombreclass:idnodopadreval, 
                    nombremethod:nombrehijoval,
                    path:uri
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
                        //alert(sistemaseleccionado);
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
            mostrarMensaje(3,'No se pueden dejar campos vacios.'/*3,perfil.etiquetas.lbMsgErrorEnCamops*/);
    }
	
    ////------------ Modififcar Funcionalidad ------------////
    function modificarValidacion(){
        if (auxcbError == false || auxdescrip == false || auxarbmetval == false) {
            auxcbError = true;
            auxdescrip = true;
            auxarbmetval = true;
            if (fpAddValidaciones.getForm().isValid()){
                /*var idnodopadresel = arbolmetodos.getSelectionModel().getSelectedNode().parentNode.attributes.id;
			var nodesel = arbolmetodos.getSelectionModel().getSelectedNode();
			var nombrehijosel = nodesel.text;*/
                var padresel = sm.getSelected().get('controlador');
                var hijosel = sm.getSelected().get('accion');
                var nombval = sm.getSelected().get('denominacion');
                var nodopadre = arbolmetodosval.getSelectionModel().getSelectedNode().parentNode;
                var claseval = nodopadre.text;
                var nodohijo = arbolmetodosval.getSelectionModel().getSelectedNode();
                var metodoval = nodohijo.text;
			
                fpAddValidaciones.getForm().submit({
                    url:'modificarvalidacion',
                    waitMsg:perfil.etiquetas.lbMsgEsperaModFun,
                    params:{
                        idsistema:arbolSistema.getSelectionModel().getSelectedNode().attributes.id, 
                        padresel:padresel, 
                        hijosel:hijosel, 
                        nombreval:nombval, 
                        claseval:claseval, 
                        metodoval:metodoval,
                        path:uri
                    },
                    failure: function(form, action){
                        if(action.result.codMsg != 3){
                            mostrarMensaje(action.result.codMsg,action.result.mensaje);
                            sistemaseleccionado = arbolSistema.getSelectionModel().getSelectedNode().attributes.id;
                            stGpValidaciones.reload();
                            winMod.hide();
                        }
                        if(action.result.codMsg == 3) mostrarMensaje(action.result.codMsg,action.result.mensaje);
					
                    }
                });
            }
            else
                mostrarMensaje(3,perfil.etiquetas.lbMsgErrorEnCamops); 
        }
        else
            winMod.hide();
		               
    }
	
    ////-----------Funcion Eliminar Validaci�n------------////
    function eliminarValidacion(){
        var idsistema = arbolSistema.getSelectionModel().getSelectedNode().attributes.id;
        var padresel = sm.getSelected().get('controlador');
        var hijosel = sm.getSelected().get('accion');
        var nombval = sm.getSelected().get('denominacion');
        var claseval = sm.getSelected().get('clase');
        var metodoval = sm.getSelected().get('metodo');
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
                        claseval:claseval, 
                        metodoval:metodoval,
                        path:uri
                    },
                    callback: function (options,success,response){
                        mostrarMensaje(1,"La validaci&oacute;n fue eliminada correctamente.");
                        //Ext.Msg.alert(1/*"Informaci&oacute;n"*/,"La validaci&oacute;n fue eliminada correctamente.");
                        stGpValidaciones.reload();	
                         btnModificar.disable();
                         btnAdicionar.enable();
                                btnEliminar.disable();
                    }
                });
            }
        }
    }
	
    ////------------ Buscar Funcionalidad ------------////
    function buscarvalidacion(valid){
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
