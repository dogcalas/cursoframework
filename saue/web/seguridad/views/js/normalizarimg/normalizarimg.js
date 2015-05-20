
var perfil = window.parent.UCID.portal.perfil;
perfil.etiquetas = Object();
UCID.portal.cargarEtiquetas('normalizarimg',cargarInterfaz);
		
////------------ Inicializo el singlenton QuickTips ------------////
Ext.QuickTips.init();
		
////------------ Declarar variables ------------////
var winIns, winMod,brillo,contraste;
var brilloMod,contrasteMod,anchoMod,altoMod,formaMod,formatoMod,cantimgMod;
       
////------------ Area de validaciones ------------////
var soloNumeros;
var a,dir = "";
soloNumeros = /^[0-9]+$/;
		
function cargarInterfaz()
{
    ////------------ Botones ------------////
    btnAdicionar = new Ext.Button({
        disabled:true,
        id:'btnAgrDatosIm', 
        hidden:true,
        icon:perfil.dirImg+'adicionar.png', 
        iconCls:'btn', 
        text:perfil.etiquetas.lbBtnAdicionar, 
        handler:function(){
            winForm('Ins');
        }
    });
    btnModificar = new Ext.Button({
        disabled:true,
        id:'btnModDatosIm', 
        hidden:true,
        icon:perfil.dirImg+'modificar.png', 
        iconCls:'btn', 
        text:perfil.etiquetas.lbBtnModificar, 
        handler:function(){
            winForm('Mod');
        }
    });
    
    /*btnAyuda = new Ext.Button({id:'btnAyuClaves', hidden:true,icon:perfil.dirImg+'ayuda.png', iconCls:'btn', text:perfil.etiquetas.lbBtnAyuda });*/
    UCID.portal.cargarAcciones(window.parent.idFuncionalidad);
    ////------------ Store del Grid ------------//// 
    var stDatimg =  new Ext.data.Store({
        url: 'cargardatos',
        reader:new Ext.data.JsonReader({
            totalProperty: "cantidad_filas",
            root: "datos",
            id: "iddatosimg"
        },
        [
							
							
        {
            name:'iddatosimg',
            mapping:'iddatosimg'
        },

        {
            name:'formato',
            mapping:'formato'
        },

        {
            name:'forma',
            mapping:'forma'
        },

        {
            name:'cantimg',
            mapping:'cantimg'
        },

        {
            name:'contraste',
            mapping:'contraste'
        },

        {
            name:'brillo',
            mapping:'brillo'
        },

        {
            name:'ancho',
            mapping:'ancho'
        },

        {
            name:'dir',
            mapping:'dir'
        },

        {
            name:'alto',
            mapping:'alto'
        }
        ])
    });
			
    ////------------ Modo de seleccion del grid ------------////
    sm = new Ext.grid.RowSelectionModel({
        singleSelect:true
    });

    sm.on('rowselect', function (smodel, rowIndex, record)
    {
                     
			
        }, this);
    sm.on('beforerowselect', function (smodel, rowIndex, keepExisting, record){
        btnModificar.enable();      
        brillo = record.data.brillo;
        contraste = record.data.contraste;
                                                            
    }, this);
			
			
    stDatimg.on('load', function(){
                            
                           
        if(stDatimg.getCount() != 0){
            btnAdicionar.disable();
            btnAdicionar.hide();
        }
        else
        {
            btnAdicionar.enable();
            auxIns = true;
        }
    }
                    
    );				
			
    ////------------ Defino el grid de datos de imagenes  ------------////
    var gpDatimg= new Ext.grid.GridPanel({
        frame:true,
        region:'center',
        iconCls:'icon-grid',
        autoExpandColumn:'expandir',
        store:stDatimg,
        sm:sm,
        columns: [
        {
            hidden: true, 
            hideable: false,  
            dataIndex: 'iddatosimg'
        },

        {
            id:'expandir',
            header:perfil.etiquetas.lbFLAncho,
            width:100,  
            dataIndex: 'ancho'
        },

        {
            header: perfil.etiquetas.lbFLAlto,
            width:100, 
            dataIndex: 'alto'
        },

        {
            header: perfil.etiquetas.lbFLNoimg,
            width:200, 
            dataIndex: 'cantimg'
        },

        {
            header:perfil.etiquetas.lbFLFormato,
            width:100, 
            dataIndex: 'formato'
        },

        {
            header:perfil.etiquetas.lbFLFimg,
            width:200, 
            dataIndex: 'forma'
        },

        {
            header:perfil.etiquetas.lbFLBrillo,
            width:100, 
            dataIndex: 'brillo'
        },

        {
            header:perfil.etiquetas.lbFLContraste,
            width:100, 
            dataIndex: 'contraste'
        },
        ],
        loadMask:{
            store:stDatimg
        }

    });
    
                               
			
    ////------------ Panel ------------////
    var panel = new Ext.Panel({
        layout:'border',
        title:perfil.etiquetas.lbTitPanelTit,
        renderTo:'panel',
        items:[gpDatimg],
        tbar:[btnAdicionar,btnModificar/*,btnAyuda*/],
        keys: new Ext.KeyMap(document,[
        {
            key:"i",
            alt:true,
            fn: function(){
                if(auxIns1 && auxIns && auxIns2)
                    winForm('Ins');
            }
        },
        {
            key:"m",
            alt:true,
            fn: function(){
                if(auxMod && auxMod2 && auxMod2)
                    winForm('Mod');
            }
        }])
    });
    ////------------ Eventos para hotkeys ------------////
    btnAdicionar.on('show',function(){
        auxIns1 = true;
    },this)
    btnModificar.on('show',function(){
        auxMod = true;
    },this)
    stDatimg.on('load',function(){
        if(stDatimg.getCount() != 0)
            auxMod2 = true;
        else
            auxMod2 = false;
    },this)
    ////------------ Viewport ------------////
    var vpServidorAut = new Ext.Viewport({
        layout:'fit',
        items:panel
    })		
    stDatimg.load({
        params:{
            start:0, 
            limit:15
        }
    });	

    var formato = new Ext.data.SimpleStore({
        fields: ['id', 'formato'],
        data : [['1','*.png'],['2','*.bmp']/*,['3','*.jpg']*/]
    });
    var forma = new Ext.data.SimpleStore({
        fields: ['id', 'forma'],
        data : [['1','Rectangulo'],['2','Elipse']]
    });
                        
                        

                            
                               
 
    function cargarDir () {
	

        var j = Ext.Ajax.request({
            url:'cargardir',
            method: 'GET',
            success: function(r){       
                data = {
                    url:Ext.decode(r.responseText)
     
                };
            }
        });


    }
    cargarDir ();       



	
    /*var thumbTemplate = new Ext.XTemplate(
																'<tpl for=".">',
					'<div class="thumb-wrap" id="{name}">',
					'<div class="thumb"><img src="{url}" title="{name}"></div>',
					'<span>{url}</span></div>',
				'</tpl>'
				'<tpl for=".">',
					'<div class="thumb-wrap" id="{name}">',
					'<div class="thumb"><img src="{url}" title="{name}"></div>',
					
				'</tpl>'
			)*/
	  
							
    ////------------ Formulario ------------////			
    var regDatos = new Ext.FormPanel({
                         
        labelAlign: 'top',
        id:'form',
        //region:'center',
        frame:true,
        bodyStyle:'padding:5px 5px 0',
        items: [{
            layout:'column',
            items:[							
            {
                columnWidth:.5,
                layout: 'form',
                items: [ 
                new Ext.form.FieldSet({
                    title:perfil.etiquetas.lbTitImSet,
                    width:150,
                    autoHeight: true,
                    items: [
                    {
						
                        id: 'img-detail-panel',
                        //region: 'east',
                        split: true,
                        width: 150,
                        minWidth: 150,
                        maxWidth: 250
                    },
									              
                    {
                        xtype: 'label',                    
                        text:perfil.etiquetas.lbFLBrillo,
                        id: 'lbrillo',
                        name: 'lbrillo'
                    },new Ext.Slider({
                        //  renderTo: 'brillo',
                        id: 'brillo',
                        name:'brillo',
                        width:120,
                        increment: 1,
                        value:brillo,
                        minValue:0,
                        maxValue: 200,
                        plugins: new Ext.ux.SliderTip(),
                        change:function(){
                            alert("aas");
                        }
                                    
                    }),
                    {
                        xtype: 'label',                    
                        text:perfil.etiquetas.lbFLContraste,
                        id: 'lcontraste',
                        name: 'lgal_id'
                    },new Ext.Slider({
                        //  renderTo: 'brillo',
                        id: 'contraste',
                        width:120,
                        increment: 1,
                        value: 100,
                        minValue: 0,
                        maxValue: 200,
                        plugins: new Ext.ux.SliderTip()
                    }),
                    /*,{
					xtype: 'tbbutton',
					text: 'Aplicar',
					
					handler:function(){modificarDatos();}
					
					}*/

				
									  
		
														
                    ],

                    buttons:[
                    {
                        icon:perfil.dirImg+'aplicar.png',
                        iconCls:'btn',
                        text:perfil.etiquetas.lbBtnAplicar,
                        handler:function(){
                            modificarDatos('apl');
                        }
                    }
																		
                    ]
                })
															  
			
                ]
            },
									  
            {
                columnWidth:.5,
                layout: 'form',
                items: [{
                    xtype:'textfield',
                    fieldLabel:perfil.etiquetas.lbFLAncho,
                    id:'ancho',
                    name: 'ancho',
                    allowBlank:false,
                    blankText:perfil.etiquetas.lbMsgBlank,
                    regex:soloNumeros,
                    regexText:perfil.etiquetas.lbMsgregexI,
                    maxLength:3, 
                    anchor:'95%'
                },
                {
                    xtype:'hidden',													
                    id:'iddatosimg',
                    name: 'iddatosimg',													
                    anchor:'95%'
                },
                {
                    xtype:'textfield',
                    fieldLabel:perfil.etiquetas.lbFLAlto,
                    id:'alto',
                    name: 'alto',
                    allowBlank:false,
                    blankText:perfil.etiquetas.lbMsgBlank,
                    regex:soloNumeros,
                    regexText:perfil.etiquetas.lbMsgregexI,
                    anchor:'95%',
                    maxLength:3
                },{
                    xtype:'textfield',
                    fieldLabel:perfil.etiquetas.lbFLNoimg,
                    id:'cantimg',
                    name: 'cantimg',
                    allowBlank:false,
                    blankText:perfil.etiquetas.lbMsgBlank,
                    regex:soloNumeros,
                    regexText:perfil.etiquetas.lbMsgregexI,
                    anchor:'95%',
                    maxLength:2
                },{
                    xtype: 'combo',
                    name: 'formato',
                    id:'formato',
                    fieldLabel: perfil.etiquetas.lbFLFormato,
                    mode: 'local',
                    store: formato,
                    displayField:'formato',
                    lazyRender:true,
                    allowBlank:false,
                    triggerAction : 'all',
                    anchor:'95%',
                    editable : false
                   
                },{
                    xtype:'combo',
                    name: 'forma',
                    id:'forma',
                    fieldLabel:perfil.etiquetas.lbFLFimg,
                    mode: 'local',												
                    displayField:'forma',
                    store: forma,
                    lazyRender:true,
                    allowBlank:false,
                    anchor:'95%',
                    triggerAction : 'all',
                    editable : false
                }//,
                // {
                // 	xtype:'textfield',
                // 	fieldLabel:'Desplazamiento(eje x)',
                // 	id:'desplazamiento',
                // 	name: 'Noimg',
                // 	allowBlank:false,
                // 	blankText:perfil.etiquetas.lbMsgBlank,
                // 	regex:soloNumeros,
                // 	regexText:perfil.etiquetas.lbMsgregexI,
                // 	anchor:'95%'
                //}
                ]
            }]
        }]
    });



    var tpl1 = new Ext.XTemplate(
   
        '<tpl for=".">', 
        '<img src="{url}" title="{name}"> ',     // process the data.kids node
        // use current array index to autonumber
        '</tpl>'
        );

    var  a;	//Cargar la ventana
    function winForm(opcion){
        switch(opcion){
            case 'Ins':{
                if(!winIns){
                    winIns = new Ext.Window({
                        modal: true,
                        closeAction:'hide',
                        layout:'fit',
                        title:perfil.etiquetas.lbTitVentanaTitI,
                        width:400,
                        height:380,
                        buttons:[
                        {
                            icon:perfil.dirImg+'cancelar.png',
                            iconCls:'btn',
                            text:perfil.etiquetas.lbBtnCancelar,
                            handler:function(){
                                winIns.hide();
                            }
                        },
                        {		
                            icon:perfil.dirImg+'aceptar.png',
                            iconCls:'btn',
                            text:perfil.etiquetas.lbBtnAceptar,
                            handler:function(){
                                adicionarDatos();
                            }
                        }
                        ]
                    });
                    winIns.on('show',function(){
                        auxIns2 = false;
                        auxMod2 = false;
                    },this)
                    winIns.on('hide',function(){
                        auxIns2 = true;
                        auxMod2 = true;
                    },this)
                }
                regDatos.getForm().reset();
                winIns.add(regDatos);				
                winIns.doLayout();
                winIns.show();
			
            }
            break;
            case 'Mod':{
                                                      
                if(!winMod){
								 
								
                    winMod= new Ext.Window({
                        modal: true,
                        closeAction:'hide',
                        layout:'fit',
                        title:perfil.etiquetas.lbDatosImagenes,
                        width:400,
                        height:380,
                        resizable:false,
														
                        buttons:[
                        {
                            icon:perfil.dirImg+'cancelar.png',
                            iconCls:'btn',
                            text:perfil.etiquetas.lbBtnCancelar,
                            handler:function(){		 
																																				
                                winMod.hide();
                            }
                        },{	
                            icon:perfil.dirImg+'aceptar.png',
                            iconCls:'btn',
                            text:perfil.etiquetas.lbBtnAceptar,
                            handler:function(){
                                modificarDatos();
                            }
                        }]
                    });
                    winMod.on('show',function(){
                                                                                                                                                                                                                                  
                                                                                                                        
                        auxIns2 = false;
                        auxMod2 = false;
                    },this)
                    winMod.on('hide',function(){
                                                                                                                   
                        auxIns2 = true;
                        auxMod2 = true;
                    },this)
                }
                b = Ext.getCmp('brillo');
                b.value = brillo;
                c= Ext.getCmp('contraste');
                c.value = contraste;
                winMod.add(regDatos);
                winMod.doLayout();                                                       
                winMod.show();                                                       
                a = Ext.getCmp('img-detail-panel').body;                                                        
                tpl1.overwrite(a, data);
                regDatos.getForm().loadRecord(sm.getSelected());
                contrasteMod,anchoMod,altoMod,formaMod,formatoMod,cantimgMod;
       
                brilloMod = Ext.getCmp('brillo').getValue();
                contrasteMod = Ext.getCmp('contraste').getValue();
                anchoMod = Ext.getCmp('ancho').getValue();
                altoMod = Ext.getCmp('alto').getValue();
                formaMod = Ext.getCmp('forma').getValue();
                formatoMod = Ext.getCmp('formato').getValue();
                cantimgMod = Ext.getCmp('cantimg').getValue();		

							
            }
            break;
						
        }
    }
			
    ////------------ Adicionar datos ------------////
    function adicionarDatos(apl){
        if (regDatos.getForm().isValid() ){
            regDatos.getForm().submit({
                url:'insertarDatos',
                waitMsg:perfil.etiquetas.lbMsgFunAdicionarMsg,
                params:{
                    brillo:Ext.getCmp('brillo').getValue(),
                    contraste:Ext.getCmp('contraste').getValue()
                },
                failure: function(form, action){
                    if(action.result.codMsg != 3){
                        mostrarMensaje(action.result.codMsg,action.result.mensaje); 
                        regDatos.getForm().reset(); 					
                        winIns.hide();					
                        stDatimg.reload();
                        sm.clearSelections();
                        btnModificar.disable();
							
                    }
                    if(action.result.codMsg == 3) mostrarMensaje(action.result.codMsg,action.result.mensaje);
							
                }
            });
        }
        else
            mostrarMensaje(3,perfil.etiquetas.lbMsgErrorEnCamops);
    }
			
    ////------------ Modififcar datos ------------////
    function modificarDatos(apl){
        var brillM = brilloMod != Ext.getCmp('brillo').getValue();
        var cnntrM = contrasteMod != Ext.getCmp('contraste').getValue();
        var anchM = anchoMod != Ext.getCmp('ancho').getValue();
        var altM = altoMod != Ext.getCmp('alto').getValue();
        var formM = formaMod != Ext.getCmp('forma').getValue();
        var fortM = formatoMod != Ext.getCmp('formato').getValue();
        var cantM = cantimgMod != Ext.getCmp('cantimg').getValue();
        if(apl){
            if(brillM||cnntrM||formM){
                Ext.Ajax.request({
                    url: 'modificardatosImagen',
                    params:{
                        brillo:Ext.getCmp('brillo').getValue(),
                        contraste:Ext.getCmp('contraste').getValue(),
                        apl:1,
                        iddatosimg:Ext.getCmp('iddatosimg').getValue(),
                        forma:Ext.getCmp('forma').getValue()
                    },		  
                    callback: function (options,success,response){
	                responseData = Ext.decode(response.responseText);
                        
                      if(responseData.bien == 3)
                      mostrarMensaje(responseData.bien,perfil.etiquetas.lbMsgNolibrary); 
                      else{              
                        stDatimg.reload();
                        a.update('');								
                        cargarDir ();  
                        tpl1.overwrite(a, data);  
                        brilloMod = Ext.getCmp('brillo').getValue();
                        ;
                        contrasteMod = Ext.getCmp('contraste').getValue();
                        formaMod=Ext.getCmp('forma').getValue();
                      }
                    }
                });
            }
            else
                mostrarMensaje(3,perfil.etiquetas.NoModify);
                

        }
        else
        {
            if (regDatos.getForm().isValid()){
					       
                if(cnntrM||anchM||altM||formM||fortM||brillM||cantM){
							
                    regDatos.getForm().submit({
                        url:'modificardatosImagen',
                        waitMsg:perfil.etiquetas.lbMsgFunModificarMsg,
                        params:{
                            brillo:Ext.getCmp('brillo').getValue(),
                            contraste:Ext.getCmp('contraste').getValue()
                        },
                        failure: function(form, action){
                            if(action.result.codMsg != 3){
                                //mostrarMensaje(action.result.codMsg,perfil.etiquetas.lbMsgInfModificar); 
                                stDatimg.reload();
                                a.update('');
                                winMod.hide();
                                cargarDir ();


                            }
                           // if(action.result.codMsg == 3) mostrarMensaje(action.result.codMsg,action.result.mensaje);
							
                        }
                    });
                }
                else
                    mostrarMensaje(3,perfil.etiquetas.NoModify);

            }
            else
                mostrarMensaje(3,perfil.etiquetas.lbMsgErrorEnCamops);
        }
    }
			
			
    ////------------ Funcion para validar boton adicionar si existe alguna clave ------------//// 
    function mostrar()
    {
        if(stDatimg.getCount() != 0)
            btnAdicionar.disable();
        else
        {
            btnAdicionar.enable();
            auxIns = true;
        }
    }

          


   
       


}
		
