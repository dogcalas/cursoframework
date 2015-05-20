/*
 * Componente para la instalación
 *
 * Interfaz de Instalacion.
 *
 * @author Oiner Gómez Baryolo
 * @author René R. Bauta Camejo
 * Instalador @copyright UCI Cuba
 *
 * @version 3.0
 */

var perfil = window.parent.UCID.portal.perfil;
var pnlConfInicial;

var parte = 0;
var pos = -1, tar, canTareas = 2;
var winIns, winIns1, gpmodulos = null;
var esDirIp, numerocentrodatos, mssbox, identidadactual, iddpa, tipos, tipoServidor, idservidor, idpuerto, centrodat, tfservidor, tfbasedatos, tfpuerto, tfcentrodato, tfusuario, tfpassword;
tipos = /^([a-z???????? ]+[a-z????????\d _]*)+$/;
idpuerto = /^[0-9]+$/;
esDirIp = /(^(2([0-4][0-9])|2(5[0-5]))|^([0-1]?[0-9]?[0-9]))\.(((2([0-4][0-9])|2(5[0-5]))|([0-1]?[0-9]?[0-9]))\.){2}((2([0-4][0-9])|2(5[0-5]))|([0-1]?[0-9]?[0-9]))$/;
tipoServidor = /^(2([0-4][0-9])|2(5[0-5]))|^([0-1]?[0-9]?[0-9])\.(((2([0-4][0-9])|2(5[0-5]))|([0-1]?[0-9]?[0-9]))\.){2}((2([0-4][0-9])|2(5[0-5]))|([0-1]?[0-9]?[0-9]))$|^([a-zA-Z???????? ]+[a-zA-Z????????\d _]*){1}$/;
Ext.QuickTips.init();
function cargarInterfaz() {

    Ext.ux.IFrameComponent = Ext.extend(Ext.BoxComponent, {
        onRender: function (ct, poerpsition) {
            this.el = ct.createChild
            ({
                tag: 'iframe',
                id: this.id,
                width: '100%',
                height: '100%',
                frameBorder: 0,
                src: this.url
            });
        }
    });
    //*********************************TextFilds************************************
    tfservidor = new Ext.form.TextField({
        fieldLabel: 'Servidor',
        id: 'servidor',
        allowBlank: false,
        value: 'localhost',
        blankText: 'Este campo es requerido.',
        anchor: '100%',
        listeners: {
            render: function (p) {
                Ext.QuickTips.register({
                    target: p.getEl(),
                    text: 'Ej: localhost, 10.190.0.1, http://servidor.org.cu.',
                    title: 'Direcci&oacute;n del servidor'
                });
            }
        }
    });

    tfbasedatos = new Ext.form.TextField({
        fieldLabel: 'Base de datos',
        id: 'basedatos',
        allowBlank: false,
        blankText: 'Este campo es requerido.',
        regex: tipos,
        value: 'sauxe_v22',
        regexText: 'El nombre de la base de datos debe comenzar con letras.',
        anchor: '100%',
        listeners: {
            render: function (p) {
                Ext.QuickTips.register({
                    target: p.getEl(),
                    text: 'Nombre que va a llevar la base de datos donde ser&aacute;n guardados los datos del sistema.',
                    title: 'Nombre de la base de datos'
                });
            }
        }
    });

    tfpuerto = new Ext.form.NumberField({
        fieldLabel: 'Puerto',
        id: 'puerto',
        allowBlank: false,
        blankText: 'Este campo es requerido.',
        regex: idpuerto,
        value: '5432',
        regexText: 'Solo números.',
        anchor: '100%',
        listeners: {
            render: function (p) {
                Ext.QuickTips.register({
                    target: p.getEl(),
                    text: 'Puerto por el cual se realizar&aacute; la conexi&oacute;n al servidor de base de datos, si no conoce este dato deje el que se encuentra predeterminado.',
                    title: 'Puerto del servidor'
                });
            }
        }
    });

    tfcentrodato = new Ext.form.NumberField({
        fieldLabel: 'No. Centro de datos',
        id: 'centrodat',
        allowBlank: false,
        blankText: 'Este campo es requerido.',
        regex: idpuerto,
        regexText: 'Solo números.',
        anchor: '100%',
        listeners: {
            render: function (p) {
                Ext.QuickTips.register({
                    target: p.getEl(),
                    text: 'Identificador del centro de datos de la fiscal&iacute;a.',
                    title: 'Identificador del centro de datos'
                });
            }
        }
    });

    tfusuario = new Ext.form.TextField({
        fieldLabel: 'Usuario',
        blankText: 'Este campo es requerido.',
        allowBlank: false,
        value: 'postgres',
        id: 'usuario',
        anchor: '100%',
        listeners: {
            render: function (p) {
                Ext.QuickTips.register({
                    target: p.getEl(),
                    text: 'Por medio de este usuario ser&aacute; posible conectarse al gestor de datos y crear la base de datos.',
                    title: 'Usuario de la base de datos'
                });
            }
        }
    });

    tfpassword = new Ext.form.TextField({
        fieldLabel: 'Contrase&ntilde;a',
        allowBlank: false,
        blankText: 'Este campo es requerido.',
        inputType: 'password',
        value: 'postgres',
        id: 'password',
        anchor: '100%',
        listeners: {
            render: function (p) {
                Ext.QuickTips.register({
                    target: p.getEl(),
                    text: 'Contrase&ntilde;a del usuario para conectarse al gestor de datos.',
                    title: 'Contrase&ntilde;a'
                });
            }
        }
    });

    //ventana donde se cargan los datos de la base de datos
    var instalacion = new Ext.FormPanel({
        labelAlign: 'top',
        id: 'instalacion',
        frame: true,
        region: 'center',
        items: [
            {
                xtype: 'fieldset',
                title: 'Informaci&oacute;n',
                autoHeight: true,
                html: '<h2><p align="center">Datos necesarios para la instalaci&oacute;n del sistema</p></h2>'
            },
            {
                layout: 'column',
                items: [
                    {
                        columnWidth: .50,
                        frame: true,
                        layout: 'form',
                        items: [tfservidor, tfbasedatos, tfpuerto]
                    },
                    {
                        columnWidth: .50,
                        frame: true,
                        layout: 'form',
                        items: [tfusuario, tfpassword, tfcentrodato]
                    }
                ]
            }
        ]
    });

    //Selection model del grid
    var sm = new Ext.grid.CheckboxSelectionModel({
        renderer: function (v, p, record) {
            if (record.data.estado == 0)
                return '<div class="x-grid3-row-checker">&nbsp;</div>';
            return '<input type="checkbox" name="checkbox" value="checkbox" checked="checked" disabled="disabled" class="x-grid3-row-checker"/>';
        },
        dataIndex: 'estado'

    });

    var store = new Ext.data.Store({
        url: 'index.php/index/getxml',
        reader: new Ext.data.JsonReader({
                totalProperty: "cantidad_filas",
                root: "subsistemas",
                id: "linea"
            }, [
            {
                name: 'idinstalador',
                mapping: 'idinstalador'
            },

            {
                name: 'nombre',
                mapping: 'nombre'
            },

            {
                name: 'estado',
                mapping: 'estado'
            }
        ]
        )
    });

    //columnModel del Grig
    var cm = new Ext.grid.ColumnModel([
        sm, {
            id: 'linea',
            sortable: false,
            header: "M&oacute;dulo",
            dataIndex: 'nombre',
            width: 160
        }, {
            id: 'idinst',
            header: "Id",
            hidden: true,
            dataIndex: 'idinstalador',
            width: 160
        }
    ]);

    //grid que muestra los modulos a instalar
    var gpmodulos = new Ext.grid.GridPanel({
        store: store,
        autoScroll: true,
        cm: cm,
        height: 200,
        //autoWidth : true,
        //frame:true,
        autoExpandColumn: 'linea',
        id: 'gpmodulos',
        sm: sm
    });
    gpmodulos.getView().getRowClass = function (record, index, rowParams, store) {
        if (record.data.estado == 1)
            return 'FilaRoja';
    };
    function winForm() {
        if (!winIns) {
            winIns = new Ext.Window({
                layout: 'border',
                y: 130,
                width: 450,
                closable: false,
                draggable: false,
                height: 300,
                resizable: false,
                title: 'Configuraci&oacute;n de la Base de Datos'
            });
        }
        winIns.add(instalacion);
        winIns.doLayout();
        winIns.show(this);
    }

    winForm();
    panel = new Ext.Panel({
        bodyStyle: 'padding:5px 5px 0',
        id: 'panel1',
        items: [
            {
                xtype: 'fieldset',
                // frame:true,
                title: 'Informaci&oacute;n',
                autoHeight: true,
                html: '<p align="center">Selecci&oacute;n de los m&oacute;dulos que desea instalar</p>'
            },
            gpmodulos
        ]

    });
    //funcion para mostrar el grid
    function siguienteGrid() {
        if (!winIns1) {
            winIns1 = new Ext.Window({
                width: 450,
                y: 170,
                closable: false,
                draggable: false,
                autoHeight: true,
                resizable: false,
                title: 'Selecci&oacute;n de los M&oacute;dulos a instalar'
            });
        }
        winIns1.add(panel);
        store.reload();
        winIns1.doLayout();
        winIns1.show();
    }


    // panel principal
    pnlConfInicial = new Ext.Panel({
        id: 'pConfInicial',
        title: 'Asistente para la instalaci&oacute;n del marco de trabajo Sauxe v2.2',
        html: '<div id="idbody"><img src="images/center.png"></div>',
        bbar: new Ext.StatusBar({
            defaultText: 'Listo...',
            id: 'statusbar',
            items: [
                '-', {
                    id: 'btnCancelar',
                    text: 'Cancelar',
                    iconCls: 'btn',
                    icon: 'comun/images/cancelar.png',
                    handler: function () {
                        if (parte == 1) {
                            lmAtras.show();
                            Ext.Ajax.request({
                                url: 'index.php/index/eliminardatabase',
                                method: 'POST',
                                params: {
                                    usuario: tfusuario.getValue(),
                                    password: tfpassword.getValue(),
                                    puerto: tfpuerto.getValue(),
                                    servidor: tfservidor.getValue(),
                                    basedatos: tfbasedatos.getValue()
                                },
                                success: function (response, options) {
                                    lmAtras.hide();
                                    var jsonData = Ext.util.JSON.decode(response.responseText);
                                    if (jsonData.codMsg == 0) {
                                        if (parte == 0)
                                            winIns.close();
                                        else if (parte == 1)
                                            winIns1.close();
                                        pnlConfInicial.hide();
                                    } else {
                                        lmAtras.hide();
                                        mostrarMensaje(jsonData.codMsg, jsonData.mensaje);
                                    }
                                },
                                failure: function () {
                                    mostrarMensaje(3, 'Ha ocurrido un error inesperado, descomprima nuevamente\n la soluci&oacute;n y verifique que se cumplen todas los requisitos\n para el buen funcionamiento del sistema.');
                                }
                            });
                        } else {
                            if (parte == 0)
                                winIns.close();
                            else if (parte == 1)
                                winIns1.close();
                            pnlConfInicial.hide();
                        }
                    }
                }, {
                    id: 'btnAnterior',
                    text: 'Anterior',
                    iconCls: 'btn',
                    icon: 'comun/images/anterior.png',
                    disabled: true,
                    handler: function () {
                        lmAtras.show();
                        pos--;
                        Ext.Ajax.request({
                            url: 'index.php/index/eliminardatabase',
                            method: 'POST',
                            params: {
                                usuario: tfusuario.getValue(),
                                password: tfpassword.getValue(),
                                puerto: tfpuerto.getValue(),
                                servidor: tfservidor.getValue(),
                                basedatos: tfbasedatos.getValue()
                            },
                            success: function (response, options) {
                                lmAtras.hide();
                                var jsonData = Ext.util.JSON.decode(response.responseText);

                                if (jsonData.codMsg == 0) {
                                    winIns1.hide();
                                    winIns.show();
                                    parte--;
                                    Ext.getCmp('btnAnterior').disable();
                                } //else
                                    //mostrarMensaje(3, jsonData.codMsg);
                            },
                            failure: function () {
                                mostrarMensaje(3, 'Ha ocurrido un error inesperado, descomprima nuevamente\n la soluci&oacute;n y verifique que se cumplen todas los requisitos\n para el buen funcionamiento del sistema.');
                            }
                        });
                    }
                }, {
                    id: 'btnSiguiente',
                    text: 'Siguiente',
                    iconCls: 'btn',
                    icon: 'comun/images/siguiente.png',
                    handler: function () {
                        pos++;
                        if (parte == 0) {
                            if (instalacion.getForm().isValid()) {
                                lmConectandoseServidor.show();
                                Ext.Ajax.request({
                                    url: 'index.php/index/verificar',
                                    method: 'POST',
                                    params: {
                                        usuario: tfusuario.getValue(),
                                        password: tfpassword.getValue(),
                                        puerto: tfpuerto.getValue(),
                                        servidor: tfservidor.getValue(),
                                        basedatos: tfbasedatos.getValue()
                                    },
                                    success: function (response, options) {
                                        var jsonData = Ext.util.JSON.decode(response.responseText);
                                        if (jsonData.codMsg == 0) {
                                            lmConectandoseServidor.hide();
                                            winIns.hide();
                                            siguienteGrid();
                                            Ext.getCmp('btnAnterior').enable();
                                            parte++;
                                        } else {
                                            lmConectandoseServidor.hide();
                                            //mostrarMensaje(jsonData.codMsg, jsonData.mensaje);
                                        }
                                    },
                                    failure: function () {
                                        lmConectandoseServidor.hide();
                                        //mostrarMensaje(3, 'Ha ocurrido un error inesperado, descomprima nuevamente\n la soluci&oacute;n y verifique que se cumplen todas los requisitos\n para el buen funcionamiento del sistema.');
                                    }
                                });
                            }
                            else
                                mostrarMensaje(3, 'Debe llenar todos los campos.');
                        } else if (parte == 1) {
                            Ext.MessageBox.show({
                                title: 'Instalaci&oacute;n',
                                msg: 'Creando la base de datos en el servidor...',
                                progressText: 'Ejecutando ...',
                                width: 300,
                                progress: true,
                                closable: false
                            });
                            // obteniendo los modulos seleccionados
                            var array_seleccion = gpmodulos.getSelectionModel().getSelections();
                            var arrIdSelecc = new Array();
                            if (array_seleccion.length > 0)
                                for (i = 0; i < array_seleccion.length; i++)
                                    arrIdSelecc.push(array_seleccion[i].data.idinstalador);
                            verProgreso(arrIdSelecc);
                        }
                    }
                }]
        })
    })

    // viewport
    vpConfInicial = new Ext.Viewport({
        layout: 'fit',
        items: pnlConfInicial
    })

    var loadFn = function (btn, statusBar) {
        btn = Ext.getCmp(btn);
        statusBar = Ext.getCmp(statusBar);
        btn.disable();
        statusBar.showBusy();
        (function () {
            statusBar.clearStatus({
                useDefaults: true
            });
            btn.enable();
        }).defer(2000);
    };

}

