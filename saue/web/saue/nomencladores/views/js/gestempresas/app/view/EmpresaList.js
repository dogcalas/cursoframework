
Ext.define('GestEmpresas.view.EmpresaList', {
    extend: 'Ext.container.Viewport',
    alias: 'widget.empresalist',

    layout: {
        type: 'fit'
    },

    initComponent: function() {
        UCID.portal.cargarAcciones(window.parent.idFuncionalidad);
        var me = this;

        Ext.applyIf(me, {
            items: [
                {
                    xtype: 'panel',
                    layout: {
                        type: 'border'
                    },
                    dockedItems: [
                        {
                            xtype: 'toolbar',
                            dock: 'top',
                            items: [
                                {
                                    xtype: 'button',
                                    action: 'adicionar',
                                    id: 'btnAddEmpresa',
                                    hidden: true,
                                    text: perfil.etiquetas.lbBtnAdicionar,
                                    icon: perfil.dirImg + 'adicionar.png',
                                    iconCls: 'btn'
                                },
                                {
                                    xtype: 'button',
                                    action: 'modificar',
                                    id: 'btnModEmpresa',
                                    hidden: true,
                                    text: perfil.etiquetas.lbBtnModificar,
                                    icon: perfil.dirImg + 'modificar.png',
                                    iconCls: 'btn'
                                },
                                {
                                    xtype: 'button',
                                    action: 'eliminar',
                                    id: 'btnDelEmpresa',
                                    hidden: true,
                                    text: perfil.etiquetas.lbBtnEliminar,
                                    icon: perfil.dirImg + 'eliminar.png',
                                    iconCls: 'btn'
                                },
                                '->',
                                {
                                    xtype: 'searchfield',
                                    store: 'stEmpresas',
                                    width: 400,
                                    fieldLabel: perfil.etiquetas.lbBtnBuscar,
                                    labelWidth: 40,
                                    filterPropertysNames: ['descripcion', 'actividad','direccion']
                                }
                            ]
                        }
                    ],
                    items: [
                        {
                            xtype: 'gridpanel',
                            region: 'center',
                            id: 'lista',
                            title: 'Empresas de pr&aacute;cticas',
                            store: 'stEmpresas',
                            viewConfig:{
                                    getRowClass: function(record, rowIndex, rowParams, store){
                                        if (record.data.estado == false)
                                            return 'FilaRoja';
                                    }
                                },

                            columns: [
                                {
                                    xtype: 'gridcolumn',
                                    dataIndex: 'ruc',
                                    text: 'Ruc'
                                },
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
                                },
                                {
                                    xtype: 'gridcolumn',
                                    dataIndex: 'telefono',
                                    width: 119,
                                    text: 'Teléfono'
                                },
                                {
                                    xtype: 'gridcolumn',
                                    dataIndex: 'direccion',
                                    flex: 2,
                                    text: 'Dirección'
                                }
                            ],
                            dockedItems: [
                                {
                                    xtype: 'pagingtoolbar',
                                    dock: 'bottom',
                                    id: 'paginator',
                                    store:'stEmpresas',
                                    width: 360,
                                    displayInfo: true
                                }
                            ]
                        }
                    ]
                }
            ]
        });

        me.callParent(arguments);
    }

});