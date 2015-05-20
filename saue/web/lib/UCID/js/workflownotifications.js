//-------------------------//-------------------------//----------------------//
//----------------------- For workflow use only ------------------------------//
Ext.define('WF.WorkflowNotifications', {
    extend: 'Ext.util.Observable',    
    notifications: {
        zendaction: [],
        users: []
    },
    constructor: function(cfg){
        return this;
    },
    init: function(){
        this.callParent();
    },
    add: function(_notification) {
        var type = _notification.Type;
        var data = _notification.data;
        switch (type) {
            case 'ZendAction':
                {
                    var valid = this.checkValidZendActionTask(data.zendActionVars, data.workflowControlVars);
                    if (valid === true) {
                        this.notifications.zendaction.push(_notification);
                    }
                }
                break;
            case 'User':
                {
                }
                break;
        }
    },
    find: function(_notification) {
        if (_notification) {
            var izat = this.findInZendActionTasks(_notification);
            if (izat !== -1) {
                return izat;
            } else {
                return this.findInUserTasks(_notification);
            }
        } else
            return -1;
    },
    findAndDeleteTaskByExecutionId: function(_executionId) {
        var zfts = this.notifications.zendaction;
        var found = false;
        var _notifications = null;
        for (var i = 0; i < zfts.length; ++i) {
            var zfwfcvs = zfts[i].data.workflowControlVars;
            if (zfwfcvs.executionId === _executionId) {
                _notifications = zfts.splice(i, 1);
                found = true;
                break;
            }
        }
        if (!found) {
            zfts = this.notifications.users;
            for (i = 0; i < zfts.length; ++i) {
                zfwfcvs = zfts[i].data.workflowControlVars;
                if (zfwfcvs.executionId === _executionId) {
                    _notifications = zfts.splice(i, 1);
                    found = true;
                    break;
                }
            }
        }
        if (found) {
            if (_notifications.length === 1) {
                Componentes.removeNotificationPanel(_notifications[0].data.ctrlId);
            }
        }
    },
    findInZendActionTasks: function(_notification) {
        var zfts = this.notifications.zendaction;
        var result = -1;
        var controller = _notification.controller;
        var systemId = _notification.systemId;
        for (var i = 0; i < zfts.length; ++i) {
            var zfrvs = zfts[i].data.zendActionVars;
            if (zfrvs.SystemId === systemId && zfrvs.controller === controller) {
                result = zfts[i];
                break;
            }
        }
        return result;
    },
    findInUserTasks: function(_notification) {
        return -1;
    },
    checkValidZendActionTask: function(zendActionVars, wfControlVars) {
        return true;
    }
});