Ext.define('GestCoRequisitos.view.corequisito.MateriaCoRequisitoList', {
    extend: 'Ext.grid.Panel',
    alias: 'widget.materiacorequisitolist',

    //title: perfil.etiquetas.lbTtlCoLista,

    store: 'GestCoRequisitos.store.MateriasParaCoRequisitos',

    initComponent: function () {
        var me = this;

        me.selModel = Ext.create('Ext.selection.CheckboxModel');

        me.columns = [
            { hidden: true, dataIndex: 'idmateriaco', hideable: false },
            { header: perfil.etiquetas.lbHdrCodMateria, dataIndex: 'codmateria'},
            { header: perfil.etiquetas.lbHdrDescripcion, dataIndex: 'descripcion', flex: 1}
        ];

        me.tbar = Ext.create('GestCoRequisitos.view.corequisito.ToolBar');

        me.viewConfig = {
            getRowClass: function(record){
                if (record.get('estado') === false)
                    return 'FilaRoja';
            }
        };

        me.bbar =  Ext.create('Ext.toolbar.Paging', {
            displayInfo: true,
            store: me.store
        });

        me.callParent(arguments);
    }
});