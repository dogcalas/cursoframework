Ext.define('Ext.ux.desktop.StartMenu', {
    extend: 'Ext.panel.Panel',

    requires: [
        'Ext.menu.Menu',
        'Ext.toolbar.Toolbar'
    ],

    ariaRole: 'menu',

    cls: 'x-menu ux-start-menu barraTituloMenu',

    defaultAlign: 'tl-bl?',
	
    iconCls: 'user',

    floating: false,
	region: 'west',
    shadow: true,

    // We have to hardcode a width because the internal Menu cannot drive our width.
    // This is combined with changing the align property of the menu's layout from the
    // typical 'stretchmax' to 'stretch' which allows the the items to fill the menu
    // area.
    width: 250,
    height: 300,
	maxHeight: 300,
    initComponent: function() {
        var me = this, menu = me.menu;
		
        me.menu = new Ext.menu.Menu({
            cls: 'ux-start-menu-body',
            border: false,
            floating: false,
            items: menu
        });
        me.menu.layout.align = 'stretch';

        me.items = [me.menu];
        me.layout = 'fit';

       // Ext.menu.Manager.register(me);
        me.callParent();
        // TODO - relay menu events
		
        me.toolbar = new Ext.toolbar.Toolbar(Ext.apply({
            dock: 'right',
            cls: 'ux-start-menu-toolbar',
            vertical: true,
            width: 100,
            listeners: {
                add: function(tb, c) {
                    c.on({
                        click: function() {
                           // me.hide();
                        }
                    });
                }
            }
        }, me.toolConfig));

        me.toolbar.layout.align = 'stretch';
        me.addDocked(me.toolbar);
		
        delete me.toolItems;
    },

    addMenuItem: function() {
        var cmp = this.menu;
        cmp.add.apply(cmp, arguments);
    },

    addToolItem: function() {
        var cmp = this.toolbar;
        cmp.add.apply(cmp, arguments);
    }
}); // StartMenu


