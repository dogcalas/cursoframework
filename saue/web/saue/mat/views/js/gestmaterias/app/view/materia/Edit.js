Ext.define('GestMaterias.view.materia.Edit', {
    extend: 'Ext.window.Window',
    alias: 'widget.materiaedit',

    layout: 'fit',
    modal: true,
    autoHeight: true,
    autoShow: true,
    width: 600,

    initComponent: function () {
        var me = this;

        me.items =
        {
            xtype: 'form',
            border: 0,
            fieldDefaults: {
                anchor: '100%',
                msgTarget: 'side'
            },
            padding: '10 10 10 10',
            items: [
                {
                    xtype: 'container',
                    layout: 'hbox',
                    items: [
                        {
                            xtype: 'container',
                            flex: 1,
                            padding: '0 5 0 0',
                            layout: 'anchor',
                            items: [
                                {
                                    xtype: 'hidden',
                                    name: 'idmateria'
                                },
                                {
                                    xtype: 'textfield',
                                    fieldLabel: perfil.etiquetas.lbCmpCodMateria,
                                    name: 'codmateria',
                                    allowBlank: false,
                                    labelAlign: 'top',
                                    blankText: 'Campo requerido'
                                },
                                {
                                    xtype: 'textfield',
                                    fieldLabel: perfil.etiquetas.lbCmpDescripcion,
                                    name: 'descripcion',
                                    allowBlank: false,
                                    labelAlign: 'top',
                                    blankText: 'Campo requerido'
                                },
                                {
                                    xtype: 'textfield',
                                    fieldLabel: perfil.etiquetas.lbCmpTraduccion,
                                    name: 'traduccion',
                                    allowBlank: false,
                                    labelAlign: 'top',
                                    blankText: 'Campo requerido'
                                }
                            ]
                        }, {
                            xtype: 'container',
                            flex: 1,
                            layout: 'anchor',
                            padding: '0 0 0 5',
                            items: [
                                {
                                    xtype: 'combobox',
                                    editable: false,
                                    id: 'ididiomam',
                                    labelAlign: 'top',
                                    store: Ext.create('GestMaterias.store.IdiomasM'),
                                    queryMode: 'local',
                                    name: 'ididiomam',
                                    valueField: 'ididiomam',
                                    displayField: 'descripcion',
                                    emptyText: perfil.etiquetas.lbEmpCombo,
                                    allowBlank: false,
                                    //value: null,
                                    fieldLabel: perfil.etiquetas.lbCmpIdioma
                                },
                                {
                                    xtype: 'numberfield',
                                    fieldLabel: perfil.etiquetas.lbCmpNotaMinima,
                                    name: 'min_nota_materia',
                                    allowBlank: false,
                                    blankText: 'Campo requerido',
                                    labelAlign: 'top',
                                    value: 70,
                                    minValue: 1,
                                    allowDecimals: false
                                },
                                {
                                    xtype: 'tipo_materia_combo'
                                }
                            ]
                        }
                    ]
                },
                {
                    xtype: 'materia_idioma_fieldset'

                },
                {
                    xtype: 'container',
                    layout: {
                        type: 'hbox',
                        align: 'middle'
                    },
                    items: [
                        {
                            id: 'idobligatoria',
                            name: 'obligatoria',
                            xtype: 'checkbox',
                            fieldLabel: perfil.etiquetas.lbCmpObligatoria,
                            checked: true,
                            inputValue: true,
                            uncheckedValue: false,
                            flex: 1,
                            labelWidth: 80,
                            padding: '0 5 5 0'
                        },
                        {
                            id: 'idestado_materia',
                            name: 'estado',
                            xtype: 'checkbox',
                            fieldLabel: perfil.etiquetas.lbHdrEstado,
                            checked: true,
                            inputValue: true,
                            uncheckedValue: false,
                            flex: 1,
                            labelWidth: 70,
                            padding: '0 5 5 0'
                        }
                    ]
                },

            ]
            /*items: [
             {
             xtype: 'hidden',
             name: 'idmateria'
             },
             {
             xtype: 'textfield',
             fieldLabel: perfil.etiquetas.lbCmpCodMateria,
             name: 'codmateria',
             allowBlank: false,
             labelAlign: 'top',
             blankText: 'Campo requerido'
             },
             {
             xtype: 'textareafield',
             fieldLabel: perfil.etiquetas.lbCmpDescripcion,
             name: 'descripcion',
             allowBlank: false,
             labelAlign: 'top',
             blankText: 'Campo requerido'
             },
             {
             xtype: 'textareafield',
             fieldLabel: perfil.etiquetas.lbCmpTraduccion,
             name: 'traduccion',
             allowBlank: false,
             labelAlign: 'top',
             blankText: 'Campo requerido'
             },{
             xtype: 'combobox',
             editable: false,
             id: 'ididiomam',
             labelAlign: 'top',
             store: Ext.create('GestMaterias.store.IdiomasM'),
             queryMode: 'local',
             name: 'ididiomam',
             valueField: 'ididiomam',
             displayField: 'descripcion',
             emptyText: perfil.etiquetas.lbEmpCombo,
             allowBlank: false,
             //value: null,
             fieldLabel: perfil.etiquetas.lbCmpIdioma
             },
             {
             xtype: 'numberfield',
             fieldLabel: perfil.etiquetas.lbCmpNotaMinima,
             name: 'min_nota_materia',
             allowBlank: false,
             blankText: 'Campo requerido',
             labelAlign: 'top',
             value: 70,
             minValue: 1,
             allowDecimals: false
             },
             {
             id: 'idobligatoria',
             name: 'obligatoria',
             xtype: 'checkbox',
             fieldLabel: perfil.etiquetas.lbCmpObligatoria,
             checked: true,
             inputValue: true,
             uncheckedValue: false
             },
             {
             xtype: 'tipo_materia_combo'
             },
             {
             xtype: 'materia_idioma_fieldset'
             },
             {
             id: 'idestado_materia',
             name: 'estado',
             xtype: 'checkbox',
             fieldLabel: perfil.etiquetas.lbHdrEstado,
             checked: true,
             inputValue: true,
             uncheckedValue: false
             }
             ]*/
        }
        ;

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
                id: 'idBtnAceptar',
                icon: perfil.dirImg + 'aceptar.png',
                iconCls: 'btn',
                text: perfil.etiquetas.lbBtnAceptar,
                action: 'aceptar'
            }
        ];

        me.callParent(arguments);
    }
})