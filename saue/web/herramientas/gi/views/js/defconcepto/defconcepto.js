var perfil = window.parent.UCID.portal.perfil;
UCID.portal.cargarEtiquetas('defconcepto', function () {
    cargarInterfaz();
});

////------------ Inicializo el singlenton QuickTips ------------////
Ext.QuickTips.init();
var gen, componente;
var cont = 0;
var otro, seleccionado;
var atrib = new Array();
var comp = new Array();
function cargarInterfaz() {
    componente = Ext.create('Ext.form.field.ComboBox', {//combo para agregar componentes
        id: 'cmp',
        fieldLabel: 'Componente',
        editable: false,
        draggable: true,
        store: ['Arbol', 'Checkbox', 'Combobox', 'Datefield', 'Radio', 'TextArea', 'Textfield'],
        name: 'componentes',
        /* listeners:{
         'select': function(){
         if(this.getValue()=='Arbol' || this.getValue()=='Combobox'){
         Ext.getCmp('d').setVisible(true);

         }
         else{
         Ext.getCmp('d').setVisible(false);
         }

         }
         },*/
        emptyText: '--seleccione--'
    });
    var simple = Ext.create('Ext.form.Panel', {//panel para generar la interfaz
        frame: true,
        bodyStyle: 'padding:5px 5px 0',
        width: 350,
        fieldDefaults: {
            msgTarget: 'side',
            labelWidth: 75
        },
        defaultType: 'textfield',
        defaults: {
            anchor: '100%'
        },

        items: [
            {
                fieldLabel: 'Concepto',
                id: 'cc',
                name: 'concepto'
            },
            {
                fieldLabel: 'url Grid',
                hidden: true,
                id: 'd',
                name: 'dir'
            },
            {
                fieldLabel: 'url Arbol',
                hidden: true,
                id: 't',
                name: 'dirA'
            },
            {
                fieldLabel: 'Atributos',
                //hidden:true,
                id: 'aa',
                name: 'atributos'
            },
            componente
        ]


    });

    function mostForm(opcion) {//funcion para mostrar el formulario
        switch (opcion) {
            case 'gen':
            {
                if (!gen) {
                    gen = Ext.create('Ext.Window', {
                        title: 'Concepto',
                        closeAction: 'hide',
                        width: 500,
                        height: 250,
                        x: 220,
                        y: 100,
                        constrain: true,
                        layout: 'fit',
                        //items: simple,
                        buttons: [
                            {
                                text: 'Agregar atributo',
                                icon: perfil.dirImg + 'aplicar.png',
                                handler: function () {
                                    if (Ext.getCmp('aa').getValue() == "" || Ext.getCmp('cmp').getValue() == null) {
                                        Ext.MessageBox.show({
                                            msg: "Los campos atributo y componente no pueden quedar vacíos",
                                            buttons: Ext.MessageBox.OK,
                                            icon: Ext.MessageBox.ERROR
                                        });
                                    }
                                    else {
                                        atrib[cont] = Ext.getCmp('aa').getValue();
                                        comp[cont] = Ext.getCmp('cmp').getValue();
                                        cont = cont + 1;
                                        Ext.getCmp('cmp').setValue(null);
                                        Ext.getCmp('aa').setValue("");
                                        //	Ext.getCmp('d').setVisible(false);
                                    }
                                }
                            },
                            {
                                text: 'Guardar',
                                icon: perfil.dirImg + 'aceptar.png',
                                handler: function () {
                                    if (Ext.getCmp('cc').getValue() == "") {
                                        Ext.MessageBox.show({
                                            msg: "El campo concepto no puede quedar vacío",
                                            buttons: Ext.MessageBox.OK,
                                            icon: Ext.MessageBox.ERROR
                                        });
                                    }
                                    else {
                                        if (seleccionado == 'CRUD-Simple') {
                                            Ext.Ajax.request({
                                                url: 'crearConcepto',
                                                method: 'POST',
                                                params: {
                                                    concepto: Ext.getCmp('cc').getValue(),
                                                    plantilla: seleccionado,
                                                    urlG: Ext.getCmp('d').getValue(),
                                                    atributo: Ext.JSON.encode(atrib),
                                                    componentes: Ext.JSON.encode(comp)
                                                },
                                                success: function (aa) {
                                                    Ext.MessageBox.show({
                                                        msg: aa.responseText,
                                                        buttons: Ext.MessageBox.OK,
                                                        icon: Ext.MessageBox.INFO
                                                    });
                                                    //alert(aa.responseText)
                                                }
                                            });
                                        }
                                        else {
                                            Ext.Ajax.request({
                                                url: 'crearConcepto',
                                                method: 'POST',
                                                params: {
                                                    concepto: Ext.getCmp('cc').getValue(),
                                                    plantilla: seleccionado,
                                                    urlG: Ext.getCmp('d').getValue(),
                                                    urlA: Ext.getCmp('t').getValue(),
                                                    atributo: Ext.JSON.encode(atrib),
                                                    componentes: Ext.JSON.encode(comp)
                                                },
                                                success: function (aa) {
                                                    Ext.MessageBox.show({
                                                        msg: aa.responseText,
                                                        buttons: Ext.MessageBox.OK,
                                                        icon: Ext.MessageBox.INFO
                                                    });
                                                    //alert(aa.responseText)
                                                }
                                            });
                                        }

                                        Ext.getCmp('cmp').setValue(null);
                                        Ext.getCmp('aa').setValue("");
                                        Ext.getCmp('cc').setValue("");
                                        Ext.getCmp('d').setValue("");
                                        Ext.getCmp('t').setValue("");
                                        Ext.getCmp('d').setVisible(false);
                                        Ext.getCmp('t').setVisible(false);
                                        cont = 0;
                                        gen.hide();
                                    }
                                }
                            },
                            {
                                text: 'Cancelar',
                                icon: perfil.dirImg + 'cancelar.png',
                                handler: function () {
                                    Ext.getCmp('cmp').setValue(null);
                                    Ext.getCmp('aa').setValue("");
                                    Ext.getCmp('cc').setValue("");
                                    Ext.getCmp('d').setValue("");
                                    Ext.getCmp('t').setValue("");
                                    Ext.getCmp('d').setVisible(false);
                                    Ext.getCmp('t').setVisible(false);
                                    cont = 0;
                                    gen.hide();

                                }
                            }
                        ]
                    });
                    gen.add(simple);

                }
                if (seleccionado == 'CRUD-Simple') {
                    Ext.getCmp('d').setVisible(true);
                }
                else {
                    Ext.getCmp('d').setVisible(true);
                    Ext.getCmp('t').setVisible(true);
                }
                gen.show();

            }
                break;
        }
    }

    var btnConcepto = Ext.create('Ext.Button', {//boton generar
        text: 'Crear concepto',
        // renderTo: Ext.getBody(),
        disabled: true,
        icon: perfil.dirImg + 'generardocumentos.png',
        iconCls: 'btn',
        handler: function () {
            mostForm('gen');
            /*Ext.Ajax.request({
             url:'genInterfaz',
             success: function(){
             alert('aaaaaa')
             }
             });*/
        }
    });
    ImageModel = Ext.define('ImageModel', {//utilizado para cargar las imagenes
        extend: 'Ext.data.Model',
        fields: [
            {name: 'name'},
            {name: 'url'},
            {name: 'size', type: 'float'},
            {name: 'lastmod', type: 'date', dateFormat: 'timestamp'}
        ]
    });
    store = Ext.create('Ext.data.Store', {//utilizado para cargar las imagenes
        model: 'ImageModel',
        proxy: {
            type: 'ajax',
            url: 'cargarimagen',
            reader: {
                type: 'json',
                root: 'images'
            }
        }
    });
    store.load();
    var g = Ext.create('Ext.Panel', { //subpanel general
        id: 'images-view',
        frame: true,
        region: 'center',
        width: 750,
        items: Ext.create('Ext.view.View', {
            store: store,
            tpl: [
                '<tpl for=".">',
                '<div class="thumb-wrap" id="{name}">',
                '<div class="thumb"><img src="{url}" title="{name}"></div>',
                '<span class="x-editable">{shortName}</span></div>',
                '</tpl>',
                '<div class="x-clear"></div>'
            ],
            multiSelect: false,
            height: 600,
            trackOver: true,
            overItemCls: 'x-item-over',
            itemSelector: 'div.thumb-wrap',
            emptyText: 'No hay imagen',


            prepareData: function (data) {
                Ext.apply(data, {
                    shortName: Ext.util.Format.ellipsis(data.name, 25),
                    sizeString: Ext.util.Format.fileSize(data.size),
                    dateString: Ext.util.Format.date(data.lastmod, "m/d/Y g:i a")
                });
                return data;
            },
            listeners: {
                selectionchange: function (dv, nodes) {
                    var nombre = nodes[0].data.name;
                    seleccionado = nombre;
                    btnConcepto.enable();
                    //alert(seleccionado);
                    otro.load({
                        params: {
                            node: nombre
                        }
                    })

                }
            }
        })
    });
    otro = Ext.create('Ext.data.Store', {//utilizado para mostrar la imagen seleccionada
        model: 'ImageModel',

        proxy: {
            type: 'ajax',
            url: 'loadPreview',

            reader: {
                type: 'json',
                root: 'images'
            }
        }
    });
    var ok = Ext.create('Ext.Panel', { //utilizado para mostrar la imagen seleccionada
        // title: 'Selector de interfaz',
        id: 'images-view1',
        frame: true,
        region: 'east',
        // collapsible: true,
        width: 290,
        items: Ext.create('Ext.view.View', {
            store: otro,
            tpl: [
                '<tpl for=".">',
                '<div class="thumb-wrap1" id="{name}">',
                '<div class="thumb1"><img src="{url}" title="{name}"></div>',
                '<span class="x-editable">{shortName}</span></div>',
                '</tpl>',
                '<div class="x-clear"></div>'
            ],
            multiSelect: false,
            height: 600,
            trackOver: true,
            overItemCls: 'x-item-over',
            itemSelector: 'div.thumb-wrap1',
            emptyText: 'No hay imagen',


            prepareData: function (data) {
                Ext.apply(data, {
                    shortName: Ext.util.Format.ellipsis(data.name, 15),
                    sizeString: Ext.util.Format.fileSize(data.size),
                    dateString: Ext.util.Format.date(data.lastmod, "m/d/Y g:i a")
                });
                return data;
            }

        })
    });

    var panel = Ext.create('Ext.panel.Panel', {
        //title:'Concepto',
        tbar: [btnConcepto],
        items: [g, ok],
        layout: 'border'
    });


    var vpGestSistema = Ext.create('Ext.Viewport', {
        layout: 'fit',
        items: panel
    });


}
