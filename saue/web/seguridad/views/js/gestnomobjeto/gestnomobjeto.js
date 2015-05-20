    var perfil = window.parent.UCID.portal.perfil;
    perfil.etiquetas = Object();
    UCID.portal.cargarEtiquetas('gestnomobjeto', cargarInterfaz);
    ////------------ Inicializo el singlenton QuickTips ------------////
    Ext.QuickTips.init();

    ////------------ Declarar variables ------------////
    var win, opcion, winCamb;
    var auxIns = false;
    var auxDel = false;
    var auxMod = false;
    var descripcion = "";
    var nombre = "";
    ////------------ Area de Validaciones ------------////
    var tipos, abreviatura;
    tipos = /^([a-zA-ZÃ¡Ã©Ã­Ã³ÃºÃ±Ã¼Ã‘]+[a-zA-ZÃ¡Ã©Ã­Ã³ÃºÃ±Ã¼Ã‘\d_]*)+(((\(){1}).([a-zA-ZÃ¡Ã©Ã­Ã³ÃºÃ±Ã¼Ã‘]+[a-zA-ZÃ¡Ã©Ã­Ã³ÃºÃ±Ã¼Ã‘\d_ ]*)+((\)){1}))?$$/;


function cargarInterfaz()
{

    ////------------ Botones ------------////
    btnAdicionar = new Ext.Button({
        id: 'btnAgrObjeto',
        hidden: false,
        icon: perfil.dirImg + 'adicionar.png',
        iconCls: 'btn',
        text: perfil.etiquetas.lbBtnAdicionar,
        handler: function(){
            opcion = 'Ins'; 
            winForm();
        }
    });
    btnModificar = new Ext.Button({
        disabled: true,
        id: 'btnModObjeto',
        hidden: false,
        icon: perfil.dirImg + 'modificar.png',
        iconCls: 'btn',
        text: perfil.etiquetas.lbBtnModificar,
        handler: function() {
            opcion = 'Mod';
            winForm();
        }
    });
    btnEliminar = new Ext.Button({
        disabled: true,
        id: 'btnEliObjeto',
        hidden: false,
        icon: perfil.dirImg + 'eliminar.png',
        iconCls: 'btn',
        text: perfil.etiquetas.lbBtnEliminar,
        handler: function() {
            eliminarObjeto();
        }
    });
    /*btnAyuda = new Ext.Button({id:'btnAyuIdioma', hidden:true, icon:perfil.dirImg+'ayuda.png', iconCls:'btn', text:perfil.etiquetas.lbBtnAyuda });*/
    UCID.portal.cargarAcciones(window.parent.idFuncionalidad);

    ////------------ Store del Grid de Objetos ------------////
    stgpObjeto = new Ext.data.Store({
        fields:
                [{
                        name: 'id',
                        mapping: 'id'
                    },
                    {
                        name: 'nombreobjeto',
                        mapping: 'nombreobjeto'
                    },
                    {
                        name: 'descripcion',
                        mapping: 'descripcion'
                    },
                ],
        proxy: {
            type: 'ajax',
            url: 'cargarnomobjetos',
            actionMethods: { //Esta Linea es necesaria para el metodo de llamada POST o GET

                read: 'POST'
            },
            reader: {
                totalProperty: "cantidad_filas",
                root: "datos",
                id: "id"
            }
        }
    });

    stgpObjeto.on('load', function() {
        if (stgpObjeto.getCount() == 5) {
            btnAdicionar.disable();
        }
        else {
            btnAdicionar.enable();
        }
    });

    ////------------ Establesco modo de seleccion de grid (single) ------------////
    sm = Ext.create('Ext.selection.RowModel', {
        mode: 'SINGLE',
        allowDeselect: true
    });
    sm.on('beforeselect', function(smodel, rowIndex, keepExisting, record) {
        btnModificar.enable();
        btnEliminar.enable();
    }, this);

    ////------------ Defino el grid de Objetos ------------////
    var gpObjeto = new Ext.grid.GridPanel({
        frame: true,
        region: 'center',
        header:false,
        iconCls: 'icon-grid',
        autoExpandColumn: 'descripcion',
        store: stgpObjeto,
        selModel: sm,
        columns: [
            {
                hidden: true,
                hideable: false,
                dataIndex: 'id'
            },
            {
                header: perfil.etiquetas.lbTitDenominacion,
                width: 200,
                dataIndex: 'nombreobjeto',
                flex: 1
            },
            {
                header: perfil.etiquetas.lbTitDescripcion,
                width: 300,
                dataIndex: 'descripcion'
            }
        ],
        loadMask: {
            store: stgpObjeto
        },
        bbar: new Ext.PagingToolbar({
            pageSize: 10,
            id: 'ptbaux',
            store: stgpObjeto,
            displayInfo: true,
            displayMsg: perfil.etiquetas.lbMsgbbarI,
            emptyMsg: perfil.etiquetas.lbMsgbbarII
        })
    });
    ////------------ Trabajo con el PagingToolbar ------------////
    Ext.getCmp('ptbaux').on('change', function() {
       // if (stgpObjeto.count() > 0)
            sm.select();
    }, this);
    ////------------ Panel con los componentes ------------////
    var panel = new Ext.Panel({
        layout: 'fit',
        title: perfil.etiquetas.lbTitPanelTit,
        items: [gpObjeto],
        tbar: [btnAdicionar, btnModificar, btnEliminar/*,btnAyuda*/],
        keys: new Ext.KeyMap(document, [
            {
                key: Ext.EventObject.DELETE,
                fn: function() {
                    if (auxDel)
                        eliminarObjeto();
                }
            },
            {
                key: 'i',
                alt: true,
                fn: function() {
                    if (auxIns){
                        opcion = 'Ins'; 
                        winForm();
                    }
                }
            },
            {
                key: "m",
                alt: true,
                fn: function() {
                    if (auxMod){
                        opcion = 'Mod';
                        winForm();
                    }
                }
            }
        ])
    });
    ////------------ Eventos para hotkeys ------------////
    btnAdicionar.on('show', function() {
        auxIns = true;
    }, this);
    btnEliminar.on('enable', function() {
        auxDel = true;
    }, this);
    btnModificar.on('enable', function() {
        auxMod = true;
    }, this);
    stgpObjeto.on('load', function() {
        if (stgpObjeto.getCount() != 0) {
            auxMod = true;
            auxDel = true;
        }
        else {
            auxMod = false;
            auxDel = false;
        }
    }, this);

    ///----Store del comboObjetosNombre----///
    var stcombo = new Ext.data.Store({
        autoLoad:true,
        fields:
                [{
                        name: 'nombreobjeto',
                        mapping: 'nombreobjeto'
                    },
                    {
                        name: 'idobj',
                        mapping: 'idobj'
                    }
                ],
        proxy: {
            type: 'ajax',
            url: 'getObjetosBd',
            actionMethods: { //Esta Linea es necesaria para el metodo de llamada POST o GET

                read: 'POST'
            },
            reader: {
                id: "nombreobjeto"
            }
        }
    });
    stcombo.on('load', function() {
        if (stcombo.getCount() == 0)
            comboobjetos.disable();
        else
            comboobjetos.enable();
    });
    ///----Va a traer los criterios de seleccion (Tablas, Secuencias,Vistas,Funciones)----///
    var comboobjetos = new Ext.form.ComboBox({
        fieldLabel: perfil.etiquetas.comboLabel,
        id: 'objbd',
        xtype: 'combo',
        store: stcombo,
        valueField: 'nombreobjeto',
        displayField: 'nombreobjeto',
        name: 'nombreobjeto',
        triggerAction: 'all',
        allowBlank: false,
        editable: false,
        mode: 'local',
        emptyText: perfil.etiquetas.comboEmptyText,
        anchor: '95%',
        width: 100
    });
    ////------------ Formulario ------------////
    var regObjeto = new Ext.FormPanel({
        labelAlign: 'top',
        frame: true,
        bodyStyle: 'padding:5px 5px 0',
        trackResetOnLoad:true,
        items: [{
                items: [{
                        columnWidth: .5,
                        layout: 'form',
                        margin: '5 5 5 5',
                        border: 0,
                        items: [comboobjetos]
                    }, {
                        columnWidth: .5,
                        layout: 'form',
                        margin: '5 5 5 5',
                        border: 0,
                        items: [{
                                xtype: 'textarea',
                                fieldLabel: perfil.etiquetas.lbFLDescripcion,
                                id: 'descripcion',
                                name: 'descripcion',
                                maxLength: 150,
                                anchor: '95%'
                            }]
                    }]
            }]
    });
    ////------------ Cargar la ventana ------------////
    function winForm() {
       stcombo.load();
       if (!win) {
            win = new Ext.Window({
                modal: true,
                closeAction: 'close',
                layout: 'fit',
                resizable: false,
                title: (opcion == 'Ins') ? perfil.etiquetas.lbTitVentanaTitI : perfil.etiquetas.lbTitVentanaTitII,
                width: 270,
                height: 220,
                buttons: [{
                        icon: perfil.dirImg + 'cancelar.png',
                        iconCls: 'btn',
                        text: perfil.etiquetas.lbBtnCancelar,
                        handler: function() {
                            win.hide();
                        }
                    }, {
                        icon: perfil.dirImg + 'aplicar.png',
                        iconCls: 'btn',
                        id: 'btnaplicar',
                        text: perfil.etiquetas.lbBtnAplicar,
                        handler: function(){
                           adicionarObjeto('apl');
                        }
                    }, {
                        icon: perfil.dirImg + 'aceptar.png',
                        iconCls: 'btn',
                        text: perfil.etiquetas.lbBtnAceptar,
                        handler: function() {
                            gestionarAccion();
                        }
                    }]
            });
            win.on('show', function() {
                auxIns = false;
                auxMod = false;
                auxDel = false;
            }, this);
            win.on('hide', function() {
                auxIns = true;
                auxMod = true;
                auxDel = true;
            }, this);
        }
        win.add(regObjeto);
        if (opcion == 'Ins'){
            win.setTitle(perfil.etiquetas.lbTitVentanaTitI);
            Ext.getCmp('btnaplicar').show();
            comboobjetos.originalValue=null;
            comboobjetos.clearValue();
            comboobjetos.initValue();
        }
        else{
            win.setTitle(perfil.etiquetas.lbTitVentanaTitII);
            Ext.getCmp('btnaplicar').hide();
            regObjeto.getForm().reset();
            var record = sm.getLastSelected();
            regObjeto.getForm().loadRecord(record);
            nombre = record.get('nombreobjeto');
            comboobjetos.setValue(nombre);
            descripcion = Ext.getCmp('descripcion').getValue();
        }
        win.doLayout();
        win.show(); 
    }
     
    ////------------ Viewport ------------////
    var vpGestObjeto = new Ext.Viewport({
        layout: 'fit',
        items: panel
    });
    stgpObjeto.load({
        params: {
            start: 0,
            limit: 10
        }
    });
    
    function gestionarAccion(){
        if (opcion == 'Ins')
            adicionarObjeto();
        else
           modificarObjeto(); 
    }

    ////------------ Adicionar Objetos ------------////
    function adicionarObjeto(apl) {
         if (regObjeto.getForm().isValid()) {
            if (Ext.getCmp('objbd').getValue()) {
                regObjeto.getForm().submit({
                    url: 'insertarnomobjeto',
                    waitMsg: perfil.etiquetas.lbMsgFunAdicionarMsg,
                    failure: function(form, action) {
                        if (action.result.codMsg != 3) {                            
                            regObjeto.getForm().reset();
                            stgpObjeto.load();
                            stcombo.load();
                            if (!apl)
                                win.hide();
                            sm.clearSelections();
                            btnModificar.disable();
                            btnEliminar.disable();
                        }                        
                    }
                });
            }
            else
                mostrarMensaje(3, perfil.etiquetas.lbMsgErrorEnCamops);
        }
        else
            mostrarMensaje(3, perfil.etiquetas.lbMsgErrorEnCamops);
    }

    ////------------ Modififcar Objeto ------------////
    function modificarObjeto() {
        if (regObjeto.getForm().isValid()) {
            if (Ext.getCmp('objbd').getValue() != nombre || descripcion != Ext.getCmp('descripcion').getValue()) {
                regObjeto.getForm().submit({
                    url: 'modificaromobjeto',
                    waitMsg: perfil.etiquetas.lbMsgFunModificarMsg,
                    params: {
                        id: sm.getLastSelected().data.id
                    },
                    failure: function(form, action) {
                        if (action.result.codMsg != 3) {                            
                            win.hide();
                            stgpObjeto.load();
                            stcombo.load();
                        }                        
                    }
                });
            }
            else {
                mostrarMensaje(3, perfil.etiquetas.NoModify);
            }
        }
        else
            mostrarMensaje(3, perfil.etiquetas.lbMsgErrorEnCamops);
    }
    
    ////------------ Eliminar Objeto ------------////
    function eliminarObjeto() {
        mostrarMensaje(2, perfil.etiquetas.lbMsgFunEliminarMsgI, elimina);
        function elimina(btnPresionado) {
            if (btnPresionado == 'ok') {
                Ext.Ajax.request({
                    url: 'eliminarobjeto',
                    method: 'POST',
                    params: {
                        id: sm.getLastSelected().data.id
                    },
                    callback: function(options, success, response) {
                        responseData = Ext.decode(response.responseText);
                        if (responseData.codMsg == 1) {                            
                            stgpObjeto.load();
                            sm.clearSelections();
                            btnModificar.disable();
                            btnEliminar.disable();
                        }                        
                    }
                });
            }
        }
    }
}
