
Ext.define('GestEmpresas.controller.Empresa', {
    extend: 'Ext.app.Controller',
    
    init: function(application) {
        this.control({
            "empresalist button[action=adicionar]": {
                click: this.onAdicionar
            },
            "empresalist button[action=modificar]": {
                click: this.onModificar
            },
            "empresalist button[action=eliminar]": {
                click: this.onEliminar
            },
            "winadicionar button[action=aplicar]": {
                click: this.onGuardar
            },
            "winadicionar button[action=aceptar]": {
                click: this.onGuardar
            }
        });
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
      var view =  Ext.widget('winadicionar');
      view.setTitle(perfil.etiquetas.lbTitleAdd);
      view.show();
    },   
    onModificar: function(button, e, options) {
      var view =  Ext.widget('winadicionar');
      view.setTitle(perfil.etiquetas.lbTitleMod);
        view.down('button[action=aplicar]').hide();
      view.show();
      record = Ext.getCmp('lista').getSelectionModel().getLastSelected();
      view.down('form').loadRecord(record);
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
                }
            }
        )
    },
    sincronizarStore: function (grid, store) {
        store.sync({
            success: function (batch) {
                grid.getDockedComponent('paginator').doRefresh();
            },
            failure: function () {
                grid.getDockedComponent('paginator').doRefresh();
            }
        });
    }
});
