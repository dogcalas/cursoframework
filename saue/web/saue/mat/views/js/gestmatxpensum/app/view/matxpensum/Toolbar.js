Ext.define('GestMatxPensum.view.matxpensum.Toolbar', {
    extend: 'Ext.toolbar.Toolbar',
    alias: 'widget.matxpensum_toolbar',

    initComponent: function () {
        UCID.portal.cargarAcciones(window.parent.idFuncionalidad);
        var me = this;

        me.items = [
            {
                id: 'idBtnDelhMatxPensum',
                text: 'Eliminar',//perfil.etiquetas.lbBtnBuscar,
                icon: perfil.dirImg + 'eliminar.png',
                iconCls: 'btn',
                action: 'eliminar',
                disabled: true,
                hidden: true
            }
        ];

        me.callParent(arguments);
    }
});