Ext.define('GestNotas.view.nota.WindowSearch', {
    extend: 'Ext.window.Window',
    alias: 'widget.windowsearch',
    layout: 'fit',
    modal: true,
    resizable: false,
    autoShow: true,
    width: 600,
    height: 200,
    initComponent: function () {
        var me = this;
        if (me.who == 'alumnolist') {
            me.title = "Buscar alumno:";
            me.items = [
                {
                    xtype: 'form',
                    layout: 'fit',
                    tbar: [
                        {
                            xtype: 'searchfield',
                            store: 'GestNotas.store.Alumnos',
                            width: 400,
                            fieldLabel: perfil.etiquetas.lbBtnBuscar,
                            labelWidth: 40,
                            padding: '5',
                            filterPropertysNames: ["nombre", "apellidos", "facultad", "codigo", "cedula", "pasaporte"]
                        }
                    ],
                    items: [
                        Ext.create('GestNotas.view.nota.AlumnoList')
                    ]
                }
            ];
        } else {
            me.title = "Buscar curso:";
            me.items = [
                {
                    xtype: 'form',
                    layout: 'fit',
                    tbar: [
                        {
                            xtype: 'searchfield',
                            store: 'GestNotas.store.Cursos',
                            width: 400,
                            fieldLabel: perfil.etiquetas.lbBtnBuscar,
                            labelWidth: 40,
                            padding: '5',
                            filterPropertysNames: ["codmateria", "descripcion"],
                            listeners: {
                                afterrender: function (field) {
                                    field.focus();
                                }
                            }
                        }
                    ],
                    items: [
                        Ext.create('GestNotas.view.nota.CursoList')
                    ]
                }
            ];
        }
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
                id: 'idBtnAceptar',
                icon: perfil.dirImg + 'aceptar.png',
                iconCls: 'btn',
                text: perfil.etiquetas.lbBtnAceptar,
                list: me.who,
                action: 'aceptar'
            }
        ];

        me.callParent(arguments);
    }
})