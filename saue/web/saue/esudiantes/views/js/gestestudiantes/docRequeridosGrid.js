Ext.define('docRequeridosGrid', {
    extend: 'Ext.grid.GridPanel',
    alias: 'widget.documento_requerido',
    id: 'documento_requerido',
    store: Ext.create('Ext.data.ArrayStore', {
        model: Ext.define('DocRequeridosModel', {
            extend: 'Ext.data.Model',
            fields: ['iddocumentorequerido', 'idusuario', 'fecha', 'descripcion', 'estado', 'checked']
        }),
        //autoLoad: true,
        storeId: 'idStoreDocReq',
        pageSize: 15,
        remoteFilter: true,
        proxy: {
            type: 'ajax',
            api: {
                read: '../gestdocxestudiante/cargarDocRequeridos'
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
        id: 'idSelectionDocReqGrid',
        mode: 'MULTI'
    }),

    columns: [
        {
            text: 'iddocumentorequerido',
            flex: 1,
            dataIndex: 'iddocumentorequerido',
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