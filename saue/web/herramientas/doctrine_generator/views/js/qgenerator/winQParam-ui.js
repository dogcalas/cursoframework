
DoctrineGenerator.UI.winQParam = Ext.extend(Ext.Window, {
    title: 'Adicionar Par&aacute;metro',
    layout: 'fit',
    height: 300,
    width: 500,
    // maximizable: true,
    id: 'winQParam',
    autoScroll: true,
    initComponent: function() {
        var perfil = window.parent.UCID.portal.perfil;
        this.stQJOIN = new Ext.data.Store({
            id: 'stQJOIN',
            url: '../mapper/load_realations',
            reader: new Ext.data.JsonReader({
                root: 'data'
            }, ['clas', 'rel'])
        })
        this.stQJoins = ['Integer', 'Boolean', 'Array', 'Srtring', 'Float']
        this.cbQTypeParam = new Ext.form.ComboBox({
            store: this.stQJoins,
            triggerAction: 'all',
            disabled: false,
            width: 100
        })
        this.smQJoinTables = new Ext.grid.CheckboxSelectionModel({
            width: 25,
            scope: this
        });
        this.txtQJFilter = new Ext.form.TextField({
            enableKeyEvents: true,
            width: 100,
        })
        this.btnQJCancel = new Ext.Button({
            icon: '../../views/img/eliminar.png',
            iconCls: 'btn',
            disabled: true,
            tooltip: 'Eliminar par&aacute;metro seleccionado.'
        })
        this.btnQJAdd = new Ext.Button({
            icon: '../../views/img/adicionar.png',
            iconCls: 'btn',
            id: 'btnQJAdd',
            disabled: false,
            tooltip: 'A&ntilde;adir par&aacute;metro.',
        })
        this.cmbQtipJoin = new Ext.form.ComboBox({
            store: this.stQJoins,
            id: 'cmbQtipJoin',
            triggerAction: 'all',
            editable: false,
            width: 100
        }),
        this.buttons = [{
                text: 'Cerrar',
                icon: perfil.dirImg + 'cancelar.png',
                iconCls: 'btn',
                handler: function() {
                    Ext.getCmp('winQParam').hide()
                }
            }, {
                text: 'Aplicar',
                icon: perfil.dirImg + 'aplicar.png',
                iconCls: 'btn',
                handler: function() {
                    Ext.getCmp('winQParam').hide(function() {
                        alert('ok')
                    })
                }
            }]
        this.tfQNParam = new Ext.form.TextField({
            id: 'tfQNParam',
            allowBlank: false,
            width: 120,
            maxLength: 10
        })
        this.tfQDefaul = new Ext.form.TextField({
            id: 'tfQDefaul',
            allowBlank: true,            
            width: 80
        })
        this.items = [
            {xtype: 'panel',
                title: '',
                scope: this,
                id: 'qpanelG',
                frame: true,
                tbar: [
                    new Ext.Toolbar.TextItem({text: '<b>Par&aacute;metro:</b>'}),
                    this.tfQNParam,
                    {xtype: 'tbspacer'}, {xtype: 'tbspacer'}, {xtype: 'tbspacer'},
                    new Ext.Toolbar.TextItem({text: '<b>Tipo:</b>'}),
                    this.cbQTypeParam,
                    {xtype: 'tbspacer'}, {xtype: 'tbspacer'}, {xtype: 'tbspacer'},
                    new Ext.Toolbar.TextItem({text: '<b>Valor:</b>'}),
                    this.tfQDefaul,
                    this.btnQJAdd
                ],
                items: [
                    {
                        xtype: 'grid',
                        store: this.stQJOIN,
                        id: 'gridQJoin',
                        height: 260,
                        width: 470,
                        sm: this.smQJoinTables,
                        tbar: [this.btnQJCancel, '->', 'Filtro: ', this.txtQJFilter],
                        columns: [
                            this.smQJoinTables,
                            {id: 'param', header: 'Par&aacute;metro', width: 160, sortable: true, dataIndex: 'param'},
                            {id: 'table', header: 'Valor', width: 150, sortable: true, dataIndex: 'table'},
                            {id: 'type', header: 'Tipo', width: 150, sortable: true, dataIndex: 'type'},
                        ],
                        loadMask: {
                            store: this.stQJOIN
                        }
                    }
                ]
            }]
        DoctrineGenerator.UI.winQParam.superclass.initComponent.call(this);
    },
});
