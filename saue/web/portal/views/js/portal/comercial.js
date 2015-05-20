/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
var menuTMP = {};
 //Eliminar el estilo del escritorio estilo windows
 removejscssfile("desktop.css", "css");
 

 
 //removejscssfile("presentacion.css", "css");
//$('#otros').css('display','none');
/*
$('#seg').click(function(){
    mostrarWinSelectEntidad();
    
});
$('#pre').click(function(){
   winPass.show();
    
});
$('#sal').click(function(){
   cerrarSesion();
    
});


  
 
*/

jQuery("#topscp").hide();


jQuery(window).scroll(function () {
if (jQuery(this).scrollTop() > 100) {
jQuery('#topscp').fadeIn();
} else {
jQuery('#topscp').fadeOut();}});
jQuery('#topscp a').click(function () {
jQuery('body,html').animate({
scrollTop: 0}, 800);
return false;});

var panel=$('<div ></div>').prependTo('body');
panel.addClass('panelOpts');
var sign=$('<a><img src="../../views/images/portal/off.png" width="25px"/><span></span></a>');
var pass=$('<a><img src="../../views/images/portal/pass.png" width="25px"/><span></span></a>');
var ent=$('<a><img src="../../views/images/portal/home2.png" width="25px"/><span></span></a>');
sign.click(function(){
	cerrarSesion();

})
pass.click(function(){
	 if(!winPass)
	   cargarInterfazPass();
	   winPass.show();
})
ent.click(function(){
	 mostrarWinSelectEntidad();
})
panel.append(sign);
panel.append(pass);
panel.append(ent);
panel.children().addClass('itemsPanel');
$('#fg').css('height','95%')
$('span[role="enmarcar"]').click(function(){
		document.body.style.overflow='hidden';
		if(!winEnm)
			var winEnm=Ext.create('Ext.Window',{
			width:'95%',
			height:'95%',
			contentEl:'fg',
			closeAction:'destroy',
			minimizable:true,
			modal:true,
			listeners:{
			  beforedestroy	:function(){
					
                    
                    Ext.get('conte').appendChild(Ext.get('fg'))
					 jQuery('#fg').attr('src','../../views/inicio.html');
					  window.idFuncionalidad='inicioFrame';
                     jQuery('.contenido').css('visibility','visible');
                    document.body.style.overflow='visible';
                     document.getElementById('indice-pagina').innerHTML='Inicio'
                     $('span[role="enmarcar"]').hide();
                    
			},
			minimize:function(){
				Ext.get('conte').appendChild(Ext.get('fg'))
					 $('#fg').css('height','95%')
					 jQuery('.sobreContenido').css('visibility','visible');
                     jQuery('.contenido').css('visibility','visible');
                    document.body.style.overflow='visible';
                    this.hide();
			}
			}
			
		});
		$('#fg').css('height','100%')
		winEnm.show();
})

$('span[role="enmarcar"]').hide();

Ext.Ajax.request({
	url: 'cargardesktop',
	method:'POST',
	callback: function (options,success,response){
		if(success){
			menuTMP = Ext.decode(response.responseText);
			document.getElementById('cargandoconfiguracion').style.display = 'none';
			//$('#comercialBody').css('display','block');
			//Eliminar la mascara de presentacion
			Ext.get('present').remove();
			//Mostrar el comercial
			jQuery('#bodyComercial').css('visibility','visible');
			dame(menuTMP);
                       
		}else{
			Ext.Msg.alert('Sorry',response.responseText);
		}
	}
});


 
  
        
function addMenu(padre,obj){
    var icono = (obj.icono)?UCID.portal.perfil.dirImg+obj.icono+'.png':UCID.portal.perfil.dirImg+'falta.png';
    jQuery("#"+padre).append('<li><a href="#aa" ><img src="'+icono+'" style="margin-right:5px">'+obj.text+'</a><ul id="sistema'+obj.id+'"></ul></li>');
    
    
}




