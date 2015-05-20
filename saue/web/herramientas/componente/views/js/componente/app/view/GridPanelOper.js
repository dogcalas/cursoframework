Ext.define('GestComponentes.view.GridPanelOper', {
    extend: 'Ext.grid.Panel',
    alias: 'widget.gridpaneloper',
    title: perfil.etiquetas.lbTitOperaciones,
    frame: true,
    iconCls: 'icon-grid',
    autoExpandColumn: 'expandir',
    store: 'GridOperStore',
    disabled: true,
    width: 260,
    height: 345,
    selModel:{mode:'MULTI'},
    plugins: [{
            ptype: 'rowexpander',
            rowBodyTpl: new Ext.XTemplate(
                    '<div>',
                    '<p><b>Descripci&oacute;n:</b> {descripoper}</p><br>',
                    '</div>')
        }],
    region: 'west',
    columns: [
        {header: perfil.etiquetas.lbTitNombre, width: '55%', dataIndex: 'nombre'},
        {header: perfil.etiquetas.lbTitRetorno, width: '45%', dataIndex: 'retorno'},
        {hidden: true, header: perfil.etiquetas.lbTitParametros, dataIndex: 'parametros', },
        {hidden: true, header: perfil.etiquetas.lbTitCantParm, dataIndex: 'cantparm', }

    ],
    loadMask: {store: 'GridOperStore'},
    itemId: 'grid-oper',
    initComponent: function() {

        this.tbar = [{xtype: 'button',
                disabled: false,
                itemId: 'btnAddOper',
                icon: perfil.dirImg + 'adicionar.png',
                iconCls: 'btn',
                text: perfil.etiquetas.lbBtnAdicionar

            }
            , {xtype: 'button', disabled: true,
                itemId: 'btnModOper',
                icon: perfil.dirImg + 'modificar.png',
                iconCls: 'btn',
                text: perfil.etiquetas.lbBtnModificar}, {xtype: 'button', disabled: true,
                itemId: 'btnDellOper',
                icon: perfil.dirImg + 'eliminar.png',
                iconCls: 'btn',
                text: perfil.etiquetas.lbBtnEliminar}];

        this.callParent();
    }




});
