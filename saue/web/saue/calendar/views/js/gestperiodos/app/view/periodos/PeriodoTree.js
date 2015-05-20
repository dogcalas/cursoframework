Ext.define('GestPeriodos.view.periodos.PeriodoTree', {
    extend: 'Ext.tree.Panel',
    alias: 'widget.periodotree',
    id: 'periodotree',
    title: 'Sistemas/Funcionalidades',
    width: 200,
    height: 465,
    store: 'GestPeriodos.store.PeriodoTree',
    rootVisible: true,
    autoScroll: true,
    region: 'west',
    useArrows: true,
    frame: true,
    renderTo: Ext.getBody()
});
