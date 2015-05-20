var perfil = window.parent.UCID.portal.perfil;
UCID.portal.cargarEtiquetas('gestdocxestudiante', function () {
    cargarInterfaz();
});

////------------ Inicializo el singlenton QuickTips ------------////
Ext.QuickTips.init();


function cargarInterfaz() {
    ////------------ Botones ------------////
    var btnAsociarDocRequerido = Ext.create('Ext.Button', {
        id: 'btnAsociarDocRequerido',
        text: '<b>' + perfil.etiquetas.lbBtnAsociar + '</b>',
        icon: perfil.dirImg + 'adicionar.png',
        iconCls: 'btn',
        handler: function () {
            mostFormDocRequerido('add');
        }
    });

    var btnEliminarDocRequerido = Ext.create('Ext.Button', {
        id: 'btnEliDocRequerido',
        text: '<b>' + perfil.etiquetas.lbBtnEliminar + '</b>',
        disabled: true,
        icon: perfil.dirImg + 'eliminar.png',
        iconCls: 'btn',
        handler: function () {
            EliminarDocRequerido();
        }
    });

    UCID.portal.cargarAcciones(window.parent.idFuncionalidad);

    ////------------ Store del Grid de documento requerido ------------////
    var stGpDocRequerido = new Ext.data.Store({
        fields: [
            {
                name: 'iddocumentorequerido'
            },
            {
                name: 'idusuario'
            },
            {
                name: 'fecha'
            },
            {
                name: 'descripcion'
            },
            {
                name: 'estado'
            }
        ],
        remoteSort: true,
        pageSize: 20,
        proxy: {
            type: 'ajax',
            url: 'cargarDocRequeridoEstudiante',
            reader: {
                totalProperty: "cantidad",
                root: "datos"
            },
            actionMethods: {
                read: 'POST'
            }
        }
    });

    ////------------ Store del Grid de estudiantes ------------////
    var stGpEstudiante = new Ext.data.Store({
        fields: [
            {
                name: 'idestudiante'
            },
            {
                name: 'nombre'
            },
            {
                name: 'apellidos'
            },
            {
                name: 'codigo'
            },
            {
                name: 'fecha'
            },
            {
                name: 'idusuario'
            }
        ],
        remoteSort: true,
        pageSize: 20,
        proxy: {
            type: 'ajax',
            url: '../gestestudiantes/cargarEstudiantes',
            reader: {
                totalProperty: "cantidad",
                root: "datos"
            },
            actionMethods: {
                read: 'POST'
            }
        }
    });

    ////------------ Establesco modo de seleccion de grid de estudiantes ------------////
    var smEstudiantes = new Ext.selection.RowModel({
        mode: 'SINGLE',
        listeners: {
            selectionchange: function (smodel, rowIndex, keepExisting, record) {

            }
        }
    });

    smEstudiantes.on('select', function (smodel, rowIndex, keepExisting, record) {
        GpDocRequerido.enable();
        stGpDocRequerido.load({params: {idestudiante: smEstudiantes.getSelection()[0].raw.idestudiante}});
        stGpDocumentosRequeridos.load({params: {idestudiante: smEstudiantes.getSelection()[0].raw.idestudiante}});
    }, this);

    //grid de estudiantes
    var GpEstudiantes = new Ext.grid.GridPanel({
        title: 'Alumnos registrados',
        store: stGpEstudiante,
        frame: true,
        selModel: smEstudiantes,
        columns: [
            {
                text: 'idestudiante',
                flex: 1,
                dataIndex: 'idestudiante',
                hidden: true
            },
            {
                text: 'Apellidos',
                flex: 1,
                dataIndex: 'apellidos'
            },
            {
                text: 'Nombre',
                flex: 1,
                dataIndex: 'nombre'
            },
            {
                text: 'C&oacute;digo',
                flex: 1,
                dataIndex: 'codigo'
            },
            {
                text: 'Usuario',
                flex: 1,
                dataIndex: 'idusuario'
            },
            {
                text: 'Fecha',
                flex: 1,
                dataIndex: 'fecha'
            }
        ],
        bbar: new Ext.PagingToolbar({
            id: 'ptbaux9',
            store: stGpEstudiante,
            displayInfo: true,
            displayMsg: perfil.etiquetas.lbMsgbbarI,
            emptyMsg: perfil.etiquetas.lbMsgbbarII
        })
    });
    stGpEstudiante.load();

    var pnlEstudiante = Ext.create('Ext.Panel', {
        id: 'pnlEstudiante',
        frame: true,
        layout: 'fit',
        region: 'center',
        width: 500,
        items: GpEstudiantes
    });

    ////------------ Establesco modo de seleccion de grid de documentos requeridos ------------////
    var smDocRequeridos = new Ext.selection.RowModel({
        mode: 'MULTI',
        listeners: {
            selectionchange: function (smodel, rowIndex, keepExisting, record) {
                btnEliminarDocRequerido.enable(smodel.getCount() > 0);
            }
        }
    });

    //grid de documentos requeridos
    var GpDocRequerido = new Ext.grid.GridPanel({
        title: 'Documentos por alumno',
        store: stGpDocRequerido,
        disabled: true,
        frame: true,
        selModel: smDocRequeridos,
        columns: [
            {
                text: 'iddocumentorequerido',
                flex: 1,
                dataIndex: 'iddocumentorequerido',
                hidden: true
            },
            {
                text: 'Descripción',
                flex: 1,
                dataIndex: 'descripcion'
            },
            {
                text: 'Usuario',
                flex: 1,
                dataIndex: 'idusuario'
            },
            {
                text: 'Fecha',
                flex: 1,
                dataIndex: 'fecha'
            },
            {
                text: 'Estado',
                flex: 1,
                dataIndex: 'estado',
                hidden: true
            }
        ],
        tbar: [btnAsociarDocRequerido, btnEliminarDocRequerido],
        bbar: new Ext.PagingToolbar({
            id: 'ptbaux99',
            store: stGpDocRequerido,
            displayInfo: true,
            displayMsg: perfil.etiquetas.lbMsgbbarI,
            emptyMsg: perfil.etiquetas.lbMsgbbarII
        })
    });

    var pnlDocRequerido = Ext.create('Ext.Panel', {
        id: 'pnlDocRequerido',
        frame: true,
        layout: 'fit',
        region: 'east',
        width: 700,
        items: GpDocRequerido
    });

    var general = Ext.create('Ext.panel.Panel', { layout: 'border', items: [pnlEstudiante, pnlDocRequerido]});
    var vpGestSistema = Ext.create('Ext.Viewport', {layout: 'fit', items: general});

    //formulario
    var winAdicionarDocRequerido;

    ////------------ Establesco modo de seleccion de grid de todos los documentos requeridos ------------////
    var smDocumentosRequeridos = new Ext.selection.RowModel({
        mode: 'MULTI',
        listeners: {
            selectionchange: function (smodel, rowIndex, keepExisting, record) {

            }
        }
    });

    ////------------ Store del Grid de todos los documentos requeridos ------------////
    var stGpDocumentosRequeridos = new Ext.data.Store({
        fields: [
            {
                name: 'iddocumentorequerido'
            },
            {
                name: 'idusuario'
            },
            {
                name: 'fecha'
            },
            {
                name: 'descripcion'
            }
        ],
        remoteSort: true,
        pageSize: 20,
        proxy: {
            type: 'ajax',
            url: 'cargarDocReqDist',
            reader: {
                totalProperty: "cantidad",
                root: "datos"
            },
            actionMethods: {
                read: 'POST'
            }
        }
    });

    //grid de documentos requeridos
    var GpDocumentosRequeridos = new Ext.grid.GridPanel({
        store: stGpDocumentosRequeridos,
        frame: true,
        //layout: 'fit',
        selModel: smDocumentosRequeridos,
        columns: [
            {
                text: 'iddocumentorequerido',
                flex: 1,
                dataIndex: 'iddocumentorequerido',
                hidden: true
            },
            {
                text: 'Descripción',
                flex: 1,
                dataIndex: 'descripcion'
            },
            {
                text: 'Usuario',
                flex: 1,
                dataIndex: 'idusuario'
            },
            {
                text: 'Fecha',
                flex: 1,
                dataIndex: 'fecha'
            }
        ],
        bbar: new Ext.PagingToolbar({
            id: 'ptbaux11',
            store: stGpDocumentosRequeridos,
            displayInfo: true,
            displayMsg: perfil.etiquetas.lbMsgbbarI,
            emptyMsg: perfil.etiquetas.lbMsgbbarII
        })
    });

    var formDocumentosRequeridos = Ext.create('Ext.Panel', {
        layout: 'fit',
        frame: true,
        bodyStyle: 'padding:5px 5px 0',
        items: GpDocumentosRequeridos

    });

    function mostFormDocRequerido() {
        winAdicionarDocRequerido = Ext.create('Ext.Window', {
            title: perfil.etiquetas.lbTitVentanaTitI,
            closeAction: 'hide',
            //resize:false,
            width: 600,
            height: 460,
            constrain: true,
            layout: 'fit',
            buttons: [
                {
                    text: perfil.etiquetas.lbBtnCancelar,
                    icon: perfil.dirImg + 'cancelar.png',
                    tabIndex: 13,
                    handler: function () {
                        winAdicionarDocRequerido.hide();
                    }
                },
                {
                    text: perfil.etiquetas.lbBtnAplicar,
                    icon: perfil.dirImg + 'aplicar.png',
                    tabIndex: 12,
                    handler: function () {
                        AdicionarDocRequerido();
                    }
                },
                {
                    text: perfil.etiquetas.lbBtnAceptar,
                    icon: perfil.dirImg + 'aceptar.png',
                    tabIndex: 11,
                    handler: function () {
                        AdicionarDocRequerido();
                        winAdicionarDocRequerido.hide();
                    }
                }
            ]
        });

        winAdicionarDocRequerido.add(formDocumentosRequeridos);
        winAdicionarDocRequerido.doLayout();
        winAdicionarDocRequerido.show();

    }

    // funciones

    function AdicionarDocRequerido() {
        var iddocsReqs = new Array();
        for (var i = 0; i < smDocumentosRequeridos.getCount(); i++) {
            iddocsReqs.push(smDocumentosRequeridos.getSelection()[i].raw.iddocumentorequerido);
        }

        Ext.Ajax.request({
            url: 'insertarDocRequeridoEstudiante',
            method: 'POST',
            params: {
                iddocumentorequeridos: Ext.JSON.encode(iddocsReqs),
                idestudiante: smEstudiantes.getSelection()[0].raw.idestudiante
            },
            callback: function (options, success, response) {
                stGpDocRequerido.load({params: {idestudiante: smEstudiantes.getSelection()[0].raw.idestudiante}});
                stGpDocumentosRequeridos.load({params: {idestudiante: smEstudiantes.getSelection()[0].raw.idestudiante}});
            }
        })
    }

    function EliminarDocRequerido() {
        mostrarMensaje(2, '¿Desea eliminar esta asociaci&oacute;n?', eliminar);
        var delMask = new Ext.LoadMask(Ext.getBody(), {
            msg: 'Eliminando asociaci&oacute;n...'
        });

        function eliminar(btnPresionado) {
            if (btnPresionado === 'ok') {
                var ids = new Array();
                for (var i = 0; i < smDocRequeridos.getCount(); i++) {
                    ids.push(smDocRequeridos.getSelection()[i].raw.iddocumentorequerido);
                }

                delMask.show();
                Ext.Ajax.request({
                    url: 'eliminarDocRequeridoEstudiante',
                    method: 'POST',
                    params: {
                        iddocumentorequeridos: Ext.JSON.encode(ids),
                        idestudiante: smEstudiantes.getSelection()[0].raw.idestudiante
                    },
                    callback: function (options, success, response) {
                        delMask.hide();
                        stGpDocRequerido.load({params: {idestudiante: smEstudiantes.getSelection()[0].raw.idestudiante}});
                        stGpDocumentosRequeridos.load({params: {idestudiante: smEstudiantes.getSelection()[0].raw.idestudiante}});
                        btnEliminarDocRequerido.disable();
                    }
                });
            }
        }
    }
}
