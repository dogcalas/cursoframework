Ext.define('RegMaterias.view.materia.MateriaListToolBar', {
    extend: 'Ext.toolbar.Toolbar',
    alias: 'widget.tbmateria',
    store: 'RegMaterias.store.Periodos',

    initComponent: function () {
        UCID.portal.cargarAcciones(window.parent.idFuncionalidad);
        var me = this;

        this.items = [
            {
                xtype: 'combobox',
                id: 'anno',
                labelAlign: 'top',
                fieldLabel: 'Año',
                queryMode: 'local',
                valueField: 'anno',
                displayField: 'anno',
                editable: true,
                width: 90,
                store: 'RegMaterias.store.Annos'
            },
            {
                xtype: 'searchcombofield',
                id: 'periodoList',
                labelAlign: 'top',
                emptyText: '--seleccione--',
                fieldLabel: 'Período',
                queryMode: 'local',
                valueField: 'idperiododocente',
                displayField: 'descripcion',
                storeToFilter: 'RegMaterias.store.MateriasEst',
                editable: true,
                disabled: true,
                width: 300,
                store: me.store
            },
            {
                xtype: 'searchcombofield',
                id: 'idtipoaprob',
                labelAlign: 'top',
                fieldLabel: 'Estado',
                store: 'RegMaterias.store.TipoAFiltro',
                emptyText: 'Filtrar por tipo de aprobado',//Etiqueta
                name: 'tipoaprob',
                valueField: 'idtipoaprobado',
                displayField: 'descripcion',
                storeToFilter: 'RegMaterias.store.MateriasEst',
                queryMode: 'local',
                editable: true,
                width: 250,

                disabled: true
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
                name: 'BtnAddidioma',
                id: 'idBtnAddidioma',
                rowspan: 3,
                iconAlign: 'top',
                text: perfil.etiquetas.lbBtnAdicionar,
                icon: perfil.dirImg + 'adicionar.png',
                iconCls: 'btn',
                action: 'adicionar',
                disabled: true,
                hidden: true,
                style: {marginLeft: '5px'}
            },
            {
                name: 'BtnModMateria',
                id: 'idBtnModMateria',
                rowspan: 3,
                iconAlign: 'top',
                text: perfil.etiquetas.lbBtnModificar,
                icon: perfil.dirImg + 'modificar.png',
                iconCls: 'btn',
                action: 'modificar',
                disabled: true,
                hidden: true,
                style: {marginLeft: '5px'}
            },
            {
                name: 'BtnElimmateria',
                id: 'idBtnElimmateria',
                rowspan: 3,
                iconAlign: 'top',
                text: perfil.etiquetas.lbBtnEliminar,
                icon: perfil.dirImg + 'eliminar.png',
                iconCls: 'btn',
                action: 'eliminar',
                disabled: true,
                hidden: true,
                style: {marginLeft: '5px'}
            }, {
                name: 'BtnRetirar',
                id: 'idBtnRetirar',
                rowspan: 3,
                iconAlign: 'top',
                text: 'Retirar',
                icon: perfil.dirImg + 'deshacer.png',
                iconCls: 'btn',
                action: 'retirar',
                disabled: true,
                hidden: true,
                style: {marginLeft: '5px'}
            },
            {
                id: 'idBtnImprimir',
                rowspan: 3,
                disabled: true,
                iconAlign: 'top',
                text: perfil.etiquetas.lbBtnImprimir,
                icon: perfil.dirImg + 'imprimir.png',
                iconCls: 'btn',
                hidden: true,
                action: 'imprimir'
            }
        ];

        this.callParent(arguments);
    },

    changeenableboton: function ($value) {
        return this.getComponent('idBtnAddidioma');
    },

    geteditboton: function () {
        return this.getComponent('idBtnModMateria');
    },

    getdeleteboton: function () {
        return this.getComponent('idBtnElimmateria');
    },

    getretiroboton: function () {
        return this.getComponent('idBtnRetirar');
    },
    getimprimir: function () {
        return this.getComponent('idBtnImprimir');
    },
    clearRau: function () {
        return this.getComponent('idtipoaprob');
    }
});