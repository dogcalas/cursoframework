var perfil = window.parent.UCID.portal.perfil;
perfil.etiquetas = Object();
UCID.portal.cargarEtiquetas('gestnomautenticacion', cargarInterfaz);
		
////------------ Inicializo el singlenton QuickTips ------------////
Ext.QuickTips.init();
				
////------------ Declarar Variables ------------////
var winIns, winMod,regAutenticacion;
var auxIns = false;
var auxMod = false;
var auxDel = false;
var auxMod1 = false;
var auxDel1 = false;
var auxIns2 = true;
var auxMod2 = true;
var auxDel2 = true;
var denMod,abrevMod,desMod;
////------------ Area de Validaciones ------------////
var tipos, abreviatura;
tipos = /(^([a-zA-ZáéíóúñÑ])+([a-zA-ZáéíóúñÑ\d\s]*))$/;
abreviatura = /^([a-zA-ZáéíóúñüÑ]+[a-zA-ZáéíóúñüÑ\d]*)+$/;

		
function cargarInterfaz()
{
    ////------------ Botones ------------////
    btnAdicionar = new Ext.Button({
        id:'btnAgrAutentic', 
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
    id:'btnModAutentic', 
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
    id:'btnEliAutentic', 
    hidden:true, 
    icon:perfil.dirImg+'eliminar.png', 
    iconCls:'btn', 
    text:perfil.etiquetas.lbBtnEliminar,
    handler:function(){
        eliminarTAutenticacion();
    }
});
btnActivar  = new Ext.Button({
    id:'btnActivarTAut', 
    hidden:true, 
    disabled:true,
    icon:perfil.dirImg+'activar.png', 
    iconCls:'btn', 
    text:perfil.etiquetas.lbBtnactivarTAut, 
    handler:function(){
        activarTaut();
    }
});
btnDesactivar  = new Ext.Button({
    id:'btnDesctivarTAut', 
    hidden:true, 
    disabled:true,
    icon:perfil.dirImg+'desactivar.png', 
    iconCls:'btn', 
    text:perfil.etiquetas.lbBtndesactivarTAut, 
    handler:function(){
        desactivarTAut();
    }
});
/*btnAyuda = new Ext.Button({id:'btnAyuTema', hidden:true, icon:perfil.dirImg+'ayuda.png', iconCls:'btn', text:perfil.etiquetas.lbBtnAyuda });*/
UCID.portal.cargarAcciones(window.parent.idFuncionalidad);
////------------ Store del Grid de tipos de autenticacion ------------//// 
 var stGpAutenticacion =  new Ext.data.Store({
    fields:					
   [
    {
        name:'idautenticacion'
    },

    {
        name:'denominacion'
    },

    {
        name:'descripcion'
    },

    {
        name:'abreviatura'
    },

    {
        name:'activo'
    },
    ],                
    proxy: {
        type: 'ajax',
        url: 'cargarnomautenticacion',
        actionMethods:{ //Esta Linea es necesaria para el metodo de llamada POST o GET

            read:'POST'
        },
        reader:{
            totalProperty: "cantidad_filas",
            root: "datos",
            id: "idautenticacion"
        }
    }
});
		
////------------ Establesco modo de seleccion de grid (single) ------------////
sm = Ext.create('Ext.selection.RowModel',{
    mode:'SINGLE'
});
sm.on('beforeselect', function (smodel, rowIndex, keepExisting, record){
    btnModificar.enable();
    btnEliminar.enable();
    btnActivar.enable();
    btnDesactivar.enable();

}, this);
////------------ Defino el grid de Tipos de autenticacion------------////
var gpAutenticacion= new Ext.grid.GridPanel({
    frame:true,
    header:false,
    region:'center',
    iconCls:'icon-grid',
    autoExpandColumn:'expandir',
    store:stGpAutenticacion,
    selModel:sm,
    columns: [
    {
        hidden: true, 
        hideable: false,  
        dataIndex: 'idautenticacion'
    },

    {
        flex:1,
        header: perfil.etiquetas.lbTitDenominacion,
        width:200,  
        dataIndex: 'denominacion'
    },

    {
        header: perfil.etiquetas.lbTitAbreviatura,
        width:200, 
        dataIndex: 'abreviatura'
    },	

    {
        header: perfil.etiquetas.lbTitDescripcion, 
        width:200, 
        dataIndex: 'descripcion'
    },

    {
        header: perfil.etiquetas.lbTitActivo, 
        width:200, 
        dataIndex: 'activo'
    }
		
    ],
    loadMask:{
        store:stGpAutenticacion
    },			
    bbar:new Ext.PagingToolbar({
        pageSize: 15,
        id:'ptbaux',
        store: stGpAutenticacion,
        displayInfo: true,
        displayMsg: perfil.etiquetas.lbMsgbbarI,
        emptyMsg: perfil.etiquetas.lbMsgbbarII
    })
});
////------------ Trabajo con el PagingToolbar ------------////
Ext.getCmp('ptbaux').on('change',function(){
    sm.select();
},this);
			
////------------ Panel ------------////
var panel = new Ext.Panel({
    layout:'border',
    title:perfil.etiquetas.lbTitPanelTit,
    //renderTo:'panel',
    items:[gpAutenticacion],
    tbar:[btnAdicionar,btnModificar,btnEliminar,btnActivar,btnDesactivar/*,btnAyuda*/],
    keys: new Ext.KeyMap(document,[{
        key:Ext.EventObject.DELETE,
        fn: function(){
            if(auxDel && auxDel1 && auxDel2)
                eliminarTAutenticacion();
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
        key:"m",
        alt:true,
        fn: function(){
            if(auxMod && auxMod1 && auxMod2)
                winForm('Mod');
        }
    }])
});
stGpAutenticacion.on('load',function(){
    if(stGpAutenticacion.getCount() != 0)
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
////------------ Eventos para hotkeys ------------////
btnAdicionar.on('show',function(){
    auxIns = true;
},this)
btnEliminar.on('show',function(){
    auxDel = true;
},this)
btnModificar.on('show',function(){
    auxMod = true;
},this)
			
////------------ ViewPort ------------////
var vpAutenticacion = new Ext.Viewport({
    layout:'fit',
    items:panel
})
stGpAutenticacion.load({
    params:{
        start:0, 
        limit:15
    }
});
			
////------------ Formulario ------------////
regAutenticacion = new Ext.FormPanel({
    labelAlign: 'top',
    frame:true,
    bodyStyle:'padding:5px 5px 0',
    items: [{
        layout:'column',
        items:[{
            columnWidth:.5,
            layout:'form',
            border:0,
            margin:'5 5 5 5',
            items:[{
                xtype:'textfield',
                labelAlign:'top',
                fieldLabel:perfil.etiquetas.lbFLDenominacion,
                id:'denominacion',
                name:'denominacion',
                allowBlank:false,
                maxLength:100,    
                blankText:perfil.etiquetas.lbMsgBlank,
                regex:tipos,
                regexText:perfil.etiquetas.lbMsgregexI,
                anchor:'100%'
            }]
        },
        {
            columnWidth:.5,
            layout:'form',
            border:0,
            margin:'5 5 5 5',
            items:[{
                xtype:'textfield',
                labelAlign:'top',
                fieldLabel:perfil.etiquetas.lbFLAbreviatura,
                id:'abreviatura',
                name:'abreviatura',
                maxLength:40,    
                allowBlank:false,
                blankText:perfil.etiquetas.lbMsgBlank,
                regex:abreviatura,
                regexText:perfil.etiquetas.lbMsgregexI,
                anchor:'100%'
            }]
        },
        {
            columnWidth:1,
            layout:'form',
            border:0,
            margin:'5 5 5 5',
            items:[{
                xtype:'textarea',
                labelAlign:'top',
                fieldLabel:perfil.etiquetas.lbFLDescripcion,
                id:'descripcion',
                name:'descripcion',
                anchor:'100%'
            }]
        }]
    }]
});	
			
////------------ Cargar la ventana ------------////
function winForm(opcion)
{
    switch(opcion){
        case 'Ins':{
            if(!winIns)
            {
                winIns = new Ext.Window({
                    modal: true,
                    closeAction:'hide',
                    layout:'fit',
                    resizable: false,
                    title:perfil.etiquetas.lbTitVentanaTitI,
                    width:350,
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
                            adicionarautenticion('apl');
                        }
                    },
                    {
                        icon:perfil.dirImg+'aceptar.png',
                        iconCls:'btn',
                        text:perfil.etiquetas.lbBtnAceptar,
                        handler:function(){
                            adicionarautenticion();
                        }
                    }]
                });
                winIns.on('show',function(){
                    auxIns2 = false;
                    auxMod2 = false;
                    auxDel2 = false;
                },this)
                winIns.on('hide',function(){
                    auxIns2 = true;
                    auxMod2 = true;
                    auxDel2 = true;
                },this)
            }
            Ext.getCmp('abreviatura').enable();
            regAutenticacion.getForm().reset(); 
            winIns.add(regAutenticacion);
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
                    resizable: false,
                    title:perfil.etiquetas.lbTitVentanaTitII,
                    width:350,
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
                            modificarTAutenticacion();
                        }
                    }]
                });
                winMod.on('show',function(){
                    auxIns2 = false;
                    auxMod2 = false;
                    auxDel2 = false;
                },this)
                winMod.on('hide',function(){
                    auxIns2 = true;
                    auxMod2 = true;
                    auxDel2 = true;
                },this)
            }
            winMod.add(regAutenticacion);	
            denMod = Ext.getCmp('abreviatura').disable();					
            winMod.doLayout();
            winMod.show();
            regAutenticacion.getForm().loadRecord(sm.getLastSelected());
            denMod = Ext.getCmp('denominacion').getValue();
            abrevMod = Ext.getCmp('abreviatura').getValue();
            desMod = Ext.getCmp('descripcion').getValue();
        }
        break;
    }
}
			
