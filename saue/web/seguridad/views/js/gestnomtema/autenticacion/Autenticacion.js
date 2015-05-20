
	
Ext.define('Ext.TabAutenticacion',{
	extend:'Ext.panel.Panel',
	alias:'widget.TabAutenticacion',
	
    layout:'border',
                //html: "Can't see me cause I'm disabled",
    contador:0,
    contadorLogo:0,
    region:'center',
   
    cantImage:1,
    contImage:0,
    alone:false,
    //panel
    createPanelOpciones:function(){
		return new Ext.form.EditorAutenticacion;
	},
   
   
    //vista previa
    createPanelVistaPrevia:function(){
		return new Ext.panel.VistaPresentacion();
	},
     
   closeMask:function(){
		//console.info(arguments[1])
	
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
		
		this.panelOpciones=this.createPanelOpciones();
		this.panelVistaPrevia=this.createPanelVistaPrevia();
		
		me.items=[me.panelOpciones,me.panelVistaPrevia];
		me.panelOpciones.on('selected',me.renderVista,me);
		
		me.panelOpciones.fileFondo.on('change',function(){
		me.panelOpciones.getForm().submit({
				url:'actualizarFondoPre',
				waitMsg:perfil.etiquetas.loadImage,
				params:{nombre_tema:sm.getLastSelected().data.abreviatura},
				failure: function(form, action){
							if(action.result.codMsg == 3) mostrarMensaje(action.result.codMsg,action.result.mensaje);
							else{
							me.contador++;
							Ext.get(document.getElementById('fonAutRender')).on('load',function(){
								me.setLoading(false)
								me.alone=false;
							});
							document.getElementById('fonAutRender').src='/lib/ExtJS/temas/working directory/aut.jpg?n='+me.contador+'';
						   me.setLoading(perfil.etiquetas.lbAplicImagen);
						   me.alone=true;
						    }
							
						}
			});
		  
		  
	 }); 
	 
	
	 this.formatoTpl=this.createTpl();
	 
	
		me.callParent();
	
		
		
		
	},
	
//renderizar vista previa
	datosTpl:null,
	formatoTpl:null,
	
	createTpl:function(){
			return new Ext.Template(
'<div  style="position:absolute;top:0px;height:100%;width:100%"><img id="fonAutRender" style="position:absolute;top:0px;" height="100%" width="100%" src="{srcFondo}"/></div>'+
'<div  style="position:absolute;top:0px;height:100%;width:100%">'+
'<div style="height:100%;width:15%;float:left;"></div>'+
'<div style="height:100%;width:70%;float:left;">'+
'<div  style="height:30%;width:100%">'+

'<span style="height:100%;width:30%;float:left;"></span>'+
'<div style="height:100%;width:30%;float:left;text-align:center;">'+
'<span style="width:100%;height:80%;float:left;text-align:center;"></span>'+
'<span style="width:100%;height:5%;float:left;text-align:center;"><span class="idioma_sel">Espannol</span><a href="#" class="idioma_no_sel">English</a></span>'+
'</div>'+

'<span style="height:100%;width:30%;float:left;"></span>'+

'</div>'+
'<div class="bars" style="height:40%;width:100%;border-top:solid 2px white;border-bottom:solid 2px white;">'+
'<div  style="height:100%;width:0%;float:left;"></div>'+
'<div  style="height:100%;width:100%;float:left;">'+
'<div  style="height:100%;width:20%;float:left;"></div>'+
'<div  style="height:100%;width:60%;float:left;">'+

'<div  style="height:20%;width:100%;float:left;"></div>'+
'<table width="100%" height="60%" style="float:left;"><tr><td class="etiquetas">'+perfil.etiquetas.userName+'</td><td><input class="campos" type="text" style="width:100%;"/></td></tr><tr><td class="etiquetas">'+perfil.etiquetas.claveAcceso+'</td><td><input type="password" class="campos" style="width:100%;"/></td></tr><tr><td></td><td><input type="submit" class="btn-entry" value="'+perfil.etiquetas.lbSubmitEnter+'"/></td></tr></table>'+
'<div  style="height:20%;width:100%;float:left;"></div>'+

'</div>'+

'<div  style="height:100%;width:20%;float:left;"></div>'+
'</div>'+
'<div  style="height:100%;width:0%;float:left;"></div>'+
'</div>'+
'<div  style="height:30%;width:100%">'+
'<span style="height:100%;width:30%;float:left;"></span>'+
'<span class="copyr" style="height:100%;width:30%;float:left;text-align:center;margin-top:15px;">Copyright 2014</span>'+
'</div>'+
'</div>'+
'<div style="height:100%;width:15%;float:left;"></div>'+

'</div>'
	)
		
	},
	
   renderVista:function(){
	this.parentWin.setLoading(perfil.etiquetas.lbCargInitImag);
	this.recojerDatos();
	
	this.formatoTpl.overwrite(this.panelVistaPrevia.getEl(), this.datosTpl);
	this.panelVistaPrevia.getEl().select('img').on('load',this.closeMask,this);
	
	Ext.util.CSS.removeStyleSheet('autenticacion-css');
			
	Ext.util.CSS.createStyleSheet(this.css,'autenticacion-css');
	 
	},
	recojerDatos:function(){
		
             
        
                                                                                  
		this.datosTpl={
			// barra: 'background:#'+this.panelOpciones.barraTarea.getValue()+';background-image: -moz-linear-gradient(top, rgba(255,255,255,0.5), rgba(255,255,255,0.2) 49%, rgba(0,0,0,0.05) 51%, rgba(0,0,0,0.15));',
           
           
             srcFondo:'/lib/ExtJS/temas/working directory/aut.jpg?n='+this.contador+'',
          
            
             //texto:'color:#'+this.panelOpciones.colorTextoInicio.getValue()+' !important;',
             
          
                                                                                        
            // color2:''+this.panelOpciones.inicio.getValue()+''
             
             };
		
		this.css='.campos{font-family: Verdana, Geneva, sans-serif;font-size: 12px;color:#'+this.panelOpciones.inputColor.getValue()+';padding: 5px 10px 5px 10px;'+
		'border: 1px solid #999;background:#'+this.panelOpciones.inputFondo.getValue()+';text-shadow: 0px 1px 1px #666;text-decoration: none;-moz-box-shadow: 0 1px 3px #111;'+
		' -webkit-box-shadow: 0 1px 3px #111;box-shadow: 0 1px 3px #111;width:100%;border-radius: 4px;-moz-border-radius: 4px;'+
		' -webkit-border-radius: 4px;} '+
		'.btn-entry{font-family: Verdana, Geneva, sans-serif; font-size: 12px;color:#'+this.panelOpciones.submitColor.getValue()+'; padding: 5px 10px 5px 10px;'+
		'border: solid 1px #CCC; background:#'+this.panelOpciones.submitFondo.getValue()+'; text-shadow: 0px 1px 0px #000;border-radius: 5px; -moz-border-radius: 5px;'+
		' -webkit-border-radius: 5px;box-shadow: 0 1px 3px #111;-moz-box-shadow: 3px 3px 1px #999;-webkit-box-shadow: 3px 3px 1px #999;'+
		' cursor: pointer;} .etiquetas{color:#'+this.panelOpciones.etiquetas.getValue()+'}'+
		'.idioma_sel{color:#'+this.panelOpciones.idiomaSel.getValue()+'} .idioma_no_sel{color:#'+this.panelOpciones.idiomaNoSel.getValue()+'}'+
		'.copyr{color:#'+this.panelOpciones.copyrigth.getValue()+'} .bars{border-color:#'+this.panelOpciones.barHoriz.getValue()+' !important;}'+
		'.btn-entry:hover{background:#'+this.panelOpciones.submitFondoHover.getValue()+';color:#'+this.panelOpciones.submitColorHover.getValue()+';}';
		
		
		
		
			
			
		},
		
		convertirHex:function(text){
                            var pri=text.substring(0,2);
                             var sec=text.substring(2,4);
                             var ter=text.substring(4,6);
                              
                             
                             var re=new Array(this.convHex(pri), this.convHex(sec), this.convHex(ter));
                             return re;
    },
    convHex: function(num){
                            
                            //num=num.toString();
                            
                            var num2=num.charAt(0);
                            
                            switch(num2){
                                case 'A':{
                                   num2=10*16;
                                   break;
                                        
                                }
                                case 'B':{
                                     num2=11*16;   
                                       break; 
                                }
                                case 'C':{
                                     num2=12*16;   
                                       break; 
                                }
                                case 'D':{
                                     num2=13*16;   
                                       break; 
                                }
                                case 'E':{
                                      num2=14*16;  
                                       break; 
                                }
                                case 'F':{
                                      num2=15*16;  
                                        break;
                                }
                                default:{
                                      num2=parseInt(num2,10)*16;  
                                       break; 
                                }
                                
                                
                            }
                            
                            
                             var num3=num.charAt(1);
                            
                            switch(num3){
                                case 'A':{
                                   num3=num2+10;
                                   break;
                                        
                                }
                                case 'B':{
                                     num3=num2+11;   
                                       break; 
                                }
                                case 'C':{
                                     num3=num2+12;   
                                       break; 
                                }
                                case 'D':{
                                     num3=num2+13;   
                                       break; 
                                }
                                case 'E':{
                                      num3=num2+14;  
                                       break; 
                                }
                                case 'F':{
                                      num3=num2+15;  
                                        break;
                                }
                                default:{
                                     
                                      num3=parseInt(num3,10)+num2;  
                                        break;
                                }
                                
                                
                            }
                           
                            return num3;
                            
                            
                        }
    
    
    
	}
	
	);


