/* Migracion 
 * Ricardo Suarez
 *                
 *                                
 */    
var perfil = window.parent.UCID.portal.perfil;
perfil.etiquetas = Object();
UCID.portal.cargarEtiquetas('gestnomgestor',cargarInterfaz);
////------------- Inicializo el singlenton QuickTips -------------////
Ext.QuickTips.init();
		
////------------- Declarar Variables -------------////
var winIns, winMod;
var auxIns = false;
var auxMod = false;
var auxDel = false;
var auxMod1 = false;
var auxDel1 = false;
var auxIns2 = true;
var auxMod2 = true;
var auxDel2 = true;
var auxBus2 = true;
var descripcionMod,puertoMod,gestorMod;
////------------ Area de Validaciones ------------////
var tipos, soloNumeros;
var auxDelete = true;
soloNumeros = /^-?\d*$/;
tipos = /^([a-zA-ZáéíóúñüÑ]+[a-zA-ZáéíóúñüÑ\d\.\-\@\#\_]*)+$/
		
function cargarInterfaz()
{
    ////------------- Botones -------------////
    btnAdicionar = new Ext.Button({
        id:'btnAgrGest',  
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
    id:'btnModGest', 
    hidden:true, 
    icon:perfil.dirImg+'modificar.png', 
    iconCls:'btn', 
    text:perfil.etiquetas.lbBtnModificar, 
    handler:function(){
        winForm('Mod');
    }
});		
btnEliminar = new Ext.Button({
    disabled:true,
    id:'btnEliGest', 
    hidden:true, 
    icon:perfil.dirImg+'eliminar.png', 
    iconCls:'btn', 
    text:perfil.etiquetas.lbBtnEliminar,
    handler:function(){
        eliminargestor();
    }
});
/*btnAyuda = new Ext.Button({id:'btnAyuGest', hidden:true, icon:perfil.dirImg+'ayuda.png', iconCls:'btn', text:perfil.etiquetas.lbBtnAyuda});*/
UCID.portal.cargarAcciones(window.parent.idFuncionalidad);
		
////------------ Combo Box gestores ------------////
var cbGestoresData = [
{
    'gestor':'pgsql',
    'denominacion': 'PostgreSQL'
},
{
    'gestor':'oracle',
    'denominacion': 'Oracle'
}
];
		
var storeDataTmp = new Ext.data.Store({
    fields: ['gestor','denominacion'],
    data : cbGestoresData
});			
////------------- Store del Grid de Gestores -------------////
var  stGpGestor =  new Ext.data.Store({
    fields:
    [
    {
        name:'idgestor',
        mapping:'idgestor'
    },

    {
        name:'gestor',
        mapping:'gestor'
    },

    {
        name:'puerto',
        mapping:'puerto'
    },

    {
        name:'descripcion',
        mapping:'descripcion'
    }
    ],
    proxy:{
        type:'ajax',
        url: 'cargarnomgestores',
        actionMethods:{
            read:'POST'
        },
        reader:{
            totalProperty: 'cantidad_filas',
            root: 'datos',
            id: 'id'
        }
    }		
			
});
		
////------------ Establesco modo de seleccion de grid (single) ---------////
var sm = Ext.create('Ext.selection.RowModel',{
    mode:'SINGLE' 
});

sm.on('beforeselect', function (smodel, rowIndex, keepExisting, record){
    btnModificar.enable();
    btnEliminar.enable();
}, this);
				
		
////---------- Defino el grid de gestores ----------////
var gpGestor = new Ext.grid.GridPanel({
    frame:true,
    header:false,
    iconCls:'icon-grid',
    autoExpandColumn:'expandir',
    paginate:true,
    store:stGpGestor,
    selModel:sm,
    columns: [
    {
        hidden: true, 
        hideable: false,  
        dataIndex: 'idgestor'
    },

    {
        header: perfil.etiquetas.lbTitDenominacion, 
        width:200, 
        dataIndex: 'gestor',
        flex:1
    },

    {
        header: perfil.etiquetas.lbTitPuerto, 
        dataIndex: 'puerto'
    },

    {
        flex:1, 
        header: perfil.etiquetas.lbTitDescripcion, 
        dataIndex: 'descripcion'
    }
    ],
    loadMask:{
        store:stGpGestor
    },		 
    tbar:[
    //					new Ext.Toolbar.TextItem({text:perfil.etiquetas.lbTitDenBuscar}),
    gestor = new Ext.form.TextField({
        fieldLabel: perfil.etiquetas.lbTitDenBuscar,
        anchor: '90%',
        labelWidth : 40,
        id: 'nombregestor'
    }),
    new Ext.menu.Separator(),			
    new Ext.Button({
        icon:perfil.dirImg+'buscar.png',
        iconCls:'btn',
        text:'<b>'+perfil.etiquetas.lbBtnBuscar+'</b>', 
        handler:function(){
            buscargestor(gestor.getValue());
        }
    })
],		
bbar:new Ext.PagingToolbar({
    pageSize: 15,
    id:'ptbaux',
    store: stGpGestor,
    displayInfo: true,
    displayMsg: perfil.etiquetas.lbMsgbbarI,
    emptyMsg: perfil.etiquetas.lbMsgbbarII
})
});
////------------ Trabajo con el PagingToolbar ------------////
Ext.getCmp('ptbaux').on('change',function(){
    sm.select();
},this);
		
////------------- Renderiar el arbol -------------////
var panel = new Ext.Panel({
    layout:'fit',
    //renderTo:'panel',
    title:perfil.etiquetas.lbTitPanelTit,
    items:[gpGestor],
    tbar:[btnAdicionar,btnModificar,btnEliminar/*,btnAyuda*/],
    keys: new Ext.KeyMap(document,[{
        key:Ext.EventObject.DELETE,
        fn: function(){
            if(auxDelete && auxDel && auxDel1 && auxDel2)
                eliminargestor();
        }
    },
    {
        key:"i",
        alt:true,
        fn: function(){
            if(auxIns && auxIns2)	
                winForm('Ins');
        }
    },
    {
        key:"b",
        alt:true,
        fn: function(){
            if(auxBus2)	
                buscargestor(Ext.getCmp('nombregestor').getValue());
        }
    },
    {
        key:"m",
        alt:true,
        fn: function(){
            if(auxMod && auxMod1 && auxMod2)		
                winForm('Mod');
        }
    }])
});
////------------ Eventos para hotkeys ------------////
btnAdicionar.on('show',function(){
    auxIns = true;
},this);
btnEliminar.on('show',function(){
    auxDel = true;
},this);
btnModificar.on('show',function(){
    auxMod = true;
},this);
Ext.getCmp('nombregestor').on('focus',function(){
    auxDelete = false;
},this);
Ext.getCmp('nombregestor').on('blur',function(){
    auxDelete = true;
},this);
stGpGestor.on('load',function(){
    if(stGpGestor.getCount() != 0)
    {
        auxMod1 = true;
        auxDel1 = true;
    }
    else
    {
        auxMod1 = false;
        auxDel1 = false;
    }
},this)
			
////------------ ViewPort ------------////
new Ext.Viewport({
    layout:'fit',
    items:panel
});
		
		
stGpGestor.load({
    params:{
        limit:10,
        start:0
    }
});
		
////------------- Formulario -------------////
var regGestor = new Ext.FormPanel({
    labelAlign: 'top',
    frame:true,
    bodyStyle:'padding:5px 5px 0',
    items: [{
        layout:'column',
        items:[{
            columnWidth:.5,
            layout:'form',
            margin: '5 5 5 5',
            border:0,
            items:[new Ext.form.ComboBox({
                emptyText:perfil.etiquetas.lbSeleccionargestor,
                editable:false,
                labelAlign: 'top',
                fieldLabel:perfil.etiquetas.lbFLDenominacion,
                store:storeDataTmp,
                id:'tipogestor',
                valueField:'gestor',
                displayField:'denominacion',
                hiddenName:'gestor',
                name: 'gestor',
                forceSelection:true,
                //typeAhead: true,
                mode: 'local',
                allowBlank:false,
                triggerAction: 'all',                        
                // selectOnFocus:true,
                tabIndex:3, 
                anchor:'95%'
            })]
        },
        {
            columnWidth:.5,
            layout: 'form',
            margin: '5 5 5 5',
            border: 0,
            items: [{
                xtype:'textfield',
                fieldLabel:perfil.etiquetas.lbFLPuerto,
                id:'puerto',
                name:'puerto',
                labelAlign: 'top',
                allowBlank: false,
                blankText:perfil.etiquetas.lbMsgBlank,
                regex:soloNumeros,
                regexText:perfil.etiquetas.lbMsgregexII,
                maxLength:4,
                anchor:'95%'
            }]
        },
        {
            columnWidth:10,
            layout: 'form',
            margin: '5 5 5 5',
            border: 0,
                                                        
            items: [{
                xtype:'textarea',
                labelAlign: 'top',
                fieldLabel: perfil.etiquetas.lbFLDescripcion,
                id: 'descripcion',
                anchor:'95%',             
                name:'descripcion'
            }]
        }]
    }]
});
               
              
////------------- Cargar la ventana ---------------////
function winForm(opcion){
    switch(opcion){
        case 'Ins':
        {
            if(!winIns)
            {
                                                
                                               
                                                
                winIns = new Ext.Window({
                    modal: true,
                    closeAction:'hide',
                    layout:'fit',
                    title:perfil.etiquetas.lbTitVentanaTitI,
                    width:400,
                    height:260,
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
                        icon:perfil.dirImg+'aplicar.png',
                        iconCls:'btn',
                        text:perfil.etiquetas.lbBtnAplicar,
                        handler:function(){
                            adicionargestor('apl');
                        }
                    },
                    {	
                        icon:perfil.dirImg+'aceptar.png',
                        iconCls:'btn',
                        text:perfil.etiquetas.lbBtnAceptar,
                        handler:function(){
                            adicionargestor();
                        }
                    }]
                });
                winIns.on('show',function(){
                    auxIns2 = false;
                    auxMod2 = false;
                    auxDel2 = false;
                    auxBus2 = false;
                },this);
                winIns.on('hide',function(){
                    auxIns2 = true;
                    auxMod2 = true;
                    auxDel2 = true;
                    auxBus2 = true;
                },this);
            }		
            regGestor.getForm().reset();	   
            winIns.add(regGestor);
            winIns.doLayout();
            winIns.show();
        }
        break;
        case 'Mod':{
            if(!winMod)
            {
                winMod= new Ext.Window({
                    modal: true,
                    closeAction:'hide',
                    layout:'fit',
                    title:perfil.etiquetas.lbTitVentanaTitII,
                    width:400,
                    height:260,
                    buttons:[
                    {
                        icon:perfil.dirImg+'cancelar.png',
                        iconCls:'btn',
                        text:perfil.etiquetas.lbBtnCancelar,
                        handler:function(){
                            winMod.hide();
                        }
                    },
                    {
                        icon:perfil.dirImg+'aceptar.png',
                        iconCls:'btn',
                        text:perfil.etiquetas.lbBtnAceptar,
                        handler:function(){
                            modificargestor();
                        }
                    }]
                });
                winMod.on('show',function(){
                    auxIns2 = false;
                    auxMod2 = false;
                    auxDel2 = false;
                    auxBus2 = false;
                },this);
                winMod.on('hide',function(){
                    auxIns2 = true;
                    auxMod2 = true;
                    auxDel2 = true;
                    auxBus2 = true;
                },this)
            }
                                
                                
            winMod.add(regGestor);
            winMod.doLayout();
            winMod.show();
            regGestor.getForm().loadRecord(sm.getLastSelected());
            descripcionMod=Ext.getCmp('descripcion').getValue();				
            gestorMod=Ext.getCmp('tipogestor').getValue();				
            puertoMod=Ext.getCmp('puerto').getValue();	
        }
        break;
    }
}
                
                
              
function ExisteGestorPuerto(Gest,Puerto){
               
    for(var i=0; i<stGpGestor.getCount();i++){
        var record=stGpGestor.getAt(i);
        var oldGest=record.get('gestor');
        var oldPuerto=record.get('puerto');
        if(oldGest==Gest&&oldPuerto==Puerto)
            return true;
    }
    return false;
}
////------------- Adicionar Gestor -------------////
function adicionargestor(apl){
    var Gest=Ext.getCmp('tipogestor');
    var Puerto=Ext.getCmp('puerto').getValue();
    if(!ExisteGestorPuerto(Gest.getValue(),Puerto)){
        if (regGestor.getForm().isValid())
        {
            regGestor.getForm().submit({
                url:'insertargestor',
                waitMsg:perfil.etiquetas.lbMsgFunAdicionarMsg,
                failure: function(form, action){
                    if(action.result.codMsg != 3)
                    {                         
                        regGestor.getForm().reset(); 
                        if(!apl) 
                            winIns.hide();
                        stGpGestor.load();
                        sm.clearSelections();
                        btnModificar.disable();
                        btnEliminar.disable();
                    }
                    if(action.result.codMsg == 3) mostrarMensaje(action.result.codMsg,action.result.mensaje);
                }	
            });
        }
        else
            mostrarMensaje(3,perfil.etiquetas.lbMsgErrorEnCamops);
    }
    else{
        mostrarMensaje(3,perfil.etiquetas.GestorPuertoRepetido);
    }
}

	
function eliminargestor(){
    mostrarMensaje(2,perfil.etiquetas.lbMsgFunEliminarMsgI,elimina);
    function elimina(btnPresionado)
    {
        if (btnPresionado == 'ok')
        {
            Ext.Ajax.request({
                url: 'comprobargestores',
                method:'POST',
                params:{
                    idgestor:sm.getLastSelected().data.idgestor
                    },
                callback: function (options,success,response){
                    responseData = Ext.decode(response.responseText);//                    
                    if(responseData.codMsg == 1)
                    {                      
                        stGpGestor.load();
                        sm.clearSelections();
                        btnEliminar.disable();
                    }
                    if(responseData.codMsg == 3) mostrarMensaje(responseData.codMsg,responseData.mensaje);
                }
            });
        }
    }
}

////------------- Modificar Gestor -------------////
function modificargestor(){
                    
    if (regGestor.getForm().isValid())
    {
        var desMod=descripcionMod!=Ext.getCmp('descripcion').getValue();
        var pMod =puertoMod!=Ext.getCmp('puerto').getValue();
        var gMod =gestorMod!=Ext.getCmp('tipogestor').getValue();
        if(desMod||gestorMod!=Ext.getCmp('tipogestor').getValue()||pMod){
            var Gest=Ext.getCmp('tipogestor');
            var Puerto=Ext.getCmp('puerto').getValue();
            var extGestPuert=ExisteGestorPuerto(Gest.getValue(),Puerto);
            if(!pMod&&!gMod){
                regGestor.getForm().submit({
                    url:'modificaromgestor',
                    waitMsg:perfil.etiquetas.lbMsgFunModificarMsg,
                    params:{
                        idgestor:sm.getLastSelected().data.idgestor
                        },
                    failure: function(form, action)
                    {
                        if(action.result.codMsg != 3)
                        {                            
                            stGpGestor.load();
                            winMod.hide();
                        }
                        if(action.result.codMsg == 3) mostrarMensaje(action.result.codMsg,action.result.mensaje);
                    }
                });
            }else if(!extGestPuert){
                regGestor.getForm().submit({
                    url:'modificaromgestor',
                    waitMsg:perfil.etiquetas.lbMsgFunModificarMsg,
                    params:{
                        idgestor:sm.getLastSelected().data.idgestor
                        },
                    failure: function(form, action)
                    {
                        if(action.result.codMsg != 3)
                        {                           
                            stGpGestor.load();
                            winMod.hide();
                        }
                        if(action.result.codMsg == 3) mostrarMensaje(action.result.codMsg,action.result.mensaje);
                    }
                });
            }
            else{
                mostrarMensaje(3,perfil.etiquetas.GestorPuertoRepetido);
            }
        }
        else{
            mostrarMensaje(3,perfil.etiquetas.NoModify);
        }
                               
    }
    else
        mostrarMensaje(3,perfil.etiquetas.lbMsgErrorEnCamops);        
}
			
		 
////------------ Buscar Gestor ------------////
function buscargestor(gestor){  
    stGpGestor.load({
        params:{
            gestor:gestor,
            start:0,
            limit:10
        }
    });
}

}


