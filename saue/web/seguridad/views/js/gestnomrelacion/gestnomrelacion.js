var perfil = window.parent.UCID.portal.perfil;
	perfil.etiquetas = Object();
	UCID.portal.cargarEtiquetas('gestnomrelacion',cargarInterfaz);
	
	////------------ Inicializo el singlenton QuickTips ------------////
	Ext.QuickTips.init();
        
        function cargarInterfaz(){
         /*
          *
          *Declaración de Variables
          *
          */
         var idservicio;var servicio;var subsistema;var newcm;var camposGridDinamico;var criterioSel;
         var servidorSelect;var gestorSelect;var bdSelect;var esquemaSelect;var nombreSelect;
         var desabilitado=true;var nodoActual;
         var btnRelacionar = new Ext.Button({disabled:true,id:'btnAgrRelacion', icon:perfil.dirImg+'modificar.png', iconCls:'btn', text:perfil.etiquetas.btnRelacionar, handler:function(){asignar();}});
         var LastValueEntidad="";
         var LastValueEsquema="";
         var LastValueBD="";
         var LastValueGestor="";
         var LastValueServidor="";
         var FilasColumnasModificadas=new Array();
       var arbolServicios = new Ext.tree.TreePanel({
           title:perfil.etiquetas.cmpArbol,
	   collapsible:true,
	   autoScroll:true,
	   region:'west',
	   split:true,
	   width:'25%',
	   loader: new Ext.tree.TreeLoader({
	           dataUrl:'cargarServicios',
                   listeners:{
                       'load':function(_this,node, response){
                        nodoActual=node.findChild('text',servicio);
                        if(nodoActual!=null)
                        idservicio = nodoActual.attributes.idservicio;                                                  
                       }
                   }
                   
	    })
	});
        
        ////------------ Crear nodo padre del arbol ------------////
    var padreArbolServicios = new Ext.tree.AsyncTreeNode({
	   text:perfil.etiquetas.padreArbolServiciosText,
	   expandable:false,
	   expanded:true,
           id:'0'
      });
     ///Nodo raiz del arbol de acciones/subsistemas	      
   arbolServicios.setRootNode(padreArbolServicios);
         
   arbolServicios.on('click', function (node, e){
           ReiniciarCampos();
           if (node.isLeaf()){
             idservicio = node.attributes.idservicio;
             servicio = node.attributes.text;
             subsistema = node.attributes.subsistema;
             combocriterios.enable();
             nodoActual=node;
             if(!desabilitado)
             storegridObjetos.load({
                        params : {
                        start : 0,limit : 15,idservicio:idservicio,
                        criterio:criterioSel,
                        seleccionados:Ext.getCmp('checkselecteds').getValue()
                        }
                    });
                    
	   }
           else{
               combocriterios.clearValue();
               combocriterios.disable();
               storegridObjetos.removeAll();
               gridObjetosBD.disable();
               desabilitado=true;
           }
           
	   
     }, this);
       
             ///----Store del combocriterios----///
       var stcombo =   new Ext.data.Store({
           url: 'getcriterioSeleccion',
           autoLoad:true,
           reader:new Ext.data.JsonReader({
           id:"criterio"
           },[{
            name: 'criterio',
            mapping:'criterio'
           }
           ])
        });
    ///----Va a traer los criterios de seleccion (Tablas, Secuencias,Vistas,Funciones)----///
    var combocriterios = new Ext.form.ComboBox({
           xtype:'combo',
           store:stcombo,
           disabled:true,
           valueField:'criterio',
           displayField:'criterio',
           triggerAction: 'all',
           editable: false,
           emptyText:perfil.etiquetas.combEmptyText,
           anchor:'50%',
           width:100
     });
    
    combocriterios.on('select',function(c,r){
        gridObjetosBD.enable();
        desabilitado=false;
        btnRelacionar.enable();
        ReiniciarCampos();
        CargarGridObjetosBD();
        
    })
         
    var storegridObjetos = new Ext.data.Store({
            url : '',            
            reader : new Ext.data.JsonReader({
                totalProperty : "totalProperty",
                root : "root",
                messageProperty:"mensaje"
            }, [{
                name : 'vacio'
            }])
        }); 
      
      var smObjetosBD = new Ext.grid.RowSelectionModel({singleSelect : true});
      var cmObjetosBD = new Ext.grid.ColumnModel([{id : 'expandir',autoExpandColumn : 'expandir'}]);
      var gridObjetosBD = new Ext.grid.EditorGridPanel({
            frame : true,
            sm : smObjetosBD,
            clicksToEdit:10,
            region:'center',
            store : storegridObjetos,
            disabled:true,
            autowidth:true,
            visible: true,
            width:'45%',
            title:perfil.etiquetas.gridTitle,
            loadMask : {msg:perfil.etiquetas.msgCargando},
            cm : cmObjetosBD,
            tbar :[
               servidorSelect = new Ext.form.TextField({
               width:80,emptyText:perfil.etiquetas.emptyTextServidor,id:'servidor',
               maskRe:/[0-9.]/i,
               enableKeyEvents:true,
               listeners:{
                      'keyup':function(servidorSelect,e){
                           var input=servidorSelect.getValue();
                          if(input!=LastValueServidor){
                           buscarSobreObjetos()
                           LastValueServidor=input;
                          }
                      }  
                }}),
                new Ext.menu.Separator(),
                gestorSelect = new Ext.form.TextField({
                width:42,emptyText:perfil.etiquetas.emptyTextGestor,id:'gestor',
                enableKeyEvents:true,
                maskRe:/[a-z]/i,
                listeners:{
                      'keyup':function(gestorSelect,e){
                            var input=gestorSelect.getValue();
                          if(input!=LastValueGestor){
                           buscarSobreObjetos()
                           LastValueGestor=input;
                          }
                      } 
                }}),
                new Ext.menu.Separator(),
                bdSelect = new Ext.form.TextField({
                width:80,emptyText:perfil.etiquetas.emptyTextBD,id:'bd',
                maskRe: /[a-zA-Z0-9_]/i,
                enableKeyEvents:true,
                listeners:{
                      'keyup':function(bdSelect,e){
                           var input=bdSelect.getValue();
                          if(input!=LastValueBD){
                           buscarSobreObjetos()
                           LastValueBD=input;
                          }
                      } 
                }}),
                new Ext.menu.Separator(),
                esquemaSelect = new Ext.form.TextField({
                width:109,emptyText:perfil.etiquetas.emptyTextEsquema,id:'esquemas',
                maskRe: /[a-zA-Z0-9_]/i,
                enableKeyEvents:true,
                listeners:{
                      'keyup':function(esquemaSelect,e){
                           var input=esquemaSelect.getValue();
                          if(input!=LastValueEsquema){
                           buscarSobreObjetos()
                           LastValueEsquema=input;
                          }
                      } 
                }}),
                new Ext.menu.Separator(),
                nombreSelect = new Ext.form.TextField({
                width:144,emptyText:perfil.etiquetas.emptyTextEntidad,id:'nombreObj',
                maskRe: /[a-zA-Z0-9_]/i,
                enableKeyEvents:true,
                listeners:{
                      'keyup':function(nombreSelect,e){
                           var input=nombreSelect.getValue();
                          if(input!=LastValueEntidad){
                           buscarSobreObjetos()
                           LastValueEntidad=input;
                          }
                      }
                }}),
               new Ext.menu.Separator(),
               checkSelect=new Ext.form.Checkbox({
                   id:'checkselecteds',
                   boxLabel:perfil.etiquetas.checkBoxLabel,
                   listeners:{
                       'check':function(checkSelect,c){
                           buscarSobreObjetos();
                       }
                   }
           })],
            bbar : new Ext.PagingToolbar({store : storegridObjetos,displayInfo : true,pageSize : 15}),   
            listeners:{
                'cellclick': function (grid,rowIndex,columnIndex,e){
                   var record = grid.getStore().getAt(rowIndex);  // Get the Record
                    var fieldName = grid.getColumnModel().getDataIndex(columnIndex); // Get field name
                    var data = record.get(fieldName);
                    storegridObjetos.getAt(rowIndex).set(newcm.getColumnHeader(columnIndex),!data);
                   CampiarEstadoFilaColumna(rowIndex, columnIndex-11);
                   var own="OWN";var i= 11
                    if(fieldName!=own&&columnIndex>11){
                    data = record.get(own);
                    if(data){
                    storegridObjetos.getAt(rowIndex).set(newcm.getColumnHeader(i),false);
                    CampiarEstadoFilaColumna(rowIndex, i-11);
                    }
                    }
                    else if(fieldName==own){
                        var length=newcm.getColumnCount()
                        while(i<length-1){
                          i++;
                          fieldName = newcm.getDataIndex(i);
                          data = record.get(fieldName);
                        if(data){
                        storegridObjetos.getAt(rowIndex).set(newcm.getColumnHeader(i),false);
                        CampiarEstadoFilaColumna(rowIndex, i-11);
                        }
                        }
                    }
                    
                }}});
    var label1=new Ext.form.Label({
        html:'<pre> </pre>'
    });
    var label2=new Ext.form.Label({
        html:'<pre>  </pre>'
    });
    var label5=new Ext.form.Label({
        html:'<pre>     </pre>'
    });
    var panel = new Ext.Panel({
		layout:'border',
                tbar:[label2,new Ext.form.Label({html:perfil.etiquetas.labelCriterio}),label1,combocriterios,label5,btnRelacionar],
		items:[arbolServicios,gridObjetosBD]
//		keys: new Ext.KeyMap(document,[
//                      {key:Ext.EventObject.DELETE,
//		      fn: function(){if(auxDel && auxDelete && auxDel2 && auxDel3)eliminarAccion();}},
//                      {key:"i",alt:true,fn: function(){if(auxIns && auxIns2 && auxIns3)winForm('Ins');}},
//		      {key:"b",alt:true,fn: function(){if(auxBus && auxBus3)buscaraccion(Ext.getCmp('nombreaccion').getValue());}},
//		      {key:"m",alt:true,fn: function(){if(auxMod && auxMod2 && auxMod3)winForm('Mod');}}])
	});

        //ViewPort
        var vpGestSistema = new Ext.Viewport({
            layout:'fit',
            items:panel
        });
        
        function CargarGridObjetosBD(){
            criterioSel=combocriterios.getValue();
            
            Ext.Ajax.request({            
            url: 'configridObjetos',
            method:'POST',
            params:{criterio: criterioSel},
            callback: function (options,success,response){
               var responseData = Ext.decode(response.responseText);
                camposGridDinamico = responseData.grid.campos; 
                
                var i = 11;                
                while( i < responseData.grid.columns.length){
                    var aux = responseData.grid.columns[i];
                    
                    responseData.grid.columns[i].editor = new Ext.form.Checkbox({checked:false});
                    aux.renderer = function (data,cell, record, rowIndex, columnIndex,store){ 
                        if (data){
                            return "<img src='../../../../images/icons/validado.png' />";
                        }
                        else{
                            return "<img src='../../../../images/icons/no_validado.png' />";
                        }
                    }
                    i++
                }
                Ext.UCID.idiomaHeader(responseData.grid.columns,perfil.etiquetas); 
                
                newcm = Ext.UCID.generaDinamico('cm', responseData.grid.columns);

                storegridObjetos = new Ext.data.Store({
                    url : 'cargargridObjetos',
                    pruneModifiedRecords:true,
                    reader : new Ext.data.JsonReader({
                        totalProperty: 'cantidad',
                        root : 'datos',
                        id : 'iddatos',
                        messageProperty:'mensaje'
                    },Ext.UCID.generaDinamico('rdcampos', responseData.grid.campos)
                        )

                });

              
                var menu = new Ext.menu.Menu({
                    id:'submenu',
                    items:[{
                        text:perfil.etiquetas.menuTextSelectAllFile,
                        scope: this,
                        icon: "../../../../images/icons/añadir.png",
                        handler:function(){
                            storegridObjetos.getAt(fila).set(newcm.getColumnHeader(10),false);
                                for(var j = 11; j < newcm.getColumnCount(); j++){
                                    storegridObjetos.getAt(fila).set(newcm.getColumnHeader(j),true);
                                    CampiarEstadoFilaColumna(fila, j-11);
                                }
                            
                        }
                    },
                    {
                        text:perfil.etiquetas.menuTextSelectAllColumn,
                        scope: this,
                        icon: "../../../../images/icons/añadir.png",
                        handler:function(){
                            for(var i = 0; i < storegridObjetos.getCount(); i++){
                                storegridObjetos.getAt(i).set(newcm.getColumnHeader(col),true);
                                 CampiarEstadoFilaColumna(i, col-11);
                            }
                        }
                    },
                    {
                        text:perfil.etiquetas.menuTextUnCheckAllFile,
                        scope: this,
                        icon: "../../../../images/icons/eliminar.png",
                        handler:function(){
                            
                                for(var j = 11; j < newcm.getColumnCount(); j++){
                                storegridObjetos.getAt(fila).set(newcm.getColumnHeader(j),false);
                                CampiarEstadoFilaColumna(fila, j-11);
                                }
                            
                        }
                    },
                    {
                        text:perfil.etiquetas.menuTextUnCheckAllColumn,
                        scope: this,
                        icon: "../../../../images/icons/eliminar.png",
                        handler:function(){
                            for(var i = 0; i < storegridObjetos.getCount(); i++){
                                storegridObjetos.getAt(i).set(newcm.getColumnHeader(col),false);
                                CampiarEstadoFilaColumna(i, col-11);
                            }
                        }
                    }]
                });
        
                gridObjetosBD.on('cellcontextmenu', function( _this, rowIndex, cellIndex, e){
                    fila = rowIndex;
                    col = cellIndex;
                    smObjetosBD.selectRow(fila);
                    e.stopEvent();
                    menu.showAt(e.getXY());
                },this);


                if (newcm && storegridObjetos){
                    gridObjetosBD.reconfigure(storegridObjetos, newcm);
                    gridObjetosBD.getBottomToolbar().bind(storegridObjetos);
                    
                    
                    storegridObjetos.on('beforeload', function(s){
                     if(criterioSel!="Servicios"){
                    servidorSeleccionado=servidorSelect.getValue();
                    gestorSeleccionado=gestorSelect.getValue();
                    bdSeleccionada=bdSelect.getValue();
                    esquemaSeleccionado=esquemaSelect.getValue();
                    objetoSeleccionado=nombreSelect.getValue();
                   
                    }
                    else{
                    subsistemaSeleccionado=subsistemaSelect.getValue();
                    servicioSeleccionado=servicioSelect.getValue();
                    }
               
                storegridObjetos.baseParams = { 
                        
                        servSelected:servidorSeleccionado,
                        gestSelected:gestorSeleccionado,
                        bdSelected:bdSeleccionada,
                        esqSelected:esquemaSeleccionado,
                        nombSelected:objetoSeleccionado,
                        criterio:criterioSel,
                        idservicio:idservicio,
                        seleccionados:Ext.getCmp('checkselecteds').getValue()
                 };
              
                      });
                    storegridObjetos.on('load', function(s){
                      ReiniciarFilasColumnas();
                      
                });
                
                    storegridObjetos.load({
                        params : {
                        start : 0,limit : 15,idservicio:idservicio,
                        criterio:criterioSel,
                        seleccionados:Ext.getCmp('checkselecteds').getValue()
                        }
                    });
                }
            }
        });
        }
        
        function CampiarEstadoFilaColumna(fila,columna){
            if(IsPosicionModificado(fila, columna))
                pop(fila, columna);
            else
                push(fila, columna)
        }
        
        function push(fila,columna){
            FilasColumnasModificadas[fila][columna]=1;
        }
        function pop(fila,columna){
            FilasColumnasModificadas[fila][columna]=0;
        }
        
        function IsPosicionModificado(fila,columna){
            if(FilasColumnasModificadas[fila][columna]==1)
            return true;
        return false;
        }
        
        function IsModificados(){
            for(var i=0; i<FilasColumnasModificadas.length;i++){
                for (var j=0; j<FilasColumnasModificadas[i].length;j++){
                    if(FilasColumnasModificadas[i][j]==1)
                        return true;
                }
            }
        return false;
        }
        
        function ReiniciarFilasColumnas(){
               FilasColumnasModificadas=new Array();
                       var TotalColumn=newcm.getColumnCount();
                       var TotalFila=storegridObjetos.getCount();
                       var length=TotalColumn-3;
                       if(criterioSel!="Servicios")
                           length=TotalColumn-11;
                       for(var i=0 ;i<TotalFila;i++){
                          var columnas=new Array();
                          for(var j=0 ;j<length;j++){
                           columnas.push(0);
                       }
                          FilasColumnasModificadas.push(columnas);
                       }
                }
        
        function ReiniciarCampos(){
            servidorSelect.setValue("");
            gestorSelect.setValue("");
            bdSelect.setValue("");
            esquemaSelect.setValue("");
            nombreSelect.setValue("");
            Ext.getCmp('checkselecteds').setValue(false);
        }

         function buscarSobreObjetos(){
        
        
       
               var servidorSeleccionado=servidorSelect.getValue();
               var gestorSeleccionado=gestorSelect.getValue();
               var bdSeleccionada=bdSelect.getValue();
               var esquemaSeleccionado=esquemaSelect.getValue();
               var objetoSeleccionado=nombreSelect.getValue();
        
        
         
   
        storegridObjetos.load({
            params:{
                servSelected:servidorSeleccionado,
                gestSelected:gestorSeleccionado,
                bdSelected:bdSeleccionada,
                esqSelected:esquemaSeleccionado,
                nombSelected:objetoSeleccionado,
                seleccionados:Ext.getCmp('checkselecteds').getValue(),
                start:0,
                limit:15
            }
        });
        
   }
        function asignar() {
        var filasModificadas = storegridObjetos.getModifiedRecords();
        var cantFilas = filasModificadas.length;
        var cmHis = gridObjetosBD.getColumnModel();
        var cantCol = cmHis.getColumnCount();
        var arrayAcceso = [];
        var arrayDenegado = [];
        var criterio=criterioSel.toLowerCase();
                    
        for (var i = 0; i < cantFilas; i++) {            
            var nameFila = filasModificadas[i].data[criterio];
            var idservidor=filasModificadas[i].data.idservidor;
            var servidor=filasModificadas[i].data.servidor;
            var idgestor=filasModificadas[i].data.idgestor;
            var gestor=filasModificadas[i].data.gestor;
            var idbd=filasModificadas[i].data.idbd;
            var bd=filasModificadas[i].data['base de datos'];
            var idesquema=filasModificadas[i].data.idesquema;
            var esquema=filasModificadas[i].data.esquemas;
            var idrol=filasModificadas[i].data.idrol;
            var idobjetobd=filasModificadas[i].data.idobjetobd;            
            var colsFila = filasModificadas[i].getChanges();                                               
            var arrayColAut = [];
            var arrayColDen = [];
            var indexOf=storegridObjetos.indexOf(filasModificadas[i]);
            for (var j = 1; j <= cantCol; j++) {
              if(j>10&&FilasColumnasModificadas[indexOf][j-11]==1){             
              var  nameCampo = camposGridDinamico[j];                               
                var cadEval = 'colsFila.' + nameCampo;
                if(nameCampo!='base de datos')
                    var valCol = eval(cadEval);     
                if (valCol == true)
                    arrayColAut.push(nameCampo);
                else if (valCol == false)
                    arrayColDen.push(nameCampo);
              }
            }
            if (arrayColAut.length)
                arrayAcceso.push([idservidor,idgestor,idbd,idesquema,idrol,nameFila,arrayColAut,idobjetobd,servidor,gestor,bd,esquema]);
            if (arrayColDen.length)
                arrayDenegado.push([idservidor,idgestor,idbd,idesquema,idrol,nameFila,arrayColDen,idobjetobd,servidor,gestor,bd,esquema]);
        }
        if(IsModificados()){
      var  jsonAcceso = Ext.encode(arrayAcceso);
      var  jsonDenegado = Ext.encode(arrayDenegado);
       for(var h=0;h<cantFilas;h++)
       filasModificadas[h].modified=[];
        
            Ext.Ajax.request({
            url: 'modificarPermisos',            
            method: 'POST',             
            params: {
                acceso: jsonAcceso,
                denegado: jsonDenegado,
                idservicio:idservicio,
                servicio:servicio,
                subsistema:subsistema,
                criterio: criterioSel

            },
            callback: function(options, success, response){
               var responseData = Ext.decode(response.responseText);
                if (responseData.codMsg == 1) {
                    var eliminados=responseData.elim;
                    eliminados=Ext.decode(eliminados);
                    var adicionados=responseData.add;
                    adicionados=Ext.decode(adicionados);
                    IdEliminados0(eliminados, filasModificadas);
                    IdAdicionadosX(adicionados,filasModificadas,criterio); 
                    var ids=responseData.ids;
                    nodoActual.attributes.idservicio=ids;
                    idservicio=ids;
                    //mostrarMensaje(responseData.codMsg, responseData.mensaje);
                }
//                if (responseData.codMsg == 3)
//                    mostrarMensaje(responseData.codMsg, responseData.mensaje);
            }
        });
       ReiniciarFilasColumnas();
    }
    else{
        mostrarMensaje(3,perfil.etiquetas.NoModify);
    }
    storegridObjetos.rejectChanges();
    }
    
    function IdAdicionadosX(add,modificadas,criterio){
        for(var i=0; i<add.length;i++){
            CambiarIdObj(add[i], modificadas,criterio)
        }
    }
    function CambiarIdObj(obj,modificadas,criterio){
        for(var i=0; i<modificadas.length;i++){
            var nameFila = modificadas[i].data[criterio];
            var idservidor=modificadas[i].data.idservidor;
            var idgestor=modificadas[i].data.idgestor;
            var idbd=modificadas[i].data.idbd;
            var idesquema=modificadas[i].data.idesquema;
            if(idservidor==obj.idservidor&&idgestor==obj.idgestor&&idbd==obj.idbd&&
                idesquema==obj.idesquema&&nameFila==obj.obj){
                modificadas[i].data.idobjetobd=obj.idobj;
                }
        }
    }
    function IdEliminados0(eliminados,modificadas){
        for(var i=0;i<modificadas.length; i++){
            if(IsEliminado(modificadas[i].data.idobjetobd, eliminados))
                modificadas[i].data.idobjetobd=0;
        }
    }
    function IsEliminado(id,eliminados){
        for(var i=0;i<eliminados.length; i++){
            if(eliminados[i]==id)
                return true;
        }
    return false;
    }
    
}

