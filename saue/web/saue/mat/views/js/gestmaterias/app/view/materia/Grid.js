Ext.define('GestMaterias.view.materia.Grid', {
    extend: 'Ext.grid.Panel',

    alias: 'widget.materialist',

    store: Ext.create('GestMaterias.store.Materias'),

    selModel: Ext.create('Ext.selection.CheckboxModel', {
        id: 'idSelectionMateriaGrid'
    }),

    columns: [
        { dataIndex: 'idmateria', hidden: true, hideable: false},
        { dataIndex: 'idarea', hidden: true, hideable: false},
        { dataIndex: 'ididioma', hidden: true, hideable: false},
        { dataIndex: 'ididiomam', hidden: true, hideable: false},
        { dataIndex: 'idtipomateria', hidden: true, hideable: false},
        { dataIndex: 'estado', hidden: true, hideable: false },
        { dataIndex: 'have_idioma', hidden: true, hideable: false },
        { dataIndex: 'obligatoria', hidden: true, hideable: false },
        { dataIndex: 'nivel', hidden: true, hideable: false },
        { dataIndex: 'min_nota_materia', hidden: true, hideable: false },
        { text: 'Código materia', dataIndex: 'codmateria'},
        { text: 'Descripción', dataIndex: 'descripcion', flex: 1},
        { text: 'Traducción', dataIndex: 'traduccion', flex:1},
        { text: 'Créditos', dataIndex: 'creditos', xtype: 'numbercolumn', vtype: 'float', hidden: true }
    ],

    initComponent: function () {
        var me = this;

        me.tbar = Ext.create('GestMaterias.view.materia.ToolBar')

        me.bbar = Ext.create('GestMaterias.view.materia.PagingToolBar');

        me.viewConfig = {
            getRowClass: function(record){
                if (record.get('estado') === false)
                    return 'FilaRoja';
            }
        };

        me.callParent(arguments);
    }
});