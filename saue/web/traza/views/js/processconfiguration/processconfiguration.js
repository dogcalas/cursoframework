var perfil = window.parent.UCID.portal.perfil;
perfil.etiquetas = Object();
UCID.portal.cargarEtiquetas('processconfiguration', cargarInterfaz);
// //------------ Inicializo el singlenton QuickTips ------------////
Ext.QuickTips.init();
// //------------ Declarar Variables ------------////

// //------------ Area de Validaciones ------------////
var texto, num, dir, checkerid, salvanode, primera = true, cambios, salvaindexact, nameevent, idultimoevento;
texto = /^([a-zA-ZáéíóúñüÑ]+[a-zA-ZáéíóúñüÑ\d\.\-\_ ]*)+$/;
num = /^([a-z\d\.\-\_]*)+$/;
function cargarInterfaz() {
    Ext
            .onReady(function() {

        var storetree = Ext.create('Ext.data.TreeStore', {
            fields: ['text', 'src'],
            proxy: {
                type: 'ajax',
                url: 'treegridJson',
                actionMethods: {// Esta Linea es necesaria para el
                    // metodo de
                    // llamada POST o GET
                    read: 'POST'
                },
                reader: {
                    id: "id"
                }
            },
            folderSort: true,
            sorters: [{
                    property: 'text',
                    direction: 'ASC'
                }]
        });

        var panel = new Ext.Panel(
                {
                    layout: 'border',
                    title: "Configuración del registro de trazas de proceso",
                    renderTo: 'panel',
                    items: [
                        Ext
                                .create(
                                'Ext.tree.Panel',
                                {
                                    // frame:true,
                                    title: "Procesos y eventos",
                                    id: 'tree',
                                    collapsible: true,
                                    autoScroll: true,
                                    region: 'west',
                                    split: true,
                                    width: '16%',
                                    autoExpandColumn: 'expandir',
                                    rootVisible: false,
                                    singleExpand: true,
                                    store: storetree,
                                    root: {
                                        text: 'Procesos',
                                        id: '0'
                                    },
                                    listeners: {
                                        'itemclick': function(
                                                a, b, item,
                                                index, e) {
                                            valseleccion = true;
                                            if (plvisible) {
                                                plvisible = false;
                                                pl.setVisible(false);
                                            }

                                            if (b.getId() != idultimoevento
                                                    || cambios) {

                                                checkerid = b
                                                        .getId();
                                                idultimoevento = checkerid;
                                                salvaindexact = index;
                                                salvanodeindexact = b.getPath();
                                                if (primera) {
                                                    salvanodeindex = b.getPath();
                                                    nameevent = b.data.text;
                                                    primera = false;
                                                }
                                                if (cambios == false) {
                                                    nameevent = b.data.text;
                                                    salvanodeindex = b.getPath();
                                                }
                                                checkerid = checkerid + ' ';
                                                checkerid = checkerid
                                                        .split(" ");
                                                if (cambios && checkerid.length > 2) {
                                                    mostrarMensaje(
                                                            2,
                                                            "Desea guardar los cambios realizados",
                                                            elimina);
                                                }

                                                if (checkerid.length > 2
                                                        && cambios == false) {
                                                    Ext.Ajax
                                                            .request({
                                                        url: 'setidPE',
                                                        method: 'POST',
                                                        params: {
                                                            idP: checkerid[0],
                                                            idE: checkerid[1]
                                                        },
                                                        callback: function(
                                                                options,
                                                                success,
                                                                response) {
                                                            responseData = Ext
                                                                    .decode(response.responseText);
                                                            if (responseData.state == 1) {
                                                                stpl.reload();
                                                                statributes
                                                                        .reload();
                                                                stcondiciones
                                                                        .reload();
                                                                stalltable
                                                                        .reload();
                                                                panelderecho
                                                                        .enable();
                                                                panelabajo
                                                                        .enable();
                                                                panelcentro
                                                                        .enable();

                                                                Ext
                                                                        .getCmp(
                                                                        'guardar')
                                                                        .enable();
                                                            }

                                                        }

                                                    })

                                                } else {
                                                    panelderecho
                                                            .disable();
                                                    panelabajo
                                                            .disable();
                                                    panelcentro
                                                            .disable();
                                                    Ext
                                                            .getCmp(
                                                            'guardar')
                                                            .disable();
                                                    // alert(stcondiciones.getById('1').data.colunna);
                                                }
                                            }

                                        }
                                    }
                                }), panelcentro,
                        panelabajo, panelderecho

                    ],
                    tbar: [/*{
                            xtype: 'tbspacer',
                            width: '42%'
                        },*/ {
                            text: 'Guardar la configuración',
                            id: 'guardar',
                            icon: '../../views/images/guardar.png',
                            width: 160,
                            handler: function() {
                                guardar();
                            }
                        }/*,
                        {
                            xtype: 'tbspacer',
                            width: '42%'
                        }*/]
                });

        var viewport = Ext.create('Ext.Viewport', {
            layout: 'fit',
            items: [panel]

        });

        panelcentro.add(atributos);
        panelderecho.add(uniondepanel);
        panelcentro.disable();
        panelderecho.disable();
        panelabajo.disable();
        Ext.getCmp('guardar').disable();

    });
}
function guardar(guardaryseguir) {
    if (cambios) {
        var totalpl = stpl.getCount();
        var pls = "{", aux;
        for (var int = 0; int < totalpl; int++) {
            if (int == 0 && stpl.getAt(int).data.name == "" && stpl.getAt(int).data.tablev == "" && stpl.getAt(int).data.action == "") {
                break;
            }
            aux = stpl.getAt(int).data.name + ",";
            if (aux == ",") {
                Ext.MessageBox.show({
                    title: 'Guardar configuración',
                    msg: "Por favor verifique, existen campos vacíos.",
                    icon: Ext.MessageBox.ERROR,
                    buttons: Ext.MessageBox.OK
                });
                return false;
            }
            else
                pls += aux;

            aux = stpl.getAt(int).data.tablev + ",";
            if (aux == ",") {
                Ext.MessageBox.show({
                    title: 'Guardar configuración',
                    msg: "Por favor verifique, existen campos vacíos.",
                    icon: Ext.MessageBox.ERROR,
                    buttons: Ext.MessageBox.OK
                });
                return false;
            }
            else
                pls += aux;

            if (int != totalpl - 1) {
                aux = stpl.getAt(int).data.action + ",";
                if (aux == ",") {
                    Ext.MessageBox.show({
                        title: 'Guardar configuración',
                        msg: "Por favor verifique, existen campos vacíos.",
                        icon: Ext.MessageBox.ERROR,
                        buttons: Ext.MessageBox.OK
                    });
                    return false;
                }
                else
                    pls += aux;
            }
            else {
                aux = stpl.getAt(int).data.action;
                if (aux == null || aux == "") {
                    Ext.MessageBox.show({
                        title: 'Guardar configuración',
                        msg: "Por favor verifique, existen campos vacíos.",
                        icon: Ext.MessageBox.ERROR,
                        buttons: Ext.MessageBox.OK
                    });
                    return false;
                }
                else
                    pls += aux;
            }
        }
        pls += "}";


        var atributostosend = atributos.getStore(), resultatribute, acolumnas, aactions;
        for (var int = 0; int < 7; int++) {
            if (int == 0) {
                acolumnas = atributostosend.getAt(int).data.tablev;
                aactions = atributostosend.getAt(int).data.action;
                if (acolumnas == null || acolumnas == "" || aactions == null
                        || aactions == "") {
                    Ext.MessageBox.show({
                        title: 'Guardar configuración',
                        msg: "Por favor verifique, existen campos vacíos.",
                        icon: Ext.MessageBox.ERROR,
                        buttons: Ext.MessageBox.OK
                    });
                    return false;
                } else {
                    resultatribute = acolumnas;
                    resultatribute += ',' + aactions;
                }
            } else {
                acolumnas = atributostosend.getAt(int).data.tablev;
                aactions = atributostosend.getAt(int).data.action;
                if (aactions == "" && (acolumnas != null && acolumnas != "")) {
                    Ext.MessageBox.show({
                        title: 'Guardar configuración',
                        msg: "Por favor verifique, existen campos vacíos.",
                        icon: Ext.MessageBox.ERROR,
                        buttons: Ext.MessageBox.OK
                    });
                    return false;
                }
                if (acolumnas != null && acolumnas != "" && aactions != "" && aactions != "") {
                    resultatribute += ',' + acolumnas;
                    resultatribute += ',' + aactions;
                }
                else{
					resultatribute += ',';
                    resultatribute += ',';
					}

            }
        }
        var index1,index2,index3;
        var allcondiciones = condiciones.getStore(), resulcondiciones, comparador, acolumnas2;
        for (var int = 0; int < allcondiciones.getCount(); int++) {
            strin = (int).toString(8);
            if (int == 0) {
                acolumnas = allcondiciones.getAt(int).data.columna;
                comparador = allcondiciones.getAt(int).data.comparador;
                acolumnas2 = allcondiciones.getAt(int).data.valorocolumna;
                if (acolumnas == null || acolumnas == "" || comparador == null
                        || comparador == "" || acolumnas2 == null
                        || acolumnas2 == "") {
                    resulcondiciones = "";
                    break;
                }
                
                
                resulcondiciones = acolumnas;
                resulcondiciones += ',' + comparador;
                resulcondiciones += ',' + acolumnas2;
            } else {
                acolumnas = allcondiciones.getAt(int).data.columna;
                comparador = allcondiciones.getAt(int).data.comparador;
                acolumnas2 = allcondiciones.getAt(int).data.valorocolumna;
                if (acolumnas == null || acolumnas == "" || comparador == null
                        || comparador == "" || acolumnas2 == null
                        || acolumnas2 == "") {
                    Ext.MessageBox.show({
                        title: 'Guardar configuración',
                        msg: "Existen errores en las condiciones.",
                        icon: Ext.MessageBox.ERROR,
                        buttons: Ext.MessageBox.OK
                    });
                    return false;
                }
                

                resulcondiciones += ',' + acolumnas;
                resulcondiciones += ',' + comparador;
                resulcondiciones += ',' + acolumnas2;
                resulcondiciones += ',' + allcondiciones.getAt(int).data.operador;

            }

        }
        
        
        
        if (Ext.getCmp('groupby').getValue() == null
                || Ext.getCmp('groupby').getValue() == "") {
            Ext.MessageBox.show({
                title: 'Guardar configuración',
                msg: "Necesita especificar instancia de proceso.",
                icon: Ext.MessageBox.ERROR,
                buttons: Ext.MessageBox.OK
            });
            return false;
        }
        var car = Ext.getCmp('groupby').value;
        //alert(car.toSource());
        var piid = "";
        for (var i = 0; i < car.length; i++) {
            if (i == 0)
                piid += car[i];
            else
                piid += "," + car[i];
        }

        Ext.Ajax
                .request({
            url: 'save',
            method: 'POST',
            params: {
                pls: pls,
                resultatribute: resultatribute,
                resulcondiciones: resulcondiciones,
                groupby: piid
            },
            callback: function(options, success, response) {
                responseData = Ext.decode(response.responseText);
                if (responseData.state == 1) {
                    if (guardaryseguir != "guardar") {
                        cambios = false;
                        Ext.MessageBox
                                .show({
                            title: 'Configuración de evento',
                            msg: "Configuración guardada satisfactoriamente.",
                            icon: Ext.MessageBox.INFO,
                            buttons: Ext.MessageBox.OK
                        });

                    } else {
                        message("Evento  " + nameevent,
                                "Configuracion Guarda.");
                        cambios = false;
                        return true;
                    }
                }

            }

        });
    } else
        Ext.MessageBox.show({
            title: 'Guardar configuración',
            msg: "No se han realizado cambios.",
            icon: Ext.MessageBox.ERROR,
            buttons: Ext.MessageBox.OK
        });

}
function elimina(btnPresionado) {
    if (btnPresionado == 'ok') {
        if (guardar("guardar") == false) {
            panelderecho.enable();
            panelabajo.enable();
            panelcentro.enable();
            Ext.getCmp('guardar').enable();
            Ext.getCmp('tree').selectPath(salvanodeindex);

        } else
            Ext.Ajax.request({
                url: 'setidPE',
                method: 'POST',
                params: {
                    idP: checkerid[0],
                    idE: checkerid[1]
                },
                callback: function(options, success, response) {
                    responseData = Ext.decode(response.responseText);
                    if (responseData.state == 1) {
                        cambios = false;
                        stpl.reload();
                        statributes.reload();
                        stcondiciones.reload();
                        stalltable.reload();
                        panelderecho.enable();
                        panelabajo.enable();
                        panelcentro.enable();
                        Ext.getCmp('guardar').enable();
                    }

                }

            });

    } else {
        cambios = false;
        salvanodeindex = salvanodeindexact;
        Ext.Ajax.request({
            url: 'setidPE',
            method: 'POST',
            params: {
                idP: checkerid[0],
                idE: checkerid[1]
            },
            callback: function(options, success, response) {
                responseData = Ext.decode(response.responseText);
                if (responseData.state == 1) {
                    stpl.reload();
                    statributes.reload();
                    stcondiciones.reload();
                    stalltable.reload();
                    panelderecho.enable();
                    panelabajo.enable();
                    panelcentro.enable();
                    Ext.getCmp('guardar').enable();
                }

            }

        });
    }
}

function message(title, text) {
    Ext.message.msg(title, text);
}
