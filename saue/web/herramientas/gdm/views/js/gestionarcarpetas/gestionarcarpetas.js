var perfil = window.parent.UCID.portal.perfil;
UCID.portal.cargarEtiquetas('gestionarcarpetas', function(){
    cargarInterfaz();
});

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
var denRF , descRF , abrevRF;
////------------ Area de Validaciones ------------////
tipos =   /^([a-zA-Z0-9·ÈÌÛ˙Ò¸—¡…Õ”⁄]+ ?[a-zA-Z0-9·ÈÌÛ˙Ò¸—¡…Õ”⁄_]*)+$/;
soloNumeros = /^[0-9]+$/;
////------------ Area de Expresiones para validaciones ------------////
var deschekear=0, cambiar = true, modificar = 0;
//tipos = /(^([a-zA-Z·ÈÌÛ˙Ò—])+([a-zA-Z·ÈÌÛ˙Ò—\d\.\-\@\#\_\s]*))$/;
esDirIp =  /(^(2([0-4][0-9])|2(5[0-5]))|^([0-1]?[0-9]?[0-9]))\.(((2([0-4][0-9])|2(5[0-5]))|([0-1]?[0-9]?[0-9]))\.){2}((2([0-4][0-9])|2(5[0-5]))|([0-1]?[0-9]?[0-9]))$/ ;

