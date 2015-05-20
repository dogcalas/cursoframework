
var perfil = window.parent.UCID.portal.perfil;
perfil.etiquetas = Object();
UCID.portal.cargarEtiquetas('gestgestor',cargarInterfaz);
////------------ Inicializo el singlenton QuickTips ------------////
Ext.QuickTips.init();
			
////------------ Declarar Variables ------------////
var winIns, winMod, idservidor,nodoPadreBandera,idgestor;
var auxIns = false;
var auxDel = false;
var auxIns2 = true;
var auxDel2 = true;
var auxDel1 = false;
               
function cargarInterfaz(){
    ////------------ Botones ------------////
    btnAdicionar = new Ext.Button({
        disabled:true, 
        id:'btnAgrgestorBd', 
        hidden:true, 
        icon:perfil.dirImg+'adicionar.png', 
        iconCls:'btn', 
        text:perfil.etiquetas.lbBtnAdicionar, 
        handler:function(){
            winForm('Ins');
        }
    });
btnEliminar = new Ext.Button({
    disabled:true, 
    id:'btnEligestorBd', 
    hidden:true, 
    icon:perfil.dirImg+'eliminar.png', 
    iconCls:'btn',
    text: perfil.etiquetas.lbBtnEliminar,
    handler:function(){
        eliminargestor();
    }
}),
btnRecargar = new Ext.Button({
    disabled:false,
    icon:perfil.dirImg+'actualizar.png',
    iconCls:'btn',  
    text:perfil.etiquetas.lbBtnRecargar, 
    handler:function(){
    stGestores.load();       
    }
});

/*btnAyuda = new Ext.Button({id:'btnAyugestorBd', hidden:true, icon:perfil.dirImg+'ayuda.png', iconCls:'btn', text:perfil.etiquetas.lbBtnAyuda});*/
UCID.portal.cargarAcciones(window.parent.idFuncionalidad);
                
///---------------Store--------------------///
Ext.define('modelotree', {
    extend: 'Ext.data.Model',
    fields:['idgestor','idservidor','leaf','codigosid','id','abreviatura','descripcion','icono','text']
});	
var stGestores = Ext.create('Ext.data.TreeStore', {
    model: 'modelotree',
    idProperty: 'idgestor',           
    proxy: {
        type: 'ajax',
        url: 'cargarservidores',
        actionMethods:{ //Esta Linea es necesaria para el metodo de llamada POST o GET
                                        
            read:'POST'
        },
        reader: {
            type: 'json'
        }
    }
});
         
                
////------------ Arbol Servidores-Gestores ------------////
var arbolGestor = new Ext.tree.TreePanel({
    title:perfil.etiquetas.lbTitReg,
    collapsible:true,
    autoScroll:true,
    region:'west',
    store: stGestores,
    root: {
        expandable:true,
        text: perfil.etiquetas.lbTit,
        expanded:false,
        id:'0'
    },
    split:true,
    width:'37%',
    bbar: ['->',btnRecargar]
});

////--------------- Evento para habilitar botones -------------////
		arbolGestor.on('itemclick', function( a, node, item, index, e, eOpts ){
       			btnEliminar.disable();
			btnAdicionar.disable();
			stGpGestor.removeAll();
			if (node.isLeaf())
			{       
                                
                                gpGestor.enable();
				stGpGestor.removeAll();
				idservidor=node.data.id;
				stGpGestor.load({params:{start:0,limit:15}});
				btnAdicionar.enable();
				btnEliminar.disable();
				auxIns = true;
				auxDel1 = true;
			}
			else
			{
				auxDel1 = false;
				auxIns = false;
			}
                        if(node.data.id == 0)
                            gpGestor.disable(); 

		}, this);
    	
////------------ Crear nodo padre del arbol ------------////
//   		padreArbolGestor = new Ext.tree.AsyncTreeNode({
//          text: perfil.etiquetas.lbTit,
//		  expandable:false,
//		  expanded:true,
//		  id:'0'
//        });
//		arbolGestor.setRootNode(padreArbolGestor);
		
////--------------- Evento para habilitar botones -------------////
//		arbolGestor.on('click', function (node, e){
//			btnEliminar.disable();
//			btnAdicionar.disable();
//			stGpGestor.removeAll();
//			if (node.isLeaf())
//			{
//				gpGestor.enable();
//				stGpGestor.removeAll();
//				idservidor=node.data.id;
//				stGpGestor.load({params:{start:0,limit:15}});
//				btnAdicionar.enable();
//				btnEliminar.disable();
//				auxIns = true;
//				auxDel1 = true;
//			}
//			else
//			{
//				auxDel1 = false;
//				auxIns = false;
//			}
//                        if(node.data.id == 0)
//                            gpGestor.disable();
                            

//		}, this);
		
////------------- Store del Grid de Gestores -------------- ////
var stGpGestor =  new Ext.data.Store({                         
    listeners:{
        'beforeload':function(thisstore,objeto){
            objeto.params={};
            objeto.params.idservidor=idservidor
        }},
    fields:[
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
    },
    {
        name:'sid',
        mapping:'sid'
    }
    ],
   
    	
proxy: {
    type: 'ajax',
    url: 'cargargestores',
    actionMethods:{ //Esta Linea es necesaria para el metodo de llamada POST o GET
                                        
        read:'POST'
    },
    reader: {
        type: 'json',
        totalProperty: "cantidad_filas",
        root: "datos",
        id: "id"
    }
}
			
});
		
