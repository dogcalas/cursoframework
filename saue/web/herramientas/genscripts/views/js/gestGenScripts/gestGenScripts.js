var perfil = window.parent.UCID.portal.perfil;
UCID.portal.cargarEtiquetas('gestgenscripts', function() {
    conexion();
});

////------------ Inicializo el singlenton QuickTips ------------////
Ext.QuickTips.init();

var winIns, winMod, regExce, path, stGpExcepciones, criteriobusqueda, valor, nodoSel, nodoSeleccionado, tipoScript, arrayN, nodoMio, padre, error;
var modificar = false;
var grid_cargado = false;
var arregloDeschequeados = [], arregloChequeados = [], seleccionados = [];

function cargarInterfaz() {
    tfldBuscExcepcion = new Ext.form.TextField({
        emptyText: 'Valor...',
        id: 'valorBuscExcep',
        width: 120,
        anchor: '100%',
        maxLength: 22,
        listeners: {
            specialkey: function(f, e) {
                if (e.getKey() === e.ENTER && nodoSeleccionado !== null) {
                    buscador();
                }
                else {
                    tfldBuscExcepcion.reset();
                }
            }
        }
    });

    searchCriteriaCombo = new Ext.form.ComboBox({
        xtype: 'combo',
        store: new Ext.data.SimpleStore({
            fields: ['searchCriteria'],
            data: [['Ninguno'], ['Nombre'], ['Código'], ['Mensaje'], ['Descripción']]}
        ),
        name: 'searchCriteria',
        id: 'searchCriteria',
        editable: false,
        emptyText: 'Criterio',
        triggerAction: 'all',
        forceSelection: true,
        allowBlank: false,
        displayField: 'searchCriteria',
        mode: 'local',
        width: 90
    });

    searchCriteriaCombo.on('select', function() {
        if (Ext.getCmp('searchCriteria').getValue() === 'Ninguno')
            tfldBuscExcepcion.reset();
    });

    btnAdicionar = new Ext.Button({
        disabled: true,
        icon: perfil.dirImg + 'adicionar.png',
        iconCls: 'btn',
        text: 'Adicionar',
        handler: function() {
            winForm('Ins');
        }
    });
    btnModificar = new Ext.Button({
        disabled: true,
        icon: perfil.dirImg + 'modificar.png',
        iconCls: 'btn',
        text: 'Modificar',
        handler: function() {
            winForm('Mod');
        }
    });
    btnEliminar = new Ext.Button({
        disabled: true,
        icon: perfil.dirImg + 'eliminar.png',
        iconCls: 'btn',
        text: 'Eliminar',
        handler: function() {
            eliminarpaquete();
        }
    });
    btnGenerar = new Ext.Button({
        disabled: true,
        icon: perfil.dirImg + 'generardocumentos.png',
        iconCls: 'btn',
        text: 'Generar script',
        handler: function() {
            generarscript();
        }
    });

    btnDescargar = new Ext.Button({
        disabled: true,
        icon: perfil.dirImg + 'descargar.png',
        iconCls: 'btn',
        text: 'Descargar script',
        handler: function() {
            descargar();
        }
    });

    UCID.portal.cargarAcciones(window.parent.idexcepcion);

    btnGenerar.on('click', function() {
        btnDescargar.enable();
    });

    arbolSistema = new Ext.tree.TreePanel({
        title: 'Base de datos: ' + Ext.getCmp('db').getValue() + '<br>' + 'Host: ' + Ext.getCmp('host').getValue(),
        collapsible: true,
        animate: true,
        autoScroll: true,
        region: 'west',
        split: true,
        width: '150',
        loader: new Ext.tree.TreeLoader({
            dataUrl: 'cargarpaquetes',
            listeners: {
                'beforeload': function(atreeloader, anode) {
                    atreeloader.baseParams = {};
                    //atreeloader.baseParams.path = anode.attributes.path;
                }}
        })
    });

    padreSistema = new Ext.tree.AsyncTreeNode({
        text: 'Paquetes de scripts',
        animate: true,
        draggable: false,
        expandable: false,
        expanded: true,
        id: '0'
    });

    arbolSistema.setRootNode(padreSistema);

    arbolSistema.on('click', function(node, e) {
        sistemaseleccionado = node;
        sistemas = node;
        bandera = 0;
        storegridObjetos.removeAll();
        if (node.id == 0) {
            btnAdicionar.enable();
            btnModificar.disable();
            btnEliminar.disable();
            btnGenerar.disable();
            gdGestionHis.disable();
            padreArbolBd.collapse();
            arbolBd.disable();
        }
        else if (node.id !== 0 && node.isLeaf()) {
            idsistema = node.id;
            nodoSeleccionado = node;
            btnAdicionar.disable();
            btnModificar.disable();
            btnEliminar.disable();
            btnGenerar.disable();
            gdGestionHis.disable();
            gdGestionHis.disable();
            path = node.attributes.path;
            nodo_padre = node.parentNode;
            arbolBd.enable();
            tipoScript = node.text;
            padreArbolBd.reload();
            padreArbolBd.expand();
            arregloChequeados = [];
        }
        else if (node.id !== 0 && !node.isLeaf()) {
            btnAdicionar.disable();
            btnModificar.enable();
            btnEliminar.enable();
            btnGenerar.disable();
            gdGestionHis.disable();
            padreArbolBd.collapse();
            arbolBd.disable();

        }
    }, this);

    arbolBd = new Ext.tree.TreePanel({
        title: 'Elementos de la base de datos',
        collapsible: false,
        disabled: true,
        animate: true,
        autoScroll: true,
        region: 'center',
        split: false,
        width: '1',
        style: 'z-index:0',
        loader: new Ext.tree.TreeLoader({
            dataUrl: 'cargarbd',
            listeners: {
                'beforeload': function(atreeloader, anode) {
                    atreeloader.baseParams = {};
                    atreeloader.baseParams.texto = tipoScript;
                }}
        })
    });

    padreArbolBd = new Ext.tree.AsyncTreeNode({
        text: 'Esquemas',
        animate: true,
        draggable: false,
        expandable: false,
        expanded: false,
        id: '0'
    });
    arbolBd.setRootNode(padreArbolBd);

    arbolBd.on('load', function(node, e) {
        gdGestionHis.disable();
        arrayN = arbolBd.getChecked();
        parent = node.parentNode;
        if (tipoScript === 'Datos') {
            if (parent !== null) {
                nodoMio = node;
                padre = parent;
            }
        }
        else if (tipoScript === 'Estructura' || tipoScript === 'Permisos') {
            if (parent !== null) {
                parent = parent.parentNode;
                if (parent !== null) {
                    nodoMio = node;
                    padre = parent;
                }
            }
        }

    }, this);

    arbolBd.on('checkchange', function(node, e) {

        if (node.attributes.checked) {
            var estaenDeschequeados = estaEnArreglo(arregloDeschequeados,
                    node.parentNode.attributes.id, node.attributes.id);

            if (estaenDeschequeados !== -1)
                eliminarDeArreglo(arregloDeschequeados, estaenDeschequeados);

            adicionarEnArreglo(arregloChequeados,
                    node.parentNode.attributes.id, node.attributes.id);
        }
        else {
            var estaenChequeados = estaEnArreglo(arregloChequeados,
                    node.parentNode.attributes.id, node.attributes.id);

            if (estaenChequeados !== -1)
                eliminarDeArreglo(arregloChequeados, estaenChequeados);

            adicionarEnArreglo(arregloDeschequeados,
                    node.parentNode.attributes.id, node.attributes.id);
        }
        if (arregloChequeados[0] === undefined) {
            btnGenerar.disable();
        }
        else {
            if (tipoScript !== 'Datos')
                btnGenerar.enable();
        }

        val = null;
        for (var i = 0; i < arregloChequeados.length; i++) {
            if (arregloChequeados[i][0] === node.parentNode.id)
                val = i;
        }
        if (val !== null && arregloChequeados[val][1].length === node.parentNode.childNodes.length) {
            //btnSelTodo.disable();
        }

    }, this);

    arbolBd.on('click', function(node, e) {
        sistemaseleccionado = node;
        sistemas = node;
        bandera = 0;
        if (node.id !== 0 && node.isLeaf()) {
            idsistema = node.id;
            btnAdicionar.disable();
            btnModificar.disable();
            btnEliminar.disable();
            nodo_padre = node.parentNode;
            nodoSeleccionado = node;
            if (tipoScript === 'Datos') {
                gdGestionHis.enable();
                Ext.Ajax.request({
                    url: 'configridObjetos',
                    method: 'POST',
                    params: {tabla: nodoSeleccionado.id},
                    callback: function(options, success, response) {
                        responseData = Ext.decode(response.responseText);
                        camposGridDinamico = responseData.grid.campos;
                        var i = 0;

                        while (i < responseData.grid.columns.length) {
                            var aux = responseData.grid.columns[i];
                            responseData.grid.columns[i].editor = new Ext.form.Checkbox({checked: false});
                            i++;
                        }
                        newcm = generaDinamico('cm', responseData.grid.columns);

                        storegridObjetos = new Ext.data.Store({
                            url: 'cargargridObjetos',
                            paramNames: {tabla: nodoSeleccionado.id},
                            listeners: {
                                'beforeload': function() {
                                    gdGestionHis.getSelectionModel().selectFirstRow();
                                    grid_cargado = true;
                                }
                            },
                            pruneModifiedRecords: true,
                            reader: new Ext.data.JsonReader({
                                totalProperty: 'cantidad',
                                root: 'datos',
                                id: 'iddatos',
                                messageProperty: 'mensaje'
                            }, generaDinamico('rdcampos', responseData.grid.campos)
                                    )

                        });


                        var menu = new Ext.menu.Menu({
                            id: 'submenu',
                            items: [{
                                    text: perfil.etiquetas.menuTextSelectAllFile,
                                    scope: this,
                                    icon: "../../../../images/icons/añadir.png",
                                    handler: function() {
                                        var j = 0;
                                        while (j < newcm.getColumnCount()) {
                                            storegridObjetos.getAt(fila).set(newcm.getColumnHeader(j), true);
                                            var record = storegridObjetos.getAt(fila);
                                            j++;
                                        }

                                    }
                                },
                                {
                                    text: perfil.etiquetas.menuTextSelectAllColumn,
                                    scope: this,
                                    icon: "../../../../images/icons/añadir.png",
                                    handler: function() {
                                        for (var i = 0; i < storegridObjetos.getCount(); i++) {
                                            var record = storegridObjetos.getAt(fila);
                                            storegridObjetos.getAt(i).set(newcm.getColumnHeader(col), true);
                                        }
                                    }
                                },
                                {
                                    text: perfil.etiquetas.menuTextUnCheckAllFile,
                                    scope: this,
                                    icon: "../../../../images/icons/eliminar.png",
                                    handler: function() {
                                        var j = 0;
                                        while (j < newcm.getColumnCount()) {
                                            storegridObjetos.getAt(fila).set(newcm.getColumnHeader(j), false);
                                            var record = storegridObjetos.getAt(fila);
                                            j++;
                                        }

                                    }
                                },
                                {
                                    text: perfil.etiquetas.menuTextUnCheckAllColumn,
                                    scope: this,
                                    icon: "../../../../images/icons/eliminar.png",
                                    handler: function() {
                                        for (var i = 0; i < storegridObjetos.getCount(); i++) {
                                            storegridObjetos.getAt(i).set(newcm.getColumnHeader(col), false);
                                            var record = storegridObjetos.getAt(fila);
                                        }
                                    }
                                }]
                        });

                        gdGestionHis.on('cellcontextmenu', function(_this, rowIndex, cellIndex, e) {
                            fila = rowIndex;
                            col = cellIndex;
                            e.stopEvent();
                            menu.showAt(e.getXY());
                        }, this);


                        if (newcm && storegridObjetos) {
                            gdGestionHis.reconfigure(storegridObjetos, newcm);
                            gdGestionHis.getBottomToolbar().bind(storegridObjetos);
                            storegridObjetos.load({
                                params: {
                                    tabla: nodoSeleccionado.id
                                }
                            });
                            storegridObjetos.on('load', function(s) {

                            });
                        }
                    }
                });
            }
            nodoSel = node;
            searchCriteriaCombo.reset();
            path = node.attributes.path;
        }
        else if (node.id !== 0 && !node.isLeaf()) {
            btnAdicionar.disable();
            btnModificar.disable();
            btnEliminar.disable();

        }
        else {
            btnAdicionar.disable();
            btnModificar.disable();
            btnEliminar.disable();
        }
    }, this);

//****************************GRID DE LAS TABLAS********************************
    storegridObjetos = new Ext.data.Store({
        url: '',
        reader: new Ext.data.JsonReader({
            totalProperty: "totalProperty",
            root: "root",
            messageProperty: "mensaje"
        }, [{
                name: 'vacio'
            }])
    });

    cmGestionhist = new Ext.grid.ColumnModel([{id: 'expandir', autoExpandColumn: 'expandir'}]);

    smgestion = new Ext.grid.RowSelectionModel({
        singleSelect: false,
        width: 25,
        checkOnly: false,
        scope: this
    });

    gdGestionHis = new Ext.grid.GridPanel({
        frame: true,
        disabled: true,
        region: 'east',
        height: '50%',
        width: '50%',
        style: 'z-index:0',
        sm: smgestion,
        clicksToEdit: 10,
        store: storegridObjetos,
        visible: true,
        loadMask: {msg: "Cargando"},
        cm: cmGestionhist,
        bbar: new Ext.PagingToolbar({
            pageSize: 100,
            store: storegridObjetos,
            displayInfo: true,
            displayMsg: perfil.etiquetas.lbTitMsgNingunresultadoparamostrar,
            emptyMsg: perfil.etiquetas.lbTitMsgResultados,
            hidden: true
        }),
        columns: [smgestion],
        listeners: {
            'cellclick': function(grid, rowIndex, columnIndex, e) {
                var record = grid.getStore().getAt(rowIndex);  // Get the Record
                var fieldName = grid.getColumnModel().getDataIndex(columnIndex); // Get field name
                var data = record.get(fieldName);
                storegridObjetos.getAt(rowIndex).set(newcm.getColumnHeader(columnIndex), !data);
                var own = "OWN";
                var i = 11;
                if (fieldName !== own) {
                    data = record.get(own);
                    if (data) {
                        storegridObjetos.getAt(rowIndex).set(newcm.getColumnHeader(i), false);
                        CampiarEstadoFilaColumna(rowIndex, i - 11, record);
                    }
                }
            }
        }
    });

    smgestion.on('rowselect', function() {

        if (grid_cargado === true) {
            para_add = gdGestionHis.getSelectionModel().getSelections();
            for (var h = 0; h < para_add.length; h++) {
                var add = false;
                if (estaEnArreglo(seleccionados, nodoSel.id, para_add[h].get('primary_key')) === -1) {
                    for (var i = 0; i < seleccionados.length; i++) {
                        if (seleccionados[i][0] === nodoSel.id) {
                            seleccionados[i][1].push(para_add[h].get('primary_key'));
                            add = true;
                        }
                    }
                    if (!add) {
                        seleccionados.push([nodoSel.id]);
                        seleccionados[seleccionados.length - 1].push([para_add[h].get('primary_key')]);
                    }
                }
            }
        }

        if (seleccionados[0] === undefined)
            btnGenerar.disable();
        else
            btnGenerar.enable();
        selection = gdGestionHis.getSelectionModel().getSelections();

    }, this);

    smgestion.on('rowdeselect', function() {

        if (grid_cargado === true) {
            para_add = gdGestionHis.getSelectionModel().getSelections();

            for (var x = 0; x < seleccionados.length; x++) {
                if (seleccionados[x][0] === nodoSel.id) {
                    for (var y = 0; y < seleccionados[x][1].length; y++) {
                        var eliminar = true;
                        for (var z = 0; z < para_add.length; z++) {
                            if (seleccionados[x][1][y] === para_add[z].get('primary_key')) {
                                eliminar = false;
                            }
                        }
                        if (eliminar === true) {
                            seleccionados[x][1].splice(y, 1);
                            if (seleccionados[x].length === 2 && seleccionados[x][1].length === 0) {
                                seleccionados.splice(x, 1);
                                break;
                            }
                        }
                    }
                }
            }
        }

        if (seleccionados[0] === undefined)
            btnGenerar.disable();
        else
            btnGenerar.enable();
        selection = gdGestionHis.getSelectionModel().getSelections();
    }, this);



//********************************FIN DEL GRID**********************************
    regExce = new Ext.FormPanel({
        labelAlign: 'top',
        bodyStyle: 'padding:5px 5px 0',
        frame: true,
        items: [{
                layout: 'column',
                items: [{
                        columnWidth: .7,
                        layout: 'form',
                        items: [{
                                xtype: 'textfield',
                                fieldLabel: 'Nombre  del paquete',
                                name: 'nombre',
                                anchor: '90%',
                                allowBlank: false,
                                regex: /^([a-zA-Z]+)+$/,
                                maskRe: /^([a-zA-Z]+)+$/,
                                blankText: perfil.etiquetas.lbMsgCampoObligatorio,
                                regexText: perfil.etiquetas.lbMsgValorIncorrecto
                            }, {
                                xtype: 'textfield',
                                fieldLabel: 'Nombre  del sistema',
                                name: 'nombreS',
                                anchor: '90%',
                                allowBlank: false,
                                regex: /^([a-zA-Z]+)+$/,
                                maskRe: /^([a-zA-Z]+)+$/,
                                blankText: perfil.etiquetas.lbMsgCampoObligatorio,
                                regexText: perfil.etiquetas.lbMsgValorIncorrecto
                            }, {
                                xtype: 'textfield',
                                fieldLabel: 'Abreviatura',
                                name: 'abrev',
                                anchor: '40%',
                                allowBlank: false,
                                regex: /^([a-zA-Z]+)+$/,
                                maskRe: /^([a-zA-Z]+)+$/,
                                blankText: perfil.etiquetas.lbMsgCampoObligatorio,
                                regexText: perfil.etiquetas.lbMsgValorIncorrecto
                            }]
                    }, {
                        columnWidth: .3,
                        layout: 'form',
                        items: [{
                                xtype: 'textfield',
                                fieldLabel: 'Versi&oacute;n del sistema',
                                name: 'versionSis',
                                anchor: '100%',
                                allowBlank: false,
                                maxLength: 22,
                                blankText: perfil.etiquetas.lbMsgCampoObligatorio,
                                regex: /^([0-9]+\.?[0-9]+)+$/,
                                maskRe: /^[0-90-9.]*$/,
                                regexText: perfil.etiquetas.lbMsgValorIncorrecto
                            }]
                    }, {
                        columnWidth: .3,
                        layout: 'form',
                        items: [{
                                xtype: 'textfield',
                                fieldLabel: 'Versi&oacute;n del script',
                                name: 'versionSc',
                                anchor: '100%',
                                allowBlank: false,
                                maxLength: 22,
                                blankText: perfil.etiquetas.lbMsgCampoObligatorio,
                                regex: /^([0-9]+\.?[0-9]+)+$/,
                                maskRe: /^[0-90-9.]*$/,
                                regexText: perfil.etiquetas.lbMsgValorIncorrecto
                            }]
                    },
                    {
                        columnWidth: 1,
                        layout: 'form',
                        items: [{
                                xtype: 'textarea',
                                fieldLabel: 'Descripci&oacute;n',
                                name: 'descripcion',
                                anchor: '100%',
                                maxLength: 200,
                                allowBlank: true
                            }]
                    }]
            }]
    });

    store = new Ext.data.Store({
        layout: 'fit',
        reader: new Ext.data.ArrayReader({
            idIndex: 0
        })
    });


    panel = new Ext.Panel({
        layout: 'border',
        items: [arbolSistema, arbolBd, gdGestionHis],
        tbar: [btnAdicionar, btnModificar, btnEliminar, btnGenerar, btnDescargar]
    });

    vpGestValid = new Ext.Viewport({
        layout: 'fit',
        items: panel
    });

    function estaEnArreglo(arreglonodos, idsistema, idfuncionalidad) {

        for (var i = 0; i < arreglonodos.length; i++) {
            if (arreglonodos[i][0] === idsistema) {
                for (var j = 0; j < arreglonodos[i][1].length; j++) {
                    if (arreglonodos[i][1][j] === idfuncionalidad) {
                        return i + "-" + j;
                    }
                }
            }
        }

        return -1;
    }

    function eliminarDeArreglo(arreglo, pos) {
        var posminus = pos.indexOf('-');
        var i = pos.substring(0, posminus);
        var j = pos.substring(posminus + 1, pos.length);
        arreglo[i][1].splice(j, 1);
        if (arreglo[i].length === 2 && arreglo[i][1].length === 0) {
            arreglo.splice(i, 1);
        }
    }

    function adicionarEnArreglo(arreglo, idsistema, idfuncionalidad) {
        var add = false;
        if (estaEnArreglo(arreglo, idsistema, idfuncionalidad) === -1) {
            for (var i = 0; i < arreglo.length; i++) {
                if (arreglo[i][0] === idsistema) {
                    arreglo[i][1].push(idfuncionalidad);
                    add = true;
                }
            }
            if (!add) {
                arreglo.push([idsistema]);
                arreglo[arreglo.length - 1].push([idfuncionalidad]);
            }
        }


    }

    function dameNodeInfo() {
        var record = Ext.data.Record.create([
            {name: 'denominacion', mapping: 'denominacion'}
        ]);
        return new record({
            nombre: arbolSistema.getSelectionModel().getSelectedNode().text,
            versionSc: arbolSistema.getSelectionModel().getSelectedNode().id.replace(arbolSistema.getSelectionModel().getSelectedNode().text, ""),
            nombreS: arbolSistema.getSelectionModel().getSelectedNode().nombreS,
            abrev: arbolSistema.getSelectionModel().getSelectedNode().abrev,
            descripcion: "***descripcion***"
        });
    }

    ////------------ Cargar la ventana ------------////
    function winForm(opcion) {
        switch (opcion) {
            case 'Ins':
                {
                    if (!winIns)
                    {
                        winIns = new Ext.Window({modal: true, closeAction: 'hide', resizable: false, layout: 'fit',
                            title: 'Adicionar paquete', width: 450, height: 350,
                            buttons: [
                                {
                                    icon: perfil.dirImg + 'cancelar.png',
                                    iconCls: 'btn',
                                    text: perfil.etiquetas.lbBtnCancelar,
                                    handler: function() {
                                        winIns.hide();
                                    }
                                },
                                {
                                    icon: perfil.dirImg + 'aplicar.png',
                                    iconCls: 'btn',
                                    text: perfil.etiquetas.lbBtnAplicar,
                                    handler: function() {
                                        adicionarPaquete(true);
                                    }
                                },
                                {
                                    icon: perfil.dirImg + 'aceptar.png',
                                    iconCls: 'btn',
                                    text: perfil.etiquetas.lbBtnAceptar,
                                    handler: function() {
                                        adicionarPaquete(false);
                                    }
                                }]
                        });
                    }
                    regExce.getForm().reset();
                    arbolSistema.getRootNode().reload();
                    winIns.add(regExce);
                    winIns.doLayout();
                    winIns.show();
                }
                break;
            case 'Mod':
                {
                    if (!winMod)
                    {
                        winMod = new Ext.Window({modal: true, closeAction: 'hide', layout: 'fit',
                            title: 'Modificar paquete', resizable: false, width: 450, height: 350,
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
                                        modificarpaquete();
                                    }
                                }]
                        });

                    }
                    winMod.add(regExce);
                    winMod.doLayout();
                    winMod.show();
                    regExce.getForm().loadRecord(dameNodeInfo());
                }
                break;
        }
    }
}
//Boton Principal Adicionar Excepcion
function adicionarPaquete(apl) {

    if (regExce.getForm().isValid()) {
        regExce.getForm().submit({
            url: 'adicionarpaquete',
//            params: {sistema: path},
            waitMsg: 'Registrando el paquete ...',
            failure: function(form, action) {
                if (action.result.codMsg !== 3) {
                    mostrarMensaje(action.result.codMsg, action.result.mensaje);
                    regExce.getForm().reset();
                    if (!apl)
                        winIns.hide();
                    btnModificar.disable();
                    btnEliminar.disable();
                    padreSistema.reload();
                }
                if (action.result.codMsg === 3)
                    mostrarMensaje(action.result.codMsg, action.result.mensaje);
            }
        });
    }
    else
        mostrarMensaje(3, perfil.etiquetas.lbMsgErrorEnCamops);
}

