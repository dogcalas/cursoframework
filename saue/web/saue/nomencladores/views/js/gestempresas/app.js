var perfil = window.parent.UCID.portal.perfil;
perfil.etiquetas = Object();

Ext.application({
    paths: {
        'GestEmpresas': '../../views/js/gestempresas/app'
    },
    name: 'GestEmpresas',
    controllers: [
        'Empresa'
    ],    
    models: [
        'empresa'
    ],
    stores: [
        'stEmpresas'
    ],
    views: [
        'EmpresaList',
        'winAdicionar',
    ],
    enableQuickTips: true,
    launch: function() {
        UCID.portal.cargarEtiquetas('gestempresas', function () {
            Ext.create('GestEmpresas.view.EmpresaList');
        });
    }

});
