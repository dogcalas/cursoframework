Ext.define('GestPensums.view.pensum.PensumListToolBar', {
    extend: 'Ext.toolbar.Toolbar',
    alias: 'widget.pensumlisttbar',

    initComponent: function () {
        UCID.portal.cargarAcciones(window.parent.idFuncionalidad);
        var me = this;

        me.items = [
            {
                id: 'idBtnAddPensum',
                text: perfil.etiquetas.lbBtnAdicionar,
                icon: perfil.dirImg + 'adicionar.png',
                iconCls: 'btn',
                hidden: true,
                action: 'adicionar'
            },
            {
                id: 'idBtnUpdPensum',
                disabled: true,
                hidden: true,
                text: perfil.etiquetas.lbBtnModificar,
                icon: perfil.dirImg + 'modificar.png',
                iconCls: 'btn',
                action: 'modificar'
            },
            {
                id: 'idBtnDelPensum',
                disabled: true,
                hidden: true,
                text: perfil.etiquetas.lbBtnEliminar,
                icon: perfil.dirImg + 'eliminar.png',
                iconCls: 'btn',
                action: 'eliminar'
            },
            '->',
            {
                xtype: 'searchcombofield',
                store: Ext.create('GestPensums.store.Carreras'),
                name: 'idcarrera',
                valueField: 'idcarrera',
                displayField: 'descripcion',
                storeToFilter: 'GestPensums.store.Pensums',
                //filterPropertysNames: ['idfacultad'],
                emptyText: 'Filtrar por carrera',
                width: 250,
                padding: '0 0 0 5',
                labelWidth: 40
            },
            {
                xtype: 'searchfield',
                store: 'GestPensums.store.Pensums',
                emptyText: 'Filtrar por descripción',
                width: 250,
                padding: '0 0 0 5',
                labelWidth: 40,
                filterPropertysNames: ['descripcion']
            }
        ];

        me.callParent(arguments);
    }
});