function modificarpaquete() {

    if (regExce.getForm().isValid()) {
        regExce.getForm().submit({
            url: 'modificarPaquete',
            waitMsg: 'Modificando el paquete...',
            params: {nombre_paquete: arbolSistema.getSelectionModel().getSelectedNode().text,
                id_paquete: arbolSistema.getSelectionModel().getSelectedNode().id},
            failure: function(form, action) {
                if (action.result.codMsg !== 3) {
                    mostrarMensaje(action.result.codMsg, action.result.mensaje);
                    regExce.getForm().reset();
                    winMod.hide();
                    btnModificar.disable();
                    btnEliminar.disable();
                    padreSistema.reload();
                }
                if (action.result.codMsg === 3)
                    mostrarMensaje(action.result.codMsg, action.result.mensaje);
            }
        });
    }
    else
        mostrarMensaje(3, perfil.etiquetas.lbMsgErrorEnCamops);
}

function eliminarpaquete() {
    mostrarMensaje(2, '&iquest;Est&aacute; seguro de que desea eliminar el paquete?', elimina);

    function elimina(btnPresionado) {
        if (btnPresionado === 'ok') {
            Ext.Ajax.request({
                url: 'eliminarpaquete',
                waitMsg: 'Espere por favor...',
                method: 'POST',
                params: {
                    nombre_paquete: arbolSistema.getSelectionModel().getSelectedNode().text,
                    id_paquete: arbolSistema.getSelectionModel().getSelectedNode().id
                },
                callback: function(options, success, response) {
                    btnAdicionar.enable();
                    btnModificar.disable();
                    btnEliminar.disable();
                    btnGenerar.disable();
                    padreSistema.reload();
                }
            });
        }
    }
}



