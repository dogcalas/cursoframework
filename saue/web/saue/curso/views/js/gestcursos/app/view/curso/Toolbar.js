Ext.define('GestCursos.view.curso.Toolbar', {
    extend: 'Ext.toolbar.Toolbar',
    alias: 'widget.curso_toolbar',

    initComponent: function () {
        UCID.portal.cargarAcciones(window.parent.idFuncionalidad);
        var me = this;

        me.items = [
            {
                id: 'idBtnAddCurso',
                disabled: true,
                hidden: true,
                text: perfil.etiquetas.lbBtnAdicionar,
                icon: perfil.dirImg + 'adicionar.png',
                iconCls: 'btn',
                action: 'adicionar'
            },
            {
                id: 'idBtnUpdCurso',
                disabled: true,
                hidden: true,
                text: perfil.etiquetas.lbBtnModificar,
                icon: perfil.dirImg + 'modificar.png',
                iconCls: 'btn',
                action: 'modificar'
            },
            {
                id: 'idBtnDelCurso',
                disabled: true,
                hidden: true,
                text: perfil.etiquetas.lbBtnEliminar,
                icon: perfil.dirImg + 'eliminar.png',
                iconCls: 'btn',
                action: 'eliminar'
            },
            {
                id: 'idBtnGetAlumnos',
                disabled: true,
                hidden: true,
                text: perfil.etiquetas.lbBtnAlumnos,
                icon: perfil.dirImg + 'usuario.png',
                iconCls: 'btn',
                action: 'mostrar_alumnos'
            },
            '-',
            {
                xtype: 'combo',
                store: 'GestCursos.store.Annos',
                name: 'anno',
                typeAhead: true,
                forceSelection: true,
                valueField: 'anno',
                displayField: 'anno',
                queryMode: 'local',
                width: 70,
                padding: '0 0 0 5',
                labelWidth: 10
            },
            {
                xtype: 'searchcombofield',
                store: 'GestCursos.store.Periodos',
                emptyText: 'Filtrar por período',//Etiqueta
                name: 'idperiododocentetb',
                id: 'idperiododocentetb',
                valueField: 'idperiododocente',
                displayField: 'descripcion',
                storeToFilter: 'GestCursos.store.Cursos',
                disabled: true,
                queryMode: 'local',
                width: 250,
                padding: '0 0 0 5',
                labelWidth: 40,
                onTrigger1Click: function () {
                    var me = this;
                    if (me.hasSearch) {
                        me.setValue('');
                        me.store.clearFilter();
                        me.hasSearch = false;
                       me.triggerCell.item(0).setDisplayed(false);
                        me.updateLayout();
                    }
                }
            },
            {
                xtype: 'searchcombofield',
                store: 'GestCursos.store.Horarios',
                emptyText: 'Filtrar por horario',//Etiqueta
                name: 'idhorario',
                valueField: 'idhorario',
                displayField: 'horario_descripcion',
                storeToFilter: 'GestCursos.store.Cursos',
                //filterPropertysNames: ['idhorario'],
                queryMode: 'local',
                width: 180,
                padding: '0 0 0 5',
                labelWidth: 40,
                disabled: true
            }, {
                xtype: 'searchfield',
                id: 'idmatsearch',
                name: 'idmatsearch',
                emptyText: 'Filtrar por materia',//Etiqueta
                store: 'GestCursos.store.Cursos',
                width: 140,
                filterPropertysNames: ['descripcion']
            }
        ];

        this.callParent(arguments);
    }
});