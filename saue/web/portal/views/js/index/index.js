////------------ Inicializo el singlenton QuickTips ------------////


var gen, componente;
var cont = 0;
var stpreview, seleccionado;
var ventana = null, iddominio = null;
var FormIndex = null;
var newAccess = false;
var atrib = new Array();
var comp = new Array();

var App=function(){
	this.init();
};

App.prototype={
	constructor: App,
	
	main:function(){
		this.configUI();
	},
	
	wCfg : 'titlebar=yes,status=yes,resizable=yes,width=' + screen.width + ',height=' + screen.height + ',scrollbars=yes',
	
	configUI:function(){
		var me=this;
		
		this.btnExit=$('#btn-exit')
		.click(function(){
		   me.entrarAlSistema('nuevo');
		});
		this.btnAccess=$('#btn-access')
		.click(function(){
			if(!$(this).hasClass('disabled'))
				me.entrarAlSistema('viejo');
		});
		
		
		this.loading=$('#loading');
		$.ajax({
        url: 'index.php/index/cargardominio',
        type: 'POST',
        dataType:'html',
        

        success:function(pResp,atrib){
            var obj=$.parseJSON(pResp);
            for(i=0;i<obj.length;i++){
				if(obj.length&& obj.length==0){
						$('#loading').hide();
						return;
				}
            
				$(
				'<div class="entry-item-token">'+
					'<div id="'+obj[i].id+'"class="entry-item-container">'+
						'<div class="entry-item muted " >'+
							'<img src="'+obj[i].url+'" title="'+obj[i].name+'" width="100">'+
						'</div>'+
					'</div>'+
				'<br><span class="muted">'+obj[i].fullName+'</span>'+
			
				'</div>').appendTo('.one')
				.click(function(){
					$('.entry-item-token').removeClass('entry-item-container-select');
					$(this).addClass('entry-item-container-select');
					me.btnAccess.removeClass('disabled');
					me.iddominio=$(this).data('id');
				})
				.dblclick(function(){
					if(!$(this).data('open')){
							me.loadNode($(this));
							
							$(this).data('open',true)
					}else{
							$('.page').hide();
							$('.back').show();					
							$(this).data('pageDown').fadeIn(500); 
							$('.back').data('page',$(this).data('pageDown'));
					}
				})
				.data('open',false)
				.data('id',obj[i].id);
				
				$('.other').fadeOut(500,function(){
					$('.one').fadeIn(500); 
				});
				
			}
			if(obj.length == 1){
                me.iddominio = obj[0].id;
                me.entrarAlSistema('viejo');
            }
            
            me.loading.hide();
			
        }
    });
    this.back=$('.back').click(function(){
			if($(this).data('page')&&$(this).data('page').data('pageUp')){
				
				$('.page').hide();
				$(this).data('page').data('pageUp').fadeIn(500); 
				me.btnAccess.addClass('disabled');
				$(this).data('page',$(this).data('page').data('pageUp'));
				$('.entry-item-token').removeClass('entry-item-container-select');
				if(!$(this).data('page').data('pageUp'))
					$('.back').hide();
			}else{
				$('.back').hide();
			}
	})

    
    if (document.getElementById('accesodirecto')&& document.getElementById('accesodirecto').value != '0') {
        this.verificarPerfil();
    }
    
		
	},
	
	entrarAlSistema:function(acs){
		var me=this;
		$.ajax({
        url: 'index.php/index/entraralsistema',
        type: 'POST',
        dataType:'html',
        data: {
                dominio: this.iddominio,
                tipoacceso: acs

            },

        success:function(pResp,atrib){
            var objJson=$.parseJSON(pResp);
            if (me.ventana != null) {
                    me.ventana.close();
                    me.ventana = null;
                }
                if (objJson.reload == false) {
                    window.location.reload();
                }
                else if (objJson.reload == true) {
                    me.verificarPerfil();
                }
                else if (objJson.codMsg) {
                    var msg = objJson.mensaje;
                    
					alert(msg)
                    //Ext.Msg.alert('ERROR', msg);
//                    mostrarMensaje(mensaje.codMsg, mensaje.mensaje);
                }
        }
		});
	},
	
	verificarPerfil:function(){
		var me=this;
		$.ajax({
        url: 'index.php/portal/cargarperfil',
        type: 'POST',
        dataType:'html',
        
        success:function(pResp,atrib){
					 var objJson=$.parseJSON(pResp);
                    //Variable de configuracion con el perfil del usuario
                    var perfil = objJson;
                    if (perfil.portal === 'comercial'){
						
                        me.ventana = window.open('index.php/portal/portal', 'ERP_CUBA');
                    }else{
						
                        me.ventana = window.open('index.php/portal/portal', 'ERP_CUBA', me.wCfg);
					}
             
                   
              
        }
		})
	},
	
	loadNode:function(node){
		var me=this;
		$('#loading').show();
		$.ajax({
								url: 'index.php/index/cargardominio',
								type: 'POST',
								dataType:'html',
								

								success:function(pResp,atrib){
									var obj=$.parseJSON(pResp);
									if(obj.length&& obj.length==0){
										me.loading.hide();
										return;
									}
									$('.one').after(
									 '<div id="page-'+node.data('id')+'" class="container page text-center" style="display: none;"></div>'
									 );
									 
									for(i=0;i<obj.length;i++){
										
									
										$(
										'<div class="entry-item-token">'+
											'<div id="'+obj[i].id+'" class="entry-item-container">'+
												'<div class="entry-item muted " >'+
													'<img src="'+obj[i].url+'" title="'+obj[i].name+'" width="100">'+
												'</div>'+
											'</div>'+
										'<br><span class="muted">'+obj[i].fullName+'</span>'+
									
										'</div>').appendTo("#page-"+node.data('id'))
										.click(function(){
											$('.entry-item-token').removeClass('entry-item-container-select');
											$(this).addClass('entry-item-container-select');
											me.btnAccess.removeClass('disabled');
										})
										.dblclick(function(){
											
											if(!$(this).data('open')){
													me.loadNode($(this));
													$(this).data('open',true)
													
											}else{
												$('.page').hide();
												me.back.show();
												$(this).data('pageDown').fadeIn(500); 
												me.back.data('page',$(this).data('pageDown'));
											}
										})
										.data('open',false)
										
										.data('id',obj[i].id);
									}
									$('.page').hide();
									node.data('pageDown',$("#page-"+node.data('id')));
									$("#page-"+node.data('id')).fadeIn(500); 
									$("#page-"+node.data('id')).data('pageUp',node.parent('.page'));
									me.back.data('page',$("#page-"+node.data('id')));
									me.loading.hide();
									me.back.show();
								}
							});
		
	},
	//Disparador de inicio
	init:function(){
		var me=this;
		$(document).ready(function(){
			me.main();
		})
	}
	
};


new App();