function generarscript() {
    mostrarMensaje(2, '&iquest;Desea generar el script de ' + tipoScript + '?', genera);
    function genera(btnPresionado) {
        if (btnPresionado === 'ok') {
            arbolSistema.disable();
            arbolBd.disable();
            gdGestionHis.disable();
            if (tipoScript === 'Datos') {
                Ext.Ajax.request({
                    url: 'generarScript',
                    method: 'POST',
                    params: {
                        paquete: arbolSistema.getSelectionModel().getSelectedNode().parentNode.text,
                        version: arbolSistema.getSelectionModel().getSelectedNode().parentNode.id,
                        tipo: tipoScript,
                        array: Ext.util.JSON.encode(seleccionados)
                    },
                    callback: function(options, succes, response) {

                    }
                });
            }
            else {
                Ext.Ajax.request({
                    url: 'generarScript',
                    method: 'POST',
                    waitMsg: 'Generando el script ...',
                    params: {
                        paquete: arbolSistema.getSelectionModel().getSelectedNode().parentNode.text,
                        version: arbolSistema.getSelectionModel().getSelectedNode().parentNode.id,
                        tipo: tipoScript,
                        array: Ext.util.JSON.encode(arregloChequeados)
                    },
                    callback: function(options, success, response) {
                    }
                });
            }
            Ext.Ajax.on('requestcomplete', function() {
                arbolSistema.enable();
                arbolBd.enable();
                gdGestionHis.enable();
            }, this);
            Ext.Ajax.on('requestexception', function() {
                mostrarMensaje(3, 'Error al generar el script de ' + tipoScript + '.');
                arbolSistema.enable();
                arbolBd.enable();
                gdGestionHis.enable();
            }, this);
        }
    }
}

