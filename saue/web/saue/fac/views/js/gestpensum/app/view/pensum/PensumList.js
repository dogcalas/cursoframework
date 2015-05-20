Ext.define('GestPensums.view.pensum.PensumList', {
    extend: 'Ext.grid.Panel',
    alias: 'widget.pensumlist',

    store: 'GestPensums.store.Pensums',

    initComponent: function () {
        var me = this;

        me.selModel = Ext.create('Ext.selection.CheckboxModel', {
            id: 'idSelectionPensumGrid'
        });

        me.bbar = Ext.create('GestPensums.view.pensum.PensumListPagingToolBar');

        me.viewConfig = {
            getRowClass: function (record) {
                if (record.get('estado') === false)
                    return 'FilaRoja';
            }
        };

        me.tbar = Ext.widget('pensumlisttbar')

        me.columns = [
            { dataIndex: 'idpensum', hidden: true, hideable: false},
            { dataIndex: 'idcarrera', hidden: true, hideable: false},
            {dataIndex: 'idfacultad', hidden: true, hideable: false},
            { dataIndex: 'estado', hidden: true, hideable: false},
            { header: perfil.etiquetas.lbHdrDescripcion, dataIndex: 'descripcion_pensum', flex: 1},
            { header: perfil.etiquetas.lbHdrDescripcionCarrera, dataIndex: 'descripcion_carrera', flex: 1}
        ];

        me.callParent(arguments);
    }
});