var perfil = window.parent.UCID.portal.perfil;
UCID.portal.cargarEtiquetas('gesthorarioxperiodo', function () {
    cargarInterfaz();
});
////------------ Inicializo el singlenton QuickTips ------------////
Ext.QuickTips.init();
var winAdd, winMod;
function cargarInterfaz() {
    ////------------ Botones ------------////
    var btnAdicionar = Ext.create('Ext.Button', {
        id: 'btnAdicionar',
        text: '<b>' + perfil.etiquetas.lbBtnAdicionar + '</b>',
        icon: perfil.dirImg + 'adicionar.png',
        iconCls: 'btn',
        hidden: true,
        handler: function () {
            showWin('add');
        }
    });

    var btnModificar = Ext.create('Ext.Button', {
        id: 'btnModificar',
        text: '<b>' + perfil.etiquetas.lbBtnModificar + '</b>',
        disabled: true,
        hidden: true,
        icon: perfil.dirImg + 'modificar.png',
        iconCls: 'btn',
        handler: function () {
            showWin('mod');
        }
    });

    var btnEliminar = Ext.create('Ext.Button', {
        id: 'btnEliminar',
        text: '<b>' + perfil.etiquetas.lbBtnEliminar + '</b>',
        disabled: true,
        hidden: true,
        icon: perfil.dirImg + 'eliminar.png',
        iconCls: 'btn',
        handler: function () {
            eliminarHorarioXPeriodo();
        }
    });

    var txtBuscar = new Ext.form.TextField({
        fieldLabel: 'Descripción',
        labelWidth: 80,
        anchor: '95%',
        id: 'txtBuscar',
        enableKeyEvents: true
    });

    txtBuscar.on('keyup', function (tf) {
        if (tf.getValue()) {
            stgridHorario.clearFilter();
            stgridHorario.filter('descripcion', tf.getValue());
        } else
            stgridHorario.clearFilter();
    }, this)

    var btnBuscar = Ext.create('Ext.Button', {
        id: 'btnBuscar',
        disabled: true,
        hidden: true,
        icon: perfil.dirImg + 'buscar.png',
        iconCls: 'btn'
    });

    var btnRecargar = new Ext.Button({
        disabled: false,
        icon: perfil.dirImg + 'actualizar.png',
        iconCls: 'btn',
        hidden: true,
        text: perfil.etiquetas.lbBtnRecargar,
        handler: function () {
            stPeriodo.load();
        }
    });
    var separador = new Ext.menu.Separator();

    UCID.portal.cargarAcciones(window.parent.idFuncionalidad);
//
    var stPeriodo = Ext.create('Ext.data.TreeStore', {
        model: 'cargarPeriodos',
        idProperty: 'idperiododocente',
        proxy: {
            type: 'ajax',
            url: 'cargarPeriodos',
            actionMethods: { //Esta Linea es necesaria para el metodo de llamada POST o GET

                read: 'POST'
            },
            reader: {
                type: 'json'
            }
        }
    });
////------------ Arbol Periodos ------------////
    var arbolPeriodos = new Ext.tree.TreePanel({
        title: perfil.etiquetas.lbRootNode,
        colapsible:true,
        autoScroll: true,
        region: 'west',
        store: stPeriodo,
        root: {
            expandable: true,
            text: perfil.etiquetas.lbRootNode,
            expanded: true,
            id: '0'
        },
        split: true,
        width: '25%',
        bbar: ['->', btnRecargar]
    });
    var idperiodo;
    var nperiodo;
////--------------- Evento para habilitar botones -------------////
    arbolPeriodos.on('itemclick', function (a, node, item, index, e, eOpts) {
        btnEliminar.disable();
        btnModificar.disable();
        btnAdicionar.enable();
        grid.enable();
        stgridHorario.removeAll();
        idperiodo = node.data.id;
        nperiodo = node.data.text;
        grid.setTitle('Horarios en el periodo: ' + nperiodo);
        stgridAddHorario.load();
        stgridHorario.load({params: {start: 0, limit: 20, idperiodo: node.data.id}});
        var idnode = node.data.id;
        if (idnode.length == 4) {
            btnAdicionar.disable();
            grid.setTitle('Horarios en el año: ' + nperiodo);
        }
    }, this);
    ////------------ Store del Grid de tipo de estudiantes ------------////
    var stgridHorario = new Ext.data.Store({
        fields: [
            {
                name: 'idhorario_periododocente'
            },
            {
                name: 'idperiododocente'
            },
            {
                name: 'idhorario'
            },
            {
                name: 'periodo'
            },
            {
                name: 'descripcion'
            },
            {
                name: 'horas'
            },
            {
                name: 'maximas_falta'
            },
            {
                name: 'estado'
            }
        ],
        remoteSort: true,
        pageSize: 25,
        proxy: {
            type: 'ajax',
            url: 'cargarHorariosXPeriodos',
            reader: {
                totalProperty: "cantidad",
                root: "datos"
            },
            actionMethods: {
                read: 'POST'
            }
        }
    });

    ////------------ Establesco modo de seleccion de grid (single) ------------////
    var sm = new Ext.selection.RowModel({
        mode: 'MULTI',
        listeners: {
            selectionchange: function (smodel, rowIndex, keepExisting, record) {
                btnModificar.setDisabled(smodel.getCount() == 0);
                btnEliminar.enable(smodel.getCount() > 0);
                if(idperiodo.length==4){
                    btnModificar.disable(true)
                    btnEliminar.disable(true)
                }
            }
        }
    });

    var grid = new Ext.grid.GridPanel({
        store: stgridHorario,
        frame: true,
        disabled: true,
        region: 'center',
        selModel: sm,
        columns: [
            {
                text: 'idhorario_periododocente',
                flex: 1,
                dataIndex: 'idhorario_periododocente',
                hidden: true
            },
            {
                text: 'idperiododocente',
                flex: 1,
                dataIndex: 'idperiododocente',
                hidden: true
            },
            {
                text: 'idhorario',
                flex: 1,
                dataIndex: 'idhorario',
                hidden: true
            },
            {
                text: 'Período',
                flex: 1,
                dataIndex: 'periodo'
            },
            {
                text: 'Descripción',
                flex: 1,
                dataIndex: 'descripcion'
            },
            {
                text: 'M&aacute;ximas Faltas',
                flex: 1,
                dataIndex: 'maximas_falta'
            }
        ],
        region: 'center',
        tbar: [btnAdicionar, btnModificar, btnEliminar, '->', txtBuscar, btnBuscar],
        bbar: new Ext.PagingToolbar({
            id: 'ptbaux55',
            store: stgridHorario,
            displayInfo: true,
            displayMsg: perfil.etiquetas.lbMsgbbarI,
            emptyMsg: perfil.etiquetas.lbMsgbbarII
        })
    });
    ////------------ Store del Grid de tipo de estudiantes ------------////
    var stgridAddHorario = new Ext.data.Store({
        fields: [
            {
                name: 'idhorario'
            },
            {
                name: 'descripcion'
            }
        ],
        remoteSort: true,
        pageSize: 25,
        proxy: {
            type: 'ajax',
            url: 'cargarHorarios',
            reader: {
                totalProperty: "cantidad",
                root: "datos"
            },
            actionMethods: {
                read: 'POST'
            }
        },listeners: {
            beforeload: function (store) {
                store.getProxy().extraParams = {
                    idperiodo: idperiodo
                }
            }
        }
    });
    ////------------ Establesco modo de seleccion de grid (single) ------------////
    var smwin = new Ext.selection.RowModel({
        mode: 'MULTI'
    });

    var gridAddHor = new Ext.grid.GridPanel({
        store: stgridAddHorario,
        frame: true,
        height: 330,
        selModel: smwin,
        columns: [
            {
                text: 'idhorario',
                flex: 1,
                dataIndex: 'idhorario_periododocente',
                hidden: true
            },
            {
                text: 'Horarios',
                flex: 1,
                dataIndex: 'descripcion'
            }
        ],
        region: 'center',
        tbar:['->',
            {
                xtype: 'searchfield',
                store: stgridAddHorario,
                //anchor: '100%',
                width: 300,
                labelAlign: 'left',
                fieldLabel: '<b>' + 'Buscar' + '</b>',
                labelWidth: 40,
                filterPropertysNames: ['descripcion']
            }],
        bbar: new Ext.PagingToolbar({
            id: 'ptbaux565',
            store: stgridAddHorario,
            displayInfo: true,
            displayMsg: perfil.etiquetas.lbMsgbbarI,
            emptyMsg: perfil.etiquetas.lbMsgbbarII
        })
    });
    var formHorXPer = new Ext.FormPanel({
        frame: true,
        height: '100%',
        bodyStyle: 'padding:5px auto 0px',
        fieldDefaults: {
            labelAlign: 'top',
            msgTarget: 'side'
        },
        items: [
            {
                layout: 'column',
                border: false,
                items: [
                    {
                        defaults: {width: '50%'},
                        columnWidth: .5,
                        border: false,
                        bodyStyle: 'padding:5px 0 5px 5px',
                        items: [
                            {
                                xtype: 'numberfield',
                                fieldLabel: 'Horas',
                                name: 'horas',
                                anchor: '95%',
                                minValue: 0,
                                labelAlign: 'top',
                                allowBlank: false
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
                    },
                    {
                        defaults: {width: '50%'},
                        columnWidth: .5,
                        border: false,
                        bodyStyle: 'padding:5px',
                        items: [
                            {
                                xtype: 'numberfield',
                                name: "maximas_falta",
                                labelAlign: 'top',
                                minValue: 0,
                                fieldLabel: 'Máximas faltas',
                                allowBlank: false,
                                anchor: '95%'
                            }
                        ]
                    }
                ]
            },
            gridAddHor
        ]
    });
    var panel = new Ext.Panel({
        //  title: 'gesthorarioxperiodo',
        id: 'pepe',
        layout: 'border',
        renderTo: 'panel',
        items: [grid, arbolPeriodos]

    });
    var vpGestSistema = Ext.create('Ext.Viewport', {layout: 'fit', items: panel});
    var btnEliminarHorPeriodo = Ext.create('Ext.Button', {
        id: 'btnEliminarHorPeriodo',
        text: '<b>' + perfil.etiquetas.lbBtnEliminar + '</b>',
        disabled: true,
        icon: perfil.dirImg + 'eliminar.png',
        iconCls: 'btn',
        handler: function () {
            EliminarLocal();
        }
    });

    function showWin(opcion) {
        switch (opcion) {
            case 'add':
            {
                if (!winAdd) {
                    winAdd = Ext.create('Ext.Window', {
                        title: perfil.etiquetas.lbTitVentanaTitI,
                        closeAction: 'hide',
                        width: 420,
                        height: 360,
                        constrain: true,
                        modal: true,
                        layout: 'fit',
                        buttons: [
                            {
                                text: 'Cancelar',
                                icon: perfil.dirImg + 'cancelar.png',
                                tabIndex: 13,
                                handler: function () {
                                    winAdd.hide();
                                }
                            },
                            {
                                text: 'Aplicar',
                                icon: perfil.dirImg + 'aplicar.png',
                                tabIndex: 12,
                                handler: function () {
                                    adicionarHorario('apl');
                                }
                            },
                            {
                                text: 'Adicionar',
                                icon: perfil.dirImg + 'aceptar.png',
                                tabIndex: 11,
                                handler: function () {
                                    adicionarHorario('aceptar');
                                    //   winAdd.hide();
                                }
                            }
                        ]
                    });
                }
                formHorXPer.getForm().reset();
                gridAddHor.setVisible(true);
                stgridAddHorario.load();
                gridAddHor.setHeight(210);
                winAdd.add(formHorXPer);
                winAdd.doLayout();
                winAdd.show();
            }
                break;
            case 'mod':
            {
                if (!winMod)
                    winMod = Ext.create('Ext.Window', {
                        title: perfil.etiquetas.lbTitVentanaTitII,
                        closeAction: 'hide',
                        width: 380,
                        height: 200,
                        constrain: true,
                        modal: true,
                        layout: 'fit',
                        buttons: [
                            {
                                text: 'Cancelar',
                                icon: perfil.dirImg + 'cancelar.png',
                                handler: function () {
                                    winMod.hide();
                                }
                            },
                            {
                                text: 'Aceptar',
                                icon: perfil.dirImg + 'aceptar.png',
                                handler: function () {
                                    modificarHorario();
                                    winMod.hide();
                                }
                            }
                        ]
                    });
                formHorXPer.getForm().reset();
                gridAddHor.setVisible(false);
                winMod.add(formHorXPer);
                winMod.doLayout();
                winMod.show();
                formHorXPer.getForm().loadRecord(sm.getLastSelected());
            }
                break;
        }
    }

    function adicionarHorario(apl) {
        var idhorarios = new Array();
        for (var i = 0; i < smwin.getCount(); i++) {
            idhorarios.push(smwin.getSelection()[i].raw.idhorario);
        }
        if (smwin.getCount() > 0) {
            if (formHorXPer.getForm().isValid()) {
                formHorXPer.getForm().submit({
                    url: 'insertarHorarioEnPeriodo',
                    params: {
                        idhorarios: Ext.JSON.encode(idhorarios),
                        idperiodo: idperiodo
                    },
                    waitMsg: perfil.etiquetas.lbMsgRegistrandoHorario,
                    failure: function (form, action) {
                        if (action.result.codMsg === 1) {
                            stgridHorario.load({params: {idperiodo: idperiodo}});
                            stgridAddHorario.load();
                            if (apl === "aceptar")
                                winAdd.hide();
                        }
                    }
                });
            }
        } else {
            mostrarMensaje(3, 'Seleccione un horario');
        }
    }

    function modificarHorario(apl) {
        if (formHorXPer.getForm().isValid()) {
            formHorXPer.getForm().submit({
                url: 'modificarHorarioEnPeriodo',
                params: {
                    idperiodo: idperiodo,
                    idhorario_periododocente: sm.getLastSelected().raw.idhorario_periododocente,
                    idhorario: sm.getLastSelected().raw.idhorario
                },
                waitMsg: perfil.etiquetas.lbMsgRegistrandoProfesor,
                failure: function (form, action) {
                    if (action.result.codMsg === 1) {
                        stgridHorario.load({params: {idperiodo: idperiodo}});
                        stgridAddHorario.load();
                    }
                }
            });
        } else {
            mostrarMensaje(3, 'Campos obligatorios en blanco.');
        }
    }

    function eliminarHorarioXPeriodo() {
        mostrarMensaje(2, perfil.etiquetas.lbMsgEliminarAsoc, elimina);

        function elimina(btnPresionado) {
            if (btnPresionado == 'ok') {
                var arrid = new Array();
                for (var i = 0; i < sm.getCount(); i++) {
                    arrid.push(sm.getSelection()[i].raw.idhorario_periododocente);
                }
                Ext.Ajax.request({
                    url: 'eliminarAsoc',
                    method: 'POST',
                    params: {
                        idhorarioxperiodo: Ext.JSON.encode(arrid)
                    },
                    callback: function (options, success, response) {
                        responseData = Ext.decode(response.responseText);
                        if (responseData.codMsg == 1) {
                            stgridHorario.load({params: {start: 0, limit: 20, idperiodo: idperiodo}});
                            sm.clearSelections();
                            btnEliminar.disable();
                        }
                    }
                });
            }
        }
    }

}
