Ext.define('progInterForm', {
    extend: 'Ext.Window',
    alias: 'widget.alumno_progint_form',
    id: 'alumno_progint_form',

    initComponent: function () {
        Ext.QuickTips.init();
        var win = this;
        var idalumno = Ext.getCmp('alumnos_form').idalumno;
        var programas = Ext.getCmp('prog_internac').getSelectionModel().getSelection()[0];
        var store = Ext.getCmp('prog_internac').getStore();
        var nuevo = programas == null;

//         Model para el store de los tipos de programas
        Ext.define('TipoProgModel', {
            extend: 'Ext.data.Model',
            fields: ['idtipoprograma', 'descripcion', 'estado']
        });

        var btnAplicar = Ext.create('Ext.Button', {
            id: 'idbtnAplicar',
            text: 'Aplicar',
            icon: perfil.dirImg + 'aplicar.png',
            disabled: false,
            handler: function () {
                AdicionarPrograma('apl');
            }
        });

//        Store para el combo los tipos de programas
        var stcmbTipoProg = Ext.create('Ext.data.ArrayStore', {
            model: 'TipoProgModel',
            autoLoad: true,
            proxy: {
                type: 'rest',
                url: '../gesttipoprograma/cargarTipoProgramasA',
                actionMethods: { //Esta Linea es necesaria para el metodo de llamada POST o GET
                    read: 'POST'
                },
                reader: {
                    root: 'datos',
                    totalProperty: 'cantidad'
                }
            }
        });

        Ext.define('Pais', {
            extend: 'Ext.data.Model',
            fields: ['idpais', 'nombrepais', 'codigopais', 'siglas']
        });

        var stcmbPais = Ext.create('Ext.data.Store', {
            model: 'Pais',
            autoLoad: true,
            proxy: {
                type: 'ajax',
                url: 'getPaises',
                reader: {
                    type: 'json',
                    root: 'datos'
                }
            }
        });

//        Formulario para gestionar los programas internacionales
        var formProgInter = Ext.create('Ext.form.Panel', {
            id: 'idFormProgInter',
            frame: true,
            //layout: 'fit',
            bodyStyle: 'padding:5px auto 0px',
            fieldDefaults: {
                labelAlign: 'top',
                msgTarget: 'side'
            },
            items: [
                {
                    xtype: 'combo',
                    id: 'idtipoprograma',
                    name: 'idtipoprograma',
                    fieldLabel: perfil.etiquetas.lbHdrGpPTipo,
                    allowBlank: false,
                    emptyText: perfil.etiquetas.lbEmpCombo,
                    editable: false,
                    store: stcmbTipoProg,
                    anchor: '100%',
                    queryMode: 'local',
                    displayField: 'descripcion',
                    valueField: 'idtipoprograma'
                },
                {
                    xtype: 'combobox',
                    fieldLabel: 'País',
                    id: 'idpais',
                    name: 'idpais',
                    allowBlank: false,
                    editable: false,
                    forceSelection: true,
                    typeAhead: true,
                    triggerAction: 'all',
                    anchor: '100%',
                    labelAlign: 'top',
                    selectOnFocus: true,
                    store: stcmbPais,
                    displayField: 'nombrepais',
                    valueField: 'idpais'
                },
                {
                    xtype: 'textfield',
                    id: 'iduniv',
                    name: 'univ_inst',
                    fieldLabel: perfil.etiquetas.lbHdrGpPUnivInst,
                    allowBlank: false,
                    anchor: '100%',
                    displayField: 'univ_inst',
                    valueField: 'universidad'
                },
                {
                    xtype: 'numberfield',
                    id: 'idduracion',
                    name: 'duracion',
                    fieldLabel: perfil.etiquetas.lbHdrGpPDuracion,
                    allowBlank: false,
                    anchor: '100%',
                    displayField: 'duracion',
                    valueField: 'duracion'
                },
                {
                    xtype: 'checkbox',
                    id: 'estadoP',
                    name: 'estadoP',
                    fieldLabel: perfil.etiquetas.lbtxtfldActivado,
                    uncheckedValue: false,
                    labelAlign: 'left',
                    anchor: '90%',
                    checked: true
                }
            ]
        });

//        Si voy a modificar
        if (!nuevo) {
            formProgInter.getForm().reset();
            stcmbTipoProg.load();
            btnAplicar.hide();
//            cargo los datos del estudio seleccionado
            formProgInter.getForm().loadRecord(programas);
        }

        Ext.apply(this, {
            title: nuevo ? perfil.etiquetas.lbTitVentanaTitVI : perfil.etiquetas.lbTitVentanaTitVII,
            modal: true,
            width: 350,
            height: 310,
            constrain: true,
            resizable: false,
            border: false,
            layout: 'fit',
            items: [formProgInter],
            buttons: [
                {
                    text: 'Cancelar',
                    icon: perfil.dirImg + 'cancelar.png',
                    handler: function () {
                        win.close();
                    }
                }, btnAplicar,
                {
                    text: 'Aceptar',
                    icon: perfil.dirImg + 'aceptar.png',
                    disabled: false,
                    handler: function () {
                        if (!nuevo) {
                            ModificarPrograma();
                        } else {
                            AdicionarPrograma();
                        }
                    }
                }
            ]
        });

        this.callParent();

        function AdicionarPrograma(apl) {
            if (formProgInter.getForm().isValid()) {
                formProgInter.getForm().submit({
                    url: 'insertarProgramas',
                    waitMsg: perfil.etiquetas.lbMsgFunAdicionarProgMsg,
                    params: {
                        idalumno: idalumno
                    },
                    failure: function (form, action) {
                        if (action.result.codMsg != 3) {
                            formProgInter.getForm().reset();
                            store.load({params: {idalumno: idalumno}});
                            if (!apl)
                                win.close();
                        }
                    }
                });
            } else
                mostrarMensaje(3, perfil.etiquetas.lbMsgErrorEnCamops);
        }

        function ModificarPrograma() {
            if (formProgInter.getForm().isValid()) {
                formProgInter.getForm().submit({
                    url: 'modificarProgramas',
                    waitMsg: perfil.etiquetas.lbMsgFunModificarProgMsg,
                    params: {
                        idproginternacional: programas.raw.idproginternacional,
                        idalumno: idalumno
                    },
                    failure: function (form, action) {
                        if (action.result.codMsg != 3) {
                            win.close();
                            store.load({params: {idalumno: programas.raw.idalumno}});
                        }
                    }
                });
            } else
                mostrarMensaje(3, perfil.etiquetas.lbMsgErrorEnCamops);
        }
    }
});