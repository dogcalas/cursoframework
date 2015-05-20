var perfil = window.parent.UCID.portal.perfil;	
UCID.portal.cargarEtiquetas('gestexcepcion', function(){cargarInterfaz();});
		
////------------ Inicia el singlenton QuickTips ------------////
Ext.QuickTips.init();

	var winIns,winMod, regExce, path, stGpExcepciones,criteriobusqueda, valor;
		
function cargarInterfaz(){
        // Textfield Buscar Excepcion
	tfldBuscExcepcion = new Ext.form.TextField({
		emptyText: 'Valor...',
		id: 'valorBuscExcep',
		width: 120,
		anchor: '100%',
        maxLength:22,
		listeners: {
			specialkey: function(f,e){
				if (e.getKey() == e.ENTER && nodoSeleccionado != null){                            
					buscador();
				}
				else{
					tfldBuscExcepcion.reset();
				}
			}
		}
	});

	searchCriteriaCombo =new Ext.form.ComboBox({
		xtype: 'combo',
		store: new Ext.data.SimpleStore({
				fields : ['searchCriteria'],						
				data:[['Ninguno'],['Nombre'],['Código'],['Mensaje'],['Descripción']]}
		),
		name: 'searchCriteria',
		id:'searchCriteria',
		editable:false,
		emptyText:'Criterio',
		triggerAction:'all',
		forceSelection:true,
		allowBlank:false,	
		displayField: 'searchCriteria',
		mode: 'local',
		width:90
	});	

        searchCriteriaCombo.on('select',function(){
            if(Ext.getCmp('searchCriteria').getValue() == 'Ninguno')
                tfldBuscExcepcion.reset();
            })
	
	btnAdicionar = new Ext.Button({
		disabled:true, 
		icon:perfil.dirImg+'adicionar.png', 
		iconCls:'btn', 
		text:'Adicionar',
                handler:function(){winForm('Ins');}
	});
	btnModificar = new Ext.Button({
		disabled:true,
		icon:perfil.dirImg+'modificar.png', 
		iconCls:'btn', 
		text:'Modificar',
                handler:function(){winForm('Mod');}
	});
	btnEliminar = new Ext.Button({
		disabled:true, 
		icon:perfil.dirImg+'eliminar.png', 
		iconCls:'btn', 
		text:'Eliminar',
		handler:function(){
			eliminarexcepcion();
		}
	});
    btnProbar = new Ext.Button({
		disabled:true, 
		icon:perfil.dirImg+'ver.png', 
		iconCls:'btn', 
		text:'Probar excepción',
		handler:function(){
			probarexcepcion();
		}
	});

	btnBuscar = new Ext.Button({
		disabled:true,
		icon:perfil.dirImg+'buscar.png',
		iconCls:'btn',
		text:'Buscar', 
		handler:function(){
			buscador();
		}
	});

	UCID.portal.cargarAcciones(window.parent.idexcepcion);
	
	arbolSistema = new Ext.tree.TreePanel({
		title:'Subsistemas registrados',
		collapsible:true,
		autoScroll:true,
		region:'west',
		split:true,
		width:'200',
		loader: new Ext.tree.TreeLoader({
			dataUrl:'cargarsistema',
			listeners:{
                        'beforeload':function(atreeloader, anode){ 
			             atreeloader.baseParams = {};                                            
	                     atreeloader.baseParams.path = anode.attributes.path                               
						}}
		})
	});
	
	padreSistema = new Ext.tree.AsyncTreeNode({
		text: 'Subsistemas',
		animate:false,
		draggable:false,
		expandable:false,
		expanded:true,
		id:'0'
	});
	
	arbolSistema.setRootNode(padreSistema);
	
	arbolSistema.on('click', function (node, e){
		sistemaseleccionado = node;
		sistemas =  node;
		bandera =0;
	        stGpExcepciones.removeAll();
		if(node.id!=0 && node.isLeaf()){
			idsistema = node.id;
			nodoSeleccionado=node;							
			btnAdicionar.enable(); 
			btnModificar.disable();
			btnEliminar.disable();
                        btnProbar.disable();
                        btnBuscar.enable();
                        gpExcepciones.enable();
                	tfldBuscExcepcion.reset();
                	searchCriteriaCombo.reset();
		        path = node.attributes.path;
		        stGpExcepciones.load({params:{start:0,limit:20}});
		}
		else {			
			btnAdicionar.disable();
			btnModificar.disable();
			btnEliminar.disable();
                        btnProbar.disable();
                        btnBuscar.disable();
                        gpExcepciones.disable();
                        btnBuscar.enable();
		}
	},this);

    stGpExcepciones =  new Ext.data.Store({
		url: 'cargarexcepciones',
		listeners:{
			load:function(st, object){				
				if(st.getCount()!=0) cantrecords = st.getCount();				
			},
			beforeload:function(st, object){
				st.baseParams.path = nodoSeleccionado.attributes.path;
			}
		},
		reader: new Ext.data.JsonReader ({
			root: 'datos',
			totalProperty: 'cantidad_filas',
			id: 'codigo'
		},
		[
			{name: 'codigo', mapping: 'codigo'},
			{name: 'nombre', mapping: 'nombre'},
			{name: 'tipo', mapping: 'tipo'},
			{name: 'mensaje', mapping: 'mensaje'},
			{name: 'descripcion', mapping: 'descripcion'}
		])
	});
	
	sm = new Ext.grid.RowSelectionModel({singleSelect:true});
    
        sm.on('rowselect', function (smodel, rowIndex, record){
		btnModificar.enable();
		btnEliminar.enable();
                btnProbar.enable();
                btnBuscar.enable();
	},this);
	
	
	gpExcepciones = new Ext.grid.GridPanel({
		frame:true,
		region:'center',
		height:'100%',
		width:'100%',
		iconCls:'icon-grid',
                autoExpandColumn:'expandir',
		disabled:true,
		store:stGpExcepciones,
		sm:sm,
		columns: [
                    {header:'Código', width:85, dataIndex: 'codigo'},
                    {header:'Nombre', width:95, dataIndex: 'nombre'},
                    {header: 'Tipo', width:35, dataIndex: 'tipo'},
                    {header:'Mensaje', width:220, dataIndex: 'mensaje'},
                    {header: 'Descripción', width:350, dataIndex: 'descripcion', id:'expandir'}
		],
		loadMask:{store:stGpExcepciones},			
		tbar:[
                        searchCriteriaCombo,
                        tfldBuscExcepcion,
                        btnBuscar
		],
            bbar:new Ext.PagingToolbar({
            pageSize: 20,
            id:'ptbsesion',
            store: stGpExcepciones,
            displayInfo: true,
            displayMsg: perfil.etiquetas.lbTitMsgNingunresultadoparamostrar,
            emptyMsg: perfil.etiquetas.lbTitMsgResultados
		})
	});
       		////------------ Combo Box gestores ------------////
	    var cbTiposData = [
                                ['P', 'Presentación (P)'],
                                ['L', 'Log (L)'],
                                ['LP', 'Log y presentación (LP)'],
                                ['B', 'Ciega (B)'],
                                ['C', 'Confirmación (C)']
                            ];
		
		var storeDataTmp = new Ext.data.SimpleStore({
	        fields: ['tipo','denominacion'],
	        data : cbTiposData
	    }); 
        

		 regExce = new Ext.FormPanel({
				labelAlign: 'top',
                                bodyStyle:'padding:5px 5px 0',
				frame:true,
				items: [{
                                        layout:'column',
                                        items:[{
                                        columnWidth:.3,
                                        layout:'form',
                                            items:[{
                                                     xtype:'textfield',
                                                    fieldLabel: 'C&oacute;digo',
                                                    name: 'codigo',
                                                    anchor: '90%',
                                                    maxLength:20,
                                                    allowBlank:false,
                                                    blankText:perfil.etiquetas.lbMsgCampoObligatorio,
                                                    regex:/^[A-Z]+[0-9]*$/,
                                                    regexText:perfil.etiquetas.lbMsgValorIncorrecto  
                                                    }]
                                        },{
                                        columnWidth:.3,
                                        layout:'form',
                                            items:[{
                                                    xtype:'textfield',
                                                    fieldLabel: 'Nombre',
                                                    name: 'nombre',
                                                    anchor: '90%',
                                                    allowBlank:false,
                                                    maxLength:22,
                                                    blankText:perfil.etiquetas.lbMsgCampoObligatorio,
                                                    regex:/^[a-zA-Z\xf3\xf1\xe1\xe9\xed\xfa\xd1\xc1\xc9\xcd\xd3\xda\xfc]*$/,
                                                    maskRe:/^[a-zA-Z\xf3\xf1\xe1\xe9\xed\xfa\xd1\xc1\xc9\xcd\xd3\xda\xfc]*$/,
                                                    regexText:perfil.etiquetas.lbMsgValorIncorrecto
                                            }]
                                            },{
                                            columnWidth:.4,
                                            layout:'form',
                                                items:[new Ext.form.ComboBox({
                                                    fieldLabel: 'Tipo',
                                                    name: 'tipo',
                                                    editable:false,
                                                    emptyText:'Seleccione',
                                                    triggerAction:'all',
                                                    forceSelection:true,
                                                    store:cbTiposData,
                                                    valueField:'tipo',
                                                    displayField:'denominacion',
                                                    hiddenName:'tipo',
                                                    allowBlank:false,	
                                                    displayField: 'Tipo..',
                                                    mode: 'local',
                                                    anchor: '100%',
                                                    blankText:perfil.etiquetas.lbMsgCampoObligatorio
                                                }) 
                                            ]
						},
						   {
                                                columnWidth:1,
                                                layout: 'form',
                                                items: [                                                 {
                                                    xtype:'textarea',
                                                    fieldLabel: 'Mensaje',
                                                    name: 'mensaje',
                                                    anchor: '100%',
                                                    maxLength:200,
                                                    allowBlank:false,
                                                    blankText:perfil.etiquetas.lbMsgCampoObligatorio
                                                },
                                                {
                                                    xtype:'textarea',
                                                    fieldLabel: 'Descripci&oacute;n',
                                                    name: 'descripcion',
                                                    anchor: '100%',
                                                    maxLength:200,
                                                    allowBlank:false,
                                                    blankText:perfil.etiquetas.lbMsgCampoObligatorio
                                                }                                          
                                            ]
                            }]
			}]
		});
                
	panel = new Ext.Panel({
			layout:'border',
			items:[arbolSistema,gpExcepciones],			
			tbar:[btnAdicionar,btnModificar,btnEliminar,btnProbar]
	});

	vpGestValid = new Ext.Viewport({
			layout:'fit',
			items:panel
	});

        ////------------ Cargar la ventana ------------////
        function winForm(opcion){
                switch(opcion){
                        case 'Ins':{
                                if(!winIns)
                                {
		                        winIns = new Ext.Window({modal: true,closeAction:'hide',resizable:false, layout:'fit',
		                                title:perfil.etiquetas.lbTitAdicionarExce,width:450,height:300,
		                                buttons:[
		                                {
		                                        icon:perfil.dirImg+'cancelar.png',
		                                        iconCls:'btn',
		                                        text:perfil.etiquetas.lbBtnCancelar,
		                                        handler:function(){winIns.hide();}
		                                },
		                                {	
		                                        icon:perfil.dirImg+'aplicar.png',
		                                        iconCls:'btn',
		                                        text:perfil.etiquetas.lbBtnAplicar,
		                                        handler:function(){adicionarexcepcion('apl');}
		                                },
		                                {	
		                                        icon:perfil.dirImg+'aceptar.png',
		                                        iconCls:'btn',
		                                        text:perfil.etiquetas.lbBtnAceptar,
		                                        handler:function(){adicionarexcepcion();}
		                                }]
		                        });
                                }
                                regExce.getForm().reset();
                                winIns.add(regExce);
                                winIns.doLayout();
                                winIns.show();
                                }break;
                                case 'Mod':{
                                        if(!winMod)
                                        {
		                                winMod= new Ext.Window({modal: true,closeAction:'hide',layout:'fit',
		                                        title:perfil.etiquetas.lbTitModificarExce,resizable:false, width:450,height:300,
		                                        buttons:[
		                                        {
		                                                icon:perfil.dirImg+'cancelar.png',
		                                                iconCls:'btn',
		                                                text:perfil.etiquetas.lbBtnCancelar,
		                                                handler:function(){winMod.hide();}
		                                        },
		                                        {	
		                                                icon:perfil.dirImg+'aceptar.png',
		                                                iconCls:'btn',
		                                                text:perfil.etiquetas.lbBtnAceptar,
		                                                handler:function(){modificarexcepcion();}
		                                        }]
		                                });
                                        }

                                        winMod.add(regExce);
                                        winMod.doLayout();									
                                        winMod.show();
                                        regExce.getForm().loadRecord(sm.getSelected());
                                }break;
                        }
                }
}
	//Boton Principal Adicionar Excepcion
	function adicionarexcepcion(apl){
                
        if(regExce.getForm().isValid()){
            regExce.getForm().submit({
                url:'adicionarexcepcion',
                params:{sistema:path},
                waitMsg:'Registrando excepción ...',
                failure: function(form, action){
                    if(action.result.codMsg != 3) {
                        mostrarMensaje(action.result.codMsg,action.result.mensaje); 
                        regExce.getForm().reset(); 
                        if(!apl) 
                        winIns.hide();
                        stGpExcepciones.reload();
                        sm.clearSelections();								
                        btnModificar.disable();
                        btnEliminar.disable(); 
                    }
                     if(action.result.codMsg == 3) mostrarMensaje(action.result.codMsg,action.result.mensaje);	
                }
            }); 
        }
        else
           mostrarMensaje(3,perfil.etiquetas.lbMsgErrorEnCamops);
	};

	function modificarexcepcion(){

        if(regExce.getForm().isValid()){
            regExce.getForm().submit({
                url:'modificarexcepcion',					
                waitMsg:'Modificando excepción...',																
                params:{path:path, codigoanterior:sm.getSelected().get('codigo')},
                failure: function(form, action){
                    if(action.result.codMsg != 3) {
                        mostrarMensaje(action.result.codMsg,action.result.mensaje); 
                        regExce.getForm().reset();
                        stGpExcepciones.reload();
                        sm.clearSelections();
                        winMod.hide();									
                        btnModificar.disable();
                        btnEliminar.disable();
                    }
                    if(action.result.codMsg == 3) mostrarMensaje(action.result.codMsg,action.result.mensaje);	
                }
            }); 
                 
        }
        else
           mostrarMensaje(3,perfil.etiquetas.lbMsgErrorEnCamops);
	};

