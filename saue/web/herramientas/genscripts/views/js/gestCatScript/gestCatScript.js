var perfil = window.parent.UCID.portal.perfil;
UCID.portal.cargarEtiquetas('gestcatscript', function() {
    cargarInterfaz();
});

////------------ Inicializo el singlenton QuickTips ------------////
Ext.QuickTips.init();

var arr = new Array();
var stGpRol;

function cargarInterfaz() {

////------------- Botones -------------////

    btnScript = new Ext.Button({
        disabled: true,
        icon: perfil.dirImg + 'esquemas.png',
        iconCls: 'btn',
        text: 'Ver Script',
        handler: function() {
            mostrarScript();
        }
    });

    btnDescargar = new Ext.Button({
        disabled: true,
        icon: perfil.dirImg + 'descargar.png',
        iconCls: 'btn',
        text: 'Descargar paquete',
        handler: function() {
            descargar();
        }
    });

    btnEliminar = new Ext.Button({
        disabled: true,
        icon: perfil.dirImg + 'eliminar.png',
        iconCls: 'btn',
        text: 'Eliminar',
        handler: function() {
            eliminar();
        }
    });

////------------- Store del Grid de Roles -------------- ////
   stGpRol = new Ext.data.Store({
        proxy: new Ext.data.HttpProxy({
            url: 'cargarScripts'
        }),
        reader: new Ext.data.JsonReader({
            totalProperty: "cantidad_filas",
            root: "datos",
            id: "id_script"
        }, [
            {name: 'id_script', mapping: 'id_script'},
            {name: 'nombre_script', mapping: 'nombre_script'},
            {name: 'version_script', mapping: 'version_script'},
            {name: 'nombre_paquete', mapping: 'nombre_paquete'},
            {name: 'nombre_sistema', mapping: 'nombre_sistema'},
            {name: 'version_sistema', mapping: 'version_sistema'},
            {name: 'id_tiposcript', mapping: 'id_tiposcript'},
            {name: 'usuario', mapping: 'usuario'},
            {name: 'fecha', mapping: 'fecha'},
            {name: 'ip_host', mapping: 'ip_host'}
        ])
    });

////------------ Establesco modo de seleccion de grid (single) ---------////
    sm = new Ext.grid.RowSelectionModel({singleSelect: false});

    sm.on('rowselect', function(smodel, rowIndex, keepExisting, record) {
        btnScript.enable();
        btnEliminar.enable();
        btnDescargar.enable();
    }, this);

    sm.on('rowdeselect', function(smodel, rowIndex, keepExisting, record) {
        btnScript.disable();
        btnEliminar.disable();
        btnDescargar.disable();
    }, this);

////---------- Defino el grid de roles ----------////
    var gpRol = new Ext.grid.GridPanel({
        frame: true,
        region: 'center',
        iconCls: 'icon-grid',
        autoExpandColumn: 'expandir',
        store: stGpRol,
        sm: sm,
        columns: [
            {hidden: true, hideable: false, dataIndex: 'id_script'},
            {header: 'Nombre del script', width: 200, dataIndex: 'nombre_script'},
            {header: 'Versión del script', width: 110, dataIndex: 'version_script'},
            {header: 'Nombre del paquete', width: 150, dataIndex: 'nombre_paquete'},
            {header: 'Nombre del sistema', width: 150, dataIndex: 'nombre_sistema'},
            {header: 'Versión del sistema', width: 110, dataIndex: 'version_sistema'},
            {header: 'Tipo de script', width: 100, dataIndex: 'id_tiposcript'},
            {header: 'Usuario que gener&oacute;', width: 200, dataIndex: 'usuario'},
            {header: 'Fecha de creaci&oacute;n', width: 120, dataIndex: 'fecha'},
            {id: 'expandir', header: 'Desde el IP', width: 100, dataIndex: 'ip_host'}
        ],
        bbar: new Ext.PagingToolbar({
            pageSize: 15,
            id: 'ptbaux',
            store: stGpRol,
            displayInfo: true,
            displayMsg: perfil.etiquetas.lbTitMsgResultados,
            emptyMsg: perfil.etiquetas.lbTitMsgNingunresultadoparamostrar
        })
    });
////------------ Trabajo con el PagingToolbar ------------////
    Ext.getCmp('ptbaux').on('change', function() {
        sm.selectFirstRow();
    }, this);
////---------- Renderiar el arbol ----------////
    var panel = new Ext.Panel({
        layout: 'border',
        title: 'Cat&aacute;logo de scripts',
        items: [gpRol],
        tbar: [btnScript, btnEliminar, btnDescargar]

    });

    var vpGestSistema = new Ext.Viewport({
        layout: 'fit',
        items: panel
    });
    stGpRol.reload();

    regSc = new Ext.FormPanel({
        hideLabels: true,
        bodyStyle: 'padding:5px 5px 0',
        frame: true,
        items: [{
                xtype: 'textarea',
                id: 'textarea',
                name: 'script',
                anchor: '100%',
                width: 800,
                height: 495,
                readOnly: true
            }]
    });

    winScript = new Ext.Window({
        modal: true,
        closeAction: 'hide',
        resizable: true,
        layout: 'fit',
        title: 'Script',
        width: 800,
        height: 550
    });

    function mostrarScript() {
        obtenerScript();
        winScript.show();
    }

    function obtenerScript() {
        Ext.Ajax.request({
            url: 'verScript',
            method: 'POST',
            params: {
                id: gpRol.getSelectionModel().getSelected().data.id_script,
                tipo: gpRol.getSelectionModel().getSelected().data.id_tiposcript
            },
            success: function(response, opts) {
                Ext.getCmp('textarea').setValue(Ext.decode(response.responseText));
            }
        });
    }

    winScript.add(regSc);

    function eliminar() {
        mostrarMensaje(2, '&iquest;Est&aacute; seguro de que desea eliminar el script?', elimina);

        function elimina(btnPresionado) {
            if (btnPresionado === 'ok') {
                for (var i = 0; i < gpRol.getSelectionModel().getSelections().length; i++) {
                    arr.push(gpRol.getSelectionModel().getSelections()[i].id);
                }
                Ext.Ajax.request({
                    url: 'eliminar',
                    waitMsg: 'Espere por favor...',
                    method: 'POST',
                    params: {
                        seleccion: Ext.util.JSON.encode(arr)
                    },
                    callback: function(options, success, response) {
                        btnEliminar.disable();
                        btnScript.disable();
                        btnDescargar.disable();
                        stGpRol.reload();
                        arr = new Array();
                    }
                });
            }
        }
    }

    function descargar() {
        Ext.Ajax.request({
            url: 'descargar',
            params: {
                paquete: gpRol.getSelectionModel().getSelected().data.nombre_paquete,
                id: gpRol.getSelectionModel().getSelected().data.id_script,
                tipo: gpRol.getSelectionModel().getSelected().data.id_tiposcript
            },
            success: function() {
               window.open("descargar?paquete=" + arbolSistema.getSelectionModel().getSelectedNode().parentNode.text);
            }
        });
    }

}
