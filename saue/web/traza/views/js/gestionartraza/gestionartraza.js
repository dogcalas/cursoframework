Ext.QuickTips.init();
var perfil = window.parent.UCID.portal.perfil
perfil.etiquetas = Object();
UCID.portal.cargarEtiquetas('gestionartraza', cargartraza);
var fechaDesde, fechaHasta, stComboTipo;
var storegridtraza, gdGestiontraza, combotipo, cmGestiontraza, smtraza;
var viewport, winBusq, frmBusq, btnExportar, btnExportarxes, btnAyuda, btnAvanzado, btnActivarTrazas, values, seleccionarproceso;

Ext.apply(Ext.form.VTypes, {
    daterange: function(val, field) {
        var date = field.parseDate(val);

        if (!date) {
            return;
        }
        if (field.startDateField
                && (!this.dateRangeMax || (date.getTime() != this.dateRangeMax
                .getTime()))) {
            var start = Ext.getCmp(field.startDateField);
            start.setMaxValue(date);
            start.validate();
            this.dateRangeMax = date;
        } else if (field.endDateField
                && (!this.dateRangeMin || (date.getTime() != this.dateRangeMin
                .getTime()))) {
            var end = Ext.getCmp(field.endDateField);
            end.setMinValue(date);
            end.validate();
            this.dateRangeMin = date;
        }
        /*
         * Always return true since we're only using this vtype to set the
         * min/max allowed values (these are tested for after the vtype test)
         */
        return true;
    }
});
stproceso = new Ext.data.Store({
    url: 'getallprocess',
    reader: new Ext.data.JsonReader({
        root: 'datos'
    }, ['name'])
});

seleccionarproceso = new Ext.Window({
    hidden: true,
    title: 'Seleccionar proceso',
    width: 350,
    height: 120,
    layout: 'fit',
    items: [
        {
            xtype: 'form',
            title: '',
            frame: true,
            region: 'center',
            id: 'form',
            items: [{
                    id: 'combo',
                    xtype: 'combo',
                    fieldLabel: 'Proceso',
                    width: 218,
                    height: 120,
                    valueField: 'name',
                    displayField: 'name',
                    store: stproceso,
                    //mode: 'remote',
                    triggerAction: 'all',
                    allowBlank: false,
                    blankText: 'Debe seleccionar el proceso a exportar.',
                    forceSelection: true,
                    editable: false
                }]
        }],
    buttons: [
        {
            icon: perfil.dirImg + 'cancelar.png',
            iconCls: 'btn',
            text: "Cancelar",
            handler: function() {
                seleccionarproceso.hide();
            }
        },
        {
            icon: perfil.dirImg + 'aplicar.png',
            iconCls: 'btn',
            text: "Aplicar",
            handler: function() {
                adicionarBundle('apl');
            }
        },
        {
            icon: perfil.dirImg + 'aceptar.png',
            iconCls: 'btn',
            text: "Aceptar",
            handler: function() {
                ExportarTrazasxes();
            }
        }]
});
var winLogin = null;

