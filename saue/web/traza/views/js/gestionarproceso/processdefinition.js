var descripcionTest = /^([a-zA-ZáéíóúñüÑ]+[a-zA-ZáéíóúñüÑ\d\.\-\_ ]*)+$/;
var modificarprocesoname;
Ext.apply(Ext.form.field.VTypes, {
    // vtype validation function
    descripcion: function(val, field) {
        return descripcionTest.test(val);
    },
    // vtype Text property: The error text to display when the validation
    // function returns false
    descripcionTest: 'Not a valid time.  Must be in the format "12:34 PM".',
    // vtype Mask property: The keystroke filter mask
    descripcionMask: /[\w\s]/i
});
processdefinition = Ext
        .extend(
        Ext.Window,
        {
            title: 'Definir proceso',
            width: 700,
            height: 280,
            columnResize: true,
            closable: false,
            layout: 'border',
            closeAction: 'hide',
            modal: true,
            initComponent: function() {

                /*
                 * this.smprodefinition = new
                 * Ext.grid.CheckboxSelectionModel({ width: 25, scope:
                 * this });
                 */

                var smprodefinition = Ext.create(
                        'Ext.selection.CheckboxModel', {
                    mode: 'SINGLE',
                    showHeaderCheckbox: false
                });

                smprodefinition.on('selectionchange', function() {
                    if (smprodefinition.getSelection().length > 0)
                        btnCreateProject.enable();
                    else
                        btnCreateProject.disable();
                })

                txtFilterTdefinition = new Ext.form.TextField({
                    enableKeyEvents: true
                })

                var btnCreateProject = new Ext.Button({
                    icon: perfil.dirImg + 'aceptar.png',
                    text: 'Aceptar',
                    disabled: true

                });

                this.btnCancel = new Ext.Button({
                    icon: perfil.dirImg + 'cancelar.png',
                    text: 'Cancelar'
                })

                txtFilterTdefinition.on('keyup', function(tf) {
                    sttablesprodefinition.clearFilter(true);
                    if (tf.getValue()) {
                        sttablesprodefinition.filter('name', tf.getValue());
                    }
                }, this)

                this.btnCancel.setHandler(function() {
                    this.hide();
                    /*Ext.getCmp('name').reset();
					Ext.getCmp('fdatos').reset();
					Ext.getCmp('descripcion').reset();*/
                }, this);

                btnCreateProject
                        .setHandler(
                        function() {
                            if (!Ext.getCmp('formulario')
                                    .getForm().isValid()) {
                                Ext.MessageBox
                                        .show({
                                    title: 'Error',
                                    msg: 'Por favor verifique, existen campos con valores incorrectos.',
                                    icon: Ext.MessageBox.ERROR,
                                    buttons: Ext.MessageBox.OK
                                })
                            } else
								/*Ext.getCmp('name').reset();
								Ext.getCmp('fdatos').reset();
								Ext.getCmp('descripcion').reset();*/
                                saveprocessdefinition(smprodefinition
                                        .getSelection());
                        }, this);
                sttablesprodefinition = new Ext.data.Store({
                    fields: [{
                            name: 'name',
                            type: 'string'
                        }

                    ],
                    proxy: {
                        type: 'ajax',
                        url: 'getalltables',
                        actionMethods: {// Esta Linea es necesaria
                            // para el metodo de llamada
                            // POST o GET
                            read: 'POST'
                        },
                        reader: {
                            totalProperty: "cantidad_filas",
                            root: "datos",
                            id: "name"
                        }
                    },
                    autoLoad: true,
                    listeners: {
                        'load': function(a, b) {
                            sttablesprodefinition.clearFilter(true);
                            sttablesprodefinition.filter('name', '');
                            txtFilterTdefinition.setValue('');
                        }
                    }
                });
                sttablesprodefinition.on('load', function() {
                    if (actionproceso == "modificar") {
                        var indice = sttablesprodefinition.find('name',
                                instancia);
                        if (indice != -1)
                            smprodefinition.select(indice);
                    }

                })

                this.buttons = [this.btnCancel, btnCreateProject]

                this.items = [
                    {
                        xtype: 'form',
                        id: 'formulario',
                        title: '',
                        frame: true,
                        region: 'center',
                        items: [
                            {
                                xtype: 'textfield',
                                fieldLabel: 'Nombre del proceso',
                                anchor: '100%',
                                id: 'name',
                                allowBlank: false,
                                blankText: 'Debe ingresar el nombre del proceso.'
                            },
                            {
                                xtype: 'textfield',
                                fieldLabel: 'Fuente de datos',
                                anchor: '100%',
                                allowBlank: false,
                                blankText: 'Debe ingresar la fuente de datos.',
                                id: 'fdatos'
                            },
                            {
                                xtype: 'textarea',
                                fieldLabel: 'Descripción',
                                anchor: '100%',
                                id: 'descripcion'
                            }

                        ]

                    },
                    {
                        xtype: 'grid',
                        title: 'Seleccione la instancia de proceso',
                        autoScroll: true,
                        columnResize: true,
                        region: 'east',
                        selModel: smprodefinition,
                        store: Ext.data.StoreManager
                                .lookup(sttablesprodefinition),
                        tbar: ['->', 'Filtro: ', txtFilterTdefinition],
                        columns: [{
                                text: 'Nombre',
                                width: 302,
                                dataIndex: 'name'
                            }],
                        height: 302
                    }

                ];

                processdefinition.superclass.initComponent.call(this);

            }

        });

function saveprocessdefinition(iselectedtables) {

    var index = stproceso.find('name', Ext.getCmp('name').getValue(), 0, false,
            false, true);
    if ((index != -1 && actionproceso == "adicionar")
            || (modificarprocesoname != Ext.getCmp('name').getValue() && index != -1)) {
        Ext.MessageBox.show({
            title: 'Proceso',
            msg: 'Ya existe un proceso con ese nombre.',
            icon: Ext.MessageBox.ERROR,
            buttons: Ext.MessageBox.OK
        });

    } else {

        var selectedtables;
        selectedtables = iselectedtables[0].data.name;

        Ext.Ajax
                .request({
            url: 'processdefinition',
            method: 'POST',
            params: {
                actionproceso: actionproceso,
                selectedtables: selectedtables,
                Nproceso: Ext.getCmp('name').getValue(),
                fdatos: Ext.getCmp('fdatos').getValue(),
                descripcion: Ext.getCmp('descripcion').getValue()
            },
            callback: function(options, success, response) {
                responseData = Ext.decode(response.responseText);
                if (responseData.processadd == 1) {
                    Ext.MessageBox
                            .show({
                        title: 'Proceso',
                        msg: 'Proceso ' + responseData.actionAddMod + ' satisfactoriamente.',
                        icon: Ext.MessageBox.INFO,
                        buttons: Ext.MessageBox.OK
                    });
                    stproceso.reload();
                    modProceso.disable();
                    eliProceso.disable();
                    booladdmodproce = true;
                    /*Ext.getCmp('name').reset();
                    Ext.getCmp('fdatos').reset();
                    Ext.getCmp('descripcion').reset();*/
                    event.disable();

                } else {
                    Ext.MessageBox
                            .show({
                        title: 'Proceso',
                        msg: 'Error ' + responseData.actionAddMod + ' el Proceso.',
                        icon: Ext.MessageBox.ERROR,
                        buttons: Ext.MessageBox.OK
                    })
                }
            }
        });

        this.processdefinition.hide();
    }
}