function descargar() {
    Ext.Ajax.request({
        url: 'descargar',
        params: {
            paquete: arbolSistema.getSelectionModel().getSelectedNode().parentNode.text
        },
        success: function() {
            window.open("descargar?paquete=" + arbolSistema.getSelectionModel().getSelectedNode().parentNode.text);
        }
    });
}

function confgrid(tabla, schema) {
    Ext.getBody().mask(perfil.etiquetas.confMaskMsg);
    Ext.Ajax.request({
        url: 'confgrid',
        params: {
            tabla: tabla,
            schema: schema
        },
        callback: function(options, success, response) {
            responseData = Ext.decode(response.responseText);
            if (responseData.grid) {
                var newcm = generaDinamico('cm', responseData.grid.columns);
                stGpGrid = new Ext.data.Store({
                    url: 'cargargrid',
                    listeners: {'load': function() {
                            gpGrid.getSelectionModel().selectFirstRow();
                        }
                    },
                    reader: new Ext.data.JsonReader({
                        totalProperty: 'cantidad_trazas',
                        root: 'trazas',
                        id: 'idtraza'
                    }, generaDinamico('rdcampos', responseData.grid.campos))
                });
                Ext.getBody().unmask();
                if (newcm && stGpGrid)
                {
                    gpGrid.reconfigure(stGpGrid, newcm);
                    gpGrid.getBottomToolbar().bind(stGpGrid);
                    gpGrid.loadMask = new Ext.LoadMask(Ext.getBody(), {msg: perfil.etiquetas.loadMaskMsg, store: stGpGrid});
                    cargar(stGpGrid, arbolBd.getSelectionModel().getSelectedNode(), arbolBd.getSelectionModel().getSelectedNode().id);
                }
            }
        }
    });
}

