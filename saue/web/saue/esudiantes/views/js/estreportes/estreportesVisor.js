Ext.define('estreportes', {
    extend: 'Ext.form.Panel',
    alias: 'widget.reportes_visor',
    id: 'reportes_visor',
    initComponent: function () {
        //BOTONES
        var params = null;
        var winRepOptions = null;
        var anno = 0;

        //CARGAR ACCIONES PERMITIDAS
        UCID.portal.cargarAcciones(window.parent.idFuncionalidad);
        var visorReporte = new Ext.Panel({
            layout: 'fit'
        });
        var panel = new Ext.Panel({
            region: 'center',
            id: 'estreportes',
            layout: 'fit',
            items: [visorReporte]
        });

//        Panel general y viewport
        var general = Ext.create('Ext.panel.Panel', {layout: 'border', items: [panel]});
        Ext.create('Ext.Viewport', {layout: 'fit', items: general});

        this.callParent();

        function mostrarReporte() {
            var url = "verreporte?" + params;
            visorReporte.removeAll();
            var html = '<style>';
            html += '.ball {border: 5px solid rgba(0,183,229,0.9);border-top: 5px solid rgba(0,183,229,0.3);border-left: 5px solid rgba(0,183,229,0.3);border-radius: 40px;';
            html += 'width: 40px;height: 40px;-moz-animation: spin 1s infinite linear;-webkit-animation: spin 1s infinite linear;position: absolute;top: 50%;left: 50%;}';
            html += '@-moz-keyframes spin {0% {-moz-transform: rotate(0deg);}100% {-moz-transform: rotate(360deg);}}';
            html += '@-webkit-keyframes spin {0% {-webkit-transform: rotate(0deg); }100% {-webkit-transform: rotate(360deg);}}';
            html += '</style><body><div class="ball"></div><iframe style="position: relative;" top="-23" frameborder="0" height="100%" width="100%" src="' + url + '"></iframe></body>';
            var component = new Ext.Component({
                html: html
            });
            visorReporte.add(component);
        }

        function exportarReporte(format) {
            var url = "verreporte?" + params;
            window.open(url, '_blank');
        }

        function createWindowOption() {
            var reportes = Ext.create('Ext.data.Store', {
                fields: ['id', 'nombre'],
                remoteSort: true,
                autoLoad: true,
                proxy: {
                    type: 'ajax',
                    api: {
                        read: 'getReportes'
                    },
                    actionMethods: {
                        read: 'POST'
                    },
                    reader: {
                        root: 'datos'
                    }
                }
            });

            var cmbReporte = new Ext.form.ComboBox({
                fieldLabel: 'Reporte',
                store: reportes,
                queryMode: 'local',
                allowBlank: false,
                displayField: 'nombre',
                valueField: 'id',
                width: 575
            });

            reportes.on('load', function (store) {
                if (store.count() > 0) {
                    cmbReporte.select(store.getAt(0).data.id);
                } else {
                    mostrarMensaje(3, 'No tiene acceso a ningún reporte');
                }
            });

            cmbReporte.on('change', function (This, newValue) {
                checkFacultad.disable();
                checkFacultad.setValue(false);
                checkAnno.disable();
                checkAnno.setValue(false);
                checkPeriodo.disable();
                checkPeriodo.setValue(false);


                if (newValue === 8) {
                    stcmbPeriodos.getProxy().extraParams = {
                        anno: cmbAnno.getValue(),
                        idtipoperiodo: 1000007
                    };
                } else {
                    stcmbPeriodos.getProxy().extraParams = {
                        anno: cmbAnno.getValue()
                    };
                }
                stcmbPeriodos.load();

                switch (newValue) {
                    case 1:
                    case 2:
                    case 4:
                    case 5:
                    case 8:
                    {
                        checkPeriodo.setValue(true);
                        cmbPeriodo.enable();
                        checkAnno.setValue(true);
                        checkFacultad.enable();
                        cmbAnno.enable();
                    }
                        break;
                    case 9:
                    {
                        checkFacultad.enable();
                        checkAnno.setValue(true);
                        cmbAnno.enable();
                    }
                        break;
                    case 10:
                    case 11:
                    case 12:
                    case 14:
                    case 15:
                    case 16:
                    case 23:
                    case 22:
                    case 25:
                    {
                        checkAnno.setValue(true);
                        checkAnno.disable();
                        checkPeriodo.setValue(true);
                        checkPeriodo.disable();
                        cmbAnno.enable();
                        cmbPeriodo.enable();
                    }
                        break;
                    case 29:
                    {
                        checkFacultad.enable();
                        checkAnno.enable();
                        checkPeriodo.enable();
                    }
                        break;
                    case 30:
                    {
                        checkFacultad.enable();
                    }
                        break;
                    case 31:
                    {
                        checkFacultad.enable();
                        checkAnno.enable();
                        checkPeriodo.setValue(true);
                        cmbPeriodo.enable();
                    }
                        break;
                    case 33:
                    case 34:
                    case 35:
                    case 38:
                    {
                        checkFacultad.enable();
                        checkAnno.enable();
                    }
                        break;
                    case 39:
                    {
                        checkAnno.enable();
                        checkPeriodo.setValue(true);
                        cmbPeriodo.enable();
                    }
                        break;
                    default:
                    {
                        ;
                    }
                        break;
                }
            });

            var checkFacultad = new Ext.form.Checkbox({
                listeners: {
                    change: function (This, newValue, oldValue, eOpts) {
                        if (newValue)
                            cmbFacultad.enable();
                        else
                            cmbFacultad.disable();
                    },
                    enable: function (This) {
                        if (This.getValue())
                            cmbFacultad.enable();
                        else
                            cmbFacultad.disable();
                    },
                    disable: function () {
                        cmbFacultad.disable();
                    }
                }
            });
            Ext.define("modelFacultad", {
                extend: 'Ext.data.Model',
                fields: ['idestructura', 'denominacion', 'abreviatura']
            });
            var stcmbFacultades = Ext.create('Ext.data.ArrayStore', {
                model: "modelFacultad",
                autoLoad: true,
                proxy: {
                    type: 'rest',
                    url: 'cargarFacultades',
                    actionMethods: { //Esta Linea es necesaria para el metodo de llamada POST o GET
                        read: 'POST'
                    },
                    reader: {
                        root: 'datos',
                        totalProperty: 'cantidad'
                    }
                },
                listeners: {
                    load: function () {
                        cmbFacultad.select(stcmbFacultades.getAt(0));
                    }
                }
            });
            var cmbFacultad = new Ext.form.field.ComboBox({
                id: 'idestructura',
                name: 'idestructura',
                fieldLabel: perfil.etiquetas.flcmbFacultad,
                editable: false,
                allowBlank: false,
                disabled: true,
                store: stcmbFacultades,
                anchor: '100%',
                queryMode: 'local',
                displayField: 'denominacion',
                valueField: 'idestructura',
                width: 545
            });

            var checkAnno = new Ext.form.Checkbox({
                listeners: {
                    change: function (This, newValue) {
                        if (newValue) {
                            cmbAnno.enable();
                            if (cmbAnno.getValue()) {
                                stcmbPeriodos.load({params: {anno: cmbAnno.getValue()}});
                            }
                        } else {
                            cmbAnno.disable();
                        }
                    },
                    enable: function (This) {
                        if (This.getValue()) {
                            cmbAnno.enable();
                            if (cmbAnno.getValue()) {
                                stcmbPeriodos.load({params: {anno: cmbAnno.getValue()}});
                            }
                        } else {
                            cmbAnno.disable();
                        }
                    },
                    disable: function () {
                        cmbAnno.disable();
                    }
                }
            });

            var storeAnnos = Ext.create('Ext.data.ArrayStore', {
                fields: ["anno"],
                remoteSort: true,
                autoLoad: true,
                proxy: {
                    type: 'rest',
                    url: '../gestnotas/cargarAnnos',
                    actionMethods: { //Esta Linea es necesaria para el metodo de llamada POST o GET
                        read: 'POST'
                    },
                    reader: {
                        root: 'datos'
                    }
                }
            });
            var cmbAnno = new Ext.form.ComboBox({
                id: 'cmAnno',
                name: 'anno',
                fieldLabel: perfil.etiquetas.flcmbAnno,
                store: storeAnnos,
                queryMode: 'local',
                disabled: true,
                allowBlank: false,
                anchor: '100%',
                editable: false,
                displayField: 'anno',
                valueField: 'anno',
                width: 245,
                listeners: {
                    change: function () {
                        if (cmbReporte.getValue() === 8) {
                            stcmbPeriodos.getProxy().extraParams = {
                                anno: cmbAnno.getValue(),
                                idtipoperiodo: 1000007
                            };
                        } else {
                            stcmbPeriodos.getProxy().extraParams = {
                                anno: cmbAnno.getValue()
                            };
                        }
                        stcmbPeriodos.load();
                    }
                }
            });

            var checkPeriodo = new Ext.form.Checkbox({
                listeners: {
                    change: function (This, newValue, oldValue, eOpts) {
                        if (newValue) {
                            cmbPeriodo.enable();
                        } else {
                            cmbPeriodo.disable();
                        }
                        if (!checkAnno.isDisabled() && checkAnno.getValue()) {
                            if (cmbAnno.getValue()) {
                                stcmbPeriodos.load({params: {anno: cmbAnno.getValue()}});
                            }
                        }
                    },
                    enable: function (This) {
                        if (This.getValue()) {
                            cmbPeriodo.enable();
                        }
                        if (!checkAnno.isDisabled() && checkAnno.getValue()) {
                            if (cmbAnno.getValue()) {
                                stcmbPeriodos.load({params: {anno: cmbAnno.getValue()}});
                            }
                        }
                    },
                    disable: function () {
                        cmbPeriodo.disable();
                    }
                }
            });
            Ext.define("modelPeriodo", {
                extend: 'Ext.data.Model',
                fields: ['idperiododocente', 'descripcion']
            });
            var stcmbPeriodos = Ext.create('Ext.data.ArrayStore', {
                model: "modelPeriodo",
                //autoLoad: true,
                proxy: {
                    type: 'rest',
                    url: 'cargarPeriodos',
                    actionMethods: { //Esta Linea es necesaria para el metodo de llamada POST o GET
                        read: 'POST'
                    },
                    reader: {
                        root: 'datos',
                        totalProperty: 'cantidad'
                    }
                },
                listeners: {
                    load: function () {
                        cmbPeriodo.select(stcmbPeriodos.getAt(0));
                    }
                }
            });
            var cmbPeriodo = new Ext.form.field.ComboBox({
                id: 'idperiododocente',
                name: 'idperiododocente',
                fieldLabel: perfil.etiquetas.flcmbPeriodo,
                editable: false,
                allowBlank: false,
                disabled: true,
                store: stcmbPeriodos,
                anchor: '120%',
                queryMode: 'local',
                displayField: 'descripcion',
                valueField: 'idperiododocente',
                width: 258
            });

            var formPanel = new Ext.form.Panel({
                frame: true,
                bodyStyle: 'padding:10px 1px',
                fieldDefaults: {
                    labelAlign: 'top',
                    msgTarget: 'side'
                },
                items: [cmbReporte, {
                    xtype: 'panel',
                    layout: 'column',
                    bodyStyle: 'padding:10px 1px',
                    border: false,
                    items: [
                        {
                            columnWidth: .05,
                            border: false,
                            items: [{
                                html: "<br>",
                                border: false
                            }, checkFacultad]
                        },
                        {
                            columnWidth: .95,
                            border: false,
                            items: [cmbFacultad]
                        }]
                }, {
                    xtype: 'panel',
                    layout: 'column',
                    bodyStyle: 'padding:10px 1px',
                    border: false,
                    items: [
                        {
                            xtype: 'panel',
                            columnWidth: .5,
                            layout: 'column',
                            border: false,
                            items: [{
                                columnWidth: .1,
                                border: false,
                                items: [{
                                    html: "<br>",
                                    border: false
                                }, checkAnno]
                            }, {
                                columnWidth: .9,
                                border: false,
                                items: [cmbAnno]
                            }]
                        },
                        {
                            xtype: 'panel',
                            columnWidth: .5,
                            layout: 'column',
                            border: false,
                            items: [{
                                columnWidth: .1,
                                border: false,
                                items: [{
                                    html: "<br>",
                                    border: false
                                }, checkPeriodo]
                            }, {
                                columnWidth: .9,
                                border: false,
                                items: [cmbPeriodo]
                            }]
                        }
                    ]
                }]
            });
            winRepOptions = new Ext.window.Window({
                title: 'Ver reporte',
                height: 300,
                width: 600,
                layout: 'fit',
                resizable: false,
                closable: false,
                items: [formPanel],
                buttons: [
                    {
                        text: 'Aceptar',
                        handler: function () {
                            if (formPanel.getForm().isValid()) {
                                params = "idreporte=" + cmbReporte.getValue();
                                params += (checkFacultad.getValue()) ? ("&idestructura=" + cmbFacultad.getValue()) : "";
                                params += "&anno=" + cmbAnno.getValue();
                                params += (checkPeriodo.getValue()) ? ("&idperiododocente=" + cmbPeriodo.getValue()) : "";
                                if (cmbReporte.getValue() === 8) {
                                    params += (checkPeriodo.getValue()) ? ("&idtipoperiodo=" + 1000007) : "";
                                }
                                new Saue.VisorReportes({
                                    reporte: cmbReporte.getRawValue(),
                                    url: 'verreporte?' + params
                                }).show();
                            } else
                                mostrarMensaje(3, perfil.etiquetas.lbMsgErrorEnCampos);
                        }
                    }
                ]
            });

            var hoy = new Date(),
                anno = hoy.getFullYear().toString();
            cmbAnno.select(anno);
        }

        createWindowOption();
        winRepOptions.show();
    }
});
