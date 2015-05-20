Ext.define('GestMatxPensum.controller.MatxPensum', {
    extend: 'Ext.app.Controller',

    views: [
        'GestMatxPensum.view.facultad.Combo',
        'GestMatxPensum.view.carrera.Combo',
        'GestMatxPensum.view.enfasi.Combo',
        'GestMatxPensum.view.pensum.Combo',
        //'GestMatxPensum.view.tipo_materia.Combo',
        'GestMatxPensum.view.area.Combo',
        'GestMatxPensum.view.area.ComboG',
        'GestMatxPensum.view.materia.Grid',
        'GestMatxPensum.view.matxpensum.Grid',
        'GestMatxPensum.view.materia.Toolbar',
        'GestMatxPensum.view.matxpensum.Toolbar'

    ],
    stores: [
        'GestMatxPensum.store.Facultades',
        'GestMatxPensum.store.Carreras',
        'GestMatxPensum.store.Enfasis',
        'GestMatxPensum.store.Pensums',
        'GestMatxPensum.store.Areas',
        'GestMatxPensum.store.Materias',
        'GestMatxPensum.store.MateriasxPensum'
    ],
    models: [],

    refs: [
        {ref: 'carrera_combo', selector: 'carrera_combo'},
        {ref: 'enfasi_combo', selector: 'enfasi_combo'},
        {ref: 'pensum_combo', selector: 'pensum_combo'},
        {ref: 'matxpensum_grid', selector: 'matxpensum_grid'},
        {ref: 'matxpensum_toolbar', selector: 'matxpensum_toolbar'},
        {ref: 'matxpensum_materia_grid', selector: 'matxpensum_materia_grid'},
        {ref: 'matxpensum_materia_tbar', selector: 'matxpensum_materia_tbar'},
        {ref: 'area_combo', selector: 'area_combo'},
        {ref: 'area_general_combo', selector: 'area_general_combo'}
    ],

    init: function () {
        var me = this;

        me.control({
            'facultad_combo': {
                select: me.cargarCarreras,
                change: me.cargarCarreras
            },
            'carrera_combo': {
                select: me.cargarEnfasisPensum,
                change: me.cargarEnfasisPensum
            },
            'area_general_combo': {
                select: me.cargarAreas,
                change: me.cargarAreas
            },
            'pensum_combo': {
                select: function (combo) {
                    me.cargarMaterias(combo);
                    me.cargarAreas();
                },
                change: function (combo) {
                    me.cargarMaterias(combo);
                    me.cargarAreas();
                }
            },
            'enfasi_combo': {
                select: function (combo) {
                    me.cargarMaterias(combo);
                    me.cargarAreas();
                },
                change: function (combo) {
                    me.cargarMaterias(combo);
                    me.cargarAreas();
                }
            },
            'area_combo': {
                select: me.cargarMaterias,
                change: me.cargarMaterias
            },
            'matxpensum_grid dataview': {
                beforedrop: me.setearValores
            },
            'matxpensum_toolbar button[action=eliminar]': {
                click: me.eliminarMatxPensum
            },
            'matxpensum_grid': {
                selectionchange: me.activarBotones
            },
            'matxpensum_grid #idSumaCreditos': {
                change: me.setearMaxSumaCreditos
            }
        });

        me.getGestMatxPensumStoreMateriasStore().on(
            {
                beforeload: {fn: me.setearExtraParams, scope: this}
            }
        );

        me.getGestMatxPensumStoreAreasStore().on(
            {
                beforeload: {fn: me.setearExtraParamsArea, scope: this}
            }
        );

        me.getGestMatxPensumStoreMateriasxPensumStore().on(
            {
                beforeload: {fn: me.setearExtraParams, scope: this},
                load: {
                    fn: function (store, records) {
                        me.sumarCreditos(records, true)
                    }, scope: this
                }
            }
        );
    },

    cargarCarreras: function (facultad_combo) {
        var me = this,
            carrera_combo = me.getCarrera_combo();

        me.resetearValores();

        carrera_combo.getStore().load(
            {
                params: {idfacultad: facultad_combo.getValue()}
            }
        );
        carrera_combo.enable();
    },

    resetearValores: function () {
        var me = this,
            carrera_combo = me.getCarrera_combo(),
            enfasis_combo = me.getEnfasi_combo(),
            pensum_combo = me.getPensum_combo(),
            nivel_combo = me.getArea_general_combo(),
            campo_combo = me.getArea_combo();

        carrera_combo.reset();
        carrera_combo.disable();
        enfasis_combo.reset();
        enfasis_combo.disable();
        pensum_combo.reset();
        pensum_combo.disable();
        nivel_combo.reset();
        campo_combo.reset();
        campo_combo.disable();

        me.getMatxpensum_materia_grid().store.removeAll();
        me.getMatxpensum_materia_grid().store.fireEvent('load', {});
        me.getMatxpensum_grid().store.removeAll();
        me.getMatxpensum_grid().store.fireEvent('load', {});
    },

    cargarAreas: function () {
        var me = this,
            area_combo = me.getArea_combo();

        area_combo.reset();
        area_combo.getStore().load();
        area_combo.enable();
    },

    cargarEnfasisPensum: function (carrera_combo) {
        var me = this,
            pensum_combo = me.getPensum_combo(),
            enfasi_combo = me.getEnfasi_combo(),
            nivel_combo = me.getArea_general_combo();

        enfasi_combo.reset();
        enfasi_combo.getStore().load(
            {
                params: {idcarrera: carrera_combo.getValue()}
            }
        );
        enfasi_combo.enable();

        pensum_combo.reset();
        pensum_combo.getStore().load(
            {
                params: {idcarrera: carrera_combo.getValue()}
            }
        );
        pensum_combo.enable();

        nivel_combo.reset();
        nivel_combo.getStore().load();
        nivel_combo.enable();
    },

    cargarMaterias: function (combo) {
        var me = this,
            materia_store = me.getMatxpensum_materia_grid().getStore(),
            materia_pensum_store = me.getMatxpensum_grid().getStore();

        materia_store.load();
        materia_pensum_store.load();
    },

    activarBotones: function (store, selected) {
        var me = this,
            tbar = me.getMatxpensum_toolbar();
        tbar.down('button[action=eliminar]').setDisabled(selected.length === 0);
    },

    eliminarMatxPensum: function (button) {
        var me = this,
            record = me.getMatxpensum_grid().getSelectionModel().getLastSelected(),
            matxpensum_materia_grid = me.getMatxpensum_materia_grid(),
            store = me.getMatxpensum_grid().getStore();

        mostrarMensaje(
            2,
            perfil.etiquetas.lbMsgConfEliminar + " '" + record.get('descripcion') + "'",
            function (btn, text) {
                if (btn == 'ok') {
                    store.remove(record);
                    store.sync({
                        callback: function () {
                            store.reload();
                            matxpensum_materia_grid.getStore().reload();
                        }
                    });
                }
            }
        )
    },

    obtenerExtraParams: function () {
        var me = this,
            enfasi_combo = me.getEnfasi_combo(),
            pensum_combo = me.getPensum_combo(),
            area_combo = me.getArea_combo(),
            extra_params = {};

        //Obtenindo valor del combo de énfasis en caso de que halla cambiado
        if (pensum_combo.isDirty())
            extra_params.idpensum = pensum_combo.getValue();

        //Obtenindo valor del combo de énfasis en caso de que halla cambiado
        if (enfasi_combo.isDirty())
            extra_params.idenfasis = enfasi_combo.getValue();

        //Obtenindo valor del combo de áreas en caso de que halla cambiado
        if (area_combo.isDirty())
            extra_params.idarea = area_combo.getValue();

        return extra_params
    },

    setearExtraParams: function (store) {
        var me = this,
            extra_params = me.obtenerExtraParams();

        if (!extra_params.idpensum || !extra_params.idarea)
            return false;

        store.getProxy().extraParams = extra_params;
    },

    setearExtraParamsArea: function (store) {
        var me = this,
            enfasi_combo = me.getEnfasi_combo(),
            pensum_combo = me.getPensum_combo(),
            area_general_combo = me.getArea_general_combo(),
            extra_params = {};

        //Obtenindo valor del combo de énfasis en caso de que halla cambiado
        if (pensum_combo.isDirty())
            extra_params.idpensum = pensum_combo.getValue();

        //Obtenindo valor del combo de énfasis en caso de que halla cambiado
        if (enfasi_combo.isDirty())
            extra_params.idenfasis = enfasi_combo.getValue();

        //Obtenindo valor del combo de areas generales en caso de que halla cambiado
        if (area_general_combo.isDirty())
            extra_params.idareageneral = area_general_combo.getValue();

        store.getProxy().extraParams = extra_params;
    },

    setearValores: function (node, data, overModel, dropPosition, dropHandlers) {
        var me = this,
            enfasi_combo = me.getEnfasi_combo(),
            pensum_combo = me.getPensum_combo(),
            area_combo = me.getArea_combo();

        if (enfasi_combo.validate() && pensum_combo.validate() && area_combo.validate() && me.validarSumaCreditos(data)) {
            var matxpensum_grid = me.getMatxpensum_grid(),
                matxpensum_materia_grid = me.getMatxpensum_materia_grid(),
                extra_params = me.obtenerExtraParams(),
                materia_x_pensum;

            for (var i = 0; i < data.records.length; i++) {
                materia_x_pensum = Ext.create('GestMatxPensum.model.MateriaxPensum', {
                    idpensum: extra_params.idpensum,
                    idenfasis: extra_params.idenfasis,
                    idarea: extra_params.idarea,
                    idmateria: data.records[i].get('idmateria'),
                    codmateria: data.records[i].get('codmateria'),
                    descripcion: data.records[i].get('descripcion'),
                    creditos: data.records[i].get('creditos'),
                    estado: data.records[i].get('estado')
                });

                data.records[i] = materia_x_pensum;
            }

            dropHandlers.processDrop();

            matxpensum_grid.getStore().sync(
                {
                    callback: function () {
                        matxpensum_materia_grid.getStore().reload();
                        matxpensum_grid.getStore().reload();
                    }
                }
            );
        } else
            return false;

    },

    sumarCreditos: function (records, reset) {
        var me = this,
            suma_creditos_numberfield = me.getMatxpensum_grid().down('#idSumaCreditos'),
            suma_creditos = (reset) ? 0 : suma_creditos_numberfield.getValue();

        if (records.length > 0) {
            for (var i = 0; i < records.length; i++) {
                suma_creditos += records[i].get('creditos');
            }
        }

        suma_creditos_numberfield.setValue(suma_creditos);
        return suma_creditos
    },

    setearMaxSumaCreditos: function (numberfield, nuevo_valor, viejo_valor) {
        var me = this;
        if (me.getArea_combo().displayTplData.length > 0) {
            var creditos = me.getArea_combo().displayTplData[0].creditos;
            numberfield.setMaxValue(creditos);
        }

    },

    validarSumaCreditos: function (data) {
        var me = this,
            suma_creditos = me.getMatxpensum_grid().down('#idSumaCreditos').getValue(),
            creditos = 0;

        for (var i = 0; i < data.records.length; i++) {
            suma_creditos += data.records[i].get('creditos');
        }

        if (me.getArea_combo().displayTplData.length > 0) {
            creditos = me.getArea_combo().displayTplData[0].creditos;
        }

        if (creditos >= suma_creditos)
            return true;
        else {
            mostrarMensaje(1, perfil.etiquetas.lbCmpSumCredMaxText);
            return false;
        }
    }
});