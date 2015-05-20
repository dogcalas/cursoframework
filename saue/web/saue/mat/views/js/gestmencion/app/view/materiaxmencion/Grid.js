Ext.define('GestMateriaxMencion.view.materiaxmencion.Grid', {
    extend: 'Ext.grid.Panel',
    alias: 'widget.materiaxmencion_grid',

    store: 'GestMateriaxMencion.store.MateriasxMencion',

    selModel: Ext.create('Ext.selection.CheckboxModel'),

    initComponent: function () {
        var me = this;

        me.title = perfil.etiquetas.lbTtlMateriasMencion;

        me.bbar = Ext.create('Ext.toolbar.Paging', {
            displayInfo: true,
            store: 'GestMateriaxMencion.store.MateriasxMencion'
        });

        me.tbar = Ext.widget('materiaxmencion_tbar');

        me.columns = [
            { dataIndex: 'idmencion', hidden: true, hideable: false },
            { dataIndex: 'idmateria', hidden: true, hideable: false },

            { text: 'Código materia', dataIndex: 'codmateria'},//eqtiquetas
            { text: 'Descripción', dataIndex: 'descripcion', flex: 1},//etiquetas
            { text: 'Activado', dataIndex: 'estado', hidden: true}//etiquetas
        ];

        me.callParent(arguments);
    }
});