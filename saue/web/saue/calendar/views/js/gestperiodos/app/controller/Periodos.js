var idnodo = 0,
    idsistema = 0;
Ext.define('GestPeriodos.controller.Periodos', {
    extend: 'Ext.app.Controller',

    views: [
        'GestPeriodos.view.periodos.PeriodosList',
        'GestPeriodos.view.periodos.PeriodosEdit',
        'GestPeriodos.view.periodos.PeriodoActivoEdit',
        'GestPeriodos.view.periodos.PeriodosListToolBar',
        'GestPeriodos.view.periodos.PeriodoTree',
        'GestPeriodos.view.periodos.Activ_ActivasList',
        'GestPeriodos.view.periodos.Activ_Activas',
        'GestPeriodos.view.periodos.Activ_ActivasEdit',
        'GestPeriodos.view.accion.AccionList',
        'GestPeriodos.view.funcionalidad.FuncionalidadList',
        'GestPeriodos.view.tipoperiodo.Combo',
        'GestPeriodos.view.ubicaciones.Combo',
        'GestPeriodos.view.anio.Combo',
        'GestPeriodos.view.roles.Combo'
    ],
    stores: [
        'GestPeriodos.store.Periodos',
        'GestPeriodos.store.TipoPeriodos',
        'GestPeriodos.store.Ubicaciones',
        'GestPeriodos.store.Anio',
        'GestPeriodos.store.Accion',
        'GestPeriodos.store.Funcionalidad',
        'GestPeriodos.store.Roles',
        'GestPeriodos.store.PeriodoTree',
        'GestPeriodos.store.Activ_Activas'
    ],
    models: [
        'GestPeriodos.model.Periodos',
        'GestPeriodos.model.TipoPeriodos',
        'GestPeriodos.model.Ubicaciones',
        'GestPeriodos.model.Anio',
        'GestPeriodos.model.Funcionalidad',
        'GestPeriodos.model.PeriodoTree',
        'GestPeriodos.model.Activ_Activas'
    ],
    refs: [
        {ref: 'list', selector: 'periodolist'},
        {ref: 'periodo_edit', selector: 'periodoedit'},
        {ref: 'periodolisttbar', selector: 'periodolisttbar'},
        {ref: 'periodoactivoedit', selector: 'periodoactivoedit'},
        {ref: 'funcionalidadlist', selector: 'funcionalidadlist'},
        {ref: 'accionlist', selector: 'accionlist'},
        {ref: 'periodotree', selector: 'periodotree'},
        {ref: 'accionstore', selector: 'accionstore'},
        {ref: 'activ_activaslist', selector: 'activ_activaslist'},
        {ref: 'activ_activas', selector: 'activ_activas'},
        {ref: 'periodo_roles_combo', selector: 'periodo_roles_combo'}
    ],

    init: function () {
        var me = this,
            rol = 0;
        this.control({

            'periodolist': {
                render: this.crearSearhField,
                selectionchange: me.activarBotones
            },
            'activ_activaslist': {
                selectionchange: me.activarBotonesActividades,
                afterrender: me.saveChangesEvent
            },
            'periodolisttbar button[action=adicionar]': {
                click: me.adicionarPeriodo
            },
            'periodolisttbar button[action=modificar]': {
                click: me.modificarPeriodo
            },
            'periodolisttbar button[action=eliminar]': {
                click: me.eliminarPeriodo
            },
            'periodolisttbar button[action=activar]': {
                click: me.activarPeriodo
            },
            'periodoedit button[action=aceptar]': {
                click: me.guardarPeriodo
            },
            'periodoedit button[action=aplicar]': {
                click: me.guardarPeriodo
            },
            'periodoedit combo[id=idPeriodoTipoPeriodoCombo]': {
                select: function (combo) {
                    Ext.getCmp('fecha_ini1').enable();
                    Ext.getCmp('fecha_fin1').enable();
                }
            },
            'periodoactivoedit combo[id=idPeriodoRolCombo]': {
                change: function (combo) {
                    var periodo = me.getList().getSelectionModel().getSelection()[0],
                        activa = me.getActiv_activaslist().getSelectionModel().getSelection()[0];

                    if (combo.getValue()) {
                        Ext.getCmp('fecha_ini').enable();
                        Ext.getCmp('fecha_fin').enable();
                        if (activa) {
                            if (!activa.data.fecha_ini) {
                                Ext.getCmp('fecha_ini').setValue(periodo.data.fecha_ini);
                            } else {
                                Ext.getCmp('fecha_ini').setValue(activa.data.fecha_ini);
                            }
                            if (!activa.data.fecha_fin) {
                                Ext.getCmp('fecha_fin').setValue(periodo.data.fecha_fin);
                            } else {
                                Ext.getCmp('fecha_fin').setValue(activa.data.fecha_fin);
                            }
                        } else {
                            Ext.getCmp('fecha_ini').setValue(periodo.data.fecha_ini);
                            Ext.getCmp('fecha_fin').setValue(periodo.data.fecha_fin);
                        }

                        me.getAccionlist().getStore().load({
                            params: {
                                idfuncionalidad: idnodo,
                                idrol: combo.getValue(),
                                idsistema: idsistema,
                                idperiodo: periodo.data.idperiododocente
                            }
                        });
                        me.getAccionlist().enable();
                    }
                }
            },
            'periodoactivoedit button[action=aceptar]': {
                click: me.activarActividades
            },
            'periodoactivoedit button[action=cancelar]': {
                click: me.cancelarActividades
            },
            'activ_activaslist button[action=adicionar]': {
                click: me.guardarActividades
            },
            'activ_activaslist button[action=eliminar]': {
                click: me.eliminarActividades
            },
            'accionlist': {
                itemclick: function (grid, record, item, index) {

                }
            },
            'periodotree': {
                beforeload: function (atreeloader, anode) {
                    anode.params.id = anode.node.data.id;
                    anode.params.idsistema = anode.node.data.idsistema;
                },
                itemclick: function (anode, item, index, e) {
                    if (item.raw.leaf) {
                        idnodo = item.raw.idfuncionalidad;
                        idsistema = item.raw.idsistema;

                        this.getActiv_activaslist().getStore().getProxy().extraParams = {
                            idperiodo: this.getList().getSelectionModel().getLastSelected().raw.idperiododocente,
                            idfuncionalidad: item.raw.idfuncionalidad
                        };
                        this.getActiv_activaslist().getStore().load();
                        this.getActiv_activaslist().enable();
                    }
                }
            }
        });
    },
    guardarActividades: function (button) {
        var view = Ext.widget('periodoactivoedit');
        view.setTitle('Configurar actividades en período');

        this.getActiv_activaslist().getSelectionModel().clearSelections();
        this.getAccionlist().getStore().load({
            params: {
                idfuncionalidad: 0,
                idrol: 0,
                idsistema: 0,
                idperiodo: 0
            }
        });
        this.getAccionlist().disable();
    },
    activarBotones: function (store, selected) {
        var me = this,
            tbar = me.getPeriodolisttbar();

        if (selected.length == 1) {
            tbar.down('button[action=modificar]').enable();
            tbar.down('button[action=eliminar]').enable();
            tbar.down('button[action=activar]').enable();

        } else if (selected.length > 1) {
            tbar.down('button[action=modificar]').disable();
            tbar.down('button[action=activar]').disable();
            tbar.down('button[action=eliminar]').enable();
        } else {
            tbar.down('button[action=modificar]').disable();
            tbar.down('button[action=eliminar]').disable();
            tbar.down('button[action=activar]').disable();
        }
    },
    activarBotonesActividades: function (store, selected) {
        var me = this,
            tbar = me.getActiv_activaslist();

        if (selected.length == 1) {
            tbar.down('button[action=eliminar]').enable();

        } else if (selected.length > 1) {
            tbar.down('button[action=eliminar]').enable();
        } else {
            tbar.down('button[action=eliminar]').disable();
        }
    },

    crearSearhField: function (grid) {
        grid.down('periodolisttbar').add([
            '->',
            {
                xtype: 'searchfield',
                store: grid.getStore(),
                width: 400,
                fieldLabel: '<b>' + perfil.etiquetas.lbBtnBuscar + '</b>',
                labelWidth: 40,
                filterPropertysNames: ['codigo', 'descripcion', 'tipoperiodo']
            }
        ])
    },

    adicionarPeriodo: function (button) {
        var view = Ext.widget('periodoedit');
        view.setTitle(perfil.etiquetas.lbTitVentanaTitI);
    },

    activarPeriodo: function (button) {
        var view = Ext.widget('activ_activas'),
            me = this,
            record = me.getList().getSelectionModel().getLastSelected();
        me.getPeriodotree().getStore().load();
        me.getActiv_activaslist().getStore().getProxy().extraParams = {
            idperiodo: 0,
            idfuncionalidad: 0
        };
        me.getActiv_activaslist().getStore().load();
        me.getActiv_activaslist().disable();
        view.setTitle('Activar actividades: << Código: ' + record.raw.codperiodo + ' -- Período: ' + record.raw.descripcion + ' >>');
    },

    cargarAcciones: function (idfuncionalidad, rol, idsistema, idperiodo) {
        var me = this;
        me.getAccionlist().getStore().load({
            params: {
                idfuncionalidad: idfuncionalidad,
                idrol: rol,
                idsistema: idsistema,
                idperiodo: idperiodo
            }
        });
    },

    modificarPeriodo: function (button) {
        var view = Ext.widget('periodoedit'),
            record = this.getList().getSelectionModel().getLastSelected(),
            combo_ubicacion = view.down('combo[name=idcampus]');

        view.setTitle(perfil.etiquetas.lbTitVentanaTitII);
        view.down('button[action=aplicar]').hide();

        view.down('form').loadRecord(record);

        if (!record.raw.idcampus) {
            var pos = combo_ubicacion.getStore().findExact('abrev', record.raw.abrev);
            if (pos >= 0) {
                combo_ubicacion.select(combo_ubicacion.getStore().getAt(pos).raw.idcampus);
            } else {
                combo_ubicacion.setValue(record.raw.abrev);
                combo_ubicacion.disable();
            }
        }

        Ext.getCmp('fecha_ini1').enable();
        Ext.getCmp('fecha_fin1').enable();
    },

    eliminarPeriodo: function (button) {
        var store = this.getList().getStore(),
            selModel = this.getList().getSelectionModel();

        var ids = new Array();
        var per = new Array();
        for (var i = 0; i < selModel.getCount(); i++) {
            ids.push(selModel.getSelection()[i].data.idperiododocente);
            per.push(selModel.getSelection()[i]);
        }

        mostrarMensaje(
            2,
            perfil.etiquetas.lbMsgConfEliminar,
            function (btn, text) {
                if (btn == 'ok') {
                    for (var j = 0; j < per.length; j++) {
                        store.remove(per[j]);
                    }
                    store.sync();
                    store.load();
                }
            }
        )
    },

    guardarPeriodo: function (button, combo) {
        var win = button.up('window'),
            form = win.down('form'),
            me = this;

        if (form.getForm().isValid()) {
            var record = form.getRecord(),
                values = form.getValues();

            var abrev = Ext.getCmp('idPeriodoUbicacionCombo').getRawValue(),
                fecha_ini = Ext.getCmp('fecha_ini1').getRawValue(),
                fecha_fin = Ext.getCmp('fecha_fin1').getRawValue();
            me.getList().getStore().getProxy().extraParams = {abrev: abrev, fecha_ini: fecha_ini, fecha_fin: fecha_fin};

            //modificando
            if (record) {
                record.set(values);
            }
            //insertando
            else {
                me.getGestPeriodosStorePeriodosStore().add(values);
            }

            me.getList().getStore().sync();
            me.getList().getStore().load();

            if (button.action === 'aceptar') {
                win.close();
            }
            else
                form.getForm().reset();
        }
    },

    sincronizarStore: function (grid, store) {
        store.sync({
            //scope: this,
            success: function (batch) {
                if (batch.operations[0].action == "create") {

                    grid.down('periodolisttbar').moveLast();
                } else if (batch.operations[0].action == "destroy") {
                    if (store.count() > 0)
                        grid.down('periodolisttbar').doRefresh();
                    else
                        grid.down('periodolisttbar').movePrevious();
                }
            },
            failure: function () {
                grid.down('periodolisttbar').doRefresh();
            }
        });
    },

    activarActividades: function (button) {
        var win = button.up('window'),
            fecha_ini = Ext.getCmp('fecha_ini').getRawValue(),
            fecha_fin = Ext.getCmp('fecha_fin').getRawValue(),
            me = this;

        if (fecha_ini != "" && fecha_fin != "") {
            var idrol = Ext.getCmp('idPeriodoRolCombo').getValue(),
                idperiododocente = me.getList().getSelectionModel().getSelection()[0].data.idperiododocente,
                acciones = me.getAccionlist().getSelectionModel().getSelection();

            var delMask = new Ext.LoadMask(Ext.getBody(), {
                msg: 'Configurando actividades...'
            });

            if (acciones.length > 0) {
                var ids = new Array();
                for (var i = 0; i < acciones.length; i++) {
                    ids.push(acciones[i].raw.m__idaccion);
                }
                delMask.show();
                Ext.Ajax.request({
                    url: 'restAcceso',
                    method: 'POST',
                    params: {
                        idperiododocente: idperiododocente,
                        idrol: idrol,
                        fecha_ini: fecha_ini,
                        fecha_fin: fecha_fin,
                        acciones: Ext.JSON.encode(ids),
                        idfuncionalidad: idnodo,
                        idsistema: idsistema
                    },
                    callback: function (options, success, response) {
                        responseData = Ext.decode(response.responseText);
                        delMask.disable();
                        delMask.hide();
                        if (responseData.codMsg === 1) {
                            win.close();
                            me.getActiv_activaslist().getStore().load({
                                params: {
                                    idperiodo: idperiododocente,
                                    idfuncionalidad: idnodo
                                }
                            });
                        }
                    }
                });

            } else {
                mostrarMensaje(3, perfil.etiquetas.lbMsgErrorNoAcciones);
            }
        }
        else mostrarMensaje(3, perfil.etiquetas.lbMsgErrorEnCamops);
    },

    saveChangesEvent: function (grid, eOp) {
        grid.getPlugin('cellplugin').on('edit', function (editor, e) {
            console.log(e);
            if (e.originalValue != e.value) {
                if (e.field == 'fecha_ini') {
                    grid.editingPlugin.context.record.data.fecha_ini = e.value
                }
                else if (e.field == 'fecha_fin') {
                    grid.editingPlugin.context.record.data.fecha_fin = e.value
                }
                grid.getStore().sync();
                grid.getStore().load();
            }
        });
    },

    eliminarActividades: function (button) {
        var store = this.getActiv_activaslist().getStore(),
            selModel = this.getActiv_activaslist().getSelectionModel();

        var activ = new Array();
        for (var i = 0; i < selModel.getCount(); i++) {
            activ.push(selModel.getSelection()[i]);
        }

        mostrarMensaje(
            2,
            perfil.etiquetas.lbMsgConfActEliminar,
            function (btn, text) {
                if (btn == 'ok') {
                    for (var j = 0; j < activ.length; j++) {
                        store.remove(activ[j]);
                    }
                    store.sync();
                    store.load();
                }
            }
        );
    },

    cancelarActividades: function (button) {
        var win = button.up('window');
        this.getActiv_activaslist().getSelectionModel().deselectAll();
        win.close();
    }
});