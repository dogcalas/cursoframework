Ext.define('GestCursos.view.curso.Edit', {
    extend: 'Ext.window.Window',
    alias: 'widget.curso_edit',

    layout: 'fit',
    modal: true,
    resizable: false,
    autoShow: true,
    width: 500,

    initComponent: function () {
        
        this.items = [
            {
                xtype: 'form',
                fieldDefaults: {
                    msgTarget: 'side',
                    anchor: '100%',
                    labelWidth: 60
                },
                defaults: {
                    padding: '5'
                },
                defaultType: 'combo',
                items: [
                    {
                        xtype: 'hidden',
                        name: 'idcurso'
                    },
                    {
                        xtype: 'hidden',
                        name: 'idprofesor'
                    },
                    {
                        xtype: 'hidden',
                        name: 'idmateria'
                    },
                    {
                        xtype: 'hidden',
                        name: 'idperiododocente'
                    },
                    {
                        xtype: 'hidden',
                        name: 'idhorario'
                    },
                    {
                        xtype: 'fieldcontainer',
                        layout: 'hbox',
                        defaultType: 'combo',
                        items: [
                            {
                                xtype: 'combo',
                                fieldLabel: perfil.etiquetas.lbCmpAnno,
                                width: 90,
                                id: 'anno_edit',
                                name: 'anno_edit',
                                labelAlign: 'top',
                                editable: false,
                                allowBlank: false,
                                disabled: true,
                                valueField: 'anno',
                                displayField: 'anno',
                                store: 'GestCursos.store.AnnoE',
                                queryMode: 'local'                                
                            },
                            {   
                                padding: '0 0 0 10',
                                xtype: 'combo',
                                flex: 1,
                                width: 350,
                                id: 'periodo_descripcion_edit',
                                name: 'periodo_descripcion_edit',
                                fieldLabel: perfil.etiquetas.lbCmpPeriodo,
                                emptyText: perfil.etiquetas.lbEmpCombo,
                                labelAlign: 'top',
                                editable: false,
                                allowBlank: false,
                                disabled: true,
                                valueField: 'idperiododocente',
                                displayField: 'descripcion',
                                store: 'GestCursos.store.PeriodosEdit',
                                queryMode: 'local'
                            }
                        ]
                    },
                    {
                        xtype: 'triggerfield',
                        fieldLabel: perfil.etiquetas.lbHdrHorario,
                        emptyText: perfil.etiquetas.lbEmpCombo,
                        trigger1Cls: Ext.baseCSSPrefix + 'form-search-trigger',
                        name: 'horario_descripcion',
                        id: 'horario_descripcion',
                        editable: false,
                        allowBlank: false,
                        labelAlign: 'top',
                        disabled: true,
                        onTrigger1Click: function () {
                            var window = Ext.create('GestCursos.view.horario.Search');
                        }
                    },
                    {
                        fieldLabel: perfil.etiquetas.lbHdrAula,
                        emptyText: perfil.etiquetas.lbEmpCombo,
                        name: 'idaula',
                        valueField: 'idaula',
                        displayField: 'aula',
                        labelAlign: 'top',
                        disabled: true,
                        store: 'GestCursos.store.Aulas',
                        editable: false,
                        queryMode: 'local',
                        allowBlank: false
                    },
                    {
                        xtype: 'triggerfield',
                        fieldLabel: perfil.etiquetas.lbHdrProfesor,
                        emptyText: perfil.etiquetas.lbEmpCombo,
                        trigger1Cls: Ext.baseCSSPrefix + 'form-search-trigger',
                        name: 'profesor_nombre_completo',
                        editable: false,
                        labelAlign: 'top',
                        disabled: true,
                        allowBlank: false,
                        onTrigger1Click: function () {
                            var window = Ext.create('GestCursos.view.profesor.Search');
                            window.down('curso_profesor_grid').getStore().load();
                        }
                    },
                    {
                        xtype: 'triggerfield',
                        fieldLabel: perfil.etiquetas.lbHdrMateria,
                        emptyText: perfil.etiquetas.lbEmpCombo,
                        trigger1Cls: Ext.baseCSSPrefix + 'form-search-trigger',
                        name: 'materia_descripcion',
                        editable: false,
                        labelAlign: 'top',
                        disabled: true,
                        allowBlank: false,
                        onTrigger1Click: function () {
                            var window = Ext.create('GestCursos.view.materia.Search');
                            window.down('curso_materia_grid').getStore().load();
                        }
                    },
                    {
                        fieldLabel: perfil.etiquetas.lbCmpFacultad,
                        emptyText: perfil.etiquetas.lbEmpCombo,
                        name: 'idfacultad',
                        valueField: 'idfacultad',
                        displayField: 'denominacion',
                        store: 'GestCursos.store.Facultades',
                        editable: false,
                        labelAlign: 'top',
                        disabled: true,
                        queryMode: 'local',
                        allowBlank: false
                    },
                    {
                        xtype: 'fieldset',
                        title: perfil.etiquetas.lbCmpDetalles,
                        layout: 'hbox',
                        items: [
                            {
                                xtype: 'displayfield',
                                fieldLabel: perfil.etiquetas.lbCmpAlumnos,
                                name: 'n_alumnos',
                                flex: 1
                            },
                            {
                                xtype: 'textfield',
                                fieldLabel: perfil.etiquetas.lbCmpCupos,
                                name: 'cupo',
                                disabled: true,
                                padding: '0 0 10 10',
                                flex: 2,
                                allowBlank: false
                            },
                            {
                                xtype: 'textfield',
                                fieldLabel: perfil.etiquetas.lbCmpParalelos,
                                name: 'par_curs',
                                disabled: true,
                                padding: '0 0 10 10',
                                flex: 2,
                                allowBlank: false
                            }
                        ]
                    },
                    {
                        id: 'idestado_carrera',
                        xtype: 'checkbox',
                        fieldLabel: perfil.etiquetas.lbHdrEstado,
                        checked: true,
                        inputValue: true,
                        uncheckedValue: false,
                        name: 'estado'
                    }
                ]
            }
        ];

        this.buttons = [
            {
                icon: perfil.dirImg + 'cancelar.png',
                iconCls: 'btn',
                text: perfil.etiquetas.lbBtnCancelar,
                action: 'cancelar',
                scope: this,
                handler: this.close
            },
            {
                icon: perfil.dirImg + 'aplicar.png',
                iconCls: 'btn',
                text: perfil.etiquetas.lbBtnAplicar,
                action: 'aplicar'
            },
            {
                icon: perfil.dirImg + 'aceptar.png',
                iconCls: 'btn',
                text: perfil.etiquetas.lbBtnAceptar,
                action: 'aceptar'
            }
        ];

        this.callParent(arguments);
    }
})