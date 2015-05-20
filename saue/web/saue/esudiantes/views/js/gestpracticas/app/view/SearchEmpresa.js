Ext.define('PracWin.view.SearchEmpresa', {
    extend: 'Ext.window.Window',
    alias: 'widget.searchempresa',
    layout:'border',
    height: 400,
    width: 511,
    title: 'Buscar empresa',

    initComponent: function() {
        var me = this;

        Ext.applyIf(me, {
            items: [

                {
                    xtype: 'searchfield',
                   	store: 'GestEmpresas.store.stEmpresas',
                    fieldLabel: perfil.etiquetas.lbBtnBuscar,
                    labelWidth: 40,
                    region: 'north',
                    padding:'10',
                    filterPropertysNames: ["descripcion", "actividad"]
                },
                {
                            xtype: 'gridpanel',
                            region: 'center',
                            id: 'listaEmpresa',
                            title: 'Empresas de pr&aacute;cticas',
                            store: 'GestEmpresas.store.stEmpresas',
                            columns: [
                                {
                                    xtype: 'gridcolumn',
                                    flex: 1,
                                    dataIndex: 'descripcion',
                                    text: 'Denominación'
                                },
                                {
                                    xtype: 'gridcolumn',
                                    dataIndex: 'actividad',
                                    flex: 1,
                                    text: 'Actividad'
                                }
                            ],
                            dockedItems: [
                                {
                                    xtype: 'pagingtoolbar',
                                    dock: 'bottom',
                                    id: 'paginatorEmpresa',
                                    store:'GestEmpresas.store.stEmpresas',
                                    displayInfo: true
                                }
                            ]
                        }
            ],
            dockedItems: [
                {
                    xtype: 'toolbar',
                    dock: 'bottom',
                    layout: {
                        pack: 'end',
                        type: 'hbox'
                    },
                    items: [
                        {
                                icon: perfil.dirImg + 'cancelar.png',
                                iconCls: 'btn',
                                text: perfil.etiquetas.lbBtnCancelar,
                                action: 'cancelar',
                                scope: this,
                                handler: this.close
                            },
                            {
                                id: 'idBtnEmpresaAceptar',
                                icon: perfil.dirImg + 'aceptar.png',
                                iconCls: 'btn',
                                text: perfil.etiquetas.lbBtnAceptar,
                                action: 'aceptar'
                            }
                    ]
                }
            ]
        });

        me.callParent(arguments);
    }

});