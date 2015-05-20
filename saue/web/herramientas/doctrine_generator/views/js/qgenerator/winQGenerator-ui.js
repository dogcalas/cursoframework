var node;
var tree, treemanage, rootmanageNode, treemanageLoader;
var btnQOpen, btnQCancel, tfversion, textnames, getall;
var fsXlimite, ckGenLimit;
var ckfind, ckfindAll, ckgetLlave, cksave, modes, autogen, stQFields, stQFieldsV, stQFieldsC;
var pIcentral, pIoeste, pIsur;
var btnQParam, tfQfunctName, chkQJoin, cmbQAction, stQActionsaa;
var btnQWGuardar, cmbQValor, cmbQOperator, cmbAndOr, stAndOr, btnQFAadd, tfQFagregAlias, btnQEdit;
var cmbQFagregCampos, cmbQFAgregElegir, smQFAgregadas, stQFAgregadas, btnQFACancel, cmbQFACampo;
var cmbQFAOperator, cmbQFAValor, btnQFAWApp, cmbQFAAndOr, btnQFAWAO, txtQFAFilter, cmbQCampo;
var funcionesagregadas, cmbQtipJoin, btnQWApp, btnQWAO, btnQJAdd, btnQWOR, btnQWCancel;
var btnQJCancel, txtQWFilter, txtQJFilter, smQJoinTables;
var perfil, ckorderByASC, stQGClasses, cbClassesQ, btnQMap, treeLoader, smQTables, smQWFields;
var txtQFilter, txtQWFilter, stQTable, stQWFields, stVisib, cmbVisib;
var winQParam, unSave = false;

