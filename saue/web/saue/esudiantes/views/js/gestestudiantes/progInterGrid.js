Ext.define('progInterGrid', {
    extend: 'Ext.grid.GridPanel',
    alias: 'widget.prog_internac',
    id: 'prog_internac',
    store: Ext.create('Ext.data.ArrayStore', {
        model: Ext.define('ProgInterModel', {
            extend: 'Ext.data.Model',
            fields: ['idproginternacional', 'idpais','nombrepais', 'univ_inst', 'idalumno', 'fecha', 'estado',
                'idusuario', 'duracion', 'idtipoprograma', 'descripcion']
        }),
        //autoLoad: true,
        storeId: 'idStoreProgInter',
        pageSize: 15,
        remoteFilter: true,
        proxy: {
            type: 'ajax',
            api: {
                read: 'cargarProgramas'
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

        me.getSelectionModel().on('selectionchange', function(sel, selectedRecord) {
            if (selectedRecord.length === 1) {
                Ext.getCmp('btnModificarProgInter').enable();
                Ext.getCmp('btnEliminarProgInter').enable();
                idalumno = me.getSelectionModel().getSelection()[0].raw.idalumno;
            } else if (selectedRecord.length > 1) {
                Ext.getCmp('btnModificarProgInter').disable();
                Ext.getCmp('btnEliminarProgInter').enable();
            } else {
                Ext.getCmp('btnModificarProgInter').disable();
                Ext.getCmp('btnEliminarProgInter').disable();
            }
        });

        me.bbar = Ext.create('Ext.toolbar.Paging', {
            displayInfo: true,
            store: me.store
        });

        me.tbar = [
            {
                xtype: 'button',
                id: 'btnAdicionarProgInter',
                text: '<b>' + 'Adicionar' + '</b>',
                icon: perfil.dirImg + 'adicionar.png',
                iconCls: 'btn',
                handler: function () {
                    Ext.widget('alumno_progint_form').show();
                }
            },
            {
                xtype: 'button',
                id: 'btnModificarProgInter',
                text: '<b>' + 'Modificar' + '</b>',
                icon: perfil.dirImg + 'modificar.png',
                iconCls: 'btn',
                disabled: true,
                handler: function () {
                    Ext.widget('alumno_progint_form').show();
                }
            },
            {
                xtype: 'button',
                id: 'btnEliminarProgInter',
                disabled: true,
                text: '<b>' + 'Eliminar' + '</b>',
                icon: perfil.dirImg + 'eliminar.png',
                iconCls: 'btn',
                handler: function () {
                    me.eliminarPrograma(me);
                }
            }
        ];

        me.columns = [
            {
                text: 'idproginternacional',
                flex: 1,
                dataIndex: 'idproginternacional',
                hidden: true
            },
            {
                text: 'idalumno',
                flex: 1,
                dataIndex: 'idalumno',
                hidden: true
            },
            {
                text: 'idpais',
                flex: 1,
                dataIndex: 'idpais',
                hidden: true
            },
            {
                text: 'idtipoprograma',
                flex: 1,
                dataIndex: 'tipoprograma',
                hidden: true
            },
            {
                text: perfil.etiquetas.lbHdrGpPTipo,
                flex: 1,
                dataIndex: 'descripcion'
            },
            {
                text: perfil.etiquetas.lbHdrGpPPais,
                flex: 1,
                dataIndex: 'nombrepais'
            },
            {
                text: perfil.etiquetas.lbHdrGpPUnivInst,
                flex: 1,
                dataIndex: 'univ_inst'
            },
            {
                text: perfil.etiquetas.lbHdrGpPDuracion,
                flex: 1,
                dataIndex: 'duracion'
            },
            {
                text: 'fecha',
                flex: 1,
                dataIndex: 'fecha',
                hidden: true
            },
            {
                text: 'estado',
                flex: 1,
                dataIndex: 'estado',
                hidden: true
            },
            {
                text: 'idusuario',
                flex: 1,
                dataIndex: 'idusuario',
                hidden: true
            }
        ];

        me.callParent(arguments);
    },
    eliminarPrograma: function eliminarPrograma(me) {
        mostrarMensaje(2, '¿Desea eliminar este programa?', eliminar);
        var delMask = new Ext.LoadMask(Ext.getBody(), {
            msg: 'Eliminando programa...'
        });

        function eliminar(btnPresionado) {
            if (btnPresionado === 'ok') {
                var ids = new Array();
                for (var i = 0; i < me.getSelectionModel().getCount(); i++) {
                    ids.push(me.getSelectionModel().getSelection()[i].raw.idproginternacional);
                }
                delMask.show();
                Ext.Ajax.request({
                    url: 'eliminarProgramas',
                    method: 'POST',
                    params: {
                        idproginternacional: Ext.JSON.encode(ids)
                    },
                    callback: function (options, success, response) {
                        responseData = Ext.decode(response.responseText);
                        delMask.disable();
                        delMask.hide();
                        if (responseData.codMsg === 1) {
                            me.getStore().load({params: {idalumno: me.getSelectionModel().getSelection()[0].raw.idalumno}});
                            me.getSelectionModel().clearSelections();
                            Ext.getCmp('btnModificarProgInter').disable();
                            Ext.getCmp('btnEliminarProgInter').disable();
                        }
                    }
                });
            }
        }
    }
});
