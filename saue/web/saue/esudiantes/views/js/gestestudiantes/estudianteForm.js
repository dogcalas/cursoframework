Ext.define('estudianteForm', {
    extend: 'Ext.Window',
    id: 'alumnos_form',
    idalumno: 0,
    initComponent: function () {
        Ext.QuickTips.init();
        var win = this,
            idalumno = this.idalumno,
            idalumnodatos = this.idalumnodatos,
            alumno = this.alumno,
            store = this.store,
            mod = this.mod,
            exp = false,
            nuevo = alumno == null,
            docreq = new docRequeridosGrid(),
        //menciones = new mencionesGrid(),
        //estudios = new estudiosGrid(),
        //programas = new progInterGrid(),
            pasaporte,
            cedula,
            nextTab;

//        BOTONES
        var btnGuardarForm = Ext.create('Ext.Button', {
            id: 'btnGuarForm',
            text: 'Siguiente',
            icon: perfil.dirImg + 'aplicar.png',
            tabIndex: 13,
            handler: function () {
                AdicionarEstudiante();
            }
        });

        var btnAplicarForm = Ext.create('Ext.Button', {
            id: 'btnAplicForm',
            text: 'Aplicar',
            icon: perfil.dirImg + 'aplicar.png',
            hidden: true,
            handler: function () {
                ModificarEstudiante('apl');
            }
        });

        var btnApceptarForm = Ext.create('Ext.Button', {
            id: 'btnApcepForm',
            text: 'Aceptar',
            icon: perfil.dirImg + 'aceptar.png',
            hidden: true,
            handler: function () {
                ModificarEstudiante();
            }
        });

//        Model para el store del combo de tipo de alumnos
        Ext.define('TipoAlumnoModel', {
            extend: 'Ext.data.Model',
            fields: ['idtipoalumno', 'descripcion', 'fecha', 'idusuario']
        });

//        Store para el combo de tipo de alumnos
        var stcmbTipoestudiante = Ext.create('Ext.data.ArrayStore', {
            model: 'TipoAlumnoModel',
            autoLoad: true,
            proxy: {
                type: 'rest',
                url: '../gesttipoestudiante/cargarTipoEstudiantesA',
                actionMethods: { //Esta Linea es necesaria para el metodo de llamada POST o GET
                    read: 'POST'
                },
                reader: {
                    root: 'datos',
                    totalProperty: 'cantidad'
                }
            }
        });

//        Model para el store del combo de periodos
        Ext.define('PeriodoModel', {
            extend: 'Ext.data.Model',
            fields: ['idperiododocente', 'codperiodo', 'descripcion', 'idtipo_periododocente']
        });

//        Store para el combo de periodos
        var stcmbPeriodos = Ext.create('Ext.data.ArrayStore', {
            model: 'PeriodoModel',
            proxy: {
                type: 'rest',
                url: '../gestnotas/cargarPeriodos',
                actionMethods: { //Esta Linea es necesaria para el metodo de llamada POST o GET
                    read: 'POST'
                },
                reader: {
                    root: 'datos',
                    totalProperty: 'cantidad'
                }
            }
        });

//        Model para el store del combo de sector de la ciudad
        Ext.define('SectorModel', {
            extend: 'Ext.data.Model',
            fields: ['idsectorciudad', 'descripcion']
        });

//        Store para el combo de sector de la ciudad
        var stcmbSector = Ext.create('Ext.data.ArrayStore', {
            model: 'SectorModel',
            autoLoad: true,
            proxy: {
                type: 'rest',
                url: 'cargarSectores',
                actionMethods: { //Esta Linea es necesaria para el metodo de llamada POST o GET
                    read: 'POST'
                },
                reader: {
                    root: 'datos',
                    totalProperty: 'cantidad'
                }
            }
        });

//        Model para el store del combo de colegio
        Ext.define('ColegioModel', {
            extend: 'Ext.data.Model',
            fields: ['idcolegio', 'descripcion']
        });

//        Store para el combo de colegio
        var stcmbColegios = Ext.create('Ext.data.ArrayStore', {
            model: 'ColegioModel',
            autoLoad: true,
            proxy: {
                type: 'rest',
                url: 'cargarColegios',
                actionMethods: { //Esta Linea es necesaria para el metodo de llamada POST o GET
                    read: 'POST'
                },
                reader: {
                    root: 'datos',
                    totalProperty: 'cantidad'
                }
            }
        });

//        Store para el combo de los años
        var stcmbAnno = Ext.create('Ext.data.Store', {
            fields: ["anno"],
            remoteSort: true,
            autoLoad: true,
            proxy: {
                type: 'ajax',
                url: 'cargarAnnos',
                reader: {
                    totalProperty: "cantidad",
                    root: "datos"
                },
                actionMethods: {
                    read: 'POST'
                }
            }
        });

//        Model para el store de las provincia
        Ext.define('ProvinciasModel', {
            extend: 'Ext.data.Model',
            fields: ['idprovincia', 'descripcion', 'fecha', 'idusuario']
        });

//        Store para el combo de las provincias
        var stcmbProvincia = Ext.create('Ext.data.ArrayStore', {
            model: 'ProvinciasModel',
            autoLoad: true,
            proxy: {
                type: 'rest',
                url: 'cargarProvincias',
                actionMethods: { //Esta Linea es necesaria para el metodo de llamada POST o GET
                    read: 'POST'
                },
                reader: {
                    root: 'datos',
                    totalProperty: 'cantidad'
                }
            }
        });

        stcmbProvincia.on('load', function () {
            if (stcmbProvincia.count() > 0) {
                if (!Ext.getCmp('idprovinciacoleg').getValue())
                    Ext.getCmp('idprovinciacoleg').select(stcmbProvincia.getAt(0).data.idprovincia);
                //Ext.getCmp('idprovinciauniv').select(stcmbProvincia.getAt(0).data.idprovincia);
                Ext.getCmp('idprovincia').select(stcmbProvincia.getAt(0).data.idprovincia);
            }
        });

//        Model para el store de las universidades
        Ext.define('UniversidadesModel', {
            extend: 'Ext.data.Model',
            fields: ['iduniversidad', 'descripcion']
        });

//        Store para el combo de las universidades
        var stcmbUniversidad = Ext.create('Ext.data.ArrayStore', {
            model: 'UniversidadesModel',
            autoLoad: true,
            proxy: {
                type: 'rest',
                url: 'cargarUniversidades',
                actionMethods: { //Esta Linea es necesaria para el metodo de llamada POST o GET
                    read: 'POST'
                },
                reader: {
                    root: 'datos',
                    totalProperty: 'cantidad'
                }
            }
        });

//        Model para el store de las facultades
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
            if (!Ext.getCmp('idestructura').getValue()) {
                if (stcmbFacultades.count() > 0) {
                    Ext.getCmp('idestructura').select(stcmbFacultades.getAt(0).data.idestructura);
                }

                stcmbCarreras.load({params: {idfacultad: Ext.getCmp('idestructura').getValue()}});
            }
        });

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
            if (!Ext.getCmp('idcarrera').getValue()) {
                if (stcmbCarreras.count() > 0) {
                    Ext.getCmp('idcarrera').select(stcmbCarreras.getAt(0).data.idcarrera);
                }
                stcmbEnfasis.load({params: {idcarrera: Ext.getCmp('idcarrera').getValue()}});
                stcmbPensum.load({params: {idcarrera: Ext.getCmp('idcarrera').getValue()}});
            }
        });

//        Model para el store de los enfasis
        Ext.define('EnfasisModel', {
            extend: 'Ext.data.Model',
            fields: ['idenfasis', 'descripcion']
        });

