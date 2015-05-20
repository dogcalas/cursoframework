var perfil = window.parent.UCID.portal.perfil;
UCID.portal.cargarEtiquetas('gestimagenesusuario', function(){
    cargarInterfaz();
});
////------------ Inicializo el singlenton QuickTips ------------////
Ext.QuickTips.init();
////------------ Declarar variables ------------////
var chooser,fp,idusuario = 0;
		
////////------------ Area de validaciones ------------////
		
letrasnumeros = /(^([a-zA-ZáéíóúñÑ]?)+([a-zA-ZáéíóúñÑ\d\.\-\@\#\_\s]*))$/;
function cargarInterfaz()
{
    btnEntrenar = new Ext.Button({
        disabled:true,
        id:'btnEntrenarIm', 
        // hidden:true,
        icon:perfil.dirImg+'modificar.png', 
        iconCls:'btn', 
        text:perfil.etiquetas.lbBtnEntrenar, 
        handler:function(){
            entrenar()
        }
    });	
       
	
    var ImageChooser = function(config){
        this.config = config;
    }

    ImageChooser.prototype = {
        // cache data by image name for easy lookup
        lookup : {},
	    
        show : function(el, callback){
            if(!this.win){
                this.initTemplates();
				
                this.store = new Ext.data.JsonStore({
                    url: this.config.url,
                    baseParams:{
                    // usuario: idusuario,
				    	   
                    },

                    root: 'images',
                    fields: [
                    'name', 'url','lastmod',
				        
                    {
                        name:'size', 
                        type: 'float'
                    },
                    // {name:'lastmod', type:'date', dateFormat:'timestamp'}
                    ],
                    listeners: {
                        //'load': {fn:function(){ this.view.select(0);lolo='sa'; }, scope:this, single:true}
                        'beforeload':{
                            fn: function(store, options){
                                store.baseParams.usuario = idusuario;
                            },
                            scope:this
                        }
                    }
                });
                //this.store.load();


                var formatSize = function(data){
                    if(data.size < 1024) {
                        return data.size + " bytes";
                    } else {
                        return (Math.round(((data.size*10) / 1024))/10) + " KB";
                    }
                };
				
                var formatData = function(data){
                    data.shortName = data.name.ellipse(15);
                    data.sizeString = formatSize(data);
                    data.dateString = new Date(data.lastmod).format("m/d/Y g:i a");
                    this.lookup[data.name] = data;
                    return data;
                };
				
                this.view = new Ext.DataView({
                    tpl: this.thumbTemplate,					
                    singleSelect: true,
                    overClass:'x-view-over',
                    itemSelector: 'div.thumb-wrap',
                    emptyText : perfil.etiquetas.lbMsgNoImages ,
                    store: this.store,
                    listeners: {
                        'selectionchange': {
                            fn:this.showDetails, 
                            scope:this, 
                            buffer:100
                        },
                        //'dblclick'       : {fn:this.doCallback, scope:this},
                        'loadexception'  : {
                            fn:this.onLoadException, 
                            scope:this
                        },
                        'beforeselect'   : {
                            fn:function(view){
                                return view.store.getRange().length > 0;
                            }
                        }
                    },
                    prepareData: formatData.createDelegate(this)
                });
			    
                var cfg = {
                    title: perfil.etiquetas.lbBtnImg,
                    id: 'img-chooser-dlg',
                    layout: 'border',
                    resizable:false,
                    closable: false,
                    minWidth: 500,
                    minHeight: 300,
                    modal: true,
                    closeAction: 'hide',
                    border: false,
                    items:[{
                        id: 'img-chooser-view',
                        region: 'center',
                        autoScroll: true,
                        items: this.view,
                        tbar:[{
                            text: perfil.etiquetas.lbBuscar
                        },{
                            xtype: 'textfield',
                            id: 'filter',
                            selectOnFocus: true,
                            width: 100,
                            listeners: {
                                'render': {
                                    fn:function(){
                                        Ext.getCmp('filter').getEl().on('keyup', function(){
                                            this.filter();
                                        }, this, {
                                            buffer:500
                                        });
                                    }, 
                                    scope:this
                                }
                            }
                        }, ' ', '-', {
                            text: perfil.etiquetas.lbOrdenar
                        }, {
                            id: 'sortSelect',
                            xtype: 'combo',
                            typeAhead: true,
                            triggerAction: 'all',
                            width: 100,
                            editable: false,
                            mode: 'local',
                            displayField: 'desc',
                            valueField: 'name',
                            lazyInit: false,
                            value: 'name',
                            store: new Ext.data.SimpleStore({
                                fields: ['name', 'desc'],
                                data : [['name', perfil.etiquetas.lbNombre],['size', perfil.etiquetas.lbTamano],['lastmod', perfil.etiquetas.lbFecha]]
                            }),
                            listeners: {
                                'select': {
                                    fn:this.sortImages, 
                                    scope:this
                                }
                            }
                        }]
                    },{
                        id: 'img-detail-panel',
                        region: 'east',
                        split: true,
                        width: 150,
                        minWidth: 150,
                        maxWidth: 250
                    }],
                    buttons: [{
                        icon:perfil.dirImg+'cancelar.png',
                        iconCls:'btn',
                        text: perfil.etiquetas.lbBtnCancelar,
                        handler: function(){
                            borrar();
                            grid.store.reload();
                            this.reset;
                            this.win.hide();
                            grid.getSelectionModel().clearSelections(false);
                             
                        },
                        scope: this
                    },{
                        id: 'ok-btn',
                        icon:perfil.dirImg+'eliminar.png',
                        iconCls:'btn',
                        disabled:true,
                        text:perfil.etiquetas.lbBtnEliminar,
                        handler: this.borrar,
                        scope: this
                                                  
                    },{
                        icon:perfil.dirImg+'importar.png',
                        iconCls:'btn',
                        text:perfil.etiquetas.lbBtnCargar, 
                        handler: function(){
                            MostrarVentanaImagenes();
                        },
						
                        scope: this
                    }],
                    keys: {
                        key: 27, // Esc key
                        handler: function(){
                            this.win.hide();
                        },
                        scope: this
                    }
                };
                Ext.apply(cfg, this.config);
                this.win = new Ext.Window(cfg);
            }
			
            //this.reset();
            this.win.show(el);
            this.callback = callback;
            this.animateTarget = el;
        },
		
        initTemplates : function(){
            this.thumbTemplate = new Ext.XTemplate(
                '<tpl for=".">',
                '<div class="thumb-wrap" id="{name}">',
                '<div class="thumb"><img src="{url}" title="{name}"></div>',
                '<span>{shortName}</span></div>',
                '</tpl>'
                );
            this.thumbTemplate.compile();
			
            this.detailsTemplate = new Ext.XTemplate(
                '<div class="details">',
                '<tpl for=".">',
                '<img src="{url}"><div class="details-info">',
                '<b>Nombre de la imagen:</b>',
                '<span>{name}</span>',
                '<b>Tamaño:</b>',
                '<span>{sizeString}</span>',
                '<b>Fecha:</b>',
                '<span>{lastmod}</span></div>',
                '</tpl>',
                '</div>'
                );
            this.detailsTemplate.compile();
        },
		
        showDetails : function(){
            var selNode = this.view.getSelectedNodes();
            var detailEl = Ext.getCmp('img-detail-panel').body;
		  
            if(selNode && selNode.length > 0){
                selNode = selNode[0];
                Ext.getCmp('ok-btn').enable();
                var data = this.lookup[selNode.id];
                detailEl.hide();
                this.detailsTemplate.overwrite(detailEl, data);
                detailEl.slideIn('l', {
                    stopFx:true,
                    duration:.2
                });
            }else{
                Ext.getCmp('ok-btn').disable();
                detailEl.update('');
            }
        },
		
        filter : function(){
            var filter = Ext.getCmp('filter');
            this.view.store.filter('name', filter.getValue());
            this.view.select(0);
        },
		
        sortImages : function(){
            var v = Ext.getCmp('sortSelect').getValue();
            this.view.store.sort(v, v == 'name' ? 'asc' : 'desc');
            this.view.select(0);
        },
		
        borrar : function(){
            mostrarMensaje(2,perfil.etiquetas.lbEliminarimg,elimina);
            var selNode = this.view.getSelectedNodes();
            selNode = selNode[0];
            var data = this.lookup[selNode.id];
            var target = Ext.get('target');
            function elimina(btnPresionado){
                if (btnPresionado == 'ok'){
			
			
                    Ext.Ajax.request({
                        url: 'eliminarimagen',
                        params:{
                            img:data.name,
                            usuario:idusuario
                        },				
				 
                        callback: function (options,success,response)
                        {
                            responseData = Ext.decode(response.responseText);
                            if(responseData.bien == 1)
                            {
                                mostrarMensaje(responseData.bien,perfil.etiquetas.lbImgEliminada);	  
                                chooser.store.reload();
                            }
	        
                        }
                    });	
					

                }
            }			
			
        },
		
        doCallback : function(){
            var selNode = this.view.getSelectedNodes()[0];
            var callback = this.callback;
            var lookup = this.lookup;
            this.win.hide(this.animateTarget, function(){
                if(selNode && callback){
                    var data = lookup[selNode.id];
                    callback(data);
                }
            });
        },
		
        onLoadException : function(v,o){
            this.view.getEl().update('<div style="padding:10px;">Error loading images.</div>'); 
        }
    };





		
    String.prototype.ellipse = function(maxLength){
        if(this.length > maxLength){
            return this.substr(0, maxLength-3) + '...';
        }
        return this;
    };
	
	
	
	
	
	
	
	
	
	
	
	
	
    ////------------ Store del Grid de usuarios ------------////
    var storeGrid =  new Ext.data.Store({
        url: 'cargarusuario',
        //				listeners:{
        //					load:function(st, object){
        //						if(st.getCount()!=0) cantrecords = st.getCount();
        //						
        //					}
        //			
        //				},
        reader:new Ext.data.JsonReader({
            totalProperty: "cantidad_filas",
            root: "datos",
            id: "idusuario"
        },
        [
        {
            name:'nombreusuario',
            mapping:'nombreusuario'
        },

        {
            name:'idusuario',
            mapping:'idusuario'
        },

        {
            name:'estado',
            mapping:'estado'
        },

        {
            name:'nimg',
            mapping:'nimg'
        },
			            	

        ])
    });
		
    ////------------ Modo de seleccion del grid de usuarios ------------////
    sm = new Ext.grid.RowSelectionModel({
        singleSelect:false
    });
    sm.on('beforerowselect', function (smodel, rowIndex, keepExisting, record){
        idusuario= record.data.idusuario;
		            
        // MostrarVentanaImagenes();
            
        choose();
        chooser.store.reload();
			
    //MostrarVentanaImagenes()
			
    }, this);

    sm.on('rowselect', function (smodel, rowIndex, keepExisting, record){
			
			
        }, this);
		
		
    ////------------ Creando el Grid de usuarios ------------////
	
    var grid = new Ext.grid.GridPanel({
        region:'center',
        frame:true,
        iconCls:'icon-grid',
        autoExpandColumn:'expandir',
        margins:'2 2 2 -4',                        
        store:storeGrid,
        sm:sm,
        columns: [
        {
            header: perfil.etiquetas.lbUsuario, 
            width:500, 
            dataIndex: 'nombreusuario',
            id:'expandir'
        },

        {
            header: perfil.etiquetas.lbNimg, 
            width:300, 
            dataIndex: 'nimg',
            id:'expandir'
        }
        ],
        loadMask:{
            store:storeGrid
        },
        tbar:[
        new Ext.Toolbar.TextItem({
            text:perfil.etiquetas.lbTitDenBuscar
        }),
        nombreusuario = new Ext.form.TextField({
            width:90, 
            id: 'usuario',
            maxLength:50,
            regex:letrasnumeros, 
            maskRe:letrasnumeros
        }),
		  
        // new Ext.menu.Separator(),
        new Ext.Button({
            icon:perfil.dirImg+'buscar.png',
            iconCls:'btn',
            text:perfil.etiquetas.lbBtnBuscar, 
            handler:function(){
                buscarnombreusuario(nombreusuario.getValue());
            }
        })
            
        ],
        bbar:new Ext.PagingToolbar({
            pageSize: 15,
            id:'ptbaux',
            store: storeGrid,
            displayInfo: true,
            displayMsg: perfil.etiquetas.lbTitMsgNingunresultadoparamostrar,
            emptyMsg: perfil.etiquetas.lbTitMsgResultados
        })

    });
    grid.getView().getRowClass = function(record, index, rowParams, store) {
        if (record.data.estado == 1)
            return 'FilaRoja';
    };
    ////------------ Trabajo con el PagingToolbar grid usuarios ------------////
    Ext.getCmp('ptbaux').on('change',function(){
        //sm.selectFirstRow();
        },this)
		
    var panel = new Ext.Panel({
        title:perfil.etiquetas.lbgestionar,
        layout:'border',
        id:'pepe',
        renderTo:'panel',                     
        items:[grid],
        tbar:[btnEntrenar]
				
    });
			
    ////------------ Viewport ------------////
    //  function crearViewport(){
    vpGestSistema = new Ext.Viewport({
        layout:'fit',
        items:panel
    })
    //}
				
    storeGrid.load({
        params:{
            start:0,
            limit:15
        }
    });
	                         
			
    storeGrid.on('load', function(){
                            
                           
        if(storeGrid.getCount() != 0){
            btnEntrenar.enable(); 
        }
        else
        {
            btnEntrenar.disable();
            btnEntrenar.hide(); 
           
        }
    });	                           
	                           
	                           
	                           
	                           
	                      

    function buscarnombreusuario(nombreusuario){
	                     	                       	   
        storeGrid.baseParams = {};
        storeGrid.baseParams.nombreusuario = nombreusuario;		
        storeGrid.load({
            params:{
                start:0,
                limit:20
            }
        });
	                     		
    }
	                       	
	                       	
	                     
	                        
    function choose(){
        if(!chooser){
            chooser = new ImageChooser({
                url:'getimages',
                width:600, 
                height:400
            });
        }
        chooser.show();
    };

	                        
    fp = new Ext.FormPanel({        
        fileUpload: true,
        width: 320,
        frame: true,    
        height:80,
        autoheight:true,
        bodyStyle: 'padding: 5px 10px 0 10px;',
        labelWidth: 60,
        defaults: {
            anchor: '100%'
           // msgTarget: 'side'
        },
        items: [
	                    		
	                    		
        {
            xtype: 'fileuploadfield',
            id: 'form-file1',
            emptyText:perfil.etiquetas.lbSelim,
            fieldLabel:perfil.etiquetas.lbImage,
            name: 'photo',
            buttonCfg: {
                text: '',
                icon:perfil.dirImg+'buscar.png',
                iconCls: 'btn'
            }
        }	                    		
        ],
        buttons: [{
            icon:perfil.dirImg+'cancelar.png',
            iconCls:'btn',
            text: perfil.etiquetas.lbBtnCancelar,
            handler: function(){
                winins.hide();
                                             
            }

        },{
            icon:perfil.dirImg+'aceptar.png',
            iconCls:'btn',
            text:perfil.etiquetas.lbBtnAplicar,
            handler: function(){
                guardar();
                                      
	                            	
            }
        },
        {
            icon:perfil.dirImg+'aceptar.png',
            iconCls:'btn',
            text:perfil.etiquetas.lbBtnAceptar,
            handler: function(){
                guardar();
                winins.hide();
                                      
	                            	
            }
        }]
    });
    function MostrarVentanaImagenes(){
	                              
        winins =new Ext.Window({
            modal: true,
            title:perfil.etiquetas.lbGuardaImagen,
            width:330,
            height:100,
            resizable:false,
            closable: false,		
            items:fp
        }); 
        winins.doLayout();      	
        winins.show();
    }
	                    	   
    function guardar()
    {
        fp.getForm().submit({
            url:'guardar',
            params:{
                usuario:idusuario
            },
            failure: function(form, action)
            {
                responseData = Ext.decode(action.response.responseText);
                 
                if(responseData.bien == 3)
                {
                    mostrarMensaje(responseData.bien,perfil.etiquetas.lbGuardar);	  
	      
               
                }
                else if(responseData.bien == 2){
                    mostrarMensaje(1,perfil.etiquetas.lbImagenGuardada);
                    chooser.store.reload();
                }
                else if(responseData.bien == 1){
                    mostrarMensaje(3,perfil.etiquetas.lbdebeSerImg);
                   
                }
                else if(responseData.bien == 4){
                    mostrarMensaje(3,perfil.etiquetas.lbExisteImg);
                   
                }
	       
            }
	    
        } ); 
	    
	    

       


	     
	                    		
    }
           
           
    function borrar(){
        Ext.Ajax.request({
            url: 'borrardir',				
            params:{
                usuario:'hgh'
            }
        });
    }
    function entrenar()
    {
        winProgress = new Normalizar.winProgress ({
            scope : this
        });

        winProgress.show ();

        var a = 4;
        winProgress.cant = a;
        winProgress.rest = 5;


	       
	          
        t=  setInterval(function() {
            winProgress.update();
            a++;
           
            if(a > 9){
                                
        }

        }, 500);  
                    
        
        	
        Ext.Ajax.request({
            url: 'entrenamiento',
			  			  
            callback: function (options,success,response){
                clearInterval(t);
                winProgress.hide ()                           
                responseData = Ext.decode(response.responseText);
                                
                if(responseData.bien == 1)
                    mostrarMensaje(responseData.bien,perfil.etiquetas.lbMsgEntrenam);
                else if(responseData.bien == 2)
                    mostrarMensaje(3,perfil.etiquetas.lbMsgNolibrary);    
                else if(responseData.bien == 3)
                    mostrarMensaje(responseData.bien,perfil.etiquetas.lbMsgNoImg);    
                              
                              
                              
            }
        });




    }

        
    

}

