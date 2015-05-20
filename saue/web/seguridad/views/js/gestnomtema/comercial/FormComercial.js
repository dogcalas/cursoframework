
Ext.define('Ext.form.EditorComercial',{
	extend:'Ext.form.Panel',
	alias:'widget.EditorComercial',
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
    //declaracion de objetos
    
    
	createNavegacion:function(){
		return new Ext.form.ColorInicio({
		fieldLabel: perfil.etiquetas.navButton,
		
		 name:'nav_background'
	});
	},
	
	
	createOverNavegacion:function(){
		return new Ext.form.ColorInicio({
		fieldLabel: perfil.etiquetas.Foco,
		
		name:'nav_hover'
	});
	},
	
	
	createColorTextoNav:function(){
		return new Ext.form.ColorInicio({
		fieldLabel: perfil.etiquetas.text,
		
		name:'nav_color'
	});
	},
	
	
	createColorTextoNavHover:function(){
		return new Ext.form.ColorInicio({
		fieldLabel: perfil.etiquetas.Foco,
		
		name:'nav_color_hover'
	});
	},
	
	
	//texto Slogan
	
	
	createColorTextoSlogan:function(){
		return new Ext.form.ColorInicio({
		fieldLabel: perfil.etiquetas.textColor,
		hideLabel:true,
		name:'slogan_color'
	});
	},
	
	
	createComboSize:function(){
		return new Ext.form.ComboSize({name:'slogan_size'});
	},
	
	
	createComboTipografias:function(){
		return new Ext.form.ComboTipografias({name:'slogan_font'});
	},
  //Bloques de contenido
	
	createColorFondoBloques:function(){
		return new Ext.form.ColorInicio({
		fieldLabel: perfil.etiquetas.fondo,
		
		name:'bloq_header_background'
	});
	},
	
	
	createColorCabeceraBloques:function(){
		return new Ext.form.ColorInicio({
		fieldLabel: perfil.etiquetas.text,
		
		name:'bloq_header_color'
	});
	},
	
	
	createColorTextoBloques:function(){
		return new Ext.form.ColorInicio({
		fieldLabel: perfil.etiquetas.text,
		hideLabel:true,
		name:'bloq_color'
	});
	},
	
	
	createSizeTextoBloques:function(){
		return new Ext.form.ComboSize({name:'bloq_size'});
	},
	
	
	createTipografiaTextoBloques:function(){
		return new Ext.form.ComboTipografias({name:'bloq_font'});
	},
	
	//carga de imagenes
	
	createFileSlogan:function(){
		return new Ext.form.field.CargaArchivos({
		id: 'file-slogan-comercial',
		fieldLabel: perfil.etiquetas.sloganEntidad,
		qtip: '<span style="color:red">(.JPG .PNG)</span>',
		name: 'slogan_comercial',
	});
	},
	
	
	createFileFondo:function(){
		return new Ext.form.field.CargaArchivos({
		id: 'file-fondo-comercial',
		fieldLabel: perfil.etiquetas.fondoPagina,
		qtip: '<span style="color:red">(.JPG .PNG)</span>',
		name: 'fondo_comercial',
	});
	},
	
	
	createFileBanner:function(){
		return new Ext.form.field.CargaArchivos({
		id: 'file-banner-comercial',
		fieldLabel: perfil.etiquetas.baner,
		qtip: '<span style="color:red">(.JPG .PNG)</span>',
		name: 'banner_comercial',
	});
	},
	
	
	createFormatoSlogan:function(){
		return new Ext.FormatButton({nameB:'slogan_bold',nameI:'slogan_italic',nameU:'slogan_underline'});
	},
	
	createFormatoBloques:function(){
		return new Ext.FormatButton({nameB:'bloq_bold',nameI:'bloq_italic',nameU:'bloq_underline'});
	},
	
	//pagina
	
	createBarraBackground:function(){
		return new Ext.form.ColorInicio({
		fieldLabel:  perfil.etiquetas.barColor,
		
		name:'bar_background'
	});
	},
	
	
	createBodyBackground:function(){
		return new Ext.form.ColorInicio({
				fieldLabel: perfil.etiquetas.bodyColor,
				
				name:'body_background'
			});
	},
	
	
	createBarraOpacity :function(){
		return Ext.create('Ext.slider.Single', {
				width: '50%',
				value: 50,
				increment: 5,
				minValue: 20,
				maxValue: 100,
				name:'bar_opacity',
				fieldLabel: perfil.etiquetas.lbDesktopOpacid,
				labelAlign:'top',
				 margin:'0 5 5 5'
				
			});
	},
	
	
	createBodyOpacity:function(){
		return Ext.create('Ext.slider.Single', {
				width: '50%',
				value: 50,
				increment: 5,
				minValue: 20,
				maxValue: 100,
				name:'body_opacity',
				fieldLabel: perfil.etiquetas.lbDesktopOpacid,
				labelAlign:'top',
				margin:'0 5 5 5'
				
			});
	},

//texturas
	
	createColorPie:function(){
		return new Ext.form.ColorInicio({
			fieldLabel: perfil.etiquetas.footer,
			
			name:'footer_background'
		});
	},
	
	
	createTexturaBoton:function(){
		return new Ext.form.ComboTexturas({name:'nav_color_texture'});
	},

	
	createCheckTexture:function(){
		return Ext.create('Ext.form.field.Checkbox',{
				fieldLabel: perfil.etiquetas.texture,
				labelAlign:'top',
				name:'nav_texture'
				});
	},

    //Propertys
	bold:null,
	italic:null,
	underline:null,
	
	boldBloques:null,
	italicBloques:null,
	underlineBloques:null,
	navegacion:null,
    overNavegacion:null,
	colorTextoNav:null,
	colorTextoNavHover:null,
	colorTextoSlogan:null,
	comboSize:null,
	comboTipografias:null,
	colorFondoBloques:null,
	colorCabeceraBloques:null,
	colorTextoBloques:null,
	sizeTextoBloques:null,
	TipografiaTextoBloques:null,
	fileSlogan:null,
	fileFondo:null,
	fileBanner:null,
	formatoSlogan:null,
	formatoBloques:null,
	barraBackground:null,
	bodyBackground:null,
	barraOpacity:null,
	bodyOpacity:null,
	colorPie:null,
	texturaBoton:null,
	checkTexture:null,
	//methods......
	
	actualizar:function(){
		var me=this;
		me.fireEvent('selected',me);
	},
	
	initComponent:function(){
		var me=this;
		this.setTitle(perfil.etiquetas.options)
		this.navegacion=this.createNavegacion();
		this.overNavegacion=this.createOverNavegacion();
		this.colorTextoNav=this.createColorTextoNav();
		this.colorTextoNavHover=this.createColorTextoNavHover();
		this.colorTextoSlogan=this.createColorTextoSlogan();
		this.comboSize=this.createComboSize();
		this.comboTipografias=this.createComboTipografias();
		this.colorFondoBloques=this.createColorFondoBloques();
		this.colorCabeceraBloques=this.createColorCabeceraBloques();
		this.colorTextoBloques=this.createColorTextoBloques();
		this.sizeTextoBloques=this.createSizeTextoBloques();
		this.TipografiaTextoBloques=this.createTipografiaTextoBloques();
		this.fileSlogan=this.createFileSlogan();
		this.fileFondo=this.createFileFondo();
		this.fileBanner=this.createFileBanner();
		this.formatoSlogan=this.createFormatoSlogan();
		this.formatoBloques=this.createFormatoBloques();
		this.barraBackground=this.createBarraBackground();
		this.bodyBackground=this.createBodyBackground();
		this.barraOpacity=this.createBarraOpacity();
		this.bodyOpacity=this.createBodyOpacity();
		this.colorPie=this.createColorPie();
		this.texturaBoton=this.createTexturaBoton();
		this.checkTexture=this.createCheckTexture();
		
		
		this.addEvents('selected');
	
	
	me.navegacion.on('select',function(){me.actualizar()},this);
	me.overNavegacion.on('select',function(){me.actualizar()},this);
	me.colorTextoSlogan.on('select',function(){me.actualizar()},this);
	
	me.barraBackground.on('select',function(){me.actualizar()},this);
	me.bodyBackground.on('select',function(){me.actualizar()},this);
	me.barraOpacity.on('change',function(){me.actualizar()},this);
	me.bodyOpacity.on('change',function(){me.actualizar()},this);
	
	me.colorFondoBloques.on('select',function(){me.actualizar()},this);
	me.colorCabeceraBloques.on('select',function(){me.actualizar()},this);
	me.colorTextoBloques.on('select',function(){me.actualizar()},this);
	me.sizeTextoBloques.on('select',function(){me.actualizar()},this);
	me.TipografiaTextoBloques.on('select',function(){me.actualizar()},this);
	
	me.colorTextoNav.on('select',function(){me.actualizar()},this);
	me.comboTipografias.on('select',function(){me.actualizar()},this);
	me.comboSize.on('select',function(){me.actualizar()},this);
		
	me.formatoSlogan.on('buttontoggle',function(){me.actualizar()},this);
	me.formatoBloques.on('buttontoggle',function(){me.actualizar()},this);
		
	me.checkTexture.on('change',function($this, newValue, oldValue){
		if(newValue){
			me.getComponent('setbarra').add(me.texturaBoton);
			me.getComponent('setbarra').doLayout();
		}else{
			me.getComponent('setbarra').remove(me.texturaBoton,false);
			me.getComponent('setbarra').doLayout();
		}
		
	},this)
	
		
		me.items=[{
        xtype:'fieldset',
       
        title: perfil.etiquetas.slogan,
        collapsible: true,
        autoHeight:true,
        
        
		items:[
						{
							layout:'vbox',
							margin:'5 5 5 5',
                             border:0,
                            width: '100%',
							
							items:[
							
							{
							layout:'hbox',
							margin:'5 5 5 5',
                             border:0,
                            width: '100%',
							
							items:[
							me.colorTextoSlogan,me.comboSize
							]},
							
							{
							layout:'hbox',
							margin:'5 5 5 5',
                             border:0,
                             width: '100%',
                             items:[me.formatoSlogan,me.comboTipografias]}
                             ]
                             }
                             ]
                             },
                             
                             {
        xtype:'fieldset',
       
        title: perfil.etiquetas.barNav,
        collapsible: true,
        autoHeight:true,
        id:'setbarra',
        
		items:[
						{
							layout:'hbox',
							margin:'5 5 5 5',
                             border:0,
                            width: '100%',
							
							items:[
							me.navegacion,me.overNavegacion
							]},
							{
							layout:'hbox',
							margin:'5 5 5 5',
                             border:0,
                            width: '100%',
							
							items:[
							me.colorTextoNav,me.colorTextoNavHover
							]},
							{
							layout:'vbox',
							margin:'5 5 5 5',
                             border:0,
                            width: '100%',
							
							items:[
							me.checkTexture
							]}
                             ]
		},
		
		{
        xtype:'fieldset',
       
        title: perfil.etiquetas.bloques,
        collapsible: true,
        autoHeight:true,
        
        
		items:[
		new Ext.form.Label({html:'<p style="font-size:12px;bold:12px"><b>'+perfil.etiquetas.headerBloq+':</b></p>',layout:'form',columnWidth:.9,height:25}),
						{
							layout:'hbox',
							margin:'5 5 5 5',
                             border:0,
                            width: '100%',
							
							items:[
							me.colorFondoBloques,me.colorCabeceraBloques
							]},new Ext.form.Label({html:'<p style="font-size:12px;bold:12px"><b>'+perfil.etiquetas.headerFont+':</b></p>',layout:'form',columnWidth:.9,height:25}),{
							layout:'hbox',
							margin:'5 5 5 5',
                             border:0,
                            width: '100%',
							
							items:[
							me.colorTextoBloques,me.sizeTextoBloques
							]},
							{layout:'hbox',
							margin:'5 5 5 5',
                             border:0,
                             width: '100%',
                             items:[me.formatoBloques,me.TipografiaTextoBloques]
                             }
		
		]
		},
		{
        xtype:'fieldset',
       
        title: perfil.etiquetas.pagina,
        collapsible: true,
        autoHeight:true,
        
        
		items:[
						{
							layout:'hbox',
							margin:'5 5 5 5',
                             border:0,
                            width: '100%',
							
							items:[
							me.barraBackground,me.barraOpacity
							]},
							{
							layout:'hbox',
							margin:'5 5 5 5',
                             border:0,
                            width: '100%',
							
							items:[
							me.bodyBackground,me.bodyOpacity
							]}
							
                             ]
		},
		{
        xtype:'fieldset',
       
        title: perfil.etiquetas.footerTitle,
        collapsible: true,
        autoHeight:true,
        
        
		items:[
						{
							layout:'vbox',
							margin:'5 5 5 5',
                             border:0,
                            width: '100%',
							
							items:[
							me.colorPie
							]}
							
                             ]
		},{
        xtype:'fieldset',
       
        title: perfil.etiquetas.imagenes,
        collapsible: true,
        autoHeight:true,
        
        
		items:[
						{
							layout:'vbox',
							margin:'5 5 5 5',
                             border:0,
                            width: '100%',
							
							items:[
							me.fileFondo,me.fileSlogan,me.fileBanner
							]}
							
                             ]
		}
		
		];
		this.callParent();
	}
});

