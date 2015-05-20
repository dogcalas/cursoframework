Ext.define('GestFaltas.view.faltas.FaltaListToolBar', {
    extend: 'Ext.toolbar.Toolbar',
    alias: 'widget.faltalisttbar',
    store: 'GestFaltas.store.Periodos',
    initComponent: function () {
        UCID.portal.cargarAcciones(window.parent.idFuncionalidad);
        var me = this;
        this.items = [
            {
                xtype: 'fieldset',
                //columnWidth: 3.2 / 4,
                id: 'idstudentinfo',
                alias: 'widget.idstudentinfo',
                padding: '0 0 0 5',
                //height: 100,
                width: 350,
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
            },
            {
                xtype: 'fieldset',
                width: 280,
                height: 90,
                border: 0,
                style: {paddingTop: '0px'},
                items: [
                    {
                        xtype: 'combobox',
                        id: 'anno',
                        //editable: false,
                        labelAlign: 'top',
                        labelWidth: 50,
                        width: 70,
                        fieldLabel: 'Año',
                        queryMode: 'local',
                        valueField: 'anno',
                        displayField: 'anno',
                        store: 'GestFaltas.store.Annos'
                    },
                    {
                        xtype: 'combobox',
                        id: 'periodoList',
                        editable: false,
                        labelWidth: 50,
                        width: 260,
                        forceSelection: true,
                        labelAlign: 'top',
                        autoSelect: true,
                        emptyText: '--seleccione--',
                        fieldLabel: 'Período',
                        queryMode: 'local',
                        valueField: 'idperiododocente',
                        displayField: 'descripcion',
                        allowBlank: false,
                        store: me.store
                    }
                ]
            },
            {
                xtype: 'fieldset',
                //columnWidth: 1/7,
                width: 180,
                title: 'Tipo de ingreso',
                style: {top: '10px !important'},
                items: [
                    {
                        xtype: 'radiogroup',
                        id: 'radio',
                        columns: 2,
                        vertical: true,
                        items: [
                            {boxLabel: 'Por alumno', name: 'rb', inputValue: 'alumnolist', checked: true},
                            {boxLabel: 'Por curso', name: 'rb', inputValue: 'cursolist'}
                        ]
                    }
                ]
            },
            {
                id: 'idBtnSrhFalta',
                //columnWidth: 1/7,
                rowspan: 3,
                iconAlign: 'top',
                text: perfil.etiquetas.lbBtnBuscar,
                icon: perfil.dirImg + 'buscar.png',
                iconCls: 'btn',
                action: 'buscar',
                style: {marginLeft: '5px'}

            },
            '->',
            {
                id: 'idBtnImprimir',
                //columnWidth: 1/7,
                rowspan: 3,
                iconAlign: 'top',
                text: perfil.etiquetas.lbBtnImprimir,
                icon: perfil.dirImg + 'imprimir.png',
                iconCls: 'btn',
                action: 'imprimir',
                disabled: true,
                hidden: true
            },
            {
                id: 'idBtnHistorico',
                //columnWidth: 1/7,
                rowspan: 3,
                iconAlign: 'top',
                text: 'Histórico',
                icon: perfil.dirImg + 'historiales.png',
                iconCls: 'btn',
                action: 'historial',
                hidden: true
            }
        ];

        this.callParent(arguments);
    }
});