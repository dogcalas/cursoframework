var xg = Ext.grid;
var camposGridDinamico;
var storegrid;
var idusuario;
var newcm;
var datos = [[]];
var criterioSel;
var fila;
var col;
var responseData;
var checked = true;
var WithOutSaved = true;
////------------ Declarar variables ------------//// 
var winIns, winMod, winCamb, winConex;
var usuarioRegExp = /(^([a-zA-ZáéíóúñüÑ])+([a-zA-ZáéíóúñüÑ]*))$/
////------------ Area de Validaciones ------------////

var today = new Date();
var day = today.getDate();
var month = today.getMonth();
var year = today.getFullYear();
if (day < 10)
    day = '0' + day;
month += 1;
if (month < 10)
    month = '0' + month;
var fechaactual = day + '/' + month + '/' + year;
var FilasColumnasModificadas = new Array();
var InicialFalses = new Array();
var regexObj = /(^((([a-zA-Z_])+([a-zA-Z0-9_]*))((\.{0,1})([a-zA-Z_]+)([a-zA-Z0-9_]*))?))$/;
////------------ Botones ------------////
btnAdicionar = new Ext.Button({id: 'btnAgrBd', hidden: true, icon: perfil.dirImg + 'adicionar.png', iconCls: 'btn', text: '<b>' + perfil.etiquetas.lbBtnAdicionar + '</b>', handler: function() {
        winForm('Ins');
    }});
btnModificar = new Ext.Button({disabled: true, id: 'btnModBd', hidden: true, icon: perfil.dirImg + 'modificar.png', iconCls: 'btn', text: '<b>' + perfil.etiquetas.lbBtnModificar + '</b>', handler: function() {
        winForm('Mod');
    }});
btnEliminar = new Ext.Button({disabled: true, id: 'btnEliBd', hidden: true, icon: perfil.dirImg + 'eliminar.png', iconCls: 'btn', text: '<b>' + perfil.etiquetas.lbBtnEliminar + '</b>', handler: function() {
        eliminarbd();
    }});
