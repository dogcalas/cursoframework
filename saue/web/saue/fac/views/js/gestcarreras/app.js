var perfil = window.parent.UCID.portal.perfil;
perfil.etiquetas = Object();

Ext.application({
    name: 'GestCarreras',

    enableQuickTips: true,

    paths: {
        'GestCarreras': '../../views/js/gestcarreras/app'
    },

    controllers: ['CarrerasC'],

    launch: function () {
        UCID.portal.cargarEtiquetas('gestcarreras', function () {
            Ext.create('Ext.container.Viewport', {
                layout: 'fit',
                items: [
                    {
                        xtype: 'carreralist'
            }
            ]
        });
    });

}
})
;