function addit(padre,obj){
    jQuery("#"+padre).append('<li><a href="#aa" id="sist'+obj.id+'">'+obj.text+'</a></li>');
    jQuery('#sist'+obj.id).click(function(){
        jQuery('#fg').attr('src',obj.referencia );
        window.idFuncionalidad=obj.id;
        jQuery('.contenido').css('visibility','visible');
        jQuery('.contenido').css('height','500px');
        jQuery('.sobreContenido').css('visibility','visible');
      
      
     
        document.getElementById('indice-pagina').innerHTML=obj.text;
      
        if(obj.referencia=='..\/..\/..\/seguridad\/index.php\/gestnomtema\/gestnomtema'){
			 document.body.style.overflow='hidden';
			Ext.create('Ext.Window',{
			width:'95%',
			height:'95%',
			contentEl:'fg',
			closeAction:'destroy',
			modal:true,
			listeners:{
			  beforedestroy	:function(){
					Ext.get('conte').appendChild(Ext.get('fg'))
					 jQuery('#fg').attr('src','../../views/inicio.html');
					  window.idFuncionalidad='inicioFrame';
                     jQuery('.contenido').css('visibility','visible');
                    document.body.style.overflow='visible';
                     document.getElementById('indice-pagina').innerHTML='Inicio'
			}
			}
			
		}).show();
		
		}else
			 $('span[role="enmarcar"]').show();
     
    });
    
}

padre2='sistemas';

function dame( objson){
		var arrayItems = Array();
		
			for(var i=0; i<objson.length;i++){
				if (objson[i].menu){
					var ant='';
                                        addMenu(padre2,objson[i]);
                                        ant=padre2;
					padre2='sistema'+objson[i].id;	
					dame(objson[i].menu);
                                        padre2=ant;
				}
				else {
					addit(padre2, objson[i]);
                                         
				}
			}
		
		
	}
        
     var txtUser = (perfil.alias) ? perfil.alias : perfil.usuario;
     document.getElementById('nombreP').innerHTML=txtUser;
    
     document.getElementById('nombreT').innerHTML=perfil.tema;
     
 Util={};    
Util.CordenadasTag=function(obj){
       
       
         
          var x, y, w, h;
            x = y = w = h = 0;
            
            if (document.getBoxObjectFor) { // Mozilla
            var oBox = document.getBoxObjectFor(obj);
            x = oBox.x-1;
            w = oBox.width;
            y = oBox.y-1;
            h = oBox.height;

            }else if (obj.getBoundingClientRect) { // IE
                var oRect = obj.getBoundingClientRect();
                x = oRect.left-2;
                w = obj.clientWidth;
                y = oRect.top-2;
                h = obj.clientHeight;
                 //alert('se hizo 2');
            }

        /*
        while( el != null ) {
	        console.log(el, el.offsetLeft,el.offsetTop);
	        x += el.offsetLeft;
	        x+= el.offsetTop;
	        el = el.offsetParent;

	    }
         */

         return new Array(x,y,w,h);
   }

	
	jQuery('#fg').load(function(){
		 
		  var num=document.getElementById('fg').contentDocument.activeElement.offsetHeight+16;
		
		   num=document.getElementById('fg').contentDocument.documentElement.clientHeight;
		  
		  if(num>500){
		   jQuery('.contenido').css('height',''+num+'px');
		  } else
		   jQuery('.contenido').css('height','500px');
		
			jQuery('.sobreContenido').css('visibility','hidden');
			
		});
	
		 $('#fg').attr('src','../../views/inicio.html');
		 window.idFuncionalidad='inicioFrame';
		 $('.contenido').css('visibility','visible');
		 


function scrollY() {
// A shortcut, in case we're using Internet Explorer 6 in Strict Mode
var de = document.documentElement;
// If the pageYOffset of the browser is available, use that
return self.pageYOffset ||
// Otherwise, try to get the scroll top off of the root node
( de && de.scrollTop ) ||
// Finally, try to get the scroll top off of the body element
document.body.scrollTop;
}

function windowHeight() {
// A shortcut, in case we're using Internet Explorer 6 in Strict Mode
var de = document.documentElement;
// If the innerHeight of the browser is available, use that
return self.innerHeight ||
// Otherwise, try to get the height off of the root node
( de && de.clientHeight ) ||
// Finally, try to get the height off of the body element
document.body.clientHeight;
}



   