btnAyuda = new Ext.Button({id: 'btnAyuBd', hidden: true, icon: perfil.dirImg + 'ayuda.png', iconCls: 'btn', text: '<b>' + perfil.etiquetas.lbBtnAyuda + '</b>'});
var checkRolConexiones = new Ext.form.Checkbox({
    boxLabel: '<b>' + perfil.etiquetas.lbBtnConexion + '</b>',
    id: 'checkselecteds',
    disabled: true,
    listeners: {
        'check': function(checkSelect, c) {
            if (checked) {
                var record = sm.getSelected();
                var existConex = record.get('existconex');
                if (existConex == 1) {
                    if (c == false) {
                        deleteConexion();
                    }
                }
                else {
                    winForm('Conex', 'mod');
                }
            }
        }

    }}
);
UCID.portal.cargarAcciones(window.parent.idFuncionalidad);
////------------ Store del Grid de bases de datos ------------////
stGpBd = new Ext.data.Store({
    url: 'cargarRolesBD',
    reader: new Ext.data.JsonReader({
        totalProperty: "cantidad_filas",
        root: "datos",
        id: "rolname"
    },
    [
        {name: 'oid', mapping: 'oid'},
        {name: 'rolname', mapping: 'rolname'},
        {name: 'rolsuper', mapping: 'rolsuper'},
        {name: 'rolinherit', mapping: 'rolinherit'},
        {name: 'rolcreaterole', mapping: 'rolcreaterole'},
        {name: 'rolcreatedb', mapping: 'rolcreatedb'},
        {name: 'rolcatupdate', mapping: 'rolcatupdate'},
        {name: 'rolpassword', mapping: 'rolpassword'},
        {name: 'rolcanlogin', mapping: 'rolcanlogin'},
        {name: 'estado', mapping: 'estado'},
        {name: 'existconex', mapping: 'existconex'},
    ])
});
///--------------------------
var stcombo = new Ext.data.Store({
    url: 'getcriterios',
    autoLoad: true,
    reader: new Ext.data.JsonReader({
        id: "criterio"
    },
    [
        {name: 'criterio', mapping: 'criterio'}
    ])
});
var combocriterios = new Ext.form.ComboBox({
    fieldLabel: perfil.etiquetas.lbComboCriterio,
    id: 'cmbcriterios',
    xtype: 'combo',
    store: stcombo,
    disabled: true,
    valueField: 'criterio',
    displayField: 'criterio',
    triggerAction: 'all',
    editable: false,
    mode: 'local',
    emptyText: perfil.etiquetas.lbMsgEmptyText,
    anchor: '50%',
    width: 100,
    listeners: {
        select: onclickcombo,
//                enable:function(){
//                    alert(item.disabled)
//                    item.enable();
//                },
//                disable:function(){
//                    alert(item.disabled)
//                    item.disable();
//                    
//                }
    }
})
/////-------------function onclickcombo---------------////////////   
function onclickcombo() {
    smgestion = new Ext.grid.RowSelectionModel({
        singleSelect: true
    })

    /////-------------Store del grid dinamico---------------////////////
    storegrid = new Ext.data.Store({
        url: '',
        listeners: {'beforeload': function(thisstore, objeto) {
                objeto.params.esqSelected = Ext.getCmp('esquemas').getValue();
            }},
        pruneModifiedRecords: true,
        reader: new Ext.data.JsonReader({
            totalProperty: "totalProperty",
            root: "root"
        }, [{
                name: 'vacio'
            }])
    });

    cmGestionhist = new Ext.grid.ColumnModel([{
            id: 'expandir',
            autoExpandColumn: 'expandir'
        }]);


    gdGestionHis = new xg.EditorGridPanel({
        frame: true,
        sm: smgestion,
        clicksToEdit: 5,
        store: storegrid,
        autowidth: true,
        visible: true,
        title: perfil.etiquetas.titleGridpermisos,
        listeners: {'cellclick': function(grid, rowIndex, columnIndex, e) {
                var record = grid.getStore().getAt(rowIndex);  // Get the Record
                var fieldName = grid.getColumnModel().getDataIndex(columnIndex); // Get field name
                var data = record.get(fieldName);
                if (fieldName != "OWN") {
                    storegrid.getAt(rowIndex).set(grid.getColumnModel().getColumnHeader(columnIndex), !data);
                    CampiarEstadoFilaColumna(rowIndex, columnIndex - 1);
                } else {
                    if (data && !IsInicialFalse(rowIndex, columnIndex)) {
                        storegrid.getAt(rowIndex).set(grid.getColumnModel().getColumnHeader(columnIndex), false);
                        CampiarEstadoFilaColumna(rowIndex, columnIndex - 1);
                        storegrid.getAt(rowIndex).set(grid.getColumnModel().getColumnHeader(columnIndex), true);
                        CampiarEstadoFilaColumna(rowIndex, columnIndex - 1);
                    } else {
                        storegrid.getAt(rowIndex).set(grid.getColumnModel().getColumnHeader(columnIndex), !data);
                        CampiarEstadoFilaColumna(rowIndex, columnIndex - 1);
                        if (!IsInicialFalse(rowIndex, columnIndex)) {
                            var longitud = InicialFalses.length;
                            InicialFalses[longitud] = rowIndex + "-" + columnIndex;
                        }
                    }
                }

            }},
        loadMask: {
            store: storegrid
        },
        cm: cmGestionhist,
        tbar: [new Ext.Toolbar.TextItem({text: perfil.etiquetas.lbTitNombreObj + '\n' + combocriterios.getValue()}),
            esquemaSelect = new Ext.form.TextField({
                width: 80, id: 'esquemas',
                regex: regexObj,
                maskRe: /[a-zA-Z0-9_.]/i,
                regexText: perfil.etiquetas.MsgInvalidRegex

            }),
            new Ext.menu.Separator(),
            new Ext.Button({
                icon: perfil.dirImg + 'buscar.png',
                iconCls: 'btn',
                text: perfil.etiquetas.lbBtnBuscar,
                handler: function() {
                    if (Ext.getCmp('esquemas').isValid())
                        buscarEsquemas(esquemaSelect.getValue());
                }
            })
        ],
        bbar: new Ext.PagingToolbar({
            store: storegrid,
            displayInfo: true,
            pageSize: 15

        })
    });

    winCrt = new Ext.Window({modal: true, closeAction: 'hide', layout: 'fit',
        width: 700, height: 550, resizable: false, closable: false, draggable: false, region: 'center',
        buttons: [
            {
                icon: perfil.dirImg + 'cancelar.png',
                iconCls: 'btn',
                handler: function() {
                    winCrt.close();
                },
                text: perfil.etiquetas.lbBtnCerrar
            }, {
                icon: perfil.dirImg + 'aceptar.png',
                iconCls: 'btn',
                handler: function() {
                    asignar();
                },
                text: perfil.etiquetas.lbBtnAplicar
            }]
    })
    criterioSel = combocriterios.getValue();
    Ext.getBody().mask(perfil.etiquetas.MsgCargando);
    Ext.Ajax.request({
        url: 'configrid',
        method: 'POST',
        params: {criterio: criterioSel},
        callback: function(options, success, response) {
            responseData = Ext.decode(response.responseText);
            camposGridDinamico = responseData.grid.campos;

            for (var i = 1; i < responseData.grid.columns.length; i++) {
                var aux = responseData.grid.columns[i];
                responseData.grid.columns[i].editor = new Ext.form.Checkbox({checked: false, listeners: {
                        check: function(_this, checked) {
                        }}});
                aux.renderer = function(data, cell, record, rowIndex, columnIndex, store) {
                    if (data) {
                        return "<img src='../../../../images/icons/validado.png' />";
                    }
                    else {
                        return "<img src='../../../../images/icons/no_validado.png' />";
                    }
                }

            }
            newcm = Ext.UCID.generaDinamico('cm', responseData.grid.columns);

            storegrid = new Ext.data.Store({
                url: 'cargargriddatos',
//                listeners : {'load' : function() {gdGestionHis.getSelectionModel().selectFirstRow()}
//                                    },
                reader: new Ext.data.JsonReader({
                    totalProperty: 'cantidad',
                    root: 'datos',
                    id: 'iddatos'
                }, Ext.UCID.generaDinamico('rdcampos', responseData.grid.campos))
            });

            var menu = new Ext.menu.Menu({
                id: 'submenu',
                items: [{
                        text: perfil.etiquetas.menuTextSelectAllFile,
                        scope: this,
                        icon: "../../../../images/icons/añadir.png",
                        handler: function() {
                            for (var i = 1; i < newcm.getColumnCount(); i++) {
                                storegrid.getAt(fila).set(newcm.getColumnHeader(i), true);
                                CampiarEstadoFilaColumna(fila, i - 1);
                            }
                        }},
                    {
                        text: perfil.etiquetas.menuTextSelectAllColumn,
                        scope: this,
                        icon: "../../../../images/icons/añadir.png",
                        handler: function() {
                            for (var i = 0; i < storegrid.getCount(); i++) {
                                storegrid.getAt(i).set(newcm.getColumnHeader(col), true);
                                CampiarEstadoFilaColumna(i, col - 1);
                            }
                        }},
                    {
                        text: perfil.etiquetas.menuTextUnCheckAllFile,
                        scope: this,
                        icon: "../../../../images/icons/eliminar.png",
                        handler: function() {
                            for (var i = 1; i < newcm.getColumnCount(); i++) {
                                storegrid.getAt(fila).set(newcm.getColumnHeader(i), false);
                                CampiarEstadoFilaColumna(fila, i - 1);
                            }
                        }},
                    {
                        text: perfil.etiquetas.menuTextUnCheckAllColumn,
                        scope: this,
                        icon: "../../../../images/icons/eliminar.png",
                        handler: function() {
                            for (var i = 0; i < storegrid.getCount(); i++) {
                                storegrid.getAt(i).set(newcm.getColumnHeader(col), false);
                                CampiarEstadoFilaColumna(i, col - 1);
                            }
                        }}]
            });

            gdGestionHis.on('cellcontextmenu', function(_this, rowIndex, cellIndex, e) {
                fila = rowIndex;
                col = cellIndex;
                smgestion.selectRow(fila);
                e.stopEvent();
                menu.showAt(e.getXY());
            }, this);


            if (newcm && storegrid) {
                gdGestionHis.reconfigure(storegrid, newcm);
                gdGestionHis.getBottomToolbar().bind(storegrid);

                storegrid.load({params: {start: 0, limit: 15, rolbd: sm.getSelected().data.rolname, idrolselec: sm.getSelected().data.oid, user: usuario, ip: ipservidor, gestor: gestorBD, passw: password, bd: baseDato, criterio: combocriterios.getValue()}});
                storegrid.on('load', function(s, r) {
                    ReiniciarFilasColumnas();
                    if (!r.length) {
                        mostrarMensaje(1, perfil.etiquetas.lbMsgInfObjetos);
                    } else
                        gdGestionHis.getSelectionModel().selectFirstRow();
                });
                storegrid.on('beforeload', function(thisstore, objeto) {
                    objeto.params.esqSelected = Ext.getCmp('esquemas').getValue();
                    var esqu = Ext.getCmp('esquemas').getValue();
                    if (esqu != "")
                        storegrid.baseParams = {esqSelected: esqu, rolbd: sm.getSelected().data.rolname, idrolselec: sm.getSelected().data.oid, user: usuario, ip: ipservidor, gestor: gestorBD, passw: password, bd: baseDato, criterio: criterioSel};
                    else
                        storegrid.baseParams = {rolbd: sm.getSelected().data.rolname, idrolselec: sm.getSelected().data.oid, user: usuario, ip: ipservidor, gestor: gestorBD, passw: password, bd: baseDato, criterio: criterioSel};
                });
            }
            combocriterios.clearValue();
        }
    });
    winCrt.add(gdGestionHis);
    winCrt.doLayout();
    winCrt.show();
    Ext.getBody().unmask();

}

