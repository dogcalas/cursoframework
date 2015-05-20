var perfil = window.parent.UCID.portal.perfil;
var idperiodo = 0
idperiodo1 = 0;
UCID.portal.cargarEtiquetas('gestweb', function () {
    cargarInterfaz();
});
Ext.QuickTips.init();
function cargarInterfaz() {

    var btnAdicionar = Ext.create('Ext.Button', {
        id: 'btnAgr',
        text: '<b>' + perfil.etiquetas.lbBtnAdicionar + '</b>',
        icon: perfil.dirImg + 'adicionar.png',
        iconCls: 'btn',
        hidden: true,
        handler: function () {
            mostForm('add');
        }
    });
    var btnAdicionar2 = Ext.create('Ext.Button', {
        id: 'btnAgr2',
        text: '<b>' + perfil.etiquetas.lbBtnAdicionar + '</b>',
        icon: perfil.dirImg + 'adicionar.png',
        iconAlign: 'top',
        hidden: false,
        disabled: false,
        iconCls: 'btn',
        handler: function () {
            adddPermisoG();
        }
    });
    var btnEliminar = Ext.create('Ext.Button', {
        id: 'btnEli',
        text: '<b>' + perfil.etiquetas.lbBtnEliminar + '</b>',
        disabled: true,
        icon: perfil.dirImg + 'eliminar.png',
        iconCls: 'btn',
        hidden: true,
        handler: function () {
            eliminarPermisoweb();
        }
    });
    var btnEliminar2 = Ext.create('Ext.Button', {
        id: 'btnEli2',
        text: '<b>' + perfil.etiquetas.lbBtnEliminar + '</b>',
        textAlign: 'bottom',
        disabled: true,
        hidden: false,
        iconAlign: 'top',
        icon: perfil.dirImg + 'eliminar.png',
        iconCls: 'btn',
        handler: function () {
            eliminarNF();
        }
    });

    UCID.portal.cargarAcciones(window.parent.idFuncionalidad);

    var smg = new Ext.selection.RowModel({
        mode: 'SINGLE'
    });

    smg.on('selectionchange', function (sel, selectedRecord) {
        if (selectedRecord.length === 1) {
            btnEliminar2.enable();
        } else {
            btnEliminar2.disable()
        }
    });
    var smm = new Ext.selection.RowModel({
        mode: 'MULTI'
    });

    var myData = [];
    var store = Ext.create('Ext.data.ArrayStore', {
        fields: [
            {name: 'nf'}
        ],
        data: myData
    });

    var grid = Ext.create('Ext.grid.Panel', {
        store: store,
        stateful: true,
        stateId: 'stateGrid',
        height: 120,
        selModel: smg,
        autoScroll: true,
        columns: [
            {
                flex: 1,
                sortable: true,
                dataIndex: 'nf'
            }
        ]
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
                Ext.getCmp('annot').select(a);

            }
        }
    });
    stCmbA.load();
    var stCmbAdd = Ext.create('Ext.data.ArrayStore', {
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
            url: 'cargarAnnosAdd',
            reader: {
                type: 'json',
                id: 'idannoadd',
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

                Ext.getCmp('cmbanno').select(a);
            }
        }
    });
    stCmbAdd.load();
    var stCmbP = Ext.create('Ext.data.ArrayStore', {
        fields: [
            {
                name: "idperiododocente"
            },
            {
                name: 'descripcion'
            },
            {
                name: 'codperiodo'
            }
        ],
        proxy: {
            type: 'ajax',
            url: 'cargarPeriodos',
            reader: {
                type: 'json',
                id: 'idperiododocente',
                root: 'datos'
            },
            actionMethods: {
                read: 'POST'
            }
        }
    });

    var stCmbNF = Ext.create('Ext.data.ArrayStore', {
        fields: [
            {
                name: "nf"
            }
        ],
        proxy: {
            type: 'ajax',
            url: 'cargarNandF',
            reader: {
                type: 'json',
                id: 'nf',
                root: 'datos'
            },
            actionMethods: {
                read: 'POST'
            }
        },
        listeners: {
            load: function () {
                if (stCmbNF.count() > 0) {
                    Ext.getCmp('nf').select(stCmbNF.getAt(0).data.nf);
                }
            }
        }
    });

    ////////////////*****************mis stores!!!*************/////////
    var stCmbATool = Ext.create('Ext.data.ArrayStore', {
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
        }
    });
    stCmbATool.load();
    var stCmbPTool = Ext.create('Ext.data.ArrayStore', {
        fields: [
            {
                name: "idperiododocente"
            },
            {
                name: 'descripcion'
            },
            {
                name: 'codperiodo'
            }
        ],
        proxy: {
            type: 'ajax',
            url: 'cargarPeriodos',
            reader: {
                type: 'json',
                id: 'idperiododocente',
                root: 'datos'
            },
            actionMethods: {
                read: 'POST'
            }
        },
        listeners: {
            load: function () {
                if (stCmbPTool.count() > 0) {
                    Ext.getCmp('periodotool').select(stCmbPTool.getAt(0).data.idperiododocente);
                    Ext.getCmp('cmbperiodo').select(stCmbPTool.getAt(0).data.idperiododocente);
                }
            }
        }
    });

    UCID.portal.cargarAcciones(window.parent.idFuncionalidad);
    var stgfac = new Ext.data.Store({
        fields: [
            {
                name: 'idestructura'
            },
            {
                name: 'denominacion'
            }],
        remoteSort: true,
        pageSize: 8,
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
    stgfac.load();
    var stgdmaterias = new Ext.data.Store({
        fields: [
            {
                name: 'idmateria'
            },
            {
                name: 'codmateria'
            },
            {
                name: 'materia'
            },
            {
                name: 'paralelo'
            },
            {
                name: 'profesor'
            },
            {
                name: 'profesor_apellidos'
            },
            {
                name: 'profesor_nombre_completo',
                type: 'string',
                convert: function (value, record) {
                    if (value)
                        return value;
                    var nombre_completo = record.get('profesor') + ' ' + record.get('profesor_apellidos');
                    return nombre_completo;
                }
            },
            {
                name: 'horario'
            },
            {
                name: 'aula'
            }
        ],
        remoteSort: true,
        pageSize: 8,
        proxy: {
            type: 'ajax',
            url: 'cargarMaterias',
            reader: {
                totalProperty: "cantidad",
                root: "datos"
            },
            actionMethods: {
                read: 'POST'
            }
        }
    });

    stgdmaterias.on('beforeload', function (store) {
        store.getProxy().extraParams = {
            idperiodo: idperiodo1
        }
    });

    var gridmaterias = Ext.create('Ext.grid.Panel', {
        store: stgdmaterias,
        // fitlayout: 'fit',
        height: 295,
        anchor: '100%',
        selModel: smm,
        columns: [
            {hidden: true, dataIndex: 'idmateria'},
            {text: 'C&oacute;digo', dataIndex: 'codmateria', flex: 1},
            {text: 'Descripci&oacute;n', dataIndex: 'materia', flex: 1},
            {hidden: true, text: 'Prof', dataIndex: 'profesor', flex: 1},
            {hidden: true, text: 'Apellidos', dataIndex: 'profesor_apellidos', flex: 1},
            {text: 'Profesor', dataIndex: 'profesor_nombre_completo', flex: 1},
            {text: 'Horario', dataIndex: 'horario', flex: 1},
            {text: 'Aula', dataIndex: 'aula', flex: 1}
        ],
        dockedItems: [{
            xtype: 'toolbar',
            dock: 'top',
            items: [
                '->',
                {xtype:'label',
                text:'Filtrar:'
                },
                {
                    width:280,
                    xtype: 'triggerfield',
                    id: 'trigermat',
                    trigger1Cls: Ext.baseCSSPrefix + 'form-search-trigger',
                    onTrigger1Click: function () {
                        stgdmaterias.load({
                            params: {
                                idperiodo: Ext.getCmp('cmbperiodo').getValue(),
                                data: Ext.getCmp('trigermat').getRawValue(),
                                filtro:true
                            }
                        });
                    }
                }
            ]
        }
        ],
        id: 'gridmaterias',
        bbar: new Ext.PagingToolbar({
            id: 'ptgm',
            store: stgdmaterias,
            displayInfo: true,
            displayMsg: perfil.etiquetas.lbMsgbbarI,
            emptyMsg: perfil.etiquetas.lbMsgbbarII
        })
    });

    var winAdicionar;
    var form = Ext.create('Ext.form.Panel', {
        frame: true,
        bodyStyle: 'padding:5px 5px 0',
        width: 720,
        height: 540,
        fieldDefaults: {
            msgTarget: 'side',
            labelWidth: 75
        },
        defaults: {
            anchor: '100%'
        },
        items: [
            {
                xtype: 'container',
                anchor: '100%',
                layout: 'column',
                items: [
                    {
                        defaults: {width: '100%'},
                        columnWidth: .5,
                        border: true,
                        bodyStyle: 'padding:15px 10px 20px 20px',
                        items: [
                            {
                                xtype: 'combo',
                                id: 'cmbanno',
                                name: 'cmbanno',
                                fieldLabel: 'A&ntilde;o',
                                allowBlank: true,
                                //  emptyText: perfil.etiquetas.lbEmpCombo,
                                editable: false,
                                store: stCmbAdd,
                                anchor: '100%',
                                labelWidth: 70,
                                labelAlign: 'top',
                                width: 100,
                                queryMode: 'local',
                                displayField: 'idanno',
                                valueField: 'anno'
                            },
                            {
                                xtype: 'combo',
                                id: 'cmbperiodo',
                                name: 'cmbperiodo',
                                fieldLabel: 'Periodo',
                                allowBlank: false,
                                //  emptyText: perfil.etiquetas.lbEmpCombo,
                                editable: false,
                                store: stCmbP,
                                labelWidth: 70,
                                labelAlign: 'top',
                                anchor: '100%',
                                width: 315,
                                queryMode: 'local',
                                displayField: 'descripcion',
                                valueField: 'idperiododocente'
                            },
                            {
                                xtype: 'fieldset',
                                id: 'fldstCto',
                                // title: '<b>' + perfil.etiquetas.fldstlbCto + '</b>',
                                border: 1,
                                style: {
                                    borderColor: 'black',
                                    borderStyle: 'solid'
                                },
                                defaults: {
                                    width: '100%',
                                    bodyStyle: 'padding:5px 5px 5px 5px'
                                },
                                items: [
                                    {
                                        xtype: 'container',
                                        anchor: '95%',
                                        layout: 'column',
                                        items: [
                                            {
                                                xtype: 'container',
                                                columnWidth: .5,
                                                layout: 'anchor',
                                                items: [
                                                    {
                                                        xtype: 'datefield',
                                                        fieldLabel: 'Fecha inicio',
                                                        name: 'fecha_ini',
                                                        format: 'Y-m-d',
                                                        allowBlank: false,
                                                        minValue: new Date(),
                                                        editable: false,
                                                        id: 'fecha_ini',
                                                        labelAlign: 'top',
                                                        anchor: '95%',
                                                        listeners: {
                                                            'select': function (field, value) {
                                                                Ext.getCmp('fecha_fin').setMinValue(value);
                                                                Ext.getCmp('fecha_fin').enable();
                                                            },
                                                            'change': function (field, value) {
                                                                Ext.getCmp('fecha_fin').setMinValue(value);
                                                                Ext.getCmp('fecha_fin').enable();
                                                            }
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
                                                        xtype: 'datefield',
                                                        fieldLabel: 'Fecha fin',
                                                        name: 'fecha_fin',
                                                        id: 'fecha_fin',
                                                        allowBlank: false,
                                                        editable: false,
                                                        disabled: true,
                                                        format: 'Y-m-d',
                                                        labelAlign: 'top',
                                                        anchor: '95%'
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
                        columnWidth: .5,
                        border: true,
                        bodyStyle: 'padding:15px 0 5px 10px',
                        items: [
                            {
                                xtype: 'fieldset',
                                id: 'fldst2',
                                title: '<b>' + perfil.etiquetas.fldstlbTP + '</b>',
                                height: 152,
                                border: 1,
                                style: {
                                    borderColor: 'black',
                                    borderStyle: 'solid'
                                },
                                defaults: {
                                    width: '95%',
                                    bodyStyle: 'padding:5px 5px 5px 5px'
                                },
                                items: [
                                    {
                                        xtype: 'container',
                                        anchor: '95%',
                                        layout: 'column',
                                        items: [
                                            {
                                                xtype: 'container',
                                                columnWidth: .5,
                                                layout: 'anchor',
                                                items: [
                                                    {
                                                        xtype: 'combo',
                                                        id: 'nf',
                                                        name: 'nf',
                                                        allowBlank: false,
                                                        disabled: false,
                                                        label: 'Tipos de permisos',
                                                        editable: false,
                                                        store: stCmbNF,
                                                        labelAlign: 'top',
                                                        anchor: '95%',
                                                        width: 20,
                                                        queryMode: 'local',
                                                        displayField: 'nf',
                                                        valueField: 'nf',
                                                        listeners: {
                                                            select: function () {
                                                                btnAdicionar2.enable();
                                                            }
                                                        }
                                                    },
                                                    btnAdicionar2,
                                                    btnEliminar2
                                                ]
                                            },
                                            {
                                                xtype: 'container',
                                                columnWidth: .5,
                                                layout: 'anchor',
                                                anchor: '95%',
                                                items: grid
                                            }
                                        ]
                                    }
                                ]
                            }
                        ]
                    }
                ]
            },
            gridmaterias
        ]
    });

    var annotool = Ext.create('Ext.form.ComboBox', {
        id: 'annot',
        fieldLabel: 'Año',
        labelAlign: 'top',
        labelWidth: 45,
        width: 90,
        queryMode: 'local',
        valueField: 'anno',
        displayField: 'anno',
        editable: false,
        store: stCmbA
    });

    var pertool = Ext.create('Ext.form.ComboBox', {
        id: 'periodotool',
        forceSelection: true,
        labelAlign: 'top',
        width: 300,
        autoSelect: true,
        fieldLabel: 'Período:',
        queryMode: 'local',
        valueField: 'idperiododocente',
        displayField: 'descripcion',
        editable: false,
        disabled: true,
        store: stCmbPTool
    });

    pertool.on('change', function (store) {
        idperiodo = Ext.getCmp('periodotool').getValue();
        stGp.load({params: {idperiodo: idperiodo}});
    });

    var mattool = Ext.create('Ext.form.TriggerField', {
        id: 'materiatool',
        forceSelection: true,
        autoSelect: true,
        labelAlign: 'top',
        width: 170,
        fieldLabel: 'Materia:',
        queryMode: 'local',
        valueField: 'idmateria',
        displayField: 'descripcion',
        trigger1Cls: Ext.baseCSSPrefix + 'form-search-trigger',
        editable: false,
        onTrigger1Click: function () {
            mostMateria();
        },
        store: stgdmaterias,
        listeners:{
            focus: function (record, index, item, e) {
                mostMateria();
            }
        }
    });

    var factool = Ext.create('Ext.form.ComboBox', {
        id: 'facultadtool',
        forceSelection: true,
        autoSelect: true,
        labelAlign: 'top',
        width: 170,
        fieldLabel: 'Facultad:',
        queryMode: 'local',
        valueField: 'idestructura',
        displayField: 'denominacion',
        //storeToFilter: 'RegMaterias.store.MateriasEst',
        //  filterPropertysNames: ['idperiododocente'],
        //allowBlank:false,
        editable: false,
        disabled: true,
        //disabled:true,
        store: stgfac
    });

    Ext.getCmp('cmbanno').on('change', function () {
        //Ext.getCmp('cmbperiodo').reset();
        stCmbP.load({
            params: {
                annop: Ext.getCmp('cmbanno').getValue()
            }
        });
    });

    Ext.getCmp('cmbperiodo').on('change', function () {
        idperiodo1 = Ext.getCmp('cmbperiodo').getValue();
        stgdmaterias.load({
            params: {
                idperiodo: idperiodo1
            }
        });
        Ext.getCmp('nf').enable();
        stCmbNF.load({
            params: {
                idperiodo: idperiodo1
            }
        });
        myData = [];

        store.loadData(myData)
    });

    /***************mi funcion***********/////////////

    Ext.getCmp('annot').on('change', function () {
        if (Ext.getCmp('periodotool').isDisabled()) {
            Ext.getCmp('periodotool').setDisabled(false);
        }
        //Ext.getCmp('periodotool').reset();
        stCmbPTool.load({
            params: {
                annop: Ext.getCmp('annot').getValue()
            }
        })
    });


    /* Ext.getCmp('materiatool').on('onTrigger1Click', function(){
     mostMateria();
     })*/
    var stsmat = new Ext.data.Store({
        fields: [
            {
                name: 'idmateria'
            },
            {
                name: 'codmateria'
            },
            {
                name: 'descripcion'
            }
        ],
        pageSize: 25,
        proxy: {
            type: 'ajax',
            api: {
                read: 'searchMaterias'
            },
            actionMethods: {
                read: 'POST'
            },
            reader: {
                root: 'datos',
                totalProperty: 'cantidad',
                successProperty: 'success',
                messageProperty: 'mensaje'
            }
        }
    });

    function mostMateria() {
        var mostrar = Ext.create('Ext.window.Window', {
            title: 'Materia',
            height: 450,
            width: 400,
            layout: 'fit',
            items: {
                xtype: 'grid',
                border: false,
                columns: [
                    {text: 'Materia', dataIndex: 'descripcion', flex: 1},
                    {text: 'Codigo', dataIndex: 'codmateria', flex: 1}
                ],                 // One header just for show. There's no data,
                store: stsmat,
                listeners: {
                    itemdblclick: function (record, index, item, e) {
                        stGp.removeAll();
                        record = this.up('window').down('grid').getSelectionModel().getLastSelected();
                        if (record) {
                            stGp.reload({
                                params: {
                                    idperiodo: idperiodo,
                                    materia: this.up('window').down('grid').getSelectionModel().getLastSelected().data['idmateria']
                                }
                            });
                            this.up('window').close();
                        }
                        mattool.setRawValue(record.data['descripcion']);
                    }

                }
            },
            dockedItems: [{
                xtype: 'toolbar',
                dock: 'top',
                items: [
                    {
                        xtype: 'triggerfield',
                        id: 'triger',
                        trigger1Cls: Ext.baseCSSPrefix + 'form-search-trigger',
                        onTrigger1Click: function () {
                            stsmat.load({
                                params: {
                                    idperiodo: idperiodo,
                                    mat: Ext.getCmp('triger').getRawValue()
                                }
                            });
                        }
                    }
                ]
            }
            ],
            buttons: [
                {
                    text: 'Aceptar', id: 'si', handler: function () {
                    stGp.removeAll();
                    record = this.up('window').down('grid').getSelectionModel().getLastSelected();
                    if (record) {
                        stGp.reload({
                            params: {
                                idperiodo: idperiodo,
                                materia: this.up('window').down('grid').getSelectionModel().getLastSelected().data['idmateria']
                            }
                        });
                        this.up('window').close();
                    }
                    mattool.setRawValue(record.data['descripcion']);

                }
                },
                {
                    text: 'Cancelar', handler: function () {
                    this.up('window').hide();
                }
                }
            ]
        }).show();
    }

    function mostForm(opcion) {
        switch (opcion) {
            case 'add':
            {
                myData = [];

                store.loadData(myData)
                Ext.getCmp('nf').enable();
                if (!winAdicionar) {

                    winAdicionar = Ext.create('Ext.Window', {
                        title: 'Adicionar permisos',
                        closeAction: 'hide',
                        width: 720,
                        height: 560,
                        constrain: true,
                        layout: 'fit',
                        buttons: [
                            {
                                text: 'Cancelar',
                                icon: perfil.dirImg + 'cancelar.png',
                                handler: function () {
                                    winAdicionar.hide();
                                }
                            },
                            {
                                text: 'Aplicar',
                                icon: perfil.dirImg + 'aplicar.png',
                                handler: function () {
                                    adicionar(false);
                                }
                            },
                            {
                                text: 'Aceptar',
                                icon: perfil.dirImg + 'aceptar.png',
                                handler: function () {
                                    adicionar(true);
                                }
                            }
                        ],
                        items: form
                    });
                    //winAdicionar.add(form);
                }
                var ho = new Date(),
                    a = ho.getFullYear().toString();
                Ext.getCmp('cmbanno').select(a);
                Ext.getCmp('cmbperiodo').select(stCmbPTool.getAt(0).data.idperiododocente);
                winAdicionar.show();
                winAdicionar.on('hide', function () {
                    form.getForm().reset();
                }, this);
            }
                break;
        }
    }

    var stGp = Ext.create('Ext.data.ArrayStore', {
        fields: [
            {
                name: "idpermisoweb"
            },
            {
                name: 'fecha_ini'
            },
            {
                name: 'fecha_fin'
            },
            {
                name: 'fecha'
            },
            {
                name: 'codmateria'
            },
            {
                name: 'nf'
            },
            {
                name: 'materia'
            },
            {
                name: 'usuario'
            },
            {
                name: 'estado'
            }
        ],
        proxy: {
            type: 'ajax',
            api: {
                read: 'cargarPermisosWeb'
            },
            actionMethods: { //Esta Linea es necesaria para el metodo de llamada POST o GET
                read: 'POST'
            },
            reader: {
                root: 'datos',
                totalProperty: 'cantidad',
                successProperty: 'success',
                messageProperty: 'mensaje'
            }
        }
    });

    stGp.on('beforeload', function (store) {
        store.getProxy().extraParams = {
            idperiodo: idperiodo
        }
    });

    var Gp = Ext.create('Ext.grid.Panel', {
        store: stGp,
        stateId: 'stateGrid',
        columnLines: true,
        selModel: sm,
        viewConfig: {
            getRowClass: function (record, rowIndex, rowParams, store) {
                if (record.data.estado == false)
                    return 'FilaRoja';
            }
        },
        columns: [
            {
                hidden: true,
                dataIndex: 'idpermisoweb'
            },
            {
                header: 'C&oacute;digo',
                sortable: true,
                dataIndex: 'codmateria'
            },
            {
                header: 'Materia',
                flex: 3,
                sortable: true,
                dataIndex: 'materia'
            },
            {
                header: ' Fecha inicio',
                flex: 1,
                sortable: true,
                dataIndex: 'fecha_ini'
            },
            {
                header: ' Fecha fin',
                flex: 1,
                sortable: true,
                dataIndex: 'fecha_fin'
            },
            {
                header: ' Fecha registro',
                flex: 1,
                sortable: true,
                dataIndex: 'fecha'
            },
            {
                header: 'Usuario',
                sortable: true,
                dataIndex: 'usuario'
            },
            {
                header: 'Permisos',
                flex: 2,
                sortable: true,
                dataIndex: 'nf'
            }
        ],
        region: 'center',
        tbar: [btnAdicionar, btnEliminar, '->', annotool, pertool, mattool, factool],
        bbar: new Ext.PagingToolbar({
            id: 'bbar1',
            store: stGp,
            displayInfo: true,
            displayMsg: perfil.etiquetas.lbMsgbbarI,
            emptyMsg: perfil.etiquetas.lbMsgbbarII
        })
    });

    var sm = Gp.getSelectionModel();
    sm.setSelectionMode('MULTI');

    sm.on('selectionchange', function (sel, selectedRecord) {
        btnEliminar.setDisabled(selectedRecord.length == 0);
    });
    //stGp.load();



    function adddPermisoG() {
        var nfdata = Ext.getCmp('nf').getValue();
        var arra = new Array(nfdata);
        var is = false;
        for (var i = 0; i < myData.length; i++) {
            if (myData[i] == nfdata) {
                is = true;
            }
        }
        if (!is)
            myData.push(arra);
        store.loadData(myData);
        return;
    }

    function eliminarNF() {
        var nfdata = smg.getLastSelected().data.nf;
        var myDataa = new Array();
        for (var i = 0; i < myData.length; i++) {
            if (myData[i] != nfdata) {
                myDataa.push(myData[i])
            }
        }
        myData = [];

        for (var i = 0; i < myDataa.length; i++) {
            myData.push(myDataa[i]);
        }
        store.loadData(myData)
    }

    function adicionar(add) {
        var delMask = new Ext.LoadMask(Ext.getBody(), {
            msg: 'Insertando permisos...'
        });
        var nfs = new Array();
        for (var i = 0; i < myData.length; i++) {
            nfs.push(myData[i]);
        }
        var idsmat = new Array();
        for (var i = 0; i < smm.getCount(); i++) {
            //console.log(smm.getSelection()[i].raw.idmateria)
            idsmat.push(smm.getSelection()[i].raw.idmateria)
        }
        var periodo = idperiodo1;
        var fecha_ini = Ext.getCmp("fecha_ini").getValue();
        var fecha_fin = Ext.getCmp("fecha_fin").getValue();
        if (periodo != "" && fecha_fin != "" && fecha_ini != "" && nfs.length > 0 && idsmat.length > 0) {
            delMask.show();
            Ext.Ajax.request({
                url: 'insertarPermisos',
                method: 'POST',
                params: {
                    idperiodo: periodo,
                    fecha_ini: fecha_ini,
                    fecha_fin: fecha_fin,
                    nf: Ext.JSON.encode(nfs),
                    idsmateria: Ext.JSON.encode(idsmat)
                },
                callback: function (options, success, response) {
                    var responseData = Ext.decode(response.responseText);
                    delMask.disable();
                    delMask.hide();
                    if (responseData.codMsg === 1) {
                        sm.clearSelections();
                        stGp.load({params: {idperiodo: idperiodo}});
                        btnEliminar.disable();
                        if (add)
                            winAdicionar.hide();
                    }
                }
            });

        } else {
            mostrarMensaje(3, 'Debe seleccionar todos los elementos correctamente');
        }
    }

    function eliminarPermisoweb() {
        mostrarMensaje(2, '¿Desea eliminar este permiso?', eliminar);
        var delMask = new Ext.LoadMask(Ext.getBody(), {
            msg: 'Eliminando permiso...'
        });

        function eliminar(btnPresionado) {
            if (btnPresionado === 'ok') {
                var ids = new Array();
                for (var i = 0; i < sm.getCount(); i++) {
                    ids.push(sm.getSelection()[i].raw.idpermisoweb);
                }
                delMask.show();
                Ext.Ajax.request({
                    url: 'eliminarPermiso',
                    method: 'POST',
                    params: {
                        idpermisosweb: Ext.JSON.encode(ids)
                    },
                    callback: function (options, success, response) {
                        var responseData = Ext.decode(response.responseText);
                        delMask.disable();
                        delMask.hide();
                        if (responseData.codMsg === 1) {
                            sm.clearSelections();
                            stGp.load({params: {idperiodo: idperiodo}});
                            btnEliminar.disable();
                        }
                    }
                });
            }
        }
    };

    var general = Ext.create('Ext.panel.Panel', {layout: 'border', items: [Gp]});
    var vpGestSistema = Ext.create('Ext.Viewport', {layout: 'fit', items: general});
}