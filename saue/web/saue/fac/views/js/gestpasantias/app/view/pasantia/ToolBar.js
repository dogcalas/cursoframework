Ext.define('GestPasantias.view.pasantia.ToolBar', {
    extend: 'Ext.toolbar.Toolbar',
    alias: 'widget.pasantia_toolbar',

    initComponent: function () {
        UCID.portal.cargarAcciones(window.parent.idFuncionalidad);
        var me = this;

        me.items = [
            {
                id: 'idBtnAddPasantia',
                text: perfil.etiquetas.lbBtnAdicionar,
                icon: perfil.dirImg + 'adicionar.png',
                iconCls: 'btn',
                hidden: true,
                action: 'adicionar'
            },
            {
                id: 'idBtnUpdPasantia',
                disabled: true,
                hidden: true,
                text: perfil.etiquetas.lbBtnModificar,
                icon: perfil.dirImg + 'modificar.png',
                iconCls: 'btn',
                action: 'modificar'
            },
            {
                id: 'idBtnDelPasantia',
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
                store: Ext.create('GestPasantias.store.Carreras'),//'GestPasantias.store.Carreras',
                emptyText: 'Filtrar por carrera',
                name: 'idcarrera',
                valueField: 'idcarrera',
                displayField: 'descripcion',
                storeToFilter: 'GestPasantias.store.Pasantias',
                //filterPropertysNames: ['idfacultad'],
                width: 180,
                padding: '0 0 0 5',
                labelWidth: 40
            },
            {
                xtype: 'searchcombofield',
                store: Ext.create('GestPasantias.store.Enfasis'),//'GestPasantias.store.Carreras',
                emptyText: 'Filtrar por itinerario',
                name: 'idenfasis',
                valueField: 'idenfasis',
                displayField: 'descripcion',
                storeToFilter: 'GestPasantias.store.Pasantias',
                //filterPropertysNames: ['idfacultad'],
                width: 180,
                padding: '0 0 0 5',
                labelWidth: 40
            },
            {
                xtype: 'searchcombofield',
                store: Ext.create('GestPasantias.store.TipoPasantias'),//'GestPasantias.store.Carreras',
                emptyText: 'Filtrar por tipo de pasantía',
                name: 'idtipopasantia',
                valueField: 'idtipopasantia',
                displayField: 'descripcion',
                storeToFilter: 'GestPasantias.store.Pasantias',
                filterPropertysNames: ['idtipopractica'],
                width: 180,
                padding: '0 0 0 5',
                labelWidth: 40
            },
            {
                xtype: 'searchfield',
                store: 'GestPasantias.store.Pasantias',
                emptyText: 'Filtrar por empresa',
                width: 180,
                padding: '0 0 0 5',
                labelWidth: 40,
                filterPropertysNames: ['empresa']
            }
        ];

        me.callParent(arguments);
    }
});