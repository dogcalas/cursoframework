Ext.define('GestConv.view.StudentInfo', {
    extend: 'Ext.form.Panel',
    alias: 'widget.studentinfo',
    layout: 'column',
    border: 0,
    items: [
        {
            xtype: 'fieldset',
            columnWidth: 3 / 4,
            id: 'idstudentinfo',
            alias: 'widget.idstudentinfo',
            padding: '5 0 5 5',
            height: '92.5%',
            layout: 'anchor',
            border: 1,
            style: {
                borderColor: 'red',
                borderStyle: 'solid'
            },
            title: "<b>" + 'DATOS DEL ALUMNO' + "</b>",
            items: [
                {
                    xtype: 'displayfield',
                    fieldLabel: 'C&oacute;digo',
                    labelWidth: 60,
                    id: 'studentCodigo',
                    value: '-'
                }, {
                    xtype: 'displayfield',
                    hidden: true,
                    fieldLabel: 'Cédula',
                    id: 'cedula'
                }, {
                    xtype: 'displayfield',
                    fieldLabel: 'Nombre',
                    labelWidth: 60,
                    id: 'studentNombre',
                    value: '-'
                }, {
                    xtype: 'displayfield',
                    fieldLabel: 'Facultad',
                    labelWidth: 60,
                    id: 'studentFacultad',
                    value: '-'
                }, {
                    xtype: 'displayfield',
                    hidden: true,
                    fieldLabel: 'Alumno',
                    id: 'idalumno'
                }
            ]
        }, {
            columnWidth: 1 / 4,
            padding: '35 0 0 35',
            border: 0,
            items: [
                {
                    xtype: 'button',
                    id: 'studentinfo',
                    iconAlign: 'top',
                    text: "Alumno",
                    icon: perfil.dirImg + 'buscar.png',
                    iconCls: 'btn',
                    action: 'buscar'
                }
            ]

        }
    ],

    initComponent: function () {
        var me = this;
        me.callParent(arguments);
    }
});