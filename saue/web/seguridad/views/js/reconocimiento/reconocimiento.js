


var perfil = window.parent.UCID.portal.perfil;
		UCID.portal.cargarEtiquetas('reconocimiento', function(){cargarInterfaz();});
////------------ Inicializo el singlenton QuickTips ------------////
		Ext.QuickTips.init();
		////------------ Declarar variables ------------////
		var chooser,fp,idusuario = 0; var userDetail;
		var winins;
		
////////------------ Area de validaciones ------------////
		
		letrasnumeros = /(^([a-zA-ZáéíóúñÑ]?)+([a-zA-ZáéíóúñÑ\d\.\-\@\#\_\s]*))$/;
function cargarInterfaz(){

	webcam.set_api_url( 'imagenwebcam' );

	    userDetail = new Ext.FormPanel({
     //applyTo: 'chap13_ex01',
     width: 320,
     height: 240,
     frame:true,
    // title: 'Chapter 13 Example 1',
    // data: userData[0],
    items: [{
					layout:'form',
					items:[{
							columnWidth:1,
							layout:'form',
							items:[
							{
									xtype:'textfield',
									fieldLabel:'Usuario',
									id:'usuario',
                                    zindex:100,
                                                                        maxLength:50,
									//allowBlank:false,
								//	blankText:'Este campo es obligatorio.',
									
                                                                       // regexText:'Este valor es incorrecto.',
                                                                        
									anchor:'100%'
							}] }] }],
							
     tpl: new Ext.XTemplate([
      '<svg version="1.1" width="320" height="320" xmlns="http://www.w3.org/2000/svg" style="background:transparent;position:absolute;left:0;top:40"> <ellipse fill="url(#circleGrad)" stroke="#F20000" cx="50%" cy="20%" rx="15%" ry="20%"> </ellipse> </svg>',
        webcam.get_html(320, 240) 
     
      ]),
      listeners:{
         render:{
              fn: function(el){
                 this.tpl.overwrite(this.body,this.data);
              }
         }
      }
   });


winins =new Ext.Window({
	                       			
	                       			title:'Webcam',
	                       			width:335,
	                       			height:305,
	                       			resizable:false,
	                       			closable: false,
                                    allowDomMove : false,		
	                       			items:userDetail,	                       			
	                       			  buttons:[
                                    {
                                        //icon:perfil.dirImg+'cancelar.png',
                                        iconCls:'btn',
                                        text:'Configurar',
                                        handler:function(){webcam.configure();}
                                    },
                                    {
                                        icon:perfil.dirImg+'aceptar.png',
                                        iconCls:'btn',
                                        handler:function(){webcam.freeze();
                                        webcam.upload();
                                        webcam.reset();},
                                        text:'Upload'
                                    }/*,{
                                        icon:perfil.dirImg+'aceptar.png',
                                        iconCls:'btn',
                                        handler:function(){webcam.freeze();},
                                        text:'Capture'
                                    },{
                                        icon:perfil.dirImg+'aceptar.png',
                                        iconCls:'btn',
                                        handler:function(){webcam.reset();},
                                        text:'Reset'
                                    }*/
                                    
                                    ]
	                       			});
	                       			 	winins.show();



	     

        

	}
	
