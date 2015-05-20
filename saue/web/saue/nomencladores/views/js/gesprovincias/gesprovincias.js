var perfil = window.parent.UCID.portal.perfil;
UCID.portal.cargarEtiquetas('gesprovincias', function () {
    cargarInterfaz();
});
Ext.QuickTips.init();
function cargarInterfaz() {

    var btnAdicionarprovincias = Ext.create('Ext.Button', {
        id: 'btnAgrprovincias',
        text: '<b>' + perfil.etiquetas.lbBtnAdicionar + '</b>',
        icon: perfil.dirImg + 'adicionar.png',
        iconCls: 'btn',
        hidden: true,
        handler: function () {
            mostFormprovincias('add');
        }
    });
    var btnModificarprovincias = Ext.create('Ext.Button', {
        id: 'btnModprovincias',
        text: '<b>' + perfil.etiquetas.lbBtnModificar + '</b>',
        disabled: true,
        hidden: true,
        icon: perfil.dirImg + 'modificar.png',
        iconCls: 'btn',
        handler: function () {
            mostFormprovincias('mod');
        }
    });
    var btnEliminarprovincias = Ext.create('Ext.Button', {
        id: 'btnEliprovincias',
        text: '<b>' + perfil.etiquetas.lbBtnEliminar + '</b>',
        disabled: true,
        hidden: true,
        icon: perfil.dirImg + 'eliminar.png',
        iconCls: 'btn',
        handler: function () {
            Ext.MessageBox.show({
                title: 'Eliminar',
                msg: '¿Desea eliminar esta provincia?',
                buttons: Ext.MessageBox.YESNO,
                icon: Ext.MessageBox.QUESTION,
                fn: eliminarProvincia
            });
        }
    });
    UCID.portal.cargarAcciones(window.parent.idFuncionalidad);
    var winAdicionarprovincias;
    var winModificarprovincias;
    var formprovincias = Ext.create('Ext.form.Panel', {
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
        items: [{
            xtype: 'textfield',
            fieldLabel: 'Provincia:',
            name: 'descripcion',
            anchor: '100%',
            labelAlign: 'top',
            allowBlank: false,
            allowOnlyWhitespace: false
        },
            {
                xtype: 'checkbox',
                fieldLabel: 'Activado:',
                name: 'estado',
                checked: true
            },
            {
                xtype: 'hidden',
                name: 'idprovincia'
            }]
    });

    function mostFormprovincias(opcion) {
        switch (opcion) {
            case 'add':
            {
                winAdicionarprovincias = Ext.create('Ext.Window', {
                    title: 'Adicionar provincia',
                    closeAction: 'hide',
                    width: 300,
                    height: 150,
                    x: 220,
                    y: 100,
                    constrain: true,
                    layout: 'fit',
                    buttons: [{
                        text: 'Cancelar',
                        icon: perfil.dirImg + 'cancelar.png',
                        handler: function () {
                            winAdicionarprovincias.hide();
                        }
                    }, {
                        text: 'Aplicar',
                        icon: perfil.dirImg + 'aplicar.png',
                        handler: function () {
                            adicionarProvincia("apl");
                        }
                    }, {
                        text: 'Aceptar',
                        icon: perfil.dirImg + 'aceptar.png',
                        handler: function () {
                            adicionarProvincia("aceptar");
                        }
                    }
                    ]
                });
                winAdicionarprovincias.add(formprovincias);
                winAdicionarprovincias.show();
                winAdicionarprovincias.on('hide', function () {
                    formprovincias.getForm().reset();
                }, this);
            }
                break;
            case 'mod':
            {
                winModificarprovincias = Ext.create('Ext.Window', {
                    title: 'Modificar provincia',
                    closeAction: 'hide',
                    width: 300,
                    height: 150,
                    x: 220,
                    y: 100,
                    constrain: true,
                    layout: 'fit',
                    buttons: [
                        {
                            text: 'Cancelar',
                            icon: perfil.dirImg + 'cancelar.png',
                            handler: function () {
                                winModificarprovincias.hide();
                            }
                        },
                        {
                            text: 'Aceptar',
                            icon: perfil.dirImg + 'aceptar.png',
                            handler: function () {
                                modificarProvincia("aceptar");
                            }
                        }
                    ]
                });
                winModificarprovincias.add(formprovincias);
                winModificarprovincias.show();
                winModificarprovincias.on('hide', function () {
                    formprovincias.getForm().reset();
                }, this);
                formprovincias.getForm().loadRecord(sm.getLastSelected());
            }
                break;
        }
    }

    var stGpprovincias = Ext.create('Ext.data.ArrayStore', {
        fields: [{
            name: "idprovincia"
        }, {
            name: 'estado'
        }, {
            name: 'descripcion'
        }],
        proxy: {
            type: 'ajax',
            url: 'cargarProvincia',
            actionMethods: { //Esta Linea es necesaria para el metodo de llamada POST o GET
                read: 'POST'
            },
            reader: {
                type: 'json',
                id: 'idprovincia',
                root: 'datos',
                totalProperty: 'cantidad'
            }
        }
    });
    var search =
    {
        xtype: 'searchfield',
        store: stGpprovincias,
        emptyText: 'Filtrar por descripción',
        width: 400,
        padding: '0 0 0 5',
        labelWidth: 40,
        filterPropertysNames: ['descripcion']
    };

    /*Ext.create('Ext.form.field.Trigger', {
     store: stGpprovincias,
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
     me.store.filter({
     filterFn: function (item) {
     return item.get("descripcion").toLowerCase().match(me.getValue().toLowerCase());
     }
     });
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


    var Gpprovincias = Ext.create('Ext.grid.Panel', {
        store: stGpprovincias,
        stateId: 'stateGrid',
        columnLines: true,
        viewConfig: {
            getRowClass: function (record, rowIndex, rowParams, store) {
                if (record.data.estado == false)
                    return 'FilaRoja';
            }
        },
        columns: [{
            hidden: true,
            dataIndex: 'idprovincia'
        }, {
            hidden: true,
            dataIndex: 'estado'
        }, {
            header: 'Provincia',
            flex: 1,
            sortable: true,
            dataIndex: 'descripcion',
            field: {
                type: 'textfield',
                allowBlank: false
            }
        }],
        region: 'center',
        tbar: [btnAdicionarprovincias, btnModificarprovincias, btnEliminarprovincias, '->', search],
        bbar: Ext.create('Ext.toolbar.Paging', {
            displayInfo: true,
            store: stGpprovincias
        })
    });
    var sm = Gpprovincias.getSelectionModel();
    sm.setSelectionMode('MULTI');
    sm.on('selectionchange', function (sel, selectedRecord) {
        btnModificarprovincias.setDisabled(selectedRecord.length > 1);
        btnEliminarprovincias.setDisabled(selectedRecord.length == 0);

    });
    stGpprovincias.load();
    function adicionarProvincia(apl) {
        //si es la opción de aplicar
        if (formprovincias.getForm().isValid()) {
            formprovincias.getForm().submit({
                url: 'insertarProvincia',
                waitMsg: perfil.etiquetas.lbMsgRegistrando,
                failure: function (form, action) {
                    if (action.result.codMsg === 1) {
                        formprovincias.getForm().reset();
                        stGpprovincias.reload();
                        if (apl === "aceptar")
                            winAdicionarprovincias.hide();
                    }


                }
            });
        }
    }

    function modificarProvincia(apl) {
        //si es la opción de aplicar
        if (formprovincias.getForm().isValid()) {
            formprovincias.getForm().submit({
                url: 'modificarProvincia',
                waitMsg: perfil.etiquetas.lbMsgModificando,
                failure: function (form, action) {
                    if (action.result.codMsg === 1) {
                        stGpprovincias.reload();
                        if (apl === "aceptar")
                            winModificarprovincias.hide();
                    }

                }
            });
        }
    }

    function eliminarProvincia(buttonId) {
        if (buttonId === "yes") {
            var ids = new Array();
            for (var i = 0; i < sm.getCount(); i++) {
                ids.push(sm.getSelection()[i].raw.idprovincia)
            }
            Ext.Ajax.request({
                url: 'eliminarProvincia',
                method: 'POST',
                params: {idprov: Ext.JSON.encode(ids)},
                callback: function (options, success, response) {
                    responseData = Ext.decode(response.responseText);
                    if (responseData.codMsg === 1) {
                        stGpprovincias.reload();
                        sm.deselect();
                    }
                }
            });
        }
    }

    //var general = Ext.create('Ext.panel.Panel', {layout: 'border', items: [Gpprovincias]});
    var vpGestSistema = Ext.create('Ext.Viewport', {layout: 'fit', items: Gpprovincias});
}