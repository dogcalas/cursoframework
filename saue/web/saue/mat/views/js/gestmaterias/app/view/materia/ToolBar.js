Ext.define('GestMaterias.view.materia.ToolBar', {
    extend: 'Ext.toolbar.Toolbar',
    alias: 'widget.materialisttbar',

    initComponent: function () {
        UCID.portal.cargarAcciones(window.parent.idFuncionalidad);

        this.items = [
            {
                text: perfil.etiquetas.lbBtnAdicionar,
                icon: perfil.dirImg + 'adicionar.png',
                iconCls: 'btn',
                hidden: true,
                action: 'adicionar',
                id:'btnAddMateria'
            },
            {
                text: perfil.etiquetas.lbBtnModificar,
                icon: perfil.dirImg + 'modificar.png',
                iconCls: 'btn',
                action: 'modificar',
                disabled: true,
                hidden: true,
                id:'btnUpdMateria'
            },
            {
                text: perfil.etiquetas.lbBtnEliminar,
                icon: perfil.dirImg + 'eliminar.png',
                iconCls: 'btn',
                action: 'eliminar',
                disabled: true,
                hidden: true,
                id:'btnDelMateria'
            },
            '-',
            {
                text: perfil.etiquetas.lbBtnCreditos,
                icon: perfil.dirImg + 'registrarconteofinal.png',
                iconCls: 'btn',
                action: 'credito',
                disabled: true,
                hidden: true,
                id:"btnAddMCredito"
            },
            '-',
            {
                xtype: 'form',
                border: 0,
                layout: 'fit',
                items: {
                    xtype: 'filefield',
                    buttonOnly: true,
                    disabled: true,
                    hideLabel: true,
                    name: 'pa',
                    buttonText: 'Subir PA',
                    buttonConfig: {
                        icon: perfil.dirImg + 'subir.png',
                        iconCls: 'btn'
                    }
                }
            },
            {
                text: 'Visualizar PA',//perfil.etiquetas.lbBtnModificar,
                icon: perfil.dirImg + 'ver.png',
                iconCls: 'btn',
                action: 'visualizarPA',
                disabled: true
            },
            '-',
            {
                xtype: 'searchcombofield',
                store: Ext.data.StoreManager.lookup('idStoreTipoMaterias'),
                name: 'idtipomateria',
                valueField: 'idtipomateria',
                displayField: 'descripcion',
                storeToFilter: Ext.data.StoreManager.lookup('idStoreMaterias'),
                //filterPropertysNames: ['idtipomateria'],
                emptyText: 'Filtrar por tipo de materia',
                width: 250,
                padding: '0 0 0 5',
                labelWidth: 80
            },
            {
                xtype: 'searchfield',
                store: Ext.data.StoreManager.lookup('idStoreMaterias'),
                emptyText: 'Filtrar por código o descripción',
                width: 250,
                padding: '0 0 0 5',
                labelWidth: 40,
                filterPropertysNames: ['codmateria', 'descripcion']
            }
        ];

        this.callParent(arguments);
    }
});
