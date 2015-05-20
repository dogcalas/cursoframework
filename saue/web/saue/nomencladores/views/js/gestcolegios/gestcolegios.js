var perfil = window.parent.UCID.portal.perfil;
UCID.portal.cargarEtiquetas('gestcolegios', function () {
    cargarInterfaz();
});
Ext.QuickTips.init();
function cargarInterfaz() {
    var btnAdicionargestcolegio = Ext.create('Ext.Button', {
        id: 'btnAgrgestcolegio',
        text: '<b>' + perfil.etiquetas.lbBtnAdicionar + '</b>',
        icon: perfil.dirImg + 'adicionar.png',
        iconCls: 'btn',
        hidden: true,
        handler: function () {
            mostFormgestcolegio('add');
        }
    });
    var btnModificargestcolegio = Ext.create('Ext.Button', {
        id: 'btnModgestcolegio',
        text: '<b>' + perfil.etiquetas.lbBtnModificar + '</b>',
        disabled: true,
        hidden: true,
        icon: perfil.dirImg + 'modificar.png',
        iconCls: 'btn',
        handler: function () {
            mostFormgestcolegio('mod');
        }
    });
    var btnEliminargestcolegio = Ext.create('Ext.Button', {
        id: 'btnEligestcolegio',
        text: '<b>' + perfil.etiquetas.lbBtnEliminar + '</b>',
        disabled: true,
        hidden: true,
        icon: perfil.dirImg + 'eliminar.png',
        iconCls: 'btn',
        handler: function () {
            Ext.MessageBox.show({
                title: 'Eliminar',
                msg: '¿Desea eliminar este colegio?',
                buttons: Ext.MessageBox.YESNO,
                icon: Ext.MessageBox.QUESTION,
                fn: eliminarColegio
            });
        }
    });
    UCID.portal.cargarAcciones(window.parent.idFuncionalidad);
    var winAdicionargestcolegio;
    var winModificargestcolegio;
    var formgestcolegio = Ext.create('Ext.form.Panel', {
        frame: true,
        bodyStyle: 'padding:5px 5px 0',
        width: 350,
        fieldDefaults: {
            msgTarget: 'side',
            labelWidth: 75
        },
        defaults: {
            anchor: '100%'
        },
        items: [
            {xtype: 'textfield',
                fieldLabel: 'Colegio:',
                name: 'descripcion',
                labelAlign: 'top',
                anchor: '100%',
                allowBlank: false
            },
            {xtype: 'checkbox',
                fieldLabel: 'Activado:',
                name: 'estado',
                checked: true
            },
            {xtype: 'hidden',
                name: 'idcolegio'
            }
        ]
    });

    function mostFormgestcolegio(opcion) {
        switch (opcion) {
            case 'add':
            {
                winAdicionargestcolegio = Ext.create('Ext.Window', {
                    title: 'Adicionar colegio',
                    closeAction: 'hide',
                    width: 300,
                    height: 150,
                    constrain: true,
                    layout: 'fit',
                    buttons: [
                        {
                            text: 'Cancelar',
                            icon: perfil.dirImg + 'cancelar.png',
                            handler: function () {
                                winAdicionargestcolegio.hide();
                            }
                        },
                        {
                            text: 'Aplicar',
                            icon: perfil.dirImg + 'aplicar.png',
                            handler: function () {
                                adicionarColegio("apl");
                            }
                        },
                        {
                            text: 'Aceptar',
                            icon: perfil.dirImg + 'aceptar.png',
                            handler: function () {
                                adicionarColegio("aceptar");
                            }
                        }
                    ]
                });
                winAdicionargestcolegio.add(formgestcolegio);
                winAdicionargestcolegio.show();
                winAdicionargestcolegio.on('hide', function () {
                    formgestcolegio.getForm().reset();
                }, this);
            }
                break;
            case 'mod':
            {
                winModificargestcolegio = Ext.create('Ext.Window', {
                    title: 'Modificar colegio',
                    closeAction: 'hide',
                    width: 300,
                    height: 150,
                    constrain: true,
                    layout: 'fit',
                    buttons: [
                        {
                            text: 'Cancelar',
                            icon: perfil.dirImg + 'cancelar.png',
                            handler: function () {
                                winModificargestcolegio.hide();
                            }
                        },
                        {
                            text: 'Aceptar',
                            icon: perfil.dirImg + 'aceptar.png',
                            handler: function () {
                                modificarColegio("aceptar");
                            }
                        }
                    ]
                });
                winModificargestcolegio.add(formgestcolegio);
                winModificargestcolegio.show();
                winModificargestcolegio.on('hide', function () {
                    formgestcolegio.getForm().reset();
                }, this);
                formgestcolegio.getForm().loadRecord(sm.getLastSelected());
            }
                break;
        }
    }

    var stGpgestcolegio = Ext.create('Ext.data.ArrayStore', {
        fields: [
            {
                name: "idcolegio"
            },
            {
                name: 'descripcion'
            },
            {
                name: 'estado'
            }
        ],
        proxy: {
            type: 'ajax',
            url: 'cargarColegios',
            actionMethods: { //Esta Linea es necesaria para el metodo de llamada POST o GET
                read: 'POST'
            },
            reader: {
                type: 'json',
                id: 'idcolegio',
                root: 'datos',
                totalProperty: 'cantidad'
            }
        }
    });
    var search =
    {
        xtype: 'searchfield',
        store: stGpgestcolegio,
        emptyText: 'Filtrar por descripción',
        width: 400,
        padding: '0 0 0 5',
        labelWidth: 40,
        filterPropertysNames: ['descripcion']
    };

    /*Ext.create('Ext.form.field.Trigger', {
     store: stGpgestcolegio,
        trigger1Cls: Ext.baseCSSPrefix + 'form-clear-trigger',
        trigger2Cls: Ext.baseCSSPrefix + 'form-search-trigger',
        width: 400,
        fieldLabel: perfil.etiquetas.lbBtnBuscar,
        labelWidth: 40,
        onTrigger1Click: function () {
            var me = this;

            if (me.hasSearch) {
                me.setValue('');
                me.store.clearFilter();
                me.hasSearch = false;
                me.triggerCell.item(0).setDisplayed(false);
                me.updateLayout();
            }
        },

        onTrigger2Click: function () {
            var me = this,
                value = me.getValue();

            if (value != null) {
                me.store.clearFilter();
                me.store.filter({filterFn: function (item) {
                    return item.get("descripcion").toLowerCase().match(me.getValue().toLowerCase());
                }});
                me.hasSearch = true;
                me.triggerCell.item(0).setDisplayed(true);
                me.updateLayout();
            }
        }
    });
    search.on('afterrender', function () {
            this.triggerCell.item(0).setDisplayed(false);
        }
    );
    search.on('specialkey', function (f, e) {
        if (e.getKey() == e.ENTER) {
            f.onTrigger2Click();
        }
     });*/
    var Gpgestcolegio = Ext.create('Ext.grid.Panel', {
        store: stGpgestcolegio,
        stateId: 'stateGrid',
        columnLines: true,
        viewConfig: {
            getRowClass: function (record, rowIndex, rowParams, store) {
                if (record.data.estado == false)
                    return 'FilaRoja';
            }
        },
        columns: [
            {
                hidden: true,
                dataIndex: 'idcolegio'
            },
            {hidden: true,
                dataIndex: 'estado'
            },
            {
                header: 'Colegio',
                flex: 1,
                sortable: true,
                dataIndex: 'descripcion'
            }
        ],
        region: 'center',
        tbar: [btnAdicionargestcolegio, btnModificargestcolegio, btnEliminargestcolegio, '->', search],
        bbar: Ext.create('Ext.toolbar.Paging', {
            displayInfo: true,
            store: stGpgestcolegio
        })
    });
    var sm = Gpgestcolegio.getSelectionModel();
    sm.setSelectionMode('MULTI');
    sm.on('selectionchange', function (sel, selectedRecord) {
        btnModificargestcolegio.setDisabled(selectedRecord.length>1);
        btnEliminargestcolegio.setDisabled(selectedRecord.length==0);

    });
    stGpgestcolegio.load();
    function adicionarColegio(apl) {
        //si es la opción de aplicar
        if (formgestcolegio.getForm().isValid()) {
            formgestcolegio.getForm().submit({
                url: 'insertarColegio',
                waitMsg: perfil.etiquetas.lbMsgRegistrando,
                failure: function (form, action) {
                    if (action.result.codMsg === 1) {
                        formgestcolegio.getForm().reset();
                        stGpgestcolegio.reload();
                        if (apl === "aceptar")
                            winAdicionargestcolegio.hide();
                    }


                }
            });
        }
    }

    function modificarColegio(apl) {
        //si es la opción de aplicar
        if (formgestcolegio.getForm().isValid()) {
            formgestcolegio.getForm().submit({
                url: 'modificarColegio',
                waitMsg: perfil.etiquetas.lbMsgModificando,
                failure: function (form, action) {
                    if (action.result.codMsg === 1) {
                        stGpgestcolegio.reload();
                        if (apl === "aceptar")
                            winModificargestcolegio.hide();
                    }

                }
            });
        }
    }

    function eliminarColegio(buttonId) {
        if (buttonId === "yes") {
            var ids= new Array();
            for(var i=0; i<sm.getCount(); i++){
                ids.push(sm.getSelection()[i].raw.idcolegio)
            }
            Ext.Ajax.request({
                url: 'eliminarColegio',
                method: 'POST',
                params: {idcoleg: Ext.JSON.encode(ids)},
                callback: function (options, success, response) {
                    responseData = Ext.decode(response.responseText);
                    if (responseData.codMsg === 1) {
                        stGpgestcolegio.reload();
                        sm.deselect();
                    }
                }
            });
        }
    }

    //var general = Ext.create('Ext.panel.Panel', {layout: 'border', items: [Gpgestcolegio]});
    var vpGestSistema = Ext.create('Ext.Viewport', {layout: 'fit', items: Gpgestcolegio});
}