
var perfil = window.parent.UCID.portal.perfil;
UCID.portal.cargarEtiquetas('tomarimagenes', function(){
    cargarInterfaz();
});
////------------ Inicializo el singlenton QuickTips ------------////
Ext.QuickTips.init();
////------------ Declarar variables ------------////
var chooser,fp,idusuario = 0;
var userDetail;
var winins;
var direccionGuardar  = "";
var ctx;
		

		
		
function cargarInterfaz()
{

    webcam.set_api_url( 'imagenwebcam' );

    ////--------------Formulario que contiene la imagen de la webcam--------////

    userDetail = new Ext.FormPanel({
        width: 320,
        height: 240,
        region: 'east',
        items: [{
            layout:'form',
            items:[{
                columnWidth:1,
                layout:'form',
            }] 
        }],
        		
        tpl: new Ext.XTemplate([             
            
            '<svg version="1.1" width="320" height="320" xmlns="http://www.w3.org/2000/svg" style="background:transparent;position:absolute;left:0;top:0;z-index:100"> <ellipse fill="url(#circleGrad)" stroke="#F20000" cx="50%" cy="35%" rx="17%" ry="25%"> </ellipse> </svg>',
            
            webcam.get_html(320, 240) 
               
               
            ]),
        listeners:{
            render:{
                fn: function(el)
                {
                    this.tpl.overwrite(this.body,this.data);
                }
            }
        }
    });

    ////-------------ventana de la webcam ------------- ////

    winins =new Ext.Window({
       			
        title:'Webcam',
        allowDomMove : false,
        width:335,
        height:305,
        //x:0,
        //y:0,
        resizable:false,
        closable: false,		
        items:userDetail,	                       			
        buttons:[
        /* {
                    //icon:perfil.dirImg+'cancelar.png',
                    iconCls:'btn',
                    text:'Configurar',
                    handler:function(){webcam.configure()}
                },*/
                
        {
            //icon:perfil.dirImg+'aceptar.png',
            id:'BtnExp',
            icon:perfil.dirImg+'descargar.png',
            iconCls:'btn',
            handler:function(){
                exportar()
                },
            text:perfil.etiquetas.lbExportar,
            disabled:true
        },
        {
            icon:perfil.dirImg+'aceptar.png',
            iconCls:'btn',
            handler:function(){                                        
                webcam.freeze();
                webcam.upload();
                webcam.reset();
                   
            },
            text:perfil.etiquetas.lbTomarImagen
        }/*,{
                    icon:perfil.dirImg+'aceptar.png',
                    iconCls:'btn',
                    handler:function(){webcam.reset();},
                    text:'Reset'
                }*/
                
        ]
    });
    winins.show();

                        


    function exportar()
    {
        window.open('descargar');
        Ext.getCmp('BtnExp').disable();
    }
}