////------------ Funcion para cargar la interfaz ------------////
function cargarInterfaz(){
    ////------------ Botones principales ------------////
    btnAdicionar = new Ext.Button({
        id:'btnAgrSist',
        icon:perfil.dirImg+'adicionar.png',
        iconCls:'btn',
        iconCls:'btn',
        disabled:true,
        text:'Adicionar',
        handler:function(){
            winForm('Ins');
        }
    });
    btnModificar = new Ext.Button({
        disabled:true,
        id:'btnModSist',
        icon:perfil.dirImg+'modificar.png',
        iconCls:'btn',
        text:'Modificar',
        handler:function(){
            winForm('Mod');
        }
    });
    btnEliminar = new Ext.Button({
        disabled:true,
        id:'btnEliSist',
        icon:perfil.dirImg+'eliminar.png',
        iconCls:'btn',
        text:'Eliminar',
        handler:function(){
            eliminarCmp();
        }
    });
    btnAddRQ = new Ext.Button({
        disabled:true,
        id:'btnrq',
        icon:perfil.dirImg+'info.png',
        iconCls:'btn',
        text:'Gestionar requisitos',
        handler:function(){
            showWinGestionarRQ(denCmp,path,true);
        }
    });
    btnAddFunc = new Ext.Button({
        disabled:true,
        id:'btnfn',
        icon:perfil.dirImg+'gestfuncionalidades.png',
        iconCls:'btn',
        text:'Gestionar funcionalidad',
        handler:function(){
            showWinGestionarRQ(denCmp,path,false);
        }
    });
    btnAutoCreate = new Ext.Button({
        disabled:false,
        id:'btnac',
        icon:perfil.dirImg+'adicionar.png',
        iconCls:'btn',
        text:'Autoregistrar m&oacute;dulos',
        handler:function(){
            autoregistrarModulos()
        }
    });

    //UCID.portal.cargarAcciones(window.parent.idFuncionalidad);


    ////------------ Arbol de sistemas ------------////
    arbolSistema = new Ext.tree.TreePanel({
        title:'Sistemas',
        tbar:[btnAdicionar, btnModificar, btnEliminar,'-',btnAddFunc, '-',btnAutoCreate],
        enableDD:true,
        autoScroll:true,
        region:'west',
        //animate:false,
        width:150,
        margins:'2 2 2 2',
        loader: new Ext.tree.TreeLoader({
            dataUrl:'cargarsistema',
            listeners:{
                'beforeload':function(atreeloader, anode){
                    atreeloader.baseParams = {};
                    if(anode.attributes.id != 0)
                    {
                        atreeloader.baseParams.id = anode.attributes.id;
                        atreeloader.baseParams.text = anode.attributes.text;
                        atreeloader.baseParams.leaf = anode.attributes.leaf;
                        atreeloader.baseParams.path = anode.attributes.path;
                        atreeloader.baseParams.component = anode.attributes.component;

                    }

                }
            }
        })
    });

    ////------------ Crear nodo padre del arbol ------------////
    padreSistema = new Ext.tree.AsyncTreeNode({
        text: 'Subsistemas',
        animate:false,
        draggable:false,
        expandable:false,
        expanded:true,
        id:'0'
    });

    ////------------ Crear lista de hijos ------------////
    arbolSistema.setRootNode(padreSistema);

    ////------------ Evento para habilitar botones ------------////
    /*	arbolSistema.on('click', function (node, e){
            sistemaseleccionado = node.id;
            sistemas =  node;
            bandera =0;
			if(node.id!=0){
	           if(node.attributes.leaf)
			     btnAdicionar.disable();
				 else
				 btnAdicionar.enable();
              }
			else {
				btnAdicionar.enable();
			}
		}, this);*/


    arbolSistema.on('click', function (node, e){
        sistemaseleccionado = node.id;
        sistemas =  node;
        denCmp = node.attributes.text;
        path = node.attributes.path;
        bandera =0;
        if(node.id!=0){
            btnEliminar.enable();
            btnModificar.enable();
            btnAdicionar.enable();
            btnAddRQ.disable();
            btnAddFunc.disable();
            if(node.attributes.component){
                btnAdicionar.disable();
                btnAddRQ.enable();
                btnAddFunc.enable();
            }
        }else{
            btnEliminar.disable();
            btnModificar.disable();
            btnAddRQ.disable();
            btnAddFunc.disable();
            btnAdicionar.enable();
        }

    }, this);

    var vpGestSistema = new Ext.Viewport({
        layout:'fit',
        items:arbolSistema
    })




    ////------------ Formulario ------------////
    var regFuncionalidad = new Ext.FormPanel({
        labelAlign: 'top',
        border:false,
        margin: '2 2 2 2',
        cmargin: '2 2 2 2',
        frame:true,
        bodyStyle:'padding:5px 5px 0',
        items: [{
            xtype:'textfield',
            fieldLabel:"Denominaci&oacute;n",
            id:'text',
            allowBlank: false,
            blankText:"Este campo es obligatorio",
            maskRe:/[a-z0-9A-Z\s]/i,
            regex: /[a-z0-9A-Z\s]/i,
            //regexText:"No deben existir numeros en la denominacion",
            anchor:'95%'
        },{
            xtype:'textfield',
            fieldLabel:'Abreviatura',
            id:'tfAbrevCmp',
            allowBlank: false,
            maskRe:/^([a-zA-Z0-9·ÈÌÛ˙Ò¸—¡…Õ”⁄]+ ?[a-zA-Z0-9·ÈÌÛ˙Ò¸—¡…Õ”⁄]*)+$/,
            regex: /^([a-zA-Z·ÈÌÛ˙Ò¸—¡…Õ”⁄ _.-]+)([a-zA-Z0-9·ÈÌÛ˙Ò¸—¡…Õ”⁄ _.-]*)$/,
            name:'abrev',
            anchor:'95%'
        },{
            xtype:'checkbox',
            id:'componente',
            checkboxToggle:true,
            boxLabel:'M&oacute;dulo',
            hideLabel:'true'
        }
        /*{
				layout:'column',
				items:[{
					columnWidth:.8,
					layout:'form',
					items:[{
						xtype:'textfield',
						fieldLabel:"Denominaci&oacute;n",
						id:'text',
						allowBlank: false,
						blankText:"No se debe dejar este campo en blanco",
						maskRe:/^([a-zA-Z]+ ?[a-zA-Z_0-9 ]*)+$/, //esta reg
            			regex: /^([a-zA-Z]+ ?[a-zA-Z_ ]*)+$/,
            			//regexText:"No deben existir numeros en la denominacion",
						anchor:'95%'
						}]
				   },{
						xtype:'textfield',
						fieldLabel:'Abreviatura',
						id:'tfAbrevCmp',
						allowBlank: true,
						maskRe:/^([a-zA-Z]+ ?[a-zA-Z]*)+$/,
						regex: /^([a-zA-Z]+ ?[a-zA-Z]*)+$/,
						name:'abrev',
					    anchor:'100%'

					},{

				        columnWidth:.4,
						layout: 'form',
						items: [{
							xtype:'checkbox',
							id:'componente',
							checkboxToggle:true,
							boxLabel:'Componente',
							hideLabel:'true'
						}]


							}]
					}*/]
    });


    function winForm(opcion){
        if(!winIns){
            winIns = new Ext.Window({
                modal: true,
                closeAction:'hide',
                layout:'fit',
                border:false,
                title:perfil.etiquetas.lbTitAdicionarFun,
                width:270,
                height:210,
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
                    id:'btnaplicar',
                    text:perfil.etiquetas.lbBtnAplicar

                },

                {
                    icon:perfil.dirImg+'aceptar.png',
                    iconCls:'btn',
                    text:perfil.etiquetas.lbBtnAceptar,
                    id:'btnaceptar'

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
        switch(opcion){
            case 'Ins':{

                Ext.getCmp('componente').show();
                regFuncionalidad.getForm().reset();
                winIns.setTitle('Adicionar ');
                winIns.add(regFuncionalidad);
                winIns.doLayout();
                winIns.show();
                Ext.getCmp('btnaplicar').setHandler(function(){
                    adicionarFuncionalidad(true);
                });
                Ext.getCmp('btnaceptar').setHandler(function(){
                    adicionarFuncionalidad();
                });
            }
            break;
            case 'Mod':{
                regFuncionalidad.getForm().reset();
                Ext.getCmp('text').setValue(arbolSistema.getSelectionModel().getSelectedNode().attributes.text);
                Ext.getCmp('tfAbrevCmp').setValue(arbolSistema.getSelectionModel().getSelectedNode().attributes.abrev);
                Ext.getCmp('componente').hide();
                winIns.setTitle('Modificar ');
                winIns.add(regFuncionalidad);
                winIns.doLayout();
                winIns.show();
                Ext.getCmp('btnaplicar').setHandler(function(){
                    modificarCmp(true);
                });
                Ext.getCmp('btnaceptar').setHandler(function(){
                    modificarCmp();
                });

            }
            break;
        }
    }

    function adicionarFuncionalidad(apl){


        if (regFuncionalidad.getForm().isValid())
        {
            var myMask = new Ext.LoadMask(Ext.getBody(), {
                msg:"Espere por favor..."
            });
            myMask.show();
            var nodopadre=0;
            var ids = arbolSistema.getSelectionModel().getSelectedNode().id;
            var node = arbolSistema.getSelectionModel().getSelectedNode();
            var arraytext = [];
            while(ids != 0)
            {
                arraytext.push(node.attributes.text);
                ids = parseInt(node.parentNode.attributes.id);
                node = node.parentNode;
            }
            if(parseInt(node.attributes.id)!=0)
                nodopadre = parseInt(node.parentNode.attributes.id);
            regFuncionalidad.getForm().submit({
                url:'adicionarComponentePaquete',
                waitMsg:perfil.etiquetas.lbMsgEsperaRegFun,
                params:{
                    nombre:arbolSistema.getSelectionModel().getSelectedNode().attributes.text,
                    node:arbolSistema.getSelectionModel().getSelectedNode().attributes.id,
                    path:arbolSistema.getSelectionModel().getSelectedNode().attributes.path,
                    patharray:Ext.encode(arraytext),
                    component:arbolSistema.getSelectionModel().getSelectedNode().attributes.component
                },
                failure: function(form, action){
                    myMask.hide();
                    if(action.result.codMsg != 3)
                    {
                        mostrarMensaje(action.result.codMsg,action.result.mensaje);
                        regFuncionalidad.getForm().reset();
                        if(!apl)
                            winIns.hide();
                        arbolSistema.getSelectionModel().getSelectedNode().reload();
                    }
                    if(action.result.codMsg == 3) mostrarMensaje(action.result.codMsg,action.result.mensaje);
                }
            });
        } else  mostrarMensaje(3,'Existen campos incorrectos.');
    }

    function modificarCmp(apl){
        var text_nodo = arbolSistema.getSelectionModel().getSelectedNode().attributes.text;
        var text_mod = Ext.getCmp('text').getValue();
        var abrev_nodo = arbolSistema.getSelectionModel().getSelectedNode().attributes.abrev;
        var abrev_mod = Ext.getCmp('tfAbrevCmp').getValue();

        if(text_nodo != text_mod || abrev_nodo != abrev_mod){
            if (regFuncionalidad.getForm().isValid())
            {
                var myMask = new Ext.LoadMask(Ext.getBody(), {
                    msg:"Espere por favor..."
                });
                myMask.show();
                var nodopadre=0;
                var ids = arbolSistema.getSelectionModel().getSelectedNode().id;
                var node = arbolSistema.getSelectionModel().getSelectedNode();
                var arraytext = [];

                while(ids != 0)
                {
                    arraytext.push(node.attributes.text);
                    ids = parseInt(node.parentNode.attributes.id);
                    node = node.parentNode;
                }
                if(parseInt(node.attributes.id)!=0)
                    nodopadre = parseInt(node.parentNode.attributes.id);
                regFuncionalidad.getForm().submit({
                    url:'modificarComponentePaquete',
                    waitMsg:perfil.etiquetas.lbMsgEsperaRegFun,
                    params:{
                        text_actual:arbolSistema.getSelectionModel().getSelectedNode().attributes.text,
                        abrev_actual:arbolSistema.getSelectionModel().getSelectedNode().attributes.abrev,
                        node:arbolSistema.getSelectionModel().getSelectedNode().attributes.id,
                        path:arbolSistema.getSelectionModel().getSelectedNode().attributes.path,
                        patharray:Ext.encode(arraytext),
                        component:arbolSistema.getSelectionModel().getSelectedNode().attributes.component
                    },
                    failure: function(form, action){
                        myMask.hide();
                        if(action.result.codMsg != 3)
                        {
                            regFuncionalidad.getForm().reset();
                            if(!apl)
                                winIns.hide();
                            arbolSistema.getSelectionModel().getSelectedNode().parentNode.reload();
                            btnAdicionar.disable();
                            mostrarMensaje(action.result.codMsg,action.result.mensaje);

                        }
                        if(action.result.codMsg == 3){
                            mostrarMensaje(action.result.codMsg,result.mensaje);
                        }
                    }
                });
            }
            else
                mostrarMensaje(3,perfil.etiquetas.lbMsgErrorEnCamops);
        }else
            mostrarMensaje(3,'Usted no ha realizado modificaci&oacute;n.');

    }
    function autoregistrarModulos(){
        var myMask = new Ext.LoadMask(Ext.getBody(), {
            msg:"Espere por favor..."
        });
        myMask.show();
        Ext.Ajax.request({
            url:'autoregistarModulos',
            failure:function(){ myMask.hide();},
            callback: function (options,success,response)
            {
                myMask.hide();
                var result = Ext.decode(response.responseText);
                if(result.codMsg != 3)
                {
                    mostrarMensaje(result.codMsg,result.mensaje);
                    arbolSistema.getRootNode().reload();
                    btnAdicionar.disable();
                }
                if(result.codMsg == 3){
                    mostrarMensaje(result.codMsg,result.mensaje);
                }

            }
        });
    }

    function eliminarCmp(){

        var ids = arbolSistema.getSelectionModel().getSelectedNode().id;
        var node = arbolSistema.getSelectionModel().getSelectedNode();
        var arraytext = [];
        while(ids != 0)
        {
            arraytext.push(node.attributes.text);
            ids = parseInt(node.parentNode.attributes.id);
            node = node.parentNode;
        }
        mostrarMensaje(2,'&iquest;Est&aacute; seguro que desea eliminar?',function(btnoprimido)
        {
            if(btnoprimido == 'ok')
            {
                var myMask = new Ext.LoadMask(Ext.getBody(), {
                    msg:"Cargando..."
                });
                myMask.show();
                Ext.Ajax.request({
                    url:'eliminar',
                    params:{
                        nombre:arbolSistema.getSelectionModel().getSelectedNode().attributes.text,
                        node:arbolSistema.getSelectionModel().getSelectedNode().attributes.id,
                        path:arbolSistema.getSelectionModel().getSelectedNode().attributes.path,
                        patharray:Ext.encode(arraytext),
                        component:arbolSistema.getSelectionModel().getSelectedNode().attributes.component
                    },
                    callback: function (options,success,response)
                    {
                        var result = Ext.decode(response.responseText);
                        myMask.hide();
                        if(result.codMsg != 3)
                        {

                            mostrarMensaje(result.codMsg,result.mensaje);
                            arbolSistema.getSelectionModel().getSelectedNode().parentNode.reload();
                            btnAdicionar.disable();
                        }
                        if(result.codMsg == 3){
                            mostrarMensaje(result.codMsg,result.mensaje);
                        }

                    }
                });
            }
        });
    }

    function showWinGestionarRQ(denCMP,path,isrq){
        var btnAdicionarRQ = new Ext.Button({
            text:'Adicionar',
            icon:perfil.dirImg+'adicionar.png',
            iconCls:'btn',
            id: " btnAdicionarRQ",
            handler:function(){
                CrearVentanaGestionRQ(gpGestionarRQ,null,isrq);
            }
        });
        var btnModificarRQ = new Ext.Button({
            text:'Modificar',
            icon:perfil.dirImg+'modificar.png',
            iconCls:'btn',
            id:"btnModificarRQ",
            disabled:true,
            handler:function(){
                CrearVentanaGestionRQ(gpGestionarRQ,true,isrq);
            }
        });
        var btnEliminarRQ = new Ext.Button({
            handler:function(){
                eliminarElemento(gpGestionarRQ,isrq);
            },
            text:'Eliminar',
            icon:perfil.dirImg+'eliminar.png',
            iconCls:'btn',
            id:"btnEliminarRQ",
            disabled:true
        });

        var xg = Ext.grid;
        var sm = new xg.CheckboxSelectionModel({
            listeners:{
                rowselect:function(sm,index,record){
                    btnAdicionarRQ.disable();
                    btnEliminarRQ.enable();
                    btnModificarRQ.enable();

                    if(sm.getSelections().length > 1 )
                        btnModificarRQ.disable();
                },
                rowdeselect:function(sm,index,record){
                    if(sm.getSelections().length == 1)
                        btnModificarRQ.enable();
                    else {
                        if(sm.getSelections().length == 0){
                            btnAdicionarRQ.enable();
                            btnEliminarRQ.disable();
                            btnModificarRQ.disable();
                            gpGestionarRQ.getView().refresh(true);
                        }
                    }
                }
            }
        });
        var store = new Ext.data.JsonStore({
            url: 'cargarRQFN',
            baseParams:{
                path:path,
                den:denCMP,
                isrq:(isrq)?1:0	,
                idnodo:arbolSistema.getSelectionModel().getSelectedNode().attributes.id
            },
            root: 'datos',
            fields: ['id', 'den','desc','fecha','abrev' ]
        });
        expander = new xg.RowExpander({
            tpl : new Ext.Template(
                '<div class="search-item padding-expander">',
                '<p><b>Abreviatura:</b> {abrev}</p><br>',
                '<p><b>Descripci&oacute;n:</b> {desc}</p><br>',
                '</div>'
                )
        });
        var gpGestionarRQ = new xg.GridPanel({
            loadMask:new Ext.LoadMask(Ext.getBody(), {
                msg:"Cargando..."
            }),
            plugins: expander,
            store: store,
            cm: new xg.ColumnModel([
                sm,
                {
                    id:'den',
                    header: "Denominaci&oacute;n",
                    width: 200,
                    sortable: true,
                    dataIndex: 'den'
                },

                {
                    header: "Abreviatura",
                    width: 100,
                    sortable: true,
                    dataIndex: 'abrev'
                },

                {
                    header: "&Uacute;ltima modificaci&oacute;n",
                    width: 100,
                    sortable: true,
                    dataIndex: 'fecha'
                },
                expander]),
            sm: sm,
            frame:true,
            viewConfig: {
                forceFit: true
            }
        });

        var winGestionarRG = new Ext.Window({
            modal: true,
            closeAction:'close',
            layout:'fit',
            border:false,
            title:(isrq)?'Gestionar requisitos del mÛdulo "'+denCMP+'"': 'Gestionar funcionalidades del mÛdulo "'+denCMP+'"',
            width:705,
            height:405,
            items:[gpGestionarRQ],
            tbar:[btnAdicionarRQ, btnModificarRQ,btnEliminarRQ],
            buttons:[{
                icon:perfil.dirImg+'cancelar.png',
                iconCls:'btn',
                text:'Cerrar',
                handler:function(){
                    winGestionarRG.close();
                }
            }]
        });
        winGestionarRG.show();
        store.reload();
    }

    function CrearVentanaGestionRQ(gp,accion,isrq){

        var fpRQ = new Ext.form.FormPanel({
            labelAlign: 'top',
            border:false,
            margin: '2 2 2 2',
            cmargin: '2 2 2 2',
            frame:true,
            bodyStyle:'padding:5px 5px 0',
            items: [{
                xtype:'panel',
                layout:'column',
                items:[{
                    columnWidth:.70,
                    layout:'form',
                    items:[{
                        xtype:'textfield',
                        fieldLabel:"Denominaci&oacute;n",
                        id:'tfden',
                        allowBlank: false,
                        blankText:"Este campo es obligatorio",
						maskRe:/[a-z0-9A-Z\s]/i,
						regex: /[a-z0-9A-Z\s]/i,
                        name:'den',
                        anchor:(isrq)?'100%':'95%'
                    }]
                },{
                    columnWidth:.30,
                    layout:'form',
                    items:[{
                        xtype:'textfield',
                        fieldLabel:'Abreviatura',
                        hideLabel :(isrq)?true:false,
                        id:'tfAbrev',
                        allowBlank: true,
                        maskRe:/^([a-z0-9·ÈÌÛ˙Ò¸—¡…Õ”⁄]+ ?[a-z0-9·ÈÌÛ˙Ò¸—¡…Õ”⁄]*)+$/,
                        regex: /^([a-z0-9·ÈÌÛ˙Ò¸—¡…Õ”⁄]+ ?[a-z0-9·ÈÌÛ˙Ò¸—¡…Õ”⁄]*)+$/,
                        name:'abrev',
                        anchor:'100%',
                        hidden:(isrq)?true:false
                    }]
                }]
            },{
                xtype:'htmleditor',
                fieldLabel: 'Descripci&oacute;n',
                id:'htmldesc',
                name: 'desc',
                width: 540,
                height:120,
                listeners:{
                    editmodechange:function(editor){
                        editor.syncValue();
                    }
                }
            }]
        });

        var winRQ = new Ext.Window({
            id:'winRQ',
            modal: true,
            closeAction:'close',
            layout:'fit',
            border:false,
            width:575,
            height:280,
            title:isrq?'Adicionar requisito':'Adicionar funcionalidades',
            items:[fpRQ],
            buttons:[{
                icon:perfil.dirImg+'cancelar.png',
                iconCls:'btn',
                text:perfil.etiquetas.lbBtnCancelar,
                handler:function(){
                    winRQ.close();
                }
            },{
                icon:perfil.dirImg+'aplicar.png',
                iconCls:'btn',
                id:'btnaplicar',
                hidden:(accion)?true:false,
                text:perfil.etiquetas.lbBtnAplicar,
                handler:function(){
                    gestRQ(gp,fpRQ,accion,true,isrq);
                }
            },{
                icon:perfil.dirImg+'aceptar.png',
                iconCls:'btn',
                text:perfil.etiquetas.lbBtnAceptar,
                id:'btnaceptar',
                handler:function(){
                    gestRQ(gp,fpRQ,accion,false,isrq);
                }
            }]
        });
        winRQ.show();
        if(accion){
            var pathtitle = isrq?' requisito':' funcionalidades';
            winRQ.setTitle('Modificar'+pathtitle);
            denRF = gp.getSelectionModel().getSelected().data.den;
            descRF = gp.getSelectionModel().getSelected().data.desc;
            abrevRF = gp.getSelectionModel().getSelected().data.abrev;
            Ext.getCmp('tfden').setValue(denRF);
            Ext.getCmp('htmldesc').setValue(descRF);
            Ext.getCmp('tfAbrev').setValue(abrevRF);

        }
    }

    function gestRQ(gp,fp,accion,apl,isrq){
        var url = 'adicionarRQFN';
        var denid_req = '';
        var params = {
            nombre:arbolSistema.getSelectionModel().getSelectedNode().attributes.text,
            node:arbolSistema.getSelectionModel().getSelectedNode().attributes.id,
            path:arbolSistema.getSelectionModel().getSelectedNode().attributes.path,
            fecha:new Date().format('d/m/Y H:i:s'),
            isrq:(isrq)?1:0
        };
        if(accion){
            url = 'modificarRQFN';
            var record_Selected = gp.getSelectionModel().getSelected();
            params.idRQ = record_Selected.data.id;


        }
        if(  denRF  != Ext.getCmp('tfden').getValue() ||
            descRF != Ext.getCmp('htmldesc').getValue() ||
            abrevRF != Ext.getCmp('tfAbrev').getValue()
            ){
            fp.getForm().submit({
                url:url,
                waitMsg:perfil.etiquetas.lbMsgEsperaRegFun,
                params:params,
                failure: function(form, action){
                    if(action.result.codMsg != 3)
                    {
                        if(!apl) {
                            Ext.getCmp('winRQ').close();
                        }
                        fp.getForm().reset();
                        gp.getSelectionModel().clearSelections();
                        gp.getStore().reload();
                        mostrarMensaje(action.result.codMsg,action.result.mensaje);
                        if(record_Selected) expander.expandeRow(gp.getStore().indexOf(record_Selected))
                    }
                    if(action.result.codMsg == 3){
                        mostrarMensaje(action.result.codMsg,action.result.mensaje);
                    }
                }
            });
        } else mostrarMensaje(3,'Usted no ha realizado modificaci&oacute;n.');
    }

    function eliminarElemento(gp,isrq){
        var reqSeleteds = gp.getSelectionModel().getSelections();
        var arrayidreq = [];
        Ext.each(reqSeleteds,function(r){
            arrayidreq.push(r.data.id);
        })
        mostrarMensaje(2,' &iquest;Est&aacute; seguro que desea eliminar?',function(btnoprimido)
        {
            if(btnoprimido == 'ok')
            {
                Ext.getBody().mask('Eliminando...');
                Ext.Ajax.request({
                    url:'eliminarRQFN',
                    params:{
                        array_idRQ: Ext.encode(arrayidreq),
                        nombre:arbolSistema.getSelectionModel().getSelectedNode().attributes.text,
                        node:arbolSistema.getSelectionModel().getSelectedNode().attributes.id,
                        path:arbolSistema.getSelectionModel().getSelectedNode().attributes.path,
                        isrq:(isrq)?1:0
                    },
                    callback: function (options,success,response)
                    {
                        responseData = Ext.decode(response.responseText);
                        Ext.getBody().unmask();
                        if(responseData.codMsg != 3){
                            gp.getSelectionModel().clearSelections();
                            gp.getStore().reload();
                            mostrarMensaje(responseData.codMsg,responseData.mensaje);
                        }
                        if(responseData.codMsg == 3){
                            mostrarMensaje(responseData.codMsg,responseData.mensaje);
                        }
                    }
                });
            }
        });
    }

}

