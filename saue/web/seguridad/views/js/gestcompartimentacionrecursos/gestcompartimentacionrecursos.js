	
var perfil = window.parent.UCID.portal.perfil;
//perfil.etiquetas = Object();
UCID.portal.cargarEtiquetas('gestcompartimentacionrecursos', function(){
    cargarInterfaz();
});
Ext.QuickTips.init();

//----------Expresion para validar caracteres-------------------//
var tipos=/(^([a-zA-ZáéíóúñÑ.])+([a-zA-ZáéíóúñÑ\d\.\-\@\#\_\s/]*))$/;
var expresion1 = /(^([a-zA-ZáéíóúñÑ])+([a-zA-ZáéíóúñÑ\d\.\-\@\#\_\s]*))$/;
var caracteres=/^\d+(\d*)?|(^([a-zA-ZÁÉÍÓÚáéíóúñüÑ_ ]+ ?[a-zA-ZÁÉÍÓÚáéíóúñüÑ_ ]* ?[0-9]*))+$/
//-------------------variables----------------------------/
var alias,identidad=0,entidad,idusuario=0,idrol=0;
var table;
var rolacl;
var tipoDato='';
var recurso,permiso;
var oldPermisoSeleccion='';
var oldRecursoSeleccion='';
var campo,idnodo=0;
var posi=-1;
var add=false;
var mod=false;
var elim= false;
var bandera1=false;
var bandera2=false;
var aBandera=false;
var needparam='no';
var btnBuscarUsuario,btnBuscarRol;
var gpRoles,gpUser,gridPrincipal,gpCriterios,gpReglas;
var cbRecursos,cbCriterios,cbPermisos,cbOperadores;
var stComboCriterios,stComboBooleano,stComboRecursos,stComboPermisos,stComboOperadores,stCriterios,stGridPrincipal,stReglas;
var cadena,fecha,numero,regla,cbBoleano,formnuevoper,formmodif;
var panelAgregarRegla,panelModificarRegla,windModificarRegla;//,windAgregarRegla
var arbolEstructura, arrayPadresEliminar = [], arregloDeschequeados = [], arrayTiene = [], arrayReglas = []; 
//funcion principal para cargar la interfaz
var subsistema='';
var service='';
var formPermiso;
var arbolServicio='',padreArbolServicio='',panelServicios='';


function cargarInterfaz(){
	campoincorrecto=new Ext.form.TextField({
        xtype : 'textfield',
        //fieldLabel : 'Valor',
        id : 'campoincorrecto',
        allowBlank : false,
        //emptyText : 'Escriba la regla',					
        anchor : '95%',
        hidden:true
    
    });
    function crearArbol(){
        arbolServicio = new Ext.tree.TreePanel({
            autoScroll:true,
            region:'center',
            split:true,        
            width:'37%',
            loader: new Ext.tree.TreeLoader({
                dataUrl:'obtenerdatosservicios',
                listeners:{
                    'beforeload':function(atreeloader, anode){ 
                        atreeloader.baseParams = {
                            idnodo:anode.attributes.id,
                            service:service,
                            subsistema:subsistema,
                            needparam:needparam
                                 
                        };
                    
                    },
                    //init load
                    'load':function(atreeloader, anode)
                    {
                        atreeloader.baseParams = {
                            idnodo:anode.attributes.id,
                            service:service,
                            subsistema:subsistema,
                            needparam:needparam
                        };
					
                    
                              
                    }                
                }     
            })
        });

        ////------------ Crear nodo padre del arbol ------------////
        padreArbolServicio = new Ext.tree.AsyncTreeNode({
            text: perfil.etiquetas.lbServicios,
            expandable:true,
            id:'0'
        });         
        arbolServicio.setRootNode(padreArbolServicio);
    
        ////------------ Evento para seleccionar en el arbol-------------////
        arbolServicio.on('click', function (node, e){
            if (node.attributes.id > 0)
            {
                idnodo = node.attributes.id; 
                     
            }
        }, this);
    ////------------ panel para cargar los servicios dado criterio-------------////
        panelServicios = new Ext.FormPanel({
            layout:'border',
            items:[arbolServicio]
        });

    }  
    ////------------ Ventana de los servicios dado el criterio-------------////
    function crearVentanaServicios(){
        
        crearArbol();
        winServicios= new Ext.Window({
            modal: true,
            closeAction:'hide',
            layout:'fit',
            closable:false,
            title:perfil.etiquetas.lbVentanaServicio,
            width:300,
            height:300,
            resizable:false,
                            
            buttons:[
            {
                icon:perfil.dirImg+'cancelar.png',
                iconCls:'btn',
                id:'estcan',
                text:perfil.etiquetas.lbBtnCancelar,
                handler:function(){                
                    arbolServicio.collapseAll(); 
                    winServicios.hide();
                    idnodo=0;
                
                }
            },
            {
                icon:perfil.dirImg+'aceptar.png',
                iconCls:'btn',
                id:'estacept',
                handler:function(){
                
                    if(idnodo!=0 && tipoDato=='cadena'){
                        campo.setValue(arbolServicio.getNodeById(idnodo).attributes.text);
               
                        arbolServicio.collapseAll();
               
                        winServicios.hide(); 
                        idnodo=0;              
                    }else if(idnodo!=0 && tipoDato=='numerico'){
                        campo.setValue(arbolServicio.getNodeById(idnodo).attributes.id);               
               
                        arbolServicio.collapseAll();
                        winServicios.hide();
                        idnodo=0;
                              
                    }else{
                        mostrarMensaje(1,perfil.etiquetas.MsgInfArbolServ);
                    }
                
                },
                text:perfil.etiquetas.lbBtnAceptar
            }]
        });
        arbolServicio.collapseAll();               
        winServicios.add(panelServicios);
        winServicios.doLayout();
        winServicios.show();
        
    }
    

    /***inicializar campo que se genera aleatorio***/
    campo=new Ext.form.TextField({
        xtype : 'textfield',
        //fieldLabel : 'Valor',
        id : 'campovalor',
        allowBlank : false,
        //emptyText : 'Escriba la regla',					
        anchor : '95%',
        hidden:true
    
    });
 
    /*******DEFICNICION STORES*******/
    stComboCriterios= new Ext.data.Store({  
        url : 'cargarcombocriterios',      
        //autoLoad : true,
        fields: ['alias', 'tipodato','subsistema','service'],
        reader : new Ext.data.JsonReader({
            root : "criterios",
            id : "idcriterio"
        }, [{
            name : 'alias'
        },{
            name : 'tipodato'
        },{
            name : 'subsistema'
        },{
            name : 'service'
        },{
            name:'needparam'
        }])
   
   
    });
    	
    stComboOperadores= new Ext.data.Store({
        url : 'cargarcombooperadores',
        //autoLoad : true,
       
        reader : new Ext.data.JsonReader({
            root : "operadores",
            id : "idoperador"
        }, [{
            name : 'idoperador'
        }, {
            name : 'denominacion'
        },{
            name : 'valor'
        }])
    });
    
 
    stComboBooleano= new Ext.data.SimpleStore({   
        fields: ['idbooleano', 'valor'],        
        data: [[1,true],[0,false]]
        
    });
    
    
    /*****DEFINICION COMBOS ADICIONAR REGLA******/
    cbCriterios = new Ext.form.ComboBox({
     
        allowBlank : false,		
        fieldLabel : perfil.etiquetas.lbCriterio,
        anchor : '95%',
        readOnly : true,
        store : stComboCriterios,
        displayField : 'alias',   
        triggerAction : 'all',
        mode : 'local',
        editable : false,
        emptyText :perfil.etiquetas.MsgemptyTextCriterio
    
    });
        
 
    cbOperadores = new Ext.form.ComboBox({
        allowBlank : false,		
        fieldLabel : perfil.etiquetas.lbComparacion,
        anchor : '95%',
        //readOnly : true,
        store : stComboOperadores,
        //id:'idoperador',
        displayField : 'valor',
        valueField : 'valor',
        hiddenName : 'idoperador',
        triggerAction : 'all',
        mode : 'local',
        editable : false,
        emptyText : perfil.etiquetas.MsgemptyTextOperador
    
    });
   
    
    regla = new Ext.form.TextField({
        xtype : 'textfield',
        fieldLabel : perfil.etiquetas.lbReglas,
        id : 'newregla',
        allowBlank : false,
        emptyText : perfil.etiquetas.MsgemptyTextRegla,					
        anchor : '95%',
        regex:/(^([a-zA-ZáéíóúñÑ])+([a-zA-ZáéíóúñÑ\d\.\-\@\#\_\s]*))$/,
        maskRe:caracteres
    
    });
    
     stComboPermisos= new Ext.data.SimpleStore({   
        fields: ['idpermiso', 'denominacion','privilege'],        
        data: [[0,'ver','SELECT'],[1,'eliminar','DELETE'],[2,'modificar','UPDATE'],[3,'insertar','INSERT']]
        
    });
    stComboRecursos= new Ext.data.Store({
        url : 'cargarcomborecursos',
        autoLoad : true,
        reader : new Ext.data.JsonReader({
            root : "recursos",
            id : "id"
        }, [{
            id:'id'
        },{
            name : 'alias'
        },{
            name: 'table_name'
        }])
    });
    
    
    
    /**********PANEL AGREGAR REGLA************/
    panelAgregarRegla=new Ext.FormPanel({
        id:'panelAgregarRegla',
        labelAlign : 'left',
        frame : true,
        bodyStyle : 'padding:5px 5px 0',
        layout:'form',
        items:	[
        regla,
        cbCriterios,        
        cbOperadores       
							
        ]                       
      
    });
    /********END PANEL AGREGAR REGLA********/ 
    
    /***********EVENTO DEL COMBO TIPO DATO************/
    this.cbCriterios.on('select',function(cmb,record,index){ 
		Ext.getCmp('newregla').on("blur", function (f){
                           f.setValue(limpiarEspacios(f.getValue()));
                        })
       tipoDato=record.data.tipodato;
       service=record.data.service;
       needparam=record.data.needparam;
       subsistema=record.data.subsistema;
       configurarCampoParaModificar(tipoDato,service,subsistema,needparam);
          
    },this); 
    /*******END EVENTO COMBO TIPO DATO*******/   
    
    /******VENTANA AGREGAR REGLA*******/    
    
    
    function crearVentanaAdicionarRegla(){
        windAgregarRegla= new Ext.Window({        
            modal : true,
            closeAction : 'hide',
            layout : 'fit',
            title : perfil.etiquetas.lbVentanaRegla,
            width : 400,
            height : 280,   
            buttons : [{
                icon : perfil.dirImg + 'cancelar.png',
                iconCls : 'btn',
                text : perfil.etiquetas.lbBtnCancelar,
                handler : function() {
                    limpiarCamposRegla();
                    gpCriterios.getView().refresh();
                    windAgregarRegla.hide();
                    posi=-1;
                    gpCriterios.getSelectionModel().clearSelections();
                     btnModificarRegla.disable();
                     btnEliminarRegla.disable();
                }
            },  {
                icon : perfil.dirImg + 'aceptar.png',
                iconCls : 'btn',
                text : perfil.etiquetas.lbBtnAceptar,
                handler : function() {
                    arrayReglas=[];
                    llenarArregloReglas();
                    if (panelAgregarRegla.getForm().isValid()) {
                    if(validarCamposReglas()){
                        
                        if(!estaEnGridCrit(regla.getRawValue()) && !estaCamp(cbCriterios.getRawValue())){
                            fila = Ext.data.Record.create(['nombreregla', 'campo','operador','valor']);
                            record = new fila({
                                "nombreregla":regla.getRawValue(), 
                                "campo":cbCriterios.getRawValue(),
                                "operador":cbOperadores.getRawValue(),
                                "valor":campo.getRawValue()
                            });
                     
                    
                            arrayReglas.push(record.data);
                    
                            stCriterios.load({
                                params:{
                                    idusuario:idusuario,
                                    idrol:idrol,
                                    permiso:cbPermisos.getRawValue(),
                                    recurso:cbRecursos.getRawValue(),
                                    identidad:identidad,
                                    arrayReglas:Ext.encode(arrayReglas)
                                }
                            });
                            limpiarCamposRegla();
                            windAgregarRegla.hide();
                            mostrarMensaje(1,perfil.etiquetas.MsgInfAddRegla);
                        }else if(estaEnGridCrit(regla.getRawValue())){
                            mostrarMensaje(1,perfil.etiquetas.lbMsgInfAddreglaI);
                        }else {
                            mostrarMensaje(1,perfil.etiquetas.lbMsgInfAddreglaII);
                        }
                    }else{
                        mostrarMensaje(1,perfil.etiquetas.lbMsgCamposVacios);
                    } 
					}
					else
					mostrarMensaje(3,perfil.etiquetas.lbMsgErrorEnCamops);        
                }
            }]
        });
	
     
        limpiarCamposRegla();
        windAgregarRegla.add(panelAgregarRegla);
        windAgregarRegla.doLayout();
        windAgregarRegla.show();
    }
    
    
   
    /*******END WINDOW AGREGAR REGLA***********/
    function crearVentanaModificarRegla(){
        windModRegla= new Ext.Window({        
            modal : true,
            closeAction : 'hide',
            layout : 'fit',
            title : perfil.etiquetas.lbModificarRegla,
            width : 400,
            height : 280,
       
            //items:[panelAgregarRegla],
            buttons : [{
                icon : perfil.dirImg + 'cancelar.png',
                iconCls : 'btn',
                text : perfil.etiquetas.lbBtnCancelar,
                handler : function() {
                    cbCriterios.reset();
                    campo.reset();                    
                    cbOperadores.reset();
                    regla.reset();
                    panelAgregarRegla.remove(campo,false);			 
                    panelAgregarRegla.doLayout();
                    windModRegla.doLayout();	
                    windModRegla.hide();
                    //storecriterios.reload();
                    gpCriterios.getView().refresh();
                    gpCriterios.getSelectionModel().clearSelections();
                        btnModificarRegla.disable();
                        btnEliminarRegla.disable();
                        posi=-1;
                }
            },  {
                icon : perfil.dirImg + 'aceptar.png',
                iconCls : 'btn',
                text : perfil.etiquetas.lbBtnAceptar,
                handler : function() {
					
					
					arrayReglas=[];
                    llenarArregloReglas();
				    
                    fila = Ext.data.Record.create(['nombreregla', 'campo','operador','valor']);
                    var recordvieja = new fila({
                        "nombreregla":gpCriterios.getSelectionModel().getSelected().data.nombreregla, 
                        "campo":gpCriterios.getSelectionModel().getSelected().data.campo,
                        "operador":gpCriterios.getSelectionModel().getSelected().data.operador,
                        "valor":gpCriterios.getSelectionModel().getSelected().data.valor
                    });
                    var recordnueva = new fila({
                        "nombreregla":regla.getRawValue(), 
                        "campo":cbCriterios.getRawValue(),
                        "operador":cbOperadores.getRawValue(),
                        "valor":campo.getRawValue()
                    });
                    //aki el meollo Ext.getCmp('campoincorrecto').setValue(recordnueva.data.campo);
				if(panelAgregarRegla.getForm().isValid()){
                    if(validarCamposReglas()){	
                       var restoCamp=[];
						var restoNom=[];
						   for(i=0;i<stCriterios.getCount();i++){
							if(stCriterios.getAt(i).data.campo==recordnueva.data.campo && i!=posi){
								restoCamp.push(i);
								}
							}

							if(restoCamp.length==0){
							for(i=0;i<stCriterios.getCount();i++){
								if(stCriterios.getAt(i).data.nombreregla==recordnueva.data.nombreregla && i!=posi){
									restoNom.push(i);
								}
							}

			if(restoNom.length==0){
                            if(recordvieja.data.nombreregla != recordnueva.data.nombreregla||recordvieja.data.campo != recordnueva.data.campo||recordvieja.data.operador != recordnueva.data.operador||recordvieja.data.valor != recordnueva.data.valor ){
				rec=posi;//posi trae el index seleccionado
                            
                            stCriterios.getAt(rec).set('nombreregla',recordnueva.data.nombreregla);
                            stCriterios.getAt(rec).set('campo',recordnueva.data.campo);
                            stCriterios.getAt(rec).set('operador',recordnueva.data.operador);
                            stCriterios.getAt(rec).set('valor',recordnueva.data.valor);
                            gpCriterios.getView().refresh();
                            stCriterios.load({
                                params:{
                                    idusuario:idusuario,
                                    idrol:idrol,
                                    permiso:cbPermisos.getRawValue(),
                                    recurso:cbRecursos.getRawValue(),
                                    identidad:identidad,
                                    arrayReglas:Ext.encode(arrayReglas)
                                }
                            });
                            limpiarCamposRegla();
                          
                        
                        cbCriterios.reset();
                        campo.reset();                    
                        cbOperadores.reset();
                        regla.reset();
                        panelAgregarRegla.remove(campo,false);			 
                        panelAgregarRegla.doLayout();
                        windModRegla.doLayout();	
                        windModRegla.hide();
                        
                        btnModificarRegla.disable();
                        btnEliminarRegla.disable();
                        //posi=-1;
                        mostrarMensaje(1,perfil.etiquetas.lbMsgInfModRegla);
                        }
                        else
                        mostrarMensaje(3,perfil.etiquetas.NoModify);
                    } else{
                          mostrarMensaje(1,perfil.etiquetas.lbMsgInfModReglaI);
                         }

                        }else{
							
                         mostrarMensaje(1,perfil.etiquetas.lbMsgInfModReglaII);
                        }

                  
                }else{
                    mostrarMensaje(1,perfil.etiquetas.lbMsgCamposVacios);
                }
				}
				else
				mostrarMensaje(3,perfil.etiquetas.lbMsgErrorEnCamops); 
            }
        }]
        });
    
    if(posi!=-1){
       var inde=stComboCriterios.find('alias', gpCriterios.getSelectionModel().getSelected().data.campo);
       reco=stComboCriterios.getAt(inde);
       tipoDato=reco.data.tipodato;
       service=reco.data.service;
       needparam=reco.data.needparam;
       subsistema=reco.data.subsistema;
       configurarCampoParaModificar(tipoDato,service,subsistema,needparam);
       regla.setValue(gpCriterios.getSelectionModel().getSelected().data.nombreregla);
       cbCriterios.setValue(gpCriterios.getSelectionModel().getSelected().data.campo);
       cbOperadores.setValue(gpCriterios.getSelectionModel().getSelected().data.operador);
       campo.setValue(gpCriterios.getSelectionModel().getSelected().data.valor);
       
    }
    windModRegla.add(panelAgregarRegla);
    windModRegla.doLayout();
    windModRegla.show();
}

 	
		
	
function crearVentanaAdicionar(){
    
   configurarFormulario();
        
    windNuevoPer = new Ext.Window({
        modal: true,
        closeAction:'hide',
        layout:'form',
        title:perfil.etiquetas.lbAddPermiso,
        items:[formPermiso,gpCriterios],
        buttons:[
        {
            icon:perfil.dirImg+'cancelar.png',
            iconCls:'btn',
            text:perfil.etiquetas.lbBtnCancelar,
            handler:function(){
                oldPermisoSeleccion='';
                oldRecursoSeleccion='';
                limpiarWindow();        
                windNuevoPer.hide();
                arrayReglas=[];
                stGridPrincipal.reload();
            }
        },
        {	
            icon:perfil.dirImg+'aceptar.png',
            iconCls:'btn',
            text:perfil.etiquetas.lbBtnAceptar,
            handler:function(){
                //llenararregloReglas();
                permi=cbPermisos.getRawValue();
                    
                recu=cbRecursos.getRawValue();
                idrol=idrol;
                idusuario=idusuario;
                if(permi!='' && recu!=''){
                    if(existenCriterios()){ 
                        if(!estaPermisoEnGridPrincipal(permi, recu)){                   
                            adicionarPermiso(idusuario,idrol,permi,recu);
                            btnEliminar.disable();
                            btnModificar.disable();
                            stGridPrincipal.reload();
                            stReglas.removeAll(true);
                            gpReglas.disable();
                            limpiarWindow();
                            windNuevoPer.hide();
                            oldPermisoSeleccion='';
                            oldRecursoSeleccion='';
                        }else{
                            mostrarMensaje(1,perfil.etiquetas.lbMsgAddPermiso);
                        }
                    }else{
                        mostrarMensaje(1,perfil.etiquetas.lbMsgAddPermisoI);
                    }
                }else{
                    mostrarMensaje(1,perfil.etiquetas.lbMsgCamposVacios);
                }
                        
            }
        }]
    });
    gpCriterios.store.removeAll(true);    
    windNuevoPer.doLayout();
    windNuevoPer.show();
    oldPermisoSeleccion=cbPermisos.getRawValue();
    oldRecursoSeleccion=cbRecursos.getRawValue();
}
function limpiarWindow(){
    cbCriterios.reset();
    cbRecursos.reset();
     
}

 

/***FUNCION PARA MODIFICAR PERMISO***/
function crearVentanaModificar(){
    
     
    
    
      
  
    configurarFormulario();
        
    windModifPer = new Ext.Window({
        modal: true,
        closeAction:'hide',
        layout:'form',
        title:perfil.etiquetas.lbModPermiso,
        items:[formPermiso,gpCriterios],
        buttons:[
        {
            icon:perfil.dirImg+'cancelar.png',
            iconCls:'btn',
            text:perfil.etiquetas.lbBtnCancelar,
            handler:function(){
                windModifPer.hide();
                limpiarWindow();
                oldPermisoSeleccion='';
                oldRecursoSeleccion='';
                arrayReglas=[];
                stGridPrincipal.reload();
                btnModificar.disable();
                btnEliminar.disable();
            }
        },
        {	
            icon:perfil.dirImg+'aceptar.png',
            iconCls:'btn',
            text:perfil.etiquetas.lbBtnAceptar,
            handler:function(){                  
                permisonuevo=cbPermisos.getRawValue();                    
                recursonuevo=cbRecursos.getRawValue();
                permisoviejo=gridPrincipal.getSelectionModel().getSelected().data.permiso;
                recursoviejo=gridPrincipal.getSelectionModel().getSelected().data.recurso;
                idr=idrol;
                idu=idusuario;
                //llenar arrayReglas con reglas del gridcriterios
                arrayReglas=[];
                llenarArrayReglasParaModificar();
               
                if(permisonuevo!='' && recursonuevo!=''){
                    
                    if(existenCriterios()){
                        if(permisonuevo==permisoviejo && recursonuevo==recursoviejo){
                            modificarPermiso(idu,idr,permisoviejo,recursoviejo,permisonuevo,recursonuevo);
                            stGridPrincipal.reload();
                            btnEliminar.disable();
                            btnModificar.disable();                            
                            stReglas.removeAll(true);
                            gpReglas.disable();
                            windModifPer.hide();
                            limpiarWindow();
                            oldPermisoSeleccion='';
                            oldRecursoSeleccion='';
                            
                        }else 
                        if(!estaPermisoEnGridPrincipal(permisonuevo,recursonuevo)){
                            modificarPermiso(idu,idr,permisoviejo,recursoviejo,permisonuevo,recursonuevo);
                            stGridPrincipal.reload();
                            btnEliminar.disable();
                            btnModificar.disable();                            
                            stReglas.removeAll(true);
                            gpReglas.disable();
                            windModifPer.hide();
                            limpiarWindow();
                            oldPermisoSeleccion='';
                            oldRecursoSeleccion='';
                        }else{
                            mostrarMensaje(1,perfil.etiquetas.lbMsgModPermiso);
                        }
                    }else{
                        mostrarMensaje(1,perfil.etiquetas.lbMsgModPermisoI);
                    }
                
                  
                }else{
                    mostrarMensaje(1,perfil.etiquetas.lbMsgCamposVacios);
                }
                        
            }
        }]
    });
    stCriterios.load({
        params:{
            identidad:identidad,
            idrol:idrol,
            idusuario:idusuario,
            permiso:permiso,
            recurso:recurso,
            arrayReglas:Ext.encode(arrayReglas)
        }
    });
     
    cbPermisos.setValue(gridPrincipal.getSelectionModel().getSelected().data.permiso);
    cbRecursos.setValue(gridPrincipal.getSelectionModel().getSelected().data.recurso);
    stComboCriterios.load({
        params:{
            permiso:gridPrincipal.getSelectionModel().getSelected().data.permiso,
            recurso:gridPrincipal.getSelectionModel().getSelected().data.recurso
        }
    });
    oldPermisoSeleccion=cbPermisos.getRawValue();
    oldRecursoSeleccion=cbRecursos.getRawValue();    
    windModifPer.doLayout();
    windModifPer.show();
    
    
}

function configurarFormulario(){

 stCriterios =  new Ext.data.Store({	
        url:'cargargridcriterios',            
        reader:new Ext.data.JsonReader({
            totalProperty: "cantidad_filas",
            root: "datos"
        },
        [   {
            name:'nombreregla' 
        },
        {
            name:'campo'
        },

        {
            name:'operador'
        },

        {
            name:'valor'
        }
        ]
        )
    });
        
    smgpCriterios=new Ext.grid.RowSelectionModel({
        singleSelect:true
    });
        
    
    /****END STORE GRID CRITERIO****/
    /***COMIENZA COLUMMODEL DEL GRID CRITERIO***/
    cmgpCriterios = new Ext.grid.ColumnModel({
        columns: [
        {
            header: perfil.etiquetas.lbCriterio,
            dataIndex: 'campo',
            width: 100
        },
        {
            header: perfil.etiquetas.lbComparacion,
            dataIndex: 'operador',
            width: 150
        },
        {
            header: perfil.etiquetas.lbVentanaServicio,
            dataIndex: 'valor',
            width: 100
        }
        ]
    });
      
    cbPermisos = new Ext.form.ComboBox({
        allowBlank : false,		
        fieldLabel : perfil.etiquetas.lbPermiso,
        anchor : '95%',
        readOnly : true,
        store : stComboPermisos,
        displayField : 'denominacion',
        valueField : 'privilege',
        hiddenName : 'privilege',
        triggerAction : 'all',
        mode : 'local',
        editable : false,
        emptyText :perfil.etiquetas.MsgemptyTextPermiso
    
    });
    cbRecursos = new Ext.form.ComboBox({
        allowBlank : false,		
        fieldLabel : perfil.etiquetas.lbRecurso,
        anchor : '95%',
        readOnly : true,
        store : stComboRecursos,
        displayField : 'alias',
        valueField : 'table_name',
        hiddenName : 'alias',
        triggerAction : 'all',
        mode : 'local',
        editable : false,
        emptyText : perfil.etiquetas.MsgemptyTextRecurso
    
    });
        
    cbRecursos.on('select',function(cmb,record,index){ 
        //table=comborecursos.getValue('alias');
        if(oldPermisoSeleccion!=cbPermisos.getRawValue()||oldRecursoSeleccion!=cbRecursos.getRawValue()){
        permi=cbPermisos.getRawValue();
        if(permi!='' && cbRecursos.getRawValue()!=''){
            stComboCriterios.load({
                params:{
                    permiso:permi,
                    recurso:cbRecursos.getRawValue()
                }
            });
            
            
        }
        stCriterios.load({
            params:{
                idrol:idrol,
                idusuario:idusuario,
                permiso:cbPermisos.getRawValue(),
                recurso:cbRecursos.getRawValue(),
                identidad:identidad
            }
        });
        oldPermisoSeleccion=cbPermisos.getRawValue();
        oldRecursoSeleccion=cbRecursos.getRawValue();
        gpCriterios.getView().refresh();
        gpCriterios.getSelectionModel().clearSelections();
        posi=-1;
        if(btnModificarRegla.enable())
        btnModificarRegla.disable();
        if(btnEliminarRegla.enable())
        btnEliminarRegla.disable();
        }else{
            gpCriterios.getView().refresh();
            gpCriterios.getSelectionModel().clearSelections();
            posi=-1;
            if(btnModificarRegla.enable())
            btnModificarRegla.disable();
            if(btnEliminarRegla.enable())
            btnEliminarRegla.disable();
            }  
    }); 
    cbPermisos.on('select',function(cmb,record,index){ 
        //table=comborecursos.getValue('alias');
        if(oldPermisoSeleccion!=cbPermisos.getRawValue()||oldRecursoSeleccion!=cbRecursos.getRawValue()){
        recu=cbRecursos.getRawValue();
        if(recu!='' && cbPermisos.getRawValue()!=''){
            stComboCriterios.load({
                params:{
                    permiso:cbPermisos.getRawValue(),
                    recurso:cbRecursos.getRawValue()
                }
            });
                
        }
        stCriterios.load({
            params:{
                idrol:idrol,
                idusuario:idusuario,
                permiso:cbPermisos.getRawValue(),
                recurso:cbRecursos.getRawValue(),
                identidad:identidad
            }
        });
        oldPermisoSeleccion=cbPermisos.getRawValue();
        oldRecursoSeleccion=cbRecursos.getRawValue();
        gpCriterios.getView().refresh();
        gpCriterios.getSelectionModel().clearSelections();
        posi=-1;
        if(btnModificarRegla.enable())
        btnModificarRegla.disable();
        if(btnEliminarRegla.enable())
        btnEliminarRegla.disable();
        
        }else{
            gpCriterios.getView().refresh();
            gpCriterios.getSelectionModel().clearSelections();
            posi=-1;
            if(btnModificarRegla.enable())
            btnModificarRegla.disable();
            if(btnEliminarRegla.enable())
            btnEliminarRegla.disable();
            }     
    }); 
      
      
      
    
    formPermiso = new Ext.FormPanel({
        frame:true,
        width: 400,
        bodyStyle:'padding:5px 5px 0',
        items:[cbPermisos,cbRecursos]
            
    });	
    
    formPermiso.doLayout();
        
    btnModificarRegla = new Ext.Button({
        id:'btnModPerm', 
        icon:perfil.dirImg+'modificar.png',
        iconCls:'btn', 
        text:perfil.etiquetas.lbBtnModificar,
        disabled:true,
        handler:function(){
            
            crearVentanaModificarRegla();
            btnModificarRegla.disable();
        }
    });
    
    btnEliminarRegla = new Ext.Button({
        id:'btnEliRegla', 
        icon:perfil.dirImg+'eliminar.png',
        iconCls:'btn', 
        text:perfil.etiquetas.lbBtnEliminar,
        disabled:true,
        handler:function(){
           
            var rec = gpCriterios.getSelectionModel().getSelected();
		
            if (!rec) {
                Ext.Msg.alert(3, perfil.etiquetas.MsgemptyTextReglaI);
                btnEliminarRegla.disable();
            }
            else {
                Ext.MessageBox.confirm(perfil.etiquetas.lbNombMsgConfirmacion,perfil.etiquetas.lbMsgConfirmacionEliminar, function(btn){
                    if(btn=='yes'){
                gpCriterios.store.remove(rec);
                gpCriterios.getView().refresh();
                llenarArregloReglas();
                mostrarMensaje(1,perfil.etiquetas.lbMsgInfEliminarRegla);                
                btnModificarRegla.disable();
                btnEliminarRegla.disable();
                   
                }
                });
                
            } 
			
    
            
            
        }
    });
        
        
    gpCriterios = new Ext.grid.GridPanel({
        store: stCriterios,
        cm: cmgpCriterios,
        sm: smgpCriterios,
        width: 400,
        height: 200,
        autoScroll:true,
        title: perfil.etiquetas.lbReglas,
        frame: true,
        clicksToEdit: 2,
        tbar: [{ 
            icon:perfil.dirImg+'adicionar.png',
            iconCls:'btn',
            text: perfil.etiquetas.lbBtnAdicionar,
            handler:function(){
                if(cbPermisos.getRawValue()!='' && cbRecursos.getRawValue()){
                    gpCriterios.getView().refresh();
                    gpCriterios.getSelectionModel().clearSelections();
                    posi=-1;
                    regla.reset();
                    
                    limpiarCamposRegla();
                    crearVentanaAdicionarRegla();
                    
                }else{
                    mostrarMensaje(1,perfil.etiquetas.lbMsgCamposVacios);
                }
            }
        },btnModificarRegla,
        btnEliminarRegla
        ]
    });
    smgpCriterios.on('rowselect',function(smodel, rowIndex, keepExisting, record){ 
        posi=rowIndex;  
            
        btnModificarRegla.enable();
        btnEliminarRegla.enable();
            
    });

}
    
function modificarPermiso(idu,idr,permisoviejo,recursoviejo,permisonuevo,recursonuevo){
        
          
    Ext.Ajax.request(
    {
        url:'modificarpermiso',
        method:'POST',
        params:{
            idusuario:idu,
            idrol:idr,
            permisoviejo:permisoviejo,
            recursoviejo:recursoviejo,
            permisonuevo:permisonuevo,
            recursonuevo:recursonuevo,
            identidad:identidad,
            arrayReglas:Ext.encode(arrayReglas)
        },
        
        callback: function (options,success,response){
            responseData = Ext.decode(response.responseText);					
				     																										
            mostrarMensaje(responseData.codMsg,perfil.etiquetas.lbMsgInfModPermiso);
            arrayReglas=[];
        }
    });
   
}
    
function llenarArrayReglasParaModificar(){
       
    for(i=0; i<stCriterios.getCount(); i++){
        gpCriterios.getSelectionModel().selectRow(i);        
        arrayReglas.push(gpCriterios.getSelectionModel().getSelected().data);
        gpCriterios.getSelectionModel().clearSelections();
    }
}



function estaPermisoEnGridPrincipal(permi,recu){
    //stGridPrincipal
   
    for(i=0; i<stGridPrincipal.getTotalCount(); i++){
        
        gridPrincipal.getSelectionModel().selectRow(i);        
        if(permi==gridPrincipal.getSelectionModel().getSelected().data.permiso && recu==gridPrincipal.getSelectionModel().getSelected().data.recurso){
            return true;
        }
        gridPrincipal.getSelectionModel().clearSelections();
    }
    return false;
}
        
    
function llenarReglasModificarPermiso(){
         
    for(i=0; i<stReglas.getTotalCount(); i++){
        gpReglas.getSelectionModel().selectRow(i);        
        arrayReglas.push(gpReglas.getSelectionModel().getSelected().data);
        gpReglas.getSelectionModel().clearSelections();
    }
        
    permi=gridPrincipal.getSelectionModel().getSelected().data.permiso;
    recu=gridPrincipal.getSelectionModel().getSelected().data.recurso;        
    stCriterios.load({
        params:{
            idusuario:idusuario,
            idrol:idrol,
            permiso:permi,
            recurso:recu,
            identidad:identidad,
            arrayReglas:Ext.encode(arrayReglas)
        }
    });
}
function llenarGridCriteriosConOnSelect(){
       
    for(i=0; i<stCriterios.getTotalCount(); i++){
        gpCriterios.getSelectionModel().selectRow(i);        
        arrayReglas.push(gpCriterios.getSelectionModel().getSelected().data);
        gpCriterios.getSelectionModel().clearSelections();
    }
    permi=cbPermisos.getRawValue();
    recu=cbRecursos.getRawValue();
        
    stCriterios.load({
        params:{
            idusuario:idusuario,
            idrol:idrol,
            permiso:permi,
            recurso:recu,
            identidad:identidad,
            arrayReglas:Ext.encode(arrayReglas)
        }
    });
}




/**************Arbol de entidades****************/
arbolEntidades = new Ext.tree.TreePanel({
    autoScroll:true,
    region:'center',
    split:true, 
    width:'37%',
    loader: new Ext.tree.TreeLoader({
        dataUrl:'cargarentidades',
        listeners:{
            'beforeload':function(atreeloader, anode){ 
                atreeloader.baseParams = {
                    node:anode.attributes.id,
                    idusuario:gpUser.getSelectionModel().getSelected().data.idusuario,
                    idrol:gpRoles.getSelectionModel().getSelected().data.idrol
                                 
                };
                    
            },
            //init load
            'load':function(atreeloader, anode)
            {
                atreeloader.baseParams = {
                    node:anode.attributes.id,
                    idusuario:gpUser.getSelectionModel().getSelected().data.idusuario,
                    idrol:gpRoles.getSelectionModel().getSelected().data.idrol
                                 
                };
					
                    
                              
            }
        //end load
        }     
    })
});

////------------ Crear nodo padre del arbol ------------////
padreArbolEntidades = new Ext.tree.AsyncTreeNode({
    text: perfil.etiquetas.lbEstructuras,
    expandable:true,
    id:'0'
});         
arbolEntidades.setRootNode(padreArbolEntidades);
    
////------------ Evento para seleccionar la entidad las areas-------------////
arbolEntidades.on('click', function (node, e){
    if (node.id > 0)
    {
        identidad = node.attributes.id;            
        idusuario=gpUser.getSelectionModel().getSelected().data.idusuario;
        idrol=gpRoles.getSelectionModel().getSelected().data.idrol;
        arrayTiene=[];
       
            
                     
    }
}, this);
            
panelEntidades = new Ext.FormPanel({
    layout:'border',
    items:[arbolEntidades]
});
        
/////////////////////////////////

function ventanaEstructura()
{
    gridPrincipal.enable();
    identidad=0;
    arbolEntidades.getSelectionModel().clearSelections();                
    winEstructuras= new Ext.Window({
        modal: true,
        closeAction:'hide',
        layout:'fit',
        closable:false,
        title:perfil.etiquetas.lbEntidades,
        width:300,
        height:300,
        resizable:false,
                            
        buttons:[
        {
            icon:perfil.dirImg+'cancelar.png',
            iconCls:'btn',
            id:'estcan',
            text:perfil.etiquetas.lbBtnCancelar,
            handler:function(){
                //inicializarArboles();
                //arbolEntidades.getRootNode().reload();
                gpRoles.getSelectionModel().clearSelections();
                winEstructuras.hide();
                gridPrincipal.store.removeAll(true);
                gridPrincipal.disable();
                identidad=0;
            }
        },
        {
            icon:perfil.dirImg+'aceptar.png',
            iconCls:'btn',
            id:'estacept',
            handler:function(){
                //cargarEntidadAreaCargo();
                if(identidad>0){                    
                    
                    stGridPrincipal.load({
                        params:{                            
                            idrol:idrol,
                            idusuario:idusuario,
                            identidad:identidad,
                            start:0,
                            limit:3
                        }
                    });
                    gridPrincipal.enable();
                    btnAdicionar.enable();
                    //btnModificar.enable();
                   
                    winEstructuras.hide();
                }else{
                    mostrarMensaje(1,perfil.etiquetas.MsgemptyTextEntidad);
                }
            },
            text:perfil.etiquetas.lbBtnAceptar
        }]
    });
    arbolEntidades.collapseAll();               
    winEstructuras.add(panelEntidades);
    winEstructuras.doLayout();
    winEstructuras.show();
}
////////////////////////////////
    
/*******************END ARBOL ESTRUCTURAS******************/
      
        					
/*********BOTONES DEL TBAR PRINCIPAL***********/
btnAdicionar = new Ext.Button({
    id:'btnAgrFunc', 
    icon:perfil.dirImg+'adicionar.png', 
    iconCls:'btn', 
    text:perfil.etiquetas.lbBtnAdicionar, 
    disabled:true,
    handler:function(){
        crearVentanaAdicionar();
    }
});    
btnModificar = new Ext.Button({
    id:'btnModPerm', 
    icon:perfil.dirImg+'modificar.png',
    iconCls:'btn', 
    text:perfil.etiquetas.lbBtnModificar,
    disabled:true,
    handler:function(){
        crearVentanaModificar();
    }
});
btnActMongo = new Ext.Button({
    id:'btnActReglas', 
    icon:perfil.dirImg+'modificar.png',
    iconCls:'btn', 
    text:perfil.etiquetas.lbBtnActReglas,
    disabled:true,
    handler:function(){
       activarMongodb();
    }
});
btnDesactMongo = new Ext.Button({
    id:'btnDesactReglas', 
    icon:perfil.dirImg+'modificar.png',
    iconCls:'btn', 
    text:perfil.etiquetas.lbBtnDesactivarReglas,
    disabled:true,
    handler:function(){
       desactivarMongodb();
    }
});
btnEliminar = new Ext.Button({
    id:'btnEliPerm', 
    icon:perfil.dirImg+'eliminar.png',
    iconCls:'btn', 
    text:perfil.etiquetas.lbBtnEliminar,
    disabled:true,
    handler:function(){
        var rec = gridPrincipal.getSelectionModel().getSelected();
		
        if (!rec) {
            Ext.Msg.alert(3, perfil.etiquetas.MsgemptyTextPermiso);
        }
        else {
            Ext.MessageBox.confirm(perfil.etiquetas.lbNombMsgConfirmacion,perfil.etiquetas.lbMsgConfirmacionEliminarI, function(btn){
                if(btn=='yes'){
		
                    Ext.Ajax.request({
		
                        url: 'eliminarpermiso',
                        method:'POST', 
                        waitMsg:perfil.etiquetas.lbMsgWaitEliminarPermiso,
                        params:{
                            permiso:gridPrincipal.getSelectionModel().getSelected().data.permiso,
                            recurso:gridPrincipal.getSelectionModel().getSelected().data.recurso,
                            idrol:idrol,
                            idusuario:idusuario,
                            identidad:identidad
                        },

                        callback: function(options,success,response)
                        {
                            responseData = Ext.decode(response.responseText);
                            if(responseData.codMsg != 3)
                            {   
                                //gridPrincipal.store.remove(rec);
                                //eliminar en la funcion original y reload el store
                                mostrarMensaje(responseData.codMsg,perfil.etiquetas.lbMsgInfEliminarPermiso);
                                stGridPrincipal.reload();
                                stReglas.removeAll(true);
                                gpReglas.disable();
                                btnEliminar.disable();
                                btnModificar.disable();
                                
                                arrayReglas=[];
                            }
                            if(responseData.codMsg == 3) mostrarMensaje(action.result.codMsg,action.result.mensaje);
                        }

                    });
                }
            });
        } 
			
    }
	    
//handler:function(){mostrarMensaje(2, '¿Desea eliminar este permiso?');}
});

btnBuscarRol = new Ext.Button({
    icon:perfil.dirImg+'buscar.png',
    iconCls:'btn',
    text:perfil.etiquetas.lbBtnBuscar,
    //disabled:true,
    handler: function(){
        if(stUser.getCount()>0){
           
            if(gpUser.getSelectionModel().getSelected()){//arreglar mannana
        
        idusuario=gpUser.getSelectionModel().getSelected().data.idusuario;
         buscarRolUsuario(idusuario,rolbuscado.getValue());
         btnAdicionar.disable();
         btnModificar.disable();
         btnEliminar.disable();
         
     }else{mostrarMensaje(3,perfil.etiquetas.MsgemptyTextUsuario);}
        }else{
            mostrarMensaje(3,perfil.etiquetas.lbMsgInfUsuarios);
        }   
    }
		
});
btnBuscarUsuario = new Ext.Button({
    icon:perfil.dirImg+'buscar.png',
    iconCls:'btn',
    text:perfil.etiquetas.lbBtnBuscar,
    handler: function(){//cambios
       
          buscarNombreUsuario(Ext.getCmp('nombreusuario').getValue());
          if(stUser.getCount()>0){
              btnBuscarRol.enable();
          
          }
         stRoles.baseParams={}
          stRoles.load({
        params:{
            start:0,
            limit:15
        }
    });
         gpRoles.disable();
         stGridPrincipal.removeAll(true);
         gridPrincipal.disable(); 
         btnAdicionar.disable();
         btnModificar.disable();
         btnEliminar.disable();   
    }
		
});

/****END BOTONES TBAR PRINCIPAL****/
   
/*****STORES PARA EL TAB DE USUARIOS Y ROLES*****/
////-----------srote con los roles-----////
stRoles =  new Ext.data.Store({	
    url:'cargarroles',

    listeners:{
        'beforeload':function(thisstore,objeto){
            objeto.params.idusuario = idusuario
        }
    },
    reader:new Ext.data.JsonReader({
        totalProperty: "cantidad_filas",
        root: "datos"
    },
    [
    {
        name:'idrol'
    },

    {
        name:'denominacion'
    }
    ]
    )
});
smgpRoles= new Ext.grid.RowSelectionModel({
    singleSelect:true
});
smgpRoles.on('rowselect', function (smodel, rowIndex, keepExisting, record){
    if(stUser.getCount()>0){
           
       if(gpUser.getSelectionModel().getSelected()){//arreglar mannana
        
        idrol=gpRoles.getSelectionModel().getSelected().data.idrol;
              
    
    var rec1 = gpUser.getSelectionModel().getSelected();
    var rec2 = gpRoles.getSelectionModel().getSelected();
    if(rec1!=-1 && rec2!=-1){
        
        ventanaEstructura();
    }
         
     }
        }
    
            
}, this);
////-----------srote con los user-----////
stUser =  new Ext.data.Store({	
    url:'cargarusuarios',
     listeners:{
        'beforeload':function(thisstore,objeto){
            Ext.getCmp('rolbuscado').reset();
            idusuario=0;
        }
    },
    //autoLoad:true,
    reader:new Ext.data.JsonReader({
        totalProperty: "cantidad_filas",
        root: "datos"
    },
    [{
        name:'idusuario'
    },

    {
        name:'nombreusuario'
    }])
});
	
smgpUser= new Ext.grid.RowSelectionModel({
    singleSelect:true
});
smgpUser.on('rowselect', function (smodel, rowIndex, keepExisting, record){
    idusuario=gpUser.getSelectionModel().getSelected().data.idusuario;    
    Ext.getCmp('rolbuscado').reset();
    
    stRoles.load({
        params:{
            start:0,
            limit:15
        }
    });
    
    if(gpReglas.enable()){
        gpReglas.disable();
        gpReglas.store.removeAll(true);
    }
    gpRoles.enable();        
    gridPrincipal.store.removeAll(true);
    gridPrincipal.disable();
    btnAdicionar.disable();
    btnModificar.disable();
    btnEliminar.disable();     
                
			 
            
}, this);
	
/*************END STORES TAB USUARIOS Y ROLES************/
	
/*********STORE DEL GridPanelPrincipaL*************/
	
stGridPrincipal =  new Ext.data.Store({	
    url:'listarpermisosusuario',
    listeners:{
        'beforeload':function(thisstore,objeto){
             btnModificar.disable();
             btnEliminar.disable();
            objeto.params.idusuario =idusuario,
            objeto.params.idrol =idrol,
            objeto.params.identidad =identidad
            
        }
    },
    //autoLoad:true,
    reader:new Ext.data.JsonReader({
        totalProperty: "cantidad_filas",
        root: "datos"
    },
    [
    {
        name:'permiso'
    },

    {
        name:'recurso'
    },
    {
        name:'table_name'
    }
    ]
    )
});
smGridPrincipal=new Ext.grid.RowSelectionModel({
    singleSelect:true
});
smGridPrincipal.on('rowselect', function (smodel, rowIndex, keepExisting, record){
    
    permiso=gridPrincipal.getSelectionModel().getSelected().data.permiso,
    recurso=gridPrincipal.getSelectionModel().getSelected().data.recurso,
    stReglas.load({
        params:{
            recurso:recurso,                            
            permiso:permiso,            
            idrol:idrol,
            idusuario:idusuario,
            identidad:identidad,
            limit:15,
            start:0
        }
    });
    gpReglas.enable();
    btnModificar.enable();
    btnEliminar.enable(); 
    btnActMongo.enable();
    btnDesactMongo.enable();
			 
            
}, this);
/********END STORE GRID PANEL PRINCIPAL**********/
/********COMIENZA EL STORE DE LAS REGLAS POR PERMISO*********/
stReglas =  new Ext.data.Store({	
    url:'cargarreglas',
    listeners:{
        'beforeload':function(thisstore,objeto){
            objeto.params.idusuario =idusuario,
            objeto.params.idrol =idrol,
            objeto.params.identidad =identidad,
            objeto.params.permiso =permiso,
            objeto.params.recurso =recurso
        }
    },
    reader:new Ext.data.JsonReader({
        totalProperty: "cantidad_filas",
        root: "datos"
    },
    [   {
        name:'nombreregla'
    }
    ]
    )
});
	
smgpReglas= new Ext.grid.RowSelectionModel({
    singleSelect:true
});
smgpReglas.on('rowselect', function (smodel, rowIndex, keepExisting, record){
    
        
});
    
/******END STORE DE LA REGLAS POR PERMISO******/
	
/***COMIENZA PANEL DE USUARIOS Y ROLES***/
	
    
gpRoles=new Ext.grid.GridPanel({
    //title: 'Roles',
    frame:true,
    anchor:'100%',
    height:560,
    iconCls:'icon-grid',
    store:stRoles,
    autoExpandColumn:'idrol',
    sm:smgpRoles,
    style:'z-index:0',
    disabled:true,
    columns: [
    {
        header: perfil.etiquetas.lbRolI, 
        width:150, 
        dataIndex: 'denominacion', 
        id:"idrol"
    }
    ],
    loadMask:{
        store:stRoles
    },
    tbar:[
    new Ext.Toolbar.TextItem({
        text:perfil.etiquetas.lbRol
    }),
    rolbuscado = new Ext.form.TextField({
        width:80, 
        id: 'rolbuscado',
        regex:/(^([a-zA-ZáéíóúñüÑ])+([a-zA-ZáéíóúñüÑ0-9_]*))$/,
        maskRe:caracteres
    }),
    new Ext.menu.Separator(),			
    btnBuscarRol
    ],
    bbar:new Ext.PagingToolbar({
        pageSize: 15,
        id:'ptbaux1',
        store: stRoles            
    })			 	 
});
			
gpUser=new Ext.grid.GridPanel({
    //title: 'Usuarios',
    frame:true,
    anchor:'100%',
    height:560,
    iconCls:'icon-grid',
    store:stUser,
    autoExpandColumn:'idusuario',
    sm: smgpUser,
    columns: [
    {
        hidden: true, 
        hideable: false, 
        dataIndex: 'idusuario'
    },

    {
        header: perfil.etiquetas.lbUsuario, 
        width:150, 
        dataIndex: 'nombreusuario', 
        id:"idusuario"
    }
    ],
    loadMask:{
        store:stUser
    },
    tbar:[
    new Ext.Toolbar.TextItem({
        text:perfil.etiquetas.lbUsuario
    }),
    nombreusuario = new Ext.form.TextField({//cambios
        width:80, 
        id: 'nombreusuario',
        regex:/(^([a-zA-ZáéíóúñüÑ])+([a-zA-ZáéíóúñüÑ0-9_]*))$/,
        maskRe:caracteres
    }),
    new Ext.menu.Separator(),			
    btnBuscarUsuario
    ],		 
    bbar:new Ext.PagingToolbar({
        pageSize: 15,
        id:'ptbaux2',
        store: stUser
        
    })
});
    
gpReglas=new Ext.grid.GridPanel({
    //title: 'Usuarios',
    frame:true,
    anchor:'100%',
    height:560,
    iconCls:'icon-grid',
    store:stReglas,
    autoExpandColumn:'nombreregla',
    sm: smgpReglas,
    style:'z-index:0',
    disabled:true,
    columns: [
    {
        header: perfil.etiquetas.lbReglas, 
        width:150, 
        dataIndex: 'nombreregla', 
        id:"nombreregla"
    }
    ],
    loadMask:{
        store:stReglas
    },
			 
    bbar:new Ext.PagingToolbar({        
        id:'ptbaux3',
        store: stReglas
    })
});
	
/*****END TAB PANEL DE USUARIOS Y ROLES*****/
	

	
	
/*****GRID ASOCIACION PERMISO RECURSOS******/
gridPrincipal = new Ext.grid.GridPanel({
    frame:true,
    //region:'center',
    iconCls:'icon-grid',
    autoExpandColumn:'permiso',
    store:stGridPrincipal,
    sm:smGridPrincipal,
    disabled:true,
    style:'z-index:0',
    height:560,
    anchor:'100%',
    columns: [
    {
        header: perfil.etiquetas.lbPermiso, 
        width:100, 
        dataIndex: 'permiso',
        id:'permiso'
    },

    {
        header: perfil.etiquetas.lbRecurso, 
        width:150, 
        dataIndex: 'recurso'
    }
    ],
    loadMask:{
        store:stGridPrincipal
    },
    bbar:new Ext.PagingToolbar({       
        id:'ptbaux',
        store: stGridPrincipal//,
        
    })
});
    
panelGeneral = new Ext.FormPanel({
    region:'center',
    width:1000,
    layout:'column',
    frame:true,
    items:[{
        columnWidth:.25,
        layout: 'form',
        items: [gpUser]
    },{
        columnWidth:.25,
        layout: 'form',
        items: [gpRoles]
    },{
        columnWidth:.26,
        layout: 'form',
        items: [gridPrincipal]
    },{
        columnWidth:.24,
        layout: 'form',
        items: [gpReglas]
    }]
    
});
	
/****END GRID ASOCIOACION PERMISO RECURSOS****/
//FUNCIONES AUXILIARES//
                
function adicionarPermiso(idu,idr,permiso,recurso){
    //seguir aki
          
    Ext.Ajax.request(
    {
        url:'adicionarpermiso',
        method:'POST',
        params:{
            idusuario:idu,
            idrol:idr,
            permiso:permiso,
            recurso:recurso,
            identidad:identidad,
            arrayReglas:Ext.encode(arrayReglas)
        },
        
        callback: function (options,success,response){
            responseData = Ext.decode(response.responseText);					
				     																										
            mostrarMensaje(responseData.codMsg,perfil.etiquetas.lbMsgInfAddPermiso);
        }
    });
   
}
		

function llenarArregloReglas(){
    arrayReglas = [];
    for(i=0; i<stCriterios.getCount(); i++){             
        arrayReglas.push(stCriterios.getAt(i).data);
        
    }
        
}
    
//EstaEnGridCrit(nombreg) && !EstaCamp(camp)
function estaEnGridCrit(nombreg){
    for(i=0; i<stCriterios.getCount(); i++){
               
        if(stCriterios.getAt(i).data.nombreregla == nombreg){
           
            return true;
        }
        
    }
    return false;
}
function existenCriterios(){
    if(stCriterios.getCount()>0){
        return true;
    }
    return false; 
}
    
function estaCamp(camp){
    for(i=0; i<stCriterios.getCount(); i++){                
        if(stCriterios.getAt(i).data.campo==camp)
            return true;
        
    }
    return false;
}
    
function activarMongodb(){
  
               Ext.Ajax.request({
                 
       url: 'activarMongodb',
       method:'POST',
       //params:{},
       callback: function (options,success,response){
         //responseData = Ext.decode(response.responseText);
         console.debug(response.responseText);
         if(response.responseText == 1)
         {
         mostrarMensaje(1,perfil.etiquetas.lbMsgMongoActivado);
         
         }else{
             //mensaje
             mostrarMensaje(3,perfil.etiquetas.lbMsgNoestaMongo);
            }
        
       }
      });
    
    
}    
    
function desactivarMongodb(){
     

               Ext.Ajax.request({
                 
       url: 'desactivarMongodb',
       method:'POST',
       //params:{},
       callback: function (options,success,response){
         //responseData = Ext.decode(response.responseText);
         console.debug(response.responseText);
         if(response.responseText == 0)
         {
         mostrarMensaje(1,perfil.etiquetas.lbMsgMongoDesactivado);
       
                
         }
       }
       
      });
    
}   
        
   

function validarCamposReglas(){
		
        if(regla.getRawValue()=='' ||cbCriterios.getRawValue()==''|| cbOperadores.getRawValue()==''||campo.getRawValue()==''){
            return false; 
        }
        return true;
    }
    
    function limpiarEspacios(s){
                    var before = s;
                    s = s.replace(/\s+/gi,' '); //sacar espacios repetidos dejando solo uno
                    s = s.replace(/^\s+|\s+$/gi,''); //sacar espacios blanco principio y final
                    return s;
                    }
                    
    function limpiarCamposRegla(){
        cbCriterios.reset();
        campo.reset();                    
        cbOperadores.reset();
        regla.reset();
        panelAgregarRegla.remove(campo,false);			 
        panelAgregarRegla.doLayout();
        
    }
	

            
function buscarNombreUsuario(nombreusuario){
        
		stUser.baseParams = {};
		stUser.baseParams.nombreusuario = nombreusuario;		
            	stUser.load({params:{start:0,limit:15}});
            	stRoles.removeAll(true);
		
	}
function buscarRolUsuario(idusuario){
	   if(idusuario){
           if(Ext.getCmp('rolbuscado').getValue()!='')
	   {
	    stRoles.baseParams = {};
                
				
            	stRoles.load({params:{
					start:0,limit:15,
					rolbuscado : Ext.getCmp('rolbuscado').getValue(),
					idusuario:idusuario
					}});
					
		 stGridPrincipal.removeAll(true);
         gridPrincipal.disable();
         btnEliminar.disable();
         btnModificar.disable();                            
         stReglas.removeAll(true);
         gpReglas.disable();
	   }
	   else
	   {
		stRoles.baseParams = {};
                stRoles.baseParams.idusuario=idusuario;
				
            	stRoles.load({params:{start:0,limit:15}});
         stGridPrincipal.removeAll(true);
         gridPrincipal.disable();
         btnEliminar.disable();
         btnModificar.disable();                            
         stReglas.removeAll(true);
         gpReglas.disable();
		}
           }else{
              mostrarMensaje(3,perfil.etiquetas.lbMsgError); 
           }
	}  
        
        function configurarCampoParaModificar(tipoDato,service,subsistema,needparam){
    if(tipoDato=='cadena'){
        
    if(service !='' && subsistema!=''){                
                
                stComboOperadores.load({
                    params:{
                        tipo:'cadena'
                    }
                })
                Ext.getCmp('panelAgregarRegla').remove(campo,false);
                campo=new Ext.form.TextField({
                    xtype : 'textfield',
                    fieldLabel : perfil.etiquetas.lbCadena,
                    id : 'cadena',
                    name: 'cadena',	
                    allowBlank:false,
                    // hidden : true, 			
                    anchor : '95%',
                    regex:/(^([a-zA-ZáéíóúñÑ])+([a-zA-ZáéíóúñÑ\d\.\-\@\#\_\s]*))$/,
                    maskRe:caracteres,
                    tabIndex:6, 
                    listeners:{
                        'focus':function(text){                                                         
                            crearVentanaServicios();                                            
                        }
                    }
    
                });
                Ext.getCmp('panelAgregarRegla').add(campo);	
                Ext.getCmp('panelAgregarRegla').doLayout();
                
            }else{
            
                //panelAgregarRegla.remove(comboleano,false);
                //panelAgregarRegla.remove(fecha,false);
                //panelAgregarRegla.remove(numero,false);
                stComboOperadores.load({
                    params:{
                        tipo:'cadena'
                    }
                })
                Ext.getCmp('panelAgregarRegla').remove(campo,false);
                campo=new Ext.form.TextField({
                    xtype : 'textfield',
                    fieldLabel : perfil.etiquetas.lbCadena,
                    id : 'cadena',
                    name: 'cadena',
                    allowBlank:false,                   
                    // hidden : true, 			
                    anchor : '95%',
                    regex:/(^([a-zA-ZáéíóúñÑ])+([a-zA-ZáéíóúñÑ\d\.\-\@\#\_\s]*))$/,
                    maskRe:caracteres
    
                });
                Ext.getCmp('panelAgregarRegla').add(campo);	
                Ext.getCmp('panelAgregarRegla').doLayout();
            }
        
    }else if(tipoDato=='numerico'){
        
    if(service !='' && subsistema!=''){                
                
                stComboOperadores.load({
                    params:{
                        tipo:'numerico'
                    }
                })
                Ext.getCmp('panelAgregarRegla').remove(campo,false);
                campo=new Ext.form.NumberField({
                    xtype : 'numberfield',
                    fieldLabel : perfil.etiquetas.lbNumero,
                    id : 'numero',
                    name: 'numero',	
                    allowBlank:false,
                    // hidden : true, 			
                    anchor : '95%',
                    tabIndex:6, 
                    listeners:{
                        'focus':function(text){                                                         
                            crearVentanaServicios();                                            
                        }
                    }
    
                });
                Ext.getCmp('panelAgregarRegla').add(campo);	
                Ext.getCmp('panelAgregarRegla').doLayout();
                
            }else{
            
                stComboOperadores.load({
                    params:{
                        tipo:'numerico'
                    }
                })
                Ext.getCmp('panelAgregarRegla').remove(campo,false);
                campo=new Ext.form.NumberField({
                    xtype : 'numberfield',
                    fieldLabel : perfil.etiquetas.lbNumero,
                    allowBlank:false,
                    id : 'numero',
                    name: 'numero', 
                    anchor : '95%'
                    
    
                });
                Ext.getCmp('panelAgregarRegla').add(campo);	
                Ext.getCmp('panelAgregarRegla').doLayout();
            }
        
    }
    else if(tipoDato=='fecha'){        
                   
                
                stComboOperadores.load({
                    params:{
                        tipo:'fecha'
                    }
                })
                Ext.getCmp('panelAgregarRegla').remove(campo,false);
                campo=new Ext.form.DateField({
                    xtype : 'datefield',
                    fieldLabel : perfil.etiquetas.lbFecha,
                    id : 'fecha',
                    name: 'fecha', 
                    allowBlank:false,                    			
                    anchor : '95%',
                    tabIndex:6, 
                    listeners:{
                        'focus':function(text){                                                         
                            crearVentanaServicios();                                            
                        }
                    }
    
                });
                Ext.getCmp('panelAgregarRegla').add(campo);	
                Ext.getCmp('panelAgregarRegla').doLayout();
                
             
        
    }else if(tipoDato=='booleano'){
        
                   
                
                stComboOperadores.load({
                    params:{
                        tipo:'booleano'
                    }
                })
                Ext.getCmp('panelAgregarRegla').remove(campo,false);
                campo=new Ext.form.ComboBox({
                allowBlank : false,		
                fieldLabel : perfil.etiquetas.lbVentanaServicio,                      
                anchor : '95%',
                readOnly : true,
                allowBlank:false,
                store : stComboBooleano,                
                displayField : 'valor',
                valueField : 'valor',
                hiddenName : 'idbooleano',
                triggerAction : 'all',
                mode : 'local',
                editable : false,
                emptyText : perfil.etiquetas.MsgemptyTextValor
    
            });
                Ext.getCmp('panelAgregarRegla').add(campo);	
                Ext.getCmp('panelAgregarRegla').doLayout();
                
             
        
    }
}
			
//--END FUNCIONES AUXILIARES//
	
/**********"PANEL GENERAL"******/
var panel = new Ext.FormPanel({
    layout:'border',
    title:perfil.etiquetas.lbTitPanelTit,
    items:[panelGeneral],
    tbar:[btnAdicionar,btnModificar,btnEliminar,btnActMongo,btnDesactMongo]
});
/***END PANEL GENERAL***/
/****VIEWPORT****/
var vpGestFuncionalidad = new Ext.Viewport({
    layout:'fit',
    items:panel
});

stUser.load({
    params:{
        start:0,
        limit:15
    }
});
stGridPrincipal.load({
    params:{
        idusuario:idusuario,
        idrol:idrol
    }
});
stRoles.load({
    params:{
        idusuario:idusuario,
        start:0,
        limit:15
    }
});


/***END VIEWPORT***/
}

