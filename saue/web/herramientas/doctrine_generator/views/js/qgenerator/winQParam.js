DoctrineGenerator.winQParam = new Ext.extend(DoctrineGenerator.UI.winQParam, {
   
    initComponent: function() {
        DoctrineGenerator.winQParam.superclass.initComponent.call(this)
        this.txtQJFilter.on('keyup', function(tf) {
            if (tf.getValue()) {
                //   this.stQTable.filter('table_name', tf.getValue());
            }
        }, this)
        this.on('beforehide', function() {
            Ext.MessageBox.confirm('Cerrar', 'Tienes cambios si guardar. ¿Desea continuar?',
                    function(btn) {
                        if (btn == 'yes') {
                            Ext.MessageBox.show({
                                msg: 'Guardando cambios. Espere...',
                                progressText: 'Guardando...',
                                width: 270,
                                wait: true,
                                waitConfig: {
                                    interval: 200
                                },
                                progress: true,
                                closable: false,
                            });
                            setTimeout(function() {
                                Ext.MessageBox.hide();
                            }, 2000);

                        } else
                            return false;
                    });
        });

    }
})