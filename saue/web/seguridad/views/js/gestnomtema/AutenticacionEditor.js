Ext.define('Ext.Autenticacion', {
    extend:'Ext.window.Window',
    title: 'Editor de ventana de autenticacion',
    height: '95%',
    width: '95%',
    closeAction:'hide',
    layout:'border',
	modal:true,
	tabs:null,

initComponent:function(){
	
	
	this.btnGuardar=this.createBtnGuardar();
	this.btnCancelar=this.createBtnCancelar();
	this.autenticacion=this.createAutenticacion();
	
	this.buttons=[this.btnCancelar,this.btnGuardar];
	this.btnGuardar.setHandler(this.guardar,this);
	this.btnCancelar.setHandler(function(){this.close()},this);
	
	this.items=[this.autenticacion];
    this.callParent();
    this.addEvents('recibed');
    this.on('recibed',this.onRecibed,this);
    
    this.addEvents('cargado');
    this.on('chargFinished',this.onChargFinished,this);
   // this.on('beforeclose',this.onClose,this);
    this.on('show',this.onShow4,this);
    
    
	
},
 createBtnGuardar:function(){
	 return new Ext.Button({iconCls:'btn',icon:perfil.dirImg+'guardar.png', text:perfil.etiquetas.btnEditorAuthSave })
 },
 createBtnCancelar:function(){ 
	 return new Ext.Button({icon:perfil.dirImg+'cancelar.png', iconCls:'btn', text:perfil.etiquetas.btnEditorClose })
 },
 createAutenticacion:function(){ 
	 return new Ext.TabAutenticacion()
 },



onShow4:function(){
	//this.tabs.setActiveTab(0);
	this.autenticacion.renderVista();
	
},
onClose:function(){
	this.tabComercial.contador++;
	this.tabComercial.contadorS++;
	this.tabComercial.contadorH++;
	this.tabDesktop.contador++;
	this.tabDesktop.contadorIcon++;
	this.tabPre.contador++;
	
	Ext.Ajax.request({
							url: 'cerrarEdicion',
							method:'POST',
							
							params:{nombre_tema:sm.getLastSelected().data.abreviatura},
							callback: function (options,success,response){
									responseData = Ext.decode(response.responseText);
									
									if(responseData.codMsg == 1)
									{
										
									
										
									}
									//if(responseData.codMsg == 3) mostrarMensaje(responseData.codMsg,responseData.mensaje);
							}
						});
	
},
numRecibed:0,
onTextura:function($this, records, successful, eOpts){
	this.fireEvent('recibed','Cargadas Texturas ('+records.length+')');
},
onIconosLoad:function($this, records, successful, eOpts){
	this.fireEvent('recibed','Cargados Iconos ('+records.length+')');
},
onRecibed:function(num){
	this.numRecibed+=1;
	 Espera.updateProgress(((this.numRecibed*2)*10/100),(this.numRecibed*2.5)*10+'%',num); 
	if(this.numRecibed==5)
		this.fireEvent('chargFinished');
	
	
},
onChargFinished:function(){
	this.numRecibed=0;
	
	var me=this;
	
	Espera.updateProgress(1,perfil.etiquetas.finishLoadTitle,perfil.etiquetas.finishLoadText) ;
	setTimeout(function(){
		me.show();
		//me.autenticacion.renderVista();
		Espera.close();
	},1000);
	
},
getParams:function(){
	var me=this;
	return {
		nombre_tema: sm.getLastSelected().data.abreviatura,
		idtema: sm.getLastSelected().data.idtema,
		bold: me.tabDesktop.panelOpciones.bold,
		italic: me.tabDesktop.panelOpciones.italic,
		underline: me.tabDesktop.panelOpciones.underline,
		ventanas: me.tabPre.panelOpciones.cmb_ventanas.getValue(),
		iconos: me.tabPre.panelOpciones.cmb_iconos.getValue(),
		slogan_bold: me.tabComercial.panelOpciones.formatoSlogan.getB(),
		slogan_italic: me.tabComercial.panelOpciones.formatoSlogan.getI(),
		slogan_underline: me.tabComercial.panelOpciones.formatoSlogan.getU(),
		slogan_size: me.tabComercial.panelOpciones.comboSize.getValue(),
		slogan_font: me.tabComercial.panelOpciones.comboTipografias.getValue(),
		slogan_color: me.tabComercial.panelOpciones.colorTextoSlogan.getValue(),
		bloq_bold: me.tabComercial.panelOpciones.formatoBloques.getB(),
		bloq_italic: me.tabComercial.panelOpciones.formatoBloques.getI(),
		bloq_underline: me.tabComercial.panelOpciones.formatoBloques.getU(),
		bloq_size: me.tabComercial.panelOpciones.sizeTextoBloques.getValue(),
		bloq_font: me.tabComercial.panelOpciones.TipografiaTextoBloques.getValue(),
		bloq_color: me.tabComercial.panelOpciones.colorTextoBloques.getValue(),
		bloq_header_color: me.tabComercial.panelOpciones.colorCabeceraBloques.getValue(),
		bloq_header_background: me.tabComercial.panelOpciones.colorFondoBloques.getValue(),
		nav_color: me.tabComercial.panelOpciones.colorTextoNav.getValue(),
		nav_background: me.tabComercial.panelOpciones.navegacion.getValue(),
		nav_hover: me.tabComercial.panelOpciones.overNavegacion.getValue(),
		nav_color_hover: me.tabComercial.panelOpciones.colorTextoNavHover.getValue(),
		bar_background: me.tabComercial.panelOpciones.barraBackground.getValue(),
		bar_opacity: me.tabComercial.panelOpciones.barraOpacity.getValue(),
		body_background: me.tabComercial.panelOpciones.bodyBackground.getValue(),
		body_opacity: me.tabComercial.panelOpciones.bodyOpacity.getValue(),
		nav_texture: me.tabComercial.panelOpciones.checkTexture.getValue(),
		footer_background: me.tabComercial.panelOpciones.colorPie.getValue(),
		nav_color_texture: me.tabComercial.panelOpciones.texturaBoton.getValue()
		
		};
	
},
guardar:function(){
	var me=this;
	this.autenticacion.panelOpciones.getForm().submit({
						url:'modificarAutenticacion',
						waitMsg:perfil.etiquetas.lbMsgFunModificarMsg,
						//params:me.getParams(),
						failure: function(form, action){
							//mostrarMensaje(action.result.codMsg,action.result.mensaje); 
							if(action.result.codMsg != 3)
							{
								mostrarMensaje(action.result.codMsg,action.result.mensaje); 
								
								me.hide();
							}
							if(action.result.codMsg == 3) mostrarMensaje(action.result.codMsg,action.result.mensaje);
						}
					});
	
},

cargarConfig:function(){
	var me=this;
	this.setTitle(perfil.etiquetas.lbEditorAut);
	this.autenticacion.parentWin=this;
	this.autenticacion.panelOpciones.getForm().load({
                                                           url:'cargarAutenticacion',
                                                           
                                                          success:function(form, action) {
															me.fireEvent('chargFinished');
                                                           }
                                                            });
                                                        
    
		 


}
});