function cargar(estore, tabla, idtabla) {
    filtro = null;
    estore.baseParams = {
        idtipotraza: idtabla,
        tipotraza: tabla
    };
    estore.reload({params: {
            limit: 20,
            start: 0
        }
    });
}

/*function generaDinamico(tipo, obj_json) {
    switch (tipo) {
        case 'form':
            {
                return dameArrayItems(obj_json);
            }
            break;
        case 'grid':
            {

            }
            break;
        case 'store':
            {

            }
            break;
        case 'cm':
            {
                return dameColumnModel(obj_json);
            }
            break;
        case 'reader':
            {

            }
            break;
        case 'rdcampos':
            {
                return dameReaderCampos(obj_json);
            }
            break;
    }
    // Para gestion dinamica de un formulario.
    function dameArrayItems(objitems) {
        var arrayItems = new Array();
        for (var i = 1; i <= objitems[0].cantidad; i++) {
            arrayItems.push(dameColumn(objitems, objitems[0].cantidad, i));
        }
        return arrayItems;
    }
    function dameColumn(objitems, noCol, pos) {
        if (noCol === 1)
            return {columnWidth: 1, layout: 'form', items: dameItemForm(objitems[pos])};
        if (noCol === 2)
            return {columnWidth: .5, layout: 'form', items: dameItemForm(objitems[pos])};
        return {columnWidth: .33, layout: 'form', items: dameItemForm(objitems[pos])};
    }
    function dameItemForm(objitem) {
        if (objitem.xtype === 'numberfield')
            return {xtype: 'numberfield',
                fieldLabel: objitem.fieldLabel,
                id: objitem.id,
                maxLength: objitem.maxLength,
                name: objitem.name,
                regex: eval(objitem.regex),
                anchor: (objitem.anchor) ? objitem.anchor : '95%',
                allowBlank: (objitem.allowBlank) ? objitem.allowBlank : true};
        if (objitem.xtype === 'datefield')
            return {xtype: 'datefield',
                fieldLabel: objitem.fieldLabel,
                id: objitem.id,
                maxLength: objitem.maxLength,
                name: objitem.name,
                regex: eval(objitem.regex),
                anchor: (objitem.anchor) ? objitem.anchor : '95%',
                allowBlank: (objitem.allowBlank) ? objitem.allowBlank : true,
                readOnly: true};
        if (objitem.xtype === 'hidden')
            return {xtype: 'hidden',
                id: objitem.id,
                name: objitem.name};
        if (objitem.xtype === 'combo')
            return {xtype: 'combo',
                fieldLabel: objitem.fieldLabel,
                maxLength: objitem.maxLength,
                mode: 'local',
                displayField: 'displayField',
                triggerAction: 'all',
                hiddenName: objitem.id,
                emptyText: '[Seleccionar]',
                readOnly: true,
                anchor: (objitem.anchor) ? objitem.anchor : '95%',
                allowBlank: (objitem.allowBlank) ? objitem.allowBlank : true,
                valueField: 'valueField',
                store: new Ext.data.SimpleStore({fields: ['displayField', 'valueField'], data: objitem.data})};
        return {xtype: 'textfield',
            fieldLabel: objitem.fieldLabel,
            id: objitem.id,
            maxLength: objitem.maxLength,
            name: objitem.name,
            regex: eval(objitem.regex),
            anchor: '95%',
            allowBlank: (objitem.allowBlank) ? objitem.allowBlank : true
        };
    }
    // Para gestion dinamica de un grid.
    function dameStore(objstore) {
        return new Ext.data.Store({
            url: objstore.url,
            reader: dameReaderCampos(objstore)
        });
    }
    function dameColumnModel(objcolumns) {
        return new Ext.grid.ColumnModel(objcolumns);
    }
    function dameReader(objrd) {
        return new Ext.data.JsonReader({
            root: (objrd.rdRoot) ? objrd.rdRoot : 'aroot',
            id: (objrd.rdId) ? objrd.rdId : 'id',
            totalProperty: (objrd.rdTotRec) ? objrd.rdTotRec : 0
        }, dameReaderCampos(objrd.rdCampos)
                );
    }
    function dameReaderCampos(objrdcampos) {
        return (typeof(objrdcampos) === 'object') ? objrdcampos : [];
    }
}*/

