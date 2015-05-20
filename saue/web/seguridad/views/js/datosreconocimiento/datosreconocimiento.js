
var perfil = window.parent.UCID.portal.perfil;
perfil.etiquetas = Object();
UCID.portal.cargarEtiquetas('datosreconocimiento',cargarInterfaz);
		
////------------ Inicializo el singlenton QuickTips ------------////
Ext.QuickTips.init();
		
////------------ Declarar variables ------------////
var winIns, winMod
var metododistanciaMod ,metodoknnMod,metodorecMod;
////------------ Area de validaciones ------------////
var soloNumeros;
soloNumeros = /^[0-9]+$/;
		
function cargarInterfaz()
{
    ////------------ Botones ------------////
    btnAdicionar = new Ext.Button({
        disabled:true,
        id:'btnDatRec', 
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
    id:'btnModDatosR', 
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
var stCongContr =  new Ext.data.Store({
    url: 'cargardatos',
    reader:new Ext.data.JsonReader({
        totalProperty: "cantidad_filas",
        root: "datos",
        id: "iddatosreconocimiento"
    },
    [
    {
        name:'iddatosreconocimiento',
        mapping:'iddatosreconocimiento'
    },

    {
        name:'metododistancia',
        mapping:'metododistancia'
    },

    {
        name:'metodoknn',
        mapping:'metodoknn'
    },

    {
        name:'metodorec',
        mapping:'metodorec'
    },

    {
        name:'ndescomposicion',
        mapping:'ndescomposicion'
    },
    ])
});
			 
////------------ Modo de seleccion del grid ------------////
sm = new Ext.grid.RowSelectionModel({
    singleSelect:true
});
			
sm.on('beforerowselect', function (smodel, rowIndex, keepExisting, record){
    btnModificar.enable();
							
}, this);
			
			
stCongContr.on('load', function(){
                            
                           
    if(stCongContr.getCount() != 0){
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
			
////------------ Defino el grid de contrase�as ------------////
var gpContr= new Ext.grid.GridPanel({
    frame:true,
    region:'center',
    iconCls:'icon-grid',
    autoExpandColumn:'expandir',
    store:stCongContr,
    sm:sm,
    columns: [
    {
        hidden: true, 
        hideable: false,  
        dataIndex: 'iddatosreconocimiento'
    },

    {
        id:'expandir',
        header:perfil.etiquetas.lbBtnMetododistancia,
        width:100,  
        dataIndex: 'metododistancia'
    },

    {
        header: perfil.etiquetas.lbBtnMetodoknn,
        width:500, 
        dataIndex: 'metodoknn'
    },

    {
        header: perfil.etiquetas.lbBtnMetodoRec,
        width:200, 
        dataIndex: 'metodorec'
    }
    ],
    loadMask:{
        store:stCongContr
    }
   
});
////------------ Trabajo con el PagingToolbar ------------////

					
////------------ Panel ------------////
var panel = new Ext.Panel({
    layout:'border',
    title:perfil.etiquetas.lbTitPanelTit,
    renderTo:'panel',
    items:[gpContr],
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
stCongContr.on('load',function(){
    if(stCongContr.getCount() != 0)
        auxMod2 = true;
    else
        auxMod2 = false;
},this)
////------------ Viewport ------------////
var vpServidorAut = new Ext.Viewport({
    layout:'fit',
    items:panel
})		
stCongContr.load({
    params:{
        start:0, 
        limit:15
    }
});	
                        
                        
                        
                        
var MetodoR = new Ext.data.SimpleStore({
    fields: ['id', 'MetodoR'],
    data : [['1','PCA'],['2','Wavelet+PCA']]
});
var formato = new Ext.data.SimpleStore({
    fields: ['id', 'formato'],
    data : [['1','Manhattan'],['2','Euclidean']/*,['3','Chebychev']*/]
});
var forma = new Ext.data.SimpleStore({
    fields: ['id', 'forma'],
    data : [['1','No ponderado'],['2','Ponderado']]
});
var niveles = new Ext.data.SimpleStore({
    fields: ['id', 'niveles'],
    data : [['1','Nivel 1'],['2','Nivel 2'],['3','Nivel 3'],['4','Nivel 4'],['5','Nivel 5'],['6','Nivel 6'],['7','Nivel 7'],['8','Nivel 8']]
});
	
									
////------------ Formulario ------------////			
var regDatos = new Ext.FormPanel({
    labelAlign: 'top',
    //region:'center',
    frame:true,
    //bodyStyle:'padding:5px 5px 0',
    items: [{
        layout:'column',
        items:[							
        {
            columnWidth:.50,
            layout: 'form',
            items: [        
                                                                                        
												
            {               
                allowBlank : false,
                fieldLabel:perfil.etiquetas.lbBtnMetododistancia,
                anchor:'95%',
                xtype:'combo',
                readOnly : true,
                name: 'metododistancia',													
                mode: 'local',	
                id:'metododistancia',                                                   
                triggerAction : 'all',
                editable : false,
                displayField:'formato',
                store: formato,
                lazyRender:true
                                                                                                       
													
            },
													
            {
                xtype:'combo',
                name: 'metodoknn',
                id:'metodoknn',
                fieldLabel:perfil.etiquetas.lbBtnMetodoknn,
                mode: 'local',												
                displayField:'forma',
                store: forma,
                lazyRender:true,
                readOnly : true,
                editable : false,
                triggerAction : 'all',
                anchor:'95%',
                allowBlank : false,
            },
            {
                xtype:'hidden',													
                id:'iddatosreconocimiento',
                name: 'iddatosreconocimiento',													
                anchor:'95%'
            }
			
            ]
        },{
            columnWidth:.50,
            layout: 'form',
            items: [
            {               
                allowBlank : false,
                fieldLabel:perfil.etiquetas.lbBtnMetodoRec,
                id:'mr',
                anchor:'95%',
                xtype:'combo',
                readOnly : true,
                name: 'metodorec',
                id:	'metodorec',												
                mode: 'local',	                                                                                                       
                triggerAction : 'all',
                editable : false,
                displayField:'MetodoR',
                store: MetodoR,
                lazyRender:true
                                                                                                        
													
            },
            //                                                                         {               
            //                                                                                allowBlank : false,
            //                                                                                fieldLabel:'Nivel de descomposicion',
            //                                                                                anchor:'95%',
            //                                                                                id:'nd',
            // 	xtype:'combo',
            //                                                                                readOnly : true,
            // 	name: 'ndescomposicion',													
            // 	mode: 'local',	
            //                                                                                triggerAction : 'all',
            //                                                                                editable : false,
            // 	displayField:'niveles',
            // 	store: niveles,
            // 	lazyRender:true
            //                                                                                            // hidden:true,
            //                                                                               // hideLabel:true 
													
            // }
                                                                            
                                                                            
                                                                            
                                                                            
                                                                            
            ] 
                                                                            
                                                                            
                                                                            
                                                                            
        }]
									
    }]
});
//// Ext.getCmp('mr').on('select',function(){
////    if( Ext.getCmp('mr').getValue()== 'Wavelet+PCA')
////    {                           
////        Ext.getCmp('nd').enable();
//                               
////    }
////    else
////    {                                
////        Ext.getCmp('nd').disable();
//                               
////    }
// },this)
                    
//Cargar la ventana
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
                    height:230,
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
                    title:'Métodos',
                    width:400,
                    height:200,
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
            // alert(sm.getSelected().data.metodorec);
            //if(sm.getSelected().data.metodorec == 'PCA')
            //Ext.getCmp('nd').disable();
            winMod.add(regDatos);
            winMod.doLayout();
            winMod.show();
            regDatos.getForm().loadRecord(sm.getSelected());
            metododistanciaMod = Ext.getCmp('metododistancia').getValue();
            metodoknnMod = Ext.getCmp('metodoknn').getValue();
            metodorecMod = Ext.getCmp('metodorec').getValue();
							
        }
        break;
						
    }
}
			
////------------ Adicionar clave ------------////
function adicionarDatos(apl){
    if (regDatos.getForm().isValid()){
        regDatos.getForm().submit({
            url:'insertardatos',
            waitMsg:perfil.etiquetas.lbMsgFunAdicionarMsg,
						
            failure: function(form, action){
                if(action.result.codMsg != 3){
                    mostrarMensaje(action.result.codMsg,perfil.etiquetas.lbMsgFunModificarMsgI); 
                    winIns.hide();	
                    stCongContr.reload();			
							        
							
                }
                if(action.result.codMsg == 3) mostrarMensaje(action.result.codMsg,action.result.mensaje);
							
            }
        });
    }
    else
        mostrarMensaje(3,perfil.etiquetas.lbMsgErrorEnCamops);
}
			
////------------ Modififcar clave ------------////
function modificarDatos(){
    if (regDatos.getForm().isValid()){

        var metDist = metododistanciaMod != Ext.getCmp('metododistancia').getValue();
        var metknn = metodoknnMod != Ext.getCmp('metodoknn').getValue();
        var metRec = metodorecMod != Ext.getCmp('metodorec').getValue();
        if(metDist||metknn||metRec){
            regDatos.getForm().submit({
                url:'modificardatosReconocimiento',
                waitMsg:perfil.etiquetas.lbMsgFunModificarMsg,						
                failure: function(form, action){
                    if(action.result.codMsg != 3){
                        //mostrarMensaje(action.result.codMsg,perfil.etiquetas.lbMsgFunModificarMsgI); 
                        stCongContr.reload();
                        winMod.hide();
                    }                    
							
                }
            });
        }
        else
            mostrarMensaje(3,perfil.etiquetas.NoModify);
    }
    else
        mostrarMensaje(3,perfil.etiquetas.lbMsgErrorEnCamops);
}
			
			
////------------ Funcion para validar boton adicionar si existe algun datos ------------//// 
function mostrar()
{
    alert('zz');
    if(stCongContr.getCount() != 0)
        btnAdicionar.disable();
    else
    {
        btnAdicionar.enable();
        auxIns = true;
    }
}
}
		
