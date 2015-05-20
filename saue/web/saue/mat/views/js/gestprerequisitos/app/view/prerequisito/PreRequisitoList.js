Ext.define('GestPreRequisitos.view.prerequisito.PreRequisitoList', {
    extend: 'Ext.grid.Panel',
    alias: 'widget.prerequisitolist',

    store: 'GestPreRequisitos.store.PreRequisitos',

    selModel: Ext.create('Ext.selection.CheckboxModel', {
        id: 'idSelectionPrerequisitoGrid'
    }),

    initComponent: function () {
        var me = this;

        me.title = perfil.etiquetas.lbTtlPreLista + "'X'";

        me.bbar = Ext.create('GestPreRequisitos.view.prerequisito.PagingToolBar', {
            store: me.store
        });

        me.tbar = Ext.create('GestPreRequisitos.view.prerequisito.PreRequisitoListToolBar');

        me.viewPrenfig = {
            getRowClass: function (record) {
                if (record.get('estado') === false)
                    return 'FilaRoja';
            }
        };

        me.columns = [
            {
                dataIndex: 'idpre_requisito',
                hidden: true,
                hideable: false
            },
            {
                dataIndex: 'idmateria',
                hidden: true,
                hideable: false
            },
            {
                dataIndex: 'idmateriapre',
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