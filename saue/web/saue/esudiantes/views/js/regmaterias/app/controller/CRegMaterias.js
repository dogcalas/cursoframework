Ext.define('RegMaterias.controller.CRegMaterias', {
    extend: 'Ext.app.Controller',

    views: [
        'RegMaterias.view.materia.MateriaListToolBar',
        'GestNotas.view.nota.AlumnoList',
        'GestNotas.view.nota.WindowSearch',
        'RegMaterias.view.materia.ListMaterias',
        'RegMaterias.view.materia.WindowAddMateria',
        'RegMaterias.view.materia.WindowSearch',
        'RegMaterias.view.materia.Search',
        'RegMaterias.view.materia.Grid',
        'RegMaterias.view.materia.Toolbar'
    ],
    stores: [
        'RegMaterias.store.Materia',
        'RegMaterias.store.TipoAprobado',
        'RegMaterias.store.TipoAFiltro',
        'GestNotas.store.Alumnos',
        'GestNotas.store.Periodos',
        'GestNotas.store.Annos',
        'RegMaterias.store.MateriasEst',
        'RegMaterias.store.Periodos',
        'GestCursos.store.Materias',
        'RegMaterias.store.Annos',
        'RegMaterias.store.PeriodoAdd'
    ],

    models: [
        'RegMaterias.model.Materia',
        'RegMaterias.model.TipoAprobado',
        'RegMaterias.model.TipoAFiltro',
        'GestNotas.model.Alumnos',
        'GestNotas.model.Periodos',
        'GestNotas.model.Annos',
        'RegMaterias.model.MateriasEst',
        'RegMaterias.model.Periodos',
        'GestCursos.model.Materia'
    ],

    refs: [
        {ref: 'list', selector: 'materialist'},
        {ref: 'alist', selector: 'alumnolist'},
        {ref: 'mtlist', selector: 'tbmateria'},
        {ref: 'gridlist', selector: 'materiagrid'},
        {ref: 'addmat', selector: 'addmatwindow'},
        {ref: 'windowsearch', selector: 'windowsearch'},
        {ref: 'windowsearch_materia', selector: 'windowsearchmateria'}
    ],

    init: function () {

        this.control({
            'tbmateria button[action=buscar]': {
                click: this.buscar
            },
            'tbmateria searchcombofield[id=periodoList]': {
                afterrender: this.comboEvents,
                select: this.cargarMateria

                //change: this.cargarMateria
            },
            'tbmateria combobox[id=anno]': {
                change: this.updateStore
                // afterrender: this.updateStore
            },
            'tbmateria textfield[id=buscar]': {
                keyup: this.fastSearch
            },
            'windowsearch button[action=aceptar]': {
                click: this.cargarMateria
            },
            'alumnolist': {
                celldblclick: function () {
                    var button = this.getWindowsearch().down('button[action=aceptar]');
                    this.cargarMateria(button);
                }
            },
            'materiagrid': {
                celldblclick: function () {
                    var button = this.getWindowsearch_materia().down('button[action=aceptar]');
                    this.selecmateria(button);
                }
            },
            'materialist': {
                afterrender: this.saveChangesEvent,
                select: this.enabletrashedit,
                selectionchange: this.activarBotones
            },
            'tbmateria button[action=adicionar]': {
                click: this.winadicionar
            },
            'tbmateria button[action=modificar] ': {
                click: this.wineditar
            },
            'tbmateria button[action=eliminar] ': {
                click: this.eliminar
            }, 'tbmateria button[action=retirar] ': {
                click: this.retirar
            },
            'addmatwindow button[id=idBtnAceptarAdd]': {
                click: this.adicionar
            },
            'addmatwindow button[id=idBtnAplicar]': {
                click: this.adicionar
            },
            'addmatwindow combobox[id=annomat]': {
                change: this.updateSMateria
                //afterrender: this.updateSMateria
            },
            'addmatwindow searchfield': {
                click: this.buscarmaterias
            },
            'addmatwindow triggerfield[name=materia]': {
                focus: function (triggerfield) {
                    triggerfield.onTrigger1Click();
                }
            },
            'materiaaa button[action=aceptar]': {
                click: this.selecmateria
            }
        });
    },

    activarBotones: function (store, selected) {
        var me = this,
            tbar = me.getMtlist();
        tbar.down('button[action=modificar]').setDisabled(selected.length === 0 || (store.getCount() != 0 && selected[0].get('idtipoaprobado') == 1000028 || selected[0].get('idtipoaprobado') == 1000021));
        tbar.down('button[action=eliminar]').setDisabled(selected.length === 0);
    },

    selecmateria: function (button) {
        var window = button.up('window'),
            mategrid = window.down('materiagrid'),
            record = mategrid.getSelectionModel().getLastSelected(),
            me = this,

            matadd = me.getAddmat();
        // console.log(record);
        matadd.down('triggerfield[name=materia]').setValue(record.get('descripcion'));
        matadd.down('hidden[name=idmateria]').setValue(record.get('idmateria'));
        matadd.down('hidden[name=minNote]').setValue(record.raw.min_nota_materia);
        window.close();
    },

    enabletrashedit: function () {

        this.getMtlist().geteditboton().setDisabled(false);
        this.getMtlist().getdeleteboton().setDisabled(false);
        this.getMtlist().getretiroboton().setDisabled(false);
        this.getMtlist().getimprimir().setDisabled(false);
    },

    disablebuttons: function () {
        this.getMtlist().geteditboton().setDisabled(true);
        this.getMtlist().getdeleteboton().setDisabled(true);
        this.getMtlist().getretiroboton().setDisabled(true);
        this.getMtlist().getimprimir().setDisabled(true);
    },

    enable: function () {
        var bb = this.getList().enablepagging();
    },


    /////////******************eliminar*********************/////////////////

    eliminar: function () {
        var me = this;
        record = this.getList().getSelectionModel().getSelection();
        mostrarMensaje(
            2,
            perfil.etiquetas.lbMsgEliminar,
            function (btn, text) {
                if (btn == 'ok') {
                    var ids = new Array();
                    for (var i = 0; i <= 20; i++) {
                        if (record[i]) {
                            ids.push(record[i].raw.idalumno_mate_deta);
                        }
                        else
                            break;
                    }
                    me.eliminarMateria(ids);

                    /* me.getList().getStore().removeAll();
                     me.getList().getStore().load();*/
                    me.getMtlist().geteditboton().setDisabled(true);
                    me.getMtlist().getdeleteboton().setDisabled(true);
                    me.getMtlist().getretiroboton().setDisabled(true);
                    me.getMtlist().getimprimir().setDisabled(true);
                }
            }
        )
    },

    eliminarMateria: function (ids, idt) {
        var me = this;
        Ext.Ajax.request({
            url: 'delmateria',
            method: 'POST',
            params: {record: Ext.JSON.encode(ids), idpensum: this.idpensum},
            callback: function (options, success, response) {
                //console.log(response.responseText);
                if (response.responseText === "prerrequisitos") {
                    mostrarMensaje(2, 'Hay prerrequisitos seleccionados, si se eliminan, también serán eliminados las materias dependientes. ', function (btn, text) {

                        if (btn == 'ok') {
                            //datos = action.result.datos;
                            me.delPre(Ext.JSON.encode(ids), me.idpensum);
                            //me.delPre( datos.idmateria, datos.idperiodo, datos.idenfasis, datos.obs, datos.nota, datos.idtipoaprobado, me.idt, win);
                        }
                    });
                }
                else {
                    me.getList().getStore().removeAll();
                    me.getList().getStore().load();
                    responseData = Ext.decode(response.responseText);
                }

            }
        });
    },

    delPre: function (record, idpensum) {
        var me = this;

        Ext.Ajax.request({
            url: 'eliminarPre',
            method: 'POST',
            params: {idmateria: record, idpensum: idpensum},
            //params:{datos: datos, idalumno:idalumno},
            callback: function (options, success, response) {
                responseData = Ext.decode(response.responseText);
                //me.getList().getStore().reload();
                me.getList().getStore().removeAll();
                me.getList().getStore().load();
                //win.close();

            }
        });
    },
    ////////////******************////////////////////

    changeDisable: function (window, eOp) {
        var addboton = this.getMtlist().changeenableboton();
        if (addboton.isDisabled()) {
            addboton.setDisabled(false);
        }
    },

    updateStore: function (combo, nv) {
        if (Ext.getCmp('anno').getValue()) {
            Ext.getCmp('periodoList').getStore().load(
                {
                    params: {
                        anno: Ext.getCmp('anno').getValue()
                    }
                });
            Ext.getCmp('periodoList').setDisabled(false);
            this.cargarMateria();
        }
        else {
            Ext.getCmp('periodoList').clearValue();
            Ext.getCmp('periodoList').getStore().removeAll();
            this.cargarMateria();
        }
    },

    updateSMateria: function (combo, nv) {
        Ext.getCmp('descripcion').clearValue();
        Ext.getCmp('descripcion').getStore().removeAll();
        Ext.getCmp('descripcion').getStore().load({params: {anno: Ext.getCmp('annomat').getValue()}});
    },

    buscar: function (button) {
        var view = Ext.widget('windowsearch', {who: 'alumnolist', height: 400});
    },

    winadicionar: function (button) {
        var add = Ext.widget('addmatwindow', {height: 370});
        var hoy1 = new Date();
        anno1 = hoy1.getFullYear().toString();
        Ext.getCmp('annomat').select(anno1);


    },

    wineditar: function (button) {
        //var tb= button.up('window');
        var edit = Ext.widget('addmatwindow', {height: 380});
        edit.setTitle('Editar materia');
        record = this.getList().getSelectionModel().getLastSelected();
        edit.down('button[action=aplicar]').hide();
        edit.down('form').loadRecord(record);
    },

    adicionar: function (button, eOp) {
        var me = this,
            win = button.up('window'),
            formulario = win.getComponent('idform');

        if (formulario.isValid()) {
            win.getComponent('idform').submit({
                params: {idt: me.idt, idenfasis: me.idenfasis, idcarrera: me.idcarrera},
                failure: function (form, action) {


                    if (action.result.codMsg === 1) {
                        formulario.getForm().reset();
                        me.getList().getStore().load();
                        me.getList().getStore().reload();
                        //formulario.down('combo [id = periodoListMat]').reset();

                        me.disablebuttons();
                        if (button.action === 'aceptar')
                            win.close();
                    }
                    else if (action.result.codMsg === 3) {
                        Ext.JSON.decode(action.result.mensaje);//perfil.etiquetas.lbMsgAddErrorDuplicado);
                        win.close();
                    }
                    else {
                        mostrarMensaje(2, 'Esta materia contiene prerrequisitos que no están aprobados. ¿Desea aprobarlos? ', function (btn, text) {

                            if (btn == 'ok') {
                                datos = action.result.datos;
                                me.savePre(datos.idmateria, datos.idperiodo, datos.idenfasis, datos.obs, datos.nota, datos.idtipoaprobado, me.idt, win);
                            }
                        });
                    }

                }


            });
        }
        else {
            mostrarMensaje(1, 'Debe introducir todos los datos correctamente.');
        }
    },

    savePre: function (idmateria, idperiodo, idenfasis, obs, nota, idtipoaprobado, idalumno, win) {
        var me = this;

        Ext.Ajax.request({
            url: 'salvarPre',
            method: 'POST',
            params: {
                idmateria: idmateria,
                idperiodo: idperiodo,
                idenfasis: idenfasis,
                obs: obs,
                nota: nota,
                idtipoaprobado: idtipoaprobado,
                idalumno: idalumno
            },
            //params:{datos: datos, idalumno:idalumno},
            callback: function (options, success, response) {
                responseData = Ext.decode(response.responseText);
                me.getList().getStore().reload();
                win.close();

            }
        });
    },
    retirar: function () {
        var me = this;
        record = this.getList().getSelectionModel().getSelection();
        mostrarMensaje(
            2,
            perfil.etiquetas.lbMsgRetirar,
            function (btn, text) {
                if (btn == 'ok') {
                    var ids = new Array();
                    for (var i = 0; i <= 20; i++) {
                        if (record[i]) {
                            //if (record[i].raw.idtipoaprobado == 1000028) {
                            ids.push(record[i].raw.idalumno_mate_deta);
                            //}
                            /*else {
                             mostrarMensaje(3, perfil.etiquetas.lbMsgFunRetirarMateriaMsgError);
                             var error = true;
                             break;
                             }*/
                        }
                        else
                            break;
                    }
                    //if (!error) {
                    me.retirarMateria(ids);
                    me.getList().getStore().reload();
                    me.getList().getStore().removeAll();
                    me.getList().getStore().load();
                    me.disablebuttons();
                    //}
                }
            }
        )
    },

    retirarMateria: function (ids) {
        Ext.Ajax.request({
            url: 'retiromateria',
            method: 'POST',
            params: {record: Ext.JSON.encode(ids)},
            callback: function (options, success, response) {
                responseData = Ext.decode(response.responseText);
            }
        });
    },

    mostrarError: function (type) {
        mostrarMensaje(1, perfil.etiquetas.lbMsgError + " " + type + ".")
    },
    saveChangesEvent: function (grid, eOp) {
        var me = this;
        grid.getPlugin('cellplugin').on('edit', function (editor, e) {
            e.grid.getStore().sync();
        });
        grid.getStore().on('beforeload', function (store) {
            store.getProxy().extraParams = {
                type: me.typo,
                idt: me.idt,
                idpd: Ext.getCmp('periodoList').getValue(),
                idpensum: me.idpensum,
                anno: Ext.getCmp('anno').getValue()
            };
        });

        me.buscar();
    },
    comboEvents: function (combo, eOp) {
        combo.getStore().on('beforeload', function (store) {
            store.getProxy().extraParams = {anno: Ext.getCmp('anno').getValue()};
        });
        //combo.getStore().on('load', function (store) {
        //    if (store.count() > 0)
        //        combo.select(store.getAt(0).data.idperiododocente);
        //});
    },
    cargarMateria: function (button) {
        if (button) {
            var win = button.up('window');
        }
        var me = this;
        if (win) {
            record = me.getAlist().getSelectionModel().getSelection()[0];
            if (record) {
                Ext.getCmp('anno').setValue('');

                me.getList().reconfigure(me.getList().getStore(), [
                    {dataIndex: 'idalumno', hidden: true, hideable: false},
                    {dataIndex: 'idalumno_mate_deta', hidden: true, hideable: false},
                    {dataIndex: 'idmateria', hidden: true, hideable: false},
                    {dataIndex: 'idperiodo', hidden: true, hideable: false},
                    {dataIndex: 'idtipoaprobado', hidden: true, hideable: true},
                    {header: 'Año', dataIndex: 'anno', width: 40},
                    {header: 'Período', dataIndex: 'descripcion', flex: 1.5},
                    {header: 'Estado', dataIndex: 'tipo', flex: 2},
                    {header: 'Nota', dataIndex: 'nota', flex: 1 / 2},
                    {header: 'Observaciones', dataIndex: 'observacion', flex: 3},
                    {header: 'Cod. Materia', dataIndex: 'codmateria', width: 80},
                    {header: 'Materia', dataIndex: 'materia', flex: 3},
                    {header: 'Crédito', dataIndex: 'cred', flex: 0.6},
                    {header: 'Falta', dataIndex: 'falta', flex: 1 / 2}
                ]);
                me.typo = 'alumno';
                me.idt = record.data.idalumno;
                me.idpensum = record.raw.idpensum;
                me.idcarrera = record.data.idcarrera;
                me.idenfasis = record.data.idenfasis;
                me.enable();
                //console.log(record);
                me.getList().getStore().getProxy().extraParams = {
                    type: me.typo,
                    idt: me.idt,
                    idpd: Ext.getCmp('periodoList').getValue(),
                    idtipoa: Ext.getCmp('idtipoaprob').getValue(),
                    idpensum: me.idpensum,
                    anno: Ext.getCmp('anno').getValue()
                };
                me.getList().getStore().load();
                me.getList().setTitle("Nombre: " + record.data.nombre + " " + record.data.apellidos + "| C&oacute;digo: " + record.data.codigo);

                me.getAlist().getStore().removeAll();
                me.getAlist().getSelectionModel().deselectAll(true);
                me.getMtlist().changeenableboton().setDisabled(false);
                me.getMtlist().getComponent('idtipoaprob').setDisabled(false);

                Ext.getCmp('periodoList').setDisabled(false);
                Ext.getCmp('anno').setDisabled(false);

                win.close();
            } else {
                me.mostrarError(perfil.etiquetas.lbMsgEst);
            }
        }
        else {
            if (me.idt) {
                me.typo = 'alumno';
                me.enable();
                me.getList().getStore().getProxy().extraParams = {
                    type: me.typo,
                    idt: me.idt,
                    idpd: Ext.getCmp('periodoList').getValue(),
                    idtipoa: Ext.getCmp('idtipoaprob').getValue(),
                    idpensum: record.data.idpensum,
                    anno: Ext.getCmp('anno').getValue()
                };
                me.getList().getStore().load();
                me.getMtlist().changeenableboton().setDisabled(false);
                me.getMtlist().getComponent('idtipoaprob').setDisabled(false);
            }

        }


    }
});