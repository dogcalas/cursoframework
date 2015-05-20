var perfil = window.parent.UCID.portal.perfil;
UCID.portal.cargarEtiquetas('gestuniversidades', function () {
    cargarInterfaz();
});
Ext.QuickTips.init();
function cargarInterfaz() {
    var btnAdicionargestuniversidades = Ext.create('Ext.Button', {
        id: 'btnAgrgestuniversidades',
        text: '<b>' + perfil.etiquetas.lbBtnAdicionar + '</b>',
        icon: perfil.dirImg + 'adicionar.png',
        iconCls: 'btn',
        hidden: true,
        handler: function () {
            mostFormgestuniversidades('add');
        }
    });
    var btnModificargestuniversidades = Ext.create('Ext.Button', {
        id: 'btnModgestuniversidades',
        text: '<b>' + perfil.etiquetas.lbBtnModificar + '</b>',
        disabled: true,
        hidden: true,
        icon: perfil.dirImg + 'modificar.png',
        iconCls: 'btn',
        handler: function () {
            mostFormgestuniversidades('mod');
        }
    });
    var btnEliminargestuniversidades = Ext.create('Ext.Button', {
        id: 'btnEligestuniversidades',
        text: '<b>' + perfil.etiquetas.lbBtnEliminar + '</b>',
        disabled: true,
        hidden: true,
        icon: perfil.dirImg + 'eliminar.png',
        iconCls: 'btn',
        handler: function () {
            Ext.MessageBox.show({
                title: 'Eliminar',
                msg: '¿Desea eliminar esta universidad?',
                buttons: Ext.MessageBox.YESNO,
                icon: Ext.MessageBox.QUESTION,
                fn: eliminarUniversidad
            });
        }
    });
    UCID.portal.cargarAcciones(window.parent.idFuncionalidad);

    Ext.define('Pais', {
        extend: 'Ext.data.Model',
        fields: ['idpais', 'nombrepais', 'codigopais', 'siglas']
    });

    var stcmbPais = Ext.create('Ext.data.Store', {
        model: 'Pais',
        autoLoad: true,
        proxy: {
            type: 'ajax',
            url: 'getPaises',
            reader: {
                type: 'json',
                root: 'datos'
            }
        }
    });

    var winAdicionargestuniversidades;
    var winModificargestuniversidades;
    var formgestuniversidadesAdd = Ext.create('Ext.form.Panel', {
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
            {
                xtype: 'textfield',
                fieldLabel: 'Universidad:',
                name: 'descripcion',
                labelAlign: 'top',
                anchor: '100%',
                allowBlank: false
            },
            {
                xtype: 'combobox',
                fieldLabel: 'País',
                name: 'idpais',
                allowBlank: false,
                editable: false,
                forceSelection: true,
                typeAhead: true,
                triggerAction: 'all',
                labelAlign: 'top',
                selectOnFocus: true,
                emptyText: "Seleccione el país",
                store: stcmbPais,
                queryMode: 'local',
                displayField: 'nombrepais',
                valueField: 'idpais'
            },

            {
                xtype: 'checkbox',
                fieldLabel: 'Activado:',
                name: 'estado',
                checked: true
            },
            {
                xtype: 'hidden',
                name: 'iduniversidad'
            }
        ]
    });

    var formgestuniversidadesMod = Ext.create('Ext.form.Panel', {
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
            {
                xtype: 'textfield',
                fieldLabel: 'Universidad:',
                name: 'descripcion',
                labelAlign: 'top',
                anchor: '100%',
                allowBlank: false
            },
            {
                xtype: 'combobox',
                fieldLabel: 'País',
                name: 'idpais',
                allowBlank: false,
                editable: false,
                forceSelection: true,
                typeAhead: true,
                triggerAction: 'all',
                labelAlign: 'top',
                selectOnFocus: true,
                emptyText: "Seleccione el país",
                store: stcmbPais,
                queryMode: 'local',
                displayField: 'nombrepais',
                valueField: 'idpais'
            },

            {
                xtype: 'checkbox',
                fieldLabel: 'Activado:',
                name: 'estado',
                checked: true
            },
            {
                xtype: 'hidden',
                name: 'iduniversidad'
            }
        ]
    });


    function mostFormgestuniversidades(opcion) {
        switch (opcion) {
            case 'add':
            {
                if (!winAdicionargestuniversidades) {
                    winAdicionargestuniversidades = Ext.create('Ext.Window', {
                        title: 'Adicionar universidad',
                        closeAction: 'hide',
                        width: 300,
                        height: 250,
                        constrain: true,
                        layout: 'fit',
                        buttons: [
                            {
                                text: 'Cancelar',
                                icon: perfil.dirImg + 'cancelar.png',
                                handler: function () {
                                    winAdicionargestuniversidades.hide();
                                }
                            },
                            {
                                text: 'Aplicar',
                                icon: perfil.dirImg + 'aplicar.png',
                                handler: function () {
                                    adicionarUniversidad("apl");
                                }
                            },
                            {
                                text: 'Aceptar',
                                icon: perfil.dirImg + 'aceptar.png',
                                handler: function () {
                                    adicionarUniversidad("aceptar");
                                }
                            }
                        ]
                    });
                }
                winAdicionargestuniversidades.add(formgestuniversidadesAdd);
                winAdicionargestuniversidades.show();
                winAdicionargestuniversidades.on('hide', function () {
                    formgestuniversidadesAdd.getForm().reset();
                }, this);
            }
                break;
            case 'mod':
            {
                if (!winModificargestuniversidades) {
                    winModificargestuniversidades = Ext.create('Ext.Window', {
                        title: 'Modificar universidad',
                        closeAction: 'hide',
                        width: 300,
                        height: 250,
                        constrain: true,
                        layout: 'fit',
                        buttons: [
                            {
                                text: 'Cancelar',
                                icon: perfil.dirImg + 'cancelar.png',
                                handler: function () {
                                    winModificargestuniversidades.hide();
                                }
                            },
                            {
                                text: 'Aceptar',
                                icon: perfil.dirImg + 'aceptar.png',
                                handler: function () {
                                    modificarUniversidad("aceptar");
                                }
                            }
                        ]
                    });
                }
                winModificargestuniversidades.add(formgestuniversidadesMod);
                winModificargestuniversidades.show();
                winModificargestuniversidades.on('hide', function () {
                    formgestuniversidadesMod.getForm().reset();
                }, this);
                formgestuniversidadesMod.getForm().loadRecord(sm.getLastSelected());
            }
                break;
        }
    }

    var stGpgestuniversidades = Ext.create('Ext.data.ArrayStore', {
        fields: [
            {
                name: "iduniversidad"
            },
            {
                name: 'descripcion'
            },
            {
                name: 'estado'
            },
            {
                name: 'nombrepais'
            },
            {
                name: 'idpais'
            }
        ],
        proxy: {
            type: 'ajax',
            url: 'cargarUniversidad',
            actionMethods: { //Esta Linea es necesaria para el metodo de llamada POST o GET
                read: 'POST'
            },
            reader: {
                type: 'json',
                id: 'iduniversidad',
                root: 'datos',
                totalProperty: 'cantidad'
            }
        }
    });
    var search = {
        xtype: 'searchfield',
        store: stGpgestuniversidades,
        emptyText: 'Filtrar por descripción',
        width: 400,
        padding: '0 0 0 5',
        labelWidth: 40,
        filterPropertysNames: ['descripcion']
    }
    /*Ext.create('Ext.form.field.Trigger', {
     store: stGpgestuniversidades,
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
    var Gpgestuniversidades = Ext.create('Ext.grid.Panel', {
        store: stGpgestuniversidades,
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
                dataIndex: 'iduniversidad'
            },
            {
                hidden: true,
                dataIndex: 'idpais'
            },
            {
                hidden: true,
                dataIndex: 'estado'
            },
            {
                header: 'Universidad',
                flex: 1,
                sortable: true,
                dataIndex: 'descripcion'
            },
            {
                header: 'País',
                flex: 1,
                sortable: true,
                dataIndex: 'nombrepais'
            }
        ],
        region: 'center',
        tbar: [btnAdicionargestuniversidades, btnModificargestuniversidades, btnEliminargestuniversidades, '->', search],
        bbar: Ext.create('Ext.toolbar.Paging', {
            displayInfo: true,
            store: stGpgestuniversidades
        })
    });
    var sm = Gpgestuniversidades.getSelectionModel();
    sm.setSelectionMode('MULTI');
    sm.on('selectionchange', function (sel, selectedRecord) {
        btnModificargestuniversidades.setDisabled(selectedRecord.length > 1);
        btnEliminargestuniversidades.setDisabled(selectedRecord.length == 0);

    });
    stGpgestuniversidades.load();
    function adicionarUniversidad(apl) {
        //si es la opción de aplicar
        if (formgestuniversidadesAdd.getForm().isValid()) {
            formgestuniversidadesAdd.getForm().submit({
                url: 'insertarUniversidad',
                waitMsg: perfil.etiquetas.lbMsgRegistrando,
                failure: function (form, action) {
                    if (action.result.codMsg === 1) {
                        formgestuniversidadesAdd.getForm().reset();
                        stGpgestuniversidades.reload();
                        if (apl === "aceptar")
                            winAdicionargestuniversidades.hide();
                    }


                }
            });
        }
    }

    function modificarUniversidad(apl) {
        //si es la opción de aplicar
        if (formgestuniversidadesMod.getForm().isValid()) {
            formgestuniversidadesMod.getForm().submit({
                url: 'modificarUniversidad',
                waitMsg: perfil.etiquetas.lbMsgModificando,
                failure: function (form, action) {
                    if (action.result.codMsg === 1) {
                        stGpgestuniversidades.reload();
                        if (apl === "aceptar")
                            winModificargestuniversidades.hide();
                    }

                }
            });
        }
    }

    function eliminarUniversidad(buttonId) {
        if (buttonId === "yes") {
            var ids = new Array();
            for (var i = 0; i < sm.getCount(); i++) {
                ids.push(sm.getSelection()[i].raw.iduniversidad)
            }
            Ext.Ajax.request({
                url: 'eliminarUniversidad',
                method: 'POST',
                params: {iduniv: Ext.JSON.encode(ids)},
                callback: function (options, success, response) {
                    responseData = Ext.decode(response.responseText);
                    if (responseData.codMsg === 1) {
                        stGpgestuniversidades.reload();
                        sm.deselect();
                    }
                }
            });
        }
    }

    //var general = Ext.create('Ext.panel.Panel', {layout: 'border', items: [Gpgestuniversidades]});
    var vpGestSistema = Ext.create('Ext.Viewport', {layout: 'fit', items: Gpgestuniversidades});
}
