Ext.define('estudiosGrid', {
    extend: 'Ext.grid.GridPanel',
    alias: 'widget.alumno_estudios',
    id: 'alumno_estudios',
    store: Ext.create('Ext.data.ArrayStore', {
        model: Ext.define('EstudiosModel', {
            extend: 'Ext.data.Model',
            fields: ['idestudios', 'idalumno', {name: 'idestructuraE', mapping: 'idestructura'},
                {name: 'idcarreraE', mapping: 'idcarrera'}, {name: 'idenfasisE', mapping: 'idenfasis'},
                {name: 'idpensumE', mapping: 'idpensum'}, 'facultad', 'enfasis', 'carrera', 'pensum']
        }),
        //autoLoad: true,
        storeId: 'idStoreEstudios',
        pageSize: 15,
        remoteFilter: true,
        proxy: {
            type: 'ajax',
            api: {
                read: 'cargarEstudios',
                create: 'insertarEstudio'
            },
            actionMethods: { //Esta Linea es necesaria para el metodo de llamada POST o GET
                read: 'POST'
            },
            reader: {
                root: 'datos',
                totalProperty: 'cantidad',
                successProperty: 'success',
                messageProperty: 'mensaje'
            }
        }
    }),

    selModel: Ext.create('Ext.selection.RowModel', {
        id: 'idSelectionEstudiosGrid',
        mode: 'MULTI'
    }),

    initComponent: function () {
        var me = this;

        me.getSelectionModel().on('selectionchange', function (sel, selectedRecord) {
            if (selectedRecord.length === 1) {
                Ext.getCmp('btnModificarEstudio').enable();
                Ext.getCmp('btnEliminarEstudio').enable();
                idalumno = me.getSelectionModel().getSelection()[0].raw.idalumno;
            } else if (selectedRecord.length > 1) {
                Ext.getCmp('btnModificarEstudio').disable();
                Ext.getCmp('btnEliminarEstudio').enable();
            } else {
                Ext.getCmp('btnModificarEstudio').disable();
                Ext.getCmp('btnEliminarEstudio').disable();
            }
        });

        me.bbar = Ext.create('Ext.toolbar.Paging', {
            displayInfo: true,
            store: me.store
        });

        me.tbar = [
            {
                xtype: 'button',
                id: 'btnAdicionarEstudio',
                text: '<b>' + 'Adicionar' + '</b>',
                icon: perfil.dirImg + 'adicionar.png',
                iconCls: 'btn',
                handler: function () {
                    Ext.widget('alumno_estudios_form').show();
                }
            },
            {
                xtype: 'button',
                id: 'btnModificarEstudio',
                text: '<b>' + 'Modificar' + '</b>',
                icon: perfil.dirImg + 'modificar.png',
                iconCls: 'btn',
                disabled: true,
                handler: function () {
                    Ext.widget('alumno_estudios_form').show();
                }
            },
            {
                xtype: 'button',
                id: 'btnEliminarEstudio',
                disabled: true,
                text: '<b>' + 'Eliminar' + '</b>',
                icon: perfil.dirImg + 'eliminar.png',
                iconCls: 'btn',
                handler: function () {
                    me.eliminarEstudio(me);
                }
            }
        ];

        me.columns = [
            {
                text: 'idestudios',
                flex: 1,
                dataIndex: 'idestudios',
                hidden: true
            },
            {
                text: 'idalumno',
                flex: 1,
                dataIndex: 'idalumno',
                hidden: true
            },
            {
                text: 'idfacultad',
                flex: 1,
                dataIndex: 'idestructuraE',
                hidden: true
            },
            {
                text: 'idcarrera',
                flex: 1,
                dataIndex: 'idcarreraE',
                hidden: true
            },
            {
                text: 'idenfasis',
                flex: 1,
                dataIndex: 'idenfasisE',
                hidden: true
            },
            {
                text: 'idpensum',
                flex: 1,
                dataIndex: 'idpensumE',
                hidden: true
            },
            {
                text: perfil.etiquetas.lbHdrGpEstFac,
                flex: 1,
                dataIndex: 'facultad'
            },
            {
                text: perfil.etiquetas.lbHdrGpEstCarrera,
                flex: 1,
                dataIndex: 'carrera'
            },
            {
                text: perfil.etiquetas.lbHdrGpEstEnfasis,
                flex: 1,
                dataIndex: 'enfasis'
            },
            {
                text: perfil.etiquetas.lbHdrGpEstPensum,
                flex: 1,
                dataIndex: 'pensum',
                hidden: true
            }
        ];

        me.callParent(arguments);
    },

    eliminarEstudio: function eliminarEstudio(me) {
        mostrarMensaje(2, '¿Desea eliminar este estudio?', eliminar);
        var delMask = new Ext.LoadMask(Ext.getBody(), {
            msg: 'Eliminando estudio...'
        });

        function eliminar(btnPresionado) {
            if (btnPresionado === 'ok') {
                var ids = new Array();
                for (var i = 0; i < me.getSelectionModel().getCount(); i++) {
                    ids.push(me.getSelectionModel().getSelection()[i].raw.idestudios);
                }
                delMask.show();
                Ext.Ajax.request({
                    url: 'eliminarEstudio',
                    method: 'POST',
                    params: {
                        idestudios: Ext.JSON.encode(ids)
                    },
                    callback: function (options, success, response) {
                        responseData = Ext.decode(response.responseText);
                        delMask.disable();
                        delMask.hide();
                        if (responseData.codMsg === 1) {
                            me.getStore().load({params: {idalumno: me.getSelectionModel().getSelection()[0].raw.idalumno}});
                            me.getSelectionModel().clearSelections();
                            Ext.getCmp('btnModificarEstudio').disable();
                            Ext.getCmp('btnEliminarEstudio').disable();
                        }
                    }
                });
            }
        }
    }
});