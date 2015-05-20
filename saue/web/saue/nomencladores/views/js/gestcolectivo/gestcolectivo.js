var perfil = window.parent.UCID.portal.perfil;
UCID.portal.cargarEtiquetas('gestcolectivo', function() {
    cargarInterfaz();
});
Ext.QuickTips.init();
function cargarInterfaz() {
    var btnAdicionargestcolectivo = Ext.create('Ext.Button', {
        id: 'btnAgrgestcolectivo',
        text: '<b>' + perfil.etiquetas.lbBtnAdicionar + '</b>',
        icon: perfil.dirImg + 'adicionar.png',
        iconCls: 'btn',
        hidden: true,
        handler: function() {
            mostFormgestcolectivo('add');
        }
    });
    var btnModificargestcolectivo = Ext.create('Ext.Button', {
        id: 'btnModgestcolectivo',
        text: '<b>' + perfil.etiquetas.lbBtnModificar + '</b>',
        disabled: true,
        hidden: true,
        icon: perfil.dirImg + 'modificar.png',
        iconCls: 'btn',
        handler: function() {
            mostFormgestcolectivo('mod');
        }
    });
    var btnEliminargestcolectivo = Ext.create('Ext.Button', {
        id: 'btnEligestcolectivo',
        text: '<b>' + perfil.etiquetas.lbBtnEliminar + '</b>',
        disabled: true,
        hidden: true,
        icon: perfil.dirImg + 'eliminar.png',
        iconCls: 'btn',
        handler: function() {
            Ext.MessageBox.show({
                title: 'Eliminar',
                msg: 'Desea eliminar este colectivo?',
                buttons: Ext.MessageBox.YESNO,
                icon: Ext.MessageBox.QUESTION,
                fn: eliminarColectivo
            });
        }
    });
    UCID.portal.cargarAcciones(window.parent.idFuncionalidad);
    var winAdicionargestcolectivo;
    var winModificargestcolectivo;
    var formgestcolectivo = Ext.create('Ext.form.Panel', {
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
        items: [{xtype: 'textfield',
                fieldLabel: 'Colectivo académico:',
                name: 'descripcion',
                anchor: '100%',
            labelAlign: 'top',
                allowBlank: false
            }, {xtype: 'checkbox',
                fieldLabel: 'Activado:',
                name: 'estado',
                checked: true
            },
            {xtype: 'hidden',
                name: 'idcolectivo'
            }]
    });
    function mostFormgestcolectivo(opcion) {
        switch (opcion) {
            case 'add':
                {
                    winAdicionargestcolectivo = Ext.create('Ext.Window', {
                        title: 'Adicionar colectivo académico',
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
                                handler: function() {
                                    winAdicionargestcolectivo.hide();
                                }
                            }, {
                                text: 'Aplicar',
                                icon: perfil.dirImg + 'aplicar.png',
                                handler: function() {
                                    adicionarColectivo("apl");
                                }
                            }, {
                                text: 'Aceptar',
                                icon: perfil.dirImg + 'aceptar.png',
                                handler: function() {
                                    adicionarColectivo("aceptar");
                                }
                            }
                        ]
                    });
                    winAdicionargestcolectivo.add(formgestcolectivo);
                    winAdicionargestcolectivo.show();
                    winAdicionargestcolectivo.on('hide', function() {
                        formgestcolectivo.getForm().reset();
                    }, this);
                }
                break;
            case 'mod':
                {
                    winModificargestcolectivo = Ext.create('Ext.Window', {
                        title: 'Modificar colectivo académico',
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
                                handler: function() {
                                    winModificargestcolectivo.hide();
                                }
                            }, {
                                text: 'Aceptar',
                                icon: perfil.dirImg + 'aceptar.png',
                                handler: function() {
                                    modificarColectivo("aceptar");
                                }
                            }
                        ]
                    });
                    winModificargestcolectivo.add(formgestcolectivo);
                    winModificargestcolectivo.show();
                    winModificargestcolectivo.on('hide', function() {
                        formgestcolectivo.getForm().reset();
                    }, this);
                    formgestcolectivo.getForm().loadRecord(sm.getLastSelected());
                }
                break;
        }
    }
    var stGpgestcolectivo = Ext.create('Ext.data.ArrayStore', {
        fields: [{
                name: "idcolectivo"
            }, {
                name: 'descripcion'
            }, {
                name: 'estado'
            }],
        proxy: {
            type: 'ajax',
            url: 'cargarColectivo',
            reader: {
                type: 'json',
                id: 'idcolectivo',
                root: 'datos'
            }
        }
    });
             var search = Ext.create ('Ext.form.field.Trigger', {
                store:stGpgestcolectivo,
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
                        me.store.filter({filterFn: function(item) { return item.get("descripcion").toLowerCase().match(me.getValue().toLowerCase());}});
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
    var Gpgestcolectivo = Ext.create('Ext.grid.Panel', {
        store: stGpgestcolectivo,
        stateId: 'stateGrid',
        columnLines: true,
        viewConfig:{
            getRowClass: function(record, rowIndex, rowParams, store){
                if (record.data.estado == false)
                    return 'FilaRoja';
            }
        },
        columns: [{
                hidden:true,
                dataIndex: 'idcolectivo'
            }, {hidden:true,
                dataIndex: 'estado'
            }, {
                header: 'Colectivo académico',
                flex: 1,
                sortable: true,
                dataIndex: 'descripcion'
            }],
        region: 'center',
        tbar: [btnAdicionargestcolectivo, btnModificargestcolectivo, btnEliminargestcolectivo, '->', search]
    });
    var sm = Gpgestcolectivo.getSelectionModel();
    sm.setSelectionMode('MULTI');
    sm.on('selectionchange', function (sel, selectedRecord) {
        btnModificargestcolectivo.setDisabled(selectedRecord.length>1);
        btnEliminargestcolectivo.setDisabled(selectedRecord.length==0);
        
    });
    stGpgestcolectivo.load();
    function adicionarColectivo(apl) {
        //si es la opción de aplicar
        if (formgestcolectivo.getForm().isValid()) {
            formgestcolectivo.getForm().submit({
                url: 'insertarColectivo',
                waitMsg: perfil.etiquetas.lbMsgRegistrando,
                failure: function(form, action) {
                    if (action.result.codMsg === 1) {
                        formgestcolectivo.getForm().reset();
                        stGpgestcolectivo.reload();
                        if (apl === "aceptar")
                            winAdicionargestcolectivo.hide();
                    }


                }
            });
        }
    }
    function modificarColectivo(apl) {
        //si es la opción de aplicar
        if (formgestcolectivo.getForm().isValid()) {
            formgestcolectivo.getForm().submit({
                url: 'modificarColectivo',
                waitMsg: perfil.etiquetas.lbMsgModificando,
                failure: function(form, action) {
                    if (action.result.codMsg === 1) {
                        stGpgestcolectivo.reload();
                        if (apl === "aceptar")
                            winModificargestcolectivo.hide();
                    }

                }
            });
        }
    }
    function eliminarColectivo(buttonId) {
        if (buttonId === "yes") {
            var ids= new Array();
            for(var i=0; i<sm.getCount(); i++){
                ids.push(sm.getSelection()[i].raw.idcolectivo)
            }
            Ext.Ajax.request({
                url: 'eliminarColectivo',
                method: 'POST',
                params: {idcolect: Ext.JSON.encode(ids)},
                callback: function(options, success, response) {
                    responseData = Ext.decode(response.responseText);
                    if (responseData.codMsg === 1) {
                        stGpgestcolectivo.reload();
                        sm.deselect();
                    }
                }
            });
        }
    }
    var general = Ext.create('Ext.panel.Panel', {layout: 'border', items: [Gpgestcolectivo]});
    var vpGestSistema = Ext.create('Ext.Viewport', {layout: 'fit', items: general});
}