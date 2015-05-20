Ext.define('GestMateriaxMencion.view.materiaxmencion.Toolbar', {
    extend: 'Ext.toolbar.Toolbar',
    alias: 'widget.materiaxmencion_tbar',

    initComponent: function () {
        UCID.portal.cargarAcciones(window.parent.idFuncionalidad);
        var me = this;

        me.items = [
            {
                text: 'Eliminar',//perfil.etiquetas.lbBtnBuscar,
                icon: perfil.dirImg + 'eliminar.png',
                iconCls: 'btn',
                action: 'eliminar',
                disabled: true,
                hidden: true,
                id:'btnDelMatxMenc'
            }
        ];

        me.callParent(arguments);
    }
});
