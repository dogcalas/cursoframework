Ext.define('GestNotas.view.nota.NotaListToolBar', {
    extend: 'Ext.toolbar.Toolbar',
    alias: 'widget.notalisttbar',
    store: 'GestNotas.store.Periodos',
    initComponent: function () {
        var me = this;
        UCID.portal.cargarAcciones(window.parent.idFuncionalidad);
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
                        fieldLabel: 'Año',
                        queryMode: 'local',
                        labelAlign: 'top',
                        labelWidth: 50,
                        width: 70,
                        //editable:false,
                        valueField: 'anno',
                        displayField: 'anno',
                        store: 'GestNotas.store.Annos'
                    },
                    {
                        xtype: 'combobox',
                        id: 'periodoList',
                        forceSelection: true,
                        labelWidth: 50,
                        width: 260,
                        autoSelect: true,
                        labelAlign: 'top',
                        emptyText: '--seleccione--',
                        fieldLabel: 'Período',
                        queryMode: 'local',
                        valueField: 'idperiododocente',
                        displayField: 'descripcion',
                        allowBlank: false,
                        editable:false,
                        store: me.store
                    }
                ]
            },
            {
                xtype: 'fieldset',
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
                id: 'idBtnSrhNota',
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
                rowspan: 3,
                iconAlign: 'top',
                text: perfil.etiquetas.lbBtnImprimir,
                icon: perfil.dirImg + 'imprimir.png',
                iconCls: 'btn',
                action: 'imprimir',
                disabled: true
            },
            {
                id: 'idHistorico',
                rowspan: 3,
                iconAlign: 'top',
                text: perfil.etiquetas.lbBtnHistorico,
                icon: perfil.dirImg + 'historiales.png',
                iconCls: 'btn',
                //disabled: true,
                hidden: true,
                action: 'historial'
            }
        ];

        this.callParent(arguments);
    }
});