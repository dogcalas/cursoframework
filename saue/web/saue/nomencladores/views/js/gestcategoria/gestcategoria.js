var perfil = window.parent.UCID.portal.perfil;
UCID.portal.cargarEtiquetas('gestcategoria', function () {
    cargarInterfaz();
});
Ext.QuickTips.init();
function cargarInterfaz() {
    var btnAdicionargestcategoria = Ext.create('Ext.Button', {
        id: 'btnAgrgestcategoria',
        text: '<b>' + perfil.etiquetas.lbBtnAdicionar + '</b>',
        icon: perfil.dirImg + 'adicionar.png',
        iconCls: 'btn',
        hidden: true,
        handler: function () {
            mostFormgestcategoria('add');
        }
    });
    var btnModificargestcategoria = Ext.create('Ext.Button', {
        id: 'btnModgestcategoria',
        text: '<b>' + perfil.etiquetas.lbBtnModificar + '</b>',
        disabled: true,
        hidden: true,
        icon: perfil.dirImg + 'modificar.png',
        iconCls: 'btn',
        handler: function () {
            mostFormgestcategoria('mod');
        }
    });
    var btnEliminargestcategoria = Ext.create('Ext.Button', {
        id: 'btnEligestcategoria',
        text: '<b>' + perfil.etiquetas.lbBtnEliminar + '</b>',
        disabled: true,
        hidden: true,
        icon: perfil.dirImg + 'eliminar.png',
        iconCls: 'btn',
        handler: function () {
            Ext.MessageBox.show({
                title: 'Eliminar',
                msg: '¿Desea eliminar esta categoría?',
                buttons: Ext.MessageBox.YESNO,
                icon: Ext.MessageBox.QUESTION,
                fn: eliminarCategoria
            });
        }
    });
    UCID.portal.cargarAcciones(window.parent.idFuncionalidad);
    var winAdicionargestcategoria;
    var winModificargestcategoria;
    var formgestcategoria = Ext.create('Ext.form.Panel', {
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
                fieldLabel: 'Categoria docente:',
                name: 'descripcion',
                anchor: '100%',
                labelAlign: 'top',
                allowBlank: false
            },
            {xtype: 'checkbox',
                fieldLabel: 'Activado:',
                name: 'estado',
                checked: true
            },
            {xtype: 'hidden',
                name: 'idcategoria'
            }
        ]
    });

    function mostFormgestcategoria(opcion) {
        switch (opcion) {
            case 'add':
            {
                winAdicionargestcategoria = Ext.create('Ext.Window', {
                    title: 'Adicionar categoria docente',
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
                                winAdicionargestcategoria.hide();
                            }
                        },
                        {
                            text: 'Aplicar',
                            icon: perfil.dirImg + 'aplicar.png',
                            handler: function () {
                                adicionarCategoria("apl");
                            }
                        },
                        {
                            text: 'Aceptar',
                            icon: perfil.dirImg + 'aceptar.png',
                            handler: function () {
                                adicionarCategoria("aceptar");
                            }
                        }
                    ]
                });
                winAdicionargestcategoria.add(formgestcategoria);
                winAdicionargestcategoria.show();
                winAdicionargestcategoria.on('hide', function () {
                    formgestcategoria.getForm().reset();
                }, this);
            }
                break;
            case 'mod':
            {
                winModificargestcategoria = Ext.create('Ext.Window', {
                    title: 'Modificar categoria docente',
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
                                winModificargestcategoria.hide();
                            }
                        },
                        {
                            text: 'Aceptar',
                            icon: perfil.dirImg + 'aceptar.png',
                            handler: function () {
                                modificarCategoria("aceptar");
                            }
                        }
                    ]
                });
                winModificargestcategoria.add(formgestcategoria);
                winModificargestcategoria.show();
                winModificargestcategoria.on('hide', function () {
                    formgestcategoria.getForm().reset();
                }, this);
                formgestcategoria.getForm().loadRecord(sm.getLastSelected());
            }
                break;
        }
    }

    var stGpgestcategoria = Ext.create('Ext.data.ArrayStore', {
        fields: [
            {
                name: "idcategoria"
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
            url: 'cargarCategoria',
            reader: {
                type: 'json',
                id: 'idcategoria',
                root: 'datos'
            }
        }
    });
    var search = Ext.create('Ext.form.field.Trigger', {
        store: stGpgestcategoria,
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
    });
    var Gpgestcategoria = Ext.create('Ext.grid.Panel', {
        store: stGpgestcategoria,
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
                dataIndex: 'idcategoria'
            },
            {hidden: true,
                dataIndex: 'estado'
            },
            {
                header: 'Categoria docente',
                flex: 1,
                sortable: true,
                dataIndex: 'descripcion'
            }
        ],
        region: 'center',
        tbar: [btnAdicionargestcategoria, btnModificargestcategoria, btnEliminargestcategoria, '->', search]
    });

    var sm = Gpgestcategoria.getSelectionModel();
    sm.setSelectionMode('MULTI');
    sm.on('selectionchange', function (sel, selectedRecord) {
        btnModificargestcategoria.setDisabled(selectedRecord.length>1);
        btnEliminargestcategoria.setDisabled(selectedRecord.length==0);

    });
    stGpgestcategoria.load();
    function adicionarCategoria(apl) {
        //si es la opción de aplicar
        if (formgestcategoria.getForm().isValid()) {
            formgestcategoria.getForm().submit({
                url: 'insertarCategoria',
                waitMsg: perfil.etiquetas.lbMsgRegistrando,
                failure: function (form, action) {
                    if (action.result.codMsg === 1) {
                        formgestcategoria.getForm().reset();
                        stGpgestcategoria.reload();
                        if (apl === "aceptar")
                            winAdicionargestcategoria.hide();
                    }


                }
            });
        }
    }

    function modificarCategoria(apl) {
        //si es la opción de aplicar
        if (formgestcategoria.getForm().isValid()) {
            formgestcategoria.getForm().submit({
                url: 'modificarCategoria',
                waitMsg: perfil.etiquetas.lbMsgModificando,
                failure: function (form, action) {
                    if (action.result.codMsg === 1) {
                        stGpgestcategoria.reload();
                        if (apl === "aceptar")
                            winModificargestcategoria.hide();
                    }

                }
            });
        }
    }

    function eliminarCategoria(buttonId) {
        if (buttonId === "yes") {
            var ids= new Array();
            for(var i=0; i<sm.getCount(); i++){
                ids.push(sm.getSelection()[i].raw.idcategoria)
            }
            Ext.Ajax.request({
                url: 'eliminarCategoria',
                method: 'POST',
                params: {idcateg: Ext.JSON.encode(ids)},
                callback: function (options, success, response) {
                    responseData = Ext.decode(response.responseText);
                    if (responseData.codMsg === 1) {
                        stGpgestcategoria.reload();
                        sm.deselect();
                    }
                }
            });
        }
    }

    var general = Ext.create('Ext.panel.Panel', {layout: 'border', items: [Gpgestcategoria]});
    var vpGestSistema = Ext.create('Ext.Viewport', {layout: 'fit', items: general});
}