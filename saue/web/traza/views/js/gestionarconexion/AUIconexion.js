var winconexion1;
///////validaciones
letrasnumeros = /(^([a-zA-ZáéíóúñÑ])+([a-zA-ZáéíóúñÑ\d\_\s]*))$/; 
var puertoTest = /^(([1-9][0-9][0-9][0-9])|([1-9][0-9][0-9])|([1-9][0-9])|([0-9])|default)$/i;
Ext.apply(Ext.form.field.VTypes, {
//  vtype validation function
    puerto: function(val, field) {
        return puertoTest.test(val);
    },
    // vtype Text property: The error text to display when the validation function returns false
    puertoTest: 'Not a valid time.  Must be in the format "12:34 PM".',
    // vtype Mask property: The keystroke filter mask
    puertoMask: /[\d\s]/i
});
var serverTest = /^((([1][0][.])(([1-2][0-9][0-9][.])|([1-9][0-9][.])|([0-9][.]))(([1-2][0-9][0-9][.])|([1-9][0-9][.])|([0-9][.]))(([1-2][0-9][0-9])|([1-9][0-9])|([0-9])))|default|localhost|127.0.0.1)$/i;
Ext.apply(Ext.form.field.VTypes, {
//  vtype validation function
    server: function(val, field) {
        return serverTest.test(val);
    },
    // vtype Text property: The error text to display when the validation function returns false
    serverTest: 'Not a valid time.  Must be in the format "12:34 PM".',
    // vtype Mask property: The keystroke filter mask
    serverMask: /[\d\s.default127.0.0.1localhost]/i
});


