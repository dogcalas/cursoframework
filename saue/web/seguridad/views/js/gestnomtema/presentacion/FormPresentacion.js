Ext.define('Ext.form.EditorPresentacion',{
	extend:'Ext.form.Panel',
	alias:'widget.EditorPresentacion',
	fileUpload:true,  
    title: 'Opciones',
    id:'presentacionEditor',
    collapsible: true,
	labelAlign: 'top',
	frame:true,
    collapseDirection:'left',
    region:'west',
	bodyStyle:'padding:5px 5px 0',
	width: 250,
		
    autoScroll:true,
   
	cmb_ventanas: null,
	createCmb_ventanas:function(){
		return Ext.create('Ext.form.ComboVentanas', {fieldLabel:perfil.etiquetas.extTheme});
	},
	
	cmb_iconos: null,
	createCmb_iconos:function(){
		return Ext.create('Ext.form.ComboIconos', {fieldLabel:perfil.etiquetas.iconsSet});
	},
	
	createFileAnimacion:function(){
		return new Ext.form.field.CargaArchivos({
		id: 'idanimacion',
		fieldLabel: perfil.etiquetas.iconAnimate,
		qtip:'<span style="color:red">(.GIF)</span>',
		name: 'carg',
	});
	},
	createDescript:function(){
		return new Ext.form.field.TextArea({
													
													fieldLabel:perfil.etiquetas.lbFLDescripcion,
													
													 labelAlign: 'top',
													anchor:'100%',
													name:'descripcion'
											  })
	},
	createName:function(){
		return new Ext.form.field.Text({			fieldLabel:perfil.etiquetas.lbFLDenominacion,
												
													allowBlank:false,
                                                    maxLength:40,    
							            			blankText:perfil.etiquetas.lbMsgBlank,
							            			regex:tipos,
													regexText:perfil.etiquetas.lbMsgregexI,
													anchor:'95%',
													 labelAlign: 'top',
													 name:'denominacion'
											  })
	},
	createFileIcons:function(){
		return new Ext.form.field.CargaArchivos({
		id: 'idimporticons',
		fieldLabel: perfil.etiquetas.labelImport,
		qtip:'<span style="color:red">(.ZIP)</span>',
		name: 'importIcons',
	});
	},
	createFileTemas:function(){
		return new Ext.form.field.CargaArchivos({
		
		fieldLabel: perfil.etiquetas.labelImportTema,
		qtip:'<span style="color:red">(.ZIP)</span>',
		name: 'importExt',
	});
	},
	createFileFondo:function(){
		return new Ext.form.field.CargaArchivos({
		id:'idfondoPre',
		 fieldLabel: perfil.etiquetas.fondoEntrada,
		 qtip:'<span style="color:red">(.JPG .PNG)</span>',
            name: 'entrada',
	});
	},
	createFileLogo:function(){
		return new Ext.form.field.CargaArchivos({
		
		 fieldLabel: perfil.etiquetas.logo,
		 qtip:' <span style="color:red">(.JPG .PNG)</span> ',
         name: 'pre_logo'
	});
	},
  
	initComponent: function () {
		var me=this;
		this.setTitle(perfil.etiquetas.options);
		this.cmb_ventanas=this.createCmb_ventanas();
		this.cmb_iconos=this.createCmb_iconos();
		this.fileAnimacion=this.createFileAnimacion();
		this.fileFondo=this.createFileFondo();
		this.fileIcons=this.createFileIcons();
		this.fileTemas=this.createFileTemas();
		this.fileLogo=this.createFileLogo();
		this.descript=this.createDescript();
		this.name=this.createName();
		
		me.items=[{
        xtype:'fieldset',
       
        title: perfil.etiquetas.ventIcons,
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
							items:[me.cmb_ventanas,me.fileTemas,me.cmb_iconos,me.fileIcons]
				}   ]
		

        },{
        xtype:'fieldset',
       
        title: perfil.etiquetas.lbDesktopFieldSetImagenes,
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
							items:[me.fileAnimacion,me.fileFondo,me.fileLogo]
				}]
		

        },{
        xtype:'fieldset',
       
        title: perfil.etiquetas.lbFieldSetStaticVar,
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
							items:[me.name,me.descript]
				}]
		

        }];
    
        me.callParent();
		
	}
	
	});
