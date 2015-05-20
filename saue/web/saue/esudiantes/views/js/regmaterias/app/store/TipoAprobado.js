Ext.define('RegMaterias.store.TipoAprobado', {
    extend: 'Ext.data.Store',
    model: 'RegMaterias.model.TipoAprobado',
    autoLoad: true,
    storeId: 'idStoreTipoAprobado',
    listeners: {
        load: function () {
            if (this.getCount() > 0) {
                Ext.getCmp('tipo').select(this.first());
            }
        }
    },
    data: [
        {"descripcion": "Aprobado examen de suficiencia", "idtipoaprobado": 1000031},
        {"descripcion": "Reprobado examen de suficiencia", "idtipoaprobado": 1000030},
        {"descripcion": "Aprobado examen de ubicación", "idtipoaprobado": 1000033}
    ]
});