function buscador() {
    valor = Ext.getCmp('valorBuscExcep').getValue();
    criteriobusqueda = Ext.getCmp('searchCriteria').getValue();
    btnBuscar.focus();
    stGpExcepciones.removeAll();

    if (!valor && criteriobusqueda !== 'Ninguno')
    {
        mostrarMensaje(3, perfil.etiquetas.lbMsgErrorBuscar);
    }
    else
    {
        stGpExcepciones.baseParams = {};
        stGpExcepciones.baseParams.criteriobusqueda = criteriobusqueda;
        stGpExcepciones.baseParams.idsubsistema = nodoSeleccionado.attributes.path;
        stGpExcepciones.baseParams.valor = valor;
        stGpExcepciones.load({params: {start: 0, limit: 20}});
        stGpExcepciones.baseParams = {};
        valor = false;
        criteriobusqueda = false;
    }

    return;
}



// ---------componentes del formulario---------------

var host = new Ext.form.TextField({
    xtype: 'textfield',
    fieldLabel: 'Dirección IP',
    name: 'host',
    allowBlank: false,
    maxLength: 150,
    anchor: '100%',
    id: 'host',
    regex: /^([0-9a-zA-Z \xf3\xf1\xe1\xe9\xed\xfa\xd1]+ ?[0-9a-zA-Z]*)+$/,
    maskRe: /^([0-9a-zA-Z \xf3\xf1\xe1\xe9\xed\xfa\xd1]+ ?[0-9a-zA-Z]*)+$/,
    regexText: 'Valores incorrectos'
});

