var perfil = window.parent.UCID.portal.perfil;
UCID.portal.cargarEtiquetas('gestcomisiones', function () {
    cargarInterfaz();
});
Ext.QuickTips.init();
function cargarInterfaz() {
    var btnAdicionargestcomisiones = Ext.create('Ext.Button', {
        id: 'btnAgrgestcomisiones',
        text: '<b>' + perfil.etiquetas.lbBtnAdicionar + '</b>',
        icon: perfil.dirImg + 'adicionar.png',
        iconCls: 'btn',
        hidden: true,
        handler: function () {
            mostFormgestcomisiones('add');
        }
    });
    var btnModificargestcomisiones = Ext.create('Ext.Button', {
        id: 'btnModgestcomisiones',
        text: '<b>' + perfil.etiquetas.lbBtnModificar + '</b>',
        disabled: true,
        hidden: true,
        icon: perfil.dirImg + 'modificar.png',
        iconCls: 'btn',
        handler: function () {
            mostFormgestcomisiones('mod');
        }
    });
    var btnEliminargestcomisiones = Ext.create('Ext.Button', {
        id: 'btnEligestcomisiones',
        text: '<b>' + perfil.etiquetas.lbBtnEliminar + '</b>',
        disabled: true,
        hidden: true,
        icon: perfil.dirImg + 'eliminar.png',
        iconCls: 'btn',
        handler: function () {
            Ext.MessageBox.show({
                title: 'Eliminar',
                msg: '¿Desea eliminar esta comisión?',
                buttons: Ext.MessageBox.YESNO,
                icon: Ext.MessageBox.QUESTION,
                fn: eliminarComisiones
            });
        }
    });
    UCID.portal.cargarAcciones(window.parent.idFuncionalidad);
    var winAdicionargestcomisiones;
    var winModificargestcomisiones;
    var formgestcomisiones = Ext.create('Ext.form.Panel', {
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
                fieldLabel: 'Comisiones:',
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
                name: 'idcomisiones'
            }
        ]
    });

    function mostFormgestcomisiones(opcion) {
        switch (opcion) {
            case 'add':
            {
                winAdicionargestcomisiones = Ext.create('Ext.Window', {
                    title: 'Adicionar comisiones',
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
                                winAdicionargestcomisiones.hide();
                            }
                        },
                        {
                            text: 'Aplicar',
                            icon: perfil.dirImg + 'aplicar.png',
                            handler: function () {
                                adicionarComisiones("apl");
                            }
                        },
                        {
                            text: 'Aceptar',
                            icon: perfil.dirImg + 'aceptar.png',
                            handler: function () {
                                adicionarComisiones("aceptar");
                            }
                        }
                    ]
                });
                winAdicionargestcomisiones.add(formgestcomisiones);
                winAdicionargestcomisiones.show();
                winAdicionargestcomisiones.on('hide', function () {
                    formgestcomisiones.getForm().reset();
                }, this);
            }
                break;
            case 'mod':
            {
                winModificargestcomisiones = Ext.create('Ext.Window', {
                    title: 'Modificar comisiones',
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
                                winModificargestcomisiones.hide();
                            }
                        },
                        {
                            text: 'Aceptar',
                            icon: perfil.dirImg + 'aceptar.png',
                            handler: function () {
                                modificarComisiones("aceptar");
                            }
                        }
                    ]
                });
                winModificargestcomisiones.add(formgestcomisiones);
                winModificargestcomisiones.show();
                winModificargestcomisiones.on('hide', function () {
                    formgestcomisiones.getForm().reset();
                }, this);
                formgestcomisiones.getForm().loadRecord(sm.getLastSelected());
            }
                break;
        }
    }

    var stGpgestcomisiones = Ext.create('Ext.data.ArrayStore', {
        fields: [
            {
                name: "idcomisiones"
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
            url: 'cargarComisiones',
            reader: {
                type: 'json',
                id: 'idcomisiones',
                root: 'datos'
            }
        }
    });
    var search = Ext.create('Ext.form.field.Trigger', {
        store: stGpgestcomisiones,
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
    
         var sm = new Ext.selection.RowModel({
            mode: 'MULTI'
        });
    
    sm.on('selectionchange', function (sel, selectedRecord) {
            if (selectedRecord.length == 1) {
                btnModificargestcomisiones.enable();
                btnEliminargestcomisiones.enable();
            } else if (selectedRecord.length > 1) {
                btnModificargestcomisiones.disable();
                btnEliminargestcomisiones.enable();
            } else {
                btnModificargestcomisiones.disable();
                btnEliminargestcomisiones.disable();
            }
        });
        
    var Gpgestcomisiones = Ext.create('Ext.grid.Panel', {
        store: stGpgestcomisiones,
        stateId: 'stateGrid',
        columnLines: true,
        selModel: sm,
        viewConfig: {
            getRowClass: function (record, rowIndex, rowParams, store) {
                if (record.data.estado == false)
                    return 'FilaRoja';
            }
        },
        columns: [
            {
                hidden: true,
                dataIndex: 'idcomisiones'
            },
            {hidden: true,
                dataIndex: 'estado'
            },
            {
                header: 'Comisiones',
                flex: 1,
                sortable: true,
                dataIndex: 'descripcion'
            }
        ],
        region: 'center',
        tbar: [btnAdicionargestcomisiones, btnModificargestcomisiones, btnEliminargestcomisiones, '->', search]
    });
        
    stGpgestcomisiones.load();
    function adicionarComisiones(apl) {
        //si es la opción de aplicar
        if (formgestcomisiones.getForm().isValid()) {
            formgestcomisiones.getForm().submit({
                url: 'insertarComisiones',
                waitMsg: perfil.etiquetas.lbMsgRegistrando,
                failure: function (form, action) {
                    if (action.result.codMsg === 1) {
                        formgestcomisiones.getForm().reset();
                        stGpgestcomisiones.reload();
                        if (apl === "aceptar")
                            winAdicionargestcomisiones.hide();
                    }


                }
            });
        }
    }

    function modificarComisiones(apl) {
        //si es la opción de aplicar
        if (formgestcomisiones.getForm().isValid()) {
            formgestcomisiones.getForm().submit({
                url: 'modificarComisiones',
                waitMsg: perfil.etiquetas.lbMsgModificando,
                failure: function (form, action) {
                    if (action.result.codMsg === 1) {
                        stGpgestcomisiones.reload();
                        if (apl === "aceptar")
                            winModificargestcomisiones.hide();
                    }

                }
            });
        }
    }

    function eliminarComisiones(buttonId) {
        if (buttonId === "yes") {
			 var ids = new Array();
                    for (var i = 0; i < sm.getCount(); i++) {
                        ids.push(sm.getSelection()[i].raw.idcomisiones);
                    }
            Ext.Ajax.request({
                url: 'eliminarComisiones',
                method: 'POST',
                params: {
					idcomisiones: Ext.JSON.encode(ids)
					},
                callback: function (options, success, response) {
                    responseData = Ext.decode(response.responseText);
                    if (responseData.codMsg === 1) {
                        stGpgestcomisiones.reload();
                        sm.deselect();
                    }
                }
            });
        }
    }

    var general = Ext.create('Ext.panel.Panel', {layout: 'border', items: [Gpgestcomisiones]});
    var vpGestSistema = Ext.create('Ext.Viewport', {layout: 'fit', items: general});
}