function eliminarexcepcion(){
	var codigoExcepcion = sm.getSelected().get('codigo');
	var path = nodoSeleccionado.attributes.path;
	mostrarMensaje(2,'&iquest;Est&aacute; seguro que desea eliminarla?',elimina);
	
	function elimina(btnPresionado){
		if (btnPresionado == 'ok'){
			Ext.Ajax.request({
				url: 'eliminarexcepcion',
                waitMsg:'Espere por favor...',
				method:'POST',
				params:{
					path:path, 
					codigoExcepcion:codigoExcepcion
				},				
				callback: function (options,success,response){
                    stGpExcepciones.reload();
                                    
                    btnAdicionar.enable();
                    btnModificar.disable();
                    btnEliminar.disable();
                    btnProbar.disable();
                    btnBuscar.enable();
                }
            });
        }
    }
}

function probarexcepcion(){
		var descE = sm.getSelected().get('descripcion');
		mostrarMensaje(1,'Atención: La siguiente notificación es solo una prueba de la excepción seleccionada.',prueba);
        function prueba(btnPresionado){
           if (btnPresionado == 'ok'){
               mostrarMensaje(3,descE);
        
               btnAdicionar.disable();
               btnModificar.enable();
               btnEliminar.enable();
               btnProbar.enable();
               btnBuscar.enable();
           }
        }
}

function buscador(){
	valor = Ext.getCmp('valorBuscExcep').getValue();
	criteriobusqueda = Ext.getCmp('searchCriteria').getValue();
        btnBuscar.focus();
	stGpExcepciones.removeAll();
        
        if(!valor && criteriobusqueda !='Ninguno' )
	   {
	    mostrarMensaje(3,perfil.etiquetas.lbMsgErrorBuscar);
	   }
	   else
	   {
		stGpExcepciones.baseParams = {};
		stGpExcepciones.baseParams.criteriobusqueda = criteriobusqueda;
		stGpExcepciones.baseParams.idsubsistema = nodoSeleccionado.attributes.path;
                stGpExcepciones.baseParams.valor = valor;
            	stGpExcepciones.load({params:{start:0,limit:20}});
		stGpExcepciones.baseParams = {};
		valor = false;
		criteriobusqueda = false;
	}

return;
}
