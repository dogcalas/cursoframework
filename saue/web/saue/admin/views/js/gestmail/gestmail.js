var perfil = window.parent.UCID.portal.perfil;
UCID.portal.cargarEtiquetas('gestmail', function () {
    cargarInterfaz();
});
Ext.QuickTips.init();
var idmail;
function cargarInterfaz() {
    var btnAddMail = Ext.create('Ext.Button', {
        id: 'btnAgrsector',
        text: '<b>' + perfil.etiquetas.lbBtnAdicionar + '</b>',
        icon: perfil.dirImg + 'adicionar.png',
        iconCls: 'btn',
        // hidden: true,
        handler: function () {
            winAddMail('add');
        }
    });
    var btnModMail = Ext.create('Ext.Button', {
        id: 'btnModsector',
        text: '<b>' + perfil.etiquetas.lbBtnModificar + '</b>',
        disabled: true,
        //  hidden: true,
        icon: perfil.dirImg + 'modificar.png',
        iconCls: 'btn',
        handler: function () {
            winAddMail('mod');
        }
    });
    var btnElimMail = Ext.create('Ext.Button', {
        id: 'btnElisector',
        text: '<b>' + perfil.etiquetas.lbBtnEliminar + '</b>',
        disabled: true,
        //  hidden: true,
        icon: perfil.dirImg + 'eliminar.png',
        iconCls: 'btn',
        handler: function () {
            Ext.MessageBox.show({
                title: 'Eliminar',
                msg: '¿Desea eliminar este sector?',
                buttons: Ext.MessageBox.YESNO,
                icon: Ext.MessageBox.QUESTION,
                fn: eliminarMail
            });
        }
    });
    var btnActiveMail = Ext.create('Ext.Button', {
        id: 'btnactivar',
        text: '<b>' + 'Activar' + '</b>',
        disabled: true,
        //hidden: true,
        icon: perfil.dirImg + 'usuarioactivo.png',
        iconCls: 'btn',
        handler: function () {
            activarMail();
        }
    });
    var btnDactiveMail = Ext.create('Ext.Button', {
        id: 'btndesactivar',
        text: '<b>' + 'Desactivar' + '</b>',
        disabled: true,
        //hidden: true,
        icon: perfil.dirImg + 'usuarioinactivo.png',
        iconCls: 'btn',
        handler: function () {
            desactivarMail();
        }
    });
    UCID.portal.cargarAcciones(window.parent.idFuncionalidad);
    var winAdicionarMail;
    var winModMail;
    var formMail = Ext.create('Ext.form.Panel', {
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
            {
                xtype: 'textfield',
                fieldLabel: 'Usuario',
                name: 'usuario',
                labelAlign: 'top',
                allowBlank: false
            },
            {
                xtype: 'textfield',
                fieldLabel: 'Contrase&ntilde;a:',
                name: 'password',
                id: 'password',
                labelAlign: 'top',
                inputType: 'password',
                allowBlank: false
            },
            {
                xtype: 'textfield',
                fieldLabel: 'Repetir Contrase&ntilde;a:',
                name: 'passwordr',
                id: 'passwordr',
                inputType: 'password',
                labelAlign: 'top',
                allowBlank: false
            },
            {
                xtype: 'textfield',
                fieldLabel: 'Servidor de salida:',
                name: 'smtp',
                labelAlign: 'top',
                checked: true
            }, ,
            {
                xtype: 'numberfield',
                fieldLabel: 'Puerto:',
                name: 'port',
                labelAlign: 'top',
                minValue: 0,
                value: 25,
                checked: true
            }, {
                xtype: 'combo',
                name: 'ssl',
                id: 'ssl',
                fieldLabel: 'Seguridad:',
                labelAlign: 'top',
                width: 80,
                allowBlank: false,
                emptyText: perfil.etiquetas.lbEmpCombo,
                editable: false,
                store: Ext.create('Ext.data.Store', {
                    fields: ['idssl', 'ssl'],
                    data: [
                        {
                            "idssl": 'starttls',
                            "ssl": 'STARTTLS'
                        },
                        {
                            "idssl": 'ssl',
                            "ssl": 'SSL'
                        },
                        {
                            "idssl": 'tls',
                            "ssl": 'TLS'
                        }
                    ]
                }),
                queryMode: 'local',
                displayField: 'ssl',
                valueField: 'idssl',
                style: {
                    marginTop: '6px'
                }
            }
        ]
    });

    function winAddMail(opcion) {
        switch (opcion) {
            case 'add':
            {
                winAdicionarMail = Ext.create('Ext.Window', {
                    title: 'Adicionar correo del sistema',
                    closeAction: 'hide',
                    width: 300,
                    height: 350,
                    constrain: true,
                    layout: 'fit',
                    items: [formMail],
                    buttons: [
                        {
                            text: 'Cancelar',
                            icon: perfil.dirImg + 'cancelar.png',
                            handler: function () {
                                winAdicionarMail.hide();
                            }
                        },
                        {
                            text: 'Aplicar',
                            icon: perfil.dirImg + 'aplicar.png',
                            handler: function () {
                                adicionarMail("apl");

                            }
                        },
                        {
                            text: 'Aceptar',
                            icon: perfil.dirImg + 'aceptar.png',
                            handler: function () {
                                adicionarMail("aceptar");
                            }
                        }
                    ]
                });
                winAdicionarMail.show();
                winAdicionarMail.on('hide', function () {
                    formMail.getForm().reset();
                }, this);
            }
                break;
            case 'mod':
            {
                winModMail = Ext.create('Ext.Window', {
                    title: 'Modificar correo del sistema',
                    closeAction: 'hide',
                    width: 300,
                    height: 350,
                    constrain: true,
                    layout: 'fit',
                    items: [formMail],
                    buttons: [
                        {
                            text: 'Cancelar',
                            icon: perfil.dirImg + 'cancelar.png',
                            handler: function () {
                                winModMail.hide();
                            }
                        },
                        {
                            text: 'Aceptar',
                            icon: perfil.dirImg + 'aceptar.png',
                            handler: function () {
                                modificarMail("aceptar");
                            }
                        }
                    ]
                });
                winModMail.add(formMail);
                winModMail.show();
                winModMail.on('hide', function () {
                    formMail.getForm().reset();
                }, this);
                formMail.getForm().loadRecord(sm.getLastSelected());
            }
                break;
        }
    }

    var stGdMail = Ext.create('Ext.data.ArrayStore', {
        fields: [

            {
                name: 'id'
            }, {
                name: 'usuario'
            },

            {
                name: 'smtp'
            },

            {
                name: 'port'
            },

            {
                name: 'ssl'
            },

            {
                name: 'estado'
            },

            {
                name: 'activo'
            }
        ],
        autoLoad: true,
        proxy: {
            type: 'ajax',
            url: 'cargarMail',
            reader: {
                type: 'json',
                id: 'idMail',
                root: 'datos'
            }
        }
    });
    var search = Ext.create('Ext.form.field.Trigger', {
        store: stGdMail,
        trigger1Cls: Ext.baseCSSPrefix + 'form-clear-trigger',
        trigger2Cls: Ext.baseCSSPrefix + 'form-search-trigger',
        width: 400,
        emptyText: 'Filtrar por: <usuario>',
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
                        return item.get("usuario").toLowerCase().match(me.getValue().toLowerCase());
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
    var sm = new Ext.selection.RowModel({
        mode: 'SINGLE'
    });
    var GdMail = new Ext.grid.GridPanel({
        store: stGdMail,
        stateId: 'stateGrid',
        columnLines: true,
        selModel: sm,
        columns: [
            {
                hidden: true,
                dataIndex: 'id'
            }, {
                hidden: true,
                dataIndex: 'estado'
            },
            {
                header: 'Usuario',
                flex: 1,
                sortable: true,
                dataIndex: 'usuario',
                field: {
                    type: 'textfield',
                    allowBlank: false
                }
            },
            {
                header: 'Servidor smtp',
                flex: 1,
                sortable: true,
                dataIndex: 'smtp',
                field: {
                    type: 'textfield',
                    allowBlank: false
                }
            },
            {
                header: 'Puerto',
                flex: 1,
                sortable: true,
                dataIndex: 'port',
                field: {
                    type: 'numberfield',
                    allowBlank: false
                }
            },
            {
                header: 'SSL',
                flex: 1,
                sortable: true,
                dataIndex: 'ssl',
                field: {
                    type: 'textfield',
                    allowBlank: false
                }
            }, {
                header: 'Estado',
                flex: 1,
                sortable: true,
                dataIndex: 'activo'
            },
        ],
        tbar: [btnAddMail, btnModMail, btnElimMail, btnActiveMail, btnDactiveMail, '->', search],
        region: 'center',
        viewConfig: {
            getRowClass: function (record, rowIndex, rowParams, store) {
                if (record.data.estado == false)
                    return 'FilaRoja';
            }
        }
    });


    sm.on('select', function (sel, selectedRecord) {
        var estado = sm.getLastSelected().raw.estado;
        idmail = sm.getLastSelected().raw.id;
        if (estado == false) {
            btnActiveMail.enable(true);
            btnDactiveMail.disable();
        }
        if (estado == true) {
            btnActiveMail.disable();
            btnDactiveMail.enable();
        }
        btnElimMail.enable(true);
        btnModMail.enable(true);

    });

    function adicionarMail(apl) {
        var passw = Ext.getCmp('password').getValue();
        var passw1 = Ext.getCmp('passwordr').getValue();
        if (passw == passw1) {
            //si es la opción de aplicar
            if (formMail.getForm().isValid()) {
                formMail.getForm().submit({
                    url: 'insertarMail',
                    waitMsg: perfil.etiquetas.lbMsgRegistrando,
                    success: function (form, action) {
                        if (action.result.codMsg === 1) {
                            formMail.getForm().reset();
                            stGdMail.load();
                            if (apl === "aceptar")
                                winAdicionarMail.hide();
                        }
                    }
                });
            }
            sm.clearSelections();
        } else {

            Ext.Msg.alert('ERROR', 'Las contraseñas no coinciden');
            Ext.getCmp('password').setValue('');
            Ext.getCmp('passwordr').setValue('');
        }

    }

    function modificarMail(apl) {
        var passw = Ext.getCmp('password').getValue();
        var passw1 = Ext.getCmp('passwordr').getValue();
        if (passw == passw1) {
            //si es la opción de aplicar
            if (formMail.getForm().isValid()) {
                formMail.getForm().submit({
                    url: 'modificarMail',
                    params: {
                        id: idmail
                    },
                    waitMsg: perfil.etiquetas.lbMsgRegistrando,
                    success: function (form, action) {
                        if (action.result.codMsg === 1) {
                            formMail.getForm().reset();
                            stGdMail.load();
                            if (apl === "aceptar")
                                winModMail.hide();
                            btnActiveMail.disable();
                            btnDactiveMail.disable();
                            btnElimMail.disable();
                            btnModMail.disable();
                        }


                    }
                });
            }
        } else {

            Ext.Msg.alert('ERROR', 'Las contraseñas no coinciden');
            Ext.getCmp('password').setValue('');
            Ext.getCmp('passwordr').setValue('');
        }
        sm.clearSelections();
    }


    function eliminarMail(buttonId) {
        if (buttonId === "yes") {
            Ext.Ajax.request({
                url: 'eliminarMail',
                method: 'POST',
                params: {idmail: sm.getLastSelected().raw.id},
                callback: function (options, success, response) {
                    responseData = Ext.decode(response.responseText);
                    if (responseData.codMsg === 1) {
                        stGdMail.load();
                        sm.deselect();
                        btnActiveMail.disable();
                        btnDactiveMail.disable();
                        btnElimMail.disable();
                        btnModMail.disable();
                    }
                }
            });
        }
        sm.clearSelections();
    }

    function activarMail() {

        Ext.Ajax.request({
            url: 'activarMail',
            method: 'POST',
            params: {
                idmail: sm.getLastSelected().raw.id
            },
            callback: function (options, success, response) {
                responseData = Ext.decode(response.responseText);
                if (responseData.codMsg === 1) {
                    stGdMail.load();
                    sm.deselect();
                    btnActiveMail.disable();
                    btnDactiveMail.disable();
                    btnElimMail.disable();
                    btnModMail.disable();
                }
            }
        });
        sm.clearSelections();
    }

    function desactivarMail() {

        Ext.Ajax.request({
            url: 'desactivarMail',
            method: 'POST',
            params: {
                idmail: sm.getLastSelected().raw.id
            },
            callback: function (options, success, response) {
                responseData = Ext.decode(response.responseText);
                if (responseData.codMsg === 1) {
                    stGdMail.load();
                    sm.deselect();
                    btnActiveMail.disable();
                    btnDactiveMail.disable();
                    btnElimMail.disable();
                    btnModMail.disable();
                }
            }
        });

        sm.clearSelections();
    }


    //var general = Ext.create('Ext.panel.Panel', {layout: 'border', items: [GdMail]});
    var vpGestSistema = Ext.create('Ext.Viewport', {layout: 'fit', items: GdMail});
}