////------------ Establesco modo de seleccion de grid (single) ---------////
var sm = Ext.create('Ext.selection.RowModel',{
    mode:'SINGLE'
});
sm.on('beforeselect', function (smodel, rowIndex, keepExisting, record){
    btnEliminar.enable();
}, this);
////---------- Defino el grid de gestores ----------////
var gpGestor = new Ext.grid.GridPanel({
    frame:true,
    header:false,
    region:'center',
    iconCls:'icon-grid',
    autoExpandColumn:'expandir',
    store:stGpGestor,
    selModel:sm,
    disabled:true,
    columns: [
    {
        hidden: true, 
        hideable: false,  
        dataIndex: 'idgestor'
    },

    {
        hidden: true, 
        hideable: false, 
        dataIndex: 'sid'
    },

    {
        header: perfil.etiquetas.lbGestor, 
        width:150, 
        dataIndex: 'gestor',
        flex:1
    },

    {
        header: perfil.etiquetas.lbPuerto, 
        dataIndex: 'puerto'
    },

    {
        id:'expandir',
        header: perfil.etiquetas.lbDes,
        width:300, 
        dataIndex: 'descripcion'
    }
    ],

    loadMask:{
        store:stGpGestor
    },		
    bbar:new Ext.PagingToolbar({
        pageSize: 15,
        id:'ptbaux',
        store: stGpGestor,
        displayInfo: true,
        displayMsg: perfil.etiquetas.lbDisplayMsg,
        emptyMsg: perfil.etiquetas.lbEmptyMsg
    })
});
////------------ Trabajo con el PagingToolbar ------------////
Ext.getCmp('ptbaux').on('change',function(){
    sm.select();
},this);
////------------- modo de seleccion del combo ------------------////
//var cms = new Ext.grid.RowSelectionModel({singleSelect:true});
		
////------------------ Store del combobox de expresiones -----------------////	
var storeGestor =  new Ext.data.Store({
    fields:[
    {
        name: 'idgestor', 
        mapping:'idgestor'
    },
    {
        name:'gestor', 
        mapping:'gestor'
    },
    {
        name:'gestorpuerto', 
        mapping:'gestorpuerto'
    }
    ],
    listeners:{
        beforeLoad:function(a,b){
            b.params={};
            b.params.idservidor=arbolGestor.getSelectionModel().getLastSelected().data.id
        },
        load:function(c,d){
            if(d.length==0)
                mostrarMensaje(1,perfil.etiquetas.MsgGestoresVacio);
        }
    },
    proxy: {
        type: 'ajax',
        url: 'cargarcombogestores',
        id: 'id',
        actionMethods:{ //Esta Linea es necesaria para el metodo de llamada POST o GET                           
            read:'POST'
        },
        reader: {
            type: 'json',
            id:'id'
        }   
    }
});
		
