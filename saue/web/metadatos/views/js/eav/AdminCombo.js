/**
 * @author proyecto
 */
 Ext.MessageBox.buttonText.yes = "Si";
Ext.MessageBox.buttonText.ok = "Aceptar";
AdminCombo = function (){
		/** VARIABLES PRIVADAS **/
		var wn_AdminCombo;
		formulario = Ext.form;		
		/**
		 * @public
		 * @return{Ext.data.Store}
		 */
		this.idcombo;
    	/**@private    	 **/
		var Valor = new formulario.TextField({
					xtype:'textfield',
					//fieldLabel:'Valor por Defecto',
					name: 'vpdcb',
					anchor:'93%',
					allowBlank:false,
					blankText:'Este campo es requerido'
					//regex:/^([a-zA-Z]+?[a-zA-Z]*)+$/,
					//regexText:'Solo se permiten numeros y letras sin espacio',	
			});
		var idValor =new formulario.TextField({
				xtype:'textfield',
				//fieldLabel:'Id del valor',
				name: 'idvpdcb',
				anchor:'10%',
				width :60,
				allowBlank:false,
				blankText:'Este campo es requerido'
				//regex:/^([a-zA-Z]+?[a-zA-Z]*)+$/,
				//regexText:'Solo se permiten numeros y letras sin espacio',	
		});
		
		
		var bt_AdicionarValores = new Ext.Button({
								  text:'Adicionar',
								  icon:perfil.dirImg+'adicionar.png',
								  iconCls:'btn',
								  id:"Adicionarvlor",
								  handler:Onbt_AdicionarValoresClick				
			});
		var bt_ModificarValores = new Ext.Button({
								  text:'Modificar',
								  icon:perfil.dirImg+'modificar.png',
								  iconCls:'btn',
								  disabled:true,
								  id:"Modificararvlor",
								  handler:Onbt_ModificarValoresClick				
			});
		var bt_EliminarValores = new Ext.Button({
								  text:'Eliminar',
								  icon:perfil.dirImg+'eliminar.png',
								  iconCls:'btn',
								  disabled:true,
								  id:"Eliminarvlor",
								  handler:Onbt_EliminarValoresClick				
			});
			
		var st_MostrarValores =  new Ext.data.Store({
										url:'mostrarvalorcombo',
										reader:new Ext.data.JsonReader({
											root:'datos',
											id:'idvalor',
											totalProperty:'cant'
											},
											[
											{name: 'idvalor', mapping:'valorid'},
											{name: 'valor', mapping:'valor'},
											{name: 'idvalordefecto'},
											{name: 'idcampo'}											
											]
										)		
						});						
		var sm_MostrarValores = new Ext.grid.RowSelectionModel({
									singleSelect:true,
									listeners: {rowselect: function(sm, row, rec) {
	                       									 Valor.setValue(rec.data.valor);
	                       									 idvalorpordefecto = data.idvalordefecto
	                       									 idValor.setValue(rec.data.idvalor);
	                       									 chkt_BotonesTbarGrid();
	                    }}
			});
		
		var columns= [
					{dataIndex: 'valor',header: 'Nombre a mostrar', id:'expandir'},
					{ dataIndex: 'idvalor',header: 'Valor real', width:40}			
		]; 
		
		var gd_MostrarValores = new Ext.grid.GridPanel({
								iconCls:'icon-grid',
								autoExpandColumn:'expandir',
								store:st_MostrarValores,
								sm:sm_MostrarValores,
								viewConfig :{forceFit :true},
								columns: columns,
								loadMask:{msg :'Cargando valores del combo'},
								tbar:['Valor por defecto: ',Valor,' Id del valor ',idValor,'-',
										bt_AdicionarValores,bt_ModificarValores,bt_EliminarValores],
								bbar: new Ext.PagingToolbar({
												pageSize: 5,
												store: st_MostrarValores,
												displayInfo: true,
												displayMsg: 'Resultados de {0} - {1} de {2}',
												emptyMsg: "No hay resultados."
								}),
								keys : [{key:[46],fn:function(){Eliminar();}
						  		}]
							});
	
		/** VARIABLES PUBLICAS **/
			
		/** METODOS PRIVADOS **/
		function Onbt_AdicionarValoresClick(btn){
				if(Valor.isValid() && idValor.isValid())
					{
						Ext.Ajax.request({
								url: 'insertarvalorcombo',
								method:'POST',
								params:{valor:Valor.getValue(), idvalor : idValor.getValue(),idcombo:idcombo},
								callback: function (options,success,response){
													responseData = Ext.decode(response.responseText);
													mostrarMensaje(responseData.codMsg,responseData.mensaje);
													st_MostrarValores.reload();
								}
							});
					}
				else{Ext.MessageBox.show({title:'Error',msg: 'Debe llenar los campos'}); }
				Valor.reset();
				idValor.reset();
			}
		
		function Onbt_ModificarValoresClick(btn){				
				if(Valor.isValid() && idValor.isValid())
					{
						Ext.Ajax.request({
								url: 'modificarvalorcombo',
								method:'POST',
								params:{valor:Valor.getValue(), idvalor : idValor.getValue(),idvalordefecto :idvalorpordefecto,idcombo :idcombo},
								callback: function (options,success,response){
													responseData = Ext.decode(response.responseText);
													mostrarMensaje(responseData.codMsg,responseData.mensaje);
													st_MostrarValores.reload();
								}
							});
					}
				else{Ext.MessageBox.show({title:'Error',msg: 'Debe llenar los campos'}); }
				Valor.reset();
				idValor.reset();
			
						
		}
		
		function Onbt_EliminarValoresClick(btn){
								Eliminar();		
		}

		function Eliminar(){
			 	var eliminar =  sm_MostrarValores.getSelected().data.valor;
				var msg="¿Est&aacute seguro que desea eliminar '"+eliminar+"'?";
				mostrarMensaje(2, msg, function(btn){	                    	
	                    	if (btn == 'ok'){
	                    		Ext.Ajax.request({
											url: 'eliminarvalorcombo',
											method:'POST',
											params:{idvalor:sm_MostrarValores.getSelected().data.idvalor,idcombo :idcombo},
											callback: function (options,success,response){
																responseData = Ext.decode(response.responseText);
																mostrarMensaje(responseData.codMsg,responseData.mensaje);
																 sm_MostrarValores.clearSelections();												
																 st_MostrarValores.load();
											}
									});            					
            					}
            				})			
		}
		
		function chkt_BotonesTbarGrid(){			
			if(sm_MostrarValores.hasSelection()){
					bt_ModificarValores.enable();
					bt_EliminarValores.enable();
				}
			else{
					bt_ModificarValores.disable();
					bt_EliminarValores.disable();
				}
		}

		/** METODOS PUBLICOS **/
		this.mostrarWin_AdminCombo = function(){
			idcombo = this.idcombo;
			st_MostrarValores.baseParams = {idcombo :idcombo};
			st_MostrarValores.load({params:{start:0, limit:5}})
			if(!wn_AdminCombo){
							wn_AdminCombo = new Ext.Window({
												title:'Agregar valores al combo',
												layout:'fit',
												width:600,
												modal:true,
												height:250,
												closeAction:'hide',
												items:gd_MostrarValores
											});
			}
				wn_AdminCombo.show(this);	
		}
		/**
		 * @return {array}
		 */
		
		/*this.RecargaGrid = function(){
				st_MostrarValores = this.store;
				st_MostrarValores.load({params:{start : 0, limit:5}})
		
		}*/
		
		
		mostrarMensaje = function(tipo, msg, fn){
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
/*
 * 
							Ext.Ajax.request({
								url: 'isertarvalorcombo',
								method:'POST',
								params:{valor:Valor.getValue(), idvalor : idValor.getValue()},
								callback: function (options,success,response){
													responseData = Ext.decode(response.responseText);
													mostrarMensaje(responseData.codMsg,responseData.mensaje);
													st_MostrarValores.reload();
								}
							});
					
 */