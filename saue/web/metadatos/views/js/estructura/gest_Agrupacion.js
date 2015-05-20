/**
 * @author proyecto
 * @class {Gestionar Agrupacion}
 */

GestionarAgrupacion = function(){
	
	/**VARIABLES PRIVADAS
	 * @private*/
	var wn_GestionarAgrupaciones;
	var id;
	//var Tree1 = Ext.tree;
	var st_MostrarAgrupaciones =  new Ext.data.Store({
										url:'mostraragrupacion',
										reader:new Ext.data.JsonReader({
											root:'datos',
											id:'idagrupacion',
											totalProperty:'cant'
											},
											[
											{name: 'idagrupacion'},
											{name: 'nombre', mapping:'denagrupacion'}
											]
										)		
						});
						
	var sm_MostrarAgrupaciones = new Ext.grid.RowSelectionModel({singleSelect:true});
	
	var cm_Agrupaciones= [
				{id:'expandir',header: 'Nombre', dataIndex: 'nombre'}
	]; 
	var gd_MostrarAgrupaciones = new Ext.grid.GridPanel({
										title:'Agrupaciones',
										region:'center',
										split : true,
										collapsible : true,
										width:150,
										layout:'fit',
										iconCls:'icon-grid',
										autoExpandColumn:'expandir',
										store:st_MostrarAgrupaciones,
										sm:sm_MostrarAgrupaciones,
										viewConfig :{forceFit :true},
										loadMask:{msg :'Cargando agrupaciones..'},
										columns: cm_Agrupaciones,
										bbar: new Ext.PagingToolbar({
														pageSize: 5,
														store: st_MostrarAgrupaciones,
														displayInfo: true,
														displayMsg: 'Resultados de {0} - {1} de {2}',
														emptyMsg: "No hay resultados para mostrar."
											})
						});
	var st_MostrarEstAgrp =  new Ext.data.Store({
										url:'mostrarestructuraagrupacion',
										reader:new Ext.data.JsonReader({
											root:'datos',
											id:'idestructura',
											totalProperty:'cant'
											},
											[
											{name: 'idestructura'},
											{name: 'denominacion', mapping:'DatEstructura.denominacion'},
											{name: 'abreviatura', mapping:'DatEstructura.abreviatura'}											
											]
										)		
						});
	var bt_Elimin = new Ext.Button({
								text:'Eliminar',
								icon:perfil.dirImg+'eliminar.png',
								iconCls:'btn',
								id:"Eliminargpo",
								disabled:true,											
								handler:function(){eliminarAgrupacion();}									
								});
	var sm_MostrarEstAgrp = new Ext.grid.RowSelectionModel({singleSelect:true});
	
	var columns_MostrarEstAgrp= [
							{id:'exp',header: 'Nombre', dataIndex: 'denominacion'},
							{header: 'Abreviatura', width:50, dataIndex: 'abreviatura'}				
		]; 
	var gd_MostrarEstAgrp = new Ext.grid.GridPanel({
							title:'La agrupaci&oacute;n esta compuesta por:',
							//frame:true,
							region:'south',
							iconCls:'icon-grid',
							height:250,
							autoExpandColumn:'exp',
							store:st_MostrarEstAgrp,
							sm:sm_MostrarEstAgrp,
							viewConfig :{forceFit :true},
							columns: columns_MostrarEstAgrp,
							tbar:[bt_Elimin],
							bbar: new Ext.PagingToolbar({
											pageSize: 10,
											store: st_MostrarEstAgrp,
											displayInfo: true,
											displayMsg: 'Resultados de {0} - {1} de {2}',
											emptyMsg: "No hay resultados."
								})
						});

		/*TREE PANEL DEL ARBOL DE ESTRUCTURAS*/
	var trp_Estructura = new Ext.tree.TreePanel({        
        						autoScroll:true,
								title:'Composici&oacute;n',
        						split:true,							
        						width: 200,
        						disabled:true,
        						height:200,
        						minSize: 175,
        						maxSize: 200,
        						collapsible: true,
        						collapsed :true,
       							margins:'0 0 0 5',                    
        						layoutConfig:{  animate:true   },								
        						enableDD:true,
        						region:'west',
        						containerScroll: true, 
        						loader: new Ext.tree.TreeLoader({
        									dataUrl:'buscarhijosagrup',
        									baseParams:[{tipo:'ll'}]
        						})
				});

		/** RAIZ DEL ARBOL DE ESTRUCTURAS*/
	var root_agrup = new Ext.tree.AsyncTreeNode({
       					text: 'Estructuras',
        				draggable:false,
        				id:'Estructuras1'        				
					});
		/**METODOS PRIVADOS */
					
		/**ASIGNANDOLE LA RAIZ AL ARBOL*/			
		trp_Estructura.setRootNode(root_agrup);
		
		/**MANDANDO AL ARBOL QUE SE MUESTRE*/
		trp_Estructura.show();
		
		/**MANDANDO AQUE SE EXPANDA EN LA RAIZ*/
		//root_agrup.expand();
		sm_MostrarAgrupaciones.on("rowselect",function(_sm, indiceFila, record){	
   					trp_Estructura.enable();
   					st_MostrarEstAgrp.baseParams = {idagrupacion:DameSeleccionadoAgrupacion("idagrupacion")};
   					st_MostrarEstAgrp.load({params:{start:0,limit:10}})
   				});
   		sm_MostrarEstAgrp.on("rowselect",function(_sm, indiceFila, record){	
   					bt_Elimin.enable();
   				});
   		trp_Estructura.on("click",function(n){
   					id= n.id;
					insertarAgrup();
	 			});
		
		insertarAgrup= function(){
			Ext.Ajax.request({
							url: 'insertarestructuraagrupacion',
							method:'POST',
							params:{idagrupacion:DameSeleccionadoAgrupacion("idagrupacion"),idestructura:id},
							callback: function (options,success,response){
							responseData = Ext.decode(response.responseText);
							mostrarMensaje(responseData.codMsg,responseData.mensaje);
							st_MostrarEstAgrp.reload();
											}
									});
			
		}
		eliminarAgrupacion = function(){
			mostrarMensaje(2,'&iquest;Est&aacute; seguro que desea eliminar la estructura seleccionada ?',elimina);
					
			function elimina(btnPresionado){
							   // var cant=sm_MostrarNomenclador.getCount();
								if (btnPresionado =='ok'){										
									
										Ext.Ajax.request({
														url: 'eliminarestructuraagrupacion',
														method:'POST',
														params:{idagrupacion:DameSeleccionadoAgrupacion("idagrupacion"),idestructura:DameSeleccionadoAgrupacionEst("idestructura")},
														callback: function (options,success,response){
														responseData = Ext.decode(response.responseText);
														mostrarMensaje(responseData.codMsg,responseData.mensaje);
														st_MostrarEstAgrp.reload();
																		}
																,waitMsg:'Eliminando los datos...'});										
									
								}
				
						}							
		}
	DameSeleccionadoAgrupacion= function(idp){
				var params ="";
					if(TieneAgrupacionSeleccionados())
						{
							params = sm_MostrarAgrupaciones.getSelected().get(idp);
						}
						//alert(params);
					return params;
				}
   	TieneAgrupacionSeleccionados = function(){
   		return (sm_MostrarAgrupaciones.hasSelection());
   		}
   	DameSeleccionadoAgrupacionEst= function(idp){
				var params ="";
					if(TieneAgrupacionEstSeleccionados())
						{
							
							params = sm_MostrarEstAgrp.getSelected().get(idp);
						}
						//alert(params);
					return params;
				}
   	TieneAgrupacionEstSeleccionados = function(){
   		return (sm_MostrarEstAgrp.hasSelection());
   		}
		
		/**FUNCIONES PUBLICAS*/
	this.mostrarWin_GestionarAgrupaciones = function (){
				st_MostrarAgrupaciones.load({params:{start:0,limit:10}})
				if(!wn_GestionarAgrupaciones){						
							wn_GestionarAgrupaciones = new Ext.Window({
												title:'Gestionar Agrupaciones',
												layout:'border',
												maximizable:true,
												width:600,
												modal:true,
												height:400,
												closeAction:'hide',
												items:[trp_Estructura,gd_MostrarEstAgrp,gd_MostrarAgrupaciones]
											});
					
					}
				//SetEstadoBotonesTbarGrid();
				wn_GestionarAgrupaciones.show(this);
				}
				
		function mostrarMensaje(tipo, msg, fn){
	    var buttons = new Array(Ext.MessageBox.OK, Ext.MessageBox.OKCANCEL, Ext.MessageBox.OK);
	    var title = new Array('Informaci&oacute;n', 'Confirmaci&oacute;n', 'Error');
	    var icons = new Array(Ext.MessageBox.INFO, Ext.MessageBox.QUESTION, Ext.MessageBox.ERROR);
	    Ext.MessageBox.show({
	        title: title[tipo - 1],
	        msg: msg,
	        buttons: buttons[tipo - 1],
	        icon: icons[tipo - 1],
	        fn: fn
	    });
	}
}