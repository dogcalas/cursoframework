Ext.ns('Traza');
Ext.Ajax.method = 'post';

Traza.ConfigurarContainer = Ext.extend(Ext.Viewport, {
    check: function (pValue) {
        result = '';

        if (pValue == '1')
            result = 'Activado';
        else
            result = 'Desactivado';

        return result;
    },
    initComponent: function () {

        this.columns = [
            {header: 'Traza', dataIndex: 'traza'},
            {header: 'Activado', dataIndex: 'activado', renderer: this.check}
        ];

        this.store = new Ext.data.Store({
            url: 'cargar',
            // layout:'absolute',
            reader: new Ext.data.JsonReader({
                root: 'data',
                fields: ['traza', 'activado']
            })
        });

        this.store.layout = 'fit';

        this.store.load();

        this.items = {
            xtype: 'grid',
            autoHeight: true,
            tbar: [
                {
                    text: 'Activar/Desactivar',
                    id: 'btn',
                    disabled: true,
                    handler: function () {
                        Ext.Ajax.request({
                            url: 'cambiar',
                            params: Ext.getCmp('grid').getSelectionModel().getSelected().data,
                            success: function () {
                                Ext.getCmp('grid').getStore().load();
                                Ext.getCmp('btn').disable();
                            }
                        });
                    }
                },
                {
                    text: 'Limpiar las trazas',
                    id: 'btn',
                    disabled: false,
                    handler: function () {
                        Ext.Ajax.request({
                            url: 'limpiar'
                        });
                    }
                }],
            viewConfig: {
                forceFit: true
            },
            id: 'grid',
            store: this.store,
            columns: this.columns,
            listeners: {
                rowclick: function () {
                    Ext.getCmp('btn').enable();
                },
                rowdblclick: function () {
                    Ext.Ajax.request({
                        url: 'cambiar',
                        params: Ext.getCmp('grid').getSelectionModel().getSelected().data,
                        success: function () {
                            Ext.getCmp('grid').getStore().load();
                            Ext.getCmp('btn').disable();
                        }
                    });
                }
            }
        }

        Traza.ConfigurarContainer.superclass.initComponent.call(this);
    }
});

new Traza.ConfigurarContainer();

