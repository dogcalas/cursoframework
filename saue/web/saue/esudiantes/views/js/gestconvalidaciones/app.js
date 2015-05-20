Ext.Loader.setConfig({
    enabled: true,
    paths: {
        'GestConv.controller': '../../views/js/gestconvalidaciones/app/controller',
        'GestConv.view': '../../views/js/gestconvalidaciones/app/view',
        'GestConv.store': '../../views/js/gestconvalidaciones/app/store',
        'GestConv.model': '../../views/js/gestconvalidaciones/app/model',
        'GestNotas': '../../views/js/gestnotas/app',
        'GestMatxPensum': '../../../mat/views/js/gestmatxpensum/app'
    }
});

Ext.require(
    [
        'Ext.container.Viewport',
        'GestConv.*'
    ]
);

var perfil = window.parent.UCID.portal.perfil;
perfil.etiquetas = Object();

Ext.application({
    name: 'GestConv',

    enableQuickTips: true,
    appFolder: 'app',
    controllers: ['Convalidaciones'],

    launch: function () {		
		UCID.portal.cargarAcciones(window.parent.idFuncionalidad);
		UCID.portal.cargarEtiquetas('gestconvalidaciones', function(){
        Ext.create('Ext.container.Viewport', {
                layout: {
                        type: 'hbox',
                        align: 'stretch',
                        defaultType: 'container'

                    },
            items: [
                {
                    flex: 1.5,
                    layout: 'anchor',
                    padding: '5 0 5 5',
                    border: 0,
                    items:[
                        Ext.widget('studentinfo'), Ext.widget('searchoptions'),  
                        {
                            xtype: 'materialist',
                            anchor: '100% -180'
                        }
                    ]
                },{
                    flex: 1,
                    layout: 'anchor',
                    border: 0,
                    padding: '5 0 5 15',
                    items:[ Ext.widget('enfasisfilter'),
                    {
                            xtype: 'convlist',
                            anchor: '100%, -265'
                        }
                    ]
                }
            ]
        })
});
    }
});
