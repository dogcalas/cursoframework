Ext.define('GestMaterias.controller.Materias', {
    extend: 'Ext.app.Controller',

    views: [
        'GestMaterias.view.materia.Grid',
        'GestMaterias.view.materia.Edit',
        'GestMaterias.view.materia.ToolBar',
        'GestMaterias.view.materia.PagingToolBar',
        'GestMaterias.view.tipo_materia.Combo',
        'GestMaterias.view.idioma.FieldSet',
        'GestMaterias.view.idioma.Combo',
        'GestMaterias.view.idioma.Nivel',
        'GestMaterias.view.area.Combo',
        'GestMaterias.view.credito.Grid',
        'GestMaterias.view.credito.Edit'
    ],
    stores: [
        'GestMaterias.store.Materias',
        'GestMaterias.store.Areas',
        'GestMaterias.store.Idiomas',
        'GestMaterias.store.TipoMaterias',
        'GestMaterias.store.Creditos',
        'GestMaterias.store.Carreras'
    ],
    models: [
        'GestMaterias.model.Materia',
        'GestMaterias.model.TipoMateria',
        'GestMaterias.model.Idioma'
    ],

    refs: [
        {ref: 'list', selector: 'materialist'},
        {ref: 'materia_idioma_combo', selector: 'materia_idioma_combo'},
        {ref: 'materia_idioma_nivel', selector: 'materia_idioma_nivel'},
        {ref: 'materia_idioma_fieldset', selector: 'materia_idioma_fieldset'},
        {ref: 'materialisttbar', selector: 'materialisttbar'},
        {ref: 'materia_edit', selector: 'materiaedit'}
    ],

    init: function () {
        var me = this;

        me.control({

            'materialist': {
                selectionchange: me.manejarBotones
            },
            'materialisttbar button[action=adicionar]': {
                click: me.adicionarMateria
            },
            'materialisttbar button[action=modificar]': {
                click: me.modificarMateria
            },
            'materialisttbar button[action=eliminar]': {
                click: me.eliminarMateria
            },
            'materialisttbar button[action=visualizarPA]': {
                click: me.visualizarProgramaAnalitico
            },
            'materialisttbar button[action=credito]': {
                click: me.asignarCreditos
            },
            'materialisttbar filefield': {
                change: me.subirProgramaAnalitico
            },
            'materiaedit button[action=aceptar]': {
                click: me.guardarMateria
            },
            'materiaedit button[action=aplicar]': {
                click: me.guardarMateria
            },
            'creditos_edit button[action=aceptar]': {
                click: me.guardarCredito
            },
            'creditos_edit button[action=aplicar]': {
                click: me.guardarCredito
            },
            'materia_idioma_fieldset checkbox': {
                change: me.activarIdioma
            },
            'materia_idioma_combo': {
                select: me.setearMinNivelValue
            }
        });

        me.getGestMateriasStoreCreditosStore().on(
            {
                beforeload: {fn: me.setearExtraParams, scope: this}
                //beforesync: {fn: me.setearIdMateria, scope: this}
            }
        );
    },

    setearExtraParams: function (store) {
        var me = this,
            extra_params = {idmateria: me.getList().getSelectionModel().getLastSelected().get('idmateria')};

        store.getProxy().extraParams = extra_params;
    },

    setearIdMateria: function (options) {
        var me = this,
            idmateria = me.getList().getSelectionModel().getLastSelected().get('idmateria');

        options.update[0].set('idmateria', idmateria);
    },

    manejarBotones: function (sm, selections) {
        var me = this,
            tbar = me.getMaterialisttbar();

        tbar.down('button[action=modificar]').setDisabled(selections.length !== 1);
        tbar.down('button[action=eliminar]').setDisabled(selections.length === 0);
        tbar.down('button[action=credito]').setDisabled(selections.length === 0);
        tbar.down('button[action=visualizarPA]').setDisabled(selections.length === 0);
        tbar.down('filefield').setDisabled(selections.length === 0);
    },

    setearMinNivelValue: function (combo, records) {
        var me = this;

        me.getMateria_idioma_nivel().setMaxValue(records[0].get('nivel'));
    },

    activarIdioma: function (checkbox) {
        var me = this,
            combo_idioma_edita = me.getMateria_edit().down('combo[name=ididiomam]'),
            combo_idioma = me.getMateria_idioma_fieldset().down('materia_idioma_combo'),
            combo_idioma_nivel = me.getMateria_idioma_fieldset().down('materia_idioma_nivel'),
            flag = checkbox.getValue();

        combo_idioma_edita.setDisabled(flag);
        combo_idioma.setDisabled(!flag);
        combo_idioma_nivel.setDisabled(!flag);

        if (flag) {
            combo_idioma_edita.reset();
        } else {
            combo_idioma.reset();
            combo_idioma_nivel.reset();
        }
    },

    adicionarMateria: function (button) {
        var ventana = Ext.widget('materiaedit');
        ventana.setTitle(perfil.etiquetas.lbTtlAdicionar);
    },

    modificarMateria: function (button) {
        var view = Ext.widget('materiaedit'),
            record = this.getList().getSelectionModel().getLastSelected();

        view.setTitle(perfil.etiquetas.lbTtlModificar);
        view.down('button[action=aplicar]').hide();

        view.down('form').loadRecord(record);
    },

    asignarCreditos: function (button) {
        var view = Ext.widget('creditos_edit');

        view.down('creditos_grid').getStore().removeAll();

        view.setTitle(perfil.etiquetas.lbTtlCreditos);
    },

    eliminarMateria: function (button) {
        var me = this,
            grid = me.getList(),
            record = grid.getSelectionModel().getSelection(),
            store = grid.getStore(),
            mensaje = perfil.etiquetas.lbMsgConfEliminarM;

        if (record.length == 1)
            mensaje = perfil.etiquetas.lbMsgConfEliminar + " '" + record[0].get('descripcion') + "'?";

        mostrarMensaje(
            2,
            mensaje,
            function (btn, text) {
                if (btn == 'ok') {
                    store.remove(record);
                    me.sincronizarStore(grid, store);
                }
            }
        )
    },

    guardarMateria: function (button) {
        var me = this,
            win = button.up('window'),
            form = win.down('form');

        if (form.getForm().isValid()) {
            var record = form.getRecord(),
                values = form.getValues();
            //para visualizar la descripción en el grid
            //values.descripcion_area = form.down('#idMateriasAreasCombo').getRawValue();

            //modificando
            if (record) {
                record.set('ididioma', null);
                record.set('ididiomam', null);
                record.set('nivel', null);
                record.set(values);
            }
            //insertando
            else {
                //values.idarea = form.down('materia_area_combo').getValue();
                values.ididioma = form.down('materia_idioma_combo').getValue();
                me.getList().getStore().add(values);
            }

            me.sincronizarStore(me.getList(), me.getList().getStore());

            if (button.action === 'aceptar')
                win.close();
            else if (button.action === 'aplicar')
                form.getForm().reset();
        }
    },

    guardarCredito: function (button) {
        var me = this,
            win = button.up('window'),
            store = me.getGestMateriasStoreCreditosStore(),//win.down('creditos_grid').getStore(),
            records = store.getModifiedRecords();

        if (records.length > 0) {
            var idmateria = me.getList().getSelectionModel().getLastSelected().get('idmateria');

            for (var i = 0; i < records.length; i++) {
                records[i].set('idmateria', idmateria);
            }

            store.sync({
                success: function () {
                    mostrarMensaje(1, perfil.etiquetas.lbMsgInfCreditosAsig);
                }
            });

            if (button.action === 'aceptar')
                win.close();
            /*else if (button.action === 'aplicar')
             form.getForm().reset();*/
        }
    },

    sincronizarStore: function (grid, store) {
        store.sync({
            //scope: this,
            success: function (batch) {
                if (batch.operations[0].action == "create") {
                    //var idcarrera = Ext.decode(batch.operations[0].response.responseText).idcarrera;
                    //store.last().set('idcarrera', idcarrera);

                    grid.down('materialistpbar').moveLast();
                    //grid.down('carreralistpbar').updateInfo();
                } else if (batch.operations[0].action == "update") {
                    grid.down('materialistpbar').doRefresh();
                }
                else if (batch.operations[0].action == "destroy") {
                    if (batch.total > 1)
                        mostrarMensaje(1, perfil.etiquetas.lbMsgInfEliminarM);
                    else
                        mostrarMensaje(1, perfil.etiquetas.lbMsgInfEliminar);
                    if (store.count() > 0)
                        grid.down('materialistpbar').doRefresh();//me.getList().down('carreralistpbar').doRefresh();
                    else
                        grid.down('materialistpbar').movePrevious();
                }
            },
            failure: function () {
                grid.down('materialistpbar').doRefresh();
            }
        });
    },

    subirProgramaAnalitico: function (fb, v) {
        var me = this,
            formulario = me.getMaterialisttbar().down('form').getForm(),
            codigo = me.getList().getSelectionModel().getLastSelected().get('codmateria');
        formulario.submit({
            url: 'subirPA',
            params: {nombre: codigo},
            waitMsg: perfil.etiquetas.lbMsgEsperaPA,
            success: function (fp, o) {
                mostrarMensaje(1, perfil.etiquetas.lbMsgInfPA);
            },
            failure: function () {
                mostrarMensaje(3, perfil.etiquetas.lbMsgInfPAErrExtension);
            }
        });
    },

    visualizarProgramaAnalitico: function (button) {
        var me = this,
            codigo = me.getList().getSelectionModel().getLastSelected().get('codmateria');

        Ext.Ajax.request({
            url: 'existePA',
            params: {nombre: codigo},
            callback: function (options, success, response) {
                var respuesta = Ext.decode(response.responseText);
                if (respuesta.flag) {
                    var win = new Ext.Window({
                        title: perfil.etiquetas.lbTtlPA + ' - ' + codigo + '.pdf',
                        layout: 'fit',
                        plain: true,
                        maximized: true,
                        items: {
                            xtype: 'component',
                            autoEl: {
                                tag: "iframe",
                                style: 'height: 100%; width: 100%; border: none',
                                src: "../../views/js/gestmaterias/app/files/" + codigo + '.pdf'
                            }
                        }
                    });
                    win.show();
                }
                else
                // Ext.Msg.alert('Error', "Esta materia aún no tiene un Programa analítico asociado");
                    mostrarMensaje(1, perfil.etiquetas.lbMsgInfPAErrCarga);
            }
        });


    }
});
