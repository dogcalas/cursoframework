var groupb, plvisible = false, plcontadoratribtos = 0, plpresskey = false, tienepl,valseleccion=true,stpl;
var stpropiedades = Ext.create('Ext.data.Store', {
    fields: ['id', 'groupb'],
    proxy: {
        type: 'ajax',
        url: 'getpropiedades',
        actionMethods: {// Esta Linea es necesaria para el metodo de llamada
            // POST o GET
            read: 'POST'

        },
        reader: {
            totalProperty: "cantidad_filas",
            root: "datos",
            id: "id"
        }
    },
    listeners: {
        'datachanged': function(a, b) {

            groupb = stpropiedades.getById('1').data.groupb;
            setorderbygroupby(groupb);


        }
    }
});



propiedades5 = Ext.create('Ext.form.Panel', {
    colspan: 3,
    bodyPadding: 5,
    width: '100%',
    height: '100%',
    region: 'center',
    autoExpandColumn: 'expandir',
    layout: {
        type: 'hbox',
        align: 'stretch'
    },
    items: [new Ext.form.field.ComboBox({
            id: 'groupby',
            width: '95%',
            labelWidth: '50%',
            fieldLabel: 'Instancia de proceso',
            displayField: 'name',
            valueField: 'name',
            value: groupb,
            multiSelect: true,
            typeAhead: true,
            triggerAction: 'all',
            selectOnTab: true,
            store: stalltable,
            listeners: {
                'blur': function(a, b, c) {
                    if (a.isDirty())
                        cambios = a.isDirty();
                },
                'focus': function(a, b, c) {
					if(valseleccion){
					
                    var arraygroup=stpropiedades.getById('1').data.groupb;
					
                    if(arraygroup){
					
					if(arraygroup.indexOf(',')!= -1){
					 arraygroup=arraygroup.split(',');
					
                    Ext.getCmp('groupby').select(arraygroup);
                    valseleccion=false;}
					else{
                    arraygroup=arraygroup.split(',');
					
                    Ext.getCmp('groupby').select(arraygroup);
                    valseleccion=false;
					}
						}
					}
                    a.resetOriginalValue();
                }
            }
        })]
});
Ext.define('plmodel', {
    extend: 'Ext.data.Model',
    fields: [
        // the 'name' below matches the tag name to read, except 'availDate'
        // which is mapped to the tag 'availability'
        {
            name: 'id',
            type: 'string'
        }, {
            name: 'name',
            type: 'string'
        }, {
            name: 'tablev',
            type: 'string'
        }, {
            name: 'action',
            type: 'string'
        }]
});
var cellEditing = Ext.create('Ext.grid.plugin.CellEditing', {
    clicksToEdit: 1
});

var stpl = Ext.create('Ext.data.Store', {
    //fields: ['id', 'name', 'tablev', 'action'],
    model: 'plmodel',
    proxy: {
        type: 'ajax',
        url: 'getpl',
        actionMethods: {// Esta Linea es necesaria para el metodo de llamada
            // POST o GET
            read: 'POST'

        },
        reader: {
            totalProperty: "cantidad_filas",
            root: "datos",
            id: 'id'
        }
    },
    listeners: {
        'load': function(a, b) {
            if (stpl.getCount() > 1){
                tienepl = true;
                pl.setVisible(true);
            }
            else {
                if (stpl.getAt(0).data.name == ""){
                    tienepl = false;
                    pl.setVisible(false);
                }
                else{
                    tienepl = true;
                    pl.setVisible(true);
                    //este es para que deje editar el primero
                    stpl.insert(0, "");
                    stpl.removeAt(0);
                        
                }
            }


        }
    }

});





