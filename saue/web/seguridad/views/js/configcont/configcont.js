/*
 *Componente de presentaci�n para la configuraci�n de claves.
 *
 * @package SIGIS
 * @copyright UCID-ERP Cuba
 * @author Oiner Gomez Baryolo    
 * @author Darien Garcia Tejo
 * @author Julio Cesar Garc�a Mosquera  
 * @author Noel Jesus Rivero Pino  
 * @version 1.0-0
 */
var perfil = window.parent.UCID.portal.perfil;
perfil.etiquetas = Object();
UCID.portal.cargarEtiquetas('configcont',cargarInterfaz);
		
////------------ Inicializo el singlenton QuickTips ------------////
Ext.QuickTips.init();
		
////------------ Declarar variables ------------////
var winIns, winMod,regServ;
var auxIns1 = false;
var auxIns = false;
var auxMod = false;
var auxMod2 = false;
var auxIns2 = true;
var cantHistMod,diascaducidadMod,mincaracteresMod,signosMod
////------------ Area de validaciones ------------////
var soloNumeros;
soloNumeros = /^[0-9]+$/;
		
function cargarInterfaz()
{
    ////------------ Botones ------------////
    btnAdicionar = new Ext.Button({
        disabled:false,
        id:'btnAgrClaves', 
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
    id:'btnModClaves', 
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
    fields:					
    [
    {
        name:'idfuncionalidad'
    },

    {
        name:'idmodulo'
    },

    {
        name:'idrestricclaveacceso',
        mapping:'idrestricclaveacceso'
    },
    {
        name:'signos',
        mapping:'signos'
    },

    {
        name:'diascaducidad',
        mapping:'diascaducidad'
    },

    {
        name:'minimocaracteres',
        mapping:'minimocaracteres'
    },

    {
        name:'canthistorico',
        mapping:'canthistorico'
    },

    {
        name:'numerica',
        mapping:'numerica'
    },

    {
        name:'alfabetica',
        mapping:'alfabetica'
    }
    ],                
    proxy: {
        type: 'ajax',
        url: 'cargarclaves',
        actionMethods:{ //Esta Linea es necesaria para el metodo de llamada POST o GET

            read:'POST'
        },
        reader:{
            totalProperty: "cantidad_filas",
            root: "datos",
            id: "idfuncionalidad"
        }
    }
});
			
////------------ Modo de seleccion del grid ------------////
sm = Ext.create('Ext.selection.RowModel',{
    mode:'SINGLE'
});
			
sm.on('beforeselect', function (smodel, rowIndex, keepExisting, record){
    btnModificar.enable();
		
}, this);		
stCongContr.on('load', mostrar);				
			
////------------ Defino el grid de contrase�as ------------////
var gpContr= new Ext.grid.GridPanel({
    frame:true,
    header:false,
    region:'center',
    iconCls:'icon-grid',
    autoExpandColumn:'expandir',
    store:stCongContr,
    selModel:sm,
    columns: [
    {
        hidden: true, 
        hideable: false,  
        dataIndex: 'idrestricclaveacceso'
    },

    {
        header:perfil.etiquetas.lbTitDiasCaducidad,
        width:100,  
        dataIndex: 'diascaducidad', 
        flex:1
    },

    {
        header: perfil.etiquetas.lbTitMinCaracteres,
        width:150, 
        dataIndex: 'minimocaracteres'
    },

    {
        header: perfil.etiquetas.lbFLCantHistorico,
        width:150, 
        dataIndex: 'canthistorico'
    },

    {
        header: perfil.etiquetas.lbTitAlfabetico,
        width:100, 
        dataIndex: 'alfabetica'
    },

    {
        header:perfil.etiquetas.lbTitSignos,
        width:100, 
        dataIndex: 'signos'
    },

    {
        header:perfil.etiquetas.lbTitNumerico,
        width:100, 
        dataIndex: 'numerica'
    }
    ],
    loadMask:{
        store:stCongContr
    },
    bbar:new Ext.PagingToolbar({
        pageSize: 15,
        id:'ptbaux',
        store: stCongContr,
        displayInfo: true,
        displayMsg: perfil.etiquetas.lbMsgbbarI,
        emptyMsg: perfil.etiquetas.lbMsgbbarII
    })
});
   
////------------ Trabajo con el PagingToolbar ------------////
Ext.getCmp('ptbaux').on('change',function(){
    sm.select();
},this)	
                       
////------------ Panel ------------////
var panel = new Ext.Panel({
    layout:'border',
    title:perfil.etiquetas.lbTitPanelTit,
    items:[gpContr],
    tbar:[btnModificar/*,btnAyuda*/],
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
})	
                         
			
////------------ Formulario ------------////			
var regUsuario = new Ext.FormPanel({
    labelAlign: 'top',
    //region:'center',
    frame:true,
    bodyStyle:'padding:5px 5px 0',
    items: [{
        layout:'column',
         style: 'background-color:#eee',
         border:false,
        items:[							
        {
            columnWidth:.5,
            layout: 'form',
            items: [
            new Ext.form.FieldSet({
                title:perfil.etiquetas.lbTitFieldSet,
                width:150,
                autoHeight: true,
                items: [
                {
                    xtype:'checkbox',
                    boxLabel:perfil.etiquetas.lbBLNumerico,
                    id:'numerica',
                    hideLabel:true,
                    checked:true,
                    disabled:true,																
                    name: 'numerica',
                    anchor:'80%'
                },
                {
                    xtype:'checkbox',
                    boxLabel:perfil.etiquetas.lbBLAlfabetico,
                    hideLabel:true,
                    disabled:true,
                    checked:true,
                    name: 'alfabetica',
                    id:'alfabetica',
                    anchor:'80%'
                },
                {
                    xtype:'checkbox',
                    boxLabel:perfil.etiquetas.lbBLSignos,
                    hideLabel:true,
                    name: 'signos',
                    id:'signos',
                    anchor:'80%'
                }]
                })
			
            ]
        },
        {
            columnWidth:.5,
            layout: 'form',
            border:0,
            margin:'5 5 5 5',
            items: [{
                xtype:'textfield',
                 labelAlign:'top',
                fieldLabel:perfil.etiquetas.lbFLMinCaracteres,
                id:'minimocaracteres',
                name: 'minimocaracteres',
                allowBlank:false,
                blankText:perfil.etiquetas.lbMsgBlank,
                regex:soloNumeros,
                regexText:perfil.etiquetas.lbMsgregexI,
                anchor:'95%'
            },
            {
                xtype:'textfield',
                 labelAlign:'top',
                fieldLabel:perfil.etiquetas.lbFLDiasCaducidad,
                id:'diascaducidad',
                name: 'diascaducidad',
                allowBlank:false,
                blankText:perfil.etiquetas.lbMsgBlank,
                regex:soloNumeros,
                regexText:perfil.etiquetas.lbMsgregexI,
                anchor:'95%'
            },
            {
                xtype:'textfield',
                 labelAlign:'top',
                fieldLabel:perfil.etiquetas.lbFLCantHistorico,
                id:'canthistorico',
                name: 'canthistorico',
                allowBlank:false,
                blankText:perfil.etiquetas.lbMsgBlank,
                regex:soloNumeros,
                regexText:perfil.etiquetas.lbMsgregexI,
                anchor:'95%'
            }]
        }]
    }]
});
//Cargar la ventana
function winForm(opcion){
    switch(opcion){
        case 'Mod':{
            if(!winMod){
                winMod= new Ext.Window({
                    modal: true,
                    closeAction:'hide',
                    layout:'fit',
                    title:perfil.etiquetas.lbTitVentanaTitII,
                    resizable:false,
                    width:400,
                    height:230,
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
                            modificarClave();
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
							
            winMod.add(regUsuario);
            winMod.doLayout();
            winMod.show();
            regUsuario.getForm().loadRecord(sm.getLastSelected());
            cantHistMod = Ext.getCmp('canthistorico').getValue();
            diascaducidadMod = Ext.getCmp('diascaducidad').getValue();
            mincaracteresMod = Ext.getCmp('minimocaracteres').getValue();
            signosMod = Ext.getCmp('signos').getValue();
							
        }
        break;
						
    }
}
			
////------------ Adicionar clave ------------////
function adicionarClave(apl){
    if (regUsuario.getForm().isValid() && ValidaC()){
        regUsuario.getForm().submit({
            url:'insertarclave',
            waitMsg:perfil.etiquetas.lbMsgFunAdicionarMsg,
						
            failure: function(form, action){
                if(action.result.codMsg != 3){
                    mostrarMensaje(action.result.codMsg,action.result.mensaje); 
                    regUsuario .getForm().reset(); 					
                    winIns.hide();					
                    stCongContr.load();
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
			
////------------ Modififcar clave ------------////
function modificarClave(){
    if (regUsuario.getForm().isValid() ){
        var chMod = cantHistMod != Ext.getCmp('canthistorico').getValue();
        var dcMod = diascaducidadMod != Ext.getCmp('diascaducidad').getValue();
        var mincMod = mincaracteresMod != Ext.getCmp('minimocaracteres').getValue();
        var sMod = signosMod != Ext.getCmp('signos').getValue(); 
        if(chMod||dcMod||mincMod||sMod){
            regUsuario.getForm().submit({
                url:'modificarclave',
                waitMsg:perfil.etiquetas.lbMsgFunModificarMsg,
                params:{
                    idrestricclaveacceso:sm.getLastSelected().data.idrestricclaveacceso
                    },
                failure: function(form, action){
                    if(action.result.codMsg != 3){                         
                        stCongContr.load();
                        winMod.hide();
                    }
                    if(action.result.codMsg == 3)
                        mostrarMensaje(action.result.codMsg,action.result.mensaje);
							
                }
            });
        }
        else
            mostrarMensaje(3,perfil.etiquetas.NoModify);
    }
    else
        mostrarMensaje(3,perfil.etiquetas.lbMsgErrorEnCamops);
}
////------------ Funcion para validar  los checkbox ------------////
function  ValidaC()
{
    if((regUsuario.getComponent(0).getComponent(0).getComponent(0).getComponent(0).getValue()==false) && (regUsuario.getComponent(0).getComponent(0).getComponent(0).getComponent(1).getValue()==false) && (regUsuario.getComponent(0).getComponent(0).getComponent(0).getComponent(2).getValue()==false))
    {
        mostrarMensaje(3,perfil.etiquetas.lbMsgFunValidaMsg)
        return false;
    }
    else return true;
}
			
////------------ Funcion para validar boton adicionar si existe alguna clave ------------//// 
function mostrar()
{
    if(stCongContr.getCount() != 0)
        btnAdicionar.disable();
    else
    {
        btnAdicionar.enable();
        auxIns = true;
    }
}
}
		