////------------ Establesco modo de seleccion de grid (single) ------------////
sm = new Ext.grid.RowSelectionModel({singleSelect: true});

sm.on('beforerowselect', function(smodel, rowIndex, keepExisting, record) {
    btnModificar.enable();
    combocriterios.enable();
    oid = record.data.oid;
    btnEliminar.enable();

}, this);
sm.on('rowselect', function(smodel, rowIndex, record) {

    checkRolConexiones.enable();
    if (record.get('existconex') == 1) {
        if (record.get('estado') == 1) {
            checked = false;
            checkRolConexiones.setValue(true);
            checked = true;

        }
        else {
            checked = false;
            checkRolConexiones.setValue(false);
            checkRolConexiones.disable();
            checked = false;
        }
    }


}, this);

////------------ Defino el grid de bases de datos ------------////
var GpBd = new Ext.grid.EditorGridPanel({
    frame: true,
    height: 520,
    iconCls: 'icon-grid',
    autoExpandColumn: 'expandir',
    store: stGpBd,
    sm: sm,
    columns: [
        {id: 'expandir', header: perfil.etiquetas.lbTitNombreRol, dataIndex: 'rolname'},
        {header: perfil.etiquetas.lbTitSR, dataIndex: 'rolsuper', renderer: function(data, cell, record, rowIndex, columnIndex, store) {
                if (data)
                    return "<img src='../../../../images/icons/validado.png' />";
                else
                    return "<img src='../../../../images/icons/no_validado.png' />";
            }},
        {header: perfil.etiquetas.lbTitHP, dataIndex: 'rolinherit', renderer: function(data, cell, record, rowIndex, columnIndex, store) {
                if (data)
                    return "<img src='../../../../images/icons/validado.png' />";
                else
                    return "<img src='../../../../images/icons/no_validado.png' />";
            }},
        {header: perfil.etiquetas.lbTitCR, dataIndex: 'rolcreaterole', renderer: function(data, cell, record, rowIndex, columnIndex, store) {
                if (data)
                    return "<img src='../../../../images/icons/validado.png' />";
                else
                    return "<img src='../../../../images/icons/no_validado.png' />";
            }},
        {header: perfil.etiquetas.lbTitCBD, dataIndex: 'rolcreatedb', renderer: function(data, cell, record, rowIndex, columnIndex, store) {
                if (data)
                    return "<img src='../../../../images/icons/validado.png' />";
                else
                    return "<img src='../../../../images/icons/no_validado.png' />";
            }},
        {header: perfil.etiquetas.lbTitCU, width: 110, dataIndex: 'rolcatupdate', renderer: function(data, cell, record, rowIndex, columnIndex, store) {
                if (data)
                    return "<img src='../../../../images/icons/validado.png' />";
                else
                    return "<img src='../../../../images/icons/no_validado.png' />";
            }},
        {header: perfil.etiquetas.lbTitCL, dataIndex: 'rolcanlogin', renderer: function(data, cell, record, rowIndex, columnIndex, store) {
                if (data)
                    return "<img src='../../../../images/icons/validado.png' />";
                else
                    return "<img src='../../../../images/icons/no_validado.png' />";
            }},
        {hidden: true, dataIndex: 'rolpassword'},
        {hidden: true, dataIndex: 'estado'},
        {hidden: true, dataIndex: 'existconex'}
    ],
    loadMask: {store: stGpBd},
    tbar: [
        new Ext.Toolbar.TextItem({text: perfil.etiquetas.lbFLDenominacion}),
        rol = new Ext.form.TextField({
            width: 80, id: 'nombrerol',
            regex: /(^([a-zA-Z_])+([a-zA-Z0-9_]*))$/,
            maskRe: /[a-zA-Z0-9_]/i,
            regexText: perfil.etiquetas.MsgInvalidRegex
        }),
        new Ext.menu.Separator(),
        new Ext.Button({
            icon: perfil.dirImg + 'buscar.png',
            iconCls: 'btn',
            text: perfil.etiquetas.lbBtnBuscar,
            handler: function() {
                var textFiel = Ext.getCmp('nombrerol')
                if (textFiel.isValid())
                    buscarrol(rol.getValue());
            }
        })
    ],
    bbar: new Ext.PagingToolbar({
        pageSize: 15,
        store: stGpBd,
        displayInfo: true,
        displayMsg: perfil.etiquetas.lbMsgbbarI,
        emptyMsg: perfil.etiquetas.lbMsgbbarII
    })
});
GpBd.getView().getRowClass = function(record, index, rowParams, store) {
    if (record.data.estado == 1)
        return 'FilaRoja';
};

