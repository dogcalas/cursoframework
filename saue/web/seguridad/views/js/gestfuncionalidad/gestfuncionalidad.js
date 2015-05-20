
var perfil = window.parent.UCID.portal.perfil;
perfil.etiquetas = Object();
UCID.portal.cargarEtiquetas('gestfuncionalidad',cargarInterfaz);

////------------ Inicializo el singlenton QuickTips ------------////
Ext.QuickTips.init();

////------------ Declarar variables ------------////
var winIns, winMod,winCamb, idsistma,tipos,referencia;
var auxIns = false;
var auxMod = false;
var auxDel = false;
var auxIns2 = false;
var auxMod2 = false;
var auxDel2 = false;
var auxBus = false;
var auxDelete = true;
var auxIns3 = true;
var auxMod3 = true;
var auxDel3 = true;
var auxBus3 = true;
////------------ Area de Validaciones ------------////	 
tipos =   /(^([a-zA-ZáéíóúñÑ])+([a-zA-ZáéíóúñÑ\d\.\-\@\#\_\(\)\s]*))$/;
soloNumeros = /^[0-9]+$/;
function cargarInterfaz(){

    ////------------ Botones ------------////
    btnAdicionar = new Ext.Button({
        disabled:true, 
        id:'btnAgrFunc', 
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
    id:'btnModFunc', 
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
    id:'btnEliFunc', 
    hidden:true, 
    icon:perfil.dirImg+'eliminar.png',
    iconCls:'btn', 
    text:perfil.etiquetas.lbBtnEliminar,
    handler:function(){
        eliminarFuncionalidad();
    }
});
btnBuscar = new Ext.Button({
    disabled:true,
    icon:perfil.dirImg+'buscar.png',
    iconCls:'btn',
    text:perfil.etiquetas.lbBtnBuscar, 
    handler:function(){
        buscarfuncionalidad(funcionalidad.getValue());
    }
}),
btnRecargar = new Ext.Button({
    disabled:false,
    icon:perfil.dirImg+'actualizar.png',
    iconCls:'btn',
    text:perfil.etiquetas.lbBtnRecargar,     
    handler:function(){
    sttreefuncion.load();       
    }
})
/*btnAyuda = new Ext.Button({id:'btnAyuFunc', hidden:true, icon:perfil.dirImg+'ayuda.png', iconCls:'btn', text:perfil.etiquetas.lbBtnAyuda });*/
UCID.portal.cargarAcciones(window.parent.idFuncionalidad);
////------------ Arbol Sistemas ------------////
Ext.define('funcion', {
    extend: 'Ext.data.Model',
    fields:['id','abreviatura','descripcion','icono','leaf','text']
});	

var sttreefuncion = Ext.create('Ext.data.TreeStore', {
    model:'funcion',
    idProperty: 'id',

    proxy: {
        type: 'ajax',
        url: 'cargarsistema',
        actionMethods:{ //Esta Linea es necesaria para el metodo de llamada POST o GET

            read:'POST'
        },
        reader: {
            type: 'json'
        }
    }    
});    
arbolFunc = new Ext.tree.TreePanel({
    title:perfil.etiquetas.lbTitArbolSistemas,
    collapsible:true,
    autoScroll:true,
    root:{
        text: perfil.etiquetas.lbRootNodeArbolSubsist,
        expanded:false,
        id:'0'
    },
    store:sttreefuncion,
    region:'west',
    split:true,
    width:'37%',    
    bbar: ['->',btnRecargar],
    listeners:{
        itemclick:function(a,node){
            if(node.data.id == 0)
            {
                stGpFuncionalidades.removeAll();
                btnModificar.disable();
                btnEliminar.disable();
                btnAdicionar.disable();
                auxIns = false;
                auxMod = false;
                auxDel = false;
                auxBus = false;
                btnBuscar.disable();
                gpFuncionalidades.disable();
            }
            else if(node.isLeaf())
            {
                gpFuncionalidades.enable();
                stGpFuncionalidades.removeAll();
                idsistema = node.data.id;
                btnAdicionar.enable();
                stGpFuncionalidades.load({
                    params:{
                        start:0,
                        limit:10
                    }
                });
            btnBuscar.enable();
        }
        else
        {
            idsistema = node.data.id;
            gpFuncionalidades.enable();
            stGpFuncionalidades.load({
                params:{
                    start:0,
                    limit:10
                }
            });
        auxIns = true;
        auxMod = false;
        auxDel = false;
        auxBus = false;
        btnBuscar.disable();
        btnAdicionar.enable();
    }}}});   


////------------ Store del Grid de Funcionalidades ------------////
stGpFuncionalidades =  new Ext.data.Store({
    fields:[
    {
        name:'idfuncionalidad'
    },

    {
        name:'referencia'
    },

    {
        name:'text'
    },

    {
        name:'descripcion'
    },

    {
        name:'icono'
    },

    {
        name:'idsistema'
    },

    {
        name:'index'
    }
    ],
    listeners:{
        beforeload:function(thisstore,objeto){
            objeto.params={}
            objeto.params.idsistema=idsistema			
        }
    },
          pageSize: 10,
proxy: {
    type: 'ajax',
    url: 'cargarfuncionalidades',
    actionMethods:{ //Esta Linea es necesaria para el metodo de llamada POST o GET

        read:'POST'
    },
    reader: {
        type: 'json',
        totalProperty: "cantidad_filas",
        root: "datos",
        id: "idfuncionalidad"
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
    auxDel = true;
    auxMod = true;
    auxBus = true;
}, this);

var paginado=new Ext.PagingToolbar({
        pageSize: 10,
        id:'ptbaux',        
        store: stGpFuncionalidades,
        displayInfo: true,
        displayMsg: perfil.etiquetas.lbMsgPaginado,
        emptyMsg: perfil.etiquetas.lbMsgEmpty
    })
////------------ Defino el grid de Funcionalidades ------------////
var gpFuncionalidades = new Ext.grid.GridPanel({
    frame:true,
    header:false,
    region:'center',
    iconCls:'icon-grid',
    disabled:true,  
    paginate:true,
    autoExpandColumn:'expandir',
    store:stGpFuncionalidades,
    selModel:sm,
    columns: [
    {
        hidden: true, 
        hideable: false, 
        dataIndex: 'idfuncionalidad'
       
    },

    {
        hidden: true, 
        hideable: false, 
        dataIndex: 'idsistema'
    },

    {
        hidden: true, 
        hideable: false, 
        dataIndex: 'descripcion'
    },

    {
        hidden: true, 
        hideable: false, 
        dataIndex: 'index'
    },

    {
        header: perfil.etiquetas.lbCampoDenom, 
        width:150, 
        dataIndex: 'text'
    },

    {
        header: perfil.etiquetas.lbCampoReferencia, 
        width:300, 
        dataIndex: 'referencia', 
        flex:1
    }
    ],
    loadMask:{
        store:stGpFuncionalidades
    },

    tbar:[
    //new Ext.Toolbar.TextItem({text:perfil.etiquetas.lbBuscarFunc}),
    funcionalidad = new Ext.form.TextField({
        width:150, 
        id: 'nombrefuncionalidad'
    }),
    new Ext.menu.Separator(),			
    btnBuscar
    ],
    bbar:paginado
});
////------------ Trabajo con el PagingToolbar ------------////
Ext.getCmp('ptbaux').on('change',function(){
    if(stGpFuncionalidades.count()>0)
        sm.select(0);
},this)

 
////------------ Panel con los componentes ------------////
var panel = new Ext.Panel({
    layout:'border',
    title:perfil.etiquetas.lbTitGestFuncionalidades,
    items:[gpFuncionalidades,arbolFunc],
    tbar:[btnAdicionar,btnModificar,btnEliminar/*,btnAyuda*/],
    keys: new Ext.KeyMap(document,[{
        key:Ext.EventObject.DELETE,
        fn: function(){
            if(auxDel && auxDelete && auxDel2 && auxDel3)
                eliminarFuncionalidad();
        }
    },
    {
        key:"i",
        alt:true,
        fn: function(){
            if(auxIns && auxIns2 && auxIns3)
                winForm('Ins');
        }
    },
    {
        key:"b",
        alt:true,
        fn: function(){
            if(auxBus && auxBus3)
                buscarfuncionalidad(Ext.getCmp('nombrefuncionalidad').getValue());
        }
    },
    {
        key:"m",
        alt:true,
        fn: function(){
            if(auxMod && auxMod2 && auxMod3)
                winForm('Mod');
        }		    			
    }])
});
////---------- Eventos para hotkeys ----------////
btnAdicionar.on('show',function(){
    auxIns2 = true;
},this)
btnEliminar.on('show',function(){
    auxDel2 = true;
},this)
btnModificar.on('show',function(){
    auxMod2 = true;
},this)
Ext.getCmp('nombrefuncionalidad').on('focus',function(){
    auxDelete = false;
},this)
Ext.getCmp('nombrefuncionalidad').on('blur',function(){
    auxDelete = true;
},this)
stGpFuncionalidades.on('load',function(){
    if(stGpFuncionalidades.getCount() != 0)
    {
        auxMod = true;
        auxDel = true;
    }
    else
    {
        auxMod = false;
        auxDel = false;
    }
},this)


////------------ Formulario ------------////
var regFuncionalidad = new Ext.FormPanel({
    labelAlign: 'top',
    frame:true,
    //bodyStyle:'padding:5px 5px 0',
    items: [{
        layout:'column',
        border:0,
        items:[{
            columnWidth:.7,
            layout:'form',
            border:0,
            margin:'5 5 5 5',
            items:[{
                xtype:'textfield',
                labelAlign: 'top',
                fieldLabel:perfil.etiquetas.lbDenominacion,
                id:'text',
                name:'text',
                allowBlank: false,
                blankText:perfil.etiquetas.lbMsgBlankTextDenom,
                regex: tipos,
                maskRe: /[a-z0-9_.\s]/i,
                regexText:perfil.etiquetas.lbMsgExpRegDenom,
                anchor:'95%'
            }]
        },
        {
            columnWidth:.3,
            layout: 'form',
            border:0,
            margin:'5 5 5 5',
            items: [{
                xtype:'textfield',
                labelAlign: 'top',
                fieldLabel: perfil.etiquetas.lbIcono,
                id:'icono',
                regex: tipos,
                maxLength:20,   
                name:'icono',
                regexText:perfil.etiquetas.lbMsgExpRegIcon,
                anchor:'100%'
            }]
        },
        {
            columnWidth:.8,
            layout: 'form',
            border:0,
            margin:'5 5 5 5',
            items: [{
                xtype:'textfield',
                labelAlign: 'top',
                fieldLabel: perfil.etiquetas.lbReferencia,
                id: 'referencia',
                maxLength:255,                                          
                name: 'referencia',      
                allowBlank: false,
                blankText:perfil.etiquetas.lbMsgBlankTextRef,
                regexText:perfil.etiquetas.lbMsgExpRegRef,
                anchor:'95%'
            }]
        },
        {
            columnWidth:.2,
            layout: 'form',
            border:0,
            margin:'5 5 5 5',
            items: [{
                xtype:'textfield',
                labelAlign: 'top',
                fieldLabel: perfil.etiquetas.lbIndex,
                allowBlank: false,
                blankText:perfil.etiquetas.lbMsgBlankTextDenom,
                id:'index',
                name:'index',
                regex:/[a-z0-9_.\s]/i,
                anchor:'100%'
            }]
        },
        {
            columnWidth:1,
            layout: 'form',
            border:0,
            margin:'5 5 5 5',
            items: [{
                xtype:'textarea',
                labelAlign: 'top',
                fieldLabel: perfil.etiquetas.lbDescripcion,
                id: 'descripcion',
                name: 'descripcion',
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
                    title:perfil.etiquetas.lbTitAdicionarFun,
                    width:400,
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
                            adicionarFuncionalidad('apl');
                        }
                    },
                    {	
                        icon:perfil.dirImg+'aceptar.png',
                        iconCls:'btn',
                        text:perfil.etiquetas.lbBtnAceptar,
                        handler:function(){
                            adicionarFuncionalidad();
                        }
                    }]
                });
                winIns.on('show',function(){
                    auxIns3 = false;
                    auxMod3 = false;
                    auxDel3 = false;
                    auxBus3 = false;
                },this)
                winIns.on('show',function(){
                    auxIns3 = false;
                    auxMod3 = false;
                    auxDel3 = false;
                    auxBus3 = false;
                },this)
                winIns.on('hide',function(){
                    auxIns3 = true;
                    auxMod3 = true;
                    auxDel3 = true;
                    auxBus3 = true;
                },this)
            }
            regFuncionalidad.getForm().reset();
            winIns.add(regFuncionalidad);
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
                    title:perfil.etiquetas.lbTitModificarFun,
                    width:400,
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
                            modificarFuncionalidad();
                        }
                    }]
                });
                winMod.on('show',function(){
                    auxIns3 = false;
                    auxMod3 = false;
                    auxDel3 = false;
                    auxBus3 = false;
                },this)
                winMod.on('hide',function(){
                    auxIns3 = true;
                    auxMod3 = true;
                    auxDel3 = true;
                    auxBus3 = true;
                },this)
            }

            winMod.add(regFuncionalidad);
            winMod.doLayout();									
            winMod.show();
            regFuncionalidad.getForm().loadRecord(sm.getLastSelected());
        }
        break;
    }
}