Ext.define('Ext.ux.desktop.Wallpaper', {
    extend: 'Ext.Component',

    alias: 'widget.wallpaper',

   // cls: 'ux-wallpaper',
    html: '<img width="100%" height="100%"/><div id="identidadOrg" style="position:absolute;width:400px;height:150px;bottom:10px;left:50%;margin-left:-200px;border:dashed 1px black;align:center;background-size:400px 150px;text-align : center;padding-top:74px;"><span style="font-style: italic;font-weight:bold;font-family:arial,helvetica;color:white;"></span><div>',

    stretch: false,
    wallpaper: null,
    stateful  : true,
    stateId  : 'desk-wallpaper',

    afterRender: function () {
        var me = this;
        me.callParent();
        me.setWallpaper(me.wallpaper, me.stretch);
    },

    applyState: function () {
        var me = this, old = me.wallpaper;
        me.callParent(arguments);
        if (old != me.wallpaper) {
            me.setWallpaper(me.wallpaper);
        }
    },

    getState: function () {
        return this.wallpaper && { wallpaper: this.wallpaper };
    },

    setWallpaper: function (wallpaper, stretch) {
        var me = this, imgEl, bkgnd;

        me.stretch = (stretch !== false);
        me.wallpaper = wallpaper;

        if (me.rendered) {
            imgEl = me.el.dom.firstChild;
			imgEl.src = wallpaper;
           

          
        }
        return me;
    }
});

	
Ext.define('Ext.TabDesktop',{
	extend:'Ext.panel.Panel',
	alias:'widget.TabDesktop',
	//iconCls: 'icon-desktop',
    title: 'Estilo Windows',
    layout:'border',
                //html: "Can't see me cause I'm disabled",
    contador:0,
    contadorIcon:0,
    contadorOrg:0,
   
   cantImage:2,
   contImage:0,
   alone:false,
    //panel
    createPanelOpciones:function(){
		return new Ext.form.EditorDesktop();
	},
   
   
    //vista previa
    createPanelVistaPrevia:function(){
		return new Ext.panel.VistaPresentacion({layout:'border'});
	},
     
   
	//barra
	createToobar:function(){
		return new Ext.toolbar.Toolbar({
	height:30,
	border:'0 0 0 0',
	dock:'top'	
	});
	},
	
	
     //escritorio
    createWall:function(){
		return new Ext.ux.desktop.Wallpaper({id:'dgdgdgd'});
	},
	
	closeMask:function(){
		this.setLoadsMsg=false;
		
		if(this.alone==false){
		this.contImage++;
		
		if(this.cantImage<=this.contImage){
			
				this.parentWin.setLoading(false);
				this.contImage=0;
				this.cantImage=1;
			
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
		this.toolbar=this.createToobar();
		this.wall=this.createWall();
		this.setTitle(perfil.etiquetas.desktopTitle);
		
		this.on('activate',function(){
			
			
			if(this.setLoadsMsg)
				this.panelVistaPrevia.getEl().select('img').on('load',this.closeMask,this);
		},this);
		
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
							//document.getElementById('fonDesktopRender').src='/lib/ExtJS/temas/'+sm.getLastSelected().data.abreviatura+'/desktop_1.jpg?n='+me.contador+'';
						    me.wall.setWallpaper('/lib/ExtJS/temas/'+sm.getLastSelected().data.abreviatura+'/cache/desktop_1.jpg?n='+me.contador+'');
						   me.parentWin.setLoading(perfil.etiquetas.lbAplicImagen);
						    me.alone=true;
						    me.recojerDatos();
						    }
							
						}
			});
		  
		  
	 }); 
	 
	 me.panelOpciones.fileIcono.on('change',function(){
		me.panelOpciones.getForm().submit({
				url:'actualizarFondoPre',
				waitMsg:perfil.etiquetas.loadImage,
				params:{nombre_tema:sm.getLastSelected().data.abreviatura},
				failure: function(form, action){
							if(action.result.codMsg == 3) mostrarMensaje(action.result.codMsg,action.result.mensaje);
							else{
							me.contadorIcon++;
							 
							//document.getElementById('icon-desktop-editor').src='/lib/ExtJS/temas/'+sm.getLastSelected().data.abreviatura+'/icon_1.gif?n='+me.contadorIcon;
						   me.btnIni.setIcon('/lib/ExtJS/temas/'+sm.getLastSelected().data.abreviatura+'/cache/icon_1.gif?n='+me.contadorIcon);
						    me.recojerDatos();
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
							me.contadorOrg++;
							document.getElementById('identidadOrg').style.backgroundImage='url(/lib/ExtJS/temas/'+sm.getLastSelected().data.abreviatura+'/cache/org_1.png?n='+me.contadorOrg+')';
						   // me.toolbar.setIcon('/lib/ExtJS/temas/'+sm.getLastSelected().data.abreviatura+'/cache/icon_1.gif?n='+me.contadorIcon);
						   me.recojerDatos();
						    }
							
						}
			});
		  
		  
	 });
		
		me.startMenu = new Ext.ux.desktop.StartMenu();
		me.btnIni=new Ext.Button({
		
              
               
                scale   : 'medium',
                overCls:'btn-desktop-inicio-over-editor',
				cls:'btn-desktop-inicio-editor',
                //para alineacion de menu de inicio
                menuAlign: 'tl-bl?',
                text: perfil.etiquetas.inicioText,
				//menu: me.startMenu
	});	
	me.btnTarea=new Ext.Button({
		
              
               
                scale   : 'medium',
                overCls:'btn-desktop-tarea-editor-over',
				cls:'btn-desktop-tarea-editor',
                //para alineacion de menu de inicio
                
                text: perfil.etiquetas.tarea,
				//menu: me.startMenu
	});	
	
	
		me.callParent();
		this.panelVistaPrevia.add(this.wall);
		this.toolbar.add(this.btnIni);
		this.toolbar.add(new Ext.toolbar.Separator({xtype:'tbseparator',margin:'0 0 0 0'}));
		this.toolbar.add(this.btnTarea);
		this.toolbar.doLayout();
		
		this.panelVistaPrevia.addDocked(this.toolbar);
		this.panelVistaPrevia.add(this.startMenu);
		this.panelVistaPrevia.doLayout();
		//this.startMenu.render(this.panelVistaPrevia.getEl());
		
		
		
	},
	