function cargartraza() {
    // Store de los tipos de trazas
    stComboTipo = new Ext.data.Store({
        url: 'cargarcombotipo',
        autoLoad: true,
        reader: new Ext.data.JsonReader({
            root: "tipo_traza",
            id: "idtipotraza"
        }, [{
                name: 'idtipotraza'
            }, {
                name: 'tipotraza'
            }])
    });

    // Store del grid
    storegridtraza = new Ext.data.Store({
        url: '',
        listeners: {'beforeload': function(thisstore, objeto) {
                if (!combotipo.getValue()) {
                    mostrarMensaje(3, "Debe seleccionar un tipo de traza.");

                }
            }},
        reader: new Ext.data.JsonReader({
            totalProperty: "totalProperty",
            root: "root"
        }, [{
                name: 'vacio'
            }])
    });
    btnExportar = new Ext.Button({
        disabled: true,
        id: 'btnExportar',
        icon: perfil.dirImg + 'exportar.png',
        iconCls: 'btn',
        text: perfil.etiquetas.lbBtnExportar,
        handler: OnbtnExportarTrazas
    });
    btnExportarxes = new Ext.Button({
        disabled: false,
        hidden: true,
        id: 'btnExportarxes',
        icon: perfil.dirImg + 'exportar.png',
        iconCls: 'btn',
        text: 'Exportar XES',
        handler: OnbtnExportarTrazasxes
    });
    btnActivarTrazas = new Ext.Button({
        disabled: false,
        id: 'btnActivarTrazas',
        icon: perfil.dirImg + 'exportar.png',
        iconCls: 'btn',
        text: 'Activar trazas', //perfil.etiquetas.lbBtnExportar,
        handler: OnbtnActivarTrazas
    });
    // Boton de Ayuda
    btnAyuda = new Ext.Button({
        disabled: true,
        id: 'btnAyuda',
        icon: perfil.dirImg + 'ayuda.png',
        iconCls: 'btn',
        text: perfil.etiquetas.lbBtnAyuda
    });
    // Boton para busqueda avanzada
    btnAvanzado = new Ext.Button({
        disabled: true,
        id: 'btnAvanzado',
        icon: perfil.dirImg + 'buscaravanzada.png',
        iconCls: 'btn',
        text: perfil.etiquetas.lbBtnAvanzada,
        handler: function() {
            winForm(true)
        }
    })

    Ext.Ajax.method = 'post';

    function loadData(pUser, pPasswd, pData) {
        pData.user = pUser;
        pData.passwd = pPasswd

        storeMostrarDatos.load({
            params: pData
        });

        winMostrarDatos.show();
    }

    storeMostrarDatos = new Ext.data.Store({
        url: 'mostrardatos',
        reader: new Ext.data.JsonReader({
            root: 'data',
            fields: ['campo', 'valor']
        })
    });


    winMostrarDatos = new Ext.Window({
        modal: true,
        title: 'Mostrar Datos',
        width: 400,
        height: 400,
        closable: false,
        layout: 'fit',
        closeAction: 'hide',
        buttons: [{text: 'Cerrar', handler: function() {
                    winMostrarDatos.hide();
                }
            }],
        items: {
            xtype: 'grid',
            store: storeMostrarDatos,
            viewConfig: {forceFit: true},
            columns: [{header: 'Campo', dataIndex: 'campo'},
                {header: 'Valor', dataIndex: 'valor'}]
        }
    });

    frmLogin = new Ext.form.FormPanel({
        frame: true,
        closeAction: 'hide',
        items: [{xtype: 'textfield', fieldLabel: 'Usuario', id: 'user'},
            {xtype: 'textfield', fieldLabel: 'Contraseña', inputType: 'password', id: 'passwd'}]
    });



    btnMostrarDatos = new Ext.Button({
        disabled: true,
        id: 'btnMostrarDatos',
        icon: perfil.dirImg + 'buscaravanzada.png',
        iconCls: 'btn',
        text: 'Mostrar Datos',
        handler: function() {
            if (!winLogin)
                winLogin = new Ext.Window({
                    modal: true,
                    title: 'Autenticación',
                    items: frmLogin,
                    closable: false,
                    buttons: [{text: 'Cancelar', handler: function() {
                                winLogin.hide();
                            }
                        },
                        {text: 'Aceptar', handler: function() {
                                loadData(Ext.getCmp('user').getValue(),
                                        Ext.getCmp('passwd').getValue(),
                                        gdGestiontraza.getSelectionModel().getSelected().data);
                                winLogin.hide();
                            }}]
                });


            winLogin.show();
        }
    });

    // Campo fecha desde
    fechaDesde = new Ext.form.DateField({
        id: 'iddesde',
        fieldLabel: perfil.etiquetas.lbDfDesde,
        vtype: 'daterange',
        endDateField: 'idhasta',
        anchor: '50%',
        maxValue: new Date(),
        readOnly: false,
        format: 'd/m/Y',
        listeners: {
            'change': function() {
                if (combotipo.getValue() != "") {
                    cargar(storegridtraza, combotipo.getRawValue(), combotipo.getValue(), combocategoria.getValue(), fechaDesde.getRawValue(), fechaHasta.getRawValue());
                }
            }
        }
    });
    // Campo fecha hasta
    fechaHasta = new Ext.form.DateField({
        id: 'idhasta',
        fieldLabel: perfil.etiquetas.lbDfHasta,
        vtype: 'daterange',
        startDateField: 'iddesde',
        anchor: '50%',
        maxValue: new Date(),
        readOnly: false,
        format: 'd/m/Y',
        listeners: {
            'change': function() {
                if (combotipo.getValue() != "") {
                    cargar(storegridtraza, combotipo.getRawValue(), combotipo.getValue(), fechaDesde.getRawValue(), fechaHasta.getRawValue());
                }
            }
        }
    });

    cmGestiontraza = new Ext.grid.ColumnModel([{
            id: 'expandir'
        }]);
    // Combo de tipos de trazas
    combotipo = new Ext.form.ComboBox({
        allowBlank: false,
        emptyText: 'Seleccione',
        fieldLabel: perfil.etiquetas.lbCbTipoTraza,
        anchor: '95%',
        readOnly: true,
        store: stComboTipo,
        displayField: 'tipotraza',
        valueField: 'idtipotraza',
        hiddenName: 'idtipotraza',
        triggerAction: 'all',
        mode: 'local',
        listeners: {
            'select': function() {
                confform(combotipo.getRawValue()), confgrid(combotipo.getRawValue());
            }
        }
    });


    smtraza = new Ext.grid.RowSelectionModel({
        singleSelect: true,
        listeners: {
            'rowselect': function(smodel, rowIndex, keepExisting, record) {
                btnExportar.enable(), btnAvanzado.enable();
                ;
            }
        }
    });

//    smtraza.on('rowselect', function(smodel, rowIndex, keepExisting, record) {
//        if (combotipo.getValue() == '11') {
//            //btnMostrarDatos.enable ();
//        }
//    }, this);
    // create the gdGestiontraza
    var xg = Ext.grid;
    gdGestiontraza = new xg.GridPanel({
        frame: true,
        sm: smtraza,
        store: storegridtraza,
        /*loadMask : {
         store : storegridtraza
         },*/
        cm: cmGestiontraza,
        tbar: [{
                xtype: 'label',
                text: 'Desde'
            }, fechaDesde, {
                xtype: 'label',
                text: 'Hasta'
            }, fechaHasta, {
                xtype: 'label',
                text: 'Tipo'
            }, combotipo, '-', btnAvanzado],
        bbar: new Ext.PagingToolbar({
            store: storegridtraza,
            displayInfo: false
        })
    });

    pGrid = new Ext.Panel({
        title: 'Gestión de trazas',
        id: 'id',
        tbar: [btnExportar, btnExportarxes /*, btnAyuda,btnActivarTrazas*/],
        layout: 'fit',
        items: gdGestiontraza
    });

    viewport = new Ext.Viewport({
        layout: 'fit',
        items: pGrid
    });
}// Endof cargar interfaz

