var perfil = window.parent.UCID.portal.perfil;
UCID.portal.cargarEtiquetas('gesttaprobado', function () {
    cargarInterfaz();
});
Ext.QuickTips.init();
function cargarInterfaz() {
    var btnAdicionargesttipoaprobado = Ext.create('Ext.Button', {
        id: 'btnAgrgesttipoaprobado',
        text: '<b>' + perfil.etiquetas.lbBtnAdicionar + '</b>',
        icon: perfil.dirImg + 'adicionar.png',
        iconCls: 'btn',
        hidden: true,
        handler: function () {
            mostFormgesttipoaprobado('add');
        }
    });
    var btnModificargesttipoaprobado = Ext.create('Ext.Button', {
        id: 'btnModgesttipoaprobado',
        text: '<b>' + perfil.etiquetas.lbBtnModificar + '</b>',
        disabled: true,
        hidden: true,
        icon: perfil.dirImg + 'modificar.png',
        iconCls: 'btn',
        handler: function () {
            mostFormgesttipoaprobado('mod');
        }
    });
    var btnEliminargesttipoaprobado = Ext.create('Ext.Button', {
        id: 'btnEligesttipoaprobado',
        text: '<b>' + perfil.etiquetas.lbBtnEliminar + '</b>',
        disabled: true,
        hidden: true,
        icon: perfil.dirImg + 'eliminar.png',
        iconCls: 'btn',
        handler: function () {
            Ext.MessageBox.show({
                title: 'Eliminar',
                msg: '¿Desea eliminar el status?',
                buttons: Ext.MessageBox.YESNO,
                icon: Ext.MessageBox.QUESTION,
                fn: eliminarTipoaprobado
            });
        }
    });
    UCID.portal.cargarAcciones(window.parent.idFuncionalidad);
    var winAdicionargesttipoaprobado;
    var winModificargesttipoaprobado;
    var formgesttipoaprobado = Ext.create('Ext.form.Panel', {
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
                fieldLabel: 'Status:',
                name: 'descripcion',
                labelAlign: 'top',
                anchor: '100%',
                allowBlank: false
            },
            {
                xtype: 'checkbox',
                fieldLabel: 'Activado:',
                name: 'estado',
                checked: true
            },
            {
                xtype: 'hidden',
                name: 'idtipoaprobado'
            }
        ]
    });

    function mostFormgesttipoaprobado(opcion) {
        switch (opcion) {
            case 'add':
            {
                winAdicionargesttipoaprobado = Ext.create('Ext.Window', {
                    title: 'Adicionar status',
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
                                winAdicionargesttipoaprobado.hide();
                            }
                        },
                        {
                            text: 'Aplicar',
                            icon: perfil.dirImg + 'aplicar.png',
                            handler: function () {
                                adicionarTipoaprobado("apl");
                            }
                        },
                        {
                            text: 'Aceptar',
                            icon: perfil.dirImg + 'aceptar.png',
                            handler: function () {
                                adicionarTipoaprobado("aceptar");
                            }
                        }
                    ]
                });
                winAdicionargesttipoaprobado.add(formgesttipoaprobado);
                winAdicionargesttipoaprobado.show();
                winAdicionargesttipoaprobado.on('hide', function () {
                    formgesttipoaprobado.getForm().reset();
                }, this);
            }
                break;
            case 'mod':
            {
                winModificargesttipoaprobado = Ext.create('Ext.Window', {
                    title: 'Modificar status',
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
                                winModificargesttipoaprobado.hide();
                            }
                        },
                        {
                            text: 'Aceptar',
                            icon: perfil.dirImg + 'aceptar.png',
                            handler: function () {
                                modificarTipoaprobado("aceptar");
                            }
                        }
                    ]
                });
                winModificargesttipoaprobado.add(formgesttipoaprobado);
                winModificargesttipoaprobado.show();
                winModificargesttipoaprobado.on('hide', function () {
                    formgesttipoaprobado.getForm().reset();
                }, this);
                formgesttipoaprobado.getForm().loadRecord(sm.getLastSelected());
            }
                break;
        }
    }

    var stGpgesttipoaprobado = Ext.create('Ext.data.ArrayStore', {
        fields: [
            {
                name: "idtipoaprobado"
            },
            {
                name: 'descripcion'
            },
            {
                name: 'estado'
            },
            {
                name: 'bloqueado'
            }
        ],
        proxy: {
            type: 'ajax',
            url: 'cargarTipoaprobado',
            reader: {
                type: 'json',
                id: 'idtipoaprobado',
                root: 'datos'
            }
        }
    });
    var search = Ext.create('Ext.form.field.Trigger', {
        store: stGpgesttipoaprobado,
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
    });
    var Gpgesttipoaprobado = Ext.create('Ext.grid.Panel', {
        store: stGpgesttipoaprobado,
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
                dataIndex: 'idtipoaprobado'
            },
            {
                hidden: true,
                dataIndex: 'estado'
            },
            {
                hidden: true,
                dataIndex: 'bloqueado'
            },
            {
                header: 'Status',
                flex: 1,
                sortable: true,
                dataIndex: 'descripcion'
            }
        ],
        region: 'center',
        tbar: [btnAdicionargesttipoaprobado, btnModificargesttipoaprobado, btnEliminargesttipoaprobado, '->', search]
    });
    var sm = Gpgesttipoaprobado.getSelectionModel();
    sm.setSelectionMode('MULTI');
    sm.on('selectionchange', function (sel, selectedRecord) {
        console.log(sel.getCount());
        btnModificargesttipoaprobado.setDisabled(selectedRecord.length > 1 || (sel.getCount() != 0 && selectedRecord[0].get('bloqueado')));
        btnEliminargesttipoaprobado.setDisabled(selectedRecord.length == 0);

    });
    stGpgesttipoaprobado.load();
    function adicionarTipoaprobado(apl) {
        //si es la opción de aplicar
        if (formgesttipoaprobado.getForm().isValid()) {
            formgesttipoaprobado.getForm().submit({
                url: 'insertarTipoaprobado',
                waitMsg: perfil.etiquetas.lbMsgRegistrando,
                failure: function (form, action) {
                    if (action.result.codMsg === 1) {
                        formgesttipoaprobado.getForm().reset();
                        stGpgesttipoaprobado.reload();
                        if (apl === "aceptar")
                            winAdicionargesttipoaprobado.hide();
                    }
                }
            });
        }
    }

    function modificarTipoaprobado(apl) {
        //si es la opción de aplicar
        if (formgesttipoaprobado.getForm().isValid()) {
            formgesttipoaprobado.getForm().submit({
                url: 'modificarTipoaprobado',
                waitMsg: perfil.etiquetas.lbMsgModificando,
                failure: function (form, action) {
                    if (action.result.codMsg === 1) {
                        stGpgesttipoaprobado.reload();
                        if (apl === "aceptar")
                            winModificargesttipoaprobado.hide();
                    }

                }
            });
        }
    }

    function eliminarTipoaprobado(buttonId) {
        if (buttonId === "yes") {
            var ids = new Array(),
                bloqueados = "";
            for (var i = 0; i < sm.getCount(); i++) {
                if (sm.getSelection()[i].raw.bloqueado) {
                    if (bloqueados !== "")
                        bloqueados += ", ";
                    bloqueados += "\"" + sm.getSelection()[i].raw.descripcion + "\"";
                } else
                    ids.push(sm.getSelection()[i].raw.idtipoaprobado)
            }
            if (bloqueados !== "") {
                mostrarMensaje(3, 'No se pueden eliminar: ' + bloqueados + ' por estar bloqueado(s)');
                return;
            }
            Ext.Ajax.request({
                url: 'eliminarTipoaprobado',
                method: 'POST',
                params: {idtipoaprob: Ext.JSON.encode(ids)},
                callback: function (options, success, response) {
                    responseData = Ext.decode(response.responseText);
                    if (responseData.codMsg === 1) {
                        stGpgesttipoaprobado.reload();
                        sm.deselect();
                    }
                }
            });
        }
    }

    var general = Ext.create('Ext.panel.Panel', {layout: 'border', items: [Gpgesttipoaprobado]});
    var vpGestSistema = Ext.create('Ext.Viewport', {layout: 'fit', items: general});
}