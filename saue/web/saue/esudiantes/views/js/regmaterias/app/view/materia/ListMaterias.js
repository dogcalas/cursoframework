Ext.define('RegMaterias.view.materia.ListMaterias',
    {
        extend: 'Ext.grid.Panel',
        alias: 'widget.materialist',
        id: 'materialist',
        store: 'RegMaterias.store.MateriasEst',
        selType: 'rowmodel',
        plugins: [
            Ext.create('Ext.grid.plugin.CellEditing', {
                clicksToEdit: 1,
                pluginId: 'cellplugin'
            })
        ],
        columns: [
            {dataIndex: 'idalumno', hidden: true, hideable: false},
            {dataIndex: 'idpd', hidden: true},
            {dataIndex: 'idtipoaprobado', hidden: true},
            //{ dataIndex: 'tipo', hidden: true},
            {dataIndex: 'estado', hidden: true},
            {dataIndex: 'idalumno_mate_deta', hidden: true, hideable: false},
            {header: 'Año', dataIndex: 'anno', width: 30},
            {header: 'Período', dataIndex: 'descripcion'},
            {header: 'Estado', dataIndex: 'tipo'},
            {header: 'Cod. Materia', dataIndex: 'codmateria', width: 80},
            {header: 'Materia', dataIndex: 'materia'},
            {header: 'Crédito', dataIndex: 'cred', width: 50},
            //{header: 'Paralelo', dataIndex: '"par_curs', flex: 1},
            {header: 'Nota', dataIndex: 'examen', width: 40},
            {header: 'Observaciones', dataIndex: 'desc'}],
        selModel: Ext.create('Ext.selection.RowModel', {
            id: 'idSelectionMateriaAlumno',
            mode: 'MULTI'
        }),
        viewConfig: {
            getRowClass: function (record, rowIndex, rowParams, store) {
                if (record.data.idtipoaprobado == 1000013)
                    return 'FilaVerde';
                else if (record.data.idtipoaprobado == 1000017)
                    return 'FilaRojaReXFaltasNotas';
                else if (record.data.idtipoaprobado == 1000014)
                    return 'FilaRojaReXFaltas';
                else if (record.data.idtipoaprobado == 1000029)
                    return 'FilaRojaReXNotas';
                else if (record.data.idtipoaprobado == 1000030)
                    return 'FilaRojaReXNotas';
                else if (record.data.idtipoaprobado == 1000032)
                    return 'FilaRojaReXNotas';
                else if (record.data.idtipoaprobado == 1000034)
                    return 'FilaRojaReXNotas';
                else if (record.data.idtipoaprobado == 1000021)
                    return 'FilaNaranjaAprob';
                else if (record.data.idtipoaprobado == 1000033)
                    return 'FilaNarXUb';
                else if (record.data.idtipoaprobado == 1000031)
                    return 'FilaNaranja';
                else if (record.data.idtipoaprobado == 1000035)
                    return 'FilaNaranja';
                else if (record.data.idtipoaprobado == 1000024)
                    return 'FilaGris';
                else if (record.data.idtipoaprobado == 1000015)
                    return 'FilaRojaReprobado';
                else if (record.data.idtipoaprobado == 1000023)
                    return 'FilaAzulClaro';
            }
        },
        initComponent: function () {
            var me = this;
            me.bbar = Ext.create('Ext.toolbar.Paging', {
                displayInfo: true,
                id: 'idbb',
                disabled: true,
                store: me.store
            });
            me.callParent(arguments);
        },

        enablepagging: function () {
            this.getComponent('idbb').setDisabled(false);
        }
    });