var pl = Ext.create('Ext.grid.Panel', {
    width: '100%',
    flex: 1,
    region: 'center',
    sortableColumns: false,
    autoExpandColumn: 'expandir',
    store: stpl,
    columns: [{
            flex: 2,
            text: 'Nombre del atributo',
            width: '35%',
            dataIndex: 'name',
            hideable: false,
            editor: {
                listeners: {
                    'change': function(a, b, c) {
                        if (a.isDirty())
                            cambios = a.isDirty();
                    },
                     'focus': function(a, b, c) {
                        a.resetOriginalValue();
                    }
                }
            }
            
        }, {
            header: 'Valor del atributo',
            flex: 3,
            renderer: function(value, metaData, record, rowIdx, colIdx, store, view) {
                plcontadoratribtos++;
                //if (plcontadoratribtos > 7)
                if (plpresskey) {
                    record.set('action', 'no action');
                    plpresskey = false;
                }
                return value;
            },
            dataIndex: 'tablev',
            width: '100%',
            editor: new Ext.form.field.ComboBox({
                typeAhead: true,
                enableKeyEvents: true,
                triggerAction: 'all',
                selectOnTab: true,
                store: stalltable,
                displayField: 'name',
                valueField: 'name', 
                listeners: {
                    'change': function(a, b, c) {
                        if (a.isDirty())
                            cambios = a.isDirty();
                    },
                    'keyup': function(a, b, c) {
                        plpresskey = true;
                    },
                    'select': function(a, b, c) {
                        plpresskey = false;
                    }
                }
            })

        }, {
            header: 'Acción',
            flex: 1,
            dataIndex: 'action',
            width: '100%',
            editor: new Ext.form.field.ComboBox({
                typeAhead: true,
                enableKeyEvents: true,
                width: '35%',
                store: actions,
                displayField: 'action',
                valueField: 'action',
                forceSelection: true,
                allowBlank: false,
                blankText: 'Este campo es obligatorio.',
                listeners: {
                    'blur': function(a, b, c) {
                        if (a.isDirty())
                            cambios = a.isDirty();
                    },
                    'focus': function(a, b, c) {
                        a.resetOriginalValue();
                    }
                }
            })
        },{
				xtype : 'actioncolumn',
				width : 30,
				sortable : false,
				items : [ {
					icon : '../../views/images/delete.gif',
					tooltip : 'Eliminar atributo del negocio ',
					handler : function(grid, rowIndex, colIndex) {
								cambios=true;
								stpl.insert(0, "");
								stpl.removeAt(0);
								stpl.removeAt(rowIndex);
								message("Atributo eliminado", "Atributo del negocio eliminado.");
					}
				} ]
			}],
    plugins: [cellEditing]
});
pl.setVisible(false);
  

uniondepanel = Ext.create('Ext.form.Panel', {
    bodyPadding: 5,
    scroll: true,
    width: '100%',
    height: '100%',
    region: 'center',
    autoExpandColumn: 'expandir',
    layout: 'anchor',
    items: [propiedades5],
    tbar: [{
            text: 'Agregar atributo del negocio',
            icon: perfil.dirImg + 'adicionar.png',
            iconCls: 'btn',
            width: 165,
            handler: function() {
                if (!plvisible) {
                    plvisible = true;
                    pl.setVisible(true);
                    if (tienepl) {
                        var r = Ext.create('plmodel', {
                            //'id', 'name', 'tablev', 'action'
                            id: '',
                            name: '',
                            tablev: '',
                            action: ''
                        });
                        var count = stpl.getCount();
                        stpl.insert(count, r);
                        cellEditing.startEditByPosition({
                            row: count,
                            column: 0
                        });
                    }
                }
                else{
                    var r = Ext.create('plmodel', {
                            //'id', 'name', 'tablev', 'action'
                            id: '',
                            name: '',
                            tablev: '',
                            action: ''
                        });
                        var count = stpl.getCount();
                        stpl.insert(count, r);
                        cellEditing.startEditByPosition({
                            row: count,
                            column: 0
                        });
                }
                
            }
        }]
});
uniondepanel.add(pl);



var panelderecho = Ext.create('Ext.form.Panel', {
    title: 'Identificador de instancia de proceso y datos del negocio',
    bodyStyle: 'padding:3px 3px 0;background-color:#ffffff;',
    region: 'east',
    scroll: true,
    frame: true,
    split: true,
    width: '42%',
    height: '100%',
    collapsible: true,
    collapsed: false,
    layout: 'anchor'
});

function setorderbygroupby(groupby) {

    if (groupby != null) {
        Ext.getCmp('groupby').setValue(groupby);
        //alert('if2');
    }
    else {
        Ext.getCmp('groupby').setValue();
        //alert('else2');
    }

}
