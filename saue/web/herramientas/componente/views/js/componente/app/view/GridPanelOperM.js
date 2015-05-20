Ext.define('GestComponentes.view.GridPanelOperM', {
    extend: 'Ext.grid.Panel',
    alias: 'widget.gridpaneloperm',
    title: perfil.etiquetas.lbTitOperaciones,
    frame: true,
    
    iconCls: 'icon-grid',
    autoExpandColumn: 'expandir',
    store: 'GridOperMStore',
    disabled: false,
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
        {hidden: true, header: perfil.etiquetas.lbTitParametros, hideable: true, dataIndex: 'parametros'},
        {hidden: true, header: perfil.etiquetas.lbTitCantParm, dataIndex: 'cantparm', }
    ],
    loadMask: {store: 'GridOperMStore'},
    itemId: 'grid-oper',
    initComponent: function() {

        this.tbar = [{xtype: 'button',
                disabled: false,
                itemId: 'btnAddOperm',
                icon: perfil.dirImg + 'adicionar.png',
                iconCls: 'btn',
                text: perfil.etiquetas.lbBtnAdicionar

            }
            , {xtype: 'button', disabled: true,
                itemId: 'btnModOperm',
                icon: perfil.dirImg + 'modificar.png',
                iconCls: 'btn',
                text: perfil.etiquetas.lbBtnModificar}, {xtype: 'button', disabled: true,
                itemId: 'btnDellOperm',
                icon: perfil.dirImg + 'eliminar.png',
                iconCls: 'btn',
                text: perfil.etiquetas.lbBtnEliminar}];

        this.callParent();
    }




});
