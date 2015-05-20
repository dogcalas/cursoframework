Ext.define('GestComponentes.view.PanelArbolComp', {
    extend: 'Ext.tree.Panel',
    alias: 'widget.panelarbolcomp',
    title: perfil.etiquetas.lbTitpanelArbolComp,
    collapsible: true,
    region: 'west',
    width: 330,
    height: 150,
    store: 'ArbolComponentesStore',
   rootVisible: true,
    itemId: 'arbol-comp',
    initComponent: function() {
        this.tbar = [{xtype: 'button',
                disabled: true,
                itemId: 'btnAddComp',
                icon: perfil.dirImg + 'adicionar.png',
                iconCls: 'btn',
                text: perfil.etiquetas.lbBtnRegistrar
            }
            , {xtype: 'button', disabled: false,
                itemId: 'btnModComp',
                disabled:true,
                        icon: perfil.dirImg + 'modificar.png',
                iconCls: 'btn',
                text: perfil.etiquetas.lbBtnModificar}, {xtype: 'button', disabled: false,
                itemId: 'btnDellComp',
                disabled:true,
                        icon: perfil.dirImg + 'deshabilitar.png',
                iconCls: 'btn',
                text: perfil.etiquetas.lbBtnDeshabilitar}, {xtype: 'button', disabled: false,
                itemId: 'btnHabComp',
                disabled:true,
                        icon: perfil.dirImg + 'habilitar.png',
                iconCls: 'btn',
                text: perfil.etiquetas.lbBtnHabilitar}];
        
        this.callParent();
    }




});