// Funcion para configurar el grid en dependencia del tipo de traza
function confgrid(traza) {
    btnExportar.disabled = true,
    btnAvanzado.disabled = true,
    btnExportarxes.disable = true;
//    Ext.getBody().mask(perfil.etiquetas.confMaskMsg);
    Ext.Ajax.request({
        url: 'confgrid',
        params: {tipo_traza: traza},
        callback: function(options, success, response) {
            responseData = Ext.decode(response.responseText);
            if (responseData.grid) {
                var newcm = generaDinamico('cm', responseData.grid.columns);
                storegridtraza = new Ext.data.Store({
                    url: 'cargargrid',
                    listeners: {'load': function() {
                            gdGestiontraza.getSelectionModel().selectFirstRow();
                        }
                    },
                    reader: new Ext.data.JsonReader({
                        totalProperty: 'cantidad_trazas',
                        root: 'trazas',
                        id: 'idtraza'
                    }, generaDinamico('rdcampos', responseData.grid.campos))
                });
                Ext.getBody().unmask();
                if (newcm && storegridtraza)
                {
                    gdGestiontraza.reconfigure(storegridtraza, newcm);
                    gdGestiontraza.getBottomToolbar().bind(storegridtraza);
//                    gdGestiontraza.loadMask = new Ext.LoadMask(Ext.getBody(), {/*msg: perfil.etiquetas.loadMaskMsg,*/ store: storegridtraza});
                    cargar(storegridtraza, combotipo.getRawValue(), combotipo.getValue(), fechaDesde.getRawValue(), fechaHasta.getRawValue());
                }
            }
        }
    });
}
//Funcion para cargar el grid
function cargar(estore, traza, idtraza, fechaini, fechafin)
{
    if (traza == "Proceso")
        btnExportarxes.show();
    else
        btnExportarxes.hide();
    filtro = null;
    if (winBusq)
        filtro = frmBusq.getForm().getValues();
    estore.baseParams = {
        idtipotraza: idtraza,
        fecha_desde: fechaini,
        fecha_hasta: fechafin,
        tipotraza: traza,
        campos: Ext.encode(filtro)
    };
    estore.reload({params: {
            limit: 20,
            start: 0
        }
    });
    this.values = filtro;
    if (winBusq)
        winBusq.hide();
}
//Funcion para configurar el formulario de busqueda avanzada.
function confform(traza)
{
    Ext.getBody().mask(perfil.etiquetas.confMaskMsg);
    Ext.Ajax.request({
        url: 'confform',
        params: {tipo_traza: traza},
        callback: function(options, success, response) {
            responseData = Ext.decode(response.responseText);
            crearformulario(responseData);
        }
    });
}
//Funcion para crear la ventana del formulario de busqueda.
function winForm(mostrar) {
    if (!winBusq) {
        winBusq = new Ext.Window({modal: true, closeAction: 'hide', layout: 'fit',
            title: 'B&uacute;squeda avanzada', width: 500, autoHeight: true,
            buttons: [{icon: perfil.dirImg + 'cancelar.png', iconCls: 'btn', text: 'Cancelar', handler: function() {
                        winBusq.hide();
                    }},
                {icon: perfil.dirImg + 'buscar.png', iconCls: 'btn', text: 'Buscar', handler: function() {
                        cargar(gdGestiontraza.getStore(), combotipo.getRawValue(), combotipo.getValue(), fechaDesde.getRawValue(), fechaHasta.getRawValue());
                    }}]
        });
    }
    winBusq.add(frmBusq);
    winBusq.doLayout();
    if (mostrar)
        winBusq.show();
}
//Funcion para crear el formulario de busqueda avanzada.
function crearformulario(objitems) {

    if (objitems.length > 1) {
        if (winBusq)
        {
            winBusq.destroy();
            winBusq = false;
        }
        frmBusq = new Ext.FormPanel({
            id: 'idfrmBusq',
            labelAlign: 'top',
            autoHeight: true,
            frame: true,
            items: {layout: 'column',
                items: generaDinamico('form', objitems)
            }
        });
    }
}

