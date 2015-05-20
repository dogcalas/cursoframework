	
Ext.define('Ext.TabPresentacion',{
	extend:'Ext.panel.Panel',
	alias:'widget.TabPresentacion',
	
    title: 'Entrada al sistema',
    layout:'border',
                //html: "Can't see me cause I'm disabled",
    contador:0,
    contadorLogo:0,
    panelOpciones:null,
    cantImage:2,
    contImage:0,
    alone:false,
    createPanelOpciones:function(){
		return new Ext.form.EditorPresentacion({});
	},
	
    panelVistaPrevia:null, 
    createPanelVistaPrevia:function(){
		return new Ext.panel.VistaPresentacion({});
	},
	
	iframeEd:null,
	containerEd:null,
	iconEd:null,
	
	closeVistaPrevia:function(){
		/*if(document.getElementById('iframe-editor')){
			var a=document.getElementById('iframe-editor');
			a.parentNode.removeChild(a);
		}
		
		if(document.getElementById('container-editor'))	{
			var b=document.getElementById('container-editor');
			b.parentNode.removeChild(b);
		}
		
		if(document.getElementById('container-msg')){
			var c=document.getElementById('container-msg');
			c.parentNode.removeChild(c);
		}
		*/
		
	},
	isOpen:true,
	
	initVistaPrevia:function(){
		if(this.isOpen){
		this.parentWin.setLoading(perfil.etiquetas.lbEditorEsperaPre)
		
		this.iframeEd=this.getEl().createChild({
			tag:'iframe',
			name:'frame-presentacion',
			src:'../../views/js/gestnomtema/presentacion/VistaPreviaVentanas.html',
			id:'iframe-editor'
			});	
		Ext.DomHelper.applyStyles(this.iframeEd,{
                            'width':'400px',
                            'right':'40px',
                           
                            'position':'absolute',
                            'z-index':'10000',
                            'top':'30px',
                            'height':'400px',
                            'border':'none'
          });
        
          this.iframeEd.on('load',function(){
				this.parentWin.setLoading(false);
				
				this.isOpen=false
		   },this)
         
          this.iframeEd=this.iframeEd.dom;
			   
		
			
			   frames['frame-presentacion'].valor=this.panelOpciones.cmb_ventanas.getValue();	
			   frames['frame-presentacion'].tema=sm.getLastSelected().data.abreviatura;
			   frames['frame-presentacion'].tab=this.parentWin;
		   }else{
			    this.parentWin.setLoading(perfil.etiquetas.lbCargInitImag)
			   
				frames['frame-presentacion'].valor=this.panelOpciones.cmb_ventanas.getValue();	
				frames['frame-presentacion'].tema=sm.getLastSelected().data.abreviatura;
				frames['frame-presentacion'].tab=this.parentWin;
				frames['frame-presentacion'].setCss();
		   }
		   	
		   
	},
	
	closeMask:function(){
		
		if(this.isOpen==false){
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
	}
	},
    items:[this.panelOpciones,this.panelVistaPrevia],       
	initComponent:function(){
		var me=this;
		this.setTitle(perfil.etiquetas.preTitle);
		this.panelOpciones=this.createPanelOpciones();
		this.panelVistaPrevia=this.createPanelVistaPrevia();
		
		me.items=[me.panelOpciones,me.panelVistaPrevia];
		me.panelOpciones.cmb_ventanas.on('change',me.renderExt,me);
		
		this.on('activate',function(){
			this.initVistaPrevia();
			this.renderVista();
			this.panelVistaPrevia.getEl().select('img').on('load',this.closeMask,this);
		},this)
		
		
		
		Ext.ComponentManager.get('idfondoPre').on('change',function(){
			
			var oldvalue=this.getValue();
			me.panelOpciones.getForm().submit({
				url:'actualizarFondoPre',
				waitMsg:perfil.etiquetas.loadImage,
				params:{nombre_tema:sm.getLastSelected().data.abreviatura},
				failure: function(form, action){
							//me.renderVista();
							if(action.result.codMsg == 3) mostrarMensaje(action.result.codMsg,action.result.mensaje);
							else{
							me.contador++;
							var preFon=Ext.get(document.getElementById('fonPreRender'));
							preFon.on('load',function(){
								me.parentWin.setLoading(false)
								me.alone=false;
							})
							document.getElementById('fonPreRender').src='/lib/ExtJS/temas/'+sm.getLastSelected().data.abreviatura+'/cache/entrada_1.jpg?n='+me.contador;
							me.parentWin.setLoading(perfil.etiquetas.lbAplicImagen);
							me.alone=true;
							
						}
						}
			});
			})
			
			
			
			this.panelOpciones.fileIcons.on('change',function(){
			
			var oldvalue=this.getValue();
			me.panelOpciones.getForm().submit({
				url:'importarIconos',
				waitMsg:perfil.etiquetas.importIcons,
				params:{nombre_tema:sm.getLastSelected().data.abreviatura},
				failure: function(form, action){
							//me.renderVista();
							if(action.result.codMsg == 3) mostrarMensaje(action.result.codMsg,action.result.mensaje);
							else{
							mostrarMensaje(action.result.codMsg,action.result.mensaje);
							me.panelOpciones.cmb_iconos.getStore().load();
							}
						}
			});
			});
			
			this.panelOpciones.fileTemas.on('change',function(){
			
				var oldvalue=this.getValue();
				me.panelOpciones.getForm().submit({
					url:'importarTemaExt',
					waitMsg:perfil.etiquetas.importTema,
					params:{nombre_tema:sm.getLastSelected().data.abreviatura},
					failure: function(form, action){
								
								if(action.result.codMsg == 3) mostrarMensaje(action.result.codMsg,action.result.mensaje);
								else{
								mostrarMensaje(action.result.codMsg,action.result.mensaje);
								me.panelOpciones.cmb_ventanas.getStore().load();
								}
							}
				});
			});
			
			this.panelOpciones.fileLogo.on('change',function(){
				me.panelOpciones.getForm().submit({
				url:'actualizarFondoPre',
				waitMsg:perfil.etiquetas.loadImage,
				params:{nombre_tema:sm.getLastSelected().data.abreviatura},
				failure: function(form, action){
							//me.renderVista();
							if(action.result.codMsg == 3) mostrarMensaje(action.result.codMsg,action.result.mensaje);
							else{
							me.contadorLogo++;
							
							frames['frame-presentacion'].imagen.on('load',function(){
								me.parentWin.setLoading(false)
								me.alone=false;
							})
							frames['frame-presentacion'].imagen.dom.src='/lib/ExtJS/temas/'+sm.getLastSelected().data.abreviatura+'/cache/entry_logo_1.png?n='+me.contadorLogo;
							me.parentWin.setLoading(perfil.etiquetas.lbAplicImagen);
							me.alone=true;
							//console.info(document.getElementById('fonPreRender'));
							
							//Ext.ComponentManager.get('idfondoPre').setValue(oldvalue);
							
							//Ext.util.CSS.refreshCache();
							//alert('xx')
							
						}
						}
			});	
				
			});
			
			Ext.ComponentManager.get('idanimacion').on('change',function(){
			
			var oldvalue=this.getValue();
			me.panelOpciones.getForm().submit({
				url:'actualizarFondoPre',
				waitMsg:perfil.etiquetas.loadImage,
				params:{nombre_tema:sm.getLastSelected().data.abreviatura},
				failure: function(form, action){
							//me.renderVista();
							if(action.result.codMsg == 3) mostrarMensaje(action.result.codMsg,action.result.mensaje);
							else{
							me.contador++;
							var preFon=Ext.get(document.getElementById('animacionPre'));
							preFon.on('load',function(){
								me.parentWin.setLoading(false)
								me.alone=false;
							})
							document.getElementById('animacionPre').src='/lib/ExtJS/temas/'+sm.getLastSelected().data.abreviatura+'/cache/cargando_1.gif?n='+me.contador;
							me.parentWin.setLoading(perfil.etiquetas.lbAplicImagen);
							me.alone=true;
						}
						}
			});
			})
			
			
		me.callParent();
		
	},
	constructor:function(){
		this.callParent();
		
	},
	//crear css,
 createjscssfile:function(filename, filetype,ventana){
 if (filetype=="js"){ //if filename is a external JavaScript file
  var fileref=ventana.document.createElement('script')
  fileref.setAttribute("type","text/javascript")
  fileref.setAttribute("src", filename)
 }
 else if (filetype=="css"){ //if filename is an external CSS file
  var fileref=ventana.document.createElement("link")
  fileref.setAttribute("rel", "stylesheet")
  fileref.setAttribute("type", "text/css")
  fileref.setAttribute("href", filename)
 }
 return fileref
},
	//remover css,
	  replacejscssfile:function(oldfilename, newfilename, filetype,ventana){
 var targetelement=(filetype=="js")? "script" : (filetype=="css")? "link" : "none" //determine element type to create nodelist using
 var targetattr=(filetype=="js")? "src" : (filetype=="css")? "href" : "none" //determine corresponding attribute to test for
 var allsuspects=ventana.document.getElementsByTagName(targetelement)
 for (var i=allsuspects.length; i>=0; i--){ //search backwards within nodelist for matching elements to remove
  if (allsuspects[i] && allsuspects[i].getAttribute(targetattr)!=null && allsuspects[i].getAttribute(targetattr).indexOf(oldfilename)!=-1){
   var newelement=this.createjscssfile(newfilename, filetype,ventana)
   allsuspects[i].parentNode.replaceChild(newelement, allsuspects[i])
  }
 }
},
primera:false,
opening:false,	
renderExt:function(combo, newValue, oldValue, eOpts ){
	
	if(this.primera&&this.opening==false){
	this.replacejscssfile('../../../../../lib/ExtJS/temas/resources4/ExtThemes/'+oldValue+'/'+oldValue+'-all.css','../../../../../lib/ExtJS/temas/resources4/ExtThemes/'+newValue+'/'+newValue+'-all.css','css',frames['frame-presentacion']);
    //frames[0].reed.hide();
   // frames[0].reed.show();
   frames['frame-presentacion'].reed.doLayout();
   
    }
	
	this.primera=true;
	
},
	
//renderizar vista previa
	datosTpl:null,
	formatoTpl:new Ext.Template(
	'<img id="fonPreRender" style="position:absolute;top:0px;" height="100%" width="100%" src="{fondo}"/><img align="center" id="animacionPre" style="position:absolute;left:50px;bottom:20px;" src="{carg}"/>'
	),
	
   renderVista:function(){
	
	this.recojerDatos();
	
	//console.info(this.panelVistaPrevia.getEl())
	
this.formatoTpl.overwrite(this.panelVistaPrevia.getEl(), this.datosTpl);
	
    //this.panelVistaPrevia.body.fadeIn();   
	},
	recojerDatos:function(){
		 var tipoV=this.panelOpciones.cmb_ventanas.getValue();
         
            tipoV='/images/vistaTemas/vent_'+tipoV+'.png';
          
        var me=this;                                                                          
		this.datosTpl={
			//ventana:tipoV,
			carg:'/lib/ExtJS/temas/'+sm.getLastSelected().data.abreviatura+'/cache/cargando_1.gif?n='+me.contador,
			fondo:'/lib/ExtJS/temas/'+sm.getLastSelected().data.abreviatura+'/cache/entrada_1.jpg?n='+me.contador,
			ifvist:'../../views/js/gestnomtema/'+me.panelOpciones.cmb_ventanas.getValue()+'.html',
		}
	}
	
	});
	