////------------- Renderiar el Arbol ----------------////
var panel = new Ext.Panel({
    layout:'border',
    title:perfil.etiquetas.lbTitTitulo,
    items:[gpGestor,arbolGestor],
    tbar:[btnAdicionar,btnEliminar/*,btnAyuda*/],
    keys: new Ext.KeyMap(document,[{
        key:Ext.EventObject.DELETE,
        fn: function(){
            if(auxDel && auxDel1 && auxDel2)
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
    }])
});
                
//mostrarMensaje("Hacer");
                
////---------- Eventos para hotkeys ----------////
btnAdicionar.on('show',function(){
    auxIns = true;
},this);
btnEliminar.on('show',function(){
    auxDel = true;
},this);;
stGpGestor.on('load',function(){
    if(stGpGestor.getCount() != 0)
        auxDel1 = true;
    else
        auxDel1 = false;
},this);

var vpGestGestor = new Ext.Viewport({
    layout:'fit',
    items:panel
});

////------------- Formulario --------------////
var regGestor = new Ext.FormPanel({
    labelAlign: 'top',
    frame:true,
    bodyStyle:'padding:5px 5px 0',
    items: [{
        layout:'column',
        items:[{
            columnWidth:.55,
            layout:'form',
            margin: '5 5 5 5',
            border:0,
                                                         
            items:[{
                xtype:'fieldset',
                id:'comb',
                height:70,
                anchor:'-10',
                items:[
                new Ext.form.ComboBox({
                    emptyText:perfil.etiquetas.lbMsgSelect,
                    editable:false,
                    fieldLabel:perfil.etiquetas.lbMsgNombreG,
                    store:storeGestor,
                    valueField:'gestor',
                    displayField:'gestorpuerto',
                    hiddenName:'gestor',
                    name:'gestor',
                    forceSelection:true,
                    typeAhead: false,
                    mode: 'local',
                    allowBlank:false,
                    id:'combo',
                    triggerAction: 'all',                        
                    selectOnFocus:false,
                    anchor:'90%',
                    labelAlign:'top'
                })]			
            }]											
        },{
            columnWidth:.45,
            layout:'form',
            margin: '5 5 5 5',
            border:0,
            items:[{
                xtype:'fieldset',
                id:'fsid',
                name:'fsid',
                //height:70,
                hidden:true,
                allowBlank:false,
                items:[{
                    fieldLabel:'Sid',
                    name:'sid',
                    id:'sid',
                    labelWidth : 30,
                    xtype:'textfield',
                    anchor:'100%'
                }]			
            }]				
        }]
    }]		
});
        
Ext.getCmp('combo').on('select',function(combo,record,index ){
			
    idgestor = record[0].data.idgestor;
    if(Ext.getCmp('combo').getValue()=='oracle')
        Ext.getCmp('fsid').show();
    else
        Ext.getCmp('fsid').hide();
})
		
		
////------------- Cargar la Ventana ---------------////
function winForm(opcion){
    switch(opcion){
        case 'Ins':
        {
            if(!winIns){
                winIns = new Ext.Window({
                    modal: true,
                    closeAction:'hide',
                    layout:'fit',
                    title:perfil.etiquetas.lbTitAdicionarG,
                    width:450,
                    height:170,
                    buttons:[
                    {
                        icon:perfil.dirImg+'cancelar.png',
                        iconCls:'btn',
                        text:perfil.etiquetas.lbBtnCancelar,
                        handler:function(){
                            Ext.getCmp('fsid').hide();	
                            winIns.hide();
                        }
                    },{	
                        icon:perfil.dirImg+'aplicar.png',
                        iconCls:'btn',
                        text:perfil.etiquetas.lbBtnAplicar,
                        handler:function(){
                            adicionargestor('apl');
                        }
                    },{	
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
                    auxDel2 = false;
                },this);
                winIns.on('hide',function(){
                    auxIns2 = true;
                    auxDel2 = true;
                    Ext.getCmp('fsid').hide();
                },this);
            }
            storeGestor.load();
            regGestor.getForm().reset();
            winIns.add(regGestor);
            winIns.doLayout();
            winIns.show();		   
        }
        break;
    }
}
       
////------------------- Adicionar Gestor ------------------------////
function adicionargestor(apl){
    if (regGestor.getForm().isValid()){
        if(Ext.getCmp('combo').getValue()=='oracle' && Ext.getCmp('sid').getValue()==''){            
            mostrarMensaje(3,perfil.etiquetas.lbMsgCampVacioSid);
        }else{	
				
            regGestor.getForm().submit({
                url:'insertargestorservidor',
                waitMsg:perfil.etiquetas.lbMsgAdicionandoG,				
                params:{				
                    idservidor:arbolGestor.getSelectionModel().getLastSelected().data.id,
                    //idgestor:sm.getSelected().data.idgestor
                    idgestor:idgestor
                },
                failure: function(form, action){
                    if(action.result.codMsg != 3)
                    { 							
                        if(!apl) {
                            winIns.hide();
                        }
                        if(apl) 
                            storeGestor.load();
                        regGestor.getForm().reset(); 
                        //storeGestor.load({params:{idservidor:arbolGestor.getSelectionModel().getLastSelected().data.id}});	
                        stGpGestor.load();
                        sm.clearSelections();
                        btnEliminar.disable();
                    }				
                  }	
            });
        }		
    }
    else
        mostrarMensaje(3,perfil.etiquetas.lbMsgErrorEnCamops);           
}
 
function eliminargestor(){
    mostrarMensaje(2,perfil.etiquetas.lbMsgEliminarG,elimina);
     function elimina(btnPresionado)
    {
        if (btnPresionado == 'ok')
        {
            Ext.Ajax.request({
               url: 'comprobargestores',
               method:'POST',
                params:{
                    idservidor:arbolGestor.getSelectionModel().getLastSelected().data.id,
                    idgestor:sm.getLastSelected().data.idgestor                
                    },
                callback: function (options,success,response){
                    responseData = Ext.decode(response.responseText);
                    if(responseData.codMsg == 1)
                    {                      
                        stGpGestor.load();
                        sm.clearSelections();
                        btnEliminar.disable();
                    }                    
                }
            });
        }
    }
}
}



