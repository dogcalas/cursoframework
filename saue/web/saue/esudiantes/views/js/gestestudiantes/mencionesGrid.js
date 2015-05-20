Ext.define('mencionesGrid', {
    extend: 'Ext.grid.GridPanel',
    alias: 'widget.alumno_menciones',
    id: 'alumno_menciones',
    store: Ext.create('Ext.data.ArrayStore', {
        model: Ext.define('MencionesModel', {
            extend: 'Ext.data.Model',
            fields: ['idmencion','idfacultad', 'idusuario', 'fecha', 'descripcion', 'estado', 'denominacion', 'checked']
        }),
        //autoLoad: true,
        storeId: 'idStoreMenciones',
        pageSize: 15,
        remoteFilter: true,
        proxy: {
            type: 'ajax',
            api: {
                read: 'cargarMenciones'
            },
            actionMethods: { //Esta Linea es necesaria para el metodo de llamada POST o GET
                read: 'POST'
            },
            reader: {
                root: 'datos',
                totalProperty: 'cantidad',
                successProperty: 'success',
                messageProperty: 'mensaje'
            }
        }
    }),

    selModel: Ext.create('Ext.selection.RowModel', {
        id: 'idSelectionMencionesGrid',
        mode: 'MULTI'
    }),

    columns: [

        {
            text: 'idmencion',
            flex: 1,
            dataIndex: 'idmencion',
            hidden: true
        },
        {
            text: 'idfacultad',
            flex: 1,
            dataIndex: 'idfacultad',
            hidden: true
        },
        {
            text: '',
            dataIndex: 'checked',
            xtype: 'checkcolumn',
            width: 25
        },
        {
            text: 'Descripción',
            flex: 1,
            dataIndex: 'descripcion'
        },
        {
            text: 'Facultad',
            flex: 1,
            dataIndex: 'denominacion'
        }
    ],

    initComponent: function () {
        var me = this;
        me.bbar = Ext.create('Ext.toolbar.Paging', {
            displayInfo: true,
            store: me.store
        });

        me.callParent(arguments);
    }
});