////------------ Renderiar el panel ------------////
//var item=new Ext.Toolbar.TextItem({
//    xtype: 'tbtext',id:'label-combo-criterio', text: perfil.etiquetas.lbComboCriterio,
//    disabled:true
//    
//});

var panelConexiones = new Ext.Panel({
    id: 'pgsql',
    title: perfil.etiquetas.lbTitRender,
    items: [GpBd],
    tbar: [btnAdicionar, btnModificar, btnEliminar, btnAyuda, '<b>' + perfil.etiquetas.lbComboCriterio + '</b>', combocriterios, '', checkRolConexiones]
            //tbar: [btnAdicionar, btnModificar, btnEliminar, btnAyuda,item,combocriterios,'',checkRolConexiones]
});

panelAdicionar.add(panelConexiones);
panelAdicionar.doLayout();
//panelConexiones.render('conexiones');
stGpBd.baseParams = {user: usuario, ip: ipservidor, gestor: gestorBD, passw: password, bd: baseDato, puerto: puerto};
stGpBd.load({params: {limit: 15, start: 0}});
////------------ Formulario ------------////
var regBd = new Ext.FormPanel({
    frame: true,
    width: 200,
    bodyStyle: 'padding:5px 5px 0',
    id: 'pgsqlfrm',
    items: [{
            layout: 'form',
            items: [{
                    xtype: 'textfield',
                    fieldLabel: perfil.etiquetas.lbTitNombreRol,
                    id: 'rolname',
                    blankText: perfil.etiquetas.lbMsgBlank,
                    allowBlank: false,
                    labelStyle: 'width:120px',
                    width: 200,
                    regex: /(^([a-z])+([a-z0-9]*))$/,
                    regexText: perfil.etiquetas.MsgInvalidRegexRol

                }, {
                    xtype: 'textfield',
                    fieldLabel: perfil.etiquetas.lbContrasena,
                    inputType: 'password',
                    id: 'contrasena',
                    blankText: perfil.etiquetas.lbMsgBlank,
                    labelStyle: 'width:120px',
                    width: 200
                }, {
                    xtype: 'textfield',
                    fieldLabel: perfil.etiquetas.lbNewContrasena,
                    inputType: 'password',
                    blankText: perfil.etiquetas.lbMsgBlank,
                    id: 'newcontrasena',
                    labelStyle: 'width:120px',
                    width: 200
                }, {
                    columnWidth: .5,
                    layout: 'column',
                    items: [{
                            columnWidth: .7,
                            layout: 'form',
                            items: [{
                                    xtype: 'datefield',
                                    tabIndex: 18,
                                    labelStyle: 'width:120px',
                                    fieldLabel: perfil.etiquetas.fecha,
                                    readOnly: true,
                                    id: 'fechainicio',
                                    width: 88,
                                    minValue: fechaactual,
                                    format: 'd/m/Y',
                                    listeners: {
                                        change: function() {
                                            Ext.getCmp('horaaa').enable();
                                        }
                                    }
                                }]
                        }, {
                            columnWidth: .3,
                            layout: 'form',
                            items: [{
                                    xtype: 'timefield',
                                    id: 'horaaa',
                                    readOnly: true,
                                    hideLabel: true,
                                    disabled: true,
                                    format: 'H:i',
                                    width: 75
                                }]
                        }]
                }, {
                    columnWidth: .5,
                    layout: 'form',
                    items: [{
                            xtype: 'fieldset',
                            title: perfil.etiquetas.privilegios,
                            autoHeight: 'auto',
                            defaultType: 'checkbox',
                            width: 324,
                            items: [{
                                    hideLabel: true,
                                    boxLabel: perfil.etiquetas.Hpermisos,
                                    name: 'permisos',
                                    id: 'rolinherit'
                                }, {
                                    hideLabel: true,
                                    boxLabel: perfil.etiquetas.lbTitSR,
                                    id: 'rolsuper',
                                    listeners: {
                                        check: function() {
                                            if (Ext.getCmp('rolsuper').getValue())
                                            {
                                                Ext.getCmp('rolcatupdate').enable();
                                            }
                                            else
                                                Ext.getCmp('rolcatupdate').disable();
                                        }
                                    }
                                }, {
                                    hideLabel: true,
                                    boxLabel: perfil.etiquetas.lbTiOBD,
                                    id: 'rolcreatedb'
                                }, {
                                    hideLabel: true,
                                    boxLabel: perfil.etiquetas.lbTiCR,
                                    id: 'rolcreaterole'
                                }, {
                                    hideLabel: true,
                                    boxLabel: perfil.etiquetas.lbTiMCD,
                                    disabled: true,
                                    id: 'rolcatupdate'
                                }]
                        }]
                }]
        }]
});
var rolname;
var contrasena;
var fechainicio;
var horaaa;
var rolinherit;
var rolsuper;
var rolcreatedb;
var rolcreaterole;
var rolcatupdate;

