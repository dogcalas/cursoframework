var perfil = window.parent.UCID.portal.perfil;
var aJsonGrid;
var objStore;
var vpGestperfilusuario, fpRegistrarPerfil = {}, GpUsuarios = {};
var arraycmp = [];
var varMod;
UCID.portal.cargarEtiquetas('gestperfil', function() {
	cargarInterfaz();
});

// //------------ Inicializo el singlenton QuickTips ------------////
Ext.QuickTips.init();

// //------------ Declarar variables ------------////
var winIns, winMod, winCamb, aux,prueba;
var auxIns3 = true;
var auxMod3 = true;
var auxIns2 = false;
var auxMod2 = false;
var auxBus2 = true;
tipos = /(^([a-zA-ZáéíóúñÑ])+([a-zA-ZáéíóúñÑ\d\_\s]*))$/;
function cargarInterfaz() {

	// //------------ Botones ------------////
	btnAdicionar = new Ext.Button({
		disabled : true,
		id : 'btnAddPerfil',
		hidden : true,
		icon : perfil.dirImg + 'adicionar.png',
		iconCls : 'btn',
		text :perfil.etiquetas.lbBtnAdicionar,
		handler : function() {
			winForm('Ins');
		}
	});
	btnModificar = new Ext.Button({
		disabled : true,
		id : 'btnModPerfil',
		hidden : true,
		icon : perfil.dirImg + 'modificar.png',
		iconCls : 'btn',
		text :perfil.etiquetas.lbBtnModificar,
		handler : function() {
			winForm('Mod');
		}
	});
	/*
	 * btnAyuda = new Ext.Button({id:'btnHelpPerfil', hidden:true,
	 * icon:perfil.dirImg+'ayuda.png', iconCls:'btn',
	 * text:perfil.etiquetas.lbBtnAyuda});
	 */
	UCID.portal.cargarAcciones(window.parent.idFuncionalidad);

	// //------------ Establesco modo de seleccion de grid
	// (single)------------////
	sm = new Ext.grid.RowSelectionModel({
		singleSelect : true
	});
	sm.on("rowselect", getFila);

	// //------------ Cargar la ventana ------------////
	function winForm(opcion) {
		switch (opcion) {
			case 'Ins' : {
				if (!winIns) {
					winIns = new Ext.Window({
						modal : true,
						closeAction : 'hide',
						layout : 'fit',
						title : perfil.etiquetas.lbTitAdicionardatosalperfil,
						width : 400,
						autoHeight : true,
						buttons : [{
							icon : perfil.dirImg + 'cancelar.png',
							iconCls : 'btn',
							text : perfil.etiquetas.lbBtnCancelar,
							handler : function() {
								winIns.hide();
							}
						}, {
							icon : perfil.dirImg + 'aceptar.png',
							iconCls : 'btn',
							text : perfil.etiquetas.lbBtnAceptar,
							handler : function() {
								adicionarperfil();
							}
						}]
					});
					winIns.on('show', function() {
						auxIns3 = false;
						auxMod3 = false;
						auxBus2 = false;
					}, this)
					winIns.on('hide', function() {
						auxIns3 = true;
						auxMod3 = true;
						auxBus2 = true;
						fpRegistrarPerfil.getForm().reset();
					}, this)
				}
				winIns.add(fpRegistrarPerfil);
				winIns.doLayout();
				winIns.show();
			}
				break;
			case 'Mod' : {
				if (!winMod) {
					winMod = new Ext.Window({
						modal : true,
						closeAction : 'hide',
						layout : 'fit',
						title : perfil.etiquetas.lbTitModificardatosdelperfil,
						width : 500,
						autoHeight : true,
						buttons : [{
							icon : perfil.dirImg + 'cancelar.png',
							iconCls : 'btn',
							text : perfil.etiquetas.lbBtnCancelar,
							handler : function() {
								winMod.hide();
							}
						}, {
							icon : perfil.dirImg + 'aceptar.png',
							iconCls : 'btn',
							text : perfil.etiquetas.lbBtnAceptar,
							handler : function() {
								modificarperfil();
							}
						}]
					});
					winMod.on('show', function() {
						auxIns3 = false;
						auxMod3 = false;
						auxBus2 = false;
					}, this)
					winMod.on('hide', function() {
						auxIns3 = true;
						auxMod3 = true;
						auxBus2 = true;
                                               
					}, this)
				}
				winMod.add(fpRegistrarPerfil);
				winMod.doLayout();
				winMod.show();
				fpRegistrarPerfil.getForm().loadRecord(GpUsuarios
						.getSelectionModel().getSelected());
                                    varMod = "";            
                                 for(i = 0;i< arraycmp.length;i++){
                                   varMod+= Ext.getCmp(arraycmp[i]).getValue();
                                    
                                }
                                
                            
                               
                                    
                                           
                                              
                                               
			}
				break;
		}
	}

	// EndOF Cargar
	function cargarConfigGrid() {
		Ext.Ajax.request({
			url : 'configurargrid',
			params : {},
			callback : function(options, success, response) {
				aJsonGrid = Ext.decode(response.responseText);
				creaViewPort(aJsonGrid);
			}
		});
	}
	cargarConfigGrid();
	// //------------ Formulario que se crea dinamico ------------////
	function cargarConfigForm() {
		Ext.Ajax.request({
			method : 'POST',
			url : 'cargarcampos',
			params : {},
			callback : function(options, success, response) {
				aJsonForm = Ext.decode(response.responseText);
				fpRegistrarPerfil = dameFormUsuarios(aJsonForm);
				btnAdicionar.enable();
			}
		});
	}
	cargarConfigForm();
	// Para formulario dinamico
	function creaArrayItems(aJSonItems) {
		var arrayItems = new Array();
		for (var i = 0; i < aJSonItems.cantidad; i++)
			arrayItems.push(dameArrayColumn(aJSonItems.campos,
					aJSonItems.cantidad, i))
		return arrayItems;
	}
	// Para crear el arreglo de columnas
	function dameArrayColumn(aJSonItems, noCol, pos) {
		if (noCol == 1)
			return {
				columnWidth : 1,
				layout : 'form',
				items : dameItem(aJSonItems[pos].tipocampo, pos, aJSonItems)
			};
		if (noCol == 2)
			return {
				columnWidth : .5,
				layout : 'form',
				items : dameItem(aJSonItems[pos].tipocampo, pos, aJSonItems)
			};
		if (noCol >= 3)
			return {
				columnWidth : .33,
				layout : 'form',
				items : dameItem(aJSonItems[pos].tipocampo, pos, aJSonItems)
			};
	}
	function dameItem(xtype, pos, aJSonItems) {
               
                arraycmp.push(aJSonItems[pos].idcampo);
		prueba = aJSonItems[pos].NomExpresiones.expresion;
		var d = eval(prueba);
		if (xtype == 'textfield')
			return {
				xtype : 'textfield',
				fieldLabel : aJSonItems[pos].nombreamostrar,
				id : aJSonItems[pos].idcampo,
				maxLength : aJSonItems[pos].longitud,
				name : aJSonItems[pos].nombre,
				regex : new RegExp (d),
				anchor : '95%'
			};
		if (xtype == 'datefield')
			return {
				xtype : 'datefield',
				fieldLabel : aJSonItems[pos].nombreamostrar,
				id : aJSonItems[pos].idcampo,
				maxLength : aJSonItems[pos].longitud,
				name : aJSonItems[pos].nombre,
				anchor : '95%'
			};
	}
	// //---------- AdicionarPerfil ----------////
	function adicionarperfil(apl) {
		if (fpRegistrarPerfil.getForm().isValid()) {
			fpRegistrarPerfil.getForm().submit({
				url : 'insertarperfil',
				waitMsg : perfil.etiquetas.lbMsgFunAdicionarWait,
				params : {
					idusuario : GpUsuarios.getSelectionModel().getSelected().data.idusuario
				},
				failure : function(form, action) {
					if (action.result.codMsg != 3) {
//						mostrarMensaje(action.result.codMsg,
//								perfil.etiquetas.lbMsgInfAdicionar);
						GpUsuarios.getStore().reload();
						fpRegistrarPerfil.getForm().reset();
						if (!apl)
							winIns.hide();
						btnModificar.disable();
					}
//					if (action.result.codMsg == 3)
//						mostrarMensaje(action.result.codMsg,
//								action.result.mensaje);
				}
			});
		} else
			mostrarMensaje(3, perfil.etiquetas.lbMsgErrorEnCamops);
	}
	// //------------ Modififcar Perfil ------------////
	function modificarperfil() {
		if (fpRegistrarPerfil.getForm().isValid()) {
                    vMod=[];
                    for(i = 0;i< arraycmp.length;i++){
                                   vMod+= Ext.getCmp(arraycmp[i]).getValue();
                                    
                                }
                       
                        if(vMod != varMod){ 
                            vMod = "";
                           
			fpRegistrarPerfil.getForm().submit({
				url : 'modificarperfil',
				waitMsg :perfil.etiquetas.lbMsgFunModificarWait,
				params : {
					idfila : GpUsuarios.getSelectionModel().getSelected().data.idfila
				},
				failure : function(form, action) {
					if (action.result.codMsg != 3) {
//						mostrarMensaje(action.result.codMsg,
//								perfil.etiquetas.lbMsgInfModificar);
						GpUsuarios.getStore().reload();
						fpRegistrarPerfil.getForm().reset();
						winMod.hide();
					}
//					if (action.result.codMsg == 3)
//						mostrarMensaje(action.result.codMsg,
//								action.result.mensaje);
				}
			});
                        }
                        else{
                        mostrarMensaje(3,perfil.etiquetas.NoModify);
                        vMod = "";
                        
                        }
		} else
			mostrarMensaje(3, perfil.etiquetas.lbMsgErrorEnCamops);
	}

	// Funciones que copie
	// Funcion para crear el store
	function dameStoreGp(aJsonGrid) {
		return new Ext.data.Store({
			proxy : new Ext.data.HttpProxy({
				url : 'cargargrid'
			}),

			reader : new Ext.data.JsonReader({
				totalProperty : 'cant_fila',
				root : 'datos',
				id : 'idusuario'
			}, aJsonGrid[1].store)
		});
	}

	function getFila(_sm, indiceFila, record) {
		if (record.data.idfila) {
			btnModificar.enable();
			btnAdicionar.disable();
			auxMod2 = true;
			auxIns2 = false;
		} else {
			btnModificar.disable();
			auxMod2 = false;
                        if(GpUsuarios.getColumnModel().getColumnCount(true)>1){
                        btnAdicionar.enable();
			auxIns2 = true;
                        }
                        else{
                        btnAdicionar.disable();
			auxIns2 = false; 
                        }
		}
	}

	// Para las columnas del grid
	function dameColumnModelGpUsuario(aJsonGrid) {
		return new Ext.grid.ColumnModel(aJsonGrid[0].columns);
	}
	// Para crear el formulario
	function dameFormUsuarios(aJsonForm) {
        
		return new Ext.FormPanel({
			labelAlign : 'top',
			autoHeight : true,
			frame : true,
			items : {
				layout : 'column',
				items : creaArrayItems(aJsonForm)
			}
		});
	}

	// //------------ Defino el grid de perfiles ------------////
	function dameGpUsuarios(aJsonGrid) {

		objStore = dameStoreGp(aJsonGrid);
		// //------------ Trabajo con el PagingToolbar ------------////
		var ptbaux = new Ext.PagingToolbar({
			pageSize : 15,
			store : objStore,
			displayInfo : true,
                        displayMsg: perfil.etiquetas.lbMsgbbarI,
                        emptyMsg: perfil.etiquetas.lbMsgbbarII
		})

		ptbaux.on('change', function() {
			sm.selectFirstRow();
		}, this)

		return new Ext.grid.GridPanel({
			title : perfil.etiquetas.lbTitGestionarperfilesdeusuarios,
			tbar : [btnAdicionar, btnModificar,/* btnAyuda */

					new Ext.menu.Separator(), new Ext.Toolbar.TextItem({
						text : perfil.etiquetas.lbTitDenBuscar
					}), nombreusuario = new Ext.form.TextField({
						width : 80,
						id : 'usuario',
                                                regex:/(^([a-z_])+([a-z0-9_]*))$/,
                                                maskRe: /[a-z0-9_]/i
					}), new Ext.menu.Separator(), new Ext.Button({
						icon : perfil.dirImg + 'buscar.png',
						iconCls : 'btn',
						text : '<b>' + perfil.etiquetas.lbBtnBuscar + '</b>',
						handler : function() {
							if(Ext.getCmp('usuario').isValid())
                                                        buscarnombreusuario(nombreusuario.getValue());
						}
					})],

			frame : true,
			region : 'center',
			autoExpandColumn : 'expandir',
			store : objStore,
			loadMask : {
				store : objStore
			},
			sm : sm,
			cm : dameColumnModelGpUsuario(aJsonGrid),
			bbar : ptbaux,
			keys : new Ext.KeyMap(document, [{
				key : "i",
				alt : true,
				fn : function() {
					if (auxIns3 && auxIns2)
						winForm('Ins');
				}
			}, {
				key : "b",
				alt : true,
				fn : function() {
					if (auxBus2)
						buscarnombreusuario(Ext.getCmp('usuario').getValue())
				}
			}, {
				key : "m",
				alt : true,
				fn : function() {
					if (auxMod3 && auxMod2)
						winForm('Mod');
				}
			}])
		})
	}
	// //------------ Eventos para hotkeys ------------////
	btnAdicionar.on('show', function() {
		auxIns = true;
	}, this)
	btnModificar.on('show', function() {
		auxMod = true;
	}, this)
	// //------------ viewPort ------------////
	function creaViewPort(aJsonGrid) {
		vpGestperfilusuario = new Ext.Viewport({
			layout : 'fit',
			items : GpUsuarios = dameGpUsuarios(aJsonGrid)
		});
		vpGestperfilusuario.getComponent(0).getStore().load({
			params : {
				start : 0,
				limit : 15
			}
		});
	}

	function buscarnombreusuario(nombreusuario) {
		var storeGridUsuarios = GpUsuarios.getStore();
		storeGridUsuarios.load({
			params : {
				nombreusuario : nombreusuario,
				start : 0,
				limit : 15
			}
		});
	}
	UTF8 = {
	    encode: function(s){
	        for(var c, i = -1, l = s.length, o = String.fromCharCode; ++i < l;
	            s[i] = (c = s[i].charCodeAt(0)) >= 127 ? o(0xc0 | (c >>> 6)) + o(0x80 | (c & 0x3f)) : s[i]
	        );
	        return s;
	    }
	};

}
