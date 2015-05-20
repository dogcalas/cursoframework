ComponenteNotificaciones = function() {
    return{
        connection: null,
        notifier: null,
        connect: function() {
            var scope = this;
            this.connection = new Strophe.Connection('/http-bind');

            this.connection.addHandler(this.onPresence, null, 'presence', null, null, null);
            this.connection.addHandler(this.onMessage, null, 'message', null, null, null);

            var userName = window.perfil.usuario;
            this.connection.connect(
                    userName
                    + '@'
                    + 'cig-tec-sxe-132',
                    scope.transform(userName),
                    this.onConnect
                    );
        },
        init: function() {
            /*
             * notifier deberia inicializarse solo una vez,
             * hay que verificar en algun momento que sea eso
             * y no otra cosa 
             */
            if (this.notifier === null) {
                this.notifier = Ext.create('Ext.util.Observable');
                this.notifier.addEvents({
                    ready: true
                });
            }
        },
        transform: function(userName) {
            return userName;
        },
        onMessage: function(msg) {
            var _msgChildNodes = msg.childNodes;
            for (var i = 0; i < _msgChildNodes.length; i++) {
                var msgType = _msgChildNodes[i].nodeName;
                switch (msgType) {
                    case 'event':
                    case 'delay':
                    case 'addresses':
                    case 'composing':
                    case 'paused':
                    case 'active':
                        break;
                    case 'body':
                        var txt = Strophe.getText(_msgChildNodes[i]);
                        Componentes.addListener('notificationarrived', function() {
                            Ext.MessageBox.alert(
                                    'Notificaci&oacute;n',
                                    'Tiene una nueva notificaci&oacute;n'
                                    );
                            /*jQuery.noticeAdd({
                                text: 'Llego una notificacion',
                                stay: true,
                                type: 'success_ntfy_ui_cedrux'
                            });*/
                        });
                        var _txt = Ext.JSON.decode(txt);
                        var isitWFNotification = ComponenteNotificaciones.isItWorkflowNotification(_txt);
                        if (isitWFNotification) {
                            Componentes.buildOrRegisterNotification(txt);
                        }
                        break;
                    default:
                        console.log('default');
                        break;
                }

            }
            return true;
        },
        onPresence: function(presence) {
            return true;
        },
        onIQ: function(/*iq*/) {
        },
        onConnect: function(status) {
            switch (status) {
                case Strophe.Status.CONNECTED:
                    {
                        var presence = $pres();
                        presence.c('show').t('away').up();
                        this.send(presence.tree());
                        this.send($iq({
                            type: 'get',
                            xmlns: Strophe.NS.CLIENT
                        }).c('query', {
                            xmlns: Strophe.NS.ROSTER
                        }).tree());
                        jQuery.noticeAdd({
                            text: 'Sistema de Notificaciones listo...',
                            stay: false,
                            stayTime: 5000,
                            type: 'success_ntfy_ui_cedrux'
                        });
                    }
                    break;
            }
        },
        isItWorkflowNotification: function(msg) {
            return true;
        }
    };
}();
