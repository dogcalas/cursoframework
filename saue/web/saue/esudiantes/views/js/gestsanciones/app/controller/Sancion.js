Ext.define('Sancion.controller.Sancion', {
    extend: 'Ext.app.Controller',
    refs: [
        {ref: 'alist', selector: 'alumnolist'},
        {ref: 'windowsearch', selector: 'windowsearch'}
    ],
    init: function(application) {
        this.control({
            'studentinfo button[action=buscar]': {
                afterrender: this.hideCod,
                click: this.buscar
            },
            'windowsearch button[action=aceptar]': {
                click: this.searchStudents
            },
            "sancionlist gridpanel[id=lista]": {
                selectionchange: this.updateButtons
            }, 
            "sancionlist button[action=adicionar]": {
                click: this.onAdicionar
            },
            "sancionlist button[action=modificar]": {
                click: this.onModificar
            },
            "sancionlist button[action=eliminar]": {
                click: this.onEliminar
            },
            "editsancion button[action=aplicar]": {
                click: this.onGuardar
            },
            "editsancion button[action=aceptar]": {
                click: this.onGuardar
            },
            'alumnolist': {
                celldblclick: function () {
                    var button = this.getWindowsearch().down('button[action=aceptar]');
                    this.searchStudents(button);
                }
            }
        });
    },
    buscar: function (button) {
        var searchalumno = Ext.widget('windowsearch',{who:'alumnolist', height:400});
    },    
    hideCod: function (button) {
      Ext.getCmp('studentCodigo').hide();
      Ext.getCmp('cedula').show();
      Ext.getCmp('studentFacultad'). hide();
    },
    updateButtons: function(sm, selections){
        
        Ext.getCmp('eliminarBtn').setDisabled(selections.length === 0); 
        Ext.getCmp('modificarBtn').setDisabled(selections.length === 0);
    },
    onGuardar: function(button){
        var me = this,
            win = button.up('window'),
            form = win.down('form');

        if (form.getForm().isValid()) {
            var record = form.getRecord(),
                values = form.getValues();
               
               //modificando
            if (record) {
                record.set(values);
            }
            //insertando
            else {
                Ext.getCmp('lista').getStore().add(values);
            }

            me.sincronizarStore(Ext.getCmp('lista'), Ext.getCmp('lista').getStore());
            Ext.getCmp('lista').getStore().reload();

            if (button.action === 'aceptar')
                win.close();
            else
                if(win.title == perfil.etiquetas.lbTitleAdd)
                    form.getForm().reset();
        }
    },
    onAdicionar: function(button, e, options) {
      var view =  Ext.widget('editsancion');
      view.setTitle(perfil.etiquetas.lbTitleAdd);
      view.show();
    },   
    onModificar: function(button, e, options) {
      var view =  Ext.widget('editsancion');
      view.setTitle(perfil.etiquetas.lbTitleMod);
      view.show();
      record = Ext.getCmp('lista').getSelectionModel().getLastSelected();
      view.down('form').loadRecord(record);
      console.log(view.down('form').getValues());
      Ext.getCmp('cedula').setValue("<b>" + record.data.cedula + "</b>");
      Ext.getCmp('idalumnofield').setValue(record.data.idalumno);
      Ext.getCmp('cedulafield').setValue(record.data.cedula);
      Ext.getCmp('studentNombre').setValue(record.data.nombre +" "+ record.data.apellidos);
    },    
    onEliminar: function(button, e, options) {
      var me = this,
            grid = Ext.getCmp('lista'),
            record = grid.getSelectionModel().getLastSelected(),
            store = grid.getStore();

        mostrarMensaje(
            2,
            perfil.etiquetas.lbMsgConfEliminar,
            function (btn, text) {
                if (btn == 'ok') {
                    store.remove(record);
                    me.sincronizarStore(grid, store);
                    store.load();
                }
            }
        )
    },
    sincronizarStore: function (grid, store) {
        store.sync();
    },
    searchStudents: function(button){
        var win = button.up('window');
        var me = this;
        record = me.getAlist().getSelectionModel().getSelection()[0];
            if (record){
                win.setLoading("Cargando");
                me.idusuario = record.data.idusuario;
                Ext.getCmp('studentNombre').setValue(record.data.nombre +" "+ record.data.apellidos);
                Ext.getCmp('cedula').setValue("<b>" + record.data.cedula + "</b>");
                Ext.getCmp('idalumno').setValue(record.data.idalumno);
                Ext.getCmp('idalumnofield').setValue(record.data.idalumno);
                Ext.getCmp('cedulafield').setValue(record.data.cedula);
                Ext.getCmp('nombrefield').setValue(record.data.nombre);
                Ext.getCmp('apellidosfield').setValue(record.data.apellidos);
                me.getAlist().getSelectionModel().deselectAll();
                win.close();
            } else {
                  me.mostrarError(perfil.etiquetas.lbMsgEst);
            }
        }

});