////------------ Viewport ------------////
var vpGestFuncionalidad = new Ext.Viewport({
    layout:'fit',
    items:panel
})




////------------ Adicionar Funcionalidades ------------////
function adicionarFuncionalidad(apl){

    if (regFuncionalidad.getForm().isValid())
    {

        var ids = arbolFunc.getSelectionModel().getLastSelected().data.id;	
        //console.info(arbolFunc.getSelectionModel().getSelectedNode());
        var node = arbolFunc.getSelectionModel().getLastSelected();
        var arraytext = [];
        while(ids != 0)
        {
            arraytext.push(node.data.text);
            ids = parseInt(node.parentNode.data.id);
            node = node.parentNode;
        }	
        regFuncionalidad.getForm().submit({
            url:'insertarfuncionalidad',
            waitMsg:perfil.etiquetas.lbMsgEsperaRegFun,
            params:{
                idsistema:arbolFunc.getSelectionModel().getLastSelected().data.id,
                idnodopadre:arbolFunc.getSelectionModel().getLastSelected().parentNode.data.id
                ,
                arreglosistemas:Ext.encode(arraytext)
                },
            failure: function(form, action){
                if(action.result.codMsg != 3)
                {                   
                    regFuncionalidad.getForm().reset(); 
                    if(!apl) 
                        winIns.hide();
                    stGpFuncionalidades.load();
                    sm.clearSelections();
                    btnModificar.disable();
                    btnEliminar.disable();
                }                
            }
        });
    }
    else
        mostrarMensaje(3,perfil.etiquetas.lbMsgErrorEnCamops);                
}