//Funcion para activar las trazas
function OnbtnActivarTrazas(btn) {
    // Store de las categorias
    stComboEstado = new Ext.data.Store({
        url: 'cargarcomboestado',
        autoLoad: true,
        reader: new Ext.data.JsonReader({
            root: "estados",
            id: "enabled"
        }, [{
                name: 'enabled'
            }])
    });

    // Combo de tipos de trazas
    combotipoTraza = new Ext.form.ComboBox({
        allowBlank: false,
        emptyText: 'Seleccione',
        fieldLabel: perfil.etiquetas.lbCbTipoTraza,
        anchor: '95%',
        readOnly: true,
        store: stComboEstado,
        displayField: 'enabled',
        valueField: 'enabled',
        hiddenName: 'enabled',
        triggerAction: 'all',
        mode: 'local'
                /*listeners : {
                 'select' : function() {confform(combotipo.getRawValue()),confgrid(combotipo.getRawValue());
                 }
                 }*/
    });
    // Store de los tipos de trazas
    var stGridTipoTraza = new Ext.data.Store({
        url: 'cargarestados',
        autoLoad: true,
        reader: new Ext.data.JsonReader({
            root: "tipo_traza",
            id: "idtipotraza"
        },
        [{
                name: 'tipotraza'
            },
            {
                name: 'enabled'
            }])
    });

    //Grid Principal	
    var gdConfiguraciontraza = new Ext.grid.EditorGridPanel({
        frame: true,
        //sm : smtraza,
        store: stGridTipoTraza, //storegridtraza,
        //clicsToEdit: 1,
        loadMask: {
            store: stGridTipoTraza      //storegridtraza
        },
        //cm : cmConfiguraciontraza,
        columns: [
            {id: 'Alias', header: "Alias", width: 300, dataIndex: 'tipotraza', sortable: true},
            {header: "Estado", width: 100, dataIndex: 'enabled',
                editor: combotipoTraza
            }
        ]
    });

    function OnbtnCancelar(btn) {
        windowTrazas.destroy();
    }

    function OnbtnAceptar(btn) {
        var result = new Array();
        for (var i = 0; i < stGridTipoTraza.getModifiedRecords().length; i++) {
            result.push(stGridTipoTraza.getModifiedRecords()[i].data);
        }
        Ext.Ajax.request({
            url: 'actualizarestadotrazas',
            method: 'POST',
            params: {activation: Ext.util.JSON.encode(result)},
            callback: function(options, sucess, response) {
                var responseData = Ext.decode(response.responseText);
                mostrarMensaje(responseData.codMsg, responseData.msg);
            }
        });
        windowTrazas.destroy();
    }

    var btnCancelar = new Ext.Button({
        //disabled : true,
        id: 'btnCancelar',
        icon: perfil.dirImg + 'cancelar.png',
        iconCls: 'btn',
        text: 'Cancelar', //perfil.etiquetas.lbbtnAdicionar,
        handler: OnbtnCancelar
    });

    var btnAceptar = new Ext.Button({
        //disabled : true,
        id: 'btnAceptar',
        icon: perfil.dirImg + 'aceptar.png',
        iconCls: 'btn',
        text: 'Aceptar', //perfil.etiquetas.lbbtnAtributos
        handler: OnbtnAceptar
    });

    //Panel Principal
    var pGrid = new Ext.Panel({
        //title : 'Activar trazas',
        id: 'id',
        bbar: [btnAceptar, btnCancelar],
        layout: 'fit',
        items: gdConfiguraciontraza
    });
    windowTrazas = new Ext.Window({modal: true, title: 'Activar trazas', closeAction: 'hide', layout: 'fit',
        resizable: false, width: 450, height: 400
    });
    windowTrazas.add(pGrid);
    windowTrazas.doLayout();
    windowTrazas.show();
}

