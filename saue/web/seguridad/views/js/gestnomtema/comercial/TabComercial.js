
Ext.define('Ext.TabComercial',{
	extend:'Ext.panel.Panel',
	alias:'widget.TabComercial',
	
    title: 'Entorno Comercial',
    layout:'border',
                //html: "Can't see me cause I'm disabled",
    contador:0,
    contadorS:0,
    contadorH:0,
    contadorPie:0,
    contadorBot:0,
    cantImage:3,
    contImage:0,
    alone:false,
    panelOpciones:null,
    createPanelOpciones:function(){
		return new Ext.form.EditorComercial({});
	},
    //CSS del editor
    styleSheetEdit:null,
    ruleSheet:null,
    
    panelVistaPrevia:null, 
    createPanelVistaPrevia:function(){
		return new Ext.panel.VistaPresentacion({});
	},
    isOpen:true,
    closeMask:function(){
		this.setLoadsMsg=false;
	
		if(this.alone==false){
		this.contImage++;
		
		if(this.cantImage<=this.contImage){
			
				this.parentWin.setLoading(false);
				this.contImage=0;
			
		}
	 }
	 if(this.alone==true){
		 this.parentWin.setLoading(false)
		 this.alone=false;
	 }
	
	},
    initComponent:function(){
		var me=this;
		this.setTitle(perfil.etiquetas.comercialTitle);
		this.panelOpciones=this.createPanelOpciones();
		this.panelVistaPrevia=this.createPanelVistaPrevia();
		this.formatoTpl=this.getFormat();
		
		//this.ruleSheet=Ext.util.CSS.createRule(this.styleSheetEdit,'.btn-comercial-hov:hover','background-color:black;');
		//Ext.util.CSS.refreshCache();
		this.items=[this.panelOpciones,this.panelVistaPrevia];
		this.panelOpciones.on('selected',function(){me.renderVista();});
		this.callParent();
		this.on('activate',function(){
			
			this.renderVista();
			
			this.panelVistaPrevia.getEl().select('img').on('load',this.closeMask,this);
			
		},this);
		this.on('beforeshow',function(){
			// console.info('beforeshow','comercial')
			 if(this.isOpen){
				 this.contador++;
				 this.contadorS++;
				 this.contadorH++;
				 this.contadorPie++;
				 this.contadorBot++;
				 this.parentWin.setLoading(perfil.etiquetas.lbCargInitImag)
			}
			this.isOpen=false;
			
			
			 
			 if(this.panelOpciones.checkTexture.getValue()){
				 
				 Ext.util.CSS.removeStyleSheet('comercial-id-css');
				 me.contadorBot++;
				 me.styleSheetEdit=Ext.util.CSS.createStyleSheet('.btn-comercial-hov:hover{background:#'+me.panelOpciones.overNavegacion.getValue()+' !important;background-image:url(/lib/ExtJS/temas/'+sm.getLastSelected().data.abreviatura+'/cache/texture_bot_1_hover.png?n='+me.contadorBot+') !important;background-size:cover !important; cursor: pointer;}','comercial-id-css');
			}else{
				Ext.util.CSS.removeStyleSheet('comercial-id-css');
				
				this.styleSheetEdit=Ext.util.CSS.createStyleSheet('.btn-comercial-hov:hover{background:#'+me.panelOpciones.overNavegacion.getValue()+' !important; cursor: pointer;}','comercial-id-css');
				
			}
		},this);
		
		
		
		me.panelOpciones.fileFondo.on('change',function(){
		me.panelOpciones.getForm().submit({
				url:'actualizarFondoPre',
				waitMsg:perfil.etiquetas.loadImage,
				params:{nombre_tema:sm.getLastSelected().data.abreviatura},
				failure: function(form, action){
							if(action.result.codMsg == 3) mostrarMensaje(action.result.codMsg,action.result.mensaje);
							else{
							me.contador++;
							var preFon=Ext.get(document.getElementById('fonComercial'));
							preFon.on('load',function(){
								me.parentWin.setLoading(false)
								me.alone=false;
							})
							document.getElementById('fonComercial').src='/lib/ExtJS/temas/'+sm.getLastSelected().data.abreviatura+'/cache/comercial_1.jpg?n='+me.contador+'';
						    me.parentWin.setLoading(perfil.etiquetas.lbAplicImagen);
						    me.alone=true;
						    }
							
						}
			});
		});
		me.panelOpciones.fileBanner.on('change',function(){
		me.panelOpciones.getForm().submit({
				url:'actualizarFondoPre',
				waitMsg:perfil.etiquetas.loadImage,
				params:{nombre_tema:sm.getLastSelected().data.abreviatura},
				failure: function(form, action){
							if(action.result.codMsg == 3) mostrarMensaje(action.result.codMsg,action.result.mensaje);
							else{
							me.contadorH++;
							var preFon=Ext.get(document.getElementById('vist-header'));
							preFon.on('load',function(){
								me.parentWin.setLoading(false)
								me.alone=false;
							})
							document.getElementById('vist-header').src='/lib/ExtJS/temas/'+sm.getLastSelected().data.abreviatura+'/cache/header_1.jpg?n='+me.contadorH+'';
						    me.parentWin.setLoading(perfil.etiquetas.lbAplicImagen);
						    me.alone=true;
						    }
							
						}
			});
		 });
		 
		me.panelOpciones.fileSlogan.on('change',function(){
			
			me.panelOpciones.getForm().submit({
				url:'actualizarFondoPre',
				waitMsg:perfil.etiquetas.loadImage,
				params:{nombre_tema:sm.getLastSelected().data.abreviatura},
				failure: function(form, action){
							
							if(action.result.codMsg == 3) mostrarMensaje(action.result.codMsg,action.result.mensaje);
							else{
							me.contadorS++;
							Ext.get(document.getElementById('vist-slogan')).on('load',function(){
								me.parentWin.setLoading(false)
								me.alone=false;
							});
							
					
							document.getElementById('vist-slogan').src='/lib/ExtJS/temas/'+sm.getLastSelected().data.abreviatura+'/cache/slogan_1.png?n='+me.contadorS+'';
						   me.parentWin.setLoading(perfil.etiquetas.lbAplicImagen);
						    me.alone=true;
						    }
							
						}
			});
		},this);
	
	 
	 me.panelOpciones.colorPie.on('select',function(){
		me.panelOpciones.getForm().submit({
				url:'actualizarTextura',
				waitMsg:perfil.etiquetas.waitTexture,
				params:{nombre_tema:sm.getLastSelected().data.abreviatura,texture:1},
				failure: function(form, action){
							if(action.result.codMsg == 3) mostrarMensaje(action.result.codMsg,action.result.mensaje);
							else{
							me.contadorPie++;
							me.renderVista();
						    }
							
						}
			});
		  
		
	 });
	 
	  me.panelOpciones.navegacion.on('select',function(){
		if(this.panelOpciones.checkTexture.getValue()){
			
			this.updateTextureButton();
		}else
		    this.renderVista();
	  
	  },this);
	  
	  me.panelOpciones.overNavegacion.on('select',function(){
		if(this.panelOpciones.checkTexture.getValue()){
			 
			this.updateTextureButton();
		}else{
		    Ext.util.CSS.removeStyleSheet('comercial-id-css');
			
			 this.styleSheetEdit=Ext.util.CSS.createStyleSheet('.btn-comercial-hov:hover{background:#'+me.panelOpciones.overNavegacion.getValue()+' !important; cursor: pointer;}','comercial-id-css');
		    this.renderVista();
		}
	  
	  },this);
	  
	  
	  
	   me.panelOpciones.checkTexture.on('change',function($this, newValue, oldValue){
		if(newValue){
			
			this.panelOpciones.texturaBoton.selectFirst();
			this.updateTextureButton();
		}else{
			  Ext.util.CSS.removeStyleSheet('comercial-id-css');
			
			 this.styleSheetEdit=Ext.util.CSS.createStyleSheet('.btn-comercial-hov:hover{background:#'+me.panelOpciones.overNavegacion.getValue()+' !important; cursor: pointer;}','comercial-id-css');
			 this.renderVista();
		}
		
	},this)
	 
	 me.panelOpciones.texturaBoton.on('select',function(){
		me.updateTextureButton();
		  
		
	 },this);
		
	},
	
	datosTpl:null,
	
	updateTextureButton:function(){
		var me=this; 
		if(me.panelOpciones.texturaBoton.getValue())
		me.panelOpciones.getForm().submit({
				url:'actualizarTextura',
				waitMsg:perfil.etiquetas.waitTexture2,
				params:{nombre_tema:sm.getLastSelected().data.abreviatura,texture:2,dir:me.panelOpciones.texturaBoton.findRecordByValue(me.panelOpciones.texturaBoton.getValue()).get('dir')},
				failure: function(form, action){
							if(action.result.codMsg == 3) mostrarMensaje(action.result.codMsg,action.result.mensaje);
							else{
							me.contadorBot++;
							Ext.util.CSS.removeStyleSheet('comercial-id-css');
							me.contadorBot++;
							me.styleSheetEdit=Ext.util.CSS.createStyleSheet('.btn-comercial-hov:hover{background:#'+me.panelOpciones.overNavegacion.getValue()+' !important;background-image:url(/lib/ExtJS/temas/'+sm.getLastSelected().data.abreviatura+'/cache/texture_bot_1_hover.png?n='+me.contadorBot+') !important;background-size:cover !important; cursor: pointer;}','comercial-id-css');
							me.renderVista();
						    }
							
						}
			});
	},
	
	formatoTpl:null,
	getFormat:function(){
		return new Ext.Template(
	'<div><img id="fonComercial" src="{fondo}"'+
	 'height="100%" width="100%" style="position:absolute;top:0px;"/>'+
	 '<div style="position:absolute;top:0px;height:90%;width:100%;">'+
	 '<div style="height:100%;width:20%;float:left;"></div>'+
	 '<div style="height:100%;width:60%;float:left;"><div style="height:30%;width:99%;margin:0.5%">'+
	 '<div style="height:80%;width:100%;">'+
	 '<div style="position:absolute;top:40px;left:25%;"><b style="{color_slogan}{tipografia_slogan}">Sauxe</b><br>'+
	 '<b style="font-size: 15px;">Marco de trabajo</b></div> '+
	 '<div style="position:absolute;left:65%;height:20%;width:100px;top:20px;">'+
	 '</div> </div>'+
	 '<div style="height:20%;width:100%;{barra_background};{barra_opacity};border-radius:0px 0px 5px 5px;">'+
	 ''+
	 ''+
	 '</div></div>'+
	 
	  
	 '<div style="height:67%;width:99%;{body_background};{body_opacity};margin-top:2%">'+
	 
	 
	 '<div style="height:99%;width:25%;float:left;margin:0.5%"><div style="height:40%;width:100%;">'+
	 '</div><div style="height:99%;width:73%;float:left;margin:0.5%;">'+
	 ''+
	 '</div></div></div><div style="height:100%;width:20%;float:red;"></div></div>'+
	 
	
	 
	
	 '</div>'+
	
	
	'<div>'+
	 '<div style="position:absolute;top:0px;height:90%;width:100%;">'+
	 '<div style="height:100%;width:20%;float:left;"></div>'+
	 '<div style="height:100%;width:60%;float:left;"><div style="height:30%;width:99%;margin:0.5%">'+
	 '<div style="height:80%;width:100%;"><img id="vist-header" src="{source_header}"  height="100%" width="100%">'+
	 '<div style="position:absolute;top:40px;left:25%;"><b style="{color_slogan}{tipografia_slogan}">Sauxe</b><br>'+
	 '<b style="font-size: 15px;">Marco de trabajo</b></div> '+
	 '<div style="position:absolute;left:65%;height:20%;width:100px;top:20px;">'+
	 '<img id="vist-slogan" src="{source_slogan}"  height="100%" width="100%"></div> </div>'+
	 '<div style="height:20%;width:100%;border-radius:0px 0px 5px 5px;">'+
	 '<div class="btn-comercial-hov" style="height:80%;float:left;background:green;margin:.4%;padding:5px;{color_nav}{color_texto_nav};{nav_texture}">'+
	 '<b style="{color_texto_nav}font-size:80%;">'+perfil.etiquetas.btnComercialStart+'</b></div><div class="btn-comercial-hov" style="height:80%;float:left;background:green;margin:.4%;padding:5px;{color_nav};{nav_texture}">'+
	 '<b style="{color_texto_nav}font-size:80%;">'+perfil.etiquetas.btnComercialContact+'</b></div></div></div>'+
	 
	  
	 '<div style="height:67%;width:99%;margin-top:2%">'+
	 
	 
	 '<div style="height:99%;width:25%;float:left;margin:0.5%"><div style="height:40%;width:100%;">'+
	 '<div style="height:20%;width:100%;background:green;padding:5px;{color_fondo_cabecera}">'+
	 '<b style="{color_texto_cabecera}"> '+perfil.etiquetas.lbComercialBlocks+'</b></div>'+
	 '<div style="height:80%;width:100%;margin-top:10px;{color_texto_bloques}{tipografia_bloques}">'+
	 '<span>'+perfil.etiquetas.lbComercialContentBlocks+'</span></div></div><div style="height:40%;width:100%;">'+
	 '<div style="height:20%;width:100%;background:green;padding:5px;{color_fondo_cabecera}">' +
	 '<b style="{color_texto_cabecera}">'+perfil.etiquetas.lbComercialBlocks+'</b></div>'+
	 '<div style="height:80%;width:100%;margin-top:10px;{color_texto_bloques}{tipografia_bloques}">'+
	 '<span>'+perfil.etiquetas.lbComercialContentBlocks+'</span></div></div></div><div style="height:99%;width:73%;float:left;margin:0.5%;">'+
	 '<div style="width:100%;float:top;margin-bottom:5px;"><b style="color:white;font-size:14px">'+perfil.etiquetas.lbComercialUbication+'</b></div>'+
	 '<div style="height:90%;width:100%;float:top;background:white;padding:15px;">'+perfil.etiquetas.lbComercialContentPage+'</div></div></div></div><div style="height:100%;width:20%;float:red;"></div></div>'+
	 
	
	 
	 '<div style="position:absolute;bottom:0px;height:8%;width:100%;background:url({pie});"></div>'+
	 '</div>'
	)
	},
	renderVista:function(){
	//console.info('render vista comercial')
	this.recojerDatos();
	
	//console.info(this.panelVistaPrevia.getEl())
this.formatoTpl.overwrite(this.panelVistaPrevia.getEl(), this.datosTpl);

	
    //this.panelVistaPrevia.body.fadeIn();   
	},
	
	recojerDatos:function(){
		 var tipog='';
         
          if(this.panelOpciones.formatoSlogan.getB())
             tipog+='font-weight:bold;';
          if(this.panelOpciones.formatoSlogan.getI())
             tipog+='font-style: italic;';
          if(this.panelOpciones.formatoSlogan.getU())
             tipog+='text-decoration: underline;';
             
        
          if(this.panelOpciones.comboSize.getValue()){
              tipog+='font-size:'+this.panelOpciones.comboSize.getValue()+'px;';
			}
		if(this.panelOpciones.comboTipografias.getValue())
		 tipog+= 'font-family:'+this.panelOpciones.comboTipografias.getValue()+' !important;';
		 
		 var tipogBloques='';
         
          if(this.panelOpciones.formatoBloques.getB())
             tipogBloques+='font-weight:bold;';
          if(this.panelOpciones.formatoBloques.getI())
             tipogBloques+='font-style: italic;';
          if(this.panelOpciones.formatoBloques.getU())
             tipogBloques+='text-decoration: underline;';
             
        
          if(this.panelOpciones.sizeTextoBloques.getValue()){
              tipogBloques+='font-size:'+this.panelOpciones.sizeTextoBloques.getValue()+'px;';
			}
		if(this.panelOpciones.TipografiaTextoBloques.getValue())
		 tipogBloques+= 'font-family:'+this.panelOpciones.TipografiaTextoBloques.getValue()+' !important;';
		
		var opacityBar=this.panelOpciones.barraOpacity.getValue()/100;
		var opacityBod=this.panelOpciones.bodyOpacity.getValue()/100;
		
		this.datosTpl={
			 color_slogan: 'color:#'+this.panelOpciones.colorTextoSlogan.getValue()+';',
             
             tipografia_slogan: tipog,
             color_nav:'background:#'+this.panelOpciones.navegacion.getValue()+';',
             color_texto_nav:'color:#'+this.panelOpciones.colorTextoNav.getValue()+' !important;',
             
             color_fondo_cabecera:'background:#'+this.panelOpciones.colorFondoBloques.getValue()+';',
             color_texto_cabecera:'color:#'+this.panelOpciones.colorCabeceraBloques.getValue()+';',
             tipografia_bloques: tipogBloques,
             color_texto_bloques:'color:#'+this.panelOpciones.colorTextoBloques.getValue()+';',
             
             fondo:'/lib/ExtJS/temas/'+sm.getLastSelected().data.abreviatura+'/cache/comercial_1.jpg?n='+this.contador+'',
             source_header:'/lib/ExtJS/temas/'+sm.getLastSelected().data.abreviatura+'/cache/header_1.jpg?n='+this.contadorH+'',
             source_slogan:'/lib/ExtJS/temas/'+sm.getLastSelected().data.abreviatura+'/cache/slogan_1.png?n='+this.contadorS+'',
			 barra_background:'background:#'+this.panelOpciones.barraBackground.getValue(),
             body_background:'background:#'+this.panelOpciones.bodyBackground.getValue(),
             barra_opacity:'opacity:'+opacityBar,
             body_opacity:'opacity:'+opacityBod,
             color:'trasparent',
             
             pie:'/lib/ExtJS/temas/'+sm.getLastSelected().data.abreviatura+'/cache/footer-1_1.png?n='+this.contadorPie+''
           
             };
          if(this.panelOpciones.checkTexture.getValue()){
			   this.datosTpl.nav_texture='background-image:url(/lib/ExtJS/temas/'+sm.getLastSelected().data.abreviatura+'/cache/texture_bot_1.png?n='+this.contadorBot+');background-size:cover;';
			  //this.datosTpl.nav_texture='background-image:url('+this.panelOpciones.texturaBoton.findRecordByValue(this.panelOpciones.texturaBoton.getValue()).get('dir')+'?n='+this.contadorBot+');background-size:cover';
			  //alert(t);
			 //console.debug(this.panelOpciones.texturaBoton.findRecordByValue(this.panelOpciones.texturaBoton.getValue()))
			  
		  }
	}
	
}); 
