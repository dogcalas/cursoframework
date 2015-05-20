Ext.define('GestCoRequisitos.view.corequisito.CoRequisitoList', {
    extend: 'Ext.grid.Panel',
    alias: 'widget.corequisitolist',

    store: 'GestCoRequisitos.store.CoRequisitos',

    selModel: Ext.create('Ext.selection.CheckboxModel', {
        id: 'idSelectionCorequisitoGrid'
    }),

    initComponent: function () {
        var me = this;

        me.title = perfil.etiquetas.lbTtlCoLista + "'X'";

        me.bbar = Ext.create('GestCoRequisitos.view.corequisito.PagingToolBar', {
            store: me.store
        });

        me.tbar = Ext.create('GestCoRequisitos.view.corequisito.CoRequisitoListToolBar');

        me.viewPrenfig = {
            getRowClass: function (record) {
                if (record.get('estado') === false)
                    return 'FilaRoja';
            }
        };

        me.columns = [
            {
                dataIndex: 'idco_requisito',
                hidden: true,
                hideable: false
            },
            {
                dataIndex: 'idmateria',
                hidden: true,
                hideable: false
            },
            {
                dataIndex: 'idmateriaco',
                hidden: true,
                hideable: false
            },
            {
                text: perfil.etiquetas.lbHdrCodMateria,
                dataIndex: 'codmateria'
            },
            {
                text: perfil.etiquetas.lbHdrDescripcion,
                dataIndex: 'descripcion',
                flex: 1
            }
        ];

        me.callParent(arguments);
    }
});