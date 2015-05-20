var perfil = window.parent.UCID.portal.perfil;
perfil.etiquetas = Object();
UCID.portal.cargarEtiquetas('gestnomconexion', cargarInterfaz);

////------------ Inicializo el singlenton QuickTips ------------////
Ext.QuickTips.init();

function cargarInterfaz() {
    /***********************************
     ***********************************
     *Abriendo Declaracion de Variables
     ***********************************
     ***********************************
     */
    var RegExp;
    var win, opcion, valordispl, winCamb;
    RegExp = /(^([a-zA-ZáéíóúñüÑ])+([a-zA-Z0-9áéíóúñüÑ ]*))$/;
    ///Declaracion de botones**************************************
    var denom;
    var tipoSelected;
    var descripcion;
    var idconexion = 0;
    var btnAdicionar = new Ext.Button({disabled: false, id: 'btnAddConex', icon: perfil.dirImg + 'adicionar.png', iconCls: 'btn', text: perfil.etiquetas.lbBtnAdicionar, handler: function() {
            opcion = 'Ins';
            winForm();
        }});
    var btnSalvarCambios = new Ext.Button({disabled: true, id: 'btnModConex', icon: perfil.dirImg + 'modificar.png', iconCls: 'btn', text: perfil.etiquetas.lbBtnSalvar, handler: function() {
            opcion = 'Mod';
            winForm();
        }});
    var btnEliminar = new Ext.Button({disabled: true, id: 'btnElimConex', icon: perfil.dirImg + 'eliminar.png', iconCls: 'btn', text: perfil.etiquetas.lbBtnEliminar, handler: function() {
            EliminarTipoConexion();
        }});

    UCID.portal.cargarAcciones(window.parent.idFuncionalidad);

    ///Declaracion de Stores**************************************
    var smRadioButtons = Ext.create('Ext.selection.RowModel', {
        mode: 'SINGLE'
    });
    //Store del Combo de la accion adicionar

    var storeCombo = new Ext.data.Store({
        fields:
                [
                    {name: 'conex', mapping: 'conex'}
                ],
        proxy: {
            type: 'ajax',
            url: 'cargarCombo',
            actionMethods: { //Esta Linea es necesaria para el metodo de llamada POST o GET

                read: 'POST'
            },
            reader: {
                totalProperty: "cantidad",
                root: "datos",
                id: "id"
            }
        }
    });
    //Store del grid
    var storeGRB = new Ext.data.Store({
        pruneModifiedRecords: true,
        autoLoad: true,
        fields:
                [
                    {name: 'idconexion', mapping: 'idconexion'},
                    {name: 'seleccion', mapping: 'seleccion'},
                    {name: 'tipoconexion', mapping: 'tipoconexion'},
                    {name: 'tipo', mapping: 'tipo'},
                    {name: 'descripcion', mapping: 'descripcion'}
                ],
        proxy: {
            type: 'ajax',
            url: 'CargarGridTipoConexiones',
            actionMethods: { //Esta Linea es necesaria para el metodo de llamada POST o GET

                read: 'POST'
            },
            reader: {
                totalProperty: "cantidad",
                root: "datos",
                id: "id"
            }
        },
        baseParams: {start: 0, limit: 15}
    });

    //Combo Box de la accion adicionar            
    var combo = new Ext.form.ComboBox({
        fieldLabel: perfil.etiquetas.lbMsgComboAddFielLabel,
        xtype: 'combo',
        store: storeCombo,
        name: 'conex',
        labelWidth: 110,
        id: 'tipo',
        allowBlank: false,
        editable: false,
        blankText: perfil.etiquetas.lbMsgComboBlankText,
        valueField: 'conex',
        displayField: 'conex',
        mode: 'local',
        triggerAction: 'all',
        emptyText: perfil.etiquetas.lbMsgComboEmtyText,
        anchor: '100%'
    });

    //Evento del store del grid
    storeGRB.on('load', function(grid, records, successful, eOpts) {
        if (storeGRB.getCount() == 4) {
            btnAdicionar.disable();
            combo.disable();
        } else {
            btnAdicionar.enable();
            combo.enable();
        }
        storeCombo.load();
    });

    var dameNodeInfo = function(record) {
        Ext.define('Record', {
            extend: 'Ext.data.Model',
            fields: [
                {name: 'conex', type: 'string'}
            ]
        });
        var record = Ext.create('Record', {
            conex: record.data.conex
        });
        return record;
    }


    storeCombo.on('load', function(grid, records, successful, eOpts) {
        var stAux = new Ext.data.Store({
            fields:
                    [
                        {name: 'conex', mapping: 'conex'}
                    ]
        });
        for (X = 0; X < storeCombo.getCount(); X++) {
            var record1 = records[X];
            stAux.insert(i, dameNodeInfo(record1));
        }

        for (i = 0; i < storeGRB.getCount(); i++) {
            var record = storeGRB.getAt(i);
            var tipo = record.get('tipo');
            for (j = 0; j < stAux.getCount(); j++) {
                var record1 = records[j];
                var tipo1 = record1.get('conex');
                if (tipo === tipo1)
                {
                    //stAux.insert(i, dameNodeInfo(record1));
                    storeCombo.remove(record1);
                }
            }
        }

    });

    //Seleccion Model de las filas del grid
    //var smRadioButtons = new Ext.grid.RowSelectionModel({singleSelect: true});

    //Grid de las conexiones
    var gridRadioButtons = new Ext.grid.GridPanel({
        title: perfil.etiquetas.lbTitTiposConex,
        region: 'center',
        frame: true,
        width: '40%',
        clicksToEdit: 10,
        iconCls: 'icon-grid',
        margins: '2 2 2 -4',
        autoExpandColumn: 'descripcion',
        store: storeGRB,
        selModel: smRadioButtons,
        columns: [
            {hidden: true, hideable: false, dataIndex: 'idconexion'},
            {header: perfil.etiquetas.lbSeleccion, width: 70, editable: true, dataIndex: 'seleccion',
                editor: new Ext.form.Checkbox({checked: false}),
                renderer: function(value, metaData, record, rowIndex, colIndex, store, view) {
                    if (value)
                        return "<img src='../../../../images/icons/validado.png' />";
                    else
                        return "<img src='../../../../images/icons/no_validado.png' />";
                }
            },
            {flex: 1, header: perfil.etiquetas.lbCampoDenom, width: 150, dataIndex: 'tipoconexion', editable: false},
            {header: perfil.etiquetas.lbTitTiposConex, width: 150, dataIndex: 'tipo', editable: false},
            {id: 'descripcion', header: perfil.etiquetas.lbDescripcion, width: 150, dataIndex: 'descripcion', editable: false}
        ],
        loadMask: {store: storeGRB},
        bbar: new Ext.PagingToolbar({
            pageSize: 15, id: 'ptbconex', store: storeGRB, displayInfo: true,
            emptyMsg: perfil.etiquetas.lbMsgEmpty,displayMsg: perfil.etiquetas.lbDisplayMsg
        }),
        listeners: {
            'cellclick': function(grid, td, cellIndex, record, tr, rowIndex, e, eOpts) {
                if (cellIndex == 1) {
                    var length = grid.getStore().getCount();
                    var fila, data;
                    var nombrefila = record.index;
                    for (var i = 0; i < length; i++) {
                        if (i == rowIndex) {
                            fila = grid.getStore().getAt(i);
                            data = fila.get(nombrefila);
                            if (data == false)
                                storeGRB.getAt(i).set(nombrefila, true);
                        }
                        else {
                            fila = grid.getStore().getAt(i);
                            data = fila.get(nombrefila);
                            if (data == true)
                                storeGRB.getAt(i).set(nombrefila, false);
                        }
                    }

                    Salvarseleccion();
                }
            }}});
    //Evento del seleccion Model
    smRadioButtons.on('beforeselect', function(ss, record, eOpts) {
        btnSalvarCambios.enable();
        var data = record.get("seleccion");
        if (data) {
            btnEliminar.disable();
        } else {
            btnEliminar.enable();
        }

    }, this);
    //Evento del Paging Tool Bar
    Ext.getCmp('ptbconex').on('change', function() {
        smRadioButtons.select();
    }, this);
    //Formulario de la ventana adicionar
    var formAdd = new Ext.FormPanel({
        labelAlign: 'top',
        frame: true,
        bodyStyle: 'padding:5px 5px 0',
        items: [{
                layout: 'column',
                items: [{
                        columnWidth: 1,
                        layout: 'form',
                        border: 0,
                        margin: '5 5 5 5',
                        items: [{
                                xtype: 'textfield',
                                labelWidth: 110,
                                fieldLabel: perfil.etiquetas.lbCampoDenom,
                                id: 'tipoconexion',
                                name: 'tipoconexion',
                                allowBlank: false,
                                blankText: perfil.etiquetas.lbMsgBlankTextDenom,
                                regex: RegExp,
                                regexText: perfil.etiquetas.lbMsgExpRegDenom,
                                anchor: '100%'
                            }]
                    }, {
                        columnWidth: 1,
                        layout: 'form',
                        border: 0,
                        margin: '5 5 5 5',
                        items: [combo]
                    }, {
                        columnWidth: 1,
                        layout: 'form',
                        border: 0,
                        margin: '5 5 5 5',
                        items: [{
                                xtype: 'textarea',
                                fieldLabel: perfil.etiquetas.lbDescripcion,
                                id: 'descripcion',
                                name: 'descripcion',
                                labelWidth: 110,
                                anchor: '100%'
                            }]
                    }]}]});


    //Panel
    var panel = new Ext.Panel({
        layout: 'fit',
        title: perfil.etiquetas.lbPnPanelConex,
        items: [gridRadioButtons],
        tbar: [btnAdicionar, btnSalvarCambios, btnEliminar]
//		keys: new Ext.KeyMap(document,[
//                      {key:Ext.EventObject.DELETE,
//		      fn: function(){if(auxDel && auxDelete && auxDel2 && auxDel3)eliminarAccion();}},
//                      {key:"i",alt:true,fn: function(){if(auxIns && auxIns2 && auxIns3)winForm('Ins');}},
//		      {key:"b",alt:true,fn: function(){if(auxBus && auxBus3)buscaraccion(Ext.getCmp('nombreaccion').getValue());}},
//		      {key:"m",alt:true,fn: function(){if(auxMod && auxMod2 && auxMod3)winForm('Mod');}}])
    });
    //ViewPort
    var vpGestSistema = new Ext.Viewport({
        layout: 'fit',
        items: panel
    });


    function DeleteRecordsAdicionar() {
        //alert(sss);
        for (var i = 0; i < storeGRB.getCount(); i++) {
            var record = storeGRB.getAt(i);
            var tipo = record.get('tipo');
            for (var j = 0; j < storeCombo.getCount(); j++) {
                var record1 = storeCombo.getAt(j);
                var tipo1 = record1.get('conex');
                if (tipo == tipo1) {
                    storeCombo.remove(record1);
                    break;
                }
            }

        }
    }

    function DeleteRecordsModificar(value) {
        for (var i = 0; i < storeGRB.getCount(); i++) {
            var record = storeGRB.getAt(i);
            var tipo = record.get('tipo');
            for (var j = 0; j < storeCombo.getCount(); j++) {
                var record1 = storeCombo.getAt(j);
                var tipo1 = record1.get('conex');
                if (tipo == tipo1 && value != tipo1) {
                    storeCombo.remove(record1);
                    break;
                }
            }

        }
    }
    function winForm() {
        if (!win) {
            win = new Ext.Window({
                modal: true,
                closeAction: 'close',
                layout: 'fit',
                resizable: false,
                title: (opcion == 'Ins') ? perfil.etiquetas.lbTitAddConex : perfil.etiquetas.lbTitModConex,
                width: 270,
                height: 270,
                buttons: [{
                        icon: perfil.dirImg + 'cancelar.png',
                        iconCls: 'btn',
                        text: perfil.etiquetas.lbBtnCancelar,
                        handler: function() {
                            win.hide();
                        }
                    }, {
                        icon: perfil.dirImg + 'aplicar.png',
                        iconCls: 'btn',
                        id: 'btnaplicar',
                        text: perfil.etiquetas.lbBtnAplicar,
                        handler: function() {
                            adicionarConexion('apl');
                        }
                    }, {
                        icon: perfil.dirImg + 'aceptar.png',
                        iconCls: 'btn',
                        text: perfil.etiquetas.lbBtnAceptar,
                        handler: function() {
                            gestionarAccion();
                        }
                    }]
            });
            win.on('show', function() {
                auxIns = false;
                auxMod = false;
                auxDel = false;
            }, this);
            win.on('hide', function() {
                auxIns = true;
                auxMod = true;
                auxDel = true;
            }, this);
        }
        win.add(formAdd);
        if (opcion == 'Ins') {
            win.setTitle(perfil.etiquetas.lbTitAddConex);
            formAdd.getForm().reset();
            Ext.getCmp('btnaplicar').show();
        }
        else {
            win.setTitle(perfil.etiquetas.lbTitModConex);
            formAdd.getForm().reset();
            var record = smRadioButtons.getLastSelected();
            valordispl = record.get('tipo');
            idconexion = record.get('idconexion');
            storeCombo.load();
            DeleteRecordsModificar(valordispl);
            Ext.getCmp('btnaplicar').hide();
            idconexion = record.get('idconexion');
            denom = record.get('tipoconexion');
            tipoSelected = record.get('conex');
            descripcion = record.get('descripcion');
            formAdd.getForm().loadRecord(record);
        }
        win.doLayout();
        win.show();
    }
    function gestionarAccion() {
        if (opcion == 'Ins')
            adicionarConexion();
        else
            Modificar();
    }
    //Metodo para adicionar una conexion
    function adicionarConexion(a) {
        var denominacion = Ext.getCmp('tipoconexion').getValue();
        if (!VerificarDenominacion(denominacion)) {
            if (formAdd.getForm().isValid()) {                 
                var tipoconex = Ext.getCmp('tipo').getValue();
                formAdd.getForm().submit({
                    url: 'InsertarConexion',
                    params: {tipoconex: tipoconex},
                    failure: function(form, action) {
                        if (action.result.codMsg != 3) {                           
                            storeGRB.load();
                            storeCombo.load();
                            //formAdd.getForm().reset();
                            btnEliminar.enable();
                            if (!a)
                                win.hide();
                        }
                        if (action.result.codMsg == 3)                           
                        btnEliminar.enable();
                    }});
            }
            else {
                mostrarMensaje(3, perfil.etiquetas.lbMsgErrorEnCampos);
            }
        }
        else {
            mostrarMensaje(3, perfil.etiquetas.denoRepetida);
        }
    }

    function Modificar() {
        var denominacionM = Ext.getCmp('tipoconexion').getValue();
        var tipoSelectedM = Ext.getCmp('tipo').getValue();
        var descripcionM = Ext.getCmp('descripcion').getValue();
        if (denom != denominacionM || tipoSelected != tipoSelectedM || descripcion != descripcionM) {
            if (formAdd.getForm().isValid()) {               
                var json = Ext.encode([]);
                formAdd.getForm().submit({
                    url: 'ModificarTipoConexion',
                    params: {records: json, tipo1: valordispl},
                    failure: function(form, action) {
                        if (action.result.codMsg != 3) {                            
                            storeGRB.load();
                            storeCombo.load();
                            //formAdd.getForm().reset();
                            btnEliminar.enable();
                            win.hide();

                        }
                        if (action.result.codMsg == 3)                           
                        btnEliminar.enable();
                    }});
            } else {
                mostrarMensaje(3, perfil.etiquetas.lbMsgErrorEnCampos);
            }
        }
        else {
            mostrarMensaje(3, perfil.etiquetas.NoModify);
        }
    }
    //Metodo para modificar las conexiones        
    function Salvarseleccion() {
        var filasModificadas = storeGRB.getModifiedRecords();
        var longitud = filasModificadas.length;
        var sendAllModifiers = [];
        for (var i = 0; i < longitud; i++) {
            var oneFile = {
                idconexion: filasModificadas[i].data.idconexion,
                seleccion: filasModificadas[i].data.seleccion,
                tipoconexion: filasModificadas[i].data.tipoconexion,
                descripcion: filasModificadas[i].data.descripcion,
                tipo: filasModificadas[i].data.tipo
            };
            sendAllModifiers.push(oneFile);
        }
        if (sendAllModifiers.length > 0) {          
            var json = Ext.encode(sendAllModifiers);
            Ext.Ajax.request({
                url: 'ModificarTipoConexion',
                method: 'POST',
                params: {records: json},
                callback: function(options, success, response) {
                    responseData = Ext.decode(response.responseText);
                    Ext.Msg.hide();
                    if (responseData.codMsg == 1) {
                        storeGRB.load();
                        btnSalvarCambios.disable();                        
                    }
                    if (responseData.codMsg == 3)
                        storeGRB.load();                  
                }});
        }
    }

    function VerificarDenominacion(denominacion) {

        for (var i = 0; i < storeGRB.getCount(); i++) {
            var record = storeGRB.getAt(i);
            var oldDenom = record.get('tipoconexion');
            if (oldDenom == denominacion)
                return true;
        }
        return false;
    }

    //Metodo para Eliminar las conexiones    
    function EliminarTipoConexion() {
        mostrarMensaje(2, perfil.etiquetas.lbMsgDeseaEliminar,
                function elimina(btnPresionado) {
                    if (btnPresionado == 'ok') {                 
                        Ext.Ajax.request({
                            url: 'EliminarConexion',
                            method: 'POST',
                            params: {tipo: smRadioButtons.getLastSelected().data.tipo},
                            callback: function(options, success, response) {
                                responseData = Ext.decode(response.responseText);
                                Ext.Msg.hide();
                                if (responseData.codMsg == 1) {                                   
                                    storeGRB.load();
                                    smRadioButtons.clearSelections();
                                    storeCombo.load();
                                }
                            }
                        });
                    }
                });
            }
}
