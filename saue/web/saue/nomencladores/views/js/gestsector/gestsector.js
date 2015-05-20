var perfil = window.parent.UCID.portal.perfil;
UCID.portal.cargarEtiquetas('gestsector', function () {
    cargarInterfaz();
});
Ext.QuickTips.init();
function cargarInterfaz() {
    var btnAdicionarsector = Ext.create('Ext.Button', {
        id: 'btnAgrsector',
        text: '<b>' + perfil.etiquetas.lbBtnAdicionar + '</b>',
        icon: perfil.dirImg + 'adicionar.png',
        iconCls: 'btn',
        hidden: true,
        handler: function () {
            mostFormsector('add');
        }
    });
    var btnModificarsector = Ext.create('Ext.Button', {
        id: 'btnModsector',
        text: '<b>' + perfil.etiquetas.lbBtnModificar + '</b>',
        disabled: true,
        hidden: true,
        icon: perfil.dirImg + 'modificar.png',
        iconCls: 'btn',
        handler: function () {
            mostFormsector('mod');
        }
    });
    var btnEliminarsector = Ext.create('Ext.Button', {
        id: 'btnElisector',
        text: '<b>' + perfil.etiquetas.lbBtnEliminar + '</b>',
        disabled: true,
        hidden: true,
        icon: perfil.dirImg + 'eliminar.png',
        iconCls: 'btn',
        handler: function () {
            Ext.MessageBox.show({
                title: 'Eliminar',
                msg: '¿Desea eliminar este sector?',
                buttons: Ext.MessageBox.YESNO,
                icon: Ext.MessageBox.QUESTION,
                fn: eliminarSector
            });
        }
    });
    UCID.portal.cargarAcciones(window.parent.idFuncionalidad);
    var winAdicionarsector;
    var winModificarsector;
    var formsector = Ext.create('Ext.form.Panel', {
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
                fieldLabel: 'Sector de la ciudad:',
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
                name: 'idsectorciudad'
            }
        ]
    });

    function mostFormsector(opcion) {
        switch (opcion) {
            case 'add':
            {
                winAdicionarsector = Ext.create('Ext.Window', {
                    title: 'Adicionar sector de la ciudad',
                    closeAction: 'hide',
                    width: 300,
                    height: 200,
                    constrain: true,
                    layout: 'fit',
                    items: [formsector],
                    buttons: [
                        {
                            text: 'Cancelar',
                            icon: perfil.dirImg + 'cancelar.png',
                            handler: function () {
                                winAdicionarsector.hide();
                            }
                        },
                        {
                            text: 'Aplicar',
                            icon: perfil.dirImg + 'aplicar.png',
                            handler: function () {
                                adicionarSector("apl");
                            }
                        },
                        {
                            text: 'Aceptar',
                            icon: perfil.dirImg + 'aceptar.png',
                            handler: function () {
                                adicionarSector("aceptar");
                            }
                        }
                    ]
                });
                winAdicionarsector.show();
                winAdicionarsector.on('hide', function () {
                    formsector.getForm().reset();
                }, this);
            }
                break;
            case 'mod':
            {
                winModificarsector = Ext.create('Ext.Window', {
                    title: 'Modificar  sector de la ciudad',
                    closeAction: 'hide',
                    width: 300,
                    height: 200,
                    constrain: true,
                    layout: 'fit',
                    items: [formsector],
                    buttons: [
                        {
                            text: 'Cancelar',
                            icon: perfil.dirImg + 'cancelar.png',
                            handler: function () {
                                winModificarsector.hide();
                            }
                        },
                        {
                            text: 'Aceptar',
                            icon: perfil.dirImg + 'aceptar.png',
                            handler: function () {
                                modificarSector("aceptar");
                            }
                        }
                    ]
                });
                winModificarsector.add(formsector);
                winModificarsector.show();
                winModificarsector.on('hide', function () {
                    formsector.getForm().reset();
                }, this);
                formsector.getForm().loadRecord(sm.getLastSelected());
            }
                break;
        }
    }

    var stGpsector = Ext.create('Ext.data.ArrayStore', {
        fields: [
            {
                name: "idsectorciudad"
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
            url: 'cargarSector',
            reader: {
                type: 'json',
                id: 'idSector',
                root: 'datos'
            }
        }
    });
    var search = Ext.create('Ext.form.field.Trigger', {
        store: stGpsector,
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
    var Gpsector = Ext.create('Ext.grid.Panel', {
        store: stGpsector,
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
                dataIndex: 'idsectorciudad'
            },
            {
                hidden: true,
                dataIndex: 'estado'
            },
            {
                header: 'Sector de la ciudad',
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
        tbar: [btnAdicionarsector, btnModificarsector, btnEliminarsector, '->', search]
    });
    var sm = Gpsector.getSelectionModel();
    sm.setSelectionMode('MULTI');
    sm.on('selectionchange', function (sel, selectedRecord) {
        btnModificarsector.setDisabled(selectedRecord.length>1);
        btnEliminarsector.setDisabled(selectedRecord.length==0);

    });
    stGpsector.load();
    function adicionarSector(apl) {
        //si es la opción de aplicar
        if (formsector.getForm().isValid()) {
            formsector.getForm().submit({
                url: 'insertarSector',
                waitMsg: perfil.etiquetas.lbMsgRegistrando,
                failure: function (form, action) {
                    if (action.result.codMsg === 1) {
                        formsector.getForm().reset();
                        stGpsector.reload();
                        if (apl === "aceptar")
                            winAdicionarsector.hide();
                    }


                }
            });
        }
    }

    function modificarSector(apl) {
        //si es la opción de aplicar
        if (formsector.getForm().isValid()) {
            formsector.getForm().submit({
                url: 'modificarSector',
                waitMsg: perfil.etiquetas.lbMsgModificando,
                failure: function (form, action) {
                    if (action.result.codMsg === 1) {
                        stGpsector.reload();
                        if (apl === "aceptar")
                            winModificarsector.hide();
                    }

                }
            });
        }
    }

    function eliminarSector(buttonId) {
        if (buttonId === "yes") {
            var ids= new Array();
            for(var i=0; i<sm.getCount(); i++){
                ids.push(sm.getSelection()[i].raw.idsectorciudad)
            }
            Ext.Ajax.request({
                url: 'eliminarSector',
                method: 'POST',
                params: {idsect: Ext.JSON.encode(ids)},
                callback: function (options, success, response) {
                    responseData = Ext.decode(response.responseText);
                    if (responseData.codMsg === 1) {
                        stGpsector.reload();
                        sm.deselect();
                    }
                }
            });
        }
    }

    //var general = Ext.create('Ext.panel.Panel', {layout: 'border', items: [Gpsector]});
    var vpGestSistema = Ext.create('Ext.Viewport', {layout: 'fit', items: Gpsector});
}