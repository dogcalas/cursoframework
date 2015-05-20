//Ext.namespace('Workflow');
Componentes = function() {
    return{
        notifier: null,
        getNotificationsWdw: function() {
            var notifications = myDesktopApp.getDesktop().getWindow('winNotificacionesWorkflow') || this._createNotificationsWindow();
            return notifications;
        },
        init: function() {
            if (this.notifier === null) {
                this.notifier = Ext.create('Ext.util.Observable');
                this.notifier.addEvents({
                    notificationarrived: true
                });
            }
        },
        addListener: function(eventName, fn) {
            this.init();
            this.notifier.addListener(eventName, fn);
        },
        buildOrRegisterNotification: function(_msg) {
            var decoded = this._decode(_msg);
            var callFn = decoded['Type'];
            var data = decoded['data'];
            var ctrl = null;
            /*
             * Llego una notificacion... la registro antes de construir
             * los controles 
             */
            switch (callFn) {
                case 'ZendAction':
                    {
                        var vars = {};

                        Ext.apply(vars, data.workflowControlVars, data.zendActionVars);

                        //Para probar.... despues volver a comentar prox. 2 lineas
                        ctrl = this._buildTaskZendActionPanel(vars);
                        Ext.apply(decoded.data, {ctrlId: ctrl.getId()});
                        /*
                         * Notification is registered, no more than that.
                         */
                        window.parent.WF.WorkflowNotifications.prototype.add(decoded);
                    }
                    break;
                case 'User':
                    {
                        ctrl = this._buildTaskUserPanel();
                    }
                    break;
            }

            if (ctrl !== null) {
                var nw = this.getNotificationsWdw();
                var notifsPanel = nw.getComponent('panelNotificaciones');
                var scp = this;
                notifsPanel.on('add', function() {
                    scp.notifier.fireEvent('notificationarrived',arguments);
                    //console.log('notificationarrival');
                });
                notifsPanel.add(ctrl);
                notifsPanel.doLayout();
            }
        },
        _createNotificationsWindow: function() {
            var scope = this;
            var notifs = myDesktopApp.getDesktop().createWindow({
                id: 'winNotificacionesWorkflow',
                title: 'Notificaciones Workflow',
                layout: 'fit',
                width: 400,
                maximized: false,
                maximizable: false,
                height: 300,
                minWidth: 400,
                minHeight: 300,
                iconCls: 'bogus',
                shim: false,
                animCollapse: false,
                constrainHeader: true,
                listeners: {
                    show: function(thisWdw) {
                        //scope._populateNotificationsWdw.call(scope, thisWdw);
                    }
                },
                items: [new Ext.Panel({
                        title: 'Notificaciones',
                        layout: 'accordion',
                        layoutConfig: {
                            renderHidden: true,
                            fill: false
                        },
                        id: 'panelNotificaciones',
                        draggable: false
                    })]
            });
            return notifs;
        },
        _populateNotificationsWdw: function(notificationsWdw) {

        },
        _decode: function(_jsonEncoded) {
            return Ext.JSON.decode(_jsonEncoded);
        },
        _buildTaskZendActionPanel: function(_wfdata) {
            var panel = new Ext.Panel({
                title: 'Ejecutar Acci&oacute;n de Sauxe',
                draggable: false,
                collapsible: true,
                height: 50,
                buttons: [new workflowControls.ZendActionButtonOK({
                        text: 'Aceptar',
                        workflowZendActionData: _wfdata
                    }), {
                        text: 'Cancelar',
                        listeners: {
                            click: function(btn, event) {
                                console.log('cancelar');
                            }
                        }
                    }]
            });
            return panel;
        },
        _attachZendActionParams: function(connection, options) {

        },
        _buildTaskUserPanel: function() {
            return new Ext.Panel({
                title: 'Ejecutar Tarea Usuario',
                draggable: false,
                height: 50,
                buttons: [new workflowControls.UserButtonOK({
                        text: 'Aceptar'
                    }), {
                        text: 'Cancelar',
                        listeners: {
                            click: function(btn, event) {
                                console.log(btn);
                            }
                        }
                    }]
            });
        },
        removeNotificationPanel: function(_panelId) {
            var nw = this.getNotificationsWdw();
            var notifsPanel = nw.getComponent('panelNotificaciones');
            notifsPanel.remove(_panelId, true);
            notifsPanel.doLayout();
        }
    };
}();

