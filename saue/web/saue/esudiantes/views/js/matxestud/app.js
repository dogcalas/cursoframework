Ext.Loader.setConfig({
    enabled: true,
    paths: {
        'MatEst.controller': '../../views/js/matxestud/app/controller',
        'MatEst.view': '../../views/js/matxestud/app/view',
        'MatEst.store': '../../views/js/matxestud/app/store',
        'MatEst.model': '../../views/js/matxestud/app/model',
        'GestNotas.view': '../../views/js/gestnotas/app/view',
        'GestNotas.store': '../../views/js/gestnotas/app/store',
        'GestNotas.model': '../../views/js/gestnotas/app/model',
        'GestConv': '../../views/js/gestconvalidaciones/app',
        'GestNotas.controller': '../../views/js/gestnotas/app/controller'
    }
});

Ext.require(
    [
        'Ext.container.Viewport',
        'MatEst.*'
    ]
);

var perfil = window.parent.UCID.portal.perfil;
perfil.etiquetas = Object();
Ext.application({
    name: 'MatEst',

    enableQuickTips: true,
    appFolder: 'app',
    controllers: ['CMatEst'],

    launch: function () {
        UCID.portal.cargarEtiquetas('matxestud', function () {
            Ext.create('Ext.container.Viewport', {
                layout: {
                    type: 'hbox',
                    align: 'stretch',
                    defaultType: 'container'

                },
                items: [
                    {
                        flex: 1,
                        layout: 'anchor',
                        padding: '5 0 5 5',
                        border: 0,
                        items: [
                            Ext.widget('studentinfo'),
                            {
                                xtype: 'regmatxest',
                                anchor: '100%, 72.8%'
                            }
                        ]
                    }, {
                        flex: 1,
                        layout: 'anchor',
                        border: 0,
                        padding: '5 0 5 15',
                        items: [
                            Ext.widget('tbmateriaxest'),
                            {
                                xtype: 'matxestlis',
                                anchor: '100%, 82%'
                            }
                        ]
                    }
                ]
            })
        });
    }
});