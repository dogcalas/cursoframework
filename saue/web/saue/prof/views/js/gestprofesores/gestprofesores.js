var perfil = window.parent.UCID.portal.perfil;
UCID.portal.cargarEtiquetas('gestprofesores', function () {
    cargarInterfaz();
});
var winAdicionargestprofesores;
var winModificargestprofesores;
var formgestprofesores;
Ext.QuickTips.init();

function cargarInterfaz() {

    var stcmbSexo = Ext.data.SimpleStore({
        fields: ["sexo"],
        data: [
            {"sexo": "Femenino"},
            {"sexo": "Masculino"}
        ]
    });
    Ext.define('Colectivo', {
        extend: 'Ext.data.Model',
        fields: ['idcolectivo', 'descripcion']
    });
    Ext.define('Comisiones', {
        extend: 'Ext.data.Model',
        fields: ['idcomisiones', 'descripcion']
    });
    Ext.define('Categoria', {
        extend: 'Ext.data.Model',
        fields: ['idcategoria', 'descripcion']
    });
    var stcmbColectivo = Ext.create('Ext.data.Store', {

        storeId: 'idStoreColectivo',
        autoLoad: true,
        model: 'Colectivo',
        proxy: {
            type: 'ajax',
            url: 'cargarColectivos',
            reader: {
                type: 'json',
                id: 'idcolectivo',
                root: 'datos'
            }
        }
    });
    var stcmbComisiones = Ext.create('Ext.data.Store', {

        storeId: 'idStoreComisiones',
        autoLoad: true,
        model: 'Comisiones',
        proxy: {
            type: 'ajax',
            url: 'cargarComisiones',
            reader: {
                type: 'json',
                id: 'idcomisiones',
                root: 'datos'
            }
        }
    });
    var stcmbCategoria = Ext.create('Ext.data.Store', {

        storeId: 'idStoreCategoria',
        autoLoad: true,
        model: 'Categoria',
        proxy: {
            type: 'ajax',
            url: 'cargarCategorias',
            reader: {
                type: 'json',
                id: 'idcategoria',
                root: 'datos'
            }
        }
    });

    Ext.define('EstadoCivil', {
        extend: 'Ext.data.Model',
        fields: ['idestadocivil', 'descripcion']
    });

    Ext.define('Historial', {
        extend: 'Ext.data.Model',
        fields: ['idcurso', 'materia', 'periodo', 'horario', 'local', 'anno']
    });
    Ext.define('Titulos', {
        extend: 'Ext.data.Model',

        fields: [
            {
                name: 'idtitulo'
            },
            {
                name: 'titulo', mapping: 'descripcion'
            },
            {
                name: 'iduniversidad'
            },
            {
                name: 'anno'
            },
            {
                name: 'universidad'
            }
        ]
    });
    var stTitulos = Ext.create('Ext.data.Store', {

        storeId: 'idStoreTitulos',
        model: 'Titulos',
        proxy: {
            type: 'ajax',
            api: {
                read: 'cargarTitulos',
                create: 'insertarTitulo',
                destroy: 'eliminarTitulo'
            },
            actionMethods: {
                read: 'POST',
                update: 'POST'
            },
            reader: {
                type: 'json',
                id: 'idtitulo',
                root: 'datos'
            }
        }
    });
    var stGpgestuniversidades = Ext.create('Ext.data.ArrayStore', {
        autoLoad: true,
        fields: [
            {
                name: "iduniversidad"
            },
            {
                name: 'descripcion'
            },
            {
                name: 'estado'
            }
        ],
        proxy: {
            type: 'ajax',
            url: 'cargarUniversidades',
            reader: {
                type: 'json',
                id: 'iduniversidad',
                root: 'datos'
            }
        }
    });
    var stcmbEstadocivil = Ext.create('Ext.data.Store', {
        model: 'EstadoCivil',
        autoLoad: true,
        proxy: {
            type: 'ajax',
            url: 'cargarEstadoCivil',
            reader: {
                type: 'json',
                id: 'idestadocivil',
                root: 'datos'
            }
        }
    });
    var stHistorial = Ext.create('Ext.data.Store', {
        model: 'Historial',
        autoLoad: false,
        proxy: {
            type: 'ajax',
            url: 'cargarHistorial',
            reader: {
                root: 'datos',
                totalProperty: 'cantidad',
                successProperty: 'success',
                messageProperty: 'mensaje'
            },
            actionMethods: { //Esta Linea es necesaria para el metodo de llamada POST o GET
                read: 'POST'
            }
        }
    });
    stHistorial.on('beforeload', function (store) {
        store.getProxy().extraParams = {
            "idprofesor": sm.getLastSelected().data.idprofesor
        }
    });
    var btnAdicionargestprofesores = Ext.create('Ext.Button', {
        id: 'btnAgrgestprofesores',
        text: '<b>' + perfil.etiquetas.lbBtnAdicionar + '</b>',
        icon: perfil.dirImg + 'adicionar.png',
        iconCls: 'btn',
        hidden: true,
        handler: function () {
            mostFormgestprofesores('add');
        }
    });
    var btnModificargestprofesores = Ext.create('Ext.Button', {
        id: 'btnModgestprofesores',
        text: '<b>' + perfil.etiquetas.lbBtnModificar + '</b>',
        disabled: true,
        hidden: true,
        icon: perfil.dirImg + 'modificar.png',
        iconCls: 'btn',
        handler: function () {
            mostFormgestprofesores('mod');
        }
    });
    var btnEliminargestprofesores = Ext.create('Ext.Button', {
        id: 'btnEligestprofesores',
        text: '<b>' + perfil.etiquetas.lbBtnEliminar + '</b>',
        disabled: true,
        hidden: true,
        icon: perfil.dirImg + 'eliminar.png',
        iconCls: 'btn',
        handler: function () {
            Ext.MessageBox.show({
                title: 'Eliminar',
                msg: '¿Desea eliminar este profesor?',
                buttons: Ext.MessageBox.YESNO,
                icon: Ext.MessageBox.QUESTION,
                fn: eliminarProfesor
            });
        }
    });
    UCID.portal.cargarAcciones(window.parent.idFuncionalidad);


    formgestprofesores = new Ext.FormPanel({
        frame: true,
        bodyStyle: 'padding:5px auto 0px',
        fieldDefaults: {
            labelAlign: 'top',
            msgTarget: 'side'
        },
        items: [
            {
                xtype: 'tabpanel',
                plain: true,
                activeTab: 0,
                id: 'tabadd',
                items: [
                    {
                        title: 'Información personal',
                        layout: 'column',
                        border: false,
                        items: [
                            {
                                defaults: {width: '100%'},
                                columnWidth: .5,
                                border: false,
                                bodyStyle: 'padding:5px 0 5px 5px',
                                items: [
                                    {
                                        xtype: 'textfield',
                                        fieldLabel: 'Apellidos',
                                        name: 'apellidos',
                                        anchor: '100%',
                                        labelAlign: 'top',
                                        allowBlank: false
                                    },
                                    {
                                        xtype: 'textfield',
                                        fieldLabel: 'Nombre',
                                        name: 'nombre',
                                        anchor: '100%',
                                        labelAlign: 'top',
                                        allowBlank: false
                                    },
                                    {
                                        xtype: 'textfield',
                                        fieldLabel: 'Correo',
                                        name: 'correo',
                                        labelAlign: 'top',
                                        anchor: '100%',
                                        vtype: 'email',
                                        allowBlank: false
                                    },
                                    {
                                        xtype: 'fieldset',
                                        title: 'Identificación',
                                        defaults: {width: '100%'},
                                        items: [
                                            {
                                                xtype: 'radiogroup',
                                                id: 'radio',
                                                columns: 2,
                                                vertical: true,
                                                items: [
                                                    {boxLabel: 'Cédula', name: 'rb', inputValue: 'cedula', checked: true},
                                                    {boxLabel: 'Pasaporte', name: 'rb', inputValue: 'pasaporte'}
                                                ],
                                                listeners: {
                                                    change: function (radio, newValue, oldValue, eOpts) {
                                                        if (newValue.rb == 'pasaporte') {
                                                            console.log(radio);
                                                        } else {

                                                        }
                                                    }
                                                }
                                            },
                                            {
                                                id: 'cedpas',
                                                xtype: 'textfield',
                                                labelAlign: 'top',
                                                name: 'cedpas',
                                                allowBlank: false,
                                                validator: function(val){
                                                	if (Ext.getCmp('radio').getValue().rb == 'cedula'){
                                                		if(val.length > 10)
                                                			return 'No debe contener más de 10 n&uacute;meros';
                                                		if(!/^\d+$/.test(val)){
                                                			return 'Debe contener solo n&uacute;meros';
                                                		}

                                                	}else{
                                                		if(val.length > 10)
                                                			return 'No debe contener más de 10 n&uacute;meros';
                                                	}
                                                	return true;
                                                }
                                            }
                                        ]
                                    }
                                ]
                            },
                            {
                                defaults: {width: 200},
                                columnWidth: .5,
                                border: false,
                                bodyStyle: 'padding:5px',
                                items: [
                                    {
                                        xtype: 'combobox',
                                        fieldLabel: 'G&eacute;nero',
                                        name: 'sexo',
                                        allowBlank: false,
                                        editable: false,
                                        forceSelection: true,
                                        typeAhead: true,
                                        triggerAction: 'all',
                                        labelAlign: 'top',
                                        selectOnFocus: true,
                                        emptyText: "Seleccione el género",
                                        store: stcmbSexo,
                                        queryMode: 'local',
                                        displayField: 'sexo',
                                        valueField: 'sexo'
                                    },
                                    {
                                        xtype: 'combobox',
                                        fieldLabel: 'Estado civil',
                                        name: 'idestadocivil',
                                        editable: false,
                                        labelAlign: 'top',
                                        allowBlank: false,
                                        forceSelection: true,
                                        typeAhead: true,
                                        queryMode: 'local',
                                        triggerAction: 'all',
                                        store: stcmbEstadocivil,
                                        displayField: 'descripcion',
                                        valueField: 'idestadocivil'
                                    },
                                    {
                                        xtype: 'datefield',
                                        name: "fecha_nacimiento",
                                        fieldLabel: 'Fecha de nacimiento',
                                        maxValue: new Date(),
                                        allowBlank: false,
                                        labelAlign: 'top',
                                        editable: false
                                    },
                                    {
                                        xtype: 'checkbox',
                                        fieldLabel: 'Activado:',
                                        name: 'estado',
                                        labelAlign: 'left',
                                        style: {
                                            marginTop: '10px'
                                        },
                                        checked: true
                                    }
                                ]
                            }
                        ]
                    },
                    {
                        title: 'Datos adicionales',
                        bodyStyle: 'padding:5px',
                        layout: 'column',
                        items: [
                            {
                                defaults: {width: '100%'},
                                columnWidth: .5,
                                border: false,
                                bodyStyle: 'padding:5px 0 5px 5px',
                                items: [
                                    {
                                        xtype: 'textfield',
                                        labelAlign: 'top',
                                        fieldLabel: 'Especialización',
                                        name: 'especializacion'
                                    },
                                    {
                                        xtype: 'textfield',
                                        fieldLabel: 'Instrucción',
                                        name: 'instruccion',
                                        labelAlign: 'top'
                                    },
                                    {
                                        xtype: 'textareafield',
                                        name: 'domicilio',
                                        labelAlign: 'top',
                                        fieldLabel: 'Domicilio'
                                    },
                                    {
                                        xtype: 'textfield',
                                        fieldLabel: 'Teléfono',
                                        labelAlign: 'top',
                                        name: 'telefono'
                                    }
                                ]
                            },
                            {
                                defaults: {width: 200},
                                columnWidth: .5,
                                border: false,
                                bodyStyle: 'padding:5px 0 5px 5px',
                                items: [
                                    {
                                        xtype: 'textfield',
                                        fieldLabel: 'Celular',
                                        labelAlign: 'top',
                                        name: 'celular'
                                    },{
                                        xtype: 'combobox',
                                        fieldLabel: 'Colectivo acad&eacute;mico',
                                        name: 'idcolectivo',
                                        editable: false,
                                        forceSelection: true,
                                        typeAhead: true,
                                        triggerAction: 'all',
                                        selectOnFocus: true,
                                        store: stcmbColectivo,
                                        labelAlign: 'top',
                                        queryMode: 'local',
                                        displayField: 'descripcion',
                                        valueField: 'idcolectivo'
                                    },
                                    {
                                        xtype: 'combobox',
                                        fieldLabel: 'Comisiones',
                                        name: 'idcomisiones',
                                        editable: false,
                                        forceSelection: true,
                                        labelAlign: 'top',
                                        typeAhead: true,
                                        triggerAction: 'all',
                                        selectOnFocus: true,
                                        store: stcmbComisiones,
                                        queryMode: 'local',
                                        displayField: 'descripcion',
                                        valueField: 'idcomisiones'
                                    },
                                    {
                                        xtype: 'combobox',
                                        fieldLabel: 'Categor&iacute;a docente',
                                        name: 'idcategoria',
                                        editable: false,
                                        forceSelection: true,
                                        typeAhead: true,
                                        triggerAction: 'all',
                                        selectOnFocus: true,
                                        store: stcmbCategoria,
                                        queryMode: 'local',
                                        displayField: 'descripcion',
                                        valueField: 'idcategoria'
                                    }
                                ]}
                        ]
                    },
                    {
                        title: 'Niveles de formación',
                        items: [
                            {
                                xtype: 'gridpanel',
                                id: 'GpgestTitulos',
                                selType: 'checkboxmodel',
                                store: stTitulos,
                                height: 300,
                                listeners: {
                                    selectionchange: function (sm, selected, eOpts) {
                                        Ext.getCmp('btnDelTitulo').setDisabled(selected.length === 0);
                                    }
                                },
                                dockedItems: [
                                    {
                                        xtype: 'toolbar',
                                        dock: 'top',
                                        items: [
                                            {
                                                xtype: 'button',
                                                id: 'btnAddTitulo',
                                                text: '<b>' + perfil.etiquetas.lbBtnAdicionar + '</b>',
                                                icon: perfil.dirImg + 'adicionar.png',
                                                iconCls: 'btn',
                                                handler: function () {
                                                    addTitulo('add', 'GpgestTitulos');
                                                }
                                            },
                                            {
                                                xtype: 'button',
                                                id: 'btnDelTitulo',
                                                text: '<b>' + perfil.etiquetas.lbBtnEliminar + '</b>',
                                                disabled: true,
                                                icon: perfil.dirImg + 'eliminar.png',
                                                iconCls: 'btn',
                                                handler: function () {
                                                    addTitulo('del', 'GpgestTitulos');
                                                }
                                            }
                                        ]
                                    }
                                ],
                                columns: [
                                    {
                                        xtype: 'gridcolumn',
                                        dataIndex: 'titulo',
                                        text: 'Titulo',
                                        flex: 3
                                    },
                                    {
                                        xtype: 'gridcolumn',
                                        dataIndex: 'anno',
                                        text: 'Año'
                                    },
                                    {
                                        xtype: 'gridcolumn',
                                        dataIndex: 'universidad',
                                        text: 'Universidad',
                                        flex: 2
                                    }
                                ]
                            }
                        ]
                    }
                ],
                listeners: {
                    beforetabchange: function (tabs, newTab, oldTab) {
                        return formgestprofesores.getForm().isValid();
                    }
                }
            }
        ]
    });
    formgestprofesores2 = new Ext.FormPanel({
        frame: true,
        bodyStyle: 'padding:5px auto 0px',
        fieldDefaults: {
            labelAlign: 'top',
            msgTarget: 'side'
        },
        items: [
            {
                xtype: 'tabpanel',
                plain: true,
                activeTab: 0,
                id: 'tabmod',
                height: 320,
                items: [
                    {
                        title: 'Información personal',
                        layout: 'column',
                        border: false,
                        items: [
                            {
                                defaults: {width: '100%'},
                                columnWidth: .5,
                                border: false,
                                bodyStyle: 'padding:5px 0 5px 5px',
                                items: [
                                    {
                                        xtype: 'textfield',
                                        fieldLabel: 'Apellidos',
                                        name: 'apellidos',
                                        anchor: '100%',
                                        labelAlign: 'top',
                                        allowBlank: false
                                    },
                                    {
                                        xtype: 'textfield',
                                        fieldLabel: 'Nombre',
                                        name: 'nombre',
                                        anchor: '100%',
                                        labelAlign: 'top',
                                        allowBlank: false
                                    },
                                    {
                                        xtype: 'textfield',
                                        fieldLabel: 'Correo',
                                        name: 'correo',
                                        anchor: '100%',
                                        labelAlign: 'top',
                                        vtype: 'email',
                                        allowBlank: false
                                    },
                                    {
                                        xtype: 'fieldset',
                                        title: 'Identificación',
                                        defaults: {width: '100%'},
                                        items: [
                                            {
                                                xtype: 'radiogroup',
                                                id: 'radio2',
                                                columns: 2,
                                                vertical: true,
                                                items: [
                                                    {boxLabel: 'Cédula', name: 'rb', inputValue: 'cedula', checked: true},
                                                    {boxLabel: 'Pasaporte', name: 'rb', inputValue: 'pasaporte'}
                                                ]
                                            },
                                            {
                                                id: 'cedpas2',
                                                xtype: 'textfield',
                                                name: 'cedpas',
                                                labelAlign: 'top',
                                                vtype: 'alphanum',
                                                allowBlank: false,
                                                validator: function(val){
                                                	if (Ext.getCmp('radio2').getValue().rb == 'cedula'){
                                                		if(val.length > 10)
                                                			return 'No debe contener más de 10 n&uacute;meros';
                                                		if(!/^\d+$/.test(val)){
                                                			return 'Debe contener solo n&uacute;meros';
                                                		}

                                                	}else{
                                                		if(val.length > 10)
                                                			return 'No debe contener más de 10 n&uacute;meros';
                                                	}
                                                	return true;
                                                }
                                            }
                                        ]
                                    }
                                ]
                            },
                            {
                                defaults: {width: 200},
                                columnWidth: .5,
                                border: false,
                                bodyStyle: 'padding:5px',
                                items: [
                                    {
                                        xtype: 'combobox',
                                        fieldLabel: 'G&eacute;nero',
                                        name: 'sexo',
                                        editable: false,
                                        forceSelection: true,
                                        typeAhead: true,
                                        triggerAction: 'all',
                                        selectOnFocus: true,
                                        labelAlign: 'top',
                                        emptyText: "Seleccione el g&eacute;nero",
                                        store: stcmbSexo,
                                        queryMode: 'local',
                                        labelAlign: 'top',
                                        displayField: 'sexo',
                                        valueField: 'sexo'
                                    },
                                    {
                                        xtype: 'combobox',
                                        labelAlign: 'top',
                                        fieldLabel: 'Estado civil',
                                        name: 'idestadocivil',
                                        editable: false,
                                        forceSelection: true,
                                        emptyText: "Seleccione el estado civil",
                                        typeAhead: true,
                                        queryMode: 'local',
                                        triggerAction: 'all',
                                        store: stcmbEstadocivil,
                                        displayField: 'descripcion',
                                        valueField: 'idestadocivil'
                                    },
                                    {
                                        xtype: 'datefield',
                                        name: "fecha_nacimiento",
                                        fieldLabel: 'Fecha de nacimiento',
                                        maxValue: new Date(),
                                        allowBlank: false,
                                        editable: false
                                    },
                                    {
                                        xtype: 'checkbox',
                                        fieldLabel: 'Activado:',
                                        name: 'estado',
                                        labelAlign: 'left',
                                        style: {
                                            marginTop: '10px'
                                        },
                                        checked: true
                                    }
                                ]
                            }
                        ]
                    },
                    {
                        title: 'Datos adicionales',
                        bodyStyle: 'padding:5px',
                        layout: 'column',
                        items: [
                            {
                                defaults: {width: '100%'},
                                columnWidth: .5,
                                border: false,
                                bodyStyle: 'padding:5px 0 5px 5px',
                                items: [
                                    {
                                        xtype: 'textfield',
                                        fieldLabel: 'Especialización',
                                        labelAlign: 'top',
                                        name: 'especializacion'
                                    },
                                    {
                                        xtype: 'textfield',
                                        fieldLabel: 'Instrucción',
                                        labelAlign: 'top',
                                        name: 'instruccion'
                                    },
                                    {
                                        xtype: 'textareafield',
                                        name: 'domicilio',
                                        labelAlign: 'top',
                                        fieldLabel: 'Domicilio'
                                    },
                                    {
                                        xtype: 'textfield',
                                        fieldLabel: 'Teléfono',
                                        labelAlign: 'top',
                                        name: 'telefono'
                                    }
                                ]
                            },
                            {
                                defaults: {width: 200},
                                columnWidth: .5,
                                border: false,
                                bodyStyle: 'padding:5px 0 5px 5px',
                                items: [
                                    {
                                        xtype: 'textfield',
                                        fieldLabel: 'Celular',
                                        labelAlign: 'top',
                                        name: 'celular'
                                    },{
                                        xtype: 'combobox',
                                        fieldLabel: 'Colectivo acad&eacute;mico',
                                        name: 'idcolectivo',
                                        id: "col",
                                        editable: false,
                                        forceSelection: true,
                                        typeAhead: true,
                                        triggerAction: 'all',
                                        selectOnFocus: true,
                                        store: stcmbColectivo,
                                        labelAlign: 'top',
                                        queryMode: 'local',
                                        displayField: 'descripcion',
                                        valueField: 'idcolectivo'
                                    },
                                    {
                                        xtype: 'combobox',
                                        fieldLabel: 'Comisiones',
                                        name: 'idcomisiones',
                                        id: "com",
                                        editable: false,
                                        labelAlign: 'top',
                                        forceSelection: true,
                                        typeAhead: true,
                                        triggerAction: 'all',
                                        selectOnFocus: true,
                                        store: stcmbComisiones,
                                        queryMode: 'local',
                                        displayField: 'descripcion',
                                        valueField: 'idcomisiones'
                                    },
                                    {
                                        xtype: 'combobox',
                                        fieldLabel: 'Categor&iacute;a docente',
                                        name: 'idcategoria',
                                        id: "cat",
                                        editable: false,
                                        forceSelection: true,
                                        typeAhead: true,
                                        queryMode: 'local',
                                        triggerAction: 'all',
                                        labelAlign: 'top',
                                        store: stcmbCategoria,
                                        displayField: 'descripcion',
                                        valueField: 'idcategoria'
                                    }
                                ]}
                        ]
                    },
                    {
                        title: 'Historial laboral',
                        bodyStyle: 'padding:5px',
                        flex: 1,
                        layout: 'anchor',
                        items: [
                            {
                                xtype: 'gridpanel',
                                anchor: '100%, 91%',
                                viewConfig: {
                                    getRowClass: function (record, rowIndex, rowParams, store) {
                                        if (record.data.periodo.match("2015"))
                                            return 'FilaVerde';
                                    }
                                },
                                store: stHistorial,
                                columns: [
                                    { text: 'Año', dataIndex: 'anno', width: 50},
                                    { text: 'Período', dataIndex: 'periodo'},
                                    { text: 'Materia', dataIndex: 'materia', flex: 1},
                                    { text: 'Horario', dataIndex: 'horario', flex: 1},
                                    { text: 'Local', dataIndex: 'local'}
                                ],
                                bbar: new Ext.PagingToolbar({
                                    store: stHistorial,
                                    displayInfo: true,
                                    displayMsg: perfil.etiquetas.lbMsgbbarI,
                                    emptyMsg: perfil.etiquetas.lbMsgbbarII
                                })
                            }
                        ]
                    },
                    {
                        title: 'Niveles de formacion',
                        items: [
                            {
                                xtype: 'gridpanel',
                                id: 'GpgestModTitulos',
                                selType: 'checkboxmodel',
                                store: stTitulos,
                                height: 300,
                                listeners: {
                                    selectionchange: function (sm, selected, eOpts) {
                                        Ext.getCmp('btnDelModTitulo').setDisabled(selected.length === 0);
                                    }
                                },
                                dockedItems: [
                                    {
                                        xtype: 'toolbar',
                                        dock: 'top',
                                        items: [
                                            {
                                                xtype: 'button',
                                                id: 'btnAddModTitulo',
                                                text: '<b>' + perfil.etiquetas.lbBtnAdicionar + '</b>',
                                                icon: perfil.dirImg + 'adicionar.png',
                                                iconCls: 'btn',
                                                handler: function () {
                                                    addTitulo('add', 'GpgestModTitulos');
                                                }
                                            },
                                            {
                                                xtype: 'button',
                                                id: 'btnDelModTitulo',
                                                text: '<b>' + perfil.etiquetas.lbBtnEliminar + '</b>',
                                                disabled: true,
                                                icon: perfil.dirImg + 'eliminar.png',
                                                iconCls: 'btn',
                                                handler: function () {
                                                    addTitulo('del', 'GpgestModTitulos');
                                                }
                                            }
                                        ]
                                    }
                                ],
                                columns: [
                                    {
                                        xtype: 'gridcolumn',
                                        dataIndex: 'titulo',
                                        text: 'Titulo',
                                        flex: 3
                                    },
                                    {
                                        xtype: 'gridcolumn',
                                        dataIndex: 'anno',
                                        text: 'Año'
                                    },
                                    {
                                        xtype: 'gridcolumn',
                                        dataIndex: 'universidad',
                                        text: 'Universidad',
                                        flex: 2
                                    }
                                ]
                            }
                        ]
                    }
                ],
                listeners: {
                    beforetabchange: function (tabs, newTab, oldTab) {
                        return formgestprofesores2.getForm().isValid();
                    }
                }
            }
        ]
    });
    function adicionarTitulo(opcion, me) {
        win = me.up('window'),
            form = win.down('form');
        combo = form.down('combobox');

        if (form.getForm().isValid()) {
            values = form.getValues();
            values.universidad = combo.getRawValue();
            stTitulos.add(values);

            if (opcion === 'aceptar')
                win.hide();
            else
                form.getForm().reset();
        }

    }

    function addTitulo(opcion, gridpanel) {
        switch (opcion) {
            case 'add':
            {
                if (!winAdicionarTitulo) {
                    var winAdicionarTitulo = new Ext.Window({
                        height: 176,
                        closeAction: 'hide',
                        width: 400,
                        title: "Adicionar t&iacute;tulo",
                        items: [
                            {
                                xtype: "form",
                                frame: true,
                                bodyPadding: 10,
                                items: [
                                    {
                                        xtype: "textfield",
                                        anchor: "100%",
                                        fieldLabel: "T&iacute;tulo",
                                        name: 'titulo',
                                        allowBlank: false
                                    },
                                    {
                                        xtype: "combobox",
                                        anchor: "100%",
                                        editable: true,
                                        queryMode: 'local',
                                        fieldLabel: "Universidad",
                                        store: stGpgestuniversidades,
                                        displayField: 'descripcion',
                                        valueField: 'iduniversidad',
                                        name: 'iduniversidad',
                                        allowBlank: false
                                    },
                                    {
                                        xtype: "numberfield",
                                        anchor: "100%",
                                        fieldLabel: "Año",
                                        name: 'anno',
                                        decimalPrecision: 0,
                                        maxValue: 2100,
                                        minValue: 1890,
                                        allowBlank: false
                                    }
                                ]
                            }
                        ],
                        dockedItems: [
                            {
                                xtype: "toolbar",
                                dock: "bottom",
                                layout: {
                                    pack: "end",
                                    type: "hbox"
                                },
                                items: [
                                    {
                                        text: 'Cancelar',
                                        icon: perfil.dirImg + 'cancelar.png',
                                        handler: function () {
                                            winAdicionarTitulo.hide();
                                        }
                                    },
                                    {
                                        text: 'Aplicar',
                                        icon: perfil.dirImg + 'aplicar.png',
                                        handler: function () {
                                            adicionarTitulo("apl", this);
                                        }
                                    },
                                    {
                                        text: 'Aceptar',
                                        icon: perfil.dirImg + 'aceptar.png',
                                        id: 'btnAceptarT',
                                        handler: function () {
                                            adicionarTitulo("aceptar", this);
                                        }
                                    }
                                ]
                            }
                        ]
                    });
                }
                winAdicionarTitulo.show();
            }
                break;
            case 'del':
            {
                var grid = Ext.getCmp(gridpanel),
                    record = grid.getSelectionModel().getSelection(),
                    store = grid.getStore();

                mostrarMensaje(
                    2,
                    perfil.etiquetas.lbMsgConfEliminar,
                    function (btn, text) {
                        if (btn == 'ok') {
                            store.remove(record);
                        }
                    }
                )
            }
                break;
        }
    }

    function mostFormgestprofesores(opcion) {
        switch (opcion) {
            case 'add':
            {
                if (!winAdicionargestprofesores) {
                    winAdicionargestprofesores = new Ext.Window({
                        title: 'Adicionar profesor',
                        closeAction: 'hide',
                        width: 450,
                        height: 360,
                        resizable: false,
                        modal: true,
                        items: [formgestprofesores],
                        constrain: true,
                        layout: 'fit',
                        buttons: [
                            {
                                text: 'Cancelar',
                                icon: perfil.dirImg + 'cancelar.png',
                                handler: function () {
                                    winAdicionargestprofesores.hide();
                                }
                            },
                            {
                                text: 'Aplicar',
                                icon: perfil.dirImg + 'aplicar.png',
                                handler: function () {
                                    adicionarProfesor("apl");
                                }
                            },
                            {
                                text: 'Aceptar',
                                icon: perfil.dirImg + 'aceptar.png',
                                handler: function () {
                                    adicionarProfesor("aceptar");
                                }
                            }
                        ]
                    });
                }
                winAdicionargestprofesores.doLayout();
                winAdicionargestprofesores.show();
                stTitulos.removeAll();
                formgestprofesores.getForm().reset();
                Ext.getCmp('tabadd').suspendEvents();
                Ext.getCmp('tabadd').setActiveTab(0);
                Ext.getCmp('tabadd').resumeEvents();
            }
                break;
            case 'mod':
            {
                if (!winModificargestprofesores) {
                    winModificargestprofesores = new Ext.Window({
                        title: 'Modificar profesor',
                        closeAction: 'hide',
                        width: 540,
                        height: 360,
                        modal: true,
                        resizable: false,
                        items: [formgestprofesores2],
                        constrain: true,
                        layout: 'fit',
                        buttons: [
                            {
                                text: 'Cancelar',
                                icon: perfil.dirImg + 'cancelar.png',
                                handler: function () {
                                    winModificargestprofesores.hide();
                                }
                            },
                            {
                                text: 'Aceptar',
                                icon: perfil.dirImg + 'aceptar.png',
                                handler: function () {
                                    modificarProfesor("aceptar");
                                }
                            }
                        ]
                    });
                }
                winModificargestprofesores.doLayout();
                winModificargestprofesores.show();
                stHistorial.load();
                stTitulos.load({
                    params: {
                        idprofesor: sm.getLastSelected().data.idprofesor
                    }
                });
                Ext.getCmp('tabmod').suspendEvents();
                Ext.getCmp('tabmod').setActiveTab(0);
                Ext.getCmp('tabmod').resumeEvents();

                formgestprofesores2.getForm().loadRecord(sm.getLastSelected());
                if (sm.getLastSelected().data.cedula) {
                    Ext.getCmp('radio2').getComponent(0).setValue(true);
                    Ext.getCmp('cedpas2').setValue(sm.getLastSelected().data.cedula);
                } else {
                    Ext.getCmp('radio2').getComponent(1).setValue(true);
                    Ext.getCmp('cedpas2').setValue(sm.getLastSelected().data.pasaporte);
                }
            }
                break;
        }
    }

    var stGpgestprofesores = Ext.create('Ext.data.ArrayStore', {
        fields: [
            {
                name: 'idprofesor'
            },
            {
                name: 'nombre'
            },
            {
                name: 'apellidos'
            },
            {
                name: 'sexo'
            },
            {
                name: 'estado'
            },
            {
                name: 'estadocivil'
            },
            {
                name: 'idestadocivil'
            },
            {
                name: 'especializacion'
            },
            {
                name: 'instruccion'
            },
            {
                name: 'correo'
            },
            {
                name: 'pasaporte'
            },
            {
                name: 'cedula'
            },
            {
                name: 'fecha_nacimiento'
            },
            {
                name: 'domicilio'
            },
            {
                name: 'telefono'
            },
            {
                name: 'celular'
            },
            {
                name: 'idcomisiones'
            },
            {
                name: 'idcolectivo'
            },
            {
                name: 'idcategoria'
            },
            {
                name: 'comisiones'
            },
            {
                name: 'colectivo'
            },
            {
                name: 'categoria'
            }
        ],
        pageSize: 25,
        proxy: {
            remoteSort: true,
            type: 'ajax',
            url: 'cargarProfesores',
            reader: {
                type: 'json',
                id: 'idprofesor',
                totalProperty: "cantidad",
                root: 'datos'
            },
            actionMethods: {
                read: 'POST'
            }
        }
    });

    var Gpgestprofesores = Ext.create('Ext.grid.Panel', {
        store: stGpgestprofesores,
        stateful: true,
        stateId: 'stateGrid',
        columns: [
            {
                hidden: true,
                dataIndex: 'idprofesor'
            },
            {
                hidden: true,
                hideable: false,
                dataIndex: 'idestadocivil'
            },
            {
                text: 'Apellidos',
                flex: 1,
                dataIndex: 'apellidos'
            },
            {
                text: 'Nombre',
                flex: 1,
                dataIndex: 'nombre'
            },
            {
                text: 'G&eacute;nero',
                flex: 1, dataIndex: 'sexo',
                hidden: true
            },
            {
                text: 'Estado civil',
                flex: 1,
                dataIndex: 'estadocivil',
                hidden: true
            },
            {
                text: 'Especialización',
                flex: 1,
                dataIndex: 'especializacion'
            },
            {
                text: 'Instrucción',
                flex: 1,
                dataIndex: 'instruccion'
            },
            {
                text: 'Correo',
                flex: 1,
                dataIndex: 'correo',
                hidden: true
            },
            {
                text: 'Cédula',
                flex: 1,
                dataIndex: 'cedula'
            },
            {
                text: 'Pasaporte',
                flex: 1,
                dataIndex: 'pasaporte'
            },
            {
                text: 'Fecha de nacimiento',
                flex: 1,
                dataIndex: 'fecha_nacimiento',
                hidden: true
            },
            {
                hidden: true,
                dataIndex: 'estado'
            },
            {
                hidden: true,
                dataIndex: 'domicilio'
            },
            {
                hidden: true,
                dataIndex: 'telefono'
            },
            {
                hidden: true,
                dataIndex: 'celular'
            }
        ],
        dockedItems: [
            {
                xtype: 'pagingtoolbar',
                store: stGpgestprofesores,
                dock: 'bottom',
                displayInfo: true
            }
        ],
        viewConfig: {
            getRowClass: function (record, rowIndex, rowParams, store) {
                if (record.data.estado == false)
                    return 'FilaRoja';
            }
        },
        region: 'center',
        tbar: [btnAdicionargestprofesores, btnModificargestprofesores, btnEliminargestprofesores,'->',
            {
                xtype: 'searchfield',
                store: stGpgestprofesores,
                width: 400,
                fieldLabel: perfil.etiquetas.lbBtnBuscar,
                labelWidth: 40,
                filterPropertysNames: ['nombre', 'apellidos']
            }
            /* {xtype: 'textfield', labelWidth: 45, id: 'buscar'}, {xtype: 'button',
             text: perfil.etiquetas.lbBtnBuscar,
             icon: perfil.dirImg + 'buscar.png',
             iconCls: 'btn',
             handler: function() {
             stGpgestprofesores.clearFilter();
             if (Ext.getCmp('buscar').getValue() !== "") {
             stGpgestprofesores.filter({
             filterFn: function(item) {
             exp = new RegExp(Ext.getCmp('buscar').getValue().toLowerCase());
             return exp.test(item.get('apellidos').toLowerCase()) || exp.test(item.get('nombre').toLowerCase());
             }
             });
             }
             }}*/
        ]});
    var txtBuscarProfesor = new Ext.form.TextField();
    var sm = Gpgestprofesores.getSelectionModel();
    sm.setSelectionMode('MULTI');
    sm.on('selectionchange', function (sel, selectedRecord) {
        if (selectedRecord.length === 1) {
            btnModificargestprofesores.enable();
            btnEliminargestprofesores.enable();
        } else if (selectedRecord.length > 1) {
            btnModificargestprofesores.disable();
            btnEliminargestprofesores.enable();
        } else {
            btnModificargestprofesores.disable();
            btnEliminargestprofesores.disable();
        }
    });
    stGpgestprofesores.load();
    function adicionarProfesor(apl) {
        win = formgestprofesores;
        //si es la opción de aplicar
        var array = new Array();
        for (var i = 0; i < stTitulos.getCount(); i++) {
            array.push(stTitulos.getAt(i).data);
        }
        if (win.getForm().isValid()) {
            win.getForm().submit({
                url: 'insertarProfesor',
                params: {
                    titulos: Ext.encode(array)
                },
                waitMsg: perfil.etiquetas.lbMsgRegistrandoProfesor,
                failure: function (form, action) {
                    if (action.result.codMsg === 1) {
                        win.getForm().reset();
                        stGpgestprofesores.reload();
                        if (apl === "aceptar")
                            winAdicionargestprofesores.hide();
                        stTitulos.removeAll();
                    }


                }
            });
        }
    }

    function modificarProfesor(apl) {
        win = formgestprofesores2;
        if (win.getForm().isValid()) {
            win.getForm().submit({
                url: 'modificarProfesor',
                params: {
                    idprofesor: sm.getLastSelected().data.idprofesor},
                waitMsg: perfil.etiquetas.lbMsgModificandoProfesor,
                failure: function (form, action) {
                    if (action.result.codMsg === 1) {
                        stGpgestprofesores.reload();
                        if (apl === "aceptar")
                            winModificargestprofesores.hide();
                        stTitulos.getProxy().extraParams = {idprofesor: sm.getLastSelected().data.idprofesor};
                        stTitulos.sync();
                    }

                }
            });
        }
    }

    function eliminarProfesor(buttonId) {
        if (buttonId === "yes") {
            var arrProfesoresElim = sm.getSelection();
            var arrProfElim = [];
            for (var i = 0; i < arrProfesoresElim.length; i++) {
                arrProfElim.push(arrProfesoresElim[i].data.idprofesor);
            }
            Ext.Ajax.request({
                url: 'eliminarProfesor',
                method: 'POST',
                params: {ArrayProfDel: Ext.encode(arrProfElim)},
                callback: function (options, success, response) {
                    responseData = Ext.decode(response.responseText);
                    if (responseData.codMsg === 1) {
                        stGpgestprofesores.reload();
                        sm.deselect();
                    }
                }
            });
        }
    }

    var general = Ext.create('Ext.panel.Panel', {layout: 'border', items: [Gpgestprofesores]});
    var vpGestSistema = Ext.create('Ext.Viewport', {layout: 'fit', items: general});
}
