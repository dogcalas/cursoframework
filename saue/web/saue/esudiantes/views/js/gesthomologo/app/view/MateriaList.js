Ext.define('GestHom.view.MateriaList', {
    extend: 'Ext.grid.Panel',

    alias: 'widget.materialist',
    id:'materialist',
    title: 'Materias del pensum actual',
    store: 'GestHom.store.Materias',
    columns: [
        // { dataIndex: 'idalumno', hidden: true, hideable: false},
        // { dataIndex: 'idpd', hidden: true},
        // { dataIndex: 'idmateriahomo', hidden: true},
        // { dataIndex: 'iduniversidad', hidden: true},
        // { dataIndex: 'idmateria', hidden: true},
        { header: 'Código', dataIndex: 'codigo', width: 45},
        { header: 'Materia', dataIndex: 'descripcion', flex: 1},
        { header: 'Cod. homologada' , dataIndex:'codmateriahomo'},
        { header: 'Materia homologada', dataIndex: 'materiahomo', flex: 1},
        { header: 'Nota', dataIndex: 'nota', width: 40}
    ],
    columnLines : true, 
    
    selType: 'checkboxmodel',
    viewConfig: {
        getRowClass: function (record, rowIndex, rowParams, store) {
            if (record.data.idtipoaprobado == 1000023)
                return 'FilaVerde';
        }
    },
    initComponent: function () {
        var me = this;

        me.bbar = [Ext.create('Ext.toolbar.Paging', {
            displayInfo: true,
            disabled:true,
            id:"paginator",
            store: me.store
        }),
        "->",
        {
            xtype:'button',
            id: 'btnHomologar',
            text:'Homologar',
            action:'homologar',
            icon: perfil.dirImg + 'avanzada.png',
            iconCls: 'btn'
        }
        ];

        me.callParent(arguments);
    }
});
