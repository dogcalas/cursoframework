Ext.define('GestComponentes.view.GridPanelParmm', {
    extend: 'Ext.grid.Panel',
    alias: 'widget.gridpanelparmm',
    animCollapse: false,
    store: 'GridParmmStore',
    disabled: false,
    iconCls: 'icon-grid',
    enableLocking: true,
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
