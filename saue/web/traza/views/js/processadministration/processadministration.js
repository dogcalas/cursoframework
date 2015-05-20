var perfil = window.parent.UCID.portal.perfil;
perfil.etiquetas = Object();
UCID.portal.cargarEtiquetas('processadministration', cargarInterfaz);

// //------------ Inicializo el singlenton QuickTips ------------////
Ext.QuickTips.init();

// //------------ Declarar Variables ------------////
var conexion = true, interface, actionconexion; 
var stprocess;
var sm, host, db, port;

// //------------ Area de Validaciones ------------////
var texto, num, dir;
texto = /^([a-zA-ZáéíóúñüÑ]+[a-zA-ZáéíóúñüÑ\d\.\-\_ ]*)+$/;
num = /^([a-z\d\.\-\_]*)+$/;

function cargarInterfaz() {
     Ext.onReady(function(){
   
    // //------------ Botones ------------////

    ActProceso = new Ext.Button({
        disabled: false,
        id: 'CProceso',
        icon: perfil.dirImg + 'historial.png',
        iconCls: 'btn',
        text: perfil.etiquetas.ActProceso,
        handler: function() {
          activar_proceso();
        }

    });


    DesacProceso = new Ext.Button({
        disabled: true,
        id: 'ModifProceso',
        icon: perfil.dirImg + 'restringir.png',
        iconCls: 'btn',
        text: perfil.etiquetas.DesacProceso,
        handler: function() {
            desac_proceso();
        }

    });
    GenTrazasProceso = new Ext.Button({
        disabled: true,
        id: 'ElimProceso',
        icon: perfil.dirImg + 'movimientos.png',
        iconCls: 'btn',
        text: perfil.etiquetas.GenTrazasProceso,
        handler: function() {
            generartrazasproceso();
        }
    });
       ValidarProceso = new Ext.Button({
        disabled: true,
        id: 'Limp',
        icon: perfil.dirImg + 'limpiar.png',
        iconCls: 'btn',
        text: perfil.etiquetas.ValProcess,
        handler: function() {
            validar_proceso();
        }
    });




    // //------------ Store del Grid de Temas ------------////

    stprocess = new Ext.data.Store({
        fields: [
            {
                name: 'id',
                type: 'string'
            },
            {
                name: 'name',
                type: 'string'
            },
            {
                name: 'fuentedatos',
                type: 'string'
            },
            {
                name: 'descripcion',
                type: 'string'
            },
            {
                name: 'instancia',
                type: 'string'
            },{
                name: 'version',
                type: 'string'
            },
            {
                name: 'validado',
                type: 'checked'
            },
              {
                name: 'activado',
                type: 'checked'
            }
        ],
        proxy: {
            type: 'ajax',
            url: 'getprocess',
            actionMethods: {//Esta Linea es necesaria para el metodo de llamada POST o GET                           
                read: 'POST'
            },
            reader: {
                totalProperty: "cantidad_filas",
                root: "datos",
                id: "id"
            }
        }
    });


    // //------------ Establesco modo de seleccion de grid (single)

    sm = Ext.create('Ext.selection.RowModel', {
        mode: 'SINGLE',
        allowDeselect: false,
         enableKeyNav: false
    });




   stprocess.on('load', function() {
        if (stprocess.getCount() != 0)
        {
          //  ActTrazasProceso.enable();
            ValidarProceso.enable();
            DesacProceso.enable();
         GenTrazasProceso.enable();
            sm.select(0);
        }
        else
        {
            ValidarProceso.disable();
            ActProceso.disable();

            DesacProceso.disable();
          GenTrazasProceso.disable();
        }
    }, this)




    // //------------ Defino el grid de Temas ------------////

    var GModulo = Ext.create('Ext.grid.Panel', {
        frame: true,
        region: 'center',
        autoExpandColumn: 'expandir',
        store: stprocess,
        selModel: sm,
        columns: [
            {
                text: 'Nombre del proceso',
                dataIndex: 'name',
                flex: 2
            },
            {
                text: 'Fuente de datos',
                dataIndex: 'fuentedatos',
                flex: 2

            },
            {
                text: 'Descripción',
                dataIndex: 'descripcion',
                flex: 2
            },
            {
                text: 'Instancia de proceso',
                dataIndex: 'instancia',
                flex: 2
            },{
                text: 'Versión del proceso',
                dataIndex: 'version',
                flex: 1.5
            },
               {
                text: 'Validado',
                dataIndex: 'validado',
                editable:true,
                 editor:new Ext.form.Checkbox({checked:false}),
                       renderer:function(validado){
                           if (validado==true)
                                return "<img src='../../views/images/validado.png' />";
                           else 
                               return "<img src='../../views/images/no_validado.png' />";
                       },
                flex: 0
            },
                     {
                text: 'Activado',
                dataIndex: 'activado',
                editable:true,
                 editor:new Ext.form.Checkbox({checked:false}),
                       renderer:function(activado){
                           if (activado==true)
                                return "<img src='../../views/images/validado.png' />";
                           else 
                               return "<img src='../../views/images/no_validado.png' />";
                       },
                flex: 0
            }
          

        ],
        loadMask: {
            store: stprocess
        },
        bbar:new Ext.PagingToolbar({
				pageSize: 25,
				id:'ptbeaux',
				store: stprocess,
				displayInfo: true,
				displayMsg:" Resultados {0} - {1} de {2}",
				emptyMsg: "Ning&uacute;n resultado para mostrar."
			}),
        renderTo: Ext.getBody()
    });
   
           ////------------ Trabajo con el PagingToolbar ------------////
			Ext.getCmp('ptbeaux').on('change',function(){
				sm.getLastSelected();
			},this)





    // //------------ Renderiar el arbol ------------////
    var panel = new Ext.Panel({
        layout: 'border',
        title: "Administración de procesos",
        renderTo: 'panel',
        items: [GModulo],
        tbar: [ValidarProceso,ActProceso,DesacProceso,GenTrazasProceso]

    });




    // //------------ ViewPort ------------////
    var vpTema = new Ext.Viewport({
        disable: true,
        layout: 'fit',
        items: [panel]
    })
    stprocess.load({
        params: {
            start: 0,
            limit: 25
        }
    })
            ;
           

    function Configurar_proceso() {
        if (crearproceso)
            this.UIwinCreateProject = new UIwinCreateProject();
        else {
            this.UIwinCreateProject.stTable.reload();
        }
        crearproceso = false;
        this.UIwinCreateProject.show();




    }
    function Adicionar_conexion() {
        actionconexion="Adicionada";
        if (conexion)
            this.UIconexion1 = new UIconexion1();
        conexion = false;
        interface = "add";
        this.UIconexion1.setTitle("Adicionar conexión");
        btnAplicar.show();
        host = true;
        port = true;
        db = true;
        this.UIconexion1.show();
        Resettext();
    }

    function Modificar_conexion() {
        actionconexion="Modificada";
        if (conexion)
            this.UIconexion1 = new UIconexion1();
        conexion = false;
        interface = "mod";
        this.UIconexion1.setTitle("Modificar conexión");
        btnAplicar.hide();
        Ext.getCmp('pass').reset();
        this.UIconexion1.show();
        winconexion1.loadRecord(sm.getLastSelected());

      

    }
   function activar_proceso() {
       
       
     Ext.Ajax.request({
                    url: 'actprocess',
                    method: 'POST',
                    params: {
                        id: sm.getLastSelected().data.id
                    },
                    callback: function(options, success, response) {
                        responseData = Ext.decode(response.responseText);
                        
                        if (responseData == 0)
                        {
                            stprocess.reload();
                            Ext.MessageBox.show({
                                title: 'Proceso activado',
                                msg: 'El proceso se ha activado satisfactoriamente.',
                                icon: Ext.MessageBox.INFO,
                                buttons: Ext.MessageBox.OK
                            })
                     }
                      else if (responseData == 1)
                        {
                            stprocess.reload();
                            Ext.MessageBox.show({
                                title: 'Error de activación',
                                msg: 'El proceso no está validado.',
                                icon: Ext.MessageBox.ERROR,
                                buttons: Ext.MessageBox.OK
                            })
                     }
                      else if (responseData == 2)
                        {
                            stprocess.reload();
                            Ext.MessageBox.show({
                                title: 'Activación',
                                msg: 'El proceso ya está activado.',
                                icon: Ext.MessageBox.ERROR,
                                buttons: Ext.MessageBox.OK
                            })
                     }

                    }

                })
     
   }
    function desac_proceso() {
       
       
     Ext.Ajax.request({
                    url: 'deacprocess',
                    method: 'POST',
                    params: {
                        id: sm.getLastSelected().data.id
                    },
                    callback: function(options, success, response) {
                       
                         stprocess.reload();
                         responseData = Ext.decode(response.responseText);
                        
                        if (responseData == 0)
                        {
                          
                            Ext.MessageBox.show({
                                title: 'Proceso desactivado',
                                msg: 'El proceso se ha desactivado satisfactoriamente.',
                                icon: Ext.MessageBox.INFO,
                                buttons: Ext.MessageBox.OK
                            })
                     
                        }
                           else if (responseData == 1)
                        {
                          
                            Ext.MessageBox.show({
                                title: 'Error',
                                msg: 'El proceso ya está desactivado.',
                                icon: Ext.MessageBox.ERROR,
                                buttons: Ext.MessageBox.OK
                            })
                     
                        }

                    }

                })
     
   }
   
    function validar_proceso() {
       
       
     Ext.Ajax.request({
                    url: 'valprocess',
                    method: 'POST',
                    params: {
                        id: sm.getLastSelected().data.id
                    },
                    callback: function(options, success, response) {
                       
                         stprocess.reload();
                         responseData = Ext.decode(response.responseText);
                        
                      
                          if(responseData==0){
                            Ext.MessageBox.show({
                                title: 'Proceso validado',
                                msg: 'El proceso se ha validado correctamente',
                                icon: Ext.MessageBox.INFO,
                                buttons: Ext.MessageBox.OK
                            })
                     
                        
                          }
                           else if(responseData==1){
                            Ext.MessageBox.show({
                                title: 'Error de validación',
                                msg: 'El proceso tiene errores en su configuración. No se puede validar.',
                                icon: Ext.MessageBox.ERROR,
                                buttons: Ext.MessageBox.OK
                            })
                     
                        
                          }
                        else if(responseData==2){
                            Ext.MessageBox.show({
                                title: 'Error de validación',
                                msg: 'La configuración del proceso está incompleta.',
                                icon: Ext.MessageBox.ERROR,
                                buttons: Ext.MessageBox.OK
                            })
                     
                        
                          }
                                
                                               else if(responseData==3){
                            Ext.MessageBox.show({
                                title: 'Error de validación',
                                msg: 'La configuración del proceso está incorrecta. Algunas condiciones poseen tipos de datos incompatibles.',
                                icon: Ext.MessageBox.ERROR,
                                buttons: Ext.MessageBox.OK
                            })
                     
                        
                          }
                           else if(responseData==4){
                            Ext.MessageBox.show({
                                title: 'Validación',
                                msg: 'El proceso ya está validado.',
                                icon: Ext.MessageBox.ERROR,
                                buttons: Ext.MessageBox.OK
                            })
                     
                        
                          }
                           else if(responseData==7){
                            Ext.MessageBox.show({
                                title: 'Error en la validación',
                                msg: 'Las condiciones del proceso son insuficientes para relacionar las tablas.',
                                icon: Ext.MessageBox.ERROR,
                                buttons: Ext.MessageBox.OK
                            })
                     
                        
                          }
                          else if(responseData==8){
                            Ext.MessageBox.show({
                                title: 'Error en la validación',
                                msg: 'Algunas columnas seleccionadas en la sección de atributos de evento o de negocio no existen.',
                                icon: Ext.MessageBox.ERROR,
                                buttons: Ext.MessageBox.OK
                            })
                     
                        
                          }

                    }

                })
     
   }
   
    function Resettext() {
        Ext.getCmp('pass').reset();
        Ext.getCmp('user').reset();
        Ext.getCmp('pass').reset();
        Ext.getCmp('host').reset();
        Ext.getCmp('name').reset();
        Ext.getCmp('db').reset();
        Ext.getCmp('port').reset();
    }
    function generartrazasproceso(){
    	Ext.Ajax.request({
            url: 'generartrazasproceso',
            method: 'POST',
            params: {
                idp: sm.getLastSelected().data.id
            },
            callback: function(options, success, response) {
                 responseData = Ext.decode(response.responseText);
                 if(responseData.estado==1){
                     Ext.MessageBox.show({
                         title: 'Generación de trazas de proceso',
                         msg: responseData.cantidad+' trazas de proceso generadas satisfactoriamente',
                         icon: Ext.MessageBox.INFO,
                         buttons: Ext.MessageBox.OK
                     })
              
                 
                   }
                    else if(responseData==4){
                     Ext.MessageBox.show({
                         title: 'Imposible generar trazas de proceso',
                         msg: 'Este proceso no posee registro de activación.',
                         icon: Ext.MessageBox.ERROR,
                         buttons: Ext.MessageBox.OK
                     })
              
                 
                   }
                   else{
					   Ext.MessageBox.show({
                         title: 'Generar trazas de proceso',
                         msg: 'Error generando trazas de proceso',
                         icon: Ext.MessageBox.ERROR,
                         buttons: Ext.MessageBox.OK
                     })
					   }

            }

        })
    }
})


}



