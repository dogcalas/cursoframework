Ext.define('MatEst.view.matx.StudentInfo', {
    extend: 'Ext.form.Panel',
    alias: 'widget.studentinfo',
    layout: 'column',
    border: 0,
    items: [
        {
            xtype: 'fieldset',
            //columnWidth: 3.2 / 4,
            id: 'idstudentinfo',
            alias: 'widget.idstudentinfo',
            //height: 100,
            width: '100%',
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
                    labelWidth: 60,
                    id: 'studentCodigo',
                    value: '-'
                }, {
                    xtype: 'displayfield',
                    hidden: true,
                    id: 'cedula'
                }, {
                    xtype: 'displayfield',
                    labelWidth: 60,
                    id: 'studentNombre',
                    value: '-'
                }, {
                    xtype: 'displayfield',
                    labelWidth: 60,
                    id: 'studentFacultad',
                    value: '-'
                }, {
                    xtype: 'displayfield',
                    labelWidth: 60,
                    id: 'studentCarrera',
                    value: '-'
                }, {
                    xtype: 'displayfield',
                    labelWidth: 60,
                    id: 'studentItinerario',
                    value: '-'
                }, {
                    xtype: 'displayfield',
                    hidden: true,
                    fieldLabel: 'Alumno',
                    id: 'idalumno'
                }
            ]
        }
    ],

    initComponent: function () {
        var me = this;
        me.callParent(arguments);
    }
});