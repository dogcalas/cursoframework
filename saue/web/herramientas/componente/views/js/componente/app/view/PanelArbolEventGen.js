Ext.define('GestComponentes.view.PanelArbolEventGen', {
    extend: 'Ext.tree.Panel',
    alias: 'widget.panelarboleventgen',
    title: perfil.etiquetas.lbTitpanelArbolComp,
    region: 'west',
    width: 170,
    height: 150,
    store: 'ArbolEventosGenStore',
    rootVisible: false,
    itemId: 'arbol-eventgen',
    initComponent: function() {
        this.tbar = [{xtype: 'button',
                disabled: true,
                itemId: 'btnSelEventGen',
                icon: perfil.dirImg + 'adicionar.png',
                iconCls: 'btn',
                text: perfil.etiquetas.lbTitpanelArbolComp

            }];

        this.callParent();
    }




});
