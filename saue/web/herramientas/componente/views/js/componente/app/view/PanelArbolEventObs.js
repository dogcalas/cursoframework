Ext.define('GestComponentes.view.PanelArbolEventObs', {
    extend: 'Ext.tree.Panel',
    alias: 'widget.panelarboleventobs',
    title: perfil.etiquetas.lbTitpanelArbolComp,
    region: 'west',
    width: 170,
    height: 150,
    store: 'ArbolEventosObsStore',
    rootVisible: false,
    itemId: 'arbol-eventobs',
    initComponent: function() {
        this.tbar = [{xtype: 'button',
                disabled: true,
                itemId: 'btnSelEventObs',
                icon: perfil.dirImg + 'adicionar.png',
                iconCls: 'btn',
                text: perfil.etiquetas.lbBtnSelEvent
            }];

        this.callParent();
    }

});
