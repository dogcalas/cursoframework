Ext.define('estudiosForm', {
    extend: 'Ext.Window',
    alias: 'widget.alumno_estudios_form',
    id: 'alumno_estudios_form',

    initComponent: function () {
        Ext.QuickTips.init();
        var win = this;
        var idalumno = Ext.getCmp('alumnos_form').idalumno;
        var estudio = Ext.getCmp('alumno_estudios').getSelectionModel().getSelection()[0];
        var store = Ext.getCmp('alumno_estudios').getStore();
        var nuevo = estudio == null;

//         Model para el store de las facultades
        Ext.define('FacultadesModel', {
            extend: 'Ext.data.Model',
            fields: ['idestructura', 'denominacion', 'abreviatura']
        });

//        Store para el combo de las facultades
        var stcmbFacultades = Ext.create('Ext.data.ArrayStore', {
            model: 'FacultadesModel',
            autoLoad: true,
            proxy: {
                type: 'rest',
                url: 'cargarFacultades',
                actionMethods: { //Esta Linea es necesaria para el metodo de llamada POST o GET
                    read: 'POST'
                },
                reader: {
                    root: 'datos',
                    totalProperty: 'cantidad'
                }
            }
        });

        stcmbFacultades.on('load', function () {
            if (stcmbFacultades.count() > 0) {
                Ext.getCmp('idestructuraE').select(stcmbFacultades.getAt(0).data.idestructura);
            }

            stcmbCarreras.load({params: {idfacultad: Ext.getCmp('idestructuraE').getValue()}});
        })

//        Model para el store de las carreras
        Ext.define('CarrerasModel', {
            extend: 'Ext.data.Model',
            fields: ['idcarrera', 'descripcion']
        });

//        Store para el combo de las carreras
        var stcmbCarreras = Ext.create('Ext.data.ArrayStore', {
            model: 'CarrerasModel',
            //autoLoad: true,
            proxy: {
                type: 'rest',
                url: 'cargarCarreras',
                actionMethods: { //Esta Linea es necesaria para el metodo de llamada POST o GET
                    read: 'POST'
                },
                reader: {
                    root: 'datos',
                    totalProperty: 'cantidad'
                }
            }
        });

        stcmbCarreras.on('load', function () {
            if (stcmbCarreras.count() > 0) {
                Ext.getCmp('idcarreraE').select(stcmbCarreras.getAt(0).data.idcarrera);
            }
            stcmbEnfasis.load({params: {idcarrera: Ext.getCmp('idcarreraE').getValue()}});
            stcmbPensum.load({params: {idcarrera: Ext.getCmp('idcarreraE').getValue()}});
        });

//        Model para el store de los enfasis
        Ext.define('EnfasisModel', {
            extend: 'Ext.data.Model',
            fields: ['idenfasis', 'descripcion']
        });

//        Store para el combo de los enfasis
        var stcmbEnfasis = Ext.create('Ext.data.ArrayStore', {
            model: 'EnfasisModel',
            //autoLoad: true,
            proxy: {
                type: 'rest',
                url: 'cargarEnfasis',
                actionMethods: { //Esta Linea es necesaria para el metodo de llamada POST o GET
                    read: 'POST'
                },
                reader: {
                    root: 'datos',
                    totalProperty: 'cantidad'
                }
            }
        });

        stcmbEnfasis.on('load', function () {
            if (stcmbEnfasis.count() > 0) {
                Ext.getCmp('idenfasisE').select(stcmbEnfasis.getAt(0).data.idenfasis);
            }
        });

//       Model para el store de los pensum
        Ext.define('PensumModel', {
            extend: 'Ext.data.Model',
            fields: ['idpensum', 'descripcion']
        });

//        Store para el combo de los pensum
        var stcmbPensum = Ext.create('Ext.data.ArrayStore', {
            model: 'PensumModel',
            //autoLoad: true,
            proxy: {
                type: 'rest',
                url: 'cargarPensums',
                actionMethods: { //Esta Linea es necesaria para el metodo de llamada POST o GET
                    read: 'POST'
                },
                reader: {
                    root: 'datos',
                    totalProperty: 'cantidad'
                }
            }
        });

        stcmbPensum.on('load', function () {
            if (stcmbPensum.count() > 0) {
                Ext.getCmp('idpensumE').select(stcmbPensum.getAt(0).data.idpensum);
            }
        });

//        Formulario para gestionar estudios
        var formEstudios = Ext.create('Ext.form.Panel', {
            id: 'idFormEstudios',
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
                    id: 'idestructuraE',
                    name: 'idestructuraE',
                    fieldLabel: perfil.etiquetas.lbtxtfldFacultad,
                    allowBlank: false,
                    emptyText: perfil.etiquetas.lbEmpCombo,
                    editable: false,
                    store: stcmbFacultades,
                    anchor: '100%',
                    labelWidth: 130,
                    queryMode: 'local',
                    displayField: 'denominacion',
                    valueField: 'idestructura',
                    listeners: {
                        select: function () {
                            //cargar store de carreras
                            stcmbCarreras.load({params: {idfacultad: Ext.getCmp('idestructuraE').getValue()}});
                            Ext.getCmp('idcarreraE').setValue('');
                            Ext.getCmp('idenfasisE').setValue('');
                        }
                    }
                },
                {
                    xtype: 'combo',
                    id: 'idcarreraE',
                    name: 'idcarreraE',
                    fieldLabel: perfil.etiquetas.lbtxtfldCarrera,
                    allowBlank: false,
                    emptyText: perfil.etiquetas.lbEmpCombo,
                    editable: false,
                    store: stcmbCarreras,
                    anchor: '100%',
                    labelWidth: 130,
                    queryMode: 'local',
                    displayField: 'descripcion',
                    valueField: 'idcarrera',
                    listeners: {
                        select: function () {
                            //cargar store de enfasis y pensum
                            stcmbEnfasis.load({params: {idcarrera: Ext.getCmp('idcarreraE').getValue()}});
                            stcmbPensum.load({params: {idcarrera: Ext.getCmp('idcarreraE').getValue()}});
                        }
                    }
                },
                {
                    xtype: 'combo',
                    id: 'idenfasisE',
                    name: 'idenfasisE',
                    fieldLabel: perfil.etiquetas.lbtxtfldEnfasis,
                    allowBlank: false,
                    emptyText: perfil.etiquetas.lbEmpCombo,
                    editable: false,
                    store: stcmbEnfasis,
                    anchor: '100%',
                    labelWidth: 130,
                    queryMode: 'local',
                    displayField: 'descripcion',
                    valueField: 'idenfasis'
                },
                {
                    xtype: 'combo',
                    id: 'idpensumE',
                    name: 'idpensumE',
                    fieldLabel: perfil.etiquetas.lbtxtfldPensum,
                    allowBlank: false,
                    editable: false,
                    store: stcmbPensum,
                    anchor: '100%',
                    labelWidth: 130,
                    queryMode: 'local',
                    displayField: 'descripcion',
                    valueField: 'idpensum'
                },
                {
                    xtype: 'checkbox',
                    id: 'estadoE',
                    name: 'estadoE',
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
            formEstudios.getForm().reset();
            stcmbCarreras.load({params: {idfacultad: estudio.raw.idestructura}});
            stcmbEnfasis.load({params: {idcarrera: estudio.raw.idcarrera}});
            stcmbPensum.load({params: {idcarrera: estudio.raw.idcarrera}});

//            cargo los datos del estudio seleccionado
            formEstudios.getForm().loadRecord(estudio);
        }

        Ext.apply(this, {
            title: nuevo ? perfil.etiquetas.lbTitVentanaTitIV : perfil.etiquetas.lbTitVentanaTitV,
            modal: true,
            width: 350,
            height: 310,
            constrain: true,
            resizable: false,
            border: false,
            layout: 'fit',
            items: [formEstudios],
            buttons: [
                {
                    text: 'Cancelar',
                    icon: perfil.dirImg + 'cancelar.png',
                    handler: function () {
                        win.close();
                    }
                },
                {
                    id: 'idbtnAplicar',
                    text: 'Aplicar',
                    icon: perfil.dirImg + 'aplicar.png',
                    disabled: false,
                    handler: function () {
                        if (!nuevo) {
                            Ext.getCmp('idbtnAplicar').disable();
                        } else {
                            AdicionarEstudio('apl');
                        }
                    }
                },
                {
                    text: 'Aceptar',
                    icon: perfil.dirImg + 'aceptar.png',
                    disabled: false,
                    handler: function () {
                        if (!nuevo) {
                            ModificarEstudio();
                        } else {
                            AdicionarEstudio();
                        }
                    }
                }
            ]
        });

        this.callParent();

        function AdicionarEstudio(apl) {
            if (formEstudios.getForm().isValid()) {
                formEstudios.getForm().submit({
                    url: 'insertarEstudio',
                    waitMsg: perfil.etiquetas.lbMsgFunAdicionarEstMsg,
                    params: {
                        idalumno: idalumno
                    },
                    failure: function (form, action) {
                        if (action.result.codMsg != 3) {
                            formEstudios.getForm().reset();
                            store.load({params: {idalumno: idalumno}});
                            if (!apl)
                                win.close();
                        }
                    }
                });
            } else
                mostrarMensaje(3, perfil.etiquetas.lbMsgErrorEnCamops);
        }

        function ModificarEstudio() {
            if (formEstudios.getForm().isValid()) {
                formEstudios.getForm().submit({
                    url: 'modificarEstudio',
                    waitMsg: perfil.etiquetas.lbMsgFunModificarEstMsg,
                    params: {
                        idestudio: estudio.raw.idestudios,
                        idalumno: estudio.raw.idalumno
                    },
                    failure: function (form, action) {
                        if (action.result.codMsg != 3) {
                            win.close();
                            store.load({params: {idalumno: estudio.raw.idalumno}});
                        }
                    }
                });
            } else
                mostrarMensaje(3, perfil.etiquetas.lbMsgErrorEnCamops);
        }
    }
});