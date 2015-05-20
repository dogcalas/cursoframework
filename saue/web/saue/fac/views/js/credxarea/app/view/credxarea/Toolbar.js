Ext.define('CredxArea.view.credxarea.Toolbar', {
    extend: 'Ext.toolbar.Toolbar',
    alias: 'widget.credxarea_toolbar',

    initComponent: function () {
        UCID.portal.cargarAcciones(window.parent.idFuncionalidad);
        var me = this;

        me.defaults = {
            padding: '0 0 0 5'
        };

        me.items = [
            {
                text: perfil.etiquetas.lbBtnAdicionar,
                icon: perfil.dirImg + 'adicionar.png',
                iconCls: 'btn',
                hidden: true,
                action: 'adicionar',
                id: 'btnAddCredArea'
            },
            {
                disabed: true,
                text: perfil.etiquetas.lbBtnModificar,
                icon: perfil.dirImg + 'modificar.png',
                disabled: true,
                hidden: true,
                iconCls: 'btn',
                action: 'modificar',
                id: 'btnUpdCredArea'
            },
            {
                disabed: true,
                text: perfil.etiquetas.lbBtnEliminar,
                icon: perfil.dirImg + 'eliminar.png',
                disabled: true,
                hidden: true,
                iconCls: 'btn',
                action: 'eliminar',
                id: 'btnDelCredArea'
            },'->',
            {
                xtype: 'searchcombofield',
                store: Ext.create('CredxArea.store.Enfasis'),
                name: 'idenfasis',
                valueField: 'idenfasis',
                displayField: 'descripcion',
                storeToFilter: 'CredxArea.store.CredsxArea',
                //filterPropertysNames: ['idfacultad'],
                width: 250,
                padding: '0 0 0 5',
                emptyText: 'Filtrar por itinerario',
                labelWidth: 38
            },
            {
                xtype: 'searchcombofield',
                store: Ext.create('CredxArea.store.Pensums'),
                name: 'idpensum',
                valueField: 'idpensum',
                displayField: 'descripcion',
                storeToFilter: 'CredxArea.store.CredsxArea',
                //filterPropertysNames: ['idfacultad'],
                width: 250,
                padding: '0 0 0 5',
                emptyText: 'Filtrar por pensum',
                labelWidth: 42
            },
            {
                xtype: 'searchcombofield',
                store: Ext.create('CredxArea.store.AreasGenerales'),
                name: 'idareageneral',
                valueField: 'idareageneral',
                displayField: 'descripcion',
                storeToFilter: 'CredxArea.store.CredsxArea',
                //filterPropertysNames: ['idfacultad'],
                width: 250,
                padding: '0 0 0 5',
                emptyText: 'Filtrar por área general',
                labelWidth: 65
            }
        ];

        me.callParent(arguments);
    }
});
