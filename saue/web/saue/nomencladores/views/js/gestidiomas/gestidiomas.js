var perfil = window.parent.UCID.portal.perfil;
UCID.portal.cargarEtiquetas('gestidiomas', function () {
    cargarInterfaz();
});
Ext.QuickTips.init();
function cargarInterfaz() {
    var btnAdicionaridiomas = Ext.create('Ext.Button', {
        id: 'btnAgridiomas',
        text: '<b>' + perfil.etiquetas.lbBtnAdicionar + '</b>',
        icon: perfil.dirImg + 'adicionar.png',
        iconCls: 'btn',
        hidden: true,
        handler: function () {
            mostFormidiomas('add');
        }
    });
    var btnModificaridiomas = Ext.create('Ext.Button', {
        id: 'btnModidiomas',
        text: '<b>' + perfil.etiquetas.lbBtnModificar + '</b>',
        disabled: true,
        hidden: true,
        icon: perfil.dirImg + 'modificar.png',
        iconCls: 'btn',
        handler: function () {
            mostFormidiomas('mod');
        }
    });
    var btnEliminaridiomas = Ext.create('Ext.Button', {
        id: 'btnEliidiomas',
        text: '<b>' + perfil.etiquetas.lbBtnEliminar + '</b>',
        disabled: true,
        hidden: true,
        icon: perfil.dirImg + 'eliminar.png',
        iconCls: 'btn',
        handler: function () {
            Ext.MessageBox.show({
                title: 'Eliminar',
                msg: '¿Desea eliminar este idioma?',
                buttons: Ext.MessageBox.YESNO,
                icon: Ext.MessageBox.QUESTION,
                fn: eliminarIdioma
            });
        }
    });
    UCID.portal.cargarAcciones(window.parent.idFuncionalidad);
    var winAdicionaridiomas;
    var winModificaridiomas;
    var formidiomas = Ext.create('Ext.form.Panel', {
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
                fieldLabel: 'Idioma:',
                name: 'descripcion',
                labelAlign: 'top',
                anchor: '100%',
                allowBlank: false
            },
            {xtype: 'numberfield',
                labelAlign: 'top',
                fieldLabel: 'Niveles:',
                name: 'nivel',
                anchor: '100%',
                allowBlank: false
            },
            {xtype: 'checkbox',
                fieldLabel: 'Activado:',
                name: 'estado',
                checked: true
            },
            {xtype: 'hidden',
                name: 'ididioma'
            }
        ]
    });

    function mostFormidiomas(opcion) {
        switch (opcion) {
            case 'add':
            {
                winAdicionaridiomas = Ext.create('Ext.Window', {
                    title: 'Adicionar idioma',
                    closeAction: 'hide',
                    width: 300,
                    height: 200,
                    constrain: true,
                    layout: 'fit',
                    buttons: [
                        {
                            text: 'Cancelar',
                            icon: perfil.dirImg + 'cancelar.png',
                            handler: function () {
                                winAdicionaridiomas.hide();
                            }
                        },
                        {
                            text: 'Aplicar',
                            icon: perfil.dirImg + 'aplicar.png',
                            handler: function () {
                                adicionarIdioma("apl");
                            }
                        },
                        {
                            text: 'Aceptar',
                            icon: perfil.dirImg + 'aceptar.png',
                            handler: function () {
                                adicionarIdioma("aceptar");
                            }
                        }
                    ]
                });
                winAdicionaridiomas.add(formidiomas);
                winAdicionaridiomas.show();
                winAdicionaridiomas.on('hide', function () {
                    formidiomas.getForm().reset();
                }, this);
            }
                break;
            case 'mod':
            {
                winModificaridiomas = Ext.create('Ext.Window', {
                    title: 'Modificar idioma',
                    closeAction: 'hide',
                    width: 300,
                    height: 200,
                    constrain: true,
                    layout: 'fit',
                    buttons: [
                        {
                            text: 'Cancelar',
                            icon: perfil.dirImg + 'cancelar.png',
                            handler: function () {
                                winModificaridiomas.hide();
                            }
                        },
                        {
                            text: 'Aceptar',
                            icon: perfil.dirImg + 'aceptar.png',
                            handler: function () {
                                modificarIdioma("aceptar");
                            }
                        }
                    ]
                });
                winModificaridiomas.add(formidiomas);
                winModificaridiomas.show();
                winModificaridiomas.on('hide', function () {
                    formidiomas.getForm().reset();
                }, this);
                formidiomas.getForm().loadRecord(sm.getLastSelected());
            }
                break;
        }
    }

    var stGpidiomas = Ext.create('Ext.data.ArrayStore', {
        fields: [
            {
                name: "ididioma"
            },
            {
                name: 'estado'
            },
            {
                name: 'descripcion'
            },
            {
                name: 'nivel'
            }
        ],
        proxy: {
            type: 'ajax',
            url: 'cargarIdioma',
            actionMethods: { //Esta Linea es necesaria para el metodo de llamada POST o GET
                read: 'POST'
            },
            reader: {
                type: 'json',
                id: 'ididioma',
                root: 'datos'
            }
        }
    });
    var search =
    {
        xtype: 'searchfield',
        store: stGpidiomas,
        emptyText: 'Filtrar por descripción',
        width: 400,
        padding: '0 0 0 5',
        labelWidth: 40,
        filterPropertysNames: ['descripcion']
    };
    /*Ext.create('Ext.form.field.Trigger', {
     store: stGpidiomas,
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
    var Gpidiomas = Ext.create('Ext.grid.Panel', {
        store: stGpidiomas,
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
                dataIndex: 'ididioma'
            },
            {
                hidden: true,
                dataIndex: 'estado'
            },
            {
                header: 'Idioma',
                flex: 1,
                sortable: true,
                dataIndex: 'descripcion'
            },
            {
                header: 'Niveles',
                flex: 1,
                sortable: true,
                dataIndex: 'nivel'
            }
        ],
        region: 'center',
        tbar: [btnAdicionaridiomas, btnModificaridiomas, btnEliminaridiomas, '->', search],
        bbar: Ext.create('Ext.toolbar.Paging', {
            displayInfo: true,
            store: stGpidiomas
        })
    });
    var sm = Gpidiomas.getSelectionModel();
    sm.setSelectionMode('MULTI');
    sm.on('selectionchange', function (sel, selectedRecord) {
        btnModificaridiomas.setDisabled(selectedRecord.length>1);
        btnEliminaridiomas.setDisabled(selectedRecord.length==0);

    });
    stGpidiomas.load();
    function adicionarIdioma(apl) {
        //si es la opción de aplicar
        if (formidiomas.getForm().isValid()) {
            formidiomas.getForm().submit({
                url: 'insertarIdioma',
                waitMsg: perfil.etiquetas.lbMsgRegistrando,
                failure: function (form, action) {
                    if (action.result.codMsg === 1) {
                        formidiomas.getForm().reset();
                        stGpidiomas.reload();
                        if (apl === "aceptar")
                            winAdicionaridiomas.hide();
                    }


                }
            });
        }
    }

    function modificarIdioma(apl) {
        //si es la opción de aplicar
        if (formidiomas.getForm().isValid()) {
            formidiomas.getForm().submit({
                url: 'modificarIdioma',
                waitMsg: perfil.etiquetas.lbMsgModificando,
                failure: function (form, action) {
                    if (action.result.codMsg === 1) {
                        stGpidiomas.reload();
                        if (apl === "aceptar")
                            winModificaridiomas.hide();
                    }

                }
            });
        }
    }

    function eliminarIdioma(buttonId) {
        if (buttonId === "yes") {
            var ids= new Array();
            for(var i=0; i<sm.getCount(); i++){
                ids.push(sm.getSelection()[i].raw.ididioma)
            }
            Ext.Ajax.request({
                url: 'eliminarIdioma',
                method: 'POST',
                params: {ididiomas: Ext.JSON.encode(ids)},
                callback: function (options, success, response) {
                    responseData = Ext.decode(response.responseText);
                    if (responseData.codMsg === 1) {
                        stGpidiomas.reload();
                        sm.deselect();
                    }
                }
            });
        }
    }

    //var general = Ext.create('Ext.panel.Panel', {layout: 'border', items: [Gpidiomas]});
    var vpGestSistema = Ext.create('Ext.Viewport', {layout: 'fit', items: Gpidiomas});
}