function comprobartareas() {
    Ext.Ajax.request({
        url: 'index.php/index/Comprobartareas',
        method: 'POST',
        callback: function (options, success, response) {
            responseData = Ext.decode(response.responseText);
            cargarInterfaz();
        }
    });
}
comprobartareas();


/******************************fucniones******************************/
var failureAjaxFn = function () {
    mostrarMensaje(3, 'No se ha podido establecer conexión con el servidor');
};

var lmConectandoseServidor = new Ext.LoadMask(Ext.getBody(), {
    msg: 'Conect&aacute;ndose al servidor...'
});

var lmAtras = new Ext.LoadMask(Ext.getBody(), {
    msg: 'Cancelando...'
});

var lmInstalando = new Ext.LoadMask(Ext.getBody(), {
    msg: 'Creando estructura de datos...'
});

function updateProgressBar(i) {
    Ext.MessageBox.updateProgress(i, Math.floor(i * 100) + "% completado");
}

function verProgreso(arrIdSelecc) {
    Ext.Ajax.request({
        url: 'index.php/index/instalacion',
        method: 'POST',
        timeout: 120000,
        params: {
            arrSelecc: Ext.util.JSON.encode(arrIdSelecc),
            usuario: tfusuario.getValue(),
            password: tfpassword.getValue(),
            puerto: tfpuerto.getValue(),
            servidor: tfservidor.getValue(),
            centrodato: tfcentrodato.getValue(),
            entidadactual: identidadactual,
            iddpa: iddpa,
            basedatos: tfbasedatos.getValue()
        },
        success: function (response, options) {
            var jsonData = Ext.util.JSON.decode(response.responseText);
            if (jsonData.codMsg == 0) {
                updateProgressBar(jsonData.progreso);
                if (jsonData.progreso == 1) {
                    winIns1.hide();
                    mostrarMensaje(1, "La instalaci&oacute;n ha sido terminada con &eacute;xito.");
                    window.location.replace("../portal/index.php");
                } else {
                    verProgreso(arrIdSelecc);
                }
            } else {
                //mostrarMensaje(jsonData.codMsg, jsonData.mensaje);//quitar este mensaje y esconder la ventana de prgreso.
            }
        },
        failure: function () {
            lmInstalando.hide();
            mostrarMensaje(3, 'No se ha podido establecer conexión con el servidor.');
        }
    });
}