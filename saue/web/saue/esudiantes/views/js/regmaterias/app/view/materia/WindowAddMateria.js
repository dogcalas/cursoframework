Ext.define('RegMaterias.view.materia.WindowAddMateria', {
    extend: 'Ext.window.Window',
    alias: 'widget.addmatwindow',
    layout: 'fit',
    modal: true,
    resizable: false,
    autoShow: true,
    width: 420,

    initComponent: function () {
        var me = this;
        me.title = 'Adicionar Materia';
        me.items = [
            {
                xtype: 'form',
                id: 'idform',
                name: 'formulario',
                frame: true,
                layout: 'column',
                columnWidth: 1,
                padding: '5 5 5',
                url: 'saveMateria',
                items: [

                    {
                        layout: 'anchor',
                        padding: '5 5 5 5',
                        border: 0,
                        width: '100%',
                        items: [

                            {
                                xtype: 'combobox',
                                id: 'annomat',
                                fieldLabel: 'Año',
                                padding: '5 5 5 0',
                                labelAlign: 'top',
                                queryMode: 'local',
                                valueField: 'anno',
                                displayField: 'anno',
                                editable: false,
                                allowBlank: false,
                                store: 'GestNotas.store.Annos',

                                width: 80
                            }

                        ]
                    },
                    {
                        layout: 'anchor',
                        padding: '5 0 5 5',
                        border: 0,
                        width: '100%',
                        items: [

                            {
                                xtype: 'combobox',
                                id: 'descripcion',
                                name: 'descripcion',
                                padding: '5 5 5 0',
                                autoSelect: false,
                                forceSelection: true,
                                autoRender: true,
                                emptyText: '--Seleccione--',
                                fieldLabel: 'Período',
                                labelAlign: 'top',
                                queryMode: 'local',
                                valueField: 'idperiododocente',
                                displayField: 'descripcion',
                                allowBlank: false,
                                editable: false,
                                store: 'RegMaterias.store.PeriodoAdd',
                                width: 285
                            }
                        ]
                    },
                    {
                        xtype: 'combobox',
                        id: 'tipo',
                        name: 'tipo',
                        padding: '5 5 5 5',
                        fieldLabel: 'Estado',
                        labelAlign: 'top',
                        queryMode: 'local',
                        valueField: 'idtipoaprobado',
                        displayField: 'descripcion',
                        allowBlank: false,
                        store: 'RegMaterias.store.TipoAprobado',
                        editable: false,
                        emptyText: 'Seleccione',
                        width: 285
                    },
                    {
                        xtype: 'numberfield',
                        id: 'idnota',
                        name: 'nota',
                        padding: '5 5 5 5',
                        fieldLabel: 'Nota',
                        labelAlign: 'top',
                        maxValue: 100,
                        minValue: 1,
                        //allowBlank: false,
                        width: 80
                    },
                    {
                        xtype: 'triggerfield',
                        fieldLabel: perfil.etiquetas.lbHdrMateria,
                        padding: '5 5 5 5',
                        trigger1Cls: Ext.baseCSSPrefix + 'form-search-trigger',
                        name: 'materia',
                        labelAlign: 'top',
                        editable: false,
                        allowBlank: false,
                        width: '100%',
                        onTrigger1Click: function () {
                            var window = Ext.create('RegMaterias.view.materia.Search');
                            window.down('materiagrid').getStore().load();
                        }
                    },
                    {
                        xtype: 'hidden',
                        name: 'idmateria'
                    },
                    {
                        xtype: 'hidden',
                        name: 'idtipoaprobado'
                    },
                    {
                        xtype: 'hidden',
                        name: 'idalumno_mate_deta'
                    },
                    {
                        xtype: 'hidden',
                        name: 'idperiodo'
                    },
                    {
                        xtype: 'hidden',
                        name: 'minNote'
                    },
                    {
                        xtype: 'textareafield',
                        id: 'txtarea',
                        name: 'observacion',
                        padding: '5 5 5 5',
                        layout: 'fit',
                        width: 378,
                        allowBlank: false,
                        fieldLabel: perfil.etiquetas.lbHdrObservaciones,
                        labelAlign: 'top'
                    }
                ]
            }
        ];

        me.buttons = [
            {
                icon: perfil.dirImg + 'cancelar.png',
                iconCls: 'btn',
                text: perfil.etiquetas.lbBtnCancelar,
                action: 'cancelar',
                scope: this,
                handler: this.close
            },
            {
                id: 'idBtnAplicar',
                icon: perfil.dirImg + 'aplicar.png',
                iconCls: 'btn',
                text: perfil.etiquetas.lbBtnAplicar,
                action: 'aplicar'
            },
            {
                id: 'idBtnAceptarAdd',
                icon: perfil.dirImg + 'aceptar.png',
                iconCls: 'btn',
                text: perfil.etiquetas.lbBtnAceptar,
                list: me.who,
                action: 'aceptar'
            }
        ];

        me.callParent(arguments);
    }
});
