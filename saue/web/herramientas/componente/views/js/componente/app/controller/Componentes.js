var forminscomp;
var wininscomp;
var formmodcomp;
var winmodcomp;
var gridpanelserv;
var gridpaneldep;
var gridpanelevgen;
var gridpanelevobs;
var addservicio;
var modservicio;
var aplicar;
var addDependencia;
var modDependencia;
var addEventoGen;
var modEventoGen;
var addEventoObs;
var modEventoObs;
var responseData;
var actual;
var actualm;
var serv, dep, gen, obs, modificar;
var changed;

Ext.define('GestComponentes.controller.Componentes', {
    extend: 'Ext.app.Controller',
    refs: [{
            ref: 'grid',
            selector: 'gridpepe'
        }, {
            ref: 'arbolComp',
            selector: '#arbol-comp'
        }, {ref: 'wininscomp',
            selector: 'wininscomp'
        }, {ref: 'winmodcomp',
            selector: 'winmodcomp'},
        {ref: 'gridpanelservicios',
            selector: 'gridpanelservicios'},
        {ref: 'gridpaneldependencias',
            selector: 'gridpaneldependencias'},
        {ref: 'gridpaneleventgen',
            selector: 'gridpaneleventgen'},
        {ref: 'gridpaneleventobs',
            selector: 'gridpaneleventobs'},
        {ref: 'tabpanelgeneral',
            selector: 'tabpanelgeneral'},
        {ref: 'forminscomp',
            selector: 'forminscomp'}, {
            ref: 'dirComp',
            selector: 'DirComp'}, {
            ref: 'gridpanelcomp',
            selector: 'gridpanelcomp'
        }, {ref: 'wininsserv',
            selector: 'wininsserv'},
        {ref: 'gridpanelparm',
            selector: 'gridpanelparm'},
        {ref: 'gridpanelparmm',
            selector: 'gridpanelparmm'},
        {ref: 'formaddoper',
            selector: 'formaddoper'},
        {ref: 'gridpaneloper',
            selector: 'gridpaneloper'},
        {ref: 'btnaddparm',
            selector: '#btnaddparm'},
        {ref: 'btnmodparm',
            selector: '#btnmodparm'},
        {ref: 'btndellparm',
            selector: '#btndellparm'},
        {ref: 'btnaddpar',
            selector: '#btnaddpar'},
        {ref: 'btnmodpar',
            selector: '#btnmodpar'},
        {ref: 'btndellpar',
            selector: '#btndellpar'},
        {ref: 'btnModServ',
            selector: '#btnModServ'},
        {ref: 'btnDelServ',
            selector: '#btnDelServ'},
        {ref: 'btnModOper',
            selector: '#btnModOper'},
        {ref: 'btnDellOper',
            selector: '#btnDellOper'},
        {
            ref: 'arbolDep',
            selector: '#arbol-dep'
        }, {ref: 'arbolEventGen',
            selector: '#arbol-eventgen'},
        {ref: 'arbolEventObs',
            selector: '#arbol-eventobs'

        }
        , {
            ref: 'formadddep',
            selector: 'formadddep'
        }, {
            ref: 'btnModDep',
            selector: '#btnModDep'
        }, {
            ref: 'btnDelDep',
            selector: '#btnDelDep'
        }, {ref: 'btnSelDep',
            selector: '#btnSelDep'},
        {ref: 'btnSelUso',
            selector: '#btnSelUso'},
        {ref: 'btnSelEventGen',
            selector: '#btnSelEventGen'},
        {ref: 'formaddeventg',
            selector: 'formaddeventg'},
        {ref: 'formaddevento',
            selector: 'formaddevento'},
        {
            ref: 'wininseventg',
            selector: 'wininseventg'
        }, {ref: 'btnModEventG',
            selector: '#btnModEventG'},
        {ref: 'btnDelEventG',
            selector: '#btnDelEventG'},
        {ref: 'winmodeventg',
            selector: 'winmodeventg'},
        {ref: 'wininsevento',
            selector: 'wininsevento'},
        {
            ref: 'winmodevento',
            selector: 'winmodevento'
        }, {
            ref: 'formaddserv',
            selector: 'formaddserv'
        }, {ref: 'gridpaneloperm',
            selector: 'gridpaneloperm'},
        {ref: 'formmodoper',
            selector: 'formmodoper'},
        {ref: 'btnModComp',
            selector: '#btnModComp'},
        {ref: 'btnDellComp',
            selector: '#btnDellComp'},
        {ref: 'btnHabComp',
            selector: '#btnHabComp'},
        {ref: 'formmodserv',
            selector: 'formmodserv'},
        {ref: 'btnAddOperm',
            selector: '#btnAddOperm'},
        {ref: 'btnModOperm',
            selector: '#btnModOperm'},
        {ref: 'btnDellOperm',
            selector: '#btnDellOperm'},
        {ref: 'formmodcomp',
            selector: 'formmodcomp'},
        {ref: 'wininsdep',
            selector: 'wininsdep'},
        {ref: 'panelarboleventgen',
            selector: 'panelarboleventgen'},
        {ref: 'panelarboleventobs',
            selector: 'panelarboleventobs'},
        {ref: 'btnModEventG',
            selector: '#btnModEventG'},
        {
            ref: 'btnSelEventObs',
            selector: '#btnSelEventObs'
        }, {
            ref: 'btnModEventO',
            selector: '#btnModEventO'
        }, {
            ref: 'btnDelEventO',
            selector: '#btnDelEventO'
        }, {
            ref: 'btnAddComp',
            selector: '#btnAddComp'
        }, {
            ref: 'btnapliAddComp',
            selector: '#btnapliAddComp'
        }, {
            ref: 'btnapliAddServ',
            selector: '#btnapliAddServ'
        }, {
            ref: 'btnapliAddDep',
            selector: '#btnapliAddDep'
        }, {
            ref: 'btnapliAddEventG',
            selector: '#btnapliAddEventG'
        }, {
            ref: 'btnapliAddEventO',
            selector: '#btnapliAddEventO'
        },{
            ref:'btnAddComp',
            selector:'#btnAddComp'
        }


    ],
    init: function() {

        this.control({
            'gridpanelcomp toolbar[dock="top"] button': {
                click: this.Tbar

            },
            '#arbol-comp': {
                beforeitemexpand: this.nodoDir,
                itemclick: this.Seleccionar


            }, 'panelarbolcomp toolbar[dock="top"] button': {
                click: this.BotonesGestComp
            }, '#btncancAddComp': {
                click: this.CancAddComp
            },
            '#btnapliAddComp': {
                click: this.ApliAddComp
            },
            '#btnacepAddComp': {
                click: this.AcepAddComp
            },
            '#btncancModComp': {
                click: this.CancModComp
            },
            '#btnapliModComp': {
                click: this.ApliModComp
            },
            '#btnacepModComp': {
                click: this.AcepModComp
            },
            'gridpanelservicios toolbar[dock="top"] button': {
                click: this.BotonesGestServ
            },
            'gridpaneldependencias toolbar[dock="top"] button': {
                click: this.BotonesGestDep
            },
            'gridpaneleventgen toolbar[dock="top"] button': {
                click: this.BotonesGestEventGen
            },
            'gridpaneleventobs toolbar[dock="top"] button': {
                click: this.BotonesGestEventObs
            }, 'gridpanelcomp': {
                afteractivate: this.EsconderArbol
            }, '#btnaddpar': {
                click: this.AdicionarParametro
            }, 'wininsserv [dock] button': {
                click: this.BotonesAddServicio
            },
            '#nombreserv': {
                keypress: this.ActivarFormOperacion
            },
            '#btnaceptoper': {
                click: this.AdicionarOperacion
            }, '#btnaddparm': {
                click: this.AdicionarParametrom
            }, 'gridpanelparm': {
                itemclick: this.MostrarInfo
            }, 'gridpanelparmm': {
                itemclick: this.MostrarInfoM
            }, '#btnmodparm': {
                click: this.ModificarParametrom
            }, '#btndellparm': {
                click: this.EliminarParametrom
            }, '#btnmodpar': {
                click: this.ModificarParametro
            }, '#btndellpar': {
                click: this.EliminarParametro
            }, 'winmodserv [dock] button': {
                click: this.BotonesModServicio
            }, 'gridpanelservicios': {
                itemclick: this.ActivarBotones,
                beforeactivate: this.cargarServicios,
                destroy: this.CambiarEstadoServ
            }, 'winmodserv': {
                hide: this.DesactivarBotonesmod,
            }, 'wininsserv': {
                hide: this.DesactivarBotonesadd
            }, 'gridpaneloperm': {
                itemclick: this.ActivarBotonesoperM

            }, 'gridpaneloper toolbar[dock="top"] button': {
                click: this.BotonesGridOper
            }, '#btncancoper': {
                click: this.CancOperacion
            }, '#btncancoperm': {
                click: this.CancOperacionM
            }, 'gridpaneloper': {
                itemclick: this.ActivarBotonesGridOper
            }, '#arbol-dep': {
                beforeitemexpand: this.NodoDirDep
            }, '#arbol-eventgen': {
                beforeitemexpand: this.NodoDirEventG
            }, '#arbol-eventobs': {
                beforeitemexpand: this.NodoDirEventO
            }, 'wininsdep [dock] button': {
                click: this.BotonesInsDep
            }, 'gridpaneldependencias': {
                itemclick: this.ActivarBotonesGDep,
                destroy: this.CambiarEstadoDep,
                beforeactivate: this.cargarDependencias
            }, 'panelarboldep toolbar[dock="top"] button': {
                click: this.BotonesArbolDep
            }, 'panelarboldep': {
                itemclick: this.ActivarBotonesArbDep
            }, 'winmoddep [dock] button': {
                click: this.BotonesModDep
            }, 'gridpaneleventgen': {
                destroy: this.CambiarEstadoGen,
                itemclick: this.ActivarBotonesGEventG,
                beforeactivate: this.cargarEventGen
            }, 'gridpaneleventobs': {
                destroy: this.CambiarEstadoObs,
                itemclick: this.CambiarEstadoBot,
                beforeactivate: this.cargarEventObs
            }, 'panelarboleventgen': {
                itemclick: this.ActivarBotonArbEG
            }, 'panelarboleventobs': {
                itemclick: this.ActivarBotonArbEO
            }
            , '#btnSelEventGen': {
                click: this.GenerarEvento
            }, 'wininseventg [dock] button': {
                click: this.BotonesWinInsEG
            }, '#clase': {
                change: this.AdicionarTextField
            }, 'winmodeventg [dock] button': {
                click: this.BotonesWinMEvG
            }, 'wininsevento [dock] button': {
                click: this.BotonesWinInsEO
            }, 'winmodevento [dock] button': {
                click: this.BotonesWinModEO
            }, 'gridpaneloperm [dock] button': {
                click: this.BotonesGridOperM,
            }, '#btnaceptoperm': {
                click: this.AdicionarOperacionM
            },
            'wininsdep': {
                beforeshow: this.CargarArbolDep
            }, 'winmoddep': {
                beforeshow: this.CargarArbolDep
            }, 'wininsevento': {
                beforeshow: this.CargarArbolObserver
            }, '#btnSelEventObs': {
                click: this.SelecionarEvento
            }, 'winmodevento': {
                beforeshow: this.CargarArbolObserver
            }
        });


    },
    BotonesGestComp: function(button) {

        switch (button.itemId) {

            case 'btnAddComp' :
                {
                    forminscomp = Ext.create('GestComponentes.view.FormInsComp');
                    wininscomp = Ext.create('GestComponentes.view.WinInsComp');
                    wininscomp.add(forminscomp);
                    if (!this.getArbolComp().getSelectionModel().getLastSelected().isLeaf()) {
                        var c = this.getArbolComp().getSelectionModel().getLastSelected().get('dir');
                        var d = c.charAt(2);
                        var a = this.getArbolComp().getSelectionModel().getLastSelected().get('dir') + d + this.getArbolComp().getSelectionModel().getLastSelected().get('text') + '.scl';
                        var b = a.split('apps');

                        this.getForminscomp().getForm().findField('direccion').setValue(b[1]);
                    }
                    wininscomp.show();

                    break;

                }
            case 'btnModComp' :
                {
                    formmodcomp = Ext.create('GestComponentes.view.FormModComp');
                    winmodcomp = Ext.create('GestComponentes.view.WinModComp');
                    winmodcomp.add(formmodcomp);


                    var nombre = this.getArbolComp().getSelectionModel().getLastSelected().get('text');

                    formmodcomp.getForm().findField('nombre').setValue(this.getGridpanelcomp().getStore().findRecord('nombre', nombre).get('nombre'));
                    formmodcomp.getForm().findField('direccion').setValue(this.getGridpanelcomp().getStore().findRecord('nombre', nombre).get('direccion'));
                    formmodcomp.getForm().findField('id').setValue(this.getGridpanelcomp().getStore().findRecord('nombre', nombre).get('id'));
                    formmodcomp.getForm().findField('version').setValue(this.getGridpanelcomp().getStore().findRecord('nombre', nombre).get('version'));
                    formmodcomp.getForm().findField('estado').setValue(this.getGridpanelcomp().getStore().findRecord('nombre', nombre).get('estado'));


                    winmodcomp.show();

                    break;
                }
            case 'btnDellComp' :
                {

                    this.BorrarComponente();

                    break;
                }
            case 'btnHabComp':
                {
                    this.HabilitarComponente();
                }

        }


    },
    Tbar: function(button) {
        switch (button.itemId) {
            case 'btnServicios' :
                {

                    gridpanelserv = Ext.create('GestComponentes.view.GridPanelServicios');
                    this.getTabpanelgeneral().add(gridpanelserv);
                    this.getGridpanelservicios().setVisible(true);
                    serv = true;

                    break;
                }
            case 'btnDependencias':
                {
                    gridpaneldep = Ext.create('GestComponentes.view.GridPanelDependencias');
                    this.getTabpanelgeneral().add(gridpaneldep);
                    this.getGridpaneldependencias().setVisible(true);

                    dep = true;
                    break;
                }
            case 'btnEventosGen':
                {
                    gridpanelevgen = Ext.create('GestComponentes.view.GridPanelEventGenerados');
                    this.getTabpanelgeneral().add(gridpanelevgen);
                    this.getGridpaneleventgen().setVisible(true);
                    gen = true;
                    break;
                }
            case 'btnEventosObs':
                {
                    gridpanelevobs = Ext.create('GestComponentes.view.GridPanelEventObservados');
                    this.getTabpanelgeneral().add(gridpanelevobs);
                    this.getGridpaneleventobs().setVisible(true);
                    obs = true;
                    break;
                }
        }


    },
    nodoDir: function(record) {

        this.getArbolComp().getStore().getProxy().extraParams = {carpeta: record.get('dir')};

    },
    cargarServicios: function() {

        this.getGridpanelservicios().getStore().load({
            waitMsg: perfil.etiquetas.lbMsgCargServ,
            params: {
                nombre: this.getArbolComp().getSelectionModel().getLastSelected().get('text')

            }

        });



    },
    cargarDependencias: function() {



        this.getGridpaneldependencias().getStore().load({
            waitMsg: perfil.etiquetas.lbMsgCargDep,
            params: {
                direccion: this.getArbolComp().getSelectionModel().getLastSelected().get('dir'),
                nombre: this.getArbolComp().getSelectionModel().getLastSelected().get('text')

            }

        });



    },
    cargarEventGen: function() {

        this.getGridpaneleventgen().getStore().load({
            waitMsg: perfil.etiquetas.lbMsgCargEvent,
            params: {
                direccion: this.getArbolComp().getSelectionModel().getLastSelected().get('dir'),
                nombre: this.getArbolComp().getSelectionModel().getLastSelected().get('text')

            }

        });



    },
    cargarEventObs: function() {

        this.getGridpaneleventobs().getStore().load({
            waitMsg: perfil.etiquetas.lbMsgCargObs,
            params: {
                direccion: this.getArbolComp().getSelectionModel().getLastSelected().get('dir'),
                nombre: this.getArbolComp().getSelectionModel().getLastSelected().get('text')

            }

        });



    },
    CancAddComp: function() {

       
        wininscomp.close();


    },
    ApliAddComp: function() {
        if (forminscomp.getForm().findField('direccion').getValue() != '') {
            if (forminscomp.getForm().findField('direccion').isValid() === true) {
                aplicar = true;
                this.insertarbundle();

            } else {
                mostrarMensaje(3, perfil.etiquetas.lbMsgErrorEnCamops);
            }

        } else {
            mostrarMensaje(3, perfil.etiquetas.lbMsgEmptyField);
        }



    },
    AcepAddComp: function() {

        if (forminscomp.getForm().findField('direccion').getValue() != '') {
            if (forminscomp.getForm().findField('direccion').isValid() === true) {
               
                    aplicar = false;
                    this.insertarbundle();

            } else {
                mostrarMensaje(3, perfil.etiquetas.lbMsgErrorEnCamops);


            }

        } else {
            mostrarMensaje(3, perfil.etiquetas.lbMsgEmptyField);
        }

    },
    CancModComp: function() {

        winmodcomp.close();

    },
    AcepModComp: function() {
        if (formmodcomp.getForm().findField('direccion').getValue() != '' && formmodcomp.getForm().findField('nombre').getValue() != '' && formmodcomp.getForm().findField('version').getValue() != '') {
            if (formmodcomp.getForm().findField('version').isValid() === true && formmodcomp.getForm().findField('nombre').isValid() === true && formmodcomp.getForm().findField('direccion').isValid() === true && formmodcomp.getForm().isDirty() === true && formmodcomp.getForm().isValid() === true) {
                if (this.getGridpanelcomp().getStore().findExact('direccion', formmodcomp.getForm().findField('direccion').getValue()) ==0 && this.getGridpanelcomp().getStore().findExact('nombre', formmodcomp.getForm().findField('nombre').getValue()) ==0 && this.getGridpanelcomp().getStore().findExact('version', formmodcomp.getForm().findField('version').getValue()) ==0) {
                    mostrarMensaje(3, perfil.etiquetas.lbMsgNoMod);
                } else {
                    console.log('entro');
                    this.modificarBundle();
                }

            } else {
                mostrarMensaje(3, perfil.etiquetas.lbMsgErrorEnCamops);
            }

        } else {
            mostrarMensaje(3, perfil.etiquetas.lbMsgEmptyFields);
        }




    },
    BotonesGestServ: function(button) {
        switch (button.itemId) {
            case 'btnAddServ':
                {

                    addservicio = Ext.create('GestComponentes.view.WinInsServ');

                    addservicio.show();
                    this.getBtnDelServ().setDisabled(true);
                    this.getBtnModServ().setDisabled(true);
                    break;
                }
            case 'btnModServ':
                {
                    var id = this.getGridpanelservicios().getSelectionModel().getLastSelected().get('id');
                    var interface = this.getGridpanelservicios().getSelectionModel().getLastSelected().get('interface');
                    var impl = this.getGridpanelservicios().getSelectionModel().getLastSelected().get('impl');
                    modservicio = Ext.create('GestComponentes.view.WinModServ');
                    this.getFormmodserv().getForm().findField('nombreservm').setValue(id);
                    this.getGridpaneloperm().getStore().load({
                        url: 'cargarOperaciones',
                        waitMsg: perfil.etiquetas.lbMsgCargOper,
                        params: {
                            id: this.getGridpanelservicios().getSelectionModel().getLastSelected().get('id'),
                            interface: this.getGridpanelservicios().getSelectionModel().getLastSelected().get('interface'),
                            impl: this.getGridpanelservicios().getSelectionModel().getLastSelected().get('impl'),
                            direccion: this.getArbolComp().getSelectionModel().getLastSelected().get('dir'),
                            nombre: this.getArbolComp().getSelectionModel().getLastSelected().get('text')
                        }

                    });

                    modservicio.show();
                    this.getBtnDelServ().setDisabled(true);
                    this.getBtnModServ().setDisabled(true);
                    break;
                }
            case 'btnDelServ':
                {
                    var ids = this.getGridpanelservicios().getSelectionModel().getSelection();

                    this.BorrarServicio(ids);
                    this.getBtnDelServ().setDisabled(true);
                    this.getBtnModServ().setDisabled(true);

                    break;
                }
        }

    },
    BotonesGestDep: function(button) {
        switch (button.itemId) {
            case 'btnAddDep':
                {
                    addDependencia = Ext.create('GestComponentes.view.WinInsDep');

                    addDependencia.show();
                    this.getBtnDelDep().setDisabled(true);
                    this.getBtnModDep().setDisabled(true);
                    break;
                }
            case 'btnModDep':
                {
                    var iddep = this.getGridpaneldependencias().getSelectionModel().getLastSelected().get('id');
                    var interface = this.getGridpaneldependencias().getSelectionModel().getLastSelected().get('interface');
                    var use = this.getGridpaneldependencias().getSelectionModel().getLastSelected().get('use');
                    var opcional = this.getGridpaneldependencias().getSelectionModel().getLastSelected().get('optional');


                    modDependencia = Ext.create('GestComponentes.view.WinModDep');
                    this.getFormadddep().getForm().findField('nombredep').setValue(iddep);
                    if (opcional == 'true') {
                        this.getFormadddep().getForm().findField('opcional').setValue(true);
                    } else {
                        this.getFormadddep().getForm().findField('opcional').setValue(false);
                    }


                    this.getFormadddep().getForm().findField('use').setValue(use);
                    this.getFormadddep().getForm().findField('interface').setValue(interface);

                    modDependencia.show();
                    this.getBtnDelDep().setDisabled(true);
                    this.getBtnModDep().setDisabled(true);
                    break;
                }
            case 'btnDelDep':
                {
                    var ids = this.getGridpaneldependencias().getSelectionModel().getSelection();


                    this.BorrarDependencia(ids);
                    this.getBtnDelDep().setDisabled(true);
                    this.getBtnModDep().setDisabled(true);

                    break;
                }
        }

    },
    BotonesGestEventGen: function(button) {
        switch (button.itemId) {
            case 'btnAddEventG':
                {
                    addEventoGen = Ext.create('GestComponentes.view.WinInsEventG');
                    addEventoGen.show();
                    this.getBtnModEventG().setDisabled(true);
                    this.getBtnDelEventG().setDisabled(true);
                    break;
                }
            case 'btnModEventG':
                {
                    var idevento = this.getGridpaneleventgen().getSelectionModel().getLastSelected().get('id');
                    var clase = this.getGridpaneleventgen().getSelectionModel().getLastSelected().get('class');
                    modEventoGen = Ext.create('GestComponentes.view.WinModEventG')
                    if (clase == '') {

                    } else {
                        this.getFormaddeventg().getForm().findField('clase').setValue(true);
                        this.getFormaddeventg().getForm().findField('nombreclase').setValue(clase);
                    }
                    this.getFormaddeventg().getForm().findField('nombreventg').setValue(idevento);

                    ;
                    modEventoGen.show();
                    this.getBtnDelEventG().setDisabled(true);
                    this.getBtnModEventG().setDisabled(true);
                    break;
                }
            case 'btnDelEventG':
                {
                    var ids = this.getGridpaneleventgen().getSelectionModel().getSelection();
                    this.BorrarEventGen(ids);
                    this.getBtnDelEventG().setDisabled(true);
                    this.getBtnModEventG().setDisabled(true);

                    break;
                }
        }

    },
    BotonesGestEventObs: function(button) {
        switch (button.itemId) {
            case 'btnAddEventO':
                {
                    addEventoObs = Ext.create('GestComponentes.view.WinInsEventO');
                    addEventoObs.show();
                    this.getBtnDelEventO().setDisabled(true);
                    this.getBtnModEventO().setDisabled(true);
                    break;
                }
            case 'btnModEventO':
                {
                    var source = this.getGridpaneleventobs().getSelectionModel().getLastSelected().get('source');
                    var impl = this.getGridpaneleventobs().getSelectionModel().getLastSelected().get('impl');
                    modEventoObs = Ext.create('GestComponentes.view.WinModEventO');
                    this.getFormaddevento().getForm().findField('nombrevento').setValue(source);
                    this.getFormaddevento().getForm().findField('impl').setValue(impl);

                    modEventoObs.show();
                    this.getBtnDelEventO().setDisabled(true);
                    this.getBtnModEventO().setDisabled(true);
                    break;
                }
            case 'btnDelEventO':
                {
                    var sources = this.getGridpaneleventobs().getSelectionModel().getSelection();
                    this.BorrarEventObs(sources);
                    this.getBtnDelEventO().setDisabled(true);
                    this.getBtnModEventO().setDisabled(true);

                    break;
                }
        }

    },
    insertarbundle: function() {
//        if (mod == true) {
            var me = this;
            if (forminscomp.getForm().findField('direccion').getValue() != '') {
                if (forminscomp.getForm().isValid() === true && forminscomp.getForm().findField('direccion').isValid() === true) {
                    forminscomp.getForm().submit({
                        url: 'insertarbundle',
                        waitMsg: perfil.etiquetas.lbMsgRegComp,
                        success: function(form, action) {
                            Ext.Msg.alert('Success', action.result.msg);
                        },
                        failure: function(form, action) {
                            if (action.result.codMsg != 3)
                            {
                                me.getGridpanelcomp().getStore().load({
                                    url: 'selArbol',
                                    params: {
                                        nombre: me.getArbolComp().getSelectionModel().getLastSelected().get('text'),
                                        direccion: me.getArbolComp().getSelectionModel().getLastSelected().get('dir')
                                    }});
                                me.getArbolComp().getStore().load();
                                me.getGridpanelcomp().show();
                                forminscomp.getForm().findField('direccion').reset();
                                if(aplicar){}else{
                                   me.getWininscomp().close();
                                   me.getBtnAddComp().setDisabled(true);
                                }
                                

                            } else {
                                if (action.result.codMsg == 3)
                                    mostrarMensaje(action.result.codMsg, action.result.mensaje);
                            }
                        }
                    });
                } else {
                    mostrarMensaje(3, perfil.etiquetas.lbMsgErrorEnCamops);
                }

            } else {
                mostrarMensaje(3, perfil.etiquetas.lbMsgEmptyField);
            }

//        } else {
//
//
//            var me = this;
//            if (forminscomp.getForm().findField('direccion').getValue() != '') {
//                if (forminscomp.getForm().isValid() === true && forminscomp.getForm().findField('direccion').isValid() === true) {
//                    forminscomp.getForm().submit({
//                        url: 'insertarbundle',
//                        waitMsg: perfil.etiquetas.lbMsgRegComp,
//                        failure: function(form, action) {
//                            if (action.result.codMsg != 3)
//                            {
//
//
//                                me.getGridpanelcomp().getStore().load({
//                                    url: 'selArbol',
//                                    params: {
//                                        nombre: me.getArbolComp().getSelectionModel().getLastSelected().get('text')
//                                    }});
//                                me.getArbolComp().getStore().load();
//                                me.getGridpanelcomp().show();
//                                wininscomp.close();
//
//
//                            } else {
//                                if (action.result.codMsg == 3)
//                                    mostrarMensaje(action.result.codMsg, action.result.mensaje);
//                            }
//                        }
//                    });
//                } else {
//                    mostrarMensaje(3, perfil.etiquetas.lbMsgErrorEnCamops);
//                }
//
//            } else {
//                mostrarMensaje(3, perfil.etiquetas.lbMsgEmptyField);
//            }
//
//
//
//        }





    },
    modificarBundle: function() {
        var me = this;
        if (formmodcomp.getForm().findField('direccion').getValue() != '' && formmodcomp.getForm().findField('nombre').getValue() != '' && formmodcomp.getForm().findField('version').getValue() != '') {
            if (formmodcomp.getForm().findField('version').isValid() === true && formmodcomp.getForm().findField('nombre').isValid() === true && formmodcomp.getForm().findField('direccion').isValid() === true && formmodcomp.getForm().isValid() === true && formmodcomp.getForm().findField('direccion').isDirty() === true && formmodcomp.getForm().findField('nombre').isDirty() === true && formmodcomp.getForm().findField('version').isDirty() === true) {

                this.getFormmodcomp().getForm().submit({
                    url: 'modificarbundle',
                    waitMsg: perfil.etiquetas.lbMsgModComp,
                    success: function(form, action) {
                        Ext.Msg.alert('Success', action.result.msg);
                    },
                    params: {
                        id: formmodcomp.getForm().findField('id').getValue(),
                        nombre: formmodcomp.getForm().findField('nombre').getValue(),
                        direccion: formmodcomp.getForm().findField('direccion').getValue(),
                        version: formmodcomp.getForm().findField('version').getValue()
                    },
                    failure: function(form, action) {

                        if (action.result.codMsg != 3)
                        {
                            me.getGridpanelcomp().getStore().load({
                                url: 'selArbol',
                                params: {
                                    nombre: me.getArbolComp().getSelectionModel().getLastSelected().get('text'),
                                    direccion: me.getArbolComp().getSelectionModel().getLastSelected().get('dir')
                                }});
                            winmodcomp.close();


                        } else {
                            if (action.result.codMsg == 3)
                                mostrarMensaje(action.result.codMsg, action.result.mensaje);
                        }
                    }
                });

            } else {
                mostrarMensaje(3, perfil.etiquetas.lbMsgErrorEnCamops);
            }
        } else {
            mostrarMensaje(3, perfil.etiquetas.lbMsgEmptyField);
        }

    },
    AdicionarParametro: function() {
        var nombrepar = this.getFormaddoper().getForm().findField('nombrepar').getValue();
        var tipo = this.getFormaddoper().getForm().findField('tipoparametro').getValue();
        var descripcion = this.getFormaddoper().getForm().findField('descripcion').getValue();
        if (this.getFormaddoper().getForm().findField('nombreoper').getValue() != '') {
            if (this.getFormaddoper().getForm().findField('nombreoper').isValid() === true) {
                if (this.getFormaddoper().getForm().findField('nombrepar').getValue() != '') {
                    if (this.getFormaddoper().getForm().findField('nombrepar').isValid() === true) {
                        if (this.getGridpanelparm().getStore().findExact('nombre', nombrepar)<0) {
                            this.getGridpanelparm().setDisabled(false);
                            this.getGridpanelparm().getStore().insert(0, {nombre: nombrepar, tipo: tipo, descripcion: descripcion});
                            this.getFormaddoper().getForm().findField('tipoparametro').reset();
                            this.getFormaddoper().getForm().findField('descripcion').reset();
                            this.getFormaddoper().getForm().findField('nombrepar').reset();
                        } else {
                            mostrarMensaje(3, perfil.etiquetas.lbMsgParmExist);
                        }
                    } else {
                        mostrarMensaje(3, perfil.etiquetas.lbMsgErrorEnCamops);
                    }



                } else {
                    mostrarMensaje(3, perfil.etiquetas.lbMsgEmptyFields);
                }
            } else {
                mostrarMensaje(3, perfil.etiquetas.lbMsgErrorEnCamops);
            }


        } else {
            mostrarMensaje(3, perfil.etiquetas.lbMsgEmptyFields);
        }


    },
    AdicionarParametrom: function() {
        var nombrepar = this.getFormmodoper().getForm().findField('nombreparm').getValue();
        var tipo = this.getFormmodoper().getForm().findField('tipoparametrom').getValue();
        var descripcion = this.getFormmodoper().getForm().findField('descripcionm').getValue();
        if (this.getFormmodoper().getForm().findField('nombreoperm').getValue() != '') {
            if (this.getFormmodoper().getForm().findField('nombreoperm').isValid() === true) {
                if (this.getFormmodoper().getForm().findField('nombreparm').getValue() != '') {
                    if (this.getFormmodoper().getForm().findField('nombreparm').isValid() === true) {

                        if (this.getGridpanelparmm().getStore().findExact('nombre', nombrepar)<0) {
                            this.getGridpanelparmm().setDisabled(false);
                            this.getGridpanelparmm().getStore().insert(0, {nombre: nombrepar, tipo: tipo, descripcion: descripcion});
                            this.getFormmodoper().getForm().findField('tipoparametrom').reset();
                            this.getFormmodoper().getForm().findField('descripcionm').reset();
                            this.getFormmodoper().getForm().findField('nombreparm').reset();
                        } else {
                            mostrarMensaje(3, perfil.etiquetas.lbMsgParmExist);
                        }
                    } else {
                        mostrarMensaje(3, perfil.etiquetas.lbMsgErrorEnCamops);
                    }




                } else {
                    mostrarMensaje(3, perfil.etiquetas.lbMsgEmptyFields);
                }
            } else {
                mostrarMensaje(3, perfil.etiquetas.lbMsgErrorEnCamops);
            }


        } else {
            mostrarMensaje(3, perfil.etiquetas.lbMsgEmptyFields);
        }

    },
    BotonesAddServicio: function(button) {
        switch (button.itemId) {
            case 'btncancAddServ':
                {
                    this.getGridpaneloper().getStore().removeAll();
                    this.getGridpanelparm().getStore().removeAll();

                    addservicio.close();
                    this.getBtnModServ().setDisabled(true);
                    this.getBtnDelServ().setDisabled(true);

                    break;
                }
            case 'btnapliAddServ':
                {
                    if (this.getFormaddserv().getForm().findField('nombreserv').getValue() != '') {
                        if (this.getFormaddserv().getForm().isValid() === true && this.getFormaddserv().getForm().findField('nombreserv').isValid() === true) {
                            aplicar = true;
                            var id=this.getFormaddserv().getForm().findField('nombreserv').getValue();
                            if(this.getGridpanelservicios().getStore().findExact('id',id)<0){
                                this.InsertarServicio();
                            this.getGridpaneloper().getStore().removeAll();
                            this.getFormaddserv().getForm().findField('nombreserv').reset();
                            this.getGridpanelparm().getStore().removeAll();
                            this.getFormaddoper().getForm().reset();
                            this.getFormaddoper().setDisabled(true);
                            }else{
                               mostrarMensaje(3, perfil.etiquetas.lbMsgServExist); 
                            }
                            


                        } else {
                            mostrarMensaje(3, perfil.etiquetas.lbMsgFieldError);
                        }

                    } else {
                        this.getGridpaneloper().setDisabled(true);
                        mostrarMensaje(3, perfil.etiquetas.lbMsgEmptyField);
                    }



                    break;
                }
            case 'btnacepAddServ':
                {
                    
                        if (this.getFormaddserv().getForm().findField('nombreserv').getValue() != '') {
                            if (this.getFormaddserv().getForm().isValid() === true && this.getFormaddserv().getForm().findField('nombreserv').isValid() === true) {
                                     aplicar = false;
                                     var id=this.getFormaddserv().getForm().findField('nombreserv').getValue();
                                     
                                     if(this.getGridpanelservicios().getStore().findExact('id',id)<0){
                                           this.InsertarServicio();

                                this.getGridpaneloper().getStore().removeAll();
                               this.getFormaddserv().getForm().findField('nombreserv').reset();  
                                     }else{
                                        
                                      mostrarMensaje(3, perfil.etiquetas.lbMsgServExist);
                                     }
                                

                           
                            } else {
                                mostrarMensaje(3, perfil.etiquetas.lbMsgFieldError);
                            }

                        } else {
                            this.getGridpaneloper().setDisabled(true);
                            mostrarMensaje(3, perfil.etiquetas.lbMsgEmptyField);
                        }


                   
                        //addservicio.close();
//                        if (this.getFormaddserv().getForm().findField('nombreserv').getValue() != '') {
//                            if (this.getFormaddserv().getForm().isValid() === true && this.getFormaddserv().getForm().findField('nombreserv').isValid() === true) {
//                                aplicar = false
//                                this.getGridpaneloper().getStore().removeAll();
//                                this.getFormaddserv().getForm().findField('nombreserv').reset();
//                                
//                                this.getBtnModServ().setDisabled(true);
//                                this.getBtnDelServ().setDisabled(true);
//
//                            } else {
//                                mostrarMensaje(3, perfil.etiquetas.lbMsgErrorEnCamops);
//                            }
//
//                        } else {
//                            this.getGridpaneloper().setDisabled(true);
//                            mostrarMensaje(3, perfil.etiquetas.lbMsgEmptyField);
//                        }


                    
                    break;
                }


        }
    },
    ActivarFormOperacion: function() {

        this.getGridpaneloper().setDisabled(false);
    },
    Hola: function() {
    },
    Seleccionar: function(node, record) {

        var me = this;

        if (this.getArbolComp().getSelectionModel().getLastSelected().get('componente')==true) {
            this.getBtnAddComp().setDisabled(true);
            this.getGridpanelcomp().getStore().load({
                waitMsg: perfil.etiquetas.lbMsgCarComp,
                scope: this,
                params: {nombre: record.get('text'), direccion: record.get('dir')},
                callback: function(records, operation, success) {
                    if (records != null) {
                        this.getTabpanelgeneral().setDisabled(false);
                        this.getBtnModComp().setDisabled(false);
                        this.getBtnDellComp().setDisabled(false);
                        this.getBtnHabComp().setDisabled(true);
                        if (serv) {
                            this.getGridpanelservicios().getStore().load({
                                params: {
                                    nombre: record.get('text')
                                }

                            });
                        }
                        if (dep) {
                            this.getGridpaneldependencias().getStore().load({
                                params: {
                                    direccion: record.get('dir'),
                                    nombre: record.get('text')
                                }
                            });
                        }
                        if (gen) {
                            this.getGridpaneleventgen().getStore().load({
                                params: {
                                    direccion: record.get('dir'),
                                    nombre: record.get('text')
                                }
                            });
                        }
                        if (obs) {
                            this.getGridpaneleventobs().getStore().load({
                                params: {
                                    direccion: record.get('dir'),
                                    nombre: record.get('text')
                                }
                            });
                        }

                    } else {
                        this.getTabpanelgeneral().setDisabled(true);
                        this.getBtnHabComp().setDisabled(false);
                        this.getBtnModComp().setDisabled(true);
                        this.getBtnDellComp().setDisabled(true);

                    }
                }
            });




        } else {

            this.getTabpanelgeneral().setDisabled(true);
            this.getBtnModComp().setDisabled(true);
            this.getBtnDellComp().setDisabled(true);
            this.getBtnHabComp().setDisabled(true);
            if (this.getArbolComp().getSelectionModel().getLastSelected().get('text') != 'Componentes') {
                this.getBtnAddComp().setDisabled(false);
            } else {
                this.getBtnAddComp().setDisabled(true);
            }


        }




    },
    HabilitarComponente: function() {
        var me = this;
        mostrarMensaje(2, perfil.etiquetas.lbMsgConfHab, function(btn) {
            me.habilitar(btn)
        });

    },
    habilitar: function(btn) {
        var me = this;
        if (btn === 'ok') {
            Ext.Ajax.request({
                url: 'habilitarComponente',
                waitMsg: perfil.etiquetas.lbMsgHabComp,
                method: 'POST',
                params: {
                    direccion: this.getArbolComp().getSelectionModel().getLastSelected().get('dir'),
                    nombre: this.getArbolComp().getSelectionModel().getLastSelected().get('text')
                },
                callback: function(options, success, response) {
                    responseData = Ext.decode(response.responseText);
                    me.getGridpanelcomp().getStore().load({
                        url: 'selArbol',
                        params: {
                            direccion: me.getArbolComp().getSelectionModel().getLastSelected().get('dir'),
                            nombre: me.getArbolComp().getSelectionModel().getLastSelected().get('text')
                        }
                    });
                    if (responseData.codMsg == 1)
                    {


                    } else {
                        if (responseData.codMsg == 3)
                            mostrarMensaje(responseData.codMsg, responseData.mensaje);
                    }

                }
            });
            this.getBtnModComp().setDisabled(false);
            this.getBtnDellComp().setDisabled(false);
            this.getBtnHabComp().setDisabled(true);
            this.getTabpanelgeneral().setDisabled(false);

        }
    },
    BorrarComponente: function() {
        var me = this;
        mostrarMensaje(2, perfil.etiquetas.lbMsgConfDHab, function(btn) {
            me.elimina(btn)
        });



    }, elimina: function(btnPresionado) {
        var me = this;
        var deletingMask = new Ext.LoadMask(Ext.getBody(), {
            msg: perfil.etiquetas.lbMsgDHabComp
        });
        if (btnPresionado === 'ok')
        {
            deletingMask.show();
            Ext.Ajax.request({
                url: 'eliminarBundle',
                waitMsg: perfil.etiquetas.lbMsgDHabComp,
                method: 'POST',
                params: {
                    direccion: this.getArbolComp().getSelectionModel().getLastSelected().get('dir'),
                    nombre: this.getArbolComp().getSelectionModel().getLastSelected().get('text')
                },
                callback: function(options, success, response) {
                    responseData = Ext.decode(response.responseText);
                    me.getGridpanelcomp().getStore().load({
                        url: 'selArbol',
                        params: {
                            direccion: me.getArbolComp().getSelectionModel().getLastSelected().get('dir'),
                            nombre: me.getArbolComp().getSelectionModel().getLastSelected().get('text')
                        }
                    });
                    if (responseData.codMsg == 1)
                    {

                        deletingMask.disable();
                        deletingMask.hide();
                        me.getGridpanelcomp().getStore().load();
                    } else {
                        if (responseData.codMsg == 3)
                            mostrarMensaje(responseData.codMsg, responseData.mensaje);
                        deletingMask.disable();
                        deletingMask.hide();
                    }
                }
            });
            this.getBtnModComp().setDisabled(true);
            this.getBtnDellComp().setDisabled(true);
            this.getBtnHabComp().setDisabled(false);
            this.getTabpanelgeneral().setDisabled(true);

        }
    },
    AdicionarOperacion: function() {
        var nombreoper = this.getFormaddoper().getForm().findField('nombreoper').getValue();
        
     
        var retorno = this.getFormaddoper().getForm().findField('retorno').getValue();
        var descripoper = this.getFormaddoper().getForm().findField('descripoper').getValue();
        var cont = this.getGridpanelparm().getStore().getCount();
        var params = new Array();

        for (var i = 0; i < cont; i++) {
            var obj = new Array();
            obj[0] = this.getGridpanelparm().getStore().getAt(i).getData().nombre;
            obj[1] = this.getGridpanelparm().getStore().getAt(i).getData().tipo;
            obj[2] = this.getGridpanelparm().getStore().getAt(i).getData().descripcion;
            params[i] = obj + '|';
        }
        if (modificar != true) {
            
            if (this.getGridpaneloper().getStore().findExact('nombre', nombreoper) <0) {
                if (this.getFormaddoper().getForm().findField('nombreoper').getValue() != '') {
                    if (this.getFormaddoper().getForm().findField('nombreoper').isValid() === true) {
                        this.getGridpaneloper().setDisabled(false);
                        this.getGridpaneloper().getStore().insert(0, {nombre: nombreoper, retorno: retorno, parametros: params, descripoper: descripoper, cantparm: cont});
                        this.getFormaddoper().getForm().reset();
                        this.getGridpanelparm().getStore().removeAll();
                        this.getFormaddoper().setDisabled(true);
                        this.getBtnModOper().setDisabled(true);
                        this.getBtnDellOper().setDisabled(true);
                    } else {
                        mostrarMensaje(3, perfil.etiquetas.lbMsgErrorEnCamops);
                    }

                } else {
                    mostrarMensaje(3, perfil.etiquetas.lbMsgEmptyFields);
                }


            } else {
                mostrarMensaje(3, perfil.etiquetas.lbMsgOperExist);
            }


        } else {
            if (this.getFormaddoper().getForm().findField('nombreoper').isValid() === true) {
                if (this.getFormaddoper().getForm().findField('nombreoper').isDirty() === true) {
                    if (this.getFormaddoper().getForm().findField('nombreoper').getValue() != '') {
                        this.getGridpaneloper().getStore().remove(this.getGridpaneloper().getSelectionModel().getLastSelected());
                        var nombreoper = this.getFormaddoper().getForm().findField('nombreoper').getValue();
                        var retorno = this.getFormaddoper().getForm().findField('retorno').getValue();
                        var descripoper = this.getFormaddoper().getForm().findField('descripoper').getValue();
                        var cont = this.getGridpanelparm().getStore().getCount();
                        var params = new Array();

                        for (var i = 0; i < cont; i++) {
                            var obj = new Array();
                            obj[0] = this.getGridpanelparm().getStore().getAt(i).getData().nombre;
                            obj[1] = this.getGridpanelparm().getStore().getAt(i).getData().tipo;
                            obj[2] = this.getGridpanelparm().getStore().getAt(i).getData().descripcion;
                            params[i] = obj + '|';
                        }
                        this.getGridpaneloper().setDisabled(false);
                        this.getGridpaneloper().getStore().insert(0, {nombre: nombreoper, retorno: retorno, parametros: params, descripoper: descripoper, cantparm: cont});

                        this.getFormaddoper().getForm().reset();
                        this.getGridpanelparm().getStore().removeAll();
                        this.getFormaddoper().setDisabled(true);
                        this.getBtnModOper().setDisabled(true);
                        this.getBtnDellOper().setDisabled(true);
                    } else {
                        mostrarMensaje(3, perfil.etiquetas.lbMsgEmptyFields);
                    }

                } else {
                    mostrarMensaje(3, perfil.etiquetas.lbMsgNoMod);
                }

            } else {
                mostrarMensaje(3, perfil.etiquetas.lbMsgErrorEnCamops);
            }

        }



    },
    AdicionarOperacionM: function() {

        var nombreoper = this.getFormmodoper().getForm().findField('nombreoperm').getValue();
        var retorno = this.getFormmodoper().getForm().findField('retornom').getValue();
        var descripoper = this.getFormmodoper().getForm().findField('descripoper').getValue();
        var cont = this.getGridpanelparmm().getStore().getCount();
        var params = new Array();

        for (var i = 0; i < cont; i++) {
            var obj = new Array();
            obj[0] = this.getGridpanelparmm().getStore().getAt(i).getData().nombre;
            obj[1] = this.getGridpanelparmm().getStore().getAt(i).getData().tipo;
            obj[2] = this.getGridpanelparmm().getStore().getAt(i).getData().descripcion;
            params[i] = obj + '|';
        }
        if (modificar != true) {
            if (this.getGridpaneloperm().getStore().findExact('nombre', nombreoper) <0) {

                this.getGridpaneloperm().setDisabled(false);
                this.getGridpaneloperm().getStore().insert(0, {nombre: nombreoper, retorno: retorno, parametros: params, descripoper: descripoper, cantparm: cont});
                this.getFormmodoper().getForm().reset();
                this.getGridpanelparmm().getStore().removeAll();
                this.getFormmodoper().setDisabled(true);
                this.getBtnModOperm().setDisabled(true);
                this.getBtnDellOperm().setDisabled(true);


            } else {
                mostrarMensaje(3, perfil.etiquetas.lbMsgOperExist);
            }


        } else {
            if (this.getFormmodoper().getForm().findField('nombreoperm').isValid() === true) {
                this.getGridpaneloperm().getStore().remove(this.getGridpaneloperm().getSelectionModel().getLastSelected());
                var nombreoper = this.getFormmodoper().getForm().findField('nombreoperm').getValue();
                var retorno = this.getFormmodoper().getForm().findField('retornom').getValue();
                var descripoper = this.getFormmodoper().getForm().findField('descripoper').getValue();
                var cont = this.getGridpanelparmm().getStore().getCount();
                var params = new Array();

                for (var i = 0; i < cont; i++) {
                    var obj = new Array();
                    obj[0] = this.getGridpanelparmm().getStore().getAt(i).getData().nombre;
                    obj[1] = this.getGridpanelparmm().getStore().getAt(i).getData().tipo;
                    obj[2] = this.getGridpanelparmm().getStore().getAt(i).getData().descripcion;
                    params[i] = obj + '|';
                }
                this.getGridpaneloperm().setDisabled(false);
                this.getGridpaneloperm().getStore().insert(0, {nombre: nombreoper, retorno: retorno, parametros: params, descripoper: descripoper, cantparm: cont});

                this.getFormmodoper().getForm().reset();
                this.getGridpanelparmm().getStore().removeAll();
                this.getFormmodoper().setDisabled(true);
            } else {
                mostrarMensaje(3, perfil.etiquetas.lbMsgErrorEnCamops);
            }

        }
    },
    InsertarServicio: function() {
        var me = this;
        var addMask = new Ext.LoadMask(Ext.getBody(), {
            msg: perfil.etiquetas.lbMsgAddServ
        });
        addMask.show();
        if (this.getFormaddserv().getForm().findField('nombreserv').getValue() != '') {
            var cant = this.getGridpaneloper().getStore().getCount();
            var retornos = new Array();
            var nombresoper = new Array();
            var parametros = new Array();
            var descripoper = new Array();
            var cantparams = new Array();

            for (var i = 0; i < cant; i++) {

                nombresoper[i] = this.getGridpaneloper().getStore().getAt(i).getData().nombre;
                retornos[i] = this.getGridpaneloper().getStore().getAt(i).getData().retorno;
                parametros[i] = this.getGridpaneloper().getStore().getAt(i).getData().parametros;
                descripoper[i] = this.getGridpaneloper().getStore().getAt(i).getData().descripoper;
                cantparams[i] = this.getGridpaneloper().getStore().getAt(i).getData().cantparm;

            }

            Ext.Ajax.request({
                url: 'insertarServicio',
                waitMsg: perfil.etiquetas.lbMsgAddServ,
                params: {
                    direccion: this.getArbolComp().getSelectionModel().getLastSelected().get('dir'),
                    nombre: this.getArbolComp().getSelectionModel().getLastSelected().get('text'),
                    nombreserv: this.getFormaddserv().getForm().findField('nombreserv').getValue(),
                    parametros: parametros.toString(),
                    cantoperaciones: cant,
                    nombresoper: nombresoper.toString(),
                    retornos: retornos.toString(),
                    descripoper: descripoper.toString(),
                    cantparams: cantparams.toString()
                },
                callback: function(options, success, response) {
                    responseData = Ext.decode(response.responseText);
                    me.getGridpanelservicios().getStore().load({
                        url: 'cargarServicios',
                        params: {nombre: me.getArbolComp().getSelectionModel().getLastSelected().get('text')}});
                    me.getGridpanelcomp().getStore().load({
                        url: 'selArbol',
                        params: {nombre: me.getArbolComp().getSelectionModel().getLastSelected().get('text'),
                            direccion: me.getArbolComp().getSelectionModel().getLastSelected().get('dir')}
                    });

                    if (responseData.codMsg == 1)
                    {

                        addMask.disable();
                        addMask.hide();
                        if (aplicar) {
                        } else {
                            addservicio.close();
                        }

                    } else {
//                        if (responseData.codMsg == 3)
                            mostrarMensaje(responseData.codMsg, responseData.mensaje);
                    }

                }

            });

        } else {
            mostrarMensaje(3, perfil.etiquetas.lbMsgEmptyField);
        }


    },
    MostrarInfo: function(node, record) {


        this.getBtnmodpar().setDisabled(false);
        this.getBtndellpar().setDisabled(false);
        this.getBtnaddpar().setDisabled(true);


        var nombre = this.getFormaddoper().getForm().findField('nombrepar').setValue(record.get('nombre'));
        var tipo = this.getFormaddoper().getForm().findField('tipoparametro').setValue(record.get('tipo'));
        var descripcion = this.getFormaddoper().getForm().findField('descripcion').setValue(record.get('descripcion'));

    },
    MostrarInfoM: function(node, record) {


        this.getBtnmodparm().setDisabled(false);
        this.getBtndellparm().setDisabled(false);
        this.getBtnaddparm().setDisabled(true);

        var nombrem = this.getFormmodoper().getForm().findField('nombreparm').setValue(record.get('nombre'));
        var tipom = this.getFormmodoper().getForm().findField('tipoparametrom').setValue(record.get('tipo'));
        var descripcionm = this.getFormmodoper().getForm().findField('descripcionm').setValue(record.get('descripcion'));


    },
    ModificarParametrom: function() {
        var nombrepar = this.getFormmodoper().getForm().findField('nombreparm').getValue();
        var tipo = this.getFormmodoper().getForm().findField('tipoparametrom').getValue();
        var descripcion = this.getFormmodoper().getForm().findField('descripcionm').getValue();
        if (this.getFormmodoper().getForm().findField('nombreparm').getValue() != '') {
            if (this.getFormmodoper().getForm().findField('nombreparm').isValid() === true) {
                var num = this.getGridpanelparmm().getSelectionModel().getLastSelected();

                this.getGridpanelparmm().getStore().remove(num);
                this.getGridpanelparmm().getStore().insert(0,
                        {nombre: nombrepar, tipo: tipo, descripcion: descripcion});

                this.getBtnaddparm().setDisabled(false);
                this.getBtnmodparm().setDisabled(true);
                this.getBtndellparm().setDisabled(true);
                this.getFormmodoper().getForm().findField('tipoparametrom').reset();
                this.getFormmodoper().getForm().findField('descripcionm').reset();
                this.getFormmodoper().getForm().findField('nombreparm').reset();

            } else {
                mostrarMensaje(3, perfil.etiquetas.lbMsgErrorEnCamops);
            }

        } else {
            mostrarMensaje(3, perfil.etiquetas.lbMsgEmptyFields);
        }

    },
    EliminarParametrom: function() {
        //var nombrepar = this.getFormmodoper().getForm().findField('nombreparm').getValue();
        var num = this.getGridpanelparmm().getSelectionModel().getSelection();
        this.getGridpanelparmm().getStore().removeAt(num);
        this.getBtnaddparm().setDisabled(false);
        this.getBtnmodparm().setDisabled(true);
        this.getBtndellparm().setDisabled(true);
        this.getFormmodoper().getForm().findField('tipoparametrom').reset();
        this.getFormmodoper().getForm().findField('descripcionm').reset();
        this.getFormmodoper().getForm().findField('nombreparm').reset();
    },
    ModificarParametro: function() {

        var nombrepar = this.getFormaddoper().getForm().findField('nombrepar').getValue();
        var tipo = this.getFormaddoper().getForm().findField('tipoparametro').getValue();
        var descripcion = this.getFormaddoper().getForm().findField('descripcion').getValue();

        if (this.getFormaddoper().getForm().findField('nombrepar').getValue() != '') {
            if (this.getFormaddoper().getForm().findField('nombrepar').isValid() === true) {
                var num = this.getGridpanelparm().getSelectionModel().getLastSelected();
                this.getGridpanelparm().getStore().remove(num);
                this.getGridpanelparm().getStore().insert(0,
                        {nombre: nombrepar, tipo: tipo, descripcion: descripcion});

                this.getBtnaddpar().setDisabled(false);
                this.getBtnmodpar().setDisabled(true);
                this.getBtndellpar().setDisabled(true);
                this.getFormaddoper().getForm().findField('tipoparametro').reset();
                this.getFormaddoper().getForm().findField('descripcion').reset();
                this.getFormaddoper().getForm().findField('nombrepar').setValue('');
            } else {
                mostrarMensaje(3, perfil.etiquetas.lbMsgErrorEnCamops);

            }

        } else {
            mostrarMensaje(3, perfil.etiquetas.lbMsgEmptyFields);
        }

    },
    EliminarParametro: function() {
        //var nombrepar = this.getFormaddoper().getForm().findField('nombrepar').getValue();
        var num = this.getGridpanelparm().getSelectionModel().getSelection();
        this.getGridpanelparm().getStore().remove(num);
        this.getBtnaddpar().setDisabled(false);
        this.getBtnmodpar().setDisabled(true);
        this.getBtndellpar().setDisabled(true);
        this.getFormaddoper().getForm().findField('tipoparametro').reset();
        this.getFormaddoper().getForm().findField('descripcion').reset();
        this.getFormaddoper().getForm().findField('nombrepar').reset();
    }, BorrarServicio: function(ids) {
        var me = this;
        mostrarMensaje(2, perfil.etiquetas.lbMsgConfDelServ, function(btn) {
            me.EliminarServicio(ids, btn)
        });




    },
    EliminarServicio: function(ids, btn) {
        var me = this;
        var deletingMask = new Ext.LoadMask(Ext.getBody(), {
            msg: perfil.etiquetas.lbMsgDelServ
        });
        if (btn == 'ok') {
            deletingMask.show();
            var a = new Array();
            for (var i = 0; i < ids.length; i++) {
                a[i] = ids[i].get('id');
            }
            var b = a.toString();

            Ext.Ajax.request({
                url: 'eliminarServicio',
                waitMsg: perfil.etiquetas.lbMsgDelServ,
                params: {
                    ids: b,
                    direccion: this.getArbolComp().getSelectionModel().getLastSelected().get('dir'),
                    nombre: this.getArbolComp().getSelectionModel().getLastSelected().get('text')

                },
                callback: function(options, success, response) {
                    responseData = Ext.decode(response.responseText);
                    me.getGridpanelservicios().getStore().load({
                        url: 'cargarServicios',
                        params: {nombre: me.getArbolComp().getSelectionModel().getLastSelected().get('text')}});
                    me.getGridpanelcomp().getStore().load({
                        url: 'selArbol',
                        params: {nombre: me.getArbolComp().getSelectionModel().getLastSelected().get('text'),
                            direccion: me.getArbolComp().getSelectionModel().getLastSelected().get('dir')}});

                    if (responseData.codMsg == 1)
                    {

                        deletingMask.disable();
                        deletingMask.hide();

                    } else {
                        if (responseData.codMsg == 3)
                            mostrarMensaje(responseData.codMsg, responseData.mensaje);
                    }

                }
            });
        }


    },
    BotonesModServicio: function(button) {
        switch (button.itemId) {
            case 'btncancModServ':
                {
                    this.getGridpaneloperm().getStore().removeAll();
                    this.getGridpanelparmm().getStore().removeAll();
                    this.getBtnModServ().setDisabled(true);
                    this.getBtnDelServ().setDisabled(true);
                    this.getBtnModOperm().setDisabled(true);
                    this.getBtnDellOperm().setDisabled(true);

                    modservicio.close();
                    changed = false;
                    break;
                }
            case 'btnacepModServ':
                {
                    var a=this.getGridpaneloperm().getStore().getNewRecords().length;
                    var b=this.getGridpaneloperm().getStore().getUpdatedRecords().length;
                    var c=this.getGridpaneloperm().getStore().getRemovedRecords().length;
                    var id = this.getGridpanelservicios().getSelectionModel().getLastSelected().get('id');
             
                    if (this.getFormmodserv().getForm().findField('nombreservm').getValue() != '') {
                        if (this.getFormmodserv().getForm().findField('nombreservm').isValid() === true) {
                            if (a === 0 && b === 0 && c === 0 && this.getFormmodserv().getForm().findField('nombreservm').getValue()==id) {
                                
                                mostrarMensaje(3, perfil.etiquetas.lbMsgNoMod);
                                
                            } else {

                                
                               
                                   this.ModificarServicio(id);

                                this.getGridpaneloperm().getStore().removeAll();
                                this.getBtnModServ().setDisabled(true);
                                this.getBtnDelServ().setDisabled(true);
                                this.getBtnModOperm().setDisabled(true);
                                this.getBtnDellOperm().setDisabled(true);

                                changed = false;  

                            }


                        } else {
                            mostrarMensaje(3, perfil.etiquetas.lbMsgEmptyFields);
                        }

                    } else {
                        this.getGridpaneloperm().setDisabled(true);
                        mostrarMensaje(3, perfil.etiquetas.lbMsgEmptyField);
                    }


                    break;
                }
        }
    },
    ActivarBotones: function() {
        this.getBtnModServ().setDisabled(false);
        this.getBtnDelServ().setDisabled(false);

    },
    DesactivarBotonesmod: function() {
        this.getBtnModServ().setDisabled(true);
        this.getBtnDelServ().setDisabled(true);
    },
    DesactivarBotonesadd: function() {
        this.getBtnModServ().setDisabled(true);
        this.getBtnDelServ().setDisabled(true);
    },
    ModificarServicio: function(id) {
        var me = this;
        var modMask = new Ext.LoadMask(Ext.getBody(), {
            msg: perfil.etiquetas.lbMsgModServ
        });
        modMask.show();
        if (this.getFormmodserv().getForm().findField('nombreservm').getValue() != '') {
            var cant = this.getGridpaneloperm().getStore().getCount();
            var retornos = new Array();
            var nombresoper = new Array();
            var parametros = new Array();
            var descripoper = new Array();
            var cantparams = new Array();

            for (var i = 0; i < cant; i++) {

                nombresoper[i] = this.getGridpaneloperm().getStore().getAt(i).getData().nombre;
                retornos[i] = this.getGridpaneloperm().getStore().getAt(i).getData().retorno;
                parametros[i] = this.getGridpaneloperm().getStore().getAt(i).getData().parametros;
                descripoper[i] = this.getGridpaneloperm().getStore().getAt(i).getData().descripoper;
                cantparams[i] = this.getGridpaneloperm().getStore().getAt(i).getData().cantparm;

            }
            Ext.Ajax.request({
                url: 'modificarServicio',
                waitMsg: perfil.etiquetas.lbMsgModServ,
                params: {
                    id: id,
                    direccion: this.getArbolComp().getSelectionModel().getLastSelected().get('dir'),
                    nombre: this.getArbolComp().getSelectionModel().getLastSelected().get('text'),
                    nombreserv: this.getFormmodserv().getForm().findField('nombreservm').getValue(),
                    parametros: parametros.toString(),
                    cantoperaciones: cant,
                    nombresoper: nombresoper.toString(),
                    retornos: retornos.toString(),
                    descripoper: descripoper.toString(),
                    cantparams: cantparams.toString()
                },
                callback: function(options, success, response) {
                    responseData = Ext.decode(response.responseText);
                    me.getGridpanelservicios().getStore().load({
                        url: 'cargarServicios',
                        params: {nombre: me.getArbolComp().getSelectionModel().getLastSelected().get('text')}});
                    me.getGridpanelcomp().getStore().load({
                        url: 'selArbol',
                        params: {nombre: me.getArbolComp().getSelectionModel().getLastSelected().get('text'),
                            direccion: me.getArbolComp().getSelectionModel().getLastSelected().get('die')}});

                    if (responseData.codMsg == 1)
                    {

                        modMask.disable();
                        modMask.hide();
                        modservicio.close();

                    } else {
                        if (responseData.codMsg == 3)
                            mostrarMensaje(responseData.codMsg, responseData.mensaje);
                    }

                }
            });


        } else {
            mostrarMensaje(3, perfil.etiquetas.lbMsgEmptyField);
        }

    },
    BotonesGridOper: function(button) {
        switch (button.itemId) {
            case 'btnAddOper':
                {
                    this.getFormaddoper().setDisabled(false);
                    this.getGridpaneloper().setDisabled(true);
                    modificar = false;

                    break;
                }
            case 'btnModOper':
                {
                    this.getFormaddoper().setDisabled(false);
                    var nombreoper = this.getGridpaneloper().getSelectionModel().getLastSelected().get('nombre');
                    var retorno = this.getGridpaneloper().getSelectionModel().getLastSelected().get('retorno');
                    var descripcion = this.getGridpaneloper().getSelectionModel().getLastSelected().get('descripoper');
                    var parametros = this.getGridpaneloper().getSelectionModel().getLastSelected().get('parametros');
                    var arreglo = Array();
                    for (var i = 0; i < parametros.length; i++) {

                        var nuevo = parametros[i].split(',');
                        var nombre = nuevo[0];
                        var tipo = nuevo[1];
                        var temp = nuevo[2];
                        var pos = temp.lastIndexOf('|');
                        var descrip = temp.slice(0, pos);
                        arreglo[i] = {nombre: nombre, tipo: tipo, descripcion: descrip};
                    }

                    this.getFormaddoper().getForm().findField('nombreoper').setValue(nombreoper);
                    this.getFormaddoper().getForm().findField('retorno').setValue(retorno);
                    this.getFormaddoper().getForm().findField('descripoper').setValue(descripcion);
                    this.getGridpanelparm().getStore().loadData(arreglo);
                    this.getGridpaneloper().setDisabled(true);
                    modificar = true;
                    break;
                }
            case 'btnDellOper':
                {
                    this.getGridpaneloper().getStore().remove(this.getGridpaneloper().getSelectionModel().getSelection());
                    break;
                }
        }
    },
    ActivarBotonesoperM: function(nodo, record) {


        this.getBtnModOperm().setDisabled(false);
        this.getBtnDellOperm().setDisabled(false);


    },
    CancOperacion: function() {
        this.getFormaddoper().getForm().reset();
        this.getGridpanelparm().getStore().removeAll();
        this.getFormaddoper().setDisabled(true);

        this.getGridpaneloper().setDisabled(false);
        this.getBtnModOper().setDisabled(true);
        this.getBtnDellOper().setDisabled(true)

    },
    CancOperacionM: function() {
        this.getFormmodoper().getForm().reset();
        this.getGridpanelparmm().getStore().removeAll();
        this.getFormmodoper().setDisabled(true);

        this.getGridpaneloperm().setDisabled(false);
        this.getBtnModOperm().setDisabled(true);
        this.getBtnDellOperm().setDisabled(true);

    },
    ActivarBotonesGridOper: function() {
        this.getBtnModOper().setDisabled(false);
        this.getBtnDellOper().setDisabled(false);
    },
    NodoDirDep: function(record) {
        this.getArbolDep().getStore().getProxy().extraParams = {text: record.get('text')};

    },
    NodoDirEventG: function(record) {
        this.getArbolEventGen().getStore().getProxy().extraParams = {text: record.get('text')};
    },
    NodoDirEventO: function(record) {
        this.getArbolEventObs().getStore().getProxy().extraParams = {text: record.get('text')};
    },
    BotonesInsDep: function(button) {
        switch (button.itemId) {
            case 'btncancAddDep':
                {
                    this.getBtnapliAddDep().setDisabled(false);
                    this.getWininsdep().close();

                    break;
                }
            case 'btnapliAddDep':
                {

                    if (this.getFormadddep().getForm().findField('nombredep').getValue() != '' && this.getFormadddep().getForm().findField('interface').getValue() != '') {
                        if (this.getFormadddep().getForm().findField('nombredep').isValid() === true && this.getFormadddep().getForm().findField('interface').isValid() === true) {
                            var encontrado = this.getGridpaneldependencias().getStore().find('id', this.getFormadddep().getForm().findField('nombredep').getValue());
                            var dir=this.getGridpaneldependencias().getStore().find('interface',this.getFormadddep().getForm().findField('interface').getValue());
                            if(dir> 0){
                                mostrarMensaje(3, perfil.etiquetas.lbMsgIntExist);
                            }else{
                             if (encontrado >0) {
                                mostrarMensaje(3, perfil.etiquetas.lbMsgDepExist);

                            } else {
                                modificar = false;
                                this.InsertarDependencia();
                                aplicar = true;

                            }   
                            }
                            
                        } else {
                            mostrarMensaje(3, perfil.etiquetas.lbMsgErrorEnCamops);
                        }


                    } else {
                        mostrarMensaje(3, perfil.etiquetas.lbMsgEmptyFields);
                    }

                    break;
                }
            case 'btnacepAddDep':
                {

                   
                        if (this.getFormadddep().getForm().findField('nombredep').getValue() != '' && this.getFormadddep().getForm().findField('interface').getValue() != '') {
                            if (this.getFormadddep().getForm().findField('nombredep').isValid() === true && this.getFormadddep().getForm().findField('interface').isValid() === true) {
                                var encontrado = this.getGridpaneldependencias().getStore().find('id', this.getFormadddep().getForm().findField('nombredep').getValue());
                                var dir=this.getGridpaneldependencias().getStore().find('interface',this.getFormadddep().getForm().findField('interface').getValue());
                            if(dir > 0){
                                 mostrarMensaje(3, perfil.etiquetas.lbMsgIntExist);
                            }else{
                              if (encontrado >0) {
                                    mostrarMensaje(3, perfil.etiquetas.lbMsgDepExist);

                                } else {
                                    aplicar = false;
                                    modificar = false;
                                    this.InsertarDependencia();

                                }  
                            }    
                            
                            } else {
                                mostrarMensaje(3, perfil.etiquetas.lbMsgErrorEnCamops);
                            }


                        } else {
                            mostrarMensaje(3, perfil.etiquetas.lbMsgEmptyFields);
                        }


                    

                    break;
                }
        }
    },ModificarDependencia:function(){
        var modMask = new Ext.LoadMask(Ext.getBody(), {
            msg: perfil.etiquetas.lbMsgModDep
        });
        var me=this;
        var nombredep = this.getFormadddep().getForm().findField('nombredep').getValue();
        var opcional = this.getFormadddep().getForm().findField('opcional').getValue();
        var interfacedep = this.getFormadddep().getForm().findField('interface').getValue();
        var use = this.getFormadddep().getForm().findField('use').getValue();
        var uso;
        if (use == '') {
            uso = false;
        } else {
            uso = use;
        }
         modMask.show();
            Ext.Ajax.request({
                url: 'modificarDependencia',
                waitMsg: perfil.etiquetas.lbMsgModDep,
                params: {
                    direccion: this.getArbolComp().getSelectionModel().getLastSelected().get('dir'),
                    nombre: this.getArbolComp().getSelectionModel().getLastSelected().get('text'),
                    nombredep: nombredep,
                    nomold: this.getGridpaneldependencias().getSelectionModel().getLastSelected().get('id'),
                    opcional: opcional,
                    interfacedep: interfacedep,
                    use: use
                },
                callback: function(options, success, response) {
                    responseData = Ext.decode(response.responseText);
                    me.getGridpaneldependencias().getStore().load({
                        url: 'cargarDependencias',
                        params: {nombre: me.getArbolComp().getSelectionModel().getLastSelected().get('text')}});
                    me.getGridpanelcomp().getStore().load({
                        url: 'selArbol',
                        params: {nombre: me.getArbolComp().getSelectionModel().getLastSelected().get('text'),
                            direccion: me.getArbolComp().getSelectionModel().getLastSelected().get('dir')}
                    });

                    if (responseData.codMsg == 1)
                    {

                        modMask.disable();
                        modMask.hide();
                        modDependencia.close();

                    } else {
                        if (responseData.codMsg == 3)
                            mostrarMensaje(responseData.codMsg, responseData.mensaje);
                        modMask.disable();
                        modMask.hide();
                    }

                }

            });
    },
    InsertarDependencia: function() {
        var me = this;
        var addMask = new Ext.LoadMask(Ext.getBody(), {
            msg: perfil.etiquetas.lbMsgAddDep
        });
        

        var nombredep = this.getFormadddep().getForm().findField('nombredep').getValue();
        var opcional = this.getFormadddep().getForm().findField('opcional').getValue();
        var interfacedep = this.getFormadddep().getForm().findField('interface').getValue();
        var use = this.getFormadddep().getForm().findField('use').getValue();
        var uso;
        if (use == '') {
            uso = false;
        } else {
            uso = use;
        }

        
            addMask.show();
            Ext.Ajax.request({
                url: 'insertarDependencia',
                waitMsg: perfil.etiquetas.lbMsgAddDep,
                params: {
                    direccion: this.getArbolComp().getSelectionModel().getLastSelected().get('dir'),
                    nombre: this.getArbolComp().getSelectionModel().getLastSelected().get('text'),
                    nombredep: nombredep,
                    opcional: opcional,
                    interfacedep: interfacedep,
                    use: uso
                },
                callback: function(options, success, response) {
                    responseData = Ext.decode(response.responseText);
                    me.getGridpaneldependencias().getStore().load({
                        url: 'cargarDependencias',
                        params: {nombre: me.getArbolComp().getSelectionModel().getLastSelected().get('text')}});
                    me.getGridpanelcomp().getStore().load({
                        url: 'selArbol',
                        params: {nombre: me.getArbolComp().getSelectionModel().getLastSelected().get('text'),
                            direccion: me.getArbolComp().getSelectionModel().getLastSelected().get('dir')}
                    });

                    if (responseData.codMsg == 1)
                    {

                        addMask.disable();
                        addMask.hide();
                        me.getFormadddep().getForm().findField('nombredep').reset();
                        me.getFormadddep().getForm().findField('opcional').reset();
                        me.getFormadddep().getForm().findField('interface').reset();
                        me.getFormadddep().getForm().findField('use').reset();
                        if (aplicar) {
                            
                        } else {
                            addDependencia.close();
                        }
                        


                    } else {
                        if (responseData.codMsg == 3)
                            mostrarMensaje(responseData.codMsg, responseData.mensaje);
                        addMask.disable();
                        addMask.hide();
                    }

                }

            });

       

    },
    ActivarBotonesGDep: function() {
        this.getBtnModDep().setDisabled(false);
        this.getBtnDelDep().setDisabled(false);
    }, BorrarDependencia: function(ids) {
        var me = this;
        mostrarMensaje(2, perfil.etiquetas.lbMsgConfDDep, function(btn) {
            me.EliminarDependencia(ids, btn)
        });



    },
    EliminarDependencia: function(ids, btn) {
        var me = this;

        var DelMask = new Ext.LoadMask(Ext.getBody(), {
            msg: perfil.etiquetas.lbMsgDelDep
        });
        if (btn == 'ok') {
            DelMask.show();
            var a = new Array();
            for (var i = 0; i < ids.length; i++) {
                a[i] = ids[i].get('id');
            }
            var b = a.toString();

            Ext.Ajax.request({
                url: 'eliminarDependencia',
                waitMsg: perfil.etiquetas.lbMsgDelDep,
                params: {
                    ids: b,
                    direccion: this.getArbolComp().getSelectionModel().getLastSelected().get('dir'),
                    nombre: this.getArbolComp().getSelectionModel().getLastSelected().get('text')

                },
                callback: function(options, success, response) {
                    responseData = Ext.decode(response.responseText);
                    me.getGridpaneldependencias().getStore().load({
                        url: 'cargarDependencias',
                        params: {nombre: me.getArbolComp().getSelectionModel().getLastSelected().get('text')}});
                    me.getGridpanelcomp().getStore().load({
                        url: 'selArbol',
                        params: {nombre: me.getArbolComp().getSelectionModel().getLastSelected().get('text'),
                            direccion: me.getArbolComp().getSelectionModel().getLastSelected().get('dir')}
                    });

                    if (responseData.codMsg == 1) {
                        DelMask.disable();
                        DelMask.hide();

                    } else {
                        if (responseData.codMsg == 3)
                            mostrarMensaje(responseData.codMsg, responseData.mensaje);
                        DelMask.disable();
                        DelMask.hide();
                    }
                }
            });

        } else {

        }

    },
    BotonesArbolDep: function(button) {
        switch (button.itemId) {
            case'btnSelDep':
                {

                    var interface = this.getArbolDep().getSelectionModel().getLastSelected().get('interface');
                    var text = this.getArbolDep().getSelectionModel().getLastSelected().get('text');
                    var c = text.charAt(0);
                    var d = c.toUpperCase();
                    var arr1 = text.split(c);
                    var Texto = d + arr1[1];
                    var inter = interface.split('services');
                    var nombre = this.getArbolComp().getSelectionModel().getLastSelected().get('text');
                    var a = nombre.charAt(0);
                    var b = a.toUpperCase();
                    var arr = nombre.split(a);
                    var nomb = b + arr[1];
                    var dirseparator = inter[1].charAt(0);

                    this.getFormadddep().getForm().findField('interface').setValue('services' + dirseparator + nomb + Texto + '.php');
                    this.getBtnSelDep().setDisabled(true);
                    this.getBtnSelUso().setDisabled(true);

                    break;
                }
            case'btnSelUso':
                {
                    var nombre = this.getArbolDep().getSelectionModel().getLastSelected().get('text');
                    var version = this.getArbolDep().getSelectionModel().getLastSelected().get('version');
                    var uso = nombre + '-' + version;
                    this.getFormadddep().getForm().findField('use').setValue(uso);
                    this.getBtnSelDep().setDisabled(true);
                    this.getBtnSelUso().setDisabled(true);
                    break;
                }
        }
    },
    ActivarBotonesArbDep: function() {
        if (this.getArbolDep().getSelectionModel().getLastSelected().isLeaf()) {
            this.getBtnSelDep().setDisabled(false);
            this.getBtnSelUso().setDisabled(true);
        } else {
            this.getBtnSelUso().setDisabled(false);
            this.getBtnSelDep().setDisabled(true)
        }

    },
    BotonesModDep: function(button) {
        switch (button.itemId) {
            case 'btncancAddDepM' :
                {
                    modDependencia.close();
                    this.getBtnModDep().setDisabled(true);
                    this.getBtnDelDep().setDisabled(true);
                    break;
                }
            case 'btnacepAddDepM' :
                {
                    var nom = this.getGridpaneldependencias().getSelectionModel().getLastSelected().get('id');
                    var use = this.getGridpaneldependencias().getSelectionModel().getLastSelected().get('use');

                    var interface = this.getGridpaneldependencias().getSelectionModel().getLastSelected().get('interface');
                    var optional = this.getGridpaneldependencias().getSelectionModel().getLastSelected().get('optional');
                    var opt;
                    if (optional == 'true') {
                        opt = true;
                    } else {
                        opt = false;
                    }



                    if (this.getFormadddep().getForm().findField('nombredep').getValue() != '' && this.getFormadddep().getForm().findField('use').getValue() != '' && this.getFormadddep().getForm().findField('interface').getValue() != '') {
                        if (this.getFormadddep().getForm().findField('nombredep').isValid() === true && this.getFormadddep().getForm().findField('interface').isValid() === true) {
                            if (this.getFormadddep().getForm().findField('nombredep').getValue() == nom && this.getFormadddep().getForm().findField('use').getValue() == use && this.getFormadddep().getForm().findField('interface').getValue() == interface && this.getFormadddep().getForm().findField('opcional').getValue() == opt) {
                                mostrarMensaje(3, 'Por favor verifique, no ha realizado ninguna modificación.');

                            } else {
                                if (this.getFormadddep().getForm().findField('nombredep').getValue() != nom || this.getFormadddep().getForm().findField('use').getValue() != use || this.getFormadddep().getForm().findField('interface').getValue() != interface || this.getFormadddep().getForm().findField('opcional').getValue() != opt) {
                                    modificar = true;
                                    this.ModificarDependencia();
                                    modDependencia.close();
                                    this.getBtnModDep().setDisabled(true);
                                    this.getBtnDelDep().setDisabled(true);
                                }



                            }
                        } else {
                            mostrarMensaje(3, perfil.etiquetas.lbMsgErrorEnCamops);
                        }



                    } else {
                        mostrarMensaje(3, perfil.etiquetas.lbMsgEmptyFields);
                    }
                    break;

                }
        }
    },
    CambiarEstadoServ: function() {
        serv = false;
    },
    CambiarEstadoDep: function() {
        dep = false;
    },
    CambiarEstadoGen: function() {
        gen = false;
    },
    CambiarEstadoObs: function() {
        obs = false;
    },
    ActivarBotonArbEG: function() {
        this.getBtnSelEventGen().setDisabled(false);
    },
    ActivarBotonArbEO: function() {
        if (this.getArbolEventObs().getSelectionModel().getLastSelected().isLeaf()) {
            this.getBtnSelEventObs().setDisabled(false);
        } else {
            this.getBtnSelEventObs().setDisabled(true);
        }

    },
    GenerarEvento: function() {

        var nombreventg = this.getArbolEventGen().getSelectionModel().getLastSelected().get('text');
        var num = 1 + this.getGridpaneleventgen().getStore().find('id', nombreventg);

        this.getFormaddeventg().getForm().findField('nombreventg').setValue(nombreventg + 'Event' + num);
    },
    SelecionarEvento: function() {
        var nombrevento = this.getArbolEventObs().getSelectionModel().getLastSelected().get('text');
        this.getFormaddevento().getForm().findField('nombrevento').setValue(nombrevento);
        var impl = nombrevento + "Observer.php";
        this.getFormaddevento().getForm().findField('impl').setValue(impl);




    },
    BotonesWinInsEG: function(button) {
        switch (button.itemId) {
            case 'btncancAddEventG':
                {

                    this.getWininseventg().close();

                    break;
                }
            case 'btnapliAddEventG':
                {

                    if (this.getFormaddeventg().getForm().findField('nombreventg').getValue() != '') {
                        if (this.getFormaddeventg().getForm().findField('nombreventg').isValid() === true) {
                            var find = this.getGridpaneleventgen().getStore().find('id', this.getFormaddeventg().getForm().findField('nombreventg').getValue());
                            if (find > 0) {
                                mostrarMensaje(3, perfil.etiquetas.lbMsgEventExist);
                            } else {
                                modificar = false;
                                aplicar = true;
                                this.InsertarEventG();

                            }
                        } else {
                            mostrarMensaje(3, perfil.etiquetas.lbMsgErrorEnCamops);
                        }


                    } else {
                        mostrarMensaje(3, perfil.etiquetas.lbMsgEmptyFields);
                    }

                    break;
                }
            case 'btnacepAddEventG':
                {
                    if (this.getFormaddeventg().getForm().findField('nombreventg').getValue() != '') {
                        if (this.getFormaddeventg().getForm().findField('nombreventg').isValid() === true) {
                            var find = this.getGridpaneleventgen().getStore().find('id', this.getFormaddeventg().getForm().findField('nombreventg').getValue());

                                if (find > 0) {
                                    mostrarMensaje(3, perfil.etiquetas.lbMsgEventExist);
                                } else {

                                    modificar = false;
                                    aplicar = false;
                                    this.InsertarEventG();
                                    aplicar = false;

                                }

                            

                        } else {
                            mostrarMensaje(3, perfil.etiquetas.lbMsgErrorEnCamops);
                        }


                    } else {
                        mostrarMensaje(3, perfil.etiquetas.lbMsgEmptyFields);
                    }

                    break;

                }
        }
    }, ModificarEventG: function() {
        var me = this;
        var nombreventg = this.getFormaddeventg().getForm().findField('nombreventg').getValue();
        var clase = this.getFormaddeventg().getForm().findField('clase').getValue();
        var nombreclase = this.getFormaddeventg().getForm().findField('nombreclase').getValue();
        if (clase == true) {

        } else {
            nombreclase = false;
        }
        this.getFormaddeventg().getForm().submit({
            url: 'modificarEventG',
            waitMsg: perfil.etiquetas.lbMsgModEvent,
            params: {
                direccion: this.getArbolComp().getSelectionModel().getLastSelected().get('dir'),
                nombre: this.getArbolComp().getSelectionModel().getLastSelected().get('text'),
                nomold: this.getGridpaneleventgen().getSelectionModel().getLastSelected().get('id')
            },
            failure: function(form, action) {
                if (action.result.codMsg != 3)
                {
                    me.getGridpaneleventgen().getStore().load({
                        url: 'cargarEventosGen',
                        params: {nombre: me.getArbolComp().getSelectionModel().getLastSelected().get('text')}});
                    me.getGridpanelcomp().getStore().load({
                        url: 'selArbol',
                        params: {nombre: me.getArbolComp().getSelectionModel().getLastSelected().get('text'),
                            direccion: me.getArbolComp().getSelectionModel().getLastSelected().get('die')}});


                    me.getWinmodeventg().close();





                } else {
                    if (action.result.codMsg == 3)
                        mostrarMensaje(action.result.codMsg, action.result.mensaje);
                }
            }
        });


    },
    InsertarEventG: function() {
        var me = this;

        var nombreventg = this.getFormaddeventg().getForm().findField('nombreventg').getValue();
        var clase = this.getFormaddeventg().getForm().findField('clase').getValue();
        var nombreclase = this.getFormaddeventg().getForm().findField('nombreclase').getValue();



        if (clase == true) {

        } else {
            nombreclase = false;
        }
        this.getFormaddeventg().getForm().submit({
            url: 'insertarEventG',
            waitMsg: perfil.etiquetas.lbMsgAddEvent,
            params: {
                nombreventg: nombreventg,
                nombreclase: nombreclase,
                direccion: this.getArbolComp().getSelectionModel().getLastSelected().get('dir'),
                nombre: this.getArbolComp().getSelectionModel().getLastSelected().get('text')
            },
            failure: function(form, action) {
                if (action.result.codMsg != 3)
                {
                    me.getGridpaneleventgen().getStore().load({
                        url: 'cargarEventosGen',
                        params: {nombre: me.getArbolComp().getSelectionModel().getLastSelected().get('text')}});
                    me.getGridpanelcomp().getStore().load({
                        url: 'selArbol',
                        params: {nombre: me.getArbolComp().getSelectionModel().getLastSelected().get('text'),
                            direccion: me.getArbolComp().getSelectionModel().getLastSelected().get('dir')}
                    });
                    me.getFormaddeventg().getForm().findField('nombreventg').reset();
                    me.getFormaddeventg().getForm().findField('clase').reset();
                    me.getFormaddeventg().getForm().findField('nombreclase').reset();
                    if (aplicar) {

                    } else {
                        me.getWininseventg().close();
                    }

                } else {
                    if (action.result.codMsg == 3)
                        mostrarMensaje(action.result.codMsg, action.result.mensaje);
                }
            }
        });






    },
    AdicionarTextField: function() {

        if (this.getFormaddeventg().getForm().findField('clase').getValue() == true) {
            this.getFormaddeventg().getForm().findField('nombreclase').setDisabled(false);
            this.getFormaddeventg().getForm().findField('nombreclase').setValue('Obj' + this.getFormaddeventg().getForm().findField('nombreventg').getValue() + '.php')
        } else {
            this.getFormaddeventg().getForm().findField('nombreclase').setDisabled(true);
            this.getFormaddeventg().getForm().findField('nombreclase').reset();
        }

    },
    ActivarBotonesGEventG: function() {
        this.getBtnModEventG().setDisabled(false);
        this.getBtnDelEventG().setDisabled(false);
    }, BorrarEventGen: function(ids) {
        var me = this;
        mostrarMensaje(2, perfil.etiquetas.lbMsgConfDEvent, function(btn) {
            me.EliminarEventGen(ids, btn)
        });



    },
    EliminarEventGen: function(ids, btn) {
        var me = this;
        var delMask = new Ext.LoadMask(Ext.getBody(), {
            msg: perfil.etiquetas.lbMsgDelEvent
        });
        if (btn == 'ok') {
            delMask.show();
            var a = new Array();
            for (var i = 0; i < ids.length; i++) {
                a[i] = ids[i].get('id');
            }
            var b = a.toString();
            Ext.Ajax.request({
                url: 'eliminarEventGen',
                waitMsg: perfil.etiquetas.lbMsgDelEvent,
                params: {
                    ids: b,
                    direccion: this.getArbolComp().getSelectionModel().getLastSelected().get('dir'),
                    nombre: this.getArbolComp().getSelectionModel().getLastSelected().get('text')

                },
                callback: function(options, success, response) {
                    responseData = Ext.decode(response.responseText);
                    me.getGridpaneleventgen().getStore().load({
                        url: 'cargarEventosGen',
                        params: {nombre: me.getArbolComp().getSelectionModel().getLastSelected().get('text')}});
                    me.getGridpanelcomp().getStore().load({
                        url: 'selArbol',
                        params: {nombre: me.getArbolComp().getSelectionModel().getLastSelected().get('text'),
                            direccion: me.getArbolComp().getSelectionModel().getLastSelected().get('dir')}});

                    if (responseData.codMsg == 1)
                    {

                        delMask.disable();
                        delMask.hide();

                    } else {
                        if (responseData.codMsg == 3)
                            mostrarMensaje(responseData.codMsg, responseData.mensaje);
                    }

                }

            });
        } else {
        }

    }, BorrarEventObs: function(sources) {
        var me = this;
        mostrarMensaje(2, perfil.etiquetas.lbMsgConfDObs, function(btn) {
            me.EliminarEventObs(sources, btn)
        });



    },
    EliminarEventObs: function(sources, btn) {
        var me = this;
        var delMask = new Ext.LoadMask(Ext.getBody(), {
            msg: perfil.etiquetas.lbMsgDelObs
        });
        if (btn == 'ok') {
            delMask.show();
            var a = new Array();
            for (var i = 0; i < sources.length; i++) {
                a[i] = sources[i].get('source');
            }
            var b = a.toString();
            Ext.Ajax.request({
                url: 'eliminarEventobs',
                waitMsg: perfil.etiquetas.lbMsgDelObs,
                params: {
                    sources: b,
                    direccion: this.getArbolComp().getSelectionModel().getLastSelected().get('dir'),
                    nombre: this.getArbolComp().getSelectionModel().getLastSelected().get('text')

                },
                callback: function(options, success, response) {
                    responseData = Ext.decode(response.responseText);
                    me.getGridpaneleventobs().getStore().load({
                        url: 'cargarEventosObs',
                        params: {nombre: me.getArbolComp().getSelectionModel().getLastSelected().get('text')}});
                    me.getGridpanelcomp().getStore().load({
                        url: 'selArbol',
                        params: {nombre: me.getArbolComp().getSelectionModel().getLastSelected().get('text'),
                            direccion: me.getArbolComp().getSelectionModel().getLastSelected().get('dir')}});

                    if (responseData.codMsg == 1)
                    {

                        delMask.disable();
                        delMask.hide();

                    } else {
                        if (responseData.codMsg == 3)
                            mostrarMensaje(responseData.codMsg, responseData.mensaje);
                        delMask.disable();
                        delMask.hide();
                    }

                }
            });
        } else {
        }

    },
    BotonesWinMEvG: function(button) {
        switch (button.itemId) {
            case 'btncancModEventG':
                {
                    this.getWinmodeventg().close();
                    this.getBtnModEventG().setDisabled(true);
                    this.getBtnDelEventG().setDisabled(true);
                    break;
                }
            case 'btnacepModEventG':
                {
                    if (this.getFormaddeventg().getForm().findField('nombreventg').getValue() != '') {
                        if (this.getFormaddeventg().getForm().findField('nombreventg').isValid() === true) {
                            if (this.getFormaddeventg().getForm().findField('nombreventg').getValue() == this.getGridpaneleventgen().getSelectionModel().getLastSelected().get('id') && this.getFormaddeventg().getForm().findField('nombreclase').getValue() == this.getGridpaneleventgen().getSelectionModel().getLastSelected().get('class')) {
                                mostrarMensaje(3, 'Por favor verifique, no ha realizado ninguna modificación.');
                            } else {
                                modificar = true;
                                this.ModificarEventG();

                                this.getBtnModEventG().setDisabled(true);
                                this.getBtnDelEventG().setDisabled(true);
                            }
                        } else {
                            mostrarMensaje(3, perfil.etiquetas.lbMsgErrorEnCamops);
                        }


                    } else {
                        mostrarMensaje(3, perfil.etiquetas.lbMsgEmptyFields);
                    }

                    break;
                }
        }
    },
    BotonesWinInsEO: function(button) {
        switch (button.itemId) {
            case 'btncancAddEventO':
                {

                    this.getWininsevento().close();
                    this.getBtnDelEventO().setDisabled(true);
                    this.getBtnModEventO().setDisabled(true);

                    break;
                }
            case 'btnapliAddEventO':
                {
                    if (this.getFormaddevento().getForm().findField('nombrevento').getValue() != '' && this.getFormaddevento().getForm().findField('impl').getValue() != '') {
                        if (this.getFormaddevento().getForm().findField('nombrevento').isValid() === true) {
                            var find = this.getGridpaneleventobs().getStore().find('source', this.getFormaddevento().getForm().findField('nombrevento').getValue());
                            if (find > 0) {
                                mostrarMensaje(3, perfil.etiquetas.lbMsgObsExist);
                            } else {
                                modificar = false;
                                aplicar = true;

                                this.InsertarEventoObs();

                            }

                        } else {
                            mostrarMensaje(3, perfil.etiquetas.lbMsgErrorEnCamops);
                        }

                    } else {
                        mostrarMensaje(3, perfil.etiquetas.lbMsgEmptyFields);
                    }
                    break;
                }
            case 'btnacepAddEventO':
                {
                   
                        if (this.getFormaddevento().getForm().findField('nombrevento').getValue() != '' && this.getFormaddevento().getForm().findField('impl').getValue() != '') {
                            if (this.getFormaddevento().getForm().findField('nombrevento').isValid() === true) {
                                var find = this.getGridpaneleventobs().getStore().find('source', this.getFormaddevento().getForm().findField('nombrevento').getValue());
                                
                                    
                               
                                    if (find  > 0) {
                                        mostrarMensaje(3, perfil.etiquetas.lbMsgObsExist);
                                    } else {
                                        modificar = false;
                                        aplicar = false;
                                        this.InsertarEventoObs();


                                        this.getBtnDelEventO().setDisabled(true);
                                        this.getBtnModEventO().setDisabled(true);


                                    }
                                

                            } else {
                                mostrarMensaje(3, perfil.etiquetas.lbMsgErrorEnCamops);
                            }


                        } else {
                            mostrarMensaje(3, perfil.etiquetas.lbMsgEmptyFields);
                        }


                    break;
                }

        }
    }, ModificarEventoObs: function() {
        var me = this;
        var nombrevento = this.getFormaddevento().getForm().findField('nombrevento').getValue();
        var impl = this.getFormaddevento().getForm().findField('impl').getValue();
        this.getFormaddevento().getForm().submit({
            url: 'modificarEventObs',
            waitMsg: perfil.etiquetas.lbMsgModObs,
            params: {
                nombrevento: nombrevento,
                impl: impl,
                direccion: this.getArbolComp().getSelectionModel().getLastSelected().get('dir'),
                nombre: this.getArbolComp().getSelectionModel().getLastSelected().get('text'),
                nomold: this.getGridpaneleventobs().getSelectionModel().getLastSelected().get('source')
            },
            failure: function(form, action) {
                if (action.result.codMsg != 3)
                {
                    me.getGridpaneleventobs().getStore().load({
                        url: 'cargarEventosObs',
                        params: {nombre: me.getArbolComp().getSelectionModel().getLastSelected().get('text')}});
                    me.getGridpanelcomp().getStore().load({
                        url: 'selArbol',
                        params: {nombre: me.getArbolComp().getSelectionModel().getLastSelected().get('text'),
                            direccion: me.getArbolComp().getSelectionModel().getLastSelected().get('die')}});
                    me.getWinmodevento().close();





                } else {
                    if (action.result.codMsg == 3)
                        mostrarMensaje(action.result.codMsg, action.result.mensaje);
                }
            }
        });

    },
    InsertarEventoObs: function() {
        var me = this;
        var nombrevento = this.getFormaddevento().getForm().findField('nombrevento').getValue();
        var impl = this.getFormaddevento().getForm().findField('impl').getValue();


        this.getFormaddevento().getForm().submit({
            url: 'insertarEventObs',
            waitMsg: perfil.etiquetas.lbMsgAddObs,
            params: {
                nombrevento: nombrevento,
                impl: impl,
                direccion: this.getArbolComp().getSelectionModel().getLastSelected().get('dir'),
                nombre: this.getArbolComp().getSelectionModel().getLastSelected().get('text')
            },
            failure: function(form, action) {
                if (action.result.codMsg != 3)
                {
                    me.getGridpaneleventobs().getStore().load({
                        url: 'cargarEventosObs',
                        params: {nombre: me.getArbolComp().getSelectionModel().getLastSelected().get('text')}});
                    me.getGridpanelcomp().getStore().load({
                        url: 'selArbol',
                        params: {nombre: me.getArbolComp().getSelectionModel().getLastSelected().get('text'),
                            direccion: me.getArbolComp().getSelectionModel().getLastSelected().get('die')}});
                    me.getFormaddevento().getForm().findField('nombrevento').reset();
                    me.getFormaddevento().getForm().findField('impl').reset();

                    if (aplicar) {
                    } else {
                        me.getWininsevento().close();
                    }



                } else {
                    if (action.result.codMsg == 3)
                        mostrarMensaje(action.result.codMsg, action.result.mensaje);
                }
            }
        });








    },
    BotonesWinModEO: function(button) {
        switch (button.itemId) {
            case 'btncancModEventO':
                {
                    this.getWinmodevento().close();
                    this.getBtnDelEventO().setDisabled(true);
                    this.getBtnModEventO().setDisabled(true);

                    break;
                }
            case 'btnacepModEventO':
                {
                    if (this.getFormaddevento().getForm().findField('nombrevento').getValue() != '' && this.getFormaddevento().getForm().findField('impl')) {
                        if (this.getFormaddevento().getForm().findField('nombrevento').isValid() === true) {
                            if (this.getFormaddevento().getForm().findField('nombrevento').getValue() == this.getGridpaneleventobs().getSelectionModel().getLastSelected().get('source') && this.getFormaddevento().getForm().findField('impl').getValue() == this.getGridpaneleventobs().getSelectionModel().getLastSelected().get('impl')) {
                                mostrarMensaje(3, perfil.etiquetas.lbMsgNoMod);
                            } else {
                                modificar = true;
                                this.ModificarEventoObs();

                                this.getBtnDelEventO().setDisabled(true);
                                this.getBtnModEventO().setDisabled(true);
                            }

                        } else {
                            mostrarMensaje(3, perfil.etiquetas.lbMsgErrorEnCamops);
                        }

                    } else {
                        mostrarMensaje(3, perfil.etiquetas.lbMsgEmptyFields);
                    }

                    break;
                }
        }
    },
    BotonesGridOperM: function(button) {
        switch (button.itemId) {
            case 'btnAddOperm':
                {

                    this.getFormmodoper().setDisabled(false);
                    this.getGridpaneloperm().setDisabled(true);
                    modificar = false;
                    break;
                }
            case 'btnModOperm':
                {
                    this.getFormmodoper().setDisabled(false);
                    var nombreoper = this.getGridpaneloperm().getSelectionModel().getLastSelected().get('nombre');
                    var retorno = this.getGridpaneloperm().getSelectionModel().getLastSelected().get('retorno');
                    var descripcion = this.getGridpaneloperm().getSelectionModel().getLastSelected().get('descripoper');
                    var parametros = this.getGridpaneloperm().getSelectionModel().getLastSelected().get('parametros');
                    var cantparm = this.getGridpaneloperm().getSelectionModel().getLastSelected().get('cantparm');


                    var arreglo = Array();
                    for (var i = 0; i < cantparm; i++) {

                        var nuevo = parametros[i].split(',');
                        var nombre = nuevo[0];
                        var tipo = nuevo[1];

                        var temp = nuevo[2];
                        var pos = temp.lastIndexOf('|');
                        var descrip = temp.slice(0, pos);
                        arreglo[i] = {nombre: nombre, tipo: tipo, descripcion: descrip};
                    }

                    this.getFormmodoper().getForm().findField('nombreoperm').setValue(nombreoper);
                    this.getFormmodoper().getForm().findField('retornom').setValue(retorno);
                    this.getFormmodoper().getForm().findField('descripoper').setValue(descripcion);
                    this.getGridpanelparmm().getStore().loadData(arreglo);
                    this.getGridpaneloperm().setDisabled(true);
                    modificar = true;
                    break;
                }
            case 'btnDellOperm':
                {
                    this.getGridpaneloperm().getStore().remove(this.getGridpaneloperm().getSelectionModel().getSelection());
                    break;
                }
        }
    },
    Chandged: function() {
        alert('die');
        changed = true;
    },
    CargarArbolDep: function() {
        this.getArbolDep().getStore().load();
    },
    CargarArbolObserver: function() {
        this.getPanelarboleventobs().getStore().load();
    },
    CambiarEstadoBot: function() {
        this.getBtnDelEventO().setDisabled(false);
        this.getBtnModEventO().setDisabled(false);
    }



});