////------------ Adicionar tpo de autenticacion ------------////
function adicionarautenticion(apl)
{
    if (regAutenticacion .getForm().isValid())
    {
        regAutenticacion .getForm().submit({
            url:'insertarautenticacion',
            waitMsg:perfil.etiquetas.lbMsgFunAdicionarMsg,
            failure: function(form, action){
                if(action.result.codMsg != 3)
                {
                    regAutenticacion .getForm().reset(); 
                    stGpAutenticacion.reload();					
                    if(!apl) 
                    winIns.hide();
                    sm.clearSelections();
                    btnModificar.disable();
                    btnEliminar.disable();
                    btnActivar.disable();
                    btnDesactivar.disable();
                }                
            }
        });
    }
    else
        mostrarMensaje(3,perfil.etiquetas.lbMsgErrorEnCamops);                
}
			
////------------ Modififcar TAutenticacion ------------////
function modificarTAutenticacion()
{
    if (regAutenticacion .getForm().isValid())
    {
        var dMod = denMod != Ext.getCmp('denominacion').getValue();
        var aMod = abrevMod != Ext.getCmp('abreviatura').getValue();
        var deMod = desMod != Ext.getCmp('descripcion').getValue(); 
        if(dMod||aMod||deMod){
            regAutenticacion .getForm().submit({
                url:'modificarautenticacion',
                waitMsg:perfil.etiquetas.lbMsgFunModificarMsg,
                params:{
                    idautenticacion:sm.getLastSelected().data.idautenticacion
                    },
                failure: function(form, action){
                    if(action.result.codMsg != 3)
                    {
                        stGpAutenticacion.reload();
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
			
////------------ Eliminar  Tipo de autenticacion ------------////
function eliminarTAutenticacion()
{
    mostrarMensaje(2,perfil.etiquetas.lbMsgFunEliminarMsgI,elimina);
    function elimina(btnPresionado){
        if (btnPresionado == 'ok')
        {
            Ext.Ajax.request({
                url: 'eliminarnomautenticacion',
                method:'POST',
                params:{
                    idautenticacion:sm.getLastSelected().data.idautenticacion
                    },
                callback: function (options,success,response){
                    responseData = Ext.decode(response.responseText);
                    if(responseData.codMsg == 1)
                    {
                        stGpAutenticacion.reload();
                        sm.clearSelections();
                        btnModificar.disable();
                        btnEliminar.disable();
                        btnActivar.disable();
                        btnDesactivar.disable();
                    }                  
                }
            });
        }
    }
}

function activarTaut()
{
    if(sm.getLastSelected().data.activo =="Si")
    {
        mostrarMensaje(3,perfil.etiquetas.lbMsgYaActivados);            			
        return;
    }
    Ext.Ajax.request({
        url: 'ActivarTAut',
        method:'POST',
        params:{
            activar:sm.getLastSelected().data.idautenticacion
            },						
        callback: function (options,success,response){
            responseData = Ext.decode(response.responseText);
            if(responseData.codMsg == 1){							
                stGpAutenticacion.load({
                    params:{
                        start:0,
                        limit:15
                    }
                });							
        }
    }
    });
}

function desactivarTAut()
{
    if(sm.getLastSelected().data.activo =="No")
    {
        mostrarMensaje(3,perfil.etiquetas.lbMsgYaDesactivados);            			
        return;
    }
    Ext.Ajax.request({
        url: 'desctivarTAut',
        method:'POST',
        params:{
            activar:sm.getLastSelected().data.idautenticacion
            },						
        callback: function (options,success,response){
            responseData = Ext.decode(response.responseText);
            if(responseData.codMsg != 3){							
                stGpAutenticacion.load({
                    params:{
                        start:0,
                        limit:15
                    }
                });							
        }
    }
    });			
}
}
		
		
