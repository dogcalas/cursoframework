var perfil = window.parent.UCID.portal.perfil;
perfil.etiquetas = Object();
UCID.portal.cargarEtiquetas('gestexpresiones', cargarInterfaz);

////------------- Inicializo el singlenton QuickTips -------------////
Ext.QuickTips.init();

////------------ Area de Validaciones ------------////
var letras,tipos,expre;
var auxDelete = true;
tipos = /^([a-zA-ZáéíóúñüÑ]+[a-zA-ZáéíóúñüÑ\d\.\-\@\#\_]*)+$/;
expre = /^(\/{1}).+(\/{1})$/;

////------------- Declarar Variables -------------////
var winIns, winMod,regServ;
var auxIns = false;
var auxMod = false;
var auxDel = false;
var auxIns2 = true;
var auxMod2 = true;
var auxDel2 = true;
var auxMod1 = false;
var auxDel1 = false;
var auxBus2 = true;

function cargarInterfaz()
{
    ////------------- Botones -------------////
    btnAdicionar = new Ext.Button({
        id:'btnAgrExpre', 
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
    id:'btnModExpre', 
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
    id:'btnEliExpre', 
    hidden:true, 
    icon:perfil.dirImg+'eliminar.png', 
    iconCls:'btn', 
    text:perfil.etiquetas.lbBtnEliminar,
    handler:function(){
        eliminarExpresion();
    }
});
/*btnAyuda = new Ext.Button({id:'btnAyuExpre', hidden:true, icon:perfil.dirImg+'ayuda.png', iconCls:'btn', text:perfil.etiquetas.lbBtnAyuda });*/
UCID.portal.cargarAcciones(window.parent.idFuncionalidad);
////------------- Store del Grid -------------//// 
stGpExp  =  new Ext.data.Store({
    fields:
    [
    {
        name:'idfuncionalidad'
    },

    {
        name:'idmodulo'
    },

    {
        name:'idexpresiones'
    },

    {
        name:'denominacion'
    },

    {
        name:'descripcion'
    },

    {
        name:'expresion'
    }
    ],
    proxy: {
        type: 'ajax',
        url: 'cargarexpresiones',
        actionMethods:{ //Esta Linea es necesaria para el metodo de llamada POST o GET

            read:'POST'
        },
        reader:{
            totalProperty: "cantidad_filas",
            root: "datos",
            id: "idexpresiones"
        }
    }
});	
////------------- Establesco modo de seleccion de grid (single) -------------////
sm = Ext.create('Ext.selection.RowModel',{
    mode:'SINGLE'
});
sm.on('beforeselect', function (smodel, rowIndex, keepExisting, record){
    btnModificar.enable();
    btnEliminar.enable();
}, this);

sm.on('select', function (smodel, rowIndex, keepExisting, record){
    if(sm.getCount()!=1)
        btnModificar.disable();
    else
        btnModificar.enable();
}, this); 

sm.on('deselect', function (smodel, rowIndex, keepExisting, record){
    if(sm.getCount()!=1)
        btnModificar.disable();
    else
        btnModificar.enable();
}, this);
////------------- Defino el grid de Expresiones -------------////
var gpExp= new Ext.grid.GridPanel({
    frame:true,
    region:'center',
    header:false,
    iconCls:'icon-grid',
    paginate:true,
    autoExpandColumn:'expandir',
    store:stGpExp,
    selModel:sm,
    columns: [
    {
        hidden: true, 
        hideable: false,  
        dataIndex: 'idexpresiones'
    },

    {
        header: perfil.etiquetas.lbTitDenominacion,
        width:300,
        dataIndex: 'denominacion',
        flex:1
    },

    {
        header:perfil.etiquetas.lbTitExpresion,
        width:200, 
        dataIndex: 'expresion'
    },

    {
        id:'expandir', 
        header:perfil.etiquetas.lbTitDescripcion,
        width:210, 
        dataIndex: 'descripcion'
    }
    ],
    loadMask:{
        store:stGpExp
    },
    tbar:[
    // new Ext.Toolbar.TextItem({text:perfil.etiquetas.lbTitDenBuscar}),
    denomExp = new Ext.form.TextField({
        fieldLabel: perfil.etiquetas.lbTitDenBuscar,
        anchor: '95%',
        labelWidth : 80,
        id: 'denomExp',
        regex:/(^([a-zA-Z_])+([a-zA-Z0-9_]*))$/,
        maskRe: /[a-zA-Z0-9_]/i
    }),
    new Ext.menu.Separator(),            
    new Ext.Button({
        icon:perfil.dirImg+'buscar.png',
        iconCls:'btn',
        text:perfil.etiquetas.lbBtnBuscar, 
        handler:function(){
            if(Ext.getCmp('denomExp').isValid())buscarExpresiones(denomExp.getValue());
        }
    })
],			
bbar:new Ext.PagingToolbar({
    pageSize: 15,
    id:'ptbaux',
    store: stGpExp,
    displayInfo: true,
    displayMsg: perfil.etiquetas.lbMsgbbarI,
    emptyMsg: perfil.etiquetas.lbMsgbbarII
})
});
////------------ Trabajo con el PagingToolbar ------------////
Ext.getCmp('ptbaux').on('change',function(){
    sm.select();
},this)			
////------------- Renderiar el arbol -------------////
var panel = new Ext.Panel({
    layout:'border',
    title:perfil.etiquetas.lbTitPanelTit,
    items:[gpExp],
    tbar:[btnAdicionar,btnModificar,btnEliminar/*,btnAyuda*/],
    keys: new Ext.KeyMap(document,[{
        key:Ext.EventObject.DELETE,
        fn: function(){
            if(auxDelete && auxDel && auxDel1 && auxDel2)
                eliminarExpresion();
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
                buscarExpresiones(Ext.getCmp('denomExp').getValue());
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
Ext.getCmp('denomExp').on('focus',function(){
    auxDelete = false;
},this);
Ext.getCmp('denomExp').on('blur',function(){
    auxDelete = true;
},this);
stGpExp.on('load',function(){
    if(stGpExp.getCount() != 0)
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
////------------- Viewport -------------////
var vpServidorAut = new Ext.Viewport({
    layout:'fit',
    items:panel
});
stGpExp.load({
    params:{
        start:0, 
        limit:10
    }
});

////------------- Formulario -------------////
regExp = new Ext.FormPanel({
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
                maxLength:40,                                       
                name:'denominacion',
                allowBlank:false,
                blankText:perfil.etiquetas.lbMsgBlank,
                regexText:perfil.etiquetas.lbMsgregexI,
                regex:tipos,
                anchor:'95%'
            }]
        },
        {
            columnWidth:.5,
            layout: 'form',
            border:0,
            margin:'5 5 5 5',
            items: [{
                xtype:'textfield',
                labelAlign:'top',
                fieldLabel:perfil.etiquetas.lbFLExpresion,
                id:'expresion',
                maxLength:255,                                      
                name:'expresion',
                allowBlank:false,
                blankText:perfil.etiquetas.lbMsgBlank,
                regexText:perfil.etiquetas.lbMsgregexII,
                regex:expre,												
                anchor:'95%'
            }]
        },
        {
            columnWidth:1,
            layout: 'form',
            border:0,
            margin:'5 5 5 5',
            items: [{
                xtype:'textarea',
                labelAlign:'top',
                fieldLabel:perfil.etiquetas.lbFLDescripcion,
                id: 'descripcion',
                name: 'descripcion',
                anchor:'100%'
            }]
        }]
    }]
});
var denominacion,expresion,descripcion;
////------------- Cargar la ventana -------------////
function winForm(opcion){
    switch(opcion){
        case 'Ins':{
            if(!winIns)
            {
                winIns = new Ext.Window({
                    modal: true,
                    closeAction:'hide',
                    layout:'fit',
                    title:perfil.etiquetas.lbTitVentanaTitI,
                    width:450,
                    height:250,
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
                            adicionarExpresiones('apl');
                        }
                    },
                    {
                        icon:perfil.dirImg+'aceptar.png',
                        iconCls:'btn',
                        text:perfil.etiquetas.lbBtnAceptar,
                        handler:function(){
                            adicionarExpresiones();
                        }
                    }]
                });
                winIns.on('show',function(){
                    auxIns2 = false;
                    auxMod2 = false;
                    auxDel2 = false;
                    auxBus2 = false;
                },this)
                winIns.on('hide',function(){
                    auxIns2 = true;
                    auxMod2 = true;
                    auxDel2 = true;
                    auxBus2 = true;
                },this)
            }
            regExp .getForm().reset(); 
            winIns.add(regExp);					
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
                    width:450,
                    height:250,
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
                            modificarExpresion();
                        }
                    }]
                });
                winMod.on('show',function(){
                    auxIns2 = false;
                    auxMod2 = false;
                    auxDel2 = false;
                    auxBus2 = false;
                },this)
                winMod.on('hide',function(){
                    auxIns2 = true;
                    auxMod2 = true;
                    auxDel2 = true;
                    auxBus2 = true;
                },this)
            }
            winMod.add(regExp);
            winMod.doLayout();
            winMod.show();
            regExp.getForm().loadRecord(sm.getLastSelected());
            denominacion=Ext.getCmp('denominacion').getValue();
            expresion=Ext.getCmp('expresion').getValue();
            descripcion=Ext.getCmp('descripcion').getValue();
        }
        break;
    }
}

