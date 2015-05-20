var perfil = window.parent.UCID.portal.perfil;
UCID.portal.cargarEtiquetas('estreportes', function () {
    cargarInterfaz();
});
////------------ Inicializo el singlenton QuickTips ------------////
Ext.QuickTips.init();

////------------ Inicializo el singlenton QuickTips ------------////
Ext.QuickTips.init();

function cargarInterfaz() {
    Ext.create('estreportes');
}