function InicialValuesFor() {
    rolname = Ext.getCmp('rolname').getValue();
    contrasena = Ext.getCmp('contrasena').getValue();
    fechainicio = Ext.getCmp('fechainicio').getValue();
    horaaa = Ext.getCmp('horaaa').getValue();
    rolinherit = Ext.getCmp('rolinherit').getValue();
    rolsuper = Ext.getCmp('rolsuper').getValue();
    rolcreatedb = Ext.getCmp('rolcreatedb').getValue();
    rolcreaterole = Ext.getCmp('rolcreaterole').getValue();
    rolcatupdate = Ext.getCmp('rolcatupdate').getValue();
}

////------------ Cargar la ventana ------------////
function winForm(opcion) {
    switch (opcion) {
        case 'Ins':
            {
                if (!winIns)
                {
                    winIns = new Ext.Window({modal: true, closeAction: 'hide', layout: 'fit',
                        resizable: false,
                        title: perfil.etiquetas.lbTitVentanaAddTit, width: 390, height: 380,
                        buttons: [
                            {
                                icon: perfil.dirImg + 'cancelar.png',
                                iconCls: 'btn',
                                text: perfil.etiquetas.lbBtnCancelar,
                                handler: function() {
                                    restaurarvalores();
                                }
                            },
                            {
                                icon: perfil.dirImg + 'aplicar.png',
                                iconCls: 'btn',
                                text: perfil.etiquetas.lbBtnAplicar,
                                handler: function() {
                                    adicionarrolbasedatos('apl');
                                }
                            },
                            {
                                icon: perfil.dirImg + 'aceptar.png',
                                iconCls: 'btn',
                                text: perfil.etiquetas.lbBtnAceptar,
                                handler: function() {
                                    adicionarrolbasedatos();
                                }
                            }]
                    });
                }
                regBd.getForm().reset();
                winIns.add(regBd);
                winIns.doLayout();
                winIns.show();
            }
            break;
        case 'Mod':
            {

                if (!winMod)
                {
                    winMod = new Ext.Window({modal: true, closeAction: 'hide', layout: 'fit',
                        resizable: false,
                        title: perfil.etiquetas.lbTitVentanaDelTit, width: 390, height: 380,
                        buttons: [
                            {
                                icon: perfil.dirImg + 'cancelar.png',
                                iconCls: 'btn',
                                text: perfil.etiquetas.lbBtnCancelar,
                                handler: function() {
                                    winMod.hide();
                                }
                            },
                            {
                                icon: perfil.dirImg + 'aceptar.png',
                                iconCls: 'btn',
                                text: perfil.etiquetas.lbBtnAceptar,
                                handler: function() {
                                    modificarrolbd();
                                }
                            }]
                    });
                }
                winMod.add(regBd);
                winMod.doLayout();
                winMod.show();
                regBd.getForm().loadRecord(sm.getSelected());
                InicialValuesFor();
            }
            break;
        case 'Conex':
            {
                if (!winConex)
                {
                    winConex = new Ext.Window({modal: true, closeAction: 'hide', layout: 'fit',
                        resizable: false,
                        title: perfil.etiquetas.lbTitVentanaConexTit, width: 390, height: 380,
                        buttons: [
                            {
                                icon: perfil.dirImg + 'cancelar.png',
                                iconCls: 'btn',
                                text: perfil.etiquetas.lbBtnCancelar,
                                handler: function() {
                                    checkRolConexiones.setValue(false);
                                    winConex.hide();
                                }
                            },
                            {
                                icon: perfil.dirImg + 'aceptar.png',
                                iconCls: 'btn',
                                text: perfil.etiquetas.lbBtnAceptar,
                                handler: function() {
                                    crearRolConexiones();
                                }
                            }]
                    });
                }
                winConex.add(regBd);
                winConex.doLayout();
                winConex.show();
                regBd.getForm().loadRecord(sm.getSelected());
            }
            break;
    }
}
////-----------Restaurar valores-------------////
function restaurarvalores()
{
    Ext.getCmp('rolcatupdate').disable();
    Ext.getCmp('horaaa').disable();
    winIns.hide();
}
////------------ Adicionar Base de Datos ------------////    
function IsInicialFalse(p, j) {
    var cmp = p + "-" + j;
    for (i = 0; i < InicialFalses.length; i++) {
        if (InicialFalses[i] == cmp)
            return true;
    }
    return false;
}

