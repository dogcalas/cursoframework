Ext.define('GestComponentes.view.GridPanelParm', {
    extend: 'Ext.grid.Panel',
    alias: 'widget.gridpanelparm',
    animCollapse: false,
    store: 'GridParmStore',
    disabled: true,
    iconCls: 'icon-grid',
    autoExpandColumn: 'expandir',
    width: 240,
    height: 195,
    selModel:{mode:'MULTI'},
    plugins: [{
            ptype: 'rowexpander',
            rowBodyTpl: new Ext.XTemplate(
                    '<div>',
                    '<p><b>Descripci&oacute;n:</b> {descripcion}</p><br>',
                    '</div>')
        }],
    title: perfil.etiquetas.lbTitParametros,
    columns: [
        {text: perfil.etiquetas.lbTitNombre, dataIndex: 'nombre'},
        {text: perfil.etiquetas.lbTitTipo, dataIndex: 'tipo', flex: 1}

    ],
    initComponent: function() {


        this.callParent();
    }


});
