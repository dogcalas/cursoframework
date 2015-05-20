var perfil = window.parent.UCID.portal.perfil;
perfil.etiquetas = Object();

Interface = {
    init: function() {
        Ext.Loader.setPath({
            'GestComponentes.controller': '../../views/js/componente/app/controller',
            'GestComponentes.view': '../../views/js/componente/app/view',
            'GestComponentes.store': '../../views/js/componente/app/store',
            'GestComponentes.model': '../../views/js/componente/app/model'

        });

        Ext.application({
            name: 'GestComponentes',
            autoCreateViewport: true,
            models: ['ArbolComponentesModel', 'GridComponentesModel', 'GridParmModel', 'GridServiciosModel', 'GridDependenciasModel', 'GridEventGenModel', 'GridEventObsModel', 'GridParmmModel', 'GridOperMModel', 'GridOperModel', 'ArbolDependenciasModel', 'ArbolEventosGenModel', 'ArbolEventosObsModel'],
            stores: ['ArbolComponentesStore', 'GridComponentesStore', 'ComboTDServStore', 'GridParmStore', 'GridServiciosStore', 'GridDependenciasStore', 'GridEventGenStore', 'GridEventObsStore', 'GridParmmStore', 'GridOperMStore', 'GridOperStore', 'ArbolDependenciasStore', 'ArbolEventosGenStore', 'ArbolEventosObsStore'],
            controllers: ['Componentes']
        });

    }

}

UCID.portal.cargarEtiquetas('componente', Interface.init);