function crearRolConexiones() {
    if (regBd.getForm().isValid()) {
        if (Ext.getCmp('contrasena').getValue() != "") {
            if (Ext.getCmp('contrasena').getValue() == Ext.getCmp('newcontrasena').getValue()) {
                regBd.getForm().submit({
                    url: 'crearConexion',
                    waitMsg: perfil.etiquetas.lbMsgRolConexionesMsg,
                    params: {
                        user: usuario,
                        ip: ipservidor,
                        gestor: gestorBD,
                        passw: password,
                        bd: baseDato,
                        oid: sm.getSelected().data.oid,
                        idservidor: idservidor,
                        idgestor: idgestor
                    },
                    failure: function(form, action) {
                        if (action.result.codMsg != 3) {
                            //mostrarMensaje(action.result.codMsg, perfil.etiquetas.lbMsgInfConex);
                            stGpBd.reload();
                            sm.clearSelections();
                            checked = false;
                            checkRolConexiones.setValue(true);
                            checked = true;
                            checkRolConexiones.disable();
                            GpBd.getView().refresh();
                            winConex.hide();
                        } else
//                        if (action.result.codMsg == 3) {
//                            mostrarMensaje(action.result.codMsg, action.result.mensaje);
//                        }
                            Ext.getCmp('contrasena').setValue("");
                        Ext.getCmp('newcontrasena').setValue("");
                    }
                });
            }
            else
                mostrarMensaje(3, perfil.etiquetas.lbTitMsgContrasenaIncorrecta);
        } else {
            mostrarMensaje(3, perfil.etiquetas.lbTitMsgContrasenaBlank);
        }
    }
    else
        mostrarMensaje(3, perfil.etiquetas.lbMsgErrorEnCamops);

}
function deleteConexion() {
    Ext.Msg.wait(perfil.etiquetas.lbMsgdeletMsg, perfil.etiquetas.lbMsgdeletTitle);
    Ext.Ajax.request({
        url: 'eliminarConexion',
        method: 'POST',
        params: {
            rolname: sm.getSelected().data.rolname,
            idservidor: idservidor,
            idgestor: idgestor
        },
        callback: function(options, success, response) {
            responseData = Ext.decode(response.responseText);
            if (responseData.codMsg == 1)
            {
                Ext.Msg.hide();
                stGpBd.reload();
                sm.clearSelections();
                checked = false;
                checkRolConexiones.setValue(false);
                checked = true;
                checkRolConexiones.disable();
                GpBd.getView().refresh();
                //mostrarMensaje(responseData.codMsg, perfil.etiquetas.lbMsgInfConexI);
            } else
            if (responseData.codMsg == 3) {
                Ext.Msg.hide();
                //mostrarMensaje(responseData.codMsg, responseData.mensaje);
                checked = false;
                checkRolConexiones.setValue(true);
                checked = true;
            }

        }
    });
}

