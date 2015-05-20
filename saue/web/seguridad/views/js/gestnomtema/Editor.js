Ext.define('Ext.Editor', {
    extend:'Ext.window.Window',
    title: 'Editor de Temas Dinamicos',
    alias:'widget.editortema',
    height: '95%',
    width: '95%',
    iconCls:'icon-editor',
    closeAction:'hide',
    layout:'border',
	modal:true,
	tabs:null,

initComponent:function(){
	
	
	this.btnGuardar=this.createBtnGuardar();
	this.btnCancelar=this.createBtnCancelar();
	this.tabDesktop=this.createTabDesktop();
	this.tabPre=this.createTabPre();
	this.tabPre.parentWin=this;
	this.tabComercial=this.createTabComercial();
	this.buttons=[this.btnCancelar,this.btnGuardar];
	this.btnGuardar.setHandler(this.guardar,this);
	this.btnCancelar.setHandler(function(){this.close()},this);
	this.tabs=Ext.create('Ext.tab.Panel',{
        itemId:'entornos',
        activeTab: 0,
		//anchor:'95% 100%',
		region:'center',
        //plain: true,
       
        
        items: [this.tabDesktop,this.tabComercial,this.tabPre]
    });
	this.items=[this.tabs];
    this.callParent();
    this.addEvents('recibed');
    this.on('recibed',this.onRecibed,this);
    this.tabComercial.panelOpciones.texturaBoton.getStore().on('load',this.onTextura,this);
    this.tabPre.panelOpciones.cmb_iconos.getStore().on('load',this.onIconosLoad,this);
    this.addEvents('cargado');
    this.on('chargFinished',this.onChargFinished,this);
    this.on('beforeclose',this.onClose,this);
    this.on('show',this.onShow4,this);
    var me=this;
	
    this.tabs.items.each(function(value){
			value.setLoadsMsg=true;
			value.parentWin=me;
			
		
	})
    
	
},

 createBtnGuardar:function(){
	 return new Ext.Button({iconCls:'btn',icon:perfil.dirImg+'guardar.png', text:perfil.etiquetas.btnEditorSave })
 },
 createBtnCancelar:function(){ 
	 return new Ext.Button({icon:perfil.dirImg+'cancelar.png', iconCls:'btn', text:perfil.etiquetas.btnEditorClose })
 },
 createTabDesktop:function(){ 
	 return new Ext.TabDesktop()
 },
 createTabPre:function(){ 
	 return new Ext.TabPresentacion()
 },
 createTabComercial:function(){ 
	 return new Ext.TabComercial()
 },


onShow4:function(){
	var me=this;
	
	
	
	this.tabDesktop.panelOpciones.radios.setCheked(this.tabDesktop.panelOpciones.radioSelected);
	
	
	
	
	
},
onClose:function(){
	this.tabComercial.contador++;
	this.tabComercial.contadorS++;
	this.tabComercial.contadorH++;
	this.tabDesktop.contador++;
	this.tabDesktop.contadorIcon++;
	this.tabPre.contador++;
	this.tabPre.contadorLogo++;
	this.tabPre.closeVistaPrevia();
	Ext.Ajax.request({
							url: 'cerrarEdicion',
							method:'POST',
							
							params:{nombre_tema:sm.getLastSelected().data.abreviatura},
							callback: function (options,success,response){
									responseData = Ext.decode(response.responseText);
									
									if(responseData.codMsg == 1)
									{
										
									
										
									}
									if(responseData.codMsg == 3) mostrarMensaje(responseData.codMsg,responseData.mensaje);
							}
						});
	
},
numRecibed:0,
onTextura:function($this, records, successful, eOpts){
	this.fireEvent('recibed',perfil.etiquetas.finishLoadTexture+' ('+records.length+')');
},
onIconosLoad:function($this, records, successful, eOpts){
	this.fireEvent('recibed',perfil.etiquetas.finishLoadIcons+' ('+records.length+')');
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
		me.tabDesktop.renderVista();
		//me.tabs.setActiveTab(0);
		
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
		nav_color_texture: me.tabComercial.panelOpciones.texturaBoton.getValue(),
		denominacion:this.tabPre.panelOpciones.name.getValue(),
		descripcion:this.tabPre.panelOpciones.descript.getValue()
		};
	
},
guardar:function(){
	var me=this;
	if(this.tabDesktop.panelOpciones.getForm().isValid()&&this.tabPre.panelOpciones.getForm().isValid()&&this.tabComercial.panelOpciones.getForm().isValid())
	this.tabDesktop.panelOpciones.getForm().submit({
						url:'modificartemaPresonalizadoBase',
						waitMsg:perfil.etiquetas.lbMsgFunModificarMsg,
						params:me.getParams(),
						failure: function(form, action){
							mostrarMensaje(action.result.codMsg,action.result.mensaje); 
							if(action.result.codMsg != 3)
							{
								
								stGpTema.load();
								me.tabPre.closeVistaPrevia();
								me.hide();
							}
							if(action.result.codMsg == 3) mostrarMensaje(action.result.codMsg,action.result.mensaje);
						}
					});
	else
		mostrarMensaje(3,perfil.etiquetas.lbMsgErrorEnCamops);
},

cargarConfig:function(){
	var me=this;
	this.setTitle(perfil.etiquetas.editorTitle+sm.getLastSelected().data.denominacion);
	this.numRecibed=0;
	this.tabPre.opening=true;
	//this.tabPre.isOpen=true;
	this.tabComercial.isOpen=true;
	this.tabPre.panelOpciones.cmb_iconos.getStore().load();
	this.tabPre.panelOpciones.descript.setValue(sm.getLastSelected().get('descripcion'));
	this.tabPre.panelOpciones.name.setValue(sm.getLastSelected().get('denominacion'));
	this.tabDesktop.panelOpciones.getForm().load({
                                                           url:'cargarDesktop',
                                                           params:{idtema:sm.getLastSelected().data.idtema,nombre_tema:sm.getLastSelected().get('abreviatura')}
                                                          ,success:function(form, action) {
                                                        var fID =action.result.data.shadow;
                                                        //Ext.getCmp(fID).setValue(true);
                                                                 if(fID){
                                                                        //  Ext.getCmp('val_sombreado').setValue(true);
                                                                        //   Ext.getCmp('val_puro').setValue(false);
                                                                         // opcionBoton=2; 
                                                                          me.tabDesktop.panelOpciones.radioSelected=true;
                                                                      }else{
                                                                        //  Ext.getCmp('val_puro').setValue(true);
                                                                         // Ext.getCmp('val_sombreado').setValue(false);
                                                                         // opcionBoton=1;
                                                                           me.tabDesktop.panelOpciones.radioSelected=false;
                                                                      }
                                                                      if(action.result.data.inicio_bold){
																		 me.tabDesktop.panelOpciones.bold=true;
																		 Ext.getCmp('Neg').toggle(true,true);
																		 }else{
																		 me.tabDesktop.panelOpciones.bold=false;
																		 Ext.getCmp('Neg').toggle(false,true);
																	 }
																	  if(action.result.data.inicio_italic){
																		 me.tabDesktop.panelOpciones.italic=true;
																		 Ext.getCmp('Ita').toggle(true,true);
																		 }else{
																		 me.tabDesktop.panelOpciones.italic=false;
																		 Ext.getCmp('Ita').toggle(false,true);
																	 }
																	  if(action.result.data.inicio_underline){
																		 me.tabDesktop.panelOpciones.underline=true;
																		 Ext.getCmp('Und').toggle(true,true);
																		} else{
																		 me.tabDesktop.panelOpciones.underline=false;
																		 Ext.getCmp('Und').toggle(false,true);
																	 }
                                                                 me.fireEvent('recibed',perfil.etiquetas.finishLoadDesktop);
																//Espera.updateProgress(,'Desktop') 
                                                               // icon.setValue('Defecto Gris');
                                                           }
                                                            });
                                                        
      this.tabPre.panelOpciones.getForm().load({
		  url:'cargarPresentacion',
                                                           params:{idtema:sm.getLastSelected().data.idtema}
                                                          ,success:function(form, action) {
                                                       // var fID =action.result.data.shadow;
																me.tabPre.opening=false;
																me.fireEvent('recibed',perfil.etiquetas.finishLoadPre)
																
                                                           }
		  });
	this.tabComercial.panelOpciones.texturaBoton.getStore().load({
		scope:this,
		callback:function(){
			
			this.tabComercial.panelOpciones.getForm().load({
															url:'cargarComercial',
                                                           params:{idtema:sm.getLastSelected().data.idtema}
                                                          ,success:function(form, action) {
                                                       // var fID =action.result.data.shadow;
																
																me.fireEvent('recibed',perfil.etiquetas.finishLoadComercial);
																
																//me.tabComercial.panelOpciones.checkTexture.setValue(action.result.data.nav_texture);
																//if(me.tabComercial.panelOpciones.checkTexture)
																//	me.tabComercial.panelOpciones.texturaBoton.setValue(action.result.data.nav_color_texture);
																me.tabComercial.panelOpciones.formatoSlogan.setData(action.result.data);
																me.tabComercial.panelOpciones.formatoBloques.setData(action.result.data);
                                                           }
		  });
		}
	});	  
	
		 

	
}
});