var port = new Ext.form.TextField({
    xtype: 'textfield',
    fieldLabel: 'Puerto',
    name: 'port',
    allowBlank: false,
    maxLength: 150,
    blankText: 'Campo obligatorio.',
    anchor: '100%',
    id: 'port',
//        regex:/^(([1-9][0-9][0-9][0-9])|([1-9][0-9][0-9])|([1-9][0-9])|([0-9]))$/i,
    maskRe: /\d{0,4}/i,
    regexText: 'Valores incorrectos'
});

var bd = new Ext.form.TextField({
    xtype: 'textfield',
    fieldLabel: 'B.Datos',
    name: 'bd',
    allowBlank: false,
    maxLength: 150,
    anchor: '100%',
    id: 'bd'

});

var user = new Ext.form.TextField({
    xtype: 'textfield',
    fieldLabel: 'Usuario',
    name: 'user',
    allowBlank: false,
    maxLength: 150,
    anchor: '100%',
    id: 'user',
    regex: /^([0-9a-zA-Z \xf3\xf1\xe1\xe9\xed\xfa\xd1]+ ?[0-9a-zA-Z]*)+$/,
    maskRe: /^([0-9a-zA-Z \xf3\xf1\xe1\xe9\xed\xfa\xd1]+ ?[0-9a-zA-Z]*)+$/,
    regexText: 'Valores incorrectos'

});