function adicionarrolbasedatos(apl)
{
    if (regBd.getForm().isValid())
    {
        if (Ext.getCmp('contrasena').getValue() != "") {
            if (Ext.getCmp('contrasena').getValue() == Ext.getCmp('newcontrasena').getValue())
            {
                regBd.getForm().submit({
                    url: 'insertarRolBaseDato',
                    waitMsg: perfil.etiquetas.lbMsgAdicionarMsg,
                    params: {
                        user: usuario,
                        ip: ipservidor,
                        gestor: gestorBD,
                        passw: password,
                        bd: baseDato,
                        idservidor: idservidor,
                        idgestor: idgestor
                    },
                    failure: function(form, action) {
                        if (action.result.codMsg != 3)
                        {
                            // mostrarMensaje(action.result.codMsg, perfil.etiquetas.lbMsgInfAdd);
                            regBd.getForm().reset();
                            if (!apl)
                                winIns.hide();
                            stGpBd.reload();
                            sm.clearSelections();
                            btnModificar.disable();
                            btnEliminar.disable();
                        }
                        if (action.result.codMsg == 3)
                            // mostrarMensaje(action.result.codMsg, action.result.mensaje);
                            Ext.getCmp('contrasena').setValue("");
                        Ext.getCmp('newcontrasena').setValue("");
                    }
                });
            }
            else
                mostrarMensaje(3, perfil.etiquetas.lbTitMsgContrasenaIncorrecta);
        } else {
            mostrarMensaje(3, perfil.etiquetas.lbTitMsgContrasenaBlank);
        }
    }
    else
        mostrarMensaje(3, perfil.etiquetas.lbMsgErrorEnCamops);


}

////------------ Modificar Base de Datos ------------////
function modificarrolbd() {
    var rolnamem = Ext.getCmp('rolname').getValue();
    var contrasenam = Ext.getCmp('contrasena').getValue();
    var fechainiciom = Ext.getCmp('fechainicio').getValue();
    var horaaam = Ext.getCmp('horaaa').getValue();
    var rolinheritm = Ext.getCmp('rolinherit').getValue();
    var rolsuperm = Ext.getCmp('rolsuper').getValue();
    var rolcreatedbm = Ext.getCmp('rolcreatedb').getValue();
    var rolcreaterolem = Ext.getCmp('rolcreaterole').getValue();
    var rolcatupdatem = Ext.getCmp('rolcatupdate').getValue();
    if (Ext.getCmp('contrasena').getValue() != "") {
        if (rolname != rolnamem || contrasena != contrasenam || fechainicio != fechainiciom
                || horaaa != horaaam || rolinherit != rolinheritm || rolsuper != rolsuperm ||
                rolcreatedb != rolcreatedbm || rolcreaterole != rolcreaterolem || rolcatupdate != rolcatupdatem) {
            if (regBd.getForm().isValid()) {
                if (Ext.getCmp('contrasena').getValue() == Ext.getCmp('newcontrasena').getValue()) {
                    var record = sm.getSelected();
                    var estado = record.get('estado')
                    regBd.getForm().submit({
                        url: 'modificarRolBaseDato',
                        waitMsg: perfil.etiquetas.lbMsgModificarMsg,
                        params: {
                            user: usuario,
                            ip: ipservidor,
                            gestor: gestorBD,
                            passw: password,
                            bd: baseDato,
                            oid: sm.getSelected().data.oid,
                            idservidor: idservidor,
                            idgestor: idgestor,
                            estado: estado
                        },
                        failure: function(form, action) {
                            if (action.result.codMsg != 3) {
                                //mostrarMensaje(action.result.codMsg, perfil.etiquetas.lbMsgInfMod);
                                stGpBd.reload();
                                winMod.hide();
                            } else
//                            if (action.result.codMsg == 3) {
//                                mostrarMensaje(action.result.codMsg, action.result.mensaje);
//
//                            }

                                Ext.getCmp('contrasena').setValue("");
                            Ext.getCmp('newcontrasena').setValue("");


                        }
                    });
                }
                else
                    mostrarMensaje(3, perfil.etiquetas.lbTitMsgContrasenaIncorrecta);
            }
            else
                mostrarMensaje(3, perfil.etiquetas.lbMsgErrorEnCamops);
        }
        else {
            mostrarMensaje(3, perfil.etiquetas.NoModify);
        }
    } else {
        mostrarMensaje(3, perfil.etiquetas.lbTitMsgContrasenaBlank);
    }

}
////------------ Eliminar Base de Datos ------------////

function eliminarbd() {
    mostrarMensaje(2, perfil.etiquetas.lbMsgFunEliminar, elimina);
    function elimina(btnPresionado)
    {
        if (btnPresionado == 'ok')
        {
            Ext.Ajax.request({
                url: 'eliminarRolesDB',
                method: 'POST',
                params: {
                    user: usuario,
                    ip: ipservidor,
                    gestor: gestorBD,
                    passw: password,
                    bd: baseDato,
                    oid: sm.getSelected().data.oid,
                    rolname: sm.getSelected().data.rolname,
                    idservidor: idservidor,
                    idgestor: idgestor
                },
                callback: function(options, success, response) {
                    responseData = Ext.decode(response.responseText);
                    if (responseData.codMsg == 1)
                    {
                        checkRolConexiones.setValue(false);
                        //mostrarMensaje(responseData.codMsg, perfil.etiquetas.lbMsgInfDel);
                        stGpBd.reload();
                        sm.clearSelections();
                        btnEliminar.disable();
                    }
                    if (responseData.codMsg == 3) {                    
                     checkRolConexiones.setValue(false);
                    //mostrarMensaje(responseData.codMsg, responseData.mensaje);
                    }
                    if (responseData.error == 3) {
                            var Msg = responseData.objetos;
                            mostrarMensaje(3, responseData.mensaje + Msg);
                            checkRolConexiones.setValue(false);
                        } 

                }
            });
        }
    }
}
////------------ Buscar Base de Datos ------------////
function buscarrol(rol) {
    stGpBd.load({params: {nombreRol: rol, user: usuario, ip: ipservidor, gestor: gestorBD, passw: password, bd: baseDato, start: 0, limit: 15}});
}

