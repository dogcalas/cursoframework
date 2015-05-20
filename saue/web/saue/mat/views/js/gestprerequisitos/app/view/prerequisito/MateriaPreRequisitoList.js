Ext.define('GestPreRequisitos.view.prerequisito.MateriaPreRequisitoList', {
    extend: 'Ext.grid.Panel',
    alias: 'widget.materiaprerequisitolist',

    //title: perfil.etiquetas.lbTtlCoLista,

    store: 'GestPreRequisitos.store.MateriasParaPreRequisitos',

    initComponent: function () {
        var me = this;

        me.selModel = Ext.create('Ext.selection.CheckboxModel');

        me.columns = [
            { hidden: true, dataIndex: 'idmateriapre', hideable: false },
            { header: perfil.etiquetas.lbHdrCodMateria, dataIndex: 'codmateria'},
            { header: perfil.etiquetas.lbHdrDescripcion, dataIndex: 'descripcion', flex: 1}
        ];

        me.tbar = Ext.create('GestPreRequisitos.view.prerequisito.ToolBar');

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