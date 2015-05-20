Ext.define('GestTipoEstudiante.controller.TipoEstudiante', {
    extend: 'Ext.app.Controller',

    views: [
        'GestTipoEstudiante.view.tipoestudiante.TipoEstudianteList',
        'GestTipoEstudiante.view.tipoestudiante.TipoEstudianteEdit',
        'GestTipoEstudiante.view.tipoestudiante.TipoEstudianteListToolBar'
    ],
    stores: [
        'GestTipoEstudiante.store.TipoEstudiante'
    ],
    models: [
        'GestTipoEstudiante.model.TipoEstudiante'
    ],
    refs: [
        {ref: 'list', selector: 'tipoestudiantelist'},
        {ref: 'tipoestudiantelisttbar', selector: 'tipoestudiantelisttbar'}
    ],

    init: function () {
        var me = this;
        this.control({

            'tipoestudiantelist': {
                render: this.crearSearhField,
                selectionchange: me.activarBotones
            },
            'tipoestudiantelisttbar button[action=adicionar]': {
                click: me.adicionarTipoEstudiante
            },
            'tipoestudiantelisttbar button[action=modificar]': {
                click: me.modificarTipoEstudiante
            },
            'tipoestudiantelisttbar button[action=eliminar]': {
                click: me.eliminarTipoEstudiante
            },
            'tipoestudianteedit button[action=aceptar]': {
                click: me.guardarTipoEstudiante
            },
            'tipoestudianteedit button[action=aplicar]': {
                click: me.guardarTipoEstudiante
            }
        });
    },

    activarBotones: function (store, selected) {
        var me = this,
            tbar = me.getTipoestudiantelisttbar();

        if(selected.length==1){
            tbar.down('button[action=modificar]').enable();
            tbar.down('button[action=eliminar]').enable();
        }else if(selected.length > 1){
            tbar.down('button[action=modificar]').disable();
            tbar.down('button[action=eliminar]').enable();
        }else{
            tbar.down('button[action=modificar]').disable();
            tbar.down('button[action=eliminar]').disable();
        }
    },

    crearSearhField: function (grid) {
        grid.down('tipoestudiantelisttbar').add([
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

    adicionarTipoEstudiante: function (button) {
        var view = Ext.widget('tipoestudianteedit');
        view.setTitle(perfil.etiquetas.lbTtlAdicionar);
    },

    modificarTipoEstudiante: function (button) {
        var view = Ext.widget('tipoestudianteedit'),
            record = this.getList().getSelectionModel().getLastSelected();

        view.setTitle(perfil.etiquetas.lbTtlModificar);
        view.down('button[action=aplicar]').hide();
        view.down('form').loadRecord(record);
    },

    eliminarTipoEstudiante: function (button) {
//        var record = this.getList().getSelectionModel().getLastSelected(),
            var store = this.getList().getStore(),
                selModel = this.getList().getSelectionModel();

        var ids = new Array();
        var est = new Array();
        for (var i = 0; i < selModel.getCount(); i++) {
            ids.push(selModel.getSelection()[i].data.idtipoalumno);
            est.push(selModel.getSelection()[i]);
        }

        mostrarMensaje(
            2,
            perfil.etiquetas.lbMsgConfEliminar,
            function (btn, text) {
                if (btn == 'ok') {
                    for (var j = 0; j < est.length; j++) {
                        store.remove(est[j]);
                    }
                    store.sync();
                    store.load();
                }
            }
        )
    },

    guardarTipoEstudiante: function (button) {
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
                this.getGestTipoEstudianteStoreTipoEstudianteStore().add(values);
            }

            me.getGestTipoEstudianteStoreTipoEstudianteStore().sync({
                success: function (batch) {
                    if (batch.operations[0].action == "create")
                        me.getGestTipoEstudianteStoreTipoEstudianteStore().reload();
                },
                failure: function () {
                    me.getGestTipoEstudianteStoreTipoEstudianteStore().reload();
                }
            });

            if (button.action === 'aceptar')
                win.close();
            else
                form.getForm().reset();
        }
    }
});