
//Variables de config general
var tpCargaInicial, WinCargaInicial, idFuncionalidad, objDesk;
var arrayItems, contadorTab = 0, ejecutarFunc = null;
// Agrego al objeto portal la direccion del framework ExtJS
UCID.portal.dir_ext_css = document.getElementById('dir_ext_css').value;
// Creo un objeto para guardar el perfil
var perfil = new Object();
var perfilPortal = new Object();
var winSelecEntidad, winPerfilUsuario, tbDesktop, tbHriztal, winPass;
var newAccess = false;
var iddominio = null;
var entidad;
var ventanas = Ext.WindowGroup({});
//Funcion para cargar el XML de configuracion del tab panel para la carga inicial
function CargarDatosXML() {
    Ext.Ajax.request({
        url: 'cargardatostabpanel',
        callback: function(options, success, response) {
            if (response.responseXML) {
                xmlObj = response.responseXML.documentElement;
            }
            //Se importa el fichero JS que pintará una interfaz u otra en dependencia del perfil del usuario
            else
            {
                importarJS('../../views/js/portal/' + perfil.portal + '.js');
                if (perfil.portal == 'standardarbol' || perfil.portal == 'comercial')
                {
                  //  Ext.get('ux-taskbar').remove();
                    //Ext.get('x-desktop').remove();
                    Ext.get('center').remove();

                }

                if (perfil.portal == 'desktopaction')
                {
                    //Ext.get('comercialBody').remove();


                }
            }
        }
    });
}
//Funcion para cargar el perfil del usuario. La primera que se llama para comenzar la carga de la configuracion
UCID.portal.cargarPerfil = function() {
    Ext.Ajax.request({
        url: 'cargarperfil',
        method: 'POST',
        callback: function(options, success, response) {
            if (success) {

                //Variable de configuracion con el perfil del usuario
                UCID.portal.perfil = Ext.decode(response.responseText);
                //Variable de configuracion con la direccion de los iconos
                UCID.portal.perfil.dirImg = UCID.portal.dir_ext_css + 'icons/';
                //Variable de configuracion con la dirección de la CSS de EXT ext-all.css
                UCID.portal.perfil.dirCss = UCID.portal.dir_ext_css + 'css/ext-all.css';
                //Se crea una variable de configuracion con el perfil
                perfil = UCID.portal.perfil;
                //Se manda a cargar las etiquetas del portal y luego se ejecuta la funcion CargarDatosXML
                cargarEtiquetasPortal('portal', function() {
                    CargarDatosXML();
                });
                
                ComponenteNotificaciones.connect();
                
            } else {
                mostrarMensaje(3, response.responseText);
            }
        }
    });
}

//Inmediatamente se llama la funcion UCID.portal.cargarPerfil
UCID.portal.cargarPerfil();
//Funcion para cargar las etiquetas del portal según la configuración del idioma
function cargarEtiquetasPortal(vistaCU, fn) {
    Ext.Ajax.request({
        url: 'cargaretiquetas',
        method: 'POST',
        params: {vista: vistaCU},
        callback: function(options, success, response) {
            if (success) {
                perfilPortal.etiquetas = Ext.decode(response.responseText);
                colocarEntidad(UCID.portal.perfil.entidad, (perfil.portal == 'standardarbol') ? 'entidadStAccedida' : 'entidadAccedida', (perfil.portal == 'standardarbol') ? '5px 0px 0px 5px;' : '28px 0px 0px 38px;', 20);
                entidad = UCID.portal.perfil.entidad;
                definirBotonesBarras();
                var codMsg = perfilPortal.etiquetas.codMsg;
                if (!codMsg)
                    fn();
            }
        }
    });
}
//Funcion que es llamada para colocar un div con el nombre de la entidad seleccionada.
function colocarEntidad(aentidad, st, aMargin, aFont) {
    if (document.getElementById(st)) {
        aDiv = document.getElementById(st);
        aDiv.style.margin = aMargin;
        aDiv.style.fontSize = aFont;
        aDiv.style.visibility = 'visible';
        aDiv.innerHTML = aentidad;
    }
}
//Funcion que permite incluir un fichero .js de forma dinámica
function importarJS(dirJS) {
    var js_file = document.createElement("script");
    js_file.setAttribute("language", "javascript");
    js_file.setAttribute("type", "text/javascript");
    js_file.setAttribute("src", dirJS);
    document.getElementsByTagName("head")[0].appendChild(js_file);
}
//Funcion que se va a llamar para cerrar la sesión de un usuario autenticado
function cerrarSesion() {
    var txtUser = (perfil.alias) ? perfil.alias : perfil.usuario;
    // ventanas.hideAll();
    mostrarMensaje(2, '¿' + perfilPortal.etiquetas.lbMsgCerrarSesion + txtUser + '?', okCierra)
    function okCierra(btnPresionado) {
        if (btnPresionado == 'ok') {
            Ext.getBody().mask('Por favor espere. Tramitando el cierre de la sesi&oacute;n ...');
            Ext.Ajax.request({
                url: 'closeportal',
                callback: function(options, success, response) {
                    Ext.getBody().unmask();
                    responseData = Ext.decode(response.responseText);
                    if (responseData.codMsg)
                        mostrarMensaje(responseData.codMsg, responseData.mensaje);
                    else if (responseData.close) {
                        window.parent.focus();
                        window.opener.parent.location.reload();
                        window.close();
                    }
                }
            });
        }
    }
}
//Definición del formulario para seleccionar si quiero cambiar de usuario o entidad

