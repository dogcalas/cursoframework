Ext.define('GestEnfasis.view.enfasi.EnfasiList', {
    extend: 'Ext.grid.Panel',
    alias: 'widget.enfasilist',

    store: Ext.create('GestEnfasis.store.Enfasis'),

    initComponent: function () {
        var me = this;

        me.selModel = Ext.create('Ext.selection.CheckboxModel', {
            id: 'idSelectionEnfasiGrid'
        });

        me.bbar = Ext.create('GestEnfasis.view.enfasi.EnfasiListPagingToolBar');

        me.viewConfig = {
            getRowClass: function (record) {
                if (record.get('estado') === false)
                    return 'FilaRoja';
            }
        };

        me.tbar = Ext.widget('enfasilisttbar');

        me.columns = [
            { dataIndex: 'idenfasis', hidden: true, hideable: false },
            { dataIndex: 'idcarrera', hidden: true, hideable: false },
            {dataIndex: 'idfacultad', hidden: true, hideable: false},
            { dataIndex: 'estado', hidden: true },
            { header: perfil.etiquetas.lbHdrDescripcion, dataIndex: 'descripcion_enfasi', flex: 2},
            { header: perfil.etiquetas.lbHdrDescripcionCarrera, dataIndex: 'descripcion_carrera', flex: 1}
        ];

        this.callParent(arguments);
    }
});