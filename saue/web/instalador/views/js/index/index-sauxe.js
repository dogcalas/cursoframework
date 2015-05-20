var perfil = window.parent.UCID.portal.perfil;
Ext.QuickTips.init();
	var winIns;
	var esDirIp, tipos, tipoServidor;
		puerto = /^[0-9]+$/;
		centro = /^([1-9][0-9]{1,3})|([2-9]{1,4})$/;
		tipos = /^([a-zA-Z???????? ]+[a-zA-Z????????\d _]*)+$/;
		esDirIp =  /(^(2([0-4][0-9])|2(5[0-5]))|^([0-1]?[0-9]?[0-9]))\.(((2([0-4][0-9])|2(5[0-5]))|([0-1]?[0-9]?[0-9]))\.){2}((2([0-4][0-9])|2(5[0-5]))|([0-1]?[0-9]?[0-9]))$/ ;
		tipoServidor = /^(2([0-4][0-9])|2(5[0-5]))|^([0-1]?[0-9]?[0-9])\.(((2([0-4][0-9])|2(5[0-5]))|([0-1]?[0-9]?[0-9]))\.){2}((2([0-4][0-9])|2(5[0-5]))|([0-1]?[0-9]?[0-9]))$|^([a-zA-Z???????? ]+[a-zA-Z????????\d _]*){1}$/;
function cargarInterfaz(){

var instalacion = new Ext.FormPanel({
            labelAlign: 'top',
	    
            id:'instalacion',
            frame:true,
            width:400,
            height:200, 
            items: [{
                    layout:'column',
                    items:[{
                            columnWidth:.5,
                            layout:'form',
                            items:[{
                                    xtype:'textfield',
                                    fieldLabel:'Servidor',
                                    id:'servidor',
                                    allowBlank:false,
                                    blankText:'Este campo es requerido.',
				    value: 'localhost',
                                    anchor:'95%'
                                  },{
                                    xtype:'textfield',
                                    fieldLabel:'Base de datos',
                                    id:'basedatos',
                                    allowBlank:false,
                                    blankText:'Este campo es requerido.',
                                    regex: tipos,
                                    regexText:'El nombre de la base de datos debe comenzar con letras.',
                                    value: 'sauxe',
                                    anchor:'95%'
                                  },{
		                    xtype : 'textfield',
		                    fieldLabel : 'Puerto',
		                    id : 'puerto',
		                    allowBlank : false,		                    
		                    blankText : 'Este campo es requerido.',
		                    regex : puerto,
		                    value: '5432',
		                    regexText : 'Solo números.',		                    
		                    anchor : '60%'
		                }]
                            },
                            {
                                columnWidth:.5,
                                layout: 'form',
                                items: [{
                                        xtype:'textfield',
                                        fieldLabel: 'Usuario',
                                        blankText:'Este campo es requerido.',
                                        allowBlank:false,
                                        id: 'usuario',
					value: 'postgres',
                                        anchor:'100%'
                                       },{
                                        xtype:'textfield',
                                        fieldLabel: 'Contrase&ntilde;a',
                                        allowBlank:false,
                                        blankText:'Este campo es requerido.',
                                        inputType:'password',
                                        id: 'password',
                                        anchor:'100%'
                                       },{
                                        xtype:'numberfield',
                                        fieldLabel: 'No. Centro de datos',
                                        allowBlank:false,
					maxLength:4,
					regex:centro,
					regexText:'Este valor es incorrecto.',
                                        blankText:'Este campo es requerido.',
                                        id: 'centrodato',
                                        anchor:'60%'
                                       }]
                            },{
                            columnWidth:1,
                            layout: 'form',
                            items: [
                            new Ext.form.FieldSet({
                            title: 'Sistema operativo:',
                            id:'opcion',
			    layout : 'column',
                            autoHeight: true,
                            items: [
                           {
			    columnWidth : .5, 
				layout: 'form',
				items:[{
				xtype : 'radio',
				hideLabel : true,
				fieldLabel : 'Linux',
				name : 'radiob',
				id : 'linux',
				boxLabel : 'Linux',
				anchor : '80%'
			    }, {
				xtype : 'radio',
				hideLabel : true,
				fieldLabel : 'Windows',
				name : 'radiob',
				id : 'windows',
				boxLabel : 'Windows',
				anchor : '80%'
				    }]
			      },{
			    columnWidth : .5,
			      layout: 'form',
			      html : '<img src="../instalador/images/1.png" >'
			      }
                        ]})]
                        }]
                }]
    });
       
       var vpGestSistema = new Ext.Viewport({
        layout:'fit',           
        items: [{
        region: 'center',
        autoHeight: true,
        border: false,
        margins: '0 0 0 0'
    }]
        })    

	function winForm(){
		if(!winIns){
				
		  	winIns = new Ext.Window({layout:'fit',
                        y:150,
			width:400,
			closable:false,
			draggable:false,
			height:330,
			resizable:false,
			title:'Instalaci&oacute;n de Sauxe',				
                            buttons:[
                            {
                                icon:'comun/images/cancelar.png', 
                                iconCls:'btn',
                                text:'Cancelar',
                                handler:function(){winIns.hide();}                                
                            },
							{
                                icon:'comun/images/aceptar.png',
								iconCls:'btn',
								text:'Aceptar',
								handler:function(){adicionardominio();}
							}]
		
						});
		}
		winIns.add(instalacion);				
		winIns.doLayout();
		winIns.show();
	}
	winForm();    

 function adicionardominio(){
    if(Ext.getCmp('servidor').getValue()&& Ext.getCmp('basedatos').getValue()&& Ext.getCmp('usuario').getValue() && Ext.getCmp('password').getValue())
    {
        if(Ext.getCmp('windows').getValue() || Ext.getCmp('linux').getValue())
        {
        if(Ext.getCmp('windows').getValue())
           opcion = 'windows';
        else
           opcion = 'linux';

				if (instalacion.getForm().isValid()){	
					instalacion.getForm().submit({
						url:'index.php/index/instalacion',
                        params:{'opcion':opcion},
						 timeout:600,
						waitMsg:'Registrando datos. La operación puede tardar varios minutos.',
						failure: function(form, action){                                                     
							if(action.result.cadena){
							todo = "http://" + action.result.cadena;
							winIns.hide();														
							window.location = todo;                      	
							}
							else{
                                                            var mensaje='Error en la instalación.';
                                                            if(action.result.mensaje)
                                                                mensaje=action.result.mensaje;
                                                            mostrarMensaje(3,mensaje);
                                                        }
								
						}
		
					});
				}
             }
             else
             {
                mostrarMensaje(3,'Debe seleccionar el sistema operativo');
                return;
             } 
        }
        else
        {
            mostrarMensaje(3,'Debe llenar todos los campos.');
            return;
        }
 
 }
}
cargarInterfaz();
