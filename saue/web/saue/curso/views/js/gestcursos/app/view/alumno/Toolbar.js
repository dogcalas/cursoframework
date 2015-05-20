Ext.define('GestCursos.view.alumno.Toolbar', {
    extend: 'Ext.toolbar.Toolbar',
    alias: 'widget.curso_alumno_toolbar',

    initComponent: function () {
        var me = this;

        me.items = [
            {
                xtype: 'form',
                autoHeight: true,
                flex: 6,
                border: 0,
                defaults: {
                    anchor: '100%'
                },
                padding: '5',
                items: [
                    {
                        xtype: 'fieldcontainer',
                        layout: 'hbox',
                        defaultType: 'displayfield',
                        defaults: {
                            flex: 1,
                            labelWidth: 60,
                            labelStyle: 'font-weight: bold'
                        },
                        items: [
                            {
                                name: 'anno',
                                fieldLabel: 'Año'
                            },
                            {
                                name: 'periodo',
                                fieldLabel: 'Período'
                            }
                        ]
                    },
                    {
                        xtype: 'fieldcontainer',
                        layout: 'hbox',
                        defaultType: 'displayfield',
                        defaults: {
                            flex: 1,
                            labelWidth: 60,
                            labelStyle: 'font-weight: bold'

                        },
                        items: [
                            {
                                name: 'horario_descripcion',
                                fieldLabel: 'Horario'
                            },
                            {
                                name: 'materia_descripcion',
                                fieldLabel: 'Materia'
                            }
                        ]
                    },
                    {
                        xtype: 'fieldcontainer',
                        layout: 'hbox',
                        defaultType: 'displayfield',
                        defaults: {
                            flex: 1,
                            labelWidth: 60,
                            labelStyle: 'font-weight: bold'
                        },
                        items: [
                            {
                                name: 'aula_descripcion',
                                fieldLabel: 'Aula'
                            },
                            {
                                name: 'profesor_nombre_completo',
                                fieldLabel: 'Profesor'
                            }
                        ]
                    }
                ]
            },'->',
            {
                text: 'Enviar correo',
                icon: perfil.dirImg + 'cargocivil.png',
                scale: 'medium',
                iconAlign: 'top',
                action: 'sendmail'
            }, {
                text: 'Imprimir listado',
                icon: perfil.dirImg + 'imprimir.png',
                scale: 'medium',
                iconAlign: 'top',
                action: 'printtoolbar'
            }
        ];

        this.callParent(arguments);
    }
});