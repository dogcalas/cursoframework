
	Ext.define('Ext.form.EditorDesktop',{
	extend:'Ext.form.Panel',
	alias:'widget.EditorDesktop',
	fileUpload:true,  
    title: 'Opciones',
    collapsible: true,
	labelAlign: 'top',
	frame:true,
    collapseDirection:'left',
    region:'west',
	bodyStyle:'padding:5px 5px 0',
	width: 250,
		
    autoScroll:true,
	
	
	//Propertys
	bold:null,
	italic:null,
	underline:null,
	radioSelected:null,
	
    initComponent:function(){
	var me=this;
	this.setTitle(perfil.etiquetas.options)
	
	this.barraTarea=this.createBarraTarea();
	this.tarea=this.createTarea();
	this.inicio=this.createInicio();
	this.overInicio=this.createOverInicio();
	this.colorTextoInicio=this.createColorTextoInicio();
	this.radios=this.createRadios();
	this.fileFondo=this.createFileFondo();
    this.fileIcono=this.createFileIcono();
	this.fileSlogan=this.createFileSlogan();
	this.comboSize=this.createComboSize();
	this.comboTipografias=this.createComboTipografias();
	this.headerMenu=this.createHeaderMenu();
	this.dockMenu=this.createDockMenu();
	this.dockMenuOpacity=this.createDockMenuOpacity();
	
	
	
	me.addEvents('selected');
	me.radios.on('seleccionado',function(ra,value){
		me.radioSelected=value;
		
		me.actualizar();
	});
	me.radios.radio1.on('show',function(ra,value){
		if(this.radioSelected)
			me.radios.radio1.setRawValue(false);
			else
			me.radios.radio1.setRawValue(true);
		alert(this.radioSelected+' 1')	
	},this);
	me.radios.radio2.on('show',function(ra,value){
		if(this.radioSelected)
			me.radios.radio2.setRawValue(true)
			else
			me.radios.radio2.setRawValue(false)
			alert(this.radioSelected+' 2')
	},this);
	me.barraTarea.on('select',function(){me.actualizar()},this);
	me.tarea.on('select',function(){me.actualizar()},this);
	me.inicio.on('select',function(){me.actualizar()},this);
	me.overInicio.on('select',function(){me.actualizar()},this);
	me.colorTextoInicio.on('select',function(){me.actualizar()},this);
	me.comboTipografias.on('select',function(){me.actualizar()},this);
	me.comboSize.on('select',function(){me.actualizar()},this);
	me.headerMenu.on('select',function(){me.actualizar()},this);
	me.dockMenu.on('select',function(){me.actualizar()},this);
	me.dockMenuOpacity.on('change',function(){me.actualizar()},this);
	
	
	me.items=[{
        xtype:'fieldset',
       
        title: perfil.etiquetas.lbDesktopFieldSetBarTarea,
        collapsible: true,
        autoHeight:true,
        
        
		items:[{
							layout:'vbox',
							margin:'5 5 5 5',
                             border:0,
                             anchor:'100%',
							items:[new Ext.form.Label({html:'<p style="font-size:12px;bold:12px"><b>'+perfil.etiquetas.lbDesktopBarra+':</b></p>',layout:'form',columnWidth:.9,height:25}),{
										
										margin:'5 5 0 0',
										width: '100%',
                                        border:0,
										layout:'vbox',
										labelAlign: 'top',
										items:[me.radios]
								   },me.barraTarea,
								   
								   me.tarea]
						}
                                                
                           ]
		

        },{
        xtype:'fieldset',
       
        title: perfil.etiquetas.lbDesktopFieldSetBotInicio,
        collapsible: true,
        autoHeight:true,
        
        defaults: {
          // leave room for error icon
        },
		items:[{
							layout:'vbox',
							margin:'5 5 5 5',
                             border:0,
                             anchor:'100%',
							items:[
							
						{
							layout:'hbox',
							margin:'5 5 5 5',
                             border:0,
                            width: '100%',
						
							items:[
							me.inicio,me.overInicio
							]},new Ext.form.Label({html:'<p style="font-size:12px;bold:12px"><b>'+perfil.etiquetas.lbDesktopFuente+':</b></p>',layout:'form',columnWidth:.9,height:25}),
							{
							layout:'hbox',
							margin:'5 5 5 5',
                             border:0,
                            width: '100%',
							
							items:[
							me.colorTextoInicio,me.comboSize
							]},
							{layout:'hbox',
							margin:'5 5 5 5',
                             border:0,
                             width: '100%',
                             items:[{
            xtype: 'buttongroup',
            columns: 3,
           // title: 'Tipo',
          // margin:'15 15 0 0',
            width:'50%',
            items: [{
                text: '<b >N</b>',
                //scale: 'large',
                id:'Neg',
                rowspan: 1,
                height:20,
                width:20,
               // iconCls: 'add',
                iconAlign: 'top',
               // cls: 'x-btn-as-arrow',
                enableToggle:true,
                toggleHandler:function(b,st){
					me.bold=st;
					me.actualizar();
					//recojerDatos();
					//renderVista();
					}
            },{
              
                text: '<i style="font-weight:bold;">I</i>',
               // scale: 'large',
                rowspan: 2,
                id:'Ita',
                height:20,
                width:20,
              //  iconCls: 'add',
                iconAlign: 'top',
                enableToggle:true,
                toggleHandler:function(b,st){
					me.italic=st;
					me.actualizar();
					 //recojerDatos();
					//renderVista();
					}
               
            },{
              
                text: '<i style="text-decoration:underline;font-weight:bold;">S</i>',
               // scale: 'large',
                rowspan: 2,
                height:20,
                id:'Und',
                width:20,
              //  iconCls: 'add',
                iconAlign: 'top',
                enableToggle:true,
                toggleHandler:function(b,st){
					me.underline=st;
					me.actualizar();
					// recojerDatos();
				//renderVista();
					}
               
            }]
        },me.comboTipografias]
                             }
                             

        ]
						}
                                                
                           ]
		

        },{
        xtype:'fieldset',
       
        title: perfil.etiquetas.lbDesktopFieldSetMenuInicio,
        collapsible: true,
        autoHeight:true,
       
        
		items:[  {
							layout:'vbox',
							margin:'5 5 5 5',
                            border:0,
							items:[{
										
										
										margin:'5 5 5 5',
                                        border:0,
                                        width: '100%',
                                        
										items:[me.headerMenu]
								   }]
						},{
							layout:'hbox',
							
							margin:'5 5 5 5',
                             border:0,
                            width: '100%',
							
							items:[
							me.dockMenu,me.dockMenuOpacity
							]}
                                                
                           ]
		

        },{
        xtype:'fieldset',
       
        title: perfil.etiquetas.lbDesktopFieldSetImagenes,
        collapsible: true,
        autoHeight:true,
        
        
		items:[  {
							layout:'vbox',
							margin:'5 5 5 5',
                            border:0,
							items:[{
										
										
										margin:'5 5 5 5',
                                        border:0,
                                        
                                        
										items:[me.fileFondo,me.fileIcono,me.fileSlogan]
								   }]
						}
                                                
                           ]
		

        }]
		me.callParent();
	},
	
	actualizar:function(){
		var me=this;
		me.fireEvent('selected',me);
	},
	
	
	//barra de tareas
	createBarraTarea:function(){
		return Ext.create('Ext.form.ColorBarras',{
			id:'color1',
			fieldLabel: perfil.etiquetas.lbDesktopColorSuperior,
			hiddenName:'pref_sales',
			name:'barra_hex_sup',
	});
	},
	
	
	
	//tarea
	createTarea:function(){
		return Ext.create('Ext.form.ColorBarras',{
		fieldLabel: perfil.etiquetas.lbDesktopPestanna,
		hiddenName:'pref_sales3',
		 name:'tarea_hex'
	});
	},
	
	
	//inicio
	createInicio:function(){
		return new Ext.form.ColorInicio({
		fieldLabel: perfil.etiquetas.lbDesktopInicio,
		hiddenName:'pref_sales3',
		 name:'inicio_hex'
	});
	},
	
	
	//overInicio
	createOverInicio:function(){
		return new Ext.form.ColorInicio({
		fieldLabel: perfil.etiquetas.lbDesktopFocoIni,
		hiddenName:'pref_sales4',
		name:'inicio_fondo_hex'
	});
	},
	
	
	//colorTextoInicio
	createColorTextoInicio:function(){
		return new Ext.form.ColorInicio({
		fieldLabel: perfil.etiquetas.lbDesktopTextoIni,
		hideLabel:true,
		hiddenName:'pref_sales',
		name:'inicio_texto_hex'
	});
	},
	
	
	//radios
	createRadios:function(){
		return Ext.create('Ext.form.RadioGroupSombra');
	},
	
    
    
    
	//fileFondo
	createFileFondo:function(){
		return new Ext.form.field.CargaArchivos({
		id: 'file-fondo-desktop',
		fieldLabel: perfil.etiquetas.lbDesktopFondoEscrit,
		qtip:' <span style="color:red">(.JPG .PNG)</span> ',
		name: 'fondo',
	});
	},
	
	
	//fileIcono
	createFileIcono:function(){
		return new Ext.form.field.CargaArchivos({
		id: 'file-icono-desktop',
		fieldLabel: perfil.etiquetas.lbDesktopIconIni,
		qtip: ' <span style="color:red">(.GIF)</span> ',
		name: 'Icono',
	});
	},
	
	
	//fileSlogan
	createFileSlogan:function(){
		return new Ext.form.field.CargaArchivos({
		id: 'file-slogan-desktop',
		fieldLabel: perfil.etiquetas.lbDesktopLogOrg,
		qtip: '<span style="color:red">(.JPG .PNG)</span>',
		name: 'Slogan',
	});
	},
	
	
	//comboSize
	createComboSize:function(){
	   return 	new Ext.form.ComboSize({fieldLabel: perfil.etiquetas.lbDesktopSize,});
	},
	
	
	//comboTipografias
	createComboTipografias:function(){
		return new Ext.form.ComboTipografias();
	},
	
	
	
	//headerMenu
	createHeaderMenu:function(){
		return new Ext.form.ColorInicio({
		fieldLabel: perfil.etiquetas.lbDesktopCabMenu,
		
		 name:'menu_header'
	});
	},
	
	
	//dockMenu
	createDockMenu:function(){
		return new Ext.form.ColorInicio({
		fieldLabel: perfil.etiquetas.lbDesktopPanDer,
		
		name:'menu_dock'
	});
	},
	
	
	//dockMenuOpacity
	createDockMenuOpacity:function(){
		return Ext.create('Ext.slider.Single', {
    width: '50%',
    value: 50,
    increment: 5,
    minValue: 5,
    maxValue: 100,
    name:'menu_dock_opacity',
    fieldLabel: perfil.etiquetas.lbDesktopOpacid,
    labelAlign:'top',
   margin:'0 5 5 5'
    
	});
	}
	
	
	});