var pass = new Ext.form.TextField({
    xtype: 'textfield',
    inputType: 'password',
    fieldLabel: 'Password',
    name: 'pass',
    allowBlank: false,
    maxLength: 150,
    anchor: '100%',
    id: 'pass'

});

var combomodo = new Ext.form.ComboBox({
    fieldLabel: 'Modo',
    store: ['simple', 'modular'],
    typeAhead: true,
    mode: 'local',
    emptyText: 'Seleccione...',
    selectOnFocus: true,
    width: 140,
    id: 'modo'
});
var combogestor = new Ext.form.ComboBox({
    fieldLabel: 'Gestor',
    store: ['pgsql', 'mysql', 'odbc'],
    typeAhead: true,
    mode: 'local',
    emptyText: 'Seleccione...',
    selectOnFocus: true,
    width: 140,
    id: 'gestor'
});

var cmpversion = new Ext.form.TextField({
    xtype: 'textfield',
    fieldLabel: 'Version',
    name: 'version',
    allowBlank: false,
    maxLength: 150,
    blankText: 'Campo obligatorio.',
    anchor: '100%',
    id: 'version'
});

// ------------------------------

function conexion() {
    // ------Declaracion del formulario----------------
    var formpanel = new Ext.form.FormPanel({
        frame: true,
        buttonAlign: 'right',
        autoHeight: true,
        autoWidth: true,
        style: 'z-index:10000',
        items: [{
                xtype: 'fieldset',
                title: 'Autenticaci&oacute;n al cluster de datos',
                layout: 'form',
                autoHeight: true,
                autoWidth: true,
                items: [
                    {
                        xtype: 'textfield',
                        fieldLabel: 'Servidor',
                        anchor: '100%',
                        width: 100,
                        id: 'host',
                        allowBlank: false,
                        regex: /^((([1][0][.])(([1-2][0-9][0-9][.])|([1-9][0-9][.])|([0-9][.]))(([1-2][0-9][0-9][.])|([1-9][0-9][.])|([0-9][.]))(([1-2][0-9][0-9])|([1-9][0-9])|([0-9])))|default|localhost|127.0.0.1|[0-9a-zA-Z \-\_\+\=\(\)\{\}\[\]\@\#\$\%\^\&\:\;\'\,\.\xf3\xf1\xe1\xe9\xed\xfa\xd1]+ ?[0-9a-zA-Z]+)$/i,
                        blankText: 'Debe ingresar el IP',
                        value: 'localhost'
                    },
                    {
                        xtype: 'numberfield',
                        fieldLabel: 'Puerto',
                        anchor: '100%',
                        allowBlank: false,
                        regex: /^(([1-9][0-9][0-9][0-9])|([1-9][0-9][0-9])|([1-9][0-9])|([0-9]))$/i,
                        maskRe: /\d/i,
                        blankText: 'Debe ingresar el puerto',
                        id: 'port',
                        value: '5432'
                    },
                    {
                        xtype: 'textfield',
                        fieldLabel: 'Usuario',
                        anchor: '100%',
                        allowBlank: false,
                        blankText: 'Debe ingresar el usuario',
                        vtype: 'alpha',
                        vtypeText: 'Usuario inválido',
                        id: 'user',
                        value: 'postgres'
                    },
                    {
                        xtype: 'textfield',
                        fieldLabel: 'Contrase&ntilde;a',
                        anchor: '100%',
                        inputType: 'password',
                        width: 100,
                        allowBlank: false,
                        blankText: 'Debe ingresar la contrase&ntilde;a',
                        id: 'passwd',
                        value: 'postgres'
                    },
                    {
                        xtype: 'textfield',
                        fieldLabel: 'Base de datos',
                        anchor: '100%',
                        allowBlank: false,
                        blankText: 'Debe ingresar el nombre de la Base de Datos',
                        id: 'db',
                        value: 'sauxe_v2.2'
                    }
                ]

            }],
        buttons: [{
                text: 'Cancelar',
                name: 'cancelar',
                icon: perfil.dirImg + 'cancelar.png',
                iconCls: 'btn',
                handler: function() {
                    win1.close();
                }
            }, {
                type: 'submit',
                text: 'Aceptar',
                name: 'conectar',
                icon: perfil.dirImg + 'aceptar.png',
                iconCls: 'btn',
                handler: function() {

                    var host = Ext.getCmp('host').getValue();
                    var user = Ext.getCmp('user').getValue();
                    var pass = Ext.getCmp('pass').getValue();

                    formpanel.getForm().submit(
                            {
                                url: 'establecerconexion',
                                method: 'POST',
                                waitMsg: 'Estableciendo conexiones...',
                                failure: function(form, action) {

                                    if (action.result.codMsg === 3) {
                                        error = true;
                                        mostrarMensaje(3, action.result.mensaje);
                                    } else {
                                        error = false;
                                        win1.close();
                                    }
                                }

                            });
                    cargarInterfaz();

                }
            }]
    });

    // construyo la ventana------------------------------------------------------
    win1 = new Ext.Window({
        frame: true,
        title: 'Autenticaci&oacute;n de servidores',
        closable: true,
        maximizable: false,
        minimizable: false,
        resizable: false,
        autoWith: true,
        autoHeight: true,
        layout: 'fit',
        //shim : false,
        animCollapse: false,
        style: 'z-index:0',
        items: [formpanel]
    });

    win1.show(this);
}
