Ext.define('GestComponentes.view.PanelArbolDep', {
    extend: 'Ext.tree.Panel',
    alias: 'widget.panelarboldep',
    title: perfil.etiquetas.lbTitpanelArbolComp,
    region: 'west',
    width: 270,
    height: 150,
    store: 'ArbolDependenciasStore',
    rootVisible: false,
    itemId: 'arbol-dep',
    initComponent: function() {
        this.tbar = [{xtype: 'button',
                disabled: true,
                itemId: 'btnSelDep',
                icon: perfil.dirImg + 'dependencia.png',
                iconCls: 'btn',
                text: perfil.etiquetas.lbBtnSelDep

            }, {xtype: 'button',
                disabled: true,
                itemId: 'btnSelUso',
                icon: perfil.dirImg + 'seleccion.png',
                iconCls: 'btn',
                text: perfil.etiquetas.lbBtnAddUso

            }];
        
        this.callParent();
    }




});
