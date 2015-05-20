var perfil = window.parent.UCID.portal.perfil;
perfil.etiquetas = Object();

Ext.application({
    name: 'GestTipoPeriodo',

    enableQuickTips: true,

    paths: {
        'GestTipoPeriodo': '../../views/js/gesttipoperiodo/app'
    },
    
    views: [
        'GestTipoPeriodo.view.tipoperiodo.TipoPeriodoList',
        'GestTipoPeriodo.view.tipoperiodo.TipoPeriodoEdit',
        'GestTipoPeriodo.view.tipoperiodo.TipoPeriodoListToolBar'
    ],
    stores: [
        'GestTipoPeriodo.store.TipoPeriodo'
    ],
    models: [
        'GestTipoPeriodo.model.TipoPeriodo'
    ],

   controllers: ['TipoPeriodo'],

    launch: function () {
        UCID.portal.cargarEtiquetas('gesttipoperiodo', function () {
            Ext.create('Ext.container.Viewport', {
                layout: 'fit',
                items: [
                    {
                        xtype: 'tipoperiodolist',
                        tbar: Ext.widget('tipoperiodolisttbar')
                    }
                ]
            })
        });

    }
})
