Ext.define('GestComponentes.view.Viewport', {
    extend: 'Ext.container.Viewport',
    layout: 'fit',
    idmio: 'view',
    requires: [
        'GestComponentes.view.PanelPrincipal',
        'GestComponentes.view.PanelArbolComp',
        'GestComponentes.view.GridPanelComp',
        'GestComponentes.view.GridPanelServicios',
        'GestComponentes.view.GridPanelEventGenerados',
        'GestComponentes.view.GridPanelEventObservados',
        'GestComponentes.view.GridPanelDependencias',
        'GestComponentes.view.TabPanelGeneral',
        'GestComponentes.view.GridPanelOper',
        'GestComponentes.view.FormAddServ',
        'GestComponentes.view.FormInsComp',
        'GestComponentes.view.FormModComp',
        'GestComponentes.view.WinInsComp',
        'GestComponentes.view.WinInsDep',
        'GestComponentes.view.WinInsEventG',
        'GestComponentes.view.WinInsEventO',
        'GestComponentes.view.WinInsServ',
        'GestComponentes.view.WinModComp',
        'GestComponentes.view.WinModDep',
        'GestComponentes.view.WinModEventG',
        'GestComponentes.view.WinModEventO',
        'GestComponentes.view.WinModServ',
        'GestComponentes.view.FormAddOper',
        'GestComponentes.view.GridPanelParm',
        'GestComponentes.view.GridPanelOperM',
        'GestComponentes.view.FormModOper',
        'GestComponentes.view.FormModServ',
        'GestComponentes.view.GridPanelParmm',
        'GestComponentes.view.PanelArbolDep',
        'GestComponentes.view.FormAddDep',
        'GestComponentes.view.PanelArbolEventGen',
        'GestComponentes.view.FormAddEventG',
        'GestComponentes.view.PanelArbolEventObs',
        'GestComponentes.view.FormAddEventO'

   ],
    initComponent: function() {
        this.items = {
            xtype: 'panelprincipal'

        };

        this.callParent();
    }
});
