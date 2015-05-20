Ext.define('GestEnfasis.view.enfasi.EnfasiListToolBar', {
    extend: 'Ext.toolbar.Toolbar',
    alias: 'widget.enfasilisttbar',

    initComponent: function () {
        UCID.portal.cargarAcciones(window.parent.idFuncionalidad);
        var me = this;

        me.items = [
            {
                id: 'idBtnAddEnfasi',
                text: perfil.etiquetas.lbBtnAdicionar,
                icon: perfil.dirImg + 'adicionar.png',
                iconCls: 'btn',
                hidden: true,
                action: 'adicionar'
            },
            {
                id: 'idBtnUpdEnfasi',
                disabled: true,
                hidden: true,
                text: perfil.etiquetas.lbBtnModificar,
                icon: perfil.dirImg + 'modificar.png',
                iconCls: 'btn',
                action: 'modificar'
            },
            {
                id: 'idBtnDelEnfasi',
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
                store: Ext.create('GestEnfasis.store.Carreras'),
                name: 'idcarrera',
                valueField: 'idcarrera',
                displayField: 'descripcion',
                storeToFilter: Ext.StoreMgr.lookup('idStoreEnfasis'),
                //filterPropertysNames: ['idfacultad'],
                emptyText: 'Filtrar por carrera',
                width: 250,
                padding: '0 0 0 5',
                labelWidth: 40
            },
            {
                xtype: 'searchfield',
                store: Ext.StoreMgr.lookup('idStoreEnfasis'),
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