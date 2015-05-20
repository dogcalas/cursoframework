
	Ext.define('Ext.form.EditorAutenticacion',{
	extend:'Ext.form.Panel',
	alias:'widget.EditorAutenticacion',
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
	
	
    initComponent:function(){
	var me=this;
	this.setTitle(perfil.etiquetas.options)
	
	this.idiomaSel=this.createIdiomaSel();
	this.idiomaNoSel=this.createIdiomaNoSel();
	
	this.etiquetas=this.createEtiquetas();
	this.inputFondo=this.createInputFondo();
	this.inputColor=this.createInputColor();
	this.submitFondo=this.createSubmitFondo();
	this.submitColor=this.createSubmitColor();
	this.submitFondoHover=this.createSubmitFondoHover();
	this.submitColorHover=this.createSubmitColorHover();
	
	this.barHoriz=this.createBarHoriz();
	this.fileFondo=this.createFileFondo();
	this.fileLogo=this.createFileLogo();
	this.copyrigth=this.createCopyrigth();
	this.altColor=this.createAltColor();
	
	me.addEvents('selected');
	
	
	
	
	
	
	this.idiomaSel.on('select',function(){me.actualizar()},this);
	this.idiomaNoSel.on('select',function(){me.actualizar()},this);
	
	this.etiquetas.on('select',function(){me.actualizar()},this);
	this.inputFondo.on('select',function(){me.actualizar()},this);
	this.inputColor.on('select',function(){me.actualizar()},this);
	this.submitFondo.on('select',function(){me.actualizar()},this);
	this.submitColor.on('select',function(){me.actualizar()},this);
	this.submitFondoHover.on('select',function(){me.actualizar()},this);
	this.submitColorHover.on('select',function(){me.actualizar()},this);
	this.submitColor.on('select',function(){me.actualizar()},this);
	this.barHoriz.on('select',function(){me.actualizar()},this);
	
	this.copyrigth.on('select',function(){me.actualizar()},this);
	this.altColor.on('select',function(){me.actualizar()},this);
	
	me.items=[{
        xtype:'fieldset',
       
        title: perfil.etiquetas.idiomas,
        collapsible: true,
        autoHeight:true,
        
        
		items:[{
							layout:'vbox',
							margin:'5 5 5 5',
                             border:0,
                             anchor:'100%',
							items:[me.idiomaSel,me.idiomaNoSel]
						}
                                                
                           ]
		

        },{
        xtype:'fieldset',
       
        title:perfil.etiquetas.form,
        collapsible: true,
        autoHeight:true,
        
        
		items:[  {
							layout:'vbox',
							margin:'5 5 5 5',
                            border:0,
							items:[{
										
										
										margin:'5 5 5 5',
                                        border:0,
                                        
                                        
										items:[me.etiquetas]
								   }]
						},new Ext.form.Label({html:'<p style="font-size:12px;bold:12px"><b>'+perfil.etiquetas.cmpText+':</b></p>',layout:'form',columnWidth:.9,height:25}),
						{
							layout:'hbox',
							margin:'0',
                             border:0,
                            width: '100%',
							//style:'background:red',
							items:[
							me.inputFondo,me.inputColor
							]},
							new Ext.form.Label({html:'<p style="font-size:12px;bold:12px"><b>'+perfil.etiquetas.btnEntrada+':</b></p>',layout:'form',columnWidth:.9,height:25}),
							{
							layout:'hbox',
							margin:'0',
                             border:0,
                            width: '100%',
							//style:'background:red',
							items:[
							me.submitFondo,me.submitColor
							]},
						new Ext.form.Label({html:'<p style="font-size:12px;bold:12px"><b>'+perfil.etiquetas.lbFieldSetInputHover+':</b></p>',layout:'form',columnWidth:.9,height:25}),
							{
							layout:'hbox',
							margin:'0',
                             border:0,
                            width: '100%',
							//style:'background:red',
							items:[
							me.submitFondoHover,me.submitColorHover
							]}
                                                
                           ]
		

        },{
        xtype:'fieldset',
       
        title: perfil.etiquetas.horBar,
        collapsible: true,
        autoHeight:true,
        
        
		items:[  {
							layout:'vbox',
							margin:'5 5 5 5',
                            border:0,
							items:[{
										
										
										margin:'5 5 5 5',
                                        border:0,
                                        
                                        
										items:[me.barHoriz]
								   }]
						}
                                                
                           ]
		

        },{
        xtype:'fieldset',
       
        title: perfil.etiquetas.footerTitle,
        collapsible: true,
        autoHeight:true,
        
        
		items:[  {
							layout:'vbox',
							margin:'5 5 5 5',
                            border:0,
							items:[{
										
										
										margin:'5 5 5 5',
                                        border:0,
                                        
                                        
										items:[me.copyrigth]
								   }]
						}
                                                
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
                                        
                                        
										items:[me.fileFondo,me.altColor]
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
	
	
	//idiomas
	createIdiomaSel:function(){
		return Ext.create('Ext.form.ColorBarras',{
			
			fieldLabel: perfil.etiquetas.selected,
			
			name:'idioma_sel'
	});
	},
	
	createIdiomaNoSel:function(){
		return Ext.create('Ext.form.ColorBarras',{
			
			fieldLabel: perfil.etiquetas.noSelected,
			
			name:'idioma_no_sel'
	});
	},
	
	createEtiquetas:function(){
		return Ext.create('Ext.form.ColorBarras',{
			
			fieldLabel:  perfil.etiquetas.labels,
			
			name:'etiquetas'
	});
	},
	
	createInputFondo:function(){
		return Ext.create('Ext.form.ColorBarras',{
			
			fieldLabel: perfil.etiquetas.fondoAut,
			width: '50%',
			name:'input_fondo'
	});
	},
	
	createInputColor:function(){
		return Ext.create('Ext.form.ColorBarras',{
			
			fieldLabel: perfil.etiquetas.colorAut,
			width: '50%',
			name:'input_color'
	});
	},
	
	createSubmitFondo:function(){
		return Ext.create('Ext.form.ColorBarras',{
			
			fieldLabel: perfil.etiquetas.fondoAut,
			width: '50%',
			name:'submit_fondo'
	});
	},
	
	createSubmitColor:function(){
		return Ext.create('Ext.form.ColorBarras',{
			
			fieldLabel: perfil.etiquetas.colorAut,
			width: '50%',
			name:'submit_color'
	});
	},
	
	createBarHoriz:function(){
		return Ext.create('Ext.form.ColorBarras',{
			
			fieldLabel: perfil.etiquetas.barsAut,
			
			name:'bar_horiz'
	});
	},
	
	createFileFondo:function(){
		return new Ext.form.field.CargaArchivos({
		
		fieldLabel: perfil.etiquetas.fondoAut,
		qtip:'<span style="color:red">( .JPG )</span>',
		name: 'fondo_autenticacion',
	});
	},
	
	createFileLogo:function(){
		return new Ext.form.field.CargaArchivos({
		
		fieldLabel: perfil.etiquetas.logotipo,
		
		name: 'logo',
	});
	},
	
	createCopyrigth:function(){
		return Ext.create('Ext.form.ColorBarras',{
			
			fieldLabel: perfil.etiquetas.copyright,
			
			name:'copyrigth'
	});
	},
	
	createAltColor:function(){
		return Ext.create('Ext.form.ColorBarras',{
			
			fieldLabel: perfil.etiquetas.fondoAlt,
			
			name:'altcolor'
	});
	},
	
	createSubmitFondoHover:function(){
		return Ext.create('Ext.form.ColorBarras',{
			margin:'5 0 5 0',
			fieldLabel: perfil.etiquetas.fondoAut,
			width: '50%',
			name:'submit_fondo_hover'
	});
	},
	
	createSubmitColorHover:function(){
		return Ext.create('Ext.form.ColorBarras',{
			margin:'5 0 5 0',
			fieldLabel: perfil.etiquetas.colorAut,
			width: '50%',
			name:'submit_color_hover'
	});
	}
	
	});

