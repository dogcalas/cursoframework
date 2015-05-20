		var perfil = window.parent.UCID.portal.perfil;
		perfil.etiquetas = Object();
		UCID.portal.cargarEtiquetas('gestrolesbd',cargarInterfaz);
		
		////------------ Inicializo el singlenton QuickTips ------------////
		Ext.QuickTips.init();
		
		////------------ Declarar variables ------------////
		var winPass, sistemaseleccionado, panelAdicionar, arbolConex, usuario,pepe, ipservidor,sid, gestorBD, puerto, password, baseDato, idgestor, idservidor;
		tipos = /(^([a-zA-ZáéíóúñÑ])+([a-zA-ZáéíóúñÑ\d\.\-\@\#\_\s]*))$/; 
		var msg = 'Usuario o contrase�a no v�lidos o el servidor no esta en l�nea.';    
		
		function cargarInterfaz(){
      
			arbolLoaderConex = new Ext.tree.TreeLoader({
			dataUrl:'cargarservidores',
			listeners:{
				'beforeload':function(atreeloader, anode) {
					if(anode.attributes.idgestor) {
						if (typeof(atreeloader.baseParams) != 'object' || !atreeloader.baseParams.user) {
							nodeArbolConexSelect = anode; 
							levantarVentana();
							return false;
						}
					}
					else if(anode.attributes.idservidor) {
						atreeloader.baseParams = {};
						atreeloader.baseParams.idservidor = anode.attributes.idservidor;
						atreeloader.baseParams.accion = 'cargargestores';
					} 
				},
				'load':function(treeLoaderObj, nodeObj, response) {
					respObj = Ext.decode(response.responseText);
					if(respObj.codMsg == 2 && nodeObj.attributes.idgestor) {
						Ext.get('divPassIncorrect').update(respObj.mensaje);
						nodeObj.reload();
					}
					else if(respObj.codMsg == 3 && nodeObj.attributes.idgestor) {
						 var error=DeterminarTipodeError(respObj.detalles);
                                                mostrarMensaje(3,error); 
                                                nodeObj.parentNode.reload();
					}
				}
			}
		});
		
                 function DeterminarTipodeError(detalles){
                    var index=detalles.indexOf("exception 'Doctrine_Connection_Exception' with message 'PDO Connection Error: SQLSTATE[08006] [7] FATAL:  password authentication failed for user");
                    if(index==0)
                        return perfil.etiquetas.wrongUserPass;
                   index=detalles.indexOf("exception 'Doctrine_Connection_Exception' with message 'PDO Connection Error: SQLSTATE[08006] [7] could not connect to server: No route to host");
		   if(index==0)
                       return perfil.etiquetas.NoServer;
                       return perfil.etiquetas.NoBD;
                 }		
		////------------ Arbol de servidores ------------////
		arbolConex = new Ext.tree.TreePanel({

					title:perfil.etiquetas.lbTitArbol,
					title:perfil.etiquetas.lbSeleccionar,
					border:false,
					id:'arbol',
					autoScroll:true,
					region:'west',
					collapsible:'true',
					width:250,
					loader: arbolLoaderConex,
					margins:'2 2 2 2'
		});
		
		////------------ Evento onClick del arbol ------------////
		arbolConex.on('click', function (node, e){
			if(node.attributes.leaf){
				usuario = node.attributes.user;
				ipservidor = node.attributes.ipgestorbd;
				puerto = node.attributes.puerto;
				gestorBD = node.attributes.type;
				password = node.attributes.passw;
				baseDato = node.attributes.namebd;
				idgestor = node.attributes.idgestor;
				idservidor = node.attributes.idservidor;
				sid=node.attributes.sid;
        		cargarVistas(node.attributes.type);
        		}
		}, this);
		
		////------------ Crear nodo padre del arbol ------------////
		padreConex = new Ext.tree.AsyncTreeNode({
			  text: perfil.etiquetas.lbTitNodeRoot,
			  text: perfil.etiquetas.lbserver,
			  draggable:false,
			  expandable:false,
			   expanded: true,
			  id:'0'
			});
			
		////------------ Crear lista de hijos ------------////
		arbolConex.setRootNode(padreConex);
				
		panelAdicionar = new Ext.Panel({
		region:'center',
		frame:'true',
		border:'false'	
		});
					
					////------------ Panel con los componentes ------------////
					var panel = new Ext.Panel({
						layout:'border',
						items:[arbolConex,panelAdicionar]
						
						});
									
					
					////------------ Viewport ------------////
					var vpGestFuncionalidad = new Ext.Viewport({
						layout:'fit',
						items:panel
						})
		
		fpPass = new Ext.FormPanel({
	    frame:true,
          
	    width:100,
	    bodyStyle:'padding:5px 5px 0',
	    items: [{
            layout:'column',
            items:[{
	            columnWidth:2,
	            layout:'form',
	            items:[{
	                xtype:'textfield',
	                fieldLabel:perfil.etiquetas.lbUsuario,                                   
	                id:'user',
	                blankText:perfil.etiquetas.lbMsgBlankTextDenom,
	                allowBlank:false,     
					labelStyle:'width:80px',
					width:130
				},{
	                xtype:'textfield',
	                fieldLabel:perfil.etiquetas.lbTitMsgContrasena,
	                inputType:'password',
	                blankText:perfil.etiquetas.lbMsgBlankTextDenom,                                
	                id:'passw',
					labelStyle:'width:80px',
					allowBlank:false,
					width:130
				}]
			}]
	    }],
	    html: '<div id="divPassIncorrect" style="color: red"></div>'
	});
        
	function levantarVentana() {
		if (!winPass) {
			winPass = new Ext.Window({
				modal: true,
				closeAction:'hide',
				layout:'fit',
				title:perfil.etiquetas.lbBtnCambiarpass,
				width:290,
				height:150,
				resizable:false,
				buttons:[{
					icon:perfil.dirImg+'cancelar.png',
					iconCls:'btn',
					id:'can',
					text:perfil.etiquetas.lbBtnCancelar,
					handler:function(){
					nodeArbolConexSelect.parentNode.reload();
                                        winPass.hide();
					}
	       		},{
					icon:perfil.dirImg+'aceptar.png',
					iconCls:'btn',
					handler:function() {
						if(fpPass.getForm().isValid()) {
							arbolLoaderConex.baseParams = {};
							arbolLoaderConex.baseParams.accion = 'cargarbd';
							arbolLoaderConex.baseParams.user = Ext.getCmp('user').getValue();
							arbolLoaderConex.baseParams.passw = Ext.getCmp('passw').getValue();                
							arbolLoaderConex.baseParams.gestor = nodeArbolConexSelect.attributes.gestor;
							arbolLoaderConex.baseParams.ipgestorbd = nodeArbolConexSelect.attributes.ipgestorbd;
							arbolLoaderConex.baseParams.idgestor = nodeArbolConexSelect.attributes.idgestor;
							arbolLoaderConex.baseParams.idservidor = nodeArbolConexSelect.attributes.idservidor;
							arbolLoaderConex.baseParams.puerto = nodeArbolConexSelect.attributes.puerto;
							arbolLoaderConex.baseParams.sid=nodeArbolConexSelect.attributes.sid;
							arbolLoaderConex.baseParams.idsistema = sistemaseleccionado;
							arbolLoaderConex.load(nodeArbolConexSelect);
							
							winPass.hide();
							arbolLoaderConex.baseParams = {};
							Ext.get('divPassIncorrect').update('');							
						}	
						else
						mostrarMensaje(3,perfil.etiquetas.lmMsgCamposEspe);
					},
					text:perfil.etiquetas.lbBtnAceptar
				}]
			});
			winPass.add(fpPass);        
			winPass.doLayout();
		}
		Ext.getCmp('user').reset();
		Ext.getCmp('passw').reset();
		winPass.show();	
	}
	
	function cargarVistas(type){
		if (Ext.getCmp('oracle')){
                        var frm=document.getElementById('pgsqlfrm');
                        if(frm!=null){
                        frm.parentNode.removeChild(frm);
                       }
   			panelAdicionar.remove('oracle',true);
                }
  		if (Ext.getCmp('pgsql')){
                        var frm1=document.getElementById('pgsqlfrm');
                       if(frm1!=null){
                        frm1.parentNode.removeChild(frm1);
                       }
   			panelAdicionar.remove('pgsql',true);
                }
		panelAdicionar.load({
		    url:'loadInterface',
		    params: {tipo: type},
		    nocache: true,
		    scripts: true
			});
		} 
               function accionCerrar(){
	var  tree=Ext.getCmp('arbol');  
	      tree.getRootNode().reload();
					
	}
                
		}
		
		
		
		
		