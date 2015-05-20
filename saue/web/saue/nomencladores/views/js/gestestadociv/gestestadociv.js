var perfil = window.parent.UCID.portal.perfil;
UCID.portal.cargarEtiquetas('gestestadociv', function () {
    cargarInterfaz();
});
Ext.QuickTips.init();
function cargarInterfaz() {
    var btnAdicionargestestadociv = Ext.create('Ext.Button', {
        id: 'btnAgrgestestadociv',
        text: '<b>' + perfil.etiquetas.lbBtnAdicionar + '</b>',
        icon: perfil.dirImg + 'adicionar.png',
        iconCls: 'btn',
        hidden: true,
        handler: function () {
            mostFormgestestadociv('add');
        }
    });
    var btnModificargestestadociv = Ext.create('Ext.Button', {
        id: 'btnModgestestadociv',
        text: '<b>' + perfil.etiquetas.lbBtnModificar + '</b>',
        disabled: true,
        hidden: true,
        icon: perfil.dirImg + 'modificar.png',
        iconCls: 'btn',
        handler: function () {
            mostFormgestestadociv('mod');
        }
    });
    var btnEliminargestestadociv = Ext.create('Ext.Button', {
        id: 'btnEligestestadociv',
        text: '<b>' + perfil.etiquetas.lbBtnEliminar + '</b>',
        disabled: true,
        hidden: true,
        icon: perfil.dirImg + 'eliminar.png',
        iconCls: 'btn',
        handler: function () {
            Ext.MessageBox.show({
                title: 'Eliminar',
                msg: '¿Desea eliminar este estado civil?',
                buttons: Ext.MessageBox.YESNO,
                icon: Ext.MessageBox.QUESTION,
                fn: eliminarEstadoCivil
            });
        }
    });
    UCID.portal.cargarAcciones(window.parent.idFuncionalidad);
    var winAdicionargestestadociv;
    var winModificargestestadociv;
    var formgestestadociv = Ext.create('Ext.form.Panel', {
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
                fieldLabel: 'Estado civil:',
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
                name: 'idestadocivil'
            }
        ]
    });

    function mostFormgestestadociv(opcion) {
        switch (opcion) {
            case 'add':
            {
                winAdicionargestestadociv = Ext.create('Ext.Window', {
                    title: 'Adicionar estado civil',
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
                                winAdicionargestestadociv.hide();
                            }
                        },
                        {
                            text: 'Aplicar',
                            icon: perfil.dirImg + 'aplicar.png',
                            handler: function () {
                                adicionarEstadoCivil("apl");
                            }
                        },
                        {
                            text: 'Aceptar',
                            icon: perfil.dirImg + 'aceptar.png',
                            handler: function () {
                                adicionarEstadoCivil("aceptar");
                            }
                        }
                    ]
                });
                winAdicionargestestadociv.add(formgestestadociv);
                winAdicionargestestadociv.show();
                winAdicionargestestadociv.on('hide', function () {
                    formgestestadociv.getForm().reset();
                }, this);
            }
                break;
            case 'mod':
            {
                winModificargestestadociv = Ext.create('Ext.Window', {
                    title: 'Modificar estado civil',
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
                                winModificargestestadociv.hide();
                            }
                        },
                        {
                            text: 'Aceptar',
                            icon: perfil.dirImg + 'aceptar.png',
                            handler: function () {
                                modificarEstadoCivil("aceptar");
                            }
                        }
                    ]
                });
                winModificargestestadociv.add(formgestestadociv);
                winModificargestestadociv.show();
                winModificargestestadociv.on('hide', function () {
                    formgestestadociv.getForm().reset();
                }, this);
                formgestestadociv.getForm().loadRecord(sm.getLastSelected());
            }
                break;
        }
    }

    var stGpgestestadociv = Ext.create('Ext.data.ArrayStore', {
        fields: [
            {
                name: "idestadocivil"
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
            url: 'cargarEstadoCivil',
            reader: {
                type: 'json',
                id: 'idestadocivil',
                root: 'datos'
            }
        }
    });
    var search = Ext.create('Ext.form.field.Trigger', {
        store: stGpgestestadociv,
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
    var Gpgestestadociv = Ext.create('Ext.grid.Panel', {
        store: stGpgestestadociv,
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
                dataIndex: 'idestadocivil'
            },
            {hidden: true,
                dataIndex: 'estado'
            },
            {
                header: 'Estado civil',
                flex: 1,
                sortable: true,
                dataIndex: 'descripcion'
            }
        ],
        region: 'center',
        tbar: [btnAdicionargestestadociv, btnModificargestestadociv, btnEliminargestestadociv, '->', search]
    });
    var sm = Gpgestestadociv.getSelectionModel();
    sm.on('selectionchange', function (sel, selectedRecord) {
        if (selectedRecord.length) {
            btnModificargestestadociv.enable();
            btnEliminargestestadociv.enable();
        } else {
            btnModificargestestadociv.disable();
            btnEliminargestestadociv.disable();
        }
    });
    stGpgestestadociv.load();
    function adicionarEstadoCivil(apl) {
        //si es la opción de aplicar
        if (formgestestadociv.getForm().isValid()) {
            formgestestadociv.getForm().submit({
                url: 'insertarEstadoCivil',
                waitMsg: perfil.etiquetas.lbMsgRegistrando,
                failure: function (form, action) {
                    if (action.result.codMsg === 1) {
                        formgestestadociv.getForm().reset();
                        stGpgestestadociv.reload();
                        if (apl === "aceptar")
                            winAdicionargestestadociv.hide();
                    }


                }
            });
        }
    }

    function modificarEstadoCivil(apl) {
        //si es la opción de aplicar
        if (formgestestadociv.getForm().isValid()) {
            formgestestadociv.getForm().submit({
                url: 'modificarEstadoCivil',
                waitMsg: perfil.etiquetas.lbMsgModificando,
                failure: function (form, action) {
                    if (action.result.codMsg === 1) {
                        stGpgestestadociv.reload();
                        if (apl === "aceptar")
                            winModificargestestadociv.hide();
                    }

                }
            });
        }
    }

    function eliminarEstadoCivil(buttonId) {
        if (buttonId === "yes") {
            Ext.Ajax.request({
                url: 'eliminarEstadoCivil',
                method: 'POST',
                params: {idestadocivil: sm.getLastSelected().data.idestadocivil},
                callback: function (options, success, response) {
                    responseData = Ext.decode(response.responseText);
                    if (responseData.codMsg === 1) {
                        stGpgestestadociv.reload();
                        sm.deselect();
                    }
                }
            });
        }
    }

    //var general = Ext.create('Ext.panel.Panel', {layout: 'border', items: [Gpgestestadociv]});
    var vpGestSistema = Ext.create('Ext.Viewport', {layout: 'fit', items: Gpgestestadociv});
}