DoctrineGenerator.UI.winQGenerator = Ext.extend(Ext.Window, {
    title: 'Generador de Consultas',
    layout: 'fit',
    height: 580,
    width: 1000,
    minWidth: 800,
    maxWidth: 1000,
    maximizable: true,
    maximized: true,
    // modal: true,
    closable: true,
    resizable: false,
    id: 'mywinOk',
    listeners: {
        click: function(n) {
            Ext.Msg.alert('Navigation Tree Click', 'You clicked: ""');
        }
    },
    initComponent: function() {

        winQParam = new DoctrineGenerator.winQParam()
        perfil = window.parent.UCID.portal.perfil;
        btnQOpen = new Ext.Button({
            text: 'Guardar',
            icon: perfil.dirImg + 'guardar.png',
            iconCls: 'btn'
        })

        btnQCancel = new Ext.Button({
            text: 'Cerrar',
            icon: perfil.dirImg + 'cancelar.png',
            iconCls: 'btn',
        })



        this.buttons = [btnQCancel, btnQOpen]
        //////----Combo para Editar con Doctrine 2-----/////
        tfversion = new Ext.form.TextField({
            id: 'tfversion',
            name: 'tfversion',
            allowBlank: false,
            readOnly: true,
            editable: false,
            width: 120
        })
        ////---TextField para editar el namespace-------///////
        textnames = new Ext.form.TextField({
            fieldLabel: 'Namespace:',
            name: 'tnamespace',
            id: 'tnamespace',
            triggerAction: 'all',
            emptyText: 'Ej: "component\\subcomponent"',
            width: 206
        })
        /*
         * Conjunto de combobox para la seleccion de metodos
         * magicos
         */
        getall = new Ext.form.Checkbox({
            name: 'getall',
            fieldLabel: 'getCount',
            anchor: '100%',
            id: 'getall',
            listeners: {
                click: function(n) {
                    Ext.Msg.alert('Navigation Tree Click', 'You clicked: ""');
                }
            }
        })

        ckfind = new Ext.form.Checkbox({
            name: 'ckfind',
            fieldLabel: 'find',
            anchor: '100%',
            id: 'ckfind'
        })
        ckfindAll = new Ext.form.Checkbox({
            name: 'ckfindall',
            fieldLabel: 'findAll',
            anchor: '100%',
            id: 'ckfindall'
        })
        cksave = new Ext.form.Checkbox({
            name: 'cksave',
            fieldLabel: 'save',
            anchor: '100%',
            id: 'cksave'
        })
        ckorderByASC = new Ext.form.ComboBox({
            name: 'orderByASC',
            fieldLabel: 'orderBy-ASC',
            anchor: '100%',
            id: 'ckorderbyasc'
        })

        ckgetLlave = new Ext.form.Checkbox({
            name: 'ckkey',
            fieldLabel: 'getLlave',
            anchor: '100%',
            id: 'ckkey'
        })

        /*
         * Store local para el modo de ordenamiento
         */

        modes = new Ext.data.Store({
            // autoLoad: true,
            url: '../qgenerator/load_modes',
            reader: new Ext.data.JsonReader({
                root: 'data'
            }, ['id', 'mod'])
        })
        ckGenLimit = new Ext.form.Checkbox({
            name: 'ckGenLimit',
            anchor: '100%',
            id: 'ckGenLimit'

        })
        fsXlimite = new Ext.form.FieldSet({
            title: 'Por limite',
            checkboxToggle: true,
            collapsed: true,
            autoHeight: true,
            autoScroll: true,
            animCollapse: true,
            defaults: {
                anchor: '-20'
            },
            defaultType: 'numberfield',
            items: [{
                    fieldLabel: 'Limite',
                    width: 85,
                    id: 'tflimit'
                }, {
                    fieldLabel: 'Inicio',
                    width: 85,
                    id: 'tfstart'
                }
            ],
            bbar: [
                '->', ckGenLimit,
                new Ext.Toolbar.TextItem({text: '<b>Generar</b>'})
            ]
        })
        /*
         * Formulario de los metodos predeterminados
         */
        autogen = new Ext.FormPanel({
            height: 325,
            autoScroll: true,
            items: [getall, ckfind, ckfindAll, ckgetLlave, cksave,
                {
                    xtype: 'fieldset',
                    columnWidth: 0.3,
                    title: 'OrderBy',
                    checkboxToggle: true,
                    collapsed: true,
                    autoHeight: true,
                    autoScroll: true,
                    defaults: {
                        anchor: '-20'
                    },
                    defaultType: 'combo',
                    items: [{
                            fieldLabel: 'Campo',
                            editable: false,
                            store: stQFields,
                            displayField: 'name',
                            valueField: 'name',
                            name: 'campoOr',
                            mode: 'local',
                            width: 85,
                            labelwidth: 50,
                            triggerAction: 'all',
                        }, {
                            displayField: 'mod',
                            valueField: 'id',
                            store: modes,
                            fieldLabel: 'Modo',
                            width: 85,
                            name: 'mode',
                            mode: 'local',
                            triggerAction: 'all',
                            editable: false

                        }
                    ]
                }, {
                    xtype: 'fieldset',
                    columnWidth: 0.5,
                    title: 'findOneBy',
                    checkboxToggle: true,
                    collapsed: true,
                    autoHeight: true,
                    autoScroll: true,
                    defaults: {
                        anchor: '-20'
                    },
                    defaultType: 'combo',
                    items: [{
                            fieldLabel: 'Campos',
                            width: 85,
                            name: 'campof',
                            mode: 'local',
                            store: stQFields,
                            displayField: 'name',
                            valueField: 'name',
                            editable: false,
                            triggerAction: 'all'
                        }
                    ],
                    // buttons: [this.btnAnd]
                }, fsXlimite,
//                {
//                    xtype: 'fieldset',
//                    columnWidth: 0.5,
//                    title: 'Por limite',
//                    checkboxToggle: true,
//                    collapsed: true,
//                    autoHeight: true,
//                    autoScroll: true,
//                    //width: 150,
//                    defaults: {
//                        anchor: '-20'
//                    },
//                    defaultType: 'numberfield',
//                    items: [{
//                            fieldLabel: 'Limite',
//                            width: 85,
//                        }, {
//                            fieldLabel: 'Inicio',
//                            width: 85,
//                        }
//                    ]
//                }

            ],
            // buttons: [this.btnSavepe, this.btnCleanpe]
        });
        //////---------------------Combo que carga las clases a mapear
        stQGClasses = new Ext.data.Store({
            id: 'stClasses',
            url: '../qgenerator/load_classes',
            reader: new Ext.data.JsonReader({
                root: 'data'
            }, ['clas', 'table'])
        })


        cbClassesQ = new Ext.form.ComboBox({
            store: stQGClasses,
            fieldLabel: 'Clase:',
            displayField: 'clas',
            valueField: 'table',
            id: 'cbClassesQ',
            triggerAction: 'all',
            width: 206
        })
        stVisib = new Ext.data.Store({
            id: 'stVisib',
            url: '../qgenerator/load_visib',
            reader: new Ext.data.JsonReader({
                root: 'data'
            }, ['id', 'visib'])
        })

        cmbVisib = new Ext.form.ComboBox({
            store: stVisib,
            displayField: 'visib',
            valueField: 'id',
            id: 'cmbVisib',
            triggerAction: 'all',
            emptyText: 'Visibilidad...',
            width: 140,
            style: {
                color: '#000022'
            }

        })


        ////////--------------Panel del Codigo de Salida---------------///////////
        psur = new Ext.Panel({
            title: 'Código de Salida',
            collapsible: true,
            region: 'south',
            frame: true,
            id: 'psur',
            collapsed: true,
            resizable: true,
            height: 150,
            width: 890,
            disabled: true,
            items: [
                {
                    xtype: 'textarea',
                    readOnly: true,
                    width: '100%',
                    height: '100%',
                    id: 'textcoduot',
                    autoscroll: true
                }
            ]


        });
        btnQMap = new Ext.Button({
            text: 'Mapear'
        })
//// Inicio del arbol
        treeLoader = new Ext.tree.TreeLoader({
            dataUrl: '../qgenerator/load_tree'
        });
        var rootNode = new Ext.tree.AsyncTreeNode({
            text: 'Clases'
        });
        tree = new Ext.tree.TreePanel({
            loader: treeLoader,
            root: rootNode,
            selModel: new Ext.tree.MultiSelectionModel(),
            useArrows: true,
            autoScroll: true,
            animate: true,
            enableDD: true,
            containerScroll: true,
            border: false,
            id: tree
        });
        var contextMenu = new Ext.menu.Menu({
            items: [
                {
                    text: 'Mostrar', handler: function() {
                        alert(1)
                    }
                },
                {
                    text: 'Renombrar', handler: function() {
                        alert(1)
                    }
                },
                {
                    text: 'Guardar', handler: function() {
                        alert(2)
                    }
                },
            ]
        });
        tree.on('contextmenu', treeContextHandler);

        function sortHandler() {
            tree.getSelectionModel().getSelectedNode().sort(
                    function(leftNode, rightNode) {
                        return (leftNode.text.toUpperCase() < rightNode.text.
                                toUpperCase() ? 1 : -1);
                    }
            );
        }

        function treeContextHandler(node) {
            node.select();
            contextMenu.show(node.ui.getAnchor());
        }
        var editor = new Ext.tree.TreeEditor(tree);
        editor.on('beforecomplete', function(editor, newValue, originalValue)
        {
            alert(newValue + '-' + originalValue + '--' + editor.id)
        });
//// fin de arbol
///////-----Arbol 2 para administrar funciones----/////
        ;
        treemanageLoader = new Ext.tree.TreeLoader({
            url: '../qgenerator/load_treeFunctions',
            proxy: {
                type: 'ajax',
                actionMethods: { //Esta Linea es necesaria para el metodo de llamada POST o GET

                    read: 'POST'
                },
                reader: {
                    type: 'json'

                }
            }
        });
        rootmanageNode = new Ext.tree.AsyncTreeNode({
            text: 'Funciones',
            id: 0,
        });

        treemanage = new Ext.tree.TreePanel({
            loader: treemanageLoader,
            root: rootmanageNode,
            selModel: new Ext.tree.MultiSelectionModel(),
            useArrows: true,
            autoScroll: true,
            animate: true,
            enableDD: true,
            containerScroll: true,
            border: false,
            actionMethods: {//Esta Linea es necesaria para el metodo de llamada POST o GET
                read: 'POST'
            },
        });
        treemanage.selModel.on('selectionchange', function(selModel, node) {

        });

//        treemanage.on("expandnode", function(n) {
//           node=n.data.id;
//        });
        var contextmanageMenu = new Ext.menu.Menu({
            items: [
                {text: 'Editar', handler: function() {
                        alert(0)
                    }
                },
                {text: 'Copiar', disabled: false, handler: function() {
                        alert(0 + "Copy")
                    }
                },
                {text: 'Pegar', disabled: true, handler: function() {
                        alert(0 + "paste")
                    }
                },
                {text: 'Renombrar', handler: function() {
                        alert(1)
                    }
                },
                {text: 'Eliminar', handler: deleteHandler

                },
                {text: 'Ordenar', handler: sortHandler

                },
                {text: 'Buscar', handler: filterHandler

                },
            ]
        });
        treemanage.on('contextmenu', treemanageContextHandler);
        function treemanageContextHandler(node) {
            node.select();
            contextmanageMenu.show(node.ui.getAnchor());
        }
        function filterHandler() {
            var node = treemanage.getSelectionModel().getSelectedNode();
            filter.filter('find', 'text', node);
        }

        function deleteHandler() {
            treemanage.getSelectionModel().getSelectedNode().remove();
        }
        function sortHandler() {
            treemanage.getSelectionModel().getSelectedNode().sort(
                    function(leftNode, rightNode) {
                        return (leftNode.text.toUpperCase() < rightNode.text.
                                toUpperCase() ? 1 : -1);
                    }
            );
        }

//////----Fin del arbol para administrar funciones-----////
        ////////--------Panel del este---////////        
        peste = new Ext.Panel({
            title: 'Paleta de Acciones',
            region: 'east',
            split: true,
            titleCollapse: true,
            collapsible: true,
            autoScroll: true,
            disabled: false,
            layoutConfig: {
                animate: true
            },
            collapsed: true,
            collapseMode: 'mini',
            margins: '3 0 3 3',
            cmargins: '3 3 3 3',
            id: 'peste',
            frame: true,
            width: '20%',
//            bbar: [this.btnSave, this.btnClean],
            items: [{
                    xtype: 'fieldset',
                    id: 'fsreply',
                    title: '<b>Administrar Ficheros',
                    autoHeight: true,
                    checkboxToggle: true,
                    collapsed: true,
                    autoScroll: true,
                    items: [tree]

                }, {
                    xtype: 'fieldset',
                    id: 'fsmanage',
                    title: '<b>Administrar Funciones',
                    autoHeight: true,
                    checkboxToggle: true,
                    collapsed: true,
                    autoScroll: true,
                    items: [treemanage]

                }]

        });
        poeste = new Ext.Panel({
            title: 'Funciones Autogeneradas',
            collapsible: true,
            collapseMode: 'mini',
            region: 'west',
            id: 'poeste',
            frame: true,
            split: true,
            floatable: false,
            disabled: true,
            width: '20%',
            items: [autogen]
        });

        stQTable = new Ext.data.Store({
            url: 'load_Campos',
            reader: new Ext.data.JsonReader({
                root: 'data'
            }, ['table_name'])
        })

        txtQFilter = new Ext.form.TextField({
            enableKeyEvents: true,
            width: 70,
        })
        txtQWFilter = new Ext.form.TextField({
            enableKeyEvents: true,
            width: 70,
        })

        stQFields = new Ext.data.Store({
            url: '../qgenerator/load_fields',
            reader: new Ext.data.JsonReader({
                root: 'fields'
            }, ['name', 'length', 'sequence', 'pk', 'type'])
        })
        stQWFields = new Ext.data.Store({
            url: '../qgenerator/load_fields',
            reader: new Ext.data.JsonReader({
                root: 'fields'
            }, ['name', 'length', 'sequence', 'pk', 'type'])
        })
        stQFieldsV = new Ext.data.Store({
            url: '../qgenerator/load_fieldsv',
            reader: new Ext.data.JsonReader({
                root: 'fields'
            }, ['name'])
        })
        stQFieldsC = new Ext.data.Store({
            url: '../qgenerator/load_fieldsc',
            params: {
                table: tfversion.getValue()
            },
            reader: new Ext.data.JsonReader({
                root: 'fields'
            }, ['name'])
        })

        smQTables = new Ext.grid.CheckboxSelectionModel({
            width: 25,
            scope: this
        });
        smQWFields = new Ext.grid.CheckboxSelectionModel({
            width: 25,
            scope: this
        });
        pIoeste = new Ext.Panel({
            collapsible: true,
            region: 'west',
            id: 'pIoeste',
            disabled: true,
            frame: true,
            width: 200,
            items: [{
                    xtype: 'grid',
                    store: stQFields,
                    id: 'myQgryd',
                    height: 350,
                    width: 190,
                    sm: smQTables,
                    tbar: ['->', 'Filtro: ', txtQFilter],
                    columns: [
                        smQTables,
                        {id: 'Campo', header: 'Campo', width: 160, sortable: true, dataIndex: 'name'},
                    ],
                    loadMask: {
                        store: stQFields
                    }

                }]
        });
        //*temoporal a ver si incha

        stQGClassesJ = new Ext.data.Store({
            id: 'stClassesJ',
            url: '../qgenerator/load_classes',
            reader: new Ext.data.JsonReader({
                root: 'data'
            }, ['clas', 'table'])
        });
        stQJOIN = new Ext.data.Store({
            id: 'stQJOIN',
            url: '../qgenerator/load_relations',
            reader: new Ext.data.JsonReader({
                root: 'data'
            }, ['clas', 'rel'])
        });
        //Modificar este store
        stQFAgregadas = new Ext.data.Store({
            id: 'stQWHERE',
            url: '../qgenerator/load_operandos',
            reader: new Ext.data.JsonReader({
                root: 'data'
            }, ['id', 'op'])
        });
        operators = new Ext.data.Store({
            url: '../qgenerator/load_operandos',
            reader: new Ext.data.JsonReader({
                root: 'data'
            }, ['id', 'op'])
        });
        stQJoins = ['LEFTJOIN', 'RIGHTJOIN', 'INNERJOIN']
        cbClassesQJ = new Ext.form.ComboBox({
            store: stQGClassesJ,
            fieldLabel: 'Clase:',
            displayField: 'clas',
            valueField: 'table',
            id: 'cbClassesQJ',
            triggerAction: 'all',
            disabled: false,
            width: 100
        });
        smQJoinTables = new Ext.grid.CheckboxSelectionModel({
            width: 25,
            scope: this
        });
        smQFAgregadas = new Ext.grid.CheckboxSelectionModel({
            width: 25,
            scope: this
        });

        txtQJFilter = new Ext.form.TextField({
            enableKeyEvents: true,
            width: 100
        });
        txtQWFilter = new Ext.form.TextField({
            enableKeyEvents: true,
            width: 100
        });

        btnQJCancel = new Ext.Button({
            icon: '../../views/img/eliminar.png',
            iconCls: 'btn',
            disabled: true
        });

        btnQWCancel = new Ext.Button({
            icon: '../../views/img/eliminar.png',
            iconCls: 'btn',
            disabled: true
        });
        btnQWOR = new Ext.Button({
            icon: '../../views/img/adicionar.png',
            iconCls: 'btn',
            id: 'btnQWOr',
            tooltip: 'A&ntilde;adir Sentencia',
            disabled: true
        });
        btnQJAdd = new Ext.Button({
            icon: '../../views/img/adicionar.png',
            iconCls: 'btn',
            id: 'btnQJAdd',
            disabled: true
        });
        btnQWAO = new Ext.Button({
            icon: '../../views/img/adicionar.png',
            iconCls: 'btn',
            id: 'btnQWAnd',
            tooltip: 'A&ntilde;adir Sentencia:  <b>AND | OR</b>.',
            disabled: true
        });
        btnQWApp = new Ext.Button({
            icon: '../../views/img/aceptarblue.png',
            iconCls: 'btn',
            id: 'btnQJAdd',
            tooltip: 'Aplicar Sentencia',
            disabled: false
        });
        cmbQtipJoin = new Ext.form.ComboBox({
            store: stQJoins,
            id: 'cmbQtipJoin',
            triggerAction: 'all',
            editable: false,
            width: 80
        }),
        ////Variables de componentes para el Fieldset del WHERE
        /*
         * Store local para las funciones agregadas
         */
        funcionesagregadas = ['GROUPBY', 'AVG', 'COUNT', 'MAX', 'MIN', 'SUM', 'DISTINCT'];
        stQWFields = new Ext.data.Store({
            url: '../qgenerator/load_fields',
            reader: new Ext.data.JsonReader({
                root: 'fields'
            }, ['name', 'length', 'sequence', 'pk', 'type'])
        })
        cmbQCampo = new Ext.form.ComboBox({
            store: stQWFields,
            fieldLabel: 'Clase:',
            displayField: 'name',
            valueField: 'name',
            emptyText: 'Campo...',
            id: 'comboQCampo',
            width: 80,
            triggerAction: 'all',
            editable: false
        });

        //Variables de componentes para el Fieldset de Funciones Agregadas
        txtQFAFilter = new Ext.form.TextField({
            enableKeyEvents: true,
            width: 100
        });
        btnQFAWAO = new Ext.Button({
            icon: '../../views/img/adicionar.png',
            iconCls: 'btn',
            id: 'btnQFAWAnd',
            tooltip: 'A&ntilde;adir Sentencia:  <b>AND | OR</b>.',
            disabled: true
        });

        cmbQFAAndOr = new Ext.form.ComboBox({
            store: stAndOr,
            displayField: 'un',
            valueField: 'id',
            id: 'cmbQFAAndOr',
            triggerAction: 'all',
            width: 45,
            editable: false,
            disabled: true
        });

        btnQFAWApp = new Ext.Button({
            icon: '../../views/img/aceptarblue.png',
            iconCls: 'btn',
            id: 'btnQFAJAdd',
            tooltip: 'Aplicar Sentencia',
            disabled: false
        });

        cmbQFAValor = new Ext.form.ComboBox({
            typeAhead: true,
            id: 'comboQFAValor',
            maxLength: 20,
            width: 60,
            mode: 'local',
            triggerAction: 'all',
            emptyText: 'Valor..',
            editable: true

        });

        cmbQFAOperator = new Ext.form.ComboBox({
            store: operators,
            displayField: 'op',
            valueField: 'id',
            id: 'comboQFAOperator',
            triggerAction: 'all',
            width: 40,
            editable: false
        });

        cmbQFACampo = new Ext.form.ComboBox({
            id: 'comboQFACampo',
            width: 80,
            emptyText: 'Campo...',
            anchor: '90%',
            editable: false,
            autoScroll: false
        });


        btnQFACancel = new Ext.Button({
            icon: '../../views/img/eliminar.png',
            iconCls: 'btn',
            disabled: true
        });
        //Modificar este store
        stQFAgregadas = new Ext.data.Store({
            id: 'stQFAgregadas',
            url: '../qgenerator/load_operandos',
            reader: new Ext.data.JsonReader({
                root: 'data'
            }, ['id', 'op'])
        });
        smQFAgregadas = new Ext.grid.CheckboxSelectionModel({
            width: 25,
            scope: this
        });

        cmbQFAgregElegir = new Ext.form.ComboBox({
            id: 'cmbQFAelegir',
            store: funcionesagregadas,
            width: 80,
            allowBlank: false,
            emptyText: 'Funciones',
            anchor: '95%',
            editable: false
        });
        cmbQFagregCampos = new Ext.form.ComboBox({
            id: 'cmbQFAcampos',
            maxLength: 20,
            width: 70,
            emptyText: 'Campos',
            anchor: '100%',
            editable: false
        });
        tfQFagregAlias = new Ext.form.TextField({
            id: 'tfQFAalias',
            maxLength: 20,
            emptyText: 'Alias',
            width: 50,
            editable: false,
            anchor: '100%'
        });

        btnQFAadd = new Ext.Button({
            icon: '../../views/img/adicionar.png',
            iconCls: 'btn',
            id: 'btnQFAadd',
            tooltip: 'A&ntilde;adir Funci&oacute;n',
            disabled: true
        });
        btnQEdit = new Ext.Button({
            icon: '../../views/img/adicionar.png',
            iconCls: 'btn',
            id: 'btnQEdit',
            tooltip: 'A&ntilde;adir funcionalidades.',
            disabled: false
        });


        stAndOr = new Ext.data.Store({
            url: '../qgenerator/load_unions',
            reader: new Ext.data.JsonReader({
                root: 'data'
            }, ['id', 'un'])
        });
        cmbAndOr = new Ext.form.ComboBox({
            store: stAndOr,
            displayField: 'un',
            valueField: 'id',
            id: 'cmbAndOr',
            triggerAction: 'all',
            width: 45,
            editable: false,
            disabled: true
        });
        cmbQOperator = new Ext.form.ComboBox({
            store: operators,
            displayField: 'op',
            valueField: 'id',
            id: 'comboQOperator',
            triggerAction: 'all',
            width: 40,
            editable: false
        });
        cmbQValor = new Ext.form.ComboBox({
            displayField: 'name',
            valueField: 'name',
            store: stQFieldsV,
            typeAhead: true,
            id: 'comboQValor',
            maxLength: 20,
            width: 80,
            triggerAction: 'all',
            emptyText: 'Valor..',
            editable: true

        });
        btnQWGuardar = new Ext.Button({
            icon: '../../views/img/guardar.png',
            iconCls: 'btn',
            id: 'btnQWGuardar',
            tooltip: 'Guardar Consulta',
            disabled: true,
            text: '<b>Hecho</b>'
        });


////Fin del temporal

        pIcentral = new Ext.Panel({
            collapsible: true,
            frame: true,
            id: 'pIcentral',
            region: 'center',
            autoScroll: true,
            items: [{
                    xtype: 'fieldset',
                    id: 'fsjoin',
                    title: '<b>JOIN',
                    autoHeight: true,
                    checkboxToggle: true,
                    collapsed: true,
                    tbar: [
                        new Ext.Toolbar.TextItem({text: '<b> UNI&Oacute;N</>'}),
                        cmbQtipJoin,
                        {xtype: 'tbspacer'}, {xtype: 'tbspacer'}, {xtype: 'tbspacer'}, {xtype: 'tbspacer'},
                        new Ext.Toolbar.TextItem({text: '<b>Clase</b>'}),
                        cbClassesQJ,
                        {xtype: 'tbspacer'}, {xtype: 'tbspacer'}, {xtype: 'tbspacer'}, {xtype: 'tbspacer'},
                        btnQJAdd,
                    ],
                    items: {
                        xtype: 'grid',
                        store: stQJOIN,
                        id: 'gridQJoin',
                        height: 200,
                        width: 330,
                        sm: smQJoinTables,
                        tbar: [btnQJCancel, '->', 'Filtro: ', txtQJFilter],
                        columns: [
                            smQJoinTables,
                            {id: 'JOIN', header: 'JOIN', width: 100, sortable: true, dataIndex: 'tjoin'},
                            {id: 'Tabla', header: 'Tabla', width: 200, sortable: true, dataIndex: 'table'},
                        ],
                        loadMask: {
                            store: stQJOIN
                        },
                        bbar: [new Ext.PagingToolbar({
                                pageSize: 15,
                                id: 'ptwhere',
                                store: stAndOr,
                                displayInfo: true,
                                width: 330,
                                //displayMsg : 'Sin Resultados',
                                emptyMsg: 'Resultados'
                            })
                        ]
                    },
                }, {
                    xtype: 'fieldset',
                    id: 'fswhere',
                    title: '<b>WHERE',
                    autoHeight: true,
                    checkboxToggle: true,
                    collapsed: true,
                    /* tbar: [cmbQCampo,
                     {xtype: 'tbspacer'}, {xtype: 'tbspacer'}, {xtype: 'tbspacer'}, {xtype: 'tbspacer'}, {xtype: 'tbspacer'}, {xtype: 'tbspacer'},
                     cmbQOperator,
                     {xtype: 'tbspacer'}, {xtype: 'tbspacer'}, {xtype: 'tbspacer'}, {xtype: 'tbspacer'}, {xtype: 'tbspacer'}, {xtype: 'tbspacer'},
                     cmbQValor,
                     {xtype: 'tbspacer'}, {xtype: 'tbspacer'}, {xtype: 'tbspacer'}, {xtype: 'tbspacer'}, {xtype: 'tbspacer'}, {xtype: 'tbspacer'},
                     btnQWApp,
                     {xtype: 'tbspacer'}, {xtype: 'tbspacer'},
                     cmbAndOr,
                     btnQWAO,
                     //new Ext.Toolbar.TextItem({text: '<b>AND</>', }),
                     //  new Ext.Toolbar.TextItem({text: '<b>|</>'}),
                     // btnQWOR, new Ext.Toolbar.TextItem({text: '<b>OR</b>'})
                     ],*/
                    tbar: ['->', 'Filtro: ', txtQWFilter],
                    items: [
                        new Ext.grid.EditorGridPanel({
                            store: stQWFields,
                            id: 'gridQWhere',
                            height: 200,
                            width: 330,
                            sm: smQWFields,
                            frame: true,
                            clicksToEdit: 1,
                            columns: [
                                {id: 'wcamp', header: 'Campo', width: 88, sortable: true, dataIndex: 'name', editable: false},
                                {id: 'Operador', header: 'Operador', width: 60, sortable: true,
                                    editor: new Ext.form.ComboBox({
                                        displayField: 'op',
                                        valueField: 'op',
                                        id: 'comboQOperator',
                                        triggerAction: 'all',
                                        store: stQFAgregadas,
                                        emptyText: 'Operando...',
                                        listClass: 'x-combo-list-small',
                                        buttonAlign: 'center'
                                    }), dataIndex: 'op'},
                                {id: 'wecamp', header: 'Valor', width: 85, sortable: true,
                                    editor: new Ext.form.TextField({
                                        allowBlank: true,
                                    }), dataIndex: 'new'},
                                {id: 'wecamp', header: 'Tipo', width: 55, sortable: true,
                                    editor: new Ext.form.TextField({
                                        allowBlank: true,
                                    }), dataIndex: 'type'},
                                smQFAgregadas,
                            ],
                            loadMask: {
                                store: stQWFields
                            },
                            bbar: [new Ext.PagingToolbar({
                                    pageSize: 15,
                                    id: 'ptbaux',
                                    store: stQWFields,
                                    displayInfo: true,
                                    width: 330,
                                    displayMsg: 'Sin Resultados',
                                    emptyMsg: 'Resultados'
                                })
                            ]
                        })
                                /*{
                                 xtype: 'grid',
                                 store: stQWFields,
                                 id: 'gridQWhere',
                                 height: 200,
                                 width: 330,
                                 sm: smQTables,
                                 tbar: [btnQWCancel, '->', 'Filtro: ', txtQWFilter],
                                 columns: [
                                 smQFAgregadas,
                                 {id: 'wcamp', header: 'Campo', width: 120, sortable: true, dataIndex: 'name'},
                                 {id: 'Operador', header: 'Operador', width: 60, sortable: true,editor:cmbQFAOperator, dataIndex: 'op'},
                                 ],
                                 loadMask: {
                                 store: stQWFields
                                 }
                                 
                                 }*/
                                /*{
                                 xtype: 'grid',
                                 store: stQWFields,
                                 id: 'gridQWhere',
                                 height: 200,
                                 width: 330,
                                 sm: stQWFields,
                                 tbar: [btnQWCancel, '->', 'Filtro: ', txtQWFilter],
                                 columns: [
                                 smQFAgregadas,
                                 {id: 'gridQCampo', header: 'Campo', width: 80, sortable: true, dataIndex: 'name'},
                                 // {id: 'gridQOperat',header: "Operador",  dataIndex: 'op', width: 130, editor: cmbQOperator },
                                 // {id: 'gridQOperat', header: 'Operaci&oacute;n', width: 70, sortable: true, dataIndex: 'table'},
                                 {id: 'geridQValor', header: 'Tipo', width: 80, sortable: true, dataIndex: 'type'},
                                 {id: 'gridQAccion', header: 'Acci&oacute;n', width: 75, sortable: true, dataIndex: 'name'}
                                 ],
                                 loadMask: {
                                 store: stQWFields
                                 },
                                 
                                 },*/
                    ],
                }, {
                    xtype: 'fieldset',
                    id: 'fsagregate',
                    title: '<b>Funciones Agregadas',
                    autoHeight: true,
                    checkboxToggle: true,
                    collapsed: true,
                    tbar: [
                        cmbQFAgregElegir,
                        {xtype: 'tbspacer'}, {xtype: 'tbspacer'}, {xtype: 'tbspacer'}, {xtype: 'tbspacer'}, {xtype: 'tbspacer'}, {xtype: 'tbspacer'},
                        {xtype: 'tbspacer'}, {xtype: 'tbspacer'}, {xtype: 'tbspacer'}, {xtype: 'tbspacer'}, {xtype: 'tbspacer'}, {xtype: 'tbspacer'},
                        cmbQFagregCampos,
                        {xtype: 'tbspacer'}, {xtype: 'tbspacer'}, {xtype: 'tbspacer'}, {xtype: 'tbspacer'}, {xtype: 'tbspacer'}, {xtype: 'tbspacer'},
                        {xtype: 'tbspacer'}, {xtype: 'tbspacer'},
                        new Ext.Toolbar.TextItem({text: '<b>AS:</>'}),
                        {xtype: 'tbspacer'}, {xtype: 'tbspacer'}, {xtype: 'tbspacer'}, {xtype: 'tbspacer'}, {xtype: 'tbspacer'}, {xtype: 'tbspacer'},
                        {xtype: 'tbspacer'}, {xtype: 'tbspacer'}, {xtype: 'tbspacer'}, {xtype: 'tbspacer'}, {xtype: 'tbspacer'}, {xtype: 'tbspacer'},
                        btnQFAadd
                    ],
                    items: [{
                            xtype: 'panel',
                            id: 'gridQFAHaving',
                            tbar: [
                                cmbQFACampo,
                                {xtype: 'tbspacer'}, {xtype: 'tbspacer'}, {xtype: 'tbspacer'}, {xtype: 'tbspacer'}, {xtype: 'tbspacer'},
                                cmbQFAOperator,
                                {xtype: 'tbspacer'}, {xtype: 'tbspacer'}, {xtype: 'tbspacer'}, {xtype: 'tbspacer'}, {xtype: 'tbspacer'},
                                cmbQFAValor,
                                {xtype: 'tbspacer'}, {xtype: 'tbspacer'}, {xtype: 'tbspacer'}, {xtype: 'tbspacer'}, {xtype: 'tbspacer'},
                                btnQFAWApp,
                                {xtype: 'tbspacer'}, {xtype: 'tbspacer'},
                                cmbQFAAndOr,
                                {xtype: 'tbspacer'}, {xtype: 'tbspacer'}, {xtype: 'tbspacer'}, {xtype: 'tbspacer'},
                                btnQFAWAO]
                        }, {
                            xtype: 'grid',
                            store: stQFAgregadas,
                            id: 'gridQFAgregadas',
                            height: 180,
                            width: 320,
                            sm: smQFAgregadas,
                            tbar: [btnQFACancel, '->', 'Filtro: ', txtQFAFilter],
                            columns: [
                                smQFAgregadas,
                                {id: 'gridQCampo', header: 'Campo', width: 80, sortable: true, dataIndex: 'tjoin'},
                                {id: 'gridQOperat', header: 'Operaci&oacute;n', width: 70, sortable: true, dataIndex: 'table'},
                                {id: 'geridQValor', header: 'Valor', width: 80, sortable: true, dataIndex: 'table'},
                                {id: 'gridQAccion', header: 'Acci&oacute;n', width: 75, sortable: true, dataIndex: 'table'}
                            ],
                            loadMask: {
                                store: stQFAgregadas
                            },
                            bbar: [new Ext.PagingToolbar({
                                    pageSize: 15,
                                    id: 'ptbaux',
                                    store: stAndOr,
                                    displayInfo: true,
                                    width: 330,
                                    //displayMsg : 'Sin Resultados',
                                    emptyMsg: 'Resultados'
                                })
                            ]
                        }]
                }]
        })
        //****************************************************\\
        //*****************ESTE PANEL NO SE USA***************\\
        //*****************************************************\\
        pIeste = new Ext.Panel({
            collapsible: true,
            region: 'east',
            id: 'pIeste',
            frame: true,
            width: 220,
//            bbar: [btnSave, btnClean],
//            items: [tarpreview]
        });
        //****************************************************\\
        //********CODIGO DE SAILIDA DEL METODO DINAMICO*******\\
        //*****************************************************\\
        pIsur = new Ext.Panel({
            title: 'Código de Salida',
            collapsible: true,
            resizable: true,
            region: 'south',
            frame: true,
            id: 'pIsur',
            collapsed: true,
            height: 140,
            items: [
                {
                    xtype: 'textarea',
                    readOnly: true,
                    width: '100%',
                    height: '100%',
                    id: 'textacodI',
                    autoscroll: true
                }


            ]


        });
        //****************************************************\\
        //****STORE DE LAS ACCIONES PERMITIDAS [SELECT...]****\\
        //*****************************************************\\        
        stQActionsaa = new Ext.data.Store({
            url: '../qgenerator/load_actions',
            reader: new Ext.data.JsonReader({
                root: 'data'
            }, ['id', 'act'])
        })
        //****************************************************\\
        //***********COMBO QUE CARGA EL stQActionsaa**********\\
        //*****************************************************\\
        cmbQAction = new Ext.form.ComboBox({
            store: stQActionsaa,
            fieldLabel: 'FUNCION',
            displayField: 'act',
            valueField: 'id',
            id: 'cmbQActions',
            triggerAction: 'all',
            width: 100,
        })
        //****************************************************\\
        //***********COMBO QUE CARGA EL stQActionsaa**********\\
        //*****************************************************\\       
        tfQfunctName = new Ext.form.TextField({
            id: 'tfQfunctName',
            allowBlank: false,
            emptyText: 'Nombre de la funcion...',
            width: 180

        })

        btnQParam = new Ext.Button({
            text: '<b>PAR&Aacute;METROS</b>',
            icon: '../../views/img/adicionar.png',
            iconCls: 'btn',
            tooltip: 'A&ntilde;adir par&aacute;metro a la funcionalidad.',
        })
        ////////////---Panel del centro-----///        
        pcentral = new Ext.Panel({
            title: '.:: Generar consulta avanzada :::.',
            layout: 'border',
            frame: true,
            id: 'pcentral',
            region: 'center',
            width: 150,
            disabled: true,
            tbar: [cmbVisib,
                // new Ext.Toolbar.TextItem({text: '<b>NOMBRE:</b>'}),
                {xtype: 'tbspacer'}, {xtype: 'tbspacer'}, {xtype: 'tbspacer'}, {xtype: 'tbspacer'}, {xtype: 'tbspacer'}, {xtype: 'tbspacer'},
                tfQfunctName,
                {xtype: 'tbspacer'}, {xtype: 'tbspacer'}, {xtype: 'tbspacer'}, {xtype: 'tbspacer'}, {xtype: 'tbspacer'}, {xtype: 'tbspacer'},
                btnQParam,
                {xtype: 'tbspacer'}, {xtype: 'tbspacer'}, {xtype: 'tbspacer'}, {xtype: 'tbspacer'}, {xtype: 'tbspacer'}, {xtype: 'tbspacer'},
                new Ext.Toolbar.TextItem({text: '<b>ACCI&Oacute;N:</b>'}),
                cmbQAction
            ],
            items: [pIcentral, pIoeste],
            bbar: ['->', btnQWGuardar]

        });
        textQAlias = new Ext.form.TextField({
            id: 'textQAlias',
            readOnly: true,
            maxLength: 5,
            emptyText: 'm0_c',
            anchor: '80%',
            width: 50
        })
        this.items = [
            {
                xtype: 'tabpanel',
                activeTab: 0,
                scope: this,
                id: 'tpQ1',
                title: 'Relaciones',
                height: 262,
                items: [{
                        xtype: 'panel',
                        title: 'Generador de Consultas',
                        layout: 'border',
                        scope: this,
                        id: 'qpanelG',
                        frame: true,
                        tbar: [
                            new Ext.Toolbar.TextItem({text: '<b>Versión'}),
                            tfversion,
                            {xtype: 'tbspacer'}, {xtype: 'tbspacer'}, {xtype: 'tbspacer'}, {xtype: 'tbspacer'}, {xtype: 'tbspacer'}, {xtype: 'tbspacer'},
                            new Ext.Toolbar.TextItem({text: '<b>Namespace'}),
                            textnames,
                            {xtype: 'tbspacer'}, {xtype: 'tbspacer'}, {xtype: 'tbspacer'}, {xtype: 'tbspacer'}, {xtype: 'tbspacer'}, {xtype: 'tbspacer'},
                            new Ext.Toolbar.TextItem({text: '<b>Clase'}),
                            cbClassesQ,
                            {xtype: 'tbspacer'}, {xtype: 'tbspacer'}, {xtype: 'tbspacer'}, {xtype: 'tbspacer'}, {xtype: 'tbspacer'}, {xtype: 'tbspacer'},
                            new Ext.Toolbar.TextItem({text: '<b>Alias'}),
                            textQAlias,
                            {xtype: 'tbspacer'}, {xtype: 'tbspacer'}, {xtype: 'tbspacer'}, {xtype: 'tbspacer'}, {xtype: 'tbspacer'}, {xtype: 'tbspacer'},
                            btnQEdit
                        ],
                        items: [peste, pcentral, poeste, psur]
                    }, pIsur]
            }


        ];
        DoctrineGenerator.UI.winQGenerator.superclass.initComponent.call(this);
    }
});