function buscarEsquemas(esqSelected) {
    storegrid.load({params: {esqSelected: esqSelected, nombreRol: rol, user: usuario, ip: ipservidor, gestor: gestorBD, passw: password, bd: baseDato, start: 0, limit: 15}});
}

function CampiarEstadoFilaColumna(fila, columna) {
    if (IsPosicionModificado(fila, columna))
        pop(fila, columna);
    else
        push(fila, columna)
}

function push(fila, columna) {
    FilasColumnasModificadas[fila][columna] = 1;
}
function pop(fila, columna) {
    FilasColumnasModificadas[fila][columna] = 0;
}
function IsPosicionModificado(fila, columna) {
    if (FilasColumnasModificadas[fila][columna] == 1)
        return true;
    return false;
}

function IsModificados() {
    for (var i = 0; i < FilasColumnasModificadas.length; i++) {
        for (var j = 0; j < FilasColumnasModificadas[i].length; j++) {
            if (FilasColumnasModificadas[i][j] == 1)
                return true;
        }
    }
    return false;
}

function ReiniciarFilasColumnas() {
    FilasColumnasModificadas = new Array();
    var TotalColumn = newcm.getColumnCount();
    var TotalFila = storegrid.getCount();
    var length = TotalColumn - 1;
    for (var i = 0; i < TotalFila; i++) {
        var columnas = new Array();
        for (var j = 0; j < length; j++) {
            columnas.push(0);
        }
        FilasColumnasModificadas.push(columnas);
    }
}
Ext.Ajax.on('requestcomplete', function(conn, response, options) {
    var respText = response.responseText;
    if (respText && respText != "<script type=\"text/javascript\" src=\"../../views/js/gestrolesbd/pgsql.js\"></script>") {
        var respObj = Ext.decode(respText);
        if (respObj.codMsg && respObj.codMsg >= 1 && respObj.codMsg <= 4) {
            // mostrarMensaje(respObj.codMsg, respObj.mensaje);
        }

    }
});
////---------------Asignar los permisos para la base de datos-----------------------////
function asignar() {
    var filasModifcadas = storegrid.getModifiedRecords();
    var cantFilas = filasModifcadas.length;
    var cmHis = gdGestionHis.getColumnModel();
    var cantCol = cmHis.getColumnCount();
    var arrayAcceso = [];
    var arrayDenegado = [];
    if (IsModificados()) {
        for (var i = 0; i < cantFilas; i++) {
            var nameFila = filasModifcadas[i].data.name;
            var colsFila = filasModifcadas[i].getChanges();
            var indexOf = storegrid.indexOf(filasModifcadas[i]);
            var arrayColAut = [];
            var arrayColDen = [];
            for (var j = 1; j <= cantCol; j++) {
                if (FilasColumnasModificadas[indexOf][j - 1] == 1) {
                    nameCampo = camposGridDinamico[j];
                    var cadEval = 'colsFila.' + nameCampo;
                    var valCol = eval(cadEval);
                    if (valCol == true)
                        arrayColAut.push(nameCampo);
                    else if (valCol == false)
                        arrayColDen.push(nameCampo);
                }
            }
            if (arrayColAut.length)
                arrayAcceso.push([nameFila, arrayColAut]);
            if (arrayColDen.length)
                arrayDenegado.push([nameFila, arrayColDen]);
        }
        jsonAcceso = Ext.encode(arrayAcceso);
        jsonDenegado = Ext.encode(arrayDenegado);


        Ext.Ajax.request({
            url: 'modificarPermisos',
            method: 'POST',
            params: {
                acceso: jsonAcceso,
                denegado: jsonDenegado,
                nombreRol: rol,
                user: usuario,
                ip: ipservidor,
                gestor: gestorBD,
                passw: password,
                bd: baseDato,
                criterio: criterioSel,
                usuariobd: sm.getSelected().data.rolname
            },
            callback: function(options, success, response) {
                responseData = Ext.decode(response.responseText);
                if (responseData.codMsg == 1) {
                    //mostrarMensaje(responseData.codMsg, perfil.etiquetas.lbMsgInfPermiso);

                }
                if (responseData.codMsg == 3)
                    // mostrarMensaje(responseData.codMsg, responseData.mensaje);
                    storegrid.reload();
                storegrid.rejectChanges();
            }
        });
        ReiniciarFilasColumnas();
    } else {
        mostrarMensaje(3, perfil.etiquetas.NoModifyGrid);
    }
    InicialFalses = new Array();

}   