var storeA = Ext.create('Ext.data.TreeStore', {
    fields: ['id', 'idsistema', 'text', 'leaf', 'tiene', 'idfuncionalidad'],
    idProperty: 'id',
    proxy: {
        type: 'ajax',
        url: 'cargardominio',
        actionMethods: { //Esta Linea es necesaria para el metodo de llamada POST o GET

            read: 'POST'
        },
        reader: {
            type: 'json'

        }
    }
});

FormIndex = new Ext.FormPanel({
    formId: 'FormIndex',
    frame: true,
    bodyStyle: 'padding:5px 5px 0',
    width: 400,
    style: 'text-align:left',
    autoHeight: 300,
    items: [new Ext.tree.TreePanel({
            autoScroll: true,
            frame: false,
            height: 200,
            bodyStyle: 'background-color:#FFFFFF;',
            store: storeA,
            rootVisible: true,
            root: {expanded: true, text: "Dominio de entidades", id: '0'},
            listeners: {
                itemclick: function(nodo, rec, item, index, e) {
                    if (nodo.id != 0) {
                        Ext.getCmp('btnAceptarCambio').enable();
                        iddominio = nodo.id;
                    } else {
                        if (!newAccess)
                            Ext.getCmp('btnAceptarCambio').disable();
                        iddominio = null;
                    }
                }

            }

        }),
        /*	{
         xtype: 'treepanel',
         autoScroll:true,
         frame:false,
         height:200,
         bodyStyle:'background-color:#FFFFFF;',
         listeners:{
         'click':function(nodo){
         if(nodo.id != 0){
         Ext.getCmp('btnAceptarCambio').enable();
         iddominio = nodo.id;
         } else {
         if (!newAccess)
         Ext.getCmp('btnAceptarCambio').disable();
         iddominio = null;
         }
         }},
         root:new Ext.tree.AsyncTreeNode({
         text: 'Dominio de entidades',
         id:'0'
         }),
         loader: new Ext.tree.TreeLoader({
         dataUrl:'cargardominio',
         preloadChildren :false,
         baseParams:{}
         })
         },*/
        {
            xtype: 'fieldset',
            style: 'margin-top:10px;',
            title: 'Acceder al sistema',
            autoHeight: true,
            items: [{
                    xtype: 'radio',
                    hideLabel: true,
                    boxLabel: 'Entrar con el usuario autenticado',
                    name: 'tipoacceso',
                    inputValue: 'ultimo',
                    checked: true,
                    listeners: {
                        'check': function(obj, ischeck) {
                            if (ischeck) {
                                if (iddominio == null)
                                    Ext.getCmp('btnAceptarCambio').disable();
                                newAccess = false;
                            }
                        }
                    }
                }, {
                    xtype: 'radio',
                    hideLabel: true,
                    boxLabel: 'Entrar con un usuario diferente',
                    name: 'tipoacceso',
                    inputValue: 'nuevo',
                    listeners: {
                        'check': function(obj, ischeck) {
                            if (ischeck) {
                                Ext.getCmp('btnAceptarCambio').enable();
                                newAccess = true;
                            }
                        }
                    }
                }]
        }
    ]
});
// Funcion para mostrar la ventana para cambiar el usuario o la entidad
mostrarWinSelectEntidad = function() {
    if (!winSelecEntidad) {
        winSelecEntidad = new Ext.Window({modal: true, closeAction: 'hide', layout: 'fit', width: 400,
            title: 'Modificar entrada', autoHeight: true, items: FormIndex,
            buttons: [{
                    text: 'Aceptar',
                    disabled: true,
                    id: 'btnAceptarCambio',
                    disabled:true,
                            handler: function() {
                                entrarAlSistema();
                            }
                }, {
                    text: 'Cancelar',
                    handler: function() {
                        winSelecEntidad.hide();
                    }
                }]
        });
    }
    winSelecEntidad.show(Ext.getBody());
}
//Funcion para acceder al sistema una vez seleccionada la entidad
function entrarAlSistema() {
    FormIndex.getForm().submit({
        url: 'entraralsistema',
        waitMsg: 'Tramitando entrada ...',
        params: {dominio: iddominio},
        failure: function(form, action) {
            if (action.result.codMsg)
                mostrarMensaje(action.result.codMsg, action.result.mensaje);
            else if (action.result.reload == false) {
                window.parent.focus();
                window.opener.parent.location.reload();
                window.close();
            }
            else if (action.result.reload == true) {
                winSelecEntidad.hide();
                window.opener.parent.location.reload();
                window.location.reload();
            }
        }
    });
}