//renderizar vista previa
	datosTpl:null,
	formatoTpl:new Ext.Template(
'<div id="paraB" style="position:absolute;top:0px;left:0px;width:100%;"></div><div  style="position:absolute;top:0px;height:100%;width:100%"><img id="fonDesktopRender" style="position:absolute;top:0px;" height="100%" width="100%" src="{desktop}"/><div style="background:{color}"><div style="height:30px;width:95px;background:#{fondo};position:absolute;z-index:6;border-right-style: solid; border-right-width: 3px;border-right-color: black;"><div style="-moz-border-radius: 5px;-webkit-border-radius: 5px;border-radius: 5px;color:#{texto};{inicioMoz};height:100%;width:95%;padding-left:10px;padding-right:10px;padding-top:5px;margin-left:5px;margin-right:10px;z-index:5;overflow:hidden;"><a href="#" style="text-decoration: none;"><img id="icon-desktop-editor" src="{iconoMenu}" style="margin-right:2px;"/><span align="center" style="{tipografia};{estilo_letra};{texto};background:red;">Inicio</span></a></div></div><div style="height:30px;width:100%;{barra};position:absolute;right:0px;z-index:5;"></div><div style="background:url({tipoV}) no-repeat;height:416px;width:631px;position:absolute;top:80px;left:40px"></div></div></div><div style="position:absolute;bottom:20px;left:30px;height: 40px;border-radius:5px;z-index:3;"><img src="/lib/ExtJS/temas/Personalizado/fondos/icon-warning.gif" style="display: inline;"/><b style="display: inline;margin-left:7px"> Las imagenes no seran aplicadas hasta su carga en el servidor !!</b></div><div style="position:absolute;bottom:22px;left:27px;height: 40px;width: 400px;border-radius:5px;background:white;opacity:0.5;z-index:2;"></div>;"><img src="/lib/ExtJS/temas/Personalizado/fondos/acaxiaD.png" style="display: inline;position:absolute;bottom:2px;right:15px;height:75px;width: 200px;"/></div>'
	),
	
   renderVista:function(){
	var desktopBefore=this.datosTpl?this.datosTpl.desktop:'NO DATA';
	this.recojerDatos();
	console.info(desktopBefore,this.datosTpl.desktop)
	if( desktopBefore!=this.datosTpl.desktop)
		this.parentWin.setLoading(perfil.etiquetas.lbCargInitImag)
   
	this.wall.setWallpaper(this.datosTpl.desktop);
	document.getElementById('identidadOrg').style.backgroundImage='url(/lib/ExtJS/temas/'+sm.getLastSelected().data.abreviatura+'/cache/org_1.png?n='+this.contadorOrg+')';
	
	this.btnIni.setIcon(this.datosTpl.iconoMenu);
	this.toolbar.getEl().applyStyles(this.datosTpl.barra);
	
    this.startMenu.getEl().first('',true).style=this.datosTpl.headerMenu;
    this.startMenu.getEl().first('div.x-toolbar',false).applyStyles(this.datosTpl.dockMenu);
    Ext.util.CSS.removeStyleSheet('desktop-id-css-editor');
			
	Ext.util.CSS.createStyleSheet('.btn-desktop-inicio-over-editor:hover{background:#'+this.panelOpciones.overInicio.getValue()+
	' !important;background-image: -moz-linear-gradient(rgba(255,255,255,.15) 49%, rgba(0,0,0,.1) 51%, rgba(0,0,0,.15)) !important;border-radius:5px !important;}'+
	'.btn-desktop-tarea-editor{background-color:#'+this.panelOpciones.tarea.getValue()+';background-image:-moz-linear-gradient(top, rgba(255,255,200,0.5), rgba(255,255,200,0.2) 49%, rgba(0,0,0,0.05) 51%, rgba(0,0,0,0.15));'+
	
	'border-radius: 3px 3px 3px 3px;border-style: outset;border: 1px solid #333;height:25px;padding-left: 5px;color:white !important;margin-left:5px !important;}'+
	'.btn-desktop-tarea-editor .x-btn-inner{color:white !important;}'+
	'.btn-desktop-tarea-editor-over{background-color: #111;background-image: -moz-linear-gradient(top, rgba(255,255,255,0.5), rgba(255,255,255,0.2) 49%, rgba(0,0,0,0.05) 51%, rgba(0,0,0,0.15));}'+
	'.btn-desktop-inicio-editor{'+this.datosTpl.inicioMoz+'}'+
	'.btn-desktop-inicio-editor .x-btn-inner{'+this.datosTpl.texto+this.datosTpl.tipografia+this.datosTpl.estilo_letra+'}','desktop-id-css-editor');
   
   this.toolbar.doLayout();
	
    this.parentWin.tabs.setActiveTab(0);
	},
	recojerDatos:function(){
		 var tipog='';
         
          if(this.panelOpciones.bold)
             tipog+='font-weight:bold;';
          if(this.panelOpciones.italic)
             tipog+='font-style: italic;';
          if(this.panelOpciones.underline)
             tipog+='text-decoration: underline;';
             
        
          if(this.panelOpciones.comboSize.getValue()){
              tipog+='font-size:'+this.panelOpciones.comboSize.getValue()+'px;';
			}
        
         var col=this.convertirHex(this.panelOpciones.barraTarea.getValue());
        //alert(this.panelOpciones.radioSelected)
        if(this.panelOpciones.radioSelected==false){
                                                                                  
		this.datosTpl={
			 barra: 'background:#'+this.panelOpciones.barraTarea.getValue()+';background-image: -moz-linear-gradient(top, rgba(255,255,255,0.5), rgba(255,255,255,0.2) 49%, rgba(0,0,0,0.05) 51%, rgba(0,0,0,0.15));',
             inicioMoz: 'background:#'+this.panelOpciones.inicio.getValue()+';background-image: -moz-linear-gradient(rgba(255,255,255,.15) 49%, rgba(0,0,0,.1) 51%, rgba(0,0,0,.15));border-radius:5px;',
             tipografia: tipog,
           
           
             fondo:''+this.panelOpciones.overInicio.getValue()+'',
           
             desktop:'/lib/ExtJS/temas/'+sm.getLastSelected().data.abreviatura+'/cache/desktop_1.jpg?n='+this.contador+'',
             iconoMenu:'/lib/ExtJS/temas/'+sm.getLastSelected().data.abreviatura+'/cache/icon_1.gif?n='+this.contadorIcon,
             estilo_letra: 'font-family:'+this.panelOpciones.comboTipografias.getValue()+' !important;',
             texto:'color:#'+this.panelOpciones.colorTextoInicio.getValue()+' !important;',
             
             color:'trasparent',
                                                                                        
             color2:''+this.panelOpciones.inicio.getValue()+''
             
             };
		 }else{
			 this.datosTpl={
			 barra: 'background:black;background-image: -moz-linear-gradient(top, rgba('+col[0]+','+col[1]+','+col[2]+',1), rgba('+col[0]+','+col[1]+','+col[2]+',0.5) 49%, rgba(0,0,0,0.5) 51%, rgba(0,0,0,1));',
			 inicioMoz: 'background:#'+this.panelOpciones.inicio.getValue()+';background-image: -moz-linear-gradient(rgba(255,255,255,.15) 49%, rgba(0,0,0,.1) 51%, rgba(0,0,0,.15));border-radius:5px;',
            //inicioMoz: 'background-image: -moz-linear-gradient(top, rgba(255,255,200,0.5), rgba(255,255,200,0.2) 49%,  rgba(0,0,0,0.15)51%,rgba(0,255,0,0.05));',
             tipografia: tipog,
            
             
             fondo:''+this.panelOpciones.overInicio.getValue()+'',
            
             desktop:'/lib/ExtJS/temas/'+sm.getLastSelected().data.abreviatura+'/cache/desktop_1.jpg?n='+this.contador+'',
             iconoMenu:'/lib/ExtJS/temas/'+sm.getLastSelected().data.abreviatura+'/cache/icon_1.gif?n='+this.contadorIcon,
             estilo_letra: 'font-family:'+this.panelOpciones.comboTipografias.getValue()+' !important;',
             texto:'color:#'+this.panelOpciones.colorTextoInicio.getValue()+' !important;',
             
             color:'trasparent',
                                                                                        
             color2:''+this.panelOpciones.inicio.getValue()+''
             
             };
			}
			
			this.datosTpl.headerMenu='background:#'+this.panelOpciones.headerMenu.getValue()+';background-image: -moz-linear-gradient(top, rgba(255,255,255,0.5), rgba(255,255,255,0.2) 49%, rgba(0,0,0,0.05) 51%, rgba(0,0,0,0.15));';
			//this.datosTpl.dockMenu='background:#'+this.panelOpciones.dockMenu.getValue()+';opacity:'+(this.panelOpciones.dockMenuOpacity.getValue()/100)+';';
			var rgb=this.convertirHex(this.panelOpciones.dockMenu.getValue());
			this.datosTpl.dockMenu='background:rgba('+rgb[0]+','+rgb[1]+','+rgb[2]+','+(this.panelOpciones.dockMenuOpacity.getValue()/100)+');';
			//this.datosTpl.dockMenu='background-image: -moz-linear-gradient(top, rgba(255,255,255,0.2), rgba(255,255,255,0.2) 49%, rgba(0,0,0,0.2) 51%, rgba(0,0,0,0.2));';
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