////------------ Modififcar Funcionalidad ------------////
function modificarFuncionalidad(){
    if (regFuncionalidad.getForm().isValid()){
        regFuncionalidad.getForm().submit({
            url:'modificarfuncionalidad',
            waitMsg:perfil.etiquetas.lbMsgEsperaModFun,
            params:{
                idfuncionalidad:sm.getLastSelected().data.idfuncionalidad,
                idsistema:sm.getLastSelected().data.idsistema
                },
            failure: function(form, action){
                if(action.result.codMsg != 3){                   
                    stGpFuncionalidades.load();
                    winMod.hide();
                }                
            }
        });
    }
    else
        mostrarMensaje(3,perfil.etiquetas.lbMsgErrorEnCamops);                
}

////------------ Eliminar Funcionalidad ------------////
function eliminarFuncionalidad(){
    mostrarMensaje(2,perfil.etiquetas.lbMsgDeseaEliminar,elimina);
    function elimina(btnPresionado){
        if (btnPresionado == 'ok')
        {
            Ext.Ajax.request({
                url: 'eliminarfuncionalidad',
                method:'POST',
                params:{
                    idfuncionalidad:sm.getLastSelected().data.idfuncionalidad
                    },
                callback: function (options,success,response){
                    responseData = Ext.decode(response.responseText);
                    if(responseData.codMsg == 1)
                    {                       
                        stGpFuncionalidades.load();
                        sm.clearSelections();
                        btnModificar.disable();
                        btnEliminar.disable();
                    }                    
                }
            });
        }
    }
}

////------------ Buscar Funcionalidad ------------////
function buscarfuncionalidad(funcionalidad){  
    stGpFuncionalidades.load({
        params:{
            denominacion:funcionalidad,
            idsistema:arbolFunc.getSelectionModel().getLastSelected().data.id,
            start:0,
            limit:10
        }
    });
}
}