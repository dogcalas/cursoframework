var perfil = window.parent.UCID.portal.perfil;
perfil.etiquetas = Object();
UCID.portal.cargarEtiquetas('gestionarproceso', cargarInterfaz);

// //------------ Inicializo el singlenton QuickTips ------------////
Ext.QuickTips.init();

// //------------ Declarar Variables ------------////
var selectconexion1 = true, booladdmodproce = true, event, page,primeravez=false;
var stproceso, stevent, actionAddMod, actionproceso, sm, modificareventname, modificardescripcion;

// //------------ Area de Validaciones ------------////
var texto, num, dir;
texto = /^([a-zA-ZáéíóúñüÑ]+[a-zA-ZáéíóúñüÑ\d\.\-\_ ]*)+$/;
num = /^([a-z\d\.\-\_]*)+$/;
addevento = new Ext.Window({
    modal: true,
    closeAction: 'hide',
    layout: 'fit',
    resizable: false,
    title: "",
    width: 310,
    height: 180,
    items: [{
            xtype: 'form',
            title: '',
            id: 'formevent',
            frame: true,
            region: 'center',
            items: [{
                    xtype: 'hidden',
                    fieldLabel: 'Nombre',
                    width: 280,
                    id: 'idevent',
                    allowBlank: false,
                    blankText: 'Debe ingresar el nombre del evento.'
                }, {
                    xtype: 'textfield',
                    fieldLabel: 'Nombre',
                    width: 280,
                    id: 'ename',
                    allowBlank: false,
                    blankText: 'Debe ingresar el nombre del evento.'
                }, {
                    xtype: 'textarea',
                    fieldLabel: 'Descripción',
                    width: 280,
                    id: 'edescripcions'

                }]

        }],
    buttons: [{
            icon: perfil.dirImg + 'cancelar.png',
            iconCls: 'btn',
            text: 'Cancelar',
            handler: function() {
                addevento.hide();
                Ext.getCmp('ename').reset();
                Ext.getCmp('edescripcions').reset();
            }
        }, {
            icon: perfil.dirImg + 'aplicar.png',
            id: 'ebtnaplicar',
            iconCls: 'btn',
            text: 'Aplicar',
            handler: function() {
                addeventBD('Modificando');
            }
        }, {
            icon: perfil.dirImg + 'aceptar.png',
            iconCls: 'btn',
            text: 'Aceptar',
            handler: function() {
                addeventBD('Adicionando');
            }
        }]

});
function cargarInterfaz() {
    Ext.onReady(function() {
        // //------------ Botones ------------////

        addProceso = new Ext.Button({
            disabled: false,
            id: 'addProceso',
            icon: perfil.dirImg + 'adicionar.png',
            iconCls: 'btn',
            text: perfil.etiquetas.addProceso,
            handler: function() {
                Adicionar_proceso();
            }

        });

        modProceso = new Ext.Button({
            disabled: true,
            id: 'modProceso',
            icon: perfil.dirImg + 'modificar.png',
            iconCls: 'btn',
            text: perfil.etiquetas.modProceso,
            handler: function() {
                Modificar_proceso();
            }

        });
        eliProceso = new Ext.Button({
            disabled: true,
            id: 'eliProceso',
            icon: perfil.dirImg + 'eliminar.png',
            iconCls: 'btn',
            text: perfil.etiquetas.eliProceso,
            handler: function() {
                eliminar_proceso();
            }

        });

        addevent = new Ext.Button({
            disabled: false,
            id: 'addevent',
            icon: perfil.dirImg + 'adicionar.png',
            iconCls: 'btn',
            text: perfil.etiquetas.addevent,
            handler: function() {
                Adicionar_event();
            }

        });

        modevent = new Ext.Button({
            disabled: true,
            id: 'modevent',
            icon: perfil.dirImg + 'modificar.png',
            iconCls: 'btn',
            text: perfil.etiquetas.modevent,
            handler: function() {
                Modificar_event();
            }

        });
        elievent = new Ext.Button({
            disabled: true,
            id: 'elievent',
            icon: perfil.dirImg + 'eliminar.png',
            iconCls: 'btn',
            text: perfil.etiquetas.delevent,
            handler: function() {
                eliminar_event();
            }

        });

        // //------------ Store del Grid de Temas ------------////

        stproceso = new Ext.data.Store({
            fields: [{
                    name: 'idproceso',
                    type: 'string'
                }, {
                    name: 'name',
                    type: 'string'
                }, {
                    name: 'descripcion',
                    type: 'string'
                }, {
                    name: 'fdatos',
                    type: 'string'
                }, {
                    name: 'instancia',
                    type: 'string'
                },
                {
                    name: 'version',
                    type: 'string'
                }],
            proxy: {
                type: 'ajax',
                url: 'getprocesos',
                actionMethods: {// Esta Linea es necesaria para el
                    // metodo de llamada POST o GET
                    read: 'POST'
                },
                reader: {
                    totalProperty: "cantidad_filas",
                    root: "datos",
                    id: "id"
                }
            }
        });
        stevent = new Ext.data.Store({
            fields: [{
                    name: 'idevent',
                    type: 'string'
                }, {
                    name: 'ename',
                    type: 'string'
                }, {
                    name: 'edescripcions',
                    type: 'string'
                }],
            proxy: {
                type: 'ajax',
                url: 'getevent',
                actionMethods: {// Esta Linea es necesaria para el
                    // metodo de llamada POST o GET
                    read: 'POST'

                },
                reader: {
                    totalProperty: "cantidad_filas",
                    root: "datos",
                    id: "idevent"
                }
            }
            // autoLoad: true
        });

        // //------------ Establesco modo de seleccion de grid (single)

        sm = Ext.create('Ext.selection.RowModel', {
            mode: 'SINGLE',
            allowDeselect: false,
            enableKeyNav: false
        });
        sm.on('select', function(smodel, rowIndex, keepExisting, record) {
            cargareventoproceso(sm.getSelection()[0].data.idproceso);

            if (booladdmodproce) {
                modProceso.enable();
                eliProceso.enable();
                booladdmodproce = false;
            }
        }, this);

        sme = Ext.create('Ext.selection.RowModel', {
            mode: 'SINGLE',
            allowDeselect: false,
            enableKeyNav: false
        });

        stproceso.on('load', function() {
            if (stproceso.getCount() != 0) {
                // modProceso.enable();
                // eliProceso.enable();
                // sm.select(0);
            } else {
                modProceso.disable();
                eliProceso.disable();
            }
        }, this);

        stevent.on('load', function() {
            if (stevent.getCount() != 0) {
                modevent.enable();
                elievent.enable();
                sme.select(0);
            }
            else {
                modevent.disable();
                elievent.disable();
            }

        }, this);

        // //------------ Defino el grid de Temas ------------////

        var GModulo = Ext.create('Ext.grid.Panel', {
            frame: true,
            region: 'center',
            autoExpandColumn: 'expandir',
            store: stproceso,
            selModel: sm,
            tbar: [addProceso, modProceso, eliProceso],
            columns: [{
                    text: 'Nombre del proceso',
                    dataIndex: 'name',
                    flex: 2
                }, {
                    text: 'Fuente de datos',
                    dataIndex: 'fdatos',
                    flex: 2

                }, {
                    text: 'Descripción',
                    dataIndex: 'descripcion',
                    flex: 2
                },
                {
                    text: 'Versión del proceso',
                    dataIndex: 'version',
                    flex: 1.5
                }

            ],
            loadMask: {
                store: stproceso
            },
            renderTo: Ext.getBody(),
            bbar: new Ext.toolbar.Paging({
                store: stproceso,
                displayInfo: true,
                displayMsg:" Resultados {0} - {1} de {2}",
                emptyMsg: "Ning&uacute;n resultado para mostrar."
            })
        });

        event = Ext.create('Ext.grid.Panel', {
            frame: true,
            title: "Gestionar eventos de proceso",
            width: 350,
            region: 'east',
            animCollapse: true,
            collapsible: true,
            autoExpandColumn: 'expandir',
            store: stevent,
            selModel: sme,
            tbar: [addevent, modevent, elievent],
            columns: [{
                    text: 'Nombre del evento',
                    dataIndex: 'ename',
                    flex: 1
                }, {
                    text: 'Descripción',
                    dataIndex: 'edescripcions',
                    flex: 2
                }

            ],
            loadMask: {
                store: stevent
            },
            bbar: new Ext.PagingToolbar({
                store: stevent,
                displayInfo: true,
                displayMsg:" Resultados {0} - {1}",
                emptyMsg: "Ning&uacute;n resultado."
            })
        });
        event.disable();

        // //------------ Renderiar el arbol ------------////

        var panel = new Ext.Panel({
            layout: 'border',
            title: "Gestionar proceso",
            renderTo: 'panel',
            items: [GModulo, event]

        });

        // //------------ ViewPort ------------////
        var vpTema = new Ext.Viewport({
            disable: true,
            layout: 'fit',
            items: [panel]
        });

        stproceso.reload({params: {
                limit: 4,
                start: 0
            }
        });

        function Adicionar_proceso() {

            actionproceso = "adicionar";
            if (selectconexion1)
                this.selectconexion = new selectconexion();
            else
                stconexion.reload();
            selectconexion1 = false;
            this.selectconexion.show();
            if(primeravez){
            Ext.getCmp('name').reset();
			Ext.getCmp('fdatos').reset();
			Ext.getCmp('descripcion').reset();
			primeravez=true;
		}
        }

        function Modificar_proceso() {
            Ext.Ajax.request({
                url: 'Modconexion',
                method: 'POST',
                params: {
                    idproceso: sm.getLastSelected().data.idproceso
                }
            });
            actionproceso = "modificar";
            // stproceso.load();
            if (selectconexion1)
                this.selectconexion = new selectconexion();
            else
                stconexion.reload();
            selectconexion1 = false;
            // adicionar = false;
            this.selectconexion.show();
            // this.selectconexion.initCar();

            // winproceso.loadRecord(sm.getLastSelected());

        }
        function eliminar_event() {
            mostrarMensaje(2, "Está seguro que desea eliminar el evento.",
                    elimina);

            function elimina(btnPresionado) {
                if (btnPresionado == 'ok') {
                    Ext.Ajax
                            .request({
                        url: 'delevent',
                        method: 'POST',
                        params: {
                            idevent: sme.getLastSelected().data.idevent
                        },
                        callback: function(options, success,
                                response) {
                            responseData = Ext
                                    .decode(response.responseText);
                            if (responseData.conectado == 1) {
                                stevent.reload();
                                Ext.MessageBox
                                        .show({
                                    title: 'Eliminar evento',
                                    msg: 'Evento eliminado satisfactoriamente.',
                                    icon: Ext.MessageBox.INFO,
                                    buttons: Ext.MessageBox.OK
                                });
                            }
                            if (responseData.conectado == 0) {
                                Ext.MessageBox
                                        .show({
                                    title: 'Eliminar evento',
                                    msg: 'El evento no puede ser eliminado, el proceso está activo.',
                                    icon: Ext.MessageBox.ERROR,
                                    buttons: Ext.MessageBox.OK
                                });
                            }

                        }

                    });
                }
            }

        }
        function eliminar_proceso() {
            mostrarMensaje(2, "Está seguro que desea eliminar el proceso.",
                    elimina);

            function elimina(btnPresionado) {
                if (btnPresionado == 'ok') {
                    Ext.Ajax
                            .request({
                        url: 'delproceso',
                        method: 'POST',
                        params: {
                            idproceso: sm.getLastSelected().data.idproceso
                        },
                        callback: function(options, success,
                                response) {
                            responseData = Ext
                                    .decode(response.responseText);
                            if (responseData.conectado == 1) {
                                stproceso.reload();
                                stevent.removeAll();
                                event.disable();
                                modevent.disable();
                                elievent.disable();
                                modProceso.disable();
                                eliProceso.disable();
                                booladdmodproce = true;
                                Ext.MessageBox
                                        .show({
                                    title: 'Eliminar proceso',
                                    msg: 'Proceso eliminado satisfactoriamente.',
                                    icon: Ext.MessageBox.INFO,
                                    buttons: Ext.MessageBox.OK
                                });

                            }
                            if (responseData.conectado == 0) {
                                Ext.MessageBox
                                        .show({
                                    title: 'Eliminar proceso',
                                    msg: 'El proceso está activo y no se puede eliminar.',
                                    icon: Ext.MessageBox.ERROR,
                                    buttons: Ext.MessageBox.OK
                                });
                            }

                        }

                    });
                }
            }

        }

        function cargareventoproceso(idproceso) {
            Ext.Ajax
                    .request({
                url: 'setidproceso',
                method: 'POST',
                params: {
                    idproceso: idproceso
                },
                callback: function(options, success, response) {
                    responseData = Ext
                            .decode(response.responseText);
                    if (responseData.vacio == 1) {
                        stevent.removeAll();
                        modevent.disable();
                        elievent.disable();
                        event.enable();
                        message("Proceso",
                                "El proceso no contiene eventos");
                    } else {
                        event.enable();
                        stevent.reload({params: {
                                page: 1,
                                limit: 25,
                                start: 0
                            }
                        });
                    }

                }
            });

        }

        function Resettext() {
            Ext.getCmp('user').reset();
            Ext.getCmp('pass').reset();
            Ext.getCmp('host').reset();
            Ext.getCmp('name').reset();
            Ext.getCmp('db').reset();
            Ext.getCmp('port').reset();
        }
        function message(title, text) {
            Ext.message.msg(title, text);
        }

        function Adicionar_event() {
            modificareventname = '';
            modificardescripcion = '';
            addevento.show();
            actionAddMod = "Adiconado";
            addevento.setTitle("Adicionar evento");
            Ext.getCmp('ebtnaplicar').show();
        }
        function Modificar_event() {

            addevento.show();
            Ext.getCmp('formevent').getForm().loadRecord(
                    sme.getSelection()[0]);
            modificareventname = Ext.getCmp('ename').getValue();
            modificardescripcion = Ext.getCmp('edescripcions').getValue();
            actionAddMod = "Modificado";
            addevento.setTitle("Modificar evento");
            Ext.getCmp('ebtnaplicar').hide();
        }

    });
}
function addeventBD(action) {
    if (modificareventname == Ext.getCmp('ename').getValue() && modificardescripcion == Ext.getCmp('edescripcions').getValue()) {
        Ext.MessageBox.show({
            title: 'Evento',
            msg: 'No se han realizado cambios.',
            icon: Ext.MessageBox.ERROR,
            buttons: Ext.MessageBox.OK
        });
        return;
    }
    //aqui
    var index = stevent.find('ename', Ext.getCmp('ename').getValue(), 0, false, false, true);

    if ((index != -1 && actionAddMod == "Adiconado") || (modificareventname != Ext.getCmp('ename').getValue() && index != -1)) {
        Ext.MessageBox.show({
            title: 'Evento',
            msg: 'El evento ya existe.',
            icon: Ext.MessageBox.ERROR,
            buttons: Ext.MessageBox.OK
        });
    } else {
        if (Ext.getCmp('formevent').getForm().isValid()) {
            Ext.Ajax.request({
                url: 'addevent',
                method: 'POST',
                params: {
                    descripcion: Ext.getCmp('edescripcions').getValue(),
                    name: Ext.getCmp('ename').getValue(),
                    idevent: Ext.getCmp('idevent').getValue(),
                    actionAddMod: actionAddMod
                },
                callback: function(options, success, response) {
                    responseData = Ext.decode(response.responseText);
                    if (responseData.vacio == 1) {

                        stevent.reload();
                        modevent.enable();
                        elievent.enable();
                        if (action == "Adicionando") {
                            addevento.hide();
                            Ext.MessageBox.show({
                                title: 'Evento',
                                msg: "Evento " + responseData.actionAddMod
                                        + " satisfactoriamente.",
                                icon: Ext.MessageBox.INFO,
                                buttons: Ext.MessageBox.OK
                            });
                        } else
                            message("Evento",
                                    "Evento agregado satisfactoriamente");
                        Ext.getCmp('ename').reset();
                        Ext.getCmp('edescripcions').reset();

                    } else {
                        if (actionAddMod == "Adicionado")
                            actionAddMod = "Adicionando";
                        else
                            actionAddMod = "Modifcando";
                        Ext.MessageBox.show({
                            title: 'Evento',
                            msg: 'Error ' + actionAddMod + ' el Evento.',
                            icon: Ext.MessageBox.ERROR,
                            buttons: Ext.MessageBox.OK
                        });

                    }

                }
            });
        } else
            Ext.MessageBox.show({
                title: 'Evento',
                msg: 'Existen campos vacios.',
                icon: Ext.MessageBox.ERROR,
                buttons: Ext.MessageBox.OK
            });
    }

}
function message(title, text) {
    Ext.message.msg(title, text);
}
