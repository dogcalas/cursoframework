
Ext.define('Sancion.view.SancionList', {
    extend: 'Ext.panel.Panel',
    alias: 'widget.sancionlist',

    layout: {
        type: 'fit'
    },
    title: '',

    initComponent: function() {
        var me = this;

        Ext.applyIf(me, {
            items: [
                {
                    xtype: 'gridpanel',
                    title: 'Sanciones',
                    id:'lista',
                    store: 'stSancion',
                    columns: [
                        {
                            xtype: 'gridcolumn',
                            dataIndex: 'cedula',
                            text: 'Cedula'
                        },
                        {
                            xtype: 'gridcolumn',
                            dataIndex: 'alumno',
                            flex: 3,
                            text: 'Nombre'
                        },
                        {
                            xtype: 'datecolumn',
                            dataIndex: 'fechainicio',
                            text: 'Fecha de inicio'
                        },
                        {
                            xtype: 'datecolumn',
                            dataIndex: 'fechafin',
                            text: 'Fecha de fin'
                        }
                    ],
                    plugins: [{
                        ptype: 'rowexpander',
                        rowBodyTpl : new Ext.XTemplate(
                            '<p><b>Descripci&oacute;n:</b> {descripcion}</p>')
                    }],
                    dockedItems: [
                        {
                            xtype: 'pagingtoolbar',
                            dock: 'bottom',
                            id: 'paginatorsancion',
                            width: 360,
                            store: 'stSancion',
                            displayInfo: true
                        }
                    ]
                }
            ],
            dockedItems: [
                {
                    xtype: 'toolbar',
                    dock: 'top',
                    items: [
                            {
                                xtype: 'button',
                                action: 'adicionar',
                                id:'adicionarBtn',
                                text: perfil.etiquetas.lbBtnAdicionar,
                                icon: perfil.dirImg + 'adicionar.png',
                                iconCls: 'btn'
                            },
                            {
                                xtype: 'button',
                                action: 'modificar',
                                id:'modificarBtn',                                    
                                text: perfil.etiquetas.lbBtnModificar,
                                icon: perfil.dirImg + 'modificar.png',
                                iconCls: 'btn',
                                disabled: true
                            },
                            {
                                xtype: 'button',
                                action: 'eliminar',
                                id:'eliminarBtn',
                                text: perfil.etiquetas.lbBtnEliminar,
                                icon: perfil.dirImg + 'eliminar.png',
                                iconCls: 'btn',
                                disabled: true
                            },"->",
                            {
                                xtype: 'searchfield',
                                store: 'stSancion',
                                width: 400,
                                fieldLabel: perfil.etiquetas.lbBtnBuscar,
                                labelWidth: 40,
                                padding:'5',
                                filterPropertysNames: ["nombre", "apellidos", "cedula"]
                            }
                    ]
                }
            ]
        });

        me.callParent(arguments);
    }

});