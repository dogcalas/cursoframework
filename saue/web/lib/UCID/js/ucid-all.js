
var UCID = new Object();
var winError;
var acciones_reportes = [];
var msgArray = new Array();
var msgArrayText = new Array();
UCID.portal = new Object();
if (Ext) {

    Ext.BLANK_IMAGE_URL = '/lib/ExtJS/temas/default/images/s.gif';

    if (Ext.version === '2.2') {
        //idioma base para ext 2.2
        if (window.parent.perfil)
            importarJS('/lib/idiomas/2.2/' + window.parent.perfil.idioma + '.js');

        if (window.perfil)
            importarJS('/lib/idiomas/2.2/' + window.perfil.idioma + '.js');
        Ext.layout.FormLayout.prototype.labelSeparator = '';

        Ext.form.TextField.superclass.initComponent = function () {

            if (this.allowBlank === false) {
                this.labelSeparator = '<b style="color:red;font-size:12px"> *</b>';
            }

        };
    } else {
        //idioma base para ext 4.2
        if (window.parent.perfil)
            importarJS('/lib/idiomas/4.2/' + window.parent.perfil.idioma + '.js');

        if (window.perfil)
            importarJS('/lib/idiomas/4.2/' + window.perfil.idioma + '.js');

        Ext.form.field.Base.override({
            constructor: function () {


                this.callParent(arguments);
                if (this.allowBlank === false)
                    this.labelSeparator = '<b style="color:red;font-size:12px"> *</b>';
            }
        });
        Ext.grid.Panel.override({
            constructor: function (config) {
                var me = this;
                this.callParent(arguments);
                var stcbpaginado = new Ext.data.SimpleStore({
                    fields: ['idnumpag', 'dennumpag'],
                    data: [
                        [10, '10'],
                        [20, '20'],
                        [40, '40'],
                        [80, '80']
                    ]
                });

                var cbpaginado = new Ext.form.ComboBox({
                    value: 10,
                    store: stcbpaginado,
                    displayField: 'dennumpag',
                    margin: '5 5 5 5',
                    typeAhead: true,
                    dock: 'right',
                    mode: 'local',
                    forceSelection: true,
                    triggerAction: 'all',
                    selectOnFocus: true,
                    editable: false,
                    hiddenName: 'idnumpag',
                    valueField: 'idnumpag',
                    width: 50,
                    listeners: {
                        select: function (cb, record, index) {
                            me.store.getProxy().extraParams = {limit: cb.value};
                            me.store.pageSize = cb.value;
                            me.store.load();
                        }
                    }
                });


                if (config && config.paginate == true && this.query('pagingtoolbar')[0]) {
                    this.query('pagingtoolbar')[0].add('-');
                    this.query('pagingtoolbar')[0].add(cbpaginado);
                    this.query('pagingtoolbar')[0].doLayout();
                }
            }
        })
    }


    //Ext.WindowMgr.zseed = 50000;


    if (window.parent.UCID.portal.dir_ext) {
        var existLink = false;
        var linkArr = document.getElementsByTagName("link");
        var port = '';
        if (window.location.port)
            port = ':' + window.location.port;
        var dirHostIconCSS = window.location.protocol + '//' + window.location.host + port + window.parent.UCID.portal.perfil.dirIconCSS;
        var dirHostnameIconCSS = window.location.protocol + '//' + window.location.hostname + port + window.parent.UCID.portal.perfil.dirIconCSS;
        for (var i = 0; i < linkArr.length; i++) {
            if (linkArr[i].href === dirHostIconCSS || linkArr[i].href === dirHostnameIconCSS) {
                existLink = true;
                break;
            }
        }
        if (!existLink)
            importarCSS(window.parent.UCID.portal.perfil.dirIconCSS);
    }


    // Control de excepciones (No se puede implementar hatsta que no haya gestion de excepciones no controladas)

    UCID.validarJson = function (respText) {
        var isjson = false;
        var contIz = 0;
        var contDer = 0;
        var primero = true;
        var aceptacion = false;
        for (var i = 0; i < respText.length; i++) {
            switch (respText.charAt(i)) {
                case '{':
                {
                    contIz++;
                    break;
                }
                case '}':
                {
                    contDer++;
                    break;
                }
            }
            if (respText.charAt(i) === '{' && primero) {
                aceptacion = true;
                primero = false;
            }
            if (respText.charAt(i) === '}' && primero) {
                aceptacion = false;
                primero = false;
            }


        }
        if (aceptacion === true) {
            if (contIz === contDer) {
                isjson = true;
            }
        } else
            isjson = false;

        return isjson;

    }


    Ext.Ajax.on('requestcomplete', function (conn, response, options) {
        var respText = response.responseText;
        var respXML = response.responseXML;

        var isjson = UCID.validarJson(respText);

        if (respText && !respXML && isjson) {
            var respObj = Ext.decode(respText);

            if (respObj.codMsg && respObj.codMsg >= 1 && respObj.codMsg <= 4) {

                if (Ext.version === '2.2') {
                    setTimeout(function () {
                        mostrarMensaje(respObj.codMsg, respObj.mensaje, null, respObj.detalles);
                    }, 100);
                } else
                    Ext.defer(mostrarMensaje, 100, this, [respObj.codMsg, respObj.mensaje, null, respObj.detalles]);

            }

        }

        if (options.params && options.params.isWorkflowTask) {
            if (options.url === options.params.accion && options.params.executionId) {
                window.parent.MyDesktop.getNotifications().findAndDeleteTaskByExecutionId(options.params.executionId);
            }
        }
    });


    UCID.portal.cargarEtiquetas = function (vistaCU, fn) {
        Ext.Ajax.request({
            url: 'cargaretiquetas',
            method: 'POST',
            params: {
                vista: vistaCU
            },
            callback: function (options, success, response) {
                if (success) {
                    perfil.etiquetas = Ext.decode(response.responseText);
                    var codMsg = perfil.etiquetas.codMsg;
                    if (!codMsg && fn)
                        fn();
                }
            }
        });
        perfil = clonarObjeto(perfil);
    };

    //yriverog
    Ext.Ajax.on('beforerequest', function (connection, options) {
        var pn = window.location.pathname;
        var _pieces = pn.split('/');
        var _length = _pieces.length;
        var controller = _pieces[_length - 1];
        var systemId = _pieces[_length - 2];
        if (window.parent.WF && window.parent.WF.WorkflowNotifications) {
            var notifications = window.parent.WF.WorkflowNotifications.prototype;
            var br = notifications.find({
                systemId: systemId,
                controller: controller
            });
            var found = br !== -1;
            if (found) {
                Ext.apply(options.params, br.data.workflowControlVars, br.data.zendActionVars);
                Ext.apply(options.params, {
                    isWorkflowTask: true
                });
            }
        }
    });


    clonarObjeto = function (o) {
        return eval(uneval(o));
    };

    // Funcion para mostrar un mensaje


    mostrarMensaje = function (tipo, msg, fn, detalle) {
        Sauxe.Msg.mensaje(tipo, msg, detalle, fn);
    };


    /*************************  MENSAJERIA NUEVA PARA SAUXE  ************************/
        //BEGIN MENSAJERIA
    Ext.ns('Sauxe.Msg');
    Sauxe.Msg = {

        mensaje: function (type, msg, detalle, fn) {

            switch (type) {
                case 1:
                {
                    this.information(msg);
                    break;
                }
                case 2:
                {
                    this.confirmation(msg, fn);
                    break;
                }
                case 3:
                {
                    this.error(msg, detalle, fn);
                    break;
                }


            }

        },
        //mensaje de Error Ext JS 4 y Ext JS 2.2
        error: function (msg, detalle, fn) {
            var m3;

            if (Ext.version == '2.2') {

                if (detalle) {
                    m3 = new Ext.Window({
                        width: 400,
                        resizable: false,
                        //buttonAlign:'center',
                        autoHeight: true,
                        modal: true,
                        closeAction: 'destroy',
                        cls: Ext.MessageBox.ERROR,
                        bodyBorder: false,
                        layout: 'fit',
                        frame: true,
                        defaults: {frame: true, border: false},
                        items: new Ext.Panel({
                            frame: true, monitorResize: true,
                            autoHeight: true, layout: 'column',
                            bodyStyle: 'background:red !important;',
                            items: [
                                {columnWidth: .15,
                                    items: {style: 'margin:2px 5px 10px 5px;',
                                        html: '<div  class="' + Ext.MessageBox.ERROR + '" style="width:40px;height:40px;"></div>',
                                        cls: 'x-window-dlg',
                                        width: 40,
                                        height: 40
                                    }
                                },
                                {columnWidth: .8,
                                    items: {xtype: 'panel',
                                        autoHeight: true,
                                        margin: '5 5 5 10',
                                        html: '<div >' + msg + '</div>'}
                                },
                                {columnWidth: 1,
                                    items: new Ext.form.FieldSet({
                                        autoScroll: true,
                                        layout: 'form', title: ' Detalles : ',
                                        autoWidth: true, html: '<div >' + detalle + '</div>',
                                        autoHeight: true, collapsed: true, collapsible: true
                                    })
                                }
                            ]
                        }),
                        buttons: [
                            {text: Ext.MessageBox.buttonText.ok,
                                handler: function () {
                                    m3.close();
                                    if (typeof(fn) == 'function')fn();
                                }
                            }
                        ]
                    });
                    m3.show();
                } else {
                    m3 = Ext.MessageBox.show({
                        msg: msg, buttons: Ext.MessageBox.OK,
                        icon: Ext.MessageBox.ERROR,
                        fn: fn
                    });
                }


            } else {

                m3 = Ext.MessageBox.show({
                    msg: msg, buttons: Ext.MessageBox.OK,
                    icon: Ext.MessageBox.ERROR,
                    fn: fn
                });


            }

            if (detalle) {
                if (Ext.version != '2.2') {

                    var setF = new Ext.form.FieldSet({
                        autoScroll: true, title: 'Detalles : ',
                        autoWidth: true,
                        autoHeight: true,
                        collapsible: true, collapsed: false, id: 'details', html: detalle
                    });
                    m3.add(setF);
                    m3.doLayout();
                    setF.collapse();
                    m3.on('beforeclose', function () {
                        m3.remove('details');
                    });
                }

            }


        },
        //mensaje de informacion Ext JS 4 y Ext JS 2.2
        information: function (msg) {
            var f = Ext.DomHelper.append(Ext.getBody(), {
                tag: 'div'
            }, true);

            var ancho = Ext.getBody();

            Ext.DomHelper.applyStyles(f, {
                'width': '250px', 'z-index': '1000000',
                'background': 'black', 'border-radius': '5px', 'color': 'white',top:'10px'
            });
            f.setOpacity(0.75);
            if (Ext.version == '2.2') {
                f.addClass('x-panel-header');
                f.addClass('x-unselectable');
                f.addClass('x-panel-header-text');
            }

           f.position('absolute', 1000000, ancho.getWidth(true)-255, 0);
            var im = Ext.DomHelper.append(f, {
                tag: 'div',
                html: '<div class="' + Ext.MessageBox.INFO + '" style="width:40px;height:40px; float:left;margin:5px;"></div>',
                cls: 'x-window-dlg'
            }, true);


            var tex = Ext.DomHelper.append(f, {
                tag: 'div',
                html: msg


            }, true);

            Ext.DomHelper.applyStyles(tex, {
                'font': 'bold', 'margin': '10px', 'float': 'rigth'
            });

            msgArray.push(f);
            msgArrayText.push(msg);
            var suma = 0;
            for (var i = 0; i < msgArray.length; i++) {
                suma += msgArray[i].getHeight() + 2;
            }

            setTimeout(function () {
                f.shift({   y: f.getY() + suma, callback: function () {
                }
                });

            }, 200);

            setTimeout(function () {
                f.shift({   y: f.getY() - (suma+2), callback: function () {
                    f.remove();
                    msgArray.splice(
                        msgArray.indexOf(f), 1);
                    msgArrayText.
                        splice(msgArrayText.indexOf(msg), 1);
                } });

            }, 8000);
        },
        //mensaje de confirmacion Ext JS 4 y Ext JS 2.2
        confirmation: function (msg, fn) {
            var m2 = Ext.MessageBox.show({
                msg: msg, buttons: Ext.MessageBox.OKCANCEL,
                icon: Ext.MessageBox.QUESTION, fn: fn
            });


        }

    }
    //END MENSAJERIA


    UCID.portal.cargarAcciones = function (idFuncionalidad, fn) {
        Ext.Ajax.request({
            url: 'cargaracciones',
            method: 'POST',
            params: {idfuncionalidad: idFuncionalidad},
            callback: function (options, success, response) {
                if (success) {

                    acciones_reportes = Ext.decode(response.responseText);
                    var codMsg = acciones_reportes.codMsg;
                    if (!codMsg) {
                        for (i in acciones_reportes) {
                            if (i !== 'remove' && Ext.getCmp(acciones_reportes[i].abreviatura)) {
                                if (Ext.getCmp(acciones_reportes[i].abreviatura).getXType() === 'datefield' || Ext.getCmp(acciones_reportes[i].abreviatura).getXType() === 'textfield')
                                    Ext.getCmp(acciones_reportes[i].abreviatura).enable();
                                else
                                    Ext.getCmp(acciones_reportes[i].abreviatura).show();
                            }
                        }
                        if (fn)
                            fn();
                    }
                }
            }
        });
    };


    UCID.portal.getAccionesReportes = function () {
        return acciones_reportes;
    };
    Ext.Ajax.on('requestexception', function (conn, response, options) {
        var answer = Ext.decode(response.responseText);
        Ext.Msg.show({
            title: 'Error',
            msg: answer.msg,
            buttons: Ext.MessageBox.OK,
            fn: function () {
                location.reload(true);
            },
            icon: Ext.MessageBox.ERROR});
    }, this);
}//Endofif
