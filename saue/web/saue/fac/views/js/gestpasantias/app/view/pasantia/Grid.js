Ext.define('GestPasantias.view.pasantia.Grid', {
    extend: 'Ext.grid.Panel',
    alias: 'widget.pasantia_grid',

    store: 'GestPasantias.store.Pasantias',

    initComponent: function () {
        var me = this;

        me.selModel = Ext.create('Ext.selection.CheckboxModel');

        me.bbar = Ext.create('Ext.toolbar.Paging', {
            displayInfo: true,
            store: me.store
        });

        me.viewConfig = {
            getRowClass: function (record) {
                if (record.get('estado') === false)
                    return 'FilaRoja';
            }
        };

        me.tbar = Ext.widget('pasantia_toolbar');

        me.columns = [
            { dataIndex: 'idpasantia', hidden: true, hideable: false },
            { dataIndex: 'idfacultad', hidden: true, hideable: false },
            { dataIndex: 'idcarrera', hidden: true, hideable: false },
            { dataIndex: 'idenfasis', hidden: true, hideable: false },
            { dataIndex: 'idtipopasantia', hidden: true, hideable: false },

            //{ text: perfil.etiquetas.lbHdrEmpresa, dataIndex: 'empresa', flex: 1},
            { text: perfil.etiquetas.lbHdrTipoPasantia, dataIndex: 'tipo_pasantia_descripcion', flex: 1},
            { text: perfil.etiquetas.lbHdrHoras, dataIndex: 'horas' },
            { text: perfil.etiquetas.lbHdrEstado, dataIndex: 'estado', hidden: true }
        ];

        this.callParent(arguments);
    }
});