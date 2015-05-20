/*
 * Componente de presentaci�n para gestionar los campos adicionales de los
 * uasuarios.
 * 
 * @package SIGIS @copyright UCID-ERP Cuba @author Oiner Gomez Baryolo @author
 * Darien Garcia Tejo @author Julio Cesar Garc�a Mosquera @author Noel Jesus
 * Rivero Pino
 * 
 * @version 1.0-0
 */

var perfil = window.parent.UCID.portal.perfil;
UCID.portal.cargarEtiquetas('datosusuario', function() {
	cargarInterfaz();
});
// //------------ Declaracion de variables ------------////
	var winIns, winMod, winCamb;
	var auxIns = false;
	var auxMod = false;
	var auxDel = false;
	var auxMod1 = false;
	var auxDel1 = false;
	var auxIns2 = true;
	var auxMod2 = true;
	var auxDel2 = true;
        var denominacionMod,nombreMod,longitudMod,tipoMod,tipocampoMod,visibleMod,idexpresionesMod,descripcionMod;
// //------------ Inicializo el singlenton QuickTips ----------------////
Ext.QuickTips.init();

function cargarInterfaz() {

	var dataVis = [['true', 'True'], ['false', 'False']];
	var storeDataVis = new Ext.data.SimpleStore({
		fields : ['idvis', 'vis'],
		data : dataVis

	});

	var dataTipoData = [['boolean', 'boolean'], ['date', 'date'],
			['int', 'int'], ['time', 'time'], ['varchar', 'varchar']];
	var storeDataTipoData = new Ext.data.SimpleStore({
		fields : ['data', 'iddata'],
		data : dataTipoData

	});

	var dataTipoCamp = [['textfield', 'textfield'], ['datefield', 'datefield']

	];
	var storeDataTipoCamp = new Ext.data.SimpleStore({
		fields : ['idcamp', 'camp'],
		data : dataTipoCamp

	});

	// //------------ Area de Validaciones ------------////
	var esDirIp, tipos;
	tipos = /(^([a-zA-ZáéíóúñüÑ]+ ?[a-zA-ZáéíóúñüÑ]*)+([a-zA-ZáéíóúñüÑ\d\_]+ ?[a-zA-ZáéíóúñüÑ\d\.\-\@\#\_]*)*)+$/;
	esDirIp = /(^(2([0-4][0-9])|2(5[0-5]))|^([0-1]?[0-9]?[0-9]))\.(((2([0-4][0-9])|2(5[0-5]))|([0-1]?[0-9]?[0-9]))\.){2}((2([0-4][0-9])|2(5[0-5]))|([0-1]?[0-9]?[0-9]))$/;

	
	// //------------ Botones ------------////
	btnAdicionar = new Ext.Button({
		id : 'btnAgrCampPerf',
		hidden : true,
		icon : perfil.dirImg + 'adicionar.png',
		iconCls : 'btn',
		text : perfil.etiquetas.lbBtnAdicionar,
		handler : function() {
			winForm('Ins');
		}
	});
	btnModificar = new Ext.Button({
		disabled : true,
		id : 'btnModCampPerf',
		hidden : true,
		icon : perfil.dirImg + 'modificar.png',
		iconCls : 'btn',
		text :perfil.etiquetas.lbBtnModificar,
		handler : function() {
			winForm('Mod');
		}
	});
	btnEliminar = new Ext.Button({
		disabled : true,
		id : 'btnEliCampPerf',
		hidden : true,
		icon : perfil.dirImg + 'eliminar.png',
		iconCls : 'btn',
		text :perfil.etiquetas.lbBtnEliminar ,
		handler : function() {
			eliminarCampo();
		}
	});
	/*
	 * btnAyuda = new Ext.Button({id:'btnAyuCampPerf', hidden:true,
	 * icon:perfil.dirImg+'ayuda.png', iconCls:'btn', text:perfil.etiquetas.lbBtnAyuda
	 * });
	 */
	UCID.portal.cargarAcciones(window.parent.idFuncionalidad);

	// //------------ Store del Grid de Perfiles ------------////
	stGpPerfiles = new Ext.data.Store({
		url : 'cargarcampos',
		reader : new Ext.data.JsonReader({
			totalProperty : "cantidad_filas",
			root : "datos",
			id : "id"
		}, [{
			name : 'idcampo',
			mapping : 'idcampo'
		}, {
			name : 'nombre',
			mapping : 'nombre'
		}, {
			name : 'tipo',
			mapping : 'tipo'
		}, {
			name : 'alias',
			mapping : 'nombreamostrar'
		}, {
			name : 'visible',
			mapping : 'visible'
		}, {
			name : 'longitud',
			mapping : 'longitud'
		}, {
			name : 'tipocampo',
			mapping : 'tipocampo'
		}, {
			name : 'descripcion',
			mapping : 'descripcion'
		}, {
			name : 'idexpresiones',
			mapping : 'idexpresiones'
		}, {
			name : 'denominacion',
			mapping : 'denominacion'
		}]

		)
	});

	sm = new Ext.grid.RowSelectionModel({
		singleSelect : true
	});

	sm.on('beforerowselect', function(smodel, rowIndex, keepExisting, record) {
		btnModificar.enable();
		btnEliminar.enable();
	}, this);

	// //------------ Defino el grid ------------////
	var gpPErfiles = new Ext.grid.GridPanel({
		frame : true,
		region : 'center',
		iconCls : 'icon-grid',
		autoExpandColumn : 'expandir',
		store : stGpPerfiles,
		sm : sm,
		columns : [{
			hidden : true,
			hideable : false,
			dataIndex : 'idcampo'
		}, {
			id : 'expandir',
			header : perfil.etiquetas.lbDenominacion,
			width : 150,
			dataIndex : 'nombre'
		}, {
			header : perfil.etiquetas.lbNombreamostrar,
			width : 150,
			dataIndex : 'alias'
		}, {
			header : perfil.etiquetas.lbTipodeDato,
			dataIndex : 'tipo'
		}, {
			header : perfil.etiquetas.lbVisible,
			width : 50,
			dataIndex : 'visible'
		}, {
			header : perfil.etiquetas.lbLongitud,
			width : 50,
			dataIndex : 'longitud'
		}, {
			header : perfil.etiquetas.lbExpresion,
			width : 150,
			dataIndex : 'denominacion'
		}, {
			header : perfil.etiquetas.lbTipodeCampo,
			width : 150,
			dataIndex : 'tipocampo'
		}],
		loadMask : {
			store : stGpPerfiles
		},
		bbar : new Ext.PagingToolbar({
			pageSize : 15,
			id : 'ptbaux',
			store : stGpPerfiles,
			displayInfo : true,
			displayMsg : perfil.etiquetas.lbTitMsgNingunresultadoparamostrar,
			emptyMsg : perfil.etiquetas.lbTitMsgResultados
		})
	});
	// //------------ Trabajo con el PagingToolbar ------------////
	Ext.getCmp('ptbaux').on('change', function() {
		sm.selectFirstRow();
	}, this)

	// //------------ Renderiar el arbol ------------////
	var panel = new Ext.Panel({
		layout : 'border',
		title : perfil.etiquetas.lbTitDatosdelperfildeusuarios,
		renderTo : 'panel',
		items : [gpPErfiles],
		tbar : [btnAdicionar, btnModificar, btnEliminar,/* btnAyuda */],
		keys : new Ext.KeyMap(document, [{
			key : Ext.EventObject.DELETE,
			fn : function() {
				if (auxDel && auxDel1 && auxDel2)
					eliminarCampo();
			}
		}, {
			key : "i",
			alt : true,
			fn : function() {
				if (auxIns && auxIns2)
					winForm('Ins');
			}
		}, {
			key : "m",
			alt : true,
			fn : function() {
				if (auxMod && auxMod1 && auxMod2)
					winForm('Mod');
			}
		}])
	});
	// //------------ Eventos para hotkeys ------------////
	btnAdicionar.on('show', function() {
		auxIns = true;
	}, this)
	btnEliminar.on('show', function() {
		auxDel = true;
	}, this)
	btnModificar.on('show', function() {
		auxMod = true;
	}, this)
	stGpPerfiles.on('load', function() {
		if (stGpPerfiles.getCount() != 0) {
			auxMod1 = true;
			auxDel1 = true;
		} else {
			auxMod1 = false;
			auxDel1 = false;
		}
	}, this)

	// //---------------------------Store del combobox de
	// expresiones-----------------///
	var storeExp = new Ext.data.Store({
		autoLoad : true,
		url : '../gestexpresiones/cargarexpresiones',
		reader : new Ext.data.JsonReader({
			root : 'datos',
			id : 'idgrupos'

		}, [{
			name : 'idexpresiones',
			mapping : 'idexpresiones'
		}, {
			name : 'denominacion',
			mapping : 'denominacion'
		}])
	});
	var vpGestPerfil = new Ext.Viewport({
		layout : 'fit',
		items : panel
	})
	stGpPerfiles.load({
		params : {
			start : 0,
			limit : 15
		}
	});

	// //------------ Formulario de perfil ------------////
	var regPerfil = new Ext.FormPanel({
		labelAlign : 'top',
		frame : true,
		bodyStyle : 'padding:5px 5px 0',
		items : [{
			layout : 'column',
			items : [{
				columnWidth : .33,
				layout : 'form',
				items : [{
					xtype : 'textfield',
					fieldLabel : perfil.etiquetas.lbDenominacion,
					id : 'nombre',
					allowBlank : false,
					blankText:perfil.etiquetas.lbMsgBlank,
					regex : tipos,
					// maskRe:tipos,
					regexText : perfil.etiquetas.lbMsgSedebeempezarconunaletra,
					tabIndex : 1,
					anchor : '95%'
				}, {
					xtype : 'combo',
					fieldLabel : perfil.etiquetas.lbTipodeDato,
					id : 'tipo',
					displayField : 'iddata',
					valueField : 'data',
					store : dataTipoData,
					triggerAction : 'all',
					typeAhead : true,
					allowBlank : false,
                                        blankText:perfil.etiquetas.lbMsgBlank,
					mode : 'local',
					editable : false,
					emptyText : perfil.etiquetas.lbMsgSeleccionetipodedato,
					tabIndex : 4,
					anchor : '95%'

				}]
			}, {
				columnWidth : .33,
				layout : 'form',
				items : [{
					xtype : 'textfield',
					fieldLabel : perfil.etiquetas.lbNombreamostrar,
					id : 'alias',
					allowBlank : false,
					blankText:perfil.etiquetas.lbMsgBlank,
					// maskRe:/^([a-zA-Z]+ ?[a-zA-Z]*)+$/,
					regex : /^([a-zA-Z]+ ?[a-zA-Z]*)+$/,
					regexText : perfil.etiquetas.lbSolopuedeentrarvaloresa,
					tabIndex : 2,
					anchor : '95%'
				}, {
					xtype : 'combo',
					fieldLabel : perfil.etiquetas.lbTipodeCampo,
					id : 'tipocampo',
					displayField : 'camp',
					valueField : 'idcamp',
					store : storeDataTipoCamp,
					triggerAction : 'all',
					typeAhead : true,
					allowBlank : false,
                                        blankText:perfil.etiquetas.lbMsgBlank,
					mode : 'local',
					editable : false,
					emptyText : perfil.etiquetas.lbMsgSeleccionetipodecampo,
					tabIndex : 5,
					anchor : '95%'
				}]
			}, {
				columnWidth : .33,
				layout : 'form',
				items : [{
					xtype : 'textfield',
					fieldLabel : perfil.etiquetas.lbLongitud,
					id : 'longitud',
					regexText : perfil.etiquetas.lbMsgSolopuedeentrarvaloresnumericos,
					// maskRe:/^\d*$/,
					regex : /^\d*$/,
					allowBlank : false,
                                        blankText:perfil.etiquetas.lbMsgBlank,
					tabIndex : 3,
					anchor : '95%'
				}, {
					xtype : 'combo',
					fieldLabel : perfil.etiquetas.lbVisible,
					id : 'visible',
					displayField : 'vis',
					valueField : 'idvis',
					triggerAction : 'all',
					typeAhead : true,
					store : storeDataVis,
					mode : 'local',
					editable : false,
					forceSelection : true,                                        
					allowBlank : false,
                                        blankText:perfil.etiquetas.lbMsgBlank,
					selectOnFocus : true,
					emptyText : perfil.etiquetas.lbMsgSeleccioneunaopcion,
					tabIndex : 6,
					anchor : '95%'
				}]
			}, {
				columnWidth : .5,
				layout : 'form',
				items : [new Ext.form.ComboBox({
					emptyText : perfil.etiquetas.lbMsgSeleccionarexpresion,
					editable : false,
					fieldLabel : perfil.etiquetas.lbMsgExpresionesregular,
					store : storeExp,
                                        id:'idexp',
					valueField : 'idexpresiones',
					displayField : 'denominacion',
					hiddenName : 'idexpresiones',
					forceSelection : true,
					typeAhead : true,
					mode : 'local',
					allowBlank : false,
                                        blankText:perfil.etiquetas.lbMsgBlank,
					triggerAction : 'all',
					selectOnFocus : true,
					tabIndex : 7,
					anchor : '95%'
				})]
			}, {
				columnWidth : .5,
				layout : 'form',
				items : [{
					xtype : 'textarea',
					fieldLabel : perfil.etiquetas.lbDescripcion,
					id : 'descripcion',
					tabIndex : 8,
					anchor : '100%'
				}]
			}]
		}]
	});

	// //------------ Cargar la ventana ------------////
	function winForm(opcion) {

		switch (opcion) {
			case 'Ins' : {
				if (!winIns) {
					winIns = new Ext.Window({
						modal : true,
						closeAction : 'hide',
						layout : 'fit',
						title : perfil.etiquetas.lbTitAdicionarcamposalperfil,
						width : 530,
						height : 260,
						buttons : [{
							icon : perfil.dirImg + 'cancelar.png',
							iconCls : 'btn',
							text : perfil.etiquetas.lbBtnCancelar,
							handler : function() {
								winIns.hide();
							}
						}, {
							icon : perfil.dirImg + 'aplicar.png',
							iconCls : 'btn',
							text : perfil.etiquetas.lbBtnAplicar,
							handler : function() {
								adicionarCampos('apl');
							}
						}, {
							icon : perfil.dirImg + 'aceptar.png',
							iconCls : 'btn',
							text : perfil.etiquetas.lbBtnAceptar,
							handler : function() {
								adicionarCampos();
							}
						}]
					});
					winIns.on('show', function() {
						auxIns2 = false;
						auxMod2 = false;
						auxDel2 = false;
					}, this)
					winIns.on('hide', function() {
						auxIns2 = true;
						auxMod2 = true;
						auxDel2 = true;
					}, this)
				}
				regPerfil.getForm().reset();
				winIns.add(regPerfil);
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
						title : perfil.etiquetas.lbTitModificarcamposdelperfil,
						width : 530,
						height : 260,
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
								modificarCampo();
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
				regPerfil.getForm().reset();
				winMod.add(regPerfil);
				winMod.doLayout();
				winMod.show();
				regPerfil.getForm().loadRecord(sm.getSelected());
                                denominacionMod = Ext.getCmp('nombre').getValue();
                                nombreMod= Ext.getCmp('alias').getValue();
                                longitudMod= Ext.getCmp('longitud').getValue();
                                tipoMod= Ext.getCmp('tipo').getValue();
                                tipocampoMod= Ext.getCmp('tipocampo').getValue();
                                visibleMod= Ext.getCmp('visible').getValue();
                                idexpresionesMod= Ext.getCmp('idexp').getValue();
                                descripcionMod= Ext.getCmp('descripcion').getValue();
			}
				break;
		}
	}

	function adicionarCampos(apl) {

		if (regPerfil.getForm().isValid()) {
			if (Ext.getCmp('tipocampo').getValue() == 'datefield') {
				if(Ext.getCmp('tipo').getValue() == 'date'){
				if (Ext.getCmp('longitud').getValue() == 10) {
					regPerfil.getForm().submit({
						url : 'insertarcampo',
						waitMsg :perfil.etiquetas.lbMsgFunAddMsg,
						failure : function(form, action) {
							if (action.result.codMsg != 3) {
//								mostrarMensaje(action.result.codMsg,
//										perfil.etiquetas.lbMsgInformacionAdd);
								regPerfil.getForm().reset();
								if (!apl)
								winIns.hide();
								stGpPerfiles.reload();
								sm.clearSelections();
								btnModificar.disable();
								btnEliminar.disable();
							}
//							if (action.result.codMsg == 3)
//								mostrarMensaje(action.result.codMsg,perfil.etiquetas.lbMsgInformacionAdd);
						}
					});
				}
				else
					mostrarMensaje(3, perfil.etiquetas.lbMsgErrorLongitud);
				} 
				else
					mostrarMensaje(3, perfil.etiquetas.lbMsgErrordatos);
			} else {
				regPerfil.getForm().submit({
					url : 'insertarcampo',
					waitMsg :perfil.etiquetas.lbMsgFunAddMsg,
					failure : function(form, action) {
						if (action.result.codMsg != 3) {
//							mostrarMensaje(action.result.codMsg,
//									perfil.etiquetas.lbMsgInformacionAdd);
							regPerfil.getForm().reset();
							if (!apl)
								winIns.hide();
							stGpPerfiles.reload();
							sm.clearSelections();
							btnModificar.disable();
							btnEliminar.disable();
						}
//						if (action.result.codMsg == 3)
//							mostrarMensaje(action.result.codMsg,
//									action.result.mensaje);

					}
				});
			}
		} else
			mostrarMensaje(3, perfil.etiquetas.lbMsgErrorEnCamops);
	}

	function modificarCampo() {
		if (regPerfil.getForm().isValid()) {
                    var denMod = denominacionMod != Ext.getCmp('nombre').getValue();
                    var nomMod = nombreMod != Ext.getCmp('alias').getValue();
                    var longMod = longitudMod != Ext.getCmp('longitud').getValue();
                    var tMod = tipoMod != Ext.getCmp('tipo').getValue();
                    var tcMod = tipocampoMod != Ext.getCmp('tipocampo').getValue();
                    var vMod = visibleMod != Ext.getCmp('visible').getValue();
                    var idexMod = idexpresionesMod != Ext.getCmp('idexp').getValue();
                    var desMod = descripcionMod != Ext.getCmp('descripcion').getValue();
                    if (denMod||nomMod||longMod||tMod||tcMod||vMod||idexMod||desMod){
                    
			if (Ext.getCmp('tipocampo').getValue() == 'datefield') {
				if(Ext.getCmp('tipo').getValue() == 'date'){
				if (Ext.getCmp('longitud').getValue() == 10) {
					regPerfil.getForm().submit({
						url : 'modificarcampo',
						waitMsg :perfil.etiquetas.lbMsgFunModificarMsg,
						params : {
							idcampo : sm.getSelected().data.idcampo
						},
						failure : function(form, action) {
							if (action.result.codMsg != 3) {
//								mostrarMensaje(action.result.codMsg,perfil.etiquetas.lbMsgInformacionMod);
								stGpPerfiles.reload();
								winMod.hide();
							}
//							if (action.result.codMsg == 3)
//								mostrarMensaje(action.result.codMsg,
//										action.result.mensaje);

						}
					});
				}else
					mostrarMensaje(3, perfil.etiquetas.lbMsgErrorLongitud)
				} else
					mostrarMensaje(3, perfil.etiquetas.lbMsgErrordatos);
			} else {
				regPerfil.getForm().submit({
					url : 'modificarcampo',
					waitMsg :perfil.etiquetas.lbMsgFunModificarMsg,
					params : {
						idcampo : sm.getSelected().data.idcampo
					},
					failure : function(form, action) {
						if (action.result.codMsg != 3) {
//							mostrarMensaje(action.result.codMsg,
//									perfil.etiquetas.lbMsgInformacionMod);
							stGpPerfiles.reload();
							winMod.hide();
						}
//						if (action.result.codMsg == 3)
//							mostrarMensaje(action.result.codMsg,
//									action.result.mensaje);

					}
				});
			}
                        
                    }
                    else
                        mostrarMensaje(3,perfil.etiquetas.NoModify);
		} else
			mostrarMensaje(3, perfil.etiquetas.lbMsgErrorEnCamops);
	}

	function eliminarCampo() {
		mostrarMensaje(2,
				perfil.etiquetas.lbMsgConfirmacionEliminar,
				elimina);
		function elimina(btnPresionado) {
			if (btnPresionado == 'ok') {
				Ext.Ajax.request({
					url : 'eliminarcampo',
					method : 'POST',
					params : {
						idcampo : sm.getSelected().data.idcampo
					},
					callback : function(options, success, response) {
						responseData = Ext.decode(response.responseText);
						if (responseData.codMsg == 1) {
//							mostrarMensaje(responseData.codMsg,
//									perfil.etiquetas.lbMsgInformacionElim);
							stGpPerfiles.reload();
							sm.clearSelections();
							btnModificar.disable();
							btnEliminar.disable();
						}
//						if (responseData.codMsg == 3)
//							mostrarMensaje(responseData.codMsg,
//									responseData.mensaje);
					}
				});
			}
		}
	}
}
