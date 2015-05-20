var perfil = window.parent.UCID.portal.perfil;
perfil.etiquetas = Object();
UCID.portal.cargarEtiquetas('gestnomdesktop', cargarInterfaz);
////------------ Inicializo el singlenton QuickTips ------------////
Ext.QuickTips.init();

////------------ Declarar variables ------------////
var winIns, winMod,winCamb;
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
tipos = /^([a-zA-ZáéíóúÁÉÓÍÚñüÑ]+[a-zA-ZÁÉÓÍÚÁÉÓÍÚáéíóúñüÑ\d\.\-\@\#\_ ]*)+$/;
abreviatura = /^([a-zA-ZáéíóúÁÉÓÍÚñüÑ]+[a-zA-ZáéíóúÁÉÓÍÚñüÑ\d\.\-\@\#\_]*)+$/;

function cargarInterfaz()
{
    ////------------ Botones ------------////
    btnAdicionar = new Ext.Button({
        id:'btnAgrDesktop', 
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
    id:'btnModDesktop', 
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
    id:'btnEliDesktop', 
    hidden:true, 
    icon:perfil.dirImg+'eliminar.png',
    iconCls:'btn', 
    text:perfil.etiquetas.lbBtnEliminar,
    handler:function(){
        eliminarDesktop();
    }
});
/*btnAyuda = new Ext.Button({id:'btnAyuDesktop', hidden:true, icon:perfil.dirImg+'ayuda.png', iconCls:'btn', text:perfil.etiquetas.lbBtnAyuda});*/
UCID.portal.cargarAcciones(window.parent.idFuncionalidad);		
////------------ Store del Grid de Funcionalidades ------------////
stgpDesktop=  new Ext.data.Store({
    fields:				
    [
    {
        name:'iddesktop',
        mapping:'iddesktop'
    },						

    {
        name:'descripcion',
        mapping:'descripcion'
    },						

    {
        name:'denominacion',
        mapping:'denominacion'
    },

    {
        name:'abreviatura',
        mapping:'abreviatura'
    }
    ],                       
    proxy: {
        type: 'ajax',
        url: 'cargarnomdesktop',
        actionMethods:{ //Esta Linea es necesaria para el metodo de llamada POST o GET

            read:'POST'
        },
        reader:{
            totalProperty: "cantidad_filas",
            root: "datos",
            id: "iddesktop"
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
}, this);
////------------ Defino el grid de Funcionalidades ------------////
var gpDesktop = new Ext.grid.GridPanel({
    frame:true,
    header:false,
    region:'center',
    iconCls:'icon-grid',
    autoExpandColumn:'expandir',
    paginate:true,
    store:stgpDesktop,
    selModel:sm,
    columns: [
    {
        hidden: true, 
        hideable: false, 
        dataIndex: 'iddesktop'
    },

    {
        header: perfil.etiquetas.lbTitDenominacion, 
        width:200, 
        dataIndex: 'denominacion', 
        flex:1
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
    }
    ],

    loadMask:{
        store:stgpDesktop
    },
    bbar:new Ext.PagingToolbar({
        pageSize: 15,
        id:'ptbaux',
        store: stgpDesktop,
        displayInfo: true,
        displayMsg: perfil.etiquetas.lbMsgbbarI,
        emptyMsg: perfil.etiquetas.lbMsgbbarII
    })
});
////------------ Trabajo con el PagingToolbar ------------////
Ext.getCmp('ptbaux').on('change',function(){
    sm.select();
},this);

////------------ Panel con los componentes ------------////
var panel = new Ext.Panel({
    layout:'border',
    title:perfil.etiquetas.lbTitPanelTit,
    items:[gpDesktop],
    tbar:[btnAdicionar,btnModificar,btnEliminar/*,btnAyuda*/],
    keys: new Ext.KeyMap(document,[{
        key:Ext.EventObject.DELETE,
        fn: function(){
            if(auxDel && auxDel1 && auxDel2)
                eliminarDesktop();
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
stgpDesktop.on('load',function(){
    if(stgpDesktop.getCount() != 0)
    {
        auxMod1 = true;
        auxDel1 = true;
    }
    else
    {
        auxMod1 = false;
        auxDel1 = false;
    }
},this);

////------------ Formulario ------------////
var regDesktop = new Ext.FormPanel({
    labelAlign: 'top',
    frame:true,
    //hideCollapseTool:true,
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
                maxLength:40, 
                blankText:perfil.etiquetas.lbMsgBlank,
                regex:tipos,
                regexText:perfil.etiquetas.lbMsgregexI,
                anchor:'95%'
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
                maxLength:20, 
                allowBlank:false,
                blankText:perfil.etiquetas.lbMsgBlank,
                regex:abreviatura ,
                regexText:perfil.etiquetas.lbMsgregexI,
                anchor:'95%'
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
function winForm(opcion){
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
                    width:500,
                    height:280,
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
                            adicionarDesktop('apl');
                        }
                    },
                    {	
                        icon:perfil.dirImg+'aceptar.png',
                        iconCls:'btn',
                        text:perfil.etiquetas.lbBtnAceptar,
                        handler:function(){
                            adicionarDesktop();
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
            regDesktop.getForm().reset(); 
            winIns.add(regDesktop);
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
                    width:500,
                    height:280,
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
                            modificarDesktop();
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
            regDesktop.getForm().reset();
            winMod.add(regDesktop);												
            winMod.doLayout();	
            winMod.show();
            regDesktop.getForm().loadRecord(sm.getLastSelected());
            denMod = Ext.getCmp('denominacion').getValue();
            abrevMod = Ext.getCmp('abreviatura').getValue();
            desMod = Ext.getCmp('descripcion').getValue();
        }
        break;
    }
}

////------------ Viewport ------------////
var vpGestDesktop = new Ext.Viewport({
    layout:'fit',
    items:panel
});
stgpDesktop.load({
    params:{
        start:0, 
        limit:10
    }
});	

////------------ Adicionar Desktop ------------////
function adicionarDesktop(apl){
    if (regDesktop.getForm().isValid())
    {
        regDesktop.getForm().submit({
            url:'insertarnomdesktop',
            waitMsg:perfil.etiquetas.lbMsgFunAdicionarMsg,
            failure: function(form, action){
                if(action.result.codMsg != 3)
                {                    
                    regDesktop.getForm().reset(); 
                    stgpDesktop.load();
                    if(!apl) 
                        winIns.hide();

                    sm.clearSelections();
                    btnModificar.disable();
                    btnEliminar.disable();
                }
                //if(action.result.codMsg == 3) mostrarMensaje(action.result.codMsg,action.result.mensaje);
            }
        });
    }
    else
        mostrarMensaje(3,perfil.etiquetas.lbMsgErrorEnCamops);                
}

////------------ Modififcar Desktop ------------////
function modificarDesktop(){
    if (regDesktop.getForm().isValid()){
        var dMod = denMod != Ext.getCmp('denominacion').getValue();
        var aMod = abrevMod != Ext.getCmp('abreviatura').getValue();
        var deMod = desMod != Ext.getCmp('descripcion').getValue(); 
        if(dMod||aMod||deMod){
            regDesktop.getForm().submit({
                url:'modificarnomdesktop',
                waitMsg:perfil.etiquetas.lbMsgFunModificarMsg,
                params:{
                    iddesktop:sm.getLastSelected().data.iddesktop
                    },
                failure: function(form, action){
                    if(action.result.codMsg != 3){                        							
                        winMod.hide();
                        stgpDesktop.load();
                    }
                    //if(action.result.codMsg == 3) mostrarMensaje(action.result.codMsg,action.result.mensaje);						
                }
            });
        }
        else
            mostrarMensaje(3,perfil.etiquetas.NoModify);
    }
    else
        mostrarMensaje(3,perfil.etiquetas.lbMsgErrorEnCamops);                
}

////------------ Eliminar Desktop ------------////
function eliminarDesktop(){
    mostrarMensaje(2,perfil.etiquetas.lbMsgFunEliminarMsgI,elimina);
    function elimina(btnPresionado){
        if (btnPresionado == 'ok')
        {
            Ext.Ajax.request({
                url: 'eliminarnomdesktop',
                method:'POST',
                params:{
                    iddesktop:sm.getLastSelected().data.iddesktop
                    },
                callback: function (options,success,response){
                    responseData = Ext.decode(response.responseText);
                    if(responseData.codMsg == 1)
                    {                       
                        stgpDesktop.load();
                        sm.clearSelections();
                        btnModificar.disable();
                        btnEliminar.disable();
                    }                    
                    if(responseData.codMsg == 3) mostrarMensaje(responseData.codMsg,responseData.mensaje);
                }
            });
        }
    }
}
}