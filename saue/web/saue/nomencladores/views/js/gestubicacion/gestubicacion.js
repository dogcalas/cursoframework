var perfil = window.parent.UCID.portal.perfil;
UCID.portal.cargarEtiquetas('gestubicacion', function () {
    cargarInterfaz();
});
Ext.QuickTips.init();
function cargarInterfaz() {
    var btnAdicionargestubicacion = Ext.create('Ext.Button', {
        id: 'btnAgrgestubicacion',
        text: '<b>' + perfil.etiquetas.lbBtnAdicionar + '</b>',
        icon: perfil.dirImg + 'adicionar.png',
        iconCls: 'btn',
        hidden: true,
        handler: function () {
            mostFormgestubicacion('add');
        }
    });
    var btnModificargestubicacion = Ext.create('Ext.Button', {
        id: 'btnModgestubicacion',
        text: '<b>' + perfil.etiquetas.lbBtnModificar + '</b>',
        disabled: true,
        hidden: true,
        icon: perfil.dirImg + 'modificar.png',
        iconCls: 'btn',
        handler: function () {
            mostFormgestubicacion('mod');
        }
    });
    var btnEliminargestubicacion = Ext.create('Ext.Button', {
        id: 'btnEligestubicacion',
        text: '<b>' + perfil.etiquetas.lbBtnEliminar + '</b>',
        disabled: true,
        hidden: true,
        icon: perfil.dirImg + 'eliminar.png',
        iconCls: 'btn',
        handler: function () {
            Ext.MessageBox.show({
                title: 'Eliminar',
                msg: '¿Desea eliminar esta ubicación?',
                buttons: Ext.MessageBox.YESNO,
                icon: Ext.MessageBox.QUESTION,
                fn: eliminarUbicacion
            });
        }
    });
    UCID.portal.cargarAcciones(window.parent.idFuncionalidad);
    var winAdicionargestubicacion;
    var winModificargestubicacion;
    var formgestubicacion = Ext.create('Ext.form.Panel', {
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
                fieldLabel: 'Ubicaci&oacute;n:',
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
                name: 'idubicacion'
            }
        ]
    });

    function mostFormgestubicacion(opcion) {
        switch (opcion) {
            case 'add':
            {
                winAdicionargestubicacion = Ext.create('Ext.Window', {
                    title: 'Adicionar ubicaci&oacute;n',
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
                                winAdicionargestubicacion.hide();
                            }
                        },
                        {
                            text: 'Aplicar',
                            icon: perfil.dirImg + 'aplicar.png',
                            handler: function () {
                                adicionarUbicacion("apl");
                            }
                        },
                        {
                            text: 'Aceptar',
                            icon: perfil.dirImg + 'aceptar.png',
                            handler: function () {
                                adicionarUbicacion("aceptar");
                            }
                        }
                    ]
                });
                winAdicionargestubicacion.add(formgestubicacion);
                winAdicionargestubicacion.show();
                winAdicionargestubicacion.on('hide', function () {
                    formgestubicacion.getForm().reset();
                }, this);
            }
                break;
            case 'mod':
            {
                winModificargestubicacion = Ext.create('Ext.Window', {
                    title: 'Modificar ubicaci&oacute;n',
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
                                winModificargestubicacion.hide();
                            }
                        },
                        {
                            text: 'Aceptar',
                            icon: perfil.dirImg + 'aceptar.png',
                            handler: function () {
                                modificarUbicacion("aceptar");
                            }
                        }
                    ]
                });
                winModificargestubicacion.add(formgestubicacion);
                winModificargestubicacion.show();
                winModificargestubicacion.on('hide', function () {
                    formgestubicacion.getForm().reset();
                }, this);
                formgestubicacion.getForm().loadRecord(sm.getLastSelected());
            }
                break;
        }
    }

    var stGpgestubicacion = Ext.create('Ext.data.ArrayStore', {
        fields: [
            {
                name: "idubicacion"
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
            url: 'cargarUbicacion',
            reader: {
                type: 'json',
                id: 'idubicacion',
                root: 'datos'
            }
        }
    });
    var search = Ext.create('Ext.form.field.Trigger', {
        store: stGpgestubicacion,
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
    var Gpgestubicacion = Ext.create('Ext.grid.Panel', {
        store: stGpgestubicacion,
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
                dataIndex: 'idubicacion'
            },
            {hidden: true,
                dataIndex: 'estado'
            },
            {
                header: 'Ubicaci&oacute;n',
                flex: 1,
                sortable: true,
                dataIndex: 'descripcion'
            }
        ],
        region: 'center',
        tbar: [btnAdicionargestubicacion, btnModificargestubicacion, btnEliminargestubicacion, '->', search]
    });
    var sm = Gpgestubicacion.getSelectionModel();
    sm.on('selectionchange', function (sel, selectedRecord) {
        if (selectedRecord.length) {
            btnModificargestubicacion.enable();
            btnEliminargestubicacion.enable();
        } else {
            btnModificargestubicacion.disable();
            btnEliminargestubicacion.disable();
        }
    });
    stGpgestubicacion.load();
    function adicionarUbicacion(apl) {
        //si es la opción de aplicar
        if (formgestubicacion.getForm().isValid()) {
            formgestubicacion.getForm().submit({
                url: 'insertarUbicacion',
                waitMsg: perfil.etiquetas.lbMsgRegistrando,
                failure: function (form, action) {
                    if (action.result.codMsg === 1) {
                        formgestubicacion.getForm().reset();
                        stGpgestubicacion.reload();
                        if (apl === "aceptar")
                            winAdicionargestubicacion.hide();
                    }


                }
            });
        }
    }

    function modificarUbicacion(apl) {
        //si es la opción de aplicar
        if (formgestubicacion.getForm().isValid()) {
            formgestubicacion.getForm().submit({
                url: 'modificarUbicacion',
                waitMsg: perfil.etiquetas.lbMsgModificando,
                failure: function (form, action) {
                    if (action.result.codMsg === 1) {
                        stGpgestubicacion.reload();
                        if (apl === "aceptar")
                            winModificargestubicacion.hide();
                    }

                }
            });
        }
    }

    function eliminarUbicacion(buttonId) {
        if (buttonId === "yes") {
            Ext.Ajax.request({
                url: 'eliminarUbicacion',
                method: 'POST',
                params: {idubicacion: sm.getLastSelected().data.idubicacion},
                callback: function (options, success, response) {
                    responseData = Ext.decode(response.responseText);
                    if (responseData.codMsg === 1) {
                        stGpgestubicacion.reload();
                        sm.deselect();
                    }
                }
            });
        }
    }

    var general = Ext.create('Ext.panel.Panel', {layout: 'border', items: [Gpgestubicacion]});
    var vpGestSistema = Ext.create('Ext.Viewport', {layout: 'fit', items: general});
}