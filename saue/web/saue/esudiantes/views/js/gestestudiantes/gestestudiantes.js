var perfil = window.parent.UCID.portal.perfil;
UCID.portal.cargarEtiquetas('gestestudiantes', function () {
    cargarInterfaz();
});
////------------ Inicializo el singlenton QuickTips ------------////
Ext.QuickTips.init();

function cargarInterfaz() {
    new Ext.Viewport({
        layout: 'fit',
        items: [{xtype:'alumno_grid'}]
    });
}