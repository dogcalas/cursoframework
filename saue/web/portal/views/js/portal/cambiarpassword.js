var perfil = UCID.portal.perfil;
var perfilPass = new Object();
    	
function cargarEtiquetasPass(vistaCU, fn){
	Ext.Ajax.request({
		url: 'cargaretiquetas',
		method:'POST',
		params:{vista:vistaCU},
		callback: function (options,success,response){
			if(success){
				perfilPass.etiquetas = Ext.decode(response.responseText);
				var codMsg = perfilPass.etiquetas.codMsg;
				if (!codMsg)
					fn();
			}
		}
	});
}
		cargarEtiquetasPass('cambiarpassword', function(){cargarInterfazPass();});
		
		////------------ Inicializo el singlenton QuickTips ------------////
		Ext.QuickTips.init();
		
		////------------ Declarar variables ------------////
		var winPass,fpPass;
		var TFContrasenna, TFConfContrasenna;
		
     function cargarInterfazPass(){
		////------------ TextField - Contrasenna------------////
		TFContrasenna = new Ext.form.TextField({
			fieldLabel:perfilPass.etiquetas.lbTitMsgContrasena,
			inputType:'password',
			id : 'contrasenna',
            maxLength:50,
			allowBlank : true,
			//hideLabel:true,
			blankText : 'Este campo es requerido.',
			name : 'contrasenna',
			anchor : '100%'
		});
		////------------ TextField - Confirmar Contrasenna------------////
		
		TFConfContrasenna = new Ext.form.TextField({
			fieldLabel:perfilPass.etiquetas.lbTitMsgConfirmarcontrasena,
			id : 'contrasena',
			inputType:'password',
            maxLength:50,
			allowBlank : true,
			blankText : 'Este campo es requerido.',
			name : 'contrasena',
			anchor : '100%'
		});
		
    ////------------ Formulario de Datos de Sistema ------------////
        fpPass = new Ext.FormPanel({
            frame:true,
            width:500,
             layout:'vbox',
            bodyStyle:'padding:5px 5px 0',
            items: [{
                                    xtype:'textfield',
                                    fieldLabel:perfilPass.etiquetas.lbUsuario,                                   
                                    id:'usuariop',     
									//labelStyle:'width:100px;background:red;',
									labelWidth:200,
									width:400,
									readOnly:'true',
									//labelAlign:'left'
                            },
                            {
                                    xtype:'textfield',
                                    inputType:'password',
                                    fieldLabel:perfilPass.etiquetas.lbTitMsgContrasenaanterior,
                                    id:'oldpass',
									//labelStyle:'width:200px',
									labelWidth:200,
                                    allowBlank:false,
                                    blankText:perfilPass.etiquetas.lbMsgEstecampoesrequerido,                                    
                                    width:400
                            },
                            {
                                    xtype:'textfield',
                                    fieldLabel:perfilPass.etiquetas.lbTitMsgContrasenanueva,
                                    inputType:'password',
                                    blankText:perfilPass.etiquetas.lbMsgEstecampoesrequerido,                                
                                    id:'contrasenap',
									labelWidth:200,
                                    allowBlank:false,
									width:400
                            },{
                                    xtype:'textfield',
                                    inputType:'password',
                                    fieldLabel:perfilPass.etiquetas.lbTitMsgContrasenanuevaconfirmada,
                                    blankText:perfilPass.etiquetas.lbMsgEstecampoesrequerido,
                                    id:'contrasennap',
									labelWidth:200,
									width:400,
									allowBlank:false
                            }]
        });        
		
		winPass= new Ext.Window({modal: true,closeAction:'hide',layout:'fit',
                              title:perfilPass.etiquetas.lbBtnCambiarpass,width:450,height:200,resizable:false,
                              buttons:[
                              {
                                  icon:perfil.dirImg+'cancelar.png',
                                  iconCls:'btn',
                                  text:perfilPass.etiquetas.lbBtnCancelar,
                                  handler:function(){winPass.hide();}
                              },
                              {
                                  icon:perfil.dirImg+'aceptar.png',
                                  iconCls:'btn',
                                  handler:function(){cambiarpass();},
                                  text:perfilPass.etiquetas.lbBtnAceptar
                              }]
                          });
                      winPass.add(fpPass);                
                      winPass.doLayout();
                      Ext.getCmp('oldpass').reset();
                      Ext.getCmp('contrasenap').reset();
                      Ext.getCmp('contrasennap').reset();
                      Ext.getCmp('usuariop').setValue(perfil.usuario);
  
            
        ////----------------------------------- Cambiar Password --------------------------------////
        function cambiarpass()
        {
            if(fpPass.getForm().isValid())
            {
                if(Ext.getCmp('contrasennap').getValue() ==  Ext.getCmp('contrasenap').getValue())
                {
                    fpPass.getForm().submit({
                            url:'nuevopassword', 
                            waitMsg:'Cambiando contrase&ntilde;a...', 
                            failure: function(form, action)
                            {          
                               if(action.result.codMsg != 3)
                                {
                                    mostrarMensaje(1,perfilPass.etiquetas.lbTitMsgContrasenaCorrecta);
                                    Ext.getCmp('oldpass').reset();
                      				Ext.getCmp('contrasenap').reset();
                      				Ext.getCmp('contrasennap').reset();
                                    winPass.hide();                                 
                                }
				else if (action.result.codMsg)
                              		mostrarMensaje(action.result.codMsg,action.result.mensaje);
                            }
                    } );
                }
                else
                mostrarMensaje(3,perfilPass.etiquetas.lbTitMsgContrasenaIncorrecta);
            }
            else
                mostrarMensaje(3,perfilPass.etiquetas.lbMsgErrorEnCamops);            
        }
    }        
