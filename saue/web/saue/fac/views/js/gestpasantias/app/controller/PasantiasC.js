Ext.define('GestPasantias.controller.PasantiasC', {
    extend: 'Ext.app.Controller',

    views: [
        'GestPasantias.view.pasantia.Grid',
        'GestPasantias.view.pasantia.Edit',
        'GestPasantias.view.pasantia.ToolBar',
        'GestPasantias.view.pasantia.PagingToolBar',
        'GestEnfasis.view.facultad.Combo',
        'GestPasantias.view.carrera.Combo',
        'GestPasantias.view.enfasi.Combo',
        'GestPasantias.view.tipo_pasantia.Combo'

    ],
    stores: [
        'GestPasantias.store.Pasantias',
        'GestPasantias.store.TipoPasantias',
        'GestPasantias.store.Carreras',
        'GestPasantias.store.Enfasis'
    ],

    models: [
        'GestPasantias.model.Pasantia',
        'GestPasantias.model.TipoPasantia'
    ],

    refs: [
        {ref: 'list', selector: 'pasantia_grid'},
        {ref: 'tbar', selector: 'pasantia_toolbar'},
        //{ref: 'enfasis_facultad_combo', selector: 'enfasis_facultad_combo'},
        {ref: 'pasantias_carrera_combo', selector: 'pasantias_carrera_combo'},
        {ref: 'pasantias_enfasi_combo', selector: 'pasantias_enfasi_combo'},
        {ref: 'tipo_pasantias_combo', selector: 'tipo_pasantias_combo'}
    ],

    init: function () {
        var me = this;

        me.control({

            'pasantia_grid': {
                selectionchange: me.manejarBotones
            },
            'enfasis_facultad_combo': {
                select: function (combo) {
                    me.cargarCarreras(combo.getValue());
                }
            },
            'pasantias_carrera_combo': {
                select: function (combo) {
                    me.cargarEnfasis(combo.getValue());
                }
            },
            'pasantia_toolbar button[action=adicionar]': {
                click: me.adicionarPasantia
            },
            'pasantia_toolbar button[action=modificar]': {
                click: me.modificarPasantia
            },
            'pasantia_toolbar button[action=eliminar]': {
                click: me.eliminarPasantia
            },
            'pasantia_edit button[action=aceptar]': {
                click: me.guardarPasantia
            },
            'pasantia_edit button[action=aplicar]': {
                click: me.guardarPasantia
            }
        });
    },

    cargarCarreras: function (idfacultad) {
        var me = this,
            pasantias_enfasis_combo = me.getPasantias_enfasi_combo(),
            pasantias_carrera_combo = me.getPasantias_carrera_combo();

        pasantias_enfasis_combo.reset();
        pasantias_enfasis_combo.disable();

        pasantias_carrera_combo.reset();
        pasantias_carrera_combo.enable();
        pasantias_carrera_combo.getStore().load(
            {
                params: {idfacultad: idfacultad}
            }
        )
    },

    cargarEnfasis: function (idcarrera) {
        var me = this,
            pasantias_enfasi_combo = me.getPasantias_enfasi_combo();

        pasantias_enfasi_combo.reset();
        pasantias_enfasi_combo.enable();
        pasantias_enfasi_combo.getStore().load(
            {
                params: {idcarrera: idcarrera}
            }
        )
    },

    manejarBotones: function (store, selected) {
        var me = this,
            tbar = me.getTbar();
        tbar.down('button[action=modificar]').setDisabled(selected.length !== 1);
        tbar.down('button[action=eliminar]').setDisabled(selected.length === 0);
    },

    adicionarPasantia: function (button) {
        var view = Ext.widget('pasantia_edit');
        view.setTitle(perfil.etiquetas.lbTtlAdicionar);
    },

    modificarPasantia: function (button) {
        var view = Ext.widget('pasantia_edit'),
            record = this.getList().getSelectionModel().getLastSelected(),
            me = this,
            pasantias_enfasi_combo = me.getPasantias_enfasi_combo(),
            pasantias_carrera_combo = me.getPasantias_carrera_combo();

        view.setTitle(perfil.etiquetas.lbTtlModificar);
        view.down('button[action=aplicar]').hide();

        pasantias_enfasi_combo.enable();
        pasantias_carrera_combo.enable();

        me.cargarCarreras(record.get('idfacultad'));
        me.cargarEnfasis(record.get('idcarrera'));

        view.down('form').loadRecord(record);
    },

    eliminarPasantia: function (button) {
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

    guardarPasantia: function (button) {
        var win = button.up('window'),
            form = win.down('form'),
            me = this;

        if (form.getForm().isValid()) {
            var record = form.getRecord(),
                values = form.getValues();


            //modificando
            if (record) {
                values.tipo_pasantia_descripcion = me.getTipo_pasantias_combo().getRawValue();
                record.set(values);
            }
            //insertando
            else {
                me.getList().getStore().add(values);
            }

            if (button.action === 'aceptar')
                win.close();
            else if (button.action === 'aplicar')
                form.getForm().reset();

            me.sincronizarStore(me.getList(), me.getList().getStore());
        }
    },

    sincronizarStore: function (grid, store) {
        store.sync({
            //scope: this,
            success: function (batch) {
                if (batch.operations[0].action == "create") {
                    //var idcarrera = Ext.decode(batch.operations[0].response.responseText).idcarrera;
                    //store.last().set('idcarrera', idcarrera);

                    grid.down('pagingtoolbar').moveLast();
                    //grid.down('pasantialisttbar').updateInfo();
                } else if (batch.operations[0].action == "destroy") {
                    if (store.count() > 0)
                        grid.down('pagingtoolbar').doRefresh();//me.getList().down('pasantialisttbar').doRefresh();
                    else
                        grid.down('pagingtoolbar').movePrevious();
                }
            },
            failure: function () {
                grid.down('pagingtoolbar').doRefresh();
            }
        });
    }
});