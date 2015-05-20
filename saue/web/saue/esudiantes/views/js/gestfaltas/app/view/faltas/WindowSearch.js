Ext.define('GestFaltas.view.faltas.WindowSearch', {
    extend: 'Ext.window.Window',
    alias: 'widget.windowsearch',

    //title: perfil.etiquetas.lbTtlEditar,
    layout: 'fit',
    modal: true,
    resizable: false,
    autoShow: true,
    width:600,
    height:200,
    initComponent: function () {
        var me = this;
        if(me.who == 'alumnolistf'){
            me.title = "Buscar alumno:";
            me.items = [
                {
                    xtype: 'form',
                    layout: 'fit',
                    tbar:[ {
                    xtype: 'searchfield',
                    store: 'GestFaltas.store.Alumnos',
                    width: 400,
                    fieldLabel: perfil.etiquetas.lbBtnBuscar,
                    labelWidth: 40,
                    padding:'5',
                    filterPropertysNames: ["nombre", "apellidos","facultad","codigo","cedula", "pasaporte"]
                }],
                    items: [
                        Ext.create('GestFaltas.view.faltas.AlumnoList')
                    ]
                }
            ];
        } else {
            me.title = "Buscar curso:";
            me.items = [
                {
                    xtype: 'form',
                    layout: 'fit',
                    tbar:[{
                            xtype: 'searchfield',
                            store: 'GestFaltas.store.Cursos',
                            width: 400,
                            fieldLabel: perfil.etiquetas.lbBtnBuscar,
                            labelWidth: 40,
                            padding:'5',
                            filterPropertysNames: ["codmateria", "descripcion"]
                        } ],
                    items: [
                        Ext.create('GestFaltas.view.faltas.CursoList')
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
                list:me.who,
                action: 'aceptar'
            }
        ];

        me.callParent(arguments);
    }
})