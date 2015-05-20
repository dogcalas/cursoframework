var perfil = window.parent.UCID.portal.perfil;
UCID.portal.cargarEtiquetas('gestconsejeria', function () {
    cargarInterfaz();
});

////------------ Inicializo el singlenton QuickTips ------------////
Ext.QuickTips.init();
var formobser, gridtoprigth, gridup, gridobserv, gridactual, gridpasantias, gridpracticas;
var gridmateriasnocarrera, gridmaterias, grid1ro, grid2ro, grid3ro;
var suma = 0.0;
var stcmbObserv;
var anno;
var winIns, winSearch, formaddobserv;
var idalumno, idcarrera = 0, idenfasis = 0, idpensum = 0, idfacultad = 0;
/*var promediocont = 0.0;
 var mataprobadas = 0;
 var mataprobxcarr = 0;*/
var cmbobserv, cmbfacultad, cmbcarrera, cmbcarrerap, cmbenfasis, cmbfacultadp, cmbenfasisp, periodoact;

function cargarInterfaz() {
    var tfnombre = Ext.create('Ext.form.TextField', {
        name: 'nombre',
        id: 'tfnombre',
        readOnly: true,
        width: 280,
        listeners: {
            focus: function (record, index, item, e) {
                Search();
            }
        }
    })
    var btnBuscar = Ext.create('Ext.Button', {
        id: 'btnBuscar',
        text: '<b>' + perfil.etiquetas.lbBtnBuscar + '</b>',
        icon: perfil.dirImg + 'buscar.png',
        iconCls: 'btn',
        handler: function () {
            Search();
        }
    });
    var btnObservaciones = Ext.create('Ext.Button', {
        id: 'btnObservaciones',
        text: '<b>' + perfil.etiquetas.lbBtnAdicionar + '</b>',
        icon: perfil.dirImg + 'adicionar.png',
        iconCls: 'btn',
        disabled: true,
        handler: function () {
            winForm();
        }
    });
    /*var btnImprimir = Ext.create('Ext.Button', {
     id: 'btnImprimir',
     text: '<b>' + perfil.etiquetas.lbBtnImprimir + '</b>',
     icon: perfil.dirImg + 'imprimir.png',
     iconCls: 'btn',
     //disabled: false,
     handler: function () {
     alert('Informacion imprimida satisfactoriamente');
     //ModificarEstudiante();
     }
     });*/
    var btnImprimir2 = Ext.create('Ext.Button', {
        id: 'btnImprimir2',
        text: '<b>' + perfil.etiquetas.lbBtnImprimir + '</b>',
        icon: perfil.dirImg + 'imprimir.png',
        disabled: true,
        handler: function () {
            //ModificarEstudiante();
        }
    });


    UCID.portal.cargarAcciones(window.parent.idFuncionalidad);

    var std = Ext.create('Ext.data.Store', {
        id: 'simpsonsStore2',
        fields: ['tipoaprobado', 'cant'],
        proxy: {
            type: 'ajax',
            url: 'cargarMaterias',
            reader: {
                type: 'json',
                root: 'datos'
            },
            actionMethods: {
                read: 'POST'
            }
        },
        listeners: {
            beforeload: function (store) {
                store.getProxy().extraParams = {
                    idalumno: idalumno
                }
            }
        }
    });

    stcmbObserv = new Ext.data.Store({
        fields: [
            {
                name: 'idtipoobservacion'
            },
            {
                name: 'descripcion'
            },
            {
                name: 'estado'
            }
        ],
        remoteSort: true,
        pageSize: 20,
        // autoLoad: true,
        proxy: {
            type: 'ajax',
            url: 'cargarTObserv',
            reader: {
                totalProperty: "cantidad",
                root: "datos"
            },
            actionMethods: {
                read: 'POST'
            }
        }
    });

    var stcmbPeriodos = new Ext.data.Store({
        fields: [
            {
                name: 'idperiododocente'
            },
            {
                name: 'descripcion'
            }
        ],
        remoteSort: true,
        pageSize: 20,
        // autoLoad: true,
        proxy: {
            type: 'ajax',
            url: 'cargarPeriodos',
            reader: {
                totalProperty: "cantidad",
                root: "datos"
            },
            actionMethods: {
                read: 'POST'
            }
        }
    });
    var stcmbFacultades = new Ext.data.Store({
        fields: [
            {
                name: 'idestructura'
            },
            {
                name: 'denominacion'
            }
        ],
        remoteSort: true,
        pageSize: 20,
        //autoLoad: true,
        proxy: {
            type: 'ajax',
            url: 'cargarFacultades',
            reader: {
                totalProperty: "cantidad",
                root: "datos"
            },
            actionMethods: {
                read: 'POST'
            }
        }
    });


    var stcmbCarreras = new Ext.data.Store({
        fields: [
            {
                name: 'idcarrera'
            },
            {
                name: 'descripcion'
            }
        ],
        remoteSort: true,
        pageSize: 20,
        proxy: {
            type: 'ajax',
            url: 'cargarCarreras',
            reader: {
                totalProperty: "cantidad",
                root: "datos"
            },
            actionMethods: {
                read: 'POST'
            }
        }
    });


    var stcmbEnfasis = new Ext.data.Store({
        fields: [
            {
                name: 'idenfasis'
            },
            {
                name: 'descripcion'
            }
        ],
        remoteSort: true,
        pageSize: 20,
        proxy: {
            type: 'ajax',
            url: 'cargarEnfasis',
            reader: {
                totalProperty: "cantidad",
                root: "datos"
            },
            actionMethods: {
                read: 'POST'
            }
        }
    });

    var stcmbFacultadesp = new Ext.data.Store({
        fields: [
            {
                name: 'idestructura'
            },
            {
                name: 'denominacion'
            }
        ],
        remoteSort: true,
        pageSize: 20,
        //autoLoad: true,
        proxy: {
            type: 'ajax',
            url: 'cargarFacultades',
            reader: {
                totalProperty: "cantidad",
                root: "datos"
            },
            actionMethods: {
                read: 'POST'
            }
        }
    });
    var stcmbCarrerasp = new Ext.data.Store({
        fields: [
            {
                name: 'idcarrera'
            },
            {
                name: 'descripcion'
            }
        ],
        remoteSort: true,
        pageSize: 20,
        proxy: {
            type: 'ajax',
            url: 'cargarCarreras',
            reader: {
                totalProperty: "cantidad",
                root: "datos"
            },
            actionMethods: {
                read: 'POST'
            }
        }
    });
    var stcmbEnfasisp = new Ext.data.Store({
        fields: [
            {
                name: 'idenfasis'
            },
            {
                name: 'descripcion'
            }
        ],
        remoteSort: true,
        pageSize: 20,
        proxy: {
            type: 'ajax',
            url: 'cargarEnfasis',
            reader: {
                totalProperty: "cantidad",
                root: "datos"
            },
            actionMethods: {
                read: 'POST'
            }
        }
    });
    var stgdActual = new Ext.data.Store({
        fields: [
            {
                name: 'codmateria'
            },
            {
                name: 'materia'
            },
            {
                name: 'falta'
            },
            {
                name: 'nota'
            },
            {
                name: 'par_curs'
            },
            {
                name: 'nombre'
            },
            {
                name: 'apellidos'
            },
            {
                name: 'profesor',
                convert: function (value, record) {
                    return record.get('nombre') + ' ' + record.get('apellidos');
                }
            },
            {
                name: 'aula'
            },
            {
                name: 'horario'
            }
        ],
        remoteSort: true,
        pageSize: 20,
        proxy: {
            type: 'ajax',
            url: 'cargarActualidad',
            reader: {
                totalProperty: "cantidad",
                root: "datos"
            },
            actionMethods: {
                read: 'POST'
            }
        },
        listeners: {
            beforeload: function (store) {
                store.getProxy().extraParams = {
                    idalumno: idalumno,
                    idperiodo: Ext.getCmp('idperiodoact').getValue()
                }
            }
        }
    });
    var stgdpasantias = new Ext.data.Store({
        fields: [
            {
                name: 'tpractica'
            },
            {
                name: 'hrs_req',
                type: 'int'
            },
            {
                name: 'hrs_real',
                type: 'int'
            },
            {
                name: 'faltantes',
                type: 'int',
                default: 0,
                convert: function (value, record) {
                    if (record.get('hrs_req') - record.get('hrs_real') >= 0)
                        return record.get('hrs_req') - record.get('hrs_real');
                    else
                        return 0;
                }
            }
        ],
        remoteSort: true,
        proxy: {
            type: 'ajax',
            url: 'practicaGral',
            reader: {
                root: "datos"
            },
            actionMethods: {
                read: 'POST'
            }
        },
        listeners: {
            beforeload: function (store) {
                store.getProxy().extraParams = {
                    idalumno: idalumno
                }
            }
        }
    });
    var stgdpasantias2 = new Ext.data.Store({
        fields: [
            {
                name: 'horas'
            },
            {
                name: 'estado'
            },
            {
                name: 'empresa'
            },
            {
                name: 'practica'
            },
            {
                name: 'tpractica'
            },
            {
                name: 'enfasis'
            },
            {
                name: 'carrera'
            },
            {
                name: 'facultad'
            }
        ],
        remoteSort: true,
        proxy: {
            type: 'ajax',
            url: 'cargarPasantias',
            reader: {
                totalProperty: "cantidad",
                root: "datos"
            },
            actionMethods: {
                read: 'POST'
            }
        },
        listeners: {
            beforeload: function (store) {
                store.getProxy().extraParams = {
                    idalumno: idalumno,
                    detalles: true,
                    idenfasis: Ext.getCmp('idenfasisp').getValue()
                }
            },
            load: function (store) {
                var suma = store.proxy.reader.jsonData.suma;
                if (suma == null)
                    suma = 0.0
                gridpasantias.setTitle(perfil.etiquetas.lbTitGridPracticas + suma);
            }
        }
    });
    var stgdtipomat = new Ext.data.Store({
        fields: [
            {
                name: 'campo', mapping: 'campo', type: 'string'
            },
            {
                name: 'cred_total', type: 'int'
            },
            {
                name: 'cred_obt', type: 'int'
            },
            {
                name: 'faltan',
                type: 'int',
                default: 0,
                convert: function (value, record) {
                    var f = record.get('cred_total') - record.get('cred_obt');
                    if (f > 0)
                        return f;
                    else
                        return 0;
                }
            },
            {
                name: 'cant_mat', type: 'int'
            }
        ],
        remoteSort: true,
        pageSize: 5,
        proxy: {
            type: 'ajax',
            url: 'cargarTMaterias',
            reader: {
                totalProperty: "cantidad",
                root: "datos"
            },
            actionMethods: {
                read: 'POST'
            }
        },
        listeners: {
            beforeload: function (store) {
                store.getProxy().extraParams = {
                    idalumno: idalumno
                }
            }
        }
    });

    var stgdmaterias = new Ext.data.Store({
        fields: [
            {
                name: 'codmateria'
            },
            {
                name: 'materia'
            },
            {
                name: 'nota'
            },
            {
                name: 'creditos'
            },
            {
                name: 'tmateria'
            }
        ],
        remoteSort: true,
        pageSize: 7,
        proxy: {
            type: 'ajax',
            url: 'cargarMateriasAprob',
            reader: {
                totalProperty: "cantidad",
                root: "datos"
            },
            actionMethods: {
                read: 'POST'
            }
        },
        listeners: {
            beforeload: function (store) {
                store.getProxy().extraParams = {
                    idalumno: idalumno,
                    idcarrera: idcarrera,
                    idenfasis: idenfasis
                }
            }/*,
             load: function (store) {
             Ext.getCmp('matAprobadas').setValue(store.totalCount);
             }*/
        }
    });
    var stgdnomaterias = new Ext.data.Store({
        fields: [
            {
                name: 'codmateria'
            },
            {
                name: 'materia'
            },
            {
                name: 'nota'
            },
            {
                name: 'creditos'
            },
            {
                name: 'tmateria'
            }
        ],
        remoteSort: true,
        pageSize: 7,
        proxy: {
            type: 'ajax',
            url: 'cargarMateriasAprobNO',
            reader: {
                totalProperty: "cantidad",
                root: "datos"
            },
            actionMethods: {
                read: 'POST'
            }
        },
        listeners: {
            beforeload: function (store) {
                store.getProxy().extraParams = {
                    idalumno: idalumno,
                    idenfasis: idenfasis,
                    idcarrera: idcarrera,
                    idpensum: idpensum
                }
            }/*,
             load: function (store) {
             Ext.getCmp('matAprobadasC').setValue(store.totalCount);
             }*/
        }
    });
    var stgdObserva = new Ext.data.Store({
        fields: [
            {
                name: 'idobservacion'
            },
            {
                name: 'detalles'
            },
            {
                name: 'idtipoobservacion'
            },
            {
                name: 'descripcion'
            },
            {
                name: 'fecha'
            },
            {
                name: 'usuario'
            },
            {
                name: 'estado'
            }
        ],
        remoteSort: true,
        pageSize: 20,
        proxy: {
            type: 'ajax',
            url: 'cargarObservaciones',
            reader: {
                totalProperty: "cantidad",
                root: "datos"
            },
            actionMethods: {
                read: 'POST'
            },
            params: {
                idalumno: idalumno
            }
        },
        listeners: {
            beforeload: function (store) {
                store.getProxy().extraParams = {
                    idalumno: idalumno
                }
            }
        }
    });

    var stCmbA = Ext.create('Ext.data.ArrayStore', {
        fields: [
            {
                name: "idanno"
            },
            {
                name: 'anno'
            }
        ],
        proxy: {
            type: 'ajax',
            url: 'cargarAnnosPeriodos',
            reader: {
                type: 'json',
                id: 'idanno',
                root: 'datos'
            },
            actionMethods: {
                read: 'POST'
            }
        },
        listeners: {
            load: function () {
                var ho = new Date(),
                    a = ho.getFullYear().toString();
                anno.select(a);
            }
        }
    });

    anno = Ext.create('Ext.form.ComboBox', {
        id: 'annot',
        fieldLabel: '<b>' + 'Año' + '<b>',
        labelWidth: 100,
        padding: '0 0 0 10',
        //labelAlign: 'top',
        labelWidth: 45,
        width: 120,
        queryMode: 'local',
        valueField: 'anno',
        displayField: 'anno',
        editable: false,
        store: stCmbA,
        listeners: {
            select: function () {
                stcmbPeriodos.load({
                    params: {
                        idanno: anno.getValue()
                    }
                })
            },
            change: function () {
                stcmbPeriodos.load({
                    params: {
                        idanno: anno.getValue()
                    }
                })
            }
        }
    });
    gridobserv = Ext.create('Ext.grid.Panel', {

        store: stgdObserva,
        disabled: true,
        columns: [
            {header: 'Tipo observaci&oacute;n', dataIndex: 'descripcion'},
            {header: 'Detalles', dataIndex: 'detalles', flex: 1},
            {header: 'Fecha', dataIndex: 'fecha'},
            {header: 'Usuario', dataIndex: 'usuario'}
        ],
        height: 460,
        id: 'qweas',
        width: '100%',
        tbar: [
            btnObservaciones,
            '->',
            {
                fieldLabel: '<b>' + perfil.etiquetas.lbInfVerXTipos + '</b>',
                labelWidth: 100,
                padding: '0 0 0 10',
                xtype: 'searchcombofield',
                id: 'idtipoobservacion',
                name: 'idtipoobservacion',
                allowBlank: true,
                emptyText: perfil.etiquetas.lbEmpCombo,
                editable: false,
                storeToFilter: stgdObserva,
                store: stcmbObserv,
                width: 400,
                queryMode: 'local',
                displayField: 'descripcion',
                valueField: 'idtipoobservacion',
                style: {
                    marginTop: '11px'
                },
                tabIndex: 8
            }
        ],
        bbar: new Ext.PagingToolbar({
            id: 'ptbaux991',
            store: stgdObserva,
            displayInfo: true

        }),
        viewConfig: {
            getRowClass: function (record, rowIndex, rowParams, store) {
                if (record.data.estado == false)
                    return 'FilaRoja';
            }
        }
    });
    gridactual = Ext.create('Ext.grid.Panel', {
        store: stgdActual,
        layout: 'fit',
        columns: [
            {header: 'Aula', dataIndex: 'aula', flex: 1},
            {header: 'Cod Materia', dataIndex: 'codmateria', flex: 1},
            {header: 'Materia', dataIndex: 'materia', flex: 1},
            {header: 'Paralelo', dataIndex: 'par_curs'},
            {header: 'Profesor', dataIndex: 'profesor', flex: 1},
            {header: 'Horario', dataIndex: 'horario', flex: 1},
            {header: 'Nota', dataIndex: 'nota'},
            {header: 'Falta', dataIndex: 'falta'}
        ],
        height: 430,
        id: 'qridactual',
        width: '100%',
        disabled: true,
        tbar: [
            anno,
            {
                xtype: 'searchcombofield',
                fieldLabel: '<b>' + perfil.etiquetas.lbInfPeriodos + '</b>',
                labelWidth: 100,
                padding: '0 0 0 10',
                id: 'idperiodoact',
                name: 'idperiodoact',
                allowBlank: true,
                emptyText: perfil.etiquetas.lbEmpCombo,
                editable: false,
                store: stcmbPeriodos,
                //disabled: true,
                width: 400,
                storeToFilter: stgdActual,
                queryMode: 'local',
                displayField: 'descripcion',
                valueField: 'idperiododocente',
                style: {
                    marginTop: '11px'
                },
                tabIndex: 8
            },
            '->', btnImprimir2
        ],
        bbar: new Ext.PagingToolbar({
            id: 'ptbaux992',
            store: stgdActual,
            displayInfo: true
        })

    });

    grid3ro = Ext.create('Ext.grid.Panel', {
        store: stgdpasantias,
        title: 'Horas de pasant&iacute;as obtenidas',
        disabled: true,
        height: 100,
        columns: [
            {header: 'Pasant&iacute;as', dataIndex: 'tpractica', flex: 1},
            {header: 'Requeridas', dataIndex: 'hrs_req'},
            {header: 'Realizadas', dataIndex: 'hrs_real'},
            {header: 'Faltantes', dataIndex: 'faltantes'}
        ],
        id: 'grid3ro',
        anchor: '100% 100%'
    });

    grid1ro = Ext.create('Ext.grid.Panel', {
        disabled: true,
        title: 'Cantidad de materias por tipo de aprobado',
        store: std,
        columns: [
            {header: 'Tipo aprobado', dataIndex: 'tipoaprobado', flex: 1},
            {header: 'Cantidad', dataIndex: 'cant', flex: 1}
        ],
        id: 'grid1ro',
        anchor: '100%, 100%',
        padding: '0 0 0 5'
    });

    grid2ro = Ext.create('Ext.grid.Panel', {
        store: stgdtipomat,
        title: 'Cr&eacute;ditos por campo',
        disabled: true,
        height: 300,
        columns: [
            {header: 'Campos', dataIndex: 'campo', flex: 1},
            {header: 'Cr&eacute;ditos', dataIndex: 'cred_total'},
            {header: 'Obtenidos', dataIndex: 'cred_obt'},
            {header: 'Faltantes', dataIndex: 'faltan'},
            {header: 'Cantidad Materias', dataIndex: 'cant_mat'}
        ],
        id: 'grid2ro',
        anchor: '100%, 100%'
    });

    gridpasantias = Ext.create('Ext.grid.Panel', {
        disabled: true,
        store: stgdpasantias,
        layout: 'fit',
        title: perfil.etiquetas.lbTitGridPracticas + suma,
        columns: [
            {header: 'Pasant&iacute;as', dataIndex: 'tpractica', flex: 1},
            {header: 'Requeridas', dataIndex: 'hrs_req'},
            {header: 'Realizadas', dataIndex: 'hrs_real'},
            {header: 'Faltantes', dataIndex: 'faltantes'}
        ],
        //height: 215,
        id: 'gridpasantias',
        //width: '100%',
        flex: 1,
        tbar: [
            {
                xtype: 'combo',
                fieldLabel: '<b>' + perfil.etiquetas.lbTtlFacultad + '</b>',
                labelWidth: 60,
                padding: '0 0 0 10',
                id: 'idfacultadp',
                name: 'idfacultadp',
                allowBlank: true,
                emptyText: perfil.etiquetas.lbEmpCombo,
                editable: false,
                store: stcmbFacultadesp,
                width: 250,
                queryMode: 'local',
                displayField: 'denominacion',
                valueField: 'idestructura',
                style: {
                    marginTop: '11px'
                },
                tabIndex: 8
            },
            {
                xtype: 'combo',
                fieldLabel: '<b>' + perfil.etiquetas.lbTtlCarrera + '</b>',
                labelWidth: 50,
                padding: '0 0 0 10',
                id: 'idcarrerap',
                name: 'idcarrerap',
                allowBlank: true,
                emptyText: perfil.etiquetas.lbEmpCombo,
                editable: false,
                store: stcmbCarrerasp,
                width: 250,
                //disabled: true,
                queryMode: 'local',
                displayField: 'descripcion',
                valueField: 'idcarrera',
                style: {
                    marginTop: '11px'
                },
                tabIndex: 8
            },
            {
                xtype: 'searchcombofield',
                fieldLabel: '<b>' + perfil.etiquetas.lbTtlEnfasis + '</b>',
                labelWidth: 60,
                padding: '0 0 0 10',
                id: 'idenfasisp',
                name: 'idenfasisp',
                allowBlank: true,
                emptyText: perfil.etiquetas.lbEmpCombo,
                editable: false,
                store: stcmbEnfasisp,
                storeToFilter: stgdpasantias2,
                //disabled: true,
                width: 250,
                queryMode: 'local',
                displayField: 'descripcion',
                valueField: 'idenfasis',
                style: {
                    marginTop: '11px'
                },
                tabIndex: 8
            }
        ],
        bbar: new Ext.PagingToolbar({
            id: 'ptbaux997',
            store: stgdpasantias,
            displayInfo: true
        })
    });

    gridpracticas = Ext.create('Ext.grid.Panel', {
        store: stgdpasantias2,
        layout: 'fit',
        title: 'Detalles de Pr&aacute;cticas:',
        disabled: true,
        columns: [
            {header: 'Tipo de Pr&aacute;cticas', dataIndex: 'tpractica', flex: 1},
            {header: 'Facultad', dataIndex: 'facultad', flex: 1},
            {header: 'Carrera', dataIndex: 'carrera', flex: 1},
            {header: 'Itinerario', dataIndex: 'enfasis', flex: 1},
            {header: 'Empresa', dataIndex: 'empresa', flex: 1},
            {header: 'Horas', dataIndex: 'horas'}
        ],
        bbar: new Ext.PagingToolbar({
            id: 'ptbaux998',
            store: stgdpasantias,
            displayInfo: true
        }),
        //height: 215,
        id: 'gridpracticas',
        //width: '100%'
        flex: 1
    });

    gridmaterias = Ext.create('Ext.grid.Panel', {
        store: stgdmaterias,
        layout: 'fit',
        disabled: true,
        columns: [
            {header: 'Cod Materia', dataIndex: 'codmateria'},
            {header: 'Materia', dataIndex: 'materia', flex: 1},
            {header: 'Cr&eacute;ditos', dataIndex: 'creditos'},
            {header: 'Nota', dataIndex: 'nota'},
            {header: 'Tipo Materia', dataIndex: 'tmateria'}
        ],
        //height: 220,
        id: 'gridmaterias',
        //width: '100%',
        tbar: [
            {
                xtype: 'combo',
                fieldLabel: '<b>' + perfil.etiquetas.lbTtlFacultad + '</b>',
                labelWidth: 50,
                padding: '0 0 0 10',
                id: 'idfacultad',
                name: 'idfacultad',
                allowBlank: true,
                emptyText: perfil.etiquetas.lbEmpCombo,
                editable: false,
                store: stcmbFacultades,
                width: 250,
                queryMode: 'local',
                displayField: 'denominacion',
                valueField: 'idestructura',
                listener: {}
            },
            {
                xtype: 'combo',
                fieldLabel: '<b>' + perfil.etiquetas.lbTtlCarrera + '</b>',
                labelWidth: 50,
                padding: '0 0 0 10',
                id: 'idcarrera',
                name: 'idcarrera',
                allowBlank: true,
                emptyText: perfil.etiquetas.lbEmpCombo,
                editable: false,
                store: stcmbCarreras,
                storeToFilter: [stgdmaterias, stgdnomaterias],
                width: 250,
                //disabled: true,
                queryMode: 'local',
                displayField: 'descripcion',
                valueField: 'idcarrera'
            },
            {
                xtype: 'combo',
                fieldLabel: '<b>' + perfil.etiquetas.lbTtlEnfasis + '</b>',
                labelWidth: 60,
                padding: '0 0 0 10',
                id: 'idenfasis',
                name: 'idenfasis',
                allowBlank: true,
                emptyText: perfil.etiquetas.lbEmpCombo,
                editable: false,
                store: stcmbEnfasis,
                storeToFilter: [stgdmaterias, stgdnomaterias],
                width: 250,
                //disabled: true,
                queryMode: 'local',
                displayField: 'descripcion',
                valueField: 'idenfasis'
            }
        ],
        flex: 1,
        bbar: new Ext.PagingToolbar({
            id: 'ptbaux999',
            store: stgdmaterias,
            displayInfo: true
        })
    });


    gridmateriasnocarrera = Ext.create('Ext.grid.Panel', {
        store: stgdnomaterias,
        //layout: 'fit',
        title: 'Materias aprobadas que no est&aacute;n en la carrera',
        flex: 1,
        disabled: true,
        columns: [
            {header: 'Cod Materia', dataIndex: 'codmateria'},
            {header: 'Materia', dataIndex: 'materia', flex: 1},
            {header: 'Cr&eacute;ditos', dataIndex: 'creditos'},
            {header: 'Nota', dataIndex: 'nota'}
        ],
        //height: '100%',
        id: 'gridmateriasnocarrera',
        //width: '100%',
        bbar: new Ext.PagingToolbar({
            id: 'ptbaux9910',
            store: stgdnomaterias,
            displayInfo: true
        })
    });

    formobser = Ext.create('Ext.tab.Panel', {
            activeTab: 0,
            items: [
                {
                    title: perfil.etiquetas.lbInfEst,
                    layout: {
                        type: 'vbox',
                        align: 'stretch',
                        defaultType: 'container'
                    },
                    items: [
                        {
                            flex: 1,
                            layout: {
                                type: 'hbox',
                                align: 'stretch',
                                defaultType: 'container'
                            },
                            //padding: '10 10 5 10',
                            border: 0,
                            width: 140,
                            items: [
                                {
                                    flex: 1,
                                    layout: 'anchor',
                                    border: 0,
                                    items: [
                                        {
                                            xtype: 'panel',
                                            //anchor: '100%, 100%',
                                            //padding: '0 5 0 0',
                                            layout: 'fit',
                                            title: 'Información general',
                                            height: 140,
                                            items: [
                                                {
                                                    xtype: 'fieldset',
                                                    border: 0,
                                                    defaultType: 'displayfield',
                                                    items: [
                                                        {
                                                            fieldLabel: perfil.etiquetas.lbtxtfldPromCont,
                                                            id: 'studentCodigo',
                                                            labelWidth: 250,
                                                            value: '-'
                                                        },
                                                        {
                                                            fieldLabel: perfil.etiquetas.lbtxtfldMatAprob,
                                                            id: 'matAprobadas',
                                                            labelWidth: 250,
                                                            value: '-'
                                                        },
                                                        {
                                                            fieldLabel: perfil.etiquetas.lbtxtfldMatAprobC,
                                                            id: 'matAprobadasC',
                                                            labelWidth: 250,
                                                            value: '-'
                                                        }
                                                    ]
                                                }
                                            ],
                                            bbar: ['->', {
                                                xtype: 'button',
                                                id: 'btnImprimir',
                                                text: '<b>' + perfil.etiquetas.lbBtnImprimir + '</b>',
                                                icon: perfil.dirImg + 'imprimir.png',
                                                iconCls: 'btn',
                                                handler: function () {
                                                }
                                            }
                                            ]
                                        }
                                    ]
                                },
                                {
                                    flex: 1,
                                    border: 0,
                                    layout: 'anchor',
                                    items: [grid1ro]
                                }
                            ]
                        },
                        {
                            //padding: '5 10 5 10',
                            flex: 2,
                            border: 0,
                            layout: 'anchor',
                            items: [grid2ro]

                        },
                        {
                            //padding: '5 10 10 10',
                            flex: 1,
                            border: 0,
                            layout: 'anchor',
                            items: [grid3ro]
                        }
                    ]
                },
                {
                    title: perfil.etiquetas.lbInfMatApr,
                    //bodyPadding: 10,
                    disabled: true,
                    id: 'idTabMatApr',
                    layout: {
                        type: 'vbox',
                        align: 'stretch',
                        defaultType: 'container'
                    },
                    items: [
                        gridmaterias,
                        gridmateriasnocarrera
                    ]
                },
                {
                    title: perfil.etiquetas.lbInfPasantias,
                    //bodyPadding: 10,
                    disabled: true,
                    id: 'idTabPasantias',
                    layout: {
                        type: 'vbox',
                        align: 'stretch',
                        defaultType: 'container'
                    },
                    items: [
                        gridpasantias, gridpracticas
                    ]
                },
                {
                    title: perfil.etiquetas.lbInfActual,
                    //bodyPadding: 10,
                    disabled: true,
                    id: 'idTabActualidad',
                    items: gridactual,
                    layout: 'fit'
                },
                {
                    title: perfil.etiquetas.lbBtnObservaciones,
                    //bodyPadding: 10,
                    disabled: true,
                    id: 'idTabObservaciones',
                    layout: 'fit',
                    items: gridobserv
                }
            ]
        }
    );

    var cmbobserv = Ext.getCmp('idtipoobservacion'),
        cmbfacultad = Ext.getCmp('idfacultad'),
        cmbfacultadp = Ext.getCmp('idfacultadp'),
        cmbcarrera = Ext.getCmp('idcarrera'),
        cmbcarrerap = Ext.getCmp('idcarrerap'),
        cmbenfasis = Ext.getCmp('idenfasis'),
        cmbenfasisp = Ext.getCmp('idenfasisp'),
        periodoact = Ext.getCmp('idperiodoact');


    cmbfacultad.on('select', function () {
        Ext.getCmp("idcarrera").enable();
        stcmbCarreras.load({
            params: {
                idfacultad: cmbfacultad.getValue()
            }
        });
        cmbcarrera.onTrigger1Click(false);


    });

    cmbfacultad.on('change', function () {
        Ext.getCmp("idcarrera").enable();
        stcmbCarreras.load({
            params: {
                idfacultad: cmbfacultad.getValue()
            }
        });

    });


    cmbfacultadp.on('select', function () {
        Ext.getCmp("idcarrerap").enable();
        stcmbCarrerasp.load({
            params: {
                idfacultad: cmbfacultadp.getValue()
            }
        });

    });

    cmbfacultadp.on('change', function () {
        Ext.getCmp("idcarrerap").enable();
        stcmbCarrerasp.load({
            params: {
                idfacultad: cmbfacultadp.getValue()
            }
        });

    });


    cmbfacultadp.on('select', function () {
        Ext.getCmp("idcarrerap").enable();
        stcmbCarrerasp.load({
            params: {
                idfacultad: cmbfacultadp.getValue()
            }
        });

    });

    cmbfacultadp.on('change', function () {
        Ext.getCmp("idcarrerap").enable();
        stcmbCarrerasp.load({
            params: {
                idfacultad: cmbfacultadp.getValue()
            }
        });

    });


    cmbfacultadp.on('select', function () {
        Ext.getCmp("idcarrerap").enable();
        stcmbCarrerasp.load({
            params: {
                idfacultad: cmbfacultadp.getValue()
            }
        });

    });

    cmbfacultadp.on('change', function () {
        Ext.getCmp("idcarrerap").enable();
        stcmbCarrerasp.load({
            params: {
                idfacultad: cmbfacultadp.getValue()
            }
        });

    });

    cmbcarrera.on('select', function () {
        Ext.getCmp("idenfasis").enable();
        idcarrera = cmbcarrera.getValue()
        stcmbEnfasis.load({
            params: {
                idenfasis: cmbcarrera.getValue()
            }
        });
        idcarrera = cmbcarrera.getValue();
    });
    cmbcarrera.on('change', function () {
        idcarrera = cmbcarrera.getValue()
        Ext.getCmp("idenfasis").enable();
        stcmbEnfasis.load({
            params: {
                idenfasis: cmbcarrera.getValue()
            }
        });
        idcarrera = cmbcarrera.getValue();
    });


    cmbcarrerap.on('select', function () {
        Ext.getCmp("idenfasisp").enable();
        stcmbEnfasisp.load({
            params: {
                idenfasis: cmbcarrerap.getValue()
            }
        });
    });
    cmbcarrerap.on('change', function () {
        Ext.getCmp("idenfasisp").enable();
        stcmbEnfasisp.load({
            params: {
                idenfasis: cmbcarrerap.getValue()
            }
        });
    });


    cmbcarrerap.on('select', function () {
        Ext.getCmp("idenfasisp").enable();
        stcmbEnfasisp.load({
            params: {
                idenfasis: cmbcarrerap.getValue()
            }
        });
    });
    cmbcarrerap.on('change', function () {
        Ext.getCmp("idenfasisp").enable();
        stcmbEnfasisp.load({
            params: {
                idenfasis: cmbcarrerap.getValue()
            }
        });
        cmbenfasisp.onTrigger1Click(false);
    });

    cmbcarrerap.on('select', function () {
        Ext.getCmp("idenfasisp").enable();
        stcmbEnfasisp.load({
            params: {
                idenfasis: cmbcarrerap.getValue()
            }
        });
        cmbenfasisp.onTrigger1Click(false);
    });
    cmbcarrerap.on('change', function () {
        Ext.getCmp("idenfasisp").enable();
        stcmbEnfasisp.load({
            params: {
                idenfasis: cmbcarrerap.getValue()
            }
        });
        cmbenfasisp.onTrigger1Click(false);
    });

    cmbenfasis.on('change', function () {
        idenfasis = cmbenfasis.getValue();
        stgdmaterias.load({
            params: {
                limit: 7,
                start: 0
            }
        });
        stgdnomaterias.load();
    });
    cmbenfasis.on('select', function () {
        idenfasis = cmbenfasis.getValue();
        stgdmaterias.load({
            params: {
                limit: 7,
                start: 0
            }
        });
        stgdnomaterias.load();
    });
    cmbenfasisp.on('select', function () {
        stgdpasantias.load({
            params: {
                idenfasis: cmbenfasisp.getValue(),
                idalumno: idalumno
            }
        });
        stgdpasantias2.load({
            params: {
                idenfasis: cmbenfasisp.getValue(),
                idalumno: idalumno
            }
        });
    });
    cmbenfasisp.on('change', function () {
        stgdpasantias.load({
            params: {
                idenfasis: cmbenfasisp.getValue(),
                idalumno: idalumno
            }

        });
        stgdpasantias2.load({
            params: {
                idenfasis: cmbenfasisp.getValue(),
                idalumno: idalumno
            }

        });
    })

    cmbfacultadp.on('select', function () {
        Ext.getCmp("idcarrerap").enable();
        stcmbCarrerasp.load({
            params: {
                idfacultad: cmbfacultadp.getValue()
            }
        });
        //  cmbcarrerap.clearValue();
        cmbenfasisp.onTrigger1Click(false);

    });

    cmbcarrerap.on('select', function () {
        Ext.getCmp("idenfasisp").enable();

        stcmbEnfasisp.load({
            params: {
                idcarrera: cmbcarrerap.getValue()
            }
        });
        cmbenfasisp.onTrigger1Click(false);
    });
    periodoact.on('select', function () {
        stgdActual.load()

    });

    periodoact.on('select', function () {
        stgdActual.load()
    });


    stcmbFacultades.on('load', function () {
        if (stcmbFacultades.count() > 0) {
            var tipo = stcmbFacultades.findExact('idestructura', idfacultad);
            if (tipo > 0) {
                cmbfacultad.select(stcmbFacultades.getAt(tipo).data.idestructura);
            }
            else {
                cmbfacultad.select(stcmbFacultades.getAt(0).data.idestructura);

            }

        }
    });

    stcmbCarreras.on('load', function () {
        if (stcmbCarreras.count() > 0) {
            var tipo = stcmbCarreras.findExact('idcarrera', idcarrera);
            if (tipo > 0) {
                cmbcarrera.select(stcmbCarreras.getAt(tipo).data.idcarrera);
            }
            else {
                cmbcarrera.select(stcmbCarreras.getAt(0).data.idcarrera);
            }
        }
    });
    stcmbEnfasis.on('load', function () {
        if (stcmbEnfasis.count() > 0) {
            var tipo = stcmbEnfasis.findExact('idenfasis', idenfasis);
            if (tipo > 0) {
                cmbenfasis.select(stcmbEnfasis.getAt(tipo).data.idenfasis);
            }
            else {
                cmbenfasis.select(stcmbEnfasis.getAt(0).data.idenfasis);
            }
        }
    });
    stcmbFacultadesp.on('load', function () {
        if (stcmbFacultadesp.count() > 0) {
            var tipo = stcmbFacultadesp.findExact('idestructura', idfacultad);
            if (tipo > 0) {
                cmbfacultadp.select(stcmbFacultadesp.getAt(tipo).data.idestructura);
            }
            else {
                cmbfacultadp.select(stcmbFacultadesp.getAt(0).data.idestructura);
            }

        }
    });

    stcmbCarrerasp.on('load', function () {
        if (stcmbCarrerasp.count() > 0) {
            var tipo = stcmbCarrerasp.findExact('idcarrera', idcarrera);
            if (tipo > 0) {
                cmbcarrerap.select(stcmbCarrerasp.getAt(tipo).data.idcarrera);
            }
            else {
                cmbcarrerap.select(stcmbCarrerasp.getAt(0).data.idcarrera);
            }
        }
    });
    stcmbEnfasisp.on('load', function () {
        if (stcmbEnfasisp.count() > 0) {
            var tipo = stcmbEnfasisp.findExact('idenfasis', idenfasis);
            if (tipo > 0) {
                cmbenfasisp.select(stcmbEnfasisp.getAt(tipo).data.idenfasis);
            }
            else {
                cmbenfasisp.select(stcmbEnfasisp.getAt(0).data.idenfasis);
            }
        }
    });

    stcmbPeriodos.on('load', function () {
        if (!periodoact.getValue()) {
            if (stcmbPeriodos.count() > 0) {
                periodoact.select(stcmbPeriodos.getAt(0).data.idperiododocente);
            }
        }
    });

    var storeEst = new Ext.data.Store({
        //autoLoad: true,
        fields: [
            {
                name: 'alumno'
            },
            {
                name: 'nombre'
            },
            {
                name: 'apellidos'
            },
            {
                name: 'codigo'
            },
            {
                name: 'sexo'
            },
            {
                name: 'idcarrera'
            },
            {
                name: 'idenfasis'
            },
            {
                name: 'idpensum'
            },
            {
                name: 'idestructura'
            }
        ],
        proxy: {
            type: 'ajax',
            url: '../gestestudiantes/cargarEstudiantesByNA',
            actionMethods: {
                read: 'POST'
            },
            reader: {
                totalProperty: "cantidad",
                root: "datos",
                id: "alumno"
            }
        }
    });

    var sm = Ext.create('Ext.selection.RowModel', {
        mode: 'SINGLE'
    });


    var GpEstudiante = Ext.create('Ext.grid.Panel', {
        store: storeEst,
        frame: true,
        selModel: sm,
        columns: [
            {
                dataIndex: 'alumno',
                hidden: true,
                hideable: false
            },
            {
                dataIndex: 'idcarrera',
                hidden: true,
                hideable: false
            },
            {
                dataIndex: 'idenfasis',
                hidden: true,
                hideable: false
            },
            {
                dataIndex: 'idestructura',
                hidden: true,
                hideable: false
            },
            {
                text: perfil.etiquetas.lbHdrGpECodigo,
                flex: 1,
                width: 100,
                dataIndex: 'codigo'
            },
            {
                text: perfil.etiquetas.lbHdrGpEApellidos,
                flex: 1.5,
                width: 220,
                dataIndex: 'apellidos'
            },
            {
                text: perfil.etiquetas.lbHdrGpENombre,
                flex: 1.5,
                width: 150,
                dataIndex: 'nombre'
            },
            {
                text: perfil.etiquetas.lbHdrGpESexo,
                flex: 1.5,
                width: 100,
                dataIndex: 'sexo'
            }
        ],
        tbar: [
            {
                xtype: 'searchfield',
                store: storeEst,
                width: 400,
                id: 'searchEst',
                fieldLabel: '<b>' + perfil.etiquetas.lbBtnBuscar + '</b>',
                labelWidth: 40,
                filterPropertysNames: ["nombre", "apellidos", "facultad", "codigo", "cedula", "pasaporte"]
            }
        ],
        bbar: new Ext.PagingToolbar({
            id: 'ptbaux9997',
            store: storeEst,
            displayInfo: true
        }),
        viewConfig: {
            getRowClass: function (record, rowIndex, rowParams, store) {
                if (record.data.estado == false)
                    return 'FilaRoja';
            },
            listeners: {
                itemdblclick: function (record, index, item, e) {
                    idalumno = sm.getLastSelected().get('alumno');
                    idfacultad = sm.getLastSelected().get('idestructura');
                    idcarrera = sm.getLastSelected().get('idcarrera');
                    idenfasis = sm.getLastSelected().get('idenfasis');
                    idpensum = sm.getLastSelected().get('idpensum');
                    stcmbFacultades.load();
                    stcmbFacultadesp.load();

                    var nombre = sm.getLastSelected().get('nombre') + " " + sm.getLastSelected().get('apellidos');
                    Ext.getCmp('tfnombre').setValue(nombre);
                    Ext.getCmp('tfcodigo').setValue(sm.getLastSelected().get('codigo'));
                    loadAction();
                    stCmbA.load();
                }

            }
        }

    })


    formaddobserv = Ext.create('Ext.form.Panel', {

        bodyStyle: 'padding:5px 5px 0',
        items: [
            {
                xtype: 'combo',
                fieldLabel: perfil.etiquetas.lbTipodeObserv,
                id: 'cmbidtipoobservacion',
                name: 'cmbidtipoobservacion',
                displayField: 'descripcion',
                valueField: 'idtipoobservacion',
                store: stcmbObserv,
                triggerAction: 'all',
                typeAhead: true,
                allowBlank: false,
                labelAlign: 'top',
                blankText: perfil.etiquetas.lbMsgBlank,
                mode: 'local',
                editable: false,
                emptyText: perfil.etiquetas.lbMsgBlank,
                width: 200,
                anchor: '95%'
            },
            {
                xtype: 'textfield',
                labelAlign: 'top',
                fieldLabel: perfil.etiquetas.lbDescripcion,
                id: 'tarobservacion',
                name: 'tarobservacion',
                triggerAction: 'all',
                autoScroll: true,
                allowBlank: false,
                anchor: '95%'
            },
            {
                xtype: 'checkbox',
                fieldLabel: 'Activado',
                name: 'estado',
                id: 'estado',
                checked: true
            }
        ]
    });

    function winForm() {
        if (!winIns) {
            winIns = new Ext.Window({
                modal: true,
                closeAction: 'hide',
                layout: 'fit',
                title: perfil.etiquetas.lbTitAdicionarObser,
                width: 360,
                height: 180,
                buttons: [
                    {
                        icon: perfil.dirImg + 'cancelar.png',
                        iconCls: 'btn',
                        text: perfil.etiquetas.lbBtnCancelar,
                        handler: function () {
                            winIns.close();
                            winIns = null;
                        }
                    },
                    {
                        icon: perfil.dirImg + 'aplicar.png',
                        iconCls: 'btn',
                        text: perfil.etiquetas.lbBtnAplicar,
                        handler: function () {
                            addObser('apl');
                        }
                    },
                    {
                        icon: perfil.dirImg + 'aceptar.png',
                        iconCls: 'btn',
                        text: perfil.etiquetas.lbBtnAceptar,
                        handler: function () {
                            addObser();
                        }
                    }
                ]
            });
            formaddobserv.getForm().reset();
            winIns.add(formaddobserv);
            winIns.doLayout();
            winIns.show();
        }
    }

    function Search() {

        if (!winSearch) {
            winSearch = Ext.create('Ext.window.Window', {
                modal: true,
                closeAction: 'hide',
                layout: 'fit',
                title: perfil.etiquetas.lbTitSelEst,
                width: 730,
                height: 520,
                buttons: [
                    {
                        icon: perfil.dirImg + 'cancelar.png',
                        iconCls: 'btn',
                        text: perfil.etiquetas.lbBtnCancelar,
                        handler: function () {

                            winSearch.close();
                            winSearch = null;
                        }
                    },
                    {
                        icon: perfil.dirImg + 'aceptar.png',
                        iconCls: 'btn',
                        text: perfil.etiquetas.lbBtnAceptar,
                        handler: function () {
                            idalumno = sm.getLastSelected().get('alumno');
                            idenfasis = sm.getLastSelected().get('idenfasis');
                            idfacultad = sm.getLastSelected().get('idestructura');
                            idcarrera = sm.getLastSelected().get('idcarrera');
                            idpensum = sm.getLastSelected().get('idpensum');
                            stcmbFacultades.load();
                            stcmbFacultadesp.load();
                            var nombre = sm.getLastSelected().get('nombre') + " " + sm.getLastSelected().get('apellidos');
                            Ext.getCmp('tfnombre').setValue(nombre);
                            Ext.getCmp('tfcodigo').setValue(sm.getLastSelected().get('codigo'));
                            loadAction();
                        }
                    }
                ]
            });
            winSearch.add(GpEstudiante);
            winSearch.doLayout();
            winSearch.show();
        }
    }

    var panel = new Ext.Panel({
        id: 'pepe',
        layout: 'fit',
        border: 0,
        tbar: [
            {
                text: '<b>' + perfil.etiquetas.lbCode + '</b>'
            },
            {
                xtype: 'textfield',
                name: 'codigo',
                id: 'tfcodigo',
                readOnly: true,
                width: 120
            },

            {
                text: '<b>' + perfil.etiquetas.lbName + '</b>'
            }, tfnombre, '-',
            btnBuscar
        ],
        items: formobser
    });


    var vpGestSistema = new Ext.Viewport({
        layout: 'fit',
        items: panel
    });

    function addObser(apl) {
        if (formaddobserv.getForm().isValid()) {
            formaddobserv.getForm().submit({
                method: 'POST',
                url: 'insertarObservaciones',
                waitMsg: perfil.etiquetas.lbMsgFunAdicionarEstMsg,
                params: {
                    idalumno: idalumno
                },
                failure: function (form, action) {
                    if (action.result.codMsg != 3) {
                        formaddobserv.getForm().reset();
                        stgdObserva.load()
                    }
                }

            });

            if (!apl)
                winIns.close();
        } else
            mostrarMensaje(3, perfil.etiquetas.lbMsgErrorEnCamops);

    }


    function loadAction() {
        std.load();
        stgdpasantias.load({
            params: {
                idenfasis: idenfasis,
                idalumno: idalumno
            }
        });
        stgdpasantias2.load({
            params: {
                idenfasis: idenfasis,
                idalumno: idalumno
            }
        });
        Ext.Ajax.request({
            url: 'contarMateriasAprob',
            params: {idalumno: idalumno},
            callback: function (options, success, response) {
                var mat_aprob = Ext.decode(response.responseText);
                Ext.getCmp('matAprobadas').setValue(mat_aprob.cantidad);
            }
        });
        Ext.Ajax.request({
            url: 'contarMateriasAprobNo',
            params: {
                idalumno: idalumno,
                idenfasis: idenfasis,
                idpensum: idpensum
            },
            callback: function (options, success, response) {
                var mat_aprob_no_carr = Ext.decode(response.responseText);
                Ext.getCmp('matAprobadasC').setValue(mat_aprob_no_carr.cantidad);
            }
        });
        stgdtipomat.load();

        Ext.getCmp('idTabMatApr').enable();
        Ext.getCmp('idTabPasantias').enable();
        Ext.getCmp('idTabActualidad').enable();
        Ext.getCmp('idTabObservaciones').enable();

        btnObservaciones.enable();
        gridactual.enable();
        gridpasantias.enable();
        gridpracticas.enable();
        gridmaterias.enable();
        grid1ro.enable();
        grid2ro.enable();
        grid3ro.enable();
        gridmateriasnocarrera.enable();
        btnImprimir2.enable();
        Ext.getCmp("idperiodoact").enable();
        gridobserv.enable();

        cmbcarrera.clearValue();
        //cmbcarrera.disable();
        cmbcarrerap.clearValue();
        //cmbcarrerap.disable();
        //cmbenfasis.disable();
        cmbenfasisp.onTrigger1Click(false);
        //cmbenfasisp.disable();
        cmbfacultad.clearValue();
        cmbfacultadp.clearValue();
        cmbobserv.onTrigger1Click(false);
        Ext.getCmp("idperiodoact").onTrigger1Click(false);

        winSearch.close();
        winSearch = null;
    }

    Ext.onReady(function () {
        Search();
    })
}
