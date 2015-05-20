Ext.define('GestMaterias.view.credito.Edit', {
    extend: 'Ext.window.Window',
    alias: 'widget.creditos_edit',

    layout: 'anchor',
    modal: true,
    resizable: false,
    autoShow: true,
    width: 500,
    height: 300,

    initComponent: function () {
        var me = this;

        me.items = [
            {
                xtype: 'searchcombofield',
                store: 'GestMaterias.store.Carreras',
                name: 'idcarrera',
                valueField: 'idcarrera',
                displayField: 'descripcion',
                labelAlign: 'top',
                storeToFilter: 'GestMaterias.store.Creditos',
                //filterPropertysNames: ['idareageneral'],
                emptyText: 'Filtrar por carrera',
                width: 250,
                labelWidth: 45,
                padding: '5 5 0 5'
            },
            {
                xtype: 'creditos_grid',
                anchor: '100% 85%',
                padding: '5'
            }
        ];

        me.buttons = [
            {
                icon: perfil.dirImg + 'cancelar.png',
                iconCls: 'btn',
                text: perfil.etiquetas.lbBtnCancelar,
                action: 'cancelar',
                scope: this,
                handler: this.close
            },
            {
                id: 'idBtnAplicar',
                icon: perfil.dirImg + 'aplicar.png',
                iconCls: 'btn',
                text: perfil.etiquetas.lbBtnAplicar,
                action: 'aplicar'
            },
            {
                id: 'idBtnAceptar',
                icon: perfil.dirImg + 'aceptar.png',
                iconCls: 'btn',
                text: perfil.etiquetas.lbBtnAceptar,
                action: 'aceptar'
            }
        ];

        me.callParent(arguments);
    }
})