//Definicion de las barras de herramientas para un desktop u otro
function definirBotonesBarras() {
    var tEntidad = new Ext.form.Label({
        cls: 'tEntidad',
        text: perfil.entidad
    });
    var txtUser = (perfil.alias) ? perfil.alias : perfil.usuario;
    tbDesktop = [{iconCls: 'btn', icon: perfil.dirImg + 'ayuda.png', text: perfilPortal.etiquetas.lbBtnAyuda, handler: function() {
            }, scope: this}, '-',
        {iconCls: 'btn', icon: perfil.dirImg + 'buscarclitepersona.png', text: perfilPortal.etiquetas.lbBtnCambiarpass, handler: function() {
                winPass.show();
            }, scope: this}, '-',
        {iconCls: 'btn', icon: perfil.dirImg + 'salir.png', text: perfilPortal.etiquetas.lbSalir, handler: function() {
                cerrarSesion();
            }, scope: this}];
    tbHriztal = [tEntidad,
        '->',
        {iconCls: 'btn', icon: perfil.dirImg + 'usuario.png', text: txtUser, id: 'selectnuevaentidad', handler: function() {
                mostrarWinSelectEntidad();
            }, scope: this},
        {iconCls: 'btn', icon: perfil.dirImg + 'buscarclitepersona.png', text: perfilPortal.etiquetas.lbBtnCambiarpass, handler: function() {
                winPass.show();
            }, scope: this},
        {iconCls: 'btn', icon: perfil.dirImg + 'salir.png', text: perfilPortal.etiquetas.lbSalir, handler: function() {
                cerrarSesion();
            }, scope: this}];
}
//*******Para carga inicial*******//
//Botones para el tabpanel
btnSiguiente = new Ext.Button({disabled: true, hidden: false, id: 'btnSiguiente', text: 'Siguiente', handler: function() {
        (ejecutarFunc != null) ? (ejecutarFunc()) ? cambiarTab('next') : alert('Por favor revise la entrada de datos que falta información.') : cambiarTab('next')
    }});
btnAnterior = new Ext.Button({disabled: true, hidden: true, id: 'btnAnterior', text: 'Anterior', handler: function() {
    }});
btnFinalizar = new Ext.Button({disabled: false, hidden: true, id: 'btnFinalizar', text: 'Finalizar', handler: function() {
        importarJS('../../views/js/portal/' + perfil.portal + '.js');
        WinCargaInicial.close();
    }});
//Funcion para llamar la ventana para la carga inicial
//Funcion que recibe el xml de configuración y devuelve un array de items en forma de panel
function dameItemsTP(xmlObj) {
    arrayItems = [];
    for (var i = 0; i < xmlObj.childNodes.length; i++) {
        if (xmlObj.childNodes[i].nodeName == "tab") {
            arrayItems.push({
                title: xmlObj.childNodes[i].getAttribute("title"),
                disabled: (i == 1) ? false : true,
                layout: 'fit',
                html: '<iframe id="' + xmlObj.childNodes[i].getAttribute("idiframe") + '" src="' + xmlObj.childNodes[i].getAttribute("funcionalidad") + '"; style="width:100%; height: 100%; border:none;"></iframe>',
                id: xmlObj.childNodes[i].getAttribute("idtab"),
                funcionalidad: xmlObj.childNodes[i].getAttribute("funcionalidad"),
                idiframe: xmlObj.childNodes[i].getAttribute("idiframe"),
                estado: xmlObj.childNodes[i].getAttribute("estado")
            });
        }
    }
    return arrayItems;
}
//Funcion para verificar el cambio de tab correctamente
function verificarCambioTab(tabPanel, tabNew, tabActual) {
    return true;
}
//Funcion para cambiar el estado del tab correctamente
var cambiarEstado = function(estado, fn) {
    ejecutarFunc = fn;
    (estado == 'si') ? btnSiguiente.enable() : btnSiguiente.disable();
}
//Funcion para cambiar de tab correctamente
var cambiarTab = function(direccion) {
    if (direccion == 'next') {
        tpCargaInicial.getComponent(contadorTab++).enable();
        tpCargaInicial.getComponent(contadorTab).enable();
        tpCargaInicial.setActiveTab(tpCargaInicial.getComponent(contadorTab));
        btnSiguiente.disable();
        if (contadorTab == arrayItems.length - 1) {
            btnFinalizar.show();
            btnSiguiente.hide();
        }
    }
}

getWinById = function(aid) {
    return (aid) ? objDesk.getWindow('win' + aid) : 0;
};

function removejscssfile(filename, filetype){
 var targetelement=(filetype=="js")? "script" : (filetype=="css")? "link" : "none" //determine element type to create nodelist from
 var targetattr=(filetype=="js")? "src" : (filetype=="css")? "href" : "none" //determine corresponding attribute to test for
 var allsuspects=document.getElementsByTagName(targetelement)
 for (var i=allsuspects.length; i>=0; i--){ //search backwards within nodelist for matching elements to remove
  if (allsuspects[i] && allsuspects[i].getAttribute(targetattr)!=null && allsuspects[i].getAttribute(targetattr).indexOf(filename)!=-1)
   allsuspects[i].parentNode.removeChild(allsuspects[i]) //remove element by calling parentNode.removeChild()
 }
}
