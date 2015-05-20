Ext.define('GestComponentes.view.GridPanelComp', {
    extend: 'Ext.grid.Panel',
    alias: 'widget.gridpanelcomp',
    title: perfil.etiquetas.lbTitPropiedades,
    frame: true,
    disabled: false,
    iconCls: 'icon-grid',
    autoExpandColumn: 'expandir',
    store: 'GridComponentesStore',
    plugins: [{
            ptype: 'rowexpander',
            rowBodyTpl: new Ext.XTemplate(
                    '<div>',
                    '<p><b>Servicios:</b> {servicios}</p><br>',
                    '<p><b>Dependencias:</b> {dependencias}</p><br>',
                    '<p><b>Eventos generados:</b> {generados}</p><br>',
                    '<p><b>Eventos observados:</b> {observados}</p><br>',
                    '</div>'
                    )
        }],
    columns: [
        {hidden: true, hideable: false, dataIndex: 'id'},
        {header: perfil.etiquetas.lbTitNombre, width: '30%', dataIndex: 'nombre'},
        {header: perfil.etiquetas.lbTitEstado, width: '20%', dataIndex: 'estado'},
        {header: perfil.etiquetas.lbTitVersion, width: '10%', dataIndex: 'version'},
        {header: perfil.etiquetas.lbTitUbicacion, width: '37%', dataIndex: 'direccion'}

    ],
    loadMask: {store: 'GridComponentesStore'},
    initComponent: function() {

        this.tbar = [
            {xtype: 'button', disabled: false,
                itemId: 'btnServicios',
                icon: perfil.dirImg + 'servicios.png',
                iconCls: 'btn',
                text: perfil.etiquetas.lbBtnServs, },
            {xtype: 'button', disabled: false,
                itemId: 'btnDependencias',
                icon: perfil.dirImg + 'dependencias.png',
                iconCls: 'btn',
                text: perfil.etiquetas.lbBtnDeps}, {
                xtype: 'button',
                disabled: false,
                itemId: 'btnEventosGen',
                icon: perfil.dirImg + 'entidad.png',
                iconCls: 'btn',
                text: perfil.etiquetas.lbBtnEventG
            },
            {xtype: 'button',
                disabled: false,
                itemId: 'btnEventosObs',
                icon: perfil.dirImg + 'ver.png',
                iconCls: 'btn',
                text: perfil.etiquetas.lbBtnEventO}];

        this.callParent();
    }


});
