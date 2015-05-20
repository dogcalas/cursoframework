Ext.ns('workflowControls');
/*
 * Buttons 
 */
workflowControls.ZendActionButtonOK = Ext.extend(Ext.Button, {
    workflowZendActionData: null,
    listeners: {
        zendactionoked: function(thisBtn, event) {
            this._onzendactionoked(thisBtn, event);
        },
        click: function(thisBtn, event) {
            this.fireEvent('zendactionoked', thisBtn, event);
        }
    },
    constructor: function(config) {
        workflowControls.ZendActionButtonOK.superclass.constructor.call(this, config);
        this.addEvents({
            'zendactionoked': true
        });

    },
    _onzendactionoked: function(thisBtn, event) {
    }
});

workflowControls.UserButtonOK = Ext.extend(Ext.Button, {
    listeners: {
        useroked: function(thisBtn, event) {
            this._onuseroked(thisBtn, event);
        },
        click: function(thisBtn, event) {
            this.fireEvent('useroked', thisBtn, event);
        }
    },
    constructor: function(config) {
        workflowControls.UserButtonOK.superclass.constructor.call(this, config);
        this.addEvents({
            'useroked': true
        });
    },
    _onuseroked: function(thisBtn, event) {
        var uw = new workflowControls.UserInputWindow({
            forBuilding: [{
                    name: 'Edad',
                    type: 'int'
                }]
        });
        uw.show();
    }
});

/*
 * Textfields
 * */
workflowControls.TextField = Ext.extend(Ext.form.TextField, {
    checkFn: null,
    enableKeyEvents: true,
    listeners: {
        keypress: function(_this, _event) {
            var charCode = String.fromCharCode(_event.getCharCode());
            var valid = this.doChecking(charCode);
            if (!valid) {
                _event.stopPropagation();
                _event.preventDefault();
                return false;
            }
            return true;//lo quito?
        }
    },
    constructor: function(config) {
        workflowControls.TextField.superclass.constructor.call(this, config);
    },
    initComponent: function() {
        workflowControls.TextField.superclass.initComponent.call(this);

        Ext.apply(this, {
            width: 100
        });
    },
    doChecking: function(character) {
        return this.checkFn(character);
    }
});

/*
 * Window
 * */
workflowControls.UserInputWindow = Ext.extend(Ext.Window, {
    forBuilding: null,
    width: 300,
    height: 300,
    layout: 'fit',
    listeners: {
        beforeshow: function(thisWdw) {
            this._addUserControls(thisWdw);
        }
    },
    constructor: function(config) {
        workflowControls.UserInputWindow.superclass.constructor.call(this, config);
    },
    initComponent: function() {
        workflowControls.UserInputWindow.superclass.initComponent.call(this);
        this.add({
            xtype: 'panel',
            layout: 'form',
            bodyStyle: 'padding:10px',
            id: 'panelUserInputControlsContainer',
            title: 'asdasd'
        });
    },
    _addUserControls: function(thisWdw) {
        if (thisWdw._getForBuilding() !== null) {
            if (Array.isArray(this._getForBuilding())) {
                var p = thisWdw.getComponent('panelUserInputControlsContainer');

                var fb = this._getForBuilding();
                for (var i = 0; i < fb.length; i++) {
                    var _spec = fb[i];

                    var _regExp = getRegExp(_spec.type);
                    var cf = getCheckingFunction(_spec.type);

                    var tf = new workflowControls.TextField({
                        fieldLabel: _spec.name,
                        regEx: new RegExp(_regExp),
                        checkFn: cf
                    });

                    p.add(tf);
                }
            }
        }
    },
    _getForBuilding: function() {
        return this.forBuilding;
    }
});

/*
 * Funciones
 * */
function getRegExp(type) {
    var regEx = null;
    switch (type) {
        case 'int':
            regEx = '/^[1-9]{1}[0-9]*$/';
            break;
    }
    return regEx;
}

function getCheckingFunction(type) {
    var cf = null;
    switch (type) {
        case 'int':
            cf = checkInt;
            break;
        case 'uint':
            break;
        case 'long':
            break;
        case 'string':
            break;
        case 'float':
            break;
    }
    return cf;
}

function checkInt(charCode) {
    if (!(charCode >= 0 && charCode <= 9)) {
        return false;
    }
    else
        return true;
}
