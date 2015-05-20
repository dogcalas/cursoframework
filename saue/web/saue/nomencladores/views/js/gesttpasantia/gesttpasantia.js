var perfil = window.parent.UCID.portal.perfil;
UCID.portal.cargarEtiquetas('gesttpasantia', function () {
    cargarInterfaz();
});
Ext.QuickTips.init();
function cargarInterfaz() {
    var btnAdicionartpasantia = Ext.create('Ext.Button', {
        id: 'btnAgrtpasantia',
        text: '<b>' + perfil.etiquetas.lbBtnAdicionar + '</b>',
        icon: perfil.dirImg + 'adicionar.png',
        iconCls: 'btn',
        hidden: true,
        handler: function () {
            mostFormtpasantia('add');
        }
    });
    var btnModificartpasantia = Ext.create('Ext.Button', {
        id: 'btnModtpasantia',
        text: '<b>' + perfil.etiquetas.lbBtnModificar + '</b>',
        disabled: true,
        hidden: true,
        icon: perfil.dirImg + 'modificar.png',
        iconCls: 'btn',
        handler: function () {
            mostFormtpasantia('mod');
        }
    });
    var btnEliminartpasantia = Ext.create('Ext.Button', {
        id: 'btnElitpasantia',
        text: '<b>' + perfil.etiquetas.lbBtnEliminar + '</b>',
        disabled: true,
        hidden: true,
        icon: perfil.dirImg + 'eliminar.png',
        iconCls: 'btn',
        handler: function () {
            Ext.MessageBox.show({
                title: 'Eliminar',
                msg: '¿Desea eliminar este tipo de pasantía?',
                buttons: Ext.MessageBox.YESNO,
                icon: Ext.MessageBox.QUESTION,
                fn: eliminarTpasantia
            });
        }
    });
    UCID.portal.cargarAcciones(window.parent.idFuncionalidad);
    var winAdicionartpasantia;
    var winModificartpasantia;
    var formtpasantia = Ext.create('Ext.form.Panel', {
        frame: true,
        bodyStyle: 'padding:5px 5px 0',
        width: 350,
        fieldDefaults: {
            msgTarget: 'side',
            labelWidth: 90
        },
        defaults: {
            anchor: '100%'
        },
        items: [
            {xtype: 'textfield',
                fieldLabel: 'Tipo de pasant&iacute;a:',
                name: 'descripcion',
                labelAlign: 'top',
                allowBlank: false
            },
            //////////////////////////////////////////////////////////////         agregado
            {xtype: 'checkbox',
                fieldLabel: 'Activado:',
                name: 'estado',
                checked: true
            },
            //////////////////////////////////////////////////////////////         agregado
            {xtype: 'hidden',
                name: 'idtipopractica'
            }
        ]
    });

    function mostFormtpasantia(opcion) {
        switch (opcion) {
            case 'add':
            {
                winAdicionartpasantia = Ext.create('Ext.Window', {
                    title: 'Adicionar tipo de pasant&iacute;a',
                    closeAction: 'hide',
                    width: 350,
                    height: 150,
                    constrain: true,
                    layout: 'fit',
                    items: [formtpasantia],
                    buttons: [
                        {
                            text: 'Cancelar',
                            icon: perfil.dirImg + 'cancelar.png',
                            handler: function () {
                                winAdicionartpasantia.hide();
                            }
                        },
                        {
                            text: 'Aplicar',
                            icon: perfil.dirImg + 'aplicar.png',
                            handler: function () {
                                adicionarTpasantia("apl");
                            }
                        },
                        {
                            text: 'Aceptar',
                            icon: perfil.dirImg + 'aceptar.png',
                            handler: function () {
                                adicionarTpasantia("aceptar");
                            }
                        }
                    ]
                });
                winAdicionartpasantia.show();
                winAdicionartpasantia.on('hide', function () {
                    formtpasantia.getForm().reset();
                }, this);
            }
                break;
            case 'mod':
            {
                winModificartpasantia = Ext.create('Ext.Window', {
                    title: 'Modificar tipo de pasant&iacute;a',
                    closeAction: 'hide',
                    width: 350,
                    height: 150,
                    constrain: true,
                    layout: 'fit',
                    items: [formtpasantia],
                    buttons: [
                        {
                            text: 'Cancelar',
                            icon: perfil.dirImg + 'cancelar.png',
                            handler: function () {
                                winModificartpasantia.hide();
                            }
                        },
                        {
                            text: 'Aceptar',
                            icon: perfil.dirImg + 'aceptar.png',
                            handler: function () {
                                modificarTpasantia("aceptar");
                            }
                        }
                    ]
                });
                winModificartpasantia.add(formtpasantia);
                winModificartpasantia.show();
                winModificartpasantia.on('hide', function () {
                    formtpasantia.getForm().reset();
                }, this);
                formtpasantia.getForm().loadRecord(sm.getLastSelected());
            }
                break;
        }
    }

    var stGptpasantia = Ext.create('Ext.data.ArrayStore', {
        fields: [
            {
                name: "idtipopractica"
            },
            {
                name: 'descripcion'
            },
            //////////////////////////////////////////////////////////////         agregado
            {
                name: 'estado'
            }
            //////////////////////////////////////////////////////////////         agregado}
        ],
        proxy: {
            type: 'ajax',
            url: 'cargarTpasantia',
            reader: {
                type: 'json',
                id: 'idtipopractica',
                root: 'datos'
            }
        }
    });
    var search = Ext.create('Ext.form.field.Trigger', {
        store: stGptpasantia,
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
    var Gptpasantia = Ext.create('Ext.grid.Panel', {
        store: stGptpasantia,
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
                dataIndex: 'idtipopractica'
            },
            {
                hidden: true,
                dataIndex: 'estado'
            },
            {
                header: 'Tipo de pasant&iacute;a',
                flex: 1,
                sortable: true,
                dataIndex: 'descripcion',
                field: {
                    type: 'textfield',
                    allowBlank: false
                }
            }
        ],
        region: 'center',
        tbar: [btnAdicionartpasantia, btnModificartpasantia, btnEliminartpasantia, '->', search]
    });
    var sm = Gptpasantia.getSelectionModel();
    sm.on('selectionchange', function (sel, selectedRecord) {
        if (selectedRecord.length) {
            btnModificartpasantia.enable();
            btnEliminartpasantia.enable();
        } else {
            btnModificartpasantia.disable();
            btnEliminartpasantia.disable();
        }
    });
    stGptpasantia.load();
    function adicionarTpasantia(apl) {
        //si es la opción de aplicar
        if (formtpasantia.getForm().isValid()) {
            formtpasantia.getForm().submit({
                url: 'insertarTpasantia',
                waitMsg: perfil.etiquetas.lbMsgRegistrando,
                failure: function (form, action) {
                    if (action.result.codMsg === 1) {
                        formtpasantia.getForm().reset();
                        stGptpasantia.reload();
                        if (apl === "aceptar")
                            winAdicionartpasantia.hide();
                    }


                }
            });
        }
    }

    function modificarTpasantia(apl) {
        //si es la opción de aplicar
        if (formtpasantia.getForm().isValid()) {
            formtpasantia.getForm().submit({
                url: 'modificarTpasantia',
                waitMsg: perfil.etiquetas.lbMsgModificando,
                failure: function (form, action) {
                    if (action.result.codMsg === 1) {
                        stGptpasantia.reload();
                        if (apl === "aceptar")
                            winModificartpasantia.hide();
                    }

                }
            });
        }
    }

    function eliminarTpasantia(buttonId) {
        if (buttonId === "yes") {
            Ext.Ajax.request({
                url: 'eliminarTpasantia',
                method: 'POST',
                params: {idtipopractica: sm.getLastSelected().data.idtipopractica},
                callback: function (options, success, response) {
                    responseData = Ext.decode(response.responseText);
                    if (responseData.codMsg === 1) {
                        stGptpasantia.reload();
                        sm.deselect();
                    }
                }
            });
        }
    }

    var general = Ext.create('Ext.panel.Panel', {layout: 'border', items: [Gptpasantia]});
    var vpGestSistema = Ext.create('Ext.Viewport', {layout: 'fit', items: general});
}