var winconexion1;
var portTest = /^([0-9])/;
Ext.apply(Ext.form.field.VTypes, {
    //  vtype validation function
    port: function(val, field) {
        return portTest.test(val);
    },
    // vtype Text property: The error text to display when the validation function returns false
    portTest: 'Not a valid time.  Must be in the format "12:34 PM".',
    // vtype Mask property: The keystroke filter mask
    portMask: /[\d]/
});





var UIconexion1 = Ext.extend(Ext.Window, {
    title: 'Definir conexión',
    width: 300,
    height: 245,
    closable: false,
    layout: 'fit',
    modal: true,
    initComponent: function() {


        winconexion1 = new Ext.form.Panel({
            title: '',
            bodyStyle: 'padding:5px 5px 0;background-color:#dfe9f6',
            renderTo: Ext.getBody(),
            items: [
                {
                    xtype: 'textfield',
                    fieldLabel: 'Nombre',
                    id: 'name',
                    anchor: '100%',
                    width: 442,
                    allowBlank: false,
                    blankText: 'Debe ingresar el nombre.'
                },
                {
                    xtype: 'textfield',
                    fieldLabel: 'Servidor',
                    id: 'host',
                    value: 'default',
                    anchor: '100%',
                    width: 442,
                    allowBlank: false,
                    blankText: 'Debe ingresar el IP.'
                },
                {
                    xtype: 'textfield',
                    fieldLabel: 'Base de datos',
                    id: 'db',
                    value: 'default',
                    anchor: '100%',
                    allowBlank: false,
                    blankText: 'Debe ingresar la contrase&ntilde;a.'

                },
                {
                    xtype: 'textfield',
                    fieldLabel: 'Puerto',
                    id: 'port',
                    value: 'default',
                    anchor: '100%',
                    allowBlank: false,
                    blankText: 'Debe ingresar el puerto.',
                    vtype: 'port',
                    vtypeText: 'Puerto inválido'
                },
                {
                    xtype: 'textfield',
                    fieldLabel: 'Usuario',
                    id: 'user',
                    anchor: '100%',
                    allowBlank: false,
                    blankText: 'Debe ingresar el usuario.',
                    vtype: 'alpha',
                    vtypeText: 'Usuario inválido'

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
            if (host)
                Ext.getCmp('host').setValue(), host = false;
        }, this)

        Ext.getCmp('db').on('focus', function() {
            if (db)
                Ext.getCmp('db').setValue(), db = false;
        }, this)

        Ext.getCmp('port').on('focus', function() {
            if (port)
                Ext.getCmp('port').setValue(), port = false;
        }, this)



        this.btnAplicar = new Ext.Button({
            text: 'Aplicar'

        })

        this.btnAceptar = new Ext.Button({
            text: 'Aceptar'

        })

        this.btnCancel = new Ext.Button({
            text: 'Cancelar'
        })
        this.btnCancel.setHandler(function() {
            UIconexion1.hide();
        }, this)

        this.btnAceptar.setHandler(function() {

            if (Ext.getCmp('user').getValue() == "" ||
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
                    url: 'addconexion',
                    method: 'POST',
                    params: {
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
                            Ext.MessageBox.show({
                                title: 'Agregar conexión',
                                msg: 'Conexión agregada satisfactoriamente.',
                                icon: Ext.MessageBox.INFO,
                                buttons: Ext.MessageBox.OK
                            })
                        }

                    }

                });
            }


        }, this)

        this.btnAplicar.setHandler(function() {

            if (Ext.getCmp('user').getValue() == "" ||
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
                    url: 'addconexion',
                    method: 'POST',
                    params: {
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
                            ConfigProceso.enable();
                            message("Conexión", "Conexión agregada");
                            //this.UIconexion.hide ();
                        }

                    }

                });
            }


        }, this)


        this.buttons = [this.btnCancel, this.btnAplicar, this.btnAceptar]

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

function message(title, text) {
    Ext.message.msg(title, text);
}