//        Store para el combo de los enfasis
        var stcmbEnfasis = Ext.create('Ext.data.ArrayStore', {
            model: 'EnfasisModel',
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
            if (!Ext.getCmp('idenfasis').getValue()) {
                if (stcmbEnfasis.count() > 0) {
                    Ext.getCmp('idenfasis').select(stcmbEnfasis.getAt(0).data.idenfasis);
                }
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
            if (!Ext.getCmp('idpensum').getValue()) {
                if (stcmbPensum.count() > 0) {
                    Ext.getCmp('idpensum').select(stcmbPensum.getAt(0).data.idpensum);
                }
            }
        });


//        Model para el store del estado civil
        Ext.define('EstadoCivilModel', {
            extend: 'Ext.data.Model',
            fields: ['idestadocivil', 'descripcion', 'fecha', 'idusuario']
        });

//        Store para el combo del estado civil
        var stcmbEstadocivil = Ext.create('Ext.data.ArrayStore', {
            model: 'EstadoCivilModel',
            proxy: {
                type: 'rest',
                url: 'cargarEstadoCivil',
                actionMethods: { //Esta Linea es necesaria para el metodo de llamada POST o GET
                    read: 'POST'
                },
                reader: {
                    root: 'datos',
                    totalProperty: 'cantidad'
                }
            }
        });

        //        Model para el store de etnias
        Ext.define('EtniasModel', {
            extend: 'Ext.data.Model',
            fields: ['idetnia', 'descripcion', 'fecha', 'idusuario']
        });

//        Store para el combo del estado civil
        var stcmbEtnias = Ext.create('Ext.data.ArrayStore', {
            model: 'EtniasModel',
            proxy: {
                type: 'rest',
                url: 'cargarEtnias',
                actionMethods: { //Esta Linea es necesaria para el metodo de llamada POST o GET
                    read: 'POST'
                },
                reader: {
                    root: 'datos',
                    totalProperty: 'cantidad'
                }
            }
        });


        //        Model para el store de tipos de discapacidades
        Ext.define('TipoDiscapacidadesModel', {
            extend: 'Ext.data.Model',
            fields: ['idtipodiscapacidad', 'descripcion', 'fecha', 'idusuario']
        });

//        Store para el combo de tipos de discapacidades
        var stcmbTipoDiscapacidades = Ext.create('Ext.data.ArrayStore', {
            model: 'TipoDiscapacidadesModel',
            proxy: {
                type: 'rest',
                url: 'cargarTipoDiscapacidades',
                actionMethods: { //Esta Linea es necesaria para el metodo de llamada POST o GET
                    read: 'POST'
                },
                reader: {
                    root: 'datos',
                    totalProperty: 'cantidad'
                }
            }
        });

        //        Model para el store de grados de discapacidades
        Ext.define('GradoDiscapacidadesModel', {
            extend: 'Ext.data.Model',
            fields: ['idgradodiscapacidad', 'descripcion', 'fecha', 'idusuario']
        });

//        Store para el combo de grados de discapacidades
        var stcmbGradoDiscapacidades = Ext.create('Ext.data.ArrayStore', {
            model: 'GradoDiscapacidadesModel',
            proxy: {
                type: 'rest',
                url: 'cargarGradoDiscapacidades',
                actionMethods: { //Esta Linea es necesaria para el metodo de llamada POST o GET
                    read: 'POST'
                },
                reader: {
                    root: 'datos',
                    totalProperty: 'cantidad'
                }
            }
        });

        //        Model para el store de discapacidades
        Ext.define('DiscapacidadesModel', {
            extend: 'Ext.data.Model',
            fields: ['iddiscapacidad', 'descripcion', 'fecha', 'idusuario']
        });

//        Store para el combo de discapacidades
        var stcmbDiscapacidades = Ext.create('Ext.data.ArrayStore', {
            model: 'DiscapacidadesModel',
            proxy: {
                type: 'rest',
                url: 'cargarDiscapacidades',
                actionMethods: { //Esta Linea es necesaria para el metodo de llamada POST o GET
                    read: 'POST'
                },
                reader: {
                    root: 'datos',
                    totalProperty: 'cantidad'
                }
            }
        });


        //        Model para el store de tipos de becas
        Ext.define('TipoBecaModel', {
            extend: 'Ext.data.Model',
            fields: ['idtipobeca', 'descripcion', 'fecha', 'idusuario']
        });

//        Store para el combo de tipos de becas
        var stcmbTipoBeca = Ext.create('Ext.data.ArrayStore', {
            model: 'TipoBecaModel',
            proxy: {
                type: 'rest',
                url: 'cargarTipoBeca',
                actionMethods: { //Esta Linea es necesaria para el metodo de llamada POST o GET
                    read: 'POST'
                },
                reader: {
                    root: 'datos',
                    totalProperty: 'cantidad'
                }
            }
        });

//        Formulario para gestionar estudiantes
        var formEstudiantes = Ext.create('Ext.form.Panel', {
            frame: true,
            layout: 'fit',
            bodyStyle: 'padding:5px auto 0px',
            fieldDefaults: {
                labelAlign: 'top',
                msgTarget: 'side'
            },
            items: [
                {
                    xtype: 'tabpanel',
                    plain: true,
                    id: 'tbpnlEstudiantes',
                    layout: 'fit',
                    activeTab: 0,
                    listeners: {
                        tabchange: function (tabs, newTab) {
                            if (newTab.id == 'tbDocReq') {
                                btnApceptarForm.show();
                                btnAplicarForm.hide();
                            } else {
                                btnApceptarForm.hide();
                                btnAplicarForm.show();
                            }
                            return formEstudiantes.getForm().isValid();
                        }
                    },
                    items: [
                        {
                            title: perfil.etiquetas.tbpnllbInfPrinc,
                            layout: 'column',
                            id: 'tbInfPrinc',
                            items: [
                                {
                                    //primera columa
                                    defaults: {
                                        width: '100%'
                                    },
                                    columnWidth: .54,
                                    border: false,
                                    bodyStyle: 'padding:5px 0 5px 5px',
                                    items: [
                                        {
                                            xtype: 'fieldset',
                                            id: 'fldstGrales',
                                            title: '<b>' + perfil.etiquetas.fldstlbGrales + '</b>',
                                            border: 1,
                                            style: {
                                                borderColor: 'black',
                                                borderStyle: 'solid'
                                            },
                                            defaults: {width: '100%'},
                                            items: [
                                                {
                                                    xtype: 'container',
                                                    anchor: '100%',
                                                    layout: 'column',
                                                    items: [
                                                        {
                                                            xtype: 'container',
                                                            columnWidth: .5,
                                                            layout: 'anchor',
                                                            items: [
                                                                {
                                                                    xtype: 'radiogroup',
                                                                    id: 'radio',
                                                                    columns: 2,
                                                                    vertical: true,
                                                                    items: [
                                                                        {
                                                                            boxLabel: 'Cédula',
                                                                            name: 'rb',
                                                                            inputValue: 'cedula',
                                                                            checked: true
                                                                        },
                                                                        {
                                                                            boxLabel: 'Pasaporte',
                                                                            name: 'rb',
                                                                            inputValue: 'pasaporte'
                                                                        }
                                                                    ]
                                                                },
                                                                {
                                                                    id: 'cedpas',
                                                                    xtype: 'textfield',
                                                                    labelAlign: 'top',
                                                                    name: 'cedpas',
                                                                    anchor: '95%',
                                                                    style: {
                                                                        marginTop: '11px'
                                                                    },
                                                                    allowBlank: false,
                                                                    validator: function (val) {
                                                                        if (Ext.getCmp('radio').getValue().rb == 'cedula') {
                                                                            if (val.length > 10)
                                                                                return 'No debe contener más de 10 n&uacute;meros';
                                                                            if (!/^\d+$/.test(val)) {
                                                                                return 'Debe contener solo n&uacute;meros';
                                                                            }

                                                                        } else {
                                                                            if (val.length > 10)
                                                                                return 'No debe contener más de 10 n&uacute;meros';
                                                                        }
                                                                        return true;
                                                                    }
                                                                },
                                                                {
                                                                    xtype: 'textfield',
                                                                    fieldLabel: perfil.etiquetas.lbtxtfldNombre,
                                                                    name: 'nombre',
                                                                    anchor: '95%',
                                                                    allowBlank: false,
                                                                    maskRe: /[A-Z a-z]/,
                                                                    tabIndex: 1
                                                                },
                                                                {
                                                                    xtype: 'container',
                                                                    anchor: '100%',
                                                                    layout: 'column',
                                                                    items: [
                                                                        {
                                                                            xtype: 'container',
                                                                            columnWidth: .4,
                                                                            layout: 'anchor',
                                                                            items: [
                                                                                {
                                                                                    xtype: 'combo',
                                                                                    name: 'sexo',
                                                                                    id: 'idsexo_combo',
                                                                                    fieldLabel: perfil.etiquetas.lbtxtfldSexo,
                                                                                    width: 80,
                                                                                    allowBlank: false,
                                                                                    emptyText: perfil.etiquetas.lbEmpCombo,
                                                                                    editable: false,
                                                                                    store: Ext.create('Ext.data.Store', {
                                                                                        fields: ['idsexo', 'dsexo'],
                                                                                        data: [
                                                                                            {
                                                                                                "idsexo": 1,
                                                                                                "dsexo": 'Masculino'
                                                                                            },
                                                                                            {
                                                                                                "idsexo": 2,
                                                                                                "dsexo": 'Femenino'
                                                                                            }
                                                                                        ]
                                                                                    }),
                                                                                    queryMode: 'local',
                                                                                    displayField: 'dsexo',
                                                                                    valueField: 'dsexo',
                                                                                    style: {
                                                                                        marginTop: '6px'
                                                                                    },
                                                                                    tabIndex: 3
                                                                                }
                                                                            ]
                                                                        },
                                                                        {
                                                                            xtype: 'container',
                                                                            columnWidth: .55,
                                                                            layout: 'anchor',
                                                                            items: [
                                                                                {
                                                                                    xtype: 'datefield',
                                                                                    name: "fec_nacimiento",
                                                                                    fieldLabel: perfil.etiquetas.lbtxtfldFechaNac,
                                                                                    maxValue: Ext.Date.add(new Date(), Ext.Date.YEAR, -16),
                                                                                    value: Ext.Date.add(new Date(), Ext.Date.YEAR, -16),
                                                                                    allowBlank: false,
                                                                                    forceSelection: true,
                                                                                    emptyText: 'Mayor de 16 años',
                                                                                    width: 124,
                                                                                    editable: true,
                                                                                    style: {
                                                                                        marginTop: '6px'
                                                                                    },
                                                                                    tabIndex: 4
                                                                                }
                                                                            ]
                                                                        }
                                                                    ]
                                                                },
                                                                {
                                                                    xtype: 'container',
                                                                    anchor: '100%',
                                                                    layout: 'column',
                                                                    items: [
                                                                        {
                                                                            xtype: 'container',
                                                                            columnWidth: .4,
                                                                            layout: 'anchor',
                                                                            items: [
                                                                                {
                                                                                    xtype: 'textfield',
                                                                                    id: 'anno',
                                                                                    name: 'anno',
                                                                                    fieldLabel: perfil.etiquetas.lbtxtfldAnio,
                                                                                    disabled: true,
                                                                                    anchor: '90%',
                                                                                    queryMode: 'local',
                                                                                    style: {
                                                                                        marginTop: '6px'
                                                                                    },
                                                                                    tabIndex: 6
                                                                                }
                                                                            ]
                                                                        }

                                                                    ]
                                                                }
                                                            ]
                                                        },
                                                        {
                                                            xtype: 'container',
                                                            columnWidth: .5,
                                                            layout: 'anchor',
                                                            items: [
                                                                {
                                                                    xtype: 'fieldset',
                                                                    anchor: '100%',
                                                                    layout: 'fit',
                                                                    height: 21,
                                                                    items: {
                                                                        xtype: 'label',
                                                                        name: 'codigo',
                                                                        id: 'codigo',
                                                                        text: 'CÓDIGO DEL ALUMNO',
                                                                        style: {
                                                                            marginTop: '2px'
                                                                        }
                                                                    },
                                                                    border: 1,
                                                                    style: {
                                                                        borderColor: 'red',
                                                                        borderStyle: 'solid',
                                                                        marginTop: '33px'
                                                                    }
                                                                },
                                                                {
                                                                    xtype: 'textfield',
                                                                    fieldLabel: perfil.etiquetas.lbtxtfldApellidos,
                                                                    name: 'apellidos',
                                                                    anchor: '100%',
                                                                    allowBlank: false,
                                                                    maskRe: /[A-Z a-z]/,
                                                                    tabIndex: 2
                                                                },
                                                                {
                                                                    xtype: 'combo',
                                                                    id: 'idtipoalumno',
                                                                    name: 'idtipoalumno',
                                                                    fieldLabel: perfil.etiquetas.lbtxtfldTipoAlumno,
                                                                    allowBlank: false,
                                                                    forceSelection: true,
                                                                    emptyText: perfil.etiquetas.lbEmpCombo,
                                                                    editable: true,
                                                                    store: stcmbTipoestudiante,
                                                                    anchor: '100%',
                                                                    labelWidth: 130,
                                                                    queryMode: 'local',
                                                                    displayField: 'descripcion',
                                                                    valueField: 'idtipoalumno',
                                                                    style: {
                                                                        marginTop: '11px'
                                                                    },
                                                                    tabIndex: 5,
                                                                    onTrigger1Click: function () {
                                                                        exp = true;
                                                                        stcmbTipoestudiante.load();
                                                                    }
                                                                },
                                                                {
                                                                    xtype: 'combo',
                                                                    id: 'idperiododocente',
                                                                    name: 'idperiododocente',
                                                                    fieldLabel: perfil.etiquetas.lbtxtfldPeriodo,
                                                                    allowBlank: false,
                                                                    emptyText: perfil.etiquetas.lbEmpCombo,
                                                                    editable: true,
                                                                    store: stcmbPeriodos,
                                                                    anchor: '100%',
                                                                    labelWidth: 130,
                                                                    forceSelection: true,
                                                                    queryMode: 'local',
                                                                    displayField: 'descripcion',
                                                                    valueField: 'idperiododocente',
                                                                    style: {
                                                                        marginTop: '11px'
                                                                    },
                                                                    tabIndex: 8,
                                                                    onTrigger1Click: function () {
                                                                        exp = true;
                                                                        this.setLoading(true);
                                                                        if (mod) {
                                                                            stcmbPeriodos.load({params: {anno: alumno.raw.anno}});
                                                                        } else {
                                                                            stcmbPeriodos.load({params: {anno: fecha.getFullYear()}});
                                                                        }
                                                                    }
                                                                }
                                                            ]
                                                        }
                                                    ]
                                                }
                                            ]
                                        },
                                        {
                                            xtype: 'fieldset',
                                            id: 'fldstCto',
                                            title: '<b>' + perfil.etiquetas.fldstlbCto + '</b>',
                                            border: 1,
                                            style: {
                                                borderColor: 'black',
                                                borderStyle: 'solid'
                                            },
                                            defaults: {
                                                width: '100%'
                                            },
                                            items: [
                                                {
                                                    xtype: 'textfield',
                                                    fieldLabel: perfil.etiquetas.lbtxtfldDomicilio,
                                                    name: 'domicilio',
                                                    anchor: '100%',
                                                    id: 'domicilio',
                                                    allowBlank: false,
                                                    tabIndex: 11
                                                },
                                                {
                                                    xtype: 'container',
                                                    anchor: '100%',
                                                    layout: 'column',
                                                    items: [
                                                        {
                                                            xtype: 'container',
                                                            columnWidth: .6,
                                                            layout: 'anchor',
                                                            items: [
                                                                {
                                                                    xtype: 'combo',
                                                                    id: 'idsectorciudad',
                                                                    name: 'idsectorciudad',
                                                                    fieldLabel: perfil.etiquetas.lbtxtfldSector,
                                                                    allowBlank: false,
                                                                    emptyText: perfil.etiquetas.lbEmpCombo,
                                                                    editable: true,
                                                                    store: stcmbSector,
                                                                    anchor: '95%',
                                                                    labelWidth: 130,
                                                                    queryMode: 'local',
                                                                    forceSelection: true,
                                                                    displayField: 'descripcion',
                                                                    valueField: 'idsectorciudad',
                                                                    style: {
                                                                        marginTop: '6px'
                                                                    },
                                                                    tabIndex: 12,
                                                                    onTrigger1Click: function () {
                                                                        exp = true;
                                                                        this.setLoading(true);
                                                                        stcmbSector.load();
                                                                    }
                                                                }
                                                            ]
                                                        },
                                                        {
                                                            xtype: 'container',
                                                            columnWidth: .4,
                                                            layout: 'anchor',
                                                            items: [
                                                                {
                                                                    xtype: 'textfield',
                                                                    id: 'e_mail',
                                                                    name: 'e_mail',
                                                                    vtype: 'email',
                                                                    fieldLabel: perfil.etiquetas.lbtxtfldEmail1,
                                                                    anchor: '100%',
                                                                    allowBlank: false,
                                                                    style: {
                                                                        marginTop: '6px'
                                                                    },
                                                                    tabIndex: 13
                                                                }
                                                            ]
                                                        }
                                                    ]
                                                },
                                                {
                                                    xtype: 'container',
                                                    anchor: '100%',
                                                    layout: 'column',
                                                    items: [
                                                        {
                                                            xtype: 'container',
                                                            columnWidth: .5,
                                                            layout: 'anchor',
                                                            items: [
                                                                {
                                                                    xtype: 'textfield',
                                                                    fieldLabel: perfil.etiquetas.lbtxtfldTelefonoCasa,
                                                                    name: 'telefono',
                                                                    anchor: '90%',
                                                                    id: 'telefono',
                                                                    style: {
                                                                        marginTop: '6px'
                                                                    },
                                                                    tabIndex: 14
                                                                }
                                                            ]
                                                        },
                                                        {
                                                            xtype: 'container',
                                                            columnWidth: .5,
                                                            layout: 'anchor',
                                                            items: [
                                                                {
                                                                    xtype: 'textfield',
                                                                    fieldLabel: perfil.etiquetas.lbtxtfldTelefonoCelular,
                                                                    name: 'celular',
                                                                    anchor: '100%',
                                                                    id: 'celular',
                                                                    allowBlank: false,
                                                                    style: {
                                                                        marginTop: '6px'
                                                                    },
                                                                    tabIndex: 15
                                                                }
                                                            ]
                                                        }
                                                    ]
                                                }
                                            ]
                                        },
                                        {
                                            xtype: 'checkbox',
                                            id: 'estado',
                                            name: 'estado',
                                            fieldLabel: perfil.etiquetas.lbtxtfldActivado,
                                            uncheckedValue: false,
                                            labelAlign: 'left',
                                            anchor: '90%',
                                            style: {
                                                marginTop: '15px'
                                            },
                                            checked: true,
                                            tabIndex: 26
                                        }
                                    ]
                                },
                                {
                                    //segunda columa
                                    defaults: {
                                        width: '100%'
                                    },
                                    columnWidth: .45,
                                    border: false,
                                    bodyStyle: 'padding:5px 0 5px 10px',
                                    items: [
                                        {
                                            xtype: 'fieldset',
                                            id: 'fldstProc',
                                            title: '<b>' + perfil.etiquetas.fldstlbProcedencia + '</b>',
                                            border: 1,
                                            style: {
                                                borderColor: 'black',
                                                borderStyle: 'solid'
                                            },
                                            defaults: {width: '100%'},
                                            items: [
                                                {
                                                    xtype: 'combo',
                                                    id: 'idcolegio',
                                                    name: 'idcolegio',
                                                    fieldLabel: perfil.etiquetas.lbtxtfldColegio,
                                                    allowBlank: false,
                                                    emptyText: perfil.etiquetas.lbEmpCombo,
                                                    editable: true,
                                                    store: stcmbColegios,
                                                    forceSelection: true,
                                                    anchor: '100%',
                                                    labelWidth: 130,
                                                    queryMode: 'local',
                                                    displayField: 'descripcion',
                                                    valueField: 'idcolegio',
                                                    tabIndex: 16,
                                                    style: {
                                                        marginTop: '11px'
                                                    },
                                                    onTrigger1Click: function () {
                                                        exp = true;
                                                        this.setLoading(true);
                                                        stcmbColegios.load();
                                                    }
                                                },
                                                {
                                                    xtype: 'container',
                                                    anchor: '100%',
                                                    layout: 'column',
                                                    items: [
                                                        {
                                                            xtype: 'container',
                                                            columnWidth: .4,
                                                            layout: 'anchor',
                                                            items: [
                                                                {
                                                                    xtype: 'combo',
                                                                    id: 'annoc',
                                                                    name: 'annoc',
                                                                    fieldLabel: perfil.etiquetas.lbtxtfldAnio,
                                                                    allowBlank: false,
                                                                    emptyText: perfil.etiquetas.lbEmpCombo,
                                                                    editable: true,
                                                                    store: stcmbAnno,
                                                                    anchor: '90%',
                                                                    forceSelection: true,
                                                                    queryMode: 'local',
                                                                    displayField: 'anno',
                                                                    valueField: 'anno',
                                                                    style: {
                                                                        marginTop: '6px'
                                                                    },
                                                                    tabIndex: 17,
                                                                    onTrigger1Click: function () {
                                                                        exp = true;
                                                                        this.setLoading(true);
                                                                        stcmbAnno.load();
                                                                    }
                                                                }
                                                            ]
                                                        },
                                                        {
                                                            xtype: 'container',
                                                            columnWidth: .6,
                                                            layout: 'anchor',
                                                            items: [
                                                                {
                                                                    xtype: 'combo',
                                                                    id: 'idprovinciacoleg',
                                                                    name: 'idprovinciacoleg',
                                                                    fieldLabel: perfil.etiquetas.lbtxtfldProvinciaC,
                                                                    allowBlank: false,
                                                                    emptyText: perfil.etiquetas.lbEmpCombo,
                                                                    editable: true,
                                                                    store: stcmbProvincia,
                                                                    forceSelection: true,
                                                                    anchor: '100%',
                                                                    labelWidth: 130,
                                                                    queryMode: 'local',
                                                                    displayField: 'descripcion',
                                                                    valueField: 'idprovincia',
                                                                    style: {
                                                                        marginTop: '6px'
                                                                    },
                                                                    tabIndex: 18
                                                                    /*onTrigger1Click: function () {
                                                                     stcmbProvincia.load();
                                                                     }*/
                                                                }
                                                            ]
                                                        }
                                                    ]
                                                },
                                                {
                                                    xtype: 'combo',
                                                    id: 'iduniversidad',
                                                    name: 'iduniversidad',
                                                    fieldLabel: perfil.etiquetas.lbtxtfldUniversidad,
                                                    //allowBlank: false,
                                                    emptyText: perfil.etiquetas.lbEmpCombo,
                                                    forceSelection: true,
                                                    editable: true,
                                                    store: stcmbUniversidad,
                                                    anchor: '100%',
                                                    labelWidth: 130,
                                                    queryMode: 'local',
                                                    displayField: 'descripcion',
                                                    valueField: 'iduniversidad',
                                                    style: {
                                                        marginTop: '5px'
                                                    },
                                                    tabIndex: 19,
                                                    listeners: {
                                                        select: function (combo, records) {
                                                            if (records[0].raw.idpais == 107) {
                                                                Ext.getCmp('idprovinciauniv').enable();
                                                                Ext.getCmp('idprovinciauniv').select(stcmbProvincia.getAt(0).data.idprovincia);
                                                            } else {
                                                                Ext.getCmp('idprovinciauniv').setValue("");
                                                                Ext.getCmp('idprovinciauniv').disable();
                                                            }
                                                        }
                                                    },
                                                    onTrigger1Click: function () {
                                                        exp = true;
                                                        this.setLoading(true);
                                                        stcmbUniversidad.load();
                                                    }
                                                },
                                                {
                                                    xtype: 'container',
                                                    anchor: '100%',
                                                    layout: 'column',
                                                    items: [
                                                        {
                                                            xtype: 'container',
                                                            columnWidth: .6,
                                                            layout: 'anchor',
                                                            items: [
                                                                {
                                                                    xtype: 'combo',
                                                                    id: 'idprovinciauniv',
                                                                    name: 'idprovinciauniv',
                                                                    fieldLabel: perfil.etiquetas.lbtxtfldProvinciaU,
                                                                    //allowBlank: false,
                                                                    emptyText: perfil.etiquetas.lbEmpCombo,
                                                                    editable: true,
                                                                    disabled: true,
                                                                    store: stcmbProvincia,
                                                                    forceSelection: true,
                                                                    anchor: '100%',
                                                                    labelWidth: 130,
                                                                    queryMode: 'local',
                                                                    displayField: 'descripcion',
                                                                    valueField: 'idprovincia',
                                                                    style: {
                                                                        marginTop: '6px'
                                                                    },
                                                                    tabIndex: 20
                                                                }
                                                            ]
                                                        }
                                                    ]
                                                }
                                            ]
                                        },
                                        {
                                            xtype: 'fieldset',
                                            id: 'fldstUbLab',
                                            title: '<b>' + perfil.etiquetas.fldstlbUbicDocente + '</b>',
                                            border: 1,
                                            style: {
                                                borderColor: 'black',
                                                borderStyle: 'solid'
                                            },
                                            defaults: {width: '100%'},
                                            items: [
                                                {
                                                    xtype: 'combo',
                                                    id: 'idestructura',
                                                    name: 'idestructura',
                                                    fieldLabel: perfil.etiquetas.lbtxtfldFacultad,
                                                    allowBlank: false,
                                                    emptyText: perfil.etiquetas.lbEmpCombo,
                                                    forceSelection: true,
                                                    editable: true,
                                                    store: stcmbFacultades,
                                                    anchor: '100%',
                                                    labelWidth: 130,
                                                    queryMode: 'local',
                                                    displayField: 'denominacion',
                                                    valueField: 'idestructura',
                                                    tabIndex: 21,
                                                    listeners: {
                                                        select: function () {
                                                            //cargar store de carreras
                                                            stcmbCarreras.load({params: {idfacultad: Ext.getCmp('idestructura').getValue()}});
                                                            Ext.getCmp('idcarrera').setValue('');
                                                            Ext.getCmp('idenfasis').setValue('');
                                                        }
                                                    },
                                                    onTrigger1Click: function () {
                                                        exp = true;
                                                        this.setLoading(true);
                                                        stcmbFacultades.load();
                                                    }
                                                },
                                                {
                                                    xtype: 'combo',
                                                    id: 'idcarrera',
                                                    name: 'idcarrera',
                                                    fieldLabel: perfil.etiquetas.lbtxtfldCarrera,
                                                    allowBlank: false,
                                                    emptyText: perfil.etiquetas.lbEmpCombo,
                                                    forceSelection: true,
                                                    editable: true,
                                                    store: stcmbCarreras,
                                                    anchor: '100%',
                                                    labelWidth: 130,
                                                    queryMode: 'local',
                                                    displayField: 'descripcion',
                                                    valueField: 'idcarrera',
                                                    style: {
                                                        marginTop: '12px'
                                                    },
                                                    tabIndex: 22,
                                                    listeners: {
                                                        select: function () {
                                                            //cargar store de enfasis y pensum
                                                            stcmbEnfasis.load({params: {idcarrera: Ext.getCmp('idcarrera').getValue()}});
                                                            stcmbPensum.load({params: {idcarrera: Ext.getCmp('idcarrera').getValue()}});
                                                        }
                                                    },
                                                    onTrigger1Click: function () {
                                                        exp = true;
                                                        this.setLoading(true);
                                                        stcmbCarreras.load();
                                                    }
                                                },
                                                {
                                                    xtype: 'combo',
                                                    id: 'idenfasis',
                                                    name: 'idenfasis',
                                                    fieldLabel: perfil.etiquetas.lbtxtfldEnfasis,
                                                    allowBlank: false,
                                                    emptyText: perfil.etiquetas.lbEmpCombo,
                                                    forceSelection: true,
                                                    editable: true,
                                                    store: stcmbEnfasis,
                                                    anchor: '100%',
                                                    labelWidth: 130,
                                                    queryMode: 'local',
                                                    displayField: 'descripcion',
                                                    valueField: 'idenfasis',
                                                    style: {
                                                        marginTop: '12px'
                                                    },
                                                    tabIndex: 23,
                                                    onTrigger1Click: function () {
                                                        exp = true;
                                                        this.setLoading(true);
                                                        stcmbEnfasis.load();
                                                    }
                                                },
                                                {
                                                    xtype: 'combo',
                                                    id: 'idpensum',
                                                    name: 'idpensum',
                                                    fieldLabel: perfil.etiquetas.lbtxtfldPensum,
                                                    //allowBlank: false,
                                                    emptyText: '0',
                                                    editable: true,
                                                    hidden: true,
                                                    store: stcmbPensum,
                                                    forceSelection: true,
                                                    anchor: '100%',
                                                    labelWidth: 130,
                                                    queryMode: 'local',
                                                    displayField: 'descripcion',
                                                    valueField: 'idpensum',
                                                    tabIndex: 24,
                                                    onTrigger1Click: function () {
                                                        exp = true;
                                                        this.setLoading(true);
                                                        stcmbPensum.load();
                                                    }
                                                }
                                            ]
                                        }
                                    ]
                                }
                            ]
                        },
                        {
                            title: perfil.etiquetas.tbpnllbInfAdic,
                            bodyStyle: 'padding:5px',
                            id: 'tbInfAdic',
                            disabled: true,
                            items: [
                                {
                                    xtype: 'container',
                                    anchor: '100%',
                                    layout: 'column',
                                    items: [
                                        {
                                            defaults: {width: '100%'},
                                            columnWidth: .33,
                                            border: false,
                                            bodyStyle: 'padding:15px 0 10px 5px',
                                            items: [
                                                {
                                                    xtype: 'fieldset',
                                                    title: '<b>' + perfil.etiquetas.fldstlbGrales + '</b>',
                                                    border: 1,
                                                    style: {
                                                        borderColor: 'black',
                                                        borderStyle: 'solid'
                                                    },
                                                    defaults: {width: '100%'},
                                                    items: [
                                                        {
                                                            xtype: 'container',
                                                            anchor: '100%',
                                                            layout: 'column',
                                                            items: [
                                                                {
                                                                    xtype: 'container',
                                                                    columnWidth: .5,
                                                                    layout: 'anchor',
                                                                    items: [
                                                                        {
                                                                            xtype: 'textfield',
                                                                            vtype: 'email',
                                                                            id: 'e_mail2',
                                                                            name: 'e_mail2',

                                                                            fieldLabel: perfil.etiquetas.lbtxtfldEmail2,
                                                                            anchor: '95%'
                                                                        }
                                                                    ]
                                                                },
                                                                {
                                                                    xtype: 'container',
                                                                    columnWidth: .5,
                                                                    layout: 'anchor',
                                                                    items: [
                                                                        {
                                                                            xtype: 'textfield',
                                                                            fieldLabel: perfil.etiquetas.lbtxtfldReligion,
                                                                            name: 'religion',
                                                                            anchor: '100%',
                                                                            id: 'religion'
                                                                        }
                                                                    ]
                                                                }
                                                            ]
                                                        },
                                                        {
                                                            xtype: 'container',
                                                            anchor: '100%',
                                                            layout: 'column',
                                                            items: [
                                                                {
                                                                    xtype: 'container',
                                                                    columnWidth: .6,
                                                                    layout: 'anchor',
                                                                    items: [
                                                                        {
                                                                            xtype: 'combo',
                                                                            id: 'idestadocivil',
                                                                            name: 'idestadocivil',
                                                                            fieldLabel: perfil.etiquetas.lbtxtfldEstadoCivil,
                                                                            emptyText: perfil.etiquetas.lbEmpCombo,
                                                                            editable: false,
                                                                            store: stcmbEstadocivil,
                                                                            anchor: '95%',
                                                                            labelWidth: 130,
                                                                            queryMode: 'local',
                                                                            displayField: 'descripcion',
                                                                            valueField: 'idestadocivil',
                                                                            onTrigger1Click: function () {
                                                                                exp = true;
                                                                                this.setLoading(true);
                                                                                stcmbEstadocivil.load();
                                                                            }
                                                                        }
                                                                    ]
                                                                },
                                                                {
                                                                    xtype: 'container',
                                                                    columnWidth: .4,
                                                                    layout: 'anchor',
                                                                    items: [
                                                                        {
                                                                            xtype: 'numberfield',
                                                                            fieldLabel: perfil.etiquetas.lbtxtfldHijos,
                                                                            name: 'cant_hijos',
                                                                            labelWidth: 130,
                                                                            anchor: '100%',
                                                                            id: 'hijos'
                                                                        }
                                                                    ]
                                                                }
                                                            ]
                                                        },
                                                        {
                                                            xtype: 'combo',
                                                            name: 'idtipodiscapacidad',
                                                            id: 'idtipodiscapacidad',
                                                            fieldLabel: perfil.etiquetas.lbtxtfldTipoDisc,
                                                            //width: 80,
                                                            anchor: '100%',
                                                            allowBlank: true,
                                                            emptyText: perfil.etiquetas.lbEmpCombo,
                                                            editable: true,
                                                            store: stcmbTipoDiscapacidades,
                                                            queryMode: 'local',
                                                            displayField: 'descripcion',
                                                            valueField: 'idtipodiscapacidad',
                                                            listeners: {
                                                                'select': function () {
                                                                    Ext.getCmp('idgradodiscapacidad').setValue('');
                                                                }
                                                            },
                                                            onTrigger1Click: function () {
                                                                exp = true;
                                                                this.setLoading(true);
                                                                stcmbTipoDiscapacidades.load();
                                                            }
                                                        },
                                                        {
                                                            xtype: 'container',
                                                            anchor: '100%',
                                                            layout: 'column',
                                                            items: [
                                                                {
                                                                    xtype: 'container',
                                                                    columnWidth: .4,
                                                                    layout: 'anchor',
                                                                    items: [
                                                                        {
                                                                            xtype: 'combo',
                                                                            name: 'idgradodiscapacidad',
                                                                            id: 'idgradodiscapacidad',
                                                                            fieldLabel: perfil.etiquetas.lbtxtfldGdoDisc,
                                                                            //width: 80,
                                                                            anchor: '95%',
                                                                            allowBlank: true,
                                                                            emptyText: perfil.etiquetas.lbEmpCombo,
                                                                            editable: true,
                                                                            store: stcmbGradoDiscapacidades,
                                                                            queryMode: 'local',
                                                                            displayField: 'descripcion',
                                                                            valueField: 'idgradodiscapacidad',
                                                                            listeners: {
                                                                                'select': function () {//poner un listener en los combos
                                                                                    stcmbDiscapacidades.load(
                                                                                        {
                                                                                            params: {
                                                                                                idtipodiscapacidad: Ext.getCmp('idtipodiscapacidad').getValue(),
                                                                                                idgradodiscapacidad: Ext.getCmp('idgradodiscapacidad').getValue()
                                                                                            }
                                                                                        });
                                                                                }
                                                                            },
                                                                            onTrigger1Click: function () {
                                                                                exp = true;
                                                                                this.setLoading(true);
                                                                                stcmbGradoDiscapacidades.load();

                                                                            }
                                                                        }
                                                                    ]
                                                                },
                                                                {
                                                                    xtype: 'container',
                                                                    columnWidth: .6,
                                                                    layout: 'anchor',
                                                                    items: [
                                                                        {
                                                                            xtype: 'combo',
                                                                            id: 'iddiscapacidad',
                                                                            name: 'iddiscapacidad',
                                                                            fieldLabel: perfil.etiquetas.lbtxtfldDiscap,
                                                                            allowBlank: true,
                                                                            emptyText: perfil.etiquetas.lbEmpCombo,
                                                                            editable: true,
                                                                            store: stcmbDiscapacidades,
                                                                            multiSelect: true,
                                                                            anchor: '100%',
                                                                            labelWidth: 130,
                                                                            queryMode: 'local',
                                                                            displayField: 'descripcion',
                                                                            valueField: 'iddiscapacidad'
                                                                        }
                                                                    ]
                                                                }
                                                            ]
                                                        },
                                                        {
                                                            xtype: 'container',
                                                            anchor: '100%',
                                                            layout: 'column',
                                                            items: [
                                                                {
                                                                    xtype: 'container',
                                                                    columnWidth: .5,
                                                                    layout: 'anchor',
                                                                    items: [
                                                                        {
                                                                            xtype: 'combo',
                                                                            id: 'idetnia',
                                                                            name: 'idetnia',
                                                                            fieldLabel: perfil.etiquetas.lbtxtfldEtnia,
                                                                            //allowBlank: false,
                                                                            emptyText: perfil.etiquetas.lbEmpCombo,
                                                                            editable: true,
                                                                            store: stcmbEtnias,
                                                                            anchor: '95%',
                                                                            queryMode: 'local',
                                                                            displayField: 'descripcion',
                                                                            valueField: 'idetnia',
                                                                            onTrigger1Click: function () {
                                                                                exp = true;
                                                                                this.setLoading(true);
                                                                                stcmbEtnias.load();
                                                                            }
                                                                        }
                                                                    ]
                                                                },
                                                                {
                                                                    xtype: 'container',
                                                                    columnWidth: .5,
                                                                    layout: 'anchor',
                                                                    items: [
                                                                        {
                                                                            xtype: 'combo',
                                                                            id: 'idtipobeca',
                                                                            name: 'idtipobeca',
                                                                            store: stcmbTipoBeca,
                                                                            fieldLabel: perfil.etiquetas.lbtxtfldTipoBeca,
                                                                            anchor: '100%',
                                                                            queryMode: 'local',
                                                                            displayField: 'descripcion',
                                                                            valueField: 'idtipobeca',
                                                                            //allowBlank: false,
                                                                            emptyText: perfil.etiquetas.lbEmpCombo,
                                                                            editable: true,
                                                                            onTrigger1Click: function () {
                                                                                exp = true;
                                                                                this.setLoading(true);
                                                                                stcmbTipoBeca.load();
                                                                            }
                                                                        }
                                                                    ]
                                                                }
                                                            ]
                                                        }
                                                    ]
                                                }
                                            ]
                                        },
                                        {
                                            defaults: {width: '100%'},
                                            columnWidth: .33,
                                            border: false,
                                            bodyStyle: 'padding:15px 0 10px 5px',
                                            items: [
                                                {
                                                    xtype: 'fieldset',
                                                    title: '<b>' + perfil.etiquetas.fldstlbCto + '</b>',
                                                    border: 1,
                                                    style: {
                                                        borderColor: 'black',
                                                        borderStyle: 'solid'
                                                    },
                                                    defaults: {width: '90%'},
                                                    items: [
                                                        {
                                                            xtype: 'textfield',
                                                            fieldLabel: perfil.etiquetas.lbtxtfldLugarNac,
                                                            name: 'lugar_nacimiento',
                                                            anchor: '100%',
                                                            id: 'lugar_nacimiento'
                                                        },
                                                        {
                                                            xtype: 'textfield',
                                                            fieldLabel: perfil.etiquetas.lbtxtfldNacionalidad,
                                                            name: 'nacionalidad',
                                                            anchor: '100%',
                                                            id: 'nacionalidad'
                                                        },
                                                        {
                                                            xtype: 'textfield',
                                                            fieldLabel: perfil.etiquetas.lbtxtfldPais,
                                                            name: 'pais',
                                                            anchor: '100%',
                                                            id: 'pais'
                                                        },
                                                        {
                                                            xtype: 'combo',
                                                            id: 'idprovincia',
                                                            name: 'idprovincia',
                                                            fieldLabel: perfil.etiquetas.lbtxtfldProvincia,
                                                            emptyText: perfil.etiquetas.lbEmpCombo,
                                                            editable: true,
                                                            store: stcmbProvincia,
                                                            anchor: '100%',
                                                            labelWidth: 130,
                                                            queryMode: 'local',
                                                            displayField: 'descripcion',
                                                            valueField: 'idprovincia'
                                                        }
                                                    ]
                                                }
                                            ]
                                        },
                                        {
                                            defaults: {width: '100%'},
                                            columnWidth: .34,
                                            border: false,
                                            bodyStyle: 'padding:15px 0 10px 5px',
                                            items: [
                                                {
                                                    xtype: 'fieldset',
                                                    title: '<b>' + perfil.etiquetas.fldstlbUbicLaboral + '</b>',
                                                    border: 1,
                                                    style: {
                                                        borderColor: 'black',
                                                        borderStyle: 'solid'
                                                    },
                                                    defaults: {width: '90%'},
                                                    items: [
                                                        {
                                                            xtype: 'textfield',
                                                            fieldLabel: perfil.etiquetas.lbtxtfldEmpresa,
                                                            name: 'empresa_trab',
                                                            anchor: '100%',
                                                            id: 'empresa'
                                                        },
                                                        {
                                                            xtype: 'textfield',
                                                            fieldLabel: perfil.etiquetas.lbtxtfldDirEmpresa,
                                                            name: 'direccion_trab',
                                                            anchor: '100%',
                                                            id: 'direccion_trab'
                                                        },
                                                        {
                                                            xtype: 'textfield',
                                                            fieldLabel: perfil.etiquetas.lbtxtfldTelefEmpresa,
                                                            name: 'telefono_trab',
                                                            anchor: '100%',
                                                            id: 'telefono_trab'
                                                        },
                                                        {
                                                            xtype: 'textfield',
                                                            fieldLabel: perfil.etiquetas.lbtxtfldCargo,
                                                            name: 'cargo_empresa',
                                                            anchor: '100%',
                                                            id: 'cargo'
                                                        },
                                                        {
                                                            xtype: 'textfield',
                                                            fieldLabel: perfil.etiquetas.lbtxtfldCiudad,
                                                            name: 'ciudad_trabajo',
                                                            anchor: '100%',
                                                            id: 'ciudad'
                                                        }
                                                    ]
                                                }
                                            ]
                                        }
                                    ]
                                }
                            ]
                        },
                        {
                            title: perfil.etiquetas.tbpnllbDatosFam,
                            bodyStyle: 'padding:5px',
                            id: 'tbDatosFam',
                            disabled: true,
                            items: [
                                {
                                    xtype: 'container',
                                    anchor: '100%',
                                    layout: 'column',
                                    items: [
                                        {
                                            defaults: {width: '100%'},
                                            columnWidth: .46,
                                            border: false,
                                            bodyStyle: 'padding:15px 0 5px 70px',
                                            items: [
                                                {
                                                    xtype: 'fieldset',
                                                    title: '<b>' + perfil.etiquetas.fldstlbDatosPadre + '</b>',
                                                    border: 1,
                                                    style: {
                                                        borderColor: 'black',
                                                        borderStyle: 'solid'
                                                    },
                                                    defaults: {width: '100%'},
                                                    items: [
                                                        {
                                                            xtype: 'textfield',
                                                            fieldLabel: perfil.etiquetas.lbtxtfldNombre,
                                                            name: 'nombre_padre',
                                                            anchor: '100%',
                                                            maskRe: /[A-Z a-z]/
                                                        },
                                                        {
                                                            xtype: 'textfield',
                                                            fieldLabel: perfil.etiquetas.lbtxtfldApellidos,
                                                            name: 'apellidos_padre',
                                                            anchor: '100%',
                                                            maskRe: /[A-Z a-z]/
                                                        },
                                                        {
                                                            xtype: 'textarea',
                                                            fieldLabel: perfil.etiquetas.lbtxtfldDomicilio,
                                                            name: 'direccion_padre',
                                                            anchor: '100%'
                                                        },
                                                        {
                                                            xtype: 'textfield',
                                                            fieldLabel: perfil.etiquetas.lbtxtfldTelefono,
                                                            name: 'telefono_padre',
                                                            anchor: '100%'
                                                        },
                                                        {
                                                            xtype: 'textfield',
                                                            fieldLabel: perfil.etiquetas.lbtxtfldProfesión,
                                                            name: 'profesion_padre',
                                                            anchor: '100%'
                                                        },
                                                        {
                                                            xtype: 'textfield',
                                                            fieldLabel: perfil.etiquetas.lbtxtfldCargo,
                                                            name: 'cargo_padre',
                                                            anchor: '100%'
                                                        },
                                                        {
                                                            xtype: 'textfield',
                                                            fieldLabel: perfil.etiquetas.lbtxtfldEmpresa,
                                                            name: 'empresa_padre',
                                                            anchor: '100%'
                                                        }
                                                    ]
                                                }
                                            ]
                                        },
                                        {
                                            defaults: {width: '100%'},
                                            columnWidth: .46,
                                            border: false,
                                            bodyStyle: 'padding:15px 0 20px 70px',
                                            items: [
                                                {
                                                    xtype: 'fieldset',
                                                    title: '<b>' + perfil.etiquetas.fldstlbDatosMadre + '</b>',
                                                    border: 1,
                                                    style: {
                                                        borderColor: 'black',
                                                        borderStyle: 'solid'
                                                    },
                                                    defaults: {width: '90%'},
                                                    items: [
                                                        {
                                                            xtype: 'textfield',
                                                            fieldLabel: perfil.etiquetas.lbtxtfldNombre,
                                                            name: 'nombre_madre',
                                                            anchor: '100%',
                                                            maskRe: /[A-Z a-z]/
                                                        },
                                                        {
                                                            xtype: 'textfield',
                                                            fieldLabel: perfil.etiquetas.lbtxtfldApellidos,
                                                            name: 'apellidos_madre',
                                                            anchor: '100%',
                                                            maskRe: /[A-Z a-z]/
                                                        },
                                                        {
                                                            xtype: 'textarea',
                                                            fieldLabel: perfil.etiquetas.lbtxtfldDomicilio,
                                                            name: 'direccion_madre',
                                                            anchor: '100%'
                                                        },
                                                        {
                                                            xtype: 'textfield',
                                                            fieldLabel: perfil.etiquetas.lbtxtfldTelefono,
                                                            name: 'telefono_madre',
                                                            anchor: '100%'
                                                        },
                                                        {
                                                            xtype: 'textfield',
                                                            fieldLabel: perfil.etiquetas.lbtxtfldProfesión,
                                                            name: 'profesion_madre',
                                                            anchor: '100%'
                                                        },
                                                        {
                                                            xtype: 'textfield',
                                                            fieldLabel: perfil.etiquetas.lbtxtfldCargo,
                                                            name: 'cargo_madre',
                                                            anchor: '100%'
                                                        },
                                                        {
                                                            xtype: 'textfield',
                                                            fieldLabel: perfil.etiquetas.lbtxtfldEmpresa,
                                                            name: 'empresa_madre',
                                                            anchor: '100%'
                                                        }
                                                    ]
                                                }
                                            ]
                                        }
                                    ]
                                }
                            ]
                        },
                        {
                            title: perfil.etiquetas.tbpnllbDocReq,
                            layout: 'fit',
                            bodyStyle: 'padding:5px',
                            id: 'tbDocReq',
                            disabled: true,
                            items: [
                                {
                                    xtype: 'documento_requerido'
                                }
                            ]
                        }
                        /*{
                         title: perfil.etiquetas.tbpnllbMenciones,
                         layout: 'fit',
                         bodyStyle: 'padding:5px',
                         id: 'tbMenciones',
                         disabled: true,
                         items: [
                         {
                         xtype: 'alumno_menciones'
                         }
                         ]
                         }*/
                        /*{
                         title: perfil.etiquetas.tbpnllbEstudios,
                         bodyStyle: 'padding:5px',
                         layout: 'fit',
                         id: 'tbEstudios',
                         disabled: true,
                         items: [
                         {
                         xtype: 'alumno_estudios'
                         }
                         ]
                         }*/
                        /*{
                         title: perfil.etiquetas.tbpnllbProgInt,
                         bodyStyle: 'padding:5px',
                         layout: 'fit',
                         id: 'tbProgInt',
                         disabled: true,
                         items: [
                         {
                         xtype: 'prog_internac'
                         }
                         ]
                         }*/
                    ]
                }
            ]
        });

//        Si voy a modificar
        if (!nuevo) {
            formEstudiantes.getForm().reset();
            stcmbCarreras.load({params: {idfacultad: alumno.raw.idestructura}});
            stcmbEnfasis.load({params: {idcarrera: alumno.raw.idcarrera}});
            stcmbPensum.load({params: {idcarrera: alumno.raw.idcarrera}});
            stcmbPeriodos.load({params: {anno: alumno.raw.anno}});

            docreq.getStore().load({params: {idalumno: alumno.raw.alumno}});
            /*menciones.getStore().load({params: {idalumno: alumno.raw.alumno}});
             estudios.getStore().load({params: {idalumno: alumno.raw.alumno}});
             programas.getStore().load({params: {idalumno: alumno.raw.alumno}});*/

            Ext.getCmp('idpensum').show();
            Ext.getCmp('idpensum').setDisabled(true);

//            cargo los datos del alumno seleccionado
            formEstudiantes.getForm().loadRecord(alumno);

//            cargo la cedula o pasaporte en dependencia de lo que tenga el alumno
            if (alumno.raw.cedula) {
                Ext.getCmp('radio').getComponent(0).setValue(true);
                Ext.getCmp('cedpas').setValue(alumno.raw.cedula);
            } else {
                Ext.getCmp('radio').getComponent(1).setValue(true);
                Ext.getCmp('cedpas').setValue(alumno.raw.pasaporte);
            }
            stcmbPeriodos.load({params: {anno: alumno.raw.anno}});


//            habilito los tabs y fieldset para modificar toda la información del alumno
            Ext.getCmp('tbInfAdic').enable();
            Ext.getCmp('tbDatosFam').enable();
            Ext.getCmp('tbDocReq').enable();
            //Ext.getCmp('tbMenciones').enable();
            //Ext.getCmp('tbEstudios').enable();
            //Ext.getCmp('tbProgInt').enable();

            Ext.getCmp('fldstGrales').enable();
            Ext.getCmp('fldstProc').enable();
            Ext.getCmp('fldstCto').enable();
            Ext.getCmp('fldstUbLab').enable();

            btnGuardarForm.hide();
            btnAplicarForm.show();
            btnAplicarForm.setText('Siguente');

            Ext.getCmp('codigo').setText('CÓDIGO: ' + alumno.raw.codigo);
        } else {
            var fecha = new Date();
            stcmbPeriodos.load({params: {anno: fecha.getFullYear()}});
            Ext.getCmp('anno').setValue(fecha.getFullYear());
        }

        Ext.apply(this, {
            title: nuevo ? perfil.etiquetas.lbTitVentanaTitI : perfil.etiquetas.lbTitVentanaTitII + ' << ' + alumno.raw.nombre + ' ' + alumno.raw.apellidos + ' >>',
            modal: true,
            width: 900,
            height: 550,
            constrain: true,
            resizable: false,
            border: false,
            layout: 'fit',
            items: [formEstudiantes],
            buttons: [
                {
                    text: 'Cancelar',
                    icon: perfil.dirImg + 'cancelar.png',
                    handler: function () {

                        win.close();
                    }
                },
                btnGuardarForm,
                btnAplicarForm,
                btnApceptarForm
            ]
        });


        stcmbColegios.on('load', function () {
            Ext.getCmp('idcolegio').setLoading(false);
            if (exp)
                Ext.getCmp('idcolegio').expand();
        })

        stcmbTipoestudiante.on('load', function () {
            Ext.getCmp('idtipoalumno').setLoading(false);
            if (exp)
                Ext.getCmp('idtipoalumno').expand();
        })
        stcmbSector.on('load', function () {
            Ext.getCmp('idsectorciudad').setLoading(false);
            if (exp)
                Ext.getCmp('idsectorciudad').expand();
        })
        stcmbAnno.on('load', function () {
            Ext.getCmp('annoc').setLoading(false);
            if (exp)
                Ext.getCmp('annoc').expand();
        })
        /* stcmbProvincia.on('load', function () {
         Ext.getCmp('idprovinciacoleg').expand();
         })*/

        stcmbUniversidad.on('load', function () {
            Ext.getCmp('iduniversidad').setLoading(false);
            if (exp)
                Ext.getCmp('iduniversidad').expand();
        })

        /* Ext.getCmp('idprovinciauniv').on('expand', function () {
         stcmbProvincia.load()
         })*/

        stcmbFacultades.on('load', function () {
            Ext.getCmp('idestructura').setLoading(false);
        })
        stcmbEnfasis.on('load', function () {
            Ext.getCmp('idenfasis').setLoading(false);
            //Ext.getCmp('idenfasis').expand();
        })
        stcmbPeriodos.on('load', function () {
            Ext.getCmp('idperiododocente').setLoading(false);
            if (exp)
                Ext.getCmp('idperiododocente').expand();
        })

        stcmbEstadocivil.on('load', function () {
            Ext.getCmp('idestadocivil').setLoading(false)
            if (exp)
                Ext.getCmp('idestadocivil').expand();
        })
        stcmbTipoDiscapacidades.on('load', function () {
            Ext.getCmp('idtipodiscapacidad').setLoading(false);
            if (exp)
                Ext.getCmp('idtipodiscapacidad').expand();
        })
        stcmbGradoDiscapacidades.on('load', function () {
            Ext.getCmp('idgradodiscapacidad').setLoading(false);
            if (exp)
                Ext.getCmp('idgradodiscapacidad').expand();
        })
        stcmbEtnias.on('load', function () {
            Ext.getCmp('idetnia').setLoading(false);
            if (exp)
                Ext.getCmp('idetnia').expand();
        })
        stcmbTipoBeca.on('load', function () {
            Ext.getCmp('idtipobeca').setLoading(false);
            if (exp)
                Ext.getCmp('idtipobeca').expand();
        })

        this.callParent();

//        FUNCIONES

//        Función para adicionar un alumno
        function AdicionarEstudiante() {
            if (formEstudiantes.getForm().isValid()) {
                formEstudiantes.getForm().submit({
                    url: 'insertarEstudiante',
                    waitMsg: perfil.etiquetas.lbMsgFunAdicionarMsg,
                    failure: function (form, action) {
                        if (action.result.codMsg !== 3) {
                            idalumno = action.result.idalumno;
                            idalumnodatos = action.result.id_alumnodatos;
                            Ext.getCmp('alumnos_form').idalumno = idalumno;

                            Ext.getCmp('tbInfAdic').enable();
                            Ext.getCmp('tbDatosFam').enable();
                            Ext.getCmp('tbDocReq').enable();
                            //Ext.getCmp('tbMenciones').enable();
                            //Ext.getCmp('tbEstudios').enable();
                            //Ext.getCmp('tbProgInt').enable();

                            Ext.getCmp('btnApcepForm').hide();
                            Ext.getCmp('btnAplicForm').show();
                            Ext.getCmp('btnAplicForm').setText('Siguiente');
                            Ext.getCmp('btnGuarForm').hide();

                            Ext.getCmp('fldstGrales').disable();
                            Ext.getCmp('fldstProc').disable();
                            Ext.getCmp('fldstCto').disable();
                            Ext.getCmp('fldstUbLab').disable();

                            Ext.getCmp('tbpnlEstudiantes').setActiveTab('tbInfAdic');

                            store.load();
                            docreq.getStore().load({params: {idalumno: idalumno}});
                            /*menciones.getStore().load({params: {idalumno: idalumno}});
                             estudios.getStore().load({params: {idalumno: idalumno}});
                             programas.getStore().load({params: {idalumno: idalumno}});*/
                        }
                    }
                });
            } else
                mostrarMensaje(3, perfil.etiquetas.lbMsgErrorEnCamops);
        };

//        Función para moverse por los tabs.
        function siguienteTab(tab) {
            switch (tab) {
                case 'tbInfPrinc':
                {
                    nextTab = 'tbInfAdic';
                    break;
                }
                case 'tbInfAdic':
                {
                    nextTab = 'tbDatosFam';
                    break;
                }
                case 'tbDatosFam':
                {
                    nextTab = 'tbDocReq';
                    break;
                }
                case 'tbDocReq':
                {
                    //nextTab = 'tbMenciones';
                    break;
                }
                case 'tbMenciones':
                {
                    //nextTab = 'tbEstudios';
                    break;
                }
                case 'tbEstudios':
                {
                    //nextTab = 'tbProgInt';
                    break;
                }
            }
        }

//        Función para modificar un alumno
        function ModificarEstudiante(apl) {
            if (formEstudiantes.getForm().isValid()) {
                var items = docreq.getStore().data.items;
                //var itemsMenc = menciones.getStore().data.items;

                var iddocsAdd = new Array();
                var iddocsDel = new Array();
                //var idMencAdd = new Array();
                //var idMencDel = new Array();

                for (var i = 0; i < items.length; i++) {
                    if (items[i]['dirty']) {
                        if (items[i].data['checked']) {
                            iddocsAdd.push(items[i].data['iddocumentorequerido']);
                        } else {
                            iddocsDel.push(items[i].data['iddocumentorequerido']);
                        }
                    }
                }

                /*for (var i = 0; i < itemsMenc.length; i++) {
                 if (itemsMenc[i]['dirty']) {
                 if (itemsMenc[i].data['checked']) {
                 idMencAdd.push(itemsMenc[i].data['idmencion']);
                 } else {
                 idMencDel.push(itemsMenc[i].data['idmencion']);
                 }
                 }
                 }*/

                formEstudiantes.getForm().submit({
                    url: 'modificarEstudiante',
                    waitMsg: perfil.etiquetas.lbMsgFunModificarMsg,
                    params: {
                        idalumno: idalumno,
                        id_alumnodatos: idalumnodatos,
                        iddocsAdd: Ext.JSON.encode(iddocsAdd),
                        iddocsDel: Ext.JSON.encode(iddocsDel)
                        //idMencAdd: Ext.JSON.encode(idMencAdd),
                        //idMencDel: Ext.JSON.encode(idMencDel)
                    },
                    failure: function (form, action) {
                        if (action.result.codMsg === 1) {
                            store.load();
                            siguienteTab(Ext.getCmp('tbpnlEstudiantes').getActiveTab().id);
                            Ext.getCmp('tbpnlEstudiantes').setActiveTab(nextTab);
                            if (!apl) {
                                win.close();
                            }
                        }
                    }
                });
            } else
                mostrarMensaje(3, perfil.etiquetas.lbMsgErrorEnCamops);
        };
    }
});
