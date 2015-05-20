Ext.define('GestPensums.controller.Pensums', {
    extend: 'Ext.app.Controller',

    views: [
        'GestPensums.view.pensum.PensumList',
        'GestPensums.view.pensum.PensumEdit',
        'GestPensums.view.pensum.PensumListToolBar',
        'GestPensums.view.pensum.PensumListPagingToolBar',
        'GestPensums.view.facultad.Combo',
        'GestPensums.view.carrera.Combo'
    ],
    stores: [
        'GestPensums.store.Pensums',
        'GestPensums.store.Carreras'
    ],
    models: [
        //'GestPensums.model.Pensum'
    ],

    refs: [
        {ref: 'list', selector: 'pensumlist'},
        {ref: 'tbar', selector: 'pensumlisttbar'},
        {ref: 'pensum_carrera_combo', selector: 'pensum_carrera_combo'}
    ],

    init: function () {
        var me = this;

        this.control({

            'pensumlist': {
                selectionchange: me.activarBotones
            },
            'pensum_facultad_combo': {
                select: function (combo) {
                    me.cargarCarreras(combo.getValue());
                }
            },
            'pensumlisttbar button[action=adicionar]': {
                click: me.adicionarPensum
            },
            'pensumlisttbar button[action=modificar]': {
                click: me.modificarPensum
            },
            'pensumlisttbar button[action=eliminar]': {
                click: me.eliminarPensum
            },
            'pensumedit button[action=aceptar]': {
                click: me.guardarPensum
            },
            'pensumedit button[action=aplicar]': {
                click: me.guardarPensum
            }
        });
    },

    cargarCarreras: function (idfacultad) {
        var me = this,
            pensum_carrera_combo = me.getPensum_carrera_combo();

        pensum_carrera_combo.reset();
        pensum_carrera_combo.getStore().load(
            {
                params: {idfacultad: idfacultad}
            }
        )
    },

    activarBotones: function (store, selected) {
        var me = this,
            tbar = me.getTbar();
        tbar.down('button[action=modificar]').setDisabled(selected.length !== 1);
        tbar.down('button[action=eliminar]').setDisabled(selected.length === 0);
    },

    adicionarPensum: function (button) {
        var view = Ext.widget('pensumedit');
        view.setTitle(perfil.etiquetas.lbTtlAdicionar);
    },

    modificarPensum: function (button) {
        var me = this,
            view = Ext.widget('pensumedit'),
            record = this.getList().getSelectionModel().getLastSelected();

        view.setTitle(perfil.etiquetas.lbTtlModificar);
        view.down('button[action=aplicar]').hide();

        me.cargarCarreras(record.get('idfacultad'));
        view.down('form').loadRecord(record);
    },

    eliminarPensum: function (button) {
        var me = this,
            record = me.getList().getSelectionModel().getSelection(),
            store = me.getList().getStore(),
            mensaje = perfil.etiquetas.lbMsgConfEliminarM;

        if (record.length == 1)
            mensaje = perfil.etiquetas.lbMsgConfEliminar;

        mostrarMensaje(
            2,
            mensaje,
            function (btn, text) {
                if (btn == 'ok') {
                    store.remove(record);
                    me.sincronizarStore(me.getList(), store);
                }
            }
        )
    },

    guardarPensum: function (button) {
        var win = button.up('window'),
            form = win.down('form'),
            me = this;

        if (form.getForm().isValid()) {
            var record = form.getRecord(),
                values = form.getValues();
            values.descripcion_carrera = form.down('#idPensumCarrerasCombo').getRawValue();

            //modificando
            if (record) {
                record.set(values);
            }
            //insertando
            else {
                //values.descripcion = form.down('#idPensumsCarrerasCombo').getRawValue();
                //values.idcarrera = form.down('#idPensumsCarrerasCombo').getValue();
                me.getList().getStore().add(values);
            }

            me.sincronizarStore(me.getList(), me.getList().getStore());

            if (button.action === 'aceptar')
                win.close();
            else if (button.action === 'aplicar')
                form.getForm().reset();
        }
    },

    sincronizarStore: function (grid, store) {
        store.sync({
            //scope: this,
            success: function (batch) {
                if (batch.operations[0].action == "create") {
                    //var idcarrera = Ext.decode(batch.operations[0].response.responseText).idcarrera;
                    //store.last().set('idcarrera', idcarrera);

                    grid.down('pensumlistpbar').moveLast();
                    //grid.down('enfasilisttbar').updateInfo();
                } else if (batch.operations[0].action == "update") {
                    var response = Ext.decode(batch.operations[0].response.responseText);
                    if (response.codMsg == 3)
                        grid.down('pensumlistpbar').doRefresh();
                } else if (batch.operations[0].action == "destroy") {
                    if (store.count() > 0)
                        grid.down('pensumlistpbar').doRefresh();//me.getList().down('enfasilisttbar').doRefresh();
                    else
                        grid.down('pensumlistpbar').movePrevious();
                }
            },
            failure: function () {
                grid.down('pensumlistpbar').doRefresh();
            }
        });
    }
});