Ext.define('App.view.campus.CampusListToolBar', {
    extend: 'Ext.toolbar.Toolbar',
    alias: 'widget.campuslisttbar',

    initComponent: function () {

        UCID.portal.cargarAcciones(window.parent.idFuncionalidad);
        this.items = [
            {
                id: 'idBtnAddCampus',
                text: perfil.etiquetas.lbBtnAdicionar,
                icon: perfil.dirImg + 'adicionar.png',
                iconCls: 'btn',
                hidden: true,
                action: 'adicionar'
            },
            {
                id: 'idBtnUpdCampus',
                text: perfil.etiquetas.lbBtnModificar,
                icon: perfil.dirImg + 'modificar.png',
                iconCls: 'btn',
                hidden: true,
                action: 'modificar',
                disabled: true
            },
            {
                id: 'idBtnDelCampus',
                text: perfil.etiquetas.lbBtnEliminar,
                icon: perfil.dirImg + 'eliminar.png',
                iconCls: 'btn',
                hidden: true,
                action: 'eliminar',
                disabled: true
            }, '->',
            {
                xtype: 'searchfield',
                store: 'App.store.stCampus',
                fieldLabel: '<b>' + perfil.etiquetas.lbBtnBuscar + '</b>',
                width: 400,
                padding: '0 0 0 5',
                labelWidth: 40,
                filterPropertysNames: ['descripcion']
            }
        ];

        this.callParent(arguments);
    }
});