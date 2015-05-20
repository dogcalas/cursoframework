var perfil = window.parent.UCID.portal.perfil;
UCID.portal.cargarEtiquetas('gestareasgen', function () {
    cargarInterfaz();
});
Ext.QuickTips.init();
function cargarInterfaz() {
    var btnAdicionargestareasgen = Ext.create('Ext.Button', {
        id: 'btnAgrgestareasgen',
        text: '<b>' + perfil.etiquetas.lbBtnAdicionar + '</b>',
        icon: perfil.dirImg + 'adicionar.png',
        iconCls: 'btn',
        hidden: true,
        handler: function () {
            mostFormgestareasgen('add');
        }
    });
    var btnModificargestareasgen = Ext.create('Ext.Button', {
        id: 'btnModgestareasgen',
        text: '<b>' + perfil.etiquetas.lbBtnModificar + '</b>',
        disabled: true,
        hidden: true,
        icon: perfil.dirImg + 'modificar.png',
        iconCls: 'btn',
        handler: function () {
            mostFormgestareasgen('mod');
        }
    });
    var btnEliminargestareasgen = Ext.create('Ext.Button', {
        id: 'btnEligestareasgen',
        text: '<b>' + perfil.etiquetas.lbBtnEliminar + '</b>',
        disabled: true,
        hidden: true,
        icon: perfil.dirImg + 'eliminar.png',
        iconCls: 'btn',
        handler: function () {
            Ext.MessageBox.show({
                title: 'Eliminar',
                msg: (sm.getCount() > 1) ? '¿Desea eliminar los niveles?' : '¿Desea eliminar el nivel?',
                buttons: Ext.MessageBox.YESNO,
                icon: Ext.MessageBox.QUESTION,
                fn: eliminarAreasgen
            });
        }
    });
    UCID.portal.cargarAcciones(window.parent.idFuncionalidad);
    var winAdicionargestareasgen;
    var winModificargestareasgen;
    var formgestareasgen = Ext.create('Ext.form.Panel', {
        frame: true,
        bodyStyle: 'padding:5px 5px 0',
        width: 340,
        fieldDefaults: {
            msgTarget: 'side',
            labelWidth: 90
        },
        defaults: {
            anchor: '100%'
        },
        items: [
            {xtype: 'textfield',
                fieldLabel: 'Nivel:',
                name: 'descripcion',
                anchor: '100%',
                allowBlank: false
            },
            {xtype: 'checkbox',
                fieldLabel: 'Activado:',
                name: 'estado',
                checked: true
            },
            {xtype: 'hidden',
                name: 'idareasgen'
            }
        ]
    });

    function mostFormgestareasgen(opcion) {
        switch (opcion) {
            case 'add':
            {
                winAdicionargestareasgen = Ext.create('Ext.Window', {
                    title: 'Adicionar nivel',
                    closeAction: 'hide',
                    width: 350,
                    height: 150,
                    constrain: true,
                    layout: 'fit',
                    buttons: [
                        {
                            text: 'Cancelar',
                            icon: perfil.dirImg + 'cancelar.png',
                            handler: function () {
                                winAdicionargestareasgen.hide();
                            }
                        },
                        {
                            text: 'Aplicar',
                            icon: perfil.dirImg + 'aplicar.png',
                            handler: function () {
                                adicionarAreasgen("apl");
                            }
                        },
                        {
                            text: 'Aceptar',
                            icon: perfil.dirImg + 'aceptar.png',
                            handler: function () {
                                adicionarAreasgen("aceptar");
                            }
                        }
                    ]
                });
                winAdicionargestareasgen.add(formgestareasgen);
                winAdicionargestareasgen.show();
                winAdicionargestareasgen.on('hide', function () {
                    formgestareasgen.getForm().reset();
                }, this);
            }
                break;
            case 'mod':
            {
                winModificargestareasgen = Ext.create('Ext.Window', {
                    title: 'Modificar  nivel',
                    closeAction: 'hide',
                    width: 350,
                    height: 150,
                    constrain: true,
                    layout: 'fit',
                    buttons: [
                        {
                            text: 'Cancelar',
                            icon: perfil.dirImg + 'cancelar.png',
                            handler: function () {
                                winModificargestareasgen.hide();
                            }
                        },
                        {
                            text: 'Aceptar',
                            icon: perfil.dirImg + 'aceptar.png',
                            handler: function () {
                                modificarAreasgen("aceptar");
                            }
                        }
                    ]
                });
                winModificargestareasgen.add(formgestareasgen);
                winModificargestareasgen.show();
                winModificargestareasgen.on('hide', function () {
                    formgestareasgen.getForm().reset();
                }, this);
                formgestareasgen.getForm().loadRecord(sm.getLastSelected());
            }
                break;
        }
    }

    var stGpgestareasgen = Ext.create('Ext.data.ArrayStore', {
        fields: [
            {
                name: "idareasgen",
                mapping: "idareageneral"
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
            url: 'cargarAreasgen',
            reader: {
                type: 'json',
                id: 'idareasgen',
                root: 'datos'
            }
        }
    });
    var search =
    {
        xtype: 'searchfield',
        store: stGpgestareasgen,
        emptyText: 'Filtrar por descripción',
        width: 400,
        padding: '0 0 0 5',
        labelWidth: 40,
        filterPropertysNames: ['descripcion']
    };
    /*Ext.create('Ext.form.field.Trigger', {
     store: stGpgestareasgen,
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
    var Gpgestareasgen = Ext.create('Ext.grid.Panel', {
        store: stGpgestareasgen,
        selModel: Ext.create('Ext.selection.CheckboxModel'),
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
                dataIndex: 'idareasgen'
            },
            {hidden: true,
                dataIndex: 'estado'
            },
            {
                header: 'Adicionar nivel',
                flex: 1,
                sortable: true,
                dataIndex: 'descripcion'
            }
        ],
        region: 'center',
        tbar: [btnAdicionargestareasgen, btnModificargestareasgen, btnEliminargestareasgen, '->', search]
    });
    var sm = Gpgestareasgen.getSelectionModel();
    sm.on('selectionchange', function (sel, selectedRecord) {
        if (selectedRecord.length) {
            btnModificargestareasgen.enable();
            btnEliminargestareasgen.enable();
        } else {
            btnModificargestareasgen.disable();
            btnEliminargestareasgen.disable();
        }
    });
    stGpgestareasgen.load();
    function adicionarAreasgen(apl) {
        //si es la opción de aplicar
        if (formgestareasgen.getForm().isValid()) {
            formgestareasgen.getForm().submit({
                url: 'insertarAreasgen',
                waitMsg: perfil.etiquetas.lbMsgRegistrando,
                failure: function (form, action) {
                    if (action.result.codMsg === 1) {
                        formgestareasgen.getForm().reset();
                        stGpgestareasgen.reload();
                        if (apl === "aceptar")
                            winAdicionargestareasgen.hide();
                    }


                }
            });
        }
    }

    function modificarAreasgen(apl) {
        //si es la opción de aplicar
        if (formgestareasgen.getForm().isValid()) {
            formgestareasgen.getForm().submit({
                url: 'modificarAreasgen',
                waitMsg: perfil.etiquetas.lbMsgModificando,
                failure: function (form, action) {
                    if (action.result.codMsg === 1) {
                        stGpgestareasgen.reload();
                        if (apl === "aceptar")
                            winModificargestareasgen.hide();
                    }

                }
            });
        }
    }

    function eliminarAreasgen(buttonId) {
        if (buttonId === "yes") {
            var selecciones = sm.getSelection(),
                areasgen = [];
            for (i = 0; i < selecciones.length; i++) {
                areasgen[i] = selecciones[i].get('idareasgen');
            }
            Ext.Ajax.request({
                url: 'eliminarAreasgen',
                method: 'POST',
                //params: {idareasgen: sm.getLastSelected().data.idareasgen},
                params: {areasgen: Ext.encode(areasgen)},
                callback: function (options, success, response) {
                    responseData = Ext.decode(response.responseText);
                    if (responseData.codMsg === 1) {
                        stGpgestareasgen.reload();
                        sm.deselect();
                    }
                }
            });
        }
    }

    //var general = Ext.create('Ext.panel.Panel', {layout: 'border', items: [Gpgestareasgen]});
    var vpGestSistema = Ext.create('Ext.Viewport', {layout: 'fit', items: Gpgestareasgen});
}