//Funcion para exportar las trazas
function OnbtnExportarTrazas() {
    arreglo = [];
    var limite = gdGestiontraza.getBottomToolbar().pageSize;
    var pagina_activa = gdGestiontraza.getBottomToolbar().getPageData().activePage - 1
    var inicio = limite * pagina_activa;
    arreglo.push(inicio);
    arreglo.push(limite);
    arreglo.push(storegridtraza.baseParams.idtipotraza);
    //arreglo.push(storegridtraza.baseParams.ip_host)
    arreglo.push(storegridtraza.baseParams.fecha_desde);
    arreglo.push(storegridtraza.baseParams.fecha_hasta);
    arreglo.push(storegridtraza.baseParams.tipotraza);
    arreglo.push(Ext.decode(storegridtraza.baseParams.campos));
    window.open('exportarxml?datos=' + Ext.encode(arreglo));
}
function OnbtnExportarTrazasxes(btn) {
    stproceso.reload();
    Ext.getCmp('combo').reset('');
    seleccionarproceso.show();
}
function ExportarTrazasxes(btn) {
    var nameproceso = Ext.getCmp('combo').getValue();
    arreglo = []
    var limite = gdGestiontraza.getBottomToolbar().pageSize;
    var pagina_activa = gdGestiontraza.getBottomToolbar().getPageData().activePage - 1;
    var inicio = limite * pagina_activa;
    arreglo.push(inicio);
    arreglo.push(limite);
    arreglo.push(storegridtraza.baseParams.idtipotraza);
    //arreglo.push(storegridtraza.baseParams.ip_host);
    arreglo.push(storegridtraza.baseParams.fecha_desde);
    arreglo.push(storegridtraza.baseParams.fecha_hasta);
    arreglo.push(storegridtraza.baseParams.tipotraza);
    arreglo.push(Ext.decode(storegridtraza.baseParams.campos));
    arreglo.push(nameproceso);
    /*Ext.Ajax.request({
     url: 'exportarxes',
     method: 'POST',
     params: {
     datos: Ext.encode(arreglo),
     nameproceso: nameproceso
     }
     })*/
    window.open('exportarxes?datos=' + Ext.encode(arreglo));
}
