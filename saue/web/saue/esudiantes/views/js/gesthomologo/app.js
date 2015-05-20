Ext.Loader.setConfig({
    enabled: true,
    paths: {
        'GestHom.controller': '../../views/js/gesthomologo/app/controller',
        'GestHom.view': '../../views/js/gesthomologo/app/view',
        'GestHom.store': '../../views/js/gesthomologo/app/store',
        'GestHom.model': '../../views/js/gesthomologo/app/model',
        'GestNotas': '../../views/js/gestnotas/app',
        'GestConv': '../../views/js/gestconvalidaciones/app',
        'GestMatxPensum': '../../../mat/views/js/gestmatxpensum/app'
    }
});

Ext.require(
    [
        'Ext.container.Viewport',
        'GestHom.*'
    ]
);

var perfil = window.parent.UCID.portal.perfil;
perfil.etiquetas = Object();

Ext.application({
    name: 'GestHom',

    enableQuickTips: true,
    appFolder: 'app',
    controllers: ['Homologaciones'],

    launch: function () {
		UCID.portal.cargarAcciones(window.parent.idFuncionalidad);
		UCID.portal.cargarEtiquetas('gesthomologo', function(){
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
                        Ext.widget('studentinfo'), Ext.widget('searchhomoptions'),
                        {
                            xtype: 'materialist',
                            anchor: '100% -175'
                        }
                    ]
                },{
                    flex: 1,
                    layout: 'anchor',
                    border: 0,
                    padding: '5 0 5 15',
                    items:[ Ext.widget('enfasisfilter'),
                    {
                            xtype: 'materialibrelist',
                            anchor: '100%, -210'
                        }
                    ]
                }
            ]
        })
});
    }
});
