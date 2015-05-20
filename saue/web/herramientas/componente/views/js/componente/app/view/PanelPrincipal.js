Ext.define('GestComponentes.view.PanelPrincipal', {
    extend: 'Ext.panel.Panel',
    alias: 'widget.panelprincipal',
    layout: 'border',
    title: perfil.etiquetas.lbTitVentana,
    initComponent: function() {

        this.items = [{xtype: 'panelarbolcomp'}, {xtype: 'tabpanelgeneral', region: 'center', itemId: 'tabgeneral'
            }];
        this.callParent();
    },
});