var btnAplicar;
var btnProbarconexion = new Ext.Button({
    text: 'Probar conexión',
    region: 'center',
    aling: 'center',
    layout: 'center'

})
var UIconexion1 = Ext.extend(Ext.Window, {
    title: 'Definir conexión',
    region: 'center',
    width: 300,
    height: 275,
    closable: false,
    modal: true,
    initComponent: function() {


        winconexion1 = new Ext.form.Panel({
            title: '',
            bodyStyle: 'padding:5px 5px 0;background-color:#dfe9f6',
            renderTo: Ext.getBody(),
            region: 'center',
            buttons: [btnProbarconexion, {xtype: 'tbspacer', width: 75}],
            items: [
                {
                    xtype: 'hidden',
                    fieldLabel: 'Id',
                    id: 'id',
                    anchor: '100%'
                },
                {
                    xtype: 'textfield',
                    fieldLabel: 'Nombre',
                    id: 'name',
                    anchor: '100%',
                    width: 442,
                    allowBlank: false,
                    blankText: 'Debe ingresar el nombre.',
                    vtype: 'alphanum',
                    vtypeText: 'Nombre inválido'
                },
                {
                    xtype: 'textfield',
                    fieldLabel: 'Servidor',
                    id: 'host',
                    value: 'default',
                    anchor: '100%',
                    width: 442,
                    allowBlank: false,
                    blankText: 'Debe ingresar el IP.',
                    vtype: 'server',
                    vtypeText: 'Servidor inválido'


                },
                {
                    xtype: 'textfield',
                    fieldLabel: 'Base de datos',
                    id: 'db',
                    value: 'default',
                    anchor: '100%',
                    allowBlank: false,
                    blankText: 'Debe ingresar una base de datos.',

                },
                {
                    xtype: 'textfield',
                    fieldLabel: 'Puerto',
                    id: 'port',
                    anchor: '100%',
                    allowBlank: false,
                    blankText: 'Debe ingresar el puerto.',
                    vtype: 'puerto',
                    vtypeText: 'Puerto inválido'
                },
                {
                    xtype: 'textfield',
                    fieldLabel: 'Usuario',
                    id: 'user',
                    anchor: '100%',
                    allowBlank: false,
                    blankText: 'Debe ingresar el usuario.',
                    regex:letrasnumeros

                },
                {
                    xtype: 'textfield',
                    fieldLabel: 'Contrase&ntilde;a',
                    inputType: 'password',
                    id: 'pass',
                    anchor: '100%',
                    width: 451,
                    allowBlank: false,
                    blankText: 'Debe ingresar la contrase&ntilde;a.'

                }



            ]
        })



        Ext.getCmp('host').on('focus', function() {
            if (host && interface == "add")
                Ext.getCmp('host').setValue(), host = false;
        }, this)

        Ext.getCmp('db').on('focus', function() {
            if (db && interface == "add")
                Ext.getCmp('db').setValue(), db = false;
        }, this)

        Ext.getCmp('port').on('focus', function() {
            if (port && interface == "add")
                Ext.getCmp('port').setValue(), port = false;
        }, this)



        btnAplicar = new Ext.Button({
            icon: perfil.dirImg + 'aplicar.png',
            text: 'Aplicar'

        })

        this.btnAceptar = new Ext.Button({
            icon: perfil.dirImg + 'aceptar.png',
            text: 'Aceptar'

        })

        btnProbarconexion.setHandler(function() {

            if (Ext.getCmp('name').getValue() == "" ||
                    Ext.getCmp('user').getValue() == "" ||
                    Ext.getCmp('pass').getValue() == "" ||
                    Ext.getCmp('port').getValue() == "" ||
                    Ext.getCmp('host').getValue() == "" ||
                    Ext.getCmp('db').getValue() == "") {

                Ext.MessageBox.show({
                    title: 'Definir conexión',
                    msg: 'Debe completar todos los campos.',
                    icon: Ext.MessageBox.ERROR,
                    buttons: Ext.MessageBox.OK
                })

            }
            else {
                Ext.Ajax.request({
                    url: 'connect',
                    method: 'POST',
                    params: {
                        name: Ext.getCmp('name').getValue(),
                        user: Ext.getCmp('user').getValue(),
                        passwd: Ext.getCmp('pass').getValue(),
                        port: Ext.getCmp('port').getValue(),
                        host: Ext.getCmp('host').getValue(),
                        db: Ext.getCmp('db').getValue()
                    },
                    callback: function(options, success, response) {
                        responseData = Ext.decode(response.responseText);
                        if (responseData.conectado == 1)
                        {
                            message("Conexión", "Conexión establecida");
                        }
                        else {
                            Ext.MessageBox.show({
                                title: 'Conexión',
                                msg: 'Conexión fallida.',
                                icon: Ext.MessageBox.ERROR,
                                buttons: Ext.MessageBox.OK
                            })
                        }
                    }

                });
            }


        }, this)

        this.btnCancel = new Ext.Button({
            icon: perfil.dirImg + 'cancelar.png',
            text: 'Cancelar'
        })
        this.btnCancel.setHandler(function() {
            UIconexion1.hide();
            Resettext();
        }, this)

        this.btnAceptar.setHandler(function() {

            if (Ext.getCmp('name').getValue() == "" ||
                    Ext.getCmp('user').getValue() == "" ||
                    Ext.getCmp('pass').getValue() == "" ||
                    Ext.getCmp('port').getValue() == "" ||
                    Ext.getCmp('host').getValue() == "" ||
                    Ext.getCmp('db').getValue() == "") {

                Ext.MessageBox.show({
                    title: 'Definir conexión',
                    msg: 'Debe completar todos los campos.',
                    icon: Ext.MessageBox.ERROR,
                    buttons: Ext.MessageBox.OK
                })
                return ;

            }
            if (!winconexion1.getForm().isValid()) {
                Ext.MessageBox.show({
                    title: 'Error',
                    msg: 'Por favor verifique, existen campos con valores incorrectos.',
                    icon: Ext.MessageBox.ERROR,
                    buttons: Ext.MessageBox.OK
                })
                return ;
            }
            var index = stconexion.find('name', Ext.getCmp('name').getValue(), 0, false, false, true);
            if ((index != -1 && actionconexion=="adicionada") || (thisconexion!=Ext.getCmp('name').getValue() && index != -1)) {
                Ext.MessageBox.show({
                    title: 'Conexión',
                    msg: 'La conexión ya existe.',
                    icon: Ext.MessageBox.ERROR,
                    buttons: Ext.MessageBox.OK
                });
                return ;
            }
            else {
                Ext.Ajax.request({
                    url: 'addconexion',
                    method: 'POST',
                    params: {
                        interface: interface,
                        id: Ext.getCmp('id').getValue(),
                        name: Ext.getCmp('name').getValue(),
                        user: Ext.getCmp('user').getValue(),
                        passwd: Ext.getCmp('pass').getValue(),
                        port: Ext.getCmp('port').getValue(),
                        host: Ext.getCmp('host').getValue(),
                        db: Ext.getCmp('db').getValue()
                    },
                    callback: function(options, success, response) {
                        responseData = Ext.decode(response.responseText);
                        if (responseData.conectado == 1)
                        {

                            this.UIconexion1.hide();
                            stconexion.reload();
                            Ext.MessageBox.show({
                                title: 'Agregar conexión',
                                msg: 'Conexión ' + actionconexion + ' satisfactoriamente.',
                                icon: Ext.MessageBox.INFO,
                                buttons: Ext.MessageBox.OK
                            });
                            Resettext();
                        }

                    }

                });
            }


        }, this)

        btnAplicar.setHandler(function() {

            if (Ext.getCmp('name').getValue() == "" ||
                    Ext.getCmp('user').getValue() == "" ||
                    Ext.getCmp('pass').getValue() == "" ||
                    Ext.getCmp('port').getValue() == "" ||
                    Ext.getCmp('host').getValue() == "" ||
                    Ext.getCmp('db').getValue() == "") {

                Ext.MessageBox.show({
                    title: 'Definir conexión',
                    msg: 'Debe completar todos los campos.',
                    icon: Ext.MessageBox.ERROR,
                    buttons: Ext.MessageBox.OK
                })

            }
            if (!winconexion1.getForm().isValid()) {
                Ext.MessageBox.show({
                    title: 'Error',
                    msg: 'Por favor verifique, existen campos con valores incorrectos.',
                    icon: Ext.MessageBox.ERROR,
                    buttons: Ext.MessageBox.OK
                })
            }
            var index = stconexion.find('name', Ext.getCmp('name').getValue(), 0, false, false, true);
            if ((index != -1 && actionconexion=="adicionada") || (thisconexion!=Ext.getCmp('name').getValue() && index != -1)) {
                Ext.MessageBox.show({
                    title: 'Evento',
                    msg: 'El evento ya existe.',
                    icon: Ext.MessageBox.ERROR,
                    buttons: Ext.MessageBox.OK
                });
            }
            else {
                Ext.Ajax.request({
                    url: 'addconexion',
                    method: 'POST',
                    params: {
                        id: Ext.getCmp('id').getValue(),
                        name: Ext.getCmp('name').getValue(),
                        user: Ext.getCmp('user').getValue(),
                        passwd: Ext.getCmp('pass').getValue(),
                        port: Ext.getCmp('port').getValue(),
                        host: Ext.getCmp('host').getValue(),
                        db: Ext.getCmp('db').getValue()
                    },
                    callback: function(options, success, response) {
                        responseData = Ext.decode(response.responseText);
                        if (responseData.conectado == 1)
                        {
                            Resettext();
                            stconexion.reload();
                            host = true;
                            db = true;
                            port = true;
                            message("Conexión", "Conexión agregada");
                            //this.UIconexion.hide ();
                        }

                    }

                });
            }


        }, this)


        this.buttons = [this.btnCancel, btnAplicar, this.btnAceptar]

        this.items = [
            {
                xtype: 'form',
                id: 'conexionv',
                title: '',
                frame: true,
                region: 'center',
                store: stconexion,
                sm: sm,
                items: [winconexion1]
            }

        ];
        UIconexion1.superclass.initComponent.call(this);
    }
})

function Resettext() {
    Ext.getCmp('pass').reset();
    Ext.getCmp('user').reset();
    Ext.getCmp('pass').reset();
    Ext.getCmp('host').reset();
    Ext.getCmp('name').reset();
    Ext.getCmp('db').reset();
    Ext.getCmp('port').reset();
}

function message(title, text) {
    Ext.message.msg(title, text);
}