function buscarExpresiones(denomExp){  
    stGpExp.load({
        params:{
            denominacion:denomExp,
            start:0,
            limit:10
        }
    });
}

////------------- Adicionar Expresiones -------------////
function adicionarExpresiones(apl){
    if (regExp .getForm().isValid())
    {
        regExp .getForm().submit({
            url:'insertarexpresion',
            waitMsg:perfil.etiquetas.lbMsgFunAdicionarMsg,
            failure: function(form, action){
                if(action.result.codMsg != 3)
                {                    
                    regExp .getForm().reset(); 
                    if(!apl) 
                        winIns.hide();
                    stGpExp.load();
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

////------------- Modififcar Expresion -------------////
function modificarExpresion(){
    if (regExp .getForm().isValid())
    {
        var den=Ext.getCmp('denominacion').getValue();
        var exp=Ext.getCmp('expresion').getValue();
        var des=Ext.getCmp('descripcion').getValue();
        if(denominacion!=den||expresion!=exp||descripcion!=des){
            regExp .getForm().submit({
                url:'modificarexpresion',
                waitMsg:perfil.etiquetas.lbMsgFunModificarMsg,
                params:{
                    idexpresiones:sm.getLastSelected().data.idexpresiones
                    },
                failure: function(form, action){
                    if(action.result.codMsg != 3)
                    {                         
                        stGpExp.load();
                        winMod.hide();
                    }
                    if(action.result.codMsg == 3) mostrarMensaje(action.result.codMsg,action.result.mensaje);
                }
            });
        }
        else{
            mostrarMensaje(3,perfil.etiquetas.NoModify);
        }
    }
    else
        mostrarMensaje(3,perfil.etiquetas.lbMsgErrorEnCamops);
}

////------------- Eliminar  Expresion -------------////
function eliminarExpresion(){
    var arrExpElim = sm.getSelection();
    var arrayExpElim = [];
    for (var i=0;i<arrExpElim.length;i++)
        arrayExpElim.push(arrExpElim[i].data.idexpresiones);

    mostrarMensaje(2,perfil.etiquetas.lbMsgFunEliminarMsgI,elimina);
    function elimina(btnPresionado){
        if (btnPresionado == 'ok')
        {
            Ext.Ajax.request({
                url: 'eliminarexpresion',
                method:'POST',
                params:{
                    expresionesElim:Ext.encode(arrayExpElim)
                    },
                callback: function (options,success,response){
                    responseData = Ext.decode(response.responseText);
                    if(responseData.codMsg == 1)
                    {                       
                        stGpExp.load();
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
