var perfil = window.parent.UCID.portal.perfil;
perfil.etiquetas = Object();
UCID.portal.cargarEtiquetas('gestsistema', cargarInterfaz);
////------------ Inicializo el singlenton QuickTips ------------////
Ext.QuickTips.init();
////------------ Declarar variables ------------////
var winIns, nodoarbol, winPassAdd, winPassMod, nodeGestor, nodeArbolConexSelect, winCon, winMod, winCamb, winExp, winImp, btnBuscar, idsistema, regEsquemas, fpbuscar, sistemas, winEs, panelAdicionar, arbolSistema, arbolConex, arbolLoaderConex, sistemaseleccionado, idpadre, frmContrasennaRol, fpRegSistema, nodoSeleccionado, primernodobd, bandera = 0;
var arregloDeschequeados = [];
var auxIns = false;
var auxMod = false;
var auxDel = false;
var auxImp = false;
var auxExp = false;
var auxIns2 = false;
var auxMod2 = false;
var auxDel2 = false;
var auxImp2 = false;
var auxExp2 = false;
var auxIns3 = true;
var auxMod3 = true;
var auxDel3 = true;
var auxImp3 = true;
var auxExp3 = true;
var idrolbdOnclick = 0;
var acc;
var denominacion, abreviatura, icono, servidorweb, descripcion;
var nodeRoleChecked = null;
var RoleChecked = [];
var RoleUnChecked = [];
var nodeBDChecked = null;
var BDChecked = [];
var BDUnChecked = [];
var systemRelatedWithRole = false;
var id_padre = null;
var esquemaChecked = false;
////------------ Area de Expresiones para validaciones ------------////
var deschekear = 0, roleModify = false;
var tipos = /(^([a-zA-ZáéíóúñÑ])+((\s)+[a-zA-Z0-9áéíóúñÑ]+)*([a-zA-Z0-9áéíóúñÑ]*))$/;
var urllocal = /(^([a-zA-ZáéíóúñÑ_])+((\s)+[a-zA-Z0-9áéíóúñÑ_]+)*([a-zA-Z0-9áéíóúñÑ_]*))$/;
////------------ Funcion para cargar la interfaz ------------////
function cargarInterfaz() {
    ////------------ Botones principales ------------////
    btnAdicionar = new Ext.Button({disabled: true, id: 'btnAgrSist', hidden: true, icon: perfil.dirImg + 'adicionar.png', iconCls: 'btn', iconCls:'btn', text: perfil.etiquetas.lbBtnAdicionar, handler: function() {
            id_padre = arbolSistema.getSelectionModel().getSelectedNode().attributes.id;
            winForm('Ins');
        }});
    btnModificar = new Ext.Button({disabled: true, id: 'btnModSist', hidden: true, icon: perfil.dirImg + 'modificar.png', iconCls: 'btn', text: perfil.etiquetas.lbBtnModificar, handler: function() {
            winForm('Mod');
        }});
    btnEliminar = new Ext.Button({disabled: true, id: 'btnEliSist', hidden: true, icon: perfil.dirImg + 'eliminar.png', iconCls: 'btn', text: perfil.etiquetas.lbBtnEliminar, handler: function() {
            eliminarSistema();
        }});
    btnExportar = new Ext.Button({disabled: true, id: 'btnExpSist', hidden: true, icon: perfil.dirImg + 'exportar.png', iconCls: 'btn', text: perfil.etiquetas.lbBtnExportar, handler: function() {
            exportarSistema();
        }});
    btnImportar = new Ext.Button({disabled: true, id: 'btnImpSist', hidden: true, icon: perfil.dirImg + 'importar.png', iconCls: 'btn', text: perfil.etiquetas.lbBtnImportar, handler: function() {
            winForm('Imp');
        }});
    /*btnAyuda = new Ext.Button({id:'btnAyuSist', hidden:true, icon:perfil.dirImg+'ayuda.png', iconCls:'btn', text:'Ayuda' });*/
    UCID.portal.cargarAcciones(window.parent.idFuncionalidad);
    /*
     *
     ******************Objetos****************
     *
     */
    ////------------ Arbol de sistemas ------------////
    arbolSistema = new Ext.tree.TreePanel({
        title: perfil.etiquetas.lbMsgGridSist,
        tbar: [btnAdicionar, btnModificar, btnEliminar, btnImportar, btnExportar/*,btnAyuda*/],
        enableDD: true,
        autoScroll: true,
        region: 'west',
        width: 150,
        margins: '2 2 2 2',
        loader: new Ext.tree.TreeLoader({
            dataUrl: 'cargarsistema'
        }),
        keys: new Ext.KeyMap(document, [{
                key: Ext.EventObject.DELETE,
                fn: function() {
                    if (auxDel && auxDel2 && auxDel3)
                        eliminarSistema();
                }
            },
            {
                key: "i",
                alt: true,
                fn: function() {
                    if (auxIns && auxIns2 && auxIns3)
                        winForm('Ins');
                }
            },
            {
                key: "b",
                alt: true,
                fn: function() {
                    buscargestor(Ext.getCmp('nombregestor').getValue());
                }
            },
            {
                key: "m",
                alt: true,
                fn: function() {
                    if (auxMod && auxMod2 && auxMod3)
                        winForm('Mod');
                }
            },
            {
                key: "e",
                alt: true,
                fn: function() {
                    if (auxExp && auxExp2 && auxExp3)
                        exportarSistema();
                }},
            {
                key: "x",
                alt: true,
                fn: function() {
                    if (auxImp && auxImp2 && auxImp3)
                        winForm('Imp');
                }}])
    });
    ////---------- Eventos para hotkeys ----------////
    btnAdicionar.on('show', function() {
        auxIns2 = true;
    }, this)
    btnEliminar.on('show', function() {
        auxDel2 = true;
    }, this)
    btnModificar.on('show', function() {
        auxMod2 = true;
    }, this)
    btnExportar.on('show', function() {
        auxExp2 = true;
    }, this)
    btnImportar.on('show', function() {
        auxImp2 = true;
    }, this)

    ////------------ Crear nodo padre del arbol ------------////
    padreSistema = new Ext.tree.AsyncTreeNode({
        text: perfil.etiquetas.lbRootNodeArbolSubsist,
        animate: false,
        draggable: false,
        expandable: false,
        expanded: true,
        abreviatura: "defecto",
        id: '0'
    });
    ////------------ Crear lista de hijos ------------////
    arbolSistema.setRootNode(padreSistema);
    ////------------ Evento para habilitar botones ------------////
    arbolSistema.on('click', function(node, e) {
        sistemaseleccionado = node.id
        sistemas = node;
        bandera = 0;
        idsistema = node.id;
        nodoSeleccionado = node;
        btnModificar.enable();
        btnEliminar.enable();
        btnAdicionar.enable();
        btnImportar.enable();
        btnExportar.enable();
        auxIns = true;
        auxMod = true;
        auxExp = true;
        auxImp = true;
        auxDel = true;
    }, this);
    arbolLoaderConex = new Ext.tree.TreeLoader({
        dataUrl: 'cargarservidores',
        listeners: {
            'beforeload': function(atreeloader, anode) {

                if (anode.attributes.namebd) {
                    atreeloader.baseParams = {};
                    atreeloader.baseParams.accion = 'cargaresquemas';
                    atreeloader.baseParams.user = anode.attributes.user;
                    atreeloader.baseParams.passw = anode.attributes.passw;
                    atreeloader.baseParams.gestor = anode.attributes.gestor;
                    atreeloader.baseParams.namebd = anode.attributes.namebd;
                    atreeloader.baseParams.idservidor = anode.attributes.idservidor;
                    atreeloader.baseParams.idgestor = anode.attributes.idgestor;
                    atreeloader.baseParams.puerto = anode.attributes.puerto;
                    atreeloader.baseParams.idsistema = sistemaseleccionado;
                    atreeloader.baseParams.ipgestorbd = anode.attributes.ipgestorbd;
                    atreeloader.baseParams.idrolbd = anode.attributes.idrolbd;
                    atreeloader.baseParams.acc = acc;
                }
                else if (anode.attributes.idgestor) {
                    atreeloader.baseParams = {};
                    atreeloader.baseParams.accion = 'cargarbd';
                    atreeloader.baseParams.gestor = anode.attributes.gestor;
                    atreeloader.baseParams.ipgestorbd = anode.attributes.ipgestorbd;
                    atreeloader.baseParams.idgestor = anode.attributes.idgestor;
                    atreeloader.baseParams.idservidor = anode.attributes.idservidor;
                    atreeloader.baseParams.puerto = anode.attributes.puerto;
                    atreeloader.baseParams.idsistema = sistemaseleccionado;
                    atreeloader.baseParams.acc = acc;
                }
                else if (anode.attributes.idservidor) {
                    atreeloader.baseParams = {};
                    atreeloader.baseParams.idservidor = anode.attributes.idservidor;
                    atreeloader.baseParams.idsistema = sistemaseleccionado;
                    atreeloader.baseParams.accion = 'cargargestores';
                    atreeloader.baseParams.acc = acc;
                }
                else if (anode.attributes.id == 0) {
                    atreeloader.baseParams.idsistema = sistemaseleccionado;
                    atreeloader.baseParams.acc = acc;
                }
            },
            'load': function(treeLoaderObj, nodeObj, response) {

                respObj = Ext.decode(response.responseText);
                if (nodeObj.attributes.namebd) {
                } else
                if (nodeObj.attributes.idgestor) {
                    nodeRoleChecked = null;
                    nodeBDChecked = null;
                    systemRelatedWithRole = "doNotAction";
                    RoleChecked = [];
                    RoleUnChecked = [];
                    BDChecked = [];
                    BDUnChecked = [];
                    nodeObj.eachChild(
                            function(hijo) {

                                if (hijo.attributes.type == 'roles' && hijo.attributes.checked) {
                                    nodeRoleChecked = hijo;
                                }
                                else if (hijo.attributes.type == 'roles' && hijo.attributes.isconex) {
                                    idrolbdOnclick = hijo.attributes.idrolbd;
                                    hijo.getUI().hide();
                                }

                                if (hijo.attributes.type == 'bd' && hijo.attributes.checked) {
                                    nodeBDChecked = hijo;
                                }
                            }
                    , this);
                }

                if (!respObj.codMsg) {
                    btnAdicionar.disable();
                    btnModificar.disable();
                    btnEliminar.disable();
                    btnImportar.disable();
                    btnExportar.disable();
                }
                else if (respObj.codMsg == 2 && nodeObj.attributes.idgestor) {
                    nodeObj.parentNode.reload();
                }
                else if (respObj.codMsg == 3 && nodeObj.attributes.idgestor) {
                    mostrarMensaje(3, perfil.etiquetas.MsgErrorConexI);
                    nodeObj.parentNode.reload();
                }
            }
        }
    });
    ////------------ Arbol de servidores ------------////
    arbolConex = new Ext.tree.TreePanel({
        title: perfil.etiquetas.lbTitArbolConex,
        border: false,
        autoScroll: true,
        region: 'center',
        width: 320,
        loader: arbolLoaderConex,
        margins: '2 2 2 2'
    });
    arbolConex.on('checkchange', function(node, checked) {
        if (node.attributes.type == 'roles' || node.attributes.type == 'bd') {
            if (checked) {
                var tree = node.getOwnerTree().getRootNode();
                var arrayRolesBDs = [];
                var arrayServidores = tree.childNodes;
                var arrayGestores = [];
                for (var i = 0; i < arrayServidores.length; i++) {
                    arrayGestores.push.apply(arrayGestores, arrayServidores[i].childNodes)
                }

                for (i = 0; i < arrayGestores.length; i++) {
                    arrayRolesBDs.push.apply(arrayRolesBDs, arrayGestores[i].childNodes)
                }

                arrayRolesBDs.forEach(function(valor, indice, array) {
                    if (valor.attributes.type == node.attributes.type && valor.attributes.id != node.attributes.id
                            && valor.attributes.checked) {
                        valor.getUI().toggleCheck(false);
                        valor.attributes.checked = false;
                    } else
                    if (valor.attributes.type == node.attributes.type && valor.attributes.id != node.attributes.id
                            && valor.attributes.checked) {
                        valor.getUI().toggleCheck(false);
                        valor.attributes.checked = false;
                    }
                });
                var pos;
                if (node.attributes.type == 'roles') {
                    nodeRoleChecked = node;
                    systemRelatedWithRole = true;
                    pos = BuscarArreglo(RoleUnChecked, node.attributes.id);
                    if (pos != -1) {
                        EliminarEnArreglo(RoleUnChecked, pos);
                    }
                    AdicionarEnArreglo(RoleChecked, node.attributes.id);
                }

                else if (node.attributes.type == 'bd') {
                    nodeBDChecked = node;
                    systemRelatedWithRole = true;
                    pos = BuscarArreglo(BDUnChecked, node.attributes.id);
                    if (pos != -1) {
                        EliminarEnArreglo(BDUnChecked, pos);
                    }
                    AdicionarEnArreglo(BDChecked, node.attributes.id);
                }

            }
            else {
                systemRelatedWithRole = false;
                if (node.attributes.type == 'roles') {
                    nodeRoleChecked = null;
                    pos = BuscarArreglo(RoleChecked, node.attributes.id);
                    if (pos != -1) {
                        EliminarEnArreglo(RoleChecked, pos);
                    }
                    AdicionarEnArreglo(RoleUnChecked, node.attributes.id);
                }
                else if (node.attributes.type == 'bd') {
                    nodeBDChecked = null;
                    pos = BuscarArreglo(BDChecked, node.attributes.id);
                    if (pos != -1) {
                        EliminarEnArreglo(BDChecked, pos);
                    }
                    AdicionarEnArreglo(BDUnChecked, node.attributes.id);
                }
            }

        } else
        if (winMod && node.attributes.type == 'schemas') {
            if (node.attributes.marcado) {
                var esta = estaEnDeschequeados(arregloDeschequeados, node.attributes.marcado);
            }
            if (node.attributes.checked && esta != -1) {
                esquemaChecked = true;
                eliminarEnDeschequeados(arregloDeschequeados, esta);
            }
            else {
                adicionarEnDeschequeados(arregloDeschequeados, node.attributes.marcado);
            }
        }
    }, this);
    function AdicionarEnArreglo(arreglo, id) {
        arreglo.push(id);
    }
    function EliminarEnArreglo(arreglo, pos) {
        arreglo.splice(pos, 1);
    }
    function BuscarArreglo(arreglo, id) {
        for (var p = 0; p < arreglo.length; p++)
            if (arreglo[p] == id)
                return p;
        return -1;
    }

    ////------------ Crear nodo padre del arbol ------------////
    padreConex = new Ext.tree.AsyncTreeNode({
        text: perfil.etiquetas.lbRootNodeArbolServidor,
        draggable: false,
        expandable: false,
        id: '0'
    });
    ////------------ Crear lista de hijos ------------////
    arbolConex.setRootNode(padreConex);
    ////------------ Formulario de Datos de Sistema ------------////
    fpRegSistema = new Ext.FormPanel({
        labelAlign: 'top',
        frame: true,
        title: perfil.etiquetas.lbTitFormRegSistema,
        width: 200,
        region: 'west',
        bodyStyle: 'padding:5px 5px 0',
        items: [{
                layout: 'column',
                items: [{
                        columnWidth: 1,
                        layout: 'form',
                        items: [{
                                xtype: 'textfield',
                                fieldLabel: perfil.etiquetas.lbCampoDenom,
                                id: 'denominacion',
                                maxLength: 50,
                                allowBlank: false,
                                blankText: perfil.etiquetas.lbMsgBlank,
                                regex: tipos,
                                regexText: perfil.etiquetas.lbMsgValorIncorrecto,
                                maskRe: /[a-z0-9A-Z\s]/i,
                                anchor: '100%'
                            }, {
                                xtype: 'textfield',
                                fieldLabel: perfil.etiquetas.lbCampoAbreviatura,
                                id: 'abreviatura',
                                maxLength: 50,
                                allowBlank: false,
                                blankText: perfil.etiquetas.lbMsgBlank,
                                regex: tipos,
                                maskRe: /[a-z0-9A-Z\s]/i,
                                regexText: perfil.etiquetas.lbMsgValorIncorrecto,
                                anchor: '100%'
                            }, {
                                xtype: 'textfield',
                                fieldLabel: perfil.etiquetas.lbIcono,
                                id: 'icono',
                                maxLength: 20,
                                regex: urllocal,
                                maskRe: /[a-z0-9A-Z_\s]/i,
                                regexText: perfil.etiquetas.lbMsgValorIncorrecto,
                                anchor: '100%'
                            }, {
                                xtype: 'fieldset',
                                id: 'externo',
                                checkboxToggle: true,
                                title: perfil.etiquetas.lbFieldExterno,
                                autoHeight: true,
                                defaultType: 'textfield',
                                collapsed: false,
                                items: [{
                                        fieldLabel: perfil.etiquetas.lbCampoServidorWeb,
                                        name: 'servidorweb',
                                        id: 'servidorweb',
                                        maxLength: 20,
                                        anchor: '100%'
                                    }]
                            }, {
                                xtype: 'textarea',
                                fieldLabel: perfil.etiquetas.lbDescripcion,
                                id: 'descripcion',
                                height: 60,
                                anchor: '100%'
                            }]
                    }]
            }]
    });

    $ventanaImportar = new Ext.form.FileUploadField({
        fieldLabel: perfil.etiquetas.lbCampoImportar,
        name: 'fileUpload',
        id: 'fileUpload',
        accept: "text/xml",
        allowBlank: false,
        blankText: perfil.etiquetas.lbMsgBlank,
        anchor: '100%',
        listeners: {fileselected: function(fileuploader, v) {
                var valid = v.endsWith(".xml");
                if (!valid) {
                    Ext.Msg.show({
                        msg: perfil.etiquetas.MsgErrorXMLInvalido,
                        buttons: Ext.Msg.OK,
                        icon: Ext.MessageBox.ERROR
                    });
                    fileuploader.setValue('');
                    return false;
                }
            }
        }
    });

    fpbuscar = new Ext.FormPanel({
        labelAlign: 'top',
        frame: true,
        fileUpload: true,
        bodyStyle: 'padding:5px 5px 0',
        items: $ventanaImportar
    });
    ////------------ Panel para auntenticar con el servidor de base de datos ------------////
    frmContrasennaRol = new Ext.FormPanel({
        labelAlign: 'left',
        frame: true,
        width: 100,
        fileUpload: true,
        bodyStyle: 'padding:5px 5px 0',
        items: [{
                xtype: 'textfield',
                fieldLabel: perfil.etiquetas.lbTitMsgContrasena,
                id: 'pass',
                inputType: 'password',
                allowBlank: false,
                blankText: perfil.etiquetas.lbMsgBlank,
                regex: tipos,
                anchor: '100%'
            }, {
                xtype: 'textfield',
                fieldLabel: perfil.etiquetas.lbCampoConfirmarPass,
                id: 'conf',
                inputType: 'password',
                allowBlank: false,
                blankText: perfil.etiquetas.lbMsgBlank,
                anchor: '100%'
            }]
    });
    ////------------ Panel ------------////
    panelAdicionar = new Ext.Panel({
        layout: 'border',
        border: 'false',
        items: [fpRegSistema, arbolConex]
    });
    var vpGestSistema = new Ext.Viewport({
        layout: 'fit',
        items: arbolSistema
    })
    function eliminarEnDeschequeados(arreglo, pos) {
        arreglo.splice(pos, 1);
    }

    function adicionarEnDeschequeados(arreglo, id) {
        if (estaEnDeschequeados(arreglo, id) == -1)
            arreglo.push(id);
    }

    function estaEnDeschequeados(arreglonodos, idnodo) {
        for (p = 0; p < arreglonodos.length; p++)
            if (arreglonodos[p] == idnodo)
                return p;
        return -1;
    }

    ////------------ Cargar ventanas ------------////
    function winForm(opcion) {
        switch (opcion) {
            case 'Ins':
                {
                    if (!winIns) {
                        winIns = new Ext.Window({
                            modal: true, closeAction: 'hide', layout: 'fit',
                            title: perfil.etiquetas.lbTitAdicionarSist, width: 580, height: 450,
                            buttons: [{
                                    icon: perfil.dirImg + 'cancelar.png',
                                    iconCls: 'btn',
                                    text: perfil.etiquetas.lbBtnCancelar,
                                    handler: function() {
                                        ReiniciarVariables();
                                        winIns.hide();
                                    }
                                }, {
                                    icon: perfil.dirImg + 'aplicar.png',
                                    iconCls: 'btn',
                                    text: perfil.etiquetas.lbBtnAplicar,
                                    handler: function() {
                                        adicionarSistema('apl');
                                    }
                                }, {
                                    icon: perfil.dirImg + 'aceptar.png',
                                    iconCls: 'btn',
                                    text: perfil.etiquetas.lbBtnAceptar,
                                    handler: function() {
                                        adicionarSistema();
                                    }
                                }
                            ]
                        });
                        winIns.on('show', function() {
                            auxIns3 = false;
                            auxMod3 = false;
                            auxDel3 = false;
                            auxExp3 = false;
                            auxImp3 = false;
                        }, this);
                        winIns.on('hide', function() {
                            auxIns3 = true;
                            auxMod3 = true;
                            auxDel3 = true;
                            auxExp3 = true;
                            auxImp3 = true;
                            fpRegSistema.getForm().reset();
                        }, this)
                    }
                    acc = "add"
                    arbolConex.getRootNode().reload();
                    fpRegSistema.getForm().reset();
                    winIns.add(panelAdicionar);
                    winIns.doLayout();
                    winIns.show();
                }
                break;
            case 'Mod':
                {
                    if (!winMod) {
                        winMod = new Ext.Window({
                            modal: true, closeAction: 'hide', layout: 'fit',
                            title: perfil.etiquetas.lbTitModificarSist, width: 580, height: 450,
                            buttons: [{
                                    icon: perfil.dirImg + 'cancelar.png',
                                    iconCls: 'btn',
                                    text: perfil.etiquetas.lbBtnCancelar,
                                    handler: function() {
                                        ReiniciarVariables();
                                        winMod.hide();
                                        if (fpRegSistema.disabled)
                                            fpRegSistema.enable();
                                    }
                                }, {
                                    icon: perfil.dirImg + 'aceptar.png',
                                    iconCls: 'btn',
                                    text: perfil.etiquetas.lbBtnAceptar,
                                    handler: function() {
                                        modificarSistema();
                                        if (fpRegSistema.disabled)
                                            fpRegSistema.enable();
                                    }
                                }]
                        });
                        winMod.on('show', function() {
                            auxIns3 = false;
                            auxMod3 = false;
                            auxDel3 = false;
                            auxExp3 = false;
                            auxImp3 = false;
                        }, this);
                        winMod.on('hide', function() {
                            auxIns3 = true;
                            auxMod3 = true;
                            auxDel3 = true;
                            auxExp3 = true;
                            auxImp3 = true;
                            fpRegSistema.getForm().reset();
                        }, this);
                    }
                    fpRegSistema.getForm().reset();
                    acc = "mod";
                    arbolConex.getLoader().baseParams = {};
                    arbolConex.getLoader().baseParams.idsistema = sistemaseleccionado;
                    arbolConex.getRootNode().reload();
                    winMod.add(panelAdicionar);
                    winMod.doLayout();
                    Ext.getCmp('externo').show();
                    if (arbolSistema.getSelectionModel().getSelectedNode().id == 0) {
                        fpRegSistema.disable();
                    }
                    if (arbolSistema.getSelectionModel().getSelectedNode().id != 0 && arbolSistema.getSelectionModel().getSelectedNode().parentNode.id != 0)
                        Ext.getCmp('externo').setVisible(false);
                    winMod.show();
                    fpRegSistema.getForm().loadRecord(dameNodeInfo());
                    if (Ext.getCmp('servidorweb').getValue()) {
                        if (bandera == 0) {
                            Ext.getCmp('externo').expand(false);
                            bandera = 1;
                        }
                    }
                    else {
                        if (bandera == 0) {
                            Ext.getCmp('externo').collapse(false);
                            bandera = 1;
                        }
                    }
                    denominacion = Ext.getCmp('denominacion').getValue();
                    abreviatura = Ext.getCmp('abreviatura').getValue();
                    servidorweb = Ext.getCmp('servidorweb').getValue();
                    descripcion = Ext.getCmp('descripcion').getValue();
                    icono = Ext.getCmp('icono').getValue();
                }
                break;
            case 'Imp':
                {
                    if (!winImp) {
                        winImp = new Ext.Window({modal: false, closeAction: 'hide', layout: 'fit',
                            title: perfil.etiquetas.lbTitImportarSist, width: 400, height: 150, items: fpbuscar,
                            buttons: [{icon: perfil.dirImg + 'cancelar.png', iconCls: 'btn',
                                    text: perfil.etiquetas.lbBtnCancelar,
                                    handler: function() {
                                        winImp.hide();
                                    }
                                }, {icon: perfil.dirImg + 'aceptar.png', iconCls: 'btn',
                                    text: perfil.etiquetas.lbBtnAceptar,
                                    handler: function() {
                                        importarSistema();
                                    }}
                            ]
                        });
                        winImp.on('show', function() {
                            auxIns3 = false;
                            auxMod3 = false;
                            auxDel3 = false;
                            auxExp3 = false;
                            auxImp3 = false;
                        }, this)
                        winImp.on('hide', function() {
                            auxIns3 = true;
                            auxMod3 = true;
                            auxDel3 = true;
                            auxExp3 = true;
                            auxImp3 = true;
                        }, this)
                    }
                    fpbuscar.getForm().reset();
                    acc = "Imp";
                    winImp.show('btnImportar');
                }
                break;
        }
    }


    //---------------obtener datos del sistema para modificar---------------------------//
    var dameNodeInfo = function() {
        var record = Ext.data.Record.create([
            {name: 'denominacion', mapping: 'denominacion'},
        ])
        return new record({
            denominacion: nodoSeleccionado.attributes.text,
            abreviatura: nodoSeleccionado.attributes.abreviatura,
            descripcion: nodoSeleccionado.attributes.descripcion,
            icono: nodoSeleccionado.attributes.icono,
            servidorweb: nodoSeleccionado.attributes.servidorweb
        })
    }

    function levantarVentanaAdicionar(arrayPgsql, arrayOracle, apl) {
        if (nodeRoleChecked != null && nodeBDChecked != null) {
            if (nodeRoleChecked.attributes.ipgestorbd == nodeBDChecked.attributes.ipgestorbd &&
                    nodeRoleChecked.attributes.gestor == nodeBDChecked.attributes.gestor &&
                    nodeRoleChecked.attributes.puerto == nodeBDChecked.attributes.puerto) {
                if (!winPassAdd) {
                    winPassAdd = new Ext.Window({
                        modal: true,
                        closeAction: 'hide',
                        layout: 'fit',
                        title: perfil.etiquetas.lbCampoContrasena + '\n' + nodeRoleChecked.attributes.text + '.',
                        width: 290,
                        height: 150,
                        resizable: false,
                        buttons: [{
                                icon: perfil.dirImg + 'cancelar.png',
                                iconCls: 'btn',
                                id: 'can',
                                text: perfil.etiquetas.lbBtnCancelar,
                                handler: function() {
                                    winPassAdd.hide();
                                }
                            }, {
                                icon: perfil.dirImg + 'aceptar.png',
                                iconCls: 'btn',
                                handler: function() {
                                    if (frmContrasennaRol.getForm().isValid()) {
                                        var contrasenna = Ext.getCmp('conf').getValue();
                                        var confirmacion = Ext.getCmp('pass').getValue();
                                        if (contrasenna == confirmacion) {
                                            fpRegSistema.getForm().submit(ObjectSubmitFRMADD(arrayPgsql, arrayOracle, apl, contrasenna));
                                            winPassAdd.hide();
                                        } else {
                                            mostrarMensaje(3, perfil.etiquetas.MsgErrorContrasena);
                                        }
                                    }
                                    else
                                        mostrarMensaje(3, perfil.etiquetas.MsgErrorCamposVacios);
                                },
                                text: perfil.etiquetas.lbBtnAceptar
                            }]
                    });
                    winPassAdd.add(frmContrasennaRol);
                    winPassAdd.doLayout();
                }
                Ext.getCmp('conf').reset();
                Ext.getCmp('pass').reset();
                if (nodeRoleChecked.attributes.pass == "") {                    
                    winPassAdd.setTitle(perfil.etiquetas.lbCampoContrasena + '\n' + nodeRoleChecked.attributes.text + '.');
                    winPassAdd.show();
                } else {
                    fpRegSistema.getForm().submit(ObjectSubmitFRMADD(arrayPgsql, arrayOracle, apl, nodeRoleChecked.attributes.pass));
                }
            } else {
                mostrarMensaje(3, perfil.etiquetas.MsgErrorConex);
            }
        } else if (nodeRoleChecked == null && nodeBDChecked == null) {
            fpRegSistema.getForm().submit(ObjectSubmitFRMADD(arrayPgsql, arrayOracle, apl, ""));
        }
        else {
            mostrarMensaje(3, perfil.etiquetas.MsgErrorConf);
        }
    }

    function ObjectSubmitFRMADD(arrayPgsql, arrayOracle, apl, pass) {

        if (nodeRoleChecked != null && nodeBDChecked != null) {

            return {
                url: 'insertarsistema',
                params: {
                    idpadre: id_padre,
                    pgsql: Ext.encode(arrayPgsql),
                    oracle: Ext.encode(arrayOracle),
                    rolepassword: pass,
                    nombrerole: nodeRoleChecked.attributes.text,
                    ip: nodeRoleChecked.attributes.ipgestorbd,
                    gestor: nodeRoleChecked.attributes.gestor,
                    puerto: nodeRoleChecked.attributes.puerto,
                    bd: nodeBDChecked.attributes.namebd,
                    createXML: true
                },
                waitMsg: perfil.etiquetas.lbMsgEsperaRegSist,
                failure: function(form, action) {
                    if (action.result.codMsg != 3) {
                        arbolSistema.getRootNode().reload();
                        arbolConex.getRootNode().reload();
                        fpRegSistema.getForm().reset();
                        if (!apl)
                            winIns.hide();
                    }
                }
            };
        }
        else {
            return {
                url: 'insertarsistema',
                params: {
                    idpadre: id_padre,
                    pgsql: Ext.encode(arrayPgsql),
                    oracle: Ext.encode(arrayOracle),
                    createXML: false
                },
                waitMsg: perfil.etiquetas.lbMsgEsperaRegSist,
                failure: function(form, action) {
                    if (action.result.codMsg != 3) {
                        arbolSistema.getRootNode().reload();
                        arbolConex.getRootNode().reload();
                        fpRegSistema.getForm().reset();
                        if (!apl)
                            winIns.hide();
                    }
                }
            };
        }
    }
    function ObjectSubmitFRMUPD(arrayPgsql, arrayOracle, pass, arregloDeschequeados) {

        if (nodeRoleChecked != null && nodeBDChecked != null) {
            return {
                url: 'modificarsistema',
                waitMsg: perfil.etiquetas.lbMsgEsperaModSist,
                params: {
                    idsistema: arbolSistema.getSelectionModel().getSelectedNode().id,
                    pgsql: Ext.encode(arrayPgsql),
                    oracle: Ext.encode(arrayOracle),
                    esquemasEliminados: Ext.encode(arregloDeschequeados),
                    rolepassword: pass,
                    nombrerole: nodeRoleChecked.attributes.text,
                    ip: nodeRoleChecked.attributes.ipgestorbd,
                    gestor: nodeRoleChecked.attributes.gestor,
                    puerto: nodeRoleChecked.attributes.puerto,
                    bd: nodeBDChecked.attributes.namebd,
                    createXML: true
                },
                failure: function(form, action) {
                    if (action.result.codMsg != 3) {
                        fpRegSistema.getForm().reset();
                        winMod.hide();
                        arbolSistema.getRootNode().reload();
                        btnModificar.disable();
                        btnEliminar.disable();
                        auxIns = false;
                        auxMod = false;
                        auxDel = false;
                        auxImp = false;
                        auxExp = false;
                    }
                }
            };
        } else {
            return {
                url: 'modificarsistema',
                waitMsg: perfil.etiquetas.lbMsgEsperaModSist,
                params: {
                    idsistema: arbolSistema.getSelectionModel().getSelectedNode().id,
                    pgsql: Ext.encode(arrayPgsql),
                    oracle: Ext.encode(arrayOracle),
                    systemRelatedWithRole: systemRelatedWithRole,
                    createXML: false,
                    esquemasEliminados: Ext.encode(arregloDeschequeados)
                },
                failure: function(form, action) {
                    if (action.result.codMsg != 3) {
                        fpRegSistema.getForm().reset();
                        winMod.hide();
                        arbolSistema.getRootNode().reload();
                        btnModificar.disable();
                        btnEliminar.disable();
                        auxIns = false;
                        auxMod = false;
                        auxDel = false;
                        auxImp = false;
                        auxExp = false;
                    }
                }
            };
        }
    }

    ////------------ Adicionar Sistema ------------////
    function adicionarSistema(apl) {
        if (fpRegSistema.getForm().isValid()) {
            var arrayNodos = arbolConex.getChecked();
            var arrayPgsql = [];
            var arrayOracle = [];
            for (var i = 0; i < arrayNodos.length; i++) {
                if (arrayNodos[i].attributes.lenguaje == "pgsql") {
                    arrayPgsql[i] = [];
                    arrayPgsql[i].push(idrolbdOnclick);
                    arrayPgsql[i].push(arrayNodos[i].parentNode.attributes.idgestor);
                    arrayPgsql[i].push(arrayNodos[i].parentNode.attributes.namebd);
                    arrayPgsql[i].push(arrayNodos[i].attributes.text);
                    arrayPgsql[i].push(arrayNodos[i].parentNode.attributes.idservidor);
                }
                else if (arrayNodos[i].attributes.lenguaje == "Oracle") {
                    arrayOracle[i] = [];
                    arrayOracle[i].push(arrayNodos[i].attributes.idrolbd);
                    arrayOracle[i].push(arrayNodos[i].parentNode.attributes.idgestor);
                    arrayOracle[i].push(arrayNodos[i].parentNode.attributes.namebd);
                    arrayOracle[i].push(arrayNodos[i].attributes.text);
                    arrayOracle[i].push(arrayNodos[i].parentNode.attributes.idservidor);
                }
            }
            levantarVentanaAdicionar(arrayPgsql, arrayOracle, apl);
        }
        else
            mostrarMensaje(3, perfil.etiquetas.lbMsgErrorEnCamops);
        //         ReiniciarVariables()
    }

    function levantarVentanaModificar(arrayPgsql, arrayOracle, arregloDeschequeados) {
        if (nodeRoleChecked != null && nodeBDChecked != null) {
            if (nodeRoleChecked.attributes.ipgestorbd == nodeBDChecked.attributes.ipgestorbd &&
                    nodeRoleChecked.attributes.gestor == nodeBDChecked.attributes.gestor &&
                    nodeRoleChecked.attributes.puerto == nodeBDChecked.attributes.puerto) {
                if (!winPassMod) {
                    winPassMod = new Ext.Window({
                        modal: true,
                        closeAction: 'hide',
                        layout: 'fit',
                        title: perfil.etiquetas.lbCampoContrasena + '\n' + nodeRoleChecked.attributes.text + '.',
                        width: 290,
                        height: 150,
                        resizable: false,
                        buttons: [{
                                icon: perfil.dirImg + 'cancelar.png',
                                iconCls: 'btn',
                                id: 'can',
                                text: perfil.etiquetas.lbBtnCancelar,
                                handler: function() {
                                    winPassMod.hide();
                                }
                            }, {
                                icon: perfil.dirImg + 'aceptar.png',
                                iconCls: 'btn',
                                handler: function() {
                                    if (frmContrasennaRol.getForm().isValid()) {
                                        var contrasenna = Ext.getCmp('conf').getValue();
                                        var confirmacion = Ext.getCmp('pass').getValue();
                                        if (contrasenna == confirmacion) {
                                            fpRegSistema.getForm().submit(ObjectSubmitFRMUPD(arrayPgsql, arrayOracle, contrasenna, arregloDeschequeados));
                                            winPassMod.hide();
                                        } else {
                                            mostrarMensaje(3, perfil.etiquetas.MsgErrorContrasena);
                                        }
                                    }
                                    else
                                        mostrarMensaje(3, perfil.etiquetas.MsgErrorCamposVacios);
                                },
                                text: perfil.etiquetas.lbBtnAceptar
                            }]
                    });
                    winPassMod.add(frmContrasennaRol);
                    winPassMod.doLayout();
                }
                Ext.getCmp('conf').reset();
                Ext.getCmp('pass').reset();
                if (nodeRoleChecked.attributes.pass == "") {
                    winPassMod.setTitle(perfil.etiquetas.lbCampoContrasena + '\n' + nodeRoleChecked.attributes.text + '.');
                    winPassMod.show();
                } else {
                    fpRegSistema.getForm().submit(ObjectSubmitFRMUPD(arrayPgsql, arrayOracle, nodeRoleChecked.attributes.pass, arregloDeschequeados));
                }
            } else {
                mostrarMensaje(3, perfil.etiquetas.MsgErrorConex);
            }
        } else if (nodeRoleChecked == null && nodeBDChecked == null) {
            fpRegSistema.getForm().submit(ObjectSubmitFRMUPD(arrayPgsql, arrayOracle, "", arregloDeschequeados));
        }
        else {
            mostrarMensaje(3, perfil.etiquetas.MsgErrorConf);
        }

    }

    ////------------ Modificar sistema ------------////
    function modificarSistema() {
        if (fpRegSistema.getForm().isValid()) {
            var arrayNodos = arbolConex.getChecked();
            //console.log(arrayNodos[0].parentNode.attributes);
            arrayPgsql = [];
            arrayOracle = [];
            for (i = 0; i < arrayNodos.length; i++) {
                if (arrayNodos[i].attributes.lenguaje == "pgsql") {
                    arrayPgsql[i] = [];
                    arrayPgsql[i].push(idrolbdOnclick);
                    arrayPgsql[i].push(arrayNodos[i].parentNode.attributes.idgestor);
                    arrayPgsql[i].push(arrayNodos[i].parentNode.attributes.namebd);
                    arrayPgsql[i].push(arrayNodos[i].attributes.text);
                    arrayPgsql[i].push(arrayNodos[i].parentNode.attributes.idservidor);
                }

                else if (arrayNodos[i].attributes.lenguaje == "Oracle") {
                    arrayOracle[i] = [];
                    arrayOracle[i].push(arrayNodos[i].attributes.idrolbd);
                    arrayOracle[i].push(arrayNodos[i].parentNode.attributes.idgestor);
                    arrayOracle[i].push(arrayNodos[i].parentNode.attributes.namebd);
                    arrayOracle[i].push(arrayNodos[i].attributes.text);
                    arrayOracle[i].push(arrayNodos[i].parentNode.attributes.idservidor);
                }
            }

            if (denominacion == Ext.getCmp('denominacion').getValue() &&
                    abreviatura == Ext.getCmp('abreviatura').getValue() &&
                    servidorweb == Ext.getCmp('servidorweb').getValue() &&
                    descripcion == Ext.getCmp('descripcion').getValue() &&
                    icono == Ext.getCmp('icono').getValue() && RoleUnChecked.length == 0 && RoleChecked.length == 0
                    && BDUnChecked.length == 0
                    && BDChecked.length == 0 && esquemaChecked == false) {
                mostrarMensaje(3, perfil.etiquetas.NoModify);
            } else
                levantarVentanaModificar(arrayPgsql, arrayOracle, arregloDeschequeados)
        }
        else
            mostrarMensaje(3, perfil.etiquetas.lbMsgErrorEnCamops);
        arrayPgsql = [];
        arrayOracle = [];
        arregloDeschequeados = [];
        ReiniciarVariables()
    }

    function ReiniciarVariables() {
        idrolbdOnclick = 0;
    }


    ////------------------- Eliminar Sistema ------------------------////
    function eliminarSistema() {
        mostrarMensaje(2, perfil.etiquetas.lbMsgDeseaEliminar, elimina);
        function elimina(btnPresionado)
        {
            if (btnPresionado == 'ok')
            {
                Ext.Ajax.request({
                    url: 'eliminarsistema',
                    method: 'POST',
                    params: {idsistema: arbolSistema.getSelectionModel().getSelectedNode().attributes.id},
                    callback: function(options, success, response) {
                        responseData = Ext.decode(response.responseText);
                        if (responseData.codMsg == 1)
                        {
                            arbolSistema.getRootNode().reload();
                            btnEliminar.disable();
                            sistemas = null;
                            btnModificar.disable();
                            btnAdicionar.disable();
                            btnExportar.disable();
                            btnImportar.disable();
                            btnAdicionar.disable();
                            auxIns = false;
                            auxMod = false;
                            auxDel = false;
                            auxImp = false;
                            auxExp = false;
                        }
                    }
                });
            }
        }
    }
////------------------- Exportar Sistema ------------------------////
    function exportarSistema()
    {
        auxIns3 = false;
        auxMod3 = false;
        auxDel3 = false;
        auxExp3 = false;
        auxImp3 = false;
        document.getElementById('idsistema').value = arbolSistema.getSelectionModel().getSelectedNode().attributes.id;
        var formexport = document.getElementById('exportarsistemas');        
        formexport.method = 'POST';
        formexport.target = '_blank';
        formexport.action = 'exportarsistema';
        formexport.submit();
        auxIns3 = true;
        auxMod3 = true;
        auxDel3 = true;
        auxExp3 = true;
        auxImp3 = true;
    }
    ////------------------- Importar Sistema ------------------------////
    function importarSistema()
    {
        fpbuscar.getForm().submit({
            url: 'importarXML',
            waitMsg: perfil.etiquetas.lbMsgEsperaImpSist,
            params: {idsistema: arbolSistema.getSelectionModel().getSelectedNode().attributes.id},
            failure: function(form, action) {
                if (action.result.codMsg == 1) {
                   // mostrarMensaje(action.result.codMsg, perfil.etiquetas.MsgInfImportar);
                    winImp.hide();
                    if (arbolSistema.getSelectionModel().getSelectedNode().attributes.id == 0)
                        arbolSistema.getSelectionModel().getSelectedNode().reload();
                    else
                        arbolSistema.getSelectionModel().getSelectedNode().parentNode.reload();
                } 
//                else {
//                    if (action.result.codMsg == 3) {
//                        //mostrarMensaje(action.result.codMsg, action.result.mensaje);
//                    }
//                }
            }
        });
    }

}
