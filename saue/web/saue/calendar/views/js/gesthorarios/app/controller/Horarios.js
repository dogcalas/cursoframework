Ext.define('GestHorarios.controller.Horarios', {
    extend: 'Ext.app.Controller',

    views: [
        'GestHorarios.view.horarios.HorariosList',
        'GestHorarios.view.horarios.HorariosEdit',
        'GestHorarios.view.horarios.Frecuencias',
        'GestHorarios.view.horarios.HorariosListToolBar',
        'GestHorarios.view.horarios.Combo_Dias',
        'GestHorarios.view.horarios.HorariosDetaList'
    ],
    stores: [
        'GestHorarios.store.Horarios',
        'GestHorarios.store.Dias',
        'GestHorarios.store.HorariosDeta'
    ],
    models: [
        'GestHorarios.model.Horarios',
        'GestHorarios.model.Dias',
        'GestHorarios.model.HorariosDeta'
    ],
    refs: [
        {ref: 'list', selector: 'horarioslist'},
        {ref: 'horariodeta', selector: 'horariosdetalist'},
        {ref: 'horarioslisttbar', selector: 'horarioslisttbar'},
        {ref: 'horariosdetalisttbar', selector: 'horariosdetalisttbar'},
        {ref: 'frecuencias', selector: 'frecuencias'}
    ],

    init: function () {
        var me = this;
        this.control({

            'horarioslist': {
                render: this.crearSearhField,
                selectionchange: me.activarBotones
            },
            'frecuencias button[action=aceptar]': {
                click: function () {
                    me.getList().getStore().load();
                }
            },
            'horariosdetalist': {
                selectionchange: me.activarBotonesHD
            },
            'horarioslisttbar button[action=adicionar]': {
                click: me.adicionarHorarios
            },
            'horarioslisttbar button[action=modificar]': {
                click: me.modificarHorarios
            },
            'horarioslisttbar button[action=frecuencia]': {
                click: me.FrecuenciaHorarios
            },
            'horarioslisttbar button[action=eliminar]': {
                click: me.eliminarHorarios
            },
            'horariosedit button[action=aceptar]': {
                click: me.guardarHorarios
            },
            'horariosedit button[action=aplicar]': {
                click: me.guardarHorarios
            },
            'horariosdetalist button[action=adicionarHD]': {
                click: me.guardarHorariosDeta
            },
            'horariosdetalist button[action=eliminarHD]': {
                click: me.eliminarHorariosDeta
            }
        });
    },

    activarBotones: function (store, selected) {
        var me = this,
            tbar = me.getHorarioslisttbar();

        if(selected.length == 1){
            tbar.down('button[action=modificar]').enable();
            tbar.down('button[action=frecuencia]').enable();
            tbar.down('button[action=eliminar]').enable();
        }else if(selected.length > 1){
            tbar.down('button[action=modificar]').disable();
            tbar.down('button[action=frecuencia]').disable();
            tbar.down('button[action=eliminar]').enable();
        }else{
            tbar.down('button[action=modificar]').disable();
            tbar.down('button[action=frecuencia]').disable();
            tbar.down('button[action=eliminar]').disable();
        }
    },

    activarBotonesHD: function (store, selected) {
        var me = this,
            tbar = me.getHorariosdetalisttbar();

        if(selected.length == 1){
            tbar.down('button[action=eliminarHD]').enable();
        }else if(selected.length > 1){
            tbar.down('button[action=eliminarHD]').enable();
        }else{
            tbar.down('button[action=eliminarHD]').disable();
        }
    },

    crearSearhField: function (grid) {
        grid.down('horarioslisttbar').add([
            '->',
            {
                xtype: 'searchfield',
                store: grid.getStore(),
                //anchor: '100%',
                width: 400,
                fieldLabel: '<b>' + perfil.etiquetas.lbBtnBuscar + '</b>',
                labelWidth: 40,
                filterPropertysNames: ['descripcion']
            }
        ])
    },

    adicionarHorarios: function (button) {
        var view = Ext.widget('horariosedit');
        view.setTitle('Adicionar horario');
    },

    FrecuenciaHorarios: function (button) {
        var view = Ext.widget('frecuencias'),
            record = this.getList().getSelectionModel().getLastSelected();
        view.setTitle('Frecuencias');
        this.getHorariodeta().getStore().load({params:{idhorario: record.get('idhorario')}});
    },

    modificarHorarios: function (button) {
        var view = Ext.widget('horariosedit'),
            record = this.getList().getSelectionModel().getLastSelected();

        view.setTitle('Modificar horario');
        view.down('button[action=aplicar]').hide();

        view.down('form').loadRecord(record);
    },

    eliminarHorarios: function (button) {
        //var record = this.getList().getSelectionModel().getLastSelected(),
            var store = this.getList().getStore(),
                selModel = this.getList().getSelectionModel();

        var ids = new Array();
        var hor = new Array();
        for (var i = 0; i < selModel.getCount(); i++) {
            ids.push(selModel.getSelection()[i].data.idhorario);
            hor.push(selModel.getSelection()[i]);
        }

        mostrarMensaje(
            2,
            perfil.etiquetas.lbMsgConfEliminar,
            function (btn, text) {
                if (btn == 'ok') {
                    for (var j = 0; j < hor.length; j++) {
                        store.remove(hor[j]);
                    }
                    store.sync();
                }
            }
        )
    },

    eliminarHorariosDeta: function (button) {
            var store = this.getHorariodeta().getStore(),
                selModel = this.getHorariodeta().getSelectionModel();

        var ids = new Array();
        var hor = new Array();
        for (var i = 0; i < selModel.getCount(); i++) {
            ids.push(selModel.getSelection()[i].data.idhorario_detallado);
            hor.push(selModel.getSelection()[i]);
        }

        mostrarMensaje(
            2,
            perfil.etiquetas.lbMsgConfEliminarHD,
            function (btn, text) {
                if (btn == 'ok') {
                    for (var j = 0; j < hor.length; j++) {
                        store.remove(hor[j]);
                    }
                    store.sync();
                }
            }
        )
    },

    guardarHorarios: function (button) {
        var win = button.up('window'),
            form = win.down('form'),
            me = this;

        if (form.getForm().isValid()) {
            var record = form.getRecord(),
                values = form.getValues();

            //modificando
            if (record) {
                record.set(values);
            }
            //insertando
            else {
                me.getGestHorariosStoreHorariosStore().add(values);
            }

            me.getGestHorariosStoreHorariosStore().sync({
                success: function (batch) {
                    if (batch.operations[0].action == "create")
                        me.getGestHorariosStoreHorariosStore().load();
                },
                failure: function () {
                    me.getGestHorariosStoreHorariosStore().load();
                }
            });

            if (button.action === 'aceptar')
                win.close();
            else
                form.getForm().reset();
        }
    },

    guardarHorariosDeta: function (button) {
        var win = button.up('window'),
            form = win.down('form'),
            me = this;

        if (form.getForm().isValid()) {
            var record = form.getRecord(),
                values = form.getValues();

            var idhorario = me.getList().getSelectionModel().getSelection()[0].raw.idhorario;
            me.getHorariodeta().getStore().getProxy().extraParams = {idhorario: idhorario};

                me.getHorariodeta().getStore().add(values);

            me.getHorariodeta().getStore().sync({
                success: function (batch) {
                    if (batch.operations[0].action == "create")
                        me.getHorariodeta().getStore().load();
                },
                failure: function () {
                    me.getHorariodeta().getStore().load();
                }
            });
        }
    }
});