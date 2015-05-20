Ext.define('CredxArea.controller.CredsxAreaC', {
    extend: 'Ext.app.Controller',

    views: [
        'CredxArea.view.credxarea.Grid',
        'CredxArea.view.credxarea.Toolbar',
        'CredxArea.view.credxarea.Edit',
        'CredxArea.view.area.Combo',
        'CredxArea.view.area_general.Combo',
        'CredxArea.view.enfasi.Combo',
        'CredxArea.view.pensum.Combo',
        'CredxArea.view.carrera.Combo',
        'GestEnfasis.view.facultad.Combo'
    ],
    stores: [
        'CredxArea.store.CredsxArea',
        'CredxArea.store.Areas',
        'CredxArea.store.AreasGenerales',
        'CredxArea.store.Enfasis',
        'CredxArea.store.Pensums',
        'CredxArea.store.Carreras'
    ],
    models: [],

    refs: [
        {ref: 'credxarea_grid', selector: 'credxarea_grid'},
        {ref: 'credxarea_toolbar', selector: 'credxarea_toolbar'},
        {ref: 'credxarea_carrera_combo', selector: 'credxarea_carrera_combo'},
        {ref: 'credxarea_enfasi_combo', selector: 'credxarea_enfasi_combo'},
        {ref: 'credxarea_pensum_combo', selector: 'credxarea_pensum_combo'},
        {ref: 'credxarea_area_combo', selector: 'credxarea_area_combo'}
    ],

    init: function () {
        var me = this;

        me.control({

            'credxarea_grid': {
                selectionchange: me.manejarBotones
            },
            'credxarea_toolbar button[action=adicionar]': {
                click: me.adicionarCredsxArea
            },
            'credxarea_toolbar button[action=modificar]': {
                click: me.modificarCredsxArea
            },
            'credxarea_toolbar button[action=eliminar]': {
                click: me.eliminarCredsxArea
            },
            'credxarea_edit button[action=aceptar]': {
                click: me.guardarCredsxArea
            },
            'credxarea_edit button[action=aplicar]': {
                click: me.guardarCredsxArea
            },
            'enfasis_facultad_combo': {
                select: function (combo) {
                    me.cargarCarreras(combo.getValue())
                }
            },
            'credxarea_carrera_combo': {
                select: function (combo) {
                    me.cargarEnfasisPensum(combo.getValue())
                }
            },
            'credxarea_area_general_combo': {
                select: function (combo) {
                    me.cargarAreas(combo.getValue())
                }
            }
        });
    },

    cargarCarreras: function (idfacultad) {
        var me = this,
            credxarea_carrera_combo = me.getCredxarea_carrera_combo();

        credxarea_carrera_combo.reset();
        credxarea_carrera_combo.getStore().load(
            {
                params: {idfacultad: idfacultad}
            }
        );
        credxarea_carrera_combo.enable();
    },

    cargarEnfasisPensum: function (idcarrera) {
        var me = this,
            credxarea_pensum_combo = me.getCredxarea_pensum_combo(),
            credxarea_enfasi_combo = me.getCredxarea_enfasi_combo();

        credxarea_enfasi_combo.reset();
        credxarea_enfasi_combo.getStore().load(
            {
                params: {idcarrera: idcarrera}
            }
        );
        credxarea_enfasi_combo.enable();

        credxarea_pensum_combo.reset();
        credxarea_pensum_combo.getStore().load(
            {
                params: {idcarrera: idcarrera}
            }
        );
        credxarea_pensum_combo.enable();
    },

    cargarAreas: function (idareag) {
        var me = this,
            credxarea_area_combo = me.getCredxarea_area_combo();

        credxarea_area_combo.reset();
        credxarea_area_combo.getStore().load(
            {
                params: {idareageneral: idareag}
            }
        );
        credxarea_area_combo.enable();
    },

    /*recargarGrid: function (combo) {
     var me = this,
     store = me.getCredxarea_grid().getStore();

     me.setearExtraParams(store)
     store.reload();
     },

     setearExtraParams: function (store) {
     var me = this,
     extra_params = me.obtenerExtraParams();

     store.getProxy().extraParams = extra_params
     },

     obtenerExtraParams: function () {
     var me = this,
     facultades_combo = me.getCredxarea_toolbar().down('carrera_facultad_combo'),
     extra_params = {};

     //Obteniendo valor del combo de facultades en caso de que halla cambiado
     if (facultades_combo.isDirty())
     extra_params.idfacultad = facultades_combo.getValue();

     return extra_params
     },*/

    manejarBotones: function (store, selected) {
        var me = this,
            tbar = me.getCredxarea_toolbar();
        tbar.down('button[action=modificar]').setDisabled(selected.length !== 1);
        tbar.down('button[action=eliminar]').setDisabled(selected.length === 0);
    },

    adicionarCredsxArea: function (button) {
        var view = Ext.widget('credxarea_edit');
        view.setTitle(perfil.etiquetas.lbTtlAdicionar);
    },

    modificarCredsxArea: function (button) {
        var view = Ext.widget('credxarea_edit'),
            me = this,
            record = this.getCredxarea_grid().getSelectionModel().getLastSelected();

        view.setTitle(perfil.etiquetas.lbTtlModificar);
        view.down('button[action=aplicar]').hide();

        me.cargarCarreras(record.get('idfacultad'));
        me.cargarEnfasisPensum(record.get('idcarrera'));
        me.cargarAreas(record.get('idareageneral'));

        view.down('form').loadRecord(record);
    },

    eliminarCredsxArea: function (button) {
        var me = this,
            record = me.getCredxarea_grid().getSelectionModel().getSelection(),
            store = me.getCredxarea_grid().getStore(),
            mensaje = perfil.etiquetas.lbMsgConfEliminarM;

        if (record.length == 1)
            mensaje = perfil.etiquetas.lbMsgConfEliminar;

        mostrarMensaje(
            2,
            mensaje,
            function (btn, text) {
                if (btn == 'ok') {
                    store.remove(record);
                    me.sincronizarStore(me.getCredxarea_grid(), store);
                }
            }
        )
    },

    guardarCredsxArea: function (button) {
        var win = button.up('window'),
            form = win.down('form'),
            me = this;

        if (form.getForm().isValid()) {
            var record = form.getRecord(),
                values = form.getValues();

            //modificando
            if (record) {
                values.descripcion = form.down('credxarea_area_combo').getRawValue();
                record.set(values);
            }
            //adicionando
            else {
                me.getCredxarea_grid().getStore().add(values);
            }

            if (button.action === 'aceptar')
                win.close();
            else if (button.action === 'aplicar')
                form.getForm().reset();

            me.sincronizarStore(me.getCredxarea_grid(), me.getCredxarea_grid().getStore());
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
                    //grid.down('carreralistpbar').updateInfo();
                } else if (batch.operations[0].action == "destroy") {
                    if (store.count() > 0)
                        grid.down('pagingtoolbar').doRefresh();//me.getCredxarea_grid().down('carreralistpbar').doRefresh();
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